<?php

namespace App\Middleware;

use App\Core\Session;

class AdminMiddleware
{
    public static function handle(): void
    {
        AuthMiddleware::handle();

        if (!Session::isAdmin()) {
            http_response_code(403);
            echo "<!DOCTYPE html><html><body><h1>403 — Forbidden</h1><p>You do not have admin access.</p></body></html>";
            exit;
        }
    }
}
