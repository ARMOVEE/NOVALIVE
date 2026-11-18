<style>
  .detail-wrap {
    max-width: 1200px;
    margin: 36px auto 60px;
    padding: 0 24px;
  }
  .detail-grid {
    display: grid;
    grid-template-columns: 1.8fr 1.2fr;
    gap: 36px;
    align-items: start;
  }
  @media (max-width: 900px) {
    .detail-grid { grid-template-columns: 1fr; }
  }
  .detail-poster {
    border-radius: 20px;
    overflow: hidden;
    background: var(--navy-deep);
    aspect-ratio: 16/9;
    box-shadow: var(--shadow-lg);
    position: relative;
    border: 1px solid var(--line);
  }
  .detail-poster img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  .detail-card {
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: 20px;
    padding: 32px;
    box-shadow: var(--shadow);
  }
  .ticket-box {
    background: var(--cream);
    border: 1px solid #E8DFCB;
    border-radius: 16px;
    padding: 24px;
    margin-top: 24px;
  }
  .info-item {
    display: flex;
    gap: 14px;
    align-items: flex-start;
    margin-bottom: 18px;
  }
  .info-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: #E8F0F8;
    color: var(--brand);
    display: grid;
    place-items: center;
    flex-shrink: 0;
  }
  .info-title {
    font-size: 12px;
    font-weight: 700;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: 0.04em;
  }
  .info-val {
    font-size: 15px;
    font-weight: 700;
    color: var(--navy);
    margin-top: 2px;
  }
</style>

<div class="detail-wrap">
  <!-- Breadcrumb -->
  <div style="font-size:14px;color:var(--muted);margin-bottom:20px;display:flex;gap:8px;align-items:center;">
    <a href="<?= base_url() ?>">Beranda</a>
    <span>›</span>
    <a href="<?= base_url('events') ?>">Event</a>
    <span>›</span>
    <a href="<?= base_url('kota/' . ($event['city_slug'] ?? '')) ?>"><?= e($event['city_name'] ?? 'Kota') ?></a>
    <span>›</span>
    <span style="color:var(--navy);font-weight:600;"><?= e($event['title']) ?></span>
  </div>

  <div class="detail-grid">
    <!-- Kolom Kiri: Poster & Deskripsi -->
    <div>
      <div class="detail-poster">
        <?php $pUrl = poster_url($event['photo']); ?>
        <?php if ($pUrl): ?>
          <img src="<?= e($pUrl) ?>" alt="<?= e($event['title']) ?>">
        <?php else: ?>
          <div style="background:linear-gradient(135deg, #0E3254 0%, #23649A 100%);color:var(--gold);display:flex;align-items:center;justify-content:center;font-size:64px;font-weight:800;width:100%;height:100%;">
            <?= strtoupper(mb_substr($event['title'], 0, 1)) ?>
          </div>
        <?php endif; ?>
        <?php if (!empty($event['badge'])): ?>
          <span style="position:absolute;top:16px;left:16px;background:rgba(14,50,84,.95);color:var(--gold);font-size:13px;font-weight:700;padding:6px 14px;border-radius:8px;box-shadow:var(--shadow);">
            <?= e($event['badge']) ?>
          </span>
        <?php endif; ?>
      </div>

      <div style="background:var(--card);border-radius:20px;border:1px solid var(--line);padding:32px;margin-top:28px;box-shadow:var(--shadow);">
        <h2 style="font-size:20px;font-weight:800;color:var(--navy);margin-bottom:14px;">Deskripsi Event</h2>
        <p style="font-size:15px;line-height:1.7;color:var(--ink);">
          <?= nl2br(e($event['description'] ?? 'Tidak ada deskripsi rinci untuk event ini.')) ?>
        </p>

        <div style="margin-top:28px;padding-top:20px;border-top:1px dashed var(--line);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
          <div>
            <span style="font-size:12px;color:var(--muted);display:block;">Diselenggarakan oleh</span>
            <strong style="font-size:15px;color:var(--navy);"><?= e($event['organizer']) ?></strong>
          </div>
          <span style="font-size:13px;padding:6px 12px;background:#F0F4F8;color:var(--brand);border-radius:8px;font-weight:700;">
            Kode: <?= e($event['code']) ?>
          </span>
        </div>
      </div>
    </div>

    <!-- Kolom Kanan: Info Waktu, Lokasi, Harga & Aksi Beli -->
    <div>
      <div class="detail-card">
        <div style="display:flex;gap:8px;margin-bottom:12px;">
          <span style="font-size:12.5px;font-weight:700;padding:4px 10px;background:#EBF3FA;color:var(--brand);border-radius:6px;">
            <?= e($event['category_name'] ?? 'Umum') ?>
          </span>
          <span style="font-size:12.5px;font-weight:700;padding:4px 10px;background:#FEF3E6;color:#D97706;border-radius:6px;">
            📍 <?= e($event['city_name'] ?? '') ?>
          </span>
        </div>

        <h1 style="font-size:26px;font-weight:800;line-height:1.25;color:var(--navy);margin-bottom:20px;">
          <?= e($event['title']) ?>
        </h1>

        <div class="info-item">
          <div class="info-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
          </div>
          <div>
            <div class="info-title">Tanggal & Waktu</div>
            <div class="info-val"><?= tanggal($event['event_date'], 'l, d F Y') ?></div>
            <div style="font-size:13.5px;color:var(--muted);margin-top:2px;"><?= e($event['event_time'] ?? '19:00') ?> WIB</div>
          </div>
        </div>

        <div class="info-item">
          <div class="info-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
          </div>
          <div>
            <div class="info-title">Lokasi / Tempat</div>
            <div class="info-val"><?= e($event['venue'] ?? 'Lokasi Ditentukan') ?></div>
            <div style="font-size:13.5px;color:var(--muted);margin-top:2px;"><?= e($event['city_name'] ?? '') ?></div>
          </div>
        </div>

        <!-- Sisa Tiket -->
        <div style="margin:20px 0;">
          <?php
            $quota = (int)$event['quota'];
            $sold = (int)$event['sold'];
            $remaining = max(0, $quota - $sold);
            $pct = $quota > 0 ? min(100, round(($sold / $quota) * 100)) : 0;
          ?>
          <div style="display:flex;justify-content:space-between;font-size:13px;font-weight:700;margin-bottom:6px;">
            <span style="color:var(--muted);">Ketersediaan Tiket:</span>
            <span style="color:var(--navy);"><?= $remaining ?> tiket tersisa</span>
          </div>
          <div style="width:100%;height:8px;background:var(--line);border-radius:999px;overflow:hidden;">
            <div style="width:<?= $pct ?>%;height:100%;background:<?= $pct >= 85 ? '#C5221F' : 'var(--brand)' ?>;"></div>
          </div>
        </div>

        <!-- Box Tiket & Pesan Sekarang -->
        <div class="ticket-box">
          <span style="font-size:13px;color:var(--muted);display:block;">Harga Tiket</span>
          <div style="font-size:28px;font-weight:900;color:var(--navy);font-family:'Plus Jakarta Sans',sans-serif;margin:4px 0 14px;">
            <?= (float)$event['price'] > 0 ? rupiah($event['price']) : 'Gratis' ?>
          </div>
          <button type="button" onclick="alert('Demo Mockup: Pembelian tiket untuk <?= addslashes(e($event['title'])) ?> berhasil disimulasikan!')" style="width:100%;padding:14px;border:0;border-radius:999px;background:var(--navy);color:#fff;font-size:15px;font-weight:700;cursor:pointer;box-shadow:var(--shadow);transition:transform .15s;">
            Pesan Sekarang
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Event Terkait di Kota yang Sama -->
  <?php if (!empty($related)): ?>
    <div style="margin-top:60px;">
      <h2 style="font-size:22px;font-weight:800;color:var(--navy);margin-bottom:20px;">
        Event Lainnya di <?= e($event['city_name'] ?? 'Kota Ini') ?>
      </h2>
      <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(260px, 1fr));gap:20px;">
        <?php foreach ($related as $rel): ?>
          <?php if ($rel['id'] == $event['id']) continue; ?>
          <a href="<?= base_url('events/show/' . $rel['id']) ?>" style="background:var(--card);border-radius:14px;overflow:hidden;border:1px solid var(--line);box-shadow:var(--shadow);display:flex;flex-direction:column;">
            <div style="aspect-ratio:16/9;background:var(--navy-deep);position:relative;">
              <?php $rpUrl = poster_url($rel['photo']); ?>
              <?php if ($rpUrl): ?>
                <img src="<?= e($rpUrl) ?>" alt="<?= e($rel['title']) ?>" style="width:100%;height:100%;object-fit:cover;">
              <?php else: ?>
                <div style="background:linear-gradient(135deg, #0E3254 0%, #23649A 100%);color:var(--gold);display:flex;align-items:center;justify-content:center;font-size:28px;font-weight:800;width:100%;height:100%;">
                  <?= strtoupper(mb_substr($rel['title'], 0, 1)) ?>
                </div>
              <?php endif; ?>
            </div>
            <div style="padding:14px;flex:1;display:flex;flex-direction:column;">
              <h3 style="font-size:15px;font-weight:700;color:var(--navy);margin-bottom:6px;"><?= e($rel['title']) ?></h3>
              <div style="font-size:12.5px;color:var(--muted);margin-top:auto;"><?= tanggal($rel['event_date']) ?></div>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endif; ?>
</div>
