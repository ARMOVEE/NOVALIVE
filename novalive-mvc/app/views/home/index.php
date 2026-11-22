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

  /* Base Wraps & Cards umum */
  .wrap{max-width:var(--wrap);margin:0 auto;padding:0 20px}
  .section{padding:52px 0;position:relative}
  .section.alt{background:var(--card)}
  .section-head{display:flex;justify-content:space-between;align-items:flex-end;gap:20px;flex-wrap:wrap;margin-bottom:22px}
  .card{background:var(--card);border:1px solid var(--line);border-radius:var(--radius);box-shadow:var(--shadow);overflow:hidden;transition:transform .2s,box-shadow .2s}
  .card:hover{transform:translateY(-3px);box-shadow:var(--shadow-lg)}
  @media(prefers-reduced-motion:reduce){.card:hover{transform:none}}

  /* EVENT SERU (Carousel) */
  #seru{background:var(--cream);padding:52px 0;position:relative}
  .seru-wrap{position:relative}
  .seru-icon{vertical-align:-4px;margin-right:6px;color:var(--brand)}
  .seru-row{display:flex;gap:20px;overflow-x:auto;scroll-snap-type:x mandatory;-webkit-overflow-scrolling:touch;scrollbar-width:none;padding:4px 4px 14px;margin:0 -4px}
  .seru-row::-webkit-scrollbar{display:none}
  .seru-card{flex:0 0 400px;width:400px;scroll-snap-align:start;display:flex;flex-direction:column;height:auto}
  .seru-card img{height:170px;width:100%;object-fit:cover;border-bottom:1px solid var(--line);display:block}
  .seru-placeholder{height:170px;width:100%;background:linear-gradient(135deg,#0E3254 0%,#23649A 100%);color:var(--gold);display:flex;align-items:center;justify-content:center;font-family:var(--font-display);font-size:42px;font-weight:800;border-bottom:1px solid var(--line);user-select:none}
  .seru-body{padding:16px;display:flex;flex-direction:column;flex:1}
  .seru-body .meta{color:var(--muted);font-size:.85rem;margin:0}
  .seru-body h3{font-size:1.02rem;margin:6px 0;line-height:1.35}
  .seru-body h3 a{text-decoration:none;color:inherit;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
  .seru-body h3 a:hover{color:var(--brand)}
  .seru-from{color:var(--muted);font-size:.85rem;margin:14px 0 2px;margin-top:auto;border-top:1px solid var(--line);padding-top:12px}
  .seru-price{font-weight:800;font-size:1.05rem;margin:0;color:var(--navy)}
  .seru-arrow{position:absolute;top:calc(50% + 16px);transform:translateY(-50%);width:46px;height:46px;border-radius:50%;background:var(--card);border:1px solid var(--line);box-shadow:var(--shadow);font-size:1.6rem;line-height:1;color:var(--navy);z-index:5;display:flex;align-items:center;justify-content:center;cursor:pointer;padding:0;font-family:inherit;transition:transform .2s,box-shadow .2s,background .2s}
  .seru-arrow:hover{background:#fff;transform:translateY(-50%) scale(1.06);box-shadow:var(--shadow-lg)}
  .seru-arrow:active{transform:translateY(-50%) scale(0.96)}
  .seru-arrow.seru-prev{left:-22px}
  .seru-arrow.seru-next{right:-22px}
  @media(max-width:1240px){.seru-arrow.seru-prev{left:10px}.seru-arrow.seru-next{right:10px}}
  @media(max-width:1024px){.seru-card{flex:0 0 340px;width:340px}}
  @media(max-width:760px){.seru-card{flex:0 0 85%;width:85%}.seru-card img,.seru-placeholder{height:160px}.seru-arrow{display:none!important}}
  @media(prefers-reduced-motion:reduce){.seru-arrow:hover{transform:translateY(-50%)}}

  /* Reveal */
  .reveal{opacity:1;transform:none}
  .reveal.visible{opacity:1;transform:none}

  /* KATEGORI EVENT */
  #populer,.kategori-sec{background:#fff;--katblue:#23649A;--katbg:#F5EFE0;--katdark:#15497A}
  .kategori-title{color:var(--navy);margin-bottom:36px}
  .kategori-row{display:flex;flex-wrap:wrap;justify-content:space-between;gap:20px 10px}
  .kategori-item{flex:1 1 0;min-width:100px;display:flex;flex-direction:column;align-items:center;gap:16px;text-decoration:none}
  .kategori-circle{width:106px;height:106px;border-radius:50%;background:var(--katbg);border:1.5px solid var(--gold);display:flex;align-items:center;justify-content:center;color:var(--katblue);transition:background .2s,border-color .2s}
  .kategori-item:hover .kategori-circle{--katbg:var(--gold);background:var(--katbg);border-color:var(--gold-deep)}
  .kategori-pill{border:1.5px solid var(--katdark);color:var(--katdark);background:#fff;border-radius:999px;padding:7px 20px;font-weight:600;font-size:.95rem;white-space:nowrap;transition:background .2s,color .2s,border-color .2s}
  .kategori-item:hover .kategori-pill{background:var(--katblue);color:#fff;border-color:var(--katblue)}
  @media(max-width:760px){
    .kategori-item{flex:1 1 21%;min-width:0}
    .kategori-circle{width:74px;height:74px}
    .kategori-circle svg{width:36px;height:36px}
    .kategori-pill{padding:5px 12px;font-size:.8rem}
  }

  #populer{background:#fff}
</style>

<?php
$secSeru = $sections['event_seru'] ?? ['title' => 'Event Seru Untukmu', 'is_visible' => 1, 'item_limit' => 10];
$secKat = $sections['kategori'] ?? ['title' => 'Kategori Event', 'is_visible' => 1, 'item_limit' => 8];

$activeBanners = !empty($banners) ? $banners : [
  ['image' => null, 'title' => null, 'subtitle' => null, 'link_url' => null, 'bg_color' => '#15497A'],
  ['image' => null, 'title' => null, 'subtitle' => null, 'link_url' => null, 'bg_color' => '#15497A'],
  ['image' => null, 'title' => null, 'subtitle' => null, 'link_url' => null, 'bg_color' => '#15497A'],
];
while (count($activeBanners) < 3) {
  $activeBanners[] = ['image' => null, 'title' => null, 'subtitle' => null, 'link_url' => null, 'bg_color' => '#15497A'];
}
?>

<!-- 1. BANNER: tumpukan slide dinamis -->
<section class="banner-stack-sec" aria-label="Banner">
  <h1 class="sr-only">NOVA LIVE — Platform Tiket Event Indonesia</h1>
  <div class="banner-stack reveal">
    <?php foreach ($activeBanners as $idx => $b): 
      $posClass = ($idx === 0) ? 'back' : (($idx === 1) ? 'mid' : 'front');
      $hasImg = !empty($b['image']);
      $bgStyle = '';
      if ($hasImg) {
          $bgStyle = "background-image:url('" . base_url($b['image']) . "');background-size:cover;background-position:center;";
      } elseif (!empty($b['bg_color']) && $b['bg_color'] !== '#15497A') {
          $bgStyle = "background:" . htmlspecialchars($b['bg_color']) . ";";
      }
    ?>
      <div class="banner-slide <?= $posClass ?>" style="<?= $bgStyle ?>" aria-hidden="<?= $posClass === 'front' ? 'false' : 'true' ?>">
        <?php if (!empty($b['title']) || !empty($b['subtitle'])): ?>
          <div class="banner-caption" style="position:relative;z-index:2;padding:32px 38px;height:100%;box-sizing:border-box;display:flex;flex-direction:column;justify-content:center;color:#fff;background:linear-gradient(90deg,rgba(7,18,34,.65),transparent 75%);border-radius:inherit;">
            <?php if (!empty($b['title'])): ?>
              <h2 style="font-size:clamp(1.2rem,2.4vw,2rem);font-weight:800;margin:0 0 8px;color:#fff;"><?= htmlspecialchars($b['title']) ?></h2>
            <?php endif; ?>
            <?php if (!empty($b['subtitle'])): ?>
              <p style="font-size:clamp(0.85rem,1.2vw,1.05rem);color:#D9E6F2;margin:0 0 16px;max-width:600px;line-height:1.45;"><?= htmlspecialchars($b['subtitle']) ?></p>
            <?php endif; ?>
            <?php if (!empty($b['link_url'])): ?>
              <a href="<?= htmlspecialchars($b['link_url']) ?>" style="align-self:flex-start;background:var(--gold,#C5A880);color:var(--navy,#0E3254);font-weight:700;padding:8px 18px;border-radius:10px;text-decoration:none;font-size:0.92rem;display:inline-flex;align-items:center;gap:6px;"><?= htmlspecialchars($b['cta_text'] ?? 'Lihat Event') ?> &rarr;</a>
            <?php endif; ?>
          </div>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
    <button type="button" class="banner-arrow prev" id="bannerPrev" aria-label="Slide sebelumnya">‹</button>
    <button type="button" class="banner-arrow next" id="bannerNext" aria-label="Slide berikutnya">›</button>
    <div class="banner-dots" id="bannerDots" aria-hidden="true">
      <?php for ($i = 0; $i < count($activeBanners); $i++): ?>
        <span class="<?= $i === (count($activeBanners) - 1) ? 'on' : '' ?>"></span>
      <?php endfor; ?>
    </div>
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
  var total = slides.length;
  var pos = ["back", "mid", "front"];
  var cur = total >= 3 ? 2 : 0;
  function render(){
    slides.forEach(function(s, i){
      s.classList.remove("back", "mid", "front");
      if (total === 3) {
        s.classList.add(pos[(i - cur + 5) % 3]);
        s.style.display = "";
      } else {
        if (i === cur) {
          s.classList.add("front");
          s.style.display = "";
        } else if (i === (cur - 1 + total) % total) {
          s.classList.add("mid");
          s.style.display = "";
        } else if (i === (cur - 2 + total) % total) {
          s.classList.add("back");
          s.style.display = "";
        } else {
          s.style.display = "none";
        }
      }
    });
    dots.forEach(function(d, i){ d.classList.toggle("on", i === cur); });
  }
  render();

  /* Auto-slide: putar tumpukan tiap 4 detik (loop). */
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
    timer = setInterval(function(){ cur = (cur + 1) % total; render(); }, DELAY);
  }
  next.addEventListener("click", function(){ cur = (cur + 1) % total; render(); startAuto(); });
  prev.addEventListener("click", function(){ cur = (cur - 1 + total) % total; render(); startAuto(); });
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

<?php
$categorySvgs = [
  'Musik'     => '<svg width="46" height="46" viewBox="0 0 48 48" fill="none" aria-hidden="true"><g fill="currentColor"><rect x="29.5" y="2.5" width="5.5" height="19" rx="2" transform="rotate(38 32 12)"/><circle cx="16.5" cy="33.5" r="10.5"/><circle cx="23.5" cy="24.5" r="7.5"/></g><circle cx="18.5" cy="31.5" r="3.2" fill="var(--katbg)"/></svg>',
  'Pameran'   => '<svg width="46" height="46" viewBox="0 0 48 48" fill="none" aria-hidden="true"><rect x="7" y="6" width="34" height="19" rx="2.5" fill="currentColor"/><path d="M14 20.5l5-6.5 3.5 4 3-3.2 4.5 5.7z" fill="var(--katbg)"/><circle cx="31.5" cy="12" r="2" fill="var(--katbg)"/><g fill="currentColor"><circle cx="13" cy="31" r="2.7"/><circle cx="24" cy="31" r="2.7"/><circle cx="35" cy="31" r="2.7"/><path d="M7.5 41c.6-3.4 2.7-5 5.5-5s4.9 1.6 5.5 5zM18.5 41c.6-3.4 2.7-5 5.5-5s4.9 1.6 5.5 5zM29.5 41c.6-3.4 2.7-5 5.5-5s4.9 1.6 5.5 5z"/></g></svg>',
  'Teater'    => '<svg width="46" height="46" viewBox="0 0 48 48" fill="none" aria-hidden="true"><path d="M24 4c8.5 0 15 5.4 15 13.2C39 27 32.8 40 24 40S9 27 9 17.2C9 9.4 15.5 4 24 4z" fill="currentColor"/><circle cx="17.5" cy="17" r="2.6" fill="var(--katbg)"/><circle cx="30.5" cy="17" r="2.6" fill="var(--katbg)"/><path d="M16 25c2.4 3.4 5.2 5 8 5s5.6-1.6 8-5" stroke="var(--katbg)" stroke-width="3" stroke-linecap="round"/></svg>',
  'Olahraga'  => '<svg width="46" height="46" viewBox="0 0 48 48" fill="none" aria-hidden="true"><rect x="5" y="11" width="38" height="26" rx="3" fill="currentColor"/><path d="M24 11v26M5 18.5h6v11H5M43 18.5h-6v11h6" stroke="var(--katbg)" stroke-width="2.5" fill="none"/><circle cx="24" cy="24" r="5" fill="none" stroke="var(--katbg)" stroke-width="2.5"/></svg>',
  'Wisata'    => '<svg width="46" height="46" viewBox="0 0 48 48" fill="none" aria-hidden="true"><path d="M24 3.5c-6.3 0-10.8 4.4-10.8 10.2C13.2 21.5 24 33 24 33s10.8-11.5 10.8-19.3C34.8 7.9 30.3 3.5 24 3.5z" fill="currentColor"/><circle cx="24" cy="13.8" r="4.2" fill="var(--katbg)"/><path d="M8 38.5l10.5-3 11 3L40 35.5v6.5l-10.5 3-11-3L8 45z" fill="currentColor" opacity=".5"/></svg>',
  'Talkshow'  => '<svg width="46" height="46" viewBox="0 0 48 48" fill="none" aria-hidden="true"><rect x="19" y="5" width="10" height="20" rx="5" fill="currentColor"/><path d="M13.5 19a10.5 10.5 0 0021 0" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round"/><path d="M24 29.5V38M16.5 43h15" stroke="currentColor" stroke-width="3.5" stroke-linecap="round"/></svg>',
  'Workshop'  => '<svg width="46" height="46" viewBox="0 0 48 48" fill="none" aria-hidden="true"><rect x="6" y="6" width="36" height="23" rx="2.5" fill="currentColor"/><circle cx="24" cy="14.5" r="3" fill="var(--katbg)"/><path d="M16.5 25c.8-3.6 3.6-5.4 7.5-5.4s6.7 1.8 7.5 5.4z" fill="var(--katbg)"/><path d="M24 29v5M14 42l6.5-8M34 42l-6.5-8" stroke="currentColor" stroke-width="3" stroke-linecap="round" fill="none"/></svg>',
  'Kompetisi' => '<svg width="46" height="46" viewBox="0 0 48 48" fill="none" aria-hidden="true"><path d="M15 5h18v7.5c0 5-4 9-9 9s-9-4-9-9z" fill="currentColor"/><path d="M15 9H10a5.5 5.5 0 005 6.5M33 9h5a5.5 5.5 0 01-5 6.5" fill="none" stroke="currentColor" stroke-width="3"/><path d="M24 9.6l1.2 2.5 2.7.4-2 1.9.5 2.7-2.4-1.3-2.4 1.3.5-2.7-2-1.9 2.7-.4z" fill="var(--katbg)"/><path d="M24 21.5V27" stroke="currentColor" stroke-width="4"/><rect x="20" y="27" width="8" height="4.5" fill="currentColor"/><rect x="14.5" y="34" width="19" height="4.5" rx="2" fill="currentColor"/></svg>'
];
?>

<?php if (!empty($secKat['is_visible'])): ?>
<!-- 2. KATEGORI EVENT -->
<section class="section kategori-sec" id="populer" aria-labelledby="katTitle">
  <div class="wrap">
    <h2 class="kategori-title reveal" id="katTitle"><?= e($secKat['title'] ?? 'Kategori Event') ?></h2>
    <div class="kategori-row">
      <?php 
        $catList = $categories ?? [];
        if (!empty($secKat['item_limit']) && (int)$secKat['item_limit'] > 0) {
            $catList = array_slice($catList, 0, (int)$secKat['item_limit']);
        }
        foreach ($catList as $c): 
      ?>
        <a class="kategori-item reveal" href="<?= base_url('events?category=' . $c['id']) ?>" data-explore="<?= e($c['name']) ?>">
          <span class="kategori-circle"><?= $categorySvgs[$c['name']] ?? '' ?></span>
          <span class="kategori-pill"><?= e($c['name']) ?></span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if (!empty($secSeru['is_visible'])): ?>
<!-- 3. EVENT SERU UNTUKMU (Carousel 10 Event) -->
<section class="section alt" id="seru" aria-labelledby="seruTitle">
  <div class="wrap seru-wrap">
    <div class="section-head">
      <h2 id="seruTitle"><svg class="seru-icon" width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3 9v6h4l5 5V4L7 9H3z" fill="currentColor"/><path d="M16.5 8.5a5 5 0 010 7M19 6a8.5 8.5 0 010 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg><?= e($secSeru['title'] ?? 'Event Seru Untukmu') ?></h2>
    </div>
    <div class="seru-row reveal">
      <?php 
        $seruList = $eventsSeru ?? $seru ?? [];
        if (!empty($secSeru['item_limit']) && (int)$secSeru['item_limit'] > 0) {
            $seruList = array_slice($seruList, 0, (int)$secSeru['item_limit']);
        }
        foreach ($seruList as $e): 
      ?>
        <article class="card seru-card">
          <div class="seru-placeholder" aria-hidden="true">
            <?= strtoupper(mb_substr($e['title'], 0, 1)) ?>
          </div>
          <div class="seru-body">
            <p class="meta"><?= e(ucwords(strtolower($e['city_name'] ?? 'Indonesia'))) ?></p>
            <h3><a href="<?= base_url('events/show/' . $e['id']) ?>"><?= e($e['title']) ?></a></h3>
            <p class="meta">Oleh <?= e($e['organizer'] ?? 'NOVA Live') ?></p>
            <p class="seru-from">Mulai dari</p>
            <p class="seru-price"><?= (float)$e['price'] > 0 ? rupiah($e['price']) : 'Gratis' ?></p>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
    <button type="button" class="seru-arrow seru-prev" aria-label="Event sebelumnya">‹</button>
    <button type="button" class="seru-arrow seru-next" aria-label="Event berikutnya">›</button>
  </div>
</section>
<?php endif; ?>

<script>
/* Carousel Event Seru Untukmu: panah ‹ › */
(function initSeruCarousel() {
  var seruRow = document.querySelector(".seru-row");
  var seruPrev = document.querySelector(".seru-arrow.seru-prev");
  var seruNext = document.querySelector(".seru-arrow.seru-next");

  function updateSeruArrows() {
    if (!seruRow || !seruPrev || !seruNext) return;
    var maxScroll = seruRow.scrollWidth - seruRow.clientWidth;
    seruPrev.style.display = seruRow.scrollLeft <= 5 ? "none" : "flex";
    seruNext.style.display = seruRow.scrollLeft >= maxScroll - 5 ? "none" : "flex";
  }

  if (seruRow && seruNext) {
    seruNext.addEventListener("click", function (e) {
      e.preventDefault();
      var card = seruRow.querySelector(".seru-card");
      var step = card ? card.offsetWidth + 20 : 420;
      seruRow.scrollBy({ left: step, behavior: "smooth" });
      setTimeout(updateSeruArrows, 350);
    });
  }
  if (seruRow && seruPrev) {
    seruPrev.addEventListener("click", function (e) {
      e.preventDefault();
      var card = seruRow.querySelector(".seru-card");
      var step = card ? card.offsetWidth + 20 : 420;
      seruRow.scrollBy({ left: -step, behavior: "smooth" });
      setTimeout(updateSeruArrows, 350);
    });
  }

  if (seruRow) {
    seruRow.addEventListener("scroll", updateSeruArrows, { passive: true });
    window.addEventListener("resize", updateSeruArrows);
    updateSeruArrows();
  }
})();
</script>

<script>
/* Reveal */
(function initReveals() {
  function setupReveals() {
    var reveals = document.querySelectorAll(".reveal");
    if ("IntersectionObserver" in window && !window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
      var io = new IntersectionObserver(function(entries) {
        entries.forEach(function(en) {
          if (en.isIntersecting) {
            en.target.classList.add("visible");
            io.unobserve(en.target);
          }
        });
      }, { threshold: 0.1 });
      reveals.forEach(function(el) { io.observe(el); });
    } else {
      reveals.forEach(function(el) { el.classList.add("visible"); });
    }
  }
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", setupReveals);
  } else {
    setupReveals();
  }
})();
</script>
