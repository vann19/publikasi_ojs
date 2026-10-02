<?php
// router.php
// Ini adalah router khusus untuk PHP Built-in Web Server (php -S)

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Jika file/folder asli ada, biarkan PHP server menanganinya langsung
if (file_exists(__DIR__ . $path) && is_file(__DIR__ . $path)) {
    return false;
}

// Redirect /buku to /books (dari .htaccess)
if (preg_match('#^/buku/(.*)$#', $path, $matches)) {
    header("Location: /books/" . $matches[1], true, 301);
    exit;
}

// Rewrite books/slug to books/detail.php?slug=slug (dari .htaccess)
if (preg_match('#^/books/([^/]+)/?$#', $path, $matches)) {
    // Set parameter GET slug
    $_GET['slug'] = $matches[1];
    
    // Include file detail
    require __DIR__ . '/books/detail.php';
    return true; // Beri tahu built-in server kita sudah menangani ini
}

// Jika bukan file dan tidak cocok dengan rule di atas, coba ke index.php
// atau biarkan PHP return false (404 Not Found default)
return false;
