<?php

namespace App\Models;

use CodeIgniter\Model;

class MajelisModel extends Model
{
    protected $table            = 'majelis';
    protected $primaryKey       = 'id_majelis';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['nama', 'jabatan', 'bidang', 'no_hp', 'periode', 'foto', 'status'];
    protected $useTimestamps    = false;
}
