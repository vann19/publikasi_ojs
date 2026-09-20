<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

// Auth check
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
}

$id = $_POST['id'] ?? null;
$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$date = trim($_POST['date'] ?? '');
$location = trim($_POST['location'] ?? '');
$link = trim($_POST['link'] ?? '');
$price = isset($_POST['price']) && $_POST['price'] !== '' ? (int)$_POST['price'] : null;
$is_active = isset($_POST['is_active']) ? 1 : 0;

if (!$id || empty($title)) {
    echo json_encode(['status' => 'error', 'message' => 'ID dan Judul seminar wajib diisi']);
    exit;
}

try {
    $pdo = getDB();
    
    // Ambil data seminar yang lama
    $stmt = $pdo->prepare("SELECT image FROM seminars WHERE id = ?");
    $stmt->execute([$id]);
    $oldSeminar = $stmt->fetch();
    
    if (!$oldSeminar) {
        echo json_encode(['status' => 'error', 'message' => 'Seminar tidak ditemukan']);
        exit;
    }
    
    $imagePath = $oldSeminar['image'];

    // Cek jika ada gambar baru diupload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../storage/seminars/';
        
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $_FILES['image']['tmp_name']);
        finfo_close($finfo);

        if (strpos($mimeType, 'image/') !== 0) {
            echo json_encode(['status' => 'error', 'message' => 'File harus berupa gambar.']);
            exit;
        }

        $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $newFileName = 'seminar_' . uniqid() . '.' . $extension;
        $destination = $uploadDir . $newFileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
            // Hapus gambar lama jika ada
            if ($imagePath && file_exists('..' . $imagePath)) {
                unlink('..' . $imagePath);
            }
            $imagePath = '/storage/seminars/' . $newFileName;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal mengupload gambar.']);
            exit;
        }
    }

    $updateStmt = $pdo->prepare("UPDATE seminars SET title=?, description=?, date=?, location=?, link=?, price=?, image=?, is_active=? WHERE id=?");
    $updateStmt->execute([$title, $description, $date ?: null, $location, $link, $price, $imagePath, $is_active, $id]);

    echo json_encode(['status' => 'success', 'message' => 'Seminar berhasil diperbarui']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()]);
}
