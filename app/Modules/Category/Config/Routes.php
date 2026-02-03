<?php

if (!isset($routes)) {
    $routes = \Config\Services::routes(true);
}

$routes->group('category', ['namespace' => 'Modules\Category\Controllers', 'filter' => 'permission:view.category'], function ($subroutes) {
    $subroutes->add('/', 'CategoryController::index');
    $subroutes->add('list', 'CategoryController::list_ajax');
    $subroutes->add('count', 'CategoryController::count_categories');
});

$routes->group('category', ['namespace' => 'Modules\Category\Controllers', 'filter' => 'permission:manage.category'], function ($subroutes) {
    $subroutes->add('create', 'CategoryController::create_ajax');
    $subroutes->add('delete', 'CategoryController::delete_ajax');
    $subroutes->add('detail', 'CategoryController::detail_ajax');
    $subroutes->add('update', 'CategoryController::update_ajax');
});
