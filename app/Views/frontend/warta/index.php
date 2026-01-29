<?= $this->extend('layouts/frontend') ?>

<?= $this->section('content') ?>

<div class="bg-primary pt-8 pb-8 md:pt-12 md:pb-12 border-b border-white/5 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#D4AF37 0.5px, transparent 0.5px); background-size: 20px 20px;"></div>
    <div class="max-w-7xl mx-auto px-4 md:px-8 text-center relative z-10" data-aos="fade-down">
        <span class="text-[9px] md:text-[10px] font-bold uppercase tracking-[0.4em] text-accent mb-2 md:mb-3 block"><?= $gereja['nama_gereja'] ?></span>
        <h1 class="text-2xl md:text-5xl font-extrabold text-white font-heading">Warta Gereja</h1>
        <p class="text-slate-400 mt-2 md:mt-3 text-[10px] md:text-sm font-medium max-w-xl mx-auto italic"><?= str_replace(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'], ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'], date('D, d M Y')) ?></p>
    </div>
</div>

<div class="max-w-6xl mx-auto px-4 md:px-8 -mt-6 md:-mt-8 mb-8 md:mb-12 space-y-6 md:space-y-10 relative z-10">
    
    <!-- Renungan (Top) -->
    <?php if($renungan): ?>
    <div class="bg-white rounded-[24px] md:rounded-[40px] shadow-2xl shadow-primary/5 overflow-hidden mb-8 md:mb-16" data-aos="fade-up">
         <div class="p-5 md:p-12 border-b border-slate-50 flex flex-col md:flex-row justify-between items-start md:items-center gap-3 md:gap-4">
            <div class="text-left w-full">
                <div class="flex items-center justify-start space-x-2 mb-1">
                     <div class="h-px w-3 md:w-5 bg-accent"></div>
                     <span class="text-[8px] md:text-[9px] font-bold uppercase tracking-widest text-accent">Renungan Hari Ini</span>
                </div>
                <h3 class="text-base md:text-2xl font-extrabold text-primary font-heading leading-tight"><?= $renungan['judul'] ?></h3>
            </div>
            <div class="flex items-center space-x-2 bg-slate-50 px-2 py-1 md:px-3 md:py-1.5 rounded-lg md:rounded-xl border border-slate-100 self-start md:self-auto">
                <ion-icon name="calendar-outline" class="text-accent text-[10px] md:text-sm"></ion-icon>
                <span class="text-[8px] md:text-[9px] font-extrabold text-slate-500 uppercase tracking-widest leading-none"><?= date('d F Y', strtotime($renungan['tanggal'])) ?></span>
            </div>
        </div>
        
        <?php if(!empty($renungan['gambar'])): ?>
        <div class="w-full h-48 md:h-96 relative overflow-hidden group">
             <img src="<?= base_url('uploads/renungan/'.$renungan['gambar']) ?>" alt="<?= $renungan['judul'] ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
             <div class="absolute inset-0 bg-gradient-to-t from-white via-white/80 to-transparent"></div>
        </div>
        <?php endif; ?>
        
        <div class="px-5 md:px-12 pb-5 md:pb-12 <?php if(!empty($renungan['gambar'])) echo '-mt-24 md:-mt-32 relative z-10'; ?>">
             <div class="prose prose-xs md:prose-sm max-w-none text-slate-700 leading-snug font-medium prose-p:my-1.5 prose-headings:mb-1.5 prose-headings:mt-3 text-[9px] md:text-sm">
                 <?= nl2br($renungan['isi']) ?>
             </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Jadwal Petugas (Table Layout) -->
    <?php if(!empty($jadwalList)): ?>
    <div class="bg-white rounded-[20px] md:rounded-[40px] shadow-2xl shadow-primary/5 overflow-hidden border border-slate-100 mb-8 md:mb-16" data-aos="fade-up">
        
        <div class="p-4 md:p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row justify-between md:items-center gap-2">
             <h3 class="text-lg md:text-xl font-extrabold text-primary font-heading flex items-center gap-2">
                 <ion-icon name="calendar-outline"></ion-icon> Jadwal Ibadah
             </h3>
             <span class="text-[8px] md:text-[10px] font-bold text-slate-400 uppercase tracking-widest">Mingguan</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-primary/5 text-primary uppercase text-[8px] md:text-xs font-bold tracking-wider">
                        <th class="px-2 py-2 md:px-6 md:py-4 text-center border-r border-slate-200 w-1/3">06:00</th>
                        <th class="px-2 py-2 md:px-6 md:py-4 text-center border-r border-slate-200 w-1/3">09:00</th>
                        <th class="px-2 py-2 md:px-6 md:py-4 text-center w-1/3">17:00</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php 
                        // Group by Date
                        $groupedByDate = [];
                        foreach($jadwalList as $j) {
                            $dateKey = date('Y-m-d', strtotime($j['tanggal']));
                            $groupedByDate[$dateKey][] = $j;
                        }

                        foreach($groupedByDate as $date => $schedules): 
                            // Sort unique times for this date into slots
                            $slots = [
                                '06:00' => null,
                                '09:00' => null,
                                '17:00' => null
                            ];

                            foreach($schedules as $s) {
                                $hour = (int)date('H', strtotime($s['jam']));
                                if ($hour < 9) $slots['06:00'] = $s;
                                elseif ($hour < 15) $slots['09:00'] = $s;
                                else $slots['17:00'] = $s;
                            }
                    ?>
                    
                    <!-- Date Header Row (Sticky) -->
                    <tr class="bg-slate-50 sticky top-0 z-10 border-b border-slate-100">
                        <td colspan="3" class="px-4 py-2 md:px-6 md:py-3 text-center">
                            <span class="text-[10px] md:text-sm font-extrabold text-slate-700 uppercase tracking-widest">
                                <?php
                                    $day = date('l', strtotime($date));
                                    $fullDate = date('d F Y', strtotime($date));
                                    $indoDay = str_replace(
                                        ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
                                        ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
                                        $day
                                    );
                                    $indoDate = str_replace(
                                        ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                                        ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                                        $fullDate
                                    );
                                    echo $indoDay . ', ' . $indoDate;
                                ?>
                            </span>
                        </td>
                    </tr>

                    <!-- Time Slots Row -->
                    <tr class="hover:bg-slate-50 transition-colors border-b border-slate-100 last:border-0">
                        <?php foreach(['06:00', '09:00', '17:00'] as $timeKey): $data = $slots[$timeKey]; ?>
                        <td class="px-1.5 py-2 md:px-6 md:py-4 border-r border-slate-100 align-top last:border-0 w-1/3">
                            <?php if($data): ?>
                                <div class="flex flex-col h-full bg-white rounded-lg border border-slate-100 p-1.5 shadow-sm">
                                    <div class="border-b border-dashed border-slate-200 pb-1.5 mb-1.5">
                                        <div class="text-[8px] md:text-xs font-bold text-primary mb-0.5 uppercase tracking-wide leading-tight"><?= $data['nama_ibadah'] ?></div>
                                        <?php if(!empty($data['tema'])): ?>
                                        <div class="text-[8px] md:text-[10px] text-slate-500 font-medium italic leading-tight">
                                            "<?= $data['tema'] ?>"
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <!-- Ultra Compact List of Officers -->
                                    <?php if(!empty($data['petugas'])): ?>
                                    <div class="space-y-1">
                                        <?php foreach($data['petugas'] as $p): 
                                            // Icon logic
                                            $icon = 'person'; // Defaults to Person
                                            $role = strtolower($p['jenis_tugas']);
                                            if(strpos($role, 'kotbah') !== false) $icon = 'mic';
                                            elseif(strpos($role, 'liturgos') !== false) $icon = 'book';
                                            elseif(strpos($role, 'alkitab') !== false) $icon = 'library';
                                            elseif(strpos($role, 'sembahan') !== false) $icon = 'wallet';
                                            elseif(strpos($role, 'musik') !== false) $icon = 'musical-notes';
                                            elseif(strpos($role, 'singer') !== false) $icon = 'mic-circle';
                                        ?>
                                        <div class="flex flex-col leading-none">
                                            <div class="flex items-center gap-1 mb-0.5">
                                                <ion-icon name="<?= $icon ?>-outline" class="text-accent text-[8px] md:text-[10px]"></ion-icon>
                                                <span class="text-[7px] md:text-[9px] font-bold text-slate-400 uppercase tracking-wide truncate"><?= $p['jenis_tugas'] ?></span>
                                            </div>
                                            <span class="text-[8px] md:text-[10px] font-bold text-slate-700 truncate"><?= $p['nama_petugas'] ?></span>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <?php else: ?>
                                        <span class="text-[7px] text-slate-300 italic text-center block mt-2">-</span>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <div class="h-full flex items-center justify-center min-h-[80px] text-slate-100 bg-slate-50/30 rounded-lg border border-dashed border-slate-100">
                                    <span class="text-xl md:text-2xl opacity-20 font-bold">X</span>
                                </div>
                            <?php endif; ?>
                        </td>
                        <?php endforeach; ?>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <!-- Informasi Persembahan (Above Keuangan) -->
    <div class="bg-white rounded-[20px] md:rounded-[32px] shadow-2xl shadow-primary/5 overflow-hidden mb-6 md:mb-10 border border-slate-100" data-aos="fade-up">
        <div class="p-3 md:p-5 border-b border-slate-50 flex justify-between items-center">
            <div>
                <div class="flex items-center space-x-2">
                    <div class="h-px w-4 bg-accent"></div>
                    <span class="text-[8px] md:text-[9px] font-bold uppercase tracking-widest text-accent">Laporan Persembahan</span>
                </div>
                <h3 class="text-base md:text-lg font-extrabold text-primary font-heading uppercase leading-tight">Minggu Kemarin</h3>
                <p class="text-[8px] md:text-[9px] text-slate-400 font-bold tracking-widest italic"><?= $minggu_kemarin_range ?></p>
            </div>
            <div class="text-[8px] font-bold text-slate-300 uppercase tracking-widest italic leading-none">Keuangan</div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-primary/5">
                        <th class="py-1.5 px-3 md:px-6 text-[8px] font-extrabold text-primary/60 uppercase tracking-widest">Tanggal</th>
                        <th class="py-1.5 px-3 md:px-6 text-[8px] font-extrabold text-primary/60 uppercase tracking-widest">Nama Ibadah</th>
                        <th class="py-1.5 px-3 md:px-6 text-[8px] font-extrabold text-primary/60 uppercase tracking-widest text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php 
                        $totalOfferings = 0;
                        if(!empty($persembahan)): 
                            foreach($persembahan as $p): 
                                $totalOfferings += $p['jumlah'];
                    ?>
                        <tr class="group hover:bg-slate-50/50 transition-colors">
                            <td class="py-0.5 px-3 md:px-6">
                                <span class="text-[9px] font-bold text-slate-500"><?= !empty($p['tanggal']) ? date('d/m/y', strtotime($p['tanggal'])) : '-' ?></span>
                            </td>
                            <td class="py-0.5 px-3 md:px-6">
                                <span class="text-[10px] md:text-xs font-bold text-slate-800"><?= $p['judul'] ?></span>
                                <?php if(!empty($p['deskripsi'])): ?>
                                    <span class="text-[8px] text-slate-400 font-medium ml-1">— <?= strip_tags($p['deskripsi']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="py-0.5 px-3 md:px-6 text-right">
                                <span class="text-[11px] md:text-xs font-black text-indigo-600">Rp <?= number_format($p['jumlah'], 0, ',', '.') ?></span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="py-4 text-center text-slate-400 italic text-[9px]">Belum ada data persembahan (Minggu Kemarin).</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot class="bg-indigo-50/50 border-t border-indigo-100">
                    <tr class="font-bold">
                        <td colspan="2" class="py-1.5 px-3 md:px-6 text-right">
                            <span class="text-[8px] font-black uppercase tracking-widest text-indigo-400">Total</span>
                        </td>
                        <td class="py-1.5 px-3 md:px-6 text-right">
                            <span class="text-xs md:text-sm font-black text-indigo-600">Rp <?= number_format($totalOfferings, 0, ',', '.') ?></span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Laporan Keuangan (Simplified Table Layout) -->
    <div class="bg-white rounded-[20px] md:rounded-[32px] shadow-2xl shadow-primary/5 overflow-hidden border border-slate-100" data-aos="fade-up">
        <div class="p-3 md:p-5 border-b border-slate-50 flex justify-between items-center">
            <div>
                <h3 class="text-base md:text-lg font-extrabold text-primary font-heading uppercase leading-tight">Laporan Keuangan</h3>
                <p class="text-slate-400 font-bold text-[8px] uppercase tracking-widest">Bulan <?= date('F Y') ?></p>
            </div>
            <div class="text-[8px] font-bold text-slate-300 uppercase tracking-widest italic">Kas Gereja</div>
        </div>
        
        <div class="p-3 md:p-5 overflow-x-auto">
            <table class="w-full text-left border border-slate-50 rounded-lg overflow-hidden">
                <tbody class="divide-y divide-slate-100">
                    <tr>
                        <td class="py-1 px-3 md:px-6 text-[9px] font-bold text-slate-500 uppercase tracking-wider">Saldo Bulan Lalu</td>
                        <td class="py-1 px-3 md:px-6 text-right font-bold text-slate-600 text-[10px] md:text-xs">Rp <?= number_format($saldo_bulan_lalu, 0, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <td class="py-1 px-3 md:px-6 text-[9px] font-bold text-emerald-600 uppercase tracking-wider">Pemasukan Bulan Ini</td>
                        <td class="py-1 px-3 md:px-6 text-right font-bold text-emerald-600 text-[10px] md:text-xs">Rp <?= number_format($pemasukan_bulan_ini, 0, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <td class="py-1 px-3 md:px-6 text-[9px] font-bold text-rose-500 uppercase tracking-wider">Pengeluaran Bulan Ini</td>
                        <td class="py-1 px-3 md:px-6 text-right font-bold text-rose-500 text-[10px] md:text-xs">Rp <?= number_format($pengeluaran_bulan_ini, 0, ',', '.') ?></td>
                    </tr>
                    <tr class="bg-primary">
                        <td class="py-2 px-3 md:px-6 text-[9px] md:text-[10px] font-black text-white uppercase tracking-[0.2em]">Saldo Akhir</td>
                        <td class="py-2 px-3 md:px-6 text-right font-black text-white text-xs md:text-base">Rp <?= number_format($saldo_akhir, 0, ',', '.') ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="px-3 md:px-5 py-2 md:py-3 bg-slate-50/50 flex justify-between items-center">
            <p class="text-slate-300 text-[7px] font-bold uppercase tracking-[0.2em] italic">Updated: <?= date('d/m/y H:i') ?></p>
            <p class="text-slate-300 text-[7px] font-bold uppercase tracking-[0.2em]">GKI</p>
        </div>
    </div>

    <!-- Jadwal Kegiatan (Simplified Table Layout) -->
    <div class="bg-white rounded-[20px] md:rounded-[32px] shadow-2xl shadow-primary/5 overflow-hidden border border-slate-100" data-aos="fade-up">
        <div class="p-3 md:p-5 border-b border-slate-50 flex justify-between items-center">
            <div>
                <h3 class="text-base md:text-lg font-extrabold text-primary font-heading uppercase leading-tight">Jadwal Kegiatan</h3>
                <p class="text-slate-400 font-bold text-[8px] uppercase tracking-widest">Mendatang</p>
            </div>
            <div class="text-[8px] font-bold text-slate-300 uppercase tracking-widest italic">Informasi Jemaat</div>
        </div>
        
        <div class="p-3 md:p-5 overflow-x-auto">
            <table class="w-full text-left border border-slate-50 rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-primary/5">
                        <th class="py-1.5 px-3 md:px-6 text-[8px] font-extrabold text-primary/60 uppercase tracking-widest w-24">Waktu</th>
                        <th class="py-1.5 px-3 md:px-6 text-[8px] font-extrabold text-primary/60 uppercase tracking-widest">Nama Kegiatan</th>
                        <th class="py-1.5 px-3 md:px-6 text-[8px] font-extrabold text-primary/60 uppercase tracking-widest">Lokasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php if(!empty($kegiatan)): foreach($kegiatan as $k): ?>
                    <tr>
                        <td class="py-1 px-3 md:px-6 text-[9px] font-bold text-slate-500 uppercase">
                            <?= date('d/m/y', strtotime($k['tanggal_mulai'])) ?>
                        </td>
                        <td class="py-1 px-3 md:px-6">
                            <span class="text-[10px] md:text-xs font-bold text-slate-800"><?= $k['nama_kegiatan'] ?></span>
                            <?php if(!empty($k['deskripsi'])): ?>
                                <span class="text-[8px] text-slate-400 font-medium ml-1 block md:inline">— <?= strip_tags($k['deskripsi']) ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="py-1 px-3 md:px-6">
                            <div class="flex items-center gap-1">
                                <ion-icon name="location-outline" class="text-accent text-[8px]"></ion-icon>
                                <span class="text-[10px] text-slate-600 font-medium"><?= $k['lokasi'] ?: '-' ?></span>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr>
                        <td colspan="3" class="py-6 text-center text-slate-400 italic text-[9px]">Belum ada jadwal kegiatan mendatang.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="px-3 md:px-5 py-2 md:py-3 bg-slate-50/50 flex justify-between items-center">
            <p class="text-slate-300 text-[7px] font-bold uppercase tracking-[0.2em] italic">Segala Mulia Bagi Allah</p>
            <p class="text-slate-300 text-[7px] font-bold uppercase tracking-[0.2em]">GKI</p>
        </div>
    </div>

    <!-- Informasi Lain (Simplified Table Layout) -->
    <?php if(!empty($infoLain)): ?>
    <div class="bg-white rounded-[20px] md:rounded-[32px] shadow-2xl shadow-primary/5 overflow-hidden border border-slate-100" data-aos="fade-up">
        <div class="p-3 md:p-5 border-b border-slate-50 flex justify-between items-center">
            <div>
                <h3 class="text-base md:text-lg font-extrabold text-primary font-heading uppercase leading-tight">Informasi Lain</h3>
                <p class="text-slate-400 font-bold text-[8px] uppercase tracking-widest">Pengumuman & Berita</p>
            </div>
            <div class="text-[8px] font-bold text-slate-300 uppercase tracking-widest italic">Warta Jemaat</div>
        </div>
        
        <div class="p-3 md:p-5 overflow-x-auto">
            <table class="w-full text-left border border-slate-50 rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-primary/5">
                        <th class="py-1.5 px-3 md:px-6 text-[8px] font-extrabold text-primary/60 uppercase tracking-widest w-24">Tanggal</th>
                        <th class="py-1.5 px-3 md:px-6 text-[8px] font-extrabold text-primary/60 uppercase tracking-widest">Informasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php foreach($infoLain as $info): ?>
                    <tr>
                        <td class="py-2 px-3 md:px-6 text-[9px] font-bold text-primary/80 uppercase whitespace-nowrap">
                            <?= $info['tanggal'] ? date('d/m/y', strtotime($info['tanggal'])) : date('d/m/y', strtotime($info['created_at'])) ?>
                        </td>
                        <td class="py-2 px-3 md:px-6">
                            <span class="text-[10px] md:text-xs font-bold text-slate-800 block mb-0.5"><?= $info['judul'] ?></span>
                            <div class="text-[9px] md:text-[10px] text-slate-500 font-medium leading-tight">
                                <?= strip_tags($info['deskripsi']) ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="px-3 md:px-5 py-2 md:py-3 bg-slate-50/50 flex justify-between items-center">
            <p class="text-slate-300 text-[7px] font-bold uppercase tracking-[0.2em] italic">Terpujilah Tuhan</p>
            <p class="text-slate-300 text-[7px] font-bold uppercase tracking-[0.2em]">Kasih Karunia-Nya Menyertai Kita</p>
        </div>
    </div>
    <?php endif; ?>

</div>

<?= $this->endSection() ?>
