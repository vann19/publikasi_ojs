<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
checkAuth();

$adminPage = 'seminar';
$id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
if ($id === false) {
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

require_once '../includes/seminar_loader.php';
$t = loadSeminarFormData($pdo, $id);
$speakerRows = $t['speakerRows'];
$slidesText = $t['slidesText'];
$slideRows = $t['slideRows'];
$scheduleText = $t['scheduleText'];
$partnersText = $t['partnersText'];
$partnerRows = $t['partnerRows'];
$feesText = $t['feesText'];
$subthemesText = $t['subthemesText'];
$bankRows = $t['bankRows'];
$contentValues = $t['contentValues'];
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
                    <a href="/admin/seminar.php" class="flex items-center gap-1.5 hover:text-primary-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        Kelola Seminar
                    </a>
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
            <div class="max-w-5xl mx-auto">
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                    <h2 class="text-xl font-bold text-gray-900 mb-1">Edit Seminar</h2>
                    <p class="text-sm text-gray-500 mb-6 pb-4 border-b border-gray-100">Ubah data seminar lalu klik <strong>Update Seminar</strong>.</p>
                    <div id="alert-box" class="hidden mb-4 p-4 text-sm rounded-lg"></div>
                    <?php include 'seminar_form.php'; ?>
                </div>
            </div>
        </main>
    </div>
</div>

</body>
</html>
