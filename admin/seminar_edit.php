<?php
require_once '../includes/auth.php';
checkAuth();
require_once '../includes/db.php';

$adminPage = 'seminar';
$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: /admin/seminar.php');
    exit;
}

$pdo = getDB();
$stmt = $pdo->prepare("SELECT * FROM seminars WHERE id = ?");
$stmt->execute([$id]);
$s = $stmt->fetch();

if (!$s) {
    header('Location: /admin/seminar.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Seminar | Admin Nawa Edukasi</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/assets/css/output.css">
</head>
<body class="bg-gray-50 font-sans text-gray-800 antialiased">

<div class="flex h-screen overflow-hidden">
    <?php include 'sidebar.php'; ?>

    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="h-20 bg-white border-b border-gray-100 flex items-center justify-between px-6 shrink-0">
            <div class="flex items-center gap-3">
                <button onclick="toggleSidebar()" class="md:hidden text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </button>
                <div class="flex items-center gap-2 text-sm text-gray-500">
                    <a href="/admin/seminar.php" class="hover:text-primary-600 transition-colors">Kelola Seminar</a>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <span class="font-semibold text-gray-900">Edit Seminar</span>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-[#1E1B3A] flex items-center justify-center">
                    <span class="text-sm font-bold text-white"><?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?></span>
                </div>
                <span class="text-sm font-medium text-gray-700 hidden sm:block"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-6 sm:p-8">
            <div class="max-w-2xl mx-auto">
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                    <h2 class="text-xl font-bold text-gray-900 mb-1">Edit Seminar</h2>
                    <p class="text-sm text-gray-500 mb-6 pb-4 border-b border-gray-100">Ubah data seminar di bawah ini lalu klik <strong>Update</strong>.</p>

                    <div id="alert-box" class="hidden mb-4 p-4 text-sm rounded-lg"></div>

                    <form id="form-seminar" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?php echo $s['id']; ?>">

                        <!-- Judul -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Judul Seminar <span class="text-red-500">*</span></label>
                            <input type="text" name="title" value="<?php echo htmlspecialchars($s['title']); ?>"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#1E1B3A]/20 focus:border-[#1E1B3A] outline-none transition" required>
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                            <textarea name="description" rows="4"
                                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#1E1B3A]/20 focus:border-[#1E1B3A] outline-none transition resize-none"><?php echo htmlspecialchars($s['description'] ?? ''); ?></textarea>
                        </div>

                        <!-- Tanggal & Lokasi -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pelaksanaan</label>
                                <input type="date" name="date" value="<?php echo htmlspecialchars($s['date'] ?? ''); ?>"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#1E1B3A]/20 focus:border-[#1E1B3A] outline-none transition">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
                                <input type="text" name="location" value="<?php echo htmlspecialchars($s['location'] ?? ''); ?>"
                                       placeholder="Contoh: Online (Zoom) / Aula Kampus"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#1E1B3A]/20 focus:border-[#1E1B3A] outline-none transition">
                            </div>
                        </div>

                        <!-- Harga & Link -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp)</label>
                                <input type="number" name="price" min="0"
                                       value="<?php echo htmlspecialchars($s['price'] ?? ''); ?>"
                                       placeholder="Kosongkan jika Gratis"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#1E1B3A]/20 focus:border-[#1E1B3A] outline-none transition">
                                <p class="mt-1 text-xs text-gray-400">Biarkan kosong jika seminar gratis.</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Link Pendaftaran</label>
                                <input type="url" name="link" value="<?php echo htmlspecialchars($s['link'] ?? ''); ?>"
                                       placeholder="https://..."
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#1E1B3A]/20 focus:border-[#1E1B3A] outline-none transition">
                            </div>
                        </div>

                        <!-- Cover/Poster -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Poster / Cover Seminar</label>
                            <?php if ($s['image']): ?>
                                <div class="mb-2 flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                    <img src="<?php echo htmlspecialchars($s['image']); ?>" alt="Cover saat ini" class="h-16 w-auto rounded object-contain">
                                    <div>
                                        <p class="text-xs font-medium text-gray-600">Cover saat ini</p>
                                        <p class="text-xs text-gray-400">Upload baru untuk menggantinya.</p>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <input type="file" name="image" accept="image/jpeg,image/png,image/webp,image/gif"
                                   onchange="previewImage(this)"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-[#1E1B3A]/5 file:text-[#1E1B3A] hover:file:bg-[#1E1B3A]/10 cursor-pointer">
                            <p class="mt-1 text-xs text-gray-400">Format: JPG, PNG, WebP. Maks. 2 MB. Kosongkan jika tidak diubah.</p>
                            <img id="img-preview" src="" alt="" class="mt-3 max-h-48 rounded-lg border border-gray-200 object-contain hidden">
                        </div>

                        <!-- Status Aktif -->
                        <div class="mb-6 flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div>
                                <p class="text-sm font-medium text-gray-700">Tampilkan di Website</p>
                                <p class="text-xs text-gray-400 mt-0.5">Seminar ditampilkan di halaman seminar publik.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" class="sr-only peer" <?php echo $s['is_active'] ? 'checked' : ''; ?>>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#1E1B3A]"></div>
                            </label>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center gap-3">
                            <button type="submit" id="btn-submit"
                                    class="flex-1 bg-[#1E1B3A] hover:bg-gray-800 text-white font-semibold py-2.5 px-4 rounded-lg text-sm transition-colors flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                Update Seminar
                            </button>
                            <a href="/admin/seminar.php"
                               class="px-5 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
function previewImage(input) {
    const preview = document.getElementById('img-preview');
    if (input.files && input.files[0]) {
        if (input.files[0].size > 2 * 1024 * 1024) {
            alert('Ukuran file maksimal 2 MB!');
            input.value = '';
            preview.classList.add('hidden');
            return;
        }
        const reader = new FileReader();
        reader.onload = e => { preview.src = e.target.result; preview.classList.remove('hidden'); };
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.classList.add('hidden');
    }
}

document.getElementById('form-seminar').addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn = document.getElementById('btn-submit');
    const alertBox = document.getElementById('alert-box');

    btn.disabled = true;
    btn.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Menyimpan...';

    try {
        const res = await fetch('/api/edit-seminar.php', { method: 'POST', body: new FormData(e.target) });
        const data = await res.json();

        alertBox.className = data.status === 'success'
            ? 'mb-4 p-4 text-sm rounded-lg bg-green-50 text-green-800 border border-green-200 block'
            : 'mb-4 p-4 text-sm rounded-lg bg-red-50 text-red-800 border border-red-200 block';
        alertBox.textContent = data.message;
        alertBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

        if (data.status === 'success') {
            setTimeout(() => window.location.href = '/admin/seminar.php', 1000);
        } else {
            btn.disabled = false;
            btn.innerHTML = '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> Update Seminar';
        }
    } catch {
        alertBox.className = 'mb-4 p-4 text-sm rounded-lg bg-red-50 text-red-800 border border-red-200 block';
        alertBox.textContent = 'Terjadi kesalahan jaringan.';
        btn.disabled = false;
        btn.innerHTML = '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> Update Seminar';
    }
});
</script>

</body>
</html>
