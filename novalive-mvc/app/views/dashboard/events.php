<?php
/**
 * Halaman Kelola Event Saya — Gaya Quixotic
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
  <title><?= e($pageTitle ?? 'NOVA LIVE — Event Saya') ?></title>
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
    .btn-gold{
      display:inline-flex;align-items:center;gap:8px;background:var(--gold);color:var(--navy);
      border-radius:999px;padding:10px 18px;font-size:13.5px;font-weight:700;min-height:42px;
      font-family:'Plus Jakarta Sans',sans-serif;box-shadow:0 8px 18px rgba(212,180,117,.35);transition:background .15s;text-decoration:none;
    }
    .btn-gold:hover{background:var(--gold-deep);color:#fff}

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
    .b-draft{background:#EDF0F2;color:#52657C}
    .b-done{background:#F1F3F4;color:var(--muted)}
    .mini-btn{
      border:1.5px solid var(--line);background:var(--card);color:#5B6B78;border-radius:9px;
      padding:5px 11px;font-size:12.5px;font-weight:600;min-height:32px;transition:all .15s;white-space:nowrap;display:inline-flex;align-items:center;
    }
    .mini-btn:hover{color:var(--navy);border-color:var(--gold-deep)}
    .mini-btn.warn:hover{color:var(--gold-dark);border-color:var(--gold-deep);background:var(--cream)}
    .mini-btn.danger{color:var(--red)}
    .mini-btn.danger:hover{background:var(--red-bg);border-color:var(--red)}
    .row-actions{display:flex;gap:6px;flex-wrap:wrap;justify-content:flex-end}
    .progress{height:7px;border-radius:99px;background:#EDF1F3;margin-top:6px;overflow:hidden}
    .progress i{display:block;height:100%;border-radius:99px;background:var(--gold-deep)}
    .mini-progress b{font-family:'Plus Jakarta Sans',sans-serif}
    .mini-progress small{color:var(--muted)}
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
      .ev-cell{padding-bottom:4px}
      .ev-cell::before{display:none}
      .empty-row td::before{display:none}
      td.no-label::before{display:none}
      .row-actions{justify-content:flex-start;margin-top:6px}
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

    <!-- ========== VIEW: EVENT SAYA ========== -->
    <section class="view active" id="view-event" aria-label="Event Saya">
      <div class="page-head">
        <div>
          <h1>Event Saya</h1>
          <p>Kelola semua event yang kamu selenggarakan di NOVA LIVE</p>
        </div>
        <div class="spacer"></div>
        <a class="btn-gold" href="<?= base_url('events/create') ?>" id="buatEventBtn2">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
          Buat Event Baru
        </a>
      </div>
      <div class="card">
        <div class="table-tools">
          <div class="chips" id="eventChips">
            <button class="chip on" type="button" data-filter="semua">Semua</button>
            <button class="chip" type="button" data-filter="tayang">Tayang</button>
            <button class="chip" type="button" data-filter="draft">Draft</button>
            <button class="chip" type="button" data-filter="selesai">Selesai</button>
          </div>
          <div class="mini-search">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/></svg>
            <input type="search" id="eventSearch" placeholder="Cari nama event…" aria-label="Cari event" value="<?= e($filters['search'] ?? '') ?>" />
          </div>
        </div>
        <div class="tbl-scroll">
          <table class="tbl">
            <thead>
              <tr>
                <th scope="col">Event</th>
                <th scope="col">Kategori</th>
                <th scope="col">Tanggal</th>
                <th scope="col">Harga Mulai</th>
                <th scope="col">Tiket Terjual</th>
                <th scope="col">Status</th>
                <th scope="col">Aksi</th>
              </tr>
            </thead>
            <tbody id="eventTableBody">
              <?php if (empty($data)): ?>
                <tr class="empty-row"><td colspan="7">Belum ada data event yang terdaftar.</td></tr>
              <?php else: ?>
                <?php foreach ($data as $e): 
                  $sold = (int)($e['sold'] ?? 0);
                  $quota = (int)($e['quota'] ?? 0);
                  $pct = $quota > 0 ? round(($sold / $quota) * 100) : 0;
                  $initials = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $e['title']), 0, 2));
                  if (strlen($initials) < 2) $initials = 'EV';
                  $color = !empty($e['category_color']) ? $e['category_color'] : '#23649A';
                  $priceDisplay = (float)$e['price'] > 0 ? rupiah($e['price']) : 'Gratis';
                ?>
                  <tr class="event-row" data-name="<?= strtolower(e($e['title'])) ?>" data-status="tayang">
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
                    <td data-label="Tanggal" style="white-space:nowrap"><?= tanggal($e['event_date']) ?></td>
                    <td data-label="Harga Mulai"><b><?= $priceDisplay ?></b></td>
                    <td data-label="Tiket Terjual">
                      <div class="mini-progress">
                        <b><?= number_format($sold, 0, ',', '.') ?></b> <small>/ <?= number_format($quota, 0, ',', '.') ?> (<?= $pct ?>%)</small>
                        <div class="progress"><i style="width:<?= min(100, $pct) ?>%"></i></div>
                      </div>
                    </td>
                    <td data-label="Status" class="no-label"><span class="badge b-live status-badge">Tayang</span></td>
                    <td data-label="Aksi" class="no-label">
                      <div class="row-actions">
                        <button type="button" class="mini-btn warn js-toggle-ev" data-id="<?= (int)$e['id'] ?>" data-name="<?= e($e['title']) ?>">Jeda</button>
                        <a href="<?= base_url('events/edit/' . (int)$e['id']) ?>" class="mini-btn">Ubah</a>
                        <a href="<?= base_url('events/delete/' . (int)$e['id']) ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus event ini?')" class="mini-btn danger">Hapus</a>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
              <tr id="emptyEventRow" class="empty-row" hidden>
                <td colspan="7">Tidak ada event yang cocok dengan filter atau pencarian.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </section>

    <?php $shellFooter = true; require __DIR__ . '/sidebar.php'; ?>

  <!-- Script Khusus Event: Chips filter, Live Search & Jeda/Tayangkan Toggle -->
  <script>
    (function(){
      var currentFilter = "semua";
      var searchInput = document.getElementById("eventSearch");
      var chips = document.querySelectorAll("#eventChips .chip");
      var rows = document.querySelectorAll("#eventTableBody .event-row");
      var emptyRow = document.getElementById("emptyEventRow");

      function filterRows() {
        var q = (searchInput ? searchInput.value : "").trim().toLowerCase();
        var visible = 0;
        rows.forEach(function(r) {
          var name = r.getAttribute("data-name") || "";
          var st = r.getAttribute("data-status") || "tayang";
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

      // Jeda / Tayangkan status toggle
      document.querySelectorAll(".js-toggle-ev").forEach(function(btn) {
        btn.addEventListener("click", function() {
          var row = btn.closest(".event-row");
          if (!row) return;
          var name = btn.getAttribute("data-name") || "Event";
          var curSt = row.getAttribute("data-status") || "tayang";
          var badge = row.querySelector(".status-badge");

          if (curSt === "tayang") {
            row.setAttribute("data-status", "draft");
            btn.textContent = "Tayangkan";
            if (badge) {
              badge.className = "badge b-draft status-badge";
              badge.textContent = "Draft";
            }
            showToast('<em>' + name + '</em> dijeda dan kembali ke Draft.');
          } else {
            row.setAttribute("data-status", "tayang");
            btn.textContent = "Jeda";
            if (badge) {
              badge.className = "badge b-live status-badge";
              badge.textContent = "Tayang";
            }
            showToast('<em>' + name + '</em> sekarang tayang di NOVA LIVE.');
          }
          filterRows();
        });
      });
    })();
  </script>
</body>
</html>
