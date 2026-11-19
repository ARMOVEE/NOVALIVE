<?php
/**
 * View: Daftar Kota (/kota)
 * Samakan persis dengan pola kartu Jelajahi Kotamu di Beranda (Langkah 6)
 */

$cityConfig = [
    'jakarta'    => ['displayName' => 'DKI Jakarta',   'accent' => '#F97316', 'photo' => 'monas-jakarta-4k.webp'],
    'denpasar'   => ['displayName' => 'Kota Denpasar', 'accent' => '#14B8A6', 'photo' => 'pura-denpasar-4k.webp'],
    'bandung'    => ['displayName' => 'Bandung',       'accent' => '#3B82F6', 'photo' => 'gedung-sate-bandung-4k.webp'],
    'surabaya'   => ['displayName' => 'Kota Surabaya', 'accent' => '#22C55E', 'photo' => 'patung-sura-baya-4k.webp'],
    'yogyakarta' => ['displayName' => 'Yogyakarta',    'accent' => '#EC4899', 'photo' => 'tugu-yogyakarta-4k.webp'],
];
?>

<style>
.kota-page { padding: 48px 0 70px; background: #FFFFFF; }
.kota-head { margin-bottom: 32px; }
.kota-head h1 { font-size: 32px; font-weight: 800; letter-spacing: -0.02em; color: var(--navy2); margin: 0 0 6px; }
.kota-head p { font-size: 15px; color: var(--muted); margin: 0; }
.kota-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 16px; }
.kota-card { position: relative; display: block; text-decoration: none; width: 100%; text-align: left; background: var(--card); border: 1px solid var(--line); border-radius: 14px; box-shadow: var(--shadow); padding: 16px 14px; min-height: 118px; cursor: pointer; font-family: var(--font-body); color: var(--ink); overflow: hidden; transition: transform .2s, box-shadow .2s, border-color .2s; }
.kota-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-lg); border-color: var(--gold-deep); }
.kota-name { display: block; font-family: var(--font-display); font-weight: 800; font-size: 1.02rem; line-height: 1.25; max-width: 56%; position: relative; z-index: 1; }
.kota-arrow { position: absolute; left: 14px; bottom: 18px; color: var(--brand); z-index: 1; display: flex; }
.kota-media { position: absolute; right: 6px; bottom: 15px; top: 10px; width: 46%; background: #fff; border-radius: 10px; display: flex; align-items: flex-end; justify-content: center; overflow: hidden; }
.kota-media img { max-height: 100%; max-width: 100%; object-fit: contain; }
.kota-bar { position: absolute; left: 16px; right: 16px; bottom: 5px; height: 5px; border-radius: 99px; background: var(--kota-accent); }
@media (max-width: 1024px) { .kota-grid { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 640px) { .kota-grid { grid-template-columns: repeat(2, 1fr); } .kota-card { min-height: 108px; } }
@media (max-width: 420px) { .kota-grid { grid-template-columns: 1fr; } }
@media (prefers-reduced-motion: reduce) { .kota-card:hover { transform: none; } }
</style>

<div class="wrap kota-page">
  <div class="kota-head">
    <h1>Jelajahi Event di Kotamu</h1>
    <p>Pilih kotamu untuk menemukan berbagai pertunjukan, konser, dan festival menarik di sekitarmu.</p>
  </div>
  <div class="kota-grid">
    <?php foreach ($cities as $ci):
      $slug = strtolower($ci['slug']);
      $cfg = $cityConfig[$slug] ?? [];
      $displayName = $cfg['displayName'] ?? $ci['name'];
      $accent = $cfg['accent'] ?? ($ci['accent'] ?? '#23649A');
      $photo = $cfg['photo'] ?? ($slug . '-4k.webp');
    ?>
      <a class="kota-card" href="<?= base_url('kota/' . $slug) ?>" style="--kota-accent:<?= e($accent) ?>" aria-label="Lihat event di <?= e($displayName) ?>">
        <span class="kota-name"><?= e($displayName) ?></span>
        <span class="kota-arrow" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M7 17L17 7M9 7h8v8" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
        <span class="kota-media"><img src="<?= asset($photo) ?>" alt="<?= e($displayName) ?>" loading="lazy"></span>
        <span class="kota-bar" aria-hidden="true"></span>
      </a>
    <?php endforeach; ?>
  </div>
</div>
