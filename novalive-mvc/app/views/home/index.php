<style>
  /* BANNER: tumpukan 3 slide navy kosong (sesuai Figma) */
  .sr-only{position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0 0 0 0);white-space:nowrap;border:0}
  .banner-stack-sec{background:var(--nav-bg);padding:34px 20px 30px}
  .banner-stack{position:relative;max-width:1374px;margin:0 auto;aspect-ratio:1374/499}
  .banner-slide{position:absolute;left:50%;transform:translateX(-50%);background:#15497A;border-radius:20px;transition:top .45s ease,height .45s ease,width .45s ease,opacity .45s ease,background .45s ease}
  .banner-slide.back{width:95.12%;height:49.7%;top:21.04%;background:rgba(21,73,122,.5);opacity:1;z-index:1}
  .banner-slide.mid{width:89.3%;height:65.73%;top:13.03%;background:rgba(21,73,122,.75);opacity:1;z-index:2}
  .banner-slide.front{width:83.48%;height:81.76%;top:5.01%;background:#15497A;opacity:1;z-index:3}
  .banner-arrow{position:absolute;top:45.89%;transform:translateY(-50%);width:46px;height:46px;min-height:46px;border-radius:50%;background:#fff;border:0;box-shadow:var(--shadow-lg);font-size:1.6rem;line-height:1;color:var(--navy);z-index:4;display:flex;align-items:center;justify-content:center;cursor:pointer;padding:0 0 4px}
  .banner-arrow:hover{color:var(--brand)}
  .banner-arrow.prev{left:6.55%}
  .banner-arrow.next{right:6.55%}
  .banner-dots{position:absolute;left:50%;transform:translateX(-50%);bottom:2.5%;background:#F5EFE0;border-radius:999px;padding:8px 12px;display:flex;gap:7px;align-items:center;box-shadow:var(--shadow);z-index:4}
  .banner-dots span{width:8px;height:8px;border-radius:999px;background:rgba(21,73,122,.45);transition:width .3s ease,background .3s ease}
  .banner-dots span.on{width:24px;background:#23649A}
  @media(max-width:760px){ .banner-arrow{width:40px;height:40px;min-height:40px;font-size:1.35rem} .banner-arrow.prev{left:2%} .banner-arrow.next{right:2%} }

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

<!-- 1. BANNER: tumpukan 3 slide navy kosong -->
<section class="banner-stack-sec" aria-label="Banner">
  <h1 class="sr-only">NOVA LIVE — Platform Tiket Event Indonesia</h1>
  <div class="banner-stack reveal">
    <div class="banner-slide back" aria-hidden="true"></div>
    <div class="banner-slide mid" aria-hidden="true"></div>
    <div class="banner-slide front" aria-hidden="true"></div>
    <button type="button" class="banner-arrow prev" id="bannerPrev" aria-label="Slide sebelumnya">‹</button>
    <button type="button" class="banner-arrow next" id="bannerNext" aria-label="Slide berikutnya">›</button>
    <div class="banner-dots" id="bannerDots" aria-hidden="true"><span class="on"></span><span></span><span></span></div>
  </div>
</section>

<script>
(function(){
  var slides = document.querySelectorAll(".banner-slide");
  var dots = document.querySelectorAll("#bannerDots span");
  var prev = document.getElementById("bannerPrev");
  var next = document.getElementById("bannerNext");
  var stack = document.querySelector(".banner-stack");
  if (!slides.length || !prev || !next) return;
  var pos = ["back", "mid", "front"];
  var cur = 2;
  function render(){
    slides.forEach(function(s, i){
      s.classList.remove("back", "mid", "front");
      s.classList.add(pos[(i - cur + 5) % 3]);
    });
    dots.forEach(function(d, i){ d.classList.toggle("on", i === cur); });
  }
  render();

  /* Auto-slide: putar tumpukan tiap 4 detik (loop).
     Pause saat kursor di atas banner atau kontrol banner difokuskan.
     Tidak jalan kalau pengguna memakai reduced-motion. */
  var DELAY = 4000;
  var reduced = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  var timer = null;
  var hovering = false;
  var focused = false;
  function stopAuto(){
    if (timer !== null){ clearInterval(timer); timer = null; }
  }
  function startAuto(){
    stopAuto();
    if (reduced || hovering || focused) return;
    timer = setInterval(function(){ cur = (cur + 1) % 3; render(); }, DELAY);
  }
  next.addEventListener("click", function(){ cur = (cur + 1) % 3; render(); startAuto(); });
  prev.addEventListener("click", function(){ cur = (cur + 2) % 3; render(); startAuto(); });
  dots.forEach(function(d, i){
    d.style.cursor = "pointer";
    d.addEventListener("click", function(){ cur = i; render(); startAuto(); });
  });
  if (stack){
    stack.addEventListener("mouseenter", function(){ hovering = true; stopAuto(); });
    stack.addEventListener("mouseleave", function(){ hovering = false; startAuto(); });
    stack.addEventListener("focusin", function(){ focused = true; stopAuto(); });
    stack.addEventListener("focusout", function(){ focused = false; startAuto(); });
  }
  startAuto();
})();
</script>

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
