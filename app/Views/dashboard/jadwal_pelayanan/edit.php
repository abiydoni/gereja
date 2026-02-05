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

    <form action="<?= base_url('dashboard/jadwal_pelayanan/update/'.$jadwal['id_jadwal_utama']) ?>" method="post">
        <?= csrf_field() ?>
        
        <!-- Header Section -->
        <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 p-4 md:p-5 mb-4">
            <h2 class="text-xs font-bold text-slate-800 border-b border-slate-100 pb-2 mb-4 flex items-center gap-2">
                <div class="w-6 h-6 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center text-[10px] font-bold">1</div>
                Detail Ibadah
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <!-- Left Column -->
                <div class="space-y-3">
                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider">Nama Ibadah <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_ibadah" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all text-sm font-medium placeholder-slate-400" value="<?= $jadwal['nama_ibadah'] ?>" required>
                    </div>
                    <div class="space-y-1">
                         <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider">Tema Ibadah</label>
                         <input type="text" name="tema" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all text-sm font-medium placeholder-slate-400" value="<?= $jadwal['tema'] ?>">
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-3">
                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider">Tanggal <span class="text-rose-500">*</span></label>
                            <input type="date" name="tanggal" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all text-sm font-medium text-slate-600" required value="<?= $jadwal['tanggal'] ?>">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider">Jam <span class="text-rose-500">*</span></label>
                            <input type="time" name="jam" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all text-sm font-medium text-slate-600" required value="<?= date('H:i', strtotime($jadwal['jam'])) ?>">
                        </div>
                    </div>
                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider">Status</label>
                        <div class="relative">
                            <select name="status" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all appearance-none text-sm font-medium text-slate-600">
                                <option value="aktif" <?= $jadwal['status'] == 'aktif' ? 'selected' : '' ?>>Aktif (Akan Datang)</option>
                                <option value="selesai" <?= $jadwal['status'] == 'selesai' ? 'selected' : '' ?>>Selesai</option>
                                <option value="dibatalkan" <?= $jadwal['status'] == 'dibatalkan' ? 'selected' : '' ?>>Dibatalkan</option>
                            </select>
                            <ion-icon name="chevron-down-outline" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></ion-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Petugas Section -->
        <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 p-4 md:p-5 mb-4">
            <div class="flex justify-between items-center border-b border-slate-100 pb-2 mb-4">
                <h2 class="text-xs font-bold text-slate-800 flex items-center gap-2">
                    <div class="w-6 h-6 rounded-full bg-violet-50 text-violet-600 flex items-center justify-center text-[10px] font-bold">2</div>
                    Daftar Petugas
                </h2>
                <button type="button" id="add-row" class="px-3 py-1.5 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 font-bold rounded-lg transition-colors text-[10px] flex items-center gap-2">
                    <ion-icon name="add-circle"></ion-icon> Tambah Baris
                </button>
            </div>

            <div id="petugas-container" class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                <?php if(!empty($petugas)): ?>
                    <?php foreach($petugas as $index => $p): ?>
                     <div class="petugas-row relative bg-white p-3 rounded-xl border border-slate-100 shadow-sm group hover:border-indigo-200 transition-all">
                        <div class="absolute -top-2 -left-2 w-6 h-6 rounded-full bg-slate-800 text-white text-[10px] font-bold flex items-center justify-center shadow-md z-10 row-number">
                            <?= $index + 1 ?>
                        </div>
                        <button type="button" class="remove-row absolute -top-2 -right-2 w-6 h-6 rounded-full bg-rose-500 text-white flex items-center justify-center transition-all scale-75 opacity-0 group-hover:opacity-100 group-hover:scale-100 shadow-md z-10" title="Hapus Baris">
                            <ion-icon name="close-outline"></ion-icon>
                        </button>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Jenis Tugas</label>
                                <div class="relative">
                                    <ion-icon name="briefcase-outline" class="absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></ion-icon>
                                    <input type="text" name="jenis_tugas[]" class="w-full pl-8 pr-2 py-1.5 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all text-xs font-medium" value="<?= $p['jenis_tugas'] ?>">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Keterangan / Nama Petugas</label>
                                <div class="relative">
                                     <ion-icon name="person-outline" class="absolute left-2.5 top-2.5 text-slate-400 text-xs"></ion-icon>
                                    <textarea name="nama_petugas[]" rows="2" class="w-full pl-8 pr-2 py-1.5 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all text-xs font-medium resize-none min-h-[38px]"><?= $p['nama_petugas'] ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
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

        // Update Row Numbers Function
        function updateRowNumbers() {
            document.querySelectorAll('.row-number').forEach((el, index) => {
                el.textContent = index + 1;
            });
        }

        // Add Row Function
        addButton.addEventListener('click', function() {
            const row = document.createElement('div');
            row.className = 'petugas-row relative bg-white p-3 rounded-xl border border-slate-100 shadow-sm group hover:border-indigo-200 transition-all animate-fade-in';
            row.innerHTML = `
                <div class="absolute -top-2 -left-2 w-6 h-6 rounded-full bg-slate-800 text-white text-[10px] font-bold flex items-center justify-center shadow-md z-10 row-number"></div>
                <button type="button" class="remove-row absolute -top-2 -right-2 w-6 h-6 rounded-full bg-rose-500 text-white flex items-center justify-center transition-all scale-75 opacity-0 group-hover:opacity-100 group-hover:scale-100 shadow-md z-10" title="Hapus Baris">
                    <ion-icon name="close-outline"></ion-icon>
                </button>
                <div class="grid grid-cols-12 gap-2">
                    <div class="col-span-4">
                        <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Jenis Tugas</label>
                        <div class="relative">
                            <ion-icon name="briefcase-outline" class="absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></ion-icon>
                            <input type="text" name="jenis_tugas[]" class="w-full pl-8 pr-2 py-1.5 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all text-xs font-medium" placeholder="Musik/Singer">
                        </div>
                    </div>
                    <div class="col-span-8">
                        <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Keterangan / Nama Petugas</label>
                        <div class="relative">
                             <ion-icon name="person-outline" class="absolute left-2.5 top-2.5 text-slate-400 text-xs"></ion-icon>
                            <textarea name="nama_petugas[]" rows="2" class="w-full pl-8 pr-2 py-1.5 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all text-xs font-medium resize-none min-h-[38px]" placeholder="Keterangan..."></textarea>
                        </div>
                    </div>
                </div>
            `;
            container.appendChild(row);
            updateRowNumbers();
        });

        // Remove Row Function (Delegation)
        container.addEventListener('click', function(e) {
            if (e.target.closest('.remove-row')) {
                const row = e.target.closest('.petugas-row');
                
                Swal.fire({
                    title: 'Hapus petugas?',
                    text: "Tugas ini akan dihapus dari daftar.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#EF4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        row.remove();
                        updateRowNumbers();
                    }
                });
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
