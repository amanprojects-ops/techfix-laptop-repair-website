<?php
/**
 * TechFix Invoicing Engine — Template 4: TechFix Cyber Glow (High-Tech)
 * 
 * @var array $invoice
 * @var array $items
 * @var array $template
 * @var array $settings
 * @var bool  $isPrintMode
 */

$accentColor = $template['accent_color'] ?? '#06B6D4';
$secondaryColor = $template['secondary_color'] ?? '#0B132B';
$fontFamily = $template['font_family'] ?? 'Inter, sans-serif';
$showWatermark = !empty($template['show_watermark']) && $invoice['status'] === 'paid';
$showQr = !empty($template['show_qr_code']) && !empty($invoice['payment_qr_data']);
$showSignature = !empty($template['show_signature']);
?>
<div class="invoice-container invoice-techfix-neon" style="font-family: <?= htmlspecialchars($fontFamily, ENT_QUOTES) ?>; color: #0F172A; background: #FFFFFF; max-width: 860px; margin: 0 auto; padding: 0; box-sizing: border-box; position: relative; border-radius: 14px; overflow: hidden; box-shadow: 0 10px 30px rgba(11, 19, 43, 0.1);">

  <!-- Cyber Glow Dark Header -->
  <div style="background: linear-gradient(135deg, #0B132B 0%, #172554 100%); color: #FFFFFF; padding: 36px 40px; position: relative; border-bottom: 3px solid <?= $accentColor ?>;">
    
    <!-- Neon top highlight line -->
    <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #38BDF8, #06B6D4, #3B82F6);"></div>

    <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 20px; flex-wrap: wrap;">
      <div>
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
          <span style="background: <?= $accentColor ?>; color: #0B132B; padding: 4px 8px; border-radius: 6px; font-size: 0.72rem; font-weight: 900; letter-spacing: 1px;">LAB CERTIFIED</span>
          <span style="font-size: 0.78rem; color: #94A3B8; text-transform: uppercase; letter-spacing: 1px;">CHIP-LEVEL HARDWARE FACILITY</span>
        </div>
        <div style="font-size: 1.8rem; font-weight: 900; color: #FFFFFF; letter-spacing: -0.5px;">
          <?= htmlspecialchars(site_name(), ENT_QUOTES) ?> <span style="color: <?= $accentColor ?>;">DIAGNOSTICS</span>
        </div>
        <div style="color: #94A3B8; font-size: 0.85rem; margin-top: 4px;">
          <?= htmlspecialchars(site_address(), ENT_QUOTES) ?> | Ph: <?= htmlspecialchars(site_phone(), ENT_QUOTES) ?>
        </div>
      </div>

      <div style="text-align: right;">
        <div style="font-size: 0.75rem; font-weight: 800; color: <?= $accentColor ?>; letter-spacing: 1.5px; text-transform: uppercase;">TAX INVOICE &amp; WORK RECEIPT</div>
        <div style="font-size: 1.4rem; font-weight: 900; color: #FFFFFF; font-family: monospace; margin-top: 4px;">
          <?= htmlspecialchars($invoice['invoice_number'], ENT_QUOTES) ?>
        </div>
        <div style="margin-top: 8px; display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; border-radius: 9999px; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; background: rgba(6, 182, 212, 0.15); color: <?= $accentColor ?>; border: 1px solid <?= $accentColor ?>;">
          <i class="fas fa-check-shield"></i> <?= htmlspecialchars(\App\Models\Invoice::STATUSES[$invoice['status']] ?? $invoice['status'], ENT_QUOTES) ?>
        </div>
      </div>
    </div>
  </div>

  <div style="padding: 36px 40px;">

    <!-- Hardware Inspection & Customer Info Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 28px;">
      
      <!-- Customer Card -->
      <div style="background: #F8FAFC; border-left: 4px solid #0B132B; padding: 16px 20px; border-radius: 6px;">
        <div style="font-size: 0.72rem; font-weight: 800; text-transform: uppercase; color: #64748B; letter-spacing: 0.5px;">Client Information</div>
        <div style="font-size: 1.05rem; font-weight: 800; color: #0F172A; margin-top: 4px;"><?= htmlspecialchars($invoice['customer_name'], ENT_QUOTES) ?></div>
        <div style="font-size: 0.85rem; color: #475569; margin-top: 2px;">Phone: <strong><?= htmlspecialchars($invoice['customer_phone'], ENT_QUOTES) ?></strong></div>
        <?php if (!empty($invoice['customer_email'])): ?>
        <div style="font-size: 0.8rem; color: #64748B;"><?= htmlspecialchars($invoice['customer_email'], ENT_QUOTES) ?></div>
        <?php endif; ?>
      </div>

      <!-- Device & Diagnosis Card -->
      <div style="background: #F8FAFC; border-left: 4px solid <?= $accentColor ?>; padding: 16px 20px; border-radius: 6px;">
        <div style="font-size: 0.72rem; font-weight: 800; text-transform: uppercase; color: #64748B; letter-spacing: 0.5px;">Hardware Machine Profile</div>
        <div style="font-size: 1.05rem; font-weight: 800; color: #0F172A; margin-top: 4px;">
          <?= htmlspecialchars(($invoice['device_brand'] ?? 'Laptop') . ' ' . ($invoice['device_model'] ?? ''), ENT_QUOTES) ?>
        </div>
        <?php if (!empty($invoice['repair_tracking_id'])): ?>
        <div style="font-size: 0.82rem; color: <?= $accentColor ?>; font-weight: 700; font-family: monospace; margin-top: 2px;">
          Ticket: <?= htmlspecialchars($invoice['repair_tracking_id'], ENT_QUOTES) ?>
        </div>
        <?php endif; ?>
      </div>

      <!-- Billing Schedule -->
      <div style="background: #F8FAFC; border-left: 4px solid #3B82F6; padding: 16px 20px; border-radius: 6px;">
        <div style="font-size: 0.72rem; font-weight: 800; text-transform: uppercase; color: #64748B; letter-spacing: 0.5px;">Billing Timeline</div>
        <div style="font-size: 0.88rem; color: #334155; margin-top: 4px;">Date: <strong><?= date('d M Y', strtotime($invoice['invoice_date'])) ?></strong></div>
        <?php if (!empty($invoice['due_date'])): ?>
        <div style="font-size: 0.88rem; color: #334155;">Due: <strong><?= date('d M Y', strtotime($invoice['due_date'])) ?></strong></div>
        <?php endif; ?>
        <div style="font-size: 0.82rem; color: #64748B; margin-top: 2px;">Pay Mode: <strong style="text-transform: uppercase;"><?= htmlspecialchars($invoice['payment_method'], ENT_QUOTES) ?></strong></div>
      </div>
    </div>

    <!-- Line Items Table -->
    <div style="margin-bottom: 28px; overflow-x: auto;">
      <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
        <thead>
          <tr style="background: #0B132B; color: #FFFFFF;">
            <th style="padding: 12px 14px; text-align: center; width: 40px; border-top-left-radius: 6px;">#</th>
            <th style="padding: 12px 14px; text-align: left;">Technical Service / Component Replaced</th>
            <th style="padding: 12px 14px; text-align: center; width: 80px;">Category</th>
            <th style="padding: 12px 14px; text-align: center; width: 60px;">Qty</th>
            <th style="padding: 12px 14px; text-align: right; width: 110px;">Unit (₹)</th>
            <th style="padding: 12px 14px; text-align: right; width: 120px; border-top-right-radius: 6px;">Total (₹)</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($items as $i => $item): ?>
          <tr style="border-bottom: 1px solid #E2E8F0; <?= $i % 2 === 1 ? 'background: #F8FAFC;' : '' ?>">
            <td style="padding: 12px 14px; text-align: center; color: #64748B; font-weight: bold;"><?= $i + 1 ?></td>
            <td style="padding: 12px 14px;">
              <div style="font-weight: 700; color: #0F172A;"><?= htmlspecialchars($item['item_name'], ENT_QUOTES) ?></div>
              <?php if (!empty($item['description'])): ?>
              <div style="font-size: 0.78rem; color: #64748B; margin-top: 2px;"><?= htmlspecialchars($item['description'], ENT_QUOTES) ?></div>
              <?php endif; ?>
            </td>
            <td style="padding: 12px 14px; text-align: center;">
              <span style="background: rgba(6, 182, 212, 0.1); color: #0891B2; font-size: 0.72rem; padding: 3px 8px; border-radius: 4px; font-weight: 800; text-transform: uppercase;">
                <?= htmlspecialchars($item['item_type'] ?? 'service', ENT_QUOTES) ?>
              </span>
            </td>
            <td style="padding: 12px 14px; text-align: center; font-weight: 600;"><?= (float)$item['quantity'] ?></td>
            <td style="padding: 12px 14px; text-align: right; font-family: monospace;">₹<?= number_format((float)$item['unit_price'], 2) ?></td>
            <td style="padding: 12px 14px; text-align: right; font-weight: 800; color: #0F172A; font-family: monospace;">₹<?= number_format((float)$item['total_price'], 2) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Bottom Financial Summary & Payment Box -->
    <div style="display: grid; grid-template-columns: 1fr 340px; gap: 24px; align-items: start; margin-bottom: 24px;">
      
      <!-- UPI QR & Guarantee Badge -->
      <div style="display: flex; flex-direction: column; gap: 14px;">
        <?php if ($showQr && (float)$invoice['balance_due'] > 0): ?>
        <div style="display: flex; align-items: center; gap: 16px; background: #ECFEFF; border: 1px solid #A5F3FC; padding: 14px 18px; border-radius: 8px;">
          <img src="<?= htmlspecialchars($invoice['payment_qr_data'], ENT_QUOTES) ?>" alt="UPI QR" style="width: 80px; height: 80px; border-radius: 6px; background: #fff; padding: 4px; border: 1px solid #67E8F9;" />
          <div>
            <div style="font-size: 0.78rem; font-weight: 900; color: #0E7490; text-transform: uppercase;">Direct UPI Payment Gateway</div>
            <div style="font-size: 0.75rem; color: #0891B2; margin-top: 2px;">Instant zero-fee payment via GPay / PhonePe</div>
            <div style="font-size: 0.82rem; font-weight: 700; font-family: monospace; color: #0B132B; margin-top: 3px;"><?= htmlspecialchars($settings['billing_upi_id'] ?? 'techfix@sbi', ENT_QUOTES) ?></div>
          </div>
        </div>
        <?php endif; ?>

        <div style="border: 1px dashed #CBD5E1; padding: 12px 16px; border-radius: 8px; font-size: 0.8rem; color: #475569;">
          <div style="font-weight: 800; color: #0F172A; text-transform: uppercase; margin-bottom: 3px;">
            <i class="fas fa-shield-alt" style="color: <?= $accentColor ?>; margin-right: 4px;"></i> 90-Day Hardware Guarantee
          </div>
          <div>All replaced hardware parts are tagged with tamper-proof security holographic seals and covered under 90-day comprehensive workshop warranty.</div>
        </div>
      </div>

      <!-- Financial Calculation -->
      <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 8px; padding: 18px 20px;">
        <div style="display: flex; justify-content: space-between; font-size: 0.88rem; margin-bottom: 6px; color: #475569;">
          <span>Subtotal:</span>
          <span style="font-weight: 700; font-family: monospace;">₹<?= number_format((float)$invoice['subtotal'], 2) ?></span>
        </div>
        <?php if ((float)$invoice['discount_amount'] > 0): ?>
        <div style="display: flex; justify-content: space-between; font-size: 0.88rem; margin-bottom: 6px; color: #059669;">
          <span>Discount:</span>
          <span style="font-weight: 700; font-family: monospace;">-₹<?= number_format((float)$invoice['discount_amount'], 2) ?></span>
        </div>
        <?php endif; ?>
        <?php if ((float)$invoice['tax_amount'] > 0): ?>
        <div style="display: flex; justify-content: space-between; font-size: 0.88rem; margin-bottom: 6px; color: #475569;">
          <span>GST (<?= (float)$invoice['tax_rate'] ?>%):</span>
          <span style="font-weight: 700; font-family: monospace;">₹<?= number_format((float)$invoice['tax_amount'], 2) ?></span>
        </div>
        <?php endif; ?>
        <div style="height: 1px; background: #CBD5E1; margin: 8px 0;"></div>
        <div style="display: flex; justify-content: space-between; font-size: 1.15rem; font-weight: 900; color: #0B132B; margin-bottom: 8px;">
          <span>Total:</span>
          <span style="font-family: monospace;">₹<?= number_format((float)$invoice['total_amount'], 2) ?></span>
        </div>
        <div style="display: flex; justify-content: space-between; font-size: 0.88rem; color: #059669; margin-bottom: 6px;">
          <span>Amount Paid:</span>
          <span style="font-weight: bold; font-family: monospace;">₹<?= number_format((float)$invoice['paid_amount'], 2) ?></span>
        </div>
        <div style="display: flex; justify-content: space-between; font-size: 1rem; font-weight: 900; background: <?= (float)$invoice['balance_due'] > 0 ? '#FEF2F2' : '#ECFDF5' ?>; color: <?= (float)$invoice['balance_due'] > 0 ? '#DC2626' : '#059669' ?>; padding: 6px 10px; border-radius: 6px;">
          <span>Balance:</span>
          <span style="font-family: monospace;">₹<?= number_format((float)$invoice['balance_due'], 2) ?></span>
        </div>
      </div>
    </div>

    <!-- Signatory Footer -->
    <?php if ($showSignature): ?>
    <div style="display: flex; justify-content: flex-end; margin-top: 20px; border-top: 1px solid #E2E8F0; padding-top: 16px;">
      <div style="text-align: center; width: 200px;">
        <div style="height: 35px; border-bottom: 1px solid #0B132B;"></div>
        <div style="font-size: 0.78rem; font-weight: 800; text-transform: uppercase; margin-top: 4px;"><?= htmlspecialchars(site_name(), ENT_QUOTES) ?></div>
        <div style="font-size: 0.72rem; color: #64748B;">Lead Hardware Engineer</div>
      </div>
    </div>
    <?php endif; ?>

  </div>

</div>
