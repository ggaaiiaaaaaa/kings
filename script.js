// Header scroll effect and Logo Swapping
const header = document.getElementById('main-header');
const brandLogo = document.querySelector('.brand-logo');

// Image Paths (WP Aware)
const logoWhitePath = (typeof KG_THEME !== 'undefined') ? KG_THEME.logoWhite : "img/[LOGO] Main Logo White.webp";
const logoBlackPath = (typeof KG_THEME !== 'undefined') ? KG_THEME.logoBlack : "img/[LOGO] Main Logo Black.webp";

// Determine if we are on the homepage (or a page with a dark hero section)
const isDarkHeroPage = document.querySelector('.hero') || document.querySelector('.page-hero') || document.querySelector('.labor-hero') || document.querySelector('.kit-hero');

function updateHeader() {
    if (window.scrollY > 50 || !isDarkHeroPage) {
        if (header) {
            header.classList.add('scrolled');
            header.style.paddingTop = '0.5rem';
            header.style.paddingBottom = '0.5rem';
        }
        if (brandLogo) {
            brandLogo.src = logoBlackPath;
            brandLogo.style.height = '40px';
        }
    } else {
        if (header) {
            header.classList.remove('scrolled');
            header.style.paddingTop = '1.25rem';
            header.style.paddingBottom = '1.25rem';
        }
        if (brandLogo) {
            brandLogo.src = logoWhitePath;
            brandLogo.style.height = '48px';
        }
    }
}

// Initial check on page load
updateHeader();

// Check on scroll
window.addEventListener('scroll', updateHeader);

// Intersection Observer for scroll animations
const observerOptions = {
    root: null,
    rootMargin: '0px',
    threshold: 0.01
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

/* --- INTERACTIVE POLISH JS --- */

// 1. Scroll Progress Bar
window.addEventListener('scroll', () => {
    const scrollBar = document.getElementById('scrollBar');
    if (!scrollBar) return;

    const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
    const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
    const scrolled = (winScroll / height) * 100;
    scrollBar.style.width = scrolled + '%';
});

// 2. Live Stats Counter
function animateValue(obj, start, end, duration) {
    let startTimestamp = null;
    const step = (timestamp) => {
        if (!startTimestamp) startTimestamp = timestamp;
        const progress = Math.min((timestamp - startTimestamp) / duration, 1);
        obj.innerHTML = Math.floor(progress * (end - start) + start).toLocaleString();
        if (progress < 1) {
            window.requestAnimationFrame(step);
        } else {
            obj.innerHTML = end.toLocaleString() + '+';
        }
    };
    window.requestAnimationFrame(step);
}

const statsObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            const target = entry.target;
            const endValue = parseInt(target.getAttribute('data-value'));
            animateValue(target, 0, endValue, 2000);
            statsObserver.unobserve(target);
        }
    });
}, { threshold: 0.5 });

document.querySelectorAll('.stats-number').forEach(stat => {
    statsObserver.observe(stat);
});

// Mobile Menu Toggle
const mobileToggle = document.querySelector('.mobile-toggle');
const mobileNav = document.createElement('div');
mobileNav.className = 'mobile-nav';

// Construct Mobile Menu Content matching the desktop main nav
mobileNav.innerHTML = `
    <div class="mobile-nav-header" style="width: 100%; display: flex; justify-content: center; margin-bottom: 0.5rem; flex-shrink: 0;">
        <img src="${logoWhitePath}" alt="Kings Group" style="height: 54px; width: auto;">
    </div>
    
    <div class="mobile-nav-links-container" style="display: flex; flex-direction: column; align-items: center; gap: 1.2rem; width: 100%; overflow-y: auto; max-height: 70vh; padding: 1rem 0;">
        <a href="${KG_THEME.homeUrl}" class="mobile-nav-link">Home</a>
        
        <div class="mobile-nav-dropdown">
            <button class="mobile-nav-link mobile-dropdown-toggle">
                About
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="margin-left: 0.25rem;"><path d="M6 9l6 6 6-6"></path></svg>
            </button>
            <div class="mobile-dropdown-menu">
                <div class="mobile-dropdown-section">
                    <span class="mobile-dropdown-section-title">Company</span>
                    <a href="${KG_THEME.homeUrl}story/" class="mobile-sub-link">Our Story</a>
                    <a href="${KG_THEME.homeUrl}news/" class="mobile-sub-link">News</a>
                    <a href="${KG_THEME.homeUrl}community/" class="mobile-sub-link">Community</a>
                </div>
                <div class="mobile-dropdown-section">
                    <span class="mobile-dropdown-section-title">Services</span>
                    <a href="${KG_THEME.homeUrl}service-labor/" class="mobile-sub-link">Labor Management</a>
                    <a href="${KG_THEME.homeUrl}service-kit/" class="mobile-sub-link">HR & Tech (KIT)</a>
                </div>
                <div class="mobile-dropdown-section">
                    <span class="mobile-dropdown-section-title">Network</span>
                    <a href="${KG_THEME.homeUrl}network/" class="mobile-sub-link">Client Engagements</a>
                </div>
            </div>
        </div>

        <a href="${KG_THEME.homeUrl}our-jobs/" class="mobile-nav-link">Our Jobs</a>
        <a href="${KG_THEME.homeUrl}careers/" class="mobile-nav-link">Apply Now</a>
        <a href="https://zckings.azurewebsites.net/" target="_blank" rel="noopener" class="mobile-nav-link">Member Portal</a>
        
        <div style="display: flex; flex-direction: column; gap: 0.85rem; width: 100%; max-width: 280px; margin-top: 1rem; padding-bottom: 1rem;">
            <a href="${KG_THEME.homeUrl}quote/" class="btn btn-gold" style="width: 100%;">Get a Quote</a>
            <a href="https://zckings.azurewebsites.net/" target="_blank" rel="noopener" class="btn btn-outline" style="width: 100%; border-color: rgba(255,255,255,0.4); color: white;">Log In</a>
        </div>
    </div>
`;
document.body.appendChild(mobileNav);

// Toggle about dropdown accordion
const mobileDropdownToggle = mobileNav.querySelector('.mobile-dropdown-toggle');
const mobileDropdownMenu = mobileNav.querySelector('.mobile-dropdown-menu');

if (mobileDropdownToggle && mobileDropdownMenu) {
    mobileDropdownToggle.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        mobileDropdownToggle.classList.toggle('active');
        mobileDropdownMenu.classList.toggle('active');
    });
}

if (mobileToggle) {
    // Set initial WCAG accessibility states
    mobileToggle.setAttribute('aria-expanded', 'false');
    mobileToggle.setAttribute('aria-controls', 'mobile-nav');
    mobileNav.id = 'mobile-nav';

    mobileToggle.addEventListener('click', () => {
        const isActive = mobileToggle.classList.toggle('active');
        mobileNav.classList.toggle('active');
        mobileToggle.setAttribute('aria-expanded', isActive ? 'true' : 'false');
        document.body.style.overflow = mobileNav.classList.contains('active') ? 'hidden' : '';
    });
}

// Close mobile menu on link click (ignoring the dropdown toggle trigger)
const mobileLinks = mobileNav.querySelectorAll('a');
mobileLinks.forEach(link => {
    link.addEventListener('click', () => {
        mobileToggle.classList.remove('active');
        mobileNav.classList.remove('active');
        mobileToggle.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
    });
});

/* ─────────────────────────────────────────
   PHASE 2 — TILT EFFECT
   Applies to: .engagement-card, .folder-item, .feature-card,
               .affiliate-showcase-image, .team-card-inner
   ───────────────────────────────────────── */
(function initTilt() {
    // Disabled 3D tilt on request to optimize visual flow
    const TILT_SELECTORS = [];

    const MAX_TILT = 8;   // degrees
    const MAX_LIFT = 6;   // px translateZ
    const RESET_MS = 300;

    function applyTilt(el) {
        el.classList.add('tilt-card');

        el.addEventListener('mousemove', (e) => {
            const rect = el.getBoundingClientRect();
            const cx = rect.left + rect.width / 2;
            const cy = rect.top + rect.height / 2;
            const dx = (e.clientX - cx) / (rect.width / 2); // -1 → 1
            const dy = (e.clientY - cy) / (rect.height / 2); // -1 → 1
            const rotX = -dy * MAX_TILT;
            const rotY = dx * MAX_TILT;
            el.style.transform = `perspective(800px) rotateX(${rotX}deg) rotateY(${rotY}deg) translateZ(${MAX_LIFT}px)`;
        });

        el.addEventListener('mouseleave', () => {
            el.style.transition = `transform ${RESET_MS}ms cubic-bezier(0.16,1,0.3,1)`;
            el.style.transform = 'perspective(800px) rotateX(0deg) rotateY(0deg) translateZ(0)';
            setTimeout(() => { el.style.transition = ''; }, RESET_MS);
        });
    }

    TILT_SELECTORS.forEach(sel => {
        document.querySelectorAll(sel).forEach(applyTilt);
    });
})();

/* ─────────────────────────────────────────
   PHASE 2 — HERO PARALLAX
   Moves .hero-bg-media and hero backgrounds
   at 40% of scroll speed for depth.
   ───────────────────────────────────────── */
(function initParallax() {
    // Parallax hero scrolling disabled on request
    return;
})();


// Solutions Toggle Logic
const toggleBtns = document.querySelectorAll('.toggle-btn');
const solutionPanels = document.querySelectorAll('.solution-panel');

toggleBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        const targetId = btn.getAttribute('data-target');

        // Update Buttons
        toggleBtns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        // Update Panels
        solutionPanels.forEach(panel => {
            if (panel.id === targetId) {
                panel.classList.remove('hidden');
                // Re-trigger animation observer for the newly shown panel
                document.querySelectorAll('#' + targetId + ' .animate-on-scroll').forEach(el => {
                    observer.observe(el);
                });
            } else {
                panel.classList.add('hidden');
            }
        });
    });
});

