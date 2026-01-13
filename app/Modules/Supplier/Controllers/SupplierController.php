<?php

namespace Modules\Supplier\Controllers;

use App\Controllers\BaseController;

class SupplierController extends BaseController
{
    protected $supplierModel;

    public function __construct()
    {
        $this->supplierModel = new \Modules\Supplier\Models\Supplier();
    }

    public function index()
    {
        $data = [
            'title' => 'Supplier Management'
        ];

        return view('Modules\Supplier\Views\Supplier', $data);
    }

    public function count_ajax()
    {
        $totalSuppliers = $this->supplierModel->countSuppliers();

        return $this->response->setJSON(['status' => true, 'total_suppliers' => $totalSuppliers]);
    }

    public function list_ajax()
    {
        $suppliers = $this->supplierModel->findAll();

        if (empty($suppliers)) {
            return $this->response->setJSON(['status' => false, 'message' => 'Tidak ada data supplier.', 'data' => []]);
        }

        return $this->response->setJSON(['status' => true, 'data' => $suppliers]);
    }

    public function create_ajax()
    {
        $data = $this->request->getPost();

        if (!isset($data['nama_supplier']) || !isset($data['kontak'])) {
            return $this->response->setJSON(['status' => false, 'message' => 'Nama supplier dan kontak wajib diisi.']);
        }

        $supplier = [
            'nama_supplier' => $data['nama_supplier'],
            'kontak'          => $data['kontak'],
            'alamat'          => $data['alamat'],
        ];

        $insert = $this->supplierModel->insert($supplier);

        if ($insert === false) {
            return $this->response->setJSON(['status' => false, 'message' => 'Gagal menambahkan supplier.']);
        }

        return $this->response->setJSON(['status' => true, 'message' => 'Supplier berhasil ditambahkan.', 'data' => $supplier]);
    }

    public function delete_ajax()
    {
        $data = $this->request->getPost();

        if (!isset($data['id'])) {
            return $this->response->setJSON(['status' => false, 'message' => 'ID supplier wajib diisi.']);
        }

        $delete = $this->supplierModel->delete($data['id']);

        if ($delete === false) {
            return $this->response->setJSON(['status' => false, 'message' => 'Gagal menghapus supplier.']);
        }

        return $this->response->setJSON(['status' => true, 'message' => 'Supplier berhasil dihapus.']);
    }

    public function detail_ajax()
    {
        $data = $this->request->getGet();

        if (!isset($data['id'])) {
            return $this->response->setJSON(['status' => false, 'message' => 'ID supplier wajib diisi.']);
        }

        $supplier = $this->supplierModel->find($data['id']);

        if (empty($supplier)) {
            return $this->response->setJSON(['status' => false, 'message' => 'Supplier tidak ditemukan.']);
        }

        return $this->response->setJSON(['status' => true, 'data' => $supplier]);
    }

    public function update_ajax()
    {
        $data = $this->request->getPost();

        if (!isset($data['id'])) {
            return $this->response->setJSON(['status' => false, 'message' => 'ID wajib diisi.']);
        }

        $supplier = [
            'nama_supplier' => $data['nama_supplier'],
            'kontak'          => $data['kontak'],
            'alamat'          => $data['alamat'],
        ];

        $update = $this->supplierModel->update($data['id'], $supplier);

        if ($update === false) {
            return $this->response->setJSON(['status' => false, 'message' => 'Gagal memperbarui supplier.']);
        }

        return $this->response->setJSON(['status' => true, 'message' => 'Supplier berhasil diperbarui.', 'data' => $supplier]);
    }
}
