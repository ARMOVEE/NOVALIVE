<?php
  $landmarkFiles = [
    'jakarta'    => 'monas-jakarta-4k.webp',
    'denpasar'   => 'pura-denpasar-4k.webp',
    'bandung'    => 'gedung-sate-bandung-4k.webp',
    'surabaya'   => 'patung-sura-baya-4k.webp',
    'yogyakarta' => 'tugu-yogyakarta-4k.webp'
  ];
  $landmarkImg = $landmarkFiles[$city['slug']] ?? null;
  $eventCount = count($events);
?>

<div style="background:#FFFFFF;border-bottom:1px solid var(--line);padding:40px 24px 36px;">
  <div style="max-width:1280px;margin:0 auto;display:flex;align-items:center;gap:32px;flex-wrap:wrap;">
    <?php if ($landmarkImg): ?>
      <img src="<?= asset($landmarkImg) ?>" alt="<?= e($city['landmark'] ?? $city['name']) ?>" style="height:110px;object-fit:contain;background:transparent;border:0;box-shadow:none;">
    <?php endif; ?>
    <div>
      <span style="font-size:14px;font-weight:700;color:var(--muted);letter-spacing:0.04em;text-transform:uppercase;display:block;">
        Event Menarik di
      </span>
      <h1 style="font-size:clamp(32px,5vw,52px);font-weight:900;color:var(--navy);line-height:1.1;margin:4px 0 8px;letter-spacing:-0.02em;">
        <?= strtoupper(e($city['name'])) ?>
      </h1>
      <p style="font-size:15px;color:var(--muted);margin:0;">
        <strong><?= $eventCount ?></strong> event tersedia di <?= e($city['name']) ?>.
      </p>
    </div>
  </div>
</div>

<div style="max-width:1280px;margin:40px auto 60px;padding:0 24px;">
  <?php if (empty($events)): ?>
    <div style="background:var(--card);border-radius:20px;border:1px dashed var(--line);padding:60px 24px;text-align:center;">
      <div style="font-size:48px;margin-bottom:12px;">🏙️</div>
      <h3 style="font-size:20px;color:var(--navy);margin-bottom:8px;">Belum ada event di kota ini.</h3>
      <p style="font-size:15px;color:var(--muted);max-width:440px;margin:0 auto 24px;">
        Kami terus memperluas jangkauan event ke seluruh penjuru Nusantara. Coba jelajahi kota-kota lainnya!
      </p>
      <a href="<?= base_url('kota') ?>" style="display:inline-block;padding:12px 24px;background:var(--navy);color:#fff;font-weight:700;border-radius:999px;font-size:14.5px;">
        Lihat Kota Lainnya
      </a>
    </div>
  <?php else: ?>
    <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(280px, 1fr));gap:24px;">
      <?php foreach ($events as $e): ?>
        <a href="<?= base_url('events/show/' . $e['id']) ?>" style="background:var(--card);border-radius:16px;overflow:hidden;border:1px solid var(--line);box-shadow:var(--shadow);display:flex;flex-direction:column;transition:transform .2s,box-shadow .2s;text-decoration:none;">
          <div style="aspect-ratio:16/9;background:var(--navy-deep);position:relative;overflow:hidden;">
            <?php $pUrl = poster_url($e['photo']); ?>
            <?php if ($pUrl): ?>
              <img src="<?= e($pUrl) ?>" alt="<?= e($e['title']) ?>" loading="lazy" style="width:100%;height:100%;object-fit:cover;">
            <?php else: ?>
              <div style="background:linear-gradient(135deg, #0E3254 0%, #23649A 100%);color:var(--gold);display:flex;align-items:center;justify-content:center;font-size:36px;font-weight:800;width:100%;height:100%;">
                <?= strtoupper(mb_substr($e['title'], 0, 1)) ?>
              </div>
            <?php endif; ?>
            <?php if (!empty($e['badge'])): ?>
              <span style="position:absolute;top:10px;left:10px;background:rgba(14,50,84,.9);color:var(--gold);font-size:11px;font-weight:700;padding:4px 8px;border-radius:6px;"><?= e($e['badge']) ?></span>
            <?php endif; ?>
          </div>
          <div style="padding:16px 18px 18px;display:flex;flex-direction:column;flex:1;">
            <div style="font-size:12px;font-weight:700;color:var(--brand);text-transform:uppercase;letter-spacing:.04em;margin-bottom:4px;"><?= e($city['name']) ?></div>
            <h3 style="font-size:16.5px;font-weight:700;line-height:1.35;margin-bottom:8px;color:var(--navy);"><?= e($e['title']) ?></h3>
            <div style="display:flex;align-items:center;gap:6px;font-size:13px;color:var(--muted);margin-bottom:12px;">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
              <span><?= tanggal($e['event_date']) ?></span>
            </div>
            <div style="margin-top:auto;padding-top:12px;border-top:1px dashed var(--line);display:flex;justify-content:space-between;align-items:center;">
              <div>
                <span style="font-size:11px;color:var(--muted);display:block;">Mulai dari</span>
                <span style="font-size:15px;font-weight:800;color:var(--navy);"><?= (float)$e['price'] > 0 ? rupiah($e['price']) : 'Gratis' ?></span>
              </div>
              <span style="font-size:12px;font-weight:700;color:var(--brand);"><?= e($e['category_name'] ?? '') ?></span>
            </div>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
