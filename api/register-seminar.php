<?php
// Pendaftaran & submission peserta seminar (publik).
require_once '../includes/db.php';
require_once '../includes/seminar_files.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
}

$seminarId   = filter_var($_POST['seminar_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$feeId       = filter_var($_POST['fee_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$fullName    = trim($_POST['full_name'] ?? '');
$email       = trim($_POST['email'] ?? '');
$affiliation = trim($_POST['affiliation'] ?? '');
$paperTitle  = trim($_POST['paper_title'] ?? '');
$subtheme    = trim($_POST['subtheme'] ?? '');

if ($seminarId === false || $fullName === '' || $email === '') {
    echo json_encode(['status' => 'error', 'message' => 'Nama dan email wajib diisi.']);
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'message' => 'Format email tidak valid.']);
    exit;
}

$abstractFile = null;
$receiptFile = null;
try {
    $pdo = getDB();

    $stmt = $pdo->prepare("SELECT id FROM seminars WHERE id = ? AND is_active = 1");
    $stmt->execute([$seminarId]);
    if (!$stmt->fetch()) {
        echo json_encode(['status' => 'error', 'message' => 'Seminar tidak ditemukan.']);
        exit;
    }

    if ($feeId !== false) {
        $stmt = $pdo->prepare("SELECT id FROM seminar_fees WHERE id = ? AND seminar_id = ? AND is_closed = 0");
        $stmt->execute([$feeId, $seminarId]);
        if (!$stmt->fetch()) {
            echo json_encode(['status' => 'error', 'message' => 'Kategori registrasi tidak valid.']);
            exit;
        }
    }

    if ($subtheme !== '') {
        $stmt = $pdo->prepare("SELECT id FROM seminar_subthemes WHERE seminar_id = ? AND name = ?");
        $stmt->execute([$seminarId, $subtheme]);
        if (!$stmt->fetch()) {
            echo json_encode(['status' => 'error', 'message' => 'Subtema tidak valid.']);
            exit;
        }
    }

    $abstractFile = storeSeminarRegistrationFile($_FILES['abstract'] ?? null, 'abstract');
    $receiptFile  = storeSeminarRegistrationFile($_FILES['receipt'] ?? null, 'receipt');

    $stmt = $pdo->prepare("INSERT INTO seminar_registrations
        (seminar_id, fee_id, full_name, email, affiliation, paper_title, subtheme, abstract_file, receipt_file)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $seminarId, $feeId ?: null, $fullName, $email, $affiliation ?: null,
        $paperTitle ?: null, $subtheme ?: null, $abstractFile, $receiptFile,
    ]);

    echo json_encode(['status' => 'success', 'message' => 'Pendaftaran berhasil. Simpan email Anda untuk mengecek status di halaman Cek Status Pendaftaran.']);
} catch (PDOException $e) {
    removeSeminarRegistrationFile($abstractFile);
    removeSeminarRegistrationFile($receiptFile);
    if ((int) ($e->errorInfo[1] ?? 0) === 1062) {
        echo json_encode(['status' => 'error', 'message' => 'Email ini sudah terdaftar untuk seminar tersebut.']);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan sistem.']);
    }
} catch (RuntimeException $e) {
    removeSeminarRegistrationFile($abstractFile);
    removeSeminarRegistrationFile($receiptFile);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
