<?php
/**
 * Halaman Admin untuk Mengelola Struktur Organisasi Majelis Gereja
 */

require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/functions.php';

// Cek session admin
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$db = new Database();
$message = '';
$error = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add_anggota':
                $nama_lengkap = trim($_POST['nama_lengkap']);
                $nama_panggilan = trim($_POST['nama_panggilan']);
                $tempat_lahir = trim($_POST['tempat_lahir']);
                $tanggal_lahir = $_POST['tanggal_lahir'];
                $jenis_kelamin = $_POST['jenis_kelamin'];
                $alamat = trim($_POST['alamat']);
                $no_telepon = trim($_POST['no_telepon']);
                $email = trim($_POST['email']);
                $status_pernikahan = $_POST['status_pernikahan'];
                $tanggal_bergabung = $_POST['tanggal_bergabung'];
                
                if (empty($nama_lengkap)) {
                    $error = 'Nama lengkap harus diisi';
                } else {
                    $sql = "INSERT INTO majelis_anggota (nama_lengkap, nama_panggilan, tempat_lahir, tanggal_lahir, 
                            jenis_kelamin, alamat, no_telepon, email, status_pernikahan, tanggal_bergabung, status_aktif) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'aktif')";
                    
                    $db->query($sql);
                    $db->bind(1, $nama_lengkap);
                    $db->bind(2, $nama_panggilan);
                    $db->bind(3, $tempat_lahir);
                    $db->bind(4, $tanggal_lahir);
                    $db->bind(5, $jenis_kelamin);
                    $db->bind(6, $alamat);
                    $db->bind(7, $no_telepon);
                    $db->bind(8, $email);
                    $db->bind(9, $status_pernikahan);
                    $db->bind(10, $tanggal_bergabung);
                    
                    if ($db->execute()) {
                        $message = 'Anggota berhasil ditambahkan';
                    } else {
                        $error = 'Gagal menambahkan anggota';
                    }
                }
                break;
                
            case 'add_struktur':
                $jabatan_id = $_POST['jabatan_id'];
                $anggota_id = $_POST['anggota_id'];
                $periode_mulai = $_POST['periode_mulai'];
                
                if (empty($jabatan_id) || empty($anggota_id) || empty($periode_mulai)) {
                    $error = 'Semua field harus diisi';
                } else {
                    // Cek apakah sudah ada struktur untuk jabatan ini
                    $db->query("SELECT id FROM majelis_struktur WHERE jabatan_id = ? AND status = 'aktif'");
                    $db->bind(1, $jabatan_id);
                    $existing = $db->single();
                    
                    if ($existing) {
                        $error = 'Jabatan ini sudah memiliki pemegang yang aktif';
                    } else {
                        $sql = "INSERT INTO majelis_struktur (jabatan_id, anggota_id, periode_mulai, status) 
                                VALUES (?, ?, ?, 'aktif')";
                        
                        $db->query($sql);
                        $db->bind(1, $jabatan_id);
                        $db->bind(2, $anggota_id);
                        $db->bind(3, $periode_mulai);
                        
                        if ($db->execute()) {
                            $message = 'Struktur organisasi berhasil ditambahkan';
                        } else {
                            $error = 'Gagal menambahkan struktur organisasi';
                        }
                    }
                }
                break;
                
            case 'update_komisi':
                $komisi_id = $_POST['komisi_id'];
                $ketua_id = $_POST['ketua_id'];
                $wakil_ketua_id = $_POST['wakil_ketua_id'];
                $sekretaris_id = $_POST['sekretaris_id'];
                $bendahara_id = $_POST['bendahara_id'];
                
                $sql = "UPDATE majelis_komisi SET ketua_id = ?, wakil_ketua_id = ?, sekretaris_id = ?, bendahara_id = ? 
                        WHERE id = ?";
                
                $db->query($sql);
                $db->bind(1, $ketua_id ?: null);
                $db->bind(2, $wakil_ketua_id ?: null);
                $db->bind(3, $sekretaris_id ?: null);
                $db->bind(4, $bendahara_id ?: null);
                $db->bind(5, $komisi_id);
                
                if ($db->execute()) {
                    $message = 'Struktur komisi berhasil diupdate';
                } else {
                    $error = 'Gagal mengupdate struktur komisi';
                }
                break;

            case 'add_periode':
                $nama_periode = trim($_POST['nama_periode']);
                $tahun_mulai = intval($_POST['tahun_mulai']);
                $tahun_selesai = intval($_POST['tahun_selesai']);
                $status = $_POST['status'] === 'aktif' ? 'aktif' : 'nonaktif';

                if (empty($nama_periode) || empty($tahun_mulai) || empty($tahun_selesai)) {
                    $error = 'Nama periode, tahun mulai, dan tahun selesai harus diisi';
                } else {
                    $db->query("INSERT INTO majelis_periode (nama_periode, tahun_mulai, tahun_selesai, status) VALUES (?, ?, ?, ?)");
                    $db->bind(1, $nama_periode);
                    $db->bind(2, $tahun_mulai);
                    $db->bind(3, $tahun_selesai);
                    $db->bind(4, $status);
                    if ($db->execute()) {
                        $message = 'Periode berhasil ditambahkan';
                    } else {
                        $error = 'Gagal menambahkan periode';
                    }
                }
                break;

            case 'set_periode_aktif':
                $periode_id = intval($_POST['periode_id']);
                if ($periode_id <= 0) {
                    $error = 'Periode tidak valid';
                } else {
                    // Nonaktifkan semua, lalu aktifkan satu
                    $db->query("UPDATE majelis_periode SET status = 'nonaktif'");
                    $db->execute();
                    $db->query("UPDATE majelis_periode SET status = 'aktif' WHERE id = ?");
                    $db->bind(1, $periode_id);
                    if ($db->execute()) {
                        $message = 'Periode aktif berhasil diubah';
                    } else {
                        $error = 'Gagal mengubah periode aktif';
                    }
                }
                break;
        }
    }
}

// Ambil data untuk dropdown
$db->query("SELECT id, nama_jabatan FROM majelis_jabatan WHERE status_aktif = 'aktif' ORDER BY level_hierarki, urutan_tampil");
$jabatan_list = $db->resultSet();

$db->query("SELECT id, nama_lengkap FROM majelis_anggota WHERE status_aktif = 'aktif' ORDER BY nama_lengkap");
$anggota_list = $db->resultSet();

$db->query("SELECT id, nama_komisi FROM majelis_komisi WHERE status_aktif = 'aktif' ORDER BY nama_komisi");
$komisi_list = $db->resultSet();

// Ambil data struktur organisasi
$db->query("SELECT ms.id, mj.nama_jabatan, ma.nama_lengkap, ma.nama_panggilan, ms.periode_mulai, ms.status
            FROM majelis_struktur ms 
            JOIN majelis_jabatan mj ON ms.jabatan_id = mj.id 
            JOIN majelis_anggota ma ON ms.anggota_id = ma.id 
            ORDER BY mj.level_hierarki, mj.urutan_tampil");
$struktur_list = $db->resultSet();

// Ambil data komisi dengan struktur
$db->query("SELECT mk.id, mk.nama_komisi, mk.deskripsi,
            ketua.nama_lengkap as ketua_nama,
            wakil.nama_lengkap as wakil_nama,
            sekretaris.nama_lengkap as sekretaris_nama,
            bendahara.nama_lengkap as bendahara_nama
            FROM majelis_komisi mk
            LEFT JOIN majelis_anggota ketua ON mk.ketua_id = ketua.id
            LEFT JOIN majelis_anggota wakil ON mk.wakil_ketua_id = wakil.id
            LEFT JOIN majelis_anggota sekretaris ON mk.sekretaris_id = sekretaris.id
            LEFT JOIN majelis_anggota bendahara ON mk.bendahara_id = bendahara.id
            WHERE mk.status_aktif = 'aktif'
            ORDER BY mk.nama_komisi");
$komisi_struktur = $db->resultSet();

// Periode aktif dan daftar periode
$db->query("SELECT * FROM majelis_periode ORDER BY tahun_mulai DESC");
$periode_list = $db->resultSet();

$db->query("SELECT * FROM majelis_periode WHERE status = 'aktif' ORDER BY tahun_mulai DESC LIMIT 1");
$periode_aktif = $db->single();

// Riwayat jabatan (terakhir 50)
$db->query("SELECT rj.id, ma.nama_lengkap, mj.nama_jabatan, mp.nama_periode, rj.tanggal_mulai, rj.tanggal_selesai, rj.status
            FROM majelis_riwayat_jabatan rj
            JOIN majelis_anggota ma ON rj.anggota_id = ma.id
            JOIN majelis_jabatan mj ON rj.jabatan_id = mj.id
            JOIN majelis_periode mp ON rj.periode_id = mp.id
            ORDER BY rj.tanggal_mulai DESC, rj.id DESC
            LIMIT 50");
$riwayat_list = $db->resultSet();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Majelis Gereja - Admin</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #374151;
        }
        
        .form-input {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 0.875rem;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #f59e0b;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
        }
        
        .btn {
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .btn-primary {
            background-color: #f59e0b;
            color: white;
            border: none;
        }
        
        .btn-primary:hover {
            background-color: #d97706;
        }
        
        .btn-secondary {
            background-color: #6b7280;
            color: white;
            border: none;
        }
        
        .btn-secondary:hover {
            background-color: #4b5563;
        }
        
        .btn-danger {
            background-color: #ef4444;
            color: white;
            border: none;
        }
        
        .btn-danger:hover {
            background-color: #dc2626;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold text-gray-900">
                        <i class="fas fa-sitemap text-amber-600 mr-3"></i>
                        Manajemen Majelis Gereja
                    </h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="dashboard.php" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Messages -->
        <?php if ($message): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            <i class="fas fa-check-circle mr-2"></i><?php echo htmlspecialchars($message); ?>
        </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <i class="fas fa-exclamation-circle mr-2"></i><?php echo htmlspecialchars($error); ?>
        </div>
        <?php endif; ?>

        <!-- Tabs -->
        <div class="mb-8">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8">
                    <button onclick="showTab('anggota')" class="tab-button active py-2 px-1 border-b-2 border-amber-500 font-medium text-sm text-amber-600">
                        <i class="fas fa-users mr-2"></i>Anggota Majelis
                    </button>
                    <button onclick="showTab('struktur')" class="tab-button py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        <i class="fas fa-sitemap mr-2"></i>Struktur Organisasi
                    </button>
                    <button onclick="showTab('komisi')" class="tab-button py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        <i class="fas fa-handshake mr-2"></i>Komisi Pelayanan
                    </button>
                    <button onclick="showTab('periode')" class="tab-button py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        <i class="fas fa-calendar-alt mr-2"></i>Periode
                    </button>
                    <button onclick="showTab('riwayat')" class="tab-button py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        <i class="fas fa-history mr-2"></i>Riwayat Jabatan
                    </button>
                </nav>
            </div>
        </div>

        <!-- Tab Content -->
        
        <!-- Tab 1: Anggota Majelis -->
        <div id="tab-anggota" class="tab-content">
            <div class="bg-white shadow rounded-lg p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-plus text-amber-600 mr-2"></i>
                    Tambah Anggota Baru
                </h3>
                
                <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input type="hidden" name="action" value="add_anggota">
                    
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap *</label>
                        <input type="text" name="nama_lengkap" class="form-input" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Nama Panggilan</label>
                        <input type="text" name="nama_panggilan" class="form-input">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" class="form-input">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="form-input">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Jenis Kelamin *</label>
                        <select name="jenis_kelamin" class="form-input" required>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Status Pernikahan</label>
                        <select name="status_pernikahan" class="form-input">
                            <option value="belum_menikah">Belum Menikah</option>
                            <option value="menikah">Menikah</option>
                            <option value="cerai">Cerai</option>
                        </select>
                    </div>
                    
                    <div class="form-group md:col-span-2">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-input" rows="3"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">No. Telepon</label>
                        <input type="tel" name="no_telepon" class="form-input">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-input">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Tanggal Bergabung</label>
                        <input type="date" name="tanggal_bergabung" class="form-input">
                    </div>
                    
                    <div class="form-group md:col-span-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-2"></i>Simpan Anggota
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Daftar Anggota -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-list text-amber-600 mr-2"></i>
                    Daftar Anggota Majelis
                </h3>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Panggilan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Kelamin</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($anggota_list as $anggota): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($anggota['nama_lengkap']); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500"><?php echo htmlspecialchars($anggota['nama_panggilan']); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full <?php echo $anggota['jenis_kelamin'] === 'L' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800'; ?>">
                                        <?php echo $anggota['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan'; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Aktif
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button class="text-amber-600 hover:text-amber-900 mr-3">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tab 2: Struktur Organisasi -->
        <div id="tab-struktur" class="tab-content hidden">
            <div class="bg-white shadow rounded-lg p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-plus text-amber-600 mr-2"></i>
                    Tambah Struktur Organisasi
                </h3>
                
                <form method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <input type="hidden" name="action" value="add_struktur">
                    
                    <div class="form-group">
                        <label class="form-label">Jabatan *</label>
                        <select name="jabatan_id" class="form-input" required>
                            <option value="">Pilih Jabatan</option>
                            <?php foreach ($jabatan_list as $jabatan): ?>
                            <option value="<?php echo $jabatan['id']; ?>"><?php echo htmlspecialchars($jabatan['nama_jabatan']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Anggota *</label>
                        <select name="anggota_id" class="form-input" required>
                            <option value="">Pilih Anggota</option>
                            <?php foreach ($anggota_list as $anggota): ?>
                            <option value="<?php echo $anggota['id']; ?>"><?php echo htmlspecialchars($anggota['nama_lengkap']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Periode Mulai *</label>
                        <input type="date" name="periode_mulai" class="form-input" required>
                    </div>
                    
                    <div class="form-group md:col-span-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-2"></i>Simpan Struktur
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Daftar Struktur -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-list text-amber-600 mr-2"></i>
                    Struktur Organisasi Saat Ini
                </h3>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jabatan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Panggilan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode Mulai</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($struktur_list as $struktur): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($struktur['nama_jabatan']); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?php echo htmlspecialchars($struktur['nama_lengkap']); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500"><?php echo htmlspecialchars($struktur['nama_panggilan']); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500"><?php echo date('d/m/Y', strtotime($struktur['periode_mulai'])); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        <?php echo ucfirst($struktur['status']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button class="text-amber-600 hover:text-amber-900 mr-3">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tab 3: Komisi Pelayanan -->
        <div id="tab-komisi" class="tab-content hidden">
            <!-- Daftar Komisi -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-list text-amber-600 mr-2"></i>
                    Struktur Komisi Pelayanan
                </h3>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <?php foreach ($komisi_struktur as $komisi): ?>
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-900 mb-3"><?php echo htmlspecialchars($komisi['nama_komisi']); ?></h4>
                        
                        <form method="POST" class="space-y-3">
                            <input type="hidden" name="action" value="update_komisi">
                            <input type="hidden" name="komisi_id" value="<?php echo $komisi['id']; ?>">
                            
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Ketua</label>
                                    <select name="ketua_id" class="form-input text-sm">
                                        <option value="">Pilih Ketua</option>
                                        <?php foreach ($anggota_list as $anggota): ?>
                                        <option value="<?php echo $anggota['id']; ?>" <?php echo ($komisi['ketua_nama'] === $anggota['nama_lengkap']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($anggota['nama_lengkap']); ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Wakil Ketua</label>
                                    <select name="wakil_ketua_id" class="form-input text-sm">
                                        <option value="">Pilih Wakil Ketua</option>
                                        <?php foreach ($anggota_list as $anggota): ?>
                                        <option value="<?php echo $anggota['id']; ?>" <?php echo ($komisi['wakil_nama'] === $anggota['nama_lengkap']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($anggota['nama_lengkap']); ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Sekretaris</label>
                                    <select name="sekretaris_id" class="form-input text-sm">
                                        <option value="">Pilih Sekretaris</option>
                                        <?php foreach ($anggota_list as $anggota): ?>
                                        <option value="<?php echo $anggota['id']; ?>" <?php echo ($komisi['sekretaris_nama'] === $anggota['nama_lengkap']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($anggota['nama_lengkap']); ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Bendahara</label>
                                    <select name="bendahara_id" class="form-input text-sm">
                                        <option value="">Pilih Bendahara</option>
                                        <?php foreach ($anggota_list as $anggota): ?>
                                        <option value="<?php echo $anggota['id']; ?>" <?php echo ($komisi['bendahara_nama'] === $anggota['nama_lengkap']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($anggota['nama_lengkap']); ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary text-sm w-full">
                                <i class="fas fa-save mr-2"></i>Update Struktur
                            </button>
                        </form>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Tab 4: Periode -->
        <div id="tab-periode" class="tab-content hidden">
            <div class="bg-white shadow rounded-lg p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-plus text-amber-600 mr-2"></i>
                    Tambah Periode
                </h3>
                <form method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <input type="hidden" name="action" value="add_periode">
                    <div class="form-group">
                        <label class="form-label">Nama Periode *</label>
                        <input type="text" name="nama_periode" class="form-input" placeholder="Mis. Periode 2025" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tahun Mulai *</label>
                        <input type="number" name="tahun_mulai" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tahun Selesai *</label>
                        <input type="number" name="tahun_selesai" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-input">
                            <option value="nonaktif">Nonaktif</option>
                            <option value="aktif">Aktif</option>
                        </select>
                    </div>
                    <div class="form-group md:col-span-4">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-2"></i>Simpan Periode</button>
                    </div>
                </form>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-list text-amber-600 mr-2"></i>
                    Daftar Periode
                </h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tahun</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($periode_list as $periode): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($periode['nama_periode']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo (int)$periode['tahun_mulai'] . ' - ' . (int)$periode['tahun_selesai']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full <?php echo $periode['status'] === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'; ?>">
                                        <?php echo ucfirst($periode['status']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <?php if ($periode['status'] !== 'aktif'): ?>
                                    <form method="POST" class="inline">
                                        <input type="hidden" name="action" value="set_periode_aktif">
                                        <input type="hidden" name="periode_id" value="<?php echo $periode['id']; ?>">
                                        <button type="submit" class="text-amber-600 hover:text-amber-900"><i class="fas fa-check mr-1"></i>Jadikan Aktif</button>
                                    </form>
                                    <?php else: ?>
                                    <span class="text-gray-400">Periode Aktif</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tab 5: Riwayat Jabatan -->
        <div id="tab-riwayat" class="tab-content hidden">
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-history text-amber-600 mr-2"></i>
                    Riwayat Jabatan (terbaru)
                </h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jabatan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mulai</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Selesai</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($riwayat_list as $row): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($row['nama_lengkap']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($row['nama_jabatan']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($row['nama_periode']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($row['tanggal_mulai']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $row['tanggal_selesai'] ? htmlspecialchars($row['tanggal_selesai']) : '-'; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full <?php echo $row['status'] === 'aktif' ? 'bg-green-100 text-green-800' : ($row['status'] === 'selesai' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800'); ?>">
                                        <?php echo ucfirst($row['status']); ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <script>
        function showTab(tabName) {
            // Hide all tab contents
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(content => content.classList.add('hidden'));
            
            // Remove active class from all tab buttons
            const tabButtons = document.querySelectorAll('.tab-button');
            tabButtons.forEach(button => {
                button.classList.remove('active', 'border-amber-500', 'text-amber-600');
                button.classList.add('border-transparent', 'text-gray-500');
            });
            
            // Show selected tab content
            document.getElementById('tab-' + tabName).classList.remove('hidden');
            
            // Add active class to selected tab button
            event.target.classList.add('active', 'border-amber-500', 'text-amber-600');
            event.target.classList.remove('border-transparent', 'text-gray-500');
        }
    </script>
</body>
</html>
