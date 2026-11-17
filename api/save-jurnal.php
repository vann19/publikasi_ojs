<?php
// Tambah / edit jurnal (CRUD). Scraping OJS tetap di scrape-ojs.php.
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

$id = $_POST['id'] ?? '';
$isEdit = $id !== '';
if ($isEdit && filter_var($id, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) === false) {
    echo json_encode(['status' => 'error', 'message' => 'ID jurnal tidak valid']);
    exit;
}

$t = fn($k) => trim($_POST[$k] ?? '');
$data = [
    'title'          => $t('title'),
    'path'           => $t('path'),
    'issn'           => $t('issn'),
    'sinta'          => $t('sinta'),
    'warna'          => $t('warna'),
    'link'           => $t('link'),
    'description'    => $t('description'),
    'volume'         => $t('volume'),
    'issue'          => $t('issue'),
    'published_date' => $t('published_date') ?: null,
    'is_active'      => isset($_POST['is_active']) ? 1 : 0,
];

if ($data['title'] === '') {
    echo json_encode(['status' => 'error', 'message' => 'Judul jurnal wajib diisi']);
    exit;
}
if ($data['link'] !== '' && !filter_var($data['link'], FILTER_VALIDATE_URL)) {
    echo json_encode(['status' => 'error', 'message' => 'Link OJS tidak valid']);
    exit;
}
if ($data['warna'] !== '' && !preg_match('/^bg-[a-z]+-\d{2,3}$/', $data['warna'])) {
    echo json_encode(['status' => 'error', 'message' => 'Warna harus berupa kelas Tailwind, contoh: bg-teal-700']);
    exit;
}

try {
    $pdo = getDB();

    // Path wajib unik (dipakai untuk upsert hasil scraping)
    $dupStmt = $pdo->prepare("SELECT id FROM journals WHERE path = ? AND path <> '' AND id <> ? LIMIT 1");
    if ($data['path'] !== '' && $dupStmt->execute([$data['path'], $isEdit ? (int)$id : 0]) && $dupStmt->fetch()) {
        echo json_encode(['status' => 'error', 'message' => 'Path jurnal sudah dipakai jurnal lain']);
        exit;
    }
    $data['path'] = $data['path'] === '' ? null : $data['path'];

    if ($isEdit) {
        $sets = implode(', ', array_map(fn($c) => "$c = ?", array_keys($data)));
        $stmt = $pdo->prepare("UPDATE journals SET $sets WHERE id = ?");
        $stmt->execute([...array_values($data), (int)$id]);
        $message = 'Jurnal berhasil diperbarui';
    } else {
        $cols = array_keys($data);
        $sql = "INSERT INTO journals (" . implode(', ', $cols) . ") VALUES (" . implode(', ', array_fill(0, count($cols), '?')) . ")";
        $pdo->prepare($sql)->execute(array_values($data));
        $message = 'Jurnal berhasil ditambahkan';
    }

    echo json_encode(['status' => 'success', 'message' => $message]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()]);
}
