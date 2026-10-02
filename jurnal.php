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
 <section class="relative overflow-hidden">
    <!-- Gambar latar -->
    <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('/assets/img/laptop.png');"></div>
    <!-- Gradien overlay biar teks tetap kebaca -->
    <div class="absolute inset-0 bg-gradient-to-r from-[#1E1B3A]/95 via-[#1E1B3A]/80 to-[#1E1B3A]/30"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 grid lg:grid-cols-2 gap-10 items-center">
      <div>
        <span class="inline-block text-xs font-semibold text-white bg-white/10 border border-white/30 rounded-full px-3 py-1 mb-4">Jurnal Ilmiah (OJS)</span>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-white leading-tight">
          Akses Jurnal Ilmiah <span class="text-primary-300">Terpercaya dan Terakreditasi</span>
        </h1>
        <p class="text-gray-200 mt-4 max-w-md">
          Temukan dan baca berbagai jurnal ilmiah dari Nawa Edukasi yang terbit secara berkala melalui sistem Open Journal Systems (OJS).
        </p>
        <a href="#tentang-ojs" class="inline-flex items-center gap-2 mt-8 px-6 py-3 rounded-lg bg-primary-600 text-white font-semibold hover:bg-primary-700 transition-colors">
          Tentang OJS
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
        </a>
        <p class="text-sm text-gray-300 mt-8">
          <a href="/" class="hover:text-white transition-colors">Beranda</a> / <span class="text-primary-300 font-medium">Jurnal (OJS)</span>
        </p>
      </div>
      <div class="hidden lg:flex justify-center">
      </div>
    </div>
  </section>

  <!-- Trust badges -->
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-10">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm grid grid-cols-2 lg:grid-cols-4 divide-y lg:divide-y-0 lg:divide-x divide-gray-100">
      <div class="p-6 flex items-start gap-3">
        <div class="w-10 h-10 rounded-lg bg-primary-50 flex items-center justify-center shrink-0">
          <svg class="w-6 h-6 text-primary-600" focusable="false" aria-hidden="true" viewBox="0 0 24 24" fill="currentColor"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"></path></svg>
        </div>
        <div>
          <p class="font-semibold text-gray-900 text-sm">Terakreditasi</p>
          <p class="text-xs text-gray-500 mt-0.5">Jurnal kami terakreditasi dan terstandar nasional.</p>
        </div>
      </div>
      <div class="p-6 flex items-start gap-3">
        <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center shrink-0">
          <svg class="w-6 h-6 text-emerald-600" focusable="false" aria-hidden="true" viewBox="0 0 24 24" fill="currentColor"><path d="M21 5c-1.11-.35-2.33-.5-3.5-.5-1.95 0-4.05.4-5.5 1.5-1.45-1.1-3.55-1.5-5.5-1.5S2.45 4.9 1 6v14.65c0 .25.25.5.5.5.1 0 .15-.05.25-.05C3.1 20.45 5.05 20 6.5 20c1.95 0 4.05.4 5.5 1.5 1.35-.85 3.8-1.5 5.5-1.5 1.65 0 3.35.3 4.75 1.05.1.05.15.05.25.05.25 0 .5-.25.5-.5V6c-.6-.45-1.25-.75-2-1zm-1 13.5c-1.1-.35-2.3-.5-3.5-.5-1.7 0-4.15.65-5.5 1.5V8c1.35-.85 3.8-1.5 5.5-1.5 1.2 0 2.4.15 3.5.5v11.5z"></path></svg>
        </div>
        <div>
          <p class="font-semibold text-gray-900 text-sm">Akses Mudah</p>
          <p class="text-xs text-gray-500 mt-0.5">Akses jurnal kapan saja dan di mana saja.</p>
        </div>
      </div>
      <div class="p-6 flex items-start gap-3">
        <div class="w-10 h-10 rounded-lg bg-orange-50 flex items-center justify-center shrink-0">
          <svg class="w-6 h-6 text-orange-600" focusable="false" aria-hidden="true" viewBox="0 0 24 24" fill="currentColor"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"></path></svg>
        </div>
        <div>
          <p class="font-semibold text-gray-900 text-sm">Untuk Semua</p>
          <p class="text-xs text-gray-500 mt-0.5">Terbuka untuk penulis, pembaca, dan reviewer.</p>
        </div>
      </div>
      <div class="p-6 flex items-start gap-3">
        <div class="w-10 h-10 rounded-lg bg-sky-50 flex items-center justify-center shrink-0">
          <svg class="w-6 h-6 text-sky-600" focusable="false" aria-hidden="true" viewBox="0 0 24 24" fill="currentColor"><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"></path></svg>
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
        <div class="jurnal-card rounded-xl border border-gray-100 overflow-hidden shadow-md hover:shadow-lg transition-all duration-200 flex flex-col"
             data-judul="<?php echo strtolower(htmlspecialchars($j['judul'])); ?>">
          <?php if (!empty($j['cover'])): ?>
            <div class="aspect-[3/4] w-full bg-gray-100 overflow-hidden">
              <img src="<?php echo htmlspecialchars($j['cover']); ?>" alt="Cover <?php echo htmlspecialchars($j['judul']); ?>" class="w-full h-full object-cover">
            </div>
          <?php else: ?>
            <div class="aspect-[3/4] <?php echo $j['warna']; ?> p-4 flex flex-col justify-center items-center text-center">
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
      </div>
      <div class="lg:col-span-2 grid sm:grid-cols-2 gap-8">
        <div>
          <h3 class="text-lg font-bold text-gray-900 mb-2">Apa itu OJS?</h3>
          <p class="text-sm text-gray-600 leading-relaxed mb-4">
            OJS (Open Journal Systems) adalah sistem pengelolaan jurnal open source yang digunakan untuk menerbitkan dan mengelola jurnal ilmiah secara online.
          </p>
        <a href="https://e-journal.nawaedukasi.org" target="_blank" rel="noopener" class="inline-flex items-center gap-2 text-sm font-semibold text-primary-700 border border-primary-200 rounded-lg px-4 py-2 hover:bg-white transition-colors">
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