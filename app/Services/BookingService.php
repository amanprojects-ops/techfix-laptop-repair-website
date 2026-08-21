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
                'brand'                => $data['device_brand']         ?? $data['brand'] ?? 'Unknown',
                'model'                => $data['device_model']         ?? $data['model'] ?? null,
                'serial_number'        => $data['serial_number']        ?? $data['device_serial'] ?? null,
                'color'                => $data['device_color']         ?? $data['color'] ?? null,
                'password_required'    => !empty($data['password_required']) ? 1 : 0,
                'device_password_hint' => $data['device_password_hint'] ?? $data['lock_pattern'] ?? null,
                'accessories'          => $data['accessories']          ?? $data['accessories_included'] ?? null,
                'physical_condition'   => $data['physical_condition']   ?? null,
            ]);

            // 3. Generate tracking ID
            $trackingId = $this->idGenerator->generateUnique();

            // 4. Create repair job
            $estimatedAmt = !empty($data['estimated_amount']) ? (float)$data['estimated_amount'] : (!empty($data['estimated_cost']) ? (float)$data['estimated_cost'] : null);
            $repairId = RepairJob::create([
                'tracking_id'             => $trackingId,
                'customer_id'             => $customerId,
                'device_id'               => $deviceId,
                'service_id'              => !empty($data['service_id']) ? (int)$data['service_id'] : null,
                'assigned_technician_id'  => !empty($data['technician_id']) ? (int)$data['technician_id'] : null,
                'problem_description'     => $data['problem_description'],
                'estimated_amount'        => $estimatedAmt,
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

            // 6. Record advance payment if provided
            $advanceAmt = !empty($data['advance_amount']) ? (float)$data['advance_amount'] : 0;
            if ($advanceAmt > 0) {
                \App\Models\Payment::create([
                    'repair_job_id'  => $repairId,
                    'amount'         => $advanceAmt,
                    'payment_method' => $data['advance_payment_method'] ?? 'cash',
                    'transaction_id' => $data['advance_transaction_id'] ?? null,
                    'payment_status' => 'paid',
                    'note'           => 'Advance payment at intake',
                    'paid_at'        => date('Y-m-d H:i:s'),
                    'created_by'     => Session::userId(),
                ]);
            }

            Database::commit();

            // 7. Send admin notification email if enabled in system settings
            try {
                if ((string)setting('notify_on_new_booking', '1') === '1') {
                    $adminEmail = (string)setting('admin_notification_email', setting('contact_email', ''));
                    if (!empty($adminEmail)) {
                        $mailer = new \App\Services\MailService();
                        $custName = htmlspecialchars($data['customer_name'] ?? 'Customer', ENT_QUOTES);
                        $custPhone = htmlspecialchars($data['customer_phone'] ?? '', ENT_QUOTES);
                        $brand = htmlspecialchars($data['device_brand'] ?? 'Device', ENT_QUOTES);
                        $problem = nl2br(htmlspecialchars($data['problem_description'] ?? '', ENT_QUOTES));
                        $site = htmlspecialchars(site_name(), ENT_QUOTES);
                        
                        $subject = "⚡ [{$trackingId}] New Repair Intake — {$custName}";
                        $body = "
                        <div style=\"font-family: Arial, sans-serif; max-width: 600px; padding: 20px; border: 1px solid #e2e8f0; border-radius: 8px;\">
                            <h2 style=\"color: #2563eb; margin-top: 0;\">New Repair Intake — {$site}</h2>
                            <p>A new repair booking has been submitted:</p>
                            <table style=\"width: 100%; border-collapse: collapse; margin: 15px 0;\">
                                <tr style=\"background: #f8fafc;\"><td style=\"padding: 8px; font-weight: bold; width: 35%; border: 1px solid #e2e8f0;\">Tracking ID</td><td style=\"padding: 8px; border: 1px solid #e2e8f0; color: #2563eb; font-weight: bold;\">{$trackingId}</td></tr>
                                <tr><td style=\"padding: 8px; font-weight: bold; border: 1px solid #e2e8f0;\">Customer Name</td><td style=\"padding: 8px; border: 1px solid #e2e8f0;\">{$custName}</td></tr>
                                <tr style=\"background: #f8fafc;\"><td style=\"padding: 8px; font-weight: bold; border: 1px solid #e2e8f0;\">Phone Number</td><td style=\"padding: 8px; border: 1px solid #e2e8f0;\">{$custPhone}</td></tr>
                                <tr><td style=\"padding: 8px; font-weight: bold; border: 1px solid #e2e8f0;\">Device Brand</td><td style=\"padding: 8px; border: 1px solid #e2e8f0;\">{$brand}</td></tr>
                                <tr style=\"background: #f8fafc;\"><td style=\"padding: 8px; font-weight: bold; border: 1px solid #e2e8f0;\">Problem</td><td style=\"padding: 8px; border: 1px solid #e2e8f0;\">{$problem}</td></tr>
                            </table>
                            <p><a href=\"" . url('/admin/repairs/' . $repairId) . "\" style=\"display: inline-block; padding: 10px 18px; background: #2563eb; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: bold;\">Open Repair Job in Admin &rarr;</a></p>
                        </div>";

                        $mailer->send($adminEmail, $subject, $body);
                    }
                }
            } catch (\Throwable) {
                // Ignore background notification exceptions to avoid failing user transaction
            }

            return $trackingId;

        } catch (\Throwable $e) {
            Database::rollBack();
            throw $e;
        }
    }
}
