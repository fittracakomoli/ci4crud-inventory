<?php

namespace Modules\Tes\Models;

use CodeIgniter\Model;

class Tes extends Model
{
    protected $table = 'people';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama', 'email', 'role'];

    public function getDatatablesData($start, $length, $search, $col, $dir)
    {
        $query = "SELECT * FROM {$this->table}";
        $countQuery = "SELECT COUNT(*) as total FROM {$this->table}";

        $where = "";
        if ($search) {
            $where = " WHERE nama LIKE '%$search%' OR email LIKE '%$search%' OR role LIKE '%$search%' ";
        }

        $orderBy = " ORDER BY $col $dir ";
        $limit = " LIMIT $length OFFSET $start ";

        $totalAll = $this->db->query($countQuery)->getRow()->total;

        if ($search) {
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

    // public function getDatatablesData($start, $length, $searchValue, $orderColumn, $orderDir)
    // {
    //     $db = \Config\Database::connect();
    //     $tableName = $this->table;

    //     $sqlTotal = "SELECT COUNT(*) as total FROM $tableName";
    //     $totalRecords = $db->query($sqlTotal)->getRow()->total;

    //     $whereSql = "";
    //     $bindParams = [];

    //     if (!empty($searchValue)) {
    //         $whereSql = " WHERE (nama LIKE ? OR email LIKE ? OR role LIKE ?)";
            
    //         $searchParam = "%{$searchValue}%";
    //         $bindParams = [$searchParam, $searchParam, $searchParam];
    //     }

    //     $sqlFiltered = "SELECT COUNT(*) as total FROM $tableName" . $whereSql;
    //     $totalRecordWithFilter = $db->query($sqlFiltered, $bindParams)->getRow()->total;

    //     $allowedColumns = ['nama', 'email', 'role', 'id'];
    //     if (!in_array($orderColumn, $allowedColumns)) {
    //         $orderColumn = 'nama';
    //     }

    //     $sqlData = "SELECT * FROM $tableName" 
    //              . $whereSql 
    //              . " ORDER BY $orderColumn $orderDir" 
    //              . " LIMIT ? OFFSET ?";

    //     $bindParams[] = intval($length);
    //     $bindParams[] = intval($start);

    //     $data = $db->query($sqlData, $bindParams)->getResultArray();

    //     return [
    //         'totalRecords' => $totalRecords,
    //         'totalFiltered' => $totalRecordWithFilter,
    //         'data' => $data
    //     ];
    // }
}