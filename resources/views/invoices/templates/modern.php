<?php
/**
 * TechFix Invoicing Engine — Template 1: Modern Minimalist
 * 
 * @var array $invoice
 * @var array $items
 * @var array $template
 * @var array $settings
 * @var bool  $isPrintMode
 */

$accentColor = $template['accent_color'] ?? '#2563EB';
$secondaryColor = $template['secondary_color'] ?? '#0F172A';
$fontFamily = $template['font_family'] ?? 'Inter, sans-serif';
$showWatermark = !empty($template['show_watermark']) && $invoice['status'] === 'paid';
$showQr = !empty($template['show_qr_code']) && !empty($invoice['payment_qr_data']);
$showSignature = !empty($template['show_signature']);
$showBank = !empty($template['show_bank_details']);
$showTax = !empty($template['show_tax_breakup']);
?>
<div class="invoice-container invoice-modern" style="font-family: <?= htmlspecialchars($fontFamily, ENT_QUOTES) ?>; color: #1E293B; background: #FFFFFF; max-width: 860px; margin: 0 auto; padding: 40px; box-sizing: border-box; position: relative; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.06);">

  <!-- Paid Watermark -->
  <?php if ($showWatermark): ?>
  <div style="position: absolute; top: 38%; left: 50%; transform: translate(-50%, -50%) rotate(-25deg); font-size: 5.5rem; font-weight: 900; color: rgba(16, 185, 129, 0.12); border: 8px solid rgba(16, 185, 129, 0.18); padding: 10px 40px; border-radius: 20px; text-transform: uppercase; letter-spacing: 12px; pointer-events: none; user-select: none; z-index: 1;">
    <?= htmlspecialchars($template['watermark_text'] ?? 'PAID', ENT_QUOTES) ?>
  </div>
  <?php endif; ?>

  <!-- Header Section -->
  <div style="display: flex; justify-content: space-between; align-items: flex-start; padding-bottom: 28px; border-bottom: 2px solid #F1F5F9; margin-bottom: 30px; gap: 20px; flex-wrap: wrap;">
    <div style="flex: 1; min-width: 250px;">
      <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
        <?php if (!empty($settings['site_logo'])): ?>
          <img src="<?= asset('/' . ltrim($settings['site_logo'], '/')) ?>" alt="<?= htmlspecialchars(site_name(), ENT_QUOTES) ?>" style="max-height: 48px; max-width: 180px; object-fit: contain;" />
        <?php else: ?>
          <div style="font-size: 1.6rem; font-weight: 900; color: <?= $accentColor ?>; letter-spacing: -0.5px;">
            <i class="fas fa-tools" style="margin-right: 6px;"></i><?= htmlspecialchars(site_name(), ENT_QUOTES) ?>
          </div>
        <?php endif; ?>
      </div>
      <div style="font-size: 0.88rem; color: #64748B; line-height: 1.5;">
        <div style="font-weight: 600; color: #334155;"><?= htmlspecialchars(site_tagline(), ENT_QUOTES) ?></div>
        <div><?= htmlspecialchars(site_address(), ENT_QUOTES) ?></div>
        <div>Phone: <strong><?= htmlspecialchars(site_phone(), ENT_QUOTES) ?></strong> | Email: <?= htmlspecialchars(site_email(), ENT_QUOTES) ?></div>
        <?php if (!empty($settings['billing_gst_number'])): ?>
        <div style="margin-top: 4px; font-weight: 700; color: #0F172A;">GSTIN: <span style="font-family: monospace;"><?= htmlspecialchars($settings['billing_gst_number'], ENT_QUOTES) ?></span></div>
        <?php endif; ?>
      </div>
    </div>

    <div style="text-align: right; min-width: 200px;">
      <div style="font-size: 1.75rem; font-weight: 900; color: <?= $secondaryColor ?>; text-transform: uppercase; letter-spacing: 1px; line-height: 1;">
        TAX INVOICE
      </div>
      <div style="font-size: 1.15rem; font-weight: 800; color: <?= $accentColor ?>; margin-top: 6px; font-family: monospace;">
        #<?= htmlspecialchars($invoice['invoice_number'], ENT_QUOTES) ?>
      </div>
      <div style="margin-top: 10px; display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; border-radius: 9999px; font-size: 0.8rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; background: <?= $invoice['status'] === 'paid' ? '#ECFDF5' : ($invoice['status'] === 'partially_paid' ? '#FFFBEB' : '#EFF6FF') ?>; color: <?= $invoice['status'] === 'paid' ? '#059669' : ($invoice['status'] === 'partially_paid' ? '#D97706' : '#2563EB') ?>; border: 1px solid currentColor;">
        <span style="width: 8px; height: 8px; border-radius: 50%; background: currentColor;"></span>
        <?= htmlspecialchars(\App\Models\Invoice::STATUSES[$invoice['status']] ?? ucfirst($invoice['status']), ENT_QUOTES) ?>
      </div>
    </div>
  </div>

  <!-- Meta Info Grid (Billed To, Dates, Repair Ticket) -->
  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 24px; margin-bottom: 32px; background: #F8FAFC; padding: 20px 24px; border-radius: 10px; border: 1px solid #E2E8F0;">
    <div>
      <div style="font-size: 0.72rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.8px; color: #64748B; margin-bottom: 6px;">Billed To (Customer)</div>
      <div style="font-size: 1.05rem; font-weight: 800; color: #0F172A;"><?= htmlspecialchars($invoice['customer_name'], ENT_QUOTES) ?></div>
      <div style="font-size: 0.88rem; color: #475569; margin-top: 2px;"><?= htmlspecialchars($invoice['customer_phone'], ENT_QUOTES) ?></div>
      <?php if (!empty($invoice['customer_email'])): ?>
      <div style="font-size: 0.82rem; color: #64748B;"><?= htmlspecialchars($invoice['customer_email'], ENT_QUOTES) ?></div>
      <?php endif; ?>
      <?php if (!empty($invoice['customer_address'])): ?>
      <div style="font-size: 0.82rem; color: #64748B; margin-top: 4px;"><?= htmlspecialchars($invoice['customer_address'], ENT_QUOTES) ?>, <?= htmlspecialchars($invoice['customer_city'] ?? '', ENT_QUOTES) ?></div>
      <?php endif; ?>
    </div>

    <div>
      <div style="font-size: 0.72rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.8px; color: #64748B; margin-bottom: 6px;">Invoice Details</div>
      <div style="font-size: 0.88rem; color: #334155; margin-bottom: 4px;">
        <span style="color: #64748B;">Invoice Date:</span> <strong><?= date('d M Y', strtotime($invoice['invoice_date'])) ?></strong>
      </div>
      <?php if (!empty($invoice['due_date'])): ?>
      <div style="font-size: 0.88rem; color: #334155; margin-bottom: 4px;">
        <span style="color: #64748B;">Due Date:</span> <strong><?= date('d M Y', strtotime($invoice['due_date'])) ?></strong>
      </div>
      <?php endif; ?>
      <div style="font-size: 0.88rem; color: #334155;">
        <span style="color: #64748B;">Payment Mode:</span> <strong style="text-transform: uppercase;"><?= htmlspecialchars($invoice['payment_method'], ENT_QUOTES) ?></strong>
      </div>
    </div>

    <?php if (!empty($invoice['repair_tracking_id'])): ?>
    <div>
      <div style="font-size: 0.72rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.8px; color: #64748B; margin-bottom: 6px;">Repair Job Info</div>
      <div style="font-size: 0.95rem; font-weight: 800; color: <?= $accentColor ?>; font-family: monospace;">
        <?= htmlspecialchars($invoice['repair_tracking_id'], ENT_QUOTES) ?>
      </div>
      <div style="font-size: 0.88rem; color: #334155; font-weight: 700; margin-top: 2px;">
        <?= htmlspecialchars(($invoice['device_brand'] ?? '') . ' ' . ($invoice['device_model'] ?? ''), ENT_QUOTES) ?>
      </div>
      <?php if (!empty($invoice['device_serial'])): ?>
      <div style="font-size: 0.78rem; color: #64748B;">S/N: <?= htmlspecialchars($invoice['device_serial'], ENT_QUOTES) ?></div>
      <?php endif; ?>
    </div>
    <?php endif; ?>
  </div>

  <!-- Line Items Table -->
  <div style="margin-bottom: 30px; overflow-x: auto;">
    <table style="width: 100%; border-collapse: collapse; font-size: 0.92rem; text-align: left;">
      <thead>
        <tr style="background: <?= $secondaryColor ?>; color: #FFFFFF;">
          <th style="padding: 12px 14px; font-weight: 700; border-top-left-radius: 6px; width: 40px; text-align: center;">#</th>
          <th style="padding: 12px 14px; font-weight: 700;">Service / Hardware Description</th>
          <th style="padding: 12px 14px; font-weight: 700; text-align: center; width: 80px;">Type</th>
          <th style="padding: 12px 14px; font-weight: 700; text-align: center; width: 70px;">Qty</th>
          <th style="padding: 12px 14px; font-weight: 700; text-align: right; width: 110px;">Rate (₹)</th>
          <th style="padding: 12px 14px; font-weight: 700; text-align: right; width: 120px; border-top-right-radius: 6px;">Total (₹)</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($items as $i => $item): ?>
        <tr style="border-bottom: 1px solid #E2E8F0; <?= $i % 2 === 1 ? 'background: #FAFAFA;' : '' ?>">
          <td style="padding: 12px 14px; text-align: center; color: #64748B; font-weight: 600;"><?= $i + 1 ?></td>
          <td style="padding: 12px 14px;">
            <div style="font-weight: 700; color: #0F172A;"><?= htmlspecialchars($item['item_name'], ENT_QUOTES) ?></div>
            <?php if (!empty($item['description'])): ?>
            <div style="font-size: 0.8rem; color: #64748B; margin-top: 2px;"><?= htmlspecialchars($item['description'], ENT_QUOTES) ?></div>
            <?php endif; ?>
          </td>
          <td style="padding: 12px 14px; text-align: center;">
            <span style="background: #F1F5F9; color: #475569; font-size: 0.72rem; padding: 2px 8px; border-radius: 4px; font-weight: 700; text-transform: uppercase;">
              <?= htmlspecialchars($item['item_type'] ?? 'service', ENT_QUOTES) ?>
            </span>
          </td>
          <td style="padding: 12px 14px; text-align: center; font-weight: 600;"><?= (float)$item['quantity'] ?></td>
          <td style="padding: 12px 14px; text-align: right; font-weight: 600; font-family: monospace;">₹<?= number_format((float)$item['unit_price'], 2) ?></td>
          <td style="padding: 12px 14px; text-align: right; font-weight: 800; color: #0F172A; font-family: monospace;">₹<?= number_format((float)$item['total_price'], 2) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- Summary Calculation & UPI QR Section -->
  <div style="display: grid; grid-template-columns: 1fr 340px; gap: 30px; margin-bottom: 30px; align-items: start;">
    
    <!-- Left: Notes, Bank Details & UPI QR -->
    <div style="display: flex; flex-direction: column; gap: 16px;">
      
      <?php if ($showQr && (float)$invoice['balance_due'] > 0): ?>
      <div style="display: flex; align-items: center; gap: 16px; background: #EFF6FF; border: 1px solid #BFDBFE; padding: 14px 18px; border-radius: 8px;">
        <img src="<?= htmlspecialchars($invoice['payment_qr_data'], ENT_QUOTES) ?>" alt="Scan to Pay UPI" style="width: 86px; height: 86px; border-radius: 6px; background: #fff; padding: 4px; border: 1px solid #93C5FD;" />
        <div>
          <div style="font-size: 0.8rem; font-weight: 800; color: #1E40AF; text-transform: uppercase; letter-spacing: 0.5px;">Instant UPI Scan &amp; Pay</div>
          <div style="font-size: 0.78rem; color: #3B82F6; margin-top: 2px;">Scan with GPay, PhonePe, Paytm, or BHIM</div>
          <div style="font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-top: 4px; font-family: monospace;"><?= htmlspecialchars($settings['billing_upi_id'] ?? 'techfix@sbi', ENT_QUOTES) ?></div>
        </div>
      </div>
      <?php endif; ?>

      <?php if ($showBank && !empty($settings['billing_bank_account'])): ?>
      <div style="background: #F8FAFC; border: 1px solid #E2E8F0; padding: 14px 18px; border-radius: 8px; font-size: 0.82rem; color: #475569;">
        <div style="font-weight: 800; color: #0F172A; text-transform: uppercase; margin-bottom: 4px; letter-spacing: 0.5px;">Direct Bank Transfer (NEFT / IMPS)</div>
        <div>Bank: <strong><?= htmlspecialchars($settings['billing_bank_name'] ?? 'State Bank of India', ENT_QUOTES) ?></strong></div>
        <div>A/C No: <strong style="font-family: monospace;"><?= htmlspecialchars($settings['billing_bank_account'], ENT_QUOTES) ?></strong> | IFSC: <strong style="font-family: monospace;"><?= htmlspecialchars($settings['billing_bank_ifsc'] ?? '', ENT_QUOTES) ?></strong></div>
        <div>Branch: <?= htmlspecialchars($settings['billing_bank_branch'] ?? '', ENT_QUOTES) ?></div>
      </div>
      <?php endif; ?>

      <?php if (!empty($invoice['notes'])): ?>
      <div style="font-size: 0.82rem; color: #64748B; line-height: 1.5;">
        <strong style="color: #334155;">Customer Note:</strong> <?= nl2br(htmlspecialchars($invoice['notes'], ENT_QUOTES)) ?>
      </div>
      <?php endif; ?>
    </div>

    <!-- Right: Totals Box -->
    <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px; padding: 18px 22px;">
      <div style="display: flex; justify-content: space-between; font-size: 0.9rem; margin-bottom: 8px; color: #475569;">
        <span>Subtotal:</span>
        <span style="font-weight: 700; color: #0F172A; font-family: monospace;">₹<?= number_format((float)$invoice['subtotal'], 2) ?></span>
      </div>

      <?php if ((float)$invoice['discount_amount'] > 0): ?>
      <div style="display: flex; justify-content: space-between; font-size: 0.9rem; margin-bottom: 8px; color: #059669;">
        <span>Discount (<?= $invoice['discount_type'] === 'percentage' ? (float)$invoice['discount_value'] . '%' : 'Fixed' ?>):</span>
        <span style="font-weight: 700; font-family: monospace;">-₹<?= number_format((float)$invoice['discount_amount'], 2) ?></span>
      </div>
      <?php endif; ?>

      <?php if ($showTax && (float)$invoice['tax_rate'] > 0): ?>
      <div style="display: flex; justify-content: space-between; font-size: 0.9rem; margin-bottom: 8px; color: #475569;">
        <span><?= htmlspecialchars($invoice['tax_name'] ?: 'GST', ENT_QUOTES) ?> (<?= (float)$invoice['tax_rate'] ?>%):</span>
        <span style="font-weight: 700; color: #0F172A; font-family: monospace;">₹<?= number_format((float)$invoice['tax_amount'], 2) ?></span>
      </div>
      <?php endif; ?>

      <?php if ((float)$invoice['shipping_or_handling'] > 0): ?>
      <div style="display: flex; justify-content: space-between; font-size: 0.9rem; margin-bottom: 8px; color: #475569;">
        <span>Handling &amp; Logistics:</span>
        <span style="font-weight: 700; color: #0F172A; font-family: monospace;">₹<?= number_format((float)$invoice['shipping_or_handling'], 2) ?></span>
      </div>
      <?php endif; ?>

      <div style="height: 1px; background: #CBD5E1; margin: 10px 0;"></div>

      <div style="display: flex; justify-content: space-between; font-size: 1.15rem; font-weight: 900; color: <?= $accentColor ?>; margin-bottom: 10px;">
        <span>Grand Total:</span>
        <span style="font-family: monospace;">₹<?= number_format((float)$invoice['total_amount'], 2) ?></span>
      </div>

      <div style="display: flex; justify-content: space-between; font-size: 0.9rem; margin-bottom: 6px; color: #059669;">
        <span>Amount Paid:</span>
        <span style="font-weight: 800; font-family: monospace;">₹<?= number_format((float)$invoice['paid_amount'], 2) ?></span>
      </div>

      <div style="display: flex; justify-content: space-between; font-size: 1.05rem; font-weight: 900; padding: 8px 12px; border-radius: 6px; background: <?= (float)$invoice['balance_due'] > 0 ? '#FEF2F2' : '#ECFDF5' ?>; color: <?= (float)$invoice['balance_due'] > 0 ? '#DC2626' : '#059669' ?>; border: 1px solid <?= (float)$invoice['balance_due'] > 0 ? '#FECACA' : '#A7F3D0' ?>;">
        <span>Balance Due:</span>
        <span style="font-family: monospace;">₹<?= number_format((float)$invoice['balance_due'], 2) ?></span>
      </div>
    </div>
  </div>

  <!-- Terms & Signature Footer -->
  <div style="display: grid; grid-template-columns: 1fr 220px; gap: 20px; border-top: 1px solid #E2E8F0; padding-top: 20px; margin-top: 20px;">
    <div style="font-size: 0.78rem; color: #64748B; line-height: 1.5;">
      <div style="font-weight: 700; color: #334155; text-transform: uppercase; margin-bottom: 4px;">Terms &amp; Conditions</div>
      <?= nl2br(htmlspecialchars($invoice['terms_conditions'] ?? $settings['billing_default_terms'] ?? '', ENT_QUOTES)) ?>
    </div>

    <?php if ($showSignature): ?>
    <div style="text-align: center; display: flex; flex-direction: column; justify-content: flex-end;">
      <div style="border-bottom: 1px solid #94A3B8; height: 40px; margin-bottom: 6px;"></div>
      <div style="font-size: 0.78rem; font-weight: 800; color: #0F172A; text-transform: uppercase;"><?= htmlspecialchars(site_name(), ENT_QUOTES) ?></div>
      <div style="font-size: 0.72rem; color: #64748B;">Authorized Signatory</div>
    </div>
    <?php endif; ?>
  </div>

</div>
