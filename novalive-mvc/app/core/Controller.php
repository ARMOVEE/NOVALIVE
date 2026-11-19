<?php
/**
 * Base Controller
 * Menyediakan metode pembantu untuk memuat View, Model, redirect, flash message, otorisasi, dan audit log
 */

abstract class Controller {
    /**
     * Merender tampilan (View)
     */
    public function view(string $view, array $data = [], bool $layout = true): void {
        $shared = view_shared_data();
        extract($shared);
        extract($data);

        $viewPath = ROOT_PATH . '/app/views/' . $view . '.php';

        if (!file_exists($viewPath)) {
            Security::abort(500, "View [{$view}] tidak ditemukan pada sistem.");
        }

        if ($layout) {
            $headerPath = ROOT_PATH . '/app/views/layouts/header.php';
            $footerPath = ROOT_PATH . '/app/views/layouts/footer.php';

            if (file_exists($headerPath)) {
                require $headerPath;
            }
            require $viewPath;
            if (file_exists($footerPath)) {
                require $footerPath;
            }
        } else {
            require $viewPath;
        }
    }

    /**
     * Memvalidasi CSRF token pada controller
     */
    protected function validateCsrf(): void {
        Security::requireCsrf();
    }

    /**
     * Memuat objek Model
     */
    public function model(string $model): Model {
        $modelPath = ROOT_PATH . '/app/models/' . $model . '.php';
        if (file_exists($modelPath)) {
            require_once $modelPath;
        }

        if (!class_exists($model)) {
            Security::abort(500, "Model [{$model}] tidak ditemukan.");
        }

        return new $model();
    }

    /**
     * Mengalihkan halaman ke URL lain
     */
    public function redirect(string $url): void {
        if (!str_starts_with($url, 'http://') && !str_starts_with($url, 'https://')) {
            $url = base_url($url);
        }
        header('Location: ' . $url);
        exit;
    }

    /**
     * Menyimpan pesan flash pada sesi
     */
    public function flash(string $key, string $message, string $type = 'success'): void {
        $_SESSION['flash'][$key] = $message;
        $_SESSION['flash']['type'] = $type;
    }

    /**
     * Mengharuskan pengguna sudah login
     */
    public function requireLogin(): void {
        Middleware::auth();
    }

    /**
     * Memvalidasi hak akses RBAC pengguna
     */
    public function authorize(string $permission): void {
        Middleware::authorize($permission);
    }

    /**
     * Mencatat audit trail ke tabel activity_logs (aman dari kegagalan proses utama)
     */
    public function logActivity(string $action, ?string $entity = null, ?int $entityId = null, ?string $desc = null): void {
        try {
            $activityModel = $this->model('ActivityLog');
            if (method_exists($activityModel, 'record')) {
                $activityModel->record($action, $entity, $entityId, $desc);
            }
        } catch (\Throwable $e) {
            // Kegagalan pencatatan log tidak boleh membatalkan aksi bisnis pengguna
            Logger::error('Gagal mencatat activity log: ' . $e->getMessage(), [
                'action' => $action,
                'entity' => $entity,
                'entity_id' => $entityId
            ]);
        }
    }

    /**
     * Mengirimkan respons JSON
     */
    public function json($data, int $code = 200): void {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        header('Access-Control-Allow-Origin: ' . API_ALLOWED_ORIGIN);
        echo json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        exit;
    }
}
