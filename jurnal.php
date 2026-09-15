<?php
$activePage = 'jurnal';
include 'includes/header.php';

// Data dummy jurnal — nanti diganti query dari database / API OJS
$jurnalList = [
  ['judul' => 'Jurnal Pendidikan dan Pembelajaran', 'issn' => '2614-XXXX', 'sinta' => 'SINTA 3', 'deskripsi' => 'Kajian di bidang pendidikan dan pembelajaran.', 'warna' => 'bg-emerald-700'],
  ['judul' => 'Jurnal Teknologi dan Sistem Informasi', 'issn' => '2620-XXXX', 'sinta' => 'SINTA 2', 'deskripsi' => 'Penelitian di bidang teknologi informasi dan komputer.', 'warna' => 'bg-sky-800'],
  ['judul' => 'Jurnal Manajemen dan Bisnis', 'issn' => '2685-XXXX', 'sinta' => 'SINTA 3', 'deskripsi' => 'Kajian penelitian di bidang manajemen, bisnis, dan organisasi.', 'warna' => 'bg-purple-700'],
  ['judul' => 'Jurnal Sains dan Teknologi', 'issn' => '2638-XXXX', 'sinta' => 'SINTA 3', 'deskripsi' => 'Jurnal penelitian di bidang sains dasar dan terapan.', 'warna' => 'bg-teal-700'],
  ['judul' => 'Jurnal Ilmu Sosial dan Humaniora', 'issn' => '2688-XXXX', 'sinta' => 'SINTA 5', 'deskripsi' => 'Publikasi penelitian di bidang sosial dan humaniora.', 'warna' => 'bg-orange-600'],
  ['judul' => 'Jurnal Kesehatan Masyarakat', 'issn' => '2716-XXXX', 'sinta' => 'SINTA 4', 'deskripsi' => 'Kajian penelitian di bidang kesehatan masyarakat dan lingkungan.', 'warna' => 'bg-green-800'],
  ['judul' => 'Jurnal Hukum dan Kebijakan', 'issn' => '2720-XXXX', 'sinta' => 'SINTA 3', 'deskripsi' => 'Kajian hukum dan regulasi di Indonesia.', 'warna' => 'bg-rose-800'],
  ['judul' => 'Jurnal Ekonomi dan Pembangunan', 'issn' => '2688-XXXX', 'sinta' => 'SINTA 4', 'deskripsi' => 'Publikasi penelitian di bidang ekonomi, pembangunan, dan kebijakan.', 'warna' => 'bg-slate-700'],
];

$totalJurnal = 12; // dummy
$jumlahDitampilkan = count($jurnalList);
?>

  <!-- Hero -->
  <section class="bg-primary-50 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 grid lg:grid-cols-2 gap-10 items-center">
      <div>
        <span class="inline-block text-xs font-semibold text-primary-700 bg-white rounded-full px-3 py-1 mb-4">Jurnal Ilmiah (OJS)</span>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 leading-tight">
          Akses Jurnal Ilmiah <span class="text-primary-700">Terpercaya dan Terakreditasi</span>
        </h1>
        <p class="text-gray-500 mt-4 max-w-md">
          Temukan dan baca berbagai jurnal ilmiah dari Nawa Edukasi yang terbit secara berkala melalui sistem Open Journal Systems (OJS).
        </p>
        <a href="#tentang-ojs" class="inline-flex items-center gap-2 mt-8 px-6 py-3 rounded-lg bg-primary-600 text-white font-semibold hover:bg-primary-700 transition-colors">
          Tentang OJS
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
        </a>
        <p class="text-sm text-gray-500 mt-8">
          <a href="/" class="hover:text-primary-700">Beranda</a> / <span class="text-primary-700 font-medium">Jurnal (OJS)</span>
        </p>
      </div>
      <div class="hidden lg:flex justify-center">
        <img src="/assets/img/laptop.png" alt="Tampilan jurnal Nawa Edukasi di sistem OJS" class="w-full max-w-lg h-auto object-contain">
      </div>
    </div>
  </section>

  <!-- Trust badges -->
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-10">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm grid grid-cols-2 lg:grid-cols-4 divide-y lg:divide-y-0 lg:divide-x divide-gray-100">
      <div class="p-6 flex items-start gap-3">
        <div class="w-10 h-10 rounded-lg bg-primary-50 flex items-center justify-center shrink-0">
          <svg class="w-5 h-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.96 11.96 0 013.598 6 11.96 11.96 0 001.5 12c0 5.83 3.4 10.86 8.318 13.246a11.96 11.96 0 004.365 0C19.1 22.86 22.5 17.83 22.5 12a11.96 11.96 0 00-2.098-6 11.96 11.96 0 01-8.402-3.286z"/></svg>
        </div>
        <div>
          <p class="font-semibold text-gray-900 text-sm">Terakreditasi</p>
          <p class="text-xs text-gray-500 mt-0.5">Jurnal kami terakreditasi dan terstandar nasional.</p>
        </div>
      </div>
      <div class="p-6 flex items-start gap-3">
        <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center shrink-0">
          <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.04A8.97 8.97 0 006 3.75c-1.05 0-2.06.18-3 .5v14.25A8.99 8.99 0 016 18c2.3 0 4.4.87 6 2.29m0-14.25a8.97 8.97 0 016-2.29c1.05 0 2.06.18 3 .5v14.25A8.99 8.99 0 0018 18a8.97 8.97 0 00-6 2.29m0-14.25v14.25"/></svg>
        </div>
        <div>
          <p class="font-semibold text-gray-900 text-sm">Akses Mudah</p>
          <p class="text-xs text-gray-500 mt-0.5">Akses jurnal kapan saja dan di mana saja.</p>
        </div>
      </div>
      <div class="p-6 flex items-start gap-3">
        <div class="w-10 h-10 rounded-lg bg-orange-50 flex items-center justify-center shrink-0">
          <svg class="w-5 h-5 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
        </div>
        <div>
          <p class="font-semibold text-gray-900 text-sm">Untuk Semua</p>
          <p class="text-xs text-gray-500 mt-0.5">Terbuka untuk penulis, pembaca, dan reviewer.</p>
        </div>
      </div>
      <div class="p-6 flex items-start gap-3">
        <div class="w-10 h-10 rounded-lg bg-sky-50 flex items-center justify-center shrink-0">
          <svg class="w-5 h-5 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
        </div>
        <div>
          <p class="font-semibold text-gray-900 text-sm">Sistem Aman</p>
          <p class="text-xs text-gray-500 mt-0.5">Dikelola dengan sistem OJS yang aman dan stabil.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Daftar Jurnal -->
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

    <!-- Search + filter -->
    <div class="flex flex-col lg:flex-row gap-3 mb-8">
      <div class="relative flex-1">
        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input type="text" placeholder="Cari judul jurnal, e-ISSN / p-ISSN, atau kata kunci..."
               class="w-full text-sm border border-gray-200 rounded-lg pl-9 pr-4 py-2.5 text-gray-600 focus:outline-none focus:ring-2 focus:ring-primary-200">
      </div>
      <select class="text-sm border border-gray-200 rounded-lg px-4 py-2.5 text-gray-600">
        <option>Semua Bidang</option>
        <option>Pendidikan</option>
        <option>Teknologi</option>
        <option>Ekonomi</option>
        <option>Sosial &amp; Humaniora</option>
      </select>
      <select class="text-sm border border-gray-200 rounded-lg px-4 py-2.5 text-gray-600">
        <option>Status Akreditasi</option>
        <option>SINTA 2</option>
        <option>SINTA 3</option>
        <option>SINTA 4</option>
        <option>SINTA 5</option>
      </select>
      <button type="button" class="px-6 py-2.5 rounded-lg bg-primary-600 text-white text-sm font-semibold hover:bg-primary-700 transition-colors">
        Cari
      </button>
    </div>

    <div class="flex items-end justify-between mb-6">
      <h2 class="text-xl font-bold text-gray-900">Daftar Jurnal</h2>
      <p class="text-sm text-gray-500">Menampilkan 1&ndash;<?php echo $jumlahDitampilkan; ?> dari <?php echo $totalJurnal; ?> jurnal</p>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
      <?php foreach ($jurnalList as $j): ?>
        <div class="rounded-xl border border-gray-100 overflow-hidden hover:shadow-sm transition-shadow flex flex-col">
          <div class="aspect-[4/3] <?php echo $j['warna']; ?> p-4 flex flex-col justify-center items-center text-center">
            <span class="text-white text-xs font-bold uppercase tracking-wide leading-snug"><?php echo htmlspecialchars($j['judul']); ?></span>
          </div>
          <div class="p-4 flex flex-col flex-1">
            <p class="text-xs text-gray-400 mb-1">e-ISSN: <?php echo $j['issn']; ?></p>
            <p class="text-sm text-gray-500 leading-relaxed flex-1"><?php echo htmlspecialchars($j['deskripsi']); ?></p>
            <span class="inline-block w-fit text-xs font-semibold text-primary-700 bg-primary-50 rounded-full px-2.5 py-1 mt-3 mb-3"><?php echo $j['sinta']; ?></span>
            <a href="jurnal-detail.php" class="text-center text-sm font-semibold text-primary-700 border border-primary-200 rounded-lg py-2 hover:bg-primary-50 transition-colors">
              Lihat Jurnal
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <div class="flex items-center justify-center gap-2 mt-12">
      <a href="#" class="w-9 h-9 flex items-center justify-center rounded-lg bg-primary-600 text-white text-sm font-semibold">1</a>
      <a href="#" class="w-9 h-9 flex items-center justify-center rounded-lg border border-gray-200 text-sm text-gray-600 hover:bg-gray-50">2</a>
      <a href="#" class="w-9 h-9 flex items-center justify-center rounded-lg border border-gray-200 text-sm text-gray-600 hover:bg-gray-50">3</a>
      <a href="#" class="w-9 h-9 flex items-center justify-center rounded-lg border border-gray-200 text-gray-400 hover:bg-gray-50">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
      </a>
    </div>
  </section>

  <!-- Apa itu OJS? -->
  <section id="tentang-ojs" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
    <div class="rounded-2xl bg-primary-50 p-8 lg:p-10 grid lg:grid-cols-3 gap-8 items-center">
      <div class="hidden lg:block">
        <img src="/assets/img/laptop.png" alt="Ilustrasi sistem OJS" class="w-full h-auto object-contain">
      </div>
      <div class="lg:col-span-2 grid sm:grid-cols-2 gap-8">
        <div>
          <h3 class="text-lg font-bold text-gray-900 mb-2">Apa itu OJS?</h3>
          <p class="text-sm text-gray-600 leading-relaxed mb-4">
            OJS (Open Journal Systems) adalah sistem pengelolaan jurnal open source yang digunakan untuk menerbitkan dan mengelola jurnal ilmiah secara online.
          </p>
          <a href="#" class="inline-flex items-center gap-2 text-sm font-semibold text-primary-700 border border-primary-200 rounded-lg px-4 py-2 hover:bg-white transition-colors">
            Pelajari Selengkapnya
          </a>
        </div>
        <div>
          <h3 class="text-lg font-bold text-gray-900 mb-3">Untuk Pengguna</h3>
          <ul class="space-y-3 text-sm">
            <li>
              <p class="font-semibold text-gray-900">Penulis</p>
              <p class="text-gray-500">Kirim naskah dan pantau proses review.</p>
            </li>
            <li>
              <p class="font-semibold text-gray-900">Reviewer</p>
              <p class="text-gray-500">Lakukan penelaahan naskah secara online.</p>
            </li>
            <li>
              <p class="font-semibold text-gray-900">Editor</p>
              <p class="text-gray-500">Kelola alur dan proses publikasi.</p>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </section>

<?php include 'includes/footer.php'; ?>