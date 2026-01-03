<?php

if (!isset($routes)) {
    $routes = \Config\Services::routes(true);
}

$routes->group('inventory', ['namespace' => 'Modules\Inventory\Controllers'], function ($subroutes) {
    $subroutes->add('/', 'InventoryController::index');
});
