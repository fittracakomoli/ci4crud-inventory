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
            'title' => 'Inventory Management',
            'categories' => (new \Modules\Category\Models\Category())->findAll(),
        ];

        return view('Modules\Inventory\Views\index', $data);
    }

    public function list_ajax()
    {
        $barang = $this->inventoryModel->withCategory();
        if (empty($barang)) {
            return $this->response->setJSON(['status' => false, 'message' => 'Tidak ada data barang.', 'data' => []]);
        }

        return $this->response->setJSON(['status' => true, 'data' => $barang]);
    }

    public function create_ajax()
    {
        $data = $this->request->getPost();
        $fileGambar = $this->request->getFile('gambar');

        if (!isset($data['nama_barang']) ||  !isset($data['harga'])) {
            return $this->response->setJSON(['status' => false, 'message' => 'Data tidak lengkap.']);
        }

        if ($fileGambar && $fileGambar->isValid() && !$fileGambar->hasMoved()) {
            $namaGambar = $fileGambar->getRandomName();
            $fileGambar->move(FCPATH . 'uploads', $namaGambar);
            $data['gambar'] = $namaGambar;
        } else {
            $data['gambar'] = null;
        }

        $barang = [
            'id_kategori' => $data['kategori'],
            'nama_barang' => $data['nama_barang'],
            'deskripsi'   => $data['deskripsi'],
            'harga'       => $data['harga'],
            'gambar'      => $namaGambar,
        ];

        $insert = $this->inventoryModel->insert($barang);

        if ($insert === false) {
            return $this->response->setJSON(['status' => false, 'message' => 'Gagal menambahkan data barang.']);
        }

        return $this->response->setJSON(['status' => true, 'message' => 'Berhasil menambahkan data barang.', 'data' => $barang]);
    }

    public function delete_ajax()
    {
        $data = $this->request->getPost();

        if (!isset($data['id'])) {
            return $this->response->setJSON(['status' => false, 'message' => 'ID barang tidak ditemukan.']);
        }

        $barang = $this->inventoryModel->find($data['id']);
        if ($barang['gambar']) {
            $filePath = FCPATH . 'uploads/' . $barang['gambar'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $delete = $this->inventoryModel->delete($data['id']);

        if ($delete === false) {
            return $this->response->setJSON(['status' => false, 'message' => 'Gagal menghapus data barang.']);
        }

        return $this->response->setJSON(['status' => true, 'message' => 'Berhasil menghapus data barang.']);
    }

    public function detail_ajax()
    {
        $data = $this->request->getGet();

        if (!isset($data['id'])) {
            return $this->response->setJSON(['status' => false, 'message' => 'ID barang tidak ditemukan.']);
        }

        $barang = $this->inventoryModel->find($data['id']);
        if (empty($barang)) {
            return $this->response->setJSON(['status' => false, 'message' => 'Data barang tidak ditemukan.']);
        }

        return $this->response->setJSON(['status' => true, 'data' => $barang]);
    }

    public function update_ajax()
    {
        $data = $this->request->getPost();
        $fileGambar = $this->request->getFile('gambar');
        $barangLama = $this->inventoryModel->find($data['id']);

        if (!isset($data['id'])) {
            return $this->response->setJSON(['status' => false, 'message' => 'ID barang tidak ditemukan.']);
        }

        if ($fileGambar && $fileGambar->isValid() && !$fileGambar->hasMoved()) {
            if ($barangLama['gambar'] && file_exists(FCPATH . 'uploads/' . $barangLama['gambar'])) {
                unlink(FCPATH . 'uploads/' . $barangLama['gambar']);
            }

            $namaGambar = $fileGambar->getRandomName();
            $fileGambar->move(FCPATH . 'uploads', $namaGambar);
        } else {
            $namaGambar = $barangLama['gambar'];
        }

        $barang = [
            'id_kategori' => $data['kategori'],
            'nama_barang' => $data['nama_barang'],
            'deskripsi'   => $data['deskripsi'],
            'harga'       => $data['harga'],
            'gambar'      => $namaGambar,
        ];

        $update = $this->inventoryModel->update($data['id'], $barang);

        if ($update === false) {
            return $this->response->setJSON(['status' => false, 'message' => 'Gagal memperbarui data barang.', 'data' => $barang]);
        }

        return $this->response->setJSON(['status' => true, 'message' => 'Berhasil memperbarui data barang.', 'data' => $barang]);
    }
}
