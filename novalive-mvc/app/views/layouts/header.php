<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <meta name="color-scheme" content="light">
  <title><?= isset($pageTitle) ? e($pageTitle) : 'NOVA LIVE: Homepage' ?></title>
  <meta name="description" content="NOVA LIVE: platform tiket event Indonesia. Konser, pameran, wahana, dan olahraga.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@600;700;800&family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root{
      --bg:#FFFFFF; --card:#FFFFFF; --ink:#0E2A47; --muted:#4A607A;
      --navy:#15497A; --navy2:#0E3254; --navy-deep:#0E3254; --brand:#23649A; --sky:#509FC8; --sky-soft:#E6F2F8;
      --accent:#23649A; --accent-ink:#15497A; --gold:#EAD4A2; --gold-deep:#D4B475; --success:#15803D;
      --cream:#F5EFE0; --nav-bg:#F5F1E6;
      --line:#E2DDD0; --chipbg:#EFE9DA;
      --font-display:"Plus Jakarta Sans","Inter",system-ui,sans-serif;
      --font-body:"Inter",system-ui,sans-serif;
      --radius:16px;
      --shadow:0 1px 3px rgba(14,50,84,.08),0 4px 16px rgba(14,50,84,.06);
      --shadow-lg:0 8px 28px rgba(14,50,84,.14);
      --wrap:1180px;
      --navh:73px;
    }
    *{box-sizing:border-box}
    html{scroll-behavior:smooth}
    body{margin:0;background:var(--bg);color:var(--ink);font-family:var(--font-body);line-height:1.6;display:flex;flex-direction:column;min-height:100vh}
    img{max-width:100%;display:block}
    a{color:inherit;text-decoration:none}
    a[href],button,summary,select,label[for],[role="button"],[role="tab"],[role="switch"]{cursor:pointer}
    :disabled,[aria-disabled="true"]{cursor:not-allowed}
    h1,h2,h3,h4,h5,.font-heading{font-family:var(--font-display);line-height:1.2;margin:0 0 12px;letter-spacing:-.01em;color:var(--navy2)}
    h2{font-size:clamp(1.55rem,3vw,2.15rem);font-weight:800}
    h3{font-size:1.15rem;font-weight:700}
    p{margin:0 0 12px}
    button,[role="button"],select,summary,input:not([type="checkbox"],[type="radio"]){min-height:2.25rem}
    :focus-visible{outline:3px solid var(--brand);outline-offset:2px;border-radius:6px}

    /* NAVBAR: struktur referensi mockup: logo | Kategori | search lebar | partner | Akun */
    .topnav{background:var(--navy2);border-bottom:1px solid rgba(255,255,255,.16);position:sticky;top:0;z-index:100;color:#fff}
    .topnav-row{max-width:1440px;margin:0 auto;padding:12px 24px;display:flex;align-items:center;gap:18px}
    .logo{display:inline-flex;align-items:center;gap:10px;font-family:var(--font-display);font-weight:800;font-size:1.65rem;color:#fff;text-decoration:none;white-space:nowrap;letter-spacing:-.01em;flex-shrink:0}
    .logo .logo-live{color:var(--gold)}
    .nav-ticket-svg{height:40px;width:auto;display:block;overflow:visible;flex-shrink:0}
    .logo-img{height:36px;width:auto;display:block;max-width:100%;object-fit:contain}
    .nav-cat{position:relative}
    .nav-cat-btn{display:flex;align-items:center;gap:8px;background:none;border:0;font:700 1rem var(--font-body);color:#EAF3FA;cursor:pointer;padding:10px 6px;white-space:nowrap}
    .nav-cat-btn:hover{color:#fff}
    .nav-cat-chev{transition:transform .2s}
    .nav-cat-btn[aria-expanded="true"] .nav-cat-chev{transform:rotate(180deg)}

    /* MEGA MENU KATEGORI: panel lebar di bawah navbar, grid 4 kolom + bagian Jelajahi Event */
    .mega-menu{position:absolute;top:calc(100% + 10px);left:50%;transform:translateX(-50%);width:min(1080px,calc(100vw - 32px));background:var(--card);border:1px solid var(--line);border-radius:18px;box-shadow:var(--shadow-lg);padding:26px 28px 24px;display:none;z-index:90}
    .mega-menu.open{display:block;animation:mega-in .18s ease}
    @keyframes mega-in{from{opacity:0;transform:translateX(-50%) translateY(-6px)}to{opacity:1;transform:translateX(-50%) translateY(0)}}
    @media(prefers-reduced-motion:reduce){.mega-menu.open{animation:none}.nav-cat-chev{transition:none}}
    .mega-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:4px 16px}
    .mega-item{display:flex;align-items:center;gap:14px;padding:11px 12px;border-radius:12px;color:var(--ink);font-weight:600;font-size:1rem;text-decoration:none;min-height:3.5rem}
    .mega-item:hover{background:var(--sky-soft)}
    .mega-ic{width:48px;height:48px;flex-shrink:0;border-radius:12px;background:var(--sky-soft);color:var(--brand);display:flex;align-items:center;justify-content:center;transition:background .2s,color .2s}
    .mega-item:hover .mega-ic{background:var(--gold);color:var(--navy2)}
    .mega-explore{border-top:1px solid var(--line);margin-top:20px;padding-top:20px}
    .mega-explore h3{font-size:1.3rem;color:var(--navy);margin:0 0 14px}
    .mega-explore-card{display:flex;align-items:center;gap:16px;background:var(--cream);border:1px solid var(--line);border-radius:14px;padding:16px 20px;text-decoration:none;color:var(--ink);min-height:3.5rem}
    .mega-explore-card:hover{border-color:var(--gold-deep);box-shadow:var(--shadow)}
    .mega-explore-ic{width:48px;height:48px;flex-shrink:0;border-radius:12px;background:var(--navy);color:var(--gold);display:flex;align-items:center;justify-content:center}
    .mega-explore-txt strong{display:block;font-family:var(--font-display);font-size:1.05rem;color:var(--navy);line-height:1.3}
    .mega-explore-txt small{color:var(--muted);font-size:.9rem}
    .mega-arrow{margin-left:auto;font-size:1.7rem;line-height:1;color:var(--brand);flex-shrink:0}
    @media(max-width:760px){
      .mega-menu{padding:20px 16px}
      .mega-grid{grid-template-columns:repeat(2,1fr);gap:4px 8px}
      .mega-item{gap:10px;padding:9px 8px;font-size:.92rem}
      .mega-ic{width:40px;height:40px;border-radius:10px}
      .mega-explore-card{padding:14px 16px}
    }

    /* SEARCH NAVBAR + 3D FLIP HINT */
    .nav-search{flex:1;display:flex;align-items:center;background:#fff;border:1px solid var(--line);border-radius:999px;padding:0 6px 0 20px;height:48px;min-width:0;position:relative}
    .nav-search input{flex:1;border:0;background:transparent;outline:none;font:400 .95rem var(--font-body);min-width:0;color:var(--navy)}
    .nav-search input::placeholder{color:var(--muted)}
    .nav-search-hint{position:absolute;left:20px;right:52px;top:50%;transform:translateY(-50%);font:500 .95rem var(--font-body);color:var(--navy);pointer-events:none;white-space:nowrap;overflow:hidden}
    .nav-search-hint.hidden{display:none}
    .nav-search-hint .hint-prefix{white-space:pre;color:var(--navy);font-weight:500}
    .nav-search-hint .hint-word{display:inline-block;transform-origin:50% 50%;color:var(--navy);font-weight:700}
    .nav-search-hint .hint-word.flip-out{animation:hint-flip-out .5s ease-in forwards}
    .nav-search-hint .hint-word.flip-in{animation:hint-flip-in .5s ease-out forwards}
    @keyframes hint-flip-out{from{transform:perspective(400px) rotateX(0deg);opacity:1}to{transform:perspective(400px) rotateX(90deg);opacity:0}}
    @keyframes hint-flip-in{from{transform:perspective(400px) rotateX(-90deg);opacity:0}to{transform:perspective(400px) rotateX(0deg);opacity:1}}
    @media(prefers-reduced-motion:reduce){.nav-search-hint{display:none}.nav-search-hint .hint-word.flip-out,.nav-search-hint .hint-word.flip-in{animation:none}}
    .nav-search button{width:40px;height:40px;border:0;background:transparent;cursor:pointer;color:var(--navy);display:flex;align-items:center;justify-content:center;flex-shrink:0}

    /* KANAN NAVBAR: Partner + Account / Logged-in User */
    .nav-partner{display:flex;align-items:center;gap:8px;font-weight:700;color:#DCEBF5;text-decoration:none;white-space:nowrap;padding:10px 4px}
    .nav-partner:hover{color:#fff}
    .nav-partner svg{color:var(--gold)}
    .nav-account{background:var(--gold);color:var(--navy2);border-radius:999px;padding:11px 20px;display:flex;align-items:center;gap:8px;font-weight:700;text-decoration:none;white-space:nowrap;border:0;cursor:pointer;font-family:var(--font-body);font-size:1rem}
    .nav-account:hover{background:var(--gold-deep)}
    .nav-auth-group{display:flex;align-items:center;gap:10px;flex-shrink:0}
    .nav-user-chip{display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,.12);color:#fff;padding:8px 14px;border-radius:999px;font-weight:700;font-size:.92rem;white-space:nowrap;text-decoration:none}
    .nav-user-chip:hover{background:rgba(255,255,255,.2)}
    .nav-btn-dash{background:var(--gold);color:var(--navy2);border-radius:999px;padding:9px 16px;font-weight:700;font-size:.92rem;text-decoration:none;white-space:nowrap;display:inline-flex;align-items:center;gap:6px}
    .nav-btn-dash:hover{background:var(--gold-deep)}
    .nav-btn-logout{background:transparent;color:#fff;border:1px solid rgba(255,255,255,.32);border-radius:999px;padding:8px 15px;font-weight:700;font-size:.92rem;text-decoration:none;white-space:nowrap;display:inline-flex;align-items:center}
    .nav-btn-logout:hover{background:rgba(255,255,255,.12);border-color:#fff}

    /* BURGER & DRAWER MOBILE */
    .burger{display:none;background:none;border:0;font-size:1.5rem;cursor:pointer;color:#fff;padding:8px;min-width:2.75rem}
    .drawer{position:fixed;top:0;right:0;width:300px;max-width:85vw;height:100%;background:var(--card);color:var(--ink);z-index:300;padding:24px;overflow:auto;box-shadow:var(--shadow-lg);display:none}
    .drawer.open{display:block;animation:drawer-in .25s ease}
    @keyframes drawer-in{from{transform:translateX(105%)}to{transform:none}}
    @media(prefers-reduced-motion:reduce){.drawer.open{animation:none}}
    .drawer a{display:block;padding:13px 0;font-weight:600;text-decoration:none;border-bottom:1px solid var(--line);min-height:2.75rem;color:var(--ink)}
    .drawer a:hover{color:var(--brand)}
    .drawer-backdrop{position:fixed;inset:0;background:rgba(14,50,84,.45);z-index:250;display:none}
    .drawer-backdrop.open{display:block}
    @media(max-width:1020px){.nav-partner{display:none}}
    @media(max-width:900px){
      .topnav-row{flex-wrap:wrap;gap:10px;padding:12px 16px}
      .nav-search{order:3;flex-basis:100%}
      .nav-account,.nav-auth-group{display:none}
      .burger{display:block;margin-left:auto}
      .nav-cat-btn span.lbl{display:none}
      .logo-img{height:30px}
      .logo{font-size:1.3rem;gap:8px}
      .nav-ticket-svg{height:32px}
    }

    /* Flash Messages */
    .flash-container{max-width:1280px;margin:16px auto 0;padding:0 24px;width:100%}
    .alert{padding:14px 20px;border-radius:12px;font-size:14.5px;display:flex;align-items:center;justify-content:space-between;gap:12px;box-shadow:var(--shadow)}
    .alert-success{background:#E6F4EA;color:#137333;border:1px solid #CEEAD6}
    .alert-error{background:#FCE8E6;color:#C5221F;border:1px solid #FAD2CF}

    .site-main{flex:1}
  </style>
</head>
<body>

<header class="topnav">
  <div class="topnav-row">
    <a class="logo" href="<?= base_url() ?>" aria-label="NOVA LIVE" title="Putar ulang animasi logo"><svg id="navTicket" class="nav-ticket-svg" viewBox="0 30 1300 540" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Logo tiket NOVALIVE">
      <defs>
        <linearGradient id="navnv-dark" x1="60" y1="70" x2="780" y2="540" gradientUnits="userSpaceOnUse">
          <stop offset="0" stop-color="#2E8CB9"/>
          <stop offset="0.45" stop-color="#1F6AA3"/>
          <stop offset="1" stop-color="#17508A"/>
        </linearGradient>
        <linearGradient id="navnv-light" x1="690" y1="400" x2="1170" y2="300" gradientUnits="userSpaceOnUse">
          <stop offset="0" stop-color="#2A72B0"/>
          <stop offset="0.35" stop-color="#3F95CF"/>
          <stop offset="1" stop-color="#58B5E1"/>
        </linearGradient>
        <linearGradient id="navnv-shine" x1="0" y1="0" x2="1" y2="0">
          <stop offset="0" stop-color="#fff" stop-opacity="0"/>
          <stop offset="0.5" stop-color="#fff" stop-opacity="0.55"/>
          <stop offset="1" stop-color="#fff" stop-opacity="0"/>
        </linearGradient>
        <path id="navnv-dark-shape" d="M 42 352 L 105 285 A 106 106 0 0 0 258 135 L 325 62 L 840 535 L 300 535 C 176 536 96 438 42 352 Z"/>
        <path id="navnv-light-shape" d="M 678 365 L 798 248 L 885 248 C 888 285 905 292 925 292 C 990 292 1030 262 1075 248 L 1183 248 C 1130 330 1060 480 900 492 C 800 495 720 430 678 365 Z"/>
        <clipPath id="navnv-clip">
          <use href="#navnv-dark-shape"/>
          <use href="#navnv-light-shape"/>
        </clipPath>
      </defs>
      <g data-part="root">
        <path data-part="ribbon" d="M 385 68 L 665 382 C 735 466 803 514 897 512 C 1022 510 1120 384 1230 258" fill="none" stroke="#E8D5A1" stroke-width="44" pathLength="1" stroke-dasharray="1" stroke-dashoffset="1"/>
        <g data-part="dark" opacity="0">
          <use href="#navnv-dark-shape" fill="url(#navnv-dark)" stroke="url(#navnv-dark)" stroke-width="5" stroke-linejoin="round"/>
          <g fill="#FAFAFC">
            <g data-part="dot" data-cx="395" data-cy="255"><circle r="28"/></g>
            <g data-part="dot" data-cx="312" data-cy="340"><circle r="28"/></g>
            <g data-part="dot" data-cx="228" data-cy="424"><circle r="28"/></g>
          </g>
        </g>
        <g data-part="light" opacity="0">
          <use href="#navnv-light-shape" fill="url(#navnv-light)" stroke="url(#navnv-light)" stroke-width="4" stroke-linejoin="round"/>
          <g fill="#FAFAFC">
            <g data-part="oval" data-cx="773" data-cy="355"><ellipse rx="27" ry="16" transform="rotate(-8)"/></g>
            <g data-part="oval" data-cx="875" data-cy="359"><ellipse rx="29" ry="16" transform="rotate(-8)"/></g>
            <g data-part="oval" data-cx="975" data-cy="362"><ellipse rx="27" ry="16" transform="rotate(-8)"/></g>
          </g>
        </g>
        <g clip-path="url(#navnv-clip)">
          <rect data-part="shine" x="-120" y="-120" width="240" height="900" fill="url(#navnv-shine)" opacity="0"/>
        </g>
      </g>
    </svg><span class="logo-word"><span class="logo-nova">NOVA</span> <span class="logo-live">LIVE</span></span></a>

    <div class="nav-cat">
      <button class="nav-cat-btn" id="navCatBtn" type="button" aria-haspopup="true" aria-expanded="false" aria-controls="navCatMenu">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="3" y="3" width="8" height="8" rx="2" stroke="currentColor" stroke-width="2"/><rect x="13" y="3" width="8" height="8" rx="2" stroke="currentColor" stroke-width="2"/><rect x="3" y="13" width="8" height="8" rx="2" stroke="currentColor" stroke-width="2"/><rect x="13" y="13" width="8" height="8" rx="2" stroke="currentColor" stroke-width="2"/></svg><span class="lbl">Kategori</span><svg class="nav-cat-chev" width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </button>
    </div>

    <form class="nav-search" id="navSearch" action="<?= base_url('events') ?>" method="GET" role="search">
      <input id="navQ" name="search" type="search" value="<?= e(query_param('search', '')) ?>" placeholder="Cari event, artis, atau kota…" aria-label="Cari event, artis, atau kota…">
      <span class="nav-search-hint" id="navSearchHint" aria-hidden="true"><span class="hint-prefix">Cari event </span><span class="hint-word" id="navHintWord"></span></span>
      <button type="submit" aria-label="Cari"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2"/><path d="M20 20l-3.5-3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></button>
    </form>

    <a class="nav-partner" href="<?= base_url('events') ?>">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 2l2.2 6.6L21 11l-6.8 2.4L12 20l-2.2-6.6L3 11l6.8-2.4z" fill="currentColor"/></svg><span>Bikin Eventmu</span>
    </a>

    <?php if (!empty($currentUser)): ?>
      <div class="nav-auth-group">
        <a class="nav-user-chip" href="<?= base_url('dashboard') ?>">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="2"/><path d="M4 20c0-4 3.6-6 8-6s8 2 8 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
          <span><?= e($currentUser['full_name']) ?></span>
        </a>
        <a class="nav-btn-dash" href="<?= base_url('dashboard') ?>">Dashboard</a>
        <a class="nav-btn-logout" href="<?= base_url('auth/logout') ?>">Keluar</a>
      </div>
    <?php else: ?>
      <a class="nav-account" href="<?= base_url('login') ?>">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="2"/><path d="M4 20c0-4 3.6-6 8-6s8 2 8 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg><span>Masuk</span>
      </a>
    <?php endif; ?>

    <button class="burger" id="burgerBtn" aria-label="Buka menu" aria-expanded="false" aria-controls="drawer">☰</button>
  </div>

  <!-- MEGA MENU KATEGORI: grid 4 kolom kategori NOVA + bagian Jelajahi Event -->
  <div class="mega-menu" id="navCatMenu" role="menu" aria-label="Kategori event">
    <div class="mega-grid">
      <a class="mega-item" href="<?= base_url('events?category=1') ?>" role="menuitem">
        <span class="mega-ic"><svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/><path d="M9 18V5l12-2v13"/></svg></span>
        <span>Musik</span>
      </a>
      <a class="mega-item" href="<?= base_url('events?category=2') ?>" role="menuitem">
        <span class="mega-ic"><svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="M21 15l-3.1-3.1a2 2 0 0 0-2.8 0L6 21"/></svg></span>
        <span>Pameran</span>
      </a>
      <a class="mega-item" href="<?= base_url('events?category=3') ?>" role="menuitem">
        <span class="mega-ic"><svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3c4.97 0 8 2.6 8 6.2 0 5.6-3.4 11.8-8 11.8S4 14.8 4 9.2C4 5.6 7.03 3 12 3z"/><circle cx="9" cy="9.5" r="1" fill="currentColor" stroke="none"/><circle cx="15" cy="9.5" r="1" fill="currentColor" stroke="none"/><path d="M9 14.5c.9 1.1 1.9 1.6 3 1.6s2.1-.5 3-1.6"/></svg></span>
        <span>Teater</span>
      </a>
      <a class="mega-item" href="<?= base_url('events?category=4') ?>" role="menuitem">
        <span class="mega-ic"><svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7.5l2.9 2.1-1.1 3.4h-3.6l-1.1-3.4z"/><path d="M12 3v4.5M20.6 9.3l-3.2 1.1M17.8 19.5l-2.9-2.1M6.2 19.5l2.9-2.1M3.4 9.3l3.2 1.1"/></svg></span>
        <span>Olahraga</span>
      </a>
      <a class="mega-item" href="<?= base_url('events?category=5') ?>" role="menuitem">
        <span class="mega-ic"><svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 10.5c0 5.2-8 11.5-8 11.5S4 15.7 4 10.5a8 8 0 0 1 16 0z"/><circle cx="12" cy="10.5" r="3"/></svg></span>
        <span>Wisata</span>
      </a>
      <a class="mega-item" href="<?= base_url('events?category=6') ?>" role="menuitem">
        <span class="mega-ic"><svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="9" y="2.5" width="6" height="11" rx="3"/><path d="M5.5 11a6.5 6.5 0 0 0 13 0M12 17.5V21M9 21h6"/></svg></span>
        <span>Talkshow</span>
      </a>
      <a class="mega-item" href="<?= base_url('events?category=7') ?>" role="menuitem">
        <span class="mega-ic"><svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3.5" width="18" height="12" rx="2"/><circle cx="12" cy="8" r="1.7"/><path d="M8.3 13.2c.6-2 2-3 3.7-3s3.1 1 3.7 3M12 15.5V19M8.5 21l3.5-2 3.5 2"/></svg></span>
        <span>Workshop</span>
      </a>
      <a class="mega-item" href="<?= base_url('events?category=8') ?>" role="menuitem">
        <span class="mega-ic"><svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 4h10v5a5 5 0 0 1-10 0z"/><path d="M7 5H4.5a2.5 2.5 0 0 0 2.6 4M17 5h2.5a2.5 2.5 0 0 1-2.6 4M12 14v3M8.5 20.5h7M10 17h4l.6 3.5H9.4z"/></svg></span>
        <span>Kompetisi</span>
      </a>
    </div>
    <div class="mega-explore">
      <h3>Jelajahi Event</h3>
      <a class="mega-explore-card" href="<?= base_url('events') ?>">
        <span class="mega-explore-ic"><svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M15.5 8.5l-2 5-5 2 2-5z"/></svg></span>
        <span class="mega-explore-txt"><strong>Lihat Semua Event</strong><small>Telusuri semua event yang tersedia di NOVA LIVE dan temukan yang cocok untukmu.</small></span>
        <span class="mega-arrow" aria-hidden="true">›</span>
      </a>
    </div>
  </div>
</header>

<div class="drawer-backdrop" id="drawerBackdrop"></div>
<nav class="drawer" id="drawer" aria-label="Menu">
  <a href="<?= base_url() ?>">Beranda</a>
  <a href="<?= base_url('events') ?>">Jelajahi Event</a>
  <a href="<?= base_url('kategori') ?>">Kategori Event</a>
  <a href="<?= base_url('kota') ?>">Jelajahi Kotamu</a>
  <?php if (!empty($currentUser)): ?>
    <a href="<?= base_url('dashboard') ?>">Dashboard (<?= e($currentUser['full_name']) ?>)</a>
    <a href="<?= base_url('auth/logout') ?>">Keluar</a>
  <?php else: ?>
    <a href="<?= base_url('login') ?>">Masuk / Akun</a>
  <?php endif; ?>
</nav>

<script>
(function(){
  "use strict";

  /* 1. Mega menu Kategori: tombol membuka panel; menutup saat memilih kategori/tautan,
     klik di luar panel, atau tekan Escape. */
  var megaBtn = document.getElementById("navCatBtn"),
      megaMenu = document.getElementById("navCatMenu");
  function closeMega(){
    if (megaMenu) megaMenu.classList.remove("open");
    if (megaBtn) megaBtn.setAttribute("aria-expanded", "false");
  }
  if (megaBtn && megaMenu){
    megaBtn.addEventListener("click", function(e){
      e.stopPropagation();
      var open = megaMenu.classList.toggle("open");
      megaBtn.setAttribute("aria-expanded", open ? "true" : "false");
    });
    document.addEventListener("click", function(e){
      if (!megaMenu.contains(e.target) && !megaBtn.contains(e.target)) closeMega();
    });
    megaMenu.querySelectorAll("a").forEach(function(a){
      a.addEventListener("click", closeMega);
    });
    document.addEventListener("keydown", function(e){
      if (e.key === "Escape") closeMega();
    });
  }

  /* 2. Drawer Mobile */
  var burger = document.getElementById("burgerBtn"),
      drawer = document.getElementById("drawer"),
      backdrop = document.getElementById("drawerBackdrop");
  if (burger && drawer && backdrop){
    var drawerLinks = drawer.querySelectorAll("a");
    function syncDrawerLinks(){
      var open = drawer.classList.contains("open");
      drawerLinks.forEach(function(a){ a.tabIndex = open ? 0 : -1; });
    }
    function closeDrawer(){
      drawer.classList.remove("open");
      backdrop.classList.remove("open");
      burger.setAttribute("aria-expanded", "false");
      syncDrawerLinks();
    }
    burger.addEventListener("click", function(){
      var open = drawer.classList.toggle("open");
      backdrop.classList.toggle("open", open);
      burger.setAttribute("aria-expanded", open ? "true" : "false");
      syncDrawerLinks();
    });
    syncDrawerLinks();
    backdrop.addEventListener("click", closeDrawer);
    drawerLinks.forEach(function(a){ a.addEventListener("click", closeDrawer); });
  }

  /* 3. Animasi 3D berputar (flip rotateX) pada saran search navbar */
  (function initSearchFlipHint(){
    var navQ = document.getElementById("navQ");
    var hint = document.getElementById("navSearchHint");
    var word = document.getElementById("navHintWord");
    if (!navQ || !hint || !word) return;

    var STATIC_PLACEHOLDER = "Cari event, artis, atau kota\u2026";
    var SUGGESTIONS = [
      "Seru Untukmu",
      "Musik",
      "Populer",
      "di Kotamu",
      "Wisata",
      "Olahraga",
      "Favorit Kamu"
    ];

    var SHOW_TIME = 2000;
    var FLIP_TIME = 500;

    var motionQuery = window.matchMedia("(prefers-reduced-motion: reduce)");
    var sugIdx = 0;
    var isFocused = false;
    var timer = null;

    function isReduced(){
      return !!(motionQuery && motionQuery.matches);
    }

    function showStatic(){
      hint.classList.add("hidden");
      navQ.placeholder = STATIC_PLACEHOLDER;
    }

    function showHint(){
      navQ.placeholder = "";
      word.textContent = SUGGESTIONS[sugIdx];
      word.classList.remove("flip-out", "flip-in");
      hint.classList.remove("hidden");
    }

    function stop(){
      if (timer){
        clearTimeout(timer);
        timer = null;
      }
    }

    function scheduleFlip(){
      stop();
      timer = setTimeout(flipOut, SHOW_TIME);
    }

    function flipOut(){
      timer = null;
      if (isFocused || isReduced() || navQ.value) return;
      word.classList.remove("flip-in");
      word.classList.add("flip-out");
      timer = setTimeout(flipIn, FLIP_TIME);
    }

    function flipIn(){
      timer = null;
      if (isFocused || isReduced() || navQ.value) return;
      sugIdx = (sugIdx + 1) % SUGGESTIONS.length;
      word.textContent = SUGGESTIONS[sugIdx];
      word.classList.remove("flip-out");
      void word.offsetWidth;
      word.classList.add("flip-in");
      timer = setTimeout(function(){
        word.classList.remove("flip-in");
        scheduleFlip();
      }, FLIP_TIME);
    }

    function start(){
      stop();
      if (isReduced() || isFocused || navQ.value){
        showStatic();
        return;
      }
      showHint();
      scheduleFlip();
    }

    function handleFocus(){
      isFocused = true;
      stop();
      word.classList.remove("flip-out", "flip-in");
      showStatic();
    }

    function handleBlur(){
      isFocused = false;
      start();
    }

    navQ.addEventListener("focus", handleFocus);
    navQ.addEventListener("blur", handleBlur);
    navQ.addEventListener("input", function(){
      if (navQ.value){
        stop();
        showStatic();
      }
    });

    if (motionQuery.addEventListener){
      motionQuery.addEventListener("change", function(e){
        if (e.matches){
          stop();
          word.classList.remove("flip-out", "flip-in");
          showStatic();
        } else if (!isFocused){
          start();
        }
      });
    }

    start();
  })();

  /* 4. Logo tiket animasi di navbar: LOOP OTOMATIS — satu putaran 4,2 detik, jeda 1,2 detik, lalu putar lagi.
     Klik logo memutar ulang dari awal. prefers-reduced-motion: tampil frame akhir, tanpa loop. */
  (function initNavTicketAnim(){
    var svg = document.getElementById("navTicket");
    if (!svg) return;
    var DURATION = 4.2;
    var PAUSE_MS = 1200;

    var clamp = function(x){ return Math.min(1, Math.max(0, x)); };
    var prog = function(t, a, b){ return clamp((t - a) / (b - a)); };
    var ease = {
      outCubic: function(x){ return 1 - Math.pow(1 - x, 3); },
      inOutCubic: function(x){ return x < 0.5 ? 4 * x * x * x : 1 - Math.pow(-2 * x + 2, 3) / 2; },
      outBack: function(x, s){ s = s || 1.70158; var c = s + 1; return 1 + c * Math.pow(x - 1, 3) + s * Math.pow(x - 1, 2); }
    };

    var part = function(name){ return svg.querySelector('[data-part="' + name + '"]'); };
    var parts = {
      root: part("root"), ribbon: part("ribbon"), dark: part("dark"), light: part("light"), shine: part("shine"),
      dots: Array.prototype.slice.call(svg.querySelectorAll('[data-part="dot"]')),
      ovals: Array.prototype.slice.call(svg.querySelectorAll('[data-part="oval"]'))
    };
    if (!parts.root || !parts.ribbon || !parts.dark || !parts.light || !parts.shine) return;

    function pop(el, t, t0){
      var cx = +el.dataset.cx, cy = +el.dataset.cy;
      var u = prog(t, t0, t0 + 0.42);
      var s = u <= 0 ? 0 : ease.outBack(u, 2.4);
      el.setAttribute("transform", "translate(" + cx + " " + cy + ") scale(" + Math.max(s, 0.0001) + ")");
    }

    function apply(t){
      var p = 1 + 0.04 * ease.inOutCubic(prog(t, 0.2, DURATION));
      parts.root.setAttribute("transform", "translate(650 300) scale(" + p + ") translate(-650 -300)");
      parts.ribbon.setAttribute("stroke-dashoffset", (1 - ease.inOutCubic(prog(t, 0.25, 1.3))).toFixed(4));
      var uDark = prog(t, 0.6, 1.45), eDark = ease.outCubic(uDark);
      parts.dark.setAttribute("transform",
        "translate(" + (450 - 260 * (1 - eDark)) + " " + (300 + 150 * (1 - eDark)) + ") scale(" + (0.86 + 0.14 * eDark) + ") translate(-450 -300)");
      parts.dark.setAttribute("opacity", clamp(uDark * 3).toFixed(3));
      var uLight = prog(t, 0.95, 1.85), eLight = ease.outCubic(uLight);
      parts.light.setAttribute("transform",
        "translate(" + (678 + 90 * (1 - eLight)) + " " + (365 - 50 * (1 - eLight)) + ") rotate(" + (-16 * (1 - eLight)) + ") scale(" + (0.12 + 0.88 * eLight) + " " + (0.6 + 0.4 * eLight) + ") translate(-678 -365)");
      parts.light.setAttribute("opacity", clamp(uLight * 4).toFixed(3));
      parts.dots.forEach(function(el, i){ pop(el, t, 1.45 + 0.1 * i); });
      parts.ovals.forEach(function(el, i){ pop(el, t, 1.9 + 0.1 * i); });
      var uShine = prog(t, 2.15, 3.0), eShine = ease.inOutCubic(uShine);
      parts.shine.setAttribute("transform", "translate(" + (-250 + eShine * 1850) + " 0) rotate(20 0 300)");
      parts.shine.setAttribute("opacity", uShine > 0 && uShine < 1 ? "1" : "0");
    }

    var reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    var raf = 0;
    var loopTimer = null;

    function play(){
      cancelAnimationFrame(raf);
      if (loopTimer !== null){ clearTimeout(loopTimer); loopTimer = null; }
      if (reduceMotion){ apply(DURATION); return; }
      var start = 0;
      apply(0);
      function tick(now){
        if (!start) start = now;
        var t = (now - start) / 1000;
        apply(Math.min(t, DURATION));
        if (t < DURATION){
          raf = requestAnimationFrame(tick);
        } else {
          loopTimer = setTimeout(play, PAUSE_MS);
        }
      }
      raf = requestAnimationFrame(tick);
    }

    var logoLink = svg.parentNode;
    if (logoLink) logoLink.addEventListener("click", function(){ play(); });

    window.novaliveNavTicket = { apply: apply, play: play, DURATION: DURATION };
    play();
  })();
})();
</script>

<?php if (!empty($flash)): ?>
  <div class="flash-container">
    <?php if (!empty($flash['success'])): ?>
      <div class="alert alert-success">
        <span><?= e($flash['success']) ?></span>
      </div>
    <?php endif; ?>
    <?php if (!empty($flash['error'])): ?>
      <div class="alert alert-error">
        <span><?= e($flash['error']) ?></span>
      </div>
    <?php endif; ?>
  </div>
<?php endif; ?>

<main id="atas" class="site-main">
