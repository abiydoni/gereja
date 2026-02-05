<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= $item['judul'] ?> | Modern Reader</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'primary': '#020617', // Slate 950
                        'accent': '#D4AF37', // Gold
                        'glass-bg': 'rgba(255, 255, 255, 0.7)',
                        'indigo-premium': '#4F46E5',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        heading: ['Outfit', 'sans-serif'],
                        jakarta: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    animation: {
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    }
                }
            }
        }
    </script>
    
    <!-- Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; color: black !important; padding: 0 !important; }
            .print-container { padding: 0 !important; margin: 0 !important; max-width: 100% !important; }
            .reader-content { font-size: 12pt !important; }
        }

        body {
            font-family: 'Inter', sans-serif; 
            background-color: #F8FAFC;
            color: #1E293B;
            line-height: 1.8;
            -webkit-font-smoothing: antialiased;
        }

        /* Reading Progress Bar */
        .progress-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: transparent;
            z-index: 9999;
        }
        .progress-bar {
            height: 100%;
            background: linear-gradient(to right, #D4AF37, #4F46E5);
            width: 0%;
            border-radius: 0 4px 4px 0;
            transition: width 0.1s ease;
            box-shadow: 0 0 10px rgba(79, 70, 229, 0.4);
        }

        /* Role Badge: Modern Style */
        .role-badge {
            display: inline-flex;
            align-items: center;
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            padding: 3px 12px;
            border-radius: 99px; /* Pill */
            margin-right: 12px;
            vertical-align: middle;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: default;
        }
        .role-badge:hover { transform: scale(1.1); }
        
        .role-p { background: #EEF2FF; color: #4F46E5; border: 1px solid #E0E7FF; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.1); }
        .role-j { background: #FFF7ED; color: #EA580C; border: 1px solid #FFEDD5; box-shadow: 0 4px 12px rgba(234, 88, 12, 0.1); }
        .role-s { background: #F0FDF4; color: #16A34A; border: 1px solid #DCFCE7; box-shadow: 0 4px 12px rgba(22, 163, 74, 0.1); }
        .role-l { background: #F5F3FF; color: #7C3AED; border: 1px solid #EDE9FE; box-shadow: 0 4px 12px rgba(124, 58, 237, 0.1); }

        /* Modernized Component Blocks */
        .song-block {
            background: linear-gradient(135deg, rgba(255,255,255,0.8), rgba(248,250,252,0.8));
            backdrop-filter: blur(8px);
            border: 1px solid rgba(212, 175, 55, 0.2);
            padding: 2.5rem;
            margin: 3.5rem 0;
            border-radius: 32px;
            text-align: center;
            position: relative;
            box-shadow: 0 10px 40px -10px rgba(0,0,0,0.05);
        }
        .song-block strong {
            display: block;
            font-family: 'Outfit', sans-serif;
            font-size: 1.1rem;
            letter-spacing: 0.05em;
            color: #1E293B;
            margin-top: 0.5rem;
        }

        .bible-block {
            background: white;
            padding: 2.5rem;
            margin: 3.5rem 0;
            border-radius: 32px;
            position: relative;
            border: 1px solid #F1F5F9;
            box-shadow: 0 20px 50px -20px rgba(79, 70, 229, 0.1);
        }
        .bible-block::after {
            content: '';
            position: absolute;
            left: 0; top: 0; bottom: 0;
            width: 6px;
            background: linear-gradient(to bottom, #4F46E5, #818CF8);
            border-radius: 32px 0 0 32px;
        }

        /* Typography Granularity (Increments of 25%) */
        .zoom-text-1 { font-size: 0.25rem; } /* 25% */
        .zoom-text-2 { font-size: 0.5rem; }  /* 50% */
        .zoom-text-3 { font-size: 0.75rem; } /* 75% */
        .zoom-text-4 { font-size: 1.0rem; }  /* 100% - Baseline */
        .zoom-text-5 { font-size: 1.25rem; } /* 125% */
        .zoom-text-6 { font-size: 1.5rem; }  /* 150% */
        .zoom-text-7 { font-size: 1.75rem; } /* 175% */
        .zoom-text-8 { font-size: 2.0rem; }  /* 200% */

        /* Dark Mode Aesthetics */
        body.dark { background-color: #020617; color: #E2E8F0; }
        body.dark .song-block { background: rgba(30, 41, 59, 0.5); border-color: rgba(255,255,255,0.05); }
        body.dark .bible-block { background: rgba(30, 41, 59, 0.8); border-color: rgba(255,255,255,0.05); }
        body.dark .role-p { background: #1e1b4b; color: #818cf8; border-color: #312e81; }
        body.dark .role-j { background: #451a03; color: #fbbf24; border-color: #78350f; }

        /* Animations */
        .fade-slide-up {
            opacity: 0;
            transform: translateY(30px);
            animation: fsu 1.2s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
        }
        @keyframes fsu { to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="transition-colors duration-700">

    <!-- Progress Indicator -->
    <div class="progress-container no-print">
        <div class="progress-bar" id="progressBar"></div>
    </div>

    <!-- Header: Glass UI -->
    <header class="fixed top-6 left-6 right-6 h-16 bg-white/70 dark:bg-slate-900/70 backdrop-blur-xl border border-white/40 dark:border-white/5 rounded-2xl z-50 px-6 flex items-center justify-between no-print transition-all duration-500 shadow-2xl shadow-indigo-500/5">
        <a href="<?= base_url('liturgi') ?>" class="flex items-center space-x-3 text-slate-600 dark:text-slate-400 hover:text-indigo-premium transition-all">
            <ion-icon name="arrow-back" class="text-xl"></ion-icon>
            <span class="hidden md:block text-[10px] font-black uppercase tracking-[0.2em] font-heading">Kembali</span>
        </a>
        
        <div class="flex-grow text-center px-6 overflow-hidden">
            <h1 class="font-heading font-black text-xs md:text-sm uppercase tracking-[0.3em] text-slate-900 dark:text-white truncate">
                <?= $item['judul'] ?>
            </h1>
        </div>

        <div class="flex items-center space-x-4">
            <button onclick="window.print()" class="w-10 h-10 rounded-xl bg-slate-50 dark:bg-slate-800 flex items-center justify-center text-slate-600 dark:text-slate-400 hover:bg-white dark:hover:bg-slate-700 transition-all shadow-sm">
                <ion-icon name="print-outline"></ion-icon>
            </button>
            <button id="themeToggle" class="w-10 h-10 rounded-xl bg-slate-50 dark:bg-slate-800 flex items-center justify-center text-slate-600 dark:text-slate-400 hover:bg-white dark:hover:bg-slate-700 transition-all shadow-sm">
                <ion-icon name="moon-outline" id="themeIcon"></ion-icon>
            </button>
        </div>
    </header>

    <!-- Content Wrapper -->
    <main class="print-container pt-36 md:pt-48 px-8 pb-32 max-w-3xl mx-auto min-h-screen">
        
        <!-- Animated Hero -->
        <div class="mb-16 text-center fade-slide-up">
            <div class="inline-flex items-center space-x-3 px-4 py-1.5 rounded-full bg-indigo-500/5 dark:bg-indigo-500/10 border border-indigo-500/10 mb-6">
                <span class="w-2 h-2 rounded-full bg-accent animate-pulse"></span>
                <span class="text-[9px] font-black text-accent uppercase tracking-[0.3em]"><?= $item['kategori'] ?? 'Liturgi' ?></span>
            </div>
            <h2 class="text-2xl md:text-4xl font-black text-slate-900 dark:text-white mb-6 font-heading tracking-tight leading-[1.1]">
                <?= $item['judul'] ?>
            </h2>
            <p class="font-jakarta text-[10px] md:text-xs text-slate-400 font-bold uppercase tracking-[0.4em] opacity-60">
                <?php
                    $fullDate = date('l, d F Y', strtotime($item['tanggal']));
                    echo str_replace(
                        ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                        ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                        $fullDate
                    );
                ?>
            </p>
        </div>

        <!-- Reader Surface -->
        <article id="readerContent" class="reader-content zoom-text-4 font-medium text-slate-700 dark:text-slate-300 transition-all duration-500 fade-slide-up" style="animation-delay: 0.2s">
            <?= $item['isi_liturgi'] ?>
        </article>

        <!-- Dynamic Interaction: Bottom Info -->
        <div class="mt-32 pt-16 border-t border-slate-100 dark:border-slate-800 text-center fade-slide-up" style="animation-delay: 0.4s">
            <div class="w-16 h-16 bg-accent/10 rounded-3xl flex items-center justify-center mx-auto mb-8 animate-pulse-slow">
                <ion-icon name="heart" class="text-3xl text-accent"></ion-icon>
            </div>
            <p class="font-heading font-black text-[10px] text-slate-400 dark:text-slate-600 uppercase tracking-[0.6em]">Tuhan Memberkati Pelayanan Kita</p>
        </div>

    </main>

    <!-- Floating Glass Controls (Modern Panel) -->
    <div class="fixed bottom-10 left-1/2 -translate-x-1/2 flex items-center p-2 bg-white/70 dark:bg-slate-900/70 backdrop-blur-2xl border border-white/40 dark:border-white/5 rounded-2xl shadow-2xl z-50 no-print scale-90 md:scale-100 transition-all hover:scale-105">
        <button onclick="changeZoom(-1)" class="w-12 h-12 rounded-xl flex items-center justify-center text-slate-600 dark:text-slate-400 hover:bg-white dark:hover:bg-slate-800 transition-all">
            <ion-icon name="remove-circle-outline" class="text-xl"></ion-icon>
        </button>
        <div class="px-4 border-l border-r border-slate-200 dark:border-slate-700">
             <span id="zoomLabel" class="font-heading font-black text-[10px] uppercase tracking-widest text-slate-500">100%</span>
        </div>
        <button onclick="changeZoom(1)" class="w-12 h-12 rounded-xl flex items-center justify-center text-slate-600 dark:text-slate-400 hover:bg-white dark:hover:bg-slate-800 transition-all">
            <ion-icon name="add-circle-outline" class="text-xl"></ion-icon>
        </button>
    </div>

    <!-- Logic Engine -->
    <script>
        // Reading Progress
        window.onscroll = function() {
            let winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            let height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            let scrolled = (winScroll / height) * 100;
            document.getElementById("progressBar").style.width = scrolled + "%";
        };

        // Theme Toggle
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        themeToggle.addEventListener('click', () => {
            document.documentElement.classList.toggle('dark');
            document.body.classList.toggle('dark');
            const isDark = document.body.classList.contains('dark');
            themeIcon.setAttribute('name', isDark ? 'sunny-outline' : 'moon-outline');
            localStorage.setItem('modern-liturgi-theme', isDark ? 'dark' : 'light');
        });
        if (localStorage.getItem('modern-liturgi-theme') === 'dark') {
            document.documentElement.classList.add('dark');
            document.body.classList.add('dark');
            themeIcon.setAttribute('name', 'sunny-outline');
        }

        // Zoom Engine
        let currentLevel = parseInt(localStorage.getItem('liturgi-zoom-level')) || 4;
        const zoomLabel = document.getElementById('zoomLabel');
        const reader = document.getElementById('readerContent');
        
        function updateZoom() {
            reader.classList.remove('zoom-text-1', 'zoom-text-2', 'zoom-text-3', 'zoom-text-4', 'zoom-text-5', 'zoom-text-6', 'zoom-text-7', 'zoom-text-8');
            reader.classList.add('zoom-text-' + currentLevel);
            const labels = {1: '25%', 2: '50%', 3: '75%', 4: '100%', 5: '125%', 6: '150%', 7: '175%', 8: '200%'};
            zoomLabel.innerText = labels[currentLevel];
            localStorage.setItem('liturgi-zoom-level', currentLevel);
        }
        
        function changeZoom(delta) {
            currentLevel = Math.min(Math.max(currentLevel + delta, 1), 8);
            updateZoom();
        }
        updateZoom();

        // Aesthetic Post-Processing
        function applyLiquidUI() {
            const content = document.getElementById('readerContent');
            let html = content.innerHTML;

            // 1. Roles
            const roleRegex = /(^|<br>|<p>)\s*([A-Z][A-Za-z0-9\s\.]{1,20})\s*:/g;
            html = html.replace(roleRegex, function(match, prefix, role) {
                const r = role.toLowerCase().trim();
                let cls = 'role-s';
                if (r.startsWith('pdt') || r.startsWith('pemimpin') || r === 'p') cls = 'role-p';
                else if (r.startsWith('jemaat') || r === 'j' || r === 'u') cls = 'role-j';
                else if (r.startsWith('lektor') || r === 'l') cls = 'role-l';
                return prefix + '<span class="role-badge ' + cls + '">' + role + '</span> ';
            });

            // 2. Songs
            const songRegex = /\[(KJ|PKJ|NKB|Kidung|Nyanyian|Pujian)\s*[:\.]?\s*(\d+[^\]]+)\]/gi;
            html = html.replace(songRegex, '<div class="song-block"><div class="flex items-center justify-center space-x-3 mb-2"><ion-icon name="musical-notes" class="text-accent text-xl"></ion-icon><span class="text-[10px] font-black uppercase tracking-[0.3em] text-accent">$1 $2</span></div></div>');

            // 3. Bible
            const bibleRegex = /\[((?:[123]\s)?(?:[A-Z][a-z]+)\s\d+:\d+[^\]]*)\]/g;
            html = html.replace(bibleRegex, '<div class="bible-block"><div class="flex items-center space-x-3 mb-4"><ion-icon name="book" class="text-indigo-premium"></ion-icon><span class="text-[10px] font-black uppercase tracking-[0.2em] text-indigo-premium">$1</span></div></div>');

            content.innerHTML = html;
        }

        document.addEventListener('DOMContentLoaded', applyLiquidUI);
    </script>
</body>
</html>
