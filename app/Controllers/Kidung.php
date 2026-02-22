<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KidungModel;

class Kidung extends BaseController
{
    protected $kidungModel;

    public function __construct()
    {
        $this->kidungModel = new KidungModel();
    }

    public function index()
    {
        $keyword = $this->request->getGet('q');
        
        $query = $this->kidungModel;
        if ($keyword) {
            $query = $query->search($keyword);
        }

        $data = [
            'title'   => 'Kidung Jemaat',
            'kidung'  => $query->orderBy('nomor', 'ASC')->paginate(20, 'kidung'),
            'pager'   => $this->kidungModel->pager,
            'keyword' => $keyword
        ];

        return view('frontend/kidung/index', $data);
    }

    public function detail($nomor)
    {
        $song = $this->kidungModel->where('nomor', $nomor)->first();

        if (!$song) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Lagu Kidung Jemaat nomor $nomor tidak ditemukan.");
        }

        $data = [
            'title' => 'KJ ' . $song['nomor'] . ' - ' . $song['judul'],
            'song'  => $song
        ];

        return view('frontend/kidung/detail', $data);
    }
}
