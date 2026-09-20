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
    echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed']);
    exit;
}

$id = $_POST['id'] ?? null;
$is_active = $_POST['is_active'] ?? null;

if ($id === null || $is_active === null) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid parameters']);
    exit;
}

try {
    $pdo = getDB();
    $stmt = $pdo->prepare("UPDATE journals SET is_active = ? WHERE id = ?");
    $stmt->execute([(int)$is_active, (int)$id]);
    
    echo json_encode(['status' => 'success', 'message' => 'Status berhasil diubah.']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()]);
}
