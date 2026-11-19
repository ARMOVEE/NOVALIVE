<?php
/**
 * Halaman E-Tiket / Dokumen — Gaya Quixotic
 * NOVA LIVE MVC
 */
$currentUser = $currentUser ?? ['full_name' => 'Admin NOVA LIVE', 'role' => 'admin'];
$userInitials = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $currentUser['full_name'] ?? 'AD'), 0, 2));
if (strlen($userInitials) < 2) $userInitials = 'AD';

$ordersData = [];
foreach (($orders ?? []) as $ord) {
    $ordersData[] = [
        'id'       => (int)$ord['id'],
        'inv'      => $ord['invoice'] ?? ('INV-' . $ord['id']),
        'buyer'    => $ord['buyer_name'] ?? 'Pembeli',
        'event'    => $ord['event_title'] ?? 'Event Penyelenggara',
        'cat'      => 'VIP / Reguler',
        'qty'      => (int)($ord['qty'] ?? 1),
        'eventId'  => (int)($ord['event_id'] ?? 1)
    ];
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <title><?= e($pageTitle ?? 'NOVA LIVE — Pratinjau E-Tiket') ?></title>
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

    <!-- ========== VIEW: E-TIKET ========== -->
    <section class="view active" id="view-etiket" aria-label="E-Tiket">
      <div class="page-head">
        <div>
          <h1>Pratinjau E-Tiket</h1>
          <p>Desain e-tiket yang diterima pembeli — atur logo, warna aksen, dan catatan tiket</p>
        </div>
      </div>
      <div class="grid-2">
        <div class="card">
          <div class="card-head"><div><h2>Pengaturan E-Tiket</h2></div></div>
          <div class="form-grid">
            <div class="field full">
              <label for="etOrder">Contoh Pesanan</label>
              <select id="etOrder"></select>
            </div>
            <div class="field">
              <label for="etLogo">Teks Logo</label>
              <input id="etLogo" type="text" value="NOVA LIVE" />
            </div>
            <div class="field">
              <label for="etColor">Warna Aksen Tiket</label>
              <input id="etColor" type="color" class="color-inp" value="#15497a" />
            </div>
            <div class="field full">
              <label for="etNote">Catatan di Tiket</label>
              <textarea id="etNote" rows="3">Tunjukkan e-tiket ini beserta identitas di pintu masuk. Pintu dibuka 1 jam sebelum acara dimulai.</textarea>
            </div>
          </div>
          <p style="color:var(--muted);font-size:12.5px;margin:14px 0 0;line-height:1.55">Perubahan langsung terlihat di pratinjau dan tersimpan di sesi ini saja (demo).</p>
        </div>
        <div>
          <div class="eticket" id="etPreview"></div>
          <p style="text-align:center;color:var(--muted);font-size:12.5px;margin-top:14px">Begini tampilan e-tiket yang diterima pembeli setelah pembayaran lunas.</p>
        </div>
      </div>
    </section>

    <?php $shellFooter = true; require __DIR__ . '/sidebar.php'; ?>

  <!-- Script Khusus E-Tiket -->
  <script>
    (function(){
      var DB_ORDERS = <?= json_encode($ordersData, JSON_UNESCAPED_SLASHES) ?>;
      var MOCK_ORDERS = [
        {id:1, inv:"NL-88231", buyer:"Hadi Gunawan", event:"Tulus — Konser Tur Manusia", cat:"VIP (Tribune)", qty:1, eventId:1},
        {id:2, inv:"NL-88252", buyer:"Gita Permata", event:"Nadin Amizah — Live in Bandung", cat:"Festival Presale", qty:2, eventId:2},
        {id:3, inv:"NL-88190", buyer:"Fajar Nugroho", event:"Final Kejuaraan Basket Nasional", cat:"Reguler", qty:1, eventId:3}
      ];

      var ORDERS = DB_ORDERS.length ? DB_ORDERS : MOCK_ORDERS;

      var ETKT = {
        logo: "NOVA LIVE",
        color: "#15497A",
        note: "Tunjukkan e-tiket ini beserta identitas di pintu masuk. Pintu dibuka 1 jam sebelum acara dimulai."
      };

      function qrSVG(code){
        var seed = 0; for(var i = 0; i < code.length; i++){ seed = (seed * 31 + code.charCodeAt(i)) >>> 0; }
        function rnd(){ seed = (seed * 1103515245 + 12345) >>> 0; return (seed >> 16) & 1; }
        var n = 21, cell = 6, out = "";
        function finder(x, y){
          return '<rect x="' + x * cell + '" y="' + y * cell + '" width="' + 7 * cell + '" height="' + 7 * cell + '" fill="#0E3254"/>' +
                 '<rect x="' + (x + 1) * cell + '" y="' + (y + 1) * cell + '" width="' + 5 * cell + '" height="' + 5 * cell + '" fill="#fff"/>' +
                 '<rect x="' + (x + 2) * cell + '" y="' + (y + 2) * cell + '" width="' + 3 * cell + '" height="' + 3 * cell + '" fill="#0E3254"/>';
        }
        for(var y = 0; y < n; y++){
          for(var x = 0; x < n; x++){
            var inF = (x < 8 && y < 8) || (x > n - 9 && y < 8) || (x < 8 && y > n - 9);
            if(!inF && rnd()) out += '<rect x="' + x * cell + '" y="' + y * cell + '" width="' + cell + '" height="' + cell + '" fill="#0E3254"/>';
          }
        }
        return '<svg width="104" height="104" viewBox="0 0 ' + (n * cell) + ' ' + (n * cell) + '" role="img" aria-label="QR tiket ' + code + '" style="background:#fff;border-radius:6px">' + out + finder(0, 0) + finder(n - 7, 0) + finder(0, n - 7) + '</svg>';
      }

      function initSelect(){
        var sel = document.getElementById("etOrder");
        sel.innerHTML = ORDERS.map(function(o){
          return '<option value="' + o.inv + '">' + o.inv + ' — ' + o.buyer + ' (' + o.event + ')</option>';
        }).join("");
      }

      function renderEtiket(){
        var sel = document.getElementById("etOrder");
        var inv = sel ? sel.value : ORDERS[0].inv;
        var o = ORDERS.find(function(x){ return x.inv === inv; }) || ORDERS[0];
        
        document.getElementById("etPreview").innerHTML =
          '<div class="et-main" style="background:' + ETKT.color + '">' +
            '<div class="et-logo">' + ETKT.logo + '</div>' +
            '<h3>' + o.event + '</h3>' +
            '<p>Sabtu, 18 Nov 2026 · Gelora Bung Karno, Jakarta</p>' +
            '<div class="et-meta">' +
              '<div><small>Pembeli</small>' + o.buyer + '</div>' +
              '<div><small>Kategori Tiket</small>' + o.cat + '</div>' +
              '<div><small>Jumlah</small>' + o.qty + ' tiket</div>' +
              '<div><small>Invoice</small>' + o.inv + '</div>' +
            '</div>' +
            '<div class="et-note">' + ETKT.note + '</div>' +
          '</div>' +
          '<div class="et-stub">' +
            qrSVG(o.inv + "-1") +
            '<span class="code">' + o.inv + '-1</span>' +
            '<small>Scan kode ini di pintu masuk untuk check-in</small>' +
          '</div>';
      }

      function syncEtiket(){
        ETKT.logo = document.getElementById("etLogo").value || "NOVA LIVE";
        ETKT.color = document.getElementById("etColor").value;
        ETKT.note = document.getElementById("etNote").value;
        renderEtiket();
      }

      ["etLogo","etColor","etNote"].forEach(function(id){
        var el = document.getElementById(id);
        if(el) el.addEventListener("input", syncEtiket);
      });

      var sel = document.getElementById("etOrder");
      if(sel) sel.addEventListener("change", renderEtiket);

      initSelect();
      renderEtiket();
    })();
  </script>
</body>
</html>
