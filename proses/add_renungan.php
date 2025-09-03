<?php
session_start();
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';

// Check if user is logged in (optional - remove if you want public access)
// if (!isAdminLoggedIn()) { 
//     header('Location: ../admin/login.php');
//     exit;
// }

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = trim($_POST['judul'] ?? '');
    $penulis = trim($_POST['penulis'] ?? '');
    $kategori = trim($_POST['kategori'] ?? '');
    $ayat_alkitab = trim($_POST['ayat_alkitab'] ?? '');
    $tanggal_publish = trim($_POST['tanggal_publish'] ?? '');
    $status = trim($_POST['status'] ?? 'draft');
    $konten = trim($_POST['konten'] ?? '');
    $action = $_POST['action'] ?? 'publish';

    // Validation
    if (empty($judul)) {
        $error = 'Judul renungan wajib diisi.';
    } elseif (empty($konten)) {
        $error = 'Konten renungan wajib diisi.';
    } elseif (empty($tanggal_publish)) {
        $error = 'Tanggal publish wajib diisi.';
    } else {
        try {
            $db = new Database();
            
            // Set status based on action
            if ($action === 'save_draft') {
                $status = 'draft';
            } elseif ($action === 'publish') {
                $status = 'published';
            }
            
            // Insert into database
            $db->query(
                "INSERT INTO renungan (judul, penulis, kategori, ayat_alkitab, konten, status, tanggal_publish, created_at, updated_at) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())",
                [
                    $judul,
                    $penulis ?: 'Anonymous',
                    $kategori ?: null,
                    $ayat_alkitab ?: null,
                    $konten,
                    $status,
                    $tanggal_publish
                ]
            );
            
            $success = 'Renungan berhasil disimpan!';
            
            // Redirect after successful save
            if ($status === 'published') {
                header('Location: ../pages/renungan.php?success=1');
            } else {
                header('Location: ../pages/renungan.php?draft=1');
            }
            exit;
            
        } catch (Exception $e) {
            $error = 'Gagal menyimpan renungan: ' . $e->getMessage();
        }
    }
}

// If we reach here, there was an error or this is a direct access
if ($error) {
    // Redirect back with error
    header('Location: ../pages/renungan.php?error=' . urlencode($error));
    exit;
}
?>
