<?php
$old = $flash_input ?? [];
$fn  = fn($k) => htmlspecialchars($old[$k] ?? '', ENT_QUOTES);
?>
<header class="header">
  <div class="header-left">
    <button id="sidebar-toggle" class="sidebar-toggle"><i class="fas fa-bars"></i></button>
    <div class="header-title-wrap">
      <h2>New Device Intake / Job Card</h2>
      <span class="header-subtitle">Create a new customer repair ticket</span>
    </div>
  </div>
  <div class="header-right">
    <a href="/admin/repairs" class="btn-secondary"><i class="fas fa-arrow-left"></i> Back to Queue</a>
  </div>
</header>

<div style="padding:1.5rem;max-width:900px;">

  <?php if (!empty($flash_errors)): ?>
  <div style="background:#fee2e2;color:#991b1b;padding:12px 16px;border-radius:8px;font-size:0.875rem;margin-bottom:1.25rem;font-weight:600;border:1px solid #fca5a5;">
    <i class="fas fa-exclamation-triangle"></i>
    <?php foreach ($flash_errors as $err): ?><div><?= htmlspecialchars($err, ENT_QUOTES) ?></div><?php endforeach; ?>
  </div>
  <?php endif; ?>

  <form method="POST" action="/admin/repairs" style="display:flex;flex-direction:column;gap:1.25rem;">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES) ?>" />

    <!-- Customer -->
    <div style="background:var(--card-bg);border:1px solid var(--border);border-radius:12px;padding:1.5rem;">
      <div style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--text-muted);margin-bottom:16px;"><i class="fas fa-user"></i> Customer Information</div>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;">
        <div>
          <label style="font-size:0.8rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:6px;">Full Name *</label>
          <input type="text" name="customer_name" value="<?= $fn('customer_name') ?>" required placeholder="Customer name" style="width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:8px;background:var(--card-bg);color:var(--text);font-size:0.875rem;" />
        </div>
        <div>
          <label style="font-size:0.8rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:6px;">Phone Number *</label>
          <input type="tel" name="customer_phone" value="<?= $fn('customer_phone') ?>" required placeholder="10-digit mobile" style="width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:8px;background:var(--card-bg);color:var(--text);font-size:0.875rem;" />
        </div>
        <div>
          <label style="font-size:0.8rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:6px;">Email</label>
          <input type="email" name="customer_email" value="<?= $fn('customer_email') ?>" placeholder="Optional" style="width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:8px;background:var(--card-bg);color:var(--text);font-size:0.875rem;" />
        </div>
        <div>
          <label style="font-size:0.8rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:6px;">City</label>
          <input type="text" name="customer_city" value="<?= $fn('customer_city') ?>" placeholder="City" style="width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:8px;background:var(--card-bg);color:var(--text);font-size:0.875rem;" />
        </div>
      </div>
    </div>

    <!-- Device -->
    <div style="background:var(--card-bg);border:1px solid var(--border);border-radius:12px;padding:1.5rem;">
      <div style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--text-muted);margin-bottom:16px;"><i class="fas fa-laptop"></i> Device Information</div>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:16px;">
        <div>
          <label style="font-size:0.8rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:6px;">Brand *</label>
          <input type="text" name="device_brand" value="<?= $fn('device_brand') ?>" required placeholder="Dell, HP, Lenovo..." style="width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:8px;background:var(--card-bg);color:var(--text);font-size:0.875rem;" />
        </div>
        <div>
          <label style="font-size:0.8rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:6px;">Model</label>
          <input type="text" name="device_model" value="<?= $fn('device_model') ?>" placeholder="Inspiron 15, Pavilion..." style="width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:8px;background:var(--card-bg);color:var(--text);font-size:0.875rem;" />
        </div>
        <div>
          <label style="font-size:0.8rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:6px;">Serial Number</label>
          <input type="text" name="serial_number" value="<?= $fn('serial_number') ?>" placeholder="Optional" style="width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:8px;background:var(--card-bg);color:var(--text);font-size:0.875rem;" />
        </div>
        <div>
          <label style="font-size:0.8rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:6px;">Accessories</label>
          <input type="text" name="accessories" value="<?= $fn('accessories') ?>" placeholder="Charger, bag..." style="width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:8px;background:var(--card-bg);color:var(--text);font-size:0.875rem;" />
        </div>
        <div style="grid-column:1/-1;">
          <label style="font-size:0.8rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:6px;">Physical Condition</label>
          <input type="text" name="physical_condition" value="<?= $fn('physical_condition') ?>" placeholder="e.g. Small scratch on lid, cracked bottom cover" style="width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:8px;background:var(--card-bg);color:var(--text);font-size:0.875rem;" />
        </div>
      </div>
    </div>

    <!-- Repair Details -->
    <div style="background:var(--card-bg);border:1px solid var(--border);border-radius:12px;padding:1.5rem;">
      <div style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--text-muted);margin-bottom:16px;"><i class="fas fa-tools"></i> Repair Details</div>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:16px;">
        <div>
          <label style="font-size:0.8rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:6px;">Service Type</label>
          <select name="service_id" style="width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:8px;background:var(--card-bg);color:var(--text);font-size:0.875rem;">
            <option value="">— Select Service —</option>
            <?php foreach ($services as $svc): ?>
            <option value="<?= $svc['id'] ?>"><?= htmlspecialchars($svc['name'], ENT_QUOTES) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label style="font-size:0.8rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:6px;">Assign Technician</label>
          <select name="technician_id" style="width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:8px;background:var(--card-bg);color:var(--text);font-size:0.875rem;">
            <option value="">— Assign Later —</option>
            <?php foreach ($technicians as $t): ?>
            <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['name'], ENT_QUOTES) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label style="font-size:0.8rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:6px;">Priority</label>
          <select name="priority" style="width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:8px;background:var(--card-bg);color:var(--text);font-size:0.875rem;">
            <option value="low">Low</option>
            <option value="normal" selected>Normal</option>
            <option value="high">High</option>
            <option value="urgent">Urgent</option>
          </select>
        </div>
        <div style="grid-column:1/-1;">
          <label style="font-size:0.8rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:6px;">Problem Description *</label>
          <textarea name="problem_description" rows="3" required placeholder="Describe what the customer reported..." style="width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:8px;background:var(--card-bg);color:var(--text);font-size:0.875rem;resize:vertical;"><?= $fn('problem_description') ?></textarea>
        </div>
      </div>
    </div>

    <div style="display:flex;gap:12px;">
      <button type="submit" style="font-size:0.9rem;font-weight:700;padding:10px 24px;border-radius:8px;background:var(--accent);color:#fff;border:none;cursor:pointer;"><i class="fas fa-laptop-medical"></i> Create Repair Job</button>
      <a href="/admin/repairs" style="font-size:0.9rem;font-weight:600;padding:10px 20px;border-radius:8px;background:var(--card-bg);color:var(--text-muted);border:1px solid var(--border);text-decoration:none;">Cancel</a>
    </div>

  </form>
</div>
