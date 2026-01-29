<?php

namespace App\Models;

use CodeIgniter\Model;

class JadwalIbadahModel extends Model
{
    protected $table            = 'jadwal_ibadah';
    protected $primaryKey       = 'id_jadwal';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['nama_ibadah', 'hari', 'jam', 'lokasi', 'keterangan', 'status'];
    protected $useTimestamps    = false;
}
