<?php
/**
 * Middleware & RBAC (Role-Based Access Control)
 * Menegakkan otorisasi pengguna berdasarkan peran dan matriks izin
 */

class Middleware {
    /**
     * Matriks izin per peran pengguna
     */
    protected static array $permissions = [
        'admin' => ['*'],
        'manager' => [
            'dashboard.view',
            'events.view',
            'events.create',
            'events.update',
            'events.delete',
            'events.upload',
            'categories.view',
            'categories.manage',
            'orders.view',
            'orders.manage',
            'reports.view',
            'reports.export'
        ],
        'staff' => [
            'dashboard.view',
            'events.view',
            'categories.view',
            'orders.view',
            'reports.view'
        ]
    ];

    /**
     * Memeriksa apakah pengguna saat ini memiliki izin tertentu
     */
    public static function check(string $permission, ?array $user = null): bool {
        if ($user === null) {
            $user = $_SESSION['user'] ?? null;
        }

        if (!$user || empty($user['role'])) {
            return false;
        }

        $role = $user['role'];
        if (!isset(self::$permissions[$role])) {
            return false;
        }

        $userPerms = self::$permissions[$role];

        // Admin memiliki wildcard '*'
        if (in_array('*', $userPerms, true)) {
            return true;
        }

        return in_array($permission, $userPerms, true);
    }

    /**
     * Menegakkan izin: jika tidak memiliki izin, abort dengan status 403
     */
    public static function authorize(string $permission): void {
        if (!isset($_SESSION['user'])) {
            $_SESSION['flash']['error'] = 'Silakan login terlebih dahulu untuk mengakses halaman ini.';
            header('Location: ' . base_url('login'));
            exit;
        }

        if (!self::check($permission)) {
            Security::abort(403, 'Anda tidak memiliki hak akses untuk melakukan aksi ini.');
        }
    }

    /**
     * Memeriksa apakah pengguna sudah login, jika belum arahkan ke halaman login
     */
    public static function auth(): void {
        if (!isset($_SESSION['user'])) {
            $_SESSION['flash']['error'] = 'Silakan login terlebih dahulu untuk melanjutkan.';
            header('Location: ' . base_url('login'));
            exit;
        }
    }
}
