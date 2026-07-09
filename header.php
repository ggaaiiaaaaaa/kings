<?php get_template_part('head'); ?>
<script>
    // Global theme data for JS
    const KG_THEME = {
        templateUrl: '<?php echo function_exists('get_template_directory_uri') ? get_template_directory_uri() : "."; ?>',
        homeUrl: '<?php echo esc_url(home_url('/')); ?>',
        logoWhite: '<?php echo kg_asset("img/[LOGO] Main Logo White.webp"); ?>',
        logoBlack: '<?php echo kg_asset("img/[LOGO] Main Logo Black.webp"); ?>',
        userGeo: '<?php echo esc_js(kg_get_user_geo()); ?>'
    };
</script>

<body <?php if (function_exists('body_class'))
    body_class(); ?>>
    <?php if (!kg_has_accepted_consent()): ?>
        <!-- Cookie Consent Banner -->
        <div id="kg-consent-banner" role="dialog" aria-label="Cookie Consent" aria-live="polite">
            <div id="kg-consent-inner">
                <div id="kg-consent-text">
                    <strong>Your Privacy Matters</strong>
                    <span>By using this site, you agree to our
                        <a href="<?php echo esc_url(home_url('/terms/')); ?>" target="_blank" rel="noopener">Terms of
                            Service</a>
                        and
                        <a href="<?php echo esc_url(home_url('/privacy/')); ?>" target="_blank" rel="noopener">Privacy
                            Policy</a>.
                        We use cookies to improve your experience.
                    </span>
                </div>
                <div id="kg-consent-actions">
                    <button id="kg-consent-decline" aria-label="Decline cookies">Decline</button>
                    <button id="kg-consent-accept" aria-label="Accept cookies">Accept & Continue</button>
                </div>
            </div>
        </div>
        <style>
            #kg-consent-banner {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                z-index: 999999;
                background: rgba(10, 37, 64, 0.97);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                border-top: 1px solid rgba(255, 209, 102, 0.3);
                box-shadow: 0 -4px 32px rgba(0, 0, 0, 0.25);
                transform: translateY(100%);
                transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
                display: none;
            }

            #kg-consent-banner.kg-consent-visible {
                display: block;
                transform: translateY(0);
            }

            #kg-consent-inner {
                max-width: 1200px;
                margin: 0 auto;
                padding: 1rem 1.5rem;
                display: flex;
                align-items: center;
                gap: 1.5rem;
                flex-wrap: wrap;
            }

            #kg-consent-text {
                flex: 1;
                min-width: 220px;
                font-size: 0.85rem;
                color: rgba(255, 255, 255, 0.85);
                line-height: 1.5;
                display: flex;
                flex-direction: column;
                gap: 0.2rem;
            }

            #kg-consent-text strong {
                color: #ffd166;
                font-size: 0.9rem;
            }

            #kg-consent-text a {
                color: #ffd166;
                text-decoration: underline;
                font-weight: 600;
            }

            #kg-consent-actions {
                display: flex;
                gap: 0.65rem;
                flex-shrink: 0;
                flex-wrap: wrap;
            }

            #kg-consent-decline {
                padding: 0.55rem 1.25rem;
                background: transparent;
                border: 1px solid rgba(255, 255, 255, 0.3);
                color: rgba(255, 255, 255, 0.7);
                border-radius: 6px;
                font-size: 0.82rem;
                cursor: pointer;
                transition: all 0.2s;
            }

            #kg-consent-decline:hover {
                border-color: rgba(255, 255, 255, 0.6);
                color: #fff;
            }

            #kg-consent-accept {
                padding: 0.55rem 1.5rem;
                background: #ffd166;
                border: none;
                color: #0a2540;
                border-radius: 6px;
                font-size: 0.85rem;
                font-weight: 700;
                cursor: pointer;
                transition: background 0.2s, transform 0.15s;
            }

            #kg-consent-accept:hover {
                background: #ffdc85;
                transform: translateY(-1px);
            }

            #kg-consent-banner.kg-consent-hidden {
                transform: translateY(100%);
                pointer-events: none;
            }

            @media (max-width: 600px) {
                #kg-consent-inner {
                    flex-direction: column;
                    align-items: flex-start;
                    gap: 0.85rem;
                }

                #kg-consent-actions {
                    width: 100%;
                }

                #kg-consent-accept,
                #kg-consent-decline {
                    flex: 1;
                    text-align: center;
                }
            }
        </style>
        <script>
            (function () {
                var CONSENT_KEY = 'kg_consent_accepted';

                function setCookie(name, value, days) {
                    var date = new Date();
                    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                    var secure = window.location.protocol === 'https:' ? '; Secure; SameSite=Lax' : '; SameSite=Lax';
                    document.cookie = name + '=' + value + '; expires=' + date.toUTCString() + '; path=/' + secure;
                }

                function hideBanner(banner) {
                    banner.classList.remove('kg-consent-visible');
                    banner.classList.add('kg-consent-hidden');
                    setTimeout(function () { banner.style.display = 'none'; }, 600);
                }

                document.addEventListener('DOMContentLoaded', function () {
                    var banner = document.getElementById('kg-consent-banner');
                    var acceptBtn = document.getElementById('kg-consent-accept');
                    var declineBtn = document.getElementById('kg-consent-decline');

                    // Guard: if localStorage already shows consent, hide immediately without showing
                    if (localStorage.getItem(CONSENT_KEY) === 'true') {
                        if (banner) banner.style.display = 'none';
                        return;
                    }

                    // Show banner after short delay
                    if (banner) {
                        banner.style.display = 'block';
                        setTimeout(function () {
                            banner.classList.add('kg-consent-visible');
                        }, 300);
                    }

                    if (acceptBtn) {
                        acceptBtn.addEventListener('click', function () {
                            setCookie(CONSENT_KEY, 'true', 30);
                            try { localStorage.setItem(CONSENT_KEY, 'true'); } catch (e) { }

                            // Save IP detected geo region to the cookie instantly
                            var detectedGeo = (typeof KG_THEME !== 'undefined' && KG_THEME.userGeo) ? KG_THEME.userGeo : 'PH';
                            setCookie('kg_user_geo', detectedGeo, 30);

                            hideBanner(banner);

                            // Reload page immediately to apply redirects and hide/show menu items
                            window.location.reload();
                        });
                    }

                    if (declineBtn) {
                        declineBtn.addEventListener('click', function () {
                            hideBanner(banner);
                        });
                    }
                });
            })();
        </script>
    <?php endif; ?>


    <!-- Scroll Progress Bar -->
    <div class="scroll-progress" id="scrollBar"></div>


    <!-- Header: Choose Your Path -->
    <header id="main-header">
        <div class="nav-container">
            <div class="logo"><a href="<?php echo esc_url(home_url('/')); ?>"><img
                        src="<?php echo kg_asset('img/[LOGO] Main Logo White.webp'); ?>" alt="Kings Group Logo"
                        class="brand-logo" loading="eager"></a></div>

            <!-- Left Side (Client Focus) -->
            <div class="nav-section client">
                <?php
                // Dynamic client nav — managed from WP Admin → Appearance → Menus → Primary Client Menu
                if (has_nav_menu('menu-1')) {
                    wp_nav_menu(array(
                        'theme_location' => 'menu-1',
                        'container' => false,
                        'depth' => 1,
                        'fallback_cb' => false,
                        'items_wrap' => '<ul class="nav-menu-list">%3$s</ul>',
                    ));
                } else { ?>
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="nav-link">Home</a>
                <?php } ?>

                <!-- Dropdown Menu: About (stays hardcoded — custom mega menu design) -->
                <div class="dropdown">
                    <button class="nav-link dropdown-toggle" aria-haspopup="true" aria-expanded="false">
                        About
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M7 10L12 15L17 10"></path>
                        </svg>
                    </button>
                    <div class="dropdown-menu mega-menu">
                        <div class="mega-menu-inner">

                            <!-- Main Content Area (70%) -->
                            <div class="mega-main">

                                <!-- Column 1: Company -->
                                <div class="mega-links-col">
                                    <h4
                                        style="margin-bottom: 0.75rem; color: var(--main-blue); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; padding-left: 1rem;">
                                        Company</h4>
                                    <a href="<?php echo esc_url(home_url('/story/')); ?>" class="mega-feature-link">
                                        <div class="feature-link-icon">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2">
                                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                                <circle cx="9" cy="7" r="4"></circle>
                                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                            </svg>
                                        </div>
                                        <div class="feature-link-text">
                                            <span class="title">Our Story</span>
                                            <span class="desc">A legacy of empowering workers since 1999.</span>
                                        </div>
                                    </a>

                                    <a href="<?php echo esc_url(home_url('/news/')); ?>" class="mega-feature-link">
                                        <div class="feature-link-icon">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2">
                                                <path
                                                    d="M19 20H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h10l4 4v10a2 2 0 0 1-2 2z">
                                                </path>
                                                <polyline points="14 2 14 8 20 8"></polyline>
                                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                            </svg>
                                        </div>
                                        <div class="feature-link-text">
                                            <span class="title">News</span>
                                            <span class="desc">Latest updates and corporate milestones.</span>
                                        </div>
                                    </a>
                                    <a href="<?php echo esc_url(home_url('/community/')); ?>" class="mega-feature-link">
                                        <div class="feature-link-icon"><svg width="18" height="18" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2">
                                                <path
                                                    d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                                                </path>
                                            </svg></div>
                                        <div class="feature-link-text"><span class="title">Community</span><span
                                                class="desc">Uplifting lives through education and livelihood.</span>
                                        </div>
                                    </a>

                                </div>

                                <!-- Column 2: Solutions -->
                                <div class="mega-links-col">
                                    <h4
                                        style="margin-bottom: 0.75rem; color: var(--main-blue); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; padding-left: 1rem;">
                                        Services</h4>
                                    <a href="<?php echo esc_url(home_url('/service-labor/')); ?>"
                                        class="mega-feature-link">
                                        <div class="feature-link-icon">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2">
                                                <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                                            </svg>
                                        </div>
                                        <div class="feature-link-text">
                                            <span class="title">Labor Management</span>
                                            <span class="desc">Managed services & staff leasing.</span>
                                        </div>
                                    </a>
                                    <a href="<?php echo esc_url(home_url('/service-kit/')); ?>"
                                        class="mega-feature-link">
                                        <div class="feature-link-icon">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2">
                                                <path d="M12 2L2 7l10 5 10-5-10-5zm0 10l-10 5 10 5 10-5-10-5z">
                                                </path>
                                            </svg>
                                        </div>
                                        <div class="feature-link-text">
                                            <span class="title">HR & Tech (KIT)</span>
                                            <span class="desc">Proprietary Kings Information Technology systems.</span>
                                        </div>
                                    </a>
                                </div>

                                <!-- Column 3: Network -->
                                <div class="mega-links-col">
                                    <h4
                                        style="margin-bottom: 0.75rem; color: var(--main-blue); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; padding-left: 1rem;">
                                        Network</h4>
                                    <a href="<?php echo esc_url(home_url('/network/')); ?>" class="mega-feature-link">
                                        <div class="feature-link-icon">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2">
                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z">
                                                </path>
                                                <polyline points="14 2 14 8 20 8"></polyline>
                                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                                <polyline points="10 9 9 9 8 9"></polyline>
                                            </svg>
                                        </div>
                                        <div class="feature-link-text">
                                            <span class="title">Client Engagements</span>
                                            <span class="desc">Serving a network of over 10,000 members globally.</span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <a href="<?php echo esc_url(home_url('/quote/')); ?>" class="nav-link intl-only">Get a Quote</a>
            </div>

            <div class="nav-section applicant">
                <?php $geo_region = kg_get_user_geo(); ?>
                <div class="nav-region-indicator" style="display: flex; align-items: center; gap: 0.4rem; color: #fff; margin-right: 0.5rem; font-size: 0.85rem; font-weight: 600;">
                    <?php if ($geo_region === 'PH'): ?>
                        <img src="https://flagcdn.com/w20/ph.png" alt="PH Flag" width="20" height="15" style="border-radius: 2px; box-shadow: 0 0 2px rgba(0,0,0,0.3);">
                        <span style="letter-spacing: 0.5px;">PH</span>
                    <?php else: ?>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                        <span style="letter-spacing: 0.5px;">GLOBAL</span>
                    <?php endif; ?>
                </div>
                <a href="<?php echo esc_url(home_url('/our-jobs/')); ?>" class="nav-link ph-only">Our Jobs</a>
                <a href="https://www.thesocialmanila.com/" class="nav-link" target="_blank" rel="noopener">Shop</a>
                <a href="https://zckings.azurewebsites.net/" class="nav-link" target="_blank" rel="noopener">Log In</a>
                <a href="<?php echo esc_url(home_url('/careers/')); ?>" class="nav-link ph-only">Apply Now</a>
            </div>

            <!-- Mobile Toggle -->
            <button class="mobile-toggle" aria-label="Toggle Menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </header>

    <main id="main-content">