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
        $artikels = $this->artikelModel->where('status', 'aktif')->orderBy('created_at', 'DESC')->findAll();

        $data = [
            'title'     => 'Artikel & Berita',
            'gereja'    => $gereja,
            'artikels'  => $artikels,
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
