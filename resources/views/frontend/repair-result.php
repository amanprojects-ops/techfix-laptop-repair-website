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
    <div class="section-badge">Repair Status</div>
    <h1><?= htmlspecialchars($repair['tracking_id'], ENT_QUOTES) ?></h1>
    <p>Live status update for your device repair.</p>
  </div>
</section>

<section class="section" style="background:var(--bg);">
  <div class="container" style="max-width:700px;">

    <!-- Status Badge -->
    <div style="text-align:center;margin-bottom:2rem;">
      <div style="display:inline-flex;align-items:center;gap:10px;background:<?= $statusColor ?>22;border:1.5px solid <?= $statusColor ?>;border-radius:999px;padding:10px 24px;">
        <div style="width:10px;height:10px;border-radius:50%;background:<?= $statusColor ?>;box-shadow:0 0 8px <?= $statusColor ?>;"></div>
        <span style="font-size:1rem;font-weight:800;color:<?= $statusColor ?>;"><?= htmlspecialchars($statusLabel, ENT_QUOTES) ?></span>
      </div>
    </div>

    <!-- Repair Info Card -->
    <div class="repair-info-card" style="margin-bottom:2rem;">
      <div class="repair-info-grid">
        <div class="repair-info-item">
          <strong>Repair ID</strong>
          <span><?= htmlspecialchars($repair['tracking_id'], ENT_QUOTES) ?></span>
        </div>
        <div class="repair-info-item">
          <strong>Device</strong>
          <span><?= htmlspecialchars(($repair['device_brand'] ?? '') . ' ' . ($repair['device_model'] ?? ''), ENT_QUOTES) ?></span>
        </div>
        <div class="repair-info-item">
          <strong>Service</strong>
          <span><?= htmlspecialchars($repair['service_name'] ?? 'General Repair', ENT_QUOTES) ?></span>
        </div>
        <div class="repair-info-item">
          <strong>Technician</strong>
          <span><?= htmlspecialchars($repair['technician_name'] ?? 'Being assigned', ENT_QUOTES) ?></span>
        </div>
        <div class="repair-info-item">
          <strong>Date Received</strong>
          <span><?= date('d M Y, h:i A', strtotime($repair['received_at'])) ?></span>
        </div>
        <?php if ($repair['estimated_delivery_at']): ?>
        <div class="repair-info-item">
          <strong>Est. Delivery</strong>
          <span><?= date('d M Y', strtotime($repair['estimated_delivery_at'])) ?></span>
        </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Timeline -->
    <h3 style="font-size:0.9375rem;font-weight:700;color:var(--text);margin-bottom:20px;">Repair Progress</h3>
    <div class="status-timeline">
      <?php foreach (($repair['timeline'] ?? []) as $i => $entry): ?>
      <div class="timeline-item timeline-item--done">
        <div class="timeline-icon"><i data-lucide="check" style="width:14px;height:14px;"></i></div>
        <div class="timeline-content">
          <strong><?= htmlspecialchars(\App\Models\RepairJob::statusLabel($entry['status']), ENT_QUOTES) ?></strong>
          <?php if ($entry['note']): ?>
          <p style="font-size:0.8rem;color:var(--text-muted);margin:3px 0 0;"><?= htmlspecialchars($entry['note'], ENT_QUOTES) ?></p>
          <?php endif; ?>
          <span style="font-size:0.75rem;color:var(--text-muted);"><?= date('d M Y, h:i A', strtotime($entry['created_at'])) ?></span>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Contact note -->
    <div style="margin-top:24px;padding:16px;background:var(--accent-light);border-radius:var(--radius-sm);font-size:0.875rem;color:var(--text);">
      <strong>Questions about your repair?</strong><br />
      Call <a href="tel:+919876543210" style="color:var(--accent);font-weight:600;">+91 98765 43210</a> or
      <a href="https://wa.me/919876543210" style="color:var(--whatsapp,#25d366);font-weight:600;" target="_blank" rel="noopener">WhatsApp us</a>
      with your Repair ID.
    </div>

    <div style="text-align:center;margin-top:2rem;">
      <a href="/track-repair" class="btn btn--secondary">← Track Another Repair</a>
    </div>

  </div>
</section>
