<?php
$activePage = 'buku';
include '../includes/header.php';

// Data dummy kategori & buku — nanti diganti query database
$kategoriList = [
  ['nama' => 'Semua Kategori', 'jumlah' => 86, 'aktif' => true],
  ['nama' => 'Pendidikan', 'jumlah' => 28],
  ['nama' => 'Metodologi Penelitian', 'jumlah' => 16],
  ['nama' => 'Manajemen', 'jumlah' => 12],
  ['nama' => 'Teknologi', 'jumlah' => 10],
  ['nama' => 'Sosial & Humaniora', 'jumlah' => 6],
  ['nama' => 'Ekonomi', 'jumlah' => 8],
  ['nama' => 'Lainnya', 'jumlah' => 4],
];

$bukuList = [
  ['judul' => 'Metodologi Penelitian Pendidikan', 'penulis' => 'Dr. Budi Santoso', 'tahun' => 2024, 'harga' => 'Rp100.000', 'warna' => 'bg-emerald-700'],
  ['judul' => 'Manajemen Pendidikan di Era Digital', 'penulis' => 'Prof. Siti Nurjanah', 'tahun' => 2024, 'harga' => 'Rp95.000', 'warna' => 'bg-sky-700'],
  ['judul' => 'Literasi Digital dalam Pembelajaran', 'penulis' => 'Dr. Andi Wijaya', 'tahun' => 2024, 'harga' => 'Rp90.000', 'warna' => 'bg-orange-600'],
  ['judul' => 'Inovasi Pembelajaran untuk Abad 21', 'penulis' => 'Dr. Rina Marlina', 'tahun' => 2024, 'harga' => 'Rp90.000', 'warna' => 'bg-indigo-700'],
  ['judul' => 'Statistika untuk Penelitian', 'penulis' => 'Dr. Ahmad Fauzi', 'tahun' => 2024, 'harga' => 'Rp85.000', 'warna' => 'bg-rose-700'],
  ['judul' => 'Evaluasi Pembelajaran', 'penulis' => 'Dr. Dewi Lestari', 'tahun' => 2024, 'harga' => 'Rp80.000', 'warna' => 'bg-teal-700'],
  ['judul' => 'Pengantar Kecerdasan Buatan', 'penulis' => 'Dr. Fajar Ramadhan', 'tahun' => 2024, 'harga' => 'Rp120.000', 'warna' => 'bg-purple-700'],
  ['judul' => 'Teknologi Informasi dalam Pendidikan', 'penulis' => 'Dr. Yudi Kurniawan', 'tahun' => 2024, 'harga' => 'Rp95.000', 'warna' => 'bg-slate-700'],
];

$totalBuku = 86; // dummy, nanti dari COUNT(*) query
$jumlahDitampilkan = count($bukuList);
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
              <a href="#" class="flex items-center justify-between px-3 py-2 rounded-lg text-sm <?php echo !empty($k['aktif']) ? 'bg-primary-50 text-primary-700 font-semibold' : 'text-gray-600 hover:bg-gray-50'; ?>">
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
            <input type="checkbox" checked class="accent-primary-600 w-4 h-4"> Semua Harga
          </label>
          <label class="flex items-center gap-2">
            <input type="checkbox" class="accent-primary-600 w-4 h-4"> &lt; Rp50.000
          </label>
          <label class="flex items-center gap-2">
            <input type="checkbox" class="accent-primary-600 w-4 h-4"> Rp50.000 &ndash; Rp100.000
          </label>
          <label class="flex items-center gap-2">
            <input type="checkbox" class="accent-primary-600 w-4 h-4"> Rp100.000 &ndash; Rp150.000
          </label>
          <label class="flex items-center gap-2">
            <input type="checkbox" class="accent-primary-600 w-4 h-4"> &gt; Rp150.000
          </label>
        </div>

        <p class="text-sm font-medium text-gray-700 mb-2">Tahun Terbit</p>
        <select class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 text-gray-600 mb-5">
          <option>Pilih Tahun</option>
          <option>2024</option>
          <option>2023</option>
          <option>2022</option>
        </select>

        <button type="button" class="w-full text-sm font-semibold text-primary-700 border border-primary-200 rounded-lg py-2 hover:bg-primary-50 transition-colors">
          Reset Filter
        </button>
      </div>
    </aside>

    <!-- Grid buku -->
    <div class="lg:col-span-3">

      <!-- Search + sort -->
      <div class="flex flex-col sm:flex-row gap-4 mb-6">
        <div class="relative flex-1">
          <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
          <input type="text" placeholder="Cari judul buku, penulis, atau kata kunci..."
                 class="w-full text-sm border border-gray-200 rounded-lg pl-9 pr-4 py-2.5 text-gray-600 focus:outline-none focus:ring-2 focus:ring-primary-200">
        </div>
        <div class="flex items-center gap-2 text-sm text-gray-500">
          <span>Urutkan:</span>
          <select class="border border-gray-200 rounded-lg px-3 py-2 text-gray-700">
            <option>Terbaru</option>
            <option>Termurah</option>
            <option>Termahal</option>
            <option>Judul A-Z</option>
          </select>
        </div>
      </div>

      <p class="text-sm text-gray-500 mb-5">
        Menampilkan 1&ndash;<?php echo $jumlahDitampilkan; ?> dari <?php echo $totalBuku; ?> buku
      </p>

      <!-- Grid -->
      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
        <?php foreach ($bukuList as $buku): ?>
          <div class="group">
            <a href="detail.php">
              <div class="aspect-[3/4] rounded-lg <?php echo $buku['warna']; ?> p-4 flex items-end shadow-sm group-hover:shadow-md transition-shadow">
                <span class="text-white text-xs font-semibold leading-snug"><?php echo htmlspecialchars($buku['judul']); ?></span>
              </div>
            </a>
            <p class="mt-2 text-sm font-medium text-gray-900 line-clamp-2"><?php echo htmlspecialchars($buku['judul']); ?></p>
            <p class="text-xs text-gray-500"><?php echo htmlspecialchars($buku['penulis']); ?></p>
            <p class="text-xs text-gray-400"><?php echo $buku['tahun']; ?></p>
            <p class="text-sm font-semibold text-primary-700 mt-0.5"><?php echo htmlspecialchars($buku['harga']); ?></p>
            <a href="detail.php" class="inline-flex items-center gap-1 mt-2 text-sm font-medium text-primary-600 hover:text-primary-700">
              Lihat Detail &rarr;
            </a>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- Pagination -->
      <div class="flex items-center justify-center gap-2 mt-12">
        <a href="#" class="w-9 h-9 flex items-center justify-center rounded-lg border border-gray-200 text-gray-400 hover:bg-gray-50">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <a href="#" class="w-9 h-9 flex items-center justify-center rounded-lg bg-primary-600 text-white text-sm font-semibold">1</a>
        <a href="#" class="w-9 h-9 flex items-center justify-center rounded-lg border border-gray-200 text-sm text-gray-600 hover:bg-gray-50">2</a>
        <a href="#" class="w-9 h-9 flex items-center justify-center rounded-lg border border-gray-200 text-sm text-gray-600 hover:bg-gray-50">3</a>
        <a href="#" class="w-9 h-9 flex items-center justify-center rounded-lg border border-gray-200 text-sm text-gray-600 hover:bg-gray-50">4</a>
        <a href="#" class="w-9 h-9 flex items-center justify-center rounded-lg border border-gray-200 text-sm text-gray-600 hover:bg-gray-50">5</a>
        <a href="#" class="w-9 h-9 flex items-center justify-center rounded-lg border border-gray-200 text-gray-400 hover:bg-gray-50">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        </a>
      </div>
    </div>
  </section>

<?php include '../includes/footer.php'; ?>