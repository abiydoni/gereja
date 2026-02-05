<!-- Global Loader -->
<div id="global-loader" class="fixed inset-0 z-[1050] flex items-center justify-center bg-primary/20 backdrop-blur-sm opacity-0 pointer-events-none transition-all duration-300">
    <div class="relative">
        <!-- Outer Rotating Ring -->
        <div class="w-20 h-20 rounded-full border-4 border-accent/20 border-t-accent animate-spin"></div>
        
        <!-- Inner Glow -->
        <div class="absolute inset-0 flex items-center justify-center">
            <div class="w-12 h-12 rounded-full bg-accent/10 animate-pulse flex items-center justify-center">
                <?php if(!empty($gereja['logo'])): ?>
                    <img src="<?= base_url('uploads/'.$gereja['logo']) ?>" class="w-8 h-8 rounded-full object-cover gold-filter opacity-80" alt="L">
                <?php else: ?>
                    <ion-icon name="heart" class="text-accent text-xl animate-bounce"></ion-icon>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Loading Text -->
        <div class="absolute -bottom-10 left-1/2 -translate-x-1/2 whitespace-nowrap">
            <span class="text-primary font-heading font-bold text-xs tracking-[0.3em] uppercase animate-pulse">Memuat...</span>
        </div>
    </div>
</div>

<style>
    #global-loader.show {
        opacity: 1;
        pointer-events: auto;
    }
    
    /* Ensure user can't interact with anything when loader is active */
    body.loading-active {
        overflow: hidden !important;
        pointer-events: none !important;
    }
    
    /* But the loader itself must be interactive (or at least visible) */
    #global-loader {
        pointer-events: none; /* Default */
    }
    #global-loader.show {
        pointer-events: auto;
    }
</style>

<script>
    window.showLoader = function() {
        const loader = document.getElementById('global-loader');
        if (loader) {
            loader.classList.add('show');
            document.body.classList.add('loading-active');
        }
    };

    window.hideLoader = function() {
        const loader = document.getElementById('global-loader');
        if (loader) {
            loader.classList.remove('show');
            document.body.classList.remove('loading-active');
        }
    };

    // Auto-hide on page load completion
    window.addEventListener('load', function() {
        setTimeout(hideLoader, 300); // Small delay for smoothness
    });

    // Handle back/forward button (bfcache)
    window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
            hideLoader();
        }
    });

    // Trigger on all link clicks (unless they are hash links or have data-no-loader)
    document.addEventListener('click', function(e) {
        const link = e.target.closest('a');
        if (link && 
            link.href && 
            !link.href.includes('#') && 
            !link.target && 
            link.hostname === window.location.hostname &&
            !link.hasAttribute('data-no-loader') &&
            !link.classList.contains('btn-delete') &&
            !e.ctrlKey && !e.shiftKey && !e.metaKey && !e.altKey) {
            
            showLoader();
        }
    });

    // Trigger on all form submissions (unless they have data-no-loader)
    document.addEventListener('submit', function(e) {
        if (!e.target.hasAttribute('data-no-loader')) {
            showLoader();
        }
    });
</script>
