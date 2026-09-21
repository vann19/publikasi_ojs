<?php
$activePage = 'buku';
include '../includes/header.php';

require_once '../includes/db.php';
$pdo = getDB();

$whatsappNomor = '6281916200962';

// ── Ambil parameter filter dari URL ──────────────────────────────────────
$q        = trim($_GET['q'] ?? '');
$kategori = trim($_GET['kategori'] ?? '');
$harga    = trim($_GET['harga'] ?? '');
$tahun    = trim($_GET['tahun'] ?? '');
$urut     = trim($_GET['urut'] ?? 'terbaru');
$page     = max(1, (int)($_GET['page'] ?? 1));
$perPage  = 12;

// ── Bangun WHERE dinamis (prepared statement, aman dari SQL injection) ──
$where  = [];
$params = [];

if ($q !== '') {
    $where[] = '(title LIKE :q OR author LIKE :q)';
    $params['q'] = '%' . $q . '%';
}
if ($kategori !== '') {
    $where[] = 'LOWER(TRIM(category)) = LOWER(TRIM(:kategori))';
    $params['kategori'] = $kategori;
}
if ($tahun !== '') {
    $where[] = 'YEAR(published_date) = :tahun';
    $params['tahun'] = $tahun;
}
if ($harga === 'lt50') {
    $where[] = 'harga < 50000';
} elseif ($harga === '50-100') {
    $where[] = 'harga BETWEEN 50000 AND 100000';
} elseif ($harga === '100-150') {
    $where[] = 'harga BETWEEN 100000 AND 150000';
} elseif ($harga === 'gt150') {
    $where[] = 'harga > 150000';
}

$whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

// ── Urutan (whitelist, tidak boleh dari input langsung) ──
$sortMap = [
    'terbaru'  => 'created_at DESC',
    'termurah' => 'harga ASC',
    'termahal' => 'harga DESC',
    'judul'    => 'title ASC',
];
$orderBy = $sortMap[$urut] ?? $sortMap['terbaru'];

// ── Hitung total untuk pagination ──
$stmtCount = $pdo->prepare("SELECT COUNT(*) FROM books $whereSql");
$stmtCount->execute($params);
$totalBuku = (int) $stmtCount->fetchColumn();
$totalPages = max(1, (int) ceil($totalBuku / $perPage));
$page = min($page, $totalPages);
$offset = ($page - 1) * $perPage;

// ── Ambil data buku sesuai filter + urutan + halaman ──
$stmt = $pdo->prepare("SELECT * FROM books $whereSql ORDER BY $orderBy LIMIT :limit OFFSET :offset");
foreach ($params as $key => $val) {
    $stmt->bindValue(':' . $key, $val);
}
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$bukuList = $stmt->fetchAll();

$jumlahDitampilkan = count($bukuList);

// ── Kategori dinamis + jumlah tiap kategori ──
$kategoriRows = $pdo->query("SELECT COALESCE(NULLIF(category,''),'Lainnya') AS nama, COUNT(*) AS jumlah FROM books GROUP BY nama ORDER BY nama ASC")->fetchAll();
$totalSemuaBuku = (int) $pdo->query("SELECT COUNT(*) FROM books")->fetchColumn();

$kategoriList = [
    ['nama' => 'Semua Kategori', 'jumlah' => $totalSemuaBuku, 'value' => ''],
];
foreach ($kategoriRows as $row) {
    $kategoriList[] = ['nama' => $row['nama'], 'jumlah' => (int) $row['jumlah'], 'value' => $row['nama']];
}

// ── Tahun terbit dinamis ──
$tahunList = $pdo->query("SELECT DISTINCT YEAR(published_date) AS thn FROM books WHERE published_date IS NOT NULL ORDER BY thn DESC")->fetchAll(PDO::FETCH_COLUMN);

// ── Helper: bikin URL query string, pertahankan filter lain, timpa yang di-override ──
function bukuQueryUrl(array $override = []) {
    $params = array_merge($_GET, $override);
    // buang parameter kosong biar URL bersih
    $params = array_filter($params, fn($v) => $v !== '' && $v !== null);
    return '?' . http_build_query($params);
}
?>

  <!-- Hero Banner -->
   <section class="relative overflow-hidden">
    <!-- Gambar latar -->
    <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('/assets/img/buku.png');"></div>
    <!-- Gradien overlay biar teks tetap kebaca -->
    <div class="absolute inset-0 bg-gradient-to-r from-[#1E1B3A]/95 via-[#1E1B3A]/80 to-[#1E1B3A]/30"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
      <div class="max-w-xl">
        <span class="inline-block text-xs font-semibold text-white bg-white/10 border border-white/30 rounded-full px-3 py-1 mb-4">
          Katalog Buku
        </span>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-white leading-tight">
          Temukan Buku Berkualitas untuk <span class="text-primary-300">Ilmu dan Inspirasi</span>
        </h1>
        <p class="text-gray-200 mt-4 max-w-md">
          Berbagai buku terbitan Nawa Edukasi yang membahas pendidikan, teknologi, metodologi penelitian, dan banyak topik lainnya.
        </p>
        <p class="text-sm text-gray-300 mt-6">
          <a href="/" class="hover:text-white transition-colors">Beranda</a> / <span class="text-primary-300 font-medium">Buku</span>
        </p>
      </div>
    </div>
  </section>

  <!-- Semua filter dalam satu form GET, biar bisa dikombinasi -->
  <form method="GET" action="">

  <!-- Konten: sidebar + grid -->
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 grid lg:grid-cols-4 gap-8">

    <!-- Sidebar -->
    <aside class="lg:col-span-1 space-y-6">

      <!-- Kategori -->
      <div class="rounded-xl border border-gray-100 p-5">
        <h3 class="font-semibold text-gray-900 mb-3">Kategori</h3>
        <ul class="space-y-1">
          <?php foreach ($kategoriList as $k): ?>
            <li>
              <a href="<?php echo htmlspecialchars(bukuQueryUrl(['kategori' => $k['value'], 'page' => null])); ?>"
                 class="flex items-center justify-between px-3 py-2 rounded-lg text-sm <?php echo $kategori === $k['value'] ? 'bg-primary-50 text-primary-700 font-semibold' : 'text-gray-600 hover:bg-gray-50'; ?>">
                <span><?php echo htmlspecialchars($k['nama']); ?></span>
                <span class="text-xs text-gray-400"><?php echo $k['jumlah']; ?></span>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>

      <!-- Filter Harga & Tahun -->
      <div class="rounded-xl border border-gray-100 p-5">
        <h3 class="font-semibold text-gray-900 mb-3">Filter</h3>

        <p class="text-sm font-medium text-gray-700 mb-2">Harga</p>
        <div class="space-y-2 text-sm text-gray-600 mb-5">
          <label class="flex items-center gap-2">
            <input type="radio" name="harga" value="" <?php echo $harga === '' ? 'checked' : ''; ?> class="accent-primary-600 w-4 h-4"> Semua Harga
          </label>
          <label class="flex items-center gap-2">
            <input type="radio" name="harga" value="lt50" <?php echo $harga === 'lt50' ? 'checked' : ''; ?> class="accent-primary-600 w-4 h-4"> &lt; Rp50.000
          </label>
          <label class="flex items-center gap-2">
            <input type="radio" name="harga" value="50-100" <?php echo $harga === '50-100' ? 'checked' : ''; ?> class="accent-primary-600 w-4 h-4"> Rp50.000 &ndash; Rp100.000
          </label>
          <label class="flex items-center gap-2">
            <input type="radio" name="harga" value="100-150" <?php echo $harga === '100-150' ? 'checked' : ''; ?> class="accent-primary-600 w-4 h-4"> Rp100.000 &ndash; Rp150.000
          </label>
          <label class="flex items-center gap-2">
            <input type="radio" name="harga" value="gt150" <?php echo $harga === 'gt150' ? 'checked' : ''; ?> class="accent-primary-600 w-4 h-4"> &gt; Rp150.000
          </label>
        </div>

        <p class="text-sm font-medium text-gray-700 mb-2">Tahun Terbit</p>
        <select name="tahun" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 text-gray-600 mb-5">
          <option value="">Pilih Tahun</option>
          <?php foreach ($tahunList as $thn): ?>
            <option value="<?php echo $thn; ?>" <?php echo $tahun == $thn ? 'selected' : ''; ?>><?php echo $thn; ?></option>
          <?php endforeach; ?>
        </select>

        <?php if ($kategori !== ''): ?>
          <input type="hidden" name="kategori" value="<?php echo htmlspecialchars($kategori); ?>">
        <?php endif; ?>

        <button type="submit" class="w-full text-sm font-semibold text-white bg-primary-600 rounded-lg py-2 hover:bg-primary-700 transition-colors mb-2">
          Terapkan Filter
        </button>
        <a href="buku/index.php" class="block text-center w-full text-sm font-semibold text-primary-700 border border-primary-200 rounded-lg py-2 hover:bg-primary-50 transition-colors">
          Reset Filter
        </a>
      </div>
    </aside>

    <!-- Grid buku -->
    <div class="lg:col-span-3">

      <!-- Search + sort -->
      <div class="flex flex-col sm:flex-row gap-4 mb-6">
        <div class="relative flex-1">
          <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
          <input type="text" name="q" value="<?php echo htmlspecialchars($q); ?>" placeholder="Cari judul buku, penulis, atau kata kunci..."
                 class="w-full text-sm border border-gray-200 rounded-lg pl-9 pr-4 py-2.5 text-gray-600 focus:outline-none focus:ring-2 focus:ring-primary-200">
        </div>
        <div class="flex items-center gap-2 text-sm text-gray-500">
          <span>Urutkan:</span>
          <select name="urut" onchange="this.form.submit()" class="border border-gray-200 rounded-lg px-3 py-2 text-gray-700">
            <option value="terbaru" <?php echo $urut === 'terbaru' ? 'selected' : ''; ?>>Terbaru</option>
            <option value="termurah" <?php echo $urut === 'termurah' ? 'selected' : ''; ?>>Termurah</option>
            <option value="termahal" <?php echo $urut === 'termahal' ? 'selected' : ''; ?>>Termahal</option>
            <option value="judul" <?php echo $urut === 'judul' ? 'selected' : ''; ?>>Judul A-Z</option>
          </select>
        </div>
        <button type="submit" class="sm:hidden px-4 py-2.5 rounded-lg bg-primary-600 text-white text-sm font-semibold">Cari</button>
      </div>

      <p class="text-sm text-gray-500 mb-5">
        <?php if ($totalBuku > 0): ?>
          Menampilkan <?php echo $offset + 1; ?>&ndash;<?php echo $offset + $jumlahDitampilkan; ?> dari <?php echo $totalBuku; ?> buku
        <?php else: ?>
          Tidak ada buku yang cocok dengan pencarian/filter Anda
        <?php endif; ?>
      </p>

      <!-- Grid: items-stretch + h-full di tiap card biar tingginya seragam -->
      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5 items-stretch">
        <?php foreach ($bukuList as $buku): ?>
          <?php
            $hargaAngka = $buku['harga'];
            $hargaText  = $hargaAngka ? 'Rp' . number_format($hargaAngka, 0, ',', '.') : 'Hubungi kami';
            $pesanWA    = "Halo, saya ingin memesan buku \"{$buku['title']}\" ({$hargaText}).";
            $linkWA     = 'https://wa.me/' . $whatsappNomor . '?text=' . urlencode($pesanWA);
          ?>
          <div class="h-full flex flex-col rounded-xl border border-gray-100 overflow-hidden shadow-md hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 bg-white group">

            <!-- Cover: tinggi tetap, konsisten di semua card -->
            <a href="detail.php?id=<?php echo $buku['id']; ?>" class="block aspect-[3/4] bg-gray-100 overflow-hidden">
              <?php if ($buku['image']): ?>
                <img src="<?php echo htmlspecialchars($buku['image']); ?>" alt="<?php echo htmlspecialchars($buku['title']); ?>" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
              <?php else: ?>
                <div class="w-full h-full flex items-center justify-center p-4">
                  <span class="text-gray-400 text-xs font-semibold text-center leading-snug"><?php echo htmlspecialchars($buku['title']); ?></span>
                </div>
              <?php endif; ?>
            </a>

            <!-- Konten: flex-1 supaya tombol selalu di bawah, tingginya nyesuaiin card lain -->
            <div class="p-4 flex flex-col flex-1">
              <?php if (!empty($buku['category'])): ?>
                <p class="text-[11px] font-semibold text-primary-600 uppercase tracking-wide mb-1 truncate"><?php echo htmlspecialchars($buku['category']); ?></p>
              <?php endif; ?>

              <!-- min-h jaga 2 baris judul biar card lain nggak ikut naik-turun -->
              <p class="text-sm font-semibold text-gray-900 leading-snug line-clamp-2 min-h-[2.5rem]"><?php echo htmlspecialchars($buku['title']); ?></p>

              <p class="text-xs text-gray-500 mt-1 line-clamp-1"><?php echo htmlspecialchars($buku['author']); ?></p>

              <div class="flex items-center justify-between text-xs text-gray-400 mt-1">
                <span><?php echo $buku['published_date'] ? date('Y', strtotime($buku['published_date'])) : '-'; ?></span>
                <?php if (!empty($buku['pages'])): ?>
                  <span><?php echo (int) $buku['pages']; ?> hlm.</span>
                <?php endif; ?>
              </div>

              <p class="text-sm font-bold text-primary-700 mt-2"><?php echo $hargaText; ?></p>

              <!-- spacer: dorong tombol ke bawah supaya semua card sejajar -->
              <div class="mt-auto pt-3 space-y-2">
                <a href="detail.php?id=<?php echo $buku['id']; ?>" class="block text-center text-xs font-semibold text-primary-700 border border-primary-200 rounded-lg py-2 hover:bg-primary-50 transition-colors">
                  Detail Buku
                </a>
                <a href="<?php echo htmlspecialchars($linkWA); ?>" target="_blank" rel="noopener"
                   class="flex items-center justify-center gap-1.5 text-xs font-semibold text-white bg-green-600 rounded-lg py-2 hover:bg-green-700 transition-colors">
                  <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.04 2.25c-5.46 0-9.88 4.42-9.88 9.88 0 1.74.46 3.44 1.33 4.94L2 21.75l4.8-1.46a9.83 9.83 0 004.24.98h.01c5.46 0 9.88-4.42 9.88-9.88 0-5.46-4.42-9.88-9.89-9.88Zm5.7 14.02c-.24.68-1.4 1.3-1.93 1.34-.5.05-.99.24-3.32-.7-2.8-1.14-4.6-3.98-4.75-4.16-.14-.19-1.14-1.52-1.14-2.9 0-1.37.72-2.05.98-2.33.26-.28.56-.35.75-.35h.53c.17 0 .4-.06.62.48.24.58.8 2 .87 2.14.07.14.12.31.02.5-.1.19-.15.31-.3.48-.15.17-.31.38-.44.51-.15.15-.3.3-.13.6.17.3.77 1.27 1.65 2.06 1.14 1.02 2.09 1.34 2.39 1.49.3.15.47.13.65-.08.17-.2.74-.86.94-1.16.2-.3.4-.25.66-.15.27.1 1.72.81 2.02.96.3.15.5.22.57.35.07.13.07.75-.17 1.43Z"/></svg>
                  Pesan via WhatsApp
                </a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <?php if ($totalBuku === 0): ?>
        <div class="text-center py-16 text-gray-400 text-sm">
          Coba kata kunci lain atau reset filter di sidebar.
        </div>
      <?php endif; ?>

      <!-- Pagination (sekarang mengikuti jumlah halaman sebenarnya) -->
      <?php if ($totalPages > 1): ?>
        <div class="flex items-center justify-center gap-2 mt-12">
          <a href="<?php echo htmlspecialchars(bukuQueryUrl(['page' => max(1, $page - 1)])); ?>"
             class="w-9 h-9 flex items-center justify-center rounded-lg border border-gray-200 <?php echo $page <= 1 ? 'text-gray-300 pointer-events-none' : 'text-gray-500 hover:bg-gray-50'; ?>">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
          </a>
          <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="<?php echo htmlspecialchars(bukuQueryUrl(['page' => $i])); ?>"
               class="w-9 h-9 flex items-center justify-center rounded-lg text-sm font-semibold <?php echo $i === $page ? 'bg-primary-600 text-white' : 'border border-gray-200 text-gray-600 hover:bg-gray-50'; ?>">
              <?php echo $i; ?>
            </a>
          <?php endfor; ?>
          <a href="<?php echo htmlspecialchars(bukuQueryUrl(['page' => min($totalPages, $page + 1)])); ?>"
             class="w-9 h-9 flex items-center justify-center rounded-lg border border-gray-200 <?php echo $page >= $totalPages ? 'text-gray-300 pointer-events-none' : 'text-gray-500 hover:bg-gray-50'; ?>">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
          </a>
        </div>
      <?php endif; ?>
    </div>
  </section>
  </form>

<?php include '../includes/footer.php'; ?>