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
        
        $keyword = service('request')->getGet('keyword');
        $kegiatanModel->where('status', 'aktif');
        
        if($keyword) {
            $kegiatanModel->groupStart()
                          ->like('nama_kegiatan', $keyword)
                          ->orLike('deskripsi', $keyword)
                          ->orLike('lokasi', $keyword)
                          ->groupEnd();
        }

        $kegiatan = $kegiatanModel->orderBy('tanggal_mulai', 'ASC')->paginate(9, 'kegiatan');

        $data = [
            'title'    => 'Kegiatan Gereja',
            'gereja'   => $gereja,
            'kegiatan' => $kegiatan,
            'pager'    => $kegiatanModel->pager,
        ];

        return view('frontend/kegiatan/index', $data);
    }
}
