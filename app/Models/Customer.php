<?php

namespace App\Models;

use App\Core\Database;

class Customer
{
    public static function findByPhone(string $phone): array|false
    {
        return Database::fetchOne(
            'SELECT * FROM customers WHERE phone = :phone LIMIT 1',
            ['phone' => $phone]
        );
    }

    public static function findById(int $id): array|false
    {
        return Database::fetchOne('SELECT * FROM customers WHERE id = :id LIMIT 1', ['id' => $id]);
    }

    public static function all(int $limit = 50, int $offset = 0): array
    {
        return Database::fetchAll(
            'SELECT c.*, COUNT(r.id) as total_jobs
             FROM customers c
             LEFT JOIN repair_jobs r ON r.customer_id = c.id
             GROUP BY c.id
             ORDER BY c.created_at DESC
             LIMIT :limit OFFSET :offset',
            ['limit' => $limit, 'offset' => $offset]
        );
    }

    public static function count(): int
    {
        $row = Database::fetchOne('SELECT COUNT(*) as cnt FROM customers');
        return (int)($row['cnt'] ?? 0);
    }

    public static function create(array $data): int
    {
        Database::query(
            'INSERT INTO customers (name, phone, email, address, city, state, pincode)
             VALUES (:name, :phone, :email, :address, :city, :state, :pincode)',
            [
                'name'    => $data['name'],
                'phone'   => $data['phone'],
                'email'   => $data['email']   ?? null,
                'address' => $data['address'] ?? null,
                'city'    => $data['city']    ?? null,
                'state'   => $data['state']   ?? null,
                'pincode' => $data['pincode'] ?? null,
            ]
        );
        return (int)Database::lastInsertId();
    }

    public static function findOrCreate(array $data): int
    {
        $existing = self::findByPhone($data['phone']);
        if ($existing) {
            return (int)$existing['id'];
        }
        return self::create($data);
    }

    public static function search(string $q): array
    {
        $like = '%' . $q . '%';
        return Database::fetchAll(
            'SELECT * FROM customers WHERE name LIKE :q OR phone LIKE :q2 OR email LIKE :q3 ORDER BY name LIMIT 20',
            ['q' => $like, 'q2' => $like, 'q3' => $like]
        );
    }
}
