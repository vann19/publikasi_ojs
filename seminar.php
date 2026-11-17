<?php
$activePage = 'seminar';
$seoTitle = 'Seminar & Konferensi Ilmiah | Nawa Edukasi';
$seoDesc  = 'Ikuti seminar, webinar, dan konferensi ilmiah yang diselenggarakan oleh PT Nawa Edukasi Nusantara. Jadwal seminar terbaru untuk peneliti dan akademisi Indonesia.';
$seoUrl   = 'https://nawaedukasi.org/seminar.php';
include 'includes/header.php';
require_once 'includes/db.php';
require_once 'includes/seminar_helper.php';

$pdo = getDB();
$featured = $pdo->query("
    SELECT * FROM seminars
    WHERE is_active = 1
    ORDER BY (date >= CURDATE()) DESC, date ASC, created_at DESC
    LIMIT 1
")->fetch();

$slides = $speakers = $schedule = $partners = $fees = $subthemes = $contentMap = $bankTransfers = [];
if ($featured) {
    $fid = (int) $featured['id'];

    $q = $pdo->prepare("SELECT * FROM seminar_slides WHERE seminar_id = ? ORDER BY sort_order, id");
    $q->execute([$fid]);
    $slides = $q->fetchAll();

    $q = $pdo->prepare("SELECT * FROM seminar_speakers WHERE seminar_id = ? ORDER BY sort_order, id");
    $q->execute([$fid]);
    foreach ($q->fetchAll() as $sp) $speakers[$sp['category']][] = $sp;

    $q = $pdo->prepare("SELECT * FROM seminar_schedules WHERE seminar_id = ? ORDER BY sort_order, id");
    $q->execute([$fid]);
    $schedule = $q->fetchAll();

    $q = $pdo->prepare("SELECT * FROM seminar_partners WHERE seminar_id = ? ORDER BY role = 'host' DESC, sort_order, id");
    $q->execute([$fid]);
    $partners = $q->fetchAll();

    $q = $pdo->prepare("SELECT * FROM seminar_fees WHERE seminar_id = ? ORDER BY sort_order, id");
    $q->execute([$fid]);
    foreach ($q->fetchAll() as $f) $fees[$f['category']][] = $f;

    $q = $pdo->prepare("SELECT name FROM seminar_subthemes WHERE seminar_id = ? ORDER BY sort_order, id");
    $q->execute([$fid]);
    $subthemes = array_column($q->fetchAll(), 'name');

    $q = $pdo->prepare("SELECT * FROM seminar_bank_transfers WHERE seminar_id = ? ORDER BY sort_order, id");
    $q->execute([$fid]);
    $bankTransfers = $q->fetchAll();

    $q = $pdo->prepare("SELECT content_key, content_value FROM seminar_content WHERE seminar_id = ?");
    $q->execute([$fid]);
    foreach ($q->fetchAll() as $row) $contentMap[$row['content_key']] = (string) $row['content_value'];
}

$journals = $pdo->query("SELECT * FROM journals WHERE is_active = 1 ORDER BY id ASC")->fetchAll();
$others = $pdo->prepare("SELECT id, title, date, location, image, price FROM seminars WHERE is_active = 1 AND id <> ? ORDER BY (date >= CURDATE()) DESC, date ASC, created_at DESC LIMIT 6");
$others->execute([$featured['id'] ?? 0]);
$otherList = $others->fetchAll();

$c = fn(string $key) => seminarContentValue($contentMap, $key);
$e = fn($s) => htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
$lines = fn(string $text) => array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $text)), fn($line) => $line !== ''));
$money = fn($n) => 'Rp' . number_format((int) $n, 0, ',', '.');
$fmt = fn($d) => $d ? date('d M Y', strtotime($d)) : '-';

$speakerGroups = [
    'opening' => ['title' => $c('speakers_opening_title'), 'desc' => $c('speakers_opening_desc'), 'cols' => 'max-w-sm'],
    'keynote' => ['title' => $c('speakers_keynote_title'), 'desc' => $c('speakers_keynote_desc'), 'cols' => 'sm:grid-cols-2 max-w-2xl'],
    'plenary' => ['title' => $c('speakers_plenary_title'), 'desc' => $c('speakers_plenary_desc'), 'cols' => 'sm:grid-cols-2 lg:grid-cols-3'],
];

$feeNotes = [];
foreach ($lines($c('fee_notes')) as $line) {
    [$cat, $note] = array_pad(array_map('trim', explode('|', $line, 2)), 2, '');
    if ($cat !== '' && $note !== '') $feeNotes[$cat] = $note;
}

$tz = new DateTimeZone('Asia/Makassar');
$countdownTargets = [];
$cdDefault = 0;
if ($featured) {
    foreach ($lines($c('deadlines')) as $line) {
        [$label, $when] = array_pad(array_map('trim', explode('|', $line, 2)), 2, '');
        if ($label === '' || $when === '') continue;
        try {
            $dt = new DateTime($when, $tz);
        } catch (Exception $ex) {
            continue;
        }
        $countdownTargets[] = ['label' => $label, 'iso' => $dt->format(DATE_ATOM), 'text' => $dt->format('j F Y, H:i') . ' WITA'];
    }
    if (!$countdownTargets && !empty($featured['deadline_date'])) {
        $dt = new DateTime($featured['deadline_date'] . ' 23:59:59', $tz);
        $countdownTargets[] = ['label' => $featured['deadline_label'] ?: 'Batas Pendaftaran', 'iso' => $dt->format(DATE_ATOM), 'text' => $dt->format('j F Y') . ' 23:59 WITA'];
    }
    if (!empty($featured['date'])) {
        $dt = new DateTime($featured['date'] . ' 08:00:00', $tz);
        $countdownTargets[] = ['label' => 'Conference Day', 'iso' => $dt->format(DATE_ATOM), 'text' => $dt->format('j F Y') . ' 08:00 WITA'];
    }
    foreach ($countdownTargets as $i => $target) {
        $cdDefault = $i;
        if ((new DateTime($target['iso']))->getTimestamp() > time()) break;
    }
}

$rangeText = '';
if ($featured && $featured['date']) {
    $rangeText = date('d F Y', strtotime($featured['date']));
    if (!empty($featured['end_date']) && $featured['end_date'] !== $featured['date']) {
        $rangeText = date('d', strtotime($featured['date'])) . ' – ' . date('d F Y', strtotime($featured['end_date']));
    }
}
$heroBadge = $featured ? $c('hero_badge') : '';
if ($featured && $heroBadge === '' && $rangeText) {
    $heroBadge = ($featured['subtitle'] ?: 'Tanggal Pelaksanaan') . ': ' . strtoupper($rangeText);
}
$paymentText = $featured ? ($c('payment_desc') ?: ($featured['fee_info'] ?? '')) : '';
$hostPartners = array_values(array_filter($partners, fn($p) => $p['role'] === 'host'));
$coHosts = array_values(array_filter($partners, fn($p) => $p['role'] === 'cohost'));
$feeBars = ['bg-primary-600', 'bg-amber-500', 'bg-slate-700'];
$feeNoteClasses = ['bg-primary-50 text-primary-700', 'bg-amber-100 text-amber-800', 'bg-slate-100 text-slate-600'];
$jBars = ['bg-primary-600', 'bg-amber-500', 'bg-slate-700', 'bg-teal-700'];
?>

<style>
  @keyframes partner-scroll { from { transform: translateX(0); } to { transform: translateX(-50%); } }
  .partner-track { animation: partner-scroll 30s linear infinite; }
  .partner-marquee:hover .partner-track { animation-play-state: paused; }
  @media (prefers-reduced-motion: reduce) { .partner-track { animation: none; } }
</style>

<?php if (!$featured): ?>
  <section class="max-w-4xl mx-auto px-4 py-24 text-center">
    <h1 class="text-3xl font-extrabold text-gray-900 mb-3">Seminar & Konferensi</h1>
    <p class="text-gray-500">Belum ada seminar yang dijadwalkan.</p>
  </section>
<?php else: ?>

  <!-- HERO -->
  <section class="relative overflow-hidden bg-gradient-to-br from-white to-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 lg:py-20 grid lg:grid-cols-12 gap-12 items-center">
      <div class="lg:col-span-6 space-y-6">
        <?php if ($heroBadge): ?>
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-amber-100 border border-amber-200 text-amber-900 text-xs font-bold uppercase tracking-wider">
          <svg class="w-4 h-4 text-amber-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
          <span><?php echo $e(strtoupper($heroBadge)); ?></span>
        </div>
        <?php endif; ?>

        <h1 class="text-4xl sm:text-5xl font-extrabold text-gray-900 tracking-tight leading-[1.08]"><?php echo $e($featured['title']); ?></h1>

        <?php $heroDesc = $c('hero_description'); ?>
        <?php if ($heroDesc || $featured['organizer']): ?>
        <p class="text-gray-600 text-sm sm:text-base leading-relaxed">
          <?php echo $heroDesc ? nl2br($e($heroDesc)) : 'Diselenggarakan oleh <strong class="text-gray-900 font-semibold">' . $e($featured['organizer']) . '</strong>'; ?>
        </p>
        <?php endif; ?>

        <?php if ($hostPartners): ?>
        <div class="pt-2">
          <span class="block text-[11px] font-bold text-gray-500 tracking-widest uppercase mb-3">Hosted &amp; Co-Organized by</span>
          <div class="flex flex-wrap items-center gap-3">
            <?php foreach ($hostPartners as $hp): ?>
              <div class="h-16 px-3 bg-white border border-gray-200 rounded-xl flex items-center justify-center shadow-sm" title="<?php echo $e($hp['name']); ?>">
                <img src="<?php echo $e($hp['logo']); ?>" alt="<?php echo $e($hp['name']); ?>" class="h-10 w-auto object-contain">
              </div>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>

        <div class="pt-3 flex flex-wrap items-center gap-3">
          <a href="#register" class="inline-flex items-center px-5 py-2.5 rounded-lg bg-gradient-to-br from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-700 text-white font-semibold text-sm shadow-lg shadow-primary-600/30 transition">Submit Abstract →</a>
          <a href="#register" class="inline-flex items-center px-5 py-2.5 rounded-lg bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 font-semibold text-sm shadow-sm transition">Online Registration &amp; Submission</a>
        </div>
      </div>

      <div class="lg:col-span-6 relative">
        <?php if ($slides): ?>
        <div id="hero-slider" class="relative aspect-video bg-white rounded-2xl border-4 border-white shadow-2xl overflow-hidden">
          <?php foreach ($slides as $i => $slide): ?>
            <div class="hero-slide absolute inset-0 <?php echo $i === 0 ? '' : 'hidden'; ?>" data-index="<?php echo $i; ?>">
              <img src="<?php echo $e($slide['image']); ?>" alt="<?php echo $e($slide['title'] ?? ''); ?>" class="w-full h-full object-cover">
              <?php if ($slide['title'] || $slide['caption']): ?>
              <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-transparent to-transparent flex flex-col justify-end p-6 text-white pointer-events-none">
                <?php if ($slide['title']): ?><h3 class="text-xl sm:text-2xl font-bold mb-1"><?php echo $e($slide['title']); ?></h3><?php endif; ?>
                <?php if ($slide['caption']): ?><p class="text-xs sm:text-sm text-slate-200 font-medium"><?php echo $e($slide['caption']); ?></p><?php endif; ?>
              </div>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
          <?php if (count($slides) > 1): ?>
          <button type="button" id="slide-prev" aria-label="Sebelumnya" class="absolute left-2 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-black/45 hover:bg-black/60 text-white flex items-center justify-center">&#8249;</button>
          <button type="button" id="slide-next" aria-label="Berikutnya" class="absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-black/45 hover:bg-black/60 text-white flex items-center justify-center">&#8250;</button>
          <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex items-center gap-1.5" id="slide-dots">
            <?php foreach ($slides as $i => $_): ?><button type="button" data-dot="<?php echo $i; ?>" aria-label="Slide <?php echo $i + 1; ?>" class="w-2.5 h-2.5 rounded-full <?php echo $i === 0 ? 'bg-amber-500' : 'bg-white/70'; ?>"></button><?php endforeach; ?>
          </div>
          <?php endif; ?>
        </div>
        <?php elseif ($featured['image']): ?>
        <div class="rounded-2xl overflow-hidden border-4 border-white shadow-2xl bg-white">
          <img src="<?php echo $e($featured['image']); ?>" alt="Poster <?php echo $e($featured['title']); ?>" class="w-full h-auto object-cover">
        </div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- COUNTDOWN -->
  <?php if ($countdownTargets): ?>
  <section class="bg-primary-700 text-white py-12 px-4" id="countdown">
    <div class="max-w-4xl mx-auto text-center space-y-4">
      <span class="text-[11px] font-bold tracking-widest text-amber-400 uppercase"><?php echo $e($c('countdown_eyebrow')); ?></span>
      <h2 id="cdTitle" class="text-2xl sm:text-4xl font-extrabold tracking-tight"><?php echo $e($countdownTargets[$cdDefault]['label']); ?></h2>
      <p id="cdTarget" class="text-xs text-primary-100"></p>
      <div class="grid grid-cols-4 gap-3 sm:gap-6 max-w-xl mx-auto pt-2">
        <?php foreach (['DAYS', 'HOURS', 'MINUTES', 'SECONDS'] as $label): ?>
        <div class="bg-black/25 border border-white/10 rounded-xl py-4 px-2 text-center">
          <span class="block text-3xl sm:text-4xl font-extrabold text-amber-400 tabular-nums" data-unit>00</span>
          <span class="block text-[11px] sm:text-xs font-semibold text-primary-200 mt-1"><?php echo $label; ?></span>
        </div>
        <?php endforeach; ?>
      </div>
      <?php if (count($countdownTargets) > 1): ?><div id="cdChips" class="flex flex-wrap justify-center gap-2 pt-2"></div><?php endif; ?>
    </div>
  </section>
  <?php endif; ?>

  <!-- MAIN THEME -->
  <?php if ($featured['theme'] || $featured['call_for_paper']): ?>
  <section class="py-20 px-4" id="theme">
    <div class="max-w-4xl mx-auto">
      <div class="bg-white border border-slate-200 rounded-3xl p-8 sm:p-14 text-center shadow-sm space-y-6">
        <?php if ($featured['theme']): ?>
        <span class="inline-flex px-4 py-1 rounded-full bg-primary-50 border border-primary-200 text-primary-700 text-[10px] font-bold uppercase tracking-widest"><?php echo $e($c('theme_eyebrow')); ?></span>
        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight uppercase max-w-3xl mx-auto"><?php echo nl2br($e($featured['theme'])); ?></h2>
        <?php endif; ?>
        <?php if ($featured['call_for_paper']): ?><p class="text-slate-600 text-sm sm:text-base max-w-xl mx-auto whitespace-pre-line"><?php echo $e($featured['call_for_paper']); ?></p><?php endif; ?>
        <?php if ($featured['submission_link'] || $featured['booklet_link']): ?>
        <div class="pt-4 flex flex-col items-center justify-center gap-3">
          <?php if ($featured['submission_link']): ?><a href="<?php echo $e($featured['submission_link']); ?>" target="_blank" rel="noopener" class="w-full sm:w-auto inline-flex justify-center px-6 py-3 rounded-lg bg-primary-600 hover:bg-primary-700 text-white font-semibold text-sm transition">Lihat Call for Paper &amp; Subthemes →</a><?php endif; ?>
          <?php if ($featured['submission_link'] && $featured['booklet_link']): ?><span class="text-xs font-medium text-slate-400">atau</span><?php endif; ?>
          <?php if ($featured['booklet_link']): ?><a href="<?php echo $e($featured['booklet_link']); ?>" target="_blank" rel="noopener" class="w-full sm:w-auto inline-flex justify-center px-6 py-3 rounded-lg bg-primary-600 hover:bg-primary-700 text-white font-semibold text-sm transition"><?php echo $e($c('theme_booklet_text')); ?> →</a><?php endif; ?>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <?php if ($featured['description']): ?>
  <section class="max-w-4xl mx-auto px-4 pb-12 text-center"><p class="text-slate-600 leading-relaxed whitespace-pre-line"><?php echo $e($featured['description']); ?></p></section>
  <?php endif; ?>

  <!-- SPEAKERS -->
  <?php if ($speakers): ?>
  <section class="py-16 px-4 bg-slate-50" id="speakers">
    <div class="max-w-6xl mx-auto space-y-14">
      <div class="text-center"><span class="text-[11px] font-bold uppercase tracking-widest text-primary-600"><?php echo $e($c('speakers_eyebrow')); ?></span></div>
      <?php foreach ($speakerGroups as $category => $group): ?>
        <?php if (!empty($speakers[$category])): ?>
        <div class="space-y-8">
          <div class="text-center space-y-2">
            <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight"><?php echo $e($group['title']); ?></h3>
            <?php if ($group['desc']): ?><p class="text-slate-500 text-sm max-w-2xl mx-auto"><?php echo $e($group['desc']); ?></p><?php endif; ?>
          </div>
          <div class="grid grid-cols-1 gap-6 mx-auto w-full <?php echo $group['cols']; ?>">
            <?php foreach ($speakers[$category] as $speaker): ?>
            <div class="bg-white border border-slate-200 rounded-2xl p-6 text-center shadow-sm hover:border-primary-200 transition flex flex-col">
              <div class="w-28 h-28 mx-auto mb-4 rounded-full overflow-hidden border-4 border-primary-100 bg-slate-100 flex items-center justify-center">
                <?php if ($speaker['image']): ?><img src="<?php echo $e($speaker['image']); ?>" alt="<?php echo $e($speaker['name']); ?>" class="w-full h-full object-cover object-top"><?php else: ?><span class="text-2xl font-bold text-slate-400"><?php echo $e(mb_strtoupper(mb_substr($speaker['name'], 0, 1))); ?></span><?php endif; ?>
              </div>
              <h4 class="font-bold text-slate-900 text-base"><?php echo $e($speaker['name']); ?></h4>
              <?php if ($speaker['title']): ?><p class="text-xs font-semibold text-primary-700 mt-1"><?php echo $e($speaker['title']); ?></p><?php endif; ?>
              <?php if ($speaker['affiliation']): ?><p class="text-xs text-slate-500 mt-1"><?php echo $e($speaker['affiliation']); ?></p><?php endif; ?>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
  </section>
  <?php endif; ?>

  <!-- TIMELINE -->
  <?php if ($schedule): ?>
  <section class="py-16 px-4" id="timeline">
    <div class="max-w-5xl mx-auto">
      <div class="text-center space-y-1">
        <span class="text-[11px] font-bold uppercase tracking-widest text-amber-600"><?php echo $e($c('timeline_eyebrow')); ?></span>
        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900"><?php echo $e($c('timeline_title')); ?></h2>
        <?php if ($c('timeline_desc')): ?><p class="text-slate-500 text-sm"><?php echo $e($c('timeline_desc')); ?></p><?php endif; ?>
      </div>
      <div class="max-w-xl mx-auto border-l-2 border-slate-200 pl-7 space-y-4 mt-10">
        <?php foreach ($schedule as $i => $item): ?>
        <div class="relative rounded-xl bg-white border border-slate-200 p-4 shadow-sm">
          <span class="absolute -left-[37px] top-5 size-3 rounded-full ring-2 ring-white <?php echo $i % 2 ? 'bg-amber-500' : 'bg-primary-600'; ?>"></span>
          <?php if ($item['schedule_date']): ?><span class="rounded-md px-2 py-0.5 text-[11px] font-bold <?php echo $i % 2 ? 'bg-amber-100 text-amber-800' : 'bg-primary-50 text-primary-700'; ?>"><?php echo $e($item['schedule_date']); ?></span><?php endif; ?>
          <h4 class="mt-1.5 font-bold text-slate-900"><?php echo $e($item['label']); ?></h4>
          <?php if ($item['description']): ?><p class="text-[13px] text-slate-500"><?php echo $e($item['description']); ?></p><?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- FEES + PAYMENT -->
  <?php if ($fees || $bankTransfers || $paymentText): ?>
  <section class="py-16 px-4 bg-slate-50 border-t border-slate-200/60" id="fees">
    <div class="max-w-5xl mx-auto">
      <div class="text-center space-y-1">
        <span class="text-[11px] font-bold uppercase tracking-widest text-primary-600"><?php echo $e($c('fees_eyebrow')); ?></span>
        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900"><?php echo $e($c('fees_title')); ?></h2>
        <?php if ($c('fees_desc')): ?><p class="text-slate-500 text-sm"><?php echo $e($c('fees_desc')); ?></p><?php endif; ?>
      </div>

      <?php if ($fees): ?>
      <div class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-4">
        <?php $feeIndex = 0; foreach ($fees as $category => $items): $bar = $feeBars[$feeIndex % 3]; $noteClass = $feeNoteClasses[$feeIndex % 3]; $feeIndex++; ?>
        <div class="flex flex-col overflow-hidden rounded-2xl bg-white border border-slate-200 shadow-sm">
          <div class="h-1.5 <?php echo $bar; ?>"></div>
          <div class="flex flex-1 flex-col p-5">
            <h3 class="mb-3 text-lg font-bold text-slate-900"><?php echo $e($category); ?></h3>
            <div class="flex-1 text-[13px]">
              <?php foreach ($items as $fee): ?>
              <div class="flex justify-between items-start gap-3 py-2 border-b border-slate-100">
                <div><span class="text-slate-600"><?php echo $e($fee['item']); ?></span><?php if ($fee['note']): ?><p class="text-[11px] text-slate-400 italic"><?php echo $e($fee['note']); ?></p><?php endif; ?></div>
                <?php if ($fee['is_closed']): ?><span class="px-2 py-0.5 rounded bg-rose-100 text-rose-700 font-bold text-[10px] whitespace-nowrap">Registration Closed</span><?php else: ?><b class="text-primary-700 whitespace-nowrap"><?php echo $fee['amount'] !== null ? $money($fee['amount']) : '-'; ?></b><?php endif; ?>
              </div>
              <?php endforeach; ?>
            </div>
            <?php if (!empty($feeNotes[$category])): ?><div class="mt-auto pt-4"><p class="rounded-lg p-2.5 text-center text-[11px] font-semibold <?php echo $noteClass; ?>"><?php echo $e($feeNotes[$category]); ?></p></div><?php endif; ?>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <?php if ($bankTransfers || $paymentText): ?>
      <div class="mx-auto mt-8 max-w-lg rounded-2xl border border-primary-200 bg-primary-50 p-7 text-center">
        <svg class="w-8 h-8 mx-auto text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
        <h4 class="font-bold text-slate-900 mt-2"><?php echo $e($c('payment_title')); ?></h4>
        <?php if ($paymentText): ?><p class="mt-2 text-sm text-slate-600 leading-relaxed whitespace-pre-line"><?php echo $e($paymentText); ?></p><?php endif; ?>
        <?php if ($bankTransfers): ?>
        <div class="mt-4 space-y-2 text-left">
          <?php foreach ($bankTransfers as $bank): ?>
          <div class="flex items-center justify-between gap-3 rounded-xl border border-primary-200 bg-white px-4 py-3">
            <div class="min-w-0">
              <span class="block text-xs font-bold text-slate-900"><?php echo $e($bank['bank_name']); ?></span>
              <span class="block text-base font-mono font-bold text-primary-700 mt-0.5"><?php echo $e($bank['account_number']); ?></span>
              <?php if ($bank['account_name']): ?><span class="block text-xs text-slate-500 mt-0.5">a.n. <?php echo $e($bank['account_name']); ?></span><?php endif; ?>
            </div>
            <button type="button" data-copy="<?php echo $e($bank['account_number']); ?>" class="copy-account shrink-0 text-xs font-semibold text-primary-700 hover:text-primary-700 border border-primary-200 rounded-lg px-3 py-1.5 hover:bg-primary-50 transition">Salin</button>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
        <p class="mt-4 text-[11px] italic text-slate-500">*Simpan bukti transfer dan unggah pada formulir registrasi di bawah.</p>
      </div>
      <?php endif; ?>
    </div>
  </section>
  <?php endif; ?>

  <!-- JOURNALS -->
  <?php if ($journals): ?>
  <section class="py-16 px-4" id="publications">
    <div class="max-w-5xl mx-auto">
      <div class="text-center space-y-1 mb-10">
        <span class="text-[11px] font-bold uppercase tracking-widest text-primary-600"><?php echo $e($c('journals_eyebrow')); ?></span>
        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900"><?php echo $e($c('journals_title')); ?></h2>
        <?php if ($c('journals_desc')): ?><p class="text-slate-500 text-sm"><?php echo $e($c('journals_desc')); ?></p><?php endif; ?>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
        <?php foreach ($journals as $i => $journal): ?>
        <div class="flex gap-3.5 rounded-xl bg-white border border-slate-200 p-3.5 hover:border-primary-200 transition">
          <div class="grid h-[68px] w-[52px] shrink-0 place-items-center rounded-lg border border-slate-200 bg-slate-50 overflow-hidden">
            <?php if (!empty($journal['image'])): ?><img src="<?php echo $e($journal['image']); ?>" alt="<?php echo $e($journal['title']); ?>" class="w-full h-full object-contain"><?php else: ?><span class="text-[11px] font-bold text-white w-full h-full grid place-items-center <?php echo $jBars[$i % 4]; ?>"><?php echo $e(mb_strtoupper(mb_substr($journal['title'], 0, 1))); ?></span><?php endif; ?>
          </div>
          <div class="flex-1 min-w-0">
            <h3 class="font-bold text-slate-900 text-sm leading-snug"><?php echo $e($journal['title']); ?></h3>
            <?php if ($journal['description']): ?><p class="text-xs text-slate-500 mt-1 line-clamp-2"><?php echo $e($journal['description']); ?></p><?php endif; ?>
            <?php if ($journal['link']): ?><a href="<?php echo $e($journal['link']); ?>" target="_blank" rel="noopener" class="inline-block mt-2 text-xs font-bold text-primary-700 hover:text-primary-700">Kunjungi Jurnal ↗</a><?php endif; ?>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- REGISTRATION -->
  <section class="py-20 px-4 bg-slate-50" id="register">
    <div class="max-w-2xl mx-auto">
      <div class="bg-white border border-slate-200 rounded-3xl p-8 sm:p-10 shadow-xl">
        <div class="text-center space-y-2 mb-8">
          <span class="inline-flex px-3.5 py-1 rounded-full bg-primary-50 border border-primary-200 text-primary-700 text-[10px] font-bold uppercase tracking-widest"><?php echo $e($c('register_eyebrow')); ?></span>
          <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight"><?php echo $e($c('register_title')); ?></h2>
          <?php if ($c('register_desc')): ?><p class="text-slate-500 text-sm"><?php echo $e($c('register_desc')); ?></p><?php endif; ?>
        </div>

        <div class="text-center mb-5">
          <span class="block text-xs text-slate-500 mb-1">Sudah mengirim pendaftaran?</span>
          <a href="/cek-pendaftaran.php?seminar=<?php echo (int) $featured['id']; ?>" class="text-xs font-semibold text-primary-700 hover:text-primary-700 underline underline-offset-4">Cek Status Pendaftaran</a>
        </div>
        <div id="reg-alert" class="hidden mb-5 p-4 text-sm rounded-lg"></div>

        <form id="form-register" class="space-y-5">
          <input type="hidden" name="seminar_id" value="<?php echo (int) $featured['id']; ?>">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div><label class="block text-xs font-semibold text-slate-700 mb-1.5">Full Name &amp; Title <span class="text-red-500">*</span></label><input name="full_name" type="text" required maxlength="255" placeholder="e.g. Dr. John Doe" class="w-full text-sm rounded-lg border-slate-200 focus:border-primary-600 focus:ring-primary-600 py-2.5 px-3 bg-slate-50/50"></div>
            <div><label class="block text-xs font-semibold text-slate-700 mb-1.5">Email Address <span class="text-red-500">*</span></label><input name="email" type="email" required maxlength="255" placeholder="john@university.edu" class="w-full text-sm rounded-lg border-slate-200 focus:border-primary-600 focus:ring-primary-600 py-2.5 px-3 bg-slate-50/50"></div>
          </div>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div><label class="block text-xs font-semibold text-slate-700 mb-1.5">Affiliation / Institution</label><input name="affiliation" type="text" maxlength="255" placeholder="Universitas Islam Negeri Palopo" class="w-full text-sm rounded-lg border-slate-200 focus:border-primary-600 focus:ring-primary-600 py-2.5 px-3 bg-slate-50/50"></div>
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1.5">Registration Category</label>
              <select name="fee_id" class="w-full text-sm rounded-lg border-slate-200 focus:border-primary-600 focus:ring-primary-600 py-2.5 px-3 bg-slate-50/50 text-slate-600">
                <option value="">-- Select Category --</option>
                <?php foreach ($fees as $category => $items): foreach ($items as $fee): ?>
                <option value="<?php echo (int) $fee['id']; ?>" <?php echo $fee['is_closed'] ? 'disabled' : ''; ?>><?php echo $e($category . ' - ' . $fee['item']); ?><?php echo $fee['is_closed'] ? ' (Ditutup)' : ($fee['amount'] !== null ? ' (' . $money($fee['amount']) . ')' : ''); ?></option>
                <?php endforeach; endforeach; ?>
              </select>
            </div>
          </div>
          <div><label class="block text-xs font-semibold text-slate-700 mb-1.5">Conference Subtheme (for presenters)</label><select name="subtheme" class="w-full text-sm rounded-lg border-slate-200 focus:border-primary-600 focus:ring-primary-600 py-2.5 px-3 bg-slate-50/50 text-slate-600"><option value="">-- Select Subtheme --</option><?php foreach ($subthemes as $subtheme): ?><option value="<?php echo $e($subtheme); ?>"><?php echo $e($subtheme); ?></option><?php endforeach; ?></select></div>
          <div><label class="block text-xs font-semibold text-slate-700 mb-1.5">Paper Title (for presenters)</label><input name="paper_title" type="text" maxlength="255" class="w-full text-sm rounded-lg border-slate-200 focus:border-primary-600 focus:ring-primary-600 py-2.5 px-3 bg-slate-50/50"></div>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div><label class="block text-xs font-semibold text-slate-700 mb-1.5">Upload Abstract <span class="font-normal text-slate-400">(.doc / .docx, max. 5 MB)</span></label><input name="abstract" type="file" accept=".doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-3 file:rounded-md file:border-0 file:bg-primary-50 file:text-primary-700"></div>
            <div><label class="block text-xs font-semibold text-slate-700 mb-1.5">Upload Payment Receipt <span class="font-normal text-slate-400">(PDF/JPG/PNG, max. 5 MB)</span></label><input name="receipt" type="file" accept=".pdf,image/jpeg,image/png,image/webp" class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-3 file:rounded-md file:border-0 file:bg-primary-50 file:text-primary-700"></div>
          </div>
          <p class="text-[11px] text-slate-500">Pembayaran ditandai lunas oleh panitia setelah bukti transfer diverifikasi.</p>
          <button id="btn-register" type="submit" class="w-full py-3.5 px-4 rounded-xl bg-gradient-to-br from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-700 text-white font-semibold text-sm transition shadow-lg shadow-primary-600/30">Submit</button>
        </form>
      </div>
    </div>
  </section>

  <!-- PARTNERS -->
  <section class="py-16 px-4">
    <div class="max-w-5xl mx-auto">
      <div class="text-center mb-8 space-y-1">
        <span class="text-[11px] font-bold uppercase tracking-widest text-primary-600 block"><?php echo $e($c('partners_eyebrow')); ?></span>
        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900"><?php echo $e($c('partners_title')); ?></h2>
      </div>
      <div class="partner-marquee overflow-hidden rounded-2xl border border-slate-200 bg-white">
        <?php if ($coHosts): ?>
        <div class="partner-track flex w-max items-center py-4">
          <?php for ($pass = 0; $pass < 2; $pass++): foreach ($coHosts as $partner): ?>
          <div class="h-16 w-48 px-6 shrink-0 flex items-center justify-center grayscale hover:grayscale-0 opacity-80 hover:opacity-100 transition" <?php echo $pass ? 'aria-hidden="true"' : ''; ?>>
            <?php if ($partner['website']): ?><a href="<?php echo $e($partner['website']); ?>" target="_blank" rel="noopener" title="<?php echo $e($partner['name']); ?>" class="w-full h-full flex items-center justify-center"><img src="<?php echo $e($partner['logo']); ?>" alt="<?php echo $pass ? '' : $e($partner['name']); ?>" class="max-h-full max-w-full object-contain"></a><?php else: ?><img src="<?php echo $e($partner['logo']); ?>" alt="<?php echo $pass ? '' : $e($partner['name']); ?>" title="<?php echo $e($partner['name']); ?>" class="max-h-full max-w-full object-contain"><?php endif; ?>
          </div>
          <?php endforeach; endfor; ?>
        </div>
        <?php else: ?><p class="text-center text-sm text-slate-500 py-8">Logo partner belum ditambahkan. Tambahkan lewat menu Edit Seminar di admin.</p><?php endif; ?>
      </div>
    </div>
  </section>

  <?php if ($otherList): ?>
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <h2 class="text-2xl font-extrabold text-gray-900 mb-8">Seminar Lainnya</h2>
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php foreach ($otherList as $other): ?>
      <a href="/seminar/detail.php?id=<?php echo (int) $other['id']; ?>" class="rounded-xl border border-gray-100 overflow-hidden shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 bg-white flex flex-col">
        <div class="h-40 bg-[#1E1B3A] flex items-center justify-center overflow-hidden"><?php if ($other['image']): ?><img src="<?php echo $e($other['image']); ?>" alt="" class="w-full h-full object-cover"><?php endif; ?></div>
        <div class="p-5 flex-1"><h3 class="font-semibold text-gray-900 leading-snug"><?php echo $e($other['title']); ?></h3><p class="text-xs text-gray-500 mt-2"><?php echo $fmt($other['date']); ?><?php echo $other['location'] ? ' · ' . $e($other['location']) : ''; ?></p></div>
      </a>
      <?php endforeach; ?>
    </div>
  </section>
  <?php endif; ?>
<?php endif; ?>

<?php if ($featured): ?>
<script>
(function () {
  const slides = document.querySelectorAll('.hero-slide');
  const dots = document.querySelectorAll('[data-dot]');
  if (slides.length > 1) {
    let idx = 0, timer;
    function show(n) {
      idx = (n + slides.length) % slides.length;
      slides.forEach((slide, i) => slide.classList.toggle('hidden', i !== idx));
      dots.forEach((dot, i) => { dot.classList.toggle('bg-amber-500', i === idx); dot.classList.toggle('bg-white/70', i !== idx); });
    }
    const restart = () => { clearInterval(timer); timer = setInterval(() => show(idx + 1), 5000); };
    document.getElementById('slide-prev').addEventListener('click', () => { show(idx - 1); restart(); });
    document.getElementById('slide-next').addEventListener('click', () => { show(idx + 1); restart(); });
    dots.forEach(dot => dot.addEventListener('click', () => { show(+dot.dataset.dot); restart(); }));
    restart();
  }

  const cdTargets = <?php echo json_encode($countdownTargets, JSON_HEX_TAG | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE); ?>;
  const cd = document.getElementById('countdown');
  if (cd && cdTargets.length) {
    const units = cd.querySelectorAll('[data-unit]');
    const title = document.getElementById('cdTitle');
    const target = document.getElementById('cdTarget');
    const wrap = document.getElementById('cdChips');
    const chips = [];
    const pad = value => String(value).padStart(2, '0');
    let current = <?php echo (int) $cdDefault; ?>;

    if (wrap) cdTargets.forEach((item, i) => {
      const button = document.createElement('button');
      button.type = 'button';
      button.textContent = item.label;
      button.addEventListener('click', () => { current = i; tick(); });
      wrap.appendChild(button);
      chips.push(button);
    });

    function tick() {
      const item = cdTargets[current];
      let diff = Math.max(0, new Date(item.iso).getTime() - Date.now());
      const days = Math.floor(diff / 86400000); diff %= 86400000;
      const hours = Math.floor(diff / 3600000); diff %= 3600000;
      const minutes = Math.floor(diff / 60000); diff %= 60000;
      const seconds = Math.floor(diff / 1000);
      [days, hours, minutes, seconds].forEach((value, i) => units[i].textContent = i === 0 ? String(value) : pad(value));
      title.textContent = item.label;
      target.textContent = 'Target: ' + item.text + (new Date(item.iso).getTime() <= Date.now() ? ' · sudah lewat' : '');
      chips.forEach((chip, i) => chip.className = 'rounded-lg border px-3 py-1.5 text-[11px] ' + (i === current ? 'bg-amber-400 text-amber-950 border-amber-400 font-bold' : 'border-white/25 bg-white/5 text-primary-100'));
    }
    tick();
    setInterval(tick, 1000);
  }

  document.querySelectorAll('.copy-account').forEach(button => button.addEventListener('click', async () => {
    const original = button.textContent;
    try { await navigator.clipboard.writeText(button.dataset.copy); button.textContent = 'Disalin!'; } catch (_) {}
    setTimeout(() => { button.textContent = original; }, 2000);
  }));

  const form = document.getElementById('form-register');
  if (form) form.addEventListener('submit', async event => {
    event.preventDefault();
    const button = document.getElementById('btn-register');
    const box = document.getElementById('reg-alert');
    const label = button.textContent;
    button.disabled = true;
    button.textContent = 'Mengirim...';
    try {
      const response = await fetch('/api/register-seminar.php', { method: 'POST', body: new FormData(form) });
      const data = await response.json();
      box.className = 'p-4 text-sm rounded-lg ' + (data.status === 'success' ? 'bg-primary-50 text-primary-700 border border-primary-200' : 'bg-red-50 text-red-800 border border-red-200');
      box.textContent = data.message;
      if (data.status === 'success') form.reset();
    } catch (_) {
      box.className = 'p-4 text-sm rounded-lg bg-red-50 text-red-800 border border-red-200';
      box.textContent = 'Terjadi kesalahan jaringan.';
    } finally {
      button.disabled = false;
      button.textContent = label;
    }
  });
})();
</script>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
