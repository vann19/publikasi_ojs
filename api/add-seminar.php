<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/seminar_helper.php';

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

$in = readSeminarInput();
if ($in['title'] === '') {
    echo json_encode(['status' => 'error', 'message' => 'Judul seminar wajib diisi']);
    exit;
}

$uploaded = [];
try {
    $imagePath = uploadSeminarImage(null);
    if ($imagePath) $uploaded[] = $imagePath;

    $pdo = getDB();
    $pdo->beginTransaction();

    $cols = array_merge(SEMINAR_COLUMNS, ['image']);
    $vals = [];
    foreach (SEMINAR_COLUMNS as $c) $vals[] = $in[$c];
    $vals[] = $imagePath;

    $sql = "INSERT INTO seminars (" . implode(', ', $cols) . ") VALUES (" . implode(', ', array_fill(0, count($cols), '?')) . ")";
    $pdo->prepare($sql)->execute($vals);
    $id = (int) $pdo->lastInsertId();

    $result = saveSeminarChildren($pdo, $id, $in);
    $uploaded = array_merge($uploaded, $result['uploaded']);
    $pdo->commit();

    echo json_encode(['status' => 'success', 'message' => 'Seminar berhasil ditambahkan']);
} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
    foreach ($uploaded as $p) removeLocalSeminarFile($p);
    echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()]);
}
