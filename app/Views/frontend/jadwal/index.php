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
    <div class="bg-white rounded-[40px] shadow-2xl shadow-primary/5 overflow-hidden border border-slate-100" data-aos="fade-up">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50 text-slate-500 uppercase text-[9px] font-bold tracking-[0.2em] border-b border-slate-100">
                        <th class="px-6 py-4">Pangibadah</th>
                        <th class="px-6 py-4">Wanci</th>
                        <th class="px-6 py-4">Papan</th>
                        <th class="px-6 py-4">Katrangan</th>
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
                            <td class="px-6 py-6">
                                <h3 class="text-lg font-extrabold text-primary font-heading group-hover:text-accent transition-colors"><?= $j['nama_ibadah'] ?></h3>
                            </td>
                            <td class="px-6 py-6">
                                <div class="flex flex-col space-y-1">
                                    <span class="text-[10px] font-bold text-primary uppercase tracking-widest"><?= $j['hari'] ?></span>
                                    <div class="flex items-center text-slate-400 text-xs font-medium">
                                        <ion-icon name="time-outline" class="mr-2 text-accent"></ion-icon>
                                        <?= date('H:i', strtotime($j['jam'])) ?> WIB
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-6">
                                <div class="flex items-center text-slate-600 font-bold text-xs">
                                    <ion-icon name="location-outline" class="mr-2 text-accent"></ion-icon>
                                    <?= $j['lokasi'] ?>
                                </div>
                            </td>
                            <td class="px-6 py-6">
                                <p class="text-[9px] text-slate-500 italic font-medium leading-relaxed max-w-xs">
                                    <?= $j['keterangan'] ?>
                                </p>
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
