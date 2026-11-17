<?php
// Halaman publik: cek apakah email sudah terdaftar dan status pembayaran.
require_once 'includes/db.php';

$pdo = getDB();
$seminarId = filter_var($_GET['seminar'] ?? $_POST['seminar_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$email = trim($_POST['email'] ?? '');
$result = null;
$error = '';
$searched = $_SERVER['REQUEST_METHOD'] === 'POST';

if ($searched) {
    if ($seminarId === false) {
        $error = 'Seminar tidak valid.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Masukkan alamat email yang valid.';
    } else {
        $stmt = $pdo->prepare("SELECT r.full_name, r.paper_title, r.subtheme, r.status, r.is_paid, r.created_at,
                                      s.title AS seminar_title, f.category AS fee_category, f.item AS fee_item
                               FROM seminar_registrations r
                               JOIN seminars s ON s.id = r.seminar_id
                               LEFT JOIN seminar_fees f ON f.id = r.fee_id
                               WHERE r.seminar_id = ? AND r.email = ?");
        $stmt->execute([$seminarId, $email]);
        $result = $stmt->fetch() ?: false;
    }
}

$esc = fn($v) => htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
$statusLabel = ['pending' => ['Menunggu Verifikasi', 'bg-amber-100 text-amber-800'],
                'confirmed' => ['Terkonfirmasi', 'bg-emerald-100 text-emerald-800'],
                'rejected' => ['Ditolak', 'bg-red-100 text-red-700']];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cek Status Pendaftaran Seminar | Nawa Edukasi</title>
  <meta name="robots" content="noindex">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen flex items-start justify-center px-4 py-16 text-slate-800">
  <div class="w-full max-w-lg bg-white border border-slate-200 rounded-3xl p-8 shadow-lg">
    <h1 class="text-2xl font-extrabold text-slate-900">Cek Status Pendaftaran</h1>
    <p class="text-sm text-slate-500 mt-1 mb-6">Masukkan email yang Anda gunakan saat mendaftar.</p>

    <form method="post" class="space-y-4">
      <input type="hidden" name="seminar_id" value="<?php echo $seminarId ? (int)$seminarId : ''; ?>">
      <div>
        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Email</label>
        <input type="email" name="email" required maxlength="255" value="<?php echo $esc($email); ?>" class="w-full text-sm rounded-lg border-slate-200 py-2.5 px-3 bg-slate-50/50">
      </div>
      <button type="submit" class="w-full py-3 rounded-xl bg-emerald-800 hover:bg-emerald-900 text-white font-semibold text-sm">Cek Status</button>
    </form>

    <div class="mt-6">
      <?php if ($error): ?>
        <div class="p-4 text-sm rounded-lg bg-red-50 text-red-800 border border-red-200"><?php echo $esc($error); ?></div>
      <?php elseif ($searched && $result === false): ?>
        <div class="p-4 text-sm rounded-lg bg-slate-100 text-slate-700 border border-slate-200">
          Email tersebut <strong>belum terdaftar</strong> pada seminar ini. Silakan daftar terlebih dahulu di halaman seminar.
        </div>
      <?php elseif (is_array($result)): ?>
        <?php [$label, $cls] = $statusLabel[$result['status']] ?? [$result['status'], 'bg-slate-100 text-slate-700']; ?>
        <div class="p-5 rounded-xl border border-emerald-200 bg-emerald-50/50 space-y-3 text-sm">
          <p class="font-semibold text-emerald-900">Anda sudah terdaftar.</p>
          <dl class="grid grid-cols-3 gap-y-2 text-sm">
            <dt class="text-slate-500">Nama</dt><dd class="col-span-2 font-medium"><?php echo $esc($result['full_name']); ?></dd>
            <dt class="text-slate-500">Seminar</dt><dd class="col-span-2"><?php echo $esc($result['seminar_title']); ?></dd>
            <dt class="text-slate-500">Kategori</dt><dd class="col-span-2"><?php echo $esc(trim(($result['fee_category'] ?? '') . ' ' . ($result['fee_item'] ?? '')) ?: '-'); ?></dd>
            <dt class="text-slate-500">Subtema</dt><dd class="col-span-2"><?php echo $esc($result['subtheme'] ?: '-'); ?></dd>
            <dt class="text-slate-500">Status</dt><dd class="col-span-2"><span class="px-2 py-0.5 rounded text-xs font-semibold <?php echo $cls; ?>"><?php echo $esc($label); ?></span></dd>
            <dt class="text-slate-500">Pembayaran</dt><dd class="col-span-2">
              <?php if ($result['is_paid']): ?>
                <span class="px-2 py-0.5 rounded text-xs font-bold bg-emerald-600 text-white">LUNAS</span>
              <?php else: ?>
                <span class="px-2 py-0.5 rounded text-xs font-semibold bg-amber-100 text-amber-800">Belum Lunas</span>
              <?php endif; ?>
            </dd>
          </dl>
        </div>
      <?php endif; ?>
    </div>

    <p class="mt-8 text-center"><a href="/seminar.php" class="text-xs font-semibold text-emerald-800 hover:underline">&larr; Kembali ke halaman seminar</a></p>
  </div>
</body>
</html>
