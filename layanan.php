<?php
$activePage = 'layanan';
include 'includes/header.php';

$whatsappNomor = '6281916200962';

$layananList = [
  [
    'judul' => 'Publikasi Jurnal',
    'deskripsi' => 'Kami menyediakan layanan publikasi artikel ilmiah pada jurnal nasional maupun internasional yang sesuai dengan bidang keilmuan Anda. Tim kami membantu proses mulai dari pemilihan jurnal, penyesuaian naskah, hingga proses submission, sehingga peluang publikasi menjadi lebih optimal.',
    'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.5L17 7.5V19a2 2 0 01-2 2z',
  ],
  [
    'judul' => 'Penerbit Buku',
    'deskripsi' => 'Wujudkan karya terbaik Anda menjadi buku berkualitas bersama kami. Layanan penerbitan meliputi penyuntingan naskah, layout profesional, desain cover, pengurusan ISBN, pencetakan, hingga distribusi buku. Cocok untuk dosen, guru, mahasiswa, peneliti, maupun penulis umum.',
    'icon' => 'M12 6.04A8.97 8.97 0 006 3.75c-1.05 0-2.06.18-3 .5v14.25A8.99 8.99 0 016 18c2.3 0 4.4.87 6 2.29m0-14.25a8.97 8.97 0 016-2.29c1.05 0 2.06.18 3 .5v14.25A8.99 8.99 0 0018 18a8.97 8.97 0 00-6 2.29m0-14.25v14.25',
  ],
  [
    'judul' => 'Hak Kekayaan Intelektual (HKI)',
    'deskripsi' => 'Lindungi hasil karya dan inovasi Anda melalui layanan pendaftaran Hak Kekayaan Intelektual. Kami mendampingi proses pengajuan hak cipta mulai dari persiapan dokumen, pengisian administrasi, hingga penerbitan sertifikat resmi dari Direktorat Jenderal Kekayaan Intelektual (DJKI).',
    'icon' => 'M9 12.75L11.25 15 15 9.75m-3-7.036A11.96 11.96 0 013.598 6 11.96 11.96 0 001.5 12c0 5.83 3.4 10.86 8.318 13.246a11.96 11.96 0 004.365 0C19.1 22.86 22.5 17.83 22.5 12a11.96 11.96 0 00-2.098-6 11.96 11.96 0 01-8.402-3.286z',
  ],
  [
    'judul' => 'Pembuatan Open Journal Systems (OJS)',
    'deskripsi' => 'Kami melayani pembuatan dan pengembangan website jurnal berbasis Open Journal Systems (OJS) yang modern, responsif, dan sesuai standar pengelolaan jurnal ilmiah. Layanan mencakup instalasi, konfigurasi, desain tampilan, pengaturan workflow, DOI, indexing, hingga pendampingan pengelolaan jurnal.',
    'icon' => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
  ],
  [
    'judul' => 'Pendampingan Artikel Ilmiah',
    'deskripsi' => 'Tingkatkan kualitas artikel ilmiah Anda bersama tim editor berpengalaman. Layanan kami meliputi konsultasi penelitian, penyusunan artikel sesuai template jurnal, proofreading, editing bahasa Indonesia maupun Inggris, pengecekan sitasi, reference manager (Mendeley/Zotero), pemeriksaan plagiarisme, hingga persiapan submit ke jurnal tujuan.',
    'icon' => 'M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z',
  ],
];

$keunggulanList = [
  'Tim profesional dan berpengalaman di bidang akademik dan publikasi.',
  'Layanan cepat, responsif, dan terpercaya.',
  'Pendampingan dari awal hingga proses selesai.',
  'Harga kompetitif dengan kualitas terbaik.',
  'Berkomitmen mendukung dosen, guru, mahasiswa, peneliti, dan institusi dalam menghasilkan karya ilmiah yang berkualitas dan bereputasi.',
];
?>

  <!-- Hero -->
  <section class="bg-primary-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
      <span class="inline-block text-xs font-semibold text-primary-700 bg-white rounded-full px-3 py-1 mb-4">Layanan Kami</span>
      <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 leading-tight max-w-2xl">
        Solusi Lengkap untuk Publikasi, Penerbitan, dan Perlindungan Karya Ilmiah Anda
      </h1>
      <p class="text-gray-500 mt-4 max-w-lg">
        Dari naskah hingga terbit, dari ide hingga terlindungi secara hukum — tim Nawa Edukasi siap mendampingi setiap langkahnya.
      </p>
      <p class="text-sm text-gray-500 mt-6">
        <a href="/" class="hover:text-primary-700">Beranda</a> / <span class="text-primary-700 font-medium">Layanan</span>
      </p>
    </div>
  </section>

  <!-- Daftar Layanan -->
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-6">
    <?php foreach ($layananList as $l): ?>
      <div class="rounded-2xl border border-gray-100 shadow-md p-8 flex flex-col sm:flex-row gap-6 items-start hover:border-primary-200 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
        <div class="w-14 h-14 rounded-xl bg-primary-50 flex items-center justify-center shrink-0">
          <svg class="w-7 h-7 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="<?php echo $l['icon']; ?>" />
          </svg>
        </div>
        <div class="flex-1">
          <h3 class="text-lg font-bold text-gray-900 mb-2"><?php echo htmlspecialchars($l['judul']); ?></h3>
          <p class="text-sm text-gray-600 leading-relaxed"><?php echo htmlspecialchars($l['deskripsi']); ?></p>
        </div>
      </div>
    <?php endforeach; ?>
  </section>

  <!-- Mengapa Memilih Kami? -->
  <section class="bg-[#1E1B3A]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
      <h2 class="text-2xl sm:text-3xl font-bold text-white mb-10">Mengapa Memilih Kami?</h2>
      <div class="grid sm:grid-cols-2 gap-6">
        <?php foreach ($keunggulanList as $poin): ?>
          <div class="flex items-start gap-3">
            <div class="w-6 h-6 rounded-full bg-primary-600 flex items-center justify-center shrink-0 mt-0.5">
              <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
            </div>
            <p class="text-gray-200 leading-relaxed"><?php echo htmlspecialchars($poin); ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- CTA -->
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
    <h2 class="text-2xl font-bold text-gray-900 mb-3">Masih Ada Pertanyaan?</h2>
    <p class="text-gray-500 max-w-md mx-auto mb-8">Tim kami siap membantu menjelaskan layanan yang paling sesuai dengan kebutuhan Anda.</p>
    <a href="https://wa.me/<?php echo $whatsappNomor; ?>" target="_blank" rel="noopener"
       class="inline-flex items-center px-6 py-3 rounded-lg bg-primary-600 text-white font-semibold hover:bg-primary-700 transition-colors">
      Hubungi via WhatsApp
    </a>
  </section>

<?php include 'includes/footer.php'; ?>