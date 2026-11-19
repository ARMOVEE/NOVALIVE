<?php
/**
 * Halaman Manajemen Pesanan Tiket — Dashboard Admin
 * NOVA LIVE MVC
 */
$currentUser = $currentUser ?? ['full_name' => 'Admin NOVA LIVE', 'role' => 'admin'];
$userInitials = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $currentUser['full_name'] ?? 'AD'), 0, 2));
if (strlen($userInitials) < 2) $userInitials = 'AD';

$statusCounts = $statusCounts ?? ['Semua' => 0, 'Terkirim' => 0, 'Menunggu' => 0, 'Selesai' => 0, 'Dibatalkan' => 0];
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
<meta name="color-scheme" content="light" />
<link rel="icon" href="data:," />
<title><?= e($pageTitle ?? 'NOVA LIVE — Pesanan') ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
<style>
:root{
  --navy:#15497A; --navy-deep:#0E3254; --navy-soft:#1F6AA3;
  --gold:#EAD4A2; --gold-deep:#D4B475; --gold-dark:#8C6D1F; --gold-soft:#F5EFE0;
  --blue:#23649A; --sky:#509FC8; --sky-soft:#E6F2F8;
  --bg:#F5F1E6; --surface:#FFFFFF; --surface-2:#EFE9DA;
  --ink:#0E2A47; --muted:#4A607A; --faint:#93A9BF;
  --line:#E2DDD0;
  --green:#15803D; --green-bg:#DCFCE7;
  --red:#DC2626; --red-bg:#FEE2E2;
  --amber:#8C6D1F; --amber-bg:#F5EFE0;
  --gray-bg:#EFE9DA; --gray-tx:#52657C;
  --shadow:0 10px 30px rgba(14,50,84,.08);
  --radius:16px;
  color-scheme:light;
}
*{box-sizing:border-box}
html{scroll-behavior:smooth}
html,body{margin:0;padding:0}
body{
  font-family:"Inter",system-ui,sans-serif;
  background:var(--bg); color:var(--ink);
  font-size:15px; line-height:1.55;
  -webkit-font-smoothing:antialiased;
}
h1,h2,h3,.pj{font-family:"Plus Jakarta Sans","Inter",sans-serif}
button,[role="button"],select,summary,input:not([type="checkbox"],[type="radio"]){min-height:2.25rem}
button{font-family:inherit;cursor:pointer}
a{color:var(--blue);text-decoration:none}
:focus-visible{outline:2px solid var(--blue);outline-offset:2px;border-radius:6px}
svg.ic{width:20px;height:20px;flex:none;stroke:currentColor;fill:none;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round}

/* ============ LAYOUT ============ */
.app{display:flex;min-height:100vh}
.sidebar{
  width:264px;flex:none;background:var(--navy);color:#D9E6F2;
  display:flex;flex-direction:column;position:sticky;top:0;height:100vh;z-index:60;
}
.main{flex:1;min-width:0;display:flex;flex-direction:column}
.content{padding:28px 32px 56px;max-width:1240px;width:100%;margin:0 auto}

/* ============ SIDEBAR ============ */
.brand{padding:22px 22px 18px;display:flex;flex-direction:column;align-items:flex-start;gap:10px;border-bottom:1px solid rgba(255,255,255,.08)}
.brand-logo-btn{display:block;width:100%;max-width:218px;padding:0;margin:0;background:none;border:0;cursor:pointer;line-height:0;border-radius:8px}
.brand-logo-btn svg{width:100%;height:auto;display:block;overflow:visible}
.brand-name{font-family:"Plus Jakarta Sans",sans-serif;font-weight:800;font-size:19px;letter-spacing:.02em;color:#fff;line-height:1.1}
.brand-name em{font-style:normal;color:var(--gold)}
.brand-sub{font-size:11.5px;color:#9FB3C8;letter-spacing:.06em;text-transform:uppercase;margin-top:3px}
.nav{padding:16px 14px;display:flex;flex-direction:column;gap:4px;overflow-y:auto;scrollbar-width:none;-ms-overflow-style:none;overscroll-behavior:contain;-webkit-overflow-scrolling:touch}
.nav::-webkit-scrollbar{display:none;width:0;height:0}
.nav-label{font-size:11px;letter-spacing:.1em;text-transform:uppercase;color:#86A3BE;padding:12px 12px 6px}
.nav-item{
  display:flex;align-items:center;gap:12px;padding:11px 12px;border-radius:10px;
  color:#C7D6E6;background:none;border:0;text-align:left;font-size:14.5px;font-weight:500;width:100%;
  text-decoration:none;cursor:pointer;
}
.nav-item:hover{background:var(--navy-soft);color:#fff}
.nav-item.active{background:var(--gold);color:#0E2A47;font-weight:700}
.nav-item.active svg{stroke:#0E2A47}
.nav-badge{margin-left:auto;background:var(--gold);color:#0E2A47;font-size:11px;font-weight:700;border-radius:999px;padding:1px 8px}
.nav-item.active .nav-badge{background:var(--navy);color:#fff}
.side-foot{margin-top:auto;padding:16px;border-top:1px solid rgba(255,255,255,.08)}
.org-card{display:flex;gap:11px;align-items:center}
.avatar{width:40px;height:40px;border-radius:50%;background:var(--gold);color:#0E2A47;font-weight:800;display:flex;align-items:center;justify-content:center;font-family:"Plus Jakarta Sans",sans-serif;flex:none}
.org-name{color:#fff;font-weight:600;font-size:14px;line-height:1.3}
.org-role{font-size:12px;color:#9FB3C8}
.logout{margin-top:12px;width:100%;background:transparent;border:1px solid rgba(255,255,255,.22);color:#D9E6F2;border-radius:10px;display:flex;align-items:center;justify-content:center;gap:8px;font-size:13.5px;cursor:pointer;padding:8px 12px}
.logout:hover{background:rgba(255,255,255,.08)}

/* ============ SIDEBAR COLLAPSED — desktop ============ */
@media (min-width:901px){
  .sidebar{
    position:fixed;left:0;top:0;width:76px;height:100vh;overflow:hidden;cursor:pointer;
    transition:width .3s cubic-bezier(.4,0,.2,1),box-shadow .3s ease;
  }
  .sidebar.sb-open{width:264px;box-shadow:18px 0 38px rgba(7,18,34,.28);cursor:default}
  .main{margin-left:76px}

  .brand{align-items:center;padding:20px 12px 16px}
  .sidebar.sb-open .brand{align-items:flex-start;padding:22px 22px 18px}
  .brand-logo-btn{max-width:52px;transition:max-width .3s cubic-bezier(.4,0,.2,1)}
  .sidebar.sb-open .brand-logo-btn{max-width:218px}
  .brand-txt{opacity:0;visibility:hidden;max-height:0;overflow:hidden;white-space:nowrap;transition:opacity .15s ease,visibility .15s,max-height .3s ease}
  .sidebar.sb-open .brand-txt{opacity:1;visibility:visible;max-height:72px;transition:opacity .25s ease .12s,visibility 0s,max-height .3s ease}

  .nav{padding:14px 10px;overflow-x:hidden;transition:padding .3s ease}
  .sidebar.sb-open .nav{padding:16px 14px}
  .nav-label{flex:none;height:1px;padding:0;margin:12px 8px 8px;background:rgba(255,255,255,.14);font-size:0;letter-spacing:0;overflow:hidden;white-space:nowrap}
  .sidebar.sb-open .nav-label{height:auto;padding:12px 12px 6px;margin:0;background:none;font-size:11px;letter-spacing:.1em;overflow:visible}
  .nav-item{flex:none;position:relative;justify-content:center;gap:0;padding:11px 0;transition:gap .3s ease,padding .3s ease,background .15s ease,color .15s ease}
  .sidebar.sb-open .nav-item{justify-content:flex-start;gap:12px;padding:11px 12px}
  .nav-txt{opacity:0;visibility:hidden;max-width:0;overflow:hidden;white-space:nowrap;transition:opacity .12s ease,visibility .12s,max-width .3s ease}
  .sidebar.sb-open .nav-txt{opacity:1;visibility:visible;max-width:172px;transition:opacity .25s ease .12s,visibility 0s,max-width .3s ease}
  .nav-badge{position:absolute;left:50%;right:auto;top:0;transform:translateX(4px);margin:0;padding:0 5px;font-size:10px;line-height:1.6;box-shadow:0 0 0 2px var(--navy);z-index:2}
  .sidebar.sb-open .nav-badge{position:static;margin-left:auto;padding:1px 8px;font-size:11px;line-height:normal;left:auto;transform:none;box-shadow:none}

  .side-foot{padding:14px 10px;transition:padding .3s ease}
  .sidebar.sb-open .side-foot{padding:16px}
  .org-card{justify-content:center}
  .sidebar.sb-open .org-card{justify-content:flex-start}
  .org-txt{opacity:0;visibility:hidden;max-width:0;overflow:hidden;white-space:nowrap;transition:opacity .12s ease,visibility .12s,max-width .3s ease}
  .sidebar.sb-open .org-txt{opacity:1;visibility:visible;max-width:172px;transition:opacity .25s ease .12s,visibility 0s,max-width .3s ease}
  .logout{gap:0;padding-left:4px;padding-right:4px;transition:gap .3s ease}
  .sidebar.sb-open .logout{gap:8px}
  .btn-txt{opacity:0;visibility:hidden;max-width:0;overflow:hidden;white-space:nowrap;transition:opacity .12s ease,visibility .12s,max-width .3s ease}
  .sidebar.sb-open .btn-txt{opacity:1;visibility:visible;max-width:172px;transition:opacity .25s ease .12s,visibility 0s,max-width .3s ease}
}

/* ============ SIDEBAR: HIGHLIGHT CREAM, GLOW & GLIDE ============ */
.sb-glow,.nav-glide{display:none}
@media (min-width:901px){
  .sb-glow{
    display:block;position:absolute;left:0;top:0;width:230px;height:230px;border-radius:50%;
    background:radial-gradient(circle,rgba(234,212,162,.30) 0%,rgba(234,212,162,.12) 42%,rgba(234,212,162,0) 68%);
    pointer-events:none;opacity:0;transition:opacity .35s ease;z-index:0;will-change:transform;
  }
  .sidebar.sb-hover .sb-glow{opacity:1}
  .brand,.nav,.side-foot{position:relative;z-index:1}

  .nav{position:relative}
  .nav-glide{
    display:block;position:absolute;left:0;top:0;z-index:0;pointer-events:none;
    background:var(--gold);border-radius:10px;opacity:0;
    box-shadow:0 6px 18px rgba(7,18,34,.25);will-change:transform;
    transition:transform .5s cubic-bezier(.3,1.4,.4,1),width .5s cubic-bezier(.3,1.4,.4,1),height .5s cubic-bezier(.3,1.4,.4,1),opacity .25s ease;
  }
  .nav-item{z-index:1}

  .nav-item svg.ic{transition:transform .32s cubic-bezier(.34,1.56,.64,1)}
  .nav-item.glide-on{background:transparent;color:#0E2A47}
  .nav-item.glide-on .nav-badge{background:var(--navy);color:#fff}
  .nav-item.glide-on svg.ic{transform:scale(1.18)}
  .nav-item:hover svg.ic{transform:scale(1.3)}

  .nav.glide-live .nav-item.active{background:transparent;color:#C7D6E6}
  .nav.glide-live .nav-item.active svg{stroke:currentColor}
  .nav.glide-live .nav-item.active .nav-badge{background:var(--gold);color:#0E2A47}
  .nav.glide-live .nav-item.active.glide-on{color:#0E2A47}
  .nav.glide-live .nav-item.active.glide-on .nav-badge{background:var(--navy);color:#fff}
}

/* ============ TOPBAR ============ */
.topbar{
  position:sticky;top:0;z-index:50;background:var(--surface);border-bottom:1px solid var(--line);
  display:flex;align-items:center;gap:14px;padding:12px 32px;
}
.menu-btn{display:none;background:var(--surface-2);border:1px solid var(--line);border-radius:10px;width:42px;height:42px;align-items:center;justify-content:center;color:var(--ink);cursor:pointer}
.searchbox{flex:1;max-width:440px;position:relative}
.searchbox svg{position:absolute;left:12px;top:50%;transform:translateY(-50%);width:18px;height:18px;color:var(--faint)}
.searchbox input{
  width:100%;border:1px solid var(--line);background:var(--bg);border-radius:11px;
  padding:9px 14px 9px 40px;font-size:14px;color:var(--ink);font-family:inherit;outline:none;
}
.searchbox input::placeholder{color:var(--faint)}
.top-actions{margin-left:auto;display:flex;align-items:center;gap:10px}
.btn{
  display:inline-flex;align-items:center;gap:8px;border-radius:11px;border:1px solid transparent;
  padding:9px 16px;font-size:14px;font-weight:600;background:var(--surface-2);color:var(--ink);text-decoration:none;
}
.btn-gold{background:var(--gold);color:#0E2A47}
.btn-gold:hover{background:var(--gold-dark);color:#fff}
.btn-navy{background:var(--navy);color:#fff}
.btn-ghost{background:transparent;border-color:var(--line);cursor:pointer}
.btn-sm{padding:6px 12px;font-size:13px;border-radius:9px}
.icon-btn{position:relative;width:42px;height:42px;border-radius:11px;border:1px solid var(--line);background:var(--surface);color:var(--muted);display:flex;align-items:center;justify-content:center;cursor:pointer}
.icon-btn:hover{color:var(--ink)}
.dot{position:absolute;top:9px;right:10px;width:9px;height:9px;border-radius:50%;background:var(--red);border:2px solid var(--surface)}
.top-avatar{width:40px;height:40px;border-radius:50%;background:var(--navy);color:#fff;font-weight:700;display:flex;align-items:center;justify-content:center;font-size:14px}

/* ============ VIEWS & SECTIONS ============ */
.page-head{display:flex;flex-wrap:wrap;align-items:flex-end;gap:14px;margin-bottom:22px}
.page-head h1{font-size:26px;margin:0;font-weight:800;letter-spacing:-.01em}
.page-head p{margin:4px 0 0;color:var(--muted);font-size:14px}
.page-head .spacer{flex:1}

/* Tables */
.table-card{background:var(--surface);border:1px solid var(--line);border-radius:var(--radius);box-shadow:var(--shadow);overflow:hidden}
.table-tools{display:flex;flex-wrap:wrap;gap:10px;align-items:center;padding:16px 20px;border-bottom:1px solid var(--line)}
.table-tools h2{font-size:16.5px;margin:0;font-weight:700}
.chips{display:flex;gap:8px;flex-wrap:wrap}
.chip{border:1px solid var(--line);background:var(--surface);color:var(--muted);border-radius:999px;padding:6px 14px;font-size:13px;font-weight:600;min-height:2.25rem;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center}
.chip:hover{color:var(--ink)}
.chip.on{background:var(--navy);border-color:var(--navy);color:#fff}
.mini-search{position:relative;margin-left:auto}
.mini-search input{border:1px solid var(--line);background:var(--bg);border-radius:10px;padding:8px 12px 8px 36px;font-size:13.5px;color:var(--ink);font-family:inherit;width:230px;outline:none}
.mini-search svg{position:absolute;left:11px;top:50%;transform:translateY(-50%);width:16px;height:16px;color:var(--faint)}
.tbl-scroll{overflow-x:auto}
table{width:100%;border-collapse:collapse;min-width:760px}
th{text-align:left;font-size:11.5px;letter-spacing:.07em;text-transform:uppercase;color:var(--faint);font-weight:700;padding:12px 16px;border-bottom:1px solid var(--line);background:var(--surface-2)}
td{padding:14px 16px;border-bottom:1px solid var(--line);font-size:14px;vertical-align:middle}
tr:last-child td{border-bottom:0}
tbody tr:hover{background:var(--surface-2)}
.ev-sub{font-size:12.5px;color:var(--muted)}
.badge{display:inline-flex;align-items:center;gap:6px;font-size:12px;font-weight:700;border-radius:999px;padding:3px 11px;white-space:nowrap}
.badge::before{content:"";width:7px;height:7px;border-radius:50%;background:currentColor}
.b-live{background:var(--green-bg);color:var(--green)}
.b-draft{background:var(--gray-bg);color:var(--gray-tx)}
.b-done{background:var(--surface-2);color:var(--muted)}
.b-wait{background:var(--amber-bg);color:var(--amber)}
.b-refund{background:var(--red-bg);color:var(--red)}
.mini-btn{border:1px solid var(--line);background:var(--surface);color:var(--muted);border-radius:9px;padding:5px 11px;font-size:12.5px;font-weight:600;min-height:2.1rem;cursor:pointer}
.mini-btn:hover{color:var(--ink);border-color:var(--faint)}
.empty-row td{text-align:center;color:var(--muted);padding:34px 16px}

/* Modal */
.modal-back{position:fixed;inset:0;background:rgba(7,18,34,.55);backdrop-filter:blur(3px);z-index:100;display:none;align-items:flex-end;justify-content:center;padding:0}
.modal-back.open{display:flex}
.modal{background:var(--surface);width:100%;max-width:560px;max-height:92vh;overflow-y:auto;border-radius:20px 20px 0 0;padding:26px 26px 30px;animation:up .25s ease}
@keyframes up{from{transform:translateY(24px);opacity:.4}to{transform:none;opacity:1}}
@media(min-width:640px){.modal-back{align-items:center;padding:20px}.modal{border-radius:20px}}
.modal h2{margin:0 0 4px;font-size:20px;font-weight:800}
.modal .sub{color:var(--muted);font-size:13.5px;margin:0 0 20px}
.modal-x{float:right;background:var(--surface-2);border:0;width:38px;height:38px;border-radius:10px;font-size:18px;color:var(--muted);cursor:pointer}
.detail-list{display:grid;grid-template-columns:auto 1fr;gap:9px 18px;font-size:14px;margin-bottom:18px}
.detail-list dt{color:var(--muted)}
.detail-list dd{margin:0;font-weight:600;text-align:right}
.detail-total{border-top:1px dashed var(--line);padding-top:14px;display:flex;justify-content:space-between;font-family:"Plus Jakarta Sans",sans-serif;font-weight:800;font-size:17px}

/* Toast */
.toast{
  position:fixed;left:50%;bottom:26px;transform:translate(-50%,80px);z-index:200;
  background:var(--navy);color:#fff;padding:13px 20px;border-radius:12px;font-size:14px;font-weight:600;
  box-shadow:0 14px 34px rgba(0,0,0,.3);transition:transform .3s ease;display:flex;gap:9px;align-items:center;max-width:92vw;
}
.toast.show{transform:translate(-50%,0)}
.toast .ok{color:var(--gold)}

/* Notif dropdown */
.notif-wrap{position:relative}
.notif-panel{
  position:absolute;right:0;top:52px;width:330px;max-width:88vw;background:var(--surface);
  border:1px solid var(--line);border-radius:14px;box-shadow:var(--shadow);padding:8px;display:none;z-index:80;
}
.notif-panel.open{display:block}
.notif-panel h3{font-size:14px;margin:8px 10px 6px}
.notif-item{display:flex;gap:11px;padding:10px;border-radius:10px}
.notif-item:hover{background:var(--surface-2)}
.notif-item p{margin:0;font-size:13px;line-height:1.45}
.notif-item small{color:var(--faint);font-size:11.5px}
.notif-ic{width:34px;height:34px;border-radius:9px;background:var(--gold-soft);color:var(--gold-dark);display:flex;align-items:center;justify-content:center;flex:none}

.scrim{position:fixed;inset:0;background:rgba(7,18,34,.5);z-index:55;display:none}
.scrim.show{display:block}

/* ============ RESPONSIVE ============ */
@media(max-width:900px){
  .sidebar{
    position:fixed;left:0;right:0;bottom:0;top:auto;width:100%;height:auto;
    transform:none;visibility:visible;flex-direction:row;z-index:70;
    box-shadow:0 -6px 24px rgba(7,18,34,.25);
  }
  .brand,.side-foot,.nav-label{display:none}
  .nav{flex-direction:row;flex-wrap:wrap;width:100%;padding:8px 6px calc(8px + env(safe-area-inset-bottom));gap:2px;justify-content:flex-start;overflow:visible}
  .nav-item{position:relative;flex:1 1 15%;min-width:54px;flex-direction:column;align-items:center;justify-content:center;text-align:center;gap:4px;font-size:10px;line-height:1.15;padding:7px 2px;border-radius:10px;min-height:3.2rem}
  .nav-item svg.ic{width:21px;height:21px}
  .nav-badge{position:absolute;top:2px;left:50%;transform:translateX(9px);margin:0;padding:0 5px;font-size:10px;line-height:1.5}
  .menu-btn{display:none}
  .topbar{padding:12px 16px}
  .content{padding:20px 16px 248px}
  .searchbox{display:none}
  .btn-create span{display:none}
  .btn-create{padding:9px 12px}
}
@media(max-width:560px){
  .mini-search{margin-left:0;width:100%}
  .mini-search input{width:100%}
  .page-head h1{font-size:22px}
}
@media (prefers-reduced-motion:reduce){
  *{animation:none!important;transition:none!important}
  html{scroll-behavior:auto}
}
</style>
</head>
<body>
<div class="app">

  <?php require __DIR__ . '/sidebar.php'; ?>

  <!-- ================= MAIN ================= -->
  <div class="main">
    <header class="topbar">
      <button class="menu-btn" id="menuBtn" aria-label="Buka menu">
        <svg class="ic" viewBox="0 0 24 24"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
      </button>
      <div class="searchbox">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/></svg>
        <input type="search" id="globalSearch" placeholder="Cari event, pesanan, atau pembeli…" aria-label="Pencarian global" />
      </div>
      <div class="top-actions">
        <div class="notif-wrap">
          <button class="icon-btn" id="notifBtn" aria-label="Notifikasi">
            <svg class="ic" viewBox="0 0 24 24"><path d="M18 9a6 6 0 1 0-12 0c0 6-2.5 7-2.5 7h17S18 15 18 9zM10 20a2.2 2.2 0 0 0 4 0"/></svg>
            <span class="dot" id="notifDot"></span>
          </button>
          <div class="notif-panel" id="notifPanel" role="dialog" aria-label="Panel notifikasi">
            <h3>Notifikasi</h3>
            <div class="notif-item">
              <div class="notif-ic"><svg class="ic" viewBox="0 0 24 24" style="width:17px;height:17px"><path d="M4 9.5A8.5 8.5 0 0 1 20 9.5c0 3-1.6 4-1.6 4H5.6S4 12.5 4 9.5zM9 3h6M10 20.5a2 2 0 0 0 4 0"/></svg></div>
              <div><p><b>Tulus Live in Concert</b> hampir habis: sisa 400 dari 5.000 tiket.</p><small>25 menit lalu</small></div>
            </div>
            <div class="notif-item">
              <div class="notif-ic"><svg class="ic" viewBox="0 0 24 24" style="width:17px;height:17px"><rect x="3" y="6" width="18" height="13" rx="2"/><path d="M3 10.5h18"/></svg></div>
              <div><p>Pencairan <b>Rp42.500.000</b> sudah masuk ke rekening BCA ••4821.</p><small>2 jam lalu</small></div>
            </div>
          </div>
        </div>
        <a href="<?= base_url('events/create') ?>" class="btn btn-gold btn-create" id="createEventBtn">
          <svg class="ic" viewBox="0 0 24 24" style="width:17px;height:17px"><path d="M12 5v14M5 12h14"/></svg>
          <span>Buat Event</span>
        </a>
        <div class="top-avatar" id="topAvatar" title="<?= e($currentUser['full_name'] ?? 'Admin') ?> — <?= e(ucfirst($currentUser['role'] ?? 'Admin')) ?>"><?= e($userInitials) ?></div>
      </div>
    </header>

    <main class="content">
      <div class="page-head">
        <div>
          <h1>Pesanan</h1>
          <p>Semua transaksi pembelian tiket dari event kamu</p>
        </div>
        <div class="spacer"></div>
        <button type="button" class="btn btn-ghost" id="exportBtn">
          <svg class="ic" viewBox="0 0 24 24" style="width:17px;height:17px"><path d="M12 3v12M7 10l5 5 5-5M4 21h16"/></svg>
          Export CSV
        </button>
      </div>

      <div class="table-card">
        <div class="table-tools">
          <div class="chips" id="orderChips">
            <button type="button" class="chip <?= empty($filters['status']) ? 'on' : '' ?>" data-filter="semua">Semua (<?= (int)$statusCounts['Semua'] ?>)</button>
            <button type="button" class="chip <?= ($filters['status'] ?? '') === 'Terkirim' ? 'on' : '' ?>" data-filter="terkirim">Terkirim (<?= (int)$statusCounts['Terkirim'] ?>)</button>
            <button type="button" class="chip <?= ($filters['status'] ?? '') === 'Menunggu' ? 'on' : '' ?>" data-filter="menunggu">Menunggu (<?= (int)$statusCounts['Menunggu'] ?>)</button>
            <button type="button" class="chip <?= ($filters['status'] ?? '') === 'Selesai' ? 'on' : '' ?>" data-filter="selesai">Selesai (<?= (int)$statusCounts['Selesai'] ?>)</button>
            <button type="button" class="chip <?= ($filters['status'] ?? '') === 'Dibatalkan' ? 'on' : '' ?>" data-filter="dibatalkan">Dibatalkan (<?= (int)$statusCounts['Dibatalkan'] ?>)</button>
          </div>
          <div class="mini-search">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/></svg>
            <input type="search" id="orderSearch" placeholder="Cari invoice / pembeli…" aria-label="Cari pesanan" value="<?= e($filters['search'] ?? '') ?>" />
          </div>
        </div>
        <div class="tbl-scroll">
          <table>
            <thead>
              <tr>
                <th>Invoice</th>
                <th>Pembeli</th>
                <th>Event</th>
                <th>Tiket</th>
                <th>Total</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody id="orderTableBody">
              <?php if (empty($data)): ?>
                <tr class="empty-row"><td colspan="7">Tidak ada data pesanan tiket yang sesuai.</td></tr>
              <?php else: ?>
                <?php foreach ($data as $o): 
                  $st = $o['status'];
                  $stLower = strtolower($st);
                  $cls = ($st === 'Terkirim' || $st === 'Selesai') ? 'b-live' : ($st === 'Menunggu' ? 'b-wait' : 'b-refund');
                  $formattedDate = date('d M Y, H.i', strtotime($o['created_at'] ?? 'now'));
                ?>
                  <tr class="order-row" data-invoice="<?= strtolower(e($o['invoice'])) ?>" data-buyer="<?= strtolower(e($o['buyer_name'])) ?>" data-email="<?= strtolower(e($o['buyer_email'])) ?>" data-event="<?= strtolower(e($o['event_title'])) ?>" data-status="<?= $stLower ?>">
                    <td>
                      <b><?= e($o['invoice']) ?></b>
                      <div class="ev-sub"><?= e($formattedDate) ?></div>
                    </td>
                    <td>
                      <div style="font-weight:600;"><?= e($o['buyer_name']) ?></div>
                      <div class="ev-sub"><?= e($o['buyer_email']) ?></div>
                    </td>
                    <td style="min-width:180px">
                      <div style="font-weight:600;"><?= e($o['event_title']) ?></div>
                      <div class="ev-sub">Kode: <?= e($o['ticket_code'] ?? '-') ?></div>
                    </td>
                    <td><?= (int)$o['qty'] ?> tiket</td>
                    <td><b><?= rupiah($o['total_price']) ?></b></td>
                    <td><span class="badge <?= $cls ?>"><?= e($st) ?></span></td>
                    <td>
                      <button type="button" class="mini-btn js-order-detail"
                        data-inv="<?= e($o['invoice']) ?>"
                        data-buyer="<?= e($o['buyer_name']) ?>"
                        data-email="<?= e($o['buyer_email']) ?>"
                        data-event="<?= e($o['event_title']) ?>"
                        data-qty="<?= (int)$o['qty'] ?>"
                        data-total="<?= rupiah($o['total_price']) ?>"
                        data-status="<?= e($st) ?>"
                        data-ticket="<?= e($o['ticket_code'] ?? '-') ?>"
                        data-date="<?= e($formattedDate) ?>">
                        Detail
                      </button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>
</div>

<!-- ================= MODAL DETAIL PESANAN ================= -->
<div class="modal-back" id="orderModalBack" role="dialog" aria-modal="true" aria-labelledby="orderModalTitle">
  <div class="modal">
    <button type="button" class="modal-x" id="modalCloseBtn" aria-label="Tutup modal">✕</button>
    <h2 id="orderModalTitle">Detail Pesanan</h2>
    <p class="sub" id="orderModalInv">-</p>

    <dl class="detail-list" id="orderDetailList">
      <dt>Pembeli</dt><dd id="mdBuyer">-</dd>
      <dt>Email</dt><dd id="mdEmail">-</dd>
      <dt>Event</dt><dd id="mdEvent">-</dd>
      <dt>Jumlah Tiket</dt><dd id="mdQty">-</dd>
      <dt>Status</dt><dd id="mdStatus">-</dd>
      <dt>Kode Tiket</dt><dd id="mdTicket" style="font-family:monospace;">-</dd>
    </dl>

    <div class="detail-total">
      <span>Total Bayar</span>
      <span id="mdTotal">-</span>
    </div>
  </div>
</div>

<script>
/* ==================== ORDER SEARCH & CHIP FILTER ==================== */
(function(){
  var currentFilter = "semua";
  var searchInput = document.getElementById("orderSearch");
  var chips = document.querySelectorAll("#orderChips .chip");
  var rows = document.querySelectorAll("#orderTableBody .order-row");

  function filterRows() {
    var q = (searchInput ? searchInput.value : "").trim().toLowerCase();
    rows.forEach(function(r) {
      var inv = r.getAttribute("data-invoice") || "";
      var buyer = r.getAttribute("data-buyer") || "";
      var email = r.getAttribute("data-email") || "";
      var ev = r.getAttribute("data-event") || "";
      var st = r.getAttribute("data-status") || "";

      var matchQ = !q || inv.indexOf(q) > -1 || buyer.indexOf(q) > -1 || email.indexOf(q) > -1 || ev.indexOf(q) > -1;
      var matchSt = (currentFilter === "semua") || (st === currentFilter);
      r.style.display = (matchQ && matchSt) ? "" : "none";
    });
  }

  chips.forEach(function(c) {
    c.addEventListener("click", function() {
      chips.forEach(function(x){ x.classList.remove("on"); });
      c.classList.add("on");
      currentFilter = c.getAttribute("data-filter");
      filterRows();
    });
  });

  if (searchInput) {
    searchInput.addEventListener("input", filterRows);
  }

  // Modal Detail Pesanan
  var modal = document.getElementById("orderModalBack");
  var modalClose = document.getElementById("modalCloseBtn");

  document.addEventListener("click", function(e) {
    var btn = e.target.closest(".js-order-detail");
    if (!btn || !modal) return;
    document.getElementById("orderModalInv").textContent = (btn.getAttribute("data-inv") || "") + " · " + (btn.getAttribute("data-date") || "");
    document.getElementById("mdBuyer").textContent = btn.getAttribute("data-buyer") || "-";
    document.getElementById("mdEmail").textContent = btn.getAttribute("data-email") || "-";
    document.getElementById("mdEvent").textContent = btn.getAttribute("data-event") || "-";
    document.getElementById("mdQty").textContent = (btn.getAttribute("data-qty") || "0") + " tiket";
    document.getElementById("mdStatus").textContent = btn.getAttribute("data-status") || "-";
    document.getElementById("mdTicket").textContent = btn.getAttribute("data-ticket") || "-";
    document.getElementById("mdTotal").textContent = btn.getAttribute("data-total") || "-";
    modal.classList.add("open");
  });

  function closeModal() {
    if (modal) modal.classList.remove("open");
  }

  if (modalClose) modalClose.addEventListener("click", closeModal);
  if (modal) {
    modal.addEventListener("click", function(e) {
      if (e.target === modal) closeModal();
    });
  }
  document.addEventListener("keydown", function(e) {
    if (e.key === "Escape") closeModal();
  });

  // Export CSV
  var exportBtn = document.getElementById("exportBtn");
  if (exportBtn) {
    exportBtn.addEventListener("click", function() {
      var lines = [["Invoice", "Tanggal", "Pembeli", "Email", "Event", "Qty", "Total", "Status", "Kode Tiket"].join(",")];
      rows.forEach(function(r) {
        if (r.style.display !== "none") {
          var btn = r.querySelector(".js-order-detail");
          if (btn) {
            lines.push([
              '"' + (btn.getAttribute("data-inv") || "") + '"',
              '"' + (btn.getAttribute("data-date") || "") + '"',
              '"' + (btn.getAttribute("data-buyer") || "") + '"',
              '"' + (btn.getAttribute("data-email") || "") + '"',
              '"' + (btn.getAttribute("data-event") || "") + '"',
              btn.getAttribute("data-qty") || "0",
              '"' + (btn.getAttribute("data-total") || "") + '"',
              '"' + (btn.getAttribute("data-status") || "") + '"',
              '"' + (btn.getAttribute("data-ticket") || "") + '"'
            ].join(","));
          }
        }
      });
      var blob = new Blob([lines.join("\n")], { type: "text/csv;charset=utf-8;" });
      var url = URL.createObjectURL(blob);
      var a = document.createElement("a");
      a.href = url;
      a.download = "pesanan_tiket_novalive.csv";
      document.body.appendChild(a);
      a.click();
      document.body.removeChild(a);
      toast("Data pesanan berhasil diekspor ke file CSV.");
    });
  }
})();
</script>
</body>
</html>
