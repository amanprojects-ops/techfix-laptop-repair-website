<?php

namespace App\Models;

use App\Core\Database;

class User
{
    public static function findByEmail(string $email): array|false
    {
        return Database::fetchOne(
            'SELECT * FROM users WHERE email = :email AND status = "active" LIMIT 1',
            ['email' => $email]
        );
    }

    public static function findById(int $id): array|false
    {
        return Database::fetchOne(
            'SELECT id, name, email, phone, role, status, created_at FROM users WHERE id = :id LIMIT 1',
            ['id' => $id]
        );
    }

    public static function allTechnicians(): array
    {
        return Database::fetchAll(
            'SELECT id, name, email, phone, status FROM users WHERE role = "technician" ORDER BY name'
        );
    }

    public static function all(): array
    {
        return Database::fetchAll(
            'SELECT id, name, email, phone, role, status, created_at FROM users ORDER BY name'
        );
    }

    public static function create(array $data): int
    {
        Database::query(
            'INSERT INTO users (name, email, phone, password, role, status) VALUES (:name, :email, :phone, :password, :role, :status)',
            [
                'name'     => $data['name'],
                'email'    => $data['email'],
                'phone'    => $data['phone']    ?? '',
                'password' => password_hash($data['password'], PASSWORD_BCRYPT),
                'role'     => $data['role']     ?? 'technician',
                'status'   => $data['status']   ?? 'active',
            ]
        );
        return (int)Database::lastInsertId();
    }

    public static function updateStatus(int $id, string $status): void
    {
        Database::query('UPDATE users SET status = :status WHERE id = :id', ['status' => $status, 'id' => $id]);
    }

    public static function verifyPassword(string $plain, string $hash): bool
    {
        return password_verify($plain, $hash);
    }
}
