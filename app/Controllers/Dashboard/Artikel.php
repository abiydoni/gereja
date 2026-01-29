<?php

namespace App\Controllers\Dashboard;

use App\Controllers\BaseController;
use App\Models\ArtikelModel;

class Artikel extends BaseController
{
    protected $artikelModel;

    public function __construct()
    {
        $this->artikelModel = new ArtikelModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Manajemen Artikel',
            'artikels' => $this->artikelModel->orderBy('created_at', 'DESC')->findAll(),
        ];
        return view('dashboard/artikel/index', $data);
    }

    public function create()
    {
        $data = ['title' => 'Tambah Artikel Baru'];
        return view('dashboard/artikel/create', $data);
    }

    public function store()
    {
        if (!$this->validate([
            'judul'   => 'required',
            'isi'     => 'required',
            'gambar'  => 'max_size[gambar,2048]|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]',
        ])) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal. Cek inputan anda.');
        }

        $gambar = $this->request->getFile('gambar');
        $namaGambar = null;
        if ($gambar && $gambar->isValid() && !$gambar->hasMoved()) {
            $namaGambar = $gambar->getRandomName();
            $gambar->move('uploads/artikel', $namaGambar);
        }

        $this->artikelModel->save([
            'id_gereja' => 1,
            'judul'     => $this->request->getPost('judul'),
            'slug'      => url_title($this->request->getPost('judul'), '-', true) . '-' . time(),
            'isi'       => $this->request->getPost('isi'),
            'penulis'   => $this->request->getPost('penulis') ?? 'Admin',
            'gambar'    => $namaGambar,
            'status'    => $this->request->getPost('status') ?? 'aktif',
        ]);

        return redirect()->to('/dashboard/artikel')->with('success', 'Artikel berhasil disimpan.');
    }

    public function edit($id)
    {
        $artikel = $this->artikelModel->find($id);
        if (!$artikel) {
            return redirect()->to('/dashboard/artikel')->with('error', 'Artikel tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Artikel',
            'artikel' => $artikel,
        ];
        return view('dashboard/artikel/edit', $data);
    }

    public function update($id)
    {
        if (!$this->validate([
            'judul'   => 'required',
            'isi'     => 'required',
            'gambar'  => 'max_size[gambar,2048]|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]',
        ])) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal.');
        }

        $artikel = $this->artikelModel->find($id);
        $gambar = $this->request->getFile('gambar');
        $namaGambar = $artikel['gambar'];
        
        if ($gambar && $gambar->isValid() && !$gambar->hasMoved()) {
            if ($namaGambar && file_exists('uploads/artikel/' . $namaGambar)) {
                unlink('uploads/artikel/' . $namaGambar);
            }
            $namaGambar = $gambar->getRandomName();
            $gambar->move('uploads/artikel', $namaGambar);
        }

        $this->artikelModel->update($id, [
            'judul'     => $this->request->getPost('judul'),
            'slug'      => url_title($this->request->getPost('judul'), '-', true) . '-' . time(),
            'isi'       => $this->request->getPost('isi'),
            'penulis'   => $this->request->getPost('penulis'),
            'gambar'    => $namaGambar,
            'status'    => $this->request->getPost('status'),
        ]);

        return redirect()->to('/dashboard/artikel')->with('success', 'Artikel berhasil diperbarui.');
    }

    public function delete($id)
    {
        $artikel = $this->artikelModel->find($id);
        if ($artikel['gambar'] && file_exists('uploads/artikel/' . $artikel['gambar'])) {
            unlink('uploads/artikel/' . $artikel['gambar']);
        }
        $this->artikelModel->delete($id);
        return redirect()->to('/dashboard/artikel')->with('success', 'Artikel berhasil dihapus.');
    }
}
