<?php
/* Template Name: Service Labor */
?>
<?php
if ( ! defined( 'ABSPATH' ) ) {
    require_once 'functions.php';
}
$page_title       = 'Labor Management Services | Kings Group Cooperative';
$page_description = 'Managed labor services and staff leasing solutions to optimize your business operations and workforce productivity.';

// JSON-LD: Service schema — helps Google surface this in service-type rich results
$page_schema = [
    '@context'    => 'https://schema.org',
    '@type'       => 'Service',
    '@id'         => 'https://kingsgroup.com.ph/service-labor/#service',
    'name'        => 'Labor Management Services',
    'url'         => 'https://kingsgroup.com.ph/service-labor/',
    'description' => 'Managed labor services and staff leasing solutions to optimize your business operations and workforce productivity.',
    'provider'    => [ '@id' => 'https://kingsgroup.com.ph/#organization' ],
    'areaServed'  => [ '@type' => 'Country', 'name' => 'Philippines' ],
    'serviceType' => 'Labor Management',
    'category'    => 'Staffing & Recruitment',
    'breadcrumb'  => [
        '@type'           => 'BreadcrumbList',
        'itemListElement' => [
            [ '@type' => 'ListItem', 'position' => 1, 'name' => 'Home',                    'item' => 'https://kingsgroup.com.ph/' ],
            [ '@type' => 'ListItem', 'position' => 2, 'name' => 'Labor Management Services','item' => 'https://kingsgroup.com.ph/service-labor/' ],
        ],
    ],
];

$page_hero_bg = kg_get_field('slab_bg', 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=2000&q=80');
get_header();
?>

    <!-- Premium Animated Hero -->
    <?php
    $slab_headline = kg_get_field('slab_headline', 'Managed Services &<br>Offshore Staff Leasing');
    $slab_desc = kg_get_field('slab_desc', 'End-to-end workforce solutions powered by the Philippines\' leading worker-owned cooperative. From recruitment to payroll — we handle it all.');
    $slab_bg = kg_get_field('slab_bg', 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=2000&q=80');
    $slab_bg_style = !empty($slab_bg) ? "background-image: linear-gradient(rgba(10, 37, 64, 0.75), rgba(10, 37, 64, 0.85)), url('" . esc_url($slab_bg) . "');" : '';
    ?>
    <section class="labor-hero" style="<?php echo $slab_bg_style; ?>">
        <div class="hero-inner">
            <h1><?php echo wp_kses_post($slab_headline); ?></h1>
            <p class="hero-sub"><?php echo esc_html($slab_desc); ?></p>
        </div>
    </section>

    <!-- Main Content Area -->
    <main class="service-content container">
        <div class="service-intro animate-on-scroll">
            <div class="service-intro-text">
                <?php
                $slab_intro_title = kg_get_field('slab_intro_title', 'Empowering your business with Managed Services &amp); Offshore Staff Leasing.');
                $slab_intro_desc = kg_get_field('slab_intro_desc', 'Human resource management, consulting and benefits administration are crucial aspects of the business that The Kings can manage for you. Payroll and HR Experts from The Kings, who are familiar with the local laws and taxations will handle your employees so you can focus on the revenue- generating activities of your business. They can either be placed in your office or work from our own corporate offices in Parañaque City, Philippines.');
                ?>
                <h2 class="intro-lead"><?php echo esc_html($slab_intro_title); ?></h2>
                <p><?php echo esc_html($slab_intro_desc); ?></p>

                <div class="pills-container">
                    <?php
                    $slab_intro_pills_raw = kg_get_field('slab_intro_pills', "Recruitment & Deployment\nOrientation & Training\nTimekeeping & Payroll\nCompensation Programs\nLegal Processes");
                    $slab_intro_pills_lines = array_filter(array_map('trim', explode("\n", $slab_intro_pills_raw)));
                    foreach ($slab_intro_pills_lines as $pill_line) : ?>
                        <span class="pill-item"><span style="color:var(--sec-accent-green); margin-right: 0.5rem;">✓</span>
                            <?php echo esc_html($pill_line); ?></span>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="service-intro-image">
                <?php $slab_intro_img = kg_get_field('slab_intro_img', 'https://images.unsplash.com/photo-1600880292203-757bb62b4baf?auto=format&fit=crop&w=1200&q=80'); echo kg_img($slab_intro_img, 'Corporate team strategy meeting'); ?>
            </div>
        </div>

        <!-- Section A: Managed Services -->
        <section class="animate-on-scroll" id="managed-services" style="margin-bottom: 6rem;">
            <?php
            $slab_managed_title = kg_get_field('slab_managed_title', 'A. Managed Services');
            $slab_managed_desc = kg_get_field('slab_managed_desc', 'Focused on local companies in the Philippines, providing an innovative <strong>Manpower Service Cooperative</strong> model to maximize contractual worker productivity.');
            ?>
            <div class="comparison-header">
                <h2><?php echo esc_html($slab_managed_title); ?></h2>
                <p style="color: var(--text-muted); font-size: 1.15rem; max-width: 700px; margin: 0 auto;"><?php echo wp_kses_post($slab_managed_desc); ?></p>
            </div>

            <!-- 2x2 Feature Grid -->
            <?php
            $slab_feat1_title = kg_get_field('slab_feat1_title', 'Owner-Members');
            $slab_feat1_desc = kg_get_field('slab_feat1_desc', 'Owned by professionals and workers bound by a common aim of providing sustainable livelihood opportunities for each member.');
            $slab_feat2_title = kg_get_field('slab_feat2_title', 'Self-Employed Status');
            $slab_feat2_desc = kg_get_field('slab_feat2_desc', 'As owner-members, individuals are self-employed. We operate independently, meaning we are neither traditional employees nor employers.');
            $slab_feat3_title = kg_get_field('slab_feat3_title', 'Flexible Engagement');
            $slab_feat3_desc = kg_get_field('slab_feat3_desc', 'Keep our services as long as necessary without absorbing members as regular employees, breaking the tedious cycle of recruitment and termination.');
            $slab_feat4_title = kg_get_field('slab_feat4_title', 'Maximized Productivity');
            $slab_feat4_desc = kg_get_field('slab_feat4_desc', 'Increase productivity by up to 70% through continuous engagement of learned skills while bringing down administrative costs.');
            ?>
            <div class="feature-grid">
                <div class="feature-card">
                    <div class="feature-card-image">
                        <?php $sf1 = kg_get_field('slab_feat1_img', 'https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?auto=format&fit=crop&w=600&q=80'); echo kg_img($sf1, 'Team synergy'); ?>
                    </div>
                    <div class="feature-card-content">
                        <h4><?php echo esc_html($slab_feat1_title); ?></h4>
                        <p><?php echo esc_html($slab_feat1_desc); ?></p>
                    </div>
                </div>

                <div class="feature-card">
                    <div class="feature-card-image">
                        <?php $sf2 = kg_get_field('slab_feat2_img', 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?auto=format&fit=crop&w=600&q=80'); echo kg_img($sf2, 'Self employed workers'); ?>
                    </div>
                    <div class="feature-card-content">
                        <h4><?php echo esc_html($slab_feat2_title); ?></h4>
                        <p><?php echo esc_html($slab_feat2_desc); ?></p>
                    </div>
                </div>

                <div class="feature-card">
                    <div class="feature-card-image">
                        <?php $sf3 = kg_get_field('slab_feat3_img', 'https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=600&q=80'); echo kg_img($sf3, 'Flexible engagement tracking'); ?>
                    </div>
                    <div class="feature-card-content">
                        <h4><?php echo esc_html($slab_feat3_title); ?></h4>
                        <p><?php echo esc_html($slab_feat3_desc); ?></p>
                    </div>
                </div>

                <div class="feature-card">
                    <div class="feature-card-image">
                        <?php $sf4 = kg_get_field('slab_feat4_img', 'https://images.unsplash.com/photo-1593642632559-0c6d3fc62b89?auto=format&fit=crop&w=600&q=80'); echo kg_img($sf4, 'Analytics and productivity'); ?>
                    </div>
                    <div class="feature-card-content">
                        <h4><?php echo esc_html($slab_feat4_title); ?></h4>
                        <p><?php echo esc_html($slab_feat4_desc); ?></p>
                    </div>
                </div>
            </div>

            <!-- Split Content Section -->
            <?php
            $slab_manpower_title = kg_get_field('slab_manpower_title', 'Total Manpower Solutions');
            $slab_manpower_text = kg_get_field('slab_manpower_text', '<p>We are committed to providing total solutions to our clients\' manpower outsourcing concerns and our candidates\' successful entry.</p><p>We may absorb the client\'s existing people or look for your needed manpower force, freeing your company from the hassles of manpower attention and supervision. We move as you desire.</p><p>Our goal is to exceed expectations by offering outstanding services, increased flexibility, and greater value. Thriving on partnerships, The Kings has been serving with excellence and passion through the years.</p>');
            ?>
            <div class="split-content" style="margin-top: 5rem;">
                <div class="split-text">
                    <h2><?php echo esc_html($slab_manpower_title); ?></h2>
                    <?php echo wp_kses_post($slab_manpower_text); ?>
                </div>
                <div class="image-wrapper">
                    <?php $slab_mp_img = kg_get_field('slab_manpower_img', 'https://images.unsplash.com/photo-1542744173-8e7e53415bb0?auto=format&fit=crop&w=1200&q=80'); echo kg_img($slab_mp_img, 'Professional meeting', 'image-placeholder'); ?>
                </div>
            </div>

            <!-- Premium Comparison Table -->
            <div class="comparison-container">
                <div class="comparison-header">
                    <h2>The Cooperative Advantage</h2>
                    <p style="color: var(--text-muted);">Comparing Direct Hire, Agency, and The Kings model.</p>
                </div>

                <div class="comparison-table-wrapper">
                    <table class="comparison-table">
                        <thead>
                            <tr>
                                <th>Feature</th>
                                <th>Direct Hire</th>
                                <th>From Agency</th>
                                <th class="col-kings">From Kings</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Affiliation</td>
                                <td>Employee</td>
                                <td>Employee</td>
                                <td class="col-kings-td">Member</td>
                            </tr>
                            <tr>
                                <td>ER-EE Relationship</td>
                                <td><svg class="icon-check" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="3">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg></td>
                                <td><svg class="icon-check" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="3">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg></td>
                                <td class="col-kings-td"><svg class="icon-cross" width="20" height="20"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg></td>
                            </tr>
                            <tr>
                                <td>VAT / EWT</td>
                                <td><svg class="icon-check" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="3">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg></td>
                                <td><svg class="icon-check" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="3">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg></td>
                                <td class="col-kings-td"><svg class="icon-cross" width="20" height="20"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg></td>
                            </tr>
                            <tr>
                                <td>Separation Pay</td>
                                <td>Required</td>
                                <td>Required</td>
                                <td class="col-kings-td"><span
                                        style="color: var(--sec-accent-green); font-weight: 700;">Dividends /
                                        Refund</span></td>
                            </tr>
                            <tr>
                                <td>Future Labor Cases</td>
                                <td>Possible</td>
                                <td>Prone</td>
                                <td class="col-kings-td"><svg class="icon-check" width="20" height="20"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg> Minimal-None</td>
                            </tr>
                            <tr>
                                <td>Credit Loan Facility</td>
                                <td><svg class="icon-cross" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="3">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg></td>
                                <td><svg class="icon-cross" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="3">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg></td>
                                <td class="col-kings-td"><svg class="icon-check" width="20" height="20"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg></td>
                            </tr>
                            <tr>
                                <td>Savings Program</td>
                                <td><svg class="icon-cross" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="3">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg></td>
                                <td><svg class="icon-cross" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="3">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg></td>
                                <td class="col-kings-td"><svg class="icon-check" width="20" height="20"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg></td>
                            </tr>
                            <tr>
                                <td>Govt Benefits</td>
                                <td>Required</td>
                                <td><svg class="icon-dash" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="3">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg> Maybe</td>
                                <td class="col-kings-td"><svg class="icon-check" width="20" height="20"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg></td>
                            </tr>
                            <tr>
                                <td>Insurance / HMO</td>
                                <td><svg class="icon-dash" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="3">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg> Maybe</td>
                                <td><svg class="icon-cross" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="3">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg></td>
                                <td class="col-kings-td"><svg class="icon-check" width="20" height="20"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg></td>
                            </tr>
                            <tr>
                                <td>Culinary & Training</td>
                                <td><svg class="icon-cross" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="3">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg></td>
                                <td><svg class="icon-cross" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="3">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg></td>
                                <td class="col-kings-td"><svg class="icon-check" width="20" height="20"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- Section B: Managed Staff Leasing Services -->
        <section class="animate-on-scroll" id="managed-staff-leasing" style="margin-bottom: 6rem;">
            <?php
            $lease_title = kg_get_field('slab_lease_title', 'B. Managed Staff Leasing Services');
            $lease_desc = kg_get_field('slab_lease_desc', 'Primarily for our Offshore Clients in countries such as Australia and the United States of America.');
            ?>
            <div class="comparison-header">
                <h2><?php echo esc_html($lease_title); ?></h2>
                <p style="color: var(--text-muted); font-size: 1.15rem; max-width: 700px; margin: 0 auto;"><?php echo esc_html($lease_desc); ?></p>
            </div>

            <?php
            $slab_offshore_title = kg_get_field('slab_offshore_title', '1. How Does Offshore Staff Leasing Work?');
            $slab_offshore_text = kg_get_field('slab_offshore_text', '<p>Managed Staff Leasing is a business delivery where we take care of recruiting staff for your particular needs, providing equipment, and overseeing operations to ensure your leased staff deliver expected quality.</p><p><strong>It is your team.</strong> With offshore managed staff leasing, you have full control of your members without worrying about facilities, operations, or local regulations in the Philippines. We take care of everything on the ground while you focus on business processes and expanding your core business.</p>');
            ?>
            <div class="split-content" style="margin-top: 4rem;">
                <div class="split-text">
                    <h2><?php echo esc_html($slab_offshore_title); ?></h2>
                    <?php echo wp_kses_post($slab_offshore_text); ?>
                </div>
                <!-- Placeholder Image for Remote Teams -->
                <div class="image-wrapper">
                    <?php $slab_off_img = kg_get_field('slab_offshore_img', 'https://images.unsplash.com/photo-1553877522-43269d4ea984?auto=format&fit=crop&w=1200&q=80'); echo kg_img($slab_off_img, 'Remote offshore team', 'image-placeholder'); ?>
                </div>
            </div>

            <?php
            $slab_improve_title = kg_get_field('slab_improve_title', '2. Improving Your Manpower');
            $slab_improve_desc = kg_get_field('slab_improve_desc', 'We source, recruit, and onboard your offshore team in line with your business needs, handling all administrative requirements. This frees you to focus on income-generating activities.');
            $slab_check1_title = kg_get_field('slab_check1_title', 'Cost Efficient');
            $slab_check1_desc = kg_get_field('slab_check1_desc', 'Save overhead costs on equipment, space, and training for additional employees.');
            $slab_check2_title = kg_get_field('slab_check2_title', 'Complete Set Up');
            $slab_check2_desc = kg_get_field('slab_check2_desc', 'We provide your offshore team with space, facilities, equipment, and everything they need.');
            $slab_check3_title = kg_get_field('slab_check3_title', 'Extensive HR & Payroll');
            $slab_check3_desc = kg_get_field('slab_check3_desc', 'Optimize talent management and cost-saving measures while retaining full oversight.');
            ?>
            <div class="split-content reverse" style="margin-top: 5rem;">
                <div class="split-text">
                    <h2><?php echo esc_html($slab_improve_title); ?></h2>
                    <p><?php echo esc_html($slab_improve_desc); ?></p>

                    <ul style="list-style:none; padding:0; margin-top:2rem;">
                        <li style="margin-bottom: 1.5rem; display:flex; align-items:flex-start;">
                            <span style="color:var(--sec-accent-green); margin-right:1rem; margin-top:0.25rem;"><svg
                                    width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg></span>
                            <div><strong><?php echo esc_html($slab_check1_title); ?>:</strong> <?php echo esc_html($slab_check1_desc); ?></div>
                        </li>
                        <li style="margin-bottom: 1.5rem; display:flex; align-items:flex-start;">
                            <span style="color:var(--sec-accent-green); margin-right:1rem; margin-top:0.25rem;"><svg
                                    width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg></span>
                            <div><strong><?php echo esc_html($slab_check2_title); ?>:</strong> <?php echo esc_html($slab_check2_desc); ?></div>
                        </li>
                        <li style="display:flex; align-items:flex-start;">
                            <span style="color:var(--sec-accent-green); margin-right:1rem; margin-top:0.25rem;"><svg
                                    width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg></span>
                            <div><strong><?php echo esc_html($slab_check3_title); ?>:</strong> <?php echo esc_html($slab_check3_desc); ?></div>
                        </li>
                    </ul>
                </div>
                <!-- Placeholder Image for Office Setup -->
                <div class="image-wrapper">
                    <?php $slab_imp_img = kg_get_field('slab_improve_img', 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=1200&q=80'); echo kg_img($slab_imp_img, 'Modern office workspace', 'image-placeholder'); ?>
                </div>
            </div>

            <?php
            $slab_onboard_title = kg_get_field('slab_onboard_title', '3. The Onboarding Journey');
            $slab_onboard_desc = kg_get_field('slab_onboard_desc', 'Your team in the Philippines is legally employed and managed by The Kings, but they report directly to you. Here is how we get started.');
            ?>
            <div class="comparison-header" style="margin-top: 8rem;">
                <h2><?php echo esc_html($slab_onboard_title); ?></h2>
                <p style="color: var(--text-muted); max-width: 600px; margin: 0 auto;"><?php echo esc_html($slab_onboard_desc); ?></p>
            </div>

            <!-- Horizontal Timeline: Step 1-10 -->
            <div class="timeline-container">
                <!-- Row 1: Steps 1–5 -->
                <div class="timeline-row">
                    <div class="timeline-step">
                        <div class="timeline-dot">1</div>
                        <div class="timeline-content">
                            <h4>Inquiry</h4>
                            <p>Business Development</p>
                        </div>
                    </div>
                    <div class="timeline-step">
                        <div class="timeline-dot">2</div>
                        <div class="timeline-content">
                            <h4>Negotiation</h4>
                            <p>Business Development</p>
                        </div>
                    </div>
                    <div class="timeline-step">
                        <div class="timeline-dot">3</div>
                        <div class="timeline-content">
                            <h4>Contract Signing</h4>
                            <p>Business Development</p>
                        </div>
                    </div>
                    <div class="timeline-step">
                        <div class="timeline-dot">4</div>
                        <div class="timeline-content">
                            <h4>Alignment of Policies</h4>
                            <p>People &amp; Culture, Operations</p>
                        </div>
                    </div>
                    <div class="timeline-step">
                        <div class="timeline-dot">5</div>
                        <div class="timeline-content">
                            <h4>Manpower Requisition</h4>
                            <p>People &amp; Culture, Operations</p>
                        </div>
                    </div>
                </div>

                <!-- Row 2: Steps 6–10 -->
                <div class="timeline-row">
                    <div class="timeline-step">
                        <div class="timeline-dot">6</div>
                        <div class="timeline-content">
                            <h4>Verification of Hours</h4>
                            <p>Operations</p>
                        </div>
                    </div>
                    <div class="timeline-step">
                        <div class="timeline-dot">7</div>
                        <div class="timeline-content">
                            <h4>Payment for Services</h4>
                            <p>Accounting &amp; Finance</p>
                        </div>
                    </div>
                    <div class="timeline-step">
                        <div class="timeline-dot">8</div>
                        <div class="timeline-content">
                            <h4>Satisfaction Survey</h4>
                            <p>Audit &amp; Business Dev</p>
                        </div>
                    </div>
                    <div class="timeline-step">
                        <div class="timeline-dot">9</div>
                        <div class="timeline-content">
                            <h4>Offering Other Services</h4>
                            <p>Business Development</p>
                        </div>
                    </div>
                    <div class="timeline-step">
                        <div class="timeline-dot">10</div>
                        <div class="timeline-content">
                            <h4>Contract Renewal</h4>
                            <p>Business Dev &amp; People</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Comparison Table: BPO vs Kings vs Incorporation -->
            <div class="comparison-container" style="margin-top: 8rem;">
                <div class="comparison-header">
                    <h2>Difference against BPO & Incorporation</h2>
                    <p style="color: var(--text-muted);">See why Kings Managed Staff Leasing stands out.</p>
                </div>

                <div class="comparison-table-wrapper">
                    <table class="comparison-table">
                        <thead>
                            <tr>
                                <th>Feature</th>
                                <th>Outsourcing (BPO)</th>
                                <th>Incorporating</th>
                                <th class="col-kings">Kings Staff Leasing</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Start-up Costs</td>
                                <td><span style="color: var(--sec-accent-green); font-weight: 700;">Low</span></td>
                                <td><span style="color: var(--accent-red); font-weight: 700;">High</span></td>
                                <td class="col-kings-td"><span
                                        style="color: var(--sec-accent-green); font-weight: 700;">None</span></td>
                            </tr>
                            <tr>
                                <td>Longer Term Costs</td>
                                <td><span style="color: var(--accent-red); font-weight: 700;">High</span></td>
                                <td><span style="color: var(--text-gold-light); font-weight: 700;">Medium</span></td>
                                <td class="col-kings-td"><span
                                        style="color: var(--sec-accent-green); font-weight: 700;">Low</span></td>
                            </tr>
                            <tr>
                                <td>Philippine Expertise</td>
                                <td><svg class="icon-check" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="3">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg> High</td>
                                <td><svg class="icon-cross" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="3">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg> Low</td>
                                <td class="col-kings-td"><svg class="icon-check" width="20" height="20"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg> High</td>
                            </tr>
                            <tr>
                                <td>Process Expertise</td>
                                <td><svg class="icon-check" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="3">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg> High</td>
                                <td><svg class="icon-dash" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="3">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg> Varies</td>
                                <td class="col-kings-td"><svg class="icon-dash" width="20" height="20"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg> Varies</td>
                            </tr>
                            <tr>
                                <td>Operational Flexibility</td>
                                <td><svg class="icon-check" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="3">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg> High</td>
                                <td><svg class="icon-cross" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="3">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg> Low</td>
                                <td class="col-kings-td"><svg class="icon-check" width="20" height="20"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg> High</td>
                            </tr>
                            <tr>
                                <td>Operational Control</td>
                                <td><svg class="icon-cross" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="3">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg> Low</td>
                                <td><svg class="icon-check" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="3">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg> High</td>
                                <td class="col-kings-td"><svg class="icon-check" width="20" height="20"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg> High</td>
                            </tr>
                            <tr>
                                <td>Client Resistance</td>
                                <td><svg class="icon-cross" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="3">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg> High</td>
                                <td><svg class="icon-dash" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="3">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg> Medium</td>
                                <td class="col-kings-td"><svg class="icon-dash" width="20" height="20"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg> Medium</td>
                            </tr>
                            <tr>
                                <td>Employee Resistance</td>
                                <td><svg class="icon-cross" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="3">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg> High</td>
                                <td><svg class="icon-dash" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="3">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg> Medium</td>
                                <td class="col-kings-td"><svg class="icon-dash" width="20" height="20"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg> Medium</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

    </main>

<?php get_footer(); ?>



