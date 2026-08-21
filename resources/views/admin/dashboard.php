<?php
$statusColors = [
    'RECEIVED'         => '#3B82F6',
    'DIAGNOSIS'        => '#F59E0B',
    'WAITING_APPROVAL' => '#F97316',
    'APPROVED'         => '#8B5CF6',
    'IN_REPAIR'        => '#06B6D4',
    'QUALITY_CHECK'    => '#A855F7',
    'READY_FOR_PICKUP' => '#10B981',
    'DELIVERED'        => '#22C55E',
    'CANCELLED'        => '#EF4444',
    'ON_HOLD'          => '#64748B',
    'PARTS_PENDING'    => '#F59E0B',
    'UNREPAIRABLE'     => '#DC2626',
];
?>

<header class="header">
  <div class="header-left">
    <button id="sidebar-toggle" class="sidebar-toggle" aria-label="Toggle Sidebar"><i class="fas fa-bars"></i></button>
    <div class="header-title-wrap">
      <h2>Workshop Dashboard</h2>
      <span class="header-subtitle">Welcome back, <?= htmlspecialchars(explode(' ', $user['name'])[0] ?? 'Admin', ENT_QUOTES) ?> 👋 (<?= date('l, d F Y') ?>)</span>
    </div>
  </div>
  <div class="header-right">
    <a href="/admin/repairs/create" class="btn-primary"><i class="fas fa-plus"></i> Intake Device</a>
  </div>
</header>

<!-- Stats Grid -->
<div class="stats-grid">
  <div class="stat-card">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
      <span style="font-size:0.8rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:.5px;">Active Repairs</span>
      <div style="width:38px;height:38px;background:rgba(6,182,212,0.12);border-radius:8px;display:flex;align-items:center;justify-content:center;color:#06B6D4;font-size:1.05rem;"><i class="fas fa-wrench"></i></div>
    </div>
    <div style="font-size:2rem;font-weight:900;color:var(--text);line-height:1.1;"><?= (int)($stats['active_repairs'] ?? 0) ?></div>
    <span style="font-size:0.78rem;color:var(--text-muted);margin-top:6px;display:block;">In workshop queue</span>
  </div>

  <div class="stat-card">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
      <span style="font-size:0.8rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:.5px;">Waiting Approval</span>
      <div style="width:38px;height:38px;background:rgba(249,115,22,0.12);border-radius:8px;display:flex;align-items:center;justify-content:center;color:#F97316;font-size:1.05rem;"><i class="fas fa-clock"></i></div>
    </div>
    <div style="font-size:2rem;font-weight:900;color:var(--text);line-height:1.1;"><?= (int)($stats['waiting_approval'] ?? 0) ?></div>
    <span style="font-size:0.78rem;color:var(--text-muted);margin-top:6px;display:block;">Estimate sent to customer</span>
  </div>

  <div class="stat-card">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
      <span style="font-size:0.8rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:.5px;">Ready For Pickup</span>
      <div style="width:38px;height:38px;background:rgba(16,185,129,0.12);border-radius:8px;display:flex;align-items:center;justify-content:center;color:#10B981;font-size:1.05rem;"><i class="fas fa-check-circle"></i></div>
    </div>
    <div style="font-size:2rem;font-weight:900;color:var(--text);line-height:1.1;"><?= (int)($stats['ready_pickup'] ?? 0) ?></div>
    <span style="font-size:0.78rem;color:var(--text-muted);margin-top:6px;display:block;">Tested &amp; ready for delivery</span>
  </div>

  <div class="stat-card">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
      <span style="font-size:0.8rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:.5px;">Monthly Revenue</span>
      <div style="width:38px;height:38px;background:rgba(139,92,246,0.12);border-radius:8px;display:flex;align-items:center;justify-content:center;color:#8B5CF6;font-size:1.05rem;"><i class="fas fa-rupee-sign"></i></div>
    </div>
    <div style="font-size:2rem;font-weight:900;color:var(--text);line-height:1.1;">₹<?= number_format((float)($stats['revenue_month'] ?? 0), 0) ?></div>
    <span style="font-size:0.78rem;color:var(--text-muted);margin-top:6px;display:block;">Delivered repairs this month</span>
  </div>

  <div class="stat-card">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
      <span style="font-size:0.8rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:.5px;">Total Customers</span>
      <div style="width:38px;height:38px;background:rgba(37,99,235,0.12);border-radius:8px;display:flex;align-items:center;justify-content:center;color:#2563EB;font-size:1.05rem;"><i class="fas fa-users"></i></div>
    </div>
    <div style="font-size:2rem;font-weight:900;color:var(--text);line-height:1.1;"><?= (int)($stats['total_customers'] ?? 0) ?></div>
    <span style="font-size:0.78rem;color:var(--text-muted);margin-top:6px;display:block;">Registered in database</span>
  </div>

  <div class="stat-card">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
      <span style="font-size:0.8rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:.5px;">Received Today</span>
      <div style="width:38px;height:38px;background:rgba(245,158,11,0.12);border-radius:8px;display:flex;align-items:center;justify-content:center;color:#F59E0B;font-size:1.05rem;"><i class="fas fa-calendar-day"></i></div>
    </div>
    <div style="font-size:2rem;font-weight:900;color:var(--text);line-height:1.1;"><?= (int)($stats['received_today'] ?? 0) ?></div>
    <span style="font-size:0.78rem;color:var(--text-muted);margin-top:6px;display:block;">New devices dropped off</span>
  </div>
</div>

<!-- Today's Repairs Table Section -->
<div style="padding:0 24px 24px;">
  <div class="table-card">
    <div style="display:flex;align-items:center;justify-content:space-between;padding:18px 22px;border-bottom:1px solid var(--border-color);background:#FFFFFF;">
      <div>
        <h3 style="font-size:1.05rem;font-weight:800;color:var(--text-primary);display:flex;align-items:center;gap:8px;">
          <i class="fas fa-laptop-medical" style="color:var(--primary-color);"></i>
          <span>Today's Received Repairs</span>
        </h3>
        <span style="font-size:0.8rem;color:var(--text-muted);"><?= count($todayJobs ?? []) ?> repair jobs received today</span>
      </div>
      <a href="/admin/repairs" class="btn-secondary btn-sm">View All Jobs →</a>
    </div>

    <?php if (empty($todayJobs)): ?>
    <div style="padding:3rem 2rem;text-align:center;color:var(--text-muted);">
      <i class="fas fa-clipboard-check" style="font-size:2.5rem;color:#10B981;margin-bottom:14px;display:block;opacity:0.8;"></i>
      <strong style="font-size:1rem;color:var(--text-primary);display:block;margin-bottom:4px;">No new repairs received today yet.</strong>
      <span>Click "Intake Device" above to record a new laptop ticket.</span>
    </div>
    <?php else: ?>
    <div style="overflow-x:auto;">
      <table class="data-table">
        <thead>
          <tr>
            <th>Tracking ID</th>
            <th>Customer</th>
            <th>Device</th>
            <th>Service</th>
            <th>Status</th>
            <th style="text-align:right;">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($todayJobs as $job): ?>
          <tr>
            <td>
              <span style="font-family:monospace;font-weight:800;color:var(--primary-color);background:#EFF6FF;padding:4px 8px;border-radius:6px;font-size:0.85rem;">
                <?= htmlspecialchars($job['tracking_id'], ENT_QUOTES) ?>
              </span>
            </td>
            <td>
              <strong style="display:block;font-size:0.9rem;"><?= htmlspecialchars($job['customer_name'], ENT_QUOTES) ?></strong>
              <span style="font-size:0.8rem;color:var(--text-muted);"><?= htmlspecialchars($job['customer_phone'], ENT_QUOTES) ?></span>
            </td>
            <td>
              <span style="font-weight:600;"><?= htmlspecialchars($job['device_brand'] . ' ' . ($job['device_model'] ?? ''), ENT_QUOTES) ?></span>
            </td>
            <td>
              <span style="font-size:0.85rem;color:var(--text-secondary);"><?= htmlspecialchars($job['service_name'] ?? 'General Inspection', ENT_QUOTES) ?></span>
            </td>
            <td>
              <span class="status-pill" style="background:<?= ($statusColors[$job['current_status']] ?? '#64748B') ?>1A;color:<?= $statusColors[$job['current_status']] ?? '#64748B' ?>;border:1px solid <?= $statusColors[$job['current_status']] ?? '#64748B' ?>44;">
                <span style="width:6px;height:6px;border-radius:50%;background:currentColor;display:inline-block;"></span>
                <?= htmlspecialchars(\App\Models\RepairJob::statusLabel($job['current_status']), ENT_QUOTES) ?>
              </span>
            </td>
            <td style="text-align:right;">
              <a href="/admin/repairs/<?= $job['id'] ?>" class="btn-secondary btn-sm">Open Job →</a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
  </div>
</div>
