<?php

namespace App\Models;

use CodeIgniter\Model;

class KidungModel extends Model
{
    protected $table            = 'kidung_jemaat';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nomor', 'judul', 'isi', 'nada_dasar', 'pengarang'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Search songs by title or number
     */
    public function search($keyword)
    {
        return $this->groupStart()
                        ->like('judul', $keyword)
                        ->orWhere('nomor', $keyword)
                    ->groupEnd();
    }
}
