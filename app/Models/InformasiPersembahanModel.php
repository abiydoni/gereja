<?php
 
 namespace App\Models;
 
 use CodeIgniter\Model;
 
 class InformasiPersembahanModel extends Model
 {
     protected $table            = 'informasi_persembahan';
     protected $primaryKey       = 'id_persembahan';
     protected $useAutoIncrement = true;
     protected $returnType       = 'array';
     protected $allowedFields    = ['tanggal', 'waktu_ibadah', 'judul', 'deskripsi', 'jumlah', 'jumlah_pria', 'jumlah_wanita', 'status', 'is_posted', 'posted_at', 'created_at', 'update_at'];
     protected $useTimestamps    = false; 
 }
