<?php

namespace App\Models;

use CodeIgniter\Model;

class InformasiKegiatanModel extends Model
{
    protected $table            = 'informasi_kegiatan';
    protected $primaryKey       = 'id_kegiatan';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['nama_kegiatan', 'deskripsi', 'tanggal_mulai', 'tanggal_selesai', 'lokasi', 'status', 'created_at'];
    protected $useTimestamps    = true; 
    protected $createdField     = 'created_at';
    protected $updatedField     = ''; // Disable updated_at
}
