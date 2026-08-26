<?php
/**
 * TechFix Invoicing Engine — Template 3: 80mm Thermal POS Receipt
 * 
 * @var array $invoice
 * @var array $items
 * @var array $template
 * @var array $settings
 * @var bool  $isPrintMode
 */

$showQr = !empty($template['show_qr_code']) && !empty($invoice['payment_qr_data']);
?>
<div class="invoice-container invoice-thermal" style="font-family: 'Courier New', Courier, monospace; color: #000000; background: #FFFFFF; width: 320px; max-width: 100%; margin: 0 auto; padding: 16px 12px; box-sizing: border-box; font-size: 12px; line-height: 1.35; border: 1px dashed #000;">

  <!-- POS Header -->
  <div style="text-align: center; margin-bottom: 10px;">
    <div style="font-size: 16px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.5px;">*** <?= htmlspecialchars(site_name(), ENT_QUOTES) ?> ***</div>
    <div style="font-size: 11px; font-weight: bold;"><?= htmlspecialchars(site_tagline(), ENT_QUOTES) ?></div>
    <div style="font-size: 10px; margin-top: 2px;"><?= htmlspecialchars(site_address(), ENT_QUOTES) ?></div>
    <div style="font-size: 10px;">Ph: <?= htmlspecialchars(site_phone(), ENT_QUOTES) ?></div>
    <?php if (!empty($settings['billing_gst_number'])): ?>
    <div style="font-size: 10px; font-weight: bold;">GSTIN: <?= htmlspecialchars($settings['billing_gst_number'], ENT_QUOTES) ?></div>
    <?php endif; ?>
  </div>

  <div style="border-top: 1px dashed #000; margin: 6px 0;"></div>

  <!-- Receipt Details -->
  <div style="font-size: 11px;">
    <div><strong>RCPT NO :</strong> <?= htmlspecialchars($invoice['invoice_number'], ENT_QUOTES) ?></div>
    <div><strong>DATE    :</strong> <?= date('d/m/Y h:i A', strtotime($invoice['invoice_date'] . ' ' . date('H:i:s'))) ?></div>
    <div><strong>CLIENT  :</strong> <?= htmlspecialchars(substr($invoice['customer_name'], 0, 22), ENT_QUOTES) ?></div>
    <div><strong>PHONE   :</strong> <?= htmlspecialchars($invoice['customer_phone'], ENT_QUOTES) ?></div>
    <?php if (!empty($invoice['repair_tracking_id'])): ?>
    <div><strong>JOB ID  :</strong> <?= htmlspecialchars($invoice['repair_tracking_id'], ENT_QUOTES) ?></div>
    <div><strong>DEVICE  :</strong> <?= htmlspecialchars(substr(($invoice['device_brand'] ?? '') . ' ' . ($invoice['device_model'] ?? ''), 0, 22), ENT_QUOTES) ?></div>
    <?php endif; ?>
  </div>

  <div style="border-top: 1px dashed #000; margin: 6px 0;"></div>

  <!-- Items Table -->
  <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
    <thead>
      <tr style="border-bottom: 1px dashed #000; text-align: left;">
        <th style="padding: 2px 0;">ITEM</th>
        <th style="padding: 2px 0; text-align: center; width: 30px;">QTY</th>
        <th style="padding: 2px 0; text-align: right; width: 65px;">AMT(₹)</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($items as $item): ?>
      <tr>
        <td style="padding: 3px 0; word-break: break-word;">
          <div style="font-weight: bold;"><?= htmlspecialchars(substr($item['item_name'], 0, 24), ENT_QUOTES) ?></div>
          <?php if ((float)$item['quantity'] > 1): ?>
          <div style="font-size: 10px; color: #333;">@ ₹<?= number_format((float)$item['unit_price'], 2) ?></div>
          <?php endif; ?>
        </td>
        <td style="padding: 3px 0; text-align: center;"><?= (float)$item['quantity'] ?></td>
        <td style="padding: 3px 0; text-align: right; font-weight: bold;"><?= number_format((float)$item['total_price'], 2) ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <div style="border-top: 1px dashed #000; margin: 6px 0;"></div>

  <!-- Totals Summary -->
  <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
    <tr>
      <td style="padding: 2px 0;">SUBTOTAL:</td>
      <td style="padding: 2px 0; text-align: right; font-weight: bold;">₹<?= number_format((float)$invoice['subtotal'], 2) ?></td>
    </tr>
    <?php if ((float)$invoice['discount_amount'] > 0): ?>
    <tr>
      <td style="padding: 2px 0;">DISCOUNT:</td>
      <td style="padding: 2px 0; text-align: right;">-₹<?= number_format((float)$invoice['discount_amount'], 2) ?></td>
    </tr>
    <?php endif; ?>
    <?php if ((float)$invoice['tax_amount'] > 0): ?>
    <tr>
      <td style="padding: 2px 0;">GST (<?= (float)$invoice['tax_rate'] ?>%):</td>
      <td style="padding: 2px 0; text-align: right;">₹<?= number_format((float)$invoice['tax_amount'], 2) ?></td>
    </tr>
    <?php endif; ?>
    <tr style="border-top: 1px solid #000; border-bottom: 1px solid #000;">
      <td style="padding: 4px 0; font-size: 13px; font-weight: 900;">TOTAL:</td>
      <td style="padding: 4px 0; text-align: right; font-size: 14px; font-weight: 900;">₹<?= number_format((float)$invoice['total_amount'], 2) ?></td>
    </tr>
    <tr>
      <td style="padding: 2px 0;">PAID (<?= strtoupper($invoice['payment_method']) ?>):</td>
      <td style="padding: 2px 0; text-align: right; font-weight: bold;">₹<?= number_format((float)$invoice['paid_amount'], 2) ?></td>
    </tr>
    <tr>
      <td style="padding: 2px 0; font-weight: bold;">BALANCE DUE:</td>
      <td style="padding: 2px 0; text-align: right; font-weight: 900;">₹<?= number_format((float)$invoice['balance_due'], 2) ?></td>
    </tr>
  </table>

  <!-- UPI QR Code for instant checkout -->
  <?php if ($showQr && (float)$invoice['balance_due'] > 0): ?>
  <div style="text-align: center; margin: 10px 0; padding: 6px 0; border-top: 1px dashed #000;">
    <div style="font-size: 10px; font-weight: bold; margin-bottom: 4px;">SCAN &amp; PAY UPI</div>
    <img src="<?= htmlspecialchars($invoice['payment_qr_data'], ENT_QUOTES) ?>" alt="QR" style="width: 90px; height: 90px; border: 1px solid #000;" />
    <div style="font-size: 9px; margin-top: 2px; font-weight: bold;"><?= htmlspecialchars($settings['billing_upi_id'] ?? 'techfix@sbi', ENT_QUOTES) ?></div>
  </div>
  <?php endif; ?>

  <div style="border-top: 1px dashed #000; margin: 6px 0;"></div>

  <!-- Footer Policy -->
  <div style="text-align: center; font-size: 10px;">
    <div style="font-weight: bold;">90-DAY SERVICE WARRANTY</div>
    <div style="margin-top: 2px;">Keep this slip for warranty &amp; delivery.</div>
    <div style="margin-top: 4px; font-weight: 900;">*** THANK YOU - VISIT AGAIN ***</div>
    <div style="font-size: 9px; margin-top: 4px;"><?= htmlspecialchars(site_name(), ENT_QUOTES) ?> Online Portal</div>
  </div>

</div>
