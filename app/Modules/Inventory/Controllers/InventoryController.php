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

    public function create_ajax()
    {
        $data = $this->request->getPost();

        if (!isset($data['nama_barang']) || !isset($data['stok']) || !isset($data['harga'])) {
            return $this->response->setJSON(['status' => false, 'message' => 'Data tidak lengkap.']);
        }

        $barang = [
            'nama_barang' => $data['nama_barang'],
            'deskripsi'   => $data['deskripsi'],
            'stok'        => $data['stok'],
            'harga'       => $data['harga'],
            'gambar'      => $data['gambar'],
        ];

        $insert = $this->inventoryModel->insert($barang);

        if ($insert === false) {
            return $this->response->setJSON(['status' => false, 'message' => 'Gagal menambahkan data barang.']);
        }

        return $this->response->setJSON(['status' => true, 'message' => 'Berhasil menambahkan data barang.', 'data' => $barang]);
    }
}
