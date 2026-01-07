<?php

namespace Modules\TransaksiStok\Models;

use CodeIgniter\Model;

class TransaksiStok extends Model
{
    protected $table = 'transaksi_stok';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_barang', 'id_supplier', 'id_divisi', 'invoice', 'jenis', 'jumlah', 'keterangan'];

    protected $useTimestamps = true;

    public function withRelations()
    {
        $builder = $this->db->table($this->table);
        $builder->select('transaksi_stok.*, barang.nama_barang as nama_barang, barang.stok')->join('barang', 'barang.id = transaksi_stok.id_barang', 'left');

        $builder->select('transaksi_stok.*, supplier.nama_supplier as nama_supplier')->join('supplier', 'supplier.id = transaksi_stok.id_supplier', 'left');

        $builder->select('transaksi_stok.*, divisi.nama_divisi as nama_divisi')->join('divisi', 'divisi.id = transaksi_stok.id_divisi', 'left');

        return $builder->get()->getResultArray();
    }

    public function withBarang()
    {
        $builder = $this->db->table($this->table);
        $builder->select('transaksi_stok.*, barang.nama_barang as nama_barang, barang.stok')->join('barang', 'barang.id = transaksi_stok.id_barang', 'left');

        return $builder->get()->getResultArray();
    }

    public function withSupplier()
    {
        $builder = $this->db->table($this->table);
        $builder->select('transaksi_stok.*, supplier.nama_supplier as nama_supplier')->join('supplier', 'supplier.id = transaksi_stok.id_supplier', 'left');

        return $builder->get()->getResultArray();
    }

    public function withDivisi()
    {
        $builder = $this->db->table($this->table);
        $builder->select('transaksi_stok.*, divisi.nama_divisi as nama_divisi')->join('divisi', 'divisi.id = transaksi_stok.id_divisi', 'left');

        return $builder->get()->getResultArray();
    }

    public function simpanTransaksi($data)
    {
        $this->db->transStart();

        $this->insert($data);

        $inventoryModel = new \Modules\Inventory\Models\Inventory();
        $barang = $inventoryModel->find($data['id_barang']);

        if ($data['jenis'] === 'masuk') {
            $barang['stok'] += $data['jumlah'];
        } elseif ($data['jenis'] === 'keluar') {
            $barang['stok'] -= $data['jumlah'];
        }

        $inventoryModel->update($data['id_barang'], ['stok' => $barang['stok']]);

        $this->db->transComplete();

        return $this->db->transStatus();
    }
}
