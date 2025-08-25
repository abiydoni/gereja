// Initialize AOS
AOS.init({
    duration: 800,
    easing: 'ease-in-out',
    once: true,
    disable: 'mobile' // Disable AOS on mobile for better performance
});

// Background Slideshow Functionality
function initBackgroundSlideshow() {
    const slides = document.querySelectorAll('.slide');
    const indicators = document.querySelectorAll('.indicator');
    
    if (slides.length === 0 || indicators.length === 0) {
        console.log('Slideshow elements not found');
        return;
    }
    
    let currentSlide = 0;
    let slideInterval;
    
    function showSlide(slideIndex) {
        console.log('Showing slide:', slideIndex);
        
        // Remove active class from current slide and indicator
        slides[currentSlide].classList.remove('active');
        indicators[currentSlide].classList.remove('active');
        
        // Update current slide
        currentSlide = slideIndex;
        
        // Add active class to new slide and indicator
        slides[currentSlide].classList.add('active');
        indicators[currentSlide].classList.add('active');
    }
    
    function nextSlide() {
        const nextIndex = (currentSlide + 1) % slides.length;
        showSlide(nextIndex);
    }
    
    // Add click event to indicators
    indicators.forEach((indicator, index) => {
        indicator.addEventListener('click', () => {
            console.log('Indicator clicked:', index);
            showSlide(index);
            // Reset interval
            clearInterval(slideInterval);
            slideInterval = setInterval(nextSlide, 5000);
        });
    });
    
    // Show first slide initially
    showSlide(0);
    
    // Start automatic slideshow
    slideInterval = setInterval(nextSlide, 5000);
    
    console.log('Slideshow initialized with', slides.length, 'slides');
}

// Initialize slideshow when page loads
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing slideshow...');
    initBackgroundSlideshow();
    initNavbarScroll();
});

// Navbar scroll effect
function initNavbarScroll() {
    const navbar = document.querySelector('nav');
    
    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            navbar.classList.add('navbar-scrolled');
        } else {
            navbar.classList.remove('navbar-scrolled');
        }
    });
}

// Mobile menu functionality
const mobileMenuButton = document.getElementById('mobile-menu-button');
const mobileMenu = document.getElementById('mobile-menu');

mobileMenuButton.addEventListener('click', function() {
    mobileMenu.classList.toggle('hidden');
});

// Close mobile menu when clicking on a link
document.querySelectorAll('#mobile-menu a').forEach(link => {
    link.addEventListener('click', function() {
        mobileMenu.classList.add('hidden');
    });
});

// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Welcome message
window.addEventListener('load', function() {
    Swal.fire({
        title: 'Selamat Datang!',
        text: 'Selamat datang di Gereja Kristen Jawa Randuares - Salatiga',
        icon: 'info',
        confirmButtonText: 'Mulai Jelajahi',
        confirmButtonColor: '#8B4513'
    });
});
