<?php

namespace App\Models;

use App\Core\Database;

class Device
{
    public static function create(array $data): int
    {
        Database::query(
            'INSERT INTO devices (customer_id, device_type, brand, model, serial_number, color, password_required, device_password_hint, accessories, physical_condition)
             VALUES (:customer_id, :device_type, :brand, :model, :serial_number, :color, :password_required, :device_password_hint, :accessories, :physical_condition)',
            [
                'customer_id'          => $data['customer_id'],
                'device_type'          => $data['device_type']          ?? 'laptop',
                'brand'                => $data['brand'],
                'model'                => $data['model']                ?? null,
                'serial_number'        => $data['serial_number']        ?? null,
                'color'                => $data['color']                ?? null,
                'password_required'    => $data['password_required']    ?? 0,
                'device_password_hint' => $data['device_password_hint'] ?? null,
                'accessories'          => $data['accessories']          ?? null,
                'physical_condition'   => $data['physical_condition']   ?? null,
            ]
        );
        return (int)Database::lastInsertId();
    }

    public static function findById(int $id): array|false
    {
        return Database::fetchOne('SELECT * FROM devices WHERE id = :id LIMIT 1', ['id' => $id]);
    }

    public static function findByCustomer(int $customerId): array
    {
        return Database::fetchAll('SELECT * FROM devices WHERE customer_id = :cid ORDER BY created_at DESC', ['cid' => $customerId]);
    }
}
