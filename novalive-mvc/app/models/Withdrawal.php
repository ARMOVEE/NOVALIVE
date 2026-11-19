<?php
/**
 * Model Withdrawal (Penarikan Dana Penyelenggara)
 * NOVA LIVE MVC — Tahap 3
 */

class Withdrawal extends Model {
    protected string $table = 'withdrawals';

    public const SERVICE_FEE_PERCENT = 0.05; // 5% Biaya Layanan

    /**
     * Ambil seluruh riwayat penarikan dana dengan informasi user pembuat
     */
    public function allWithUser(): array {
        $sql = "SELECT w.*, u.full_name AS creator_name, u.email AS creator_email 
                FROM {$this->table} w 
                LEFT JOIN users u ON w.created_by = u.id 
                ORDER BY w.created_at DESC";
        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Hitung total pendapatan kotor dari pesanan yang sukses (Terkirim / Selesai)
     */
    public function getGrossRevenue(): float {
        $stmt = $this->db->query("SELECT COALESCE(SUM(total_price), 0) FROM orders WHERE status IN ('Terkirim', 'Selesai')");
        return (float)$stmt->fetchColumn();
    }

    /**
     * Hitung total refund yang disetujui
     */
    public function getApprovedRefunds(): float {
        $stmt = $this->db->query("SELECT COALESCE(SUM(amount), 0) FROM refunds WHERE status = 'Disetujui'");
        return (float)$stmt->fetchColumn();
    }

    /**
     * Hitung biaya layanan (5% dari pendapatan kotor)
     */
    public function getServiceFee(): float {
        return round($this->getGrossRevenue() * self::SERVICE_FEE_PERCENT, 2);
    }

    /**
     * Total dana yang sudah ditarik atau sedang diproses
     */
    public function getTotalWithdrawn(): float {
        $stmt = $this->db->query("SELECT COALESCE(SUM(amount), 0) FROM {$this->table} WHERE status IN ('Diproses', 'Berhasil')");
        return (float)$stmt->fetchColumn();
    }

    /**
     * Total dana yang berhasil dicairkan (status Berhasil)
     */
    public function getTotalSuccessful(): float {
        $stmt = $this->db->query("SELECT COALESCE(SUM(amount), 0) FROM {$this->table} WHERE status = 'Berhasil'");
        return (float)$stmt->fetchColumn();
    }

    /**
     * Total dana yang masih menunggu pencairan (status Diproses)
     */
    public function getTotalPending(): float {
        $stmt = $this->db->query("SELECT COALESCE(SUM(amount), 0) FROM {$this->table} WHERE status = 'Diproses'");
        return (float)$stmt->fetchColumn();
    }

    /**
     * Hitung Saldo Tersedia yang bisa ditarik
     * Formula: Kotor - Refund Disetujui - Biaya Layanan (5%) - (Withdrawal Diproses + Berhasil)
     */
    public function getAvailableBalance(): float {
        $gross = $this->getGrossRevenue();
        $refund = $this->getApprovedRefunds();
        $fee = $this->getServiceFee();
        $withdrawn = $this->getTotalWithdrawn();

        $balance = $gross - $refund - $fee - $withdrawn;
        return max(0.0, (float)$balance);
    }

    /**
     * Buat pengajuan penarikan dana baru
     */
    public function requestWithdraw(float $amount, string $bankName, string $accountNo, string $accountName, ?int $userId = null, ?string $note = null): int {
        return $this->create([
            'amount' => $amount,
            'bank_name' => $bankName,
            'account_no' => $accountNo,
            'account_name' => $accountName,
            'status' => 'Diproses',
            'note' => $note,
            'created_by' => $userId,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Update status penarikan dana (Diproses / Berhasil / Ditolak)
     */
    public function updateStatus(int $id, string $status, ?string $note = null): bool {
        $data = ['status' => $status];
        if ($note !== null) {
            $data['note'] = $note;
        }
        return $this->update($id, $data);
    }
}
