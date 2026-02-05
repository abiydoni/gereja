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
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php if(empty($kegiatan)): ?>
            <div class="col-span-full bg-white p-16 rounded-[40px] shadow-xl text-center text-slate-400 font-medium italic border border-slate-100" data-aos="zoom-in">
                Dereng wonten kegiatan ingkang kacathet.
            </div>
        <?php else: ?>
            <?php foreach($kegiatan as $index => $k): ?>
            <div class="group bg-white rounded-[40px] shadow-xl shadow-primary/5 overflow-hidden border border-slate-100 flex flex-col h-full transition-all duration-500 hover:-translate-y-2" data-aos="fade-up" data-aos-delay="<?= $index * 100 ?>">
                 <div class="p-10 flex-grow space-y-6">
                     <div class="flex items-center space-x-2">
                        <div class="w-10 h-10 rounded-xl bg-accent/10 text-accent flex items-center justify-center">
                            <ion-icon name="calendar" class="text-xl"></ion-icon>
                        </div>
                        <span class="text-[10px] font-bold text-accent uppercase tracking-[0.2em]"><?= date('d M Y', strtotime($k['tanggal_mulai'])) ?></span>
                     </div>
                     
                     <h3 class="text-sm font-extrabold text-primary font-heading group-hover:text-accent transition-colors line-clamp-2">
                         <?= $k['nama_kegiatan'] ?>
                     </h3>
                     
                     <div class="text-slate-600 text-xs leading-relaxed font-medium prose prose-slate line-clamp-3">
                         <?= $k['deskripsi'] ?>
                     </div>
                 </div>
                 
                 <div class="bg-slate-50/50 p-6 border-t border-slate-100 grid grid-cols-2 gap-3">
                     <div class="flex items-center text-slate-500 font-bold">
                         <ion-icon name="time-outline" class="mr-2 text-accent text-lg transition-transform group-hover:rotate-12"></ion-icon>
                         <span class="text-[10px]"><?= date('H:i', strtotime($k['tanggal_mulai'])) ?> <span class="text-[8px] uppercase font-bold text-slate-400">WIB</span></span>
                     </div>
                     <div class="flex items-center text-slate-500 font-bold">
                         <ion-icon name="location-outline" class="mr-2 text-accent text-lg transition-transform group-hover:translate-y-[-2px]"></ion-icon>
                         <span class="text-[10px] truncate"><?= $k['lokasi'] ?></span>
                     </div>
                 </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
