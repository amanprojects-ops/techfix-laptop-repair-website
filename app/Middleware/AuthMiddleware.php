<?php

namespace App\Middleware;

use App\Core\Session;

class AuthMiddleware
{
    public static function handle(): void
    {
        Session::start();
        if (!Session::isLoggedIn()) {
            $reqUri = $_SERVER['REQUEST_URI'] ?? '';
            if ($reqUri && !str_contains($reqUri, '/admin/login') && !str_contains($reqUri, '/admin/logout')) {
                Session::set('intended_url', $reqUri);
            }
            Session::flash('error', 'Please login to continue.');
            header('Location: ' . url('/admin/login'));
            exit;
        }
    }
}
