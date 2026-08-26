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
            <select name="customer_id" id="customer_id" class="form-control" onchange="onCustomerSelect(this)" style="font-weight: 600;">
              <option value="0" data-name="" data-phone="" data-email="" data-address="" data-city="">➕ — Quick-Add New Customer —</option>
              <?php foreach ($customers as $c): ?>
              <option value="<?= $c['id'] ?>"
                data-name="<?= htmlspecialchars($c['name'], ENT_QUOTES) ?>"
                data-phone="<?= htmlspecialchars($c['phone'], ENT_QUOTES) ?>"
                data-email="<?= htmlspecialchars($c['email'] ?? '', ENT_QUOTES) ?>"
                data-address="<?= htmlspecialchars($c['address'] ?? '', ENT_QUOTES) ?>"
                data-city="<?= htmlspecialchars($c['city'] ?? '', ENT_QUOTES) ?>"
                data-state="<?= htmlspecialchars($c['state'] ?? '', ENT_QUOTES) ?>"
                data-pincode="<?= htmlspecialchars($c['pincode'] ?? '', ENT_QUOTES) ?>"
                <?= (($customer && $customer['id'] == $c['id']) || ($flash_input['customer_id'] ?? '') == $c['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($c['name'], ENT_QUOTES) ?> — <?= htmlspecialchars($c['phone'], ENT_QUOTES) ?><?= !empty($c['city']) ? ' (' . htmlspecialchars($c['city'], ENT_QUOTES) . ')' : '' ?>
              </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div>
            <label class="form-label">Linked Repair Ticket (Optional)</label>
            <input type="hidden" name="repair_job_id" id="input_repair_job_id" value="<?= $repair ? $repair['id'] : '' ?>" />
            <div id="linked-ticket-display">
              <?php if ($repair): ?>
              <div style="background: #eff6ff; border: 1px solid #bfdbfe; padding: 9px 14px; border-radius: var(--radius-sm); font-weight: 700; color: #1e40af; display: flex; justify-content: space-between; align-items: center;">
                <span><i class="fas fa-laptop-medical"></i> <?= htmlspecialchars($repair['tracking_id'], ENT_QUOTES) ?></span>
                <span style="font-size: 0.8rem; font-weight: 600; color: #3b82f6;"><?= htmlspecialchars($repair['device_brand'] . ' ' . ($repair['device_model'] ?? ''), ENT_QUOTES) ?></span>
              </div>
              <?php else: ?>
              <div style="background: #f8fafc; border: 1px solid var(--border-color); padding: 9px 14px; border-radius: var(--radius-sm); color: #64748b; font-size: 0.88rem; display: flex; align-items: center; justify-content: space-between;">
                <span id="ticket-status-text">Direct Standalone Invoice (No Ticket)</span>
                <span id="ticket-unlink-btn" style="display:none; color: #ef4444; font-size: 0.78rem; font-weight: 700; cursor: pointer;" onclick="unlinkRepairTicket()">✕ Unlink</span>
              </div>
              <?php endif; ?>
            </div>
          </div>

        </div>

        <!-- Selected Customer Auto-Fill Profile Box -->
        <div id="selected-customer-box" style="display: none; margin-top: 16px; padding: 16px 18px; background: #f0fdf4; border: 1.5px solid #86efac; border-radius: 8px;">
          <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 10px;">
            <div>
              <div style="display: flex; align-items: center; gap: 8px;">
                <span style="display: inline-flex; align-items: center; justify-content: center; width: 24px; height: 24px; border-radius: 50%; background: #16a34a; color: #ffffff; font-size: 0.72rem;">
                  <i class="fas fa-check"></i>
                </span>
                <span id="cust-card-name" style="font-weight: 800; font-size: 1.05rem; color: #14532d;"></span>
                <span id="cust-card-city-badge" style="font-size: 0.76rem; background: #dcfce7; color: #166534; padding: 2px 8px; border-radius: 9999px; font-weight: 700; display: none;"></span>
              </div>
              <div style="margin-top: 8px; font-size: 0.85rem; color: #166534; display: flex; flex-wrap: wrap; gap: 18px;">
                <span><i class="fas fa-phone" style="margin-right: 4px; color: #15803d;"></i><strong id="cust-card-phone"></strong></span>
                <span id="cust-card-email-wrap" style="display: none;"><i class="fas fa-envelope" style="margin-right: 4px; color: #15803d;"></i><span id="cust-card-email"></span></span>
                <span id="cust-card-addr-wrap" style="display: none;"><i class="fas fa-map-marker-alt" style="margin-right: 4px; color: #15803d;"></i><span id="cust-card-address"></span></span>
              </div>
            </div>
            <button type="button" onclick="clearCustomerSelection()" style="background: none; border: none; color: #15803d; font-size: 0.8rem; font-weight: 700; cursor: pointer; text-decoration: underline;">
              Add New Customer Instead
            </button>
          </div>

          <!-- Linked Repair Jobs for this customer -->
          <div id="cust-repairs-section" style="margin-top: 14px; padding-top: 12px; border-top: 1px dashed #bbf7d0; display: none;">
            <div style="font-size: 0.76rem; font-weight: 800; text-transform: uppercase; color: #166534; letter-spacing: 0.5px; margin-bottom: 8px;">
              <i class="fas fa-laptop-medical"></i> Available Repair Tickets for this Customer:
            </div>
            <div id="cust-repairs-list" style="display: flex; flex-direction: column; gap: 6px;"></div>
          </div>
        </div>

        <!-- Quick Add New Customer (Shown if customer_id == 0) -->
        <div id="new-customer-fields" style="margin-top: 16px; padding: 16px 18px; background: #f8fafc; border: 1px dashed var(--border-color); border-radius: 8px; display: <?= $customer ? 'none' : 'block' ?>;">
          <div style="font-size: 0.78rem; font-weight: 800; color: var(--primary-color); text-transform: uppercase; margin-bottom: 12px; display: flex; align-items: center; gap: 6px;">
            <i class="fas fa-user-plus"></i> New Customer Details (Auto-Saves to Directory):
          </div>
          <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px;">
            <div>
              <label class="form-label" style="font-size: 0.78rem;">Full Customer Name *</label>
              <input type="text" name="new_customer_name" id="new_customer_name" placeholder="Full Customer Name" class="form-control" value="<?= htmlspecialchars($flash_input['new_customer_name'] ?? '', ENT_QUOTES) ?>" />
            </div>
            <div>
              <label class="form-label" style="font-size: 0.78rem;">Phone Number *</label>
              <input type="text" name="new_customer_phone" id="new_customer_phone" placeholder="Phone (e.g. 9876543210)" class="form-control" value="<?= htmlspecialchars($flash_input['new_customer_phone'] ?? '', ENT_QUOTES) ?>" />
            </div>
            <div>
              <label class="form-label" style="font-size: 0.78rem;">Email Address (Optional)</label>
              <input type="email" name="new_customer_email" id="new_customer_email" placeholder="Email Address (optional)" class="form-control" value="<?= htmlspecialchars($flash_input['new_customer_email'] ?? '', ENT_QUOTES) ?>" />
            </div>
          </div>
          <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 12px; margin-top: 10px;">
            <div>
              <label class="form-label" style="font-size: 0.78rem;">Billing Address (Optional)</label>
              <input type="text" name="new_customer_address" id="new_customer_address" placeholder="Street / Landmark / Colony" class="form-control" value="<?= htmlspecialchars($flash_input['new_customer_address'] ?? '', ENT_QUOTES) ?>" />
            </div>
            <div>
              <label class="form-label" style="font-size: 0.78rem;">City (Optional)</label>
              <input type="text" name="new_customer_city" id="new_customer_city" placeholder="City (e.g. Saharsa)" class="form-control" value="<?= htmlspecialchars($flash_input['new_customer_city'] ?? '', ENT_QUOTES) ?>" />
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

function onCustomerSelect(selectEl) {
  const val = selectEl.value;
  const newCustBox = document.getElementById('new-customer-fields');
  const selectedBox = document.getElementById('selected-customer-box');
  const repairsSection = document.getElementById('cust-repairs-section');
  const repairsList = document.getElementById('cust-repairs-list');

  if (val === '0' || !val) {
    if (newCustBox) newCustBox.style.display = 'block';
    if (selectedBox) selectedBox.style.display = 'none';
    if (repairsSection) repairsSection.style.display = 'none';
    return;
  }

  // Selected existing customer
  if (newCustBox) newCustBox.style.display = 'none';

  const selectedOpt = selectEl.options[selectEl.selectedIndex];
  if (!selectedOpt) return;

  const name    = selectedOpt.dataset.name || '';
  const phone   = selectedOpt.dataset.phone || '';
  const email   = selectedOpt.dataset.email || '';
  const address = selectedOpt.dataset.address || '';
  const city    = selectedOpt.dataset.city || '';
  const state   = selectedOpt.dataset.state || '';
  const pincode = selectedOpt.dataset.pincode || '';

  // Fill Customer Card
  document.getElementById('cust-card-name').textContent = name;
  document.getElementById('cust-card-phone').textContent = phone;

  const cityBadge = document.getElementById('cust-card-city-badge');
  if (city) {
    cityBadge.textContent = city;
    cityBadge.style.display = 'inline-block';
  } else {
    cityBadge.style.display = 'none';
  }

  const emailWrap = document.getElementById('cust-card-email-wrap');
  if (email) {
    document.getElementById('cust-card-email').textContent = email;
    emailWrap.style.display = 'inline';
  } else {
    emailWrap.style.display = 'none';
  }

  const addrWrap = document.getElementById('cust-card-addr-wrap');
  const fullAddr = [address, city, state, pincode].filter(Boolean).join(', ');
  if (fullAddr) {
    document.getElementById('cust-card-address').textContent = fullAddr;
    addrWrap.style.display = 'inline';
  } else {
    addrWrap.style.display = 'none';
  }

  if (selectedBox) selectedBox.style.display = 'block';

  // Fetch linked repair tickets asynchronously
  fetch('<?= url('/admin/invoices/customer/') ?>' + encodeURIComponent(val))
    .then(res => res.json())
    .then(data => {
      if (data.success && data.repairs && data.repairs.length > 0) {
        repairsList.innerHTML = '';
        data.repairs.forEach(rep => {
          const repDiv = document.createElement('div');
          repDiv.style.cssText = 'background: #ffffff; border: 1px solid #bbf7d0; border-radius: 6px; padding: 8px 12px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px;';
          
          const finalAmt = parseFloat(rep.final_amount || rep.estimated_amount || 0);
          const totalPaid = parseFloat(rep.total_paid || 0);
          
          repDiv.innerHTML = `
            <div style="display: flex; align-items: center; gap: 8px; font-size: 0.84rem;">
              <span style="font-family: monospace; font-weight: 800; color: #166534; background: #dcfce7; padding: 2px 6px; border-radius: 4px;">${rep.tracking_id}</span>
              <strong style="color: #1f2937;">${rep.device_brand || ''} ${rep.device_model || ''}</strong>
              <span style="color: #6b7280; font-size: 0.78rem;">(${rep.service_name || 'Hardware Repair'})</span>
              <span style="font-size: 0.76rem; background: #f3f4f6; color: #374151; padding: 1px 6px; border-radius: 4px;">${rep.current_status}</span>
            </div>
            <div style="display: flex; align-items: center; gap: 10px;">
              <span style="font-size: 0.84rem; font-weight: 700; color: #15803d; font-family: monospace;">₹${finalAmt.toFixed(2)}</span>
              <button type="button" class="btn btn-sm btn-primary" style="padding: 4px 10px; font-size: 0.78rem; font-weight: 700;" onclick='importRepairTicket(${JSON.stringify(rep).replace(/'/g, "&apos;")})'>
                <i class="fas fa-magic" style="margin-right: 4px;"></i>Auto-Fill into Invoice
              </button>
            </div>
          `;
          repairsList.appendChild(repDiv);
        });
        repairsSection.style.display = 'block';
      } else {
        repairsSection.style.display = 'none';
      }
    })
    .catch(() => {
      if (repairsSection) repairsSection.style.display = 'none';
    });
}

function clearCustomerSelection() {
  const sel = document.getElementById('customer_id');
  if (sel) {
    sel.value = '0';
    onCustomerSelect(sel);
  }
}

function unlinkRepairTicket() {
  document.getElementById('input_repair_job_id').value = '';
  document.getElementById('ticket-status-text').textContent = 'Direct Standalone Invoice (No Ticket)';
  document.getElementById('ticket-unlink-btn').style.display = 'none';
}

function importRepairTicket(rep) {
  // 1. Set hidden repair_job_id
  document.getElementById('input_repair_job_id').value = rep.id;
  
  // 2. Update linked ticket display
  const statusText = document.getElementById('ticket-status-text');
  const unlinkBtn = document.getElementById('ticket-unlink-btn');
  if (statusText) {
    statusText.innerHTML = `<span style="color: #1e40af; font-weight: 700;"><i class="fas fa-laptop-medical"></i> ${rep.tracking_id}</span> — ${rep.device_brand || ''} ${rep.device_model || ''}`;
  }
  if (unlinkBtn) unlinkBtn.style.display = 'inline';

  // 3. Populate first line item row
  const firstRow = document.querySelector('.item-row');
  if (firstRow) {
    const nameInput = firstRow.querySelector('.item-name-input');
    const descInput = firstRow.querySelector('input[name*="[description]"]');
    const priceInput = firstRow.querySelector('.item-price-input');
    const typeSelect = firstRow.querySelector('select[name*="[item_type]"]');

    if (typeSelect) typeSelect.value = 'service';
    if (nameInput) {
      nameInput.value = (rep.service_name || 'Laptop Diagnostic & Repair') + ' — ' + (rep.device_brand || '') + ' ' + (rep.device_model || '');
    }
    if (descInput && rep.problem_description) {
      descInput.value = rep.problem_description;
    }
    
    const amt = parseFloat(rep.final_amount || rep.estimated_amount || 0);
    if (priceInput && amt > 0) {
      priceInput.value = amt.toFixed(2);
    }
  }

  // 4. Populate amount paid if recorded
  const totalPaid = parseFloat(rep.total_paid || 0);
  if (totalPaid > 0) {
    const paidInput = document.getElementById('paid_amount');
    if (paidInput) paidInput.value = totalPaid.toFixed(2);
  }

  recalculateInvoiceTotals();
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
  const custSel = document.getElementById('customer_id');
  if (custSel && custSel.value !== '0') {
    onCustomerSelect(custSel);
  }
});
</script>
