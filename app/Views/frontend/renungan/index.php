<?= $this->extend('layouts/frontend') ?>

<?= $this->section('content') ?>

<div class="bg-primary pt-10 pb-16 border-b border-white/5 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#D4AF37 0.5px, transparent 0.5px); background-size: 20px 20px;"></div>
    <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center relative z-10" data-aos="fade-down">
        <span class="text-[9px] font-bold uppercase tracking-[0.4em] text-accent mb-3 block">Santapan Rohani</span>
        <h1 class="text-xl md:text-2xl font-extrabold text-white font-heading"><?= esc($title) ?></h1>
        <p class="text-slate-400 mt-3 font-medium max-w-xl mx-auto italic text-xs">Renungkan firman Tuhan setiap hari untuk pertumbuhan iman kita.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-6 lg:px-8 -mt-8 mb-12 relative z-10">
    <!-- Search Bar -->
    <form action="" method="get" class="mb-12" data-aos="fade-up" data-aos-delay="100">
        <div class="relative max-w-lg mx-auto">
            <input type="text" name="keyword" value="<?= esc(service('request')->getGet('keyword')) ?>" placeholder="Cari judul atau isi renungan..." 
                   class="w-full pl-6 pr-14 py-4 rounded-full bg-white shadow-lg shadow-primary/5 border border-slate-100 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all placeholder:text-slate-400 font-medium">
            <button type="submit" class="absolute right-2 top-2 p-2 w-10 h-10 flex items-center justify-center bg-primary text-white rounded-full hover:bg-slate-800 transition-colors shadow-md shadow-primary/20">
                <ion-icon name="search" class="text-lg"></ion-icon>
            </button>
        </div>
    </form>
    <?php if(empty($renungan)): ?>
        <div class="bg-white p-16 rounded-[40px] shadow-xl text-center text-slate-400 font-medium italic border border-slate-100" data-aos="zoom-in">
            Belum ada renungan yang diterbitkan.
        </div>
    <?php else: ?>
        <?php 
            $latest = $renungan[0];
            $others = array_slice($renungan, 1);
        ?>

        <!-- Featured Renungan (Latest) - Style Matches Warta -->
        <div class="bg-white rounded-[24px] md:rounded-[40px] shadow-2xl shadow-primary/5 overflow-hidden mb-12 border border-slate-100" data-aos="fade-up">
             <div class="p-5 md:p-12 border-b border-slate-50 flex flex-col md:flex-row justify-between items-start md:items-center gap-3 md:gap-4">
                <div class="text-left w-full">
                    <div class="flex items-center justify-start space-x-2 mb-1">
                         <div class="h-px w-3 md:w-5 bg-accent"></div>
                         <span class="text-[8px] md:text-[9px] font-bold uppercase tracking-widest text-accent">Renungan Terbaru</span>
                    </div>
                    <h3 class="text-base md:text-2xl font-extrabold text-primary font-heading leading-tight">
                        <a href="<?= base_url('renungan/'.$latest['id_renungan']) ?>" class="hover:text-accent transition-colors">
                            <?= esc($latest['judul']) ?>
                        </a>
                    </h3>
                </div>
                <div class="flex items-center space-x-2 bg-slate-50 px-2 py-1 md:px-3 md:py-1.5 rounded-lg md:rounded-xl border border-slate-100 self-start md:self-auto">
                    <ion-icon name="calendar-outline" class="text-accent text-[10px] md:text-sm"></ion-icon>
                    <span class="text-[8px] md:text-[9px] font-extrabold text-slate-500 uppercase tracking-widest leading-none"><?= date('d F Y', strtotime($latest['tanggal'])) ?></span>
                </div>
            </div>
            
            <?php if(!empty($latest['gambar'])): ?>
            <div class="w-full h-48 md:h-96 relative overflow-hidden group">
                 <a href="<?= base_url('renungan/'.$latest['id_renungan']) ?>">
                     <img src="<?= base_url('uploads/renungan/'.$latest['gambar']) ?>" alt="<?= $latest['judul'] ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                     <div class="absolute inset-0 bg-gradient-to-t from-white via-white/80 to-transparent"></div>
                 </a>
            </div>
            <?php endif; ?>
            
            <div class="px-5 md:px-12 pb-5 md:pb-12 <?php if(!empty($latest['gambar'])) echo '-mt-24 md:-mt-32 relative z-10'; ?>">
                 <div class="prose prose-xs md:prose-sm max-w-none text-slate-700 leading-snug font-medium prose-p:my-1.5 prose-headings:mb-1.5 prose-headings:mt-3 text-[9px] md:text-sm">
                     <?= $latest['isi'] ?>
                 </div>
                 <div class="mt-4 md:mt-6">
                     <a href="<?= base_url('renungan/'.$latest['id_renungan']) ?>" class="inline-flex items-center space-x-2 text-primary font-bold uppercase tracking-widest text-[9px] md:text-xs hover:text-accent transition-all group/link">
                        <span>Baca Selengkapnya</span>
                        <ion-icon name="arrow-forward" class="text-sm group-hover/link:translate-x-2 transition-transform"></ion-icon>
                    </a>
                 </div>
            </div>
        </div>

        <!-- Other Renungan List -->
        <?php if(!empty($others)): ?>
        <div class="bg-white rounded-[24px] shadow-xl shadow-primary/5 border border-slate-100 divide-y divide-slate-100 overflow-hidden">
            <div class="p-4 bg-slate-50/50 border-b border-slate-100">
                <h4 class="text-xs font-bold text-slate-500 uppercase tracking-widest">Arsip Renungan</h4>
            </div>
            <?php foreach($others as $index => $r): ?>
            <a href="<?= base_url('renungan/'.$r['id_renungan']) ?>" class="block hover:bg-slate-50 transition-colors group p-3 flex items-center space-x-4" data-aos="fade-up" data-aos-delay="100">
                <!-- Thumbnail -->
                <div class="w-12 h-12 md:w-14 md:h-14 shrink-0 rounded-xl overflow-hidden bg-slate-100 relative shadow-sm border border-slate-200/50">
                    <?php if(!empty($r['gambar'])): ?>
                        <img src="<?= base_url('uploads/renungan/'.$r['gambar']) ?>" alt="<?= $r['judul'] ?>" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center text-accent/50">
                            <ion-icon name="book" class="text-xl"></ion-icon>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Text Content -->
                <div class="flex-grow min-w-0 py-0.5">
                    <h3 class="text-xs md:text-sm font-bold text-slate-700 font-heading truncate group-hover:text-primary transition-colors">
                        <?= esc($r['judul']) ?>
                    </h3>
                    <div class="flex items-center space-x-2 mt-1">
                        <span class="text-[9px] md:text-[10px] font-bold uppercase tracking-wider text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full inline-flex items-center">
                            <ion-icon name="calendar-outline" class="mr-1"></ion-icon>
                            <?= date('d M Y', strtotime($r['tanggal'])) ?>
                        </span>
                    </div>
                </div>

                <!-- Action Icon -->
                <div class="text-slate-300 group-hover:text-accent group-hover:translate-x-1 transition-all">
                    <ion-icon name="chevron-forward-outline" class="text-lg"></ion-icon>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        
        <!-- Pagination -->
        <?php if (isset($pager) && $pager->getPageCount() > 1): ?>
            <div class="mt-8">
                <?= $pager->links('renungan', 'frontend_full') ?>
            </div>
        <?php endif; ?>
        <?php endif; ?>

    <?php endif; ?>
</div>

<?= $this->endSection() ?>
