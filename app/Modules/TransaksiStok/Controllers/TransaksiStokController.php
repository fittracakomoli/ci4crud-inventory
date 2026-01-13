<?php

namespace Modules\TransaksiStok\Controllers;

use App\Controllers\BaseController;
use Modules\TransaksiStok\Models\TransaksiStok;
use Modules\Inventory\Models\Inventory;

class TransaksiStokController extends BaseController
{
    protected $transaksiStokModel;
    protected $inventoryModel;
    protected $divisionModel;
    protected $supplierModel;

    public function __construct()
    {
        $this->transaksiStokModel = new TransaksiStok();
        $this->inventoryModel = new Inventory();
        $this->divisionModel = new \Modules\Division\Models\Division();
        $this->supplierModel = new \Modules\Supplier\Models\Supplier();
    }

    public function index()
    {
        $data = [
            'title' => 'Transaksi Stok',
            'barangs' => $this->inventoryModel->findAll(),
            'suppliers' => $this->supplierModel->findAll(),
            'divisis' => $this->divisionModel->findAll(),
        ];

        return view('Modules\TransaksiStok\Views\Transaction', $data);
    }

    public function count_ajax()
    {
        $total_transaksi = $this->transaksiStokModel->countAllResults();

        return $this->response->setJSON(['status' => true, 'total_transactions' => $total_transaksi]);
    }

    public function list_ajax()
    {
        $transaksi = $this->transaksiStokModel->withRelations();

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

        if ($data['jenis'] === 'masuk' && empty($data['id_supplier'])) {
            return $this->response->setJSON(['status' => false, 'message' => 'Supplier harus diisi untuk transaksi masuk']);
        }

        if ($data['jenis'] === 'keluar' && empty($data['id_divisi'])) {
            return $this->response->setJSON(['status' => false, 'message' => 'Divisi harus diisi untuk transaksi keluar']);
        }

        $invoice = date('His') . strtoupper(substr(md5(uniqid(rand(), true)), 0, 6));

        $transaksi = [
            'id_barang'  => $data['id_barang'],
            'id_supplier'  => $data['id_supplier'] ?? null,
            'id_divisi'  => $data['id_divisi'] ?? null,
            'invoice'  => $invoice,
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
