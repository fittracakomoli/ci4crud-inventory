<?php

namespace Modules\Category\Models;

use CodeIgniter\Model;

class Category extends Model
{
    protected $table = 'kategori';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama', 'keterangan'];

    protected $useTimestamps = true;
}
