<?php
/**
 * Halaman Pengaturan & Kontak Penyelenggara — Gaya Quixotic
 * NOVA LIVE MVC
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
  <title><?= e($pageTitle ?? 'NOVA LIVE — Pengaturan & Kontak') ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="<?= base_url('assets/css/dashboard-views.css') ?>" />
  <style>
    :root {
      --navy: #0E3254;
      --navy-light: #15497A;
      --panel: #F7FAFC;
      --card: #FFFFFF;
      --muted: #5B6B78;
      --line: #E2E8F0;
      --gold: #F4D06F;
      --gold-deep: #D4AF37;
    }
  </style>
</head>
<body>
  <div class="frame">
    <?php require __DIR__ . '/sidebar.php'; ?>

    <!-- ========== VIEW: PENGATURAN ========== -->
    <section class="view active" id="view-pengaturan" aria-label="Pengaturan">
      <div class="page-head">
        <div>
          <h1>Pengaturan</h1>
          <p>Profil penyelenggara dan preferensi notifikasi</p>
        </div>
      </div>
      <div class="grid-2">
        <div class="card">
          <div class="card-head"><div><h2>Profil Penyelenggara &amp; Kontak</h2><p>Data ini tampil di halaman detail event dan kontak tiket</p></div></div>
          <form id="orgForm">
            <div class="form-grid">
              <div class="field full">
                <label for="fOrgName">Nama Penyelenggara / Organisasi</label>
                <input id="fOrgName" type="text" value="<?= e($currentUser['full_name'] ?? 'Sanggar Gembira Kreasi') ?>" required />
              </div>
              <div class="field">
                <label for="fOrgEmail">Email Resmi Penyelenggara</label>
                <input id="fOrgEmail" type="email" value="<?= e($currentUser['email'] ?? 'halo@sanggargembira.id') ?>" required />
              </div>
              <div class="field">
                <label for="fOrgPhone">Nomor WhatsApp / CS</label>
                <input id="fOrgPhone" type="text" value="+62 812-3456-7890" />
              </div>
              <div class="field full">
                <label for="fOrgAddr">Alamat Kantor / Domisili</label>
                <input id="fOrgAddr" type="text" value="Jl. Kemang Raya No. 18, Jakarta Selatan 12730" />
              </div>
              <div class="field full">
                <label for="fOrgBank">Rekening Utama Pencairan</label>
                <input id="fOrgBank" type="text" value="BCA •••• 4821 a.n. PT Sanggar Gembira Kreasi" readonly style="background:#F1F5F9;cursor:not-allowed" />
              </div>
            </div>
            <div style="margin-top:16px">
              <button class="btn-navy" type="submit" style="padding:10px 24px;border-radius:12px">Simpan Profil Penyelenggara</button>
            </div>
          </form>
        </div>
        <div class="card">
          <div class="card-head"><div><h2>Preferensi Notifikasi</h2><p>Pilih pesan apa saja yang dikirim ke email penyelenggara</p></div></div>
          <div class="switch-row">
            <div class="txt"><b>Pembelian tiket baru</b><span>Dapat email tiap ada pembayaran tiket yang lunas</span></div>
            <label class="switch"><input type="checkbox" checked data-notif="Pembelian tiket baru" /><span class="tr"></span></label>
          </div>
          <div class="switch-row">
            <div class="txt"><b>Pengajuan refund pembeli</b><span>Notifikasi segera saat ada pesanan yang minta pengembalian</span></div>
            <label class="switch"><input type="checkbox" checked data-notif="Pengajuan refund" /><span class="tr"></span></label>
          </div>
          <div class="switch-row">
            <div class="txt"><b>Tiket hampir habis</b><span>Peringatan jika kuota tiket event tersisa kurang dari 10%</span></div>
            <label class="switch"><input type="checkbox" checked data-notif="Tiket hampir habis" /><span class="tr"></span></label>
          </div>
          <div class="switch-row">
            <div class="txt"><b>Ringkasan mingguan</b><span>Laporan performa penjualan event setiap hari Senin pagi</span></div>
            <label class="switch"><input type="checkbox" checked data-notif="Ringkasan mingguan" /><span class="tr"></span></label>
          </div>
        </div>
      </div>
    </section>

    <?php $shellFooter = true; require __DIR__ . '/sidebar.php'; ?>

  <!-- Script Khusus Pengaturan -->
  <script>
    (function(){
      var form = document.getElementById("orgForm");
      if(form){
        form.addEventListener("submit", function(e){
          e.preventDefault();
          if(window.showToast) window.showToast("Profil penyelenggara berhasil diperbarui.");
        });
      }
      document.querySelectorAll("[data-notif]").forEach(function(sw){
        sw.addEventListener("change", function(){
          var name = this.getAttribute("data-notif");
          if(window.showToast) window.showToast("Pengaturan " + name + " diperbarui.");
        });
      });
    })();
  </script>
</body>
</html>
