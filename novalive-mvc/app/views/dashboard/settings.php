<?php
/**
 * Halaman Pengaturan (Profil Organisasi, Akun Saya, Kelola Pengguna) — Gaya Quixotic
 * NOVA LIVE MVC — Tahap 5
 */
$currentUser = $currentUser ?? ['full_name' => 'Admin NOVA LIVE', 'role' => 'admin'];
$userInitials = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $currentUser['full_name'] ?? 'AD'), 0, 2));
if (strlen($userInitials) < 2) $userInitials = 'AD';
$flash = $flash ?? ($_SESSION['flash'] ?? []);
$activeTab = $activeTab ?? 'org';
$settings = $settings ?? [];
$users = $users ?? [];
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <meta name="color-scheme" content="light" />
  <link rel="icon" href="data:," />
  <title><?= e($pageTitle ?? 'NOVA LIVE — Pengaturan') ?></title>
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
    .avatar-btn{
      display:flex;align-items:center;gap:6px;padding:3px 8px 3px 3px;border-radius:999px;
      background:#F5F7FA;color:var(--navy);font-weight:700;font-size:12.5px;border:none;cursor:pointer;text-decoration:none;
    }
    .avatar{
      width:32px;height:32px;border-radius:50%;background:var(--navy);color:#fff;
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
      background:var(--navy);color:#fff;box-shadow:0 6px 16px rgba(14,50,84,.28);
    }
    .rail .spacer{flex:1;min-height:20px}
    .views{flex:1;min-width:0;display:flex;flex-direction:column;gap:18px}

    /* Page Head */
    .page-head{
      display:flex;align-items:center;justify-content:space-between;gap:18px;
      flex-wrap:wrap;padding:6px 0 2px;
    }
    .page-head h1{
      font-size:26px;font-weight:800;margin:0 0 4px;letter-spacing:-.4px;color:var(--navy);
      font-family:var(--font-display);
    }
    .page-head p{margin:0;font-size:13.5px;color:var(--muted)}

    /* Alert / Flash */
    .alert{
      border-radius:14px;padding:12px 18px;font-size:13.5px;font-weight:500;display:flex;align-items:center;gap:10px;
    }
    .alert-success{background:var(--green-bg);color:var(--green);border:1px solid rgba(30,142,90,.2)}
    .alert-danger{background:var(--red-bg);color:var(--red);border:1px solid rgba(197,34,31,.2)}

    /* Grid & Cards */
    .grid-2{display:grid;grid-template-columns:1fr 1fr;gap:20px;align-items:stretch}
    .card{
      background:var(--card);border-radius:var(--radius);padding:24px 26px;box-shadow:var(--shadow);
    }
    .card-head{
      display:flex;align-items:center;justify-content:space-between;gap:14px;
      margin-bottom:20px;padding-bottom:14px;border-bottom:1px solid var(--line);
    }
    .card-head h2{margin:0 0 4px;font-size:17px;font-weight:800;color:var(--navy);font-family:var(--font-display)}
    .card-head p{margin:0;font-size:12.5px;color:var(--muted)}

    /* Form Fields */
    .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px}
    .field{display:flex;flex-direction:column;gap:5px;margin-bottom:14px}
    .field.full{grid-column:1 / -1}
    .field label{font-size:12.5px;font-weight:700;color:var(--navy)}
    .field input, .field select, .field textarea{
      border:1.5px solid var(--line);border-radius:11px;padding:9px 13px;font-family:inherit;
      font-size:13.5px;color:var(--ink);background:var(--card);transition:border-color .15s;outline:none;
    }
    .field input:focus, .field select:focus, .field textarea:focus{border-color:var(--navy)}

    /* Switches */
    .switch-row{
      display:flex;align-items:center;justify-content:space-between;gap:12px;padding:14px 0;
      border-bottom:1px solid var(--line);
    }
    .switch-row:last-child{border-bottom:0}
    .switch-row .txt{flex:1;min-width:0}
    .switch-row .txt b{display:block;font-size:13.5px;color:var(--ink);margin-bottom:2px}
    .switch-row .txt span{font-size:12px;color:var(--muted)}
    .switch{position:relative;width:46px;height:26px;flex:none;display:inline-block}
    .switch input{position:absolute;opacity:0;width:100%;height:100%;cursor:pointer;margin:0;z-index:2}
    .switch .tr{position:absolute;inset:0;background:#E3E8EC;border:1px solid var(--line);border-radius:99px;transition:.2s;pointer-events:none}
    .switch .tr::after{content:"";position:absolute;top:2px;left:2px;width:20px;height:20px;border-radius:50%;background:#fff;box-shadow:0 1px 3px rgba(14,50,84,.25);transition:.2s}
    .switch input:checked + .tr{background:var(--navy);border-color:var(--navy)}
    .switch input:checked + .tr::after{transform:translateX(20px)}

    /* Buttons */
    .btn-navy{
      display:inline-flex;align-items:center;justify-content:center;gap:7px;background:var(--navy);color:#fff;
      font-weight:700;font-size:13px;border-radius:12px;padding:9px 20px;cursor:pointer;border:none;
      box-shadow:0 4px 12px rgba(14,50,84,.22);transition:background .15s,transform .12s;text-decoration:none;
    }
    .btn-navy:hover{background:var(--navy-2);transform:translateY(-1px)}
    .btn-outline{
      border:1.5px solid var(--line);background:var(--card);color:var(--navy);border-radius:10px;
      padding:7px 14px;font-size:12.5px;font-weight:700;transition:all .15s;cursor:pointer;text-decoration:none;
      display:inline-flex;align-items:center;gap:5px;
    }
    .btn-outline:hover{background:#F8FAFB;border-color:var(--gold-deep);color:var(--gold-deep)}
    .btn-sm{padding:6px 12px;font-size:12px;min-height:32px}

    /* Badges */
    .badge{
      display:inline-flex;align-items:center;gap:6px;font-size:11.5px;font-weight:700;
      border-radius:999px;padding:4px 11px;white-space:nowrap;
    }
    .badge::before{content:"";width:7px;height:7px;border-radius:50%;background:currentColor;flex:none}
    .b-live{background:var(--green-bg);color:var(--green)}
    .b-done{background:#F1F3F4;color:var(--muted)}
    .b-draft{background:#EDF0F2;color:#52657C}
    .b-admin{background:var(--cream);color:var(--gold-dark)}

    /* Table */
    .table-responsive{overflow-x:auto}
    table{width:100%;border-collapse:collapse;text-align:left}
    th{font-size:12px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;padding:12px 14px;border-bottom:1.5px solid var(--line)}
    td{padding:14px;border-bottom:1px solid var(--line);font-size:13.5px;vertical-align:middle}
    tr:last-child td{border-bottom:0}

    /* Modal */
    .overlay{
      position:fixed;inset:0;background:rgba(14,50,84,.45);backdrop-filter:blur(3px);
      display:none;place-items:center;z-index:100;padding:18px;overflow-y:auto;
    }
    .overlay.open{display:grid}
    .modal{background:#fff;border-radius:24px;max-width:520px;width:100%;padding:26px;box-shadow:0 30px 70px rgba(14,50,84,.3);position:relative;margin:auto}
    .modal h2{margin:0 0 6px;font-size:19px;color:var(--navy);padding-right:34px;font-family:var(--font-display)}
    .modal p.sub{margin:0 0 18px;font-size:13px;color:var(--muted)}
    .modal-x{
      position:absolute;top:16px;right:16px;width:34px;height:34px;border-radius:50%;
      border:none;background:transparent;display:grid;place-items:center;color:#8A97A3;font-size:15px;cursor:pointer;
    }
    .modal-x:hover{background:#EEF3F7;color:var(--navy)}

    @media (max-width:820px){
      body{padding:12px 10px 20px}
      .frame{padding:14px 16px 20px;border-radius:20px}
      .grid-2{grid-template-columns:1fr}
      .form-grid{grid-template-columns:1fr}
    }
  </style>
</head>
<body>
  <div class="frame">

    <?php require __DIR__ . '/sidebar.php'; ?>

    <!-- ========== VIEW: PENGATURAN ========== -->
    <section class="view" id="view-pengaturan" aria-label="Pengaturan">
      <div class="page-head">
        <div>
          <h1>Pengaturan</h1>
          <p>Profil penyelenggara, kontak publik, keamanan akun, dan manajemen staf</p>
        </div>
      </div>

      <?php if (!empty($flash['success'])): ?>
        <div class="alert alert-success"><?= e($flash['success']) ?></div>
      <?php endif; ?>
      <?php if (!empty($flash['error'])): ?>
        <div class="alert alert-danger"><?= e($flash['error']) ?></div>
      <?php endif; ?>

      <?php if ($activeTab === 'org'): ?>
        <!-- TAB 1: PROFIL ORGANISASI & KONTAK -->
        <div class="grid-2">
          <!-- Card Kiri: Profil Penyelenggara & Kontak -->
          <div class="card">
            <div class="card-head">
              <div>
                <h2>Profil Penyelenggara</h2>
                <p>Kontak resmi yang tampil ke pembeli di e-tiket dan footer website</p>
              </div>
            </div>

            <form method="POST" action="<?= base_url('dashboard/settings/profile') ?>">
              <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

              <div class="form-grid">
                <div class="field full">
                  <label for="fOrgName">Nama Penyelenggara / Komunitas</label>
                  <input id="fOrgName" name="org_name" type="text" value="<?= e($settings['org_name'] ?? 'Sanggar Gembira') ?>" required />
                </div>
                <div class="field">
                  <label for="fOrgEmail">Email Kontak Resmi</label>
                  <input id="fOrgEmail" name="contact_email" type="email" value="<?= e($settings['contact_email'] ?? 'halo@sanggargembira.id') ?>" required />
                </div>
                <div class="field">
                  <label for="fOrgPhone">WhatsApp / No. HP Kontak</label>
                  <input id="fOrgPhone" name="contact_phone" type="text" value="<?= e($settings['contact_phone'] ?? '0812-3456-7890') ?>" required />
                </div>
                <div class="field">
                  <label for="fOrgCity">Kota Basis</label>
                  <select id="fOrgCity" name="contact_city">
                    <?php
                      $citiesList = ['Jakarta', 'Bandung', 'Surabaya', 'Yogyakarta', 'Denpasar', 'Semarang', 'Medan', 'Makassar', 'Malang', 'Solo'];
                      $selectedCity = $settings['contact_city'] ?? 'Jakarta';
                    ?>
                    <?php foreach ($citiesList as $c): ?>
                      <option value="<?= $c ?>" <?= $selectedCity === $c ? 'selected' : '' ?>><?= $c ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="field">
                  <label for="fOrgIg">Instagram</label>
                  <input id="fOrgIg" name="contact_instagram" type="text" value="<?= e($settings['contact_instagram'] ?? '@sanggargembira') ?>" placeholder="@username" />
                </div>
                <div class="field full">
                  <label for="fOrgAddress">Alamat Kantor / Sekretariat</label>
                  <input id="fOrgAddress" name="contact_address" type="text" value="<?= e($settings['contact_address'] ?? 'Jl. Senopati No. 45, Kebayoran Baru, Jakarta Selatan') ?>" />
                </div>
                <div class="field full">
                  <label for="fOrgDesc">Deskripsi Singkat</label>
                  <textarea id="fOrgDesc" name="org_description" rows="3"><?= e($settings['org_description'] ?? 'Penyelenggara konser dan festival musik independen sejak 2019.') ?></textarea>
                </div>
              </div>

              <div style="margin-top:18px">
                <button type="submit" class="btn-navy" style="padding:10px 24px">Simpan Perubahan</button>
              </div>
            </form>
          </div>

          <!-- Card Kanan: Preferensi Notifikasi -->
          <div class="card">
            <div class="card-head">
              <div>
                <h2>Preferensi Notifikasi</h2>
                <p>Pilih kabar dan pemberitahuan sistem yang ingin Anda terima</p>
              </div>
            </div>

            <form method="POST" action="<?= base_url('dashboard/settings/profile') ?>">
              <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

              <div class="switch-row">
                <div class="txt">
                  <b>Pesanan Baru</b>
                  <span>Kirim email pemberitahuan setiap ada pembelian tiket event baru</span>
                </div>
                <label class="switch">
                  <input type="checkbox" name="notif_new_order" value="1" <?= (!isset($settings['notif_new_order']) || $settings['notif_new_order'] === '1') ? 'checked' : '' ?> />
                  <span class="tr"></span>
                </label>
              </div>

              <div class="switch-row">
                <div class="txt">
                  <b>Tiket Hampir Habis</b>
                  <span>Peringatan instan saat sisa kuota tiket event berada di bawah 15%</span>
                </div>
                <label class="switch">
                  <input type="checkbox" name="notif_low_stock" value="1" <?= (!isset($settings['notif_low_stock']) || $settings['notif_low_stock'] === '1') ? 'checked' : '' ?> />
                  <span class="tr"></span>
                </label>
              </div>

              <div class="switch-row">
                <div class="txt">
                  <b>Pencairan Dana</b>
                  <span>Konfirmasi email saat pengajuan transfer dana tiket berhasil diproses</span>
                </div>
                <label class="switch">
                  <input type="checkbox" name="notif_payout" value="1" <?= (!isset($settings['notif_payout']) || $settings['notif_payout'] === '1') ? 'checked' : '' ?> />
                  <span class="tr"></span>
                </label>
              </div>

              <div class="switch-row">
                <div class="txt">
                  <b>Ringkasan Mingguan</b>
                  <span>Laporan analitik performa penjualan tiket dikirim setiap Senin pagi</span>
                </div>
                <label class="switch">
                  <input type="checkbox" name="notif_weekly_summary" value="1" <?= (!empty($settings['notif_weekly_summary']) && $settings['notif_weekly_summary'] === '1') ? 'checked' : '' ?> />
                  <span class="tr"></span>
                </label>
              </div>

              <div style="margin-top:24px">
                <button type="submit" class="btn-navy" style="padding:10px 24px">Simpan Preferensi</button>
              </div>
            </form>
          </div>
        </div>

      <?php elseif ($activeTab === 'account'): ?>
        <!-- TAB 2: AKUN SAYA -->
        <div class="grid-2">
          <!-- Card Kiri: Profil Pribadi -->
          <div class="card">
            <div class="card-head">
              <div>
                <h2>Profil Akun Saya</h2>
                <p>Informasi identitas pribadi dan foto profil pengelola</p>
              </div>
            </div>

            <form method="POST" action="<?= base_url('dashboard/settings/account') ?>" enctype="multipart/form-data">
              <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

              <div style="display:flex;align-items:center;gap:16px;margin-bottom:20px;padding:12px;background:#F8FAFC;border-radius:14px">
                <div style="width:64px;height:64px;border-radius:50%;overflow:hidden;background:var(--navy);display:grid;place-items:center;color:#fff;font-size:20px;font-weight:800;flex:none">
                  <?php if (!empty($currentUser['avatar']) && file_exists(ROOT_PATH . '/public/' . ltrim($currentUser['avatar'], '/'))): ?>
                    <img src="<?= base_url(ltrim($currentUser['avatar'], '/')) ?>" alt="Avatar" style="width:100%;height:100%;object-fit:cover" />
                  <?php else: ?>
                    <?= e($userInitials) ?>
                  <?php endif; ?>
                </div>
                <div style="flex:1">
                  <label for="userAvatarInput" style="font-size:12.5px;font-weight:700;color:var(--navy);display:block;margin-bottom:4px">Unggah Foto Profil Baru</label>
                  <input type="file" id="userAvatarInput" name="avatar" accept="image/jpeg,image/png,image/webp" style="font-size:12px" />
                  <span style="display:block;font-size:11px;color:var(--muted);margin-top:4px">Maksimal 2 MB (JPG, PNG, atau WebP).</span>
                </div>
              </div>

              <div class="field">
                <label for="accFullName">Nama Lengkap</label>
                <input id="accFullName" name="full_name" type="text" value="<?= e($currentUser['full_name'] ?? '') ?>" required />
              </div>

              <div class="field">
                <label for="accEmail">Email Login</label>
                <input id="accEmail" name="email" type="email" value="<?= e($currentUser['email'] ?? '') ?>" required />
              </div>

              <div class="field">
                <label for="accPhone">No. WhatsApp / HP</label>
                <input id="accPhone" name="phone" type="text" value="<?= e($currentUser['phone'] ?? '') ?>" placeholder="cth. 0812-3456-7890" />
              </div>

              <div class="field">
                <label>Peran Hak Akses</label>
                <div>
                  <span class="badge b-admin"><?= strtoupper(e($currentUser['role'] ?? 'admin')) ?></span>
                </div>
              </div>

              <div style="margin-top:20px">
                <button type="submit" class="btn-navy" style="padding:10px 24px">Perbarui Profil</button>
              </div>
            </form>
          </div>

          <!-- Card Kanan: Ganti Kata Sandi -->
          <div class="card">
            <div class="card-head">
              <div>
                <h2>Ganti Kata Sandi</h2>
                <p>Pastikan akun Anda tetap aman dengan kata sandi kuat</p>
              </div>
            </div>

            <form method="POST" action="<?= base_url('dashboard/settings/password') ?>">
              <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

              <div class="field">
                <label for="pwdCurrent">Kata Sandi Saat Ini</label>
                <input id="pwdCurrent" name="current_password" type="password" placeholder="Masukkan kata sandi lama Anda" required />
              </div>

              <div class="field">
                <label for="pwdNew">Kata Sandi Baru</label>
                <input id="pwdNew" name="new_password" type="password" minlength="8" placeholder="Minimal 8 karakter" required />
              </div>

              <div class="field">
                <label for="pwdConfirm">Konfirmasi Kata Sandi Baru</label>
                <input id="pwdConfirm" name="confirm_password" type="password" minlength="8" placeholder="Ulangi kata sandi baru" required />
              </div>

              <div style="margin-top:24px">
                <button type="submit" class="btn-navy" style="padding:10px 24px">Perbarui Kata Sandi</button>
              </div>
            </form>
          </div>
        </div>

      <?php elseif ($activeTab === 'users'): ?>
        <!-- TAB 3: KELOLA PENGGUNA (Khusus Admin) -->
        <div class="card">
          <div class="card-head">
            <div>
              <h2>Kelola Pengguna &amp; Hak Akses Staf</h2>
              <p>Kelola staf internal penyelenggara yang memiliki akses ke dashboard</p>
            </div>
            <div>
              <button class="btn-navy" id="openAddUserBtn" type="button">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
                Tambah Pengguna Baru
              </button>
            </div>
          </div>

          <div class="table-responsive">
            <table>
              <thead>
                <tr>
                  <th>Pengguna</th>
                  <th>No. WhatsApp / HP</th>
                  <th>Peran</th>
                  <th>Status Akun</th>
                  <th>Login Terakhir</th>
                  <th style="text-align:right">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($users as $u): ?>
                  <?php
                    $uInitials = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $u['full_name'] ?? 'ST'), 0, 2));
                    if (strlen($uInitials) < 2) $uInitials = 'ST';
                    $isSelf = ((int)$u['id'] === (int)($currentUser['id'] ?? 1));
                  ?>
                  <tr>
                    <td>
                      <div style="display:flex;align-items:center;gap:12px">
                        <div style="width:36px;height:36px;border-radius:50%;overflow:hidden;background:var(--navy);color:#fff;display:grid;place-items:center;font-size:12px;font-weight:800;flex:none">
                          <?php if (!empty($u['avatar']) && file_exists(ROOT_PATH . '/public/' . ltrim($u['avatar'], '/'))): ?>
                            <img src="<?= base_url(ltrim($u['avatar'], '/')) ?>" alt="Avatar" style="width:100%;height:100%;object-fit:cover" />
                          <?php else: ?>
                            <?= e($uInitials) ?>
                          <?php endif; ?>
                        </div>
                        <div>
                          <b style="color:var(--navy);font-size:14px"><?= e($u['full_name']) ?></b>
                          <span style="display:block;font-size:12px;color:var(--muted)"><?= e($u['email']) ?></span>
                        </div>
                      </div>
                    </td>
                    <td><?= !empty($u['phone']) ? e($u['phone']) : '<span style="color:var(--muted)">—</span>' ?></td>
                    <td>
                      <?php if ($u['role'] === 'admin'): ?>
                        <span class="badge b-admin">Administrator</span>
                      <?php elseif ($u['role'] === 'manager'): ?>
                        <span class="badge b-live">Manager</span>
                      <?php else: ?>
                        <span class="badge b-draft">Staff</span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <?php if (!empty($u['is_active'])): ?>
                        <span class="badge b-live">Aktif</span>
                      <?php else: ?>
                        <span class="badge b-draft">Nonaktif</span>
                      <?php endif; ?>
                    </td>
                    <td style="font-size:12.5px;color:var(--muted)">
                      <?= !empty($u['last_login_at']) ? tanggal($u['last_login_at'], 'd M Y · H:i') . ' WIB' : 'Belum pernah login' ?>
                    </td>
                    <td style="text-align:right">
                      <?php if ($isSelf): ?>
                        <span style="font-size:12px;color:var(--muted);font-style:italic">Akun Anda</span>
                      <?php else: ?>
                        <div style="display:inline-flex;align-items:center;gap:6px">
                          <button class="btn-outline btn-sm js-edit-role" type="button"
                            data-id="<?= $u['id'] ?>"
                            data-name="<?= e($u['full_name']) ?>"
                            data-role="<?= e($u['role']) ?>">Ubah Peran</button>

                          <form method="POST" action="<?= base_url('dashboard/settings/users/toggle/' . $u['id']) ?>" style="display:inline;margin:0" onsubmit="return confirm('Apakah Anda yakin ingin <?= empty($u['is_active']) ? 'mengaktifkan' : 'menonaktifkan' ?> akun <?= e($u['full_name']) ?>?')">
                            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                            <button class="btn-outline btn-sm" type="submit" title="<?= empty($u['is_active']) ? 'Aktifkan akun' : 'Nonaktifkan akun' ?>">
                              <?= empty($u['is_active']) ? 'Aktifkan' : 'Nonaktifkan' ?>
                            </button>
                          </form>
                        </div>
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      <?php endif; ?>
    </section>

    <p class="foot-note" style="margin:26px 0 0;font-size:12px;color:var(--muted);text-align:center">© 2026 NOVA LIVE. Hak Cipta Dilindungi. · Dashboard Penyelenggara</p>

    <?php $shellFooter = true; require __DIR__ . '/sidebar.php'; ?>

  </div><!-- /.frame -->

  <!-- Modal Tambah Pengguna Baru (Khusus Admin) -->
  <div class="overlay" id="addUserModal">
    <div class="modal">
      <button class="modal-x" type="button" id="closeAddUserModal" aria-label="Tutup">&times;</button>
      <h2>Tambah Pengguna Baru</h2>
      <p class="sub">Daftarkan akun staf baru untuk membantu operasional event</p>

      <form method="POST" action="<?= base_url('dashboard/settings/users/store') ?>">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

        <div class="field full">
          <label for="newFullName">Nama Lengkap</label>
          <input type="text" id="newFullName" name="full_name" placeholder="cth. Budi Pratama" required />
        </div>

        <div class="field full">
          <label for="newEmail">Alamat Email</label>
          <input type="email" id="newEmail" name="email" placeholder="budi@penyelenggara.id" required />
        </div>

        <div class="form-grid">
          <div class="field">
            <label for="newPhone">No. WhatsApp / HP</label>
            <input type="text" id="newPhone" name="phone" placeholder="0812-xxxx-xxxx" />
          </div>
          <div class="field">
            <label for="newRole">Peran Hak Akses</label>
            <select id="newRole" name="role" required>
              <option value="staff">Staff (Operasional / Check-in)</option>
              <option value="manager">Manager (Kelola Event & Pesanan)</option>
              <option value="admin">Administrator (Akses Penuh)</option>
            </select>
          </div>
        </div>

        <div class="field full">
          <label for="newPassword">Kata Sandi Awal</label>
          <input type="password" id="newPassword" name="password" minlength="8" placeholder="Minimal 8 karakter" required />
        </div>

        <div style="margin-top:18px;display:flex;justify-content:flex-end;gap:10px">
          <button type="button" class="btn-outline" id="cancelAddUserBtn">Batal</button>
          <button type="submit" class="btn-navy">Daftarkan Pengguna</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal Ubah Peran Pengguna (Khusus Admin) -->
  <div class="overlay" id="editRoleModal">
    <div class="modal">
      <button class="modal-x" type="button" id="closeEditRoleModal" aria-label="Tutup">&times;</button>
      <h2>Ubah Peran Pengguna</h2>
      <p class="sub" id="editRoleTargetDesc">Pilih tingkat hak akses akun pengguna</p>

      <form method="POST" id="editRoleForm" action="">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

        <div class="field full">
          <label for="editRoleSelect">Peran Baru</label>
          <select id="editRoleSelect" name="role" required>
            <option value="staff">Staff (Operasional & Laporan)</option>
            <option value="manager">Manager (Event, Pesanan, Laporan)</option>
            <option value="admin">Administrator (Akses Penuh)</option>
          </select>
        </div>

        <div style="margin-top:18px;display:flex;justify-content:flex-end;gap:10px">
          <button type="button" class="btn-outline" id="cancelEditRoleBtn">Batal</button>
          <button type="submit" class="btn-navy">Simpan Peran</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    (function(){
      // Add User Modal
      var openAddUserBtn = document.getElementById('openAddUserBtn');
      var addUserModal = document.getElementById('addUserModal');
      var closeAddUserModal = document.getElementById('closeAddUserModal');
      var cancelAddUserBtn = document.getElementById('cancelAddUserBtn');

      function openAddUser(){ if (addUserModal) addUserModal.classList.add('open'); }
      function closeAddUser(){ if (addUserModal) addUserModal.classList.remove('open'); }

      if (openAddUserBtn) openAddUserBtn.addEventListener('click', openAddUser);
      if (closeAddUserModal) closeAddUserModal.addEventListener('click', closeAddUser);
      if (cancelAddUserBtn) cancelAddUserBtn.addEventListener('click', closeAddUser);
      if (addUserModal) addUserModal.addEventListener('click', function(e){ if (e.target === addUserModal) closeAddUser(); });

      // Edit Role Modal
      var editRoleModal = document.getElementById('editRoleModal');
      var closeEditRoleModal = document.getElementById('closeEditRoleModal');
      var cancelEditRoleBtn = document.getElementById('cancelEditRoleBtn');
      var editRoleForm = document.getElementById('editRoleForm');
      var editRoleTargetDesc = document.getElementById('editRoleTargetDesc');
      var editRoleSelect = document.getElementById('editRoleSelect');

      function closeEditRole(){ if (editRoleModal) editRoleModal.classList.remove('open'); }
      if (closeEditRoleModal) closeEditRoleModal.addEventListener('click', closeEditRole);
      if (cancelEditRoleBtn) cancelEditRoleBtn.addEventListener('click', closeEditRole);
      if (editRoleModal) editRoleModal.addEventListener('click', function(e){ if (e.target === editRoleModal) closeEditRole(); });

      document.querySelectorAll('.js-edit-role').forEach(function(btn){
        btn.addEventListener('click', function(){
          var id = this.getAttribute('data-id');
          var name = this.getAttribute('data-name');
          var role = this.getAttribute('data-role');

          if (editRoleForm) {
            editRoleForm.action = '<?= base_url('dashboard/settings/users/role/') ?>' + id;
          }
          if (editRoleTargetDesc) {
            editRoleTargetDesc.textContent = 'Pilih hak akses untuk pengguna ' + name;
          }
          if (editRoleSelect) {
            editRoleSelect.value = role || 'staff';
          }
          if (editRoleModal) editRoleModal.classList.add('open');
        });
      });
    })();
  </script>
</body>
</html>
