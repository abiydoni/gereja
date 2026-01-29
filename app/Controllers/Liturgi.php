<?php

namespace App\Controllers;

use App\Models\LiturgiModel;
use App\Models\GerejaModel;

class Liturgi extends BaseController
{
    public function index()
    {
        $liturgiModel = new LiturgiModel();
        $gerejaModel = new GerejaModel();
        
        $gereja = $gerejaModel->first();
        if(!$gereja) return "Data gereja kosong.";

        $data = [
            'title'   => 'Daftar Liturgi',
            'gereja'  => $gereja,
            'liturgi' => $liturgiModel->where('status', 'aktif')->orderBy('tanggal', 'DESC')->findAll(),
        ];

        return view('frontend/liturgi/index', $data);
    }

    public function detail($id)
    {
        $liturgiModel = new LiturgiModel();
        $gerejaModel = new GerejaModel();
        
        $item = $liturgiModel->find($id);
        
        if (!$item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $gereja = $gerejaModel->first();

        $data = [
            'title' => $item['judul'],
            'gereja' => $gereja,
            'item'  => $item,
        ];

        return view('frontend/liturgi/detail', $data);
    }
}
