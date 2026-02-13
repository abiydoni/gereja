<?= $this->extend('layouts/backend') ?>

<?= $this->section('content') ?>

<div class="max-w-full mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
        <div class="mb-6 border-b border-slate-100 pb-4">
            <h3 class="text-lg font-bold text-slate-800">Edit Anggota Majelis</h3>
        </div>

        <form action="<?= base_url('dashboard/majelis/update/'.$majelis['id_majelis']) ?>" method="post" enctype="multipart/form-data">
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Nama Lengkap</label>
                    <input type="text" name="nama" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" required value="<?= $majelis['nama'] ?>">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                        <option value="aktif" <?= $majelis['status'] == 'aktif' ? 'selected' : '' ?>>Aktif</option>
                        <option value="tidak aktif" <?= $majelis['status'] == 'tidak aktif' ? 'selected' : '' ?>>Tidak Aktif</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Jabatan</label>
                    <input type="text" name="jabatan" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" required value="<?= $majelis['jabatan'] ?>">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Bidang</label>
                    <input type="text" name="bidang" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" value="<?= $majelis['bidang'] ?>">
                </div>
            </div>

             <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                     <label class="block text-sm font-medium text-slate-700 mb-2">No HP / WhatsApp</label>
                     <input type="text" name="no_hp" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" value="<?= $majelis['no_hp'] ?>">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Periode</label>
                    <input type="text" name="periode" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" value="<?= $majelis['periode'] ?>">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-700 mb-2">Foto (Opsional)</label>
                
                <div class="mb-3" id="preview-container">
                    <?php if($majelis['foto']): ?>
                        <img id="preview-image" src="<?= base_url('uploads/majelis/'.$majelis['foto']) ?>" class="h-32 w-32 object-cover rounded-xl shadow-lg border-2 border-slate-200">
                    <?php else: ?>
                        <img id="preview-image" src="#" alt="Preview Foto" class="h-32 w-32 object-cover rounded-xl shadow-lg border-2 border-slate-200 hidden">
                    <?php endif; ?>
                </div>

                <input type="file" name="foto" id="foto-input" accept="image/*" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                <p class="text-xs text-slate-500 mt-1">Biarkan kosong jika tidak ingin mengubah foto. Format: JPG, PNG. Max 2MB.</p>
            </div>

            <script>
                document.getElementById('foto-input').addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const preview = document.getElementById('preview-image');
                            preview.src = e.target.result;
                            preview.classList.remove('hidden');
                        }
                        reader.readAsDataURL(file);
                    }
                });
            </script>

            <div class="flex justify-end space-x-3">
                <a href="<?= base_url('dashboard/majelis') ?>" class="px-5 py-2.5 bg-slate-100 text-slate-600 font-medium rounded-lg hover:bg-slate-200 transition">Batal</a>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">Perbarui</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
