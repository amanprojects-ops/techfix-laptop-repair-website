<?php
/**
 * TechFix Laptop Repair Management System
 * Seed Default Billing & Invoicing Settings
 */

declare(strict_types=1);

use App\Core\Database;
use App\Models\Setting;

require_once __DIR__ . '/../app/helpers.php';

$billingSettings = [
    'billing_invoice_prefix'   => 'INV-{year}-',
    'billing_next_number'      => '1001',
    'billing_default_template' => 'modern',
    'billing_tax_name'         => 'GST',
    'billing_tax_rate'         => '18',
    'billing_enable_tax'       => '1',
    'billing_gst_number'       => '10AAACT0000A1Z5',
    'billing_pan_number'       => 'AAACT0000A',
    'billing_bank_name'        => 'State Bank of India',
    'billing_bank_account'     => '389201948201',
    'billing_bank_ifsc'        => 'SBIN0001234',
    'billing_bank_branch'      => 'Saharsa Main Branch',
    'billing_upi_id'           => 'techfix@sbi',
    'billing_upi_payee_name'   => 'TechFix Laptop Repair Center',
    'billing_default_due_days' => '7',
    'billing_default_notes'    => 'Thank you for choosing TechFix. All repair works are covered under our 90-day comprehensive service warranty.',
    'billing_default_terms'    => "1. Warranty is valid for 90 days from invoice date on parts replaced.\n2. Physical, liquid, or electrical surge damages are not covered under warranty.\n3. Goods once delivered are subject to warranty terms with original seal intact.\n4. Interest @18% p.a. will be applicable on overdue payments.",
];

foreach ($billingSettings as $key => $val) {
    Database::execute(
        "INSERT INTO `site_settings` (`key`, `value`) VALUES (?, ?) ON DUPLICATE KEY UPDATE `key` = `key`",
        [$key, $val]
    );
}

echo "  - Billing settings verified/seeded successfully.\n";
