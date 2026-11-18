<div style="max-width:1280px;margin:36px auto 60px;padding:0 24px;">
  <div style="margin-bottom:32px;">
    <h1 style="font-size:32px;font-weight:800;letter-spacing:-0.02em;color:var(--navy);">Kategori Event</h1>
    <p style="font-size:15px;color:var(--muted);margin-top:6px;">Temukan beragam pertunjukan dan aktivitas seru sesuai dengan minat favoritmu</p>
  </div>

  <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(260px, 1fr));gap:24px;">
    <?php
      $icons = [
        'Musik' => '🎵', 'Pameran' => '🎨', 'Teater' => '🎭', 'Olahraga' => '🏆',
        'Wisata' => '🎡', 'Talkshow' => '🎙️', 'Workshop' => '💡', 'Kompetisi' => '🎮'
      ];
    ?>
    <?php foreach ($categories as $cat): ?>
      <a href="<?= base_url('events?category=' . (int)$cat['id']) ?>" style="background:var(--card);border-radius:18px;padding:28px 24px;border:1px solid var(--line);box-shadow:var(--shadow);display:flex;flex-direction:column;transition:transform .2s,box-shadow .2s;text-decoration:none;">
        <div style="width:60px;height:60px;border-radius:16px;background:var(--cream);display:grid;place-items:center;font-size:28px;margin-bottom:16px;">
          <?= $icons[$cat['name']] ?? '🎪' ?>
        </div>
        <h2 style="font-size:20px;font-weight:800;color:var(--navy);margin-bottom:6px;"><?= e($cat['name']) ?></h2>
        <p style="font-size:14px;color:var(--muted);line-height:1.5;margin-bottom:16px;flex:1;">
          <?= e($cat['description'] ?? 'Eksplorasi event menarik dalam kategori ini.') ?>
        </p>
        <div style="margin-top:auto;display:flex;align-items:center;justify-content:space-between;border-top:1px dashed var(--line);padding-top:14px;">
          <span style="font-size:13px;font-weight:700;color:var(--navy);"><?= (int)($cat['event_count'] ?? 0) ?> Event</span>
          <span style="font-size:14px;font-weight:700;color:var(--brand);">Jelajahi →</span>
        </div>
      </a>
    <?php endforeach; ?>
  </div>
</div>
