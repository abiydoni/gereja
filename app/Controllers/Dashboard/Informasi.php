<?php

namespace App\Controllers\Dashboard;

use App\Controllers\BaseController;
use App\Models\InformasiLainModel;

class Informasi extends BaseController
{
    protected $infoModel;
    protected $logModel;

    public function __construct()
    {
        $this->infoModel = new InformasiLainModel();
        $this->logModel = new \App\Models\ActivityLogModel();
    }

    public function index()
    {

        $data = [
            'title' => 'Manajemen Informasi Lain',
            'informasi' => $this->infoModel->orderBy('created_at', 'DESC')->findAll(),
        ];
        return view('dashboard/informasi/index', $data);
    }

    public function create()
    {
        $data = ['title' => 'Tambah Informasi'];
        return view('dashboard/informasi/create', $data);
    }

    public function store()
    {

        if (!$this->validate([
            'judul'     => 'required',
            'deskripsi' => 'required',
            'tanggal'   => 'required|valid_date',
            'gambar'    => 'permit_empty|uploaded[gambar]|max_size[gambar,2048]|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]',
        ])) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal. Cek inputan anda.');
        }

        $fileGambar = $this->request->getFile('gambar');
        $namaGambar = null;
        if ($fileGambar && $fileGambar->isValid() && !$fileGambar->hasMoved()) {
            $namaGambar = $fileGambar->getRandomName();
            $fileGambar->move('uploads/informasi', $namaGambar);
        }

        $this->infoModel->save([
            'judul'     => $this->request->getPost('judul'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'tanggal'   => $this->request->getPost('tanggal'),
            'gambar'    => $namaGambar,
            'status'    => $this->request->getPost('status') ?? 'aktif',
        ]);
        
        $newId = $this->infoModel->insertID();
        $this->logModel->add('CREATE', 'informasi_lain', $newId, null, $this->request->getPost());

        return redirect()->to('/dashboard/informasi')->with('success', 'Data berhasil disimpan.');
    }

    public function edit($id)
    {
        $info = $this->infoModel->find($id);
        if (!$info) {
            return redirect()->to('/dashboard/informasi')->with('error', 'Data tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Informasi',
            'informasi' => $info,
        ];
        return view('dashboard/informasi/edit', $data);
    }

    public function update($id)
    {
        if (!$this->validate([
            'judul'     => 'required',
            'deskripsi' => 'required',
            'tanggal'   => 'required|valid_date',
            'gambar'    => 'permit_empty|uploaded[gambar]|max_size[gambar,2048]|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]',
        ])) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal.');
        }

        $infoLama = $this->infoModel->find($id);
        $fileGambar = $this->request->getFile('gambar');
        $namaGambar = $infoLama['gambar'];

        if ($fileGambar && $fileGambar->isValid() && !$fileGambar->hasMoved()) {
            // Delete old info if exists
            if ($namaGambar && file_exists('uploads/informasi/' . $namaGambar)) {
                unlink('uploads/informasi/' . $namaGambar);
            }
            $namaGambar = $fileGambar->getRandomName();
            $fileGambar->move('uploads/informasi', $namaGambar);
        }

        $updateData = [
            'judul'     => $this->request->getPost('judul'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'tanggal'   => $this->request->getPost('tanggal'),
            'gambar'    => $namaGambar,
            'status'    => $this->request->getPost('status'),
        ];
        
        $oldData = $this->infoModel->asArray()->find($id);
        $this->infoModel->update($id, $updateData);
        
        $this->logModel->add('UPDATE', 'informasi_lain', $id, $oldData, $updateData);

        return redirect()->to('/dashboard/informasi')->with('success', 'Data berhasil diperbarui.');
    }

    public function delete($id)
    {
        $info = $this->infoModel->find($id);
        if ($info['gambar'] && file_exists('uploads/informasi/' . $info['gambar'])) {
            unlink('uploads/informasi/' . $info['gambar']);
        }
        $oldData = $this->infoModel->asArray()->find($id);
        $this->infoModel->delete($id);
        $this->logModel->add('DELETE', 'informasi_lain', $id, $oldData, null);
        return redirect()->to('/dashboard/informasi')->with('success', 'Data berhasil dihapus.');
    }
}
