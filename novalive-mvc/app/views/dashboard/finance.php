<?php
/**
 * Halaman Keuangan — Gaya Quixotic
 * NOVA LIVE MVC — Tahap 3
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
  <title><?= e($pageTitle ?? 'NOVA LIVE — Keuangan') ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="<?= base_url('css/dashboard.css') ?>" />
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
      --green-bg:#E6F5EC;
      --red:#D04444;
      --red-bg:#FDF0F0;
      --shadow:0 8px 24px rgba(14,50,84,.07);
    }
    *{box-sizing:border-box}
    html,body{margin:0;padding:0}
    body{
      font-family:'Inter',system-ui,sans-serif;
      background:var(--page);
      color:var(--ink);
      -webkit-font-smoothing:antialiased;
      padding:22px 18px 30px;
    }
    h1,h2,h3,.pj{font-family:'Plus Jakarta Sans','Inter',sans-serif}
    button{font-family:inherit;cursor:pointer;border:none;background:none;color:inherit}
    a{text-decoration:none;color:inherit}
    .frame{
      width:100%;max-width:none;margin:0;
      background:var(--panel);
      border-radius:30px;
      padding:20px 24px 26px;
      box-shadow:0 24px 60px rgba(14,50,84,.10);
      box-sizing:border-box;
    }
    .topbar{
      display:grid;grid-template-columns:1fr auto 1fr;align-items:center;gap:18px;
      background:var(--card);
      border-radius:999px;
      padding:10px 12px 10px 20px;
      box-shadow:var(--shadow);
      position:relative;z-index:30;
    }
    .logo{display:flex;align-items:center;gap:9px;text-decoration:none;color:var(--navy);flex:none;justify-self:start}
    .logo svg{display:block}
    .logo-ticket{height:40px;width:auto;overflow:visible;cursor:pointer;flex:none}
    .logo-word{font-family:'Plus Jakarta Sans',sans-serif;font-weight:800;font-size:19px;letter-spacing:.2px;color:var(--navy);white-space:nowrap}
    .logo-word em{font-style:normal;color:var(--gold-deep)}
    .topnav{display:flex;gap:4px;margin:0;justify-self:center;background:#F1F3F4;border-radius:999px;padding:4px}
    .topnav a, .topnav button{
      display:inline-flex;align-items:center;padding:8px 18px;border-radius:999px;font-size:13.5px;font-weight:600;color:#5B6B78;min-height:36px;
      transition:background .15s,color .15s;text-decoration:none;
    }
    .topnav a:hover, .topnav button:hover{color:var(--navy)}
    .topnav a.active, .topnav button.active{background:var(--card);color:var(--navy);box-shadow:0 2px 8px rgba(0,0,0,.08)}
    .top-actions{display:flex;align-items:center;gap:6px;flex:none;justify-self:end;margin-left:0}
    .icon-btn{
      width:40px;height:40px;border-radius:50%;display:grid;place-items:center;color:#4A5965;
      background:#F1F3F4;transition:background .15s,color .15s;position:relative;
    }
    .icon-btn:hover{background:#E5E9EC;color:var(--navy)}
    .icon-btn .dot{
      position:absolute;top:8px;right:9px;width:8px;height:8px;border-radius:50%;background:#D34545;
      box-shadow:0 0 0 2px var(--card);
    }
    .avatar-btn{display:flex;align-items:center;gap:6px;padding:3px 8px 3px 3px;border-radius:999px;background:#F1F3F4;transition:background .15s}
    .avatar-btn:hover{background:#E5E9EC}
    .avatar{
      width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,var(--navy),var(--blue));
      color:#fff;font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;font-size:13px;display:grid;place-items:center;
    }
    .search-wrap{position:relative;display:flex;align-items:center}
    .search-input{
      width:0;opacity:0;pointer-events:none;transition:width .22s ease,opacity .18s;
      border:1.5px solid var(--line);border-radius:999px;padding:8px 14px;font-size:13px;background:var(--card);color:var(--ink);outline:none;
    }
    .search-wrap.open .search-input{width:180px;opacity:1;pointer-events:auto;margin-right:6px}
    .pop{
      position:absolute;top:calc(100% + 10px);right:12px;background:var(--card);border-radius:18px;
      box-shadow:0 18px 45px rgba(14,50,84,.16);width:320px;padding:0;z-index:90;display:none;overflow:hidden;
    }
    .pop.open{display:block;animation:popIn .18s ease-out}
    @keyframes popIn{from{opacity:0;transform:translateY(-6px)}to{opacity:1;transform:translateY(0)}}
    .greet{
      display:flex;align-items:center;justify-content:space-between;margin:22px 4px 18px;flex-wrap:wrap;gap:12px;
    }
    .greet h1{margin:0;font-size:24px;color:var(--navy);font-weight:700}
    .greet h1 strong{font-weight:800}
    .greet-actions{display:flex;align-items:center;gap:10px}
    .range-wrap{position:relative}
    .range-btn{
      display:inline-flex;align-items:center;gap:9px;background:var(--card);border-radius:999px;
      padding:9px 16px;box-shadow:var(--shadow);font-size:13px;font-weight:600;color:#394854;
    }
    .range-menu{
      position:absolute;top:calc(100% + 6px);right:0;background:var(--card);border-radius:14px;
      box-shadow:0 14px 35px rgba(14,50,84,.14);padding:6px;width:240px;z-index:80;display:none;
    }
    .range-menu.open{display:block}
    .range-menu button{
      display:block;width:100%;text-align:left;padding:8px 12px;border-radius:9px;font-size:12.5px;
      color:#3A4A57;font-weight:500;
    }
    .range-menu button:hover, .range-menu button.sel{background:#F1F4F7;color:var(--navy);font-weight:700}
    .btn-primary{
      display:inline-flex;align-items:center;gap:8px;background:var(--navy);color:#fff;
      border-radius:999px;padding:9px 18px;font-weight:700;font-size:13px;box-shadow:0 4px 14px rgba(14,50,84,.22);
      transition:transform .12s,background .15s;
    }
    .btn-primary:hover{background:var(--navy-2);transform:translateY(-1px)}
    .layout{display:flex;gap:22px;align-items:flex-start}
    .rail{
      flex:none;width:58px;background:var(--card);border-radius:999px;padding:14px 8px;
      box-shadow:var(--shadow);display:flex;flex-direction:column;align-items:center;gap:10px;
      position:sticky;top:20px;min-height:480px;
    }
    .rail a, .rail button{
      width:42px;height:42px;border-radius:50%;display:grid;place-items:center;color:#6A7885;
      position:relative;transition:background .15s,color .15s,transform .1s;text-decoration:none;
    }
    .rail a:hover, .rail button:hover{background:#F1F4F7;color:var(--navy)}
    .rail a.active, .rail button.active{
      background:var(--navy);color:#fff;box-shadow:0 4px 14px rgba(14,50,84,.28);
    }
    .rail .rail-badge{
      position:absolute;top:5px;right:5px;min-width:16px;height:16px;padding:0 4px;border-radius:999px;
      background:var(--gold-deep);color:#fff;font-size:10px;font-weight:800;display:grid;place-items:center;
      line-height:1;border:2px solid var(--card);
    }
    .rail .spacer{flex:1}
    .views{flex:1;min-width:0}
    .view{display:block}
    .page-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:18px;flex-wrap:wrap;gap:12px}
    .page-head h1{margin:0 0 3px;font-size:22px;color:var(--navy);font-weight:800}
    .page-head p{margin:0;font-size:13px;color:var(--muted)}
    .card{background:var(--card);border-radius:20px;padding:20px 22px;box-shadow:var(--shadow);position:relative;margin-bottom:20px}
    .card-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:16px}
    .card-head h2{margin:0;font-size:17px;font-weight:800;color:var(--navy)}
    .card-head p{margin:3px 0 0;font-size:12.5px;color:var(--muted)}

    /* Fin Grid Styling */
    .fin-grid{display:grid;grid-template-columns:1.35fr 1fr 1fr;gap:18px;margin-bottom:20px;align-items:stretch}
    .fin-grid > .card, .fin-grid > .fin-saldo{margin-bottom:0}
    .fin-saldo{
      border-radius:20px;color:#fff;position:relative;overflow:hidden;
      background:linear-gradient(125deg,var(--navy-2) 0%,var(--navy) 60%,#09243E 100%);padding:24px;
      box-shadow:0 12px 30px rgba(14,50,84,.22);display:flex;flex-direction:column;justify-content:space-between;
    }
    .fin-saldo::after{
      content:"";position:absolute;right:-40px;top:-40px;width:160px;height:160px;border-radius:50%;
      background:radial-gradient(circle,rgba(234,212,162,.32),rgba(234,212,162,0) 70%);pointer-events:none;
    }
    .fin-saldo .lbl{font-size:12px;letter-spacing:.8px;color:#B9CFE2;font-weight:700;position:relative;z-index:1}
    .fin-saldo .val{font-family:'Plus Jakarta Sans',sans-serif;font-size:32px;font-weight:800;margin:10px 0 4px;position:relative;z-index:1}
    .fin-saldo .sub{font-size:13px;color:#CFDFED;margin-bottom:18px;position:relative;z-index:1;line-height:1.4}
    .btn-gold{
      display:inline-flex;align-items:center;justify-content:center;gap:8px;background:var(--gold);color:var(--navy);
      font-weight:800;font-size:13px;border-radius:12px;padding:10px 20px;box-shadow:0 4px 14px rgba(234,212,162,.3);
      cursor:pointer;position:relative;z-index:1;border:none;transition:transform .12s,background .15s;
    }
    .btn-gold:hover{background:#dfc78f;transform:translateY(-1px)}
    .kpi-top{display:flex;align-items:center;gap:9px;font-size:13px;font-weight:700;color:var(--muted)}
    .kpi-ic{width:34px;height:34px;border-radius:10px;background:var(--cream);color:var(--gold-deep);display:grid;place-items:center;flex:none}
    .kpi-val{font-family:'Plus Jakarta Sans',sans-serif;font-size:26px;font-weight:800;color:var(--ink);margin:12px 0 4px}
    .kpi-delta{font-size:12.5px;color:var(--muted)}
    .kpi-delta.up{color:var(--green);font-weight:600}

    /* Sub-cards Row */
    .sub-fin-grid{display:grid;grid-template-columns:repeat(3, 1fr);gap:16px;margin-bottom:20px}
    .sub-fin-box{background:var(--card);border-radius:16px;padding:14px 18px;box-shadow:var(--shadow);border-left:4px solid var(--navy)}
    .sub-fin-box.fee{border-left-color:var(--gold-deep)}
    .sub-fin-box.cair{border-left-color:var(--green)}
    .sub-fin-box.proc{border-left-color:var(--blue)}
    .sub-fin-box span{display:block;font-size:12px;color:var(--muted);font-weight:600;margin-bottom:4px}
    .sub-fin-box b{font-size:18px;color:var(--navy);font-family:'Plus Jakarta Sans',sans-serif}

    /* Monthly Revenue Chart Bars */
    .fin-chart{display:flex;align-items:flex-end;gap:18px;height:160px;padding:10px 0 4px}
    .f-col{flex:1;display:flex;flex-direction:column;align-items:center;height:100%;justify-content:flex-end}
    .f-bar{
      width:100%;max-width:44px;border-radius:8px 8px 0 0;background:var(--bar);position:relative;
      min-height:8px;transition:height .3s,background .2s;cursor:pointer;
    }
    .f-bar:hover{background:var(--blue)}
    .f-bar.hot{background:linear-gradient(180deg,var(--navy) 0%,var(--navy-2) 100%)}
    .f-lbl{font-size:12px;font-weight:600;color:var(--muted);margin-top:8px;text-align:center}
    .f-bar .f-tip{
      position:absolute;bottom:calc(100% + 6px);left:50%;transform:translateX(-50%);
      background:var(--navy);color:#fff;font-size:11px;font-weight:700;padding:3px 7px;border-radius:6px;
      white-space:nowrap;opacity:0;pointer-events:none;transition:opacity .15s;
    }
    .f-bar:hover .f-tip{opacity:1}

    /* Tables */
    .table-tools{display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;flex-wrap:wrap;gap:10px}
    .mini-search{display:flex;align-items:center;gap:8px;background:#F6F8FA;border-radius:10px;padding:6px 12px;width:260px}
    .mini-search input{border:none;background:none;outline:none;font-size:13px;width:100%;color:var(--ink)}
    .tbl-scroll{overflow-x:auto}
    .tbl{width:100%;border-collapse:collapse;text-align:left;font-size:13.5px}
    .tbl th{padding:12px 14px;color:var(--muted);font-weight:700;font-size:12px;letter-spacing:.3px;border-bottom:1.5px solid var(--line)}
    .tbl td{padding:13px 14px;border-bottom:1px solid #F1F4F7;color:var(--ink);vertical-align:middle}
    .tbl tr:hover td{background:#F9FBFC}
    .badge{display:inline-flex;align-items:center;gap:5px;font-size:11.5px;font-weight:700;border-radius:999px;padding:4px 10px;white-space:nowrap}
    .b-live{background:var(--green-bg);color:var(--green)}
    .b-wait{background:var(--cream);color:var(--gold-dark)}
    .b-danger{background:var(--red-bg);color:var(--red)}
    .btn-outline{
      display:inline-flex;align-items:center;gap:6px;background:#fff;border:1.5px solid var(--line);
      border-radius:999px;padding:7px 15px;font-size:12.5px;font-weight:700;color:var(--navy);
      cursor:pointer;transition:background .15s,border-color .15s;
    }
    .btn-outline:hover{background:#F8FAFB;border-color:#CFD8DF}
    .btn-sm{padding:5px 12px;font-size:12px}

    /* Modals */
    .overlay{
      position:fixed;inset:0;background:rgba(14,50,84,.42);backdrop-filter:blur(3px);
      z-index:100;display:none;align-items:center;justify-content:center;padding:16px;
    }
    .overlay.open{display:flex}
    .modal{
      background:var(--card);border-radius:24px;width:100%;max-width:480px;padding:26px 28px;
      box-shadow:0 24px 60px rgba(14,50,84,.22);position:relative;animation:popIn .2s ease-out;
    }
    .modal-x{position:absolute;top:20px;right:22px;font-size:20px;color:var(--muted);cursor:pointer;line-height:1}
    .modal-x:hover{color:var(--navy)}
    .modal h2{margin:0 0 6px;font-size:20px;font-weight:800;color:var(--navy)}
    .modal p.desc{margin:0 0 18px;font-size:13px;color:var(--muted);line-height:1.5}
    .form-group{margin-bottom:14px}
    .form-group label{display:block;font-size:12.5px;font-weight:700;color:var(--navy);margin-bottom:6px}
    .form-control{
      width:100%;border:1.5px solid var(--line);border-radius:12px;padding:10px 14px;
      font-size:13.5px;font-family:inherit;color:var(--ink);background:#FBFCFD;outline:none;
    }
    .form-control:focus{border-color:var(--blue);background:#fff}
    .form-row{display:flex;gap:12px}
    .modal-actions{display:flex;gap:10px;margin-top:20px;justify-content:flex-end}
    .btn-navy{background:var(--navy);color:#fff;border-radius:12px;padding:10px 22px;font-weight:700;font-size:13.5px;border:none}
    .btn-navy:hover{background:var(--navy-2)}
    .btn-ghost{background:#F1F4F7;color:#4A5965;border-radius:12px;padding:10px 18px;font-weight:600;font-size:13.5px;border:none}
    .btn-ghost:hover{background:#E5E9EC;color:var(--navy)}

    @media (max-width:1120px){
      .fin-grid{grid-template-columns:1fr 1fr}
      .fin-saldo{grid-column:1/-1}
      .sub-fin-grid{grid-template-columns:1fr}
    }
    @media (max-width:820px){
      .fin-grid{grid-template-columns:1fr}
      .layout{flex-direction:column}
      .rail{flex-direction:row;width:100%;min-height:auto;border-radius:20px;padding:8px 12px;position:static}
      .rail .spacer{display:none}
    }
  </style>
</head>
<body>
  <div class="frame">

    <?php require __DIR__ . '/sidebar.php'; ?>

    <!-- ========== VIEW: KEUANGAN ========== -->
    <section class="view" id="view-keuangan" aria-label="Keuangan">
      <div class="page-head">
        <div>
          <h1>Keuangan &amp; Penarikan Dana</h1>
          <p>Saldo tersedia, rincian pendapatan event, dan riwayat penarikan rekening</p>
        </div>
        <div style="display:flex;gap:10px">
          <button class="btn-primary" type="button" id="openWithdrawBtn">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
            Tarik Dana
          </button>
          <a href="<?= base_url('dashboard/finance?export=csv') ?>" class="btn-outline">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v12M7 10l5 5 5-5M4 21h16"/></svg>
            Export CSV
          </a>
        </div>
      </div>

      <!-- Financial KPI Cards -->
      <div class="fin-grid">
        <!-- 1. Saldo Tersedia -->
        <div class="fin-saldo">
          <div>
            <div class="lbl">SALDO TERSEDIA</div>
            <div class="val"><?= rupiah($availableBalance) ?></div>
            <div class="sub">Siap ditarik ke rekening bank penyelenggara terdaftar.</div>
          </div>
          <div>
            <button class="btn-gold" type="button" id="openWithdrawBtn2">Tarik Dana Sekarang</button>
          </div>
        </div>

        <!-- 2. Pendapatan Kotor -->
        <div class="card">
          <div class="kpi-top">
            <span class="kpi-ic">
              <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
            </span>
            Pendapatan Kotor
          </div>
          <div class="kpi-val"><?= rupiah($grossRevenue) ?></div>
          <div class="kpi-delta up">Dari seluruh pesanan tiket terkirim &amp; selesai</div>
        </div>

        <!-- 3. Refund Disetujui -->
        <div class="card">
          <div class="kpi-top">
            <span class="kpi-ic">
              <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10h10a5 5 0 0 1 5 5v2M3 10l6-6M3 10l6 6"/></svg>
            </span>
            Refund Disetujui
          </div>
          <div class="kpi-val" style="color:var(--red)"><?= rupiah($approvedRefunds) ?></div>
          <div class="kpi-delta">Total dana tiket yang dikembalikan ke pembeli</div>
        </div>
      </div>

      <!-- 3 Sub-metrik Biaya & Pencairan -->
      <div class="sub-fin-grid">
        <div class="sub-fin-box fee">
          <span>BIAYA LAYANAN NOVA LIVE (5%)</span>
          <b><?= rupiah($serviceFee) ?></b>
        </div>
        <div class="sub-fin-box cair">
          <span>TOTAL SUDAH DICAIRKAN</span>
          <b><?= rupiah($totalSuccessful) ?></b>
        </div>
        <div class="sub-fin-box proc">
          <span>SEDANG DIPROSES / MENUNGGU</span>
          <b><?= rupiah($totalPending) ?></b>
        </div>
      </div>

      <!-- Grafik Pendapatan 6 Bulan Terakhir -->
      <div class="card">
        <div class="card-head">
          <div>
            <h2>Tren Pendapatan (6 Bulan Terakhir)</h2>
            <p>Akumulasi penjualan tiket per bulan dari pesanan berstatus sukses</p>
          </div>
        </div>
        <?php
          $maxRev = 1.0;
          foreach ($monthlyRevenue as $mr) {
              if ($mr['total'] > $maxRev) $maxRev = $mr['total'];
          }
        ?>
        <div class="fin-chart" id="finChart">
          <?php foreach ($monthlyRevenue as $i => $mr): 
            $pct = max(8, min(100, round(($mr['total'] / $maxRev) * 100)));
            $isHot = ($i === count($monthlyRevenue) - 1);
          ?>
            <div class="f-col">
              <div class="f-bar <?= $isHot ? 'hot' : '' ?>" style="height:<?= $pct ?>%">
                <span class="f-tip"><?= rupiah($mr['total']) ?> (<?= $mr['tickets_sold'] ?> tiket)</span>
              </div>
              <span class="f-lbl"><?= e($mr['label']) ?></span>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Tabel Pendapatan per Event -->
      <div class="card">
        <div class="table-tools">
          <div>
            <h2>Pendapatan per Event</h2>
            <p style="margin:3px 0 0;font-size:12.5px;color:var(--muted)">Rincian tiket terjual, omset kotor, potongan refund, dan omset bersih per event</p>
          </div>
          <div style="display:flex;gap:10px;align-items:center">
            <div class="mini-search">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/></svg>
              <input type="search" id="eventFinSearch" placeholder="Cari nama event…" />
            </div>
          </div>
        </div>
        <div class="tbl-scroll">
          <table class="tbl" id="eventFinTable">
            <thead>
              <tr>
                <th scope="col">Kode</th>
                <th scope="col">Nama Event</th>
                <th scope="col">Harga Tiket</th>
                <th scope="col">Terjual / Kuota</th>
                <th scope="col">Pendapatan Kotor</th>
                <th scope="col">Refund</th>
                <th scope="col">Pendapatan Bersih</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($revenuePerEvent)): ?>
                <tr><td colspan="7" style="text-align:center;padding:24px;color:var(--muted)">Belum ada data event.</td></tr>
              <?php else: ?>
                <?php foreach ($revenuePerEvent as $ev): ?>
                  <tr class="ev-fin-row" data-name="<?= strtolower(e($ev['title'] . ' ' . $ev['code'])) ?>">
                    <td><span style="font-family:monospace;font-weight:700"><?= e($ev['code']) ?></span></td>
                    <td><b><?= e($ev['title']) ?></b></td>
                    <td><?= rupiah($ev['price']) ?></td>
                    <td><b><?= number_format($ev['sold']) ?></b> / <?= number_format($ev['quota']) ?></td>
                    <td><b><?= rupiah($ev['gross_revenue']) ?></b></td>
                    <td><span style="color:<?= $ev['refund_amount'] > 0 ? 'var(--red)' : 'var(--muted)' ?>"><?= rupiah($ev['refund_amount']) ?></span></td>
                    <td><b style="color:var(--green)"><?= rupiah($ev['net_revenue']) ?></b></td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Tabel Riwayat Pencairan Dana -->
      <div class="card">
        <div class="card-head">
          <div>
            <h2>Riwayat Penarikan Dana</h2>
            <p>Daftar transfer dana ke rekening bank penyelenggara</p>
          </div>
        </div>
        <div class="tbl-scroll">
          <table class="tbl">
            <thead>
              <tr>
                <th scope="col">ID</th>
                <th scope="col">Tanggal Pengajuan</th>
                <th scope="col">Rekening Tujuan</th>
                <th scope="col">Jumlah</th>
                <th scope="col">Catatan</th>
                <th scope="col">Status</th>
                <?php if (($_SESSION['user']['role'] ?? '') === 'admin'): ?>
                  <th scope="col">Aksi</th>
                <?php endif; ?>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($withdrawals)): ?>
                <tr><td colspan="7" style="text-align:center;padding:24px;color:var(--muted)">Belum ada riwayat penarikan dana.</td></tr>
              <?php else: ?>
                <?php foreach ($withdrawals as $w): ?>
                  <tr>
                    <td>#W-<?= (int)$w['id'] ?></td>
                    <td><?= tanggal($w['created_at'], 'd M Y · H:i') ?> WIB</td>
                    <td>
                      <b><?= e($w['bank_name']) ?></b> <?= e($w['account_no']) ?><br>
                      <small style="color:var(--muted)">a.n. <?= e($w['account_name']) ?></small>
                    </td>
                    <td><b><?= rupiah($w['amount']) ?></b></td>
                    <td style="color:var(--muted);font-size:12.5px"><?= e($w['note'] ?: '—') ?></td>
                    <td>
                      <?php if ($w['status'] === 'Berhasil'): ?>
                        <span class="badge b-live">Berhasil</span>
                      <?php elseif ($w['status'] === 'Diproses'): ?>
                        <span class="badge b-wait">Diproses</span>
                      <?php else: ?>
                        <span class="badge b-danger">Ditolak</span>
                      <?php endif; ?>
                    </td>
                    <?php if (($_SESSION['user']['role'] ?? '') === 'admin'): ?>
                      <td>
                        <button type="button" class="btn-outline btn-sm js-edit-status" 
                                data-id="<?= (int)$w['id'] ?>" 
                                data-amount="<?= rupiah($w['amount']) ?>"
                                data-status="<?= e($w['status']) ?>"
                                data-note="<?= e($w['note'] ?? '') ?>">
                          Ubah Status
                        </button>
                      </td>
                    <?php endif; ?>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </section>

    <?php $shellFooter = true; require __DIR__ . '/sidebar.php'; ?>
  </div>

  <!-- Modal Tarik Dana -->
  <div class="overlay" id="withdrawModal" role="dialog" aria-modal="true" aria-labelledby="wModalTitle">
    <div class="modal">
      <button type="button" class="modal-x" id="closeWithdrawBtn" aria-label="Tutup modal">✕</button>
      <h2 id="wModalTitle">Tarik Dana Penyelenggara</h2>
      <p class="desc">Dana akan ditransfer ke rekening bank terdaftar dalam 1–2 hari kerja.</p>

      <form action="<?= base_url('dashboard/finance/withdraw') ?>" method="POST" id="withdrawForm">
        <?= csrf_field() ?>
        
        <div class="form-group">
          <label for="wAmount">Nominal Penarikan (Rp)</label>
          <input type="number" class="form-control" id="wAmount" name="amount" min="10000" max="<?= (int)$availableBalance ?>" step="1000" placeholder="cth. 1000000" required />
          <small style="color:var(--muted);font-size:12px;margin-top:4px;display:block">
            Maksimal saldo yang dapat ditarik: <b><?= rupiah($availableBalance) ?></b>
          </small>
        </div>

        <div class="form-group">
          <label for="wBank">Bank Tujuan</label>
          <select class="form-control" id="wBank" name="bank_name" required>
            <option value="BCA">Bank Central Asia (BCA)</option>
            <option value="Mandiri">Bank Mandiri</option>
            <option value="BNI">Bank Negara Indonesia (BNI)</option>
            <option value="BRI">Bank Rakyat Indonesia (BRI)</option>
            <option value="BSI">Bank Syariah Indonesia (BSI)</option>
            <option value="CIMB Niaga">CIMB Niaga</option>
            <option value="Permata">Bank Permata</option>
          </select>
        </div>

        <div class="form-row">
          <div class="form-group" style="flex:1">
            <label for="wAccountNo">Nomor Rekening</label>
            <input type="text" class="form-control" id="wAccountNo" name="account_no" placeholder="cth. 8820491823" required />
          </div>
          <div class="form-group" style="flex:1">
            <label for="wAccountName">Nama Pemilik Rekening</label>
            <input type="text" class="form-control" id="wAccountName" name="account_name" value="<?= e($currentUser['full_name'] ?? '') ?>" placeholder="Sesuai buku tabungan" required />
          </div>
        </div>

        <div class="form-group">
          <label for="wNote">Catatan (Opsional)</label>
          <input type="text" class="form-control" id="wNote" name="note" placeholder="cth. Pencairan penjualan tiket November" />
        </div>

        <div class="modal-actions">
          <button type="button" class="btn-ghost" id="cancelWithdrawBtn">Batal</button>
          <button type="submit" class="btn-navy">Ajukan Penarikan</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal Ubah Status Penarikan (Admin Only) -->
  <?php if (($_SESSION['user']['role'] ?? '') === 'admin'): ?>
    <div class="overlay" id="statusModal" role="dialog" aria-modal="true" aria-labelledby="statusModalTitle">
      <div class="modal">
        <button type="button" class="modal-x" id="closeStatusModalBtn" aria-label="Tutup modal">✕</button>
        <h2 id="statusModalTitle">Ubah Status Penarikan Dana</h2>
        <p class="desc" id="statusModalDesc">Perbarui status pencairan dana penyelenggara.</p>

        <form action="" method="POST" id="statusForm">
          <?= csrf_field() ?>
          
          <div class="form-group">
            <label for="wStatusSelect">Status Pencairan</label>
            <select class="form-control" id="wStatusSelect" name="status" required>
              <option value="Diproses">Diproses</option>
              <option value="Berhasil">Berhasil (Sudah Ditransfer)</option>
              <option value="Ditolak">Ditolak</option>
            </select>
          </div>

          <div class="form-group">
            <label for="wStatusNote">Catatan Admin / Alasan (Opsional)</label>
            <textarea class="form-control" id="wStatusNote" name="note" rows="3" placeholder="Tuliskan nomor referensi mutasi atau alasan penolakan…"></textarea>
          </div>

          <div class="modal-actions">
            <button type="button" class="btn-ghost" id="cancelStatusBtn">Batal</button>
            <button type="submit" class="btn-navy">Simpan Status</button>
          </div>
        </form>
      </div>
    </div>
  <?php endif; ?>

  <script>
    (function(){
      // Modal Tarik Dana
      var wModal = document.getElementById('withdrawModal');
      var openWBtn = document.getElementById('openWithdrawBtn');
      var openWBtn2 = document.getElementById('openWithdrawBtn2');
      var closeWBtn = document.getElementById('closeWithdrawBtn');
      var cancelWBtn = document.getElementById('cancelWithdrawBtn');

      function openWithdraw(){ if(wModal) wModal.classList.add('open'); }
      function closeWithdraw(){ if(wModal) wModal.classList.remove('open'); }

      if(openWBtn) openWBtn.addEventListener('click', openWithdraw);
      if(openWBtn2) openWBtn2.addEventListener('click', openWithdraw);
      if(closeWBtn) closeWBtn.addEventListener('click', closeWithdraw);
      if(cancelWBtn) cancelWBtn.addEventListener('click', closeWithdraw);

      if(window.location.search.indexOf('modal=withdraw') !== -1 || window.location.hash === '#withdraw'){
        openWithdraw();
      }

      // Live search tabel event finance
      var evSearch = document.getElementById('eventFinSearch');
      var evRows = document.querySelectorAll('#eventFinTable .ev-fin-row');
      if(evSearch && evRows.length){
        evSearch.addEventListener('input', function(){
          var q = evSearch.value.trim().toLowerCase();
          evRows.forEach(function(row){
            var match = (row.getAttribute('data-name') || '').indexOf(q) !== -1;
            row.style.display = match ? '' : 'none';
          });
        });
      }

      // Modal Ubah Status (Admin)
      var statusModal = document.getElementById('statusModal');
      var statusForm = document.getElementById('statusForm');
      var closeStatusBtn = document.getElementById('closeStatusModalBtn');
      var cancelStatusBtn = document.getElementById('cancelStatusBtn');
      var statusSelect = document.getElementById('wStatusSelect');
      var statusNote = document.getElementById('wStatusNote');
      var statusDesc = document.getElementById('statusModalDesc');

      document.querySelectorAll('.js-edit-status').forEach(function(btn){
        btn.addEventListener('click', function(){
          var id = btn.getAttribute('data-id');
          var amount = btn.getAttribute('data-amount');
          var status = btn.getAttribute('data-status');
          var note = btn.getAttribute('data-note') || '';

          if(statusForm){
            statusForm.action = '<?= base_url('dashboard/finance/withdraw/status') ?>/' + id;
          }
          if(statusDesc){
            statusDesc.textContent = 'Perbarui status penarikan #' + id + ' senilai ' + amount + '.';
          }
          if(statusSelect) statusSelect.value = status;
          if(statusNote) statusNote.value = note;
          if(statusModal) statusModal.classList.add('open');
        });
      });

      if(closeStatusBtn) closeStatusBtn.addEventListener('click', function(){ if(statusModal) statusModal.classList.remove('open'); });
      if(cancelStatusBtn) cancelStatusBtn.addEventListener('click', function(){ if(statusModal) statusModal.classList.remove('open'); });
    })();
  </script>
</body>
</html>
