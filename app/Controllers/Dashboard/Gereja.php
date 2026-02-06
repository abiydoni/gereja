<?php

namespace App\Controllers\Dashboard;

use App\Controllers\BaseController;
use App\Models\GerejaModel;

class Gereja extends BaseController
{
    protected $gerejaModel;
    protected $logModel;

    public function __construct()
    {
        $this->gerejaModel = new GerejaModel();
        $this->logModel = new \App\Models\ActivityLogModel();
    }

    public function index()
    {
        $gereja = $this->gerejaModel->first();

        $data = [
            'title' => 'Profil Gereja',
            'gereja' => $gereja,
        ];
        return view('dashboard/gereja/index', $data);
    }

    public function update()
    {
        $gereja = $this->gerejaModel->first();
        $id_gereja = $gereja['id'];
        
        if (!$this->validate([
            'nama_gereja' => 'required',
            'alamat'      => 'required',
            'deskripsi'   => 'required',
            'logo'        => 'permit_empty|uploaded[logo]|max_size[logo,2048]|is_image[logo]|mime_in[logo,image/jpg,image/jpeg,image/png]',
        ])) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal.');
        }

        $gerejaLama = $this->gerejaModel->find($id_gereja);
        $fileLogo = $this->request->getFile('logo');
        $namaLogo = $gerejaLama['logo'] ?? null;

        if ($fileLogo && $fileLogo->isValid() && !$fileLogo->hasMoved()) {
            if ($namaLogo && file_exists('uploads/' . $namaLogo)) {
                unlink('uploads/' . $namaLogo);
            }
            $namaLogo = $fileLogo->getRandomName();
            $fileLogo->move('uploads', $namaLogo);
        }

        $updateData = [
            'nama_gereja' => $this->request->getPost('nama_gereja'),
            'alamat'      => $this->request->getPost('alamat'),
            'deskripsi'   => $this->request->getPost('deskripsi'),
            'telp'        => $this->request->getPost('telp'),
            'email'       => $this->request->getPost('email'),
            'ig'          => $this->request->getPost('ig'),
            'fb'          => $this->request->getPost('fb'),
            'tt'          => $this->request->getPost('tt'),
            'yt'          => $this->request->getPost('yt'),
            'logo'        => $namaLogo,
        ];
        
        $this->gerejaModel->update($id_gereja, $updateData);
        
        $this->logModel->add('UPDATE', 'gereja', $id_gereja, $gerejaLama, $updateData);

        return redirect()->to('/dashboard/gereja')->with('success', 'Profil Gereja berhasil diperbarui.');
    }
}
