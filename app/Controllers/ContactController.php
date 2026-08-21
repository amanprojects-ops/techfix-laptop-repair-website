<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Middleware\CsrfMiddleware;

class ContactController extends Controller
{
    public function submit(): void
    {
        CsrfMiddleware::verify();

        $name    = $this->request->post('name', '');
        $phone   = $this->request->post('phone', '');
        $message = $this->request->post('message', '');

        if (!$name || !$phone || !$message) {
            Session::flash('contact_error', 'Please fill in all required fields.');
            $this->redirect('/contact.html');
        }

        // For now: log to file. In Phase 2 we'll add email/WhatsApp notification.
        $logEntry = date('Y-m-d H:i:s') . " | {$name} | {$phone} | " . str_replace("\n", ' ', $message) . "\n";
        file_put_contents(BASE_PATH . '/storage/logs/contact.log', $logEntry, FILE_APPEND);

        Session::flash('contact_success', 'Thank you! We will contact you shortly.');
        $this->redirect('/#contact');
    }
}
