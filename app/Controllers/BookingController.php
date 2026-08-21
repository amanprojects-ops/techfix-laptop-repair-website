<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Service;
use App\Models\User;
use App\Services\BookingService;
use App\Middleware\CsrfMiddleware;

class BookingController extends Controller
{
    public function form(): void
    {
        $services     = Service::all(true);
        $csrfToken    = Session::csrfToken();
        $flash_error  = Session::getFlash('error');
        $flash_input  = Session::getFlash('input', []);
        $this->render('frontend/booking', compact('services', 'csrfToken', 'flash_error', 'flash_input'), 'main');
    }

    public function submit(): void
    {
        CsrfMiddleware::verify();

        $errors = $this->validate([
            'customer_name'       => 'Your Name',
            'customer_phone'      => 'Phone Number',
            'device_brand'        => 'Device Brand',
            'problem_description' => 'Problem Description',
        ]);

        // Phone validation
        $phone = $this->request->post('customer_phone');
        if ($phone && !preg_match('/^[6-9]\d{9}$/', $phone)) {
            $errors['customer_phone'] = 'Enter a valid 10-digit Indian mobile number.';
        }

        if (!empty($errors)) {
            Session::flash('errors', $errors);
            Session::flash('input', $this->request->all());
            $this->redirect('/book-repair');
        }

        try {
            $service  = new BookingService();
            $trackingId = $service->book($this->request->all());
            Session::flash('success', $trackingId);
            $this->redirect('/book-repair?success=1&id=' . urlencode($trackingId));
        } catch (\Throwable $e) {
            Session::flash('error', 'Something went wrong. Please try again.');
            Session::flash('input', $this->request->all());
            $this->redirect('/book-repair');
        }
    }
}
