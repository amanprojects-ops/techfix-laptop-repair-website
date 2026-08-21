<?php

namespace App\Middleware;

use App\Core\Session;

class CsrfMiddleware
{
    public static function handle(string $token): void
    {
        Session::start();
        if (!Session::verifyCsrf($token)) {
            http_response_code(403);
            echo "<!DOCTYPE html><html><body><h1>403 — Invalid CSRF Token</h1><p>Your request could not be verified. Please go back and try again.</p></body></html>";
            exit;
        }
    }

    /** Verify CSRF from the current request's POST data */
    public static function verify(): void
    {
        $token = $_POST['csrf_token'] ?? '';
        self::handle($token);
    }
}
