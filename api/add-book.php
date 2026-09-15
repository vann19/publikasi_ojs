<?php
require_once '../includes/auth.php';
checkAuth();
require_once '../includes/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $author = $_POST['author'] ?? '';
    $publisher = $_POST['publisher'] ?? '';
    $published_year = $_POST['published_year'] ?? null;
    $isbn = $_POST['isbn'] ?? '';
    $description = $_POST['description'] ?? '';
    $imagePath = null;

    if (empty($title) || empty($author)) {
        echo json_encode(['status' => 'error', 'message' => 'Judul dan Penulis wajib diisi']);
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
        $filename = uniqid('book_') . '.' . $ext;
        $uploadDir = __DIR__ . '/../storage/books/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        if (move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
            $imagePath = '/storage/books/' . $filename;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal mengupload foto']);
            exit;
        }
    }

    $pdo = getDB();
    $stmt = $pdo->prepare("INSERT INTO books (title, author, publisher, published_year, isbn, description, image) VALUES (:title, :author, :publisher, :published_year, :isbn, :description, :image)");

    $success = $stmt->execute([
        'title' => $title,
        'author' => $author,
        'publisher' => $publisher,
        'published_year' => $published_year ?: null,
        'isbn' => $isbn,
        'description' => $description,
        'image' => $imagePath
    ]);

    if ($success) {
        echo json_encode(['status' => 'success', 'message' => 'Buku berhasil ditambahkan', 'image' => $imagePath]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menambahkan buku']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Metode request tidak diizinkan']);
}
