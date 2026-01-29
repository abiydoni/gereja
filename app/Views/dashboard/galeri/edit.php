<?= $this->extend('layouts/backend') ?>

<?= $this->section('content') ?>

<div class="max-w-full mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-lg font-bold text-slate-800">Edit Item Galeri</h3>
            <a href="<?= base_url('dashboard/galeri') ?>" class="text-slate-400 hover:text-slate-600 transition text-2xl">
                <ion-icon name="close-circle-outline"></ion-icon>
            </a>
        </div>
        
        <form action="<?= base_url('dashboard/galeri/update/'.$item['id_galeri']) ?>" method="POST" class="p-8 space-y-6">
            <div class="space-y-4">
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Judul Item</label>
                    <input type="text" name="judul" value="<?= esc($item['judul']) ?>" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition">
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Kategori Media</label>
                    <select name="kategori" id="kategori" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition">
                        <option value="youtube" <?= $item['kategori'] == 'youtube' ? 'selected' : '' ?>>YouTube Video</option>
                        <option value="drive_img" <?= $item['kategori'] == 'drive_img' ? 'selected' : '' ?>>Google Drive (Folder Gambar)</option>
                        <option value="drive_audio" <?= $item['kategori'] == 'drive_audio' ? 'selected' : '' ?>>Google Drive (Folder Audio/Musik)</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Link / ID Media</label>
                    <input type="text" name="link_media" value="<?= esc($item['link_media']) ?>" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition">
                    <p class="text-[10px] text-slate-400 italic">
                        ID Video YouTube atau ID Folder Google Drive.
                    </p>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Keterangan (Opsional)</label>
                    <textarea name="keterangan" rows="3" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition"><?= esc($item['keterangan']) ?></textarea>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Status</label>
                    <select name="status" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition">
                        <option value="aktif" <?= $item['status'] == 'aktif' ? 'selected' : '' ?>>Aktif</option>
                        <option value="nonaktif" <?= $item['status'] == 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="px-8 py-3 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all hover:-translate-y-1">
                    PERBARUI GALERI
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
