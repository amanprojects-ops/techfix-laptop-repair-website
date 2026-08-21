<?php 
use App\Core\Session; 
Session::start(); 

$currentUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . ($_SERVER['REQUEST_URI'] ?? '/');
$canonical = (string)setting('canonical_url', '');
if (empty($canonical)) {
    $canonical = $currentUrl;
}

$siteName = site_name();
$siteTagline = site_tagline();
$city = (string)setting('city', 'Saharsa');
$siteTitle = htmlspecialchars($pageTitle ?? (string)setting('meta_title', "{$siteName} — Professional Laptop Repair Center"), ENT_QUOTES);
$siteDesc = htmlspecialchars($metaDesc ?? (string)setting('meta_description', "Professional laptop repair center in {$city}, Bihar. Screen replacement, motherboard chip-level repair, battery replacement & data recovery with 90-day warranty."), ENT_QUOTES);
$siteKeywords = htmlspecialchars((string)setting('meta_keywords', 'laptop repair, screen replacement, motherboard repair'), ENT_QUOTES);
$ga4Id = trim((string)setting('google_analytics_id', ''));
$gscCode = trim((string)setting('google_search_console_code', ''));
$headerScripts = (string)setting('header_custom_scripts', '');
$footerScripts = (string)setting('footer_custom_scripts', '');
$ogTitle = htmlspecialchars((string)setting('og_title', $siteTitle), ENT_QUOTES);
$ogDesc = htmlspecialchars((string)setting('og_description', $siteDesc), ENT_QUOTES);
$ogImage = (string)setting('og_image', '');
$ogImageUrl = !empty($ogImage) ? asset('/' . ltrim($ogImage, '/')) : site_logo();
$copyrightText = str_replace('{year}', date('Y'), (string)setting('copyright_text', "© {year} {$siteName} Laptop Repair Center. All rights reserved."));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= $siteTitle ?></title>
  <meta name="description" content="<?= $siteDesc ?>" />
  <?php if (!empty($siteKeywords)): ?>
  <meta name="keywords" content="<?= $siteKeywords ?>" />
  <?php endif; ?>
  <link rel="canonical" href="<?= htmlspecialchars($canonical, ENT_QUOTES) ?>" />

  <!-- Robots Indexing Control -->
  <?php if ((string)setting('robots_indexing', '1') === '1'): ?>
  <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1" />
  <?php else: ?>
  <meta name="robots" content="noindex, nofollow" />
  <?php endif; ?>

  <!-- Open Graph / Social Meta -->
  <meta property="og:type" content="website" />
  <meta property="og:url" content="<?= htmlspecialchars($currentUrl, ENT_QUOTES) ?>" />
  <meta property="og:title" content="<?= $ogTitle ?>" />
  <meta property="og:description" content="<?= $ogDesc ?>" />
  <meta property="og:image" content="<?= htmlspecialchars($ogImageUrl, ENT_QUOTES) ?>" />
  <meta property="og:site_name" content="<?= htmlspecialchars($siteName, ENT_QUOTES) ?>" />

  <!-- Twitter Meta -->
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content="<?= $ogTitle ?>" />
  <meta name="twitter:description" content="<?= $ogDesc ?>" />
  <meta name="twitter:image" content="<?= htmlspecialchars($ogImageUrl, ENT_QUOTES) ?>" />

  <!-- Favicon & Touch Icons -->
  <link rel="icon" href="<?= site_favicon() ?>" />
  <link rel="apple-touch-icon" href="<?= apple_touch_icon() ?>" />

  <!-- Preconnect & Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />

  <!-- Lucide Icons -->
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

  <!-- Google Search Console Verification -->
  <?php if (!empty($gscCode)): ?>
    <?php if (str_starts_with($gscCode, '<meta')): ?>
      <?= $gscCode . "\n" ?>
    <?php else: ?>
      <meta name="google-site-verification" content="<?= htmlspecialchars($gscCode, ENT_QUOTES) ?>" />
    <?php endif; ?>
  <?php endif; ?>

  <!-- Google Analytics 4 (GA4) -->
  <?php if (!empty($ga4Id)): ?>
  <script async src="https://www.googletagmanager.com/gtag/js?id=<?= htmlspecialchars($ga4Id, ENT_QUOTES) ?>"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', '<?= htmlspecialchars($ga4Id, ENT_QUOTES) ?>');
  </script>
  <?php endif; ?>

  <!-- Styles -->
  <link rel="stylesheet" href="<?= asset('/assets/css/styles.css') ?>" />

  <!-- Custom Header Scripts -->
  <?php if (!empty($headerScripts)): ?>
  <?= $headerScripts . "\n" ?>
  <?php endif; ?>
</head>
<body>

<!-- NAVBAR -->
<header class="navbar" id="navbar">
  <div class="container navbar__inner">
    <a href="<?= url('/') ?>" class="navbar__logo">
      <img src="<?= site_logo() ?>" alt="<?= htmlspecialchars($siteName, ENT_QUOTES) ?>" class="navbar__logo-img" style="max-height: 38px; width: auto;" />
    </a>
    <nav class="navbar__nav" id="navMenu">
      <div class="nav-dropdown">
        <button class="nav-link dropdown-trigger">Services <i data-lucide="chevron-down"></i></button>
        <div class="dropdown-menu">
          <a href="<?= url('/#services') ?>" class="dropdown-item"><i data-lucide="monitor"></i> Screen Replacement</a>
          <a href="<?= url('/#services') ?>" class="dropdown-item"><i data-lucide="cpu"></i> Motherboard Repair</a>
          <a href="<?= url('/#services') ?>" class="dropdown-item"><i data-lucide="battery-charging"></i> Battery Replacement</a>
          <a href="<?= url('/#services') ?>" class="dropdown-item"><i data-lucide="database"></i> Data Recovery</a>
          <a href="<?= url('/#services') ?>" class="dropdown-item"><i data-lucide="hard-drive"></i> SSD / RAM Upgrade</a>
          <a href="<?= url('/#services') ?>" class="dropdown-item"><i data-lucide="droplet"></i> Liquid Damage Repair</a>
        </div>
      </div>
      <a href="<?= url('/pricing') ?>" class="nav-link">Pricing</a>
      <a href="<?= url('/track-repair') ?>" class="nav-link">Track Repair</a>
      <a href="<?= url('/#about') ?>" class="nav-link">About</a>
      <a href="<?= url('/#reviews') ?>" class="nav-link">Reviews</a>
      <a href="<?= url('/#contact') ?>" class="nav-link">Contact</a>
    </nav>
    <div class="navbar__actions">
      <?php if ((string)setting('allow_customer_booking', '1') === '1'): ?>
      <a href="<?= url('/book-repair') ?>" class="btn btn--primary">Book Repair</a>
      <?php else: ?>
      <a href="tel:<?= htmlspecialchars(site_phone(), ENT_QUOTES) ?>" class="btn btn--primary"><i data-lucide="phone"></i> Call Now</a>
      <?php endif; ?>
      <button class="nav-hamburger" id="hamburger" aria-label="Open menu">
        <i data-lucide="menu"></i>
      </button>
    </div>
  </div>
</header>

<!-- Mobile Nav Drawer -->
<div class="mobile-nav" id="mobileNav">
  <button class="mobile-nav__close" id="mobileNavClose" aria-label="Close menu"><i data-lucide="x"></i></button>
  <a href="<?= url('/') ?>" class="navbar__logo" style="padding:0 0 1.25rem">
    <img src="<?= site_logo() ?>" alt="<?= htmlspecialchars($siteName, ENT_QUOTES) ?>" style="max-height: 36px; width: auto;" />
  </a>
  <a href="<?= url('/#services') ?>" class="mobile-nav__link"><i data-lucide="wrench"></i> Services</a>
  <a href="<?= url('/pricing') ?>" class="mobile-nav__link"><i data-lucide="indian-rupee"></i> Pricing</a>
  <a href="<?= url('/track-repair') ?>" class="mobile-nav__link"><i data-lucide="activity"></i> Track Repair</a>
  <a href="<?= url('/#about') ?>" class="mobile-nav__link"><i data-lucide="shield-check"></i> About</a>
  <a href="<?= url('/#reviews') ?>" class="mobile-nav__link"><i data-lucide="star"></i> Reviews</a>
  <a href="<?= url('/#contact') ?>" class="mobile-nav__link"><i data-lucide="phone"></i> Contact</a>
  <?php if ((string)setting('allow_customer_booking', '1') === '1'): ?>
  <a href="<?= url('/book-repair') ?>" class="btn btn--primary" style="margin-top:1rem;text-align:center;justify-content:center;">Book Repair</a>
  <?php else: ?>
  <a href="tel:<?= htmlspecialchars(site_phone(), ENT_QUOTES) ?>" class="btn btn--primary" style="margin-top:1rem;text-align:center;justify-content:center;">Call <?= htmlspecialchars(site_phone(), ENT_QUOTES) ?></a>
  <?php endif; ?>
</div>
<div class="mobile-nav-overlay" id="mobileNavOverlay"></div>

<?php
// Flash messages
$flashSuccess = \App\Core\Session::getFlash('contact_success') ?? \App\Core\Session::getFlash('success');
$flashError   = \App\Core\Session::getFlash('contact_error') ?? \App\Core\Session::getFlash('error');
if ($flashSuccess): ?>
<div style="background:#ECFDF5;border-bottom:1px solid #A7F3D0;color:#065F46;padding:14px 24px;text-align:center;font-size:0.92rem;font-weight:700;display:flex;align-items:center;justify-content:center;gap:8px;">
  <i data-lucide="check-circle" style="color:#10B981;width:18px;height:18px;"></i>
  <span><?= htmlspecialchars($flashSuccess, ENT_QUOTES) ?></span>
</div>
<?php endif; ?>
<?php if ($flashError): ?>
<div style="background:#FEF2F2;border-bottom:1px solid #FECACA;color:#991B1B;padding:14px 24px;text-align:center;font-size:0.92rem;font-weight:700;display:flex;align-items:center;justify-content:center;gap:8px;">
  <i data-lucide="alert-triangle" style="color:#EF4444;width:18px;height:18px;"></i>
  <span><?= htmlspecialchars($flashError, ENT_QUOTES) ?></span>
</div>
<?php endif; ?>

<!-- PAGE CONTENT -->
<?= $content ?>

<!-- FOOTER -->
<footer class="footer">
  <div class="container footer__inner">
    <div class="footer__brand">
      <a href="<?= url('/') ?>" class="navbar__logo">
        <img src="<?= site_logo_dark() ?>" alt="<?= htmlspecialchars($siteName, ENT_QUOTES) ?>" style="max-height: 38px; width: auto;" />
      </a>
      <p><?= htmlspecialchars((string)setting('footer_about_text', "Professional laptop repair center in {$city}, Bihar. Trusted by 10,000+ satisfied customers since 2014."), ENT_QUOTES) ?></p>
      
      <div class="footer__social">
        <?php if (!empty(setting('facebook_url'))): ?>
        <a href="<?= htmlspecialchars((string)setting('facebook_url'), ENT_QUOTES) ?>" target="_blank" rel="noopener" aria-label="Facebook"><i data-lucide="facebook"></i></a>
        <?php endif; ?>
        <?php if (!empty(setting('instagram_url'))): ?>
        <a href="<?= htmlspecialchars((string)setting('instagram_url'), ENT_QUOTES) ?>" target="_blank" rel="noopener" aria-label="Instagram"><i data-lucide="instagram"></i></a>
        <?php endif; ?>
        <?php if (!empty(setting('youtube_url'))): ?>
        <a href="<?= htmlspecialchars((string)setting('youtube_url'), ENT_QUOTES) ?>" target="_blank" rel="noopener" aria-label="YouTube"><i data-lucide="youtube"></i></a>
        <?php endif; ?>
        <?php if (!empty(setting('twitter_url'))): ?>
        <a href="<?= htmlspecialchars((string)setting('twitter_url'), ENT_QUOTES) ?>" target="_blank" rel="noopener" aria-label="Twitter"><i data-lucide="twitter"></i></a>
        <?php endif; ?>
        <a href="<?= site_whatsapp_link() ?>" aria-label="WhatsApp" target="_blank" rel="noopener"><i data-lucide="message-circle"></i></a>
      </div>
    </div>
    
    <div class="footer__links">
      <h4>Services</h4>
      <a href="<?= url('/#services') ?>">Screen Replacement</a>
      <a href="<?= url('/#services') ?>">Motherboard Repair</a>
      <a href="<?= url('/#services') ?>">Battery Replacement</a>
      <a href="<?= url('/#services') ?>">Data Recovery</a>
      <a href="<?= url('/#services') ?>">SSD / RAM Upgrade</a>
    </div>
    <div class="footer__links">
      <h4>Quick Links</h4>
      <a href="<?= url('/pricing') ?>">Pricing Table</a>
      <a href="<?= url('/track-repair') ?>">Track Repair</a>
      <a href="<?= url('/book-repair') ?>">Book Repair Online</a>
      <a href="<?= url('/#faq') ?>">FAQ</a>
      <a href="<?= url('/#contact') ?>">Contact &amp; Location</a>
      <a href="<?= url('/admin/login') ?>" style="color:#38BDF8;font-weight:700;display:inline-flex;align-items:center;gap:4px;">
        <i data-lucide="shield-check" style="width:14px;height:14px;"></i> Staff Portal
      </a>
    </div>
    <div class="footer__links">
      <h4>Contact &amp; Hours</h4>
      <p style="color:var(--text-muted);font-size:0.88rem;margin-bottom:6px;"><i data-lucide="map-pin" style="width:14px;height:14px;display:inline;vertical-align:middle;"></i> <?= htmlspecialchars(site_address(), ENT_QUOTES) ?></p>
      <p style="color:var(--text-muted);font-size:0.88rem;margin-bottom:6px;"><i data-lucide="phone" style="width:14px;height:14px;display:inline;vertical-align:middle;"></i> <a href="tel:<?= htmlspecialchars(site_phone(), ENT_QUOTES) ?>" style="color:var(--text-light);"><?= htmlspecialchars(site_phone(), ENT_QUOTES) ?></a></p>
      <p style="color:var(--text-muted);font-size:0.88rem;"><i data-lucide="clock" style="width:14px;height:14px;display:inline;vertical-align:middle;"></i> <?= htmlspecialchars((string)setting('working_hours', 'Mon–Sat: 9:00 AM – 8:00 PM'), ENT_QUOTES) ?></p>
    </div>
  </div>
  <div class="footer__bottom">
    <div class="container">
      <p><?= htmlspecialchars($copyrightText, ENT_QUOTES) ?> | <?= htmlspecialchars(site_address(), ENT_QUOTES) ?></p>
    </div>
  </div>
</footer>

<!-- MOBILE STICKY BAR -->
<div class="mobile-sticky-bar">
  <a href="tel:<?= htmlspecialchars(site_phone(), ENT_QUOTES) ?>" class="sticky-btn"><i data-lucide="phone"></i><span>Call</span></a>
  <a href="<?= site_whatsapp_link() ?>" class="sticky-btn sticky-btn--whatsapp" target="_blank" rel="noopener"><i data-lucide="message-circle"></i><span>WhatsApp</span></a>
  <?php if ((string)setting('allow_customer_booking', '1') === '1'): ?>
  <a href="<?= url('/book-repair') ?>" class="sticky-btn sticky-btn--book"><i data-lucide="wrench"></i><span>Book</span></a>
  <?php else: ?>
  <a href="<?= url('/track-repair') ?>" class="sticky-btn sticky-btn--book"><i data-lucide="activity"></i><span>Track</span></a>
  <?php endif; ?>
</div>

<!-- Custom Footer Scripts -->
<?php if (!empty($footerScripts)): ?>
<?= $footerScripts . "\n" ?>
<?php endif; ?>

<script src="<?= asset('/assets/js/main.js') ?>"></script>
</body>
</html>
