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

    public function count_categories()
    {
        $totalCategories = $this->categoryModel->countCategories();
        return $this->response->setJSON(['status' => true, 'total_categories' => $totalCategories]);
    }

    public function list_ajax()
    {
        $category = $this->categoryModel->findAll();

        if (empty($category)) {
            return $this->response->setJSON(['status' => false, 'message' => 'Tidak ada data kategori.', 'data' => []]);
        }

        return $this->response->setJSON(['status' => true, 'data' => $category]);
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
