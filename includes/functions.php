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
    $bulan = array(
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    );
    
    $split = explode('-', $tanggal);
    $tgl_indo = $split[2] . ' ' . $bulan[(int)$split[1]] . ' ' . $split[0];
    
    return $tgl_indo;
}}

// Fungsi untuk format rupiah
if (!function_exists('formatRupiah')) {
function formatRupiah($angka) {
    return "Rp " . number_format($angka, 0, ',', '.');
}}

// Fungsi untuk generate ID unik
if (!function_exists('generateId')) {
function generateId($prefix, $table, $field) {
    global $db;
    $db = new Database();
    
    $db->query("SELECT MAX($field) as max_id FROM $table");
    $result = $db->single();
    
    if($result->max_id) {
        $last_id = intval(substr($result->max_id, strlen($prefix)));
        $new_id = $prefix . str_pad($last_id + 1, 4, '0', STR_PAD_LEFT);
    } else {
        $new_id = $prefix . '0001';
    }
    
    return $new_id;
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
        $db = new Database();
        // Prioritaskan pengaturan_sistem jika ada, fallback ke pengaturan_umum
        $db->query("SELECT nilai FROM pengaturan_sistem WHERE nama_pengaturan = 'logo_gereja'");
        $db->execute();
        $row = $db->single();
        if ($row && isset($row->nilai) && $row->nilai) {
            $path = '../assets/images/' . $row->nilai;
            return $path;
        }

        $db->query("SELECT logo FROM pengaturan_umum WHERE id = 1");
        $result = $db->single();
        if ($result && $result->logo) {
            return '../assets/images/' . $result->logo;
        }
        return '../assets/images/logo.png';
    } catch (Exception $e) {
        return '../assets/images/logo.png';
    }
}}

// Fungsi untuk mendapatkan nama gereja
if (!function_exists('getNamaGereja')) {
function getNamaGereja() {
    try {
        $db = new Database();
        $db->query("SELECT nilai FROM pengaturan_sistem WHERE nama_pengaturan = 'nama_gereja'");
        $db->execute();
        $row = $db->single();
        if ($row && isset($row->nilai) && $row->nilai) {
            return $row->nilai;
        }

        $db->query("SELECT nama_gereja FROM pengaturan_umum WHERE id = 1");
        $result = $db->single();
        if ($result && $result->nama_gereja) {
            return $result->nama_gereja;
        } else {
            return 'Gereja Kristen Jawa Randuares';
        }
    } catch (Exception $e) {
        return 'Gereja Kristen Jawa Randuares';
    }
}}

// Fungsi untuk mendapatkan alamat gereja
if (!function_exists('getAlamatGereja')) {
function getAlamatGereja() {
    try {
        $db = new Database();
        $db->query("SELECT alamat FROM pengaturan_umum WHERE id = 1");
        $result = $db->single();
        if ($result && $result->alamat) {
            return $result->alamat;
        } else {
            return 'Jl. Randuares No. 123, Yogyakarta';
        }
    } catch (Exception $e) {
        return 'Jl. Randuares No. 123, Yogyakarta';
    }
}}

// Fungsi untuk mendapatkan kontak gereja
if (!function_exists('getKontakGereja')) {
function getKontakGereja() {
    try {
        $db = new Database();
        $db->query("SELECT kontak FROM pengaturan_umum WHERE id = 1");
        $result = $db->single();
        if ($result && $result->kontak) {
            return $result->kontak;
        } else {
            return '+62 123 456 789';
        }
    } catch (Exception $e) {
        return '+62 123 456 789';
    }
}}
?>
