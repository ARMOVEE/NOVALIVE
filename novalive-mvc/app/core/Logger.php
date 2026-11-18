<?php
/**
 * Logger
 * Mencatat log error aplikasi ke file JSON harian di logs/errors/*.json
 */

class Logger {
    public static function error(string $message, array $context = []): void {
        self::log('ERROR', $message, $context);
    }

    public static function info(string $message, array $context = []): void {
        self::log('INFO', $message, $context);
    }

    public static function log(string $level, string $message, array $context = []): void {
        $logDir = ROOT_PATH . '/logs/errors';
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0755, true);
        }

        $logFile = $logDir . '/' . date('Y-m-d') . '.json';

        $entry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'level'     => $level,
            'message'   => $message,
            'ip'        => Security::clientIp(),
            'user_id'   => $_SESSION['user']['id'] ?? null,
            'context'   => $context
        ];

        @file_put_contents($logFile, json_encode($entry, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . PHP_EOL, FILE_APPEND | LOCK_EX);
    }
}
