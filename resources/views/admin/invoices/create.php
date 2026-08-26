<?php
/**
 * TechFix Create New Invoice Form
 * 
 * @var array|null $repair
 * @var array|null $customer
 * @var array      $customers
 * @var array      $templates
 * @var string     $defaultTemplate
 * @var string     $nextNumber
 * @var float      $taxRate
 * @var string     $taxName
 * @var string     $invoiceDate
 * @var string     $dueDate
 * @var string     $csrfToken
 * @var array      $flash_errors
 * @var array      $flash_input
 * @var array      $user
 */
?>

<div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
  <div>
    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
      <a href="<?= url('/admin/invoices') ?>" style="color: var(--text-muted); font-size: 1.1rem; text-decoration: none;" title="Back to Invoices">
        <i class="fas fa-arrow-left"></i>
      </a>
      <h1 style="font-size: 1.6rem; font-weight: 800; color: var(--text-primary); margin: 0;">
        Create New Tax Invoice
      </h1>
      <span class="badge" style="background: rgba(37, 99, 235, 0.1); color: var(--primary-color); padding: 4px 10px; border-radius: 9999px; font-weight: 700; font-size: 0.78rem;">
        #<?= htmlspecialchars($nextNumber, ENT_QUOTES) ?>
      </span>
    </div>
    <p style="color: var(--text-muted); font-size: 0.9rem; margin: 0;">
      Add billable repair items, replacement hardware, labor charges, discounts, and taxes.
    </p>
  </div>

  <div>
    <a href="<?= url('/admin/invoices') ?>" class="btn btn-secondary">
      <i class="fas fa-times" style="margin-right: 6px;"></i>Cancel
    </a>
  </div>
</div>

<?php if (!empty($flash_errors)): ?>
<div style="background: #fef2f2; border-left: 4px solid #ef4444; padding: 14px 18px; border-radius: var(--radius-sm); margin-bottom: 24px; color: #991b1b; font-weight: 600;">
  <div style="display: flex; align-items: center; gap: 8px;">
    <i class="fas fa-exclamation-circle"></i>
    <span>Please check the errors below and try again.</span>
  </div>
</div>
<?php endif; ?>

<form method="POST" action="<?= url('/admin/invoices') ?>" id="invoice-create-form">
  <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES) ?>" />
  <?php if ($repair): ?>
  <input type="hidden" name="repair_job_id" value="<?= $repair['id'] ?>" />
  <?php endif; ?>

  <div style="display: grid; grid-template-columns: 1fr 340px; gap: 24px; align-items: start;">
    
    <!-- Left Column: Primary Details & Line Items Table -->
    <div style="display: flex; flex-direction: column; gap: 24px;">

      <!-- Card 1: Customer Selection & Job Linkage -->
      <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 22px; box-shadow: var(--shadow-xs);">
        <div style="font-size: 0.82rem; font-weight: 800; text-transform: uppercase; color: var(--primary-color); letter-spacing: 0.6px; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
          <i class="fas fa-user-check"></i> Customer &amp; Machine Information
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
          
          <div>
            <label class="form-label" for="customer_id">Select Existing Customer <span style="color: #ef4444;">*</span></label>
            <select name="customer_id" id="customer_id" class="form-control" onchange="toggleCustomerQuickAdd(this.value)">
              <option value="0">— Select Customer (Or Add New Below) —</option>
              <?php foreach ($customers as $c): ?>
              <option value="<?= $c['id'] ?>" <?= (($customer && $customer['id'] == $c['id']) || ($flash_input['customer_id'] ?? '') == $c['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($c['name'], ENT_QUOTES) ?> (<?= htmlspecialchars($c['phone'], ENT_QUOTES) ?>)
              </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div>
            <label class="form-label">Linked Repair Ticket (Optional)</label>
            <?php if ($repair): ?>
            <div style="background: #eff6ff; border: 1px solid #bfdbfe; padding: 9px 14px; border-radius: var(--radius-sm); font-weight: 700; color: #1e40af; display: flex; justify-content: space-between; align-items: center;">
              <span><i class="fas fa-laptop-medical"></i> <?= htmlspecialchars($repair['tracking_id'], ENT_QUOTES) ?></span>
              <span style="font-size: 0.8rem; font-weight: 600; color: #3b82f6;"><?= htmlspecialchars($repair['device_brand'] . ' ' . ($repair['device_model'] ?? ''), ENT_QUOTES) ?></span>
            </div>
            <?php else: ?>
            <input type="text" class="form-control" value="Direct Standalone Invoice" readonly style="background: #f8fafc; color: #64748b;" />
            <?php endif; ?>
          </div>

        </div>

        <!-- Quick Add New Customer (Shown if customer_id == 0) -->
        <div id="new-customer-fields" style="margin-top: 16px; padding-top: 16px; border-top: 1px dashed var(--border-color); display: <?= $customer ? 'none' : 'block' ?>;">
          <div style="font-size: 0.78rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 10px;">Or Quick-Register New Customer:</div>
          <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px;">
            <div>
              <input type="text" name="new_customer_name" placeholder="Full Customer Name" class="form-control" value="<?= htmlspecialchars($flash_input['new_customer_name'] ?? '', ENT_QUOTES) ?>" />
            </div>
            <div>
              <input type="text" name="new_customer_phone" placeholder="Phone (e.g. 9876543210)" class="form-control" value="<?= htmlspecialchars($flash_input['new_customer_phone'] ?? '', ENT_QUOTES) ?>" />
            </div>
            <div>
              <input type="email" name="new_customer_email" placeholder="Email Address (optional)" class="form-control" value="<?= htmlspecialchars($flash_input['new_customer_email'] ?? '', ENT_QUOTES) ?>" />
            </div>
          </div>
        </div>

      </div>

      <!-- Card 2: Interactive Line Items Builder -->
      <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 22px; box-shadow: var(--shadow-xs);">
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
          <div style="font-size: 0.82rem; font-weight: 800; text-transform: uppercase; color: var(--primary-color); letter-spacing: 0.6px; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-list-ol"></i> Billable Services &amp; Hardware Items
          </div>
          <button type="button" onclick="addLineItemRow()" class="btn btn-sm btn-primary" style="font-weight: 700; font-size: 0.82rem;">
            <i class="fas fa-plus" style="margin-right: 4px;"></i>Add Item Row
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
              <!-- Item Row 1 -->
              <tr class="item-row" style="border-bottom: 1px solid var(--border-color);">
                <td style="padding: 10px 12px; vertical-align: top;">
                  <select name="items[0][item_type]" class="form-control" style="font-size: 0.84rem;">
                    <option value="service" selected>Service</option>
                    <option value="part">Part</option>
                    <option value="labor">Labor</option>
                    <option value="diagnostic">Diagnostic</option>
                    <option value="custom">Custom</option>
                  </select>
                </td>
                <td style="padding: 10px 12px; vertical-align: top;">
                  <input type="text" name="items[0][item_name]" class="form-control item-name-input" placeholder="e.g. Motherboard Chip-Level Repair &amp; IC Replacement" value="<?= $repair ? htmlspecialchars($repair['service_name'] ?? 'Laptop Repair Service', ENT_QUOTES) : '' ?>" required style="font-weight: 600;" />
                  <input type="text" name="items[0][description]" class="form-control" placeholder="Optional notes (e.g. Replaced C402 power capacitor, 90-day warranty)" style="font-size: 0.8rem; margin-top: 4px;" />
                </td>
                <td style="padding: 10px 12px; vertical-align: top;">
                  <input type="number" name="items[0][quantity]" class="form-control item-qty-input" value="1" min="0.01" step="any" required style="text-align: center; font-weight: 600;" oninput="recalculateInvoiceTotals()" />
                </td>
                <td style="padding: 10px 12px; vertical-align: top;">
                  <input type="number" name="items[0][unit_price]" class="form-control item-price-input" placeholder="0.00" value="<?= $repair ? htmlspecialchars($repair['final_amount'] ?? $repair['estimated_amount'] ?? '500.00', ENT_QUOTES) : '0.00' ?>" min="0" step="0.01" required style="text-align: right; font-weight: 700; font-family: monospace;" oninput="recalculateInvoiceTotals()" />
                </td>
                <td style="padding: 10px 12px; vertical-align: top; text-align: right; font-weight: 800; font-family: monospace; font-size: 0.95rem; color: var(--text-primary);">
                  <span class="row-total-display">₹0.00</span>
                </td>
                <td style="padding: 10px 8px; vertical-align: top; text-align: center;">
                  <button type="button" onclick="removeLineItemRow(this)" class="btn btn-sm" style="color: #94a3b8; background: none; border: none; font-size: 1rem; cursor: pointer;" title="Remove row">
                    <i class="fas fa-trash-alt"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div style="margin-top: 14px;">
          <button type="button" onclick="addLineItemRow()" class="btn btn-sm" style="background: #f1f5f9; border: 1px dashed #cbd5e1; color: var(--primary-color); font-weight: 700; width: 100%; padding: 8px;">
            <i class="fas fa-plus"></i> Add Another Billable Item / Part
          </button>
        </div>

      </div>

      <!-- Card 3: Notes & Legal Terms -->
      <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 22px; box-shadow: var(--shadow-xs);">
        <div style="font-size: 0.82rem; font-weight: 800; text-transform: uppercase; color: var(--primary-color); letter-spacing: 0.6px; margin-bottom: 14px; display: flex; align-items: center; gap: 8px;">
          <i class="fas fa-comment-alt"></i> Notes &amp; Terms for Customer
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
          <div>
            <label class="form-label" for="notes">Customer Remarks / Advice</label>
            <textarea name="notes" id="notes" rows="3" class="form-control" placeholder="Optional notes for customer..."><?= htmlspecialchars(setting('billing_default_notes', ''), ENT_QUOTES) ?></textarea>
          </div>
          <div>
            <label class="form-label" for="terms_conditions">Terms &amp; Warranty Policy</label>
            <textarea name="terms_conditions" id="terms_conditions" rows="3" class="form-control" style="font-size: 0.8rem;"><?= htmlspecialchars(setting('billing_default_terms', ''), ENT_QUOTES) ?></textarea>
          </div>
        </div>
      </div>

    </div><!-- /left -->

    <!-- Right Column: Invoicing Rules & Live Math Calculation Box -->
    <div style="display: flex; flex-direction: column; gap: 20px; position: sticky; top: 20px;">

      <!-- Card: Invoice Meta -->
      <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 20px; box-shadow: var(--shadow-xs);">
        
        <div style="font-size: 0.82rem; font-weight: 800; text-transform: uppercase; color: var(--text-primary); letter-spacing: 0.6px; margin-bottom: 14px; display: flex; align-items: center; gap: 6px;">
          <i class="fas fa-receipt" style="color: var(--primary-color);"></i> Invoice Header Data
        </div>

        <div class="form-group" style="margin-bottom: 12px;">
          <label class="form-label" for="invoice_number">Invoice Number <span style="color: #ef4444;">*</span></label>
          <input type="text" id="invoice_number" name="invoice_number" class="form-control" value="<?= htmlspecialchars($nextNumber, ENT_QUOTES) ?>" required style="font-weight: 800; font-family: monospace; font-size: 1rem; color: var(--primary-color);" />
        </div>

        <div class="form-group" style="margin-bottom: 12px;">
          <label class="form-label" for="template_key">Invoice Template <span style="color: #ef4444;">*</span></label>
          <select id="template_key" name="template_key" class="form-control" style="font-weight: 600;">
            <?php foreach ($templates as $tpl): ?>
            <option value="<?= htmlspecialchars($tpl['template_key'], ENT_QUOTES) ?>" <?= $defaultTemplate === $tpl['template_key'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($tpl['name'], ENT_QUOTES) ?> (<?= strtoupper($tpl['paper_size']) ?>)
            </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 12px;">
          <div>
            <label class="form-label" for="invoice_date">Invoice Date</label>
            <input type="date" id="invoice_date" name="invoice_date" class="form-control" value="<?= $invoiceDate ?>" required />
          </div>
          <div>
            <label class="form-label" for="due_date">Due Date</label>
            <input type="date" id="due_date" name="due_date" class="form-control" value="<?= $dueDate ?>" />
          </div>
        </div>

        <div class="form-group" style="margin-bottom: 0;">
          <label class="form-label" for="payment_method">Payment Mode</label>
          <select id="payment_method" name="payment_method" class="form-control">
            <option value="cash">Cash Payment</option>
            <option value="upi" selected>UPI (GPay / PhonePe / Paytm)</option>
            <option value="card">Debit / Credit Card</option>
            <option value="bank_transfer">Direct Bank Transfer</option>
          </select>
        </div>

      </div>

      <!-- Card: Live Financial Calculation -->
      <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 20px; box-shadow: var(--shadow-xs);">
        
        <div style="font-size: 0.82rem; font-weight: 800; text-transform: uppercase; color: var(--text-primary); letter-spacing: 0.6px; margin-bottom: 14px;">
          Financial Calculation
        </div>

        <div style="display: flex; flex-direction: column; gap: 10px; font-size: 0.88rem;">
          
          <div style="display: flex; justify-content: space-between; color: var(--text-secondary);">
            <span>Subtotal:</span>
            <span id="display-subtotal" style="font-weight: 700; color: var(--text-primary); font-family: monospace;">₹0.00</span>
          </div>

          <!-- Discount -->
          <div style="display: flex; justify-content: space-between; align-items: center; gap: 8px;">
            <span style="color: var(--text-secondary);">Discount:</span>
            <div style="display: flex; gap: 4px; width: 150px;">
              <input type="number" id="discount_value" name="discount_value" class="form-control" placeholder="0.00" value="0" min="0" step="any" style="text-align: right; padding: 4px 8px; font-size: 0.85rem;" oninput="recalculateInvoiceTotals()" />
              <select name="discount_type" id="discount_type" class="form-control" style="width: 55px; padding: 4px; font-size: 0.82rem;" onchange="recalculateInvoiceTotals()">
                <option value="fixed">₹</option>
                <option value="percentage">%</option>
              </select>
            </div>
          </div>

          <!-- Tax -->
          <div style="display: flex; justify-content: space-between; align-items: center; gap: 8px;">
            <span style="color: var(--text-secondary);"><?= htmlspecialchars($taxName, ENT_QUOTES) ?> Tax (%):</span>
            <div style="width: 90px;">
              <input type="number" id="tax_rate" name="tax_rate" class="form-control" value="<?= $taxRate ?>" min="0" step="0.01" style="text-align: right; padding: 4px 8px; font-size: 0.85rem;" oninput="recalculateInvoiceTotals()" />
            </div>
          </div>

          <!-- Shipping / Handling -->
          <div style="display: flex; justify-content: space-between; align-items: center; gap: 8px;">
            <span style="color: var(--text-secondary);">Handling / Courier:</span>
            <div style="width: 100px;">
              <input type="number" id="shipping_or_handling" name="shipping_or_handling" class="form-control" value="0.00" min="0" step="0.01" style="text-align: right; padding: 4px 8px; font-size: 0.85rem;" oninput="recalculateInvoiceTotals()" />
            </div>
          </div>

          <div style="height: 1px; background: var(--border-color); margin: 6px 0;"></div>

          <!-- Grand Total -->
          <div style="display: flex; justify-content: space-between; font-size: 1.15rem; font-weight: 900; color: var(--primary-color);">
            <span>Grand Total:</span>
            <span id="display-grand-total" style="font-family: monospace;">₹0.00</span>
          </div>

          <!-- Amount Paid & Balance Due -->
          <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 6px;">
            <span style="color: #059669; font-weight: 700;">Amount Paid:</span>
            <div style="width: 120px;">
              <input type="number" id="paid_amount" name="paid_amount" class="form-control" value="0.00" min="0" step="0.01" style="text-align: right; padding: 6px 8px; font-weight: 800; font-family: monospace; color: #059669;" oninput="recalculateInvoiceTotals()" />
            </div>
          </div>

          <div style="display: flex; justify-content: space-between; font-size: 1rem; font-weight: 900; background: #fef2f2; color: #dc2626; padding: 8px 12px; border-radius: 6px; border: 1px solid #fecaca; margin-top: 4px;">
            <span>Balance Due:</span>
            <span id="display-balance" style="font-family: monospace;">₹0.00</span>
          </div>

        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 20px; padding: 12px; font-weight: 800; font-size: 1rem; justify-content: center;">
          <i class="fas fa-check-circle" style="margin-right: 8px;"></i>Save &amp; Generate Invoice
        </button>

      </div>

    </div><!-- /right -->

  </div>
</form>

<script>
let rowCounter = 1;

function toggleCustomerQuickAdd(val) {
  const newCustBox = document.getElementById('new-customer-fields');
  if (newCustBox) {
    newCustBox.style.display = (val === '0') ? 'block' : 'none';
  }
}

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
      <input type="text" name="items[${index}][item_name]" class="form-control item-name-input" placeholder="e.g. 512GB NVMe SSD (Crucial / Kingston)" required style="font-weight: 600;" />
      <input type="text" name="items[${index}][description]" class="form-control" placeholder="Optional hardware specs / part serial" style="font-size: 0.8rem; margin-top: 4px;" />
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
    alert('Invoice must contain at least one billable item.');
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

  // Discount
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

  // Tax
  const taxRate = parseFloat(document.getElementById('tax_rate').value) || 0;
  const taxAmount = (taxable * taxRate) / 100;

  // Shipping
  const shipping = parseFloat(document.getElementById('shipping_or_handling').value) || 0;

  // Grand Total
  const grandTotal = taxable + taxAmount + shipping;
  document.getElementById('display-grand-total').textContent = '₹' + grandTotal.toFixed(2);

  // Paid & Balance
  const paid = parseFloat(document.getElementById('paid_amount').value) || 0;
  const balance = Math.max(0, grandTotal - paid);
  document.getElementById('display-balance').textContent = '₹' + balance.toFixed(2);
}

document.addEventListener('DOMContentLoaded', () => {
  recalculateInvoiceTotals();
});
</script>
