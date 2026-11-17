<?php
// Helper bersama untuk data seminar (dipakai API tambah/edit seminar & halaman publik).

const SEMINAR_SPEAKER_CATEGORIES = ['opening', 'keynote', 'plenary'];
const SPEAKER_PHOTO_DIR = __DIR__ . '/../storage/speakers';
const SPEAKER_PHOTO_SIZE = 600; // px, hasil crop persegi
const SLIDE_IMAGE_DIR  = __DIR__ . '/../storage/slides';
const PARTNER_LOGO_DIR = __DIR__ . '/../storage/partners';

// Kolom tabel seminars yang diisi dari form admin
const SEMINAR_COLUMNS = [
    'title', 'subtitle', 'organizer', 'theme', 'call_for_paper', 'description',
    'date', 'end_date', 'deadline_date', 'deadline_label', 'location',
    'price', 'fee_info', 'link', 'submission_link', 'booklet_link', 'is_active',
];

// Teks section halaman publik yang bisa diatur dari admin (CMS).
// Kunci => label admin, nilai default, tipe input, dan petunjuk.
const SEMINAR_CONTENT_FIELDS = [
    'hero_badge'             => ['label' => 'Badge di atas judul', 'default' => '', 'type' => 'text', 'hint' => 'Kosongkan untuk memakai tanggal pelaksanaan otomatis.'],
    'hero_description'       => ['label' => 'Keterangan penyelenggara (hero)', 'default' => '', 'type' => 'textarea', 'hint' => 'Teks biasa. Kosongkan untuk memakai nama penyelenggara.'],
    'deadlines'              => ['label' => 'Target countdown', 'default' => '', 'type' => 'textarea', 'hint' => 'Format: label | YYYY-MM-DD HH:MM, satu per baris. Hari-H (tanggal mulai) otomatis menjadi target terakhir.'],
    'countdown_eyebrow'      => ['label' => 'Judul kecil countdown', 'default' => 'Important Deadline Countdown', 'type' => 'text'],
    'theme_eyebrow'          => ['label' => 'Judul kecil tema', 'default' => 'Main Theme', 'type' => 'text'],
    'theme_booklet_text'     => ['label' => 'Teks tombol booklet', 'default' => 'Lihat Booklet', 'type' => 'text'],
    'speakers_eyebrow'       => ['label' => 'Judul kecil pembicara', 'default' => 'Distinguished Experts', 'type' => 'text'],
    'speakers_opening_title' => ['label' => 'Judul: Opening Speech', 'default' => 'Opening Speech', 'type' => 'text'],
    'speakers_opening_desc'  => ['label' => 'Keterangan: Opening Speech', 'default' => '', 'type' => 'text'],
    'speakers_keynote_title' => ['label' => 'Judul: Keynote Speakers', 'default' => 'Keynote Speakers', 'type' => 'text'],
    'speakers_keynote_desc'  => ['label' => 'Keterangan: Keynote Speakers', 'default' => '', 'type' => 'text'],
    'speakers_plenary_title' => ['label' => 'Judul: Plenary Speakers', 'default' => 'Plenary Speakers', 'type' => 'text'],
    'speakers_plenary_desc'  => ['label' => 'Keterangan: Plenary Speakers', 'default' => '', 'type' => 'text'],
    'timeline_eyebrow'       => ['label' => 'Judul kecil timeline', 'default' => 'Schedule', 'type' => 'text'],
    'timeline_title'         => ['label' => 'Judul timeline', 'default' => 'Conference Timeline', 'type' => 'text'],
    'timeline_desc'          => ['label' => 'Keterangan timeline', 'default' => '', 'type' => 'text'],
    'fees_eyebrow'           => ['label' => 'Judul kecil biaya', 'default' => 'Investment', 'type' => 'text'],
    'fees_title'             => ['label' => 'Judul biaya', 'default' => 'Registration Fee Structure', 'type' => 'text'],
    'fees_desc'              => ['label' => 'Keterangan biaya', 'default' => '', 'type' => 'text'],
    'fee_notes'              => ['label' => 'Catatan per kategori biaya', 'default' => '', 'type' => 'textarea', 'hint' => 'Format: kategori | catatan, satu per baris. Tampil di bawah kartu kategori.'],
    'payment_title'          => ['label' => 'Judul instruksi pembayaran', 'default' => 'Payment Instructions', 'type' => 'text'],
    'payment_desc'           => ['label' => 'Teks instruksi pembayaran', 'default' => '', 'type' => 'textarea'],
    'journals_eyebrow'       => ['label' => 'Judul kecil jurnal', 'default' => 'Outlets', 'type' => 'text'],
    'journals_title'         => ['label' => 'Judul jurnal', 'default' => 'Publication Channels', 'type' => 'text'],
    'journals_desc'          => ['label' => 'Keterangan jurnal', 'default' => '', 'type' => 'text'],
    'register_eyebrow'       => ['label' => 'Judul kecil registrasi', 'default' => 'Online Registration & Submission', 'type' => 'text'],
    'register_title'         => ['label' => 'Judul registrasi', 'default' => 'Registration & Paper Submission', 'type' => 'text'],
    'register_desc'          => ['label' => 'Keterangan registrasi', 'default' => '', 'type' => 'text'],
    'partners_eyebrow'       => ['label' => 'Judul kecil partner', 'default' => 'Partners', 'type' => 'text'],
    'partners_title'         => ['label' => 'Judul partner', 'default' => 'Our Co-Hosts', 'type' => 'text'],
];

// Nilai teks dari database, atau default jika belum diisi.
function seminarContentValue(array $map, string $key): string {
    $val = trim((string) ($map[$key] ?? ''));
    return $val !== '' ? $val : (SEMINAR_CONTENT_FIELDS[$key]['default'] ?? '');
}

function parseSeminarContentInput(): array {
    $raw = is_array($_POST['content'] ?? null) ? $_POST['content'] : [];
    $out = [];
    foreach (array_keys(SEMINAR_CONTENT_FIELDS) as $key) {
        $val = trim((string) ($raw[$key] ?? ''));
        if ($val !== '') $out[$key] = $val;
    }
    return $out;
}

function readSeminarInput(): array {
    $t = fn($key) => trim($_POST[$key] ?? '');
    return [
        'title'           => $t('title'),
        'subtitle'        => $t('subtitle'),
        'organizer'       => $t('organizer'),
        'theme'           => $t('theme'),
        'call_for_paper'  => $t('call_for_paper'),
        'description'     => $t('description'),
        'date'            => $t('date') ?: null,
        'end_date'        => $t('end_date') ?: null,
        'deadline_date'   => $t('deadline_date') ?: null,
        'deadline_label'  => $t('deadline_label'),
        'location'        => $t('location'),
        'price'           => ($_POST['price'] ?? '') !== '' ? (int) $_POST['price'] : null,
        'fee_info'        => $t('fee_info'),
        'link'            => $t('link'),
        'submission_link' => $t('submission_link'),
        'booklet_link'    => $t('booklet_link'),
        'is_active'       => isset($_POST['is_active']) ? 1 : 0,
        'slides'          => is_array($_POST['slides'] ?? null) ? $_POST['slides'] : [],
        'speakers'        => is_array($_POST['speakers'] ?? null) ? $_POST['speakers'] : [],
        'schedule_text'   => trim($_POST['schedule'] ?? ''),
        'partners'        => is_array($_POST['partners'] ?? null) ? $_POST['partners'] : [],
        'fees_text'       => trim($_POST['fees'] ?? ''),
        'subthemes_text'  => trim($_POST['subthemes'] ?? ''),
        'bank_transfers'  => is_array($_POST['bank_transfers'] ?? null) ? $_POST['bank_transfers'] : [],
        'content'         => parseSeminarContentInput(),
    ];
}

// Baris format "a | b | c" -> array terpotong. Baris kosong diabaikan.
function parseSeminarLines(string $text, int $parts): array {
    $rows = [];
    foreach (preg_split('/\r\n|\r|\n/', $text) as $line) {
        $cols = array_map('trim', explode('|', $line, $parts));
        if (($cols[0] ?? '') === '') continue;
        $rows[] = array_pad($cols, $parts, '');
    }
    return $rows;
}

// Mengubah foto pembicara (JPG/PNG/GIF/WebP) menjadi WebP persegi.
// Mengembalikan path publik. Melempar RuntimeException jika file tidak valid.
function saveSpeakerPhotoAsWebp(array $file): string {
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Upload foto pembicara gagal.');
    }
    if ($file['size'] > 2 * 1024 * 1024) {
        throw new RuntimeException('Foto pembicara maksimal 2 MB.');
    }
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    if (!in_array($mime, ['image/jpeg', 'image/png', 'image/gif', 'image/webp'], true)) {
        throw new RuntimeException('Foto pembicara harus JPG, PNG, GIF, atau WebP.');
    }

    $src = @imagecreatefromstring(file_get_contents($file['tmp_name']));
    if ($src === false) {
        throw new RuntimeException('Foto pembicara tidak bisa dibaca.');
    }

    // Crop tengah menjadi persegi agar kartu seragam
    $w = imagesx($src);
    $h = imagesy($src);
    $side = min($w, $h);
    $cropped = imagecrop($src, ['x' => (int) (($w - $side) / 2), 'y' => (int) (($h - $side) / 2), 'width' => $side, 'height' => $side]);
    imagedestroy($src);
    if ($cropped === false) {
        throw new RuntimeException('Gagal memproses foto pembicara.');
    }
    $scaled = imagescale($cropped, SPEAKER_PHOTO_SIZE, SPEAKER_PHOTO_SIZE);
    imagedestroy($cropped);

    if (!is_dir(SPEAKER_PHOTO_DIR)) {
        mkdir(SPEAKER_PHOTO_DIR, 0755, true);
    }
    $name = 'speaker_' . bin2hex(random_bytes(8)) . '.webp';
    $ok = imagewebp($scaled, SPEAKER_PHOTO_DIR . '/' . $name, 85);
    imagedestroy($scaled);
    if (!$ok) {
        throw new RuntimeException('Gagal menyimpan foto pembicara.');
    }
    return '/storage/speakers/' . $name;
}

// Ambil satu berkas dari $_FILES['speaker_photo'][$idx] dengan struktur per-file.
function speakerPhotoAt(int $idx): ?array {
    return fileAtIdx('speaker_photo', $idx);
}

// Helper generik: ambil satu berkas dari struktur $_FILES multi-index.
function fileAtIdx(string $field, int $idx): ?array {
    if (!isset($_FILES[$field]['error'][$idx])) return null;
    if ($_FILES[$field]['error'][$idx] === UPLOAD_ERR_NO_FILE) return null;
    return [
        'name'     => $_FILES[$field]['name'][$idx],
        'type'     => $_FILES[$field]['type'][$idx],
        'tmp_name' => $_FILES[$field]['tmp_name'][$idx],
        'error'    => $_FILES[$field]['error'][$idx],
        'size'     => $_FILES[$field]['size'][$idx],
    ];
}

// Simpan gambar slide ke /storage/slides/. Mengembalikan path publik.
function saveSlideImage(array $file): string {
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Upload gambar slide gagal.');
    }
    if ($file['size'] > 5 * 1024 * 1024) {
        throw new RuntimeException('Gambar slide maksimal 5 MB.');
    }
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    if (strpos($mime, 'image/') !== 0) {
        throw new RuntimeException('Gambar slide harus berupa file gambar.');
    }
    if (!is_dir(SLIDE_IMAGE_DIR)) {
        mkdir(SLIDE_IMAGE_DIR, 0755, true);
    }
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION)) ?: 'jpg';
    $name = 'slide_' . bin2hex(random_bytes(8)) . '.' . $ext;
    if (!move_uploaded_file($file['tmp_name'], SLIDE_IMAGE_DIR . '/' . $name)) {
        throw new RuntimeException('Gagal menyimpan gambar slide.');
    }
    return '/storage/slides/' . $name;
}

// Simpan logo partner ke /storage/partners/. Mengembalikan path publik.
function savePartnerLogo(array $file): string {
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Upload logo partner gagal.');
    }
    if ($file['size'] > 2 * 1024 * 1024) {
        throw new RuntimeException('Logo partner maksimal 2 MB.');
    }
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    if (strpos($mime, 'image/') !== 0) {
        throw new RuntimeException('Logo partner harus berupa file gambar.');
    }
    if (!is_dir(PARTNER_LOGO_DIR)) {
        mkdir(PARTNER_LOGO_DIR, 0755, true);
    }
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION)) ?: 'png';
    $name = 'partner_' . bin2hex(random_bytes(8)) . '.' . $ext;
    if (!move_uploaded_file($file['tmp_name'], PARTNER_LOGO_DIR . '/' . $name)) {
        throw new RuntimeException('Gagal menyimpan logo partner.');
    }
    return '/storage/partners/' . $name;
}

// Hapus file lokal berdasarkan path publik (e.g. /storage/slides/x.jpg).
function removeLocalSeminarFile(?string $publicPath): void {
    if (!$publicPath) return;
    $local = __DIR__ . '/..' . $publicPath;
    if (is_file($local)) unlink($local);
}

// Menyimpan seluruh data turunan seminar. Data lama dihapus lalu diisi ulang.
// Mengembalikan ['uploaded' => [...]] berisi path file yang baru diupload
// (untuk cleanup jika terjadi error/rollback).
function saveSeminarChildren(PDO $pdo, int $seminarId, array $in): array {
    $uploaded = [];

    foreach (['seminar_speakers', 'seminar_schedules', 'seminar_slides', 'seminar_partners', 'seminar_fees', 'seminar_subthemes', 'seminar_bank_transfers', 'seminar_content'] as $table) {
        $pdo->prepare("DELETE FROM $table WHERE seminar_id = ?")->execute([$seminarId]);
    }

    // Slider: dikirim sebagai array slides[idx][title|caption|image] + file slide_photo[idx]
    $ins = $pdo->prepare("INSERT INTO seminar_slides (seminar_id, title, caption, image, sort_order) VALUES (?, ?, ?, ?, ?)");
    foreach ($in['slides'] as $idx => $row) {
        $title  = trim($row['title'] ?? '');
        $caption = trim($row['caption'] ?? '');
        $image  = trim($row['image'] ?? '');

        $file = fileAtIdx('slide_photo', (int) $idx);
        if ($file) {
            $newImage = saveSlideImage($file);
            if ($image) removeLocalSeminarFile($image);
            $image = $newImage;
            $uploaded[] = $newImage;
        }

        if ($image === '') continue; // lewati slide tanpa gambar
        $ins->execute([$seminarId, $title ?: null, $caption ?: null, $image, $idx]);
    }

    // Pembicara: dikirim sebagai array speakers[idx][field] + file speaker_photo[idx]
    $ins = $pdo->prepare("INSERT INTO seminar_speakers (seminar_id, category, name, title, affiliation, image, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $order = 0;
    foreach ($in['speakers'] as $idx => $row) {
        $name = trim($row['name'] ?? '');
        if ($name === '') continue;
        $cat = strtolower(trim($row['category'] ?? ''));
        if (!in_array($cat, SEMINAR_SPEAKER_CATEGORIES, true)) $cat = 'plenary';

        $image = trim($row['image'] ?? '') ?: null;
        $file = fileAtIdx('speaker_photo', (int) $idx);
        if ($file) {
            $newImage = saveSpeakerPhotoAsWebp($file);
            if ($image) removeLocalSeminarFile($image);
            $image = $newImage;
            $uploaded[] = $newImage;
        }

        $ins->execute([$seminarId, $cat, $name, trim($row['title'] ?? '') ?: null, trim($row['affiliation'] ?? '') ?: null, $image, $order++]);
    }

    // Jadwal: label | tanggal | deskripsi
    $ins = $pdo->prepare("INSERT INTO seminar_schedules (seminar_id, label, schedule_date, description, sort_order) VALUES (?, ?, ?, ?, ?)");
    foreach (parseSeminarLines($in['schedule_text'], 3) as $i => [$label, $when, $desc]) {
        $ins->execute([$seminarId, $label, $when ?: null, $desc ?: null, $i]);
    }

    // Partner: dikirim sebagai array partners[idx][role|name|website|logo] + file partner_logo[idx]
    $ins = $pdo->prepare("INSERT INTO seminar_partners (seminar_id, role, name, logo, website, sort_order) VALUES (?, ?, ?, ?, ?, ?)");
    foreach ($in['partners'] as $idx => $row) {
        $name = trim($row['name'] ?? '');
        if ($name === '') continue;

        $role    = strtolower(trim($row['role'] ?? '')) === 'host' ? 'host' : 'cohost';
        $website = trim($row['website'] ?? '') ?: null;
        $logo    = trim($row['logo'] ?? '');

        $file = fileAtIdx('partner_logo', (int) $idx);
        if ($file) {
            $newLogo = savePartnerLogo($file);
            if ($logo) removeLocalSeminarFile($logo);
            $logo = $newLogo;
            $uploaded[] = $newLogo;
        }

        if ($logo === '') continue; // lewati partner tanpa logo
        $ins->execute([$seminarId, $role, $name, $logo, $website, $idx]);
    }

    // Biaya: kategori | item | nominal atau CLOSED | catatan
    $ins = $pdo->prepare("INSERT INTO seminar_fees (seminar_id, category, item, amount, note, is_closed, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?)");
    foreach (parseSeminarLines($in['fees_text'], 4) as $i => [$cat, $item, $amount, $note]) {
        if ($cat === '' || $item === '') continue;
        $closed = strtolower($amount) === 'closed';
        $nominal = (!$closed && $amount !== '') ? (int) preg_replace('/\D/', '', $amount) : null;
        $ins->execute([$seminarId, $cat, $item, $nominal, $note ?: null, $closed ? 1 : 0, $i]);
    }

    // Subtema: satu nama per baris
    $ins = $pdo->prepare("INSERT INTO seminar_subthemes (seminar_id, name, sort_order) VALUES (?, ?, ?)");
    $i = 0;
    foreach (preg_split('/\r\n|\r|\n/', $in['subthemes_text']) as $line) {
        $name = trim($line);
        if ($name === '') continue;
        $ins->execute([$seminarId, $name, $i++]);
    }

    // Bank Transfer: dikirim sebagai array bank_transfers[idx][bank_name|account_number|account_name]
    $ins = $pdo->prepare("INSERT INTO seminar_bank_transfers (seminar_id, bank_name, account_number, account_name, sort_order) VALUES (?, ?, ?, ?, ?)");
    foreach ($in['bank_transfers'] as $idx => $row) {
        $bankName     = trim($row['bank_name'] ?? '');
        $accountNumber = trim($row['account_number'] ?? '');
        $accountName  = trim($row['account_name'] ?? '');
        if ($bankName === '' || $accountNumber === '') continue;
        $ins->execute([$seminarId, $bankName, $accountNumber, $accountName ?: null, $idx]);
    }

    // Teks section CMS: hanya simpan nilai yang diisi; halaman publik memakai default untuk sisanya.
    $ins = $pdo->prepare("INSERT INTO seminar_content (seminar_id, content_key, content_value) VALUES (?, ?, ?)");
    foreach ($in['content'] as $key => $value) {
        if (!array_key_exists($key, SEMINAR_CONTENT_FIELDS)) continue;
        $ins->execute([$seminarId, $key, $value]);
    }

    return ['uploaded' => $uploaded];
}

// Upload poster seminar. Mengembalikan path publik atau $oldPath jika tidak ada file baru.
function uploadSeminarImage(?string $oldPath = null): ?string {
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        return $oldPath;
    }
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $_FILES['image']['tmp_name']);
    finfo_close($finfo);
    if (strpos($mimeType, 'image/') !== 0) {
        throw new RuntimeException('File harus berupa gambar.');
    }
    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    $fileName = 'seminar_' . uniqid() . '.' . $ext;
    $dest = __DIR__ . '/../storage/seminars/' . $fileName;
    if (!move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
        throw new RuntimeException('Gagal mengupload gambar.');
    }
    // Penghapusan file lama ditangani oleh pemanggil setelah commit.
    return '/storage/seminars/' . $fileName;
}
