<?php

namespace Modules\Inventory\Models;

use CodeIgniter\Model;

class Inventory extends Model
{
    protected $table = 'barang';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama_barang', 'deskripsi', 'stok', 'harga', 'gambar'];

    protected $useTimestamps = true;
}
