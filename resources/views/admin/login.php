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
  <title>Staff &amp; Admin Portal — TechFix Laptop Repair</title>

  <!-- Preconnect & Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />

  <!-- Font Awesome 6.5 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  
  <!-- Stylesheets -->
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
      <div style="background:rgba(239, 68, 68, 0.15);border:1px solid #EF4444;color:#FCA5A5;padding:12px 16px;border-radius:10px;font-size:0.875rem;margin-bottom:20px;font-weight:600;display:flex;align-items:center;gap:10px;">
        <i class="fas fa-exclamation-triangle" style="color:#EF4444;"></i>
        <span><?= htmlspecialchars($flash_error, ENT_QUOTES) ?></span>
      </div>
      <?php endif; ?>

      <form class="auth-form" action="/admin/login" method="POST">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES) ?>" />

        <div class="auth-group">
          <label for="email"><i class="fas fa-envelope"></i> Engineer Email</label>
          <div class="input-icon-wrap">
            <i class="fas fa-at field-icon"></i>
            <input type="email" id="email" name="email" placeholder="e.g. admin@techfix.in" required autocomplete="email" autofocus />
          </div>
        </div>

        <div class="auth-group">
          <label for="password"><i class="fas fa-lock"></i> Password</label>
          <div class="input-icon-wrap">
            <i class="fas fa-key field-icon"></i>
            <input type="password" id="password" name="password" placeholder="Enter your password" required autocomplete="current-password" />
            <button type="button" class="password-toggle" id="togglePassword" aria-label="Toggle password visibility">
              <i class="fas fa-eye"></i>
            </button>
          </div>
        </div>

        <button type="submit" class="btn-auth-submit">
          <span>Access Workshop Dashboard</span>
          <i class="fas fa-arrow-right"></i>
        </button>

        <div class="auth-hint">
          <i class="fas fa-shield-alt"></i>
          <span>Default: <strong>admin@techfix.in</strong> / <strong>admin123</strong></span>
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
