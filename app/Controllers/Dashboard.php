<?php

namespace App\Controllers;

use App\Models\GerejaModel;
use App\Models\RenunganModel;
use App\Models\JadwalIbadahModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $gerejaModel   = new GerejaModel();
        $renunganModel = new RenunganModel();
        $jadwalModel   = new JadwalIbadahModel();

        $data = [
            'title'         => 'Dashboard Overview',
            'gereja'        => $gerejaModel->first(),
            'total_renungan'=> $renunganModel->countAllResults(),
            'total_jadwal'  => $jadwalModel->countAllResults(),
        ];

        return view('dashboard/index', $data);
    }
}
