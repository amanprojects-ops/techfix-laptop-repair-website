-- ============================================================
-- TechFix Laptop Repair Management System
-- Database Migration 002: Invoices, Invoice Items & Templates
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;
SET NAMES utf8mb4;

-- ============================================================
-- 1. INVOICE TEMPLATES TABLE (Dynamic template registry)
-- ============================================================
CREATE TABLE IF NOT EXISTS `invoice_templates` (
  `id`                 INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `template_key`       VARCHAR(50)  NOT NULL,
  `name`               VARCHAR(100) NOT NULL,
  `description`        VARCHAR(255)          DEFAULT NULL,
  `is_system`          TINYINT(1)   NOT NULL DEFAULT 1,
  `is_active`          TINYINT(1)   NOT NULL DEFAULT 1,
  `paper_size`         VARCHAR(20)  NOT NULL DEFAULT 'A4',
  `accent_color`       VARCHAR(30)  NOT NULL DEFAULT '#2563EB',
  `secondary_color`    VARCHAR(30)  NOT NULL DEFAULT '#0F172A',
  `font_family`        VARCHAR(100) NOT NULL DEFAULT 'Inter, sans-serif',
  `show_watermark`     TINYINT(1)   NOT NULL DEFAULT 1,
  `watermark_text`     VARCHAR(50)  NOT NULL DEFAULT 'PAID',
  `show_qr_code`       TINYINT(1)   NOT NULL DEFAULT 1,
  `show_signature`     TINYINT(1)   NOT NULL DEFAULT 1,
  `show_tax_breakup`   TINYINT(1)   NOT NULL DEFAULT 1,
  `show_bank_details`  TINYINT(1)   NOT NULL DEFAULT 1,
  `header_layout`      VARCHAR(50)  NOT NULL DEFAULT 'standard',
  `custom_css`         TEXT                  DEFAULT NULL,
  `terms_default`      TEXT                  DEFAULT NULL,
  `notes_default`      TEXT                  DEFAULT NULL,
  `created_at`         TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`         TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_template_key` (`template_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 2. INVOICES TABLE
-- ============================================================
CREATE TABLE IF NOT EXISTS `invoices` (
  `id`                   INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `invoice_number`       VARCHAR(50)  NOT NULL,
  `repair_job_id`        INT UNSIGNED          DEFAULT NULL,
  `customer_id`          INT UNSIGNED NOT NULL,
  `template_key`         VARCHAR(50)  NOT NULL DEFAULT 'modern',
  `invoice_date`         DATE         NOT NULL,
  `due_date`             DATE                  DEFAULT NULL,
  `status`               ENUM('draft','issued','paid','partially_paid','cancelled','overdue') NOT NULL DEFAULT 'issued',
  `currency`             VARCHAR(10)  NOT NULL DEFAULT 'INR',
  `currency_symbol`      VARCHAR(10)  NOT NULL DEFAULT '₹',
  `subtotal`             DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `discount_type`        ENUM('fixed','percentage') NOT NULL DEFAULT 'fixed',
  `discount_value`       DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `discount_amount`      DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `tax_name`             VARCHAR(50)  NOT NULL DEFAULT 'GST',
  `tax_rate`             DECIMAL(5,2) NOT NULL DEFAULT 18.00,
  `tax_amount`           DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `shipping_or_handling` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `total_amount`         DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `paid_amount`          DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `balance_due`          DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `payment_method`       VARCHAR(50)  NOT NULL DEFAULT 'cash',
  `payment_reference`    VARCHAR(100)          DEFAULT NULL,
  `notes`                TEXT                  DEFAULT NULL,
  `terms_conditions`     TEXT                  DEFAULT NULL,
  `customer_notes`       TEXT                  DEFAULT NULL,
  `payment_qr_data`      VARCHAR(255)          DEFAULT NULL,
  `created_by`           INT UNSIGNED          DEFAULT NULL,
  `created_at`           TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`           TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_invoice_number` (`invoice_number`),
  KEY `idx_inv_customer` (`customer_id`),
  KEY `idx_inv_repair`   (`repair_job_id`),
  KEY `idx_inv_status`   (`status`),
  KEY `idx_inv_date`     (`invoice_date`),
  CONSTRAINT `fk_inv_customer` FOREIGN KEY (`customer_id`)   REFERENCES `customers`   (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_inv_repair`   FOREIGN KEY (`repair_job_id`) REFERENCES `repair_jobs` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_inv_user`     FOREIGN KEY (`created_by`)    REFERENCES `users`       (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 3. INVOICE ITEMS TABLE
-- ============================================================
CREATE TABLE IF NOT EXISTS `invoice_items` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `invoice_id`  INT UNSIGNED NOT NULL,
  `item_type`   ENUM('service','part','labor','diagnostic','custom') NOT NULL DEFAULT 'service',
  `item_name`   VARCHAR(255) NOT NULL,
  `description` TEXT                  DEFAULT NULL,
  `quantity`    DECIMAL(10,2) NOT NULL DEFAULT 1.00,
  `unit_price`  DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `total_price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `sort_order`  TINYINT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_ii_invoice` (`invoice_id`),
  CONSTRAINT `fk_ii_invoice` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 4. SEED DEFAULT INVOICE TEMPLATES
-- ============================================================
INSERT INTO `invoice_templates` (
  `template_key`, `name`, `description`, `is_system`, `is_active`, `paper_size`,
  `accent_color`, `secondary_color`, `font_family`, `show_watermark`, `watermark_text`,
  `show_qr_code`, `show_signature`, `show_tax_breakup`, `show_bank_details`, `header_layout`
) VALUES
(
  'modern',
  'Modern Minimalist (Default)',
  'Clean, contemporary invoice with vibrant gradient header, badge status, sleek tables, and instant UPI QR code.',
  1, 1, 'A4',
  '#2563EB', '#0F172A', 'Inter, sans-serif', 1, 'PAID',
  1, 1, 1, 1, 'standard'
),
(
  'classic',
  'Classic Corporate & GST Tax Invoice',
  'Formal enterprise structured grid with full GSTIN, HSN/SAC codes, CGST/SGST/IGST tax breakdown, and NEFT/RTGS bank details.',
  1, 1, 'A4',
  '#1E293B', '#334155', 'Inter, sans-serif', 1, 'TAX INVOICE',
  1, 1, 1, 1, 'standard'
),
(
  'thermal_pos',
  '80mm Thermal POS Receipt',
  'Compact monochrome thermal slip format optimized for 80mm point-of-sale receipt printers and rapid walk-in checkout.',
  1, 1, '80mm_pos',
  '#000000', '#111827', 'Courier Prime, monospace', 0, 'PAID',
  1, 0, 0, 1, 'centered'
),
(
  'techfix_neon',
  'TechFix Cyber Glow (High-Tech)',
  'Sleek dark cyberpunk-inspired header with vibrant electric blue accents, hardware diagnostics summary box, and warranty seal.',
  1, 1, 'A4',
  '#06B6D4', '#0B132B', 'Inter, sans-serif', 1, 'VERIFIED REPAIR',
  1, 1, 1, 1, 'modern_split'
),
(
  'executive_clean',
  'Executive Clean (Luxury Minimalist)',
  'Sophisticated minimalist editorial typography with charcoal and subtle slate accents, clean lines, and luxury feel.',
  1, 1, 'A4',
  '#475569', '#1E293B', 'Inter, sans-serif', 1, 'ORIGINAL',
  1, 1, 1, 1, 'standard'
)
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `description` = VALUES(`description`);

SET FOREIGN_KEY_CHECKS = 1;
