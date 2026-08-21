<?php

namespace App\Models;

use App\Core\Database;

class Payment
{
    public static function create(array $data): int
    {
        Database::query(
            'INSERT INTO payments (repair_job_id, amount, payment_method, transaction_id, payment_status, note, paid_at, created_by)
             VALUES (:repair_job_id, :amount, :payment_method, :transaction_id, :payment_status, :note, :paid_at, :created_by)',
            [
                'repair_job_id'   => $data['repair_job_id'],
                'amount'          => $data['amount'],
                'payment_method'  => $data['payment_method']  ?? 'cash',
                'transaction_id'  => $data['transaction_id']  ?? null,
                'payment_status'  => $data['payment_status']  ?? 'paid',
                'note'            => $data['note']            ?? null,
                'paid_at'         => $data['paid_at']         ?? date('Y-m-d H:i:s'),
                'created_by'      => $data['created_by']      ?? null,
            ]
        );
        return (int)Database::lastInsertId();
    }

    public static function getByRepairJob(int $repairJobId): array
    {
        return Database::fetchAll(
            'SELECT p.*, u.name AS created_by_name FROM payments p
             LEFT JOIN users u ON u.id = p.created_by
             WHERE p.repair_job_id = :rid ORDER BY p.created_at DESC',
            ['rid' => $repairJobId]
        );
    }

    public static function totalPaid(int $repairJobId): float
    {
        $row = Database::fetchOne(
            'SELECT COALESCE(SUM(amount), 0) AS total FROM payments WHERE repair_job_id = :rid AND payment_status IN ("paid","partial")',
            ['rid' => $repairJobId]
        );
        return (float)($row['total'] ?? 0);
    }
}
