<?php
// Berkas pendaftaran seminar (abstrak & bukti bayar). Disimpan di folder privat,
// hanya bisa diunduh lewat api/download-seminar-file.php oleh admin.

const SEMINAR_PRIVATE_DIR = __DIR__ . '/../storage/seminar-berkas';

function storeSeminarRegistrationFile(?array $file, string $kind): ?string {
    if (!$file || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) return null;
    if ($file['error'] !== UPLOAD_ERR_OK) throw new RuntimeException('Upload berkas gagal (kode ' . (int)$file['error'] . ').');
    if ($file['size'] < 1 || $file['size'] > 5 * 1024 * 1024) throw new RuntimeException('Ukuran berkas maksimal 5 MB.');

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if ($kind === 'abstract') {
        // .doc = application/msword, .docx = zip-based OOXML
        $ok = ($ext === 'doc' && $mime === 'application/msword')
           || ($ext === 'docx' && in_array($mime, ['application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/zip'], true));
        if (!$ok) throw new RuntimeException('Abstrak harus berupa file .doc atau .docx.');
    } else {
        $allowed = ['pdf' => 'application/pdf', 'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'webp' => 'image/webp'];
        if (!isset($allowed[$ext]) || $allowed[$ext] !== $mime) {
            throw new RuntimeException('Bukti pembayaran harus berupa PDF, JPG, PNG, atau WebP.');
        }
    }

    if (!is_dir(SEMINAR_PRIVATE_DIR)) {
        mkdir(SEMINAR_PRIVATE_DIR, 0750, true);
        file_put_contents(SEMINAR_PRIVATE_DIR . '/.htaccess', "Require all denied\nDeny from all\n");
    }
    $name = $kind . '_' . bin2hex(random_bytes(12)) . '.' . $ext;
    if (!move_uploaded_file($file['tmp_name'], SEMINAR_PRIVATE_DIR . '/' . $name)) {
        throw new RuntimeException('Gagal menyimpan berkas.');
    }
    return $name;
}

function removeSeminarRegistrationFile(?string $name): void {
    if (!$name || strpbrk($name, '/\\') !== false) return;
    $path = SEMINAR_PRIVATE_DIR . '/' . $name;
    if (is_file($path)) @unlink($path);
}
