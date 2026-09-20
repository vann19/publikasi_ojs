<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
checkAuth();

$adminPage = 'jurnal';
$pdo = getDB();

// Ambil daftar jurnal dari DB
$stmt = $pdo->query("SELECT * FROM journals ORDER BY id DESC");
$journals = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kelola Jurnal OJS | Admin Nawa Edukasi</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/assets/css/output.css">
  <style>
    /* Toggle Switch CSS */
    .toggle-checkbox:checked {
      right: 0;
      border-color: #059669;
    }
    .toggle-checkbox:checked + .toggle-label {
      background-color: #059669;
    }
  </style>
</head>
<body class="bg-gray-50 font-sans text-gray-800 antialiased">

<div class="flex h-screen overflow-hidden">
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Top Bar -->
        <header class="h-20 bg-white border-b border-gray-100 flex items-center justify-between px-6 shrink-0">
            <div class="flex items-center gap-4">
                <button onclick="toggleSidebar()" class="md:hidden text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </button>
                <h1 class="text-xl font-bold text-gray-900">Kelola Jurnal OJS</h1>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-primary-100 flex items-center justify-center">
                    <span class="text-sm font-bold text-primary-700"><?php echo strtoupper(substr($_SESSION['username'] ?? 'A', 0, 1)); ?></span>
                </div>
                <span class="text-sm font-medium text-gray-700 hidden sm:block"><?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?></span>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 overflow-y-auto p-6 sm:p-8">
            <div class="max-w-6xl mx-auto">
                
                <div id="jurnal-alert" class="hidden mb-4 p-4 text-sm rounded-lg"></div>

                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div>
                            <h2 class="text-lg font-bold text-gray-900">Daftar Jurnal</h2>
                            <p class="text-sm text-gray-500">Kelola daftar jurnal ilmiah yang ditampilkan di website.</p>
                        </div>
                        <button onclick="scrapeData()" id="btn-scrape" class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors text-sm">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                            Scrape Data OJS
                        </button>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-100 text-xs uppercase text-gray-500 tracking-wider">
                                    <th class="p-4 font-semibold">Cover</th>
                                    <th class="p-4 font-semibold">Jurnal</th>
                                    <th class="p-4 font-semibold">OJS Link</th>
                                    <th class="p-4 font-semibold text-center">Tampilkan di Web</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php if(empty($journals)): ?>
                                <tr>
                                    <td colspan="4" class="p-8 text-center text-gray-500">Belum ada data jurnal. Silakan klik "Scrape Data OJS" untuk menarik data dari website OJS.</td>
                                </tr>
                                <?php else: ?>
                                    <?php foreach($journals as $j): ?>
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="p-4 align-middle">
                                            <?php if(!empty($j['image'])): ?>
                                                <img src="<?php echo htmlspecialchars($j['image']); ?>" alt="Cover" class="w-16 h-20 object-cover rounded shadow-sm border border-gray-200">
                                            <?php else: ?>
                                                <div class="w-16 h-20 bg-gray-200 rounded flex items-center justify-center text-gray-400 text-xs text-center border border-gray-300">No Cover</div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="p-4 align-middle">
                                            <p class="font-bold text-gray-900 text-sm mb-1"><?php echo htmlspecialchars($j['title']); ?></p>
                                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold bg-gray-100 text-gray-600 border border-gray-200">
                                                Path: <?php echo htmlspecialchars($j['path'] ?? '-'); ?>
                                            </span>
                                        </td>
                                        <td class="p-4 align-middle">
                                            <a href="<?php echo htmlspecialchars($j['link']); ?>" target="_blank" class="text-sm text-primary-600 hover:underline">Lihat OJS</a>
                                        </td>
                                        <td class="p-4 align-middle text-center">
                                            <div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                                                <input type="checkbox" name="toggle" id="toggle-<?php echo $j['id']; ?>" class="toggle-checkbox absolute block w-5 h-5 rounded-full bg-white border-4 appearance-none cursor-pointer z-10 top-0 bottom-0 m-auto transition-all duration-300 border-gray-300" <?php echo $j['is_active'] ? 'checked' : ''; ?> onchange="toggleStatus(<?php echo $j['id']; ?>, this.checked)"/>
                                                <label for="toggle-<?php echo $j['id']; ?>" class="toggle-label block overflow-hidden h-5 rounded-full bg-gray-300 cursor-pointer transition-colors duration-300"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
async function scrapeData() {
    const btn = document.getElementById('btn-scrape');
    const alertBox = document.getElementById('jurnal-alert');
    
    // UI Loading state
    btn.disabled = true;
    btn.innerHTML = `<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Scraping...`;
    
    try {
        const response = await fetch('/api/scrape-ojs.php');
        const data = await response.json();
        
        alertBox.className = data.status === 'success'
            ? 'mb-4 p-4 text-sm rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200 block'
            : 'mb-4 p-4 text-sm rounded-lg bg-red-50 text-red-800 border border-red-200 block';
        alertBox.textContent = data.message;
        
        if (data.status === 'success') {
            setTimeout(() => location.reload(), 2000); // Reload untuk melihat data baru
        }
    } catch (error) {
        alertBox.className = 'mb-4 p-4 text-sm rounded-lg bg-red-50 text-red-800 block';
        alertBox.textContent = 'Gagal menghubungi server.';
    } finally {
        btn.disabled = false;
        btn.innerHTML = `<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg> Scrape Data OJS`;
    }
}

async function toggleStatus(id, isActive) {
    const formData = new FormData();
    formData.append('id', id);
    formData.append('is_active', isActive ? 1 : 0);
    
    try {
        const response = await fetch('/api/toggle-jurnal.php', {
            method: 'POST',
            body: formData
        });
        const data = await response.json();
        if (data.status !== 'success') {
            alert('Gagal mengubah status: ' + data.message);
            // Revert the toggle visually if it failed
            document.getElementById('toggle-' + id).checked = !isActive;
        }
    } catch (error) {
        alert('Terjadi kesalahan jaringan.');
        document.getElementById('toggle-' + id).checked = !isActive;
    }
}
</script>

</body>
</html>
