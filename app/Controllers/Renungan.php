<?php

namespace App\Controllers;

use App\Models\RenunganModel;
use App\Models\GerejaModel;

class Renungan extends BaseController
{
    protected $renunganModel;
    protected $gerejaModel;

    public function __construct()
    {
        $this->renunganModel = new RenunganModel();
        $this->gerejaModel = new GerejaModel();
    }

    public function index()
    {
        $gereja = $this->gerejaModel->first();
        // Get latest 3 for home/index page usually, but here maybe list all paginated
        $renungan = $this->renunganModel->where('status', 'aktif')->orderBy('tanggal', 'DESC')->paginate(6, 'renungan');

        $data = [
            'title'     => 'Renungan Harian',
            'gereja'    => $gereja,
            'renungan'  => $renungan,
            'pager'     => $this->renunganModel->pager,
        ];

        return view('frontend/renungan/index', $data);
    }

    public function arsip()
    {
        $gereja = $this->gerejaModel->first();
        // Archive might imply all, essentially same as index but maybe different view
        $renungan = $this->renunganModel->where('status', 'aktif')->orderBy('tanggal', 'DESC')->paginate(12, 'renungan');

        $data = [
            'title'     => 'Arsip Renungan',
            'gereja'    => $gereja,
            'renungan'  => $renungan,
            'pager'     => $this->renunganModel->pager,
        ];

        return view('frontend/renungan/arsip', $data);
    }

    public function detail($id)
    {
        $gereja = $this->gerejaModel->first();
        $renungan = $this->renunganModel->where('id_renungan', $id)->where('status', 'aktif')->first();

        if (!$renungan) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title'     => $renungan['judul'],
            'gereja'    => $gereja,
            'renungan'  => $renungan,
        ];

        return view('frontend/renungan/detail', $data);
    }
}
