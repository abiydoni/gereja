<?php
 
 namespace App\Models;
 
 use CodeIgniter\Model;
 
 class KeuanganModel extends Model
 {
     protected $table            = 'keuangan';
     protected $primaryKey       = 'id';
     protected $useAutoIncrement = true;
     protected $returnType       = 'array';
     protected $allowedFields    = ['tanggal', 'keterangan', 'reff', 'debet', 'kredit', 'created_at'];
     protected $useTimestamps    = false; 
 }
