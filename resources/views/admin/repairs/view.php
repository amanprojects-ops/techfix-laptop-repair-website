<?php
$statusColors = [
    'RECEIVED'=>'#3b82f6','DIAGNOSIS'=>'#f59e0b','WAITING_APPROVAL'=>'#f97316',
    'APPROVED'=>'#8b5cf6','IN_REPAIR'=>'#06b6d4','QUALITY_CHECK'=>'#a855f7',
    'READY_FOR_PICKUP'=>'#10b981','DELIVERED'=>'#22c55e','CANCELLED'=>'#ef4444',
    'ON_HOLD'=>'#64748b','PARTS_PENDING'=>'#f59e0b','UNREPAIRABLE'=>'#dc2626',
];
$currentStatus = $repair['current_status'];
$statusColor   = $statusColors[$currentStatus] ?? '#64748b';
$paid   = (float)($totalPaid ?? 0);
$final  = (float)($repair['final_amount'] ?? 0);
$balance = max(0, $final - $paid);
?>

<header class="header">
  <div class="header-left">
    <button id="sidebar-toggle" class="sidebar-toggle"><i class="fas fa-bars"></i></button>
    <div class="header-title-wrap">
      <h2><?= htmlspecialchars($repair['tracking_id'], ENT_QUOTES) ?></h2>
      <span class="header-subtitle">Repair Job Detail</span>
    </div>
  </div>
  <div class="header-right">
    <a href="/admin/repairs" class="btn-secondary"><i class="fas fa-arrow-left"></i> Back to Repairs</a>
  </div>
</header>

<?php if ($flash_success): ?>
<div style="background:#d1fae5;color:#065f46;padding:12px 24px;font-size:0.875rem;font-weight:600;border-bottom:1px solid #a7f3d0;">✓ <?= htmlspecialchars($flash_success, ENT_QUOTES) ?></div>
<?php endif; ?>
<?php if ($flash_error): ?>
<div style="background:#fee2e2;color:#991b1b;padding:12px 24px;font-size:0.875rem;font-weight:600;border-bottom:1px solid #fca5a5;">⚠ <?= htmlspecialchars($flash_error, ENT_QUOTES) ?></div>
<?php endif; ?>

<div style="padding:1.5rem;display:grid;grid-template-columns:1fr 340px;gap:1.5rem;">

  <!-- Left Column -->
  <div style="display:flex;flex-direction:column;gap:1.25rem;">

    <!-- Status Bar -->
    <div style="background:var(--card-bg);border:1px solid var(--border);border-radius:12px;padding:1.25rem;display:flex;align-items:center;gap:16px;">
      <div style="width:12px;height:12px;border-radius:50%;background:<?= $statusColor ?>;box-shadow:0 0 8px <?= $statusColor ?>;flex-shrink:0;"></div>
      <div>
        <div style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--text-muted);">Current Status</div>
        <div style="font-size:1.1rem;font-weight:800;color:<?= $statusColor ?>;"><?= htmlspecialchars(\App\Models\RepairJob::statusLabel($currentStatus), ENT_QUOTES) ?></div>
      </div>
      <div style="margin-left:auto;display:flex;gap:8px;flex-wrap:wrap;">
        <!-- Quick status update -->
        <?php if (!empty($transitions)): ?>
        <form method="POST" action="/admin/repairs/<?= $repair['id'] ?>/status" style="display:flex;gap:8px;align-items:center;">
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES) ?>" />
          <select name="status" style="font-size:0.8rem;padding:6px 10px;border:1px solid var(--border);border-radius:6px;background:var(--card-bg);color:var(--text);">
            <?php foreach ($transitions as $s): ?>
            <option value="<?= $s ?>"><?= htmlspecialchars(\App\Models\RepairJob::statusLabel($s), ENT_QUOTES) ?></option>
            <?php endforeach; ?>
          </select>
          <input type="text" name="note" placeholder="Optional note..." style="font-size:0.8rem;padding:6px 10px;border:1px solid var(--border);border-radius:6px;background:var(--card-bg);color:var(--text);min-width:160px;" />
          <button type="submit" style="font-size:0.8rem;font-weight:700;padding:6px 14px;border-radius:6px;background:var(--accent);color:#fff;border:none;cursor:pointer;">Update Status</button>
        </form>
        <?php endif; ?>
      </div>
    </div>

    <!-- Customer & Device -->
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;">
      <div style="background:var(--card-bg);border:1px solid var(--border);border-radius:12px;padding:1.25rem;">
        <div style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--text-muted);margin-bottom:12px;"><i class="fas fa-user"></i> Customer</div>
        <div style="font-weight:700;color:var(--text);font-size:1rem;"><?= htmlspecialchars($repair['customer_name'], ENT_QUOTES) ?></div>
        <div style="color:var(--text-muted);font-size:0.875rem;margin-top:4px;"><i class="fas fa-phone" style="width:16px;"></i> <?= htmlspecialchars($repair['customer_phone'], ENT_QUOTES) ?></div>
        <?php if ($repair['customer_email']): ?>
        <div style="color:var(--text-muted);font-size:0.875rem;margin-top:4px;"><i class="fas fa-envelope" style="width:16px;"></i> <?= htmlspecialchars($repair['customer_email'], ENT_QUOTES) ?></div>
        <?php endif; ?>
      </div>
      <div style="background:var(--card-bg);border:1px solid var(--border);border-radius:12px;padding:1.25rem;">
        <div style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--text-muted);margin-bottom:12px;"><i class="fas fa-laptop"></i> Device</div>
        <div style="font-weight:700;color:var(--text);font-size:1rem;"><?= htmlspecialchars($repair['device_brand'] . ' ' . ($repair['device_model'] ?? ''), ENT_QUOTES) ?></div>
        <div style="color:var(--text-muted);font-size:0.875rem;margin-top:4px;text-transform:capitalize;"><?= htmlspecialchars($repair['device_type'] ?? '', ENT_QUOTES) ?></div>
        <?php if ($repair['service_name']): ?>
        <div style="color:var(--accent);font-size:0.875rem;margin-top:4px;font-weight:600;"><i class="fas fa-tools" style="width:16px;"></i> <?= htmlspecialchars($repair['service_name'], ENT_QUOTES) ?></div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Problem & Diagnosis -->
    <div style="background:var(--card-bg);border:1px solid var(--border);border-radius:12px;padding:1.25rem;">
      <div style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--text-muted);margin-bottom:8px;">Customer Complaint</div>
      <p style="color:var(--text);font-size:0.9rem;line-height:1.7;">"<?= htmlspecialchars($repair['problem_description'], ENT_QUOTES) ?>"</p>
    </div>

    <!-- Update Form -->
    <div style="background:var(--card-bg);border:1px solid var(--border);border-radius:12px;padding:1.25rem;">
      <div style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--text-muted);margin-bottom:16px;"><i class="fas fa-edit"></i> Update Repair Details</div>
      <form method="POST" action="/admin/repairs/<?= $repair['id'] ?>/update">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES) ?>" />
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
          <div>
            <label style="font-size:0.78rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:6px;">Technician</label>
            <select name="technician_id" style="width:100%;padding:8px 12px;border:1px solid var(--border);border-radius:8px;background:var(--card-bg);color:var(--text);font-size:0.875rem;">
              <option value="">— Unassigned —</option>
              <?php foreach ($technicians as $t): ?>
              <option value="<?= $t['id'] ?>" <?= $repair['assigned_technician_id'] == $t['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($t['name'], ENT_QUOTES) ?>
              </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label style="font-size:0.78rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:6px;">Priority</label>
            <select name="priority" style="width:100%;padding:8px 12px;border:1px solid var(--border);border-radius:8px;background:var(--card-bg);color:var(--text);font-size:0.875rem;">
              <?php foreach (['low'=>'Low','normal'=>'Normal','high'=>'High','urgent'=>'Urgent'] as $k => $v): ?>
              <option value="<?= $k ?>" <?= $repair['priority'] === $k ? 'selected' : '' ?>><?= $v ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label style="font-size:0.78rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:6px;">Estimated Amount (₹)</label>
            <input type="number" name="estimated_amount" value="<?= htmlspecialchars($repair['estimated_amount'] ?? '', ENT_QUOTES) ?>" step="0.01" min="0" style="width:100%;padding:8px 12px;border:1px solid var(--border);border-radius:8px;background:var(--card-bg);color:var(--text);font-size:0.875rem;" />
          </div>
          <div>
            <label style="font-size:0.78rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:6px;">Final Amount (₹)</label>
            <input type="number" name="final_amount" value="<?= htmlspecialchars($repair['final_amount'] ?? '', ENT_QUOTES) ?>" step="0.01" min="0" style="width:100%;padding:8px 12px;border:1px solid var(--border);border-radius:8px;background:var(--card-bg);color:var(--text);font-size:0.875rem;" />
          </div>
        </div>
        <div style="margin-bottom:16px;">
          <label style="font-size:0.78rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:6px;">Technician Diagnosis</label>
          <textarea name="diagnosis" rows="3" style="width:100%;padding:8px 12px;border:1px solid var(--border);border-radius:8px;background:var(--card-bg);color:var(--text);font-size:0.875rem;resize:vertical;"><?= htmlspecialchars($repair['diagnosis'] ?? '', ENT_QUOTES) ?></textarea>
        </div>
        <button type="submit" style="font-size:0.875rem;font-weight:700;padding:8px 20px;border-radius:8px;background:var(--accent);color:#fff;border:none;cursor:pointer;"><i class="fas fa-save"></i> Save Changes</button>
      </form>
    </div>

    <!-- Upload Images -->
    <div style="background:var(--card-bg);border:1px solid var(--border);border-radius:12px;padding:1.25rem;">
      <div style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--text-muted);margin-bottom:16px;"><i class="fas fa-images"></i> Repair Photos</div>

      <?php if (!empty($images)): ?>
      <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(110px,1fr));gap:10px;margin-bottom:16px;">
        <?php foreach ($images as $img): ?>
        <div style="border-radius:8px;overflow:hidden;border:1px solid var(--border);position:relative;">
          <img src="/admin/uploads/<?= urlencode(basename($img['file_path'])) ?>" alt="Repair photo" style="width:100%;aspect-ratio:1;object-fit:cover;" />
          <div style="position:absolute;bottom:0;left:0;right:0;background:rgba(0,0,0,.7);font-size:0.65rem;font-weight:700;text-align:center;padding:3px;color:#fff;text-transform:uppercase;"><?= htmlspecialchars($img['type'], ENT_QUOTES) ?></div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <form method="POST" action="/admin/repairs/<?= $repair['id'] ?>/images" enctype="multipart/form-data" style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end;">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES) ?>" />
        <div>
          <label style="font-size:0.78rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:6px;">Photo Type</label>
          <select name="image_type" style="padding:8px 12px;border:1px solid var(--border);border-radius:8px;background:var(--card-bg);color:var(--text);font-size:0.875rem;">
            <option value="RECEIVED">Received Condition</option>
            <option value="DAMAGE">Damage Photo</option>
            <option value="DIAGNOSIS">Diagnosis</option>
            <option value="REPAIR">During Repair</option>
            <option value="COMPLETED">Completed</option>
          </select>
        </div>
        <div>
          <label style="font-size:0.78rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:6px;">Upload Photo</label>
          <input type="file" name="image" accept="image/jpeg,image/png,image/webp" required style="font-size:0.875rem;" />
        </div>
        <button type="submit" style="font-size:0.875rem;font-weight:700;padding:8px 16px;border-radius:8px;background:var(--card-bg);color:var(--text);border:1px solid var(--border);cursor:pointer;"><i class="fas fa-upload"></i> Upload</button>
      </form>
    </div>

  </div><!-- /left -->

  <!-- Right Column -->
  <div style="display:flex;flex-direction:column;gap:1.25rem;">

    <!-- Payment summary -->
    <div style="background:var(--card-bg);border:1px solid var(--border);border-radius:12px;padding:1.25rem;">
      <div style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--text-muted);margin-bottom:12px;"><i class="fas fa-receipt"></i> Payment</div>
      <div style="display:flex;flex-direction:column;gap:8px;margin-bottom:16px;">
        <div style="display:flex;justify-content:space-between;font-size:0.875rem;">
          <span style="color:var(--text-muted);">Estimated</span>
          <span style="font-weight:600;color:var(--text);">₹<?= $repair['estimated_amount'] ? number_format((float)$repair['estimated_amount'], 2) : '—' ?></span>
        </div>
        <div style="display:flex;justify-content:space-between;font-size:0.875rem;">
          <span style="color:var(--text-muted);">Final</span>
          <span style="font-weight:600;color:var(--text);">₹<?= $repair['final_amount'] ? number_format((float)$repair['final_amount'], 2) : '—' ?></span>
        </div>
        <div style="height:1px;background:var(--border);"></div>
        <div style="display:flex;justify-content:space-between;font-size:0.875rem;">
          <span style="color:var(--text-muted);">Paid</span>
          <span style="font-weight:700;color:#10b981;">₹<?= number_format($paid, 2) ?></span>
        </div>
        <?php if ($balance > 0): ?>
        <div style="display:flex;justify-content:space-between;font-size:0.9rem;">
          <span style="font-weight:700;color:var(--text);">Balance Due</span>
          <span style="font-weight:800;color:#ef4444;">₹<?= number_format($balance, 2) ?></span>
        </div>
        <?php endif; ?>
      </div>
      <form method="POST" action="/admin/repairs/<?= $repair['id'] ?>/payment" style="display:flex;flex-direction:column;gap:8px;">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES) ?>" />
        <input type="number" name="amount" placeholder="Amount received (₹)" step="0.01" min="1" required style="padding:8px 10px;border:1px solid var(--border);border-radius:8px;background:var(--card-bg);color:var(--text);font-size:0.875rem;" />
        <select name="payment_method" style="padding:8px 10px;border:1px solid var(--border);border-radius:8px;background:var(--card-bg);color:var(--text);font-size:0.875rem;">
          <option value="cash">Cash</option><option value="upi">UPI</option><option value="card">Card</option><option value="bank_transfer">Bank Transfer</option>
        </select>
        <input type="text" name="transaction_id" placeholder="Transaction ID (optional)" style="padding:8px 10px;border:1px solid var(--border);border-radius:8px;background:var(--card-bg);color:var(--text);font-size:0.875rem;" />
        <button type="submit" style="font-size:0.875rem;font-weight:700;padding:8px;border-radius:8px;background:#10b981;color:#fff;border:none;cursor:pointer;"><i class="fas fa-rupee-sign"></i> Record Payment</button>
      </form>
    </div>

    <!-- Timeline -->
    <div style="background:var(--card-bg);border:1px solid var(--border);border-radius:12px;padding:1.25rem;">
      <div style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--text-muted);margin-bottom:16px;"><i class="fas fa-history"></i> Repair Timeline</div>
      <div style="display:flex;flex-direction:column;gap:0;">
        <?php foreach ($timeline as $i => $entry): ?>
        <div style="display:flex;gap:12px;<?= $i < count($timeline) - 1 ? 'padding-bottom:16px;border-left:2px solid var(--border);margin-left:8px;padding-left:20px;position:relative;' : 'padding-left:20px;margin-left:8px;position:relative;' ?>">
          <div style="position:absolute;left:-10px;top:0;width:18px;height:18px;border-radius:50%;background:var(--accent);border:2px solid var(--bg);display:flex;align-items:center;justify-content:center;">
            <i class="fas fa-check" style="font-size:8px;color:#fff;"></i>
          </div>
          <div>
            <div style="font-size:0.8rem;font-weight:700;color:var(--text);"><?= htmlspecialchars(\App\Models\RepairJob::statusLabel($entry['status']), ENT_QUOTES) ?></div>
            <?php if ($entry['note']): ?>
            <div style="font-size:0.75rem;color:var(--text-muted);margin-top:2px;"><?= htmlspecialchars($entry['note'], ENT_QUOTES) ?></div>
            <?php endif; ?>
            <div style="font-size:0.7rem;color:var(--text-muted);margin-top:3px;">
              <?= date('d M Y, h:i A', strtotime($entry['created_at'])) ?>
              <?php if ($entry['changed_by_name']): ?>· <?= htmlspecialchars($entry['changed_by_name'], ENT_QUOTES) ?><?php endif; ?>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

  </div><!-- /right -->

</div>
