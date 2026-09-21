<?php
$activePage = 'beranda';
include 'includes/header.php';
require_once 'includes/db.php';
$pdo = getDB();
$latestBooks = $pdo->query("SELECT * FROM books ORDER BY created_at DESC LIMIT 6")->fetchAll();
// Ambil data komentar dengan pagination (6 per halaman)
$limit = 6;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;
$totalComments = $pdo->query("SELECT COUNT(*) FROM comments WHERE is_active = 1")->fetchColumn();
$totalPages = ceil($totalComments / $limit);

$stmt = $pdo->prepare("SELECT * FROM comments WHERE is_active = 1 ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$activeComments = $stmt->fetchAll();

// Ambil data jurnal dari database untuk katalog di beranda
$jurnalTerbaru = $pdo->query("SELECT title AS judul, description AS deskripsi, link AS url, image AS cover, issn, sinta, warna FROM journals WHERE is_active = 1 ORDER BY id ASC LIMIT 6")->fetchAll();
?>

  <!-- Hero -->
  <section class="relative overflow-hidden">
    <!-- Gambar latar -->
    <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('assets/img/background.png');"></div>
    <!-- Gradien overlay biar teks tetap kebaca -->
    <div class="absolute inset-0 bg-gradient-to-r from-[#1E1B3A]/95 via-[#1E1B3A]/80 to-[#1E1B3A]/30"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-28 lg:py-36">
      <div class="max-w-xl">
        <h1 class="text-4xl sm:text-5xl font-extrabold text-white leading-tight">
          Penerbitan Berkualitas, Ilmu Berdaya, Masyarakat Bermakna.
        </h1>
        <p class="mt-5 text-gray-200 leading-relaxed max-w-md">
          PT Nawa Edukasi Nusantara hadir sebagai mitra terpercaya dalam penerbitan buku,
          layanan Hak Kekayaan Intelektual, pengelolaan jurnal ilmiah, seminar, dan layanan akademik lainnya.
        </p>
        <div class="mt-8 flex flex-wrap gap-4">
          <a href="/buku/" class="inline-flex items-center px-6 py-3 rounded-lg bg-primary-600 text-white font-semibold hover:bg-primary-700 transition-colors">
            Jelajahi Buku
          </a>
          <a href="/layanan.php" class="inline-flex items-center px-6 py-3 rounded-lg border border-white/60 text-white font-semibold hover:bg-white/10 transition-colors">
            Lihat Layanan
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- Layanan Utama -->
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">

      <div class="p-6 rounded-xl border border-gray-100 hover:border-primary-200 transition-colors">
        <div class="w-10 h-10 rounded-lg bg-primary-50 flex items-center justify-center mb-4">
          <svg class="w-5 h-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.04A8.97 8.97 0 006 3.75c-1.05 0-2.06.18-3 .5v14.25A8.99 8.99 0 016 18c2.3 0 4.4.87 6 2.29m0-14.25a8.97 8.97 0 016-2.29c1.05 0 2.06.18 3 .5v14.25A8.99 8.99 0 0018 18a8.97 8.97 0 00-6 2.29m0-14.25v14.25" /></svg>
        </div>
        <h3 class="font-semibold text-gray-900 mb-1">Penerbitan Buku</h3>
        <p class="text-sm text-gray-500 leading-relaxed">Menerbitkan buku berkualitas dengan proses profesional dari naskah hingga cetak.</p>
        <a href="/layanan.php" class="inline-block mt-3 text-sm font-medium text-primary-600 hover:text-primary-700">Selengkapnya &rarr;</a>
      </div>

      <div class="p-6 rounded-xl border border-gray-100 hover:border-primary-200 transition-colors">
        <div class="w-10 h-10 rounded-lg bg-primary-50 flex items-center justify-center mb-4">
          <svg class="w-5 h-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.5L17 7.5V19a2 2 0 01-2 2z" /></svg>
        </div>
        <h3 class="font-semibold text-gray-900 mb-1">Jurnal Ilmiah (OJS)</h3>
        <p class="text-sm text-gray-500 leading-relaxed">Mengelola jurnal ilmiah melalui sistem Open Journal Systems yang kredibel.</p>
        <a href="/jurnal.php" class="inline-block mt-3 text-sm font-medium text-primary-600 hover:text-primary-700">Selengkapnya &rarr;</a>
      </div>

      <div class="p-6 rounded-xl border border-gray-100 hover:border-primary-200 transition-colors">
        <div class="w-10 h-10 rounded-lg bg-primary-50 flex items-center justify-center mb-4">
          <svg class="w-5 h-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.96 11.96 0 013.598 6 11.96 11.96 0 001.5 12c0 5.83 3.4 10.86 8.318 13.246a11.96 11.96 0 004.365 0C19.1 22.86 22.5 17.83 22.5 12a11.96 11.96 0 00-2.098-6 11.96 11.96 0 01-8.402-3.286z" /></svg>
        </div>
        <h3 class="font-semibold text-gray-900 mb-1">Layanan HKI</h3>
        <p class="text-sm text-gray-500 leading-relaxed">Pendampingan pendaftaran Hak Kekayaan Intelektual untuk karya dan penelitian Anda.</p>
        <a href="/layanan.php" class="inline-block mt-3 text-sm font-medium text-primary-600 hover:text-primary-700">Selengkapnya &rarr;</a>
      </div>

      <div class="p-6 rounded-xl border border-gray-100 hover:border-primary-200 transition-colors">
        <div class="w-10 h-10 rounded-lg bg-primary-50 flex items-center justify-center mb-4">
          <svg class="w-5 h-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.09 9.09 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.94 11.94 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" /></svg>
        </div>
        <h3 class="font-semibold text-gray-900 mb-1">Seminar</h3>
        <p class="text-sm text-gray-500 leading-relaxed">Menyelenggarakan seminar nasional dan internasional bagi akademisi dan peneliti.</p>
        <a href="/seminar.php" class="inline-block mt-3 text-sm font-medium text-primary-600 hover:text-primary-700">Selengkapnya &rarr;</a>
      </div>

    </div>
  </section>

  <!-- Katalog Buku Terbaru -->
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
    <div class="flex items-end justify-between mb-6">
      <h2 class="text-2xl font-bold text-gray-900">Katalog Buku Terbaru</h2>
      <a href="/buku/" class="text-sm font-medium text-primary-600 hover:text-primary-700">Lihat Semua Buku &rarr;</a>
    </div>

    <?php
      $whatsappNomor = '6281916200962';
    ?>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-5 items-stretch">
      <?php if (count($latestBooks) > 0): ?>
        <?php foreach ($latestBooks as $buku): ?>
          <?php
            $hargaAngka = $buku['harga'];
            $hargaText  = $hargaAngka ? 'Rp' . number_format($hargaAngka, 0, ',', '.') : 'Hubungi kami';
            $pesanWA    = "Halo, saya ingin memesan buku \"{$buku['title']}\" ({$hargaText}).";
            $linkWA     = 'https://wa.me/' . $whatsappNomor . '?text=' . urlencode($pesanWA);
          ?>
          <div class="h-full flex flex-col rounded-xl border border-gray-100 overflow-hidden shadow-md hover:shadow-lg transition-shadow bg-white">

            <!-- Cover -->
            <a href="/buku/detail.php?id=<?php echo $buku['id']; ?>" class="block aspect-[3/4] bg-gray-100 overflow-hidden">
              <?php if ($buku['image']): ?>
                <img src="<?php echo htmlspecialchars($buku['image']); ?>" alt="<?php echo htmlspecialchars($buku['title']); ?>" class="w-full h-full object-cover">
              <?php else: ?>
                <div class="w-full h-full flex items-center justify-center p-4">
                  <span class="text-gray-400 text-xs font-semibold text-center leading-snug"><?php echo htmlspecialchars($buku['title']); ?></span>
                </div>
              <?php endif; ?>
            </a>

            <!-- Konten -->
            <div class="p-3 flex flex-col flex-1">
              <?php if (!empty($buku['category'])): ?>
                <p class="text-[11px] font-semibold text-primary-600 uppercase tracking-wide mb-1 truncate"><?php echo htmlspecialchars($buku['category']); ?></p>
              <?php endif; ?>

              <p class="text-xs font-semibold text-gray-900 leading-snug line-clamp-2 min-h-[2.5rem]"><?php echo htmlspecialchars($buku['title']); ?></p>

              <p class="text-[11px] text-gray-500 mt-1 line-clamp-1"><?php echo htmlspecialchars($buku['author']); ?></p>

              <div class="flex items-center justify-between text-[11px] text-gray-400 mt-1">
                <span><?php echo $buku['published_date'] ? date('Y', strtotime($buku['published_date'])) : '-'; ?></span>
                <?php if (!empty($buku['pages'])): ?>
                  <span><?php echo (int) $buku['pages']; ?> hlm.</span>
                <?php endif; ?>
              </div>

              <p class="text-xs font-bold text-primary-700 mt-2"><?php echo $hargaText; ?></p>

              <!-- Tombol selalu di bawah -->
              <div class="mt-auto pt-3 space-y-2">
                <a href="/buku/detail.php?id=<?php echo $buku['id']; ?>" class="block text-center text-[11px] font-semibold text-primary-700 border border-primary-200 rounded-lg py-1.5 hover:bg-primary-50 transition-colors">
                  Detail Buku
                </a>
                <a href="<?php echo htmlspecialchars($linkWA); ?>" target="_blank" rel="noopener"
                   class="flex items-center justify-center gap-1 text-[11px] font-semibold text-white bg-green-600 rounded-lg py-1.5 hover:bg-green-700 transition-colors">
                  <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12.04 2.25c-5.46 0-9.88 4.42-9.88 9.88 0 1.74.46 3.44 1.33 4.94L2 21.75l4.8-1.46a9.83 9.83 0 004.24.98h.01c5.46 0 9.88-4.42 9.88-9.88 0-5.46-4.42-9.88-9.89-9.88Zm5.7 14.02c-.24.68-1.4 1.3-1.93 1.34-.5.05-.99.24-3.32-.7-2.8-1.14-4.6-3.98-4.75-4.16-.14-.19-1.14-1.52-1.14-2.9 0-1.37.72-2.05.98-2.33.26-.28.56-.35.75-.35h.53c.17 0 .4-.06.62.48.24.58.8 2 .87 2.14.07.14.12.31.02.5-.1.19-.15.31-.3.48-.15.17-.31.38-.44.51-.15.15-.3.3-.13.6.17.3.77 1.27 1.65 2.06 1.14 1.02 2.09 1.34 2.39 1.49.3.15.47.13.65-.08.17-.2.74-.86.94-1.16.2-.3.4-.25.66-.15.27.1 1.72.81 2.02.96.3.15.5.22.57.35.07.13.07.75-.17 1.43Z"/></svg>
                  WhatsApp
                </a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="text-sm text-gray-500 col-span-full">Belum ada buku yang diterbitkan.</p>
      <?php endif; ?>
    </div>
  </section>

  <!-- Katalog Jurnal Terbaru (data dummy, nanti diganti query dari DB / API OJS) -->
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
    <div class="flex items-end justify-between mb-6">
      <h2 class="text-2xl font-bold text-gray-900">Katalog Jurnal Terbaru</h2>
      <a href="/jurnal.php" class="text-sm font-medium text-primary-600 hover:text-primary-700">Lihat Semua Jurnal &rarr;</a>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-5">
      <?php foreach ($jurnalTerbaru as $j): ?>
        <div class="jurnal-card rounded-xl border border-gray-100 overflow-hidden shadow-md hover:shadow-lg transition-all duration-200 flex flex-col">
          <?php if (!empty($j['cover'])): ?>
            <div class="aspect-[3/4] w-full bg-gray-100 overflow-hidden">
              <img src="<?php echo htmlspecialchars($j['cover']); ?>" alt="Cover <?php echo htmlspecialchars($j['judul']); ?>" class="w-full h-full object-cover">
            </div>
          <?php else: ?>
            <div class="aspect-[3/4] <?php echo $j['warna']; ?> p-3 flex flex-col justify-center items-center text-center">
              <span class="text-white text-[11px] font-bold uppercase tracking-wide leading-snug"><?php echo htmlspecialchars($j['judul']); ?></span>
            </div>
          <?php endif; ?>
          <div class="p-4 flex flex-col flex-1">
            <p class="font-semibold text-gray-900 text-sm mb-1 leading-snug line-clamp-2"><?php echo htmlspecialchars($j['judul']); ?></p>
            <p class="text-xs text-gray-400 mb-3">e-ISSN: <?php echo $j['issn']; ?></p>
            <a href="<?php echo htmlspecialchars($j['url']); ?>" target="_blank" class="mt-auto text-center text-sm font-semibold text-primary-700 border border-primary-200 rounded-lg py-2 hover:bg-primary-50 transition-colors">
              Lihat Jurnal
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- OJS + CTA Terbitkan Buku -->
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20 grid lg:grid-cols-2 gap-6">
    <div class="rounded-2xl bg-primary-50 p-8 flex flex-col justify-between">
      <div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Akses Jurnal Ilmiah Kami di OJS</h3>
        <p class="text-sm text-gray-600 leading-relaxed max-w-sm">Temukan berbagai jurnal ilmiah terakreditasi kami yang dikelola dengan sistem Open Journal Systems.</p>
      </div>
      <a href="/jurnal.php" class="inline-flex items-center mt-6 px-5 py-2.5 rounded-lg bg-primary-600 text-white text-sm font-semibold hover:bg-primary-700 transition-colors w-fit">
        Kunjungi OJS
      </a>
    </div>

    <div class="rounded-2xl bg-[#1E1B3A] p-8 flex flex-col justify-between text-white">
      <div>
        <h3 class="text-xl font-bold mb-2">Ingin Menerbitkan Buku?</h3>
        <p class="text-sm text-gray-300 leading-relaxed max-w-sm">Kami siap membantu Anda menerbitkan buku dengan proses mudah, cepat, dan profesional.</p>
      </div>
      <a href="https://wa.me/6281916200962" target="_blank" rel="noopener"
         class="inline-flex items-center mt-6 px-5 py-2.5 rounded-lg bg-white text-[#1E1B3A] text-sm font-semibold hover:bg-gray-100 transition-colors w-fit">
        Konsultasi via WhatsApp
      </a>
    </div>
  </section>

  <!-- Statistik (dummy) -->
  <section class="border-y border-gray-100 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 grid grid-cols-2 lg:grid-cols-4 gap-8 text-center">
      <div>
        <p class="text-2xl font-bold text-primary-700">100+</p>
        <p class="text-sm text-gray-500 mt-1">Buku Terbit</p>
      </div>
      <div>
        <p class="text-2xl font-bold text-primary-700">50+</p>
        <p class="text-sm text-gray-500 mt-1">Jurnal Terbit</p>
      </div>
      <div>
        <p class="text-2xl font-bold text-primary-700">200+</p>
        <p class="text-sm text-gray-500 mt-1">Naskah HKI Terdaftar</p>
      </div>
      <div>
        <p class="text-2xl font-bold text-primary-700">10+</p>
        <p class="text-sm text-gray-500 mt-1">Tahun Pengalaman</p>
      </div>
    </div>
  </section>

  <!-- Testimoni / Komentar -->
  <section id="komentar" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <div class="grid lg:grid-cols-3 gap-12">
      <!-- Daftar Komentar -->
      <div class="lg:col-span-2">
        <h2 class="text-2xl font-bold text-gray-900 mb-8">Komentar & Testimoni</h2>
        <div class="grid sm:grid-cols-2 gap-6">
          <?php if(empty($activeComments)): ?>
            <p class="text-gray-500 text-sm">Belum ada komentar yang ditampilkan.</p>
          <?php else: ?>
            <?php foreach ($activeComments as $c): ?>
              <div class="p-6 rounded-xl border border-gray-100 bg-white shadow-sm">
                <p class="text-sm text-gray-600 leading-relaxed mb-4">&ldquo;<?php echo nl2br(htmlspecialchars($c['komentar'])); ?>&rdquo;</p>
                <div class="flex items-center gap-3">
                  <div class="w-9 h-9 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 text-sm font-semibold">
                    <?php echo strtoupper(substr($c['nama'], 0, 1)); ?>
                  </div>
                  <div>
                    <p class="text-sm font-semibold text-gray-900"><?php echo htmlspecialchars($c['nama']); ?></p>
                    <p class="text-xs text-gray-500"><?php echo htmlspecialchars($c['instansi'] ?: '-'); ?></p>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
        
        <!-- Pagination Komentar -->
        <?php if ($totalPages > 1): ?>
        <div class="flex items-center justify-center gap-2 mt-8">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>#komentar" class="w-9 h-9 flex items-center justify-center rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50">
                    &laquo;
                </a>
            <?php endif; ?>
            
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?php echo $i; ?>#komentar" class="w-9 h-9 flex items-center justify-center rounded-lg <?php echo $i === $page ? 'bg-primary-600 text-white font-semibold' : 'border border-gray-200 text-gray-600 hover:bg-gray-50'; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
            
            <?php if ($page < $totalPages): ?>
                <a href="?page=<?php echo $page + 1; ?>#komentar" class="w-9 h-9 flex items-center justify-center rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50">
                    &raquo;
                </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
      </div>

      <!-- Form Tambah Komentar -->
      <div>
        <h3 class="text-xl font-bold text-gray-900 mb-6">Tinggalkan Komentar</h3>
        <form id="comment-form" class="space-y-4" onsubmit="submitComment(event)">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap *</label>
            <input type="text" name="nama" required class="w-full text-sm border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-200">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Instansi / Asal</label>
            <input type="text" name="instansi" class="w-full text-sm border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-200">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Komentar *</label>
            <textarea name="komentar" required rows="4" class="w-full text-sm border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-200"></textarea>
          </div>
          <button type="submit" class="w-full bg-primary-600 text-white font-semibold rounded-lg px-4 py-2.5 hover:bg-primary-700 transition-colors">
            Kirim Komentar
          </button>
          <p id="comment-msg" class="text-sm mt-2 hidden"></p>
        </form>
      </div>
    </div>
  </section>

  <script>
  function submitComment(e) {
      e.preventDefault();
      const form = e.target;
      const msg = document.getElementById('comment-msg');
      const formData = new FormData(form);

      fetch('/api/post-comment.php', {
          method: 'POST',
          body: formData
      })
      .then(res => res.json())
      .then(data => {
          msg.classList.remove('hidden', 'text-red-600', 'text-green-600');
          if (data.status === 'success') {
              msg.classList.add('text-green-600');
              msg.textContent = data.message;
              form.reset();
          } else {
              msg.classList.add('text-red-600');
              msg.textContent = data.message;
          }
      })
      .catch(err => {
          msg.classList.remove('hidden', 'text-green-600');
          msg.classList.add('text-red-600');
          msg.textContent = 'Terjadi kesalahan sistem.';
      });
  }
  </script>

<?php include 'includes/footer.php'; ?>