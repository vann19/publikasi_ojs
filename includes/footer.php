</main>
<!-- Footer -->
<footer class="bg-gray-900 text-gray-300 mt-auto">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
      <!-- Brand -->
      <div class="col-span-1 md:col-span-2">
        <div class="flex items-center gap-2 mb-4">
          <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center">
            <span class="text-white font-bold text-sm">OJS</span>
          </div>
          <span class="text-xl font-bold text-white">Publikasi</span>
        </div>
        <p class="text-sm text-gray-400 leading-relaxed max-w-xs">
          Platform publikasi jurnal ilmiah terbuka untuk mendukung penyebaran pengetahuan akademik Indonesia.
        </p>
      </div>

      <!-- Links -->
      <div>
        <h4 class="text-white font-semibold mb-4">Navigasi</h4>
        <ul class="space-y-2 text-sm">
          <li><a href="/" class="hover:text-white transition-colors">Beranda</a></li>
          <li><a href="/pages/journals.php" class="hover:text-white transition-colors">Jurnal</a></li>
          <li><a href="/pages/articles.php" class="hover:text-white transition-colors">Artikel</a></li>
          <li><a href="/pages/about.php" class="hover:text-white transition-colors">Tentang Kami</a></li>
        </ul>
      </div>

      <!-- Kontak -->
      <div>
        <h4 class="text-white font-semibold mb-4">Kontak</h4>
        <ul class="space-y-2 text-sm">
          <li class="flex items-center gap-2">
            <span>✉️</span>
            <a href="mailto:info@ojspublikasi.id" class="hover:text-white transition-colors">info@ojspublikasi.id</a>
          </li>
          <li class="flex items-center gap-2">
            <span>📍</span>
            <span>Indonesia</span>
          </li>
        </ul>
      </div>
    </div>

    <div class="border-t border-gray-700 mt-10 pt-6 flex flex-col sm:flex-row justify-between items-center gap-2 text-sm text-gray-500">
      <p>&copy; <?= date('Y') ?> OJS Publikasi. Hak Cipta Dilindungi.</p>
      <p>Dibuat dengan ❤️ untuk komunitas akademik Indonesia</p>
    </div>
  </div>
</footer>

</body>
</html>
