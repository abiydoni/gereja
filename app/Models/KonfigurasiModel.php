<?php

namespace App\Models;

use CodeIgniter\Model;

class KonfigurasiModel extends Model
{
    protected $table            = 'konfigurasi_frontend';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['group', 'slug', 'label', 'status', 'urutan'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get active configurations grouped by slug for easy access
     */
    public function getActiveConfig()
    {
        $configs = $this->where('status', 'aktif')->findAll();
        $result = [];
        foreach ($configs as $c) {
            $result[$c['slug']] = true;
        }
        return $result;
    }
}
