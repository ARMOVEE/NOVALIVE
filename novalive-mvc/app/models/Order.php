<?php
/**
 * Model Order
 * Mengelola transaksi pesanan tiket, status verifikasi, dan statistik pendapatan
 */

class Order extends Model {
    protected string $table = 'orders';

    /**
     * Mengambil detail satu pesanan beserta informasi event terkait
     */
    public function find($id, bool $includeDeleted = false) {
        $sql = "
            SELECT o.*, 
                   e.title AS event_title, e.code AS event_code, e.event_date, e.venue, e.price AS unit_price
            FROM orders o
            JOIN events e ON o.event_id = e.id
            WHERE o.id = :id
            LIMIT 1
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Paginasi pesanan tiket dengan filter status dan kata kunci pencarian
     */
    public function paginate(array $filters = [], int $page = 1, int $perPage = 10): array {
        $page = max(1, $page);
        $perPage = max(1, min(100, $perPage));
        $offset = ($page - 1) * $perPage;

        $conditions = ["1=1"];
        $params = [];

        if (!empty($filters['status']) && in_array($filters['status'], ['Terkirim', 'Menunggu', 'Selesai', 'Dibatalkan'], true)) {
            $conditions[] = "o.status = :status";
            $params[':status'] = $filters['status'];
        }

        if (!empty($filters['search'])) {
            $conditions[] = "(o.invoice LIKE :search OR o.buyer_name LIKE :search OR o.buyer_email LIKE :search OR e.title LIKE :search)";
            $params[':search'] = '%' . trim($filters['search']) . '%';
        }

        $whereClause = implode(" AND ", $conditions);

        // Hitung total data
        $countSql = "
            SELECT COUNT(o.id)
            FROM orders o
            JOIN events e ON o.event_id = e.id
            WHERE {$whereClause}
        ";
        $countStmt = $this->db->prepare($countSql);
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();

        // Ambil data halaman aktif
        $dataSql = "
            SELECT o.*, 
                   e.title AS event_title, e.code AS event_code, e.event_date, e.venue
            FROM orders o
            JOIN events e ON o.event_id = e.id
            WHERE {$whereClause}
            ORDER BY o.id DESC
            LIMIT {$perPage} OFFSET {$offset}
        ";
        $dataStmt = $this->db->prepare($dataSql);
        $dataStmt->execute($params);
        $data = $dataStmt->fetchAll();

        return [
            'data'        => $data,
            'total'       => $total,
            'perPage'     => $perPage,
            'currentPage' => $page,
            'totalPages'  => (int)ceil($total / $perPage)
        ];
    }

    /**
     * Mengambil daftar pesanan tiket terbaru untuk dashboard
     */
    public function recent(int $limit = 5): array {
        $sql = "
            SELECT o.*, 
                   e.title AS event_title, e.code AS event_code
            FROM orders o
            JOIN events e ON o.event_id = e.id
            ORDER BY o.id DESC
            LIMIT :limit
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Menghitung rincian jumlah pesanan berdasarkan status
     */
    public function countsByStatus(): array {
        $sql = "
            SELECT status, COUNT(*) AS count
            FROM orders
            GROUP BY status
        ";
        $stmt = $this->db->query($sql);
        $rows = $stmt->fetchAll();

        $counts = [
            'Semua'      => 0,
            'Terkirim'   => 0,
            'Menunggu'   => 0,
            'Selesai'    => 0,
            'Dibatalkan' => 0
        ];

        foreach ($rows as $row) {
            $st = $row['status'];
            $c = (int)$row['count'];
            $counts[$st] = $c;
            $counts['Semua'] += $c;
        }

        return $counts;
    }

    /**
     * Menghitung total omset / pendapatan dari pesanan yang sukses ('Terkirim' & 'Selesai')
     */
    public function totalRevenue(): float {
        $sql = "
            SELECT COALESCE(SUM(total_price), 0)
            FROM orders
            WHERE status IN ('Terkirim', 'Selesai')
        ";
        return (float)$this->db->query($sql)->fetchColumn();
    }

    /**
     * Memperbarui status pesanan tiket
     */
    public function updateStatus(int $id, string $status): bool {
        $valid = ['Terkirim', 'Menunggu', 'Selesai', 'Dibatalkan'];
        if (!in_array($status, $valid, true)) {
            return false;
        }

        $stmt = $this->db->prepare("
            UPDATE orders
            SET status = :status
            WHERE id = :id
        ");
        return $stmt->execute([
            ':id'     => $id,
            ':status' => $status
        ]);
    }

    /**
     * Cari pesanan berdasarkan kode tiket (NVL-...)
     */
    public function findByTicketCode(string $code): ?array {
        $sql = "
            SELECT o.*, 
                   e.id AS event_id, e.title AS event_title, e.code AS event_code, 
                   e.event_date, e.venue, e.price AS unit_price
            FROM orders o
            JOIN events e ON o.event_id = e.id
            WHERE o.ticket_code = :code
            LIMIT 1
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':code' => trim($code)]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    /**
     * Tandai pesanan sudah check-in
     */
    public function markCheckedIn(int $id): bool {
        $stmt = $this->db->prepare("UPDATE orders SET checked_in_at = NOW() WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Ambil riwayat check-in hari ini
     */
    public function getTodayCheckins(int $eventId = 0): array {
        $sql = "
            SELECT o.*, 
                   e.title AS event_title, e.event_date, e.venue
            FROM orders o
            JOIN events e ON o.event_id = e.id
            WHERE o.checked_in_at IS NOT NULL
              AND DATE(o.checked_in_at) = CURDATE()
        ";
        $params = [];
        if ($eventId > 0) {
            $sql .= " AND o.event_id = :eventId";
            $params[':eventId'] = $eventId;
        }
        $sql .= " ORDER BY o.checked_in_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Statistik check-in tiket per event atau keseluruhan
     */
    public function getCheckinStats(int $eventId = 0): array {
        $sql = "
            SELECT 
                COALESCE(SUM(CASE WHEN checked_in_at IS NOT NULL THEN qty ELSE 0 END), 0) AS checked_in,
                COALESCE(SUM(qty), 0) AS total_tickets
            FROM orders
            WHERE status IN ('Terkirim', 'Selesai')
        ";
        $params = [];
        if ($eventId > 0) {
            $sql .= " AND event_id = :eventId";
            $params[':eventId'] = $eventId;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch() ?: ['checked_in' => 0, 'total_tickets' => 0];
        $checkedIn = (int)$row['checked_in'];
        $total = (int)$row['total_tickets'];
        $percentage = $total > 0 ? round(($checkedIn / $total) * 100, 1) : 0;
        return [
            'checked_in'    => $checkedIn,
            'total_tickets' => $total,
            'percentage'    => $percentage
        ];
    }

    /**
     * Daftar pesanan yang memiliki e-tiket (status Terkirim / Selesai)
     */
    public function tickets(array $filters = [], int $page = 1, int $perPage = 15): array {
        $page = max(1, $page);
        $perPage = max(1, min(100, $perPage));
        $offset = ($page - 1) * $perPage;

        $conditions = [
            "o.ticket_code IS NOT NULL",
            "o.ticket_code != ''",
            "o.status IN ('Terkirim', 'Selesai')"
        ];
        $params = [];

        if (!empty($filters['search'])) {
            $conditions[] = "(o.invoice LIKE :search OR o.buyer_name LIKE :search OR o.buyer_email LIKE :search OR o.ticket_code LIKE :search OR e.title LIKE :search)";
            $params[':search'] = '%' . trim($filters['search']) . '%';
        }

        if (!empty($filters['event_id'])) {
            $conditions[] = "o.event_id = :event_id";
            $params[':event_id'] = (int)$filters['event_id'];
        }

        $whereClause = implode(" AND ", $conditions);

        $countSql = "
            SELECT COUNT(o.id)
            FROM orders o
            JOIN events e ON o.event_id = e.id
            WHERE {$whereClause}
        ";
        $countStmt = $this->db->prepare($countSql);
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();

        $dataSql = "
            SELECT o.*, 
                   e.title AS event_title, e.code AS event_code, e.event_date, e.venue
            FROM orders o
            JOIN events e ON o.event_id = e.id
            WHERE {$whereClause}
            ORDER BY o.id DESC
            LIMIT {$perPage} OFFSET {$offset}
        ";
        $dataStmt = $this->db->prepare($dataSql);
        $dataStmt->execute($params);
        $data = $dataStmt->fetchAll();

        return [
            'data'        => $data,
            'total'       => $total,
            'perPage'     => $perPage,
            'currentPage' => $page,
            'totalPages'  => (int)ceil($total / $perPage)
        ];
    }

    /**
     * Pendapatan per bulan (N bulan terakhir) dari orders Terkirim/Selesai
     */
    public function getMonthlyRevenue(int $months = 6): array {
        $result = [];
        // Buat daftar N bulan terakhir (YYYY-MM)
        for ($i = $months - 1; $i >= 0; $i--) {
            $monthKey = date('Y-m', strtotime("-$i month"));
            $monthLabel = date('M Y', strtotime("-$i month"));
            $result[$monthKey] = [
                'key' => $monthKey,
                'label' => $monthLabel,
                'total' => 0.0,
                'orders_count' => 0,
                'tickets_sold' => 0
            ];
        }

        $sql = "
            SELECT DATE_FORMAT(created_at, '%Y-%m') AS ym,
                   SUM(total_price) AS total_revenue,
                   COUNT(id) AS orders_count,
                   SUM(qty) AS tickets_sold
            FROM orders
            WHERE status IN ('Terkirim', 'Selesai')
              AND created_at >= DATE_SUB(DATE_FORMAT(NOW(), '%Y-%m-01'), INTERVAL :months MONTH)
            GROUP BY DATE_FORMAT(created_at, '%Y-%m')
            ORDER BY ym ASC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':months', $months - 1, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll();

        foreach ($rows as $row) {
            $k = $row['ym'];
            if (isset($result[$k])) {
                $result[$k]['total'] = (float)$row['total_revenue'];
                $result[$k]['orders_count'] = (int)$row['orders_count'];
                $result[$k]['tickets_sold'] = (int)$row['tickets_sold'];
            }
        }

        return array_values($result);
    }

    /**
     * Tabel Pendapatan per Event (tiket terjual, pendapatan kotor, refund, pendapatan bersih)
     */
    public function getRevenuePerEvent(): array {
        $sql = "
            SELECT e.id, e.title, e.code, e.price, e.quota, e.sold,
                   COALESCE(SUM(CASE WHEN o.status IN ('Terkirim', 'Selesai') THEN o.total_price ELSE 0 END), 0) AS gross_revenue,
                   COALESCE(SUM(CASE WHEN o.status IN ('Terkirim', 'Selesai') THEN o.qty ELSE 0 END), 0) AS actual_sold,
                   COALESCE((
                       SELECT SUM(r.amount) 
                       FROM refunds r 
                       JOIN orders o2 ON r.order_id = o2.id 
                       WHERE o2.event_id = e.id AND r.status = 'Disetujui'
                   ), 0) AS refund_amount
            FROM events e
            LEFT JOIN orders o ON e.id = o.event_id
            WHERE e.deleted_at IS NULL
            GROUP BY e.id
            ORDER BY gross_revenue DESC, e.sold DESC
        ";
        $rows = $this->db->query($sql)->fetchAll();

        foreach ($rows as &$r) {
            $r['gross_revenue'] = (float)$r['gross_revenue'];
            $r['refund_amount'] = (float)$r['refund_amount'];
            $r['net_revenue'] = max(0.0, $r['gross_revenue'] - $r['refund_amount']);
            $r['sold'] = max((int)$r['sold'], (int)$r['actual_sold']);
        }
        unset($r);

        return $rows;
    }

    /**
     * Analitik: Tiket terjual dan pendapatan per kategori
     */
    public function getSalesByCategory(string $startDate = '', string $endDate = ''): array {
        $where = ["e.deleted_at IS NULL"];
        $params = [];

        if (!empty($startDate)) {
            $where[] = "DATE(o.created_at) >= :sdate";
            $params[':sdate'] = $startDate;
        }
        if (!empty($endDate)) {
            $where[] = "DATE(o.created_at) <= :edate";
            $params[':edate'] = $endDate;
        }

        $whereClause = implode(" AND ", $where);

        $sql = "
            SELECT c.id, c.name, c.color, c.icon,
                   COALESCE(SUM(CASE WHEN o.status IN ('Terkirim', 'Selesai') THEN o.qty ELSE 0 END), 0) AS tickets_sold,
                   COALESCE(SUM(CASE WHEN o.status IN ('Terkirim', 'Selesai') THEN o.total_price ELSE 0 END), 0) AS total_revenue
            FROM categories c
            LEFT JOIN events e ON c.id = e.category_id AND e.deleted_at IS NULL
            LEFT JOIN orders o ON e.id = o.event_id AND {$whereClause}
            WHERE c.deleted_at IS NULL
            GROUP BY c.id
            ORDER BY tickets_sold DESC, total_revenue DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Analitik: Tiket terjual dan pendapatan per kota
     */
    public function getSalesByCity(string $startDate = '', string $endDate = ''): array {
        $where = ["e.deleted_at IS NULL"];
        $params = [];

        if (!empty($startDate)) {
            $where[] = "DATE(o.created_at) >= :sdate";
            $params[':sdate'] = $startDate;
        }
        if (!empty($endDate)) {
            $where[] = "DATE(o.created_at) <= :edate";
            $params[':edate'] = $endDate;
        }

        $whereClause = implode(" AND ", $where);

        $sql = "
            SELECT ci.id, ci.name, ci.accent,
                   COALESCE(SUM(CASE WHEN o.status IN ('Terkirim', 'Selesai') THEN o.qty ELSE 0 END), 0) AS tickets_sold,
                   COALESCE(SUM(CASE WHEN o.status IN ('Terkirim', 'Selesai') THEN o.total_price ELSE 0 END), 0) AS total_revenue
            FROM cities ci
            LEFT JOIN events e ON ci.id = e.city_id AND e.deleted_at IS NULL
            LEFT JOIN orders o ON e.id = o.event_id AND {$whereClause}
            WHERE ci.deleted_at IS NULL
            GROUP BY ci.id
            ORDER BY tickets_sold DESC, total_revenue DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Analitik: Distribusi status pesanan (Menunggu, Terkirim, Selesai, Dibatalkan)
     */
    public function getOrderStatusBreakdown(string $startDate = '', string $endDate = ''): array {
        $where = ["1=1"];
        $params = [];

        if (!empty($startDate)) {
            $where[] = "DATE(created_at) >= :sdate";
            $params[':sdate'] = $startDate;
        }
        if (!empty($endDate)) {
            $where[] = "DATE(created_at) <= :edate";
            $params[':edate'] = $endDate;
        }

        $whereClause = implode(" AND ", $where);

        $sql = "
            SELECT status, COUNT(id) AS total_count, COALESCE(SUM(total_price), 0) AS total_amount
            FROM orders
            WHERE {$whereClause}
            GROUP BY status
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll();

        $statuses = [
            'Terkirim' => ['count' => 0, 'amount' => 0.0],
            'Selesai' => ['count' => 0, 'amount' => 0.0],
            'Menunggu' => ['count' => 0, 'amount' => 0.0],
            'Dibatalkan' => ['count' => 0, 'amount' => 0.0]
        ];

        foreach ($rows as $r) {
            $st = $r['status'];
            if (isset($statuses[$st])) {
                $statuses[$st]['count'] = (int)$r['total_count'];
                $statuses[$st]['amount'] = (float)$r['total_amount'];
            }
        }

        return $statuses;
    }

    /**
     * Analitik: Tingkat keterisian tiket event (sold / quota)
     */
    public function getEventOccupancyRates(): array {
        $sql = "
            SELECT id, code, title, quota, sold, price,
                   CASE WHEN quota > 0 THEN ROUND((sold / quota) * 100, 1) ELSE 0 END AS occupancy_pct
            FROM events
            WHERE deleted_at IS NULL
            ORDER BY occupancy_pct DESC, sold DESC
        ";
        return $this->db->query($sql)->fetchAll();
    }
}
