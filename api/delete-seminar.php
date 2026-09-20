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

$input = json_decode(file_get_contents('php://input'), true);
$id = $input['id'] ?? null;

if (!$id) {
    echo json_encode(['status' => 'error', 'message' => 'ID tidak valid']);
    exit;
}

try {
    $pdo = getDB();
    
    // Cari image untuk dihapus
    $stmt = $pdo->prepare("SELECT image FROM seminars WHERE id = ?");
    $stmt->execute([$id]);
    $seminar = $stmt->fetch();
    
    if ($seminar && $seminar['image']) {
        $imgPath = '..' . $seminar['image'];
        if (file_exists($imgPath)) {
            unlink($imgPath);
        }
    }
    
    // Hapus data
    $delStmt = $pdo->prepare("DELETE FROM seminars WHERE id = ?");
    $delStmt->execute([$id]);
    
    echo json_encode(['status' => 'success', 'message' => 'Seminar berhasil dihapus']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus seminar: ' . $e->getMessage()]);
}
