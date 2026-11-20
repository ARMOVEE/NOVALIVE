<?php
/**
 * Halaman Tata Letak Homepage & Kurasi Event — Gaya Quixotic
 * NOVA LIVE MVC — Tahap 4
 */
$currentUser = $currentUser ?? ['full_name' => 'Admin NOVA LIVE', 'role' => 'admin'];
$userInitials = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $currentUser['full_name'] ?? 'AD'), 0, 2));
if (strlen($userInitials) < 2) $userInitials = 'AD';

$secSeru = $sections['event_seru'] ?? ['title' => 'Event Seru Untukmu', 'is_visible' => 1, 'sort_order' => 1, 'item_limit' => 10];
$secKat = $sections['kategori'] ?? ['title' => 'Kategori Event', 'is_visible' => 1, 'sort_order' => 2, 'item_limit' => 8];
$secPop = $sections['event_populer'] ?? ['title' => 'Event Populer', 'is_visible' => 1, 'sort_order' => 3, 'item_limit' => 4];
$secKota = $sections['jelajahi_kota'] ?? ['title' => 'Jelajahi Event di Kotamu', 'is_visible' => 1, 'sort_order' => 4, 'item_limit' => 6];

// Buat map event berdasarkan id
$eventsMap = [];
foreach ($events as $ev) {
    $eventsMap[$ev['id']] = $ev;
}

// Urutan seru & populer
$seruIds = !empty($seruFeaturedIds) ? $seruFeaturedIds : array_slice(array_keys($eventsMap), 0, min(10, count($eventsMap)));
$popIds = !empty($popFeaturedIds) ? $popFeaturedIds : array_slice(array_keys($eventsMap), 0, min(4, count($eventsMap)));
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <meta name="color-scheme" content="light" />
  <link rel="icon" href="data:," />
  <title><?= e($pageTitle ?? 'NOVA LIVE — Tata Letak Homepage') ?></title>
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
      --bar-soft:#CFDFED;
      --cream:#F5EFE0;
      --gold:#EAD4A2;
      --gold-dark:#A88338;
      --gold-deep:#8C6A24;
      --page:#EEF2F6;
      --panel:#FFFFFF;
      --card:#FFFFFF;
      --ink:#1D2D3A;
      --muted:#718292;
      --line:#E3E8EC;
      --line-subtle:#F0F3F6;
      --green:#1E8E5A;
      --green-bg:#E6F4EA;
      --red:#C5221F;
      --red-bg:#FCE8E6;
      --radius:20px;
      --shadow:0 8px 24px rgba(14,50,84,.07);
      --font-display:'Plus Jakarta Sans',sans-serif;
      --font-body:'Inter',system-ui,sans-serif;
    }
    *{box-sizing:border-box}
    body{
      margin:0;padding:22px 18px 30px;background:var(--page);color:var(--ink);
      font-family:var(--font-body);-webkit-font-smoothing:antialiased;
    }
    .frame{
      width:100%;max-width:none;margin:0;background:var(--panel);border-radius:30px;
      padding:20px 24px 26px;box-shadow:0 24px 60px rgba(14,50,84,.10);box-sizing:border-box;
    }
    .topbar{
      display:grid;grid-template-columns:1fr auto 1fr;align-items:center;gap:18px;
      background:var(--card);border-radius:999px;padding:10px 12px 10px 20px;
      box-shadow:var(--shadow);position:relative;z-index:30;
    }
    .logo{display:flex;align-items:center;gap:9px;text-decoration:none;color:var(--navy);flex:none;justify-self:start}
    .logo-ticket{height:40px;width:auto;display:block}
    .logo-word{font-family:'Plus Jakarta Sans',sans-serif;font-weight:800;font-size:19px;letter-spacing:1.5px;color:var(--navy)}
    .topnav{
      display:flex;gap:4px;margin:0;justify-self:center;background:#F1F3F4;border-radius:999px;padding:4px;
    }
    .topnav a, .topnav button{
      padding:7px 16px;border-radius:999px;font-size:13px;font-weight:600;color:#5B6B78;
      border:none;background:transparent;text-decoration:none;cursor:pointer;transition:all .15s;
    }
    .topnav a:hover, .topnav button:hover{color:var(--navy)}
    .topnav a.active, .topnav button.active{
      background:var(--navy);color:#fff;box-shadow:0 3px 10px rgba(14,50,84,.22);
    }
    .top-actions{display:flex;align-items:center;gap:6px;flex:none;justify-self:end;margin-left:0}
    .icon-btn{
      width:38px;height:38px;border-radius:50%;border:none;background:transparent;
      display:grid;place-items:center;color:#4A5965;cursor:pointer;position:relative;transition:background .15s;
    }
    .icon-btn:hover{background:#F1F4F7;color:var(--navy)}
    .user-pill{
      display:flex;align-items:center;gap:8px;padding:3px 10px 3px 4px;border-radius:999px;
      background:#F5F7FA;color:var(--navy);font-weight:700;font-size:12.5px;border:none;cursor:pointer;
    }
    .user-avatar{
      width:30px;height:30px;border-radius:50%;background:var(--navy);color:#fff;
      display:grid;place-items:center;font-size:11.5px;font-weight:800;letter-spacing:.5px;
    }
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
    .views{flex:1;min-width:0}
    .view{display:block}
    .page-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:18px;flex-wrap:wrap;gap:12px}
    .page-head h1{margin:0 0 3px;font-size:22px;color:var(--navy);font-weight:800}
    .page-head p{margin:0;font-size:13px;color:var(--muted)}
    .card{background:var(--card);border-radius:20px;padding:20px 22px;box-shadow:var(--shadow);position:relative;margin-bottom:20px;border:1px solid var(--line-subtle)}
    .card-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;flex-wrap:wrap;gap:10px}
    .card-head h2{margin:0;font-size:17px;font-weight:800;color:var(--navy)}
    .card-head p{margin:2px 0 0;font-size:12.5px;color:var(--muted)}

    /* Alert / Flash Message */
    .alert-flash{
      display:flex;align-items:center;gap:10px;padding:12px 18px;border-radius:14px;
      margin-bottom:18px;font-size:13.5px;font-weight:600;
    }
    .alert-flash.success{background:var(--green-bg);color:var(--green);border:1px solid rgba(30,142,90,.2)}
    .alert-flash.error{background:var(--red-bg);color:var(--red);border:1px solid rgba(197,34,31,.2)}

    .note-demo{
      background:var(--cream);border-left:4px solid var(--gold-deep);border-radius:12px;
      padding:12px 16px;font-size:13px;color:#5D4616;margin-bottom:20px;line-height:1.5;
    }

    /* Buttons */
    .btn-primary{
      display:inline-flex;align-items:center;gap:8px;background:var(--navy);color:#fff;
      border-radius:999px;padding:9px 18px;font-weight:700;font-size:13px;box-shadow:0 4px 14px rgba(14,50,84,.22);
      transition:transform .12s,background .15s;text-decoration:none;border:none;cursor:pointer;
    }
    .btn-primary:hover{background:var(--navy-2);transform:translateY(-1px)}
    .btn-navy{
      display:inline-flex;align-items:center;justify-content:center;gap:8px;background:var(--navy);color:#fff;
      font-weight:700;font-size:13px;border-radius:12px;padding:9px 20px;cursor:pointer;border:none;
      box-shadow:0 4px 12px rgba(14,50,84,.22);transition:background .15s,transform .12s;text-decoration:none;
    }
    .btn-navy:hover{background:var(--navy-2);transform:translateY(-1px)}
    .btn-gold{
      display:inline-flex;align-items:center;justify-content:center;gap:8px;background:var(--gold);color:var(--navy);
      font-weight:800;font-size:13px;border-radius:12px;padding:9px 18px;box-shadow:0 4px 14px rgba(234,212,162,.3);
      cursor:pointer;border:none;transition:transform .12s,background .15s;text-decoration:none;
    }
    .btn-gold:hover{background:#dfc78f;transform:translateY(-1px)}
    .mini-btn{
      border:1.5px solid var(--line);background:var(--card);color:#5B6B78;border-radius:9px;
      padding:5px 11px;font-size:12px;font-weight:600;min-height:30px;transition:all .15s;
      cursor:pointer;display:inline-flex;align-items:center;gap:4px;
    }
    .mini-btn:hover{color:var(--navy);border-color:var(--gold-deep)}
    .mini-btn.warn{color:var(--red);border-color:rgba(197,34,31,.25);background:var(--red-bg)}
    .mini-btn.warn:hover{background:#fadbd8;border-color:var(--red)}

    /* Grid 2 Column */
    .grid-2{display:grid;grid-template-columns:1fr 1fr;gap:20px;align-items:start}

    /* Ord Row Styling (Mockup Match) */
    .ord-row{
      display:flex;align-items:center;gap:11px;padding:10px 12px;border:1.5px solid var(--line);
      border-radius:14px;margin-bottom:9px;background:var(--card);transition:all .15s;
    }
    .ord-row:hover{border-color:var(--gold-deep);box-shadow:0 2px 8px rgba(14,50,84,.05)}
    .ord-row .pos{
      font-family:var(--font-display);font-weight:800;color:#B4BFC8;width:22px;text-align:center;flex:none;font-size:13px;
    }
    .ev-thumb{
      width:38px;height:38px;border-radius:10px;flex:none;display:grid;place-items:center;color:#fff;
      font-family:var(--font-display);font-weight:800;font-size:13px;background:linear-gradient(135deg,var(--navy-2),var(--navy));
    }
    .ev-name{font-weight:600;color:var(--ink);font-size:13.5px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:240px}
    .ev-sub{font-size:11.5px;color:var(--muted);font-weight:500;margin-top:1px}
    .ord-btns{display:flex;gap:5px;margin-left:auto;flex:none}

    /* Pool styling */
    .pool-list{max-height:280px;overflow-y:auto;padding-right:4px}
    .pool-list::-webkit-scrollbar{width:5px}
    .pool-list::-webkit-scrollbar-thumb{background:#D0D8E0;border-radius:99px}

    /* Table Sections */
    .tbl-sections{width:100%;border-collapse:collapse}
    .tbl-sections th{
      text-align:left;font-size:12px;font-weight:700;color:var(--muted);letter-spacing:.3px;
      padding:8px 12px;border-bottom:1px solid var(--line);
    }
    .tbl-sections td{
      padding:14px 12px;border-bottom:1px solid #F0F3F6;vertical-align:middle;font-size:13.5px;
    }
    .tbl-sections tr:last-child td{border-bottom:none}
    .sec-input{
      border:1.5px solid var(--line);border-radius:9px;padding:7px 11px;font-family:inherit;
      font-size:13px;color:var(--ink);width:100%;max-width:280px;outline:none;
    }
    .sec-input:focus{border-color:var(--navy)}

    /* Switch */
    .switch{position:relative;width:44px;height:24px;flex:none;display:inline-block}
    .switch input{position:absolute;opacity:0;width:100%;height:100%;cursor:pointer;margin:0;z-index:2}
    .switch .tr{position:absolute;inset:0;background:#E3E8EC;border:1px solid var(--line);border-radius:99px;transition:.2s;pointer-events:none}
    .switch .tr::after{content:"";position:absolute;top:2px;left:2px;width:18px;height:18px;border-radius:50%;background:#fff;box-shadow:0 1px 3px rgba(14,50,84,.25);transition:.2s}
    .switch input:checked + .tr{background:var(--navy);border-color:var(--navy)}
    .switch input:checked + .tr::after{transform:translateX(20px)}

    @media (max-width:1024px){
      .grid-2{grid-template-columns:1fr}
      .ev-name{max-width:180px}
    }
  </style>
</head>
<body>
  <div class="frame">

    <?php require __DIR__ . '/sidebar.php'; ?>

    <!-- ========== VIEW: TAMPILAN HOMEPAGE ========== -->
    <section class="view active" id="view-tampilan" aria-label="Tampilan Homepage">
      <div class="page-head">
        <div>
          <h1>Tampilan Homepage</h1>
          <p>Pilih dan urutkan event yang tampil di “Event Seru Untukmu” dan “Event Populer”, serta atur section homepage</p>
        </div>
        <div class="spacer" style="margin-left:auto"></div>
        <a href="<?= base_url('/') ?>" target="_blank" class="btn-primary" data-preview title="Buka Pratinjau Homepage">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"><path d="M2 12s3.5-6.5 10-6.5S22 12 22 12s-3.5 6.5-10 6.5S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg>
          Pratinjau Homepage
        </a>
      </div>

      <?php
        $flash = $flash ?? ($_SESSION['flash'] ?? []);
        $flashMsg = $flash['success'] ?? ($flash['danger'] ?? ($flash['error'] ?? null));
        $flashType = !empty($flash['success']) ? 'success' : 'error';
      ?>
      <?php if ($flashMsg): ?>
        <div class="alert-flash <?= $flashType ?>">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
          <?= e($flashMsg) ?>
        </div>
      <?php endif; ?>

      <!-- 1. PENGATURAN SECTION HOMEPAGE -->
      <div class="card">
        <div class="card-head">
          <div>
            <h2>Pengaturan Section Homepage</h2>
            <p>Atur visibilitas, judul kustom, urutan section, dan batas item yang tampil di beranda utama</p>
          </div>
        </div>

        <form method="POST" action="<?= base_url('dashboard/homepage/layout/update') ?>">
          <?= csrf_field() ?>
          <div style="overflow-x:auto">
            <table class="tbl-sections">
              <thead>
                <tr>
                  <th scope="col" style="width:50px">Tampil</th>
                  <th scope="col">Nama Section</th>
                  <th scope="col">Judul di Homepage</th>
                  <th scope="col" style="width:110px">Urutan</th>
                  <th scope="col" style="width:110px">Batas Item</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>
                    <label class="switch">
                      <input type="checkbox" name="sections[event_seru][is_visible]" value="1" <?= !empty($secSeru['is_visible']) ? 'checked' : '' ?> />
                      <span class="tr"></span>
                    </label>
                  </td>
                  <td><b>Event Seru Untukmu</b><div style="font-size:11.5px;color:var(--muted)">Carousel kartu event bergeser ke samping</div></td>
                  <td><input type="text" name="sections[event_seru][title]" class="sec-input" value="<?= e($secSeru['title']) ?>" /></td>
                  <td><input type="number" name="sections[event_seru][sort_order]" class="sec-input" style="max-width:80px" value="<?= (int)$secSeru['sort_order'] ?>" min="1" max="10" /></td>
                  <td><input type="number" name="sections[event_seru][item_limit]" class="sec-input" style="max-width:80px" value="<?= (int)$secSeru['item_limit'] ?>" min="1" max="20" /></td>
                </tr>
                <tr>
                  <td>
                    <label class="switch">
                      <input type="checkbox" name="sections[kategori][is_visible]" value="1" <?= !empty($secKat['is_visible']) ? 'checked' : '' ?> />
                      <span class="tr"></span>
                    </label>
                  </td>
                  <td><b>Kategori Event</b><div style="font-size:11.5px;color:var(--muted)">Ikon 8 kategori event populer</div></td>
                  <td><input type="text" name="sections[kategori][title]" class="sec-input" value="<?= e($secKat['title']) ?>" /></td>
                  <td><input type="number" name="sections[kategori][sort_order]" class="sec-input" style="max-width:80px" value="<?= (int)$secKat['sort_order'] ?>" min="1" max="10" /></td>
                  <td><input type="number" name="sections[kategori][item_limit]" class="sec-input" style="max-width:80px" value="<?= (int)$secKat['item_limit'] ?>" min="1" max="16" /></td>
                </tr>
                <tr>
                  <td>
                    <label class="switch">
                      <input type="checkbox" name="sections[event_populer][is_visible]" value="1" <?= !empty($secPop['is_visible']) ? 'checked' : '' ?> />
                      <span class="tr"></span>
                    </label>
                  </td>
                  <td><b>Event Populer</b><div style="font-size:11.5px;color:var(--muted)">Poster berganti otomatis & daftar vertikal teratas</div></td>
                  <td><input type="text" name="sections[event_populer][title]" class="sec-input" value="<?= e($secPop['title']) ?>" /></td>
                  <td><input type="number" name="sections[event_populer][sort_order]" class="sec-input" style="max-width:80px" value="<?= (int)$secPop['sort_order'] ?>" min="1" max="10" /></td>
                  <td><input type="number" name="sections[event_populer][item_limit]" class="sec-input" style="max-width:80px" value="<?= (int)$secPop['item_limit'] ?>" min="1" max="10" /></td>
                </tr>
                <tr>
                  <td>
                    <label class="switch">
                      <input type="checkbox" name="sections[jelajahi_kota][is_visible]" value="1" <?= !empty($secKota['is_visible']) ? 'checked' : '' ?> />
                      <span class="tr"></span>
                    </label>
                  </td>
                  <td><b>Jelajahi Event di Kotamu</b><div style="font-size:11.5px;color:var(--muted)">Grid kartu landmark kota-kota di Indonesia</div></td>
                  <td><input type="text" name="sections[jelajahi_kota][title]" class="sec-input" value="<?= e($secKota['title']) ?>" /></td>
                  <td><input type="number" name="sections[jelajahi_kota][sort_order]" class="sec-input" style="max-width:80px" value="<?= (int)$secKota['sort_order'] ?>" min="1" max="10" /></td>
                  <td><input type="number" name="sections[jelajahi_kota][item_limit]" class="sec-input" style="max-width:80px" value="<?= (int)$secKota['item_limit'] ?>" min="1" max="12" /></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div style="margin-top:16px">
            <button class="btn-navy" type="submit">Simpan Pengaturan Tata Letak</button>
          </div>
        </form>
      </div>

      <!-- 2. KURASI EVENT: SERU & POPULER -->
      <div class="note-demo">
        Gunakan tombol naik (↑), turun (↓), atau keluarkan (✕) untuk mengelola daftar event unggulan. Jika daftar dikosongkan, sistem akan otomatis memilih event terdekat yang belum berlangsung dari database.
      </div>

      <form method="POST" action="<?= base_url('dashboard/homepage/layout/featured') ?>" id="featuredForm">
        <?= csrf_field() ?>
        <div class="grid-2">
          
          <!-- Kolom 1: Event Seru Untukmu -->
          <div class="card">
            <div class="card-head">
              <div>
                <h2>Event Seru Untukmu</h2>
                <p id="seruCount"><?= count($seruIds) ?> event terpilih di carousel homepage</p>
              </div>
            </div>

            <div id="seruList">
              <?php if (empty($seruIds)): ?>
                <p style="color:var(--muted);font-size:13px;padding:12px 0;">Belum ada kurasi manual. Homepage akan menampilkan event terdekat otomatis.</p>
              <?php else: ?>
                <?php foreach ($seruIds as $idx => $eid): 
                  $ev = $eventsMap[$eid] ?? null;
                  if (!$ev) continue;
                  $initial = strtoupper(mb_substr($ev['title'], 0, 1));
                ?>
                  <div class="ord-row" data-id="<?= $eid ?>" data-list="seru">
                    <span class="pos"><?= $idx + 1 ?></span>
                    <div class="ev-thumb"><?= $initial ?></div>
                    <div style="flex:1;min-width:130px">
                      <div class="ev-name" title="<?= e($ev['title']) ?>"><?= e($ev['title']) ?></div>
                      <div class="ev-sub"><?= e($ev['city_name'] ?? 'Indonesia') ?> · <?= tanggal($ev['event_date']) ?></div>
                    </div>
                    <input type="hidden" name="seru_events[]" value="<?= $eid ?>" />
                    <span class="ord-btns">
                      <button class="mini-btn js-move-up" type="button" aria-label="Naik">↑</button>
                      <button class="mini-btn js-move-down" type="button" aria-label="Turun">↓</button>
                      <button class="mini-btn warn js-remove" type="button" aria-label="Hapus">✕</button>
                    </span>
                  </div>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>

            <!-- Pool Event Seru -->
            <div style="margin-top:16px;border-top:1px dashed var(--line);padding-top:12px">
              <p style="font-size:12.5px;color:var(--muted);margin:0 0 10px;font-weight:600">Event lain yang dapat ditambahkan:</p>
              <div class="pool-list" id="seruPool">
                <?php foreach ($events as $ev): 
                  if (in_array((int)$ev['id'], $seruIds, true)) continue;
                  $initial = strtoupper(mb_substr($ev['title'], 0, 1));
                ?>
                  <div class="ord-row" data-id="<?= $ev['id'] ?>" style="opacity:0.9">
                    <div class="ev-thumb" style="width:34px;height:34px;font-size:11.5px"><?= $initial ?></div>
                    <div style="flex:1;min-width:130px">
                      <div class="ev-name" style="font-size:13px"><?= e($ev['title']) ?></div>
                      <div class="ev-sub"><?= e($ev['city_name'] ?? 'Indonesia') ?> · <?= e($ev['category_name'] ?? 'Event') ?></div>
                    </div>
                    <button class="mini-btn js-add-seru" type="button" data-id="<?= $ev['id'] ?>" data-name="<?= e($ev['title']) ?>" data-city="<?= e($ev['city_name'] ?? 'Indonesia') ?>" data-date="<?= tanggal($ev['event_date']) ?>">+ Tambah</button>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>

          <!-- Kolom 2: Event Populer -->
          <div class="card">
            <div class="card-head">
              <div>
                <h2>Event Populer</h2>
                <p id="popCount"><?= count($popIds) ?> event terpilih di section Event Populer</p>
              </div>
            </div>

            <div id="popList">
              <?php if (empty($popIds)): ?>
                <p style="color:var(--muted);font-size:13px;padding:12px 0;">Belum ada kurasi manual. Homepage akan menampilkan event terlaris otomatis.</p>
              <?php else: ?>
                <?php foreach ($popIds as $idx => $eid): 
                  $ev = $eventsMap[$eid] ?? null;
                  if (!$ev) continue;
                  $initial = strtoupper(mb_substr($ev['title'], 0, 1));
                ?>
                  <div class="ord-row" data-id="<?= $eid ?>" data-list="pop">
                    <span class="pos"><?= $idx + 1 ?></span>
                    <div class="ev-thumb"><?= $initial ?></div>
                    <div style="flex:1;min-width:130px">
                      <div class="ev-name" title="<?= e($ev['title']) ?>"><?= e($ev['title']) ?></div>
                      <div class="ev-sub"><?= e($ev['city_name'] ?? 'Indonesia') ?> · <?= tanggal($ev['event_date']) ?></div>
                    </div>
                    <input type="hidden" name="pop_events[]" value="<?= $eid ?>" />
                    <span class="ord-btns">
                      <button class="mini-btn js-move-up" type="button" aria-label="Naik">↑</button>
                      <button class="mini-btn js-move-down" type="button" aria-label="Turun">↓</button>
                      <button class="mini-btn warn js-remove" type="button" aria-label="Hapus">✕</button>
                    </span>
                  </div>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>

            <!-- Pool Event Populer -->
            <div style="margin-top:16px;border-top:1px dashed var(--line);padding-top:12px">
              <p style="font-size:12.5px;color:var(--muted);margin:0 0 10px;font-weight:600">Event lain yang dapat ditambahkan:</p>
              <div class="pool-list" id="popPool">
                <?php foreach ($events as $ev): 
                  if (in_array((int)$ev['id'], $popIds, true)) continue;
                  $initial = strtoupper(mb_substr($ev['title'], 0, 1));
                ?>
                  <div class="ord-row" data-id="<?= $ev['id'] ?>" style="opacity:0.9">
                    <div class="ev-thumb" style="width:34px;height:34px;font-size:11.5px"><?= $initial ?></div>
                    <div style="flex:1;min-width:130px">
                      <div class="ev-name" style="font-size:13px"><?= e($ev['title']) ?></div>
                      <div class="ev-sub"><?= e($ev['city_name'] ?? 'Indonesia') ?> · <?= e($ev['category_name'] ?? 'Event') ?></div>
                    </div>
                    <button class="mini-btn js-add-pop" type="button" data-id="<?= $ev['id'] ?>" data-name="<?= e($ev['title']) ?>" data-city="<?= e($ev['city_name'] ?? 'Indonesia') ?>" data-date="<?= tanggal($ev['event_date']) ?>">+ Tambah</button>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>

        </div>

        <div style="margin-top:20px">
          <button class="btn-navy" type="submit" style="padding:11px 26px;font-size:14px">Simpan Kurasi Event Homepage</button>
        </div>
      </form>

    </section>

    <?php $shellFooter = true; require __DIR__ . '/sidebar.php'; ?>

  <script>
    (function(){
      // Re-index row positions and hidden inputs
      function updatePositions(container, inputName){
        var rows = container.querySelectorAll('.ord-row');
        rows.forEach(function(row, idx){
          var posSpan = row.querySelector('.pos');
          if (posSpan) posSpan.textContent = idx + 1;
        });
      }

      // Delegate event listeners for move up, down, remove
      document.addEventListener('click', function(e){
        var upBtn = e.target.closest('.js-move-up');
        var downBtn = e.target.closest('.js-move-down');
        var rmBtn = e.target.closest('.js-remove');

        if (upBtn) {
          var row = upBtn.closest('.ord-row');
          var prev = row.previousElementSibling;
          if (prev && prev.classList.contains('ord-row')) {
            row.parentNode.insertBefore(row, prev);
            updatePositions(row.parentNode);
          }
        } else if (downBtn) {
          var row = downBtn.closest('.ord-row');
          var next = row.nextElementSibling;
          if (next && next.classList.contains('ord-row')) {
            row.parentNode.insertBefore(next, row);
            updatePositions(row.parentNode);
          }
        } else if (rmBtn) {
          var row = rmBtn.closest('.ord-row');
          var parent = row.parentNode;
          row.remove();
          updatePositions(parent);
        }
      });

      // Add to Seru list
      document.addEventListener('click', function(e){
        var addSeru = e.target.closest('.js-add-seru');
        if (addSeru) {
          var id = addSeru.getAttribute('data-id');
          var name = addSeru.getAttribute('data-name');
          var city = addSeru.getAttribute('data-city');
          var date = addSeru.getAttribute('data-date');
          var container = document.getElementById('seruList');
          var count = container.querySelectorAll('.ord-row').length + 1;
          var initial = name.charAt(0).toUpperCase();

          var html = '<div class="ord-row" data-id="' + id + '" data-list="seru">' +
            '<span class="pos">' + count + '</span>' +
            '<div class="ev-thumb">' + initial + '</div>' +
            '<div style="flex:1;min-width:130px">' +
              '<div class="ev-name" title="' + name + '">' + name + '</div>' +
              '<div class="ev-sub">' + city + ' · ' + date + '</div>' +
            '</div>' +
            '<input type="hidden" name="seru_events[]" value="' + id + '" />' +
            '<span class="ord-btns">' +
              '<button class="mini-btn js-move-up" type="button" aria-label="Naik">↑</button>' +
              '<button class="mini-btn js-move-down" type="button" aria-label="Turun">↓</button>' +
              '<button class="mini-btn warn js-remove" type="button" aria-label="Hapus">✕</button>' +
            '</span>' +
          '</div>';

          container.insertAdjacentHTML('beforeend', html);
          addSeru.closest('.ord-row').remove();
          updatePositions(container);
          document.getElementById('seruCount').textContent = container.querySelectorAll('.ord-row').length + ' event terpilih di carousel homepage';
        }

        var addPop = e.target.closest('.js-add-pop');
        if (addPop) {
          var id = addPop.getAttribute('data-id');
          var name = addPop.getAttribute('data-name');
          var city = addPop.getAttribute('data-city');
          var date = addPop.getAttribute('data-date');
          var container = document.getElementById('popList');
          var count = container.querySelectorAll('.ord-row').length + 1;
          var initial = name.charAt(0).toUpperCase();

          var html = '<div class="ord-row" data-id="' + id + '" data-list="pop">' +
            '<span class="pos">' + count + '</span>' +
            '<div class="ev-thumb">' + initial + '</div>' +
            '<div style="flex:1;min-width:130px">' +
              '<div class="ev-name" title="' + name + '">' + name + '</div>' +
              '<div class="ev-sub">' + city + ' · ' + date + '</div>' +
            '</div>' +
            '<input type="hidden" name="pop_events[]" value="' + id + '" />' +
            '<span class="ord-btns">' +
              '<button class="mini-btn js-move-up" type="button" aria-label="Naik">↑</button>' +
              '<button class="mini-btn js-move-down" type="button" aria-label="Turun">↓</button>' +
              '<button class="mini-btn warn js-remove" type="button" aria-label="Hapus">✕</button>' +
            '</span>' +
          '</div>';

          container.insertAdjacentHTML('beforeend', html);
          addPop.closest('.ord-row').remove();
          updatePositions(container);
          document.getElementById('popCount').textContent = container.querySelectorAll('.ord-row').length + ' event terpilih di section Event Populer';
        }
      });
    })();
  </script>
</body>
</html>
