<?php
/* Template Name: Service Kit */
?>
<?php
if ( ! defined( 'ABSPATH' ) ) {
    require_once 'functions.php';
}
$page_title       = 'HR & Payroll System (KIT) | Kings Group Cooperative';
$page_description = 'Streamline your HR and payroll with Kings Group\'s specialized KIT system, designed for efficient labor management.';

// JSON-LD: Service schema for KIT (SoftwareApplication + Service hybrid)
$page_schema = [
    '@context'    => 'https://schema.org',
    '@type'       => 'Service',
    '@id'         => 'https://kingsgroup.com.ph/service-kit/#service',
    'name'        => 'Kings Information Technology (KIT) — HR & Payroll System',
    'url'         => 'https://kingsgroup.com.ph/service-kit/',
    'description' => 'Streamline your HR and payroll with Kings Group\'s specialized KIT system, designed for efficient labor management.',
    'provider'    => [ '@id' => 'https://kingsgroup.com.ph/#organization' ],
    'areaServed'  => [ '@type' => 'Country', 'name' => 'Philippines' ],
    'serviceType' => 'HR Technology',
    'category'    => 'Human Resources Software',
    'breadcrumb'  => [
        '@type'           => 'BreadcrumbList',
        'itemListElement' => [
            [ '@type' => 'ListItem', 'position' => 1, 'name' => 'Home',                'item' => 'https://kingsgroup.com.ph/' ],
            [ '@type' => 'ListItem', 'position' => 2, 'name' => 'HR & Payroll (KIT)', 'item' => 'https://kingsgroup.com.ph/service-kit/' ],
        ],
    ],
];

get_header();
?>

    <!-- Premium Animated Hero -->
    <?php
    $skit_headline = kg_get_field('skit_headline', 'HR & Payroll System');
    $skit_desc = kg_get_field('skit_desc', 'Kings Information Technology (KIT)');
    ?>
    <section class="kit-hero">
        <div class="hero-inner">
            <h1><?php echo wp_kses_post($skit_headline); ?></h1>
            <p class="hero-sub"><?php echo esc_html($skit_desc); ?></p>
        </div>
    </section>

    <!-- Main Content Area -->
    <main class="service-content container">
        <div class="service-intro animate-on-scroll">
            <div class="service-intro-text">
                <?php
                $skit_intro_title = kg_get_field('skit_intro_title', 'Empowering Growth through Localized Software.');
                $skit_intro_desc = kg_get_field('skit_intro_desc', 'Kings Information Technology (KIT) is a software the company created offering the best solution for the Philippines HR demands. The Philippines has a great need for a localized software that is why KIT was born.\n\nOur goal is to help companies in the Philippines grow through our suite of backend solutions that address payroll, HR and recruitment challenges.');
                ?>
                <h2 class="intro-lead"><?php echo esc_html($skit_intro_title); ?></h2>
                <?php echo wpautop(wp_kses_post($skit_intro_desc)); ?>

                <?php
                $skit_intro_pills_raw = kg_get_field('skit_intro_pills', "Tailored for the Philippines\nEnd-to-end HR Solution\nAutomated Payroll");
                $skit_intro_pills_lines = array_filter( array_map( 'trim', explode( "\n", $skit_intro_pills_raw ) ) );
                ?>
                <div class="pills-container" style="margin-top: 2.5rem;">
                    <?php foreach ( $skit_intro_pills_lines as $pill_line ) : ?>
                    <span class="pill-item"><span style="color:var(--sec-accent-green); margin-right: 0.5rem;">✓</span>
                        <?php echo esc_html( $pill_line ); ?></span>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Redesigned Overlapping Images -->
            <?php
            $skit_intro_img1 = function_exists('get_field') ? get_field('skit_intro_img1', get_queried_object_id()) : '';
            $skit_intro_img2 = function_exists('get_field') ? get_field('skit_intro_img2', get_queried_object_id()) : '';
            ?>
            <div class="stacked-images-container">
                <div class="img-back">
                    <?php echo kg_img($skit_intro_img1, 'HR Dashboard Analytics'); ?>
                </div>
                <div class="img-front">
                    <?php echo kg_img($skit_intro_img2, 'Team using software'); ?>
                </div>
            </div>
        </div>

        <section class="animate-on-scroll" id="how-we-work" style="margin-bottom: 8rem;">
            <div class="split-content reverse" style="margin-top: 5rem;">
                <div class="split-text">
                    <?php
                    $skit_hww_title = kg_get_field('skit_hww_title', 'How We Work');
                    $skit_hww_text = kg_get_field('skit_hww_text', 'Our work structure is uniquely tailored to a process that involves accountability, transparency and drive from all our teams. The Kings practices the flexibility of continuously adapting to changes and trends in the industry focusing on the delivering of quality product for our client\'s satisfaction. We make sure we deliver on-time, with the best quality, right at your fingertips.');
                    ?>
                    <h3 style="font-size: 2.5rem; margin-bottom: 1.5rem;"><?php echo esc_html( $skit_hww_title ); ?></h3>
                    <p style="font-size: 1.1rem; line-height: 1.8; color: var(--text-muted); margin-bottom: 2rem;"><?php echo wp_kses_post( $skit_hww_text ); ?></p>
                </div>

                <div class="image-wrapper" style="box-shadow: var(--shadow-xl); border-radius: 24px; overflow: hidden;">
                    <?php $skit_hww_img = function_exists('get_field') ? get_field('skit_hww_img', get_queried_object_id()) : ''; echo kg_img($skit_hww_img, 'KIT Platform Workflow', 'image-placeholder', 'width: 100%; height: 100%; object-fit: cover;'); ?>
                </div>
            </div>

            <!-- Features Grid -->
            <?php
            $skit_feat_defaults = array(
                1 => array( 'title' => 'Time & Attendance Monitoring',  'desc' => 'Automated and accurate tracking of employee hours seamlessly integrated with your customized rules.' ),
                2 => array( 'title' => 'Payslip Generation',            'desc' => 'Instant online viewing and generation of detailed, fully compliant digital payslips for all team members.' ),
                3 => array( 'title' => 'Government Reports',            'desc' => 'Hassle-free automated handling of government-mandated remittances and regulatory reports.' ),
                4 => array( 'title' => 'Payroll & Tax Reporting',       'desc' => 'Access updated, comprehensive reports on payroll expenses and taxation at any time.' ),
                5 => array( 'title' => 'Labor Issue Processing',        'desc' => 'Dedicated support and systematic handling for all labor management related issues.' ),
                6 => array( 'title' => 'Employer Access Portal',        'desc' => 'Full transparency with dedicated employer access to view and audit employees\' time records instantly.' ),
            );
            $skit_feat_icon_styles = array(
                1 => 'background: rgba(0, 208, 156, 0.1); color: var(--sec-accent-green);',
                2 => 'background: rgba(255, 209, 102, 0.15); color: #E5A910;',
                3 => 'background: rgba(38, 196, 133, 0.1); color: var(--sec-accent-green);',
                4 => 'background: rgba(10, 37, 64, 0.05); color: var(--main-blue);',
                5 => 'background: rgba(229, 62, 62, 0.1); color: #E53E3E;',
                6 => 'background: rgba(0, 208, 156, 0.1); color: var(--sec-accent-green);',
            );
            $skit_feat_svgs = array(
                1 => '<circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline>',
                2 => '<rect x="2" y="4" width="20" height="16" rx="2" ry="2"></rect><line x1="2" y1="10" x2="22" y2="10"></line>',
                3 => '<path d="M14 2H6a2 2 0 0 0-2-2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline>',
                4 => '<line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line>',
                5 => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path>',
                6 => '<rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line>',
            );
            ?>
            <div class="features-grid"
                style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 2rem; margin-top: 4rem;">
                <?php for ( $i = 1; $i <= 6; $i++ ) :
                    $feat_title = function_exists('get_field') && get_field( 'skit_feat' . $i . '_title', get_queried_object_id() )
                        ? get_field( 'skit_feat' . $i . '_title', get_queried_object_id() )
                        : $skit_feat_defaults[ $i ]['title'];
                    $feat_desc = function_exists('get_field') && get_field( 'skit_feat' . $i . '_desc', get_queried_object_id() )
                        ? get_field( 'skit_feat' . $i . '_desc', get_queried_object_id() )
                        : $skit_feat_defaults[ $i ]['desc'];
                ?>
                <!-- Card <?php echo $i; ?> -->
                <div class="feature-card"
                    style="background: var(--bg-white); border: 1px solid var(--border-color); border-radius: 20px; padding: 2.5rem; transition: var(--transition); box-shadow: var(--shadow-sm);">
                    <div class="icon-box"
                        style="width: 60px; height: 60px; <?php echo esc_attr( $skit_feat_icon_styles[ $i ] ); ?> border-radius: 16px; display: flex; align-items: center; justify-content: center; margin-bottom: 1.5rem;">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <?php echo $skit_feat_svgs[ $i ]; ?>
                        </svg>
                    </div>
                    <h4 style="font-size: 1.25rem; color: var(--main-blue); margin-bottom: 1rem;"><?php echo esc_html( $feat_title ); ?></h4>
                    <p style="color: var(--text-muted); line-height: 1.6; margin: 0;"><?php echo wp_kses_post( $feat_desc ); ?></p>
                </div>
                <?php endfor; ?>
            </div>
        </section>

        <!-- Moving Forward -->
        <?php
        $skit_forward_title = kg_get_field('skit_forward_title', 'Moving Forward');
        $skit_forward_text = kg_get_field('skit_forward_text', 'The Kings has a smooth track record and an expert in the said industry for over 10 years. We will be glad to meet with you, personally or virtually, to clarify any concern and work on the engagement that fits your current and future requirements.');
        ?>
        <section class="section"
            style="padding: 4rem 2rem; margin-top: 4rem; text-align: center; background: rgba(10, 37, 64, 0.02); border-radius: 24px;">
            <div class="container animate-on-scroll" style="max-width: 800px; margin: 0 auto;">
                <h2 class="section-title" style="margin-bottom: 1.5rem; font-size: 2.25rem;"><?php echo esc_html( $skit_forward_title ); ?></h2>
                <p style="font-size: 1.15rem; color: var(--text-muted); line-height: 1.8; margin-bottom: 0;">
                    <?php echo wp_kses_post( $skit_forward_text ); ?>
                </p>
            </div>
        </section>
    </main>

<?php get_footer(); ?>



