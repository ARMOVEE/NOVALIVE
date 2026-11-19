<?php
/**
 * Halaman Check-in Tiket — Gaya Quixotic
 * NOVA LIVE MVC
 */
$currentUser = $currentUser ?? ['full_name' => 'Admin NOVA LIVE', 'role' => 'admin'];
$userInitials = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $currentUser['full_name'] ?? 'AD'), 0, 2));
if (strlen($userInitials) < 2) $userInitials = 'AD';

$eventsData = [];
foreach (($events ?? []) as $ev) {
    $eventsData[] = [
        'id'        => (int)$ev['id'],
        'name'      => $ev['title'] ?? 'Event #' . $ev['id'],
        'venue'     => $ev['venue'] ?? 'Venue',
        'city'      => 'Jakarta',
        'dateLabel' => !empty($ev['event_date']) ? date('d M Y', strtotime($ev['event_date'])) : 'Terjadwal',
        'price'     => (float)($ev['price'] ?? 0),
        'status'    => 'tayang',
        'color'     => '#23649A'
    ];
}

$ordersData = [];
foreach (($orders ?? []) as $ord) {
    $ordersData[] = [
        'id'       => (int)$ord['id'],
        'inv'      => $ord['invoice'] ?? ('INV-' . $ord['id']),
        'buyer'    => $ord['buyer_name'] ?? 'Pembeli',
        'eventId'  => (int)($ord['event_id'] ?? 1),
        'cat'      => 'Reguler',
        'qty'      => (int)($ord['qty'] ?? 1),
        'status'   => ($ord['status'] === 'Terkirim' || $ord['status'] === 'Selesai') ? 'lunas' : 'menunggu'
    ];
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <title><?= e($pageTitle ?? 'NOVA LIVE — Check-in Tiket') ?></title>
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

    <!-- ========== VIEW: CHECK-IN TIKET ========== -->
    <section class="view active" id="view-checkin" aria-label="Check-in">
      <div class="page-head">
        <div>
          <h1>Check-in Tiket</h1>
          <p>Validasi tiket di pintu masuk dan kelola daftar hadir per event</p>
        </div>
      </div>
      <div class="note-demo">Simulasi demo — mode offline: scan tetap jalan walau tanpa internet. Data check-in tersimpan di sesi ini dan disinkronkan nanti saat koneksi kembali.</div>
      <div class="grid-2">
        <div class="card">
          <div class="card-head"><div><h2>Scan / Ketik Kode Tiket</h2><p>Pilih event, lalu scan QR atau ketik kode tiket pembeli</p></div></div>
          <div class="field">
            <label for="ciEvent">Event di Pintu Masuk</label>
            <select id="ciEvent"></select>
          </div>
          <div class="scan-row">
            <input id="ciCode" type="text" placeholder="cth. NL-88231-1" aria-label="Kode tiket" autocomplete="off" />
            <button class="btn-navy" type="button" id="ciValidate">Validasi</button>
            <button class="btn-ghost" type="button" id="ciRandom">Simulasi Scan QR</button>
          </div>
          <p style="color:var(--muted);font-size:12.5px;margin:10px 0 0;line-height:1.55">Kode demo untuk event terpilih tampil di daftar hadir di bawah — atau klik “Simulasi Scan QR” untuk memindai kode acak.</p>
          <div class="res-box" id="ciResult" role="status"></div>
          <div class="switch-row" style="margin-top:8px">
            <div class="txt"><b>Mode Offline</b><span>Scan tetap berjalan tanpa internet (simulasi demo)</span></div>
            <label class="switch"><input type="checkbox" id="ciOffline" /><span class="tr"></span></label>
          </div>
        </div>
        <div class="card">
          <div class="card-head"><div><h2>Statistik Check-in</h2><p id="ciStatSub">—</p></div></div>
          <div class="stat-grid" style="margin-bottom:12px">
            <div class="stat-box"><b id="ciIn">0</b><span>Sudah Check-in</span></div>
            <div class="stat-box"><b id="ciTotal">0</b><span>Total Tiket</span></div>
            <div class="stat-box"><b id="ciPct">0%</b><span>Kehadiran</span></div>
          </div>
          <div class="progress" style="height:10px"><i id="ciBar" style="width:0%"></i></div>
          <p style="color:var(--muted);font-size:12.5px;margin:10px 0 0" id="ciLeft">—</p>
        </div>
      </div>
      <div class="card">
        <div class="table-tools">
          <h2>Daftar Hadir</h2>
          <div class="mini-search">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/></svg>
            <input type="search" id="ciSearch" placeholder="Cari nama / kode tiket…" aria-label="Cari daftar hadir" />
          </div>
          <button class="btn-outline btn-sm" type="button" id="ciExport">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v12M7 10l5 5 5-5M4 21h16"/></svg>
            Export CSV
          </button>
        </div>
        <div class="tbl-scroll">
          <table class="tbl">
            <thead><tr><th scope="col">Kode Tiket</th><th scope="col">Pembeli</th><th scope="col">Kategori Tiket</th><th scope="col">Status</th><th scope="col">Aksi</th></tr></thead>
            <tbody id="ciTableBody"></tbody>
          </table>
        </div>
      </div>
    </section>

    <?php $shellFooter = true; require __DIR__ . '/sidebar.php'; ?>

  <!-- Script Khusus Check-in -->
  <script>
    (function(){
      var DB_EVENTS = <?= json_encode($eventsData, JSON_UNESCAPED_SLASHES) ?>;
      var DB_ORDERS = <?= json_encode($ordersData, JSON_UNESCAPED_SLASHES) ?>;

      var EVENTS = DB_EVENTS.length ? DB_EVENTS : [
        {id:1, name:"Tulus — Konser Tur Manusia", dateLabel:"Min, 6 Des 2026", venue:"Istora Senayan", city:"Jakarta"},
        {id:2, name:"Nadin Amizah — Live in Bandung", dateLabel:"Jum, 11 Des 2026", venue:"The Hall", city:"Bandung"}
      ];

      function evById(id){
        return EVENTS.find(function(e){ return String(e.id) === String(id); }) || EVENTS[0];
      }

      var ATTENDEES = [];
      DB_ORDERS.filter(function(o){ return o.status === "lunas"; }).forEach(function(o){
        for(var i = 1; i <= o.qty; i++){
          ATTENDEES.push({code:o.inv + "-" + i, buyer:o.buyer, eventId:o.eventId, cat:o.cat, checkedIn:false});
        }
      });

      var EXTRA_ATT = [
        ["Andini Prameswari",1,"CAT 1"],["Bima Sakti",1,"Festival · Tribune"],["Citra Dewi",1,"CAT 2"],
        ["Dewi Anggraini",1,"VIP"],["Eko Prasetyo",1,"Festival · Tribune"],["Fitri Rahmawati",1,"CAT 1"],
        ["Galih Permana",2,"Festival"],["Hana Safitri",2,"Tribune"],["Irfan Hakim",2,"Festival"],
        ["Joko Santoso",3,"Reguler"],["Kirana Larasati",3,"Reguler"],["Lukman Nulhakim",3,"VIP"],
        ["Maya Kusuma",4,"Reguler"],["Nanda Rizki",4,"Reguler"],["Oscar Wijaya",4,"VIP"],
        ["Putri Maharani",1,"Reguler"],["Rudi Hartono",1,"Reguler"]
      ];
      EXTRA_ATT.forEach(function(x, i){
        var evTarget = EVENTS[x[1] % EVENTS.length] || EVENTS[0];
        ATTENDEES.push({code:"NLT-" + (2001 + i), buyer:x[0], eventId:evTarget.id, cat:x[2], checkedIn:(i % 4 === 0)});
      });

      function attOf(evId){ return ATTENDEES.filter(function(a){ return String(a.eventId) === String(evId); }); }

      function renderCiEvents(){
        var sel = document.getElementById("ciEvent");
        if(!sel) return;
        var ids = [];
        ATTENDEES.forEach(function(a){ if(ids.indexOf(a.eventId) === -1) ids.push(a.eventId); });
        if(!ids.length && EVENTS.length){ ids = [EVENTS[0].id]; }
        sel.innerHTML = ids.map(function(id){
          var e = evById(id);
          return e ? '<option value="' + e.id + '">' + (e.name) + ' · ' + (e.dateLabel || '') + '</option>' : "";
        }).join("");
      }

      function renderCheckin(){
        var sel = document.getElementById("ciEvent");
        if(!sel) return;
        var evId = sel.value;
        var ev = evById(evId);
        var list = attOf(evId);
        var inCount = list.filter(function(a){ return a.checkedIn; }).length;
        document.getElementById("ciIn").textContent = inCount;
        document.getElementById("ciTotal").textContent = list.length;
        var p = list.length ? Math.round(inCount / list.length * 100) : 0;
        document.getElementById("ciPct").textContent = p + "%";
        document.getElementById("ciBar").style.width = p + "%";
        document.getElementById("ciStatSub").textContent = ev ? ev.name : "—";
        document.getElementById("ciLeft").textContent = (list.length - inCount) + " tiket belum check-in dari " + list.length + " tiket.";
        
        var q = (document.getElementById("ciSearch").value || "").toLowerCase();
        var rows = list.filter(function(a){ return a.buyer.toLowerCase().indexOf(q) > -1 || a.code.toLowerCase().indexOf(q) > -1; });
        var body = document.getElementById("ciTableBody");
        if(!rows.length){ body.innerHTML = '<tr class="empty-row"><td colspan="5">Tidak ada peserta yang cocok.</td></tr>'; return; }
        body.innerHTML = rows.map(function(a){
          return '<tr><td data-label="Kode Tiket"><b>' + a.code + '</b></td><td data-label="Pembeli">' + a.buyer + '</td><td data-label="Kategori Tiket">' + a.cat + '</td>' +
            '<td data-label="Status" class="no-label">' + (a.checkedIn ? '<span class="badge b-live">Sudah Check-in</span>' : '<span class="badge b-draft">Belum Check-in</span>') + '</td>' +
            '<td data-label="Aksi" class="no-label">' + (a.checkedIn ? '<span style="color:var(--muted);font-size:12.5px">—</span>' : '<button class="mini-btn" type="button" data-ci="' + a.code + '">Check-in Manual</button>') + '</td></tr>';
        }).join("");
      }

      function ciShowResult(kind, title, detail){
        var box = document.getElementById("ciResult");
        box.className = "res-box show " + (kind === "valid" ? "res-valid" : kind === "used" ? "res-used" : "res-invalid");
        box.innerHTML = "<b>" + title + "</b><p>" + detail + "</p>";
      }

      function doValidate(raw){
        var code = (raw || "").trim().toUpperCase();
        if(!code){ ciShowResult("invalid", "TIDAK VALID", "Kolom kode masih kosong — ketik atau scan kode tiket dulu."); return; }
        var evId = document.getElementById("ciEvent").value;
        var a = ATTENDEES.find(function(x){ return x.code.toUpperCase() === code; });
        if(!a){ ciShowResult("invalid", "TIDAK VALID", "Kode “" + code + "” tidak ditemukan di sistem."); return; }
        if(String(a.eventId) !== String(evId)){
          var other = evById(a.eventId);
          ciShowResult("invalid", "TIDAK VALID UNTUK EVENT INI", "Kode ini milik event “" + (other ? other.name : "lain") + "”, bukan event yang dipilih.");
          return;
        }
        if(a.checkedIn){
          ciShowResult("used", "SUDAH DIPAKAI", "Tiket " + a.code + " atas nama " + a.buyer + " (" + a.cat + ") sudah check-in sebelumnya.");
          return;
        }
        a.checkedIn = true;
        ciShowResult("valid", "VALID — SILAKAN MASUK", "<b>" + a.buyer + "</b> · " + a.cat + " · Kode " + a.code + " · Berhasil Check-in" + (document.getElementById("ciOffline").checked ? " (mode offline)" : ""));
        renderCheckin();
      }

      document.getElementById("ciValidate").addEventListener("click", function(){ doValidate(document.getElementById("ciCode").value); });
      document.getElementById("ciCode").addEventListener("keydown", function(e){ if(e.key === "Enter"){ e.preventDefault(); doValidate(this.value); } });
      document.getElementById("ciRandom").addEventListener("click", function(){
        var list = attOf(document.getElementById("ciEvent").value);
        if(!list.length) return;
        var a = list[Math.floor(Math.random() * list.length)];
        document.getElementById("ciCode").value = a.code;
        doValidate(a.code);
      });
      document.getElementById("ciEvent").addEventListener("change", function(){ document.getElementById("ciResult").className = "res-box"; renderCheckin(); });
      document.getElementById("ciSearch").addEventListener("input", renderCheckin);
      document.getElementById("ciTableBody").addEventListener("click", function(e){
        var b = e.target.closest("[data-ci]"); if(!b) return;
        document.getElementById("ciCode").value = b.getAttribute("data-ci");
        doValidate(b.getAttribute("data-ci"));
      });
      document.getElementById("ciOffline").addEventListener("change", function(){
        if(window.showToast) window.showToast(this.checked ? "Mode offline aktif — scan tetap berjalan lokal." : "Mode offline nonaktif.");
      });
      document.getElementById("ciExport").addEventListener("click", function(){
        var ev = evById(document.getElementById("ciEvent").value);
        var lines = ["Kode Tiket,Pembeli,Event,Kategori Tiket,Status Check-in"];
        attOf(document.getElementById("ciEvent").value).forEach(function(a){
          lines.push([a.code, '"' + a.buyer + '"', '"' + (ev ? ev.name : "") + '"', '"' + a.cat + '"', a.checkedIn ? "Sudah Check-in" : "Belum Check-in"].join(","));
        });
        var csv = "\uFEFF" + lines.join("\n");
        var blob = new Blob([csv], {type:"text/csv;charset=utf-8;"});
        var a = document.createElement("a");
        a.href = URL.createObjectURL(blob);
        a.download = "daftar-hadir-nova-live.csv";
        document.body.appendChild(a); a.click(); document.body.removeChild(a);
        if(window.showToast) window.showToast("Daftar hadir berhasil diunduh.");
      });

      renderCiEvents();
      renderCheckin();
    })();
  </script>
</body>
</html>
