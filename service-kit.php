<?php
/* Template Name: Service Kit */
?>
<?php
if (!defined('ABSPATH')) {
    require_once 'functions.php';
}
$page_title = 'HR & Payroll Management & KIT System | Kings Group Cooperative';
$page_description = 'Human resource management, consulting and benefits administration are crucial aspects of the business that The Kings can manage for you.';

// JSON-LD: Service schema for KIT (SoftwareApplication + Service hybrid)
$page_schema = [
    '@context' => 'https://schema.org',
    '@type' => 'Service',
    '@id' => 'https://kingsgroup.com.ph/service-kit/#service',
    'name' => 'HR & Payroll Management & Kings Information Technology (KIT)',
    'url' => 'https://kingsgroup.com.ph/service-kit/',
    'description' => 'Human resource management, consulting and benefits administration are crucial aspects of the business that The Kings can manage for you.',
    'provider' => ['@id' => 'https://kingsgroup.com.ph/#organization'],
    'areaServed' => ['@type' => 'Country', 'name' => 'Philippines'],
    'serviceType' => 'HR & Payroll Services',
    'category' => 'Human Resources & Payroll',
    'breadcrumb' => [
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => 'https://kingsgroup.com.ph/'],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'HR & Payroll (KIT)', 'item' => 'https://kingsgroup.com.ph/service-kit/'],
        ],
    ],
];

$page_hero_bg = kg_get_field('skit_bg', kg_asset('img/service-kit/hero-kit.png'));
get_header();
?>

<!-- Premium Animated Hero -->
<?php
$skit_headline = kg_get_field('skit_headline', 'HR & Kings Information Technology (KIT)');
$skit_desc = kg_get_field('skit_desc', 'Proprietary Kings Information Technology System');
$skit_bg = kg_get_field('skit_bg', kg_asset('img/service-kit/hero-kit.png'));
$skit_bg_style = !empty($skit_bg) ? "background-image: linear-gradient(rgba(10, 37, 64, 0.75), rgba(10, 37, 64, 0.85)), url('" . esc_url($skit_bg) . "');" : '';
?>
<section class="kit-hero" style="<?php echo $skit_bg_style; ?>">
    <div class="hero-inner">
        <h1><?php echo esc_html($skit_headline); ?></h1>
        <p class="hero-sub"><?php echo esc_html($skit_desc); ?></p>
    </div>
</section>

<!-- Main Content Area -->
<main class="service-content container">
    <!-- HR & Payroll Management Intro Section (Split Layout below Hero) -->
    <?php
    $skit_hr_title = kg_get_field('skit_hr_title', 'HR & Payroll Management');
    $skit_hr_desc = kg_get_field('skit_hr_desc', 'Human resource management, consulting and benefits administration are crucial aspects of the business that The Kings can manage for you. Payroll and HR Experts from The Kings, who are familiar with the local laws and taxations will handle your employees so you can focus on the revenue-generating activities of your business. They can either be placed in your office or work from our own corporate offices in Parañaque City.');
    $skit_hr_list = kg_get_field('skit_hr_list');
    ?>
    <div class="split-content animate-on-scroll" style="align-items: center; margin-bottom: 6rem; gap: 4rem;">
        <div class="split-text">
            <h2 style="margin-bottom: 1.5rem;"><?php echo esc_html($skit_hr_title); ?></h2>
            <div style="font-size: 1.1rem; color: var(--text-muted); line-height: 1.8; margin-bottom: 2rem;">
                <?php echo wp_kses_post(wpautop($skit_hr_desc)); ?>
            </div>
            
            <?php if (!empty($skit_hr_list)) : ?>
                <ul style="list-style: none; padding: 0; margin: 0; text-align: left;">
                    <?php
                    $hr_items = explode("\n", $skit_hr_list);
                    foreach ($hr_items as $item) :
                        $item = trim($item);
                        if (empty($item)) continue;
                        ?>
                        <li style="margin-bottom: 0.75rem; font-size: 1.05rem; color: var(--text-body); display: flex; align-items: center; gap: 0.75rem;">
                            <span style="color: var(--sec-accent-green); font-weight: bold;">✓</span> <?php echo esc_html($item); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else : ?>
                <ul style="list-style: none; padding: 0; margin: 0; text-align: left;">
                    <li style="margin-bottom: 0.75rem; font-size: 1.05rem; color: var(--text-body); display: flex; align-items: center; gap: 0.75rem;">
                        <span style="color: var(--sec-accent-green); font-weight: bold;">✓</span> Recruitment, Selection and Deployment
                    </li>
                    <li style="margin-bottom: 0.75rem; font-size: 1.05rem; color: var(--text-body); display: flex; align-items: center; gap: 0.75rem;">
                        <span style="color: var(--sec-accent-green); font-weight: bold;">✓</span> Orientation and Training
                    </li>
                    <li style="margin-bottom: 0.75rem; font-size: 1.05rem; color: var(--text-body); display: flex; align-items: center; gap: 0.75rem;">
                        <span style="color: var(--sec-accent-green); font-weight: bold;">✓</span> Timekeeping and Payroll
                    </li>
                    <li style="margin-bottom: 0.75rem; font-size: 1.05rem; color: var(--text-body); display: flex; align-items: center; gap: 0.75rem;">
                        <span style="color: var(--sec-accent-green); font-weight: bold;">✓</span> Compensation Programs
                    </li>
                    <li style="margin-bottom: 0.75rem; font-size: 1.05rem; color: var(--text-body); display: flex; align-items: center; gap: 0.75rem;">
                        <span style="color: var(--sec-accent-green); font-weight: bold;">✓</span> Management and Legal Processes
                    </li>
                </ul>
            <?php endif; ?>
        </div>
        <div class="image-wrapper" style="box-shadow: var(--shadow-xl); border-radius: 24px; overflow: hidden; aspect-ratio: 4/3; width: 100%;">
            <?php 
            $skit_hr_img = kg_get_field('skit_hr_img', kg_asset('img/service-labor/kings-labor-content1.JPG')); 
            echo kg_img($skit_hr_img, 'HR and Payroll Management Consulting', 'image-placeholder', 'width: 100%; height: 100%; object-fit: cover;'); 
            ?>
        </div>
    </div>


    <?php
    $skit_kit_title = kg_get_field('skit_kit_title', 'Kings Information Technology (KIT)');
    $skit_kit_label = kg_get_field('skit_kit_label', 'HR & Payroll System');
    $skit_kit_desc = kg_get_field('skit_kit_desc', "Kings Information Technology is a software the company aimed to create offering the best solution for the Philippines HR demands— Philippines has a great need for a localized software that is why KIT was born.\n\nOur goal is to help companies in the Philippines grow through our suite of backend solutions that address payroll, HR and recruitment challenges.");
    ?>
    <div class="service-intro animate-on-scroll" style="margin-bottom: 6rem;">
        <!-- Single Image Wrapper -->
        <div class="image-wrapper" style="box-shadow: var(--shadow-xl); border-radius: 24px; overflow: hidden; aspect-ratio: 4/3; width: 100%;">
            <?php 
            $skit_intro_img1 = kg_get_field('skit_intro_img1', kg_asset('img/service-kit/skit-intro-img1.png')); 
            echo kg_img($skit_intro_img1, 'KIT Platform HR Dashboard', 'image-placeholder', 'width: 100%; height: 100%; object-fit: cover;'); 
            ?>
        </div>

        <div class="service-intro-text">
            <h2 class="intro-lead" style="margin-bottom: 1.5rem;"><?php echo esc_html($skit_kit_title); ?></h2>
            <div style="line-height: 1.75;">
                <?php echo wp_kses_post(wpautop($skit_kit_desc)); ?>
            </div>
        </div>
    </div>

    <?php
    $skit_hww_title = kg_get_field('skit_hww_title', 'HOW WE WORK');
    $skit_hww_desc = kg_get_field('skit_hww_desc', 'Our work structure is uniquely tailored to a process that involves accountability, transparency and drive from all our teams. The Kings practices the flexibility of continuously adapting to changes and trends in the industry focusing on the delivering of quality product for our client’s satisfaction. We make sure we deliver on-time, with the best quality, right at your fingertips.');
    $skit_hww_list = kg_get_field('skit_hww_list');
    ?>
    <section class="animate-on-scroll" id="how-we-work" style="margin-bottom: 8rem;">
        <div class="split-content" style="margin-top: 5rem;">
            <div class="split-text">
                <h3 style="font-size: 2.5rem; margin-bottom: 1.5rem;"><?php echo esc_html($skit_hww_title); ?></h3>
                <div style="font-size: 1.1rem; line-height: 1.8; color: var(--text-muted); margin-bottom: 2rem;">
                    <?php echo wp_kses_post(wpautop($skit_hww_desc)); ?>
                </div>
                
                <?php if (!empty($skit_hww_list)) : ?>
                    <ul style="list-style: none; padding: 0; margin: 0; text-align: left;">
                        <?php
                        $hww_items = explode("\n", $skit_hww_list);
                        foreach ($hww_items as $item) :
                            $item = trim($item);
                            if (empty($item)) continue;
                            ?>
                            <li style="margin-bottom: 0.75rem; font-size: 1.05rem; color: var(--text-body); display: flex; align-items: center; gap: 0.75rem;">
                                <span style="color: var(--sec-accent-green); font-weight: bold;">✓</span> <?php echo esc_html($item); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else : ?>
                    <ul style="list-style: none; padding: 0; margin: 0; text-align: left;">
                        <li style="margin-bottom: 0.75rem; font-size: 1.05rem; color: var(--text-body); display: flex; align-items: center; gap: 0.75rem;">
                            <span style="color: var(--sec-accent-green); font-weight: bold;">✓</span> Time and Attendance Monitoring
                        </li>
                        <li style="margin-bottom: 0.75rem; font-size: 1.05rem; color: var(--text-body); display: flex; align-items: center; gap: 0.75rem;">
                            <span style="color: var(--sec-accent-green); font-weight: bold;">✓</span> Payslip Generation (Online Viewing)
                        </li>
                        <li style="margin-bottom: 0.75rem; font-size: 1.05rem; color: var(--text-body); display: flex; align-items: center; gap: 0.75rem;">
                            <span style="color: var(--sec-accent-green); font-weight: bold;">✓</span> Government mandated remittances and reports
                        </li>
                        <li style="margin-bottom: 0.75rem; font-size: 1.05rem; color: var(--text-body); display: flex; align-items: center; gap: 0.75rem;">
                            <span style="color: var(--sec-accent-green); font-weight: bold;">✓</span> Updated Report on Payroll and Tax
                        </li>
                        <li style="margin-bottom: 0.75rem; font-size: 1.05rem; color: var(--text-body); display: flex; align-items: center; gap: 0.75rem;">
                            <span style="color: var(--sec-accent-green); font-weight: bold;">✓</span> Handling Labor Management related issues
                        </li>
                        <li style="margin-bottom: 0.75rem; font-size: 1.05rem; color: var(--text-body); display: flex; align-items: center; gap: 0.75rem;">
                            <span style="color: var(--sec-accent-green); font-weight: bold;">✓</span> Employer access to Employees' Time record
                        </li>
                    </ul>
                <?php endif; ?>
            </div>

            <div class="image-wrapper" style="box-shadow: var(--shadow-xl); border-radius: 24px; overflow: hidden;">
                <?php $skit_hww_img = kg_get_field('skit_hww_img', kg_asset('img/service-kit/how-we-work.JPG'));
                echo kg_img($skit_hww_img, 'KIT Platform Workflow', 'image-placeholder', 'width: 100%; height: 100%; object-fit: cover;'); ?>
            </div>
        </div>
    </section>

    <!-- Moving Forward -->
    <?php
    $skit_mf_title = kg_get_field('skit_mf_title', 'Moving Forward');
    $skit_mf_desc = kg_get_field('skit_mf_desc', 'The Kings has a smooth track record and an expert in the said industry for over 10 years. We will be glad to meet with you, personally or virtually, to clarify any concern and work on the engagement that fits your current and future requirements.');
    ?>
    <section class="animate-on-scroll" style="margin-top: 6rem; margin-bottom: 4rem; text-align: center;">
        <div style="background: rgba(10, 37, 64, 0.02); border: 1px solid var(--border-color); border-radius: 24px; padding: 4.5rem 3rem; max-width: 900px; margin: 0 auto; box-shadow: var(--shadow-sm);">
            <h2 style="font-size: 2.25rem; font-family: var(--font-header); margin-bottom: 1.5rem; font-weight: 700; color: var(--main-blue);"><?php echo esc_html($skit_mf_title); ?></h2>
            <div style="font-size: 1.15rem; color: var(--text-muted); line-height: 1.8; margin-bottom: 0; max-width: 750px; margin-left: auto; margin-right: auto;">
                <?php echo wp_kses_post(wpautop($skit_mf_desc)); ?>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>