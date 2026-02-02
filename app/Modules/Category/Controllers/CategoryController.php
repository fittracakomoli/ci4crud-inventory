<?php

namespace Modules\Category\Controllers;

use App\Controllers\BaseController;

class CategoryController extends BaseController
{
    protected $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new \Modules\Category\Models\Category();
    }

    public function index()
    {
        $data = [
            'title' => 'Category Management'
        ];

        return view('Modules\Category\Views\Category', $data);
    }

    public function list_ajax()
    {
        $request = \Config\Services::request();

        $draw           = $request->getVar('draw');
        $start          = $request->getVar('start');
        $length         = $request->getVar('length');
        $order          = $request->getVar('order');
        $columns        = $request->getVar('columns');
        $searchValue    = $request->getVar('search')['value'] ?? '';

        $columnIndex    = $order[0]['column']; 
        $orderColumn    = $columns[$columnIndex]['data'];
        $orderDir       = $order[0]['dir'];

        $result = $this->categoryModel->GetDatatatbleData(
            $start, 
            $length, 
            $searchValue, 
            $orderColumn,
            $orderDir
        );

        return $this->response->setJSON([
            "draw"            => intval($draw),
            "recordsTotal"    => intval($result['recordsTotal']),
            "recordsFiltered" => intval($result['recordsFiltered']),
            "data"            => $result['data']
        ]);
    }

    public function create_ajax()
    {
        $data = $this->request->getPost();

        if (!isset($data['nama'])) {
            return $this->response->setJSON(['status' => false, 'message' => 'Nama kategori wajib diisi.']);
        }

        $category = [
            'nama'       => $data['nama'],
            'keterangan' => $data['keterangan'],
        ];

        $insert = $this->categoryModel->insert($category);

        if ($insert === false) {
            return $this->response->setJSON(['status' => false, 'message' => 'Gagal menambahkan kategori.']);
        }

        return $this->response->setJSON(['status' => true, 'message' => 'Kategori berhasil ditambahkan.', 'data' => $category]);
    }

    public function delete_ajax()
    {
        $data = $this->request->getPost();

        if (!isset($data['id'])) {
            return $this->response->setJSON(['status' => false, 'message' => 'ID kategori wajib diisi.']);
        }

        $delete = $this->categoryModel->delete($data['id']);
        if ($delete === false) {
            return $this->response->setJSON(['status' => false, 'message' => 'Gagal menghapus kategori.']);
        }

        return $this->response->setJSON(['status' => true, 'message' => 'Kategori berhasil dihapus.']);
    }

    public function detail_ajax()
    {
        $data = $this->request->getGet();

        if (!isset($data['id'])) {
            return $this->response->setJSON(['status' => false, 'message' => 'ID kategori wajib diisi.']);
        }

        $category = $this->categoryModel->find($data['id']);

        if (empty($category)) {
            return $this->response->setJSON(['status' => false, 'message' => 'Kategori tidak ditemukan.']);
        }

        return $this->response->setJSON(['status' => true, 'data' => $category]);
    }

    public function update_ajax()
    {
        $data = $this->request->getPost();

        if (!isset($data['id'])) {
            return $this->response->setJSON(['status' => false, 'message' => 'ID kategori wajib diisi.']);
        }

        $category = [
            'nama'       => $data['nama'],
            'keterangan' => $data['keterangan'],
        ];

        $update = $this->categoryModel->update($data['id'], $category);

        if ($update === false) {
            return $this->response->setJSON(['status' => false, 'message' => 'Gagal memperbarui kategori.']);
        }

        return $this->response->setJSON(['status' => true, 'message' => 'Kategori berhasil diperbarui.', 'data' => $category]);
    }
}
