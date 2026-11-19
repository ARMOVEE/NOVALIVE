<?php
/**
 * Halaman E-Tiket & Dokumen (Menu Pesanan — Tab E-Tiket / Topbar Dokumen)
 * NOVA LIVE MVC — Tahap 2
 */
$currentUser = $currentUser ?? ['full_name' => 'Admin NOVA LIVE', 'role' => 'admin'];
$userInitials = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $currentUser['full_name'] ?? 'AD'), 0, 2));
if (strlen($userInitials) < 2) $userInitials = 'AD';

$orders = $data ?? ($orders ?? []);
$events = $events ?? [];
$filters = $filters ?? [];
$total = $total ?? count($orders);
$currentPage = $currentPage ?? 1;
$totalPages = $totalPages ?? 1;

$flash = $flash ?? ($_SESSION['flash'] ?? []);
$flashSuccess = $flash['success'] ?? null;
$flashError = $flash['danger'] ?? ($flash['error'] ?? null);
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <meta name="color-scheme" content="light" />
  <link rel="icon" href="data:," />
  <title><?= e($pageTitle ?? 'NOVA LIVE — Pratinjau E-Tiket') ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="<?= base_url('css/dashboard.css') ?>" />
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

    .page-head{display:flex;align-items:flex-start;gap:14px;flex-wrap:wrap;margin:2px 2px 18px}
    .page-head h1{font-size:24px;margin:0;color:var(--ink);letter-spacing:-.2px;font-family:'Plus Jakarta Sans',sans-serif;font-weight:800}
    .page-head p{margin:4px 0 0;font-size:13px;color:var(--muted)}

    .table-tools{display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-bottom:14px}
    .mini-search{display:flex;align-items:center;gap:8px;background:#F1F3F4;border-radius:999px;padding:6px 14px;border:1px solid var(--line)}
    .mini-search input{border:none;background:transparent;outline:none;font-size:13px;color:var(--ink);width:220px}

    .tbl-scroll{overflow-x:auto}
    .tbl{width:100%;border-collapse:collapse;font-size:13.5px}
    .tbl th{text-align:left;padding:11px 14px;background:#F8FAFC;color:var(--muted);font-weight:600;font-size:12px;border-bottom:1.5px solid var(--line);letter-spacing:.3px;text-transform:uppercase}
    .tbl td{padding:12px 14px;border-bottom:1px solid var(--line);vertical-align:middle}
    .tbl tbody tr:hover{background:#FAFCFE}

    .badge{display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:999px;font-size:11.5px;font-weight:700}
    .badge-success{background:var(--green-bg);color:var(--green)}
    .badge-gray{background:#F1F5F9;color:#475569}

    .btn-sm-navy{
      background:var(--navy);color:#fff;border-radius:8px;padding:6px 14px;font-size:12px;font-weight:700;border:none;cursor:pointer;
      display:inline-flex;align-items:center;gap:5px;transition:background .15s;text-decoration:none;
    }
    .btn-sm-navy:hover{background:var(--navy-2)}
    .btn-sm-ghost{
      background:#F1F3F4;color:var(--navy);border-radius:8px;padding:6px 12px;font-size:12px;font-weight:600;border:1.5px solid var(--line);cursor:pointer;
      display:inline-flex;align-items:center;gap:4px;
    }
    .btn-sm-ghost:hover{background:#E2E8F0}
    .btn-outline{
      display:inline-flex;align-items:center;gap:6px;padding:8px 14px;border-radius:10px;font-size:12.5px;font-weight:600;
      border:1.5px solid var(--line);background:#fff;color:var(--navy);cursor:pointer;
    }
    .btn-outline:hover{background:#F8FAFC}

    .alert-box{padding:12px 16px;border-radius:14px;margin-bottom:16px;display:flex;align-items:center;gap:10px;font-size:13.5px;font-weight:600}
    .alert-success{background:var(--green-bg);color:var(--green);border:1px solid #B8E4C9}
    .alert-error{background:var(--red-bg);color:var(--red);border:1px solid #F9C2C2}

    .pagination{display:flex;gap:6px;align-items:center;justify-content:center;margin-top:20px}
    .page-link{padding:6px 12px;border-radius:8px;background:#F1F3F4;color:var(--ink);font-size:13px;font-weight:600;text-decoration:none}
    .page-link.active{background:var(--navy);color:#fff}
  </style>
</head>
<body>
  <div class="frame">
    <?php require __DIR__ . '/sidebar.php'; ?>

    <section class="view active" id="view-etiket" aria-label="E-Tiket">
      <div class="page-head">
        <div>
          <h1>Pratinjau &amp; Dokumen E-Tiket</h1>
          <p>Daftar tiket elektronik resmi yang telah terbit untuk pembeli — siap cetak, simpan, atau kirim ulang</p>
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

      <div class="card">
        <div class="table-tools">
          <form method="GET" action="<?= base_url('dashboard/orders/tickets') ?>" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
            <div class="mini-search">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/></svg>
              <input type="search" name="search" value="<?= e($filters['search'] ?? '') ?>" placeholder="Cari invoice / kode tiket / pembeli…" aria-label="Cari tiket" />
            </div>
            <select name="event_id" style="border:1.5px solid var(--line);border-radius:999px;padding:6px 14px;background:#F1F3F4;font-size:13px;font-family:inherit;color:var(--ink);outline:none" onchange="this.form.submit()">
              <option value="">Semua Event</option>
              <?php foreach ($events as $ev): ?>
                <option value="<?= (int)$ev['id'] ?>" <?= ($filters['event_id'] ?? '') == $ev['id'] ? 'selected' : '' ?>>
                  <?= e($ev['title']) ?>
                </option>
              <?php endforeach; ?>
            </select>
            <button class="btn-sm-navy" type="submit" style="border-radius:999px;padding:8px 16px;">Filter</button>
          </form>

          <button class="btn-outline" type="button" id="ticketExportBtn">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v12M7 10l5 5 5-5M4 21h16"/></svg>
            Export CSV
          </button>
        </div>

        <div class="tbl-scroll">
          <table class="tbl" id="ticketTable">
            <thead>
              <tr>
                <th scope="col">Kode Tiket</th>
                <th scope="col">Invoice &amp; Tanggal</th>
                <th scope="col">Nama Pembeli</th>
                <th scope="col">Event &amp; Venue</th>
                <th scope="col">Qty</th>
                <th scope="col">Status Kehadiran</th>
                <th scope="col">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($orders)): ?>
                <tr>
                  <td colspan="7" style="text-align:center;padding:24px;color:var(--muted)">
                    Tidak ada pesanan dengan kode tiket yang sesuai.
                  </td>
                </tr>
              <?php else: ?>
                <?php foreach ($orders as $ord): 
                  $isCheckedIn = !empty($ord['checked_in_at']);
                ?>
                  <tr>
                    <td>
                      <code style="font-weight:800;font-size:14px;background:#F1F5F9;padding:4px 9px;border-radius:8px;color:var(--navy);letter-spacing:.8px">
                        <?= e($ord['ticket_code']) ?>
                      </code>
                    </td>
                    <td>
                      <div style="font-weight:700;color:var(--ink)"><?= e($ord['invoice']) ?></div>
                      <div style="font-size:11.5px;color:var(--muted)"><?= tanggal(substr($ord['created_at'], 0, 10)) ?></div>
                    </td>
                    <td>
                      <div style="font-weight:600;color:var(--ink)"><?= e($ord['buyer_name']) ?></div>
                      <div style="font-size:11.5px;color:var(--muted)"><?= e($ord['buyer_email']) ?></div>
                    </td>
                    <td>
                      <div style="font-weight:600;color:var(--navy)"><?= e($ord['event_title']) ?></div>
                      <div style="font-size:11.5px;color:var(--muted)"><?= tanggal($ord['event_date']) ?> · <?= e($ord['venue']) ?></div>
                    </td>
                    <td><b><?= (int)$ord['qty'] ?> tiket</b></td>
                    <td>
                      <?php if ($isCheckedIn): ?>
                        <span class="badge badge-success">✓ Sudah Check-in</span>
                        <div style="font-size:11px;color:var(--muted);margin-top:2px"><?= date('H:i', strtotime($ord['checked_in_at'])) ?> WIB</div>
                      <?php else: ?>
                        <span class="badge badge-gray">Belum Hadir</span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <div style="display:flex;gap:6px;align-items:center;">
                        <!-- Tombol Lihat E-Tiket -->
                        <a href="<?= base_url('dashboard/orders/tickets/' . (int)$ord['id']) ?>" target="_blank" class="btn-sm-navy" title="Lihat dan cetak e-tiket resmi">
                          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M2 12s3.5-6.5 10-6.5S22 12 22 12s-3.5 6.5-10 6.5S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg>
                          Lihat E-Tiket
                        </a>
                        <!-- Tombol Kirim Ulang -->
                        <form method="POST" action="<?= base_url('dashboard/orders/tickets/resend/' . (int)$ord['id']) ?>" onsubmit="return confirm('Kirim ulang e-tiket ini ke <?= e($ord['buyer_email']) ?>?')">
                          <?= csrf_field() ?>
                          <button class="btn-sm-ghost" type="submit" title="Kirim ulang konfirmasi tiket ke email">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 2 11 13M22 2l-7 20-4-9-9-4 20-7z"/></svg>
                            Kirim Ulang
                          </button>
                        </form>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <?php if ($totalPages > 1): ?>
          <div class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
              <a href="<?= base_url('dashboard/orders/tickets?page=' . $i . (!empty($filters['search']) ? '&search=' . urlencode($filters['search']) : '') . (!empty($filters['event_id']) ? '&event_id=' . $filters['event_id'] : '')) ?>" class="page-link <?= $i === $currentPage ? 'active' : '' ?>">
                <?= $i ?>
              </a>
            <?php endfor; ?>
          </div>
        <?php endif; ?>
      </div>
    </section>

    <?php $shellFooter = true; require __DIR__ . '/sidebar.php'; ?>
  </div>

  <script>
    (function(){
      var exportBtn = document.getElementById('ticketExportBtn');
      if (exportBtn) {
        exportBtn.addEventListener('click', function() {
          var table = document.getElementById('ticketTable');
          var rows = table.querySelectorAll('tbody tr');
          var csv = ["Kode Tiket,Invoice,Tanggal,Nama Pembeli,Email,Event,Venue,Jumlah Tiket,Status"];
          rows.forEach(function(r) {
            var cols = r.querySelectorAll('td');
            if (cols.length >= 7) {
              var line = [
                '"' + cols[0].innerText.trim() + '"',
                '"' + cols[1].querySelector('div').innerText.trim() + '"',
                '"' + cols[1].querySelectorAll('div')[1].innerText.trim() + '"',
                '"' + cols[2].querySelector('div').innerText.trim() + '"',
                '"' + cols[2].querySelectorAll('div')[1].innerText.trim() + '"',
                '"' + cols[3].querySelector('div').innerText.trim() + '"',
                '"' + cols[3].querySelectorAll('div')[1].innerText.trim() + '"',
                '"' + cols[4].innerText.trim() + '"',
                '"' + cols[5].innerText.trim().replace(/\n/g, ' ') + '"'
              ];
              csv.push(line.join(','));
            }
          });
          var blob = new Blob(["\uFEFF" + csv.join("\n")], { type: "text/csv;charset=utf-8;" });
          var link = document.createElement("a");
          var url = URL.createObjectURL(blob);
          link.setAttribute("href", url);
          link.setAttribute("download", "daftar_etiket_" + (new Date().toISOString().slice(0, 10)) + ".csv");
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
