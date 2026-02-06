<?php

namespace App\Controllers\Dashboard;

use App\Controllers\BaseController;

class System extends BaseController
{
    protected $logModel;

    public function __construct()
    {
        $this->logModel = new \App\Models\ActivityLogModel();
    }

    public function toggleStatus($module, $id)
    {
        $whitelist = [
            'artikel'           => ['model' => 'ArtikelModel', 'field' => 'status', 'active' => 'aktif', 'inactive' => 'nonaktif'],
            'renungan'          => ['model' => 'RenunganModel', 'field' => 'status', 'active' => 'aktif', 'inactive' => 'nonaktif'],
            'informasi'         => ['model' => 'InformasiLainModel', 'field' => 'status', 'active' => 'aktif', 'inactive' => 'nonaktif'],
            'informasi_kegiatan'=> ['model' => 'InformasiKegiatanModel', 'field' => 'status', 'active' => 'aktif', 'inactive' => 'nonaktif'],
            'informasi_keuangan'=> ['model' => 'InformasiKeuanganModel', 'field' => 'status', 'active' => 'aktif', 'inactive' => 'nonaktif'],
            'informasi_persembahan'=> ['model' => 'InformasiPersembahanModel', 'field' => 'status', 'active' => 'aktif', 'inactive' => 'nonaktif'],
            'galeri'            => ['model' => 'GaleriModel', 'field' => 'status', 'active' => 'aktif', 'inactive' => 'nonaktif'],
            'jadwal_rutin'      => ['model' => 'JadwalIbadahModel', 'field' => 'status', 'active' => 'aktif', 'inactive' => 'nonaktif'],
            'jadwal_pelayanan'  => ['model' => 'JadwalIbadahUtamaModel', 'field' => 'status', 'active' => 'aktif', 'inactive' => 'nonaktif'],
            'kegiatan'          => ['model' => 'InformasiKegiatanModel', 'field' => 'status', 'active' => 'aktif', 'inactive' => 'nonaktif'],
            'majelis'           => ['model' => 'MajelisModel', 'field' => 'status', 'active' => 'aktif', 'inactive' => 'nonaktif'],
            'liturgi'           => ['model' => 'LiturgiModel', 'field' => 'status', 'active' => 'aktif', 'inactive' => 'nonaktif'],
            'diskusi'           => ['model' => 'DiskusiModel', 'field' => 'status', 'active' => 'aktif', 'inactive' => 'nonaktif'],
            'users'             => ['model' => 'UsersModel', 'field' => 'status', 'active' => 'aktif', 'inactive' => 'non-aktif'],
            'jemaat'            => ['model' => 'JemaatModel', 'field' => 'status_jemaat', 'active' => 'Aktif', 'inactive' => 'Non-Aktif'],
        ];

        if (!isset($whitelist[$module])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Module not allowed']);
        }

        $config = $whitelist[$module];
        $modelName = "\\App\\Models\\" . $config['model'];
        $model = new $modelName();
        
        $item = $model->find($id);
        if (!$item) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Item not found']);
        }

        $fieldName = $config['field'];
        $currentValue = $item[$fieldName];
        $newValue = ($currentValue == $config['active']) ? $config['inactive'] : $config['active'];

        if ($model->update($id, [$fieldName => $newValue])) {
            
            // Log Activity
            $this->logModel->add('UPDATE', $module, $id, [$fieldName => $currentValue], [$fieldName => $newValue]);

            return $this->response->setJSON([
                'status' => 'success', 
                'new_value' => $newValue,
                'active_label' => $config['active'],
                'inactive_label' => $config['inactive']
            ]);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to update status']);
    }
}
