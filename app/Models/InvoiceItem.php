<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class InvoiceItem
{
    /**
     * Retrieve all items for a given invoice ordered by sort_order / id
     */
    public static function getByInvoiceId(int $invoiceId): array
    {
        return Database::fetchAll(
            "SELECT * FROM `invoice_items` WHERE `invoice_id` = ? ORDER BY `sort_order` ASC, `id` ASC",
            [$invoiceId]
        );
    }

    /**
     * Replace all items for an invoice in a single operation
     */
    public static function replaceForInvoice(int $invoiceId, array $items): void
    {
        $db = Database::get();

        // Delete existing items
        $db->prepare("DELETE FROM `invoice_items` WHERE `invoice_id` = ?")->execute([$invoiceId]);

        if (empty($items)) {
            return;
        }

        $stmt = $db->prepare("
            INSERT INTO `invoice_items` (
                `invoice_id`, `item_type`, `item_name`, `description`,
                `quantity`, `unit_price`, `total_price`, `sort_order`
            ) VALUES (
                :invoice_id, :item_type, :item_name, :description,
                :quantity, :unit_price, :total_price, :sort_order
            )
        ");

        $sortOrder = 0;
        foreach ($items as $item) {
            $name = trim((string)($item['item_name'] ?? ''));
            if ($name === '') {
                continue;
            }

            $qty   = max(0.01, (float)($item['quantity'] ?? 1.0));
            $unit  = max(0.0, (float)($item['unit_price'] ?? 0.0));
            $total = (float)($item['total_price'] ?? ($qty * $unit));

            $stmt->execute([
                ':invoice_id'   => $invoiceId,
                ':item_type'    => $item['item_type'] ?? 'service',
                ':item_name'    => $name,
                ':description'  => !empty($item['description']) ? trim((string)$item['description']) : null,
                ':quantity'     => $qty,
                ':unit_price'   => $unit,
                ':total_price'  => $total,
                ':sort_order'   => (int)($item['sort_order'] ?? $sortOrder++),
            ]);
        }
    }
}
