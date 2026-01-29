<?php

namespace App\Models;

use CodeIgniter\Model;

class ArtikelModel extends Model
{
    protected $table            = 'artikels';
    protected $primaryKey       = 'id_artikel';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['id_gereja', 'judul', 'slug', 'isi', 'gambar', 'penulis', 'status'];
    protected $useTimestamps    = true;
}
