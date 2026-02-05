<?php

namespace App\Controllers\Dashboard;

use App\Controllers\BaseController;
use App\Models\JemaatModel;

class Jemaat extends BaseController
{
    protected $jemaatModel;

    public function __construct()
    {
        $this->jemaatModel = new JemaatModel();
    }

    public function index()
    {
        $data = [
            'title'   => 'Data Jemaat',
            'jemaat'  => $this->jemaatModel->orderBy('nama_lengkap', 'ASC')->findAll(),
        ];
        return view('dashboard/jemaat/index', $data);
    }

    public function create()
    {
        $data = ['title' => 'Tambah Jemaat Baru'];
        return view('dashboard/jemaat/create', $data);
    }

    public function store()
    {
        if (!$this->validate($this->jemaatModel->validationRules)) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal. Mohon periksa kembali inputan Anda.');
        }

        $foto = $this->request->getFile('foto');
        $namaFoto = null;
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $namaFoto = $foto->getRandomName();
            $foto->move('uploads/jemaat', $namaFoto);
        }

        $this->jemaatModel->save([
            'nij'                => $this->request->getPost('nij'),
            'nik'                => $this->request->getPost('nik'),
            'nikk'               => $this->request->getPost('nikk'),
            'nama_lengkap'       => $this->request->getPost('nama_lengkap'),
            'nama_panggilan'     => $this->request->getPost('nama_panggilan'),
            'tempat_lahir'       => $this->request->getPost('tempat_lahir'),
            'tanggal_lahir'      => $this->request->getPost('tanggal_lahir'),
            'jenis_kelamin'      => $this->request->getPost('jenis_kelamin'),
            'golongan_darah'     => $this->request->getPost('golongan_darah'),
            'status_perkawinan'  => $this->request->getPost('status_perkawinan'),
            'hubungan_keluarga'  => $this->request->getPost('hubungan_keluarga'),
            'alamat'             => $this->request->getPost('alamat'),
            'wilayah_rayon'      => $this->request->getPost('wilayah_rayon'),
            'telepon'            => $this->request->getPost('telepon'),
            'pekerjaan'          => $this->request->getPost('pekerjaan'),
            'pendidikan_terakhir'=> $this->request->getPost('pendidikan_terakhir'),
            'tanggal_baptis'     => $this->request->getPost('tanggal_baptis') ?: null,
            'tanggal_sidhi'      => $this->request->getPost('tanggal_sidhi') ?: null,
            'tanggal_bergabung'  => $this->request->getPost('tanggal_bergabung') ?: null,
            'foto'               => $namaFoto,
            'status_jemaat'      => $this->request->getPost('status_jemaat'),
            'keterangan'         => $this->request->getPost('keterangan'),
        ]);

        return redirect()->to('/dashboard/jemaat')->with('success', 'Data jemaat berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $jemaat = $this->jemaatModel->find($id);
        if (!$jemaat) {
            return redirect()->to('/dashboard/jemaat')->with('error', 'Data jemaat tidak ditemukan.');
        }

        $data = [
            'title'  => 'Edit Data Jemaat',
            'jemaat' => $jemaat,
        ];
        return view('dashboard/jemaat/edit', $data);
    }

    public function update($id)
    {
        $rules = $this->jemaatModel->validationRules;
        // Inject ID for unique checks
        $rules['nij'] = str_replace('{id_jemaat}', $id, $rules['nij']);
        $rules['nik'] = str_replace('{id_jemaat}', $id, $rules['nik']);

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal.');
        }

        $jemaat = $this->jemaatModel->find($id);
        $foto = $this->request->getFile('foto');
        $namaFoto = $jemaat['foto'];
        
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            if ($namaFoto && file_exists('uploads/jemaat/' . $namaFoto)) {
                unlink('uploads/jemaat/' . $namaFoto);
            }
            $namaFoto = $foto->getRandomName();
            $foto->move('uploads/jemaat', $namaFoto);
        }

        $this->jemaatModel->update($id, [
            'nij'                => $this->request->getPost('nij'),
            'nik'                => $this->request->getPost('nik'),
            'nikk'               => $this->request->getPost('nikk'),
            'nama_lengkap'       => $this->request->getPost('nama_lengkap'),
            'nama_panggilan'     => $this->request->getPost('nama_panggilan'),
            'tempat_lahir'       => $this->request->getPost('tempat_lahir'),
            'tanggal_lahir'      => $this->request->getPost('tanggal_lahir'),
            'jenis_kelamin'      => $this->request->getPost('jenis_kelamin'),
            'golongan_darah'     => $this->request->getPost('golongan_darah'),
            'status_perkawinan'  => $this->request->getPost('status_perkawinan'),
            'hubungan_keluarga'  => $this->request->getPost('hubungan_keluarga'),
            'alamat'             => $this->request->getPost('alamat'),
            'wilayah_rayon'      => $this->request->getPost('wilayah_rayon'),
            'telepon'            => $this->request->getPost('telepon'),
            'pekerjaan'          => $this->request->getPost('pekerjaan'),
            'pendidikan_terakhir'=> $this->request->getPost('pendidikan_terakhir'),
            'tanggal_baptis'     => $this->request->getPost('tanggal_baptis') ?: null,
            'tanggal_sidhi'      => $this->request->getPost('tanggal_sidhi') ?: null,
            'tanggal_bergabung'  => $this->request->getPost('tanggal_bergabung') ?: null,
            'foto'               => $namaFoto,
            'status_jemaat'      => $this->request->getPost('status_jemaat'),
            'keterangan'         => $this->request->getPost('keterangan'),
        ]);

        return redirect()->to('/dashboard/jemaat')->with('success', 'Data jemaat berhasil diperbarui.');
    }

    public function delete($id)
    {
        $jemaat = $this->jemaatModel->find($id);
        if ($jemaat['foto'] && file_exists('uploads/jemaat/' . $jemaat['foto'])) {
            unlink('uploads/jemaat/' . $jemaat['foto']);
        }
        $this->jemaatModel->delete($id);
        return redirect()->to('/dashboard/jemaat')->with('success', 'Data jemaat berhasil dihapus.');
    }
}
