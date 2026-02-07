<?= $this->extend('layouts/frontend') ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="relative bg-primary pt-16 pb-12 md:pt-20 md:pb-16 overflow-hidden">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-accent/10 rounded-full blur-[120px] animate-pulse-slow"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-primary/20 rounded-full blur-[120px] animate-pulse-slow" style="animation-delay: 2s;"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 md:px-6 lg:px-8 flex flex-col md:flex-row items-center">
        <div class="w-full md:w-3/5 text-center md:text-left space-y-5" data-aos="fade-right">
            <div class="inline-flex items-center space-x-2 px-2 py-1 rounded-full bg-accent/10 border border-accent/20">
                <span class="flex h-1.5 w-1.5 rounded-full bg-accent animate-ping"></span>
                <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-accent">Selamat Datang</span>
            </div>
            
            <h1 class="text-3xl md:text-5xl font-extrabold text-white font-heading leading-tight tracking-tight">
                Bertumbuh & <br>
                <span class="text-gradient">Melayani Sesama</span>
            </h1>
            
            <p class="text-sm md:text-base text-slate-400 max-w-xl leading-relaxed font-medium">
                <?= $gereja['deskripsi'] ?>
            </p>
            
            <div class="flex flex-col sm:flex-row gap-2 pt-2 justify-center md:justify-start flex-wrap">
                <?php if(isset($config['menu_warta'])): ?>
                <a href="<?= base_url('warta') ?>" class="px-5 py-2.5 bg-indigo-600 text-white text-xs font-bold rounded-xl transition hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 border border-indigo-500/50 active:scale-95 text-center">
                    Warta Gereja
                </a>
                <?php endif; ?>
                <?php if(isset($config['menu_renungan'])): ?>
                <a href="#renungan" class="px-5 py-2.5 btn-gold text-primary text-xs font-bold rounded-xl transition hover:scale-105 active:scale-95 text-center">
                     Baca Renungan
                </a>
                <?php endif; ?>
                <?php if(isset($config['menu_jadwal'])): ?>
                <a href="#jadwal" class="px-5 py-2.5 bg-white/5 text-white text-xs font-bold rounded-xl transition hover:bg-white/10 border border-white/10 active:scale-95 text-center">
                    Jadwal Ibadah
                </a>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="w-full md:w-2/5 mt-8 md:mt-0 flex justify-center" data-aos="zoom-in" data-aos-delay="200">
        <div class="relative w-40 h-40 md:w-56 md:h-56">
            <!-- Floating Decorative Shapes -->
            <div class="absolute -inset-6 bg-gradient-to-tr from-accent/40 via-accent/5 to-transparent rounded-[40px] rotate-6 animate-float opacity-50"></div>
            <div class="absolute -inset-6 bg-gradient-to-bl from-accent/20 via-transparent to-accent/30 rounded-[40px] -rotate-3 animate-float opacity-30" style="animation-delay: -3s;"></div>
            
            <?php if(!empty($gereja['logo'])): ?>
                <div class="relative w-full h-full bg-white/5 backdrop-blur-3xl rounded-[32px] shadow-2xl overflow-hidden border border-white/10 flex items-center justify-center p-6 group">
                    <div class="absolute inset-0 bg-gradient-to-br from-accent/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                    <img src="<?= base_url('uploads/'.$gereja['logo']) ?>" alt="Church Logo" class="max-w-full h-auto drop-shadow-[0_20px_50px_rgba(0,0,0,0.5)] group-hover:scale-105 transition-transform duration-700 gold-filter" style="display: block !important; filter: brightness(0) saturate(100%) invert(72%) sepia(51%) saturate(544%) hue-rotate(355deg) brightness(91%) contrast(88%) !important; -webkit-filter: brightness(0) saturate(100%) invert(72%) sepia(51%) saturate(544%) hue-rotate(355deg) brightness(91%) contrast(88%) !important;">
                </div>
            <?php else: ?>
                <div class="relative w-full h-full bg-secondary rounded-[32px] shadow-2xl overflow-hidden border border-white/10 flex items-center justify-center">
                     <ion-icon name="business" class="text-[60px] text-accent animate-pulse-slow opacity-20"></ion-icon>
                </div>
            <?php endif; ?>
        </div>
        </div>
    </div>
    
    <!-- Scroll Down Icon -->
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 hidden md:block animate-bounce text-slate-500">
        <ion-icon name="arrow-down-outline" class="text-2xl"></ion-icon>
    </div>
</section>

<!-- Quick Stats Overlay -->
<section class="relative -mt-8 z-10 px-6 max-w-7xl mx-auto hidden md:block">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-0 bg-white rounded-3xl shadow-lg shadow-primary/5 border border-slate-100 overflow-hidden divide-x divide-slate-100">
        
        <!-- Instagram -->
        <a href="<?= !empty($gereja['ig']) ? $gereja['ig'] : '#' ?>" target="_blank" class="flex items-center space-x-4 p-4 hover:bg-pink-50 transition-colors group cursor-pointer">
            <div class="w-10 h-10 rounded-xl bg-pink-50 text-pink-500 flex items-center justify-center text-xl group-hover:scale-110 group-hover:bg-pink-100 transition-all">
                <ion-icon name="logo-instagram"></ion-icon>
            </div>
            <div>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest group-hover:text-pink-500 transition-colors">Instagram</p>
                <p class="text-xs font-bold text-primary">Follow Kami</p>
            </div>
        </a>

        <!-- Facebook -->
        <a href="<?= !empty($gereja['fb']) ? $gereja['fb'] : '#' ?>" target="_blank" class="flex items-center space-x-4 p-4 hover:bg-blue-50 transition-colors group cursor-pointer">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl group-hover:scale-110 group-hover:bg-blue-100 transition-all">
                <ion-icon name="logo-facebook"></ion-icon>
            </div>
            <div>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest group-hover:text-blue-600 transition-colors">Facebook</p>
                <p class="text-xs font-bold text-primary">Gabung Grup</p>
            </div>
        </a>

        <!-- TikTok -->
        <a href="<?= !empty($gereja['tt']) ? $gereja['tt'] : '#' ?>" target="_blank" class="flex items-center space-x-4 p-4 hover:bg-slate-50 transition-colors group cursor-pointer">
            <div class="w-10 h-10 rounded-xl bg-slate-100 text-black flex items-center justify-center text-xl group-hover:scale-110 group-hover:bg-slate-200 transition-all">
                <ion-icon name="logo-tiktok"></ion-icon>
            </div>
            <div>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest group-hover:text-slate-800 transition-colors">TikTok</p>
                <p class="text-xs font-bold text-primary">Tonton Video</p>
            </div>
        </a>

        <!-- YouTube -->
        <a href="<?= !empty($gereja['yt']) ? $gereja['yt'] : base_url('galeri') ?>" target="_blank" class="flex items-center space-x-4 p-4 hover:bg-red-50 transition-colors group cursor-pointer">
            <div class="w-10 h-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center text-xl group-hover:scale-110 group-hover:bg-red-100 transition-all">
                <ion-icon name="logo-youtube"></ion-icon>
            </div>
            <div>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest group-hover:text-red-600 transition-colors">YouTube</p>
                <p class="text-xs font-bold text-primary">Subscribe</p>
            </div>
        </a>

    </div>
</section>

<!-- Renungan Section -->
<?php if($renungan && isset($config['menu_renungan'])): ?>
<section id="renungan" class="py-8 px-6 overflow-hidden">
    <div class="max-w-5xl mx-auto">
        <div class="flex flex-col md:flex-row items-start md:items-end justify-between mb-6 gap-4" data-aos="fade-up">
            <div class="space-y-3">
                <div class="flex items-center space-x-2">
                    <div class="h-1 w-6 bg-accent"></div>
                    <span class="text-[10px] font-bold uppercase tracking-[0.3em] text-accent">Renungan Hari Ini</span>
                </div>
                <h2 class="text-3xl md:text-4xl font-extrabold text-primary font-heading">Sabda Panglipur</h2>
            </div>
            <p class="text-slate-400 font-bold text-base"><?= date('d F Y', strtotime($renungan['tanggal'])) ?></p>
        </div>
        
        <div class="relative" data-aos="fade-up" data-aos-delay="200">
            <div class="absolute -top-10 -left-10 text-9xl text-slate-100 group-hover:text-accent/5 transition duration-500 select-none">
                <ion-icon name="chatbubbles-outline"></ion-icon>
            </div>
            
            <div class="relative bg-white p-5 md:p-8 rounded-[32px] shadow-xl shadow-primary/5 border border-slate-100 overflow-hidden group">
                <div class="absolute top-0 right-0 w-1 md:w-1.5 h-full bg-accent"></div>
                
                <div class="flex flex-col md:flex-row gap-6 md:gap-8 items-center md:items-start">
                    <?php if(!empty($renungan['gambar'])): ?>
                    <div class="w-full md:w-48 flex-shrink-0">
                        <div class="aspect-video md:aspect-square rounded-2xl overflow-hidden relative shadow-lg">
                            <img src="<?= base_url('uploads/renungan/'.$renungan['gambar']) ?>" alt="<?= $renungan['judul'] ?>" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                            <div class="absolute inset-0 bg-gradient-to-t from-primary/30 to-transparent"></div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="flex-grow">
                        <h3 class="text-2xl md:text-3xl font-extrabold font-heading text-primary mb-4 group-hover:text-accent transition duration-300">
                            <?= $renungan['judul'] ?>
                        </h3>
                        
                        <div class="prose prose-sm max-w-none text-slate-600 leading-relaxed font-medium line-clamp-6 text-xs md:text-sm">
                            <?= nl2br($renungan['isi']) ?>
                        </div>
                        
                        <div class="mt-6">
                            <a href="<?= base_url('renungan/arsip') ?>" class="inline-flex items-center space-x-2 text-primary font-bold uppercase tracking-widest text-[10px] hover:text-accent transition-all group/link">
                                <span>Lihat Semua Renungan</span>
                                <ion-icon name="arrow-forward" class="text-base group-hover/link:translate-x-2 transition-transform"></ion-icon>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Jadwal Ibadah Section -->
<?php if(isset($config['menu_jadwal'])): ?>
<section id="jadwal" class="py-8 px-6 bg-slate-50">
    <div class="max-w-5xl mx-auto">
        <div class="flex flex-col md:flex-row items-start md:items-end justify-between mb-6 gap-4" data-aos="fade-up">
            <div class="space-y-3">
                <span class="text-[10px] font-bold uppercase tracking-[0.4em] text-accent">Ibadah Pasamuwan</span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-primary font-heading tracking-tight">Jadwal Pangibadah</h2>
            </div>
             <p class="text-slate-400 font-medium text-xs text-right hidden md:block">Minggu & Sepekan</p>
        </div>

        <?php 
        $jadwalMinggu = [];
        $jadwalLain = [];
        foreach($jadwal as $j) {
            if(stripos($j['hari'], 'Minggu') !== false) {
                $jadwalMinggu[] = $j;
            } else {
                $jadwalLain[] = $j;
            }
        }
        ?>

        <!-- SECTION 1: Sunday Services (Centered & Compact) -->
        <?php if(!empty($jadwalMinggu)): ?>
        <div class="mb-10">
            <h3 class="text-center text-base font-bold text-slate-400 uppercase tracking-widest mb-4">Ibadah Minggu</h3>
            <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3 justify-center">
                <?php foreach($jadwalMinggu as $index => $j): ?>
                <div class="group bg-white rounded-xl p-3 md:p-4 border border-slate-100 shadow-md shadow-primary/5 hover:shadow-lg hover:shadow-primary/10 transition-all duration-300 flex flex-col h-full" data-aos="zoom-in" data-aos-delay="<?= $index * 100 ?>">
                    <div class="flex items-center space-x-3 mb-2">
                        <div class="w-7 h-7 rounded-lg bg-primary flex items-center justify-center text-accent flex-shrink-0 group-hover:rotate-6 transition-transform">
                            <ion-icon name="calendar" class="text-sm"></ion-icon>
                        </div>
                        <h3 class="text-sm font-bold text-primary font-heading leading-tight"><?= $j['nama_ibadah'] ?></h3>
                    </div>
                    
                    <div class="space-y-1 flex-grow">
                        <div class="flex items-center text-slate-500 font-bold text-[8px] uppercase tracking-widest">
                            <ion-icon name="today-outline" class="mr-1.5 text-accent text-[10px]"></ion-icon>
                            <span><?= $j['hari'] ?></span>
                        </div>
                        <div class="flex items-center text-slate-500 font-bold">
                             <ion-icon name="time-outline" class="mr-1.5 text-accent text-[10px]"></ion-icon>
                            <span class="text-sm text-primary font-heading"><?= date('H:i', strtotime($j['jam'])) ?> <span class="text-[8px] uppercase">WIB</span></span>
                        </div>
                         <div class="flex items-start text-slate-500 font-semibold text-[9px] leading-relaxed">
                            <ion-icon name="location-outline" class="mr-1.5 mt-0.5 text-accent text-xs"></ion-icon>
                            <span class="line-clamp-1"><?= $j['lokasi'] ?></span>
                        </div>
                    </div>
                    
                    <?php if(!empty($j['keterangan'])): ?>
                    <div class="mt-2.5 pt-2.5 border-t border-slate-50">
                        <div class="flex items-start bg-slate-50 p-1.5 rounded-md border border-slate-100">
                            <ion-icon name="information-circle" class="text-accent mr-1.5 mt-0.5 text-[10px]"></ion-icon>
                            <div class="text-[8px] font-bold text-slate-400 uppercase tracking-tighter leading-none line-clamp-2" style="-webkit-text-size-adjust: none;"><?= $j['keterangan'] ?></div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- SECTION 2: Other Days (List View) -->
        <?php if(!empty($jadwalLain)): ?>
        <div class="w-full">
            <h3 class="text-center text-lg font-bold text-slate-400 uppercase tracking-widest mb-6">Jadwal Sepekan</h3>
            <div class="grid grid-cols-2 md:grid-cols-2 gap-3">
                <?php foreach($jadwalLain as $index => $j): ?>
                <div class="bg-white rounded-2xl p-3 md:p-4 border border-slate-100 shadow-sm hover:shadow-md transition-all group" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex flex-col md:flex-row items-start gap-3 md:gap-4">
                        <div class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 group-hover:bg-accent/10 group-hover:text-accent flex items-center justify-center text-xl transition-colors shrink-0 mt-0 md:mt-1">
                            <ion-icon name="calendar-clear-outline"></ion-icon>
                        </div>
                        <div class="flex-grow">
                            <h4 class="font-bold text-primary group-hover:text-accent transition-colors text-xs"><?= $j['nama_ibadah'] ?></h4>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider flex items-center mt-0.5 mb-2">
                                <span class="text-slate-600 mr-2"><?= $j['hari'] ?></span> • 
                                <span class="ml-2"><?= date('H:i', strtotime($j['jam'])) ?> WIB</span>
                            </p>
                            
                            <!-- Location Text (Bottom) -->
                            <div class="inline-flex items-center text-[10px] font-bold text-slate-500 bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-100">
                                <ion-icon name="location" class="mr-1.5 text-accent"></ion-icon>
                                <?= $j['lokasi'] ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>

<!-- Majelis Info Section -->
<?php if(isset($config['section_organisasi'])): ?>
<section id="majelis" class="py-8 px-6 relative overflow-hidden">
    <div class="max-w-5xl mx-auto">
        <div class="flex flex-col md:flex-row items-start md:items-end justify-between mb-6 gap-4" data-aos="fade-up">
            <div class="space-y-3">
                <span class="text-[10px] font-bold uppercase tracking-[0.4em] text-accent">Struktur Organisasi</span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-primary font-heading">Majelis & Pengurus</h2>
            </div>
            <p class="text-slate-400 font-medium max-w-sm text-center md:text-right text-xs">Para pelayan Tuhan yang setia melayani kebutuhan jemaat.</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
            <?php foreach($majelis as $index => $m): ?>
            <div class="group relative bg-white rounded-xl overflow-hidden border border-slate-100 shadow-sm shadow-primary/5 hover:shadow-lg transition-all duration-500" data-aos="fade-up" data-aos-delay="<?= $index * 50 ?>">
                <div class="aspect-square overflow-hidden relative">
                    <?php if($m['foto']): ?>
                        <img class="w-full h-full object-cover transition duration-700 group-hover:scale-110" src="<?= base_url('uploads/majelis/'.$m['foto']) ?>" alt="<?= $m['nama'] ?>">
                    <?php else: ?>
                        <div class="w-full h-full bg-slate-50 flex items-center justify-center">
                            <ion-icon name="person" class="text-3xl text-slate-200"></ion-icon>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Hover Info Overlay (Micro) -->
                    <div class="absolute inset-0 bg-gradient-to-t from-primary/90 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-2">
                        <a href="https://wa.me/<?= preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $m['no_hp'] ?? '')) ?>" target="_blank" class="w-full py-1.5 bg-accent text-primary text-[10px] font-bold rounded flex items-center justify-center space-x-1 active:scale-95 transition-transform">
                            <ion-icon name="logo-whatsapp"></ion-icon>
                            <span>Chat</span>
                        </a>
                    </div>
                </div>
                
                <div class="p-2 text-center">
                    <p class="text-[8px] text-accent font-bold uppercase tracking-widest mb-0.5 truncate"><?= $m['jabatan'] ?></p>
                    <h3 class="text-xs font-bold text-primary font-heading truncate leading-tight"><?= $m['nama'] ?></h3>
                    <?php if(!empty($m['bidang'])): ?>
                        <p class="text-[8px] text-slate-400 font-bold mt-0.5 truncate">
                            Bidang: <?= $m['bidang'] ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Statistik Jemaat Section -->
<?php if(isset($config['section_statistik_jemaat'])): ?>
<section id="statistik" class="py-8 px-6 bg-slate-50/50">
    <div class="max-w-5xl mx-auto">
        <div class="flex flex-col md:flex-row items-start md:items-end justify-between mb-8 gap-4" data-aos="fade-up">
            <div class="space-y-3">
                <span class="text-[10px] font-bold uppercase tracking-[0.4em] text-accent">Pertumbuhan</span>
                <h2 class="text-2xl md:text-3xl font-extrabold text-primary font-heading">Statistik Jemaat</h2>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Growth Chart -->
            <div class="bg-white p-6 rounded-[32px] shadow-xl shadow-primary/5 border border-slate-100" data-aos="fade-up">
                <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4 flex items-center">
                    <ion-icon name="trending-up" class="mr-2 text-accent"></ion-icon>
                    Pertumbuhan 6 Bln
                </h3>
                <div class="h-40">
                    <canvas id="growthChart"></canvas>
                </div>
            </div>

            <!-- Gender Chart -->
            <div class="bg-white p-6 rounded-[32px] shadow-xl shadow-primary/5 border border-slate-100" data-aos="fade-up" data-aos-delay="100">
                <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4 flex items-center">
                    <ion-icon name="people" class="mr-2 text-accent"></ion-icon>
                    Distribusi Gender
                </h3>
                <div class="h-40">
                    <canvas id="genderChart"></canvas>
                </div>
            </div>

            <!-- Age Chart -->
            <div class="bg-white p-6 rounded-[32px] shadow-xl shadow-primary/5 border border-slate-100" data-aos="fade-up" data-aos-delay="200">
                <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4 flex items-center">
                    <ion-icon name="pie-chart" class="mr-2 text-accent"></ion-icon>
                    Kelompok Usia
                </h3>
                <div class="h-40">
                    <canvas id="ageChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Shared Config
    const fontConfig = {
        family: "'Inter', sans-serif",
        size: 10,
        weight: '600'
    };

    // 1. Growth Chart
    new Chart(document.getElementById('growthChart'), {
        type: 'line',
        data: {
            labels: <?= json_encode(array_keys($stats['growth'])) ?>,
            datasets: [{
                label: 'Total Jemaat',
                data: <?= json_encode(array_values($stats['growth'])) ?>,
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#6366f1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { display: false },
                x: {
                    grid: { display: false },
                    ticks: { font: fontConfig, color: '#94a3b8' }
                }
            }
        }
    });

    // 2. Gender Chart
    new Chart(document.getElementById('genderChart'), {
        type: 'doughnut',
        data: {
            labels: ['Pria', 'Wanita'],
            datasets: [{
                data: [<?= $stats['gender']['pria'] ?>, <?= $stats['gender']['wanita'] ?>],
                backgroundColor: ['#6366f1', '#f43f5e'],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 10,
                        padding: 15,
                        font: fontConfig,
                        color: '#64748b'
                    }
                }
            }
        }
    });

    // 3. Age Chart
    new Chart(document.getElementById('ageChart'), {
        type: 'bar',
        data: {
            labels: ['Anak', 'Remaja', 'Dewasa', 'Lansia'],
            datasets: [{
                data: [
                    <?= $stats['age']['anak'] ?>, 
                    <?= $stats['age']['remaja'] ?>, 
                    <?= $stats['age']['dewasa'] ?>, 
                    <?= $stats['age']['lansia'] ?>
                ],
                backgroundColor: '#fbbf24',
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { display: false },
                x: {
                    grid: { display: false },
                    ticks: { font: fontConfig, color: '#94a3b8' }
                }
            }
        }
    });
});
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>
