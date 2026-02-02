<?php

namespace Modules\Tes\Controllers;

use App\Controllers\BaseController;

class TesController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new \Modules\Tes\Models\Tes();
    }

    public function index()
    {
        $data = [
            'title' => 'Data User (Dummy)',
        ];
        return view('Modules\Tes\Views\Tes', $data);
    }

    public function getData()
    {
        $request = \Config\Services::request();

        $draw           = $request->getVar('draw');
        $start          = $request->getVar('start');
        $length         = $request->getVar('length');
        $order          = $request->getVar('order');
        $columns        = $request->getVar('columns');
        $searchValue    = $request->getVar('search')['value'] ?? '';

        $columnIndex    = $order[0]['column']; 
        $orderColumn    = $columns[$columnIndex]['data'];
        $orderDir       = $order[0]['dir'];

        $result = $this->userModel->getDatatablesData(
            $start, 
            $length, 
            $searchValue, 
            $orderColumn, 
            $orderDir
        );

        return $this->response->setJSON([
            "draw"            => intval($draw),
            "recordsTotal"    => intval($result['recordsTotal']),
            "recordsFiltered" => intval($result['recordsFiltered']),
            "data"            => $result['data']
        ]);
    }
}

// <?php

// namespace Modules\Tes\Controllers;

// use App\Controllers\BaseController;

// class TesController extends BaseController
// {
//     protected $userModel;

//     public function __construct()
//     {
//         $this->userModel = new \Modules\Tes\Models\Tes();
//     }

//     public function index()
//     {
//         $data = [
//             'title' => 'Tes Pagination AJAX',
//         ];
//         return view('Modules\Tes\Views\Tes', $data);
//     }

//     public function getData()
//     {
//         $data = $this->userModel->findAll();

//         $page = $this->request->getVar('page') ? (int)$this->request->getVar('page') : 1;
//         $limit = 10;

//         $totalData = count($data);
//         $totalPages = ceil($totalData / $limit);

//         $offset = ($page - 1) * $limit;
        
//         $dataHalamanIni = array_slice($data, $offset, $limit);

//         return $this->response->setJSON([
//             'data' => $data,
//             'pagination' => [
//                 'current_page' => $page,
//                 'total_pages' => $totalPages,
//                 'has_next' => $page < $totalPages,
//                 'has_prev' => $page > 1
//             ]
//         ]);
//     }
// }