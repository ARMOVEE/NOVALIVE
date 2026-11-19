<?php
/**
 * Halaman Cetak E-Tiket Resmi — Siap Cetak & Simpan PDF
 * NOVA LIVE MVC — Tahap 2
 */
$order = $order ?? [];
$ticketCode = $order['ticket_code'] ?? 'NVL-DEMO99';
$invoice = $order['invoice'] ?? 'INV-DEMO';
$buyerName = $order['buyer_name'] ?? 'Peserta NOVA LIVE';
$buyerEmail = $order['buyer_email'] ?? 'peserta@email.com';
$eventTitle = $order['event_title'] ?? 'Event NOVA LIVE';
$eventDate = $order['event_date'] ?? date('Y-m-d');
$venue = $order['venue'] ?? 'Jakarta Convention Center';
$qty = (int)($order['qty'] ?? 1);
$totalPrice = (float)($order['total_price'] ?? 0);
$status = $order['status'] ?? 'Terkirim';
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <title>E-Tiket Resmi <?= e($ticketCode) ?> — NOVA LIVE</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@600;700;800&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@700&display=swap" rel="stylesheet" />
  <style>
    :root{
      --navy:#0E3254;
      --navy-2:#15497A;
      --gold:#EAD4A2;
      --gold-deep:#D4B475;
      --cream:#F5EFE0;
      --ink:#14232F;
      --muted:#64748B;
      --line:#E2E8F0;
    }
    *{box-sizing:border-box}
    body{
      margin:0;padding:24px;background:#F1F5F9;font-family:'Inter',system-ui,sans-serif;color:var(--ink);
      display:flex;flex-direction:column;align-items:center;min-height:100vh;
    }
    .action-bar{
      width:100%;max-width:820px;display:flex;align-items:center;justify-content:space-between;
      margin-bottom:20px;gap:12px;
    }
    .back-link{
      display:inline-flex;align-items:center;gap:6px;font-size:13.5px;font-weight:600;color:var(--navy);text-decoration:none;
      background:#fff;padding:8px 16px;border-radius:999px;box-shadow:0 2px 6px rgba(0,0,0,.06);
    }
    .back-link:hover{background:#F8FAFC}
    .btn-print{
      display:inline-flex;align-items:center;gap:8px;background:var(--navy);color:#fff;
      font-size:14px;font-weight:700;padding:10px 22px;border-radius:999px;border:none;cursor:pointer;
      font-family:'Plus Jakarta Sans',sans-serif;box-shadow:0 8px 18px rgba(14,50,84,.25);
    }
    .btn-print:hover{background:var(--navy-2)}

    /* Ticket Body Card */
    .ticket-container{
      width:100%;max-width:820px;background:#fff;border-radius:24px;box-shadow:0 20px 50px rgba(14,50,84,.12);
      overflow:hidden;position:relative;display:flex;border:1px solid var(--line);
    }
    .ticket-main{
      flex:1;padding:32px 36px;display:flex;flex-direction:column;justify-content:space-between;
    }
    .ticket-stub{
      width:250px;background:#F8FAFC;border-left:2px dashed #CBD5E1;padding:32px 24px;
      display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;position:relative;
    }
    /* Half circle notches */
    .ticket-stub::before, .ticket-stub::after{
      content:"";position:absolute;width:24px;height:24px;background:#F1F5F9;border-radius:50%;left:-13px;
    }
    .ticket-stub::before{top:-12px}
    .ticket-stub::after{bottom:-12px}

    .t-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;border-bottom:1px solid var(--line);padding-bottom:16px}
    .t-logo{display:flex;align-items:center;gap:8px;font-family:'Plus Jakarta Sans',sans-serif;font-weight:800;font-size:20px;color:var(--navy)}
    .t-logo em{font-style:normal;color:var(--gold-deep)}
    .t-badge{background:#ECFDF5;color:#047857;font-size:12px;font-weight:700;padding:4px 12px;border-radius:999px;border:1px solid #A7F3D0}

    .ev-title{font-family:'Plus Jakarta Sans',sans-serif;font-size:26px;font-weight:800;color:var(--navy);margin:0 0 14px;line-height:1.2}

    .grid-info{display:grid;grid-template-columns:1fr 1fr;gap:18px 24px;margin-bottom:26px}
    .info-item span{display:block;font-size:11.5px;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;font-weight:600;margin-bottom:3px}
    .info-item b{display:block;font-size:15px;color:var(--ink);font-weight:700}

    .ticket-footer{display:flex;align-items:center;justify-content:space-between;border-top:1px solid var(--line);padding-top:16px;margin-top:auto}
    .ticket-footer p{margin:0;font-size:12px;color:var(--muted)}

    .qr-box{
      background:#fff;padding:12px;border-radius:16px;border:1.5px solid var(--line);box-shadow:0 4px 12px rgba(0,0,0,.04);
      margin-bottom:16px;display:grid;place-items:center;
    }
    .code-label{font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:.6px;font-weight:700}
    .code-val{font-family:'JetBrains Mono',monospace;font-size:19px;font-weight:800;color:var(--navy);letter-spacing:1.5px;margin:4px 0 12px}

    /* Print styles */
    @media print{
      body{background:#fff;padding:0;margin:0}
      .action-bar{display:none}
      .ticket-container{box-shadow:none;border:1.5px solid #000;max-width:100%;border-radius:0;margin:0}
      .ticket-stub::before, .ticket-stub::after{display:none}
      .ticket-stub{background:#fff;border-left:1.5px dashed #000}
      @page{size:A4 portrait;margin:15mm}
    }

    @media (max-width:720px){
      .ticket-container{flex-direction:column}
      .ticket-stub{width:100%;border-left:none;border-top:2px dashed #CBD5E1}
      .ticket-stub::before{top:auto;left:-12px;bottom:-12px}
      .ticket-stub::after{top:auto;right:-12px;left:auto;bottom:-12px}
    }
  </style>
</head>
<body>

  <div class="action-bar">
    <a href="<?= base_url('dashboard/orders/tickets') ?>" class="back-link">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
      Kembali ke Daftar Tiket
    </a>
    <button class="btn-print" onclick="window.print()" type="button">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9V2h12v7M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
      Cetak E-Tiket (Print / PDF)
    </button>
  </div>

  <div class="ticket-container">
    <div class="ticket-main">
      <div class="t-head">
        <div class="t-logo">
          <svg width="28" height="28" viewBox="0 0 32 32" fill="none"><rect width="32" height="32" rx="8" fill="#0E3254"/><path d="M8 16l8-8 8 8-8 8-8-8z" fill="#D4B475"/></svg>
          NOVA <em>LIVE</em>
        </div>
        <span class="t-badge">✓ E-TIKET RESMI</span>
      </div>

      <h1 class="ev-title"><?= e($eventTitle) ?></h1>

      <div class="grid-info">
        <div class="info-item">
          <span>Hari &amp; Tanggal</span>
          <b><?= tanggal($eventDate) ?></b>
        </div>
        <div class="info-item">
          <span>Lokasi Venue</span>
          <b><?= e($venue) ?></b>
        </div>
        <div class="info-item">
          <span>Nama Pemegang Tiket</span>
          <b><?= e($buyerName) ?></b>
        </div>
        <div class="info-item">
          <span>Jumlah &amp; Kategori</span>
          <b><?= (int)$qty ?> Tiket (Reguler Entry)</b>
        </div>
        <div class="info-item">
          <span>Nomor Invoice</span>
          <b><?= e($invoice) ?></b>
        </div>
        <div class="info-item">
          <span>Total Pembayaran</span>
          <b style="color:var(--navy)"><?= rupiah($totalPrice) ?></b>
        </div>
      </div>

      <div class="ticket-footer">
        <p>Tunjukkan QR code atau kode tiket ini kepada petugas di pintu gerbang venue untuk validasi check-in masuk.</p>
      </div>
    </div>

    <div class="ticket-stub">
      <div class="code-label">Scan Barcode / QR</div>
      <div class="qr-box" id="qrcode"></div>
      <div class="code-label">Kode Tiket Unik</div>
      <div class="code-val"><?= e($ticketCode) ?></div>
      <p style="margin:0;font-size:11.5px;color:var(--muted);line-height:1.4">
        Tiket berlaku untuk 1 kali akses masuk. Jangan bagikan kode ini kepada pihak lain.
      </p>
    </div>
  </div>

  <!-- QR Code Generator Library & Offline Fallback -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
  <script>
    (function(){
      var qrContainer = document.getElementById("qrcode");
      var text = "<?= addslashes($ticketCode) ?>";
      if (typeof QRCode !== "undefined" && qrContainer) {
        new QRCode(qrContainer, {
          text: text,
          width: 140,
          height: 140,
          colorDark: "#0E3254",
          colorLight: "#FFFFFF",
          correctLevel: QRCode.CorrectLevel.H
        });
      } else if (qrContainer) {
        // Fallback: SVG barcode representation
        qrContainer.innerHTML = '<svg width="140" height="140" viewBox="0 0 100 100"><rect width="100" height="100" fill="#fff"/><rect x="10" y="10" width="80" height="80" rx="6" fill="#F1F5F9"/><text x="50" y="45" font-size="12" font-family="monospace" text-anchor="middle" fill="#0E3254" font-weight="bold">QR CODE</text><text x="50" y="65" font-size="9" font-family="monospace" text-anchor="middle" fill="#64748B">' + text + '</text></svg>';
      }
    })();
  </script>
</body>
</html>
