<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nawa Edukasi | Penerbitan Buku, Jurnal Ilmiah & Layanan HKI</title>
  <meta name="description" content="PT Nawa Edukasi Nusantara - penerbitan buku, layanan HKI, dan publikasi jurnal ilmiah terpercaya di Indonesia.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/assets/css/output.css">
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
  ?>

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

          <div class="relative group">
            <button class="flex items-center gap-1 hover:text-primary-700 transition-colors <?php echo $activePage === 'buku' ? 'text-primary-700 font-semibold' : ''; ?>">
              Buku
              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
              </svg>
            </button>
            <div class="absolute left-0 top-full pt-3 w-48 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all">
              <div class="bg-white rounded-lg shadow-lg border border-gray-100 py-2">
                <a href="/buku/" class="block px-4 py-2 hover:bg-primary-50 hover:text-primary-700">Katalog Buku</a>
                <a href="/ajukan-naskah" class="block px-4 py-2 hover:bg-primary-50 hover:text-primary-700">Ajukan Naskah</a>
              </div>
            </div>
          </div>

          <?php $navLink('jurnal', 'Jurnal (OJS)', '/jurnal.php'); ?>
          <?php $navLink('seminar', 'Seminar', '/seminar.php'); ?>
          <?php $navLink('layanan', 'Layanan', '/layanan.php'); ?>
          <?php $navLink('kontak', 'Kontak', '/kontak.php'); ?>
        </nav>

        <a href="https://wa.me/6281916200962" target="_blank" rel="noopener"
           class="hidden lg:inline-flex items-center px-5 py-2.5 rounded-lg bg-primary-600 text-white text-sm font-semibold hover:bg-primary-700 transition-colors">
          Hubungi Kami
        </a>

        <button id="mobileMenuBtn" class="lg:hidden text-gray-700" aria-label="Buka menu">
          <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
      </div>
    </div>

    <nav id="mobileMenu" class="hidden lg:hidden border-t border-gray-100 px-4 py-4 space-y-3 text-sm font-medium text-gray-600">
      <?php $navLink('beranda', 'Beranda', '/'); ?>
      <?php $navLink('tentang-kami', 'Tentang Kami', '/tentang-kami.php'); ?>
      <?php $navLink('buku', 'Buku', '/buku/'); ?>
      <?php $navLink('jurnal', 'Jurnal (OJS)', '/jurnal.php'); ?>
      <?php $navLink('seminar', 'Seminar', '/seminar.php'); ?>
      <?php $navLink('layanan', 'Layanan', '/layanan.php'); ?>
      <?php $navLink('kontak', 'Kontak', '/kontak.php'); ?>
      <a href="https://wa.me/6281916200962" class="block px-4 py-2 rounded-lg bg-primary-600 text-white text-center font-semibold">Hubungi Kami</a>
    </nav>
  </header>

  <main>