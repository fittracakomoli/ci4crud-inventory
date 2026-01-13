<?php

namespace Modules\Inventory\Models;

use CodeIgniter\Model;

class Inventory extends Model
{
    protected $table = 'barang';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_kategori', 'nama_barang', 'deskripsi', 'stok', 'harga', 'gambar'];

    protected $useTimestamps = true;

    public function withCategory()
    {
        $builder = $this->db->table($this->table);
        $builder->select('barang.*, kategori.nama as kategori')->join('kategori', 'kategori.id = barang.id_kategori', 'left');

        return $builder->get()->getResultArray();
    }

    public function countItems()
    {
        return $this->countAllResults();
    }
}
