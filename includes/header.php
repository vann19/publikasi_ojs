<?php
// Default SEO tags jika tidak di-set dari halaman masing-masing
$seoTitle = $seoTitle ?? 'Nawa Edukasi | Penerbitan Buku, Jurnal Ilmiah & Layanan HKI';
$seoDesc  = $seoDesc ?? 'PT Nawa Edukasi Nusantara - penerbitan buku, layanan HKI, dan publikasi jurnal ilmiah terpercaya di Indonesia.';
$seoImage = $seoImage ?? 'https://nawaedukasi.org/assets/img/logo.png';
$seoUrl   = $seoUrl ?? 'https://nawaedukasi.org' . $_SERVER['REQUEST_URI'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <!-- Basic SEO -->
  <title><?php echo htmlspecialchars($seoTitle); ?></title>
  <meta name="description" content="<?php echo htmlspecialchars($seoDesc); ?>">
  
  <!-- Favicon -->
  <link rel="icon" type="image/png" href="/assets/img/logo.png">
  <link rel="apple-touch-icon" href="/assets/img/logo.png">
  
  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website">
  <meta property="og:url" content="<?php echo htmlspecialchars($seoUrl); ?>">
  <meta property="og:title" content="<?php echo htmlspecialchars($seoTitle); ?>">
  <meta property="og:description" content="<?php echo htmlspecialchars($seoDesc); ?>">
  <meta property="og:image" content="<?php echo htmlspecialchars($seoImage); ?>">
  
  <!-- Twitter -->
  <meta property="twitter:card" content="summary_large_image">
  <meta property="twitter:url" content="<?php echo htmlspecialchars($seoUrl); ?>">
  <meta property="twitter:title" content="<?php echo htmlspecialchars($seoTitle); ?>">
  <meta property="twitter:description" content="<?php echo htmlspecialchars($seoDesc); ?>">
  <meta property="twitter:image" content="<?php echo htmlspecialchars($seoImage); ?>">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/assets/css/output.css">
  
  <!-- AOS CSS -->
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>
<body class="bg-white font-sans text-gray-800 antialiased">

  <?php
    $activePage = $activePage ?? 'beranda';
    $navLink = function ($page, $label, $href) use ($activePage) {
      $active = $activePage === $page;
      $class = $active
        ? 'text-primary-700 font-semibold'
        : 'hover:text-primary-700 transition-colors';
      echo '<a href="' . $href . '" class="' . $class . '">' . $label . '</a>';
    };
    $navLinkMobile = function ($page, $label, $href) use ($activePage) {
      $active = $activePage === $page;
      $class = $active
        ? 'block w-full py-3 px-2 text-primary-700 font-semibold border-b border-gray-100'
        : 'block w-full py-3 px-2 hover:text-primary-700 transition-colors border-b border-gray-100';
      echo '<a href="' . $href . '" class="' . $class . '">' . $label . '</a>';
    };
  ?>

  <?php if (!isset($hideNavbar) || !$hideNavbar): ?>
  <!-- Navbar -->
  <header class="sticky top-0 z-50 bg-white/90 backdrop-blur border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-20">

        <a href="/" class="flex items-center">
          <img src="/assets/img/logo.png" alt="Nawa Edukasi" class="h-10 w-auto object-contain">
        </a>

        <nav class="hidden lg:flex items-center gap-8 text-sm font-medium text-gray-600">
          <?php $navLink('beranda', 'Beranda', '/'); ?>
          <?php $navLink('tentang-kami', 'Tentang Kami', '/tentang-kami.php'); ?>

          <?php $navLink('buku', 'Buku', '/books/'); ?>

          <?php $navLink('jurnal', 'Jurnal (OJS)', '/jurnal.php'); ?>
          <?php $navLink('seminar', 'Seminar', '/seminar.php'); ?>
          <?php $navLink('layanan', 'Layanan', '/layanan.php'); ?>
          <?php $navLink('kontak', 'Kontak', '/kontak.php'); ?>
        </nav>

        <a href="https://wa.me/6281916200962" target="_blank" rel="noopener"
           class="hidden lg:inline-flex items-center px-5 py-2.5 rounded-lg bg-primary-600 text-white text-sm font-semibold hover:bg-primary-700 transition-colors">
          Hubungi Kami
        </a>

        <button id="mobileMenuBtn" class="lg:hidden text-gray-700 p-1" aria-label="Buka menu">
          <!-- Hamburger icon -->
          <svg id="iconHamburger" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
          <!-- Close icon -->
          <svg id="iconClose" class="w-7 h-7 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </div>

    <nav id="mobileMenu" class="hidden lg:hidden border-t border-gray-100 text-sm font-medium text-gray-600 bg-white shadow-md">
      <?php $navLinkMobile('beranda', 'Beranda', '/'); ?>
      <?php $navLinkMobile('tentang-kami', 'Tentang Kami', '/tentang-kami.php'); ?>

      <?php $navLinkMobile('buku', 'Buku', '/books/'); ?>

      <?php $navLinkMobile('jurnal', 'Jurnal (OJS)', '/jurnal.php'); ?>
      <?php $navLinkMobile('seminar', 'Seminar', '/seminar.php'); ?>
      <?php $navLinkMobile('layanan', 'Layanan', '/layanan.php'); ?>
      <?php $navLinkMobile('kontak', 'Kontak', '/kontak.php'); ?>
      <div class="p-4">
        <a href="https://wa.me/6281916200962" target="_blank" rel="noopener" class="flex items-center justify-center w-full px-4 py-3 rounded-lg bg-primary-600 text-white text-center font-semibold hover:bg-primary-700 transition-colors">
          Hubungi Kami
        </a>
      </div>
    </nav>
  </header>
  <?php endif; ?>


  <main>