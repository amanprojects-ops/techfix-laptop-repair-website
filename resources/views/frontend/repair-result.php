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

$currentStatus = $repair['current_status'] ?? 'RECEIVED';
$statusColor   = $statusColors[$currentStatus] ?? '#64748B';
$statusLabel   = \App\Models\RepairJob::statusLabel($currentStatus);

$finalAmount = (float)($repair['final_amount'] ?? 0);
$estAmount   = (float)($repair['estimated_amount'] ?? 0);
$totalPaid   = (float)($repair['paid'] ?? 0);
$balanceDue  = max(0, $finalAmount - $totalPaid);

// Main stage sequence for visual progress bar
$mainStages = [
    'RECEIVED'         => ['title' => 'Received',      'icon' => 'package'],
    'DIAGNOSIS'        => ['title' => 'Diagnosis',     'icon' => 'search'],
    'IN_REPAIR'        => ['title' => 'In Repair',     'icon' => 'wrench'],
    'QUALITY_CHECK'    => ['title' => 'Testing',       'icon' => 'check-circle'],
    'READY_FOR_PICKUP' => ['title' => 'Ready',         'icon' => 'package-check'],
    'DELIVERED'        => ['title' => 'Delivered',     'icon' => 'shield-check'],
];

$stageOrder = array_keys($mainStages);
$currentStageIndex = array_search($currentStatus, $stageOrder);
if ($currentStageIndex === false) {
    if (in_array($currentStatus, ['WAITING_APPROVAL', 'APPROVED'])) $currentStageIndex = 1;
    elseif (in_array($currentStatus, ['ON_HOLD', 'PARTS_PENDING'])) $currentStageIndex = 2;
    else $currentStageIndex = 0;
}
?>

<!-- PAGE HERO -->
<section class="page-hero">
  <div class="container">
    <div class="section-badge"><i data-lucide="activity"></i> Live Customer Tracker</div>
    <h1><?= htmlspecialchars($repair['tracking_id'], ENT_QUOTES) ?></h1>
    <p>Real-time repair lifecycle, hardware diagnostic notes, photos &amp; billing summary</p>
  </div>
</section>

<section class="section" style="background:var(--bg);padding:48px 0 80px;">
  <div class="container" style="max-width:880px;">

    <!-- 1. Top Status Highlight Card -->
    <div style="background:#FFFFFF;border:1.5px solid var(--border);border-radius:var(--radius-lg);padding:28px 24px;box-shadow:var(--shadow);margin-bottom:28px;text-align:center;position:relative;overflow:hidden;">
      <div style="position:absolute;top:0;left:0;right:0;height:4px;background:<?= $statusColor ?>;"></div>
      
      <div style="display:inline-flex;align-items:center;gap:10px;background:<?= $statusColor ?>15;border:1.5px solid <?= $statusColor ?>44;border-radius:var(--radius-full);padding:8px 24px;margin-bottom:18px;">
        <span style="width:10px;height:10px;border-radius:50%;background:<?= $statusColor ?>;box-shadow:0 0 10px <?= $statusColor ?>;"></span>
        <span style="font-size:1.15rem;font-weight:900;color:<?= $statusColor ?>;letter-spacing:0.5px;text-transform:uppercase;">
          <?= htmlspecialchars($statusLabel, ENT_QUOTES) ?>
        </span>
      </div>

      <!-- Stage Stepper Progress -->
      <div style="display:flex;align-items:center;justify-content:space-between;margin-top:16px;overflow-x:auto;padding:12px 6px;">
        <?php foreach ($mainStages as $k => $st): 
          $idx = array_search($k, $stageOrder);
          $isDone = ($idx <= $currentStageIndex);
          $isActive = ($idx === $currentStageIndex);
        ?>
        <div style="display:flex;flex-direction:column;align-items:center;gap:6px;min-width:64px;flex:1;position:relative;">
          <div style="width:36px;height:36px;border-radius:50%;background:<?= $isDone ? ($isActive ? $statusColor : '#10B981') : '#F1F5F9' ?>;color:<?= $isDone ? '#FFFFFF' : '#94A3B8' ?>;display:flex;align-items:center;justify-content:center;border:2px solid <?= $isDone ? 'transparent' : '#CBD5E1' ?>;box-shadow:<?= $isActive ? '0 0 14px ' . $statusColor . '55' : 'none' ?>;">
            <i data-lucide="<?= $st['icon'] ?>" style="width:16px;height:16px;"></i>
          </div>
          <span style="font-size:0.75rem;font-weight:<?= $isDone ? '800' : '600' ?>;color:<?= $isDone ? 'var(--text)' : 'var(--text-muted)' ?>;"><?= $st['title'] ?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- 2. Device & Intake Overview -->
    <div class="repair-info-card" style="margin-bottom:24px;">
      <div style="display:flex;align-items:center;gap:10px;margin-bottom:18px;border-bottom:1px solid var(--border);padding-bottom:12px;">
        <div style="width:34px;height:34px;background:var(--accent-light);color:var(--accent);border-radius:8px;display:flex;align-items:center;justify-content:center;">
          <i data-lucide="laptop" style="width:18px;height:18px;"></i>
        </div>
        <h3 style="font-size:1.15rem;font-weight:800;color:var(--text);">Device &amp; Ticket Information</h3>
      </div>

      <div class="repair-info-grid">
        <div class="repair-info-item">
          <strong>Tracking ID</strong>
          <span style="color:var(--accent);font-family:monospace;font-size:1.05rem;"><?= htmlspecialchars($repair['tracking_id'], ENT_QUOTES) ?></span>
        </div>
        <div class="repair-info-item">
          <strong>Laptop Model</strong>
          <span><?= htmlspecialchars(($repair['device_brand'] ?? '') . ' ' . ($repair['device_model'] ?? ''), ENT_QUOTES) ?></span>
        </div>
        <div class="repair-info-item">
          <strong>Customer Name</strong>
          <span><?= htmlspecialchars($repair['customer_name'] ?? 'Customer', ENT_QUOTES) ?></span>
        </div>
        <div class="repair-info-item">
          <strong>Service Type</strong>
          <span><?= htmlspecialchars($repair['service_name'] ?? 'Hardware Repair / Inspection', ENT_QUOTES) ?></span>
        </div>
        <div class="repair-info-item">
          <strong>Assigned Engineer</strong>
          <span><?= htmlspecialchars($repair['technician_name'] ?? 'Workshop Senior Tech', ENT_QUOTES) ?></span>
        </div>
        <div class="repair-info-item">
          <strong>Intake Date</strong>
          <span><?= date('d M Y, h:i A', strtotime($repair['received_at'])) ?></span>
        </div>
        <?php if (!empty($repair['completed_at'])): ?>
        <div class="repair-info-item">
          <strong>Completed On</strong>
          <span style="color:#10B981;font-weight:800;"><?= date('d M Y, h:i A', strtotime($repair['completed_at'])) ?></span>
        </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- 3. Problem Complaint & Technician Diagnosis -->
    <div style="background:#FFFFFF;border:1px solid var(--border);border-radius:var(--radius-lg);padding:24px;box-shadow:var(--shadow-sm);margin-bottom:24px;">
      <div style="display:flex;align-items:center;gap:10px;margin-bottom:16px;">
        <div style="width:34px;height:34px;background:var(--accent-light);color:var(--accent);border-radius:8px;display:flex;align-items:center;justify-content:center;">
          <i data-lucide="clipboard-check" style="width:18px;height:18px;"></i>
        </div>
        <h3 style="font-size:1.15rem;font-weight:800;color:var(--text);">Diagnostics &amp; Problem Details</h3>
      </div>

      <div style="display:flex;flex-direction:column;gap:14px;">
        <div style="background:var(--bg);padding:14px 18px;border-radius:var(--radius-sm);border-left:4px solid #94A3B8;">
          <span style="font-size:0.75rem;font-weight:800;text-transform:uppercase;color:var(--text-muted);letter-spacing:0.5px;display:block;margin-bottom:4px;">Customer Reported Issue</span>
          <p style="font-size:0.92rem;color:var(--text);line-height:1.6;">
            <?= htmlspecialchars($repair['problem_description'] ?: 'General hardware inspection requested.', ENT_QUOTES) ?>
          </p>
        </div>

        <?php if (!empty($repair['diagnosis'])): ?>
        <div style="background:rgba(37,99,235,0.06);padding:14px 18px;border-radius:var(--radius-sm);border-left:4px solid var(--accent);">
          <span style="font-size:0.75rem;font-weight:800;text-transform:uppercase;color:var(--accent);letter-spacing:0.5px;display:block;margin-bottom:4px;">
            <i data-lucide="check-circle" style="width:14px;height:14px;display:inline-block;vertical-align:middle;margin-right:2px;"></i> Certified Engineer Diagnostic Findings
          </span>
          <p style="font-size:0.95rem;color:var(--text);font-weight:600;line-height:1.6;">
            <?= nl2br(htmlspecialchars($repair['diagnosis'], ENT_QUOTES)) ?>
          </p>
        </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- 4. Hardware Photos Gallery -->
    <div style="background:#FFFFFF;border:1px solid var(--border);border-radius:var(--radius-lg);padding:24px;box-shadow:var(--shadow-sm);margin-bottom:24px;">
      <div style="display:flex;align-items:center;gap:10px;margin-bottom:18px;">
        <div style="width:34px;height:34px;background:var(--accent-light);color:var(--accent);border-radius:8px;display:flex;align-items:center;justify-content:center;">
          <i data-lucide="camera" style="width:18px;height:18px;"></i>
        </div>
        <h3 style="font-size:1.15rem;font-weight:800;color:var(--text);">Workshop Hardware Photos</h3>
        <?php if (!empty($repair['images'])): ?>
        <span style="font-size:0.75rem;background:var(--accent-light);color:var(--accent);font-weight:800;padding:2px 8px;border-radius:var(--radius-full);margin-left:auto;">
          <?= count($repair['images']) ?> Photos
        </span>
        <?php endif; ?>
      </div>

      <?php if (!empty($repair['images'])): ?>
      <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(160px, 1fr));gap:14px;">
        <?php foreach ($repair['images'] as $img): 
          $filename = basename($img['file_path']);
          $imgSrc   = '/uploads/repair-images/' . urlencode($filename);
        ?>
        <a href="<?= $imgSrc ?>" target="_blank" rel="noopener" style="text-decoration:none;display:block;border-radius:var(--radius-sm);overflow:hidden;border:1.5px solid var(--border);box-shadow:var(--shadow-xs);position:relative;background:#000;">
          <img src="<?= $imgSrc ?>" alt="Hardware photo" style="width:100%;aspect-ratio:4/3;object-fit:cover;transition:transform 0.2s;" onmouseover="this.style.transform='scale(1.04)'" onmouseout="this.style.transform='scale(1)'" />
          <div style="position:absolute;bottom:0;left:0;right:0;background:rgba(10,15,30,0.85);backdrop-filter:blur(4px);color:#FFFFFF;font-size:0.7rem;font-weight:800;text-align:center;padding:5px 8px;text-transform:uppercase;letter-spacing:0.5px;">
            <?= htmlspecialchars($img['type'], ENT_QUOTES) ?>
          </div>
        </a>
        <?php endforeach; ?>
      </div>
      <span style="font-size:0.78rem;color:var(--text-muted);display:block;margin-top:12px;text-align:center;">Click any photo to view full high-resolution image</span>
      <?php else: ?>
      <div style="padding:18px;text-align:center;background:var(--bg);border-radius:var(--radius-sm);color:var(--text-muted);font-size:0.875rem;">
        <i data-lucide="image" style="width:28px;height:28px;margin:0 auto 8px;opacity:0.4;display:block;"></i>
        <span>No hardware photos attached by technician yet.</span>
      </div>
      <?php endif; ?>
    </div>

    <!-- 5. Invoicing & Payment Receipts -->
    <div style="background:#FFFFFF;border:1px solid var(--border);border-radius:var(--radius-lg);padding:24px;box-shadow:var(--shadow-sm);margin-bottom:24px;">
      <div style="display:flex;align-items:center;gap:10px;margin-bottom:18px;">
        <div style="width:34px;height:34px;background:var(--accent-light);color:var(--accent);border-radius:8px;display:flex;align-items:center;justify-content:center;">
          <i data-lucide="receipt" style="width:18px;height:18px;"></i>
        </div>
        <h3 style="font-size:1.15rem;font-weight:800;color:var(--text);">Billing &amp; Payment Receipts</h3>
      </div>

      <!-- Financial Summary Grid -->
      <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(180px, 1fr));gap:14px;margin-bottom:20px;">
        <div style="background:var(--bg);padding:14px;border-radius:var(--radius-sm);border:1px solid var(--border);">
          <span style="font-size:0.75rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;">Estimated Cost</span>
          <div style="font-size:1.2rem;font-weight:800;color:var(--text);margin-top:2px;">
            <?= $estAmount > 0 ? '₹' . number_format($estAmount, 0) : 'Quote on Check' ?>
          </div>
        </div>

        <div style="background:var(--bg);padding:14px;border-radius:var(--radius-sm);border:1px solid var(--border);">
          <span style="font-size:0.75rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;">Final Bill Amount</span>
          <div style="font-size:1.25rem;font-weight:900;color:var(--accent);margin-top:2px;">
            <?= $finalAmount > 0 ? '₹' . number_format($finalAmount, 2) : 'Pending' ?>
          </div>
        </div>

        <div style="background:#ECFDF5;padding:14px;border-radius:var(--radius-sm);border:1px solid #A7F3D0;">
          <span style="font-size:0.75rem;font-weight:700;color:#065F46;text-transform:uppercase;">Total Paid</span>
          <div style="font-size:1.25rem;font-weight:900;color:#10B981;margin-top:2px;">
            ₹<?= number_format($totalPaid, 2) ?>
          </div>
        </div>

        <?php if ($balanceDue > 0): ?>
        <div style="background:#FEF2F2;padding:14px;border-radius:var(--radius-sm);border:1px solid #FECACA;">
          <span style="font-size:0.75rem;font-weight:700;color:#991B1B;text-transform:uppercase;">Balance Due</span>
          <div style="font-size:1.25rem;font-weight:900;color:#DC2626;margin-top:2px;">
            ₹<?= number_format($balanceDue, 2) ?>
          </div>
        </div>
        <?php endif; ?>
      </div>

      <!-- Payment Transactions Log -->
      <?php if (!empty($repair['payments'])): ?>
      <div style="border-top:1px solid var(--border);padding-top:16px;">
        <h4 style="font-size:0.875rem;font-weight:800;color:var(--text-muted);text-transform:uppercase;margin-bottom:12px;">Payment Transactions Recorded</h4>
        <div style="display:flex;flex-direction:column;gap:8px;">
          <?php foreach ($repair['payments'] as $p): ?>
          <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 14px;background:var(--bg);border-radius:var(--radius-sm);border:1px solid var(--border);flex-wrap:wrap;gap:8px;">
            <div style="display:flex;align-items:center;gap:10px;">
              <span style="background:#ECFDF5;color:#065F46;border:1px solid #A7F3D0;padding:3px 8px;border-radius:4px;font-size:0.75rem;font-weight:800;text-transform:uppercase;">
                <?= htmlspecialchars($p['payment_method'], ENT_QUOTES) ?>
              </span>
              <div>
                <span style="font-size:0.875rem;font-weight:700;color:var(--text);">₹<?= number_format((float)$p['amount'], 2) ?></span>
                <?php if (!empty($p['transaction_id'])): ?>
                <span style="font-size:0.75rem;color:var(--text-muted);margin-left:6px;font-family:monospace;">(Ref: <?= htmlspecialchars($p['transaction_id'], ENT_QUOTES) ?>)</span>
                <?php endif; ?>
              </div>
            </div>
            <span style="font-size:0.8rem;color:var(--text-muted);"><?= date('d M Y, h:i A', strtotime($p['paid_at'] ?? $p['created_at'])) ?></span>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>
    </div>

    <!-- 6. Timeline History & Audit Log -->
    <div style="background:#FFFFFF;border:1px solid var(--border);border-radius:var(--radius-lg);padding:24px;box-shadow:var(--shadow-sm);margin-bottom:28px;">
      <div style="display:flex;align-items:center;gap:10px;margin-bottom:20px;">
        <div style="width:34px;height:34px;background:var(--accent-light);color:var(--accent);border-radius:8px;display:flex;align-items:center;justify-content:center;">
          <i data-lucide="git-commit" style="width:18px;height:18px;"></i>
        </div>
        <h3 style="font-size:1.15rem;font-weight:800;color:var(--text);">Repair History &amp; Timeline</h3>
      </div>

      <div class="status-timeline">
        <?php foreach (($repair['timeline'] ?? []) as $i => $entry): ?>
        <div class="timeline-item timeline-item--done">
          <div class="timeline-icon"><i data-lucide="check" style="width:14px;height:14px;"></i></div>
          <div class="timeline-content">
            <strong><?= htmlspecialchars(\App\Models\RepairJob::statusLabel($entry['status']), ENT_QUOTES) ?></strong>
            <?php if (!empty($entry['note'])): ?>
            <p style="font-size:0.875rem;color:var(--text-muted);margin:4px 0 0;line-height:1.5;"><?= htmlspecialchars($entry['note'], ENT_QUOTES) ?></p>
            <?php endif; ?>
            <span style="font-size:0.75rem;color:var(--text-muted);display:block;margin-top:3px;"><?= date('d M Y, h:i A', strtotime($entry['created_at'])) ?></span>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- 7. Support & Actions -->
    <div style="padding:20px 24px;background:var(--accent-light);border:1px solid rgba(37, 99, 235, 0.2);border-radius:var(--radius-lg);display:flex;align-items:center;justify-content:space-between;gap:20px;flex-wrap:wrap;">
      <div>
        <strong style="color:var(--accent);font-size:1rem;display:block;margin-bottom:4px;">Have questions about this repair?</strong>
        <span style="font-size:0.875rem;color:var(--text);">Our technician is available on call &amp; WhatsApp for any queries.</span>
      </div>
      <div style="display:flex;gap:10px;flex-wrap:wrap;">
        <a href="https://wa.me/919876543210?text=<?= urlencode('Hi TechFix, I am inquiring about my Repair Ticket ID: ' . $repair['tracking_id']) ?>" target="_blank" rel="noopener" class="btn btn--whatsapp btn--sm">
          <i data-lucide="message-circle"></i> WhatsApp Us
        </a>
        <a href="tel:+919876543210" class="btn btn--primary btn--sm">
          <i data-lucide="phone"></i> Call Workshop
        </a>
      </div>
    </div>

    <div style="text-align:center;margin-top:28px;">
      <a href="/track-repair" class="btn btn--secondary"><i data-lucide="arrow-left"></i> Track Another Device</a>
    </div>

  </div>
</section>
