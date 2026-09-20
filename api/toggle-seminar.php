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
$is_active = isset($input['is_active']) ? (int)$input['is_active'] : null;

if (!$id || $is_active === null) {
    echo json_encode(['status' => 'error', 'message' => 'ID dan status tidak valid']);
    exit;
}

try {
    $pdo = getDB();
    $stmt = $pdo->prepare("UPDATE seminars SET is_active = ? WHERE id = ?");
    $stmt->execute([$is_active, $id]);
    
    echo json_encode(['status' => 'success', 'message' => 'Status seminar berhasil diubah']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Gagal mengubah status: ' . $e->getMessage()]);
}
