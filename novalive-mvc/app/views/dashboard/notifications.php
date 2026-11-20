<?php
/**
 * Halaman Notifikasi Sistem & Broadcast Pembeli — Gaya Quixotic
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
  <title><?= e($pageTitle ?? 'NOVA LIVE — Notifikasi') ?></title>
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
    .card-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:16px}
    .card-head h2{margin:0;font-size:17px;font-weight:800;color:var(--navy)}
    .card-head p{margin:3px 0 0;font-size:12.5px;color:var(--muted)}
    .grid-2{display:grid;grid-template-columns:1.2fr 1fr;gap:20px;margin-bottom:20px}

    /* Form Grid */
    .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px}
    .field{display:flex;flex-direction:column;gap:6px}
    .field.full{grid-column:1/-1}
    .field label{font-size:12.5px;font-weight:700;color:var(--navy)}
    .field input, .field select, .field textarea{
      border:1.5px solid var(--line);border-radius:12px;padding:10px 14px;
      font-size:13.5px;font-family:inherit;color:var(--ink);background:#FBFCFD;outline:none;
    }
    .field input:focus, .field select:focus, .field textarea:focus{border-color:var(--blue);background:#fff}
    .btn-navy{
      background:var(--navy);color:#fff;border-radius:12px;padding:11px 24px;font-weight:700;font-size:13.5px;border:none;cursor:pointer;
      transition:background .15s,transform .1s;
    }
    .btn-navy:hover{background:var(--navy-2);transform:translateY(-1px)}
    .btn-outline{display:inline-flex;align-items:center;gap:6px;background:#fff;border:1.5px solid var(--line);border-radius:999px;padding:7px 15px;font-size:12.5px;font-weight:700;color:var(--navy);cursor:pointer;text-decoration:none}
    .btn-outline:hover{background:#F8FAFB;border-color:#CFD8DF}

    /* Switch Component */
    .switch-row{display:flex;align-items:center;justify-content:space-between;padding:14px 16px;background:#F8FAFC;border-radius:14px;gap:14px}
    .switch-row .txt b{display:block;font-size:13.5px;color:var(--navy)}
    .switch-row .txt span{font-size:12px;color:var(--muted)}
    .switch{position:relative;display:inline-block;width:44px;height:24px;flex:none}
    .switch input{opacity:0;width:0;height:0}
    .tr{position:absolute;cursor:pointer;inset:0;background:#CFD8DF;border-radius:34px;transition:.2s}
    .tr:before{position:absolute;content:"";height:18px;width:18px;left:3px;bottom:3px;background:white;border-radius:50%;transition:.2s}
    input:checked + .tr{background:var(--navy)}
    input:checked + .tr:before{transform:translateX(20px)}

    /* Notification Items */
    .notif-item{
      display:flex;gap:14px;padding:14px 16px;border-radius:14px;border-bottom:1px solid var(--line);
      transition:background .15s;align-items:flex-start;
    }
    .notif-item:hover{background:#F8FAFC}
    .notif-item.unread{background:rgba(234,212,162,0.12);border-left:4px solid var(--gold-deep)}
    .notif-ic{
      width:38px;height:38px;border-radius:10px;background:var(--cream);color:var(--gold-deep);
      display:grid;place-items:center;flex:none;
    }
    .notif-ic.stock{background:#FDF2E9;color:#C05621}
    .notif-ic.refund{background:#FEEBC8;color:#9C4221}
    .notif-ic.order{background:var(--green-bg);color:var(--green)}
    .notif-ic.broadcast{background:#E6F2F8;color:var(--blue)}
    .notif-content{flex:1;min-width:0}
    .notif-content h3{margin:0 0 3px;font-size:14px;font-weight:700;color:var(--navy)}
    .notif-content p{margin:0;font-size:13px;color:var(--ink);line-height:1.45}
    .notif-content small{display:block;margin-top:5px;font-size:11.5px;color:var(--muted)}
    .notif-actions{display:flex;align-items:center;gap:8px;flex:none}

    @media (max-width:1120px){
      .grid-2{grid-template-columns:1fr}
    }
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

    <!-- ========== VIEW: NOTIFIKASI ========== -->
    <section class="view" id="view-notifikasi" aria-label="Notifikasi">
      <div class="page-head">
        <div>
          <h1>Pusat Notifikasi &amp; Pengumuman</h1>
          <p>Kirim pesan broadcast ke pembeli tiket, serta pantau notifikasi otomatis pesanan dan refund</p>
        </div>
        <div style="display:flex;gap:10px">
          <?php if (!empty($unreadCount)): ?>
            <form action="<?= base_url('dashboard/reports/notifications/read-all') ?>" method="POST" style="margin:0">
              <?= csrf_field() ?>
              <button type="submit" class="btn-outline">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M20 6 9 17l-5-5"/></svg>
                Tandai Semua Dibaca
              </button>
            </form>
          <?php endif; ?>
        </div>
      </div>

      <div class="grid-2">
        <!-- Form Kirim Pengumuman -->
        <div class="card">
          <div class="card-head">
            <div>
              <h2>Kirim Pengumuman ke Pembeli</h2>
              <p>Siarkan notifikasi ke semua pembeli atau peserta event tertentu</p>
            </div>
          </div>

          <form action="<?= base_url('dashboard/reports/notifications/create') ?>" method="POST" id="broadcastForm">
            <?= csrf_field() ?>
            <div class="form-grid">
              <div class="field">
                <label for="nfTarget">Target Penerima</label>
                <select id="nfTarget" name="target">
                  <option value="all">Semua Pembeli Tiket (Broadcast Global)</option>
                  <?php foreach ($events as $ev): ?>
                    <option value="<?= (int)$ev['id'] ?>"><?= e($ev['title']) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="field">
                <label for="nfTpl">Template Pengumuman</label>
                <select id="nfTpl">
                  <option value="">— Pilih template otomatis —</option>
                  <option value="jadwal">Perubahan Jadwal Event</option>
                  <option value="venue">Perubahan Lokasi / Venue</option>
                  <option value="h1">Pengingat H-1 Acara</option>
                  <option value="umum">Informasi Ketertiban &amp; Tiket</option>
                </select>
              </div>

              <div class="field full">
                <label for="nfTitle">Judul Notifikasi</label>
                <input id="nfTitle" name="title" type="text" placeholder="cth. Perubahan Jadwal Pintu Masuk — Tulus in Concert" required />
              </div>

              <div class="field full">
                <label for="nfMsg">Isi Pesan</label>
                <textarea id="nfMsg" name="message" rows="4" placeholder="Tuliskan pengumuman penting bagi seluruh pemegang e-tiket…" required></textarea>
              </div>
            </div>

            <div style="margin-top:16px;display:flex;justify-content:flex-end">
              <button type="submit" class="btn-navy">Kirim Notifikasi Sekarang</button>
            </div>
          </form>
        </div>

        <!-- Pengaturan Otomasi Notifikasi -->
        <div class="card">
          <div class="card-head">
            <div>
              <h2>Otomasi Notifikasi Sistem</h2>
              <p>Pengingat cerdas yang dikirimkan secara otomatis oleh platform NOVA LIVE</p>
            </div>
          </div>

          <div style="display:flex;flex-direction:column;gap:12px">
            <div class="switch-row">
              <div class="txt">
                <b>Peringatan Stok Menipis (&le; 20%)</b>
                <span>Sistem otomatis memberitahu admin saat kuota tiket tersisa kurang dari 20%</span>
              </div>
              <label class="switch"><input type="checkbox" id="autoStockAlert" checked /><span class="tr"></span></label>
            </div>

            <div class="switch-row">
              <div class="txt">
                <b>Pengingat Acara H-1 ke Pembeli</b>
                <span>Mengirimkan pengingat otomatis beserta QR e-tiket ke pembeli H-1 acara</span>
              </div>
              <label class="switch"><input type="checkbox" id="autoH1Alert" checked /><span class="tr"></span></label>
            </div>

            <div class="switch-row">
              <div class="txt">
                <b>Notifikasi Pengajuan Refund</b>
                <span>Pemberitahuan instan saat ada pembeli yang mengajukan pembatalan pesanan</span>
              </div>
              <label class="switch"><input type="checkbox" id="autoRefundAlert" checked /><span class="tr"></span></label>
            </div>
          </div>

          <p style="color:var(--muted);font-size:12px;margin:16px 0 0;line-height:1.5">
            Semua otomasi diaktifkan secara default untuk memastikan koordinasi pintu masuk dan penyelenggara berjalan optimal.
          </p>
        </div>
      </div>

      <!-- Riwayat & Daftar Notifikasi -->
      <div class="card">
        <div class="card-head">
          <div>
            <h2>Semua Pemberitahuan (<?= count($notifications) ?>)</h2>
            <p>Daftar notifikasi sistem, pesanan baru, refund, stok tiket, dan broadcast penyelenggara</p>
          </div>
        </div>

        <div style="display:flex;flex-direction:column;gap:4px">
          <?php if (empty($notifications)): ?>
            <div style="text-align:center;padding:36px;color:var(--muted)">Belum ada notifikasi.</div>
          <?php else: ?>
            <?php foreach ($notifications as $nt): 
              $isUnread = empty($nt['is_read']);
              $type = $nt['type'] ?? 'system';
            ?>
              <div class="notif-item <?= $isUnread ? 'unread' : '' ?>">
                <div class="notif-ic <?= e($type) ?>">
                  <?php if ($type === 'stock'): ?>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0zM12 9v4M12 17h.01"/></svg>
                  <?php elseif ($type === 'refund'): ?>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 10h10a5 5 0 0 1 5 5v2M3 10l6-6M3 10l6 6"/></svg>
                  <?php elseif ($type === 'order'): ?>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6 9 17l-5-5"/></svg>
                  <?php elseif ($type === 'broadcast'): ?>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                  <?php else: ?>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                  <?php endif; ?>
                </div>

                <div class="notif-content">
                  <h3><?= e($nt['title']) ?></h3>
                  <p><?= e($nt['message']) ?></p>
                  <small><?= tanggal($nt['created_at'], 'd M Y · H:i') ?> WIB</small>
                </div>

                <div class="notif-actions">
                  <?php if (!empty($nt['link'])): ?>
                    <a href="<?= base_url($nt['link']) ?>" class="btn-outline btn-sm">Lihat</a>
                  <?php endif; ?>

                  <?php if ($isUnread): ?>
                    <form action="<?= base_url('dashboard/reports/notifications/read/' . (int)$nt['id']) ?>" method="POST" style="margin:0">
                      <?= csrf_field() ?>
                      <button type="submit" class="btn-outline btn-sm" title="Tandai Dibaca">Tandai Dibaca</button>
                    </form>
                  <?php endif; ?>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </section>

    <?php $shellFooter = true; require __DIR__ . '/sidebar.php'; ?>
  </div>

  <script>
    (function(){
      // Template Pengumuman Pre-fill
      var tplSelect = document.getElementById('nfTpl');
      var titleInput = document.getElementById('nfTitle');
      var msgInput = document.getElementById('nfMsg');

      var templates = {
        'jadwal': {
          title: 'Perubahan Jadwal Pintu Masuk Event',
          msg: 'Pemberitahuan kepada seluruh pemegang e-tiket: Open gate acara dimajukan 30 menit lebih awal untuk memfasilitasi pemeriksaan tiket dan kenyamanan peserta. Mohon siapkan kode e-tiket Anda di pintu masuk.'
        },
        'venue': {
          title: 'Informasi Penyesuaian Area / Venue',
          msg: 'Informasi penting: Akses gate masuk untuk tiket reguler dialihkan ke Pintu Selatan (Gate B). Tim penunjuk jalan NOVA LIVE siap mengarahkan Anda di lokasi.'
        },
        'h1': {
          title: 'Pengingat Acara: Event Anda Dimulai Besok!',
          msg: 'Hitung mundur dimulai! Acara Anda akan dimulai besok. Pastikan baterai ponsel terisi dan simpan dokumen e-tiket Anda untuk ditunjukkan ke petugas check-in.'
        },
        'umum': {
          title: 'Panduan Kehadiran & Ketertiban Event',
          msg: 'Harap membawa kartu identitas yang sah (KTP/SIM/Paspor) sesuai nama pemesan tiket. Dilarang membawa makanan dan minuman dari luar area pameran/konser.'
        }
      };

      if(tplSelect && titleInput && msgInput){
        tplSelect.addEventListener('change', function(){
          var val = tplSelect.value;
          if(templates[val]){
            titleInput.value = templates[val].title;
            msgInput.value = templates[val].msg;
          }
        });
      }
    })();
  </script>
</body>
</html>
