<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class InvoiceTemplate
{
    /**
     * Get all templates
     */
    public static function all(): array
    {
        return Database::fetchAll("SELECT * FROM `invoice_templates` ORDER BY `is_system` DESC, `id` ASC");
    }

    /**
     * Get all active templates
     */
    public static function allActive(): array
    {
        return Database::fetchAll("SELECT * FROM `invoice_templates` WHERE `is_active` = 1 ORDER BY `is_system` DESC, `id` ASC");
    }

    /**
     * Find template by template_key (e.g. 'modern', 'classic', 'thermal_pos', 'techfix_neon', 'executive_clean')
     */
    public static function findByKey(string $key): ?array
    {
        return Database::fetch("SELECT * FROM `invoice_templates` WHERE `template_key` = ? LIMIT 1", [$key]);
    }

    /**
     * Find template by ID
     */
    public static function findById(int $id): ?array
    {
        return Database::fetch("SELECT * FROM `invoice_templates` WHERE `id` = ? LIMIT 1", [$id]);
    }

    /**
     * Create a new custom template
     */
    public static function create(array $data): int
    {
        $sql = "
            INSERT INTO `invoice_templates` (
                `template_key`, `name`, `description`, `is_system`, `is_active`,
                `paper_size`, `accent_color`, `secondary_color`, `font_family`,
                `show_watermark`, `watermark_text`, `show_qr_code`, `show_signature`,
                `show_tax_breakup`, `show_bank_details`, `header_layout`, `custom_css`,
                `terms_default`, `notes_default`
            ) VALUES (
                :template_key, :name, :description, :is_system, :is_active,
                :paper_size, :accent_color, :secondary_color, :font_family,
                :show_watermark, :watermark_text, :show_qr_code, :show_signature,
                :show_tax_breakup, :show_bank_details, :header_layout, :custom_css,
                :terms_default, :notes_default
            )
        ";

        $db = Database::get();
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':template_key'       => preg_replace('/[^a-z0-9_]/', '', strtolower(trim((string)$data['template_key']))),
            ':name'               => trim((string)$data['name']),
            ':description'        => !empty($data['description']) ? trim((string)$data['description']) : null,
            ':is_system'          => 0,
            ':is_active'          => !empty($data['is_active']) ? 1 : 0,
            ':paper_size'         => $data['paper_size'] ?? 'A4',
            ':accent_color'       => $data['accent_color'] ?? '#2563EB',
            ':secondary_color'    => $data['secondary_color'] ?? '#0F172A',
            ':font_family'        => $data['font_family'] ?? 'Inter, sans-serif',
            ':show_watermark'     => !empty($data['show_watermark']) ? 1 : 0,
            ':watermark_text'     => trim((string)($data['watermark_text'] ?? 'PAID')),
            ':show_qr_code'       => !empty($data['show_qr_code']) ? 1 : 0,
            ':show_signature'     => !empty($data['show_signature']) ? 1 : 0,
            ':show_tax_breakup'   => !empty($data['show_tax_breakup']) ? 1 : 0,
            ':show_bank_details'  => !empty($data['show_bank_details']) ? 1 : 0,
            ':header_layout'      => $data['header_layout'] ?? 'standard',
            ':custom_css'         => !empty($data['custom_css']) ? trim((string)$data['custom_css']) : null,
            ':terms_default'      => !empty($data['terms_default']) ? trim((string)$data['terms_default']) : null,
            ':notes_default'      => !empty($data['notes_default']) ? trim((string)$data['notes_default']) : null,
        ]);

        return (int)$db->lastInsertId();
    }

    /**
     * Update an existing template (system or custom)
     */
    public static function update(int $id, array $data): bool
    {
        $fields = [];
        $params = [':id' => $id];

        $allowed = [
            'name', 'description', 'is_active', 'paper_size', 'accent_color', 'secondary_color',
            'font_family', 'show_watermark', 'watermark_text', 'show_qr_code', 'show_signature',
            'show_tax_breakup', 'show_bank_details', 'header_layout', 'custom_css',
            'terms_default', 'notes_default'
        ];

        foreach ($allowed as $field) {
            if (array_key_exists($field, $data)) {
                $fields[] = "`{$field}` = :{$field}";
                $params[":{$field}"] = $data[$field];
            }
        }

        if (empty($fields)) {
            return false;
        }

        $sql = "UPDATE `invoice_templates` SET " . implode(', ', $fields) . " WHERE `id` = :id";
        return Database::execute($sql, $params) > 0;
    }

    /**
     * Delete custom template (system templates cannot be deleted)
     */
    public static function delete(int $id): bool
    {
        return Database::execute("DELETE FROM `invoice_templates` WHERE `id` = ? AND `is_system` = 0", [$id]) > 0;
    }
}
