<?php
/**
 * TechFix Laptop Repair Management System
 * Seed Default System Settings Script
 *
 * Usage:
 *   php database/002_seed_default_settings.php
 */

declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));

// 1. Autoload
$autoloader = BASE_PATH . '/vendor/autoload.php';
if (!file_exists($autoloader)) {
    fwrite(STDERR, "[ERROR] Composer dependencies not found. Please run 'composer install' first.\n");
    exit(1);
}
require $autoloader;

// 2. Load .env
if (file_exists(BASE_PATH . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
    $dotenv->safeLoad();
}

$config = require BASE_PATH . '/config/database.php';
require_once BASE_PATH . '/app/helpers.php';

use App\Models\Setting;
use App\Core\Database;

echo "====================================================\n";
echo " TechFix - System Settings Seeder\n";
echo "====================================================\n";

try {
    // 1. Ensure table exists
    Database::query("
        CREATE TABLE IF NOT EXISTS `site_settings` (
          `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
          `key`        VARCHAR(100) NOT NULL,
          `value`      TEXT         DEFAULT NULL,
          `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`),
          UNIQUE KEY `uq_settings_key` (`key`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");

    $defaults = Setting::getDefaults();
    $inserted = 0;
    $existing = 0;

    foreach ($defaults as $key => $val) {
        $row = Database::fetchOne('SELECT `key` FROM `site_settings` WHERE `key` = :key LIMIT 1', ['key' => $key]);
        if (!$row) {
            Database::query('INSERT INTO `site_settings` (`key`, `value`) VALUES (:key, :val)', [
                'key' => $key,
                'val' => $val,
            ]);
            $inserted++;
        } else {
            $existing++;
        }
    }

    echo "[SUCCESS] Default system settings verified and seeded!\n";
    echo "  - Total Settings   : " . count($defaults) . "\n";
    echo "  - New Inserted     : {$inserted}\n";
    echo "  - Already Existing : {$existing}\n";
    echo "====================================================\n";

} catch (\Throwable $e) {
    fwrite(STDERR, "\n[DATABASE ERROR] " . $e->getMessage() . "\n");
    exit(1);
}
