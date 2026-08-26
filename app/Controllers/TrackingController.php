<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Services\TrackingService;
use App\Middleware\CsrfMiddleware;

class TrackingController extends Controller
{
    private TrackingService $trackingService;

    public function __construct()
    {
        parent::__construct();
        $this->trackingService = new TrackingService();
    }

    public function form(): void
    {
        $csrfToken  = Session::csrfToken();
        $flash_error = Session::getFlash('tracking_error');
        $this->render('frontend/tracking', compact('csrfToken', 'flash_error'), 'main');
    }

    public function lookup(): void
    {
        CsrfMiddleware::verify();

        $trackingId = strtoupper(trim($this->request->post('tracking_id', '')));
        $phone      = trim($this->request->post('phone', ''));

        if (!$trackingId || !$phone) {
            Session::flash('tracking_error', 'Please enter both Repair ID and phone number.');
            $this->redirect('/track-repair');
        }

        $repair = $this->trackingService->lookup($trackingId, $phone);

        if (!$repair) {
            Session::flash('tracking_error', 'No repair found with that Repair ID and phone number. Please check and try again.');
            $this->redirect('/track-repair');
        }

        // Store in session and redirect to result page
        Session::set('tracked_repair', $repair);
        $this->redirect('/repair/' . urlencode($trackingId));
    }

    public function result(string $trackingId): void
    {
        // Always fetch fresh live data from DB (status, photos, payments, timeline)
        $repair = $this->trackingService->getByTrackingId($trackingId);

        if (!$repair) {
            Session::flash('tracking_error', 'Repair job not found. Please check your Repair ID.');
            $this->redirect('/track-repair');
        }

        // Update session with latest state
        Session::set('tracked_repair', $repair);

        $pageTitle = 'Live Tracking: ' . $repair['tracking_id'] . ' — TechFix';
        $csrfToken = Session::csrfToken();
        $this->render('frontend/repair-result', compact('repair', 'csrfToken', 'pageTitle'), 'main');
    }

    /**
     * Customer-Facing Tax Invoice & Receipt View
     */
    public function invoice(string $trackingId): void
    {
        $repair = $this->trackingService->getByTrackingId($trackingId);
        if (!$repair) {
            $this->abort(404, 'Repair record not found.');
        }

        $invoiceService = new \App\Services\InvoiceService();
        $invoice = \App\Models\Invoice::findByRepairJobId((int)$repair['id']);

        if (!$invoice) {
            // Auto-generate invoice for this ticket
            $invId = $invoiceService->createFromRepair((int)$repair['id']);
            $invoice = \App\Models\Invoice::findById($invId);
        }

        $templateKey = $invoice['template_key'] ?? 'modern';
        $renderedHtml = $invoiceService->renderInvoiceHtml($invoice, $templateKey);

        $pageTitle = 'Invoice #' . $invoice['invoice_number'] . ' — ' . site_name();
        $csrfToken = Session::csrfToken();

        $this->render('frontend/invoice', compact('repair', 'invoice', 'renderedHtml', 'csrfToken', 'pageTitle'), 'main');
    }

    /**
     * Safely serve uploaded repair images to public tracking page
     */
    public function serveImage(string $filename): void
    {
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
        header('Cache-Control: public, max-age=86400');
        readfile($path);
        exit;
    }
}
