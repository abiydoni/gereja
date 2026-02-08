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
    <?php if($renungan && isset($config['menu_renungan'])): ?>
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

    <!-- Jadwal Pelayan Ibadah (Moved & Renamed) -->
    <?php if(!empty($jadwalList) && isset($config['section_jadwal_tugas'])): ?>
    <div class="bg-white rounded-[20px] md:rounded-[40px] shadow-2xl shadow-primary/5 overflow-hidden border border-slate-100 mb-8 md:mb-16" data-aos="fade-up">
        
        <div class="p-4 md:p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row justify-between md:items-center gap-2">
            <div class="text-left w-full">
                <div class="flex items-center justify-start space-x-2 mb-1">
                        <div class="h-px w-3 md:w-5 bg-accent"></div>
                        <span class="text-[8px] md:text-[9px] font-bold uppercase tracking-widest text-accent">Mingguan</span>
                </div>
                <h3 class="text-lg md:text-xl font-extrabold text-primary font-heading uppercase flex items-center gap-2">
                    <ion-icon name="calendar-outline"></ion-icon> Jadwal Pelayan Ibadah
                </h3>
            </div>
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
                                                <span class="text-[7px] md:text-[9px] font-bold text-slate-400 uppercase tracking-wide"><?= $p['jenis_tugas'] ?></span>
                                            </div>
                                            <span class="text-[8px] md:text-[10px] font-bold text-slate-700"><?= nl2br($p['nama_petugas']) ?></span>
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

    <!-- Ulang Tahun Bulan Ini -->
    <?php if(!empty($ulangTahun)): ?>
    <div class="bg-white rounded-[20px] md:rounded-[32px] shadow-2xl shadow-primary/5 overflow-hidden border border-slate-100 mb-8 md:mb-16 relative" data-aos="fade-up">
        <!-- Decoration -->
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-accent/5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="p-5 md:p-8 border-b border-slate-50 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 relative z-10">
            <div class="text-left w-full">
                <div class="flex items-center justify-start space-x-2 mb-1">
                    <div class="h-px w-3 md:w-5 bg-accent"></div>
                    <span class="text-[8px] md:text-[9px] font-bold uppercase tracking-widest text-accent">Sukacita Pertambahan Usia</span>
                </div>
                <h3 class="text-lg md:text-xl font-extrabold text-primary font-heading uppercase flex items-center gap-2">
                    <ion-icon name="gift-outline" class="animate-bounce"></ion-icon> Ulang Tahun Bulan <?= date('F') ?>
                </h3>
            </div>
            
            <div class="bg-indigo-50 px-3 py-1.5 rounded-lg border border-indigo-100 flex items-center gap-2">
                 <ion-icon name="people" class="text-indigo-500"></ion-icon>
                 <span class="text-[10px] font-bold text-indigo-600"><?= count($ulangTahun) ?> Jemaat</span>
            </div>
        </div>

        <div class="p-5 md:p-8">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-4">
                <?php foreach($ulangTahun as $ultah): 
                    $isToday = date('d-m') == date('d-m', strtotime($ultah['tanggal_lahir']));
                ?>
                <div class="relative group" data-aos="zoom-in" data-aos-duration="500">
                    <?php if($isToday): ?>
                        <div class="absolute -top-2 -right-2 z-20">
                            <span class="relative flex h-3 w-3">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-3 w-3 bg-rose-500"></span>
                            </span>
                        </div>
                    <?php endif; ?>

                    <div class="<?= $isToday ? 'bg-gradient-to-br from-indigo-500 to-purple-600 text-white shadow-lg shadow-indigo-500/30 scale-105 ring-2 ring-offset-2 ring-indigo-500' : 'bg-white border border-slate-100 hover:border-accent/50 hover:shadow-lg hover:shadow-accent/10' ?> rounded-xl p-3 md:p-4 transition-all duration-300 flex items-center gap-3 md:gap-4 overflow-hidden h-full">
                        
                        <!-- Icon/Avatar -->
                        <div class="<?= $isToday ? 'bg-white/20 text-white' : 'bg-slate-50 text-slate-400 group-hover:bg-accent group-hover:text-white' ?> w-10 h-10 rounded-full flex items-center justify-center transition-colors flex-shrink-0">
                            <ion-icon name="person" class="text-lg"></ion-icon>
                        </div>
                        
                        <div class="flex-grow min-w-0">
                            <h4 class="<?= $isToday ? 'text-white' : 'text-slate-700 group-hover:text-primary' ?> text-[10px] md:text-xs font-bold truncate transition-colors leading-tight">
                                <?= $ultah['nama_lengkap'] ?>
                            </h4>
                            <div class="flex items-center gap-1 mt-0.5">
                                <ion-icon name="calendar-number-outline" class="<?= $isToday ? 'text-white/80' : 'text-accent' ?> text-[10px]"></ion-icon>
                                <span class="<?= $isToday ? 'text-white/90' : 'text-slate-500' ?> text-[9px] font-bold uppercase tracking-wider">
                                    <?= date('d F', strtotime($ultah['tanggal_lahir'])) ?>
                                </span>
                            </div>
                             <?php if($isToday): ?>
                                <p class="text-[8px] font-medium text-white/80 italic mt-1 animate-pulse">Hari ini!</p>
                             <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Doa Ucapan -->
             <div class="mt-6 p-4 bg-orange-50/50 rounded-xl border border-orange-100 text-center">
                <p class="text-[10px] md:text-xs text-orange-800 font-medium italic">
                    "Kiranya Tuhan memberkati dan melindungi saudara, menyinari dengan wajah-Nya, dan memberi kasih karunia serta damai sejahtera."
                    <span class="block font-bold not-italic mt-1 text-orange-600 line-through md:no-underline decoration-orange-300/50">— Bilangan 6:24-26</span>
                </p>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Warta Sepekan (Moved & Renamed) -->
    <?php if(!empty($infoLain) && isset($config['section_informasi_lain'])): ?>
    <div class="bg-white rounded-[20px] md:rounded-[32px] shadow-2xl shadow-primary/5 overflow-hidden border border-slate-100 mb-8 md:mb-16" data-aos="fade-up">

        
        <div class="p-5 md:p-8 space-y-8 divide-y divide-slate-100">
            <?php foreach($infoLain as $info): ?>
            <div class="mb-4 text-left border-b border-slate-50 pb-2">
                <div class="flex items-center justify-start space-x-2 mb-1">
                        <div class="h-px w-3 md:w-5 bg-accent"></div>
                        <span class="text-[8px] md:text-[9px] font-bold uppercase tracking-widest text-accent"><?= date('d F Y', strtotime($info['tanggal'])) ?></span>
                </div>
                 <h3 class="text-lg md:text-xl font-extrabold text-primary font-heading uppercase leading-tight"><?= $info['judul'] ?></h3>
            </div>
            <div class="prose prose-sm md:prose-base max-w-none text-slate-700 pt-6 first:pt-0 w-full">
                <style>
                    /* Force table to fit container width */
                    .prose table { 
                        width: 100% !important; 
                        max-width: 100% !important;
                        table-layout: auto !important; /* Allow columns to size naturally based on content */
                        border-collapse: collapse; 
                        display: table !important;
                        margin-bottom: 0 !important; /* Remove bottom margin */
                    }
                    /* Force text wrapping inside cells */
                    .prose td, .prose th { 
                        border: 1px solid #e2e8f0; 
                        padding: 2px 3px !important; /* MAX COMPACT for Mobile */
                        word-wrap: break-word !important;
                        overflow-wrap: break-word !important;
                        word-break: break-word !important; /* Force break long words if needed */
                        white-space: normal !important;
                        vertical-align: middle; /* Center text vertically */
                        font-size: 11px !important; /* Smaller font for dense tables on mobile */
                        width: auto !important; /* Reset specific widths */
                        line-height: 1.1 !important; /* Tighter line height */
                    }
                    /* Remove paragraph margins inside table cells */
                    .prose table p {
                        margin: 0 !important;
                        padding: 0 !important;
                    }
                    /* Desktop adjustments (Reset to normal readable size but COMPACT) */
                    @media (min-width: 768px) {
                        .prose td, .prose th {
                            padding: 4px 6px !important; /* Larger than mobile but still compact */
                            font-size: 0.95rem !important; /* Slightly smaller than 1rem */
                            line-height: 1.2 !important;
                        }
                    }
                    /* Make images responsive inside table */
                    .prose table img {
                        max-width: 100% !important;
                        height: auto !important;
                        margin: 0 !important; /* Remove image margins */
                        display: block;
                    }
                    /* FORCE Table Width 100% even if it has width attribute */
                    .prose table[width] {
                        width: 100% !important;
                    }
                    /* Reset cell widths to auto so they share space equally */
                    .prose td[width], .prose th[width] {
                        width: auto !important;
                    }
                </style>
                <?= $info['deskripsi'] ?>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="px-3 md:px-5 py-2 md:py-3 bg-slate-50/50 flex justify-between items-center">
            <p class="text-slate-300 text-[7px] font-bold uppercase tracking-[0.2em] italic">Terpujilah Tuhan</p>
            <p class="text-slate-300 text-[7px] font-bold uppercase tracking-[0.2em]">Kasih Karunia-Nya Menyertai Kita</p>
        </div>
    </div>
    <?php endif; ?>



    <!-- Informasi Persembahan (Above Keuangan) -->
    <?php if(isset($config['section_persembahan']) && !empty($persembahan)): ?>
    <div class="bg-white rounded-[20px] md:rounded-[32px] shadow-2xl shadow-primary/5 overflow-hidden mb-6 md:mb-10 border border-slate-100" data-aos="fade-up">
        <div class="p-3 md:p-5 border-b border-slate-50 flex justify-between items-center bg-slate-50/30">
            <div class="text-left w-full">
                <div class="flex items-center justify-start space-x-2 mb-1">
                    <div class="h-px w-3 md:w-5 bg-accent"></div>
                    <span class="text-[8px] md:text-[9px] font-bold uppercase tracking-widest text-accent">Laporan Tahunan</span>
                </div>
                <h3 class="text-base md:text-xl font-extrabold text-primary font-heading uppercase leading-tight">Persembahan Jemaat</h3>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-primary/5">
                        <th class="py-1.5 px-3 md:px-6 text-[8px] font-extrabold text-primary/60 uppercase tracking-widest w-10 text-center">No</th>
                        <th class="py-1.5 px-3 md:px-6 text-[8px] font-extrabold text-primary/60 uppercase tracking-widest">Keterangan</th>
                        <th class="py-1.5 px-3 md:px-6 text-[8px] font-extrabold text-primary/60 uppercase tracking-widest text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $totalOfferings = 0;
                        if(!empty($persembahan)): 
                            // 1. Triple Nesting: Category -> Date -> Items
                            $nestedGroups = [];
                            foreach($persembahan as $p) {
                                $category = $p['judul'];
                                $dateKey = date('Y-m-d', strtotime($p['tanggal']));
                                
                                if(!isset($nestedGroups[$category])) {
                                    $nestedGroups[$category] = [
                                        'total' => 0,
                                        'dates' => []
                                    ];
                                }
                                
                                if(!isset($nestedGroups[$category]['dates'][$dateKey])) {
                                    $nestedGroups[$category]['dates'][$dateKey] = [
                                        'total' => 0,
                                        'items' => []
                                    ];
                                }
                                
                                $nestedGroups[$category]['dates'][$dateKey]['items'][] = $p;
                                $nestedGroups[$category]['dates'][$dateKey]['total'] += $p['jumlah'];
                                $nestedGroups[$category]['total'] += $p['jumlah'];
                                $totalOfferings += $p['jumlah'];
                            }

                            foreach($nestedGroups as $categoryName => $catData):
                    ?>
                        <!-- Category Sub-Header -->
                        <tr class="bg-slate-50 border-y border-slate-200">
                            <td colspan="3" class="py-1 px-3 md:px-6">
                                <span class="text-[10px] md:text-xs font-black text-primary uppercase tracking-widest"><?= $categoryName ?></span>
                            </td>
                        </tr>

                        <?php 
                            foreach($catData['dates'] as $date => $dateData):
                                // Format Indonesian Date Header
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
                        ?>
                        <!-- Date Header Row -->
                        <tr class="bg-indigo-50/10">
                            <td colspan="3" class="py-1 px-3 md:px-6 border-b border-indigo-50/30">
                                <span class="text-[8px] md:text-[9px] font-bold text-indigo-400 uppercase tracking-widest"><?= $indoDay ?>, <?= $indoDate ?></span>
                            </td>
                        </tr>

                        <?php 
                            $no = 1;
                            foreach($dateData['items'] as $item):
                        ?>
                        <tr class="group hover:bg-slate-50/50 transition-colors border-b border-slate-50 last:border-0">
                            <td class="py-0.5 px-3 md:px-6 text-center">
                                <span class="text-[8px] md:text-[9px] font-bold text-slate-400"><?= $no++ ?>.</span>
                            </td>
                            <td class="py-0.5 px-3 md:px-6">
                                <span class="text-[9px] md:text-xs font-medium text-slate-700 italic">
                                    <?= !empty($item['deskripsi']) ? strip_tags($item['deskripsi']) : '(Tanpa Keterangan)' ?>
                                </span>
                            </td>
                            <td class="py-0.5 px-3 md:px-6 text-right">
                                <span class="text-[9px] md:text-xs font-bold text-slate-800">Rp <?= number_format($item['jumlah'], 0, ',', '.') ?></span>
                            </td>
                        </tr>
                        <?php endforeach; ?>

                        <!-- Date Sub-Total Row (Optional, if multiple items) -->
                        <?php if(count($dateData['items']) > 1): ?>
                        <tr class="bg-indigo-50/5 border-b border-indigo-50/20">
                            <td colspan="2" class="py-0.5 px-3 md:px-6 text-right">
                                <span class="text-[7px] md:text-[8px] font-bold uppercase tracking-widest text-indigo-300">Sub-Total <?= $indoDay ?></span>
                            </td>
                            <td class="py-0.5 px-3 md:px-6 text-right">
                                <span class="text-[8px] md:text-[9px] font-bold text-indigo-500">Rp <?= number_format($dateData['total'], 0, ',', '.') ?></span>
                            </td>
                        </tr>
                        <?php endif; ?>

                        <?php endforeach; ?>

                        <!-- Category Sub-Total Row -->
                        <tr class="bg-indigo-50/20 border-b border-indigo-100/40">
                            <td colspan="2" class="py-1 px-3 md:px-6 text-right">
                                <span class="text-[8px] md:text-[9px] font-black uppercase tracking-widest text-indigo-600">Total <?= $categoryName ?></span>
                            </td>
                            <td class="py-1 px-3 md:px-6 text-right">
                                <span class="text-[10px] md:text-[11px] font-black text-indigo-700">Rp <?= number_format($catData['total'], 0, ',', '.') ?></span>
                            </td>
                        </tr>
                        
                        <!-- Spacer between categories -->
                        <tr class="h-3"></tr>

                    <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="py-8 text-center text-slate-400 italic text-[9px]">Belum ada data persembahan aktif.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <?php if(count($nestedGroups ?? []) > 1): ?>
                <tfoot class="bg-indigo-50/50 border-t border-indigo-100">
                    <tr class="font-bold">
                        <td colspan="2" class="py-3 px-3 md:px-6 text-right">
                            <span class="text-[9px] font-black uppercase tracking-widest text-indigo-400">Total Keseluruhan</span>
                        </td>
                        <td class="py-3 px-3 md:px-6 text-right">
                            <span class="text-xs md:text-sm font-black text-indigo-600">Rp <?= number_format($totalOfferings, 0, ',', '.') ?></span>
                        </td>
                    </tr>
                </tfoot>
                <?php endif; ?>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <!-- Laporan Keuangan (Simplified Table Layout) -->
    <?php if(isset($config['section_keuangan']) && ($saldo_bulan_lalu != 0 || $pemasukan_bulan_ini != 0 || $pengeluaran_bulan_ini != 0 || $saldo_akhir != 0)): ?>
    <div class="bg-white rounded-[20px] md:rounded-[32px] shadow-2xl shadow-primary/5 overflow-hidden border border-slate-100" data-aos="fade-up">
        <div class="p-3 md:p-5 border-b border-slate-50 flex justify-between items-center">
            <div class="text-left w-full">
                <div class="flex items-center justify-start space-x-2 mb-1">
                    <div class="h-px w-3 md:w-5 bg-accent"></div>
                    <span class="text-[8px] md:text-[9px] font-bold uppercase tracking-widest text-accent">Bulan <?= date('F Y') ?></span>
                </div>
                <h3 class="text-base md:text-xl font-extrabold text-primary font-heading uppercase leading-tight">Laporan Keuangan</h3>
            </div>
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
    <?php endif; ?>

    <!-- Jadwal Kegiatan (Simplified Table Layout) -->
    <?php if(isset($config['menu_kegiatan']) && !empty($kegiatan)): ?>
    <div class="bg-white rounded-[20px] md:rounded-[32px] shadow-2xl shadow-primary/5 overflow-hidden border border-slate-100" data-aos="fade-up">
        <div class="p-3 md:p-5 border-b border-slate-50 flex justify-between items-center">
            <div class="text-left w-full">
                <div class="flex items-center justify-start space-x-2 mb-1">
                    <div class="h-px w-3 md:w-5 bg-accent"></div>
                    <span class="text-[8px] md:text-[9px] font-bold uppercase tracking-widest text-accent">Mendatang</span>
                </div>
                <h3 class="text-base md:text-xl font-extrabold text-primary font-heading uppercase leading-tight">Jadwal Kegiatan</h3>
            </div>
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
    <?php endif; ?>



</div>

<?= $this->endSection() ?>
