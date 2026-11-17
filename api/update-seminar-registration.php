<?php
// Admin: ubah status pendaftaran dan tanda lunas.
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

$input = json_decode(file_get_contents('php://input'), true) ?: [];
$id = filter_var($input['id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
if ($id === false) {
    echo json_encode(['status' => 'error', 'message' => 'ID tidak valid']);
    exit;
}

$sets = [];
$vals = [];
if (array_key_exists('status', $input)) {
    if (!in_array($input['status'], ['pending', 'confirmed', 'rejected'], true)) {
        echo json_encode(['status' => 'error', 'message' => 'Status tidak valid']);
        exit;
    }
    $sets[] = 'status = ?';
    $vals[] = $input['status'];
}
if (array_key_exists('is_paid', $input)) {
    $paid = (int) (bool) $input['is_paid'];
    $sets[] = 'is_paid = ?';
    $vals[] = $paid;
    $sets[] = 'paid_at = ' . ($paid ? 'NOW()' : 'NULL');
}
if (!$sets) {
    echo json_encode(['status' => 'error', 'message' => 'Tidak ada perubahan']);
    exit;
}

try {
    $pdo = getDB();
    $vals[] = $id;
    $stmt = $pdo->prepare("UPDATE seminar_registrations SET " . implode(', ', $sets) . " WHERE id = ?");
    $stmt->execute($vals);
    if ($stmt->rowCount() === 0 && !$pdo->query("SELECT 1 FROM seminar_registrations WHERE id = " . (int)$id)->fetch()) {
        echo json_encode(['status' => 'error', 'message' => 'Pendaftaran tidak ditemukan']);
        exit;
    }
    echo json_encode(['status' => 'success', 'message' => 'Data pendaftaran diperbarui']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui: ' . $e->getMessage()]);
}
