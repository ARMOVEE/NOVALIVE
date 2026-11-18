<div style="max-width:1280px;margin:36px auto 60px;padding:0 24px;">
  <div style="margin-bottom:32px;">
    <h1 style="font-size:32px;font-weight:800;letter-spacing:-0.02em;color:var(--navy);">Kategori Kotamu</h1>
    <p style="font-size:15px;color:var(--muted);margin-top:6px;">Pilih kota untuk menemukan berbagai pertunjukan, konser, dan festival menarik di sekitarmu</p>
  </div>

  <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(240px, 1fr));gap:24px;">
    <?php
      $landmarkFiles = [
        'jakarta'    => 'monas-jakarta-4k.webp',
        'denpasar'   => 'pura-denpasar-4k.webp',
        'bandung'    => 'gedung-sate-bandung-4k.webp',
        'surabaya'   => 'patung-sura-baya-4k.webp',
        'yogyakarta' => 'tugu-yogyakarta-4k.webp'
      ];
    ?>
    <?php foreach ($cities as $ci): ?>
      <a href="<?= base_url('kota/' . $ci['slug']) ?>" style="background:var(--card);border-radius:18px;padding:24px;border:1px solid var(--line);box-shadow:var(--shadow);position:relative;overflow:hidden;min-height:160px;display:flex;flex-direction:column;transition:transform .2s,box-shadow .2s;text-decoration:none;">
        <span style="font-size:20px;font-weight:800;color:var(--navy);margin-bottom:4px;"><?= e($ci['name']) ?></span>
        <span style="font-size:13.5px;color:var(--muted);"><?= (int)($ci['event_count'] ?? 0) ?> Event Tersedia</span>
        <span style="font-size:18px;color:var(--brand);margin-top:auto;font-weight:700;">Lihat Event ↗</span>
        <?php if (!empty($landmarkFiles[$ci['slug']])): ?>
          <img src="<?= asset($landmarkFiles[$ci['slug']]) ?>" alt="<?= e($ci['name']) ?>" style="position:absolute;right:10px;bottom:8px;height:90px;object-fit:contain;pointer-events:none;opacity:.95;">
        <?php endif; ?>
        <div style="position:absolute;bottom:0;left:0;right:0;height:4px;background:<?= e($ci['accent'] ?? '#23649A') ?>;"></div>
      </a>
    <?php endforeach; ?>
  </div>
</div>
