<?php
/**
 * View: Halaman Kota (/kota/{slug})
 * Samakan persis dengan desain mockup (nova-live/NOVA LIVE — Halaman Kota.html)
 */

$citySlug = strtolower($city['slug'] ?? '');
$isBali = ($citySlug === 'denpasar' || strtolower($city['name'] ?? '') === 'denpasar' || $citySlug === 'bali');
$cityDisplayName = $isBali ? 'Bali' : $city['name'];
$cityNameUpper = $isBali ? 'BALI' : strtoupper($city['name']);

$landmarks = [
    'jakarta'    => ['file' => 'monas-jakarta-4k.webp',       'alt' => 'Monumen Nasional (Monas), landmark Jakarta'],
    'denpasar'   => ['file' => 'pura-denpasar-4k.webp',       'alt' => 'Pura Bajra Sandhi, landmark Denpasar'],
    'bandung'    => ['file' => 'gedung-sate-bandung-4k.webp', 'alt' => 'Gedung Sate, landmark Bandung'],
    'surabaya'   => ['file' => 'patung-sura-baya-4k.webp',    'alt' => 'Patung Sura & Baya, landmark Surabaya'],
    'yogyakarta' => ['file' => 'tugu-yogyakarta-4k.webp',    'alt' => 'Tugu Yogyakarta, landmark Yogyakarta'],
];

$landmarkInfo = $landmarks[$citySlug] ?? null;
$eventCount = count($events);

$avaColors = ["#23649A", "#B7791F", "#0F766E", "#BE185D", "#7C3AED", "#C2410C"];

if (!function_exists('tanggalFullIndo')) {
    function tanggalFullIndo($date) {
        if (empty($date)) return '-';
        $ts = strtotime($date);
        if ($ts === false) return $date;
        $bulanIndo = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        return (int)date('j', $ts) . ' ' . ($bulanIndo[(int)date('n', $ts)] ?? date('F', $ts)) . ' ' . date('Y', $ts);
    }
}
?>

<style>
  /* ===== WRAP & DASAR ===== */
  .wrap{max-width:1280px;margin:0 auto;padding:0 24px}

  /* ===== HEADER KOTA (pola Artatix: landmark + Event Menarik di + nama kota) ===== */
  .city-head{padding:52px 0 10px}
  .city-title{display:flex;align-items:center;gap:26px}
  .city-landmark{width:118px;height:118px;flex:none;display:grid;place-items:center;background:transparent;border:0;box-shadow:none}
  .city-landmark img{max-width:118px;max-height:118px;object-fit:contain;background:transparent;border:0;box-shadow:none;filter:none}
  .city-title p{margin:0 0 4px;font-size:21px;font-weight:600;color:var(--ink)}
  .city-title h1{margin:0;font-size:44px;line-height:1.05;font-weight:800;letter-spacing:-.01em;text-transform:uppercase}
  .city-sub{margin:14px 0 0 144px;color:var(--muted);font-size:15px}
  .city-sub b{color:var(--ink)}

  /* ===== GRID EVENT (kartu pola Artatix) ===== */
  .grid{display:grid;grid-template-columns:repeat(4,1fr);gap:26px;padding:28px 0 70px}
  .card{background:var(--card);border-radius:var(--radius);box-shadow:var(--shadow);overflow:hidden;display:flex;flex-direction:column;transition:transform .18s ease,box-shadow .18s ease;text-decoration:none;color:inherit}
  .card:hover{transform:translateY(-4px);box-shadow:var(--shadow-lg)}
  .card-media{aspect-ratio:16/7.6;overflow:hidden;background:var(--navy-deep);flex:none;display:flex;align-items:center;justify-content:center}
  .card-media .poster-ph{width:100%;height:100%;background:linear-gradient(135deg, #0E3254 0%, #23649A 100%);color:var(--gold);display:flex;align-items:center;justify-content:center;font-size:52px;font-weight:800;font-family:var(--font-display, 'Plus Jakarta Sans', sans-serif);user-select:none}
  .card-body{padding:18px 20px 16px;display:flex;flex-direction:column;flex:1}
  .card-body h3{margin:0 0 12px;font-size:18px;line-height:1.35;font-weight:700;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;min-height:49px;color:var(--ink)}
  .card-date{display:flex;align-items:center;gap:9px;color:var(--muted);font-size:14.5px;font-weight:500;margin:0 0 12px}
  .cal{width:26px;height:26px;flex:none;border-radius:7px;background:#DCEBFA;display:grid;place-items:center;color:var(--brand)}
  .card-price{margin:0;font-size:19px;font-weight:800;color:var(--ink)}
  .card-price small{display:block;font-size:12px;font-weight:600;color:var(--muted);margin-bottom:2px}
  .card-org{display:flex;align-items:center;gap:11px;margin-top:16px;padding-top:15px;border-top:2px dotted var(--line)}
  .card-org .org-ava{width:42px;height:42px;flex:none;border-radius:50%;display:grid;place-items:center;font-weight:800;font-size:14px;color:#fff;background:var(--brand)}
  .card-org .org-name{font-size:14.5px;font-weight:600;color:var(--muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
  .empty{grid-column:1/-1;text-align:center;padding:64px 20px;background:var(--card);border-radius:var(--radius);box-shadow:var(--shadow)}
  .empty h3{margin:0 0 8px;font-size:20px;color:var(--ink)}.empty p{margin:0;color:var(--muted)}

  @media (max-width:1024px){ .grid{grid-template-columns:repeat(2,1fr)} }
  @media (max-width:760px){
    .wrap{padding:0 16px}
    .city-head{padding:34px 0 6px}
    .city-title{gap:16px}
    .city-landmark{width:74px;height:74px}
    .city-landmark img{max-width:74px;max-height:74px}
    .city-title p{font-size:16px}
    .city-title h1{font-size:30px}
    .city-sub{margin-left:90px;font-size:14px}
    .grid{grid-template-columns:1fr;gap:20px;padding-bottom:52px}
  }
  @media (prefers-reduced-motion: reduce){ .card{transition:none!important} }
</style>

<div class="wrap">
  <!-- Header kota: landmark di kiri, "Event Menarik di" + nama kota besar (pola halaman territory Artatix) -->
  <section class="city-head">
    <div class="city-title">
      <?php if ($landmarkInfo): ?>
        <div class="city-landmark">
          <img id="cityImg" src="<?= asset($landmarkInfo['file']) ?>" alt="<?= e($landmarkInfo['alt']) ?>">
        </div>
      <?php endif; ?>
      <div>
        <p>Event Menarik di</p>
        <h1 id="cityName"><?= e($cityNameUpper) ?></h1>
      </div>
    </div>
    <?php if ($eventCount > 0): ?>
      <p class="city-sub" id="citySub"><b><?= $eventCount ?> event</b> tersedia di <?= e($cityDisplayName) ?>.</p>
    <?php else: ?>
      <p class="city-sub" id="citySub">Belum ada event di kota ini.</p>
    <?php endif; ?>
  </section>

  <!-- Grid event kota terpilih saja -->
  <section class="grid" id="grid" aria-live="polite">
    <?php if (empty($events)): ?>
      <div class="empty">
        <h3>Belum ada event ditemukan</h3>
        <p>Belum ada event di kota ini.</p>
      </div>
    <?php else: ?>
      <?php foreach ($events as $i => $e): ?>
        <?php
          $avaColor = $avaColors[$i % count($avaColors)];
          $orgName = $e['organizer'] ?? 'NOVA Live';
          $words = preg_split('/\s+/', trim((string)$orgName));
          $initials = '';
          if (!empty($words)) {
              foreach (array_slice($words, 0, 2) as $w) {
                  $initials .= mb_substr($w, 0, 1);
              }
          }
          $initials = strtoupper($initials ?: 'NL');
          $priceDisplay = ((float)$e['price'] > 0) ? rupiah($e['price']) : 'Gratis';
          $venueName = $e['venue'] ?? '';
        ?>
        <a class="card" href="<?= base_url('events/show/' . $e['id']) ?>" aria-label="<?= e($e['title']) ?><?= $venueName ? ' di ' . e($venueName) : '' ?>, <?= e($cityDisplayName) ?>">
          <div class="card-media">
            <div class="poster-ph" aria-hidden="true">
              <?= strtoupper(mb_substr($e['title'], 0, 1)) ?>
            </div>
          </div>
          <div class="card-body">
            <h3><?= e($e['title']) ?></h3>
            <p class="card-date">
              <span class="cal">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4"/></svg>
              </span>
              <?= tanggalFullIndo($e['event_date']) ?>
            </p>
            <p class="card-price"><small>Mulai dari</small><?= $priceDisplay ?></p>
            <div class="card-org">
              <span class="org-ava" style="background:<?= $avaColor ?>"><?= e($initials) ?></span>
              <span class="org-name"><?= e($orgName) ?></span>
            </div>
          </div>
        </a>
      <?php endforeach; ?>
    <?php endif; ?>
  </section>
</div>
