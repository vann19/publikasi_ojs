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

$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$date = trim($_POST['date'] ?? '');
$location = trim($_POST['location'] ?? '');
$link = trim($_POST['link'] ?? '');
$price = isset($_POST['price']) && $_POST['price'] !== '' ? (int)$_POST['price'] : null;
$is_active = isset($_POST['is_active']) ? 1 : 0;

if (empty($title)) {
    echo json_encode(['status' => 'error', 'message' => 'Judul seminar wajib diisi']);
    exit;
}

$imagePath = null;
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = '../storage/seminars/';
    
    // Validasi file (gambar saja)
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
        $imagePath = '/storage/seminars/' . $newFileName;
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal mengupload gambar.']);
        exit;
    }
}

try {
    $pdo = getDB();
    $stmt = $pdo->prepare("INSERT INTO seminars (title, description, date, location, link, price, image, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$title, $description, $date ?: null, $location, $link, $price, $imagePath, $is_active]);

    echo json_encode(['status' => 'success', 'message' => 'Seminar berhasil ditambahkan']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()]);
}
