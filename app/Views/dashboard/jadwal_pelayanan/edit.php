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
        <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 p-6 md:p-8 mb-6">
            <h2 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-3 mb-6 flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm font-bold">1</div>
                Detail Ibadah
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-700">Nama Ibadah <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_ibadah" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all font-medium placeholder-slate-400" value="<?= $jadwal['nama_ibadah'] ?>" required>
                    </div>
                    <div class="space-y-2">
                         <label class="block text-sm font-bold text-slate-700">Tema Ibadah</label>
                         <input type="text" name="tema" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all font-medium placeholder-slate-400" value="<?= $jadwal['tema'] ?>">
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-slate-700">Tanggal <span class="text-rose-500">*</span></label>
                            <input type="date" name="tanggal" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all font-medium text-slate-600" required value="<?= $jadwal['tanggal'] ?>">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-slate-700">Jam <span class="text-rose-500">*</span></label>
                            <input type="time" name="jam" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all font-medium text-slate-600" required value="<?= date('H:i', strtotime($jadwal['jam'])) ?>">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-700">Status</label>
                        <div class="relative">
                            <select name="status" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all appearance-none font-medium text-slate-600">
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
        <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 p-6 md:p-8 mb-6">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3 mb-6">
                <h2 class="text-base font-bold text-slate-800 flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-violet-50 text-violet-600 flex items-center justify-center text-sm font-bold">2</div>
                    Daftar Petugas
                </h2>
                <button type="button" id="add-row" class="px-4 py-2 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 font-bold rounded-lg transition-colors text-xs flex items-center gap-2">
                    <ion-icon name="add-circle"></ion-icon> Tambah Baris
                </button>
            </div>

            <div id="petugas-container" class="space-y-4">
                <?php if(!empty($petugas)): ?>
                    <?php foreach($petugas as $p): ?>
                     <div class="petugas-row grid grid-cols-1 md:grid-cols-12 gap-4 items-center bg-slate-50/50 p-4 rounded-xl border border-slate-100 shadow-sm group hover:border-indigo-200 transition-all">
                        <div class="md:col-span-4">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Jenis Tugas</label>
                            <div class="relative">
                                <ion-icon name="briefcase-outline" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></ion-icon>
                                <input type="text" name="jenis_tugas[]" class="w-full pl-9 pr-3 py-2.5 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all text-sm font-medium" value="<?= $p['jenis_tugas'] ?>">
                            </div>
                        </div>
                        <div class="md:col-span-7">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Nama Petugas</label>
                            <div class="relative">
                                 <ion-icon name="person-outline" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></ion-icon>
                                <input type="text" name="nama_petugas[]" class="w-full pl-9 pr-3 py-2.5 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all text-sm font-medium" value="<?= $p['nama_petugas'] ?>">
                            </div>
                        </div>
                        <div class="md:col-span-1 flex justify-end pt-5 md:pt-0">
                            <button type="button" class="remove-row w-9 h-9 rounded-lg bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white flex items-center justify-center transition-all opacity-60 group-hover:opacity-100" title="Hapus Baris">
                                <ion-icon name="trash-outline"></ion-icon>
                            </button>
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

        // Add Row Function
        addButton.addEventListener('click', function() {
            const row = document.createElement('div');
            row.className = 'petugas-row grid grid-cols-1 md:grid-cols-12 gap-4 items-center bg-slate-50/50 p-4 rounded-xl border border-slate-100 shadow-sm group hover:border-indigo-200 transition-all animate-fade-in';
            row.innerHTML = `
                <div class="md:col-span-4">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Jenis Tugas</label>
                    <div class="relative">
                        <ion-icon name="briefcase-outline" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></ion-icon>
                        <input type="text" name="jenis_tugas[]" class="w-full pl-9 pr-3 py-2.5 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all text-sm font-medium" placeholder="e.g. Musik/Singer">
                    </div>
                </div>
                <div class="md:col-span-7">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Nama Petugas</label>
                    <div class="relative">
                         <ion-icon name="person-outline" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></ion-icon>
                        <input type="text" name="nama_petugas[]" class="w-full pl-9 pr-3 py-2.5 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all text-sm font-medium" placeholder="Nama Lengkap Petugas">
                    </div>
                </div>
                <div class="md:col-span-1 flex justify-end pt-5 md:pt-0">
                    <button type="button" class="remove-row w-9 h-9 rounded-lg bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white flex items-center justify-center transition-all opacity-60 group-hover:opacity-100" title="Hapus Baris">
                        <ion-icon name="trash-outline"></ion-icon>
                    </button>
                </div>
            `;
            container.appendChild(row);
        });

        // Remove Row Function (Delegation)
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
