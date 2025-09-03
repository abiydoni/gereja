<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/database.php';
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../login.php');
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id > 0) {
    try {
        $db = new Database();
        $db->execute("DELETE FROM kegiatan_kerohanian WHERE id = ?", [$id]);
    } catch (Exception $e) {}
}
header('Location: index.php?deleted=1');
exit;
?>


