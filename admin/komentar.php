<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

// Auth check
if (!isset($_SESSION['user_id'])) {
    header("Location: /login-secure-xyz.php");
    exit;
}

$pdo = getDB();
$stmt = $pdo->query("SELECT * FROM comments ORDER BY created_at DESC");
$comments = $stmt->fetchAll();

$adminPage = 'komentar';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Komentar - Admin Nawa Edukasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden text-gray-800">

    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        <!-- Header -->
        <header class="bg-white border-b border-gray-200 px-8 py-5 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-4">
                <button onclick="toggleSidebar()" class="md:hidden text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </button>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Kelola Komentar</h1>
                    <p class="text-sm text-gray-500 mt-1">Kelola komentar dari pengunjung website.</p>
                </div>
            </div>
        </header>

        <!-- Content area -->
        <div class="flex-1 overflow-auto p-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm whitespace-nowrap">
                        <thead class="bg-gray-50 text-gray-600 font-semibold border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4">Nama</th>
                                <th class="px-6 py-4">Instansi</th>
                                <th class="px-6 py-4">Komentar</th>
                                <th class="px-6 py-4">Tanggal</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php if (empty($comments)): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    Belum ada komentar.
                                </td>
                            </tr>
                            <?php else: ?>
                                <?php foreach ($comments as $c): ?>
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4 font-medium text-gray-900"><?php echo htmlspecialchars($c['nama']); ?></td>
                                    <td class="px-6 py-4 text-gray-600"><?php echo htmlspecialchars($c['instansi'] ?? '-'); ?></td>
                                    <td class="px-6 py-4 text-gray-600 max-w-xs truncate" title="<?php echo htmlspecialchars($c['komentar']); ?>">
                                        <?php echo htmlspecialchars($c['komentar']); ?>
                                    </td>
                                    <td class="px-6 py-4 text-gray-500"><?php echo date('d M Y H:i', strtotime($c['created_at'])); ?></td>
                                    <td class="px-6 py-4 text-center flex items-center justify-center gap-4">
                                        <!-- Toggle Switch -->
                                        <label class="relative inline-flex items-center cursor-pointer" title="Tampilkan di beranda">
                                            <input type="checkbox" class="sr-only peer" 
                                                   onchange="toggleKomentar(<?php echo $c['id']; ?>, this.checked)"
                                                   <?php echo $c['is_active'] ? 'checked' : ''; ?>>
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#1E1B3A]"></div>
                                        </label>
                                        
                                        <!-- Delete Button -->
                                        <button type="button" onclick="deleteKomentar(<?php echo $c['id']; ?>)" class="text-red-500 hover:text-red-700 transition-colors" title="Hapus Komentar">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
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

    <script>
    function toggleKomentar(id, isChecked) {
        fetch('/api/toggle-comment.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                id: id,
                is_active: isChecked ? 1 : 0
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status !== 'success') {
                alert(data.message);
                // Revert toggle if failed
                event.target.checked = !isChecked;
            }
        })
        .catch(err => {
            console.error(err);
            alert('Terjadi kesalahan jaringan.');
            event.target.checked = !isChecked;
        });
    }

    function deleteKomentar(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus komentar ini?')) {
            return;
        }

        fetch('/api/delete-comment.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: id })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                window.location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(err => {
            console.error(err);
            alert('Terjadi kesalahan jaringan saat menghapus.');
        });
    }
    </script>
</body>
</html>
