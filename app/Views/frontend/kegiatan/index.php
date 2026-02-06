<?= $this->extend('layouts/frontend') ?>

<?= $this->section('content') ?>

<div class="bg-primary pt-10 pb-16 border-b border-white/5 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#D4AF37 0.5px, transparent 0.5px); background-size: 20px 20px;"></div>
    <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center relative z-10" data-aos="fade-down">
        <span class="text-[9px] font-bold uppercase tracking-[0.4em] text-accent mb-3 block">Prastawa Ingkang Badhe Lumampah</span>
        <h1 class="text-xl md:text-2xl font-extrabold text-white font-heading">Kegiatan Gereja</h1>
        <p class="text-slate-400 mt-3 font-medium max-w-xl mx-auto italic text-xs">Berbagai agenda dan persiapan kegiatan pasamuwan.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-6 lg:px-8 -mt-8 mb-12 relative z-10">
    <!-- Search Bar -->
    <form action="" method="get" class="mb-12" data-aos="fade-up" data-aos-delay="100">
        <div class="relative max-w-lg mx-auto">
            <input type="text" name="keyword" value="<?= esc(service('request')->getGet('keyword')) ?>" placeholder="Cari kegiatan..." 
                   class="w-full pl-6 pr-14 py-4 rounded-full bg-white shadow-lg shadow-primary/5 border border-slate-100 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all placeholder:text-slate-400 font-medium">
            <button type="submit" class="absolute right-2 top-2 p-2 w-10 h-10 flex items-center justify-center bg-primary text-white rounded-full hover:bg-slate-800 transition-colors shadow-md shadow-primary/20">
                <ion-icon name="search" class="text-lg"></ion-icon>
            </button>
        </div>
    </form>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if(empty($kegiatan)): ?>
            <div class="col-span-full bg-white p-12 rounded-[32px] shadow-xl text-center text-slate-400 font-medium italic border border-slate-100" data-aos="zoom-in">
                Dereng wonten kegiatan ingkang kacathet.
            </div>
        <?php else: ?>
            <?php foreach($kegiatan as $index => $k): ?>
            <div class="group bg-white rounded-[32px] shadow-lg shadow-primary/5 overflow-hidden border border-slate-100 flex flex-col h-full transition-all duration-500 hover:-translate-y-2 hover:shadow-xl" data-aos="fade-up" data-aos-delay="<?= $index * 100 ?>">
                 <!-- Content -->
                 <div class="p-6 flex-grow space-y-4">
                     <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-accent/10 text-accent flex items-center justify-center shrink-0">
                            <ion-icon name="calendar" class="text-lg"></ion-icon>
                        </div>
                        <span class="text-[10px] font-bold text-accent uppercase tracking-widest"><?= date('d M Y', strtotime($k['tanggal_mulai'])) ?></span>
                     </div>
                     
                     <h3 class="text-base font-extrabold text-primary font-heading group-hover:text-accent transition-colors leading-tight line-clamp-2">
                         <?= $k['nama_kegiatan'] ?>
                     </h3>
                     
                     <div class="text-slate-500 text-xs leading-relaxed line-clamp-2">
                         <?= $k['deskripsi'] ?>
                     </div>
                 </div>
                 
                 <!-- Footer Meta -->
                 <div class="bg-slate-50/50 px-6 py-4 border-t border-slate-100 grid grid-cols-2 gap-2 text-[10px] font-bold text-slate-500">
                     <div class="flex items-center">
                         <ion-icon name="time-outline" class="mr-2 text-accent text-base"></ion-icon>
                         <span><?= date('H:i', strtotime($k['tanggal_mulai'])) ?> WIB</span>
                     </div>
                     <div class="flex items-center">
                         <ion-icon name="location-outline" class="mr-2 text-accent text-base"></ion-icon>
                         <span class="truncate"><?= $k['lokasi'] ?></span>
                     </div>
                 </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <!-- Pagination -->
    <?php if (isset($pager) && $pager->getPageCount() > 1): ?>
        <div class="mt-12">
            <?= $pager->links('kegiatan', 'frontend_full') ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
