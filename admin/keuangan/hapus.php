<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/database.php';
require_once __DIR__ . '/../../includes/functions.php';

if (!isAdminLoggedIn()) { redirect('../login.php'); }

$id = isset($_GET['id']) ? trim($_GET['id']) : '';
try {
    $db = new Database();
    $db->query('DELETE FROM keuangan WHERE id = :id');
    $db->bind(':id', $id);
    $db->execute();
} catch (Exception $e) {}

header('Location: ' . rtrim(APP_URL,'/') . '/admin/keuangan/?success=1');
exit;
?>


