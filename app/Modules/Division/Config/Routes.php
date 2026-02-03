<?php

if (!isset($routes)) {
    $routes = \Config\Services::routes(true);
}

$routes->group('division', ['namespace' => 'Modules\Division\Controllers', 'filter' => 'permission:view.division'], function ($subroutes) {
    $subroutes->add('/', 'DivisionController::index');
    $subroutes->add('list', 'DivisionController::list_ajax');
    $subroutes->add('count', 'DivisionController::count_ajax');
});

$routes->group('division', ['namespace' => 'Modules\Division\Controllers', 'filter' => 'permission:manage.division'], function ($subroutes) {
    $subroutes->add('create', 'DivisionController::create_ajax');
    $subroutes->add('delete', 'DivisionController::delete_ajax');
    $subroutes->add('detail', 'DivisionController::detail_ajax');
    $subroutes->add('update', 'DivisionController::update_ajax');
});