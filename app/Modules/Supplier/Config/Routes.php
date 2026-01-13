<?php

if (!isset($routes)) {
    $routes = \Config\Services::routes(true);
}

$routes->group('supplier', ['namespace' => 'Modules\Supplier\Controllers'], function ($subroutes) {
    $subroutes->add('/', 'SupplierController::index');
    $subroutes->add('list', 'SupplierController::list_ajax');
    $subroutes->add('count', 'SupplierController::count_ajax');
    $subroutes->add('create', 'SupplierController::create_ajax');
    $subroutes->add('delete', 'SupplierController::delete_ajax');
    $subroutes->add('detail', 'SupplierController::detail_ajax');
    $subroutes->add('update', 'SupplierController::update_ajax');
});
