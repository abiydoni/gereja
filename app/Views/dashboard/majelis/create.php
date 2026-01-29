<?= $this->extend('layouts/backend') ?>

<?= $this->section('content') ?>

<div class="max-w-full mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
        <div class="mb-6 border-b border-slate-100 pb-4">
            <h3 class="text-lg font-bold text-slate-800">Tambah Anggota Majelis</h3>
        </div>

        <form action="<?= base_url('dashboard/majelis/store') ?>" method="post" enctype="multipart/form-data">
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Nama Lengkap</label>
                    <input type="text" name="nama" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" required placeholder="Contoh: Bpk. Sutrisno">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                        <option value="aktif">Aktif</option>
                        <option value="tidak aktif">Tidak Aktif</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Jabatan</label>
                    <input type="text" name="jabatan" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" required placeholder="Contoh: Ketua Majelis">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Bidang</label>
                    <input type="text" name="bidang" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" placeholder="Contoh: Umum">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                     <label class="block text-sm font-medium text-slate-700 mb-2">No HP / WhatsApp</label>
                     <input type="text" name="no_hp" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" placeholder="08123456789">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Periode</label>
                    <input type="text" name="periode" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" value="2024-2029">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-700 mb-2">Foto (Opsional)</label>
                <input type="file" name="foto" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                <p class="text-xs text-slate-500 mt-1">Format: JPG, PNG. Max 2MB.</p>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="<?= base_url('dashboard/majelis') ?>" class="px-5 py-2.5 bg-slate-100 text-slate-600 font-medium rounded-lg hover:bg-slate-200 transition">Batal</a>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">Simpan</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
