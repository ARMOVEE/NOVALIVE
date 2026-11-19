<?php
/**
 * Halaman Log Aktivitas Admin (Audit Trail) — Gaya Quixotic
 * NOVA LIVE MVC — Tahap 3
 */
$currentUser = $currentUser ?? ['full_name' => 'Admin NOVA LIVE', 'role' => 'admin'];
$userInitials = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $currentUser['full_name'] ?? 'AD'), 0, 2));
if (strlen($userInitials) < 2) $userInitials = 'AD';

$logs = $logsData['data'] ?? [];
$totalLogs = (int)($logsData['total'] ?? 0);
$currentPage = (int)($logsData['currentPage'] ?? 1);
$totalPages = (int)($logsData['totalPages'] ?? 1);
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <meta name="color-scheme" content="light" />
  <title><?= e($pageTitle ?? 'NOVA LIVE — Log Aktivitas') ?></title>
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
      max-width:1680px;margin:0 auto;
      background:var(--panel);
      border-radius:30px;
      padding:20px 32px 26px;
      box-shadow:0 24px 60px rgba(14,50,84,.10);
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
    .pop.open{display:block}
    .greet{display:flex;align-items:center;justify-content:space-between;margin:22px 4px 18px;flex-wrap:wrap;gap:12px}
    .greet h1{margin:0;font-size:24px;color:var(--navy);font-weight:700}
    .greet h1 strong{font-weight:800}
    .layout{display:flex;gap:22px;align-items:flex-start}
    .rail{flex:none;width:58px;background:var(--card);border-radius:999px;padding:14px 8px;box-shadow:var(--shadow);display:flex;flex-direction:column;align-items:center;gap:10px;position:sticky;top:20px;min-height:480px}
    .rail a, .rail button{width:42px;height:42px;border-radius:50%;display:grid;place-items:center;color:#6A7885;position:relative;transition:background .15s,color .15s;text-decoration:none}
    .rail a:hover, .rail button:hover{background:#F1F4F7;color:var(--navy)}
    .rail a.active, .rail button.active{background:var(--navy);color:#fff;box-shadow:0 4px 14px rgba(14,50,84,.28)}
    .rail .rail-badge{position:absolute;top:5px;right:5px;min-width:16px;height:16px;padding:0 4px;border-radius:999px;background:var(--gold-deep);color:#fff;font-size:10px;font-weight:800;display:grid;place-items:center;line-height:1;border:2px solid var(--card)}
    .rail .spacer{flex:1}
    .views{flex:1;min-width:0}
    .view{display:block}
    .page-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:18px;flex-wrap:wrap;gap:12px}
    .page-head h1{margin:0 0 3px;font-size:22px;color:var(--navy);font-weight:800}
    .page-head p{margin:0;font-size:13px;color:var(--muted)}
    .card{background:var(--card);border-radius:20px;padding:20px 22px;box-shadow:var(--shadow);position:relative;margin-bottom:20px}

    /* Filter Bar */
    .filter-grid{
      display:grid;grid-template-columns:repeat(auto-fit, minmax(170px, 1fr));gap:12px;align-items:center;
      background:var(--card);border-radius:18px;padding:16px 20px;box-shadow:var(--shadow);margin-bottom:20px;
    }
    .filter-grid select, .filter-grid input{
      width:100%;border:1.5px solid var(--line);border-radius:10px;padding:8px 12px;font-size:13px;
      font-family:inherit;color:var(--ink);background:#FBFCFD;outline:none;
    }
    .filter-grid select:focus, .filter-grid input:focus{border-color:var(--blue);background:#fff}
    .btn-apply{
      background:var(--navy);color:#fff;border-radius:10px;padding:9px 18px;font-weight:700;font-size:13px;
      cursor:pointer;border:none;height:100%;
    }
    .btn-apply:hover{background:var(--navy-2)}

    /* Audit Log Table */
    .tbl-scroll{overflow-x:auto}
    .tbl{width:100%;border-collapse:collapse;text-align:left;font-size:13px}
    .tbl th{padding:12px 14px;color:var(--muted);font-weight:700;font-size:12px;letter-spacing:.3px;border-bottom:1.5px solid var(--line)}
    .tbl td{padding:12px 14px;border-bottom:1px solid #F1F4F7;color:var(--ink);vertical-align:middle}
    .tbl tr:hover td{background:#F9FBFC}
    .badge-act{
      display:inline-block;padding:3px 9px;border-radius:6px;font-size:11px;font-weight:700;
      font-family:'Plus Jakarta Sans',sans-serif;background:#EDF3F7;color:var(--navy);
    }
    .badge-role{
      display:inline-block;padding:2px 7px;border-radius:4px;font-size:10.5px;font-weight:700;
      text-transform:uppercase;background:#F1F4F7;color:var(--muted);
    }
    .badge-role.admin{background:var(--cream);color:var(--gold-dark)}
    .badge-role.manager{background:#E6F2F8;color:var(--blue)}
    .ip-txt{font-family:monospace;font-size:11.5px;color:var(--muted)}

    /* Pagination */
    .pag-wrap{display:flex;align-items:center;justify-content:space-between;margin-top:18px;flex-wrap:wrap;gap:10px}
    .pag-info{font-size:12.5px;color:var(--muted)}
    .pag-btns{display:flex;gap:5px}
    .pag-btn{
      min-width:34px;height:34px;border-radius:8px;border:1.5px solid var(--line);background:#fff;
      font-size:12.5px;font-weight:700;color:var(--navy);display:grid;place-items:center;padding:0 8px;text-decoration:none;
    }
    .pag-btn:hover{background:#F8FAFC;border-color:#CFD8DF}
    .pag-btn.active{background:var(--navy);color:#fff;border-color:var(--navy)}
    .pag-btn.disabled{opacity:.4;pointer-events:none}

    @media (max-width:820px){
      .layout{flex-direction:column}
      .rail{flex-direction:row;width:100%;min-height:auto;border-radius:20px;padding:8px 12px;position:static}
      .rail .spacer{display:none}
    }
  </style>
</head>
<body>
  <div class="frame">

    <?php require __DIR__ . '/sidebar.php'; ?>

    <!-- ========== VIEW: LOG AKTIVITAS ========== -->
    <section class="view" id="view-log" aria-label="Log Aktivitas">
      <div class="page-head">
        <div>
          <h1>Log Aktivitas Admin</h1>
          <p>Jejak audit trail: siapa mengubah data apa, waktu perubahan, dan alamat IP klien</p>
        </div>
      </div>

      <!-- Filter Pencarian Log -->
      <form class="filter-grid" method="GET" action="<?= base_url('dashboard/reports/logs') ?>">
        <div>
          <label style="display:block;font-size:11.5px;font-weight:700;color:var(--muted);margin-bottom:4px">Aksi</label>
          <select name="action">
            <option value="">— Semua Aksi —</option>
            <?php foreach ($distinctActions as $act): ?>
              <option value="<?= e($act) ?>" <?= ($filters['action'] ?? '') === $act ? 'selected' : '' ?>><?= e($act) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label style="display:block;font-size:11.5px;font-weight:700;color:var(--muted);margin-bottom:4px">Pengguna</label>
          <select name="user_id">
            <option value="">— Semua Pengguna —</option>
            <?php foreach ($users as $u): ?>
              <option value="<?= (int)$u['id'] ?>" <?= ($filters['user_id'] ?? '') == $u['id'] ? 'selected' : '' ?>><?= e($u['full_name']) ?> (<?= e($u['role']) ?>)</option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label style="display:block;font-size:11.5px;font-weight:700;color:var(--muted);margin-bottom:4px">Dari Tanggal</label>
          <input type="date" name="start_date" value="<?= e($filters['start_date'] ?? '') ?>" />
        </div>

        <div>
          <label style="display:block;font-size:11.5px;font-weight:700;color:var(--muted);margin-bottom:4px">Sampai Tanggal</label>
          <input type="date" name="end_date" value="<?= e($filters['end_date'] ?? '') ?>" />
        </div>

        <div>
          <label style="display:block;font-size:11.5px;font-weight:700;color:var(--muted);margin-bottom:4px">Cari Kata Kunci</label>
          <input type="search" name="q" placeholder="Cari deskripsi / IP…" value="<?= e($filters['search'] ?? '') ?>" />
        </div>

        <div style="display:flex;gap:6px;align-items:flex-end">
          <button type="submit" class="btn-apply" style="flex:1">Filter</button>
          <?php if (!empty($filters['action']) || !empty($filters['user_id']) || !empty($filters['start_date']) || !empty($filters['end_date']) || !empty($filters['search'])): ?>
            <a href="<?= base_url('dashboard/reports/logs') ?>" class="pag-btn" style="flex:none" title="Reset Filter">✕</a>
          <?php endif; ?>
        </div>
      </form>

      <!-- Tabel Log Aktivitas -->
      <div class="card">
        <div class="tbl-scroll">
          <table class="tbl">
            <thead>
              <tr>
                <th scope="col" style="width:160px">Waktu &amp; Tanggal</th>
                <th scope="col" style="width:180px">Pengguna</th>
                <th scope="col" style="width:140px">Aksi</th>
                <th scope="col" style="width:110px">Entitas</th>
                <th scope="col">Deskripsi Aktivitas</th>
                <th scope="col" style="width:120px">Alamat IP</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($logs)): ?>
                <tr>
                  <td colspan="6" style="text-align:center;padding:32px;color:var(--muted)">
                    Tidak ada rekaman log aktivitas yang sesuai kriteria pencarian.
                  </td>
                </tr>
              <?php else: ?>
                <?php foreach ($logs as $l): ?>
                  <tr>
                    <td>
                      <b><?= tanggal($l['created_at'], 'd M Y') ?></b><br>
                      <small style="color:var(--muted)"><?= date('H:i:s', strtotime($l['created_at'])) ?> WIB</small>
                    </td>
                    <td>
                      <?php if (!empty($l['user_name'])): ?>
                        <b><?= e($l['user_name']) ?></b><br>
                        <span class="badge-role <?= e($l['user_role'] ?? '') ?>"><?= e($l['user_role'] ?? 'user') ?></span>
                      <?php else: ?>
                        <span style="color:var(--muted)">Sistem Otomatis</span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <span class="badge-act"><?= e($l['action']) ?></span>
                    </td>
                    <td>
                      <?php if (!empty($l['entity'])): ?>
                        <span style="font-weight:600"><?= e($l['entity']) ?></span>
                        <?php if (!empty($l['entity_id'])): ?>
                          <small style="color:var(--muted)">#<?= (int)$l['entity_id'] ?></small>
                        <?php endif; ?>
                      <?php else: ?>
                        <span style="color:var(--muted)">—</span>
                      <?php endif; ?>
                    </td>
                    <td><?= e($l['description'] ?? '—') ?></td>
                    <td>
                      <span class="ip-txt"><?= e($l['ip_address'] ?? '127.0.0.1') ?></span>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <!-- Paginasi 20 per halaman -->
        <?php if ($totalPages > 1): 
          $queryParams = $_GET;
        ?>
          <div class="pag-wrap">
            <span class="pag-info">Menampilkan halaman <b><?= $currentPage ?></b> dari total <b><?= $totalPages ?></b> (<?= number_format($totalLogs) ?> total catatan log)</span>
            <div class="pag-btns">
              <?php
                $queryParams['page'] = max(1, $currentPage - 1);
                $prevUrl = base_url('dashboard/reports/logs?' . http_build_query($queryParams));
              ?>
              <a href="<?= $prevUrl ?>" class="pag-btn <?= $currentPage <= 1 ? 'disabled' : '' ?>">«</a>

              <?php for ($p = max(1, $currentPage - 2); $p <= min($totalPages, $currentPage + 2); $p++): 
                $queryParams['page'] = $p;
                $pageUrl = base_url('dashboard/reports/logs?' . http_build_query($queryParams));
              ?>
                <a href="<?= $pageUrl ?>" class="pag-btn <?= $p === $currentPage ? 'active' : '' ?>"><?= $p ?></a>
              <?php endfor; ?>

              <?php
                $queryParams['page'] = min($totalPages, $currentPage + 1);
                $nextUrl = base_url('dashboard/reports/logs?' . http_build_query($queryParams));
              ?>
              <a href="<?= $nextUrl ?>" class="pag-btn <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">»</a>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </section>

    <?php $shellFooter = true; require __DIR__ . '/sidebar.php'; ?>
  </div>
</body>
</html>
