<?php

namespace App\Models;

use CodeIgniter\Model;

class DiskusiModel extends Model
{
    protected $table            = 'diskusi';
    protected $primaryKey       = 'id_diskusi';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['id_gereja', 'judul', 'isi', 'penulis', 'status'];
    protected $useTimestamps    = true;

    /**
     * Fetch topics with total replies (jawaban)
     */
    public function getTopicsWithCount($keyword = null)
    {
        $builder = $this->select('diskusi.*, (SELECT COUNT(*) FROM diskusi_jawaban WHERE diskusi_jawaban.id_diskusi = diskusi.id_diskusi) as total_jawaban')
                    ->where('status', 'aktif');
        
        if($keyword) {
            $builder->groupStart()
                    ->like('judul', $keyword)
                    ->orLike('isi', $keyword)
                    ->orLike('penulis', $keyword)
                    ->groupEnd();
        }

        return $builder->orderBy('created_at', 'DESC')->findAll();
    }
}
