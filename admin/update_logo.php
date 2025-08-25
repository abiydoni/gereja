<?php
require_once '../includes/config.php';
require_once '../includes/database.php';

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/png', 'image/jpeg', 'image/jpg'];
        $file_type = $_FILES['logo']['type'];
        
        if (in_array($file_type, $allowed_types)) {
            $upload_dir = '../assets/images/';
            $file_extension = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
            $new_filename = 'logo.' . $file_extension;
            $upload_path = $upload_dir . $new_filename;
            
            if (move_uploaded_file($_FILES['logo']['tmp_name'], $upload_path)) {
                try {
                    $db = new Database();
                    
                    // Update database dengan nama file logo
                    $db->query("UPDATE pengaturan_sistem SET logo = ? WHERE id = 1");
                    $db->bind(1, $new_filename);
                    
                    if ($db->execute()) {
                        $message = 'Logo berhasil diupdate!';
                    } else {
                        $error = 'Gagal mengupdate database.';
                    }
                } catch (Exception $e) {
                    $error = 'Error database: ' . $e->getMessage();
                }
            } else {
                $error = 'Gagal mengupload file.';
            }
        } else {
            $error = 'Tipe file tidak didukung. Gunakan PNG, JPG, atau JPEG.';
        }
    } else {
        $error = 'Pilih file logo terlebih dahulu.';
    }
}

// Ambil logo saat ini
$current_logo = '';
try {
    $db = new Database();
    $db->query("SELECT logo FROM pengaturan_sistem WHERE id = 1");
    $db->execute();
    $result = $db->single();
    if ($result && isset($result->logo)) {
        $current_logo = $result->logo;
    }
} catch (Exception $e) {
    $error = 'Error mengambil data logo: ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Logo - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Custom Admin CSS -->
    <link rel="stylesheet" href="admin-style.css">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full bg-white rounded-lg shadow-md p-6">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Update Logo</h1>
                <p class="text-gray-600">Upload logo baru untuk sistem gereja</p>
            </div>
            
            <?php if ($message): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <!-- Logo Saat Ini -->
            <?php if ($current_logo): ?>
                <div class="mb-6 text-center">
                    <p class="text-sm text-gray-600 mb-2">Logo Saat Ini:</p>
                    <img src="../assets/images/<?php echo htmlspecialchars($current_logo); ?>" 
                         alt="Current Logo" 
                         class="w-24 h-24 mx-auto object-contain border border-gray-200 rounded">
                    <p class="text-xs text-gray-500 mt-1"><?php echo htmlspecialchars($current_logo); ?></p>
                </div>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data" class="space-y-4">
                <div>
                    <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih File Logo
                    </label>
                    <input type="file" 
                           id="logo" 
                           name="logo" 
                           accept="image/png,image/jpeg,image/jpg"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500">
                    <p class="text-xs text-gray-500 mt-1">
                        Format: PNG, JPG, JPEG. Ukuran maksimal: 2MB
                    </p>
                </div>
                
                <button type="submit" 
                        class="w-full bg-amber-600 text-white py-2 px-4 rounded-md hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-amber-500 transition-colors">
                    <i class="fas fa-upload mr-2"></i>Upload Logo
                </button>
            </form>
            
            <div class="mt-6 text-center">
                <a href="dashboard.php" class="text-amber-600 hover:text-amber-800 text-sm">
                    <i class="fas fa-arrow-left mr-1"></i>Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>
</body>
</html>
