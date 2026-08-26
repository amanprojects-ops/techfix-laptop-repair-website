<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Session;
use App\Middleware\AuthMiddleware;
use App\Middleware\CsrfMiddleware;
use App\Models\Invoice;
use App\Models\RepairJob;
use App\Models\RepairStatusHistory;
use App\Models\RepairImage;
use App\Models\Payment;
use App\Models\Service;
use App\Models\User;
use App\Services\BookingService;
use App\Services\RepairService;
use App\Services\UploadService;

class RepairController extends Controller
{
    public function index(): void
    {
        AuthMiddleware::handle();

        $status  = $this->request->get('status', '');
        $page    = max(1, (int)$this->request->get('page', 1));
        $limit   = 20;
        $offset  = ($page - 1) * $limit;

        $repairs = RepairJob::all($limit, $offset, $status);
        $total   = RepairJob::count($status);
        $pages   = (int)ceil($total / $limit);
        $statuses = RepairJob::STATUSES;

        $csrfToken = Session::csrfToken();
        $user      = ['name' => Session::userName(), 'role' => Session::userRole()];

        $this->render('admin/repairs/index', compact('repairs', 'total', 'pages', 'page', 'status', 'statuses', 'csrfToken', 'user'), 'admin');
    }

    public function create(): void
    {
        AuthMiddleware::handle();

        $services     = Service::all(true);
        $technicians  = User::allTechnicians();
        $csrfToken    = Session::csrfToken();
        $flash_errors = Session::getFlash('errors', []);
        $flash_input  = Session::getFlash('input', []);
        $user         = ['name' => Session::userName(), 'role' => Session::userRole()];

        $this->render('admin/repairs/create', compact('services', 'technicians', 'csrfToken', 'flash_errors', 'flash_input', 'user'), 'admin');
    }

    public function store(): void
    {
        AuthMiddleware::handle();
        CsrfMiddleware::verify();

        $errors = $this->validate([
            'customer_name'       => 'Customer Name',
            'customer_phone'      => 'Customer Phone',
            'device_brand'        => 'Device Brand',
            'problem_description' => 'Problem Description',
        ]);

        if (!empty($errors)) {
            Session::flash('errors', $errors);
            Session::flash('input', $this->request->all());
            $this->redirect('/admin/repairs/create');
        }

        try {
            $service    = new BookingService();
            $trackingId = $service->book($this->request->all());
            Session::flash('success', "Repair job created: {$trackingId}");
            $this->redirect('/admin/repairs');
        } catch (\Throwable $e) {
            Session::flash('errors', ['_' => 'Failed to create repair: ' . $e->getMessage()]);
            Session::flash('input', $this->request->all());
            $this->redirect('/admin/repairs/create');
        }
    }

    public function view(string $id): void
    {
        AuthMiddleware::handle();

        $repair = RepairJob::findById((int)$id);
        if (!$repair) $this->abort(404, 'Repair job not found.');

        // Object-level auth: technician can only see their own jobs
        if (Session::isTechnician() && $repair['assigned_technician_id'] != Session::userId()) {
            $this->abort(403, 'Access denied.');
        }

        $timeline    = RepairStatusHistory::getByRepairJob((int)$id);
        $images      = RepairImage::getByRepairJob((int)$id);
        $payments    = Payment::getByRepairJob((int)$id);
        $totalPaid   = Payment::totalPaid((int)$id);
        $invoice     = Invoice::findByRepairJobId((int)$id);
        $services    = Service::all(true);
        $technicians = User::allTechnicians();
        $statuses    = RepairJob::STATUSES;
        $transitions = RepairJob::TRANSITIONS[$repair['current_status']] ?? [];

        $csrfToken    = Session::csrfToken();
        $flash_success = Session::getFlash('success');
        $flash_error   = Session::getFlash('error');
        $user          = ['name' => Session::userName(), 'role' => Session::userRole()];

        $this->render('admin/repairs/view', compact(
            'repair', 'timeline', 'images', 'payments', 'totalPaid', 'invoice',
            'services', 'technicians', 'statuses', 'transitions',
            'csrfToken', 'flash_success', 'flash_error', 'user'
        ), 'admin');
    }

    public function update(string $id): void
    {
        AuthMiddleware::handle();
        CsrfMiddleware::verify();

        $data = [
            'diagnosis'              => $this->request->post('diagnosis'),
            'estimated_amount'       => $this->request->post('estimated_amount') ?: null,
            'final_amount'           => $this->request->post('final_amount') ?: null,
            'assigned_technician_id' => $this->request->post('technician_id') ?: null,
            'service_id'             => $this->request->post('service_id') ?: null,
            'priority'               => $this->request->post('priority', 'normal'),
            'estimated_delivery_at'  => $this->request->post('estimated_delivery_at') ?: null,
        ];

        RepairJob::update((int)$id, $data);
        Session::flash('success', 'Repair job updated successfully.');
        $this->redirect('/admin/repairs/' . $id);
    }

    public function updateStatus(string $id): void
    {
        AuthMiddleware::handle();
        CsrfMiddleware::verify();

        $newStatus = strtoupper($this->request->post('status', ''));
        $note      = $this->request->post('note', '');

        if (!array_key_exists($newStatus, RepairJob::STATUSES)) {
            Session::flash('error', 'Invalid status.');
            $this->redirect('/admin/repairs/' . $id);
        }

        try {
            $service = new RepairService();
            // Admins can force any transition; technicians follow allowed flow
            if (Session::isAdmin()) {
                $service->forceStatus((int)$id, $newStatus, $note);
            } else {
                $service->changeStatus((int)$id, $newStatus, $note);
            }
            Session::flash('success', 'Status updated to: ' . RepairJob::statusLabel($newStatus));
        } catch (\InvalidArgumentException $e) {
            Session::flash('error', $e->getMessage());
        }

        $this->redirect('/admin/repairs/' . $id);
    }

    public function uploadImage(string $id): void
    {
        AuthMiddleware::handle();
        CsrfMiddleware::verify();

        $file = $this->request->file('image');
        $type = strtoupper($this->request->post('image_type', 'RECEIVED'));

        if (!$file || $file['error'] === UPLOAD_ERR_NO_FILE) {
            Session::flash('error', 'No file selected.');
            $this->redirect('/admin/repairs/' . $id);
        }

        try {
            $uploadService = new UploadService();
            $filePath      = $uploadService->uploadRepairImage($file);

            RepairImage::add((int)$id, $type, $filePath, $file['name'], Session::userId());
            Session::flash('success', 'Image uploaded successfully.');
        } catch (\RuntimeException $e) {
            Session::flash('error', $e->getMessage());
        }

        $this->redirect('/admin/repairs/' . $id);
    }

    public function addPayment(string $id): void
    {
        AuthMiddleware::handle();
        CsrfMiddleware::verify();

        $amount = (float)$this->request->post('amount', 0);
        if ($amount <= 0) {
            Session::flash('error', 'Invalid payment amount.');
            $this->redirect('/admin/repairs/' . $id);
        }

        Payment::create([
            'repair_job_id'  => (int)$id,
            'amount'         => $amount,
            'payment_method' => $this->request->post('payment_method', 'cash'),
            'transaction_id' => $this->request->post('transaction_id') ?: null,
            'payment_status' => 'paid',
            'note'           => $this->request->post('note') ?: null,
            'paid_at'        => date('Y-m-d H:i:s'),
            'created_by'     => Session::userId(),
        ]);

        Session::flash('success', '₹' . number_format($amount, 2) . ' payment recorded.');
        $this->redirect('/admin/repairs/' . $id);
    }

    /** Serve private repair images (protected by auth) */
    public function serveImage(string $filename): void
    {
        AuthMiddleware::handle();

        $filename = basename($filename); // prevent path traversal
        $path     = BASE_PATH . '/storage/uploads/repair-images/' . $filename;

        if (!file_exists($path)) {
            $this->abort(404, 'Image not found.');
        }

        $ext  = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $mime = match ($ext) {
            'jpg', 'jpeg' => 'image/jpeg',
            'png'         => 'image/png',
            'webp'        => 'image/webp',
            default       => 'application/octet-stream',
        };

        header('Content-Type: ' . $mime);
        header('Content-Length: ' . filesize($path));
        header('Cache-Control: private, max-age=3600');
        readfile($path);
        exit;
    }
}
