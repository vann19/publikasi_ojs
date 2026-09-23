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

if (filter_var($id, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) === false) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'ID jurnal wajib diisi']);
    exit;
}

if (!isset($_FILES['cover']) || $_FILES['cover']['error'] !== UPLOAD_ERR_OK) {
    $errorMessages = [
        UPLOAD_ERR_INI_SIZE   => 'File terlalu besar (melebihi batas server)',
        UPLOAD_ERR_FORM_SIZE  => 'File terlalu besar (melebihi batas form)',
        UPLOAD_ERR_PARTIAL    => 'File hanya terupload sebagian',
        UPLOAD_ERR_NO_FILE    => 'Tidak ada file yang dipilih',
        UPLOAD_ERR_NO_TMP_DIR => 'Folder temporary tidak ditemukan',
        UPLOAD_ERR_CANT_WRITE => 'Gagal menulis file ke disk',
    ];
    $errCode = $_FILES['cover']['error'] ?? UPLOAD_ERR_NO_FILE;
    $msg = $errorMessages[$errCode] ?? 'Terjadi kesalahan upload';
    echo json_encode(['status' => 'error', 'message' => $msg]);
    exit;
}

$file = $_FILES['cover'];
$maxSize = 5 * 1024 * 1024; // 5 MB

if (!is_uploaded_file($file['tmp_name'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'File upload tidak valid']);
    exit;
}

if ($file['size'] <= 0 || $file['size'] > $maxSize) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Ukuran file maksimal 5 MB']);
    exit;
}

// Validate MIME type
$allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'image/bmp'];
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);

if ($mimeType === false || !in_array($mimeType, $allowedTypes, true)) {
    http_response_code(415);
    echo json_encode(['status' => 'error', 'message' => 'Format file tidak didukung. Gunakan JPG, PNG, WebP, GIF, atau BMP']);
    exit;
}

$imageFunctions = [
    'image/jpeg' => 'imagecreatefromjpeg',
    'image/png' => 'imagecreatefrompng',
    'image/webp' => 'imagecreatefromwebp',
    'image/gif' => 'imagecreatefromgif',
    'image/bmp' => 'imagecreatefrombmp',
];

if (!function_exists($imageFunctions[$mimeType]) || !function_exists('imagewebp')) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Server belum mendukung konversi gambar ke WebP']);
    exit;
}

// Create upload directory
$uploadDir = __DIR__ . '/../storage/journals/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

try {
    $pdo = getDB();

    // Get existing image to delete later
    $stmt = $pdo->prepare("SELECT image FROM journals WHERE id = ? LIMIT 1");
    $stmt->execute([(int)$id]);
    $journal = $stmt->fetch();

    if (!$journal) {
        echo json_encode(['status' => 'error', 'message' => 'Jurnal tidak ditemukan']);
        exit;
    }

    // Create GD image resource from uploaded file.
    $sourceImage = call_user_func($imageFunctions[$mimeType], $file['tmp_name']);

    if (!$sourceImage) {
        echo json_encode(['status' => 'error', 'message' => 'Gagal memproses gambar. File mungkin rusak.']);
        exit;
    }

    // Preserve transparency for PNG/GIF by converting to true color with alpha
    $width = imagesx($sourceImage);
    $height = imagesy($sourceImage);
    if ($width < 1 || $height < 1) {
        imagedestroy($sourceImage);
        echo json_encode(['status' => 'error', 'message' => 'Dimensi gambar tidak valid']);
        exit;
    }
    $trueColor = imagecreatetruecolor($width, $height);
    
    // Fill with white background (WebP doesn't support transparency well in all browsers)
    $white = imagecolorallocate($trueColor, 255, 255, 255);
    imagefill($trueColor, 0, 0, $white);
    
    // Copy source onto white background
    imagecopy($trueColor, $sourceImage, 0, 0, 0, 0, $width, $height);
    imagedestroy($sourceImage);

    // Generate unique filename
    $filename = 'journal_cover_' . (int)$id . '_' . bin2hex(random_bytes(8)) . '.webp';
    $savePath = $uploadDir . $filename;

    // Convert and save as WebP (quality 85)
    $success = imagewebp($trueColor, $savePath, 85);
    imagedestroy($trueColor);

    if (!$success) {
        echo json_encode(['status' => 'error', 'message' => 'Gagal mengkonversi gambar ke format WebP']);
        exit;
    }

    // Update database with local path
    $imagePath = '/storage/journals/' . $filename;
    $pdo->beginTransaction();
    $updateStmt = $pdo->prepare("UPDATE journals SET image = ? WHERE id = ?");
    $updateStmt->execute([$imagePath, (int)$id]);
    if ($updateStmt->rowCount() < 1 && $journal['image'] !== $imagePath) {
        throw new RuntimeException('Gagal menyimpan cover jurnal');
    }
    $pdo->commit();

    // Delete old local cover if it was a local file
    $oldImage = $journal['image'];
    if ($oldImage && strpos($oldImage, '/storage/journals/') === 0) {
        $oldFilePath = __DIR__ . '/..' . $oldImage;
        if (file_exists($oldFilePath)) {
            unlink($oldFilePath);
        }
    }

    echo json_encode([
        'status' => 'success',
        'message' => 'Cover berhasil diupload dan dikonversi ke WebP!',
        'image' => $imagePath
    ]);

} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    if (isset($savePath) && file_exists($savePath)) {
        unlink($savePath);
    }
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan saat menyimpan cover jurnal']);
}
