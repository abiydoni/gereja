<?= $this->extend('layouts/backend') ?>

<?= $this->section('content') ?>

<!-- Welcome Section -->
<div class="mb-10" data-aos="fade-down">
    <h1 class="font-heading font-extrabold text-3xl text-slate-800 mb-2">Selamat Datang, Admin! 👋</h1>
    <p class="text-slate-500 text-lg">Berikut ringkasan aktivitas gereja terbaru.</p>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
    <!-- Renungan Card -->
    <div class="relative group bg-white p-8 rounded-[32px] shadow-xl shadow-indigo-100/50 border border-slate-100 overflow-hidden transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl">
        <div class="absolute top-0 right-0 p-8 opacity-10 group-hover:scale-125 transition-transform duration-500">
            <ion-icon name="book" class="text-9xl text-indigo-600"></ion-icon>
        </div>
        <div class="relative z-10">
            <div class="w-14 h-14 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 mb-6 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300 shadow-sm">
                <ion-icon name="book" class="text-2xl"></ion-icon>
            </div>
            <p class="text-slate-500 font-bold text-sm uppercase tracking-wider mb-1">Total Renungan</p>
            <h3 class="font-heading font-extrabold text-4xl text-slate-800"><?= $total_renungan ?></h3>
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-1.5 bg-gradient-to-r from-indigo-400 to-purple-400 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500"></div>
    </div>

    <!-- Jadwal Card -->
    <div class="relative group bg-white p-8 rounded-[32px] shadow-xl shadow-emerald-100/50 border border-slate-100 overflow-hidden transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl">
        <div class="absolute top-0 right-0 p-8 opacity-10 group-hover:scale-125 transition-transform duration-500">
            <ion-icon name="calendar" class="text-9xl text-emerald-600"></ion-icon>
        </div>
        <div class="relative z-10">
            <div class="w-14 h-14 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 mb-6 group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-300 shadow-sm">
                <ion-icon name="calendar" class="text-2xl"></ion-icon>
            </div>
            <p class="text-slate-500 font-bold text-sm uppercase tracking-wider mb-1">Jadwal Ibadah</p>
            <h3 class="font-heading font-extrabold text-4xl text-slate-800"><?= $total_jadwal ?></h3>
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-1.5 bg-gradient-to-r from-emerald-400 to-teal-400 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500"></div>
    </div>

    <!-- Jemaat Card -->
    <div class="relative group bg-white p-8 rounded-[32px] shadow-xl shadow-amber-100/50 border border-slate-100 overflow-hidden transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl">
        <div class="absolute top-0 right-0 p-8 opacity-10 group-hover:scale-125 transition-transform duration-500">
            <ion-icon name="people" class="text-9xl text-amber-600"></ion-icon>
        </div>
        <div class="relative z-10">
            <div class="w-14 h-14 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-600 mb-6 group-hover:bg-amber-600 group-hover:text-white transition-colors duration-300 shadow-sm">
                <ion-icon name="people" class="text-2xl"></ion-icon>
            </div>
            <p class="text-slate-500 font-bold text-sm uppercase tracking-wider mb-1">Jemaat Terdaftar</p>
            <h3 class="font-heading font-extrabold text-4xl text-slate-800">1,250</h3> 
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-1.5 bg-gradient-to-r from-amber-400 to-orange-400 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500"></div>
    </div>
</div>

<!-- Church Profile Widget -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    <!-- Main Info Card -->
    <div class="lg:col-span-2 bg-gradient-to-br from-primary to-slate-900 rounded-[32px] shadow-2xl overflow-hidden relative text-white group">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(#D4AF37 1px, transparent 1px); background-size: 24px 24px;"></div>
        <div class="absolute top-0 right-0 w-64 h-64 bg-accent/20 rounded-full blur-[80px] -translate-y-1/2 translate-x-1/2"></div>
        
        <div class="relative z-10 p-10 h-full flex flex-col justify-between">
            <div>
                <div class="flex items-center space-x-3 mb-6">
                    <div class="px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-xs font-bold text-accent tracking-wider uppercase">
                        Terverifikasi
                    </div>
                    </div>
                <h2 class="font-heading font-extrabold text-3xl md:text-4xl leading-tight mb-4 tracking-tight">
                    <?= $gereja['nama_gereja'] ?>
                </h2>
                <div class="flex items-start space-x-3 text-slate-300 text-lg max-w-xl">
                    <ion-icon name="location" class="text-accent text-2xl mt-1 flex-shrink-0"></ion-icon>
                    <p class="font-medium leading-relaxed"><?= $gereja['alamat'] ?></p>
                </div>
            </div>

            <div class="mt-10 pt-8 border-t border-white/10 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="h-10 w-10 rounded-full bg-white/10 flex items-center justify-center backdrop-blur-sm">
                        <ion-icon name="logo-instagram" class="text-xl"></ion-icon>
                    </div>
                    <div class="h-10 w-10 rounded-full bg-white/10 flex items-center justify-center backdrop-blur-sm">
                        <ion-icon name="logo-facebook" class="text-xl"></ion-icon>
                    </div>
                    <div class="h-10 w-10 rounded-full bg-white/10 flex items-center justify-center backdrop-blur-sm">
                        <ion-icon name="logo-youtube" class="text-xl"></ion-icon>
                    </div>
                    <span class="text-xs text-slate-400 italic ml-2">Media sosial terhubung</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Moto / Description Card -->
    <div class="bg-white rounded-[32px] shadow-xl border border-slate-100 p-8 flex flex-col justify-center relative overflow-hidden">
        <div class="absolute top-0 right-0 opacity-5 -rotate-12 translate-x-1/4 -translate-y-1/4">
            <ion-icon name="chatbubbles" class="text-[150px] text-primary"></ion-icon>
        </div>
        
        <div class="relative z-10">
            <label class="block text-xs font-bold text-accent uppercase tracking-[0.2em] mb-4">Tentang Kami</label>
            <div class="space-y-4">
                <p class="text-xl font-heading font-bold text-slate-800 leading-relaxed italic">
                    "<?= $gereja['deskripsi'] ?>"
                </p>
                <div class="w-12 h-1 bg-accent rounded-full"></div>
                <p class="text-sm text-slate-500 font-medium">
                    Melayani dengan kasih dan membangun jemaat yang bertumbuh dalam iman.
                </p>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
