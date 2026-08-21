<?php
/**
 * TechFix Laptop Repair Management System
 * Maintenance Mode Public Template
 */
$siteName = site_name();
$sitePhone = site_phone();
$siteWhatsAppLink = site_whatsapp_link('Hello, I am visiting the website and would like to contact your workshop.');
$message = (string)setting('maintenance_message', 'We are currently performing scheduled maintenance and updates to improve your experience. We will be back online shortly!');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Under Maintenance — <?= htmlspecialchars($siteName, ENT_QUOTES) ?></title>
  <meta name="robots" content="noindex, nofollow" />

  <!-- Favicon -->
  <link rel="icon" href="<?= site_favicon() ?>" />

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />

  <!-- Lucide Icons -->
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      background: radial-gradient(ellipse at top, #1e293b 0%, #0b132b 100%);
      color: #f8fafc;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 24px;
      line-height: 1.6;
    }
    .maintenance-card {
      background: rgba(15, 23, 42, 0.85);
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 20px;
      padding: 48px 36px;
      max-width: 580px;
      width: 100%;
      text-align: center;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6);
    }
    .badge-maintenance {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: rgba(245, 158, 11, 0.15);
      border: 1px solid rgba(245, 158, 11, 0.3);
      color: #fbbf24;
      padding: 6px 14px;
      border-radius: 9999px;
      font-size: 0.82rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      margin-bottom: 24px;
    }
    .title {
      font-size: 2rem;
      font-weight: 900;
      color: #ffffff;
      margin-bottom: 16px;
      line-height: 1.25;
    }
    .description {
      color: #94a3b8;
      font-size: 1.05rem;
      margin-bottom: 32px;
    }
    .contact-box {
      background: rgba(30, 41, 59, 0.7);
      border: 1px solid rgba(255, 255, 255, 0.08);
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 32px;
    }
    .contact-title {
      font-size: 0.85rem;
      color: #cbd5e1;
      font-weight: 600;
      margin-bottom: 12px;
    }
    .btn-group {
      display: flex;
      flex-wrap: wrap;
      gap: 12px;
      justify-content: center;
    }
    .btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 12px 22px;
      border-radius: 10px;
      font-weight: 700;
      font-size: 0.95rem;
      text-decoration: none;
      transition: transform 0.18s ease, box-shadow 0.18s ease;
    }
    .btn:hover {
      transform: translateY(-2px);
    }
    .btn-call {
      background: #2563eb;
      color: #ffffff;
      box-shadow: 0 4px 14px rgba(37, 99, 235, 0.4);
    }
    .btn-whatsapp {
      background: #22c55e;
      color: #ffffff;
      box-shadow: 0 4px 14px rgba(34, 197, 94, 0.4);
    }
    .footer-link {
      margin-top: 28px;
      font-size: 0.85rem;
      color: #64748b;
    }
    .footer-link a {
      color: #38bdf8;
      text-decoration: none;
      font-weight: 600;
    }
    .footer-link a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

<div class="maintenance-card">
  <div style="margin-bottom: 24px;">
    <img src="<?= site_logo_dark() ?>" alt="<?= htmlspecialchars($siteName, ENT_QUOTES) ?>" style="max-height: 48px; width: auto;" />
  </div>

  <div class="badge-maintenance">
    <i data-lucide="wrench"></i> Scheduled Maintenance
  </div>

  <h1 class="title">We'll Be Right Back!</h1>
  <p class="description"><?= htmlspecialchars($message, ENT_QUOTES) ?></p>

  <div class="contact-box">
    <div class="contact-title">Need urgent laptop repair assistance? Reach our technicians directly:</div>
    <div class="btn-group">
      <a href="tel:<?= htmlspecialchars($sitePhone, ENT_QUOTES) ?>" class="btn btn-call">
        <i data-lucide="phone"></i> Call <?= htmlspecialchars($sitePhone, ENT_QUOTES) ?>
      </a>
      <a href="<?= $siteWhatsAppLink ?>" class="btn btn-whatsapp" target="_blank" rel="noopener">
        <i data-lucide="message-circle"></i> WhatsApp Us
      </a>
    </div>
  </div>

  <div class="footer-link">
    Are you a staff member? <a href="<?= url('/admin/login') ?>">Staff Login &rarr;</a>
  </div>
</div>

<script>
  if (typeof lucide !== 'undefined') {
    lucide.createIcons();
  }
</script>
</body>
</html>
