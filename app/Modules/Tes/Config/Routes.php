<?php

if (!isset($routes)) {
    $routes = \Config\Services::routes(true);
}

$routes->group('tes', ['namespace' => 'Modules\Tes\Controllers'], function ($subroutes) {
    $subroutes->add('/', 'TesController::index');
    $subroutes->add('get-data', 'TesController::getData');
});