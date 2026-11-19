<?php
/**
 * Model Event
 * Mengelola katalog event, query join kategori & kota, paginasi terfilter, dan soft delete
 */

class Event extends Model {
    protected string $table = 'events';

    /**
     * Mengambil seluruh event aktif lengkap dengan relasi kategori dan kota
     */
    public function all(bool $includeDeleted = false): array {
        $sql = "
            SELECT e.*, 
                   c.name AS category_name, c.color AS category_color, c.icon AS category_icon,
                   ci.name AS city_name, ci.slug AS city_slug, ci.accent AS city_accent, ci.landmark AS city_landmark
            FROM events e
            LEFT JOIN categories c ON e.category_id = c.id
            LEFT JOIN cities ci ON e.city_id = ci.id
        ";

        if (!$includeDeleted) {
            $sql .= " WHERE e.deleted_at IS NULL";
        }

        $sql .= " ORDER BY e.event_date ASC, e.id DESC";

        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Mencari satu event berdasarkan ID lengkap dengan relasi
     */
    public function find($id, bool $includeDeleted = false) {
        $sql = "
            SELECT e.*, 
                   c.name AS category_name, c.color AS category_color, c.icon AS category_icon,
                   ci.name AS city_name, ci.slug AS city_slug, ci.accent AS city_accent, ci.landmark AS city_landmark
            FROM events e
            LEFT JOIN categories c ON e.category_id = c.id
            LEFT JOIN cities ci ON e.city_id = ci.id
            WHERE e.id = :id
        ";

        if (!$includeDeleted) {
            $sql .= " AND e.deleted_at IS NULL";
        }

        $sql .= " LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Paginasi event dengan dukungan filter pencarian, kategori, kota, rentang harga, dan pengurutan
     */
    public function paginate(array $filters = [], int $page = 1, int $perPage = 10): array {
        $page = max(1, $page);
        $perPage = max(1, min(100, $perPage));
        $offset = ($page - 1) * $perPage;

        $conditions = ["e.deleted_at IS NULL"];
        $params = [];

        // Filter kata kunci pencarian (judul, penyelenggara, lokasi)
        if (!empty($filters['search'])) {
            $conditions[] = "(e.title LIKE :search1 OR e.organizer LIKE :search2 OR e.venue LIKE :search3)";
            $searchVal = '%' . trim($filters['search']) . '%';
            $params[':search1'] = $searchVal;
            $params[':search2'] = $searchVal;
            $params[':search3'] = $searchVal;
        }

        // Filter kategori (ID atau nama)
        if (!empty($filters['category'])) {
            if (is_numeric($filters['category'])) {
                $conditions[] = "e.category_id = :category";
                $params[':category'] = (int)$filters['category'];
            } else {
                $conditions[] = "LOWER(c.name) = LOWER(:category)";
                $params[':category'] = trim($filters['category']);
            }
        }

        // Filter kota (ID, slug, atau nama)
        if (!empty($filters['city'])) {
            if (is_numeric($filters['city'])) {
                $conditions[] = "e.city_id = :city";
                $params[':city'] = (int)$filters['city'];
            } else {
                $conditions[] = "(LOWER(ci.slug) = :city_slug OR LOWER(ci.name) = :city_name OR LOWER(ci.search_alias) = :city_alias)";
                $cityVal = strtolower(trim($filters['city']));
                $params[':city_slug'] = $cityVal;
                $params[':city_name'] = $cityVal;
                $params[':city_alias'] = $cityVal;
            }
        }

        // Filter harga (gratis / berbayar)
        if (!empty($filters['price'])) {
            if ($filters['price'] === 'gratis') {
                $conditions[] = "e.price = 0";
            } elseif ($filters['price'] === 'berbayar') {
                $conditions[] = "e.price > 0";
            }
        }

        // Filter waktu (minggu-ini, minggu-depan, bulan-ini, bulan-depan)
        if (!empty($filters['time'])) {
            $today = new DateTime('today');
            $timeVal = strtolower(trim($filters['time']));
            if ($timeVal === 'minggu-ini') {
                $startOfWeek = clone $today;
                $dayOfWeek = (int)$today->format('N');
                $startOfWeek->modify('-' . ($dayOfWeek - 1) . ' days');
                $endOfWeek = clone $startOfWeek;
                $endOfWeek->modify('+6 days');
                $conditions[] = "e.event_date >= :time_start AND e.event_date <= :time_end";
                $params[':time_start'] = $startOfWeek->format('Y-m-d');
                $params[':time_end'] = $endOfWeek->format('Y-m-d');
            } elseif ($timeVal === 'minggu-depan') {
                $startOfWeek = clone $today;
                $dayOfWeek = (int)$today->format('N');
                $startOfWeek->modify('-' . ($dayOfWeek - 1) . ' days');
                $startOfNextWeek = clone $startOfWeek;
                $startOfNextWeek->modify('+7 days');
                $endOfNextWeek = clone $startOfNextWeek;
                $endOfNextWeek->modify('+6 days');
                $conditions[] = "e.event_date >= :time_start AND e.event_date <= :time_end";
                $params[':time_start'] = $startOfNextWeek->format('Y-m-d');
                $params[':time_end'] = $endOfNextWeek->format('Y-m-d');
            } elseif ($timeVal === 'bulan-ini') {
                $startOfMonth = clone $today;
                $startOfMonth->modify('first day of this month');
                $endOfMonth = clone $today;
                $endOfMonth->modify('last day of this month');
                $conditions[] = "e.event_date >= :time_start AND e.event_date <= :time_end";
                $params[':time_start'] = $startOfMonth->format('Y-m-d');
                $params[':time_end'] = $endOfMonth->format('Y-m-d');
            } elseif ($timeVal === 'bulan-depan') {
                $startOfNextMonth = clone $today;
                $startOfNextMonth->modify('first day of next month');
                $endOfNextMonth = clone $today;
                $endOfNextMonth->modify('last day of next month');
                $conditions[] = "e.event_date >= :time_start AND e.event_date <= :time_end";
                $params[':time_start'] = $startOfNextMonth->format('Y-m-d');
                $params[':time_end'] = $endOfNextMonth->format('Y-m-d');
            }
        }

        $whereClause = implode(" AND ", $conditions);

        // Pengurutan
        $orderBy = "e.event_date ASC, e.id DESC";
        if (!empty($filters['sort'])) {
            switch ($filters['sort']) {
                case 'priceAsc':
                    $orderBy = "e.price ASC, e.event_date ASC";
                    break;
                case 'priceDesc':
                    $orderBy = "e.price DESC, e.event_date ASC";
                    break;
                case 'name':
                case 'nameAsc':
                    $orderBy = "e.title ASC";
                    break;
                case 'dateDesc':
                    $orderBy = "e.event_date DESC, e.id DESC";
                    break;
                case 'dateAsc':
                case 'date':
                    $orderBy = "e.event_date ASC, e.id ASC";
                    break;
                case 'popular':
                    $orderBy = "e.sold DESC, e.quota DESC";
                    break;
            }
        }

        // Hitung total record yang cocok
        $countSql = "
            SELECT COUNT(e.id)
            FROM events e
            LEFT JOIN categories c ON e.category_id = c.id
            LEFT JOIN cities ci ON e.city_id = ci.id
            WHERE {$whereClause}
        ";
        $countStmt = $this->db->prepare($countSql);
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();

        // Ambil data halaman saat ini
        $dataSql = "
            SELECT e.*, 
                   c.name AS category_name, c.color AS category_color, c.icon AS category_icon,
                   ci.name AS city_name, ci.slug AS city_slug, ci.accent AS city_accent, ci.landmark AS city_landmark
            FROM events e
            LEFT JOIN categories c ON e.category_id = c.id
            LEFT JOIN cities ci ON e.city_id = ci.id
            WHERE {$whereClause}
            ORDER BY {$orderBy}
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
     * 10 event untuk section Event Seru di beranda
     */
    public function seru(int $limit = 10): array {
        $sql = "
            SELECT e.*, 
                   c.name AS category_name, c.color AS category_color,
                   ci.name AS city_name, ci.slug AS city_slug
            FROM events e
            LEFT JOIN categories c ON e.category_id = c.id
            LEFT JOIN cities ci ON e.city_id = ci.id
            WHERE e.deleted_at IS NULL
            ORDER BY e.event_date ASC, e.id ASC
            LIMIT :limit
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * 4 event terpopuler berdasarkan tiket terjual
     */
    public function popular(int $limit = 4): array {
        $sql = "
            SELECT e.*, 
                   c.name AS category_name, c.color AS category_color,
                   ci.name AS city_name, ci.slug AS city_slug
            FROM events e
            LEFT JOIN categories c ON e.category_id = c.id
            LEFT JOIN cities ci ON e.city_id = ci.id
            WHERE e.deleted_at IS NULL
            ORDER BY e.sold DESC, e.quota DESC
            LIMIT :limit
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Event berikutnya yang segera berlangsung (WHERE event_date >= CURDATE() AND deleted_at IS NULL)
     */
    public function upcoming(int $limit = 5): array {
        $sql = "
            SELECT e.*, 
                   c.name AS category_name, c.color AS category_color,
                   ci.name AS city_name, ci.slug AS city_slug
            FROM events e
            LEFT JOIN categories c ON e.category_id = c.id
            LEFT JOIN cities ci ON e.city_id = ci.id
            WHERE e.event_date >= CURDATE() AND e.deleted_at IS NULL
            ORDER BY e.event_date ASC, e.id ASC
            LIMIT :limit
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Mengambil event berdasarkan ID kota
     */
    public function byCity(int $cityId, int $limit = 20): array {
        $sql = "
            SELECT e.*, 
                   c.name AS category_name, c.color AS category_color,
                   ci.name AS city_name, ci.slug AS city_slug, ci.landmark AS city_landmark
            FROM events e
            LEFT JOIN categories c ON e.category_id = c.id
            LEFT JOIN cities ci ON e.city_id = ci.id
            WHERE e.city_id = :city_id AND e.deleted_at IS NULL
            ORDER BY e.event_date ASC
            LIMIT :limit
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':city_id', $cityId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Mengambil event dengan sisa tiket sedikit (kuota hampir habis)
     */
    public function lowTicketEvents(int $limit = 5): array {
        $sql = "
            SELECT e.*, (e.quota - e.sold) AS remaining
            FROM events e
            WHERE e.deleted_at IS NULL 
              AND e.quota > 0 
              AND (e.sold / e.quota) >= 0.70
            ORDER BY remaining ASC, e.event_date ASC
            LIMIT :limit
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Menghasilkan kode unik event baru (contoh: EVT011)
     */
    public function generateCode(): string {
        $sql = "SELECT code FROM events ORDER BY id DESC LIMIT 1";
        $lastCode = $this->db->query($sql)->fetchColumn();

        if ($lastCode && preg_match('/EVT(\d+)/', $lastCode, $m)) {
            $num = (int)$m[1] + 1;
        } else {
            $num = $this->count(true) + 1;
        }

        return sprintf('EVT%03d', $num);
    }

    /**
     * Menambahkan event baru
     */
    public function create(array $data): int {
        $code = !empty($data['code']) ? $data['code'] : $this->generateCode();

        $stmt = $this->db->prepare("
            INSERT INTO events (
                code, title, category_id, city_id, organizer,
                event_date, event_time, venue, price, quota,
                sold, photo, badge, description
            ) VALUES (
                :code, :title, :category_id, :city_id, :organizer,
                :event_date, :event_time, :venue, :price, :quota,
                :sold, :photo, :badge, :description
            )
        ");

        $stmt->execute([
            ':code'        => $code,
            ':title'       => $data['title'],
            ':category_id' => !empty($data['category_id']) ? (int)$data['category_id'] : null,
            ':city_id'     => !empty($data['city_id']) ? (int)$data['city_id'] : null,
            ':organizer'   => $data['organizer'],
            ':event_date'  => $data['event_date'],
            ':event_time'  => $data['event_time'] ?? null,
            ':venue'       => $data['venue'] ?? null,
            ':price'       => (float)($data['price'] ?? 0),
            ':quota'       => (int)($data['quota'] ?? 0),
            ':sold'        => (int)($data['sold'] ?? 0),
            ':photo'       => $data['photo'] ?? null,
            ':badge'       => !empty($data['badge']) ? $data['badge'] : null,
            ':description' => $data['description'] ?? null
        ]);

        return (int)$this->db->lastInsertId();
    }

    /**
     * Memperbarui data event
     */
    public function update($id, array $data): bool {
        $stmt = $this->db->prepare("
            UPDATE events SET
                title = :title,
                category_id = :category_id,
                city_id = :city_id,
                organizer = :organizer,
                event_date = :event_date,
                event_time = :event_time,
                venue = :venue,
                price = :price,
                quota = :quota,
                sold = :sold,
                photo = :photo,
                badge = :badge,
                description = :description
            WHERE id = :id
        ");

        return $stmt->execute([
            ':id'          => $id,
            ':title'       => $data['title'],
            ':category_id' => !empty($data['category_id']) ? (int)$data['category_id'] : null,
            ':city_id'     => !empty($data['city_id']) ? (int)$data['city_id'] : null,
            ':organizer'   => $data['organizer'],
            ':event_date'  => $data['event_date'],
            ':event_time'  => $data['event_time'] ?? null,
            ':venue'       => $data['venue'] ?? null,
            ':price'       => (float)($data['price'] ?? 0),
            ':quota'       => (int)($data['quota'] ?? 0),
            ':sold'        => (int)($data['sold'] ?? 0),
            ':photo'       => $data['photo'] ?? null,
            ':badge'       => !empty($data['badge']) ? $data['badge'] : null,
            ':description' => $data['description'] ?? null
        ]);
    }

    /**
     * Mengambil daftar event yang telah terhapus (soft delete)
     */
    public function trash(): array {
        $sql = "
            SELECT e.*, c.name AS category_name, ci.name AS city_name
            FROM events e
            LEFT JOIN categories c ON e.category_id = c.id
            LEFT JOIN cities ci ON e.city_id = ci.id
            WHERE e.deleted_at IS NOT NULL
            ORDER BY e.deleted_at DESC
        ";
        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Menghitung total event aktif
     */
    public function totalEvents(): int {
        return (int)$this->db->query("SELECT COUNT(*) FROM events WHERE deleted_at IS NULL")->fetchColumn();
    }

    /**
     * Menghitung total tiket yang telah terjual di seluruh event aktif
     */
    public function totalTicketsSold(): int {
        return (int)$this->db->query("SELECT COALESCE(SUM(sold), 0) FROM events WHERE deleted_at IS NULL")->fetchColumn();
    }

    /**
     * Mengambil daftar event aktif untuk manajemen stok tiket
     */
    public function allStock(): array {
        $sql = "
            SELECT e.*, c.name AS category_name, ci.name AS city_name
            FROM events e
            LEFT JOIN categories c ON e.category_id = c.id
            LEFT JOIN cities ci ON e.city_id = ci.id
            WHERE e.deleted_at IS NULL
            ORDER BY e.event_date ASC, e.id ASC
        ";
        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Memperbarui kuota tiket suatu event
     */
    public function updateQuota(int $id, int $quota): bool {
        $stmt = $this->db->prepare("
            UPDATE events
            SET quota = :quota
            WHERE id = :id AND deleted_at IS NULL
        ");
        return $stmt->execute([
            ':id'    => $id,
            ':quota' => $quota
        ]);
    }
}
