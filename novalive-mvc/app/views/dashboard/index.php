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
    transition: background 0.15s, color 0.15s;
  }
  .dash-menu a:hover {
    background: #F0F4F8;
    color: var(--brand);
  }
  .dash-menu a.active {
    background: var(--navy);
    color: #fff;
  }
  .kpi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    margin-bottom: 32px;
  }
  .kpi-card {
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: 16px;
    padding: 24px 20px;
    box-shadow: var(--shadow);
  }
  .kpi-label {
    font-size: 13px;
    font-weight: 700;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: 0.04em;
    margin-bottom: 6px;
  }
  .kpi-val {
    font-size: 28px;
    font-weight: 900;
    color: var(--navy);
    font-family: 'Plus Jakarta Sans', sans-serif;
  }
</style>

<div class="dash-layout">
  <!-- Sidebar Menu -->
  <aside>
    <div style="background:var(--card);border:1px solid var(--line);border-radius:16px;padding:20px;margin-bottom:16px;box-shadow:var(--shadow);">
      <div style="font-size:12px;font-weight:700;color:var(--muted);text-transform:uppercase;">Masuk Sebagai</div>
      <div style="font-size:16px;font-weight:800;color:var(--navy);margin-top:2px;"><?= e($currentUser['full_name']) ?></div>
      <span style="display:inline-block;margin-top:6px;font-size:11px;font-weight:800;padding:2px 8px;border-radius:6px;background:#EBF3FA;color:var(--brand);text-transform:uppercase;">
        <?= e($currentUser['role']) ?>
      </span>
    </div>

    <ul class="dash-menu">
      <li><a href="<?= base_url('dashboard') ?>" class="active">📊 Ringkasan</a></li>
      <li><a href="<?= base_url('dashboard/orders') ?>">🎟️ Pesanan Tiket</a></li>
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
    <div style="margin-bottom:28px;">
      <h1 style="font-size:28px;font-weight:800;color:var(--navy);">Ringkasan Aktivitas</h1>
      <p style="font-size:14.5px;color:var(--muted);margin-top:4px;">Pantau performa penjualan tiket dan metrik event Anda secara real-time</p>
    </div>

    <!-- 4 KPI Cards -->
    <div class="kpi-grid">
      <div class="kpi-card" style="border-top:4px solid var(--brand);">
        <div class="kpi-label">Total Event Aktif</div>
        <div class="kpi-val"><?= (int)$totalEvents ?></div>
      </div>
      <div class="kpi-card" style="border-top:4px solid #F97316;">
        <div class="kpi-label">Total Tiket Terjual</div>
        <div class="kpi-val"><?= number_format($totalSold, 0, ',', '.') ?></div>
      </div>
      <div class="kpi-card" style="border-top:4px solid #14B8A6;">
        <div class="kpi-label">Total Pendapatan</div>
        <div class="kpi-val"><?= rupiah($totalRevenue) ?></div>
      </div>
      <div class="kpi-card" style="border-top:4px solid #3B82F6;">
        <div class="kpi-label">Total Transaksi</div>
        <div class="kpi-val"><?= (int)$totalOrders ?></div>
      </div>
    </div>

    <!-- Grid 2 Kolom: Pesanan Terbaru & Tiket Hampir Habis -->
    <div style="display:grid;grid-template-columns:1.3fr 1fr;gap:24px;">
      <!-- Pesanan Terbaru -->
      <div style="background:var(--card);border:1px solid var(--line);border-radius:18px;padding:24px;box-shadow:var(--shadow);">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;">
          <h2 style="font-size:18px;font-weight:800;color:var(--navy);">Pesanan Terbaru</h2>
          <a href="<?= base_url('dashboard/orders') ?>" style="font-size:13px;font-weight:700;color:var(--brand);">Lihat Semua →</a>
        </div>
        <div style="display:grid;gap:12px;">
          <?php foreach ($recentOrders as $ro): ?>
            <div style="display:flex;justify-content:space-between;align-items:center;padding:12px;background:#F9FBFE;border:1px solid var(--line);border-radius:10px;">
              <div>
                <div style="font-size:13.5px;font-weight:700;color:var(--navy);"><?= e($ro['buyer_name']) ?></div>
                <div style="font-size:12px;color:var(--muted);"><?= e($ro['invoice']) ?> • <?= e($ro['event_title']) ?></div>
              </div>
              <div style="text-align:right;">
                <div style="font-size:13.5px;font-weight:800;color:var(--navy);"><?= rupiah($ro['total_price']) ?></div>
                <span style="font-size:11px;font-weight:700;padding:2px 8px;border-radius:4px;background:<?= $ro['status'] === 'Terkirim' ? '#E6F4EA' : ($ro['status'] === 'Menunggu' ? '#FEF3E6' : '#F0F4F8') ?>;color:<?= $ro['status'] === 'Terkirim' ? '#137333' : ($ro['status'] === 'Menunggu' ? '#D97706' : 'var(--navy)') ?>;">
                  <?= e($ro['status']) ?>
                </span>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Tiket Menipis -->
      <div style="background:var(--card);border:1px solid var(--line);border-radius:18px;padding:24px;box-shadow:var(--shadow);">
        <h2 style="font-size:18px;font-weight:800;color:var(--navy);margin-bottom:18px;">Tiket Hampir Habis</h2>
        <div style="display:grid;gap:14px;">
          <?php foreach ($lowTickets as $lt): ?>
            <div style="padding:12px;background:#FEF9F8;border:1px solid #FCDFD8;border-radius:10px;">
              <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;">
                <strong style="font-size:13.5px;color:var(--navy);"><?= e($lt['title']) ?></strong>
                <span style="font-size:12px;font-weight:800;color:#C5221F;"><?= $lt['remaining'] ?> tersisa</span>
              </div>
              <div style="font-size:12px;color:var(--muted);">
                Terjual <?= $lt['sold'] ?> dari kuota <?= $lt['quota'] ?> tiket
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</div>
