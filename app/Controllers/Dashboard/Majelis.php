<?php

namespace App\Controllers\Dashboard;

use App\Controllers\BaseController;
use App\Models\MajelisModel;

class Majelis extends BaseController
{
    protected $majelisModel;
    protected $logModel;

    public function __construct()
    {
        $this->majelisModel = new MajelisModel();
        $this->logModel = new \App\Models\ActivityLogModel();
    }

    public function index()
    {

        $data = [
            'title' => 'Manajemen Majelis',
            'majelis' => $this->majelisModel->findAll(),
        ];
        return view('dashboard/majelis/index', $data);
    }

    public function create()
    {
        $data = ['title' => 'Tambah Anggota Majelis'];
        return view('dashboard/majelis/create', $data);
    }

    public function store()
    {

        if (!$this->validate([
            'nama'    => 'required',
            'jabatan' => 'required',
            'foto'    => 'permit_empty|uploaded[foto]|max_size[foto,2048]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png]',
        ])) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal. Cek inputan anda.');
        }

        $fileFoto = $this->request->getFile('foto');
        $namaFoto = null;
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $namaFoto = $fileFoto->getRandomName();
            $fileFoto->move('uploads/majelis', $namaFoto);
        }

        $this->majelisModel->save([

            'nama'      => $this->request->getPost('nama'),
            'jabatan'   => $this->request->getPost('jabatan'),
            'bidang'    => $this->request->getPost('bidang'),
            'no_hp'     => $this->request->getPost('no_hp'),
            'periode'   => $this->request->getPost('periode'),
            'foto'      => $namaFoto,
            'status'    => $this->request->getPost('status') ?? 'aktif',
        ]);
        
        $newId = $this->majelisModel->insertID();
        $this->logModel->add('CREATE', 'majelis', $newId, null, $this->request->getPost());

        return redirect()->to('/dashboard/majelis')->with('success', 'Data berhasil disimpan.');
    }

    public function edit($id)
    {
        $majelis = $this->majelisModel->find($id);
        if (!$majelis) {
            return redirect()->to('/dashboard/majelis')->with('error', 'Data tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Anggota Majelis',
            'majelis' => $majelis,
        ];
        return view('dashboard/majelis/edit', $data);
    }

    public function update($id)
    {
        if (!$this->validate([
            'nama'    => 'required',
            'jabatan' => 'required',
            'foto'    => 'permit_empty|uploaded[foto]|max_size[foto,2048]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png]',
        ])) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal.');
        }

        $majelisLama = $this->majelisModel->find($id);
        $fileFoto = $this->request->getFile('foto');
        $namaFoto = $majelisLama['foto'];

        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            // Delete old photo if exists
            if ($namaFoto && file_exists('uploads/majelis/' . $namaFoto)) {
                unlink('uploads/majelis/' . $namaFoto);
            }
            $namaFoto = $fileFoto->getRandomName();
            $fileFoto->move('uploads/majelis', $namaFoto);
        }

        $updateData = [
            'nama'      => $this->request->getPost('nama'),
            'jabatan'   => $this->request->getPost('jabatan'),
            'bidang'    => $this->request->getPost('bidang'),
            'no_hp'     => $this->request->getPost('no_hp'),
            'periode'   => $this->request->getPost('periode'),
            'foto'      => $namaFoto,
            'status'    => $this->request->getPost('status'),
        ];
        
        $oldData = $this->majelisModel->asArray()->find($id);
        $this->majelisModel->update($id, $updateData);
        
        $this->logModel->add('UPDATE', 'majelis', $id, $oldData, $updateData);

        return redirect()->to('/dashboard/majelis')->with('success', 'Data berhasil diperbarui.');
    }

    public function delete($id)
    {
        $majelis = $this->majelisModel->find($id);
        if ($majelis['foto'] && file_exists('uploads/majelis/' . $majelis['foto'])) {
            unlink('uploads/majelis/' . $majelis['foto']);
        }
        $oldData = $this->majelisModel->asArray()->find($id);
        $this->majelisModel->delete($id);
        $this->logModel->add('DELETE', 'majelis', $id, $oldData, null);
        return redirect()->to('/dashboard/majelis')->with('success', 'Data berhasil dihapus.');
    }
}
