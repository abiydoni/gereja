<?php
// Login admin sederhana
session_start();

// Check if already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: dashboard.php');
    exit;
}

require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    try {
        $db = new Database();
        $db->query("SELECT id, username, password, nama_lengkap, email, role, status FROM admin WHERE username = ? LIMIT 1", [$username]);
        $user = $db->single();

        if ($user && ($user['status'] ?? '') === 'aktif' && password_verify($password, $user['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = (int)$user['id'];
            $_SESSION['admin_username'] = $user['username'];
            $_SESSION['admin_nama'] = $user['nama_lengkap'] ?: $user['username'];
            $_SESSION['admin_role'] = $user['role'] ?? 'admin';
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Username atau password salah, atau akun nonaktif.';
        }
    } catch (Exception $e) {
        $error = 'Terjadi kesalahan sistem: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Gereja</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-amber-50 via-white to-orange-50 min-h-screen flex items-center justify-center">
    
    <div class="max-w-md w-full mx-auto">
        
        <!-- Login Card -->
        <div class="bg-white rounded-2xl shadow-xl border p-8">
            
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="w-20 h-20 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-church text-3xl text-white"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">Admin Panel</h1>
                <p class="text-gray-600 mt-2">Masuk ke sistem admin gereja</p>
            </div>
            
            <!-- Error Message -->
            <?php if ($error): ?>
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-600 mr-2"></i>
                    <span class="text-red-800"><?= htmlspecialchars($error) ?></span>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Login Form -->
            <form method="POST" class="space-y-6">
                
                <!-- Username -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user mr-2 text-amber-600"></i>
                        Username
                    </label>
                    <input type="text" name="username" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-colors"
                           placeholder="Masukkan username">
                </div>
                
                <!-- Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lock mr-2 text-amber-600"></i>
                        Password
                    </label>
                    <input type="password" name="password" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-colors"
                           placeholder="Masukkan password">
                </div>
                
                <!-- Login Button -->
                <button type="submit" 
                        class="w-full bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-medium py-2 px-4 rounded-lg transition-all duration-300 transform hover:scale-105">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Masuk
                </button>
                
            </form>
            
            <!-- Back to Website -->
            <div class="mt-6 text-center">
                <a href="../" class="text-amber-600 hover:text-amber-700 text-sm">
                    <i class="fas fa-arrow-left mr-1"></i>
                    Kembali ke Website
                </a>
            </div>
            
        </div>
        
    </div>
    
</body>
</html>
