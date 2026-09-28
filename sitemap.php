<?php
require_once 'includes/db.php';
$pdo = getDB();

header("Content-Type: text/xml;charset=iso-8859-1");
echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

$baseUrl = 'https://nawaedukasi.org';

// Halaman Statis
$staticPages = [
    '/',
    '/tentang-kami.php',
    '/books/',
    '/jurnal.php',
    '/seminar.php',
    '/layanan.php',
    '/kontak.php'
];

foreach ($staticPages as $page) {
    echo '<url>';
    echo '<loc>' . $baseUrl . $page . '</loc>';
    echo '<changefreq>weekly</changefreq>';
    echo '<priority>' . ($page === '/' ? '1.0' : '0.8') . '</priority>';
    echo '</url>';
}

// Halaman Dinamis - Buku
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

// Halaman Dinamis - Seminar (jika ada detail seminar nantinya)
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

echo '</urlset>';
?>
