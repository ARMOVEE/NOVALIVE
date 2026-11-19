<?php
/**
 * Halaman Manajemen Kota & Landmark — Gaya Quixotic
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
  <title><?= e($pageTitle ?? 'NOVA LIVE — Kota & Landmark') ?></title>
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

    /* ---------- Kota Cards Grid ---------- */
    .cat-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(250px,1fr));gap:16px}
    .cat-card{background:var(--card);border-radius:var(--radius);box-shadow:var(--shadow);overflow:hidden;display:flex;flex-direction:column}
    .cat-media{height:96px;display:grid;place-items:center;color:#fff;position:relative;overflow:hidden}
    .cat-media .lm{
      font-family:'Plus Jakarta Sans',sans-serif;font-weight:800;font-size:16px;text-align:center;
      padding:0 14px;text-shadow:0 2px 8px rgba(0,0,0,.35);letter-spacing:.3px;
    }
    .cat-media img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover}
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
    .field input{
      width:100%;border:1.5px solid var(--line);border-radius:12px;padding:10px 13px;font-size:14px;
      font-family:inherit;color:var(--ink);background:#FBFCFD;min-height:44px;outline:none;
    }
    .field input:focus{border-color:var(--blue)}
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

    <!-- ========== VIEW: KOTA ========== -->
    <section class="view active" id="view-kota" aria-label="Kota & Landmark">
      <div class="page-head">
        <div>
          <h1>Kota &amp; Landmark</h1>
          <p>Kota-kota di section “Jelajahi Event di Kotamu” beserta landmark khasnya</p>
        </div>
        <div class="spacer"></div>
        <button class="btn-gold" type="button" id="cityAddBtn">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
          Tambah Kota
        </button>
      </div>

      <div class="note-demo">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
        <span>Kota yang masih memiliki event aktif <b>tidak dapat dihapus</b>. Hapus atau pindahkan event aktif dari kota tersebut terlebih dahulu sebelum menghapusnya.</span>
      </div>

      <div class="filter-bar">
        <span style="font-size:13.5px;color:var(--muted);font-weight:600">Total: <b><?= count($cities ?? []) ?> Kota</b></span>
        <div class="mini-search">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/></svg>
          <input type="search" id="citySearch" placeholder="Cari nama kota, landmark, atau alias…" aria-label="Cari kota" />
        </div>
      </div>

      <!-- Kota Grid -->
      <div class="cat-grid" id="cityGrid">
        <?php if (empty($cities)): ?>
          <p style="grid-column:1/-1;text-align:center;color:var(--muted);padding:40px 0;">Belum ada data kota yang terdaftar.</p>
        <?php else: ?>
          <?php foreach ($cities as $c):
            $count = (int)($c['event_count'] ?? 0);
            $accent = !empty($c['accent']) ? $c['accent'] : (!empty($c['accent_color']) ? $c['accent_color'] : '#23649A');
            $landmark = $c['landmark'] ?? $c['name'];
          ?>
            <div class="cat-card"
              data-name="<?= strtolower(e($c['name'])) ?>"
              data-landmark="<?= strtolower(e($c['landmark'] ?? '')) ?>"
              data-alias="<?= strtolower(e($c['search_alias'] ?? '')) ?>">
              <div class="cat-media" style="background:<?= e($accent) ?>">
                <?php if (!empty($c['image'])): ?>
                  <img src="<?= base_url(ltrim($c['image'], '/')) ?>" alt="Foto landmark <?= e($c['name']) ?>" />
                <?php else: ?>
                  <span class="lm"><?= e($landmark) ?></span>
                <?php endif; ?>
              </div>
              <div class="cat-body">
                <h3><?= e($c['name']) ?></h3>
                <p>Landmark: <b><?= e($c['landmark'] ?? '—') ?></b></p>
                <?php if (!empty($c['search_alias'])): ?>
                  <p style="margin-top:2px;font-size:11.5px;color:var(--muted)">Alias: <?= e($c['search_alias']) ?></p>
                <?php endif; ?>
                <p style="margin-top:8px"><b style="color:var(--ink)"><?= $count ?> event</b> di kota ini</p>
                <div class="card-actions">
                  <button class="mini-btn js-city-edit" type="button"
                    data-id="<?= (int)$c['id'] ?>"
                    data-name="<?= e($c['name']) ?>"
                    data-slug="<?= e($c['slug'] ?? '') ?>"
                    data-landmark="<?= e($c['landmark'] ?? '') ?>"
                    data-alias="<?= e($c['search_alias'] ?? '') ?>"
                    data-accent="<?= e($accent) ?>">
                    Edit
                  </button>
                  <button class="mini-btn warn js-city-del" type="button"
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
      <p id="cityNoMatch" style="text-align:center;color:var(--muted);padding:30px 0;display:none;">Tidak ada kota yang cocok dengan pencarian.</p>
    </section>

    <!-- ======= MODAL: TAMBAH / EDIT KOTA ======= -->
    <div class="overlay" id="cityOverlay">
      <div class="modal" role="dialog" aria-modal="true" aria-labelledby="cityModalTitle">
        <button class="modal-x" type="button" id="cityModalClose" aria-label="Tutup">✕</button>
        <h2 id="cityModalTitle">Tambah Kota</h2>
        <p>Kota tampil di section “Jelajahi Event di Kotamu” di homepage.</p>
        <form id="cityForm" method="POST" action="<?= base_url('dashboard/cities/store') ?>">
          <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>" />
          <div class="form-grid">
            <div class="field">
              <label for="cityName">Nama Kota</label>
              <input id="cityName" name="name" type="text" placeholder="cth. Semarang" required />
            </div>
            <div class="field">
              <label for="citySlug">Slug URL (opsional)</label>
              <input id="citySlug" name="slug" type="text" placeholder="cth. semarang" />
            </div>
            <div class="field">
              <label for="cityLandmark">Nama Landmark</label>
              <input id="cityLandmark" name="landmark" type="text" placeholder="cth. Lawang Sewu" />
            </div>
            <div class="field">
              <label for="cityAccent">Warna Aksen</label>
              <input id="cityAccent" name="accent" type="color" class="color-inp" value="#23649A" />
            </div>
            <div class="field full">
              <label for="cityAlias">Search Alias (kata kunci pencarian)</label>
              <input id="cityAlias" name="search_alias" type="text" placeholder="cth. Jateng, Kota Atlas" />
            </div>
          </div>
          <div class="modal-actions">
            <button class="btn-ghost" type="button" id="cityCancelBtn">Batal</button>
            <button class="btn-navy" type="submit" id="citySubmitBtn">Simpan Kota</button>
          </div>
        </form>
      </div>
    </div>

    <!-- ======= MODAL: KONFIRMASI HAPUS ======= -->
    <div class="overlay" id="delCityOverlay">
      <div class="modal" role="alertdialog" aria-modal="true" aria-labelledby="delCityTitle" style="max-width:440px">
        <button class="modal-x" type="button" id="delCityClose" aria-label="Tutup">✕</button>
        <h2 id="delCityTitle">Hapus Kota?</h2>
        <p id="delCityMsg" style="margin-bottom:18px">—</p>
        <form id="delCityForm" method="POST" action="">
          <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>" />
          <div class="modal-actions">
            <button class="btn-ghost" type="button" id="delCityCancelBtn">Batal</button>
            <button class="btn-danger" type="submit" id="delCityConfirmBtn">Ya, Hapus</button>
          </div>
        </form>
      </div>
    </div>

    <?php $shellFooter = true; require __DIR__ . '/sidebar.php'; ?>

  <!-- Script Khusus Kota: Modal Tambah/Edit, Konfirmasi Hapus & Search -->
  <script>
    (function(){
      var cityOverlay = document.getElementById("cityOverlay");
      var cityForm = document.getElementById("cityForm");
      var cityModalTitle = document.getElementById("cityModalTitle");
      var cityName = document.getElementById("cityName");
      var citySlug = document.getElementById("citySlug");
      var cityLandmark = document.getElementById("cityLandmark");
      var cityAccent = document.getElementById("cityAccent");
      var cityAlias = document.getElementById("cityAlias");
      var cityAddBtn = document.getElementById("cityAddBtn");
      var cityModalClose = document.getElementById("cityModalClose");
      var cityCancelBtn = document.getElementById("cityCancelBtn");

      var delOverlay = document.getElementById("delCityOverlay");
      var delForm = document.getElementById("delCityForm");
      var delMsg = document.getElementById("delCityMsg");
      var delConfirmBtn = document.getElementById("delCityConfirmBtn");
      var delModalClose = document.getElementById("delCityClose");
      var delCancelBtn = document.getElementById("delCityCancelBtn");

      var storeUrl = "<?= base_url('dashboard/cities/store') ?>";
      var updateUrlBase = "<?= base_url('dashboard/cities/update/') ?>";
      var deleteUrlBase = "<?= base_url('dashboard/cities/delete/') ?>";

      // Buka modal Tambah Kota
      if (cityAddBtn) {
        cityAddBtn.addEventListener("click", function(){
          cityModalTitle.textContent = "Tambah Kota";
          cityForm.action = storeUrl;
          cityName.value = "";
          citySlug.value = "";
          cityLandmark.value = "";
          cityAccent.value = "#23649A";
          cityAlias.value = "";
          cityOverlay.classList.add("open");
        });
      }

      // Buka modal Edit Kota
      document.querySelectorAll(".js-city-edit").forEach(function(b){
        b.addEventListener("click", function(){
          var id = b.getAttribute("data-id");
          var name = b.getAttribute("data-name");
          var slug = b.getAttribute("data-slug");
          var landmark = b.getAttribute("data-landmark");
          var alias = b.getAttribute("data-alias");
          var accent = b.getAttribute("data-accent");

          cityModalTitle.textContent = "Edit Kota";
          cityForm.action = updateUrlBase + id;
          cityName.value = name || "";
          citySlug.value = slug || "";
          cityLandmark.value = landmark || "";
          cityAccent.value = accent || "#23649A";
          cityAlias.value = alias || "";
          cityOverlay.classList.add("open");
        });
      });

      function closeCityModal(){
        cityOverlay.classList.remove("open");
      }
      if (cityModalClose) cityModalClose.addEventListener("click", closeCityModal);
      if (cityCancelBtn) cityCancelBtn.addEventListener("click", closeCityModal);

      // Konfirmasi Hapus
      document.querySelectorAll(".js-city-del").forEach(function(b){
        b.addEventListener("click", function(){
          var id = b.getAttribute("data-id");
          var name = b.getAttribute("data-name");
          var count = parseInt(b.getAttribute("data-count"), 10) || 0;

          if (count > 0) {
            delMsg.innerHTML = 'Kota <b>“' + name + '”</b> masih dipakai oleh <b>' + count + ' event aktif</b>.<br><br><span style="color:var(--red);font-weight:600;">Tidak dapat menghapus kota yang masih memiliki event aktif.</span> Pindahkan atau hapus event tersebut terlebih dahulu.';
            delConfirmBtn.style.display = "none";
          } else {
            delMsg.innerHTML = 'Apakah kamu yakin ingin menghapus kota <b>“' + name + '”</b>? Tindakan ini tidak dapat dibatalkan.';
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
      var searchInput = document.getElementById("citySearch");
      var cards = document.querySelectorAll("#cityGrid .cat-card");
      var noMatch = document.getElementById("cityNoMatch");
      if (searchInput) {
        searchInput.addEventListener("input", function(){
          var q = searchInput.value.trim().toLowerCase();
          var visible = 0;
          cards.forEach(function(card){
            var name = card.getAttribute("data-name") || "";
            var landmark = card.getAttribute("data-landmark") || "";
            var alias = card.getAttribute("data-alias") || "";
            var show = !q || name.indexOf(q) > -1 || landmark.indexOf(q) > -1 || alias.indexOf(q) > -1;
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