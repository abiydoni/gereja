<?= $this->extend('layouts/backend') ?>

<?= $this->section('content') ?>

<div class="max-w-full mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
        <div class="mb-6 border-b border-slate-100 pb-4">
            <h3 class="text-lg font-bold text-slate-800">Edit Informasi</h3>
        </div>

        <form action="<?= base_url('dashboard/informasi/update/'.$informasi['id_informasi']) ?>" method="post" enctype="multipart/form-data">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Judul</label>
                    <input type="text" name="judul" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" required value="<?= $informasi['judul'] ?>">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Tanggal</label>
                    <input type="date" name="tanggal" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" required value="<?= $informasi['tanggal'] ?>">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                        <option value="aktif" <?= $informasi['status'] == 'aktif' ? 'selected' : '' ?>>Aktif</option>
                        <option value="tidak aktif" <?= $informasi['status'] == 'tidak aktif' ? 'selected' : '' ?>>Tidak Aktif</option>
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-2">Deskripsi</label>
                <textarea id="editor" name="deskripsi" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"><?= $informasi['deskripsi'] ?></textarea>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-700 mb-2">Gambar (Opsional)</label>
                <?php if($informasi['gambar']): ?>
                    <div class="mb-2">
                        <img src="<?= base_url('uploads/informasi/'.$informasi['gambar']) ?>" class="h-24 w-auto object-cover rounded-lg border border-slate-200">
                    </div>
                <?php endif; ?>
                <input type="file" name="gambar" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                <p class="text-xs text-slate-500 mt-1">Biarkan kosong jika tidak ingin mengubah gambar.</p>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="<?= base_url('dashboard/informasi') ?>" class="px-5 py-2.5 bg-slate-100 text-slate-600 font-medium rounded-lg hover:bg-slate-200 transition">Batal</a>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">Perbarui</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js"></script>
<script>
    tinymce.init({
        selector: '#editor',
        language: 'id',
        language_url: 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/id.js',
        plugins: 'advlist anchor autolink charmap code codesample emoticons fullscreen help image insertdatetime link lists media preview searchreplace table visualblocks wordcount',
        menubar: 'edit insert view format table tools help',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | forecolor backcolor | align lineheight | numlist bullist indent outdent | link image table emoticons charmap | removeformat code fullscreen',
        toolbar_mode: 'sliding',
        height: 400,
        branding: false,
        promotion: false,
        content_style: 'body { font-family:Inter,ui-sans-serif,system-ui,-apple-system,sans-serif; font-size:16px }'
    });
</script>
<?= $this->endSection() ?>
