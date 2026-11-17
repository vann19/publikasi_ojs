<?php
// Partial form seminar, dipakai admin/seminar_tambah.php dan admin/seminar_edit.php
// Variabel: $s (array|null), $speakerRows (array), $slidesText, $scheduleText,
//           $partnersText, $feesText, $subthemesText (string)
$isEdit = !empty($s);
$v = fn($k) => htmlspecialchars((string)($s[$k] ?? ''));
$inputCls = 'w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#1E1B3A]/20 focus:border-[#1E1B3A] outline-none transition';
$labelCls = 'block text-sm font-medium text-gray-700 mb-1';
$hintCls  = 'mt-1 text-xs text-gray-400';
$speakerRows = $speakerRows ?? [];
$contentValues = $contentValues ?? [];
$speakerCats = ['opening' => 'Opening Speech', 'keynote' => 'Keynote Speaker', 'plenary' => 'Plenary Speaker'];
$selCls = 'w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm';
?>
<form id="form-seminar" enctype="multipart/form-data">
    <?php if ($isEdit): ?>
        <input type="hidden" name="id" value="<?php echo (int)$s['id']; ?>">
    <?php endif; ?>

    <h3 class="text-sm font-bold text-gray-900 mb-3">Informasi Utama</h3>
    <div class="mb-4">
        <label class="<?php echo $labelCls; ?>">Judul Seminar <span class="text-red-500">*</span></label>
        <input type="text" name="title" value="<?php echo $v('title'); ?>" placeholder="Contoh: The International Conference on Multidisciplinary Studies (ICoMS 2026)" class="<?php echo $inputCls; ?>" required>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
        <div>
            <label class="<?php echo $labelCls; ?>">Subjudul / Kicker</label>
            <input type="text" name="subtitle" value="<?php echo $v('subtitle'); ?>" placeholder="Contoh: Conference Day" class="<?php echo $inputCls; ?>">
        </div>
        <div>
            <label class="<?php echo $labelCls; ?>">Penyelenggara</label>
            <input type="text" name="organizer" value="<?php echo $v('organizer'); ?>" class="<?php echo $inputCls; ?>">
        </div>
    </div>
    <div class="mb-4">
        <label class="<?php echo $labelCls; ?>">Tema Utama</label>
        <textarea name="theme" rows="2" class="<?php echo $inputCls; ?> resize-none"><?php echo $v('theme'); ?></textarea>
    </div>
    <div class="mb-4">
        <label class="<?php echo $labelCls; ?>">Call for Paper</label>
        <textarea name="call_for_paper" rows="3" class="<?php echo $inputCls; ?> resize-none"><?php echo $v('call_for_paper'); ?></textarea>
    </div>
    <div class="mb-4">
        <label class="<?php echo $labelCls; ?>">Deskripsi</label>
        <textarea name="description" rows="3" class="<?php echo $inputCls; ?> resize-none"><?php echo $v('description'); ?></textarea>
    </div>

    <h3 class="text-sm font-bold text-gray-900 mb-3 mt-6">Jadwal & Lokasi</h3>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
        <div>
            <label class="<?php echo $labelCls; ?>">Tanggal Mulai</label>
            <input type="date" name="date" value="<?php echo $v('date'); ?>" class="<?php echo $inputCls; ?>">
        </div>
        <div>
            <label class="<?php echo $labelCls; ?>">Tanggal Selesai</label>
            <input type="date" name="end_date" value="<?php echo $v('end_date'); ?>" class="<?php echo $inputCls; ?>">
        </div>
        <div>
            <label class="<?php echo $labelCls; ?>">Lokasi</label>
            <input type="text" name="location" value="<?php echo $v('location'); ?>" placeholder="Online (Zoom) / Aula Kampus" class="<?php echo $inputCls; ?>">
        </div>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
        <div>
            <label class="<?php echo $labelCls; ?>">Batas Deadline (countdown)</label>
            <input type="date" name="deadline_date" value="<?php echo $v('deadline_date'); ?>" class="<?php echo $inputCls; ?>">
        </div>
        <div>
            <label class="<?php echo $labelCls; ?>">Label Deadline</label>
            <input type="text" name="deadline_label" value="<?php echo $v('deadline_label'); ?>" placeholder="Contoh: Extended Abstract Submission Deadline" class="<?php echo $inputCls; ?>">
        </div>
    </div>

    <h3 class="text-sm font-bold text-gray-900 mb-3 mt-6">Slider Banner</h3>
    <p class="<?php echo $hintCls; ?> mb-3">Upload gambar untuk setiap slide. Isi judul & caption opsional. Maks. 5 MB per gambar.</p>
    <div id="slides-list" class="space-y-3">
        <?php foreach ($slideRows as $i => $sl): ?>
        <div class="slide-row border border-gray-200 rounded-xl p-4 bg-gray-50/60">
            <div class="flex items-start gap-4">
                <div class="shrink-0 w-24">
                    <div class="w-24 h-16 rounded-lg overflow-hidden bg-gray-200 border border-gray-300 flex items-center justify-center">
                        <?php if (!empty($sl['image'])): ?>
                            <img src="<?php echo htmlspecialchars($sl['image']); ?>" alt="" class="w-full h-full object-cover slide-preview">
                        <?php else: ?>
                            <img src="" alt="" class="w-full h-full object-cover slide-preview hidden">
                        <?php endif; ?>
                    </div>
                </div>
                <div class="flex-1 space-y-2">
                    <input type="text" name="slides[<?php echo $i; ?>][title]" value="<?php echo htmlspecialchars($sl['title'] ?? ''); ?>" placeholder="Judul slide" class="<?php echo $inputCls; ?>">
                    <input type="text" name="slides[<?php echo $i; ?>][caption]" value="<?php echo htmlspecialchars($sl['caption'] ?? ''); ?>" placeholder="Caption singkat" class="<?php echo $inputCls; ?>">
                    <div class="flex items-center justify-between gap-3">
                        <input type="file" name="slide_photo[<?php echo $i; ?>]" accept="image/jpeg,image/png,image/webp,image/gif" class="slide-file text-xs">
                        <button type="button" class="remove-slide text-xs font-semibold text-red-600 hover:text-red-800">Hapus</button>
                    </div>
                </div>
            </div>
            <input type="hidden" name="slides[<?php echo $i; ?>][image]" value="<?php echo htmlspecialchars($sl['image'] ?? ''); ?>">
        </div>
        <?php endforeach; ?>
    </div>
    <button type="button" id="add-slide" class="mt-3 text-sm font-semibold text-[#1E1B3A] border border-dashed border-gray-400 rounded-lg px-4 py-2 hover:bg-gray-50">+ Tambah Slide</button>

    <template id="slide-tpl">
        <div class="slide-row border border-gray-200 rounded-xl p-4 bg-gray-50/60">
            <div class="flex items-start gap-4">
                <div class="shrink-0 w-24">
                    <div class="w-24 h-16 rounded-lg overflow-hidden bg-gray-200 border border-gray-300 flex items-center justify-center">
                        <img src="" alt="" class="w-full h-full object-cover slide-preview hidden">
                    </div>
                </div>
                <div class="flex-1 space-y-2">
                    <input type="text" name="slides[__IDX__][title]" placeholder="Judul slide" class="<?php echo $inputCls; ?>">
                    <input type="text" name="slides[__IDX__][caption]" placeholder="Caption singkat" class="<?php echo $inputCls; ?>">
                    <div class="flex items-center justify-between gap-3">
                        <input type="file" name="slide_photo[__IDX__]" accept="image/jpeg,image/png,image/webp,image/gif" class="slide-file text-xs">
                        <button type="button" class="remove-slide text-xs font-semibold text-red-600 hover:text-red-800">Hapus</button>
                    </div>
                </div>
            </div>
            <input type="hidden" name="slides[__IDX__][image]" value="">
        </div>
    </template>

    <h3 class="text-sm font-bold text-gray-900 mb-3 mt-6">Pembicara</h3>
    <div id="speakers-list" class="space-y-3">
        <?php foreach ($speakerRows as $i => $sp): ?>
        <div class="speaker-row border border-gray-200 rounded-xl p-4 bg-gray-50/60">
            <div class="flex items-start gap-4">
                <div class="shrink-0 w-20">
                    <div class="w-20 h-20 rounded-full overflow-hidden bg-gray-200 border border-gray-300 flex items-center justify-center">
                        <?php if (!empty($sp['image'])): ?>
                            <img src="<?php echo htmlspecialchars($sp['image']); ?>" alt="" class="w-full h-full object-cover photo-preview">
                        <?php else: ?>
                            <img src="" alt="" class="w-full h-full object-cover photo-preview hidden">
                        <?php endif; ?>
                    </div>
                    <input type="hidden" name="speakers[<?php echo $i; ?>][image]" value="<?php echo htmlspecialchars($sp['image'] ?? ''); ?>">
                </div>
                <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <select name="speakers[<?php echo $i; ?>][category]" class="<?php echo $selCls; ?>">
                        <?php foreach ($speakerCats as $k => $lbl): ?>
                            <option value="<?php echo $k; ?>" <?php echo ($sp['category'] === $k) ? 'selected' : ''; ?>><?php echo $lbl; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="text" name="speakers[<?php echo $i; ?>][name]" value="<?php echo htmlspecialchars($sp['name']); ?>" placeholder="Nama lengkap & gelar" class="<?php echo $inputCls; ?>">
                    <input type="text" name="speakers[<?php echo $i; ?>][title]" value="<?php echo htmlspecialchars($sp['title'] ?? ''); ?>" placeholder="Jabatan" class="<?php echo $inputCls; ?>">
                    <input type="text" name="speakers[<?php echo $i; ?>][affiliation]" value="<?php echo htmlspecialchars($sp['affiliation'] ?? ''); ?>" placeholder="Afiliasi / institusi" class="<?php echo $inputCls; ?>">
                    <div class="sm:col-span-2 flex items-center justify-between gap-3">
                        <input type="file" name="speaker_photo[<?php echo $i; ?>]" accept="image/jpeg,image/png,image/webp,image/gif" class="speaker-file text-xs">
                        <button type="button" class="remove-speaker text-xs font-semibold text-red-600 hover:text-red-800">Hapus</button>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <button type="button" id="add-speaker" class="mt-3 text-sm font-semibold text-[#1E1B3A] border border-dashed border-gray-400 rounded-lg px-4 py-2 hover:bg-gray-50">+ Tambah Pembicara</button>
    <p class="<?php echo $hintCls; ?>">Foto akan dipotong persegi dan otomatis disimpan sebagai WebP. Maks. 2 MB per foto. Kategori: Opening Speech, Keynote, atau Plenary.</p>

    <template id="speaker-tpl">
        <div class="speaker-row border border-gray-200 rounded-xl p-4 bg-gray-50/60">
            <div class="flex items-start gap-4">
                <div class="shrink-0 w-20">
                    <div class="w-20 h-20 rounded-full overflow-hidden bg-gray-200 border border-gray-300 flex items-center justify-center">
                        <img src="" alt="" class="w-full h-full object-cover photo-preview hidden">
                    </div>
                    <input type="hidden" name="speakers[__IDX__][image]" value="">
                </div>
                <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <select name="speakers[__IDX__][category]" class="<?php echo $selCls; ?>">
                        <?php foreach ($speakerCats as $k => $lbl): ?><option value="<?php echo $k; ?>"><?php echo $lbl; ?></option><?php endforeach; ?>
                    </select>
                    <input type="text" name="speakers[__IDX__][name]" placeholder="Nama lengkap & gelar" class="<?php echo $inputCls; ?>">
                    <input type="text" name="speakers[__IDX__][title]" placeholder="Jabatan" class="<?php echo $inputCls; ?>">
                    <input type="text" name="speakers[__IDX__][affiliation]" placeholder="Afiliasi / institusi" class="<?php echo $inputCls; ?>">
                    <div class="sm:col-span-2 flex items-center justify-between gap-3">
                        <input type="file" name="speaker_photo[__IDX__]" accept="image/jpeg,image/png,image/webp,image/gif" class="speaker-file text-xs">
                        <button type="button" class="remove-speaker text-xs font-semibold text-red-600 hover:text-red-800">Hapus</button>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <h3 class="text-sm font-bold text-gray-900 mb-3 mt-6">Timeline (Jadwal Penting)</h3>
    <div class="mb-4">
        <textarea name="schedule" rows="4" class="<?php echo $inputCls; ?> resize-none font-mono text-xs" placeholder="Abstract Submission | 15–30 Oktober 2026 | Deskripsi"><?php echo htmlspecialchars($scheduleText); ?></textarea>
        <p class="<?php echo $hintCls; ?>">Format: <code>label | tanggal | deskripsi</code>.</p>
    </div>

    <h3 class="text-sm font-bold text-gray-900 mb-3 mt-6">Subtema (untuk Abstrak)</h3>
    <div class="mb-4">
        <textarea name="subthemes" rows="4" class="<?php echo $inputCls; ?> resize-none" placeholder="Satu subtema per baris"><?php echo htmlspecialchars($subthemesText); ?></textarea>
        <p class="<?php echo $hintCls; ?>">Peserta memilih subtema ini saat mengirim abstrak.</p>
    </div>

    <h3 class="text-sm font-bold text-gray-900 mb-3 mt-6">Biaya Registrasi</h3>
    <div class="mb-4">
        <textarea name="fees" rows="6" class="<?php echo $inputCls; ?> resize-none font-mono text-xs" placeholder="Students | Online Participant | 25000 | Wajib kartu mahasiswa&#10;International | Presenter | CLOSED"><?php echo htmlspecialchars($feesText); ?></textarea>
        <p class="<?php echo $hintCls; ?>">Format: <code>kategori | item | nominal atau CLOSED | catatan</code>. Nominal angka saja (tanpa Rp).</p>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
        <div>
            <label class="<?php echo $labelCls; ?>">Harga Dasar (Rp)</label>
            <input type="number" name="price" min="0" value="<?php echo $v('price'); ?>" class="<?php echo $inputCls; ?>">
        </div>
        <div>
            <label class="<?php echo $labelCls; ?>">Catatan Pembayaran</label>
            <textarea name="fee_info" rows="2" class="<?php echo $inputCls; ?> resize-none"><?php echo $v('fee_info'); ?></textarea>
        </div>
    </div>

    <h3 class="text-sm font-bold text-gray-900 mb-3 mt-6">Rekening Transfer Bank</h3>
    <p class="<?php echo $hintCls; ?> mb-3">Tambahkan rekening untuk pembayaran registrasi. Tampil di halaman publik seminar.</p>
    <div id="banks-list" class="space-y-3">
        <?php foreach ($bankRows as $i => $bk): ?>
        <div class="bank-row border border-gray-200 rounded-xl p-4 bg-gray-50/60">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <input type="text" name="bank_transfers[<?php echo $i; ?>][bank_name]" value="<?php echo htmlspecialchars($bk['bank_name']); ?>" placeholder="Nama Bank (cth: BCA)" class="<?php echo $inputCls; ?>">
                <input type="text" name="bank_transfers[<?php echo $i; ?>][account_number]" value="<?php echo htmlspecialchars($bk['account_number']); ?>" placeholder="No. Rekening" class="<?php echo $inputCls; ?>">
                <div class="flex items-center gap-3">
                    <input type="text" name="bank_transfers[<?php echo $i; ?>][account_name]" value="<?php echo htmlspecialchars($bk['account_name']); ?>" placeholder="Atas Nama" class="<?php echo $inputCls; ?> flex-1">
                    <button type="button" class="remove-bank text-xs font-semibold text-red-600 hover:text-red-800 whitespace-nowrap">Hapus</button>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <button type="button" id="add-bank" class="mt-3 text-sm font-semibold text-[#1E1B3A] border border-dashed border-gray-400 rounded-lg px-4 py-2 hover:bg-gray-50">+ Tambah Rekening</button>

    <template id="bank-tpl">
        <div class="bank-row border border-gray-200 rounded-xl p-4 bg-gray-50/60">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <input type="text" name="bank_transfers[__IDX__][bank_name]" placeholder="Nama Bank (cth: BCA)" class="<?php echo $inputCls; ?>">
                <input type="text" name="bank_transfers[__IDX__][account_number]" placeholder="No. Rekening" class="<?php echo $inputCls; ?>">
                <div class="flex items-center gap-3">
                    <input type="text" name="bank_transfers[__IDX__][account_name]" placeholder="Atas Nama" class="<?php echo $inputCls; ?> flex-1">
                    <button type="button" class="remove-bank text-xs font-semibold text-red-600 hover:text-red-800 whitespace-nowrap">Hapus</button>
                </div>
            </div>
        </div>
    </template>

    <h3 class="text-sm font-bold text-gray-900 mb-3 mt-6">Tautan</h3>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
        <div>
            <label class="<?php echo $labelCls; ?>">Link Pendaftaran</label>
            <input type="url" name="link" value="<?php echo $v('link'); ?>" placeholder="https://..." class="<?php echo $inputCls; ?>">
        </div>
        <div>
            <label class="<?php echo $labelCls; ?>">Link Submission Abstrak</label>
            <input type="url" name="submission_link" value="<?php echo $v('submission_link'); ?>" placeholder="https://..." class="<?php echo $inputCls; ?>">
        </div>
        <div>
            <label class="<?php echo $labelCls; ?>">Link Booklet</label>
            <input type="url" name="booklet_link" value="<?php echo $v('booklet_link'); ?>" placeholder="https://..." class="<?php echo $inputCls; ?>">
        </div>
    </div>

    <h3 class="text-sm font-bold text-gray-900 mb-3 mt-6">Partner / Co-Host</h3>
    <p class="<?php echo $hintCls; ?> mb-3">Upload logo untuk setiap partner. Pilih role (Host/Cohost), isi nama & website. Logo cohost tampil berjalan di halaman publik.</p>
    <div id="partners-list" class="space-y-3">
        <?php foreach ($partnerRows as $i => $pt): ?>
        <div class="partner-row border border-gray-200 rounded-xl p-4 bg-gray-50/60">
            <div class="flex items-start gap-4">
                <div class="shrink-0 w-20">
                    <div class="w-20 h-20 rounded-lg overflow-hidden bg-gray-200 border border-gray-300 flex items-center justify-center">
                        <?php if (!empty($pt['logo'])): ?>
                            <img src="<?php echo htmlspecialchars($pt['logo']); ?>" alt="" class="w-full h-full object-contain partner-preview">
                        <?php else: ?>
                            <img src="" alt="" class="w-full h-full object-contain partner-preview hidden">
                        <?php endif; ?>
                    </div>
                </div>
                <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <select name="partners[<?php echo $i; ?>][role]" class="<?php echo $selCls; ?>">
                        <option value="cohost" <?php echo ($pt['role'] === 'cohost') ? 'selected' : ''; ?>>Co-Host</option>
                        <option value="host" <?php echo ($pt['role'] === 'host') ? 'selected' : ''; ?>>Host</option>
                    </select>
                    <input type="text" name="partners[<?php echo $i; ?>][name]" value="<?php echo htmlspecialchars($pt['name']); ?>" placeholder="Nama institusi" class="<?php echo $inputCls; ?>">
                    <input type="text" name="partners[<?php echo $i; ?>][website]" value="<?php echo htmlspecialchars($pt['website'] ?? ''); ?>" placeholder="https://..." class="<?php echo $inputCls; ?>">
                    <div class="sm:col-span-2 flex items-center justify-between gap-3">
                        <input type="file" name="partner_logo[<?php echo $i; ?>]" accept="image/jpeg,image/png,image/webp,image/gif" class="partner-file text-xs">
                        <button type="button" class="remove-partner text-xs font-semibold text-red-600 hover:text-red-800">Hapus</button>
                    </div>
                </div>
            </div>
            <input type="hidden" name="partners[<?php echo $i; ?>][logo]" value="<?php echo htmlspecialchars($pt['logo'] ?? ''); ?>">
        </div>
        <?php endforeach; ?>
    </div>
    <button type="button" id="add-partner" class="mt-3 text-sm font-semibold text-[#1E1B3A] border border-dashed border-gray-400 rounded-lg px-4 py-2 hover:bg-gray-50">+ Tambah Partner</button>

    <template id="partner-tpl">
        <div class="partner-row border border-gray-200 rounded-xl p-4 bg-gray-50/60">
            <div class="flex items-start gap-4">
                <div class="shrink-0 w-20">
                    <div class="w-20 h-20 rounded-lg overflow-hidden bg-gray-200 border border-gray-300 flex items-center justify-center">
                        <img src="" alt="" class="w-full h-full object-contain partner-preview hidden">
                    </div>
                </div>
                <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <select name="partners[__IDX__][role]" class="<?php echo $selCls; ?>">
                        <option value="cohost">Co-Host</option>
                        <option value="host">Host</option>
                    </select>
                    <input type="text" name="partners[__IDX__][name]" placeholder="Nama institusi" class="<?php echo $inputCls; ?>">
                    <input type="text" name="partners[__IDX__][website]" placeholder="https://..." class="<?php echo $inputCls; ?>">
                    <div class="sm:col-span-2 flex items-center justify-between gap-3">
                        <input type="file" name="partner_logo[__IDX__]" accept="image/jpeg,image/png,image/webp,image/gif" class="partner-file text-xs">
                        <button type="button" class="remove-partner text-xs font-semibold text-red-600 hover:text-red-800">Hapus</button>
                    </div>
                </div>
            </div>
            <input type="hidden" name="partners[__IDX__][logo]" value="">
        </div>
    </template>

    <h3 class="text-sm font-bold text-gray-900 mb-2 mt-8">Teks Halaman Publik (CMS)</h3>
    <p class="<?php echo $hintCls; ?> mb-4">Atur judul, keterangan, countdown, dan label setiap section. Kolom yang dikosongkan akan memakai teks bawaan.</p>
    <div class="space-y-4 rounded-xl border border-purple-100 bg-purple-50/40 p-4 sm:p-5">
        <?php
        $contentGroups = [
            'Hero & Countdown' => ['hero_badge', 'hero_description', 'deadlines', 'countdown_eyebrow'],
            'Tema' => ['theme_eyebrow', 'theme_booklet_text'],
            'Pembicara' => ['speakers_eyebrow', 'speakers_opening_title', 'speakers_opening_desc', 'speakers_keynote_title', 'speakers_keynote_desc', 'speakers_plenary_title', 'speakers_plenary_desc'],
            'Timeline' => ['timeline_eyebrow', 'timeline_title', 'timeline_desc'],
            'Biaya & Pembayaran' => ['fees_eyebrow', 'fees_title', 'fees_desc', 'fee_notes', 'payment_title', 'payment_desc'],
            'Jurnal' => ['journals_eyebrow', 'journals_title', 'journals_desc'],
            'Registrasi' => ['register_eyebrow', 'register_title', 'register_desc'],
            'Partner' => ['partners_eyebrow', 'partners_title'],
        ];
        foreach ($contentGroups as $groupLabel => $keys):
        ?>
        <details class="rounded-xl border border-gray-200 bg-white p-4" <?php echo $groupLabel === 'Hero & Countdown' ? 'open' : ''; ?>>
            <summary class="cursor-pointer text-sm font-bold text-gray-900"><?php echo htmlspecialchars($groupLabel); ?></summary>
            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <?php foreach ($keys as $key):
                    $cfg = SEMINAR_CONTENT_FIELDS[$key];
                    $value = $contentValues[$key] ?? '';
                    $isTextarea = ($cfg['type'] ?? 'text') === 'textarea';
                ?>
                <div class="<?php echo $isTextarea ? 'sm:col-span-2' : ''; ?>">
                    <label class="<?php echo $labelCls; ?>" for="content-<?php echo $key; ?>"><?php echo htmlspecialchars($cfg['label']); ?></label>
                    <?php if ($isTextarea): ?>
                        <textarea id="content-<?php echo $key; ?>" name="content[<?php echo $key; ?>]" rows="3" placeholder="<?php echo htmlspecialchars($cfg['default'] ?? ''); ?>" class="<?php echo $inputCls; ?> resize-y"><?php echo htmlspecialchars($value); ?></textarea>
                    <?php else: ?>
                        <input id="content-<?php echo $key; ?>" type="text" name="content[<?php echo $key; ?>]" value="<?php echo htmlspecialchars($value); ?>" placeholder="<?php echo htmlspecialchars($cfg['default'] ?? ''); ?>" class="<?php echo $inputCls; ?>">
                    <?php endif; ?>
                    <?php if (!empty($cfg['hint'])): ?><p class="<?php echo $hintCls; ?>"><?php echo htmlspecialchars($cfg['hint']); ?></p><?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </details>
        <?php endforeach; ?>
    </div>

    <h3 class="text-sm font-bold text-gray-900 mb-3 mt-6">Poster / Cover Utama</h3>
    <div class="mb-4">
        <?php if ($isEdit && !empty($s['image'])): ?>
            <div class="mb-2 flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                <img src="<?php echo $v('image'); ?>" alt="Cover saat ini" class="h-16 w-auto rounded object-contain">
                <div>
                    <p class="text-xs font-medium text-gray-600">Cover saat ini</p>
                    <p class="text-xs text-gray-400">Upload baru untuk menggantinya.</p>
                </div>
            </div>
        <?php endif; ?>
        <input type="file" name="image" accept="image/jpeg,image/png,image/webp,image/gif" onchange="previewImage(this)"
               class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-[#1E1B3A]/5 file:text-[#1E1B3A] hover:file:bg-[#1E1B3A]/10 cursor-pointer">
        <p class="<?php echo $hintCls; ?>">Format: JPG, PNG, WebP, GIF. Maks. 2 MB.<?php echo $isEdit ? ' Kosongkan jika tidak diubah.' : ''; ?></p>
        <img id="img-preview" src="" alt="" class="mt-3 max-h-48 rounded-lg border border-gray-200 object-contain hidden">
    </div>

    <div class="mb-6 flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
        <div>
            <p class="text-sm font-medium text-gray-700">Tampilkan di Website</p>
            <p class="text-xs text-gray-400 mt-0.5">Seminar ditampilkan di halaman seminar publik.</p>
        </div>
        <label class="relative inline-flex items-center cursor-pointer">
            <input type="checkbox" name="is_active" value="1" class="sr-only peer" <?php echo (!$isEdit || !empty($s['is_active'])) ? 'checked' : ''; ?>>
            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#1E1B3A]"></div>
        </label>
    </div>

    <div class="flex items-center gap-3">
        <button type="submit" id="btn-submit" class="flex-1 bg-[#1E1B3A] hover:bg-gray-800 text-white font-semibold py-2.5 px-4 rounded-lg text-sm transition-colors">
            <?php echo $isEdit ? 'Update Seminar' : 'Simpan Seminar'; ?>
        </button>
        <a href="/admin/seminar.php" class="px-5 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">Batal</a>
    </div>
</form>

<script>
let speakerIdx = <?php echo count($speakerRows); ?>;
const speakersList = document.getElementById('speakers-list');
const speakerTpl = document.getElementById('speaker-tpl');

document.getElementById('add-speaker').addEventListener('click', () => {
    const html = speakerTpl.innerHTML.replaceAll('__IDX__', speakerIdx++);
    speakersList.insertAdjacentHTML('beforeend', html);
});

speakersList.addEventListener('click', (e) => {
    if (e.target.classList.contains('remove-speaker')) {
        e.target.closest('.speaker-row').remove();
    }
});

// Preview foto pembicara sebelum upload
speakersList.addEventListener('change', (e) => {
    if (!e.target.classList.contains('speaker-file')) return;
    const file = e.target.files[0];
    const img = e.target.closest('.speaker-row').querySelector('.photo-preview');
    if (!file) return;
    if (file.size > 2 * 1024 * 1024) {
        alert('Foto maksimal 2 MB.');
        e.target.value = '';
        return;
    }
    const reader = new FileReader();
    reader.onload = ev => { img.src = ev.target.result; img.classList.remove('hidden'); };
    reader.readAsDataURL(file);
});

// === SLIDER BANNER ===
let slideIdx = <?php echo count($slideRows); ?>;
const slidesList = document.getElementById('slides-list');
const slideTpl = document.getElementById('slide-tpl');

document.getElementById('add-slide').addEventListener('click', () => {
    const html = slideTpl.innerHTML.replaceAll('__IDX__', slideIdx++);
    slidesList.insertAdjacentHTML('beforeend', html);
});

slidesList.addEventListener('click', (e) => {
    if (e.target.classList.contains('remove-slide')) {
        e.target.closest('.slide-row').remove();
    }
});

slidesList.addEventListener('change', (e) => {
    if (!e.target.classList.contains('slide-file')) return;
    const file = e.target.files[0];
    const img = e.target.closest('.slide-row').querySelector('.slide-preview');
    if (!file) return;
    if (file.size > 5 * 1024 * 1024) {
        alert('Gambar slide maksimal 5 MB.');
        e.target.value = '';
        return;
    }
    const reader = new FileReader();
    reader.onload = ev => { img.src = ev.target.result; img.classList.remove('hidden'); };
    reader.readAsDataURL(file);
});

// === PARTNER ===
let partnerIdx = <?php echo count($partnerRows); ?>;
const partnersList = document.getElementById('partners-list');
const partnerTpl = document.getElementById('partner-tpl');

document.getElementById('add-partner').addEventListener('click', () => {
    const html = partnerTpl.innerHTML.replaceAll('__IDX__', partnerIdx++);
    partnersList.insertAdjacentHTML('beforeend', html);
});

partnersList.addEventListener('click', (e) => {
    if (e.target.classList.contains('remove-partner')) {
        e.target.closest('.partner-row').remove();
    }
});

partnersList.addEventListener('change', (e) => {
    if (!e.target.classList.contains('partner-file')) return;
    const file = e.target.files[0];
    const img = e.target.closest('.partner-row').querySelector('.partner-preview');
    if (!file) return;
    if (file.size > 2 * 1024 * 1024) {
        alert('Logo maksimal 2 MB.');
        e.target.value = '';
        return;
    }
    const reader = new FileReader();
    reader.onload = ev => { img.src = ev.target.result; img.classList.remove('hidden'); };
    reader.readAsDataURL(file);
});

// === BANK TRANSFER ===
let bankIdx = <?php echo count($bankRows); ?>;
const banksList = document.getElementById('banks-list');
const bankTpl = document.getElementById('bank-tpl');

document.getElementById('add-bank').addEventListener('click', () => {
    const html = bankTpl.innerHTML.replaceAll('__IDX__', bankIdx++);
    banksList.insertAdjacentHTML('beforeend', html);
});

banksList.addEventListener('click', (e) => {
    if (e.target.classList.contains('remove-bank')) {
        e.target.closest('.bank-row').remove();
    }
});

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
    const label = btn.textContent.trim();
    const endpoint = <?php echo $isEdit ? "'/api/edit-seminar.php'" : "'/api/add-seminar.php'"; ?>;

    btn.disabled = true;
    btn.textContent = 'Menyimpan...';
    try {
        const res = await fetch(endpoint, { method: 'POST', body: new FormData(e.target) });
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
            btn.textContent = label;
        }
    } catch {
        alertBox.className = 'mb-4 p-4 text-sm rounded-lg bg-red-50 text-red-800 border border-red-200 block';
        alertBox.textContent = 'Terjadi kesalahan jaringan.';
        btn.disabled = false;
        btn.textContent = label;
    }
});
</script>
