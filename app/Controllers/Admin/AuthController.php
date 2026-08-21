<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Session;
use App\Models\User;
use App\Middleware\CsrfMiddleware;

class AuthController extends Controller
{
    public function loginForm(): void
    {
        if (Session::isLoggedIn()) {
            $this->redirect('/admin/dashboard');
        }
        $csrfToken   = Session::csrfToken();
        $flash_error = Session::getFlash('error');
        $this->render('admin/login', compact('csrfToken', 'flash_error'), 'none');
    }

    public function login(): void
    {
        CsrfMiddleware::verify();

        $email    = $this->request->post('email', '');
        $password = $this->request->post('password', '');

        if (!$email || !$password) {
            Session::flash('error', 'Email and password are required.');
            $this->redirect('/admin/login');
        }

        $user = User::findByEmail($email);

        if (!$user || !User::verifyPassword($password, $user['password'])) {
            // Small delay to slow brute-force
            sleep(1);
            Session::flash('error', 'Invalid email or password.');
            $this->redirect('/admin/login');
        }

        if ($user['status'] !== 'active') {
            Session::flash('error', 'Your account is inactive. Contact admin.');
            $this->redirect('/admin/login');
        }

        Session::login($user);
        $this->redirect('/admin/dashboard');
    }

    public function logout(): void
    {
        CsrfMiddleware::verify();
        Session::destroy();
        $this->redirect('/admin/login');
    }
}
