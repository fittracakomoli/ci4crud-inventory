<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Hello World in CodeIgniter 4'
        ];

        return view('home', $data);
    }
}
