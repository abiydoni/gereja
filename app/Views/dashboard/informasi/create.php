<?= $this->extend('layouts/backend') ?>

<?= $this->section('content') ?>

<div class="max-w-full mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
        <div class="mb-6 border-b border-slate-100 pb-4">
            <h3 class="text-lg font-bold text-slate-800">Tambah Informasi</h3>
        </div>

        <form action="<?= base_url('dashboard/informasi/store') ?>" method="post" enctype="multipart/form-data">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Judul</label>
                    <input type="text" name="judul" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" required placeholder="Judul Informasi...">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Tanggal</label>
                    <input type="date" name="tanggal" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" required value="<?= date('Y-m-d') ?>">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                        <option value="aktif">Aktif</option>
                        <option value="tidak aktif">Tidak Aktif</option>
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-2">Deskripsi</label>
                <textarea id="editor" name="deskripsi" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" placeholder="Jelaskan detail informasi..."></textarea>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-700 mb-2">Gambar Utama</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-xl hover:bg-slate-50 transition-colors relative group cursor-pointer" id="drop-zone">
                    <div class="space-y-1 text-center" id="upload-placeholder">
                        <div class="mx-auto h-12 w-12 text-slate-400 group-hover:text-indigo-500 transition-colors">
                            <ion-icon name="image-outline" class="text-5xl"></ion-icon>
                        </div>
                        <div class="flex text-sm text-slate-600 justify-center">
                            <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                <span>Upload gambar</span>
                                <input id="file-upload" name="gambar" type="file" class="sr-only" accept="image/png, image/jpeg, image/jpg">
                            </label>
                            <p class="pl-1">atau drag and drop</p>
                        </div>
                        <p class="text-xs text-slate-500">PNG, JPG, GIF up to 2MB</p>
                    </div>
                    
                    <!-- Preview Container -->
                    <div id="preview-container" class="hidden absolute inset-0 w-full h-full bg-slate-50 rounded-xl flex items-center justify-center overflow-hidden group-hover:opacity-90 transition-opacity">
                        <img id="image-preview" src="#" alt="Preview" class="max-h-full max-w-full object-contain">
                        <button type="button" id="remove-image" class="absolute top-2 right-2 p-1.5 bg-white/80 rounded-full text-slate-600 hover:text-red-500 hover:bg-white shadow-sm transition-all">
                            <ion-icon name="close-outline" class="text-xl"></ion-icon>
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="<?= base_url('dashboard/informasi') ?>" class="px-5 py-2.5 bg-slate-100 text-slate-600 font-medium rounded-lg hover:bg-slate-200 transition">Batal</a>
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
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | forecolor backcolor | align lineheight | numlist bullist indent outdent | link image table emoticons charmap | removeformat code fullscreen',
        toolbar_mode: 'sliding',
        height: 400,
        branding: false,
        promotion: false,
        content_style: 'body { font-family:Inter,ui-sans-serif,system-ui,-apple-system,sans-serif; font-size:16px }'
    });

    // Image Upload Preview Logic
    const dropZone = document.getElementById('drop-zone');
    const fileInput = document.getElementById('file-upload');
    const previewContainer = document.getElementById('preview-container');
    const imagePreview = document.getElementById('image-preview');
    const uploadPlaceholder = document.getElementById('upload-placeholder');
    const removeBtn = document.getElementById('remove-image');

    // Handle File Select
    fileInput.addEventListener('change', function(e) {
        const file = this.files[0];
        if (file) {
            showPreview(file);
        }
    });

    // Handle Drag & Drop
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('border-indigo-500', 'bg-indigo-50');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('border-indigo-500', 'bg-indigo-50');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-indigo-500', 'bg-indigo-50');
        const file = e.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) {
            fileInput.files = e.dataTransfer.files; // Update input files
            showPreview(file);
        }
    });

    // Show Preview Function
    function showPreview(file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            imagePreview.src = e.target.result;
            previewContainer.classList.remove('hidden');
            uploadPlaceholder.classList.add('opacity-0'); // Hide placeholder but keep layout
        }
        reader.readAsDataURL(file);
    }

    // Remove Image
    removeBtn.addEventListener('click', (e) => {
        e.stopPropagation(); // Prevent triggering dropzone click
        fileInput.value = ''; // Clear input
        previewContainer.classList.add('hidden');
        uploadPlaceholder.classList.remove('opacity-0');
        imagePreview.src = '#';
    });
</script>
<?= $this->endSection() ?>
