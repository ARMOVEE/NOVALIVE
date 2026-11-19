<?php
/**
 * Halaman Refund / Pengembalian Dana (Menu Pesanan — Tab Refund)
 * NOVA LIVE MVC — Tahap 2
 */
$currentUser = $currentUser ?? ['full_name' => 'Admin NOVA LIVE', 'role' => 'admin'];
$userInitials = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $currentUser['full_name'] ?? 'AD'), 0, 2));
if (strlen($userInitials) < 2) $userInitials = 'AD';

$refunds = $refunds ?? [];
$statusCounts = $statusCounts ?? ['Semua' => 0, 'Diajukan' => 0, 'Disetujui' => 0, 'Ditolak' => 0];
$currentStatus = $currentStatus ?? 'Semua';
$search = $search ?? '';

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
  <title><?= e($pageTitle ?? 'NOVA LIVE — Refund & Pengembalian Dana') ?></title>
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

    .page-head{display:flex;align-items:flex-start;gap:14px;flex-wrap:wrap;margin:2px 2px 18px}
    .page-head h1{font-size:24px;margin:0;color:var(--ink);letter-spacing:-.2px;font-family:'Plus Jakarta Sans',sans-serif;font-weight:800}
    .page-head p{margin:4px 0 0;font-size:13px;color:var(--muted)}

    .chips-bar{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:16px}
    .chip{
      display:inline-flex;align-items:center;gap:6px;padding:7px 16px;border-radius:999px;font-size:13px;font-weight:600;
      background:#F1F3F4;color:#5B6B78;border:1.5px solid var(--line);text-decoration:none;transition:all .15s;
    }
    .chip:hover{color:var(--navy);background:#E2E8F0}
    .chip.active{background:var(--navy);color:#fff;border-color:var(--navy)}
    .chip-count{
      background:rgba(0,0,0,.08);padding:1px 7px;border-radius:99px;font-size:11px;font-weight:800;
    }
    .chip.active .chip-count{background:var(--gold);color:var(--navy)}

    .table-tools{display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-bottom:14px}
    .mini-search{display:flex;align-items:center;gap:8px;background:#F1F3F4;border-radius:999px;padding:6px 14px;border:1px solid var(--line)}
    .mini-search input{border:none;background:transparent;outline:none;font-size:13px;color:var(--ink);width:200px}

    .tbl-scroll{overflow-x:auto}
    .tbl{width:100%;border-collapse:collapse;font-size:13.5px}
    .tbl th{text-align:left;padding:11px 14px;background:#F8FAFC;color:var(--muted);font-weight:600;font-size:12px;border-bottom:1.5px solid var(--line);letter-spacing:.3px;text-transform:uppercase}
    .tbl td{padding:12px 14px;border-bottom:1px solid var(--line);vertical-align:middle}
    .tbl tbody tr:hover{background:#FAFCFE}

    .badge{display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:999px;font-size:11.5px;font-weight:700}
    .badge-diajukan{background:#FEF3C7;color:#B45309}
    .badge-disetujui{background:var(--green-bg);color:var(--green)}
    .badge-ditolak{background:var(--red-bg);color:var(--red)}

    .btn-sm-green{
      background:#16A34A;color:#fff;border-radius:8px;padding:6px 12px;font-size:12px;font-weight:700;border:none;cursor:pointer;
      display:inline-flex;align-items:center;gap:4px;transition:background .15s;
    }
    .btn-sm-green:hover{background:#15803D}
    .btn-sm-red{
      background:#EF4444;color:#fff;border-radius:8px;padding:6px 12px;font-size:12px;font-weight:700;border:none;cursor:pointer;
      display:inline-flex;align-items:center;gap:4px;transition:background .15s;
    }
    .btn-sm-red:hover{background:#DC2626}
    .btn-outline{
      display:inline-flex;align-items:center;gap:6px;padding:8px 14px;border-radius:10px;font-size:12.5px;font-weight:600;
      border:1.5px solid var(--line);background:#fff;color:var(--navy);cursor:pointer;
    }
    .btn-outline:hover{background:#F8FAFC}

    .alert-box{padding:12px 16px;border-radius:14px;margin-bottom:16px;display:flex;align-items:center;gap:10px;font-size:13.5px;font-weight:600}
    .alert-success{background:var(--green-bg);color:var(--green);border:1px solid #B8E4C9}
    .alert-error{background:var(--red-bg);color:var(--red);border:1px solid #F9C2C2}

    .pending-banner{
      background:#FFFBEB;border:1.5px solid #FDE68A;border-radius:16px;padding:14px 18px;margin-bottom:18px;
      display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap;
    }
    .pending-banner b{color:#92400E;font-size:14px}
    .pending-banner p{color:#B45309;font-size:12.5px;margin:2px 0 0}

    /* Modal */
    .overlay{position:fixed;inset:0;background:rgba(14,50,84,.45);backdrop-filter:blur(3px);display:none;place-items:center;z-index:99;padding:16px}
    .overlay.open{display:grid}
    .modal{background:#fff;border-radius:24px;max-width:480px;width:100%;padding:26px;box-shadow:0 30px 70px rgba(14,50,84,.3);position:relative;margin:auto}
    .modal h2{margin:0 0 6px;font-size:18px;color:var(--navy);font-weight:800}
    .modal p{margin:0 0 16px;font-size:13px;color:var(--muted);line-height:1.5}
    .modal-x{position:absolute;top:16px;right:16px;width:32px;height:32px;border-radius:50%;display:grid;place-items:center;color:#8A97A3;font-size:15px;background:none;border:none;cursor:pointer}
    .modal-x:hover{background:#EEF3F7;color:var(--navy)}
  </style>
</head>
<body>
  <div class="frame">
    <?php require __DIR__ . '/sidebar.php'; ?>

    <section class="view active" id="view-refund" aria-label="Refund Tiket">
      <div class="page-head">
        <div>
          <h1>Refund / Pengembalian Dana</h1>
          <p>Pengajuan refund dari pesanan tiket — setujui, tolak, dan pantau pengembalian dana pembeli</p>
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

      <?php if ((int)($statusCounts['Diajukan'] ?? 0) > 0): ?>
        <div class="pending-banner">
          <div>
            <b>Ada <?= (int)$statusCounts['Diajukan'] ?> Pengajuan Refund Menunggu Tindakan</b>
            <p>Setujui pengajuan untuk membatalkan pesanan dan mengembalikan kuota tiket secara otomatis ke event.</p>
          </div>
          <a href="<?= base_url('dashboard/orders/refunds?status=Diajukan') ?>" class="chip" style="background:#B45309;color:#fff;border-color:#B45309">
            Lihat Pengajuan Pending
          </a>
        </div>
      <?php endif; ?>

      <!-- Filter Chips -->
      <div class="chips-bar">
        <a href="<?= base_url('dashboard/orders/refunds?status=Semua') ?>" class="chip <?= $currentStatus === 'Semua' ? 'active' : '' ?>">
          Semua <span class="chip-count"><?= (int)$statusCounts['Semua'] ?></span>
        </a>
        <a href="<?= base_url('dashboard/orders/refunds?status=Diajukan') ?>" class="chip <?= $currentStatus === 'Diajukan' ? 'active' : '' ?>">
          Diajukan <span class="chip-count"><?= (int)$statusCounts['Diajukan'] ?></span>
        </a>
        <a href="<?= base_url('dashboard/orders/refunds?status=Disetujui') ?>" class="chip <?= $currentStatus === 'Disetujui' ? 'active' : '' ?>">
          Disetujui <span class="chip-count"><?= (int)$statusCounts['Disetujui'] ?></span>
        </a>
        <a href="<?= base_url('dashboard/orders/refunds?status=Ditolak') ?>" class="chip <?= $currentStatus === 'Ditolak' ? 'active' : '' ?>">
          Ditolak <span class="chip-count"><?= (int)$statusCounts['Ditolak'] ?></span>
        </a>
      </div>

      <!-- Tabel Daftar Refund -->
      <div class="card">
        <div class="table-tools">
          <div class="card-head" style="margin-bottom:0">
            <div>
              <h2>Semua Pengajuan &amp; Riwayat Refund</h2>
              <p style="margin:2px 0 0;font-size:12.5px;color:var(--muted)">Daftar lengkap permohonan pengembalian dana tiket</p>
            </div>
          </div>
          <div style="display:flex;gap:10px;align-items:center;">
            <div class="mini-search">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/></svg>
              <input type="search" id="refundSearch" placeholder="Cari invoice / pembeli / alasan…" aria-label="Cari pengajuan refund" />
            </div>
            <button class="btn-outline" type="button" id="refundExportBtn">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v12M7 10l5 5 5-5M4 21h16"/></svg>
              Export CSV
            </button>
          </div>
        </div>

        <div class="tbl-scroll">
          <table class="tbl">
            <thead>
              <tr>
                <th scope="col">ID</th>
                <th scope="col">Invoice &amp; Tanggal</th>
                <th scope="col">Pembeli</th>
                <th scope="col">Event</th>
                <th scope="col">Jumlah Refund</th>
                <th scope="col">Alasan</th>
                <th scope="col">Status</th>
                <th scope="col">Aksi</th>
              </tr>
            </thead>
            <tbody id="refundTableBody">
              <?php if (empty($refunds)): ?>
                <tr class="empty-row" id="refundEmptyRow">
                  <td colspan="8" style="text-align:center;padding:24px;color:var(--muted)">
                    Tidak ada data permohonan refund yang sesuai dengan filter ini.
                  </td>
                </tr>
              <?php else: ?>
                <?php foreach ($refunds as $ref): 
                  $st = $ref['status'];
                  $badgeCls = ($st === 'Disetujui') ? 'badge-disetujui' : (($st === 'Ditolak') ? 'badge-ditolak' : 'badge-diajukan');
                ?>
                  <tr class="ref-row" data-invoice="<?= strtolower(e($ref['invoice'])) ?>" data-buyer="<?= strtolower(e($ref['buyer_name'])) ?>" data-reason="<?= strtolower(e($ref['reason'])) ?>">
                    <td><b>#<?= (int)$ref['id'] ?></b></td>
                    <td>
                      <div style="font-weight:700;color:var(--navy)"><?= e($ref['invoice']) ?></div>
                      <div style="font-size:11.5px;color:var(--muted)"><?= tanggal(substr($ref['created_at'], 0, 10)) ?></div>
                    </td>
                    <td>
                      <div style="font-weight:600;color:var(--ink)"><?= e($ref['buyer_name']) ?></div>
                      <div style="font-size:11.5px;color:var(--muted)"><?= e($ref['buyer_email']) ?></div>
                    </td>
                    <td>
                      <div style="font-weight:500"><?= e($ref['event_title']) ?></div>
                      <div style="font-size:11.5px;color:var(--muted)"><?= (int)$ref['qty'] ?> tiket (<?= e($ref['ticket_code'] ?? '-') ?>)</div>
                    </td>
                    <td><b style="color:#B45309;font-size:14px"><?= rupiah($ref['amount']) ?></b></td>
                    <td style="max-width:240px">
                      <div style="font-size:12.5px;line-height:1.45;color:var(--ink)"><?= e($ref['reason']) ?></div>
                    </td>
                    <td><span class="badge <?= $badgeCls ?>"><?= e($st) ?></span></td>
                    <td>
                      <?php if ($st === 'Diajukan'): ?>
                        <div style="display:flex;gap:6px;align-items:center;">
                          <!-- Tombol Setujui -->
                          <form method="POST" action="<?= base_url('dashboard/orders/refunds/approve/' . (int)$ref['id']) ?>" onsubmit="return confirm('Apakah Anda yakin ingin MENYETUJUI refund ini? Pesanan akan dibatalkan dan kuota tiket dikembalikan ke event.')">
                            <?= csrf_field() ?>
                            <button class="btn-sm-green" type="submit" title="Setujui pengembalian dana">✓ Setujui</button>
                          </form>
                          <!-- Tombol Tolak (membuka modal) -->
                          <button class="btn-sm-red js-reject-btn" type="button" data-id="<?= (int)$ref['id'] ?>" data-inv="<?= e($ref['invoice']) ?>" title="Tolak permohonan refund">✕ Tolak</button>
                        </div>
                      <?php else: ?>
                        <div style="font-size:11.5px;color:var(--muted)">
                          Diproses oleh: <b><?= e($ref['processor_name'] ?? 'Admin') ?></b>
                        </div>
                      <?php endif; ?>
                    </td>
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

  <!-- Modal Tolak Refund -->
  <div class="overlay" id="rejectModal" role="dialog" aria-modal="true" aria-labelledby="rejectModalTitle">
    <div class="modal">
      <button type="button" class="modal-x" id="rejectCloseBtn" aria-label="Tutup modal">✕</button>
      <h2 id="rejectModalTitle">Tolak Pengajuan Refund</h2>
      <p id="rejectModalSub">Silakan masukkan alasan penolakan refund untuk invoice ini.</p>

      <form method="POST" action="" id="rejectForm">
        <?= csrf_field() ?>
        <div style="margin-bottom:14px">
          <label for="rejectReason" style="display:block;font-size:12.5px;font-weight:600;margin-bottom:6px;color:var(--ink)">Alasan Penolakan:</label>
          <textarea name="reject_reason" id="rejectReason" rows="3" style="width:100%;border:1.5px solid var(--line);border-radius:12px;padding:10px 14px;font-family:inherit;font-size:13.5px;box-sizing:border-box" required placeholder="Contoh: Tiket sudah melewati batas waktu pengajuan refund..."></textarea>
        </div>
        <div style="display:flex;gap:10px;justify-content:flex-end">
          <button type="button" id="rejectCancelBtn" class="btn-ghost" style="min-height:38px;padding:6px 16px">Batal</button>
          <button type="submit" class="btn-navy" style="min-height:38px;padding:6px 18px;background:var(--red)">Kirim Penolakan</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    (function(){
      // Live search
      var searchInput = document.getElementById('refundSearch');
      var rows = document.querySelectorAll('#refundTableBody .ref-row');
      if (searchInput && rows.length > 0) {
        searchInput.addEventListener('input', function() {
          var q = (searchInput.value || '').trim().toLowerCase();
          rows.forEach(function(r) {
            var inv = r.getAttribute('data-invoice') || '';
            var buyer = r.getAttribute('data-buyer') || '';
            var reason = r.getAttribute('data-reason') || '';
            var match = !q || inv.indexOf(q) > -1 || buyer.indexOf(q) > -1 || reason.indexOf(q) > -1;
            r.style.display = match ? '' : 'none';
          });
        });
      }

      // Export CSV
      var exportBtn = document.getElementById('refundExportBtn');
      if (exportBtn && rows.length > 0) {
        exportBtn.addEventListener('click', function() {
          var csv = ["ID Refund,Invoice,Pembeli,Email,Event,Jumlah Refund,Alasan,Status"];
          rows.forEach(function(r) {
            if (r.style.display !== 'none') {
              var cols = r.querySelectorAll('td');
              if (cols.length >= 7) {
                var line = [
                  '"' + cols[0].innerText.trim() + '"',
                  '"' + cols[1].querySelector('div').innerText.trim() + '"',
                  '"' + cols[2].querySelector('div').innerText.trim() + '"',
                  '"' + cols[2].querySelectorAll('div')[1].innerText.trim() + '"',
                  '"' + cols[3].querySelector('div').innerText.trim() + '"',
                  '"' + cols[4].innerText.trim() + '"',
                  '"' + cols[5].innerText.trim().replace(/"/g, '""') + '"',
                  '"' + cols[6].innerText.trim() + '"'
                ];
                csv.push(line.join(','));
              }
            }
          });
          var blob = new Blob(["\uFEFF" + csv.join("\n")], { type: "text/csv;charset=utf-8;" });
          var link = document.createElement("a");
          var url = URL.createObjectURL(blob);
          link.setAttribute("href", url);
          link.setAttribute("download", "daftar_refund_" + (new Date().toISOString().slice(0, 10)) + ".csv");
          link.style.visibility = "hidden";
          document.body.appendChild(link);
          link.click();
          document.body.removeChild(link);
        });
      }

      // Modal Tolak
      var modal = document.getElementById('rejectModal');
      var rejectForm = document.getElementById('rejectForm');
      var modalSub = document.getElementById('rejectModalSub');
      var closeBtn = document.getElementById('rejectCloseBtn');
      var cancelBtn = document.getElementById('rejectCancelBtn');

      function closeModal() {
        if (modal) modal.classList.remove('open');
      }

      document.querySelectorAll('.js-reject-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
          var id = btn.getAttribute('data-id');
          var inv = btn.getAttribute('data-inv');
          if (rejectForm) {
            rejectForm.action = "<?= base_url('dashboard/orders/refunds/reject/') ?>" + id;
          }
          if (modalSub) {
            modalSub.textContent = "Masukkan alasan penolakan untuk refund #" + id + " (" + inv + ").";
          }
          if (modal) modal.classList.add('open');
        });
      });

      if (closeBtn) closeBtn.onclick = closeModal;
      if (cancelBtn) cancelBtn.onclick = closeModal;
      if (modal) {
        modal.addEventListener('click', function(e) {
          if (e.target === modal) closeModal();
        });
      }
    })();
  </script>
</body>
</html>
