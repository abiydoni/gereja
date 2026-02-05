<?= $this->extend('layouts/frontend') ?>

<?= $this->section('content') ?>

<!-- Modern Aesthetics -->
<style>
    :root {
        --glass: rgba(255, 255, 255, 0.7);
        --glass-border: rgba(255, 255, 255, 0.4);
        --accent-glow: rgba(212, 175, 55, 0.15);
    }
    .glass-card {
        background: var(--glass);
        backdrop-filter: blur(12px) saturate(180%);
        border: 1px solid var(--glass-border);
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.07);
    }
    .glass-card:hover {
        background: rgba(255, 255, 255, 0.9);
        transform: translateY(-5px);
        box-shadow: 0 15px 45px 0 rgba(31, 38, 135, 0.12);
        border-color: rgba(212, 175, 55, 0.3);
    }
    .animate-float {
        animation: float 6s ease-in-out infinite;
    }
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    .gradient-text {
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .dark .glass-card {
        background: rgba(30, 41, 59, 0.7);
        border-color: rgba(255, 255, 255, 0.05);
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.2);
    }
    .dark .gradient-text {
        background: linear-gradient(135deg, #f1f5f9 0%, #cbd5e1 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
</style>

<!-- Modern Hero Section -->
<div class="relative pt-20 pb-32 overflow-hidden bg-slate-50 dark:bg-primary">
    <!-- Animated Orbs -->
    <div class="absolute top-0 -left-10 w-72 h-72 bg-accent/20 rounded-full blur-[100px] animate-pulse"></div>
    <div class="absolute bottom-0 -right-10 w-96 h-96 bg-indigo-500/10 rounded-full blur-[120px] animate-float"></div>

    <div class="max-w-7xl mx-auto px-6 relative z-10 text-center">
        <div class="inline-flex items-center space-x-2 px-4 py-1.5 rounded-full bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700 mb-8" data-aos="fade-up">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-accent opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-accent"></span>
            </span>
            <span class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">Digital Liturgy Archive</span>
        </div>
        
        <h1 class="text-3xl md:text-5xl font-black mb-4 tracking-tight gradient-text" data-aos="fade-up" data-aos-delay="100">
            Koleksi Tata Ibadah <br> & Liturgi Pelayanan
        </h1>
        
        <p class="max-w-xl mx-auto text-slate-500 dark:text-slate-400 font-medium text-xs md:text-base leading-relaxed" data-aos="fade-up" data-aos-delay="200">
            Eksplorasi panduan ibadah yang disusun dengan rapi dan modern. 
            Tersedia dalam format digital yang interaktif.
        </p>

        <!-- Search / Filter Placeholder (Optional Visual) -->
        <div class="mt-12 max-w-lg mx-auto relative group" data-aos="fade-up" data-aos-delay="300">
             <div class="absolute -inset-1 bg-gradient-to-r from-accent to-indigo-500 rounded-2xl blur opacity-20 group-hover:opacity-40 transition duration-1000 group-hover:duration-200"></div>
             <div class="relative flex items-center bg-white dark:bg-slate-800 rounded-2xl p-2 shadow-xl border border-slate-100 dark:border-slate-700">
                <ion-icon name="search-outline" class="ml-4 text-xl text-slate-400"></ion-icon>
                <input type="text" placeholder="Cari judul atau tanggal liturgi..." class="w-full px-4 py-3 bg-transparent border-none focus:ring-0 text-sm font-medium text-slate-700 dark:text-slate-200" readonly>
                <button class="bg-primary dark:bg-accent text-white dark:text-primary px-6 py-2.5 rounded-xl text-xs font-bold transition hover:scale-105 active:scale-95">Filter</button>
             </div>
        </div>
    </div>
</div>

<!-- Main Content: Grouped by Category -->
<div class="max-w-7xl mx-auto px-6 py-20 relative z-20">
    <div class="space-y-24">
        <?php if(empty($liturgi)): ?>
            <div class="glass-card p-24 rounded-[40px] text-center" data-aos="zoom-in">
                <div class="w-24 h-24 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                    <ion-icon name="file-tray-outline" class="text-5xl"></ion-icon>
                </div>
                <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-2">Data Belum Tersedia</h3>
                <p class="text-slate-400 text-sm">Belum ada liturgi yang dipublikasikan saat ini.</p>
            </div>
        <?php else: 
            // Grouping logic
            $grouped = [];
            foreach($liturgi as $l) {
                $kat = $l['kategori'] ?? 'Ibadah Minggu';
                $grouped[$kat][] = $l;
            }

            $catIcons = [
                'Ibadah Minggu' => 'calendar-clear',
                'Ibadah Raya' => 'sunny',
                'Natal' => 'star',
                'Paskah' => 'flame',
                'Pernikahan' => 'heart',
                'Pemakaman' => 'body',
                'Spesial' => 'sparkles'
            ];

            foreach($grouped as $category => $items):
                $icon = $catIcons[$category] ?? 'layers';
        ?>
            <section data-aos="fade-up">
                <!-- Section Header -->
                <div class="flex items-center justify-between mb-10">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-2xl bg-white dark:bg-slate-800 shadow-xl border border-accent/20 flex items-center justify-center text-accent">
                            <ion-icon name="<?= $icon ?>-outline" class="text-2xl"></ion-icon>
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-slate-800 dark:text-white tracking-tight"><?= $category ?></h2>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]"><?= count($items) ?> Arsip Tersedia</p>
                        </div>
                    </div>
                    <div class="h-px flex-grow mx-8 bg-gradient-to-r from-slate-200 dark:from-slate-700 to-transparent"></div>
                </div>

                <!-- Cards Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php foreach($items as $i => $l): ?>
                    <a href="<?= base_url('liturgi/bi/'.$l['id_liturgi']) ?>" 
                       class="glass-card group p-8 rounded-[32px] transition-all duration-500"
                       data-aos="fade-up" data-aos-delay="<?= $i * 50 ?>">
                        
                        <div class="flex flex-col h-full">
                            <!-- Date Badge -->
                            <div class="flex items-center justify-between mb-6">
                                <div class="px-3 py-1 rounded-lg bg-slate-100 dark:bg-slate-800/50 text-[9px] font-bold text-slate-500 dark:text-slate-400 flex items-center space-x-2">
                                    <ion-icon name="time-outline"></ion-icon>
                                    <span>
                                        <?php
                                            $tgl = date('d M Y', strtotime($l['tanggal']));
                                            echo str_replace(
                                                ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                                                ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                                                $tgl
                                            );
                                        ?>
                                    </span>
                                </div>
                                <div class="w-8 h-8 rounded-lg bg-accent/10 flex items-center justify-center text-accent opacity-0 group-hover:opacity-100 transition-all scale-50 group-hover:scale-100">
                                    <ion-icon name="reader-outline"></ion-icon>
                                </div>
                            </div>

                            <!-- Title -->
                            <h3 class="text-sm md:text-base font-black text-slate-800 dark:text-white group-hover:text-accent transition-colors leading-tight mb-4 flex-grow">
                                <?= $l['judul'] ?>
                            </h3>

                            <!-- Footer -->
                            <div class="flex items-center justify-between pt-6 border-t border-slate-100 dark:border-slate-800/50">
                                <div class="flex -space-x-2">
                                     <!-- Decorative avatars/markers -->
                                     <div class="w-6 h-6 rounded-full bg-indigo-500 border-2 border-white dark:border-slate-800 flex items-center justify-center text-[8px] text-white font-bold">P</div>
                                     <div class="w-6 h-6 rounded-full bg-emerald-500 border-2 border-white dark:border-slate-800 flex items-center justify-center text-[8px] text-white font-bold">J</div>
                                </div>
                                <div class="text-[10px] font-bold text-accent dark:text-accent/80 flex items-center space-x-1 group-hover:translate-x-2 transition-transform">
                                    <span>Buka Liturgi</span>
                                    <ion-icon name="arrow-forward"></ion-icon>
                                </div>
                            </div>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
