<?php
require_once 'config.php';

// Fungsi untuk sanitasi input
if (!function_exists('sanitize')) {
function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}}

// Fungsi untuk format tanggal Indonesia
if (!function_exists('formatTanggalIndonesia')) {
    function formatTanggalIndonesia($tanggal) {
        // Validasi input
        if (empty($tanggal) || !is_string($tanggal)) {
            return '';
        }
        
        $bulan = array(
            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        );
        
        $split = explode('-', $tanggal);
        
        // Validasi format tanggal (harus ada 3 bagian: tahun-bulan-tanggal)
        if (count($split) !== 3) {
            return $tanggal; // Return as-is jika format tidak sesuai
        }
        
        $tahun = $split[0];
        $bulan_num = (int)$split[1];
        $tanggal_num = $split[2];
        
        // Validasi bulan dan tanggal
        if ($bulan_num < 1 || $bulan_num > 12 || $tanggal_num < 1 || $tanggal_num > 31) {
            return $tanggal; // Return as-is jika nilai tidak valid
        }
        
        $tgl_indo = $tanggal_num . ' ' . $bulan[$bulan_num] . ' ' . $tahun;
        
        return $tgl_indo;
    }
}

// Fungsi untuk format rupiah
if (!function_exists('formatRupiah')) {
function formatRupiah($angka) {
    return "Rp " . number_format($angka, 0, ',', '.');
}}

// Fungsi untuk generate ID unik
if (!function_exists('generateId')) {
function generateId($prefix, $table, $field) {
    return $prefix . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
}}

// Fungsi untuk upload file
if (!function_exists('uploadFile')) {
function uploadFile($file, $target_dir, $allowed_types = ['jpg', 'jpeg', 'png', 'gif']) {
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if(!in_array($file_extension, $allowed_types)) {
        return ['success' => false, 'message' => 'Tipe file tidak diizinkan'];
    }
    
    if($file['size'] > 5000000) { // 5MB max
        return ['success' => false, 'message' => 'Ukuran file terlalu besar (max 5MB)'];
    }
    
    $new_filename = uniqid() . '.' . $file_extension;
    $target_file = $target_dir . $new_filename;
    
    if(move_uploaded_file($file['tmp_name'], $target_file)) {
        return ['success' => true, 'filename' => $new_filename];
    } else {
        return ['success' => false, 'message' => 'Gagal upload file'];
    }
}}

// Fungsi untuk cek login admin
if (!function_exists('isAdminLoggedIn')) {
function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}}

// Fungsi untuk redirect
if (!function_exists('redirect')) {
function redirect($url) {
    header("Location: $url");
    exit();
}}

// Fungsi untuk flash message
if (!function_exists('setFlashMessage')) {
function setFlashMessage($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}}

if (!function_exists('getFlashMessage')) {
function getFlashMessage() {
    if(isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}}

// Fungsi untuk validasi email
if (!function_exists('isValidEmail')) {
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}}

// Fungsi untuk validasi nomor telepon
if (!function_exists('isValidPhone')) {
function isValidPhone($phone) {
    return preg_match('/^[0-9]{10,13}$/', $phone);
}}

// Fungsi untuk mendapatkan path logo gereja
if (!function_exists('getLogoPath')) {
function getLogoPath() {
    try {
        require_once __DIR__ . '/database.php';
        $db = new Database();
        // Ambil nilai logo dari pengaturan_sistem
        $db->query("SELECT nilai FROM pengaturan_sistem WHERE nama_pengaturan = 'logo_gereja' LIMIT 1");
        $row = $db->single();
        $filename = null;
        if ($row) {
            // Database::single() mengembalikan array assoc
            $filename = is_array($row) ? ($row['nilai'] ?? null) : ($row->nilai ?? null);
        }
        $basePath = '../assets/images/';
        $fallback = $basePath . 'logo.png';
        if ($filename && file_exists(__DIR__ . '/../assets/images/' . $filename)) {
            // cache buster agar perubahan langsung terlihat
            $ts = @filemtime(__DIR__ . '/../assets/images/' . $filename) ?: time();
            return $basePath . $filename . '?v=' . $ts;
        }
        return $fallback;
    } catch (Exception $e) {
        return '../assets/images/logo.png';
    }
}}

// Fungsi untuk mendapatkan nama gereja
if (!function_exists('getNamaGereja')) {
function getNamaGereja() {
    return 'Gereja Kristen Jawa Randuares';
}}

// Fungsi untuk mendapatkan alamat gereja
if (!function_exists('getAlamatGereja')) {
function getAlamatGereja() {
    return 'Jl. Randuares No. 123, Yogyakarta';
}}

// Fungsi untuk mendapatkan kontak gereja
if (!function_exists('getKontakGereja')) {
function getKontakGereja() {
    return '+62 123 456 789';
}}
?>
