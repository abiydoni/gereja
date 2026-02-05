<?php

namespace App\Models;

use CodeIgniter\Model;

class LiturgiModel extends Model
{
    protected $table            = 'liturgi';
    protected $primaryKey       = 'id_liturgi';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['judul', 'kategori', 'tanggal', 'isi_liturgi', 'status', 'created_at'];
    protected $useTimestamps    = false;
}
