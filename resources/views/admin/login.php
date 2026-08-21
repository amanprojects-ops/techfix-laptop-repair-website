<?php
// Admin login page — uses 'none' layout (standalone page, no sidebar)
use App\Core\Session;
Session::start();
$csrfToken  = $csrfToken  ?? Session::csrfToken();
$flash_error = $flash_error ?? Session::getFlash('error');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Login — TechFix Laptop Repair Portal</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="/admin-assets/css/styles.css" />
  <link rel="stylesheet" href="/admin-assets/css/auth.css" />
</head>
<body class="auth-body">
  <div class="auth-container">
    <div class="auth-card">
      <div class="auth-header">
        <a href="/"><img src="/admin-assets/images/logo.svg" alt="TechFix Admin" class="auth-brand-logo" /></a>
        <h2>Technician &amp; Admin Portal</h2>
        <p>Sign in to access workshop repair queue &amp; billing</p>
      </div>

      <?php if ($flash_error): ?>
      <div style="background:#fee2e2;color:#991b1b;padding:10px 16px;border-radius:8px;font-size:0.875rem;margin-bottom:16px;font-weight:600;">
        <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($flash_error, ENT_QUOTES) ?>
      </div>
      <?php endif; ?>

      <form class="auth-form" action="/admin/login" method="POST">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES) ?>" />

        <div class="auth-group">
          <label for="email"><i class="fas fa-envelope"></i> Engineer Email</label>
          <div class="input-icon-wrap">
            <i class="fas fa-at field-icon"></i>
            <input type="email" id="email" name="email" placeholder="Enter your email" required autocomplete="email" />
          </div>
        </div>

        <div class="auth-group">
          <label for="password"><i class="fas fa-lock"></i> Password</label>
          <div class="input-icon-wrap">
            <i class="fas fa-key field-icon"></i>
            <input type="password" id="password" name="password" placeholder="Enter your password" required autocomplete="current-password" />
            <button type="button" class="password-toggle" id="togglePassword" aria-label="Toggle password">
              <i class="fas fa-eye"></i>
            </button>
          </div>
        </div>

        <button type="submit" class="btn-auth-submit">
          <span>Access Dashboard</span>
          <i class="fas fa-arrow-right"></i>
        </button>

        <div class="auth-hint">
          <i class="fas fa-info-circle"></i>
          <span>Default: <strong>admin@techfix.in</strong> / <strong>admin123</strong> — change after first login.</span>
        </div>
      </form>

      <div class="auth-footer">
        <a href="/" class="back-to-site">
          <i class="fas fa-globe"></i> Back to TechFix Customer Website
        </a>
      </div>
    </div>
  </div>
  <script src="/admin-assets/js/auth.js"></script>
</body>
</html>
