<?php
require_once '../includes/auth.php';
checkAuth();
require_once '../includes/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $author = $_POST['author'] ?? '';
    $category = $_POST['category'] ?? '';
    $harga = isset($_POST['harga']) && $_POST['harga'] !== '' ? (int)$_POST['harga'] : null;
    $publisher = $_POST['publisher'] ?? '';
    $published_date = $_POST['published_date'] ?? null;
    $isbn = $_POST['isbn'] ?? '';
    $pages = isset($_POST['pages']) && $_POST['pages'] !== '' ? (int)$_POST['pages'] : null;
    $country = $_POST['country'] ?? '';
    $language = $_POST['language'] ?? '';
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

    function generateSlug($string) {
        $s = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
        return trim($s, '-');
    }
    
    $pdo = getDB();
    $slug = generateSlug($title);
    
    // Check if slug exists
    $stmtSlug = $pdo->prepare("SELECT COUNT(*) FROM books WHERE slug = ?");
    $stmtSlug->execute([$slug]);
    if ($stmtSlug->fetchColumn() > 0) {
        $slug .= '-' . time();
    }

    $stmt = $pdo->prepare("INSERT INTO books (title, slug, author, category, harga, publisher, published_date, isbn, pages, country, language, description, image) VALUES (:title, :slug, :author, :category, :harga, :publisher, :published_date, :isbn, :pages, :country, :language, :description, :image)");

    $success = $stmt->execute([
        'title' => $title,
        'slug' => $slug,
        'author' => $author,
        'category' => $category,
        'harga' => $harga,
        'publisher' => $publisher,
        'published_date' => $published_date ?: null,
        'isbn' => $isbn,
        'pages' => $pages,
        'country' => $country,
        'language' => $language,
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
