<?= $this->extend('layouts/backend') ?>

<?= $this->section('styles') ?>
<!-- Add specific styles if needed -->
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="max-w-5xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 font-heading">Edit Jadwal Ibadah</h1>
            <p class="text-slate-500 text-sm mt-1">Perbarui jadwal dan petugas pelayanan.</p>
        </div>
        <a href="<?= base_url('dashboard/jadwal_pelayanan') ?>" class="px-4 py-2 bg-white text-slate-600 font-bold rounded-xl border border-slate-200 shadow-sm hover:bg-slate-50 transition flex items-center gap-2">
            <ion-icon name="arrow-back-outline"></ion-icon>
            <span>Kembali</span>
        </a>
    </div>

    <?php if(session()->getFlashdata('error')): ?>
    <div class="p-4 mb-6 rounded-xl bg-rose-50 border border-rose-100 text-rose-600 flex items-center gap-3 shadow-sm">
        <ion-icon name="alert-circle" class="text-xl"></ion-icon>
        <span class="font-bold"><?= session()->getFlashdata('error') ?></span>
    </div>
    <?php endif; ?>

    <form action="<?= base_url('dashboard/jadwal_pelayanan/update/'.$id) ?>" method="post">
        <?= csrf_field() ?>
        
        <!-- Global Header Section -->
        <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 p-4 md:p-6 mb-6">
            <h2 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-3 mb-5 flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs font-bold">1</div>
                Pengaturan Umum (Satu Hari)
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="space-y-1">
                    <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider">Tanggal <span class="text-rose-500">*</span></label>
                    <input type="date" name="tanggal" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all text-sm font-medium text-slate-700" required value="<?= $tanggal ?>">
                    <p class="text-[10px] text-slate-400 mt-1">Mengubah tanggal akan memindahkan semua jadwal ini.</p>
                </div>
                <div class="space-y-1">
                    <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider">Tema Ibadah (Opsional)</label>
                    <input type="text" name="tema" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all text-sm font-medium placeholder-slate-400" placeholder="Contoh: Hidup yang Berbuah" value="<?= $tema ?>">
                </div>
                <div class="space-y-1">
                    <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider">Status</label>
                    <div class="relative">
                        <select name="status" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all appearance-none text-sm font-medium text-slate-600">
                            <option value="aktif" <?= $status == 'aktif' ? 'selected' : '' ?>>Aktif (Akan Datang)</option>
                            <option value="selesai" <?= $status == 'selesai' ? 'selected' : '' ?>>Selesai</option>
                            <option value="dibatalkan" <?= $status == 'dibatalkan' ? 'selected' : '' ?>>Dibatalkan</option>
                        </select>
                        <ion-icon name="chevron-down-outline" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></ion-icon>
                    </div>
                </div>
            </div>
        </div>

        <!-- Matrix Input Section -->
        <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 p-4 md:p-6 mb-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b border-slate-100 pb-4 mb-6 gap-4">
                <h2 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-violet-50 text-violet-600 flex items-center justify-center text-xs font-bold">2</div>
                    Jadwal Petugas (Matriks 3 Sesi)
                </h2>
                <button type="button" id="add-row" class="px-4 py-2 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 font-bold rounded-xl transition-colors text-xs flex items-center gap-2">
                    <ion-icon name="add-circle" class="text-base"></ion-icon> Tambah Baris Tugas
                </button>
            </div>

            <!-- Session Configuration & Matrix Header -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-4 px-2 bg-slate-50 p-3 rounded-xl border border-slate-200">
                <div class="col-span-3 flex items-center">
                    <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Konfigurasi Sesi</span>
                </div>
                
                <!-- Sesi 1 Config -->
                <div class="col-span-3 flex flex-col gap-1">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="sess_pagi_active" id="check_pagi" value="1" <?= $sessionsData['pagi'] ? 'checked' : '' ?> class="w-4 h-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500 session-toggle" data-target="pagi">
                        <label for="check_pagi" class="text-[10px] font-bold text-slate-600 uppercase">Sesi 1 (Pagi)</label>
                    </div>
                    <?php 
                        $timePagi = $sessionsData['pagi'] ? date('H:i', strtotime($sessionsData['pagi']['jam'])) : '06:00';
                    ?>
                    <input type="time" name="sess_pagi_time" value="<?= $timePagi ?>" class="w-full px-2 py-1 text-xs border border-slate-300 rounded focus:ring-blue-500 focus:border-blue-500 text-slate-600">
                </div>

                <!-- Sesi 2 Config -->
                <div class="col-span-3 flex flex-col gap-1">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="sess_siang_active" id="check_siang" value="1" <?= $sessionsData['siang'] ? 'checked' : '' ?> class="w-4 h-4 text-orange-600 rounded border-slate-300 focus:ring-orange-500 session-toggle" data-target="siang">
                        <label for="check_siang" class="text-[10px] font-bold text-slate-600 uppercase">Sesi 2 (Siang)</label>
                    </div>
                    <?php 
                        $timeSiang = $sessionsData['siang'] ? date('H:i', strtotime($sessionsData['siang']['jam'])) : '09:00';
                    ?>
                    <input type="time" name="sess_siang_time" value="<?= $timeSiang ?>" class="w-full px-2 py-1 text-xs border border-slate-300 rounded focus:ring-orange-500 focus:border-orange-500 text-slate-600">
                </div>

                <!-- Sesi 3 Config -->
                <div class="col-span-3 flex flex-col gap-1">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="sess_sore_active" id="check_sore" value="1" <?= $sessionsData['sore'] ? 'checked' : '' ?> class="w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500 session-toggle" data-target="sore">
                        <label for="check_sore" class="text-[10px] font-bold text-slate-600 uppercase">Sesi 3 (Sore)</label>
                    </div>
                    <?php 
                        $timeSore = $sessionsData['sore'] ? date('H:i', strtotime($sessionsData['sore']['jam'])) : '17:00';
                    ?>
                    <input type="time" name="sess_sore_time" value="<?= $timeSore ?>" class="w-full px-2 py-1 text-xs border border-slate-300 rounded focus:ring-indigo-500 focus:border-indigo-500 text-slate-600">
                </div>
            </div>

            <!-- Column Labels for Desktop -->
            <div class="hidden md:grid grid-cols-12 gap-4 mb-2 px-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                <div class="col-span-3">Jenis Tugas</div>
                <div class="col-span-3 text-center bg-blue-50/50 p-1 rounded-t-lg text-blue-600">Petugas Sesi 1</div>
                <div class="col-span-3 text-center bg-orange-50/50 p-1 rounded-t-lg text-orange-600">Petugas Sesi 2</div>
                <div class="col-span-3 text-center bg-indigo-50/50 p-1 rounded-t-lg text-indigo-600">Petugas Sesi 3</div>
            </div>

            <div id="petugas-container" class="space-y-4">
                <!-- Rows generated by JS -->
            </div>
        </div>

        <!-- Submit Action -->
        <div class="flex justify-end pt-4">
            <button type="submit" class="w-full md:w-auto px-8 py-3.5 bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-bold rounded-xl shadow-lg shadow-indigo-200 hover:shadow-indigo-300 hover:-translate-y-1 transition-all flex items-center justify-center gap-2">
                <ion-icon name="save-outline" class="text-xl"></ion-icon>
                <span>SIMPAN PERUBAHAN</span>
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('petugas-container');
        const addButton = document.getElementById('add-row');

        // Session Toggles
        const toggles = document.querySelectorAll('.session-toggle');
        
        function updateSessionVisibility() {
            toggles.forEach(toggle => {
                const target = toggle.dataset.target; // pagi, siang, sore
                const isChecked = toggle.checked;
                
                // Toggle inputs
                document.querySelectorAll(`.input-${target}`).forEach(input => {
                    input.disabled = !isChecked;
                    if(!isChecked) {
                        input.classList.add('bg-slate-100', 'text-slate-400');
                        input.classList.remove('bg-white');
                    } else {
                        input.classList.remove('bg-slate-100', 'text-slate-400');
                        input.classList.add('bg-white');
                    }
                });
            });
        }

        toggles.forEach(t => t.addEventListener('change', updateSessionVisibility));

        // Data from Controller
        const sessionsData = <?= json_encode($sessionsData) ?>;
        const existingRoles = <?= json_encode($existingRoles) ?>;

        // Helper to find person name for a role in a session
        function findPerson(sessionName, roleName) {
            const session = sessionsData[sessionName]; // 'pagi', 'siang', 'sore'
            if (!session || !session.details) return '';
            
            const detail = session.details.find(d => d.jenis_tugas === roleName);
            return detail ? detail.nama_petugas : '';
        }

        // Function to create a row
        function createRow(roleValue = '', pagiVal = '', siangVal = '', soreVal = '') {
            const row = document.createElement('div');
            row.className = 'petugas-row relative bg-white border-b border-slate-100 pb-4 mb-4 md:mb-0 md:pb-2 md:border-b-0 animate-fade-in group';
            
            row.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-start">
                    <!-- Jenis Tugas Column -->
                    <div class="col-span-1 md:col-span-3">
                        <label class="md:hidden block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Jenis Tugas</label>
                        <div class="flex gap-2">
                            <button type="button" class="remove-row w-8 h-[38px] rounded-lg bg-rose-50 text-rose-500 hover:bg-rose-100 flex items-center justify-center transition-colors shrink-0" title="Hapus Baris">
                                <ion-icon name="trash-outline"></ion-icon>
                            </button>
                            <input type="text" name="jenis_tugas[]" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-slate-200 outline-none transition-all text-xs font-bold text-slate-700" placeholder="Nama Tugas (e.g. Pengkotbah)" value="${roleValue}">
                        </div>
                    </div>

                    <!-- Pagi Input -->
                    <div class="col-span-1 md:col-span-3">
                        <label class="md:hidden block text-[10px] font-bold text-blue-400 uppercase tracking-wider mb-1">Pagi</label>
                        <textarea name="petugas_pagi[]" rows="3" class="input-pagi w-full px-3 py-2 bg-white border border-blue-100 rounded-lg focus:ring-2 focus:ring-blue-100 outline-none transition-all text-xs min-h-[38px] placeholder-slate-300" placeholder="-">${pagiVal}</textarea>
                    </div>

                    <!-- Siang Input -->
                    <div class="col-span-1 md:col-span-3">
                        <label class="md:hidden block text-[10px] font-bold text-orange-400 uppercase tracking-wider mb-1">Siang</label>
                        <textarea name="petugas_siang[]" rows="3" class="input-siang w-full px-3 py-2 bg-white border border-orange-100 rounded-lg focus:ring-2 focus:ring-orange-100 outline-none transition-all text-xs min-h-[38px] placeholder-slate-300" placeholder="-">${siangVal}</textarea>
                    </div>
                    
                    <!-- Sore Input -->
                    <div class="col-span-1 md:col-span-3">
                        <label class="md:hidden block text-[10px] font-bold text-indigo-400 uppercase tracking-wider mb-1">Sore</label>
                        <textarea name="petugas_sore[]" rows="3" class="input-sore w-full px-3 py-2 bg-white border border-indigo-100 rounded-lg focus:ring-2 focus:ring-indigo-100 outline-none transition-all text-xs min-h-[38px] placeholder-slate-300" placeholder="-">${soreVal}</textarea>
                    </div>
                </div>
            `;
            container.appendChild(row);
            updateSessionVisibility(); // Apply state immediately
        }

        // Initialize with existing roles or defaults
        if (existingRoles && existingRoles.length > 0) {
            existingRoles.forEach(role => {
                const pagi = findPerson('pagi', role);
                const siang = findPerson('siang', role);
                const sore = findPerson('sore', role);
                createRow(role, pagi, siang, sore);
            });
        } else {
            // Default suggestions if no data exists
            const defaultRoles = ['Pengkotbah', 'Liturgos', 'Lector', 'Persembahan', 'Pemusik', 'Singer', 'Penyambut'];
            defaultRoles.forEach(role => createRow(role));
        }

        // Add Row Button
        addButton.addEventListener('click', () => createRow());

        // Remove Row Layout
        container.addEventListener('click', function(e) {
            if (e.target.closest('.remove-row')) {
                const row = e.target.closest('.petugas-row');
                row.remove();
            }
        });
    });
</script>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fade-in 0.3s ease-out forwards;
    }
</style>

<?= $this->endSection() ?>
