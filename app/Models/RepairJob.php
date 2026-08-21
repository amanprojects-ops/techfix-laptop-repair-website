<?php

namespace App\Models;

use App\Core\Database;

class RepairJob
{
    public const STATUSES = [
        'RECEIVED'         => 'Device Received',
        'DIAGNOSIS'        => 'Under Diagnosis',
        'WAITING_APPROVAL' => 'Waiting for Approval',
        'APPROVED'         => 'Approved',
        'IN_REPAIR'        => 'In Repair',
        'QUALITY_CHECK'    => 'Quality Check',
        'READY_FOR_PICKUP' => 'Ready for Pickup',
        'DELIVERED'        => 'Delivered',
        'CANCELLED'        => 'Cancelled',
        'ON_HOLD'          => 'On Hold',
        'PARTS_PENDING'    => 'Parts Pending',
        'UNREPAIRABLE'     => 'Unrepairable',
    ];

    public const PRIORITY_LABELS = [
        'low'    => 'Low',
        'normal' => 'Normal',
        'high'   => 'High',
        'urgent' => 'Urgent',
    ];

    // Valid forward transitions
    public const TRANSITIONS = [
        'RECEIVED'         => ['DIAGNOSIS', 'CANCELLED'],
        'DIAGNOSIS'        => ['WAITING_APPROVAL', 'APPROVED', 'UNREPAIRABLE', 'ON_HOLD'],
        'WAITING_APPROVAL' => ['APPROVED', 'CANCELLED'],
        'APPROVED'         => ['IN_REPAIR', 'PARTS_PENDING'],
        'IN_REPAIR'        => ['QUALITY_CHECK', 'PARTS_PENDING', 'ON_HOLD'],
        'QUALITY_CHECK'    => ['READY_FOR_PICKUP', 'IN_REPAIR'],
        'READY_FOR_PICKUP' => ['DELIVERED'],
        'PARTS_PENDING'    => ['IN_REPAIR', 'ON_HOLD', 'CANCELLED'],
        'ON_HOLD'          => ['IN_REPAIR', 'CANCELLED'],
        'DELIVERED'        => [],
        'CANCELLED'        => [],
        'UNREPAIRABLE'     => [],
    ];

    public static function create(array $data): int
    {
        Database::query(
            'INSERT INTO repair_jobs
             (tracking_id, customer_id, device_id, service_id, assigned_technician_id, problem_description, estimated_amount, priority, current_status, received_at, created_by)
             VALUES (:tracking_id, :customer_id, :device_id, :service_id, :assigned_technician_id, :problem_description, :estimated_amount, :priority, "RECEIVED", NOW(), :created_by)',
            [
                'tracking_id'             => $data['tracking_id'],
                'customer_id'             => $data['customer_id'],
                'device_id'               => $data['device_id'],
                'service_id'              => $data['service_id']              ?? null,
                'assigned_technician_id'  => $data['assigned_technician_id'] ?? null,
                'problem_description'     => $data['problem_description'],
                'estimated_amount'        => !empty($data['estimated_amount']) ? (float)$data['estimated_amount'] : null,
                'priority'                => $data['priority']                ?? 'normal',
                'created_by'              => $data['created_by']              ?? null,
            ]
        );
        return (int)Database::lastInsertId();
    }

    public static function findById(int $id): array|false
    {
        return Database::fetchOne(
            'SELECT r.*, c.name AS customer_name, c.phone AS customer_phone, c.email AS customer_email,
                    d.brand AS device_brand, d.model AS device_model, d.device_type,
                    s.name AS service_name,
                    u.name AS technician_name
             FROM repair_jobs r
             LEFT JOIN customers c ON c.id = r.customer_id
             LEFT JOIN devices   d ON d.id = r.device_id
             LEFT JOIN services  s ON s.id = r.service_id
             LEFT JOIN users     u ON u.id = r.assigned_technician_id
             WHERE r.id = :id LIMIT 1',
            ['id' => $id]
        );
    }

    public static function findByTrackingId(string $trackingId): array|false
    {
        return Database::fetchOne(
            'SELECT r.*, c.name AS customer_name, c.phone AS customer_phone,
                    d.brand AS device_brand, d.model AS device_model,
                    s.name AS service_name,
                    u.name AS technician_name
             FROM repair_jobs r
             LEFT JOIN customers c ON c.id = r.customer_id
             LEFT JOIN devices   d ON d.id = r.device_id
             LEFT JOIN services  s ON s.id = r.service_id
             LEFT JOIN users     u ON u.id = r.assigned_technician_id
             WHERE r.tracking_id = :tid LIMIT 1',
            ['tid' => $trackingId]
        );
    }

    public static function all(int $limit = 50, int $offset = 0, string $status = ''): array
    {
        $params = ['limit' => $limit, 'offset' => $offset];
        $where  = '';
        if ($status) {
            $where         = 'WHERE r.current_status = :status';
            $params['status'] = $status;
        }

        return Database::fetchAll(
            "SELECT r.id, r.tracking_id, r.current_status, r.priority, r.received_at, r.estimated_amount, r.final_amount,
                    c.name AS customer_name, c.phone AS customer_phone,
                    d.brand AS device_brand, d.model AS device_model,
                    s.name AS service_name,
                    u.name AS technician_name
             FROM repair_jobs r
             LEFT JOIN customers c ON c.id = r.customer_id
             LEFT JOIN devices   d ON d.id = r.device_id
             LEFT JOIN services  s ON s.id = r.service_id
             LEFT JOIN users     u ON u.id = r.assigned_technician_id
             {$where}
             ORDER BY r.created_at DESC
             LIMIT :limit OFFSET :offset",
            $params
        );
    }

    public static function count(string $status = ''): int
    {
        if ($status) {
            $row = Database::fetchOne('SELECT COUNT(*) as cnt FROM repair_jobs WHERE current_status = :s', ['s' => $status]);
        } else {
            $row = Database::fetchOne('SELECT COUNT(*) as cnt FROM repair_jobs');
        }
        return (int)($row['cnt'] ?? 0);
    }

    public static function todayJobs(): array
    {
        return Database::fetchAll(
            "SELECT r.id, r.tracking_id, r.current_status, r.priority, r.received_at, r.estimated_amount, r.final_amount,
                    c.name AS customer_name, c.phone AS customer_phone,
                    d.brand AS device_brand, d.model AS device_model,
                    s.name AS service_name,
                    u.name AS technician_name
             FROM repair_jobs r
             LEFT JOIN customers c ON c.id = r.customer_id
             LEFT JOIN devices   d ON d.id = r.device_id
             LEFT JOIN services  s ON s.id = r.service_id
             LEFT JOIN users     u ON u.id = r.assigned_technician_id
             WHERE DATE(r.received_at) = CURDATE()
             ORDER BY r.received_at DESC"
        );
    }

    public static function revenueThisMonth(): float
    {
        $row = Database::fetchOne(
            "SELECT COALESCE(SUM(final_amount), 0) AS total FROM repair_jobs
             WHERE current_status = 'DELIVERED' AND MONTH(delivered_at) = MONTH(NOW()) AND YEAR(delivered_at) = YEAR(NOW())"
        );
        return (float)($row['total'] ?? 0);
    }

    public static function update(int $id, array $data): void
    {
        $fields = [];
        $params = ['id' => $id];
        $allowed = ['diagnosis', 'estimated_amount', 'final_amount', 'assigned_technician_id', 'service_id', 'priority', 'estimated_delivery_at'];
        foreach ($allowed as $field) {
            if (array_key_exists($field, $data)) {
                $fields[] = "{$field} = :{$field}";
                $params[$field] = $data[$field];
            }
        }
        if (empty($fields)) return;
        Database::query('UPDATE repair_jobs SET ' . implode(', ', $fields) . ' WHERE id = :id', $params);
    }

    public static function updateStatus(int $id, string $status): void
    {
        $extra = '';
        if ($status === 'DELIVERED') {
            $extra = ', delivered_at = NOW()';
        } elseif (in_array($status, ['READY_FOR_PICKUP', 'DELIVERED', 'QUALITY_CHECK'])) {
            $extra = ', completed_at = NOW()';
        }
        Database::query("UPDATE repair_jobs SET current_status = :status{$extra} WHERE id = :id", ['status' => $status, 'id' => $id]);
    }

    public static function isValidTransition(string $from, string $to): bool
    {
        return in_array($to, self::TRANSITIONS[$from] ?? [], true);
    }

    public static function statusLabel(string $status): string
    {
        return self::STATUSES[$status] ?? $status;
    }
}
