<?php

namespace Modules\Category\Models;

use CodeIgniter\Model;

class Category extends Model
{
    protected $table = 'kategori';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'description'];

    protected $useTimestamps = true;
}
