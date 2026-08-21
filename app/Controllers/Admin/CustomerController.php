<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Session;
use App\Middleware\AuthMiddleware;
use App\Models\Customer;
use App\Models\RepairJob;

class CustomerController extends Controller
{
    public function index(): void
    {
        AuthMiddleware::handle();

        $page    = max(1, (int)$this->request->get('page', 1));
        $limit   = 20;
        $offset  = ($page - 1) * $limit;
        $search  = $this->request->get('q', '');

        $customers = $search ? Customer::search($search) : Customer::all($limit, $offset);
        $total     = Customer::count();
        $pages     = (int)ceil($total / $limit);

        $csrfToken = Session::csrfToken();
        $user      = ['name' => Session::userName(), 'role' => Session::userRole()];

        $this->render('admin/customers/index', compact('customers', 'total', 'pages', 'page', 'search', 'csrfToken', 'user'), 'admin');
    }

    public function view(string $id): void
    {
        AuthMiddleware::handle();

        $customer = Customer::findById((int)$id);
        if (!$customer) $this->abort(404, 'Customer not found.');

        $repairs   = RepairJob::all(50, 0);
        $repairs   = array_filter($repairs, fn($r) => $r['customer_name'] === $customer['name']);
        $csrfToken = Session::csrfToken();
        $user      = ['name' => Session::userName(), 'role' => Session::userRole()];

        $this->render('admin/customers/view', compact('customer', 'repairs', 'csrfToken', 'user'), 'admin');
    }
}
