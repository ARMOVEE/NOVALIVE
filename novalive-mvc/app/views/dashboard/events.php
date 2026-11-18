<style>
  .dash-layout {
    max-width: 1280px;
    margin: 32px auto 60px;
    padding: 0 24px;
    display: grid;
    grid-template-columns: 240px 1fr;
    gap: 32px;
    align-items: start;
  }
  @media (max-width: 900px) {
    .dash-layout { grid-template-columns: 1fr; }
  }
  .dash-menu {
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: 16px;
    padding: 16px;
    box-shadow: var(--shadow);
    list-style: none;
    display: grid;
    gap: 6px;
  }
  .dash-menu a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 700;
    color: var(--navy);
  }
  .dash-menu a.active {
    background: var(--navy);
    color: #fff;
  }
</style>

<div class="dash-layout">
  <!-- Sidebar Menu -->
  <aside>
    <ul class="dash-menu">
      <li><a href="<?= base_url('dashboard') ?>">📊 Ringkasan</a></li>
      <li><a href="<?= base_url('dashboard/orders') ?>">🎟️ Pesanan Tiket</a></li>
      <li><a href="<?= base_url('dashboard/events') ?>" class="active">🎪 Event Saya</a></li>
      <?php if (can('events.create')): ?>
        <li><a href="<?= base_url('events/create') ?>">➕ Tambah Event</a></li>
      <?php endif; ?>
      <?php if (can('events.delete')): ?>
        <li><a href="<?= base_url('events/trash') ?>">🗑️ Sampah Event</a></li>
      <?php endif; ?>
    </ul>
  </aside>

  <!-- Konten Utama -->
  <div>
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;flex-wrap:wrap;gap:16px;">
      <div>
        <h1 style="font-size:26px;font-weight:800;color:var(--navy);">Kelola Event Saya</h1>
        <p style="font-size:14px;color:var(--muted);margin-top:4px;">Daftar seluruh event aktif yang Anda selenggarakan</p>
      </div>

      <div style="display:flex;gap:12px;align-items:center;">
        <form action="<?= base_url('dashboard/events') ?>" method="GET" style="display:flex;gap:8px;">
          <input type="text" name="search" value="<?= e($filters['search'] ?? '') ?>" placeholder="Cari event..." style="padding:8px 14px;border:1px solid var(--line);border-radius:8px;font-size:13.5px;outline:none;">
          <button type="submit" style="padding:8px 16px;background:var(--navy);color:#fff;border:0;border-radius:8px;font-weight:700;font-size:13.5px;cursor:pointer;">Cari</button>
        </form>
        <?php if (can('events.create')): ?>
          <a href="<?= base_url('events/create') ?>" style="display:inline-flex;align-items:center;gap:6px;padding:9px 18px;background:var(--navy);color:#fff;border-radius:999px;font-weight:700;font-size:13.5px;box-shadow:var(--shadow);">
            ➕ Tambah Event
          </a>
        <?php endif; ?>
      </div>
    </div>

    <!-- Tabel Event -->
    <div style="background:var(--card);border:1px solid var(--line);border-radius:18px;overflow:hidden;box-shadow:var(--shadow);">
      <?php if (empty($data)): ?>
        <div style="padding:48px 24px;text-align:center;color:var(--muted);">
          Belum ada data event yang terdaftar.
        </div>
      <?php else: ?>
        <div style="overflow-x:auto;">
          <table style="width:100%;border-collapse:collapse;font-size:13.5px;text-align:left;">
            <thead>
              <tr style="background:#F7FAFD;border-bottom:1px solid var(--line);color:var(--navy);">
                <th style="padding:14px 18px;font-weight:700;">Kode</th>
                <th style="padding:14px 18px;font-weight:700;">Judul Event</th>
                <th style="padding:14px 18px;font-weight:700;">Kategori / Kota</th>
                <th style="padding:14px 18px;font-weight:700;">Tanggal</th>
                <th style="padding:14px 18px;font-weight:700;">Harga Tiket</th>
                <th style="padding:14px 18px;font-weight:700;">Keterisian</th>
                <th style="padding:14px 18px;font-weight:700;text-align:right;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($data as $e): ?>
                <tr style="border-bottom:1px solid var(--line);">
                  <td style="padding:14px 18px;font-weight:700;color:var(--muted);"><?= e($e['code']) ?></td>
                  <td style="padding:14px 18px;">
                    <a href="<?= base_url('events/show/' . $e['id']) ?>" style="font-weight:700;color:var(--navy);display:block;">
                      <?= e($e['title']) ?>
                    </a>
                    <small style="color:var(--muted);"><?= e($e['organizer']) ?></small>
                  </td>
                  <td style="padding:14px 18px;color:var(--muted);">
                    <?= e($e['category_name'] ?? '-') ?> • <?= e($e['city_name'] ?? '-') ?>
                  </td>
                  <td style="padding:14px 18px;color:var(--navy);"><?= tanggal($e['event_date']) ?></td>
                  <td style="padding:14px 18px;font-weight:800;color:var(--navy);"><?= (float)$e['price'] > 0 ? rupiah($e['price']) : 'Gratis' ?></td>
                  <td style="padding:14px 18px;">
                    <span style="font-weight:700;color:var(--navy);"><?= (int)$e['sold'] ?></span> / <?= (int)$e['quota'] ?>
                  </td>
                  <td style="padding:14px 18px;text-align:right;white-space:nowrap;">
                    <?php if (can('events.update')): ?>
                      <a href="<?= base_url('events/edit/' . (int)$e['id']) ?>" style="padding:5px 12px;background:#F0F4F8;color:var(--navy);border-radius:6px;font-weight:700;font-size:12.5px;margin-right:6px;">
                        Ubah
                      </a>
                    <?php endif; ?>
                    <?php if (can('events.delete')): ?>
                      <a href="<?= base_url('events/delete/' . (int)$e['id']) ?>" onclick="return confirm('Apakah Anda yakin ingin memindahkan event ini ke Sampah?')" style="padding:5px 12px;background:#FCE8E6;color:#C5221F;border-radius:6px;font-weight:700;font-size:12.5px;">
                        Hapus
                      </a>
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
</div>
