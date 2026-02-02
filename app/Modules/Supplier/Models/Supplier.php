<?php

namespace Modules\Supplier\Models;

use CodeIgniter\Model;

class Supplier extends Model
{
    protected $table = 'supplier';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama_supplier', 'kontak', 'alamat'];
    protected $useTimestamps = true;

    public function GetDatatableData($start, $length, $search, $col, $dir)
    {
        $query = "SELECT * FROM {$this->table}";
        $countQuery = "SELECT COUNT(*) as total FROM {$this->table} ";

        $where = "";
        if($search) {
            $where = " WHERE nama_supplier LIKE '%$search%' OR kontak LIKE '%$search%' OR alamat LIKE '%$search%'";
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
