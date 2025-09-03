<?php
/**
 * Halaman Admin untuk Mengelola Struktur Organisasi Majelis Gereja
 */

require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/functions.php';

// Cek session admin
if (!isAdminLoggedIn()) {
    redirect('login.php');
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
                    $db->query($sql, [
                        $nama_lengkap,
                        $nama_panggilan,
                        $tempat_lahir,
                        $tanggal_lahir,
                        $jenis_kelamin,
                        $alamat,
                        $no_telepon,
                        $email,
                        $status_pernikahan,
                        $tanggal_bergabung
                    ]);
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
                    $db->query("SELECT id FROM majelis_struktur WHERE jabatan_id = ? AND status = 'aktif'", [$jabatan_id]);
                    $existing = $db->single();
                    
                    if ($existing) {
                        $error = 'Jabatan ini sudah memiliki pemegang yang aktif';
                    } else {
                        $sql = "INSERT INTO majelis_struktur (jabatan_id, anggota_id, periode_mulai, status) 
                                VALUES (?, ?, ?, 'aktif')";
                        $db->query($sql, [$jabatan_id, $anggota_id, $periode_mulai]);
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
                $anggota_ids = isset($_POST['anggota_ids']) && is_array($_POST['anggota_ids']) ? $_POST['anggota_ids'] : [];

                // Simpan daftar anggota sebagai CSV di kolom TEXT anggota_id (mis: 1,2,3)
                $anggota_ids = array_filter(array_map('intval', $anggota_ids));
                $anggota_csv = implode(',', $anggota_ids);

                $sql = "UPDATE majelis_komisi SET ketua_id = ?, wakil_ketua_id = ?, anggota_id = ? WHERE id = ?";
                $db->query($sql, [
                    $ketua_id ?: null,
                    $wakil_ketua_id ?: null,
                    $anggota_csv,
                    $komisi_id
                ]);
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
                    $db->query("INSERT INTO majelis_periode (nama_periode, tahun_mulai, tahun_selesai, status) VALUES (?, ?, ?, ?)", [
                        $nama_periode,
                        $tahun_mulai,
                        $tahun_selesai,
                        $status
                    ]);
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
                    $db->query("UPDATE majelis_periode SET status = 'aktif' WHERE id = ?", [$periode_id]);
                    if ($db->execute()) {
                        $message = 'Periode aktif berhasil diubah';
                    } else {
                        $error = 'Gagal mengubah periode aktif';
                    }
                }
                break;

            // CRUD Anggota Majelis (via modal pada Tab Anggota)
            case 'create_majelis_anggota':
                $nama_lengkap = trim($_POST['nama_lengkap'] ?? '');
                $nama_panggilan = trim($_POST['nama_panggilan'] ?? '');
                $tempat_lahir = trim($_POST['tempat_lahir'] ?? '');
                $tanggal_lahir = trim($_POST['tanggal_lahir'] ?? '');
                $jenis_kelamin = $_POST['jenis_kelamin'] ?? '';
                $alamat = trim($_POST['alamat'] ?? '');
                $no_telepon = trim($_POST['no_telepon'] ?? '');
                $email = trim($_POST['email'] ?? '');
                $status_pernikahan = trim($_POST['status_pernikahan'] ?? '');
                $tanggal_bergabung = trim($_POST['tanggal_bergabung'] ?? '');
                $status_aktif = $_POST['status_aktif'] ?? 'aktif';
                if ($nama_lengkap === '' || ($jenis_kelamin !== 'L' && $jenis_kelamin !== 'P')) {
                    $error = 'Nama dan jenis kelamin wajib diisi.';
                    break;
                }
                // Gunakan skema lengkap bila kolom tersedia
                try {
                    $db->query("INSERT INTO majelis_anggota (nama_lengkap, nama_panggilan, tempat_lahir, tanggal_lahir, jenis_kelamin, alamat, no_telepon, email, status_pernikahan, tanggal_bergabung, status_aktif) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        $nama_lengkap, $nama_panggilan ?: null, $tempat_lahir ?: null, $tanggal_lahir ?: null, $jenis_kelamin, $alamat ?: null, $no_telepon ?: null, $email ?: null, $status_pernikahan ?: null, $tanggal_bergabung ?: null, $status_aktif
                    ]);
                    $db->execute();
                } catch (Exception $e) {
                    // Fallback minimal jika kolom berbeda
                    $db->query("INSERT INTO majelis_anggota (nama_lengkap, nama_panggilan, jenis_kelamin, status_aktif) VALUES (?, ?, ?, ?)", [
                        $nama_lengkap, $nama_panggilan ?: null, $jenis_kelamin, $status_aktif
                    ]);
                    $db->execute();
                }
                $message = 'Anggota berhasil ditambahkan';
                break;

            case 'update_majelis_anggota':
                $anggota_id = intval($_POST['anggota_id'] ?? 0);
                $nama_lengkap = trim($_POST['nama_lengkap'] ?? '');
                $nama_panggilan = trim($_POST['nama_panggilan'] ?? '');
                $tempat_lahir = trim($_POST['tempat_lahir'] ?? '');
                $tanggal_lahir = trim($_POST['tanggal_lahir'] ?? '');
                $jenis_kelamin = $_POST['jenis_kelamin'] ?? '';
                $alamat = trim($_POST['alamat'] ?? '');
                $no_telepon = trim($_POST['no_telepon'] ?? '');
                $email = trim($_POST['email'] ?? '');
                $status_pernikahan = trim($_POST['status_pernikahan'] ?? '');
                $tanggal_bergabung = trim($_POST['tanggal_bergabung'] ?? '');
                $status_aktif = $_POST['status_aktif'] ?? 'aktif';
                if ($anggota_id <= 0 || $nama_lengkap === '' || ($jenis_kelamin !== 'L' && $jenis_kelamin !== 'P')) {
                    $error = 'Data tidak valid.';
                    break;
                }
                try {
                    $db->query("UPDATE majelis_anggota SET nama_lengkap = ?, nama_panggilan = ?, tempat_lahir = ?, tanggal_lahir = ?, jenis_kelamin = ?, alamat = ?, no_telepon = ?, email = ?, status_pernikahan = ?, tanggal_bergabung = ?, status_aktif = ? WHERE id = ?", [
                        $nama_lengkap, $nama_panggilan ?: null, $tempat_lahir ?: null, $tanggal_lahir ?: null, $jenis_kelamin, $alamat ?: null, $no_telepon ?: null, $email ?: null, $status_pernikahan ?: null, $tanggal_bergabung ?: null, $status_aktif, $anggota_id
                    ]);
                    $db->execute();
                } catch (Exception $e) {
                    $db->query("UPDATE majelis_anggota SET nama_lengkap = ?, nama_panggilan = ?, jenis_kelamin = ?, status_aktif = ? WHERE id = ?", [
                        $nama_lengkap, $nama_panggilan ?: null, $jenis_kelamin, $status_aktif, $anggota_id
                    ]);
                    $db->execute();
                }
                $message = 'Anggota berhasil diperbarui';
                break;

            case 'delete_majelis_anggota':
                $anggota_id = intval($_POST['anggota_id'] ?? 0);
                if ($anggota_id <= 0) { $error = 'ID tidak valid.'; break; }
                $db->query("DELETE FROM majelis_anggota WHERE id = ?", [$anggota_id]);
                $db->execute();
                $message = 'Anggota berhasil dihapus';
                break;
        }
    }
}

// Ambil data untuk dropdown
$db->query("SELECT id, nama_jabatan FROM majelis_jabatan WHERE status_aktif = 'aktif' ORDER BY level_hierarki, urutan_tampil");
$jabatan_list = $db->resultSet();

// Ambil anggota dengan pencarian + pagination
$q_anggota = isset($_GET['q_anggota']) ? trim($_GET['q_anggota']) : '';
$page_anggota = isset($_GET['page_anggota']) ? max(1, (int)$_GET['page_anggota']) : 1;
$perPage_anggota = 10;
$offset_anggota = ($page_anggota - 1) * $perPage_anggota;

$where = '';
$params = [];
if ($q_anggota !== '') {
    $where = "WHERE nama_lengkap LIKE ? OR nama_panggilan LIKE ? OR no_telepon LIKE ? OR email LIKE ?";
    $kw = '%' . $q_anggota . '%';
    $params = [$kw, $kw, $kw, $kw];
}

try {
    $countRow = $db->fetchOne("SELECT COUNT(*) as total FROM majelis_anggota $where", $params);
    $totalRowsAnggota = (int)($countRow['total'] ?? 0);
    $totalPagesAnggota = max(1, (int)ceil($totalRowsAnggota / $perPage_anggota));
    $page_anggota = min($page_anggota, $totalPagesAnggota);
    $offset_anggota = ($page_anggota - 1) * $perPage_anggota;
    $anggota_list = $db->fetchAll(
        "SELECT id, nama_lengkap, nama_panggilan, jenis_kelamin, status_aktif, no_telepon, email,
                tempat_lahir, tanggal_lahir, alamat, status_pernikahan, tanggal_bergabung
         FROM majelis_anggota $where
         ORDER BY nama_lengkap
         LIMIT $perPage_anggota OFFSET $offset_anggota",
        $params
    );
} catch (Exception $e) {
    $anggota_list = [];
    $totalRowsAnggota = 0;
    $totalPagesAnggota = 1;
}

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
$db->query("SELECT mk.id, mk.nama_komisi, mk.deskripsi, mk.anggota_id,
            ketua.nama_lengkap as ketua_nama,
            wakil.nama_lengkap as wakil_nama
            FROM majelis_komisi mk
            LEFT JOIN majelis_anggota ketua ON mk.ketua_id = ketua.id
            LEFT JOIN majelis_anggota wakil ON mk.wakil_ketua_id = wakil.id
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

<?php $pageTitle = 'Manajemen Majelis Gereja'; require_once __DIR__ . '/partials/header.php'; ?>

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
            <!-- Daftar Anggota -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-list text-amber-600 mr-2"></i>
                    Daftar Anggota Majelis
                </h3>
                <div class="mb-4 flex items-center gap-3">
                    <form method="GET" class="flex items-center gap-2">
                        <input type="text" name="q_anggota" value="<?php echo htmlspecialchars($q_anggota); ?>" class="form-input" placeholder="Cari nama/panggilan/telepon/email">
                        <button type="submit" class="btn btn-secondary">Cari</button>
                        <?php if ($q_anggota !== ''): ?><a href="?" class="btn btn-secondary">Reset</a><?php endif; ?>
                    </form>
                    <button type="button" class="btn btn-primary" onclick="openAnggotaModal()"><i class="fas fa-plus mr-2"></i>Tambah Anggota</button>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Panggilan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">JK</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php $rowNo = $offset_anggota; foreach ($anggota_list as $anggota): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?php echo ++$rowNo; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo htmlspecialchars($anggota['nama_lengkap']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?php echo htmlspecialchars($anggota['nama_panggilan'] ?? '—'); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?php echo htmlspecialchars($anggota['jenis_kelamin'] ?? ''); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full <?php echo ($anggota['status_aktif'] ?? 'aktif') === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'; ?>">
                                        <?php echo ucfirst($anggota['status_aktif'] ?? 'aktif'); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button type="button" class="text-amber-600 hover:text-amber-900 mr-2 btn-edit-anggota" title="Edit"
                                        data-id="<?php echo (int)$anggota['id']; ?>"
                                        data-nama_lengkap="<?php echo htmlspecialchars($anggota['nama_lengkap']); ?>"
                                        data-nama_panggilan="<?php echo htmlspecialchars($anggota['nama_panggilan'] ?? ''); ?>"
                                        data-tempat_lahir="<?php echo htmlspecialchars($anggota['tempat_lahir'] ?? ''); ?>"
                                        data-tanggal_lahir="<?php echo htmlspecialchars($anggota['tanggal_lahir'] ?? ''); ?>"
                                        data-jenis_kelamin="<?php echo htmlspecialchars($anggota['jenis_kelamin'] ?? ''); ?>"
                                        data-alamat="<?php echo htmlspecialchars($anggota['alamat'] ?? ''); ?>"
                                        data-no_telepon="<?php echo htmlspecialchars($anggota['no_telepon'] ?? ''); ?>"
                                        data-email="<?php echo htmlspecialchars($anggota['email'] ?? ''); ?>"
                                        data-status_pernikahan="<?php echo htmlspecialchars($anggota['status_pernikahan'] ?? ''); ?>"
                                        data-tanggal_bergabung="<?php echo htmlspecialchars($anggota['tanggal_bergabung'] ?? ''); ?>"
                                        data-status_aktif="<?php echo htmlspecialchars($anggota['status_aktif'] ?? 'aktif'); ?>"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form method="POST" class="inline-block form-delete-anggota">
                                        <input type="hidden" name="action" value="delete_majelis_anggota">
                                        <input type="hidden" name="anggota_id" value="<?php echo (int)$anggota['id']; ?>">
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="flex items-center justify-between text-sm text-gray-600 mt-3">
                    <div>Menampilkan <?php echo count($anggota_list); ?> dari <?php echo number_format($totalRowsAnggota); ?> data</div>
                    <div class="flex items-center gap-1">
                        <?php
                        $baseUrl = strtok($_SERVER['REQUEST_URI'], '?');
                        $qs = $q_anggota !== '' ? ('&q_anggota=' . urlencode($q_anggota)) : '';
                        if ($page_anggota > 1) {
                            echo '<a class="px-3 py-1 rounded border" href="'.$baseUrl.'?page_anggota='.($page_anggota-1).$qs.'">&laquo; Prev</a>';
                        }
                        $start = max(1, $page_anggota - 2);
                        $end = min($totalPagesAnggota, $page_anggota + 2);
                        for ($p=$start; $p<=$end; $p++) {
                            $cls = $p===$page_anggota ? 'bg-amber-600 text-white' : 'border';
                            echo '<a class="px-3 py-1 rounded '.$cls.'" href="'.$baseUrl.'?page_anggota='.$p.$qs.'">'.$p.'</a>';
                        }
                        if ($page_anggota < $totalPagesAnggota) {
                            echo '<a class="px-3 py-1 rounded border" href="'.$baseUrl.'?page_anggota='.($page_anggota+1).$qs.'">Next &raquo;</a>';
                        }
                        ?>
                    </div>
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
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jabatan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Panggilan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode Mulai</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php $no=1; foreach ($struktur_list as $struktur): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?php echo $no++; ?></td>
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
                                <div class="md:col-span-2">
                                    <?php
                                    // Ambil anggota komisi yang sudah tersimpan untuk preselect
                                    $existingAnggotaIds = [];
                                    try {
                                        $dbPre = new Database();
                                        $dbPre->query("SELECT anggota_id FROM majelis_komisi_anggota WHERE komisi_id = ?", [$komisi['id']]);
                                        $rowsPre = $dbPre->resultSet();
                                        foreach ($rowsPre as $rp) {
                                            $existingAnggotaIds[] = is_array($rp) ? ($rp['anggota_id'] ?? null) : ($rp->anggota_id ?? null);
                                        }
                                    } catch (Exception $e) {}
                                    ?>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Anggota Komisi (multi)</label>
                                    <select name="anggota_ids[]" class="form-input text-sm" multiple size="6">
                                        <?php foreach ($anggota_list as $anggota): ?>
                                        <?php $isSelected = in_array($anggota['id'], $existingAnggotaIds, true); ?>
                                        <option value="<?php echo $anggota['id']; ?>" <?php echo $isSelected ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($anggota['nama_lengkap']); ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">Tahan Ctrl/Command untuk memilih lebih dari satu.</p>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary text-sm w-full">
                                <i class="fas fa-save mr-2"></i>Update Struktur
                            </button>
                        </form>
                        <?php
                        // Tampilkan daftar anggota komisi dari kolom TEXT anggota_id (CSV)
                        $anggotaCsv = $komisi['anggota_id'] ?? '';
                        $anggotaCsv = is_array($komisi) ? ($komisi['anggota_id'] ?? '') : $anggotaCsv;
                        $anggotaCsv = trim((string)$anggotaCsv);
                        $namaAnggota = [];
                        if ($anggotaCsv !== '') {
                            $ids = array_filter(array_map('intval', explode(',', $anggotaCsv)));
                            if (!empty($ids)) {
                                $placeholders = implode(',', array_fill(0, count($ids), '?'));
                                try {
                                    $dbAng = new Database();
                                    $dbAng->query("SELECT nama_lengkap FROM majelis_anggota WHERE id IN ($placeholders) ORDER BY nama_lengkap", $ids);
                                    $rows = $dbAng->resultSet();
                                    foreach ($rows as $r) {
                                        $namaAnggota[] = is_array($r) ? ($r['nama_lengkap'] ?? '') : ($r->nama_lengkap ?? '');
                                    }
                                } catch (Exception $e) {}
                            }
                        }

                        if (!empty($komisi['ketua_nama']) || !empty($komisi['wakil_nama']) || !empty($namaAnggota)) {
                            echo '<div class="mt-4">';
                            if (!empty($komisi['ketua_nama']) || !empty($komisi['wakil_nama'])) {
                                echo '<div class="text-xs font-medium text-gray-700 mb-1">Struktur Inti:</div>';
                                echo '<ul class="ml-5 text-sm text-gray-800 space-y-1">';
                                if (!empty($komisi['ketua_nama'])) { echo '<li><span class="font-semibold">Ketua:</span> ' . htmlspecialchars($komisi['ketua_nama']) . '</li>'; }
                                if (!empty($komisi['wakil_nama'])) { echo '<li><span class="font-semibold">Wakil Ketua:</span> ' . htmlspecialchars($komisi['wakil_nama']) . '</li>'; }
                                echo '</ul>';
                            }
                            if (!empty($namaAnggota)) {
                                echo '<div class="text-xs font-medium text-gray-700 mb-1 mt-3">Anggota saat ini:</div><ul class="list-disc ml-5 text-sm text-gray-800">';
                                foreach ($namaAnggota as $nm) {
                                    echo '<li>' . htmlspecialchars($nm) . '</li>';
                                }
                                echo '</ul>';
                            }
                            echo '</div>';
                        }
                        ?>
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
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tahun</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php $no=1; foreach ($periode_list as $periode): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?php echo $no++; ?></td>
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
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jabatan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mulai</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Selesai</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php $no=1; foreach ($riwayat_list as $row): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?php echo $no++; ?></td>
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

        function openAnggotaModal(){
            document.getElementById('anggotaModal').classList.remove('hidden');
        }
        function closeAnggotaModal(){
            document.getElementById('anggotaModal').classList.add('hidden');
        }
    </script>

    <!-- SweetAlert2 & Edit Modal Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Edit modal handlers
        function openEditAnggotaModal(){
            document.getElementById('editAnggotaModal').classList.remove('hidden');
        }
        function closeEditAnggotaModal(){
            document.getElementById('editAnggotaModal').classList.add('hidden');
        }

        // Attach edit button listeners
        document.querySelectorAll('.btn-edit-anggota').forEach(function(btn){
            btn.addEventListener('click', function(){
                var m = document.getElementById('editAnggotaModal');
                m.querySelector('input[name="anggota_id"]').value = this.dataset.id || '';
                m.querySelector('input[name="nama_lengkap"]').value = this.dataset.nama_lengkap || '';
                m.querySelector('input[name="nama_panggilan"]').value = this.dataset.nama_panggilan || '';
                m.querySelector('input[name="tempat_lahir"]').value = this.dataset.tempat_lahir || '';
                m.querySelector('input[name="tanggal_lahir"]').value = this.dataset.tanggal_lahir || '';
                m.querySelector('select[name="jenis_kelamin"]').value = this.dataset.jenis_kelamin || '';
                m.querySelector('textarea[name="alamat"]').value = this.dataset.alamat || '';
                m.querySelector('input[name="no_telepon"]').value = this.dataset.no_telepon || '';
                m.querySelector('input[name="email"]').value = this.dataset.email || '';
                m.querySelector('select[name="status_pernikahan"]').value = this.dataset.status_pernikahan || '';
                m.querySelector('input[name="tanggal_bergabung"]').value = this.dataset.tanggal_bergabung || '';
                m.querySelector('select[name="status_aktif"]').value = this.dataset.status_aktif || 'aktif';
                openEditAnggotaModal();
            });
        });

        // SweetAlert confirmation for delete
        document.querySelectorAll('.form-delete-anggota').forEach(function(form){
            form.addEventListener('submit', function(e){
                e.preventDefault();
                Swal.fire({
                    title: 'Hapus data?',
                    text: 'Tindakan ini tidak bisa dibatalkan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, hapus',
                    cancelButtonText: 'Batal'
                }).then(function(result){
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // SweetAlert notification on PHP message/error
        <?php if (!empty($message)): ?>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: <?php echo json_encode($message); ?>
        });
        <?php endif; ?>
        <?php if (!empty($error)): ?>
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: <?php echo json_encode($error); ?>
        });
        <?php endif; ?>
    </script>

    <!-- Modal Tambah Anggota -->
    <div id="anggotaModal" class="fixed inset-0 bg-black/40 hidden z-50 flex items-center justify-center p-2">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-3xl">
            <div class="card-header flex items-center justify-between">
                <h3 class="font-semibold text-amber-900">Tambah Anggota Baru</h3>
                <button class="text-gray-500 hover:text-gray-700" onclick="closeAnggotaModal()"><i class="fas fa-times"></i></button>
            </div>
            <form method="POST" class="p-3 md:p-5 grid grid-cols-1 md:grid-cols-2 gap-3 max-h-[75vh] overflow-y-auto">
                <input type="hidden" name="action" value="create_majelis_anggota">
                <div>
                    <label class="form-label">Nama Lengkap *</label>
                    <input type="text" name="nama_lengkap" class="form-input" required>
                </div>
                <div>
                    <label class="form-label">Nama Panggilan</label>
                    <input type="text" name="nama_panggilan" class="form-input">
                </div>
                <div>
                    <label class="form-label">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" class="form-input">
                </div>
                <div>
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="form-input">
                </div>
                <div>
                    <label class="form-label">Jenis Kelamin *</label>
                    <select name="jenis_kelamin" class="form-input" required>
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Status Pernikahan</label>
                    <select name="status_pernikahan" class="form-input">
                        <option value="belum_menikah">Belum Menikah</option>
                        <option value="menikah">Menikah</option>
                        <option value="cerai">Cerai</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-input" rows="3"></textarea>
                </div>
                <div>
                    <label class="form-label">No. Telepon</label>
                    <input type="tel" name="no_telepon" class="form-input">
                </div>
                <div>
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input">
                </div>
                <div>
                    <label class="form-label">Tanggal Bergabung</label>
                    <input type="date" name="tanggal_bergabung" class="form-input">
                </div>
                <div>
                    <label class="form-label">Status</label>
                    <select name="status_aktif" class="form-input">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>
                <div class="md:col-span-2 flex items-center justify-end gap-2 mt-1">
                    <button type="button" class="btn btn-secondary" onclick="closeAnggotaModal()">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i>Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Anggota -->
    <div id="editAnggotaModal" class="fixed inset-0 bg-black/40 hidden z-50 flex items-center justify-center p-2">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-3xl">
            <div class="card-header flex items-center justify-between">
                <h3 class="font-semibold text-amber-900">Edit Anggota</h3>
                <button class="text-gray-500 hover:text-gray-700" onclick="closeEditAnggotaModal()"><i class="fas fa-times"></i></button>
            </div>
            <form method="POST" class="p-3 md:p-5 grid grid-cols-1 md:grid-cols-2 gap-3 max-h-[75vh] overflow-y-auto">
                <input type="hidden" name="action" value="update_majelis_anggota">
                <input type="hidden" name="anggota_id" value="">
                <div>
                    <label class="form-label">Nama Lengkap *</label>
                    <input type="text" name="nama_lengkap" class="form-input" required>
                </div>
                <div>
                    <label class="form-label">Nama Panggilan</label>
                    <input type="text" name="nama_panggilan" class="form-input">
                </div>
                <div>
                    <label class="form-label">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" class="form-input">
                </div>
                <div>
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="form-input">
                </div>
                <div>
                    <label class="form-label">Jenis Kelamin *</label>
                    <select name="jenis_kelamin" class="form-input" required>
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Status Pernikahan</label>
                    <select name="status_pernikahan" class="form-input">
                        <option value="belum_menikah">Belum Menikah</option>
                        <option value="menikah">Menikah</option>
                        <option value="cerai">Cerai</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-input" rows="3"></textarea>
                </div>
                <div>
                    <label class="form-label">No. Telepon</label>
                    <input type="tel" name="no_telepon" class="form-input">
                </div>
                <div>
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input">
                </div>
                <div>
                    <label class="form-label">Tanggal Bergabung</label>
                    <input type="date" name="tanggal_bergabung" class="form-input">
                </div>
                <div>
                    <label class="form-label">Status</label>
                    <select name="status_aktif" class="form-input">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>
                <div class="md:col-span-2 flex items-center justify-end gap-2 mt-1">
                    <button type="button" class="btn btn-secondary" onclick="closeEditAnggotaModal()">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i>Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
<?php require_once __DIR__ . '/partials/footer.php'; ?>
