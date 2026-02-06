<?php

namespace App\Controllers\Dashboard;

use App\Controllers\BaseController;
use App\Models\MasterPersembahanModel;

class MasterPersembahan extends BaseController
{
    protected $masterModel;
    protected $logModel;

    public function __construct()
    {
        $this->masterModel = new MasterPersembahanModel();
        $this->logModel = new \App\Models\ActivityLogModel();
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
        
        $newId = $this->masterModel->insertID();
        $this->logModel->add('CREATE', 'master_persembahan', $newId, null, $this->request->getPost());

        return redirect()->to('/dashboard/master_persembahan')->with('success', 'Jenis persembahan berhasil ditambahkan.');
    }

    public function update($id)
    {
        if (!$this->validate([
            'nama_persembahan' => 'required|min_length[3]',
        ])) {
            return redirect()->back()->withInput()->with('error', 'Nama persembahan wajib diisi.');
        }

        $updateData = [
            'nama_persembahan' => $this->request->getPost('nama_persembahan'),
            'keterangan'       => $this->request->getPost('keterangan'),
            'status'           => $this->request->getPost('status'),
        ];
        
        $oldData = $this->masterModel->asArray()->find($id);
        $this->masterModel->update($id, $updateData);
        
        $this->logModel->add('UPDATE', 'master_persembahan', $id, $oldData, $updateData);

        return redirect()->to('/dashboard/master_persembahan')->with('success', 'Jenis persembahan berhasil diperbarui.');
    }

    public function delete($id)
    {
        $oldData = $this->masterModel->asArray()->find($id);
        $this->masterModel->delete($id);
        $this->logModel->add('DELETE', 'master_persembahan', $id, $oldData, null);
        return redirect()->to('/dashboard/master_persembahan')->with('success', 'Jenis persembahan berhasil dihapus.');
    }
}
