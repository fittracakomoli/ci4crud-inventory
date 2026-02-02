<?php

namespace Modules\Division\Models;

use CodeIgniter\Model;

class Division extends Model
{
    protected $table = 'divisi';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama_divisi', 'pj'];

    protected $useTimestamps = true;

    public function GetDatatableData($start, $length, $search, $col, $dir)
    {
        $query = "SELECT * FROM {$this->table}";
        $countQuery = "SELECT COUNT(*) as total FROM {$this->table} ";

        $where = "";
        if($search) {
            $where = " WHERE nama_divisi LIKE '%$search%' OR pj LIKE '%$search%'";
        }

        $orderBy = " ORDER BY $col $dir ";
        $limit = " LIMIT $length OFFSET $start ";

        $totalAll = $this->db->query($countQuery)->getRow()->total;

        if($search) {
            $sqlFiltered = $countQuery . $where;
            $totalFiltered = $this->db->query($sqlFiltered)->getRow()->total;
        } else {
            $totalFiltered = $totalAll;
        }

        $sqlFinal = $query . $where . $orderBy . $limit;
        $data = $this->db->query($sqlFinal)->getResultArray();

        return [
            'recordsTotal'    => $totalAll,
            'recordsFiltered' => $totalFiltered,
            'data'            => $data
        ];
    }
}
