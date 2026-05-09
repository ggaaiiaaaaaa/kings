<?php get_template_part('head'); ?>
<script>
    // Global theme data for JS
    const KG_THEME = {
        templateUrl: '<?php echo function_exists('get_template_directory_uri') ? get_template_directory_uri() : "."; ?>',
        homeUrl: '<?php echo esc_url(home_url('/')); ?>',
        logoWhite: '<?php echo kg_asset("img/[LOGO] Main Logo White.webp"); ?>',
        logoBlack: '<?php echo kg_asset("img/[LOGO] Main Logo Black.webp"); ?>'
    };
</script>
<body <?php if(function_exists('body_class')) body_class(); ?>>
    <!-- Scroll Progress Bar -->
    <div class="scroll-progress" id="scrollBar"></div>

    <!-- Header: Choose Your Path -->
    <header id="main-header">
        <div class="nav-container">
            <div class="logo"><a href="<?php echo esc_url(home_url('/')); ?>"><img src="<?php echo kg_asset('img/[LOGO] Main Logo White.webp'); ?>" alt="Kings Group Logo"
                        class="brand-logo" loading="eager"></a></div>

            <!-- Left Side (Client Focus) -->
            <div class="nav-section client">
                <?php
                // Dynamic client nav — managed from WP Admin → Appearance → Menus → Primary Client Menu
                if ( has_nav_menu('menu-1') ) {
                    wp_nav_menu( array(
                        'theme_location' => 'menu-1',
                        'container'      => false,
                        'depth'          => 1,
                        'fallback_cb'    => false,
                        'items_wrap'     => '<ul class="nav-menu-list">%3$s</ul>',
                    ) );
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
                                    <a href="<?php echo esc_url(home_url('/story/#vision-mission')); ?>" class="mega-feature-link">
                                        <div class="feature-link-icon">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2">
                                                <polygon
                                                    points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                                </polygon>
                                            </svg>
                                        </div>
                                        <div class="feature-link-text">
                                            <span class="title">Mission & Values</span>
                                            <span class="desc">Discover our cooperative advantage and vision.</span>
                                        </div>
                                    </a>
                                    <a href="<?php echo esc_url(home_url('/news/')); ?>" class="mega-feature-link">
                                        <div class="feature-link-icon">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M19 20H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h10l4 4v10a2 2 0 0 1-2 2z"></path>
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
                                        <div class="feature-link-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg></div>
                                        <div class="feature-link-text"><span class="title">Community</span><span class="desc">Uplifting lives through education and livelihood.</span></div>
                                    </a>

                                </div>

                                <!-- Column 2: Solutions -->
                                <div class="mega-links-col">
                                    <h4
                                        style="margin-bottom: 0.75rem; color: var(--main-blue); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; padding-left: 1rem;">
                                        Services</h4>
                                    <a href="<?php echo esc_url(home_url('/service-labor/')); ?>" class="mega-feature-link">
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
                                    <a href="<?php echo esc_url(home_url('/service-kit/')); ?>" class="mega-feature-link">
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

                <a href="<?php echo esc_url(home_url('/quote/')); ?>" class="nav-link">Get a Quote</a>

            </div>

            <!-- Right Side (Applicant Focus) -->
            <div class="nav-section applicant">
                <?php
                // Dynamic applicant nav — managed from WP Admin → Appearance → Menus → Primary Applicant Menu
                if ( has_nav_menu('menu-2') ) {
                    wp_nav_menu( array(
                        'theme_location' => 'menu-2',
                        'container'      => false,
                        'depth'          => 1,
                        'fallback_cb'    => false,
                        'items_wrap'     => '<ul class="nav-menu-list">%3$s</ul>',
                    ) );
                } else { ?>
                <a href="<?php echo esc_url(home_url('/careers/')); ?>" class="nav-link">Find a Job</a>
                <a href="https://zckings.azurewebsites.net/" class="nav-link" target="_blank" rel="noopener">Member Portal</a>
                <a href="<?php echo esc_url(home_url('/wp-login.php')); ?>" class="nav-link">Log In</a>
                <?php } ?>
            </div>

            <!-- Mobile Toggle -->
            <button class="mobile-toggle" aria-label="Toggle Menu">
                <span></span>
                <span></span>
            </button>
        </div>
    </header>

    <main id="main-content">

