<?php

namespace App\Services;

use App\Models\RepairJob;
use App\Models\RepairStatusHistory;
use App\Core\Session;

class RepairService
{
    /**
     * Change repair status — validates the transition is allowed.
     * Throws on invalid transition.
     */
    public function changeStatus(int $repairId, string $newStatus, string $note = ''): void
    {
        $repair = RepairJob::findById($repairId);
        if (!$repair) {
            throw new \InvalidArgumentException("Repair job #{$repairId} not found.");
        }

        $current = $repair['current_status'];

        if (!RepairJob::isValidTransition($current, $newStatus)) {
            throw new \InvalidArgumentException(
                "Invalid status transition: {$current} → {$newStatus}"
            );
        }

        RepairJob::updateStatus($repairId, $newStatus);

        RepairStatusHistory::add(
            $repairId,
            $newStatus,
            $note ?: RepairJob::statusLabel($newStatus),
            Session::userId()
        );
    }

    /**
     * Force-set status (admin override — skips transition validation)
     */
    public function forceStatus(int $repairId, string $newStatus, string $note = ''): void
    {
        RepairJob::updateStatus($repairId, $newStatus);
        RepairStatusHistory::add($repairId, $newStatus, $note, Session::userId());
    }
}
