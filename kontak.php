<?php
$activePage = 'kontak';
include 'includes/header.php';
?>

  <!-- Hero -->
  <section class="bg-primary-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
      <span class="inline-block text-xs font-semibold text-primary-700 bg-white rounded-full px-3 py-1 mb-4">Kontak</span>
      <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 leading-tight max-w-2xl">
        Hubungi Kami
      </h1>
      <p class="text-gray-500 mt-4 max-w-lg">
        Ada pertanyaan seputar penerbitan buku, jurnal ilmiah, HKI, atau layanan lainnya? Tim kami siap membantu.
      </p>
      <p class="text-sm text-gray-500 mt-6">
        <a href="/" class="hover:text-primary-700">Beranda</a> / <span class="text-primary-700 font-medium">Kontak</span>
      </p>
    </div>
  </section>

  <!-- Info Kontak -->
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 grid lg:grid-cols-2 gap-10">

    <!-- Info kontak -->
    <div class="space-y-6">

      <div class="rounded-2xl border border-gray-100 shadow-md p-6 space-y-6 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
        <div class="flex gap-4">
          <div class="w-10 h-10 rounded-full bg-primary-50 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.5-7.5 11.25-7.5 11.25S4.5 18 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
          </div>
          <div>
            <p class="text-sm font-semibold text-gray-900 mb-1">Alamat</p>
            <p class="text-sm text-gray-500 leading-relaxed">Jl. Gua Selarong No. 54, Dusun Gayam RT 06, Ringinharjo, Bantul, Daerah Istimewa Yogyakarta</p>
          </div>
        </div>
        <div class="flex gap-4">
          <div class="w-10 h-10 rounded-full bg-primary-50 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a1.5 1.5 0 001.5-1.5v-3a1.5 1.5 0 00-1.06-1.44l-4.19-1.24a1.5 1.5 0 00-1.62.44l-1 1.22a11.25 11.25 0 01-6.16-6.16l1.22-1a1.5 1.5 0 00.44-1.62L7.19 3.31a1.5 1.5 0 00-1.44-1.06h-3a1.5 1.5 0 00-1.5 1.5z"/></svg>
          </div>
          <div>
            <p class="text-sm font-semibold text-gray-900 mb-1">WhatsApp</p>
            <a href="https://wa.me/6281916200962" class="text-sm text-gray-500 hover:text-primary-700">0819-1620-0962</a>
          </div>
        </div>
        <div class="flex gap-4">
          <div class="w-10 h-10 rounded-full bg-primary-50 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
          </div>
          <div>
            <p class="text-sm font-semibold text-gray-900 mb-1">Email</p>
            <a href="mailto:nawaedukasinusantara@gmail.com" class="text-sm text-gray-500 hover:text-primary-700 break-all">nawaedukasinusantara@gmail.com</a>
          </div>
        </div>
        <div class="flex gap-4">
          <div class="w-10 h-10 rounded-full bg-primary-50 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          </div>
          <div>
            <p class="text-sm font-semibold text-gray-900 mb-1">Jam Operasional</p>
            <p class="text-sm text-gray-500">Senin &ndash; Jumat, 08.00 &ndash; 16.00 WIB</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Peta -->
    <div class="rounded-2xl overflow-hidden border border-gray-100 h-80">
      <iframe
        src="https://www.google.com/maps?q=Jl.+Gua+Selarong+No.+54,+Ringinharjo,+Bantul,+DIY&output=embed"
        class="w-full h-full border-0" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
        title="Lokasi PT Nawa Edukasi Nusantara"></iframe>
    </div>

  </section>

<?php include 'includes/footer.php'; ?>