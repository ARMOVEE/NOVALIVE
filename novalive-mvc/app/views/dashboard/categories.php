<?php
/**
 * Halaman Manajemen Kategori Event — Gaya Quixotic
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
  <title><?= e($pageTitle ?? 'NOVA LIVE — Kategori Event') ?></title>
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
      background:var(--panel);color:var(--ink);
      min-height:100vh;margin:0;padding:0;
    }
    a{color:inherit;text-decoration:none}
    button{font-family:inherit;background:none;border:none;padding:0;cursor:pointer;color:inherit}

    /* ---------- Outer card shell ---------- */
    .frame{
      width:100%;max-width:none;margin:0;background:var(--panel);
      border-radius:0;box-shadow:none;
      padding:16px 20px 24px;border:none;
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

    /* ---------- View scaffolding ---------- */
    .page-head{display:flex;align-items:flex-start;gap:14px;flex-wrap:wrap;margin:2px 2px 18px}
    .page-head h1{font-size:24px;margin:0;color:var(--ink);letter-spacing:-.2px}
    .page-head p{margin:4px 0 0;font-size:13px;color:var(--muted)}
    .page-head .spacer{flex:1}
    .btn-gold{
      display:inline-flex;align-items:center;gap:8px;background:var(--gold);color:var(--navy);
      font-weight:700;font-size:13.5px;border-radius:999px;padding:10px 20px;min-height:42px;
      box-shadow:0 10px 22px rgba(212,180,117,.32);transition:background .15s,transform .1s;
      font-family:'Plus Jakarta Sans',sans-serif;cursor:pointer;
    }
    .btn-gold:hover{background:#F3E2B8}
    .btn-gold:active{transform:scale(.98)}

    /* ---------- Note Demo ---------- */
    .note-demo{
      background:var(--cream);border:1px solid #E5D6B6;color:#6B5319;border-radius:14px;
      padding:11px 16px;font-size:13px;line-height:1.5;margin-bottom:18px;display:flex;align-items:center;gap:10px;
    }
    .note-demo svg{flex:none;color:var(--gold-dark)}

    /* ---------- Search & Filters ---------- */
    .filter-bar{display:flex;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:18px}
    .mini-search{
      margin-left:auto;display:flex;align-items:center;gap:7px;background:#F1F3F4;border-radius:999px;padding:0 13px;min-height:38px;color:#8A97A3;
    }
    .mini-search input{border:none;outline:none;background:transparent;font-family:inherit;font-size:13px;color:var(--ink);width:220px;min-height:36px}

    /* ---------- Kategori Cards Grid ---------- */
    .cat-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(250px,1fr));gap:16px}
    .cat-card{background:var(--card);border-radius:var(--radius);box-shadow:var(--shadow);overflow:hidden;display:flex;flex-direction:column}
    .cat-media{height:96px;display:grid;place-items:center;color:#fff;position:relative;overflow:hidden}
    .cat-media svg{width:38px;height:38px}
    .cat-body{padding:16px 18px 18px;display:flex;flex-direction:column;flex:1}
    .cat-body h3{margin:0 0 4px;font-size:16px;font-weight:700;color:var(--ink)}
    .cat-body p{margin:0;font-size:12.5px;color:var(--muted);line-height:1.5}
    .card-actions{display:flex;gap:7px;margin-top:14px}
    .mini-btn{
      border:1.5px solid var(--line);background:var(--card);color:#5B6B78;border-radius:9px;
      padding:6px 12px;font-size:12.5px;font-weight:600;min-height:32px;transition:all .15s;white-space:nowrap;
      cursor:pointer;display:inline-flex;align-items:center;
    }
    .mini-btn:hover{color:var(--navy);border-color:var(--gold-deep);background:#FBF8F0}
    .mini-btn.warn:hover{color:var(--red);border-color:var(--red);background:var(--red-bg)}

    /* ---------- Modal & Overlay ---------- */
    .overlay{
      position:fixed;inset:0;background:rgba(14,50,84,.45);backdrop-filter:blur(3px);
      display:none;place-items:center;z-index:100;padding:18px;overflow-y:auto;
    }
    .overlay.open{display:grid}
    .modal{background:#fff;border-radius:24px;max-width:480px;width:100%;padding:26px;box-shadow:0 30px 70px rgba(14,50,84,.3);position:relative;margin:auto}
    .modal h2{margin:0 0 4px;font-size:19px;color:var(--navy);padding-right:34px}
    .modal > p{margin:0 0 18px;font-size:13px;color:var(--muted);line-height:1.55}
    .modal-x{
      position:absolute;top:14px;right:14px;width:34px;height:34px;border-radius:50%;
      display:grid;place-items:center;color:#8A97A3;font-size:15px;cursor:pointer;
    }
    .modal-x:hover{background:#EEF3F7;color:var(--navy)}
    .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:13px}
    .field{margin-bottom:0}
    .field.full{grid-column:1/-1}
    .field label{display:block;font-size:12.5px;font-weight:600;margin-bottom:6px;color:var(--ink)}
    .field input, .field select, .field textarea{
      width:100%;border:1.5px solid var(--line);border-radius:12px;padding:10px 13px;font-size:14px;
      font-family:inherit;color:var(--ink);background:#FBFCFD;min-height:44px;outline:none;
    }
    .field textarea{resize:vertical;min-height:80px}
    .field input:focus, .field select:focus, .field textarea:focus{border-color:var(--blue)}
    .field input[type="color"].color-inp{padding:4px 8px;height:44px;cursor:pointer}
    .modal-actions{display:flex;gap:10px;margin-top:18px;flex-wrap:wrap;justify-content:flex-end}
    .btn-navy{
      background:var(--navy);color:#fff;border-radius:12px;padding:10px 18px;font-size:13.5px;
      font-weight:700;font-family:'Plus Jakarta Sans',sans-serif;cursor:pointer;transition:background .15s;
    }
    .btn-navy:hover{background:var(--navy-2)}
    .btn-ghost{
      background:#fff;border:1.5px solid var(--line);color:#5B6B78;border-radius:12px;
      padding:10px 18px;font-size:13.5px;font-weight:600;cursor:pointer;transition:all .15s;
    }
    .btn-ghost:hover{background:#EEF3F7;color:var(--navy)}
    .btn-danger{
      background:var(--red);color:#fff;border-radius:12px;padding:10px 18px;font-size:13.5px;
      font-weight:700;font-family:'Plus Jakarta Sans',sans-serif;cursor:pointer;transition:background .15s;
    }
    .btn-danger:hover{background:#B91C1C}
    .btn-danger:disabled{opacity:0.5;cursor:not-allowed}

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
      body{padding:0}
      .frame{padding:10px 10px 16px;border-radius:0}
      .topbar{padding:8px 8px 8px 14px}
      .greet{margin:18px 2px 14px}
      .mini-search{margin-left:0;width:100%}
      .mini-search input{width:100%}
      .cat-grid{grid-template-columns:1fr}
      .form-grid{grid-template-columns:1fr}
    }
  </style>
</head>
<body>
  <div class="frame">

    <?php require __DIR__ . '/sidebar.php'; ?>

    <!-- ========== VIEW: KATEGORI ========== -->
    <section class="view active" id="view-kategori" aria-label="Kategori Event">
      <div class="page-head">
        <div>
          <h1>Kategori Event</h1>
          <p>Kategori yang tampil di menu Kategori homepage dan opsi pilihan saat membuat event</p>
        </div>
        <div class="spacer"></div>
        <button class="btn-gold" type="button" id="catAddBtn">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
          Tambah Kategori
        </button>
      </div>

      <div class="note-demo">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
        <span>Kategori yang masih dipakai oleh event aktif <b>tidak dapat dihapus</b> demi menjaga keutuhan data. Pindahkan atau hapus event terkait terlebih dahulu sebelum menghapus kategori.</span>
      </div>

      <div class="filter-bar">
        <span style="font-size:13.5px;color:var(--muted);font-weight:600">Total: <b><?= count($categories ?? []) ?> Kategori</b></span>
        <div class="mini-search">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/></svg>
          <input type="search" id="catSearch" placeholder="Cari nama atau deskripsi kategori…" aria-label="Cari kategori" />
        </div>
      </div>

      <!-- Kategori Grid -->
      <div class="cat-grid" id="catGrid">
        <?php if (empty($categories)): ?>
          <p style="grid-column:1/-1;text-align:center;color:var(--muted);padding:40px 0;">Belum ada data kategori yang terdaftar.</p>
        <?php else: ?>
          <?php foreach ($categories as $c):
            $count = (int)($c['event_count'] ?? 0);
            $color = !empty($c['color']) ? $c['color'] : '#23649A';
            $icon = !empty($c['icon']) ? $c['icon'] : 'musik';
          ?>
            <div class="cat-card" data-name="<?= strtolower(e($c['name'])) ?>" data-desc="<?= strtolower(e($c['description'] ?? '')) ?>">
              <div class="cat-media" style="background:<?= e($color) ?>">
                <!-- Icon representation -->
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <?php if ($icon === 'musik'): ?>
                    <path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/>
                  <?php elseif ($icon === 'festival'): ?>
                    <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V4s-1 1-4 1-5-2-8-2-4 1-4 1z"/><path d="M4 22v-7"/>
                  <?php elseif ($icon === 'seni'): ?>
                    <circle cx="13.5" cy="6.5" r=".5" fill="currentColor"/><circle cx="17.5" cy="10.5" r=".5" fill="currentColor"/><circle cx="8.5" cy="7.5" r=".5" fill="currentColor"/><circle cx="6.5" cy="12.5" r=".5" fill="currentColor"/><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.926 0 1.648-.746 1.648-1.688 0-.437-.18-.835-.437-1.125-.29-.289-.438-.652-.438-1.125a1.64 1.64 0 0 1 1.668-1.668h1.996c3.051 0 5.563-2.512 5.563-5.563C22 6.5 17.5 2 12 2z"/>
                  <?php elseif ($icon === 'olahraga'): ?>
                    <circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/>
                  <?php elseif ($icon === 'bisnis'): ?>
                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                  <?php elseif ($icon === 'workshop'): ?>
                    <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                  <?php elseif ($icon === 'kuliner'): ?>
                    <path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/>
                  <?php elseif ($icon === 'komedi'): ?>
                    <circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/>
                  <?php else: ?>
                    <path d="M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                  <?php endif; ?>
                </svg>
              </div>
              <div class="cat-body">
                <h3><?= e($c['name']) ?></h3>
                <p><?= e($c['description'] ?? '—') ?></p>
                <p style="margin-top:8px"><b style="color:var(--ink)"><?= $count ?> event</b> memakai kategori ini</p>
                <div class="card-actions">
                  <button class="mini-btn js-cat-edit" type="button"
                    data-id="<?= (int)$c['id'] ?>"
                    data-name="<?= e($c['name']) ?>"
                    data-desc="<?= e($c['description'] ?? '') ?>"
                    data-color="<?= e($color) ?>"
                    data-icon="<?= e($icon) ?>">
                    Edit
                  </button>
                  <button class="mini-btn warn js-cat-del" type="button"
                    data-id="<?= (int)$c['id'] ?>"
                    data-name="<?= e($c['name']) ?>"
                    data-count="<?= $count ?>">
                    Hapus
                  </button>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
      <p id="catNoMatch" style="text-align:center;color:var(--muted);padding:30px 0;display:none;">Tidak ada kategori yang cocok dengan pencarian.</p>
    </section>

    <!-- ======= MODAL: TAMBAH / EDIT KATEGORI ======= -->
    <div class="overlay" id="catOverlay">
      <div class="modal" role="dialog" aria-modal="true" aria-labelledby="catModalTitle">
        <button class="modal-x" type="button" id="catModalClose" aria-label="Tutup">✕</button>
        <h2 id="catModalTitle">Tambah Kategori</h2>
        <p>Kategori tampil di menu Kategori homepage dan pilihan saat membuat event.</p>
        <form id="catForm" method="POST" action="<?= base_url('dashboard/categories/store') ?>">
          <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>" />
          <div class="form-grid">
            <div class="field full">
              <label for="catName">Nama Kategori</label>
              <input id="catName" name="name" type="text" placeholder="cth. Jazz & Blues" required />
            </div>
            <div class="field full">
              <label for="catDesc">Deskripsi</label>
              <textarea id="catDesc" name="description" rows="2" placeholder="Deskripsi singkat kategori…"></textarea>
            </div>
            <div class="field">
              <label for="catIcon">Ikon</label>
              <select id="catIcon" name="icon">
                <option value="musik">Musik</option>
                <option value="festival">Festival</option>
                <option value="seni">Seni & Budaya</option>
                <option value="olahraga">Olahraga</option>
                <option value="bisnis">Bisnis</option>
                <option value="workshop">Workshop</option>
                <option value="kuliner">Kuliner</option>
                <option value="komedi">Komedi</option>
              </select>
            </div>
            <div class="field">
              <label for="catColor">Warna Kategori</label>
              <input id="catColor" name="color" type="color" class="color-inp" value="#23649A" />
            </div>
          </div>
          <div class="modal-actions">
            <button class="btn-ghost" type="button" id="catCancelBtn">Batal</button>
            <button class="btn-navy" type="submit" id="catSubmitBtn">Simpan Kategori</button>
          </div>
        </form>
      </div>
    </div>

    <!-- ======= MODAL: KONFIRMASI HAPUS ======= -->
    <div class="overlay" id="delCatOverlay">
      <div class="modal" role="alertdialog" aria-modal="true" aria-labelledby="delModalTitle" style="max-width:440px">
        <button class="modal-x" type="button" id="delModalClose" aria-label="Tutup">✕</button>
        <h2 id="delModalTitle">Hapus Kategori?</h2>
        <p id="delModalMsg" style="margin-bottom:18px">—</p>
        <form id="delCatForm" method="POST" action="">
          <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>" />
          <div class="modal-actions">
            <button class="btn-ghost" type="button" id="delCancelBtn">Batal</button>
            <button class="btn-danger" type="submit" id="delConfirmBtn">Ya, Hapus</button>
          </div>
        </form>
      </div>
    </div>

    <?php $shellFooter = true; require __DIR__ . '/sidebar.php'; ?>

  <!-- Script Khusus Kategori: Modal Tambah/Edit, Konfirmasi Hapus & Search -->
  <script>
    (function(){
      var catOverlay = document.getElementById("catOverlay");
      var catForm = document.getElementById("catForm");
      var catModalTitle = document.getElementById("catModalTitle");
      var catName = document.getElementById("catName");
      var catDesc = document.getElementById("catDesc");
      var catIcon = document.getElementById("catIcon");
      var catColor = document.getElementById("catColor");
      var catAddBtn = document.getElementById("catAddBtn");
      var catModalClose = document.getElementById("catModalClose");
      var catCancelBtn = document.getElementById("catCancelBtn");

      var delOverlay = document.getElementById("delCatOverlay");
      var delForm = document.getElementById("delCatForm");
      var delMsg = document.getElementById("delModalMsg");
      var delConfirmBtn = document.getElementById("delConfirmBtn");
      var delModalClose = document.getElementById("delModalClose");
      var delCancelBtn = document.getElementById("delCancelBtn");

      var storeUrl = "<?= base_url('dashboard/categories/store') ?>";
      var updateUrlBase = "<?= base_url('dashboard/categories/update/') ?>";
      var deleteUrlBase = "<?= base_url('dashboard/categories/delete/') ?>";

      // Buka modal Tambah Kategori
      if (catAddBtn) {
        catAddBtn.addEventListener("click", function(){
          catModalTitle.textContent = "Tambah Kategori";
          catForm.action = storeUrl;
          catName.value = "";
          catDesc.value = "";
          catIcon.value = "musik";
          catColor.value = "#23649A";
          catOverlay.classList.add("open");
        });
      }

      // Buka modal Edit Kategori
      document.querySelectorAll(".js-cat-edit").forEach(function(b){
        b.addEventListener("click", function(){
          var id = b.getAttribute("data-id");
          var name = b.getAttribute("data-name");
          var desc = b.getAttribute("data-desc");
          var color = b.getAttribute("data-color");
          var icon = b.getAttribute("data-icon");

          catModalTitle.textContent = "Edit Kategori";
          catForm.action = updateUrlBase + id;
          catName.value = name || "";
          catDesc.value = desc || "";
          catColor.value = color || "#23649A";
          if (catIcon) catIcon.value = icon || "musik";
          catOverlay.classList.add("open");
        });
      });

      function closeCatModal(){
        catOverlay.classList.remove("open");
      }
      if (catModalClose) catModalClose.addEventListener("click", closeCatModal);
      if (catCancelBtn) catCancelBtn.addEventListener("click", closeCatModal);

      // Konfirmasi Hapus
      document.querySelectorAll(".js-cat-del").forEach(function(b){
        b.addEventListener("click", function(){
          var id = b.getAttribute("data-id");
          var name = b.getAttribute("data-name");
          var count = parseInt(b.getAttribute("data-count"), 10) || 0;

          if (count > 0) {
            delMsg.innerHTML = 'Kategori <b>“' + name + '”</b> masih dipakai oleh <b>' + count + ' event</b>.<br><br><span style="color:var(--red);font-weight:600;">Tidak dapat menghapus kategori yang masih memiliki event terkait.</span> Pindahkan atau hapus event tersebut terlebih dahulu.';
            delConfirmBtn.style.display = "none";
          } else {
            delMsg.innerHTML = 'Apakah kamu yakin ingin menghapus kategori <b>“' + name + '”</b>? Tindakan ini tidak dapat dibatalkan.';
            delConfirmBtn.style.display = "inline-flex";
            delForm.action = deleteUrlBase + id;
          }
          delOverlay.classList.add("open");
        });
      });

      function closeDelModal(){
        delOverlay.classList.remove("open");
      }
      if (delModalClose) delModalClose.addEventListener("click", closeDelModal);
      if (delCancelBtn) delCancelBtn.addEventListener("click", closeDelModal);

      // Live search
      var searchInput = document.getElementById("catSearch");
      var cards = document.querySelectorAll("#catGrid .cat-card");
      var noMatch = document.getElementById("catNoMatch");
      if (searchInput) {
        searchInput.addEventListener("input", function(){
          var q = searchInput.value.trim().toLowerCase();
          var visible = 0;
          cards.forEach(function(card){
            var name = card.getAttribute("data-name") || "";
            var desc = card.getAttribute("data-desc") || "";
            var show = !q || name.indexOf(q) > -1 || desc.indexOf(q) > -1;
            card.style.display = show ? "" : "none";
            if (show) visible++;
          });
          if (noMatch) {
            noMatch.style.display = visible === 0 ? "block" : "none";
          }
        });
      }
    })();
  </script>
</body>
</html>