<?php

namespace App\Controllers\Dashboard;

use App\Controllers\BaseController;
use App\Models\GaleriModel;

class Galeri extends BaseController
{
    protected $galeriModel;
    protected $logModel;

    public function __construct()
    {
        $this->galeriModel = new GaleriModel();
        $this->logModel = new \App\Models\ActivityLogModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Manajemen Galeri Multimedia',
            'items' => $this->galeriModel->orderBy('created_at', 'DESC')->findAll(),
        ];
        return view('dashboard/galeri/index', $data);
    }

    public function create()
    {
        $data = ['title' => 'Tambah Item Galeri'];
        return view('dashboard/galeri/create', $data);
    }

    public function store()
    {
        if (!$this->validate([
            'judul'      => 'required',
            'kategori'   => 'required',
            'link_media' => 'required',
        ])) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal. Pastikan semua field terisi.');
        }

        $this->galeriModel->save([
            'id_gereja'  => 1, // Default church ID
            'judul'      => $this->request->getPost('judul'),
            'kategori'   => $this->request->getPost('kategori'),
            'link_media' => $this->request->getPost('link_media'),
            'keterangan' => $this->request->getPost('keterangan'),
            'status'     => $this->request->getPost('status') ?? 'aktif',
        ]);
        
        $newId = $this->galeriModel->insertID();
        $this->logModel->add('CREATE', 'galeri', $newId, null, $this->request->getPost());

        return redirect()->to('/dashboard/galeri')->with('success', 'Item galeri berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $item = $this->galeriModel->find($id);
        if (!$item) {
            return redirect()->to('/dashboard/galeri')->with('error', 'Item tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Galeri',
            'item'  => $item,
        ];
        return view('dashboard/galeri/edit', $data);
    }

    public function update($id)
    {
        if (!$this->validate([
            'judul'      => 'required',
            'kategori'   => 'required',
            'link_media' => 'required',
        ])) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal.');
        }

        $updateData = [
            'judul'      => $this->request->getPost('judul'),
            'kategori'   => $this->request->getPost('kategori'),
            'link_media' => $this->request->getPost('link_media'),
            'keterangan' => $this->request->getPost('keterangan'),
            'status'     => $this->request->getPost('status'),
        ];
        
        $oldData = $this->galeriModel->asArray()->find($id);
        $this->galeriModel->update($id, $updateData);
        
        $this->logModel->add('UPDATE', 'galeri', $id, $oldData, $updateData);

        return redirect()->to('/dashboard/galeri')->with('success', 'Item galeri berhasil diperbarui.');
    }

    public function delete($id)
    {
        $oldData = $this->galeriModel->asArray()->find($id);
        $this->galeriModel->delete($id);
        $this->logModel->add('DELETE', 'galeri', $id, $oldData, null);
        return redirect()->to('/dashboard/galeri')->with('success', 'Item galeri berhasil dihapus.');
    }
}
