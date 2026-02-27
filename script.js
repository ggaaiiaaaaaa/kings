// Header scroll effect and Logo Swapping
const header = document.getElementById('main-header');
const brandLogo = document.querySelector('.brand-logo');

// Image Paths
const logoWhitePath = "img/[LOGO] Main Logo White.png";
const logoBlackPath = "img/[LOGO] Main Logo Black.png";

window.addEventListener('scroll', () => {
    if (window.scrollY > 50) {
        if (header) header.classList.add('scrolled');
        if (brandLogo) brandLogo.src = logoBlackPath;
    } else {
        if (header) header.classList.remove('scrolled');
        if (brandLogo) brandLogo.src = logoWhitePath;
    }
});

// Intersection Observer for scroll animations
const observerOptions = {
    root: null,
    rootMargin: '0px',
    threshold: 0.15
};

const observer = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('is-visible');
            // Stop observing once animated
            observer.unobserve(entry.target);
        }
    });
}, observerOptions);

document.querySelectorAll('.animate-on-scroll').forEach((el) => {
    observer.observe(el);
});

// Hero Image Slideshow
const heroSlides = document.querySelectorAll('.hero-slide');
let currentSlide = 0;

function nextSlide() {
    if (heroSlides.length === 0) return;

    // Remove active class from current
    heroSlides[currentSlide].classList.remove('active');
    // Increment currentSlide, wrap if needed
    currentSlide = (currentSlide + 1) % heroSlides.length;
    // Add active class to new current
    heroSlides[currentSlide].classList.add('active');
}

// Change slide every 5 seconds
if (heroSlides.length > 1) {
    setInterval(nextSlide, 5000);
}

// Mega Menu Tab Switching
const megaTabs = document.querySelectorAll('.mega-tab');
const megaGrids = document.querySelectorAll('.mega-links-grid');

megaTabs.forEach(tab => {
    tab.addEventListener('mouseenter', () => {
        // Remove active classes
        megaTabs.forEach(t => t.classList.remove('active'));
        megaGrids.forEach(g => g.classList.remove('active'));

        // Add active to current
        tab.classList.add('active');
        const targetId = tab.getAttribute('data-target');
        if (targetId) {
            const targetElement = document.getElementById(targetId);
            if (targetElement) {
                targetElement.classList.add('active');
            }
        }
    });
});

// Testimonials Slider Logic
function initTestimonialSlider(trackId, prevBtnId, nextBtnId, dotsContainerId) {
    const track = document.getElementById(trackId);
    const prevBtn = document.getElementById(prevBtnId);
    const nextBtn = document.getElementById(nextBtnId);
    const dotsContainer = document.getElementById(dotsContainerId);

    if (!track || !dotsContainer) return;

    const slides = Array.from(track.querySelectorAll('.testimonial-card.slide'));
    let currentIndex = 0;

    // Create dots
    slides.forEach((_, i) => {
        const dot = document.createElement('div');
        dot.classList.add('slider-dot');
        if (i === 0) dot.classList.add('active');
        dot.addEventListener('click', () => {
            goToSlide(i);
            resetInterval();
        });
        dotsContainer.appendChild(dot);
    });

    const dots = Array.from(dotsContainer.querySelectorAll('.slider-dot'));

    function updateSlider() {
        // Move track
        track.style.transform = `translateX(-${currentIndex * 100}%)`;

        // Update classes
        slides.forEach((slide, i) => {
            slide.classList.toggle('active', i === currentIndex);
            dots[i].classList.toggle('active', i === currentIndex);
        });
    }

    function goToSlide(index) {
        currentIndex = index;
        updateSlider();
    }

    function nextTesti() {
        currentIndex = (currentIndex + 1) % slides.length;
        updateSlider();
    }

    function prevTesti() {
        currentIndex = (currentIndex - 1 + slides.length) % slides.length;
        updateSlider();
    }

    // Event Listeners
    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            nextTesti();
            resetInterval();
        });
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            prevTesti();
            resetInterval();
        });
    }

    // Auto-advance
    let autoPlayInterval = setInterval(nextTesti, 6000);

    function resetInterval() {
        clearInterval(autoPlayInterval);
        autoPlayInterval = setInterval(nextTesti, 6000);
    }
}

// Initialize sliders for both pages if they exist
initTestimonialSlider('testimonials-track', 'prev-testi', 'next-testi', 'testi-dots');
initTestimonialSlider('testimonials-track-story', 'prev-testi-story', 'next-testi-story', 'testi-dots-story');
