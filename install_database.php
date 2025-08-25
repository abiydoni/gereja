<?php
/**
 * Script Instalasi Database Sistem Gereja
 * Jalankan file ini untuk setup database otomatis
 */

// Konfigurasi database
$host = 'localhost';
$username = 'root';
$password = '';
$database_name = 'gereja_db';

echo "<h2>Instalasi Database Sistem Gereja</h2>";
echo "<hr>";

try {
    // Koneksi ke MySQL tanpa database
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Berhasil koneksi ke MySQL<br>";
    
    // Buat database jika belum ada
    $sql = "CREATE DATABASE IF NOT EXISTS `$database_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    $pdo->exec($sql);
    echo "✅ Database '$database_name' berhasil dibuat/ditemukan<br>";
    
    // Pilih database
    $pdo->exec("USE `$database_name`");
    echo "✅ Database '$database_name' berhasil dipilih<br>";
    
    // Baca dan jalankan file SQL
    $sql_file = 'database/gereja_db.sql';
    if (file_exists($sql_file)) {
        $sql_content = file_get_contents($sql_file);
        
        // Hapus komentar dan baris kosong
        $sql_content = preg_replace('/--.*$/m', '', $sql_content);
        $sql_content = preg_replace('/^\s*$/m', '', $sql_content);
        
        // Split berdasarkan semicolon
        $queries = array_filter(array_map('trim', explode(';', $sql_content)));
        
        $success_count = 0;
        $error_count = 0;
        
        foreach ($queries as $query) {
            if (!empty($query)) {
                try {
                    $pdo->exec($query);
                    $success_count++;
                } catch (PDOException $e) {
                    echo "❌ Error pada query: " . substr($query, 0, 50) . "...<br>";
                    echo "   Pesan: " . $e->getMessage() . "<br>";
                    $error_count++;
                }
            }
        }
        
        echo "✅ Berhasil menjalankan $success_count query<br>";
        if ($error_count > 0) {
            echo "❌ Gagal menjalankan $error_count query<br>";
        }
        
    } else {
        echo "❌ File SQL tidak ditemukan: $sql_file<br>";
    }
    
    // Test koneksi ke database yang baru dibuat
    try {
        $test_pdo = new PDO("mysql:host=$host;dbname=$database_name", $username, $password);
        $test_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Test query sederhana
        $stmt = $test_pdo->query("SELECT COUNT(*) as total FROM admin");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo "✅ Test koneksi database berhasil<br>";
        echo "✅ Jumlah admin: " . $result['total'] . "<br>";
        
        // Cek tabel yang dibuat
        $stmt = $test_pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        echo "✅ Tabel yang berhasil dibuat:<br>";
        echo "<ul>";
        foreach ($tables as $table) {
            echo "<li>$table</li>";
        }
        echo "</ul>";
        
    } catch (PDOException $e) {
        echo "❌ Test koneksi database gagal: " . $e->getMessage() . "<br>";
    }
    
    echo "<hr>";
    echo "<h3>🎉 Instalasi Database Selesai!</h3>";
    echo "<p>Database '$database_name' berhasil dibuat dengan semua tabel yang diperlukan.</p>";
    echo "<p><strong>Informasi Login Admin:</strong></p>";
    echo "<ul>";
    echo "<li>Username: <strong>admin</strong></li>";
    echo "<li>Password: <strong>password</strong></li>";
    echo "</ul>";
    echo "<p><a href='index.php'>Kembali ke Beranda</a> | <a href='admin/login.php'>Login Admin</a></p>";
    
} catch (PDOException $e) {
    echo "❌ Koneksi database gagal: " . $e->getMessage() . "<br>";
    echo "<p>Pastikan:</p>";
    echo "<ul>";
    echo "<li>XAMPP sudah dijalankan</li>";
    echo "<li>MySQL service aktif</li>";
    echo "<li>Username dan password benar</li>";
    echo "</ul>";
}

// Fungsi untuk menampilkan status
function showStatus($message, $type = 'info') {
    $icon = $type === 'success' ? '✅' : ($type === 'error' ? '❌' : 'ℹ️');
    $color = $type === 'success' ? 'green' : ($type === 'error' ? 'red' : 'blue');
    echo "<span style='color: $color;'>$icon $message</span><br>";
}
?>

<style>
body {
    font-family: Arial, sans-serif;
    max-width: 800px;
    margin: 20px auto;
    padding: 20px;
    background-color: #f5f5f5;
}

h2, h3 {
    color: #333;
}

hr {
    border: none;
    border-top: 2px solid #ddd;
    margin: 20px 0;
}

ul {
    background: white;
    padding: 15px 30px;
    border-radius: 5px;
    border-left: 4px solid #007cba;
}

a {
    color: #007cba;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

.success {
    color: #28a745;
}

.error {
    color: #dc3545;
}

.info {
    color: #17a2b8;
}
</style>
