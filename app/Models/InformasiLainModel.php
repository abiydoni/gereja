<?php

namespace App\Models;

use CodeIgniter\Model;

class InformasiLainModel extends Model
{
    protected $table            = 'informasi_lain';
    protected $primaryKey       = 'id_informasi';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['judul', 'deskripsi', 'gambar', 'tanggal', 'status'];
    protected $useTimestamps    = true;
}
