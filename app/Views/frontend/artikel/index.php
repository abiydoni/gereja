<?= $this->extend('layouts/frontend') ?>

<?= $this->section('content') ?>

<div class="bg-primary pt-24 pb-32 border-b border-white/5 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#D4AF37 0.5px, transparent 0.5px); background-size: 20px 20px;"></div>
    <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center relative z-10" data-aos="fade-down">
        <span class="text-[9px] font-bold uppercase tracking-[0.4em] text-accent mb-3 block">Warta & Inspirasi</span>
        <h1 class="text-xl md:text-2xl font-extrabold text-white font-heading">Artikel & Berita</h1>
        <p class="text-slate-400 mt-3 font-medium max-w-xl mx-auto text-xs">Kumpulan tulisan inspiratif, warta gereja, dan kabar pelayanan terbaru.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-6 lg:px-8 -mt-16 mb-24 relative z-10">
    <!-- Search Bar -->
    <form action="" method="get" class="mb-8" data-aos="fade-up" data-aos-delay="100">
        <div class="relative max-w-lg mx-auto">
            <input type="text" name="keyword" value="<?= esc(service('request')->getGet('keyword')) ?>" placeholder="Cari judul atau isi artikel..." 
                   class="w-full pl-6 pr-14 py-4 rounded-full bg-white shadow-lg shadow-primary/5 border border-slate-100 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all placeholder:text-slate-400 font-medium">
            <button type="submit" class="absolute right-2 top-2 p-2 w-10 h-10 flex items-center justify-center bg-primary text-white rounded-full hover:bg-slate-800 transition-colors shadow-md shadow-primary/20">
                <ion-icon name="search" class="text-lg"></ion-icon>
            </button>
        </div>
    </form>

    <?php if(empty($artikels)): ?>
        <div class="bg-white p-12 rounded-[32px] shadow-xl text-center text-slate-400 font-medium italic border border-slate-100" data-aos="zoom-in">
            Belum ada artikel yang diterbitkan.
        </div>
    <?php else: ?>
        <div class="bg-white rounded-[24px] shadow-xl shadow-primary/5 border border-slate-100 divide-y divide-slate-100 overflow-hidden">
            <?php foreach($artikels as $index => $a): ?>
            <a href="<?= base_url('artikel/'.$a['slug']) ?>" class="block hover:bg-slate-50 transition-colors group p-3 flex items-center space-x-4" data-aos="fade-up" data-aos-delay="<?= min($index * 50, 500) ?>">
                <!-- Thumbnail -->
                <div class="w-12 h-12 md:w-14 md:h-14 shrink-0 rounded-xl overflow-hidden bg-slate-100 relative shadow-sm border border-slate-200/50">
                    <?php if($a['gambar']): ?>
                        <img src="<?= base_url('uploads/artikel/'.$a['gambar']) ?>" alt="<?= $a['judul'] ?>" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center text-accent/50">
                            <ion-icon name="image" class="text-xl"></ion-icon>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Text Content -->
                <div class="flex-grow min-w-0 py-0.5">
                    <h3 class="text-xs md:text-sm font-bold text-slate-700 font-heading truncate group-hover:text-primary transition-colors">
                        <?= $a['judul'] ?>
                    </h3>
                    <div class="flex items-center space-x-2 mt-1">
                        <span class="text-[9px] md:text-[10px] font-bold uppercase tracking-wider text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full inline-flex items-center">
                            <ion-icon name="calendar-outline" class="mr-1"></ion-icon>
                            <?= date('d M Y', strtotime($a['created_at'])) ?>
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
            <div class="mt-12">
                <?= $pager->links('artikel', 'frontend_full') ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
