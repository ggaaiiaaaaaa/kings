<?php
/* Template Name: Benefits */
?>
<?php
if ( ! defined( 'ABSPATH' ) ) {
    require_once 'functions.php';
}
$page_title       = 'Member Benefits | Kings Group Cooperative';
$page_description = 'Explore the exclusive benefits of being a Kings Group Cooperative member, from profit sharing to insurance and career growth.';

// JSON-LD: WebPage schema for the membership benefits page
$page_schema = [
    '@context'    => 'https://schema.org',
    '@type'       => 'WebPage',
    '@id'         => 'https://kingsgroup.com.ph/benefits/#webpage',
    'url'         => 'https://kingsgroup.com.ph/benefits/',
    'name'        => 'Member Benefits | Kings Group Cooperative',
    'description' => 'Explore the exclusive benefits of being a Kings Group Cooperative member, from profit sharing to insurance and career growth.',
    'isPartOf'    => [ '@id' => 'https://kingsgroup.com.ph/#website' ],
    'breadcrumb'  => [
        '@type'           => 'BreadcrumbList',
        'itemListElement' => [
            [ '@type' => 'ListItem', 'position' => 1, 'name' => 'Home',           'item' => 'https://kingsgroup.com.ph/' ],
            [ '@type' => 'ListItem', 'position' => 2, 'name' => 'Member Benefits','item' => 'https://kingsgroup.com.ph/benefits/' ],
        ],
    ],
];

get_header();
?>

    <!-- Modern Premium Hero -->
    <?php
    $benefits_headline = kg_get_field('benefits_headline', 'Why Join Kings?');
    $benefits_desc = kg_get_field('benefits_desc', 'Experience a new standard of employment. At Kings Group, our cooperative model empowers members with comprehensive benefits, financial security, and shared success.');
    ?>
    <?php
    $benefits_bg = kg_get_field('benefits_bg', '');
    $benefits_bg_style = !empty($benefits_bg) ? "background-image: linear-gradient(rgba(10, 37, 64, 0.7), rgba(10, 37, 64, 0.7)), url('" . esc_url($benefits_bg) . "');" : '';
    ?>
    <section class="page-hero" style="<?php echo $benefits_bg_style; ?>">
        <div class="container" style="text-align: center;">
            <h1><?php echo wp_kses_post($benefits_headline); ?></h1>
            <p><?php echo esc_html($benefits_desc); ?></p>
        </div>
    </section>

    <!-- The "Lucky 9" Benefits Section -->
    <section class="section section-bg-light" id="lucky-9-benefits">
        <div class="container animate-on-scroll">
            <?php
            $benefits_list_title = kg_get_field('benefits_list_title', 'The "Lucky 9" Benefits');
            $benefits_list_desc = kg_get_field('benefits_list_desc', 'Kings Group Cooperative offers exclusive benefits to ensure our members are truly empowered and cared for.');
            ?>
            <div style="text-align: center; max-width: 800px; margin: 0 auto; margin-bottom: 2rem;">
                <h2 class="section-title"><?php echo esc_html($benefits_list_title); ?></h2>
                <p class="section-subtitle"><?php echo esc_html($benefits_list_desc); ?></p>
            </div>

            <div class="engagements-grid"
                style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
                <?php
                $benefits_defaults = array(
                    1 => array( 'title' => 'Tax exempt',            'desc' => 'Enjoy the financial advantage of tax exemptions as a cooperative member.' ),
                    2 => array( 'title' => 'Associate Membership',  'desc' => 'Join a community of empowered professionals with dedicated support.' ),
                    3 => array( 'title' => 'Credit Loan Facility',  'desc' => 'Access our internal lending program tailored to support your major life milestones.' ),
                    4 => array( 'title' => 'Savings Program',       'desc' => 'Secure your future with our structured savings programs.' ),
                    5 => array( 'title' => 'Mandatory Benefits',    'desc' => 'Full coverage for SSS, PhilHealth, and Pag-IBIG contributions.' ),
                    6 => array( 'title' => 'HMO with Insurance',    'desc' => 'Comprehensive health coverage to keep you and your family protected.' ),
                    7 => array( 'title' => 'Livelihood Programs',   'desc' => 'Skill-building via Home Culinary and Technical School programs.' ),
                    8 => array( 'title' => 'Extended HR Department','desc' => 'Dedicated support from our HR professionals who are always ready to assist you.' ),
                    9 => array( 'title' => 'Education and Training','desc' => 'Continuous learning opportunities to help you advance in your career.' ),
                );
                for ( $i = 1; $i <= 9; $i++ ) :
                    $b_title = function_exists('get_field') && get_field('benefits_b' . $i . '_title', get_queried_object_id())
                        ? get_field('benefits_b' . $i . '_title', get_queried_object_id())
                        : $benefits_defaults[$i]['title'];
                    $b_desc = function_exists('get_field') && get_field('benefits_b' . $i . '_desc', get_queried_object_id())
                        ? get_field('benefits_b' . $i . '_desc', get_queried_object_id())
                        : $benefits_defaults[$i]['desc'];
                ?>
                <!-- Benefit <?php echo $i; ?> -->
                <div class="engagement-card">
                    <h3 style="display: flex; align-items: center; gap: 0.75rem;"><span
                            style="color: var(--neutral-yellow); font-size: 1.5rem;"><?php echo sprintf('%02d', $i); ?></span> <?php echo esc_html($b_title); ?></h3>
                    <p><?php echo esc_html($b_desc); ?></p>
                </div>
                <?php endfor; ?>
            </div>
        </div>
    </section>

<?php get_footer(); ?>



