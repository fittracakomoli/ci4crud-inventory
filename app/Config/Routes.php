<?php

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
