<?php
$activePage = 'seminar';
include 'includes/header.php';

// Data dummy seminar — field-field ini nanti dipetakan ke tabel "seminar" di database
// (kolom umum: id, judul, tanggal, waktu, lokasi_tipe, lokasi_detail, kategori, status, kuota, harga)
$seminarList = [
  ['judul' => 'Strategi Menembus Jurnal Terindeks SINTA', 'tanggal' => '25 Sep 2026', 'waktu' => '09.00 - 12.00 WIB', 'lokasi_tipe' => 'Online', 'kategori' => 'Publikasi Ilmiah', 'status' => 'Akan Datang', 'harga' => 'Gratis', 'warna' => 'bg-emerald-700'],
  ['judul' => 'Pelatihan Penulisan Buku Ajar bagi Dosen', 'tanggal' => '02 Okt 2026', 'waktu' => '13.00 - 16.00 WIB', 'lokasi_tipe' => 'Offline', 'kategori' => 'Penerbitan Buku', 'status' => 'Akan Datang', 'harga' => 'Rp150.000', 'warna' => 'bg-sky-800'],
  ['judul' => 'Workshop Pendaftaran Hak Cipta dan Paten', 'tanggal' => '10 Okt 2026', 'waktu' => '09.00 - 11.30 WIB', 'lokasi_tipe' => 'Online', 'kategori' => 'HKI', 'status' => 'Akan Datang', 'harga' => 'Gratis', 'warna' => 'bg-purple-700'],
  ['judul' => 'Seminar Nasional Inovasi Pendidikan 2026', 'tanggal' => '18 Okt 2026', 'waktu' => '08.00 - 15.00 WIB', 'lokasi_tipe' => 'Offline', 'kategori' => 'Pendidikan', 'status' => 'Akan Datang', 'harga' => 'Rp100.000', 'warna' => 'bg-teal-700'],
  ['judul' => 'Metode Penelitian Kuantitatif untuk Pemula', 'tanggal' => '20 Agu 2026', 'waktu' => '09.00 - 12.00 WIB', 'lokasi_tipe' => 'Online', 'kategori' => 'Metodologi Penelitian', 'status' => 'Selesai', 'harga' => 'Gratis', 'warna' => 'bg-orange-600'],
  ['judul' => 'Optimalisasi Naskah untuk Penerbit', 'tanggal' => '05 Agu 2026', 'waktu' => '13.00 - 15.00 WIB', 'lokasi_tipe' => 'Online', 'kategori' => 'Penerbitan Buku', 'status' => 'Selesai', 'harga' => 'Gratis', 'warna' => 'bg-rose-800'],
];

$totalSeminar = count($seminarList);
?>

  <!-- Hero -->
  <section class="bg-primary-50 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
      <span class="inline-block text-xs font-semibold text-primary-700 bg-white rounded-full px-3 py-1 mb-4">Seminar &amp; Webinar</span>
      <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 leading-tight max-w-2xl">
        Seminar dan Webinar untuk <span class="text-primary-700">Pengembangan Ilmu dan Riset</span>
      </h1>
      <p class="text-gray-500 mt-4 max-w-lg">
        Ikuti seminar nasional, workshop, dan pelatihan yang diselenggarakan Nawa Edukasi untuk mendukung penulis, peneliti, dan akademisi.
      </p>
      <p class="text-sm text-gray-500 mt-6">
        <a href="/" class="hover:text-primary-700">Beranda</a> / <span class="text-primary-700 font-medium">Seminar</span>
      </p>
    </div>
  </section>

  <!-- Filter -->
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-8">
      <!-- Tab status -->
      <div class="inline-flex bg-gray-100 rounded-lg p-1 w-fit">
        <button type="button" class="px-4 py-2 rounded-md text-sm font-semibold bg-white text-primary-700 shadow-sm">Semua</button>
        <button type="button" class="px-4 py-2 rounded-md text-sm font-medium text-gray-500 hover:text-gray-700">Akan Datang</button>
        <button type="button" class="px-4 py-2 rounded-md text-sm font-medium text-gray-500 hover:text-gray-700">Selesai</button>
      </div>

      <!-- Search + kategori -->
      <div class="flex flex-col sm:flex-row gap-3">
        <div class="relative">
          <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
          <input type="text" placeholder="Cari seminar..."
                 class="text-sm border border-gray-200 rounded-lg pl-9 pr-4 py-2.5 text-gray-600 focus:outline-none focus:ring-2 focus:ring-primary-200 w-full sm:w-64">
        </div>
        <select class="text-sm border border-gray-200 rounded-lg px-4 py-2.5 text-gray-600">
          <option>Semua Kategori</option>
          <option>Publikasi Ilmiah</option>
          <option>Penerbitan Buku</option>
          <option>HKI</option>
          <option>Pendidikan</option>
          <option>Metodologi Penelitian</option>
        </select>
      </div>
    </div>

    <p class="text-sm text-gray-500 mb-6">Menampilkan <?php echo $totalSeminar; ?> seminar</p>

    <!-- Grid seminar -->
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php foreach ($seminarList as $s): ?>
        <div class="rounded-xl border border-gray-100 overflow-hidden hover:shadow-sm transition-shadow flex flex-col">
          <div class="h-32 <?php echo $s['warna']; ?> flex items-center justify-center relative">
            <svg class="w-10 h-10 text-white/70" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.09 9.09 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.94 11.94 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg>
            <span class="absolute top-3 right-3 text-xs font-semibold px-2.5 py-1 rounded-full <?php echo $s['status'] === 'Akan Datang' ? 'bg-white text-emerald-700' : 'bg-white/80 text-gray-500'; ?>">
              <?php echo $s['status']; ?>
            </span>
          </div>
          <div class="p-5 flex flex-col flex-1">
            <span class="text-xs font-semibold text-primary-700 bg-primary-50 rounded-full px-2.5 py-1 w-fit mb-3"><?php echo htmlspecialchars($s['kategori']); ?></span>
            <h3 class="font-semibold text-gray-900 mb-2 leading-snug"><?php echo htmlspecialchars($s['judul']); ?></h3>

            <div class="text-xs text-gray-500 space-y-1.5 mb-4">
              <p class="flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                <?php echo $s['tanggal']; ?> &bull; <?php echo $s['waktu']; ?>
              </p>
              <p class="flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.5-7.5 11.25-7.5 11.25S4.5 18 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                <?php echo $s['lokasi_tipe']; ?>
              </p>
            </div>

            <div class="flex items-center justify-between mt-auto pt-3 border-t border-gray-100">
              <span class="text-sm font-semibold text-gray-900"><?php echo $s['harga']; ?></span>
              <a href="seminar-detail.php"
                 class="text-sm font-semibold <?php echo $s['status'] === 'Akan Datang' ? 'text-white bg-primary-600 hover:bg-primary-700 px-4 py-2 rounded-lg transition-colors' : 'text-primary-700 hover:text-primary-800'; ?>">
                <?php echo $s['status'] === 'Akan Datang' ? 'Daftar Sekarang' : 'Lihat Detail'; ?>
              </a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <div class="flex items-center justify-center gap-2 mt-12">
      <a href="#" class="w-9 h-9 flex items-center justify-center rounded-lg bg-primary-600 text-white text-sm font-semibold">1</a>
      <a href="#" class="w-9 h-9 flex items-center justify-center rounded-lg border border-gray-200 text-sm text-gray-600 hover:bg-gray-50">2</a>
      <a href="#" class="w-9 h-9 flex items-center justify-center rounded-lg border border-gray-200 text-gray-400 hover:bg-gray-50">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
      </a>
    </div>
  </section>

  <!-- CTA -->
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
    <div class="rounded-2xl bg-[#1E1B3A] px-8 py-14 text-center">
      <h2 class="text-2xl font-bold text-white mb-3">Ingin Mengadakan Seminar Bersama Kami?</h2>
      <p class="text-gray-300 max-w-md mx-auto mb-8">Kami membuka kerja sama penyelenggaraan seminar, workshop, dan pelatihan untuk institusi maupun komunitas akademik.</p>
      <a href="https://wa.me/6281916200962" target="_blank" rel="noopener"
         class="inline-flex items-center px-6 py-3 rounded-lg bg-white text-[#1E1B3A] font-semibold hover:bg-gray-100 transition-colors">
        Hubungi via WhatsApp
      </a>
    </div>
  </section>

<?php include 'includes/footer.php'; ?>