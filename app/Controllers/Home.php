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
        $jemaatModel   = new \App\Models\JemaatModel();

        $gereja = $gerejaModel->first();
        
        if (!$gereja) {
            return "Gereja tidak ditemukan. Silahkan jalankan Seeder.";
        }

        // Stats Jemaat
        $gender_stats = [
            'pria'   => $jemaatModel->groupStart()->where('jenis_kelamin', 'Laki-laki')->orWhere('jenis_kelamin', 'L')->groupEnd()->countAllResults(),
            'wanita' => $jemaatModel->groupStart()->where('jenis_kelamin', 'Perempuan')->orWhere('jenis_kelamin', 'P')->groupEnd()->countAllResults(),
        ];

        // Age Analysis (Simple)
        $all_jemaat = $jemaatModel->where('status_jemaat', 'Aktif')->findAll();
        $age_stats = ['anak' => 0, 'remaja' => 0, 'dewasa' => 0, 'lansia' => 0];
        foreach ($all_jemaat as $j) {
            if (!$j['tanggal_lahir']) continue;
            $age = date_diff(date_create($j['tanggal_lahir']), date_create('today'))->y;
            if ($age < 13) $age_stats['anak']++;
            elseif ($age < 20) $age_stats['remaja']++;
            elseif ($age < 60) $age_stats['dewasa']++;
            else $age_stats['lansia']++;
        }

        // Growth Analysis (Last 6 Months)
        $growth_stats = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $count = $jemaatModel->where("DATE_FORMAT(created_at, '%Y-%m') <=", $month)->countAllResults();
            $growth_stats[date('M', strtotime("-$i months"))] = $count;
        }

        $data = [
            'title'    => $gereja['nama_gereja'],
            'gereja'   => $gereja,
            'renungan' => $renunganModel->where('status', 'aktif')->orderBy('tanggal', 'DESC')->first(),
            'jadwal'   => $jadwalModel->where('status', 'aktif')->findAll(),
            'majelis'  => $majelisModel->where('status', 'aktif')->findAll(),
            'stats'    => [
                'gender' => $gender_stats,
                'age'    => $age_stats,
                'growth' => $growth_stats
            ]
        ];

        return view('home', $data);
    }
}
