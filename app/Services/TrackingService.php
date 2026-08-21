<?php

namespace App\Services;

use App\Models\RepairJob;
use App\Models\RepairStatusHistory;
use App\Models\RepairImage;
use App\Models\Payment;

class TrackingService
{
    /**
     * Look up a repair by tracking ID + customer phone.
     * Returns full repair data + timeline + images + payments.
     */
    public function lookup(string $trackingId, string $phone): array|false
    {
        $repair = RepairJob::findByTrackingId(strtoupper(trim($trackingId)));

        if (!$repair) {
            return false;
        }

        // Verify phone matches customer
        if (trim($repair['customer_phone']) !== trim($phone)) {
            return false;
        }

        $repair['timeline'] = RepairStatusHistory::getByRepairJob($repair['id']);
        $repair['images']   = RepairImage::getByRepairJob($repair['id']);
        $repair['payments'] = Payment::getByRepairJob($repair['id']);
        $repair['paid']     = Payment::totalPaid($repair['id']);

        return $repair;
    }

    /**
     * Public tracking by tracking ID only (with full timeline, images & payments)
     */
    public function getByTrackingId(string $trackingId): array|false
    {
        $repair = RepairJob::findByTrackingId(strtoupper(trim($trackingId)));
        if (!$repair) return false;

        $repair['timeline'] = RepairStatusHistory::getByRepairJob($repair['id']);
        $repair['images']   = RepairImage::getByRepairJob($repair['id']);
        $repair['payments'] = Payment::getByRepairJob($repair['id']);
        $repair['paid']     = Payment::totalPaid($repair['id']);

        return $repair;
    }
}
