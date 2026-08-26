<?php
/**
 * TechFix Invoicing Engine — Template 5: Executive Clean (Luxury Minimalist)
 * 
 * @var array $invoice
 * @var array $items
 * @var array $template
 * @var array $settings
 * @var bool  $isPrintMode
 */

$accentColor = $template['accent_color'] ?? '#475569';
$secondaryColor = $template['secondary_color'] ?? '#1E293B';
$fontFamily = $template['font_family'] ?? 'Inter, sans-serif';
$showWatermark = !empty($template['show_watermark']) && $invoice['status'] === 'paid';
$showQr = !empty($template['show_qr_code']) && !empty($invoice['payment_qr_data']) && ((string)($settings['billing_show_upi_qr'] ?? '1') === '1');
$showSignature = !empty($template['show_signature']);
$showBank = !empty($template['show_bank_details']) && ((string)($settings['billing_show_bank_details'] ?? '1') === '1');
?>
<div class="invoice-container invoice-executive" style="font-family: <?= htmlspecialchars($fontFamily, ENT_QUOTES) ?>; color: #1E293B; background: #FFFFFF; max-width: 860px; margin: 0 auto; padding: 45px; box-sizing: border-box; position: relative; border-radius: 8px; border: 1px solid #E2E8F0; box-shadow: 0 4px 18px rgba(0,0,0,0.04);">

  <!-- Watermark -->
  <?php if ($showWatermark): ?>
  <div style="position: absolute; top: 40%; left: 50%; transform: translate(-50%, -50%) rotate(-20deg); font-size: 5.5rem; font-weight: 900; color: rgba(71, 85, 105, 0.08); border: 6px solid rgba(71, 85, 105, 0.12); padding: 10px 40px; text-transform: uppercase; letter-spacing: 10px; pointer-events: none; user-select: none; z-index: 1;">
    <?= htmlspecialchars($template['watermark_text'] ?? 'ORIGINAL', ENT_QUOTES) ?>
  </div>
  <?php endif; ?>

  <!-- Top Minimalist Banner -->
  <div style="display: flex; justify-content: space-between; align-items: flex-end; padding-bottom: 24px; border-bottom: 1px solid #E2E8F0; margin-bottom: 30px;">
    <div>
      <div style="font-size: 1.6rem; font-weight: 900; color: <?= $secondaryColor ?>; letter-spacing: -0.5px;">
        <?= htmlspecialchars(site_name(), ENT_QUOTES) ?>
      </div>
      <div style="font-size: 0.85rem; color: #64748B; margin-top: 2px;">
        <?= htmlspecialchars(site_tagline(), ENT_QUOTES) ?>
      </div>
    </div>

    <div style="text-align: right;">
      <div style="font-size: 1.4rem; font-weight: 800; color: #0F172A; text-transform: uppercase; letter-spacing: 1px;">
        INVOICE
      </div>
      <div style="font-size: 0.95rem; font-weight: 700; color: #64748B; font-family: monospace;">
        #<?= htmlspecialchars($invoice['invoice_number'], ENT_QUOTES) ?>
      </div>
    </div>
  </div>

  <!-- 4-Column Metadata Summary -->
  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(170px, 1fr)); gap: 20px; margin-bottom: 35px; font-size: 0.84rem;">
    <div>
      <div style="font-size: 0.72rem; font-weight: 800; text-transform: uppercase; color: #94A3B8; letter-spacing: 0.6px; margin-bottom: 4px;">Issued To</div>
      <div style="font-weight: 800; color: #0F172A; font-size: 0.95rem;"><?= htmlspecialchars($invoice['customer_name'], ENT_QUOTES) ?></div>
      <div style="color: #475569;"><?= htmlspecialchars($invoice['customer_phone'], ENT_QUOTES) ?></div>
      <?php if (!empty($invoice['customer_city'])): ?>
      <div style="color: #64748B; font-size: 0.8rem;"><?= htmlspecialchars($invoice['customer_city'], ENT_QUOTES) ?></div>
      <?php endif; ?>
    </div>

    <div>
      <div style="font-size: 0.72rem; font-weight: 800; text-transform: uppercase; color: #94A3B8; letter-spacing: 0.6px; margin-bottom: 4px;">Dates</div>
      <div>Date: <strong><?= date('d M Y', strtotime($invoice['invoice_date'])) ?></strong></div>
      <?php if (!empty($invoice['due_date'])): ?>
      <div style="margin-top: 2px;">Due: <strong><?= date('d M Y', strtotime($invoice['due_date'])) ?></strong></div>
      <?php endif; ?>
    </div>

    <div>
      <div style="font-size: 0.72rem; font-weight: 800; text-transform: uppercase; color: #94A3B8; letter-spacing: 0.6px; margin-bottom: 4px;">Payment Status</div>
      <div style="font-weight: 800; text-transform: uppercase; color: <?= $invoice['status'] === 'paid' ? '#059669' : '#D97706' ?>;">
        <?= htmlspecialchars(\App\Models\Invoice::STATUSES[$invoice['status']] ?? $invoice['status'], ENT_QUOTES) ?>
      </div>
      <div style="color: #64748B; font-size: 0.8rem; margin-top: 2px;">Via <?= strtoupper($invoice['payment_method']) ?></div>
    </div>

    <div>
      <div style="font-size: 0.72rem; font-weight: 800; text-transform: uppercase; color: #94A3B8; letter-spacing: 0.6px; margin-bottom: 4px;">Center &amp; Workshop</div>
      <div style="color: #334155; font-size: 0.82rem;"><?= htmlspecialchars(site_phone(), ENT_QUOTES) ?></div>
      <div style="color: #64748B; font-size: 0.78rem;"><?= htmlspecialchars(site_email(), ENT_QUOTES) ?></div>
    </div>
  </div>

  <!-- Items Table -->
  <div style="margin-bottom: 30px;">
    <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
      <thead>
        <tr style="border-bottom: 2px solid #0F172A; text-align: left;">
          <th style="padding: 10px 0; font-weight: 800; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.5px;">Item &amp; Description</th>
          <th style="padding: 10px 0; text-align: center; width: 60px; font-weight: 800; text-transform: uppercase; font-size: 0.75rem;">Qty</th>
          <th style="padding: 10px 0; text-align: right; width: 120px; font-weight: 800; text-transform: uppercase; font-size: 0.75rem;">Rate</th>
          <th style="padding: 10px 0; text-align: right; width: 130px; font-weight: 800; text-transform: uppercase; font-size: 0.75rem;">Amount</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($items as $item): ?>
        <tr style="border-bottom: 1px solid #F1F5F9;">
          <td style="padding: 12px 0;">
            <div style="font-weight: 700; color: #0F172A;"><?= htmlspecialchars($item['item_name'], ENT_QUOTES) ?></div>
            <?php if (!empty($item['description'])): ?>
            <div style="font-size: 0.8rem; color: #64748B; margin-top: 2px;"><?= htmlspecialchars($item['description'], ENT_QUOTES) ?></div>
            <?php endif; ?>
          </td>
          <td style="padding: 12px 0; text-align: center; color: #475569;"><?= (float)$item['quantity'] ?></td>
          <td style="padding: 12px 0; text-align: right; font-family: monospace; color: #475569;">₹<?= number_format((float)$item['unit_price'], 2) ?></td>
          <td style="padding: 12px 0; text-align: right; font-family: monospace; font-weight: 700; color: #0F172A;">₹<?= number_format((float)$item['total_price'], 2) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- Bottom Calculation & Notes -->
  <div style="display: grid; grid-template-columns: 1fr 300px; gap: 30px; align-items: start;">
    <div>
      <?php if (!empty($invoice['notes'])): ?>
      <div style="font-size: 0.82rem; color: #64748B; margin-bottom: 14px;">
        <strong style="color: #334155;">Note:</strong> <?= nl2br(htmlspecialchars($invoice['notes'], ENT_QUOTES)) ?>
      </div>
      <?php endif; ?>

      <?php if ($showQr && (float)$invoice['balance_due'] > 0): ?>
      <div style="display: flex; align-items: center; gap: 12px; margin-top: 8px;">
        <img src="<?= htmlspecialchars($invoice['payment_qr_data'], ENT_QUOTES) ?>" alt="QR" style="width: 70px; height: 70px; border: 1px solid #E2E8F0; padding: 2px; border-radius: 4px;" />
        <div style="font-size: 0.78rem; color: #475569;">
          <div style="font-weight: 700; color: #0F172A;">Scan to Pay via UPI</div>
          <div style="font-family: monospace; font-size: 0.74rem;"><?= htmlspecialchars($settings['billing_upi_id'] ?? 'techfix@sbi', ENT_QUOTES) ?></div>
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
    </div>

    <div>
      <div style="display: flex; justify-content: space-between; font-size: 0.88rem; margin-bottom: 6px; color: #64748B;">
        <span>Subtotal:</span>
        <span style="font-family: monospace; color: #0F172A; font-weight: 600;">₹<?= number_format((float)$invoice['subtotal'], 2) ?></span>
      </div>
      <?php if ((float)$invoice['discount_amount'] > 0): ?>
      <div style="display: flex; justify-content: space-between; font-size: 0.88rem; margin-bottom: 6px; color: #059669;">
        <span>Discount:</span>
        <span style="font-family: monospace; font-weight: 600;">-₹<?= number_format((float)$invoice['discount_amount'], 2) ?></span>
      </div>
      <?php endif; ?>
      <?php if ((float)$invoice['tax_amount'] > 0): ?>
      <div style="display: flex; justify-content: space-between; font-size: 0.88rem; margin-bottom: 6px; color: #64748B;">
        <span>GST (<?= (float)$invoice['tax_rate'] ?>%):</span>
        <span style="font-family: monospace; color: #0F172A; font-weight: 600;">₹<?= number_format((float)$invoice['tax_amount'], 2) ?></span>
      </div>
      <?php endif; ?>
      <div style="border-top: 1px solid #0F172A; margin: 8px 0;"></div>
      <div style="display: flex; justify-content: space-between; font-size: 1.1rem; font-weight: 900; color: #0F172A; margin-bottom: 6px;">
        <span>Total:</span>
        <span style="font-family: monospace;">₹<?= number_format((float)$invoice['total_amount'], 2) ?></span>
      </div>
      <div style="display: flex; justify-content: space-between; font-size: 0.88rem; color: #059669; margin-bottom: 6px;">
        <span>Paid:</span>
        <span style="font-family: monospace; font-weight: 700;">₹<?= number_format((float)$invoice['paid_amount'], 2) ?></span>
      </div>
      <div style="display: flex; justify-content: space-between; font-size: 0.95rem; font-weight: 800; color: <?= (float)$invoice['balance_due'] > 0 ? '#DC2626' : '#059669' ?>;">
        <span>Balance Due:</span>
        <span style="font-family: monospace;">₹<?= number_format((float)$invoice['balance_due'], 2) ?></span>
      </div>
    </div>
  </div>

  <!-- Clean Footer -->
  <div style="margin-top: 35px; padding-top: 20px; border-top: 1px solid #F1F5F9; display: flex; justify-content: space-between; align-items: flex-end; font-size: 0.76rem; color: #94A3B8;">
    <div>
      Thank you for trusting <?= htmlspecialchars(site_name(), ENT_QUOTES) ?> with your device.
    </div>
    <?php if ($showSignature): ?>
    <div style="text-align: center; width: 160px;">
      <div style="border-bottom: 1px solid #CBD5E1; height: 30px;"></div>
      <div style="margin-top: 4px; font-weight: 700; color: #475569;">Authorized Signature</div>
    </div>
    <?php endif; ?>
  </div>

</div>
