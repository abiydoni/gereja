<?php

namespace App\Controllers;

class Warta extends BaseController
{
    public function index()
    {
        $gerejaModel = new \App\Models\GerejaModel();
        $gereja = $gerejaModel->first();
        
        $persembahanModel = new \App\Models\InformasiPersembahanModel();
        $keuanganModel = new \App\Models\KeuanganModel();
        
        // 1. Fetch All Active Offerings (Laporan Persembahan)
        $persembahan = $persembahanModel->where('status', 'aktif')
                                       ->orderBy('tanggal', 'DESC')
                                       ->findAll();

        // 2. Fetch Keuangan Summary
        $startOfThisMonth = date('Y-m-01');
        
        // Saldo Bulan Lalu (All before this month)
        $rowLalu = $keuanganModel->select('SUM(debet) as total_debet, SUM(kredit) as total_kredit')
                                 ->where('tanggal <', $startOfThisMonth)
                                 ->get()->getRow();
        $saldoBulanLalu = ($rowLalu->total_debet ?? 0) - ($rowLalu->total_kredit ?? 0);
        
        // Pemasukan Bulan Ini
        $pemasukanBulanIni = $keuanganModel->selectSum('debet')
                                          ->where('tanggal >=', $startOfThisMonth)
                                          ->get()->getRow()->debet ?? 0;
                                          
        // Pengeluaran Bulan Ini
        $pengeluaranBulanIni = $keuanganModel->selectSum('kredit')
                                            ->where('tanggal >=', $startOfThisMonth)
                                            ->get()->getRow()->kredit ?? 0;
                                            
        // Saldo Akhir (Realtime Total)
        $rowTotal = $keuanganModel->select('SUM(debet) as total_debet, SUM(kredit) as total_kredit')
                                  ->get()->getRow();
        $saldoAkhir = ($rowTotal->total_debet ?? 0) - ($rowTotal->total_kredit ?? 0);

        $renunganModel = new \App\Models\RenunganModel();
        $renungan = $renunganModel->getDailyRenungan();


        // Fetch Main Schedules (Pelayanan / Assignments)
        $jadwalUtamaModel = new \App\Models\JadwalIbadahUtamaModel();
        $detailModel = new \App\Models\JadwalPetugasDetailModel();
        
        // Get upcoming active schedules
        $upcomingSchedules = $jadwalUtamaModel->where('status', 'aktif')
                                            ->where('tanggal >=', date('Y-m-d'))
                                            ->orderBy('tanggal', 'ASC')
                                            ->limit(12)
                                            ->findAll();

        $jadwalList = [];
        if (!empty($upcomingSchedules)) {
            foreach($upcomingSchedules as $jadwal) {
                 $details = $detailModel->where('id_jadwal_utama', $jadwal['id_jadwal_utama'])->findAll();
                 $jadwal['petugas'] = $details;
                 $jadwalList[] = $jadwal;
            }
        } else {
             // Fallback: Show the most recent available active services (full date)
             $latest = $jadwalUtamaModel->where('status', 'aktif')
                                        ->orderBy('tanggal', 'DESC')
                                        ->first();
             if($latest) {
                $latestDateSchedules = $jadwalUtamaModel->where('status', 'aktif')
                                                       ->where('tanggal', $latest['tanggal'])
                                                       ->orderBy('jam', 'ASC')
                                                       ->findAll();
                foreach($latestDateSchedules as $ls) {
                    $details = $detailModel->where('id_jadwal_utama', $ls['id_jadwal_utama'])->findAll();
                    $ls['petugas'] = $details;
                    $jadwalList[] = $ls;
                }
             }
        }

        // Fetch Activities (Kegiatan)
        $kegiatanModel = new \App\Models\InformasiKegiatanModel();
        $kegiatan = $kegiatanModel->where('status', 'aktif')
                                  ->where('tanggal_selesai >=', date('Y-m-d'))
                                  ->orderBy('tanggal_mulai', 'ASC')
                                  ->findAll();

        // Fetch Other Information (Informasi Lain)
        $infoLainModel = new \App\Models\InformasiLainModel();
        $infoLain = $infoLainModel->where('status', 'aktif')
                                  ->orderBy('tanggal', 'DESC')
                                  ->limit(5)
                                  ->findAll();

        $data = [
            'title'                 => 'Warta Jemaat',
            'gereja'                => $gereja,
            'persembahan'           => $persembahan,
            'saldo_bulan_lalu'      => $saldoBulanLalu,
            'pemasukan_bulan_ini'   => $pemasukanBulanIni,
            'pengeluaran_bulan_ini' => $pengeluaranBulanIni,
            'saldo_akhir'           => $saldoAkhir,
            'renungan'              => $renungan,
            'jadwalList'            => $jadwalList,
            'kegiatan'              => $kegiatan,
            'infoLain'              => $infoLain
        ];

        return view('frontend/warta/index', $data);
    }
}
