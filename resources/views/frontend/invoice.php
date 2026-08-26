<?php
/**
 * TechFix Public Customer Tax Invoice & Receipt View
 * 
 * @var array  $repair
 * @var array  $invoice
 * @var string $renderedHtml
 * @var string $csrfToken
 * @var string $pageTitle
 */
?>

<div style="background: #F1F5F9; min-height: 85vh; padding: 40px 15px;">
  
  <div style="max-width: 860px; margin: 0 auto;">
    
    <!-- Top Action Toolbar -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 14px;">
      <a href="<?= url('/repair/' . urlencode($repair['tracking_id'])) ?>" class="btn" style="background: #ffffff; border: 1px solid var(--border-color); color: var(--text-secondary); padding: 9px 16px; border-radius: var(--radius-sm); font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
        <i class="fas fa-arrow-left"></i> Back to Live Tracking
      </a>

      <div style="display: flex; gap: 10px;">
        <button onclick="window.print()" class="btn btn-primary" style="padding: 9px 18px; font-weight: 700; display: inline-flex; align-items: center; gap: 8px; cursor: pointer;">
          <i class="fas fa-print"></i> Print / Save Receipt (PDF)
        </button>
      </div>
    </div>

    <!-- Rendered Invoice -->
    <div class="customer-invoice-wrapper" style="box-shadow: 0 10px 25px rgba(0,0,0,0.06); border-radius: 12px; overflow: hidden;">
      <?= $renderedHtml ?>
    </div>

    <!-- Helpful Payment & Support Info -->
    <div style="margin-top: 24px; text-align: center; color: var(--text-muted); font-size: 0.85rem;">
      <p>Have questions about your bill? Contact our billing desk directly at <a href="tel:<?= site_phone() ?>" style="color: var(--primary-color); font-weight: bold;"><?= site_phone() ?></a> or email <a href="mailto:<?= site_email() ?>" style="color: var(--primary-color); font-weight: bold;"><?= site_email() ?></a>.</p>
    </div>

  </div>

</div>

<style>
@media print {
  body {
    background: #FFFFFF !important;
    padding: 0 !important;
  }
  header, footer, .btn, .page-header, nav, .site-header, .site-footer {
    display: none !important;
  }
  .customer-invoice-wrapper {
    box-shadow: none !important;
    border-radius: 0 !important;
  }
  .invoice-container {
    box-shadow: none !important;
    border: none !important;
    max-width: 100% !important;
    padding: 10px !important;
  }
}
</style>
