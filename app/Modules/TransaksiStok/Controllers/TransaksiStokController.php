<?php

namespace Modules\TransaksiStok\Controllers;

use App\Controllers\BaseController;
use Modules\TransaksiStok\Models\TransaksiStok;
use Modules\Inventory\Models\Inventory;

class TransaksiStokController extends BaseController
{
    protected $transaksiStokModel;
    protected $inventoryModel;

    public function __construct()
    {
        $this->transaksiStokModel = new TransaksiStok();
        $this->inventoryModel = new Inventory();
    }

    public function index()
    {
        $data = [
            'title' => 'Transaksi Stok',
            'barangs' => $this->inventoryModel->findAll(),
        ];

        return view('Modules\TransaksiStok\Views\index', $data);
    }

    public function list_ajax()
    {
        $transaksi = $this->transaksiStokModel->withBarang();

        if (empty($transaksi)) {
            return $this->response->setJSON(['status' => false, 'message' => 'Data transaksi stok tidak ditemukan']);
        }

        return $this->response->setJSON(['status' => true, 'data' => $transaksi]);
    }

    public function detail_ajax()
    {
        $data = $this->request->getPost();

        if (!$data['id']) {
            return $this->response->setJSON(['status' => false, 'message' => 'ID barang tidak ditemukan']);
        }

        $barang = $this->inventoryModel->find($data['id']);
        if (!$barang) {
            return $this->response->setJSON(['status' => false, 'message' => 'Data barang tidak ditemukan']);
        }

        return $this->response->setJSON(['status' => true, 'data' => $barang]);
    }

    public function save_ajax()
    {
        $data = $this->request->getPost();

        if (!isset($data['id_barang'], $data['jenis'], $data['jumlah'])) {
            return $this->response->setJSON(['status' => false, 'message' => 'Data tidak lengkap']);
        }

        if ($data['jenis'] === 'keluar') {
            $barang = $this->inventoryModel->find($data['id_barang']);
            if ($barang['stok'] < $data['jumlah']) {
                return $this->response->setJSON(['status' => false, 'message' => 'Stok tidak mencukupi, sisa stok: ' . $barang['stok']]);
            }
        }

        $transaksi = [
            'id_barang'  => $data['id_barang'],
            'jenis'       => $data['jenis'],
            'jumlah'     => $data['jumlah'],
            'keterangan' => $data['keterangan'],
        ];

        $insert = $this->transaksiStokModel->simpanTransaksi($transaksi);

        if ($insert === false) {
            return $this->response->setJSON(['status' => false, 'message' => 'Gagal menyimpan transaksi stok']);
        }

        return $this->response->setJSON(['status' => true, 'message' => 'Transaksi stok berhasil disimpan']);
    }
}
