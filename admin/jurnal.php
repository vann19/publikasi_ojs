<?php
require_once '../includes/auth.php';
checkAuth();

$adminPage = 'jurnal';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambah Jurnal | Admin Nawa Edukasi</title>
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
                <h1 class="text-xl font-bold text-gray-900">Tambah Jurnal</h1>
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
            <div class="max-w-2xl mx-auto">

                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 border-b pb-2">Tambah Jurnal Ilmiah</h2>
                    <div id="jurnal-alert" class="hidden mb-4 p-4 text-sm rounded-lg"></div>
                    <form id="form-add-jurnal">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Judul Jurnal *</label>
                            <input type="text" name="title" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500" required>
                        </div>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">ISSN</label>
                                <input type="text" name="issn" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Rilis</label>
                                <input type="date" name="published_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Volume</label>
                                <input type="text" name="volume" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Issue/Nomor</label>
                                <input type="text" name="issue" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Link Jurnal (URL)</label>
                            <input type="url" name="link" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Foto Cover Jurnal</label>
                            <input type="file" name="image" accept="image/jpeg,image/png,image/webp,image/gif" onchange="previewImage(this, 'jurnal-preview')" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                            <p class="mt-1 text-xs text-gray-400">Format: JPG, PNG, WebP, GIF. Maksimal 2 MB.</p>
                            <img id="jurnal-preview" src="" alt="" class="mt-3 max-h-40 rounded-lg border border-gray-200 hidden">
                        </div>
                        <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                            Simpan Jurnal
                        </button>
                    </form>
                </div>

            </div>
        </main>
    </div>
</div>

<script>
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    if (input.files && input.files[0]) {
        const file = input.files[0];
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran foto maksimal 2 MB!');
            input.value = '';
            preview.classList.add('hidden');
            return;
        }
        const reader = new FileReader();
        reader.onload = (e) => {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    } else {
        preview.classList.add('hidden');
    }
}

document.getElementById('form-add-jurnal').addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    const alertBox = document.getElementById('jurnal-alert');

    try {
        const response = await fetch('/api/add-jurnal.php', {
            method: 'POST',
            body: formData
        });
        const data = await response.json();

        alertBox.className = data.status === 'success'
            ? 'mb-4 p-4 text-sm rounded-lg bg-green-50 text-green-800 block'
            : 'mb-4 p-4 text-sm rounded-lg bg-red-50 text-red-800 block';
        alertBox.textContent = data.message;

        if (data.status === 'success') {
            e.target.reset();
            document.getElementById('jurnal-preview').classList.add('hidden');
        }
    } catch (error) {
        alertBox.className = 'mb-4 p-4 text-sm rounded-lg bg-red-50 text-red-800 block';
        alertBox.textContent = 'Terjadi kesalahan sistem.';
    }
});
</script>

</body>
</html>
