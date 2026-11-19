<?php
/**
 * View: Jelajahi Event (Index)
 * Samakan persis dengan desain mockup (NOVA LIVE — Jelajahi Event.html)
 */

$activeCity = null;
if (!empty($filters['city'])) {
    foreach ($cities as $c) {
        if (strcasecmp($c['slug'], $filters['city']) === 0 || 
            strcasecmp($c['name'], $filters['city']) === 0 || 
            (!empty($c['search_alias']) && strcasecmp($c['search_alias'], $filters['city']) === 0)) {
            $activeCity = $c;
            break;
        }
    }
}
$isCityMode = ($activeCity !== null);
$cityDisplayName = '';
if ($isCityMode) {
    $cityDisplayName = (strtolower($activeCity['slug']) === 'denpasar' || strtolower($filters['city']) === 'bali') ? 'Bali' : $activeCity['name'];
}

$activeCategory = null;
if (!empty($filters['category'])) {
    foreach ($categories as $cat) {
        if (strcasecmp($cat['name'], $filters['category']) === 0 || (is_numeric($filters['category']) && (int)$cat['id'] === (int)$filters['category'])) {
            $activeCategory = $cat;
            break;
        }
    }
}

// Hitung filter aktif
$activeFilterCount = 0;
if (!empty($filters['city'])) $activeFilterCount++;
if (!empty($filters['category'])) $activeFilterCount++;
if (!empty($filters['time'])) $activeFilterCount++;
if (!empty($filters['price'])) $activeFilterCount++;

if (!function_exists('getAvColor')) {
    function getAvColor($str) {
        $colors = ['#E2E8F0','#FEF3C7','#DCFCE7','#DBEAFE','#FCE7F3','#EDE9FE','#CCFBF1'];
        return $colors[abs(crc32((string)$str)) % count($colors)];
    }
}
if (!function_exists('getInitials')) {
    function getInitials($str) {
        $words = preg_split('/\s+/', trim((string)$str));
        if (count($words) >= 2) {
            return strtoupper(mb_substr($words[0], 0, 1) . mb_substr($words[1], 0, 1));
        }
        return strtoupper(mb_substr((string)$str, 0, 2));
    }
}
?>

<style>
/* Page head */
.page-head { padding: 26px 0 6px; }
.page-head .breadcrumb { display: flex; align-items: center; gap: 8px; font-size: 13.5px; color: var(--muted); margin-bottom: 12px; flex-wrap: wrap; }
.page-head .crumb-link { display: inline-flex; align-items: center; gap: 6px; color: var(--navy); text-decoration: none; font-weight: 700; padding: 6px 14px 6px 10px; border-radius: 999px; background: var(--cream); border: 1px solid var(--line); transition: all .2s; }
.page-head .crumb-link:hover { background: #ECE4D0; border-color: var(--gold-deep); color: var(--navy2); transform: translateX(-2px); }
.page-head .crumb-link svg { color: var(--brand); }
.page-head .crumb-sep { color: var(--muted); opacity: .5; }
.page-head .crumb-current { color: var(--muted); font-weight: 500; }
.page-head h1 { font-size: clamp(26px, 4vw, 38px); font-weight: 800; margin: 0 0 6px; color: var(--ink); font-family: var(--font-display, 'Plus Jakarta Sans', sans-serif); }
.page-head p { margin: 0; color: var(--muted); font-size: 15px; }
.page-head p b { color: var(--brand); font-weight: 700; }
.city-kicker { font-size: 14px; font-weight: 700; color: var(--brand); margin-bottom: 4px; display: flex; align-items: center; gap: 6px; text-transform: uppercase; letter-spacing: .04em; }

/* Toolbar */
.toolbar { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 16px 0 20px; flex-wrap: wrap; }
.btn-filter { display: inline-flex; align-items: center; gap: 9px; background: var(--card); border: 1px solid var(--line); border-radius: 12px; padding: 11px 18px; min-height: 44px; font-weight: 600; font-size: 14px; box-shadow: 0 1px 2px rgba(14,42,71,.05); cursor: pointer; transition: all .2s; color: var(--ink); }
.btn-filter:hover { border-color: var(--sky); }
.btn-filter[aria-expanded="true"] { background: var(--cream); border-color: var(--gold-deep); color: var(--navy2); }
.btn-filter .filter-chev { margin-left: 2px; color: var(--muted); transition: transform .2s; }
.btn-filter[aria-expanded="true"] .filter-chev { transform: rotate(180deg); }
.btn-filter .count-badge { background: var(--brand); color: #fff; font-size: 11px; font-weight: 700; border-radius: 999px; min-width: 20px; height: 20px; display: none; place-items: center; padding: 0 6px; }
.btn-filter .count-badge.on { display: grid; }

.sort-wrap { position: relative; }
.btn-sort { display: inline-flex; align-items: center; gap: 8px; background: var(--card); border: 1px solid var(--line); border-radius: 12px; padding: 0 16px; height: 44px; min-height: 44px; font-weight: 600; font-size: 14px; box-shadow: 0 1px 2px rgba(14,42,71,.05); cursor: pointer; color: var(--ink); }
.btn-sort:hover { border-color: var(--sky); }
.btn-sort svg { color: var(--muted); transition: transform .18s; }
.btn-sort[aria-expanded="true"] svg { transform: rotate(180deg); }
.sort-menu { position: absolute; top: calc(100% + 8px); right: 0; min-width: 224px; background: var(--card); border: 1px solid var(--line); border-radius: 12px; box-shadow: var(--shadow); padding: 6px; display: none; z-index: 40; }
.sort-menu.open { display: block; }
.sort-item { display: flex; align-items: center; gap: 10px; width: 100%; text-align: left; padding: 10px 12px; border-radius: 8px; font-size: 14px; font-weight: 500; color: var(--ink); background: none; border: 0; cursor: pointer; }
.sort-item:hover { background: var(--cream); }
.sort-item.active { font-weight: 700; color: var(--brand); }
.sort-item .check { width: 16px; height: 16px; flex: none; color: var(--brand); visibility: hidden; }
.sort-item.active .check { visibility: visible; }

/* Filter row (4 dropdown) */
.filter-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; padding: 2px 0 22px; }
.filter-row.hidden { display: none; }
.f-group label { display: block; font-size: 14px; font-weight: 600; color: var(--ink); margin-bottom: 8px; }
.f-group select { width: 100%; height: 48px; border: 1px solid var(--line); border-radius: 10px; padding: 0 38px 0 14px; font: inherit; font-size: 14.5px; color: var(--ink); outline: none; cursor: pointer; appearance: none; -webkit-appearance: none; background-color: var(--card); background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%234A607A' stroke-width='2.4' stroke-linecap='round' stroke-linejoin='round'><path d='m6 9 6 6 6-6'/></svg>"); background-repeat: no-repeat; background-position: right 14px center; }
.f-group select:focus { border-color: var(--brand); }
@media (max-width: 900px) { .filter-row { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 520px) { .filter-row { grid-template-columns: 1fr; } }

/* Grid & Card */
.grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 22px; padding-bottom: 8px; }
@media (max-width: 1080px) { .grid { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 820px) { .grid { grid-template-columns: repeat(2, 1fr); gap: 16px; } }
@media (max-width: 520px) { .grid { grid-template-columns: 1fr; } }

.card { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius); overflow: hidden; display: flex; flex-direction: column; transition: transform .18s, box-shadow .18s; text-decoration: none; color: inherit; cursor: pointer; }
.card:hover { transform: translateY(-4px); box-shadow: var(--shadow); }
.card:focus-visible { outline: 3px solid var(--brand); outline-offset: 2px; }
.card-media { position: relative; aspect-ratio: 16/9; overflow: hidden; background: var(--navy2); }
.card-media .poster-ph { width: 100%; height: 100%; background: linear-gradient(135deg, #0E3254 0%, #23649A 100%); color: var(--gold); display: flex; align-items: center; justify-content: center; font-size: 54px; font-weight: 800; font-family: var(--font-display, 'Plus Jakarta Sans', sans-serif); user-select: none; }
.badge { position: absolute; bottom: 8px; right: 8px; z-index: 3; background: var(--gold-deep); color: #0E2A47; font-size: 10.5px; font-weight: 700; padding: 4px 9px; border-radius: 999px; letter-spacing: .2px; line-height: 1.3; }
.badge.new { background: var(--brand); color: #fff; }

.card-body { padding: 16px 16px 14px; display: flex; flex-direction: column; gap: 9px; flex: 1; }
.card-body h3 { margin: 0; font-size: 18px; font-weight: 800; line-height: 1.3; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 47px; color: var(--ink); font-family: var(--font-display, 'Plus Jakarta Sans', sans-serif); }
.meta { display: flex; align-items: center; gap: 8px; font-size: 13.5px; color: var(--muted); }
.meta svg { flex: none; color: var(--brand); }
.price { font-size: 17px; font-weight: 800; color: var(--ink); font-family: var(--font-display, 'Plus Jakarta Sans', sans-serif); }
.price small { display: block; font-size: 11.5px; font-weight: 500; color: var(--muted); margin-bottom: 2px; }
.card-foot { margin-top: auto; border-top: 1px dashed var(--line); padding-top: 12px; display: flex; align-items: center; gap: 10px; }
.org-avatar { width: 32px; height: 32px; border-radius: 50%; flex: none; display: grid; place-items: center; font-size: 11px; font-weight: 800; color: #0E2A47; }
.org-name { font-size: 13.5px; font-weight: 700; color: var(--ink); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

/* Empty state */
.empty { text-align: center; padding: 56px 20px; background: var(--card); border: 1px dashed var(--line); border-radius: var(--radius); margin-bottom: 10px; }
.empty h3 { margin: 0 0 8px; font-size: 19px; color: var(--ink); }
.empty p { margin: 0 0 18px; color: var(--muted); font-size: 14px; }
.btn-apply { background: var(--navy); color: #fff; font-weight: 700; font-size: 14px; padding: 11px 22px; border-radius: 10px; min-height: 44px; display: inline-flex; align-items: center; justify-content: center; text-decoration: none; border: 0; cursor: pointer; }
.btn-apply:hover { background: var(--brand); color: #fff; }

/* Pagination */
.pagination { display: flex; justify-content: center; align-items: center; gap: 8px; margin: 32px 0 20px; }
.page-link { display: inline-flex; align-items: center; justify-content: center; min-width: 40px; height: 40px; padding: 0 14px; border-radius: 10px; background: var(--card); border: 1px solid var(--line); font-weight: 700; font-size: 14px; color: var(--brand); text-decoration: none; box-shadow: 0 1px 2px rgba(14,42,71,.05); transition: all .2s; }
.page-link:hover { border-color: var(--brand); }
.page-link.active { background: var(--brand); color: #fff; border-color: var(--brand); }
</style>

<div class="wrap" style="padding-top:16px;">
  <!-- PAGE HEAD -->
  <div class="page-head">
    <nav class="breadcrumb" aria-label="Navigasi ke Beranda">
      <?php if ($isCityMode): ?>
        <a class="crumb-link" href="<?= base_url('events') ?>" id="cityBack" aria-label="Kembali ke Jelajahi Semua Event">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
          Jelajahi Semua Event
        </a>
        <span class="crumb-sep">/</span>
        <span class="crumb-current">Event di <?= e($cityDisplayName) ?></span>
      <?php else: ?>
        <a class="crumb-link" href="<?= base_url() ?>" aria-label="Kembali ke Beranda">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
          Kembali ke Beranda
        </a>
        <span class="crumb-sep">/</span>
        <span class="crumb-current">Jelajahi Event</span>
      <?php endif; ?>
    </nav>

    <?php if ($isCityMode): ?>
      <div class="city-kicker" id="cityKicker">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 21s-7-5.5-7-11a7 7 0 1 1 14 0c0 5.5-7 11-7 11z"/><circle cx="12" cy="10" r="2.6"/></svg>
        Event Menarik di
      </div>
      <h1 id="pageTitle"><?= e($cityDisplayName) ?></h1>
      <p id="resultCount">
        Menampilkan <b><?= count($data) ?> dari <?= (int)$total ?> event</b> di <?= e($cityDisplayName) ?><?= !empty($activeCategory) ? ' di kategori ' . e($activeCategory['name']) : '' ?><?= !empty($filters['search']) ? ' untuk “' . e($filters['search']) . '”' : '' ?>
      </p>
    <?php else: ?>
      <h1 id="pageTitle"><?= !empty($activeCategory) ? 'Event ' . e($activeCategory['name']) : 'Jelajahi Event' ?></h1>
      <p id="resultCount">
        <?php if (!empty($data)): ?>
          Menampilkan <b><?= count($data) ?> dari <?= (int)$total ?> event</b><?= !empty($activeCategory) ? ' di kategori ' . e($activeCategory['name']) : '' ?><?= !empty($filters['search']) ? ' untuk “' . e($filters['search']) . '”' : '' ?>
        <?php else: ?>
          Tidak ada event yang cocok dengan filter kamu.
        <?php endif; ?>
      </p>
    <?php endif; ?>
  </div>

  <!-- TOOLBAR & FILTER ROW -->
  <form id="filterForm" action="<?= base_url('events') ?>" method="GET">
    <?php if (!empty($filters['search'])): ?>
      <input type="hidden" name="search" value="<?= e($filters['search']) ?>">
    <?php endif; ?>
    <input type="hidden" name="sort" id="sortInput" value="<?= e($filters['sort'] ?? 'date') ?>">

    <!-- TOOLBAR -->
    <div class="toolbar">
      <button class="btn-filter" id="filterBtn" type="button" aria-expanded="true" aria-controls="filterRow">
        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 5h18l-7 8v5l-4 2v-7L3 5z"/></svg>
        Filter
        <span class="count-badge <?= $activeFilterCount > 0 ? 'on' : '' ?>" id="filterBadge"><?= $activeFilterCount ?></span>
        <svg class="filter-chev" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
      </button>

      <div class="sort-wrap">
        <button class="btn-sort" id="sortBtn" type="button" aria-haspopup="menu" aria-expanded="false">
          Urutkan
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
        </button>
        <div class="sort-menu" id="sortMenu" role="menu" aria-label="Urutkan event">
          <?php $curSort = $filters['sort'] ?? 'date'; ?>
          <button class="sort-item <?= in_array($curSort, ['date', 'dateAsc']) ? 'active' : '' ?>" type="button" role="menuitemradio" aria-checked="<?= in_array($curSort, ['date', 'dateAsc']) ? 'true' : 'false' ?>" data-sort="date">
            <svg class="check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>Tanggal Terdekat
          </button>
          <button class="sort-item <?= $curSort === 'priceAsc' ? 'active' : '' ?>" type="button" role="menuitemradio" aria-checked="<?= $curSort === 'priceAsc' ? 'true' : 'false' ?>" data-sort="priceAsc">
            <svg class="check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>Harga Terendah
          </button>
          <button class="sort-item <?= $curSort === 'priceDesc' ? 'active' : '' ?>" type="button" role="menuitemradio" aria-checked="<?= $curSort === 'priceDesc' ? 'true' : 'false' ?>" data-sort="priceDesc">
            <svg class="check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>Harga Tertinggi
          </button>
          <button class="sort-item <?= $curSort === 'name' ? 'active' : '' ?>" type="button" role="menuitemradio" aria-checked="<?= $curSort === 'name' ? 'true' : 'false' ?>" data-sort="name">
            <svg class="check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>Nama A–Z
          </button>
        </div>
      </div>
    </div>

    <!-- FILTER ROW (4 DROPDOWN) -->
    <div class="filter-row" id="filterRow">
      <div class="f-group">
        <label for="locSelect">Lokasi</label>
        <select name="city" id="locSelect" onchange="this.form.submit()">
          <option value="">Pilih Lokasi</option>
          <?php foreach ($cities as $ci): ?>
            <?php 
              $isSelected = false;
              if (!empty($filters['city'])) {
                  $isSelected = (strcasecmp($filters['city'], $ci['slug']) === 0 || strcasecmp($filters['city'], $ci['name']) === 0 || (!empty($ci['search_alias']) && strcasecmp($filters['city'], $ci['search_alias']) === 0));
              }
              $cLabel = (strtolower($ci['slug']) === 'denpasar' || strtolower($ci['name']) === 'denpasar') ? 'Bali' : $ci['name'];
            ?>
            <option value="<?= e($ci['slug']) ?>" <?= $isSelected ? 'selected' : '' ?>>
              <?= e($cLabel) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="f-group">
        <label for="catSelect">Kategori</label>
        <select name="category" id="catSelect" onchange="this.form.submit()">
          <option value="">Pilih Kategori</option>
          <?php foreach ($categories as $cat): ?>
            <?php 
              $isSelected = false;
              if (!empty($filters['category'])) {
                  $isSelected = (strcasecmp($filters['category'], $cat['name']) === 0 || (is_numeric($filters['category']) && (int)$cat['id'] === (int)$filters['category']));
              }
            ?>
            <option value="<?= e($cat['name']) ?>" <?= $isSelected ? 'selected' : '' ?>>
              <?= e($cat['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="f-group">
        <label for="timeSelect">Waktu</label>
        <select name="time" id="timeSelect" onchange="this.form.submit()">
          <option value="">Pilih Waktu</option>
          <option value="minggu-ini" <?= ($filters['time'] ?? '') === 'minggu-ini' ? 'selected' : '' ?>>Minggu Ini</option>
          <option value="minggu-depan" <?= ($filters['time'] ?? '') === 'minggu-depan' ? 'selected' : '' ?>>Minggu Depan</option>
          <option value="bulan-ini" <?= ($filters['time'] ?? '') === 'bulan-ini' ? 'selected' : '' ?>>Bulan Ini</option>
          <option value="bulan-depan" <?= ($filters['time'] ?? '') === 'bulan-depan' ? 'selected' : '' ?>>Bulan Depan</option>
        </select>
      </div>

      <div class="f-group">
        <label for="priceSelect">Harga</label>
        <select name="price" id="priceSelect" onchange="this.form.submit()">
          <option value="">Pilih Harga</option>
          <option value="gratis" <?= ($filters['price'] ?? '') === 'gratis' ? 'selected' : '' ?>>Gratis</option>
          <option value="berbayar" <?= ($filters['price'] ?? '') === 'berbayar' ? 'selected' : '' ?>>Berbayar</option>
        </select>
      </div>
    </div>
  </form>

  <!-- GRID KARTU / EMPTY STATE -->
  <?php if (empty($data)): ?>
    <div class="empty show" id="emptyState">
      <h3>Tidak ada event yang cocok</h3>
      <p>Coba ubah kata kunci, kategori, atau filter yang kamu pakai.</p>
      <a class="btn-apply" id="emptyReset" href="<?= base_url('events') ?>">Atur Ulang Filter</a>
    </div>
  <?php else: ?>
    <div class="grid" id="grid">
      <?php foreach ($data as $e): ?>
        <a class="card" href="<?= base_url('events/show/' . $e['id']) ?>" tabindex="0" role="button" aria-label="Lihat detail <?= e($e['title']) ?>">
          <div class="card-media">
            <div class="poster-ph" aria-hidden="true">
              <?= strtoupper(mb_substr($e['title'], 0, 1)) ?>
            </div>
            <?php if (!empty($e['badge'])): ?>
              <span class="badge <?= trim($e['badge']) === 'BARU' ? 'new' : '' ?>">
                <?= e($e['badge']) ?>
              </span>
            <?php endif; ?>
          </div>
          <div class="card-body">
            <h3><?= e($e['title']) ?></h3>
            <div class="meta">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
              <span><?= tanggal($e['event_date']) ?></span>
            </div>
            <div class="meta">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
              <span><?= !empty($e['session_count']) ? e($e['session_count']) : '1 Sesi' ?></span>
            </div>
            <div class="price">
              <small>Mulai dari</small>
              <?= (float)$e['price'] > 0 ? rupiah($e['price']) : 'Gratis' ?>
            </div>
            <div class="card-foot">
              <span class="org-avatar" style="background:<?= getAvColor($e['organizer']) ?>;">
                <?= getInitials($e['organizer']) ?>
              </span>
              <span class="org-name"><?= e($e['organizer']) ?></span>
            </div>
          </div>
        </a>
      <?php endforeach; ?>
    </div>

    <!-- PAGINASI -->
    <?php if ($totalPages > 1): ?>
      <div class="pagination">
        <?php if ($currentPage > 1): ?>
          <a href="<?= base_url('events?' . http_build_query(array_merge($filters, ['page' => $currentPage - 1]))) ?>" class="page-link" aria-label="Halaman sebelumnya">‹</a>
        <?php endif; ?>

        <?php for ($p = 1; $p <= $totalPages; $p++): ?>
          <a href="<?= base_url('events?' . http_build_query(array_merge($filters, ['page' => $p]))) ?>" class="page-link <?= ($p === $currentPage) ? 'active' : '' ?>">
            <?= $p ?>
          </a>
        <?php endfor; ?>

        <?php if ($currentPage < $totalPages): ?>
          <a href="<?= base_url('events?' . http_build_query(array_merge($filters, ['page' => $currentPage + 1]))) ?>" class="page-link" aria-label="Halaman berikutnya">›</a>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  <?php endif; ?>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
  var filterBtn = document.getElementById("filterBtn");
  var filterRow = document.getElementById("filterRow");
  if (filterBtn && filterRow) {
    filterBtn.addEventListener("click", function () {
      var hidden = filterRow.classList.toggle("hidden");
      filterBtn.setAttribute("aria-expanded", hidden ? "false" : "true");
    });
  }

  var sortBtn = document.getElementById("sortBtn");
  var sortMenu = document.getElementById("sortMenu");
  var sortInput = document.getElementById("sortInput");
  var filterForm = document.getElementById("filterForm");
  if (sortBtn && sortMenu && sortInput && filterForm) {
    function closeSort() {
      sortMenu.classList.remove("open");
      sortBtn.setAttribute("aria-expanded", "false");
    }
    sortBtn.addEventListener("click", function (ev) {
      ev.stopPropagation();
      var open = sortMenu.classList.toggle("open");
      sortBtn.setAttribute("aria-expanded", open ? "true" : "false");
    });
    sortMenu.querySelectorAll(".sort-item").forEach(function (item) {
      item.addEventListener("click", function () {
        var val = item.getAttribute("data-sort");
        sortInput.value = val;
        closeSort();
        filterForm.submit();
      });
    });
    document.addEventListener("click", function (ev) {
      if (!sortMenu.contains(ev.target)) closeSort();
    });
    document.addEventListener("keydown", function (ev) {
      if (ev.key === "Escape") closeSort();
    });
  }
});
</script>
