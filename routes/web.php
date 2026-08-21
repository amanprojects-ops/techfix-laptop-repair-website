<?php

use App\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\BookingController;
use App\Controllers\TrackingController;
use App\Controllers\ContactController;

/** @var Router $router */

// Public website
$router->get('/',               [HomeController::class,     'index']);
$router->get('/services',       [HomeController::class,     'services']);
$router->get('/pricing',        [HomeController::class,     'pricing']);

// Repair booking
$router->get('/book-repair',    [BookingController::class,  'form']);
$router->post('/book-repair',   [BookingController::class,  'submit']);

// Repair tracking
$router->get('/track-repair',   [TrackingController::class, 'form']);
$router->post('/track-repair',  [TrackingController::class, 'lookup']);
$router->get('/repair/{id}',    [TrackingController::class, 'result']);

// Contact
$router->post('/contact',       [ContactController::class,  'submit']);
