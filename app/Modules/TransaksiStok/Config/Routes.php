<?php

if (!isset($routes)) {
    $routes = \Config\Services::routes(true);
}

$routes->group('transaction', ['namespace' => 'Modules\TransaksiStok\Controllers'], function ($subroutes) {
    $subroutes->add('/', 'TransaksiStokController::index');
    $subroutes->add('list', 'TransaksiStokController::list_ajax');
    $subroutes->add('detail', 'TransaksiStokController::detail_ajax');
    $subroutes->add('save', 'TransaksiStokController::save_ajax');
});
