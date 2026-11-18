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
  .status-tab {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border-radius: 999px;
    background: var(--card);
    border: 1px solid var(--line);
    font-size: 13.5px;
    font-weight: 700;
    color: var(--navy);
    text-decoration: none;
  }
  .status-tab.active {
    background: var(--navy);
    color: #fff;
    border-color: var(--navy);
  }
</style>

<div class="dash-layout">
  <!-- Sidebar Menu -->
  <aside>
    <ul class="dash-menu">
      <li><a href="<?= base_url('dashboard') ?>">📊 Ringkasan</a></li>
      <li><a href="<?= base_url('dashboard/orders') ?>" class="active">🎟️ Pesanan Tiket</a></li>
      <li><a href="<?= base_url('dashboard/events') ?>">🎪 Event Saya</a></li>
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
        <h1 style="font-size:26px;font-weight:800;color:var(--navy);">Daftar Pesanan Tiket</h1>
        <p style="font-size:14px;color:var(--muted);margin-top:4px;">Kelola dan verifikasi seluruh transaksi tiket pengunjung</p>
      </div>

      <form action="<?= base_url('dashboard/orders') ?>" method="GET" style="display:flex;gap:8px;">
        <?php if (!empty($filters['status'])): ?>
          <input type="hidden" name="status" value="<?= e($filters['status']) ?>">
        <?php endif; ?>
        <input type="text" name="search" value="<?= e($filters['search'] ?? '') ?>" placeholder="Cari invoice atau pembeli..." style="padding:8px 14px;border:1px solid var(--line);border-radius:8px;font-size:13.5px;outline:none;">
        <button type="submit" style="padding:8px 16px;background:var(--navy);color:#fff;border:0;border-radius:8px;font-weight:700;font-size:13.5px;cursor:pointer;">Cari</button>
      </form>
    </div>

    <!-- Status Tabs -->
    <div style="display:flex;gap:10px;margin-bottom:20px;flex-wrap:wrap;">
      <a href="<?= base_url('dashboard/orders') ?>" class="status-tab <?= empty($filters['status']) ? 'active' : '' ?>">
        Semua (<?= (int)$statusCounts['Semua'] ?>)
      </a>
      <a href="<?= base_url('dashboard/orders?status=Terkirim') ?>" class="status-tab <?= ($filters['status'] ?? '') === 'Terkirim' ? 'active' : '' ?>">
        Terkirim (<?= (int)$statusCounts['Terkirim'] ?>)
      </a>
      <a href="<?= base_url('dashboard/orders?status=Menunggu') ?>" class="status-tab <?= ($filters['status'] ?? '') === 'Menunggu' ? 'active' : '' ?>">
        Menunggu (<?= (int)$statusCounts['Menunggu'] ?>)
      </a>
      <a href="<?= base_url('dashboard/orders?status=Selesai') ?>" class="status-tab <?= ($filters['status'] ?? '') === 'Selesai' ? 'active' : '' ?>">
        Selesai (<?= (int)$statusCounts['Selesai'] ?>)
      </a>
      <a href="<?= base_url('dashboard/orders?status=Dibatalkan') ?>" class="status-tab <?= ($filters['status'] ?? '') === 'Dibatalkan' ? 'active' : '' ?>">
        Dibatalkan (<?= (int)$statusCounts['Dibatalkan'] ?>)
      </a>
    </div>

    <!-- Tabel Pesanan -->
    <div style="background:var(--card);border:1px solid var(--line);border-radius:18px;overflow:hidden;box-shadow:var(--shadow);">
      <?php if (empty($data)): ?>
        <div style="padding:48px 24px;text-align:center;color:var(--muted);">
          Tidak ada data pesanan tiket yang sesuai.
        </div>
      <?php else: ?>
        <div style="overflow-x:auto;">
          <table style="width:100%;border-collapse:collapse;font-size:13.5px;text-align:left;">
            <thead>
              <tr style="background:#F7FAFD;border-bottom:1px solid var(--line);color:var(--navy);">
                <th style="padding:14px 18px;font-weight:700;">Invoice</th>
                <th style="padding:14px 18px;font-weight:700;">Event</th>
                <th style="padding:14px 18px;font-weight:700;">Pembeli</th>
                <th style="padding:14px 18px;font-weight:700;">Qty</th>
                <th style="padding:14px 18px;font-weight:700;">Total Harga</th>
                <th style="padding:14px 18px;font-weight:700;">Status</th>
                <th style="padding:14px 18px;font-weight:700;">Kode Tiket</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($data as $order): ?>
                <tr style="border-bottom:1px solid var(--line);">
                  <td style="padding:14px 18px;font-weight:700;color:var(--brand);"><?= e($order['invoice']) ?></td>
                  <td style="padding:14px 18px;color:var(--navy);font-weight:600;"><?= e($order['event_title']) ?></td>
                  <td style="padding:14px 18px;">
                    <div style="font-weight:600;color:var(--ink);"><?= e($order['buyer_name']) ?></div>
                    <small style="color:var(--muted);"><?= e($order['buyer_email']) ?></small>
                  </td>
                  <td style="padding:14px 18px;font-weight:700;"><?= (int)$order['qty'] ?></td>
                  <td style="padding:14px 18px;font-weight:800;color:var(--navy);"><?= rupiah($order['total_price']) ?></td>
                  <td style="padding:14px 18px;">
                    <span style="font-size:11.5px;font-weight:700;padding:3px 10px;border-radius:6px;background:<?= $order['status'] === 'Terkirim' ? '#E6F4EA' : ($order['status'] === 'Menunggu' ? '#FEF3E6' : ($order['status'] === 'Selesai' ? '#EBF3FA' : '#FCE8E6')) ?>;color:<?= $order['status'] === 'Terkirim' ? '#137333' : ($order['status'] === 'Menunggu' ? '#D97706' : ($order['status'] === 'Selesai' ? 'var(--brand)' : '#C5221F')) ?>;">
                      <?= e($order['status']) ?>
                    </span>
                  </td>
                  <td style="padding:14px 18px;font-family:monospace;font-size:13px;color:var(--muted);"><?= e($order['ticket_code'] ?? '-') ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
