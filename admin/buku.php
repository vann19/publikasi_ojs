<?php
require_once '../includes/auth.php';
checkAuth();
require_once '../includes/db.php';

$adminPage = 'buku';
$pdo = getDB();
$books = $pdo->query("SELECT * FROM books ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kelola Buku | Admin Nawa Edukasi</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/assets/css/output.css">
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
                <h1 class="text-xl font-bold text-gray-900">Kelola Buku</h1>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-primary-100 flex items-center justify-center">
                    <span class="text-sm font-bold text-primary-700"><?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?></span>
                </div>
                <span class="text-sm font-medium text-gray-700 hidden sm:block"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 overflow-y-auto p-6 sm:p-8">
            <div class="max-w-6xl mx-auto">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-gray-900">Daftar Buku</h2>
                    <a href="buku_tambah.php" class="bg-primary-600 hover:bg-primary-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                        + Tambah Buku
                    </a>
                </div>

                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="py-3 px-4 font-semibold text-sm text-gray-700">Cover</th>
                                <th class="py-3 px-4 font-semibold text-sm text-gray-700">Judul</th>
                                <th class="py-3 px-4 font-semibold text-sm text-gray-700">Penulis</th>
                                <th class="py-3 px-4 font-semibold text-sm text-gray-700">Kategori</th>
                                <th class="py-3 px-4 font-semibold text-sm text-gray-700 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($books) > 0): ?>
                                <?php foreach ($books as $book): ?>
                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                    <td class="py-3 px-4">
                                        <?php if ($book['image']): ?>
                                            <img src="<?php echo htmlspecialchars($book['image']); ?>" alt="Cover" class="w-12 h-16 object-cover rounded shadow-sm">
                                        <?php else: ?>
                                            <div class="w-12 h-16 bg-gray-200 flex items-center justify-center rounded text-xs text-gray-500">No Img</div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-3 px-4 text-sm font-medium text-gray-900"><?php echo htmlspecialchars($book['title']); ?></td>
                                    <td class="py-3 px-4 text-sm text-gray-600"><?php echo htmlspecialchars($book['author']); ?></td>
                                    <td class="py-3 px-4 text-sm text-gray-600"><?php echo htmlspecialchars($book['category'] ?? '-'); ?></td>
                                    <td class="py-3 px-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="buku_edit.php?id=<?php echo $book['id']; ?>" class="text-primary-600 hover:text-primary-800 text-sm font-medium">Edit</a>
                                            <button onclick="deleteBook(<?php echo $book['id']; ?>)" class="text-red-600 hover:text-red-800 text-sm font-medium">Hapus</button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="py-6 text-center text-gray-500">Belum ada buku.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </main>
    </div>
</div>

<script>
async function deleteBook(id) {
    if (!confirm('Yakin ingin menghapus buku ini?')) return;
    try {
        const formData = new FormData();
        formData.append('id', id);
        const res = await fetch('/api/delete-book.php', { method: 'POST', body: formData });
        const data = await res.json();
        if (data.status === 'success') {
            window.location.href = '/buku/'; // Redirect per user request
        } else {
            alert(data.message || 'Gagal menghapus buku');
        }
    } catch (e) {
        alert('Terjadi kesalahan sistem.');
    }
}
</script>

</body>
</html>
