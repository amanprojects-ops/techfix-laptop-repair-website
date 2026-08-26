<?php
/**
 * TechFix Invoicing Engine — Dynamic Custom Template Renderer
 * 
 * @var array $invoice
 * @var array $items
 * @var array $template
 * @var array $settings
 * @var bool  $isPrintMode
 */

$accentColor    = $template['accent_color'] ?? '#2563EB';
$secondaryColor = $template['secondary_color'] ?? '#0F172A';
$fontFamily     = $template['font_family'] ?? 'Inter, sans-serif';
$paperSize      = $template['paper_size'] ?? 'A4';
$showWatermark  = !empty($template['show_watermark']) && $invoice['status'] === 'paid';
$showQr         = !empty($template['show_qr_code']) && !empty($invoice['payment_qr_data']) && ((string)($settings['billing_show_upi_qr'] ?? '1') === '1');
$showSignature  = !empty($template['show_signature']);
$showBank       = !empty($template['show_bank_details']) && ((string)($settings['billing_show_bank_details'] ?? '1') === '1');
$showTax        = !empty($template['show_tax_breakup']);
$headerLayout   = $template['header_layout'] ?? 'standard';
$customCss      = $template['custom_css'] ?? '';
?>

<?php if (!empty($customCss)): ?>
<style>
<?= $customCss ?>
</style>
<?php endif; ?>

<div class="invoice-container invoice-custom template-<?= htmlspecialchars($template['template_key'] ?? 'custom', ENT_QUOTES) ?>" style="font-family: <?= htmlspecialchars($fontFamily, ENT_QUOTES) ?>; color: #0F172A; background: #FFFFFF; max-width: <?= $paperSize === '80mm_pos' ? '320px' : '860px' ?>; margin: 0 auto; padding: <?= $paperSize === '80mm_pos' ? '16px' : '36px' ?>; box-sizing: border-box; position: relative; border-radius: 8px; border: 1px solid #E2E8F0; box-shadow: 0 4px 16px rgba(0,0,0,0.05);">

  <!-- Watermark -->
  <?php if ($showWatermark): ?>
  <div style="position: absolute; top: 40%; left: 50%; transform: translate(-50%, -50%) rotate(-25deg); font-size: 5rem; font-weight: 900; color: rgba(37, 99, 235, 0.08); border: 6px solid rgba(37, 99, 235, 0.12); padding: 10px 40px; text-transform: uppercase; letter-spacing: 10px; pointer-events: none; user-select: none; z-index: 1;">
    <?= htmlspecialchars($template['watermark_text'] ?? 'PAID', ENT_QUOTES) ?>
  </div>
  <?php endif; ?>

  <!-- Dynamic Header -->
  <div style="display: flex; justify-content: space-between; align-items: flex-start; padding-bottom: 24px; border-bottom: 2px solid <?= $accentColor ?>; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
      <div style="font-size: 1.6rem; font-weight: 900; color: <?= $secondaryColor ?>;"><?= htmlspecialchars(site_name(), ENT_QUOTES) ?></div>
      <div style="font-size: 0.85rem; color: #64748B; margin-top: 2px;"><?= htmlspecialchars(site_tagline(), ENT_QUOTES) ?></div>
      <div style="font-size: 0.8rem; color: #64748B; margin-top: 4px;"><?= htmlspecialchars(site_address(), ENT_QUOTES) ?> | Ph: <?= htmlspecialchars(site_phone(), ENT_QUOTES) ?></div>
      <?php if (!empty($settings['billing_gst_number'])): ?>
      <div style="font-size: 0.8rem; font-weight: bold; color: #0F172A; margin-top: 2px;">GSTIN: <?= htmlspecialchars($settings['billing_gst_number'], ENT_QUOTES) ?></div>
      <?php endif; ?>
    </div>

    <div style="text-align: right;">
      <div style="font-size: 1.5rem; font-weight: 900; color: <?= $accentColor ?>; text-transform: uppercase;">TAX INVOICE</div>
      <div style="font-size: 1.05rem; font-weight: 800; font-family: monospace; color: #0F172A; margin-top: 2px;">#<?= htmlspecialchars($invoice['invoice_number'], ENT_QUOTES) ?></div>
      <div style="margin-top: 6px; display: inline-block; padding: 3px 10px; border-radius: 9999px; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; background: <?= $invoice['status'] === 'paid' ? '#ECFDF5' : '#FFFBEB' ?>; color: <?= $invoice['status'] === 'paid' ? '#059669' : '#D97706' ?>; border: 1px solid currentColor;">
        <?= htmlspecialchars(\App\Models\Invoice::STATUSES[$invoice['status']] ?? $invoice['status'], ENT_QUOTES) ?>
      </div>
    </div>
  </div>

  <!-- Customer & Meta Grid -->
  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px; background: #F8FAFC; padding: 16px; border-radius: 6px;">
    <div>
      <div style="font-size: 0.7rem; font-weight: 800; text-transform: uppercase; color: #64748B;">Client Details</div>
      <div style="font-weight: 800; font-size: 0.95rem; color: #0F172A; margin-top: 2px;"><?= htmlspecialchars($invoice['customer_name'], ENT_QUOTES) ?></div>
      <div style="font-size: 0.85rem; color: #475569;"><?= htmlspecialchars($invoice['customer_phone'], ENT_QUOTES) ?></div>
      <?php if (!empty($invoice['customer_email'])): ?>
      <div style="font-size: 0.8rem; color: #64748B;"><?= htmlspecialchars($invoice['customer_email'], ENT_QUOTES) ?></div>
      <?php endif; ?>
    </div>

    <div>
      <div style="font-size: 0.7rem; font-weight: 800; text-transform: uppercase; color: #64748B;">Invoice Details</div>
      <div style="font-size: 0.85rem; color: #334155;">Date: <strong><?= date('d M Y', strtotime($invoice['invoice_date'])) ?></strong></div>
      <?php if (!empty($invoice['due_date'])): ?>
      <div style="font-size: 0.85rem; color: #334155;">Due: <strong><?= date('d M Y', strtotime($invoice['due_date'])) ?></strong></div>
      <?php endif; ?>
    </div>

    <?php if (!empty($invoice['repair_tracking_id'])): ?>
    <div>
      <div style="font-size: 0.7rem; font-weight: 800; text-transform: uppercase; color: #64748B;">Repair Ticket</div>
      <div style="font-size: 0.9rem; font-weight: 800; color: <?= $accentColor ?>; font-family: monospace;"><?= htmlspecialchars($invoice['repair_tracking_id'], ENT_QUOTES) ?></div>
      <div style="font-size: 0.82rem; color: #475569;"><?= htmlspecialchars(($invoice['device_brand'] ?? '') . ' ' . ($invoice['device_model'] ?? ''), ENT_QUOTES) ?></div>
    </div>
    <?php endif; ?>
  </div>

  <!-- Items Table -->
  <div style="margin-bottom: 24px; overflow-x: auto;">
    <table style="width: 100%; border-collapse: collapse; font-size: 0.88rem;">
      <thead>
        <tr style="background: <?= $secondaryColor ?>; color: #FFFFFF;">
          <th style="padding: 10px 12px; text-align: center; width: 35px;">#</th>
          <th style="padding: 10px 12px; text-align: left;">Item Description</th>
          <th style="padding: 10px 12px; text-align: center; width: 60px;">Qty</th>
          <th style="padding: 10px 12px; text-align: right; width: 100px;">Rate (₹)</th>
          <th style="padding: 10px 12px; text-align: right; width: 110px;">Total (₹)</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($items as $i => $item): ?>
        <tr style="border-bottom: 1px solid #E2E8F0; <?= $i % 2 === 1 ? 'background: #F8FAFC;' : '' ?>">
          <td style="padding: 10px 12px; text-align: center; color: #64748B; font-weight: bold;"><?= $i + 1 ?></td>
          <td style="padding: 10px 12px;">
            <div style="font-weight: 700; color: #0F172A;"><?= htmlspecialchars($item['item_name'], ENT_QUOTES) ?></div>
            <?php if (!empty($item['description'])): ?>
            <div style="font-size: 0.78rem; color: #64748B;"><?= htmlspecialchars($item['description'], ENT_QUOTES) ?></div>
            <?php endif; ?>
          </td>
          <td style="padding: 10px 12px; text-align: center;"><?= (float)$item['quantity'] ?></td>
          <td style="padding: 10px 12px; text-align: right; font-family: monospace;">₹<?= number_format((float)$item['unit_price'], 2) ?></td>
          <td style="padding: 10px 12px; text-align: right; font-weight: 800; font-family: monospace;">₹<?= number_format((float)$item['total_price'], 2) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- Bottom Totals & UPI QR -->
  <div style="display: grid; grid-template-columns: 1fr 300px; gap: 20px; align-items: start; margin-bottom: 20px;">
    <div>
      <?php if ($showQr && (float)$invoice['balance_due'] > 0): ?>
      <div style="display: flex; align-items: center; gap: 12px; background: #F8FAFC; border: 1px solid #E2E8F0; padding: 10px 14px; border-radius: 6px;">
        <img src="<?= htmlspecialchars($invoice['payment_qr_data'], ENT_QUOTES) ?>" alt="QR" style="width: 75px; height: 75px; border-radius: 4px; background: #fff; padding: 2px;" />
        <div style="font-size: 0.78rem;">
          <div style="font-weight: 800; color: #0F172A; text-transform: uppercase;">UPI Scan &amp; Pay</div>
          <div style="font-family: monospace; font-size: 0.74rem; color: #475569;"><?= htmlspecialchars($settings['billing_upi_id'] ?? 'techfix@sbi', ENT_QUOTES) ?></div>
        </div>
      </div>
      <?php endif; ?>

      <?php if ($showBank && !empty($settings['billing_bank_account'])): ?>
      <div style="background: #F8FAFC; border: 1px solid #E2E8F0; padding: 10px 14px; border-radius: 6px; font-size: 0.78rem; color: #475569; margin-top: 8px;">
        <div style="font-weight: 800; color: #0F172A; text-transform: uppercase; margin-bottom: 2px;">Bank Transfer (NEFT / IMPS)</div>
        <div>Bank: <strong><?= htmlspecialchars($settings['billing_bank_name'] ?? '', ENT_QUOTES) ?></strong></div>
        <div>A/C: <strong style="font-family: monospace;"><?= htmlspecialchars($settings['billing_bank_account'], ENT_QUOTES) ?></strong> | IFSC: <strong style="font-family: monospace;"><?= htmlspecialchars($settings['billing_bank_ifsc'] ?? '', ENT_QUOTES) ?></strong></div>
      </div>
      <?php endif; ?>

      <?php if (!empty($invoice['notes'])): ?>
      <div style="font-size: 0.8rem; color: #64748B; margin-top: 10px;">
        <strong>Note:</strong> <?= nl2br(htmlspecialchars($invoice['notes'], ENT_QUOTES)) ?>
      </div>
      <?php endif; ?>
    </div>

    <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 6px; padding: 14px 18px;">
      <div style="display: flex; justify-content: space-between; font-size: 0.86rem; margin-bottom: 6px; color: #475569;">
        <span>Subtotal:</span>
        <span style="font-family: monospace; font-weight: bold;">₹<?= number_format((float)$invoice['subtotal'], 2) ?></span>
      </div>
      <?php if ((float)$invoice['discount_amount'] > 0): ?>
      <div style="display: flex; justify-content: space-between; font-size: 0.86rem; margin-bottom: 6px; color: #059669;">
        <span>Discount:</span>
        <span style="font-family: monospace; font-weight: bold;">-₹<?= number_format((float)$invoice['discount_amount'], 2) ?></span>
      </div>
      <?php endif; ?>
      <?php if ($showTax && (float)$invoice['tax_amount'] > 0): ?>
      <div style="display: flex; justify-content: space-between; font-size: 0.86rem; margin-bottom: 6px; color: #475569;">
        <span>GST (<?= (float)$invoice['tax_rate'] ?>%):</span>
        <span style="font-family: monospace; font-weight: bold;">₹<?= number_format((float)$invoice['tax_amount'], 2) ?></span>
      </div>
      <?php endif; ?>
      <div style="height: 1px; background: #E2E8F0; margin: 8px 0;"></div>
      <div style="display: flex; justify-content: space-between; font-size: 1.1rem; font-weight: 900; color: #0F172A; margin-bottom: 6px;">
        <span>Total:</span>
        <span style="font-family: monospace; color: <?= $accentColor ?>;">₹<?= number_format((float)$invoice['total_amount'], 2) ?></span>
      </div>
      <div style="display: flex; justify-content: space-between; font-size: 0.86rem; color: #059669; margin-bottom: 6px;">
        <span>Paid:</span>
        <span style="font-family: monospace; font-weight: bold;">₹<?= number_format((float)$invoice['paid_amount'], 2) ?></span>
      </div>
      <div style="display: flex; justify-content: space-between; font-size: 0.95rem; font-weight: 900; background: <?= (float)$invoice['balance_due'] > 0 ? '#FEF2F2' : '#ECFDF5' ?>; color: <?= (float)$invoice['balance_due'] > 0 ? '#DC2626' : '#059669' ?>; padding: 6px 10px; border-radius: 4px;">
        <span>Balance:</span>
        <span style="font-family: monospace;">₹<?= number_format((float)$invoice['balance_due'], 2) ?></span>
      </div>
    </div>
  </div>

  <!-- Terms & Signature -->
  <?php if (!empty($invoice['terms_conditions'])): ?>
  <div style="font-size: 0.72rem; color: #64748B; border-top: 1px solid #E2E8F0; padding-top: 12px; margin-top: 14px; line-height: 1.4;">
    <strong>Terms &amp; Conditions:</strong><br />
    <?= nl2br(htmlspecialchars($invoice['terms_conditions'], ENT_QUOTES)) ?>
  </div>
  <?php endif; ?>

  <?php if ($showSignature): ?>
  <div style="margin-top: 24px; text-align: right;">
    <div style="display: inline-block; text-align: center; border-top: 1px solid #CBD5E1; padding-top: 4px; min-width: 140px; font-size: 0.75rem; font-weight: 700; color: #475569;">
      Authorized Signatory
    </div>
  </div>
  <?php endif; ?>

</div>
