<?php

namespace App\Models;

use App\Core\Database;

class RepairImage
{
    public static function add(int $repairJobId, string $type, string $filePath, string $originalName, int|null $uploadedBy = null): int
    {
        Database::query(
            'INSERT INTO repair_images (repair_job_id, type, file_path, original_name, uploaded_by) VALUES (:rid, :type, :path, :orig, :uid)',
            ['rid' => $repairJobId, 'type' => $type, 'path' => $filePath, 'orig' => $originalName, 'uid' => $uploadedBy]
        );
        return (int)Database::lastInsertId();
    }

    public static function getByRepairJob(int $repairJobId): array
    {
        return Database::fetchAll(
            'SELECT * FROM repair_images WHERE repair_job_id = :rid ORDER BY created_at ASC',
            ['rid' => $repairJobId]
        );
    }

    public static function delete(int $id): array|false
    {
        $img = Database::fetchOne('SELECT * FROM repair_images WHERE id = :id LIMIT 1', ['id' => $id]);
        if ($img) {
            Database::query('DELETE FROM repair_images WHERE id = :id', ['id' => $id]);
        }
        return $img;
    }
}
