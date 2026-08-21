<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;
use App\Middleware\AdminMiddleware;
use App\Middleware\CsrfMiddleware;
use App\Models\Setting;
use App\Models\User;
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
        $validTabs = ['general', 'seo', 'branding', 'mail', 'workshop'];
        if (!in_array($activeTab, $validTabs, true)) {
            $activeTab = 'general';
        }

        $user = User::findById((int)Session::userId());
        $csrfToken = Session::csrfToken();
        $settings = Setting::all();

        $flashSuccess = Session::getFlash('success');
        $flashError   = Session::getFlash('error');

        $this->render('admin/settings/index', [
            'pageTitle'    => 'System Settings Manager',
            'activeTab'    => $activeTab,
            'settings'     => $settings,
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
