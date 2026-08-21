<?php use App\Core\Session; Session::start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($pageTitle ?? 'TechFix Admin', ENT_QUOTES) ?> — TechFix Admin</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
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
      <div class="nav-section-title">Core Operations</div>
      <ul>
        <li class="<?= (str_contains($_SERVER['REQUEST_URI'], '/admin/dashboard') || $_SERVER['REQUEST_URI'] === '/admin') ? 'active' : '' ?>">
          <a href="/admin/dashboard"><i class="fas fa-chart-pie"></i><span>Dashboard Overview</span></a>
        </li>
        <li class="has-submenu <?= str_contains($_SERVER['REQUEST_URI'], '/admin/repairs') ? 'open' : '' ?>">
          <a href="#" class="menu-toggle"><i class="fas fa-laptop-medical"></i><span>Repair Jobs</span><i class="fas fa-chevron-right submenu-arrow"></i></a>
          <ul class="submenu">
            <li><a href="/admin/repairs"><i class="fas fa-list-ul"></i><span>Active Lab Queue</span></a></li>
            <li><a href="/admin/repairs/create"><i class="fas fa-plus-circle"></i><span>Intake New Device</span></a></li>
            <li><a href="/admin/repairs?status=DELIVERED"><i class="fas fa-history"></i><span>Completed Repairs</span></a></li>
          </ul>
        </li>
        <li class="has-submenu <?= str_contains($_SERVER['REQUEST_URI'], '/admin/services') ? 'open' : '' ?>">
          <a href="#" class="menu-toggle"><i class="fas fa-microchip"></i><span>Service Catalog</span><i class="fas fa-chevron-right submenu-arrow"></i></a>
          <ul class="submenu">
            <li><a href="/admin/services"><i class="fas fa-layer-group"></i><span>Manage Services</span></a></li>
          </ul>
        </li>
      </ul>

      <div class="nav-section-title">People</div>
      <ul>
        <li class="<?= str_contains($_SERVER['REQUEST_URI'], '/admin/customers') ? 'active' : '' ?>">
          <a href="/admin/customers"><i class="fas fa-users"></i><span>Customers</span></a>
        </li>
        <li class="<?= str_contains($_SERVER['REQUEST_URI'], '/admin/technicians') ? 'active' : '' ?>">
          <a href="/admin/technicians"><i class="fas fa-user-cog"></i><span>Technicians</span></a>
        </li>
      </ul>

      <div class="nav-section-title">Analytics</div>
      <ul>
        <li>
          <a href="/track-repair" target="_blank"><i class="fas fa-external-link-alt"></i><span>Live Customer Tracker</span></a>
        </li>
        <li>
          <a href="/" target="_blank"><i class="fas fa-globe"></i><span>View Customer Site</span></a>
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

  <!-- Main Content -->
  <main class="main-content">
    <?= $content ?>
  </main>

</div>
<script src="/admin-assets/js/script.js"></script>
</body>
</html>
