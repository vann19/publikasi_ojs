<?php
$activePage = 'buku';
include '../includes/header.php';
require_once '../includes/db.php';

$pdo = getDB();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header('Location: /buku/');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM books WHERE id = :id");
$stmt->execute(['id' => $id]);
$buku = $stmt->fetch();

if (!$buku) {
    header('Location: /buku/');
    exit;
}

$whatsappNomor = '6281916200962';
$hargaAngka    = $buku['harga'];
$hargaText     = $hargaAngka ? 'Rp' . number_format($hargaAngka, 0, ',', '.') : 'Hubungi kami';
$pesanWA       = "Halo, saya ingin memesan buku \"{$buku['title']}\" ({$hargaText}).";
$linkWA        = 'https://wa.me/' . $whatsappNomor . '?text=' . urlencode($pesanWA);

// Buku lain dari kategori yang sama (rekomendasi)
$rekomendasi = [];
if (!empty($buku['category'])) {
    $stmtRek = $pdo->prepare("SELECT * FROM books WHERE category = :cat AND id != :id ORDER BY created_at DESC LIMIT 4");
    $stmtRek->execute(['cat' => $buku['category'], 'id' => $id]);
    $rekomendasi = $stmtRek->fetchAll();
}
?>

  <!-- Breadcrumb -->
  <section class="bg-primary-50 border-b border-primary-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
      <p class="text-sm text-gray-500">
        <a href="/" class="hover:text-primary-700 transition-colors">Beranda</a>
        <span class="mx-2 text-gray-300">/</span>
        <a href="/buku/" class="hover:text-primary-700 transition-colors">Katalog Buku</a>
        <span class="mx-2 text-gray-300">/</span>
        <span class="text-primary-700 font-medium line-clamp-1"><?php echo htmlspecialchars($buku['title']); ?></span>
      </p>
    </div>
  </section>

  <!-- Detail Buku -->
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
    <div class="grid lg:grid-cols-3 gap-10">

      <!-- Kolom Kiri: Cover + Aksi -->
      <div class="lg:col-span-1">
        <div class="sticky top-24 space-y-5">

          <!-- Cover -->
          <div class="rounded-2xl overflow-hidden border border-gray-100 bg-gray-50 aspect-[3/4] flex items-center justify-center shadow-sm">
            <?php if ($buku['image']): ?>
              <img src="<?php echo htmlspecialchars($buku['image']); ?>"
                   alt="Cover <?php echo htmlspecialchars($buku['title']); ?>"
                   class="w-full h-full object-cover">
            <?php else: ?>
              <div class="w-full h-full flex flex-col items-center justify-center bg-primary-50 p-8 text-center">
                <svg class="w-16 h-16 text-primary-200 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.966 8.966 0 00-6 2.292m0-14.25v14.25"/>
                </svg>
                <span class="text-primary-400 text-sm font-semibold leading-snug"><?php echo htmlspecialchars($buku['title']); ?></span>
              </div>
            <?php endif; ?>
          </div>

          <!-- Harga + Tombol -->
          <div class="rounded-2xl border border-gray-100 p-5 bg-white space-y-4">
            <div>
              <p class="text-xs text-gray-400 uppercase tracking-wide font-semibold mb-1">Harga</p>
              <p class="text-2xl font-extrabold text-primary-700"><?php echo $hargaText; ?></p>
            </div>

            <a href="<?php echo htmlspecialchars($linkWA); ?>" target="_blank" rel="noopener"
               class="flex items-center justify-center gap-2 w-full px-5 py-3 rounded-xl bg-green-600 text-white font-semibold hover:bg-green-700 transition-colors text-sm">
              <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12.04 2.25c-5.46 0-9.88 4.42-9.88 9.88 0 1.74.46 3.44 1.33 4.94L2 21.75l4.8-1.46a9.83 9.83 0 004.24.98h.01c5.46 0 9.88-4.42 9.88-9.88 0-5.46-4.42-9.88-9.89-9.88Zm5.7 14.02c-.24.68-1.4 1.3-1.93 1.34-.5.05-.99.24-3.32-.7-2.8-1.14-4.6-3.98-4.75-4.16-.14-.19-1.14-1.52-1.14-2.9 0-1.37.72-2.05.98-2.33.26-.28.56-.35.75-.35h.53c.17 0 .4-.06.62.48.24.58.8 2 .87 2.14.07.14.12.31.02.5-.1.19-.15.31-.3.48-.15.17-.31.38-.44.51-.15.15-.3.3-.13.6.17.3.77 1.27 1.65 2.06 1.14 1.02 2.09 1.34 2.39 1.49.3.15.47.13.65-.08.17-.2.74-.86.94-1.16.2-.3.4-.25.66-.15.27.1 1.72.81 2.02.96.3.15.5.22.57.35.07.13.07.75-.17 1.43Z"/>
              </svg>
              Pesan via WhatsApp
            </a>

            <a href="/buku/" class="flex items-center justify-center gap-2 w-full px-5 py-2.5 rounded-xl border border-primary-200 text-primary-700 font-semibold hover:bg-primary-50 transition-colors text-sm">
              &larr; Kembali ke Katalog
            </a>
          </div>

          <!-- Info singkat -->
          <div class="rounded-2xl border border-gray-100 p-5 bg-white space-y-3 text-sm">
            <?php if (!empty($buku['isbn'])): ?>
            <div class="flex items-start gap-3">
              <span class="text-gray-400 w-24 shrink-0">ISBN</span>
              <span class="font-medium text-gray-800"><?php echo htmlspecialchars($buku['isbn']); ?></span>
            </div>
            <?php endif; ?>
            <?php if (!empty($buku['pages'])): ?>
            <div class="flex items-start gap-3">
              <span class="text-gray-400 w-24 shrink-0">Halaman</span>
              <span class="font-medium text-gray-800"><?php echo (int) $buku['pages']; ?> hlm.</span>
            </div>
            <?php endif; ?>
            <?php if (!empty($buku['publisher'])): ?>
            <div class="flex items-start gap-3">
              <span class="text-gray-400 w-24 shrink-0">Penerbit</span>
              <span class="font-medium text-gray-800"><?php echo htmlspecialchars($buku['publisher']); ?></span>
            </div>
            <?php endif; ?>
            <?php if (!empty($buku['published_date'])): ?>
            <div class="flex items-start gap-3">
              <span class="text-gray-400 w-24 shrink-0">Tahun Terbit</span>
              <span class="font-medium text-gray-800"><?php echo date('Y', strtotime($buku['published_date'])); ?></span>
            </div>
            <?php endif; ?>
            <?php if (!empty($buku['language'])): ?>
            <div class="flex items-start gap-3">
              <span class="text-gray-400 w-24 shrink-0">Bahasa</span>
              <span class="font-medium text-gray-800"><?php echo htmlspecialchars($buku['language']); ?></span>
            </div>
            <?php endif; ?>
            <?php if (!empty($buku['country'])): ?>
            <div class="flex items-start gap-3">
              <span class="text-gray-400 w-24 shrink-0">Negara</span>
              <span class="font-medium text-gray-800"><?php echo htmlspecialchars($buku['country']); ?></span>
            </div>
            <?php endif; ?>
          </div>

        </div>
      </div>

      <!-- Kolom Kanan: Info Detail -->
      <div class="lg:col-span-2 space-y-8">

        <!-- Header buku -->
        <div>
          <?php if (!empty($buku['category'])): ?>
            <span class="inline-block text-xs font-semibold text-primary-700 bg-primary-50 rounded-full px-3 py-1 mb-3 uppercase tracking-wide">
              <?php echo htmlspecialchars($buku['category']); ?>
            </span>
          <?php endif; ?>

          <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 leading-tight mb-2">
            <?php echo htmlspecialchars($buku['title']); ?>
          </h1>

          <p class="text-gray-500 text-sm">
            Oleh <span class="font-semibold text-gray-700"><?php echo htmlspecialchars($buku['author']); ?></span>
          </p>
        </div>

        <!-- Deskripsi -->
        <?php if (!empty($buku['description'])): ?>
        <div>
          <h2 class="text-base font-bold text-gray-900 mb-3">Deskripsi Buku</h2>
          <div class="text-sm text-gray-600 leading-relaxed space-y-3 prose prose-sm max-w-none">
            <?php echo nl2br(htmlspecialchars($buku['description'])); ?>
          </div>
        </div>
        <?php else: ?>
        <div class="rounded-xl bg-gray-50 border border-dashed border-gray-200 p-6 text-center text-gray-400 text-sm">
          Deskripsi buku belum tersedia.
        </div>
        <?php endif; ?>

        <!-- Info lengkap (tabel) -->
        <div>
          <h2 class="text-base font-bold text-gray-900 mb-3">Informasi Buku</h2>
          <div class="rounded-xl border border-gray-100 overflow-hidden divide-y divide-gray-100 text-sm">

            <div class="grid grid-cols-2 sm:grid-cols-3">
              <div class="px-5 py-3 bg-gray-50 font-medium text-gray-500">Judul</div>
              <div class="px-5 py-3 text-gray-800 sm:col-span-2"><?php echo htmlspecialchars($buku['title']); ?></div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3">
              <div class="px-5 py-3 bg-gray-50 font-medium text-gray-500">Penulis</div>
              <div class="px-5 py-3 text-gray-800 sm:col-span-2"><?php echo htmlspecialchars($buku['author']); ?></div>
            </div>

            <?php if (!empty($buku['isbn'])): ?>
            <div class="grid grid-cols-2 sm:grid-cols-3">
              <div class="px-5 py-3 bg-gray-50 font-medium text-gray-500">ISBN</div>
              <div class="px-5 py-3 text-gray-800 sm:col-span-2"><?php echo htmlspecialchars($buku['isbn']); ?></div>
            </div>
            <?php endif; ?>

            <?php if (!empty($buku['publisher'])): ?>
            <div class="grid grid-cols-2 sm:grid-cols-3">
              <div class="px-5 py-3 bg-gray-50 font-medium text-gray-500">Penerbit</div>
              <div class="px-5 py-3 text-gray-800 sm:col-span-2"><?php echo htmlspecialchars($buku['publisher']); ?></div>
            </div>
            <?php endif; ?>

            <?php if (!empty($buku['published_date'])): ?>
            <div class="grid grid-cols-2 sm:grid-cols-3">
              <div class="px-5 py-3 bg-gray-50 font-medium text-gray-500">Tahun Terbit</div>
              <div class="px-5 py-3 text-gray-800 sm:col-span-2"><?php echo date('d F Y', strtotime($buku['published_date'])); ?></div>
            </div>
            <?php endif; ?>

            <?php if (!empty($buku['pages'])): ?>
            <div class="grid grid-cols-2 sm:grid-cols-3">
              <div class="px-5 py-3 bg-gray-50 font-medium text-gray-500">Jumlah Halaman</div>
              <div class="px-5 py-3 text-gray-800 sm:col-span-2"><?php echo (int) $buku['pages']; ?> halaman</div>
            </div>
            <?php endif; ?>

            <?php if (!empty($buku['category'])): ?>
            <div class="grid grid-cols-2 sm:grid-cols-3">
              <div class="px-5 py-3 bg-gray-50 font-medium text-gray-500">Kategori</div>
              <div class="px-5 py-3 text-gray-800 sm:col-span-2"><?php echo htmlspecialchars($buku['category']); ?></div>
            </div>
            <?php endif; ?>

            <?php if (!empty($buku['language'])): ?>
            <div class="grid grid-cols-2 sm:grid-cols-3">
              <div class="px-5 py-3 bg-gray-50 font-medium text-gray-500">Bahasa</div>
              <div class="px-5 py-3 text-gray-800 sm:col-span-2"><?php echo htmlspecialchars($buku['language']); ?></div>
            </div>
            <?php endif; ?>

            <div class="grid grid-cols-2 sm:grid-cols-3">
              <div class="px-5 py-3 bg-gray-50 font-medium text-gray-500">Harga</div>
              <div class="px-5 py-3 font-bold text-primary-700 sm:col-span-2"><?php echo $hargaText; ?></div>
            </div>

          </div>
        </div>

        <!-- CTA bawah (mobile friendly) -->
        <div class="flex flex-col sm:flex-row gap-3 lg:hidden">
          <a href="<?php echo htmlspecialchars($linkWA); ?>" target="_blank" rel="noopener"
             class="flex-1 flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-green-600 text-white font-semibold hover:bg-green-700 transition-colors text-sm">
            <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12.04 2.25c-5.46 0-9.88 4.42-9.88 9.88 0 1.74.46 3.44 1.33 4.94L2 21.75l4.8-1.46a9.83 9.83 0 004.24.98h.01c5.46 0 9.88-4.42 9.88-9.88 0-5.46-4.42-9.88-9.89-9.88Zm5.7 14.02c-.24.68-1.4 1.3-1.93 1.34-.5.05-.99.24-3.32-.7-2.8-1.14-4.6-3.98-4.75-4.16-.14-.19-1.14-1.52-1.14-2.9 0-1.37.72-2.05.98-2.33.26-.28.56-.35.75-.35h.53c.17 0 .4-.06.62.48.24.58.8 2 .87 2.14.07.14.12.31.02.5-.1.19-.15.31-.3.48-.15.17-.31.38-.44.51-.15.15-.3.3-.13.6.17.3.77 1.27 1.65 2.06 1.14 1.02 2.09 1.34 2.39 1.49.3.15.47.13.65-.08.17-.2.74-.86.94-1.16.2-.3.4-.25.66-.15.27.1 1.72.81 2.02.96.3.15.5.22.57.35.07.13.07.75-.17 1.43Z"/>
            </svg>
            Pesan via WhatsApp
          </a>
          <a href="/buku/" class="flex-1 flex items-center justify-center px-5 py-3 rounded-xl border border-primary-200 text-primary-700 font-semibold hover:bg-primary-50 transition-colors text-sm">
            &larr; Kembali
          </a>
        </div>

      </div>
    </div>
  </section>

  <!-- Rekomendasi Buku -->
  <?php if (!empty($rekomendasi)): ?>
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
    <div class="border-t border-gray-100 pt-12">
      <h2 class="text-xl font-bold text-gray-900 mb-6">Buku Lain dalam Kategori &ldquo;<?php echo htmlspecialchars($buku['category']); ?>&rdquo;</h2>
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-5 items-stretch">
        <?php foreach ($rekomendasi as $rek): ?>
          <?php
            $hRek    = $rek['harga'] ? 'Rp' . number_format($rek['harga'], 0, ',', '.') : 'Hubungi kami';
            $waRek   = 'https://wa.me/' . $whatsappNomor . '?text=' . urlencode("Halo, saya ingin memesan buku \"{$rek['title']}\" ({$hRek}).");
          ?>
          <div class="h-full flex flex-col rounded-xl border border-gray-100 overflow-hidden hover:shadow-sm transition-shadow bg-white">
            <a href="/buku/detail.php?id=<?php echo $rek['id']; ?>" class="block aspect-[3/4] bg-gray-100 overflow-hidden">
              <?php if ($rek['image']): ?>
                <img src="<?php echo htmlspecialchars($rek['image']); ?>" alt="<?php echo htmlspecialchars($rek['title']); ?>" class="w-full h-full object-cover">
              <?php else: ?>
                <div class="w-full h-full flex items-center justify-center p-4">
                  <span class="text-gray-400 text-xs font-semibold text-center leading-snug"><?php echo htmlspecialchars($rek['title']); ?></span>
                </div>
              <?php endif; ?>
            </a>
            <div class="p-4 flex flex-col flex-1">
              <p class="text-xs font-semibold text-gray-900 leading-snug line-clamp-2 min-h-[2.5rem]"><?php echo htmlspecialchars($rek['title']); ?></p>
              <p class="text-[11px] text-gray-500 mt-1 line-clamp-1"><?php echo htmlspecialchars($rek['author']); ?></p>
              <p class="text-xs font-bold text-primary-700 mt-2"><?php echo $hRek; ?></p>
              <div class="mt-auto pt-3 space-y-2">
                <a href="/buku/detail.php?id=<?php echo $rek['id']; ?>" class="block text-center text-[11px] font-semibold text-primary-700 border border-primary-200 rounded-lg py-1.5 hover:bg-primary-50 transition-colors">
                  Detail Buku
                </a>
                <a href="<?php echo htmlspecialchars($waRek); ?>" target="_blank" rel="noopener"
                   class="flex items-center justify-center gap-1 text-[11px] font-semibold text-white bg-green-600 rounded-lg py-1.5 hover:bg-green-700 transition-colors">
                  <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12.04 2.25c-5.46 0-9.88 4.42-9.88 9.88 0 1.74.46 3.44 1.33 4.94L2 21.75l4.8-1.46a9.83 9.83 0 004.24.98h.01c5.46 0 9.88-4.42 9.88-9.88 0-5.46-4.42-9.88-9.89-9.88Zm5.7 14.02c-.24.68-1.4 1.3-1.93 1.34-.5.05-.99.24-3.32-.7-2.8-1.14-4.6-3.98-4.75-4.16-.14-.19-1.14-1.52-1.14-2.9 0-1.37.72-2.05.98-2.33.26-.28.56-.35.75-.35h.53c.17 0 .4-.06.62.48.24.58.8 2 .87 2.14.07.14.12.31.02.5-.1.19-.15.31-.3.48-.15.17-.31.38-.44.51-.15.15-.3.3-.13.6.17.3.77 1.27 1.65 2.06 1.14 1.02 2.09 1.34 2.39 1.49.3.15.47.13.65-.08.17-.2.74-.86.94-1.16.2-.3.4-.25.66-.15.27.1 1.72.81 2.02.96.3.15.5.22.57.35.07.13.07.75-.17 1.43Z"/></svg>
                  WhatsApp
                </a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

<?php include '../includes/footer.php'; ?>
