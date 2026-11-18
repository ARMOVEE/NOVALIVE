<div style="max-width:960px;margin:36px auto 60px;padding:0 24px;">
  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:28px;">
    <div>
      <h1 style="font-size:26px;font-weight:800;color:var(--navy);">Ubah Event: <?= e($event['title']) ?></h1>
      <p style="font-size:14px;color:var(--muted);margin-top:4px;">Perbarui jadwal, harga tiket, kuota, atau poster event</p>
    </div>
    <a href="<?= base_url('dashboard/events') ?>" style="display:inline-flex;align-items:center;gap:6px;font-size:14px;font-weight:700;color:var(--navy);background:var(--cream);padding:8px 16px;border-radius:8px;border:1px solid var(--line);">
      ← Kembali ke Event Saya
    </a>
  </div>

  <div style="background:var(--card);border:1px solid var(--line);border-radius:20px;padding:36px;box-shadow:var(--shadow);">
    <form action="<?= base_url('events/update/' . (int)$event['id']) ?>" method="POST" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <input type="hidden" name="id" value="<?= (int)$event['id'] ?>">

      <?php require_once __DIR__ . '/_form.php'; ?>

      <div style="display:flex;justify-content:space-between;align-items:center;margin-top:20px;">
        <a href="<?= base_url('events/delete/' . (int)$event['id']) ?>" onclick="return confirm('Apakah Anda yakin ingin memindahkan event ini ke Sampah?')" style="color:#C5221F;font-weight:700;font-size:14px;">
          🗑️ Pindahkan ke Sampah
        </a>
        <div style="display:flex;gap:12px;">
          <a href="<?= base_url('dashboard/events') ?>" style="padding:12px 24px;border-radius:999px;border:1px solid var(--line);background:#fff;color:var(--navy);font-weight:700;font-size:14px;">
            Batal
          </a>
          <button type="submit" style="padding:12px 32px;border-radius:999px;border:0;background:var(--navy);color:#fff;font-weight:700;font-size:14.5px;cursor:pointer;box-shadow:var(--shadow);">
            Simpan Perubahan
          </button>
        </div>
      </div>
    </form>
  </div>
</div>
