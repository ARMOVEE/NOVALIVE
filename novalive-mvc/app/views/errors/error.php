<div style="max-width:800px;margin:80px auto;padding:40px 24px;text-align:center;">
  <div style="font-size:clamp(80px,12vw,140px);font-weight:900;line-height:1;color:var(--navy);font-family:'Plus Jakarta Sans',sans-serif;letter-spacing:-0.04em;">
    <?= isset($code) ? (int)$code : 404 ?>
  </div>
  <h1 style="font-size:28px;margin:16px 0 12px;color:var(--navy);">
    <?= isset($title) ? e($title) : 'Halaman Tidak Ditemukan' ?>
  </h1>
  <p style="font-size:16px;color:var(--muted);max-width:520px;margin:0 auto 28px;line-height:1.6;">
    <?= isset($message) ? e($message) : 'Halaman atau data yang Anda cari tidak ada atau sudah dipindahkan.' ?>
  </p>
  <div style="display:flex;justify-content:center;gap:12px;flex-wrap:wrap;">
    <a href="<?= base_url() ?>" style="display:inline-flex;align-items:center;gap:8px;background:var(--navy);color:#fff;padding:12px 28px;border-radius:999px;font-weight:700;font-size:15px;box-shadow:var(--shadow);">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
      Kembali ke Beranda
    </a>
    <a href="<?= base_url('events') ?>" style="display:inline-flex;align-items:center;gap:8px;background:#fff;border:1px solid var(--line);color:var(--navy);padding:12px 24px;border-radius:999px;font-weight:700;font-size:15px;">
      Jelajahi Event
    </a>
  </div>
</div>
