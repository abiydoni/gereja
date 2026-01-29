<?php

namespace App\Models;

use CodeIgniter\Model;

class InformasiKeuanganModel extends Model
{
    protected $table            = 'informasi_keuangan';
    protected $primaryKey       = 'id_keuangan';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['id_gereja', 'tanggal', 'kategori', 'keterangan', 'jumlah', 'status'];
    protected $useTimestamps    = true; 
}
