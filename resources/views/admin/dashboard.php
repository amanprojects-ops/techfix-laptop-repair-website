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
      <span class="header-subtitle">Welcome back, <?= htmlspecialchars(explode(' ', $user['name'])[0] ?? 'Admin', ENT_QUOTES) ?> 👋 | <?= date('l, d F Y') ?></span>
    </div>
  </div>
  <div class="header-right">
    <a href="<?= url('/admin/repairs/create') ?>" class="btn-primary"><i class="fas fa-plus"></i> Intake Device</a>
  </div>
</header>

<!-- ──────────────── Quick Stats Metric Cards ──────────────── -->
<div class="stats-grid">
  <div class="stat-card">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
      <span style="font-size:0.8rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:.5px;">Active In Repair</span>
      <div style="width:40px;height:40px;background:rgba(6,182,212,0.12);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#06B6D4;font-size:1.1rem;">
        <i class="fas fa-wrench"></i>
      </div>
    </div>
    <div style="font-size:2.1rem;font-weight:900;color:var(--text);line-height:1.1;"><?= (int)($stats['active_repairs'] ?? 0) ?></div>
    <span style="font-size:0.8rem;color:var(--text-muted);margin-top:6px;display:block;">Under active bench repair</span>
  </div>

  <div class="stat-card">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
      <span style="font-size:0.8rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:.5px;">Waiting Approval</span>
      <div style="width:40px;height:40px;background:rgba(249,115,22,0.12);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#F97316;font-size:1.1rem;">
        <i class="fas fa-clock"></i>
      </div>
    </div>
    <div style="font-size:2.1rem;font-weight:900;color:var(--text);line-height:1.1;"><?= (int)($stats['waiting_approval'] ?? 0) ?></div>
    <span style="font-size:0.8rem;color:var(--text-muted);margin-top:6px;display:block;">Quote sent to customer</span>
  </div>

  <div class="stat-card">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
      <span style="font-size:0.8rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:.5px;">Ready For Pickup</span>
      <div style="width:40px;height:40px;background:rgba(16,185,129,0.12);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#10B981;font-size:1.1rem;">
        <i class="fas fa-check-circle"></i>
      </div>
    </div>
    <div style="font-size:2.1rem;font-weight:900;color:var(--text);line-height:1.1;"><?= (int)($stats['ready_pickup'] ?? 0) ?></div>
    <span style="font-size:0.8rem;color:var(--text-muted);margin-top:6px;display:block;">Testing passed • Ready</span>
  </div>

  <div class="stat-card">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
      <span style="font-size:0.8rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:.5px;">Monthly Revenue</span>
      <div style="width:40px;height:40px;background:rgba(139,92,246,0.12);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#8B5CF6;font-size:1.1rem;">
        <i class="fas fa-rupee-sign"></i>
      </div>
    </div>
    <div style="font-size:2.1rem;font-weight:900;color:var(--text);line-height:1.1;">₹<?= number_format((float)($stats['revenue_month'] ?? 0), 0) ?></div>
    <span style="font-size:0.8rem;color:var(--text-muted);margin-top:6px;display:block;">Delivered jobs this month</span>
  </div>

  <div class="stat-card">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
      <span style="font-size:0.8rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:.5px;">Total Customers</span>
      <div style="width:40px;height:40px;background:rgba(37,99,235,0.12);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#2563EB;font-size:1.1rem;">
        <i class="fas fa-users"></i>
      </div>
    </div>
    <div style="font-size:2.1rem;font-weight:900;color:var(--text);line-height:1.1;"><?= (int)($stats['total_customers'] ?? 0) ?></div>
    <span style="font-size:0.8rem;color:var(--text-muted);margin-top:6px;display:block;">Customer database count</span>
  </div>

  <div class="stat-card">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
      <span style="font-size:0.8rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:.5px;">Received Today</span>
      <div style="width:40px;height:40px;background:rgba(245,158,11,0.12);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#F59E0B;font-size:1.1rem;">
        <i class="fas fa-calendar-day"></i>
      </div>
    </div>
    <div style="font-size:2.1rem;font-weight:900;color:var(--text);line-height:1.1;"><?= (int)($stats['received_today'] ?? 0) ?></div>
    <span style="font-size:0.8rem;color:var(--text-muted);margin-top:6px;display:block;">New intake today</span>
  </div>
</div>

<!-- ──────────────── Quick Action Shortcuts ──────────────── -->
<div style="padding:0 24px 20px;">
  <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));gap:14px;">
    <a href="<?= url('/admin/repairs/create') ?>" style="background:#FFFFFF;border:1px solid var(--border-color);border-radius:var(--radius-sm);padding:14px 18px;display:flex;align-items:center;gap:12px;text-decoration:none;box-shadow:var(--shadow-xs);transition:transform 0.15s, border-color 0.15s;" onmouseover="this.style.borderColor='var(--primary-color)';this.style.transform='translateY(-2px)'" onmouseout="this.style.borderColor='var(--border-color)';this.style.transform='none'">
      <div style="width:36px;height:36px;background:rgba(37,99,235,0.1);color:var(--primary-color);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:1rem;">
        <i class="fas fa-plus"></i>
      </div>
      <div>
        <strong style="font-size:0.9rem;color:var(--text-primary);display:block;">Intake New Laptop</strong>
        <span style="font-size:0.75rem;color:var(--text-muted);">Create new repair ticket</span>
      </div>
    </a>

    <a href="<?= url('/admin/repairs') ?>" style="background:#FFFFFF;border:1px solid var(--border-color);border-radius:var(--radius-sm);padding:14px 18px;display:flex;align-items:center;gap:12px;text-decoration:none;box-shadow:var(--shadow-xs);transition:transform 0.15s, border-color 0.15s;" onmouseover="this.style.borderColor='var(--primary-color)';this.style.transform='translateY(-2px)'" onmouseout="this.style.borderColor='var(--border-color)';this.style.transform='none'">
      <div style="width:36px;height:36px;background:rgba(6,182,212,0.1);color:#06B6D4;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:1rem;">
        <i class="fas fa-list-ul"></i>
      </div>
      <div>
        <strong style="font-size:0.9rem;color:var(--text-primary);display:block;">Active Queue</strong>
        <span style="font-size:0.75rem;color:var(--text-muted);">Manage all repairs</span>
      </div>
    </a>

    <a href="<?= url('/admin/customers') ?>" style="background:#FFFFFF;border:1px solid var(--border-color);border-radius:var(--radius-sm);padding:14px 18px;display:flex;align-items:center;gap:12px;text-decoration:none;box-shadow:var(--shadow-xs);transition:transform 0.15s, border-color 0.15s;" onmouseover="this.style.borderColor='var(--primary-color)';this.style.transform='translateY(-2px)'" onmouseout="this.style.borderColor='var(--border-color)';this.style.transform='none'">
      <div style="width:36px;height:36px;background:rgba(16,185,129,0.1);color:#10B981;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:1rem;">
        <i class="fas fa-users"></i>
      </div>
      <div>
        <strong style="font-size:0.9rem;color:var(--text-primary);display:block;">Customers Directory</strong>
        <span style="font-size:0.75rem;color:var(--text-muted);">View customer profiles</span>
      </div>
    </a>

    <a href="<?= url('/track-repair') ?>" target="_blank" style="background:#FFFFFF;border:1px solid var(--border-color);border-radius:var(--radius-sm);padding:14px 18px;display:flex;align-items:center;gap:12px;text-decoration:none;box-shadow:var(--shadow-xs);transition:transform 0.15s, border-color 0.15s;" onmouseover="this.style.borderColor='var(--primary-color)';this.style.transform='translateY(-2px)'" onmouseout="this.style.borderColor='var(--border-color)';this.style.transform='none'">
      <div style="width:36px;height:36px;background:rgba(139,92,246,0.1);color:#8B5CF6;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:1rem;">
        <i class="fas fa-external-link-alt"></i>
      </div>
      <div>
        <strong style="font-size:0.9rem;color:var(--text-primary);display:block;">Live Customer Tracker</strong>
        <span style="font-size:0.75rem;color:var(--text-muted);">Public tracking portal</span>
      </div>
    </a>
  </div>
</div>

<!-- ──────────────── Recent Repair Jobs Table ──────────────── -->
<div style="padding:0 24px 24px;">
  <div class="table-card">
    <div style="display:flex;align-items:center;justify-content:space-between;padding:18px 22px;border-bottom:1px solid var(--border-color);background:#FFFFFF;flex-wrap:wrap;gap:12px;">
      <div>
        <h3 style="font-size:1.05rem;font-weight:800;color:var(--text-primary);display:flex;align-items:center;gap:8px;">
          <i class="fas fa-laptop-medical" style="color:var(--primary-color);"></i>
          <span>Workshop Repair Jobs Queue</span>
        </h3>
        <span style="font-size:0.8rem;color:var(--text-muted);">Latest repair tickets in laboratory</span>
      </div>
      <div style="display:flex;gap:8px;align-items:center;">
        <a href="<?= url('/admin/repairs') ?>" class="btn-secondary btn-sm">View Full Queue (<?= (int)($stats['active_repairs'] + $stats['waiting_approval'] + $stats['ready_pickup']) ?> Active) →</a>
      </div>
    </div>

    <?php 
    $displayJobs = !empty($todayJobs) ? $todayJobs : ($recentRepairs ?? []);
    if (empty($displayJobs)): ?>
    <div style="padding:3.5rem 2rem;text-align:center;color:var(--text-muted);">
      <i class="fas fa-clipboard-check" style="font-size:2.8rem;color:#10B981;margin-bottom:14px;display:block;opacity:0.8;"></i>
      <strong style="font-size:1.05rem;color:var(--text-primary);display:block;margin-bottom:4px;">No repair jobs in queue yet.</strong>
      <span>Click "Intake Device" to register your first laptop ticket.</span>
      <div style="margin-top:16px;">
        <a href="<?= url('/admin/repairs/create') ?>" class="btn-primary"><i class="fas fa-plus"></i> Intake New Device</a>
      </div>
    </div>
    <?php else: ?>
    <div style="overflow-x:auto;">
      <table class="data-table">
        <thead>
          <tr>
            <th>Tracking ID</th>
            <th>Customer</th>
            <th>Laptop Device</th>
            <th>Service Type</th>
            <th>Intake Date</th>
            <th>Status</th>
            <th style="text-align:right;">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($displayJobs as $job): ?>
          <tr>
            <td>
              <span style="font-family:monospace;font-weight:800;color:var(--primary-color);background:#EFF6FF;padding:4px 8px;border-radius:6px;font-size:0.875rem;">
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
              <span style="font-size:0.8125rem;color:var(--text-muted);"><?= date('d M Y, h:i A', strtotime($job['received_at'])) ?></span>
            </td>
            <td>
              <span class="status-pill" style="background:<?= ($statusColors[$job['current_status']] ?? '#64748B') ?>1A;color:<?= $statusColors[$job['current_status']] ?? '#64748B' ?>;border:1px solid <?= $statusColors[$job['current_status']] ?? '#64748B' ?>44;">
                <span style="width:6px;height:6px;border-radius:50%;background:currentColor;display:inline-block;"></span>
                <?= htmlspecialchars(\App\Models\RepairJob::statusLabel($job['current_status']), ENT_QUOTES) ?>
              </span>
            </td>
            <td style="text-align:right;">
              <a href="<?= url('/admin/repairs/' . $job['id']) ?>" class="btn-secondary btn-sm">
                <i class="fas fa-folder-open"></i> Open Job
              </a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
  </div>
</div>
