<?php

namespace Modules\Inventory\Controllers;

use App\Controllers\BaseController;

class InventoryController extends BaseController
{
    protected $inventoryModel;

    public function __construct()
    {
        $this->inventoryModel = new \Modules\Inventory\Models\Inventory();
    }

    public function index()
    {
        $data = [
            'title' => 'Inventory Management'
        ];

        return view('Modules\Inventory\Views\index', $data);
    }

    public function list_ajax()
    {
        $barang = $this->inventoryModel->findAll();
        if (empty($barang)) {
            return $this->response->setJSON(['status' => false, 'message' => 'Tidak ada data barang.', 'data' => []]);
        }

        return $this->response->setJSON(['status' => true, 'data' => $barang]);
    }
}
