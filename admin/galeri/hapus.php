<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/database.php';
require_once __DIR__ . '/../../includes/functions.php';

if (!isAdminLoggedIn()) { redirect('../login.php'); }

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
try {
    $db = new Database();
    // Ambil path file
    $db->query('SELECT path_file FROM galeri WHERE id = :id');
    $db->bind(':id', $id);
    $row = $db->single();

    // Hapus record
    $db->query('DELETE FROM galeri WHERE id = :id');
    $db->bind(':id', $id);
    $db->execute();

    // Hapus file fisik jika ada
    if ($row && isset($row->path_file)) {
        $abs = realpath(__DIR__ . '/../../' . $row->path_file);
        if ($abs && strpos($abs, realpath(__DIR__ . '/../../')) === 0 && file_exists($abs)) {
            @unlink($abs);
        }
    }
} catch (Exception $e) {}

header('Location: ' . rtrim(APP_URL,'/') . '/admin/galeri/?success=1');
exit;
?>


