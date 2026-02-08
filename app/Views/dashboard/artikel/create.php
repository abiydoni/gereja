<?= $this->extend('layouts/backend') ?>

<?= $this->section('content') ?>

<div class="max-w-full mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
        <div class="mb-6 border-b border-slate-100 pb-4">
            <h3 class="text-lg font-bold text-slate-800">Tambah Artikel</h3>
        </div>

        <form action="<?= base_url('dashboard/artikel/store') ?>" method="POST" enctype="multipart/form-data">
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Penulis</label>
                    <input type="text" name="penulis" value="Admin" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                        <option value="aktif">Publikasikan (Aktif)</option>
                        <option value="nonaktif">Simpan sebagai Draft</option>
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-2">Gambar Sampul (Opsional)</label>
                
                <!-- Preview Image Container -->
                <div id="image-preview-container" class="hidden mb-3">
                    <img id="image-preview" src="#" alt="Preview Gambar" class="w-full h-64 object-cover rounded-lg border border-slate-200">
                    <button type="button" id="remove-image" class="mt-2 text-sm text-red-500 hover:text-red-700 font-medium">Hapus Gambar</button>
                </div>

                <div class="flex items-center justify-center w-full" id="dropzone-container">
                    <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-32 border-2 border-slate-300 border-dashed rounded-lg cursor-pointer bg-slate-50 hover:bg-slate-100 transition">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <ion-icon name="cloud-upload-outline" class="text-3xl text-slate-400 mb-2"></ion-icon>
                            <p class="text-sm text-slate-500"><span class="font-semibold">Klik untuk upload</span> atau drag and drop</p>
                            <p class="text-xs text-slate-500">PNG, JPG, JPEG (MAX. 2MB)</p>
                        </div>
                        <input id="dropzone-file" type="file" name="gambar" class="hidden" accept="image/*" />
                    </label>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-2">Judul Artikel</label>
                <input type="text" name="judul" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" required placeholder="Judul Artikel...">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-700 mb-2">Isi Artikel</label>
                <textarea id="editor" name="isi" class="w-full px-4 py-2 border border-slate-300 rounded-lg"><?= old('isi') ?></textarea>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="<?= base_url('dashboard/artikel') ?>" class="px-5 py-2.5 bg-slate-100 text-slate-600 font-medium rounded-lg hover:bg-slate-200 transition">Batal</a>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">Simpan</button>
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
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | forecolor backcolor | lineheight | numlist bullist indent outdent | link image media table codesample emoticons charmap | preview searchreplace visualblocks | removeformat code fullscreen',
        toolbar_mode: 'wrap',
        height: 600,
        branding: false,
        promotion: false,
        convert_urls: false,
        verify_html: false,
        valid_elements: '*[*]',
        extended_valid_elements: '*[*]',
        content_style: 'body { font-family:Inter,ui-sans-serif,system-ui,-apple-system,sans-serif; font-size:16px; color: #000000; }'
    });

    // Image Preview Script
    const dropzoneFile = document.getElementById('dropzone-file');
    const previewContainer = document.getElementById('image-preview-container');
    const imagePreview = document.getElementById('image-preview');
    const dropzoneContainer = document.getElementById('dropzone-container');
    const removeImageBtn = document.getElementById('remove-image');

    dropzoneFile.addEventListener('change', function(e) {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                previewContainer.classList.remove('hidden');
                dropzoneContainer.classList.add('hidden');
            }
            reader.readAsDataURL(file);
        }
    });

    removeImageBtn.addEventListener('click', function() {
        dropzoneFile.value = '';
        previewContainer.classList.add('hidden');
        dropzoneContainer.classList.remove('hidden');
        imagePreview.src = '#';
    });
</script>
<?= $this->endSection() ?>
