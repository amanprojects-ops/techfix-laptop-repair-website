<?php
/**
 * TechFix Invoicing Engine — Template 2: Classic Corporate & GST Tax Invoice
 * 
 * @var array $invoice
 * @var array $items
 * @var array $template
 * @var array $settings
 * @var bool  $isPrintMode
 */

$accentColor = $template['accent_color'] ?? '#1E293B';
$secondaryColor = $template['secondary_color'] ?? '#334155';
$fontFamily = $template['font_family'] ?? 'Inter, sans-serif';
$showWatermark = !empty($template['show_watermark']) && $invoice['status'] === 'paid';
$showQr = !empty($template['show_qr_code']) && !empty($invoice['payment_qr_data']) && ((string)($settings['billing_show_upi_qr'] ?? '1') === '1');
$showSignature = !empty($template['show_signature']);
$showBank = !empty($template['show_bank_details']) && ((string)($settings['billing_show_bank_details'] ?? '1') === '1');

$taxRateHalf = ((float)($invoice['tax_rate'] ?? 18.0)) / 2.0;
$taxAmountHalf = ((float)($invoice['tax_amount'] ?? 0.0)) / 2.0;
?>
<div class="invoice-container invoice-classic" style="font-family: <?= htmlspecialchars($fontFamily, ENT_QUOTES) ?>; color: #000000; background: #FFFFFF; max-width: 860px; margin: 0 auto; padding: 25px; box-sizing: border-box; position: relative; border: 2px solid #334155; border-radius: 4px;">

  <!-- Watermark -->
  <?php if ($showWatermark): ?>
  <div style="position: absolute; top: 45%; left: 50%; transform: translate(-50%, -50%) rotate(-30deg); font-size: 5rem; font-weight: 900; color: rgba(0, 0, 0, 0.07); border: 6px solid rgba(0, 0, 0, 0.1); padding: 10px 40px; text-transform: uppercase; letter-spacing: 10px; pointer-events: none; user-select: none; z-index: 1;">
    TAX INVOICE (PAID)
  </div>
  <?php endif; ?>

  <!-- Title Header Bar -->
  <div style="text-align: center; border-bottom: 2px solid #334155; padding-bottom: 12px; margin-bottom: 14px;">
    <div style="font-size: 1.4rem; font-weight: 900; letter-spacing: 1px; text-transform: uppercase;">TAX INVOICE</div>
    <div style="font-size: 0.75rem; color: #475569;">(Issued under Section 31 of Central Goods and Services Tax Act, 2017)</div>
  </div>

  <!-- Company Header & Invoice Details -->
  <div style="display: grid; grid-template-columns: 1.2fr 1fr; border: 1px solid #334155; margin-bottom: 14px;">
    <div style="padding: 12px 16px; border-right: 1px solid #334155;">
      <div style="font-size: 1.35rem; font-weight: 900; color: #0F172A; text-transform: uppercase;"><?= htmlspecialchars(site_name(), ENT_QUOTES) ?></div>
      <div style="font-size: 0.82rem; font-weight: 600; color: #334155;"><?= htmlspecialchars(site_tagline(), ENT_QUOTES) ?></div>
      <div style="font-size: 0.82rem; color: #334155; margin-top: 4px;"><?= htmlspecialchars(site_address(), ENT_QUOTES) ?></div>
      <div style="font-size: 0.82rem; color: #334155;">Phone: <?= htmlspecialchars(site_phone(), ENT_QUOTES) ?> | Email: <?= htmlspecialchars(site_email(), ENT_QUOTES) ?></div>
      <div style="margin-top: 6px; font-size: 0.84rem;">
        <strong>GSTIN:</strong> <span style="font-family: monospace; font-weight: bold;"><?= htmlspecialchars($settings['billing_gst_number'] ?? '10AAACT0000A1Z5', ENT_QUOTES) ?></span> | <strong>State:</strong> Bihar (Code 10)
      </div>
      <?php if (!empty($settings['billing_pan_number'])): ?>
      <div style="font-size: 0.82rem;"><strong>PAN:</strong> <?= htmlspecialchars($settings['billing_pan_number'], ENT_QUOTES) ?></div>
      <?php endif; ?>
    </div>

    <div style="padding: 12px 16px; font-size: 0.84rem; display: flex; flex-direction: column; justify-content: space-between;">
      <div>
        <table style="width: 100%; border-collapse: collapse;">
          <tr>
            <td style="padding: 3px 0; font-weight: bold; width: 110px;">Invoice No:</td>
            <td style="padding: 3px 0; font-weight: 900; font-family: monospace; color: #0F172A;"><?= htmlspecialchars($invoice['invoice_number'], ENT_QUOTES) ?></td>
          </tr>
          <tr>
            <td style="padding: 3px 0; font-weight: bold;">Invoice Date:</td>
            <td style="padding: 3px 0;"><?= date('d-m-Y', strtotime($invoice['invoice_date'])) ?></td>
          </tr>
          <?php if (!empty($invoice['due_date'])): ?>
          <tr>
            <td style="padding: 3px 0; font-weight: bold;">Due Date:</td>
            <td style="padding: 3px 0;"><?= date('d-m-Y', strtotime($invoice['due_date'])) ?></td>
          </tr>
          <?php endif; ?>
          <?php if (!empty($invoice['repair_tracking_id'])): ?>
          <tr>
            <td style="padding: 3px 0; font-weight: bold;">Job Card Ref:</td>
            <td style="padding: 3px 0; font-weight: bold; font-family: monospace;"><?= htmlspecialchars($invoice['repair_tracking_id'], ENT_QUOTES) ?></td>
          </tr>
          <?php endif; ?>
        </table>
      </div>
      <div style="font-size: 0.8rem; margin-top: 6px; padding: 4px 8px; background: #F1F5F9; border-left: 3px solid #334155;">
        <strong>Status:</strong> <span style="text-transform: uppercase; font-weight: bold;"><?= htmlspecialchars(\App\Models\Invoice::STATUSES[$invoice['status']] ?? $invoice['status'], ENT_QUOTES) ?></span>
      </div>
    </div>
  </div>

  <!-- Customer (Billed To) Details -->
  <div style="border: 1px solid #334155; margin-bottom: 14px; padding: 10px 16px; background: #F8FAFC;">
    <div style="font-size: 0.76rem; font-weight: 900; text-transform: uppercase; color: #334155; margin-bottom: 4px;">Details of Receiver / Billed To:</div>
    <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 16px; font-size: 0.85rem;">
      <div>
        <div style="font-weight: 800; font-size: 0.95rem;"><?= htmlspecialchars($invoice['customer_name'], ENT_QUOTES) ?></div>
        <?php if (!empty($invoice['customer_address'])): ?>
        <div><?= htmlspecialchars($invoice['customer_address'], ENT_QUOTES) ?>, <?= htmlspecialchars($invoice['customer_city'] ?? '', ENT_QUOTES) ?></div>
        <?php endif; ?>
        <div>Phone: <strong><?= htmlspecialchars($invoice['customer_phone'], ENT_QUOTES) ?></strong></div>
        <?php if (!empty($invoice['customer_email'])): ?>
        <div>Email: <?= htmlspecialchars($invoice['customer_email'], ENT_QUOTES) ?></div>
        <?php endif; ?>
      </div>
      <div>
        <div><strong>Device:</strong> <?= htmlspecialchars(($invoice['device_brand'] ?? '') . ' ' . ($invoice['device_model'] ?? ''), ENT_QUOTES) ?></div>
        <?php if (!empty($invoice['device_serial'])): ?>
        <div><strong>Serial No:</strong> <?= htmlspecialchars($invoice['device_serial'], ENT_QUOTES) ?></div>
        <?php endif; ?>
        <div><strong>Place of Supply:</strong> Bihar (10)</div>
      </div>
    </div>
  </div>

  <!-- GST Items Table with CGST/SGST Grid -->
  <div style="margin-bottom: 14px;">
    <table style="width: 100%; border-collapse: collapse; font-size: 0.82rem; border: 1px solid #334155;">
      <thead>
        <tr style="background: #E2E8F0; border-bottom: 1px solid #334155; text-align: center;">
          <th style="padding: 8px 6px; border-right: 1px solid #334155; width: 35px;">S.N.</th>
          <th style="padding: 8px 8px; border-right: 1px solid #334155; text-align: left;">Description of Goods / Services</th>
          <th style="padding: 8px 6px; border-right: 1px solid #334155; width: 75px;">SAC/HSN</th>
          <th style="padding: 8px 6px; border-right: 1px solid #334155; width: 45px;">Qty</th>
          <th style="padding: 8px 6px; border-right: 1px solid #334155; text-align: right; width: 85px;">Rate (₹)</th>
          <th style="padding: 8px 6px; border-right: 1px solid #334155; text-align: right; width: 95px;">Taxable (₹)</th>
          <th style="padding: 8px 6px; text-align: right; width: 100px;">Amount (₹)</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($items as $i => $item): 
          $hsn = ($item['item_type'] === 'part') ? '84733020' : '998713';
        ?>
        <tr style="border-bottom: 1px solid #CBD5E1;">
          <td style="padding: 8px 6px; border-right: 1px solid #334155; text-align: center;"><?= $i + 1 ?></td>
          <td style="padding: 8px 8px; border-right: 1px solid #334155;">
            <div style="font-weight: 700;"><?= htmlspecialchars($item['item_name'], ENT_QUOTES) ?></div>
            <?php if (!empty($item['description'])): ?>
            <div style="font-size: 0.74rem; color: #475569;"><?= htmlspecialchars($item['description'], ENT_QUOTES) ?></div>
            <?php endif; ?>
          </td>
          <td style="padding: 8px 6px; border-right: 1px solid #334155; text-align: center; font-family: monospace; font-size: 0.76rem;"><?= $hsn ?></td>
          <td style="padding: 8px 6px; border-right: 1px solid #334155; text-align: center; font-weight: 600;"><?= (float)$item['quantity'] ?></td>
          <td style="padding: 8px 6px; border-right: 1px solid #334155; text-align: right; font-family: monospace;">₹<?= number_format((float)$item['unit_price'], 2) ?></td>
          <td style="padding: 8px 6px; border-right: 1px solid #334155; text-align: right; font-family: monospace;">₹<?= number_format((float)$item['total_price'], 2) ?></td>
          <td style="padding: 8px 6px; text-align: right; font-weight: 700; font-family: monospace;">₹<?= number_format((float)$item['total_price'], 2) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- GST Breakup & Financial Totals Grid -->
  <div style="display: grid; grid-template-columns: 1.1fr 1fr; border: 1px solid #334155; margin-bottom: 14px;">
    
    <!-- Bank & UPI Information -->
    <div style="padding: 10px 14px; border-right: 1px solid #334155; font-size: 0.8rem; display: flex; flex-direction: column; justify-content: space-between;">
      <div>
        <?php if ($showBank && !empty($settings['billing_bank_account'])): ?>
        <div style="font-weight: 900; text-transform: uppercase; margin-bottom: 4px;">Bank Account Details for Payment:</div>
        <div>Bank Name: <strong><?= htmlspecialchars($settings['billing_bank_name'] ?? 'State Bank of India', ENT_QUOTES) ?></strong></div>
        <div>A/C Number: <strong style="font-family: monospace;"><?= htmlspecialchars($settings['billing_bank_account'] ?? '389201948201', ENT_QUOTES) ?></strong></div>
        <div>IFSC Code: <strong style="font-family: monospace;"><?= htmlspecialchars($settings['billing_bank_ifsc'] ?? 'SBIN0001234', ENT_QUOTES) ?></strong></div>
        <?php if (!empty($settings['billing_bank_branch'])): ?>
        <div>Branch: <?= htmlspecialchars($settings['billing_bank_branch'], ENT_QUOTES) ?></div>
        <?php endif; ?>
        <?php endif; ?>

        <?php if (((string)($settings['billing_show_upi_qr'] ?? '1') === '1') && !empty($settings['billing_upi_id'])): ?>
        <div style="<?= ($showBank && !empty($settings['billing_bank_account'])) ? 'margin-top: 6px;' : '' ?>">UPI ID: <strong style="font-family: monospace;"><?= htmlspecialchars($settings['billing_upi_id'] ?? 'techfix@sbi', ENT_QUOTES) ?></strong></div>
        <?php endif; ?>
      </div>

      <?php if ($showQr && (float)$invoice['balance_due'] > 0): ?>
      <div style="display: flex; align-items: center; gap: 10px; margin-top: 10px; padding-top: 8px; border-top: 1px dashed #CBD5E1;">
        <img src="<?= htmlspecialchars($invoice['payment_qr_data'], ENT_QUOTES) ?>" alt="UPI QR" style="width: 65px; height: 65px; border: 1px solid #000;" />
        <div style="font-size: 0.74rem;">
          <div style="font-weight: bold;">Scan to Pay via UPI</div>
          <div>Balance: <strong>₹<?= number_format((float)$invoice['balance_due'], 2) ?></strong></div>
        </div>
      </div>
      <?php endif; ?>
    </div>

    <!-- Calculations Table -->
    <div style="padding: 10px 14px; font-size: 0.84rem;">
      <table style="width: 100%; border-collapse: collapse;">
        <tr>
          <td style="padding: 3px 0;">Total Taxable Amount:</td>
          <td style="padding: 3px 0; text-align: right; font-family: monospace; font-weight: bold;">₹<?= number_format((float)$invoice['subtotal'], 2) ?></td>
        </tr>
        <?php if ((float)$invoice['discount_amount'] > 0): ?>
        <tr>
          <td style="padding: 3px 0; color: #059669;">Less: Discount:</td>
          <td style="padding: 3px 0; text-align: right; font-family: monospace; color: #059669;">-₹<?= number_format((float)$invoice['discount_amount'], 2) ?></td>
        </tr>
        <?php endif; ?>

        <?php if ((float)$invoice['tax_amount'] > 0): ?>
        <tr>
          <td style="padding: 3px 0;">Add: CGST @ <?= $taxRateHalf ?>%:</td>
          <td style="padding: 3px 0; text-align: right; font-family: monospace;">₹<?= number_format($taxAmountHalf, 2) ?></td>
        </tr>
        <tr>
          <td style="padding: 3px 0;">Add: SGST @ <?= $taxRateHalf ?>%:</td>
          <td style="padding: 3px 0; text-align: right; font-family: monospace;">₹<?= number_format($taxAmountHalf, 2) ?></td>
        </tr>
        <?php endif; ?>

        <tr style="border-top: 1px solid #334155; border-bottom: 1px solid #334155;">
          <td style="padding: 6px 0; font-size: 1rem; font-weight: 900; text-transform: uppercase;">Total Invoice Value:</td>
          <td style="padding: 6px 0; text-align: right; font-size: 1.05rem; font-weight: 900; font-family: monospace;">₹<?= number_format((float)$invoice['total_amount'], 2) ?></td>
        </tr>

        <tr>
          <td style="padding: 4px 0; font-weight: bold; color: #059669;">Amount Received:</td>
          <td style="padding: 4px 0; text-align: right; font-family: monospace; font-weight: bold; color: #059669;">₹<?= number_format((float)$invoice['paid_amount'], 2) ?></td>
        </tr>

        <tr style="border-top: 1px dashed #334155;">
          <td style="padding: 4px 0; font-weight: 900; color: #DC2626;">Net Balance Due:</td>
          <td style="padding: 4px 0; text-align: right; font-family: monospace; font-weight: 900; color: #DC2626;">₹<?= number_format((float)$invoice['balance_due'], 2) ?></td>
        </tr>
      </table>
    </div>
  </div>

  <!-- Terms & Declaration -->
  <div style="display: grid; grid-template-columns: 1.4fr 1fr; gap: 16px; border: 1px solid #334155; padding: 10px 14px; font-size: 0.74rem;">
    <div>
      <div style="font-weight: 800; text-transform: uppercase; margin-bottom: 2px;">Declaration &amp; Warranty Terms:</div>
      <div>1. We declare that this invoice shows the actual price of the goods/services described and that all particulars are true and correct.</div>
      <div>2. 90-day warranty applies strictly to replaced parts with warranty stickers intact.</div>
      <div>3. Physical damage, liquid ingress, or unauthorized tamper voids all warranty.</div>
    </div>

    <div style="text-align: center; display: flex; flex-direction: column; justify-content: space-between;">
      <div style="font-weight: 800; text-transform: uppercase;">For <?= htmlspecialchars(site_name(), ENT_QUOTES) ?></div>
      <div style="border-bottom: 1px solid #000; height: 35px; margin: 0 20px;"></div>
      <div style="font-weight: bold; margin-top: 4px;">Authorized Signatory</div>
    </div>
  </div>

</div>
