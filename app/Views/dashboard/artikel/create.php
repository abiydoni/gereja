<?= $this->extend('layouts/backend') ?>

<?= $this->section('content') ?>

<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 font-heading">Tambah Artikel</h1>
            <p class="text-slate-500 text-sm mt-1">Buat artikel baru untuk dipublikasikan.</p>
        </div>
        <a href="<?= base_url('dashboard/artikel') ?>" class="px-4 py-2 bg-white text-slate-600 font-bold rounded-xl border border-slate-200 shadow-sm hover:bg-slate-50 transition flex items-center gap-2">
            <ion-icon name="arrow-back-outline"></ion-icon>
            <span>Kembali</span>
        </a>
    </div>

    <form action="<?= base_url('dashboard/artikel/store') ?>" method="POST" enctype="multipart/form-data">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Title & Content Card -->
                <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 p-6 md:p-8">
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-slate-700">Judul Artikel <span class="text-rose-500">*</span></label>
                            <input type="text" name="judul" required 
                                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all font-medium placeholder-slate-400" 
                                   placeholder="Contoh: Jadwal Ibadah Natal 2026...">
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-slate-700">Konten Artikel <span class="text-rose-500">*</span></label>
                            <div class="rounded-xl overflow-hidden border border-slate-200 focus-within:ring-2 focus-within:ring-indigo-500/20 focus-within:border-indigo-500 transition-all">
                                <textarea name="isi" id="editor" rows="20" class="w-full"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Settings & Meta -->
            <div class="space-y-6">
                <!-- Publish Settings -->
                <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 p-6">
                    <h3 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-3 mb-4 flex items-center gap-2">
                        <ion-icon name="options-outline" class="text-indigo-500"></ion-icon>
                        Pengaturan Publikasi
                    </h3>
                    
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide">Status</label>
                            <div class="relative">
                                <select name="status" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all appearance-none font-medium">
                                    <option value="aktif">Publikasikan (Aktif)</option>
                                    <option value="nonaktif">Simpan sebagai Draft</option>
                                </select>
                                <ion-icon name="chevron-down-outline" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></ion-icon>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide">Penulis</label>
                            <div class="relative">
                                <ion-icon name="person-outline" class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></ion-icon>
                                <input type="text" name="penulis" value="Admin" required 
                                       class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all font-medium">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Featured Image -->
                <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 p-6">
                     <h3 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-3 mb-4 flex items-center gap-2">
                        <ion-icon name="image-outline" class="text-emerald-500"></ion-icon>
                        Gambar Sampul
                    </h3>
                    
                    <div class="space-y-3">
                        <div class="relative w-full h-40 bg-slate-50 border-2 border-dashed border-slate-200 rounded-xl flex flex-col items-center justify-center text-center hover:bg-slate-100/50 hover:border-indigo-300 transition-all cursor-pointer group overflow-hidden">
                            <input type="file" name="gambar" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full z-10">
                            <div class="group-hover:scale-110 transition-transform duration-300 flex flex-col items-center">
                                <ion-icon name="cloud-upload-outline" class="text-3xl text-slate-300 group-hover:text-indigo-500 transition-colors mb-2"></ion-icon>
                                <span class="text-xs font-bold text-slate-400 group-hover:text-slate-600">Klik / Drop Gambar</span>
                            </div>
                        </div>
                        <p class="text-[10px] text-slate-400 text-center">Format: JPG/PNG, Max: 2MB</p>
                    </div>
                </div>

                <!-- Action Button -->
                <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-bold rounded-xl shadow-lg shadow-indigo-200 hover:shadow-indigo-300 hover:-translate-y-1 transition-all flex items-center justify-center gap-2">
                    <ion-icon name="save-outline" class="text-xl"></ion-icon>
                    <span>Simpan Artikel</span>
                </button>
            </div>
        </div>
    </form>
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
        menubar: 'edit insert view format table tools help', // Enable menubar for more space
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | forecolor backcolor | align lineheight | numlist bullist indent outdent | link image table emoticons charmap | removeformat code fullscreen',
        toolbar_mode: 'sliding', // Compact toolbar mode
        height: 600,
        branding: false,
        promotion: false,
        content_style: 'body { font-family:Inter,ui-sans-serif,system-ui,-apple-system,sans-serif; font-size:16px }'
    });
</script>
<?= $this->endSection() ?>
