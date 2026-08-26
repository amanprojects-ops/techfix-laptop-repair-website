<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;
use App\Middleware\AdminMiddleware;
use App\Middleware\CsrfMiddleware;
use App\Models\InvoiceTemplate;
use App\Models\Setting;
use App\Models\User;
use App\Services\InvoiceService;
use App\Services\MailService;
use App\Services\UploadService;

class SettingsController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        AdminMiddleware::handle();
    }

    /**
     * Display the System Settings Manager dashboard
     */
    public function index(): void
    {
        $activeTab = $this->request->get('tab', 'general');
        $validTabs = ['general', 'seo', 'branding', 'mail', 'workshop', 'billing'];
        if (!in_array($activeTab, $validTabs, true)) {
            $activeTab = 'general';
        }

        $user = User::findById((int)Session::userId());
        $csrfToken = Session::csrfToken();
        $settings = Setting::all();
        $templates = InvoiceTemplate::all();

        $flashSuccess = Session::getFlash('success');
        $flashError   = Session::getFlash('error');

        $this->render('admin/settings/index', [
            'pageTitle'    => 'System Settings Manager',
            'activeTab'    => $activeTab,
            'settings'     => $settings,
            'templates'    => $templates,
            'user'         => $user ?: ['name' => Session::userName(), 'role' => Session::userRole()],
            'csrfToken'    => $csrfToken,
            'flashSuccess' => $flashSuccess,
            'flashError'   => $flashError,
        ], 'admin');
    }

    /**
     * Update General & Business Settings
     */
    public function updateGeneral(): void
    {
        CsrfMiddleware::verify();

        $data = [
            'site_name'           => trim((string)$this->request->post('site_name', 'TechFix')),
            'site_tagline'        => trim((string)$this->request->post('site_tagline', '')),
            'contact_phone'       => trim((string)$this->request->post('contact_phone', '')),
            'contact_phone_alt'   => trim((string)$this->request->post('contact_phone_alt', '')),
            'whatsapp_number'     => trim((string)$this->request->post('whatsapp_number', '')),
            'contact_email'       => trim((string)$this->request->post('contact_email', '')),
            'address_line'        => trim((string)$this->request->post('address_line', '')),
            'city'                => trim((string)$this->request->post('city', '')),
            'state'               => trim((string)$this->request->post('state', '')),
            'pincode'             => trim((string)$this->request->post('pincode', '')),
            'working_hours'       => trim((string)$this->request->post('working_hours', '')),
            'google_map_url'      => trim((string)$this->request->post('google_map_url', '')),
            'google_map_embed'    => trim((string)$this->request->post('google_map_embed', '')),
            'footer_about_text'   => trim((string)$this->request->post('footer_about_text', '')),
            'copyright_text'      => trim((string)$this->request->post('copyright_text', '')),
            'facebook_url'        => trim((string)$this->request->post('facebook_url', '')),
            'instagram_url'       => trim((string)$this->request->post('instagram_url', '')),
            'youtube_url'         => trim((string)$this->request->post('youtube_url', '')),
            'twitter_url'         => trim((string)$this->request->post('twitter_url', '')),
            'linkedin_url'        => trim((string)$this->request->post('linkedin_url', '')),
            'maintenance_mode'    => $this->request->post('maintenance_mode') === '1' ? '1' : '0',
            'maintenance_message' => trim((string)$this->request->post('maintenance_message', '')),
        ];

        if ($data['site_name'] === '') {
            Session::flash('error', 'Website name cannot be empty.');
            $this->redirect('/admin/settings?tab=general');
        }

        Setting::setMany($data);
        $this->logAudit('UPDATE_GENERAL_SETTINGS', 'Updated website general and business contact settings.');

        Session::flash('success', 'General website settings updated successfully.');
        $this->redirect('/admin/settings?tab=general');
    }

    /**
     * Update SEO & Webmaster Settings
     */
    public function updateSeo(): void
    {
        CsrfMiddleware::verify();

        $data = [
            'meta_title'                 => trim((string)$this->request->post('meta_title', '')),
            'meta_description'           => trim((string)$this->request->post('meta_description', '')),
            'meta_keywords'              => trim((string)$this->request->post('meta_keywords', '')),
            'canonical_url'              => trim((string)$this->request->post('canonical_url', '')),
            'og_title'                   => trim((string)$this->request->post('og_title', '')),
            'og_description'             => trim((string)$this->request->post('og_description', '')),
            'google_search_console_code' => trim((string)$this->request->post('google_search_console_code', '')),
            'google_analytics_id'        => trim((string)$this->request->post('google_analytics_id', '')),
            'header_custom_scripts'      => (string)$this->request->post('header_custom_scripts', ''),
            'footer_custom_scripts'      => (string)$this->request->post('footer_custom_scripts', ''),
            'robots_indexing'            => $this->request->post('robots_indexing') === '1' ? '1' : '0',
        ];

        Setting::setMany($data);
        $this->logAudit('UPDATE_SEO_SETTINGS', 'Updated SEO metadata, tags, and script injection settings.');

        Session::flash('success', 'SEO and webmaster settings updated successfully.');
        $this->redirect('/admin/settings?tab=seo');
    }

    /**
     * Update Branding Assets (Logo, Favicon, Touch Icon, Admin Logo)
     */
    public function updateBranding(): void
    {
        CsrfMiddleware::verify();

        $uploadService = new UploadService();
        $assetKeys = ['site_logo', 'site_logo_dark', 'site_favicon', 'apple_touch_icon', 'admin_logo', 'admin_icon', 'og_image'];
        $uploadedCount = 0;

        try {
            foreach ($assetKeys as $key) {
                if (isset($_FILES[$key]) && is_array($_FILES[$key]) && $_FILES[$key]['error'] === UPLOAD_ERR_OK) {
                    // Upload new asset
                    $newPath = $uploadService->uploadBrandingAsset($_FILES[$key], $key);

                    // Delete old asset if customized
                    $oldPath = (string)Setting::get($key, '');
                    if ($oldPath !== '' && $oldPath !== $newPath) {
                        $uploadService->deleteBrandingAsset($oldPath);
                    }

                    // Save setting
                    Setting::set($key, $newPath);
                    $uploadedCount++;
                }
            }

            if ($uploadedCount > 0) {
                $this->logAudit('UPDATE_BRANDING_ASSETS', "Updated {$uploadedCount} branding asset(s).");
                Session::flash('success', "Successfully updated {$uploadedCount} branding asset(s).");
            } else {
                Session::flash('info', 'No new image files were selected for upload.');
            }

        } catch (\Throwable $e) {
            Session::flash('error', 'Asset upload failed: ' . $e->getMessage());
        }

        $this->redirect('/admin/settings?tab=branding');
    }

    /**
     * Reset a branding asset to default
     */
    public function resetBranding(): void
    {
        CsrfMiddleware::verify();

        $assetKey = trim((string)$this->request->post('asset_key', ''));
        $validKeys = ['site_logo', 'site_logo_dark', 'site_favicon', 'apple_touch_icon', 'admin_logo', 'admin_icon', 'og_image'];

        if (in_array($assetKey, $validKeys, true)) {
            $oldPath = (string)Setting::get($assetKey, '');
            if ($oldPath !== '') {
                $uploadService = new UploadService();
                $uploadService->deleteBrandingAsset($oldPath);
            }
            Setting::set($assetKey, '');
            $this->logAudit('RESET_BRANDING_ASSET', "Reset {$assetKey} to system default.");
            Session::flash('success', "Reset {$assetKey} to system default.");
        } else {
            Session::flash('error', 'Invalid asset key specified.');
        }

        $this->redirect('/admin/settings?tab=branding');
    }

    /**
     * Update Email / SMTP Settings
     */
    public function updateMail(): void
    {
        CsrfMiddleware::verify();

        $data = [
            'mail_driver'              => trim((string)$this->request->post('mail_driver', 'smtp')),
            'smtp_host'                => trim((string)$this->request->post('smtp_host', 'smtp.gmail.com')),
            'smtp_port'                => (string)(int)$this->request->post('smtp_port', 587),
            'smtp_encryption'          => strtolower(trim((string)$this->request->post('smtp_encryption', 'tls'))),
            'smtp_username'            => trim((string)$this->request->post('smtp_username', '')),
            'mail_from_address'        => trim((string)$this->request->post('mail_from_address', 'support@techfix.in')),
            'mail_from_name'           => trim((string)$this->request->post('mail_from_name', 'TechFix Laptop Repair')),
            'mail_reply_to'            => trim((string)$this->request->post('mail_reply_to', '')),
            'admin_notification_email' => trim((string)$this->request->post('admin_notification_email', '')),
            'notify_on_new_booking'    => $this->request->post('notify_on_new_booking') === '1' ? '1' : '0',
            'notify_on_status_change'  => $this->request->post('notify_on_status_change') === '1' ? '1' : '0',
        ];

        // Handle password update only if non-empty
        $newPassword = (string)$this->request->post('smtp_password', '');
        if ($newPassword !== '' && $newPassword !== '••••••••') {
            $data['smtp_password'] = $newPassword;
        }

        Setting::setMany($data);
        $this->logAudit('UPDATE_MAIL_SETTINGS', "Updated email SMTP configuration (Driver: {$data['mail_driver']}, Host: {$data['smtp_host']}:{$data['smtp_port']}).");

        Session::flash('success', 'Email and SMTP settings saved successfully.');
        $this->redirect('/admin/settings?tab=mail');
    }

    /**
     * Send Real-Time Diagnostic Test Email
     */
    public function sendTestEmail(): void
    {
        CsrfMiddleware::verify();

        $testRecipient = trim((string)$this->request->post('test_email', ''));
        if (!filter_var($testRecipient, FILTER_VALIDATE_EMAIL)) {
            if ($this->isAjax()) {
                $this->json([
                    'success' => false,
                    'message' => 'Please enter a valid recipient email address.',
                    'logs'    => ['❌ Invalid email address provided.'],
                ], 422);
            }
            Session::flash('error', 'Please enter a valid recipient email address.');
            $this->redirect('/admin/settings?tab=mail');
        }

        // Custom config overrides if passed from the form
        $customConfig = [
            'mail_driver'       => trim((string)$this->request->post('mail_driver', Setting::get('mail_driver', 'smtp'))),
            'smtp_host'         => trim((string)$this->request->post('smtp_host', Setting::get('smtp_host', 'smtp.gmail.com'))),
            'smtp_port'         => (int)$this->request->post('smtp_port', Setting::get('smtp_port', 587)),
            'smtp_encryption'   => strtolower(trim((string)$this->request->post('smtp_encryption', Setting::get('smtp_encryption', 'tls')))),
            'smtp_username'     => trim((string)$this->request->post('smtp_username', Setting::get('smtp_username', ''))),
            'mail_from_address' => trim((string)$this->request->post('mail_from_address', Setting::get('mail_from_address', 'support@techfix.in'))),
            'mail_from_name'    => trim((string)$this->request->post('mail_from_name', Setting::get('mail_from_name', 'TechFix Laptop Repair'))),
            'mail_reply_to'     => trim((string)$this->request->post('mail_reply_to', Setting::get('mail_reply_to', ''))),
        ];

        $pass = (string)$this->request->post('smtp_password', '');
        if ($pass !== '' && $pass !== '••••••••') {
            $customConfig['smtp_password'] = $pass;
        } else {
            $customConfig['smtp_password'] = (string)Setting::get('smtp_password', '');
        }

        $mailer = new MailService($customConfig);
        $result = $mailer->testConnection($testRecipient);

        $this->logAudit('TEST_SMTP_EMAIL', "Test email to {$testRecipient} - Result: " . ($result['success'] ? 'SUCCESS' : 'FAILED'));

        if ($this->isAjax()) {
            $this->json($result, $result['success'] ? 200 : 400);
        }

        if ($result['success']) {
            Session::flash('success', $result['message']);
        } else {
            Session::flash('error', 'SMTP Test Failed: ' . $result['message']);
        }

        $this->redirect('/admin/settings?tab=mail');
    }

    /**
     * Update Workshop Preferences
     */
    public function updateWorkshop(): void
    {
        CsrfMiddleware::verify();

        $data = [
            'currency_symbol'        => trim((string)$this->request->post('currency_symbol', '₹')),
            'currency_code'          => strtoupper(trim((string)$this->request->post('currency_code', 'INR'))),
            'date_format'            => trim((string)$this->request->post('date_format', 'd M Y')),
            'time_format'            => in_array($this->request->post('time_format'), ['12', '24'], true) ? (string)$this->request->post('time_format') : '12',
            'repair_tracking_prefix' => strtoupper(trim((string)$this->request->post('repair_tracking_prefix', 'AMN-LR'))),
            'default_warranty_days'  => (string)max(0, (int)$this->request->post('default_warranty_days', 90)),
            'allow_customer_booking' => $this->request->post('allow_customer_booking') === '1' ? '1' : '0',
        ];

        Setting::setMany($data);
        $this->logAudit('UPDATE_WORKSHOP_SETTINGS', 'Updated workshop preferences and system localization.');

        Session::flash('success', 'Workshop preferences updated successfully.');
        $this->redirect('/admin/settings?tab=workshop');
    }

    /**
     * Update Billing, Taxes, Banking & Invoicing Settings
     */
    public function updateBilling(): void
    {
        CsrfMiddleware::verify();

        $data = [
            'billing_invoice_prefix'   => trim((string)$this->request->post('billing_invoice_prefix', 'INV-{year}-')),
            'billing_next_number'      => (string)max(1, (int)$this->request->post('billing_next_number', 1001)),
            'billing_default_template' => trim((string)$this->request->post('billing_default_template', 'modern')),
            'billing_tax_name'         => trim((string)$this->request->post('billing_tax_name', 'GST')),
            'billing_tax_rate'         => (string)max(0.0, (float)$this->request->post('billing_tax_rate', 18.0)),
            'billing_enable_tax'       => $this->request->post('billing_enable_tax') === '1' ? '1' : '0',
            'billing_gst_number'       => strtoupper(trim((string)$this->request->post('billing_gst_number', ''))),
            'billing_pan_number'       => strtoupper(trim((string)$this->request->post('billing_pan_number', ''))),
            'billing_bank_name'        => trim((string)$this->request->post('billing_bank_name', '')),
            'billing_bank_account'     => trim((string)$this->request->post('billing_bank_account', '')),
            'billing_bank_ifsc'        => strtoupper(trim((string)$this->request->post('billing_bank_ifsc', ''))),
            'billing_bank_branch'      => trim((string)$this->request->post('billing_bank_branch', '')),
            'billing_show_bank_details'=> $this->request->post('billing_show_bank_details') === '1' ? '1' : '0',
            'billing_upi_id'           => trim((string)$this->request->post('billing_upi_id', '')),
            'billing_upi_payee_name'   => trim((string)$this->request->post('billing_upi_payee_name', '')),
            'billing_show_upi_qr'      => $this->request->post('billing_show_upi_qr') === '1' ? '1' : '0',
            'billing_default_due_days' => (string)max(0, (int)$this->request->post('billing_default_due_days', 7)),
            'billing_default_notes'    => trim((string)$this->request->post('billing_default_notes', '')),
            'billing_default_terms'    => trim((string)$this->request->post('billing_default_terms', '')),
        ];

        Setting::setMany($data);
        $this->logAudit('UPDATE_BILLING_SETTINGS', 'Updated billing setup, tax details, bank and UPI configurations.');

        Session::flash('success', 'Billing and invoicing settings saved successfully.');
        $this->redirect('/admin/settings?tab=billing');
    }

    /**
     * Save/Customize an Invoice Template dynamically
     */
    public function saveTemplate(): void
    {
        CsrfMiddleware::verify();

        $templateId = (int)$this->request->post('template_id', 0);
        $templateKey = preg_replace('/[^a-z0-9_]/', '', strtolower(trim((string)$this->request->post('template_key', ''))));
        $name = trim((string)$this->request->post('name', ''));

        if ($name === '') {
            Session::flash('error', 'Template name is required.');
            $this->redirect('/admin/settings?tab=billing');
        }

        $data = [
            'name'              => $name,
            'description'       => trim((string)$this->request->post('description', '')),
            'is_active'         => $this->request->post('is_active') === '1' ? 1 : 0,
            'paper_size'        => $this->request->post('paper_size') ?: 'A4',
            'accent_color'      => trim((string)$this->request->post('accent_color', '#2563EB')),
            'secondary_color'   => trim((string)$this->request->post('secondary_color', '#0F172A')),
            'font_family'       => trim((string)$this->request->post('font_family', 'Inter, sans-serif')),
            'show_watermark'    => $this->request->post('show_watermark') === '1' ? 1 : 0,
            'watermark_text'    => trim((string)$this->request->post('watermark_text', 'PAID')),
            'show_qr_code'      => $this->request->post('show_qr_code') === '1' ? 1 : 0,
            'show_signature'    => $this->request->post('show_signature') === '1' ? 1 : 0,
            'show_tax_breakup'  => $this->request->post('show_tax_breakup') === '1' ? 1 : 0,
            'show_bank_details' => $this->request->post('show_bank_details') === '1' ? 1 : 0,
            'header_layout'     => trim((string)$this->request->post('header_layout', 'standard')),
            'custom_css'        => trim((string)$this->request->post('custom_css', '')),
            'terms_default'     => trim((string)$this->request->post('terms_default', '')),
            'notes_default'     => trim((string)$this->request->post('notes_default', '')),
        ];

        if ($templateId > 0) {
            InvoiceTemplate::update($templateId, $data);
            $this->logAudit('UPDATE_INVOICE_TEMPLATE', "Updated invoice template #{$templateId}");
            Session::flash('success', "Invoice template '{$name}' updated successfully.");
        } else {
            if ($templateKey === '') {
                $templateKey = 'tpl_' . time();
            }
            $data['template_key'] = $templateKey;
            InvoiceTemplate::create($data);
            $this->logAudit('CREATE_INVOICE_TEMPLATE', "Created dynamic invoice template '{$templateKey}'");
            Session::flash('success', "New dynamic invoice template '{$name}' created successfully.");
        }

        $this->redirect('/admin/settings?tab=billing');
    }

    /**
     * Delete custom dynamic template
     */
    public function deleteTemplate(string $id): void
    {
        CsrfMiddleware::verify();

        $tpl = InvoiceTemplate::findById((int)$id);
        if (!$tpl || !empty($tpl['is_system'])) {
            Session::flash('error', 'Cannot delete system default templates.');
            $this->redirect('/admin/settings?tab=billing');
        }

        InvoiceTemplate::delete((int)$id);
        $this->logAudit('DELETE_INVOICE_TEMPLATE', "Deleted custom template #{$id}");

        Session::flash('success', 'Custom template deleted successfully.');
        $this->redirect('/admin/settings?tab=billing');
    }

    /**
     * Dynamic AJAX template preview endpoint
     */
    public function previewTemplateAjax(): void
    {
        $templateKey = trim((string)$this->request->post('template_key', 'modern'));
        $accentColor = trim((string)$this->request->post('accent_color', '#2563EB'));
        $secondaryColor = trim((string)$this->request->post('secondary_color', '#0F172A'));
        $fontFamily = trim((string)$this->request->post('font_family', 'Inter, sans-serif'));
        $paperSize = trim((string)$this->request->post('paper_size', 'A4'));

        $sampleInvoice = [
            'id'                   => 9999,
            'invoice_number'       => 'INV-' . date('Y') . '-PREVIEW',
            'repair_job_id'        => 1,
            'customer_id'          => 1,
            'customer_name'        => 'Rahul Sharma',
            'customer_phone'       => '+91-9876543210',
            'customer_email'       => 'rahul.sharma@example.com',
            'customer_address'     => 'Koshi Chowk, Ward 12',
            'customer_city'        => 'Saharsa',
            'repair_tracking_id'   => 'AMN-LR-202601',
            'device_brand'         => 'Dell',
            'device_model'         => 'Inspiron 15 5000',
            'device_serial'        => 'CN-0X9821-70166',
            'template_key'         => $templateKey,
            'invoice_date'         => date('Y-m-d'),
            'due_date'             => date('Y-m-d', strtotime('+7 days')),
            'status'               => 'paid',
            'currency'             => 'INR',
            'currency_symbol'      => '₹',
            'subtotal'             => 3500.00,
            'discount_type'        => 'fixed',
            'discount_value'       => 200.00,
            'discount_amount'      => 200.00,
            'tax_name'             => 'GST',
            'tax_rate'             => 18.00,
            'tax_amount'           => 594.00,
            'shipping_or_handling' => 0.00,
            'total_amount'         => 3894.00,
            'paid_amount'          => 3894.00,
            'balance_due'          => 0.00,
            'payment_method'       => 'upi',
            'payment_reference'    => 'UPI/382910482910',
            'notes'                => 'Sample dynamic template preview.',
            'terms_conditions'     => Setting::get('billing_default_terms'),
            'payment_qr_data'      => (new InvoiceService())->generateUpiQrUrl('techfix@sbi', 'TechFix Center', 3894.00, 'INV-PREVIEW'),
        ];

        $sampleItems = [
            [
                'item_name'   => 'FHD 15.6" IPS Display Replacement (OEM Grade)',
                'description' => 'Original 1920x1080 30-pin matte panel with 90-day warranty',
                'item_type'   => 'part',
                'quantity'    => 1.0,
                'unit_price'  => 2800.00,
                'total_price' => 2800.00,
            ],
            [
                'item_name'   => 'Precision Display Assembly Installation & Testing',
                'description' => 'Hinge alignment, bezel sealing, and dead-pixel calibration',
                'item_type'   => 'labor',
                'quantity'    => 1.0,
                'unit_price'  => 700.00,
                'total_price' => 700.00,
            ],
        ];

        $sampleInvoice['items'] = $sampleItems;

        $template = InvoiceTemplate::findByKey($templateKey) ?: [];
        $template['accent_color']    = $accentColor;
        $template['secondary_color'] = $secondaryColor;
        $template['font_family']     = $fontFamily;
        $template['paper_size']      = $paperSize;

        $service = new InvoiceService();
        $html = $service->renderInvoiceHtml($sampleInvoice, $templateKey);

        header('Content-Type: text/html; charset=UTF-8');
        echo $html;
        exit;
    }

    private function isAjax(): bool
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' ||
            (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'));
    }

    private function logAudit(string $action, ?string $detail = null): void
    {
        try {
            Database::query(
                'INSERT INTO `audit_log` (`user_id`, `action`, `model`, `new_value`, `ip_address`)
                 VALUES (:uid, :act, :mod, :nv, :ip)',
                [
                    'uid' => Session::userId(),
                    'act' => $action,
                    'mod' => 'Setting',
                    'nv'  => $detail,
                    'ip'  => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
                ]
            );
        } catch (\Throwable) {
            // Never break execution on audit failure
        }
    }
}
