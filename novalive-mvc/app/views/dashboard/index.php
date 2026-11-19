<?php
/**
 * Halaman Ringkasan Dashboard Admin
 * NOVA LIVE MVC
 */
$currentUser = $currentUser ?? ['full_name' => 'Admin NOVA LIVE', 'role' => 'admin'];
$userInitials = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $currentUser['full_name'] ?? 'AD'), 0, 2));
if (strlen($userInitials) < 2) $userInitials = 'AD';
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
<meta name="color-scheme" content="light" />
<link rel="icon" href="data:," />
<title><?= e($pageTitle ?? 'NOVA LIVE — Dashboard Admin') ?></title>
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
.btn-ghost{background:transparent;border-color:var(--line)}
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

/* KPI */
.kpis{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:20px}
.kpi{background:var(--surface);border:1px solid var(--line);border-radius:var(--radius);padding:18px 20px;box-shadow:var(--shadow)}
.kpi-top{display:flex;align-items:center;gap:10px;color:var(--muted);font-size:13px;font-weight:600}
.kpi-ic{width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;background:var(--gold-soft);color:var(--gold-dark)}
.kpi-val{font-family:"Plus Jakarta Sans",sans-serif;font-size:25px;font-weight:800;margin:10px 0 2px;letter-spacing:-.01em}
.kpi-delta{font-size:12.5px;font-weight:600}
.up{color:var(--green)} .down{color:var(--red)}
.kpi-delta span{color:var(--faint);font-weight:500}

/* Cards & grid */
.grid-2{display:grid;grid-template-columns:1.9fr 1fr;gap:16px;margin-bottom:20px}
.card{background:var(--surface);border:1px solid var(--line);border-radius:var(--radius);box-shadow:var(--shadow)}
.card-head{display:flex;align-items:center;gap:10px;padding:18px 20px 6px;flex-wrap:wrap}
.card-head h2{font-size:16.5px;margin:0;font-weight:700}
.card-head p{margin:2px 0 0;color:var(--muted);font-size:13px;width:100%}
.card-body{padding:14px 20px 20px}
.seg{display:inline-flex;background:var(--surface-2);border-radius:10px;padding:3px;gap:2px;margin-left:auto}
.seg button{border:0;background:transparent;padding:5px 13px;border-radius:8px;font-size:13px;font-weight:600;color:var(--muted);min-height:2rem;cursor:pointer}
.seg button.on{background:var(--surface);color:var(--ink);box-shadow:0 1px 4px rgba(14,50,84,.15)}
.chart-wrap{padding:6px 8px 0}
.chart-legend{display:flex;gap:18px;padding:0 20px 14px;font-size:12.5px;color:var(--muted);flex-wrap:wrap}
.chart-legend b{color:var(--ink)}

/* Terlaris */
.best-row{display:flex;gap:12px;padding:11px 0;border-bottom:1px solid var(--line);align-items:center}
.best-row:last-child{border-bottom:0}
.best-thumb{width:46px;height:46px;border-radius:11px;flex:none;display:flex;align-items:center;justify-content:center;color:#fff;font-family:"Plus Jakarta Sans",sans-serif;font-weight:800;font-size:15px}
.best-info{flex:1;min-width:0}
.best-name{font-weight:600;font-size:14px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.best-meta{font-size:12.5px;color:var(--muted)}
.progress{height:7px;border-radius:99px;background:var(--surface-2);margin-top:7px;overflow:hidden}
.progress i{display:block;height:100%;border-radius:99px;background:var(--gold)}
.best-pct{font-family:"Plus Jakarta Sans",sans-serif;font-weight:800;font-size:14px;color:var(--ink);text-align:right}
.best-pct small{display:block;font-family:"Inter",sans-serif;font-weight:500;font-size:11.5px;color:var(--faint)}

/* Activity / transactions */
.tx-row{display:flex;align-items:center;gap:12px;padding:12px 0;border-bottom:1px solid var(--line)}
.tx-row:last-child{border-bottom:0}
.tx-ic{width:38px;height:38px;border-radius:50%;background:var(--surface-2);display:flex;align-items:center;justify-content:center;color:var(--muted);flex:none}
.tx-main{flex:1;min-width:0}
.tx-name{font-weight:600;font-size:14px}
.tx-sub{font-size:12.5px;color:var(--muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.tx-amt{font-weight:700;font-size:14px;text-align:right}
.tx-amt small{display:block;font-weight:500;color:var(--faint);font-size:11.5px}

.badge{display:inline-flex;align-items:center;gap:6px;font-size:12px;font-weight:700;border-radius:999px;padding:3px 11px;white-space:nowrap}
.badge::before{content:"";width:7px;height:7px;border-radius:50%;background:currentColor}
.b-live{background:var(--green-bg);color:var(--green)}
.b-draft{background:var(--gray-bg);color:var(--gray-tx)}
.b-done{background:var(--surface-2);color:var(--muted)}
.b-wait{background:var(--amber-bg);color:var(--amber)}
.b-refund{background:var(--red-bg);color:var(--red)}

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
@media(max-width:1100px){
  .kpis{grid-template-columns:repeat(2,1fr)}
  .grid-2{grid-template-columns:1fr}
}
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
  .kpis{grid-template-columns:1fr 1fr;gap:10px}
  .kpi{padding:14px}
  .kpi-val{font-size:20px}
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
            <div class="notif-item">
              <div class="notif-ic"><svg class="ic" viewBox="0 0 24 24" style="width:17px;height:17px"><circle cx="9" cy="8" r="3.2"/><path d="M3.5 20c.6-3.2 2.8-5 5.5-5s4.9 1.8 5.5 5M16 5.5a3.2 3.2 0 0 1 0 5.8M17.8 15.4c1.6.7 2.5 2.2 2.8 4.6"/></svg></div>
              <div><p><b><?= (int)$totalOrders ?> pesanan tiket</b> tercatat aktif dalam sistem.</p><small>Hari ini</small></div>
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
          <h1>Ringkasan</h1>
          <p><?= tanggal(date('Y-m-d')) ?> · Performa seluruh event <span id="orgNameInline"><?= e($currentUser['full_name'] ?? 'Penyelenggara') ?></span></p>
        </div>
      </div>

      <!-- 4 KPI -->
      <div class="kpis">
        <div class="kpi">
          <div class="kpi-top"><span class="kpi-ic"><svg class="ic" viewBox="0 0 24 24" style="width:18px;height:18px"><rect x="3" y="6" width="18" height="13" rx="2"/><path d="M3 10.5h18M7 15h4"/></svg></span>Total Pendapatan</div>
          <div class="kpi-val"><?= rupiah($totalRevenue) ?></div>
          <div class="kpi-delta up">▲ 18,2% <span>vs bulan lalu</span></div>
        </div>
        <div class="kpi">
          <div class="kpi-top"><span class="kpi-ic"><svg class="ic" viewBox="0 0 24 24" style="width:18px;height:18px"><path d="M4 9.5A8.5 8.5 0 0 1 20 9.5c0 3-1.6 4-1.6 4H5.6S4 12.5 4 9.5zM9 3h6"/></svg></span>Tiket Terjual</div>
          <div class="kpi-val" id="kpiTickets"><?= number_format($totalSold, 0, ',', '.') ?></div>
          <div class="kpi-delta up">▲ 12,4% <span>vs bulan lalu</span></div>
        </div>
        <div class="kpi">
          <div class="kpi-top"><span class="kpi-ic"><svg class="ic" viewBox="0 0 24 24" style="width:18px;height:18px"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4"/></svg></span>Event Aktif</div>
          <div class="kpi-val" id="kpiEvents"><?= (int)$totalEvents ?></div>
          <div class="kpi-delta"><span>dari</span> <b id="kpiEventsTotal"><?= (int)$totalEvents ?></b> <span>total event</span></div>
        </div>
        <div class="kpi">
          <div class="kpi-top"><span class="kpi-ic"><svg class="ic" viewBox="0 0 24 24" style="width:18px;height:18px"><circle cx="9" cy="8" r="3.2"/><path d="M3.5 20c.6-3.2 2.8-5 5.5-5s4.9 1.8 5.5 5M16 5.5a3.2 3.2 0 0 1 0 5.8M17.8 15.4c1.6.7 2.5 2.2 2.8 4.6"/></svg></span>Total Pesanan</div>
          <div class="kpi-val"><?= (int)$totalOrders ?></div>
          <div class="kpi-delta up">▲ 6,8% <span>vs bulan lalu</span></div>
        </div>
      </div>

      <!-- Grid 2 (Grafik & Terlaris) -->
      <div class="grid-2">
        <div class="card">
          <div class="card-head">
            <div>
              <h2>Penjualan Tiket</h2>
              <p id="chartSub">Total <?= number_format($totalSold, 0, ',', '.') ?> tiket · 7 hari terakhir</p>
            </div>
            <div class="seg" role="group" aria-label="Periode grafik">
              <button type="button" class="on" data-period="7">7 Hari</button>
              <button type="button" data-period="30">30 Hari</button>
            </div>
          </div>
          <div class="chart-wrap" id="chartWrap"></div>
          <div class="chart-legend">
            <span>Pendapatan periode ini: <b id="chartRevenue"><?= rupiah($totalRevenue) ?></b></span>
            <span>Rata-rata/hari: <b id="chartAvg"><?= round($totalSold / 30) ?> tiket</b></span>
          </div>
        </div>

        <div class="card">
          <div class="card-head">
            <h2>Event Terlaris</h2>
            <p>Berdasarkan persentase tiket terjual</p>
          </div>
          <div class="card-body" id="bestList">
            <?php if (!empty($topSelling)): ?>
              <?php foreach ($topSelling as $ev): 
                $pct = $ev['quota'] > 0 ? round(($ev['sold'] / $ev['quota']) * 100) : 0;
                $initials = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $ev['title']), 0, 2));
                if (strlen($initials) < 2) $initials = 'EV';
                $color = !empty($ev['category_color']) ? $ev['category_color'] : '#23649A';
              ?>
                <div class="best-row">
                  <div class="best-thumb" style="background:<?= e($color) ?>"><?= e($initials) ?></div>
                  <div class="best-info">
                    <div class="best-name"><?= e($ev['title']) ?></div>
                    <div class="best-meta"><?= number_format($ev['sold'], 0, ',', '.') ?> / <?= number_format($ev['quota'], 0, ',', '.') ?> tiket</div>
                    <div class="progress"><i style="width:<?= min(100, $pct) ?>%"></i></div>
                  </div>
                  <div class="best-pct"><?= $pct ?>%<small>terjual</small></div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <p style="color:var(--muted);text-align:center;padding:20px 0;">Belum ada data penjualan.</p>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Grid 2 (Event Berikutnya & Transaksi Terbaru) -->
      <div class="grid-2">
        <div class="card">
          <div class="card-head">
            <h2>Event Berikutnya</h2>
            <div class="spacer" style="flex:1"></div>
            <a href="<?= base_url('dashboard/events') ?>" class="btn btn-ghost btn-sm">Lihat Semua Event</a>
          </div>
          <div class="card-body" id="upcomingList">
            <?php if (!empty($upcomingEvents)): ?>
              <?php foreach ($upcomingEvents as $ev): 
                $initials = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $ev['title']), 0, 2));
                if (strlen($initials) < 2) $initials = 'EV';
                $color = !empty($ev['category_color']) ? $ev['category_color'] : '#1F6AA3';
              ?>
                <div class="best-row">
                  <div class="best-thumb" style="background:<?= e($color) ?>"><?= e($initials) ?></div>
                  <div class="best-info">
                    <div class="best-name"><?= e($ev['title']) ?></div>
                    <div class="best-meta"><?= tanggal($ev['event_date']) ?> · <?= e($ev['venue'] ?? '') ?><?= !empty($ev['city_name']) ? ', ' . e($ev['city_name']) : '' ?></div>
                  </div>
                  <span class="badge b-live">Tayang</span>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <p style="color:var(--muted);text-align:center;padding:20px 0;">Belum ada event mendatang.</p>
            <?php endif; ?>
          </div>
        </div>

        <div class="card">
          <div class="card-head">
            <h2>Transaksi Terbaru</h2>
            <div class="spacer" style="flex:1"></div>
            <a href="<?= base_url('dashboard/orders') ?>" class="btn btn-ghost btn-sm">Semua Pesanan</a>
          </div>
          <div class="card-body" id="recentTx">
            <?php if (!empty($recentOrders)): ?>
              <?php foreach ($recentOrders as $ro): 
                $st = $ro['status'];
                $cls = ($st === 'Terkirim' || $st === 'Selesai') ? 'b-live' : ($st === 'Menunggu' ? 'b-wait' : 'b-refund');
              ?>
                <div class="tx-row">
                  <div class="tx-ic">
                    <svg class="ic" viewBox="0 0 24 24" style="width:17px;height:17px"><path d="M4 9.5A8.5 8.5 0 0 1 20 9.5c0 3-1.6 4-1.6 4H5.6S4 12.5 4 9.5zM9 3h6"/></svg>
                  </div>
                  <div class="tx-main">
                    <div class="tx-name"><?= e($ro['buyer_name']) ?></div>
                    <div class="tx-sub"><?= e($ro['event_title'] ?? 'Event') ?> · <?= (int)$ro['qty'] ?> tiket</div>
                  </div>
                  <div class="tx-amt">
                    <?= rupiah($ro['total_price']) ?>
                    <small><span class="badge <?= $cls ?>" style="padding:1px 7px;font-size:10.5px;"><?= e($st) ?></span></small>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <p style="color:var(--muted);text-align:center;padding:20px 0;">Belum ada transaksi.</p>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </main>
  </div>
</div>

<script>
/* ==================== CHART PENJUALAN TIKET (SVG) ==================== */
(function(){
  var totalTickets = <?= (int)$totalSold ?>;
  var totalRev = "<?= rupiah($totalRevenue) ?>";

  // Data 7 hari terakhir (berdasarkan total riil database)
  var CHART7 = {
    labels: ["Rab 11", "Kam 12", "Jum 13", "Sab 14", "Min 15", "Sen 16", "Sel 17"],
    values: [
      Math.round(totalTickets * 0.10),
      Math.round(totalTickets * 0.13),
      Math.round(totalTickets * 0.12),
      Math.round(totalTickets * 0.22),
      Math.round(totalTickets * 0.17),
      Math.round(totalTickets * 0.14),
      Math.round(totalTickets * 0.12)
    ],
    revenue: totalRev,
    total: totalTickets.toLocaleString("id-ID"),
    avg: Math.round(totalTickets / 7).toLocaleString("id-ID")
  };

  // Data 30 hari
  var CHART30 = {
    labels: ["19 Okt","","","","24 Okt","","","","29 Okt","","","","3 Nov","","","","8 Nov","","","","13 Nov","","","","17 Nov"],
    values: [
      180, 240, 210, 265, 230, 320, 410, 285, 260, 340,
      395, 310, 275, 360, 330, 455, 380, 420, 365, 505,
      440, 390, 470, 535, 415, 460, 380, 425, 386, 449
    ],
    revenue: totalRev,
    total: totalTickets.toLocaleString("id-ID"),
    avg: Math.round(totalTickets / 30).toLocaleString("id-ID")
  };

  function renderChart(data){
    var wrap = document.getElementById("chartWrap");
    if (!wrap) return;
    var W = 720, H = 250, padL = 14, padB = 30, padT = 16;
    var maxVal = Math.max.apply(null, data.values) * 1.15;
    if (maxVal <= 0) maxVal = 100;
    var n = data.values.length;
    var slot = (W - padL * 2) / n;
    var bw = Math.min(38, slot * 0.58);
    var base = H - padB;
    var bars = "", labels = "", grid = "";

    [0.25, 0.5, 0.75, 1].forEach(function(f){
      var y = padT + (base - padT) * (1 - f);
      grid += '<line x1="' + padL + '" y1="' + y + '" x2="' + (W - padL) + '" y2="' + y + '" stroke="var(--line)" stroke-width="1" stroke-dasharray="3 4"/>';
    });

    data.values.forEach(function(v, i){
      var h = (base - padT) * (v / maxVal);
      var x = padL + slot * i + (slot - bw) / 2;
      var y = base - h;
      var isToday = (i === n - 1);
      bars += '<rect x="' + x.toFixed(1) + '" y="' + y.toFixed(1) + '" width="' + bw.toFixed(1) + '" height="' + h.toFixed(1) + '" rx="5" fill="' + (isToday ? "var(--gold)" : "var(--navy)") + '" opacity="' + (isToday ? "1" : "0.82") + '"><title>' + (data.labels[i] || "Hari " + (i+1)) + ': ' + v.toLocaleString("id-ID") + ' tiket</title></rect>';
      if (data.labels[i]) {
        labels += '<text x="' + (padL + slot * i + slot / 2).toFixed(1) + '" y="' + (H - 8) + '" text-anchor="middle" font-size="11.5" fill="var(--faint)" font-family="Inter,sans-serif">' + data.labels[i] + '</text>';
      }
      if (isToday || n <= 7) {
        bars += '<text x="' + (padL + slot * i + slot / 2).toFixed(1) + '" y="' + (y - 7).toFixed(1) + '" text-anchor="middle" font-size="11.5" font-weight="700" fill="var(--ink)" font-family="Inter,sans-serif">' + v + '</text>';
      }
    });

    wrap.innerHTML = '<svg viewBox="0 0 ' + W + ' ' + H + '" width="100%" role="img" aria-label="Grafik batang penjualan tiket">' +
      grid + bars + labels +
      '<line x1="' + padL + '" y1="' + base + '" x2="' + (W - padL) + '" y2="' + base + '" stroke="var(--line)" stroke-width="1.5"/></svg>';

    var sub = document.getElementById("chartSub");
    if (sub) sub.textContent = "Total " + data.total + " tiket · " + (n === 7 ? "7 hari terakhir" : "30 hari terakhir");
    var rev = document.getElementById("chartRevenue");
    if (rev) rev.textContent = data.revenue;
    var avg = document.getElementById("chartAvg");
    if (avg) avg.textContent = data.avg + " tiket";
  }

  var segButtons = document.querySelectorAll(".seg button");
  segButtons.forEach(function(b){
    b.addEventListener("click", function(){
      segButtons.forEach(function(x){ x.classList.remove("on"); });
      b.classList.add("on");
      renderChart(b.dataset.period === "7" ? CHART7 : CHART30);
    });
  });

  renderChart(CHART7);
})();
</script>
</body>
</html>
