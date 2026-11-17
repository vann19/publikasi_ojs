<?php
// Hapus jurnal (CRUD: Delete). Cover di storage ikut dihapus.
require_once '../includes/auth.php';
require_once '../includes/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
}

$id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
if ($id === false) {
    echo json_encode(['status' => 'error', 'message' => 'ID jurnal tidak valid']);
    exit;
}

try {
    $pdo = getDB();
    $stmt = $pdo->prepare("SELECT image FROM journals WHERE id = ?");
    $stmt->execute([$id]);
    $journal = $stmt->fetch();
    if (!$journal) {
        echo json_encode(['status' => 'error', 'message' => 'Jurnal tidak ditemukan']);
        exit;
    }

    $pdo->prepare("DELETE FROM journals WHERE id = ?")->execute([$id]);

    // Hanya hapus file cover lokal yang berada di folder storage/journals
    if (!empty($journal['image']) && strpos($journal['image'], '/storage/journals/') === 0) {
        $file = __DIR__ . '/..' . $journal['image'];
        if (is_file($file)) unlink($file);
    }

    echo json_encode(['status' => 'success', 'message' => 'Jurnal berhasil dihapus']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus jurnal: ' . $e->getMessage()]);
}
