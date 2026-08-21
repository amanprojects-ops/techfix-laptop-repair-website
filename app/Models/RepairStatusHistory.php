<?php

namespace App\Models;

use App\Core\Database;

class RepairStatusHistory
{
    public static function add(int $repairJobId, string $status, string $note = '', int|null $changedBy = null): int
    {
        Database::query(
            'INSERT INTO repair_status_history (repair_job_id, status, note, changed_by) VALUES (:repair_job_id, :status, :note, :changed_by)',
            ['repair_job_id' => $repairJobId, 'status' => $status, 'note' => $note, 'changed_by' => $changedBy]
        );
        return (int)Database::lastInsertId();
    }

    public static function getByRepairJob(int $repairJobId): array
    {
        return Database::fetchAll(
            'SELECT h.*, u.name AS changed_by_name
             FROM repair_status_history h
             LEFT JOIN users u ON u.id = h.changed_by
             WHERE h.repair_job_id = :id
             ORDER BY h.created_at ASC',
            ['id' => $repairJobId]
        );
    }
}
