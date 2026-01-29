<?php

namespace App\Controllers;

use App\Models\InformasiKegiatanModel;

class Kegiatan extends BaseController
{
    public function index()
    {
        $gerejaModel = new \App\Models\GerejaModel();
        $gereja = $gerejaModel->first();
        
        $kegiatanModel = new InformasiKegiatanModel();
        $kegiatan = $kegiatanModel->where('status', 'aktif')->orderBy('tanggal_mulai', 'ASC')->findAll();

        $data = [
            'title'    => 'Kegiatan Gereja',
            'gereja'   => $gereja,
            'kegiatan' => $kegiatan,
        ];

        return view('frontend/kegiatan/index', $data);
    }
}
