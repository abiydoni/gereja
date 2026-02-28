<?php

namespace App\Controllers\Dashboard;

use App\Controllers\BaseController;
use App\Models\JemaatModel;

class Jemaat extends BaseController
{
    protected $jemaatModel;
    protected $logModel;

    public function __construct()
    {
        $this->jemaatModel = new JemaatModel();
        $this->logModel = new \App\Models\ActivityLogModel();
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

        // Log Activity
        $newId = $this->jemaatModel->insertID();
        $this->logModel->add('CREATE', 'jemaat', $newId, null, $this->request->getPost());

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

        $updateData = [
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
        ];
        
        // Old Data
        $oldData = $this->jemaatModel->asArray()->find($id);

        $this->jemaatModel->update($id, $updateData);

        // Log
        $this->logModel->add('UPDATE', 'jemaat', $id, $oldData, $updateData);

        return redirect()->to('/dashboard/jemaat')->with('success', 'Data jemaat berhasil diperbarui.');
    }

    public function delete($id)
    {
        $jemaat = $this->jemaatModel->find($id);
        if ($jemaat['foto'] && file_exists('uploads/jemaat/' . $jemaat['foto'])) {
            unlink('uploads/jemaat/' . $jemaat['foto']);
        }
        $oldData = $this->jemaatModel->asArray()->find($id);
        $this->jemaatModel->delete($id);
        
        $this->logModel->add('DELETE', 'jemaat', $id, $oldData, null);
        return redirect()->to('/dashboard/jemaat')->with('success', 'Data jemaat berhasil dihapus.');
    }

    public function export()
    {
        $data = $this->jemaatModel->orderBy('nama_lengkap', 'ASC')->findAll();
        
        $filename = 'data_jemaat_lengkap_' . date('Ymd_His') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Header CSV (Semua Kolom)
        fputcsv($output, [
            'NIJ', 'NIK', 'NIKK', 'Nama Lengkap', 'Nama Panggilan', 
            'Tempat Lahir', 'Tanggal Lahir', 'Jenis Kelamin', 'Golongan Darah',
            'Status Perkawinan', 'Hubungan Keluarga', 'Alamat', 'Wilayah Rayon', 
            'Telepon', 'Pekerjaan', 'Pendidikan Terakhir', 'Tanggal Baptis', 
            'Tanggal Sidhi', 'Tanggal Bergabung', 'Status Jemaat', 'Keterangan', 'Foto'
        ]);
        
        foreach ($data as $row) {
            fputcsv($output, [
                $row['nij'], $row['nik'], $row['nikk'], $row['nama_lengkap'], $row['nama_panggilan'],
                $row['tempat_lahir'], $row['tanggal_lahir'], $row['jenis_kelamin'], $row['golongan_darah'],
                $row['status_perkawinan'], $row['hubungan_keluarga'], $row['alamat'], $row['wilayah_rayon'],
                $row['telepon'], $row['pekerjaan'], $row['pendidikan_terakhir'], 
                $row['tanggal_baptis'], $row['tanggal_sidhi'], $row['tanggal_bergabung'],
                $row['status_jemaat'], $row['keterangan'], $row['foto']
            ]);
        }
        
        fclose($output);
        exit;
    }

    public function importData()
    {
        $file = $this->request->getFile('file_excel');
        
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $ext = $file->getClientExtension();
            if ($ext != 'csv') {
                return redirect()->back()->with('error', 'Format file harus .csv');
            }
            
            $handle = fopen($file->getTempName(), 'r');
            $header = fgetcsv($handle); // Skip header
            
            $count = 0;
            while (($row = fgetcsv($handle)) !== FALSE) {
                // Map CSV index to DB fields (All Columns)
                $data = [
                    'nij'                => $row[0] ?: null,
                    'nik'                => $row[1] ?: null,
                    'nikk'               => $row[2] ?: null,
                    'nama_lengkap'       => $row[3],
                    'nama_panggilan'     => $row[4] ?: null,
                    'tempat_lahir'       => $row[5] ?: null,
                    'tanggal_lahir'      => $row[6] ?: null,
                    'jenis_kelamin'      => $row[7] ?: 'L',
                    'golongan_darah'     => $row[8] ?: null,
                    'status_perkawinan'  => $row[9] ?: 'Belum Menikah',
                    'hubungan_keluarga'  => $row[10] ?: 'Lainnya',
                    'alamat'             => $row[11] ?: null,
                    'wilayah_rayon'      => $row[12] ?: null,
                    'telepon'            => $row[13] ?: null,
                    'pekerjaan'          => $row[14] ?: null,
                    'pendidikan_terakhir'=> $row[15] ?: null,
                    'tanggal_baptis'     => $row[16] ?: null,
                    'tanggal_sidhi'      => $row[17] ?: null,
                    'tanggal_bergabung'  => $row[18] ?: null,
                    'status_jemaat'      => $row[19] ?: 'Aktif',
                    'keterangan'         => $row[20] ?: null,
                    'foto'               => $row[21] ?: null,
                ];
                
                // Cari data lama berdasarkan NIK/NIJ untuk update jika ada
                $existing = null;
                if ($data['nik']) {
                    $existing = $this->jemaatModel->where('nik', $data['nik'])->first();
                } elseif ($data['nij']) {
                    $existing = $this->jemaatModel->where('nij', $data['nij'])->first();
                }
                
                if ($existing) {
                    $this->jemaatModel->update($existing['id_jemaat'], $data);
                } else {
                    $this->jemaatModel->insert($data);
                }
                $count++;
            }
            
            fclose($handle);
            return redirect()->to('/dashboard/jemaat')->with('success', "$count data jemaat berhasil diimport.");
        }
        
        return redirect()->back()->with('error', 'Gagal mengunggah file.');
    }

    public function template()
    {
        $filename = 'template_import_jemaat.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Header CSV
        fputcsv($output, [
            'NIJ', 'NIK', 'NIKK', 'Nama Lengkap', 'Nama Panggilan', 
            'Tempat Lahir', 'Tanggal Lahir', 'Jenis Kelamin', 'Golongan Darah',
            'Status Perkawinan', 'Hubungan Keluarga', 'Alamat', 'Wilayah Rayon', 
            'Telepon', 'Pekerjaan', 'Pendidikan Terakhir', 'Tanggal Baptis', 
            'Tanggal Sidhi', 'Tanggal Bergabung', 'Status Jemaat', 'Keterangan', 'Foto'
        ]);
        
        // Contoh Data (1 Baris)
        fputcsv($output, [
            'NIJ-001', '1234567890123456', '1234567890123456', 'Budi Santoso', 'Budi',
            'Jakarta', '1990-01-01', 'L', 'O',
            'Menikah', 'Kepala Keluarga', 'Jl. Contoh No. 123', 'Rayon 1',
            '08123456789', 'Karyawan Swasta', 'S1', '2000-05-10',
            '2010-06-15', '2015-01-01', 'Aktif', 'Jemaat Pindahan', 'budi.jpg'
        ]);
        
        fclose($output);
        exit;
    }
}
