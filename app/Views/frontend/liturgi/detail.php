<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= $title ?? 'Liturgi' ?></title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'primary': '#0F172A',
                        'accent': '#D4AF37',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        heading: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <!-- Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif; 
            background-color: #F8FAFC;
            color: #0F172A;
            padding-bottom: 80px; 
            line-height: 1.8;
        }
        h1, h2, h3, h4, .font-heading {
            font-family: 'Outfit', sans-serif;
        }
        
        /* Reader Mode Typography */
        .prose p {
            margin-bottom: 2rem;
            font-size: 1.125rem; 
        }
        
        /* Dark Mode Support */
        body.dark-mode {
            background-color: #0F172A;
            color: #F8FAFC;
        }
        body.dark-mode header {
            background-color: rgba(15, 23, 42, 0.9);
            border-color: rgba(255, 255, 255, 0.1);
        }
        body.dark-mode .text-primary {
            color: #F8FAFC;
        }
        body.dark-mode .border-slate-100 {
            border-color: rgba(255, 255, 255, 0.05);
        }
    </style>
</head>
<body class="transition-colors duration-500">

    <!-- Sticky Header -->
    <header class="fixed top-0 left-0 right-0 bg-white/90 backdrop-blur-md border-b border-slate-200 z-50 px-6 py-4 flex items-center justify-between shadow-sm transition-all duration-300">
        <a href="<?= base_url('liturgi') ?>" class="text-primary hover:text-accent transition-colors">
            <ion-icon name="chevron-back" class="text-2xl"></ion-icon>
        </a>
        <h1 class="font-heading font-extrabold text-sm uppercase tracking-[0.2em] text-primary truncate max-w-[200px] text-center">
            Reader Mode
        </h1>
        <button id="themeToggle" class="text-primary hover:text-accent p-1 focus:outline-none transition-colors">
            <ion-icon name="moon" class="text-2xl" id="themeIcon"></ion-icon>
        </button>
    </header>

    <!-- Main Reader Content -->
    <main class="pt-24 px-6 max-w-3xl mx-auto">
        
        <div class="mb-12 text-center border-b border-slate-100 pb-8">
            <span class="text-[10px] font-bold text-accent uppercase tracking-[0.4em] mb-4 block">Bahan Tata Ibadah</span>
            <h2 class="text-4xl font-extrabold text-primary mb-4 font-heading leading-tight"><?= $item['judul'] ?></h2>
            <p class="text-sm text-slate-400 font-bold uppercase tracking-widest">
                <?= date('d F Y', strtotime($item['tanggal'])) ?>
            </p>
        </div>

        <div class="prose prose-lg max-w-none prose-slate">
            <!-- Content Injected Here -->
            <div class="reader-content text-lg">
                <?= $item['isi_liturgi'] ?>
            </div>
        </div>

    </main>

    <script>
        const toggleBtn = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        const body = document.body;

        toggleBtn.addEventListener('click', () => {
            body.classList.toggle('dark-mode');
            
            if (body.classList.contains('dark-mode')) {
                themeIcon.setAttribute('name', 'sunny');
                localStorage.setItem('reader-theme', 'dark');
            } else {
                themeIcon.setAttribute('name', 'moon');
                localStorage.setItem('reader-theme', 'light');
            }
        });

        // Load preference
        if (localStorage.getItem('reader-theme') === 'dark') {
            body.classList.add('dark-mode');
            themeIcon.setAttribute('name', 'sunny');
        }
    </script>
</body>
</html>
