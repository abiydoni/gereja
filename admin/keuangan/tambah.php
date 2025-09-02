<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../partials/header.php';

if (!isAdminLoggedIn()) { redirect('../login.php'); }

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = trim($_POST['id'] ?? '');
    $tanggal = trim($_POST['tanggal'] ?? '');
    $jenis = trim($_POST['jenis'] ?? '');
    $kategori = trim($_POST['kategori'] ?? '');
    $jumlah = trim($_POST['jumlah'] ?? '');
    $metode = trim($_POST['metode_pembayaran'] ?? 'tunai');
    $status = trim($_POST['status'] ?? 'diterima');
    $deskripsi = trim($_POST['deskripsi'] ?? '');

    if ($id === '' || $tanggal === '' || $jenis === '' || $kategori === '' || $jumlah === '') {
        $error = 'ID, Tanggal, Jenis, Kategori, dan Jumlah wajib diisi.';
    } else {
        try {
            $db = new Database();
            $db->query("INSERT INTO keuangan (id, tanggal, jenis, kategori, jumlah, metode_pembayaran, status, deskripsi, created_at, updated_at)
                        VALUES (:id, :tanggal, :jenis, :kategori, :jumlah, :metode, :status, :deskripsi, NOW(), NOW())");
            $db->bind(':id', $id);
            $db->bind(':tanggal', $tanggal);
            $db->bind(':jenis', $jenis);
            $db->bind(':kategori', $kategori);
            $db->bind(':jumlah', $jumlah);
            $db->bind(':metode', $metode);
            $db->bind(':status', $status);
            $db->bind(':deskripsi', $deskripsi !== '' ? $deskripsi : null);
            $db->execute();
            header('Location: ' . rtrim(APP_URL,'/') . '/admin/keuangan/?success=1');
            exit;
        } catch (Exception $e) { $error = 'Gagal menyimpan: ' . $e->getMessage(); }
    }
}
?>
    <div class="max-w-3xl mx-auto px-4 py-8">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-800">Tambah Transaksi Keuangan</h1>
            <a href="<?php echo rtrim(APP_URL,'/'); ?>/admin/keuangan/" class="btn-secondary">Kembali</a>
        </div>

        <div class="bg-white rounded-xl shadow p-6">
            <?php if ($error): ?>
                <div class="alert-error mb-4"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="post" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="form-label">ID</label>
                        <input name="id" class="form-input" placeholder="Misal: TRX2025-001" required>
                    </div>
                    <div>
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Jenis</label>
                        <select name="jenis" class="form-input" required>
                            <option value="">- Pilih -</option>
                            <option value="pemasukan">Pemasukan</option>
                            <option value="pengeluaran">Pengeluaran</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Kategori</label>
                        <input name="kategori" class="form-input" placeholder="Misal: Persembahan" required>
                    </div>
                    <div>
                        <label class="form-label">Jumlah</label>
                        <input type="number" step="0.01" name="jumlah" class="form-input" placeholder="0" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Metode Pembayaran</label>
                        <select name="metode_pembayaran" class="form-input">
                            <option value="tunai">Tunai</option>
                            <option value="transfer">Transfer</option>
                            <option value="cek">Cek</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Status</label>
                        <select name="status" class="form-input">
                            <option value="diterima">Diterima</option>
                            <option value="pending">Pending</option>
                            <option value="ditolak">Ditolak</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" class="form-input"></textarea>
                </div>

                <div class="flex items-center justify-end gap-3">
                    <a href="<?php echo rtrim(APP_URL,'/'); ?>/admin/keuangan/" class="btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>


