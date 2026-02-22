<?php

namespace App\Controllers\Dashboard;

use App\Controllers\BaseController;
use App\Models\KidungModel;

class Kidung extends BaseController
{
    protected $kidungModel;
    protected $logModel;

    public function __construct()
    {
        $this->kidungModel = new KidungModel();
        $this->logModel = new \App\Models\ActivityLogModel();
    }

    public function index()
    {
        $keyword = $this->request->getGet('q');
        
        $query = $this->kidungModel;
        if ($keyword) {
            $query = $query->search($keyword);
        }

        $data = [
            'title'   => 'Manajemen Kidung Jemaat',
            'kidung'  => $query->orderBy('nomor', 'ASC')->paginate(20, 'kidung'),
            'pager'   => $this->kidungModel->pager,
            'keyword' => $keyword
        ];
        return view('dashboard/kidung/index', $data);
    }

    public function create()
    {
        $data = ['title' => 'Tambah Kidung Jemaat'];
        return view('dashboard/kidung/create', $data);
    }

    public function store()
    {
        if (!$this->validate([
            'nomor'      => 'required|numeric',
            'judul'      => 'required',
            'isi'        => 'required',
            'nada_dasar' => 'permit_empty|string',
            'pengarang'  => 'permit_empty|string',
        ])) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal. Cek inputan anda.');
        }

        $this->kidungModel->save([
            'nomor'      => $this->request->getPost('nomor'),
            'judul'      => $this->request->getPost('judul'),
            'isi'        => $this->request->getPost('isi'),
            'nada_dasar' => $this->request->getPost('nada_dasar'),
            'pengarang'  => $this->request->getPost('pengarang'),
        ]);

        $newId = $this->kidungModel->insertID();
        $this->logModel->add('CREATE', 'kidung_jemaat', $newId, null, $this->request->getPost());

        return redirect()->to('/dashboard/kidung')->with('success', 'Lagu berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $song = $this->kidungModel->find($id);
        if (!$song) {
            return redirect()->to('/dashboard/kidung')->with('error', 'Lagu tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Kidung Jemaat',
            'song' => $song,
        ];
        return view('dashboard/kidung/edit', $data);
    }

    public function update($id)
    {
        $song = $this->kidungModel->find($id);
        if (!$song) {
            return redirect()->to('/dashboard/kidung')->with('error', 'Lagu tidak ditemukan.');
        }

        if (!$this->validate([
            'nomor'      => 'required|numeric',
            'judul'      => 'required',
            'isi'        => 'required',
            'nada_dasar' => 'permit_empty|string',
            'pengarang'  => 'permit_empty|string',
        ])) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal.');
        }

        $dataUpdate = [
            'nomor'      => $this->request->getPost('nomor'),
            'judul'      => $this->request->getPost('judul'),
            'isi'        => $this->request->getPost('isi'),
            'nada_dasar' => $this->request->getPost('nada_dasar'),
            'pengarang'  => $this->request->getPost('pengarang'),
        ];

        $oldData = $song;
        $this->kidungModel->update($id, $dataUpdate);

        $this->logModel->add('UPDATE', 'kidung_jemaat', $id, $oldData, $dataUpdate);

        return redirect()->to('/dashboard/kidung')->with('success', 'Lagu berhasil diperbarui.');
    }

    public function delete($id)
    {
        $oldData = $this->kidungModel->find($id);
        if ($oldData) {
            $this->kidungModel->delete($id);
            $this->logModel->add('DELETE', 'kidung_jemaat', $id, $oldData, null);
        }
        
        return redirect()->to('/dashboard/kidung')->with('success', 'Lagu berhasil dihapus.');
    }
}
