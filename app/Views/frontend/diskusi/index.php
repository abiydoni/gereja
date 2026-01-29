<?= $this->extend('layouts/frontend') ?>

<?= $this->section('content') ?>

<div class="bg-primary pt-24 pb-32 border-b border-white/5 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#D4AF37 0.5px, transparent 0.5px); background-size: 20px 20px;"></div>
    <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center relative z-10" data-aos="fade-down">
        <span class="text-xs font-bold uppercase tracking-[0.4em] text-accent mb-4 block">Persekutuan Lewat Kata</span>
        <h1 class="text-5xl md:text-6xl font-extrabold text-white font-heading">Ruang Diskusi</h1>
        <p class="text-slate-400 mt-4 font-medium max-w-xl mx-auto">Sarana untuk bertanya, berdiskusi, dan berbagi pemikiran mengenai iman dan kehidupan bergereja.</p>
        
        <button onclick="document.getElementById('newTopicModal').classList.remove('hidden')" class="mt-8 px-8 py-3 bg-accent text-primary font-bold rounded-2xl hover:bg-gold-light transition-all shadow-lg hover:-translate-y-1">
            Mulai Diskusi Baru
        </button>
    </div>
</div>

<div class="max-w-5xl mx-auto px-6 lg:px-8 -mt-16 mb-24 relative z-10">
    <?php if(session()->getFlashdata('success')): ?>
        <div class="bg-green-100 border border-green-200 text-green-800 px-6 py-4 rounded-3xl mb-8 flex items-center shadow-lg" data-aos="zoom-in">
             <ion-icon name="checkmark-circle" class="text-2xl mr-3"></ion-icon>
             <span class="font-bold"><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>

    <div class="space-y-6">
        <?php if(empty($topics)): ?>
            <div class="bg-white p-16 rounded-[40px] shadow-xl text-center text-slate-400 font-medium italic border border-slate-100" data-aos="zoom-in">
                Belum ada topik diskusi. Jadilah yang pertama memulai!
            </div>
        <?php else: ?>
            <?php foreach($topics as $index => $t): ?>
            <a href="<?= base_url('diskusi/'.$t['id_diskusi']) ?>" class="group block bg-white p-8 md:p-10 rounded-[40px] shadow-xl shadow-primary/5 border border-slate-100 transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl" data-aos="fade-up" data-aos-delay="<?= $index * 100 ?>">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="space-y-4">
                        <div class="flex items-center space-x-3 text-[10px] text-accent font-bold uppercase tracking-[0.2em]">
                             <ion-icon name="person-circle" class="text-lg"></ion-icon> 
                             <span><?= $t['penulis'] ?></span>
                             <span class="opacity-30">•</span>
                             <span><?= date('d M Y', strtotime($t['created_at'])) ?></span>
                        </div>
                        <h3 class="text-2xl md:text-3xl font-extrabold text-primary group-hover:text-accent transition-colors font-heading leading-tight"><?= $t['judul'] ?></h3>
                        <p class="text-slate-500 text-sm line-clamp-2 font-medium leading-relaxed max-w-2xl"><?= strip_tags($t['isi']) ?></p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-right hidden md:block">
                            <span class="block text-2xl font-black text-primary font-heading"><?= $t['total_jawaban'] ?></span>
                            <span class="text-[10px] text-slate-400 uppercase font-black tracking-widest leading-none">Jawaban</span>
                        </div>
                        <div class="h-14 w-14 shrink-0 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400 group-hover:bg-primary group-hover:text-accent transition-all duration-500 shadow-sm">
                            <ion-icon name="chatbubble-ellipses" class="text-2xl group-hover:scale-110 transition-transform"></ion-icon>
                        </div>
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
