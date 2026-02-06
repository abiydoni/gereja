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
