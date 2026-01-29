<?php

namespace App\Controllers\Dashboard;

use App\Controllers\BaseController;
use App\Models\JadwalIbadahModel;

class JadwalRutin extends BaseController
{
    protected $jadwalModel;

    public function __construct()
    {
        $this->jadwalModel = new JadwalIbadahModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Jadwal Ibadah Rutin',
            'jadwal' => $this->jadwalModel->findAll(),
        ];
        return view('dashboard/jadwal_rutin/index', $data);
    }

    public function create()
    {
        $data = ['title' => 'Tambah Jadwal Rutin'];
        return view('dashboard/jadwal_rutin/create', $data);
    }

    public function store()
    {
        if (!$this->validate([
            'nama_ibadah' => 'required',
            'hari'        => 'required',
            'jam'         => 'required',
            'lokasi'      => 'required',
        ])) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal. Cek inputan anda.');
        }

        $this->jadwalModel->save([
            'nama_ibadah' => $this->request->getPost('nama_ibadah'),
            'hari'        => $this->request->getPost('hari'),
            'jam'         => $this->request->getPost('jam'),
            'lokasi'      => $this->request->getPost('lokasi'),
            'keterangan'  => $this->request->getPost('keterangan'),
            'status'      => $this->request->getPost('status') ?? 'aktif',
        ]);

        return redirect()->to('/dashboard/jadwal_rutin')->with('success', 'Jadwal rutin berhasil disimpan.');
    }

    public function edit($id)
    {
        $jadwal = $this->jadwalModel->find($id);
        if (!$jadwal) {
            return redirect()->to('/dashboard/jadwal_rutin')->with('error', 'Data tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Jadwal Rutin',
            'jadwal' => $jadwal,
        ];
        return view('dashboard/jadwal_rutin/edit', $data);
    }

    public function update($id)
    {
        if (!$this->validate([
            'nama_ibadah' => 'required',
            'hari'        => 'required',
            'jam'         => 'required',
            'lokasi'      => 'required',
        ])) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal.');
        }

        $this->jadwalModel->update($id, [
            'nama_ibadah' => $this->request->getPost('nama_ibadah'),
            'hari'        => $this->request->getPost('hari'),
            'jam'         => $this->request->getPost('jam'),
            'lokasi'      => $this->request->getPost('lokasi'),
            'keterangan'  => $this->request->getPost('keterangan'),
            'status'      => $this->request->getPost('status'),
        ]);

        return redirect()->to('/dashboard/jadwal_rutin')->with('success', 'Jadwal rutin berhasil diperbarui.');
    }

    public function delete($id)
    {
        $this->jadwalModel->delete($id);
        return redirect()->to('/dashboard/jadwal_rutin')->with('success', 'Data berhasil dihapus.');
    }
}
