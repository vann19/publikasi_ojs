<?php
// Admin Sidebar Component
// Usage: $adminPage = 'dashboard'; include 'sidebar.php';
$adminPage = $adminPage ?? 'dashboard';
?>
<aside id="sidebar" class="w-64 bg-[#1E1B3A] text-white flex flex-col shrink-0 transition-transform duration-300 fixed inset-y-0 left-0 z-40 md:relative md:translate-x-0 -translate-x-full">
    <!-- Logo -->
    <div class="h-20 flex items-center px-6 border-b border-white/10">
        <a href="/admin/index.php" class="flex items-center gap-3">
            <img src="/assets/img/logo.png" alt="Nawa Edukasi" class="h-8 w-auto brightness-0 invert">
            <span class="font-bold text-lg">Admin</span>
        </a>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
        <p class="px-4 text-xs uppercase tracking-wider text-gray-400 mb-3">Menu Utama</p>

        <a href="/admin/index.php" class="flex items-center gap-3 px-4 py-3 rounded-lg <?php echo $adminPage === 'dashboard' ? 'bg-white/10 text-white' : 'text-gray-300 hover:bg-white/10 hover:text-white transition-colors'; ?> font-medium">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
            Dashboard
        </a>

        <p class="px-4 text-xs uppercase tracking-wider text-gray-400 mt-6 mb-3">Kelola Data</p>

        <a href="/admin/buku.php" class="flex items-center gap-3 px-4 py-3 rounded-lg <?php echo $adminPage === 'buku' ? 'bg-white/10 text-white' : 'text-gray-300 hover:bg-white/10 hover:text-white transition-colors'; ?> font-medium">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.04A8.97 8.97 0 006 3.75c-1.05 0-2.06.18-3 .5v14.25A8.99 8.99 0 016 18c2.3 0 4.4.87 6 2.29m0-14.25a8.97 8.97 0 016-2.29c1.05 0 2.06.18 3 .5v14.25A8.99 8.99 0 0018 18a8.97 8.97 0 00-6 2.29m0-14.25v14.25" /></svg>
            Kelola Buku
        </a>
        <a href="/admin/jurnal.php" class="flex items-center gap-3 px-4 py-3 rounded-lg <?php echo $adminPage === 'jurnal' ? 'bg-white/10 text-white' : 'text-gray-300 hover:bg-white/10 hover:text-white transition-colors'; ?> font-medium">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.5L17 7.5V19a2 2 0 01-2 2z" /></svg>
            Kelola Jurnal
        </a>

        <a href="/admin/komentar.php" class="flex items-center gap-3 px-4 py-3 rounded-lg <?php echo $adminPage === 'komentar' ? 'bg-white/10 text-white' : 'text-gray-300 hover:bg-white/10 hover:text-white transition-colors'; ?> font-medium">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.5L17 7.5V19a2 2 0 01-2 2z" /></svg>
            Kelola Komentar
        </a>


        <p class="px-4 text-xs uppercase tracking-wider text-gray-400 mt-6 mb-3">Lainnya</p>

        <a href="/" target="_blank" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-300 hover:bg-white/10 hover:text-white transition-colors font-medium">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" /></svg>
            Lihat Website
        </a>
    </nav>

    <!-- Logout -->
    <div class="p-4 border-t border-white/10">
        <a href="/api/logout.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-red-300 hover:bg-red-500/20 hover:text-red-200 font-medium transition-colors">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
            Logout
        </a>
    </div>
</aside>

<!-- Sidebar Overlay (Mobile) -->
<div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-30 hidden md:hidden" onclick="toggleSidebar()"></div>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    sidebar.classList.toggle('-translate-x-full');
    overlay.classList.toggle('hidden');
}
</script>
