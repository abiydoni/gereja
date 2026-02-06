<?php

namespace App\Models;

use CodeIgniter\Model;

class RenunganModel extends Model
{
    protected $table            = 'renungan';
    protected $primaryKey       = 'id_renungan';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['judul', 'isi', 'tanggal', 'gambar', 'status', 'created_at', 'updated_at'];
    protected $useTimestamps    = true;

    /**
     * Get single renungan for today, or fallback to latest active
     */
    public function getDailyRenungan()
    {
        // Try today's renungan first
        $today = $this->where('status', 'aktif')
                      ->where('tanggal', date('Y-m-d'))
                      ->first();
        
        if ($today) {
            return $today;
        }

        // Fallback to latest active
        return $this->where('status', 'aktif')
                    ->orderBy('tanggal', 'DESC')
                    ->orderBy('created_at', 'DESC')
                    ->first();
    }
}
