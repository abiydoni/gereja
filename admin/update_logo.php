<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';

// Cek apakah admin sudah login
if (!isAdminLoggedIn()) { redirect('login.php'); }

$baseAdminUrl = rtrim(APP_URL,'/') . '/admin/';
$pageTitle = 'Update Logo';
require_once __DIR__ . '/partials/header.php';
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
                    // Simpan ke pengaturan_sistem (nama_pengaturan = 'logo_gereja')
                    // Upsert kompatibel dengan struktur tabel saat ini (primary key pada id)
                    // 1) Coba UPDATE berdasarkan nama_pengaturan
                    $db->query("UPDATE pengaturan_sistem SET nilai = ?, updated_at = NOW() WHERE nama_pengaturan = 'logo_gereja'", [$new_filename]);
                    $updatedRows = $db->execute();
                    if ($updatedRows === 0) {
                        // 2) Jika belum ada, lakukan INSERT lengkap
                        $db->query(
                            "INSERT INTO pengaturan_sistem (nama_pengaturan, nilai, deskripsi, kategori, updated_at) VALUES (?, ?, ?, ?, NOW())",
                            ['logo_gereja', $new_filename, 'File logo gereja', 'tampilan']
                        );
                        $db->execute();
                    }
                    $message = 'Logo berhasil diupdate!';
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
    $db->query("SELECT nilai FROM pengaturan_sistem WHERE nama_pengaturan = 'logo_gereja' LIMIT 1");
    $result = $db->single();
    if ($result) {
        $current_logo = is_array($result) ? ($result['nilai'] ?? '') : ($result->nilai ?? '');
    }
} catch (Exception $e) {
    $error = 'Error mengambil data logo: ' . $e->getMessage();
}
?>

    <div class="max-w-6xl mx-auto px-4 py-8">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-800">Perbarui Logo Gereja</h1>
            <a href="<?php echo $baseAdminUrl; ?>dashboard.php" class="btn-secondary">Kembali</a>
        </div>

        <?php if ($message): ?>
            <div class="alert-success mb-4"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert-error mb-4"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Panel Preview Logo Saat Ini -->
            <section class="card">
                <div class="card-header">
                    <h2 class="font-semibold text-amber-900">Logo Saat Ini</h2>
                </div>
                <div class="p-4 flex flex-col items-center text-center">
                    <div class="w-45 h-45 rounded-lg border border-gray-200 bg-white flex items-center justify-center overflow-hidden mb-3">
                        <?php if ($current_logo): ?>
                            <img src="<?php echo htmlspecialchars(getLogoPath()); ?>" alt="Current Logo" class="max-w-full max-h-full object-contain">
                        <?php else: ?>
                            <i class="fas fa-church text-4xl text-amber-600"></i>
                        <?php endif; ?>
                    </div>
                    <p class="text-sm text-gray-600">Gunakan gambar latar transparan (PNG) agar tampak bagus di tema terang.</p>
                    <?php if ($current_logo): ?><p class="text-xs text-gray-500 mt-1"><?php echo htmlspecialchars($current_logo); ?></p><?php endif; ?>
                </div>
            </section>

            <!-- Panel Upload / Drag & Drop -->
            <section class="card">
                <div class="card-header">
                    <h2 class="font-semibold text-amber-900">Unggah Logo Baru</h2>
                </div>
                <form method="POST" enctype="multipart/form-data" class="p-4 space-y-4" id="logoForm">
                    <div id="dropZone" class="border-2 border-dashed border-amber-300 rounded-lg bg-amber-50 hover:bg-amber-100 transition-colors p-6 flex flex-col items-center justify-center cursor-pointer">
                        <i class="fas fa-upload text-2xl text-amber-600 mb-2"></i>
                        <p class="text-sm text-amber-800 font-medium">Tarik & letakkan file di sini, atau klik untuk memilih</p>
                        <p class="text-xs text-gray-600">PNG (disarankan), JPG, JPEG • Maks 2MB</p>
                        <input type="file" id="logo" name="logo" accept="image/png,image/jpeg,image/jpg" class="hidden">
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex-1">
                            <label class="form-label">Pratinjau</label>
                            <div class="w-full h-20 border border-gray-200 rounded-lg bg-white flex items-center justify-center overflow-hidden" id="previewBox">
                                <span class="text-gray-400 text-sm" id="previewHint">Belum ada file dipilih</span>
                                <img id="previewImage" alt="Preview" class="hidden max-h-full object-contain" />
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-end gap-3">
                        <button type="button" id="btnReset" class="btn-secondary">Reset</button>
                        <button type="submit" class="btn btn-primary" id="btnSubmit" disabled>
                            <i class="fas fa-save mr-2"></i>Upload Logo
                        </button>
                    </div>
                </form>
            </section>
        </div>
    </div>

    <script>
    (function(){
        const input = document.getElementById('logo');
        const drop = document.getElementById('dropZone');
        const previewImg = document.getElementById('previewImage');
        const previewHint = document.getElementById('previewHint');
        const btnSubmit = document.getElementById('btnSubmit');
        const btnReset = document.getElementById('btnReset');
        const form = document.getElementById('logoForm');

        function setPreview(file){
            if(!file) return;
            const allowed = ['image/png','image/jpeg','image/jpg'];
            if(!allowed.includes(file.type)){
                alert('Tipe file tidak didukung. Gunakan PNG, JPG, atau JPEG.');
                input.value='';
                btnSubmit.disabled = true;
                previewImg.classList.add('hidden');
                previewHint.classList.remove('hidden');
                previewHint.textContent = 'Belum ada file dipilih';
                return;
            }
            const reader = new FileReader();
            reader.onload = e => {
                previewImg.src = e.target.result;
                previewImg.classList.remove('hidden');
                previewHint.classList.add('hidden');
                btnSubmit.disabled = false;
            };
            reader.readAsDataURL(file);
        }

        drop.addEventListener('click', ()=> input.click());
        input.addEventListener('change', e => setPreview(e.target.files[0]));

        drop.addEventListener('dragover', e => { e.preventDefault(); drop.classList.add('bg-amber-100'); });
        drop.addEventListener('dragleave', ()=> drop.classList.remove('bg-amber-100'));
        drop.addEventListener('drop', e => {
            e.preventDefault();
            drop.classList.remove('bg-amber-100');
            const file = e.dataTransfer.files[0];
            input.files = e.dataTransfer.files;
            setPreview(file);
        });

        btnReset.addEventListener('click', ()=>{
            input.value='';
            previewImg.classList.add('hidden');
            previewHint.classList.remove('hidden');
            previewHint.textContent = 'Belum ada file dipilih';
            btnSubmit.disabled = true;
        });

        form.addEventListener('submit', ()=>{ btnSubmit.disabled = true; });
    })();
    </script>
<?php require_once __DIR__ . '/partials/footer.php'; ?>
