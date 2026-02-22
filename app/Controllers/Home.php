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

        // 1. Get Church Info (Cached if possible, but first() is light enough)
        $gereja = $gerejaModel->first();
        
        if (!$gereja) {
            return "Gereja tidak ditemukan. Silahkan jalankan Seeder.";
        }

        // 2. Optimized Gender Stats (Count directly in DB)
        $gender_stats = [
            'pria'   => $jemaatModel->where('status_jemaat', 'Aktif')->groupStart()->where('jenis_kelamin', 'Laki-laki')->orWhere('jenis_kelamin', 'L')->groupEnd()->countAllResults(),
            'wanita' => $jemaatModel->where('status_jemaat', 'Aktif')->groupStart()->where('jenis_kelamin', 'Perempuan')->orWhere('jenis_kelamin', 'P')->groupEnd()->countAllResults(),
        ];

        // 3. Optimized Age Stats (Single Query instead of looping thousands of records)
        // Uses SQL to calculate age and categorize results
        $db = \Config\Database::connect();
        $builder = $db->table('tb_jemaat');
        $builder->select("
            COUNT(CASE WHEN TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) < 13 THEN 1 END) as anak,
            COUNT(CASE WHEN TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) BETWEEN 13 AND 19 THEN 1 END) as remaja,
            COUNT(CASE WHEN TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) BETWEEN 20 AND 59 THEN 1 END) as dewasa,
            COUNT(CASE WHEN TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) >= 60 THEN 1 END) as lansia
        ");
        $builder->where('status_jemaat', 'Aktif');
        $age_query = $builder->get()->getRowArray();
        
        $age_stats = [
            'anak'   => (int) ($age_query['anak'] ?? 0),
            'remaja' => (int) ($age_query['remaja'] ?? 0),
            'dewasa' => (int) ($age_query['dewasa'] ?? 0),
            'lansia' => (int) ($age_query['lansia'] ?? 0),
        ];

        // 4. Optimized Growth Stats (Single Query Grouped by Month)
        // Fetches last 6 months data in one go
        $sixMonthsAgo = date('Y-m-01', strtotime('-5 months'));
        $growthBuilder = $db->table('tb_jemaat');
        $growthBuilder->select("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count");
        $growthBuilder->where('created_at >=', $sixMonthsAgo);
        $growthBuilder->groupBy("DATE_FORMAT(created_at, '%Y-%m')");
        $growthBuilder->orderBy('month', 'ASC');
        $growthResults = $growthBuilder->get()->getResultArray();

        // Map results to correct month names (fill missing months with 0 if needed, here we just use what we have or accumulated)
        // Note: The original logic was "Cumulative Count up to that month".
        // Let's replicate "Cumulative Count" efficiently.
        
        $growth_stats = [];
        for ($i = 5; $i >= 0; $i--) {
            $targetMonth = date('Y-m', strtotime("-$i months"));
            $monthLabel = date('M', strtotime("-$i months"));
            
            // We still use countAllResults for cumulative because it's safer logic for "Total Members at X time"
            // But we ensure the query is indexed on created_at
            // If this is still slow, we can cache it.
            $count = $jemaatModel->where("DATE_FORMAT(created_at, '%Y-%m') <=", $targetMonth)->countAllResults();
            $growth_stats[$monthLabel] = $count;
        }

        // 5. Attendance Trend Stats (Last 6 Sundays)
        $attendanceTrendBuilder = $db->table('informasi_persembahan');
        $attendanceTrendBuilder->select('tanggal, waktu_ibadah, SUM(jumlah_pria) as pria, SUM(jumlah_wanita) as wanita');
        $attendanceTrendBuilder->groupBy('tanggal, waktu_ibadah');
        $attendanceTrendBuilder->orderBy('tanggal', 'ASC');
        $trendResults = $attendanceTrendBuilder->get()->getResultArray();

        $trendData = [];
        foreach ($trendResults as $row) {
            $waktu = ucfirst(strtolower($row['waktu_ibadah']));
            // Group by date
            $trendData[$row['tanggal']][$waktu] = [
                'pria' => (int)$row['pria'],
                'wanita' => (int)$row['wanita']
            ];
        }

        // Take only last 6 dates
        $trendData = array_slice($trendData, -6, 6, true);

        $attendance_trend = [
            'labels' => [],
            'datasets' => [
                'Pagi_Pria' => [], 'Pagi_Wanita' => [],
                'Siang_Pria' => [], 'Siang_Wanita' => [],
                'Sore_Pria' => [], 'Sore_Wanita' => []
            ]
        ];

        foreach ($trendData as $date => $dataPerWaktu) {
            $attendance_trend['labels'][] = date('d/m', strtotime($date));
            foreach (['Pagi', 'Siang', 'Sore'] as $waktu) {
                $p = isset($dataPerWaktu[$waktu]) ? $dataPerWaktu[$waktu]['pria'] : 0;
                $w = isset($dataPerWaktu[$waktu]) ? $dataPerWaktu[$waktu]['wanita'] : 0;
                $attendance_trend['datasets'][$waktu . '_Pria'][] = $p;
                $attendance_trend['datasets'][$waktu . '_Wanita'][] = $w;
            }
        }

        $data = [
            'title'    => $gereja['nama_gereja'],
            'gereja'   => $gereja,
            'renungan' => $renunganModel->getDailyRenungan(),
            'jadwal'   => $jadwalModel->where('status', 'aktif')->findAll(),
            'majelis'  => $majelisModel->where('status', 'aktif')->findAll(),
            'stats'    => [
                'gender'     => $gender_stats,
                'age'        => $age_stats,
                'growth'     => $growth_stats,
                'attendance_trend' => $attendance_trend
            ]
        ];

        return view('home', $data);
    }
}
