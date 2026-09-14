<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $pageTitle ?? 'OJS Publikasi' ?></title>
  <link rel="stylesheet" href="/assets/css/output.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="min-h-screen flex flex-col">

<!-- Navbar -->
<header class="bg-white shadow-sm sticky top-0 z-50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between h-16">
      <!-- Logo -->
      <a href="/" class="flex items-center gap-2">
        <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center">
          <span class="text-white font-bold text-sm">OJS</span>
        </div>
        <span class="text-xl font-bold text-gray-900">Publikasi</span>
      </a>

      <!-- Navigation -->
      <nav class="hidden md:flex items-center gap-6">
        <a href="/" class="text-gray-600 hover:text-primary-600 font-medium transition-colors">Beranda</a>
        <a href="/pages/journals.php" class="text-gray-600 hover:text-primary-600 font-medium transition-colors">Jurnal</a>
        <a href="/pages/articles.php" class="text-gray-600 hover:text-primary-600 font-medium transition-colors">Artikel</a>
        <a href="/pages/about.php" class="text-gray-600 hover:text-primary-600 font-medium transition-colors">Tentang</a>
      </nav>

      <!-- Search + CTA -->
      <div class="flex items-center gap-3">
        <form action="/pages/search.php" method="GET" class="hidden sm:flex items-center">
          <input
            type="text"
            name="q"
            placeholder="Cari artikel..."
            class="border border-gray-200 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 w-48"
          >
        </form>
        <a href="/pages/submit.php" class="btn-primary text-sm">Submit Artikel</a>
      </div>
    </div>
  </div>
</header>

<!-- Page content wrapper -->
<main class="flex-1">
