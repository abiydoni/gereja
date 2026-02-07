<?= $this->extend('layouts/backend') ?>

<?= $this->section('content') ?>

<div class="max-w-full mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-lg font-bold text-slate-800">Tambah Item Galeri</h3>
            <a href="<?= base_url('dashboard/galeri') ?>" class="text-slate-400 hover:text-slate-600 transition text-2xl">
                <ion-icon name="close-circle-outline"></ion-icon>
            </a>
        </div>
        
        <form action="<?= base_url('dashboard/galeri/store') ?>" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
            <div class="space-y-4">
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Judul Galeri / Album</label>
                    <input type="text" name="judul" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition" placeholder="Contoh: Album Natal 2024">
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Kategori Media</label>
                    <select name="kategori" id="kategori" onchange="toggleInputs()" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition">
                        <option value="upload_audio">Upload Audio Playlist (Folder/Files)</option>
                        <option value="youtube">YouTube Video</option>
                        <option value="drive_img">Google Drive (Folder Gambar)</option>
                        <option value="drive_audio">Google Drive (Folder Audio - Legacy)</option>
                    </select>
                </div>

                <!-- Input for External Links -->
                <div class="space-y-2" id="link-input-group">
                    <label class="block text-sm font-semibold text-slate-700">Link / ID Media</label>
                    <input type="text" name="link_media" id="link_media_field" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition" placeholder="Masukkan ID YouTube atau ID Google Drive">
                    <p class="text-[10px] text-slate-400 italic">
                        Khusus YouTube & Google Drive.
                    </p>
                </div>

                <!-- Input for Local Folder Upload -->
                <div class="space-y-2 hidden" id="upload-input-group">
                    <label class="block text-sm font-semibold text-slate-700">Upload Folder Audio</label>
                    <div class="border-2 border-dashed border-slate-300 rounded-xl p-6 text-center hover:bg-slate-50 transition cursor-pointer relative">
                        <input type="file" name="audio_files[]" id="audio_files" multiple webkitdirectory directory class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        <!-- Hidden input to store paths -->
                        <input type="hidden" name="folder_paths" id="folder_paths">
                        
                        <div class="space-y-2 pointer-events-none">
                            <ion-icon name="folder-open-outline" class="text-4xl text-indigo-400"></ion-icon>
                            <p class="text-sm font-medium text-slate-600">Klik untuk pilih <span class="text-indigo-600">Folder Audio</span></p>
                            <p class="text-xs text-slate-400">Nama folder akan otomatis menjadi Sub-Judul Playlist.</p>
                        </div>
                    </div>
                    <div id="file-preview" class="mt-4 space-y-2 max-h-40 overflow-y-auto text-xs text-slate-500"></div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Keterangan (Opsional)</label>
                    <textarea name="keterangan" rows="3" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition" placeholder="Tuliskan sedikit penjelasan..."></textarea>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Status</label>
                    <select name="status" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="px-8 py-3 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all hover:-translate-y-1">
                    SIMPAN GALERI
                </button>
            </div>
        </form>

<script>
    function toggleInputs() {
        const cat = document.getElementById('kategori').value;
        const linkGroup = document.getElementById('link-input-group');
        const uploadGroup = document.getElementById('upload-input-group');
        const linkField = document.getElementById('link_media_field');

        if (cat === 'upload_audio') {
            linkGroup.classList.add('hidden');
            uploadGroup.classList.remove('hidden');
            linkField.removeAttribute('required');
        } else {
            linkGroup.classList.remove('hidden');
            uploadGroup.classList.add('hidden');
            linkField.setAttribute('required', 'required');
        }
    }

    // Capture WebkitRelativePath
    document.getElementById('audio_files').addEventListener('change', function(e) {
        const files = e.target.files;
        const paths = [];
        const preview = document.getElementById('file-preview');
        preview.innerHTML = '';

        if (files.length > 0) {
            let folderCount = {};
            
            for (let i = 0; i < files.length; i++) {
                // Determine path or fallback to filename
                const p = files[i].webkitRelativePath || files[i].name;
                paths.push(p);

                // Preview Logic (Grouping by folder)
                const parts = p.split('/');
                const folder = parts.length > 1 ? parts[0] : 'Root';
                if(!folderCount[folder]) folderCount[folder] = 0;
                folderCount[folder]++;
            }

            // Show summary
            for (const [folder, count] of Object.entries(folderCount)) {
                const div = document.createElement('div');
                div.innerHTML = `<span class="font-bold text-slate-700">📂 ${folder}</span>: ${count} file`;
                preview.appendChild(div);
            }
        }
        
        document.getElementById('folder_paths').value = JSON.stringify(paths);
    });

    // Init
    toggleInputs();
</script>
    </div>
</div>

<?= $this->endSection() ?>
