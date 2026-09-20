<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/ojs_helper.php';

// Auth check untuk API (return JSON)
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

header('Content-Type: application/json');
set_time_limit(120); // Beri waktu 120 detik untuk scraping

try {
    $pdo = getDB();
    $oaiIndexUrl = "https://e-journal.nawaedukasi.org/index.php/index/oai?verb=ListSets";
    
    // Hapus cache file JSON jika ada agar scrape dilakukan ulang
    $cacheFile = __DIR__ . '/../storage/journals_cache.json';
    if (file_exists($cacheFile)) {
        unlink($cacheFile);
    }
    
    // Proses Scraping (ini akan memakan waktu karena menarik metadata + cover HTML)
    $scrapedJournals = fetchOjsJournals($oaiIndexUrl);
    
    if (empty($scrapedJournals)) {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menarik data dari OJS atau data kosong.']);
        exit;
    }
    
    $inserted = 0;
    $updated = 0;
    
    // Loop dan UPSERT ke database
    foreach ($scrapedJournals as $j) {
        $path = $j['path'];
        $title = $j['judul'];
        $desc = $j['deskripsi'];
        $issn = $j['issn'];
        $sinta = $j['sinta'];
        $warna = $j['warna'];
        $url = $j['url'];
        $cover = $j['cover'] ?? null;
        
        // Cek apakah jurnal sudah ada di database
        $stmt = $pdo->prepare("SELECT id FROM journals WHERE path = ? LIMIT 1");
        $stmt->execute([$path]);
        $existing = $stmt->fetch();
        
        if ($existing) {
            // Update
            $updateStmt = $pdo->prepare("
                UPDATE journals 
                SET title = ?, description = ?, issn = ?, sinta = ?, warna = ?, link = ?, image = ? 
                WHERE path = ?
            ");
            $updateStmt->execute([$title, $desc, $issn, $sinta, $warna, $url, $cover, $path]);
            $updated++;
        } else {
            // Insert (is_active otomatis default 1)
            $insertStmt = $pdo->prepare("
                INSERT INTO journals (path, title, description, issn, sinta, warna, link, image, is_active)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1)
            ");
            $insertStmt->execute([$path, $title, $desc, $issn, $sinta, $warna, $url, $cover]);
            $inserted++;
        }
    }
    
    echo json_encode([
        'status' => 'success', 
        'message' => "Scraping berhasil! $inserted jurnal baru ditambahkan, $updated jurnal diperbarui."
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()]);
}
