<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
checkAuth();

$pdo = getDB();
$seminarId = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
if ($seminarId === false) {
    header('Location: /admin/seminar.php');
    exit;
}
$stmt = $pdo->prepare("SELECT id, title FROM seminars WHERE id = ?");
$stmt->execute([$seminarId]);
$seminar = $stmt->fetch();
if (!$seminar) {
    header('Location: /admin/seminar.php');
    exit;
}

$stmt = $pdo->prepare("SELECT r.*, f.category AS fee_category, f.item AS fee_item, f.amount AS fee_amount
                       FROM seminar_registrations r
                       LEFT JOIN seminar_fees f ON f.id = r.fee_id
                       WHERE r.seminar_id = ? ORDER BY r.created_at DESC, r.id DESC");
$stmt->execute([$seminarId]);
$regs = $stmt->fetchAll();

$esc = fn($v) => htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
$adminPage = 'seminar';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftar Seminar - Admin Nawa Edukasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden text-gray-800">
    <?php include 'sidebar.php'; ?>

    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        <header class="bg-white border-b border-gray-200 px-8 py-5 flex items-center justify-between shrink-0">
            <div>
                <a href="seminar.php" class="text-sm text-gray-500 hover:text-gray-800">&larr; Kelola Seminar</a>
                <h1 class="text-2xl font-bold text-gray-900 mt-1">Pendaftar Seminar</h1>
                <p class="text-sm text-gray-500 mt-1"><?php echo $esc($seminar['title']); ?></p>
            </div>
            <span class="text-sm text-gray-500"><?php echo count($regs); ?> pendaftar</span>
        </header>

        <div class="flex-1 overflow-auto p-8">
            <div id="alert" class="hidden mb-4 p-4 text-sm rounded-lg"></div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-gray-50 text-gray-600 font-semibold border-b border-gray-200">
                        <tr>
                            <th class="px-5 py-4">Tanggal</th>
                            <th class="px-5 py-4">Nama</th>
                            <th class="px-5 py-4">Email</th>
                            <th class="px-5 py-4">Kategori</th>
                            <th class="px-5 py-4">Subtema / Judul</th>
                            <th class="px-5 py-4">Berkas</th>
                            <th class="px-5 py-4">Status</th>
                            <th class="px-5 py-4 text-center">Lunas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if (!$regs): ?>
                        <tr><td colspan="8" class="px-6 py-8 text-center text-gray-500">Belum ada pendaftar.</td></tr>
                        <?php endif; ?>
                        <?php foreach ($regs as $r): ?>
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-5 py-4 text-gray-600"><?php echo $r['created_at'] ? date('d M Y H:i', strtotime($r['created_at'])) : '-'; ?></td>
                            <td class="px-5 py-4 font-medium text-gray-900"><?php echo $esc($r['full_name']); ?><div class="text-xs text-gray-500 font-normal"><?php echo $esc($r['affiliation']); ?></div></td>
                            <td class="px-5 py-4 text-gray-600"><?php echo $esc($r['email']); ?></td>
                            <td class="px-5 py-4 text-gray-600"><?php echo $esc(trim(($r['fee_category'] ?? '') . ' - ' . ($r['fee_item'] ?? ''), ' -')) ?: '-'; ?></td>
                            <td class="px-5 py-4 text-gray-600 whitespace-normal max-w-xs"><?php echo $esc($r['subtheme'] ?: '-'); ?><div class="text-xs text-gray-400"><?php echo $esc($r['paper_title']); ?></div></td>
                            <td class="px-5 py-4 text-xs space-x-2">
                                <?php if ($r['abstract_file']): ?><a class="text-emerald-700 hover:underline" href="/api/download-seminar-file.php?id=<?php echo (int)$r['id']; ?>&type=abstract">Abstrak</a><?php endif; ?>
                                <?php if ($r['receipt_file']): ?><a class="text-emerald-700 hover:underline" href="/api/download-seminar-file.php?id=<?php echo (int)$r['id']; ?>&type=receipt">Bukti Bayar</a><?php endif; ?>
                                <?php if (!$r['abstract_file'] && !$r['receipt_file']): ?><span class="text-gray-400">-</span><?php endif; ?>
                            </td>
                            <td class="px-5 py-4">
                                <select onchange="updateRegistration(<?php echo (int)$r['id']; ?>, { status: this.value })" class="text-xs rounded-lg border-gray-300 py-1.5 pl-2 pr-7">
                                    <?php foreach (['pending' => 'Menunggu', 'confirmed' => 'Terkonfirmasi', 'rejected' => 'Ditolak'] as $k => $lbl): ?>
                                        <option value="<?php echo $k; ?>" <?php echo $r['status'] === $k ? 'selected' : ''; ?>><?php echo $lbl; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-emerald-700" <?php echo $r['is_paid'] ? 'checked' : ''; ?> onchange="updateRegistration(<?php echo (int)$r['id']; ?>, { is_paid: this.checked ? 1 : 0 }, this)">
                                    <span class="text-xs font-semibold <?php echo $r['is_paid'] ? 'text-emerald-700' : 'text-gray-400'; ?>"><?php echo $r['is_paid'] ? 'Lunas' : 'Belum'; ?></span>
                                </label>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script>
    async function updateRegistration(id, changes, el) {
        const alertBox = document.getElementById('alert');
        try {
            const res = await fetch('/api/update-seminar-registration.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id, ...changes })
            });
            const data = await res.json();
            alertBox.className = 'mb-4 p-4 text-sm rounded-lg ' + (data.status === 'success' ? 'bg-green-50 text-green-800 border border-green-200' : 'bg-red-50 text-red-800 border border-red-200');
            alertBox.textContent = data.message;
            alertBox.classList.remove('hidden');
            if (data.status === 'success' && el) setTimeout(() => location.reload(), 600);
            if (data.status !== 'success') location.reload();
        } catch {
            alert('Kesalahan jaringan.');
            location.reload();
        }
    }
    </script>
</body>
</html>
