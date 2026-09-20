<?php
require_once '../includes/auth.php';
checkAuth();
require_once '../includes/db.php';

$adminPage = 'dashboard';
$pdo = getDB();

// ── Stat Cards ──────────────────────────────────────────────────────────────
$bookCount     = (int) $pdo->query("SELECT COUNT(*) FROM books")->fetchColumn();
$journalCount  = (int) $pdo->query("SELECT COUNT(*) FROM journals")->fetchColumn();
$seminarCount  = (int) $pdo->query("SELECT COUNT(*) FROM seminars")->fetchColumn();
$commentCount  = (int) $pdo->query("SELECT COUNT(*) FROM comments WHERE is_active = 1")->fetchColumn();

$seminarActive = (int) $pdo->query("SELECT COUNT(*) FROM seminars WHERE is_active = 1")->fetchColumn();
$seminarUpcoming = (int) $pdo->query("SELECT COUNT(*) FROM seminars WHERE is_active = 1 AND date >= CURDATE()")->fetchColumn();

// ── Data Grafik: 6 bulan terakhir ───────────────────────────────────────────
// Buat array 6 bulan terakhir sebagai label
$months = [];
$monthLabels = [];
for ($i = 5; $i >= 0; $i--) {
    $ts = strtotime("-$i months");
    $months[]      = date('Y-m', $ts);
    $monthLabels[] = date('M Y', $ts); // e.g. "Apr 2026"
}

// Query count per bulan untuk masing-masing tabel
function getMonthlyData(PDO $pdo, string $table, array $months): array {
    $placeholders = implode(',', array_fill(0, count($months), '?'));
    $stmt = $pdo->prepare(
        "SELECT DATE_FORMAT(created_at, '%Y-%m') as bulan, COUNT(*) as total
         FROM $table
         WHERE DATE_FORMAT(created_at, '%Y-%m') IN ($placeholders)
         GROUP BY bulan"
    );
    $stmt->execute($months);
    $rows = $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // bulan => total
    return array_map(fn($m) => (int)($rows[$m] ?? 0), $months);
}

$bookData     = getMonthlyData($pdo, 'books',    $months);
$journalData  = getMonthlyData($pdo, 'journals', $months);
$seminarData  = getMonthlyData($pdo, 'seminars', $months);

// ── Seminar akan datang ──────────────────────────────────────────────────────
$upcomingSeminars = $pdo->query(
    "SELECT title, date, location, price FROM seminars
     WHERE is_active = 1 AND date >= CURDATE()
     ORDER BY date ASC LIMIT 5"
)->fetchAll();

// ── Buku terbaru ─────────────────────────────────────────────────────────────
$latestBooks = $pdo->query(
    "SELECT title, author, image, created_at FROM books ORDER BY created_at DESC LIMIT 5"
)->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin | Nawa Edukasi</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/assets/css/output.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
</head>
<body class="bg-gray-50 font-sans text-gray-800 antialiased">

<div class="flex h-screen overflow-hidden">
    <?php include 'sidebar.php'; ?>

    <div class="flex-1 flex flex-col overflow-hidden">

        <!-- Top Bar -->
        <header class="h-20 bg-white border-b border-gray-100 flex items-center justify-between px-6 shrink-0">
            <div class="flex items-center gap-4">
                <button onclick="toggleSidebar()" class="md:hidden text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </button>
                <div>
                    <h1 class="text-xl font-bold text-gray-900">Dashboard</h1>
                    <p class="text-xs text-gray-400 mt-0.5">Selamat datang, <?php echo htmlspecialchars($_SESSION['username']); ?> 👋</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-[#1E1B3A] flex items-center justify-center">
                    <span class="text-sm font-bold text-white"><?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?></span>
                </div>
                <span class="text-sm font-medium text-gray-700 hidden sm:block"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 overflow-y-auto p-6 sm:p-8">
            <div class="max-w-6xl mx-auto space-y-8">

                <!-- ── Stat Cards ────────────────────────────────────────── -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">

                    <!-- Buku -->
                    <a href="/admin/buku.php" class="bg-white rounded-xl border border-gray-100 p-5 hover:shadow-md transition-shadow group">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-11 h-11 rounded-lg bg-blue-50 flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.04A8.97 8.97 0 006 3.75c-1.05 0-2.06.18-3 .5v14.25A8.99 8.99 0 016 18c2.3 0 4.4.87 6 2.29m0-14.25a8.97 8.97 0 016-2.29c1.05 0 2.06.18 3 .5v14.25A8.99 8.99 0 0118 18a8.97 8.97 0 00-6 2.29m0-14.25v14.25" /></svg>
                            </div>
                            <svg class="w-4 h-4 text-gray-300 group-hover:text-blue-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </div>
                        <p class="text-3xl font-bold text-gray-900"><?php echo $bookCount; ?></p>
                        <p class="text-sm text-gray-500 mt-1">Total Buku</p>
                    </a>

                    <!-- Jurnal -->
                    <a href="/admin/jurnal.php" class="bg-white rounded-xl border border-gray-100 p-5 hover:shadow-md transition-shadow group">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-11 h-11 rounded-lg bg-green-50 flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.5L17 7.5V19a2 2 0 01-2 2z" /></svg>
                            </div>
                            <svg class="w-4 h-4 text-gray-300 group-hover:text-green-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </div>
                        <p class="text-3xl font-bold text-gray-900"><?php echo $journalCount; ?></p>
                        <p class="text-sm text-gray-500 mt-1">Total Jurnal</p>
                    </a>

                    <!-- Seminar -->
                    <a href="/admin/seminar.php" class="bg-white rounded-xl border border-gray-100 p-5 hover:shadow-md transition-shadow group">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-11 h-11 rounded-lg bg-purple-50 flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            </div>
                            <svg class="w-4 h-4 text-gray-300 group-hover:text-purple-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </div>
                        <p class="text-3xl font-bold text-gray-900"><?php echo $seminarCount; ?></p>
                        <p class="text-sm text-gray-500 mt-1">Total Seminar</p>
                        <?php if ($seminarUpcoming > 0): ?>
                            <span class="inline-block mt-2 text-xs font-semibold text-purple-700 bg-purple-50 rounded-full px-2 py-0.5"><?php echo $seminarUpcoming; ?> akan datang</span>
                        <?php endif; ?>
                    </a>

                    <!-- Komentar -->
                    <a href="/admin/komentar.php" class="bg-white rounded-xl border border-gray-100 p-5 hover:shadow-md transition-shadow group">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-11 h-11 rounded-lg bg-orange-50 flex items-center justify-center">
                                <svg class="w-5 h-5 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                            </div>
                            <svg class="w-4 h-4 text-gray-300 group-hover:text-orange-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </div>
                        <p class="text-3xl font-bold text-gray-900"><?php echo $commentCount; ?></p>
                        <p class="text-sm text-gray-500 mt-1">Komentar Aktif</p>
                    </a>
                </div>

                <!-- ── Grafik + Donat ─────────────────────────────────────── -->
                <div class="grid lg:grid-cols-3 gap-6">

                    <!-- Line Chart: 6 bulan terakhir -->
                    <div class="lg:col-span-2 bg-white rounded-xl border border-gray-100 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h2 class="text-base font-bold text-gray-900">Pertumbuhan Konten</h2>
                                <p class="text-xs text-gray-400 mt-0.5">6 bulan terakhir</p>
                            </div>
                            <div class="flex items-center gap-4 text-xs text-gray-500">
                                <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-blue-500 inline-block"></span>Buku</span>
                                <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-green-500 inline-block"></span>Jurnal</span>
                                <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-purple-500 inline-block"></span>Seminar</span>
                            </div>
                        </div>
                        <canvas id="lineChart" height="100"></canvas>
                    </div>

                    <!-- Donut Chart: Proporsi -->
                    <div class="bg-white rounded-xl border border-gray-100 p-6 flex flex-col">
                        <div class="mb-6">
                            <h2 class="text-base font-bold text-gray-900">Proporsi Konten</h2>
                            <p class="text-xs text-gray-400 mt-0.5">Total keseluruhan</p>
                        </div>
                        <div class="flex-1 flex items-center justify-center">
                            <canvas id="donutChart" height="200"></canvas>
                        </div>
                        <div class="mt-4 space-y-2">
                            <div class="flex items-center justify-between text-sm">
                                <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-blue-500 inline-block"></span>Buku</span>
                                <span class="font-semibold text-gray-700"><?php echo $bookCount; ?></span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-green-500 inline-block"></span>Jurnal</span>
                                <span class="font-semibold text-gray-700"><?php echo $journalCount; ?></span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-purple-500 inline-block"></span>Seminar</span>
                                <span class="font-semibold text-gray-700"><?php echo $seminarCount; ?></span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-orange-400 inline-block"></span>Komentar</span>
                                <span class="font-semibold text-gray-700"><?php echo $commentCount; ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ── Bar Chart: Seminar per bulan ──────────────────────── -->
                <div class="bg-white rounded-xl border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-base font-bold text-gray-900">Seminar Ditambahkan</h2>
                            <p class="text-xs text-gray-400 mt-0.5">Per bulan — 6 bulan terakhir</p>
                        </div>
                        <a href="/admin/seminar_tambah.php" class="text-xs font-semibold text-[#1E1B3A] border border-[#1E1B3A]/20 rounded-lg px-3 py-1.5 hover:bg-[#1E1B3A]/5 transition-colors">
                            + Tambah Seminar
                        </a>
                    </div>
                    <canvas id="barChart" height="70"></canvas>
                </div>

                <!-- ── Tabel Seminar Akan Datang + Buku Terbaru ──────────── -->
                <div class="grid lg:grid-cols-2 gap-6">

                    <!-- Seminar akan datang -->
                    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                            <h2 class="text-sm font-bold text-gray-900">Seminar Akan Datang</h2>
                            <a href="/admin/seminar.php" class="text-xs text-primary-600 hover:underline">Lihat semua</a>
                        </div>
                        <?php if (empty($upcomingSeminars)): ?>
                            <p class="px-6 py-8 text-sm text-gray-400 text-center">Belum ada seminar mendatang.</p>
                        <?php else: ?>
                            <ul class="divide-y divide-gray-50">
                                <?php foreach ($upcomingSeminars as $sm): ?>
                                <li class="px-6 py-4 flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-lg bg-purple-50 flex items-center justify-center shrink-0">
                                        <svg class="w-5 h-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5"/></svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate"><?php echo htmlspecialchars($sm['title']); ?></p>
                                        <p class="text-xs text-gray-400 mt-0.5">
                                            <?php echo $sm['date'] ? date('d M Y', strtotime($sm['date'])) : '-'; ?>
                                            <?php if ($sm['location']): ?> &bull; <?php echo htmlspecialchars($sm['location']); ?><?php endif; ?>
                                        </p>
                                    </div>
                                    <span class="text-xs font-semibold <?php echo $sm['price'] ? 'text-gray-700' : 'text-emerald-600'; ?> shrink-0">
                                        <?php echo $sm['price'] ? 'Rp' . number_format($sm['price'], 0, ',', '.') : 'Gratis'; ?>
                                    </span>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>

                    <!-- Buku terbaru -->
                    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                            <h2 class="text-sm font-bold text-gray-900">Buku Terbaru</h2>
                            <a href="/admin/buku.php" class="text-xs text-primary-600 hover:underline">Lihat semua</a>
                        </div>
                        <?php if (empty($latestBooks)): ?>
                            <p class="px-6 py-8 text-sm text-gray-400 text-center">Belum ada buku.</p>
                        <?php else: ?>
                            <ul class="divide-y divide-gray-50">
                                <?php foreach ($latestBooks as $bk): ?>
                                <li class="px-6 py-4 flex items-center gap-4">
                                    <div class="w-10 h-12 rounded bg-gray-100 overflow-hidden shrink-0 flex items-center justify-center">
                                        <?php if ($bk['image']): ?>
                                            <img src="<?php echo htmlspecialchars($bk['image']); ?>" alt="" class="w-full h-full object-cover">
                                        <?php else: ?>
                                            <svg class="w-5 h-5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.04A8.97 8.97 0 006 3.75c-1.05 0-2.06.18-3 .5v14.25A8.99 8.99 0 016 18c2.3 0 4.4.87 6 2.29m0-14.25a8.97 8.97 0 016-2.29c1.05 0 2.06.18 3 .5v14.25A8.99 8.99 0 0118 18a8.97 8.97 0 00-6 2.29m0-14.25v14.25"/></svg>
                                        <?php endif; ?>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate"><?php echo htmlspecialchars($bk['title']); ?></p>
                                        <p class="text-xs text-gray-400 mt-0.5"><?php echo htmlspecialchars($bk['author']); ?></p>
                                    </div>
                                    <span class="text-xs text-gray-400 shrink-0"><?php echo date('d M Y', strtotime($bk['created_at'])); ?></span>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

<script>
// ── Data dari PHP ────────────────────────────────────────────────────────────
const labels      = <?php echo json_encode($monthLabels); ?>;
const bookData    = <?php echo json_encode($bookData); ?>;
const journalData = <?php echo json_encode($journalData); ?>;
const seminarData = <?php echo json_encode($seminarData); ?>;

const totalBuku    = <?php echo $bookCount; ?>;
const totalJurnal  = <?php echo $journalCount; ?>;
const totalSeminar = <?php echo $seminarCount; ?>;
const totalKomentar = <?php echo $commentCount; ?>;

Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
Chart.defaults.color = '#6b7280';

// ── Line Chart ───────────────────────────────────────────────────────────────
new Chart(document.getElementById('lineChart'), {
    type: 'line',
    data: {
        labels,
        datasets: [
            {
                label: 'Buku',
                data: bookData,
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59,130,246,0.08)',
                tension: 0.4,
                fill: true,
                pointRadius: 4,
                pointHoverRadius: 6,
                borderWidth: 2,
            },
            {
                label: 'Jurnal',
                data: journalData,
                borderColor: '#22c55e',
                backgroundColor: 'rgba(34,197,94,0.08)',
                tension: 0.4,
                fill: true,
                pointRadius: 4,
                pointHoverRadius: 6,
                borderWidth: 2,
            },
            {
                label: 'Seminar',
                data: seminarData,
                borderColor: '#a855f7',
                backgroundColor: 'rgba(168,85,247,0.08)',
                tension: 0.4,
                fill: true,
                pointRadius: 4,
                pointHoverRadius: 6,
                borderWidth: 2,
            },
        ]
    },
    options: {
        responsive: true,
        interaction: { mode: 'index', intersect: false },
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1, precision: 0 },
                grid: { color: 'rgba(0,0,0,0.04)' },
            },
            x: { grid: { display: false } }
        }
    }
});

// ── Donut Chart ──────────────────────────────────────────────────────────────
new Chart(document.getElementById('donutChart'), {
    type: 'doughnut',
    data: {
        labels: ['Buku', 'Jurnal', 'Seminar', 'Komentar'],
        datasets: [{
            data: [totalBuku, totalJurnal, totalSeminar, totalKomentar],
            backgroundColor: ['#3b82f6', '#22c55e', '#a855f7', '#fb923c'],
            borderWidth: 0,
            hoverOffset: 6,
        }]
    },
    options: {
        responsive: true,
        cutout: '70%',
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx => ` ${ctx.label}: ${ctx.parsed}`
                }
            }
        }
    }
});

// ── Bar Chart ────────────────────────────────────────────────────────────────
new Chart(document.getElementById('barChart'), {
    type: 'bar',
    data: {
        labels,
        datasets: [{
            label: 'Seminar',
            data: seminarData,
            backgroundColor: 'rgba(168,85,247,0.8)',
            borderRadius: 6,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        interaction: { mode: 'index', intersect: false },
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1, precision: 0 },
                grid: { color: 'rgba(0,0,0,0.04)' },
            },
            x: { grid: { display: false } }
        }
    }
});
</script>

</body>
</html>
