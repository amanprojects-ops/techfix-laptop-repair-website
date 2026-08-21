<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Session;
use App\Middleware\AdminMiddleware;
use App\Middleware\CsrfMiddleware;
use App\Models\Service;

class ServiceController extends Controller
{
    public function index(): void
    {
        AdminMiddleware::handle();

        $services      = Service::all(false);
        $csrfToken     = Session::csrfToken();
        $flash_success = Session::getFlash('success');
        $flash_error   = Session::getFlash('error');
        $user          = ['name' => Session::userName(), 'role' => Session::userRole()];

        $this->render('admin/services/index', compact('services', 'csrfToken', 'flash_success', 'flash_error', 'user'), 'admin');
    }

    public function store(): void
    {
        AdminMiddleware::handle();
        CsrfMiddleware::verify();

        $name = $this->request->post('name', '');
        if (!$name) {
            Session::flash('error', 'Service name is required.');
            $this->redirect('/admin/services');
        }

        $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $name));

        Service::create([
            'name'              => $name,
            'slug'              => $slug . '-' . time(),
            'short_description' => $this->request->post('short_description', ''),
            'starting_price'    => (float)$this->request->post('starting_price', 0),
            'estimated_days'    => (int)$this->request->post('estimated_days', 1),
            'warranty_days'     => (int)$this->request->post('warranty_days', 90),
            'icon'              => $this->request->post('icon', 'wrench'),
            'sort_order'        => (int)$this->request->post('sort_order', 0),
            'status'            => 'active',
        ]);

        Session::flash('success', 'Service added successfully.');
        $this->redirect('/admin/services');
    }

    public function update(string $id): void
    {
        AdminMiddleware::handle();
        CsrfMiddleware::verify();

        Service::update((int)$id, [
            'name'              => $this->request->post('name'),
            'short_description' => $this->request->post('short_description'),
            'starting_price'    => (float)$this->request->post('starting_price', 0),
            'status'            => $this->request->post('status', 'active'),
        ]);

        Session::flash('success', 'Service updated.');
        $this->redirect('/admin/services');
    }

    public function delete(string $id): void
    {
        AdminMiddleware::handle();
        CsrfMiddleware::verify();
        Service::delete((int)$id);
        Session::flash('success', 'Service deactivated.');
        $this->redirect('/admin/services');
    }
}
