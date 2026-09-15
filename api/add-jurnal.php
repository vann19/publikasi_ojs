<?php
require_once '../includes/auth.php';
checkAuth();
require_once '../includes/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $issn = $_POST['issn'] ?? '';
    $volume = $_POST['volume'] ?? '';
    $issue = $_POST['issue'] ?? '';
    $published_date = $_POST['published_date'] ?? null;
    $link = $_POST['link'] ?? '';
    $imagePath = null;

    if (empty($title)) {
        echo json_encode(['status' => 'error', 'message' => 'Judul jurnal wajib diisi']);
        exit;
    }

    // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['image'];
        $maxSize = 2 * 1024 * 1024; // 2 MB

        if ($file['size'] > $maxSize) {
            echo json_encode(['status' => 'error', 'message' => 'Ukuran foto maksimal 2 MB']);
            exit;
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, $allowedTypes)) {
            echo json_encode(['status' => 'error', 'message' => 'Format file tidak didukung. Gunakan JPG, PNG, WebP, atau GIF']);
            exit;
        }

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('jurnal_') . '.' . $ext;
        $uploadDir = __DIR__ . '/../storage/journals/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        if (move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
            $imagePath = '/storage/journals/' . $filename;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal mengupload foto']);
            exit;
        }
    }

    if (empty($published_date)) $published_date = null;

    $pdo = getDB();
    $stmt = $pdo->prepare("INSERT INTO journals (title, issn, volume, issue, published_date, link, image) VALUES (:title, :issn, :volume, :issue, :published_date, :link, :image)");

    $success = $stmt->execute([
        'title' => $title,
        'issn' => $issn,
        'volume' => $volume,
        'issue' => $issue,
        'published_date' => $published_date,
        'link' => $link,
        'image' => $imagePath
    ]);

    if ($success) {
        echo json_encode(['status' => 'success', 'message' => 'Jurnal berhasil ditambahkan', 'image' => $imagePath]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menambahkan jurnal']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Metode request tidak diizinkan']);
}
