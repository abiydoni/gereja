<?php

namespace App\Controllers;

use App\Models\JadwalIbadahModel;

class Jadwal extends BaseController
{
    public function index()
    {
        $gerejaModel = new \App\Models\GerejaModel();
        $gereja = $gerejaModel->first();
        
        $jadwalModel = new JadwalIbadahModel();
        $jadwal = $jadwalModel->where('status', 'aktif')->findAll();

        $data = [
            'title'  => 'Jadwal Ibadah',
            'gereja' => $gereja,
            'jadwal' => $jadwal,
        ];

        return view('frontend/jadwal/index', $data);
    }
}
