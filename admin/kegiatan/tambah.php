<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../partials/header.php';

if (!isAdminLoggedIn()) { redirect('../login.php'); }

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama_kegiatan'] ?? '');
    $jenis = trim($_POST['jenis_kegiatan'] ?? '');
    $tanggal_mulai = trim($_POST['tanggal_mulai'] ?? '');
    $tanggal_selesai = trim($_POST['tanggal_selesai'] ?? '');
    $tempat = trim($_POST['tempat'] ?? '');
    $status = trim($_POST['status'] ?? 'direncanakan');

    if ($nama === '' || $jenis === '') { $error = 'Nama dan Jenis wajib diisi.'; }
    else {
        try {
            $db = new Database();
            $db->query("INSERT INTO kegiatan_kerohanian (nama_kegiatan, jenis_kegiatan, tanggal_mulai, tanggal_selesai, tempat, status, created_at, updated_at)
                        VALUES (:nama, :jenis, :mulai, :selesai, :tempat, :status, NOW(), NOW())");
            $db->bind(':nama', $nama);
            $db->bind(':jenis', $jenis);
            $db->bind(':mulai', $tanggal_mulai !== '' ? $tanggal_mulai : null);
            $db->bind(':selesai', $tanggal_selesai !== '' ? $tanggal_selesai : null);
            $db->bind(':tempat', $tempat !== '' ? $tempat : null);
            $db->bind(':status', $status);
            $db->execute();
            header('Location: ' . rtrim(APP_URL,'/') . '/admin/kegiatan/?success=1');
            exit;
        } catch (Exception $e) { $error = 'Gagal menyimpan: ' . $e->getMessage(); }
    }
}
?>
    <div class="max-w-3xl mx-auto px-4 py-8">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-800">Tambah Kegiatan</h1>
            <a href="<?php echo rtrim(APP_URL,'/'); ?>/admin/kegiatan/" class="btn-secondary">Kembali</a>
        </div>

        <div class="bg-white rounded-xl shadow p-6">
            <?php if ($error): ?>
                <div class="alert-error mb-4"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="post" class="space-y-6">
                <div>
                    <label class="form-label">Nama Kegiatan</label>
                    <input name="nama_kegiatan" class="form-input" placeholder="Misal: Retreat Pemuda" required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Jenis</label>
                        <select name="jenis_kegiatan" class="form-input" required>
                            <option value="">- Pilih -</option>
                            <option value="pelatihan">Pelatihan</option>
                            <option value="retreat">Retreat</option>
                            <option value="seminar">Seminar</option>
                            <option value="workshop">Workshop</option>
                            <option value="ibadah_khusus">Ibadah Khusus</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Tempat</label>
                        <input name="tempat" class="form-input" placeholder="Misal: Aula Serbaguna">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" class="form-input">
                    </div>
                </div>

                <div>
                    <label class="form-label">Status</label>
                    <select name="status" class="form-input">
                        <option value="direncanakan">Direncanakan</option>
                        <option value="pendaftaran">Pendaftaran</option>
                        <option value="berlangsung">Berlangsung</option>
                        <option value="selesai">Selesai</option>
                        <option value="dibatalkan">Dibatalkan</option>
                    </select>
                </div>

                <div class="flex items-center justify-end gap-3">
                    <a href="<?php echo rtrim(APP_URL,'/'); ?>/admin/kegiatan/" class="btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>


