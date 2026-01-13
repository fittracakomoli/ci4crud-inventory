<?php

namespace Modules\Division\Controllers;

use App\Controllers\BaseController;

class DivisionController extends BaseController
{
    protected $divisionModel;

    public function __construct()
    {
        $this->divisionModel = new \Modules\Division\Models\Division();
    }

    public function index()
    {
        $data = [
            'title' => 'Division Management'
        ];

        return view('Modules\Division\Views\Division', $data);
    }

    public function count_ajax()
    {
        $totalDivisions = $this->divisionModel->countDivisions();

        return $this->response->setJSON(['status' => true, 'total_divisions' => $totalDivisions]);
    }

    public function list_ajax()
    {
        $divisions = $this->divisionModel->findAll();

        if (empty($divisions)) {
            return $this->response->setJSON(['status' => false, 'message' => 'Tidak ada data divisi.', 'data' => []]);
        }

        return $this->response->setJSON(['status' => true, 'data' => $divisions]);
    }

    public function create_ajax()
    {
        $data = $this->request->getPost();

        if (!isset($data['nama_divisi'])) {
            return $this->response->setJSON(['status' => false, 'message' => 'Nama divisi wajib diisi.']);
        }

        $division = [
            'nama_divisi' => $data['nama_divisi'],
            'pj'          => $data['pj'],
        ];

        $insert = $this->divisionModel->insert($division);

        if ($insert === false) {
            return $this->response->setJSON(['status' => false, 'message' => 'Gagal menambahkan divisi.']);
        }

        return $this->response->setJSON(['status' => true, 'message' => 'Divisi berhasil ditambahkan.', 'data' => $division]);
    }

    public function delete_ajax()
    {
        $data = $this->request->getPost();

        if (!isset($data['id'])) {
            return $this->response->setJSON(['status' => false, 'message' => 'ID divisi wajib diisi.']);
        }

        $delete = $this->divisionModel->delete($data['id']);

        if ($delete === false) {
            return $this->response->setJSON(['status' => false, 'message' => 'Gagal menghapus divisi.']);
        }

        return $this->response->setJSON(['status' => true, 'message' => 'Divisi berhasil dihapus.']);
    }

    public function detail_ajax()
    {
        $data = $this->request->getGet();

        if (!isset($data['id'])) {
            return $this->response->setJSON(['status' => false, 'message' => 'ID divisi wajib diisi.']);
        }

        $division = $this->divisionModel->find($data['id']);

        if (empty($division)) {
            return $this->response->setJSON(['status' => false, 'message' => 'Divisi tidak ditemukan.']);
        }

        return $this->response->setJSON(['status' => true, 'data' => $division]);
    }

    public function update_ajax()
    {
        $data = $this->request->getPost();

        if (!isset($data['id']) || !isset($data['nama_divisi'])) {
            return $this->response->setJSON(['status' => false, 'message' => 'ID dan Nama divisi wajib diisi.']);
        }

        $division = [
            'nama_divisi' => $data['nama_divisi'],
            'pj'          => $data['pj'],
        ];

        $update = $this->divisionModel->update($data['id'], $division);

        if ($update === false) {
            return $this->response->setJSON(['status' => false, 'message' => 'Gagal memperbarui divisi.']);
        }

        return $this->response->setJSON(['status' => true, 'message' => 'Divisi berhasil diperbarui.', 'data' => $division]);
    }
}
