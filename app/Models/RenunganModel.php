<?php

namespace App\Models;

use CodeIgniter\Model;

class RenunganModel extends Model
{
    protected $table            = 'renungan';
    protected $primaryKey       = 'id_renungan';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['judul', 'isi', 'tanggal', 'gambar', 'status', 'created_at', 'updated_at'];
    protected $useTimestamps    = true;
}
