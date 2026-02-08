<?= $this->extend('layouts/frontend') ?>

<?= $this->section('content') ?>

<div class="bg-primary pt-10 pb-16 border-b border-white/5 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#D4AF37 0.5px, transparent 0.5px); background-size: 20px 20px;"></div>
    <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center relative z-10" data-aos="fade-down">
        <span class="text-[9px] font-bold uppercase tracking-[0.4em] text-accent mb-3 block">Persekutuan Lewat Kata</span>
        <h1 class="text-xl md:text-2xl font-extrabold text-white font-heading">Ruang Diskusi</h1>
        <p class="text-slate-400 mt-3 font-medium max-w-xl mx-auto italic text-xs">Sarana untuk bertanya, berdiskusi, dan berbagi pemikiran mengenai iman dan kehidupan bergereja.</p>
        
        <button onclick="document.getElementById('newTopicModal').classList.remove('hidden')" class="mt-6 px-6 py-2.5 bg-accent text-primary text-xs font-bold rounded-xl hover:bg-gold-light transition-all shadow-lg hover:-translate-y-1">
            Mulai Diskusi Baru
        </button>
    </div>
</div>

<div class="max-w-7xl mx-auto px-6 lg:px-8 -mt-8 mb-12 relative z-10">
    <!-- Search Bar -->
    <form action="" method="get" class="mb-12" data-aos="fade-up" data-aos-delay="100">
        <div class="relative max-w-lg mx-auto">
            <input type="text" name="keyword" value="<?= esc(service('request')->getGet('keyword')) ?>" placeholder="Cari topik diskusi..." 
                   class="w-full pl-6 pr-14 py-4 rounded-full bg-white shadow-lg shadow-primary/5 border border-slate-100 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all placeholder:text-slate-400 font-medium">
            <button type="submit" class="absolute right-2 top-2 p-2 w-10 h-10 flex items-center justify-center bg-primary text-white rounded-full hover:bg-slate-800 transition-colors shadow-md shadow-primary/20">
                <ion-icon name="search" class="text-lg"></ion-icon>
            </button>
        </div>
    </form>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="bg-green-100 border border-green-200 text-green-800 px-6 py-4 rounded-3xl mb-8 flex items-center shadow-lg" data-aos="zoom-in">
             <ion-icon name="checkmark-circle" class="text-2xl mr-3"></ion-icon>
             <span class="font-bold"><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>

    <div class="space-y-4">
        <?php if(empty($topics)): ?>
            <div class="bg-white p-16 rounded-[40px] shadow-xl text-center text-slate-400 font-medium italic border border-slate-100" data-aos="zoom-in">
                Belum ada topik diskusi. Jadilah yang pertama memulai!
            </div>
        <?php else: ?>
            <?php foreach($topics as $index => $t): ?>
            <a href="<?= base_url('diskusi/'.$t['id_diskusi']) ?>" class="group block bg-white p-6 rounded-[32px] shadow-lg shadow-primary/5 border border-slate-100 transition-all duration-500 hover:-translate-y-2 hover:shadow-xl" data-aos="fade-up" data-aos-delay="<?= $index * 100 ?>">
                <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                    <div class="flex-grow space-y-3">
                        <div class="flex items-center space-x-3 text-[10px] text-accent font-bold uppercase tracking-widest">
                             <div class="flex items-center space-x-1">
                                <ion-icon name="person-circle" class="text-base"></ion-icon> 
                                <span><?= $t['penulis'] ?></span>
                             </div>
                             <span class="opacity-30">•</span>
                             <span><?= date('d M Y', strtotime($t['created_at'])) ?></span>
                        </div>
                        
                        <h3 class="text-base md:text-lg font-extrabold text-primary group-hover:text-accent transition-colors font-heading leading-tight">
                            <?= $t['judul'] ?>
                        </h3>
                        
                        <p class="text-slate-500 text-xs line-clamp-2 font-medium leading-relaxed">
                            <?= strip_tags($t['isi']) ?>
                        </p>
                    </div>
                    
                    <!-- Response Count (Always Visible) -->
                    <div class="flex items-center justify-between md:flex-col md:justify-center md:items-end md:space-y-1 pt-4 md:pt-0 border-t md:border-0 border-slate-50 mt-2 md:mt-0">
                         <div class="flex items-center space-x-2 text-slate-400 group-hover:text-primary transition-colors">
                            <ion-icon name="chatbubbles" class="text-xl"></ion-icon>
                            <span class="font-black text-sm"><?= $t['total_jawaban'] ?></span>
                            <span class="text-[10px] font-bold uppercase tracking-wider md:hidden">Tanggapan</span>
                         </div>
                         <div class="hidden md:block text-[10px] text-slate-400 font-bold uppercase tracking-wider">Tanggapan</div>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Diskusi Baru -->
<div id="newTopicModal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-primary/60 backdrop-blur-md" onclick="document.getElementById('newTopicModal').classList.add('hidden')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl px-6">
        <div class="bg-white rounded-[40px] shadow-3xl overflow-hidden border border-slate-200">
            <div class="p-8 md:p-12">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-3xl font-bold text-primary font-heading">Mulai Diskusi</h2>
                    <button onclick="document.getElementById('newTopicModal').classList.add('hidden')" class="text-slate-300 hover:text-primary transition-colors text-3xl">
                        <ion-icon name="close-circle-outline"></ion-icon>
                    </button>
                </div>
                <form action="<?= base_url('diskusi/submit_topic') ?>" method="POST" class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-xs font-black uppercase tracking-widest text-slate-400 ml-1">Nama Anda</label>
                        <input type="text" name="penulis" required class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-2 focus:ring-accent focus:bg-white outline-none transition-all font-bold placeholder:text-slate-300" placeholder="Masukkan nama panggilan...">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black uppercase tracking-widest text-slate-400 ml-1">Topik Utama</label>
                        <input type="text" name="judul" required class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-2 focus:ring-accent focus:bg-white outline-none transition-all font-bold placeholder:text-slate-300" placeholder="Apa yang ingin Anda bahas?">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black uppercase tracking-widest text-slate-400 ml-1">Pesan / Pertanyaan</label>
                        <textarea name="isi" required rows="4" class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-2 focus:ring-accent focus:bg-white outline-none transition-all font-bold placeholder:text-slate-300" placeholder="Tuliskan isi diskusi secara detail..."></textarea>
                    </div>
                    <button type="submit" class="w-full py-5 bg-primary text-accent font-black rounded-2xl shadow-xl shadow-primary/20 hover:bg-slate-800 transition-all hover:-translate-y-1">
                        KIRIM DISKUSI
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
