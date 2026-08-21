<?php
$statusColors = [
    'RECEIVED'=>'#3b82f6','DIAGNOSIS'=>'#f59e0b','WAITING_APPROVAL'=>'#f97316',
    'APPROVED'=>'#8b5cf6','IN_REPAIR'=>'#06b6d4','QUALITY_CHECK'=>'#a855f7',
    'READY_FOR_PICKUP'=>'#10b981','DELIVERED'=>'#22c55e','CANCELLED'=>'#ef4444',
    'ON_HOLD'=>'#64748b','PARTS_PENDING'=>'#f59e0b','UNREPAIRABLE'=>'#dc2626',
];
$statusColor = $statusColors[$repair['current_status']] ?? '#64748b';
$statusLabel = \App\Models\RepairJob::statusLabel($repair['current_status']);
?>

<section class="page-hero">
  <div class="container">
    <div class="section-badge"><i data-lucide="activity"></i> Tracking Status</div>
    <h1><?= htmlspecialchars($repair['tracking_id'], ENT_QUOTES) ?></h1>
    <p>Live real-time progress update for your device.</p>
  </div>
</section>

<section class="section" style="background:var(--bg);">
  <div class="container" style="max-width:720px;">

    <!-- Status Glow Badge -->
    <div style="text-align:center;margin-bottom:2.25rem;">
      <div style="display:inline-flex;align-items:center;gap:12px;background:#FFFFFF;border:2px solid <?= $statusColor ?>;border-radius:var(--radius-full);padding:10px 28px;box-shadow:0 8px 24px <?= $statusColor ?>22;">
        <div style="width:12px;height:12px;border-radius:50%;background:<?= $statusColor ?>;box-shadow:0 0 10px <?= $statusColor ?>;"></div>
        <span style="font-size:1.05rem;font-weight:900;color:<?= $statusColor ?>;letter-spacing:0.5px;"><?= htmlspecialchars($statusLabel, ENT_QUOTES) ?></span>
      </div>
    </div>

    <!-- Repair Info Card -->
    <div class="repair-info-card" style="margin-bottom:2rem;">
      <div class="repair-info-grid">
        <div class="repair-info-item">
          <strong>Repair ID</strong>
          <span style="color:var(--accent);font-family:monospace;"><?= htmlspecialchars($repair['tracking_id'], ENT_QUOTES) ?></span>
        </div>
        <div class="repair-info-item">
          <strong>Laptop Device</strong>
          <span><?= htmlspecialchars(($repair['device_brand'] ?? '') . ' ' . ($repair['device_model'] ?? ''), ENT_QUOTES) ?></span>
        </div>
        <div class="repair-info-item">
          <strong>Service Type</strong>
          <span><?= htmlspecialchars($repair['service_name'] ?? 'General Inspection', ENT_QUOTES) ?></span>
        </div>
        <div class="repair-info-item">
          <strong>Assigned Engineer</strong>
          <span><?= htmlspecialchars($repair['technician_name'] ?? 'Workshop Senior Tech', ENT_QUOTES) ?></span>
        </div>
        <div class="repair-info-item">
          <strong>Intake Date</strong>
          <span><?= date('d M Y, h:i A', strtotime($repair['received_at'])) ?></span>
        </div>
        <?php if ($repair['estimated_delivery_at']): ?>
        <div class="repair-info-item">
          <strong>Est. Completion</strong>
          <span><?= date('d M Y', strtotime($repair['estimated_delivery_at'])) ?></span>
        </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Timeline Progress -->
    <div style="background:#FFFFFF;border:1px solid var(--border);border-radius:var(--radius-lg);padding:24px;box-shadow:var(--shadow-sm);margin-bottom:2rem;">
      <h3 style="font-size:1.05rem;font-weight:800;color:var(--text);margin-bottom:20px;display:flex;align-items:center;gap:8px;">
        <i data-lucide="git-commit" style="color:var(--accent);width:18px;height:18px;"></i>
        <span>Repair History &amp; Audit Log</span>
      </h3>
      <div class="status-timeline">
        <?php foreach (($repair['timeline'] ?? []) as $i => $entry): ?>
        <div class="timeline-item timeline-item--done">
          <div class="timeline-icon"><i data-lucide="check" style="width:14px;height:14px;"></i></div>
          <div class="timeline-content">
            <strong><?= htmlspecialchars(\App\Models\RepairJob::statusLabel($entry['status']), ENT_QUOTES) ?></strong>
            <?php if ($entry['note']): ?>
            <p style="font-size:0.85rem;color:var(--text-muted);margin:4px 0 0;line-height:1.5;"><?= htmlspecialchars($entry['note'], ENT_QUOTES) ?></p>
            <?php endif; ?>
            <span style="font-size:0.75rem;color:var(--text-muted);display:block;margin-top:2px;"><?= date('d M Y, h:i A', strtotime($entry['created_at'])) ?></span>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Contact note -->
    <div style="padding:18px 20px;background:var(--accent-light);border:1px solid rgba(37, 99, 235, 0.18);border-radius:var(--radius-sm);font-size:0.875rem;color:var(--text);margin-bottom:24px;">
      <strong style="color:var(--accent);display:block;margin-bottom:4px;">Need assistance regarding your repair?</strong>
      Call <a href="tel:+919876543210" style="color:var(--accent);font-weight:700;">+91 98765 43210</a> or
      <a href="https://wa.me/919876543210" style="color:#16A34A;font-weight:700;" target="_blank" rel="noopener">WhatsApp Support</a>
      quoting Repair ID: <strong><?= htmlspecialchars($repair['tracking_id'], ENT_QUOTES) ?></strong>.
    </div>

    <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
      <a href="/track-repair" class="btn btn--secondary"><i data-lucide="arrow-left"></i> Track Another Device</a>
      <a href="tel:+919876543210" class="btn btn--primary"><i data-lucide="phone"></i> Call Workshop</a>
    </div>

  </div>
</section>
