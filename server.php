<?php

/**
 * TechFix Laptop Repair Management System
 * Built-in PHP Development Web Server Router
 * Usage: php -S localhost:8000 server.php
 */

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/'
);

// Serve static assets directly from public/ folder if file exists
if ($uri !== '/' && file_exists(__DIR__ . '/public' . $uri) && !is_dir(__DIR__ . '/public' . $uri)) {
    return false;
}

// Forward all other application routes to public/index.php
require_once __DIR__ . '/public/index.php';
