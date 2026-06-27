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

<!-- Client Engagements -->
<section class="section section-bg-light" id="engagements">
    <div class="container animate-on-scroll">
        <div class="nw-section-header">
            <h2 class="section-title"><?php echo esc_html(kg_get_field('net_engagements_title', 'Industry Engagements')); ?></h2>
            <p class="section-subtitle"><?php echo esc_html(kg_get_field('net_engagements_subtitle', 'We place highly trained professionals across a vast spectrum of industries — bridging talent with global opportunity.')); ?></p>
        </div>

        <div class="nw-cards-grid">
            <?php
            $card_svgs = [
                1 => '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 7H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z" /><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16" /></svg>',
                2 => '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" /></svg>',
                3 => '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2" /><line x1="12" y1="8" x2="12" y2="16" /><line x1="8" y1="12" x2="16" y2="12" /></svg>',
                4 => '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="2" y="7" width="20" height="14" rx="2" ry="2" /><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16" /></svg>',
                5 => '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 8h1a4 4 0 0 1 0 8h-1" /><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z" /><line x1="6" y1="1" x2="6" y2="4" /><line x1="10" y1="1" x2="10" y2="4" /><line x1="14" y1="1" x2="14" y2="4" /></svg>',
                6 => '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18" /><polyline points="17 6 23 6 23 12" /></svg>',
                7 => '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z" /></svg>'
            ];
            $fallback_svg = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M12 8v8M8 12h8"/></svg>';

            $fallback_cards = [
                1 => [
                    'cat' => 'Professional',
                    'title' => 'Professional Services',
                    'desc' => 'Certified experts across finance, healthcare, and technical disciplines for high-responsibility roles.',
                    'img' => 'img/jobs_professional.webp',
                    'tags' => "Accountant\nArchitect\nAuditor\nEngineer\nDentist\nNurse\nPharmacist\nTherapist"
                ],
                2 => [
                    'cat' => 'Wellness',
                    'title' => 'Wellness & Beauty',
                    'desc' => 'Skilled practitioners delivering premium personal care and therapeutic wellness experiences.',
                    'img' => 'img/jobs_wellness.webp',
                    'tags' => "Therapist\nBeautician\nNail Technician\nManicurist\nPedicurist"
                ],
                3 => [
                    'cat' => 'Medical',
                    'title' => 'Medical & Caregiving',
                    'desc' => 'Compassionate and trained medical support staff providing essential day-to-day care.',
                    'img' => 'img/jobs_medical.webp',
                    'tags' => "Medical Assistant\nCare Giver"
                ],
                4 => [
                    'cat' => 'Business',
                    'title' => 'Business & Industry',
                    'desc' => 'Operational backbone staff keeping businesses running smoothly with precision and professionalism.',
                    'img' => 'img/jobs_business.webp',
                    'tags' => "Clerk\nCustomer Service Rep.\nStaff\nSupervisors"
                ],
                5 => [
                    'cat' => 'Hospitality',
                    'title' => 'Food & Beverage',
                    'desc' => 'Hospitality-trained talent for restaurants, hotels, and luxury dining establishments worldwide.',
                    'img' => 'img/jobs_food_beverage.webp',
                    'tags' => "Waiter\nBartender\nChef\nKitchen Steward\nButler\nBaker"
                ],
                6 => [
                    'cat' => 'Sales',
                    'title' => 'Sales & Services',
                    'desc' => 'Front-line service professionals driving customer satisfaction and sales performance across retail and events.',
                    'img' => 'img/jobs_sales_services.webp',
                    'tags' => "Service Crew\nCashier\nPromodiser\nMerchandiser\nCall Center Agent\nHousekeeping\nEvent Organizer"
                ],
                7 => [
                    'cat' => 'Skilled Trades',
                    'title' => 'Skilled Trades & Construction',
                    'desc' => 'Certified tradespeople and construction professionals delivering quality workmanship on projects of every scale.',
                    'img' => 'img/jobs_skilled_construction.webp',
                    'tags' => "Carpenter\nMason\nWelder\nPlumber\nIndustrial Electrician\nPainter\nSafety Officer\nDriver\nMechanic\nProduction Worker"
                ]
            ];

            for ($i = 1; $i <= 7; $i++):
                $fallback = $fallback_cards[$i];
                $category = kg_get_field('net_card' . $i . '_category', $fallback['cat']);
                $title = kg_get_field('net_card' . $i . '_title', $fallback['title']);
                $img_val = kg_get_field('net_card' . $i . '_img');
                
                if (empty($img_val)) {
                    $img_url = kg_asset($fallback['img']);
                } else {
                    $img_url = $img_val;
                }
                
                $desc = kg_get_field('net_card' . $i . '_desc', $fallback['desc']);
                $tags_raw = kg_get_field('net_card' . $i . '_tags', $fallback['tags']);

                $tags = [];
                if (!empty($tags_raw)) {
                    if (strpos($tags_raw, "\n") !== false) {
                        $tags = explode("\n", $tags_raw);
                    } else {
                        $tags = explode(",", $tags_raw);
                    }
                    $tags = array_filter(array_map('trim', $tags));
                }

                $svg = isset($card_svgs[$i]) ? $card_svgs[$i] : $fallback_svg;
                $card_class = ($i === 7) ? 'nw-card nw-card--wide' : 'nw-card';
                ?>
                <div class="<?php echo esc_attr($card_class); ?>">
                    <div class="nw-card-image">
                        <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($title); ?>" loading="lazy">
                        <div class="nw-card-overlay">
                            <span class="nw-card-category">
                                <?php echo $svg; ?>
                                <?php echo esc_html($category); ?>
                            </span>
                        </div>
                    </div>
                    <div class="nw-card-body">
                        <h3 class="nw-card-title"><?php echo esc_html($title); ?></h3>
                        <p class="nw-card-desc"><?php echo esc_html($desc); ?></p>
                        <?php if (!empty($tags)): ?>
                            <div class="nw-role-tags">
                                <?php foreach ($tags as $tag): ?>
                                    <span class="nw-tag"><?php echo esc_html($tag); ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
    </div>
</section>

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