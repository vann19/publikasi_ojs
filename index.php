<?php
$pageTitle = 'OJS Publikasi - Beranda';
include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="bg-gradient-to-br from-primary-700 to-primary-900 text-white py-20">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
    <h1 class="text-4xl md:text-5xl font-bold mb-6 leading-tight">
      Platform Publikasi<br>Jurnal Ilmiah Terbuka
    </h1>
    <p class="text-primary-100 text-lg md:text-xl max-w-2xl mx-auto mb-10">
      Temukan, baca, dan publikasikan artikel ilmiah berkualitas dari berbagai disiplin ilmu.
      Akses terbuka untuk semua kalangan.
    </p>
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
      <a href="/pages/articles.php" class="bg-white text-primary-700 hover:bg-primary-50 font-semibold py-3 px-8 rounded-xl transition-colors">
        Jelajahi Artikel
      </a>
      <a href="/pages/submit.php" class="border-2 border-white text-white hover:bg-white hover:text-primary-700 font-semibold py-3 px-8 rounded-xl transition-colors">
        Submit Artikel
      </a>
    </div>
  </div>
</section>

<!-- Stats Section -->
<section class="bg-white border-b border-gray-100 py-10">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
      <?php
      $stats = [
        ['value' => '1.200+', 'label' => 'Artikel Terbit'],
        ['value' => '45+',    'label' => 'Jurnal Aktif'],
        ['value' => '300+',   'label' => 'Penulis Terdaftar'],
        ['value' => '50K+',   'label' => 'Unduhan Bulan Ini'],
      ];
      foreach ($stats as $stat): ?>
        <div>
          <div class="text-3xl font-bold text-primary-600"><?= $stat['value'] ?></div>
          <div class="text-gray-500 text-sm mt-1"><?= $stat['label'] ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Featured Journals -->
<section class="py-16 bg-gray-50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between mb-10">
      <div>
        <h2 class="text-2xl font-bold text-gray-900">Jurnal Unggulan</h2>
        <p class="text-gray-500 mt-1">Jurnal dengan artikel terbanyak dan terpopuler</p>
      </div>
      <a href="/pages/journals.php" class="btn-secondary text-sm">Lihat Semua</a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php
      $journals = [
        [
          'name'    => 'Jurnal Teknologi Informasi',
          'issn'    => '2301-xxxx',
          'articles'=> 120,
          'category'=> 'Teknologi',
          'color'   => 'bg-blue-100 text-blue-700',
        ],
        [
          'name'    => 'Jurnal Ilmu Kesehatan',
          'issn'    => '2302-xxxx',
          'articles'=> 98,
          'category'=> 'Kesehatan',
          'color'   => 'bg-green-100 text-green-700',
        ],
        [
          'name'    => 'Jurnal Pendidikan & Sains',
          'issn'    => '2303-xxxx',
          'articles'=> 85,
          'category'=> 'Pendidikan',
          'color'   => 'bg-purple-100 text-purple-700',
        ],
      ];
      foreach ($journals as $journal): ?>
        <div class="card hover:shadow-md transition-shadow">
          <div class="flex items-start justify-between mb-4">
            <span class="badge <?= $journal['color'] ?>"><?= $journal['category'] ?></span>
            <span class="text-xs text-gray-400">ISSN: <?= $journal['issn'] ?></span>
          </div>
          <h3 class="text-lg font-semibold text-gray-900 mb-2"><?= $journal['name'] ?></h3>
          <p class="text-sm text-gray-500"><?= $journal['articles'] ?> artikel diterbitkan</p>
          <a href="/pages/journals.php" class="mt-4 inline-flex items-center text-primary-600 hover:text-primary-700 text-sm font-medium">
            Lihat Jurnal →
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Latest Articles -->
<section class="py-16 bg-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between mb-10">
      <div>
        <h2 class="text-2xl font-bold text-gray-900">Artikel Terbaru</h2>
        <p class="text-gray-500 mt-1">Publikasi terbaru dari berbagai bidang ilmu</p>
      </div>
      <a href="/pages/articles.php" class="btn-secondary text-sm">Lihat Semua</a>
    </div>

    <div class="space-y-4">
      <?php
      $articles = [
        [
          'title'   => 'Implementasi Machine Learning untuk Deteksi Penyakit Tanaman',
          'authors' => 'Budi Santoso, Ani Wijaya',
          'journal' => 'Jurnal Teknologi Informasi',
          'date'    => '10 Sep 2026',
          'doi'     => '10.xxxx/jti.2026.001',
        ],
        [
          'title'   => 'Efektivitas Vaksin mRNA pada Populasi Lansia di Indonesia',
          'authors' => 'dr. Siti Rahayu, Prof. Ahmad Maulana',
          'journal' => 'Jurnal Ilmu Kesehatan',
          'date'    => '08 Sep 2026',
          'doi'     => '10.xxxx/jik.2026.045',
        ],
        [
          'title'   => 'Model Pembelajaran Berbasis Proyek dalam Era Digital',
          'authors' => 'Dewi Kusuma, Rahmat Hidayat',
          'journal' => 'Jurnal Pendidikan & Sains',
          'date'    => '05 Sep 2026',
          'doi'     => '10.xxxx/jps.2026.012',
        ],
      ];
      foreach ($articles as $article): ?>
        <div class="card hover:shadow-md transition-shadow flex flex-col sm:flex-row sm:items-center gap-4">
          <div class="flex-1">
            <a href="/pages/articles.php" class="text-base font-semibold text-gray-900 hover:text-primary-600 transition-colors">
              <?= $article['title'] ?>
            </a>
            <div class="flex flex-wrap items-center gap-3 mt-2 text-sm text-gray-500">
              <span>👤 <?= $article['authors'] ?></span>
              <span>📖 <?= $article['journal'] ?></span>
              <span>📅 <?= $article['date'] ?></span>
            </div>
            <div class="mt-1 text-xs text-gray-400">DOI: <?= $article['doi'] ?></div>
          </div>
          <div class="flex gap-2 shrink-0">
            <a href="#" class="btn-primary text-xs py-1.5 px-3">Baca</a>
            <a href="#" class="btn-secondary text-xs py-1.5 px-3">Unduh PDF</a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- CTA Section -->
<section class="bg-primary-700 text-white py-16">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
    <h2 class="text-3xl font-bold mb-4">Siap Publikasikan Penelitian Anda?</h2>
    <p class="text-primary-200 text-lg max-w-xl mx-auto mb-8">
      Bergabunglah dengan ratusan peneliti yang telah mempercayakan karya ilmiah mereka kepada kami.
    </p>
    <a href="/pages/submit.php" class="bg-white text-primary-700 hover:bg-primary-50 font-semibold py-3 px-10 rounded-xl transition-colors inline-block">
      Mulai Submit Sekarang
    </a>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
