<?php

declare(strict_types=1);

use App\Models\Setting;

/**
 * TechFix Global Helper Functions
 */

if (!function_exists('base_url_prefix')) {
    function base_url_prefix(): string
    {
        $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
        $dir = str_replace('\\', '/', dirname($scriptName));
        $clean = preg_replace('#/public$#', '', $dir);
        return ($clean === '/' || $clean === '.' || $clean === '' || $clean === '\\') ? '' : rtrim($clean, '/');
    }
}

if (!function_exists('url')) {
    function url(string $path = ''): string
    {
        if (preg_match('#^(https?:)?//#i', $path) || str_starts_with($path, '#') || str_starts_with($path, 'tel:') || str_starts_with($path, 'mailto:') || str_starts_with($path, 'javascript:')) {
            return $path;
        }
        $prefix = base_url_prefix();
        $cleanPath = '/' . ltrim($path, '/');
        if ($cleanPath === '/') {
            return $prefix ? $prefix . '/' : '/';
        }
        return $prefix . $cleanPath;
    }
}

if (!function_exists('asset')) {
    function asset(string $path): string
    {
        return url($path);
    }
}

if (!function_exists('setting')) {
    /**
     * Get dynamic setting value with optional default fallback
     */
    function setting(string $key, mixed $default = null): mixed
    {
        return Setting::get($key, $default);
    }
}

if (!function_exists('site_name')) {
    function site_name(): string
    {
        return (string)setting('site_name', 'TechFix');
    }
}

if (!function_exists('site_tagline')) {
    function site_tagline(): string
    {
        return (string)setting('site_tagline', 'Professional Laptop Repair Center');
    }
}

if (!function_exists('site_phone')) {
    function site_phone(): string
    {
        return (string)setting('contact_phone', '+91-9876543210');
    }
}

if (!function_exists('site_whatsapp')) {
    function site_whatsapp(): string
    {
        return (string)setting('whatsapp_number', '+91-9876543210');
    }
}

if (!function_exists('site_whatsapp_link')) {
    function site_whatsapp_link(string $message = 'Hello TechFix, I want to inquire about laptop repair.'): string
    {
        $rawNumber = preg_replace('/\D+/', '', site_whatsapp());
        if (strlen($rawNumber) === 10) {
            $rawNumber = '91' . $rawNumber;
        }
        return 'https://wa.me/' . $rawNumber . '?text=' . rawurlencode($message);
    }
}

if (!function_exists('site_email')) {
    function site_email(): string
    {
        return (string)setting('contact_email', 'support@techfix.in');
    }
}

if (!function_exists('site_address')) {
    function site_address(): string
    {
        $line = setting('address_line', 'Main Market Road, Near Bus Stand');
        $city = setting('city', 'Saharsa');
        $state = setting('state', 'Bihar');
        $pin = setting('pincode', '852201');
        return trim("{$line}, {$city}, {$state} — {$pin}", ', ');
    }
}

if (!function_exists('site_logo')) {
    function site_logo(): string
    {
        $logo = (string)setting('site_logo', '');
        if ($logo !== '') {
            $ver = file_exists(BASE_PATH . '/public/' . ltrim($logo, '/')) ? '?v=' . filemtime(BASE_PATH . '/public/' . ltrim($logo, '/')) : '';
            return asset('/' . ltrim($logo, '/') . $ver);
        }
        return asset('/assets/images/logo.svg');
    }
}

if (!function_exists('site_logo_dark')) {
    function site_logo_dark(): string
    {
        $logo = (string)setting('site_logo_dark', '');
        if ($logo !== '') {
            $ver = file_exists(BASE_PATH . '/public/' . ltrim($logo, '/')) ? '?v=' . filemtime(BASE_PATH . '/public/' . ltrim($logo, '/')) : '';
            return asset('/' . ltrim($logo, '/') . $ver);
        }
        return site_logo();
    }
}

if (!function_exists('site_favicon')) {
    function site_favicon(): string
    {
        $fav = (string)setting('site_favicon', '');
        if ($fav !== '') {
            $ver = file_exists(BASE_PATH . '/public/' . ltrim($fav, '/')) ? '?v=' . filemtime(BASE_PATH . '/public/' . ltrim($fav, '/')) : '';
            return asset('/' . ltrim($fav, '/') . $ver);
        }
        return asset('/admin-assets/images/icon.svg');
    }
}

if (!function_exists('apple_touch_icon')) {
    function apple_touch_icon(): string
    {
        $icon = (string)setting('apple_touch_icon', '');
        if ($icon !== '') {
            $ver = file_exists(BASE_PATH . '/public/' . ltrim($icon, '/')) ? '?v=' . filemtime(BASE_PATH . '/public/' . ltrim($icon, '/')) : '';
            return asset('/' . ltrim($icon, '/') . $ver);
        }
        return site_favicon();
    }
}

if (!function_exists('admin_logo')) {
    function admin_logo(): string
    {
        $logo = (string)setting('admin_logo', '');
        if ($logo !== '') {
            $ver = file_exists(BASE_PATH . '/public/' . ltrim($logo, '/')) ? '?v=' . filemtime(BASE_PATH . '/public/' . ltrim($logo, '/')) : '';
            return asset('/' . ltrim($logo, '/') . $ver);
        }
        return asset('/admin-assets/images/logo.svg');
    }
}

if (!function_exists('admin_icon')) {
    function admin_icon(): string
    {
        $icon = (string)setting('admin_icon', '');
        if ($icon !== '') {
            $ver = file_exists(BASE_PATH . '/public/' . ltrim($icon, '/')) ? '?v=' . filemtime(BASE_PATH . '/public/' . ltrim($icon, '/')) : '';
            return asset('/' . ltrim($icon, '/') . $ver);
        }
        return asset('/admin-assets/images/icon.svg');
    }
}

if (!function_exists('currency_symbol')) {
    function currency_symbol(): string
    {
        return (string)setting('currency_symbol', '₹');
    }
}

if (!function_exists('format_currency')) {
    function format_currency(float|int|string $amount, int $decimals = 0): string
    {
        $sym = currency_symbol();
        $num = number_format((float)$amount, $decimals);
        return "{$sym}{$num}";
    }
}

if (!function_exists('is_maintenance_mode')) {
    function is_maintenance_mode(): bool
    {
        return (string)setting('maintenance_mode', '0') === '1';
    }
}
