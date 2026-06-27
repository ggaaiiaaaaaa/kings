<?php
/* Template Name: Story */
?>
<?php
if (!defined('ABSPATH')) {
    require_once 'functions.php';
}
$page_title = 'Our Story | Kings Group Cooperative';
$page_description = 'Discover the legacy of Kings Group Cooperative, empowering workers and delivering ethical staffing solutions since 1999.';

// JSON-LD: AboutPage schema
$page_schema = [
    '@context' => 'https://schema.org',
    '@type' => 'AboutPage',
    '@id' => 'https://kingsgroup.com.ph/story/#webpage',
    'url' => 'https://kingsgroup.com.ph/story/',
    'name' => 'Our Story | Kings Group Cooperative',
    'description' => 'Discover the legacy of Kings Group Cooperative, empowering workers and delivering ethical staffing solutions since 1999.',
    'isPartOf' => ['@id' => 'https://kingsgroup.com.ph/#website'],
    'about' => ['@id' => 'https://kingsgroup.com.ph/#organization'],
    'breadcrumb' => [
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => 'https://kingsgroup.com.ph/'],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Our Story', 'item' => 'https://kingsgroup.com.ph/story/'],
        ],
    ],
];

$page_hero_bg = kg_get_field('story_bg', kg_asset('img/story/hero-story.png'));
get_header();
?>

<!-- Hero Section with Lightbox Video Player -->
<?php
$story_headline = kg_get_field('story_headline', 'Our Story');
$story_desc = kg_get_field('story_desc', 'A legacy formed on ethical practices, worker empowerment, and shared success since 1999.');
$story_bg = kg_get_field('story_bg', kg_asset('img/story/hero-story.png'));
$hero_video_url = kg_get_field('story_hero_video', 'https://vimeo.com/1197690853');

$hero_embed_url = '';
if ($hero_video_url) {
    if (preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $hero_video_url, $m)) {
        $hero_embed_url = 'https://www.youtube.com/embed/' . $m[1] . '?rel=0&autoplay=1&modestbranding=1';
    } elseif (preg_match('/vimeo\.com\/(\d+)/', $hero_video_url, $m)) {
        $hero_embed_url = 'https://player.vimeo.com/video/' . $m[1] . '?autoplay=1&dnt=1&title=0&byline=0&portrait=0';
    }
}
?>
<section class="story-hero-section"
    style="background-image: linear-gradient(rgba(10, 37, 64, 0.75), rgba(10, 37, 64, 0.75)), url('<?php echo esc_url($story_bg); ?>');">
    <div class="container">
        <div class="story-hero-grid">
            <!-- Left Side: Editorial Typography -->
            <div class="story-hero-text animate-on-scroll">
                <span class="story-hero-tagline">Since 1999</span>
                <h1><?php echo esc_html($story_headline); ?></h1>
                <p><?php echo esc_html($story_desc); ?></p>
            </div>

            <!-- Right Side: Pulsing Video Cover Card -->
            <div class="story-hero-video-wrapper animate-on-scroll">
                <?php if ($hero_embed_url): ?>
                    <div class="story-video-cover-card" id="story-video-trigger"
                        data-video-url="<?php echo esc_url($hero_embed_url); ?>">
                        <div class="story-video-cover-card__img"
                            style="background-image: url('<?php echo esc_url($story_bg); ?>');"></div>
                        <div class="story-video-cover-card__overlay"></div>
                        <div class="story-video-cover-card__btn" aria-label="Play Video Link">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M8 5v14l11-7z" />
                            </svg>
                            <span class="pulse-ring"></span>
                        </div>
                    </div>
                    <div class="story-video-inline-container"
                        style="display: none; position: relative; border-radius: 28px; aspect-ratio: 16/9; overflow: hidden; box-shadow: var(--shadow-xl); border: 1px solid rgba(255, 255, 255, 0.15);">
                        <iframe id="story-inline-iframe" src="" allow="autoplay; fullscreen" allowfullscreen
                            style="width: 100%; height: 100%; border: none;"></iframe>
                        <div class="story-video-overlay-ctrl" id="story-video-overlay-ctrl"
                            style="position: absolute; inset: 0; cursor: pointer; z-index: 5;">
                            <div class="story-video-cover-card__btn" aria-label="Play/Pause Video">
                                <svg class="play-icon" width="28" height="28" viewBox="0 0 24 24" fill="currentColor"
                                    style="display: none;">
                                    <path d="M8 5v14l11-7z" />
                                </svg>
                                <svg class="pause-icon" width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z" />
                                </svg>
                                <span class="pulse-ring"></span>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="roots-video-placeholder">
                        <div class="roots-video-placeholder__inner">
                            <p>Add your hero video URL in<br><strong>WordPress Admin → Our Story</strong></p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php if ($hero_embed_url): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var trigger = document.getElementById('story-video-trigger');
            var inlineContainer = document.querySelector('.story-video-inline-container');
            var iframe = document.getElementById('story-inline-iframe');
            var overlayCtrl = document.getElementById('story-video-overlay-ctrl');
            var playIcon = document.querySelector('.play-icon');
            var pauseIcon = document.querySelector('.pause-icon');
            var isPlaying = true;

            if (trigger && inlineContainer && iframe) {
                trigger.addEventListener('click', function () {
                    var videoUrl = trigger.getAttribute('data-video-url');
                    if (videoUrl) {
                        var separator = videoUrl.indexOf('?') !== -1 ? '&' : '?';
                        var finalUrl = videoUrl;

                        // Add autoplay
                        if (videoUrl.indexOf('autoplay=') === -1) {
                            finalUrl = finalUrl + separator + 'autoplay=1';
                            separator = '&';
                        }

                        // Hide Vimeo UI elements/icons (badge, byline, portrait, title, controls)
                        if (videoUrl.indexOf('badge=') === -1) {
                            finalUrl = finalUrl + separator + 'badge=0&byline=0&portrait=0&title=0&controls=0';
                            separator = '&';
                        }

                        // Enable JS API for control
                        if (videoUrl.indexOf('api=') === -1) {
                            finalUrl = finalUrl + separator + 'api=1';
                        }

                        iframe.src = finalUrl;
                        trigger.style.display = 'none';
                        inlineContainer.style.display = 'block';
                        isPlaying = true;

                        // Reset icon state to playing
                        if (playIcon && pauseIcon) {
                            playIcon.style.display = 'none';
                            pauseIcon.style.display = 'block';
                        }
                    }
                });

                if (overlayCtrl && playIcon && pauseIcon) {
                    overlayCtrl.addEventListener('click', function () {
                        if (isPlaying) {
                            iframe.contentWindow.postMessage(JSON.stringify({ method: 'pause' }), '*');
                            playIcon.style.display = 'block';
                            pauseIcon.style.display = 'none';
                            isPlaying = false;
                        } else {
                            iframe.contentWindow.postMessage(JSON.stringify({ method: 'play' }), '*');
                            playIcon.style.display = 'none';
                            pauseIcon.style.display = 'block';
                            isPlaying = true;
                        }
                    });
                }
            }
        });
    </script>
<?php endif; ?>

<!-- ═══════════════════════════════════════
         VISION & MISSION — dark/glass split
         ═══════════════════════════════════════ -->
<?php
$vision_title = kg_get_field('story_vision_title', 'Vision & Mission');
$vision_text = kg_get_field('story_vision_text', "We stand as a leader in professional workforce solutions, honoring the\nconfidence our clients and\nmembers place in us every day. Our commitment is simple: to protect the integrity of our\nclients and\nensure that their policies are upheld with consistency, respect, and excellence.\nAt Kings Manpower,\neveryday is an opportunity to build upon our collective efforts—to make clients\nfeel more secure, leaders feel more confident, and people feel more at ease. In every decision we\nmake, we work toward brighter tomorrows—for companies,\nfor communities, and for every Filipino we\nserve.");
$mission_text_1 = kg_get_field('story_mission_text', "Success takes shape in the light of the right people—those who show up with skill, heart, and\npurpose. At Kings Manpower, we nurture this spirit in every Kings Scout. Through sustainable work,\nongoing training, and meaningful pathways for growth, we empower our members to rise. And in turn, we give businesses a workforce they can rely on from the first light of every new day to the next.");
$mission_text_2 = kg_get_field('story_mission_text_2', '');
?>
<section class="vm-section" id="vision-mission">
    <div class="container">
        <div class="vm-editorial-layout">
            <!-- Left Side: Mission (Empowering People) -->
            <div class="vm-editorial-block vm-editorial-block--mission animate-on-scroll">
                <h2 class="vm-editorial-heading">
                    <?php echo esc_html(kg_get_field('story_mission_heading', 'Mission')); ?>
                </h2>
                <div class="vm-editorial-content">
                    <p class="vm-main-paragraph"><?php echo nl2br(esc_html($mission_text_1)); ?></p>
                </div>
            </div>

            <!-- Divider Line -->
            <div class="vm-editorial-divider" aria-hidden="true"></div>

            <!-- Right Side: Vision (Securing Partnerships) -->
            <div class="vm-editorial-block vm-editorial-block--vision animate-on-scroll">
                <h2 class="vm-editorial-heading"><?php echo esc_html(kg_get_field('story_vision_heading', 'Vision')); ?>
                </h2>
                <div class="vm-editorial-content">
                    <p class="vm-main-paragraph"><?php echo nl2br(esc_html($vision_text)); ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════
         CORE VALUES (SCOUT) — premium list layout
         ═══════════════════════════════════════ -->
<?php
$values_title = kg_get_field('story_values_title', 'Core Values');
$values_intro = kg_get_field('story_values_intro', 'SCOUT');

$values_defaults = array(
    1 => array(
        'title' => 'STRENGTH IN SERVICE',
        'desc' => 'We are member-centric, putting our members and their families first while serving clients and communities with respect, courtesy, and efficiency.',
    ),
    2 => array(
        'title' => 'COMMITMENT',
        'desc' => 'We lead with ownership and embrace accountability in all that we do, taking responsibility with integrity, dedication, and care to honor the trust placed in us.',
    ),
    3 => array(
        'title' => 'OUTSTANDING VALUE & INNOVATION',
        'desc' => 'We deliver meaningful results by bringing together the best people, proven practices, and innovative ideas—always improving to create smart, future-ready solutions.',
    ),
    4 => array(
        'title' => 'UNITED IN EXCELLENCE',
        'desc' => 'Success is a collective effort. By working hand-in-hand with our clients, we combine effort and insight, ensuring alignment that delivers extraordinary results and creates exceptional experiences.',
    ),
    5 => array(
        'title' => 'TRUTH & TRUST',
        'desc' => 'Our actions are rooted in honesty, guided by transparency, and strengthened by reliability.',
    ),
);

$values_data = array();
for ($i = 1; $i <= 5; $i++) {
    $title = kg_get_field('story_v' . $i . '_title', $values_defaults[$i]['title']);
    $desc = kg_get_field('story_v' . $i . '_desc', $values_defaults[$i]['desc']);

    // Highlight the first letter dynamically for the SCOUT acronym
    $first_letter = mb_substr($title, 0, 1);
    $rest_of_title = mb_substr($title, 1);

    $values_data[$i] = array(
        'first_letter' => $first_letter,
        'rest_of_title' => $rest_of_title,
        'desc' => $desc
    );
}
?>
<section class="values-scout-section" id="values">
    <div class="container animate-on-scroll">
        <div class="values-scout-header">
            <h2 class="values-scout-title"><?php echo esc_html($values_title); ?></h2>
            <span class="values-scout-acronym"><?php echo esc_html($values_intro); ?></span>
        </div>

        <div class="values-scout-list">
            <?php foreach ($values_data as $value): ?>
                <div class="values-scout-item">
                    <div class="values-scout-item__title">
                        <span
                            class="values-scout-item__highlight"><?php echo esc_html($value['first_letter']); ?></span><?php echo esc_html($value['rest_of_title']); ?>
                    </div>
                    <div class="values-scout-item__desc">
                        <p><?php echo esc_html($value['desc']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════
         OUR ROOTS — serpentine timeline grid
         ═══════════════════════════════════════ -->
<?php
$timeline_title = kg_get_field('story_timeline_title', 'Our Roots');
$timeline_intro = kg_get_field('story_timeline_intro', 'Since 1999, Kings has been redefining the staffing industry.');

$timeline_defaults = array(
    1 => array('year' => '1999', 'title' => 'KINGS Lending Corporation', 'desc' => 'Founded in Zamboanga City, providing microfinancing services to hardworking Filipinos and laying the foundation for what would become a nationwide cooperative.'),
    2 => array('year' => '2009', 'title' => 'KINGS Manpower Established', 'desc' => 'Established in Zamboanga City to provide professional manpower services to different industries across the region.'),
    3 => array('year' => '2011', 'title' => 'Metro Manila Expansion', 'desc' => 'KINGS Manpower expanded to Metro Manila, establishing offices in Parañaque, Manila, Makati and BGC.'),
    4 => array('year' => '2016', 'title' => 'Home Culinary & Technical School', 'desc' => 'Established to offer livelihood and educational programs to Kings Members. Subsequently accredited by TESDA as a Training and Assessment Center.'),
    5 => array('year' => '2017', 'title' => 'Visayas Expansion', 'desc' => 'KINGS Manpower expanded its reach into the Visayas region, strengthening the cooperative\'s nationwide presence.'),
    6 => array('year' => '2019', 'title' => 'KINGS Headquarters', 'desc' => 'Reached the 10,000 member mark and expanded services to include serviced offices, meeting rooms and private offices.'),
    7 => array('year' => '2020', 'title' => 'KINGS City', 'desc' => 'Launched an events place, bigger meeting rooms, coworking space and The Culinary Boutique – an internship hub for our students.'),
);

$timeline_nodes = array();
for ($i = 1; $i <= 7; $i++) {
    $year = kg_get_field('story_t' . $i . '_year', $timeline_defaults[$i]['year']);
    $title = kg_get_field('story_t' . $i . '_title', $timeline_defaults[$i]['title']);
    $desc = kg_get_field('story_t' . $i . '_desc', $timeline_defaults[$i]['desc']);
    if (!empty($year) || !empty($title)) {
        $timeline_nodes[] = array(
            'year' => $year,
            'title' => $title,
            'desc' => $desc
        );
    }
}
?>
<section class="roots-timeline-section" id="history">
    <div class="roots-timeline-section__bg" aria-hidden="true"></div>
    <div class="container">
        <div class="roots-timeline-section__header animate-on-scroll">
            <h2 class="timeline-section-title"><?php echo esc_html($timeline_title); ?></h2>
            <p class="timeline-section-subtitle"><?php echo esc_html($timeline_intro); ?></p>
        </div>

        <!-- Serpentine Snake Timeline Grid -->
        <div class="snake-timeline-wrapper animate-on-scroll">
            <div class="snake-timeline-grid">
                <?php
                $total_nodes = count($timeline_nodes);
                $snake_positions = array(
                    0 => array('row' => 1, 'col' => 1), // 1999
                    1 => array('row' => 1, 'col' => 2), // 2009
                    2 => array('row' => 1, 'col' => 3), // 2011
                    3 => array('row' => 2, 'col' => 3), // 2016
                    4 => array('row' => 2, 'col' => 2), // 2017
                    5 => array('row' => 2, 'col' => 1), // 2019
                    6 => array('row' => 3, 'col' => 1), // 2020
                );
                foreach ($timeline_nodes as $index => $node):
                    $pos = isset($snake_positions[$index]) ? $snake_positions[$index] : array('row' => floor($index / 3) + 1, 'col' => ($index % 3) + 1);
                    $row = $pos['row'];
                    $col = $pos['col'];
                    $is_last = ($index === $total_nodes - 1);
                    $node_classes = "snake-node-card snake-node-row-{$row} snake-node-col-{$col}";
                    if ($is_last) {
                        $node_classes .= " is-last-node";
                    }
                    ?>
                    <div class="<?php echo esc_attr($node_classes); ?>">
                        <div class="snake-node-header">
                            <div class="snake-node-dot"></div>
                        </div>
                        <div class="snake-node-year"><?php echo esc_html($node['year']); ?></div>
                        <h3 class="snake-node-title"><?php echo esc_html($node['title']); ?></h3>
                        <p class="snake-node-desc"><?php echo esc_html($node['desc']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- Bottom Glassmorphic CTA Section -->
<?php
$is_ph = (kg_get_user_geo() === 'PH');

if ($is_ph) {
    $cta_title = kg_get_field('story_cta_title_ph', 'Your Trusted Provider for Manpower Solutions & Career Growth');
    $cta_btn1_text = kg_get_field('story_cta_btn1_ph', 'Inquire for Manpower Services');
    $cta_btn1_url = kg_get_field('story_cta_btn1_url_ph', home_url('/contact/'));
    $cta_btn2_text = kg_get_field('story_cta_btn2_ph', 'Explore Career Opportunities');
    $cta_btn2_url = kg_get_field('story_cta_btn2_url_ph', home_url('/careers/'));
    $cta_subtext = kg_get_field('story_cta_subtext_ph', 'Providing dependable local staffing services across industries while empowering 10,000+ member-owners nationwide with complete benefits and ethical opportunities.');
} else {
    $cta_title = kg_get_field('story_cta_title_intl', 'Scale Your Global Operations with Elite Philippine Talent');
    $cta_btn1_text = kg_get_field('story_cta_btn1_intl', 'Contact Staffing Experts');
    $cta_btn1_url = kg_get_field('story_cta_btn1_url_intl', home_url('/contact/'));
    $cta_btn2_text = kg_get_field('story_cta_btn2_intl', 'Request a Custom Quote');
    $cta_btn2_url = kg_get_field('story_cta_btn2_url_intl', home_url('/quote/'));
    $cta_subtext = kg_get_field('story_cta_subtext_intl', 'Partner with top-tier offshore teams under an ethical, worker-owned cooperative model. Rapid integration and full operational support in under 14 days.');
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