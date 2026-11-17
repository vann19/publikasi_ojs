<?php
header("Content-Type: text/xml; charset=UTF-8");
echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

$baseUrl = 'https://nawaedukasi.org';
$today = date('Y-m-d');

// Halaman Statis — dengan lastmod agar Google tahu kapan terakhir diperbarui
$staticPages = [
    ['url' => '/',                 'freq' => 'weekly',  'priority' => '1.0',  'lastmod' => $today],
    ['url' => '/tentang-kami.php', 'freq' => 'monthly', 'priority' => '0.8',  'lastmod' => $today],
    ['url' => '/books/',           'freq' => 'weekly',  'priority' => '0.9',  'lastmod' => $today],
    ['url' => '/jurnal.php',       'freq' => 'weekly',  'priority' => '0.8',  'lastmod' => $today],
    ['url' => '/seminar.php',      'freq' => 'weekly',  'priority' => '0.8',  'lastmod' => $today],
    ['url' => '/layanan.php',      'freq' => 'monthly', 'priority' => '0.8',  'lastmod' => $today],
    ['url' => '/kontak.php',       'freq' => 'monthly', 'priority' => '0.7',  'lastmod' => $today],
];

foreach ($staticPages as $page) {
    echo '<url>';
    echo '<loc>' . $baseUrl . $page['url'] . '</loc>';
    echo '<lastmod>' . $page['lastmod'] . '</lastmod>';
    echo '<changefreq>' . $page['freq'] . '</changefreq>';
    echo '<priority>' . $page['priority'] . '</priority>';
    echo '</url>';
}

// Halaman Dinamis — hanya jika koneksi DB berhasil
try {
    require_once __DIR__ . '/includes/db.php';
    $pdo = getDB();

    // Buku — setiap detail buku mendapat entry sendiri di sitemap
    $books = $pdo->query("SELECT slug, updated_at, created_at FROM books WHERE slug IS NOT NULL AND slug != ''")->fetchAll();
    foreach ($books as $book) {
        echo '<url>';
        echo '<loc>' . $baseUrl . '/books/' . htmlspecialchars($book['slug']) . '/</loc>';
        $lastMod = $book['updated_at'] ?? $book['created_at'];
        if ($lastMod) {
            echo '<lastmod>' . date('Y-m-d', strtotime($lastMod)) . '</lastmod>';
        }
        echo '<changefreq>monthly</changefreq>';
        echo '<priority>0.9</priority>';
        echo '</url>';
    }

    // Seminar (hanya jika tabel ada dan ada data aktif)
    try {
        $seminars = $pdo->query("SELECT id, date FROM seminars WHERE is_active = 1")->fetchAll();
        foreach ($seminars as $seminar) {
            echo '<url>';
            echo '<loc>' . $baseUrl . '/seminar/detail.php?id=' . $seminar['id'] . '</loc>';
            if ($seminar['date']) {
                echo '<lastmod>' . date('Y-m-d', strtotime($seminar['date'])) . '</lastmod>';
            }
            echo '<changefreq>monthly</changefreq>';
            echo '<priority>0.7</priority>';
            echo '</url>';
        }
    } catch (\Exception $e) {
        // Tabel seminars belum ada — lewati saja, sitemap tetap valid
    }

} catch (\Exception $e) {
    // Koneksi DB gagal — hanya tampilkan halaman statis
    // Sitemap tetap valid XML, hanya tidak ada halaman dinamis
}

echo '</urlset>';
?>
