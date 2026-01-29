<?php

namespace App\Controllers\Dashboard;

use App\Controllers\BaseController;
use App\Models\RenunganModel;

class Renungan extends BaseController
{
    protected $renunganModel;

    public function __construct()
    {
        $this->renunganModel = new RenunganModel();
        // Ideally fetch from session in methods or middleware, 
        // but constructor session access can be tricky in CI4 depending on version/setup.
        // We'll access session in methods.
    }

    public function index()
    {
        $data = [
            'title' => 'Manajemen Renungan',
            'renungan' => $this->renunganModel->orderBy('tanggal', 'DESC')->findAll(),
        ];
        return view('dashboard/renungan/index', $data);
    }

    public function create()
    {
        $data = ['title' => 'Tambah Renungan'];
        return view('dashboard/renungan/create', $data);
    }

    public function store()
    {


        if (!$this->validate([
            'judul'   => 'required',
            'isi'     => 'required',
            'tanggal' => 'required|valid_date',
            'gambar'  => 'is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]|max_size[gambar,2048]',
        ])) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal. Cek inputan anda.');
        }

        // Handle Image Upload
        $fileGambar = $this->request->getFile('gambar');
        $namaGambar = null;
        if ($fileGambar && $fileGambar->isValid() && !$fileGambar->hasMoved()) {
            $namaGambar = $fileGambar->getRandomName();
            $fileGambar->move('uploads/renungan', $namaGambar);
        }

        $this->renunganModel->save([
            'judul'     => $this->request->getPost('judul'),
            'isi'       => $this->request->getPost('isi'),
            'tanggal'   => $this->request->getPost('tanggal'),
            'gambar'    => $namaGambar,
            'status'    => $this->request->getPost('status') ?? 'aktif',
        ]);

        return redirect()->to('/dashboard/renungan')->with('success', 'Data berhasil disimpan.');
    }

    public function edit($id)
    {
        $renungan = $this->renunganModel->find($id);
        if (!$renungan) {
            return redirect()->to('/dashboard/renungan')->with('error', 'Data tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Renungan',
            'renungan' => $renungan,
        ];
        return view('dashboard/renungan/edit', $data);
    }

    public function update($id)
    {
        if (!$this->validate([
            'judul'   => 'required',
            'isi'     => 'required',
            'tanggal' => 'required|valid_date',
            'gambar'  => 'is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]|max_size[gambar,2048]',
        ])) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal.');
        }

        $dataUpdate = [
            'judul'     => $this->request->getPost('judul'),
            'isi'       => $this->request->getPost('isi'),
            'tanggal'   => $this->request->getPost('tanggal'),
            'status'    => $this->request->getPost('status'),
        ];

        // Handle Image Update
        $fileGambar = $this->request->getFile('gambar');
        if ($fileGambar && $fileGambar->isValid() && !$fileGambar->hasMoved()) {
            $namaGambar = $fileGambar->getRandomName();
            $fileGambar->move('uploads/renungan', $namaGambar);
            $dataUpdate['gambar'] = $namaGambar;
        }

        $this->renunganModel->update($id, $dataUpdate);

        return redirect()->to('/dashboard/renungan')->with('success', 'Data berhasil diperbarui.');
    }

    public function delete($id)
    {
        $this->renunganModel->delete($id);
        return redirect()->to('/dashboard/renungan')->with('success', 'Data berhasil dihapus.');
    }
}
