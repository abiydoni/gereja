<?= $this->extend('layouts/backend') ?>

<?= $this->section('content') ?>

<div class="mb-6">
    <a href="<?= base_url('dashboard/jemaat') ?>" class="inline-flex items-center text-slate-500 hover:text-primary transition-colors text-sm font-bold mb-2">
        <ion-icon name="arrow-back-outline" class="mr-1"></ion-icon> Kembali
    </a>
    <h1 class="text-2xl font-bold text-slate-800 font-heading">Tambah Jemaat Baru</h1>
</div>

<form action="<?= base_url('dashboard/jemaat/store') ?>" method="POST" enctype="multipart/form-data">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left & Middle Column: Main Info -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Data Identitas -->
            <div class="bg-white p-8 rounded-[32px] shadow-xl shadow-slate-200/50 border border-slate-100">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                        <ion-icon name="id-card-outline" class="text-xl"></ion-icon>
                    </div>
                    <h2 class="text-lg font-bold text-slate-800 font-heading">Data Identitas</h2>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" value="<?= old('nama_lengkap') ?>" required class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all font-medium" placeholder="Nama sesuai KTP">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Nama Panggilan</label>
                        <input type="text" name="nama_panggilan" value="<?= old('nama_panggilan') ?>" class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all font-medium" placeholder="Nama sapaan">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">NIJ (No. Induk Jemaat)</label>
                        <input type="text" name="nij" value="<?= old('nij') ?>" class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all font-medium" placeholder="Contoh: JMT-2024-001">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">NIK (No. Induk Kependudukan)</label>
                        <input type="text" name="nik" value="<?= old('nik') ?>" maxlength="16" class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all font-medium" placeholder="16 digit angka">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">NIKK (No. Kartu Keluarga)</label>
                        <input type="text" name="nikk" value="<?= old('nikk') ?>" maxlength="16" class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all font-medium" placeholder="16 digit angka">
                    </div>
                </div>
            </div>

            <!-- Alamat & Kontak -->
            <div class="bg-white p-8 rounded-[32px] shadow-xl shadow-slate-200/50 border border-slate-100">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold">
                        <ion-icon name="location-outline" class="text-xl"></ion-icon>
                    </div>
                    <h2 class="text-lg font-bold text-slate-800 font-heading">Alamat & Kontak</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Alamat Domisili</label>
                        <textarea name="alamat" rows="3" class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all font-medium"><?= old('alamat') ?></textarea>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Wilayah / Rayon</label>
                        <input type="text" name="wilayah_rayon" value="<?= old('wilayah_rayon') ?>" class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all font-medium" placeholder="Contoh: Wilayah Tengah">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">No. Telepon / WA</label>
                        <input type="text" name="telepon" value="<?= old('telepon') ?>" class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all font-medium" placeholder="08xxxx">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Pekerjaan</label>
                        <input type="text" name="pekerjaan" value="<?= old('pekerjaan') ?>" class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all font-medium">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Pendidikan Terakhir</label>
                        <select name="pendidikan_terakhir" class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all font-medium">
                            <option value="SD" <?= old('pendidikan_terakhir') == 'SD' ? 'selected' : '' ?>>SD</option>
                            <option value="SMP" <?= old('pendidikan_terakhir') == 'SMP' ? 'selected' : '' ?>>SMP</option>
                            <option value="SMA/SMK" <?= old('pendidikan_terakhir') == 'SMA/SMK' ? 'selected' : '' ?>>SMA/SMK</option>
                            <option value="D3" <?= old('pendidikan_terakhir') == 'D3' ? 'selected' : '' ?>>D3</option>
                            <option value="S1" <?= old('pendidikan_terakhir') == 'S1' ? 'selected' : '' ?>>S1</option>
                            <option value="S2" <?= old('pendidikan_terakhir') == 'S2' ? 'selected' : '' ?>>S2</option>
                            <option value="S3" <?= old('pendidikan_terakhir') == 'S3' ? 'selected' : '' ?>>S3</option>
                            <option value="Lainnya" <?= old('pendidikan_terakhir') == 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Data Gerejawi -->
            <div class="bg-white p-8 rounded-[32px] shadow-xl shadow-slate-200/50 border border-slate-100">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                        <ion-icon name="shield-checkmark-outline" class="text-xl"></ion-icon>
                    </div>
                    <h2 class="text-lg font-bold text-slate-800 font-heading">Data Gerejawi</h2>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Status Jemaat</label>
                        <select name="status_jemaat" class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all font-medium">
                            <option value="Aktif" <?= old('status_jemaat') == 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                            <option value="Pindah" <?= old('status_jemaat') == 'Pindah' ? 'selected' : '' ?>>Pindah</option>
                            <option value="Wafat" <?= old('status_jemaat') == 'Wafat' ? 'selected' : '' ?>>Wafat</option>
                            <option value="Non-Aktif" <?= old('status_jemaat') == 'Non-Aktif' ? 'selected' : '' ?>>Non-Aktif</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Tanggal Baptis</label>
                        <input type="date" name="tanggal_baptis" value="<?= old('tanggal_baptis') ?>" class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all font-medium">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Tanggal Sidhi</label>
                        <input type="date" name="tanggal_sidhi" value="<?= old('tanggal_sidhi') ?>" class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all font-medium">
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Personal & Church Data -->
        <div class="space-y-8">
            <!-- Foto Profil -->
            <div class="bg-white p-8 rounded-[32px] shadow-xl shadow-slate-200/50 border border-slate-100">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                        <ion-icon name="image-outline" class="text-xl"></ion-icon>
                    </div>
                    <h2 class="text-lg font-bold text-slate-800 font-heading">Foto</h2>
                </div>
                
                <div class="space-y-4">
                    <div id="imagePreview" class="w-full h-48 rounded-2xl border-2 border-dashed border-slate-200 flex items-center justify-center overflow-hidden bg-slate-50">
                        <ion-icon name="cloud-upload-outline" class="text-4xl text-slate-300"></ion-icon>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block text-center">Ukuran Max: 2MB (JPG/PNG)</label>
                        <input type="file" name="foto" id="fotoInput" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 transition-all cursor-pointer">
                    </div>
                </div>
            </div>

            <!-- Data Pribadi -->
            <div class="bg-white p-8 rounded-[32px] shadow-xl shadow-slate-200/50 border border-slate-100">
                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Jenis Kelamin</label>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 cursor-pointer font-medium text-sm">
                                <input type="radio" name="jenis_kelamin" value="L" <?= old('jenis_kelamin') != 'P' ? 'checked' : '' ?> class="w-4 h-4 text-indigo-600"> Laki-laki
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer font-medium text-sm">
                                <input type="radio" name="jenis_kelamin" value="P" <?= old('jenis_kelamin') == 'P' ? 'checked' : '' ?> class="w-4 h-4 text-indigo-600"> Perempuan
                            </label>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" value="<?= old('tempat_lahir') ?>" class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all font-medium">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="<?= old('tanggal_lahir') ?>" class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all font-medium">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Status Perkawinan</label>
                        <select name="status_perkawinan" class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all font-medium">
                            <option value="Belum Menikah" <?= old('status_perkawinan') == 'Belum Menikah' ? 'selected' : '' ?>>Belum Menikah</option>
                            <option value="Menikah" <?= old('status_perkawinan') == 'Menikah' ? 'selected' : '' ?>>Menikah</option>
                            <option value="Cerai Hidup" <?= old('status_perkawinan') == 'Cerai Hidup' ? 'selected' : '' ?>>Cerai Hidup</option>
                            <option value="Cerai Mati" <?= old('status_perkawinan') == 'Cerai Mati' ? 'selected' : '' ?>>Cerai Mati</option>
                        </select>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="mt-8 flex justify-end gap-3">
        <a href="<?= base_url('dashboard/jemaat') ?>" class="px-8 py-4 bg-slate-100 text-slate-600 font-bold rounded-2xl hover:bg-slate-200 transition-all">Batal</a>
        <button type="submit" class="px-10 py-4 bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-bold rounded-2xl shadow-xl shadow-indigo-200 hover:shadow-indigo-300 hover:-translate-y-1 transition-all">Simpan Data Jemaat</button>
    </div>
</form>

<script>
    document.getElementById('fotoInput').addEventListener('change', function(e) {
        const preview = document.getElementById('imagePreview');
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                preview.innerHTML = `<img src="${event.target.result}" class="w-full h-full object-cover">`;
            }
            reader.readAsDataURL(file);
        }
    });
</script>

<?= $this->endSection() ?>
