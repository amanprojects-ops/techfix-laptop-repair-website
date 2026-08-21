<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Session;
use App\Middleware\AdminMiddleware;
use App\Middleware\CsrfMiddleware;
use App\Models\User;

class TechnicianController extends Controller
{
    public function index(): void
    {
        AdminMiddleware::handle();

        $technicians = User::allTechnicians();
        $csrfToken   = Session::csrfToken();
        $flash_success = Session::getFlash('success');
        $flash_error   = Session::getFlash('error');
        $user          = ['name' => Session::userName(), 'role' => Session::userRole()];

        $this->render('admin/technicians/index', compact('technicians', 'csrfToken', 'flash_success', 'flash_error', 'user'), 'admin');
    }

    public function store(): void
    {
        AdminMiddleware::handle();
        CsrfMiddleware::verify();

        $errors = $this->validate([
            'name'     => 'Full Name',
            'email'    => 'Email Address',
            'password' => 'Password',
        ]);

        // Check email unique
        if (empty($errors['email'])) {
            $existing = User::findByEmail($this->request->post('email', ''));
            if ($existing) {
                $errors['email'] = 'This email is already in use.';
            }
        }

        if (!empty($errors)) {
            Session::flash('error', implode(' ', $errors));
            $this->redirect('/admin/technicians');
        }

        User::create([
            'name'     => $this->request->post('name'),
            'email'    => $this->request->post('email'),
            'phone'    => $this->request->post('phone', ''),
            'password' => $this->request->post('password'),
            'role'     => 'technician',
            'status'   => 'active',
        ]);

        Session::flash('success', 'Technician added successfully.');
        $this->redirect('/admin/technicians');
    }

    public function toggle(string $id): void
    {
        AdminMiddleware::handle();
        CsrfMiddleware::verify();

        $user = User::findById((int)$id);
        if (!$user) $this->abort(404);

        $newStatus = $user['status'] === 'active' ? 'inactive' : 'active';
        User::updateStatus((int)$id, $newStatus);

        Session::flash('success', "Technician status changed to {$newStatus}.");
        $this->redirect('/admin/technicians');
    }
}
