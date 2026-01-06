<?php

if (!isset($routes)) {
    $routes = \Config\Services::routes(true);
}

$routes->group('division', ['namespace' => 'Modules\Division\Controllers'], function ($subroutes) {
    $subroutes->add('/', 'DivisionController::index');
    $subroutes->add('list', 'DivisionController::list_ajax');
    $subroutes->add('create', 'DivisionController::create_ajax');
    $subroutes->add('delete', 'DivisionController::delete_ajax');
    $subroutes->add('detail', 'DivisionController::detail_ajax');
    $subroutes->add('update', 'DivisionController::update_ajax');
});
