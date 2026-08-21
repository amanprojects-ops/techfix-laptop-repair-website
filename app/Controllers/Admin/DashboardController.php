<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Session;
use App\Middleware\AuthMiddleware;
use App\Models\RepairJob;
use App\Models\Customer;
use App\Models\User;

class DashboardController extends Controller
{
    public function index(): void
    {
        AuthMiddleware::handle();

        $stats = [
            'active_repairs'    => RepairJob::count('IN_REPAIR'),
            'waiting_approval'  => RepairJob::count('WAITING_APPROVAL'),
            'ready_pickup'      => RepairJob::count('READY_FOR_PICKUP'),
            'revenue_month'     => RepairJob::revenueThisMonth(),
            'total_customers'   => Customer::count(),
            'received_today'    => count(RepairJob::todayJobs()),
        ];

        $todayJobs     = RepairJob::todayJobs();
        $recentRepairs = RepairJob::all(10, 0);

        $csrfToken = Session::csrfToken();
        $user      = ['name' => Session::userName(), 'role' => Session::userRole()];

        $this->render('admin/dashboard', compact('stats', 'todayJobs', 'recentRepairs', 'csrfToken', 'user'), 'admin');
    }
}
