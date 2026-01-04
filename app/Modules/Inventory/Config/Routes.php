<?php

if (!isset($routes)) {
    $routes = \Config\Services::routes(true);
}

$routes->group('inventory', ['namespace' => 'Modules\Inventory\Controllers'], function ($subroutes) {
    $subroutes->add('/', 'InventoryController::index');
    $subroutes->add('list', 'InventoryController::list_ajax');
    $subroutes->add('create', 'InventoryController::create_ajax');
});
