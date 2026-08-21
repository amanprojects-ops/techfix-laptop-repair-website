<?php use App\Core\Session; Session::start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($pageTitle ?? 'TechFix — Laptop Repair Center', ENT_QUOTES) ?></title>
  <meta name="description" content="<?= htmlspecialchars($metaDesc ?? 'Professional laptop repair center in Saharsa, Bihar. Screen replacement, motherboard repair, battery replacement, data recovery & more.', ENT_QUOTES) ?>" />

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />

  <!-- Lucide Icons -->
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

  <!-- Styles -->
  <link rel="stylesheet" href="/assets/css/styles.css" />
</head>
<body>

<!-- NAVBAR -->
<header class="navbar" id="navbar">
  <div class="container navbar__inner">
    <a href="/" class="navbar__logo">
      <span class="logo-icon"><i data-lucide="cpu"></i></span>
      <span class="logo-text">Tech<span class="logo-accent">Fix</span></span>
    </a>
    <nav class="navbar__nav" id="navMenu">
      <div class="nav-dropdown">
        <button class="nav-link dropdown-trigger">Services <i data-lucide="chevron-down"></i></button>
        <div class="dropdown-menu">
          <a href="/#services" class="dropdown-item"><i data-lucide="monitor"></i> Screen Replacement</a>
          <a href="/#services" class="dropdown-item"><i data-lucide="cpu"></i> Motherboard Repair</a>
          <a href="/#services" class="dropdown-item"><i data-lucide="battery-charging"></i> Battery Replacement</a>
          <a href="/#services" class="dropdown-item"><i data-lucide="database"></i> Data Recovery</a>
          <a href="/#services" class="dropdown-item"><i data-lucide="hard-drive"></i> SSD / RAM Upgrade</a>
          <a href="/#services" class="dropdown-item"><i data-lucide="droplets"></i> Liquid Damage Repair</a>
        </div>
      </div>
      <a href="/pricing" class="nav-link">Pricing</a>
      <a href="/track-repair" class="nav-link">Track Repair</a>
      <a href="/#about" class="nav-link">About</a>
      <a href="/#reviews" class="nav-link">Reviews</a>
      <a href="/#contact" class="nav-link">Contact</a>
    </nav>
    <div class="navbar__actions">
      <a href="/book-repair" class="btn btn--primary">Book Repair</a>
      <button class="nav-hamburger" id="hamburger" aria-label="Open menu">
        <i data-lucide="menu"></i>
      </button>
    </div>
  </div>
</header>

<!-- Mobile Nav -->
<div class="mobile-nav" id="mobileNav">
  <button class="mobile-nav__close" id="mobileNavClose"><i data-lucide="x"></i></button>
  <a href="/" class="navbar__logo" style="padding:0 0 1.5rem">
    <span class="logo-icon"><i data-lucide="cpu"></i></span>
    <span class="logo-text">Tech<span class="logo-accent">Fix</span></span>
  </a>
  <a href="/#services" class="mobile-nav__link">Services</a>
  <a href="/pricing" class="mobile-nav__link">Pricing</a>
  <a href="/track-repair" class="mobile-nav__link">Track Repair</a>
  <a href="/#about" class="mobile-nav__link">About</a>
  <a href="/#reviews" class="mobile-nav__link">Reviews</a>
  <a href="/#contact" class="mobile-nav__link">Contact</a>
  <a href="/book-repair" class="btn btn--primary" style="margin-top:1rem;text-align:center;">Book Repair</a>
</div>
<div class="mobile-nav-overlay" id="mobileNavOverlay"></div>

<?php
// Flash messages
$flashSuccess = \App\Core\Session::getFlash('contact_success');
$flashError   = \App\Core\Session::getFlash('contact_error');
if ($flashSuccess): ?>
<div style="background:#d1fae5;color:#065f46;padding:12px 24px;text-align:center;font-size:0.9rem;font-weight:600;">
  ✓ <?= htmlspecialchars($flashSuccess, ENT_QUOTES) ?>
</div>
<?php endif; ?>
<?php if ($flashError): ?>
<div style="background:#fee2e2;color:#991b1b;padding:12px 24px;text-align:center;font-size:0.9rem;font-weight:600;">
  ⚠ <?= htmlspecialchars($flashError, ENT_QUOTES) ?>
</div>
<?php endif; ?>

<!-- PAGE CONTENT -->
<?= $content ?>

<!-- FOOTER -->
<footer class="footer">
  <div class="container footer__inner">
    <div class="footer__brand">
      <a href="/" class="navbar__logo">
        <span class="logo-icon"><i data-lucide="cpu"></i></span>
        <span class="logo-text">Tech<span class="logo-accent">Fix</span></span>
      </a>
      <p>Professional laptop repair center in Saharsa, Bihar. Trusted by 10,000+ customers since 2014.</p>
      <div class="footer__social">
        <a href="#" aria-label="Facebook"><i data-lucide="facebook"></i></a>
        <a href="#" aria-label="Instagram"><i data-lucide="instagram"></i></a>
        <a href="https://wa.me/919876543210" aria-label="WhatsApp"><i data-lucide="message-circle"></i></a>
      </div>
    </div>
    <div class="footer__links">
      <h4>Services</h4>
      <a href="/#services">Screen Replacement</a>
      <a href="/#services">Motherboard Repair</a>
      <a href="/#services">Battery Replacement</a>
      <a href="/#services">Data Recovery</a>
      <a href="/#services">SSD / RAM Upgrade</a>
    </div>
    <div class="footer__links">
      <h4>Quick Links</h4>
      <a href="/pricing">Pricing</a>
      <a href="/track-repair">Track Repair</a>
      <a href="/book-repair">Book Repair</a>
      <a href="/#faq">FAQ</a>
      <a href="/#contact">Contact</a>
      <a href="/admin/login" style="color:var(--accent);font-weight:600;"><i data-lucide="shield-check" style="width:14px;height:14px;display:inline-block;vertical-align:middle;margin-right:4px;"></i>Staff Portal</a>
    </div>
    <div class="footer__links">
      <h4>Locations</h4>
      <a href="#">Saharsa</a>
      <a href="#">Supaul</a>
      <a href="#">Madhepura</a>
      <a href="#">Khagaria</a>
    </div>
  </div>
  <div class="footer__bottom">
    <div class="container">
      <p>© <?= date('Y') ?> TechFix Laptop Repair Center. All rights reserved. | Saharsa, Bihar</p>
    </div>
  </div>
</footer>

<!-- MOBILE STICKY BAR -->
<div class="mobile-sticky-bar">
  <a href="tel:+919876543210" class="sticky-btn"><i data-lucide="phone"></i><span>Call</span></a>
  <a href="https://wa.me/919876543210" class="sticky-btn sticky-btn--whatsapp" target="_blank" rel="noopener"><i data-lucide="message-circle"></i><span>WhatsApp</span></a>
  <a href="/book-repair" class="sticky-btn sticky-btn--book"><i data-lucide="wrench"></i><span>Book</span></a>
</div>

<script src="/assets/js/main.js"></script>
</body>
</html>
