<?php

namespace App\Models;

use CodeIgniter\Model;

class GaleriItemsModel extends Model
{
    protected $table            = 'galeri_items';
    protected $primaryKey       = 'id_item';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['id_galeri', 'judul', 'file_name', 'file_path', 'sort_order'];
    protected $useTimestamps    = true;
}
