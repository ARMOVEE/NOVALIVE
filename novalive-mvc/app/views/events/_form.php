<?php
// Partial form untuk Tambah & Ubah Event
?>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
  <div style="margin-bottom:16px;">
    <label for="code" style="display:block;font-size:13px;font-weight:700;color:var(--navy);margin-bottom:6px;">Kode Event</label>
    <input type="text" name="code" id="code" value="<?= e($event['code'] ?? ($nextCode ?? '')) ?>" required readonly style="width:100%;padding:10px 12px;border:1px solid var(--line);border-radius:8px;background:#F0F4F8;color:var(--muted);font-weight:700;">
  </div>

  <div style="margin-bottom:16px;">
    <label for="title" style="display:block;font-size:13px;font-weight:700;color:var(--navy);margin-bottom:6px;">Judul Event *</label>
    <input type="text" name="title" id="title" value="<?= e($event['title'] ?? '') ?>" required placeholder="Contoh: Konser Musik Harmoni" style="width:100%;padding:10px 12px;border:1px solid var(--line);border-radius:8px;font-size:14px;background:#fff;">
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
  <div style="margin-bottom:16px;">
    <label for="category_id" style="display:block;font-size:13px;font-weight:700;color:var(--navy);margin-bottom:6px;">Kategori *</label>
    <select name="category_id" id="category_id" required style="width:100%;padding:10px 12px;border:1px solid var(--line);border-radius:8px;background:#fff;">
      <option value="">Pilih Kategori</option>
      <?php foreach ($categories as $cat): ?>
        <option value="<?= $cat['id'] ?>" <?= (!empty($event['category_id']) && $event['category_id'] == $cat['id']) ? 'selected' : '' ?>>
          <?= e($cat['name']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <div style="margin-bottom:16px;">
    <label for="city_id" style="display:block;font-size:13px;font-weight:700;color:var(--navy);margin-bottom:6px;">Kota Penyelenggaraan *</label>
    <select name="city_id" id="city_id" required style="width:100%;padding:10px 12px;border:1px solid var(--line);border-radius:8px;background:#fff;">
      <option value="">Pilih Kota</option>
      <?php foreach ($cities as $ci): ?>
        <option value="<?= $ci['id'] ?>" <?= (!empty($event['city_id']) && $event['city_id'] == $ci['id']) ? 'selected' : '' ?>>
          <?= e($ci['name']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
  <div style="margin-bottom:16px;">
    <label for="organizer" style="display:block;font-size:13px;font-weight:700;color:var(--navy);margin-bottom:6px;">Penyelenggara *</label>
    <input type="text" name="organizer" id="organizer" value="<?= e($event['organizer'] ?? '') ?>" required placeholder="Nama EO / Penyelenggara" style="width:100%;padding:10px 12px;border:1px solid var(--line);border-radius:8px;background:#fff;">
  </div>

  <div style="margin-bottom:16px;">
    <label for="venue" style="display:block;font-size:13px;font-weight:700;color:var(--navy);margin-bottom:6px;">Lokasi / Gedung</label>
    <input type="text" name="venue" id="venue" value="<?= e($event['venue'] ?? '') ?>" placeholder="Contoh: Istora Senayan, Jakarta" style="width:100%;padding:10px 12px;border:1px solid var(--line);border-radius:8px;background:#fff;">
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:20px;">
  <div style="margin-bottom:16px;">
    <label for="event_date" style="display:block;font-size:13px;font-weight:700;color:var(--navy);margin-bottom:6px;">Tanggal Event *</label>
    <input type="date" name="event_date" id="event_date" value="<?= e($event['event_date'] ?? '') ?>" required style="width:100%;padding:10px 12px;border:1px solid var(--line);border-radius:8px;background:#fff;">
  </div>

  <div style="margin-bottom:16px;">
    <label for="event_time" style="display:block;font-size:13px;font-weight:700;color:var(--navy);margin-bottom:6px;">Jam Mulai</label>
    <input type="text" name="event_time" id="event_time" value="<?= e($event['event_time'] ?? '19:00') ?>" placeholder="19:00" style="width:100%;padding:10px 12px;border:1px solid var(--line);border-radius:8px;background:#fff;">
  </div>

  <div style="margin-bottom:16px;">
    <label for="badge" style="display:block;font-size:13px;font-weight:700;color:var(--navy);margin-bottom:6px;">Badge Khusus</label>
    <select name="badge" id="badge" style="width:100%;padding:10px 12px;border:1px solid var(--line);border-radius:8px;background:#fff;">
      <option value="">Tanpa Badge</option>
      <option value="BARU" <?= (!empty($event['badge']) && $event['badge'] === 'BARU') ? 'selected' : '' ?>>BARU</option>
      <option value="Tinggal Sedikit" <?= (!empty($event['badge']) && $event['badge'] === 'Tinggal Sedikit') ? 'selected' : '' ?>>Tinggal Sedikit</option>
    </select>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:20px;">
  <div style="margin-bottom:16px;">
    <label for="price" style="display:block;font-size:13px;font-weight:700;color:var(--navy);margin-bottom:6px;">Harga Tiket (Rp)</label>
    <input type="number" step="1000" name="price" id="price" value="<?= (float)($event['price'] ?? 0) ?>" required min="0" style="width:100%;padding:10px 12px;border:1px solid var(--line);border-radius:8px;background:#fff;">
  </div>

  <div style="margin-bottom:16px;">
    <label for="quota" style="display:block;font-size:13px;font-weight:700;color:var(--navy);margin-bottom:6px;">Total Kuota Tiket</label>
    <input type="number" name="quota" id="quota" value="<?= (int)($event['quota'] ?? 500) ?>" required min="1" style="width:100%;padding:10px 12px;border:1px solid var(--line);border-radius:8px;background:#fff;">
  </div>

  <div style="margin-bottom:16px;">
    <label for="sold" style="display:block;font-size:13px;font-weight:700;color:var(--navy);margin-bottom:6px;">Tiket Terjual</label>
    <input type="number" name="sold" id="sold" value="<?= (int)($event['sold'] ?? 0) ?>" min="0" style="width:100%;padding:10px 12px;border:1px solid var(--line);border-radius:8px;background:#fff;">
  </div>
</div>

<div style="margin-bottom:16px;">
  <label for="description" style="display:block;font-size:13px;font-weight:700;color:var(--navy);margin-bottom:6px;">Deskripsi Lengkap</label>
  <textarea name="description" id="description" rows="4" placeholder="Jelaskan mengenai event, bintang tamu, dan ketentuan tiket..." style="width:100%;padding:10px 12px;border:1px solid var(--line);border-radius:8px;background:#fff;font-family:inherit;line-height:1.5;"><?= e($event['description'] ?? '') ?></textarea>
</div>

<!-- Upload & Preview Foto -->
<div style="margin-bottom:28px;background:#F9FBFE;border:1px solid var(--line);border-radius:12px;padding:20px;">
  <label style="display:block;font-size:13px;font-weight:700;color:var(--navy);margin-bottom:6px;">Foto Poster Event (Max 2 MB, JPG/PNG/WebP)</label>
  <div style="display:flex;align-items:center;gap:20px;">
    <div style="width:96px;height:96px;border-radius:10px;overflow:hidden;background:#E8F0F8;border:1px solid var(--line);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
      <?php if (!empty($event['photo']) && poster_url($event['photo'])): ?>
        <img src="<?= e(poster_url($event['photo'])) ?>" alt="Preview" style="width:100%;height:100%;object-fit:cover;">
      <?php else: ?>
        <span style="font-size:12px;color:var(--muted);text-align:center;padding:8px;">Tanpa Foto</span>
      <?php endif; ?>
    </div>
    <div style="flex:1;">
      <input type="file" name="photo" id="photo" accept="image/jpeg,image/png,image/webp" style="display:block;margin-bottom:8px;">
      <small style="color:var(--muted);display:block;">Biarkan kosong jika tidak ingin mengubah foto yang sudah ada.</small>
      <?php if (!empty($event['photo'])): ?>
        <label style="display:inline-flex;align-items:center;gap:6px;font-size:13px;color:#C5221F;cursor:pointer;margin-top:6px;">
          <input type="checkbox" name="remove_photo" value="1">
          Hapus foto yang saat ini terpasang
        </label>
      <?php endif; ?>
    </div>
  </div>
</div>
