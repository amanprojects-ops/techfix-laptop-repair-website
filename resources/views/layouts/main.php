<?php use App\Core\Session; Session::start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($pageTitle ?? 'TechFix — Professional Laptop Repair Center', ENT_QUOTES) ?></title>
  <meta name="description" content="<?= htmlspecialchars($metaDesc ?? 'Professional laptop repair center in Saharsa, Bihar. Screen replacement, motherboard chip-level repair, battery replacement, data recovery & more with 90-day warranty.', ENT_QUOTES) ?>" />

  <!-- Preconnect & Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />

  <!-- Lucide Icons -->
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

  <!-- Styles -->
  <link rel="stylesheet" href="<?= asset('/assets/css/styles.css') ?>" />
</head>
<body>

<!-- NAVBAR -->
<header class="navbar" id="navbar">
  <div class="container navbar__inner">
    <a href="<?= url('/') ?>" class="navbar__logo">
      <span class="logo-icon"><i data-lucide="cpu"></i></span>
      <span class="logo-text">Tech<span class="logo-accent">Fix</span></span>
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
      <a href="<?= url('/book-repair') ?>" class="btn btn--primary">Book Repair</a>
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
    <span class="logo-icon"><i data-lucide="cpu"></i></span>
    <span class="logo-text">Tech<span class="logo-accent">Fix</span></span>
  </a>
  <a href="<?= url('/#services') ?>" class="mobile-nav__link"><i data-lucide="wrench"></i> Services</a>
  <a href="<?= url('/pricing') ?>" class="mobile-nav__link"><i data-lucide="indian-rupee"></i> Pricing</a>
  <a href="<?= url('/track-repair') ?>" class="mobile-nav__link"><i data-lucide="activity"></i> Track Repair</a>
  <a href="<?= url('/#about') ?>" class="mobile-nav__link"><i data-lucide="shield-check"></i> About</a>
  <a href="<?= url('/#reviews') ?>" class="mobile-nav__link"><i data-lucide="star"></i> Reviews</a>
  <a href="<?= url('/#contact') ?>" class="mobile-nav__link"><i data-lucide="phone"></i> Contact</a>
  <a href="<?= url('/book-repair') ?>" class="btn btn--primary" style="margin-top:1rem;text-align:center;justify-content:center;">Book Repair</a>
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
        <span class="logo-icon"><i data-lucide="cpu"></i></span>
        <span class="logo-text">Tech<span class="logo-accent">Fix</span></span>
      </a>
      <p>Professional laptop repair center in Saharsa, Bihar. Trusted by 10,000+ satisfied customers since 2014.</p>
      <div class="footer__social">
        <a href="#" aria-label="Facebook"><i data-lucide="facebook"></i></a>
        <a href="#" aria-label="Instagram"><i data-lucide="instagram"></i></a>
        <a href="https://wa.me/919876543210" aria-label="WhatsApp" target="_blank" rel="noopener"><i data-lucide="message-circle"></i></a>
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
      <h4>Service Areas</h4>
      <a href="#">Saharsa Center</a>
      <a href="#">Supaul</a>
      <a href="#">Madhepura</a>
      <a href="#">Khagaria &amp; Purnia</a>
    </div>
  </div>
  <div class="footer__bottom">
    <div class="container">
      <p>© <?= date('Y') ?> TechFix Laptop Repair Center. All rights reserved. | Main Market Road, Saharsa, Bihar</p>
    </div>
  </div>
</footer>

<!-- MOBILE STICKY BAR -->
<div class="mobile-sticky-bar">
  <a href="tel:+919876543210" class="sticky-btn"><i data-lucide="phone"></i><span>Call</span></a>
  <a href="https://wa.me/919876543210" class="sticky-btn sticky-btn--whatsapp" target="_blank" rel="noopener"><i data-lucide="message-circle"></i><span>WhatsApp</span></a>
  <a href="<?= url('/book-repair') ?>" class="sticky-btn sticky-btn--book"><i data-lucide="wrench"></i><span>Book</span></a>
</div>

<script src="<?= asset('/assets/js/main.js') ?>"></script>
</body>
</html>
