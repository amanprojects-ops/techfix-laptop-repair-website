<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use App\Core\Session;
use App\Services\InvoiceService;
use PDO;

class Invoice
{
    public const STATUS_DRAFT          = 'draft';
    public const STATUS_ISSUED         = 'issued';
    public const STATUS_PAID           = 'paid';
    public const STATUS_PARTIALLY_PAID = 'partially_paid';
    public const STATUS_CANCELLED      = 'cancelled';
    public const STATUS_OVERDUE        = 'overdue';

    public const STATUSES = [
        self::STATUS_DRAFT          => 'Draft',
        self::STATUS_ISSUED         => 'Issued / Due',
        self::STATUS_PAID           => 'Paid in Full',
        self::STATUS_PARTIALLY_PAID => 'Partially Paid',
        self::STATUS_CANCELLED      => 'Cancelled',
        self::STATUS_OVERDUE        => 'Overdue',
    ];

    public const STATUS_COLORS = [
        self::STATUS_DRAFT          => '#64748B',
        self::STATUS_ISSUED         => '#2563EB',
        self::STATUS_PAID           => '#10B981',
        self::STATUS_PARTIALLY_PAID => '#F59E0B',
        self::STATUS_CANCELLED      => '#EF4444',
        self::STATUS_OVERDUE        => '#DC2626',
    ];

    /**
     * Retrieve paginated invoices with optional status/search filters
     */
    public static function all(int $limit = 20, int $offset = 0, ?string $status = null, ?string $search = null): array
    {
        $params = [];
        $where  = [];

        if ($status && array_key_exists($status, self::STATUSES)) {
            $where[] = 'i.`status` = :status';
            $params[':status'] = $status;
        }

        if ($search) {
            $where[] = '(i.`invoice_number` LIKE :search OR c.`name` LIKE :search OR c.`phone` LIKE :search OR rj.`tracking_id` LIKE :search)';
            $params[':search'] = "%{$search}%";
        }

        $whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        $sql = "
            SELECT
                i.*,
                c.`name`       AS `customer_name`,
                c.`phone`      AS `customer_phone`,
                c.`email`      AS `customer_email`,
                c.`address`    AS `customer_address`,
                c.`city`       AS `customer_city`,
                c.`state`      AS `customer_state`,
                c.`pincode`    AS `customer_pincode`,
                rj.`tracking_id` AS `repair_tracking_id`,
                d.`brand`      AS `device_brand`,
                d.`model`      AS `device_model`,
                t.`name`       AS `template_name`,
                u.`name`       AS `creator_name`
            FROM `invoices` i
            JOIN `customers` c ON c.`id` = i.`customer_id`
            LEFT JOIN `repair_jobs` rj ON rj.`id` = i.`repair_job_id`
            LEFT JOIN `devices` d ON d.`id` = rj.`device_id`
            LEFT JOIN `invoice_templates` t ON t.`template_key` = i.`template_key`
            LEFT JOIN `users` u ON u.`id` = i.`created_by`
            {$whereSql}
            ORDER BY i.`id` DESC
            LIMIT :limit OFFSET :offset
        ";

        $db = Database::get();
        $stmt = $db->prepare($sql);

        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->bindValue(':limit',  $limit,  PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Count invoices with optional filters
     */
    public static function count(?string $status = null, ?string $search = null): int
    {
        $params = [];
        $where  = [];

        if ($status && array_key_exists($status, self::STATUSES)) {
            $where[] = 'i.`status` = :status';
            $params[':status'] = $status;
        }

        if ($search) {
            $where[] = '(i.`invoice_number` LIKE :search OR c.`name` LIKE :search OR c.`phone` LIKE :search OR rj.`tracking_id` LIKE :search)';
            $params[':search'] = "%{$search}%";
        }

        $whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        $sql = "
            SELECT COUNT(*)
            FROM `invoices` i
            JOIN `customers` c ON c.`id` = i.`customer_id`
            LEFT JOIN `repair_jobs` rj ON rj.`id` = i.`repair_job_id`
            {$whereSql}
        ";

        return (int)Database::fetchValue($sql, $params);
    }

    /**
     * Find single invoice by primary ID with full relations
     */
    public static function findById(int $id): ?array
    {
        $sql = "
            SELECT
                i.*,
                c.`name`       AS `customer_name`,
                c.`phone`      AS `customer_phone`,
                c.`email`      AS `customer_email`,
                c.`address`    AS `customer_address`,
                c.`city`       AS `customer_city`,
                c.`state`      AS `customer_state`,
                c.`pincode`    AS `customer_pincode`,
                rj.`tracking_id` AS `repair_tracking_id`,
                rj.`problem_description` AS `repair_problem`,
                rj.`diagnosis` AS `repair_diagnosis`,
                d.`brand`      AS `device_brand`,
                d.`model`      AS `device_model`,
                d.`serial_number` AS `device_serial`,
                t.`name`       AS `template_name`,
                t.`accent_color` AS `template_accent_color`,
                t.`paper_size` AS `template_paper_size`,
                u.`name`       AS `creator_name`
            FROM `invoices` i
            JOIN `customers` c ON c.`id` = i.`customer_id`
            LEFT JOIN `repair_jobs` rj ON rj.`id` = i.`repair_job_id`
            LEFT JOIN `devices` d ON d.`id` = rj.`device_id`
            LEFT JOIN `invoice_templates` t ON t.`template_key` = i.`template_key`
            LEFT JOIN `users` u ON u.`id` = i.`created_by`
            WHERE i.`id` = :id
            LIMIT 1
        ";

        $invoice = Database::fetch($sql, [':id' => $id]);
        if (!$invoice) {
            return null;
        }

        $invoice['items'] = InvoiceItem::getByInvoiceId($id);
        return $invoice;
    }

    /**
     * Find invoice by invoice number (e.g. INV-2026-1001)
     */
    public static function findByInvoiceNumber(string $invoiceNumber): ?array
    {
        $invoiceNumber = trim($invoiceNumber);
        $row = Database::fetch("SELECT `id` FROM `invoices` WHERE `invoice_number` = ? LIMIT 1", [$invoiceNumber]);
        if (!$row) {
            return null;
        }
        return self::findById((int)$row['id']);
    }

    /**
     * Find invoice by linked repair job ID
     */
    public static function findByRepairJobId(int $repairJobId): ?array
    {
        $row = Database::fetch("SELECT `id` FROM `invoices` WHERE `repair_job_id` = ? ORDER BY `id` DESC LIMIT 1", [$repairJobId]);
        if (!$row) {
            return null;
        }
        return self::findById((int)$row['id']);
    }

    /**
     * Create invoice with line items in transaction
     */
    public static function create(array $data, array $items = []): int
    {
        $db = Database::get();
        $db->beginTransaction();

        try {
            $sql = "
                INSERT INTO `invoices` (
                    `invoice_number`, `repair_job_id`, `customer_id`, `template_key`,
                    `invoice_date`, `due_date`, `status`, `currency`, `currency_symbol`,
                    `subtotal`, `discount_type`, `discount_value`, `discount_amount`,
                    `tax_name`, `tax_rate`, `tax_amount`, `shipping_or_handling`,
                    `total_amount`, `paid_amount`, `balance_due`, `payment_method`,
                    `payment_reference`, `notes`, `terms_conditions`, `customer_notes`,
                    `payment_qr_data`, `created_by`
                ) VALUES (
                    :invoice_number, :repair_job_id, :customer_id, :template_key,
                    :invoice_date, :due_date, :status, :currency, :currency_symbol,
                    :subtotal, :discount_type, :discount_value, :discount_amount,
                    :tax_name, :tax_rate, :tax_amount, :shipping_or_handling,
                    :total_amount, :paid_amount, :balance_due, :payment_method,
                    :payment_reference, :notes, :terms_conditions, :customer_notes,
                    :payment_qr_data, :created_by
                )
            ";

            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':invoice_number'       => $data['invoice_number'],
                ':repair_job_id'        => $data['repair_job_id'] ?? null,
                ':customer_id'          => (int)$data['customer_id'],
                ':template_key'         => $data['template_key'] ?? 'modern',
                ':invoice_date'         => $data['invoice_date'] ?? date('Y-m-d'),
                ':due_date'             => $data['due_date'] ?? null,
                ':status'               => $data['status'] ?? self::STATUS_ISSUED,
                ':currency'             => $data['currency'] ?? 'INR',
                ':currency_symbol'      => $data['currency_symbol'] ?? '₹',
                ':subtotal'             => (float)($data['subtotal'] ?? 0.0),
                ':discount_type'        => $data['discount_type'] ?? 'fixed',
                ':discount_value'       => (float)($data['discount_value'] ?? 0.0),
                ':discount_amount'      => (float)($data['discount_amount'] ?? 0.0),
                ':tax_name'             => $data['tax_name'] ?? 'GST',
                ':tax_rate'             => (float)($data['tax_rate'] ?? 0.0),
                ':tax_amount'           => (float)($data['tax_amount'] ?? 0.0),
                ':shipping_or_handling' => (float)($data['shipping_or_handling'] ?? 0.0),
                ':total_amount'         => (float)($data['total_amount'] ?? 0.0),
                ':paid_amount'          => (float)($data['paid_amount'] ?? 0.0),
                ':balance_due'          => (float)($data['balance_due'] ?? 0.0),
                ':payment_method'       => $data['payment_method'] ?? 'cash',
                ':payment_reference'    => $data['payment_reference'] ?? null,
                ':notes'                => $data['notes'] ?? null,
                ':terms_conditions'     => $data['terms_conditions'] ?? null,
                ':customer_notes'       => $data['customer_notes'] ?? null,
                ':payment_qr_data'      => $data['payment_qr_data'] ?? null,
                ':created_by'           => $data['created_by'] ?? null,
            ]);

            $invoiceId = (int)$db->lastInsertId();

            if (!empty($items)) {
                InvoiceItem::replaceForInvoice($invoiceId, $items);
            }

            $db->commit();
            return $invoiceId;
        } catch (\Throwable $e) {
            $db->rollBack();
            throw $e;
        }
    }

    /**
     * Update invoice details and optionally replace line items
     */
    public static function update(int $id, array $data, ?array $items = null): bool
    {
        $db = Database::get();
        $db->beginTransaction();

        try {
            $fields = [];
            $params = [':id' => $id];

            $allowed = [
                'template_key', 'invoice_date', 'due_date', 'status', 'currency', 'currency_symbol',
                'subtotal', 'discount_type', 'discount_value', 'discount_amount',
                'tax_name', 'tax_rate', 'tax_amount', 'shipping_or_handling',
                'total_amount', 'paid_amount', 'balance_due', 'payment_method',
                'payment_reference', 'notes', 'terms_conditions', 'customer_notes', 'payment_qr_data'
            ];

            foreach ($allowed as $field) {
                if (array_key_exists($field, $data)) {
                    $fields[] = "`{$field}` = :{$field}";
                    $params[":{$field}"] = $data[$field];
                }
            }

            if (!empty($fields)) {
                $sql = "UPDATE `invoices` SET " . implode(', ', $fields) . " WHERE `id` = :id";
                $stmt = $db->prepare($sql);
                $stmt->execute($params);
            }

            if ($items !== null) {
                InvoiceItem::replaceForInvoice($id, $items);
            }

            $db->commit();
            return true;
        } catch (\Throwable $e) {
            $db->rollBack();
            throw $e;
        }
    }

    /**
     * Delete an invoice
     */
    public static function delete(int $id): bool
    {
        return Database::execute("DELETE FROM `invoices` WHERE `id` = ?", [$id]) > 0;
    }

    /**
     * Record a payment against an invoice and update balance/status
     */
    public static function recordPayment(int $id, float $amount, string $method = 'cash', ?string $ref = null, ?string $note = null): bool
    {
        $invoice = self::findById($id);
        if (!$invoice) {
            return false;
        }

        $newPaid    = round((float)$invoice['paid_amount'] + $amount, 2);
        $total      = (float)$invoice['total_amount'];
        $newBalance = max(0.0, round($total - $newPaid, 2));

        $newStatus = ($newBalance <= 0.001) ? self::STATUS_PAID : self::STATUS_PARTIALLY_PAID;

        // If invoice is linked to a repair job, record into payments table too
        if (!empty($invoice['repair_job_id'])) {
            Payment::create([
                'repair_job_id'  => (int)$invoice['repair_job_id'],
                'amount'         => $amount,
                'payment_method' => $method,
                'transaction_id' => $ref,
                'payment_status' => ($newBalance <= 0.001) ? 'paid' : 'partial',
                'note'           => $note ?: ('Payment for Invoice #' . $invoice['invoice_number']),
                'paid_at'        => date('Y-m-d H:i:s'),
                'created_by'     => Session::userId() ?: null,
            ]);
        }

        // Regenerate UPI QR code for remaining balance
        $upiId = (string)Setting::get('billing_upi_id', 'techfix@sbi');
        $payee = (string)Setting::get('billing_upi_payee_name', site_name());
        $qrService = new InvoiceService();
        $qrData = $qrService->generateUpiQrUrl($upiId, $payee, $newBalance, $invoice['invoice_number']);

        return self::update($id, [
            'paid_amount'       => $newPaid,
            'balance_due'       => $newBalance,
            'status'            => $newStatus,
            'payment_method'    => $method,
            'payment_reference' => $ref ?: $invoice['payment_reference'],
            'payment_qr_data'   => $qrData,
        ]);
    }

    /**
     * Get aggregate billing metrics for admin dashboard
     */
    public static function getStats(): array
    {
        $sql = "
            SELECT
                COUNT(*)                                                      AS total_count,
                COALESCE(SUM(`total_amount`), 0.00)                           AS total_invoiced,
                COALESCE(SUM(`paid_amount`), 0.00)                            AS total_collected,
                COALESCE(SUM(`balance_due`), 0.00)                            AS total_due,
                COALESCE(SUM(CASE WHEN `status` = 'paid' THEN 1 ELSE 0 END), 0) AS paid_count,
                COALESCE(SUM(CASE WHEN `status` = 'issued' THEN 1 ELSE 0 END), 0) AS issued_count,
                COALESCE(SUM(CASE WHEN `status` = 'partially_paid' THEN 1 ELSE 0 END), 0) AS partial_count,
                COALESCE(SUM(CASE WHEN `status` = 'draft' THEN 1 ELSE 0 END), 0) AS draft_count
            FROM `invoices`
        ";

        return Database::fetch($sql) ?: [
            'total_count'     => 0,
            'total_invoiced'  => 0.00,
            'total_collected' => 0.00,
            'total_due'       => 0.00,
            'paid_count'      => 0,
            'issued_count'    => 0,
            'partial_count'   => 0,
            'draft_count'     => 0,
        ];
    }
}
