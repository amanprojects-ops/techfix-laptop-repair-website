<?php
$old = $flash_input ?? [];
$fn  = fn($k) => htmlspecialchars($old[$k] ?? '', ENT_QUOTES);
?>

<header class="header">
  <div class="header-left">
    <button id="sidebar-toggle" class="sidebar-toggle" aria-label="Toggle Sidebar"><i class="fas fa-bars"></i></button>
    <div class="header-title-wrap">
      <h2>New Device Intake / Job Card</h2>
      <span class="header-subtitle">Register a new customer laptop ticket in workshop queue</span>
    </div>
  </div>
  <div class="header-right">
    <a href="/admin/repairs" class="btn-secondary"><i class="fas fa-arrow-left"></i> Back to Queue</a>
  </div>
</header>

<div style="padding:24px;max-width:980px;margin-inline:auto;">

  <!-- Intake Header Notice -->
  <div style="background:linear-gradient(135deg, rgba(37,99,235,0.08) 0%, rgba(56,189,248,0.06) 100%);border:1px solid rgba(37,99,235,0.2);border-radius:var(--radius-md);padding:16px 20px;margin-bottom:24px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
    <div style="display:flex;align-items:center;gap:12px;">
      <div style="width:40px;height:40px;background:var(--primary-color);color:#FFFFFF;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;box-shadow:0 4px 12px rgba(37,99,235,0.3);">
        <i class="fas fa-laptop-medical"></i>
      </div>
      <div>
        <strong style="font-size:0.95rem;color:var(--text-primary);display:block;">Workshop Intake Form</strong>
        <span style="font-size:0.8rem;color:var(--text-muted);">Unique Tracking ID (e.g. AMN-LR-260002) will be generated automatically.</span>
      </div>
    </div>
    <span style="font-size:0.75rem;font-weight:800;background:var(--white);color:var(--primary-color);padding:6px 14px;border-radius:var(--radius-full);border:1px solid var(--border-color);box-shadow:var(--shadow-xs);">
      <i class="fas fa-barcode" style="margin-right:4px;"></i> AUTO GENERATED ID
    </span>
  </div>

  <?php if (!empty($flash_errors)): ?>
  <div style="background:#FEF2F2;color:#991B1B;padding:16px 20px;border-radius:var(--radius-md);font-size:0.875rem;margin-bottom:24px;font-weight:600;border:1px solid #FECACA;box-shadow:var(--shadow-xs);">
    <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">
      <i class="fas fa-exclamation-circle" style="color:#EF4444;font-size:1.1rem;"></i>
      <strong style="font-size:0.95rem;">Please review the following intake errors:</strong>
    </div>
    <ul style="padding-left:24px;list-style:disc;line-height:1.6;">
      <?php foreach ($flash_errors as $err): ?>
      <li><?= htmlspecialchars($err, ENT_QUOTES) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
  <?php endif; ?>

  <form method="POST" action="/admin/repairs" id="intakeForm" style="display:flex;flex-direction:column;gap:24px;">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES) ?>" />

    <!-- ──────────────── 1. Customer Information ──────────────── -->
    <div class="form-card">
      <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px;padding-bottom:12px;border-bottom:1px solid var(--border-color);">
        <div style="width:34px;height:34px;background:rgba(37,99,235,0.1);color:var(--primary-color);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:1rem;">
          <i class="fas fa-user-circle"></i>
        </div>
        <div>
          <h3 style="font-size:1.05rem;font-weight:800;color:var(--text-primary);line-height:1.2;">1. Customer Information</h3>
          <span style="font-size:0.8rem;color:var(--text-muted);">Primary contact for repair status SMS/WhatsApp updates</span>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));gap:16px;">
        <div class="form-field">
          <label>Customer Full Name <span style="color:#EF4444;">*</span></label>
          <div style="position:relative;">
            <input type="text" name="customer_name" class="form-control" value="<?= $fn('customer_name') ?>" required placeholder="e.g. Ramesh Sharma" style="padding-left:36px;" autofocus />
            <i class="fas fa-user" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:0.85rem;"></i>
          </div>
        </div>

        <div class="form-field">
          <label>Mobile Number (10 Digits) <span style="color:#EF4444;">*</span></label>
          <div style="position:relative;">
            <input type="tel" name="customer_phone" class="form-control" value="<?= $fn('customer_phone') ?>" required placeholder="9876543210" pattern="[0-9]{10}" maxlength="10" style="padding-left:36px;font-weight:700;letter-spacing:0.5px;" />
            <i class="fas fa-phone" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:0.85rem;"></i>
          </div>
        </div>

        <div class="form-field">
          <label>Email Address</label>
          <div style="position:relative;">
            <input type="email" name="customer_email" class="form-control" value="<?= $fn('customer_email') ?>" placeholder="customer@example.com (Optional)" style="padding-left:36px;" />
            <i class="fas fa-envelope" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:0.85rem;"></i>
          </div>
        </div>

        <div class="form-field">
          <label>City / Location</label>
          <div style="position:relative;">
            <input type="text" name="customer_city" class="form-control" value="<?= $fn('customer_city') ?>" placeholder="e.g. Saharsa, Supaul" style="padding-left:36px;" />
            <i class="fas fa-map-marker-alt" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:0.85rem;"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- ──────────────── 2. Device Hardware & Condition ──────────────── -->
    <div class="form-card">
      <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px;padding-bottom:12px;border-bottom:1px solid var(--border-color);">
        <div style="width:34px;height:34px;background:rgba(37,99,235,0.1);color:var(--primary-color);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:1rem;">
          <i class="fas fa-laptop"></i>
        </div>
        <div>
          <h3 style="font-size:1.05rem;font-weight:800;color:var(--text-primary);line-height:1.2;">2. Laptop Hardware &amp; Condition</h3>
          <span style="font-size:0.8rem;color:var(--text-muted);">Brand, model details, accessories and physical check at intake</span>
        </div>
      </div>

      <!-- Quick Brand Chips -->
      <div style="margin-bottom:14px;">
        <label style="font-size:0.78rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.5px;display:block;margin-bottom:8px;">Quick Brand Select:</label>
        <div style="display:flex;flex-wrap:wrap;gap:8px;">
          <?php foreach (['Dell', 'HP', 'Lenovo', 'Apple', 'ASUS', 'Acer', 'MSI', 'Samsung', 'Toshiba', 'Other'] as $b): ?>
          <button type="button" class="brand-chip" onclick="selectBrand('<?= $b ?>')" style="padding:5px 12px;border-radius:var(--radius-full);border:1px solid var(--border-color);background:var(--bg-light);font-size:0.8rem;font-weight:700;color:var(--text-secondary);cursor:pointer;transition:all 0.15s;">
            <?= $b ?>
          </button>
          <?php endforeach; ?>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));gap:16px;">
        <div class="form-field">
          <label>Laptop Brand <span style="color:#EF4444;">*</span></label>
          <input type="text" name="device_brand" id="device_brand" class="form-control" value="<?= $fn('device_brand') ?>" required placeholder="e.g. Dell, HP, Lenovo" />
        </div>

        <div class="form-field">
          <label>Model Name / Number</label>
          <input type="text" name="device_model" class="form-control" value="<?= $fn('device_model') ?>" placeholder="e.g. Inspiron 15 3520, Pavilion 14" />
        </div>

        <div class="form-field">
          <label>Serial Number (S/N or Service Tag)</label>
          <input type="text" name="device_serial" class="form-control" value="<?= $fn('device_serial') ?>" placeholder="e.g. CN-0J6H1X-..." />
        </div>

        <div class="form-field">
          <label>Device Color</label>
          <input type="text" name="device_color" class="form-control" value="<?= $fn('device_color') ?>" placeholder="e.g. Platinum Silver, Matte Black" />
        </div>
      </div>

      <!-- Quick Accessories Chips -->
      <div style="margin-top:18px;">
        <label style="font-size:0.78rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.5px;display:block;margin-bottom:8px;">Quick Add Accessories:</label>
        <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:10px;">
          <?php foreach (['Original Charger', 'Laptop Bag', 'Wireless Mouse', 'Power Cable', 'USB Dongle', 'RAM / HDD Attached'] as $acc): ?>
          <button type="button" class="acc-chip" onclick="toggleAccessory('<?= $acc ?>')" style="padding:5px 12px;border-radius:var(--radius-full);border:1px solid var(--border-color);background:var(--bg-light);font-size:0.8rem;font-weight:600;color:var(--text-secondary);cursor:pointer;transition:all 0.15s;">
            + <?= $acc ?>
          </button>
          <?php endforeach; ?>
        </div>
        <div class="form-field">
          <label>Accessories Received with Laptop</label>
          <input type="text" name="accessories_included" id="accessories_included" class="form-control" value="<?= $fn('accessories_included') ?>" placeholder="e.g. Original 65W Dell Charger, Laptop Sleeve" />
        </div>
      </div>

      <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(280px, 1fr));gap:16px;margin-top:16px;">
        <div class="form-field">
          <label>BIOS / Windows Password (if needed to test OS)</label>
          <input type="text" name="lock_pattern" class="form-control" value="<?= $fn('lock_pattern') ?>" placeholder="User PIN or Password (or 'None')" />
        </div>
        <div class="form-field">
          <label>Physical Condition / Intake Inspection Notes</label>
          <input type="text" name="physical_condition" class="form-control" value="<?= $fn('physical_condition') ?>" placeholder="e.g. Minor scratches on top cover, hinge ok, rubber feet present" />
        </div>
      </div>
    </div>

    <!-- ──────────────── 3. Problem Reported & Assignment ──────────────── -->
    <div class="form-card">
      <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px;padding-bottom:12px;border-bottom:1px solid var(--border-color);">
        <div style="width:34px;height:34px;background:rgba(37,99,235,0.1);color:var(--primary-color);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:1rem;">
          <i class="fas fa-tools"></i>
        </div>
        <div>
          <h3 style="font-size:1.05rem;font-weight:800;color:var(--text-primary);line-height:1.2;">3. Problem Reported &amp; Assignment</h3>
          <span style="font-size:0.8rem;color:var(--text-muted);">Customer complaint, fault details, service catalog and engineer assignment</span>
        </div>
      </div>

      <div class="form-field" style="margin-bottom:18px;">
        <label>Customer Reported Issue / Fault Description <span style="color:#EF4444;">*</span></label>
        <textarea name="problem_description" class="form-control" rows="3" required placeholder="Describe the exact problem reported (e.g. Laptop not turning on after power cut, power light blinks 3 times amber, fan spins then stops)..."><?= $fn('problem_description') ?></textarea>
      </div>

      <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));gap:16px;">
        <div class="form-field">
          <label>Service Category</label>
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
          <label>Assign to Workshop Engineer</label>
          <select name="technician_id" class="form-control">
            <option value="">— Select Technician —</option>
            <?php foreach (($technicians ?? []) as $t): ?>
            <option value="<?= $t['id'] ?>" <?= ($old['technician_id'] ?? '') == $t['id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($t['name'], ENT_QUOTES) ?> (<?= htmlspecialchars($t['specialization'] ?? 'Hardware', ENT_QUOTES) ?>)
            </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-field">
          <label>Repair Priority</label>
          <select name="priority" class="form-control">
            <option value="normal" <?= ($old['priority'] ?? 'normal') === 'normal' ? 'selected' : '' ?>>Normal Priority</option>
            <option value="high" <?= ($old['priority'] ?? '') === 'high' ? 'selected' : '' ?>>High Priority ⚡</option>
            <option value="urgent" <?= ($old['priority'] ?? '') === 'urgent' ? 'selected' : '' ?>>Urgent / Same Day 🔥</option>
            <option value="low" <?= ($old['priority'] ?? '') === 'low' ? 'selected' : '' ?>>Low Priority</option>
          </select>
        </div>
      </div>
    </div>

    <!-- ──────────────── 4. Estimation & Advance Payment ──────────────── -->
    <div class="form-card">
      <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px;padding-bottom:12px;border-bottom:1px solid var(--border-color);">
        <div style="width:34px;height:34px;background:rgba(16,185,129,0.1);color:#10B981;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:1rem;">
          <i class="fas fa-rupee-sign"></i>
        </div>
        <div>
          <h3 style="font-size:1.05rem;font-weight:800;color:var(--text-primary);line-height:1.2;">4. Cost Estimation &amp; Advance Payment</h3>
          <span style="font-size:0.8rem;color:var(--text-muted);">Initial quote given to customer and advance deposit recorded</span>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));gap:16px;">
        <div class="form-field">
          <label>Estimated Cost (₹)</label>
          <div style="position:relative;">
            <input type="number" name="estimated_cost" id="estimated_cost" class="form-control" value="<?= $fn('estimated_cost') ?>" placeholder="0.00" min="0" step="1" oninput="calculateBalance()" style="padding-left:36px;font-weight:800;" />
            <span style="position:absolute;left:14px;top:50%;transform:translateY(-50%);font-weight:800;color:var(--text-muted);">₹</span>
          </div>
        </div>

        <div class="form-field">
          <label>Advance Payment Collected (₹)</label>
          <div style="position:relative;">
            <input type="number" name="advance_amount" id="advance_amount" class="form-control" value="<?= $fn('advance_amount') ?>" placeholder="0.00" min="0" step="1" oninput="calculateBalance()" style="padding-left:36px;font-weight:800;color:#10B981;" />
            <span style="position:absolute;left:14px;top:50%;transform:translateY(-50%);font-weight:800;color:#10B981;">₹</span>
          </div>
        </div>

        <div class="form-field">
          <label>Advance Payment Mode</label>
          <select name="advance_payment_method" class="form-control">
            <option value="cash">Cash Payment</option>
            <option value="upi">UPI (GPay / PhonePe / Paytm)</option>
            <option value="card">Debit / Credit Card</option>
            <option value="bank_transfer">Bank Transfer</option>
          </select>
        </div>

        <div class="form-field">
          <label>UPI / Transaction Ref ID</label>
          <input type="text" name="advance_transaction_id" class="form-control" value="<?= $fn('advance_transaction_id') ?>" placeholder="Optional ref ID" />
        </div>
      </div>

      <!-- Live Calculation Box -->
      <div id="balanceSummaryBox" style="margin-top:16px;padding:12px 16px;background:var(--bg-light);border:1px solid var(--border-color);border-radius:var(--radius-sm);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
        <span style="font-size:0.85rem;color:var(--text-muted);">Estimated Balance on Delivery:</span>
        <strong id="remainingBalanceText" style="font-size:1.05rem;color:var(--primary-color);">₹0.00</strong>
      </div>
    </div>

    <!-- ──────────────── Form Actions ──────────────── -->
    <div style="display:flex;gap:14px;justify-content:flex-end;align-items:center;padding:12px 0 32px;">
      <a href="/admin/repairs" class="btn-secondary" style="padding:11px 22px;">Cancel</a>
      <button type="submit" class="btn-primary" style="padding:12px 28px;font-size:0.95rem;box-shadow:0 6px 20px rgba(37,99,235,0.35);">
        <i class="fas fa-save"></i> Generate Job Card &amp; Intake Device
      </button>
    </div>

  </form>
</div>

<script>
function selectBrand(brand) {
  const input = document.getElementById('device_brand');
  if (input) {
    input.value = brand;
    input.focus();
  }
}

function toggleAccessory(acc) {
  const input = document.getElementById('accessories_included');
  if (!input) return;
  let val = input.value.trim();
  if (!val) {
    input.value = acc;
  } else if (!val.includes(acc)) {
    input.value = val + ', ' + acc;
  }
  input.focus();
}

function calculateBalance() {
  const est = parseFloat(document.getElementById('estimated_cost')?.value) || 0;
  const adv = parseFloat(document.getElementById('advance_amount')?.value) || 0;
  const rem = Math.max(0, est - adv);
  const textEl = document.getElementById('remainingBalanceText');
  if (textEl) {
    textEl.textContent = '₹' + rem.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  }
}

document.addEventListener('DOMContentLoaded', calculateBalance);
</script>
