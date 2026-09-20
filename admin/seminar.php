<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /login-secure-xyz.php");
    exit;
}

$pdo = getDB();
$stmt = $pdo->query("SELECT * FROM seminars ORDER BY created_at DESC");
$seminars = $stmt->fetchAll();

$adminPage = 'seminar';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Seminar - Admin Nawa Edukasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden text-gray-800">

    <?php include 'sidebar.php'; ?>

    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        <header class="bg-white border-b border-gray-200 px-8 py-5 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-4">
                <button onclick="toggleSidebar()" class="md:hidden text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </button>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Kelola Seminar</h1>
                    <p class="text-sm text-gray-500 mt-1">Daftar semua acara seminar & workshop.</p>
                </div>
            </div>
            <a href="seminar_tambah.php" class="inline-flex items-center gap-2 bg-[#1E1B3A] text-white px-5 py-2.5 rounded-lg text-sm font-semibold hover:bg-gray-800 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Tambah Seminar
            </a>
        </header>

        <div class="flex-1 overflow-auto p-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm whitespace-nowrap">
                        <thead class="bg-gray-50 text-gray-600 font-semibold border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4">Cover</th>
                                <th class="px-6 py-4">Judul Seminar</th>
                                <th class="px-6 py-4">Tanggal</th>
                                <th class="px-6 py-4">Lokasi</th>
                                <th class="px-6 py-4">Harga</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php if(empty($seminars)): ?>
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">Belum ada data seminar.</td>
                            </tr>
                            <?php else: ?>
                                <?php foreach ($seminars as $s): ?>
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="w-16 h-12 bg-gray-100 rounded overflow-hidden flex items-center justify-center">
                                            <?php if($s['image']): ?>
                                                <img src="<?php echo htmlspecialchars($s['image']); ?>" alt="Cover" class="w-full h-full object-cover">
                                            <?php else: ?>
                                                <svg class="w-6 h-6 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 font-medium text-gray-900"><?php echo htmlspecialchars($s['title']); ?></td>
                                    <td class="px-6 py-4 text-gray-600"><?php echo $s['date'] ? date('d M Y', strtotime($s['date'])) : '-'; ?></td>
                                    <td class="px-6 py-4 text-gray-600"><?php echo htmlspecialchars($s['location'] ?? '-'); ?></td>
                                    <td class="px-6 py-4 text-gray-800 font-medium">
                                        <?php echo $s['price'] ? 'Rp' . number_format($s['price'], 0, ',', '.') : '<span class="text-emerald-600 font-semibold">Gratis</span>'; ?>
                                    </td>
                                    <td class="px-6 py-4 text-center flex items-center justify-center gap-3">
                                        <!-- Toggle Status -->
                                        <label class="relative inline-flex items-center cursor-pointer mr-2" title="Tampilkan di Beranda">
                                            <input type="checkbox" class="sr-only peer" onchange="toggleSeminar(<?php echo $s['id']; ?>, this.checked)" <?php echo $s['is_active'] ? 'checked' : ''; ?>>
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#1E1B3A]"></div>
                                        </label>

                                        <a href="seminar_edit.php?id=<?php echo $s['id']; ?>" class="text-primary-600 hover:text-primary-800 transition-colors" title="Edit">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        <button onclick="deleteSeminar(<?php echo $s['id']; ?>)" class="text-red-500 hover:text-red-700 transition-colors" title="Hapus">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
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
    function toggleSeminar(id, isChecked) {
        fetch('/api/toggle-seminar.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: id, is_active: isChecked ? 1 : 0 })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status !== 'success') {
                alert(data.message);
                event.target.checked = !isChecked;
            }
        }).catch(err => {
            alert('Kesalahan jaringan.');
            event.target.checked = !isChecked;
        });
    }

    function deleteSeminar(id) {
        if(!confirm('Yakin ingin menghapus seminar ini?')) return;

        fetch('/api/delete-seminar.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: id })
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                window.location.reload();
            } else {
                alert(data.message);
            }
        });
    }
    </script>
</body>
</html>
