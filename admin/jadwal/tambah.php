<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../partials/header.php';

if (!isAdminLoggedIn()) { redirect('../login.php'); }

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = trim($_POST['judul'] ?? '');
    $tanggal = trim($_POST['tanggal'] ?? '');
    $waktu_mulai = trim($_POST['waktu_mulai'] ?? '');
    $waktu_selesai = trim($_POST['waktu_selesai'] ?? '');
    $jenis = trim($_POST['jenis_ibadah'] ?? '');
    $tempat = trim($_POST['tempat'] ?? '');
    $pemimpin = trim($_POST['pemimpin_ibadah'] ?? '');

    if ($judul === '' || $waktu_mulai === '' || $jenis === '') {
        $error = 'Judul, waktu mulai, dan jenis wajib diisi.';
    } else {
        try {
            $db = new Database();
            $db->query("INSERT INTO jadwal_ibadah (judul, deskripsi, tanggal, waktu_mulai, waktu_selesai, jenis_ibadah, tempat, pemimpin_ibadah, status, created_at, updated_at)
                        VALUES (:judul, NULL, :tanggal, :waktu_mulai, :waktu_selesai, :jenis, :tempat, :pemimpin, 'akan_datang', NOW(), NOW())");
            $db->bind(':judul', $judul);
            $db->bind(':tanggal', $tanggal !== '' ? $tanggal : '0000-00-00');
            $db->bind(':waktu_mulai', $waktu_mulai);
            $db->bind(':waktu_selesai', $waktu_selesai !== '' ? $waktu_selesai : null);
            $db->bind(':jenis', $jenis);
            $db->bind(':tempat', $tempat !== '' ? $tempat : null);
            $db->bind(':pemimpin', $pemimpin !== '' ? $pemimpin : null);
            $db->execute();
            header('Location: ' . rtrim(APP_URL,'/') . '/admin/jadwal/?success=1');
            exit;
        } catch (Exception $e) {
            $error = 'Gagal menyimpan: ' . $e->getMessage();
        }
    }
}
?>
    <div class="max-w-3xl mx-auto px-4 py-8">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-800">Tambah Jadwal Ibadah</h1>
            <a href="<?php echo rtrim(APP_URL,'/'); ?>/admin/jadwal/" class="btn-secondary">Kembali</a>
        </div>

        <div class="bg-white rounded-xl shadow p-6">
            <?php if ($error): ?>
                <div class="alert-error mb-4"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="post" class="space-y-6">
                <div>
                    <label class="form-label">Judul</label>
                    <input name="judul" class="form-input" placeholder="Misal: Ibadah Minggu Pagi" required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" class="form-input">
                        <p class="text-xs text-gray-500 mt-1">Kosongkan untuk Rutin Mingguan</p>
                    </div>
                    <div>
                        <label class="form-label">Waktu Mulai</label>
                        <input type="time" name="waktu_mulai" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Waktu Selesai</label>
                        <input type="time" name="waktu_selesai" class="form-input">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Jenis Ibadah</label>
                        <select name="jenis_ibadah" class="form-input" required>
                            <option value="">- Pilih -</option>
                            <option value="ibadah_minggu">Ibadah Minggu</option>
                            <option value="ibadah_doa">Ibadah Doa</option>
                            <option value="ibadah_pemuda">Ibadah Pemuda</option>
                            <option value="ibadah_anak">Ibadah Anak</option>
                            <option value="ibadah_khusus">Ibadah Khusus</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Tempat</label>
                        <input name="tempat" class="form-input" placeholder="Misal: Gedung Gereja Utama">
                    </div>
                </div>

                <div>
                    <label class="form-label">Pemimpin Ibadah</label>
                    <input name="pemimpin_ibadah" class="form-input" placeholder="Misal: Pdt. Daniel">
                </div>

                <div class="flex items-center justify-end gap-3">
                    <a href="<?php echo rtrim(APP_URL,'/'); ?>/admin/jadwal/" class="btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>


