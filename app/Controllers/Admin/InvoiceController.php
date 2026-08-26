<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;
use App\Middleware\AuthMiddleware;
use App\Middleware\CsrfMiddleware;
use App\Models\Customer;
use App\Models\Device;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\InvoiceTemplate;
use App\Models\Payment;
use App\Models\RepairJob;
use App\Models\Setting;
use App\Models\User;
use App\Services\InvoiceService;
use Throwable;

class InvoiceController extends Controller
{
    private InvoiceService $invoiceService;

    public function __construct()
    {
        parent::__construct();
        AuthMiddleware::handle();
        $this->invoiceService = new InvoiceService();
    }

    /**
     * Invoices directory & metrics dashboard
     */
    public function index(): void
    {
        $status = $this->request->get('status');
        $search = $this->request->get('search');
        $page   = max(1, (int)$this->request->get('page', 1));
        $limit  = 15;
        $offset = ($page - 1) * $limit;

        $invoices = Invoice::all($limit, $offset, $status, $search);
        $total    = Invoice::count($status, $search);
        $pages    = (int)ceil($total / $limit);
        $stats    = Invoice::getStats();

        $templates = InvoiceTemplate::all();
        $csrfToken = Session::csrfToken();
        $user      = ['name' => Session::userName(), 'role' => Session::userRole()];

        $flashSuccess = Session::getFlash('success');
        $flashError   = Session::getFlash('error');

        $this->render('admin/invoices/index', compact(
            'invoices', 'total', 'pages', 'page', 'status', 'search',
            'stats', 'templates', 'csrfToken', 'user', 'flashSuccess', 'flashError'
        ), 'admin');
    }

    /**
     * Create invoice form (standalone or prefilled from repair/customer)
     */
    public function create(): void
    {
        $repairId   = (int)$this->request->get('repair_id', 0);
        $customerId = (int)$this->request->get('customer_id', 0);

        $repair   = $repairId > 0 ? RepairJob::findById($repairId) : null;
        $customer = $customerId > 0 ? Customer::findById($customerId) : ($repair ? Customer::findById((int)$repair['customer_id']) : null);

        $customers = Customer::all(200);
        $templates = InvoiceTemplate::allActive();

        $defaultTemplate = (string)Setting::get('billing_default_template', 'modern');
        $nextNumber      = $this->invoiceService->generateNextInvoiceNumber();
        $taxEnabled      = (string)Setting::get('billing_enable_tax', '1') === '1';
        $taxRate         = $taxEnabled ? (float)Setting::get('billing_tax_rate', 18.0) : 0.0;
        $taxName         = (string)Setting::get('billing_tax_name', 'GST');
        $dueDays         = (int)Setting::get('billing_default_due_days', 7);

        $invoiceDate = date('Y-m-d');
        $dueDate     = date('Y-m-d', strtotime("+{$dueDays} days"));

        $csrfToken    = Session::csrfToken();
        $flash_errors = Session::getFlash('errors', []);
        $flash_input  = Session::getFlash('input', []);
        $user         = ['name' => Session::userName(), 'role' => Session::userRole()];

        $this->render('admin/invoices/create', compact(
            'repair', 'customer', 'customers', 'templates', 'defaultTemplate',
            'nextNumber', 'taxRate', 'taxName', 'invoiceDate', 'dueDate',
            'csrfToken', 'flash_errors', 'flash_input', 'user'
        ), 'admin');
    }

    /**
     * AJAX endpoint to fetch customer billing information and recent repair jobs
     */
    public function getCustomerData(string $id): void
    {
        $customerId = (int)$id;
        $customer = Customer::findById($customerId);
        if (!$customer) {
            $this->json(['success' => false, 'message' => 'Customer not found.'], 404);
        }

        $repairs = RepairJob::getByCustomerId($customerId);

        $this->json([
            'success'  => true,
            'customer' => $customer,
            'repairs'  => $repairs,
        ]);
    }

    /**
     * Store new invoice
     */
    public function store(): void
    {
        CsrfMiddleware::verify();

        $customerId = (int)$this->request->post('customer_id', 0);
        $invoiceNo  = trim((string)$this->request->post('invoice_number', ''));

        // Quick Customer Creation if new customer details are entered
        if ($customerId === 0) {
            $cName  = trim((string)$this->request->post('new_customer_name', ''));
            $cPhone = trim((string)$this->request->post('new_customer_phone', ''));
            if ($cName !== '' && $cPhone !== '') {
                $customerId = Customer::create([
                    'name'    => $cName,
                    'phone'   => $cPhone,
                    'email'   => $this->request->post('new_customer_email') ?: null,
                    'address' => $this->request->post('new_customer_address') ?: null,
                    'city'    => $this->request->post('new_customer_city') ?: null,
                ]);
            }
        }

        if ($customerId <= 0) {
            Session::flash('error', 'Please select an existing customer or enter new customer information.');
            Session::flash('input', $this->request->all());
            $this->redirect('/admin/invoices/create');
        }

        $itemsRaw = $this->request->post('items', []);
        $items = [];
        if (is_array($itemsRaw)) {
            foreach ($itemsRaw as $row) {
                $name = trim((string)($row['item_name'] ?? ''));
                if ($name === '') continue;
                $qty   = max(0.01, (float)($row['quantity'] ?? 1.0));
                $unit  = max(0.0, (float)($row['unit_price'] ?? 0.0));
                $items[] = [
                    'item_name'   => $name,
                    'description' => $row['description'] ?? null,
                    'item_type'   => $row['item_type'] ?? 'service',
                    'quantity'    => $qty,
                    'unit_price'  => $unit,
                    'total_price' => round($qty * $unit, 2),
                ];
            }
        }

        if (empty($items)) {
            Session::flash('error', 'Please add at least one line item to the invoice.');
            Session::flash('input', $this->request->all());
            $this->redirect('/admin/invoices/create');
        }

        $discountVal  = (float)$this->request->post('discount_value', 0.0);
        $discountType = $this->request->post('discount_type') === 'percentage' ? 'percentage' : 'fixed';
        $taxRate      = (float)$this->request->post('tax_rate', 0.0);
        $shipping     = (float)$this->request->post('shipping_or_handling', 0.0);
        $paidAmount   = (float)$this->request->post('paid_amount', 0.0);

        $totals = $this->invoiceService->calculateTotals(
            $items, $discountVal, $discountType, $taxRate, $shipping, $paidAmount
        );

        $status = $this->request->post('status', Invoice::STATUS_ISSUED);
        if ($totals['balance_due'] <= 0.001 && $paidAmount > 0) {
            $status = Invoice::STATUS_PAID;
        }

        $upiId = (string)Setting::get('billing_upi_id', 'techfix@sbi');
        $payee = (string)Setting::get('billing_upi_payee_name', site_name());
        $qrData = $this->invoiceService->generateUpiQrUrl($upiId, $payee, $totals['balance_due'], $invoiceNo);

        $invoiceData = [
            'invoice_number'       => $invoiceNo,
            'repair_job_id'        => $this->request->post('repair_job_id') ?: null,
            'customer_id'          => $customerId,
            'template_key'         => $this->request->post('template_key', 'modern'),
            'invoice_date'         => $this->request->post('invoice_date', date('Y-m-d')),
            'due_date'             => $this->request->post('due_date') ?: null,
            'status'               => $status,
            'currency'             => 'INR',
            'currency_symbol'      => currency_symbol(),
            'subtotal'             => $totals['subtotal'],
            'discount_type'        => $discountType,
            'discount_value'       => $discountVal,
            'discount_amount'      => $totals['discount_amount'],
            'tax_name'             => $this->request->post('tax_name', 'GST'),
            'tax_rate'             => $taxRate,
            'tax_amount'           => $totals['tax_amount'],
            'shipping_or_handling' => $shipping,
            'total_amount'         => $totals['total_amount'],
            'paid_amount'          => $paidAmount,
            'balance_due'          => $totals['balance_due'],
            'payment_method'       => $this->request->post('payment_method', 'cash'),
            'payment_reference'    => $this->request->post('payment_reference') ?: null,
            'notes'                => $this->request->post('notes') ?: Setting::get('billing_default_notes'),
            'terms_conditions'     => $this->request->post('terms_conditions') ?: Setting::get('billing_default_terms'),
            'customer_notes'       => $this->request->post('customer_notes') ?: null,
            'payment_qr_data'      => $qrData,
            'created_by'           => Session::userId() ?: null,
        ];

        try {
            $invoiceId = Invoice::create($invoiceData, $items);
            Session::flash('success', "Invoice #{$invoiceNo} created successfully.");
            $this->redirect('/admin/invoices/' . $invoiceId);
        } catch (Throwable $e) {
            Session::flash('error', 'Failed to create invoice: ' . $e->getMessage());
            Session::flash('input', $this->request->all());
            $this->redirect('/admin/invoices/create');
        }
    }

    /**
     * Inspect invoice with live template switcher & action controls
     */
    public function view(string $id): void
    {
        $invoice = Invoice::findById((int)$id);
        if (!$invoice) {
            $this->abort(404, 'Invoice not found.');
        }

        // Live template override via query param ?template=xyz
        $activeTemplateKey = $this->request->get('template') ?: ($invoice['template_key'] ?? 'modern');
        $renderedHtml = $this->invoiceService->renderInvoiceHtml($invoice, $activeTemplateKey);

        $templates = InvoiceTemplate::allActive();
        $csrfToken = Session::csrfToken();
        $user      = ['name' => Session::userName(), 'role' => Session::userRole()];

        $flashSuccess = Session::getFlash('success');
        $flashError   = Session::getFlash('error');

        $this->render('admin/invoices/view', compact(
            'invoice', 'renderedHtml', 'templates', 'activeTemplateKey',
            'csrfToken', 'user', 'flashSuccess', 'flashError'
        ), 'admin');
    }

    /**
     * Edit invoice form
     */
    public function edit(string $id): void
    {
        $invoice = Invoice::findById((int)$id);
        if (!$invoice) {
            $this->abort(404, 'Invoice not found.');
        }

        $customers = Customer::all(200);
        $templates = InvoiceTemplate::allActive();
        $csrfToken = Session::csrfToken();
        $user      = ['name' => Session::userName(), 'role' => Session::userRole()];

        $this->render('admin/invoices/edit', compact(
            'invoice', 'customers', 'templates', 'csrfToken', 'user'
        ), 'admin');
    }

    /**
     * Update existing invoice
     */
    public function update(string $id): void
    {
        CsrfMiddleware::verify();

        $invoice = Invoice::findById((int)$id);
        if (!$invoice) {
            $this->abort(404, 'Invoice not found.');
        }

        $itemsRaw = $this->request->post('items', []);
        $items = [];
        if (is_array($itemsRaw)) {
            foreach ($itemsRaw as $row) {
                $name = trim((string)($row['item_name'] ?? ''));
                if ($name === '') continue;
                $qty   = max(0.01, (float)($row['quantity'] ?? 1.0));
                $unit  = max(0.0, (float)($row['unit_price'] ?? 0.0));
                $items[] = [
                    'item_name'   => $name,
                    'description' => $row['description'] ?? null,
                    'item_type'   => $row['item_type'] ?? 'service',
                    'quantity'    => $qty,
                    'unit_price'  => $unit,
                    'total_price' => round($qty * $unit, 2),
                ];
            }
        }

        if (empty($items)) {
            Session::flash('error', 'Please add at least one line item.');
            $this->redirect('/admin/invoices/' . $id . '/edit');
        }

        $discountVal  = (float)$this->request->post('discount_value', 0.0);
        $discountType = $this->request->post('discount_type') === 'percentage' ? 'percentage' : 'fixed';
        $taxRate      = (float)$this->request->post('tax_rate', 0.0);
        $shipping     = (float)$this->request->post('shipping_or_handling', 0.0);
        $paidAmount   = (float)$this->request->post('paid_amount', $invoice['paid_amount']);

        $totals = $this->invoiceService->calculateTotals(
            $items, $discountVal, $discountType, $taxRate, $shipping, $paidAmount
        );

        $status = $this->request->post('status', $invoice['status']);
        if ($totals['balance_due'] <= 0.001 && $paidAmount > 0) {
            $status = Invoice::STATUS_PAID;
        }

        $upiId = (string)Setting::get('billing_upi_id', 'techfix@sbi');
        $payee = (string)Setting::get('billing_upi_payee_name', site_name());
        $qrData = $this->invoiceService->generateUpiQrUrl($upiId, $payee, $totals['balance_due'], $invoice['invoice_number']);

        $invoiceData = [
            'template_key'         => $this->request->post('template_key', $invoice['template_key']),
            'invoice_date'         => $this->request->post('invoice_date', $invoice['invoice_date']),
            'due_date'             => $this->request->post('due_date') ?: null,
            'status'               => $status,
            'subtotal'             => $totals['subtotal'],
            'discount_type'        => $discountType,
            'discount_value'       => $discountVal,
            'discount_amount'      => $totals['discount_amount'],
            'tax_name'             => $this->request->post('tax_name', 'GST'),
            'tax_rate'             => $taxRate,
            'tax_amount'           => $totals['tax_amount'],
            'shipping_or_handling' => $shipping,
            'total_amount'         => $totals['total_amount'],
            'paid_amount'          => $paidAmount,
            'balance_due'          => $totals['balance_due'],
            'payment_method'       => $this->request->post('payment_method', $invoice['payment_method']),
            'payment_reference'    => $this->request->post('payment_reference') ?: null,
            'notes'                => $this->request->post('notes') ?: null,
            'terms_conditions'     => $this->request->post('terms_conditions') ?: null,
            'customer_notes'       => $this->request->post('customer_notes') ?: null,
            'payment_qr_data'      => $qrData,
        ];

        Invoice::update((int)$id, $invoiceData, $items);
        Session::flash('success', "Invoice #{$invoice['invoice_number']} updated successfully.");
        $this->redirect('/admin/invoices/' . $id);
    }

    /**
     * Delete an invoice
     */
    public function delete(string $id): void
    {
        CsrfMiddleware::verify();
        Invoice::delete((int)$id);
        Session::flash('success', 'Invoice deleted successfully.');
        $this->redirect('/admin/invoices');
    }

    /**
     * Standalone Clean Print View
     */
    public function print(string $id): void
    {
        $invoice = Invoice::findById((int)$id);
        if (!$invoice) {
            $this->abort(404, 'Invoice not found.');
        }

        $templateKey = $this->request->get('template') ?: ($invoice['template_key'] ?? 'modern');
        $renderedHtml = $this->invoiceService->renderInvoiceHtml($invoice, $templateKey, true);

        require BASE_PATH . '/resources/views/admin/invoices/print.php';
        exit;
    }

    /**
     * 1-Click Auto Generate Invoice from a Repair Job Ticket
     */
    public function generateFromRepair(string $repairId): void
    {
        CsrfMiddleware::verify();

        try {
            $invoiceId = $this->invoiceService->createFromRepair((int)$repairId, null, (int)Session::userId());
            Session::flash('success', 'Tax invoice generated automatically from repair job details.');
            $this->redirect('/admin/invoices/' . $invoiceId);
        } catch (Throwable $e) {
            Session::flash('error', 'Invoice generation error: ' . $e->getMessage());
            $this->redirect('/admin/repairs/' . $repairId);
        }
    }

    /**
     * Quick Record Payment against Invoice
     */
    public function addPayment(string $id): void
    {
        CsrfMiddleware::verify();

        $amount = (float)$this->request->post('amount', 0.0);
        if ($amount <= 0) {
            Session::flash('error', 'Please enter a valid payment amount.');
            $this->redirect('/admin/invoices/' . $id);
        }

        $method = $this->request->post('payment_method', 'cash');
        $ref    = $this->request->post('payment_reference');
        $note   = $this->request->post('payment_note');

        $ok = Invoice::recordPayment((int)$id, $amount, $method, $ref, $note);
        if ($ok) {
            Session::flash('success', 'Payment of ₹' . number_format($amount, 2) . ' recorded successfully.');
        } else {
            Session::flash('error', 'Failed to record payment.');
        }

        $this->redirect('/admin/invoices/' . $id);
    }

    /**
     * Send Invoice to Customer via Email
     */
    public function sendEmail(string $id): void
    {
        CsrfMiddleware::verify();

        $customEmail = $this->request->post('recipient_email');

        try {
            $this->invoiceService->sendInvoiceEmail((int)$id, $customEmail ?: null);
            Session::flash('success', 'Invoice has been emailed to the customer successfully.');
        } catch (Throwable $e) {
            Session::flash('error', 'Failed to send email: ' . $e->getMessage());
        }

        $this->redirect('/admin/invoices/' . $id);
    }
}
