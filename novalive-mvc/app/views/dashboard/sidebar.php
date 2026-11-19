<?php
/**
 * Shared Dashboard Quixotic Shell (Top bar, Greeting, Rail, Tooltip, Toast, and Core Scripts)
 * NOVA LIVE MVC — Langkah 11 Revisi (Gaya Quixotic)
 */

if (empty($shellFooter)):
  $currentUser = $currentUser ?? ['full_name' => 'Admin NOVA LIVE', 'role' => 'admin'];
  $userInitials = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $currentUser['full_name'] ?? 'AD'), 0, 2));
  if (strlen($userInitials) < 2) $userInitials = 'AD';
?>
      <!-- ======= TOP BAR ======= -->
      <header class="topbar">
        <a class="logo" href="<?= base_url('dashboard') ?>" aria-label="NOVA LIVE — Beranda" id="logoHome">
          <svg class="logo-ticket" id="nvqTicket" viewBox="0 30 1300 540" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Logo tiket NOVALIVE">
            <defs>
              <linearGradient id="nvq-dark" x1="60" y1="70" x2="780" y2="540" gradientUnits="userSpaceOnUse">
                <stop offset="0" stop-color="#2E8CB9"/>
                <stop offset="0.45" stop-color="#1F6AA3"/>
                <stop offset="1" stop-color="#17508A"/>
              </linearGradient>
              <linearGradient id="nvq-light" x1="690" y1="400" x2="1170" y2="300" gradientUnits="userSpaceOnUse">
                <stop offset="0" stop-color="#2A72B0"/>
                <stop offset="0.35" stop-color="#3F95CF"/>
                <stop offset="1" stop-color="#58B5E1"/>
              </linearGradient>
              <linearGradient id="nvq-shine" x1="0" y1="0" x2="1" y2="0">
                <stop offset="0" stop-color="#fff" stop-opacity="0"/>
                <stop offset="0.5" stop-color="#fff" stop-opacity="0.55"/>
                <stop offset="1" stop-color="#fff" stop-opacity="0"/>
              </linearGradient>
              <path id="nvq-dark-shape" d="M 42 352 L 105 285 A 106 106 0 0 0 258 135 L 325 62 L 840 535 L 300 535 C 176 536 96 438 42 352 Z"/>
              <path id="nvq-light-shape" d="M 678 365 L 798 248 L 885 248 C 888 285 905 292 925 292 C 990 292 1030 262 1075 248 L 1183 248 C 1130 330 1060 480 900 492 C 800 495 720 430 678 365 Z"/>
              <clipPath id="nvq-clip">
                <use href="#nvq-dark-shape"/>
                <use href="#nvq-light-shape"/>
              </clipPath>
            </defs>

            <g data-part="root">
              <path data-part="ribbon" d="M 385 68 L 665 382 C 735 466 803 514 897 512 C 1022 510 1120 384 1230 258"
                    fill="none" stroke="#E8D5A1" stroke-width="44" pathLength="1" stroke-dasharray="1" stroke-dashoffset="1"/>

              <g data-part="dark" opacity="0">
                <use href="#nvq-dark-shape" fill="url(#nvq-dark)" stroke="url(#nvq-dark)" stroke-width="5" stroke-linejoin="round"/>
                <g fill="#FAFAFC">
                  <g data-part="dot" data-cx="395" data-cy="255"><circle r="28"/></g>
                  <g data-part="dot" data-cx="312" data-cy="340"><circle r="28"/></g>
                  <g data-part="dot" data-cx="228" data-cy="424"><circle r="28"/></g>
                </g>
              </g>

              <g data-part="light" opacity="0">
                <use href="#nvq-light-shape" fill="url(#nvq-light)" stroke="url(#nvq-light)" stroke-width="4" stroke-linejoin="round"/>
                <g fill="#FAFAFC">
                  <g data-part="oval" data-cx="773" data-cy="355"><ellipse rx="27" ry="16" transform="rotate(-8)"/></g>
                  <g data-part="oval" data-cx="875" data-cy="359"><ellipse rx="29" ry="16" transform="rotate(-8)"/></g>
                  <g data-part="oval" data-cx="975" data-cy="362"><ellipse rx="27" ry="16" transform="rotate(-8)"/></g>
                </g>
              </g>

              <g clip-path="url(#nvq-clip)">
                <rect data-part="shine" x="-120" y="-120" width="240" height="900" fill="url(#nvq-shine)" opacity="0"/>
              </g>
            </g>
          </svg>
          <span class="logo-word">NOVA <em>LIVE</em></span>
        </a>

        <nav class="topnav" aria-label="Navigasi utama">
          <a href="<?= base_url('dashboard') ?>" class="<?= ($activeNav ?? '') === 'ringkasan' ? 'active' : '' ?>">Dashboard</a>
          <a href="<?= base_url('dashboard/reports/analytics') ?>" class="<?= ($activeMenu ?? '') === 'laporan' ? 'active' : '' ?>">Laporan</a>
          <a href="<?= base_url('dashboard/orders/tickets') ?>" class="<?= ($activeNav ?? '') === 'tickets' ? 'active' : '' ?>">Dokumen</a>
          <a href="<?= base_url('dashboard/reports/logs') ?>" class="<?= ($activeNav ?? '') === 'logs' ? 'active' : '' ?>">Riwayat</a>
          <a href="<?= base_url('dashboard/settings') ?>" class="<?= (($activeNav ?? '') === 'settings' || ($activeMenu ?? '') === 'pengaturan') ? 'active' : '' ?>">Kontak</a>
        </nav>

        <div class="top-actions">
          <div class="search-wrap" id="searchWrap">
            <input class="search-input" id="searchInput" type="search" placeholder="Cari di dashboard…" aria-label="Cari data di dashboard" />
            <button class="icon-btn" id="searchBtn" type="button" aria-label="Cari">
              <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.8-3.8"/></svg>
            </button>
          </div>
          <?php
            $notifModelForSidebar = new Notification();
            $latestNotifications = $latestNotifications ?? $notifModelForSidebar->latest(5);
            $unreadNotifCount = (int)($sidebarCounts['notifications'] ?? $notifModelForSidebar->unreadCount());
          ?>
          <button class="icon-btn" id="bellBtn" type="button" aria-label="Notifikasi" aria-expanded="false" style="position:relative">
            <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 9a6 6 0 1 0-12 0c0 6-2.5 7-2.5 7h17S18 15 18 9"/><path d="M10 20a2.2 2.2 0 0 0 4 0"/></svg>
            <?php if ($unreadNotifCount > 0): ?>
              <span class="dot" aria-hidden="true"></span>
            <?php endif; ?>
          </button>
          <a href="<?= base_url('dashboard/settings/account') ?>" class="avatar-btn" id="avatarBtn" aria-label="Profil penyelenggara — Pengaturan" title="Pengaturan — Profil Penyelenggara">
            <?php if (!empty($currentUser['avatar']) && file_exists(ROOT_PATH . '/public/' . ltrim($currentUser['avatar'], '/'))): ?>
              <img src="<?= base_url(ltrim($currentUser['avatar'], '/')) ?>" alt="Avatar" style="width:34px;height:34px;border-radius:50%;object-fit:cover;">
            <?php else: ?>
              <span class="avatar" id="topAvatar" aria-hidden="true"><?= e($userInitials) ?></span>
            <?php endif; ?>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#5B6B78" stroke-width="2.4" stroke-linecap="round"><path d="m6 9 6 6 6-6"/></svg>
          </a>
        </div>

        <div class="pop" id="bellPop" role="dialog" aria-label="Notifikasi">
          <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 14px 8px;border-bottom:1px solid var(--line)">
            <h3 style="margin:0;font-size:14px;font-weight:700">Notifikasi (<?= $unreadNotifCount ?>)</h3>
            <a href="<?= base_url('dashboard/reports/notifications') ?>" style="font-size:12px;color:var(--blue);font-weight:600;text-decoration:none">Lihat Semua</a>
          </div>
          <div class="pop-list" style="max-height:300px;overflow-y:auto">
            <?php if (empty($latestNotifications)): ?>
              <div style="padding:16px;text-align:center;color:var(--muted);font-size:13px">Belum ada notifikasi.</div>
            <?php else: ?>
              <?php foreach ($latestNotifications as $nt): ?>
                <a href="<?= base_url($nt['link'] ?? 'dashboard/reports/notifications') ?>" class="notif" style="display:flex;gap:10px;padding:10px 14px;text-decoration:none;color:inherit;border-bottom:1px solid #f1f3f4;<?= empty($nt['is_read']) ? 'background:rgba(234,212,162,0.12);' : '' ?>">
                  <span class="n-ico" style="flex:none;width:28px;height:28px;border-radius:8px;background:var(--cream);color:var(--gold-deep);display:grid;place-items:center">
                    <?php if (($nt['type'] ?? '') === 'refund'): ?>
                      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 10h10a5 5 0 0 1 5 5v2M3 10l6-6M3 10l6 6"/></svg>
                    <?php elseif (($nt['type'] ?? '') === 'stock'): ?>
                      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0zM12 9v4M12 17h.01"/></svg>
                    <?php else: ?>
                      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6 9 17l-5-5"/></svg>
                    <?php endif; ?>
                  </span>
                  <div style="flex:1;min-width:0">
                    <p style="margin:0;font-size:12.5px;line-height:1.4"><b><?= e($nt['title']) ?></b><br><?= e(mb_strimwidth($nt['message'], 0, 70, '…')) ?><span style="display:block;font-size:11px;color:var(--muted);margin-top:3px"><?= tanggal($nt['created_at'], 'd M Y · H:i') ?> WIB</span></p>
                  </div>
                </a>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>
      </header>

      <!-- ======= GREETING ======= -->
      <div class="greet">
        <h1>Selamat Datang Kembali, <strong><?= e($currentUser['full_name'] ?? 'Admin') ?></strong></h1>
        <div class="greet-actions">
          <div class="range-wrap">
            <button class="range-btn" id="rangeBtn" type="button" aria-haspopup="true" aria-expanded="false">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="5" width="18" height="16" rx="3"/><path d="M3 10h18M8 3v4M16 3v4"/></svg>
              <span id="rangeLabel">29 Juni 2025 – 29 Agustus 2025</span>
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="m6 9 6 6 6-6"/></svg>
            </button>
            <div class="range-menu" id="rangeMenu" role="menu">
              <button type="button" data-range="29 Juni 2025 – 29 Agustus 2025" class="sel">29 Juni 2025 – 29 Agustus 2025</button>
              <button type="button" data-range="1 – 30 Juni 2025">1 – 30 Juni 2025</button>
              <button type="button" data-range="1 Jan – 30 Juni 2025">1 Jan – 30 Juni 2025</button>
              <button type="button" data-range="1 Jan – 31 Des 2025">1 Jan – 31 Des 2025</button>
            </div>
          </div>
          <a href="<?= base_url('events/create') ?>" class="btn-primary" id="buatEventBtn">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
            Buat Event
          </a>
        </div>
      </div>

      <!-- Shared Tabs Styling & Desktop 1680px Layout Override -->
      <style>
        html,body{margin:0!important;padding:0!important}
        body{background:var(--page)!important;padding:22px 18px 30px!important}
        .frame{
          max-width:1680px!important;margin:0 auto!important;
          background:var(--panel)!important;
          border-radius:30px!important;
          padding:20px 32px 26px!important;
          box-shadow:0 24px 60px rgba(14,50,84,.10)!important;
          box-sizing:border-box!important;
        }
        .topbar{
          display:grid!important;grid-template-columns:1fr auto 1fr!important;align-items:center!important;gap:18px!important;
          background:var(--card)!important;
          border-radius:999px!important;
          padding:10px 12px 10px 20px!important;
          box-shadow:var(--shadow)!important;
          position:relative!important;z-index:30!important;
        }
        .logo{display:flex!important;align-items:center!important;gap:9px!important;text-decoration:none!important;color:var(--navy)!important;flex:none!important;justify-self:start!important}
        .topnav{
          display:flex!important;gap:4px!important;margin:0!important;justify-self:center!important;
          background:#F1F3F4!important;border-radius:999px!important;padding:4px!important;
        }
        .top-actions{display:flex!important;align-items:center!important;gap:6px!important;flex:none!important;justify-self:end!important;margin-left:0!important}
        @media (max-width:1120px){
          .topbar{display:flex!important}
          .topnav{margin:0 auto!important}
          .top-actions{margin-left:auto!important}
        }
        @media (max-width:820px){
          .topnav{display:none!important}
          .frame{padding:16px 18px 20px!important}
        }
        @media (max-width:640px){
          .frame{padding:12px 12px 16px!important;border-radius:22px!important}
        }
        .tabs{
          display:flex;gap:2px;background:#F1F3F4;border-radius:999px;padding:4px;
          width:fit-content;max-width:100%;overflow-x:auto;scrollbar-width:none;
          margin:2px 2px 18px;box-shadow:inset 0 0 0 1px var(--line);
        }
        .tabs::-webkit-scrollbar{display:none}
        .tabs a, .tabs button{
          display:inline-flex;align-items:center;gap:7px;flex:none;white-space:nowrap;
          padding:8px 18px;border-radius:999px;font-size:13.5px;font-weight:600;color:#5B6B78;min-height:38px;
          transition:background .15s,color .15s;text-decoration:none;border:none;background:transparent;cursor:pointer;
        }
        .tabs a:hover, .tabs button:hover{color:var(--navy)}
        .tabs a.active, .tabs button.active{background:var(--navy);color:#fff;box-shadow:0 4px 12px rgba(14,50,84,.25)}
        .tab-badge{
          min-width:18px;height:18px;border-radius:99px;background:var(--gold-deep);color:#fff;
          font-size:10.5px;font-weight:800;line-height:1;display:grid;place-items:center;padding:0 5px;
          font-family:'Plus Jakarta Sans',sans-serif;
        }
        .tabs a.active .tab-badge, .tabs button.active .tab-badge{background:var(--gold);color:var(--navy)}
      </style>

      <div class="layout">
        <!-- ======= ICON RAIL: 8 menu dashboard ======= -->
        <nav class="rail" id="rail" aria-label="Menu dashboard">
          <!-- 1. Ringkasan -->
          <a href="<?= base_url('dashboard') ?>" class="<?= (($activeNav ?? '') === 'ringkasan' || ($activeMenu ?? '') === 'ringkasan') ? 'active' : '' ?>" data-tip="Ringkasan" aria-label="Ringkasan" title="Ringkasan">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linejoin="round"><rect x="3" y="3" width="7.5" height="7.5" rx="1.8"/><rect x="13.5" y="3" width="7.5" height="7.5" rx="1.8"/><rect x="3" y="13.5" width="7.5" height="7.5" rx="1.8"/><rect x="13.5" y="13.5" width="7.5" height="7.5" rx="1.8"/></svg>
          </a>

          <!-- 2. Event -->
          <a href="<?= base_url('dashboard/events') ?>" class="<?= (in_array($activeNav ?? '', ['events', 'stock', 'categories', 'cities']) || ($activeMenu ?? '') === 'event') ? 'active' : '' ?>" data-tip="Event" aria-label="Event" title="Event">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"><rect x="3" y="5" width="18" height="16" rx="2.5"/><path d="M3 10h18M8 3v4M16 3v4"/></svg>
            <span class="rail-badge" id="navEventCount"><?= (int)($sidebarCounts['events'] ?? 10) ?></span>
          </a>

          <!-- 3. Pesanan -->
          <a href="<?= base_url('dashboard/orders') ?>" class="<?= (in_array($activeNav ?? '', ['orders', 'checkin', 'refund', 'tickets']) || ($activeMenu ?? '') === 'pesanan') ? 'active' : '' ?>" data-tip="Pesanan" aria-label="Pesanan" title="Pesanan">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M4 5h2l2.4 11.2a1.5 1.5 0 0 0 1.5 1.2h7.9a1.5 1.5 0 0 0 1.5-1.2L20.5 8H7"/><circle cx="10" cy="21" r="1"/><circle cx="18" cy="21" r="1"/></svg>
            <span class="rail-badge" id="navOrderCount"><?= (int)($sidebarCounts['orders'] ?? 8) ?></span>
          </a>

          <!-- 4. Keuangan -->
          <!-- 4. Keuangan -->
          <a href="<?= base_url('dashboard/finance') ?>" class="<?= (($activeNav ?? '') === 'finance' || ($activeMenu ?? '') === 'keuangan') ? 'active' : '' ?>" data-tip="Keuangan" aria-label="Keuangan" title="Keuangan">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linejoin="round"><rect x="3" y="6" width="18" height="13" rx="2.5"/><path d="M3 10.5h18M7 15h4"/></svg>
          </a>

          <!-- 5. Homepage -->
          <a href="<?= base_url('dashboard/homepage/banners') ?>" class="<?= (in_array($activeNav ?? '', ['banners', 'layout']) || ($activeMenu ?? '') === 'homepage') ? 'active' : '' ?>" data-tip="Homepage" aria-label="Homepage" title="Homepage">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"><rect x="3" y="4" width="18" height="16" rx="2.5"/><path d="M3 9.5h18M8 4v5.5"/></svg>
          </a>

          <!-- 6. Laporan -->
          <a href="<?= base_url('dashboard/reports/analytics') ?>" class="<?= (in_array($activeNav ?? '', ['analytics', 'logs', 'notifications']) || ($activeMenu ?? '') === 'laporan') ? 'active' : '' ?>" data-tip="Laporan" aria-label="Laporan" title="Laporan">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"><path d="M5 20v-6M11 20V6M17 20v-9"/><path d="M3 20h18"/></svg>
            <?php if (!empty($sidebarCounts['notifications'])): ?>
              <span class="rail-badge" id="navNotifCount"><?= (int)$sidebarCounts['notifications'] ?></span>
            <?php endif; ?>
          </a>

          <!-- 7. Promosi -->
          <a href="<?= base_url('dashboard/promotions') ?>" class="<?= (($activeNav ?? '') === 'promotions' || ($activeMenu ?? '') === 'promosi') ? 'active' : '' ?>" data-tip="Promosi" aria-label="Promosi" title="Promosi">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linejoin="round"><path d="M3 11V7a2 2 0 0 1 2-2h4l9 9-4 4-9-9z"/><circle cx="8" cy="9" r="1.4"/></svg>
          </a>

          <!-- 8. Pengaturan -->
          <a href="<?= base_url('dashboard/settings') ?>" class="<?= (in_array($activeNav ?? '', ['settings', 'account', 'users']) || ($activeMenu ?? '') === 'pengaturan') ? 'active' : '' ?>" data-tip="Pengaturan" aria-label="Pengaturan" title="Pengaturan">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3.2"/><path d="M19 12a7 7 0 0 0-.14-1.4l2-1.55-2-3.46-2.36.95a7 7 0 0 0-2.42-1.4L13.7 2.6h-3.4l-.38 2.54a7 7 0 0 0-2.42 1.4l-2.36-.95-2 3.46 2 1.55a7 7 0 0 0 0 2.8l-2 1.55 2 3.46 2.36-.95a7 7 0 0 0 2.42 1.4l.38 2.54h3.4l.38-2.54a7 7 0 0 0 2.42-1.4l2.36.95 2-3.46-2-1.55c.1-.45.14-.92.14-1.4z"/></svg>
          </a>

          <span class="spacer"></span>

          <!-- Footer Rail -->
          <a href="<?= base_url('/') ?>" id="previewBtn" data-tip="Pratinjau Homepage" aria-label="Pratinjau Homepage" title="Pratinjau Homepage">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linejoin="round"><path d="M2 12s3.5-6.5 10-6.5S22 12 22 12s-3.5 6.5-10 6.5S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg>
          </a>
          <a href="<?= base_url('auth/logout') ?>" id="logoutBtn" data-tip="Keluar Dashboard" aria-label="Keluar Dashboard" title="Keluar Dashboard">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="m16 17 5-5-5-5M21 12H9"/></svg>
          </a>
        </nav>

        <!-- ======= VIEWS ======= -->
        <main class="views">
          <?php if (($activeMenu ?? '') === 'event'): ?>
            <div class="tabs" role="tablist" aria-label="Tab menu">
              <a href="<?= base_url('dashboard/events') ?>" class="<?= ($activeTab ?? 'events') === 'events' ? 'active' : '' ?>" role="tab">Event Saya</a>
              <a href="<?= base_url('dashboard/events/stock') ?>" class="<?= ($activeTab ?? '') === 'stock' ? 'active' : '' ?>" role="tab">Stok Tiket</a>
              <a href="<?= base_url('dashboard/categories') ?>" class="<?= ($activeTab ?? '') === 'categories' ? 'active' : '' ?>" role="tab">Kategori</a>
              <a href="<?= base_url('dashboard/cities') ?>" class="<?= ($activeTab ?? '') === 'cities' ? 'active' : '' ?>" role="tab">Kota</a>
            </div>
          <?php elseif (($activeMenu ?? '') === 'pesanan'): ?>
            <div class="tabs" role="tablist" aria-label="Tab menu">
              <a href="<?= base_url('dashboard/orders') ?>" class="<?= ($activeTab ?? 'pesanan') === 'pesanan' ? 'active' : '' ?>" role="tab">Pesanan</a>
              <a href="<?= base_url('dashboard/orders/checkin') ?>" class="<?= ($activeTab ?? '') === 'checkin' ? 'active' : '' ?>" role="tab">Check-in</a>
              <a href="<?= base_url('dashboard/orders/refunds') ?>" class="<?= ($activeTab ?? '') === 'refund' ? 'active' : '' ?>" role="tab">Refund <span class="tab-badge" id="navRefundCount"><?= (int)($sidebarCounts['refund'] ?? 2) ?></span></a>
              <a href="<?= base_url('dashboard/orders/tickets') ?>" class="<?= ($activeTab ?? '') === 'etiket' ? 'active' : '' ?>" role="tab">E-Tiket</a>
            </div>
          <?php elseif (($activeMenu ?? '') === 'homepage'): ?>
            <div class="tabs" role="tablist" aria-label="Tab menu">
              <a href="<?= base_url('dashboard/homepage/banners') ?>" class="<?= ($activeTab ?? 'banners') === 'banners' ? 'active' : '' ?>" role="tab">Banner Slide</a>
              <a href="<?= base_url('dashboard/homepage/layout') ?>" class="<?= ($activeTab ?? '') === 'layout' ? 'active' : '' ?>" role="tab">Tata Letak Homepage</a>
            </div>
          <?php elseif (($activeMenu ?? '') === 'laporan'): ?>
            <div class="tabs" role="tablist" aria-label="Tab menu">
              <a href="<?= base_url('dashboard/reports/analytics') ?>" class="<?= ($activeTab ?? 'analytics') === 'analytics' ? 'active' : '' ?>" role="tab">Analitik</a>
              <a href="<?= base_url('dashboard/reports/logs') ?>" class="<?= ($activeTab ?? '') === 'logs' ? 'active' : '' ?>" role="tab">Log Aktivitas</a>
              <a href="<?= base_url('dashboard/reports/notifications') ?>" class="<?= ($activeTab ?? '') === 'notifications' ? 'active' : '' ?>" role="tab">Notifikasi <?php if (!empty($sidebarCounts['notifications'])): ?><span class="tab-badge" id="tabNotifCount"><?= (int)$sidebarCounts['notifications'] ?></span><?php endif; ?></a>
            </div>
          <?php elseif (($activeMenu ?? '') === 'promosi'): ?>
            <div class="tabs" role="tablist" aria-label="Tab menu">
              <a href="<?= base_url('dashboard/promotions') ?>" class="<?= ($activeTab ?? 'promotions') === 'promotions' ? 'active' : '' ?>" role="tab">Daftar Kode Promo</a>
            </div>
          <?php elseif (($activeMenu ?? '') === 'pengaturan'): ?>
            <div class="tabs" role="tablist" aria-label="Tab menu">
              <a href="<?= base_url('dashboard/settings') ?>" class="<?= ($activeTab ?? 'org') === 'org' ? 'active' : '' ?>" role="tab">Profil Organisasi &amp; Kontak</a>
              <a href="<?= base_url('dashboard/settings/account') ?>" class="<?= ($activeTab ?? '') === 'account' ? 'active' : '' ?>" role="tab">Akun Saya</a>
              <?php if (($currentUser['role'] ?? '') === 'admin'): ?>
                <a href="<?= base_url('dashboard/settings/users') ?>" class="<?= ($activeTab ?? '') === 'users' ? 'active' : '' ?>" role="tab">Kelola Pengguna</a>
              <?php endif; ?>
            </div>
          <?php endif; ?>
<?php else: ?>
        </main>
      </div><!-- /.layout -->
    </div><!-- /.frame -->

    <!-- Rail Tooltip -->
    <div id="railTip" role="tooltip"></div>

    <!-- Toast Notification -->
    <div class="toast" id="toast" role="status" aria-live="polite"></div>

    <!-- Core Shell Scripts -->
    <script>
      /* Rail Tooltip */
      (function(){
        var tip = document.getElementById('railTip');
        var rail = document.getElementById('rail');
        if(!tip || !rail) return;
        function show(b){
          if(window.innerWidth <= 820) return;
          var r = b.getBoundingClientRect();
          tip.textContent = b.getAttribute('data-tip') || '';
          tip.style.left = (r.right + 12) + 'px';
          tip.style.top = (r.top + r.height / 2) + 'px';
          tip.classList.add('show');
        }
        function hide(){ tip.classList.remove('show'); }
        rail.querySelectorAll('[data-tip]').forEach(function(b){
          b.addEventListener('mouseenter', function(){ show(b); });
          b.addEventListener('mouseleave', hide);
          b.addEventListener('click', hide);
          b.addEventListener('focus', function(){ show(b); });
          b.addEventListener('blur', hide);
        });
        rail.addEventListener('scroll', hide, {passive:true});
        window.addEventListener('resize', hide);
      })();

      /* Animasi logo tiket NOVALIVE (Ticket Intro) — auto-loop 4,2 dtk + jeda 1200ms, klik logo = replay */
      (function(){
        var DURATION = 4.2;
        var PAUSE = 1200;

        function clamp(x){ return Math.min(1, Math.max(0, x)); }
        function prog(t, a, b){ return clamp((t - a) / (b - a)); }
        var ease = {
          outCubic: function(x){ return 1 - Math.pow(1 - x, 3); },
          inOutCubic: function(x){ return x < 0.5 ? 4 * x * x * x : 1 - Math.pow(-2 * x + 2, 3) / 2; },
          outBack: function(x, s){ s = (s == null ? 1.70158 : s); var c = s + 1; return 1 + c * Math.pow(x - 1, 3) + s * Math.pow(x - 1, 2); }
        };

        var svg = document.getElementById('nvqTicket');
        if(!svg) return;
        function part(name){ return svg.querySelector('[data-part="' + name + '"]'); }
        var parts = {
          root: part('root'), ribbon: part('ribbon'), dark: part('dark'), light: part('light'), shine: part('shine'),
          dots: Array.prototype.slice.call(svg.querySelectorAll('[data-part="dot"]')),
          ovals: Array.prototype.slice.call(svg.querySelectorAll('[data-part="oval"]'))
        };

        function pop(el, t, t0){
          var cx = +el.getAttribute('data-cx'), cy = +el.getAttribute('data-cy');
          var u = prog(t, t0, t0 + 0.42);
          var s = u <= 0 ? 0 : ease.outBack(u, 2.4);
          el.setAttribute('transform', 'translate(' + cx + ' ' + cy + ') scale(' + Math.max(s, 0.0001) + ')');
        }

        function apply(t){
          var p = 1 + 0.04 * ease.inOutCubic(prog(t, 0.2, DURATION));
          parts.root.setAttribute('transform', 'translate(650 300) scale(' + p + ') translate(-650 -300)');
          parts.ribbon.setAttribute('stroke-dashoffset', (1 - ease.inOutCubic(prog(t, 0.25, 1.3))).toFixed(4));
          var u1 = prog(t, 0.6, 1.45), e1 = ease.outCubic(u1);
          parts.dark.setAttribute('transform',
            'translate(' + (450 - 260 * (1 - e1)) + ' ' + (300 + 150 * (1 - e1)) + ') scale(' + (0.86 + 0.14 * e1) + ') translate(-450 -300)');
          parts.dark.setAttribute('opacity', clamp(u1 * 3).toFixed(3));
          var u2 = prog(t, 0.95, 1.85), e2 = ease.outCubic(u2);
          parts.light.setAttribute('transform',
            'translate(' + (678 + 90 * (1 - e2)) + ' ' + (365 - 50 * (1 - e2)) + ') rotate(' + (-16 * (1 - e2)) + ') scale(' + (0.12 + 0.88 * e2) + ' ' + (0.6 + 0.4 * e2) + ') translate(-678 -365)');
          parts.light.setAttribute('opacity', clamp(u2 * 4).toFixed(3));
          parts.dots.forEach(function(el, i){ pop(el, t, 1.45 + 0.1 * i); });
          parts.ovals.forEach(function(el, i){ pop(el, t, 1.9 + 0.1 * i); });
          var u3 = prog(t, 2.15, 3.0), e3 = ease.inOutCubic(u3);
          parts.shine.setAttribute('transform', 'translate(' + (-250 + e3 * 1850) + ' 0) rotate(20 0 300)');
          parts.shine.setAttribute('opacity', (u3 > 0 && u3 < 1) ? 1 : 0);
        }

        var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        var raf = 0, timer = 0;

        function play(){
          cancelAnimationFrame(raf);
          clearTimeout(timer);
          if(reduceMotion){ apply(DURATION); return; }
          var start = 0;
          apply(0);
          function tick(now){
            if(!start) start = now;
            var t = (now - start) / 1000;
            apply(Math.min(t, DURATION));
            if(t < DURATION){
              raf = requestAnimationFrame(tick);
            } else {
              timer = setTimeout(play, PAUSE);
            }
          }
          raf = requestAnimationFrame(tick);
        }

        var logoLink = document.getElementById('logoHome');
        if(logoLink) logoLink.addEventListener('click', function(e){
          if(window.location.pathname.endsWith('/dashboard') || window.location.pathname.endsWith('/dashboard/')) {
            e.preventDefault();
            play();
          }
        });
        window.novaliveTicket = { apply: apply, play: play, DURATION: DURATION };
        play();
      })();

      /* Toast Notification function */
      function showToast(msg, duration){
        var t = document.getElementById('toast');
        if(!t) return;
        t.innerHTML = msg;
        t.classList.add('show');
        clearTimeout(t._timer);
        t._timer = setTimeout(function(){
          t.classList.remove('show');
        }, duration || 2800);
      }


      /* Range Menu toggle */
      (function(){
        var btn = document.getElementById('rangeBtn');
        var menu = document.getElementById('rangeMenu');
        var lbl = document.getElementById('rangeLabel');
        if(!btn || !menu) return;
        btn.addEventListener('click', function(e){
          e.stopPropagation();
          var open = menu.classList.toggle('open');
          btn.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
        menu.querySelectorAll('button').forEach(function(b){
          b.addEventListener('click', function(){
            menu.querySelectorAll('button').forEach(function(x){ x.classList.remove('sel'); });
            b.classList.add('sel');
            if(lbl) lbl.textContent = b.getAttribute('data-range') || b.textContent;
            menu.classList.remove('open');
            btn.setAttribute('aria-expanded', 'false');
            showToast('Rentang tanggal diubah: <em>' + (b.getAttribute('data-range') || b.textContent) + '</em>');
          });
        });
        document.addEventListener('click', function(e){
          if(!menu.contains(e.target) && !btn.contains(e.target)){
            menu.classList.remove('open');
            btn.setAttribute('aria-expanded', 'false');
          }
        });
      })();

      /* Bell Popup toggle */
      (function(){
        var btn = document.getElementById('bellBtn');
        var pop = document.getElementById('bellPop');
        if(!btn || !pop) return;
        btn.addEventListener('click', function(e){
          e.stopPropagation();
          var open = pop.classList.toggle('open');
          btn.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
        document.addEventListener('click', function(e){
          if(!pop.contains(e.target) && !btn.contains(e.target)){
            pop.classList.remove('open');
            btn.setAttribute('aria-expanded', 'false');
          }
        });
      })();

      /* Top Search toggle & input handler */
      (function(){
        var wrap = document.getElementById('searchWrap');
        var btn = document.getElementById('searchBtn');
        var inp = document.getElementById('searchInput');
        if(!wrap || !btn || !inp) return;
        btn.addEventListener('click', function(e){
          e.stopPropagation();
          var open = wrap.classList.toggle('open');
          if(open){ inp.focus(); }
          else { inp.value = ''; filterContent(''); }
        });
        inp.addEventListener('input', function(){
          filterContent(inp.value);
        });
        function filterContent(q){
          q = q.trim().toLowerCase();
          // Filter riwayat transaksi di view ringkasan
          var txRows = document.querySelectorAll('#txBody tr:not(#emptyRow)');
          if(txRows.length){
            var visible = 0;
            txRows.forEach(function(r){
              var hit = r.textContent.toLowerCase().indexOf(q) !== -1;
              r.style.display = hit ? '' : 'none';
              if(hit) visible++;
            });
            var er = document.getElementById('emptyRow');
            if(er){ er.hidden = visible !== 0; er.style.display = visible !== 0 ? 'none' : ''; }
          }
          // Filter tabel event di view events
          var evRows = document.querySelectorAll('#eventTableBody .event-row');
          if(evRows.length){
            evRows.forEach(function(r){
              var hit = (r.getAttribute('data-name') || r.textContent).toLowerCase().indexOf(q) !== -1;
              r.style.display = hit ? '' : 'none';
            });
          }
          // Filter tabel order di view orders
          var orRows = document.querySelectorAll('#orderTableBody .order-row');
          if(orRows.length){
            orRows.forEach(function(r){
              var hit = r.textContent.toLowerCase().indexOf(q) !== -1;
              r.style.display = hit ? '' : 'none';
            });
          }
        }
      })();
    </script>

    <?php 
      $flash = $flash ?? ($_SESSION['flash'] ?? []);
      unset($_SESSION['flash']);
      $flashMsg = $flash['message'] ?? $flash['success'] ?? $flash['error'] ?? '';
      if (!empty($flashMsg)):
    ?>
    <script>
      (function(){
        var msg = <?= json_encode((string)$flashMsg) ?>;
        if(window.addEventListener){
          window.addEventListener('load', function(){
            if(typeof showToast === 'function') showToast(msg);
          });
        }
      })();
    </script>
    <?php endif; ?>
<?php endif; ?>
