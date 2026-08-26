<?php

namespace App\Core;

class Session
{
    private static bool $started = false;

    public static function start(): void
    {
        if (!self::$started && session_status() === PHP_SESSION_NONE) {
            if (!headers_sent()) {
                @ini_set('session.cookie_httponly', '1');
                @ini_set('session.cookie_samesite', 'Lax');
                @session_start();
            }
            self::$started = true;
        }
    }

    public static function set(string $key, mixed $value): void
    {
        self::start();
        $_SESSION[$key] = $value;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    public static function has(string $key): bool
    {
        self::start();
        return isset($_SESSION[$key]);
    }

    public static function remove(string $key): void
    {
        self::start();
        unset($_SESSION[$key]);
    }

    public static function flash(string $key, mixed $value): void
    {
        self::set('_flash_' . $key, $value);
    }

    public static function getFlash(string $key, mixed $default = null): mixed
    {
        $value = self::get('_flash_' . $key, $default);
        self::remove('_flash_' . $key);
        return $value;
    }

    public static function destroy(): void
    {
        self::start();
        session_unset();
        session_destroy();
        self::$started = false;
    }

    public static function regenerate(): void
    {
        self::start();
        session_regenerate_id(true);
    }

    // ── Auth shortcuts ────────────────────────────────────────────────
    public static function login(array $user): void
    {
        self::regenerate();
        self::set('user_id',   $user['id']);
        self::set('user_name', $user['name']);
        self::set('user_role', $user['role']);
    }

    public static function isLoggedIn(): bool
    {
        return self::has('user_id');
    }

    public static function userId(): int|null
    {
        return self::get('user_id');
    }

    public static function userRole(): string|null
    {
        return self::get('user_role');
    }

    public static function userName(): string|null
    {
        return self::get('user_name');
    }

    public static function isAdmin(): bool
    {
        return in_array(self::userRole(), ['admin', 'manager'], true);
    }

    public static function isTechnician(): bool
    {
        return self::userRole() === 'technician';
    }

    // ── CSRF ──────────────────────────────────────────────────────────
    public static function csrfToken(): string
    {
        if (!self::has('csrf_token')) {
            self::set('csrf_token', bin2hex(random_bytes(32)));
        }
        return self::get('csrf_token');
    }

    public static function verifyCsrf(string $token): bool
    {
        return hash_equals(self::csrfToken(), $token);
    }
}
