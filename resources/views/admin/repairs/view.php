<?php
$statusColors = [
    'RECEIVED'=>'#3B82F6','DIAGNOSIS'=>'#F59E0B','WAITING_APPROVAL'=>'#F97316',
    'APPROVED'=>'#8B5CF6','IN_REPAIR'=>'#06B6D4','QUALITY_CHECK'=>'#A855F7',
    'READY_FOR_PICKUP'=>'#10B981','DELIVERED'=>'#22C55E','CANCELLED'=>'#EF4444',
    'ON_HOLD'=>'#64748B','PARTS_PENDING'=>'#F59E0B','UNREPAIRABLE'=>'#DC2626',
];
$currentStatus = $repair['current_status'];
$statusColor   = $statusColors[$currentStatus] ?? '#64748B';
$paid          = (float)($totalPaid ?? 0);
$final         = (float)($repair['final_amount'] ?? 0);
$balance       = max(0, $final - $paid);
?>

<header class="header">
  <div class="header-left">
    <button id="sidebar-toggle" class="sidebar-toggle" aria-label="Toggle Sidebar"><i class="fas fa-bars"></i></button>
    <div class="header-title-wrap">
      <h2>Ticket: <?= htmlspecialchars($repair['tracking_id'], ENT_QUOTES) ?></h2>
      <span class="header-subtitle">Intake: <?= date('d M Y, h:i A', strtotime($repair['received_at'])) ?></span>
    </div>
  </div>
  <div class="header-right">
    <button onclick="window.print()" class="btn-secondary"><i class="fas fa-print"></i> Print Job Card</button>
    <a href="/admin/repairs" class="btn-secondary"><i class="fas fa-arrow-left"></i> Queue</a>
  </div>
</header>

<?php if (!empty($flash_success)): ?>
<div style="background:#ECFDF5;border-bottom:1px solid #A7F3D0;color:#065F46;padding:12px 24px;font-size:0.9rem;font-weight:700;display:flex;align-items:center;gap:8px;">
  <i class="fas fa-check-circle" style="color:#10B981;"></i>
  <span><?= htmlspecialchars($flash_success, ENT_QUOTES) ?></span>
</div>
<?php endif; ?>
<?php if (!empty($flash_error)): ?>
<div style="background:#FEF2F2;border-bottom:1px solid #FECACA;color:#991B1B;padding:12px 24px;font-size:0.9rem;font-weight:700;display:flex;align-items:center;gap:8px;">
  <i class="fas fa-exclamation-triangle" style="color:#EF4444;"></i>
  <span><?= htmlspecialchars($flash_error, ENT_QUOTES) ?></span>
</div>
<?php endif; ?>

<div style="padding:24px;display:grid;grid-template-columns:1fr 340px;gap:24px;">

  <!-- Left Column -->
  <div style="display:flex;flex-direction:column;gap:20px;">

    <!-- Current Status Header Bar -->
    <div class="form-card" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
      <div style="display:flex;align-items:center;gap:14px;">
        <div style="width:14px;height:14px;border-radius:50%;background:<?= $statusColor ?>;box-shadow:0 0 10px <?= $statusColor ?>;flex-shrink:0;"></div>
        <div>
          <span style="font-size:0.75rem;font-weight:800;text-transform:uppercase;letter-spacing:.6px;color:var(--text-muted);display:block;">Current Status</span>
          <span style="font-size:1.2rem;font-weight:900;color:<?= $statusColor ?>;"><?= htmlspecialchars(\App\Models\RepairJob::statusLabel($currentStatus), ENT_QUOTES) ?></span>
        </div>
      </div>

      <!-- Quick status update form -->
      <?php if (!empty($transitions)): ?>
      <form method="POST" action="/admin/repairs/<?= $repair['id'] ?>/status" style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES) ?>" />
        <select name="status" class="form-control" style="width:auto;font-weight:600;">
          <?php foreach ($transitions as $s): ?>
          <option value="<?= $s ?>"><?= htmlspecialchars(\App\Models\RepairJob::statusLabel($s), ENT_QUOTES) ?></option>
          <?php endforeach; ?>
        </select>
        <input type="text" name="note" placeholder="Status log note..." class="form-control" style="width:180px;" />
        <button type="submit" class="btn-primary btn-sm"><i class="fas fa-sync-alt"></i> Update</button>
      </form>
      <?php endif; ?>
    </div>

    <!-- Customer & Device Info -->
    <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(280px, 1fr));gap:20px;">
      <div class="form-card">
        <div style="font-size:0.75rem;font-weight:800;text-transform:uppercase;letter-spacing:.8px;color:var(--primary-color);margin-bottom:14px;display:flex;align-items:center;gap:8px;">
          <i class="fas fa-user"></i> Customer Info
        </div>
        <div style="font-weight:800;color:var(--text-primary);font-size:1.05rem;"><?= htmlspecialchars($repair['customer_name'], ENT_QUOTES) ?></div>
        <div style="color:var(--text-secondary);font-size:0.875rem;margin-top:6px;display:flex;align-items:center;gap:6px;">
          <i class="fas fa-phone" style="color:var(--primary-color);width:14px;"></i>
          <a href="tel:<?= htmlspecialchars($repair['customer_phone'], ENT_QUOTES) ?>" style="color:var(--primary-color);font-weight:600;"><?= htmlspecialchars($repair['customer_phone'], ENT_QUOTES) ?></a>
        </div>
        <?php if ($repair['customer_email']): ?>
        <div style="color:var(--text-muted);font-size:0.85rem;margin-top:4px;display:flex;align-items:center;gap:6px;">
          <i class="fas fa-envelope" style="width:14px;"></i> <?= htmlspecialchars($repair['customer_email'], ENT_QUOTES) ?>
        </div>
        <?php endif; ?>
        <?php if (!empty($repair['customer_city'])): ?>
        <div style="color:var(--text-muted);font-size:0.85rem;margin-top:4px;display:flex;align-items:center;gap:6px;">
          <i class="fas fa-map-marker-alt" style="width:14px;"></i> <?= htmlspecialchars($repair['customer_city'], ENT_QUOTES) ?>
        </div>
        <?php endif; ?>
      </div>

      <div class="form-card">
        <div style="font-size:0.75rem;font-weight:800;text-transform:uppercase;letter-spacing:.8px;color:var(--primary-color);margin-bottom:14px;display:flex;align-items:center;gap:8px;">
          <i class="fas fa-laptop"></i> Device Details
        </div>
        <div style="font-weight:800;color:var(--text-primary);font-size:1.05rem;">
          <?= htmlspecialchars($repair['device_brand'] . ' ' . ($repair['device_model'] ?? ''), ENT_QUOTES) ?>
        </div>
        <?php if (!empty($repair['service_name'])): ?>
        <div style="color:var(--primary-color);font-size:0.875rem;margin-top:6px;font-weight:700;">
          <i class="fas fa-wrench" style="margin-right:4px;"></i> <?= htmlspecialchars($repair['service_name'], ENT_QUOTES) ?>
        </div>
        <?php endif; ?>
        <?php if (!empty($repair['accessories_included'])): ?>
        <div style="color:var(--text-muted);font-size:0.85rem;margin-top:4px;">
          <strong>Accessories:</strong> <?= htmlspecialchars($repair['accessories_included'], ENT_QUOTES) ?>
        </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Customer Complaint -->
    <div class="form-card">
      <div style="font-size:0.75rem;font-weight:800;text-transform:uppercase;letter-spacing:.8px;color:var(--text-muted);margin-bottom:8px;">Customer Reported Problem</div>
      <p style="color:var(--text-primary);font-size:0.95rem;line-height:1.6;font-style:italic;">
        "<?= htmlspecialchars($repair['problem_description'], ENT_QUOTES) ?>"
      </p>
    </div>

    <!-- Update Diagnostics & Assignment Form -->
    <div class="form-card">
      <div style="font-size:0.75rem;font-weight:800;text-transform:uppercase;letter-spacing:.8px;color:var(--primary-color);margin-bottom:18px;display:flex;align-items:center;gap:8px;">
        <i class="fas fa-clipboard-check"></i> Workshop Diagnostics &amp; Pricing
      </div>
      <form method="POST" action="/admin/repairs/<?= $repair['id'] ?>/update">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES) ?>" />
        <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));gap:16px;margin-bottom:16px;">
          <div class="form-field">
            <label>Assigned Engineer</label>
            <select name="technician_id" class="form-control">
              <option value="">— Unassigned —</option>
              <?php foreach ($technicians as $t): ?>
              <option value="<?= $t['id'] ?>" <?= $repair['assigned_technician_id'] == $t['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($t['name'], ENT_QUOTES) ?>
              </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-field">
            <label>Priority</label>
            <select name="priority" class="form-control">
              <?php foreach (['low'=>'Low','normal'=>'Normal','high'=>'High','urgent'=>'Urgent'] as $k => $v): ?>
              <option value="<?= $k ?>" <?= ($repair['priority'] ?? 'normal') === $k ? 'selected' : '' ?>><?= $v ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-field">
            <label>Estimated Amount (₹)</label>
            <input type="number" name="estimated_amount" value="<?= htmlspecialchars($repair['estimated_amount'] ?? '', ENT_QUOTES) ?>" step="0.01" min="0" class="form-control" placeholder="0.00" />
          </div>
          <div class="form-field">
            <label>Final Bill Amount (₹)</label>
            <input type="number" name="final_amount" value="<?= htmlspecialchars($repair['final_amount'] ?? '', ENT_QUOTES) ?>" step="0.01" min="0" class="form-control" placeholder="0.00" />
          </div>
        </div>

        <div class="form-field" style="margin-bottom:16px;">
          <label>Engineer Diagnostic Findings &amp; Technical Notes</label>
          <textarea name="diagnosis" rows="3" class="form-control" placeholder="Enter findings after hardware inspection (e.g. 19V rail shorted, replaced capacitor C402, screen tested ok)..."><?= htmlspecialchars($repair['diagnosis'] ?? '', ENT_QUOTES) ?></textarea>
        </div>

        <button type="submit" class="btn-primary">
          <i class="fas fa-save"></i> Save Technical Updates
        </button>
      </form>
    </div>

    <!-- Repair Photos Upload -->
    <div class="form-card">
      <div style="font-size:0.75rem;font-weight:800;text-transform:uppercase;letter-spacing:.8px;color:var(--primary-color);margin-bottom:16px;display:flex;align-items:center;gap:8px;">
        <i class="fas fa-camera"></i> Hardware Photos &amp; Proof
      </div>

      <?php if (!empty($images)): ?>
      <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(120px, 1fr));gap:12px;margin-bottom:18px;">
        <?php foreach ($images as $img): 
          $filename = basename($img['file_path']);
          $imgUrl   = '/uploads/repair-images/' . urlencode($filename);
        ?>
        <div style="border-radius:var(--radius-sm);overflow:hidden;border:1px solid var(--border-color);position:relative;background:#000;">
          <a href="<?= $imgUrl ?>" target="_blank" rel="noopener" style="display:block;">
            <img src="<?= $imgUrl ?>" alt="Repair photo" style="width:100%;aspect-ratio:1;object-fit:cover;" />
          </a>
          <div style="position:absolute;bottom:0;left:0;right:0;background:rgba(0,0,0,0.75);font-size:0.7rem;font-weight:700;text-align:center;padding:4px;color:#fff;text-transform:uppercase;">
            <?= htmlspecialchars($img['type'], ENT_QUOTES) ?>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <form method="POST" action="/admin/repairs/<?= $repair['id'] ?>/images" enctype="multipart/form-data" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES) ?>" />
        <div class="form-field" style="width:160px;">
          <label>Photo Category</label>
          <select name="image_type" class="form-control">
            <option value="RECEIVED">Intake Condition</option>
            <option value="DAMAGE">Damage Point</option>
            <option value="DIAGNOSIS">Diagnostic Scan</option>
            <option value="REPAIR">During Repair</option>
            <option value="COMPLETED">Completed Result</option>
          </select>
        </div>
        <div class="form-field" style="flex:1;min-width:180px;">
          <label>Choose Image</label>
          <input type="file" name="image" accept="image/jpeg,image/png,image/webp" required class="form-control" style="padding:6px;" />
        </div>
        <button type="submit" class="btn-secondary">
          <i class="fas fa-upload"></i> Upload
        </button>
      </form>
    </div>

  </div><!-- /left -->

  <!-- Right Column -->
  <div style="display:flex;flex-direction:column;gap:20px;">

    <!-- Payment & Invoicing Card -->
    <div class="form-card">
      <div style="font-size:0.75rem;font-weight:800;text-transform:uppercase;letter-spacing:.8px;color:var(--primary-color);margin-bottom:14px;display:flex;align-items:center;gap:8px;">
        <i class="fas fa-receipt"></i> Billing &amp; Payment
      </div>
      <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:18px;">
        <div style="display:flex;justify-content:space-between;font-size:0.875rem;">
          <span style="color:var(--text-muted);">Estimated:</span>
          <span style="font-weight:700;color:var(--text-primary);">₹<?= $repair['estimated_amount'] ? number_format((float)$repair['estimated_amount'], 2) : '—' ?></span>
        </div>
        <div style="display:flex;justify-content:space-between;font-size:0.875rem;">
          <span style="color:var(--text-muted);">Final Bill:</span>
          <span style="font-weight:800;color:var(--text-primary);">₹<?= $repair['final_amount'] ? number_format((float)$repair['final_amount'], 2) : '0.00' ?></span>
        </div>
        <div style="height:1px;background:var(--border-color);margin:2px 0;"></div>
        <div style="display:flex;justify-content:space-between;font-size:0.875rem;">
          <span style="color:var(--text-muted);">Total Paid:</span>
          <span style="font-weight:800;color:#10B981;">₹<?= number_format($paid, 2) ?></span>
        </div>
        <?php if ($balance > 0): ?>
        <div style="display:flex;justify-content:space-between;font-size:0.95rem;background:#FEF2F2;padding:6px 10px;border-radius:6px;border:1px solid #FECACA;">
          <span style="font-weight:700;color:#991B1B;">Balance Due:</span>
          <span style="font-weight:900;color:#DC2626;">₹<?= number_format($balance, 2) ?></span>
        </div>
        <?php endif; ?>
      </div>

      <form method="POST" action="/admin/repairs/<?= $repair['id'] ?>/payment" style="display:flex;flex-direction:column;gap:10px;">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES) ?>" />
        <div class="form-field">
          <input type="number" name="amount" placeholder="Amount Received (₹)" step="0.01" min="1" required class="form-control" />
        </div>
        <div class="form-field">
          <select name="payment_method" class="form-control">
            <option value="cash">Cash Payment</option>
            <option value="upi">UPI (GPay/PhonePe/Paytm)</option>
            <option value="card">Debit/Credit Card</option>
            <option value="bank_transfer">Direct Bank Transfer</option>
          </select>
        </div>
        <div class="form-field">
          <input type="text" name="transaction_id" placeholder="Transaction Ref ID (optional)" class="form-control" />
        </div>
        <button type="submit" class="btn-primary" style="justify-content:center;background:linear-gradient(135deg,#10B981,#059669);">
          <i class="fas fa-check-circle"></i> Record Payment
        </button>
      </form>
    </div>

    <!-- Repair Timeline Progress -->
    <div class="form-card">
      <div style="font-size:0.75rem;font-weight:800;text-transform:uppercase;letter-spacing:.8px;color:var(--primary-color);margin-bottom:16px;display:flex;align-items:center;gap:8px;">
        <i class="fas fa-history"></i> Status Audit Log
      </div>
      <div style="display:flex;flex-direction:column;gap:0;">
        <?php foreach ($timeline as $i => $entry): ?>
        <div style="display:flex;gap:12px;<?= $i < count($timeline) - 1 ? 'padding-bottom:18px;border-left:2px solid var(--border-color);margin-left:8px;padding-left:18px;position:relative;' : 'padding-left:18px;margin-left:8px;position:relative;' ?>">
          <div style="position:absolute;left:-9px;top:0;width:16px;height:16px;border-radius:50%;background:var(--primary-color);border:2px solid var(--white);display:flex;align-items:center;justify-content:center;">
            <i class="fas fa-check" style="font-size:7px;color:#FFFFFF;"></i>
          </div>
          <div>
            <div style="font-size:0.85rem;font-weight:800;color:var(--text-primary);"><?= htmlspecialchars(\App\Models\RepairJob::statusLabel($entry['status']), ENT_QUOTES) ?></div>
            <?php if ($entry['note']): ?>
            <div style="font-size:0.8rem;color:var(--text-secondary);margin-top:2px;line-height:1.4;"><?= htmlspecialchars($entry['note'], ENT_QUOTES) ?></div>
            <?php endif; ?>
            <div style="font-size:0.75rem;color:var(--text-muted);margin-top:4px;">
              <?= date('d M Y, h:i A', strtotime($entry['created_at'])) ?>
              <?php if ($entry['changed_by_name']): ?> · <?= htmlspecialchars($entry['changed_by_name'], ENT_QUOTES) ?><?php endif; ?>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

  </div><!-- /right -->

</div>
