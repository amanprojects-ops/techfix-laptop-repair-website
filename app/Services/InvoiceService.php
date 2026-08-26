<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use App\Models\Customer;
use App\Models\Device;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\InvoiceTemplate;
use App\Models\Payment;
use App\Models\RepairJob;
use App\Models\Setting;
use RuntimeException;

class InvoiceService
{
    /**
     * Generate the next automated sequential invoice number
     */
    public function generateNextInvoiceNumber(): string
    {
        $prefix = (string)Setting::get('billing_invoice_prefix', 'INV-{year}-');
        $prefix = str_replace('{year}', date('Y'), $prefix);
        $prefix = str_replace('{month}', date('m'), $prefix);

        $nextNum = (int)Setting::get('billing_next_number', 1001);

        // Ensure uniqueness by checking DB
        $db = Database::get();
        while (true) {
            $formatted = $prefix . str_pad((string)$nextNum, 4, '0', STR_PAD_LEFT);
            $exists = Database::fetchValue("SELECT COUNT(*) FROM `invoices` WHERE `invoice_number` = ?", [$formatted]);
            if ((int)$exists === 0) {
                break;
            }
            $nextNum++;
        }

        // Increment stored next sequence
        Setting::set('billing_next_number', (string)($nextNum + 1));

        return $formatted;
    }

    /**
     * Calculate financial totals: subtotal, discount, tax, grand total, balance
     */
    public function calculateTotals(
        array $items,
        float $discountValue = 0.0,
        string $discountType = 'fixed',
        float $taxRate = 18.0,
        float $shipping = 0.0,
        float $paidAmount = 0.0
    ): array {
        $subtotal = 0.0;
        foreach ($items as $item) {
            $qty   = max(0.01, (float)($item['quantity'] ?? 1.0));
            $unit  = max(0.0, (float)($item['unit_price'] ?? 0.0));
            $total = (float)($item['total_price'] ?? ($qty * $unit));
            $subtotal += $total;
        }

        $subtotal = round($subtotal, 2);

        // Discount calculation
        $discountAmount = 0.0;
        if ($discountValue > 0) {
            if ($discountType === 'percentage') {
                $discountAmount = round(($subtotal * min(100.0, $discountValue)) / 100.0, 2);
            } else {
                $discountAmount = min($subtotal, round($discountValue, 2));
            }
        }

        $taxableBase = max(0.0, round($subtotal - $discountAmount, 2));

        // Tax calculation
        $taxAmount = 0.0;
        if ($taxRate > 0) {
            $taxAmount = round(($taxableBase * $taxRate) / 100.0, 2);
        }

        $shipping    = max(0.0, round($shipping, 2));
        $grandTotal  = round($taxableBase + $taxAmount + $shipping, 2);
        $paidAmount  = max(0.0, round($paidAmount, 2));
        $balanceDue  = max(0.0, round($grandTotal - $paidAmount, 2));

        return [
            'subtotal'        => $subtotal,
            'discount_value'  => $discountValue,
            'discount_type'   => $discountType,
            'discount_amount' => $discountAmount,
            'taxable_base'    => $taxableBase,
            'tax_rate'        => $taxRate,
            'tax_amount'      => $taxAmount,
            'shipping'        => $shipping,
            'total_amount'    => $grandTotal,
            'paid_amount'     => $paidAmount,
            'balance_due'     => $balanceDue,
        ];
    }

    /**
     * Generate dynamic UPI QR code image URL
     */
    public function generateUpiQrUrl(string $upiId, string $payeeName, float $amount, string $invoiceNo): string
    {
        $upiId     = trim($upiId);
        $payeeName = trim($payeeName);

        if ($upiId === '') {
            $upiId = (string)Setting::get('billing_upi_id', 'techfix@sbi');
        }
        if ($payeeName === '') {
            $payeeName = (string)Setting::get('billing_upi_payee_name', site_name());
        }

        $amountFormatted = number_format(max(0.0, $amount), 2, '.', '');
        $note = "Inv {$invoiceNo}";

        $upiUri = "upi://pay?pa=" . rawurlencode($upiId)
            . "&pn=" . rawurlencode($payeeName)
            . "&am=" . rawurlencode($amountFormatted)
            . "&cu=INR"
            . "&tn=" . rawurlencode($note);

        return "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($upiUri) . "&margin=2";
    }

    /**
     * Auto-generate full invoice directly from a Repair Ticket
     */
    public function createFromRepair(int $repairJobId, ?string $templateKey = null, ?int $createdBy = null): int
    {
        $repair = RepairJob::findById($repairJobId);
        if (!$repair) {
            throw new RuntimeException("Repair job #{$repairJobId} not found.");
        }

        // Check if invoice already exists for this repair
        $existing = Invoice::findByRepairJobId($repairJobId);
        if ($existing) {
            return (int)$existing['id'];
        }

        $templateKey = $templateKey ?: (string)Setting::get('billing_default_template', 'modern');
        $taxEnabled  = (string)Setting::get('billing_enable_tax', '1') === '1';
        $taxRate     = $taxEnabled ? (float)Setting::get('billing_tax_rate', 18.0) : 0.0;
        $taxName     = (string)Setting::get('billing_tax_name', 'GST');
        $dueDays     = (int)Setting::get('billing_default_due_days', 7);

        $invoiceDate = date('Y-m-d');
        $dueDate     = date('Y-m-d', strtotime("+{$dueDays} days"));

        // Build Line Items from Repair details
        $items = [];
        $parts = Database::fetchAll("SELECT * FROM `repair_parts` WHERE `repair_job_id` = ?", [$repairJobId]);

        if (!empty($parts)) {
            foreach ($parts as $part) {
                $qty   = (float)$part['quantity'];
                $price = (float)$part['unit_price'];
                $items[] = [
                    'item_type'   => 'part',
                    'item_name'   => $part['part_name'],
                    'description' => 'Replacement hardware component',
                    'quantity'    => $qty,
                    'unit_price'  => $price,
                    'total_price' => $qty * $price,
                ];
            }
        }

        // Add service / labor fee
        $finalBill = (float)($repair['final_amount'] ?? $repair['estimated_amount'] ?? 0.0);
        $serviceName = !empty($repair['service_name']) ? $repair['service_name'] : 'Laptop Hardware Repair & Diagnostics';

        if (empty($items)) {
            $items[] = [
                'item_type'   => 'service',
                'item_name'   => $serviceName . ' (' . $repair['device_brand'] . ' ' . ($repair['device_model'] ?? '') . ')',
                'description' => $repair['problem_description'] ? 'Complaint: ' . substr($repair['problem_description'], 0, 100) : 'Chip-level diagnosis and service',
                'quantity'    => 1.0,
                'unit_price'  => $finalBill > 0 ? $finalBill : 500.0,
                'total_price' => $finalBill > 0 ? $finalBill : 500.0,
            ];
        } else {
            // If parts were listed, check if remaining labor/service charge is needed
            $partsSum = array_sum(array_column($items, 'total_price'));
            if ($finalBill > $partsSum) {
                $labor = $finalBill - $partsSum;
                $items[] = [
                    'item_type'   => 'labor',
                    'item_name'   => 'Technical Labor & Chip-Level Diagnostics Fee',
                    'description' => 'Precision servicing, solder rework & hardware testing',
                    'quantity'    => 1.0,
                    'unit_price'  => $labor,
                    'total_price' => $labor,
                ];
            }
        }

        // Calculate payments already made
        $totalPaid = Payment::totalPaid($repairJobId);
        $totals = $this->calculateTotals($items, 0.0, 'fixed', $taxRate, 0.0, $totalPaid);

        $status = ($totals['balance_due'] <= 0.001) ? Invoice::STATUS_PAID : ($totalPaid > 0 ? Invoice::STATUS_PARTIALLY_PAID : Invoice::STATUS_ISSUED);

        $invoiceNumber = $this->generateNextInvoiceNumber();
        $upiId = (string)Setting::get('billing_upi_id', 'techfix@sbi');
        $payee = (string)Setting::get('billing_upi_payee_name', site_name());
        $qrData = $this->generateUpiQrUrl($upiId, $payee, $totals['balance_due'], $invoiceNumber);

        $invoiceData = [
            'invoice_number'       => $invoiceNumber,
            'repair_job_id'        => $repairJobId,
            'customer_id'          => (int)$repair['customer_id'],
            'template_key'         => $templateKey,
            'invoice_date'         => $invoiceDate,
            'due_date'             => $dueDate,
            'status'               => $status,
            'currency'             => 'INR',
            'currency_symbol'      => currency_symbol(),
            'subtotal'             => $totals['subtotal'],
            'discount_type'        => 'fixed',
            'discount_value'       => 0.0,
            'discount_amount'      => 0.0,
            'tax_name'             => $taxName,
            'tax_rate'             => $taxRate,
            'tax_amount'           => $totals['tax_amount'],
            'shipping_or_handling' => 0.0,
            'total_amount'         => $totals['total_amount'],
            'paid_amount'          => $totals['paid_amount'],
            'balance_due'          => $totals['balance_due'],
            'payment_method'       => 'cash',
            'notes'                => Setting::get('billing_default_notes'),
            'terms_conditions'     => Setting::get('billing_default_terms'),
            'customer_notes'       => 'Repair Ticket: ' . $repair['tracking_id'] . ' (' . $repair['device_brand'] . ' ' . ($repair['device_model'] ?? '') . ')',
            'payment_qr_data'      => $qrData,
            'created_by'           => $createdBy,
        ];

        return Invoice::create($invoiceData, $items);
    }

    /**
     * Render full invoice view with the chosen or fallback template
     */
    public function renderInvoiceHtml(array $invoice, ?string $templateKey = null, bool $isPrintMode = false): string
    {
        $templateKey = $templateKey ?: ($invoice['template_key'] ?? 'modern');
        $template    = InvoiceTemplate::findByKey($templateKey);

        if (!$template) {
            $template = InvoiceTemplate::findByKey('modern') ?: [
                'template_key'      => 'modern',
                'name'              => 'Modern Minimalist',
                'paper_size'        => 'A4',
                'accent_color'      => '#2563EB',
                'secondary_color'   => '#0F172A',
                'font_family'       => 'Inter, sans-serif',
                'show_watermark'    => 1,
                'watermark_text'    => 'PAID',
                'show_qr_code'      => 1,
                'show_signature'    => 1,
                'show_tax_breakup'  => 1,
                'show_bank_details' => 1,
                'header_layout'     => 'standard',
                'custom_css'        => '',
            ];
        }

        $items = $invoice['items'] ?? InvoiceItem::getByInvoiceId((int)$invoice['id']);
        $settings = Setting::all();

        // Template file resolution
        $knownTemplateFiles = [
            'modern'          => BASE_PATH . '/resources/views/invoices/templates/modern.php',
            'classic'         => BASE_PATH . '/resources/views/invoices/templates/classic.php',
            'thermal_pos'     => BASE_PATH . '/resources/views/invoices/templates/thermal_pos.php',
            'techfix_neon'    => BASE_PATH . '/resources/views/invoices/templates/techfix_neon.php',
            'executive_clean' => BASE_PATH . '/resources/views/invoices/templates/executive_clean.php',
        ];

        $templatePath = $knownTemplateFiles[$templateKey] ?? (BASE_PATH . '/resources/views/invoices/templates/custom.php');
        if (!file_exists($templatePath)) {
            $templatePath = BASE_PATH . '/resources/views/invoices/templates/modern.php';
        }

        ob_start();
        // Variables extracted into template scope:
        // $invoice, $items, $template, $settings, $isPrintMode
        require $templatePath;
        return ob_get_clean();
    }

    /**
     * Send Invoice by Email to Customer
     */
    public function sendInvoiceEmail(int $invoiceId, ?string $toEmail = null): bool
    {
        $invoice = Invoice::findById($invoiceId);
        if (!$invoice) {
            throw new RuntimeException("Invoice not found.");
        }

        $email = $toEmail ?: ($invoice['customer_email'] ?? '');
        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException("Valid customer email is required to send invoice.");
        }

        $mailService = new MailService();
        $siteName    = site_name();
        $subject     = "Invoice #{$invoice['invoice_number']} for Your Laptop Repair — {$siteName}";

        $totalFormatted = format_currency($invoice['total_amount'], 2);
        $dueFormatted   = format_currency($invoice['balance_due'], 2);
        $invoiceDate    = date('d M Y', strtotime($invoice['invoice_date']));

        $messageBody = "
            <p>Dear <strong>" . htmlspecialchars($invoice['customer_name'], ENT_QUOTES) . "</strong>,</p>
            <p>Thank you for choosing <strong>{$siteName}</strong>. Please find attached your official repair invoice details below:</p>
            <div style='background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:16px;margin:20px 0;'>
                <table style='width:100%;font-size:14px;line-height:1.6;'>
                    <tr><td><strong>Invoice Number:</strong></td><td>{$invoice['invoice_number']}</td></tr>
                    <tr><td><strong>Date:</strong></td><td>{$invoiceDate}</td></tr>
                    <tr><td><strong>Total Amount:</strong></td><td style='font-weight:bold;color:#2563eb;'>{$totalFormatted}</td></tr>
                    <tr><td><strong>Amount Paid:</strong></td><td style='color:#10b981;'>" . format_currency($invoice['paid_amount'], 2) . "</td></tr>
                    <tr><td><strong>Balance Due:</strong></td><td style='font-weight:bold;color:#dc2626;'>{$dueFormatted}</td></tr>
                    " . (!empty($invoice['repair_tracking_id']) ? "<tr><td><strong>Repair ID:</strong></td><td>{$invoice['repair_tracking_id']}</td></tr>" : "") . "
                </table>
            </div>
            <p>You can view and download your full digital receipt anytime online via our tracking portal.</p>
            <p>Warm regards,<br><strong>{$siteName} Workshop Team</strong><br>" . site_phone() . " | " . site_address() . "</p>
        ";

        return $mailService->sendHtml(
            $email,
            $invoice['customer_name'],
            $subject,
            $messageBody
        );
    }
}
