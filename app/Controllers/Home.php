<?php

namespace App\Controllers;

use App\Models\GerejaModel;
use App\Models\RenunganModel;
use App\Models\JadwalIbadahModel;
use App\Models\MajelisModel;

class Home extends BaseController
{
    public function index()
    {
        $gerejaModel   = new GerejaModel();
        $renunganModel = new RenunganModel();
        $jadwalModel   = new JadwalIbadahModel();
        $majelisModel  = new MajelisModel();

        $gereja = $gerejaModel->first();
        
        if (!$gereja) {
            return "Gereja tidak ditemukan. Silahkan jalankan Seeder.";
        }

        $data = [
            'title'    => $gereja['nama_gereja'],
            'gereja'   => $gereja,
            'renungan' => $renunganModel->where('status', 'aktif')->orderBy('tanggal', 'DESC')->first(),
            'jadwal'   => $jadwalModel->where('status', 'aktif')->findAll(),
            'majelis'  => $majelisModel->where('status', 'aktif')->findAll(),
        ];

        return view('home', $data);
    }
}
