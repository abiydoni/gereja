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

        $keyword = service('request')->getGet('keyword');
        $liturgiModel->where('status', 'aktif');
        
        if($keyword) {
             $liturgiModel->groupStart()
                          ->like('judul', $keyword)
                          ->orLike('isi_liturgi', $keyword)
                          ->groupEnd();
        }

        $activeLiturgies = $liturgiModel->orderBy('tanggal', 'DESC')->findAll();

        // Redirect hanya jika TIDAK sedang mencari dan hanya ada 1 hasil
        if (!$keyword && count($activeLiturgies) === 1) {
            // Jika hanya 1 yang aktif, langsung redirect ke detail
            return redirect()->to('liturgi/' . $activeLiturgies[0]['id_liturgi'] . '?from=auto');
        }

        $data = [
            'title'   => 'Daftar Liturgi',
            'gereja'  => $gereja,
            'liturgi' => $activeLiturgies,
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
