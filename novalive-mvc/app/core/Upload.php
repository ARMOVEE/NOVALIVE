<?php
/**
 * Upload Helper
 * Menangani upload berkas gambar secara aman dengan verifikasi konten biner (MIME + getimagesize)
 */

class Upload {
    protected static array $allowedMimes = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp'
    ];

    /**
     * Memproses upload berkas gambar
     * Mengembalikan string path relatif ('uploads/random.jpg' atau 'uploads/banners/random.jpg') atau null jika tidak ada berkas yang diunggah
     * Melempar Exception jika berkas diunggah namun tidak valid
     */
    public static function image(string $field = 'photo', string $subfolder = ''): ?string {
        if (!isset($_FILES[$field])) {
            return null;
        }

        $file = $_FILES[$field];

        // Tidak ada berkas yang dipilih pengguna
        if ($file['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Gagal mengunggah berkas. Kode kesalahan: ' . $file['error']);
        }

        // Cek ukuran berkas (maksimal 2 MB)
        if ($file['size'] > UPLOAD_MAX_BYTES) {
            $maxMb = UPLOAD_MAX_BYTES / (1024 * 1024);
            throw new Exception("Ukuran berkas melebihi batas maksimal {$maxMb} MB.");
        }

        $tmpPath = $file['tmp_name'];

        // Cek MIME dari isi berkas (bukan dari $_FILES['type'])
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $tmpPath);
        finfo_close($finfo);

        if (!isset(self::$allowedMimes[$mime])) {
            throw new Exception('Format berkas tidak didukung. Hanya menerima format JPG, PNG, atau WebP.');
        }

        // Verifikasi integritas gambar dengan getimagesize
        $imageInfo = @getimagesize($tmpPath);
        if ($imageInfo === false) {
            throw new Exception('Berkas yang diunggah bukan berkas gambar yang valid.');
        }

        $extension = self::$allowedMimes[$mime];
        $randomName = bin2hex(random_bytes(16)) . '.' . $extension;

        $sub = trim($subfolder, '/\\');
        $targetDir = ROOT_PATH . '/public/uploads' . ($sub !== '' ? '/' . $sub : '');
        if (!is_dir($targetDir)) {
            @mkdir($targetDir, 0755, true);
        }

        $destination = $targetDir . '/' . $randomName;

        if (!move_uploaded_file($tmpPath, $destination)) {
            throw new Exception('Gagal menyimpan berkas yang diunggah ke server.');
        }

        return 'uploads/' . ($sub !== '' ? $sub . '/' : '') . $randomName;
    }

    /**
     * Menghapus berkas gambar lama di public/uploads jika ada
     * Tidak akan pernah menghapus berkas default di folder assets/
     */
    public static function delete(?string $path): void {
        if (empty($path)) {
            return;
        }

        // Pastikan hanya berkas di dalam uploads/ yang dapat dihapus
        if (str_starts_with($path, 'uploads/') && !str_contains($path, '..')) {
            $fullPath = ROOT_PATH . '/public/' . $path;
            if (is_file($fullPath)) {
                @unlink($fullPath);
            }
        }
    }
}
