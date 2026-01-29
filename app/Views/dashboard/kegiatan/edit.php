<?= $this->extend('layouts/backend') ?>

<?= $this->section('content') ?>

<div class="max-w-full mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
        <div class="mb-6 border-b border-slate-100 pb-4">
            <h3 class="text-lg font-bold text-slate-800">Edit Kegiatan</h3>
        </div>

        <form action="<?= base_url('dashboard/kegiatan/update/'.$kegiatan['id_kegiatan']) ?>" method="post">
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Nama Kegiatan</label>
                    <input type="text" name="nama_kegiatan" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" required value="<?= $kegiatan['nama_kegiatan'] ?>">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                        <option value="aktif" <?= $kegiatan['status'] == 'aktif' ? 'selected' : '' ?>>Aktif</option>
                        <option value="tidak aktif" <?= $kegiatan['status'] == 'tidak aktif' ? 'selected' : '' ?>>Tidak Aktif</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Mulai</label>
                    <input type="datetime-local" name="tanggal_mulai" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" required value="<?= date('Y-m-d\TH:i', strtotime($kegiatan['tanggal_mulai'])) ?>">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Selesai</label>
                    <input type="datetime-local" name="tanggal_selesai" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" required value="<?= date('Y-m-d\TH:i', strtotime($kegiatan['tanggal_selesai'])) ?>">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-2">Lokasi</label>
                <input type="text" name="lokasi" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" required value="<?= $kegiatan['lokasi'] ?>">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-700 mb-2">Deskripsi</label>
                <textarea id="editor" name="deskripsi" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"><?= $kegiatan['deskripsi'] ?></textarea>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="<?= base_url('dashboard/kegiatan') ?>" class="px-5 py-2.5 bg-slate-100 text-slate-600 font-medium rounded-lg hover:bg-slate-200 transition">Batal</a>
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
