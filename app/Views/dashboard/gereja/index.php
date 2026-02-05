<?= $this->extend('layouts/backend') ?>

<?= $this->section('content') ?>

<div class="max-w-7xl mx-auto" data-aos="fade-up">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="font-heading font-bold text-3xl text-slate-800">Profil Gereja</h1>
            <p class="text-slate-500 mt-1">Kelola identitas dan informasi utama gereja.</p>
        </div>
        <button type="submit" form="profileForm" class="hidden md:flex items-center space-x-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-200 transition-all duration-300 transform hover:-translate-y-1">
            <ion-icon name="save-outline" class="text-xl"></ion-icon>
            <span>Simpan Perubahan</span>
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-11 gap-8 items-start">
        
        <!-- Left: Preview Card -->
        <div class="lg:col-span-4 lg:sticky lg:top-28 space-y-6">
            <div class="relative group">
                <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500 to-fuchsia-500 rounded-[35px] blur opacity-25 group-hover:opacity-50 transition duration-1000 group-hover:duration-200"></div>
                <div class="relative bg-gradient-to-br from-primary to-slate-900 rounded-[32px] overflow-hidden text-center shadow-2xl p-8 text-white min-h-[400px] flex flex-col justify-center items-center">
                    
                    <!-- Decor -->
                    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#D4AF37 1px, transparent 1px); background-size: 20px 20px;"></div>
                    <div class="absolute -top-24 -right-24 w-64 h-64 bg-accent/20 rounded-full blur-3xl"></div>
                    <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl"></div>

                    <!-- Logo -->
                    <div class="relative mb-6">
                        <div id="logo-preview-container" class="w-28 h-28 mx-auto rounded-3xl bg-white/10 backdrop-blur-md flex items-center justify-center p-4 shadow-inner ring-1 ring-white/20 overflow-hidden">
                             <?php if(!empty($gereja['logo'])): ?>
                                <img id="main-logo-preview" src="<?= base_url('uploads/'.$gereja['logo']) ?>" class="w-full h-full object-contain filter drop-shadow-md">
                            <?php else: ?>
                                <ion-icon id="main-logo-placeholder" name="image-outline" class="text-5xl text-white/50"></ion-icon>
                            <?php endif; ?>
                        </div>
                        <div class="absolute -bottom-2 -right-2 bg-accent text-primary p-2 rounded-full shadow-lg">
                            <ion-icon name="checkmark-circle" class="text-xl"></ion-icon>
                        </div>
                    </div>

                    <!-- Texts -->
                    <div class="relative z-10 w-full">
                        <h2 class="font-heading font-extrabold text-2xl tracking-tight mb-2 leading-snug">
                            <?= $gereja['nama_gereja'] ?>
                        </h2>
                        <div class="flex items-center justify-center space-x-2 text-slate-300 text-sm mb-6">
                            <ion-icon name="location" class="text-accent"></ion-icon>
                            <span class="opacity-80">Indonesia</span>
                        </div>
                        
                        <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-4 border border-white/10 text-left">
                            <p class="text-xs font-bold text-accent uppercase tracking-widest mb-2">Moto</p>
                            <p class="text-sm italic text-slate-300">"<?= $gereja['deskripsi'] ?>"</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-indigo-50 rounded-2xl p-6 border border-indigo-100 flex items-start space-x-4">
                <ion-icon name="bulb" class="text-2xl text-indigo-600 mt-1"></ion-icon>
                <div>
                    <h4 class="font-bold text-indigo-900 text-sm">Tips Tampilan</h4>
                    <p class="text-xs text-indigo-700 mt-1 leading-relaxed">
                        Logo format PNG transparan akan terlihat paling bagus di atas background gelap ini.
                    </p>
                </div>
            </div>
        </div>

        <!-- Right: Edit Form -->
        <div class="lg:col-span-7">
            <div class="bg-white rounded-[32px] shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-8 pb-4 border-b border-slate-50">
                    <h3 class="font-heading font-bold text-xl text-slate-800">Formulir Perubahan</h3>
                    <p class="text-sm text-slate-400">Update data terbaru gereja disini.</p>
                </div>
                
                <div class="p-8">
                    <form id="profileForm" action="<?= base_url('dashboard/gereja/update') ?>" method="post" enctype="multipart/form-data" class="space-y-6">
                        
                        <!-- File Upload -->
                             <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Upload Logo Baru</label>
                            <div class="relative group">
                                <label for="logo-upload" class="flex flex-col items-center justify-center w-full h-32 border-2 border-slate-200 border-dashed rounded-2xl cursor-pointer bg-slate-50 hover:bg-indigo-50 hover:border-indigo-300 transition-all duration-300 overflow-hidden">
                                    <div id="form-logo-preview" class="<?= !empty($gereja['logo']) ? '' : 'hidden' ?> absolute inset-0 w-full h-full rounded-2xl overflow-hidden bg-white">
                                        <img src="<?= !empty($gereja['logo']) ? base_url('uploads/'.$gereja['logo']) : '' ?>" class="w-full h-full object-contain p-2">
                                        <div class="absolute inset-0 bg-black/40 opacity-0 hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs font-bold">
                                            Ganti Logo
                                        </div>
                                    </div>
                                    <div id="upload-placeholder" class="<?= !empty($gereja['logo']) ? 'opacity-0' : '' ?> flex flex-col items-center justify-center pt-5 pb-6 text-slate-400 group-hover:text-indigo-500">
                                        <ion-icon name="cloud-upload-outline" class="text-3xl mb-2 transition-transform group-hover:-translate-y-1"></ion-icon>
                                        <p class="mb-1 text-sm"><span class="font-bold">Klik untuk upload</span> atau drag and drop</p>
                                        <p class="text-xs opacity-70">PNG, JPG (Max 2MB)</p>
                                    </div>
                                    <input id="logo-upload" name="logo" type="file" class="hidden" accept="image/*" />
                                </label>
                            </div>
                        </div>

                        <!-- Inputs -->
                        <!-- Identitas Section -->
                        <div class="space-y-6">
                            <h4 class="font-bold text-slate-800 text-sm uppercase tracking-wider border-b border-slate-100 pb-2 mb-4">
                                Identitas Utama
                            </h4>

                            <!-- Nama Gereja -->
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Nama Gereja</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <ion-icon name="business" class="text-slate-400"></ion-icon>
                                    </div>
                                    <input type="text" name="nama_gereja" required
                                        class="w-full pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 outline-none transition-all placeholder:text-slate-300 text-slate-800 font-medium"
                                        placeholder="Contoh: GKJ Randuares"
                                        value="<?= $gereja['nama_gereja'] ?>">
                                </div>
                            </div>
                            
                            <!-- Alamat -->
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Alamat Lengkap</label>
                                <div class="relative">
                                        <div class="absolute top-3.5 left-4 flex items-center pointer-events-none">
                                        <ion-icon name="map" class="text-slate-400"></ion-icon>
                                    </div>
                                    <textarea name="alamat" rows="2" required
                                        class="w-full pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 outline-none transition-all placeholder:text-slate-300 text-slate-800 leading-relaxed"
                                        placeholder="Alamat lengkap lokasi gereja..."><?= $gereja['alamat'] ?></textarea>
                                </div>
                            </div>

                            <!-- Deskripsi -->
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Deskripsi / Moto</label>
                                <div class="relative">
                                        <div class="absolute top-3.5 left-4 flex items-center pointer-events-none">
                                        <ion-icon name="pencil" class="text-slate-400"></ion-icon>
                                    </div>
                                    <textarea name="deskripsi" rows="2" required
                                        class="w-full pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 outline-none transition-all placeholder:text-slate-300 text-slate-800 leading-relaxed"
                                        placeholder="Tuliskan moto atau deskripsi singkat..."><?= $gereja['deskripsi'] ?></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Contact & Social Media Section -->
                        <div class="pt-8 mt-6">
                            <h4 class="font-bold text-slate-800 text-sm uppercase tracking-wider border-b border-slate-100 pb-2 mb-6 flex items-center justify-between">
                                <span>Kontak & Digital</span>
                                <ion-icon name="share-social" class="text-indigo-400"></ion-icon>
                            </h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Phone -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">WhatsApp / Telepon</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <ion-icon name="call" class="text-emerald-500"></ion-icon>
                                        </div>
                                        <input type="text" name="telp" class="w-full pl-9 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 outline-none transition-all text-sm font-medium placeholder:text-slate-400" placeholder="08..." value="<?= $gereja['telp'] ?? '' ?>">
                                    </div>
                                </div>

                                <!-- Email -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Email Resmi</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <ion-icon name="mail" class="text-blue-500"></ion-icon>
                                        </div>
                                        <input type="email" name="email" class="w-full pl-9 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all text-sm font-medium placeholder:text-slate-400" placeholder="info@..." value="<?= $gereja['email'] ?? '' ?>">
                                    </div>
                                </div>

                                <!-- Instagram -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Instagram URL</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <ion-icon name="logo-instagram" class="text-pink-500"></ion-icon>
                                        </div>
                                        <input type="text" name="ig" class="w-full pl-9 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-pink-100 focus:border-pink-500 outline-none transition-all text-sm font-medium placeholder:text-slate-400" placeholder="https://instagram.com/..." value="<?= $gereja['ig'] ?? '' ?>">
                                    </div>
                                </div>

                                <!-- Facebook -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Facebook URL</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <ion-icon name="logo-facebook" class="text-blue-600"></ion-icon>
                                        </div>
                                        <input type="text" name="fb" class="w-full pl-9 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-600 outline-none transition-all text-sm font-medium placeholder:text-slate-400" placeholder="https://facebook.com/..." value="<?= $gereja['fb'] ?? '' ?>">
                                    </div>
                                </div>

                                <!-- TikTok -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">TikTok URL</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <ion-icon name="logo-tiktok" class="text-black"></ion-icon>
                                        </div>
                                        <input type="text" name="tt" class="w-full pl-9 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-slate-100 focus:border-slate-800 outline-none transition-all text-sm font-medium placeholder:text-slate-400" placeholder="https://tiktok.com/..." value="<?= $gereja['tt'] ?? '' ?>">
                                    </div>
                                </div>

                                <!-- YouTube -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">YouTube Channel</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <ion-icon name="logo-youtube" class="text-red-600"></ion-icon>
                                        </div>
                                        <input type="text" name="yt" class="w-full pl-9 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-red-100 focus:border-red-600 outline-none transition-all text-sm font-medium placeholder:text-slate-400" placeholder="https://youtube.com/..." value="<?= $gereja['yt'] ?? '' ?>">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Mobile Save Button -->
                        <div class="md:hidden pt-4">
                            <button type="submit" class="w-full flex items-center justify-center space-x-2 px-6 py-3 bg-indigo-600 active:bg-indigo-700 text-white font-bold rounded-xl shadow-lg transition-all">
                                <ion-icon name="save-outline" class="text-xl"></ion-icon>
                                <span>Simpan Perubahan</span>
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('logo-upload').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                // Update Main Preview (Card)
                const mainPreview = document.getElementById('main-logo-preview');
                const mainPlaceholder = document.getElementById('main-logo-placeholder');
                
                if (mainPreview) {
                    mainPreview.src = event.target.result;
                } else if (mainPlaceholder) {
                    const container = document.getElementById('logo-preview-container');
                    container.innerHTML = `<img id="main-logo-preview" src="${event.target.result}" class="w-full h-full object-contain filter drop-shadow-md">`;
                }
                
                // Update Form Preview
                const formPreview = document.getElementById('form-logo-preview');
                const uploadPlaceholder = document.getElementById('upload-placeholder');
                
                formPreview.classList.remove('hidden');
                formPreview.querySelector('img').src = event.target.result;
                uploadPlaceholder.classList.add('opacity-0');
            }
            reader.readAsDataURL(file);
        }
    });

    // Sync Nama Gereja Preview
    document.querySelector('input[name="nama_gereja"]').addEventListener('input', function(e) {
        document.querySelector('h2.font-heading').textContent = e.target.value || 'Nama Gereja';
    });
</script>

<?= $this->endSection() ?>
