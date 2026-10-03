<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Dashboard
// Auth Routes
$routes->get('login', 'AuthController::login');
$routes->post('login/process', 'AuthController::processLogin');
$routes->get('logout', 'AuthController::logout');

// Group Routes Terproteksi Login
$routes->group('', ['filter' => 'auth'], function($routes) {

    // Dashboard (Admin/Owner)
    $routes->get('/', 'Dashboard::index', ['filter' => 'auth:admin']);
    $routes->get('dashboard', 'Dashboard::index', ['filter' => 'auth:admin']);

    // Master Produk (Admin & Pembelian)
    $routes->get('products', 'ProductController::index', ['filter' => 'auth:pembelian']);
    $routes->get('products/create', 'ProductController::create', ['filter' => 'auth:pembelian']);
    $routes->post('products/store', 'ProductController::store', ['filter' => 'auth:pembelian']);
    $routes->get('products/edit/(:num)', 'ProductController::edit/$1', ['filter' => 'auth:pembelian']);
    $routes->post('products/update/(:num)', 'ProductController::update/$1', ['filter' => 'auth:pembelian']);
    $routes->get('products/delete/(:num)', 'ProductController::delete/$1', ['filter' => 'auth:pembelian']);

    // Stok & Mutasi (Admin & Gudang)
    $routes->get('inventory', 'InventoryController::index', ['filter' => 'auth:gudang']);
    $routes->get('inventory/transfer', 'TransferController::index', ['filter' => 'auth:gudang']);
    $routes->post('inventory/transfer/process', 'TransferController::process', ['filter' => 'auth:gudang']);

    // POS Penjualan Kasir (Admin & Kasir)
    $routes->get('sales/create', 'SalesController::create', ['filter' => 'auth:kasir']);
    $routes->post('sales/store', 'SalesController::store', ['filter' => 'auth:kasir']);

    // Purchase Order (Admin & Pembelian)
    $routes->get('purchase', 'PurchaseController::index', ['filter' => 'auth:pembelian']);
    $routes->get('purchase/create', 'PurchaseController::create', ['filter' => 'auth:pembelian']);
    $routes->post('purchase/store', 'PurchaseController::store', ['filter' => 'auth:pembelian']);
    $routes->get('purchase/receive/(:num)', 'PurchaseController::receive/$1', ['filter' => 'auth:pembelian']);

    
    $routes->get('reports', 'ReportController::index', ['filter' => 'auth:admin']);

});