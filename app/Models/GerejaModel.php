<?php

namespace App\Models;

use CodeIgniter\Model;

class GerejaModel extends Model
{
    protected $table            = 'gereja';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['nama_gereja', 'alamat', 'deskripsi', 'logo', 'telp', 'email', 'ig', 'fb', 'tt', 'yt', 'created_at', 'updated_at'];
    protected $useTimestamps    = true;
}
