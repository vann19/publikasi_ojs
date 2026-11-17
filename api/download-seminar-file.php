<?php
// Admin: unduh abstrak/bukti bayar dari folder privat.
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/seminar_files.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    exit('Unauthorized');
}

$id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$type = $_GET['type'] ?? '';
if ($id === false || !in_array($type, ['abstract', 'receipt'], true)) {
    http_response_code(400);
    exit('Permintaan tidak valid');
}

$pdo = getDB();
$stmt = $pdo->prepare("SELECT abstract_file, receipt_file FROM seminar_registrations WHERE id = ?");
$stmt->execute([$id]);
$row = $stmt->fetch();
$name = $row[$type === 'abstract' ? 'abstract_file' : 'receipt_file'] ?? null;
$path = $name ? SEMINAR_PRIVATE_DIR . '/' . basename($name) : null;
if (!$path || !is_file($path)) {
    http_response_code(404);
    exit('Berkas tidak ditemukan');
}

header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $type . '_' . $id . '_' . basename($name) . '"');
header('Content-Length: ' . filesize($path));
header('X-Content-Type-Options: nosniff');
readfile($path);
