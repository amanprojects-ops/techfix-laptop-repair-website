<?php
$statusColors = [
    'RECEIVED'         => '#3b82f6',
    'DIAGNOSIS'        => '#f59e0b',
    'WAITING_APPROVAL' => '#f97316',
    'APPROVED'         => '#8b5cf6',
    'IN_REPAIR'        => '#06b6d4',
    'QUALITY_CHECK'    => '#a855f7',
    'READY_FOR_PICKUP' => '#10b981',
    'DELIVERED'        => '#22c55e',
    'CANCELLED'        => '#ef4444',
    'ON_HOLD'          => '#64748b',
    'PARTS_PENDING'    => '#f59e0b',
    'UNREPAIRABLE'     => '#dc2626',
];
?>

<header class="header">
  <div class="header-left">
    <button id="sidebar-toggle" class="sidebar-toggle" aria-label="Toggle Sidebar"><i class="fas fa-bars"></i></button>
    <div class="header-title-wrap">
      <h2>Dashboard Overview</h2>
      <span class="header-subtitle">Good <?= date('H') < 12 ? 'Morning' : (date('H') < 17 ? 'Afternoon' : 'Evening') ?>, <?= htmlspecialchars(explode(' ', $user['name'])[0] ?? 'Admin', ENT_QUOTES) ?> 👋</span>
    </div>
  </div>
  <div class="header-right">
    <span class="header-date"><?= date('D, d M Y') ?></span>
    <a href="/admin/repairs/create" class="btn-primary"><i class="fas fa-plus"></i> New Repair</a>
  </div>
</header>

<!-- Stats Row -->
<div class="stats-grid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1.25rem;padding:1.5rem;">
  <div class="stat-card" style="background:var(--card-bg);border:1px solid var(--border);border-radius:12px;padding:1.25rem;">
    <div style="display:flex;align-items:center;gap:12px;margin-bottom:8px;">
      <div style="width:40px;height:40px;background:rgba(6,182,212,0.1);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#06b6d4;font-size:1.1rem;"><i class="fas fa-wrench"></i></div>
      <span style="font-size:0.78rem;color:var(--text-muted);font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Active Repairs</span>
    </div>
    <div style="font-size:2rem;font-weight:800;color:var(--text);"><?= $stats['active_repairs'] ?></div>
  </div>
  <div class="stat-card" style="background:var(--card-bg);border:1px solid var(--border);border-radius:12px;padding:1.25rem;">
    <div style="display:flex;align-items:center;gap:12px;margin-bottom:8px;">
      <div style="width:40px;height:40px;background:rgba(249,115,22,0.1);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#f97316;font-size:1.1rem;"><i class="fas fa-clock"></i></div>
      <span style="font-size:0.78rem;color:var(--text-muted);font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Waiting Approval</span>
    </div>
    <div style="font-size:2rem;font-weight:800;color:var(--text);"><?= $stats['waiting_approval'] ?></div>
  </div>
  <div class="stat-card" style="background:var(--card-bg);border:1px solid var(--border);border-radius:12px;padding:1.25rem;">
    <div style="display:flex;align-items:center;gap:12px;margin-bottom:8px;">
      <div style="width:40px;height:40px;background:rgba(16,185,129,0.1);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#10b981;font-size:1.1rem;"><i class="fas fa-check-circle"></i></div>
      <span style="font-size:0.78rem;color:var(--text-muted);font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Ready Pickup</span>
    </div>
    <div style="font-size:2rem;font-weight:800;color:var(--text);"><?= $stats['ready_pickup'] ?></div>
  </div>
  <div class="stat-card" style="background:var(--card-bg);border:1px solid var(--border);border-radius:12px;padding:1.25rem;">
    <div style="display:flex;align-items:center;gap:12px;margin-bottom:8px;">
      <div style="width:40px;height:40px;background:rgba(139,92,246,0.1);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#8b5cf6;font-size:1.1rem;"><i class="fas fa-rupee-sign"></i></div>
      <span style="font-size:0.78rem;color:var(--text-muted);font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Revenue This Month</span>
    </div>
    <div style="font-size:2rem;font-weight:800;color:var(--text);">₹<?= number_format($stats['revenue_month'], 0) ?></div>
  </div>
  <div class="stat-card" style="background:var(--card-bg);border:1px solid var(--border);border-radius:12px;padding:1.25rem;">
    <div style="display:flex;align-items:center;gap:12px;margin-bottom:8px;">
      <div style="width:40px;height:40px;background:rgba(59,130,246,0.1);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#3b82f6;font-size:1.1rem;"><i class="fas fa-users"></i></div>
      <span style="font-size:0.78rem;color:var(--text-muted);font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Total Customers</span>
    </div>
    <div style="font-size:2rem;font-weight:800;color:var(--text);"><?= $stats['total_customers'] ?></div>
  </div>
  <div class="stat-card" style="background:var(--card-bg);border:1px solid var(--border);border-radius:12px;padding:1.25rem;">
    <div style="display:flex;align-items:center;gap:12px;margin-bottom:8px;">
      <div style="width:40px;height:40px;background:rgba(245,158,11,0.1);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#f59e0b;font-size:1.1rem;"><i class="fas fa-calendar-day"></i></div>
      <span style="font-size:0.78rem;color:var(--text-muted);font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Received Today</span>
    </div>
    <div style="font-size:2rem;font-weight:800;color:var(--text);"><?= $stats['received_today'] ?></div>
  </div>
</div>

<!-- Today's Repairs Table -->
<div style="padding:0 1.5rem 1.5rem;">
  <div class="table-card" style="background:var(--card-bg);border:1px solid var(--border);border-radius:12px;overflow:hidden;">
    <div style="display:flex;align-items:center;justify-content:space-between;padding:1.25rem 1.5rem;border-bottom:1px solid var(--border);">
      <div>
        <h3 style="font-size:1rem;font-weight:700;color:var(--text);">Today's Repairs</h3>
        <span style="font-size:0.8rem;color:var(--text-muted);"><?= count($todayJobs) ?> jobs received today</span>
      </div>
      <a href="/admin/repairs" style="font-size:0.8rem;color:var(--accent);font-weight:600;text-decoration:none;">View All →</a>
    </div>
    <?php if (empty($todayJobs)): ?>
    <div style="padding:2.5rem;text-align:center;color:var(--text-muted);">
      <i class="fas fa-check-circle" style="font-size:2rem;color:#10b981;margin-bottom:12px;display:block;"></i>
      No new repairs today. All caught up!
    </div>
    <?php else: ?>
    <div style="overflow-x:auto;">
      <table class="data-table" style="width:100%;border-collapse:collapse;">
        <thead>
          <tr style="background:var(--table-header);text-align:left;">
            <th style="padding:10px 16px;font-size:0.78rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;">Tracking ID</th>
            <th style="padding:10px 16px;font-size:0.78rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;">Customer</th>
            <th style="padding:10px 16px;font-size:0.78rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;">Device</th>
            <th style="padding:10px 16px;font-size:0.78rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;">Priority</th>
            <th style="padding:10px 16px;font-size:0.78rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;">Status</th>
            <th style="padding:10px 16px;"></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($todayJobs as $job): ?>
          <tr style="border-top:1px solid var(--border);">
            <td style="padding:12px 16px;font-size:0.875rem;font-weight:700;color:var(--accent);">
              <?= htmlspecialchars($job['tracking_id'], ENT_QUOTES) ?>
            </td>
            <td style="padding:12px 16px;font-size:0.875rem;color:var(--text);">
              <?= htmlspecialchars($job['customer_name'], ENT_QUOTES) ?>
            </td>
            <td style="padding:12px 16px;font-size:0.875rem;color:var(--text-muted);">
              <?= htmlspecialchars($job['device_brand'] . ' ' . ($job['device_model'] ?? ''), ENT_QUOTES) ?>
            </td>
            <td style="padding:12px 16px;">
              <?php $pColors = ['urgent'=>'#ef4444','high'=>'#f97316','normal'=>'#3b82f6','low'=>'#64748b']; ?>
              <span style="font-size:0.75rem;font-weight:700;color:<?= $pColors[$job['priority']] ?? '#64748b' ?>;text-transform:uppercase;">
                <?= htmlspecialchars($job['priority'], ENT_QUOTES) ?>
              </span>
            </td>
            <td style="padding:12px 16px;">
              <span style="font-size:0.75rem;font-weight:700;padding:4px 10px;border-radius:999px;background:<?= ($statusColors[$job['current_status']] ?? '#64748b') ?>22;color:<?= $statusColors[$job['current_status']] ?? '#64748b' ?>;">
                <?= htmlspecialchars(\App\Models\RepairJob::statusLabel($job['current_status']), ENT_QUOTES) ?>
              </span>
            </td>
            <td style="padding:12px 16px;text-align:right;">
              <a href="/admin/repairs/<?= $job['id'] ?>" style="font-size:0.8rem;color:var(--accent);font-weight:600;text-decoration:none;">Open →</a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
  </div>
</div>
