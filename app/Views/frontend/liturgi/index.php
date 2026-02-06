<?= $this->extend('layouts/frontend') ?>

<?= $this->section('content') ?>

<!-- Warta-Style Hero Section -->
<div class="bg-primary pt-12 pb-12 md:pt-20 md:pb-20 border-b border-white/5 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#D4AF37 0.5px, transparent 0.5px); background-size: 20px 20px;"></div>
    <div class="max-w-7xl mx-auto px-4 md:px-8 text-center relative z-10" data-aos="fade-down">
        <span class="text-[9px] md:text-[10px] font-bold uppercase tracking-[0.4em] text-accent mb-2 md:mb-3 block"><?= $gereja['nama_gereja'] ?></span>
        <h1 class="text-3xl md:text-5xl font-extrabold text-white font-heading">Liturgi & Tata Ibadah</h1>
        <p class="text-slate-400 mt-2 md:mt-3 text-[10px] md:text-sm font-medium max-w-xl mx-auto italic">
            <?php
                $tgl = date('D, d M Y');
                echo str_replace(
                    ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                    $tgl
                );
            ?>
        </p>
    </div>
</div>

<div class="max-w-6xl mx-auto px-4 md:px-8 -mt-6 md:-mt-10 mb-12 space-y-8 md:space-y-12 relative z-10">
    
    <!-- Search Bar -->
    <form action="" method="get" class="mb-4 md:mb-8" data-aos="fade-up" data-aos-delay="100">
        <div class="relative max-w-lg mx-auto">
            <input type="text" name="keyword" value="<?= esc(service('request')->getGet('keyword')) ?>" placeholder="Cari liturgi..." 
                   class="w-full pl-6 pr-14 py-4 rounded-full bg-white shadow-lg shadow-primary/5 border border-slate-100 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all placeholder:text-slate-400 font-medium">
            <button type="submit" class="absolute right-2 top-2 p-2 w-10 h-10 flex items-center justify-center bg-primary text-white rounded-full hover:bg-slate-800 transition-colors shadow-md shadow-primary/20">
                <ion-icon name="search" class="text-lg"></ion-icon>
            </button>
        </div>
    </form>
    
    <?php if(empty($liturgi)): ?>
    <div class="bg-white rounded-[24px] md:rounded-[40px] shadow-2xl shadow-primary/5 p-12 text-center border border-slate-100" data-aos="fade-up">
        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
            <ion-icon name="file-tray-outline" class="text-4xl"></ion-icon>
        </div>
        <h3 class="text-lg font-bold text-slate-800 mb-2">Belum Ada Liturgi Aktif</h3>
        <p class="text-slate-400 text-xs">Arsip liturgi untuk periode ini belum tersedia.</p>
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
    <!-- Category Block (Warta Style Card) -->
    <div class="bg-white rounded-[24px] md:rounded-[40px] shadow-2xl shadow-primary/5 overflow-hidden border border-slate-100" data-aos="fade-up">
        <!-- Header -->
        <div class="p-5 md:p-8 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-primary flex items-center justify-center text-accent shadow-lg shadow-primary/20">
                    <ion-icon name="<?= $icon ?>-outline" class="text-xl"></ion-icon>
                </div>
                <div>
                    <h3 class="text-base md:text-xl font-extrabold text-primary font-heading"><?= $category ?></h3>
                    <p class="text-[8px] md:text-[9px] font-bold text-slate-400 uppercase tracking-widest"><?= count($items) ?> Arsip Tersedia</p>
                </div>
            </div>
            <div class="hidden md:block">
                <div class="h-px w-24 bg-gradient-to-r from-slate-200 to-transparent"></div>
            </div>
        </div>

        <!-- Liturgy List -->
        <div class="p-2 md:p-4 grid grid-cols-1 md:grid-cols-2 gap-2 md:gap-4">
            <?php foreach($items as $l): ?>
            <a href="<?= base_url('liturgi/'.$l['id_liturgi'] . '?from=list') ?>" class="group block p-4 md:p-6 rounded-2xl md:rounded-[24px] hover:bg-slate-50 transition-all duration-300 border border-transparent hover:border-slate-100">
                <div class="flex justify-between items-start">
                    <div class="flex-grow">
                        <div class="flex items-center space-x-2 mb-2">
                             <div class="h-px w-4 bg-accent group-hover:w-8 transition-all"></div>
                             <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">
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
                        <h4 class="text-sm md:text-base font-extrabold text-primary group-hover:text-accent transition-colors leading-tight"><?= $l['judul'] ?></h4>
                    </div>
                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 group-hover:bg-primary group-hover:text-accent transition-all">
                        <ion-icon name="arrow-forward-outline" class="text-lg"></ion-icon>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>

</div>

<?= $this->endSection() ?>
