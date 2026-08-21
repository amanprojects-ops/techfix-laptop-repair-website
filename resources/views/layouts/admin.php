<?php 
use App\Core\Session; 
Session::start(); 
$currentUri = $_SERVER['REQUEST_URI'] ?? '/admin/dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($pageTitle ?? 'Dashboard', ENT_QUOTES) ?> — TechFix Workshop Admin</title>
  
  <!-- Preconnect & Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />

  <!-- Font Awesome 6.5 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  
  <!-- Admin Stylesheet -->
  <link rel="stylesheet" href="/admin-assets/css/styles.css" />
</head>
<body>
<div class="dashboard-container">

  <!-- Sidebar -->
  <aside class="sidebar" id="sidebar">
    <div class="logo-container">
      <a href="/admin/dashboard" class="brand-logo-link">
        <img src="/admin-assets/images/logo.svg" alt="TechFix Admin" class="brand-logo-img" />
        <img src="/admin-assets/images/icon.svg" alt="TechFix Icon" class="brand-logo-icon" />
      </a>
    </div>

    <nav class="main-nav">
      <div class="nav-section-title">Workshop Operations</div>
      <ul>
        <li class="<?= (str_contains($currentUri, '/admin/dashboard') || $currentUri === '/admin') ? 'active' : '' ?>">
          <a href="/admin/dashboard"><i class="fas fa-chart-pie"></i><span>Dashboard</span></a>
        </li>
        <li class="has-submenu <?= str_contains($currentUri, '/admin/repairs') ? 'open' : '' ?>">
          <a href="#" class="menu-toggle"><i class="fas fa-laptop-medical"></i><span>Repair Jobs</span><i class="fas fa-chevron-right submenu-arrow"></i></a>
          <ul class="submenu">
            <li><a href="/admin/repairs"><i class="fas fa-list-ul"></i><span>Active Lab Queue</span></a></li>
            <li><a href="/admin/repairs/create"><i class="fas fa-plus-circle"></i><span>Intake New Device</span></a></li>
            <li><a href="/admin/repairs?status=DELIVERED"><i class="fas fa-history"></i><span>Completed Repairs</span></a></li>
          </ul>
        </li>
        <li class="has-submenu <?= str_contains($currentUri, '/admin/services') ? 'open' : '' ?>">
          <a href="#" class="menu-toggle"><i class="fas fa-microchip"></i><span>Service Catalog</span><i class="fas fa-chevron-right submenu-arrow"></i></a>
          <ul class="submenu">
            <li><a href="/admin/services"><i class="fas fa-layer-group"></i><span>Manage Services</span></a></li>
          </ul>
        </li>
      </ul>

      <div class="nav-section-title">People &amp; Staff</div>
      <ul>
        <li class="<?= str_contains($currentUri, '/admin/customers') ? 'active' : '' ?>">
          <a href="/admin/customers"><i class="fas fa-users"></i><span>Customers</span></a>
        </li>
        <li class="<?= str_contains($currentUri, '/admin/technicians') ? 'active' : '' ?>">
          <a href="/admin/technicians"><i class="fas fa-user-cog"></i><span>Technicians</span></a>
        </li>
      </ul>

      <div class="nav-section-title">Quick Links</div>
      <ul>
        <li>
          <a href="/track-repair" target="_blank"><i class="fas fa-external-link-alt"></i><span>Customer Tracker</span></a>
        </li>
        <li>
          <a href="/" target="_blank"><i class="fas fa-globe"></i><span>Live Customer Site</span></a>
        </li>
      </ul>
    </nav>

    <div class="sidebar-footer">
      <div class="sidebar-user">
        <div class="sidebar-user-avatar"><?= strtoupper(substr($user['name'] ?? 'A', 0, 1)) ?></div>
        <div class="sidebar-user-info">
          <div class="sidebar-user-name"><?= htmlspecialchars($user['name'] ?? 'Admin', ENT_QUOTES) ?></div>
          <div class="sidebar-user-role"><?= htmlspecialchars(ucfirst($user['role'] ?? 'Admin'), ENT_QUOTES) ?></div>
        </div>
      </div>
      <form method="POST" action="/admin/logout">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken ?? '', ENT_QUOTES) ?>" />
        <button type="submit" class="logout-btn"><i class="fas fa-power-off"></i><span>Log Out</span></button>
      </form>
    </div>
  </aside>

  <!-- Main Content Area -->
  <main class="main-content">
    <?= $content ?>
  </main>

</div>
<script src="/admin-assets/js/script.js"></script>
</body>
</html>
