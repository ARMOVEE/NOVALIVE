<?php
/**
 * View: Login (Standalone)
 * Samakan persis dengan desain mockup (nova-live/NOVA LIVE — Form Login.html)
 */

$flashError = $flash['danger'] ?? $flash['error'] ?? null;
$flashSuccess = $flash['success'] ?? null;
?>
<!doctype html>
<html lang="id">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta name="color-scheme" content="light dark" />
    <link rel="icon" href="data:," />
    <title>NOVA LIVE — Form Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@600;700;800&family=Inter:wght@400;500;600;700&display=swap"
      rel="stylesheet"
    />
    <style>
      :root {
        --navy-deep: #0e3254;
        --navy: #15497a;
        --brand: #23649a;
        --sky: #509fc8;
        --gold: #ead4a2;
        --gold-deep: #d4b475;
        --cream: #f5efe0;
        --ink: #0e2a47;
        --muted: #4a607a;
        --line: #e2ddd0;
        --danger: #c2410c;
        --danger-bg: #fdf0e9;
        --success: #15803d;
        --page-bg: #f5efe0;
        --card-bg: #ffffff;
        --sheet-bg: #ffffff;
        --field-bg: #faf8f2;
        --field-line: #ece7d8;
        --shadow-card: 0 30px 60px -18px rgba(14, 42, 71, 0.35), 0 12px 24px -12px rgba(14, 42, 71, 0.18);
        --font-display: "Plus Jakarta Sans", system-ui, sans-serif;
        --font-body: "Inter", system-ui, sans-serif;
        color-scheme: light;
      }
      @media (prefers-color-scheme: dark) {
        :root {
          --page-bg: #f5efe0;
          --card-bg: #0d2a47;
          --sheet-bg: #0d2a47;
          --field-bg: #0a233c;
          --field-line: #1d4066;
          --line: #1d4066;
          --ink: #f2ecdc;
          --muted: #a9bdd1;
          --danger: #fda07c;
          --danger-bg: #3a2115;
          --shadow-card: 0 30px 60px -18px rgba(0, 0, 0, 0.65), 0 12px 24px -12px rgba(0, 0, 0, 0.5);
          color-scheme: dark;
        }
        /* Latar halaman tetap cream di mode gelap, jadi teks kaki halaman
           di luar kartu pakai warna mode terang biar tetap terbaca. */
        .page-foot { color: #4a607a; }
      }

      * { box-sizing: border-box; }
      html, body { margin: 0; padding: 0; }
      body {
        font-family: var(--font-body);
        color: var(--ink);
        min-height: 100dvh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px 14px 28px;
        background:
          radial-gradient(560px 380px at 12% 8%, rgba(212, 180, 117, 0.35), transparent 65%),
          radial-gradient(640px 460px at 88% 92%, rgba(80, 159, 200, 0.28), transparent 65%),
          radial-gradient(420px 300px at 85% 10%, rgba(35, 100, 154, 0.16), transparent 60%),
          var(--page-bg);
      }
      button, input { font-family: inherit; }
      button { cursor: pointer; }
      :focus-visible { outline: 3px solid var(--sky); outline-offset: 2px; border-radius: 8px; }

      /* ---------- Phone card ---------- */
      .phone {
        width: 100%;
        max-width: 360px;
        background: var(--navy-deep);
        border-radius: 32px;
        box-shadow: var(--shadow-card);
        overflow: hidden;
      }

      /* ---------- Hero ---------- */
      .hero {
        position: relative;
        height: 195px;
        background: linear-gradient(180deg, #15497a 0%, #0e3254 78%);
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
      }
      .hero::after {
        content: "";
        position: absolute;
        inset: auto 0 0 0;
        height: 54px;
        background: linear-gradient(180deg, transparent, rgba(14, 50, 84, 0.35));
        pointer-events: none;
      }
      /* Logo tiket animasi NOVALIVE (SVG transparan di atas navy) */
      .hero-ticket {
        position: relative;
        z-index: 1;
        width: 86%;
        max-width: 300px;
        height: auto;
        overflow: visible;
        display: block;
        cursor: pointer;
      }

      /* ---------- Sheet ---------- */
      .sheet {
        position: relative;
        z-index: 3;
        background: var(--sheet-bg);
        border-radius: 26px 26px 0 0;
        margin-top: -22px;
        padding: 20px 24px 24px;
      }
      .sheet h1 {
        font-family: var(--font-display);
        font-weight: 800;
        font-size: 25px;
        letter-spacing: -0.01em;
        text-align: center;
        margin: 0 0 6px;
        color: var(--ink);
      }
      .sheet .sub {
        text-align: center;
        color: var(--muted);
        font-size: 13px;
        line-height: 1.45;
        margin: 0 0 18px;
      }

      /* ---------- Pembatas lekuk ke atas di tepi atas kartu form ---------- */
      .sheet-wave {
        position: absolute;
        left: 0;
        top: -30px;
        width: 100%;
        height: 44px;
        display: block;
        color: var(--sheet-bg);
        pointer-events: none;
      }

      /* ---------- Sheet putih: kartu form terang untuk semua tampilan ----------
         (Login, Daftar, Lupa Kata Sandi). Warna dipatok ke palet terang di kedua
         mode (terang/gelap) supaya kartu form selalu putih dengan teks gelap
         dan field terang yang terbaca. */
      .sheet.sheet-light { background: #ffffff; }
      .sheet.sheet-light .sheet-wave { color: #ffffff; }
      .sheet.sheet-light h1 { color: #0e2a47; }
      .sheet.sheet-light .sub { color: #4a607a; }
      .sheet.sheet-light .field-group { background: #faf8f2; border-color: #ece7d8; }
      .sheet.sheet-light .field-row + .field-row { border-top-color: #ece7d8; }
      .sheet.sheet-light .field-row input { color: #0e2a47; }
      .sheet.sheet-light .field-row input::placeholder { color: #4a607a; opacity: 0.75; }
      .sheet.sheet-light .eye-btn { color: #4a607a; }
      .sheet.sheet-light .eye-btn:hover { color: #23649a; background: rgba(80, 159, 200, 0.12); }
      .sheet.sheet-light .ico-mail { background: #f6e7c3; color: #9a741f; }
      .sheet.sheet-light .ico-lock, .sheet.sheet-light .ico-name { background: #dcecf5; color: #23649a; }
      .sheet.sheet-light .field-group.invalid { border-color: #c2410c; background: #fdf0e9; }
      .sheet.sheet-light .field-err { color: #c2410c; }
      .sheet.sheet-light .link { color: #23649a; }
      .sheet.sheet-light .btn-login { background: #0e2a47; color: #ffffff; }
      .sheet.sheet-light .btn-login:hover { background: #15497a; }
      .sheet.sheet-light .btn-full { background: #0e2a47; color: #ffffff; }
      .sheet.sheet-light .btn-full:hover { background: #15497a; }
      .sheet.sheet-light .back-btn { color: #23649a; }
      .sheet.sheet-light .spinner { border-color: rgba(255, 255, 255, 0.4); border-top-color: #ffffff; }
      .sheet.sheet-light .switch-line { color: #4a607a; }
      .sheet.sheet-light .notice.ok { background: #e8f5ec; color: #14532d; border-color: #bbe7c6; }
      .sheet.sheet-light .notice.err { background: #fdf0e9; color: #c2410c; border-color: rgba(194, 65, 12, 0.3); }
      .sheet.sheet-light .demo-chip { color: #4a607a; }
      .sheet.sheet-light .demo-chip code { background: #faf8f2; border-color: #ece7d8; color: #0e2a47; }

      /* ---------- Field group (satu kartu, dua baris) ---------- */
      .field-group {
        background: var(--field-bg);
        border: 1px solid var(--field-line);
        border-radius: 18px;
        padding: 2px 14px;
        margin-bottom: 6px;
      }
      .field-row {
        display: flex;
        align-items: center;
        gap: 10px;
        min-height: 50px;
      }
      .field-row + .field-row { border-top: 1px solid var(--field-line); }
      .field-ico {
        flex: 0 0 auto;
        width: 32px; height: 32px;
        border-radius: 50%;
        display: grid;
        place-items: center;
      }
      .field-ico svg { width: 16px; height: 16px; }
      .ico-mail { background: #f6e7c3; color: #9a741f; }
      .ico-lock { background: #dcecf5; color: var(--brand); }
      .ico-name { background: #dcecf5; color: var(--brand); }
      @media (prefers-color-scheme: dark) {
        .ico-mail { background: #4a3a17; color: var(--gold); }
        .ico-lock, .ico-name { background: #173d63; color: var(--sky); }
      }
      .field-row input {
        flex: 1 1 auto;
        min-width: 0;
        border: 0;
        background: transparent;
        font-size: 14px;
        font-weight: 500;
        color: var(--ink);
        padding: 8px 0;
        min-height: 40px;
      }
      .field-row input::placeholder { color: var(--muted); opacity: 0.75; font-weight: 400; }
      .field-row input:focus { outline: none; }
      .field-row:focus-within .field-ico { box-shadow: 0 0 0 3px rgba(80, 159, 200, 0.35); }
      .eye-btn {
        flex: 0 0 auto;
        width: 36px; height: 36px;
        border: 0;
        background: transparent;
        border-radius: 50%;
        display: grid;
        place-items: center;
        color: var(--muted);
        padding: 0;
      }
      .eye-btn svg { width: 18px; height: 18px; }
      .eye-btn:hover { color: var(--brand); background: rgba(80, 159, 200, 0.12); }

      .field-err {
        display: none;
        color: var(--danger);
        font-size: 12.5px;
        font-weight: 600;
        margin: 6px 4px 0;
      }
      .field-err.show { display: block; }
      .field-group.invalid { border-color: var(--danger); background: var(--danger-bg); }

      /* ---------- Row: lupa sandi + tombol login ---------- */
      .action-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-top: 16px;
      }
      .link {
        color: var(--brand);
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        background: none;
        border: 0;
        padding: 8px 2px;
        min-height: 36px;
        display: inline-flex;
        align-items: center;
      }
      .link:hover { text-decoration: underline; }
      .btn-login {
        border: 0;
        background: var(--ink);
        color: #fff;
        font-family: var(--font-display);
        font-weight: 700;
        font-size: 14px;
        padding: 0 32px;
        min-height: 44px;
        border-radius: 999px;
        box-shadow: 0 10px 20px -8px rgba(14, 42, 71, 0.5);
        transition: transform 0.15s ease, background 0.15s ease;
      }
      @media (prefers-color-scheme: dark) { .btn-login { background: var(--gold-deep); color: #23190a; } }
      .btn-login:hover { background: var(--navy); transform: translateY(-1px); }
      @media (prefers-color-scheme: dark) { .btn-login:hover { background: var(--gold); } }
      .btn-login:active { transform: translateY(0); }
      .btn-login[disabled] { opacity: 0.65; cursor: wait; transform: none; }

      .btn-full {
        width: 100%;
        border: 0;
        background: var(--ink);
        color: #fff;
        font-family: var(--font-display);
        font-weight: 700;
        font-size: 14px;
        min-height: 46px;
        border-radius: 999px;
        margin-top: 16px;
        box-shadow: 0 10px 20px -8px rgba(14, 42, 71, 0.5);
      }
      @media (prefers-color-scheme: dark) { .btn-full { background: var(--gold-deep); color: #23190a; } }
      .btn-full:hover { background: var(--navy); }
      @media (prefers-color-scheme: dark) { .btn-full:hover { background: var(--gold); } }

      .spinner {
        display: inline-block;
        width: 16px; height: 16px;
        border: 2.5px solid rgba(255, 255, 255, 0.4);
        border-top-color: #fff;
        border-radius: 50%;
        vertical-align: -3px;
        margin-right: 8px;
        animation: spin 0.7s linear infinite;
      }
      @keyframes spin { to { transform: rotate(360deg); } }

      .switch-line {
        text-align: center;
        margin: 18px 0 0;
        font-size: 13px;
        color: var(--muted);
      }
      .switch-line .link { font-size: 13px; padding: 6px 2px; }

      /* ---------- Panel sukses / info ---------- */
      .notice {
        display: none;
        border-radius: 14px;
        padding: 12px 14px;
        font-size: 13px;
        line-height: 1.5;
        margin-bottom: 14px;
      }
      .notice.show { display: block; }
      .notice.ok { background: #e8f5ec; color: #14532d; border: 1px solid #bbe7c6; }
      .notice.err { background: var(--danger-bg); color: var(--danger); border: 1px solid rgba(194, 65, 12, 0.3); font-weight: 600; }
      @media (prefers-color-scheme: dark) {
        .notice.ok { background: #123324; color: #a7e8bd; border-color: #1f5136; }
      }

      .back-row { margin-bottom: 4px; }
      .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: 0;
        background: none;
        color: var(--brand);
        font-weight: 600;
        font-size: 12.5px;
        padding: 6px 2px;
        min-height: 34px;
        text-decoration: none;
      }
      .back-btn:hover { text-decoration: underline; }
      .back-btn svg { width: 15px; height: 15px; }

      .view[hidden] { display: none; }

      .demo-chip {
        margin-top: 16px;
        text-align: center;
        font-size: 11.5px;
        color: var(--muted);
        line-height: 1.55;
      }
      .demo-chip code {
        background: var(--field-bg);
        border: 1px solid var(--field-line);
        border-radius: 6px;
        padding: 1px 6px;
        font-size: 11.5px;
        color: var(--ink);
      }

      .page-foot {
        margin-top: 20px;
        text-align: center;
        font-size: 12px;
        color: var(--muted);
      }

      @media (max-width: 380px) {
        .sheet { padding: 22px 20px 26px; }
        .hero { height: 218px; }
        .btn-login { padding: 0 28px; }
      }
      @media (prefers-reduced-motion: reduce) {
        * { transition: none !important; animation: none !important; }
      }
    </style>
  </head>
  <body>
    <main>
      <div class="phone">
        <!-- ======= HERO: logo tiket animasi NOVALIVE ======= -->
        <div class="hero">
          <svg id="nvTicket" class="hero-ticket" viewBox="0 30 1300 540" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Logo tiket NOVALIVE" title="Klik untuk memutar ulang animasi">
            <defs>
              <linearGradient id="nvlogin-dark" x1="60" y1="70" x2="780" y2="540" gradientUnits="userSpaceOnUse">
                <stop offset="0" stop-color="#2E8CB9"/>
                <stop offset="0.45" stop-color="#1F6AA3"/>
                <stop offset="1" stop-color="#17508A"/>
              </linearGradient>
              <linearGradient id="nvlogin-light" x1="690" y1="400" x2="1170" y2="300" gradientUnits="userSpaceOnUse">
                <stop offset="0" stop-color="#2A72B0"/>
                <stop offset="0.35" stop-color="#3F95CF"/>
                <stop offset="1" stop-color="#58B5E1"/>
              </linearGradient>
              <linearGradient id="nvlogin-shine" x1="0" y1="0" x2="1" y2="0">
                <stop offset="0" stop-color="#fff" stop-opacity="0"/>
                <stop offset="0.5" stop-color="#fff" stop-opacity="0.55"/>
                <stop offset="1" stop-color="#fff" stop-opacity="0"/>
              </linearGradient>
              <path id="nvlogin-dark-shape" d="M 42 352 L 105 285 A 106 106 0 0 0 258 135 L 325 62 L 840 535 L 300 535 C 176 536 96 438 42 352 Z"/>
              <path id="nvlogin-light-shape" d="M 678 365 L 798 248 L 885 248 C 888 285 905 292 925 292 C 990 292 1030 262 1075 248 L 1183 248 C 1130 330 1060 480 900 492 C 800 495 720 430 678 365 Z"/>
              <clipPath id="nvlogin-clip">
                <use href="#nvlogin-dark-shape"/>
                <use href="#nvlogin-light-shape"/>
              </clipPath>
            </defs>

            <g data-part="root">
              <path data-part="ribbon" d="M 385 68 L 665 382 C 735 466 803 514 897 512 C 1022 510 1120 384 1230 258"
                    fill="none" stroke="#E8D5A1" stroke-width="44" pathLength="1" stroke-dasharray="1" stroke-dashoffset="1"/>

              <g data-part="dark" opacity="0">
                <use href="#nvlogin-dark-shape" fill="url(#nvlogin-dark)" stroke="url(#nvlogin-dark)" stroke-width="5" stroke-linejoin="round"/>
                <g fill="#FAFAFC">
                  <g data-part="dot" data-cx="395" data-cy="255"><circle r="28"/></g>
                  <g data-part="dot" data-cx="312" data-cy="340"><circle r="28"/></g>
                  <g data-part="dot" data-cx="228" data-cy="424"><circle r="28"/></g>
                </g>
              </g>

              <g data-part="light" opacity="0">
                <use href="#nvlogin-light-shape" fill="url(#nvlogin-light)" stroke="url(#nvlogin-light)" stroke-width="4" stroke-linejoin="round"/>
                <g fill="#FAFAFC">
                  <g data-part="oval" data-cx="773" data-cy="355"><ellipse rx="27" ry="16" transform="rotate(-8)"/></g>
                  <g data-part="oval" data-cx="875" data-cy="359"><ellipse rx="29" ry="16" transform="rotate(-8)"/></g>
                  <g data-part="oval" data-cx="975" data-cy="362"><ellipse rx="27" ry="16" transform="rotate(-8)"/></g>
                </g>
              </g>

              <g clip-path="url(#nvlogin-clip)">
                <rect data-part="shine" x="-120" y="-120" width="240" height="900" fill="url(#nvlogin-shine)" opacity="0"/>
              </g>
            </g>
          </svg>
        </div>

        <!-- ======= SHEET ======= -->
        <section class="sheet sheet-light" id="sheet">
          <!-- Pembatas lekuk ke atas: tepi atas kartu melengkung naik memisahkan hero navy dari kartu form -->
          <svg class="sheet-wave" viewBox="0 0 400 52" preserveAspectRatio="none" aria-hidden="true" focusable="false"><path d="M0 52 L0 34 C 110 8, 290 8, 400 34 L400 52 Z" fill="currentColor"/></svg>

          <!-- ===== VIEW: LOGIN ===== -->
          <div class="view" id="view-login">
            <div class="back-row">
              <a href="<?= base_url() ?>" class="back-btn" aria-label="Kembali ke Beranda">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5l-7 7 7 7"/></svg>
                Kembali ke Beranda
              </a>
            </div>
            <h1>Login</h1>
            <p class="sub">Selamat datang kembali! Masuk untuk melanjutkan pembelian tiket event favoritmu.</p>

            <div class="notice <?= $flashError ? 'show err' : ($flashSuccess ? 'show ok' : '') ?>" id="loginNotice" role="status"><?php if ($flashError): ?><?= e($flashError) ?><?php elseif ($flashSuccess): ?><?= e($flashSuccess) ?><?php endif; ?></div>

            <form id="loginForm" action="<?= base_url('auth/authenticate') ?>" method="POST" novalidate>
              <?= csrf_field() ?>
              <div class="field-group" id="loginGroup">
                <div class="field-row">
                  <span class="field-ico ico-mail" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 3.6-6.5 8-6.5s8 2.5 8 6.5"/></svg>
                  </span>
                  <input type="email" id="loginEmail" name="email" placeholder="Email kamu" autocomplete="email" inputmode="email" aria-label="Email" />
                </div>
                <div class="field-row">
                  <span class="field-ico ico-lock" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg>
                  </span>
                  <input type="password" id="loginPass" name="password" placeholder="Kata sandi" autocomplete="current-password" aria-label="Kata sandi" />
                  <button type="button" class="eye-btn" data-eye="loginPass" aria-label="Tampilkan kata sandi">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                  </button>
                </div>
              </div>
              <p class="field-err" id="loginErr"></p>

              <div class="action-row">
                <button type="button" class="link" id="gotoForgot">Lupa Kata Sandi?</button>
                <button type="submit" class="btn-login" id="loginBtn">Login</button>
              </div>
            </form>

            <p class="switch-line">Belum punya akun?
              <button type="button" class="link" id="gotoRegister">Daftar</button>
            </p>

            <p class="demo-chip">Akun demo: <code>demo@novalive.id</code> · sandi <code>nova1234</code><br>Akun Admin: <code>novaLive.Admin@gmail.id</code> · sandi <code>NovaLive.Admin</code></p>
          </div>

          <!-- ===== VIEW: LUPA KATA SANDI ===== -->
          <div class="view" id="view-forgot" hidden>
            <div class="back-row">
              <button type="button" class="back-btn" data-back>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5l-7 7 7 7"/></svg>
                Kembali ke Login
              </button>
            </div>
            <h1>Lupa Kata Sandi</h1>
            <p class="sub">Masukkan email yang terdaftar. Kami akan mengirimkan tautan untuk mengatur ulang kata sandimu.</p>

            <div class="notice" id="forgotNotice" role="status"></div>

            <form id="forgotForm" novalidate>
              <div class="field-group" id="forgotGroup">
                <div class="field-row">
                  <span class="field-ico ico-mail" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 3.6-6.5 8-6.5s8 2.5 8 6.5"/></svg>
                  </span>
                  <input type="email" id="forgotEmail" name="email" placeholder="Email kamu" autocomplete="email" inputmode="email" aria-label="Email" />
                </div>
              </div>
              <p class="field-err" id="forgotErr"></p>
              <button type="submit" class="btn-full" id="forgotBtn">Kirim Tautan Reset</button>
            </form>
          </div>

          <!-- ===== VIEW: DAFTAR ===== -->
          <div class="view" id="view-register" hidden>
            <div class="back-row">
              <button type="button" class="back-btn" data-back>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5l-7 7 7 7"/></svg>
                Kembali ke Login
              </button>
            </div>
            <h1>Daftar Akun</h1>
            <p class="sub">Buat akun NOVA LIVE untuk membeli tiket dan menyimpan e-tiketmu di satu tempat.</p>

            <div class="notice" id="regNotice" role="status"></div>

            <form id="regForm" novalidate>
              <div class="field-group" id="regGroup">
                <div class="field-row">
                  <span class="field-ico ico-name" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 3.6-6.5 8-6.5s8 2.5 8 6.5"/></svg>
                  </span>
                  <input type="text" id="regName" name="name" placeholder="Nama lengkap" autocomplete="name" aria-label="Nama lengkap" />
                </div>
                <div class="field-row">
                  <span class="field-ico ico-mail" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
                  </span>
                  <input type="email" id="regEmail" name="email" placeholder="Email kamu" autocomplete="email" inputmode="email" aria-label="Email" />
                </div>
                <div class="field-row">
                  <span class="field-ico ico-lock" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg>
                  </span>
                  <input type="password" id="regPass" name="password" placeholder="Kata sandi (min. 8 karakter)" autocomplete="new-password" aria-label="Kata sandi" />
                  <button type="button" class="eye-btn" data-eye="regPass" aria-label="Tampilkan kata sandi">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                  </button>
                </div>
              </div>
              <p class="field-err" id="regErr"></p>
              <button type="submit" class="btn-full" id="regBtn">Daftar</button>
            </form>

            <p class="switch-line">Sudah punya akun?
              <button type="button" class="link" data-back>Login</button>
            </p>
          </div>

        </section>
      </div>
      <p class="page-foot">© 2026 NOVA LIVE. Hak Cipta Dilindungi.</p>
    </main>

    <script>
      (function () {
        "use strict";

        var DEMO_EMAIL = "demo@novalive.id";
        var DEMO_PASS = "nova1234";
        var ADMIN_EMAIL = "novalive.admin@gmail.id";
        var ADMIN_PASS = "NovaLive.Admin";

        var views = {
          login: document.getElementById("view-login"),
          forgot: document.getElementById("view-forgot"),
          register: document.getElementById("view-register")
        };

        function showView(name) {
          Object.keys(views).forEach(function (key) {
            views[key].hidden = key !== name;
          });
          // Kartu putih + pembatas melengkung berlaku di semua tampilan
          // (Login, Daftar, Lupa Kata Sandi) supaya konsisten.
          document.getElementById("sheet").classList.add("sheet-light");
          window.scrollTo({ top: 0, behavior: "smooth" });
        }

        function setNotice(el, type, html) {
          el.className = "notice show " + type;
          el.innerHTML = html;
        }
        function clearNotice(el) {
          el.className = "notice";
          el.innerHTML = "";
        }
        function setErr(groupEl, errEl, msg) {
          if (msg) {
            groupEl.classList.add("invalid");
            errEl.textContent = msg;
            errEl.classList.add("show");
          } else {
            groupEl.classList.remove("invalid");
            errEl.textContent = "";
            errEl.classList.remove("show");
          }
        }
        function escapeHtml(s) {
          return s.replace(/[&<>"']/g, function (c) {
            return { "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;" }[c];
          });
        }
        function validEmail(v) {
          var clean = String(v).trim().toLowerCase();
          if (clean === ADMIN_EMAIL || clean === "novalive.admin") return true;
          return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v);
        }
        function setLoading(btn, loading, label) {
          if (loading) {
            btn.style.pointerEvents = "none";
            btn.innerHTML = '<span class="spinner" aria-hidden="true"></span>Memproses…';
          } else {
            btn.style.pointerEvents = "";
            btn.innerHTML = label;
          }
        }

        /* ----- Navigasi antar tampilan ----- */
        document.getElementById("gotoForgot").addEventListener("click", function () {
          var e = document.getElementById("loginEmail").value.trim();
          if (e) document.getElementById("forgotEmail").value = e;
          clearNotice(document.getElementById("forgotNotice"));
          setErr(document.getElementById("forgotGroup"), document.getElementById("forgotErr"), "");
          showView("forgot");
        });
        document.getElementById("gotoRegister").addEventListener("click", function () {
          clearNotice(document.getElementById("regNotice"));
          setErr(document.getElementById("regGroup"), document.getElementById("regErr"), "");
          showView("register");
        });
        Array.prototype.forEach.call(document.querySelectorAll("[data-back]"), function (b) {
          b.addEventListener("click", function () { showView("login"); });
        });

        /* ----- Tampilkan / sembunyikan kata sandi ----- */
        Array.prototype.forEach.call(document.querySelectorAll("[data-eye]"), function (btn) {
          btn.addEventListener("click", function () {
            var input = document.getElementById(btn.getAttribute("data-eye"));
            var show = input.type === "password";
            input.type = show ? "text" : "password";
            btn.setAttribute("aria-label", show ? "Sembunyikan kata sandi" : "Tampilkan kata sandi");
            btn.style.color = show ? "var(--brand)" : "";
          });
        });

        /* ----- Login ----- */
        var loginForm = document.getElementById("loginForm");
        var loginGroup = document.getElementById("loginGroup");
        var loginErr = document.getElementById("loginErr");
        var loginNotice = document.getElementById("loginNotice");
        var loginBtn = document.getElementById("loginBtn");

        Array.prototype.forEach.call(document.querySelectorAll(".demo-chip code"), function (c) {
          c.style.cursor = "pointer";
          c.title = "Klik untuk isi otomatis";
          c.addEventListener("click", function () {
            var txt = c.textContent.trim();
            if (txt.indexOf("@") !== -1 || txt.toLowerCase() === "novalive.admin") {
              document.getElementById("loginEmail").value = txt;
              if (txt === "demo@novalive.id") {
                document.getElementById("loginPass").value = "nova1234";
              }
            } else {
              document.getElementById("loginPass").value = txt;
            }
          });
        });

        loginForm.addEventListener("submit", function (ev) {
          clearNotice(loginNotice);
          var email = document.getElementById("loginEmail").value.trim();
          var pass = document.getElementById("loginPass").value;

          if (!validEmail(email)) {
            ev.preventDefault();
            setErr(loginGroup, loginErr, "Masukkan alamat email yang valid.");
            return;
          }
          if (pass.length < 8) {
            ev.preventDefault();
            setErr(loginGroup, loginErr, "Kata sandi minimal 8 karakter.");
            return;
          }
          setErr(loginGroup, loginErr, "");
          setLoading(loginBtn, true, "Login");
        });

        /* ----- Lupa kata sandi (DEMO) ----- */
        var forgotForm = document.getElementById("forgotForm");
        var forgotGroup = document.getElementById("forgotGroup");
        var forgotErr = document.getElementById("forgotErr");
        var forgotNotice = document.getElementById("forgotNotice");
        var forgotBtn = document.getElementById("forgotBtn");

        forgotForm.addEventListener("submit", function (ev) {
          ev.preventDefault();
          clearNotice(forgotNotice);
          var email = document.getElementById("forgotEmail").value.trim();
          if (!validEmail(email)) {
            setErr(forgotGroup, forgotErr, "Masukkan alamat email yang valid.");
            return;
          }
          setErr(forgotGroup, forgotErr, "");
          setLoading(forgotBtn, true, "Kirim Tautan Reset");
          setTimeout(function () {
            setLoading(forgotBtn, false, "Kirim Tautan Reset");
            forgotForm.reset();
            setNotice(forgotNotice, "ok",
              "<strong>Tautan reset terkirim.</strong> Cek inbox email kamu dan ikuti petunjuknya untuk membuat kata sandi baru.");
          }, 700);
        });

        /* ----- Daftar (DEMO) ----- */
        var regForm = document.getElementById("regForm");
        var regGroup = document.getElementById("regGroup");
        var regErr = document.getElementById("regErr");
        var regNotice = document.getElementById("regNotice");
        var regBtn = document.getElementById("regBtn");

        regForm.addEventListener("submit", function (ev) {
          ev.preventDefault();
          clearNotice(regNotice);
          var name = document.getElementById("regName").value.trim();
          var email = document.getElementById("regEmail").value.trim();
          var pass = document.getElementById("regPass").value;

          if (name.length < 3) {
            setErr(regGroup, regErr, "Isi nama lengkap kamu dulu ya.");
            return;
          }
          if (!validEmail(email)) {
            setErr(regGroup, regErr, "Masukkan alamat email yang valid.");
            return;
          }
          if (pass.length < 8) {
            setErr(regGroup, regErr, "Kata sandi minimal 8 karakter.");
            return;
          }
          setErr(regGroup, regErr, "");
          setLoading(regBtn, true, "Daftar");
          setTimeout(function () {
            setLoading(regBtn, false, "Daftar");
            regForm.reset();
            showView("login");
            document.getElementById("loginEmail").value = email;
            setNotice(loginNotice, "ok",
              "<strong>Akun berhasil dibuat!</strong> Halo, " + escapeHtml(name.split(" ")[0]) +
              " — sekarang login pakai email dan kata sandi barumu.");
          }, 700);
        });
      })();
    </script>

    <script>
      /* ----- Animasi logo tiket NOVALIVE di hero -----
         Putaran 4,2 detik, jeda 1,2 detik, lalu berputar lagi tanpa henti.
         Klik logo untuk memutar ulang. prefers-reduced-motion: tampil frame akhir, tanpa loop. */
      (function () {
        "use strict";

        var DURATION = 4.2; // detik
        var PAUSE_MS = 1200; // jeda antar putaran

        function clamp(x) { return Math.min(1, Math.max(0, x)); }
        function prog(t, a, b) { return clamp((t - a) / (b - a)); }
        var ease = {
          outCubic: function (x) { return 1 - Math.pow(1 - x, 3); },
          inOutCubic: function (x) { return x < 0.5 ? 4 * x * x * x : 1 - Math.pow(-2 * x + 2, 3) / 2; },
          outBack: function (x, s) {
            s = (s === undefined) ? 1.70158 : s;
            var c = s + 1;
            return 1 + c * Math.pow(x - 1, 3) + s * Math.pow(x - 1, 2);
          }
        };

        var svg = document.getElementById("nvTicket");
        if (!svg) return;
        function part(name) { return svg.querySelector('[data-part="' + name + '"]'); }
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

        // Satu fungsi murni dari waktu (detik) ke tampilan.
        function apply(t) {
          var p = 1 + 0.04 * ease.inOutCubic(prog(t, 0.2, DURATION));
          parts.root.setAttribute("transform", "translate(650 300) scale(" + p + ") translate(-650 -300)");

          // 1. pita emas tergambar
          parts.ribbon.setAttribute("stroke-dashoffset", (1 - ease.inOutCubic(prog(t, 0.25, 1.3))).toFixed(4));

          // 2. tiket biru gelap naik dari kiri bawah
          var u1 = prog(t, 0.6, 1.45), e1 = ease.outCubic(u1);
          parts.dark.setAttribute("transform",
            "translate(" + (450 - 260 * (1 - e1)) + " " + (300 + 150 * (1 - e1)) + ") scale(" + (0.86 + 0.14 * e1) + ") translate(-450 -300)");
          parts.dark.setAttribute("opacity", clamp(u1 * 3).toFixed(3));

          // 3. lipatan biru muda terbuka dari sudut kirinya
          var u2 = prog(t, 0.95, 1.85), e2 = ease.outCubic(u2);
          parts.light.setAttribute("transform",
            "translate(" + (678 + 90 * (1 - e2)) + " " + (365 - 50 * (1 - e2)) + ") rotate(" + (-16 * (1 - e2)) + ") scale(" + (0.12 + 0.88 * e2) + " " + (0.6 + 0.4 * e2) + ") translate(-678 -365)");
          parts.light.setAttribute("opacity", clamp(u2 * 4).toFixed(3));

          // 4. titik-titik muncul satu per satu
          parts.dots.forEach(function (el, i) { pop(el, t, 1.45 + 0.1 * i); });
          parts.ovals.forEach(function (el, i) { pop(el, t, 1.9 + 0.1 * i); });

          // 5. kilau melintas sekali
          var u3 = prog(t, 2.15, 3.0), e3 = ease.inOutCubic(u3);
          parts.shine.setAttribute("transform", "translate(" + (-250 + e3 * 1850) + " 0) rotate(20 0 300)");
          parts.shine.setAttribute("opacity", (u3 > 0 && u3 < 1) ? 1 : 0);
        }

        var reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
        var raf = 0, timer = 0;

        function play() {
          cancelAnimationFrame(raf);
          clearTimeout(timer);
          if (reduceMotion) { apply(DURATION); return; }
          var start = 0;
          apply(0);
          function tick(now) {
            if (!start) start = now;
            var t = (now - start) / 1000;
            apply(Math.min(t, DURATION));
            if (t < DURATION) {
              raf = requestAnimationFrame(tick);
            } else {
              timer = setTimeout(play, PAUSE_MS); // jeda, lalu putar lagi
            }
          }
          raf = requestAnimationFrame(tick);
        }

        svg.addEventListener("click", play);
        window.novaliveTicketLogin = { apply: apply, play: play, DURATION: DURATION };
        play();
      })();
    </script>
  </body>
</html>
