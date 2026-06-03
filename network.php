<?php
/* Template Name: Network */
?>
<?php
if (!defined('ABSPATH')) {
    require_once 'functions.php';
}
$page_title = 'Client Engagements | Kings Group Cooperative';
$page_description = 'See the global and local network of clients that trust Kings Group for their staffing and labor management needs.';

// JSON-LD: WebPage schema for the network/clients page
$page_schema = [
    '@context' => 'https://schema.org',
    '@type' => 'WebPage',
    '@id' => 'https://kingsgroup.com.ph/network/#webpage',
    'url' => 'https://kingsgroup.com.ph/network/',
    'name' => 'Client Engagements | Kings Group Cooperative',
    'description' => 'See the global and local network of clients that trust Kings Group for their staffing and labor management needs.',
    'isPartOf' => ['@id' => 'https://kingsgroup.com.ph/#website'],
    'breadcrumb' => [
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => 'https://kingsgroup.com.ph/'],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Client Network', 'item' => 'https://kingsgroup.com.ph/network/'],
        ],
    ],
];

$page_hero_bg = kg_get_field('net_bg', 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=2000&q=80');
get_header();
?>

<!-- Modern Premium Hero -->
<?php
$net_headline = kg_get_field('net_headline', 'Our Global Network');
$net_desc = kg_get_field('net_desc', 'Serving over 10,000 members and integrating with world-class clients across industries.');
$net_bg = kg_get_field('net_bg', 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=2000&q=80');
?>
<section class="page-hero"
    style="background-image: linear-gradient(rgba(10, 37, 64, 0.7), rgba(10, 37, 64, 0.7)), url('<?php echo esc_url($net_bg); ?>');">
    <div class="container text-center">
        <h1><?php echo wp_kses_post($net_headline); ?></h1>
        <p><?php echo esc_html($net_desc); ?></p>
    </div>
</section>

<!-- Stats Strip -->
<section class="nw-stats-strip">
    <div class="container">
        <div class="nw-stats-grid">
            <?php for ($i = 1; $i <= 4; $i++):
                $stat_num = function_exists('get_field') && get_field('net_s' . $i . '_num', get_queried_object_id()) ? get_field('net_s' . $i . '_num', get_queried_object_id()) : '';
                $stat_label = function_exists('get_field') && get_field('net_s' . $i . '_label', get_queried_object_id()) ? get_field('net_s' . $i . '_label', get_queried_object_id()) : '';
                if ($stat_num && $stat_label):
                    ?>
                    <div class="nw-stat-item">
                        <span class="nw-stat-num stats-number"
                            data-value="<?php echo esc_attr(str_replace(',', '', $stat_num)); ?>"><?php echo esc_html($stat_num); ?></span>
                        <span class="nw-stat-label"><?php echo esc_html($stat_label); ?></span>
                    </div>
                    <?php if ($i < 4): ?>
                        <div class="nw-stat-divider"></div><?php endif; ?>
                <?php
                endif;
            endfor;
            ?>
        </div>
    </div>
</section>

<!-- Client Engagements -->
<section class="section section-bg-light" id="engagements">
    <div class="container animate-on-scroll">
        <div class="nw-section-header">
            <h2 class="section-title">Industry Engagements</h2>
            <p class="section-subtitle">We place highly trained professionals across a vast spectrum of industries —
                bridging talent with global opportunity.</p>
        </div>

        <div class="nw-cards-grid">

            <!-- Professional -->
            <div class="nw-card">
                <div class="nw-card-image">
                    <img src="<?php echo kg_asset('img/jobs_professional.webp'); ?>" alt="Professional Services"
                        loading="lazy">
                    <div class="nw-card-overlay">
                        <span class="nw-card-category">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5">
                                <path d="M20 7H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z" />
                                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16" />
                            </svg>
                            Professional
                        </span>
                    </div>
                </div>
                <div class="nw-card-body">
                    <h3 class="nw-card-title">Professional Services</h3>
                    <p class="nw-card-desc">Certified experts across finance, healthcare, and technical disciplines
                        for high-responsibility roles.</p>
                    <div class="nw-role-tags">
                        <span class="nw-tag">Accountant</span>
                        <span class="nw-tag">Architect</span>
                        <span class="nw-tag">Auditor</span>
                        <span class="nw-tag">Engineer</span>
                        <span class="nw-tag">Dentist</span>
                        <span class="nw-tag">Nurse</span>
                        <span class="nw-tag">Pharmacist</span>
                        <span class="nw-tag">Therapist</span>
                    </div>
                </div>
            </div>

            <!-- Wellness -->
            <div class="nw-card">
                <div class="nw-card-image">
                    <img src="<?php echo kg_asset('img/jobs_wellness.webp'); ?>" alt="Wellness" loading="lazy">
                    <div class="nw-card-overlay">
                        <span class="nw-card-category">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5">
                                <path
                                    d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                            </svg>
                            Wellness
                        </span>
                    </div>
                </div>
                <div class="nw-card-body">
                    <h3 class="nw-card-title">Wellness & Beauty</h3>
                    <p class="nw-card-desc">Skilled practitioners delivering premium personal care and therapeutic
                        wellness experiences.</p>
                    <div class="nw-role-tags">
                        <span class="nw-tag">Therapist</span>
                        <span class="nw-tag">Beautician</span>
                        <span class="nw-tag">Nail Technician</span>
                        <span class="nw-tag">Manicurist</span>
                        <span class="nw-tag">Pedicurist</span>
                    </div>
                </div>
            </div>

            <!-- Medical -->
            <div class="nw-card">
                <div class="nw-card-image">
                    <img src="<?php echo kg_asset('img/jobs_medical.webp'); ?>" alt="Medical" loading="lazy">
                    <div class="nw-card-overlay">
                        <span class="nw-card-category">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                                <line x1="12" y1="8" x2="12" y2="16" />
                                <line x1="8" y1="12" x2="16" y2="12" />
                            </svg>
                            Medical
                        </span>
                    </div>
                </div>
                <div class="nw-card-body">
                    <h3 class="nw-card-title">Medical & Caregiving</h3>
                    <p class="nw-card-desc">Compassionate and trained medical support staff providing essential
                        day-to-day care.</p>
                    <div class="nw-role-tags">
                        <span class="nw-tag">Medical Assistant</span>
                        <span class="nw-tag">Care Giver</span>
                    </div>
                </div>
            </div>

            <!-- Business & Industry -->
            <div class="nw-card">
                <div class="nw-card-image">
                    <img src="<?php echo kg_asset('img/jobs_business.webp'); ?>" alt="Business and Industry"
                        loading="lazy">
                    <div class="nw-card-overlay">
                        <span class="nw-card-category">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5">
                                <rect x="2" y="7" width="20" height="14" rx="2" ry="2" />
                                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16" />
                            </svg>
                            Business
                        </span>
                    </div>
                </div>
                <div class="nw-card-body">
                    <h3 class="nw-card-title">Business & Industry</h3>
                    <p class="nw-card-desc">Operational backbone staff keeping businesses running smoothly with
                        precision and professionalism.</p>
                    <div class="nw-role-tags">
                        <span class="nw-tag">Clerk</span>
                        <span class="nw-tag">Customer Service Rep.</span>
                        <span class="nw-tag">Staff</span>
                        <span class="nw-tag">Supervisors</span>
                    </div>
                </div>
            </div>

            <!-- Food & Beverage -->
            <div class="nw-card">
                <div class="nw-card-image">
                    <img src="<?php echo kg_asset('img/jobs_food_beverage.webp'); ?>" alt="Food and Beverage"
                        loading="lazy">
                    <div class="nw-card-overlay">
                        <span class="nw-card-category">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5">
                                <path d="M18 8h1a4 4 0 0 1 0 8h-1" />
                                <path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z" />
                                <line x1="6" y1="1" x2="6" y2="4" />
                                <line x1="10" y1="1" x2="10" y2="4" />
                                <line x1="14" y1="1" x2="14" y2="4" />
                            </svg>
                            Hospitality
                        </span>
                    </div>
                </div>
                <div class="nw-card-body">
                    <h3 class="nw-card-title">Food & Beverage</h3>
                    <p class="nw-card-desc">Hospitality-trained talent for restaurants, hotels, and luxury dining
                        establishments worldwide.</p>
                    <div class="nw-role-tags">
                        <span class="nw-tag">Waiter</span>
                        <span class="nw-tag">Bartender</span>
                        <span class="nw-tag">Chef</span>
                        <span class="nw-tag">Kitchen Steward</span>
                        <span class="nw-tag">Butler</span>
                        <span class="nw-tag">Baker</span>
                    </div>
                </div>
            </div>

            <!-- Sales & Services -->
            <div class="nw-card">
                <div class="nw-card-image">
                    <img src="<?php echo kg_asset('img/jobs_sales_services.webp'); ?>" alt="Sales and Services"
                        loading="lazy">
                    <div class="nw-card-overlay">
                        <span class="nw-card-category">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5">
                                <polyline points="23 6 13.5 15.5 8.5 10.5 1 18" />
                                <polyline points="17 6 23 6 23 12" />
                            </svg>
                            Sales
                        </span>
                    </div>
                </div>
                <div class="nw-card-body">
                    <h3 class="nw-card-title">Sales & Services</h3>
                    <p class="nw-card-desc">Front-line service professionals driving customer satisfaction and sales
                        performance across retail and events.</p>
                    <div class="nw-role-tags">
                        <span class="nw-tag">Service Crew</span>
                        <span class="nw-tag">Cashier</span>
                        <span class="nw-tag">Promodiser</span>
                        <span class="nw-tag">Merchandiser</span>
                        <span class="nw-tag">Call Center Agent</span>
                        <span class="nw-tag">Housekeeping</span>
                        <span class="nw-tag">Event Organizer</span>
                    </div>
                </div>
            </div>

            <!-- Skilled / Construction -->
            <div class="nw-card nw-card--wide">
                <div class="nw-card-image">
                    <img src="<?php echo kg_asset('img/jobs_skilled_construction.webp'); ?>"
                        alt="Skilled and Construction" loading="lazy">
                    <div class="nw-card-overlay">
                        <span class="nw-card-category">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5">
                                <path
                                    d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z" />
                            </svg>
                            Skilled Trades
                        </span>
                    </div>
                </div>
                <div class="nw-card-body">
                    <h3 class="nw-card-title">Skilled Trades & Construction</h3>
                    <p class="nw-card-desc">Certified tradespeople and construction professionals delivering quality
                        workmanship on projects of every scale.</p>
                    <div class="nw-role-tags">
                        <span class="nw-tag">Carpenter</span>
                        <span class="nw-tag">Mason</span>
                        <span class="nw-tag">Welder</span>
                        <span class="nw-tag">Plumber</span>
                        <span class="nw-tag">Industrial Electrician</span>
                        <span class="nw-tag">Painter</span>
                        <span class="nw-tag">Safety Officer</span>
                        <span class="nw-tag">Driver</span>
                        <span class="nw-tag">Mechanic</span>
                        <span class="nw-tag">Production Worker</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- CTA Banner -->
<section class="nw-cta-section">
    <div class="container">
        <div class="nw-cta-inner">
            <div class="nw-cta-text">
                <h2>Ready to Build Your Team?</h2>
                <p>Connect with our workforce specialists and get a custom deployment plan tailored to your
                    business.</p>
            </div>
            <div class="nw-cta-actions">
                <a href="<?php echo esc_url(home_url('/quote/')); ?>" class="btn btn-gold">
                    Get a Quote
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14M12 5l7 7-7 7" />
                    </svg>
                </a>
                <a href="<?php echo esc_url(home_url('/our-jobs/')); ?>" class="btn btn-outline"
                    style="color: #fff; border-color: rgba(255,255,255,0.4);">
                    Explore Careers
                </a>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>