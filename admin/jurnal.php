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

    /* Upload Cover Modal */
    .cover-modal-overlay {
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.5);
      backdrop-filter: blur(4px);
      z-index: 50;
      display: flex;
      align-items: center;
      justify-content: center;
      opacity: 0;
      visibility: hidden;
      transition: opacity 0.3s ease, visibility 0.3s ease;
    }
    .cover-modal-overlay.active {
      opacity: 1;
      visibility: visible;
    }
    .cover-modal {
      background: white;
      border-radius: 16px;
      width: 90%;
      max-width: 480px;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
      transform: scale(0.95) translateY(10px);
      transition: transform 0.3s ease;
      overflow: hidden;
    }
    .cover-modal-overlay.active .cover-modal {
      transform: scale(1) translateY(0);
    }
    .cover-dropzone {
      border: 2px dashed #d1d5db;
      border-radius: 12px;
      padding: 2rem;
      text-align: center;
      cursor: pointer;
      transition: all 0.2s ease;
      background: #f9fafb;
    }
    .cover-dropzone:hover,
    .cover-dropzone.dragover {
      border-color: #6366f1;
      background: #eef2ff;
    }
    .cover-dropzone.has-file {
      border-color: #059669;
      background: #ecfdf5;
    }
    .cover-preview-img {
      max-width: 200px;
      max-height: 260px;
      object-fit: contain;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      margin: 0 auto;
      display: block;
    }
    .upload-progress-bar {
      height: 4px;
      border-radius: 2px;
      background: #e5e7eb;
      overflow: hidden;
      display: none;
    }
    .upload-progress-bar .bar {
      height: 100%;
      width: 0%;
      background: linear-gradient(90deg, #6366f1, #8b5cf6);
      border-radius: 2px;
      transition: width 0.3s ease;
    }
    .upload-progress-bar.active {
      display: block;
    }

    /* Cover action button */
    .cover-cell {
      position: relative;
    }
    .cover-cell .cover-upload-btn {
      position: absolute;
      inset: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      background: rgba(0, 0, 0, 0.5);
      border-radius: 6px;
      opacity: 0;
      transition: opacity 0.2s ease;
      cursor: pointer;
    }
    .cover-cell:hover .cover-upload-btn {
      opacity: 1;
    }
        .cover-upload-action {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            margin-top: 0.5rem;
            padding: 0.35rem 0.5rem;
            border: 1px solid #c7d2fe;
            border-radius: 0.375rem;
            color: #4338ca;
            background: #eef2ff;
            font-size: 0.7rem;
            font-weight: 600;
            line-height: 1rem;
            white-space: nowrap;
            cursor: pointer;
            transition: background-color 0.2s ease, color 0.2s ease;
        }
        .cover-upload-action:hover {
            color: #3730a3;
            background: #e0e7ff;
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
                                    <tr class="hover:bg-gray-50 transition-colors" id="row-jurnal-<?php echo $j['id']; ?>">
                                        <td class="p-4 align-middle">
                                            <div class="cover-cell inline-block relative" style="width:64px;height:80px;">
                                                <?php if(!empty($j['image'])): ?>
                                                    <img src="<?php echo htmlspecialchars($j['image']); ?>" alt="Cover" class="w-16 h-20 object-cover rounded shadow-sm border border-gray-200" id="cover-img-<?php echo $j['id']; ?>">
                                                <?php else: ?>
                                                    <div class="w-16 h-20 bg-gray-200 rounded flex items-center justify-center text-gray-400 text-xs text-center border border-gray-300" id="cover-img-<?php echo $j['id']; ?>">No Cover</div>
                                                <?php endif; ?>
                                                <div class="cover-upload-btn" onclick="openCoverModal(<?php echo $j['id']; ?>, '<?php echo addslashes(htmlspecialchars($j['title'])); ?>')" title="Upload Cover">
                                                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                                <button type="button" class="cover-upload-action" onclick="openCoverModal(<?php echo $j['id']; ?>, '<?php echo addslashes(htmlspecialchars($j['title'])); ?>')">
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                                    </svg>
                                                    <?php echo !empty($j['image']) ? 'Ganti cover' : 'Tambah cover'; ?>
                                                </button>
                                            </div>
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

<!-- Cover Upload Modal -->
<div class="cover-modal-overlay" id="cover-modal-overlay" onclick="closeCoverModal(event)">
    <div class="cover-modal" onclick="event.stopPropagation()">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-gray-900">Upload Cover Jurnal</h3>
                <p class="text-sm text-gray-500 mt-1" id="modal-jurnal-title"></p>
            </div>
            <button onclick="closeCoverModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <div class="p-6">
            <!-- Dropzone -->
            <div class="cover-dropzone" id="cover-dropzone" onclick="document.getElementById('cover-file-input').click()">
                <div id="dropzone-content">
                    <svg class="w-10 h-10 text-gray-400 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" />
                    </svg>
                    <p class="text-sm font-semibold text-gray-700">Klik atau seret foto cover ke sini</p>
                    <p class="text-xs text-gray-400 mt-1">JPG, PNG, WebP, GIF, BMP • Maks 5 MB</p>
                    <p class="text-xs text-emerald-600 font-medium mt-2">📦 Otomatis dikonversi ke format WebP</p>
                </div>
                <div id="dropzone-preview" class="hidden">
                    <img id="cover-preview-image" class="cover-preview-img" alt="Preview">
                    <p class="text-xs text-gray-500 mt-3" id="cover-file-info"></p>
                    <button type="button" onclick="event.stopPropagation(); resetDropzone()" class="mt-2 text-xs text-red-500 hover:text-red-700 font-medium inline-flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        Ganti foto
                    </button>
                </div>
            </div>
            <input type="file" id="cover-file-input" class="hidden" accept="image/jpeg,image/png,image/webp,image/gif,image/bmp">

            <!-- Progress Bar -->
            <div class="upload-progress-bar mt-4" id="upload-progress">
                <div class="bar" id="upload-progress-bar"></div>
            </div>

            <!-- Status Message -->
            <div id="upload-status" class="hidden mt-3 text-sm rounded-lg p-3"></div>
        </div>

        <div class="p-6 border-t border-gray-100 flex justify-end gap-3">
            <button type="button" onclick="closeCoverModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">Batal</button>
            <button type="button" id="btn-upload-cover" onclick="uploadCover()" disabled class="px-5 py-2 text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 disabled:bg-gray-300 disabled:cursor-not-allowed rounded-lg transition-colors inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                Upload & Konversi WebP
            </button>
        </div>
    </div>
</div>

<script>
// ===== Cover Upload Variables =====
let currentJurnalId = null;
let selectedFile = null;

// ===== Cover Modal Functions =====
function openCoverModal(id, title) {
    currentJurnalId = id;
    document.getElementById('modal-jurnal-title').textContent = title;
    resetDropzone();
    document.getElementById('upload-status').classList.add('hidden');
    document.getElementById('cover-modal-overlay').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeCoverModal(event) {
    if (event && event.target !== document.getElementById('cover-modal-overlay')) return;
    document.getElementById('cover-modal-overlay').classList.remove('active');
    document.body.style.overflow = '';
    currentJurnalId = null;
    selectedFile = null;
}

function resetDropzone() {
    selectedFile = null;
    document.getElementById('cover-file-input').value = '';
    document.getElementById('dropzone-content').classList.remove('hidden');
    document.getElementById('dropzone-preview').classList.add('hidden');
    document.getElementById('cover-dropzone').classList.remove('has-file');
    document.getElementById('btn-upload-cover').disabled = true;
    document.getElementById('upload-progress').classList.remove('active');
    document.getElementById('upload-progress-bar').style.width = '0%';
}

function handleFileSelected(file) {
    if (!file) return;

    // Validate file type
    const allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'image/bmp'];
    if (!allowedTypes.includes(file.type)) {
        resetDropzone();
        showUploadStatus('error', 'Format file tidak didukung. Gunakan JPG, PNG, WebP, GIF, atau BMP.');
        return;
    }

    // Validate file size (5MB)
    if (file.size > 5 * 1024 * 1024) {
        resetDropzone();
        showUploadStatus('error', 'Ukuran file maksimal 5 MB.');
        return;
    }

    selectedFile = file;

    // Show preview
    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('cover-preview-image').src = e.target.result;
        document.getElementById('cover-file-info').textContent = `${file.name} (${(file.size / 1024).toFixed(1)} KB)`;
        document.getElementById('dropzone-content').classList.add('hidden');
        document.getElementById('dropzone-preview').classList.remove('hidden');
        document.getElementById('cover-dropzone').classList.add('has-file');
        document.getElementById('btn-upload-cover').disabled = false;
        document.getElementById('upload-status').classList.add('hidden');
    };
    reader.readAsDataURL(file);
}

// File input change handler
document.getElementById('cover-file-input').addEventListener('change', function(e) {
    handleFileSelected(e.target.files[0]);
});

// Drag & drop handlers
const dropzone = document.getElementById('cover-dropzone');
dropzone.addEventListener('dragover', function(e) {
    e.preventDefault();
    this.classList.add('dragover');
});
dropzone.addEventListener('dragleave', function(e) {
    e.preventDefault();
    this.classList.remove('dragover');
});
dropzone.addEventListener('drop', function(e) {
    e.preventDefault();
    this.classList.remove('dragover');
    const file = e.dataTransfer.files[0];
    if (file) handleFileSelected(file);
});

function showUploadStatus(type, message) {
    const statusEl = document.getElementById('upload-status');
    statusEl.classList.remove('hidden');
    if (type === 'success') {
        statusEl.className = 'mt-3 text-sm rounded-lg p-3 bg-emerald-50 text-emerald-700 border border-emerald-200';
    } else {
        statusEl.className = 'mt-3 text-sm rounded-lg p-3 bg-red-50 text-red-700 border border-red-200';
    }
    statusEl.textContent = message;
}

async function uploadCover() {
    if (!selectedFile || !currentJurnalId) return;

    const btn = document.getElementById('btn-upload-cover');
    const progressContainer = document.getElementById('upload-progress');
    const progressBar = document.getElementById('upload-progress-bar');

    btn.disabled = true;
    btn.innerHTML = `<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Mengupload...`;
    progressContainer.classList.add('active');

    const formData = new FormData();
    formData.append('id', currentJurnalId);
    formData.append('cover', selectedFile);

    try {
        const xhr = new XMLHttpRequest();

        // Progress tracking
        xhr.upload.addEventListener('progress', function(e) {
            if (e.lengthComputable) {
                const percent = Math.round((e.loaded / e.total) * 100);
                progressBar.style.width = percent + '%';
            }
        });

        const result = await new Promise((resolve, reject) => {
            xhr.onload = function() {
                try {
                    resolve(JSON.parse(xhr.responseText));
                } catch(e) {
                    reject(new Error('Response bukan JSON'));
                }
            };
            xhr.onerror = function() { reject(new Error('Gagal menghubungi server')); };
            xhr.open('POST', '/api/upload-cover-jurnal.php');
            xhr.send(formData);
        });

        if (result.status === 'success') {
            showUploadStatus('success', result.message);
            progressBar.style.width = '100%';

            // Update the cover image in the table row
            const coverCell = document.getElementById('cover-img-' + currentJurnalId);
            if (coverCell) {
                if (coverCell.tagName === 'IMG') {
                    coverCell.src = result.image + '?t=' + Date.now();
                } else {
                    // Replace the placeholder div with an img
                    const img = document.createElement('img');
                    img.src = result.image + '?t=' + Date.now();
                    img.alt = 'Cover';
                    img.className = 'w-16 h-20 object-cover rounded shadow-sm border border-gray-200';
                    img.id = 'cover-img-' + currentJurnalId;
                    coverCell.parentNode.replaceChild(img, coverCell);
                }
            }

            // Close modal after delay
            setTimeout(() => closeCoverModal(), 1500);
        } else {
            showUploadStatus('error', result.message);
        }
    } catch (error) {
        showUploadStatus('error', error.message || 'Terjadi kesalahan saat upload.');
    } finally {
        btn.disabled = false;
        btn.innerHTML = `<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg> Upload & Konversi WebP`;
    }
}

// ===== Existing Functions =====
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
