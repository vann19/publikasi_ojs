<?php
$activePage = 'seminar';
include 'includes/header.php';
require_once 'includes/db.php';

$pdo = getDB();
$seminarList = $pdo->query("SELECT * FROM seminars WHERE is_active = 1 ORDER BY date ASC, created_at DESC")->fetchAll();
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
        <button type="button" data-filter="semua"
                class="tab-btn px-4 py-2 rounded-md text-sm font-semibold bg-white text-primary-700 shadow-sm transition-all">
          Semua
        </button>
        <button type="button" data-filter="akan-datang"
                class="tab-btn px-4 py-2 rounded-md text-sm font-medium text-gray-500 hover:text-gray-700 transition-all">
          Akan Datang
        </button>
        <button type="button" data-filter="selesai"
                class="tab-btn px-4 py-2 rounded-md text-sm font-medium text-gray-500 hover:text-gray-700 transition-all">
          Selesai
        </button>
      </div>

      <!-- Search + kategori -->
      <div class="flex flex-col sm:flex-row gap-3">
        <div class="relative">
          <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
          <input type="text" id="search-input" placeholder="Cari seminar..."
                 class="text-sm border border-gray-200 rounded-lg pl-9 pr-4 py-2.5 text-gray-600 focus:outline-none focus:ring-2 focus:ring-primary-200 w-full sm:w-64">
        </div>
        <select id="kategori-select" class="text-sm border border-gray-200 rounded-lg px-4 py-2.5 text-gray-600 focus:outline-none focus:ring-2 focus:ring-primary-200">
          <option value="">Semua Kategori</option>
          <option value="publikasi ilmiah">Publikasi Ilmiah</option>
          <option value="penerbitan buku">Penerbitan Buku</option>
          <option value="hki">HKI</option>
          <option value="pendidikan">Pendidikan</option>
          <option value="metodologi penelitian">Metodologi Penelitian</option>
        </select>
      </div>
    </div>

    <p class="text-sm text-gray-500 mb-6">Menampilkan <span id="counter"><?php echo $totalSeminar; ?></span> seminar</p>

    <!-- Grid seminar -->
    <div id="seminar-grid" class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php if (empty($seminarList)): ?>
        <p class="text-sm text-gray-500 col-span-full py-8 text-center">Belum ada seminar yang dijadwalkan.</p>
      <?php else: ?>
      <?php foreach ($seminarList as $s): ?>
        <?php
          $isUpcoming = $s['date'] && strtotime($s['date']) >= strtotime('today');
          $statusLabel = $isUpcoming ? 'Akan Datang' : 'Selesai';
          $statusData  = $isUpcoming ? 'akan-datang' : 'selesai';
          $tanggalFormatted = $s['date'] ? date('d M Y', strtotime($s['date'])) : '-';
          $titleLower = strtolower($s['title'] . ' ' . $s['description'] . ' ' . $s['location']);
        ?>
        <div class="seminar-card rounded-xl border border-gray-100 overflow-hidden shadow-md hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 flex flex-col group"
             data-status="<?php echo $statusData; ?>"
             data-title="<?php echo htmlspecialchars(strtolower($s['title'] . ' ' . $s['description'] . ' ' . $s['location'])); ?>">

          <!-- Cover / Poster -->
          <div class="h-40 bg-[#1E1B3A] flex items-center justify-center relative overflow-hidden">
            <?php if ($s['image']): ?>
              <img src="<?php echo htmlspecialchars($s['image']); ?>" alt="<?php echo htmlspecialchars($s['title']); ?>" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
            <?php else: ?>
              <svg class="w-10 h-10 text-white/40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.09 9.09 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.94 11.94 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg>
            <?php endif; ?>
            <span class="absolute top-3 right-3 text-xs font-semibold px-2.5 py-1 rounded-full <?php echo $isUpcoming ? 'bg-white text-emerald-700' : 'bg-white/80 text-gray-500'; ?>">
              <?php echo $statusLabel; ?>
            </span>
          </div>

          <div class="p-5 flex flex-col flex-1">
            <h3 class="font-semibold text-gray-900 mb-3 leading-snug"><?php echo htmlspecialchars($s['title']); ?></h3>

            <?php if ($s['description']): ?>
              <p class="text-xs text-gray-500 mb-3 line-clamp-2"><?php echo htmlspecialchars($s['description']); ?></p>
            <?php endif; ?>

            <div class="text-xs text-gray-500 space-y-1.5 mb-4">
              <?php if ($s['date']): ?>
              <p class="flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                <?php echo $tanggalFormatted; ?>
              </p>
              <?php endif; ?>
              <?php if ($s['location']): ?>
              <p class="flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.5-7.5 11.25-7.5 11.25S4.5 18 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                <?php echo htmlspecialchars($s['location']); ?>
              </p>
              <?php endif; ?>
            </div>

            <p class="text-sm font-bold <?php echo $s['price'] ? 'text-primary-700' : 'text-emerald-600'; ?> mt-2">
              <?php echo $s['price'] ? 'Rp' . number_format($s['price'], 0, ',', '.') : 'Gratis'; ?>
            </p>

            <div class="mt-auto pt-3 space-y-2">
              <!-- Tombol Detail — full width seperti di buku/jurnal -->
              <a href="/seminar/detail.php?id=<?php echo $s['id']; ?>"
                 class="block text-center text-xs font-semibold text-primary-700 border border-primary-200 rounded-lg py-2 hover:bg-primary-50 transition-colors">
                Lihat Detail
              </a>
              <?php if ($s['link'] && $isUpcoming): ?>
                <a href="<?php echo htmlspecialchars($s['link']); ?>" target="_blank" rel="noopener"
                   class="block text-center text-xs font-semibold text-white bg-primary-600 rounded-lg py-2 hover:bg-primary-700 transition-colors">
                  Daftar Sekarang
                </a>
              <?php elseif ($isUpcoming): ?>
                <?php
                  $pesanWA = "Halo, saya ingin bertanya tentang seminar \"{$s['title']}\".";
                  $linkWA  = 'https://wa.me/6281916200962?text=' . urlencode($pesanWA);
                ?>
                <a href="<?php echo $linkWA; ?>" target="_blank" rel="noopener"
                   class="flex items-center justify-center gap-1.5 text-xs font-semibold text-white bg-green-600 rounded-lg py-2 hover:bg-green-700 transition-colors">
                  <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.04 2.25c-5.46 0-9.88 4.42-9.88 9.88 0 1.74.46 3.44 1.33 4.94L2 21.75l4.8-1.46a9.83 9.83 0 004.24.98h.01c5.46 0 9.88-4.42 9.88-9.88 0-5.46-4.42-9.88-9.89-9.88Zm5.7 14.02c-.24.68-1.4 1.3-1.93 1.34-.5.05-.99.24-3.32-.7-2.8-1.14-4.6-3.98-4.75-4.16-.14-.19-1.14-1.52-1.14-2.9 0-1.37.72-2.05.98-2.33.26-.28.56-.35.75-.35h.53c.17 0 .4-.06.62.48.24.58.8 2 .87 2.14.07.14.12.31.02.5-.1.19-.15.31-.3.48-.15.17-.31.38-.44.51-.15.15-.3.3-.13.6.17.3.77 1.27 1.65 2.06 1.14 1.02 2.09 1.34 2.39 1.49.3.15.47.13.65-.08.17-.2.74-.86.94-1.16.2-.3.4-.25.66-.15.27.1 1.72.81 2.02.96.3.15.5.22.57.35.07.13.07.75-.17 1.43Z"/></svg>
                  Hubungi via WhatsApp
                </a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <!-- Empty state (ditampilkan via JS jika tidak ada hasil) -->
    <div id="empty-state" class="hidden py-16 text-center">
      <svg class="w-12 h-12 text-gray-200 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z"/>
      </svg>
      <p class="text-gray-400 text-sm font-medium">Tidak ada seminar yang sesuai filter.</p>
      <button id="reset-filter" class="mt-4 text-sm text-primary-600 hover:text-primary-800 font-semibold">Reset Filter</button>
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

<script>
(function () {
  const cards      = document.querySelectorAll('.seminar-card');
  const grid       = document.getElementById('seminar-grid');
  const emptyState = document.getElementById('empty-state');
  const counter    = document.getElementById('counter');
  const searchInput    = document.getElementById('search-input');
  const kategoriSelect = document.getElementById('kategori-select');
  const tabBtns    = document.querySelectorAll('.tab-btn');
  const resetBtn   = document.getElementById('reset-filter');

  let activeTab     = 'semua';
  let searchQuery   = '';
  let activeKategori = '';

  // Keyword mapping per kategori ke kata kunci yang dicari di data-title
  const kategoriKeywords = {
    'publikasi ilmiah'      : ['publikasi', 'jurnal', 'ilmiah', 'artikel', 'scopus', 'sinta'],
    'penerbitan buku'       : ['buku', 'penerbitan', 'isbn', 'monograf'],
    'hki'                   : ['hki', 'hak kekayaan intelektual', 'paten', 'hak cipta'],
    'pendidikan'            : ['pendidikan', 'pembelajaran', 'kurikulum', 'dosen', 'guru'],
    'metodologi penelitian' : ['metodologi', 'penelitian', 'riset', 'skripsi', 'tesis', 'disertasi'],
  };

  function applyFilters() {
    let visibleCount = 0;

    cards.forEach(function (card) {
      const status    = card.dataset.status;   // 'akan-datang' atau 'selesai'
      const titleData = card.dataset.title;    // lowercase title+desc+location

      // Filter tab
      const tabMatch = activeTab === 'semua' || status === activeTab;

      // Filter search
      const searchMatch = searchQuery === '' || titleData.includes(searchQuery);

      // Filter kategori — cocokkan keyword ke data-title
      let kategoriMatch = true;
      if (activeKategori !== '') {
        const keywords = kategoriKeywords[activeKategori] || [activeKategori];
        kategoriMatch = keywords.some(function (kw) { return titleData.includes(kw); });
      }

      const visible = tabMatch && searchMatch && kategoriMatch;
      card.style.display = visible ? '' : 'none';
      if (visible) visibleCount++;
    });

    counter.textContent = visibleCount;

    if (visibleCount === 0) {
      emptyState.classList.remove('hidden');
    } else {
      emptyState.classList.add('hidden');
    }
  }

  // Tab click
  tabBtns.forEach(function (btn) {
    btn.addEventListener('click', function () {
      activeTab = btn.dataset.filter;

      // Update active style
      tabBtns.forEach(function (b) {
        b.classList.remove('bg-white', 'text-primary-700', 'shadow-sm', 'font-semibold');
        b.classList.add('text-gray-500', 'font-medium');
      });
      btn.classList.add('bg-white', 'text-primary-700', 'shadow-sm', 'font-semibold');
      btn.classList.remove('text-gray-500', 'font-medium');

      applyFilters();
    });
  });

  // Search input
  searchInput.addEventListener('input', function () {
    searchQuery = searchInput.value.trim().toLowerCase();
    applyFilters();
  });

  // Kategori select
  kategoriSelect.addEventListener('change', function () {
    activeKategori = kategoriSelect.value.toLowerCase();
    applyFilters();
  });

  // Reset filter
  resetBtn.addEventListener('click', function () {
    activeTab      = 'semua';
    searchQuery    = '';
    activeKategori = '';

    searchInput.value    = '';
    kategoriSelect.value = '';

    tabBtns.forEach(function (b) {
      b.classList.remove('bg-white', 'text-primary-700', 'shadow-sm', 'font-semibold');
      b.classList.add('text-gray-500', 'font-medium');
    });
    tabBtns[0].classList.add('bg-white', 'text-primary-700', 'shadow-sm', 'font-semibold');
    tabBtns[0].classList.remove('text-gray-500', 'font-medium');

    applyFilters();
  });
})();
</script>

<?php include 'includes/footer.php'; ?>
