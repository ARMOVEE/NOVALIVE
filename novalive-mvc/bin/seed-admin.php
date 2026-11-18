<?php
/**
 * CLI Seeder Script
 * Menginisialisasi atau mereset akun administratif default NOVA LIVE
 * Jalankan dari terminal: php bin/seed-admin.php
 */

require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/core/Database.php';

echo "=== SEEDING AKUN ADMIN NOVA LIVE ===" . PHP_EOL;

try {
    $db = Database::getInstance();

    $passwordHash = password_hash('nova1234', PASSWORD_DEFAULT);

    $users = [
        [
            'username'  => 'admin',
            'email'     => 'demo@novalive.id',
            'password'  => $passwordHash,
            'full_name' => 'Admin NOVA LIVE',
            'role'      => 'admin',
            'is_active' => 1
        ],
        [
            'username'  => 'manager1',
            'email'     => 'manager@novalive.id',
            'password'  => $passwordHash,
            'full_name' => 'Manager NOVA LIVE',
            'role'      => 'manager',
            'is_active' => 1
        ],
        [
            'username'  => 'staff1',
            'email'     => 'staff@novalive.id',
            'password'  => $passwordHash,
            'full_name' => 'Staff NOVA LIVE',
            'role'      => 'staff',
            'is_active' => 1
        ]
    ];

    $stmt = $db->prepare("
        INSERT INTO users (username, email, password, full_name, role, is_active)
        VALUES (:username, :email, :password, :full_name, :role, :is_active)
        ON DUPLICATE KEY UPDATE
            password = VALUES(password),
            full_name = VALUES(full_name),
            role = VALUES(role),
            is_active = VALUES(is_active)
    ");

    foreach ($users as $u) {
        $stmt->execute([
            ':username'  => $u['username'],
            ':email'     => $u['email'],
            ':password'  => $u['password'],
            ':full_name' => $u['full_name'],
            ':role'      => $u['role'],
            ':is_active' => $u['is_active']
        ]);
        echo "  [OK] Akun {$u['role']} ({$u['email']}) berhasil disiapkan (Password: nova1234)" . PHP_EOL;
    }

    echo "=== SEEDING SELESAI DENGAN SUKSES ===" . PHP_EOL;
} catch (Exception $e) {
    echo "  [ERROR] " . $e->getMessage() . PHP_EOL;
    exit(1);
}
