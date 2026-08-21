<?php

namespace App\Middleware;

use App\Core\Session;

class AuthMiddleware
{
    public static function handle(): void
    {
        Session::start();
        if (!Session::isLoggedIn()) {
            Session::flash('error', 'Please login to continue.');
            header('Location: ' . url('/admin/login'));
            exit;
        }
    }
}
