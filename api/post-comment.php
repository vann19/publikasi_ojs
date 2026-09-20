<?php
require_once '../includes/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
}

$nama = trim($_POST['nama'] ?? '');
$instansi = trim($_POST['instansi'] ?? '');
$komentar = trim($_POST['komentar'] ?? '');

if (empty($nama) || empty($komentar)) {
    echo json_encode(['status' => 'error', 'message' => 'Nama dan komentar wajib diisi.']);
    exit;
}

try {
    $pdo = getDB();
    $stmt = $pdo->prepare("INSERT INTO comments (nama, instansi, komentar, is_active) VALUES (?, ?, ?, 0)");
    $stmt->execute([$nama, $instansi, $komentar]);

    echo json_encode(['status' => 'success', 'message' => 'Komentar berhasil dikirim dan menunggu persetujuan admin.']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()]);
}
