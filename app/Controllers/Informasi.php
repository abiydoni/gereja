<?php

namespace App\Controllers;

use App\Models\InformasiLainModel;

class Informasi extends BaseController
{
    public function index()
    {
        $gerejaModel = new \App\Models\GerejaModel();
        $gereja = $gerejaModel->first();
        
        $infoModel = new InformasiLainModel();
        $informasi = $infoModel->where('status', 'aktif')->orderBy('created_at', 'DESC')->findAll();

        $data = [
            'title'     => 'Informasi Lain',
            'gereja'    => $gereja,
            'informasi' => $informasi,
        ];

        return view('frontend/informasi/index', $data);
    }
}
