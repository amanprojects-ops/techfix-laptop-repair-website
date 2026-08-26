<?php
$statusColors = [
    'RECEIVED'=>'#3B82F6','DIAGNOSIS'=>'#F59E0B','WAITING_APPROVAL'=>'#F97316',
    'APPROVED'=>'#8B5CF6','IN_REPAIR'=>'#06B6D4','QUALITY_CHECK'=>'#A855F7',
    'READY_FOR_PICKUP'=>'#10B981','DELIVERED'=>'#22C55E','CANCELLED'=>'#EF4444',
    'ON_HOLD'=>'#64748B','PARTS_PENDING'=>'#F59E0B','UNREPAIRABLE'=>'#DC2626',
];
?>
<header class="header">
  <div class="header-left">
    <button id="sidebar-toggle" class="sidebar-toggle" aria-label="Toggle Sidebar"><i class="fas fa-bars"></i></button>
    <div class="header-title-wrap">
      <h2>Repair Jobs Queue</h2>
      <span class="header-subtitle">Manage, diagnose, and track all workshop repair cards</span>
    </div>
  </div>
  <div class="header-right">
    <a href="<?= url('/admin/repairs/create') ?>" class="btn-primary"><i class="fas fa-plus"></i> Intake New Device</a>
  </div>
</header>

<div style="padding:24px;">
  <!-- Status filter pills -->
  <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:20px;">
    <a href="<?= url('/admin/repairs') ?>" style="font-size:0.8rem;font-weight:700;padding:7px 16px;border-radius:var(--radius-full);text-decoration:none;background:<?= !$status ? 'var(--primary-color)' : 'var(--bg-card)' ?>;color:<?= !$status ? '#FFFFFF' : 'var(--text-secondary)' ?>;border:1px solid <?= !$status ? 'var(--primary-color)' : 'var(--border-color)' ?>;box-shadow:var(--shadow-xs);">
      All (<?= (int)$total ?>)
    </a>
    <?php foreach ($statuses as $key => $label): ?>
    <a href="<?= url('/admin/repairs?status=' . urlencode($key)) ?>" style="font-size:0.8rem;font-weight:700;padding:7px 16px;border-radius:var(--radius-full);text-decoration:none;background:<?= $status === $key ? ($statusColors[$key] ?? 'var(--primary-color)') : 'var(--bg-card)' ?>;color:<?= $status === $key ? '#FFFFFF' : 'var(--text-secondary)' ?>;border:1px solid <?= $status === $key ? ($statusColors[$key] ?? 'var(--primary-color)') : 'var(--border-color)' ?>;box-shadow:var(--shadow-xs);">
      <?= htmlspecialchars($label, ENT_QUOTES) ?>
    </a>
    <?php endforeach; ?>
  </div>

  <div class="table-card">
    <?php if (empty($repairs)): ?>
    <div style="padding:3.5rem 2rem;text-align:center;color:var(--text-muted);">
      <i class="fas fa-inbox" style="font-size:3rem;margin-bottom:16px;display:block;opacity:.35;"></i>
      <strong style="font-size:1.1rem;color:var(--text-primary);display:block;margin-bottom:6px;">No repair jobs found in this view.</strong>
      <a href="<?= url('/admin/repairs/create') ?>" class="btn-primary" style="margin-top:12px;"><i class="fas fa-plus"></i> Intake New Device</a>
    </div>
    <?php else: ?>
    <div style="overflow-x:auto;">
      <table class="data-table">
        <thead>
          <tr>
            <th>Tracking ID</th>
            <th>Customer</th>
            <th>Laptop Device</th>
            <th>Assigned Engineer</th>
            <th>Intake Date</th>
            <th>Status</th>
            <th style="text-align:right;">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($repairs as $r): ?>
          <tr>
            <td>
              <span style="font-family:monospace;font-weight:800;color:var(--primary-color);background:#EFF6FF;padding:4px 8px;border-radius:6px;font-size:0.875rem;">
                <?= htmlspecialchars($r['tracking_id'], ENT_QUOTES) ?>
              </span>
            </td>
            <td>
              <strong style="display:block;font-size:0.9rem;"><?= htmlspecialchars($r['customer_name'], ENT_QUOTES) ?></strong>
              <span style="font-size:0.8rem;color:var(--text-muted);"><?= htmlspecialchars($r['customer_phone'], ENT_QUOTES) ?></span>
            </td>
            <td>
              <div style="font-weight:600;font-size:0.875rem;"><?= htmlspecialchars($r['device_brand'] . ' ' . ($r['device_model'] ?? ''), ENT_QUOTES) ?></div>
              <div style="font-size:0.78rem;color:var(--text-muted);"><?= htmlspecialchars($r['service_name'] ?? 'General Inspection', ENT_QUOTES) ?></div>
            </td>
            <td>
              <span style="font-size:0.875rem;color:var(--text-secondary);font-weight:500;">
                <i class="fas fa-user-check" style="font-size:12px;color:var(--text-muted);margin-right:4px;"></i>
                <?= htmlspecialchars($r['technician_name'] ?? 'Not Assigned', ENT_QUOTES) ?>
              </span>
            </td>
            <td>
              <span style="font-size:0.8125rem;color:var(--text-muted);"><?= date('d M Y, h:i A', strtotime($r['received_at'])) ?></span>
            </td>
            <td>
              <span class="status-pill" style="background:<?= ($statusColors[$r['current_status']] ?? '#64748B') ?>1A;color:<?= $statusColors[$r['current_status']] ?? '#64748B' ?>;border:1px solid <?= $statusColors[$r['current_status']] ?? '#64748B' ?>44;">
                <span style="width:6px;height:6px;border-radius:50%;background:currentColor;display:inline-block;"></span>
                <?= htmlspecialchars(\App\Models\RepairJob::statusLabel($r['current_status']), ENT_QUOTES) ?>
              </span>
            </td>
            <td style="text-align:right;">
              <a href="<?= url('/admin/repairs/' . $r['id']) ?>" class="btn-secondary btn-sm">
                <i class="fas fa-folder-open"></i> Manage Job
              </a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <?php if ($pages > 1): ?>
    <div style="display:flex;align-items:center;justify-content:center;gap:6px;padding:16px;border-top:1px solid var(--border-color);flex-wrap:wrap;background:#FFFFFF;">
      <?php for ($p = 1; $p <= $pages; $p++): ?>
      <a href="<?= url('/admin/repairs?page=' . $p . ($status ? '&status=' . urlencode($status) : '')) ?>" style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:var(--radius-sm);font-weight:700;font-size:0.875rem;text-decoration:none;border:1px solid <?= $p === $page ? 'var(--primary-color)' : 'var(--border-color)' ?>;background:<?= $p === $page ? 'var(--primary-color)' : 'var(--white)' ?>;color:<?= $p === $page ? '#FFFFFF' : 'var(--text-secondary)' ?>;">
        <?= $p ?>
      </a>
      <?php endfor; ?>
    </div>
    <?php endif; ?>
    <?php endif; ?>
  </div>
</div>
