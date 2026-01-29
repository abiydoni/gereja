<?php

namespace App\Controllers\Dashboard;

use App\Controllers\BaseController;
use App\Models\JadwalIbadahUtamaModel;
use App\Models\JadwalPetugasDetailModel;

class JadwalPelayanan extends BaseController
{
    protected $utamaModel;
    protected $detailModel;

    public function __construct()
    {
        $this->utamaModel = new JadwalIbadahUtamaModel();
        $this->detailModel = new JadwalPetugasDetailModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Manajemen Jadwal Pelayanan',
            'jadwal' => $this->utamaModel->orderBy('tanggal', 'DESC')->findAll(),
        ];
        return view('dashboard/jadwal_pelayanan/index', $data);
    }

    public function create()
    {
        $data = ['title' => 'Tambah Jadwal Pelayanan'];
        return view('dashboard/jadwal_pelayanan/create', $data);
    }

    public function store()
    {
        if (!$this->validate([
            'nama_ibadah' => 'required',
            'tanggal'     => 'required',
            'jam'         => 'required',
        ])) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal. Mohon lengkapi data header.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // 1. Insert Header
        $headerData = [
            'id_gereja'   => 1, // Default or session based
            'tanggal'     => $this->request->getPost('tanggal'),
            'jam'         => $this->request->getPost('jam'),
            'nama_ibadah' => $this->request->getPost('nama_ibadah'),
            'tema'        => $this->request->getPost('tema'),
            'status'      => $this->request->getPost('status') ?? 'aktif',
        ];
        $this->utamaModel->insert($headerData);
        $idJadwal = $this->utamaModel->getInsertID();

        // 2. Insert Details (Petugas)
        $roles = $this->request->getPost('jenis_tugas'); // Array
        $names = $this->request->getPost('nama_petugas'); // Array

        if (!empty($roles) && !empty($names)) {
            $details = [];
            foreach ($roles as $index => $role) {
                if (!empty($role) && !empty($names[$index])) {
                    $details[] = [
                        'id_jadwal_utama' => $idJadwal,
                        'jenis_tugas'     => $role,
                        'nama_petugas'    => $names[$index]
                    ];
                }
            }
            if(!empty($details)) {
                $this->detailModel->insertBatch($details);
            }
        }

        $db->transComplete();

        if ($db->transStatus() === FALSE) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data.');
        }

        return redirect()->to('/dashboard/jadwal_pelayanan')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $jadwal = $this->utamaModel->find($id);
        if (!$jadwal) {
            return redirect()->to('/dashboard/jadwal_pelayanan')->with('error', 'Data tidak ditemukan.');
        }

        $petugas = $this->detailModel->where('id_jadwal_utama', $id)->findAll();

        $data = [
            'title'   => 'Edit Jadwal Pelayanan',
            'jadwal'  => $jadwal,
            'petugas' => $petugas
        ];
        return view('dashboard/jadwal_pelayanan/edit', $data);
    }

    public function update($id)
    {
        if (!$this->validate([
            'nama_ibadah' => 'required',
            'tanggal'     => 'required',
            'jam'         => 'required',
        ])) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // 1. Update Header
        $this->utamaModel->update($id, [
            'tanggal'     => $this->request->getPost('tanggal'),
            'jam'         => $this->request->getPost('jam'),
            'nama_ibadah' => $this->request->getPost('nama_ibadah'),
            'tema'        => $this->request->getPost('tema'),
            'status'      => $this->request->getPost('status'),
        ]);

        // 2. Sync Details (Simplest: Delete All, Re-insert)
        $this->detailModel->where('id_jadwal_utama', $id)->delete();

        $roles = $this->request->getPost('jenis_tugas'); 
        $names = $this->request->getPost('nama_petugas');

        if (!empty($roles) && !empty($names)) {
            $details = [];
            foreach ($roles as $index => $role) {
                if (!empty($role) && !empty($names[$index])) {
                    $details[] = [
                        'id_jadwal_utama' => $id,
                        'jenis_tugas'     => $role,
                        'nama_petugas'    => $names[$index]
                    ];
                }
            }
            if (!empty($details)) {
                $this->detailModel->insertBatch($details);
            }
        }

        $db->transComplete();

        return redirect()->to('/dashboard/jadwal_pelayanan')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function delete($id)
    {
        // Cascade delete handles detail rows automatically via DB constraint, 
        // but explicit deletion is safer if DB foreign keys aren't set up perfectly by user.
        // Migration has ON DELETE CASCADE, so just delete header is enough.
        $this->utamaModel->delete($id);
        return redirect()->to('/dashboard/jadwal_pelayanan')->with('success', 'Jadwal berhasil dihapus.');
    }
}
