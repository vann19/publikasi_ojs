<?php
$activePage = 'seminar';
include '../includes/header.php';
require_once '../includes/db.php';

$pdo = getDB();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header('Location: /seminar.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM seminars WHERE id = :id AND is_active = 1");
$stmt->execute(['id' => $id]);
$seminar = $stmt->fetch();

if (!$seminar) {
    header('Location: /seminar.php');
    exit;
}

$whatsappNomor = '6281916200962';
$hargaText     = $seminar['price'] ? 'Rp' . number_format($seminar['price'], 0, ',', '.') : 'Gratis';
$isUpcoming    = $seminar['date'] && strtotime($seminar['date']) >= strtotime('today');
$statusLabel   = $isUpcoming ? 'Akan Datang' : 'Selesai';
$tanggalFormatted = $seminar['date'] ? date('d F Y', strtotime($seminar['date'])) : null;

// Pesan WA yang berbeda untuk seminar yang masih akan datang vs sudah selesai
if ($isUpcoming) {
    $pesanWA = "Halo, saya ingin mendaftar seminar \"{$seminar['title']}\" ({$hargaText}). Bisa minta info lebih lanjut?";
} else {
    $pesanWA = "Halo, saya ingin bertanya tentang seminar \"{$seminar['title']}\". Apakah ada jadwal selanjutnya?";
}
$linkWA = 'https://wa.me/' . $whatsappNomor . '?text=' . urlencode($pesanWA);

// Seminar lain yang masih akan datang sebagai rekomendasi
$stmtRek = $pdo->prepare("SELECT * FROM seminars WHERE id != :id AND is_active = 1 ORDER BY date ASC LIMIT 3");
$stmtRek->execute(['id' => $id]);
$rekomendasi = $stmtRek->fetchAll();
?>

  <!-- Breadcrumb -->
  <section class="bg-primary-50 border-b border-primary-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
      <p class="text-sm text-gray-500">
        <a href="/" class="hover:text-primary-700 transition-colors">Beranda</a>
        <span class="mx-2 text-gray-300">/</span>
        <a href="/seminar.php" class="hover:text-primary-700 transition-colors">Seminar</a>
        <span class="mx-2 text-gray-300">/</span>
        <span class="text-primary-700 font-medium line-clamp-1"><?php echo htmlspecialchars($seminar['title']); ?></span>
      </p>
    </div>
  </section>

  <!-- Detail Seminar -->
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
    <div class="grid lg:grid-cols-3 gap-10">

      <!-- Kolom Kiri: Poster + Aksi -->
      <div class="lg:col-span-1">
        <div class="sticky top-24 space-y-5">

          <!-- Poster Seminar -->
          <div class="rounded-2xl overflow-hidden border border-gray-100 bg-[#1E1B3A] aspect-[4/3] flex items-center justify-center shadow-sm relative">
            <?php if ($seminar['image']): ?>
              <img src="<?php echo htmlspecialchars($seminar['image']); ?>"
                   alt="Poster <?php echo htmlspecialchars($seminar['title']); ?>"
                   class="w-full h-full object-cover">
            <?php else: ?>
              <div class="w-full h-full flex flex-col items-center justify-center p-8 text-center">
                <svg class="w-16 h-16 text-white/20 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.09 9.09 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.94 11.94 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>
                </svg>
                <span class="text-white/50 text-sm font-medium leading-snug"><?php echo htmlspecialchars($seminar['title']); ?></span>
              </div>
            <?php endif; ?>
            <!-- Badge status -->
            <span class="absolute top-3 right-3 text-xs font-semibold px-2.5 py-1 rounded-full <?php echo $isUpcoming ? 'bg-emerald-500 text-white' : 'bg-white/80 text-gray-600'; ?>">
              <?php echo $statusLabel; ?>
            </span>
          </div>

          <!-- Harga + Tombol Aksi -->
          <div class="rounded-2xl border border-gray-100 p-5 bg-white space-y-4">
            <div>
              <p class="text-xs text-gray-400 uppercase tracking-wide font-semibold mb-1">Biaya Pendaftaran</p>
              <p class="text-2xl font-extrabold <?php echo $seminar['price'] ? 'text-primary-700' : 'text-emerald-600'; ?>">
                <?php echo $hargaText; ?>
              </p>
            </div>

            <?php if ($seminar['link'] && $isUpcoming): ?>
              <a href="<?php echo htmlspecialchars($seminar['link']); ?>" target="_blank" rel="noopener"
                 class="flex items-center justify-center gap-2 w-full px-5 py-3 rounded-xl bg-primary-600 text-white font-semibold hover:bg-primary-700 transition-colors text-sm">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
                </svg>
                Daftar Sekarang
              </a>
            <?php endif; ?>

            <a href="<?php echo htmlspecialchars($linkWA); ?>" target="_blank" rel="noopener"
               class="flex items-center justify-center gap-2 w-full px-5 py-3 rounded-xl bg-green-600 text-white font-semibold hover:bg-green-700 transition-colors text-sm">
              <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12.04 2.25c-5.46 0-9.88 4.42-9.88 9.88 0 1.74.46 3.44 1.33 4.94L2 21.75l4.8-1.46a9.83 9.83 0 004.24.98h.01c5.46 0 9.88-4.42 9.88-9.88 0-5.46-4.42-9.88-9.89-9.88Zm5.7 14.02c-.24.68-1.4 1.3-1.93 1.34-.5.05-.99.24-3.32-.7-2.8-1.14-4.6-3.98-4.75-4.16-.14-.19-1.14-1.52-1.14-2.9 0-1.37.72-2.05.98-2.33.26-.28.56-.35.75-.35h.53c.17 0 .4-.06.62.48.24.58.8 2 .87 2.14.07.14.12.31.02.5-.1.19-.15.31-.3.48-.15.17-.31.38-.44.51-.15.15-.3.3-.13.6.17.3.77 1.27 1.65 2.06 1.14 1.02 2.09 1.34 2.39 1.49.3.15.47.13.65-.08.17-.2.74-.86.94-1.16.2-.3.4-.25.66-.15.27.1 1.72.81 2.02.96.3.15.5.22.57.35.07.13.07.75-.17 1.43Z"/>
              </svg>
              Hubungi via WhatsApp
            </a>

            <a href="/seminar.php"
               class="flex items-center justify-center gap-2 w-full px-5 py-2.5 rounded-xl border border-primary-200 text-primary-700 font-semibold hover:bg-primary-50 transition-colors text-sm">
              &larr; Kembali ke Seminar
            </a>
          </div>

          <!-- Info Singkat -->
          <div class="rounded-2xl border border-gray-100 p-5 bg-white space-y-3 text-sm">
            <?php if ($tanggalFormatted): ?>
            <div class="flex items-start gap-3">
              <svg class="w-4 h-4 text-gray-400 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
              </svg>
              <div>
                <p class="text-gray-400 text-xs mb-0.5">Tanggal</p>
                <p class="font-medium text-gray-800"><?php echo $tanggalFormatted; ?></p>
              </div>
            </div>
            <?php endif; ?>

            <?php if ($seminar['location']): ?>
            <div class="flex items-start gap-3">
              <svg class="w-4 h-4 text-gray-400 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.5-7.5 11.25-7.5 11.25S4.5 18 4.5 10.5a7.5 7.5 0 1115 0z"/>
              </svg>
              <div>
                <p class="text-gray-400 text-xs mb-0.5">Lokasi</p>
                <p class="font-medium text-gray-800"><?php echo htmlspecialchars($seminar['location']); ?></p>
              </div>
            </div>
            <?php endif; ?>

            <div class="flex items-start gap-3">
              <svg class="w-4 h-4 text-gray-400 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              <div>
                <p class="text-gray-400 text-xs mb-0.5">Status</p>
                <p class="font-medium <?php echo $isUpcoming ? 'text-emerald-600' : 'text-gray-500'; ?>"><?php echo $statusLabel; ?></p>
              </div>
            </div>
          </div>

        </div>
      </div>

      <!-- Kolom Kanan: Info Detail -->
      <div class="lg:col-span-2 space-y-8">

        <!-- Header seminar -->
        <div>
          <span class="inline-block text-xs font-semibold <?php echo $isUpcoming ? 'text-emerald-700 bg-emerald-50' : 'text-gray-500 bg-gray-100'; ?> rounded-full px-3 py-1 mb-3 uppercase tracking-wide">
            <?php echo $statusLabel; ?>
          </span>

          <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 leading-tight mb-3">
            <?php echo htmlspecialchars($seminar['title']); ?>
          </h1>

          <div class="flex flex-wrap gap-4 text-sm text-gray-500">
            <?php if ($tanggalFormatted): ?>
            <span class="flex items-center gap-1.5">
              <svg class="w-4 h-4 text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
              <?php echo $tanggalFormatted; ?>
            </span>
            <?php endif; ?>
            <?php if ($seminar['location']): ?>
            <span class="flex items-center gap-1.5">
              <svg class="w-4 h-4 text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.5-7.5 11.25-7.5 11.25S4.5 18 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
              <?php echo htmlspecialchars($seminar['location']); ?>
            </span>
            <?php endif; ?>
          </div>
        </div>

        <!-- Deskripsi -->
        <?php if (!empty($seminar['description'])): ?>
        <div>
          <h2 class="text-base font-bold text-gray-900 mb-3">Tentang Seminar</h2>
          <div class="text-sm text-gray-600 leading-relaxed space-y-3 prose prose-sm max-w-none">
            <?php echo nl2br(htmlspecialchars($seminar['description'])); ?>
          </div>
        </div>
        <?php else: ?>
        <div class="rounded-xl bg-gray-50 border border-dashed border-gray-200 p-6 text-center text-gray-400 text-sm">
          Deskripsi seminar belum tersedia.
        </div>
        <?php endif; ?>

        <!-- Informasi Lengkap (Tabel) -->
        <div>
          <h2 class="text-base font-bold text-gray-900 mb-3">Informasi Seminar</h2>
          <div class="rounded-xl border border-gray-100 overflow-hidden divide-y divide-gray-100 text-sm">

            <div class="grid grid-cols-2 sm:grid-cols-3">
              <div class="px-5 py-3 bg-gray-50 font-medium text-gray-500">Judul</div>
              <div class="px-5 py-3 text-gray-800 sm:col-span-2"><?php echo htmlspecialchars($seminar['title']); ?></div>
            </div>

            <?php if ($tanggalFormatted): ?>
            <div class="grid grid-cols-2 sm:grid-cols-3">
              <div class="px-5 py-3 bg-gray-50 font-medium text-gray-500">Tanggal</div>
              <div class="px-5 py-3 text-gray-800 sm:col-span-2"><?php echo $tanggalFormatted; ?></div>
            </div>
            <?php endif; ?>

            <?php if ($seminar['location']): ?>
            <div class="grid grid-cols-2 sm:grid-cols-3">
              <div class="px-5 py-3 bg-gray-50 font-medium text-gray-500">Lokasi</div>
              <div class="px-5 py-3 text-gray-800 sm:col-span-2"><?php echo htmlspecialchars($seminar['location']); ?></div>
            </div>
            <?php endif; ?>

            <div class="grid grid-cols-2 sm:grid-cols-3">
              <div class="px-5 py-3 bg-gray-50 font-medium text-gray-500">Status</div>
              <div class="px-5 py-3 sm:col-span-2">
                <span class="inline-block text-xs font-semibold px-2.5 py-1 rounded-full <?php echo $isUpcoming ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600'; ?>">
                  <?php echo $statusLabel; ?>
                </span>
              </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3">
              <div class="px-5 py-3 bg-gray-50 font-medium text-gray-500">Biaya</div>
              <div class="px-5 py-3 font-bold <?php echo $seminar['price'] ? 'text-primary-700' : 'text-emerald-600'; ?> sm:col-span-2">
                <?php echo $hargaText; ?>
              </div>
            </div>

            <?php if ($seminar['link']): ?>
            <div class="grid grid-cols-2 sm:grid-cols-3">
              <div class="px-5 py-3 bg-gray-50 font-medium text-gray-500">Link Pendaftaran</div>
              <div class="px-5 py-3 sm:col-span-2">
                <a href="<?php echo htmlspecialchars($seminar['link']); ?>" target="_blank" rel="noopener"
                   class="text-primary-600 hover:text-primary-800 font-medium break-all">
                  <?php echo htmlspecialchars($seminar['link']); ?>
                </a>
              </div>
            </div>
            <?php endif; ?>

          </div>
        </div>

        <!-- Cara Mendaftar -->
        <div class="rounded-xl bg-primary-50 border border-primary-100 p-6">
          <h2 class="text-base font-bold text-gray-900 mb-3 flex items-center gap-2">
            <svg class="w-5 h-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
            </svg>
            Cara Mendaftar
          </h2>
          <ol class="text-sm text-gray-600 space-y-2 list-none">
            <?php if ($seminar['link'] && $isUpcoming): ?>
            <li class="flex items-start gap-2">
              <span class="w-5 h-5 rounded-full bg-primary-600 text-white text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">1</span>
              <span>Klik tombol <strong>Daftar Sekarang</strong> untuk mengisi formulir pendaftaran online.</span>
            </li>
            <li class="flex items-start gap-2">
              <span class="w-5 h-5 rounded-full bg-primary-600 text-white text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">2</span>
              <span>Lakukan pembayaran sesuai instruksi yang diberikan.</span>
            </li>
            <li class="flex items-start gap-2">
              <span class="w-5 h-5 rounded-full bg-primary-600 text-white text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">3</span>
              <span>Konfirmasi pendaftaran via <strong>WhatsApp</strong> ke nomor kami.</span>
            </li>
            <?php else: ?>
            <li class="flex items-start gap-2">
              <span class="w-5 h-5 rounded-full bg-primary-600 text-white text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">1</span>
              <span>Hubungi kami melalui <strong>WhatsApp</strong> untuk informasi pendaftaran.</span>
            </li>
            <li class="flex items-start gap-2">
              <span class="w-5 h-5 rounded-full bg-primary-600 text-white text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">2</span>
              <span>Tim kami akan memandu Anda melalui proses pendaftaran.</span>
            </li>
            <?php endif; ?>
          </ol>
        </div>

        <!-- CTA bawah (tampil di mobile, sticky di desktop ada di kiri) -->
        <div class="flex flex-col sm:flex-row gap-3 lg:hidden">
          <?php if ($seminar['link'] && $isUpcoming): ?>
          <a href="<?php echo htmlspecialchars($seminar['link']); ?>" target="_blank" rel="noopener"
             class="flex-1 flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-primary-600 text-white font-semibold hover:bg-primary-700 transition-colors text-sm">
            Daftar Sekarang
          </a>
          <?php endif; ?>
          <a href="<?php echo htmlspecialchars($linkWA); ?>" target="_blank" rel="noopener"
             class="flex-1 flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-green-600 text-white font-semibold hover:bg-green-700 transition-colors text-sm">
            <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12.04 2.25c-5.46 0-9.88 4.42-9.88 9.88 0 1.74.46 3.44 1.33 4.94L2 21.75l4.8-1.46a9.83 9.83 0 004.24.98h.01c5.46 0 9.88-4.42 9.88-9.88 0-5.46-4.42-9.88-9.89-9.88Zm5.7 14.02c-.24.68-1.4 1.3-1.93 1.34-.5.05-.99.24-3.32-.7-2.8-1.14-4.6-3.98-4.75-4.16-.14-.19-1.14-1.52-1.14-2.9 0-1.37.72-2.05.98-2.33.26-.28.56-.35.75-.35h.53c.17 0 .4-.06.62.48.24.58.8 2 .87 2.14.07.14.12.31.02.5-.1.19-.15.31-.3.48-.15.17-.31.38-.44.51-.15.15-.3.3-.13.6.17.3.77 1.27 1.65 2.06 1.14 1.02 2.09 1.34 2.39 1.49.3.15.47.13.65-.08.17-.2.74-.86.94-1.16.2-.3.4-.25.66-.15.27.1 1.72.81 2.02.96.3.15.5.22.57.35.07.13.07.75-.17 1.43Z"/>
            </svg>
            Hubungi via WhatsApp
          </a>
          <a href="/seminar.php" class="flex-1 flex items-center justify-center px-5 py-3 rounded-xl border border-primary-200 text-primary-700 font-semibold hover:bg-primary-50 transition-colors text-sm">
            &larr; Kembali
          </a>
        </div>

      </div>
    </div>
  </section>

  <!-- Seminar Lainnya -->
  <?php if (!empty($rekomendasi)): ?>
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
    <div class="border-t border-gray-100 pt-12">
      <h2 class="text-xl font-bold text-gray-900 mb-6">Seminar Lainnya</h2>
      <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($rekomendasi as $rek): ?>
          <?php
            $rekUpcoming = $rek['date'] && strtotime($rek['date']) >= strtotime('today');
            $rekStatus   = $rekUpcoming ? 'Akan Datang' : 'Selesai';
            $rekHarga    = $rek['price'] ? 'Rp' . number_format($rek['price'], 0, ',', '.') : 'Gratis';
            $rekTanggal  = $rek['date'] ? date('d M Y', strtotime($rek['date'])) : null;
            $rekPesanWA  = "Halo, saya ingin bertanya tentang seminar \"{$rek['title']}\".";
            $rekLinkWA   = 'https://wa.me/' . $whatsappNomor . '?text=' . urlencode($rekPesanWA);
          ?>
          <div class="rounded-xl border border-gray-100 overflow-hidden hover:shadow-sm transition-shadow bg-white flex flex-col">
            <!-- Poster mini -->
            <div class="h-36 bg-[#1E1B3A] relative overflow-hidden">
              <?php if ($rek['image']): ?>
                <img src="<?php echo htmlspecialchars($rek['image']); ?>" alt="<?php echo htmlspecialchars($rek['title']); ?>" class="w-full h-full object-cover">
              <?php else: ?>
                <div class="w-full h-full flex items-center justify-center">
                  <svg class="w-8 h-8 text-white/20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.09 9.09 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.94 11.94 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg>
                </div>
              <?php endif; ?>
              <span class="absolute top-2 right-2 text-xs font-semibold px-2 py-0.5 rounded-full <?php echo $rekUpcoming ? 'bg-white text-emerald-700' : 'bg-white/80 text-gray-500'; ?>">
                <?php echo $rekStatus; ?>
              </span>
            </div>

            <div class="p-4 flex flex-col flex-1">
              <h3 class="text-xs font-semibold text-gray-900 leading-snug line-clamp-2 min-h-[2.5rem]">
                <?php echo htmlspecialchars($rek['title']); ?>
              </h3>
              <?php if ($rekTanggal): ?>
              <p class="text-[11px] text-gray-500 mt-1"><?php echo $rekTanggal; ?></p>
              <?php endif; ?>
              <p class="text-xs font-bold <?php echo $rek['price'] ? 'text-primary-700' : 'text-emerald-600'; ?> mt-2">
                <?php echo $rekHarga; ?>
              </p>
              <div class="mt-auto pt-3 space-y-2">
                <a href="/seminar/detail.php?id=<?php echo $rek['id']; ?>"
                   class="block text-center text-[11px] font-semibold text-primary-700 border border-primary-200 rounded-lg py-1.5 hover:bg-primary-50 transition-colors">
                  Lihat Detail
                </a>
                <a href="<?php echo htmlspecialchars($rekLinkWA); ?>" target="_blank" rel="noopener"
                   class="flex items-center justify-center gap-1 text-[11px] font-semibold text-white bg-green-600 rounded-lg py-1.5 hover:bg-green-700 transition-colors">
                  <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12.04 2.25c-5.46 0-9.88 4.42-9.88 9.88 0 1.74.46 3.44 1.33 4.94L2 21.75l4.8-1.46a9.83 9.83 0 004.24.98h.01c5.46 0 9.88-4.42 9.88-9.88 0-5.46-4.42-9.88-9.89-9.88Zm5.7 14.02c-.24.68-1.4 1.3-1.93 1.34-.5.05-.99.24-3.32-.7-2.8-1.14-4.6-3.98-4.75-4.16-.14-.19-1.14-1.52-1.14-2.9 0-1.37.72-2.05.98-2.33.26-.28.56-.35.75-.35h.53c.17 0 .4-.06.62.48.24.58.8 2 .87 2.14.07.14.12.31.02.5-.1.19-.15.31-.3.48-.15.17-.31.38-.44.51-.15.15-.3.3-.13.6.17.3.77 1.27 1.65 2.06 1.14 1.02 2.09 1.34 2.39 1.49.3.15.47.13.65-.08.17-.2.74-.86.94-1.16.2-.3.4-.25.66-.15.27.1 1.72.81 2.02.96.3.15.5.22.57.35.07.13.07.75-.17 1.43Z"/>
                  </svg>
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
