<?php
require_once __DIR__ . '/seminar_helper.php';

// Memuat data turunan seminar untuk form edit admin.
// Mengembalikan baris pembicara (array) dan teks untuk bagian lain.
function loadSeminarFormData(PDO $pdo, int $id): array {
    $textOf = function (string $sql, array $cols) use ($pdo, $id) {
        $st = $pdo->prepare($sql);
        $st->execute([$id]);
        return implode("\n", array_map(
            fn($r) => implode(' | ', array_map(fn($c) => (string)($r[$c] ?? ''), $cols)),
            $st->fetchAll()
        ));
    };

    $st = $pdo->prepare("SELECT category, name, title, affiliation, image FROM seminar_speakers WHERE seminar_id = ? ORDER BY sort_order, id");
    $st->execute([$id]);

    $fees = (function () use ($pdo, $id) {
        $st = $pdo->prepare("SELECT category, item, amount, is_closed, note FROM seminar_fees WHERE seminar_id = ? ORDER BY sort_order, id");
        $st->execute([$id]);
        return implode("\n", array_map(fn($r) => implode(' | ', [
            $r['category'], $r['item'], $r['is_closed'] ? 'CLOSED' : (string)($r['amount'] ?? ''), $r['note'] ?? ''
        ]), $st->fetchAll()));
    })();

    $stSlides = $pdo->prepare("SELECT title, caption, image FROM seminar_slides WHERE seminar_id = ? ORDER BY sort_order, id");
    $stSlides->execute([$id]);
    $slideRows = $stSlides->fetchAll();

    $stPartners = $pdo->prepare("SELECT role, name, logo, website FROM seminar_partners WHERE seminar_id = ? ORDER BY sort_order, id");
    $stPartners->execute([$id]);
    $partnerRows = $stPartners->fetchAll();

    $stBanks = $pdo->prepare("SELECT bank_name, account_number, account_name FROM seminar_bank_transfers WHERE seminar_id = ? ORDER BY sort_order, id");
    $stBanks->execute([$id]);
    $bankRows = $stBanks->fetchAll();

    $stContent = $pdo->prepare("SELECT content_key, content_value FROM seminar_content WHERE seminar_id = ?");
    $stContent->execute([$id]);
    $contentValues = [];
    foreach ($stContent->fetchAll() as $row) {
        if (array_key_exists($row['content_key'], SEMINAR_CONTENT_FIELDS)) {
            $contentValues[$row['content_key']] = (string) $row['content_value'];
        }
    }

    return [
        'speakerRows'   => $st->fetchAll(),
        'slidesText'    => $textOf("SELECT title, caption, image FROM seminar_slides WHERE seminar_id = ? ORDER BY sort_order, id", ['title', 'caption', 'image']),
        'slideRows'     => $slideRows,
        'scheduleText'  => $textOf("SELECT label, schedule_date, description FROM seminar_schedules WHERE seminar_id = ? ORDER BY sort_order, id", ['label', 'schedule_date', 'description']),
        'partnersText'  => $textOf("SELECT role, name, logo, website FROM seminar_partners WHERE seminar_id = ? ORDER BY sort_order, id", ['role', 'name', 'logo', 'website']),
        'partnerRows'   => $partnerRows,
        'feesText'      => $fees,
        'subthemesText' => $textOf("SELECT name FROM seminar_subthemes WHERE seminar_id = ? ORDER BY sort_order, id", ['name']),
        'bankRows'      => $bankRows,
        'contentValues' => $contentValues,
    ];
}
