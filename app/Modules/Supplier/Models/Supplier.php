<?php

namespace Modules\Supplier\Models;

use CodeIgniter\Model;

class Supplier extends Model
{
    protected $table = 'supplier';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama_supplier', 'kontak', 'alamat'];
    protected $useTimestamps = true;
}
