<?php
/**
 * Shared Dashboard Sidebar & Shell Scripts
 * NOVA LIVE MVC
 */
$currentUser = $currentUser ?? ['full_name' => 'Admin NOVA LIVE', 'role' => 'admin'];
$userInitials = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $currentUser['full_name'] ?? 'AD'), 0, 2));
if (strlen($userInitials) < 2) $userInitials = 'AD';
?>
<!-- Scrim mobile -->
<div class="scrim" id="scrim"></div>

<!-- ================= SIDEBAR ================= -->
<aside class="sidebar" id="sidebar" aria-label="Navigasi dashboard">
  <span class="sb-glow" aria-hidden="true"></span>
  <div class="brand">
    <button type="button" class="brand-logo-btn" id="ticketLogoBtn" aria-label="Putar ulang animasi logo NOVA LIVE" title="Klik untuk memutar ulang animasi logo">
    <svg id="ticketLogo" viewBox="0 30 1300 540" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Logo tiket NOVALIVE">
      <defs>
        <linearGradient id="nv-dark" x1="60" y1="70" x2="780" y2="540" gradientUnits="userSpaceOnUse">
          <stop offset="0" stop-color="#2E8CB9"/>
          <stop offset="0.45" stop-color="#1F6AA3"/>
          <stop offset="1" stop-color="#17508A"/>
        </linearGradient>
        <linearGradient id="nv-light" x1="690" y1="400" x2="1170" y2="300" gradientUnits="userSpaceOnUse">
          <stop offset="0" stop-color="#2A72B0"/>
          <stop offset="0.35" stop-color="#3F95CF"/>
          <stop offset="1" stop-color="#58B5E1"/>
        </linearGradient>
        <linearGradient id="nv-shine" x1="0" y1="0" x2="1" y2="0">
          <stop offset="0" stop-color="#fff" stop-opacity="0"/>
          <stop offset="0.5" stop-color="#fff" stop-opacity="0.55"/>
          <stop offset="1" stop-color="#fff" stop-opacity="0"/>
        </linearGradient>
        <path id="nv-dark-shape" d="M 42 352 L 105 285 A 106 106 0 0 0 258 135 L 325 62 L 840 535 L 300 535 C 176 536 96 438 42 352 Z"/>
        <path id="nv-light-shape" d="M 678 365 L 798 248 L 885 248 C 888 285 905 292 925 292 C 990 292 1030 262 1075 248 L 1183 248 C 1130 330 1060 480 900 492 C 800 495 720 430 678 365 Z"/>
        <clipPath id="nv-clip">
          <use href="#nv-dark-shape"/>
          <use href="#nv-light-shape"/>
        </clipPath>
      </defs>

      <g data-part="root">
        <path data-part="ribbon" d="M 385 68 L 665 382 C 735 466 803 514 897 512 C 1022 510 1120 384 1230 258"
              fill="none" stroke="#E8D5A1" stroke-width="44" pathLength="1" stroke-dasharray="1" stroke-dashoffset="1"/>

        <g data-part="dark" opacity="0">
          <use href="#nv-dark-shape" fill="url(#nv-dark)" stroke="url(#nv-dark)" stroke-width="5" stroke-linejoin="round"/>
          <g fill="#FAFAFC">
            <g data-part="dot" data-cx="395" data-cy="255"><circle r="28"/></g>
            <g data-part="dot" data-cx="312" data-cy="340"><circle r="28"/></g>
            <g data-part="dot" data-cx="228" data-cy="424"><circle r="28"/></g>
          </g>
        </g>

        <g data-part="light" opacity="0">
          <use href="#nv-light-shape" fill="url(#nv-light)" stroke="url(#nv-light)" stroke-width="4" stroke-linejoin="round"/>
          <g fill="#FAFAFC">
            <g data-part="oval" data-cx="773" data-cy="355"><ellipse rx="27" ry="16" transform="rotate(-8)"/></g>
            <g data-part="oval" data-cx="875" data-cy="359"><ellipse rx="29" ry="16" transform="rotate(-8)"/></g>
            <g data-part="oval" data-cx="975" data-cy="362"><ellipse rx="27" ry="16" transform="rotate(-8)"/></g>
          </g>
        </g>

        <g clip-path="url(#nv-clip)">
          <rect data-part="shine" x="-120" y="-120" width="240" height="900" fill="url(#nv-shine)" opacity="0"/>
        </g>
      </g>
    </svg>
    </button>
    <div class="brand-txt">
      <div class="brand-name">NOVA <em>LIVE</em></div>
      <div class="brand-sub">Dashboard Penyelenggara</div>
    </div>
  </div>

  <nav class="nav" id="mainNav">
    <span class="nav-glide" aria-hidden="true"></span>
    <div class="nav-label">Menu Utama</div>
    <a href="<?= base_url('dashboard') ?>" class="nav-item <?= ($activeNav ?? '') === 'ringkasan' ? 'active' : '' ?>" title="Ringkasan">
      <svg class="ic" viewBox="0 0 24 24"><rect x="3" y="3" width="7.5" height="7.5" rx="1.5"/><rect x="13.5" y="3" width="7.5" height="7.5" rx="1.5"/><rect x="3" y="13.5" width="7.5" height="7.5" rx="1.5"/><rect x="13.5" y="13.5" width="7.5" height="7.5" rx="1.5"/></svg>
      <span class="nav-txt">Ringkasan</span>
    </a>
    <a href="<?= base_url('dashboard/events') ?>" class="nav-item <?= ($activeNav ?? '') === 'events' ? 'active' : '' ?>" title="Event Saya">
      <svg class="ic" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4"/></svg>
      <span class="nav-txt">Event Saya</span>
      <span class="nav-badge" id="navEventCount"><?= (int)($sidebarCounts['events'] ?? 10) ?></span>
    </a>
    <a href="<?= base_url('dashboard/orders') ?>" class="nav-item <?= ($activeNav ?? '') === 'orders' ? 'active' : '' ?>" title="Pesanan">
      <svg class="ic" viewBox="0 0 24 24"><path d="M4 5h2l2.4 11.2a1.5 1.5 0 0 0 1.5 1.2h7.9a1.5 1.5 0 0 0 1.5-1.2L20.5 8H7"/><circle cx="10" cy="21" r="1"/><circle cx="18" cy="21" r="1"/></svg>
      <span class="nav-txt">Pesanan</span>
      <span class="nav-badge" id="navOrderCount"><?= (int)($sidebarCounts['orders'] ?? 8) ?></span>
    </a>
    <button type="button" class="nav-item js-soon" data-feature="Keuangan" title="Keuangan">
      <svg class="ic" viewBox="0 0 24 24"><rect x="3" y="6" width="18" height="13" rx="2"/><path d="M3 10.5h18M7 15h4"/></svg>
      <span class="nav-txt">Keuangan</span>
    </button>

    <div class="nav-label">Operasional</div>
    <button type="button" class="nav-item js-soon" data-feature="Check-in" title="Check-in">
      <svg class="ic" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><path d="M14 14h3v3h-3zM21 14v1M14 21h1M18.5 18.5H21V21h-2.5z"/></svg>
      <span class="nav-txt">Check-in</span>
    </button>
    <button type="button" class="nav-item js-soon" data-feature="Refund" title="Refund">
      <svg class="ic" viewBox="0 0 24 24"><path d="M9 14L4 9l5-5"/><path d="M4 9h10.5a5.5 5.5 0 0 1 0 11H11"/></svg>
      <span class="nav-txt">Refund</span>
      <span class="nav-badge" id="navRefundCount"><?= (int)($sidebarCounts['refund'] ?? 2) ?></span>
    </button>
    <button type="button" class="nav-item js-soon" data-feature="Stok Tiket" title="Stok Tiket">
      <svg class="ic" viewBox="0 0 24 24"><path d="M4 8l8-4 8 4v8l-8 4-8-4z"/><path d="M4 8l8 4 8-4M12 12v8"/></svg>
      <span class="nav-txt">Stok Tiket</span>
    </button>

    <div class="nav-label">Homepage</div>
    <button type="button" class="nav-item js-soon" data-feature="Banner" title="Banner">
      <svg class="ic" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"/><circle cx="9" cy="10" r="1.6"/><path d="M3 17l5-4 4 3 4-4 5 5"/></svg>
      <span class="nav-txt">Banner</span>
    </button>
    <button type="button" class="nav-item js-soon" data-feature="Tampilan Homepage" title="Tampilan Homepage">
      <svg class="ic" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M3 9.5h18M8 4v5.5"/></svg>
      <span class="nav-txt">Tampilan Homepage</span>
    </button>
    <button type="button" class="nav-item js-soon" data-feature="Kategori" title="Kategori">
      <svg class="ic" viewBox="0 0 24 24"><path d="M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
      <span class="nav-txt">Kategori</span>
    </button>
    <button type="button" class="nav-item js-soon" data-feature="Kota" title="Kota">
      <svg class="ic" viewBox="0 0 24 24"><path d="M12 21s-7-5.6-7-11a7 7 0 0 1 14 0c0 5.4-7 11-7 11z"/><circle cx="12" cy="10" r="2.6"/></svg>
      <span class="nav-txt">Kota</span>
    </button>
    <button type="button" class="nav-item js-soon" data-feature="E-Tiket" title="E-Tiket">
      <svg class="ic" viewBox="0 0 24 24"><path d="M3 8a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v1.5a3 3 0 0 0 0 5V16a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-1.5a3 3 0 0 0 0-5z"/><path d="M14 7v2M14 11v2M14 15v2"/></svg>
      <span class="nav-txt">E-Tiket</span>
    </button>

    <div class="nav-label">Komunikasi &amp; Laporan</div>
    <button type="button" class="nav-item js-soon" data-feature="Notifikasi" title="Notifikasi">
      <svg class="ic" viewBox="0 0 24 24"><path d="M22 2L11 13"/><path d="M22 2l-7 20-4-9-9-4z"/></svg>
      <span class="nav-txt">Notifikasi</span>
    </button>
    <button type="button" class="nav-item js-soon" data-feature="Analitik" title="Analitik">
      <svg class="ic" viewBox="0 0 24 24"><path d="M5 20v-6M11 20V6M17 20v-9"/><path d="M3 20h18"/></svg>
      <span class="nav-txt">Analitik</span>
    </button>
    <button type="button" class="nav-item js-soon" data-feature="Log Aktivitas" title="Log Aktivitas">
      <svg class="ic" viewBox="0 0 24 24"><path d="M8.5 6H21M8.5 12H21M8.5 18H21"/><circle cx="4" cy="6" r="1.1"/><circle cx="4" cy="12" r="1.1"/><circle cx="4" cy="18" r="1.1"/></svg>
      <span class="nav-txt">Log Aktivitas</span>
    </button>

    <div class="nav-label">Lainnya</div>
    <button type="button" class="nav-item js-soon" data-feature="Promosi" title="Promosi">
      <svg class="ic" viewBox="0 0 24 24"><path d="M3 11V7a2 2 0 0 1 2-2h4l9 9-4 4-9-9z"/><circle cx="8" cy="9" r="1.4"/></svg>
      <span class="nav-txt">Promosi</span>
    </button>
    <button type="button" class="nav-item js-soon" data-feature="Pengaturan" title="Pengaturan">
      <svg class="ic" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3.2"/><path d="M19 12a7 7 0 0 0-.14-1.4l2-1.55-2-3.46-2.36.95a7 7 0 0 0-2.42-1.4L13.7 2.6h-3.4l-.38 2.54a7 7 0 0 0-2.42 1.4l-2.36-.95-2 3.46 2 1.55a7 7 0 0 0 0 2.8l-2 1.55 2 3.46 2.36-.95a7 7 0 0 0 2.42 1.4l.38 2.54h3.4l.38-2.54a7 7 0 0 0 2.42-1.4l2.36.95 2-3.46-2-1.55c.1-.45.14-.92.14-1.4z"/></svg>
      <span class="nav-txt">Pengaturan</span>
    </button>
  </nav>

  <div class="side-foot">
    <div class="org-card">
      <div class="avatar" id="orgAvatar"><?= e($userInitials) ?></div>
      <div class="org-txt">
        <div class="org-name" id="orgName"><?= e($currentUser['full_name'] ?? 'Admin') ?></div>
        <div class="org-role"><?= e(ucfirst($currentUser['role'] ?? 'Admin')) ?> · Penyelenggara</div>
      </div>
    </div>
    <a href="<?= base_url('/') ?>" class="logout js-preview" style="margin-top:12px;text-decoration:none;" title="Pratinjau Homepage">
      <svg class="ic" viewBox="0 0 24 24" style="width:17px;height:17px"><path d="M2 12s3.5-6.5 10-6.5S22 12 22 12s-3.5 6.5-10 6.5S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg>
      <span class="btn-txt">Pratinjau Homepage</span>
    </a>
    <a href="<?= base_url('auth/logout') ?>" class="logout" id="logoutBtn" style="text-decoration:none;" title="Keluar Dashboard">
      <svg class="ic" viewBox="0 0 24 24" style="width:17px;height:17px"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg>
      <span class="btn-txt">Keluar Dashboard</span>
    </a>
  </div>
</aside>

<!-- Toast notification element -->
<div class="toast" id="toast" role="status"></div>

<script>
/* ===== TOAST NOTIFICATION ===== */
var toastTimer = null;
function toast(msg) {
  var t = document.getElementById("toast");
  if (!t) return;
  t.innerHTML = '<span class="ok">✓</span> ' + msg;
  t.classList.add("show");
  clearTimeout(toastTimer);
  toastTimer = setTimeout(function() {
    t.classList.remove("show");
  }, 3200);
}

/* ===== 14 FITUR SEGERA HADIR ===== */
document.addEventListener("click", function(e) {
  var soon = e.target.closest(".js-soon");
  if (soon) {
    e.preventDefault();
    var feat = soon.getAttribute("data-feature") || "Fitur ini";
    toast("Fitur " + feat + " segera hadir di NOVA LIVE.");
  }
});

/* ===== TOPBAR: NOTIFIKASI DROPDOWN & MENU TOGGLE ===== */
(function(){
  var notifBtn = document.getElementById("notifBtn");
  var notifPanel = document.getElementById("notifPanel");
  var notifDot = document.getElementById("notifDot");
  if (notifBtn && notifPanel) {
    notifBtn.addEventListener("click", function(e) {
      e.stopPropagation();
      notifPanel.classList.toggle("open");
      if (notifPanel.classList.contains("open") && notifDot) {
        notifDot.style.display = "none";
      }
    });
    document.addEventListener("click", function(e) {
      if (!e.target.closest(".notif-wrap")) {
        notifPanel.classList.remove("open");
      }
    });
  }

  var menuBtn = document.getElementById("menuBtn");
  var sb = document.getElementById("sidebar");
  var scrim = document.getElementById("scrim");
  if (menuBtn && sb && scrim) {
    menuBtn.addEventListener("click", function() {
      var open = sb.classList.toggle("open");
      sb.classList.toggle("sb-open", open);
      scrim.classList.toggle("show", open);
    });
    scrim.addEventListener("click", function() {
      sb.classList.remove("open");
      sb.classList.remove("sb-open");
      scrim.classList.remove("show");
    });
  }

  var gs = document.getElementById("globalSearch");
  if (gs) {
    gs.addEventListener("keydown", function(e) {
      if (e.key !== "Enter") return;
      var q = this.value.trim();
      if (!q) return;
      window.location.href = "<?= base_url('dashboard/events') ?>?search=" + encodeURIComponent(q);
    });
  }
})();

/* ===== SIDEBAR COLLAPSED (DESKTOP): CLICK TO EXPAND ===== */
(function(){
  var sb = document.getElementById("sidebar");
  if (!sb) return;
  sb.addEventListener("click", function(e) {
    if (window.innerWidth <= 900) return;
    if (!sb.classList.contains("sb-open")) {
      e.preventDefault();
      e.stopPropagation();
      sb.classList.add("sb-open");
      return;
    }
    if (!e.target.closest("button, a, input, select, textarea, [role='button']")) {
      sb.classList.remove("sb-open");
    }
  }, true);

  document.addEventListener("click", function(e) {
    if (sb.classList.contains("sb-open") && !e.target.closest("#sidebar")) {
      sb.classList.remove("sb-open");
    }
  });

  document.addEventListener("keydown", function(e) {
    if (e.key === "Escape") {
      sb.classList.remove("sb-open");
    }
  });
})();

/* ===== LOGO TIKET NOVALIVE DI SIDEBAR: ANIMASI LOOPING OTOMATIS ===== */
(function(){
  var DURATION = 4.2;
  var PAUSE = 1200;

  var clamp = function(x){ return Math.min(1, Math.max(0, x)); };
  var prog = function(t, a, b){ return clamp((t - a) / (b - a)); };
  var ease = {
    outCubic: function(x){ return 1 - Math.pow(1 - x, 3); },
    inOutCubic: function(x){ return x < 0.5 ? 4 * x * x * x : 1 - Math.pow(-2 * x + 2, 3) / 2; },
    outBack: function(x, s){ s = (s === undefined ? 1.70158 : s); var c = s + 1; return 1 + c * Math.pow(x - 1, 3) + s * Math.pow(x - 1, 2); }
  };

  var svg = document.getElementById("ticketLogo");
  if (!svg) return;
  var part = function(name){ return svg.querySelector('[data-part="' + name + '"]'); };
  var parts = {
    root: part("root"), ribbon: part("ribbon"), dark: part("dark"), light: part("light"), shine: part("shine"),
    dots: Array.prototype.slice.call(svg.querySelectorAll('[data-part="dot"]')),
    ovals: Array.prototype.slice.call(svg.querySelectorAll('[data-part="oval"]'))
  };

  function pop(el, t, t0) {
    var cx = +el.getAttribute("data-cx"), cy = +el.getAttribute("data-cy");
    var u = prog(t, t0, t0 + 0.42);
    var s = u <= 0 ? 0 : ease.outBack(u, 2.4);
    el.setAttribute("transform", "translate(" + cx + " " + cy + ") scale(" + Math.max(s, 0.0001) + ")");
  }

  function apply(t) {
    var p = 1 + 0.04 * ease.inOutCubic(prog(t, 0.2, DURATION));
    parts.root.setAttribute("transform", "translate(650 300) scale(" + p + ") translate(-650 -300)");

    // 1. pita emas
    parts.ribbon.setAttribute("stroke-dashoffset", (1 - ease.inOutCubic(prog(t, 0.25, 1.3))).toFixed(4));

    // 2. tiket biru gelap
    var u2 = prog(t, 0.6, 1.45), e2 = ease.outCubic(u2);
    parts.dark.setAttribute("transform",
      "translate(" + (450 - 260 * (1 - e2)) + " " + (300 + 150 * (1 - e2)) + ") scale(" + (0.86 + 0.14 * e2) + ") translate(-450 -300)");
    parts.dark.setAttribute("opacity", clamp(u2 * 3).toFixed(3));

    // 3. lipatan biru muda
    var u3 = prog(t, 0.95, 1.85), e3 = ease.outCubic(u3);
    parts.light.setAttribute("transform",
      "translate(" + (678 + 90 * (1 - e3)) + " " + (365 - 50 * (1 - e3)) + ") rotate(" + (-16 * (1 - e3)) + ") scale(" + (0.12 + 0.88 * e3) + " " + (0.6 + 0.4 * e3) + ") translate(-678 -365)");
    parts.light.setAttribute("opacity", clamp(u3 * 4).toFixed(3));

    // 4. titik-titik
    parts.dots.forEach(function(el, i){ pop(el, t, 1.45 + 0.1 * i); });
    parts.ovals.forEach(function(el, i){ pop(el, t, 1.9 + 0.1 * i); });

    // 5. kilau
    var u5 = prog(t, 2.15, 3.0), e5 = ease.inOutCubic(u5);
    parts.shine.setAttribute("transform", "translate(" + (-250 + e5 * 1850) + " 0) rotate(20 0 300)");
    parts.shine.setAttribute("opacity", (u5 > 0 && u5 < 1) ? 1 : 0);
  }

  var reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  var raf = 0, loopTimer = 0;

  function play() {
    cancelAnimationFrame(raf);
    clearTimeout(loopTimer);
    if (reduceMotion) { apply(DURATION); return; }
    if (!svg.getClientRects().length) { loopTimer = setTimeout(play, 600); return; }
    var start = 0;
    apply(0);
    var tick = function(now) {
      if (!start) start = now;
      var t = (now - start) / 1000;
      apply(Math.min(t, DURATION));
      if (t < DURATION) {
        raf = requestAnimationFrame(tick);
      } else {
        loopTimer = setTimeout(play, PAUSE);
      }
    };
    raf = requestAnimationFrame(tick);
  }

  var btn = document.getElementById("ticketLogoBtn");
  if (btn) btn.addEventListener("click", play);
  play();
})();

/* ===== SIDEBAR: HIGHLIGHT CREAM, GLOW & GLIDE PILL ===== */
(function(){
  var sb = document.getElementById("sidebar");
  var nav = document.getElementById("mainNav");
  if (!sb || !nav) return;
  var glow = sb.querySelector(".sb-glow");
  var glide = nav.querySelector(".nav-glide");
  if (!glow || !glide) return;
  var mq = window.matchMedia("(min-width:901px)");
  var fine = window.matchMedia("(hover:hover) and (pointer:fine)");
  var current = null;

  function desktop(){ return mq.matches && fine.matches; }

  function place(item, instant){
    if (!item) return;
    if (instant) glide.style.transition = "none";
    glide.style.width = item.offsetWidth + "px";
    glide.style.height = item.offsetHeight + "px";
    glide.style.transform = "translate(" + item.offsetLeft + "px," + item.offsetTop + "px)";
    glide.style.opacity = "1";
    if (instant) { void glide.offsetWidth; glide.style.transition = ""; }
    if (current && current !== item) current.classList.remove("glide-on");
    current = item;
    item.classList.add("glide-on");
    nav.classList.add("glide-live");
  }

  function hide(){
    glide.style.opacity = "0";
    if (current) { current.classList.remove("glide-on"); current = null; }
    nav.classList.remove("glide-live");
  }

  function pinToActive(instant){
    var act = nav.querySelector(".nav-item.active");
    if (act) { place(act, instant); return; }
    hide();
  }

  var pointer = null, glowQueued = false;
  sb.addEventListener("mousemove", function(e){
    if (!desktop()) return;
    pointer = { x: e.clientX, y: e.clientY };
    if (glowQueued) return;
    glowQueued = true;
    requestAnimationFrame(function(){
      glowQueued = false;
      if (!pointer) return;
      var r = sb.getBoundingClientRect();
      glow.style.transform = "translate(" + (pointer.x - r.left) + "px," + (pointer.y - r.top) + "px) translate(-50%,-50%)";
    });
  });
  sb.addEventListener("mouseenter", function(){ if (desktop()) sb.classList.add("sb-hover"); });
  sb.addEventListener("mouseleave", function(){
    sb.classList.remove("sb-hover");
    pointer = null;
  });

  var navScrolling = false, scrollTimer = 0;
  Array.prototype.forEach.call(nav.querySelectorAll(".nav-item"), function(it){
    it.addEventListener("mouseenter", function(){ if (desktop() && !navScrolling) place(it, false); });
  });

  nav.addEventListener("scroll", function(){
    navScrolling = true;
    clearTimeout(scrollTimer);
    scrollTimer = setTimeout(function(){
      navScrolling = false;
      if (!desktop()) return;
      if (pointer && sb.classList.contains("sb-hover")) {
        var el = document.elementFromPoint(pointer.x, pointer.y);
        var it = el && el.closest ? el.closest(".nav-item") : null;
        if (it && nav.contains(it)) { place(it, false); return; }
      }
      pinToActive(false);
    }, 140);
  }, { passive: true });

  nav.addEventListener("mouseleave", function(){ if (desktop()) pinToActive(false); });
  nav.addEventListener("click", function(){ if (desktop()) pinToActive(false); });

  if (window.MutationObserver) {
    var lastOpen = sb.classList.contains("sb-open");
    new MutationObserver(function(){
      var open = sb.classList.contains("sb-open");
      if (open === lastOpen) return;
      lastOpen = open;
      if (!desktop()) return;
      setTimeout(function(){ pinToActive(false); }, 340);
    }).observe(sb, { attributes: true, attributeFilter: ["class"] });
  }

  window.addEventListener("resize", function(){
    if (!desktop()) { hide(); return; }
    pinToActive(true);
  });

  if (desktop()) pinToActive(true);
  window.addEventListener("load", function(){ if (desktop()) pinToActive(true); });
})();
</script>
