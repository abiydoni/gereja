<?php

namespace App\Controllers\Dashboard;

use App\Controllers\BaseController;
use App\Models\InformasiKegiatanModel;

/**
 * Note: Check if InformasiKegiatanModel exists, if not create it.
 * Based on previous context, we might not have explicitly created this model file yet 
 * (we only seeded the table).
 */
use CodeIgniter\Model;

class Kegiatan extends BaseController
{
    protected $kegiatanModel;
    protected $logModel;

    public function __construct()
    {
        // Define Model inline or use query builder if Model file doesn't exist yet to save steps
        // But better to create the model file.
        $this->kegiatanModel = new InformasiKegiatanModel();
        $this->logModel = new \App\Models\ActivityLogModel();
    }

    public function index()
    {

        $data = [
            'title' => 'Manajemen Kegiatan',
            'kegiatan' => $this->kegiatanModel->orderBy('tanggal_mulai', 'DESC')->findAll(),
        ];
        return view('dashboard/kegiatan/index', $data);
    }

    public function create()
    {
        $data = ['title' => 'Tambah Kegiatan'];
        return view('dashboard/kegiatan/create', $data);
    }

    public function store()
    {

        if (!$this->validate([
            'nama_kegiatan'   => 'required',
            'deskripsi'       => 'required',
            'tanggal_mulai'   => 'required',
            'tanggal_selesai' => 'required',
            'lokasi'          => 'required',
        ])) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal.');
        }

        $this->kegiatanModel->save([

            'nama_kegiatan'   => $this->request->getPost('nama_kegiatan'),
            'deskripsi'       => $this->request->getPost('deskripsi'),
            'tanggal_mulai'   => $this->request->getPost('tanggal_mulai'),
            'tanggal_selesai' => $this->request->getPost('tanggal_selesai'),
            'lokasi'          => $this->request->getPost('lokasi'),
            'status'          => $this->request->getPost('status') ?? 'aktif',
        ]);
        
        $newId = $this->kegiatanModel->insertID();
        $this->logModel->add('CREATE', 'informasi_kegiatan', $newId, null, $this->request->getPost());

        return redirect()->to('/dashboard/kegiatan')->with('success', 'Kegiatan berhasil disimpan.');
    }

    public function edit($id)
    {
        $kegiatan = $this->kegiatanModel->find($id);
        if (!$kegiatan) {
            return redirect()->to('/dashboard/kegiatan')->with('error', 'Data tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Kegiatan',
            'kegiatan' => $kegiatan,
        ];
        return view('dashboard/kegiatan/edit', $data);
    }

    public function update($id)
    {
        if (!$this->validate([
            'nama_kegiatan'   => 'required',
            'deskripsi'       => 'required',
            'tanggal_mulai'   => 'required',
            'tanggal_selesai' => 'required',
            'lokasi'          => 'required',
        ])) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal.');
        }

        $updateData = [
            'nama_kegiatan'   => $this->request->getPost('nama_kegiatan'),
            'deskripsi'       => $this->request->getPost('deskripsi'),
            'tanggal_mulai'   => $this->request->getPost('tanggal_mulai'),
            'tanggal_selesai' => $this->request->getPost('tanggal_selesai'),
            'lokasi'          => $this->request->getPost('lokasi'),
            'status'          => $this->request->getPost('status'),
        ];
        
        $oldData = $this->kegiatanModel->asArray()->find($id);
        $this->kegiatanModel->update($id, $updateData);
        
        $this->logModel->add('UPDATE', 'informasi_kegiatan', $id, $oldData, $updateData);

        return redirect()->to('/dashboard/kegiatan')->with('success', 'Kegiatan berhasil diperbarui.');
    }

    public function delete($id)
    {
        $oldData = $this->kegiatanModel->asArray()->find($id);
        $this->kegiatanModel->delete($id);
        $this->logModel->add('DELETE', 'informasi_kegiatan', $id, $oldData, null);
        return redirect()->to('/dashboard/kegiatan')->with('success', 'Data berhasil dihapus.');
    }
}
