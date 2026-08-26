<?php
/**
 * TechFix Invoices Directory & Billing Dashboard
 * 
 * @var array  $invoices
 * @var int    $total
 * @var int    $pages
 * @var int    $page
 * @var string|null $status
 * @var string|null $search
 * @var array  $stats
 * @var array  $templates
 * @var string $csrfToken
 * @var array  $user
 * @var string|null $flashSuccess
 * @var string|null $flashError
 */

$statusLabels = \App\Models\Invoice::STATUSES;
$statusColors = \App\Models\Invoice::STATUS_COLORS;
?>

<div class="page-header" style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
  <div>
    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
      <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--text-primary); margin: 0;">
        <i class="fas fa-file-invoice-dollar" style="color: var(--primary-color); margin-right: 8px;"></i>Billing &amp; Tax Invoices
      </h1>
      <span class="badge" style="background: rgba(37, 99, 235, 0.1); color: var(--primary-color); padding: 4px 10px; border-radius: 9999px; font-weight: 700; font-size: 0.78rem;">
        <?= number_format($total) ?> Total Invoices
      </span>
    </div>
    <p style="color: var(--text-muted); font-size: 0.92rem; margin: 0;">
      Manage customer billing, GST tax invoices, payments collection, and multi-template printing.
    </p>
  </div>

  <div style="display: flex; gap: 10px; flex-wrap: wrap;">
    <a href="<?= url('/admin/settings?tab=billing') ?>" class="btn" style="background: #ffffff; border: 1px solid var(--border-color); color: var(--text-secondary); padding: 9px 16px; border-radius: var(--radius-sm); font-weight: 600; display: inline-flex; align-items: center; gap: 6px; text-decoration: none;">
      <i class="fas fa-palette"></i> Template Designer
    </a>
    <a href="<?= url('/admin/invoices/create') ?>" class="btn btn-primary" style="padding: 9px 18px; font-weight: 700; display: inline-flex; align-items: center; gap: 8px; text-decoration: none;">
      <i class="fas fa-plus-circle"></i> Create New Invoice
    </a>
  </div>
</div>

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

<!-- Metric KPI Summary Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 18px; margin-bottom: 24px;">
  
  <div style="background: var(--bg-card); border: 1px solid var(--border-color); padding: 18px 20px; border-radius: var(--radius-md); box-shadow: var(--shadow-xs); display: flex; justify-content: space-between; align-items: center;">
    <div>
      <div style="font-size: 0.76rem; font-weight: 800; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.5px;">Total Billed</div>
      <div style="font-size: 1.5rem; font-weight: 900; color: var(--text-primary); margin-top: 4px; font-family: monospace;">₹<?= number_format((float)($stats['total_invoiced'] ?? 0), 2) ?></div>
      <div style="font-size: 0.78rem; color: var(--text-muted); margin-top: 2px;"><?= $stats['total_count'] ?? 0 ?> Invoices Issued</div>
    </div>
    <div style="width: 44px; height: 44px; border-radius: 10px; background: rgba(37, 99, 235, 0.1); color: var(--primary-color); display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
      <i class="fas fa-receipt"></i>
    </div>
  </div>

  <div style="background: var(--bg-card); border: 1px solid var(--border-color); padding: 18px 20px; border-radius: var(--radius-md); box-shadow: var(--shadow-xs); display: flex; justify-content: space-between; align-items: center;">
    <div>
      <div style="font-size: 0.76rem; font-weight: 800; text-transform: uppercase; color: #059669; letter-spacing: 0.5px;">Payments Collected</div>
      <div style="font-size: 1.5rem; font-weight: 900; color: #059669; margin-top: 4px; font-family: monospace;">₹<?= number_format((float)($stats['total_collected'] ?? 0), 2) ?></div>
      <div style="font-size: 0.78rem; color: #059669; margin-top: 2px;"><?= $stats['paid_count'] ?? 0 ?> Fully Paid</div>
    </div>
    <div style="width: 44px; height: 44px; border-radius: 10px; background: rgba(16, 185, 129, 0.1); color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
      <i class="fas fa-check-double"></i>
    </div>
  </div>

  <div style="background: var(--bg-card); border: 1px solid var(--border-color); padding: 18px 20px; border-radius: var(--radius-md); box-shadow: var(--shadow-xs); display: flex; justify-content: space-between; align-items: center;">
    <div>
      <div style="font-size: 0.76rem; font-weight: 800; text-transform: uppercase; color: #dc2626; letter-spacing: 0.5px;">Pending Dues</div>
      <div style="font-size: 1.5rem; font-weight: 900; color: #dc2626; margin-top: 4px; font-family: monospace;">₹<?= number_format((float)($stats['total_due'] ?? 0), 2) ?></div>
      <div style="font-size: 0.78rem; color: #dc2626; margin-top: 2px;"><?= ($stats['issued_count'] ?? 0) + ($stats['partial_count'] ?? 0) ?> Awaiting Payment</div>
    </div>
    <div style="width: 44px; height: 44px; border-radius: 10px; background: rgba(239, 68, 68, 0.1); color: #ef4444; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
      <i class="fas fa-clock"></i>
    </div>
  </div>

</div>

<!-- Filters & Search Toolbar -->
<div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 16px 20px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px;">
  
  <!-- Status Filter Pills -->
  <div style="display: flex; gap: 8px; flex-wrap: wrap;">
    <a href="<?= url('/admin/invoices' . ($search ? '?search=' . urlencode($search) : '')) ?>" class="filter-pill <?= empty($status) ? 'active' : '' ?>">
      All (<?= $stats['total_count'] ?? 0 ?>)
    </a>
    <a href="<?= url('/admin/invoices?status=issued' . ($search ? '&search=' . urlencode($search) : '')) ?>" class="filter-pill <?= $status === 'issued' ? 'active' : '' ?>">
      Issued (<?= $stats['issued_count'] ?? 0 ?>)
    </a>
    <a href="<?= url('/admin/invoices?status=partially_paid' . ($search ? '&search=' . urlencode($search) : '')) ?>" class="filter-pill <?= $status === 'partially_paid' ? 'active' : '' ?>">
      Partial (<?= $stats['partial_count'] ?? 0 ?>)
    </a>
    <a href="<?= url('/admin/invoices?status=paid' . ($search ? '&search=' . urlencode($search) : '')) ?>" class="filter-pill <?= $status === 'paid' ? 'active' : '' ?>">
      Paid (<?= $stats['paid_count'] ?? 0 ?>)
    </a>
  </div>

  <!-- Search Input Form -->
  <form method="GET" action="<?= url('/admin/invoices') ?>" style="display: flex; gap: 8px; align-items: center;">
    <?php if ($status): ?>
    <input type="hidden" name="status" value="<?= htmlspecialchars($status, ENT_QUOTES) ?>" />
    <?php endif; ?>
    <div style="position: relative;">
      <i class="fas fa-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.85rem;"></i>
      <input type="text" name="search" value="<?= htmlspecialchars($search ?? '', ENT_QUOTES) ?>" placeholder="Invoice #, client, phone, repair..." class="form-control" style="padding-left: 34px; width: 260px; font-size: 0.88rem; height: 38px;" />
    </div>
    <button type="submit" class="btn btn-primary" style="padding: 8px 14px; font-size: 0.88rem;">Filter</button>
    <?php if ($search || $status): ?>
    <a href="<?= url('/admin/invoices') ?>" class="btn" style="background: #f1f5f9; border: 1px solid var(--border-color); color: #475569; padding: 8px 12px; font-size: 0.85rem; text-decoration: none;" title="Reset Filters">
      <i class="fas fa-times"></i>
    </a>
    <?php endif; ?>
  </form>

</div>

<!-- Invoices Table -->
<div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md); box-shadow: var(--card-shadow); overflow: hidden;">
  
  <?php if (empty($invoices)): ?>
  <div style="text-align: center; padding: 60px 20px;">
    <div style="width: 60px; height: 60px; border-radius: 50%; background: rgba(37,99,235,0.08); color: var(--primary-color); display: flex; align-items: center; justify-content: center; font-size: 1.8rem; margin: 0 auto 16px auto;">
      <i class="fas fa-file-invoice"></i>
    </div>
    <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--text-primary); margin: 0;">No Invoices Found</h3>
    <p style="color: var(--text-muted); font-size: 0.88rem; margin: 6px 0 20px 0;">No billing records matched your query. Create your first invoice or import from a repair job.</p>
    <a href="<?= url('/admin/invoices/create') ?>" class="btn btn-primary">
      <i class="fas fa-plus-circle" style="margin-right: 6px;"></i>Create New Invoice
    </a>
  </div>
  <?php else: ?>

  <div style="overflow-x: auto;">
    <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem;">
      <thead>
        <tr style="background: #f8fafc; border-bottom: 1px solid var(--border-color); color: var(--text-secondary); font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.6px;">
          <th style="padding: 14px 18px;">Invoice #</th>
          <th style="padding: 14px 18px;">Customer</th>
          <th style="padding: 14px 18px;">Repair Ticket</th>
          <th style="padding: 14px 18px;">Date</th>
          <th style="padding: 14px 18px; text-align: right;">Total Amount</th>
          <th style="padding: 14px 18px; text-align: right;">Paid / Balance</th>
          <th style="padding: 14px 18px; text-align: center;">Status</th>
          <th style="padding: 14px 18px; text-align: right;">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($invoices as $inv): 
          $sColor = $statusColors[$inv['status']] ?? '#64748B';
          $balance = (float)$inv['balance_due'];
        ?>
        <tr style="border-bottom: 1px solid var(--border-color); transition: background 0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
          
          <td style="padding: 14px 18px;">
            <a href="<?= url('/admin/invoices/' . $inv['id']) ?>" style="font-weight: 800; color: var(--primary-color); font-family: monospace; font-size: 0.95rem; text-decoration: none;">
              #<?= htmlspecialchars($inv['invoice_number'], ENT_QUOTES) ?>
            </a>
            <div style="font-size: 0.74rem; color: var(--text-muted); margin-top: 2px;">
              Template: <?= htmlspecialchars(ucfirst(str_replace('_', ' ', $inv['template_key'])), ENT_QUOTES) ?>
            </div>
          </td>

          <td style="padding: 14px 18px;">
            <div style="font-weight: 700; color: var(--text-primary);"><?= htmlspecialchars($inv['customer_name'], ENT_QUOTES) ?></div>
            <div style="font-size: 0.78rem; color: var(--text-muted);"><?= htmlspecialchars($inv['customer_phone'], ENT_QUOTES) ?></div>
          </td>

          <td style="padding: 14px 18px;">
            <?php if (!empty($inv['repair_tracking_id'])): ?>
            <a href="<?= url('/admin/repairs/' . $inv['repair_job_id']) ?>" style="display: inline-flex; align-items: center; gap: 4px; font-weight: 700; color: var(--primary-color); font-size: 0.82rem; font-family: monospace; text-decoration: none;">
              <i class="fas fa-laptop-medical"></i> <?= htmlspecialchars($inv['repair_tracking_id'], ENT_QUOTES) ?>
            </a>
            <div style="font-size: 0.76rem; color: var(--text-muted); margin-top: 2px;">
              <?= htmlspecialchars(($inv['device_brand'] ?? '') . ' ' . ($inv['device_model'] ?? ''), ENT_QUOTES) ?>
            </div>
            <?php else: ?>
            <span style="color: var(--text-muted); font-size: 0.8rem;">Direct Invoice</span>
            <?php endif; ?>
          </td>

          <td style="padding: 14px 18px; color: var(--text-secondary); font-size: 0.85rem;">
            <div><?= date('d M Y', strtotime($inv['invoice_date'])) ?></div>
            <?php if (!empty($inv['due_date'])): ?>
            <div style="font-size: 0.75rem; color: var(--text-muted);">Due: <?= date('d M Y', strtotime($inv['due_date'])) ?></div>
            <?php endif; ?>
          </td>

          <td style="padding: 14px 18px; text-align: right; font-weight: 800; font-family: monospace; color: var(--text-primary); font-size: 0.95rem;">
            ₹<?= number_format((float)$inv['total_amount'], 2) ?>
          </td>

          <td style="padding: 14px 18px; text-align: right;">
            <div style="font-weight: 700; color: #10b981; font-family: monospace; font-size: 0.85rem;">
              ₹<?= number_format((float)$inv['paid_amount'], 2) ?>
            </div>
            <?php if ($balance > 0): ?>
            <div style="font-weight: 800; color: #dc2626; font-family: monospace; font-size: 0.85rem; margin-top: 2px;">
              Due: ₹<?= number_format($balance, 2) ?>
            </div>
            <?php else: ?>
            <div style="font-size: 0.74rem; color: #059669; font-weight: 700;">Cleared</div>
            <?php endif; ?>
          </td>

          <td style="padding: 14px 18px; text-align: center;">
            <span style="display: inline-flex; align-items: center; gap: 5px; padding: 3px 10px; border-radius: 9999px; font-size: 0.72rem; font-weight: 800; text-transform: uppercase; background: <?= $inv['status'] === 'paid' ? '#ECFDF5' : ($inv['status'] === 'partially_paid' ? '#FFFBEB' : '#EFF6FF') ?>; color: <?= $sColor ?>; border: 1px solid currentColor;">
              <span style="width: 6px; height: 6px; border-radius: 50%; background: currentColor;"></span>
              <?= htmlspecialchars($statusLabels[$inv['status']] ?? ucfirst($inv['status']), ENT_QUOTES) ?>
            </span>
          </td>

          <td style="padding: 14px 18px; text-align: right;">
            <div style="display: inline-flex; gap: 6px; align-items: center;">
              <a href="<?= url('/admin/invoices/' . $inv['id']) ?>" class="btn btn-sm" style="background: rgba(37,99,235,0.08); color: var(--primary-color); border: 1px solid rgba(37,99,235,0.2); padding: 5px 10px;" title="View Invoice">
                <i class="fas fa-eye"></i>
              </a>
              <a href="<?= url('/admin/invoices/' . $inv['id'] . '/print') ?>" target="_blank" class="btn btn-sm" style="background: #f8fafc; border: 1px solid var(--border-color); color: #475569; padding: 5px 10px;" title="Print / PDF">
                <i class="fas fa-print"></i>
              </a>
              <a href="<?= url('/admin/invoices/' . $inv['id'] . '/edit') ?>" class="btn btn-sm" style="background: #f8fafc; border: 1px solid var(--border-color); color: #475569; padding: 5px 10px;" title="Edit Invoice">
                <i class="fas fa-edit"></i>
              </a>
            </div>
          </td>

        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  <?php if ($pages > 1): ?>
  <div style="padding: 16px 20px; border-top: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; font-size: 0.85rem; color: var(--text-muted);">
    <div>Showing Page <strong><?= $page ?></strong> of <strong><?= $pages ?></strong> (<?= number_format($total) ?> total)</div>
    <div style="display: flex; gap: 6px;">
      <?php for ($p = 1; $p <= $pages; $p++): ?>
      <a href="<?= url('/admin/invoices?page=' . $p . ($status ? '&status=' . urlencode($status) : '') . ($search ? '&search=' . urlencode($search) : '')) ?>" class="btn btn-sm" style="padding: 4px 10px; font-weight: <?= $p === $page ? '800' : '500' ?>; background: <?= $p === $page ? 'var(--primary-color)' : '#ffffff' ?>; color: <?= $p === $page ? '#ffffff' : 'var(--text-primary)' ?>; border: 1px solid var(--border-color); text-decoration: none;">
        <?= $p ?>
      </a>
      <?php endfor; ?>
    </div>
  </div>
  <?php endif; ?>

  <?php endif; ?>

</div>

<style>
.filter-pill {
  padding: 6px 14px;
  border-radius: 9999px;
  background: #f1f5f9;
  color: var(--text-secondary);
  font-size: 0.82rem;
  font-weight: 600;
  text-decoration: none;
  border: 1px solid transparent;
  transition: all 0.2s;
}
.filter-pill:hover {
  background: #e2e8f0;
  color: var(--text-primary);
}
.filter-pill.active {
  background: var(--primary-color);
  color: #ffffff;
  font-weight: 700;
}
</style>
