<?php

/**
 * TechFix Laptop Repair Management System
 * Front Controller — All requests enter here
 */

declare(strict_types=1);

// ── Bootstrap ─────────────────────────────────────────────────────────────
define('BASE_PATH', dirname(__DIR__));

// Autoloader (Composer)
$autoloader = BASE_PATH . '/vendor/autoload.php';
if (!file_exists($autoloader)) {
    http_response_code(500);
    echo '<h1>Please run <code>composer install</code> to install dependencies.</h1>';
    exit;
}
require $autoloader;

// Load .env
$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->safeLoad();

// App config
$appConfig = require BASE_PATH . '/config/app.php';
date_default_timezone_set($appConfig['timezone']);

// Error reporting
if ($appConfig['debug']) {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    error_reporting(0);
}

// Start session
\App\Core\Session::start();

// ── Router ────────────────────────────────────────────────────────────────
$router  = new \App\Core\Router();
$request = new \App\Core\Request();

// Load route files
require BASE_PATH . '/routes/web.php';
require BASE_PATH . '/routes/admin.php';

// Dispatch
try {
    $router->dispatch($request);
} catch (\Throwable $e) {
    if ($appConfig['debug']) {
        echo '<pre style="background:#1e1e1e;color:#f8f8f2;padding:24px;font-size:14px;overflow-x:auto;">';
        echo '<strong>' . get_class($e) . '</strong>: ' . htmlspecialchars($e->getMessage()) . "\n\n";
        echo htmlspecialchars($e->getTraceAsString());
        echo '</pre>';
    } else {
        http_response_code(500);
        echo '<!DOCTYPE html><html><body style="font-family:sans-serif;padding:2rem;background:#0f172a;color:#e2e8f0;">';
        echo '<h1 style="color:#f87171;">Something went wrong</h1>';
        echo '<p>Please try again later. If the problem persists, contact support.</p>';
        echo '</body></html>';
    }
}
