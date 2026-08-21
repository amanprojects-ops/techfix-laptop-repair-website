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
        $repair = Session::get('tracked_repair');

        // If no session data, try public lookup (less secure)
        if (!$repair || ($repair['tracking_id'] ?? '') !== strtoupper($trackingId)) {
            $repair = $this->trackingService->getByTrackingId($trackingId);
        }

        if (!$repair) {
            $this->redirect('/track-repair');
        }

        $csrfToken = Session::csrfToken();
        $this->render('frontend/repair-result', compact('repair', 'csrfToken'), 'main');
    }
}
