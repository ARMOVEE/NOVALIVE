<?php
/**
 * Halaman Refund / Pengembalian Dana — Gaya Quixotic
 * NOVA LIVE MVC
 */
$currentUser = $currentUser ?? ['full_name' => 'Admin NOVA LIVE', 'role' => 'admin'];
$userInitials = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $currentUser['full_name'] ?? 'AD'), 0, 2));
if (strlen($userInitials) < 2) $userInitials = 'AD';

$refundsData = [];
foreach (($refundOrders ?? []) as $ord) {
    $refundsData[] = [
        'id'     => 'RF-' . date('Y', strtotime($ord['created_at'] ?? 'now')) . '-' . sprintf('%03d', $ord['id']),
        'inv'    => $ord['invoice'] ?? ('INV-' . $ord['id']),
        'buyer'  => $ord['buyer_name'] ?? 'Pembeli',
        'event'  => $ord['event_title'] ?? 'Event Penyelenggara',
        'amount' => (float)($ord['total_price'] ?? 0),
        'reason' => 'Pengajuan pembatalan tiket oleh pembeli',
        'status' => 'menunggu',
        'when'   => !empty($ord['created_at']) ? date('d M Y, H.i', strtotime($ord['created_at'])) : 'Hari ini'
    ];
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <title><?= e($pageTitle ?? 'NOVA LIVE — Refund & Pengembalian Dana') ?></title>
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

    <!-- ========== VIEW: REFUND ========== -->
    <section class="view active" id="view-refund" aria-label="Refund">
      <div class="page-head">
        <div>
          <h1>Refund / Pengembalian Dana</h1>
          <p>Pengajuan refund dari pesanan yang dibatalkan — setujui, tolak, dan pantau pengembalian dananya</p>
        </div>
      </div>
      <div id="refundPending"></div>
      <div class="card">
        <div class="card-head"><div><h2>Semua Pengajuan &amp; Riwayat Refund</h2></div></div>
        <div class="tbl-scroll">
          <table class="tbl">
            <thead><tr><th scope="col">ID Refund</th><th scope="col">Invoice</th><th scope="col">Pembeli</th><th scope="col">Event</th><th scope="col">Jumlah</th><th scope="col">Alasan</th><th scope="col">Status</th><th scope="col">Aksi</th></tr></thead>
            <tbody id="refundTableBody"></tbody>
          </table>
        </div>
      </div>
    </section>

    <?php $shellFooter = true; require __DIR__ . '/sidebar.php'; ?>

  <!-- Script Khusus Refund -->
  <script>
    (function(){
      var DB_REFUNDS = <?= json_encode($refundsData, JSON_UNESCAPED_SLASHES) ?>;
      var MOCK_REFUNDS = [
        {id:"RF-2026-014", inv:"NL-88188", buyer:"Nadia Kusuma", event:"Tulus — Konser Tur Manusia", amount:900000, reason:"Berhalangan hadir — keperluan keluarga mendadak", status:"menunggu", when:"17 Nov 2026, 10.05"},
        {id:"RF-2026-013", inv:"NL-88252", buyer:"Andini Prameswari", event:"Nadin Amizah — Live in Bandung", amount:550000, reason:"Salah memilih tanggal event", status:"menunggu", when:"17 Nov 2026, 08.31"},
        {id:"RF-2026-011", inv:"NL-88102", buyer:"Bima Sakti", event:"Karnaval Kincir Ria Ancol", amount:250000, reason:"Bentrok dengan jadwal kerja", status:"disetujui", when:"15 Nov 2026, 13.20"},
        {id:"RF-2026-009", inv:"NL-88045", buyer:"Citra Dewi", event:"Pameran Fotografi Analog", amount:70000, reason:"Pembelian ganda (double order)", status:"dikembalikan", when:"13 Nov 2026, 09.44"},
        {id:"RF-2026-007", inv:"NL-88012", buyer:"Yoga Saputra", event:"Final Kejuaraan Basket Nasional", amount:100000, reason:"Ingin ganti kategori tiket", status:"ditolak", when:"12 Nov 2026, 16.02"}
      ];

      var REFUNDS = DB_REFUNDS.length ? DB_REFUNDS.concat(MOCK_REFUNDS) : MOCK_REFUNDS;
      var RF_ST = {
        menunggu:{cls:"b-wait",label:"Menunggu"},
        disetujui:{cls:"b-sky",label:"Disetujui"},
        ditolak:{cls:"b-refund",label:"Ditolak"},
        dikembalikan:{cls:"b-live",label:"Dana Dikembalikan"}
      };

      function rp(n){
        return "Rp" + Math.round(n).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
      }

      function rfActions(r){
        if(r.status === "menunggu") return '<button class="mini-btn" type="button" data-rf-ok="' + r.id + '">Setujui</button> <button class="mini-btn warn" type="button" data-rf-no="' + r.id + '">Tolak</button>';
        if(r.status === "disetujui") return '<button class="mini-btn" type="button" data-rf-done="' + r.id + '">Tandai Dana Dikembalikan</button>';
        return '<span style="color:var(--muted);font-size:12.5px">Selesai</span>';
      }

      function renderRefunds(){
        var pend = REFUNDS.filter(function(r){ return r.status === "menunggu"; });
        var badge = document.getElementById("navRefundCount");
        if(badge){ badge.textContent = pend.length; badge.style.display = pend.length ? "" : "none"; }
        var ph = document.getElementById("refundPending");
        if(pend.length){
          ph.innerHTML = '<div class="card" style="margin-bottom:18px"><div class="card-head"><div><h2>Menunggu Persetujuan (' + pend.length + ')</h2><p>Pengajuan terbaru yang belum diputuskan</p></div></div>' +
            pend.map(function(r){
              return '<div class="promo-row"><div class="promo-info"><b>' + r.id + ' · ' + r.buyer + '</b><p>' + r.event + ' · Invoice ' + r.inv + ' · ' + r.when + '<br>Alasan: ' + r.reason + '</p></div><b style="font-family:\'Plus Jakarta Sans\',sans-serif">' + rp(r.amount) + '</b><span class="badge ' + (RF_ST[r.status] ? RF_ST[r.status].cls : "b-wait") + '">' + (RF_ST[r.status] ? RF_ST[r.status].label : "Menunggu") + '</span><span class="row-actions">' + rfActions(r) + '</span></div>';
            }).join("") + '</div>';
        } else {
          ph.innerHTML = '<div class="card" style="margin-bottom:18px;color:var(--muted);font-size:13.5px">Tidak ada pengajuan refund yang menunggu persetujuan.</div>';
        }
        document.getElementById("refundTableBody").innerHTML = REFUNDS.map(function(r){
          return '<tr><td data-label="ID Refund"><b>' + r.id + '</b></td><td data-label="Invoice">' + r.inv + '</td><td data-label="Pembeli">' + r.buyer + '</td><td data-label="Event" style="min-width:160px">' + r.event + '</td><td data-label="Jumlah"><b>' + rp(r.amount) + '</b></td><td data-label="Alasan" style="min-width:170px;white-space:normal">' + r.reason + '</td><td data-label="Status" class="no-label"><span class="badge ' + (RF_ST[r.status] ? RF_ST[r.status].cls : "b-wait") + '">' + (RF_ST[r.status] ? RF_ST[r.status].label : "Menunggu") + '</span></td><td data-label="Aksi" class="no-label"><div class="row-actions">' + rfActions(r) + '</div></td></tr>';
        }).join("");
      }

      document.addEventListener("click", function(e){
        var ok = e.target.closest("[data-rf-ok]"), no = e.target.closest("[data-rf-no]"), dn = e.target.closest("[data-rf-done]");
        function find(id){ return REFUNDS.find(function(r){ return r.id === id; }); }
        if(ok){
          var r1 = find(ok.getAttribute("data-rf-ok"));
          if(r1){
            r1.status = "disetujui";
            renderRefunds();
            if(window.showToast) window.showToast("Refund " + r1.id + " disetujui — dana " + rp(r1.amount) + " siap dikembalikan.");
          }
        }
        if(no){
          var r2 = find(no.getAttribute("data-rf-no"));
          if(r2){
            r2.status = "ditolak";
            renderRefunds();
            if(window.showToast) window.showToast("Refund " + r2.id + " ditolak.");
          }
        }
        if(dn){
          var r3 = find(dn.getAttribute("data-rf-done"));
          if(r3){
            r3.status = "dikembalikan";
            renderRefunds();
            if(window.showToast) window.showToast("Dana refund " + r3.id + " ditandai sudah dikembalikan.");
          }
        }
      });

      renderRefunds();
    })();
  </script>
</body>
</html>
