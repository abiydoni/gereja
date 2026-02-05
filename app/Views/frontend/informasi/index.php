<?= $this->extend('layouts/frontend') ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="bg-primary pt-24 pb-32 border-b border-white/5 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#D4AF37 0.5px, transparent 0.5px); background-size: 20px 20px;"></div>
    <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center relative z-10" data-aos="fade-down">
        <span class="text-xs font-bold uppercase tracking-[0.4em] text-accent mb-4 block">Warta & Pengumuman</span>
        <h1 class="text-2xl md:text-3xl font-extrabold text-white font-heading">Informasi Gereja</h1>
        <p class="text-slate-400 mt-4 font-medium max-w-xl mx-auto text-xs">Berita terkini, jadwal kegiatan khusus, dan pengumuman penting untuk jemaat.</p>
    </div>
</section>

<!-- Content Section -->
<div class="max-w-7xl mx-auto px-6 lg:px-8 -mt-16 mb-24 relative z-10">
    <div class="space-y-8">
        <?php if(empty($informasi)): ?>
            <div class="bg-white p-16 rounded-[40px] shadow-xl text-center text-slate-400 font-medium italic border border-slate-100" data-aos="zoom-in">
                Belum ada informasi yang diterbitkan.
            </div>
        <?php else: ?>
            <?php foreach($informasi as $index => $info): ?>
            <div class="bg-white rounded-[40px] shadow-xl shadow-primary/5 border border-slate-100 overflow-hidden transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl" data-aos="fade-up" data-aos-delay="<?= $index * 100 ?>">
                 <div class="md:flex items-stretch">
                     <?php if($info['gambar']): ?>
                     <div class="md:w-1/3 relative overflow-hidden h-64 md:h-auto">
                         <img class="w-full h-full object-cover transition-transform duration-700 hover:scale-110" src="<?= base_url('uploads/informasi/'.$info['gambar']) ?>" alt="<?= $info['judul'] ?>">
                         <div class="absolute inset-0 bg-gradient-to-t from-primary/60 via-transparent to-transparent md:hidden"></div>
                     </div>
                     <?php endif; ?>
                     
                     <div class="p-8 md:p-12 md:flex-1 flex flex-col justify-center">
                         <div class="flex items-center space-x-3 text-[10px] text-accent font-bold uppercase tracking-[0.2em] mb-4">
                             <ion-icon name="time-outline" class="text-lg"></ion-icon> 
                             <span><?= date('d F Y', strtotime($info['created_at'])) ?></span>
                         </div>
                         <h3 class="text-lg md:text-xl font-bold text-primary hover:text-accent transition-colors font-heading leading-tight mb-6">
                             <?= $info['judul'] ?>
                         </h3>
                         <div class="text-slate-500 font-medium leading-relaxed whitespace-pre-line text-xs mb-4">
                             <?= $info['deskripsi'] ?>
                         </div>
                     </div>
                 </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
