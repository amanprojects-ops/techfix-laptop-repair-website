<?php
$statusColors = [
    'RECEIVED'=>'#3b82f6','DIAGNOSIS'=>'#f59e0b','WAITING_APPROVAL'=>'#f97316',
    'APPROVED'=>'#8b5cf6','IN_REPAIR'=>'#06b6d4','QUALITY_CHECK'=>'#a855f7',
    'READY_FOR_PICKUP'=>'#10b981','DELIVERED'=>'#22c55e','CANCELLED'=>'#ef4444',
    'ON_HOLD'=>'#64748b','PARTS_PENDING'=>'#f59e0b','UNREPAIRABLE'=>'#dc2626',
];
?>
<header class="header">
  <div class="header-left">
    <button id="sidebar-toggle" class="sidebar-toggle"><i class="fas fa-bars"></i></button>
    <div class="header-title-wrap">
      <h2>Repair Jobs</h2>
      <span class="header-subtitle">Manage all repair jobs in the queue</span>
    </div>
  </div>
  <div class="header-right">
    <a href="/admin/repairs/create" class="btn-primary"><i class="fas fa-plus"></i> Intake New Device</a>
  </div>
</header>

<div style="padding:1.5rem;">
  <!-- Status filter pills -->
  <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:1.5rem;">
    <a href="/admin/repairs" style="font-size:0.78rem;font-weight:700;padding:6px 14px;border-radius:999px;text-decoration:none;background:<?= !$status ? 'var(--accent)' : 'var(--card-bg)' ?>;color:<?= !$status ? '#fff' : 'var(--text-muted)' ?>;border:1px solid <?= !$status ? 'var(--accent)' : 'var(--border)' ?>;">All (<?= $total ?>)</a>
    <?php foreach ($statuses as $key => $label): ?>
    <a href="/admin/repairs?status=<?= urlencode($key) ?>" style="font-size:0.78rem;font-weight:700;padding:6px 14px;border-radius:999px;text-decoration:none;background:<?= $status === $key ? ($statusColors[$key] ?? 'var(--accent)') : 'var(--card-bg)' ?>;color:<?= $status === $key ? '#fff' : 'var(--text-muted)' ?>;border:1px solid <?= $status === $key ? ($statusColors[$key] ?? 'var(--accent)') : 'var(--border)' ?>;">
      <?= htmlspecialchars($label, ENT_QUOTES) ?>
    </a>
    <?php endforeach; ?>
  </div>

  <div class="table-card" style="background:var(--card-bg);border:1px solid var(--border);border-radius:12px;overflow:hidden;">
    <?php if (empty($repairs)): ?>
    <div style="padding:3rem;text-align:center;color:var(--text-muted);">
      <i class="fas fa-inbox" style="font-size:2.5rem;margin-bottom:12px;display:block;opacity:.4;"></i>
      No repair jobs found.
      <br><a href="/admin/repairs/create" style="color:var(--accent);font-weight:600;margin-top:8px;display:inline-block;">Create the first one →</a>
    </div>
    <?php else: ?>
    <div style="overflow-x:auto;">
      <table style="width:100%;border-collapse:collapse;">
        <thead>
          <tr style="background:var(--table-header);">
            <th style="padding:10px 16px;text-align:left;font-size:0.78rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;">Tracking ID</th>
            <th style="padding:10px 16px;text-align:left;font-size:0.78rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;">Customer</th>
            <th style="padding:10px 16px;text-align:left;font-size:0.78rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;">Device</th>
            <th style="padding:10px 16px;text-align:left;font-size:0.78rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;">Technician</th>
            <th style="padding:10px 16px;text-align:left;font-size:0.78rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;">Received</th>
            <th style="padding:10px 16px;text-align:left;font-size:0.78rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;">Status</th>
            <th style="padding:10px 16px;"></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($repairs as $r): ?>
          <tr style="border-top:1px solid var(--border);">
            <td style="padding:12px 16px;font-weight:700;color:var(--accent);font-size:0.875rem;"><?= htmlspecialchars($r['tracking_id'], ENT_QUOTES) ?></td>
            <td style="padding:12px 16px;">
              <div style="font-size:0.875rem;font-weight:600;color:var(--text);"><?= htmlspecialchars($r['customer_name'], ENT_QUOTES) ?></div>
              <div style="font-size:0.78rem;color:var(--text-muted);"><?= htmlspecialchars($r['customer_phone'], ENT_QUOTES) ?></div>
            </td>
            <td style="padding:12px 16px;font-size:0.875rem;color:var(--text-muted);"><?= htmlspecialchars($r['device_brand'] . ' ' . ($r['device_model'] ?? ''), ENT_QUOTES) ?></td>
            <td style="padding:12px 16px;font-size:0.875rem;color:var(--text-muted);"><?= htmlspecialchars($r['technician_name'] ?? '—', ENT_QUOTES) ?></td>
            <td style="padding:12px 16px;font-size:0.8rem;color:var(--text-muted);"><?= date('d M Y', strtotime($r['received_at'])) ?></td>
            <td style="padding:12px 16px;">
              <span style="font-size:0.72rem;font-weight:700;padding:4px 10px;border-radius:999px;background:<?= ($statusColors[$r['current_status']] ?? '#64748b') ?>22;color:<?= $statusColors[$r['current_status']] ?? '#64748b' ?>;">
                <?= htmlspecialchars(\App\Models\RepairJob::statusLabel($r['current_status']), ENT_QUOTES) ?>
              </span>
            </td>
            <td style="padding:12px 16px;text-align:right;">
              <a href="/admin/repairs/<?= $r['id'] ?>" style="font-size:0.8rem;color:var(--accent);font-weight:600;text-decoration:none;">Open →</a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <!-- Pagination -->
    <?php if ($pages > 1): ?>
    <div style="display:flex;gap:8px;padding:1rem 1.5rem;border-top:1px solid var(--border);flex-wrap:wrap;">
      <?php for ($i = 1; $i <= $pages; $i++): ?>
      <a href="/admin/repairs?page=<?= $i ?><?= $status ? '&status='.urlencode($status) : '' ?>"
         style="font-size:0.8rem;font-weight:700;padding:5px 12px;border-radius:6px;text-decoration:none;
                background:<?= $i == $page ? 'var(--accent)' : 'var(--card-bg)' ?>;
                color:<?= $i == $page ? '#fff' : 'var(--text-muted)' ?>;
                border:1px solid <?= $i == $page ? 'var(--accent)' : 'var(--border)' ?>;"><?= $i ?></a>
      <?php endfor; ?>
    </div>
    <?php endif; ?>
    <?php endif; ?>
  </div>
</div>
