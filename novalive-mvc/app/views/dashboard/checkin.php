<?php
/**
 * Halaman Check-in Tiket (Menu Pesanan — Tab Check-in)
 * NOVA LIVE MVC — Tahap 2
 */
$currentUser = $currentUser ?? ['full_name' => 'Admin NOVA LIVE', 'role' => 'admin'];
$userInitials = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $currentUser['full_name'] ?? 'AD'), 0, 2));
if (strlen($userInitials) < 2) $userInitials = 'AD';

$events = $events ?? [];
$selectedEventId = (int)($selectedEventId ?? 0);
$checkinStats = $checkinStats ?? ['checked_in' => 0, 'total_tickets' => 0, 'percentage' => 0];
$todayCheckins = $todayCheckins ?? [];

$flashSuccess = flash('success');
$flashError = flash('error');
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <meta name="color-scheme" content="light" />
  <link rel="icon" href="data:," />
  <title><?= e($pageTitle ?? 'NOVA LIVE — Check-in Tiket') ?></title>
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
      --green-bg:#E4F4EA;
      --red:#DC2626;
      --red-bg:#FDECEC;
      --radius:22px;
      --shadow:0 1px 2px rgba(14,50,84,.04),0 10px 30px rgba(14,50,84,.05);
      color-scheme:light;
    }
    *{box-sizing:border-box}
    html,body{margin:0;padding:0}
    body{
      font-family:'Inter',system-ui,sans-serif;
      background:var(--panel);color:var(--ink);
      min-height:100vh;margin:0;padding:0;
    }
    a{color:inherit;text-decoration:none}
    button{font-family:inherit;background:none;border:none;padding:0;cursor:pointer;color:inherit}

    .card{background:var(--card);border-radius:var(--radius);box-shadow:var(--shadow);padding:22px;margin-bottom:18px}
    .card-head{display:flex;align-items:flex-start;gap:10px;margin-bottom:16px}
    .card-head h2{font-size:16px;font-weight:700;margin:0;color:var(--ink)}
    .card-head p{margin:3px 0 0;font-size:12.5px;color:var(--muted)}
    .grid-2{display:grid;grid-template-columns:1.2fr 1fr;gap:18px;margin-bottom:18px}
    @media (max-width:960px){.grid-2{grid-template-columns:1fr}}

    .page-head{display:flex;align-items:flex-start;gap:14px;flex-wrap:wrap;margin:2px 2px 18px}
    .page-head h1{font-size:24px;margin:0;color:var(--ink);letter-spacing:-.2px;font-family:'Plus Jakarta Sans',sans-serif;font-weight:800}
    .page-head p{margin:4px 0 0;font-size:13px;color:var(--muted)}

    .field{margin-bottom:14px}
    .field label{display:block;font-size:12.5px;font-weight:600;margin-bottom:6px;color:var(--ink)}
    .field input,.field select{
      width:100%;border:1.5px solid var(--line);border-radius:12px;padding:10px 14px;font-size:14px;
      font-family:inherit;color:var(--ink);background:#FBFCFD;min-height:44px;outline:none;
    }
    .field input:focus,.field select:focus{border-color:var(--blue)}

    .scan-row{display:flex;gap:10px;align-items:center;flex-wrap:wrap;margin-top:6px}
    .scan-row input{flex:1;min-width:180px;border:1.5px solid var(--line);border-radius:12px;padding:10px 14px;font-size:15px;font-family:monospace;letter-spacing:1px;font-weight:700;text-transform:uppercase}
    .scan-row input:focus{border-color:var(--blue);outline:none}

    .btn-navy{
      display:inline-flex;align-items:center;justify-content:center;gap:7px;background:var(--navy);color:#fff;
      border-radius:12px;padding:10px 22px;font-size:13.5px;font-weight:700;min-height:44px;font-family:'Plus Jakarta Sans',sans-serif;
      transition:background .15s;border:none;cursor:pointer;
    }
    .btn-navy:hover{background:var(--navy-2)}
    .btn-ghost{
      display:inline-flex;align-items:center;justify-content:center;gap:7px;background:#F1F3F4;color:var(--navy);
      border-radius:12px;padding:10px 18px;font-size:13px;font-weight:600;min-height:44px;border:1.5px solid var(--line);cursor:pointer;
    }
    .btn-ghost:hover{background:#E2E8F0}
    .btn-outline{
      display:inline-flex;align-items:center;gap:6px;padding:8px 14px;border-radius:10px;font-size:12.5px;font-weight:600;
      border:1.5px solid var(--line);background:#fff;color:var(--navy);cursor:pointer;
    }
    .btn-outline:hover{background:#F8FAFC}

    .stat-grid{display:grid;grid-template-columns:repeat(3, 1fr);gap:12px;margin-bottom:14px}
    .stat-box{background:#F8FAFC;border:1px solid var(--line);border-radius:14px;padding:12px 14px;text-align:center}
    .stat-box b{display:block;font-size:22px;font-family:'Plus Jakarta Sans',sans-serif;color:var(--navy);font-weight:800}
    .stat-box span{display:block;font-size:11.5px;color:var(--muted);margin-top:2px;font-weight:500}

    .progress{background:#E7EBEE;border-radius:99px;overflow:hidden;height:10px;position:relative}
    .progress i{display:block;height:100%;background:var(--blue);border-radius:99px;transition:width .4s ease}

    .table-tools{display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-bottom:14px}
    .table-tools h2{font-size:16px;font-weight:700;margin:0;color:var(--ink)}
    .mini-search{display:flex;align-items:center;gap:8px;background:#F1F3F4;border-radius:999px;padding:6px 14px;border:1px solid var(--line)}
    .mini-search input{border:none;background:transparent;outline:none;font-size:13px;color:var(--ink);width:180px}

    .tbl-scroll{overflow-x:auto}
    .tbl{width:100%;border-collapse:collapse;font-size:13.5px}
    .tbl th{text-align:left;padding:11px 14px;background:#F8FAFC;color:var(--muted);font-weight:600;font-size:12px;border-bottom:1.5px solid var(--line);letter-spacing:.3px;text-transform:uppercase}
    .tbl td{padding:12px 14px;border-bottom:1px solid var(--line);vertical-align:middle}
    .tbl tbody tr:hover{background:#FAFCFE}

    .badge{display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:999px;font-size:11.5px;font-weight:700}
    .badge-success{background:var(--green-bg);color:var(--green)}
    .badge-info{background:#E0F2FE;color:#0369A1}

    .alert-box{padding:12px 16px;border-radius:14px;margin-bottom:16px;display:flex;align-items:center;gap:10px;font-size:13.5px;font-weight:600}
    .alert-success{background:var(--green-bg);color:var(--green);border:1px solid #B8E4C9}
    .alert-error{background:var(--red-bg);color:var(--red);border:1px solid #F9C2C2}

    .note-demo{background:#F0F6FB;border:1px solid #D1E5F5;border-radius:14px;padding:11px 16px;font-size:12.5px;color:#1B5383;line-height:1.5;margin-bottom:18px}
    .switch-row{display:flex;align-items:center;justify-content:space-between;padding-top:10px;border-top:1px solid var(--line)}
    .switch-row .txt b{display:block;font-size:13px;color:var(--ink)}
    .switch-row .txt span{display:block;font-size:11.5px;color:var(--muted)}
    .switch{position:relative;display:inline-block;width:44px;height:24px}
    .switch input{opacity:0;width:0;height:0}
    .tr{position:absolute;cursor:pointer;top:0;left:0;right:0;bottom:0;background-color:#ccc;transition:.2s;border-radius:24px}
    .tr:before{position:absolute;content:"";height:18px;width:18px;left:3px;bottom:3px;background-color:white;transition:.2s;border-radius:50%}
    input:checked + .tr{background-color:var(--blue)}
    input:checked + .tr:before{transform:translateX(20px)}
  </style>
</head>
<body>
  <div class="frame">
    <?php require __DIR__ . '/sidebar.php'; ?>

    <section class="view active" id="view-checkin" aria-label="Check-in Tiket">
      <div class="page-head">
        <div>
          <h1>Check-in Tiket</h1>
          <p>Validasi tiket di pintu masuk dan kelola daftar hadir peserta per event</p>
        </div>
      </div>

      <?php if (!empty($flashSuccess)): ?>
        <div class="alert-box alert-success" role="alert">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
          <?= e($flashSuccess) ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($flashError)): ?>
        <div class="alert-box alert-error" role="alert">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
          <?= e($flashError) ?>
        </div>
      <?php endif; ?>

      <div class="note-demo">
        <b>Sistem Check-in NOVA LIVE:</b> Masukkan kode tiket (format <code>NVL-XXXXXX</code>) atau gunakan barcode/QR scanner di pintu gerbang. Tiket yang valid akan dicatat di log aktivitas dan ditandai waktu check-in resminya.
      </div>

      <div class="grid-2">
        <!-- Scan / Ketik Form -->
        <div class="card">
          <div class="card-head">
            <div>
              <h2>Scan / Ketik Kode Tiket</h2>
              <p>Pilih event di pintu masuk, lalu validasi kode tiket pembeli</p>
            </div>
          </div>

          <form method="POST" action="<?= base_url('dashboard/orders/checkin') ?>" id="checkinForm">
            <?= csrf_field() ?>
            <div class="field">
              <label for="ciEventSelect">Pintu Masuk Event</label>
              <select name="event_id" id="ciEventSelect">
                <option value="0">Semua Event (Pintu Masuk Terpadu)</option>
                <?php foreach ($events as $ev): ?>
                  <option value="<?= (int)$ev['id'] ?>" <?= $selectedEventId === (int)$ev['id'] ? 'selected' : '' ?>>
                    <?= e($ev['title']) ?> (<?= tanggal($ev['event_date']) ?>)
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="field">
              <label for="ciCode">Kode Tiket</label>
              <div class="scan-row">
                <input type="text" name="ticket_code" id="ciCode" placeholder="Contoh: NVL-A1B2C3" required autocomplete="off" autofocus />
                <button class="btn-navy" type="submit" id="ciValidateBtn">Validasi</button>
                <button class="btn-ghost" type="button" id="ciRandomBtn">Simulasi Scan QR</button>
              </div>
            </div>
          </form>

          <p style="color:var(--muted);font-size:12px;margin:12px 0 0;line-height:1.55">
            Tekan <b>Enter</b> setelah scan. Sistem otomatis memverifikasi keaslian kode di database, mencegah penggunaan tiket ganda.
          </p>

          <div class="switch-row" style="margin-top:14px">
            <div class="txt">
              <b>Mode Offline</b>
              <span>Scan tetap berjalan tanpa sambungan internet (simulasi demo terpadu)</span>
            </div>
            <label class="switch">
              <input type="checkbox" id="ciOffline" />
              <span class="tr"></span>
            </label>
          </div>
        </div>

        <!-- Statistik Kehadiran -->
        <div class="card">
          <div class="card-head">
            <div>
              <h2>Statistik Check-in</h2>
              <p>Ringkasan kehadiran peserta tiket terjual</p>
            </div>
          </div>

          <div class="stat-grid">
            <div class="stat-box">
              <b><?= (int)$checkinStats['checked_in'] ?></b>
              <span>Sudah Check-in</span>
            </div>
            <div class="stat-box">
              <b><?= (int)$checkinStats['total_tickets'] ?></b>
              <span>Total Tiket Terbit</span>
            </div>
            <div class="stat-box">
              <b><?= $checkinStats['percentage'] ?>%</b>
              <span>Persentase Hadir</span>
            </div>
          </div>

          <div class="progress" style="margin:16px 0 8px">
            <i style="width:<?= min(100, max(0, $checkinStats['percentage'])) ?>%"></i>
          </div>
          <p style="color:var(--muted);font-size:12.5px;margin:8px 0 0">
            <b><?= (int)$checkinStats['checked_in'] ?></b> dari <b><?= (int)$checkinStats['total_tickets'] ?></b> tiket telah berhasil masuk ke venue.
          </p>
        </div>
      </div>

      <!-- Tabel Riwayat Check-in Hari Ini -->
      <div class="card">
        <div class="table-tools">
          <div>
            <h2>Daftar Hadir Check-in</h2>
            <p style="margin:2px 0 0;font-size:12.5px;color:var(--muted)">Daftar peserta yang telah melakukan validasi tiket hari ini</p>
          </div>
          <div style="display:flex;gap:10px;align-items:center;">
            <div class="mini-search">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/></svg>
              <input type="search" id="ciSearch" placeholder="Cari nama / kode tiket…" aria-label="Cari daftar hadir" />
            </div>
            <button class="btn-outline" type="button" id="ciExportBtn">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v12M7 10l5 5 5-5M4 21h16"/></svg>
              Export CSV
            </button>
          </div>
        </div>

        <div class="tbl-scroll">
          <table class="tbl">
            <thead>
              <tr>
                <th scope="col">Kode Tiket</th>
                <th scope="col">Nama Pembeli</th>
                <th scope="col">Event</th>
                <th scope="col">Jumlah</th>
                <th scope="col">Waktu Check-in</th>
                <th scope="col">Status</th>
              </tr>
            </thead>
            <tbody id="ciTableBody">
              <?php if (empty($todayCheckins)): ?>
                <tr class="empty-row" id="ciEmptyRow">
                  <td colspan="6" style="text-align:center;padding:24px;color:var(--muted)">
                    Belum ada tiket yang check-in hari ini. Gunakan kolom input di atas untuk memulai validasi.
                  </td>
                </tr>
              <?php else: ?>
                <?php foreach ($todayCheckins as $chk): ?>
                  <tr class="chk-row" data-code="<?= strtolower(e($chk['ticket_code'] ?? '')) ?>" data-name="<?= strtolower(e($chk['buyer_name'] ?? '')) ?>">
                    <td><code style="font-weight:700;background:#F1F5F9;padding:3px 8px;border-radius:6px;color:var(--navy)"><?= e($chk['ticket_code']) ?></code></td>
                    <td>
                      <div style="font-weight:600;color:var(--ink)"><?= e($chk['buyer_name']) ?></div>
                      <div style="font-size:11.5px;color:var(--muted)"><?= e($chk['buyer_email']) ?></div>
                    </td>
                    <td>
                      <div style="font-weight:500;"><?= e($chk['event_title']) ?></div>
                      <div style="font-size:11.5px;color:var(--muted)"><?= e($chk['venue']) ?></div>
                    </td>
                    <td><?= (int)$chk['qty'] ?> tiket</td>
                    <td><?= !empty($chk['checked_in_at']) ? date('d M Y, H:i', strtotime($chk['checked_in_at'])) . ' WIB' : '-' ?></td>
                    <td><span class="badge badge-success">✓ Sudah Hadir</span></td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </section>

    <?php $shellFooter = true; require __DIR__ . '/sidebar.php'; ?>
  </div>

  <script>
    (function(){
      // Switch event dropdown filter
      var selEvent = document.getElementById('ciEventSelect');
      if (selEvent) {
        selEvent.addEventListener('change', function() {
          var evId = selEvent.value;
          window.location.href = "<?= base_url('dashboard/orders/checkin') ?>" + (evId !== "0" ? "?event_id=" + evId : "");
        });
      }

      // Simulasi Scan QR
      var randomBtn = document.getElementById('ciRandomBtn');
      var codeInput = document.getElementById('ciCode');
      var demoCodes = ['NVL-A1B2C3', 'NVL-D4E5F6', 'NVL-J1K2L3', 'NVL-S1T2U3', 'NVL-P7Q8R9'];
      if (randomBtn && codeInput) {
        randomBtn.addEventListener('click', function() {
          var pick = demoCodes[Math.floor(Math.random() * demoCodes.length)];
          codeInput.value = pick;
          codeInput.focus();
        });
      }

      // Live search daftar hadir
      var searchInput = document.getElementById('ciSearch');
      var rows = document.querySelectorAll('#ciTableBody .chk-row');
      if (searchInput && rows.length > 0) {
        searchInput.addEventListener('input', function() {
          var q = (searchInput.value || '').trim().toLowerCase();
          rows.forEach(function(r) {
            var c = r.getAttribute('data-code') || '';
            var n = r.getAttribute('data-name') || '';
            var match = !q || c.indexOf(q) > -1 || n.indexOf(q) > -1;
            r.style.display = match ? '' : 'none';
          });
        });
      }

      // Export CSV
      var exportBtn = document.getElementById('ciExportBtn');
      if (exportBtn && rows.length > 0) {
        exportBtn.addEventListener('click', function() {
          var csv = ["Kode Tiket,Nama Pembeli,Event,Jumlah Tiket,Waktu Check-in,Status"];
          rows.forEach(function(r) {
            if (r.style.display !== 'none') {
              var cols = r.querySelectorAll('td');
              if (cols.length >= 6) {
                var line = [
                  '"' + cols[0].innerText.trim() + '"',
                  '"' + cols[1].querySelector('div').innerText.trim() + '"',
                  '"' + cols[2].querySelector('div').innerText.trim() + '"',
                  '"' + cols[3].innerText.trim() + '"',
                  '"' + cols[4].innerText.trim() + '"',
                  '"' + cols[5].innerText.trim() + '"'
                ];
                csv.push(line.join(','));
              }
            }
          });
          var blob = new Blob(["\uFEFF" + csv.join("\n")], { type: "text/csv;charset=utf-8;" });
          var link = document.createElement("a");
          var url = URL.createObjectURL(blob);
          link.setAttribute("href", url);
          link.setAttribute("download", "daftar_hadir_checkin_" + (new Date().toISOString().slice(0, 10)) + ".csv");
          link.style.visibility = "hidden";
          document.body.appendChild(link);
          link.click();
          document.body.removeChild(link);
        });
      }
    })();
  </script>
</body>
</html>
