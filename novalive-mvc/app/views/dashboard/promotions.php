<?php
/**
 * Halaman Kelola Kode Promo Tiket — Gaya Quixotic
 * NOVA LIVE MVC — Tahap 5
 */
$currentUser = $currentUser ?? ['full_name' => 'Admin NOVA LIVE', 'role' => 'admin'];
$userInitials = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $currentUser['full_name'] ?? 'AD'), 0, 2));
if (strlen($userInitials) < 2) $userInitials = 'AD';
$flash = $flash ?? ($_SESSION['flash'] ?? []);
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <meta name="color-scheme" content="light" />
  <link rel="icon" href="data:," />
  <title><?= e($pageTitle ?? 'NOVA LIVE — Kode Promo') ?></title>
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
      --cream:#F5EFE0;
      --gold:#EAD4A2;
      --gold-dark:#A88338;
      --gold-deep:#8C6A24;
      --page:#EEF2F6;
      --panel:#FFFFFF;
      --card:#FFFFFF;
      --ink:#1D2D3A;
      --muted:#718292;
      --line:#E3E8EC;
      --line-subtle:#F0F3F6;
      --green:#1E8E5A;
      --green-bg:#E6F4EA;
      --red:#C5221F;
      --red-bg:#FCE8E6;
      --radius:20px;
      --shadow:0 8px 24px rgba(14,50,84,.07);
      --font-display:'Plus Jakarta Sans',sans-serif;
      --font-body:'Inter',system-ui,sans-serif;
    }
    *{box-sizing:border-box}
    body{
      margin:0;padding:22px 18px 30px;background:var(--page);color:var(--ink);
      font-family:var(--font-body);-webkit-font-smoothing:antialiased;
    }
    .frame{
      max-width:1680px;margin:0 auto;background:var(--panel);border-radius:30px;
      padding:20px 32px 26px;box-shadow:0 24px 60px rgba(14,50,84,.10);
    }
    .topbar{
      display:grid;grid-template-columns:1fr auto 1fr;align-items:center;gap:18px;
      background:var(--card);border-radius:999px;padding:10px 12px 10px 20px;
      box-shadow:var(--shadow);position:relative;z-index:30;
    }
    .logo{display:flex;align-items:center;gap:9px;text-decoration:none;color:var(--navy);flex:none;justify-self:start}
    .logo-ticket{height:40px;width:auto;display:block}
    .logo-word{font-family:'Plus Jakarta Sans',sans-serif;font-weight:800;font-size:19px;letter-spacing:1.5px;color:var(--navy)}
    .topnav{
      display:flex;gap:4px;margin:0;justify-self:center;background:#F1F3F4;border-radius:999px;padding:4px;
    }
    .topnav a, .topnav button{
      padding:7px 16px;border-radius:999px;font-size:13px;font-weight:600;color:#5B6B78;
      border:none;background:transparent;text-decoration:none;cursor:pointer;transition:all .15s;
    }
    .topnav a:hover, .topnav button:hover{color:var(--navy)}
    .topnav a.active, .topnav button.active{
      background:var(--navy);color:#fff;box-shadow:0 3px 10px rgba(14,50,84,.22);
    }
    .top-actions{display:flex;align-items:center;gap:6px;flex:none;justify-self:end;margin-left:0}
    .icon-btn{
      width:38px;height:38px;border-radius:50%;border:none;background:transparent;
      display:grid;place-items:center;color:#4A5965;cursor:pointer;position:relative;transition:background .15s;
    }
    .icon-btn:hover{background:#F1F4F7;color:var(--navy)}
    .avatar-btn{
      display:flex;align-items:center;gap:6px;padding:3px 8px 3px 3px;border-radius:999px;
      background:#F5F7FA;color:var(--navy);font-weight:700;font-size:12.5px;border:none;cursor:pointer;text-decoration:none;
    }
    .avatar{
      width:32px;height:32px;border-radius:50%;background:var(--navy);color:#fff;
      display:grid;place-items:center;font-size:11.5px;font-weight:800;letter-spacing:.5px;
    }
    .layout{display:flex;gap:22px;align-items:flex-start}
    .rail{
      flex:none;width:58px;background:var(--card);border-radius:999px;padding:14px 8px;
      box-shadow:var(--shadow);display:flex;flex-direction:column;align-items:center;gap:10px;
      position:sticky;top:20px;min-height:480px;
    }
    .rail a, .rail button{
      width:42px;height:42px;border-radius:50%;display:grid;place-items:center;color:#6A7885;
      position:relative;transition:background .15s,color .15s,transform .1s;text-decoration:none;
    }
    .rail a:hover, .rail button:hover{background:#F1F4F7;color:var(--navy)}
    .rail a.active, .rail button.active{
      background:var(--navy);color:#fff;box-shadow:0 6px 16px rgba(14,50,84,.28);
    }
    .rail .spacer{flex:1;min-height:20px}
    .views{flex:1;min-width:0;display:flex;flex-direction:column;gap:18px}

    /* Page Head */
    .page-head{
      display:flex;align-items:center;justify-content:space-between;gap:18px;
      flex-wrap:wrap;padding:6px 0 2px;
    }
    .page-head h1{
      font-size:26px;font-weight:800;margin:0 0 4px;letter-spacing:-.4px;color:var(--navy);
      font-family:var(--font-display);
    }
    .page-head p{margin:0;font-size:13.5px;color:var(--muted)}

    /* Alert / Flash */
    .alert{
      border-radius:14px;padding:12px 18px;font-size:13.5px;font-weight:500;display:flex;align-items:center;gap:10px;
    }
    .alert-success{background:var(--green-bg);color:var(--green);border:1px solid rgba(30,142,90,.2)}
    .alert-danger{background:var(--red-bg);color:var(--red);border:1px solid rgba(197,34,31,.2)}

    /* Cards */
    .card{
      background:var(--card);border-radius:var(--radius);padding:24px 26px;box-shadow:var(--shadow);
    }

    /* Promo Row Mockup Styling */
    .promo-row{
      display:flex;align-items:center;gap:14px;padding:16px 0;border-bottom:1px solid var(--line);flex-wrap:wrap;
    }
    .promo-row:last-child{border-bottom:0}
    .promo-code{
      font-family:'Plus Jakarta Sans',sans-serif;font-weight:800;letter-spacing:.05em;
      background:var(--cream);color:var(--gold-dark);border:1.5px dashed var(--gold-deep);
      border-radius:10px;padding:7px 14px;font-size:14px;white-space:nowrap;user-select:all;
    }
    .promo-info{flex:1;min-width:240px}
    .promo-info b{font-size:14.5px;color:var(--navy);display:block;margin-bottom:2px}
    .promo-info p{margin:0;font-size:12.5px;color:var(--muted);line-height:1.4}
    .promo-actions{display:flex;align-items:center;gap:8px;flex-wrap:wrap}

    /* Buttons */
    .btn-navy{
      display:inline-flex;align-items:center;justify-content:center;gap:7px;background:var(--navy);color:#fff;
      font-weight:700;font-size:13px;border-radius:12px;padding:9px 20px;cursor:pointer;border:none;
      box-shadow:0 4px 12px rgba(14,50,84,.22);transition:background .15s,transform .12s;text-decoration:none;
    }
    .btn-navy:hover{background:var(--navy-2);transform:translateY(-1px)}
    .btn-outline{
      border:1.5px solid var(--line);background:var(--card);color:var(--navy);border-radius:10px;
      padding:7px 14px;font-size:12.5px;font-weight:700;transition:all .15s;cursor:pointer;text-decoration:none;
      display:inline-flex;align-items:center;gap:5px;
    }
    .btn-outline:hover{background:#F8FAFB;border-color:var(--gold-deep);color:var(--gold-deep)}
    .btn-sm{padding:6px 12px;font-size:12px;min-height:32px}
    .mini-btn{
      border:1.5px solid var(--line);background:var(--card);color:#5B6B78;border-radius:9px;
      padding:5px 10px;font-size:12px;font-weight:600;min-height:32px;transition:all .15s;
      cursor:pointer;display:inline-flex;align-items:center;justify-content:center;
    }
    .mini-btn.warn{color:var(--red);border-color:rgba(197,34,31,.25);background:var(--red-bg)}
    .mini-btn.warn:hover{background:#fadbd8;border-color:var(--red)}

    /* Badges */
    .badge{
      display:inline-flex;align-items:center;gap:6px;font-size:11.5px;font-weight:700;
      border-radius:999px;padding:4px 11px;white-space:nowrap;
    }
    .badge::before{content:"";width:7px;height:7px;border-radius:50%;background:currentColor;flex:none}
    .b-live{background:var(--green-bg);color:var(--green)}
    .b-wait{background:var(--cream);color:var(--gold-dark)}
    .b-done{background:#F1F3F4;color:var(--muted)}
    .b-warn{background:#FFF3E0;color:#E65100}
    .b-draft{background:#EDF0F2;color:#52657C}

    /* Modal */
    .overlay{
      position:fixed;inset:0;background:rgba(14,50,84,.45);backdrop-filter:blur(3px);
      display:none;place-items:center;z-index:100;padding:18px;overflow-y:auto;
    }
    .overlay.open{display:grid}
    .modal{background:#fff;border-radius:24px;max-width:520px;width:100%;padding:26px;box-shadow:0 30px 70px rgba(14,50,84,.3);position:relative;margin:auto}
    .modal h2{margin:0 0 6px;font-size:19px;color:var(--navy);padding-right:34px;font-family:var(--font-display)}
    .modal p.sub{margin:0 0 18px;font-size:13px;color:var(--muted)}
    .modal-x{
      position:absolute;top:16px;right:16px;width:34px;height:34px;border-radius:50%;
      border:none;background:transparent;display:grid;place-items:center;color:#8A97A3;font-size:15px;cursor:pointer;
    }
    .modal-x:hover{background:#EEF3F7;color:var(--navy)}

    .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:13px}
    .field{display:flex;flex-direction:column;gap:5px;margin-bottom:12px}
    .field.full{grid-column:1 / -1}
    .field label{font-size:12.5px;font-weight:700;color:var(--navy)}
    .field input, .field select{
      border:1.5px solid var(--line);border-radius:11px;padding:9px 13px;font-family:inherit;
      font-size:13.5px;color:var(--ink);background:var(--card);transition:border-color .15s;outline:none;
    }
    .field input:focus, .field select:focus{border-color:var(--navy)}

    @media (max-width:820px){
      body{padding:12px 10px 20px}
      .frame{padding:14px 16px 20px;border-radius:20px}
      .form-grid{grid-template-columns:1fr}
      .promo-row{flex-direction:column;align-items:flex-start}
      .promo-actions{width:100%;justify-content:flex-start}
    }
  </style>
</head>
<body>
  <div class="frame">

    <?php require __DIR__ . '/sidebar.php'; ?>

    <!-- ========== VIEW: PROMOSI ========== -->
    <section class="view" id="view-promosi" aria-label="Promosi">
      <div class="page-head">
        <div>
          <h1>Promosi</h1>
          <p>Kode promo aktif untuk event kamu — klik Salin untuk membagikannya</p>
        </div>
        <div>
          <button class="btn-navy" id="openAddPromoBtn" type="button">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
            Tambah Kode Promo
          </button>
        </div>
      </div>

      <?php if (!empty($flash['success'])): ?>
        <div class="alert alert-success"><?= e($flash['success']) ?></div>
      <?php endif; ?>
      <?php if (!empty($flash['error'])): ?>
        <div class="alert alert-danger"><?= e($flash['error']) ?></div>
      <?php endif; ?>

      <div class="card">
        <?php if (empty($promos)): ?>
          <div style="text-align:center;padding:40px 20px;color:var(--muted)">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" style="margin-bottom:10px;opacity:.6"><path d="M3 11V7a2 2 0 0 1 2-2h4l9 9-4 4-9-9z"/><circle cx="8" cy="9" r="1.4"/></svg>
            <p style="margin:0;font-size:14px;font-weight:600">Belum ada kode promo.</p>
            <p style="margin:4px 0 0;font-size:12.5px">Buat kode promo pertama kamu untuk menarik lebih banyak pembeli tiket.</p>
          </div>
        <?php else: ?>
          <?php foreach ($promos as $p): ?>
            <?php
              $st = $p['calculated_status'] ?? 'Aktif';
              $badgeClass = 'b-live';
              if ($st === 'Berakhir') $badgeClass = 'b-done';
              elseif ($st === 'Habis') $badgeClass = 'b-warn';
              elseif ($st === 'Terjadwal') $badgeClass = 'b-wait';
              elseif ($st === 'Nonaktif') $badgeClass = 'b-draft';

              $discountLabel = $p['type'] === 'persen'
                ? 'Diskon ' . (int)$p['value'] . '%'
                : 'Diskon ' . rupiah($p['value']);

              $targetLabel = !empty($p['event_title']) ? $p['event_title'] : 'Semua Event';

              $validityStr = !empty($p['ends_at'])
                ? 'Berlaku s.d. ' . tanggal($p['ends_at'], 'd M Y')
                : 'Tanpa batas tanggal';

              $usageStr = 'sudah dipakai ' . number_format($p['used'], 0, ',', '.') . ' kali';
              $quotaStr = $p['quota'] !== null ? 'kuota ' . number_format($p['quota'], 0, ',', '.') : 'kuota tidak terbatas';
            ?>
            <div class="promo-row">
              <span class="promo-code"><?= e($p['code']) ?></span>
              <div class="promo-info">
                <b><?= $discountLabel ?> — <?= e($targetLabel) ?></b>
                <p><?= $validityStr ?> · <?= $usageStr ?> · <?= $quotaStr ?></p>
              </div>
              <span class="badge <?= $badgeClass ?>"><?= e($st) ?></span>
              <div class="promo-actions">
                <button class="btn-outline btn-sm js-copy-code" type="button" data-code="<?= e($p['code']) ?>">Salin Kode</button>

                <form method="POST" action="<?= base_url('dashboard/promotions/toggle/' . $p['id']) ?>" style="display:inline;margin:0">
                  <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                  <button class="btn-outline btn-sm" type="submit" title="<?= empty($p['is_active']) ? 'Aktifkan kode promo' : 'Nonaktifkan kode promo' ?>">
                    <?= empty($p['is_active']) ? 'Aktifkan' : 'Nonaktifkan' ?>
                  </button>
                </form>

                <button class="btn-outline btn-sm js-edit-promo" type="button"
                  data-id="<?= $p['id'] ?>"
                  data-code="<?= e($p['code']) ?>"
                  data-type="<?= e($p['type']) ?>"
                  data-value="<?= $p['value'] ?>"
                  data-event-id="<?= (int)($p['event_id'] ?? 0) ?>"
                  data-quota="<?= $p['quota'] ?? '' ?>"
                  data-starts-at="<?= e($p['starts_at'] ?? '') ?>"
                  data-ends-at="<?= e($p['ends_at'] ?? '') ?>">Ubah</button>

                <form method="POST" action="<?= base_url('dashboard/promotions/delete/' . $p['id']) ?>" style="display:inline;margin:0" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kode promo <?= e($p['code']) ?>?')">
                  <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                  <button class="mini-btn warn" type="submit" title="Hapus kode promo">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2M10 11v6M14 11v6"/></svg>
                  </button>
                </form>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </section>

    <p class="foot-note" style="margin:26px 0 0;font-size:12px;color:var(--muted);text-align:center">© 2026 NOVA LIVE. Hak Cipta Dilindungi. · Dashboard Penyelenggara</p>

    <?php $shellFooter = true; require __DIR__ . '/sidebar.php'; ?>

  </div><!-- /.frame -->

  <!-- Modal Tambah Kode Promo -->
  <div class="overlay" id="addPromoModal">
    <div class="modal">
      <button class="modal-x" type="button" id="closeAddPromoModal" aria-label="Tutup">&times;</button>
      <h2>Tambah Kode Promo</h2>
      <p class="sub">Buat voucher diskon baru untuk pembeli tiket</p>

      <form method="POST" action="<?= base_url('dashboard/promotions/store') ?>">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

        <div class="field full">
          <label for="promoCode">Kode Promo</label>
          <input type="text" id="promoCode" name="code" placeholder="cth. NOVAHEMAT2026" maxlength="30" required style="text-transform:uppercase" />
        </div>

        <div class="form-grid">
          <div class="field">
            <label for="promoType">Tipe Diskon</label>
            <select id="promoType" name="type" required>
              <option value="persen">Persentase (%)</option>
              <option value="nominal">Nominal Tunai (Rp)</option>
            </select>
          </div>
          <div class="field">
            <label for="promoValue" id="promoValueLabel">Nilai Diskon (%)</label>
            <input type="number" id="promoValue" name="value" min="1" max="100" step="any" placeholder="cth. 15" required />
          </div>
        </div>

        <div class="field full">
          <label for="promoEventId">Berlaku Untuk Event</label>
          <select id="promoEventId" name="event_id">
            <option value="">Semua Event (Global)</option>
            <?php foreach ($events as $ev): ?>
              <option value="<?= $ev['id'] ?>"><?= e($ev['title']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="field full">
          <label for="promoQuota">Batas Kuota Penggunaan (Opsional)</label>
          <input type="number" id="promoQuota" name="quota" min="1" placeholder="Kosongkan jika tidak terbatas" />
        </div>

        <div class="form-grid">
          <div class="field">
            <label for="promoStartsAt">Tanggal Mulai</label>
            <input type="date" id="promoStartsAt" name="starts_at" />
          </div>
          <div class="field">
            <label for="promoEndsAt">Tanggal Berakhir</label>
            <input type="date" id="promoEndsAt" name="ends_at" />
          </div>
        </div>

        <div style="margin-top:18px;display:flex;justify-content:flex-end;gap:10px">
          <button type="button" class="btn-outline" id="cancelAddPromoBtn">Batal</button>
          <button type="submit" class="btn-navy">Simpan Kode Promo</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal Ubah Kode Promo -->
  <div class="overlay" id="editPromoModal">
    <div class="modal">
      <button class="modal-x" type="button" id="closeEditPromoModal" aria-label="Tutup">&times;</button>
      <h2>Ubah Kode Promo</h2>
      <p class="sub">Perbarui informasi dan batasan kode promo</p>

      <form method="POST" id="editPromoForm" action="">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

        <div class="field full">
          <label for="editPromoCode">Kode Promo</label>
          <input type="text" id="editPromoCode" name="code" maxlength="30" required style="text-transform:uppercase" />
        </div>

        <div class="form-grid">
          <div class="field">
            <label for="editPromoType">Tipe Diskon</label>
            <select id="editPromoType" name="type" required>
              <option value="persen">Persentase (%)</option>
              <option value="nominal">Nominal Tunai (Rp)</option>
            </select>
          </div>
          <div class="field">
            <label for="editPromoValue" id="editPromoValueLabel">Nilai Diskon</label>
            <input type="number" id="editPromoValue" name="value" step="any" required />
          </div>
        </div>

        <div class="field full">
          <label for="editPromoEventId">Berlaku Untuk Event</label>
          <select id="editPromoEventId" name="event_id">
            <option value="">Semua Event (Global)</option>
            <?php foreach ($events as $ev): ?>
              <option value="<?= $ev['id'] ?>"><?= e($ev['title']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="field full">
          <label for="editPromoQuota">Batas Kuota Penggunaan (Opsional)</label>
          <input type="number" id="editPromoQuota" name="quota" min="1" placeholder="Kosongkan jika tidak terbatas" />
        </div>

        <div class="form-grid">
          <div class="field">
            <label for="editPromoStartsAt">Tanggal Mulai</label>
            <input type="date" id="editPromoStartsAt" name="starts_at" />
          </div>
          <div class="field">
            <label for="editPromoEndsAt">Tanggal Berakhir</label>
            <input type="date" id="editPromoEndsAt" name="ends_at" />
          </div>
        </div>

        <div style="margin-top:18px;display:flex;justify-content:flex-end;gap:10px">
          <button type="button" class="btn-outline" id="cancelEditPromoBtn">Batal</button>
          <button type="submit" class="btn-navy">Perbarui Promo</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    (function(){
      // Copy Code Handler
      document.querySelectorAll('.js-copy-code').forEach(function(btn){
        btn.addEventListener('click', function(){
          var code = this.getAttribute('data-code');
          if (!code) return;
          if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(code).then(function(){
              if (typeof showToast === 'function') {
                showToast('Kode promo <strong>' + code + '</strong> berhasil disalin!');
              } else {
                alert('Kode promo ' + code + ' berhasil disalin!');
              }
            });
          } else {
            var tempInp = document.createElement('input');
            tempInp.value = code;
            document.body.appendChild(tempInp);
            tempInp.select();
            document.execCommand('copy');
            document.body.removeChild(tempInp);
            if (typeof showToast === 'function') {
              showToast('Kode promo <strong>' + code + '</strong> berhasil disalin!');
            }
          }
        });
      });

      // Type switch for Add modal
      var promoType = document.getElementById('promoType');
      var promoVal = document.getElementById('promoValue');
      var promoValLbl = document.getElementById('promoValueLabel');
      if (promoType && promoVal) {
        promoType.addEventListener('change', function(){
          if (this.value === 'persen') {
            promoValLbl.textContent = 'Nilai Diskon (%)';
            promoVal.min = '1';
            promoVal.max = '100';
            promoVal.placeholder = 'cth. 15';
          } else {
            promoValLbl.textContent = 'Nilai Potongan (Rp)';
            promoVal.min = '1000';
            promoVal.removeAttribute('max');
            promoVal.placeholder = 'cth. 25000';
          }
        });
      }

      // Add Modal
      var openAddBtn = document.getElementById('openAddPromoBtn');
      var addModal = document.getElementById('addPromoModal');
      var closeAddBtn = document.getElementById('closeAddPromoModal');
      var cancelAddBtn = document.getElementById('cancelAddPromoBtn');
      function openAdd(){ if (addModal) addModal.classList.add('open'); }
      function closeAdd(){ if (addModal) addModal.classList.remove('open'); }
      if (openAddBtn) openAddBtn.addEventListener('click', openAdd);
      if (closeAddBtn) closeAddBtn.addEventListener('click', closeAdd);
      if (cancelAddBtn) cancelAddBtn.addEventListener('click', closeAdd);
      if (addModal) addModal.addEventListener('click', function(e){ if (e.target === addModal) closeAdd(); });

      // Edit Modal
      var editModal = document.getElementById('editPromoModal');
      var closeEditBtn = document.getElementById('closeEditPromoModal');
      var cancelEditBtn = document.getElementById('cancelEditPromoBtn');
      var editForm = document.getElementById('editPromoForm');
      function closeEdit(){ if (editModal) editModal.classList.remove('open'); }
      if (closeEditBtn) closeEditBtn.addEventListener('click', closeEdit);
      if (cancelEditBtn) cancelEditBtn.addEventListener('click', closeEdit);
      if (editModal) editModal.addEventListener('click', function(e){ if (e.target === editModal) closeEdit(); });

      document.querySelectorAll('.js-edit-promo').forEach(function(btn){
        btn.addEventListener('click', function(){
          var id = this.getAttribute('data-id');
          var code = this.getAttribute('data-code');
          var type = this.getAttribute('data-type');
          var val = this.getAttribute('data-value');
          var evId = this.getAttribute('data-event-id');
          var quota = this.getAttribute('data-quota');
          var start = this.getAttribute('data-starts-at');
          var end = this.getAttribute('data-ends-at');

          if (editForm) {
            editForm.action = '<?= base_url('dashboard/promotions/update/') ?>' + id;
          }
          document.getElementById('editPromoCode').value = code || '';
          document.getElementById('editPromoType').value = type || 'persen';
          document.getElementById('editPromoValue').value = val || '';
          document.getElementById('editPromoEventId').value = (evId && evId !== '0') ? evId : '';
          document.getElementById('editPromoQuota').value = quota || '';
          document.getElementById('editPromoStartsAt').value = start || '';
          document.getElementById('editPromoEndsAt').value = end || '';

          if (editModal) editModal.classList.add('open');
        });
      });
    })();
  </script>
</body>
</html>
