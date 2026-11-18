<div style="max-width:440px;margin:50px auto;padding:0 20px;">
  <div style="background:var(--card);border-radius:20px;box-shadow:var(--shadow-lg);border:1px solid var(--line);padding:36px 32px;">
    <div style="text-align:center;margin-bottom:28px;">
      <div style="display:inline-flex;align-items:center;justify-content:center;width:56px;height:56px;border-radius:14px;background:var(--navy);color:var(--gold);margin-bottom:12px;">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
      </div>
      <h1 style="font-size:24px;font-weight:800;color:var(--navy);">Masuk ke Akun</h1>
      <p style="font-size:14px;color:var(--muted);margin-top:4px;">Kelola katalog event dan transaksi tiketmu</p>
    </div>

    <form action="<?= base_url('auth/authenticate') ?>" method="POST">
      <?= csrf_field() ?>

      <div style="margin-bottom:18px;">
        <label for="email" style="display:block;font-size:13.5px;font-weight:700;color:var(--navy);margin-bottom:6px;">Alamat Email</label>
        <input type="email" name="email" id="email" required placeholder="nama@novalive.id" style="width:100%;padding:12px 14px;border:1px solid var(--line);border-radius:10px;font-size:14.5px;outline:none;background:#FAFCFF;color:var(--ink);">
      </div>

      <div style="margin-bottom:24px;">
        <label for="password" style="display:block;font-size:13.5px;font-weight:700;color:var(--navy);margin-bottom:6px;">Kata Sandi</label>
        <input type="password" name="password" id="password" required placeholder="••••••••" style="width:100%;padding:12px 14px;border:1px solid var(--line);border-radius:10px;font-size:14.5px;outline:none;background:#FAFCFF;color:var(--ink);">
      </div>

      <button type="submit" style="width:100%;padding:13px;border:0;border-radius:999px;background:var(--navy);color:#fff;font-size:15px;font-weight:700;cursor:pointer;transition:background 0.2s,transform 0.15s;box-shadow:var(--shadow);">
        Masuk Sekarang
      </button>
    </form>

    <!-- Info Akun Demo -->
    <div style="margin-top:28px;padding-top:20px;border-top:1px dashed var(--line);">
      <div style="font-size:12.5px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:0.04em;margin-bottom:10px;">
        Pilihan Cepat Akun Demo (Password: nova1234)
      </div>
      <div style="display:grid;gap:8px;">
        <button type="button" onclick="fillDemo('demo@novalive.id')" style="text-align:left;background:var(--cream);border:1px solid #E8DFCB;padding:8px 12px;border-radius:8px;font-size:12.5px;cursor:pointer;display:flex;justify-content:space-between;align-items:center;">
          <span><strong>Admin:</strong> demo@novalive.id</span>
          <span style="color:var(--brand);font-weight:700;">Isi Formulir →</span>
        </button>
        <button type="button" onclick="fillDemo('manager@novalive.id')" style="text-align:left;background:var(--cream);border:1px solid #E8DFCB;padding:8px 12px;border-radius:8px;font-size:12.5px;cursor:pointer;display:flex;justify-content:space-between;align-items:center;">
          <span><strong>Manager:</strong> manager@novalive.id</span>
          <span style="color:var(--brand);font-weight:700;">Isi Formulir →</span>
        </button>
        <button type="button" onclick="fillDemo('staff@novalive.id')" style="text-align:left;background:var(--cream);border:1px solid #E8DFCB;padding:8px 12px;border-radius:8px;font-size:12.5px;cursor:pointer;display:flex;justify-content:space-between;align-items:center;">
          <span><strong>Staff:</strong> staff@novalive.id</span>
          <span style="color:var(--brand);font-weight:700;">Isi Formulir →</span>
        </button>
      </div>
    </div>
  </div>
</div>

<script>
  function fillDemo(email) {
    document.getElementById('email').value = email;
    document.getElementById('password').value = 'nova1234';
  }
</script>
