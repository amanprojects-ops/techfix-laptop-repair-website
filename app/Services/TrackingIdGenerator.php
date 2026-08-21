<?php

namespace App\Services;

use App\Core\Database;

class TrackingIdGenerator
{
    private string $prefix;

    public function __construct()
    {
        $this->prefix = $_ENV['TRACKING_PREFIX'] ?? 'AMN-LR';
    }

    /**
     * Generate next tracking ID: AMN-LR-260001
     * Year is 2-digit, sequence is zero-padded to 4 digits.
     */
    public function generate(): string
    {
        $year = date('y'); // 2-digit year

        // Get max ID for this year
        $row = Database::fetchOne(
            "SELECT MAX(CAST(SUBSTRING_INDEX(tracking_id, '-', -1) AS UNSIGNED)) AS max_seq
             FROM repair_jobs
             WHERE tracking_id LIKE :prefix",
            ['prefix' => $this->prefix . '-' . $year . '%']
        );

        $nextSeq = (int)($row['max_seq'] ?? 0) + 1;

        return $this->prefix . '-' . $year . str_pad($nextSeq, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Generate and verify uniqueness (retry on collision)
     */
    public function generateUnique(): string
    {
        for ($i = 0; $i < 5; $i++) {
            $id = $this->generate();
            $exists = Database::fetchOne(
                'SELECT id FROM repair_jobs WHERE tracking_id = :tid LIMIT 1',
                ['tid' => $id]
            );
            if (!$exists) {
                return $id;
            }
        }
        // Fallback: random suffix
        return $this->prefix . '-' . date('y') . strtoupper(bin2hex(random_bytes(2)));
    }
}
