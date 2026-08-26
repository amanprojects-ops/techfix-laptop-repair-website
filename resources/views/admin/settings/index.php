<?php
/**
 * TechFix Laptop Repair Management System
 * System Manager / Settings Dashboard
 *
 * @var string $activeTab
 * @var array  $settings
 * @var array  $user
 * @var string $csrfToken
 * @var string|null $flashSuccess
 * @var string|null $flashError
 */
?>

<div class="page-header" style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
  <div>
    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
      <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--text-primary); margin: 0;">
        <i class="fas fa-sliders-h" style="color: var(--primary-color); margin-right: 8px;"></i>System Settings Manager
      </h1>
      <span class="badge" style="background: rgba(37, 99, 235, 0.1); color: var(--primary-color); padding: 4px 10px; border-radius: 9999px; font-weight: 700; font-size: 0.78rem;">
        Dynamic Engine
      </span>
      <?php if (is_maintenance_mode()): ?>
      <span class="badge" style="background: rgba(239, 68, 68, 0.15); color: #dc2626; padding: 4px 10px; border-radius: 9999px; font-weight: 700; font-size: 0.78rem; display: flex; align-items: center; gap: 5px;">
        <i class="fas fa-exclamation-triangle"></i> Maintenance Mode ON
      </span>
      <?php endif; ?>
    </div>
    <p style="color: var(--text-muted); font-size: 0.94rem; margin: 0;">
      Centrally control your website identity, SEO rankings, branding assets, email SMTP gateway, and workshop preferences.
    </p>
  </div>

  <div style="display: flex; gap: 10px;">
    <a href="<?= url('/') ?>" target="_blank" class="btn" style="background: #ffffff; border: 1px solid var(--border-color); color: var(--text-secondary); padding: 9px 16px; border-radius: var(--radius-sm); font-weight: 600; display: inline-flex; align-items: center; gap: 8px; text-decoration: none;">
      <i class="fas fa-external-link-alt"></i> Preview Website
    </a>
  </div>
</div>

<!-- Flash Alerts -->
<?php if ($flashSuccess): ?>
<div style="background: #ecfdf5; border-left: 4px solid #10b981; padding: 16px 20px; border-radius: var(--radius-sm); margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; box-shadow: var(--shadow-xs);">
  <div style="display: flex; align-items: center; gap: 12px; color: #065f46; font-weight: 600; font-size: 0.95rem;">
    <i class="fas fa-check-circle" style="font-size: 1.25rem; color: #10b981;"></i>
    <span><?= htmlspecialchars($flashSuccess, ENT_QUOTES) ?></span>
  </div>
  <button type="button" onclick="this.parentElement.remove()" style="background: none; border: none; color: #065f46; cursor: pointer; font-size: 1.1rem;"><i class="fas fa-times"></i></button>
</div>
<?php endif; ?>

<?php if ($flashError): ?>
<div style="background: #fef2f2; border-left: 4px solid #ef4444; padding: 16px 20px; border-radius: var(--radius-sm); margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; box-shadow: var(--shadow-xs);">
  <div style="display: flex; align-items: center; gap: 12px; color: #991b1b; font-weight: 600; font-size: 0.95rem;">
    <i class="fas fa-exclamation-circle" style="font-size: 1.25rem; color: #ef4444;"></i>
    <span><?= htmlspecialchars($flashError, ENT_QUOTES) ?></span>
  </div>
  <button type="button" onclick="this.parentElement.remove()" style="background: none; border: none; color: #991b1b; cursor: pointer; font-size: 1.1rem;"><i class="fas fa-times"></i></button>
</div>
<?php endif; ?>

<!-- System Manager Container with Tabs -->
<div style="background: var(--bg-card); border-radius: var(--radius-md); border: 1px solid var(--border-color); box-shadow: var(--card-shadow); overflow: hidden;">

  <!-- Tab Navigation -->
  <div style="display: flex; background: #f8fafc; border-bottom: 1px solid var(--border-color); overflow-x: auto; padding: 0 12px;">
    <a href="<?= url('/admin/settings?tab=general') ?>" class="settings-tab-btn <?= $activeTab === 'general' ? 'active' : '' ?>">
      <i class="fas fa-globe"></i>
      <span>General &amp; Business</span>
    </a>
    <a href="<?= url('/admin/settings?tab=seo') ?>" class="settings-tab-btn <?= $activeTab === 'seo' ? 'active' : '' ?>">
      <i class="fas fa-search"></i>
      <span>SEO &amp; Webmaster</span>
    </a>
    <a href="<?= url('/admin/settings?tab=branding') ?>" class="settings-tab-btn <?= $activeTab === 'branding' ? 'active' : '' ?>">
      <i class="fas fa-palette"></i>
      <span>Logo &amp; Favicons</span>
    </a>
    <a href="<?= url('/admin/settings?tab=mail') ?>" class="settings-tab-btn <?= $activeTab === 'mail' ? 'active' : '' ?>">
      <i class="fas fa-envelope-open-text"></i>
      <span>Email SMTP &amp; Test</span>
    </a>
    <a href="<?= url('/admin/settings?tab=workshop') ?>" class="settings-tab-btn <?= $activeTab === 'workshop' ? 'active' : '' ?>">
      <i class="fas fa-cogs"></i>
      <span>Workshop &amp; Preferences</span>
    </a>
    <a href="<?= url('/admin/settings?tab=billing') ?>" class="settings-tab-btn <?= $activeTab === 'billing' ? 'active' : '' ?>">
      <i class="fas fa-file-invoice-dollar"></i>
      <span>Billing &amp; Templates</span>
    </a>
  </div>

  <!-- Tab Contents -->
  <div style="padding: 28px;">

    <!-- ========================================== -->
    <!-- TAB 1: GENERAL & BUSINESS SETTINGS         -->
    <!-- ========================================== -->
    <?php if ($activeTab === 'general'): ?>
    <form method="POST" action="<?= url('/admin/settings/general') ?>" class="settings-form">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES) ?>" />

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px;">

        <!-- Website Identity Card -->
        <div class="settings-card">
          <div class="settings-card-header">
            <i class="fas fa-store"></i>
            <div>
              <h3>Website &amp; Identity</h3>
              <p>Core name, slogan, and branding title</p>
            </div>
          </div>
          <div class="settings-card-body">
            <div class="form-group">
              <label class="form-label" for="site_name">Website Name <span class="required">*</span></label>
              <input type="text" id="site_name" name="site_name" class="form-control" value="<?= htmlspecialchars($settings['site_name'] ?? 'TechFix', ENT_QUOTES) ?>" required />
              <small class="form-hint">Displayed in header, navbar, emails, and invoices.</small>
            </div>

            <div class="form-group">
              <label class="form-label" for="site_tagline">Tagline / Slogan</label>
              <input type="text" id="site_tagline" name="site_tagline" class="form-control" value="<?= htmlspecialchars($settings['site_tagline'] ?? '', ENT_QUOTES) ?>" />
              <small class="form-hint">Short tagline e.g. "Professional Laptop Repair Center"</small>
            </div>

            <div class="form-group">
              <label class="form-label" for="footer_about_text">Footer About Summary</label>
              <textarea id="footer_about_text" name="footer_about_text" class="form-control" rows="3"><?= htmlspecialchars($settings['footer_about_text'] ?? '', ENT_QUOTES) ?></textarea>
              <small class="form-hint">Brief business introduction displayed in the website footer.</small>
            </div>

            <div class="form-group">
              <label class="form-label" for="copyright_text">Copyright Notice</label>
              <input type="text" id="copyright_text" name="copyright_text" class="form-control" value="<?= htmlspecialchars($settings['copyright_text'] ?? '', ENT_QUOTES) ?>" />
              <small class="form-hint">Use <code>{year}</code> for dynamic current year.</small>
            </div>
          </div>
        </div>

        <!-- Contact & Direct Links Card -->
        <div class="settings-card">
          <div class="settings-card-header">
            <i class="fas fa-phone-volume"></i>
            <div>
              <h3>Contact &amp; Hotlines</h3>
              <p>Phone numbers, WhatsApp, and support email</p>
            </div>
          </div>
          <div class="settings-card-body">
            <div class="form-group">
              <label class="form-label" for="contact_phone">Primary Phone Number <span class="required">*</span></label>
              <div class="input-with-icon">
                <i class="fas fa-phone"></i>
                <input type="text" id="contact_phone" name="contact_phone" class="form-control" value="<?= htmlspecialchars($settings['contact_phone'] ?? '', ENT_QUOTES) ?>" required />
              </div>
              <small class="form-hint">Customer calling hotline (e.g. +91 98765 43210)</small>
            </div>

            <div class="form-group">
              <label class="form-label" for="contact_phone_alt">Alternate Phone Number</label>
              <div class="input-with-icon">
                <i class="fas fa-mobile-alt"></i>
                <input type="text" id="contact_phone_alt" name="contact_phone_alt" class="form-control" value="<?= htmlspecialchars($settings['contact_phone_alt'] ?? '', ENT_QUOTES) ?>" />
              </div>
            </div>

            <div class="form-group">
              <label class="form-label" for="whatsapp_number">WhatsApp Business Number <span class="required">*</span></label>
              <div class="input-with-icon">
                <i class="fab fa-whatsapp" style="color: #22c55e;"></i>
                <input type="text" id="whatsapp_number" name="whatsapp_number" class="form-control" value="<?= htmlspecialchars($settings['whatsapp_number'] ?? '', ENT_QUOTES) ?>" required />
              </div>
              <small class="form-hint">Enables 1-click WhatsApp chat for repair inquiries.</small>
            </div>

            <div class="form-group">
              <label class="form-label" for="contact_email">Public Contact Email <span class="required">*</span></label>
              <div class="input-with-icon">
                <i class="fas fa-envelope"></i>
                <input type="email" id="contact_email" name="contact_email" class="form-control" value="<?= htmlspecialchars($settings['contact_email'] ?? '', ENT_QUOTES) ?>" required />
              </div>
              <small class="form-hint">Public inquiries and customer support email.</small>
            </div>
          </div>
        </div>

        <!-- Physical Location & Operating Hours Card -->
        <div class="settings-card">
          <div class="settings-card-header">
            <i class="fas fa-map-marker-alt"></i>
            <div>
              <h3>Workshop Location &amp; Hours</h3>
              <p>Physical address and shop timings</p>
            </div>
          </div>
          <div class="settings-card-body">
            <div class="form-group">
              <label class="form-label" for="address_line">Shop / Building Street Address</label>
              <input type="text" id="address_line" name="address_line" class="form-control" value="<?= htmlspecialchars($settings['address_line'] ?? '', ENT_QUOTES) ?>" />
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px;">
              <div class="form-group">
                <label class="form-label" for="city">City</label>
                <input type="text" id="city" name="city" class="form-control" value="<?= htmlspecialchars($settings['city'] ?? '', ENT_QUOTES) ?>" />
              </div>
              <div class="form-group">
                <label class="form-label" for="state">State</label>
                <input type="text" id="state" name="state" class="form-control" value="<?= htmlspecialchars($settings['state'] ?? '', ENT_QUOTES) ?>" />
              </div>
              <div class="form-group">
                <label class="form-label" for="pincode">Pincode</label>
                <input type="text" id="pincode" name="pincode" class="form-control" value="<?= htmlspecialchars($settings['pincode'] ?? '', ENT_QUOTES) ?>" />
              </div>
            </div>

            <div class="form-group">
              <label class="form-label" for="working_hours">Working Hours &amp; Schedule</label>
              <div class="input-with-icon">
                <i class="fas fa-clock"></i>
                <input type="text" id="working_hours" name="working_hours" class="form-control" value="<?= htmlspecialchars($settings['working_hours'] ?? '', ENT_QUOTES) ?>" />
              </div>
              <small class="form-hint">e.g. "Mon–Sat: 9:00 AM – 8:00 PM | Sun: Closed"</small>
            </div>

            <div class="form-group">
              <label class="form-label" for="google_map_url">Google Maps Direction Link</label>
              <div class="input-with-icon">
                <i class="fas fa-directions"></i>
                <input type="url" id="google_map_url" name="google_map_url" class="form-control" value="<?= htmlspecialchars($settings['google_map_url'] ?? '', ENT_QUOTES) ?>" placeholder="https://maps.google.com/..." />
              </div>
            </div>
          </div>
        </div>

        <!-- Social Media & Maintenance Card -->
        <div class="settings-card">
          <div class="settings-card-header">
            <i class="fas fa-share-alt"></i>
            <div>
              <h3>Social Links &amp; System Maintenance</h3>
              <p>Social channels and maintenance mode</p>
            </div>
          </div>
          <div class="settings-card-body">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
              <div class="form-group">
                <label class="form-label" for="facebook_url"><i class="fab fa-facebook" style="color: #1877f2;"></i> Facebook</label>
                <input type="url" id="facebook_url" name="facebook_url" class="form-control" value="<?= htmlspecialchars($settings['facebook_url'] ?? '', ENT_QUOTES) ?>" placeholder="https://facebook.com/..." />
              </div>
              <div class="form-group">
                <label class="form-label" for="instagram_url"><i class="fab fa-instagram" style="color: #e4405f;"></i> Instagram</label>
                <input type="url" id="instagram_url" name="instagram_url" class="form-control" value="<?= htmlspecialchars($settings['instagram_url'] ?? '', ENT_QUOTES) ?>" placeholder="https://instagram.com/..." />
              </div>
              <div class="form-group">
                <label class="form-label" for="youtube_url"><i class="fab fa-youtube" style="color: #ff0000;"></i> YouTube</label>
                <input type="url" id="youtube_url" name="youtube_url" class="form-control" value="<?= htmlspecialchars($settings['youtube_url'] ?? '', ENT_QUOTES) ?>" placeholder="https://youtube.com/..." />
              </div>
              <div class="form-group">
                <label class="form-label" for="twitter_url"><i class="fab fa-x-twitter"></i> X / Twitter</label>
                <input type="url" id="twitter_url" name="twitter_url" class="form-control" value="<?= htmlspecialchars($settings['twitter_url'] ?? '', ENT_QUOTES) ?>" placeholder="https://twitter.com/..." />
              </div>
            </div>

            <!-- Maintenance Mode Toggle -->
            <div style="margin-top: 16px; padding: 16px; background: #fff1f2; border-radius: var(--radius-sm); border: 1px solid #fecdd3;">
              <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                <label style="font-weight: 700; color: #9f1239; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                  <i class="fas fa-tools"></i> Maintenance Mode
                </label>
                <label class="switch-toggle">
                  <input type="checkbox" name="maintenance_mode" value="1" <?= ($settings['maintenance_mode'] ?? '0') === '1' ? 'checked' : '' ?> />
                  <span class="switch-slider"></span>
                </label>
              </div>
              <p style="font-size: 0.82rem; color: #9f1239; margin-bottom: 10px;">
                When active, public visitors will see a maintenance notice. Staff can still log in to the admin panel.
              </p>
              <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" for="maintenance_message" style="font-size: 0.85rem; color: #9f1239;">Maintenance Message</label>
                <textarea id="maintenance_message" name="maintenance_message" class="form-control" rows="2"><?= htmlspecialchars($settings['maintenance_message'] ?? '', ENT_QUOTES) ?></textarea>
              </div>
            </div>
          </div>
        </div>

      </div>

      <div class="settings-form-actions">
        <button type="submit" class="btn btn-primary" style="padding: 12px 28px; font-weight: 700; font-size: 1rem;">
          <i class="fas fa-save" style="margin-right: 8px;"></i>Save General Settings
        </button>
      </div>
    </form>
    <?php endif; ?>


    <!-- ========================================== -->
    <!-- TAB 2: SEO & WEBMASTER SETTINGS            -->
    <!-- ========================================== -->
    <?php if ($activeTab === 'seo'): ?>
    <form method="POST" action="<?= url('/admin/settings/seo') ?>" class="settings-form">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES) ?>" />

      <!-- Google SERP Live Snippet Preview Box -->
      <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: var(--radius-sm); padding: 20px; margin-bottom: 24px; box-shadow: var(--shadow-xs);">
        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px; color: #64748b; font-size: 0.85rem; font-weight: 700; text-transform: uppercase;">
          <i class="fab fa-google" style="color: #4285f4;"></i> Live Google Search Result Preview
        </div>
        <div style="font-family: Arial, sans-serif;">
          <div style="font-size: 12px; color: #202124; margin-bottom: 2px; display: flex; align-items: center; gap: 6px;">
            <span style="background: #f1f3f4; border-radius: 50%; width: 18px; height: 18px; display: inline-flex; align-items: center; justify-content: center; font-size: 10px; font-weight: bold;">T</span>
            <span id="serp-url-preview"><?= htmlspecialchars($_ENV['APP_URL'] ?? 'https://techfix.in') ?></span>
          </div>
          <div id="serp-title-preview" style="font-size: 18px; color: #1a0dab; line-height: 1.3; font-weight: 400; text-decoration: none; cursor: pointer;">
            <?= htmlspecialchars($settings['meta_title'] ?? 'TechFix — Fast & Reliable Laptop Repair in Saharsa, Bihar', ENT_QUOTES) ?>
          </div>
          <div id="serp-desc-preview" style="font-size: 13px; color: #4d5156; line-height: 1.5; margin-top: 4px;">
            <?= htmlspecialchars($settings['meta_description'] ?? 'Expert chip-level laptop repair, screen replacement, motherboard repair, battery replacement & data recovery with 90-day warranty in Saharsa, Bihar.', ENT_QUOTES) ?>
          </div>
        </div>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px;">

        <!-- Meta Tags Card -->
        <div class="settings-card">
          <div class="settings-card-header">
            <i class="fas fa-tags"></i>
            <div>
              <h3>Metadata &amp; Search Titles</h3>
              <p>Primary title, description, and keywords</p>
            </div>
          </div>
          <div class="settings-card-body">
            <div class="form-group">
              <div style="display: flex; justify-content: space-between;">
                <label class="form-label" for="meta_title">Default Meta Title</label>
                <span id="meta_title_count" style="font-size: 0.75rem; color: #64748b;">0/60</span>
              </div>
              <input type="text" id="meta_title" name="meta_title" class="form-control" value="<?= htmlspecialchars($settings['meta_title'] ?? '', ENT_QUOTES) ?>" maxlength="100" />
              <small class="form-hint">Recommended length: 50–60 characters.</small>
            </div>

            <div class="form-group">
              <div style="display: flex; justify-content: space-between;">
                <label class="form-label" for="meta_description">Default Meta Description</label>
                <span id="meta_desc_count" style="font-size: 0.75rem; color: #64748b;">0/160</span>
              </div>
              <textarea id="meta_description" name="meta_description" class="form-control" rows="3" maxlength="255"><?= htmlspecialchars($settings['meta_description'] ?? '', ENT_QUOTES) ?></textarea>
              <small class="form-hint">Recommended length: 150–160 characters.</small>
            </div>

            <div class="form-group">
              <label class="form-label" for="meta_keywords">Meta Keywords</label>
              <input type="text" id="meta_keywords" name="meta_keywords" class="form-control" value="<?= htmlspecialchars($settings['meta_keywords'] ?? '', ENT_QUOTES) ?>" />
              <small class="form-hint">Comma separated (e.g. laptop repair, screen replacement, saharsa)</small>
            </div>

            <div class="form-group">
              <label class="form-label" for="canonical_url">Canonical URL Base</label>
              <input type="url" id="canonical_url" name="canonical_url" class="form-control" value="<?= htmlspecialchars($settings['canonical_url'] ?? '', ENT_QUOTES) ?>" placeholder="https://techfix.in" />
              <small class="form-hint">Leave blank to use current request URL.</small>
            </div>

            <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px; background: #f8fafc; border-radius: var(--radius-sm); border: 1px solid var(--border-color);">
              <div>
                <strong style="color: var(--text-primary); font-size: 0.92rem;">Robots Search Indexing</strong>
                <p style="font-size: 0.8rem; color: var(--text-muted); margin: 0;">Allow Google and Bing to crawl and index website pages</p>
              </div>
              <label class="switch-toggle">
                <input type="checkbox" name="robots_indexing" value="1" <?= ($settings['robots_indexing'] ?? '1') === '1' ? 'checked' : '' ?> />
                <span class="switch-slider"></span>
              </label>
            </div>
          </div>
        </div>

        <!-- Open Graph & Social Cards -->
        <div class="settings-card">
          <div class="settings-card-header">
            <i class="fas fa-share-nodes"></i>
            <div>
              <h3>Social Open Graph (OG) Tags</h3>
              <p>Preview cards for Facebook, WhatsApp &amp; Twitter</p>
            </div>
          </div>
          <div class="settings-card-body">
            <div class="form-group">
              <label class="form-label" for="og_title">Open Graph Title</label>
              <input type="text" id="og_title" name="og_title" class="form-control" value="<?= htmlspecialchars($settings['og_title'] ?? '', ENT_QUOTES) ?>" />
              <small class="form-hint">Title when shared on WhatsApp/Facebook</small>
            </div>

            <div class="form-group">
              <label class="form-label" for="og_description">Open Graph Description</label>
              <textarea id="og_description" name="og_description" class="form-control" rows="3"><?= htmlspecialchars($settings['og_description'] ?? '', ENT_QUOTES) ?></textarea>
            </div>

            <div class="form-group">
              <label class="form-label" for="google_analytics_id">Google Analytics 4 (GA4) Measurement ID</label>
              <div class="input-with-icon">
                <i class="fas fa-chart-line" style="color: #f59e0b;"></i>
                <input type="text" id="google_analytics_id" name="google_analytics_id" class="form-control" value="<?= htmlspecialchars($settings['google_analytics_id'] ?? '', ENT_QUOTES) ?>" placeholder="G-XXXXXXXXXX" />
              </div>
              <small class="form-hint">Enables tracking automatically across all pages.</small>
            </div>

            <div class="form-group">
              <label class="form-label" for="google_search_console_code">Google Search Console Verification Tag / Content</label>
              <div class="input-with-icon">
                <i class="fas fa-shield-alt" style="color: #10b981;"></i>
                <input type="text" id="google_search_console_code" name="google_search_console_code" class="form-control" value="<?= htmlspecialchars($settings['google_search_console_code'] ?? '', ENT_QUOTES) ?>" placeholder="e.g. google-site-verification token or full tag" />
              </div>
            </div>
          </div>
        </div>

        <!-- Custom Script Injections Card (Full Width) -->
        <div class="settings-card" style="grid-column: 1 / -1;">
          <div class="settings-card-header">
            <i class="fas fa-code"></i>
            <div>
              <h3>Custom Header &amp; Footer Scripts</h3>
              <p>Safely inject custom tracking pixels, chat widgets, or CSS into your website</p>
            </div>
          </div>
          <div class="settings-card-body" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
              <label class="form-label" for="header_custom_scripts">
                <i class="fas fa-arrow-up"></i> Header Scripts (<code style="color: #2563eb;">&lt;head&gt;</code>)
              </label>
              <textarea id="header_custom_scripts" name="header_custom_scripts" class="form-control code-editor" rows="6" placeholder="<!-- Place custom tracking tags or CSS here -->"><?= htmlspecialchars($settings['header_custom_scripts'] ?? '', ENT_QUOTES) ?></textarea>
              <small class="form-hint">Injected just before closing <code>&lt;/head&gt;</code>.</small>
            </div>

            <div class="form-group">
              <label class="form-label" for="footer_custom_scripts">
                <i class="fas fa-arrow-down"></i> Footer Scripts (<code style="color: #2563eb;">&lt;/body&gt;</code>)
              </label>
              <textarea id="footer_custom_scripts" name="footer_custom_scripts" class="form-control code-editor" rows="6" placeholder="<!-- Place live chat widgets, Facebook Pixel, etc. here -->"><?= htmlspecialchars($settings['footer_custom_scripts'] ?? '', ENT_QUOTES) ?></textarea>
              <small class="form-hint">Injected just before closing <code>&lt;/body&gt;</code>.</small>
            </div>
          </div>
        </div>

      </div>

      <div class="settings-form-actions">
        <button type="submit" class="btn btn-primary" style="padding: 12px 28px; font-weight: 700; font-size: 1rem;">
          <i class="fas fa-save" style="margin-right: 8px;"></i>Save SEO &amp; Webmaster Settings
        </button>
      </div>
    </form>
    <?php endif; ?>


    <!-- ========================================== -->
    <!-- TAB 3: LOGO & FAVICONS (BRANDING)          -->
    <!-- ========================================== -->
    <?php if ($activeTab === 'branding'): ?>
    <form method="POST" action="<?= url('/admin/settings/branding') ?>" enctype="multipart/form-data" class="settings-form">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES) ?>" />

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px;">

        <!-- 1. Header Main Logo -->
        <div class="branding-card">
          <div class="branding-card-header">
            <h4><i class="fas fa-sun" style="color: #f59e0b;"></i> Primary Header Logo</h4>
            <span class="badge" style="background:#e0f2fe; color:#0369a1; font-size:0.75rem;">Light Theme</span>
          </div>
          <div class="branding-preview-box" id="preview-box-site_logo">
            <img src="<?= site_logo() ?>" alt="Site Logo" id="preview-img-site_logo" style="max-height: 50px; max-width: 100%; object-fit: contain;" />
          </div>
          <div class="branding-card-body">
            <label class="btn-file-upload">
              <i class="fas fa-cloud-upload-alt"></i> Choose New Logo
              <input type="file" name="site_logo" accept="image/png,image/jpeg,image/webp,image/svg+xml" onchange="previewBrandingImage(this, 'preview-img-site_logo')" />
            </label>
            <div style="font-size: 0.78rem; color: var(--text-muted); margin-top: 8px; text-align: center;">
              PNG, JPG, WebP, SVG (Max 2MB)
            </div>
            <?php if (!empty($settings['site_logo'])): ?>
            <button type="button" class="btn-reset-asset" onclick="resetBrandingAsset('site_logo')">
              <i class="fas fa-trash-alt"></i> Reset to Default
            </button>
            <?php endif; ?>
          </div>
        </div>

        <!-- 2. Dark Mode / Footer Logo -->
        <div class="branding-card">
          <div class="branding-card-header">
            <h4><i class="fas fa-moon" style="color: #6366f1;"></i> Dark / Footer Logo</h4>
            <span class="badge" style="background:#1e293b; color:#f8fafc; font-size:0.75rem;">Dark Theme</span>
          </div>
          <div class="branding-preview-box dark-bg" id="preview-box-site_logo_dark">
            <img src="<?= site_logo_dark() ?>" alt="Site Dark Logo" id="preview-img-site_logo_dark" style="max-height: 50px; max-width: 100%; object-fit: contain;" />
          </div>
          <div class="branding-card-body">
            <label class="btn-file-upload">
              <i class="fas fa-cloud-upload-alt"></i> Choose Dark Logo
              <input type="file" name="site_logo_dark" accept="image/png,image/jpeg,image/webp,image/svg+xml" onchange="previewBrandingImage(this, 'preview-img-site_logo_dark')" />
            </label>
            <div style="font-size: 0.78rem; color: var(--text-muted); margin-top: 8px; text-align: center;">
              PNG, JPG, WebP, SVG (Max 2MB)
            </div>
            <?php if (!empty($settings['site_logo_dark'])): ?>
            <button type="button" class="btn-reset-asset" onclick="resetBrandingAsset('site_logo_dark')">
              <i class="fas fa-trash-alt"></i> Reset to Default
            </button>
            <?php endif; ?>
          </div>
        </div>

        <!-- 3. Browser Favicon -->
        <div class="branding-card">
          <div class="branding-card-header">
            <h4><i class="fas fa-window-maximize" style="color: #10b981;"></i> Browser Favicon</h4>
            <span class="badge" style="background:#f1f5f9; color:#475569; font-size:0.75rem;">Tab Icon</span>
          </div>
          <div class="branding-preview-box" id="preview-box-site_favicon" style="height: 100px;">
            <div style="display: flex; align-items: center; gap: 8px; background: #e2e8f0; padding: 6px 14px; border-radius: 6px;">
              <img src="<?= site_favicon() ?>" alt="Favicon" id="preview-img-site_favicon" style="width: 24px; height: 24px; object-fit: contain;" />
              <span style="font-size: 0.85rem; font-weight: 600; color: #1e293b;"><?= htmlspecialchars($settings['site_name'] ?? 'TechFix', ENT_QUOTES) ?></span>
            </div>
          </div>
          <div class="branding-card-body">
            <label class="btn-file-upload">
              <i class="fas fa-cloud-upload-alt"></i> Choose Favicon (.ico/.png)
              <input type="file" name="site_favicon" accept="image/x-icon,image/vnd.microsoft.icon,image/png,image/svg+xml" onchange="previewBrandingImage(this, 'preview-img-site_favicon')" />
            </label>
            <div style="font-size: 0.78rem; color: var(--text-muted); margin-top: 8px; text-align: center;">
              ICO, PNG, SVG (32x32 or 48x48)
            </div>
            <?php if (!empty($settings['site_favicon'])): ?>
            <button type="button" class="btn-reset-asset" onclick="resetBrandingAsset('site_favicon')">
              <i class="fas fa-trash-alt"></i> Reset to Default
            </button>
            <?php endif; ?>
          </div>
        </div>

        <!-- 4. Apple Touch Icon -->
        <div class="branding-card">
          <div class="branding-card-header">
            <h4><i class="fab fa-apple"></i> Apple Touch Icon</h4>
            <span class="badge" style="background:#f1f5f9; color:#475569; font-size:0.75rem;">iOS Home</span>
          </div>
          <div class="branding-preview-box" id="preview-box-apple_touch_icon" style="height: 100px;">
            <img src="<?= apple_touch_icon() ?>" alt="Apple Touch Icon" id="preview-img-apple_touch_icon" style="width: 54px; height: 54px; border-radius: 12px; box-shadow: var(--shadow-sm); object-fit: contain; background: #ffffff; padding: 4px;" />
          </div>
          <div class="branding-card-body">
            <label class="btn-file-upload">
              <i class="fas fa-cloud-upload-alt"></i> Choose Apple Icon
              <input type="file" name="apple_touch_icon" accept="image/png" onchange="previewBrandingImage(this, 'preview-img-apple_touch_icon')" />
            </label>
            <div style="font-size: 0.78rem; color: var(--text-muted); margin-top: 8px; text-align: center;">
              PNG format (180x180 px recommended)
            </div>
            <?php if (!empty($settings['apple_touch_icon'])): ?>
            <button type="button" class="btn-reset-asset" onclick="resetBrandingAsset('apple_touch_icon')">
              <i class="fas fa-trash-alt"></i> Reset to Default
            </button>
            <?php endif; ?>
          </div>
        </div>

        <!-- 5. Admin Portal Brand Logo -->
        <div class="branding-card">
          <div class="branding-card-header">
            <h4><i class="fas fa-user-shield" style="color: #2563eb;"></i> Admin Portal Logo</h4>
            <span class="badge" style="background:#dbeafe; color:#1e40af; font-size:0.75rem;">Dashboard</span>
          </div>
          <div class="branding-preview-box dark-bg" id="preview-box-admin_logo">
            <img src="<?= admin_logo() ?>" alt="Admin Logo" id="preview-img-admin_logo" style="max-height: 44px; max-width: 100%; object-fit: contain;" />
          </div>
          <div class="branding-card-body">
            <label class="btn-file-upload">
              <i class="fas fa-cloud-upload-alt"></i> Choose Admin Logo
              <input type="file" name="admin_logo" accept="image/png,image/jpeg,image/webp,image/svg+xml" onchange="previewBrandingImage(this, 'preview-img-admin_logo')" />
            </label>
            <div style="font-size: 0.78rem; color: var(--text-muted); margin-top: 8px; text-align: center;">
              PNG, SVG (Used in Admin Sidebar)
            </div>
            <?php if (!empty($settings['admin_logo'])): ?>
            <button type="button" class="btn-reset-asset" onclick="resetBrandingAsset('admin_logo')">
              <i class="fas fa-trash-alt"></i> Reset to Default
            </button>
            <?php endif; ?>
          </div>
        </div>

      </div>

      <div class="settings-form-actions">
        <button type="submit" class="btn btn-primary" style="padding: 12px 28px; font-weight: 700; font-size: 1rem;">
          <i class="fas fa-upload" style="margin-right: 8px;"></i>Save &amp; Upload Selected Assets
        </button>
      </div>
    </form>

    <!-- Hidden form for resetting assets -->
    <form id="reset-asset-form" method="POST" action="<?= url('/admin/settings/reset-branding') ?>" style="display: none;">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES) ?>" />
      <input type="hidden" name="asset_key" id="reset-asset-key" value="" />
    </form>
    <?php endif; ?>


    <!-- ========================================== -->
    <!-- TAB 4: EMAIL / SMTP & TEST MAIL           -->
    <!-- ========================================== -->
    <?php if ($activeTab === 'mail'): ?>
    <div style="display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 24px; align-items: start;">

      <!-- SMTP Settings Form -->
      <form method="POST" action="<?= url('/admin/settings/mail') ?>" id="smtp-settings-form" class="settings-form">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES) ?>" />

        <div class="settings-card">
          <div class="settings-card-header">
            <i class="fas fa-server"></i>
            <div>
              <h3>SMTP Gateway Credentials</h3>
              <p>Configure outgoing mail server parameters</p>
            </div>
          </div>
          <div class="settings-card-body">

            <div class="form-group">
              <label class="form-label" for="mail_driver">Mail Driver / Gateway <span class="required">*</span></label>
              <select id="mail_driver" name="mail_driver" class="form-control" onchange="toggleMailDriverFields(this.value)">
                <option value="smtp" <?= ($settings['mail_driver'] ?? 'smtp') === 'smtp' ? 'selected' : '' ?>>SMTP (Recommended — Gmail, Outlook, Hostinger, AWS SES, cPanel)</option>
                <option value="mail" <?= ($settings['mail_driver'] ?? '') === 'mail' ? 'selected' : '' ?>>PHP Built-in mail()</option>
                <option value="log" <?= ($settings['mail_driver'] ?? '') === 'log' ? 'selected' : '' ?>>Log to File (Testing / Local dev without sending)</option>
              </select>
            </div>

            <div id="smtp-fields-wrapper" style="<?= ($settings['mail_driver'] ?? 'smtp') === 'log' ? 'opacity:0.6;' : '' ?>">
              <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 16px;">
                <div class="form-group">
                  <label class="form-label" for="smtp_host">SMTP Host <span class="required">*</span></label>
                  <input type="text" id="smtp_host" name="smtp_host" class="form-control" value="<?= htmlspecialchars($settings['smtp_host'] ?? 'smtp.gmail.com', ENT_QUOTES) ?>" placeholder="smtp.gmail.com" />
                </div>
                <div class="form-group">
                  <label class="form-label" for="smtp_port">Port <span class="required">*</span></label>
                  <input type="number" id="smtp_port" name="smtp_port" class="form-control" value="<?= htmlspecialchars($settings['smtp_port'] ?? '587', ENT_QUOTES) ?>" placeholder="587" />
                </div>
              </div>

              <div class="form-group">
                <label class="form-label" for="smtp_encryption">Encryption Protocol <span class="required">*</span></label>
                <select id="smtp_encryption" name="smtp_encryption" class="form-control">
                  <option value="tls" <?= ($settings['smtp_encryption'] ?? 'tls') === 'tls' ? 'selected' : '' ?>>STARTTLS (Port 587 — Recommended)</option>
                  <option value="ssl" <?= ($settings['smtp_encryption'] ?? '') === 'ssl' ? 'selected' : '' ?>>SSL / SMTPS (Port 465)</option>
                  <option value="none" <?= ($settings['smtp_encryption'] ?? '') === 'none' ? 'selected' : '' ?>>None / Unencrypted (Port 25 or 1025 local)</option>
                </select>
              </div>

              <div class="form-group">
                <label class="form-label" for="smtp_username">SMTP Username / Email</label>
                <div class="input-with-icon">
                  <i class="fas fa-user"></i>
                  <input type="text" id="smtp_username" name="smtp_username" class="form-control" value="<?= htmlspecialchars($settings['smtp_username'] ?? '', ENT_QUOTES) ?>" placeholder="your-email@gmail.com" />
                </div>
                <small class="form-hint">For Gmail, use a 16-character App Password.</small>
              </div>

              <div class="form-group">
                <label class="form-label" for="smtp_password">SMTP Password / App Key</label>
                <div class="input-with-icon" style="position: relative;">
                  <i class="fas fa-key"></i>
                  <input type="password" id="smtp_password" name="smtp_password" class="form-control" value="<?= !empty($settings['smtp_password']) ? '••••••••' : '' ?>" placeholder="Leave blank to keep unchanged" />
                  <button type="button" onclick="togglePasswordVisibility('smtp_password', this)" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #64748b; cursor: pointer;">
                    <i class="fas fa-eye"></i>
                  </button>
                </div>
                <small class="form-hint">Stored safely with AES-256-CBC encryption in database.</small>
              </div>
            </div>

            <!-- Sender Info -->
            <div style="border-top: 1px solid var(--border-color); margin-top: 20px; padding-top: 20px;">
              <h4 style="font-size: 0.95rem; font-weight: 700; color: var(--text-primary); margin-bottom: 12px;">
                <i class="fas fa-paper-plane" style="color: var(--primary-color);"></i> Sender &amp; Notification Identities
              </h4>

              <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                  <label class="form-label" for="mail_from_name">Sender Name</label>
                  <input type="text" id="mail_from_name" name="mail_from_name" class="form-control" value="<?= htmlspecialchars($settings['mail_from_name'] ?? 'TechFix Laptop Repair', ENT_QUOTES) ?>" />
                </div>
                <div class="form-group">
                  <label class="form-label" for="mail_from_address">Sender Email Address</label>
                  <input type="email" id="mail_from_address" name="mail_from_address" class="form-control" value="<?= htmlspecialchars($settings['mail_from_address'] ?? 'support@techfix.in', ENT_QUOTES) ?>" />
                </div>
              </div>

              <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                  <label class="form-label" for="mail_reply_to">Reply-To Address</label>
                  <input type="email" id="mail_reply_to" name="mail_reply_to" class="form-control" value="<?= htmlspecialchars($settings['mail_reply_to'] ?? '', ENT_QUOTES) ?>" placeholder="support@techfix.in" />
                </div>
                <div class="form-group">
                  <label class="form-label" for="admin_notification_email">Admin Alert Email Recipient</label>
                  <input type="email" id="admin_notification_email" name="admin_notification_email" class="form-control" value="<?= htmlspecialchars($settings['admin_notification_email'] ?? '', ENT_QUOTES) ?>" placeholder="admin@techfix.in" />
                </div>
              </div>

              <div style="display: flex; flex-direction: column; gap: 10px; margin-top: 8px;">
                <label style="display: flex; align-items: center; gap: 10px; font-size: 0.9rem; color: var(--text-secondary); cursor: pointer;">
                  <input type="checkbox" name="notify_on_new_booking" value="1" <?= ($settings['notify_on_new_booking'] ?? '1') === '1' ? 'checked' : '' ?> />
                  Send email alert to Admin when a customer books a new repair online
                </label>
                <label style="display: flex; align-items: center; gap: 10px; font-size: 0.9rem; color: var(--text-secondary); cursor: pointer;">
                  <input type="checkbox" name="notify_on_status_change" value="1" <?= ($settings['notify_on_status_change'] ?? '1') === '1' ? 'checked' : '' ?> />
                  Send status update email to Customer when repair stage changes
                </label>
              </div>
            </div>

          </div>
          <div class="settings-card-footer" style="padding: 16px 24px; background: #f8fafc; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end;">
            <button type="submit" class="btn btn-primary" style="padding: 10px 24px; font-weight: 700;">
              <i class="fas fa-save" style="margin-right: 8px;"></i>Save SMTP Settings
            </button>
          </div>
        </div>
      </form>

      <!-- Live Test Email & Diagnostics Console -->
      <div class="settings-card">
        <div class="settings-card-header" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: #ffffff;">
          <i class="fas fa-vial" style="color: #38bdf8;"></i>
          <div>
            <h3 style="color: #ffffff;">Live SMTP Diagnostic Test</h3>
            <p style="color: #94a3b8;">Send a real test email with step-by-step socket logs</p>
          </div>
        </div>
        <div class="settings-card-body">
          <p style="font-size: 0.88rem; color: var(--text-secondary); margin-bottom: 16px;">
            Enter an email address to test the SMTP handshake, SSL certificate, authentication, and message delivery.
          </p>

          <form id="test-email-form" onsubmit="executeTestEmail(event)">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES) ?>" />
            <div class="form-group">
              <label class="form-label" for="test_email">Recipient Email Address <span class="required">*</span></label>
              <div class="input-with-icon">
                <i class="fas fa-at"></i>
                <input type="email" id="test_email" name="test_email" class="form-control" value="<?= htmlspecialchars($user['email'] ?? 'admin@techfix.in', ENT_QUOTES) ?>" placeholder="recipient@example.com" required />
              </div>
            </div>

            <button type="submit" id="btn-send-test-mail" class="btn" style="width: 100%; background: var(--primary-color); color: #ffffff; padding: 12px; font-weight: 700; border-radius: var(--radius-sm); display: flex; align-items: center; justify-content: center; gap: 8px; border: none; cursor: pointer;">
              <i class="fas fa-paper-plane"></i>
              <span>Send Diagnostic Test Email</span>
            </button>
          </form>

          <!-- Live Terminal Output Console -->
          <div style="margin-top: 20px;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 6px;">
              <span style="font-size: 0.78rem; font-weight: 700; color: #64748b; text-transform: uppercase;">
                <i class="fas fa-terminal"></i> Diagnostic Logs
              </span>
              <button type="button" onclick="clearTestLogs()" style="background: none; border: none; color: #64748b; font-size: 0.75rem; cursor: pointer;">
                Clear
              </button>
            </div>
            <div id="smtp-log-console" class="terminal-box">
              <div style="color: #64748b;">Ready. Click "Send Diagnostic Test Email" above to begin test.</div>
            </div>
          </div>
        </div>
      </div>

    </div>
    <?php endif; ?>


    <!-- ========================================== -->
    <!-- TAB 5: WORKSHOP & SYSTEM PREFERENCES       -->
    <!-- ========================================== -->
    <?php if ($activeTab === 'workshop'): ?>
    <form method="POST" action="<?= url('/admin/settings/workshop') ?>" class="settings-form">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES) ?>" />

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px;">

        <!-- Localization & Currency Card -->
        <div class="settings-card">
          <div class="settings-card-header">
            <i class="fas fa-coins"></i>
            <div>
              <h3>Currency &amp; Regional Formatting</h3>
              <p>Currency symbols, formats, and date representation</p>
            </div>
          </div>
          <div class="settings-card-body">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
              <div class="form-group">
                <label class="form-label" for="currency_symbol">Currency Symbol <span class="required">*</span></label>
                <input type="text" id="currency_symbol" name="currency_symbol" class="form-control" value="<?= htmlspecialchars($settings['currency_symbol'] ?? '₹', ENT_QUOTES) ?>" required />
                <small class="form-hint">e.g. ₹ or $ or €</small>
              </div>
              <div class="form-group">
                <label class="form-label" for="currency_code">Currency ISO Code</label>
                <input type="text" id="currency_code" name="currency_code" class="form-control" value="<?= htmlspecialchars($settings['currency_code'] ?? 'INR', ENT_QUOTES) ?>" />
                <small class="form-hint">e.g. INR or USD</small>
              </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
              <div class="form-group">
                <label class="form-label" for="date_format">Date Format</label>
                <select id="date_format" name="date_format" class="form-control">
                  <option value="d M Y" <?= ($settings['date_format'] ?? 'd M Y') === 'd M Y' ? 'selected' : '' ?>>21 Aug 2026 (d M Y)</option>
                  <option value="d/m/Y" <?= ($settings['date_format'] ?? '') === 'd/m/Y' ? 'selected' : '' ?>>21/08/2026 (d/m/Y)</option>
                  <option value="Y-m-d" <?= ($settings['date_format'] ?? '') === 'Y-m-d' ? 'selected' : '' ?>>2026-08-21 (ISO Y-m-d)</option>
                </select>
              </div>
              <div class="form-group">
                <label class="form-label" for="time_format">Time Format</label>
                <select id="time_format" name="time_format" class="form-control">
                  <option value="12" <?= ($settings['time_format'] ?? '12') === '12' ? 'selected' : '' ?>>12 Hour (02:30 PM)</option>
                  <option value="24" <?= ($settings['time_format'] ?? '') === '24' ? 'selected' : '' ?>>24 Hour (14:30)</option>
                </select>
              </div>
            </div>
          </div>
        </div>

        <!-- Repair Workflow & Warranty Card -->
        <div class="settings-card">
          <div class="settings-card-header">
            <i class="fas fa-clipboard-check"></i>
            <div>
              <h3>Repair Rules &amp; Warranty</h3>
              <p>Tracking ID generator rules and customer intake</p>
            </div>
          </div>
          <div class="settings-card-body">
            <div class="form-group">
              <label class="form-label" for="repair_tracking_prefix">Repair Tracking ID Prefix <span class="required">*</span></label>
              <div class="input-with-icon">
                <i class="fas fa-barcode"></i>
                <input type="text" id="repair_tracking_prefix" name="repair_tracking_prefix" class="form-control" value="<?= htmlspecialchars($settings['repair_tracking_prefix'] ?? 'AMN-LR', ENT_QUOTES) ?>" required />
              </div>
              <small class="form-hint">Generated format: <code>AMN-LR-2026-XXXX</code></small>
            </div>

            <div class="form-group">
              <label class="form-label" for="default_warranty_days">Default Warranty (Days)</label>
              <div class="input-with-icon">
                <i class="fas fa-shield-alt"></i>
                <input type="number" id="default_warranty_days" name="default_warranty_days" class="form-control" value="<?= htmlspecialchars($settings['default_warranty_days'] ?? '90', ENT_QUOTES) ?>" min="0" />
              </div>
              <small class="form-hint">Standard default warranty issued for repair jobs.</small>
            </div>

            <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px; background: #f8fafc; border-radius: var(--radius-sm); border: 1px solid var(--border-color); margin-top: 12px;">
              <div>
                <strong style="color: var(--text-primary); font-size: 0.92rem;">Public Online Booking</strong>
                <p style="font-size: 0.8rem; color: var(--text-muted); margin: 0;">Allow customers to submit repair bookings online via website</p>
              </div>
              <label class="switch-toggle">
                <input type="checkbox" name="allow_customer_booking" value="1" <?= ($settings['allow_customer_booking'] ?? '1') === '1' ? 'checked' : '' ?> />
                <span class="switch-slider"></span>
              </label>
            </div>
          </div>
        </div>

      </div>

      <div class="settings-form-actions">
        <button type="submit" class="btn btn-primary" style="padding: 12px 28px; font-weight: 700; font-size: 1rem;">
          <i class="fas fa-save" style="margin-right: 8px;"></i>Save Workshop Preferences
        </button>
      </div>
    </form>
    <?php endif; ?>

    <!-- ========================================== -->
    <!-- TAB 6: BILLING & DYNAMIC TEMPLATE DESIGNER -->
    <!-- ========================================== -->
    <?php if ($activeTab === 'billing'): ?>
    
    <!-- Top Action Bar -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 14px;">
      <div>
        <h2 style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary); margin: 0;">
          <i class="fas fa-file-invoice-dollar" style="color: var(--primary-color); margin-right: 8px;"></i>Billing Setup &amp; Dynamic Template Engine
        </h2>
        <p style="color: var(--text-muted); font-size: 0.88rem; margin: 4px 0 0 0;">
          Configure GST tax rules, invoice numbering, instant UPI QR codes, and customize or design invoice templates dynamically.
        </p>
      </div>

      <div style="display: flex; gap: 10px;">
        <button type="button" onclick="openCreateTemplateModal()" class="btn btn-primary" style="font-size: 0.88rem; padding: 9px 16px; font-weight: 700;">
          <i class="fas fa-plus-circle" style="margin-right: 6px;"></i>Create Custom Template
        </button>
        <a href="<?= url('/admin/invoices') ?>" class="btn" style="background: #ffffff; border: 1px solid var(--border-color); color: var(--text-secondary); padding: 9px 16px; font-weight: 600; text-decoration: none;">
          <i class="fas fa-receipt" style="margin-right: 6px;"></i>View All Invoices
        </a>
      </div>
    </div>

    <!-- Section A: Template Designer Library Cards -->
    <div class="settings-card" style="margin-bottom: 28px;">
      <div class="settings-card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <div style="display: flex; align-items: center; gap: 12px;">
          <i class="fas fa-palette"></i>
          <div>
            <h3>Invoice Template Library &amp; Live Designer</h3>
            <p>Select your default invoice template, customize styling/colors, or test live renderings.</p>
          </div>
        </div>
        <span class="badge" style="background: rgba(37, 99, 235, 0.1); color: var(--primary-color); padding: 4px 10px; border-radius: 9999px; font-weight: 700; font-size: 0.78rem;">
          <?= count($templates ?? []) ?> Available Templates
        </span>
      </div>
      <div class="settings-card-body">
        
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
          <?php foreach ($templates as $tpl): 
            $isDefault = ($settings['billing_default_template'] ?? 'modern') === $tpl['template_key'];
          ?>
          <div class="template-card <?= $isDefault ? 'is-active' : '' ?>" style="border: 2px solid <?= $isDefault ? 'var(--primary-color)' : 'var(--border-color)' ?>; border-radius: var(--radius-sm); overflow: hidden; background: #ffffff; display: flex; flex-direction: column; transition: all var(--transition-speed); position: relative;">
            
            <!-- Card Header / Visual Accent Bar -->
            <div style="height: 8px; background: <?= htmlspecialchars($tpl['accent_color'], ENT_QUOTES) ?>;"></div>
            
            <div style="padding: 16px; flex: 1; display: flex; flex-direction: column;">
              <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px;">
                <div style="font-weight: 800; font-size: 1rem; color: var(--text-primary);"><?= htmlspecialchars($tpl['name'], ENT_QUOTES) ?></div>
                <?php if ($isDefault): ?>
                <span style="background: #ecfdf5; color: #059669; font-size: 0.72rem; font-weight: 800; padding: 2px 8px; border-radius: 9999px; border: 1px solid #a7f3d0; text-transform: uppercase;">
                  Active Default
                </span>
                <?php endif; ?>
              </div>

              <p style="font-size: 0.8rem; color: var(--text-muted); line-height: 1.4; margin: 0 0 14px 0; flex: 1;">
                <?= htmlspecialchars($tpl['description'] ?? 'Dynamic invoice template', ENT_QUOTES) ?>
              </p>

              <div style="display: flex; gap: 6px; flex-wrap: wrap; margin-bottom: 16px; font-size: 0.74rem;">
                <span style="background: #f1f5f9; color: #475569; padding: 2px 8px; border-radius: 4px; font-weight: 600;">
                  <i class="fas fa-file"></i> <?= htmlspecialchars(strtoupper($tpl['paper_size']), ENT_QUOTES) ?>
                </span>
                <span style="background: #f1f5f9; color: #475569; padding: 2px 8px; border-radius: 4px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                  <span style="width: 10px; height: 10px; border-radius: 50%; background: <?= htmlspecialchars($tpl['accent_color'], ENT_QUOTES) ?>; display: inline-block;"></span>
                  <?= htmlspecialchars($tpl['accent_color'], ENT_QUOTES) ?>
                </span>
                <?php if (!empty($tpl['is_system'])): ?>
                <span style="background: #eff6ff; color: #2563eb; padding: 2px 8px; border-radius: 4px; font-weight: 600;">
                  Built-in System
                </span>
                <?php else: ?>
                <span style="background: #fdf4ff; color: #c026d3; padding: 2px 8px; border-radius: 4px; font-weight: 600;">
                  Custom Dynamic
                </span>
                <?php endif; ?>
              </div>

              <!-- Template Action Buttons -->
              <div style="display: flex; gap: 8px; border-top: 1px solid var(--border-color); padding-top: 12px;">
                <button type="button" onclick="previewTemplateLive('<?= htmlspecialchars($tpl['template_key'], ENT_QUOTES) ?>', '<?= htmlspecialchars($tpl['accent_color'], ENT_QUOTES) ?>', '<?= htmlspecialchars($tpl['secondary_color'], ENT_QUOTES) ?>', '<?= htmlspecialchars($tpl['font_family'], ENT_QUOTES) ?>', '<?= htmlspecialchars($tpl['paper_size'], ENT_QUOTES) ?>')" class="btn btn-sm" style="flex: 1; background: #f8fafc; border: 1px solid var(--border-color); color: var(--text-primary); font-size: 0.8rem; font-weight: 700; display: inline-flex; align-items: center; justify-content: center; gap: 5px;">
                  <i class="fas fa-eye"></i> Live Preview
                </button>
                <button type="button" onclick="openEditTemplateModal(<?= htmlspecialchars(json_encode($tpl), ENT_QUOTES) ?>)" class="btn btn-sm" style="background: rgba(37, 99, 235, 0.08); color: var(--primary-color); border: 1px solid rgba(37, 99, 235, 0.2); font-size: 0.8rem; font-weight: 700; padding: 6px 12px;">
                  <i class="fas fa-sliders-h"></i> Customize
                </button>
                <?php if (empty($tpl['is_system'])): ?>
                <form method="POST" action="<?= url('/admin/settings/templates/' . $tpl['id'] . '/delete') ?>" onsubmit="return confirm('Delete this custom template?');" style="display: inline;">
                  <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES) ?>" />
                  <button type="submit" class="btn btn-sm" style="background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5; padding: 6px 10px;" title="Delete Custom Template">
                    <i class="fas fa-trash-alt"></i>
                  </button>
                </form>
                <?php endif; ?>
              </div>

            </div>
          </div>
          <?php endforeach; ?>
        </div>

      </div>
    </div>

    <!-- Section B: Global Billing Configuration Form -->
    <form method="POST" action="<?= url('/admin/settings/billing') ?>" class="settings-form">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES) ?>" />

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px;">

        <!-- Card 1: Invoicing & Default Sequence -->
        <div class="settings-card">
          <div class="settings-card-header">
            <i class="fas fa-sort-numeric-up-alt"></i>
            <div>
              <h3>Invoice Numbering &amp; Defaults</h3>
              <p>Setup automatic numbering sequence and invoice timeline</p>
            </div>
          </div>
          <div class="settings-card-body">
            <div class="form-group">
              <label class="form-label" for="billing_invoice_prefix">Invoice Prefix Format <span class="required">*</span></label>
              <input type="text" id="billing_invoice_prefix" name="billing_invoice_prefix" class="form-control" value="<?= htmlspecialchars($settings['billing_invoice_prefix'] ?? 'INV-{year}-', ENT_QUOTES) ?>" required />
              <span class="form-hint">Supports tags: <code>{year}</code> (e.g. 2026), <code>{month}</code> (e.g. 08). Result: <strong><?= invoice_prefix() ?>1001</strong></span>
            </div>

            <div class="form-group">
              <label class="form-label" for="billing_next_number">Next Invoice Sequence Number <span class="required">*</span></label>
              <input type="number" id="billing_next_number" name="billing_next_number" class="form-control" value="<?= htmlspecialchars($settings['billing_next_number'] ?? '1001', ENT_QUOTES) ?>" min="1" required />
              <span class="form-hint">Auto-increments after each generated invoice.</span>
            </div>

            <div class="form-group">
              <label class="form-label" for="billing_default_template">Default Active Template <span class="required">*</span></label>
              <select id="billing_default_template" name="billing_default_template" class="form-control">
                <?php foreach ($templates as $tpl): ?>
                <option value="<?= htmlspecialchars($tpl['template_key'], ENT_QUOTES) ?>" <?= ($settings['billing_default_template'] ?? 'modern') === $tpl['template_key'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($tpl['name'], ENT_QUOTES) ?> (<?= strtoupper($tpl['paper_size']) ?>)
                </option>
                <?php endforeach; ?>
              </select>
              <span class="form-hint">Applied automatically when generating new invoices.</span>
            </div>

            <div class="form-group">
              <label class="form-label" for="billing_default_due_days">Default Payment Due Period (Days)</label>
              <input type="number" id="billing_default_due_days" name="billing_default_due_days" class="form-control" value="<?= htmlspecialchars($settings['billing_default_due_days'] ?? '7', ENT_QUOTES) ?>" min="0" />
              <span class="form-hint">Number of days from invoice issue date until due (e.g. 7 days).</span>
            </div>
          </div>
        </div>

        <!-- Card 2: GST & Tax Credentials -->
        <div class="settings-card">
          <div class="settings-card-header">
            <i class="fas fa-percentage"></i>
            <div>
              <h3>GST &amp; Tax Configuration</h3>
              <p>Manage tax rates, GSTIN registration, and tax names</p>
            </div>
          </div>
          <div class="settings-card-body">
            <div class="form-group">
              <div style="display: flex; align-items: center; justify-content: space-between; background: #f8fafc; padding: 12px 14px; border-radius: var(--radius-sm); border: 1px solid var(--border-color); margin-bottom: 12px;">
                <div>
                  <div style="font-weight: 700; font-size: 0.92rem; color: var(--text-primary);">Enable Tax Calculation</div>
                  <div style="font-size: 0.78rem; color: var(--text-muted);">Apply GST / Tax rate automatically on invoice items</div>
                </div>
                <label class="switch-toggle">
                  <input type="checkbox" name="billing_enable_tax" value="1" <?= ($settings['billing_enable_tax'] ?? '1') === '1' ? 'checked' : '' ?> />
                  <span class="switch-slider"></span>
                </label>
              </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
              <div class="form-group">
                <label class="form-label" for="billing_tax_name">Tax Name</label>
                <input type="text" id="billing_tax_name" name="billing_tax_name" class="form-control" value="<?= htmlspecialchars($settings['billing_tax_name'] ?? 'GST', ENT_QUOTES) ?>" placeholder="GST" />
              </div>
              <div class="form-group">
                <label class="form-label" for="billing_tax_rate">Default Tax Rate (%)</label>
                <input type="number" id="billing_tax_rate" name="billing_tax_rate" class="form-control" value="<?= htmlspecialchars($settings['billing_tax_rate'] ?? '18', ENT_QUOTES) ?>" step="0.01" min="0" placeholder="18.00" />
              </div>
            </div>

            <div class="form-group">
              <label class="form-label" for="billing_gst_number">GSTIN Number</label>
              <div class="input-with-icon">
                <i class="fas fa-id-card"></i>
                <input type="text" id="billing_gst_number" name="billing_gst_number" class="form-control" value="<?= htmlspecialchars($settings['billing_gst_number'] ?? '', ENT_QUOTES) ?>" placeholder="10AAACT0000A1Z5" style="text-transform: uppercase;" />
              </div>
              <span class="form-hint">Shown on official GST Tax Invoices.</span>
            </div>

            <div class="form-group">
              <label class="form-label" for="billing_pan_number">Business PAN Number</label>
              <div class="input-with-icon">
                <i class="fas fa-file-invoice"></i>
                <input type="text" id="billing_pan_number" name="billing_pan_number" class="form-control" value="<?= htmlspecialchars($settings['billing_pan_number'] ?? '', ENT_QUOTES) ?>" placeholder="AAACT0000A" style="text-transform: uppercase;" />
              </div>
            </div>
          </div>
        </div>

        <!-- Card 3: Bank Account & Instant UPI Gateway -->
        <div class="settings-card">
          <div class="settings-card-header">
            <i class="fas fa-university"></i>
            <div>
              <h3>Bank &amp; Instant UPI Payment Gateway</h3>
              <p>Control direct bank transfer account details and scan-and-pay UPI QR visibility on invoices</p>
            </div>
          </div>
          <div class="settings-card-body">
            
            <!-- 1. Direct Bank Transfer Enable/Disable Toggle -->
            <div style="display: flex; align-items: center; justify-content: space-between; background: #f8fafc; padding: 14px 16px; border-radius: var(--radius-sm); border: 1.5px solid var(--border-color); margin-bottom: 16px;">
              <div>
                <div style="font-weight: 800; font-size: 0.95rem; color: var(--text-primary); display: flex; align-items: center; gap: 8px;">
                  <i class="fas fa-money-check-alt" style="color: var(--primary-color);"></i>
                  <span>Reveal Direct Bank Transfer Account Details on Invoices</span>
                </div>
                <div style="font-size: 0.78rem; color: var(--text-muted); margin-top: 2px;">
                  Enable or disable revealing your Bank Name, A/C Number, IFSC Code, and Branch on all printed and PDF invoices (NEFT / IMPS / RTGS).
                </div>
              </div>
              <label class="switch-toggle" title="Toggle Direct Bank Transfer Details">
                <input type="checkbox" id="billing_show_bank_details" name="billing_show_bank_details" value="1" <?= ($settings['billing_show_bank_details'] ?? '1') === '1' ? 'checked' : '' ?> onchange="toggleBankDetailsSection(this.checked)" />
                <span class="switch-slider"></span>
              </label>
            </div>

            <!-- Bank Details Inputs Wrapper -->
            <div id="bank-details-wrapper" style="background: #ffffff; border: 1px solid var(--border-color); padding: 16px; border-radius: var(--radius-sm); margin-bottom: 20px; transition: opacity 0.2s, max-height 0.3s; <?= ($settings['billing_show_bank_details'] ?? '1') === '1' ? '' : 'opacity: 0.55;' ?>">
              <div style="font-size: 0.78rem; font-weight: 800; color: var(--primary-color); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 12px; display: flex; align-items: center; gap: 6px;">
                <i class="fas fa-university"></i> Bank Account Information (NEFT / IMPS / RTGS)
              </div>
              
              <div class="form-group">
                <label class="form-label" for="billing_bank_name">Bank Name</label>
                <input type="text" id="billing_bank_name" name="billing_bank_name" class="form-control" value="<?= htmlspecialchars($settings['billing_bank_name'] ?? '', ENT_QUOTES) ?>" placeholder="State Bank of India" />
              </div>

              <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div class="form-group">
                  <label class="form-label" for="billing_bank_account">Account Number</label>
                  <input type="text" id="billing_bank_account" name="billing_bank_account" class="form-control" value="<?= htmlspecialchars($settings['billing_bank_account'] ?? '', ENT_QUOTES) ?>" placeholder="389201948201" />
                </div>
                <div class="form-group">
                  <label class="form-label" for="billing_bank_ifsc">IFSC Code</label>
                  <input type="text" id="billing_bank_ifsc" name="billing_bank_ifsc" class="form-control" value="<?= htmlspecialchars($settings['billing_bank_ifsc'] ?? '', ENT_QUOTES) ?>" placeholder="SBIN0001234" style="text-transform: uppercase;" />
                </div>
              </div>

              <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" for="billing_bank_branch">Branch Name / Address</label>
                <input type="text" id="billing_bank_branch" name="billing_bank_branch" class="form-control" value="<?= htmlspecialchars($settings['billing_bank_branch'] ?? '', ENT_QUOTES) ?>" placeholder="Saharsa Main Branch, Bihar" />
              </div>
            </div>

            <!-- 2. Instant UPI QR Code Enable/Disable Toggle -->
            <div style="display: flex; align-items: center; justify-content: space-between; background: #f8fafc; padding: 14px 16px; border-radius: var(--radius-sm); border: 1.5px solid var(--border-color); margin-bottom: 16px;">
              <div>
                <div style="font-weight: 800; font-size: 0.95rem; color: var(--text-primary); display: flex; align-items: center; gap: 8px;">
                  <i class="fas fa-qrcode" style="color: #059669;"></i>
                  <span>Enable Instant Scan-and-Pay UPI QR Code on Invoices</span>
                </div>
                <div style="font-size: 0.78rem; color: var(--text-muted); margin-top: 2px;">
                  Generates dynamic, real-time UPI QR codes with pre-filled balance due amount for GPay, PhonePe, Paytm, and BHIM.
                </div>
              </div>
              <label class="switch-toggle" title="Toggle UPI QR Code">
                <input type="checkbox" id="billing_show_upi_qr" name="billing_show_upi_qr" value="1" <?= ($settings['billing_show_upi_qr'] ?? '1') === '1' ? 'checked' : '' ?> onchange="toggleUpiDetailsSection(this.checked)" />
                <span class="switch-slider"></span>
              </label>
            </div>

            <!-- UPI Details Inputs Wrapper -->
            <div id="upi-details-wrapper" style="background: #ffffff; border: 1px solid var(--border-color); padding: 16px; border-radius: var(--radius-sm); transition: opacity 0.2s; <?= ($settings['billing_show_upi_qr'] ?? '1') === '1' ? '' : 'opacity: 0.55;' ?>">
              <div class="form-group">
                <label class="form-label" for="billing_upi_id">Workshop UPI ID (VPA) <span class="required">*</span></label>
                <div class="input-with-icon">
                  <i class="fas fa-qrcode"></i>
                  <input type="text" id="billing_upi_id" name="billing_upi_id" class="form-control" value="<?= htmlspecialchars($settings['billing_upi_id'] ?? 'techfix@sbi', ENT_QUOTES) ?>" placeholder="techfix@sbi" />
                </div>
                <span class="form-hint">Used to dynamically generate scan &amp; pay QR codes.</span>
              </div>

              <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" for="billing_upi_payee_name">UPI Payee Display Name</label>
                <input type="text" id="billing_upi_payee_name" name="billing_upi_payee_name" class="form-control" value="<?= htmlspecialchars($settings['billing_upi_payee_name'] ?? 'TechFix Laptop Repair Center', ENT_QUOTES) ?>" />
              </div>
            </div>

          </div>
        </div>

        <!-- Card 4: Default Terms & Notes -->
        <div class="settings-card">
          <div class="settings-card-header">
            <i class="fas fa-file-contract"></i>
            <div>
              <h3>Default Terms &amp; Customer Notes</h3>
              <p>Default policies and warranty declarations printed on invoices</p>
            </div>
          </div>
          <div class="settings-card-body">
            <div class="form-group">
              <label class="form-label" for="billing_default_notes">Default Customer Note</label>
              <textarea id="billing_default_notes" name="billing_default_notes" rows="3" class="form-control" placeholder="Thank you for choosing TechFix..."><?= htmlspecialchars($settings['billing_default_notes'] ?? '', ENT_QUOTES) ?></textarea>
              <span class="form-hint">Displayed under the line items on printed invoices.</span>
            </div>

            <div class="form-group">
              <label class="form-label" for="billing_default_terms">Default Terms &amp; Conditions / Warranty Policy</label>
              <textarea id="billing_default_terms" name="billing_default_terms" rows="7" class="form-control" style="font-size: 0.85rem; line-height: 1.5;"><?= htmlspecialchars($settings['billing_default_terms'] ?? '', ENT_QUOTES) ?></textarea>
              <span class="form-hint">Standard legal warranty declaration printed in invoice footer.</span>
            </div>
          </div>
        </div>

      </div>

      <div class="settings-form-actions">
        <button type="submit" class="btn btn-primary" style="padding: 12px 28px; font-weight: 700; font-size: 1rem;">
          <i class="fas fa-save" style="margin-right: 8px;"></i>Save All Billing &amp; Invoice Settings
        </button>
      </div>
    </form>

    <!-- Modal 1: Live Interactive Template Preview Modal -->
    <div id="modal-template-preview" class="settings-modal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15, 23, 42, 0.75); z-index: 9999; backdrop-filter: blur(4px); align-items: center; justify-content: center; padding: 20px;">
      <div style="background: #ffffff; border-radius: 12px; max-width: 960px; width: 100%; max-height: 92vh; display: flex; flex-direction: column; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
        
        <div style="padding: 16px 24px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; background: #f8fafc;">
          <div style="display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-eye" style="color: var(--primary-color); font-size: 1.2rem;"></i>
            <h3 style="font-size: 1.1rem; font-weight: 800; color: var(--text-primary); margin: 0;">Live Dynamic Template Preview</h3>
          </div>
          <button type="button" onclick="closeTemplatePreviewModal()" style="background: none; border: none; font-size: 1.2rem; cursor: pointer; color: #64748b;">
            <i class="fas fa-times"></i>
          </button>
        </div>

        <div id="preview-iframe-container" style="flex: 1; overflow-y: auto; padding: 24px; background: #f1f5f9;">
          <div id="preview-loading-spinner" style="text-align: center; padding: 60px 0;">
            <i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: var(--primary-color);"></i>
            <div style="margin-top: 12px; font-weight: 600; color: #64748b;">Generating live dynamic template preview...</div>
          </div>
          <div id="preview-content-box"></div>
        </div>

        <div style="padding: 14px 24px; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; gap: 10px; background: #ffffff;">
          <button type="button" onclick="closeTemplatePreviewModal()" class="btn btn-secondary">Close Preview</button>
        </div>
      </div>
    </div>

    <!-- Modal 2: Dynamic Template Creator / Editor Modal -->
    <div id="modal-template-editor" class="settings-modal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15, 23, 42, 0.75); z-index: 9999; backdrop-filter: blur(4px); align-items: center; justify-content: center; padding: 20px;">
      <div style="background: #ffffff; border-radius: 12px; max-width: 820px; width: 100%; max-height: 92vh; display: flex; flex-direction: column; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
        
        <form method="POST" action="<?= url('/admin/settings/templates/save') ?>" style="display: flex; flex-direction: column; flex: 1; overflow: hidden;">
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES) ?>" />
          <input type="hidden" id="tpl-edit-id" name="template_id" value="0" />

          <div style="padding: 16px 24px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; background: #f8fafc;">
            <div style="display: flex; align-items: center; gap: 10px;">
              <i class="fas fa-sliders-h" style="color: var(--primary-color); font-size: 1.2rem;"></i>
              <h3 id="tpl-modal-title" style="font-size: 1.1rem; font-weight: 800; color: var(--text-primary); margin: 0;">Customize Invoice Template</h3>
            </div>
            <button type="button" onclick="closeTemplateEditorModal()" style="background: none; border: none; font-size: 1.2rem; cursor: pointer; color: #64748b;">
              <i class="fas fa-times"></i>
            </button>
          </div>

          <div style="flex: 1; overflow-y: auto; padding: 24px;">
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
              <div class="form-group">
                <label class="form-label" for="tpl-name">Template Name <span class="required">*</span></label>
                <input type="text" id="tpl-name" name="name" class="form-control" required placeholder="e.g. Clean Modern Indigo" />
              </div>
              <div class="form-group">
                <label class="form-label" for="tpl-key">Template Key / Slug</label>
                <input type="text" id="tpl-key" name="template_key" class="form-control" placeholder="e.g. modern_indigo" style="font-family: monospace;" />
                <span class="form-hint">Lowercase alphanumeric characters only.</span>
              </div>
            </div>

            <div class="form-group">
              <label class="form-label" for="tpl-description">Short Description</label>
              <input type="text" id="tpl-description" name="description" class="form-control" placeholder="Brief note about the design layout and use case" />
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; margin-bottom: 16px;">
              <div class="form-group">
                <label class="form-label" for="tpl-accent">Primary Accent Color</label>
                <div style="display: flex; gap: 8px; align-items: center;">
                  <input type="color" id="tpl-accent-picker" style="width: 40px; height: 38px; border: 1px solid var(--border-color); border-radius: 4px; cursor: pointer; padding: 2px;" onchange="document.getElementById('tpl-accent').value = this.value" />
                  <input type="text" id="tpl-accent" name="accent_color" class="form-control" value="#2563EB" oninput="document.getElementById('tpl-accent-picker').value = this.value" />
                </div>
              </div>

              <div class="form-group">
                <label class="form-label" for="tpl-secondary">Secondary Header Color</label>
                <div style="display: flex; gap: 8px; align-items: center;">
                  <input type="color" id="tpl-secondary-picker" style="width: 40px; height: 38px; border: 1px solid var(--border-color); border-radius: 4px; cursor: pointer; padding: 2px;" onchange="document.getElementById('tpl-secondary').value = this.value" />
                  <input type="text" id="tpl-secondary" name="secondary_color" class="form-control" value="#0F172A" oninput="document.getElementById('tpl-secondary-picker').value = this.value" />
                </div>
              </div>

              <div class="form-group">
                <label class="form-label" for="tpl-paper-size">Paper Size Target</label>
                <select id="tpl-paper-size" name="paper_size" class="form-control">
                  <option value="A4">A4 Standard Sheet (210mm × 297mm)</option>
                  <option value="Letter">US Letter (8.5in × 11in)</option>
                  <option value="80mm_pos">80mm Thermal POS Roll Receipt</option>
                </select>
              </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
              <div class="form-group">
                <label class="form-label" for="tpl-font">Font Family</label>
                <select id="tpl-font" name="font_family" class="form-control">
                  <option value="Inter, sans-serif">Inter (Modern Clean Sans)</option>
                  <option value="system-ui, -apple-system, sans-serif">System UI Default</option>
                  <option value="'Courier New', Courier, monospace">Monospace / Typewriter (POS)</option>
                  <option value="Georgia, serif">Georgia (Classic Serif)</option>
                </select>
              </div>

              <div class="form-group">
                <label class="form-label" for="tpl-watermark-text">Watermark Stamp Text</label>
                <input type="text" id="tpl-watermark-text" name="watermark_text" class="form-control" value="PAID" placeholder="PAID" />
              </div>
            </div>

            <!-- Toggles Grid -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 12px; background: #f8fafc; padding: 16px; border-radius: var(--radius-sm); border: 1px solid var(--border-color); margin-bottom: 16px;">
              <label style="display: flex; align-items: center; gap: 8px; font-size: 0.88rem; font-weight: 600; cursor: pointer;">
                <input type="checkbox" id="tpl-watermark" name="show_watermark" value="1" checked /> Show PAID Watermark
              </label>
              <label style="display: flex; align-items: center; gap: 8px; font-size: 0.88rem; font-weight: 600; cursor: pointer;">
                <input type="checkbox" id="tpl-qr" name="show_qr_code" value="1" checked /> Show Instant UPI QR
              </label>
              <label style="display: flex; align-items: center; gap: 8px; font-size: 0.88rem; font-weight: 600; cursor: pointer;">
                <input type="checkbox" id="tpl-sig" name="show_signature" value="1" checked /> Show Signature Box
              </label>
              <label style="display: flex; align-items: center; gap: 8px; font-size: 0.88rem; font-weight: 600; cursor: pointer;">
                <input type="checkbox" id="tpl-tax" name="show_tax_breakup" value="1" checked /> Show GST Tax Breakdown
              </label>
              <label style="display: flex; align-items: center; gap: 8px; font-size: 0.88rem; font-weight: 600; cursor: pointer;">
                <input type="checkbox" id="tpl-bank" name="show_bank_details" value="1" checked /> Show Bank Details
              </label>
              <label style="display: flex; align-items: center; gap: 8px; font-size: 0.88rem; font-weight: 600; cursor: pointer;">
                <input type="checkbox" id="tpl-active" name="is_active" value="1" checked /> Template is Active
              </label>
            </div>

            <!-- Custom CSS Editor -->
            <div class="form-group">
              <label class="form-label" for="tpl-custom-css">Custom CSS Overrides (Optional)</label>
              <textarea id="tpl-custom-css" name="custom_css" rows="4" class="form-control code-editor" placeholder="/* Custom CSS rules for this invoice template */&#10;.invoice-custom .invoice-header { background: #1e1b4b; }"></textarea>
              <span class="form-hint">Directly injected into invoice rendering for pixel-perfect brand customization.</span>
            </div>

          </div>

          <div style="padding: 14px 24px; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; gap: 10px; background: #f8fafc;">
            <button type="button" onclick="closeTemplateEditorModal()" class="btn btn-secondary">Cancel</button>
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save" style="margin-right: 6px;"></i>Save Dynamic Template
            </button>
          </div>
        </form>

      </div>
    </div>

    <?php endif; ?>


  </div>
</div>

<!-- Extra CSS for System Manager -->
<style>
.settings-tab-btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 16px 20px;
  font-weight: 600;
  font-size: 0.92rem;
  color: var(--text-secondary);
  text-decoration: none;
  border-bottom: 3px solid transparent;
  transition: all var(--transition-speed);
  white-space: nowrap;
}
.settings-tab-btn:hover {
  color: var(--primary-color);
  background: rgba(37, 99, 235, 0.04);
}
.settings-tab-btn.active {
  color: var(--primary-color);
  border-bottom-color: var(--primary-color);
  background: #ffffff;
  font-weight: 700;
}
.settings-card {
  background: #ffffff;
  border: 1px solid var(--border-color);
  border-radius: var(--radius-sm);
  overflow: hidden;
  box-shadow: var(--shadow-xs);
}
.settings-card-header {
  padding: 16px 20px;
  background: #f8fafc;
  border-bottom: 1px solid var(--border-color);
  display: flex;
  align-items: center;
  gap: 12px;
}
.settings-card-header i {
  font-size: 1.25rem;
  color: var(--primary-color);
}
.settings-card-header h3 {
  font-size: 1.05rem;
  font-weight: 700;
  color: var(--text-primary);
  margin: 0;
}
.settings-card-header p {
  font-size: 0.8rem;
  color: var(--text-muted);
  margin: 2px 0 0 0;
}
.settings-card-body {
  padding: 20px;
}
.form-group {
  margin-bottom: 16px;
}
.form-label {
  display: block;
  font-size: 0.88rem;
  font-weight: 600;
  color: var(--text-primary);
  margin-bottom: 6px;
}
.required {
  color: #ef4444;
}
.form-control {
  width: 100%;
  padding: 10px 14px;
  border: 1px solid var(--border-color);
  border-radius: var(--radius-sm);
  font-size: 0.92rem;
  color: var(--text-primary);
  background: #ffffff;
  box-sizing: border-box;
  font-family: inherit;
  transition: border-color var(--transition-speed), box-shadow var(--transition-speed);
}
.form-control:focus {
  outline: none;
  border-color: var(--primary-color);
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
}
.form-hint {
  display: block;
  font-size: 0.78rem;
  color: var(--text-muted);
  margin-top: 4px;
}
.input-with-icon {
  position: relative;
}
.input-with-icon i {
  position: absolute;
  left: 14px;
  top: 50%;
  transform: translateY(-50%);
  color: #94a3b8;
  font-size: 0.9rem;
}
.input-with-icon .form-control {
  padding-left: 38px;
}
.code-editor {
  font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
  font-size: 0.85rem;
  background: #0f172a;
  color: #38bdf8;
  line-height: 1.45;
}
.code-editor:focus {
  background: #020617;
  color: #7dd3fc;
}
.settings-form-actions {
  margin-top: 28px;
  padding-top: 20px;
  border-top: 1px solid var(--border-color);
  display: flex;
  justify-content: flex-end;
}
.branding-card {
  background: #ffffff;
  border: 1px solid var(--border-color);
  border-radius: var(--radius-sm);
  overflow: hidden;
  box-shadow: var(--shadow-xs);
  display: flex;
  flex-direction: column;
}
.branding-card-header {
  padding: 14px 16px;
  background: #f8fafc;
  border-bottom: 1px solid var(--border-color);
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.branding-card-header h4 {
  font-size: 0.92rem;
  font-weight: 700;
  color: var(--text-primary);
  margin: 0;
  display: flex;
  align-items: center;
  gap: 6px;
}
.branding-preview-box {
  background: #f8fafc;
  height: 120px;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 16px;
  border-bottom: 1px dashed var(--border-color);
}
.branding-preview-box.dark-bg {
  background: #0b132b;
}
.branding-card-body {
  padding: 16px;
  display: flex;
  flex-direction: column;
}
.btn-file-upload {
  background: #ffffff;
  border: 1px dashed var(--primary-color);
  color: var(--primary-color);
  padding: 10px 14px;
  border-radius: var(--radius-sm);
  font-weight: 600;
  font-size: 0.88rem;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  cursor: pointer;
  transition: all var(--transition-speed);
}
.btn-file-upload:hover {
  background: rgba(37, 99, 235, 0.05);
}
.btn-file-upload input[type="file"] {
  display: none;
}
.btn-reset-asset {
  margin-top: 10px;
  background: none;
  border: none;
  color: #ef4444;
  font-size: 0.8rem;
  font-weight: 600;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 4px;
}
.btn-reset-asset:hover {
  text-decoration: underline;
}
.terminal-box {
  background: #0b132b;
  color: #e2e8f0;
  font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
  font-size: 0.82rem;
  padding: 14px;
  border-radius: var(--radius-sm);
  max-height: 240px;
  overflow-y: auto;
  line-height: 1.5;
  border: 1px solid rgba(255,255,255,0.1);
}
.switch-toggle {
  position: relative;
  display: inline-block;
  width: 44px;
  height: 24px;
}
.switch-toggle input {
  opacity: 0;
  width: 0;
  height: 0;
}
.switch-slider {
  position: absolute;
  cursor: pointer;
  top: 0; left: 0; right: 0; bottom: 0;
  background-color: #cbd5e1;
  transition: .24s;
  border-radius: 24px;
}
.switch-slider:before {
  position: absolute;
  content: "";
  height: 18px;
  width: 18px;
  left: 3px;
  bottom: 3px;
  background-color: white;
  transition: .24s;
  border-radius: 50%;
  box-shadow: 0 1px 3px rgba(0,0,0,0.2);
}
input:checked + .switch-slider {
  background-color: var(--primary-color);
}
input:checked + .switch-slider:before {
  transform: translateX(20px);
}
</style>

<!-- System Manager JavaScript Logic -->
<script>
// Live Image File Previewer
function previewBrandingImage(input, imgElementId) {
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = function(e) {
      const img = document.getElementById(imgElementId);
      if (img) {
        img.src = e.target.result;
      }
    };
    reader.readAsDataURL(input.files[0]);
  }
}

// Reset Branding Asset Helper
function resetBrandingAsset(assetKey) {
  if (confirm("Are you sure you want to reset this asset back to the default image?")) {
    document.getElementById('reset-asset-key').value = assetKey;
    document.getElementById('reset-asset-form').submit();
  }
}

// Password show/hide toggle
function togglePasswordVisibility(inputId, btn) {
  const input = document.getElementById(inputId);
  if (!input) return;
  const isPass = input.type === 'password';
  input.type = isPass ? 'text' : 'password';
  btn.querySelector('i').className = isPass ? 'fas fa-eye-slash' : 'fas fa-eye';
}

// Mail driver UI updater
function toggleMailDriverFields(driver) {
  const wrapper = document.getElementById('smtp-fields-wrapper');
  if (!wrapper) return;
  if (driver === 'log') {
    wrapper.style.opacity = '0.5';
  } else {
    wrapper.style.opacity = '1';
  }
}

// Clear Diagnostic Logs
function clearTestLogs() {
  const consoleEl = document.getElementById('smtp-log-console');
  if (consoleEl) {
    consoleEl.innerHTML = '<div style="color: #64748b;">Logs cleared.</div>';
  }
}

// Execute Live AJAX SMTP Diagnostic Test
async function executeTestEmail(e) {
  e.preventDefault();
  const form = document.getElementById('test-email-form');
  const btn = document.getElementById('btn-send-test-mail');
  const consoleEl = document.getElementById('smtp-log-console');
  const testEmail = document.getElementById('test_email').value;

  if (!testEmail) {
    alert('Please enter a recipient email address.');
    return;
  }

  // Get current form values from the SMTP form on the left to test live un-saved changes if desired
  const smtpForm = document.getElementById('smtp-settings-form');
  const formData = new FormData(smtpForm);
  formData.append('test_email', testEmail);

  btn.disabled = true;
  btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Running SMTP Diagnostic Test...';
  consoleEl.innerHTML = '<div style="color: #38bdf8;">⏳ Connecting to SMTP socket server and executing RFC 5321 handshake...</div>';

  try {
    const response = await fetch('<?= url('/admin/settings/mail/test') ?>', {
      method: 'POST',
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
      },
      body: formData
    });

    const data = await response.json();
    let logHtml = '';

    if (data.logs && Array.isArray(data.logs)) {
      data.logs.forEach(log => {
        let color = '#e2e8f0';
        if (log.startsWith('✅') || log.startsWith('🎉')) color = '#4ade80';
        else if (log.startsWith('❌')) color = '#f87171';
        else if (log.startsWith('🔌') || log.startsWith('🔒')) color = '#38bdf8';
        else if (log.startsWith('📤')) color = '#fcd34d';
        else if (log.startsWith('📥')) color = '#a78bfa';

        logHtml += `<div style="color: ${color}; margin-bottom: 2px;">${log}</div>`;
      });
    }

    if (data.success) {
      logHtml += `<div style="color: #4ade80; font-weight: bold; margin-top: 8px;">🎉 ${data.message}</div>`;
    } else {
      logHtml += `<div style="color: #f87171; font-weight: bold; margin-top: 8px;">❌ ${data.message}</div>`;
    }

    consoleEl.innerHTML = logHtml;
    consoleEl.scrollTop = consoleEl.scrollHeight;

  } catch (err) {
    consoleEl.innerHTML = `<div style="color: #f87171;">❌ Network / Server Error: ${err.message}</div>`;
  } finally {
    btn.disabled = false;
    btn.innerHTML = '<i class="fas fa-paper-plane"></i> Send Diagnostic Test Email';
  }
}

// Live SEO snippet updater & Character counters
document.addEventListener('DOMContentLoaded', () => {
  const metaTitleInput = document.getElementById('meta_title');
  const metaDescInput  = document.getElementById('meta_description');
  const serpTitle      = document.getElementById('serp-title-preview');
  const serpDesc       = document.getElementById('serp-desc-preview');
  const titleCount     = document.getElementById('meta_title_count');
  const descCount      = document.getElementById('meta_desc_count');

  if (metaTitleInput && serpTitle) {
    const updateTitle = () => {
      const val = metaTitleInput.value.trim() || 'TechFix — Professional Laptop Repair Center';
      serpTitle.textContent = val;
      if (titleCount) titleCount.textContent = `${metaTitleInput.value.length}/60`;
    };
    metaTitleInput.addEventListener('input', updateTitle);
    updateTitle();
  }

  if (metaDescInput && serpDesc) {
    const updateDesc = () => {
      const val = metaDescInput.value.trim() || 'Expert laptop repairs in Saharsa, Bihar with 90-day warranty.';
      serpDesc.textContent = val;
      if (descCount) descCount.textContent = `${metaDescInput.value.length}/160`;
    };
    metaDescInput.addEventListener('input', updateDesc);
    updateDesc();
  }
});

// ==========================================
// DYNAMIC TEMPLATE DESIGNER JAVASCRIPT
// ==========================================

function openCreateTemplateModal() {
  document.getElementById('tpl-modal-title').textContent = 'Create New Dynamic Template';
  document.getElementById('tpl-edit-id').value = '0';
  document.getElementById('tpl-name').value = '';
  document.getElementById('tpl-key').value = '';
  document.getElementById('tpl-key').readOnly = false;
  document.getElementById('tpl-description').value = '';
  document.getElementById('tpl-accent').value = '#2563EB';
  document.getElementById('tpl-accent-picker').value = '#2563EB';
  document.getElementById('tpl-secondary').value = '#0F172A';
  document.getElementById('tpl-secondary-picker').value = '#0F172A';
  document.getElementById('tpl-paper-size').value = 'A4';
  document.getElementById('tpl-font').value = 'Inter, sans-serif';
  document.getElementById('tpl-watermark-text').value = 'PAID';
  document.getElementById('tpl-watermark').checked = true;
  document.getElementById('tpl-qr').checked = true;
  document.getElementById('tpl-sig').checked = true;
  document.getElementById('tpl-tax').checked = true;
  document.getElementById('tpl-bank').checked = true;
  document.getElementById('tpl-active').checked = true;
  document.getElementById('tpl-custom-css').value = '';

  const modal = document.getElementById('modal-template-editor');
  modal.style.display = 'flex';
}

function openEditTemplateModal(tpl) {
  document.getElementById('tpl-modal-title').textContent = `Customize Template: ${tpl.name}`;
  document.getElementById('tpl-edit-id').value = tpl.id;
  document.getElementById('tpl-name').value = tpl.name || '';
  document.getElementById('tpl-key').value = tpl.template_key || '';
  document.getElementById('tpl-key').readOnly = true;
  document.getElementById('tpl-description').value = tpl.description || '';
  document.getElementById('tpl-accent').value = tpl.accent_color || '#2563EB';
  document.getElementById('tpl-accent-picker').value = tpl.accent_color || '#2563EB';
  document.getElementById('tpl-secondary').value = tpl.secondary_color || '#0F172A';
  document.getElementById('tpl-secondary-picker').value = tpl.secondary_color || '#0F172A';
  document.getElementById('tpl-paper-size').value = tpl.paper_size || 'A4';
  document.getElementById('tpl-font').value = tpl.font_family || 'Inter, sans-serif';
  document.getElementById('tpl-watermark-text').value = tpl.watermark_text || 'PAID';
  document.getElementById('tpl-watermark').checked = tpl.show_watermark == 1;
  document.getElementById('tpl-qr').checked = tpl.show_qr_code == 1;
  document.getElementById('tpl-sig').checked = tpl.show_signature == 1;
  document.getElementById('tpl-tax').checked = tpl.show_tax_breakup == 1;
  document.getElementById('tpl-bank').checked = tpl.show_bank_details == 1;
  document.getElementById('tpl-active').checked = tpl.is_active == 1;
  document.getElementById('tpl-custom-css').value = tpl.custom_css || '';

  const modal = document.getElementById('modal-template-editor');
  modal.style.display = 'flex';
}

function closeTemplateEditorModal() {
  document.getElementById('modal-template-editor').style.display = 'none';
}

async function previewTemplateLive(templateKey, accentColor, secondaryColor, fontFamily, paperSize) {
  const modal = document.getElementById('modal-template-preview');
  const spinner = document.getElementById('preview-loading-spinner');
  const contentBox = document.getElementById('preview-content-box');

  modal.style.display = 'flex';
  spinner.style.display = 'block';
  contentBox.innerHTML = '';

  try {
    const formData = new FormData();
    formData.append('csrf_token', '<?= htmlspecialchars($csrfToken, ENT_QUOTES) ?>');
    formData.append('template_key', templateKey);
    formData.append('accent_color', accentColor);
    formData.append('secondary_color', secondaryColor);
    formData.append('font_family', fontFamily);
    formData.append('paper_size', paperSize);

    const response = await fetch('<?= url('/admin/settings/templates/preview') ?>', {
      method: 'POST',
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: formData
    });

    const html = await response.text();
    spinner.style.display = 'none';
    contentBox.innerHTML = html;

  } catch (err) {
    spinner.style.display = 'none';
    contentBox.innerHTML = `<div style="color: #ef4444; padding: 20px; font-weight: bold;">Error loading preview: ${err.message}</div>`;
  }
}

function toggleBankDetailsSection(enabled) {
  const box = document.getElementById('bank-details-wrapper');
  if (box) {
    box.style.opacity = enabled ? '1' : '0.55';
    const inputs = box.querySelectorAll('input');
    inputs.forEach(inp => {
      inp.disabled = !enabled;
    });
  }
}

function toggleUpiDetailsSection(enabled) {
  const box = document.getElementById('upi-details-wrapper');
  if (box) {
    box.style.opacity = enabled ? '1' : '0.55';
    const inputs = box.querySelectorAll('input');
    inputs.forEach(inp => {
      inp.disabled = !enabled;
    });
  }
}

function closeTemplatePreviewModal() {
  document.getElementById('modal-template-preview').style.display = 'none';
}
</script>
