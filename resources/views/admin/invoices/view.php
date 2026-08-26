<?php
/**
 * TechFix View Invoice & Action Hub
 * 
 * @var array  $invoice
 * @var string $renderedHtml
 * @var array  $templates
 * @var string $activeTemplateKey
 * @var string $csrfToken
 * @var array  $user
 * @var string|null $flashSuccess
 * @var string|null $flashError
 */

$balance = (float)$invoice['balance_due'];
$statusColor = \App\Models\Invoice::STATUS_COLORS[$invoice['status']] ?? '#64748B';
?>

<!-- Header Action Toolbar -->
<header class="header" style="margin-bottom: 24px;">
  <div class="header-left">
    <a href="<?= url('/admin/invoices') ?>" class="btn-secondary" style="padding: 8px 12px; margin-right: 12px;" title="Back to All Invoices">
      <i class="fas fa-arrow-left"></i>
    </a>
    <div class="header-title-wrap">
      <h2>Invoice #<?= htmlspecialchars($invoice['invoice_number'], ENT_QUOTES) ?></h2>
      <span class="header-subtitle">
        Customer: <strong><?= htmlspecialchars($invoice['customer_name'], ENT_QUOTES) ?></strong> · <?= date('d M Y', strtotime($invoice['invoice_date'])) ?>
      </span>
    </div>
  </div>

  <div class="header-right" style="display: flex; gap: 10px; flex-wrap: wrap;">
    <!-- Live Template Switcher Dropdown -->
    <div style="display: flex; align-items: center; gap: 6px; background: #ffffff; padding: 4px 10px; border-radius: var(--radius-sm); border: 1px solid var(--border-color);">
      <i class="fas fa-palette" style="color: var(--primary-color);"></i>
      <span style="font-size: 0.8rem; font-weight: 700; color: var(--text-secondary);">Template:</span>
      <select onchange="location.href='<?= url('/admin/invoices/' . $invoice['id'] . '?template=') ?>' + this.value" style="border: none; background: transparent; font-weight: 700; color: var(--text-primary); font-size: 0.85rem; cursor: pointer; outline: none;">
        <?php foreach ($templates as $tpl): ?>
        <option value="<?= htmlspecialchars($tpl['template_key'], ENT_QUOTES) ?>" <?= $activeTemplateKey === $tpl['template_key'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($tpl['name'], ENT_QUOTES) ?>
        </option>
        <?php endforeach; ?>
      </select>
    </div>

    <a href="<?= url('/admin/invoices/' . $invoice['id'] . '/print?template=' . urlencode($activeTemplateKey)) ?>" target="_blank" class="btn btn-primary" style="padding: 9px 16px; font-weight: 700; text-decoration: none;">
      <i class="fas fa-print"></i> Print / PDF
    </a>

    <a href="<?= url('/admin/invoices/' . $invoice['id'] . '/edit') ?>" class="btn btn-secondary" style="padding: 9px 14px; text-decoration: none;">
      <i class="fas fa-edit"></i> Edit
    </a>
  </div>
</header>

<!-- Flash Alerts -->
<?php if ($flashSuccess): ?>
<div style="background: #ecfdf5; border-left: 4px solid #10b981; padding: 14px 18px; border-radius: var(--radius-sm); margin-bottom: 24px; color: #065f46; font-weight: 600; display: flex; align-items: center; justify-content: space-between;">
  <div style="display: flex; align-items: center; gap: 10px;">
    <i class="fas fa-check-circle" style="color: #10b981; font-size: 1.2rem;"></i>
    <span><?= htmlspecialchars($flashSuccess, ENT_QUOTES) ?></span>
  </div>
  <button type="button" onclick="this.parentElement.remove()" style="background:none;border:none;color:#065f46;cursor:pointer;"><i class="fas fa-times"></i></button>
</div>
<?php endif; ?>

<?php if ($flashError): ?>
<div style="background: #fef2f2; border-left: 4px solid #ef4444; padding: 14px 18px; border-radius: var(--radius-sm); margin-bottom: 24px; color: #991b1b; font-weight: 600; display: flex; align-items: center; justify-content: space-between;">
  <div style="display: flex; align-items: center; gap: 10px;">
    <i class="fas fa-exclamation-circle" style="color: #ef4444; font-size: 1.2rem;"></i>
    <span><?= htmlspecialchars($flashError, ENT_QUOTES) ?></span>
  </div>
  <button type="button" onclick="this.parentElement.remove()" style="background:none;border:none;color:#991b1b;cursor:pointer;"><i class="fas fa-times"></i></button>
</div>
<?php endif; ?>

<!-- Main 2-Column Layout -->
<div style="display: grid; grid-template-columns: 1fr 340px; gap: 24px; align-items: start;">
  
  <!-- Left: Live Rendered Invoice Sheet -->
  <div style="background: #e2e8f0; padding: 24px; border-radius: var(--radius-md); box-shadow: inset 0 2px 6px rgba(0,0,0,0.06); overflow-x: auto;">
    <div style="margin: 0 auto;">
      <?= $renderedHtml ?>
    </div>
  </div>

  <!-- Right: Actions & Financial Controls -->
  <div style="display: flex; flex-direction: column; gap: 20px;">

    <!-- Payment & Balance Summary Card -->
    <div class="form-card" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 20px;">
      <div style="font-size: 0.78rem; font-weight: 800; text-transform: uppercase; color: var(--primary-color); letter-spacing: 0.6px; margin-bottom: 14px; display: flex; align-items: center; gap: 6px;">
        <i class="fas fa-coins"></i> Billing Status
      </div>

      <div style="display: flex; flex-direction: column; gap: 8px; margin-bottom: 16px;">
        <div style="display: flex; justify-content: space-between; font-size: 0.88rem; color: var(--text-secondary);">
          <span>Total Invoiced:</span>
          <span style="font-weight: 800; font-family: monospace; color: var(--text-primary); font-size: 0.95rem;">₹<?= number_format((float)$invoice['total_amount'], 2) ?></span>
        </div>
        <div style="display: flex; justify-content: space-between; font-size: 0.88rem; color: #059669;">
          <span>Total Paid:</span>
          <span style="font-weight: 800; font-family: monospace; font-size: 0.95rem;">₹<?= number_format((float)$invoice['paid_amount'], 2) ?></span>
        </div>
        <div style="height: 1px; background: var(--border-color); margin: 4px 0;"></div>
        <div style="display: flex; justify-content: space-between; font-size: 1.05rem; font-weight: 900; background: <?= $balance > 0 ? '#FEF2F2' : '#ECFDF5' ?>; color: <?= $balance > 0 ? '#DC2626' : '#059669' ?>; padding: 8px 12px; border-radius: 6px; border: 1px solid <?= $balance > 0 ? '#FECACA' : '#A7F3D0' ?>;">
          <span>Balance Due:</span>
          <span style="font-family: monospace;">₹<?= number_format($balance, 2) ?></span>
        </div>
      </div>

      <!-- Quick Payment Record Form -->
      <?php if ($balance > 0): ?>
      <form method="POST" action="<?= url('/admin/invoices/' . $invoice['id'] . '/payment') ?>" style="display: flex; flex-direction: column; gap: 10px; border-top: 1px dashed var(--border-color); padding-top: 14px;">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES) ?>" />
        
        <div style="font-size: 0.76rem; font-weight: 800; text-transform: uppercase; color: var(--text-muted);">Record Received Payment:</div>
        
        <input type="number" name="amount" placeholder="Amount Received (₹)" value="<?= $balance ?>" step="0.01" min="1" max="<?= $balance ?>" required class="form-control" style="font-weight: 700; font-family: monospace;" />
        
        <select name="payment_method" class="form-control" style="font-size: 0.85rem;">
          <option value="cash">Cash Received</option>
          <option value="upi" selected>UPI (GPay / PhonePe / Paytm)</option>
          <option value="card">Card Payment</option>
          <option value="bank_transfer">Bank Transfer (NEFT)</option>
        </select>

        <input type="text" name="payment_reference" placeholder="Transaction ref # (optional)" class="form-control" style="font-size: 0.85rem;" />

        <button type="submit" class="btn btn-primary" style="justify-content: center; background: linear-gradient(135deg, #10b981, #059669); border: none; font-weight: 800;">
          <i class="fas fa-check-circle"></i> Collect &amp; Record Payment
        </button>
      </form>
      <?php else: ?>
      <div style="text-align: center; padding: 10px; background: #ecfdf5; border-radius: 6px; color: #059669; font-weight: 700; font-size: 0.88rem;">
        <i class="fas fa-check-double"></i> Fully Paid &amp; Settled
      </div>
      <?php endif; ?>
    </div>

    <!-- Email Invoice to Customer Card -->
    <div class="form-card" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 20px;">
      <div style="font-size: 0.78rem; font-weight: 800; text-transform: uppercase; color: var(--primary-color); letter-spacing: 0.6px; margin-bottom: 12px; display: flex; align-items: center; gap: 6px;">
        <i class="fas fa-envelope"></i> Email Invoice to Client
      </div>

      <form method="POST" action="<?= url('/admin/invoices/' . $invoice['id'] . '/send-email') ?>" style="display: flex; flex-direction: column; gap: 10px;">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES) ?>" />
        
        <input type="email" name="recipient_email" value="<?= htmlspecialchars($invoice['customer_email'] ?? '', ENT_QUOTES) ?>" placeholder="Customer Email Address" required class="form-control" style="font-size: 0.88rem;" />
        
        <button type="submit" class="btn btn-secondary" style="justify-content: center; font-weight: 700; width: 100%;">
          <i class="fas fa-paper-plane"></i> Send Invoice via Email
        </button>
      </form>
    </div>

    <!-- Customer Contact Quick Actions -->
    <div class="form-card" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 20px;">
      <div style="font-size: 0.78rem; font-weight: 800; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.6px; margin-bottom: 10px;">
        Quick Communication
      </div>
      <div style="display: flex; flex-direction: column; gap: 8px;">
        <a href="tel:<?= htmlspecialchars($invoice['customer_phone'], ENT_QUOTES) ?>" class="btn" style="background: #f8fafc; border: 1px solid var(--border-color); color: var(--text-primary); font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 8px; justify-content: center;">
          <i class="fas fa-phone" style="color: var(--primary-color);"></i> Call <?= htmlspecialchars($invoice['customer_phone'], ENT_QUOTES) ?>
        </a>
        <a href="<?= site_whatsapp_link('Hello ' . $invoice['customer_name'] . ', here is your invoice #' . $invoice['invoice_number'] . ' for laptop repair at ' . site_name() . '. Balance: ₹' . number_format($balance, 2)) ?>" target="_blank" class="btn" style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 8px; justify-content: center;">
          <i class="fab fa-whatsapp" style="color: #10b981; font-size: 1.1rem;"></i> Send on WhatsApp
        </a>
      </div>
    </div>

    <!-- Linked Repair Ticket -->
    <?php if (!empty($invoice['repair_tracking_id'])): ?>
    <div class="form-card" style="background: #f8fafc; border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 18px;">
      <div style="font-size: 0.74rem; font-weight: 800; text-transform: uppercase; color: var(--primary-color); letter-spacing: 0.6px; margin-bottom: 6px;">
        Linked Repair Ticket
      </div>
      <div style="font-weight: 800; font-family: monospace; font-size: 1rem; color: var(--text-primary);">
        <?= htmlspecialchars($invoice['repair_tracking_id'], ENT_QUOTES) ?>
      </div>
      <div style="font-size: 0.85rem; color: var(--text-secondary); margin-top: 2px;">
        <?= htmlspecialchars(($invoice['device_brand'] ?? '') . ' ' . ($invoice['device_model'] ?? ''), ENT_QUOTES) ?>
      </div>
      <a href="<?= url('/admin/repairs/' . $invoice['repair_job_id']) ?>" class="btn btn-sm" style="margin-top: 10px; background: #ffffff; border: 1px solid var(--border-color); color: var(--primary-color); font-weight: 700; width: 100%; display: flex; justify-content: center; text-decoration: none;">
        <i class="fas fa-external-link-alt" style="margin-right: 6px;"></i> Open Job Card in Lab
      </a>
    </div>
    <?php endif; ?>

    <!-- Delete Invoice (Danger Zone) -->
    <div style="text-align: center; margin-top: 10px;">
      <form method="POST" action="<?= url('/admin/invoices/' . $invoice['id'] . '/delete') ?>" onsubmit="return confirm('Are you sure you want to permanently delete this invoice?');">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES) ?>" />
        <button type="submit" style="background: none; border: none; color: #ef4444; font-size: 0.82rem; font-weight: 600; cursor: pointer;">
          <i class="fas fa-trash-alt" style="margin-right: 4px;"></i> Delete Invoice Permanently
        </button>
      </form>
    </div>

  </div><!-- /right -->

</div>
