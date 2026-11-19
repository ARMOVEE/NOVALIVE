<?php
/**
 * Halaman Laporan (Analitik, Log Aktivitas, Notifikasi) — Gaya Quixotic
 * NOVA LIVE MVC
 */
$currentUser = $currentUser ?? ['full_name' => 'Admin NOVA LIVE', 'role' => 'admin'];
$userInitials = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $currentUser['full_name'] ?? 'AD'), 0, 2));
if (strlen($userInitials) < 2) $userInitials = 'AD';

$activeTab = $activeTab ?? 'analitik';
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <title><?= e($pageTitle ?? 'NOVA LIVE — Laporan') ?></title>
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

    <?php if ($activeTab === 'logs'): ?>
    <!-- ========== VIEW: LOG AKTIVITAS ========== -->
    <section class="view active" id="view-log" aria-label="Log Aktivitas">
      <div class="page-head">
        <div>
          <h1>Log Aktivitas Admin</h1>
          <p>Jejak audit: siapa mengubah apa, dan kapan — termasuk perubahan dari semua menu dashboard</p>
        </div>
      </div>
      <div class="card">
        <div id="logList">
          <?php if (!empty($logs)): ?>
            <?php foreach ($logs as $l): ?>
              <div class="log-row">
                <div class="log-ava"><?= strtoupper(substr($l['user_name'] ?? 'AD', 0, 2)) ?></div>
                <div>
                  <p><b><?= e($l['user_name'] ?? 'Admin') ?></b> <?= e($l['description'] ?? $l['action']) ?></p>
                  <small><?= !empty($l['created_at']) ? date('d M Y, H.i', strtotime($l['created_at'])) : 'Baru saja' ?></small>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="log-row">
              <div class="log-ava">RN</div>
              <div><p><b>Rina</b> mengubah harga tiket “Tulus — Konser Tur Manusia” menjadi Rp350.000</p><small>Hari ini, 14.20</small></div>
            </div>
            <div class="log-row">
              <div class="log-ava">BG</div>
              <div><p><b>Bagas</b> menyetujui refund RF-2026-011 atas nama Bima Sakti</p><small>Hari ini, 13.05</small></div>
            </div>
            <div class="log-row">
              <div class="log-ava">AD</div>
              <div><p><b>Admin</b> mengaktifkan banner “LaLaLa Festival Jakarta 2027” di homepage</p><small>Kemarin, 20.14</small></div>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </section>

    <?php elseif ($activeTab === 'notifikasi'): ?>
    <!-- ========== VIEW: NOTIFIKASI KE PEMBELI ========== -->
    <section class="view active" id="view-notifikasi" aria-label="Notifikasi">
      <div class="page-head">
        <div>
          <h1>Notifikasi ke Pembeli</h1>
          <p>Kirim pengumuman ke semua pembeli atau pembeli event tertentu</p>
        </div>
      </div>
      <div class="grid-2">
        <div class="card">
          <div class="card-head"><div><h2>Kirim Pengumuman Baru</h2><p>Pilih penerima dan isi pesan</p></div></div>
          <form id="notifForm">
            <div class="form-grid">
              <div class="field full">
                <label for="nfTarget">Target Penerima</label>
                <select id="nfTarget">
                  <option value="semua">Semua Pembeli</option>
                  <option value="1">Pembeli Tulus — Konser Tur Manusia</option>
                  <option value="2">Pembeli Nadin Amizah — Live in Bandung</option>
                  <option value="3">Pembeli Midnight Jazz Night</option>
                </select>
              </div>
              <div class="field full">
                <label for="nfTpl">Gunakan Template Pesan</label>
                <select id="nfTpl">
                  <option value="">-- Pilih template cepat --</option>
                  <option value="jadwal">Perubahan Jadwal Acara</option>
                  <option value="venue">Perubahan Venue / Lokasi</option>
                  <option value="h1">Pengingat H-1 (Besok Acara Dimulai!)</option>
                  <option value="umum">Pengumuman Penting Umum</option>
                </select>
              </div>
              <div class="field full">
                <label for="nfTitle">Judul Pesan</label>
                <input id="nfTitle" type="text" placeholder="cth. Pengingat Jadwal Masuk Venue" required />
              </div>
              <div class="field full">
                <label for="nfMsg">Isi Pesan Notifikasi</label>
                <textarea id="nfMsg" rows="4" placeholder="Tulis pesan pengumuman..." required></textarea>
              </div>
            </div>
            <div style="margin-top:16px">
              <button class="btn-navy" type="submit" style="padding:10px 24px;border-radius:12px">Kirim Pengumuman Sekarang</button>
            </div>
          </form>
        </div>
        <div class="card">
          <div class="card-head"><div><h2>Riwayat Pengumuman Terkirim</h2></div></div>
          <div id="notifHistory">
            <div class="tx-row">
              <div class="tx-ic"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 2 11 13"/><path d="M22 2l-7 20-4-9-9-4z"/></svg></div>
              <div class="tx-main">
                <div class="tx-name">Pengingat: Konser Tulus tinggal menghitung hari</div>
                <div class="tx-sub">Halo, konser Tulus di Istora Senayan semakin dekat. Cek kembali e-tiketmu.</div>
              </div>
              <div class="tx-amt"><small>Semua Pembeli</small><small>17 Nov, 09.00</small></div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <?php else: ?>
    <!-- ========== VIEW: ANALITIK & LAPORAN ========== -->
    <section class="view active" id="view-analitik" aria-label="Analitik">
      <div class="page-head">
        <div>
          <h1>Analitik &amp; Laporan</h1>
          <p>Kata kunci pencarian di homepage dan laporan penjualan per kota &amp; kategori</p>
        </div>
      </div>
      <div class="grid-2" style="margin-bottom:18px">
        <div class="card">
          <div class="card-head"><div><h2>Kata Kunci Pencarian Homepage</h2><p>Pencarian paling sering oleh calon pembeli</p></div></div>
          <div id="kwList"></div>
        </div>
        <div class="card">
          <div class="card-head"><div><h2>Tren Pencarian 7 Hari</h2><p>Volume pencarian tiket harian di homepage</p></div></div>
          <div id="trendChart"></div>
        </div>
      </div>
      <div class="grid-2">
        <div class="card">
          <div class="card-head"><div><h2>Laporan per Kota</h2><p>Tiket terjual berdasarkan kota pelaksanaan event</p></div></div>
          <div id="cityReport">
            <div class="hbar-row"><span>Jakarta</span><div class="hbar-track"><div class="hbar-fill" style="width:100%"></div></div><b>5.410</b></div>
            <div class="hbar-row"><span>Bandung</span><div class="hbar-track"><div class="hbar-fill" style="width:45%"></div></div><b>2.450</b></div>
            <div class="hbar-row"><span>Yogyakarta</span><div class="hbar-track"><div class="hbar-fill" style="width:30%"></div></div><b>1.620</b></div>
            <div class="hbar-row"><span>Surabaya</span><div class="hbar-track"><div class="hbar-fill" style="width:25%"></div></div><b>1.340</b></div>
            <div class="hbar-row"><span>Bali</span><div class="hbar-track"><div class="hbar-fill" style="width:20%"></div></div><b>1.090</b></div>
          </div>
        </div>
        <div class="card">
          <div class="card-head"><div><h2>Laporan per Kategori</h2><p>Tiket terjual berdasarkan kategori event</p></div></div>
          <div id="catReport">
            <div class="hbar-row"><span>Musik</span><div class="hbar-track"><div class="hbar-fill" style="width:100%"></div></div><b>7.850</b></div>
            <div class="hbar-row"><span>Olahraga</span><div class="hbar-track"><div class="hbar-fill" style="width:32%"></div></div><b>2.510</b></div>
            <div class="hbar-row"><span>Festival</span><div class="hbar-track"><div class="hbar-fill" style="width:20%"></div></div><b>1.550</b></div>
          </div>
        </div>
      </div>
    </section>
    <?php endif; ?>

    <?php $shellFooter = true; require __DIR__ . '/sidebar.php'; ?>

  <!-- Script Khusus Laporan -->
  <script>
    (function(){
      <?php if ($activeTab === 'analitik'): ?>
      var SEARCH_KW = [
        ["tulus konser",1284],
        ["lalala festival",986],
        ["konser jakarta",874],
        ["festival balon bali",642],
        ["nadin amizah bandung",517],
        ["final basket surabaya",433]
      ];

      var SEARCH_TREND = {
        labels: ["Rab 11","Kam 12","Jum 13","Sab 14","Min 15","Sen 16","Sel 17"],
        values: [1240,1385,1290,1620,1480,1715,1590]
      };

      function renderKw(){
        var el = document.getElementById("kwList"); if(!el) return;
        el.innerHTML = SEARCH_KW.map(function(k, i){
          return '<div class="kw-row"><span class="kw-rank">' + (i + 1) + '</span><div style="flex:1;min-width:0"><b style="font-size:14px">“' + k[0] + '”</b><div class="progress" style="max-width:260px"><i style="width:' + Math.round(k[1] / SEARCH_KW[0][1] * 100) + '%"></i></div></div><b style="font-family:\'Plus Jakarta Sans\',sans-serif">' + k[1] + '×</b></div>';
        }).join("");
      }

      function renderTrend(){
        var el = document.getElementById("trendChart"); if(!el) return;
        var data = SEARCH_TREND, W = 720, H = 230, padL = 14, padB = 30, padT = 16;
        var max = Math.max.apply(null, data.values) * 1.15, n = data.values.length;
        var slot = (W - padL * 2) / n, bw = Math.min(46, slot * 0.55), base = H - padB, bars = "", labels = "", grid = "";
        [0.25, 0.5, 0.75, 1].forEach(function(f){ var y = padT + (base - padT) * (1 - f); grid += '<line x1="' + padL + '" y1="' + y + '" x2="' + (W - padL) + '" y2="' + y + '" stroke="#E7EBEE" stroke-width="1" stroke-dasharray="3 4"/>'; });
        data.values.forEach(function(v, i){
          var h = (base - padT) * (v / max), x = padL + slot * i + (slot - bw) / 2, y = base - h;
          bars += '<rect x="' + x.toFixed(1) + '" y="' + y.toFixed(1) + '" width="' + bw.toFixed(1) + '" height="' + h.toFixed(1) + '" rx="6" fill="' + (i === n - 1 ? "#D4B475" : "#23649A") + '" opacity="' + (i === n - 1 ? "1" : "0.85") + '"><title>' + data.labels[i] + ': ' + v + ' pencarian</title></rect>';
          bars += '<text x="' + (padL + slot * i + slot / 2).toFixed(1) + '" y="' + (y - 7).toFixed(1) + '" text-anchor="middle" font-size="11.5" font-weight="700" fill="#14232F" font-family="Plus Jakarta Sans,sans-serif">' + v + '</text>';
          labels += '<text x="' + (padL + slot * i + slot / 2).toFixed(1) + '" y="' + (H - 8) + '" text-anchor="middle" font-size="11.5" fill="#7A8794" font-family="Plus Jakarta Sans,sans-serif">' + data.labels[i] + '</text>';
        });
        el.innerHTML = '<svg viewBox="0 0 ' + W + ' ' + H + '" width="100%" role="img" aria-label="Grafik tren pencarian">' + grid + bars + labels + '<line x1="' + padL + '" y1="' + base + '" x2="' + (W - padL) + '" y2="' + base + '" stroke="#E7EBEE" stroke-width="1.5"/></svg>';
      }

      renderKw();
      renderTrend();

      <?php elseif ($activeTab === 'notifikasi'): ?>
      var TPL = {
        jadwal: { t: "Perubahan Jadwal Acara", m: "Halo, kami informasikan bahwa jadwal event mengalami perubahan waktu. Detail jadwal terbaru tercantum di e-tiket kamu." },
        venue: { t: "Perubahan Venue / Lokasi", m: "Halo, lokasi event mengalami penyesuaian tempat. Mohon datang 1 jam lebih awal untuk kelancaran check-in." },
        h1: { t: "Besok! Siapkan E-Tiketmu", m: "Halo, event berlangsung besok! Siapkan e-tiket dan tanda pengenalmu saat masuk di gate." },
        umum: { t: "Pengumuman Penting", m: "Halo, berikut informasi penting terbaru terkait penyelenggaraan event kamu." }
      };

      var tplSel = document.getElementById("nfTpl");
      if(tplSel){
        tplSel.addEventListener("change", function(){
          var key = this.value;
          if(TPL[key]){
            document.getElementById("nfTitle").value = TPL[key].t;
            document.getElementById("nfMsg").value = TPL[key].m;
          }
        });
      }

      var nForm = document.getElementById("notifForm");
      if(nForm){
        nForm.addEventListener("submit", function(e){
          e.preventDefault();
          var title = document.getElementById("nfTitle").value.trim();
          var msg = document.getElementById("nfMsg").value.trim();
          var hist = document.getElementById("notifHistory");
          if(hist){
            var item = document.createElement("div");
            item.className = "tx-row";
            item.innerHTML = '<div class="tx-ic"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 2 11 13"/><path d="M22 2l-7 20-4-9-9-4z"/></svg></div><div class="tx-main"><div class="tx-name">' + title + '</div><div class="tx-sub">' + msg + '</div></div><div class="tx-amt"><small>Penerima Terpilih</small><small>Baru saja</small></div>';
            hist.prepend(item);
          }
          if(window.showToast) window.showToast("Notifikasi berhasil dikirim ke pembeli.");
          nForm.reset();
        });
      }
      <?php endif; ?>
    })();
  </script>
</body>
</html>
