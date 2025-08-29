<?php
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/functions.php';

// Redirect jika sudah login
if (isAdminLoggedIn()) {
    redirect('dashboard.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];
    
    if (empty($username) || empty($password)) {
        $error = 'Username dan password harus diisi!';
    } else {
        try {
            $db = new Database();
            $db->query("SELECT * FROM admin WHERE username = :username AND status = 'aktif'");
            $db->bind(':username', $username);
            $admin = $db->single();
            
            if ($admin && password_verify($password, $admin->password)) {
                $_SESSION['admin_id'] = $admin->id;
                $_SESSION['admin_username'] = $admin->username;
                $_SESSION['admin_nama'] = $admin->nama_lengkap;
                $_SESSION['admin_role'] = $admin->role;
                
                setFlashMessage('success', 'Selamat datang, ' . $admin->nama_lengkap . '!');
                redirect('dashboard.php');
            } else {
                $error = 'Username atau password salah!';
            }
        } catch (Exception $e) {
            $error = 'Terjadi kesalahan sistem. Silakan coba lagi.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Sistem Gereja</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Custom Admin CSS -->
    <link rel="stylesheet" href="admin-style.css">
    
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo dan Judul -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-full mb-4 shadow-lg">
                <i class="fas fa-church text-4xl text-amber-600"></i>
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">Sistem Gereja</h1>
            <p class="text-white opacity-90">Panel Administrator</p>
        </div>
        
        <!-- Form Login -->
        <div class="glass-effect rounded-2xl p-8 shadow-2xl">
            <h2 class="text-2xl font-bold text-white text-center mb-6">Login Admin</h2>
            
            <?php if ($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <i class="fas fa-exclamation-circle mr-2"></i><?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="" class="space-y-6">
                <div>
                    <label for="username" class="block text-white text-sm font-medium mb-2">
                        <i class="fas fa-user mr-2"></i>Username
                    </label>
                    <input type="text" 
                           id="username" 
                           name="username" 
                           value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                           class="w-full px-4 py-3 rounded-lg border-0 focus:ring-2 focus:ring-amber-300 focus:outline-none transition-all"
                           placeholder="Masukkan username"
                           required>
                </div>
                
                <div>
                    <label for="password" class="block text-white text-sm font-medium mb-2">
                        <i class="fas fa-lock mr-2"></i>Password
                    </label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="w-full px-4 py-3 rounded-lg border-0 focus:ring-2 border-0 focus:ring-2 focus:ring-amber-300 focus:outline-none transition-all"
                           placeholder="Masukkan password"
                           required>
                </div>
                
                <button type="submit" 
                        class="w-full bg-white text-amber-600 py-3 px-4 rounded-lg font-semibold hover:bg-gray-100 transition-colors focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-amber-600">
                    <i class="fas fa-sign-in-alt mr-2"></i>Login
                </button>
            </form>
            
            <div class="mt-6 text-center">
                <a href="../" class="text-white hover:text-amber-200 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali ke Beranda
                </a>
            </div>
        </div>
        
        <!-- Informasi Login -->
        <div class="mt-6 text-center text-white opacity-75">
            <p class="text-sm">
                <i class="fas fa-info-circle mr-1"></i>
                Username: <strong>admin</strong> | Password: <strong>password</strong>
            </p>
        </div>
    </div>
    
    <script>
        // SweetAlert untuk notifikasi
        <?php if (isset($_GET['logout'])): ?>
        Swal.fire({
            title: 'Logout Berhasil!',
            text: 'Anda telah keluar dari sistem',
            icon: 'success',
            confirmButtonText: 'OK',
            confirmButtonColor: '#f59e0b'
        });
        <?php endif; ?>
        
        // Auto focus pada username field
        document.getElementById('username').focus();
        
        // Enter key untuk submit form
        document.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.querySelector('form').submit();
            }
        });
    </script>
</body>
</html>
