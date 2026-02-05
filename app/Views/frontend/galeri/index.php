<?= $this->extend('layouts/frontend') ?>

<?= $this->section('content') ?>

<?php
// Helper functions
// Helper functions
function getDriveInfo($url) {
    // Detect File
    if (preg_match('/file\/d\/([a-zA-Z0-9-_]+)/', $url, $matches)) {
        return ['type' => 'file', 'id' => $matches[1]];
    }
    // Detect Folder
    if (preg_match('/folders\/([a-zA-Z0-9-_]+)/', $url, $matches)) {
        return ['type' => 'folder', 'id' => $matches[1]];
    }
    // Fallback/Legacy ID only
    if (preg_match('/id=([a-zA-Z0-9-_]+)/', $url, $matches)) {
        // Assume folder if using id= param usually, or standard
        return ['type' => 'folder', 'id' => $matches[1]];
    }
    return ['type' => 'unknown', 'id' => $url];
}
?>

<!-- Hero Section -->
<section class="bg-primary pt-24 pb-32 border-b border-white/5 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#D4AF37 0.5px, transparent 0.5px); background-size: 20px 20px;"></div>
    <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center relative z-10" data-aos="fade-down">
        <span class="text-xs font-bold uppercase tracking-[0.4em] text-accent mb-4 block">Koleksi Multimedia</span>
        <h1 class="text-3xl md:text-4xl font-extrabold text-white font-heading">Galeri Gereja</h1>
        <p class="text-slate-400 mt-4 font-medium max-w-xl mx-auto text-xs">Dokumentasi kegiatan, rekaman ibadah, dan koleksi pujian yang memberkati iman percaya kita.</p>
    </div>
</section>

<!-- Content Section -->
<div class="max-w-7xl mx-auto px-6 lg:px-8 -mt-16 mb-24 relative z-10">
    
    <?php if(empty($collections)): ?>
        <div class="bg-white p-16 rounded-[40px] shadow-xl text-center text-slate-400 font-medium italic border border-slate-100" data-aos="zoom-in">
             <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-slate-50 text-slate-300 mb-6">
                <ion-icon name="images-outline" class="text-4xl"></ion-icon>
            </div>
            <h3 class="text-xl font-bold text-slate-800 mb-2">Belum Ada Galeri</h3>
            <p class="text-slate-500">Koleksi multimedia akan segera ditambahkan.</p>
        </div>
    <?php else: ?>

        <!-- TOOLBAR: DYNAMIC TABS + SEARCH -->
        <div class="bg-white rounded-[24px] shadow-lg border border-slate-100 p-2 mb-8 flex flex-col md:flex-row items-center justify-between gap-4 sticky top-24 z-30 transition-all duration-300 transform" id="mainToolbar" data-aos="fade-up">
            
            <!-- Dynamic Tabs based on Grouped Collections -->
            <div class="flex items-center p-1 bg-slate-50 rounded-xl w-full md:w-auto overflow-x-auto gap-2 scrollbar-hide">
                <?php $tabIndex = 0; foreach($collections as $tabName => $group): ?>
                    <button onclick="switchTab('col-<?= $tabIndex ?>')" id="tab-col-<?= $tabIndex ?>" class="tab-btn flex-shrink-0 flex items-center space-x-2 px-6 py-2.5 rounded-lg text-sm font-bold transition-all duration-300 <?= $tabIndex === 0 ? 'bg-white text-slate-800 shadow-sm ring-1 ring-slate-200' : 'text-slate-500 hover:text-slate-700' ?>">
                        <?php 
                            // Icon heuristic based on first item
                            $firstItem = $group['items'][0];
                            if($firstItem['kategori'] == 'youtube' || $firstItem['kategori'] == 'youtube_video') {
                                echo '<ion-icon name="videocam"></ion-icon>';
                            } elseif($firstItem['kategori'] == 'drive_img') {
                                echo '<ion-icon name="images"></ion-icon>';
                            } elseif($firstItem['kategori'] == 'drive_audio') {
                                echo '<ion-icon name="musical-notes"></ion-icon>';
                            } else {
                                echo '<ion-icon name="folder"></ion-icon>';
                            }
                        ?>
                        <span class="whitespace-nowrap"><?= esc($tabName) ?></span>
                    </button>
                    <?php $tabIndex++; endforeach; ?>
            </div>

            <!-- Search -->
            <div class="relative w-full md:w-72 pr-2">
                 <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <ion-icon name="search-outline" class="text-slate-400"></ion-icon>
                </div>
                <input type="text" id="globalSearch" class="block w-full pl-10 pr-4 py-2.5 border-0 bg-slate-50 hover:bg-white focus:bg-white rounded-xl text-slate-700 placeholder-slate-400 focus:ring-2 focus:ring-indigo-100 transition-all duration-200" placeholder="Cari dalam ruang ini...">
            </div>
        </div>

        <!-- DYNAMIC CONTENT SECTIONS -->
        <?php $tabIndex = 0; foreach($collections as $tabName => $group): ?>
            <div id="content-col-<?= $tabIndex ?>" class="tab-content <?= $tabIndex === 0 ? '' : 'hidden' ?> transition-opacity duration-300">
                
                <?php $subIndex = 0; foreach($group['items'] as $item): 
                    $uniqueId = "{$tabIndex}-{$subIndex}";
                    $searchKeywords = strtolower($item['display_title'] . ' ' . $item['judul']);
                ?>
                    
                    <div class="searchable-section mb-12 border-b border-slate-100 pb-8 last:border-0 last:pb-0" id="section-<?= $uniqueId ?>" data-keywords="<?= esc($searchKeywords) ?>">
                        <!-- Header & Search Wrapper -->
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                            
                            <!-- Title Area -->
                            <div class="flex items-center space-x-3">
                                <?php if(!empty($item['display_title'])): ?>
                                    <h3 class="text-sm font-bold text-slate-700 font-heading pl-3 border-l-4 border-primary"><?= esc($item['display_title']) ?></h3>
                                <?php elseif(count($group['items']) == 1): ?>
                                    <!-- Single item in tab, maybe show source info or standard title -->
                                    <div class="flex items-center space-x-2 text-slate-500">
                                        <ion-icon name="grid-outline" class="text-primary"></ion-icon>
                                        <span class="text-[10px] font-bold uppercase tracking-wider">Koleksi</span>
                                    </div>
                                <?php else: ?>
                                    <h3 class="text-sm font-bold text-slate-700 font-heading">Koleksi #<?= $subIndex + 1 ?></h3>
                                <?php endif; ?>
                            </div>

                            <!-- Local Search (Only for YouTube/Grid items that support it) -->
                            <?php if($item['kategori'] == 'youtube' && !empty($item['children'])): ?>
                                <div class="relative w-full md:w-64">
                                     <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <ion-icon name="search-outline" class="text-slate-400"></ion-icon>
                                    </div>
                                    <input type="text" data-target="<?= $uniqueId ?>" class="local-search block w-full pl-10 pr-3 py-2 border border-slate-200 rounded-lg text-sm bg-slate-50 focus:bg-white focus:ring-2 focus:ring-indigo-100 transition-all duration-200" placeholder="Cari video...">
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- CONTENT -->

                        <!-- If YouTube Channel -->
                        <?php if($item['kategori'] == 'youtube'): ?>
                            <?php if(!empty($item['children'])): ?>
                                <div class="video-grid grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6 mb-6" id="grid-<?= $uniqueId ?>">
                            <?php foreach($item['children'] as $child): ?>
                                        <div class="searchable-item group bg-white rounded-2xl overflow-hidden shadow-lg shadow-primary/5 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border border-slate-100" data-title="<?= strtolower(esc($child['judul'])) ?>">
                                            <div class="relative aspect-video bg-slate-900 group-hover:scale-[1.02] transition-transform duration-700">
                                                <iframe 
                                                    class="w-full h-full" 
                                                    src="https://www.youtube.com/embed/<?= esc($child['link_media']) ?>" 
                                                    title="<?= esc($child['judul']) ?>" 
                                                    frameborder="0" 
                                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                                    allowfullscreen
                                                    loading="lazy">
                                                </iframe>
                                            </div>
                                            <div class="p-4 relative bg-white h-auto">
                                                <h3 class="text-sm font-bold text-primary mb-2 line-clamp-2 group-hover:text-accent transition-colors font-heading leading-tight h-10" title="<?= esc($child['judul']) ?>">
                                                    <?= esc($child['judul']) ?>
                                                </h3>
                                                <p class="text-slate-400 text-xs line-clamp-1 font-medium bg-slate-50 p-1.5 rounded inline-block">
                                                    <ion-icon name="calendar-outline" class="align-middle mr-1"></ion-icon>
                                                    <?= esc($child['keterangan']) ?>
                                                </p>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div id="pagination-<?= $uniqueId ?>" class="pagination-controls flex justify-center items-center space-x-2"></div>
                            <?php else: ?>
                                <div class="text-center py-8 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                                    <p class="text-slate-500 text-sm">Video tidak tersedia.</p>
                                </div>
                            <?php endif; ?>

                        <!-- If Google Drive Image Folder -->
                        <?php elseif($item['kategori'] == 'drive_img'): 
                             $driveInfo = getDriveInfo($item['link_media']); 
                        ?>
                            <div class="bg-white rounded-[24px] overflow-hidden shadow-md border border-slate-100 p-1 h-[500px]" data-title="folder foto">
                                <iframe 
                                    src="https://drive.google.com/embeddedfolderview?id=<?= esc($driveInfo['id']) ?>#grid" 
                                    style="width:100%; height:100%; border:0; border-radius: 20px;"
                                    title="<?= esc($item['judul']) ?>">
                                </iframe>
                                <div class="p-2 text-center text-xs text-slate-400">
                                    Menampilkan folder Google Drive
                                </div>
                            </div>

                        <!-- If Google Drive Audio Folder or Files -->
                        <?php elseif($item['kategori'] == 'drive_audio'): 
                            $driveInfo = getDriveInfo($item['link_media']);
                        ?>
                             
                             <?php if($driveInfo['type'] == 'file'): ?>
                                <!-- SINGLE AUDIO FILE PLAYER -->
                                <div class="bg-white rounded-[20px] shadow-sm border border-slate-100 p-4 flex flex-col sm:flex-row items-center gap-4 hover:shadow-md transition-shadow duration-300" data-title="<?= strtolower($item['judul']) ?>">
                                    <!-- Icon -->
                                    <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0 text-primary">
                                        <ion-icon name="musical-note" class="text-2xl"></ion-icon>
                                    </div>
                                    
                                    <!-- Info -->
                                    <div class="flex-1 text-center sm:text-left min-w-0">
                                        <h4 class="font-bold text-slate-700 truncate" title="<?= esc($item['judul']) ?>"><?= esc($item['judul']) ?></h4>
                                        <span class="text-xs text-slate-400">Audio File</span>
                                    </div>

                                    <!-- Controls -->
                                    <div class="flex items-center gap-2 w-full sm:w-auto justify-center">
                                        <!-- HTML5 Player -->
                                        <audio controls class="h-8 w-48 sm:w-64 max-w-full rounded-lg bg-slate-50 border border-slate-200" preload="metadata">
                                            <source src="https://docs.google.com/uc?export=download&id=<?= esc($driveInfo['id']) ?>" type="audio/mpeg">
                                            Browser Anda tidak mendukung elemen audio.
                                        </audio>
                                        
                                        <!-- Download Button -->
                                        <a href="https://docs.google.com/uc?export=download&id=<?= esc($driveInfo['id']) ?>" target="_blank" class="flex items-center justify-center w-10 h-10 rounded-full bg-slate-100 text-slate-600 hover:bg-primary hover:text-white transition-all duration-300" title="Download Audio">
                                            <ion-icon name="cloud-download-outline" class="text-xl"></ion-icon>
                                        </a>
                                    </div>
                                </div>
                             
                             <?php else: ?>
                                <!-- FOLDER EMBED (IFRAME) -->
                                <div class="bg-white rounded-[24px] overflow-hidden shadow-md border border-slate-100 p-1 h-[500px]" data-title="folder audio">
                                     <iframe 
                                        src="https://drive.google.com/embeddedfolderview?id=<?= esc($driveInfo['id']) ?>#list" 
                                        style="width:100%; height:100%; border:0; border-radius: 20px;"
                                        title="<?= esc($item['judul']) ?>">
                                    </iframe>
                                    <div class="p-3 text-center bg-yellow-50 text-yellow-700 text-xs border-t border-yellow-100 rounded-b-[20px]">
                                        <ion-icon name="information-circle-outline" class="align-middle text-sm mr-1"></ion-icon>
                                        Untuk menampilkan tombol <b>Play & Download</b>, mohon input link <b>File Audio</b> satu per satu (bukan link Folder).
                                    </div>
                                </div>
                             <?php endif; ?>

                        <?php endif; ?>
                    </div>

                <?php $subIndex++; endforeach; ?>

            </div>
        <?php $tabIndex++; endforeach; ?>

    <?php endif; ?>
</div>

<script>
// 1. Define Global Tab Switcher (Outside DOMContentLoaded to ensure availability)
window.switchTab = function(tabId) {
    // Reset Buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('bg-white', 'text-slate-800', 'shadow-sm', 'ring-1', 'ring-slate-200');
        btn.classList.add('text-slate-500');
    });
    
    // Active Button
    const activeBtn = document.getElementById('tab-' + tabId);
    if(activeBtn) {
        activeBtn.classList.add('bg-white', 'text-slate-800', 'shadow-sm', 'ring-1', 'ring-slate-200');
        activeBtn.classList.remove('text-slate-500');
    }

    // Toggle Content
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Show Target
    const target = document.getElementById('content-' + tabId);
    if(target) target.classList.remove('hidden');
};

document.addEventListener('DOMContentLoaded', function() {
    console.log('Gallery Script Loaded');
    const itemsPerPage = 10;
    const sectionStates = {};

    // 2. Initialize State ONLY for Video Grids (Audio/Drive excluded from pagination)
    document.querySelectorAll('.video-grid').forEach(grid => {
        const uniqueId = grid.id.replace('grid-', '');
        const allItems = Array.from(grid.getElementsByClassName('searchable-item'));
        
        sectionStates[uniqueId] = {
            page: 1,
            allItems: allItems,
            visibleItems: allItems // Start with all visible
        };
        
        // Initial Render
        renderSection(uniqueId);
    });

    // 3. Local Search Listeners (Only for Video Grids that have them)
    document.querySelectorAll('.local-search').forEach(input => {
        input.addEventListener('input', (e) => {
            const uniqueId = e.target.getAttribute('data-target');
            const term = e.target.value.toLowerCase();
            const state = sectionStates[uniqueId];
            
            if(!state) return;

            // Filter Logic
            if (term.length > 0) {
                state.visibleItems = state.allItems.filter(item => {
                    const title = item.getAttribute('data-title') || '';
                    return title.includes(term);
                });
            } else {
                state.visibleItems = state.allItems;
            }
            
            state.page = 1; // 1 Reset page
            renderSection(uniqueId);
        });
    });

    // 4. Global Search Listener (Filter Sections)
    const globalSearch = document.getElementById('globalSearch');
    if(globalSearch) {
        globalSearch.addEventListener('input', (e) => {
            const term = e.target.value.toLowerCase();
            
            // Filter all sections in ALL tabs
            document.querySelectorAll('.searchable-section').forEach(section => {
                const keywords = section.getAttribute('data-keywords') || '';
                if(keywords.includes(term)) {
                    section.classList.remove('hidden');
                } else {
                    section.classList.add('hidden');
                }
            });
        });
    }

    // 5. Core Render Function (For Video Grids)
    function renderSection(uniqueId) {
        const state = sectionStates[uniqueId];
        const grid = document.getElementById('grid-' + uniqueId);
        const pager = document.getElementById('pagination-' + uniqueId);
        
        if (!state || !grid) return;

        // Soft Hide All
        state.allItems.forEach(item => item.classList.add('hidden'));

        // Pagination Calculations
        const totalItems = state.visibleItems.length;
        const totalPages = Math.ceil(totalItems / itemsPerPage);
        
        // Bounds Check
        if (state.page > totalPages) state.page = totalPages || 1;
        if (state.page < 1) state.page = 1;

        // Slice & Show
        const start = (state.page - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        const itemsToShow = state.visibleItems.slice(start, end);
        
        itemsToShow.forEach(item => item.classList.remove('hidden'));

        // Update Pager UI
        if(pager) renderPagerUI(pager, totalPages, state.page, uniqueId);
    }

    // 6. Pager UI Generator
    function renderPagerUI(container, totalPages, currentPage, uniqueId) {
        container.innerHTML = '';
        if (totalPages <= 1) return; // Hide if single page

        // Helper to append button
        const appendBtn = (content, onClick, disabled = false, active = false) => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.innerHTML = content;
            btn.disabled = disabled;
            btn.className = `w-8 h-8 rounded-lg flex items-center justify-center font-bold text-xs transition-all duration-200 ${
                active 
                ? 'bg-primary text-white shadow-md shadow-primary/20' 
                : disabled 
                    ? 'bg-slate-50 text-slate-300 cursor-not-allowed' 
                    : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50 hover:border-primary/30'
            }`;
            if (!disabled) {
                btn.onclick = (e) => {
                    e.preventDefault();
                    onClick();
                };
            }
            container.appendChild(btn);
        };

        // Previous
        appendBtn('<ion-icon name="chevron-back"></ion-icon>', () => {
            sectionStates[uniqueId].page--;
            renderSection(uniqueId);
        }, currentPage === 1);

        // Page Numbers
        for (let i = 1; i <= totalPages; i++) {
            if (i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
                appendBtn(i, () => {
                    sectionStates[uniqueId].page = i;
                    renderSection(uniqueId);
                }, false, i === currentPage);
            } else if (i === currentPage - 2 || i === currentPage + 2) {
                const dots = document.createElement('span');
                dots.className = 'text-slate-400 px-1 text-xs';
                dots.innerText = '..';
                container.appendChild(dots);
            }
        }

        // Next
        appendBtn('<ion-icon name="chevron-forward"></ion-icon>', () => {
            sectionStates[uniqueId].page++;
            renderSection(uniqueId);
        }, currentPage === totalPages);
        
        // Page Info Text
        const info = document.createElement('span');
        info.className = 'ml-3 text-xs text-slate-400 font-medium';
        info.innerText = `Hal ${currentPage} dari ${totalPages}`;
        container.appendChild(info);
    }
});
</script>

<?= $this->endSection() ?>
