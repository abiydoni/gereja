<?php

namespace App\Models;

use CodeIgniter\Model;

class JadwalPetugasDetailModel extends Model
{
    protected $table            = 'jadwal_petugas_detail';
    protected $primaryKey       = 'id_detail';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'id_jadwal_utama', 'jenis_tugas', 'nama_petugas'
    ];
    protected $useTimestamps    = false;
}
