<style>
  .hero-banner {
    max-width: 1280px;
    margin: 24px auto 40px;
    padding: 0 24px;
  }
  .hero-slide {
    background: linear-gradient(135deg, #0E3254 0%, #15497A 60%, #23649A 100%);
    border-radius: 20px;
    padding: 56px 48px;
    color: #fff;
    position: relative;
    overflow: hidden;
    box-shadow: var(--shadow-lg);
  }
  .hero-slide::after {
    content: '';
    position: absolute;
    right: -40px;
    bottom: -40px;
    width: 320px;
    height: 320px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(234, 212, 162, 0.15) 0%, transparent 70%);
    pointer-events: none;
  }
  .hero-badge {
    display: inline-block;
    background: rgba(234, 212, 162, 0.2);
    color: var(--gold);
    border: 1px solid rgba(234, 212, 162, 0.4);
    font-size: 13px;
    font-weight: 700;
    padding: 6px 14px;
    border-radius: 999px;
    margin-bottom: 16px;
    letter-spacing: 0.04em;
    text-transform: uppercase;
  }
  .hero-title {
    font-size: clamp(32px, 4.5vw, 48px);
    font-weight: 800;
    line-height: 1.15;
    color: #fff;
    max-width: 650px;
    margin-bottom: 16px;
  }
  .hero-desc {
    font-size: 16px;
    color: #C4D6E8;
    max-width: 520px;
    line-height: 1.6;
    margin-bottom: 28px;
  }
  .hero-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--gold);
    color: var(--navy-deep);
    font-weight: 700;
    font-size: 15px;
    padding: 12px 28px;
    border-radius: 999px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.15);
    transition: transform 0.2s, background 0.2s;
  }
  .hero-btn:hover {
    background: #f0deb3;
    transform: translateY(-2px);
  }

  /* Section Styles */
  .section-wrap {
    max-width: 1280px;
    margin: 0 auto 50px;
    padding: 0 24px;
  }
  .section-header {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    margin-bottom: 24px;
  }
  .section-header h2 {
    font-size: 24px;
    font-weight: 800;
    letter-spacing: -0.01em;
  }
  .section-link {
    font-size: 14.5px;
    font-weight: 700;
    color: var(--brand);
    display: inline-flex;
    align-items: center;
    gap: 4px;
  }
  .section-link:hover { color: var(--navy); }

  /* Event Grid & Card */
  .event-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 24px;
  }
  .event-card {
    background: var(--card);
    border-radius: var(--radius);
    overflow: hidden;
    box-shadow: var(--shadow);
    display: flex;
    flex-direction: column;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    border: 1px solid var(--line);
  }
  .event-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
  }
  .event-media {
    aspect-ratio: 16/9;
    background: var(--navy-deep);
    position: relative;
    overflow: hidden;
  }
  .event-media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  .event-badge-tag {
    position: absolute;
    top: 12px;
    left: 12px;
    background: rgba(14, 50, 84, 0.9);
    color: var(--gold);
    font-size: 11.5px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 6px;
    backdrop-filter: blur(4px);
  }
  .event-body {
    padding: 18px 20px 20px;
    display: flex;
    flex-direction: column;
    flex: 1;
  }
  .event-city-meta {
    font-size: 12.5px;
    font-weight: 600;
    color: var(--brand);
    text-transform: uppercase;
    letter-spacing: 0.04em;
    margin-bottom: 6px;
  }
  .event-title {
    font-size: 16.5px;
    font-weight: 700;
    line-height: 1.35;
    margin-bottom: 10px;
    color: var(--navy);
  }
  .event-date-row {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 13.5px;
    color: var(--muted);
    margin-bottom: 14px;
  }
  .event-footer {
    margin-top: auto;
    padding-top: 14px;
    border-top: 1px dashed var(--line);
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
  .event-price-label {
    font-size: 11.5px;
    color: var(--muted);
    display: block;
  }
  .event-price-val {
    font-size: 16px;
    font-weight: 800;
    color: var(--navy);
  }

  /* Kategori Horizontal */
  .cat-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(135px, 1fr));
    gap: 16px;
  }
  .cat-card {
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: 14px;
    padding: 20px 14px;
    text-align: center;
    transition: transform 0.18s, border-color 0.18s, box-shadow 0.18s;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
  }
  .cat-card:hover {
    transform: translateY(-3px);
    border-color: var(--brand);
    box-shadow: var(--shadow);
  }
  .cat-icon-circle {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: var(--cream);
    display: grid;
    place-items: center;
    color: var(--navy);
    font-size: 20px;
  }
  .cat-name {
    font-size: 14.5px;
    font-weight: 700;
    color: var(--navy);
  }
  .cat-count {
    font-size: 12px;
    color: var(--muted);
  }

  /* Kotamu Grid */
  .kota-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 20px;
  }
  .kota-card {
    background: var(--card);
    border-radius: 16px;
    padding: 20px;
    border: 1px solid var(--line);
    position: relative;
    overflow: hidden;
    transition: transform 0.2s, box-shadow 0.2s;
    display: flex;
    flex-direction: column;
    min-height: 140px;
    box-shadow: var(--shadow);
  }
  .kota-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
  }
  .kota-card::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--kota-accent, var(--brand));
  }
  .kota-name {
    font-size: 18px;
    font-weight: 800;
    color: var(--navy);
    margin-bottom: 4px;
  }
  .kota-arrow {
    font-size: 16px;
    color: var(--brand);
    margin-top: 8px;
    display: inline-block;
  }
  .kota-landmark-img {
    position: absolute;
    right: 12px;
    bottom: 8px;
    height: 80px;
    object-fit: contain;
    pointer-events: none;
    opacity: 0.95;
  }
</style>

<!-- 1. HERO BANNER -->
<section class="hero-banner">
  <div class="hero-slide">
    <span class="hero-badge">Jelajahi Pengalaman Baru</span>
    <h1 class="hero-title">Ribuan Event Pilihan Siap Menyambutmu</h1>
    <p class="hero-desc">Beli tiket konser musik spektakuler, pameran seni kontemporer, wahana wisata, hingga kejuaraan olahraga favoritmu di NOVA LIVE.</p>
    <a href="<?= base_url('events') ?>" class="hero-btn">
      <span>Lihat Semua Event</span>
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
    </a>
  </div>
</section>

<!-- 2. EVENT SERU (10 Event) -->
<section class="section-wrap">
  <div class="section-header">
    <div>
      <h2>Event Seru Untukmu</h2>
      <p style="font-size:14px;color:var(--muted);margin-top:4px;">Pilihan event paling dinanti dari berbagai kota di Indonesia</p>
    </div>
    <a href="<?= base_url('events') ?>" class="section-link">
      Lihat Semua
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m9 18 6-6-6-6"/></svg>
    </a>
  </div>

  <div class="event-grid">
    <?php foreach ($eventsSeru as $e): ?>
      <a href="<?= base_url('events/show/' . $e['id']) ?>" class="event-card">
        <div class="event-media">
          <?php $pUrl = poster_url($e['photo']); ?>
          <?php if ($pUrl): ?>
            <img src="<?= e($pUrl) ?>" alt="<?= e($e['title']) ?>" loading="lazy">
          <?php else: ?>
            <div style="background:linear-gradient(135deg, #0E3254 0%, #23649A 100%);color:var(--gold);display:flex;align-items:center;justify-content:center;font-size:36px;font-weight:800;width:100%;height:100%;">
              <?= strtoupper(mb_substr($e['title'], 0, 1)) ?>
            </div>
          <?php endif; ?>
          <?php if (!empty($e['badge'])): ?>
            <span class="event-badge-tag"><?= e($e['badge']) ?></span>
          <?php endif; ?>
        </div>
        <div class="event-body">
          <div class="event-city-meta"><?= e($e['city_name'] ?? 'Indonesia') ?></div>
          <h3 class="event-title"><?= e($e['title']) ?></h3>
          <div class="event-date-row">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            <span><?= tanggal($e['event_date']) ?></span>
          </div>
          <div class="event-footer">
            <div>
              <span class="event-price-label">Mulai dari</span>
              <span class="event-price-val"><?= (float)$e['price'] > 0 ? rupiah($e['price']) : 'Gratis' ?></span>
            </div>
            <span style="font-size:12px;font-weight:700;color:var(--brand);"><?= e($e['category_name'] ?? '') ?></span>
          </div>
        </div>
      </a>
    <?php endforeach; ?>
  </div>
</section>

<!-- 3. KATEGORI EVENT (8 Kategori) -->
<section class="section-wrap">
  <div class="section-header">
    <div>
      <h2>Kategori Event</h2>
      <p style="font-size:14px;color:var(--muted);margin-top:4px;">Eksplorasi sesuai minat dan kesukaanmu</p>
    </div>
    <a href="<?= base_url('kategori') ?>" class="section-link">
      Semua Kategori
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m9 18 6-6-6-6"/></svg>
    </a>
  </div>

  <div class="cat-grid">
    <?php foreach ($categories as $c): ?>
      <a href="<?= base_url('events?category=' . $c['id']) ?>" class="cat-card">
        <div class="cat-icon-circle">
          <?php
            $icons = [
              'Musik' => '🎵', 'Pameran' => '🎨', 'Teater' => '🎭', 'Olahraga' => '🏆',
              'Wisata' => '🎡', 'Talkshow' => '🎙️', 'Workshop' => '💡', 'Kompetisi' => '🎮'
            ];
            echo $icons[$c['name']] ?? '🎪';
          ?>
        </div>
        <div class="cat-name"><?= e($c['name']) ?></div>
        <div class="cat-count"><?= (int)($c['event_count'] ?? 0) ?> Event</div>
      </a>
    <?php endforeach; ?>
  </div>
</section>

<!-- 4. EVENT POPULER (4 Event Terlaris) -->
<section class="section-wrap">
  <div class="section-header">
    <div>
      <h2>Event Populer Minggu Ini</h2>
      <p style="font-size:14px;color:var(--muted);margin-top:4px;">Tiket paling cepat diburu pengunjung</p>
    </div>
    <a href="<?= base_url('events?sort=popular') ?>" class="section-link">
      Lihat Urutan Populer
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m9 18 6-6-6-6"/></svg>
    </a>
  </div>

  <div class="event-grid">
    <?php foreach ($eventsPopular as $e): ?>
      <a href="<?= base_url('events/show/' . $e['id']) ?>" class="event-card">
        <div class="event-media">
          <?php $pUrl = poster_url($e['photo']); ?>
          <?php if ($pUrl): ?>
            <img src="<?= e($pUrl) ?>" alt="<?= e($e['title']) ?>" loading="lazy">
          <?php else: ?>
            <div style="background:linear-gradient(135deg, #0E3254 0%, #23649A 100%);color:var(--gold);display:flex;align-items:center;justify-content:center;font-size:36px;font-weight:800;width:100%;height:100%;">
              <?= strtoupper(mb_substr($e['title'], 0, 1)) ?>
            </div>
          <?php endif; ?>
          <span class="event-badge-tag" style="background:#C5221F;color:#fff;">🔥 <?= (int)$e['sold'] ?> Terjual</span>
        </div>
        <div class="event-body">
          <div class="event-city-meta"><?= e($e['city_name'] ?? 'Indonesia') ?></div>
          <h3 class="event-title"><?= e($e['title']) ?></h3>
          <div class="event-date-row">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            <span><?= tanggal($e['event_date']) ?></span>
          </div>
          <div class="event-footer">
            <div>
              <span class="event-price-label">Mulai dari</span>
              <span class="event-price-val"><?= (float)$e['price'] > 0 ? rupiah($e['price']) : 'Gratis' ?></span>
            </div>
            <span style="font-size:12px;font-weight:700;color:var(--brand);"><?= e($e['category_name'] ?? '') ?></span>
          </div>
        </div>
      </a>
    <?php endforeach; ?>
  </div>
</section>

<!-- 5. JELAJAHI EVENT DI KOTAMU (5 Kota) -->
<section class="section-wrap">
  <div class="section-header">
    <div style="display:flex;align-items:center;gap:8px;">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" style="color:var(--brand);"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
      <h2>Jelajahi Event di Kotamu</h2>
    </div>
    <a href="<?= base_url('kota') ?>" class="section-link">
      Semua Kota
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m9 18 6-6-6-6"/></svg>
    </a>
  </div>

  <div class="kota-grid">
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
      <a href="<?= base_url('kota/' . $ci['slug']) ?>" class="kota-card" style="--kota-accent:<?= e($ci['accent'] ?? '#23649A') ?>;">
        <span class="kota-name"><?= e($ci['name']) ?></span>
        <span style="font-size:13px;color:var(--muted);"><?= (int)($ci['event_count'] ?? 0) ?> event aktif</span>
        <span class="kota-arrow">↗</span>
        <?php if (!empty($landmarkFiles[$ci['slug']])): ?>
          <img src="<?= asset($landmarkFiles[$ci['slug']]) ?>" alt="<?= e($ci['name']) ?>" class="kota-landmark-img">
        <?php endif; ?>
      </a>
    <?php endforeach; ?>
  </div>
</section>
