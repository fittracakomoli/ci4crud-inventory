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

        return view('Modules\Category\Views\index', $data);
    }

    public function list_ajax()
    {
        $category = $this->categoryModel->findAll();

        if (empty($category)) {
            return $this->response->setJSON(['status' => false, 'message' => 'Tidak ada data kategori.', 'data' => []]);
        }

        return $this->response->setJSON(['status' => true, 'data' => $category]);
    }
}
