<?php

namespace App\Controllers\Dashboard;

use App\Controllers\BaseController;
use App\Models\LiturgiModel;

class Liturgi extends BaseController
{
    protected $liturgiModel;
    protected $logModel;

    public function __construct()
    {
        $this->liturgiModel = new LiturgiModel();
        $this->logModel = new \App\Models\ActivityLogModel();
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
            'kategori'    => $this->request->getPost('kategori'),
            'tanggal'     => $this->request->getPost('tanggal'),
            'isi_liturgi' => $this->request->getPost('isi_liturgi'),
            'status'      => $this->request->getPost('status') ?? 'aktif',
        ]);
        
        $newId = $this->liturgiModel->insertID();
        $this->logModel->add('CREATE', 'liturgi', $newId, null, $this->request->getPost());

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

        $updateData = [
            'judul'       => $this->request->getPost('judul'),
            'kategori'    => $this->request->getPost('kategori'),
            'tanggal'     => $this->request->getPost('tanggal'),
            'isi_liturgi' => $this->request->getPost('isi_liturgi'),
            'status'      => $this->request->getPost('status'),
        ];
        
        $oldData = $this->liturgiModel->asArray()->find($id);
        $this->liturgiModel->update($id, $updateData);
        
        $this->logModel->add('UPDATE', 'liturgi', $id, $oldData, $updateData);

        return redirect()->to('/dashboard/liturgi')->with('success', 'Liturgi berhasil diperbarui.');
    }

    public function delete($id)
    {
        $oldData = $this->liturgiModel->asArray()->find($id);
        $this->liturgiModel->delete($id);
        $this->logModel->add('DELETE', 'liturgi', $id, $oldData, null);
        return redirect()->to('/dashboard/liturgi')->with('success', 'Data berhasil dihapus.');
    }
}
