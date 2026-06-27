<?php
/* Template Name: Service Labor */
?>
<?php
if (!defined('ABSPATH')) {
    require_once 'functions.php';
}
$page_title = 'Labor Management Services | Kings Group Cooperative';
$page_description = 'Comprehensive labor management services and offshore staff leasing solutions powered by the Philippines\' leading worker-owned cooperative. From recruitment to payroll — we handle it all.';

// JSON-LD: Service schema — helps Google surface this in service-type rich results
$page_schema = [
    '@context' => 'https://schema.org',
    '@type' => 'Service',
    '@id' => 'https://kingsgroup.com.ph/service-labor/#service',
    'name' => 'Labor Management Services',
    'url' => 'https://kingsgroup.com.ph/service-labor/',
    'description' => 'Comprehensive labor management services and offshore staff leasing solutions powered by the Philippines\' leading worker-owned cooperative.',
    'provider' => ['@id' => 'https://kingsgroup.com.ph/#organization'],
    'areaServed' => ['@type' => 'Country', 'name' => 'Philippines'],
    'serviceType' => 'Labor Management',
    'category' => 'Staffing & Recruitment',
    'breadcrumb' => [
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => 'https://kingsgroup.com.ph/'],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Labor Management Services', 'item' => 'https://kingsgroup.com.ph/service-labor/'],
        ],
    ],
];

$page_hero_bg = kg_get_field('slab_bg', kg_asset('img/service-labor/hero-labor.JPG'));

// ─── SVG Helper Functions (scoped to this template) ─────────────────────────
if (!function_exists('kg_svg_check')) {
    function kg_svg_check()
    {
        return '<svg class="icon-check" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>';
    }
}
if (!function_exists('kg_svg_cross')) {
    function kg_svg_cross()
    {
        return '<svg class="icon-cross" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>';
    }
}
if (!function_exists('kg_svg_dash')) {
    function kg_svg_dash()
    {
        return '<svg class="icon-dash" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="5" y1="12" x2="19" y2="12"/></svg>';
    }
}

get_header();
?>

<!-- ══════════════════════════════════════════════ -->
<!-- HERO SECTION -->
<!-- ══════════════════════════════════════════════ -->
<?php
$slab_headline = kg_get_field('slab_headline', 'Labor Management Services');
$slab_desc = kg_get_field('slab_desc', 'The Kings diversified services include the following: Managed Services and Managed Staff Leasing Services');
$slab_bg = kg_get_field('slab_bg', kg_asset('img/service-labor/hero-labor.JPG'));
$slab_bg_style = !empty($slab_bg) ? "background-image: linear-gradient(135deg, rgba(10, 25, 55, 0.82) 0%, rgba(10, 37, 64, 0.78) 100%), url('" . esc_url($slab_bg) . "');" : '';
?>
<section class="labor-hero" style="<?php echo $slab_bg_style; ?>">
    <div class="hero-inner">
        <h1 class="animate-fade-up" style="animation-delay:0.2s"><?php echo wp_kses_post($slab_headline); ?></h1>
        <p class="hero-sub animate-fade-up" style="animation-delay:0.35s"><?php echo esc_html($slab_desc); ?></p>
    </div>
</section>

<!-- ══════════════════════════════════════════════ -->
<!-- MAIN CONTENT AREA -->
<!-- ══════════════════════════════════════════════ -->
<main class="service-content container">

    <!-- ══════════════════════════════════════════════ -->
    <!-- SECTION A: MANAGED SERVICES -->
    <!-- ══════════════════════════════════════════════ -->
    <section class="animate-on-scroll" id="managed-services" style=" margin-bottom: 0;">
        <?php
        $slab_managed_label = kg_get_field('slab_managed_label', 'Managed Services');
        $slab_managed_title = kg_get_field('slab_managed_title', 'Managed Services');
        $slab_managed_desc = kg_get_field('slab_managed_desc', 'This kind of service that we are offering is focused on local companies in the Philippines.<br><br>The Kings has a very innovative concept that addresses the issue of contractual workers and their productivity. The business model we are referring to is the Manpower Service Cooperative concept of which The Kings can help you with.');

        $slab_feat1_title = kg_get_field('slab_feat1_title', 'Owner-Members');
        $slab_feat1_desc = kg_get_field('slab_feat1_desc', 'The Kings is owned by a group of professionals and workers. We are bound by a common aim of providing gainful and sustainable livelihood opportunities for each member of the organization.');

        $slab_feat2_title = kg_get_field('slab_feat2_title', 'Self-Employed Status');
        $slab_feat2_desc = kg_get_field('slab_feat2_desc', 'By being owner-members, we are SELF-EMPLOYED individuals. We are neither employees nor are we employers.');

        $slab_feat3_title = kg_get_field('slab_feat3_title', 'Flexible Engagement');
        $slab_feat3_desc = kg_get_field('slab_feat3_desc', 'You can keep our services as long as you consider it necessary without the need of absorbing our members as regular employees. This breaks the tedious cycle of recruiting, training, and terminating contractual workers.');

        $slab_feat4_title = kg_get_field('slab_feat4_title', 'Maximized Productivity');
        $slab_feat4_desc = kg_get_field('slab_feat4_desc', 'The Kings has helped increase clients’ productivity by up to 70% by way of continuous engagement of the learned skills of owner-members, while bringing down their administrative costs vis-à-vis recurring recruitment and training expenses.');
        ?>
        <div class="split-content" style="margin-bottom: 3.5rem;">
            <div class="split-text">
                <p class="section-label"><?php echo esc_html($slab_managed_label); ?></p>
                <h2><?php echo esc_html($slab_managed_title); ?></h2>
                <div style="font-size: 0.95rem; color: var(--text-muted); line-height: 1.8; margin-bottom: 2rem;">
                    <?php echo wp_kses_post($slab_managed_desc); ?>
                </div>
            </div>
            <div class="image-wrapper"
                style="box-shadow: var(--shadow-xl); border-radius: 24px; overflow: hidden; aspect-ratio: 4/3; width: 100%;">
                <?php $slab_intro_img = kg_get_field('slab_intro_img', kg_asset('img/service-labor/kings-labor-content1.JPG')); ?>
                <img src="<?php echo esc_url($slab_intro_img); ?>" alt="Kings labor management professionals"
                    style="width: 100%; height: 100%; object-fit: cover;">
            </div>
        </div>

        <!-- 4-Column steps grid below the split -->
        <div class="editorial-4col-grid" style="margin-top: 2.5rem; margin-bottom: 4.5rem;">
            <div class="editorial-step">
                <span class="step-num">01</span>
                <h4><?php echo esc_html($slab_feat1_title); ?></h4>
                <p><?php echo esc_html($slab_feat1_desc); ?></p>
            </div>
            <div class="editorial-step">
                <span class="step-num">02</span>
                <h4><?php echo esc_html($slab_feat2_title); ?></h4>
                <p><?php echo esc_html($slab_feat2_desc); ?></p>
            </div>
            <div class="editorial-step">
                <span class="step-num">03</span>
                <h4><?php echo esc_html($slab_feat3_title); ?></h4>
                <p><?php echo esc_html($slab_feat3_desc); ?></p>
            </div>
            <div class="editorial-step">
                <span class="step-num">04</span>
                <h4><?php echo esc_html($slab_feat4_title); ?></h4>
                <p><?php echo esc_html($slab_feat4_desc); ?></p>
            </div>
        </div>

        <!-- Total Manpower Solutions Section -->
        <?php
        $slab_manpower_title = kg_get_field('slab_manpower_title', 'Total Manpower Solutions');
        ?>
        <div class="split-content" style="margin-top: 4.5rem; align-items: center; gap: 4rem;">
            <div class="image-wrapper"
                style="box-shadow: var(--shadow-xl); border-radius: 24px; overflow: hidden; aspect-ratio: 4/3; width: 100%;">
                <?php $slab_mp_img = kg_get_field('slab_manpower_img', kg_asset('img/service-labor/total-manpower.png'));
                echo kg_img($slab_mp_img, 'Professional meeting for manpower solutions', 'image-placeholder', 'width: 100%; height: 100%; object-fit: cover;'); ?>
            </div>
            <div class="split-text">


                <?php
                $slab_manpower_text = kg_get_field('slab_manpower_text');
                if (!empty($slab_manpower_text)) {
                    echo wp_kses_post(wpautop($slab_manpower_text));
                }
                ?>
            </div>
        </div>
    </section>
</main>

<!-- ══════════════════════════════════════════════ -->
<!-- SECTION B: MANAGED STAFF LEASING SERVICES -->
<!-- ══════════════════════════════════════════════ -->
<div class="slab-section-alt-bg" style="margin-top: 1rem;">
    <div class="container">
        <section class="animate-on-scroll" id="managed-staff-leasing"
            style="padding-top: 0; padding-bottom: 3.5rem; margin-bottom: 0;">
            <?php
            $lease_label = kg_get_field('slab_lease_label', 'Offshore Staff Leasing');
            $lease_title = kg_get_field('slab_lease_title', 'Managed Staff Leasing Services');
            $lease_desc = kg_get_field('slab_lease_desc', 'This service is primarily for our Offshore Clients in countries such as Australia and the United States of America.<br><br>Managed Staff Leasing is a business delivery between Kings and our clients, where we take care of recruiting staff for our client\'s particular needs, along with providing equipment and overseeing operations of our clients, ensuring that their leased staff from Kings are delivering the quality of work that is expected of them by the client.');
            $slab_offshore_label = kg_get_field('slab_offshore_label', 'Cooperative Advantage');
            $slab_offshore_title = kg_get_field('slab_offshore_title', 'How Does Offshore Managed Staff Leasing Work?');
            ?>

            <div class="split-content" style="align-items: center; margin-bottom: 1.5rem; gap: 4rem;">
                <div class="split-text">
                    <p class="section-label"><?php echo esc_html($lease_label); ?></p>
                    <h2><?php echo esc_html($lease_title); ?></h2>
                    <div style="font-size: 1.1rem; color: var(--text-muted); line-height: 1.8;">
                        <?php echo wp_kses_post($lease_desc); ?>
                    </div>
                </div>
                <div class="image-wrapper"
                    style="box-shadow: var(--shadow-xl); border-radius: 24px; overflow: hidden; aspect-ratio: 4/3; width: 100%;">
                    <?php
                    $lease_header_img = kg_get_field('slab_manpower_img', kg_asset('img/service-labor/total-manpower.png'));
                    echo kg_img($lease_header_img, 'Offshore managed staff leasing header', 'image-placeholder', 'width: 100%; height: 100%; object-fit: cover;');
                    ?>
                </div>
            </div>

            <!-- How it Works Section (Alternating: Image LEFT, Text RIGHT) -->
            <div class="split-content" style="align-items: center; margin-bottom: 1.5rem; gap: 4rem;">
                <div class="image-wrapper"
                    style="box-shadow: var(--shadow-xl); border-radius: 24px; overflow: hidden; aspect-ratio: 4/3; width: 100%;">
                    <?php $slab_off_img = kg_get_field('slab_offshore_img', kg_asset('img/service-labor/offshore-staff-leasing.png'));
                    echo kg_img($slab_off_img, 'Remote offshore team collaboration', 'image-placeholder', 'width: 100%; height: 100%; object-fit: cover;'); ?>
                </div>
                <div class="split-text">
                    <p class="section-label"><?php echo esc_html($slab_offshore_label); ?></p>
                    <h3
                        style="margin-bottom: 1.5rem; font-size: 1.8rem; color: var(--main-blue); font-family: var(--font-header); font-weight: 700;">
                        <?php echo esc_html($slab_offshore_title); ?>
                    </h3>
                    <?php
                    $slab_offshore_text = kg_get_field('slab_offshore_text');
                    if (!empty($slab_offshore_text)) {
                        echo wp_kses_post(wpautop($slab_offshore_text));
                    }
                    ?>
                </div>
            </div>

            <!-- 2. Improving Your Manpower (Alternating: Text LEFT, Image RIGHT) -->
            <?php
            $slab_improve_title = kg_get_field('slab_improve_title', 'Improving Your Manpower');
            $slab_improve_desc = kg_get_field('slab_improve_desc', 'Our Offshore Managed Staff Leasing services can help improve your manpower. We will take care of all the administrative requirements, allowing you to focus more on income-generating activities and maintaining your business\' competitive edge. We source, recruit and onboard your offshore team or resource in line with your business needs.');
            $slab_check1_title = kg_get_field('slab_check1_title', 'Cost Efficient');
            $slab_check1_desc = kg_get_field('slab_check1_desc', 'Outsourcing allows companies to save overhead costs such as adding more equipment and space to accommodate and train additional employees.');
            $slab_check2_title = kg_get_field('slab_check2_title', 'Set Up');
            $slab_check2_desc = kg_get_field('slab_check2_desc', 'We provide your offshore team with the space, facilities, equipment\'s and everything they need to get the job done.');
            $slab_check3_title = kg_get_field('slab_check3_title', 'Extensive HR and Payroll Services');
            $slab_check3_desc = kg_get_field('slab_check3_desc', 'The Kings offers a comprehensive set of services that are designed to optimize our clients\' talent management, cost saving measures and improve your processes while also giving you full control on overseeing your offshore team.');
            ?>
            <div class="split-content" style="margin-top: 1rem; align-items: center; gap: 4rem;">
                <div class="split-text">
                    <p style="font-size: 1.1rem; color: var(--text-muted); line-height: 1.8; margin-bottom: 2rem;">
                        <?php echo esc_html($slab_improve_desc); ?>
                    </p>
                    <div class="editorial-checklist" style="gap: 0.75rem;">
                        <div class="checklist-item" style="flex-direction: row; align-items: flex-start;">
                            <span class="checklist-num"
                                style="font-size: 1rem; min-width: 28px; flex-shrink: 0;">01</span>
                            <div class="checklist-content">
                                <h4 style="font-size: 0.95rem;"><?php echo esc_html($slab_check1_title); ?></h4>
                                <p style="font-size: 0.88rem;"><?php echo esc_html($slab_check1_desc); ?></p>
                            </div>
                        </div>
                        <div class="checklist-item" style="flex-direction: row; align-items: flex-start;">
                            <span class="checklist-num"
                                style="font-size: 1rem; min-width: 28px; flex-shrink: 0;">02</span>
                            <div class="checklist-content">
                                <h4 style="font-size: 0.95rem;"><?php echo esc_html($slab_check2_title); ?></h4>
                                <p style="font-size: 0.88rem;"><?php echo esc_html($slab_check2_desc); ?></p>
                            </div>
                        </div>
                        <div class="checklist-item" style="flex-direction: row; align-items: flex-start;">
                            <span class="checklist-num"
                                style="font-size: 1rem; min-width: 28px; flex-shrink: 0;">03</span>
                            <div class="checklist-content">
                                <h4 style="font-size: 0.95rem;"><?php echo esc_html($slab_check3_title); ?></h4>
                                <p style="font-size: 0.88rem;"><?php echo esc_html($slab_check3_desc); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="image-wrapper"
                    style="box-shadow: var(--shadow-xl); border-radius: 24px; overflow: hidden; aspect-ratio: 4/3; width: 100%;">
                    <?php $slab_imp_img = kg_get_field('slab_improve_img', kg_asset('img/service-labor/improve-manpower.png'));
                    echo kg_img($slab_imp_img, 'Modern office workspace for offshore team', 'image-placeholder', 'width: 100%; height: 100%; object-fit: cover;'); ?>
                </div>
            </div>

            <!-- 3. Concluding Highlight Section -->
            <?php
            $slab_onboard_title = kg_get_field('slab_onboard_title', 'What is involved in offshore Managed Staff Leasing to the Philippines?');
            $slab_onboard_desc = kg_get_field('slab_onboard_desc', 'Your team in the Philippines is legally employed and managed by The Kings but they report directly to you.');
            ?>
            <div class="onboarding-statement-box animate-on-scroll" style="margin-top: 3.5rem;">
                <h3><?php echo esc_html($slab_onboard_title); ?></h3>
                <p><?php echo esc_html($slab_onboard_desc); ?></p>
            </div>
        </section>
    </div>
</div>

<!-- CTA -->
<?php
$is_ph = (kg_get_user_geo() === 'PH');

if ($is_ph) {
    $cta_title = kg_get_field('slab_cta_title_ph', 'Your Trusted Provider for Manpower Solutions & Career Growth');
    $cta_btn1_text = kg_get_field('slab_cta_btn1_ph', 'Inquire for Manpower Services');
    $cta_btn1_url = kg_get_field('slab_cta_btn1_url_ph', home_url('/contact/'));
    $cta_btn2_text = kg_get_field('slab_cta_btn2_ph', 'Explore Career Opportunities');
    $cta_btn2_url = kg_get_field('slab_cta_btn2_url_ph', home_url('/careers/'));
    $cta_subtext = kg_get_field('slab_cta_subtext_ph', 'Providing dependable local staffing services across industries while empowering 10,000+ member-owners nationwide with complete benefits and ethical opportunities.');
} else {
    $cta_title = kg_get_field('slab_cta_title_intl', 'Scale Your Global Operations with Elite Philippine Talent');
    $cta_btn1_text = kg_get_field('slab_cta_btn1_intl', 'Request a Custom Quote');
    $cta_btn1_url = kg_get_field('slab_cta_btn1_url_intl', home_url('/quote/'));
    $cta_btn2_text = kg_get_field('slab_cta_btn2_intl', 'Contact Staffing Experts');
    $cta_btn2_url = kg_get_field('slab_cta_btn2_url_intl', home_url('/contact/'));
    $cta_subtext = kg_get_field('slab_cta_subtext_intl', 'Partner with top-tier offshore teams under an ethical, worker-owned cooperative model. Rapid integration and full operational support in under 14 days.');
}
?>
<div class="cta-bottom-section">
    <div class="container animate-on-scroll">
        <div class="cta-glass-card">
            <h2><?php echo esc_html($cta_title); ?></h2>
            <div class="cta-buttons">
                <a href="<?php echo esc_url($cta_btn1_url); ?>" class="btn btn-gold cta-btn-interactive">
                    <span><?php echo esc_html($cta_btn1_text); ?></span>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14M12 5l7 7-7 7" />
                    </svg>
                </a>
                <a href="<?php echo esc_url($cta_btn2_url); ?>" class="btn btn-outline cta-btn-interactive">
                    <span><?php echo esc_html($cta_btn2_text); ?></span>
                </a>
            </div>
            <p class="cta-card-subtext"><?php echo esc_html($cta_subtext); ?></p>
        </div>
    </div>
</div>

<?php get_footer(); ?>