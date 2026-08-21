<?php
$old = $flash_input ?? [];
$fn  = fn($k) => htmlspecialchars($old[$k] ?? '', ENT_QUOTES);
?>
<header class="header">
  <div class="header-left">
    <button id="sidebar-toggle" class="sidebar-toggle" aria-label="Toggle Sidebar"><i class="fas fa-bars"></i></button>
    <div class="header-title-wrap">
      <h2>New Device Intake / Job Card</h2>
      <span class="header-subtitle">Register a new customer laptop ticket in workshop</span>
    </div>
  </div>
  <div class="header-right">
    <a href="/admin/repairs" class="btn-secondary"><i class="fas fa-arrow-left"></i> Back to Queue</a>
  </div>
</header>

<div style="padding:24px;max-width:960px;margin-inline:auto;">

  <?php if (!empty($flash_errors)): ?>
  <div style="background:#FEF2F2;color:#991B1B;padding:14px 18px;border-radius:var(--radius-sm);font-size:0.875rem;margin-bottom:20px;font-weight:600;border:1px solid #FECACA;">
    <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">
      <i class="fas fa-exclamation-circle" style="color:#EF4444;"></i>
      <strong>Please fix the following errors:</strong>
    </div>
    <ul style="padding-left:24px;list-style:disc;">
      <?php foreach ($flash_errors as $err): ?><li><?= htmlspecialchars($err, ENT_QUOTES) ?></li><?php endforeach; ?>
    </ul>
  </div>
  <?php endif; ?>

  <form method="POST" action="/admin/repairs" style="display:flex;flex-direction:column;gap:20px;">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES) ?>" />

    <!-- 1. Customer Information -->
    <div class="form-card">
      <div style="font-size:0.75rem;font-weight:800;text-transform:uppercase;letter-spacing:.8px;color:var(--primary-color);margin-bottom:18px;display:flex;align-items:center;gap:8px;">
        <i class="fas fa-user-circle"></i> 1. Customer Information
      </div>
      <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));gap:16px;">
        <div class="form-field">
          <label>Customer Full Name *</label>
          <input type="text" name="customer_name" class="form-control" value="<?= $fn('customer_name') ?>" required placeholder="e.g. Ramesh Kumar" />
        </div>
        <div class="form-field">
          <label>Mobile Number *</label>
          <input type="tel" name="customer_phone" class="form-control" value="<?= $fn('customer_phone') ?>" required placeholder="10-digit mobile number" pattern="[0-9]{10}" />
        </div>
        <div class="form-field">
          <label>Email Address</label>
          <input type="email" name="customer_email" class="form-control" value="<?= $fn('customer_email') ?>" placeholder="Optional" />
        </div>
        <div class="form-field">
          <label>City / Town</label>
          <input type="text" name="customer_city" class="form-control" value="<?= $fn('customer_city') ?>" placeholder="e.g. Saharsa" />
        </div>
      </div>
    </div>

    <!-- 2. Device Information -->
    <div class="form-card">
      <div style="font-size:0.75rem;font-weight:800;text-transform:uppercase;letter-spacing:.8px;color:var(--primary-color);margin-bottom:18px;display:flex;align-items:center;gap:8px;">
        <i class="fas fa-laptop"></i> 2. Laptop Hardware &amp; Condition
      </div>
      <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));gap:16px;">
        <div class="form-field">
          <label>Laptop Brand *</label>
          <input type="text" name="device_brand" class="form-control" value="<?= $fn('device_brand') ?>" required placeholder="Dell, HP, Lenovo, Apple, ASUS..." />
        </div>
        <div class="form-field">
          <label>Model Name / Number</label>
          <input type="text" name="device_model" class="form-control" value="<?= $fn('device_model') ?>" placeholder="e.g. Inspiron 15 3520" />
        </div>
        <div class="form-field">
          <label>Serial Number (S/N)</label>
          <input type="text" name="device_serial" class="form-control" value="<?= $fn('device_serial') ?>" placeholder="Optional serial / service tag" />
        </div>
        <div class="form-field">
          <label>Device Color</label>
          <input type="text" name="device_color" class="form-control" value="<?= $fn('device_color') ?>" placeholder="e.g. Silver, Black" />
        </div>
      </div>

      <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));gap:16px;margin-top:16px;">
        <div class="form-field">
          <label>Accessories Received</label>
          <input type="text" name="accessories_included" class="form-control" value="<?= $fn('accessories_included') ?>" placeholder="e.g. Charger, Laptop Bag, Wireless Mouse" />
        </div>
        <div class="form-field">
          <label>BIOS / Windows Password (if any)</label>
          <input type="text" name="lock_pattern" class="form-control" value="<?= $fn('lock_pattern') ?>" placeholder="PIN or password to test OS" />
        </div>
      </div>

      <div class="form-field" style="margin-top:16px;">
        <label>Physical Condition / Pre-existing Scratches</label>
        <textarea name="physical_condition" class="form-control" rows="2" placeholder="Note down any hinge cracks, screen scratches, or missing rubber feet at intake..."><?= $fn('physical_condition') ?></textarea>
      </div>
    </div>

    <!-- 3. Fault & Service Assignment -->
    <div class="form-card">
      <div style="font-size:0.75rem;font-weight:800;text-transform:uppercase;letter-spacing:.8px;color:var(--primary-color);margin-bottom:18px;display:flex;align-items:center;gap:8px;">
        <i class="fas fa-tools"></i> 3. Problem Reported &amp; Assignment
      </div>

      <div class="form-field">
        <label>Customer Reported Issue *</label>
        <textarea name="problem_description" class="form-control" rows="3" required placeholder="Detailed issue description (e.g. laptop not turning on after power surge, screen black but fan spinning)..."><?= $fn('problem_description') ?></textarea>
      </div>

      <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));gap:16px;margin-top:16px;">
        <div class="form-field">
          <label>Assigned Service Category</label>
          <select name="service_id" class="form-control">
            <option value="">— Select Service Category —</option>
            <?php foreach (($services ?? []) as $s): ?>
            <option value="<?= $s['id'] ?>" <?= ($old['service_id'] ?? '') == $s['id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($s['name'], ENT_QUOTES) ?> (From ₹<?= number_format((float)$s['starting_price'], 0) ?>)
            </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-field">
          <label>Assign to Technician</label>
          <select name="technician_id" class="form-control">
            <option value="">— Select Technician —</option>
            <?php foreach (($technicians ?? []) as $t): ?>
            <option value="<?= $t['id'] ?>" <?= ($old['technician_id'] ?? '') == $t['id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($t['name'], ENT_QUOTES) ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-field">
          <label>Estimated Cost (₹)</label>
          <input type="number" name="estimated_cost" class="form-control" value="<?= $fn('estimated_cost') ?>" placeholder="0" min="0" />
        </div>
        <div class="form-field">
          <label>Advance Payment (₹)</label>
          <input type="number" name="advance_amount" class="form-control" value="<?= $fn('advance_amount') ?>" placeholder="0" min="0" />
        </div>
      </div>
    </div>

    <div style="display:flex;gap:12px;justify-content:flex-end;">
      <a href="/admin/repairs" class="btn-secondary">Cancel</a>
      <button type="submit" class="btn-primary">
        <i class="fas fa-save"></i> Generate Ticket &amp; Intake Device
      </button>
    </div>
  </form>
</div>
