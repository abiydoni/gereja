<?php

namespace App\Controllers;

use App\Models\GerejaModel;
use App\Models\RenunganModel;
use App\Models\JadwalIbadahModel;
use App\Models\KeuanganModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $gerejaModel   = new GerejaModel();
        $renunganModel = new RenunganModel();
        $jadwalModel   = new JadwalIbadahModel();

        $keuanganModel = new KeuanganModel();

        // Data Keuangan 12 Bulan Terakhir
        $financialData = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $monthName = date('M Y', strtotime("-$i months"));
            
            $pemasukan = $keuanganModel->selectSum('debet')
                ->where("DATE_FORMAT(tanggal, '%Y-%m') =", $month)
                ->get()->getRow()->debet ?? 0;
                
            $pengeluaran = $keuanganModel->selectSum('kredit')
                ->where("DATE_FORMAT(tanggal, '%Y-%m') =", $month)
                ->get()->getRow()->kredit ?? 0;
            
            $financialData[] = [
                'bulan' => $monthName,
                'masuk' => (float)$pemasukan,
                'keluar'=> (float)$pengeluaran
            ];
        }

        // Hitung Saldo Realtime
        $rowTotal = $keuanganModel->select('SUM(debet) as total_debet, SUM(kredit) as total_kredit')
                                ->get()->getRow();
        $saldoRealtime = ($rowTotal->total_debet ?? 0) - ($rowTotal->total_kredit ?? 0);

        $jemaatModel   = new \App\Models\JemaatModel();
        
        // --- JEMAAT STATISTICS ---
        
        // 1. Gender Distribution
        $genderData = [
            'pria'   => $jemaatModel->where('jenis_kelamin', 'L')->countAllResults(),
            'wanita' => $jemaatModel->where('jenis_kelamin', 'P')->countAllResults(),
        ];
        
        // 2. Growth Data (Cumulative last 12 months)
        $growthData = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $monthName = date('M Y', strtotime("-$i months"));
            $totalUpToMonth = $jemaatModel->where("DATE_FORMAT(created_at, '%Y-%m') <=", $month)->countAllResults();
            $growthData[] = [
                'bulan' => $monthName,
                'total' => $totalUpToMonth
            ];
        }

        // 3. Age Distribution
        $allJemaat = $jemaatModel->select('tanggal_lahir')->findAll();
        $ageData = [
            'Anak (<12)' => 0,
            'Remaja (12-17)' => 0,
            'Pemuda (18-25)' => 0,
            'Dewasa (26-45)' => 0,
            'Lansia (46-60)' => 0,
            'Lanjut Usia (>60)' => 0,
        ];
        foreach ($allJemaat as $j) {
            if (!$j['tanggal_lahir']) continue;
            $age = date_diff(date_create($j['tanggal_lahir']), date_create('today'))->y;
            if ($age < 12) $ageData['Anak (<12)']++;
            elseif ($age <= 17) $ageData['Remaja (12-17)']++;
            elseif ($age <= 25) $ageData['Pemuda (18-25)']++;
            elseif ($age <= 45) $ageData['Dewasa (26-45)']++;
            elseif ($age <= 60) $ageData['Lansia (46-60)']++;
            else $ageData['Lanjut Usia (>60)']++;
        }

        $data = [
            'title'          => 'Dashboard Overview',
            'gereja'         => $gerejaModel->first(),
            'total_renungan' => $renunganModel->countAllResults(),
            'total_jadwal'   => $jadwalModel->countAllResults(),
            'total_jemaat'   => $jemaatModel->countAllResults(),
            'financialData'  => $financialData,
            'saldo_realtime' => $saldoRealtime,
            'genderData'     => $genderData,
            'growthData'     => $growthData,
            'ageData'        => $ageData
        ];

        return view('dashboard/index', $data);
    }
}
