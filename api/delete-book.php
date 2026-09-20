<?php
require_once '../includes/auth.php';
checkAuth();
require_once '../includes/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;

    if (!$id) {
        echo json_encode(['status' => 'error', 'message' => 'ID tidak ditemukan']);
        exit;
    }

    $pdo = getDB();
    
    // get image to delete
    $stmt = $pdo->prepare("SELECT image FROM books WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $book = $stmt->fetch();

    if ($book && $book['image']) {
        $imagePath = __DIR__ . '/..' . $book['image'];
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }

    $stmt = $pdo->prepare("DELETE FROM books WHERE id = :id");
    $success = $stmt->execute(['id' => $id]);

    if ($success) {
        echo json_encode(['status' => 'success', 'message' => 'Buku berhasil dihapus']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus buku']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Metode request tidak diizinkan']);
}
