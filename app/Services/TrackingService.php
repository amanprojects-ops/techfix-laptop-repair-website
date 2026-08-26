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

        // Verify phone matches customer (compare clean last 10 digits for Indian mobile numbers)
        $cleanDbPhone    = substr((string)preg_replace('/\D/', '', $repair['customer_phone']), -10);
        $cleanInputPhone = substr((string)preg_replace('/\D/', '', $phone), -10);

        if ($cleanDbPhone !== '' && $cleanDbPhone !== $cleanInputPhone) {
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
