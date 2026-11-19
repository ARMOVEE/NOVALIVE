<?php
/**
 * Halaman Promosi & Kode Promo — Gaya Quixotic
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
  <title><?= e($pageTitle ?? 'NOVA LIVE — Promosi & Kode Promo') ?></title>
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

    <!-- ========== VIEW: PROMOSI ========== -->
    <section class="view active" id="view-promosi" aria-label="Promosi">
      <div class="page-head">
        <div>
          <h1>Promosi</h1>
          <p>Kode promo aktif untuk event kamu — klik Salin untuk membagikannya</p>
        </div>
      </div>
      <div class="card">
        <div class="promo-row">
          <div class="promo-code">FLASHDEAL20</div>
          <div class="promo-info"><b>Diskon 20% · Semua Konser Musik</b><p>Kedaluwarsa 31 Des 2026 · dipakai 142 kali</p></div>
          <span class="badge b-live">Aktif</span>
          <button class="btn-outline btn-sm" type="button" data-copy="FLASHDEAL20">Salin Kode</button>
        </div>
        <div class="promo-row">
          <div class="promo-code">NOVAFEST</div>
          <div class="promo-info"><b>Potongan Rp50.000 · Min. transaksi Rp300.000</b><p>Kedaluwarsa 15 Jan 2027 · dipakai 88 kali</p></div>
          <span class="badge b-live">Aktif</span>
          <button class="btn-outline btn-sm" type="button" data-copy="NOVAFEST">Salin Kode</button>
        </div>
        <div class="promo-row">
          <div class="promo-code">EARLYBIRD</div>
          <div class="promo-info"><b>Diskon 15% · Kategori Olahraga &amp; Workshop</b><p>Kedaluwarsa 20 Des 2026 · dipakai 205 kali</p></div>
          <span class="badge b-live">Aktif</span>
          <button class="btn-outline btn-sm" type="button" data-copy="EARLYBIRD">Salin Kode</button>
        </div>
        <div class="promo-row">
          <div class="promo-code">JAZZOKT</div>
          <div class="promo-info"><b>Diskon Rp30.000 · Midnight Jazz Night</b><p>Kedaluwarsa 14 Nov 2026 · dipakai 310 kali</p></div>
          <span class="badge b-done">Berakhir</span>
          <button class="btn-outline btn-sm" type="button" data-copy="JAZZOKT">Salin Kode</button>
        </div>
      </div>
    </section>

    <?php $shellFooter = true; require __DIR__ . '/sidebar.php'; ?>

  <!-- Script Khusus Promosi: Copy to Clipboard -->
  <script>
    (function(){
      document.querySelectorAll("[data-copy]").forEach(function(b){
        b.addEventListener("click", function(){
          var code = b.getAttribute("data-copy");
          function done(){
            if(window.showToast) window.showToast('Kode promo <em>' + code + '</em> tersalin ke clipboard.');
          }
          if(navigator.clipboard && navigator.clipboard.writeText){
            navigator.clipboard.writeText(code).then(done).catch(done);
          } else {
            var ta = document.createElement("textarea"); ta.value = code; document.body.appendChild(ta);
            ta.select(); try { document.execCommand("copy"); } catch(e){} document.body.removeChild(ta); done();
          }
        });
      });
    })();
  </script>
</body>
</html>
