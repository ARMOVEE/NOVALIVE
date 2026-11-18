<div style="max-width:1080px;margin:36px auto 60px;padding:0 24px;">
  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:28px;">
    <div>
      <h1 style="font-size:26px;font-weight:800;color:var(--navy);">Sampah Event (Soft Delete)</h1>
      <p style="font-size:14px;color:var(--muted);margin-top:4px;">Daftar event yang telah dihapus sementara. Anda dapat memulihkannya kembali.</p>
    </div>
    <a href="<?= base_url('dashboard/events') ?>" style="display:inline-flex;align-items:center;gap:6px;font-size:14px;font-weight:700;color:var(--navy);background:var(--cream);padding:8px 16px;border-radius:8px;border:1px solid var(--line);">
      ← Kembali ke Event Saya
    </a>
  </div>

  <div style="background:var(--card);border:1px solid var(--line);border-radius:20px;overflow:hidden;box-shadow:var(--shadow);">
    <?php if (empty($trashEvents)): ?>
      <div style="padding:60px 24px;text-align:center;">
        <div style="font-size:40px;margin-bottom:10px;">🗑️</div>
        <h3 style="font-size:18px;color:var(--navy);margin-bottom:6px;">Sampah Kosong</h3>
        <p style="font-size:14px;color:var(--muted);">Tidak ada event yang sedang berada di folder sampah.</p>
      </div>
    <?php else: ?>
      <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:14px;text-align:left;">
          <thead>
            <tr style="background:#F7FAFD;border-bottom:1px solid var(--line);color:var(--navy);">
              <th style="padding:14px 20px;font-weight:700;">Kode</th>
              <th style="padding:14px 20px;font-weight:700;">Judul Event</th>
              <th style="padding:14px 20px;font-weight:700;">Kategori / Kota</th>
              <th style="padding:14px 20px;font-weight:700;">Dihapus Pada</th>
              <th style="padding:14px 20px;font-weight:700;text-align:right;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($trashEvents as $item): ?>
              <tr style="border-bottom:1px solid var(--line);">
                <td style="padding:14px 20px;font-weight:700;color:var(--muted);"><?= e($item['code']) ?></td>
                <td style="padding:14px 20px;font-weight:700;color:var(--navy);"><?= e($item['title']) ?></td>
                <td style="padding:14px 20px;color:var(--muted);"><?= e($item['category_name'] ?? '-') ?> • <?= e($item['city_name'] ?? '-') ?></td>
                <td style="padding:14px 20px;color:var(--muted);"><?= tanggal($item['deleted_at'], 'd M Y H:i') ?></td>
                <td style="padding:14px 20px;text-align:right;">
                  <?php if (can('*')): ?>
                    <a href="<?= base_url('events/restore/' . (int)$item['id']) ?>" onclick="return confirm('Pulihkan event ini kembali ke katalog aktif?')" style="display:inline-flex;align-items:center;gap:4px;padding:6px 14px;background:#E6F4EA;color:#137333;font-weight:700;border-radius:6px;font-size:13px;">
                      ↺ Pulihkan
                    </a>
                  <?php else: ?>
                    <span style="font-size:12px;color:var(--muted);">(Hanya Admin)</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</div>
