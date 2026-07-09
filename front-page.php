<?php
/* Template Name: Home */
?>
<?php
if (!defined('ABSPATH')) {
    require_once 'functions.php';
}
global $page_title, $page_description;
$page_title = 'Manpower Services Philippines | Labor & Staffing Solutions';
$page_description = 'Elite talent acquisition and ethical staffing solutions. Discover Kings Group\'s managed services and labor management for businesses.';

// JSON-LD: WebSite schema — enables Google Sitelinks Search Box
$page_schema = [
    '@context' => 'https://schema.org',
    '@type' => 'WebSite',
    '@id' => 'https://kingsgroup.com.ph/#website',
    'url' => 'https://kingsgroup.com.ph/',
    'name' => 'Kings Group Cooperative',
    'description' => 'Elite talent acquisition and ethical staffing solutions since 1999.',
    'publisher' => ['@id' => 'https://kingsgroup.com.ph/#organization'],
    'potentialAction' => [
        '@type' => 'SearchAction',
        'target' => [
            '@type' => 'EntryPoint',
            'urlTemplate' => 'https://kingsgroup.com.ph/?s={search_term_string}',
        ],
        'query-input' => 'required name=search_term_string',
    ],
];

$page_hero_bg = kg_get_field('hero_img_1', kg_asset('img/front-page/hero-slide1.png'));

get_header();
?>

<!-- The Hero Section: People-First Hook -->
<section class="hero">
    <?php
    $hero_id = get_the_ID();
    $is_ph = (kg_get_user_geo() === 'PH');

    // PH Fields
    $headline_ph = kg_get_field('hero_headline', 'Your Trusted Local <span style="color:#ffd166;">Manpower Provider</span> <span style="color:#ffffff;">in the Philippines.</span>');
    $description_ph = kg_get_field('hero_description', 'Helping businesses find qualified, reliable, and job-ready manpower across a wide range of industries. Kings Group has been connecting employers with skilled Filipino talent since 1999.');

    // INTL Fields
    $headline_intl = kg_get_field('hero_headline_intl', 'Elite Talent.<br>Ethical Staffing. <span>Exceptional Results.</span>');
    $description_intl = kg_get_field('hero_description_intl', 'Scale your operations with dedicated offshore professionals from the Philippines.');

    if ($headline_ph === null || $headline_ph === false) {
        $headline_ph = 'Your Trusted Local <span style="color:#ffd166;">Manpower Provider</span> <span style="color:#ffffff;">in the Philippines.</span>';
    }
    if ($headline_intl === null || $headline_intl === false) {
        $headline_intl = 'Elite Talent.<br>Ethical Staffing. <span>Exceptional Results.</span>';
    }
    if ($description_ph === null || $description_ph === false) {
        $description_ph = 'Helping businesses find qualified, reliable, and job-ready manpower across a wide range of industries. Kings Group has been connecting employers with skilled Filipino talent since 1999.';
    }
    if ($description_intl === null || $description_intl === false) {
        $description_intl = 'Scale your operations with dedicated offshore professionals from the Philippines.';
    }

    $slides = [];
    $default_images = [
        1 => kg_asset('img/front-page/hero-slide1.png'),
        2 => kg_asset('img/front-page/hero-slide2.png'),
        3 => kg_asset('img/front-page/hero-slide3.png'),
    ];
    for ($s = 1; $s <= 3; $s++) {
        $default_img = isset($default_images[$s]) ? $default_images[$s] : '';
        $img = kg_get_field('hero_img_' . $s, $default_img);
        if (!empty($img)) {
            $slides[] = $img;
        }
    }
    ?>
    <div class="hero-bg-media" id="hero-slider">
        <?php foreach ($slides as $index => $slide_url):
            $active_class = ($index === 0) ? 'hero-slide active' : 'hero-slide';
            $loading = ($index === 0) ? 'eager' : 'lazy';
            $fetchpriority = ($index === 0) ? 'high' : 'auto';
            ?>
            <?php echo kg_img($slide_url, 'Hero background slide ' . ($index + 1), $active_class, '', $loading, $fetchpriority); ?>
        <?php endforeach; ?>
    </div>

    <!-- PH Hero Content -->
    <div class="hero-content ph-only">
        <h1><?php echo wp_kses_post($headline_ph); ?></h1>
        <p><?php echo esc_html($description_ph); ?></p>
        <style>
            .hero-hover-swap {
                overflow: hidden;
            }

            .hero-hover-swap .text-default,
            .hero-hover-swap .text-hover {
                transition: transform 0.10s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.15s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .hero-hover-swap .text-default {
                transform: translateY(0);
                opacity: 1;
            }

            .hero-hover-swap .text-hover {
                transform: translateY(15px);
                opacity: 0;
            }

            .hero-hover-swap:hover .text-default {
                transform: translateY(-15px);
                opacity: 0;
            }

            .hero-hover-swap:hover .text-hover {
                transform: translateY(0);
                opacity: 1;
            }
        </style>
        <div class="hero-buttons">
            <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="btn btn-gold hero-hover-swap"
                style="background-color: #ffd166; color: #0a2540;">
                <span style="display: inline-grid; place-items: center; align-items: center;">
                    <span class="text-default" style="grid-area: 1/1;">
                        <?php echo esc_html(kg_get_field('hero_btn_ph', 'Request Manpower')); ?>
                    </span>
                    <span class="text-hover" style="grid-area: 1/1;">
                        <?php echo esc_html(kg_get_field('hero_btn_hover_ph', 'Inquire Now')); ?>
                    </span>
                </span>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                </svg>
            </a>
            <a href="<?php echo esc_url(home_url('/careers/')); ?>" class="hero-cv-link">
                <?php echo esc_html(kg_get_field('hero_cv_label', 'Looking for a job? Submit your CV')); ?>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M5 12h14M12 5l7 7-7 7" />
                </svg>
            </a>
        </div>
    </div>

    <!-- INTL Hero Content -->
    <div class="hero-content intl-only">
        <h1><?php echo wp_kses_post($headline_intl); ?></h1>
        <p><?php echo esc_html($description_intl); ?></p>
        <div class="hero-buttons">
            <a href="<?php echo esc_url(home_url('/quote/')); ?>" class="btn btn-gold"
                style="background-color: #ffd166; color: #0a2540;">
                <?php echo esc_html(kg_get_field('hero_btn_intl', 'Build Your Team')); ?>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
                </svg>
            </a>
        </div>
    </div>
</section>


<!-- Who We Are Section -->
<?php
$wwa_title = kg_get_field('wwa_title', 'Who We Are');
$wwa_p1 = kg_get_field('wwa_p1', 'THE KINGS is a fast-rising cooperative in the Philippines, duly registered with the Cooperative Development Authority (CDA) and organized pursuant to the provisions of the law and existing rules and regulations, with an ever-growing list of satisfied clients.');
$wwa_p2 = kg_get_field('wwa_p2', 'We are bound by a common goal of improving our members\' lives by giving better benefits. All owner-members enjoy additional benefits such as Interest on Capital Contribution, Insurance/HMO and Surplus Sharing. KINGS also provides members with facilities such as the Savings Program, Livelihood Program and Loan Program.');
$wwa_p3 = kg_get_field('wwa_p3', 'The Kings is offering different kinds of Manpower Services, Staff Leasing, HR & Payroll Management, Spaces, Culinary and Livelihood Programs and Microfinancing Services.');
$wwa_btn_text = kg_get_field('wwa_btn_text', 'Learn Our Story');
$wwa_img = kg_get_field('wwa_img', kg_asset('img/front-page/homepage.png'));
?>
<section class="section who-we-are-section"
    style="padding-top: 8rem; padding-bottom: 8rem; background-color: var(--bg-white); overflow: hidden;">
    <div class="container animate-on-scroll is-visible">
        <div class="wwa-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: center;">

            <!-- Text Content -->
            <div class="wwa-text-content" style="padding-right: 2rem;">
                <h2
                    style="font-size: clamp(3rem, 5vw, 4.5rem); font-family: var(--font-header); font-weight: 900; color: var(--main-blue); margin-bottom: 2rem; line-height: 1.1; letter-spacing: -0.02em;">
                    <?php echo esc_html($wwa_title); ?>
                </h2>

                <p style="font-size: 1.15rem; color: var(--text-body); line-height: 1.8; margin-bottom: 1.5rem;">
                    <?php echo esc_html($wwa_p1); ?>
                </p>
                <p style="font-size: 1.15rem; color: var(--text-body); line-height: 1.8; margin-bottom: 1.5rem;">
                    <?php echo esc_html($wwa_p2); ?>
                </p>
                <p style="font-size: 18px; color: var(--text-body); line-height: 1.8; margin-bottom: 3rem;">
                    <?php echo esc_html($wwa_p3); ?>
                </p>

                <a href="<?php echo esc_url(home_url('/story/')); ?>" class="btn btn-primary"
                    style="padding: 1rem 2.5rem; font-size: 1.1rem;">
                    <?php echo esc_html($wwa_btn_text); ?>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        style="margin-left: 0.5rem;">
                        <path d="M5 12h14M12 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            <!-- Image Composition -->
            <div class="wwa-image-composition" style="position: relative;">
                <div
                    style="position: relative; border-radius: 16px; overflow: hidden; box-shadow: var(--shadow-xl); transform: translateY(-20px);">
                    <?php echo kg_img($wwa_img, 'Who We Are - Team', '', 'width: 100%; aspect-ratio: 4/5; object-fit: cover; display: block;'); ?>
                </div>

                <!-- Decorative element -->
                <div
                    style="position: absolute; top: 40px; right: -40px; width: 200px; height: 200px; background: var(--neutral-yellow); border-radius: 50%; z-index: -1; filter: blur(40px); opacity: 0.4;">
                </div>
                <div
                    style="position: absolute; bottom: -40px; left: -40px; width: 250px; height: 250px; background: var(--main-blue-light); border-radius: 50%; z-index: -1; filter: blur(50px); opacity: 0.2;">
                </div>
            </div>

        </div>
    </div>
</section>

<!-- High-Converting Copy Sections -->
<section class="section section-bg-light">
    <div class="container animate-on-scroll">
        <?php
        if ($is_ph) {
            $intro_title = kg_get_field('home_intro_title', 'A Different Kind of Staffing');
            $intro_sub = 'Whether you need manpower for your business or you\'re a Filipino worker looking for stable employment — Kings Group has the right solution for you.';
        } else {
            $intro_title = kg_get_field('home_intro_title', 'A Different Kind of Staffing');
            $intro_sub = kg_get_field('home_intro_sub', 'Bridging the gap between a traditional agency and a modern global talent platform to serve businesses and career-seekers alike.');
        }
        ?>
        <h2 class="section-title" style="color: var(--main-blue); margin-bottom: 0.5rem;">
            <?php echo esc_html($intro_title); ?>
        </h2>
        <p class="section-subtitle" style="margin-bottom: 3rem;"><?php echo esc_html($intro_sub); ?></p>
    </div>

    <div class="container" style="display: flex; flex-direction: column; gap: 5rem; padding-top: 0.5rem;">
        <!-- PH Advantage Block -->
        <div class="staffing-split ph-only animate-on-scroll"
            style="display: flex; align-items: center; gap: 3rem; flex-wrap: wrap;">
            <?php
            // PH geo: speak to local businesses needing manpower
            $adv_headline_ph = kg_get_field('adv_headline_ph', 'Your Trusted Local<br>Manpower Provider.');
            $adv_subheadline_ph = kg_get_field('adv_subheadline_ph', 'Stop the hiring hassle. Start deploying.');
            $adv_desc_ph = kg_get_field('adv_desc_ph', 'Get job-ready Filipino workers sourced, screened, and deployed to your business — fully managed and DOLE-compliant. We\'ve been doing this since 1999.');
            $adv_f1_title_ph = kg_get_field('adv_f1_title_ph', 'DOLE-Licensed & Compliant');
            $adv_f1_desc_ph = kg_get_field('adv_f1_desc_ph', 'All deployments are covered under a valid DOLE license. We handle legal compliance, government remittances, and labor standards on your behalf.');
            $adv_f2_title_ph = kg_get_field('adv_f2_title_ph', 'Fast Deployment');
            $adv_f2_desc_ph = kg_get_field('adv_f2_desc_ph', 'From job order to boots on the ground — our local network of screened candidates means faster turnaround than traditional agencies.');
            $adv_f3_title_ph = kg_get_field('adv_f3_title_ph', 'Managed Payroll & Benefits');
            $adv_f3_desc_ph = kg_get_field('adv_f3_desc_ph', 'We administer payroll, SSS, PhilHealth, and Pag-IBIG remittances so you can focus on running your business, not HR paperwork.');
            $adv_cta_label_ph = 'Find Workers for My Business';
            $adv_cta_url_ph = home_url('/contact/');
            ?>
            <div class="staffing-content" style="flex: 1; min-width: 300px;">
                <h2
                    style="font-family: var(--font-subhead-family); font-weight: var(--font-subhead-weight); font-size: var(--font-subhead-size); line-height: var(--font-subhead-lh); letter-spacing: var(--font-subhead-ls); color: var(--main-blue); margin-bottom: 0.75rem;">
                    <?php echo wp_kses_post($adv_headline_ph); ?>
                </h2>
                <p style="font-size: 1.05rem; color: var(--text-dark); margin-bottom: 0.5rem; font-weight: 500;">
                    <?php echo esc_html($adv_subheadline_ph); ?>
                </p>
                <p style="color: var(--text-muted); margin-bottom: 1.5rem; line-height: 1.5; font-size: 0.95rem;">
                    <?php echo esc_html($adv_desc_ph); ?>
                </p>

                <div class="feature-folders">
                    <div class="folder-item">
                        <div class="folder-header">
                            <div class="folder-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                </svg>
                            </div>
                            <h3 class="folder-title"><?php echo esc_html($adv_f1_title_ph); ?></h3>
                        </div>
                        <div class="folder-body">
                            <div class="folder-content-inner">
                                <p><?php echo esc_html($adv_f1_desc_ph); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="folder-item">
                        <div class="folder-header">
                            <div class="folder-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                                </svg>
                            </div>
                            <h3 class="folder-title"><?php echo esc_html($adv_f2_title_ph); ?></h3>
                        </div>
                        <div class="folder-body">
                            <div class="folder-content-inner">
                                <p><?php echo esc_html($adv_f2_desc_ph); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="folder-item">
                        <div class="folder-header">
                            <div class="folder-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="2" y1="12" x2="22" y2="12"></line>
                                    <path
                                        d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="folder-title"><?php echo esc_html($adv_f3_title_ph); ?></h3>
                        </div>
                        <div class="folder-body">
                            <div class="folder-content-inner">
                                <p><?php echo esc_html($adv_f3_desc_ph); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <a href="<?php echo esc_url($adv_cta_url_ph); ?>" class="btn btn-primary"
                        style="padding: 0.85rem 2rem; font-size: 1rem;">
                        <?php echo esc_html($adv_cta_label_ph); ?>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" style="margin-left: 0.5rem;">
                            <path d="M5 12h14M12 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>

            <div class="staffing-image" style="flex: 1; min-width: 300px; position: relative;">
                <?php
                $adv_img = kg_get_field('adv_img', kg_asset('img/front-page/for-client.png'));
                echo kg_img($adv_img, 'Professional team meeting in a modern office', '', 'position: relative; z-index: 1; border-radius: var(--card-radius-lg); box-shadow: var(--shadow-lg); width: 100%; object-fit: cover; aspect-ratio: 4/3; border: 1px solid var(--border-color);');
                ?>
            </div>
        </div>

        <!-- INTL Advantage Block -->
        <div class="staffing-split intl-only animate-on-scroll"
            style="display: flex; align-items: center; gap: 3rem; flex-wrap: wrap;">
            <?php
            // INTL geo: keep offshore staffing pitch
            $adv_headline_intl = kg_get_field('adv_headline', 'Build a World-Class Team<br>at a Fraction of the Cost.');
            $adv_subheadline_intl = kg_get_field('adv_subheadline', 'Build your dedicated offshore team in the Philippines — without the risk, red tape, or overhead.');
            $adv_desc_intl = kg_get_field('adv_desc', 'Kings Group is the Philippines\' only worker-owned staffing cooperative. With 10,000+ members since 1999, you get deeply loyal, high-performing offshore professionals who treat your business like their own.');
            $adv_f1_title_intl = kg_get_field('adv_f1_title', 'Significant Cost Savings');
            $adv_f1_desc_intl = kg_get_field('adv_f1_desc', 'Filipino professionals deliver world-class output at a significantly lower cost than equivalent local hires — with zero compromise on quality or reliability.');
            $adv_f2_title_intl = kg_get_field('adv_f2_title', 'Fully Managed. Zero Hassle.');
            $adv_f2_desc_intl = kg_get_field('adv_f2_desc', 'We handle HR, payroll, compliance, hardware, and facilities. You focus on growing your business — we take care of everything on the ground.');
            $adv_f3_title_intl = kg_get_field('adv_f3_title', 'Hire in Days, Not Months');
            $adv_f3_desc_intl = kg_get_field('adv_f3_desc', 'Tap into our pre-screened talent pool of 10,000+ professionals. Most clients have their first team member deployed within 7–14 business days.');
            $adv_cta_label_intl = 'Get a Free Offshore Quote';
            $adv_cta_url_intl = home_url('/quote/');
            ?>
            <div class="staffing-content" style="flex: 1; min-width: 300px;">
                <h2
                    style="font-family: var(--font-subhead-family); font-weight: var(--font-subhead-weight); font-size: var(--font-subhead-size); line-height: var(--font-subhead-lh); letter-spacing: var(--font-subhead-ls); color: var(--main-blue); margin-bottom: 0.75rem;">
                    <?php echo wp_kses_post($adv_headline_intl); ?>
                </h2>
                <p style="font-size: 1.05rem; color: var(--text-dark); margin-bottom: 0.5rem; font-weight: 500;">
                    <?php echo esc_html($adv_subheadline_intl); ?>
                </p>
                <p style="color: var(--text-muted); margin-bottom: 1.5rem; line-height: 1.5; font-size: 0.95rem;">
                    <?php echo esc_html($adv_desc_intl); ?>
                </p>

                <div class="feature-folders">
                    <div class="folder-item">
                        <div class="folder-header">
                            <div class="folder-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                </svg>
                            </div>
                            <h3 class="folder-title"><?php echo esc_html($adv_f1_title_intl); ?></h3>
                        </div>
                        <div class="folder-body">
                            <div class="folder-content-inner">
                                <p><?php echo esc_html($adv_f1_desc_intl); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="folder-item">
                        <div class="folder-header">
                            <div class="folder-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                                </svg>
                            </div>
                            <h3 class="folder-title"><?php echo esc_html($adv_f2_title_intl); ?></h3>
                        </div>
                        <div class="folder-body">
                            <div class="folder-content-inner">
                                <p><?php echo esc_html($adv_f2_desc_intl); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="folder-item">
                        <div class="folder-header">
                            <div class="folder-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="2" y1="12" x2="22" y2="12"></line>
                                    <path
                                        d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="folder-title"><?php echo esc_html($adv_f3_title_intl); ?></h3>
                        </div>
                        <div class="folder-body">
                            <div class="folder-content-inner">
                                <p><?php echo esc_html($adv_f3_desc_intl); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <a href="<?php echo esc_url($adv_cta_url_intl); ?>" class="btn btn-primary"
                        style="padding: 0.85rem 2rem; font-size: 1rem;">
                        <?php echo esc_html($adv_cta_label_intl); ?>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" style="margin-left: 0.5rem;">
                            <path d="M5 12h14M12 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>

            <div class="staffing-image" style="flex: 1; min-width: 300px; position: relative;">
                <?php
                $adv_img = kg_get_field('adv_img', kg_asset('img/front-page/for-client.png'));
                echo kg_img($adv_img, 'Professional team meeting in a modern office', '', 'position: relative; z-index: 1; border-radius: var(--card-radius-lg); box-shadow: var(--shadow-lg); width: 100%; object-fit: cover; aspect-ratio: 4/3; border: 1px solid var(--border-color);');
                ?>
            </div>
        </div>

        <!-- For Applicants: More Than Just a Job -->
        <div class="staffing-split animate-on-scroll"
            style="display: flex; align-items: center; gap: 3rem; flex-wrap: wrap; flex-direction: row-reverse; margin-top: 0;">
            <?php
            $app_headline = kg_get_field('app_headline', '"Your Career,<br>Owned by You."');
            $app_subheadline = kg_get_field('app_subheadline', 'Join a community where you are a member, not just a number.');
            $app_desc = kg_get_field('app_desc', 'Get access to premium benefits, career coaching, and the stability of a worker-owned cooperative. It\'s built for you, by people like you.');
            $app_f1_title = kg_get_field('app_f1_title', 'Fast-Track Application');
            $app_f1_desc = kg_get_field('app_f1_desc', 'No long forms. Just drop your CV and let our recruiters find your perfect match in our global network.');
            $app_f2_title = kg_get_field('app_f2_title', 'Kings Lending Access');
            $app_f2_desc = kg_get_field('app_f2_desc', 'Need a boost? Our internal lending program is exclusively designed to support our members\' major life milestones.');
            ?>
            <div class="staffing-content" style="flex: 1; min-width: 300px;">
                <h2
                    style="font-family: var(--font-subhead-family); font-weight: var(--font-subhead-weight); font-size: var(--font-subhead-size); line-height: var(--font-subhead-lh); letter-spacing: var(--font-subhead-ls); color: var(--main-blue); margin-bottom: 0.75rem;">
                    <?php echo wp_kses_post($app_headline); ?>
                </h2>
                <p style="font-size: 1.05rem; color: var(--text-dark); margin-bottom: 0.5rem; font-weight: 500;">
                    <?php echo esc_html($app_subheadline); ?>
                </p>
                <p style="color: var(--text-muted); margin-bottom: 1.5rem; line-height: 1.5; font-size: 0.95rem;">
                    <?php echo esc_html($app_desc); ?>
                </p>
                <?php
                // PH features (f1 already defined above as $app_f1_title/$app_f1_desc)
                $app_f2_title = kg_get_field('app_f2_title', 'Skill Assessment & Training');
                $app_f2_desc = kg_get_field('app_f2_desc', 'Enhance your career readiness with personalized skill assessments and guidance to make your profile stand out.');
                $app_f3_title = kg_get_field('app_f3_title', 'Direct Employer Matching');
                $app_f3_desc = kg_get_field('app_f3_desc', 'Skip the middleman and get introduced directly to top local employers looking for your exact skillset.');

                // INTL features
                $app_intl_f1_title = kg_get_field('app_intl_f1_title', 'Global Hiring Alignment');
                $app_intl_f1_desc = kg_get_field('app_intl_f1_desc', 'We prepare candidates to work with top-tier international businesses, ensuring seamless cultural and workflow integration.');
                $app_intl_f2_title = kg_get_field('app_intl_f2_title', 'Modern Office Workspaces');
                $app_intl_f2_desc = kg_get_field('app_intl_f2_desc', 'Candidates work in our highly secure, modern workspaces equipped with high-speed internet and premium facilities.');
                $app_intl_f3_title = kg_get_field('app_intl_f3_title', 'Premium Benefits Support');
                $app_intl_f3_desc = kg_get_field('app_intl_f3_desc', 'Cooperative security and full statutory compliance support, offering unparalleled stability compared to traditional freelancing.');
                ?>
                <div class="feature-folders">
                    <?php if ($is_ph): ?>
                        <!-- Local features for PH Visitors -->
                        <div class="folder-item talent">
                            <div class="folder-header">
                                <div class="folder-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="22" y1="2" x2="11" y2="13"></line>
                                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                                    </svg>
                                </div>
                                <h3 class="folder-title"><?php echo esc_html($app_f1_title); ?></h3>
                            </div>
                            <div class="folder-body">
                                <div class="folder-content-inner">
                                    <p><?php echo esc_html($app_f1_desc); ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="folder-item talent">
                            <div class="folder-header">
                                <div class="folder-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
                                    </svg>
                                </div>
                                <h3 class="folder-title"><?php echo esc_html($app_f2_title); ?></h3>
                            </div>
                            <div class="folder-body">
                                <div class="folder-content-inner">
                                    <p><?php echo esc_html($app_f2_desc); ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="folder-item talent">
                            <div class="folder-header">
                                <div class="folder-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="9" cy="7" r="4"></circle>
                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                    </svg>
                                </div>
                                <h3 class="folder-title"><?php echo esc_html($app_f3_title); ?></h3>
                            </div>
                            <div class="folder-body">
                                <div class="folder-content-inner">
                                    <p><?php echo esc_html($app_f3_desc); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- International features for non-PH Visitors -->
                        <div class="folder-item talent">
                            <div class="folder-header">
                                <div class="folder-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path
                                            d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
                                        </path>
                                    </svg>
                                </div>
                                <h3 class="folder-title"><?php echo esc_html($app_intl_f1_title); ?></h3>
                            </div>
                            <div class="folder-body">
                                <div class="folder-content-inner">
                                    <p><?php echo esc_html($app_intl_f1_desc); ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="folder-item talent">
                            <div class="folder-header">
                                <div class="folder-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                                        <line x1="8" y1="21" x2="16" y2="21"></line>
                                        <line x1="12" y1="17" x2="12" y2="21"></line>
                                    </svg>
                                </div>
                                <h3 class="folder-title"><?php echo esc_html($app_intl_f2_title); ?></h3>
                            </div>
                            <div class="folder-body">
                                <div class="folder-content-inner">
                                    <p><?php echo esc_html($app_intl_f2_desc); ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="folder-item talent">
                            <div class="folder-header">
                                <div class="folder-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="8" x2="12" y2="12"></line>
                                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                    </svg>
                                </div>
                                <h3 class="folder-title"><?php echo esc_html($app_intl_f3_title); ?></h3>
                            </div>
                            <div class="folder-body">
                                <div class="folder-content-inner">
                                    <p><?php echo esc_html($app_intl_f3_desc); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <div>
                    <?php if ($is_ph): ?>
                        <?php $app_cta_ph = kg_get_field('app_cta_ph', 'Drop your CV Today'); ?>
                        <?php $app_cta_ph_url = kg_get_field('app_cta_ph_url', home_url('/careers/')); ?>
                        <a href="<?php echo esc_url($app_cta_ph_url); ?>" class="btn btn-outline"
                            style="border-color: var(--sec-accent-green); color: var(--text-dark); padding: 0.85rem 2rem; font-size: 1rem;">
                            <?php echo esc_html($app_cta_ph); ?>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" style="margin-left: 0.5rem;">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M17 8l-5-5-5 5M12 3v12" />
                            </svg>
                        </a>
                    <?php else: ?>
                        <?php
                        $app_cta_intl = kg_get_field('app_cta_intl', 'Find Offshore Talent');
                        $app_cta_intl_url = kg_get_field('app_cta_intl_url', home_url('/contact/'));
                        ?>
                        <a href="<?php echo esc_url($app_cta_intl_url); ?>" class="btn btn-outline"
                            style="border-color: var(--sec-accent-green); color: var(--text-dark); padding: 0.85rem 2rem; font-size: 1rem;">
                            <?php echo esc_html($app_cta_intl); ?>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" style="margin-left: 0.5rem;">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="2" y1="12" x2="22" y2="12"></line>
                                <path
                                    d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z">
                                </path>
                            </svg>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="staffing-image" style="flex: 1; min-width: 300px; position: relative;">
                <?php
                // Applicant section image — reads from ACF
                $app_img = kg_get_field('app_img', kg_asset('img/front-page/for-applicants.png'));
                echo kg_img($app_img, 'Empowered professionals collaborating globally', '', 'position: relative; z-index: 1; border-radius: var(--card-radius-lg); box-shadow: var(--shadow-lg); width: 100%; object-fit: cover; aspect-ratio: 4/3; border: 1px solid var(--border-color);');
                ?>
            </div>
        </div>
    </div> <!-- End Applicant Panel -->
    </div>
</section>

<!-- Testimonials Section — Driven by the kg_testimonial CPT -->
<?php
$testi_title = 'Testimonials';
$testi_sub = 'Hear from the empowered professionals and cooperative members who have built their careers with Kings.';
$testimonials = function_exists('kg_get_testimonials') ? kg_get_testimonials() : array();
?>
<section class="section testimonials-section" id="testimonials">
    <div class="container animate-on-scroll">
        <h2 class="section-title"><?php echo esc_html($testi_title); ?></h2>
        <p class="section-subtitle"><?php echo esc_html($testi_sub); ?></p>

        <div class="testimonials-slider-container">
            <div class="testimonials-track" id="testimonials-track">
                <?php if (!empty($testimonials)):
                    foreach ($testimonials as $i => $t):
                        $quote = get_post_meta($t->ID, '_kg_testi_quote', true);
                        $role = get_post_meta($t->ID, '_kg_testi_role', true);
                        if ($role === 'Cooperative Member') {
                            $role = 'Member';
                        }
                        $name = get_the_title($t);
                        $active_class = ($i === 0) ? ' active' : '';
                        ?>
                        <div class="testimonial-card slide<?php echo $active_class; ?>">
                            <div class="quote-icon">
                                <svg width="40" height="40" viewBox="0 0 24 24" fill="currentColor">
                                    <path
                                        d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                                </svg>
                            </div>
                            <p class="testimonial-text">“<?php echo esc_html($quote); ?>”</p>
                            <div class="testimonial-author-block">
                                <div class="author-info">
                                    <h4><?php echo esc_html($name); ?></h4>
                                    <span><?php echo esc_html($role); ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; else: ?>
                    <!-- No testimonials added yet — add them via WP Admin → Testimonials -->
                <?php endif; ?>
            </div>

            <div class="slider-controls">
                <div class="slider-dots" id="testi-dots"></div>
            </div>
        </div>
    </div>
</section>

<!-- Affiliated Brands / Network Section -->
<section class="affiliates-section animate-on-scroll">
    <div class="container">
        <?php
        $net_title = kg_get_field('net_title', 'Our Network');
        $net_subtitle = kg_get_field('net_subtitle', 'Explore our affiliated brands and communities.');
        ?>
        <div class="affiliates-header text-center">
            <h2><?php echo esc_html($net_title); ?></h2>
            <p><?php echo esc_html($net_subtitle); ?></p>
        </div>

        <div class="affiliates-showcase-container">
            <?php
            $brand1_title = kg_get_field('net_brand1_title', 'The Kings City');
            $brand1_desc = kg_get_field('net_brand1_desc', 'A space where creativity, productivity, and community come together. Designed for individuals, creatives, entrepreneurs, and growing teams, the club offers thoughtfully curated spaces for coworking, collaboration, workshops, and meaningful connections.');
            $brand1_link = kg_get_field('net_brand1_link', 'https://www.kings-city.com/');
            $brand1_btn = kg_get_field('net_brand1_btn', 'Our Space');
            $brand1_img = kg_get_field('net_brand1_img', kg_asset('img/front-page/kings-city.JPG'));

            $page_id = get_the_ID();
            if (strpos($brand1_desc, 'Our premier coworking') !== false) {
                $brand1_desc = 'A space where creativity, productivity, and community come together. Designed for individuals, creatives, entrepreneurs, and growing teams, the club offers thoughtfully curated spaces for coworking, collaboration, workshops, and meaningful connections.';
                update_post_meta($page_id, 'net_brand1_desc', $brand1_desc);
            }
            if ($brand1_btn === 'Discover Kings City') {
                $brand1_btn = 'Our Space';
                update_post_meta($page_id, 'net_brand1_btn', $brand1_btn);
            }
            ?>
            <div class="affiliate-showcase animate-on-scroll">
                <div class="affiliate-showcase-image">
                    <?php echo kg_img($brand1_img, esc_attr($brand1_title)); ?>
                </div>
                <div class="affiliate-showcase-content">
                    <h3><?php echo esc_html($brand1_title); ?></h3>
                    <p><?php echo esc_html($brand1_desc); ?></p>
                    <a href="<?php echo esc_url($brand1_link); ?>" class="btn btn-primary" style="margin-top: 1rem;"
                        target="_blank" rel="noopener">
                        <?php echo esc_html($brand1_btn); ?>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M5 12h14M12 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Brand 2: The Social Manila -->
            <?php
            $brand2_title = kg_get_field('net_brand2_title', 'The Social Manila');
            $brand2_desc = kg_get_field('net_brand2_desc', 'The premier events, lifestyle, and community engagement hub. We host corporate functions, team-building events, and exclusive gatherings designed to connect leaders and ignite culture.');
            $brand2_link = kg_get_field('net_brand2_link', 'https://kingscity.com.ph/');
            $brand2_btn = kg_get_field('net_brand2_btn', 'Explore The Social');
            $brand2_img = kg_get_field('net_brand2_img', kg_asset('img/front-page/the-social-manila.png'));
            ?>
            <div class="affiliate-showcase reverse animate-on-scroll">
                <div class="affiliate-showcase-image">
                    <?php echo kg_img($brand2_img, esc_attr($brand2_title)); ?>
                </div>
                <div class="affiliate-showcase-content">
                    <h3><?php echo esc_html($brand2_title); ?></h3>
                    <p><?php echo esc_html($brand2_desc); ?></p>
                    <a href="<?php echo esc_url($brand2_link); ?>" class="btn btn-outline" style="margin-top: 1rem;"
                        target="_blank" rel="noopener">
                        <?php echo esc_html($brand2_btn); ?>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M5 12h14M12 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Brand 3: The Home Culinary School -->
            <?php
            $brand3_title = kg_get_field('net_brand3_title', 'The Home Culinary School');
            $brand3_desc = kg_get_field('net_brand3_desc', 'Real skills for real world industries begin at Home. TESDA-accredited programs in Culinary Arts, Pastry, Barista, and more — designed for career starters, professionals, and food lovers alike.');
            $brand3_link = kg_get_field('net_brand3_link', 'https://temptest.homeculinaryschool.com/');
            $brand3_btn = kg_get_field('net_brand3_btn', 'Start Cooking');
            $brand3_img = kg_get_field('net_brand3_img', kg_asset('img/front-page/home-culinary.png'));
            // Auto-update old description in DB
            if (strpos($brand3_desc, 'Professional culinary training') !== false || strpos($brand3_desc, 'Equipping the next generation') !== false) {
                $brand3_desc = 'Real skills for real world industries begin at Home. TESDA-accredited programs in Culinary Arts, Pastry, Barista, and more — designed for career starters, professionals, and food lovers alike.';
                update_post_meta(get_the_ID(), 'net_brand3_desc', $brand3_desc);
            }
            ?>
            <div class="affiliate-showcase animate-on-scroll">
                <div class="affiliate-showcase-image">
                    <?php echo kg_img($brand3_img, esc_attr($brand3_title)); ?>
                </div>
                <div class="affiliate-showcase-content">
                    <h3><?php echo esc_html($brand3_title); ?></h3>
                    <p><?php echo esc_html($brand3_desc); ?></p>
                    <a href="<?php echo esc_url($brand3_link); ?>" class="btn btn-primary" style="margin-top: 1rem;"
                        target="_blank" rel="noopener">
                        <?php echo esc_html($brand3_btn); ?>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M5 12h14M12 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Join The Kings Section -->
<!-- Join The Kings Section -->
<?php
$jtk_title = kg_get_field('jtk_title', 'Join The Kings');

$jtk_c1_title = kg_get_field('jtk_card1_title', 'Why The Kings');
$jtk_c1_link = kg_get_field('jtk_card1_link', '/benefits/');
$jtk_c1_img = kg_get_field('jtk_card1_img', kg_asset('img/front-page/jointhekings1.png'));

$jtk_c2_title = kg_get_field('jtk_card2_title', 'Engagements');
$jtk_c2_link = kg_get_field('jtk_card2_link', '/network/');
$jtk_c2_img = kg_get_field('jtk_card2_img', kg_asset('img/front-page/jointhekings2.png'));

$jtk_c3_title = kg_get_field('jtk_card3_title', 'Community');
$jtk_c3_link = kg_get_field('jtk_card3_link', '/community/');
$jtk_c3_img = kg_get_field('jtk_card3_img', kg_asset('img/front-page/jointhekings3.png'));
?>
<section class="section join-kings-section"
    style="padding-top: 6rem; padding-bottom: 8rem; background-color: var(--bg-subtle);">
    <div class="container animate-on-scroll is-visible">
        <div style="text-align: center; margin-bottom: 4rem;">
            <h2
                style="font-size: clamp(2.5rem, 5vw, 4rem); font-family: var(--font-header); font-weight: 900; color: var(--main-blue); margin-bottom: 1rem; line-height: 1.1; letter-spacing: -0.02em;">
                <?php echo esc_html($jtk_title); ?>
            </h2>
        </div>

        <div class="jtk-grid"
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 2.5rem;">
            <!-- Card 1 -->
            <a href="<?php echo esc_url(home_url($jtk_c1_link)); ?>" class="jtk-card"
                style="text-decoration: none; color: inherit; background: var(--bg-white); border-radius: 16px; overflow: hidden; box-shadow: var(--shadow-md); transition: var(--transition); display: flex; flex-direction: column; cursor: pointer;"
                onmouseover="this.style.boxShadow='var(--shadow-xl)';"
                onmouseout="this.style.boxShadow='var(--shadow-md)';">
                <div class="jtk-img-wrapper"
                    style="width: 100%; aspect-ratio: 3/4; overflow: hidden; position: relative; border-radius: 16px;"
                    onmouseenter="this.querySelector('img').style.transform='scale(1.05)'; this.querySelector('.jtk-hover-overlay').style.opacity='1';"
                    onmouseleave="this.querySelector('img').style.transform='scale(1)'; this.querySelector('.jtk-hover-overlay').style.opacity='0';">
                    <?php echo kg_img($jtk_c1_img, esc_attr($jtk_c1_title), '', 'width: 100%; height: 100%; object-fit: cover; transition: var(--transition-slow);'); ?>
                    <div class="jtk-hover-overlay"
                        style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.45); display: flex; flex-direction: column; justify-content: center; align-items: center; opacity: 0; transition: opacity 0.3s ease; color: white; padding: 2rem; border-radius: 16px;">
                        <div
                            style="font-family: var(--font-header); font-size: 1.5rem; font-weight: 800; letter-spacing: 0.15em; text-transform: uppercase; text-align: center;">
                            <?php echo esc_html($jtk_c1_title); ?>
                        </div>
                        <div
                            style="width: 50%; height: 1px; background-color: rgba(255, 255, 255, 0.6); margin: 0.8rem 0;">
                        </div>
                        <div
                            style="font-size: 0.9rem; letter-spacing: 0.2em; text-transform: uppercase; font-weight: 500;">
                            READ MORE</div>
                    </div>
                </div>
            </a>

            <!-- Card 2 -->
            <a href="<?php echo esc_url(home_url($jtk_c2_link)); ?>" class="jtk-card"
                style="text-decoration: none; color: inherit; background: var(--bg-white); border-radius: 16px; overflow: hidden; box-shadow: var(--shadow-md); transition: var(--transition); display: flex; flex-direction: column; cursor: pointer;"
                onmouseover="this.style.boxShadow='var(--shadow-xl)';"
                onmouseout="this.style.boxShadow='var(--shadow-md)';">
                <div class="jtk-img-wrapper"
                    style="width: 100%; aspect-ratio: 3/4; overflow: hidden; position: relative; border-radius: 16px;"
                    onmouseenter="this.querySelector('img').style.transform='scale(1.05)'; this.querySelector('.jtk-hover-overlay').style.opacity='1';"
                    onmouseleave="this.querySelector('img').style.transform='scale(1)'; this.querySelector('.jtk-hover-overlay').style.opacity='0';">
                    <?php echo kg_img($jtk_c2_img, esc_attr($jtk_c2_title), '', 'width: 100%; height: 100%; object-fit: cover; transition: var(--transition-slow);'); ?>
                    <div class="jtk-hover-overlay"
                        style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.45); display: flex; flex-direction: column; justify-content: center; align-items: center; opacity: 0; transition: opacity 0.3s ease; color: white; padding: 2rem; border-radius: 16px;">
                        <div
                            style="font-family: var(--font-header); font-size: 1.5rem; font-weight: 800; letter-spacing: 0.15em; text-transform: uppercase; text-align: center;">
                            <?php echo esc_html($jtk_c2_title); ?>
                        </div>
                        <div
                            style="width: 50%; height: 1px; background-color: rgba(255, 255, 255, 0.6); margin: 0.8rem 0;">
                        </div>
                        <div
                            style="font-size: 0.9rem; letter-spacing: 0.2em; text-transform: uppercase; font-weight: 500;">
                            READ MORE</div>
                    </div>
                </div>
            </a>

            <!-- Card 3 (Community) -->
            <a href="<?php echo esc_url(home_url($jtk_c3_link)); ?>" class="jtk-card"
                style="text-decoration: none; color: inherit; background: var(--bg-white); border-radius: 16px; overflow: hidden; box-shadow: var(--shadow-md); transition: var(--transition); display: flex; flex-direction: column; cursor: pointer; position: relative;"
                onmouseover="this.style.boxShadow='var(--shadow-xl)';"
                onmouseout="this.style.boxShadow='var(--shadow-md)';">
                <div class="jtk-img-wrapper"
                    style="width: 100%; aspect-ratio: 3/4; overflow: hidden; position: relative; border-radius: 16px;"
                    onmouseenter="this.querySelector('img').style.transform='scale(1.05)'; this.querySelector('.jtk-hover-overlay').style.opacity='1';"
                    onmouseleave="this.querySelector('img').style.transform='scale(1)'; this.querySelector('.jtk-hover-overlay').style.opacity='0';">
                    <?php echo kg_img($jtk_c3_img, esc_attr($jtk_c3_title), '', 'width: 100%; height: 100%; object-fit: cover; transition: var(--transition-slow);'); ?>
                    <div class="jtk-hover-overlay"
                        style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.45); display: flex; flex-direction: column; justify-content: center; align-items: center; opacity: 0; transition: opacity 0.3s ease; color: white; padding: 2rem; border-radius: 16px;">
                        <div
                            style="font-family: var(--font-header); font-size: 1.5rem; font-weight: 800; letter-spacing: 0.15em; text-transform: uppercase; text-align: center;">
                            <?php echo esc_html($jtk_c3_title); ?>
                        </div>
                        <div
                            style="width: 50%; height: 1px; background-color: rgba(255, 255, 255, 0.6); margin: 0.8rem 0;">
                        </div>
                        <div
                            style="font-size: 0.9rem; letter-spacing: 0.2em; text-transform: uppercase; font-weight: 500;">
                            READ MORE</div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</section>

<style>
    @media (max-width: 992px) {
        .wwa-grid {
            grid-template-columns: 1fr !important;
            gap: 3rem !important;
        }

        .wwa-text-content {
            padding-right: 0 !important;
            order: 2;
        }

        .wwa-image-composition {
            order: 1;
        }

        .jtk-grid {
            grid-template-columns: 1fr !important;
            max-width: 500px;
            margin: 0 auto;
        }
    }
</style>
<?php get_footer(); ?>