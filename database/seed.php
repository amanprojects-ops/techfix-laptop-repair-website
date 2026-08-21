<?php
/**
 * TechFix Laptop Repair Management System
 * Database Seeder CLI Runner
 *
 * Usage:
 *   php database/seed.php          (Seed admin, services, settings, FAQs)
 *   php database/seed.php --sample (Seed everything + sample customers & repair jobs)
 */

declare(strict_types=1);

if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__));
}

// Autoload
$autoloader = BASE_PATH . '/vendor/autoload.php';
if (!file_exists($autoloader)) {
    fwrite(STDERR, "[ERROR] Composer dependencies not found. Run 'composer install' first.\n");
    exit(1);
}
require_once $autoloader;

// Load .env
if (file_exists(BASE_PATH . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
    $dotenv->safeLoad();
}

$dbConfig = require BASE_PATH . '/config/database.php';
require_once BASE_PATH . '/app/helpers.php';

use App\Core\Database;
use App\Models\Setting;

class DatabaseSeeder
{
    private PDO $pdo;
    private bool $seedSample = false;

    public function __construct(?PDO $pdo = null, array $argv = [])
    {
        $this->pdo = $pdo ?? Database::get();

        foreach ($argv as $arg) {
            if ($arg === '--sample') {
                $this->seedSample = true;
            }
        }
    }

    public function run(): void
    {
        echo "----------------------------------------------------\n";
        echo "  🌱 Executing Database Seeders...\n";
        echo "----------------------------------------------------\n";

        $this->seedAdminAndStaff();
        $this->seedServices();
        $this->seedSettings();
        $this->seedFaqs();

        if ($this->seedSample) {
            $this->seedSampleData();
        }

        echo "----------------------------------------------------\n";
        echo "  ✔ Database Seeding Completed Successfully!\n";
        echo "----------------------------------------------------\n";
    }

    private function seedAdminAndStaff(): void
    {
        echo "  > Seeding Admin & Staff Accounts... ";

        $users = [
            [
                'name'     => 'Admin',
                'email'    => 'admin@techfix.in',
                'phone'    => '9876543210',
                'password' => password_hash('admin123', PASSWORD_BCRYPT),
                'role'     => 'admin',
                'status'   => 'active',
            ],
            [
                'name'     => 'Aman Sharma',
                'email'    => 'tech.aman@techfix.in',
                'phone'    => '9876543211',
                'password' => password_hash('tech123', PASSWORD_BCRYPT),
                'role'     => 'technician',
                'status'   => 'active',
            ],
            [
                'name'     => 'Vikram Kumar',
                'email'    => 'tech.vikram@techfix.in',
                'phone'    => '9876543212',
                'password' => password_hash('tech123', PASSWORD_BCRYPT),
                'role'     => 'technician',
                'status'   => 'active',
            ],
        ];

        $stmt = $this->pdo->prepare("
            INSERT INTO `users` (`name`, `email`, `phone`, `password`, `role`, `status`)
            VALUES (:name, :email, :phone, :password, :role, :status)
            ON DUPLICATE KEY UPDATE
                `name`   = VALUES(`name`),
                `phone`  = VALUES(`phone`),
                `role`   = VALUES(`role`),
                `status` = VALUES(`status`)
        ");

        foreach ($users as $user) {
            $stmt->execute($user);
        }

        echo "[\033[32mOK\033[0m] (Admin: admin@techfix.in / admin123)\n";
    }

    private function seedServices(): void
    {
        echo "  > Seeding Repair Service Catalog... ";

        $services = [
            [
                'name'              => 'Screen Replacement',
                'slug'              => 'screen-replacement',
                'short_description' => 'Cracked, flickering or broken laptop screen? We replace all laptop screen sizes with 90-day warranty.',
                'full_description'  => 'Complete display assembly replacement with OEM quality IPS and LED panels. Same-day installation available for popular models.',
                'starting_price'    => 1500.00,
                'estimated_days'    => 1,
                'warranty_days'     => 90,
                'icon'              => 'monitor',
                'seo_title'         => 'Laptop Screen Replacement in Saharsa | Same Day Service',
                'seo_description'   => 'Get your cracked or broken laptop display replaced with genuine quality screens at TechFix Saharsa.',
                'sort_order'        => 1,
                'status'            => 'active',
            ],
            [
                'name'              => 'Motherboard Repair',
                'slug'              => 'motherboard-repair',
                'short_description' => 'Expert chip-level motherboard diagnosis, short-circuit fix, and IC replacement.',
                'full_description'  => 'Precision micro-soldering, power IC replacement, BIOS re-flashing, and BGA rework using specialized infrared workstations.',
                'starting_price'    => 2000.00,
                'estimated_days'    => 3,
                'warranty_days'     => 90,
                'icon'              => 'cpu',
                'seo_title'         => 'Motherboard Chip-Level Repair | TechFix Saharsa',
                'seo_description'   => 'Dead laptop or no display? Expert motherboard diagnosis and chip-level repair at transparent pricing.',
                'sort_order'        => 2,
                'status'            => 'active',
            ],
            [
                'name'              => 'Battery Replacement',
                'slug'              => 'battery-replacement',
                'short_description' => 'Restore your laptop battery backup with original and high-grade replacements.',
                'full_description'  => 'Genuine battery replacement with 6-month warranty. Fixes fast battery drain, swelling, and not charging issues.',
                'starting_price'    => 800.00,
                'estimated_days'    => 1,
                'warranty_days'     => 90,
                'icon'              => 'battery-charging',
                'seo_title'         => 'Laptop Battery Replacement in Saharsa | Genuine Batteries',
                'seo_description'   => 'Replace worn-out laptop batteries for Dell, HP, Lenovo, Asus, Acer, and Apple laptops.',
                'sort_order'        => 3,
                'status'            => 'active',
            ],
            [
                'name'              => 'Data Recovery',
                'slug'              => 'data-recovery',
                'short_description' => 'Recover lost files, photos, and crucial data from damaged or corrupted drives.',
                'full_description'  => 'Advanced recovery from failing HDDs, SSDs, corrupted partitions, and deleted files with 100% privacy assurance.',
                'starting_price'    => 1500.00,
                'estimated_days'    => 2,
                'warranty_days'     => 0,
                'icon'              => 'database',
                'seo_title'         => 'Hard Drive & SSD Data Recovery | TechFix Saharsa',
                'seo_description'   => 'Safe and confidential data recovery service for laptops, external hard drives, and SSDs.',
                'sort_order'        => 4,
                'status'            => 'active',
            ],
            [
                'name'              => 'SSD / RAM Upgrade',
                'slug'              => 'ssd-ram-upgrade',
                'short_description' => 'Make your old laptop 5x faster with NVMe / SATA SSD and RAM expansion.',
                'full_description'  => 'High-speed SSD installation, OS migration, memory upgrades, and thermal paste reapplication for blazing fast performance.',
                'starting_price'    => 1200.00,
                'estimated_days'    => 1,
                'warranty_days'     => 90,
                'icon'              => 'hard-drive',
                'seo_title'         => 'Laptop SSD & RAM Upgrade in Saharsa | Instant Speed Boost',
                'seo_description'   => 'Upgrade your laptop storage and RAM for ultra-fast boot times and smooth multitasking.',
                'sort_order'        => 5,
                'status'            => 'active',
            ],
            [
                'name'              => 'Liquid Damage Repair',
                'slug'              => 'liquid-damage-repair',
                'short_description' => 'Spilled water, tea, or coffee? Deep ultrasonic cleaning and corrosion removal.',
                'full_description'  => 'Complete disassembly, ultrasonic board wash, trace rebuilding, and component-level corrosion treatment.',
                'starting_price'    => 1800.00,
                'estimated_days'    => 3,
                'warranty_days'     => 30,
                'icon'              => 'droplets',
                'seo_title'         => 'Water & Liquid Damage Laptop Repair in Saharsa',
                'seo_description'   => 'Emergency liquid spill cleaning and motherboard restoration for all laptop brands.',
                'sort_order'        => 6,
                'status'            => 'active',
            ],
        ];

        $stmt = $this->pdo->prepare("
            INSERT INTO `services` (`name`, `slug`, `short_description`, `full_description`, `starting_price`, `estimated_days`, `warranty_days`, `icon`, `seo_title`, `seo_description`, `sort_order`, `status`)
            VALUES (:name, :slug, :short_description, :full_description, :starting_price, :estimated_days, :warranty_days, :icon, :seo_title, :seo_description, :sort_order, :status)
            ON DUPLICATE KEY UPDATE
                `name`              = VALUES(`name`),
                `short_description` = VALUES(`short_description`),
                `full_description`  = VALUES(`full_description`),
                `starting_price`    = VALUES(`starting_price`),
                `estimated_days`    = VALUES(`estimated_days`),
                `warranty_days`     = VALUES(`warranty_days`),
                `icon`              = VALUES(`icon`),
                `seo_title`         = VALUES(`seo_title`),
                `seo_description`   = VALUES(`seo_description`),
                `sort_order`        = VALUES(`sort_order`),
                `status`            = VALUES(`status`)
        ");

        foreach ($services as $s) {
            $stmt->execute($s);
        }

        echo "[\033[32mOK\033[0m] (" . count($services) . " services)\n";
    }

    private function seedSettings(): void
    {
        echo "  > Seeding System & Website Settings... ";

        $defaults = Setting::getDefaults();
        $stmt = $this->pdo->prepare("
            INSERT INTO `site_settings` (`key`, `value`)
            VALUES (:key, :value)
            ON DUPLICATE KEY UPDATE `key` = `key`
        ");

        foreach ($defaults as $key => $val) {
            $stmt->execute([':key' => $key, ':value' => $val]);
        }

        echo "[\033[32mOK\033[0m] (" . count($defaults) . " settings)\n";
    }

    private function seedFaqs(): void
    {
        echo "  > Seeding Frequently Asked Questions... ";

        $faqs = [
            [
                'question'   => 'How long does a typical laptop repair take?',
                'answer'     => 'Most common repairs like screen replacement, battery replacement, SSD upgrade, and RAM expansion are completed within 2 to 4 hours (same day). Complex motherboard chip-level repairs typically take 1 to 3 business days.',
                'sort_order' => 1,
                'status'     => 'active',
            ],
            [
                'question'   => 'Do you provide a warranty on repairs?',
                'answer'     => 'Yes! We provide a written 90-day warranty on parts and labor for most hardware repairs. Battery replacements carry a 6-month warranty.',
                'sort_order' => 2,
                'status'     => 'active',
            ],
            [
                'question'   => 'What if my laptop cannot be repaired?',
                'answer'     => 'If our technicians find that the device cannot be safely or cost-effectively repaired, we will inform you right away. Diagnosis is free — there is no charge if we cannot fix it.',
                'sort_order' => 3,
                'status'     => 'active',
            ],
            [
                'question'   => 'Can I track my repair status online in real time?',
                'answer'     => 'Absolutely! Use our Repair Tracking portal and enter your Repair ID to see real-time repair stages, technician diagnosis, and estimated completion time.',
                'sort_order' => 4,
                'status'     => 'active',
            ],
            [
                'question'   => 'What laptop brands do you service?',
                'answer'     => 'We service all major brands including Dell, HP, Lenovo, Asus, Acer, Apple MacBook, MSI, Toshiba, and Samsung.',
                'sort_order' => 5,
                'status'     => 'active',
            ],
        ];

        $this->pdo->exec("TRUNCATE TABLE `faqs`");
        $stmt = $this->pdo->prepare("
            INSERT INTO `faqs` (`question`, `answer`, `sort_order`, `status`)
            VALUES (:question, :answer, :sort_order, :status)
        ");

        foreach ($faqs as $f) {
            $stmt->execute($f);
        }

        echo "[\033[32mOK\033[0m] (" . count($faqs) . " FAQs)\n";
    }

    private function seedSampleData(): void
    {
        echo "  > Seeding Sample Customers & Repair Jobs (--sample)... ";

        try {
            // Customer 1
            $this->pdo->exec("
                INSERT INTO `customers` (`id`, `name`, `phone`, `email`, `address`, `city`, `state`, `pincode`)
                VALUES (1, 'Ramesh Jha', '9876543201', 'ramesh.jha@example.com', 'Ward No 4, College Road', 'Saharsa', 'Bihar', '852201')
                ON DUPLICATE KEY UPDATE `name` = VALUES(`name`)
            ");

            // Device 1
            $this->pdo->exec("
                INSERT INTO `devices` (`id`, `customer_id`, `device_type`, `brand`, `model`, `serial_number`, `color`, `physical_condition`)
                VALUES (1, 1, 'laptop', 'Dell', 'Inspiron 15 3501', 'DL-8823901', 'Black', 'Minor scratches on top lid')
                ON DUPLICATE KEY UPDATE `brand` = VALUES(`brand`)
            ");

            // Repair Job 1
            $this->pdo->exec("
                INSERT INTO `repair_jobs` (`id`, `tracking_id`, `customer_id`, `device_id`, `service_id`, `assigned_technician_id`, `problem_description`, `diagnosis`, `estimated_amount`, `current_status`, `created_by`)
                VALUES (1, 'AMN-LR-2026-1001', 1, 1, 1, 2, 'Screen is cracked and flickering after drop.', 'IPS 15.6 FHD 30-pin panel replacement required.', 2800.00, 'IN_REPAIR', 1)
                ON DUPLICATE KEY UPDATE `tracking_id` = VALUES(`tracking_id`)
            ");

            // Status history
            $this->pdo->exec("
                INSERT IGNORE INTO `repair_status_history` (`repair_job_id`, `status`, `note`, `changed_by`)
                VALUES
                (1, 'RECEIVED', 'Device booked into workshop lab.', 1),
                (1, 'DIAGNOSIS', 'Display panel cracked. Motherboard output signals normal.', 2),
                (1, 'IN_REPAIR', 'Replacement panel installed. Testing display calibration.', 2)
            ");

            echo "[\033[32mOK\033[0m] (Sample Repair: AMN-LR-2026-1001)\n";
        } catch (\Throwable $e) {
            echo "[\033[33mSKIPPED\033[0m] (" . $e->getMessage() . ")\n";
        }
    }
}

// Run if called directly
if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'] ?? '')) {
    $seeder = new DatabaseSeeder(null, $argv);
    $seeder->run();
}
