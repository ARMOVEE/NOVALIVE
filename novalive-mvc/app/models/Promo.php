<?php
/**
 * Model Promo
 * Mengelola kode promo tiket event, kuota penggunaan, validasi diskon, dan status otomatis
 */

class Promo extends Model {
    protected string $table = 'promo_codes';

    /**
     * Mengambil seluruh kode promo beserta judul event terkait jika dibatasi
     */
    public function allPromos(): array {
        $sql = "
            SELECT p.*, e.title AS event_title
            FROM {$this->table} p
            LEFT JOIN events e ON p.event_id = e.id
            ORDER BY p.id DESC
        ";
        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Mencari kode promo berdasarkan kode (case-insensitive)
     */
    public function findByCode(string $code): ?array {
        $clean = strtoupper(trim($code));
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE UPPER(code) = :code LIMIT 1");
        $stmt->execute([':code' => $clean]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /**
     * Memeriksa apakah kode promo sudah terdaftar
     */
    public function codeExists(string $code, int $excludeId = 0): bool {
        $clean = strtoupper(trim($code));
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE UPPER(code) = :code";
        $params = [':code' => $clean];

        if ($excludeId > 0) {
            $sql .= " AND id != :excludeId";
            $params[':excludeId'] = $excludeId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return ((int)$stmt->fetchColumn()) > 0;
    }

    /**
     * Menghitung status otomatis kode promo
     * Nilai: 'Aktif', 'Terjadwal', 'Berakhir', 'Habis', atau 'Nonaktif'
     */
    public function calculateStatus(array $promo): string {
        if (empty($promo['is_active'])) {
            return 'Nonaktif';
        }

        if ($promo['quota'] !== null && (int)$promo['used'] >= (int)$promo['quota']) {
            return 'Habis';
        }

        $today = date('Y-m-d');
        if (!empty($promo['starts_at']) && $today < $promo['starts_at']) {
            return 'Terjadwal';
        }

        if (!empty($promo['ends_at']) && $today > $promo['ends_at']) {
            return 'Berakhir';
        }

        return 'Aktif';
    }

    /**
     * Mengubah status aktif / nonaktif kode promo
     */
    public function toggleActive(int $id): bool {
        $promo = $this->find($id);
        if (!$promo) {
            return false;
        }

        $newStatus = empty($promo['is_active']) ? 1 : 0;
        return $this->update($id, ['is_active' => $newStatus]);
    }

    /**
     * Menambah hitungan penggunaan promo sebanyak 1 kali
     */
    public function incrementUsed(int $id): bool {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET used = used + 1 WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Validasi kode promo untuk transaksi checkout
     * Mengembalikan array struktur ['valid' => bool, 'discount' => float, 'error' => ?string, 'promo' => ?array]
     */
    public function validate(string $code, ?int $eventId = null, float $subtotal = 0.0): array {
        $cleanCode = strtoupper(trim($code));
        if ($cleanCode === '') {
            return [
                'valid'    => false,
                'discount' => 0.0,
                'error'    => 'Silakan masukkan kode promo.',
                'promo'    => null
            ];
        }

        $promo = $this->findByCode($cleanCode);
        if (!$promo) {
            return [
                'valid'    => false,
                'discount' => 0.0,
                'error'    => "Kode promo '{$cleanCode}' tidak ditemukan.",
                'promo'    => null
            ];
        }

        // Cek status aktif
        if (empty($promo['is_active'])) {
            return [
                'valid'    => false,
                'discount' => 0.0,
                'error'    => "Kode promo '{$cleanCode}' sedang dinonaktifkan.",
                'promo'    => $promo
            ];
        }

        $today = date('Y-m-d');

        // Cek tanggal mulai
        if (!empty($promo['starts_at']) && $today < $promo['starts_at']) {
            return [
                'valid'    => false,
                'discount' => 0.0,
                'error'    => "Kode promo '{$cleanCode}' belum dapat digunakan hingga " . tanggal($promo['starts_at']) . ".",
                'promo'    => $promo
            ];
        }

        // Cek tanggal berakhir
        if (!empty($promo['ends_at']) && $today > $promo['ends_at']) {
            return [
                'valid'    => false,
                'discount' => 0.0,
                'error'    => "Kode promo '{$cleanCode}' telah kedaluwarsa pada " . tanggal($promo['ends_at']) . ".",
                'promo'    => $promo
            ];
        }

        // Cek kuota
        if ($promo['quota'] !== null && (int)$promo['used'] >= (int)$promo['quota']) {
            return [
                'valid'    => false,
                'discount' => 0.0,
                'error'    => "Kuota penggunaan kode promo '{$cleanCode}' telah habis.",
                'promo'    => $promo
            ];
        }

        // Cek pembatasan event
        if ($promo['event_id'] !== null && $eventId !== null && (int)$promo['event_id'] !== (int)$eventId) {
            return [
                'valid'    => false,
                'discount' => 0.0,
                'error'    => "Kode promo '{$cleanCode}' hanya berlaku untuk event tertentu.",
                'promo'    => $promo
            ];
        }

        // Hitung nominal potongan diskon
        $discount = 0.0;
        if ($promo['type'] === 'persen') {
            $discount = ($subtotal * (float)$promo['value']) / 100.0;
        } else {
            $discount = (float)$promo['value'];
        }

        // Diskon tidak boleh melebihi subtotal
        if ($discount > $subtotal && $subtotal > 0) {
            $discount = $subtotal;
        }

        return [
            'valid'    => true,
            'discount' => round($discount, 2),
            'error'    => null,
            'promo'    => $promo
        ];
    }
}

/**
 * Alias PromoModel untuk kompatibilitas checkout dan unit test
 */
class PromoModel extends Promo {}

