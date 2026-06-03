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
            $benefits_list_title = kg_get_field('benefits_list_title', 'The "Lucky 9" Benefits');
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
                        'title' => 'Tax exempt',            
                        'desc' => 'Enjoy the financial advantage of tax exemptions as a cooperative member.', 
                        'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--sec-accent-green)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>' 
                    ),
                    2 => array( 
                        'title' => 'Associate Membership',  
                        'desc' => 'Join a community of empowered professionals with dedicated support.', 
                        'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--sec-accent-green)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>' 
                    ),
                    3 => array( 
                        'title' => 'Credit Loan Facility',  
                        'desc' => 'Access our internal lending program tailored to support your major life milestones.', 
                        'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--sec-accent-green)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="22" width="20" height="2"></rect><path d="M4 22V10h16v12"></path><path d="M2 10l10-8 10 8"></path></svg>' 
                    ),
                    4 => array( 
                        'title' => 'Savings Program',       
                        'desc' => 'Secure your future with our structured savings programs.', 
                        'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--sec-accent-green)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><rect x="9" y="9" width="6" height="6" rx="1"></rect><line x1="12" y1="1" x2="12" y2="3"></line></svg>' 
                    ),
                    5 => array( 
                        'title' => 'Mandatory Benefits',    
                        'desc' => 'Full coverage for SSS, PhilHealth, and Pag-IBIG contributions.', 
                        'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--sec-accent-green)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path><polyline points="9 11 11 13 15 9"></polyline></svg>' 
                    ),
                    6 => array( 
                        'title' => 'HMO with Insurance',    
                        'desc' => 'Comprehensive health coverage to keep you and your family protected.', 
                        'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--sec-accent-green)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"></path></svg>' 
                    ),
                    7 => array( 
                        'title' => 'Livelihood Programs',   
                        'desc' => 'Skill-building via Home Culinary and Technical School programs.', 
                        'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--sec-accent-green)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"></path></svg>' 
                    ),
                    8 => array( 
                        'title' => 'Extended HR Department',
                        'desc' => 'Dedicated support from our HR professionals who are always ready to assist you.', 
                        'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--sec-accent-green)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>' 
                    ),
                    9 => array( 
                        'title' => 'Education and Training',
                        'desc' => 'Continuous learning opportunities to help you advance in your career.', 
                        'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--sec-accent-green)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>' 
                    ),
                );
                for ( $i = 1; $i <= 9; $i++ ) :
                    $b_title = function_exists('get_field') && get_field('benefits_b' . $i . '_title', get_queried_object_id())
                        ? get_field('benefits_b' . $i . '_title', get_queried_object_id())
                        : $benefits_defaults[$i]['title'];
                    $b_desc = function_exists('get_field') && get_field('benefits_b' . $i . '_desc', get_queried_object_id())
                        ? get_field('benefits_b' . $i . '_desc', get_queried_object_id())
                        : $benefits_defaults[$i]['desc'];
                    $b_icon = $benefits_defaults[$i]['icon'];
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
    <section class="section cta-bottom">
        <div class="container animate-on-scroll" style="text-align: center; max-width: 700px;">
            <h2 class="section-title" style="color: #ffffff;">Ready to Join the Cooperative?</h2>
            <p class="section-subtitle" style="margin-bottom: 2rem; color: rgba(255, 255, 255, 0.855);">Experience the difference of working with a company that invests in your future. Apply today and unlock all 9 benefits.</p>
            <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                <a href="<?php echo esc_url(home_url('/careers/')); ?>" class="btn btn-gold" style="padding: 1rem 2.5rem;">
                    Apply Now
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7" /></svg>
                </a>
                <a href="<?php echo esc_url(home_url('/our-jobs/')); ?>" class="btn btn-outline" style="border-color: rgba(255,255,255,0.3); color: white; background: rgba(255,255,255,0.1); backdrop-filter: blur(5px); padding: 1rem 2.5rem;">
                    Browse Jobs
                </a>
            </div>
        </div>
    </section>

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


