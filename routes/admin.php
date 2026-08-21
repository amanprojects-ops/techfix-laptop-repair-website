<?php

use App\Core\Router;
use App\Controllers\Admin\AuthController;
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\RepairController;
use App\Controllers\Admin\CustomerController;
use App\Controllers\Admin\TechnicianController;
use App\Controllers\Admin\ServiceController;

/** @var Router $router */

// Auth
$router->get('/admin/login',                        [AuthController::class,       'loginForm']);
$router->post('/admin/login',                       [AuthController::class,       'login']);
$router->post('/admin/logout',                      [AuthController::class,       'logout']);

// Dashboard
$router->get('/admin/dashboard',                    [DashboardController::class,  'index']);
$router->get('/admin',                              [DashboardController::class,  'index']);

// Repairs
$router->get('/admin/repairs',                      [RepairController::class,     'index']);
$router->get('/admin/repairs/create',               [RepairController::class,     'create']);
$router->post('/admin/repairs',                     [RepairController::class,     'store']);
$router->get('/admin/repairs/{id}',                 [RepairController::class,     'view']);
$router->post('/admin/repairs/{id}/update',         [RepairController::class,     'update']);
$router->post('/admin/repairs/{id}/status',         [RepairController::class,     'updateStatus']);
$router->post('/admin/repairs/{id}/images',         [RepairController::class,     'uploadImage']);
$router->post('/admin/repairs/{id}/payment',        [RepairController::class,     'addPayment']);

// Customers
$router->get('/admin/customers',                    [CustomerController::class,   'index']);
$router->get('/admin/customers/{id}',               [CustomerController::class,   'view']);

// Technicians
$router->get('/admin/technicians',                  [TechnicianController::class, 'index']);
$router->post('/admin/technicians',                 [TechnicianController::class, 'store']);
$router->post('/admin/technicians/{id}/toggle',     [TechnicianController::class, 'toggle']);

// Services
$router->get('/admin/services',                     [ServiceController::class,    'index']);
$router->post('/admin/services',                    [ServiceController::class,    'store']);
$router->post('/admin/services/{id}/update',        [ServiceController::class,    'update']);
$router->post('/admin/services/{id}/delete',        [ServiceController::class,    'delete']);

// Serve uploaded repair images (protected)
$router->get('/admin/uploads/{filename}',           [RepairController::class,     'serveImage']);
