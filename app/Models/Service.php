<?php

namespace App\Models;

use App\Core\Database;

class Service
{
    public static function all(bool $activeOnly = true): array
    {
        $sql = 'SELECT * FROM services';
        if ($activeOnly) $sql .= ' WHERE status = "active"';
        $sql .= ' ORDER BY sort_order ASC, name ASC';
        return Database::fetchAll($sql);
    }

    public static function findById(int $id): array|false
    {
        return Database::fetchOne('SELECT * FROM services WHERE id = :id LIMIT 1', ['id' => $id]);
    }

    public static function findBySlug(string $slug): array|false
    {
        return Database::fetchOne('SELECT * FROM services WHERE slug = :slug LIMIT 1', ['slug' => $slug]);
    }

    public static function create(array $data): int
    {
        Database::query(
            'INSERT INTO services (name, slug, short_description, full_description, starting_price, estimated_days, warranty_days, icon, seo_title, seo_description, sort_order, status)
             VALUES (:name, :slug, :short_description, :full_description, :starting_price, :estimated_days, :warranty_days, :icon, :seo_title, :seo_description, :sort_order, :status)',
            [
                'name'              => $data['name'],
                'slug'              => $data['slug'],
                'short_description' => $data['short_description'] ?? null,
                'full_description'  => $data['full_description']  ?? null,
                'starting_price'    => $data['starting_price']    ?? 0,
                'estimated_days'    => $data['estimated_days']    ?? 1,
                'warranty_days'     => $data['warranty_days']     ?? 90,
                'icon'              => $data['icon']              ?? null,
                'seo_title'         => $data['seo_title']         ?? null,
                'seo_description'   => $data['seo_description']   ?? null,
                'sort_order'        => $data['sort_order']        ?? 0,
                'status'            => $data['status']            ?? 'active',
            ]
        );
        return (int)Database::lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        Database::query(
            'UPDATE services SET name=:name, short_description=:short_description, starting_price=:starting_price, status=:status WHERE id=:id',
            ['name' => $data['name'], 'short_description' => $data['short_description'], 'starting_price' => $data['starting_price'], 'status' => $data['status'], 'id' => $id]
        );
    }

    public static function delete(int $id): void
    {
        Database::query('UPDATE services SET status = "inactive" WHERE id = :id', ['id' => $id]);
    }
}
