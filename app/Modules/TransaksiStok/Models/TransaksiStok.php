<?php

namespace Modules\TransaksiStok\Models;

use CodeIgniter\Model;

class TransaksiStok extends Model
{
    protected $table = 'transaksi_stok';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_barang', 'id_supplier', 'id_divisi', 'invoice', 'jenis', 'jumlah', 'keterangan'];

    protected $useTimestamps = true;

    public function GetDatatableData($start, $length, $search, $dir, $col)
    {
        $query = "SELECT t.*, b.nama_barang, s.nama_supplier, d.nama_divisi 
                  FROM {$this->table} t
                  LEFT JOIN barang b ON t.id_barang = b.id
                  LEFT JOIN supplier s ON t.id_supplier = s.id
                  LEFT JOIN divisi d ON t.id_divisi = d.id";
        $countQuery = "SELECT COUNT(*) as total 
                       FROM {$this->table} t
                       LEFT JOIN barang b ON t.id_barang = b.id
                       LEFT JOIN supplier s ON t.id_supplier = s.id
                       LEFT JOIN divisi d ON t.id_divisi = d.id";
        
        $where = "";
        if ($search) {
            $where = " WHERE b.nama_barang LIKE '%$search%' 
                       OR s.nama_supplier LIKE '%$search%' 
                       OR d.nama_divisi LIKE '%$search%' 
                       OR t.invoice LIKE '%$search%' 
                       OR t.jenis LIKE '%$search%' 
                       OR t.keterangan LIKE '%$search%'";
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

    public function simpanTransaksi($data)
    {
        $this->db->transStart();

        $this->insert($data);

        $inventoryModel = new \Modules\Inventory\Models\Inventory();
        $barang = $inventoryModel->find($data['id_barang']);

        if ($data['jenis'] === 'masuk') {
            $barang['stok'] += $data['jumlah'];
        } elseif ($data['jenis'] === 'keluar') {
            $barang['stok'] -= $data['jumlah'];
        }

        $inventoryModel->update($data['id_barang'], ['stok' => $barang['stok']]);

        $this->db->transComplete();

        return $this->db->transStatus();
    }
}
