<?php

namespace App\Models;

use CodeIgniter\Model;

class JadwalPetugasModel extends Model
{
    protected $table            = 'jadwal_petugas';
    protected $primaryKey       = 'id_jadwal_petugas';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'id_gereja', 'tanggal', 'jam', 'nama_ibadah', 'pengkotbah', 
        'liturgos', 'pembaca_alkitab', 'persembahan', 'musik', 
        'singer', 'sambut_jemaat', 'multimedia', 'status'
    ];
    protected $useTimestamps    = true;
}
