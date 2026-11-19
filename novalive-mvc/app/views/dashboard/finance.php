<?php
/**
 * Halaman Keuangan — Gaya Quixotic
 * NOVA LIVE MVC
 */
$currentUser = $currentUser ?? ['full_name' => 'Admin NOVA LIVE', 'role' => 'admin'];
$userInitials = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $currentUser['full_name'] ?? 'AD'), 0, 2));
if (strlen($userInitials) < 2) $userInitials = 'AD';

$revenueVal = (float)($totalRevenue ?? 2750000);
$displaySaldo = $revenueVal > 0 ? $revenueVal : 128450000;
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <title><?= e($pageTitle ?? 'NOVA LIVE — Keuangan & Pencairan Dana') ?></title>
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

    <!-- ========== VIEW: KEUANGAN ========== -->
    <section class="view active" id="view-keuangan" aria-label="Keuangan">
      <div class="page-head">
        <div>
          <h1>Keuangan</h1>
          <p>Saldo, pencairan dana, dan riwayat pendapatan</p>
        </div>
      </div>
      <div class="fin-grid">
        <div class="card fin-card">
          <div class="card-head"><div><h2>Saldo Penyelenggara</h2><p>Total saldo akun penjual</p></div></div>
          <div class="fin-val"><?= rupiah($displaySaldo) ?></div>
          <div class="fin-meta">
            <div><span>Bisa ditarik</span><b><?= rupiah($displaySaldo * 0.85) ?></b></div>
            <div><span>Tertahan (settlement)</span><b><?= rupiah($displaySaldo * 0.15) ?></b></div>
            <div><span>Kliring bank</span><b>Rp0</b></div>
          </div>
          <div style="margin-top:16px;display:flex;gap:10px;flex-wrap:wrap">
            <button class="btn-navy" type="button" id="withdrawBtn" style="flex:none;padding:10px 24px;border-radius:12px">Tarik Dana ke Rekening</button>
          </div>
        </div>
        <div class="card">
          <div class="card-head"><div><h2>Rekening Pencairan</h2><p>Rekening bank utama yang terverifikasi</p></div></div>
          <div style="border:1px solid var(--line);border-radius:14px;padding:16px;margin-bottom:12px">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px">
              <b style="font-size:15px;color:var(--navy)">BCA (Bank Central Asia)</b>
              <span class="badge b-live" style="margin-left:auto">Terverifikasi</span>
            </div>
            <p style="margin:0;font-size:13.5px;color:var(--muted)">•••• •••• 4821 · a.n. Sanggar Gembira Kreasi</p>
          </div>
          <p style="color:var(--muted);font-size:12.5px;line-height:1.55;margin:0">Pencairan diproses otomatis maksimal 1 hari kerja (Senin–Jumat). Biaya transfer antar-bank ditanggung NOVA LIVE.</p>
        </div>
      </div>

      <div class="card">
        <div class="card-head"><div><h2>Riwayat Pencairan Dana</h2><p>Daftar penarikan dana ke rekening penyelenggara</p></div></div>
        <div class="tbl-scroll">
          <table class="tbl">
            <thead><tr><th scope="col">Tanggal</th><th scope="col">ID Penarikan</th><th scope="col">Rekening</th><th scope="col">Jumlah</th><th scope="col">Status</th></tr></thead>
            <tbody>
              <tr><td data-label="Tanggal">14 Nov 2026</td><td data-label="ID Penarikan">WD-2026-088</td><td data-label="Rekening">BCA •••• 4821</td><td data-label="Jumlah"><b>Rp45.000.000</b></td><td data-label="Status" class="no-label"><span class="badge b-live">Berhasil</span></td></tr>
              <tr><td data-label="Tanggal">07 Nov 2026</td><td data-label="ID Penarikan">WD-2026-085</td><td data-label="Rekening">BCA •••• 4821</td><td data-label="Jumlah"><b>Rp32.110.000</b></td><td data-label="Status" class="no-label"><span class="badge b-live">Berhasil</span></td></tr>
              <tr><td data-label="Tanggal">31 Okt 2026</td><td data-label="ID Penarikan">WD-2026-081</td><td data-label="Rekening">BCA •••• 4821</td><td data-label="Jumlah"><b>Rp51.340.000</b></td><td data-label="Status" class="no-label"><span class="badge b-live">Berhasil</span></td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </section>

    <?php $shellFooter = true; require __DIR__ . '/sidebar.php'; ?>

  <!-- Script Khusus Keuangan -->
  <script>
    (function(){
      var btn = document.getElementById("withdrawBtn");
      if(btn){
        btn.addEventListener("click", function(){
          if(window.showToast){
            window.showToast("Permintaan pencairan <em><?= rupiah($displaySaldo * 0.85) ?></em> dicatat — dana dikirim ke BCA ••4821 maks. 1 hari kerja.");
          }
        });
      }
    })();
  </script>
</body>
</html>
