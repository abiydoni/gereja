<?php

namespace App\Models;

use CodeIgniter\Model;

class DiskusiJawabanModel extends Model
{
    protected $table            = 'diskusi_jawaban';
    protected $primaryKey       = 'id_jawaban';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['id_diskusi', 'isi', 'penulis'];
    protected $useTimestamps    = true;
}
