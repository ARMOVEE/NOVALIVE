<?php
/**
 * Halaman Kelola Banner Homepage — Gaya Quixotic
 * NOVA LIVE MVC — Tahap 4
 */
$currentUser = $currentUser ?? ['full_name' => 'Admin NOVA LIVE', 'role' => 'admin'];
$userInitials = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $currentUser['full_name'] ?? 'AD'), 0, 2));
if (strlen($userInitials) < 2) $userInitials = 'AD';
$today = date('Y-m-d');
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <meta name="color-scheme" content="light" />
  <link rel="icon" href="data:," />
  <title><?= e($pageTitle ?? 'NOVA LIVE — Banner Homepage') ?></title>
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
      max-width:1680px;margin:0 auto;background:var(--panel);border-radius:30px;
      padding:20px 32px 26px;box-shadow:0 24px 60px rgba(14,50,84,.10);
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
    .btn-gold{
      display:inline-flex;align-items:center;justify-content:center;gap:8px;background:var(--gold);color:var(--navy);
      font-weight:800;font-size:13px;border-radius:12px;padding:10px 20px;box-shadow:0 4px 14px rgba(234,212,162,.3);
      cursor:pointer;border:none;transition:transform .12s,background .15s;text-decoration:none;
    }
    .btn-gold:hover{background:#dfc78f;transform:translateY(-1px)}
    .btn-navy{
      display:inline-flex;align-items:center;justify-content:center;gap:8px;background:var(--navy);color:#fff;
      font-weight:700;font-size:13px;border-radius:12px;padding:9px 20px;cursor:pointer;border:none;
      box-shadow:0 4px 12px rgba(14,50,84,.22);transition:background .15s,transform .12s;text-decoration:none;
    }
    .btn-navy:hover{background:var(--navy-2);transform:translateY(-1px)}
    .mini-btn{
      border:1.5px solid var(--line);background:var(--card);color:#5B6B78;border-radius:9px;
      padding:5px 12px;font-size:12px;font-weight:600;min-height:30px;transition:all .15s;
      cursor:pointer;display:inline-flex;align-items:center;gap:5px;
    }
    .mini-btn:hover{color:var(--navy);border-color:var(--gold-deep)}
    .mini-btn.warn{color:var(--red);border-color:rgba(197,34,31,.25);background:var(--red-bg)}
    .mini-btn.warn:hover{background:#fadbd8;border-color:var(--red)}

    /* Badges */
    .badge{
      display:inline-flex;align-items:center;gap:6px;font-size:11.5px;font-weight:700;
      border-radius:999px;padding:4px 11px;white-space:nowrap;
    }
    .badge::before{content:"";width:7px;height:7px;border-radius:50%;background:currentColor;flex:none}
    .b-live{background:var(--green-bg);color:var(--green)}
    .b-wait{background:var(--cream);color:var(--gold-dark)}
    .b-done{background:#F1F3F4;color:var(--muted)}
    .b-draft{background:#EDF0F2;color:#52657C}

    /* Slide Preview */
    .slide-prev{
      border-radius:16px;padding:24px 28px;color:#fff;position:relative;overflow:hidden;
      min-height:130px;display:flex;flex-direction:column;justify-content:center;
      background-size:cover;background-position:center;box-shadow:0 6px 18px rgba(14,50,84,.12);
    }
    .slide-prev::after{
      content:"";position:absolute;inset:0;background:linear-gradient(90deg,rgba(7,18,34,.55),transparent 75%);
    }
    .slide-prev h3{margin:0 0 6px;font-size:22px;position:relative;z-index:1;font-weight:800;color:#fff}
    .slide-prev p{margin:0 0 14px;font-size:13.5px;color:#D9E6F2;position:relative;z-index:1;max-width:540px;line-height:1.45}
    .pv-cta{
      position:relative;z-index:1;align-self:flex-start;background:var(--gold);color:var(--navy);
      font-weight:800;font-size:12.5px;border-radius:8px;padding:6px 14px;font-family:var(--font-display);
    }

    /* Form Fields */
    .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px}
    .field{display:flex;flex-direction:column;gap:5px}
    .field.full{grid-column:1 / -1}
    .field label{font-size:12.5px;font-weight:700;color:var(--navy)}
    .field input, .field select{
      border:1.5px solid var(--line);border-radius:11px;padding:9px 13px;font-family:inherit;
      font-size:13.5px;color:var(--ink);background:var(--card);transition:border-color .15s;outline:none;
    }
    .field input:focus, .field select:focus{border-color:var(--navy)}
    .color-inp{padding:3px 6px;height:42px;cursor:pointer}
    .file-inp{padding:6px 10px;background:#FAFBFC}

    /* Switch */
    .switch-row{
      display:flex;align-items:center;justify-content:space-between;gap:12px;padding:13px 0;
      border-top:1px solid var(--line);margin-top:14px;
    }
    .switch-row .txt b{display:block;font-size:13.5px;color:var(--ink)}
    .switch-row .txt span{font-size:12px;color:var(--muted)}
    .switch{position:relative;width:46px;height:26px;flex:none;display:inline-block}
    .switch input{position:absolute;opacity:0;width:100%;height:100%;cursor:pointer;margin:0;z-index:2}
    .switch .tr{position:absolute;inset:0;background:#E3E8EC;border:1px solid var(--line);border-radius:99px;transition:.2s;pointer-events:none}
    .switch .tr::after{content:"";position:absolute;top:2px;left:2px;width:20px;height:20px;border-radius:50%;background:#fff;box-shadow:0 1px 3px rgba(14,50,84,.25);transition:.2s}
    .switch input:checked + .tr{background:var(--navy);border-color:var(--navy)}
    .switch input:checked + .tr::after{transform:translateX(20px)}

    /* Modal */
    .overlay{
      position:fixed;inset:0;background:rgba(14,50,84,.45);backdrop-filter:blur(3px);
      display:none;place-items:center;z-index:100;padding:18px;overflow-y:auto;
    }
    .overlay.open{display:grid}
    .modal{background:#fff;border-radius:24px;max-width:540px;width:100%;padding:26px;box-shadow:0 30px 70px rgba(14,50,84,.3);position:relative;margin:auto}
    .modal h2{margin:0 0 6px;font-size:19px;color:var(--navy);padding-right:34px}
    .modal p.sub{margin:0 0 18px;font-size:13px;color:var(--muted)}
    .modal-x{
      position:absolute;top:16px;right:16px;width:34px;height:34px;border-radius:50%;
      border:none;background:transparent;display:grid;place-items:center;color:#8A97A3;font-size:15px;cursor:pointer;
    }
    .modal-x:hover{background:#EEF3F7;color:var(--navy)}

    @media (max-width:768px){
      .form-grid{grid-template-columns:1fr}
      .slide-prev h3{font-size:18px}
    }
  </style>
</head>
<body>
  <div class="frame">

    <?php require __DIR__ . '/sidebar.php'; ?>

    <!-- ========== VIEW: BANNER HOMEPAGE ========== -->
    <section class="view active" id="view-banner" aria-label="Banner Homepage">
      <div class="page-head">
        <div>
          <h1>Banner Homepage</h1>
          <p>Kelola slide promo di bagian paling atas homepage, lengkap dengan gambar dan penjadwalan tayang</p>
        </div>
        <div class="spacer" style="margin-left:auto"></div>
        <button class="btn-gold" type="button" id="bannerAddBtn">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
          Tambah Slide
        </button>
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

      <div class="note-demo">
        Slide aktif akan berputar otomatis setiap 4 detik pada bagian paling atas homepage. Slide dengan jadwal tayang hanya muncul selama rentang tanggalnya — periksa hasilnya langsung lewat <strong><a href="<?= base_url('/') ?>" target="_blank" style="color:var(--navy);text-decoration:underline">Pratinjau Homepage</a></strong>.
      </div>

      <div id="bannerList">
        <?php if (empty($banners)): ?>
          <div class="card" style="color:var(--muted);font-size:13.5px;text-align:center;padding:36px;">
            Belum ada slide banner. Klik tombol <strong>“Tambah Slide”</strong> di atas untuk membuat promo pertama.
          </div>
        <?php else: ?>
          <?php foreach ($banners as $idx => $b): 
            $stCls = 'b-live';
            $stLabel = 'Sedang Tayang';
            if (empty($b['is_active'])) {
                $stCls = 'b-draft'; $stLabel = 'Nonaktif';
            } elseif (!empty($b['start_date']) && $today < $b['start_date']) {
                $stCls = 'b-wait'; $stLabel = 'Terjadwal · mulai ' . tanggal($b['start_date']);
            } elseif (!empty($b['end_date']) && $today > $b['end_date']) {
                $stCls = 'b-done'; $stLabel = 'Berakhir ' . tanggal($b['end_date']);
            }
            $hasImg = !empty($b['image']);
            $bg = $hasImg ? "background-image:url('" . base_url($b['image']) . "');" : "background:linear-gradient(120deg," . (!empty($b['bg_color']) ? e($b['bg_color']) : "#15497A") . ",#0E3254);";
          ?>
            <div class="card">
              <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:14px">
                <h2 style="font-size:16px;margin:0;font-weight:800;color:var(--navy)">Slide <?= $idx + 1 ?></h2>
                <span class="badge <?= $stCls ?>"><?= $stLabel ?></span>

                <span style="margin-left:auto;display:flex;gap:6px;flex-wrap:wrap">
                  <form method="POST" action="<?= base_url('dashboard/homepage/banners/reorder/' . $b['id'] . '/up') ?>" style="display:inline">
                    <?= csrf_field() ?>
                    <button class="mini-btn" type="submit" <?= $idx === 0 ? 'disabled style="opacity:0.4;cursor:default"' : '' ?> aria-label="Naikkan slide">↑ Naik</button>
                  </form>
                  <form method="POST" action="<?= base_url('dashboard/homepage/banners/reorder/' . $b['id'] . '/down') ?>" style="display:inline">
                    <?= csrf_field() ?>
                    <button class="mini-btn" type="submit" <?= $idx === count($banners) - 1 ? 'disabled style="opacity:0.4;cursor:default"' : '' ?> aria-label="Turunkan slide">↓ Turun</button>
                  </form>
                  <form method="POST" action="<?= base_url('dashboard/homepage/banners/delete/' . $b['id']) ?>" style="display:inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus slide ini? File gambar terkait juga akan dihapus dari server.')">
                    <?= csrf_field() ?>
                    <button class="mini-btn warn" type="submit">Hapus Slide</button>
                  </form>
                </span>
              </div>

              <!-- Slide Preview -->
              <div class="slide-prev" style="<?= $bg ?>">
                <h3><?= e($b['title'] ?: 'Promo Spesial NOVA LIVE') ?></h3>
                <p><?= e($b['subtitle'] ?: 'Dapatkan tiket konser, festival musik, dan event seru lainnya dengan diskon eksklusif.') ?></p>
                <span class="pv-cta"><?= e($b['cta_text'] ?: 'Lihat Event') ?></span>
              </div>

              <!-- Edit Form -->
              <form method="POST" action="<?= base_url('dashboard/homepage/banners/update/' . $b['id']) ?>" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="form-grid" style="margin-top:16px">
                  <div class="field">
                    <label>Judul Promo</label>
                    <input type="text" name="title" value="<?= e($b['title'] ?? '') ?>" placeholder="Misal: LaLaLa Festival Jakarta 2027" />
                  </div>
                  <div class="field">
                    <label>Teks Tombol (CTA)</label>
                    <input type="text" name="cta_text" value="<?= e($b['cta_text'] ?? 'Lihat Event') ?>" placeholder="Misal: Beli Tiket" />
                  </div>
                  <div class="field full">
                    <label>Subjudul / Deskripsi Promo</label>
                    <input type="text" name="subtitle" value="<?= e($b['subtitle'] ?? '') ?>" placeholder="Tulis deskripsi singkat yang memikat pengunjung..." />
                  </div>
                  <div class="field">
                    <label>Tautan Tombol (Link URL)</label>
                    <input type="text" name="link_url" value="<?= e($b['link_url'] ?? '/events') ?>" placeholder="Misal: /events atau https://..." />
                  </div>
                  <div class="field">
                    <label>Warna Latar (jika tanpa gambar)</label>
                    <input type="color" name="bg_color" class="color-inp" style="width:100%" value="<?= e($b['bg_color'] ?? '#15497A') ?>" />
                  </div>
                  <div class="field">
                    <label>Jadwal Tayang — Mulai</label>
                    <input type="date" name="start_date" value="<?= e($b['start_date'] ?? '') ?>" />
                  </div>
                  <div class="field">
                    <label>Jadwal Tayang — Selesai</label>
                    <input type="date" name="end_date" value="<?= e($b['end_date'] ?? '') ?>" />
                  </div>
                  <div class="field full">
                    <label>Unggah Gambar Banner (opsional, JPG/PNG/WebP, maks 2 MB)</label>
                    <input type="file" name="image" class="file-inp" accept="image/jpeg,image/png,image/webp" />
                    <?php if (!empty($b['image'])): ?>
                      <div style="font-size:12px;color:var(--muted);margin-top:4px">
                        Gambar aktif tersimpan: <strong style="color:var(--navy)"><?= e($b['image']) ?></strong>
                      </div>
                    <?php endif; ?>
                  </div>
                </div>

                <div class="switch-row">
                  <div class="txt">
                    <b>Slide Aktif</b>
                    <span>Slide nonaktif tidak akan ditampilkan di homepage</span>
                  </div>
                  <label class="switch">
                    <input type="checkbox" name="is_active" value="1" <?= !empty($b['is_active']) ? 'checked' : '' ?> />
                    <span class="tr"></span>
                  </label>
                </div>

                <div style="margin-top:14px">
                  <button class="btn-navy" type="submit">Simpan Slide</button>
                </div>
              </form>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </section>

    <?php $shellFooter = true; require __DIR__ . '/sidebar.php'; ?>

  <!-- Modal Tambah Slide Baru -->
  <div class="overlay" id="bannerModalBack" role="dialog" aria-modal="true" aria-labelledby="bannerModalTitle">
    <div class="modal">
      <button type="button" class="modal-x" id="modalCloseBtn" aria-label="Tutup modal">✕</button>
      <h2 id="bannerModalTitle">Tambah Slide Banner Baru</h2>
      <p class="sub">Tambahkan banner promosi baru untuk dipasang di deretan slide homepage</p>

      <form method="POST" action="<?= base_url('dashboard/homepage/banners/store') ?>" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="form-grid">
          <div class="field">
            <label>Judul Promo</label>
            <input type="text" name="title" placeholder="Misal: Soundrenaline 2026" />
          </div>
          <div class="field">
            <label>Teks Tombol (CTA)</label>
            <input type="text" name="cta_text" value="Lihat Event" placeholder="Lihat Event" />
          </div>
          <div class="field full">
            <label>Subjudul / Deskripsi Promo</label>
            <input type="text" name="subtitle" placeholder="Tuliskan keterangan promo atau jadwal event..." />
          </div>
          <div class="field">
            <label>Tautan Tombol (Link URL)</label>
            <input type="text" name="link_url" value="/events" placeholder="/events" />
          </div>
          <div class="field">
            <label>Warna Latar</label>
            <input type="color" name="bg_color" class="color-inp" style="width:100%" value="#15497A" />
          </div>
          <div class="field">
            <label>Jadwal Tayang — Mulai</label>
            <input type="date" name="start_date" value="<?= date('Y-m-d') ?>" />
          </div>
          <div class="field">
            <label>Jadwal Tayang — Selesai</label>
            <input type="date" name="end_date" />
          </div>
          <div class="field full">
            <label>Berkas Gambar Banner (opsional, maks 2 MB)</label>
            <input type="file" name="image" class="file-inp" accept="image/jpeg,image/png,image/webp" />
          </div>
        </div>

        <div class="switch-row" style="margin-top:14px">
          <div class="txt">
            <b>Slide Aktif</b>
            <span>Langsung tayangkan banner di homepage</span>
          </div>
          <label class="switch">
            <input type="checkbox" name="is_active" value="1" checked />
            <span class="tr"></span>
          </label>
        </div>

        <div style="margin-top:20px;display:flex;justify-content:flex-end;gap:10px">
          <button type="button" class="mini-btn" id="modalCancelBtn">Batal</button>
          <button type="submit" class="btn-navy">Simpan Slide Baru</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    (function(){
      var addBtn = document.getElementById('bannerAddBtn');
      var modalBack = document.getElementById('bannerModalBack');
      var closeBtn = document.getElementById('modalCloseBtn');
      var cancelBtn = document.getElementById('modalCancelBtn');

      function openModal(){ if(modalBack) modalBack.classList.add('open'); }
      function closeModal(){ if(modalBack) modalBack.classList.remove('open'); }

      if (addBtn) addBtn.addEventListener('click', openModal);
      if (closeBtn) closeBtn.addEventListener('click', closeModal);
      if (cancelBtn) cancelBtn.addEventListener('click', closeModal);
      if (modalBack) {
        modalBack.addEventListener('click', function(e){
          if (e.target === modalBack) closeModal();
        });
      }
    })();
  </script>
</body>
</html>
