<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/database.php';
require_once __DIR__ . '/../../includes/functions.php';
$pageTitle = 'Kelola Warta';
require_once __DIR__ . '/../partials/header.php';

// Cek login admin
if (!isAdminLoggedIn()) {
    redirect('../login.php');
}

$message = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomor = isset($_POST['nomor']) ? trim($_POST['nomor']) : null;
    $tanggal = isset($_POST['tanggal']) ? trim($_POST['tanggal']) : date('Y-m-d');
    $ucapan = isset($_POST['ucapan']) ? trim($_POST['ucapan']) : '';


    try {
        $db = new Database();

        // Simpan warta (upsert berdasarkan tanggal)
        try {
            $db->query("INSERT INTO warta (tanggal, nomor) VALUES (:tanggal, :nomor)
                        ON DUPLICATE KEY UPDATE nomor = VALUES(nomor)");
            $db->bind(':tanggal', $tanggal);
            $db->bind(':nomor', $nomor);
            $db->execute();
        } catch (Exception $e) {}



        // Simpan ucapan (gunakan tabel ucapan_selamat)
        try {
            $db->query("INSERT INTO ucapan_selamat (konten, updated_at) VALUES (:konten, NOW())");
            $db->bind(':konten', $ucapan);
            $db->execute();
        } catch (Exception $e) {}

        $message = 'Berhasil disimpan.';
    } catch (Exception $e) {
        $message = 'Gagal menyimpan.';
    }
}

// Muat data terakhir
$nomor = '';
$tanggal = date('Y-m-d');
$ucapan = '';



try {
    $db = new Database();
    $db->query("SELECT nomor, tanggal FROM warta ORDER BY tanggal DESC LIMIT 1");
    $w = $db->single();
    if ($w) { $nomor = $w->nomor ?: ''; $tanggal = $w->tanggal ?: $tanggal; }

    $db->query("SELECT konten FROM ucapan_selamat ORDER BY updated_at DESC LIMIT 1");
    $u = $db->single();
    if ($u) { $ucapan = $u->konten; }
} catch (Exception $e) {}
?>

    <?php
    $listWarta = [];
    try {
        $dbList = new Database();
        $dbList->query("SELECT id, judul, kategori, status, tanggal_publish, views FROM warta ORDER BY COALESCE(tanggal_publish, created_at) DESC, id DESC LIMIT 200");
        $listWarta = $dbList->resultSet();
    } catch (Exception $e) { $listWarta = []; }
    ?>
    <div class="max-w-full mx-auto px-4 py-8 space-y-6">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-800">Kelola Warta</h1>
                <a href="<?php echo rtrim(APP_URL,'/'); ?>/admin/warta/tambah.php" class="btn-secondary">Tulis Warta</a>
            </div>
            <section class="bg-white rounded-xl shadow p-6">
                <div class="flex items-center justify-center mb-4">
                    <img src="../../assets/images/kop.png" alt="Kop Warta" class="w-full object-contain">
                </div>

                <?php if ($message): ?>
                    <div class="mb-4 p-3 rounded border <?php echo ($message==='Berhasil disimpan.') ? 'border-emerald-200 bg-emerald-50 text-emerald-800' : 'border-red-200 bg-red-50 text-red-700'; ?>">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>

                <form method="post" class="space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Warta</label>
                            <input type="text" name="nomor" value="<?php echo htmlspecialchars($nomor); ?>" class="w-full rounded-lg border-gray-300 focus:ring-amber-600 focus:border-amber-600" placeholder="Misal: 05/2025">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                            <input type="date" name="tanggal" value="<?php echo htmlspecialchars($tanggal); ?>" class="w-full rounded-lg border-gray-300 focus:ring-amber-600 focus:border-amber-600">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ucapan Selamat Datang</label>
                        <textarea id="ucapan" name="ucapan" class="w-full rounded-lg border-gray-300"><?php echo htmlspecialchars($ucapan); ?></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Renungan Minggu Ini</label>
                        <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                            <?php
                            try {
                                $dbRenungan = new Database();
                                
                                // Tampilkan renungan terbaru sebagai default
                                $dbRenungan->query("SELECT id, judul, tanggal_publish, konten FROM renungan WHERE status = 'published' ORDER BY tanggal_publish DESC LIMIT 1");
                                $renunganList = $dbRenungan->resultSet();
                                
                                if (!empty($renunganList)) {
                                    $renungan = $renunganList[0];
                                    $tanggalRenungan = $renungan['tanggal_publish'] ? date('d/m/Y', strtotime($renungan['tanggal_publish'])) : 'Belum dipublish';
                                    echo '<div class="space-y-2">';
                                    echo '<div class="flex items-center justify-between">';
                                    echo '<h4 class="font-medium text-gray-900">' . htmlspecialchars($renungan['judul']) . '</h4>';
                                    echo '<span class="text-sm text-gray-500">' . $tanggalRenungan . '</span>';
                                    echo '</div>';
                                    echo '<p class="text-sm text-gray-600 line-clamp-2">' . htmlspecialchars(substr(strip_tags($renungan['konten']), 0, 150)) . '...</p>';
                                    echo '</div>';
                                } else {
                                    echo '<p class="text-gray-500 text-sm">Belum ada renungan yang dipublish</p>';
                                }
                            } catch (Exception $e) {
                                echo '<p class="text-red-500 text-sm">Error loading renungan</p>';
                            }
                            ?>
                        </div>
                    </div>


                    <div class="flex items-center justify-end gap-3">
                        <a href="../../pages/warta.php" target="_blank" class="px-4 py-2 rounded-lg bg-amber-100 text-amber-800 hover:bg-amber-200">Lihat Halaman Warta</a>
                        <button type="submit" class="px-5 py-2 rounded-lg bg-amber-600 text-white hover:bg-amber-700">Simpan</button>
                    </div>
                </form>
            </section>

            <script>
            // Auto-refresh renungan ketika tanggal berubah
            document.addEventListener('DOMContentLoaded', function() {
                const tanggalInput = document.querySelector('input[name="tanggal"]');
                const renunganSection = document.querySelector('.bg-gray-50.rounded-lg.border.border-gray-200.p-4');
                
                tanggalInput.addEventListener('change', function() {
                    const selectedDate = this.value;
                    if (selectedDate) {
                        // Tampilkan loading state
                        renunganSection.innerHTML = '<p class="text-gray-500 text-sm">🔄 Mencari renungan...</p>';
                        
                        // Kirim request AJAX untuk filter renungan
                        fetch('filter_renungan.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: 'tanggal=' + encodeURIComponent(selectedDate)
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                // Update tampilan renungan
                                renunganSection.innerHTML = data.html;
                            } else {
                                // Tampilkan pesan tidak ditemukan
                                renunganSection.innerHTML = '<p class="text-amber-600 text-sm">' + data.message + '</p>';
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            renunganSection.innerHTML = '<p class="text-red-500 text-sm">❌ Error loading renungan. Silakan coba lagi.</p>';
                        });
                    }
                });
            });
            </script>
                </form>
            </section>

            <div class="bg-white rounded-xl shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-amber-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-amber-900 uppercase tracking-wider">Judul</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-amber-900 uppercase tracking-wider">Kategori</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-amber-900 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-amber-900 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-amber-900 uppercase tracking-wider">Views</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-amber-900 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (empty($listWarta)): ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-6 text-center text-gray-500">Belum ada warta</td>
                                </tr>
                            <?php else: foreach ($listWarta as $w): ?>
                                <tr class="hover:bg-amber-50">
                                    <td class="px-6 py-3 text-sm font-medium text-gray-900"><?php echo htmlspecialchars($w->judul); ?></td>
                                    <td class="px-6 py-3 text-sm text-gray-700"><?php echo htmlspecialchars($w->kategori); ?></td>
                                    <td class="px-6 py-3 text-sm text-gray-700"><?php echo ucfirst($w->status); ?></td>
                                    <td class="px-6 py-3 text-sm text-gray-700"><?php echo $w->tanggal_publish ? formatTanggalIndonesia($w->tanggal_publish) : '-'; ?></td>
                                    <td class="px-6 py-3 text-sm text-gray-700"><?php echo (int)$w->views; ?></td>
                                    <td class="px-6 py-3 text-sm text-gray-700">
                                        <a href="<?php echo rtrim(APP_URL,'/'); ?>/admin/warta/edit.php?id=<?php echo (int)$w->id; ?>" class="text-amber-700 hover:underline mr-3">Edit</a>
                                        <a href="<?php echo rtrim(APP_URL,'/'); ?>/admin/warta/hapus.php?id=<?php echo (int)$w->id; ?>" class="text-red-600 hover:underline" onclick="return confirm('Hapus warta ini?');">Hapus</a>
                                    </td>
                                </tr>
                            <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
    </div>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>


