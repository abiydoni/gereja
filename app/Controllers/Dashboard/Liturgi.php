<?php

namespace App\Controllers\Dashboard;

use App\Controllers\BaseController;
use App\Models\LiturgiModel;

class Liturgi extends BaseController
{
    protected $liturgiModel;

    public function __construct()
    {
        $this->liturgiModel = new LiturgiModel();
    }

    public function index()
    {

        $data = [
            'title' => 'Manajemen Liturgi',
            'liturgi' => $this->liturgiModel->orderBy('tanggal', 'DESC')->findAll(),
        ];
        return view('dashboard/liturgi/index', $data);
    }

    public function create()
    {
        $data = ['title' => 'Tambah Liturgi'];
        return view('dashboard/liturgi/create', $data);
    }

    public function store()
    {

        if (!$this->validate([
            'judul'       => 'required',
            'tanggal'     => 'required',
            // 'isi_liturgi' => 'required', // Removed requirement for now as wysiwyg might be complex to validate simply
        ])) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal.');
        }

        $this->liturgiModel->save([

            'judul'       => $this->request->getPost('judul'),
            'tanggal'     => $this->request->getPost('tanggal'),
            'isi_liturgi' => $this->request->getPost('isi_liturgi'),
            'status'      => $this->request->getPost('status') ?? 'aktif',
        ]);

        return redirect()->to('/dashboard/liturgi')->with('success', 'Liturgi berhasil disimpan.');
    }

    public function edit($id)
    {
        $liturgi = $this->liturgiModel->find($id);
        if (!$liturgi) {
            return redirect()->to('/dashboard/liturgi')->with('error', 'Data tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Liturgi',
            'liturgi' => $liturgi,
        ];
        return view('dashboard/liturgi/edit', $data);
    }

    public function update($id)
    {
        if (!$this->validate([
            'judul'       => 'required',
            'tanggal'     => 'required',
        ])) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal.');
        }

        $this->liturgiModel->update($id, [
            'judul'       => $this->request->getPost('judul'),
            'tanggal'     => $this->request->getPost('tanggal'),
            'isi_liturgi' => $this->request->getPost('isi_liturgi'),
            'status'      => $this->request->getPost('status'),
        ]);

        return redirect()->to('/dashboard/liturgi')->with('success', 'Liturgi berhasil diperbarui.');
    }

    public function delete($id)
    {
        $this->liturgiModel->delete($id);
        return redirect()->to('/dashboard/liturgi')->with('success', 'Data berhasil dihapus.');
    }
}
