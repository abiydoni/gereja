<?php

namespace App\Controllers\Dashboard;

use App\Controllers\BaseController;
use App\Models\MasterPersembahanModel;

class MasterPersembahan extends BaseController
{
    protected $masterModel;

    public function __construct()
    {
        $this->masterModel = new MasterPersembahanModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Master Jenis Persembahan',
            'jenis' => $this->masterModel->orderBy('nama_persembahan', 'ASC')->findAll(),
        ];
        return view('dashboard/master_persembahan/index', $data);
    }

    public function store()
    {
        if (!$this->validate([
            'nama_persembahan' => 'required|min_length[3]',
        ])) {
            return redirect()->back()->withInput()->with('error', 'Nama persembahan wajib diisi.');
        }

        $this->masterModel->save([
            'nama_persembahan' => $this->request->getPost('nama_persembahan'),
            'keterangan'       => $this->request->getPost('keterangan'),
            'status'           => $this->request->getPost('status') ?? 'aktif',
        ]);

        return redirect()->to('/dashboard/master_persembahan')->with('success', 'Jenis persembahan berhasil ditambahkan.');
    }

    public function update($id)
    {
        if (!$this->validate([
            'nama_persembahan' => 'required|min_length[3]',
        ])) {
            return redirect()->back()->withInput()->with('error', 'Nama persembahan wajib diisi.');
        }

        $this->masterModel->update($id, [
            'nama_persembahan' => $this->request->getPost('nama_persembahan'),
            'keterangan'       => $this->request->getPost('keterangan'),
            'status'           => $this->request->getPost('status'),
        ]);

        return redirect()->to('/dashboard/master_persembahan')->with('success', 'Jenis persembahan berhasil diperbarui.');
    }

    public function delete($id)
    {
        $this->masterModel->delete($id);
        return redirect()->to('/dashboard/master_persembahan')->with('success', 'Jenis persembahan berhasil dihapus.');
    }
}
