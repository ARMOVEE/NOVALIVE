<?php
/**
 * Halaman Manajemen Stok Tiket — Gaya Quixotic
 * NOVA LIVE MVC — Langkah 11 Revisi (Tahap 1)
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
  <title><?= e($pageTitle ?? 'NOVA LIVE — Stok Tiket') ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <style>
    button, [role="button"], select, summary, input:not([type="checkbox"], [type="radio"]) { min-height: 2rem; }
    :root{
      --navy:#0E3254;
      --navy-2:#15497A;
      --blue:#23649A;
      --sky:#509FC8;
      --bar:#B7D0E2;
      --bar-soft:#CFE0ED;
      --gold:#EAD4A2;
      --gold-deep:#D4B475;
      --gold-dark:#8C6D1F;
      --cream:#F5EFE0;
      --page:#E8EAEC;
      --panel:#F5F6F5;
      --card:#FFFFFF;
      --ink:#14232F;
      --muted:#7A8794;
      --line:#E7EBEE;
      --green:#2E9E5B;
      --green-bg:#E4F4EA;
      --red:#DC2626;
      --red-bg:#FDECEC;
      --radius:22px;
      --shadow:0 1px 2px rgba(14,50,84,.04),0 10px 30px rgba(14,50,84,.05);
      color-scheme:light;
    }
    *{box-sizing:border-box}
    html,body{margin:0;padding:0}
    body{
      font-family:'Inter',system-ui,-apple-system,sans-serif;
      background:var(--page);color:var(--ink);
      min-height:100vh;padding:22px 18px 40px;
    }
    a{color:inherit;text-decoration:none}
    button{font-family:inherit;background:none;border:none;padding:0;cursor:pointer;color:inherit}

    /* ---------- Outer card shell ---------- */
    .frame{
      max-width:1440px;margin:0 auto;background:var(--panel);
      border-radius:32px;box-shadow:0 24px 60px rgba(14,50,84,.08);
      padding:18px 20px 24px;border:1px solid rgba(255,255,255,.7);
    }

    /* ---------- Top bar ---------- */
    .topbar{
      display:flex;align-items:center;gap:18px;background:var(--card);
      border-radius:999px;padding:9px 12px 9px 18px;box-shadow:var(--shadow);
    }
    .logo{display:flex;align-items:center;gap:9px;text-decoration:none;color:var(--navy);flex:none}
    .logo-ticket{height:40px;width:auto;display:block}
    .logo-word{font-family:'Plus Jakarta Sans',sans-serif;font-weight:800;font-size:19px;letter-spacing:-.3px}
    .logo-word em{font-style:normal;color:var(--gold-deep)}
    .topnav{display:flex;align-items:center;gap:4px;margin-left:14px}
    .topnav a, .topnav button{
      padding:7px 14px;border-radius:999px;font-size:13.5px;font-weight:600;color:#5B6B78;
      transition:background .15s,color .15s;text-decoration:none;border:none;background:none;min-height:36px;
      display:inline-flex;align-items:center;
    }
    .topnav a:hover, .topnav button:hover{color:var(--navy);background:#F0F3F5}
    .topnav a.active{background:var(--cream);color:var(--navy);font-weight:700}
    .top-actions{margin-left:auto;display:flex;align-items:center;gap:6px;position:relative}
    .icon-btn{
      width:40px;height:40px;border-radius:50%;display:grid;place-items:center;color:var(--navy);
      position:relative;transition:background .15s;flex:none;
    }
    .icon-btn:hover{background:#EEF3F7}
    .icon-btn .dot{position:absolute;top:9px;right:10px;width:8px;height:8px;border-radius:50%;background:var(--gold-deep);border:2px solid #fff}
    .search-wrap{display:flex;align-items:center}
    .search-input{
      width:0;opacity:0;border:none;outline:none;background:#F1F3F4;border-radius:999px;
      font-size:13.5px;font-family:inherit;color:var(--ink);
      transition:width .25s ease,opacity .2s,padding .25s;
      padding:0;min-height:40px;
    }
    .search-wrap.open .search-input{width:170px;opacity:1;padding:0 14px;margin-right:2px}
    .avatar-btn{display:flex;align-items:center;gap:2px;border-radius:999px;padding:2px 4px 2px 2px}
    .avatar-btn:hover{background:#EEF3F7}
    .avatar{
      width:38px;height:38px;border-radius:50%;flex:none;
      background:linear-gradient(135deg,var(--navy-2),var(--navy));
      color:var(--gold);display:grid;place-items:center;
      font-family:'Plus Jakarta Sans',sans-serif;font-weight:800;font-size:13px;
      border:2px solid var(--gold);
    }
    .pop{
      position:absolute;top:calc(100% + 10px);right:12px;width:300px;max-width:calc(100vw - 40px);
      background:var(--card);border-radius:18px;box-shadow:0 18px 44px rgba(14,50,84,.16);
      border:1px solid var(--line);padding:10px;display:none;z-index:50;
    }
    .pop.open{display:block}
    .pop h3{margin:6px 8px 8px;font-size:13px;color:var(--navy)}
    .notif{display:flex;gap:10px;padding:9px 8px;border-radius:12px}
    .notif:hover{background:#F4F7F9}
    .notif .n-ico{width:34px;height:34px;border-radius:10px;background:var(--cream);display:grid;place-items:center;flex:none;color:var(--gold-deep)}
    .notif p{margin:0;font-size:12.5px;line-height:1.45;color:var(--ink)}
    .notif span{display:block;color:var(--muted);font-size:11.5px;margin-top:1px}

    /* ---------- Greeting row ---------- */
    .greet{display:flex;align-items:center;gap:14px;flex-wrap:wrap;margin:26px 4px 20px}
    .greet h1{font-size:clamp(24px,3.4vw,34px);font-weight:600;margin:0;color:#3A4750;letter-spacing:-.3px}
    .greet h1 strong{font-weight:800;color:var(--ink)}
    .greet-actions{margin-left:auto;display:flex;gap:10px;align-items:center;flex-wrap:wrap}
    .range-wrap{position:relative}
    .range-btn{
      display:flex;align-items:center;gap:9px;background:var(--card);border-radius:999px;
      padding:10px 16px;font-size:13px;font-weight:600;color:var(--ink);box-shadow:var(--shadow);min-height:42px;
    }
    .range-btn svg{color:var(--navy)}
    .range-menu{
      position:absolute;top:calc(100% + 8px);right:0;background:var(--card);border:1px solid var(--line);
      border-radius:16px;box-shadow:0 18px 44px rgba(14,50,84,.16);padding:6px;display:none;z-index:40;min-width:230px;
    }
    .range-menu.open{display:block}
    .range-menu button{display:flex;width:100%;text-align:left;padding:9px 12px;border-radius:10px;font-size:13px;font-weight:500;min-height:38px;align-items:center}
    .range-menu button:hover{background:#F4F7F9}
    .range-menu button.sel{background:var(--cream);color:var(--navy);font-weight:700}
    .btn-primary{
      display:flex;align-items:center;gap:8px;background:var(--navy);color:#fff;
      border-radius:999px;padding:10px 20px;font-size:13.5px;font-weight:700;min-height:42px;
      box-shadow:0 10px 22px rgba(14,50,84,.25);transition:background .15s,transform .1s;
      font-family:'Plus Jakarta Sans',sans-serif;text-decoration:none;
    }
    .btn-primary:hover{background:var(--navy-2);color:#fff}
    .btn-primary:active{transform:scale(.98)}

    /* ---------- Layout & rail ---------- */
    .layout{display:flex;gap:18px;align-items:flex-start}
    .rail{
      flex:none;width:62px;background:var(--card);border-radius:999px;box-shadow:var(--shadow);
      display:flex;flex-direction:column;align-items:center;padding:12px 0;gap:5px;
      position:sticky;top:16px;max-height:calc(100vh - 32px);overflow-y:auto;scrollbar-width:none;z-index:20;
    }
    .rail::-webkit-scrollbar{display:none}
    .rail a, .rail button{
      width:44px;height:44px;border-radius:50%;display:grid;place-items:center;color:#8A97A3;transition:all .15s;position:relative;flex:none;text-decoration:none;
    }
    .rail a:hover, .rail button:hover{color:var(--navy);background:#EEF3F7}
    .rail a.active, .rail button.active{background:var(--navy);color:var(--gold);box-shadow:0 8px 18px rgba(14,50,84,.28)}
    #railTip{
      position:fixed;z-index:200;background:var(--navy);color:#fff;font-size:12px;font-weight:600;
      padding:6px 11px;border-radius:8px;white-space:nowrap;pointer-events:none;
      opacity:0;transform:translateY(-50%) translateX(-4px);transition:opacity .15s,transform .15s;
      box-shadow:0 8px 20px rgba(14,50,84,.25);
    }
    #railTip.show{opacity:1;transform:translateY(-50%) translateX(0)}
    .rail-badge{
      position:absolute;top:1px;right:1px;min-width:17px;height:17px;border-radius:99px;
      background:var(--gold-deep);color:#fff;font-size:10px;font-weight:800;line-height:1;
      display:grid;place-items:center;padding:0 4px;border:2px solid #fff;font-family:'Plus Jakarta Sans',sans-serif;
    }
    .rail button.active .rail-badge, .rail a.active .rail-badge{border-color:var(--navy)}
    .rail .spacer{flex:1;min-height:14px}
    .views{flex:1;min-width:0}
    .view{display:block;animation:viewIn .25s ease}
    @keyframes viewIn{from{opacity:0;transform:translateY(5px)}to{opacity:1;transform:none}}
    .card{background:var(--card);border-radius:var(--radius);box-shadow:var(--shadow);padding:20px}

    /* ---------- View scaffolding ---------- */
    .page-head{display:flex;align-items:flex-start;gap:14px;flex-wrap:wrap;margin:2px 2px 18px}
    .page-head h1{font-size:24px;margin:0;color:var(--ink);letter-spacing:-.2px}
    .page-head p{margin:4px 0 0;font-size:13px;color:var(--muted)}
    .page-head .spacer{flex:1}

    /* ---------- KPI Summary Cards ---------- */
    .kpi-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(210px,1fr));gap:14px;margin-bottom:18px}
    .kpi-card{background:var(--card);border-radius:var(--radius);box-shadow:var(--shadow);padding:18px 20px;display:flex;align-items:center;gap:14px}
    .kpi-icon{width:46px;height:46px;border-radius:14px;display:grid;place-items:center;flex:none}
    .kpi-card h3{margin:0;font-size:12px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.4px}
    .kpi-card p{margin:4px 0 0;font-size:22px;font-weight:800;color:var(--ink);font-family:'Plus Jakarta Sans',sans-serif}

    /* ---------- Note Demo ---------- */
    .note-demo{
      background:var(--cream);border:1px solid #E5D6B6;color:#6B5319;border-radius:14px;
      padding:11px 16px;font-size:13px;line-height:1.5;margin-bottom:18px;display:flex;align-items:center;gap:10px;
    }
    .note-demo svg{flex:none;color:var(--gold-dark)}

    /* ---------- Tables in views ---------- */
    .table-tools{display:flex;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:14px}
    .table-tools h2{font-size:15px;margin:0}
    .chips{display:flex;gap:7px;flex-wrap:wrap}
    .chip{
      border:1.5px solid var(--line);background:var(--card);color:#5B6B78;border-radius:999px;
      padding:6px 14px;font-size:12.5px;font-weight:600;min-height:34px;transition:all .15s;cursor:pointer;
    }
    .chip:hover{color:var(--navy);border-color:var(--gold-deep)}
    .chip.on{background:var(--navy);border-color:var(--navy);color:#fff}
    .mini-search{
      margin-left:auto;display:flex;align-items:center;gap:7px;background:#F1F3F4;border-radius:999px;padding:0 13px;min-height:38px;color:#8A97A3;
    }
    .mini-search input{border:none;outline:none;background:transparent;font-family:inherit;font-size:13px;color:var(--ink);width:170px;min-height:36px}
    .tbl-scroll{overflow-x:auto}
    .tbl{min-width:750px;width:100%;border-collapse:collapse}
    thead th{
      text-align:left;font-size:11.5px;font-weight:600;color:var(--muted);letter-spacing:.4px;
      padding:4px 10px 10px;border-bottom:1px solid var(--line);white-space:nowrap;
    }
    thead th:last-child,tbody td:last-child{text-align:right}
    tbody td{padding:13px 10px;border-bottom:1px solid #F0F2F4;font-size:13.5px;vertical-align:middle;white-space:nowrap}
    tbody tr:last-child td{border-bottom:none}
    .ev-cell{display:flex;align-items:center;gap:11px;white-space:normal}
    .ev-thumb{
      width:42px;height:42px;border-radius:12px;flex:none;display:grid;place-items:center;color:#fff;
      font-family:'Plus Jakarta Sans',sans-serif;font-weight:800;font-size:13px;
    }
    .ev-name{font-weight:600;color:var(--ink);font-size:13.5px}
    .ev-sub{font-size:11.5px;color:var(--muted);font-weight:500;margin-top:1px}
    .badge{
      display:inline-flex;align-items:center;gap:6px;font-size:11.5px;font-weight:700;
      border-radius:999px;padding:4px 11px;white-space:nowrap;
    }
    .badge::before{content:"";width:7px;height:7px;border-radius:50%;background:currentColor;flex:none}
    .b-live{background:var(--green-bg);color:var(--green)}
    .b-wait{background:var(--cream);color:var(--gold-dark)}
    .b-refund{background:var(--red-bg);color:var(--red)}
    .mini-btn{
      border:1.5px solid var(--line);background:var(--card);color:#5B6B78;border-radius:9px;
      padding:5px 12px;font-size:12.5px;font-weight:600;min-height:34px;transition:all .15s;white-space:nowrap;
      cursor:pointer;display:inline-flex;align-items:center;gap:6px;
    }
    .mini-btn:hover{color:var(--navy);border-color:var(--gold-deep);background:#FBF8F0}
    .progress{height:7px;border-radius:99px;background:#EDF1F3;margin-top:6px;overflow:hidden;width:150px}
    .progress i{display:block;height:100%;border-radius:99px;background:var(--gold-deep)}
    .stock-form{display:flex;gap:6px;align-items:center;justify-content:flex-end}
    .stock-inp{
      width:96px;min-height:34px;padding:4px 10px;border:1.5px solid var(--line);border-radius:8px;
      font-size:13.5px;font-weight:600;color:var(--ink);font-family:inherit;background:#FBFCFD;outline:none;
    }
    .stock-inp:focus{border-color:var(--blue)}
    .empty-row td{text-align:center;color:var(--muted);padding:26px 10px;white-space:normal}

    /* ---------- Toast ---------- */
    .toast{
      position:fixed;left:50%;bottom:26px;transform:translate(-50%,80px);z-index:120;
      background:var(--navy);color:#fff;font-size:13.5px;font-weight:600;
      padding:13px 22px;border-radius:999px;box-shadow:0 16px 36px rgba(14,50,84,.35);
      transition:transform .35s cubic-bezier(.22,.8,.3,1);max-width:calc(100vw - 32px);text-align:center;
    }
    .toast.show{transform:translate(-50%,0)}
    .toast em{font-style:normal;color:var(--gold)}

    /* ---------- Responsive ---------- */
    @media (max-width:820px){
      .topnav{display:none}
      .layout{flex-direction:column}
      .rail{
        flex-direction:row;flex-wrap:wrap;width:100%;border-radius:22px;padding:10px 12px;
        position:static;max-height:none;overflow:visible;justify-content:flex-start;gap:6px;
      }
      .rail .spacer{display:none}
      .rail a, .rail button{width:42px;height:42px}
      #railTip{display:none}
    }
    @media (max-width:640px){
      body{padding:12px 8px 20px}
      .frame{padding:12px 12px 16px;border-radius:22px}
      .topbar{padding:8px 8px 8px 14px}
      .logo-ticket{height:34px}
      .logo-word{font-size:17px}
      .greet{margin:18px 2px 14px}
      .greet-actions{margin-left:0;width:100%}
      .range-wrap{flex:1}
      .range-btn{width:100%;justify-content:center}
      .btn-primary{flex:1;justify-content:center}
      .search-wrap.open .search-input{width:110px}
      .mini-search{margin-left:0;width:100%}
      .mini-search input{width:100%}
      .tbl{min-width:0}
      .tbl-scroll{overflow-x:visible}
      thead{display:none}
      table,tbody,tr,td{display:block;width:100%}
      tbody tr{padding:12px 2px;border-bottom:1px solid var(--line)}
      tbody tr:last-child{border-bottom:none}
      tbody td{border:none;padding:3px 2px;white-space:normal}
      tbody td::before{
        content:attr(data-label);display:block;font-size:10.5px;font-weight:600;color:var(--muted);
        letter-spacing:.5px;text-transform:uppercase;margin-bottom:1px;
      }
      tbody td:last-child{text-align:left}
      .ev-cell{padding-bottom:4px}
      .ev-cell::before{display:none}
      .empty-row td::before{display:none}
      td.no-label::before{display:none}
      .stock-form{justify-content:flex-start;margin-top:6px}
    }
    @media (prefers-reduced-motion:reduce){
      .toast{transition:none}
      .view{animation:none}
    }
  </style>
</head>
<body>
  <div class="frame">

    <?php require __DIR__ . '/sidebar.php'; ?>

    <!-- ========== VIEW: STOK TIKET ========== -->
    <section class="view active" id="view-stok" aria-label="Stok Tiket">
      <div class="page-head">
        <div>
          <h1>Stok Tiket</h1>
          <p>Pantau kuota tiket per event dan ubah kuota secara langsung</p>
        </div>
      </div>

      <!-- KPI Summary Cards -->
      <div class="kpi-grid">
        <div class="kpi-card">
          <div class="kpi-icon" style="background:#EBF3FA;color:var(--navy)">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 8l8-4 8 4v8l-8 4-8-4z"/><path d="M4 8l8 4 8-4M12 12v8"/></svg>
          </div>
          <div>
            <h3>Total Kuota</h3>
            <p><?= number_format($kpi['totalQuota'] ?? 0, 0, ',', '.') ?></p>
          </div>
        </div>
        <div class="kpi-card">
          <div class="kpi-icon" style="background:var(--green-bg);color:var(--green)">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 5h2l2.4 11.2a1.5 1.5 0 0 0 1.5 1.2h7.9a1.5 1.5 0 0 0 1.5-1.2L20.5 8H7"/><circle cx="10" cy="21" r="1"/><circle cx="18" cy="21" r="1"/></svg>
          </div>
          <div>
            <h3>Tiket Terjual</h3>
            <p><?= number_format($kpi['totalSold'] ?? 0, 0, ',', '.') ?></p>
          </div>
        </div>
        <div class="kpi-card">
          <div class="kpi-icon" style="background:var(--cream);color:var(--gold-dark)">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="16" rx="2.5"/><path d="M3 10h18"/></svg>
          </div>
          <div>
            <h3>Sisa Kuota</h3>
            <p><?= number_format($kpi['totalRemaining'] ?? 0, 0, ',', '.') ?></p>
          </div>
        </div>
        <div class="kpi-card">
          <div class="kpi-icon" style="background:var(--red-bg);color:var(--red)">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 9v4M12 17h.01"/><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/></svg>
          </div>
          <div>
            <h3>Menipis / Habis</h3>
            <p><?= number_format($kpi['lowStockCount'] ?? 0, 0, ',', '.') ?></p>
          </div>
        </div>
      </div>

      <div class="note-demo">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
        <span>Ubah angka total kuota lalu klik <b>Simpan</b>. Sistem mencegah kuota diset lebih kecil dari tiket yang sudah terjual. Label <b>Tinggal Sedikit</b> muncul saat sisa tiket ≤ 20%, dan <b>Habis</b> bila sisa tiket 0.</span>
      </div>

      <div class="card">
        <div class="table-tools">
          <div class="chips" id="stockChips">
            <button class="chip on" type="button" data-filter="semua">Semua</button>
            <button class="chip" type="button" data-filter="aman">Aman</button>
            <button class="chip" type="button" data-filter="sedikit">Tinggal Sedikit</button>
            <button class="chip" type="button" data-filter="habis">Habis</button>
          </div>
          <div class="mini-search">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/></svg>
            <input type="search" id="stockSearch" placeholder="Cari nama event…" aria-label="Cari event" />
          </div>
        </div>

        <div class="tbl-scroll">
          <table class="tbl">
            <thead>
              <tr>
                <th scope="col">Event</th>
                <th scope="col">Kategori</th>
                <th scope="col">Total Kuota</th>
                <th scope="col">Terjual</th>
                <th scope="col">Sisa Kuota</th>
                <th scope="col">Status</th>
                <th scope="col">Ubah Kuota</th>
              </tr>
            </thead>
            <tbody id="stockTableBody">
              <?php if (empty($events)): ?>
                <tr class="empty-row"><td colspan="7">Belum ada data stok event.</td></tr>
              <?php else: ?>
                <?php foreach ($events as $e):
                  $sold = (int)($e['sold'] ?? 0);
                  $quota = (int)($e['quota'] ?? 0);
                  $rem = (int)($e['remaining'] ?? max(0, $quota - $sold));
                  $pct = $quota > 0 ? round(($sold / $quota) * 100) : 0;
                  $status = $e['stock_status'] ?? 'aman';
                  $badgeCls = $e['stock_badge_cls'] ?? 'b-live';
                  $badgeLabel = $e['stock_label'] ?? 'Aman';
                  $initials = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $e['title']), 0, 2));
                  if (strlen($initials) < 2) $initials = 'EV';
                  $color = !empty($e['category_color']) ? $e['category_color'] : '#23649A';
                ?>
                  <tr class="stock-row" data-name="<?= strtolower(e($e['title'])) ?>" data-status="<?= e($status) ?>">
                    <td class="ev-cell">
                      <span class="ev-thumb" style="background:<?= e($color) ?>"><?= e($initials) ?></span>
                      <span>
                        <span class="ev-name">
                          <a href="<?= base_url('events/show/' . (int)$e['id']) ?>" style="color:var(--ink);font-weight:700;">
                            <?= e($e['title']) ?>
                          </a>
                        </span>
                        <span class="ev-sub" style="display:block"><?= e($e['venue'] ?? '') ?><?= !empty($e['city_name']) ? ', ' . e($e['city_name']) : '' ?></span>
                      </span>
                    </td>
                    <td data-label="Kategori"><?= e($e['category_name'] ?? 'Umum') ?></td>
                    <td data-label="Total Kuota"><b><?= number_format($quota, 0, ',', '.') ?></b></td>
                    <td data-label="Terjual"><?= number_format($sold, 0, ',', '.') ?></td>
                    <td data-label="Sisa Kuota">
                      <b><?= number_format($rem, 0, ',', '.') ?></b>
                      <div class="progress" title="<?= $pct ?>% terjual"><i style="width:<?= min(100, $pct) ?>%"></i></div>
                    </td>
                    <td data-label="Status" class="no-label">
                      <span class="badge <?= e($badgeCls) ?>"><?= e($badgeLabel) ?></span>
                    </td>
                    <td data-label="Ubah Kuota" class="no-label">
                      <form action="<?= base_url('dashboard/events/stock/' . (int)$e['id']) ?>" method="POST" class="stock-form">
                        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>" />
                        <input type="number" name="quota" min="<?= (int)$sold ?>" value="<?= (int)$quota ?>" class="stock-inp" aria-label="Ubah kuota tiket <?= e($e['title']) ?>" required />
                        <button type="submit" class="mini-btn">Simpan</button>
                      </form>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
              <tr id="emptyStockRow" class="empty-row" hidden>
                <td colspan="7">Tidak ada event yang cocok dengan filter atau pencarian stok.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </section>

    <?php $shellFooter = true; require __DIR__ . '/sidebar.php'; ?>

  <!-- Script Khusus Stok: Filter Chips & Live Search -->
  <script>
    (function(){
      var currentFilter = "semua";
      var searchInput = document.getElementById("stockSearch");
      var chips = document.querySelectorAll("#stockChips .chip");
      var rows = document.querySelectorAll("#stockTableBody .stock-row");
      var emptyRow = document.getElementById("emptyStockRow");

      function filterRows() {
        var q = (searchInput ? searchInput.value : "").trim().toLowerCase();
        var visible = 0;
        rows.forEach(function(r) {
          var name = r.getAttribute("data-name") || "";
          var st = r.getAttribute("data-status") || "aman";
          var matchQ = !q || name.indexOf(q) > -1;
          var matchSt = (currentFilter === "semua") || (st === currentFilter);
          var show = matchQ && matchSt;
          r.style.display = show ? "" : "none";
          if (show) visible++;
        });
        if (emptyRow) {
          emptyRow.hidden = visible !== 0;
          emptyRow.style.display = visible !== 0 ? "none" : "";
        }
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
    })();
  </script>
</body>
</html>