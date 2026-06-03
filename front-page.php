<?php
/* Template Name: Home */
?>
<?php
if (!defined('ABSPATH')) {
    require_once 'functions.php';
}
$page_title = 'Kings Group | Elite Talent. Ethical Staffing.';
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

$page_hero_bg = kg_get_field('hero_img_1', 'https://images.unsplash.com/photo-1600880292203-757bb62b4baf?auto=format&fit=crop&w=2000&q=80');

get_header();
?>

<!-- The Hero Section: People-First Hook -->
<section class="hero">
    <?php
    $headline = kg_get_field('hero_headline', 'Elite Talent.<br><span>Ethical Staffing.</span>Exceptional Results.');
    $description = kg_get_field('hero_description', 'We connect global businesses with the Philippines\' top professionals. Established in 1999 as a worker-owned cooperative, our people aren\'t just staff—they are partners in your success.');
    $slides = [];
    $default_images = [
        1 => 'https://images.unsplash.com/photo-1600880292203-757bb62b4baf?auto=format&fit=crop&w=2000&q=80',
        2 => 'https://images.unsplash.com/photo-1556761175-4b46a572b786?auto=format&fit=crop&w=2000&q=80',
        3 => 'https://images.unsplash.com/photo-1521737711867-e3b97375f902?auto=format&fit=crop&w=2000&q=80',
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
    <div class="hero-content">
        <h1><?php echo wp_kses_post($headline); ?></h1>
        <p><?php echo esc_html($description); ?></p>
        <div class="hero-buttons">
            <a href="<?php echo esc_url(home_url('/quote/')); ?>" class="btn btn-primary">
                Build Your Team
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
                </svg>
            </a>
            <a href="<?php echo esc_url(home_url('/our-jobs/')); ?>" class="btn btn-outline"
                style="background: rgba(255,255,255,0.1); color: white; border-color: rgba(255,255,255,0.3); backdrop-filter: blur(5px);">
                View Open Roles
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8" />
                    <path d="m21 21-4.3-4.3" />
                </svg>
            </a>
        </div>
    </div>
</section>

<!-- Trust Bar -->
<section class="trust-bar animate-on-scroll">
    <div class="trust-bar-label">
        <?php echo esc_html(kg_get_field('trust_label', 'Trusted by leading organizations worldwide')); ?></div>
    <div class="trust-logos-track">
        <img src="<?php echo kg_asset('img/adidas.webp'); ?>" alt="Adidas" loading="lazy">
        <img src="<?php echo kg_asset('img/Alveo.webp'); ?>" alt="Alveo" loading="lazy">
        <img src="<?php echo kg_asset('img/Am-Europharma Corporation.webp'); ?>" alt="Am-Europharma Corporation"
            loading="lazy">
        <img src="<?php echo kg_asset('img/american-garden.webp'); ?>" alt="American Garden" loading="lazy">
        <img src="<?php echo kg_asset('img/Antech.webp'); ?>" alt="Antech" loading="lazy">
        <img src="<?php echo kg_asset('img/apple.webp'); ?>" alt="Apple" loading="lazy">
        <img src="<?php echo kg_asset('img/bakers-secret.webp'); ?>" alt="Bakers Secret" loading="lazy">
        <img src="<?php echo kg_asset('img/brent.webp'); ?>" alt="Brent" loading="lazy">
        <img src="<?php echo kg_asset('img/BuildbyForgems.webp'); ?>" alt="Build by Forgems" loading="lazy">
        <img src="<?php echo kg_asset('img/ceres.webp'); ?>" alt="Ceres" loading="lazy">
        <img src="<?php echo kg_asset('img/Chowking.webp'); ?>" alt="Chowking" loading="lazy">
        <img src="<?php echo kg_asset('img/CircleK.webp'); ?>" alt="Circle K" loading="lazy">
        <img src="<?php echo kg_asset('img/cocolife.webp'); ?>" alt="Cocolife" loading="lazy">
        <img src="<?php echo kg_asset('img/concentrix.webp'); ?>" alt="Concentrix" loading="lazy">
        <img src="<?php echo kg_asset('img/Defoam.webp'); ?>" alt="Defoam" loading="lazy">
        <img src="<?php echo kg_asset('img/donamaria.webp'); ?>" alt="Dona Maria" loading="lazy">
        <img src="<?php echo kg_asset('img/dost.webp'); ?>" alt="DOST" loading="lazy">
        <img src="<?php echo kg_asset('img/ekco.webp'); ?>" alt="Ekco" loading="lazy">
        <img src="<?php echo kg_asset('img/entrego.webp'); ?>" alt="Entrego" loading="lazy">
        <img src="<?php echo kg_asset('img/eurocare.webp'); ?>" alt="Eurocare" loading="lazy">
        <img src="<?php echo kg_asset('img/evian.webp'); ?>" alt="Evian" loading="lazy">
        <img src="<?php echo kg_asset('img/expressionslogo.webp'); ?>" alt="Expressions" loading="lazy">
        <img src="<?php echo kg_asset('img/FDI.webp'); ?>" alt="FDI" loading="lazy">
        <img src="<?php echo kg_asset('img/Forgems Marketing.webp'); ?>" alt="Forgems Marketing" loading="lazy">
        <img src="<?php echo kg_asset('img/ForgemsPlusTech.webp'); ?>" alt="Forgems Plus Tech" loading="lazy">
        <img src="<?php echo kg_asset('img/frabelle.webp'); ?>" alt="Frabelle" loading="lazy">
        <img src="<?php echo kg_asset('img/hariraya cafe.webp'); ?>" alt="Hariraya Cafe" loading="lazy">
        <img src="<?php echo kg_asset('img/home-couture.webp'); ?>" alt="Home Couture" loading="lazy">
        <img src="<?php echo kg_asset('img/iguanas.webp'); ?>" alt="Iguanas" loading="lazy">
        <img src="<?php echo kg_asset('img/johnsinville.webp'); ?>" alt="Johnsinville" loading="lazy">
        <img src="<?php echo kg_asset('img/Jollibee.webp'); ?>" alt="Jollibee" loading="lazy">
        <img src="<?php echo kg_asset('img/kaftheinz.webp'); ?>" alt="Kraft Heinz" loading="lazy">
        <img src="<?php echo kg_asset('img/kamiseta.webp'); ?>" alt="Kamiseta" loading="lazy">
        <img src="<?php echo kg_asset('img/kapeZambo.webp'); ?>" alt="Kape Zambo" loading="lazy">
        <img src="<?php echo kg_asset('img/karimadon.webp'); ?>" alt="Karimadon" loading="lazy">
        <img src="<?php echo kg_asset('img/klg.webp'); ?>" alt="KLG" loading="lazy">
        <img src="<?php echo kg_asset('img/kraft phil.webp'); ?>" alt="Kraft Philippines" loading="lazy">
        <img src="<?php echo kg_asset('img/libbys.webp'); ?>" alt="Libbys" loading="lazy">
        <img src="<?php echo kg_asset('img/Ma-Ling-Logo1.webp'); ?>" alt="Ma Ling" loading="lazy">
        <img src="<?php echo kg_asset('img/mamypoko.webp'); ?>" alt="MamyPoko" loading="lazy">
        <img src="<?php echo kg_asset('img/MartOne.webp'); ?>" alt="Mart One" loading="lazy">
        <img src="<?php echo kg_asset('img/maxicare.webp'); ?>" alt="Maxicare" loading="lazy">
        <img src="<?php echo kg_asset('img/Metro.webp'); ?>" alt="Metro" loading="lazy">
        <img src="<?php echo kg_asset('img/Mirage.webp'); ?>" alt="Mirage" loading="lazy">
        <img src="<?php echo kg_asset('img/palmlogo.webp'); ?>" alt="Palm" loading="lazy">
        <img src="<?php echo kg_asset('img/PhilippineWaters.webp'); ?>" alt="Philippine Waters" loading="lazy">
        <img src="<?php echo kg_asset('img/pocari-sweat.webp'); ?>" alt="Pocari Sweat" loading="lazy">
        <img src="<?php echo kg_asset('img/samgyupsalamat.webp'); ?>" alt="Samgyupsalamat" loading="lazy">
        <img src="<?php echo kg_asset('img/scpa.webp'); ?>" alt="SCPA" loading="lazy">
        <img src="<?php echo kg_asset('img/slagritech.webp'); ?>" alt="SL Agritech" loading="lazy">
        <img src="<?php echo kg_asset('img/Sofitel.webp'); ?>" alt="Sofitel" loading="lazy">
        <img src="<?php echo kg_asset('img/spam.webp'); ?>" alt="Spam" loading="lazy">
        <img src="<?php echo kg_asset('img/Sterlingpaper logo.webp'); ?>" alt="Sterling Paper" loading="lazy">
        <img src="<?php echo kg_asset('img/subway.webp'); ?>" alt="Subway" loading="lazy">
        <img src="<?php echo kg_asset('img/temmys.webp'); ?>" alt="Temmys" loading="lazy">
        <img src="<?php echo kg_asset('img/tigerbalm.webp'); ?>" alt="Tiger Balm" loading="lazy">
        <img src="<?php echo kg_asset('img/vice.webp'); ?>" alt="Vice" loading="lazy">
        <img src="<?php echo kg_asset('img/vismay.webp'); ?>" alt="Vismay" loading="lazy">
        <img src="<?php echo kg_asset('img/volvic.webp'); ?>" alt="Volvic" loading="lazy">
        <img src="<?php echo kg_asset('img/worldchicken.webp'); ?>" alt="World Chicken" loading="lazy">
        <img src="<?php echo kg_asset('img/zalora.webp'); ?>" alt="Zalora" loading="lazy">
    </div>
</section>

<!-- High-Converting Copy Sections -->
<section class="section section-bg-light">
    <div class="container animate-on-scroll">
        <?php
        $intro_title = kg_get_field('home_intro_title', 'A Different Kind of Staffing');
        $intro_sub = kg_get_field('home_intro_sub', 'Bridging the gap between a traditional agency and a modern global talent platform to serve businesses and career-seekers alike.');
        ?>
        <h2 class="section-title" style="color: var(--main-blue); margin-bottom: 0.5rem;">
            <?php echo esc_html($intro_title); ?></h2>
        <p class="section-subtitle" style="margin-bottom: 3rem;"><?php echo esc_html($intro_sub); ?></p>
    </div>

    <div class="container" style="display: flex; flex-direction: column; gap: 5rem; padding-top: 0.5rem;">
        <!-- For Clients: The Kings Advantage -->
        <div class="staffing-split animate-on-scroll"
            style="display: flex; align-items: center; gap: 3rem; flex-wrap: wrap;">
            <?php
            $adv_headline = kg_get_field('adv_headline', 'Your Dedicated Philippine HQ<br>Without the Overhead.');
            $adv_subheadline = kg_get_field('adv_subheadline', 'Stop "outsourcing" and start "building."');
            $adv_desc = kg_get_field('adv_desc', 'Access elite Filipino talent through a worker-owned cooperative with 10,000 members. Since 1999, every team member has been personally invested in your growth.');
            $adv_stat = kg_get_field('adv_stat', '10000');
            $adv_f1_title = kg_get_field('adv_f1_title', 'Owner-Level Commitment');
            $adv_f1_desc = kg_get_field('adv_f1_desc', 'They aren\'t just "working for a paycheck." They have a literal stake in the company\'s success.');
            $adv_f2_title = kg_get_field('adv_f2_title', 'The "Zero-Hassle" Guarantee');
            $adv_f2_desc = kg_get_field('adv_f2_desc', 'We handle strict DOLE compliance, premium hardware, and operations so you can focus on leadership.');
            $adv_f3_title = kg_get_field('adv_f3_title', 'Ethical Global Sourcing');
            $adv_f3_desc = kg_get_field('adv_f3_desc', 'Care about ESG? By hiring through Kings, you support an ethical, worker-centric business model that empowers the local community.');
            ?>
            <div class="staffing-content" style="flex: 1; min-width: 300px;">
                <h2
                    style="font-size: 2rem; font-family: var(--font-header); color: var(--main-blue); margin-bottom: 0.75rem; line-height: 1.2;">
                    <?php echo wp_kses_post($adv_headline); ?>
                </h2>
                <p style="font-size: 1.05rem; color: var(--text-dark); margin-bottom: 0.5rem; font-weight: 500;">
                    <?php echo esc_html($adv_subheadline); ?>
                </p>
                <p style="color: var(--text-muted); margin-bottom: 1.5rem; line-height: 1.5; font-size: 0.95rem;">
                    <?php echo esc_html($adv_desc); ?>
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
                            <h3 class="folder-title"><?php echo esc_html($adv_f1_title); ?></h3>
                        </div>
                        <div class="folder-body">
                            <div class="folder-content-inner">
                                <p><?php echo esc_html($adv_f1_desc); ?></p>
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
                            <h3 class="folder-title"><?php echo esc_html($adv_f2_title); ?></h3>
                        </div>
                        <div class="folder-body">
                            <div class="folder-content-inner">
                                <p><?php echo esc_html($adv_f2_desc); ?></p>
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
                            <h3 class="folder-title"><?php echo esc_html($adv_f3_title); ?></h3>
                        </div>
                        <div class="folder-body">
                            <div class="folder-content-inner">
                                <p><?php echo esc_html($adv_f3_desc); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <a href="<?php echo esc_url(home_url('/quote/')); ?>" class="btn btn-primary"
                        style="padding: 0.85rem 2rem; font-size: 1rem;">
                        Start Hiring Now
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" style="margin-left: 0.5rem;">
                            <path d="M5 12h14M12 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>

            <div class="staffing-image" style="flex: 1; min-width: 300px; position: relative;">
                <?php
                // Client advantage image — reads from ACF
                $adv_img = kg_get_field('adv_img', 'https://images.unsplash.com/photo-1556761175-4b46a572b786?auto=format&fit=crop&w=1200&q=80');
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
            $app_f3_title = kg_get_field('app_f3_title', 'Wealth Building');
            $app_f3_desc = kg_get_field('app_f3_desc', 'Build true financial security. Share in the true economic success we create together as a cooperative.');
            ?>
            <div class="staffing-content" style="flex: 1; min-width: 300px;">
                <h2
                    style="font-size: 2rem; font-family: var(--font-header); color: var(--main-blue); margin-bottom: 0.75rem; line-height: 1.2;">
                    <?php echo wp_kses_post($app_headline); ?>
                </h2>
                <p style="font-size: 1.05rem; color: var(--text-dark); margin-bottom: 0.5rem; font-weight: 500;">
                    <?php echo esc_html($app_subheadline); ?>
                </p>
                <p style="color: var(--text-muted); margin-bottom: 1.5rem; line-height: 1.5; font-size: 0.95rem;">
                    <?php echo esc_html($app_desc); ?>
                </p>

                <div class="feature-folders">
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
                                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                    <line x1="1" y1="10" x2="23" y2="10"></line>
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
                                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                    <polyline points="17 6 23 6 23 12"></polyline>
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
                </div>

                <div>
                    <a href="<?php echo esc_url(home_url('/careers/')); ?>" class="btn btn-outline"
                        style="border-color: var(--sec-accent-green); color: var(--text-dark); padding: 0.85rem 2rem; font-size: 1rem;">
                        Drop your CV Today
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" style="margin-left: 0.5rem;">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M17 8l-5-5-5 5M12 3v12" />
                        </svg>
                    </a>
                </div>
            </div>
            <div class="staffing-image" style="flex: 1; min-width: 300px; position: relative;">
                <?php
                // Applicant section image — reads from ACF
                $app_img = kg_get_field('app_img', 'https://images.unsplash.com/photo-1521737711867-e3b97375f902?auto=format&fit=crop&w=1200&q=80');
                echo kg_img($app_img, 'Empowered professionals collaborating globally', '', 'position: relative; z-index: 1; border-radius: var(--card-radius-lg); box-shadow: var(--shadow-lg); width: 100%; object-fit: cover; aspect-ratio: 4/3; border: 1px solid var(--border-color);');
                ?>
            </div>
        </div>
    </div> <!-- End Applicant Panel -->
    </div>
</section>

<!-- Testimonials Section — Driven by the kg_testimonial CPT -->
<?php
$testi_title = kg_get_field('testi_title', 'What Our Members Say');
if (!$testi_title || $testi_title === 'What Our Partners Say') {
    $testi_title = 'What Our Members Say';
}
$testi_sub = kg_get_field('testi_subtitle', 'Hear from the empowered professionals and cooperative members who have built their careers with Kings.');
if (!$testi_sub || $testi_sub === 'Hear from the organizations and professionals who have experienced the Kings cooperative difference.') {
    $testi_sub = 'Hear from the empowered professionals and cooperative members who have built their careers with Kings.';
}
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
            <!-- Brand 1: The Kings City -->
            <?php
            $brand1_title = kg_get_field('net_brand1_title', 'The Kings City');
            $brand1_desc = kg_get_field('net_brand1_desc', 'Our premier coworking and flex-office brand. We provide modern, inspiring workspaces designed to foster collaboration, innovation, and productivity for professionals in the heart of the business district.');
            $brand1_link = kg_get_field('net_brand1_link', 'https://www.kings-city.com/');
            $brand1_btn = kg_get_field('net_brand1_btn', 'Discover Kings City');
            $brand1_img = kg_get_field('net_brand1_img', 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=800&q=80');
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
            $brand2_img = kg_get_field('net_brand2_img', 'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=800&q=80');
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
            $brand3_desc = kg_get_field('net_brand3_desc', 'Professional culinary training and certification programs. Equipping the next generation of chefs and hospitality professionals with world-class skills, discipline, and ethical standards.');
            $brand3_link = kg_get_field('net_brand3_link', 'https://unique-souffle-78e15a.netlify.app/');
            $brand3_btn = kg_get_field('net_brand3_btn', 'Start Cooking');
            $brand3_img = kg_get_field('net_brand3_img', 'https://images.unsplash.com/photo-1556910103-1c02745a872e?auto=format&fit=crop&w=800&q=80');
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

<?php get_footer(); ?>