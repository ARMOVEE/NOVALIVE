<?php
/**
 * Model HomepageSection
 * Mengelola konfigurasi tata letak section homepage dan kurasi featured events
 */

class HomepageSection extends Model {
    protected string $table = 'homepage_sections';

    /**
     * Mengambil seluruh section homepage terurut sort_order ASC
     * Mengembalikan array dengan key asosiatif section_key
     */
    public function allSections(): array {
        $sql = "SELECT * FROM {$this->table} ORDER BY sort_order ASC, id ASC";
        $rows = $this->db->query($sql)->fetchAll();
        $sections = [];
        foreach ($rows as $row) {
            $sections[$row['section_key']] = $row;
        }
        return $sections;
    }

    /**
     * Mengambil konfigurasi section berdasarkan section_key
     */
    public function findByKey(string $sectionKey): ?array {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE section_key = :key LIMIT 1");
        $stmt->execute([':key' => $sectionKey]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /**
     * Memperbarui konfigurasi section berdasarkan section_key
     */
    public function updateSection(string $sectionKey, array $data): bool {
        $allowed = ['title', 'is_visible', 'sort_order', 'item_limit'];
        $setClauses = [];
        $params = [':key' => $sectionKey];

        foreach ($data as $col => $val) {
            if (in_array($col, $allowed, true)) {
                $setClauses[] = "{$col} = :{$col}";
                $params[':' . $col] = $val;
            }
        }

        if (empty($setClauses)) {
            return false;
        }

        $sql = "UPDATE {$this->table} SET " . implode(', ', $setClauses) . " WHERE section_key = :key";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Mengambil event terkurasi untuk section tertentu
     */
    public function getFeaturedEvents(string $sectionKey, int $limit = 0): array {
        $sql = "
            SELECT e.*, fe.sort_order AS featured_order,
                   c.name AS category_name, c.color AS category_color,
                   ci.name AS city_name, ci.slug AS city_slug
            FROM featured_events fe
            INNER JOIN events e ON fe.event_id = e.id
            LEFT JOIN categories c ON e.category_id = c.id
            LEFT JOIN cities ci ON e.city_id = ci.id
            WHERE fe.section_key = :key AND e.deleted_at IS NULL
            ORDER BY fe.sort_order ASC, fe.id ASC
        ";

        if ($limit > 0) {
            $sql .= " LIMIT " . (int)$limit;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':key' => $sectionKey]);
        return $stmt->fetchAll();
    }

    /**
     * Mengambil daftar ID event terkurasi saja
     */
    public function getFeaturedEventIds(string $sectionKey): array {
        $stmt = $this->db->prepare("SELECT event_id FROM featured_events WHERE section_key = :key ORDER BY sort_order ASC, id ASC");
        $stmt->execute([':key' => $sectionKey]);
        return array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));
    }

    /**
     * Memperbarui daftar featured events untuk section tertentu
     */
    public function setFeaturedEvents(string $sectionKey, array $eventIds): bool {
        // Hapus daftar lama
        $del = $this->db->prepare("DELETE FROM featured_events WHERE section_key = :key");
        $del->execute([':key' => $sectionKey]);

        if (empty($eventIds)) {
            return true;
        }

        $ins = $this->db->prepare("INSERT INTO featured_events (section_key, event_id, sort_order) VALUES (:key, :event_id, :sort_order)");
        $order = 1;
        foreach ($eventIds as $eventId) {
            $eid = (int)$eventId;
            if ($eid > 0) {
                $ins->execute([
                    ':key' => $sectionKey,
                    ':event_id' => $eid,
                    ':sort_order' => $order++
                ]);
            }
        }

        return true;
    }

    /**
     * Mengambil event untuk section, fallback otomatis ke default query jika kurasi kosong
     */
    public function getSectionEvents(string $sectionKey, int $limit = 0, ?Event $eventModel = null): array {
        $featured = $this->getFeaturedEvents($sectionKey, $limit);
        if (!empty($featured)) {
            return $featured;
        }

        if (!$eventModel) {
            $eventModel = new Event();
        }

        if ($sectionKey === 'event_seru') {
            return $eventModel->seru($limit ?: 10);
        }

        if ($sectionKey === 'event_populer') {
            return $eventModel->upcoming($limit ?: 4);
        }

        return [];
    }
}
