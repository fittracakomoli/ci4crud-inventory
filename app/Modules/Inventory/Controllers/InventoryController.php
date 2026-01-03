<?php

namespace Modules\Inventory\Controllers;

use App\Controllers\BaseController;

class InventoryController extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Inventory Management'
        ];

        return view('Modules\Inventory\Views\index', $data);
    }
}
