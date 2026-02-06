<?php

namespace App\Controllers;

use App\Models\ArtikelModel;
use App\Models\GerejaModel;

class Artikel extends BaseController
{
    protected $artikelModel;
    protected $gerejaModel;

    public function __construct()
    {
        $this->artikelModel = new ArtikelModel();
        $this->gerejaModel = new GerejaModel();
    }

    public function index()
    {
        $gereja = $this->gerejaModel->first();
        
        $keyword = service('request')->getGet('keyword');
        $this->artikelModel->where('status', 'aktif');
        
        if($keyword) {
            $this->artikelModel->groupStart()
                               ->like('judul', $keyword)
                               ->orLike('isi', $keyword)
                               ->orLike('penulis', $keyword)
                               ->groupEnd();
        }

        $artikels = $this->artikelModel->orderBy('created_at', 'DESC')->paginate(10, 'artikel');

        $data = [
            'title'     => 'Artikel & Berita',
            'gereja'    => $gereja,
            'artikels'  => $artikels,
            'pager'     => $this->artikelModel->pager,
        ];

        return view('frontend/artikel/index', $data);
    }

    public function detail($slug)
    {
        $gereja = $this->gerejaModel->first();
        $artikel = $this->artikelModel->where('slug', $slug)->where('status', 'aktif')->first();

        if (!$artikel) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title'     => $artikel['judul'],
            'gereja'    => $gereja,
            'artikel'   => $artikel,
        ];

        return view('frontend/artikel/detail', $data);
    }
}
