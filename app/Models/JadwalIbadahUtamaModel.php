<?php

namespace App\Models;

use CodeIgniter\Model;

class JadwalIbadahUtamaModel extends Model
{
    protected $table            = 'jadwal_ibadah_utama';
    protected $primaryKey       = 'id_jadwal_utama';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'id_gereja', 'tanggal', 'jam', 'nama_ibadah', 'tema', 'status'
    ];
    protected $useTimestamps    = true;
}
