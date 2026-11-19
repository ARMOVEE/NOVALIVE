<?php
/**
 * Model Banner
 * Mengelola slide banner homepage, jadwal tayang, urutan, dan file gambar banner
 */

class Banner extends Model {
    protected string $table = 'banners';

    /**
     * Mengambil seluruh slide banner untuk dashboard, terurut sort_order ASC
     */
    public function allBanners(): array {
        $sql = "SELECT * FROM {$this->table} ORDER BY sort_order ASC, id ASC";
        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Mengambil slide banner aktif yang sedang masa tayang untuk homepage publik
     */
    public function allActive(): array {
        $sql = "
            SELECT * FROM {$this->table}
            WHERE is_active = 1
              AND (start_date IS NULL OR start_date <= CURDATE())
              AND (end_date IS NULL OR end_date >= CURDATE())
            ORDER BY sort_order ASC, id ASC
        ";
        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Menghapus slide banner beserta file gambarnya dari disk jika ada
     */
    public function deleteSlide(int $id): bool {
        $banner = $this->find($id);
        if (!$banner) {
            return false;
        }

        // Hapus file gambar fisik jika tersimpan
        if (!empty($banner['image'])) {
            $cleaned = ltrim($banner['image'], '/\\');
            $candidates = [
                ROOT_PATH . '/public/' . $cleaned,
                ROOT_PATH . '/public/uploads/banners/' . basename($cleaned),
            ];
            foreach ($candidates as $filePath) {
                if (file_exists($filePath) && is_file($filePath)) {
                    @unlink($filePath);
                }
            }
        }

        return $this->delete($id);
    }

    /**
     * Mengubah status aktif / nonaktif slide
     */
    public function toggleActive(int $id): bool {
        $banner = $this->find($id);
        if (!$banner) {
            return false;
        }

        $newStatus = empty($banner['is_active']) ? 1 : 0;
        return $this->update($id, ['is_active' => $newStatus]);
    }

    /**
     * Menukar urutan slide naik (up) atau turun (down)
     */
    public function reorder(int $id, string $direction): bool {
        $current = $this->find($id);
        if (!$current) {
            return false;
        }

        $all = $this->allBanners();
        $currentIndex = -1;
        foreach ($all as $idx => $b) {
            if ((int)$b['id'] === (int)$id) {
                $currentIndex = $idx;
                break;
            }
        }

        if ($currentIndex === -1) {
            return false;
        }

        $targetIndex = ($direction === 'up') ? $currentIndex - 1 : $currentIndex + 1;
        if ($targetIndex < 0 || $targetIndex >= count($all)) {
            return false; // Sudah di ujung
        }

        $target = $all[$targetIndex];

        // Tukar nilai sort_order jika sama atau berbeda
        $curOrder = (int)$current['sort_order'];
        $tgtOrder = (int)$target['sort_order'];
        if ($curOrder === $tgtOrder) {
            $curOrder = $currentIndex + 1;
            $tgtOrder = $targetIndex + 1;
        }

        $this->update($current['id'], ['sort_order' => $tgtOrder]);
        $this->update($target['id'], ['sort_order' => $curOrder]);

        return true;
    }

    /**
     * Mendapatkan nomor urutan berikutnya
     */
    public function getNextSortOrder(): int {
        $sql = "SELECT COALESCE(MAX(sort_order), 0) + 1 FROM {$this->table}";
        return (int)$this->db->query($sql)->fetchColumn();
    }
}
