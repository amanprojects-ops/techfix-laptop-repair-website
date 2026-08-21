<?php
/**
 * TechFix Laptop Repair Management System
 * Admin User Seeder & Database Initializer Script
 *
 * Usage:
 *   php database/seed_admin.php
 *   php database/seed_admin.php [email] [password] [name] [phone]
 */

declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));

// 1. Autoload dependencies
$autoloader = BASE_PATH . '/vendor/autoload.php';
if (!file_exists($autoloader)) {
    fwrite(STDERR, "[ERROR] Composer dependencies not found. Please run 'composer install' first.\n");
    exit(1);
}
require $autoloader;

// 2. Load .env configuration
if (file_exists(BASE_PATH . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
    $dotenv->safeLoad();
}

$config = require BASE_PATH . '/config/database.php';

// 3. User details (from CLI args or defaults)
$email    = $argv[1] ?? 'admin@techfix.in';
$password = $argv[2] ?? 'admin123';
$name     = $argv[3] ?? 'Admin';
$phone    = $argv[4] ?? '9876543210';

echo "====================================================\n";
echo " TechFix - Admin Seeder & Database Setup\n";
echo "====================================================\n";
echo "Target DB Host : {$config['host']}:{$config['port']}\n";
echo "Target DB Name : {$config['name']}\n";
echo "Target DB User : {$config['user']}\n";
echo "----------------------------------------------------\n";

try {
    // 4. Connect to MySQL server (without specifying DB name first to handle missing DB case)
    $serverDsn = sprintf(
        'mysql:host=%s;port=%s;charset=%s',
        $config['host'],
        $config['port'],
        $config['charset']
    );

    $pdo = new PDO($serverDsn, $config['user'], $config['pass'], [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);

    // 5. Ensure database exists
    $dbName = preg_replace('/[^a-zA-Z0-9_]/', '', $config['name']);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `{$dbName}`");

    // 6. Ensure users table exists
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `users` (
          `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
          `name`       VARCHAR(100) NOT NULL,
          `email`      VARCHAR(150) NOT NULL,
          `phone`      VARCHAR(15)  NOT NULL DEFAULT '',
          `password`   VARCHAR(255) NOT NULL,
          `role`       ENUM('admin','manager','technician') NOT NULL DEFAULT 'technician',
          `status`     ENUM('active','inactive') NOT NULL DEFAULT 'active',
          `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
          `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`),
          UNIQUE KEY `uq_users_email` (`email`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");

    // 7. Hash password and insert / update admin user
    $hash = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $pdo->prepare("
        INSERT INTO `users` (`name`, `email`, `phone`, `password`, `role`, `status`)
        VALUES (:name, :email, :phone, :password, 'admin', 'active')
        ON DUPLICATE KEY UPDATE
            `name`       = VALUES(`name`),
            `phone`      = VALUES(`phone`),
            `password`   = VALUES(`password`),
            `role`       = 'admin',
            `status`     = 'active'
    ");

    $stmt->execute([
        ':name'     => $name,
        ':email'    => $email,
        ':phone'    => $phone,
        ':password' => $hash,
    ]);

    echo "[SUCCESS] Admin user seeded successfully!\n";
    echo "  - Name     : {$name}\n";
    echo "  - Email    : {$email}\n";
    echo "  - Password : {$password}\n";
    echo "  - Role     : admin\n";
    echo "  - Status   : active\n";
    echo "----------------------------------------------------\n";
    echo "Login URL  : http://localhost:8000/admin/login\n";
    echo "[!] Remember to change the default password in production!\n";
    echo "====================================================\n";

} catch (PDOException $e) {
    fwrite(STDERR, "\n[DATABASE ERROR] " . $e->getMessage() . "\n");
    fwrite(STDERR, "\nTroubleshooting Tips:\n");
    fwrite(STDERR, "1. Make sure your MySQL/MariaDB service is started (XAMPP / Laragon / WAMP / Docker).\n");
    fwrite(STDERR, "2. Check credentials in .env (DB_HOST, DB_PORT, DB_USER, DB_PASS).\n");
    exit(1);
}
