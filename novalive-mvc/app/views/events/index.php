<style>
  .explore-wrap {
    max-width: 1280px;
    margin: 32px auto 60px;
    padding: 0 24px;
  }
  .filter-bar {
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: var(--radius);
    padding: 20px 24px;
    margin-bottom: 32px;
    box-shadow: var(--shadow);
  }
  .filter-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 16px;
    align-items: flex-end;
  }
  .filter-field label {
    display: block;
    font-size: 13px;
    font-weight: 700;
    color: var(--navy);
    margin-bottom: 6px;
  }
  .filter-field input, .filter-field select {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid var(--line);
    border-radius: 8px;
    font-size: 14px;
    outline: none;
    background: #FAFCFF;
    color: var(--ink);
  }
  .filter-actions {
    display: flex;
    gap: 8px;
  }
  .btn-filter {
    background: var(--navy);
    color: #fff;
    font-weight: 700;
    font-size: 14px;
    padding: 10px 18px;
    border-radius: 8px;
    border: 0;
    cursor: pointer;
    flex: 1;
    text-align: center;
  }
  .btn-reset {
    background: var(--cream);
    color: var(--navy);
    font-weight: 700;
    font-size: 14px;
    padding: 10px 14px;
    border-radius: 8px;
    border: 1px solid var(--line);
    text-align: center;
  }
  .pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
    margin-top: 40px;
  }
  .page-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 40px;
    height: 40px;
    padding: 0 12px;
    border-radius: 8px;
    background: var(--card);
    border: 1px solid var(--line);
    font-weight: 700;
    font-size: 14px;
    color: var(--navy);
  }
  .page-link.active {
    background: var(--navy);
    color: #fff;
    border-color: var(--navy);
  }
</style>

<div class="explore-wrap">
  <div style="margin-bottom:28px;">
    <h1 style="font-size:32px;font-weight:800;letter-spacing:-0.02em;">Jelajahi Event</h1>
    <p style="font-size:15px;color:var(--muted);margin-top:4px;">
      Menemukan <strong><?= (int)$total ?></strong> event seru di berbagai kota
    </p>
  </div>

  <!-- Form Filter -->
  <form action="<?= base_url('events') ?>" method="GET" class="filter-bar">
    <div class="filter-grid">
      <div class="filter-field">
        <label for="f-search">Cari Event</label>
        <input type="text" name="search" id="f-search" placeholder="Judul, artis, atau lokasi..." value="<?= e($filters['search'] ?? '') ?>">
      </div>
      <div class="filter-field">
        <label for="f-category">Kategori</label>
        <select name="category" id="f-category">
          <option value="">Semua Kategori</option>
          <?php foreach ($categories as $cat): ?>
            <option value="<?= e($cat['id']) ?>" <?= ($filters['category'] == $cat['id']) ? 'selected' : '' ?>>
              <?= e($cat['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="filter-field">
        <label for="f-city">Kota</label>
        <select name="city" id="f-city">
          <option value="">Semua Kota</option>
          <?php foreach ($cities as $ci): ?>
            <option value="<?= e($ci['slug']) ?>" <?= ($filters['city'] == $ci['slug'] || $filters['city'] == $ci['id']) ? 'selected' : '' ?>>
              <?= e($ci['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="filter-field">
        <label for="f-price">Harga</label>
        <select name="price" id="f-price">
          <option value="">Semua Harga</option>
          <option value="gratis" <?= ($filters['price'] === 'gratis') ? 'selected' : '' ?>>Gratis</option>
          <option value="berbayar" <?= ($filters['price'] === 'berbayar') ? 'selected' : '' ?>>Berbayar</option>
        </select>
      </div>
      <div class="filter-field">
        <label for="f-sort">Urutkan</label>
        <select name="sort" id="f-sort">
          <option value="dateAsc" <?= ($filters['sort'] === 'dateAsc') ? 'selected' : '' ?>>Waktu Terdekat</option>
          <option value="popular" <?= ($filters['sort'] === 'popular') ? 'selected' : '' ?>>Paling Populer</option>
          <option value="priceAsc" <?= ($filters['sort'] === 'priceAsc') ? 'selected' : '' ?>>Harga Terendah</option>
          <option value="priceDesc" <?= ($filters['sort'] === 'priceDesc') ? 'selected' : '' ?>>Harga Tertinggi</option>
        </select>
      </div>
      <div class="filter-actions">
        <button type="submit" class="btn-filter">Terapkan</button>
        <a href="<?= base_url('events') ?>" class="btn-reset">Reset</a>
      </div>
    </div>
  </form>

  <!-- Daftar Kartu Event -->
  <?php if (empty($data)): ?>
    <div style="background:var(--card);border-radius:var(--radius);border:1px dashed var(--line);padding:60px 24px;text-align:center;">
      <div style="font-size:48px;margin-bottom:12px;">🔍</div>
      <h3 style="font-size:20px;color:var(--navy);margin-bottom:8px;">Tidak ada event yang cocok</h3>
      <p style="font-size:15px;color:var(--muted);max-width:440px;margin:0 auto 20px;">
        Coba ubah kata kunci pencarian atau sesuaikan pilihan filter kategori dan kota di atas.
      </p>
      <a href="<?= base_url('events') ?>" class="btn-reset" style="display:inline-block;">Hapus Semua Filter</a>
    </div>
  <?php else: ?>
    <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(280px, 1fr));gap:24px;">
      <?php foreach ($data as $e): ?>
        <a href="<?= base_url('events/show/' . $e['id']) ?>" class="event-card" style="background:#fff;border-radius:16px;overflow:hidden;border:1px solid var(--line);box-shadow:var(--shadow);display:flex;flex-direction:column;transition:transform .2s,box-shadow .2s;">
          <div style="aspect-ratio:16/9;background:var(--navy-deep);position:relative;overflow:hidden;">
            <?php $pUrl = poster_url($e['photo']); ?>
            <?php if ($pUrl): ?>
              <img src="<?= e($pUrl) ?>" alt="<?= e($e['title']) ?>" loading="lazy" style="width:100%;height:100%;object-fit:cover;">
            <?php else: ?>
              <div style="background:linear-gradient(135deg, #0E3254 0%, #23649A 100%);color:var(--gold);display:flex;align-items:center;justify-content:center;font-size:36px;font-weight:800;width:100%;height:100%;">
                <?= strtoupper(mb_substr($e['title'], 0, 1)) ?>
              </div>
            <?php endif; ?>
            <?php if (!empty($e['badge'])): ?>
              <span style="position:absolute;top:10px;left:10px;background:rgba(14,50,84,.9);color:var(--gold);font-size:11px;font-weight:700;padding:4px 8px;border-radius:6px;"><?= e($e['badge']) ?></span>
            <?php endif; ?>
          </div>
          <div style="padding:16px 18px 18px;display:flex;flex-direction:column;flex:1;">
            <div style="font-size:12px;font-weight:700;color:var(--brand);text-transform:uppercase;letter-spacing:.04em;margin-bottom:4px;"><?= e($e['city_name'] ?? 'Indonesia') ?></div>
            <h3 style="font-size:16px;font-weight:700;line-height:1.35;margin-bottom:8px;color:var(--navy);"><?= e($e['title']) ?></h3>
            <div style="display:flex;align-items:center;gap:6px;font-size:13px;color:var(--muted);margin-bottom:12px;">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
              <span><?= tanggal($e['event_date']) ?></span>
            </div>
            <div style="margin-top:auto;padding-top:12px;border-top:1px dashed var(--line);display:flex;justify-content:space-between;align-items:center;">
              <div>
                <span style="font-size:11px;color:var(--muted);display:block;">Mulai dari</span>
                <span style="font-size:15px;font-weight:800;color:var(--navy);"><?= (float)$e['price'] > 0 ? rupiah($e['price']) : 'Gratis' ?></span>
              </div>
              <span style="font-size:12px;font-weight:700;color:var(--brand);"><?= e($e['category_name'] ?? '') ?></span>
            </div>
          </div>
        </a>
      <?php endforeach; ?>
    </div>

    <!-- Paginasi -->
    <?php if ($totalPages > 1): ?>
      <div class="pagination">
        <?php if ($currentPage > 1): ?>
          <a href="<?= base_url('events?' . http_build_query(array_merge($filters, ['page' => $currentPage - 1]))) ?>" class="page-link">‹ Sebelumnya</a>
        <?php endif; ?>

        <?php for ($p = 1; $p <= $totalPages; $p++): ?>
          <a href="<?= base_url('events?' . http_build_query(array_merge($filters, ['page' => $p]))) ?>" class="page-link <?= ($p === $currentPage) ? 'active' : '' ?>">
            <?= $p ?>
          </a>
        <?php endfor; ?>

        <?php if ($currentPage < $totalPages): ?>
          <a href="<?= base_url('events?' . http_build_query(array_merge($filters, ['page' => $currentPage + 1]))) ?>" class="page-link">Berikutnya ›</a>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  <?php endif; ?>
</div>
