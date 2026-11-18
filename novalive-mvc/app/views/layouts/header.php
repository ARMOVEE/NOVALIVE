<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= isset($pageTitle) ? e($pageTitle) : 'NOVA LIVE — Platform Tiket Event Indonesia' ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --navy: #0E3254;
      --navy-deep: #0A2540;
      --brand: #23649A;
      --sky: #509FC8;
      --gold: #EAD4A2;
      --gold-deep: #D4B475;
      --cream: #F5EFE0;
      --bg: #F5F1E6;
      --card: #FFFFFF;
      --ink: #0E2A47;
      --muted: #5B718C;
      --line: #E3E9F2;
      --radius: 16px;
      --shadow: 0 4px 20px rgba(14, 50, 84, 0.08);
      --shadow-lg: 0 12px 36px rgba(14, 50, 84, 0.14);
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Inter', sans-serif;
      background: var(--bg);
      color: var(--ink);
      line-height: 1.5;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }
    h1, h2, h3, h4, h5, .font-heading {
      font-family: 'Plus Jakarta Sans', sans-serif;
      color: var(--navy);
    }
    a { color: inherit; text-decoration: none; }

    /* Top Navbar */
    .site-nav {
      position: sticky;
      top: 0;
      z-index: 100;
      background: var(--navy);
      color: #fff;
      box-shadow: 0 4px 20px rgba(10, 37, 64, 0.25);
    }
    .nav-inner {
      max-width: 1280px;
      margin: 0 auto;
      padding: 12px 24px;
      display: flex;
      align-items: center;
      gap: 20px;
    }
    .nav-logo {
      display: flex;
      align-items: center;
      gap: 10px;
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: 22px;
      font-weight: 800;
      letter-spacing: -0.02em;
      white-space: nowrap;
    }
    .nav-logo svg {
      width: 32px;
      height: 32px;
    }
    .nav-logo .live { color: var(--gold); }
    
    .nav-links {
      display: flex;
      align-items: center;
      gap: 16px;
      list-style: none;
    }
    .nav-links a {
      font-size: 14.5px;
      font-weight: 600;
      padding: 8px 12px;
      border-radius: 8px;
      transition: background 0.2s, color 0.2s;
    }
    .nav-links a:hover {
      background: rgba(255, 255, 255, 0.1);
      color: var(--gold);
    }

    /* Search bar in Navbar */
    .nav-search-form {
      flex: 1;
      max-width: 480px;
      position: relative;
      display: flex;
      align-items: center;
      background: #fff;
      border-radius: 999px;
      padding: 4px 6px 4px 18px;
      min-height: 44px;
    }
    .nav-search-input {
      flex: 1;
      border: 0;
      outline: 0;
      font-size: 14px;
      color: var(--navy);
      background: transparent;
      min-width: 0;
    }
    .nav-search-hint {
      position: absolute;
      left: 18px;
      right: 48px;
      top: 50%;
      transform: translateY(-50%);
      font-size: 14px;
      color: #0E3254;
      pointer-events: none;
      white-space: nowrap;
      overflow: hidden;
      font-weight: 400;
    }
    .nav-search-hint.hidden { display: none; }
    .nav-search-hint .hint-word {
      font-weight: 700;
      color: #0E3254;
      display: inline-block;
      transition: transform 0.3s ease, opacity 0.3s ease;
    }
    .nav-search-btn {
      width: 36px;
      height: 36px;
      border: 0;
      background: transparent;
      border-radius: 50%;
      cursor: pointer;
      display: grid;
      place-items: center;
      color: var(--brand);
      flex: none;
    }
    .nav-search-btn:hover {
      background: var(--cream);
    }

    /* Navbar Right Actions */
    .nav-actions {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-left: auto;
    }
    .btn-masuk {
      background: var(--gold);
      color: var(--navy-deep);
      font-weight: 700;
      font-size: 14.5px;
      padding: 9px 20px;
      border-radius: 999px;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: background 0.2s, transform 0.15s;
    }
    .btn-masuk:hover {
      background: #f0deb3;
      transform: translateY(-1px);
    }
    .user-badge {
      display: flex;
      align-items: center;
      gap: 8px;
      background: rgba(255, 255, 255, 0.12);
      padding: 6px 14px;
      border-radius: 999px;
      font-size: 14px;
      font-weight: 600;
    }

    /* Flash Messages */
    .flash-container {
      max-width: 1280px;
      margin: 16px auto 0;
      padding: 0 24px;
      width: 100%;
    }
    .alert {
      padding: 14px 20px;
      border-radius: 12px;
      font-size: 14.5px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      box-shadow: var(--shadow);
    }
    .alert-success { background: #E6F4EA; color: #137333; border: 1px solid #CEEAD6; }
    .alert-error { background: #FCE8E6; color: #C5221F; border: 1px solid #FAD2CF; }

    /* Layout Wrapper */
    .site-main { flex: 1; }
  </style>
</head>
<body>

<header class="site-nav">
  <div class="nav-inner">
    <a href="<?= base_url() ?>" class="nav-logo" aria-label="NOVA LIVE Beranda">
      <svg viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
        <rect width="32" height="32" rx="8" fill="#15497A"/>
        <path d="M7 11C7 9.89543 7.89543 9 9 9H23C24.1046 9 25 9.89543 25 11V14.5C24.1716 14.5 23.5 15.1716 23.5 16C23.5 16.8284 24.1716 17.5 25 17.5V21C25 22.1046 24.1046 23 23 23H9C7.89543 23 7 22.1046 7 21V17.5C7.82843 17.5 8.5 16.8284 8.5 16C8.5 15.1716 7.82843 14.5 7 14.5V11Z" fill="#EAD4A2"/>
        <circle cx="16" cy="16" r="3" fill="#0E3254"/>
      </svg>
      <span>NOVA <span class="live">LIVE</span></span>
    </a>

    <ul class="nav-links">
      <li><a href="<?= base_url() ?>">Beranda</a></li>
      <li><a href="<?= base_url('events') ?>">Jelajahi Event</a></li>
      <li><a href="<?= base_url('kategori') ?>">Kategori</a></li>
      <li><a href="<?= base_url('kota') ?>">Kotamu</a></li>
    </ul>

    <form class="nav-search-form" action="<?= base_url('events') ?>" method="GET" role="search">
      <input type="search" name="search" class="nav-search-input" id="navSearchInput" aria-label="Cari event">
      <span class="nav-search-hint" id="navSearchHint" aria-hidden="true">
        Cari event <span class="hint-word" id="navHintWord">Favorit Kamu</span>
      </span>
      <button type="submit" class="nav-search-btn" aria-label="Mulai cari">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.8-3.8"/></svg>
      </button>
    </form>

    <div class="nav-actions">
      <?php if (!empty($currentUser)): ?>
        <a href="<?= base_url('dashboard') ?>" class="user-badge">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          <span><?= e($currentUser['full_name']) ?></span>
        </a>
        <a href="<?= base_url('dashboard') ?>" class="btn-masuk" style="background:#509FC8;color:#fff;">Dashboard</a>
        <a href="<?= base_url('auth/logout') ?>" class="btn-masuk" style="background:transparent;border:1px solid rgba(255,255,255,.3);color:#fff;">Keluar</a>
      <?php else: ?>
        <a href="<?= base_url('login') ?>" class="btn-masuk">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
          Masuk
        </a>
      <?php endif; ?>
    </div>
  </div>
</header>

<script>
  // Rotasi teks kata kunci pencarian dengan warna Navy
  (function(){
    const input = document.getElementById('navSearchInput');
    const hint = document.getElementById('navSearchHint');
    const word = document.getElementById('navHintWord');
    if (!input || !hint || !word) return;

    const keywords = ['Seru Untukmu', 'Musik', 'Populer', 'di Kotamu', 'Wisata', 'Olahraga', 'Favorit Kamu'];
    let idx = 0;

    function toggleHint() {
      if (input.value.trim().length > 0) {
        hint.classList.add('hidden');
      } else {
        hint.classList.remove('hidden');
      }
    }

    input.addEventListener('input', toggleHint);
    input.addEventListener('focus', function(){ if(!input.value) hint.style.opacity = '0.5'; });
    input.addEventListener('blur', function(){ if(!input.value) hint.style.opacity = '1'; });
    hint.addEventListener('click', function(){ input.focus(); });

    setInterval(function(){
      if (input.value.trim().length > 0) return;
      word.style.opacity = '0';
      word.style.transform = 'translateY(6px)';
      setTimeout(function(){
        idx = (idx + 1) % keywords.length;
        word.textContent = keywords[idx];
        word.style.opacity = '1';
        word.style.transform = 'translateY(0)';
      }, 250);
    }, 2800);
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

<main class="site-main">
