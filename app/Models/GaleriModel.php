<?php

namespace App\Models;

use CodeIgniter\Model;

class GaleriModel extends Model
{
    protected $table            = 'galeri';
    protected $primaryKey       = 'id_galeri';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['id_gereja', 'judul', 'kategori', 'link_media', 'keterangan', 'status'];
    protected $useTimestamps    = true;
}
