<?php
/**
 * TechFix Edit Invoice View
 * 
 * @var array $invoice
 * @var array $customers
 * @var array $templates
 * @var string $csrfToken
 * @var array $user
 */

$items = $invoice['items'] ?? [];
?>

<div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
  <div>
    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
      <a href="<?= url('/admin/invoices/' . $invoice['id']) ?>" style="color: var(--text-muted); font-size: 1.1rem; text-decoration: none;" title="Back to Invoice">
        <i class="fas fa-arrow-left"></i>
      </a>
      <h1 style="font-size: 1.6rem; font-weight: 800; color: var(--text-primary); margin: 0;">
        Edit Invoice #<?= htmlspecialchars($invoice['invoice_number'], ENT_QUOTES) ?>
      </h1>
      <span class="badge" style="background: rgba(37, 99, 235, 0.1); color: var(--primary-color); padding: 4px 10px; border-radius: 9999px; font-weight: 700; font-size: 0.78rem;">
        <?= htmlspecialchars(ucfirst($invoice['status']), ENT_QUOTES) ?>
      </span>
    </div>
    <p style="color: var(--text-muted); font-size: 0.9rem; margin: 0;">
      Modify invoice line items, pricing, template selection, and financial parameters.
    </p>
  </div>

  <div>
    <a href="<?= url('/admin/invoices/' . $invoice['id']) ?>" class="btn btn-secondary">
      <i class="fas fa-times" style="margin-right: 6px;"></i>Cancel
    </a>
  </div>
</div>

<form method="POST" action="<?= url('/admin/invoices/' . $invoice['id'] . '/update') ?>" id="invoice-edit-form">
  <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES) ?>" />

  <div style="display: grid; grid-template-columns: 1fr 340px; gap: 24px; align-items: start;">
    
    <!-- Left Column: Primary Details & Line Items Table -->
    <div style="display: flex; flex-direction: column; gap: 24px;">

      <!-- Card 1: Customer Details -->
      <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 22px; box-shadow: var(--shadow-xs);">
        <div style="font-size: 0.82rem; font-weight: 800; text-transform: uppercase; color: var(--primary-color); letter-spacing: 0.6px; margin-bottom: 14px;">
          <i class="fas fa-user"></i> Customer &amp; Machine
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
          <div>
            <div style="font-weight: 800; font-size: 1.1rem; color: var(--text-primary);"><?= htmlspecialchars($invoice['customer_name'], ENT_QUOTES) ?></div>
            <div style="color: var(--text-secondary); font-size: 0.9rem; margin-top: 2px;">Phone: <?= htmlspecialchars($invoice['customer_phone'], ENT_QUOTES) ?></div>
            <?php if (!empty($invoice['customer_email'])): ?>
            <div style="color: var(--text-muted); font-size: 0.82rem;"><?= htmlspecialchars($invoice['customer_email'], ENT_QUOTES) ?></div>
            <?php endif; ?>
          </div>
          <div>
            <?php if (!empty($invoice['repair_tracking_id'])): ?>
            <div style="font-size: 0.85rem; font-weight: 700; color: var(--primary-color); font-family: monospace;">Ticket: <?= htmlspecialchars($invoice['repair_tracking_id'], ENT_QUOTES) ?></div>
            <div style="font-size: 0.85rem; color: var(--text-primary); font-weight: 600;"><?= htmlspecialchars(($invoice['device_brand'] ?? '') . ' ' . ($invoice['device_model'] ?? ''), ENT_QUOTES) ?></div>
            <?php else: ?>
            <div style="color: var(--text-muted); font-size: 0.88rem;">Standalone Direct Invoice</div>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Card 2: Line Items -->
      <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 22px; box-shadow: var(--shadow-xs);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
          <div style="font-size: 0.82rem; font-weight: 800; text-transform: uppercase; color: var(--primary-color); letter-spacing: 0.6px;">
            <i class="fas fa-list-ol"></i> Line Items
          </div>
          <button type="button" onclick="addLineItemRow()" class="btn btn-sm btn-primary" style="font-weight: 700; font-size: 0.82rem;">
            <i class="fas fa-plus" style="margin-right: 4px;"></i>Add Row
          </button>
        </div>

        <div style="overflow-x: auto;">
          <table id="items-table" style="width: 100%; border-collapse: collapse; font-size: 0.88rem;">
            <thead>
              <tr style="background: #f8fafc; border-bottom: 1px solid var(--border-color); text-align: left; color: var(--text-secondary); font-size: 0.76rem; text-transform: uppercase;">
                <th style="padding: 10px 12px; width: 140px;">Type</th>
                <th style="padding: 10px 12px;">Item Name &amp; Description <span style="color: #ef4444;">*</span></th>
                <th style="padding: 10px 12px; width: 80px; text-align: center;">Qty</th>
                <th style="padding: 10px 12px; width: 120px; text-align: right;">Unit Rate (₹)</th>
                <th style="padding: 10px 12px; width: 120px; text-align: right;">Total (₹)</th>
                <th style="padding: 10px 8px; width: 40px; text-align: center;"></th>
              </tr>
            </thead>
            <tbody id="items-tbody">
              <?php foreach ($items as $idx => $it): ?>
              <tr class="item-row" style="border-bottom: 1px solid var(--border-color);">
                <td style="padding: 10px 12px; vertical-align: top;">
                  <select name="items[<?= $idx ?>][item_type]" class="form-control" style="font-size: 0.84rem;">
                    <option value="service" <?= ($it['item_type'] ?? '') === 'service' ? 'selected' : '' ?>>Service</option>
                    <option value="part" <?= ($it['item_type'] ?? '') === 'part' ? 'selected' : '' ?>>Part</option>
                    <option value="labor" <?= ($it['item_type'] ?? '') === 'labor' ? 'selected' : '' ?>>Labor</option>
                    <option value="diagnostic" <?= ($it['item_type'] ?? '') === 'diagnostic' ? 'selected' : '' ?>>Diagnostic</option>
                    <option value="custom" <?= ($it['item_type'] ?? '') === 'custom' ? 'selected' : '' ?>>Custom</option>
                  </select>
                </td>
                <td style="padding: 10px 12px; vertical-align: top;">
                  <input type="text" name="items[<?= $idx ?>][item_name]" class="form-control item-name-input" value="<?= htmlspecialchars($it['item_name'], ENT_QUOTES) ?>" required style="font-weight: 600;" />
                  <input type="text" name="items[<?= $idx ?>][description]" class="form-control" placeholder="Optional notes" value="<?= htmlspecialchars($it['description'] ?? '', ENT_QUOTES) ?>" style="font-size: 0.8rem; margin-top: 4px;" />
                </td>
                <td style="padding: 10px 12px; vertical-align: top;">
                  <input type="number" name="items[<?= $idx ?>][quantity]" class="form-control item-qty-input" value="<?= (float)$it['quantity'] ?>" min="0.01" step="any" required style="text-align: center; font-weight: 600;" oninput="recalculateInvoiceTotals()" />
                </td>
                <td style="padding: 10px 12px; vertical-align: top;">
                  <input type="number" name="items[<?= $idx ?>][unit_price]" class="form-control item-price-input" value="<?= (float)$it['unit_price'] ?>" min="0" step="0.01" required style="text-align: right; font-weight: 700; font-family: monospace;" oninput="recalculateInvoiceTotals()" />
                </td>
                <td style="padding: 10px 12px; vertical-align: top; text-align: right; font-weight: 800; font-family: monospace; font-size: 0.95rem; color: var(--text-primary);">
                  <span class="row-total-display">₹<?= number_format((float)$it['total_price'], 2) ?></span>
                </td>
                <td style="padding: 10px 8px; vertical-align: top; text-align: center;">
                  <button type="button" onclick="removeLineItemRow(this)" class="btn btn-sm" style="color: #94a3b8; background: none; border: none; font-size: 1rem; cursor: pointer;" title="Remove row">
                    <i class="fas fa-trash-alt"></i>
                  </button>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <div style="margin-top: 14px;">
          <button type="button" onclick="addLineItemRow()" class="btn btn-sm" style="background: #f1f5f9; border: 1px dashed #cbd5e1; color: var(--primary-color); font-weight: 700; width: 100%; padding: 8px;">
            <i class="fas fa-plus"></i> Add Item Row
          </button>
        </div>
      </div>

      <!-- Card 3: Notes -->
      <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 22px; box-shadow: var(--shadow-xs);">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
          <div>
            <label class="form-label" for="notes">Customer Remarks</label>
            <textarea name="notes" id="notes" rows="3" class="form-control"><?= htmlspecialchars($invoice['notes'] ?? '', ENT_QUOTES) ?></textarea>
          </div>
          <div>
            <label class="form-label" for="terms_conditions">Terms &amp; Policy</label>
            <textarea name="terms_conditions" id="terms_conditions" rows="3" class="form-control" style="font-size: 0.8rem;"><?= htmlspecialchars($invoice['terms_conditions'] ?? '', ENT_QUOTES) ?></textarea>
          </div>
        </div>
      </div>

    </div><!-- /left -->

    <!-- Right Column: Settings & Math Box -->
    <div style="display: flex; flex-direction: column; gap: 20px; position: sticky; top: 20px;">

      <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 20px; box-shadow: var(--shadow-xs);">
        
        <div style="font-size: 0.82rem; font-weight: 800; text-transform: uppercase; color: var(--text-primary); letter-spacing: 0.6px; margin-bottom: 14px;">
          Invoice Configuration
        </div>

        <div class="form-group" style="margin-bottom: 12px;">
          <label class="form-label" for="template_key">Template Style</label>
          <select id="template_key" name="template_key" class="form-control" style="font-weight: 600;">
            <?php foreach ($templates as $tpl): ?>
            <option value="<?= htmlspecialchars($tpl['template_key'], ENT_QUOTES) ?>" <?= $invoice['template_key'] === $tpl['template_key'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($tpl['name'], ENT_QUOTES) ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 12px;">
          <div>
            <label class="form-label" for="invoice_date">Invoice Date</label>
            <input type="date" id="invoice_date" name="invoice_date" class="form-control" value="<?= $invoice['invoice_date'] ?>" required />
          </div>
          <div>
            <label class="form-label" for="due_date">Due Date</label>
            <input type="date" id="due_date" name="due_date" class="form-control" value="<?= $invoice['due_date'] ?? '' ?>" />
          </div>
        </div>

        <div class="form-group" style="margin-bottom: 12px;">
          <label class="form-label" for="status">Invoice Status</label>
          <select id="status" name="status" class="form-control" style="font-weight: 700;">
            <?php foreach (\App\Models\Invoice::STATUSES as $k => $v): ?>
            <option value="<?= $k ?>" <?= $invoice['status'] === $k ? 'selected' : '' ?>><?= $v ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group" style="margin-bottom: 0;">
          <label class="form-label" for="payment_method">Payment Mode</label>
          <select id="payment_method" name="payment_method" class="form-control">
            <option value="cash" <?= $invoice['payment_method'] === 'cash' ? 'selected' : '' ?>>Cash Payment</option>
            <option value="upi" <?= $invoice['payment_method'] === 'upi' ? 'selected' : '' ?>>UPI</option>
            <option value="card" <?= $invoice['payment_method'] === 'card' ? 'selected' : '' ?>>Card</option>
            <option value="bank_transfer" <?= $invoice['payment_method'] === 'bank_transfer' ? 'selected' : '' ?>>Bank Transfer</option>
          </select>
        </div>

      </div>

      <!-- Financial Calculation -->
      <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 20px; box-shadow: var(--shadow-xs);">
        
        <div style="display: flex; flex-direction: column; gap: 10px; font-size: 0.88rem;">
          <div style="display: flex; justify-content: space-between; color: var(--text-secondary);">
            <span>Subtotal:</span>
            <span id="display-subtotal" style="font-weight: 700; color: var(--text-primary); font-family: monospace;">₹0.00</span>
          </div>

          <div style="display: flex; justify-content: space-between; align-items: center; gap: 8px;">
            <span style="color: var(--text-secondary);">Discount:</span>
            <div style="display: flex; gap: 4px; width: 150px;">
              <input type="number" id="discount_value" name="discount_value" class="form-control" value="<?= (float)$invoice['discount_value'] ?>" min="0" step="any" style="text-align: right; padding: 4px 8px;" oninput="recalculateInvoiceTotals()" />
              <select name="discount_type" id="discount_type" class="form-control" style="width: 55px; padding: 4px;" onchange="recalculateInvoiceTotals()">
                <option value="fixed" <?= $invoice['discount_type'] === 'fixed' ? 'selected' : '' ?>>₹</option>
                <option value="percentage" <?= $invoice['discount_type'] === 'percentage' ? 'selected' : '' ?>>%</option>
              </select>
            </div>
          </div>

          <div style="display: flex; justify-content: space-between; align-items: center; gap: 8px;">
            <span style="color: var(--text-secondary);">GST Tax (%):</span>
            <div style="width: 90px;">
              <input type="number" id="tax_rate" name="tax_rate" class="form-control" value="<?= (float)$invoice['tax_rate'] ?>" min="0" step="0.01" style="text-align: right; padding: 4px 8px;" oninput="recalculateInvoiceTotals()" />
            </div>
          </div>

          <div style="display: flex; justify-content: space-between; align-items: center; gap: 8px;">
            <span style="color: var(--text-secondary);">Handling:</span>
            <div style="width: 100px;">
              <input type="number" id="shipping_or_handling" name="shipping_or_handling" class="form-control" value="<?= (float)$invoice['shipping_or_handling'] ?>" min="0" step="0.01" style="text-align: right; padding: 4px 8px;" oninput="recalculateInvoiceTotals()" />
            </div>
          </div>

          <div style="height: 1px; background: var(--border-color); margin: 6px 0;"></div>

          <div style="display: flex; justify-content: space-between; font-size: 1.15rem; font-weight: 900; color: var(--primary-color);">
            <span>Grand Total:</span>
            <span id="display-grand-total" style="font-family: monospace;">₹0.00</span>
          </div>

          <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 6px;">
            <span style="color: #059669; font-weight: 700;">Paid:</span>
            <div style="width: 120px;">
              <input type="number" id="paid_amount" name="paid_amount" class="form-control" value="<?= (float)$invoice['paid_amount'] ?>" min="0" step="0.01" style="text-align: right; padding: 6px 8px; font-weight: 800; font-family: monospace; color: #059669;" oninput="recalculateInvoiceTotals()" />
            </div>
          </div>

          <div style="display: flex; justify-content: space-between; font-size: 1rem; font-weight: 900; background: #fef2f2; color: #dc2626; padding: 8px 12px; border-radius: 6px; border: 1px solid #fecaca; margin-top: 4px;">
            <span>Balance Due:</span>
            <span id="display-balance" style="font-family: monospace;">₹0.00</span>
          </div>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 20px; padding: 12px; font-weight: 800; font-size: 1rem; justify-content: center;">
          <i class="fas fa-save" style="margin-right: 8px;"></i>Save Changes
        </button>

      </div>

    </div>

  </div>
</form>

<script>
let rowCounter = <?= count($items) ?>;

function addLineItemRow() {
  const tbody = document.getElementById('items-tbody');
  const index = rowCounter++;

  const tr = document.createElement('tr');
  tr.className = 'item-row';
  tr.style.borderBottom = '1px solid var(--border-color)';
  tr.innerHTML = `
    <td style="padding: 10px 12px; vertical-align: top;">
      <select name="items[${index}][item_type]" class="form-control" style="font-size: 0.84rem;">
        <option value="service">Service</option>
        <option value="part" selected>Part</option>
        <option value="labor">Labor</option>
        <option value="diagnostic">Diagnostic</option>
        <option value="custom">Custom</option>
      </select>
    </td>
    <td style="padding: 10px 12px; vertical-align: top;">
      <input type="text" name="items[${index}][item_name]" class="form-control item-name-input" placeholder="Item name" required style="font-weight: 600;" />
      <input type="text" name="items[${index}][description]" class="form-control" placeholder="Optional notes" style="font-size: 0.8rem; margin-top: 4px;" />
    </td>
    <td style="padding: 10px 12px; vertical-align: top;">
      <input type="number" name="items[${index}][quantity]" class="form-control item-qty-input" value="1" min="0.01" step="any" required style="text-align: center; font-weight: 600;" oninput="recalculateInvoiceTotals()" />
    </td>
    <td style="padding: 10px 12px; vertical-align: top;">
      <input type="number" name="items[${index}][unit_price]" class="form-control item-price-input" placeholder="0.00" value="0.00" min="0" step="0.01" required style="text-align: right; font-weight: 700; font-family: monospace;" oninput="recalculateInvoiceTotals()" />
    </td>
    <td style="padding: 10px 12px; vertical-align: top; text-align: right; font-weight: 800; font-family: monospace; font-size: 0.95rem; color: var(--text-primary);">
      <span class="row-total-display">₹0.00</span>
    </td>
    <td style="padding: 10px 8px; vertical-align: top; text-align: center;">
      <button type="button" onclick="removeLineItemRow(this)" class="btn btn-sm" style="color: #94a3b8; background: none; border: none; font-size: 1rem; cursor: pointer;" title="Remove row">
        <i class="fas fa-trash-alt"></i>
      </button>
    </td>
  `;

  tbody.appendChild(tr);
  recalculateInvoiceTotals();
}

function removeLineItemRow(btn) {
  const tbody = document.getElementById('items-tbody');
  const rows = tbody.querySelectorAll('.item-row');
  if (rows.length <= 1) {
    alert('Invoice must contain at least one line item.');
    return;
  }
  btn.closest('tr').remove();
  recalculateInvoiceTotals();
}

function recalculateInvoiceTotals() {
  let subtotal = 0;
  const rows = document.querySelectorAll('.item-row');

  rows.forEach(row => {
    const qty = parseFloat(row.querySelector('.item-qty-input').value) || 0;
    const price = parseFloat(row.querySelector('.item-price-input').value) || 0;
    const rowTotal = qty * price;
    row.querySelector('.row-total-display').textContent = '₹' + rowTotal.toFixed(2);
    subtotal += rowTotal;
  });

  document.getElementById('display-subtotal').textContent = '₹' + subtotal.toFixed(2);

  const discVal = parseFloat(document.getElementById('discount_value').value) || 0;
  const discType = document.getElementById('discount_type').value;
  let discAmount = 0;
  if (discVal > 0) {
    if (discType === 'percentage') {
      discAmount = (subtotal * Math.min(100, discVal)) / 100;
    } else {
      discAmount = Math.min(subtotal, discVal);
    }
  }

  const taxable = Math.max(0, subtotal - discAmount);
  const taxRate = parseFloat(document.getElementById('tax_rate').value) || 0;
  const taxAmount = (taxable * taxRate) / 100;
  const shipping = parseFloat(document.getElementById('shipping_or_handling').value) || 0;

  const grandTotal = taxable + taxAmount + shipping;
  document.getElementById('display-grand-total').textContent = '₹' + grandTotal.toFixed(2);

  const paid = parseFloat(document.getElementById('paid_amount').value) || 0;
  const balance = Math.max(0, grandTotal - paid);
  document.getElementById('display-balance').textContent = '₹' + balance.toFixed(2);
}

document.addEventListener('DOMContentLoaded', () => {
  recalculateInvoiceTotals();
});
</script>
