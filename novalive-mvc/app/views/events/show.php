<?php
/**
 * View: Detail Event (Show)
 * Samakan persis dengan desain mockup (NOVA LIVE — Jelajahi Event.html)
 */

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

$catColor = !empty($event['category_color']) ? $event['category_color'] : '#EDE9FE';
$catIcon = !empty($event['category_icon']) ? $event['category_icon'] : '✦';
?>

<style>
/* Detail event */
.detail-view { padding: 26px 0 48px; }
.btn-back { display: inline-flex; align-items: center; gap: 8px; background: var(--card); border: 1px solid var(--line); border-radius: 999px; padding: 10px 18px; min-height: 44px; font-weight: 700; font-size: 14px; color: var(--brand); box-shadow: 0 1px 2px rgba(14,42,71,.05); margin-bottom: 22px; text-decoration: none; transition: border-color .2s; }
.btn-back:hover { border-color: var(--brand); color: var(--navy2); }
.detail-grid { display: grid; grid-template-columns: 1.15fr .85fr; gap: 44px; align-items: start; }
@media (max-width: 900px) { .detail-grid { grid-template-columns: 1fr; gap: 28px; } }

.cat-badge { display: inline-flex; align-items: center; gap: 7px; font-size: 13px; font-weight: 700; padding: 6px 13px; border-radius: 999px; color: var(--ink); border: 1px solid var(--line); }
.cat-badge .cat-ico { font-size: 14px; line-height: 1; }
.detail-title { font-family: var(--font-display, 'Plus Jakarta Sans', sans-serif); font-size: clamp(28px, 4vw, 40px); font-weight: 800; line-height: 1.15; margin: 14px 0 22px; color: var(--ink); }
.detail-label { font-size: 14px; font-weight: 600; color: var(--muted); margin: 0 0 10px; }
.org-row { display: flex; align-items: center; gap: 12px; margin-bottom: 22px; }
.org-row .org-avatar { width: 44px; height: 44px; font-size: 14px; border-radius: 50%; display: grid; place-items: center; font-weight: 800; color: #0E2A47; flex: none; }
.org-row .org-name { font-size: 16px; font-weight: 700; color: var(--ink); }

.social-row { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 30px; }
.social-btn { display: inline-flex; align-items: center; gap: 9px; background: #EEF4F9; color: var(--brand); font-weight: 700; font-size: 14px; padding: 9px 16px 9px 9px; border-radius: 999px; min-height: 44px; text-decoration: none; cursor: pointer; }
.social-btn:hover { background: #E0EBF4; }
.social-ico { width: 30px; height: 30px; border-radius: 50%; background: var(--brand); color: #fff; display: grid; place-items: center; flex: none; }

.tabs { display: flex; gap: 4px; border-bottom: 1px solid var(--line); margin-bottom: 22px; }
.tab-btn { font-family: var(--font-display, 'Plus Jakarta Sans', sans-serif); font-weight: 700; font-size: 15px; color: var(--muted); padding: 12px 16px; border: 0; background: none; border-bottom: 3px solid transparent; margin-bottom: -1px; min-height: 48px; cursor: pointer; }
.tab-btn.active { color: var(--ink); border-bottom-color: var(--brand); }
.tab-panel { font-size: 15px; line-height: 1.75; color: var(--ink); }
.tab-panel[hidden] { display: none; }
.tab-panel h3 { font-size: 20px; margin: 0 0 10px; font-family: var(--font-display, 'Plus Jakarta Sans', sans-serif); color: var(--ink); }
.tab-panel p { margin: 0 0 14px; color: #243B55; }
.tab-panel .tagline { font-style: italic; font-weight: 600; }
.tab-panel ol { margin: 0; padding-left: 20px; display: grid; gap: 12px; color: #243B55; }

.detail-poster { border-radius: var(--radius); overflow: hidden; background: linear-gradient(135deg, #0E3254 0%, #23649A 100%); aspect-ratio: 16/9; display: flex; align-items: center; justify-content: center; font-size: 72px; font-weight: 800; color: var(--gold); position: relative; user-select: none; font-family: var(--font-display, 'Plus Jakarta Sans', sans-serif); box-shadow: var(--shadow); }
.detail-poster .badge { position: absolute; bottom: 12px; right: 12px; z-index: 3; font-size: 11px; padding: 5px 11px; }

.detail-acara { margin-top: 22px; }
.detail-acara h3 { font-size: 18px; margin: 0 0 14px; font-family: var(--font-display, 'Plus Jakarta Sans', sans-serif); color: var(--ink); }
.acara-row { display: flex; align-items: center; gap: 12px; font-size: 15px; font-weight: 600; padding: 7px 0; color: var(--ink); }
.acara-ico { width: 32px; height: 32px; border-radius: 50%; background: #EEF4F9; color: var(--brand); display: grid; place-items: center; flex: none; }

.price-card { margin-top: 24px; background: #F4F7FA; border: 1px solid var(--line); border-radius: var(--radius); padding: 20px; display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; }
.price-card small { display: block; font-size: 13.5px; color: var(--muted); font-weight: 500; margin-bottom: 4px; }
.price-card .amount { font-family: var(--font-display, 'Plus Jakarta Sans', sans-serif); font-size: 22px; font-weight: 800; color: var(--ink); }
.btn-pesan { background: var(--brand); color: #fff; font-weight: 700; font-size: 15px; padding: 14px 26px; border-radius: 12px; min-height: 50px; white-space: nowrap; border: 0; cursor: pointer; transition: background .2s; }
.btn-pesan:hover { background: var(--navy2); }
.demo-note { width: 100%; font-size: 12.5px; color: var(--muted); line-height: 1.5; margin: 6px 0 0; }
</style>

<div class="wrap">
  <section class="detail-view" id="detailView" aria-label="Detail event">
    <a class="btn-back" id="backBtn" href="<?= base_url('events') ?>">
      <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
      Kembali ke Jelajahi Event
    </a>

    <div class="detail-grid">
      <!-- Kolom Kiri -->
      <div>
        <span class="cat-badge" id="dCatBadge" style="background:<?= e($catColor) ?>;">
          <span class="cat-ico" id="dCatIcon"><?= e($catIcon) ?></span>
          <span id="dCatName"><?= e($event['category_name'] ?? 'Umum') ?></span>
        </span>

        <h1 class="detail-title" id="dTitle"><?= e($event['title']) ?></h1>

        <p class="detail-label">Penyelenggara</p>
        <div class="org-row">
          <span class="org-avatar" id="dOrgAvatar" style="background:<?= getAvColor($event['organizer']) ?>;">
            <?= getInitials($event['organizer']) ?>
          </span>
          <span class="org-name" id="dOrgName"><?= e($event['organizer']) ?></span>
        </div>

        <p class="detail-label">Media Sosial</p>
        <div class="social-row">
          <a class="social-btn" href="#" onclick="return false" aria-label="Instagram penyelenggara">
            <span class="social-ico"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.2" cy="6.8" r="1" fill="currentColor" stroke="none"/></svg></span>
            Instagram
          </a>
          <a class="social-btn" href="#" onclick="return false" aria-label="TikTok penyelenggara">
            <span class="social-ico"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M16.6 3c.4 2.2 1.8 3.6 4.1 3.8v3c-1.6 0-3-.5-4.1-1.3v6.3c0 3.4-2.4 5.7-5.6 5.7A5.4 5.4 0 0 1 5.5 15c0-3 2.3-5.4 5.4-5.4l1.1.1v3.1a2.4 2.4 0 0 0-1.1-.3 2.3 2.3 0 0 0-2.3 2.4 2.3 2.3 0 0 0 2.4 2.3c1.4 0 2.4-1 2.4-2.6V3h3.2z"/></svg></span>
            TikTok
          </a>
        </div>

        <div class="tabs" role="tablist" aria-label="Informasi event">
          <button class="tab-btn active" id="tabDescBtn" type="button" role="tab" aria-selected="true" aria-controls="tabDesc">Deskripsi</button>
          <button class="tab-btn" id="tabTermsBtn" type="button" role="tab" aria-selected="false" aria-controls="tabTerms">Syarat &amp; Ketentuan</button>
        </div>

        <div class="tab-panel" id="tabDesc" role="tabpanel">
          <h3>Deskripsi</h3>
          <p class="tagline" id="dTagline">Satu malam istimewa bersama <?= e($event['title']) ?> di <?= e($event['city_name'] ?? 'Indonesia') ?>.</p>
          <p id="dDesc1">
            <?= nl2br(e($event['description'] ?? 'Event istimewa yang diselenggarakan langsung di lokasi terbaik untuk pengalaman hiburan dan edukasi berkesan.')) ?>
          </p>
          <p id="dDesc2">
            Acara ini diselenggarakan secara resmi oleh <strong><?= e($event['organizer']) ?></strong>. Dapatkan tiket resminya hanya melalui NOVA LIVE.
          </p>
        </div>

        <div class="tab-panel" id="tabTerms" role="tabpanel" hidden>
          <h3>Syarat &amp; Ketentuan</h3>
          <ol id="dTerms">
            <li>Tiket resmi hanya dijual melalui NOVA LIVE atau mitra penjualan resmi yang ditunjuk oleh penyelenggara (<?= e($event['organizer']) ?>).</li>
            <li>Satu e-tiket berlaku untuk satu orang dan satu kali masuk ke area acara.</li>
            <li>E-tiket wajib ditukarkan atau ditunjukkan saat registrasi ulang di lokasi acara; siapkan kartu identitas yang sesuai dengan nama pemesan.</li>
            <li>Tiket yang sudah dibeli tidak dapat dikembalikan (non-refundable) dan tidak dapat diubah jadwalnya, kecuali acara dibatalkan secara sepihak oleh penyelenggara.</li>
            <li>Pengunjung wajib menjaga ketertiban selama acara berlangsung dan mengikuti seluruh instruksi serta protokol keselamatan di lokasi.</li>
          </ol>
        </div>
      </div>

      <!-- Kolom Kanan -->
      <div>
        <div class="detail-poster" id="dPoster" aria-label="Poster <?= e($event['title']) ?>">
          <?= strtoupper(mb_substr($event['title'], 0, 1)) ?>
          <?php if (!empty($event['badge'])): ?>
            <span class="badge <?= trim($event['badge']) === 'BARU' ? 'new' : '' ?>">
              <?= e($event['badge']) ?>
            </span>
          <?php endif; ?>
        </div>

        <div class="detail-acara">
          <h3>Detail Acara</h3>
          <div class="acara-row">
            <span class="acara-ico"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4"/></svg></span>
            <span id="dDate"><?= tanggal($event['event_date'], 'l, d F Y') ?></span>
          </div>
          <div class="acara-row">
            <span class="acara-ico"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></span>
            <span id="dTime"><?= e($event['event_time'] ?? '19:00') ?> WIB</span>
          </div>
          <div class="acara-row">
            <span class="acara-ico"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s-7-5.5-7-11a7 7 0 1 1 14 0c0 5.5-7 11-7 11z"/><circle cx="12" cy="10" r="2.6"/></svg></span>
            <span id="dVenue"><?= e($event['venue'] ?? 'Venue Ditentukan') ?>, <?= e($event['city_name'] ?? '') ?></span>
          </div>
        </div>

        <div class="price-card">
          <div>
            <small>Harga mulai dari</small>
            <span class="amount" id="dPrice"><?= (float)$event['price'] > 0 ? rupiah($event['price']) : 'Gratis' ?></span>
          </div>
          <button class="btn-pesan" type="button" onclick="alert('Demo Mockup: Fitur pemesanan tiket sedang dalam tahap demonstrasi dan belum terhubung ke gerbang pembayaran.')">
            Pesan Sekarang
          </button>
          <div class="demo-note">
            ℹ️ <em>Demo: Sistem pembayaran / checkout belum dibangun pada tahap ini.</em>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
  var tabDescBtn = document.getElementById("tabDescBtn");
  var tabTermsBtn = document.getElementById("tabTermsBtn");
  var tabDesc = document.getElementById("tabDesc");
  var tabTerms = document.getElementById("tabTerms");
  if (tabDescBtn && tabTermsBtn && tabDesc && tabTerms) {
    function setTab(isDesc) {
      tabDescBtn.classList.toggle("active", isDesc);
      tabTermsBtn.classList.toggle("active", !isDesc);
      tabDescBtn.setAttribute("aria-selected", isDesc ? "true" : "false");
      tabTermsBtn.setAttribute("aria-selected", !isDesc ? "true" : "false");
      tabDesc.hidden = !isDesc;
      tabTerms.hidden = isDesc;
    }
    tabDescBtn.addEventListener("click", function () { setTab(true); });
    tabTermsBtn.addEventListener("click", function () { setTab(false); });
  }
});
</script>
