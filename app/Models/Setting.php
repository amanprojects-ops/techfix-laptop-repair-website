<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class Setting
{
    /** In-memory cache for all settings */
    private static ?array $cache = null;

    /** Keys that must be encrypted before saving in the database */
    private static array $encryptedKeys = [
        'smtp_password',
    ];

    /** Default values for system settings */
    private static array $defaults = [
        // General / Business
        'site_name'             => 'TechFix',
        'site_tagline'          => 'Professional Laptop Repair Center',
        'contact_phone'         => '+91-9876543210',
        'contact_phone_alt'     => '',
        'whatsapp_number'       => '+91-9876543210',
        'contact_email'         => 'support@techfix.in',
        'address_line'          => 'Main Market Road, Near Bus Stand',
        'city'                  => 'Saharsa',
        'state'                 => 'Bihar',
        'pincode'               => '852201',
        'working_hours'         => 'Mon–Sat: 9:00 AM – 8:00 PM | Sun: Closed',
        'google_map_url'        => 'https://maps.google.com',
        'google_map_embed'      => '',
        'footer_about_text'     => 'Professional laptop repair center in Saharsa, Bihar. Trusted by 10,000+ satisfied customers since 2014.',
        'copyright_text'        => '© {year} TechFix Laptop Repair Center. All rights reserved.',
        'facebook_url'          => '',
        'instagram_url'         => '',
        'youtube_url'           => '',
        'twitter_url'           => '',
        'linkedin_url'          => '',
        'maintenance_mode'      => '0',
        'maintenance_message'   => 'We are currently performing scheduled maintenance. Please check back shortly or reach us directly by phone.',

        // SEO & Webmaster
        'meta_title'                 => 'TechFix — Fast & Reliable Laptop Repair in Saharsa, Bihar',
        'meta_description'           => 'Expert chip-level laptop repair, screen replacement, motherboard repair, battery replacement & data recovery with 90-day warranty in Saharsa, Bihar.',
        'meta_keywords'              => 'laptop repair, screen replacement, motherboard repair, saharsa, bihar, macbook repair, dell hp lenovo service center',
        'canonical_url'              => '',
        'og_title'                   => 'TechFix — Laptop Repair Specialists',
        'og_description'             => 'Quick & affordable laptop repairs with 90-day warranty. Book your repair today!',
        'og_image'                   => '',
        'google_search_console_code' => '',
        'google_analytics_id'        => '',
        'header_custom_scripts'      => '',
        'footer_custom_scripts'      => '',
        'robots_indexing'            => '1',

        // Branding
        'site_logo'             => '',
        'site_logo_dark'        => '',
        'site_favicon'          => '',
        'apple_touch_icon'      => '',
        'admin_logo'            => '',
        'admin_icon'            => '',

        // Email / SMTP
        'mail_driver'              => 'smtp',
        'smtp_host'                => 'smtp.gmail.com',
        'smtp_port'                => '587',
        'smtp_encryption'          => 'tls',
        'smtp_username'            => '',
        'smtp_password'            => '',
        'mail_from_address'        => 'support@techfix.in',
        'mail_from_name'           => 'TechFix Laptop Repair',
        'mail_reply_to'            => 'support@techfix.in',
        'admin_notification_email' => 'admin@techfix.in',
        'notify_on_new_booking'    => '1',
        'notify_on_status_change'  => '1',

        // Workshop & Preferences
        'currency_symbol'          => '₹',
        'currency_code'            => 'INR',
        'date_format'              => 'd M Y',
        'time_format'              => '12',
        'repair_tracking_prefix'   => 'AMN-LR',
        'default_warranty_days'    => '90',
        'allow_customer_booking'   => '1',

        // Billing & Invoicing Defaults
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
        'billing_show_bank_details'=> '1',
        'billing_upi_id'           => 'techfix@sbi',
        'billing_upi_payee_name'   => 'TechFix Laptop Repair Center',
        'billing_show_upi_qr'      => '1',
        'billing_default_due_days' => '7',
        'billing_default_notes'    => 'Thank you for choosing TechFix. All repair works are covered under our 90-day comprehensive service warranty.',
        'billing_default_terms'    => "1. Warranty is valid for 90 days from invoice date on parts replaced.\n2. Physical, liquid, or electrical surge damages are not covered under warranty.\n3. Goods once delivered are subject to warranty terms with original seal intact.\n4. Interest @18% p.a. will be applicable on overdue payments.",
    ];

    /**
     * Load all settings from DB into in-memory cache
     */
    private static function load(): void
    {
        if (self::$cache !== null) {
            return;
        }

        self::$cache = self::$defaults;

        try {
            $rows = Database::fetchAll('SELECT `key`, `value` FROM `site_settings`');
            foreach ($rows as $row) {
                $key = $row['key'];
                $val = $row['value'];

                // Decrypt if encrypted key
                if (in_array($key, self::$encryptedKeys, true) && !empty($val)) {
                    $val = self::decrypt($val);
                }

                self::$cache[$key] = $val;
            }
        } catch (\Throwable $e) {
            // If table doesn't exist yet, fallback gracefully to defaults
        }
    }

    /**
     * Get a setting value by key with optional default fallback
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        self::load();

        if (array_key_exists($key, self::$cache)) {
            $val = self::$cache[$key];
            return ($val !== null && $val !== '') ? $val : ($default ?? self::$defaults[$key] ?? null);
        }

        return $default ?? self::$defaults[$key] ?? null;
    }

    /**
     * Check if a setting exists
     */
    public static function has(string $key): bool
    {
        self::load();
        return array_key_exists($key, self::$cache);
    }

    /**
     * Get all settings as associative array
     */
    public static function all(): array
    {
        self::load();
        return self::$cache;
    }

    /**
     * Set a single setting
     */
    public static function set(string $key, mixed $value): void
    {
        self::load();

        $rawValue = (string)($value ?? '');
        $storedValue = $rawValue;

        // Encrypt sensitive keys
        if (in_array($key, self::$encryptedKeys, true) && $rawValue !== '') {
            $storedValue = self::encrypt($rawValue);
        }

        Database::query(
            'INSERT INTO `site_settings` (`key`, `value`) VALUES (:key, :value)
             ON DUPLICATE KEY UPDATE `value` = VALUES(`value`), `updated_at` = CURRENT_TIMESTAMP',
            [
                'key'   => $key,
                'value' => $storedValue,
            ]
        );

        // Update cache
        self::$cache[$key] = $rawValue;
    }

    /**
     * Set multiple settings in a batch
     */
    public static function setMany(array $settings): void
    {
        foreach ($settings as $key => $value) {
            self::set($key, $value);
        }
    }

    /**
     * Invalidate and clear memory cache
     */
    public static function clearCache(): void
    {
        self::$cache = null;
    }

    /**
     * Get default settings array
     */
    public static function getDefaults(): array
    {
        return self::$defaults;
    }

    // ── Encryption / Decryption Utilities ─────────────────────────────────────

    private static function getSecretKey(): string
    {
        $secret = $_ENV['APP_SECRET'] ?? 'techfix_secure_system_settings_salt_key_32bytes';
        return hash('sha256', $secret, true);
    }

    public static function encrypt(string $plainText): string
    {
        if ($plainText === '') {
            return '';
        }
        $key = self::getSecretKey();
        $iv = random_bytes(16);
        $ciphertext = openssl_encrypt($plainText, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
        if ($ciphertext === false) {
            return $plainText;
        }
        return 'enc::' . base64_encode($iv . $ciphertext);
    }

    public static function decrypt(string $cipherText): string
    {
        if (!str_starts_with($cipherText, 'enc::')) {
            return $cipherText; // Return as-is if not encrypted
        }

        $raw = base64_decode(substr($cipherText, 5), true);
        if ($raw === false || strlen($raw) < 17) {
            return $cipherText;
        }

        $iv = substr($raw, 0, 16);
        $ciphertext = substr($raw, 16);
        $key = self::getSecretKey();

        $decrypted = openssl_decrypt($ciphertext, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
        return ($decrypted !== false) ? $decrypted : '';
    }
}
