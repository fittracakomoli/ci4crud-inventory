<?php

namespace Modules\Division\Models;

use CodeIgniter\Model;

class Division extends Model
{
    protected $table = 'divisi';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama_divisi', 'pj'];

    protected $useTimestamps = true;

    public function countDivisions()
    {
        return $this->countAllResults();
    }
}
