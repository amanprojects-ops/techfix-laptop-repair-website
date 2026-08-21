-- ============================================================
-- TechFix Laptop Repair Management System
-- Database Schema — Initial Migration
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;
SET NAMES utf8mb4;

CREATE DATABASE IF NOT EXISTS `techfix_repair`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `techfix_repair`;

-- ============================================================
-- 1. USERS (Admin / Manager / Technician logins)
-- ============================================================
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

-- ============================================================
-- 2. CUSTOMERS
-- ============================================================
CREATE TABLE IF NOT EXISTS `customers` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`       VARCHAR(100) NOT NULL,
  `phone`      VARCHAR(15)  NOT NULL,
  `email`      VARCHAR(150)          DEFAULT NULL,
  `address`    VARCHAR(255)          DEFAULT NULL,
  `city`       VARCHAR(100)          DEFAULT NULL,
  `state`      VARCHAR(100)          DEFAULT NULL,
  `pincode`    VARCHAR(10)           DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_customers_phone` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 3. SERVICES (Repair service catalog)
-- ============================================================
CREATE TABLE IF NOT EXISTS `services` (
  `id`               INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`             VARCHAR(100) NOT NULL,
  `slug`             VARCHAR(120) NOT NULL,
  `short_description`TEXT         DEFAULT NULL,
  `full_description` LONGTEXT     DEFAULT NULL,
  `starting_price`   DECIMAL(10,2) NOT NULL DEFAULT '0.00',
  `estimated_days`   TINYINT UNSIGNED NOT NULL DEFAULT 1,
  `warranty_days`    SMALLINT UNSIGNED NOT NULL DEFAULT 90,
  `icon`             VARCHAR(50)  DEFAULT NULL,
  `seo_title`        VARCHAR(160) DEFAULT NULL,
  `seo_description`  VARCHAR(300) DEFAULT NULL,
  `sort_order`       TINYINT UNSIGNED NOT NULL DEFAULT 0,
  `status`           ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `created_at`       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_services_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 4. DEVICES (One device per repair job)
-- ============================================================
CREATE TABLE IF NOT EXISTS `devices` (
  `id`                       INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `customer_id`              INT UNSIGNED NOT NULL,
  `device_type`              ENUM('laptop','desktop','tablet','other') NOT NULL DEFAULT 'laptop',
  `brand`                    VARCHAR(100) NOT NULL,
  `model`                    VARCHAR(150) DEFAULT NULL,
  `serial_number`            VARCHAR(100) DEFAULT NULL,
  `color`                    VARCHAR(50)  DEFAULT NULL,
  `password_required`        TINYINT(1)   NOT NULL DEFAULT 0,
  `device_password_hint`     VARCHAR(255) DEFAULT NULL COMMENT 'Never store plain password; store hint only',
  `accessories`              TEXT         DEFAULT NULL,
  `physical_condition`       TEXT         DEFAULT NULL,
  `created_at`               TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_devices_customer` (`customer_id`),
  CONSTRAINT `fk_devices_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 5. REPAIR JOBS (Core table)
-- ============================================================
CREATE TABLE IF NOT EXISTS `repair_jobs` (
  `id`                    INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tracking_id`           VARCHAR(30)  NOT NULL,
  `customer_id`           INT UNSIGNED NOT NULL,
  `device_id`             INT UNSIGNED NOT NULL,
  `service_id`            INT UNSIGNED          DEFAULT NULL,
  `assigned_technician_id`INT UNSIGNED          DEFAULT NULL,
  `problem_description`   TEXT         NOT NULL,
  `diagnosis`             TEXT                  DEFAULT NULL,
  `estimated_amount`      DECIMAL(10,2)         DEFAULT NULL,
  `final_amount`          DECIMAL(10,2)         DEFAULT NULL,
  `priority`              ENUM('low','normal','high','urgent') NOT NULL DEFAULT 'normal',
  `current_status`        ENUM(
                            'RECEIVED',
                            'DIAGNOSIS',
                            'WAITING_APPROVAL',
                            'APPROVED',
                            'IN_REPAIR',
                            'QUALITY_CHECK',
                            'READY_FOR_PICKUP',
                            'DELIVERED',
                            'CANCELLED',
                            'ON_HOLD',
                            'PARTS_PENDING',
                            'UNREPAIRABLE'
                          ) NOT NULL DEFAULT 'RECEIVED',
  `received_at`           TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `estimated_delivery_at` DATE                  DEFAULT NULL,
  `completed_at`          TIMESTAMP NULL        DEFAULT NULL,
  `delivered_at`          TIMESTAMP NULL        DEFAULT NULL,
  `created_by`            INT UNSIGNED          DEFAULT NULL,
  `created_at`            TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`            TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_tracking_id` (`tracking_id`),
  KEY `idx_rj_customer`    (`customer_id`),
  KEY `idx_rj_device`      (`device_id`),
  KEY `idx_rj_technician`  (`assigned_technician_id`),
  KEY `idx_rj_status`      (`current_status`),
  CONSTRAINT `fk_rj_customer`    FOREIGN KEY (`customer_id`)             REFERENCES `customers` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_rj_device`      FOREIGN KEY (`device_id`)               REFERENCES `devices`   (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_rj_service`     FOREIGN KEY (`service_id`)              REFERENCES `services`  (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_rj_technician`  FOREIGN KEY (`assigned_technician_id`)  REFERENCES `users`     (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_rj_created_by`  FOREIGN KEY (`created_by`)              REFERENCES `users`     (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 6. REPAIR STATUS HISTORY (Full timeline)
-- ============================================================
CREATE TABLE IF NOT EXISTS `repair_status_history` (
  `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `repair_job_id` INT UNSIGNED NOT NULL,
  `status`        VARCHAR(30)  NOT NULL,
  `note`          TEXT         DEFAULT NULL,
  `changed_by`    INT UNSIGNED DEFAULT NULL,
  `created_at`    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_rsh_repair` (`repair_job_id`),
  CONSTRAINT `fk_rsh_repair`  FOREIGN KEY (`repair_job_id`) REFERENCES `repair_jobs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_rsh_changed` FOREIGN KEY (`changed_by`)    REFERENCES `users`       (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 7. REPAIR IMAGES
-- ============================================================
CREATE TABLE IF NOT EXISTS `repair_images` (
  `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `repair_job_id` INT UNSIGNED NOT NULL,
  `type`          ENUM('RECEIVED','DAMAGE','DIAGNOSIS','REPAIR','COMPLETED') NOT NULL DEFAULT 'RECEIVED',
  `file_path`     VARCHAR(255) NOT NULL,
  `original_name` VARCHAR(255) DEFAULT NULL,
  `uploaded_by`   INT UNSIGNED DEFAULT NULL,
  `created_at`    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_ri_repair` (`repair_job_id`),
  CONSTRAINT `fk_ri_repair`    FOREIGN KEY (`repair_job_id`) REFERENCES `repair_jobs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_ri_uploader`  FOREIGN KEY (`uploaded_by`)   REFERENCES `users`       (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 8. PAYMENTS
-- ============================================================
CREATE TABLE IF NOT EXISTS `payments` (
  `id`             INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `repair_job_id`  INT UNSIGNED NOT NULL,
  `amount`         DECIMAL(10,2) NOT NULL,
  `payment_method` ENUM('cash','upi','card','bank_transfer') NOT NULL DEFAULT 'cash',
  `transaction_id` VARCHAR(100) DEFAULT NULL,
  `payment_status` ENUM('pending','partial','paid','refunded') NOT NULL DEFAULT 'pending',
  `note`           VARCHAR(255) DEFAULT NULL,
  `paid_at`        TIMESTAMP NULL DEFAULT NULL,
  `created_by`     INT UNSIGNED DEFAULT NULL,
  `created_at`     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_pay_repair` (`repair_job_id`),
  CONSTRAINT `fk_pay_repair`   FOREIGN KEY (`repair_job_id`) REFERENCES `repair_jobs` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_pay_creator`  FOREIGN KEY (`created_by`)    REFERENCES `users`       (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 9. REPAIR PARTS USED (Per job)
-- ============================================================
CREATE TABLE IF NOT EXISTS `repair_parts` (
  `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `repair_job_id` INT UNSIGNED NOT NULL,
  `part_name`     VARCHAR(150) NOT NULL,
  `quantity`      TINYINT UNSIGNED NOT NULL DEFAULT 1,
  `unit_price`    DECIMAL(10,2) NOT NULL DEFAULT '0.00',
  `total`         DECIMAL(10,2) NOT NULL DEFAULT '0.00',
  `added_by`      INT UNSIGNED DEFAULT NULL,
  `created_at`    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_rp_repair` (`repair_job_id`),
  CONSTRAINT `fk_rp_repair` FOREIGN KEY (`repair_job_id`) REFERENCES `repair_jobs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_rp_added`  FOREIGN KEY (`added_by`)      REFERENCES `users`       (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 10. SITE SETTINGS (Admin-managed homepage content)
-- ============================================================
CREATE TABLE IF NOT EXISTS `site_settings` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `key`        VARCHAR(100) NOT NULL,
  `value`      TEXT         DEFAULT NULL,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_settings_key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 11. FAQs
-- ============================================================
CREATE TABLE IF NOT EXISTS `faqs` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `question`   VARCHAR(300) NOT NULL,
  `answer`     TEXT         NOT NULL,
  `sort_order` TINYINT UNSIGNED NOT NULL DEFAULT 0,
  `status`     ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 12. AUDIT LOG
-- ============================================================
CREATE TABLE IF NOT EXISTS `audit_log` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`    INT UNSIGNED DEFAULT NULL,
  `action`     VARCHAR(100) NOT NULL,
  `model`      VARCHAR(50)  DEFAULT NULL,
  `model_id`   INT UNSIGNED DEFAULT NULL,
  `old_value`  TEXT         DEFAULT NULL,
  `new_value`  TEXT         DEFAULT NULL,
  `ip_address` VARCHAR(45)  DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_audit_user`  (`user_id`),
  KEY `idx_audit_model` (`model`, `model_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- SEED DATA
-- ============================================================

-- Default admin user (email: admin@techfix.in | password: admin123)
INSERT IGNORE INTO `users` (`name`, `email`, `phone`, `password`, `role`, `status`) VALUES
('Admin', 'admin@techfix.in', '9876543210', '$2y$10$JIodlFSvBAlGGMlVW06hHuzeccY0k617Xl97AaMJBNFQfXMr8OcXa', 'admin', 'active');

-- Default services
INSERT IGNORE INTO `services` (`name`, `slug`, `short_description`, `starting_price`, `estimated_days`, `warranty_days`, `icon`, `sort_order`) VALUES
('Screen Replacement',  'screen-replacement',  'Cracked or broken laptop screen? We replace all laptop screen sizes.',  1500.00, 1, 90, 'monitor',           1),
('Motherboard Repair',  'motherboard-repair',  'Expert motherboard diagnosis and repair for all laptop brands.',         2000.00, 3, 90, 'cpu',               2),
('Battery Replacement', 'battery-replacement', 'Restore your laptop battery life with genuine replacements.',            800.00,  1, 90, 'battery-charging',  3),
('Data Recovery',       'data-recovery',       'Recover lost files, photos and data from any laptop storage.',          1500.00, 2, 0,  'database',          4),
('SSD / RAM Upgrade',   'ssd-ram-upgrade',     'Speed up your laptop with SSD or RAM upgrade.',                          1200.00, 1, 90, 'hard-drive',        5),
('Liquid Damage Repair','liquid-damage-repair','Coffee spill? We clean and restore liquid-damaged laptops.',             1800.00, 3, 30, 'droplets',          6);

-- Default site settings
INSERT IGNORE INTO `site_settings` (`key`, `value`) VALUES
('site_name',                 'TechFix'),
('site_tagline',              'Professional Laptop Repair Center'),
('contact_phone',             '+91-9876543210'),
('contact_phone_alt',         ''),
('whatsapp_number',           '+91-9876543210'),
('contact_email',             'support@techfix.in'),
('address_line',              'Main Market Road, Near Bus Stand'),
('city',                      'Saharsa'),
('state',                     'Bihar'),
('pincode',                   '852201'),
('working_hours',             'Mon–Sat: 9:00 AM – 8:00 PM | Sun: Closed'),
('google_map_url',            'https://maps.google.com'),
('google_map_embed',          ''),
('footer_about_text',         'Professional laptop repair center in Saharsa, Bihar. Trusted by 10,000+ satisfied customers since 2014.'),
('copyright_text',            '© {year} TechFix Laptop Repair Center. All rights reserved.'),
('facebook_url',              ''),
('instagram_url',             ''),
('youtube_url',               ''),
('twitter_url',               ''),
('linkedin_url',              ''),
('maintenance_mode',          '0'),
('maintenance_message',       'We are currently performing scheduled maintenance. Please check back shortly or reach us directly by phone.'),
('meta_title',                'TechFix — Fast & Reliable Laptop Repair in Saharsa, Bihar'),
('meta_description',          'Expert chip-level laptop repair, screen replacement, motherboard repair, battery replacement & data recovery with 90-day warranty in Saharsa, Bihar.'),
('meta_keywords',             'laptop repair, screen replacement, motherboard repair, saharsa, bihar, macbook repair, dell hp lenovo service center'),
('canonical_url',             ''),
('og_title',                  'TechFix — Laptop Repair Specialists'),
('og_description',            'Quick & affordable laptop repairs with 90-day warranty. Book your repair today!'),
('og_image',                  ''),
('google_search_console_code',''),
('google_analytics_id',       ''),
('header_custom_scripts',     ''),
('footer_custom_scripts',     ''),
('robots_indexing',           '1'),
('site_logo',                 ''),
('site_logo_dark',            ''),
('site_favicon',              ''),
('apple_touch_icon',          ''),
('admin_logo',                ''),
('admin_icon',                ''),
('mail_driver',               'smtp'),
('smtp_host',                 'smtp.gmail.com'),
('smtp_port',                 '587'),
('smtp_encryption',           'tls'),
('smtp_username',             ''),
('smtp_password',             ''),
('mail_from_address',         'support@techfix.in'),
('mail_from_name',            'TechFix Laptop Repair'),
('mail_reply_to',             'support@techfix.in'),
('admin_notification_email',  'admin@techfix.in'),
('notify_on_new_booking',     '1'),
('notify_on_status_change',   '1'),
('currency_symbol',           '₹'),
('currency_code',             'INR'),
('date_format',               'd M Y'),
('time_format',               '12'),
('repair_tracking_prefix',    'AMN-LR'),
('default_warranty_days',     '90'),
('allow_customer_booking',    '1');

-- Default FAQs
INSERT IGNORE INTO `faqs` (`question`, `answer`, `sort_order`) VALUES
('How long does a repair take?',          'Most repairs are completed within 24–48 hours. Screen replacements are often done same-day.',      1),
('Do you offer a warranty?',              'Yes! All our repairs come with a 90-day warranty on parts and labor.',                             2),
('Can I track my repair online?',         'Absolutely. Use your Repair ID to track the status of your device in real time on our website.',   3),
('What brands do you repair?',            'We repair all major brands — Dell, HP, Lenovo, Asus, Acer, Apple, MSI, and more.',                 4),
('How much does a screen replacement cost?', 'Screen replacements start from ₹1,500 depending on the laptop model and screen size.',          5);
