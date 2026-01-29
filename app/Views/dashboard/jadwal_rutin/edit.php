<?= $this->extend('layouts/backend') ?>

<?= $this->section('content') ?>

<div class="mb-5">
    <a href="/dashboard/jadwal_rutin" class="text-slate-500 hover:text-primary transition-colors flex items-center gap-2 font-bold mb-2">
        <ion-icon name="arrow-back"></ion-icon> Kembali
    </a>
    <h1 class="font-heading font-extrabold text-2xl text-slate-800">Edit Jadwal Rutin</h1>
</div>

<?php if(session()->getFlashdata('error')): ?>
<div class="p-4 mb-6 rounded-xl bg-rose-50 border border-rose-100 text-rose-600 flex items-center gap-3">
    <ion-icon name="alert-circle" class="text-xl"></ion-icon>
    <span class="font-bold"><?= session()->getFlashdata('error') ?></span>
</div>
<?php endif; ?>

<form action="/dashboard/jadwal_rutin/update/<?= $jadwal['id_jadwal'] ?>" method="post" class="bg-white rounded-[20px] shadow-sm border border-slate-100 overflow-hidden max-w-4xl">
    <?= csrf_field() ?>
    
    <div class="p-8 border-b border-slate-100 grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="md:col-span-2">
            <label class="block text-sm font-bold text-slate-700 mb-2">Nama Ibadah <span class="text-rose-500">*</span></label>
            <input type="text" name="nama_ibadah" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none" value="<?= $jadwal['nama_ibadah'] ?>" required>
        </div>
        <div>
             <label class="block text-sm font-bold text-slate-700 mb-2">Hari <span class="text-rose-500">*</span></label>
             <select name="hari" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none appearance-none bg-white">
                <?php 
                $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                foreach($days as $day): ?>
                    <option value="<?= $day ?>" <?= $jadwal['hari'] == $day ? 'selected' : '' ?>><?= $day ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Jam <span class="text-rose-500">*</span></label>
            <input type="time" name="jam" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none" required value="<?= date('H:i', strtotime($jadwal['jam'])) ?>">
        </div>
        <div class="md:col-span-2">
            <label class="block text-sm font-bold text-slate-700 mb-2">Lokasi <span class="text-rose-500">*</span></label>
            <input type="text" name="lokasi" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none" value="<?= $jadwal['lokasi'] ?>" required>
        </div>
        <div class="md:col-span-2">
            <label class="block text-sm font-bold text-slate-700 mb-2">Keterangan</label>
            <textarea name="keterangan" rows="3" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none"><?= $jadwal['keterangan'] ?></textarea>
        </div>
        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Status</label>
            <select name="status" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none appearance-none bg-white">
                <option value="aktif" <?= $jadwal['status'] == 'aktif' ? 'selected' : '' ?>>Aktif</option>
                <option value="non-aktif" <?= $jadwal['status'] == 'non-aktif' ? 'selected' : '' ?>>Non-Aktif</option>
            </select>
        </div>
    </div>

    <!-- Submit Action -->
    <div class="p-8 bg-slate-50 border-t border-slate-100 flex justify-end gap-3">
        <a href="/dashboard/jadwal_rutin" class="px-6 py-3 rounded-xl font-bold text-slate-500 hover:bg-slate-200 transition-colors">Batal</a>
        <button type="submit" class="px-8 py-3 bg-primary text-white font-bold rounded-xl shadow-lg shadow-primary/30 hover:bg-primary-dark transition-all transform hover:-translate-y-1">
            Simpan Perubahan
        </button>
    </div>
</form>

<?= $this->endSection() ?>
