<?= $this->extend('layouts/frontend') ?>

<?= $this->section('content') ?>

<section class="py-10 px-4 min-h-screen bg-[#F8FAFC]">
    <div class="max-w-3xl mx-auto relative">
        <!-- Breadcrumb / Back -->
        <div class="mb-6 ml-2" data-aos="fade-down">
            <a href="<?= base_url('kidung') ?>" class="inline-flex items-center space-x-2 text-slate-400 hover:text-[#D4AF37] transition-colors font-black uppercase tracking-widest text-[10px] md:text-[11px]">
                <ion-icon name="arrow-back" class="text-sm"></ion-icon>
                <span>Kembali ke Daftar</span>
            </a>
        </div>

        <!-- Content Card -->
        <div class="bg-white rounded-[40px] shadow-2xl shadow-slate-200/50 overflow-hidden relative" data-aos="fade-up">
            <!-- Header Decor (Gold gradient bar on top) -->
            <div class="h-3 w-4/5 mx-auto rounded-b-3xl bg-gradient-to-r from-yellow-300 via-yellow-100 to-yellow-300 shadow-sm relative z-20"></div>
            
            <!-- Side Social Icons (matches screenshot) -->
            <div class="absolute left-3 top-1/2 -translate-y-1/2 flex-col gap-5 text-[#E1C15A] text-[22px] hidden md:flex opacity-90 z-20">
                <a href="#" class="hover:scale-110 hover:text-yellow-600 transition-transform drop-shadow-sm"><ion-icon name="logo-youtube"></ion-icon></a>
                <a href="#" class="hover:scale-110 hover:text-yellow-600 transition-transform drop-shadow-sm"><ion-icon name="logo-instagram"></ion-icon></a>
                <a href="#" class="hover:scale-110 hover:text-yellow-600 transition-transform drop-shadow-sm"><ion-icon name="logo-facebook"></ion-icon></a>
                <a href="#" class="hover:scale-110 hover:text-yellow-600 transition-transform drop-shadow-sm"><ion-icon name="logo-tiktok"></ion-icon></a>
                <a href="#" class="hover:scale-110 hover:text-yellow-600 transition-transform drop-shadow-sm"><ion-icon name="logo-whatsapp"></ion-icon></a>
            </div>

            <div class="px-6 py-12 md:p-16 relative z-10">
                <!-- Title Area -->
                <div class="text-center mb-12 space-y-6">
                    <!-- Pill -->
                    <div class="inline-flex items-center px-6 py-2.5 rounded-full bg-[#FFFbf0] border border-yellow-100/50 text-[#D4AF37] font-black text-[10px] md:text-sm uppercase tracking-[0.2em] shadow-sm">
                        Kidung Jemaat No. <?= $song['nomor'] ?>
                    </div>
                    
                    <!-- Title -->
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-[#1B263B] leading-tight italic tracking-wider px-2" style="font-family: ui-sans-serif, system-ui, sans-serif;">
                        <?= esc(mb_strtoupper($song['judul'])) ?>
                    </h1>
                    
                    <!-- Nada Dasar -->
                    <?php if($song['nada_dasar']): ?>
                        <div class="flex items-center justify-center space-x-2 text-[#8B9AAE] mt-4">
                            <ion-icon name="musical-note" class="text-yellow-500 text-lg"></ion-icon>
                            <span class="text-xs md:text-sm font-black uppercase tracking-[0.2em]"><?= esc($song['nada_dasar']) ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Lyrics Area -->
                <div class="text-center selection:bg-yellow-200/50 max-w-xl mx-auto pb-4">
                    <?php
                    $lines = explode("\n", $song['isi']);
                    $formatted_lyrics = '';
                    foreach ($lines as $line) {
                        $trim_line = trim($line);
                        
                        if (empty($trim_line)) {
                            $formatted_lyrics .= '<div class="h-6"></div>'; // Spacing between stanzas
                            continue;
                        }
                        
                        // Identify verse headers
                        if (preg_match('/^(Ayat|Bait)\s*\d+/i', $trim_line) || 
                            preg_match('/^Reff/i', $trim_line) || 
                            preg_match('/^Refrein/i', $trim_line) ||
                            preg_match('/^\d+\s*\./', $trim_line) ||
                            preg_match('/^\[.*\]$/', $trim_line)) {
                            
                            $formatted_lyrics .= '<div class="font-extrabold text-slate-500/80 text-[15px] md:text-lg mt-10 mb-3">' . esc($trim_line) . '</div>';
                        } else {
                            // Regular lyric lines
                            $formatted_lyrics .= '<div class="text-[#334155] leading-[2] font-semibold text-[16px] md:text-[18px] tracking-wide">' . esc($trim_line) . '</div>';
                        }
                    }
                    echo $formatted_lyrics;
                    ?>
                </div>

                <!-- Footer info -->
                <?php if($song['pengarang']): ?>
                    <div class="mt-16 pt-8 border-t border-slate-100 text-center">
                        <p class="text-[10px] text-slate-300 font-bold uppercase tracking-[0.3em]">Pengarang / Karya</p>
                        <p class="text-slate-500 font-bold mt-2 text-sm max-w-md mx-auto"><?= esc($song['pengarang']) ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Quick Nav -->
        <div class="mt-8 mb-4 flex justify-between items-center px-2" data-aos="fade-up">
            <?php if($song['nomor'] > 1): ?>
                <a href="<?= base_url('kidung/' . ($song['nomor'] - 1)) ?>" class="flex items-center space-x-3 text-slate-400 hover:text-[#D4AF37] transition-all font-black text-[10px] uppercase tracking-wider group">
                    <div class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center group-hover:bg-[#FCF8E8] transition-colors border border-slate-100">
                        <ion-icon name="chevron-back" class="text-xl"></ion-icon>
                    </div>
                    <span class="hidden sm:inline">KJ <?= $song['nomor'] - 1 ?></span>
                </a>
            <?php else: ?>
                <span></span>
            <?php endif; ?>

            <a href="<?= base_url('kidung/' . ($song['nomor'] + 1)) ?>" class="flex items-center space-x-3 text-slate-400 hover:text-[#D4AF37] transition-all font-black text-[10px] uppercase tracking-wider group">
                <span class="hidden sm:inline">KJ <?= $song['nomor'] + 1 ?></span>
                <div class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center group-hover:bg-[#FCF8E8] transition-colors border border-slate-100">
                    <ion-icon name="chevron-forward" class="text-xl"></ion-icon>
                </div>
            </a>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
