<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Warta Gereja' ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= base_url('favicon.ico') ?>">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Animate On Scroll (AOS) -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- PWA Settings -->
    <link rel="manifest" href="<?= base_url('manifest.json') ?>">
    <meta name="theme-color" content="#0F172A">
    <link rel="apple-touch-icon" href="/uploads/gkj.png">
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('<?= base_url('sw.js') ?>')
                    .then(reg => console.log('SW Registered', reg))
                    .catch(err => console.log('SW Failed', err));
            });
        }
    </script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#0F172A', // Deep Navy
                        'secondary': '#1E293B', // Slate
                        'accent': '#D4AF37', // Elegant Gold
                        'gold-light': '#F3E5AB',
                        'soft-bg': '#F8FAFC',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        heading: ['Outfit', 'sans-serif'],
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-20px)' },
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root {
            scroll-behavior: smooth;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: #F8FAFC;
            color: #0F172A;
        }
        h1, h2, h3, h4, h5, h6, .font-heading {
            font-family: 'Outfit', sans-serif;
        }
        .glass {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        .glass-dark {
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        .text-gradient {
            background: linear-gradient(135deg, #D4AF37 0%, #F3E5AB 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .bg-gradient {
            background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);
        }
        .btn-gold {
            background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%);
            box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
        }
        .btn-gold:hover {
            box-shadow: 0 6px 20px rgba(212, 175, 55, 0.4);
        }
        /* Mobile Nav Active State */
        .mobile-nav-active {
            color: #D4AF37 !important;
        }
        .mobile-nav-active ion-icon {
            transform: translateY(-4px);
            color: #D4AF37;
        }

        .gold-filter {
            /* Extreme force to black, then high-quality Gold transform */
            filter: brightness(0) saturate(100%) invert(72%) sepia(51%) saturate(544%) hue-rotate(355deg) brightness(91%) contrast(88%) !important;
            -webkit-filter: brightness(0) saturate(100%) invert(72%) sepia(51%) saturate(544%) hue-rotate(355deg) brightness(91%) contrast(88%) !important;
        }

        .bubble {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 
                inset 0 0 20px rgba(255, 255, 255, 0.1),
                0 0 15px rgba(212, 175, 55, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .bubble-glare {
            position: absolute;
            top: 15%;
            left: 15%;
            width: 30%;
            height: 30%;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.4) 0%, transparent 100%);
            border-radius: 50%;
        }
    </style>
</head>
<?php 
    $is_home_or_warta = (current_url() == base_url() || strpos(current_url(), 'warta') !== false);
?>
<body class="flex flex-col min-h-screen <?= !$is_home_or_warta ? 'text-xs' : '' ?>">
    <?= $this->include('partials/loader') ?>
    <!-- Ambient Background Animation -->
    <div id="ambient-logos" class="fixed inset-0 pointer-events-none z-[30] overflow-hidden"></div>

    <!-- Desktop Navigation -->
    <nav class="fixed w-full z-50 transition-all duration-300 glass border-b border-slate-200/50 hidden md:block">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex justify-between h-14">
                <div class="flex items-center space-x-4 group cursor-pointer">
                    <?php if(!empty($gereja['logo'])): ?>
                        <div class="relative">
                            <div class="absolute -inset-2 bg-accent/20 rounded-full blur-[6px] group-hover:bg-accent/30 transition duration-500"></div>
                            <img class="relative h-9 w-9 rounded-full object-cover border-2 border-accent/50 p-0.5 shadow-xl gold-filter" src="<?= base_url('uploads/'.$gereja['logo']) ?>" alt="Logo">
                        </div>
                    <?php else: ?>
                        <div class="h-9 w-9 flex items-center justify-center bg-gradient-to-tr from-primary to-secondary rounded-xl text-accent shadow-xl shadow-primary/20 border border-white/10 group-hover:rotate-6 transition-transform duration-500">
                             <ion-icon name="business" class="text-xl"></ion-icon>
                        </div>
                    <?php endif; ?>
                    <div class="flex flex-col">
                        <span class="font-heading font-extrabold text-lg tracking-tight leading-none text-primary group-hover:text-accent transition-colors">
                            <?= $gereja['nama_gereja'] ?? 'Warta Gereja' ?>
                        </span>
                        <!-- <span class="text-[10px] uppercase tracking-[0.3em] text-accent/60 font-black mt-1.5">Soli Deo Gloria</span> -->
                    </div>
                </div>
                <div class="flex items-center space-x-1">
                    <a href="<?= base_url() ?>" class="px-3 py-1.5 text-slate-600 hover:text-primary font-semibold transition-all text-xs rounded-lg hover:bg-slate-100/50">Beranda</a>
                    <?php if(isset($config['menu_warta'])): ?>
                    <a href="<?= base_url('warta') ?>" class="px-3 py-1.5 text-slate-600 hover:text-primary font-semibold transition-all text-xs rounded-lg hover:bg-slate-100/50">Warta</a>
                    <?php endif; ?>
                    <?php if(isset($config['menu_liturgi'])): ?>
                    <a href="<?= base_url('liturgi') ?>" class="px-3 py-1.5 text-slate-600 hover:text-primary font-semibold transition-all text-xs rounded-lg hover:bg-slate-100/50">Liturgi</a>
                    <?php endif; ?>
                    <?php if(isset($config['menu_informasi'])): ?>
                    <a href="<?= base_url('informasi') ?>" class="px-3 py-1.5 text-slate-600 hover:text-primary font-semibold transition-all text-xs rounded-lg hover:bg-slate-100/50">Informasi</a>
                    <?php endif; ?>
                    <?php if(isset($config['menu_artikel'])): ?>
                    <a href="<?= base_url('artikel') ?>" class="px-3 py-1.5 text-slate-600 hover:text-primary font-semibold transition-all text-xs rounded-lg hover:bg-slate-100/50">Artikel</a>
                    <?php endif; ?>
                    <?php if(isset($config['menu_galeri'])): ?>
                    <a href="<?= base_url('galeri') ?>" class="px-3 py-1.5 text-slate-600 hover:text-primary font-semibold transition-all text-xs rounded-lg hover:bg-slate-100/50">Galeri</a>
                    <?php endif; ?>
                    <?php if(isset($config['menu_diskusi'])): ?>
                    <a href="<?= base_url('diskusi') ?>" class="px-3 py-1.5 text-slate-600 hover:text-primary font-semibold transition-all text-xs rounded-lg hover:bg-slate-100/50">Diskusi</a>
                    <?php endif; ?>
                    <?php if(isset($config['menu_renungan']) || isset($config['menu_jadwal']) || isset($config['menu_kegiatan'])): ?>
                    <div class="relative group px-1">
                        <button class="px-3 py-1.5 text-slate-600 hover:text-primary font-semibold transition-all text-xs rounded-lg hover:bg-slate-100/50 flex items-center">
                            Lainnya <ion-icon name="chevron-down" class="ml-1 text-[10px]"></ion-icon>
                        </button>
                        <div class="absolute right-0 top-full pt-2 w-48 opacity-0 translate-y-2 pointer-events-none group-hover:opacity-100 group-hover:translate-y-0 group-hover:pointer-events-auto transition-all duration-300 z-50">
                            <div class="glass border border-slate-200 shadow-xl rounded-xl p-2 space-y-1">
                                <?php if(isset($config['menu_renungan'])): ?>
                                <a href="<?= base_url('renungan') ?>" class="block px-4 py-2 text-sm text-slate-600 hover:text-primary hover:bg-slate-100 rounded-lg font-medium transition-colors">Renungan</a>
                                <?php endif; ?>
                                <?php if(isset($config['menu_jadwal'])): ?>
                                <a href="<?= base_url('jadwal') ?>" class="block px-4 py-2 text-sm text-slate-600 hover:text-primary hover:bg-slate-100 rounded-lg font-medium transition-colors">Jadwal</a>
                                <?php endif; ?>
                                <?php if(isset($config['menu_kegiatan'])): ?>
                                <a href="<?= base_url('kegiatan') ?>" class="block px-4 py-2 text-sm text-slate-600 hover:text-primary hover:bg-slate-100 rounded-lg font-medium transition-colors">Kegiatan</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="pl-3 ml-3 border-l border-slate-200 flex-shrink-0">
                        <a href="<?= base_url('login') ?>" class="whitespace-nowrap px-4 py-1.5 bg-primary text-white text-xs font-bold rounded-lg hover:bg-slate-800 transition-all shadow-lg shadow-primary/20 transform hover:-translate-y-0.5">
                            Portal Admin
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <nav class="fixed top-0 w-full z-50 glass-dark border-b border-white/10 md:hidden h-10 flex items-center px-4 justify-between">
        <div class="flex items-center space-x-3">
            <?php if(!empty($gereja['logo'])): ?>
                <div class="relative">
                    <div class="absolute -inset-1 bg-accent/20 rounded-full blur-[4px]"></div>
                    <img class="relative h-6 w-6 rounded-full object-cover border border-accent/30 gold-filter" src="<?= base_url('uploads/'.$gereja['logo']) ?>" alt="Logo">
                </div>
            <?php endif; ?>
            <span class="font-heading font-extrabold text-sm tracking-tight text-white leading-tight">
                <?= $gereja['nama_gereja'] ?>
            </span>
        </div>
        <a href="<?= base_url('login') ?>" class="w-7 h-7 flex items-center justify-center bg-white/10 rounded-full text-white/80 hover:text-accent transition-colors">
            <ion-icon name="person-outline" class="text-base"></ion-icon>
        </a>
    </nav>

    <!-- Mobile Bottom Navigation -->
    <nav class="fixed bottom-0 w-full z-50 glass-dark border-t border-white/10 md:hidden pb-safe">
        <div class="flex justify-around items-center h-14 px-2">
            <a href="<?= base_url() ?>" class="flex flex-col items-center justify-center w-full text-slate-400 transition-all duration-300 <?= current_url() == base_url() ? 'mobile-nav-active' : '' ?>">
                <ion-icon name="home" class="text-xl transition-transform"></ion-icon>
                <span class="text-[10px] font-bold mt-1 uppercase tracking-tighter">Beranda</span>
            </a>
            <?php if(isset($config['menu_warta'])): ?>
            <a href="<?= base_url('warta') ?>" class="flex flex-col items-center justify-center w-full text-slate-400 transition-all duration-300 <?= strpos(current_url(), 'warta') !== false ? 'mobile-nav-active' : '' ?>">
                <ion-icon name="newspaper" class="text-xl transition-transform"></ion-icon>
                <span class="text-[10px] font-bold mt-1 uppercase tracking-tighter">Warta</span>
            </a>
            <?php endif; ?>
            <?php if(isset($config['menu_liturgi'])): ?>
            <a href="<?= base_url('liturgi') ?>" class="flex flex-col items-center justify-center w-full text-slate-400 transition-all duration-300 <?= strpos(current_url(), 'liturgi') !== false ? 'mobile-nav-active' : '' ?>">
                <ion-icon name="book" class="text-xl transition-transform"></ion-icon>
                <span class="text-[10px] font-bold mt-1 uppercase tracking-tighter">Liturgi</span>
            </a>
            <?php endif; ?>
            <?php if(isset($config['menu_informasi'])): ?>
            <a href="<?= base_url('informasi') ?>" class="flex flex-col items-center justify-center w-full text-slate-400 transition-all duration-300 <?= strpos(current_url(), 'informasi') !== false ? 'mobile-nav-active' : '' ?>">
                <ion-icon name="information-circle" class="text-xl transition-transform"></ion-icon>
                <span class="text-[10px] font-bold mt-1 uppercase tracking-tighter">Info</span>
            </a>
            <?php endif; ?>
            <button id="mobileMoreBtn" class="flex flex-col items-center justify-center w-full text-slate-400 transition-all duration-300">
                <ion-icon name="grid" class="text-xl transition-transform"></ion-icon>
                <span class="text-[10px] font-bold mt-1 uppercase tracking-tighter">Menu</span>
            </button>
        </div>
    </nav>

    <!-- Mobile Menu Modal -->
    <div id="mobileMenu" class="fixed inset-0 z-[60] glass-dark opacity-0 pointer-events-none transition-all duration-500 overflow-hidden">
        <div class="flex flex-col h-full p-8 pt-20">
            <button id="closeMobileMenu" class="absolute top-6 right-6 text-white text-4xl">
                <ion-icon name="close-circle-outline"></ion-icon>
            </button>
            <div class="space-y-4">
                <h4 class="text-accent text-[10px] font-bold uppercase tracking-[0.3em] mb-2">Eksplorasi</h4>
                <?php if(isset($config['menu_renungan'])): ?>
                <a href="<?= base_url('renungan') ?>" class="block text-2xl font-heading font-bold text-white hover:text-accent transition-colors">Renungan</a>
                <?php endif; ?>
                <?php if(isset($config['menu_kegiatan'])): ?>
                <a href="<?= base_url('kegiatan') ?>" class="block text-2xl font-heading font-bold text-white hover:text-accent transition-colors">Kegiatan</a>
                <?php endif; ?>
                <?php if(isset($config['menu_artikel'])): ?>
                <a href="<?= base_url('artikel') ?>" class="block text-2xl font-heading font-bold text-white hover:text-accent transition-colors">Artikel</a>
                <?php endif; ?>
                <?php if(isset($config['menu_galeri'])): ?>
                <a href="<?= base_url('galeri') ?>" class="block text-2xl font-heading font-bold text-white hover:text-accent transition-colors">Galeri Video</a>
                <?php endif; ?>
                <?php if(isset($config['menu_diskusi'])): ?>
                <a href="<?= base_url('diskusi') ?>" class="block text-2xl font-heading font-bold text-white hover:text-accent transition-colors">Diskusi</a>
                <?php endif; ?>
                <?php if(isset($config['menu_jadwal'])): ?>
                <a href="<?= base_url('jadwal') ?>" class="block text-2xl font-heading font-bold text-white hover:text-accent transition-colors">Jadwal Ibadah</a>
                <?php endif; ?>
                
                <div class="h-px bg-white/10 my-6"></div>
                
                <h4 class="text-accent text-[10px] font-bold uppercase tracking-[0.3em] mb-2">Kontak</h4>
                <div class="space-y-4">
                    <div class="flex items-center text-white/70">
                        <ion-icon name="location" class="text-accent mr-3"></ion-icon>
                        <span class="text-sm"><?= $gereja['alamat'] ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="flex-grow pt-10 md:pt-14 pb-20 md:pb-0">
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <footer class="bg-primary text-slate-400 pt-8 pb-6 border-t border-white/5">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-1 md:col-span-2 space-y-4">
                    <h3 class="font-heading font-extrabold text-2xl text-gradient mb-1">
                        <?= $gereja['nama_gereja'] ?? 'Aplikasi Gereja' ?>
                    </h3>
                    <p class="leading-relaxed max-w-sm text-slate-300 font-medium text-xs">
                        <?= $gereja['deskripsi'] ?? 'Melayani umat dengan kasih dan teknologi.' ?>
                    </p>
                    <div class="flex space-x-2 pt-2">
                        <a href="<?= !empty($gereja['fb']) ? $gereja['fb'] : '#' ?>" target="_blank" class="w-8 h-8 flex items-center justify-center rounded-full bg-white/5 border border-white/10 text-accent hover:bg-accent hover:text-primary hover:scale-110 transition-all duration-300 shadow-lg backdrop-blur-sm"><ion-icon name="logo-facebook" class="text-lg"></ion-icon></a>
                        <a href="<?= !empty($gereja['ig']) ? $gereja['ig'] : '#' ?>" target="_blank" class="w-8 h-8 flex items-center justify-center rounded-full bg-white/5 border border-white/10 text-accent hover:bg-accent hover:text-primary hover:scale-110 transition-all duration-300 shadow-lg backdrop-blur-sm"><ion-icon name="logo-instagram" class="text-lg"></ion-icon></a>
                        <a href="<?= !empty($gereja['tt']) ? $gereja['tt'] : '#' ?>" target="_blank" class="w-8 h-8 flex items-center justify-center rounded-full bg-white/5 border border-white/10 text-accent hover:bg-accent hover:text-primary hover:scale-110 transition-all duration-300 shadow-lg backdrop-blur-sm"><ion-icon name="logo-tiktok" class="text-lg"></ion-icon></a>
                        <a href="<?= !empty($gereja['yt']) ? $gereja['yt'] : '#' ?>" target="_blank" class="w-8 h-8 flex items-center justify-center rounded-full bg-white/5 border border-white/10 text-accent hover:bg-accent hover:text-primary hover:scale-110 transition-all duration-300 shadow-lg backdrop-blur-sm"><ion-icon name="logo-youtube" class="text-lg"></ion-icon></a>
                        <a href="<?= !empty($gereja['telp']) ? 'https://wa.me/'.preg_replace('/[^0-9]/','',$gereja['telp']) : '#' ?>" target="_blank" class="w-8 h-8 flex items-center justify-center rounded-full bg-white/5 border border-white/10 text-accent hover:bg-accent hover:text-primary hover:scale-110 transition-all duration-300 shadow-lg backdrop-blur-sm"><ion-icon name="logo-whatsapp" class="text-lg"></ion-icon></a>
                    </div>
                </div>
                <div>
                    <h4 class="font-heading font-bold text-base text-white mb-3">Navigasi</h4>
                    <ul class="space-y-2 font-semibold text-xs">
                        <li><a href="<?= base_url('warta') ?>" class="hover:text-accent transition-colors">Laporan Keuangan</a></li>
                        <li><a href="<?= base_url('jadwal') ?>" class="hover:text-accent transition-colors">Jadwal Ibadah</a></li>
                        <li><a href="<?= base_url('liturgi') ?>" class="hover:text-accent transition-colors">Liturgi Minggu</a></li>
                        <li><a href="<?= base_url('kegiatan') ?>" class="hover:text-accent transition-colors">Kegiatan Gereja</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-heading font-bold text-base text-white mb-3">Layanan</h4>
                    <ul class="space-y-2 text-xs font-medium">
                        <li class="flex items-start">
                            <ion-icon name="location-outline" class="mr-3 mt-1 text-accent text-lg shrink-0"></ion-icon>
                            <div class="space-y-3 w-full">
                                <span><?= $gereja['alamat'] ?? 'Alamat Gereja' ?></span>
                                <!-- Google Maps Embed -->
                                <div class="w-full h-32 bg-slate-800 rounded-xl overflow-hidden border border-white/10 shadow-lg">
                                    <iframe 
                                        width="100%" 
                                        height="100%" 
                                        frameborder="0" 
                                        style="border:0" 
                                        src="https://maps.google.com/maps?q=<?= urlencode($gereja['nama_gereja'] ?? 'Gereja') ?>&t=&z=15&ie=UTF8&iwloc=&output=embed" 
                                        allowfullscreen>
                                    </iframe>
                                </div>
                            </div>
                        </li>
                        <li class="flex items-center">
                            <ion-icon name="mail-outline" class="mr-3 text-accent text-lg shrink-0"></ion-icon>
                            <span><?= $gereja['email'] ?? '-' ?></span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-white/5 mt-8 pt-4 flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0 text-[10px] font-bold uppercase tracking-widest text-slate-500">
                <div>&copy; <a href="https://appsbee.my.id" target="_blank" class="hover:text-accent transition-colors">appsbee</a> <?= date('Y') ?> <?= $gereja['nama_gereja'] ?>. All rights reserved.</div>
            </div>
        </div>
    </footer>

    <!-- Mobile Floating Social Buttons -->
    <!-- Mobile Floating Social Buttons -->
    <div class="fixed left-2 bottom-24 z-40 flex flex-col space-y-3 md:hidden">
        
        <?php if(!empty($gereja['yt'])): ?>
        <a href="<?= $gereja['yt'] ?>" target="_blank" class="w-8 h-8 flex items-center justify-center text-accent hover:scale-110 transition-transform bg-white/10 backdrop-blur-sm rounded-full border border-white/5 shadow-sm animate-pulse" style="animation-delay: 0s;">
            <ion-icon name="logo-youtube" class="text-lg"></ion-icon>
        </a>
        <?php endif; ?>

        <?php if(!empty($gereja['ig'])): ?>
        <a href="<?= $gereja['ig'] ?>" target="_blank" class="w-8 h-8 flex items-center justify-center text-accent hover:scale-110 transition-transform bg-white/10 backdrop-blur-sm rounded-full border border-white/5 shadow-sm animate-pulse" style="animation-delay: 0.5s;">
            <ion-icon name="logo-instagram" class="text-lg"></ion-icon>
        </a>
        <?php endif; ?>

        <?php if(!empty($gereja['fb'])): ?>
        <a href="<?= $gereja['fb'] ?>" target="_blank" class="w-8 h-8 flex items-center justify-center text-accent hover:scale-110 transition-transform bg-white/10 backdrop-blur-sm rounded-full border border-white/5 shadow-sm animate-pulse" style="animation-delay: 1s;">
            <ion-icon name="logo-facebook" class="text-lg"></ion-icon>
        </a>
        <?php endif; ?>

        <?php if(!empty($gereja['tt'])): ?>
        <a href="<?= $gereja['tt'] ?>" target="_blank" class="w-8 h-8 flex items-center justify-center text-accent hover:scale-110 transition-transform bg-white/10 backdrop-blur-sm rounded-full border border-white/5 shadow-sm animate-pulse" style="animation-delay: 1.5s;">
            <ion-icon name="logo-tiktok" class="text-lg"></ion-icon>
        </a>
        <?php endif; ?>

        <?php if(!empty($gereja['telp'])): ?>
        <a href="https://wa.me/<?= preg_replace('/[^0-9]/','',$gereja['telp']) ?>" target="_blank" class="w-8 h-8 flex items-center justify-center text-accent hover:scale-110 transition-transform bg-white/10 backdrop-blur-sm rounded-full border border-white/5 shadow-sm animate-pulse" style="animation-delay: 2s;">
            <ion-icon name="logo-whatsapp" class="text-xl"></ion-icon>
        </a>
        <?php endif; ?>
    </div>

    <!-- Ionicons -->
    <script type="module" src="https://cdn.jsdelivr.net/npm/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://cdn.jsdelivr.net/npm/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <script>
        // AOS initialization
        AOS.init({
            duration: 800,
            once: true,
            offset: 100
        });

        // Mobile Menu Logic
        const mobileMenu = document.getElementById('mobileMenu');
        const mobileMoreBtn = document.getElementById('mobileMoreBtn');
        const closeMobileMenu = document.getElementById('closeMobileMenu');

        mobileMoreBtn.addEventListener('click', () => {
            mobileMenu.classList.remove('opacity-0', 'pointer-events-none');
            mobileMenu.classList.add('opacity-100', 'pointer-events-auto');
            document.body.style.overflow = 'hidden';
        });

        closeMobileMenu.addEventListener('click', () => {
            mobileMenu.classList.add('opacity-0', 'pointer-events-none');
            mobileMenu.classList.remove('opacity-100', 'pointer-events-auto');
            document.body.style.overflow = 'auto';
        });

        // Close menu on link click
        document.querySelectorAll('#mobileMenu a').forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.add('opacity-0', 'pointer-events-none');
                mobileMenu.classList.remove('opacity-100', 'pointer-events-auto');
                document.body.style.overflow = 'auto';
            });
        });

        // Ambient Background Logos Physics Engine
        const ambientContainer = document.getElementById('ambient-logos');
        const logoFile = '<?= $gereja["logo"] ?? "" ?>';
        const logoUrl = logoFile ? '<?= base_url("uploads") ?>/' + logoFile : null;
        const animationEnabled = <?= isset($config['bubble_animation']) ? 'true' : 'false' ?>;
        
        if (logoUrl && animationEnabled) {
            const bubbles = [];
            const logoCount = 8;
            
            class Bubble {
                constructor(id) {
                    const isMobile = window.innerWidth < 768;
                    if (isMobile) {
                        this.size = Math.floor(Math.random() * 30) + 30; // 30px - 60px for mobile
                    } else {
                        this.size = Math.floor(Math.random() * 50) + 70; // 70px - 120px for desktop
                    }
                    this.radius = this.size / 2;
                    
                    // Element Creation
                    this.el = document.createElement('div');
                    this.el.className = 'absolute bubble transition-opacity duration-1000';
                    this.el.style.width = `${this.size}px`;
                    this.el.style.height = `${this.size}px`;
                    this.el.style.opacity = '0';
                    
                    const glare = document.createElement('div');
                    glare.className = 'bubble-glare';
                    this.el.appendChild(glare);
                    
                    const img = document.createElement('img');
                    img.src = logoUrl;
                    img.className = 'gold-filter';
                    img.style.width = '60%';
                    img.style.height = '60%';
                    img.style.objectFit = 'contain';
                    img.style.filter = 'sepia(100%) saturate(1000%) hue-rotate(45deg) brightness(80%) contrast(120%)'; // Darker Gold/Bronze for white BG
                    img.style.webkitFilter = 'sepia(100%) saturate(1000%) hue-rotate(45deg) brightness(80%) contrast(120%)';
                    this.el.appendChild(img);
                    
                    ambientContainer.appendChild(this.el);
                    
                    // State
                    this.x = Math.random() * (window.innerWidth - this.size) + this.radius;
                    this.y = Math.random() * (window.innerHeight - this.size) + this.radius;
                    
                    const speed = 0.3 + Math.random() * 0.4;
                    const angle = Math.random() * Math.PI * 2;
                    this.vx = Math.cos(angle) * speed;
                    this.vy = Math.sin(angle) * speed;
                    
                    this.opacity = 0;
                    this.fadePhase = Math.random() * Math.PI * 2;
                    this.fadeSpeed = 0.005 + Math.random() * 0.01;
                }

                update() {
                    // Movement
                    this.x += this.vx;
                    this.y += this.vy;

                    // Wall Bounce
                    if (this.x < this.radius) { this.x = this.radius; this.vx *= -1; }
                    if (this.x > window.innerWidth - this.radius) { this.x = window.innerWidth - this.radius; this.vx *= -1; }
                    if (this.y < this.radius) { this.y = this.radius; this.vy *= -1; }
                    if (this.y > window.innerHeight - this.radius) { this.y = window.innerHeight - this.radius; this.vy *= -1; }

                    // Opacity Pulse (Appear/Disappear)
                    this.fadePhase += this.fadeSpeed;
                    this.opacity = (Math.sin(this.fadePhase) + 1) / 2 * 0.6; // Max 0.6 opacity (Increased for white bg)
                    this.el.style.opacity = this.opacity;
                    
                    // Render
                    this.el.style.transform = `translate(${this.x - this.radius}px, ${this.y - this.radius}px)`;
                }

                static resolveCollision(b1, b2) {
                    const dx = b2.x - b1.x;
                    const dy = b2.y - b1.y;
                    const dist = Math.sqrt(dx*dx + dy*dy);
                    const minDist = b1.radius + b2.radius;

                    if (dist < minDist) {
                        // Collision normal
                        const nx = dx / dist;
                        const ny = dy / dist;

                        // Resolve overlap
                        const overlap = minDist - dist;
                        b1.x -= nx * overlap / 2;
                        b1.y -= ny * overlap / 2;
                        b2.x += nx * overlap / 2;
                        b2.y += ny * overlap / 2;

                        // Bounce (Simple elastic)
                        const v1n = b1.vx * nx + b1.vy * ny;
                        const v2n = b2.vx * nx + b2.vy * ny;

                        if (v1n > v2n) {
                            const commonVelocity = v1n - v2n;
                            b1.vx -= commonVelocity * nx;
                            b1.vy -= commonVelocity * ny;
                            b2.vx += commonVelocity * nx;
                            b2.vy += commonVelocity * ny;
                        }
                    }
                }
            }

            for (let i = 0; i < logoCount; i++) {
                bubbles.push(new Bubble(i));
            }

            function animate() {
                // Check collisions between all pairs
                for (let i = 0; i < bubbles.length; i++) {
                    for (let j = i + 1; j < bubbles.length; j++) {
                        Bubble.resolveCollision(bubbles[i], bubbles[j]);
                    }
                }

                // Update and render
                bubbles.forEach(b => b.update());
                requestAnimationFrame(animate);
            }

            animate();

            // Handle resize
            window.addEventListener('resize', () => {
                bubbles.forEach(b => {
                    b.x = Math.min(b.x, window.innerWidth - b.radius);
                    b.y = Math.min(b.y, window.innerHeight - b.radius);
                });
            });
        }
    </script>

    <!-- Scripts -->
    <?= $this->renderSection('scripts') ?>
</body>
</html>
