<?php

namespace App\Models;

use CodeIgniter\Model;

class MasterPersembahanModel extends Model
{
    protected $table            = 'master_persembahan';
    protected $primaryKey       = 'id_jenis';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['nama_persembahan', 'keterangan', 'status'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
