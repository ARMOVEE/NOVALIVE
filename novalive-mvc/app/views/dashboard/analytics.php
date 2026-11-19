<?php
/**
 * Halaman Laporan & Analitik — Gaya Quixotic
 * NOVA LIVE MVC — Tahap 3
 */
$currentUser = $currentUser ?? ['full_name' => 'Admin NOVA LIVE', 'role' => 'admin'];
$userInitials = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $currentUser['full_name'] ?? 'AD'), 0, 2));
if (strlen($userInitials) < 2) $userInitials = 'AD';

$totalCategorySold = 0;
$totalCategoryRev = 0.0;
foreach ($categorySales as $c) {
    $totalCategorySold += (int)$c['tickets_sold'];
    $totalCategoryRev += (float)$c['total_revenue'];
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <meta name="color-scheme" content="light" />
  <title><?= e($pageTitle ?? 'NOVA LIVE — Analitik & Laporan') ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <style>
    button, [role="button"], select, summary, input:not([type="checkbox"], [type="radio"]) { min-height: 2rem; }
    :root{
      --navy:#0E3254;
      --navy-2:#15497A;
      --blue:#23649A;
      --sky:#509FC8;
      --bar:#B7D0E2;
      --bar-soft:#CFE0ED;
      --gold:#EAD4A2;
      --gold-deep:#D4B475;
      --gold-dark:#8C6D1F;
      --cream:#F5EFE0;
      --page:#E8EAEC;
      --panel:#F5F6F5;
      --card:#FFFFFF;
      --ink:#14232F;
      --muted:#7A8794;
      --line:#E7EBEE;
      --green:#2E9E5B;
      --green-bg:#E6F5EC;
      --shadow:0 8px 24px rgba(14,50,84,.07);
    }
    *{box-sizing:border-box}
    html,body{margin:0;padding:0}
    body{
      font-family:'Inter',system-ui,sans-serif;
      background:var(--page);
      color:var(--ink);
      -webkit-font-smoothing:antialiased;
      padding:22px 18px 30px;
    }
    h1,h2,h3,.pj{font-family:'Plus Jakarta Sans','Inter',sans-serif}
    button{font-family:inherit;cursor:pointer;border:none;background:none;color:inherit}
    a{text-decoration:none;color:inherit}
    .frame{
      max-width:1680px;margin:0 auto;
      background:var(--panel);
      border-radius:30px;
      padding:20px 32px 26px;
      box-shadow:0 24px 60px rgba(14,50,84,.10);
    }
    .topbar{
      display:grid;grid-template-columns:1fr auto 1fr;align-items:center;gap:18px;
      background:var(--card);
      border-radius:999px;
      padding:10px 12px 10px 20px;
      box-shadow:var(--shadow);
      position:relative;z-index:30;
    }
    .logo{display:flex;align-items:center;gap:9px;text-decoration:none;color:var(--navy);flex:none;justify-self:start}
    .logo svg{display:block}
    .logo-ticket{height:40px;width:auto;overflow:visible;cursor:pointer;flex:none}
    .logo-word{font-family:'Plus Jakarta Sans',sans-serif;font-weight:800;font-size:19px;letter-spacing:.2px;color:var(--navy);white-space:nowrap}
    .logo-word em{font-style:normal;color:var(--gold-deep)}
    .topnav{display:flex;gap:4px;margin:0;justify-self:center;background:#F1F3F4;border-radius:999px;padding:4px}
    .topnav a, .topnav button{
      display:inline-flex;align-items:center;padding:8px 18px;border-radius:999px;font-size:13.5px;font-weight:600;color:#5B6B78;min-height:36px;
      transition:background .15s,color .15s;text-decoration:none;
    }
    .topnav a:hover, .topnav button:hover{color:var(--navy)}
    .topnav a.active, .topnav button.active{background:var(--card);color:var(--navy);box-shadow:0 2px 8px rgba(0,0,0,.08)}
    .top-actions{display:flex;align-items:center;gap:6px;flex:none;justify-self:end;margin-left:0}
    .icon-btn{
      width:40px;height:40px;border-radius:50%;display:grid;place-items:center;color:#4A5965;
      background:#F1F3F4;transition:background .15s,color .15s;position:relative;
    }
    .icon-btn:hover{background:#E5E9EC;color:var(--navy)}
    .icon-btn .dot{
      position:absolute;top:8px;right:9px;width:8px;height:8px;border-radius:50%;background:#D34545;
      box-shadow:0 0 0 2px var(--card);
    }
    .avatar-btn{display:flex;align-items:center;gap:6px;padding:3px 8px 3px 3px;border-radius:999px;background:#F1F3F4;transition:background .15s}
    .avatar-btn:hover{background:#E5E9EC}
    .avatar{
      width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,var(--navy),var(--blue));
      color:#fff;font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;font-size:13px;display:grid;place-items:center;
    }
    .search-wrap{position:relative;display:flex;align-items:center}
    .search-input{
      width:0;opacity:0;pointer-events:none;transition:width .22s ease,opacity .18s;
      border:1.5px solid var(--line);border-radius:999px;padding:8px 14px;font-size:13px;background:var(--card);color:var(--ink);outline:none;
    }
    .search-wrap.open .search-input{width:180px;opacity:1;pointer-events:auto;margin-right:6px}
    .pop{
      position:absolute;top:calc(100% + 10px);right:12px;background:var(--card);border-radius:18px;
      box-shadow:0 18px 45px rgba(14,50,84,.16);width:320px;padding:0;z-index:90;display:none;overflow:hidden;
    }
    .pop.open{display:block}
    .greet{display:flex;align-items:center;justify-content:space-between;margin:22px 4px 18px;flex-wrap:wrap;gap:12px}
    .greet h1{margin:0;font-size:24px;color:var(--navy);font-weight:700}
    .greet h1 strong{font-weight:800}
    .greet-actions{display:flex;align-items:center;gap:10px}
    .range-wrap{position:relative}
    .range-btn{display:inline-flex;align-items:center;gap:9px;background:var(--card);border-radius:999px;padding:9px 16px;box-shadow:var(--shadow);font-size:13px;font-weight:600;color:#394854}
    .btn-primary{display:inline-flex;align-items:center;gap:8px;background:var(--navy);color:#fff;border-radius:999px;padding:9px 18px;font-weight:700;font-size:13px;box-shadow:0 4px 14px rgba(14,50,84,.22);transition:transform .12s,background .15s}
    .btn-primary:hover{background:var(--navy-2);transform:translateY(-1px)}
    .layout{display:flex;gap:22px;align-items:flex-start}
    .rail{flex:none;width:58px;background:var(--card);border-radius:999px;padding:14px 8px;box-shadow:var(--shadow);display:flex;flex-direction:column;align-items:center;gap:10px;position:sticky;top:20px;min-height:480px}
    .rail a, .rail button{width:42px;height:42px;border-radius:50%;display:grid;place-items:center;color:#6A7885;position:relative;transition:background .15s,color .15s;text-decoration:none}
    .rail a:hover, .rail button:hover{background:#F1F4F7;color:var(--navy)}
    .rail a.active, .rail button.active{background:var(--navy);color:#fff;box-shadow:0 4px 14px rgba(14,50,84,.28)}
    .rail .rail-badge{position:absolute;top:5px;right:5px;min-width:16px;height:16px;padding:0 4px;border-radius:999px;background:var(--gold-deep);color:#fff;font-size:10px;font-weight:800;display:grid;place-items:center;line-height:1;border:2px solid var(--card)}
    .rail .spacer{flex:1}
    .views{flex:1;min-width:0}
    .view{display:block}
    .page-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:18px;flex-wrap:wrap;gap:12px}
    .page-head h1{margin:0 0 3px;font-size:22px;color:var(--navy);font-weight:800}
    .page-head p{margin:0;font-size:13px;color:var(--muted)}
    .card{background:var(--card);border-radius:20px;padding:20px 22px;box-shadow:var(--shadow);position:relative;margin-bottom:20px}
    .card-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:16px}
    .card-head h2{margin:0;font-size:17px;font-weight:800;color:var(--navy)}
    .card-head p{margin:3px 0 0;font-size:12.5px;color:var(--muted)}
    .grid-2{display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px}

    /* KPI Strip */
    .kpi-strip{display:grid;grid-template-columns:repeat(4, 1fr);gap:16px;margin-bottom:20px}
    .kpi-box{background:var(--card);border-radius:18px;padding:16px 20px;box-shadow:var(--shadow)}
    .kpi-box span{display:block;font-size:12px;font-weight:700;color:var(--muted);letter-spacing:.3px;margin-bottom:6px}
    .kpi-box b{font-size:24px;font-weight:800;color:var(--navy);font-family:'Plus Jakarta Sans',sans-serif}

    /* Hbar and Keyword rows */
    .hbar-row{display:grid;grid-template-columns:130px 1fr 90px;gap:12px;align-items:center;padding:10px 0;border-bottom:1px solid var(--line);font-size:13.5px}
    .hbar-row:last-child{border-bottom:0}
    .hbar-row span{font-weight:600;color:var(--navy);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
    .hbar-track{height:12px;background:#EDF1F3;border-radius:99px;overflow:hidden}
    .hbar-fill{height:100%;border-radius:99px;background:var(--navy-2);transition:width .3s}
    .hbar-row b{text-align:right;font-family:'Plus Jakarta Sans',sans-serif;color:var(--ink)}

    .kw-row{display:flex;align-items:center;gap:12px;padding:11px 0;border-bottom:1px solid var(--line)}
    .kw-row:last-child{border-bottom:0}
    .kw-rank{width:28px;height:28px;border-radius:50%;background:var(--cream);color:var(--gold-dark);font-family:'Plus Jakarta Sans',sans-serif;font-weight:800;font-size:12.5px;display:grid;place-items:center;flex:none}
    .progress{height:8px;background:#EDF1F3;border-radius:99px;overflow:hidden;margin-top:6px}
    .progress i{display:block;height:100%;background:linear-gradient(90deg,var(--blue),var(--sky));border-radius:99px}

    .btn-outline{display:inline-flex;align-items:center;gap:6px;background:#fff;border:1.5px solid var(--line);border-radius:999px;padding:7px 15px;font-size:12.5px;font-weight:700;color:var(--navy);cursor:pointer;text-decoration:none}
    .btn-outline:hover{background:#F8FAFB;border-color:#CFD8DF}

    /* Date Filter Bar */
    .filter-bar{display:flex;align-items:center;gap:10px;background:var(--card);border-radius:16px;padding:12px 18px;box-shadow:var(--shadow);margin-bottom:20px;flex-wrap:wrap}
    .filter-bar input[type="date"]{border:1.5px solid var(--line);border-radius:10px;padding:6px 12px;font-size:13px;font-family:inherit;color:var(--ink);outline:none}
    .filter-bar button{background:var(--navy);color:#fff;border-radius:10px;padding:7px 18px;font-size:13px;font-weight:700}

    @media (max-width:1120px){
      .grid-2{grid-template-columns:1fr}
      .kpi-strip{grid-template-columns:repeat(2, 1fr)}
    }
    @media (max-width:820px){
      .kpi-strip{grid-template-columns:1fr}
      .layout{flex-direction:column}
      .rail{flex-direction:row;width:100%;min-height:auto;border-radius:20px;padding:8px 12px;position:static}
      .rail .spacer{display:none}
    }
  </style>
</head>
<body>
  <div class="frame">

    <?php require __DIR__ . '/sidebar.php'; ?>

    <!-- ========== VIEW: ANALITIK ========== -->
    <section class="view" id="view-analitik" aria-label="Analitik">
      <div class="page-head">
        <div>
          <h1>Analitik &amp; Laporan Penjualan</h1>
          <p>Laporan penjualan tiket berdasarkan kategori, kota, keterisian event, dan tren pencarian</p>
        </div>
        <div style="display:flex;gap:10px">
          <a href="<?= base_url('dashboard/reports/analytics?export=csv' . (!empty($startDate) ? '&start_date=' . urlencode($startDate) : '') . (!empty($endDate) ? '&end_date=' . urlencode($endDate) : '')) ?>" class="btn-outline">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v12M7 10l5 5 5-5M4 21h16"/></svg>
            Export CSV
          </a>
        </div>
      </div>

      <!-- Filter Rentang Tanggal -->
      <form class="filter-bar" method="GET" action="<?= base_url('dashboard/reports/analytics') ?>">
        <span style="font-weight:700;font-size:13px;color:var(--navy)">Filter Periode:</span>
        <input type="date" name="start_date" value="<?= e($startDate) ?>" aria-label="Tanggal Awal" />
        <span style="color:var(--muted);font-size:13px">s.d.</span>
        <input type="date" name="end_date" value="<?= e($endDate) ?>" aria-label="Tanggal Akhir" />
        <button type="submit">Terapkan Filter</button>
        <?php if (!empty($startDate) || !empty($endDate)): ?>
          <a href="<?= base_url('dashboard/reports/analytics') ?>" style="font-size:12.5px;color:var(--muted);margin-left:6px">Reset Filter</a>
        <?php endif; ?>
      </form>

      <!-- KPI Strip Metrik -->
      <div class="kpi-strip">
        <div class="kpi-box">
          <span>TIKET TERJUAL (PERIODE)</span>
          <b><?= number_format($totalCategorySold) ?></b>
        </div>
        <div class="kpi-box">
          <span>TOTAL PENDAPATAN</span>
          <b><?= rupiah($totalCategoryRev) ?></b>
        </div>
        <div class="kpi-box">
          <span>PESANAN SUKSES</span>
          <b><?= (int)($statusBreakdown['Terkirim']['count'] + $statusBreakdown['Selesai']['count']) ?> Pesanan</b>
        </div>
        <div class="kpi-box">
          <span>PESANAN MENUNGGU / BATAL</span>
          <b><?= (int)$statusBreakdown['Menunggu']['count'] ?> / <?= (int)$statusBreakdown['Dibatalkan']['count'] ?></b>
        </div>
      </div>

      <!-- Grid 1: Laporan Kategori & Kota -->
      <div class="grid-2">
        <!-- Laporan per Kategori -->
        <div class="card">
          <div class="card-head">
            <div>
              <h2>Laporan per Kategori</h2>
              <p>Tiket terjual dan omset berdasarkan kategori event</p>
            </div>
          </div>
          <?php if (empty($categorySales)): ?>
            <p style="color:var(--muted);font-size:13px">Belum ada data penjualan kategori.</p>
          <?php else: 
            $maxCat = 1;
            foreach ($categorySales as $c) {
                if ((int)$c['tickets_sold'] > $maxCat) $maxCat = (int)$c['tickets_sold'];
            }
          ?>
            <?php foreach ($categorySales as $c): 
              $pct = round(((int)$c['tickets_sold'] / $maxCat) * 100);
            ?>
              <div class="hbar-row">
                <span><?= e($c['name']) ?></span>
                <div class="hbar-track">
                  <div class="hbar-fill" style="width:<?= $pct ?>%;background:<?= e($c['color'] ?: 'var(--navy-2)') ?>"></div>
                </div>
                <b><?= number_format($c['tickets_sold']) ?> tiket</b>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>

        <!-- Laporan per Kota -->
        <div class="card">
          <div class="card-head">
            <div>
              <h2>Laporan per Kota</h2>
              <p>Tiket terjual berdasarkan kota penyelenggaraan</p>
            </div>
          </div>
          <?php if (empty($citySales)): ?>
            <p style="color:var(--muted);font-size:13px">Belum ada data penjualan per kota.</p>
          <?php else: 
            $maxCity = 1;
            foreach ($citySales as $ci) {
                if ((int)$ci['tickets_sold'] > $maxCity) $maxCity = (int)$ci['tickets_sold'];
            }
          ?>
            <?php foreach ($citySales as $ci): 
              $pct = round(((int)$ci['tickets_sold'] / $maxCity) * 100);
            ?>
              <div class="hbar-row">
                <span><?= e($ci['name']) ?></span>
                <div class="hbar-track">
                  <div class="hbar-fill" style="width:<?= $pct ?>%;background:<?= e($ci['accent'] ?: 'var(--blue)') ?>"></div>
                </div>
                <b><?= number_format($ci['tickets_sold']) ?> tiket</b>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>

      <!-- Grid 2: Tingkat Keterisian (Occupancy Rate) & Status Pesanan -->
      <div class="grid-2">
        <!-- Tingkat Keterisian Event -->
        <div class="card">
          <div class="card-head">
            <div>
              <h2>Tingkat Keterisian Kuota (Occupancy Rate)</h2>
              <p>Persentase tiket terjual terhadap kuota keseluruhan event</p>
            </div>
          </div>
          <div style="max-height:360px;overflow-y:auto">
            <?php if (empty($occupancyRates)): ?>
              <p style="color:var(--muted);font-size:13px">Belum ada data event aktif.</p>
            <?php else: ?>
              <?php foreach ($occupancyRates as $ev): ?>
                <div style="padding:10px 0;border-bottom:1px solid var(--line)">
                  <div style="display:flex;justify-content:space-between;margin-bottom:4px;font-size:13px">
                    <b><?= e($ev['title']) ?></b>
                    <span style="font-weight:700;color:<?= (float)$ev['occupancy_pct'] >= 80 ? 'var(--green)' : 'var(--navy)' ?>">
                      <?= number_format($ev['sold']) ?> / <?= number_format($ev['quota']) ?> (<?= $ev['occupancy_pct'] ?>%)
                    </span>
                  </div>
                  <div class="progress" style="height:8px">
                    <i style="width:<?= min(100, (float)$ev['occupancy_pct']) ?>%;background:<?= (float)$ev['occupancy_pct'] >= 80 ? 'var(--green)' : 'var(--blue)' ?>"></i>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>

        <!-- Distribusi Status Pesanan -->
        <div class="card">
          <div class="card-head">
            <div>
              <h2>Distribusi Status Pesanan</h2>
              <p>Proporsi pesanan tiket berdasarkan status pembayaran &amp; pengiriman</p>
            </div>
          </div>
          <?php
            $totalAllOrders = array_sum(array_column($statusBreakdown, 'count')) ?: 1;
          ?>
          <div style="display:flex;flex-direction:column;gap:12px;margin-top:8px">
            <div style="padding:10px 14px;background:#F8FAFC;border-radius:12px;display:flex;align-items:center;justify-content:space-between">
              <div>
                <span style="font-weight:700;color:var(--navy);font-size:13.5px">Pesanan Terkirim</span>
                <span style="display:block;font-size:12px;color:var(--muted)"><?= rupiah($statusBreakdown['Terkirim']['amount']) ?></span>
              </div>
              <b style="font-size:16px;color:var(--green)"><?= (int)$statusBreakdown['Terkirim']['count'] ?> (<?= round($statusBreakdown['Terkirim']['count'] / $totalAllOrders * 100) ?>%)</b>
            </div>

            <div style="padding:10px 14px;background:#F8FAFC;border-radius:12px;display:flex;align-items:center;justify-content:space-between">
              <div>
                <span style="font-weight:700;color:var(--navy);font-size:13.5px">Pesanan Selesai</span>
                <span style="display:block;font-size:12px;color:var(--muted)"><?= rupiah($statusBreakdown['Selesai']['amount']) ?></span>
              </div>
              <b style="font-size:16px;color:var(--navy)"><?= (int)$statusBreakdown['Selesai']['count'] ?> (<?= round($statusBreakdown['Selesai']['count'] / $totalAllOrders * 100) ?>%)</b>
            </div>

            <div style="padding:10px 14px;background:#F8FAFC;border-radius:12px;display:flex;align-items:center;justify-content:space-between">
              <div>
                <span style="font-weight:700;color:var(--navy);font-size:13.5px">Menunggu Pembayaran</span>
                <span style="display:block;font-size:12px;color:var(--muted)"><?= rupiah($statusBreakdown['Menunggu']['amount']) ?></span>
              </div>
              <b style="font-size:16px;color:var(--gold-dark)"><?= (int)$statusBreakdown['Menunggu']['count'] ?> (<?= round($statusBreakdown['Menunggu']['count'] / $totalAllOrders * 100) ?>%)</b>
            </div>

            <div style="padding:10px 14px;background:#F8FAFC;border-radius:12px;display:flex;align-items:center;justify-content:space-between">
              <div>
                <span style="font-weight:700;color:var(--navy);font-size:13.5px">Pesanan Dibatalkan</span>
                <span style="display:block;font-size:12px;color:var(--muted)"><?= rupiah($statusBreakdown['Dibatalkan']['amount']) ?></span>
              </div>
              <b style="font-size:16px;color:#D04444"><?= (int)$statusBreakdown['Dibatalkan']['count'] ?> (<?= round($statusBreakdown['Dibatalkan']['count'] / $totalAllOrders * 100) ?>%)</b>
            </div>
          </div>
        </div>
      </div>

      <!-- Grid 3: Kata Kunci Teratas & Tren Pencarian (Mockup Match) -->
      <div class="grid-2">
        <div class="card">
          <div class="card-head">
            <div>
              <h2>Kata Kunci Teratas</h2>
              <p>Pencarian terpopuler di kolom pencarian NOVA LIVE</p>
            </div>
          </div>
          <?php
            $keywords = [
              ['Tulus Live in Concert', 4820],
              ['Midnight Jazz Session', 3150],
              ['Festival Balon Udara Bali', 2410],
              ['Fun Run 10K', 1890],
              ['Pameran Seni Kontemporer', 1240]
            ];
            $maxKw = $keywords[0][1];
          ?>
          <?php foreach ($keywords as $i => $k): ?>
            <div class="kw-row">
              <span class="kw-rank"><?= $i + 1 ?></span>
              <div style="flex:1;min-width:0">
                <b style="font-size:13.5px">“<?= e($k[0]) ?>”</b>
                <div class="progress" style="max-width:280px">
                  <i style="width:<?= round($k[1] / $maxKw * 100) ?>%"></i>
                </div>
              </div>
              <b style="font-family:'Plus Jakarta Sans',sans-serif;font-size:13px;color:var(--muted)"><?= number_format($k[1]) ?>×</b>
            </div>
          <?php endforeach; ?>
        </div>

        <div class="card">
          <div class="card-head">
            <div>
              <h2>Tren Pencarian Homepage</h2>
              <p>Frekuensi pencarian harian 7 hari terakhir</p>
            </div>
          </div>
          <svg viewBox="0 0 540 180" width="100%" height="180" role="img" aria-label="Grafik Tren Pencarian">
            <line x1="14" y1="35" x2="526" y2="35" stroke="#E7EBEE" stroke-width="1" stroke-dasharray="3 4"/>
            <line x1="14" y1="75" x2="526" y2="75" stroke="#E7EBEE" stroke-width="1" stroke-dasharray="3 4"/>
            <line x1="14" y1="115" x2="526" y2="115" stroke="#E7EBEE" stroke-width="1" stroke-dasharray="3 4"/>
            <line x1="14" y1="150" x2="526" y2="150" stroke="#E7EBEE" stroke-width="1.5"/>

            <rect x="25" y="65" width="34" height="85" rx="6" fill="#23649A" opacity="0.85"><title>13 Nov: 1.120 pencarian</title></rect>
            <text x="42" y="58" text-anchor="middle" font-size="11" font-weight="700" fill="#14232F">1.120</text>
            <text x="42" y="168" text-anchor="middle" font-size="11" fill="#7A8794">13 Nov</text>

            <rect x="98" y="50" width="34" height="100" rx="6" fill="#23649A" opacity="0.85"><title>14 Nov: 1.340 pencarian</title></rect>
            <text x="115" y="43" text-anchor="middle" font-size="11" font-weight="700" fill="#14232F">1.340</text>
            <text x="115" y="168" text-anchor="middle" font-size="11" fill="#7A8794">14 Nov</text>

            <rect x="171" y="42" width="34" height="108" rx="6" fill="#23649A" opacity="0.85"><title>15 Nov: 1.450 pencarian</title></rect>
            <text x="188" y="35" text-anchor="middle" font-size="11" font-weight="700" fill="#14232F">1.450</text>
            <text x="188" y="168" text-anchor="middle" font-size="11" fill="#7A8794">15 Nov</text>

            <rect x="244" y="38" width="34" height="112" rx="6" fill="#23649A" opacity="0.85"><title>16 Nov: 1.580 pencarian</title></rect>
            <text x="261" y="31" text-anchor="middle" font-size="11" font-weight="700" fill="#14232F">1.580</text>
            <text x="261" y="168" text-anchor="middle" font-size="11" fill="#7A8794">16 Nov</text>

            <rect x="317" y="45" width="34" height="105" rx="6" fill="#23649A" opacity="0.85"><title>17 Nov: 1.410 pencarian</title></rect>
            <text x="334" y="38" text-anchor="middle" font-size="11" font-weight="700" fill="#14232F">1.410</text>
            <text x="334" y="168" text-anchor="middle" font-size="11" fill="#7A8794">17 Nov</text>

            <rect x="390" y="32" width="34" height="118" rx="6" fill="#23649A" opacity="0.85"><title>18 Nov: 1.690 pencarian</title></rect>
            <text x="407" y="25" text-anchor="middle" font-size="11" font-weight="700" fill="#14232F">1.690</text>
            <text x="407" y="168" text-anchor="middle" font-size="11" fill="#7A8794">18 Nov</text>

            <rect x="463" y="22" width="34" height="128" rx="6" fill="#D4B475"><title>19 Nov: 1.730 pencarian</title></rect>
            <text x="480" y="15" text-anchor="middle" font-size="11" font-weight="700" fill="#14232F">1.730</text>
            <text x="480" y="168" text-anchor="middle" font-size="11" fill="#7A8794">Hari Ini</text>
          </svg>
          <div style="display:flex;justify-content:space-between;margin-top:10px;font-size:12.5px;color:var(--muted)">
            <span>Total: <b>10.320 pencarian</b></span>
            <span>Rata-rata/hari: <b>1.474 pencarian</b></span>
          </div>
        </div>
      </div>
    </section>

    <?php $shellFooter = true; require __DIR__ . '/sidebar.php'; ?>
  </div>
</body>
</html>
