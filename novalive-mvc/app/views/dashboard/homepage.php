<?php
/**
 * Halaman Manajemen Homepage (Banner & Tampilan Homepage) — Gaya Quixotic
 * NOVA LIVE MVC
 */
$currentUser = $currentUser ?? ['full_name' => 'Admin NOVA LIVE', 'role' => 'admin'];
$userInitials = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $currentUser['full_name'] ?? 'AD'), 0, 2));
if (strlen($userInitials) < 2) $userInitials = 'AD';

$activeTab = $activeTab ?? 'banner';

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
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <title><?= e($pageTitle ?? 'NOVA LIVE — Manajemen Homepage') ?></title>
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

    <?php if ($activeTab === 'tampilan'): ?>
    <!-- ========== VIEW: TAMPILAN HOMEPAGE ========== -->
    <section class="view active" id="view-tampilan" aria-label="Tampilan Homepage">
      <div class="page-head">
        <div>
          <h1>Tampilan Homepage</h1>
          <p>Pilih dan urutkan event yang tampil di “Event Seru Untukmu” dan “Event Populer”</p>
        </div>
      </div>
      <div class="note-demo">Perubahan urutan dan spotlight tersimpan di sesi ini saja (demo). Klik Pratinjau Homepage untuk melihat hasilnya.</div>
      <div class="card" style="margin-bottom:18px">
        <div class="card-head"><div><h2>Event Spotlight (Sorotan Utama)</h2><p>Event yang ditempatkan di posisi paling atas dengan sorotan khusus 24 jam</p></div></div>
        <div id="spotArea"></div>
      </div>
      <div class="grid-2">
        <div class="card">
          <div class="card-head"><div><h2>Event Seru Untukmu</h2><p id="seruCount">10 baris di homepage</p></div></div>
          <div id="seruList"></div>
          <div id="seruPool"></div>
        </div>
        <div class="card">
          <div class="card-head"><div><h2>Event Populer</h2><p id="popCount">4 baris di section Event Populer</p></div></div>
          <div id="popList"></div>
          <div id="popPool"></div>
        </div>
      </div>
    </section>

    <?php else: ?>
    <!-- ========== VIEW: BANNER HOMEPAGE ========== -->
    <section class="view active" id="view-banner" aria-label="Banner">
      <div class="page-head">
        <div>
          <h1>Banner Homepage</h1>
          <p>Kelola slide promo di bagian paling atas homepage, lengkap dengan penjadwalan tayang</p>
        </div>
        <button class="btn-primary" type="button" id="addBannerBtn" style="flex:none">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
          Tambah Slide
        </button>
      </div>
      <div class="note-demo">Perubahan banner tersimpan di sesi ini saja (demo). Slide dengan jadwal tayang hanya muncul di homepage selama rentang tanggalnya — cek hasilnya lewat Pratinjau Homepage.</div>
      <div id="bannerList"></div>
    </section>
    <?php endif; ?>

    <?php $shellFooter = true; require __DIR__ . '/sidebar.php'; ?>

  <!-- Script Khusus Homepage -->
  <script>
    (function(){
      var DB_EVENTS = <?= json_encode($eventsData, JSON_UNESCAPED_SLASHES) ?>;
      var MOCK_EVENTS = [
        {id:1, name:"Tulus — Konser Tur Manusia", cat:"Musik", venue:"Istora Senayan", city:"Jakarta", color:"#23649A", dateLabel:"Min, 6 Des 2026", status:"tayang"},
        {id:2, name:"Nadin Amizah — Live in Bandung", cat:"Musik", venue:"The Hall", city:"Bandung", color:"#509FC8", dateLabel:"Jum, 11 Des 2026", status:"tayang"},
        {id:3, name:"Ghea Indrawari — Live in Jogja", cat:"Musik", venue:"GOR Among Rogo", city:"Yogyakarta", color:"#EC4899", dateLabel:"Sab, 19 Des 2026", status:"tayang"},
        {id:4, name:"Fun Run 10K Semarak Merdeka", cat:"Olahraga", venue:"Monas", city:"Jakarta", color:"#0E3254", dateLabel:"Min, 27 Des 2026", status:"tayang"},
        {id:5, name:"Festival Balon Udara Bali", cat:"Pameran", venue:"Nusa Dua", city:"Bali", color:"#10B981", dateLabel:"Sab, 16 Jan 2027", status:"tayang"},
        {id:6, name:"LaLaLa Festival Jakarta 2027", cat:"Festival", venue:"JIExpo", city:"Jakarta", color:"#F59E0B", dateLabel:"Sab, 13 Feb 2027", status:"tayang"}
      ];

      var EVENTS = DB_EVENTS.length ? DB_EVENTS : MOCK_EVENTS;
      function evById(id){ return EVENTS.find(function(e){ return String(e.id) === String(id); }) || EVENTS[0]; }
      function initials(name){ return (name || "NV").split(" ").map(function(w){ return w[0]; }).slice(0, 2).join("").toUpperCase(); }

      <?php if ($activeTab === 'tampilan'): ?>
      /* ===== Tampilan Homepage Logic ===== */
      var SERU = EVENTS.map(function(e){ return e.id; });
      var POP = EVENTS.slice(0, 4).map(function(e){ return e.id; });
      var SPOT = null;

      function ordRow(listName, idx, id){
        var e = evById(id); if(!e) return "";
        var isSpot = SPOT && SPOT.id === id && listName === "seru" && idx === 0;
        return '<div class="ord-row" draggable="true" data-list="' + listName + '" data-idx="' + idx + '">' +
          '<span class="pos">' + (idx + 1) + '</span>' +
          '<div class="ev-thumb" style="background:' + (e.color || '#23649A') + ';width:38px;height:38px;font-size:12px">' + initials(e.name) + '</div>' +
          '<div style="flex:1;min-width:130px"><div class="ev-name">' + e.name + '</div><div class="ev-sub">' + (e.city || 'Kota') + ' · ' + (e.dateLabel || '') + '</div></div>' +
          (isSpot ? '<span class="spot-badge">SPOTLIGHT</span>' : "") +
          '<span class="ord-btns">' +
            '<button class="mini-btn" type="button" data-move="-1" data-list="' + listName + '" data-idx="' + idx + '" aria-label="Naikkan">↑</button>' +
            '<button class="mini-btn" type="button" data-move="1" data-list="' + listName + '" data-idx="' + idx + '" aria-label="Turunkan">↓</button>' +
            '<button class="mini-btn warn" type="button" data-remove="' + listName + '" data-idx="' + idx + '" aria-label="Keluarkan">✕</button>' +
          '</span>' +
        '</div>';
      }

      function renderTampilan(){
        var sl = document.getElementById("seruList"), pl = document.getElementById("popList");
        if(sl) sl.innerHTML = SERU.map(function(id, i){ return ordRow("seru", i, id); }).join("") || '<p style="color:var(--muted);font-size:13px">Belum ada event dipilih.</p>';
        if(pl) pl.innerHTML = POP.map(function(id, i){ return ordRow("pop", i, id); }).join("") || '<p style="color:var(--muted);font-size:13px">Belum ada event dipilih.</p>';
        renderSpot();
      }

      function renderSpot(){
        var area = document.getElementById("spotArea"); if(!area) return;
        if(SPOT){
          var e = evById(SPOT.id);
          area.innerHTML = '<div style="margin-top:14px;display:flex;gap:14px;align-items:center;flex-wrap:wrap"><span class="spot-badge">SPOTLIGHT AKTIF</span><b style="font-size:15px">' + (e ? e.name : "") + '</b><button class="btn-outline btn-sm" type="button" id="spotCancel">Akhiri Spotlight</button></div>';
          var c = document.getElementById("spotCancel");
          if(c) c.addEventListener("click", function(){ SPOT = null; renderTampilan(); if(window.showToast) window.showToast("Spotlight diakhiri."); });
        } else {
          area.innerHTML = '<div style="margin-top:14px;display:flex;gap:10px;flex-wrap:wrap;align-items:center">' +
            '<select id="spotSel" aria-label="Pilih event spotlight">' +
            EVENTS.map(function(x){ return '<option value="' + x.id + '">' + x.name + '</option>'; }).join("") +
            '</select><button class="btn-gold" type="button" id="spotGo">Aktifkan Spotlight 24 Jam</button></div>';
          var go = document.getElementById("spotGo");
          if(go){
            go.addEventListener("click", function(){
              var sel = document.getElementById("spotSel");
              var id = sel ? parseInt(sel.value, 10) : EVENTS[0].id;
              SPOT = {id: id};
              var ix = SERU.indexOf(id);
              if(ix > 0){ SERU.splice(ix, 1); SERU.unshift(id); }
              if(ix === -1){ SERU.unshift(id); }
              renderTampilan();
              if(window.showToast) window.showToast("Spotlight diaktifkan.");
            });
          }
        }
      }

      document.addEventListener("click", function(e){
        var mv = e.target.closest("[data-move]"), rm = e.target.closest("[data-remove]");
        if(mv){
          var listName = mv.getAttribute("data-list");
          var arr = listName === "seru" ? SERU : POP;
          var i = parseInt(mv.getAttribute("data-idx"), 10);
          var j = i + parseInt(mv.getAttribute("data-move"), 10);
          if(j >= 0 && j < arr.length){
            var temp = arr[i]; arr[i] = arr[j]; arr[j] = temp;
            renderTampilan();
          }
        }
        if(rm){
          var listName2 = rm.getAttribute("data-list");
          var arr2 = listName2 === "seru" ? SERU : POP;
          arr2.splice(parseInt(rm.getAttribute("data-idx"), 10), 1);
          renderTampilan();
          if(window.showToast) window.showToast("Event dikeluarkan dari daftar.");
        }
      });

      renderTampilan();

      <?php else: ?>
      /* ===== Banner Homepage Logic ===== */
      var BANNERS = [
        {id:1, title:"LaLaLa Festival Jakarta 2027", sub:"Festival musik terbesar awal tahun — JIExpo Kemayoran, Sabtu 13 Feb 2027", cta:"Beli Tiket", link:"#event", img:"", color:"#15497A", active:true, start:"2026-11-01", end:"2027-02-13"},
        {id:2, title:"Tulus — Konser Tur Manusia", sub:"Istora Senayan, Minggu 6 Des 2026 · 20.00 WIB — tampil 1 minggu sebelum konser", cta:"Beli Tiket", link:"#event", img:"", color:"#0E3254", active:true, start:"2026-11-29", end:"2026-12-06"},
        {id:3, title:"Festival Balon Udara Bali", sub:"Terbang di atas Lapangan Nusa Dua, Sabtu 16 Jan 2027", cta:"Lihat Event", link:"#event", img:"", color:"#23649A", active:true, start:"2026-12-20", end:"2027-01-16"}
      ];

      function renderBanners(){
        var list = document.getElementById("bannerList");
        if(!list) return;
        list.innerHTML = BANNERS.map(function(b, i){
          var bg = b.img ? 'background-image:url(' + b.img + ')' : 'background:linear-gradient(120deg,' + b.color + ',#0E3254)';
          return '<div class="card" style="margin-bottom:18px">' +
            '<div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:14px">' +
              '<h2 style="font-size:16px;margin:0">Slide ' + (i + 1) + '</h2>' +
              '<span class="badge ' + (b.active ? 'b-live' : 'b-draft') + '">' + (b.active ? 'Sedang Tayang' : 'Nonaktif') + '</span>' +
              '<span style="margin-left:auto;display:flex;gap:6px;flex-wrap:wrap">' +
                '<button class="mini-btn" type="button" data-bn-up="' + i + '"' + (i === 0 ? " disabled" : "") + '>↑ Naik</button>' +
                '<button class="mini-btn" type="button" data-bn-down="' + i + '"' + (i === BANNERS.length - 1 ? " disabled" : "") + '>↓ Turun</button>' +
                '<button class="mini-btn warn" type="button" data-bn-del="' + i + '">Hapus Slide</button>' +
              '</span>' +
            '</div>' +
            '<div class="slide-prev" style="' + bg + '"><h3>' + b.title + '</h3><p>' + b.sub + '</p><span class="pv-cta">' + (b.cta || "Lihat") + '</span></div>' +
            '<div class="form-grid" style="margin-top:16px">' +
              '<div class="field"><label>Judul Promo</label><input type="text" data-bn="title" data-i="' + i + '" value="' + b.title + '"/></div>' +
              '<div class="field"><label>Teks Tombol (CTA)</label><input type="text" data-bn="cta" data-i="' + i + '" value="' + b.cta + '"/></div>' +
              '<div class="field full"><label>Subjudul / Deskripsi</label><input type="text" data-bn="sub" data-i="' + i + '" value="' + b.sub + '"/></div>' +
              '<div class="field"><label>Warna Latar</label><input type="color" class="color-inp" style="width:100%" data-bn="color" data-i="' + i + '" value="' + b.color + '"/></div>' +
            '</div>' +
            '<div class="switch-row"><div class="txt"><b>Slide Aktif</b><span>Slide nonaktif tidak tampil di homepage</span></div><label class="switch"><input type="checkbox" data-bn-active="' + i + '"' + (b.active ? " checked" : "") + '/><span class="tr"></span></label></div>' +
            '<div style="margin-top:12px"><button class="btn-navy" type="button" data-bn-save="' + i + '" style="padding:9px 20px;border-radius:12px">Simpan Slide</button></div>' +
          '</div>';
        }).join("");
      }

      var addBtn = document.getElementById("addBannerBtn");
      if(addBtn){
        addBtn.addEventListener("click", function(){
          BANNERS.push({id: Date.now(), title: "Promo Spesial", sub: "Deskripsi promo baru", cta: "Lihat Event", link: "#", color: "#15497A", active: true});
          renderBanners();
          if(window.showToast) window.showToast("Slide banner baru ditambahkan.");
        });
      }

      document.addEventListener("click", function(e){
        var up = e.target.closest("[data-bn-up]"), down = e.target.closest("[data-bn-down]"), del = e.target.closest("[data-bn-del]"), save = e.target.closest("[data-bn-save]");
        if(up){
          var i = parseInt(up.getAttribute("data-bn-up"), 10);
          if(i > 0){ var t = BANNERS[i]; BANNERS[i] = BANNERS[i-1]; BANNERS[i-1] = t; renderBanners(); }
        }
        if(down){
          var j = parseInt(down.getAttribute("data-bn-down"), 10);
          if(j < BANNERS.length - 1){ var t2 = BANNERS[j]; BANNERS[j] = BANNERS[j+1]; BANNERS[j+1] = t2; renderBanners(); }
        }
        if(del){
          var k = parseInt(del.getAttribute("data-bn-del"), 10);
          BANNERS.splice(k, 1);
          renderBanners();
          if(window.showToast) window.showToast("Slide banner dihapus.");
        }
        if(save){
          if(window.showToast) window.showToast("Perubahan banner berhasil disimpan.");
        }
      });

      renderBanners();
      <?php endif; ?>
    })();
  </script>
</body>
</html>
