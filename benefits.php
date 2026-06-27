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

$page_hero_bg = kg_get_field('benefits_bg', 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=2000&q=80');
get_header();
?>

    <!-- Modern Premium Hero -->
    <?php
    $benefits_headline = kg_get_field('benefits_headline', 'Why Join Kings?');
    $benefits_desc = kg_get_field('benefits_desc', 'Experience a new standard of employment. At Kings Group, our cooperative model empowers members with comprehensive benefits, financial security, and shared success.');
    ?>
    <?php
    $benefits_bg = kg_get_field('benefits_bg', 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=2000&q=80');
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
            $benefits_list_title = kg_get_field('benefits_list_title', 'Our Member Benefits');
            $benefits_list_desc = kg_get_field('benefits_list_desc', 'Kings Group Cooperative offers exclusive benefits to ensure our members are truly empowered and cared for.');
            ?>
            <div style="text-align: center; max-width: 800px; margin: 0 auto; margin-bottom: 2rem;">
                <h2 class="section-title"><?php echo esc_html($benefits_list_title); ?></h2>
                <p class="section-subtitle"><?php echo esc_html($benefits_list_desc); ?></p>
            </div>

            <div class="engagements-grid benefits-grid"
                style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
                <?php
                $benefits_defaults = array(
                    1 => array( 
                        'title' => 'HMO with Insurance',    
                        'desc' => 'Comprehensive health coverage and life insurance for you and your dependents.', 
                        'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--sec-accent-green)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"></path></svg>' 
                    ),
                    2 => array( 
                        'title' => 'SSS',  
                        'desc' => 'Full SSS contribution coverage and compliant government remittances.', 
                        'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--sec-accent-green)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>' 
                    ),
                    3 => array( 
                        'title' => 'PhilHealth',  
                        'desc' => 'Full PhilHealth contribution coverage and compliant government remittances.', 
                        'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--sec-accent-green)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>' 
                    ),
                    4 => array( 
                        'title' => 'Pag-Ibig',       
                        'desc' => 'Full Pag-IBIG contribution coverage and compliant government remittances.', 
                        'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--sec-accent-green)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>' 
                    ),
                    5 => array( 
                        'title' => 'Tax Exemption',    
                        'desc' => 'Enjoy the financial advantage of tax exemptions available to cooperative members.', 
                        'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--sec-accent-green)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>' 
                    ),
                    6 => array( 
                        'title' => 'Associate Membership',    
                        'desc' => 'Join a community of empowered professionals with a real stake in the company.', 
                        'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--sec-accent-green)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>' 
                    ),
                    7 => array( 
                        'title' => 'Credit and Savings Programs',   
                        'desc' => 'Access our internal lending program and structured cooperative savings schemes.', 
                        'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--sec-accent-green)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2" ry="2"></rect><line x1="2" y1="10" x2="22" y2="10"></line></svg>' 
                    ),
                    8 => array( 
                        'title' => 'Extended HR Department',
                        'desc' => 'Dedicated support from our HR professionals who are always ready to assist you.', 
                        'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--sec-accent-green)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>' 
                    ),
                    9 => array( 
                        'title' => 'Access to Advanced Technology Tools',
                        'desc' => 'Work with premium hardware, enterprise software, and our proprietary KIT platform.', 
                        'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--sec-accent-green)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="4" width="16" height="16" rx="2" ry="2"></rect><rect x="9" y="9" width="6" height="6"></rect><line x1="9" y1="1" x2="9" y2="4"></line><line x1="15" y1="1" x2="15" y2="4"></line><line x1="9" y1="20" x2="9" y2="23"></line><line x1="15" y1="20" x2="15" y2="23"></line><line x1="20" y1="9" x2="23" y2="9"></line><line x1="20" y1="15" x2="23" y2="15"></line><line x1="1" y1="9" x2="4" y2="9"></line><line x1="1" y1="15" x2="4" y2="15"></line></svg>' 
                    ),
                    10 => array( 
                        'title' => 'Livelihood Support for their Families',
                        'desc' => 'Skill-building and sustainable livelihood programs for members and their dependents.', 
                        'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--sec-accent-green)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>' 
                    ),
                );
                for ( $i = 1; $i <= 10; $i++ ) :
                    $b_title = kg_get_field('benefits_b' . $i . '_title', $benefits_defaults[$i]['title']);
                    $b_desc  = kg_get_field('benefits_b' . $i . '_desc', $benefits_defaults[$i]['desc']);
                    $b_icon  = $benefits_defaults[$i]['icon'];
                ?>
                <!-- Benefit <?php echo $i; ?> -->
                <div class="engagement-card glass-benefit-card">
                    <div class="benefit-icon-badge"><?php echo $b_icon; ?></div>
                    <h3 style="display: flex; align-items: center; gap: 0.75rem;"><span
                            style="color: var(--neutral-yellow); font-size: 1.5rem;"><?php echo sprintf('%02d', $i); ?></span> <?php echo esc_html($b_title); ?></h3>
                    <p><?php echo esc_html($b_desc); ?></p>
                </div>
                <?php endfor; ?>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
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

<style>
    .glass-benefit-card {
        background: rgba(255, 255, 255, 0.45);
        backdrop-filter: blur(25px) saturate(180%);
        -webkit-backdrop-filter: blur(25px) saturate(180%);
        border: 1px solid rgba(10, 37, 64, 0.06);
        border-radius: 20px;
        padding: 2.25rem;
        position: relative;
        overflow: hidden;
        will-change: transform;
        transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1),
                    box-shadow 0.4s cubic-bezier(0.16, 1, 0.3, 1),
                    border-color 0.4s ease;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .glass-benefit-card::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 209, 102, 0.08) 0%, transparent 60%);
        z-index: 0;
        pointer-events: none;
        transition: opacity 0.4s ease;
        opacity: 0;
    }

    .glass-benefit-card:hover {
        transform: translateY(-6px) scale(1.015);
        border-color: rgba(255, 209, 102, 0.45);
        box-shadow: 0 20px 45px rgba(10, 37, 64, 0.06), 0 0 30px rgba(255, 209, 102, 0.15);
    }
    
    .glass-benefit-card:hover::before {
        opacity: 1;
    }

    .glass-benefit-card h3 {
        margin: 0;
        font-family: var(--font-header);
        font-weight: 800;
        font-size: 1.2rem;
        color: var(--main-blue);
        position: relative;
        z-index: 1;
    }

    .glass-benefit-card p {
        margin: 0;
        font-size: 0.95rem;
        color: var(--text-body);
        line-height: 1.6;
        position: relative;
        z-index: 1;
    }

    .benefit-icon-badge {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        background: rgba(10, 37, 64, 0.03);
        border: 1px solid rgba(10, 37, 64, 0.06);
        font-size: 1.4rem;
        margin-bottom: 0.25rem;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        z-index: 1;
    }
    
    .glass-benefit-card:hover .benefit-icon-badge {
        background: #ffd166;
        color: #0a2540;
        border-color: #ffd166;
        transform: scale(1.1) rotate(-6deg);
        box-shadow: 0 8px 20px rgba(255, 209, 102, 0.45);
    }
</style>

<?php get_footer(); ?>


