<?php

namespace App\Controllers\Dashboard;

use App\Controllers\BaseController;
use App\Models\KonfigurasiModel;

class Konfigurasi extends BaseController
{
    protected $configModel;
    protected $logModel;

    public function __construct()
    {
        $this->configModel = new KonfigurasiModel();
        $this->logModel = new \App\Models\ActivityLogModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Konfigurasi Frontend',
            'konfigurasi' => $this->configModel->orderBy('urutan', 'ASC')->findAll(),
        ];
        return view('dashboard/konfigurasi/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Konfigurasi',
        ];
        return view('dashboard/konfigurasi/create', $data);
    }

    public function store()
    {
        if (!$this->validate([
            'label' => 'required',
            'slug'  => 'required|is_unique[konfigurasi_frontend.slug]',
            'group' => 'required',
        ])) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal. Cek inputan anda.');
        }

        $this->configModel->save([
            'label'  => $this->request->getPost('label'),
            'slug'   => $this->request->getPost('slug'),
            'group'  => $this->request->getPost('group'),
            'urutan' => $this->request->getPost('urutan'),
            'status' => $this->request->getPost('status') ?? 'aktif',
        ]);

        $newId = $this->configModel->insertID();
        $this->logModel->add('CREATE', 'konfigurasi', $newId, null, $this->request->getPost());

        return redirect()->to('/dashboard/konfigurasi')->with('success', 'Konfigurasi berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $config = $this->configModel->find($id);
        if (!$config) {
            return redirect()->to('/dashboard/konfigurasi')->with('error', 'Konfigurasi tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Konfigurasi',
            'config' => $config,
        ];
        return view('dashboard/konfigurasi/edit', $data);
    }

    public function update($id)
    {
        $config_old = $this->configModel->find($id);
        if (!$config_old) {
            return redirect()->to('/dashboard/konfigurasi')->with('error', 'Konfigurasi tidak ditemukan.');
        }

        // Check if slug changed to validate uniqueness
        $rules = [
            'label' => 'required',
            'group' => 'required',
        ];

        if ($this->request->getPost('slug') != $config_old['slug']) {
            $rules['slug'] = 'required|is_unique[konfigurasi_frontend.slug]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal. Slug mungkin sudah digunakan.');
        }

        $updateData = [
            'label'  => $this->request->getPost('label'),
            'slug'   => $this->request->getPost('slug'),
            'group'  => $this->request->getPost('group'),
            'urutan' => $this->request->getPost('urutan'),
            'status' => $this->request->getPost('status'),
        ];

        $this->configModel->update($id, $updateData);
        $this->logModel->add('UPDATE', 'konfigurasi', $id, $config_old, $updateData);

        return redirect()->to('/dashboard/konfigurasi')->with('success', 'Konfigurasi berhasil diperbarui.');
    }

    public function delete($id)
    {
        $config = $this->configModel->find($id);
        if (!$config) {
            return redirect()->to('/dashboard/konfigurasi')->with('error', 'Konfigurasi tidak ditemukan.');
        }

        $this->configModel->delete($id);
        $this->logModel->add('DELETE', 'konfigurasi', $id, $config, null);

        return redirect()->to('/dashboard/konfigurasi')->with('success', 'Konfigurasi berhasil dihapus.');
    }

    public function toggle($id)
    {
        $config = $this->configModel->find($id);
        if (!$config) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Konfigurasi tidak ditemukan.']);
        }

        $newStatus = ($config['status'] == 'aktif') ? 'tidak aktif' : 'aktif';
        
        $this->configModel->update($id, ['status' => $newStatus]);
        
        $this->logModel->add('UPDATE', 'konfigurasi', $id, ['status' => $config['status']], ['status' => $newStatus]);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Status ' . $config['label'] . ' berhasil diubah menjadi ' . $newStatus,
            'new_status' => $newStatus
        ]);
    }
}
