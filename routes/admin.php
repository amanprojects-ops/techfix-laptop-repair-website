<?php

use App\Core\Router;
use App\Controllers\Admin\AuthController;
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\RepairController;
use App\Controllers\Admin\InvoiceController;
use App\Controllers\Admin\CustomerController;
use App\Controllers\Admin\TechnicianController;
use App\Controllers\Admin\ServiceController;
use App\Controllers\Admin\SettingsController;

/** @var Router $router */

// Auth
$router->get('/admin/login',                        [AuthController::class,       'loginForm']);
$router->post('/admin/login',                       [AuthController::class,       'login']);
$router->post('/admin/logout',                      [AuthController::class,       'logout']);

// Dashboard
$router->get('/admin/dashboard',                    [DashboardController::class,  'index']);
$router->get('/admin',                              [DashboardController::class,  'index']);

// System Manager / Settings
$router->get('/admin/settings',                     [SettingsController::class,   'index']);
$router->post('/admin/settings/general',            [SettingsController::class,   'updateGeneral']);
$router->post('/admin/settings/seo',                [SettingsController::class,   'updateSeo']);
$router->post('/admin/settings/branding',           [SettingsController::class,   'updateBranding']);
$router->post('/admin/settings/reset-branding',     [SettingsController::class,   'resetBranding']);
$router->post('/admin/settings/mail',               [SettingsController::class,   'updateMail']);
$router->post('/admin/settings/mail/test',          [SettingsController::class,   'sendTestEmail']);
$router->post('/admin/settings/workshop',           [SettingsController::class,   'updateWorkshop']);
$router->post('/admin/settings/billing',            [SettingsController::class,   'updateBilling']);
$router->post('/admin/settings/templates/save',     [SettingsController::class,   'saveTemplate']);
$router->post('/admin/settings/templates/{id}/delete', [SettingsController::class, 'deleteTemplate']);
$router->post('/admin/settings/templates/preview',  [SettingsController::class,   'previewTemplateAjax']);


// Invoices & Billing
$router->get('/admin/invoices',                     [InvoiceController::class,    'index']);
$router->get('/admin/invoices/create',              [InvoiceController::class,    'create']);
$router->post('/admin/invoices',                    [InvoiceController::class,    'store']);
$router->get('/admin/invoices/{id}',                [InvoiceController::class,    'view']);
$router->get('/admin/invoices/{id}/edit',           [InvoiceController::class,    'edit']);
$router->post('/admin/invoices/{id}/update',        [InvoiceController::class,    'update']);
$router->post('/admin/invoices/{id}/delete',        [InvoiceController::class,    'delete']);
$router->get('/admin/invoices/{id}/print',          [InvoiceController::class,    'print']);
$router->post('/admin/invoices/{id}/payment',       [InvoiceController::class,    'addPayment']);
$router->post('/admin/invoices/{id}/send-email',    [InvoiceController::class,    'sendEmail']);
$router->post('/admin/repairs/{id}/generate-invoice', [InvoiceController::class,  'generateFromRepair']);

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
