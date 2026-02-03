<?php

if (!isset($routes)) {
    $routes = \Config\Services::routes(true);
}

$routes->group('transaction', ['namespace' => 'Modules\TransaksiStok\Controllers', 'filter' => 'permission:view.transaction'], function ($subroutes) {
    $subroutes->add('/', 'TransaksiStokController::index');
    $subroutes->add('list', 'TransaksiStokController::list_ajax');
    $subroutes->add('count', 'TransaksiStokController::count_ajax');
    $subroutes->add('detail', 'TransaksiStokController::detail_ajax');
});

$routes->group('transaction', ['namespace' => 'Modules\TransaksiStok\Controllers', 'filter' => 'permission:manage.transaction'], function ($subroutes) {
    $subroutes->add('save', 'TransaksiStokController::save_ajax');
});