<?php

namespace Modules\Inventory\Models;

use CodeIgniter\Model;

class Inventory extends Model
{
    protected $table = 'barang';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_kategori', 'nama_barang', 'deskripsi', 'stok', 'harga', 'gambar'];

    protected $useTimestamps = true;

    public function getDatatableData($start, $length, $search, $col, $dir)
    {
        $query = "SELECT b.*, k.nama as kategori
                  FROM {$this->table} b
                  LEFT JOIN kategori k ON b.id_kategori = k.id ";
        $countQuery = "SELECT COUNT(*) as total
                       FROM {$this->table} b
                       LEFT JOIN kategori k ON b.id_kategori = k.id ";

        $where = "";
        if($search) {
            $where = " WHERE b.nama_barang LIKE '%$search%' OR k.nama LIKE '%$search%'";
        }

        if(empty($col)) {
            $col = 'b.id';
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
