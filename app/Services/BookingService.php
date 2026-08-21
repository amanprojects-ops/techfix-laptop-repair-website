<?php

namespace App\Services;

use App\Core\Database;
use App\Models\Customer;
use App\Models\Device;
use App\Models\RepairJob;
use App\Models\RepairStatusHistory;
use App\Core\Session;

class BookingService
{
    private TrackingIdGenerator $idGenerator;

    public function __construct()
    {
        $this->idGenerator = new TrackingIdGenerator();
    }

    /**
     * Create a complete repair booking in a single DB transaction.
     * Returns the tracking ID on success.
     */
    public function book(array $data): string
    {
        Database::beginTransaction();

        try {
            // 1. Find or create customer
            $customerId = Customer::findOrCreate([
                'name'    => $data['customer_name'],
                'phone'   => $data['customer_phone'],
                'email'   => $data['customer_email']   ?? null,
                'address' => $data['customer_address'] ?? null,
                'city'    => $data['customer_city']    ?? null,
                'state'   => $data['customer_state']   ?? null,
                'pincode' => $data['customer_pincode'] ?? null,
            ]);

            // 2. Create device
            $deviceId = Device::create([
                'customer_id'          => $customerId,
                'device_type'          => $data['device_type']          ?? 'laptop',
                'brand'                => $data['device_brand'],
                'model'                => $data['device_model']         ?? null,
                'serial_number'        => $data['serial_number']        ?? null,
                'color'                => $data['device_color']         ?? null,
                'password_required'    => !empty($data['password_required']) ? 1 : 0,
                'device_password_hint' => $data['device_password_hint'] ?? null,
                'accessories'          => $data['accessories']          ?? null,
                'physical_condition'   => $data['physical_condition']   ?? null,
            ]);

            // 3. Generate tracking ID
            $trackingId = $this->idGenerator->generateUnique();

            // 4. Create repair job
            $repairId = RepairJob::create([
                'tracking_id'             => $trackingId,
                'customer_id'             => $customerId,
                'device_id'               => $deviceId,
                'service_id'              => !empty($data['service_id']) ? (int)$data['service_id'] : null,
                'assigned_technician_id'  => !empty($data['technician_id']) ? (int)$data['technician_id'] : null,
                'problem_description'     => $data['problem_description'],
                'priority'                => $data['priority']   ?? 'normal',
                'created_by'              => Session::userId(),
            ]);

            // 5. Add initial timeline entry
            RepairStatusHistory::add(
                $repairId,
                'RECEIVED',
                'Repair request received. Device booked in.',
                Session::userId()
            );

            Database::commit();

            return $trackingId;

        } catch (\Throwable $e) {
            Database::rollBack();
            throw $e;
        }
    }
}
