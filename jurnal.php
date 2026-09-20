<?php
$activePage = 'jurnal';
include 'includes/header.php';
require_once 'includes/db.php';

$pdo = getDB();
$stmt = $pdo->query("SELECT title AS judul, description AS deskripsi, link AS url, image AS cover, issn, sinta, warna FROM journals WHERE is_active = 1 ORDER BY id ASC");
$jurnalList = $stmt->fetchAll();

$totalJurnal = count($jurnalList);
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

    <!-- Search -->
    <div class="mb-8">
      <div class="relative max-w-xl">
        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input type="text" id="search-jurnal" placeholder="Cari nama jurnal..."
               oninput="filterJurnal()"
               class="w-full text-sm border border-gray-200 rounded-lg pl-9 pr-9 py-2.5 text-gray-600 focus:outline-none focus:ring-2 focus:ring-primary-200">
        <button type="button" id="btn-clear-search" onclick="clearSearch()" class="hidden absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
      </div>
    </div>

    <div class="flex items-end justify-between mb-6">
      <h2 class="text-xl font-bold text-gray-900">Daftar Jurnal</h2>
      <p id="jurnal-counter" class="text-sm text-gray-500">Menampilkan <span id="count-tampil"><?php echo $totalJurnal; ?></span> dari <?php echo $totalJurnal; ?> jurnal</p>
    </div>

    <div id="jurnal-grid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
      <?php foreach ($jurnalList as $j): ?>
        <div class="jurnal-card rounded-xl border border-gray-100 overflow-hidden hover:shadow-md transition-all duration-200 flex flex-col"
             data-judul="<?php echo strtolower(htmlspecialchars($j['judul'])); ?>">
          <?php if (!empty($j['cover'])): ?>
            <div class="aspect-[16/7] w-full bg-white flex items-center justify-center overflow-hidden border-b border-gray-100 p-3">
              <img src="<?php echo htmlspecialchars($j['cover']); ?>" alt="Cover <?php echo htmlspecialchars($j['judul']); ?>" class="w-full h-full object-contain">
            </div>
          <?php else: ?>
            <div class="aspect-[4/3] <?php echo $j['warna']; ?> p-4 flex flex-col justify-center items-center text-center">
              <span class="text-white text-xs font-bold uppercase tracking-wide leading-snug"><?php echo htmlspecialchars($j['judul']); ?></span>
            </div>
          <?php endif; ?>
          <div class="p-4 flex flex-col flex-1">
            <p class="font-semibold text-gray-900 text-sm mb-1 leading-snug"><?php echo htmlspecialchars($j['judul']); ?></p>
            <p class="text-xs text-gray-400 mb-3">e-ISSN: <?php echo $j['issn']; ?></p>
            <a href="<?php echo htmlspecialchars($j['url']); ?>" target="_blank" class="mt-auto text-center text-sm font-semibold text-primary-700 border border-primary-200 rounded-lg py-2 hover:bg-primary-50 transition-colors">
              Lihat Jurnal
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Empty State (tersembunyi secara default) -->
    <div id="jurnal-empty" class="hidden py-16 flex flex-col items-center justify-center text-center">
      <svg class="w-14 h-14 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
      <p class="text-gray-500 font-semibold">Jurnal tidak ditemukan</p>
      <p class="text-sm text-gray-400 mt-1">Coba kata kunci atau filter yang berbeda.</p>
      <button onclick="clearSearch()" class="mt-4 text-sm text-primary-600 hover:underline font-medium">Tampilkan semua jurnal</button>
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

<script>
function filterJurnal() {
  const keyword  = document.getElementById('search-jurnal').value.toLowerCase().trim();
  const cards    = document.querySelectorAll('.jurnal-card');
  const btnClear = document.getElementById('btn-clear-search');

  btnClear.classList.toggle('hidden', keyword === '');

  let visible = 0;
  cards.forEach(card => {
    const judul = card.dataset.judul || '';
    const match = keyword === '' || judul.includes(keyword);

    if (match) {
      card.classList.remove('hidden');
      card.style.opacity = '0';
      card.style.transform = 'translateY(8px)';
      setTimeout(() => {
        card.style.transition = 'opacity 0.2s ease, transform 0.2s ease';
        card.style.opacity = '1';
        card.style.transform = 'translateY(0)';
      }, 10);
      visible++;
    } else {
      card.classList.add('hidden');
    }
  });

  document.getElementById('count-tampil').textContent = visible;

  const emptyEl = document.getElementById('jurnal-empty');
  const gridEl  = document.getElementById('jurnal-grid');
  if (visible === 0) {
    gridEl.classList.add('hidden');
    emptyEl.classList.remove('hidden');
  } else {
    gridEl.classList.remove('hidden');
    emptyEl.classList.add('hidden');
  }
}

function clearSearch() {
  document.getElementById('search-jurnal').value = '';
  filterJurnal();
}
</script>

<?php include 'includes/footer.php'; ?>