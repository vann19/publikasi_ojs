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

$id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$in = readSeminarInput();
if ($id === false || $in['title'] === '') {
    echo json_encode(['status' => 'error', 'message' => 'ID dan Judul seminar wajib diisi']);
    exit;
}

$uploaded = [];
try {
    $pdo = getDB();
    $stmt = $pdo->prepare("SELECT image FROM seminars WHERE id = ?");
    $stmt->execute([$id]);
    $old = $stmt->fetch();
    if (!$old) {
        echo json_encode(['status' => 'error', 'message' => 'Seminar tidak ditemukan']);
        exit;
    }

    $imagePath = uploadSeminarImage($old['image']);
    if ($imagePath !== $old['image']) $uploaded[] = $imagePath;

    $pdo->beginTransaction();
    $sets = implode(', ', array_map(fn($c) => "$c = ?", SEMINAR_COLUMNS)) . ', image = ?';
    $vals = [];
    foreach (SEMINAR_COLUMNS as $c) $vals[] = $in[$c];
    $vals[] = $imagePath;
    $vals[] = $id;
    $pdo->prepare("UPDATE seminars SET $sets WHERE id = ?")->execute($vals);

    $result = saveSeminarChildren($pdo, $id, $in);
    $uploaded = array_merge($uploaded, $result['uploaded']);
    $pdo->commit();

    // Bersihkan file lama hanya setelah data baru tersimpan.
    if ($imagePath !== $old['image']) removeLocalSeminarFile($old['image']);

    echo json_encode(['status' => 'success', 'message' => 'Seminar berhasil diperbarui']);
} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
    foreach ($uploaded as $p) removeLocalSeminarFile($p);
    echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()]);
}
