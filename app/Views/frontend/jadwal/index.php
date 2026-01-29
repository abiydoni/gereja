<?= $this->extend('layouts/frontend') ?>

<?= $this->section('content') ?>

<div class="bg-primary pt-10 pb-16 border-b border-white/5 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#D4AF37 0.5px, transparent 0.5px); background-size: 20px 20px;"></div>
    <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center relative z-10" data-aos="fade-down">
        <span class="text-[9px] font-bold uppercase tracking-[0.4em] text-accent mb-3 block">Wancining Pangibadah</span>
        <h1 class="text-3xl md:text-4xl font-extrabold text-white font-heading">Jadwal Ibadah</h1>
        <p class="text-slate-400 mt-3 font-medium max-w-xl mx-auto italic text-xs md:text-sm">Mangga sami memuji asmanipun Gusti ing wanci ingkang sampun katemtokaken.</p>
    </div>
</div>

<div class="max-w-5xl mx-auto px-6 lg:px-8 -mt-8 mb-12 relative z-10">
    <div class="bg-white rounded-xl shadow-2xl shadow-primary/5 overflow-hidden border border-slate-100" data-aos="fade-up">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50 text-slate-500 uppercase text-[8px] font-bold tracking-[0.2em] border-b border-slate-100">
                        <th class="px-1.5 py-2">Pangibadah</th>
                        <th class="px-1.5 py-2">Wanci</th>
                        <th class="px-1.5 py-2">Papan</th>
                        <th class="px-1.5 py-2">Katrangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php if(empty($jadwal)): ?>
                        <tr>
                            <td colspan="4" class="px-8 py-16 text-center text-slate-400 italic font-medium">Dereng wonten jadwal pangibadah.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($jadwal as $j): ?>
                        <tr class="hover:bg-slate-50/50 transition duration-300 group">
                            <td class="px-1.5 py-2">
                                <h3 class="text-[10px] uppercase font-bold text-primary font-heading tracking-tight leading-none group-hover:text-accent transition-colors"><?= $j['nama_ibadah'] ?></h3>
                            </td>
                            <td class="px-1.5 py-2">
                                <div class="flex flex-col">
                                    <span class="text-[8px] font-bold text-primary uppercase tracking-widest leading-none mb-0.5"><?= $j['hari'] ?></span>
                                    <div class="flex items-center text-slate-400 text-[10px] font-medium leading-none">
                                        <ion-icon name="time-outline" class="mr-1 text-accent text-[8px]"></ion-icon>
                                        <?= date('H:i', strtotime($j['jam'])) ?> WIB
                                    </div>
                                </div>
                            </td>
                            <td class="px-1.5 py-2">
                                <div class="flex items-center text-slate-600 font-bold text-[10px] leading-none">
                                    <ion-icon name="location-outline" class="mr-1 text-accent text-[8px]"></ion-icon>
                                    <?= $j['lokasi'] ?>
                                </div>
                            </td>
                            <td class="px-1.5 py-2">
                                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter leading-none max-w-xs" style="-webkit-text-size-adjust: none;">
                                    <?= $j['keterangan'] ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="p-8 bg-slate-50 border-t border-slate-100 text-center">
            <p class="text-slate-400 text-[10px] font-bold uppercase tracking-[0.3em]">Jadwal saged ewah saged ugi mawi kabar sanesipun.</p>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
