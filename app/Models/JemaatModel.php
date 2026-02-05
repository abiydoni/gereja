<?php

namespace App\Models;

use CodeIgniter\Model;

class JemaatModel extends Model
{
    protected $table            = 'tb_jemaat';
    protected $primaryKey       = 'id_jemaat';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nij', 'nik', 'nikk', 'nama_lengkap', 'nama_panggilan', 
        'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 
        'golongan_darah', 'status_perkawinan', 'hubungan_keluarga', 
        'alamat', 'wilayah_rayon', 'telepon', 'pekerjaan', 
        'pendidikan_terakhir', 'tanggal_baptis', 'tanggal_sidhi', 
        'tanggal_bergabung', 'foto', 'status_jemaat', 'keterangan'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'nama_lengkap' => 'required|min_length[3]',
        'nij'          => 'permit_empty|is_unique[tb_jemaat.nij,id_jemaat,{id_jemaat}]',
        'nik'          => 'permit_empty|exact_length[16]|is_unique[tb_jemaat.nik,id_jemaat,{id_jemaat}]',
        'nikk'         => 'permit_empty|exact_length[16]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}
