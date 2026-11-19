<?php
/**
 * Halaman Manajemen Pesanan Tiket — Gaya Quixotic
 * NOVA LIVE MVC — Langkah 11 Revisi
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
  <title><?= e($pageTitle ?? 'NOVA LIVE — Pesanan') ?></title>
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
      font-family:'Inter',system-ui,sans-serif;
      background:var(--panel);
      color:var(--ink);
      -webkit-font-smoothing:antialiased;
      min-height:100vh;
      margin:0;
      padding:0;
    }
    h1,h2,h3,.pj{font-family:'Plus Jakarta Sans','Inter',sans-serif}
    button{font-family:inherit;cursor:pointer;border:none;background:none;color:inherit}
    a{text-decoration:none;color:inherit}
    .frame{
      width:100%;
      max-width:none;
      margin:0;
      background:var(--panel);
      border-radius:0;
      padding:16px 20px 24px;
      box-shadow:none;
      border:none;
    }

    /* ---------- Top bar ---------- */
    .topbar{
      display:flex;align-items:center;gap:18px;
      background:var(--card);
      border-radius:999px;
      padding:10px 12px 10px 20px;
      box-shadow:var(--shadow);
      position:relative;z-index:30;
    }
    .logo{display:flex;align-items:center;gap:9px;text-decoration:none;color:var(--navy);flex:none}
    .logo svg{display:block}
    .logo-ticket{height:40px;width:auto;overflow:visible;cursor:pointer;flex:none}
    .logo-word{font-family:'Plus Jakarta Sans',sans-serif;font-weight:800;font-size:19px;letter-spacing:.2px;color:var(--navy);white-space:nowrap}
    .logo-word em{font-style:normal;color:var(--gold-deep)}
    .topnav{display:flex;gap:4px;margin:0 auto;background:#F1F3F4;border-radius:999px;padding:4px}
    .topnav a, .topnav button{
      padding:8px 18px;border-radius:999px;font-size:13.5px;font-weight:600;color:#5B6B78;min-height:36px;
      transition:background .15s,color .15s;text-decoration:none;display:inline-flex;align-items:center;
    }
    .topnav a:hover, .topnav button:hover{color:var(--navy)}
    .topnav a.active, .topnav button.active{background:var(--card);color:var(--navy);box-shadow:0 2px 8px rgba(14,50,84,.10)}
    .top-actions{display:flex;align-items:center;gap:6px;flex:none;margin-left:auto}
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
    .rail-sep{width:26px;height:1px;background:var(--line);margin:5px 0;flex:none}
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
    .btn-outline{
      display:inline-flex;align-items:center;gap:8px;background:var(--card);color:var(--navy);
      border:1.5px solid var(--line);border-radius:999px;padding:10px 18px;font-size:13.5px;font-weight:600;min-height:42px;transition:border-color .15s,background .15s;cursor:pointer;
    }
    .btn-outline:hover{border-color:var(--gold-deep);background:#FBF8F0}

    /* ---------- Tables in views ---------- */
    .table-tools{display:flex;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:14px}
    .table-tools h2{font-size:15px;margin:0}
    .chips{display:flex;gap:7px;flex-wrap:wrap}
    .chip{
      border:1.5px solid var(--line);background:var(--card);color:#5B6B78;border-radius:999px;
      padding:6px 14px;font-size:12.5px;font-weight:600;min-height:34px;transition:all .15s;
    }
    .chip:hover{color:var(--navy);border-color:var(--gold-deep)}
    .chip.on{background:var(--navy);border-color:var(--navy);color:#fff}
    .mini-search{
      margin-left:auto;display:flex;align-items:center;gap:7px;background:#F1F3F4;border-radius:999px;padding:0 13px;min-height:38px;color:#8A97A3;
    }
    .mini-search input{border:none;outline:none;background:transparent;font-family:inherit;font-size:13px;color:var(--ink);width:170px;min-height:36px}
    .tbl-scroll{overflow-x:auto}
    .tbl{min-width:700px;width:100%;border-collapse:collapse}
    thead th{
      text-align:left;font-size:11.5px;font-weight:600;color:var(--muted);letter-spacing:.4px;
      padding:4px 10px 10px;border-bottom:1px solid var(--line);white-space:nowrap;
    }
    thead th:last-child,tbody td:last-child{text-align:right}
    tbody td{padding:13px 10px;border-bottom:1px solid #F0F2F4;font-size:13.5px;vertical-align:middle;white-space:nowrap}
    tbody tr:last-child td{border-bottom:none}
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
      padding:5px 11px;font-size:12.5px;font-weight:600;min-height:32px;transition:all .15s;white-space:nowrap;
    }
    .mini-btn:hover{color:var(--navy);border-color:var(--gold-deep)}
    .empty-row td{text-align:center;color:var(--muted);padding:26px 10px;white-space:normal}

    /* ---------- Modal & detail list ---------- */
    .overlay{
      position:fixed;inset:0;background:rgba(14,50,84,.45);backdrop-filter:blur(3px);
      display:none;place-items:center;z-index:100;padding:18px;overflow-y:auto;
    }
    .overlay.open{display:grid}
    .modal{background:#fff;border-radius:24px;max-width:460px;width:100%;padding:26px;box-shadow:0 30px 70px rgba(14,50,84,.3);position:relative;margin:auto}
    .modal h2{margin:0 0 4px;font-size:19px;color:var(--navy);padding-right:34px}
    .modal-x{
      position:absolute;top:14px;right:14px;width:34px;height:34px;border-radius:50%;
      display:grid;place-items:center;color:#8A97A3;font-size:15px;
    }
    .modal-x:hover{background:#EEF3F7;color:var(--navy)}
    .detail-list{display:grid;grid-template-columns:118px 1fr;gap:9px 14px;margin:0;font-size:13.5px}
    .detail-list dt{color:var(--muted);font-weight:500}
    .detail-list dd{margin:0;font-weight:600;color:var(--ink)}
    .detail-total{display:flex;justify-content:space-between;align-items:center;border-top:1.5px dashed var(--line);margin-top:16px;padding-top:14px;font-family:'Plus Jakarta Sans',sans-serif;font-weight:800;font-size:16px;color:var(--navy)}

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
      .rail-sep{display:none}
      .rail a, .rail button{width:42px;height:42px}
      #railTip{display:none}
    }
    @media (max-width:640px){
      body{padding:0}
      .frame{padding:10px 10px 16px;border-radius:0}
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
      .empty-row td::before{display:none}
      td.no-label::before{display:none}
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

    <!-- ========== VIEW: PESANAN ========== -->
    <section class="view active" id="view-pesanan" aria-label="Pesanan">
      <div class="page-head">
        <div>
          <h1>Pesanan</h1>
          <p>Semua transaksi pembelian tiket dari event kamu</p>
        </div>
        <div class="spacer"></div>
        <button class="btn-outline" type="button" id="exportBtn">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v12M7 10l5 5 5-5M4 21h16"/></svg>
          Export CSV
        </button>
      </div>

      <div class="card">
        <div class="table-tools">
          <div class="chips" id="orderChips">
            <button class="chip on" type="button" data-filter="semua">Semua</button>
            <button class="chip" type="button" data-filter="terkirim">Terkirim</button>
            <button class="chip" type="button" data-filter="menunggu">Menunggu</button>
            <button class="chip" type="button" data-filter="selesai">Selesai</button>
            <button class="chip" type="button" data-filter="dibatalkan">Dibatalkan</button>
          </div>
          <div class="mini-search">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/></svg>
            <input type="search" id="orderSearch" placeholder="Cari invoice / pembeli…" aria-label="Cari pesanan" value="<?= e($filters['search'] ?? '') ?>" />
          </div>
        </div>
        <div class="tbl-scroll">
          <table class="tbl">
            <thead>
              <tr>
                <th scope="col">Invoice</th>
                <th scope="col">Pembeli</th>
                <th scope="col">Event</th>
                <th scope="col">Tiket</th>
                <th scope="col">Total</th>
                <th scope="col">Status</th>
                <th scope="col">Aksi</th>
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
                    <td data-label="Invoice">
                      <b><?= e($o['invoice']) ?></b>
                      <div style="font-size:11.5px;color:var(--muted);margin-top:1px;"><?= e($formattedDate) ?></div>
                    </td>
                    <td data-label="Pembeli">
                      <div style="font-weight:600;"><?= e($o['buyer_name']) ?></div>
                      <div style="font-size:11.5px;color:var(--muted);margin-top:1px;"><?= e($o['buyer_email']) ?></div>
                    </td>
                    <td data-label="Event" style="min-width:180px">
                      <div style="font-weight:600;"><?= e($o['event_title']) ?></div>
                      <div style="font-size:11.5px;color:var(--muted);margin-top:1px;">Kode: <?= e($o['ticket_code'] ?? '-') ?></div>
                    </td>
                    <td data-label="Tiket"><?= (int)$o['qty'] ?> tiket</td>
                    <td data-label="Total"><b><?= rupiah($o['total_price']) ?></b></td>
                    <td data-label="Status" class="no-label"><span class="badge <?= $cls ?>"><?= e($st) ?></span></td>
                    <td data-label="Aksi" class="no-label">
                      <button type="button" class="mini-btn js-order-detail"
                        data-id="<?= (int)$o['id'] ?>"
                        data-checkedin="<?= !empty($o['checked_in_at']) ? '1' : '0' ?>"
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
              <tr id="emptyOrderRow" class="empty-row" hidden>
                <td colspan="7">Tidak ada pesanan yang cocok dengan filter atau pencarian.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </section>

    <?php $shellFooter = true; require __DIR__ . '/sidebar.php'; ?>

  <!-- Modal Detail Pesanan -->
  <div class="overlay" id="orderModalBack" role="dialog" aria-modal="true" aria-labelledby="orderModalTitle">
    <div class="modal">
      <button type="button" class="modal-x" id="modalCloseBtn" aria-label="Tutup modal">✕</button>
      <h2 id="orderModalTitle">Detail Pesanan</h2>
      <p id="orderModalInv" style="color:var(--muted);font-size:13px;margin:0 0 16px;">-</p>

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

      <div class="modal-actions" style="margin-top:20px;display:flex;gap:10px;justify-content:flex-end;align-items:center;">
        <a id="btnEticket" href="#" class="btn-navy" style="text-decoration:none;display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:10px;font-size:13px;font-weight:600;background:var(--navy);color:#fff;">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 3v12M7 10l5 5 5-5M4 21h16"/></svg>
          Lihat E-Tiket
        </a>
        <button type="button" id="btnOpenRefundForm" class="btn-danger" style="display:none;background:#DC2626;color:#fff;border-radius:10px;padding:8px 16px;font-size:13px;font-weight:600;border:none;cursor:pointer;">
          Ajukan Refund
        </button>
      </div>

      <!-- Form Pengajuan Refund -->
      <form id="refundForm" method="POST" action="<?= base_url('dashboard/orders/refunds/create') ?>" style="display:none;margin-top:16px;padding-top:16px;border-top:1px solid var(--line);">
        <?= csrf_field() ?>
        <input type="hidden" name="order_id" id="refundOrderId" value="" />
        <div style="margin-bottom:10px;">
          <label for="refundReason" style="display:block;font-size:12.5px;font-weight:600;margin-bottom:6px;color:var(--ink);">Alasan Pengajuan Refund:</label>
          <textarea name="reason" id="refundReason" rows="2" style="width:100%;border:1.5px solid var(--line);border-radius:10px;padding:8px 12px;font-family:inherit;font-size:13px;box-sizing:border-box;" required placeholder="Contoh: Pembeli berhalangan hadir karena keperluan mendesak..."></textarea>
        </div>
        <div style="display:flex;gap:8px;justify-content:flex-end;">
          <button type="button" id="btnCancelRefund" style="background:#F1F3F4;border:none;border-radius:8px;padding:7px 14px;font-size:12.5px;font-weight:600;cursor:pointer;">Batal</button>
          <button type="submit" style="background:#DC2626;color:#fff;border:none;border-radius:8px;padding:7px 14px;font-size:12.5px;font-weight:600;cursor:pointer;">Kirim Pengajuan</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Script Khusus Pesanan: Chips filter, Live Search, Modal Detail & Export CSV -->
  <script>
    (function(){
      var currentFilter = "semua";
      var searchInput = document.getElementById("orderSearch");
      var chips = document.querySelectorAll("#orderChips .chip");
      var rows = document.querySelectorAll("#orderTableBody .order-row");
      var emptyRow = document.getElementById("emptyOrderRow");

      function filterRows() {
        var q = (searchInput ? searchInput.value : "").trim().toLowerCase();
        var visible = 0;
        rows.forEach(function(r) {
          var inv = r.getAttribute("data-invoice") || "";
          var buyer = r.getAttribute("data-buyer") || "";
          var email = r.getAttribute("data-email") || "";
          var ev = r.getAttribute("data-event") || "";
          var st = r.getAttribute("data-status") || "";

          var matchQ = !q || inv.indexOf(q) > -1 || buyer.indexOf(q) > -1 || email.indexOf(q) > -1 || ev.indexOf(q) > -1;
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

      // Modal Detail Pesanan
      var modalBack = document.getElementById("orderModalBack");
      var closeBtn = document.getElementById("modalCloseBtn");
      var btnEticket = document.getElementById("btnEticket");
      var btnRefund = document.getElementById("btnOpenRefundForm");
      var refundForm = document.getElementById("refundForm");
      var refundOrderId = document.getElementById("refundOrderId");
      var btnCancelRefund = document.getElementById("btnCancelRefund");

      if (btnCancelRefund) {
        btnCancelRefund.onclick = function() {
          refundForm.style.display = "none";
          if (btnRefund) btnRefund.style.display = "inline-flex";
        };
      }

      function openModal(btn) {
        var orderId = btn.getAttribute("data-id") || "";
        var st = btn.getAttribute("data-status") || "";
        var checkedIn = btn.getAttribute("data-checkedin") === "1";
        var ticketCode = btn.getAttribute("data-ticket") || "";

        document.getElementById("orderModalInv").textContent = "Invoice: " + (btn.getAttribute("data-inv") || "-") + " · " + (btn.getAttribute("data-date") || "");
        document.getElementById("mdBuyer").textContent = btn.getAttribute("data-buyer") || "-";
        document.getElementById("mdEmail").textContent = btn.getAttribute("data-email") || "-";
        document.getElementById("mdEvent").textContent = btn.getAttribute("data-event") || "-";
        document.getElementById("mdQty").textContent = (btn.getAttribute("data-qty") || "0") + " tiket";
        document.getElementById("mdStatus").textContent = st;
        document.getElementById("mdTicket").textContent = ticketCode;
        document.getElementById("mdTotal").textContent = btn.getAttribute("data-total") || "-";

        if (btnEticket) {
          if (ticketCode && ticketCode !== "-") {
            btnEticket.href = "<?= base_url('dashboard/orders/tickets/') ?>" + orderId;
            btnEticket.style.display = "inline-flex";
          } else {
            btnEticket.style.display = "none";
          }
        }

        if (refundForm) refundForm.style.display = "none";

        if (btnRefund) {
          if ((st === "Terkirim" || st === "Selesai") && !checkedIn) {
            btnRefund.style.display = "inline-flex";
            btnRefund.onclick = function() {
              if (refundOrderId) refundOrderId.value = orderId;
              refundForm.style.display = "block";
              btnRefund.style.display = "none";
            };
          } else {
            btnRefund.style.display = "none";
          }
        }

        modalBack.classList.add("open");
      }

      function closeModal() {
        modalBack.classList.remove("open");
      }

      document.querySelectorAll(".js-order-detail").forEach(function(btn) {
        btn.addEventListener("click", function() {
          openModal(btn);
        });
      });

      if (closeBtn) {
        closeBtn.addEventListener("click", closeModal);
      }
      if (modalBack) {
        modalBack.addEventListener("click", function(e) {
          if (e.target === modalBack) closeModal();
        });
      }
      document.addEventListener("keydown", function(e) {
        if (e.key === "Escape" && modalBack.classList.contains("open")) closeModal();
      });

      // Export CSV
      var exportBtn = document.getElementById("exportBtn");
      if (exportBtn) {
        exportBtn.addEventListener("click", function() {
          var csv = ["Invoice,Pembeli,Email,Event,Kode Tiket,Jumlah Tiket,Total Bayar,Status"];
          rows.forEach(function(r) {
            if (r.style.display !== "none") {
              var btn = r.querySelector(".js-order-detail");
              if (btn) {
                var line = [
                  '"' + (btn.getAttribute("data-inv") || "") + '"',
                  '"' + (btn.getAttribute("data-buyer") || "").replace(/"/g, '""') + '"',
                  '"' + (btn.getAttribute("data-email") || "") + '"',
                  '"' + (btn.getAttribute("data-event") || "").replace(/"/g, '""') + '"',
                  '"' + (btn.getAttribute("data-ticket") || "") + '"',
                  btn.getAttribute("data-qty") || "0",
                  '"' + (btn.getAttribute("data-total") || "") + '"',
                  '"' + (btn.getAttribute("data-status") || "") + '"'
                ].join(",");
                csv.push(line);
              }
            }
          });
          var blob = new Blob(["\uFEFF" + csv.join("\n")], { type: "text/csv;charset=utf-8;" });
          var link = document.createElement("a");
          var url = URL.createObjectURL(blob);
          link.setAttribute("href", url);
          link.setAttribute("download", "pesanan_novalive_" + (new Date().toISOString().slice(0, 10)) + ".csv");
          link.style.visibility = "hidden";
          document.body.appendChild(link);
          link.click();
          document.body.removeChild(link);
          showToast("Data pesanan berhasil diekspor ke CSV.");
        });
      }
    })();
  </script>
</body>
</html>
