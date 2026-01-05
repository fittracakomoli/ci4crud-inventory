<?php

namespace Modules\TransaksiStok\Models;

use CodeIgniter\Model;

class TransaksiStok extends Model
{
    protected $table = 'transaksi_stok';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_barang', 'jenis', 'jumlah', 'keterangan'];

    protected $useTimestamps = true;

    public function withBarang()
    {
        $builder = $this->db->table($this->table);
        $builder->select('transaksi_stok.*, barang.nama_barang as nama_barang, barang.stok')->join('barang', 'barang.id = transaksi_stok.id_barang', 'left');

        return $builder->get()->getResultArray();
    }

    public function simpanTransaksi($data)
    {
        $this->insert($data);

        $inventoryModel = new \Modules\Inventory\Models\Inventory();
        $barang = $inventoryModel->find($data['id_barang']);

        if ($data['jenis'] === 'masuk') {
            $barang['stok'] += $data['jumlah'];
        } elseif ($data['jenis'] === 'keluar') {
            $barang['stok'] -= $data['jumlah'];
        }

        $inventoryModel->update($data['id_barang'], ['stok' => $barang['stok']]);

        return true;
    }
}
