<?php

declare(strict_types=1);

/**
 * TechFix Global Helper Functions
 */

if (!function_exists('base_url_prefix')) {
    function base_url_prefix(): string
    {
        $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
        $dir = dirname($scriptName);
        $clean = preg_replace('#/public$#', '', $dir);
        return ($clean === '/' || $clean === '.' || $clean === '') ? '' : rtrim($clean, '/');
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
