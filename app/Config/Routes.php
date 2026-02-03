<?php

$routes = \Config\Services::routes();

$routes->get('/', 'Home::index', ['filter' => 'permission:view.home']);

service('auth')->routes($routes);

/*
* --------------------------------------------------------------------
* HMVC ROUTE LOADER (Versi Inside App)
* --------------------------------------------------------------------
*/

foreach (glob(APPPATH . 'Modules/*', GLOB_ONLYDIR) as $item_dir) {
    if (file_exists($item_dir . '/Config/Routes.php')) {
        require_once($item_dir . '/Config/Routes.php');
    }
}
