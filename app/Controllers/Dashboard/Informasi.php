<?php

namespace App\Controllers\Dashboard;

use App\Controllers\BaseController;
use App\Models\InformasiLainModel;

class Informasi extends BaseController
{
    protected $infoModel;

    public function __construct()
    {
        $this->infoModel = new InformasiLainModel();
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
            'id_gereja' => 1, // Default church ID
            'judul'     => $this->request->getPost('judul'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'tanggal'   => $this->request->getPost('tanggal'),
            'gambar'    => $namaGambar,
            'status'    => $this->request->getPost('status') ?? 'aktif',
        ]);

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

        $this->infoModel->update($id, [
            'judul'     => $this->request->getPost('judul'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'tanggal'   => $this->request->getPost('tanggal'),
            'gambar'    => $namaGambar,
            'status'    => $this->request->getPost('status'),
        ]);

        return redirect()->to('/dashboard/informasi')->with('success', 'Data berhasil diperbarui.');
    }

    public function delete($id)
    {
        $info = $this->infoModel->find($id);
        if ($info['gambar'] && file_exists('uploads/informasi/' . $info['gambar'])) {
            unlink('uploads/informasi/' . $info['gambar']);
        }
        $this->infoModel->delete($id);
        return redirect()->to('/dashboard/informasi')->with('success', 'Data berhasil dihapus.');
    }
}
