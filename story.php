<?php
/* Template Name: Story */
?>
<?php
if ( ! defined( 'ABSPATH' ) ) {
    require_once 'functions.php';
}
$page_title       = 'Our Story | Kings Group Cooperative';
$page_description = 'Discover the legacy of Kings Group Cooperative, empowering workers and delivering ethical staffing solutions since 1999.';

// JSON-LD: AboutPage schema
$page_schema = [
    '@context'    => 'https://schema.org',
    '@type'       => 'AboutPage',
    '@id'         => 'https://kingsgroup.com.ph/story/#webpage',
    'url'         => 'https://kingsgroup.com.ph/story/',
    'name'        => 'Our Story | Kings Group Cooperative',
    'description' => 'Discover the legacy of Kings Group Cooperative, empowering workers and delivering ethical staffing solutions since 1999.',
    'isPartOf'    => [ '@id' => 'https://kingsgroup.com.ph/#website' ],
    'about'       => [ '@id' => 'https://kingsgroup.com.ph/#organization' ],
    'breadcrumb'  => [
        '@type'           => 'BreadcrumbList',
        'itemListElement' => [
            [ '@type' => 'ListItem', 'position' => 1, 'name' => 'Home',     'item' => 'https://kingsgroup.com.ph/' ],
            [ '@type' => 'ListItem', 'position' => 2, 'name' => 'Our Story','item' => 'https://kingsgroup.com.ph/story/' ],
        ],
    ],
];

get_header();
?>

    <!-- Hero -->
    <?php
    $story_headline = kg_get_field('story_headline', 'Our Story');
    $story_desc     = kg_get_field('story_desc', 'A legacy formed on ethical practices, worker empowerment, and shared success since 1999.');
    $story_bg       = kg_get_field('story_bg', '');
    ?>
    <section class="page-hero story-bg"
        style="background-image: linear-gradient(rgba(10, 37, 64, 0.7), rgba(10, 37, 64, 0.7)), url('<?php echo esc_url($story_bg); ?>');">
        <div class="container text-center">
            <h1><?php echo esc_html($story_headline); ?></h1>
            <p><?php echo esc_html($story_desc); ?></p>
        </div>
    </section>

    <!-- ═══════════════════════════════════════
         VISION & MISSION — dark/glass split
         ═══════════════════════════════════════ -->
    <?php
    $vision_title   = kg_get_field('story_vision_title', 'Vision & Mission');
    $vision_text    = kg_get_field('story_vision_text', 'To be the leading professional workforce provider with satisfied clients and secured members in the Philippines.');
    $mission_text_1 = kg_get_field('story_mission_text', 'Driven with passion, agility, and excellence in everything we do, we are committed to maintain a successful partnership with our clients as we also help with the needs and aspirations of our members.');
    $mission_text_2 = kg_get_field('story_mission_text_2', 'Staying true to our purpose, it is our mission to be a responsible member of the Cooperative Development Authority in contributing to the growth of the economy by continuously providing jobs.');
    ?>
    <section class="vm-section" id="vision-mission">
        <div class="container">
            <div class="vm-header animate-on-scroll">
                <h2 class="section-title"><?php echo esc_html($vision_title); ?></h2>
                <p class="section-subtitle">The purpose that drives every decision we make.</p>
            </div>
            <div class="vm-grid animate-on-scroll">
                <!-- Vision — dark card -->
                <div class="vm-card vm-card--dark">
                    <div class="vm-card__deco-letter" aria-hidden="true">V</div>
                    <div class="vm-card__icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/>
                        </svg>
                    </div>
                    <h3 class="vm-card__heading">Our Vision</h3>
                    <p class="vm-card__text"><?php echo esc_html($vision_text); ?></p>
                </div>
                <!-- Mission — glass card -->
                <div class="vm-card vm-card--glass">
                    <div class="vm-card__deco-letter" aria-hidden="true">M</div>
                    <div class="vm-card__icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                        </svg>
                    </div>
                    <h3 class="vm-card__heading">Our Mission</h3>
                    <p class="vm-card__text"><?php echo esc_html($mission_text_1); ?></p>
                    <?php if ($mission_text_2) : ?>
                    <p class="vm-card__text" style="margin-top:1rem;"><?php echo esc_html($mission_text_2); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════
         CORE VALUES — accent-bar grid
         ═══════════════════════════════════════ -->
    <?php
    $values_title = kg_get_field('story_values_title', 'Core Values');
    $values_intro = kg_get_field('story_values_intro', 'The principles that guide everything we do at Kings.');

    $values_data = array(
        1 => array('title' => 'Member-Centric',  'desc' => 'Our members\' welfare is our top priority. We help improve the lives of our members by providing jobs that uplift their dignity.',             'color' => 'blue',
            'svg' => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path>'),
        2 => array('title' => 'Truth',           'desc' => 'We strive to promote and implement truthful and transparent transactions with our Clients and Co-Members.',                                     'color' => 'yellow',
            'svg' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>'),
        3 => array('title' => 'Quality',         'desc' => 'What we do, we do well. Delivering only the best products and exceptional services for you.',                                                  'color' => 'blue',
            'svg' => '<circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>'),
        4 => array('title' => 'Value',           'desc' => 'Developing systems to ensure the best possible return on investment for our members, clients and other stakeholders.',                        'color' => 'green',
            'svg' => '<line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>'),
        5 => array('title' => 'Integrity',       'desc' => 'Possessing and practicing fairness and objectivity and strong moral principles even in the most challenging situations.',                     'color' => 'blue',
            'svg' => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline>'),
        6 => array('title' => 'Excellence',      'desc' => 'Providing our clients with superb services and exceptional overall experience, exceeding expectations by working smart and hard.',            'color' => 'yellow',
            'svg' => '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>'),
        7 => array('title' => 'Innovation',      'desc' => 'Actively creative and open to all possibilities in meeting our clients\' unique needs.',                                                       'color' => 'green',
            'svg' => '<circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line>'),
        8 => array('title' => 'Accountability',  'desc' => 'Demonstrating dependability and personal ownership necessary in achieving desired results.',                                                   'color' => 'blue',
            'svg' => '<path d="M9 11l3 3L22 4"></path><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>'),
        9 => array('title' => 'Professionalism', 'desc' => 'Making sure that everyone who seeks our services is treated with respect, courtesy and efficiency.',                                          'color' => 'yellow',
            'svg' => '<circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="10" r="3"></circle><path d="M7 20.662V19a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v1.662"></path>'),
    );
    for ($i = 1; $i <= 9; $i++) {
        if (function_exists('get_field') && get_field('story_v'.$i.'_title', get_queried_object_id()))
            $values_data[$i]['title'] = get_field('story_v'.$i.'_title', get_queried_object_id());
        if (function_exists('get_field') && get_field('story_v'.$i.'_desc', get_queried_object_id()))
            $values_data[$i]['desc']  = get_field('story_v'.$i.'_desc', get_queried_object_id());
    }
    ?>
    <section class="section" id="values">
        <div class="container animate-on-scroll">
            <h2 class="section-title"><?php echo esc_html($values_title); ?></h2>
            <p class="section-subtitle"><?php echo esc_html($values_intro); ?></p>

            <div class="values-grid">
                <div class="value-feature">
                    <span class="value-feature-num">01</span>
                    <div class="value-feature-icon" style="background: rgba(10, 37, 64, 0.06); color: var(--main-blue);"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg></div>
                    <h3><?php echo esc_html($values_data[1]['title']); ?></h3>
                    <p><?php echo esc_html($values_data[1]['desc']); ?></p>
                </div>
                <div class="value-feature">
                    <span class="value-feature-num">02</span>
                    <div class="value-feature-icon" style="background: rgba(255, 209, 102, 0.15); color: var(--neutral-yellow);"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg></div>
                    <h3><?php echo esc_html($values_data[2]['title']); ?></h3>
                    <p><?php echo esc_html($values_data[2]['desc']); ?></p>
                </div>
                <div class="value-feature">
                    <span class="value-feature-num">03</span>
                    <div class="value-feature-icon" style="background: rgba(10, 37, 64, 0.06); color: var(--main-blue);"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline></svg></div>
                    <h3><?php echo esc_html($values_data[3]['title']); ?></h3>
                    <p><?php echo esc_html($values_data[3]['desc']); ?></p>
                </div>
                <div class="value-feature">
                    <span class="value-feature-num">04</span>
                    <div class="value-feature-icon" style="background: rgba(0, 208, 156, 0.1); color: var(--sec-accent-green);"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg></div>
                    <h3><?php echo esc_html($values_data[4]['title']); ?></h3>
                    <p><?php echo esc_html($values_data[4]['desc']); ?></p>
                </div>
                <div class="value-feature">
                    <span class="value-feature-num">05</span>
                    <div class="value-feature-icon" style="background: rgba(10, 37, 64, 0.06); color: var(--main-blue);"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg></div>
                    <h3><?php echo esc_html($values_data[5]['title']); ?></h3>
                    <p><?php echo esc_html($values_data[5]['desc']); ?></p>
                </div>
                <div class="value-feature">
                    <span class="value-feature-num">06</span>
                    <div class="value-feature-icon" style="background: rgba(255, 209, 102, 0.15); color: var(--neutral-yellow);"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg></div>
                    <h3><?php echo esc_html($values_data[6]['title']); ?></h3>
                    <p><?php echo esc_html($values_data[6]['desc']); ?></p>
                </div>
                <div class="value-feature">
                    <span class="value-feature-num">07</span>
                    <div class="value-feature-icon" style="background: rgba(0, 208, 156, 0.1); color: var(--sec-accent-green);"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg></div>
                    <h3><?php echo esc_html($values_data[7]['title']); ?></h3>
                    <p><?php echo esc_html($values_data[7]['desc']); ?></p>
                </div>
                <div class="value-feature">
                    <span class="value-feature-num">08</span>
                    <div class="value-feature-icon" style="background: rgba(10, 37, 64, 0.06); color: var(--main-blue);"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4"></path><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg></div>
                    <h3><?php echo esc_html($values_data[8]['title']); ?></h3>
                    <p><?php echo esc_html($values_data[8]['desc']); ?></p>
                </div>
                <div class="value-feature">
                    <span class="value-feature-num">09</span>
                    <div class="value-feature-icon" style="background: rgba(255, 209, 102, 0.15); color: var(--neutral-yellow);"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="10" r="3"></circle><path d="M7 20.662V19a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v1.662"></path></svg></div>
                    <h3><?php echo esc_html($values_data[9]['title']); ?></h3>
                    <p><?php echo esc_html($values_data[9]['desc']); ?></p>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════
         OUR ROOTS — video section
         ═══════════════════════════════════════ -->
    <?php
    $timeline_title = kg_get_field('story_timeline_title', 'Our Roots');
    $timeline_intro = kg_get_field('story_timeline_intro', 'Since 1999, Kings has been redefining the staffing industry.');
    $roots_video_url = function_exists('get_field') ? get_field('story_roots_video', get_queried_object_id()) : '';

    // Convert YouTube/Vimeo watch URL → embed URL
    $embed_url = '';
    if ($roots_video_url) {
        if (preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $roots_video_url, $m)) {
            $embed_url = 'https://www.youtube.com/embed/' . $m[1] . '?rel=0&modestbranding=1&autoplay=0';
        } elseif (preg_match('/vimeo\.com\/(\d+)/', $roots_video_url, $m)) {
            $embed_url = 'https://player.vimeo.com/video/' . $m[1] . '?dnt=1&title=0&byline=0&portrait=0';
        }
    }
    ?>
    <section class="roots-section" id="history">
        <div class="roots-section__bg" aria-hidden="true"></div>
        <div class="container">
            <div class="roots-section__header animate-on-scroll">
                <div class="roots-section__badge">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    Since 1999
                </div>
                <h2 class="section-title" style="color:#fff;"><?php echo esc_html($timeline_title); ?></h2>
                <p class="section-subtitle" style="color:rgba(255,255,255,0.75);"><?php echo esc_html($timeline_intro); ?></p>
            </div>

            <div class="roots-video-wrap animate-on-scroll">
                <?php if ($embed_url) : ?>
                <div class="roots-video-frame">
                    <iframe
                        src="<?php echo esc_url($embed_url); ?>"
                        title="Kings Group — Company Story"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen
                        loading="lazy">
                    </iframe>
                </div>
                <?php else : ?>
                <!-- Placeholder shown until a video URL is set in WordPress admin -->
                <div class="roots-video-placeholder">
                    <div class="roots-video-placeholder__inner">
                        <div class="roots-video-placeholder__play" aria-hidden="true">
                            <svg width="36" height="36" viewBox="0 0 24 24" fill="currentColor"><path d="M5 3l14 9-14 9V3z"/></svg>
                        </div>
                        <p>Add your company video URL in<br><strong>WordPress Admin → Our Story → Our Roots (Video)</strong></p>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Milestone chips below the video -->
            <div class="roots-milestones animate-on-scroll">
                <div class="roots-milestone"><span class="roots-milestone__year">1999</span><span class="roots-milestone__label">Founded</span></div>
                <div class="roots-milestone__arrow" aria-hidden="true">→</div>
                <div class="roots-milestone"><span class="roots-milestone__year">2009</span><span class="roots-milestone__label">Manpower</span></div>
                <div class="roots-milestone__arrow" aria-hidden="true">→</div>
                <div class="roots-milestone"><span class="roots-milestone__year">2011</span><span class="roots-milestone__label">Metro Manila</span></div>
                <div class="roots-milestone__arrow" aria-hidden="true">→</div>
                <div class="roots-milestone"><span class="roots-milestone__year">2016</span><span class="roots-milestone__label">Culinary School</span></div>
                <div class="roots-milestone__arrow" aria-hidden="true">→</div>
                <div class="roots-milestone"><span class="roots-milestone__year">2019</span><span class="roots-milestone__label">10K Members</span></div>
                <div class="roots-milestone__arrow" aria-hidden="true">→</div>
                <div class="roots-milestone"><span class="roots-milestone__year">2020</span><span class="roots-milestone__label">Kings City</span></div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════
         LEADERSHIP — editorial photo cards
         ═══════════════════════════════════════ -->
    <?php
    $team_title = kg_get_field('story_team_title', 'Kings Team');
    $team_intro = kg_get_field('story_team_intro', 'Meet the visionary leaders driving our cooperative.');

    $leaders_defaults = array(
        1 => array('name' => 'Neil John S. Makasiar', 'role' => 'Managing Director',
            'creds' => "Director, Makenter Construction & Development Corp\nVice President, Human Services Cluster, CDA Region IX\nCharter Vice President, REBAP - Zamboanga City\nCorporate Secretary, ZC Tierra Verde Corporation\nFormer President, Happy Kings Corporation\nMember, Rotary Club of Makati\nBachelor's Degree, De La Salle University – Manila"),
        2 => array('name' => 'Camille Navarro Makasiar', 'role' => 'Founder and Executive Director',
            'creds' => "Master in Entrep, Ateneo de Manila University GSB\nMember, Entrepreneurs Organization (EO) PH South Chapter\nTrustee, Bayan Innovation Group, Inc. & Bayan Academy\nCharter President, Inner Wheel Club of Metro Manila\nKnowledge Management, University of Oxford\nStrategic Management, Imperial College London\nBachelor's Degree, Southville International School & Colleges"),
        3 => array('name' => 'Cory DV Navarro', 'role' => 'Founder, Kings Group of Companies',
            'creds' => "Hall of Famer, Manila's Best Dressed & Zamboanga's Best Dressed\nHuwarang Ina Awardee (2017) & Empowered Women of the PH\nCharter President, Ambassador Charter Club, Melbourne\nPast Chairman, PH National Red Cross – Zamboanga City\nPast President, Rotary Club of Makati EDSA\nMember, MAP & PH Chamber of Commerce and Industry\nBachelor's Degree, Pioneer Nursing Batch of Ateneo de Zamboanga"),
    );
    for ($i = 1; $i <= 3; $i++) {
        if (function_exists('get_field') && get_field('story_leader'.$i.'_name', get_queried_object_id()))
            $leaders_defaults[$i]['name']  = get_field('story_leader'.$i.'_name', get_queried_object_id());
        if (function_exists('get_field') && get_field('story_leader'.$i.'_role', get_queried_object_id()))
            $leaders_defaults[$i]['role']  = get_field('story_leader'.$i.'_role', get_queried_object_id());
        if (function_exists('get_field') && get_field('story_leader'.$i.'_creds', get_queried_object_id()))
            $leaders_defaults[$i]['creds'] = get_field('story_leader'.$i.'_creds', get_queried_object_id());
        $leaders_defaults[$i]['img'] = function_exists('get_field') ? get_field('story_leader'.$i.'_img', get_queried_object_id()) : '';
    }
    ?>
    <section class="section section-bg-white" id="leadership">
        <div class="container animate-on-scroll">
            <h2 class="section-title"><?php echo esc_html($team_title); ?></h2>
            <p class="section-subtitle"><?php echo esc_html($team_intro); ?></p>

            <div class="leaders-grid">
                <?php for ($i = 1; $i <= 3; $i++) :
                    $l = $leaders_defaults[$i];
                    $creds = array_filter(array_map('trim', explode("\n", $l['creds'])));
                ?>
                <article class="leader-card animate-on-scroll">
                    <div class="leader-card__photo-wrap">
                        <?php if (!empty($l['img'])) : ?>
                            <img class="leader-card__photo" src="<?php echo esc_url($l['img']); ?>" alt="<?php echo esc_attr($l['name']); ?>" loading="lazy">
                        <?php else : ?>
                            <div class="leader-card__photo leader-card__photo--placeholder">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.5)" stroke-width="1.5">
                                    <circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
                                </svg>
                            </div>
                        <?php endif; ?>
                        <div class="leader-card__overlay">
                            <span class="leader-card__role"><?php echo esc_html($l['role']); ?></span>
                            <h3 class="leader-card__name"><?php echo esc_html($l['name']); ?></h3>
                        </div>
                    </div>
                    <div class="leader-card__body">
                        <ul class="leader-card__creds">
                            <?php foreach ($creds as $cred) : ?>
                            <li><?php echo esc_html($cred); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </article>
                <?php endfor; ?>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════
         GROUP OF COMPANIES — glass logo wall
         ═══════════════════════════════════════ -->
    <?php
    $companies_title = kg_get_field('story_companies_title', 'Group of Companies');
    $companies_intro = kg_get_field('story_companies_intro', 'A unified ecosystem of businesses powering the Kings network.');

    $companies_defaults = array(
        1  => array('name' => 'Kings Cooperative',  'asset' => 'KingsCooperative.webp'),
        2  => array('name' => 'Kings Asia Pacific', 'asset' => 'KingsAsiaPacific.webp'),
        3  => array('name' => 'Kings Lending',      'asset' => 'KingsLending.webp'),
        4  => array('name' => 'Makenter',           'asset' => 'Makenter.webp'),
        5  => array('name' => 'Home Culinary',      'asset' => 'HomeCulinary.webp'),
        6  => array('name' => 'Marian Palazz',      'asset' => 'MarianPalazz.webp'),
        7  => array('name' => 'Navishi Shell',      'asset' => 'NavishiShell.webp'),
        8  => array('name' => 'Pacific Water',      'asset' => 'PacificWater.webp'),
        9  => array('name' => 'Print Artist',       'asset' => 'PrintArtist.webp'),
        10 => array('name' => 'RN Foundation',      'asset' => 'RNFoundation.webp'),
        11 => array('name' => 'RPS Migration',      'asset' => 'RPSMigration.webp'),
    );
    for ($i = 1; $i <= 11; $i++) {
        if (function_exists('get_field') && get_field('story_co'.$i.'_name', get_queried_object_id()))
            $companies_defaults[$i]['name'] = get_field('story_co'.$i.'_name', get_queried_object_id());
        $companies_defaults[$i]['img'] = function_exists('get_field') ? get_field('story_co'.$i.'_img', get_queried_object_id()) : '';
    }
    ?>
    <section class="section section-bg-light" id="group-companies">
        <div class="container animate-on-scroll">
            <h2 class="section-title"><?php echo esc_html($companies_title); ?></h2>
            <p class="section-subtitle"><?php echo esc_html($companies_intro); ?></p>

            <div class="companies-showcase">
                <?php for ($i = 1; $i <= 11; $i++) : ?>
                <div class="company-showcase-item">
                    <?php if ($companies_defaults[$i]['img']) : ?>
                        <?php echo kg_img($companies_defaults[$i]['img'], $companies_defaults[$i]['name']); ?>
                    <?php else : ?>
                        <img src="<?php echo esc_url(kg_asset('assets/' . $companies_defaults[$i]['asset'])); ?>" alt="<?php echo esc_attr($companies_defaults[$i]['name']); ?>" loading="lazy">
                    <?php endif; ?>
                </div>
                <?php endfor; ?>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <div class="cta-bottom">
        <div class="container animate-on-scroll">
            <h2 style="color:white;font-size:2.75rem;margin-bottom:2rem;">Ready to experience the Kings difference?</h2>
            <div style="display:flex;justify-content:center;gap:1.5rem;flex-wrap:wrap;">
                <a href="<?php echo esc_url(home_url('/quote/')); ?>" class="btn btn-gold" style="font-size:1.1rem;padding:1.15rem 2.5rem;">
                    Build Your Team
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
                <a href="<?php echo esc_url(home_url('/careers/')); ?>" class="btn btn-outline"
                    style="border-color:rgba(255,255,255,0.3);color:white;background:rgba(255,255,255,0.1);backdrop-filter:blur(5px);font-size:1.1rem;padding:1.15rem 2.5rem;">
                    Join the Cooperative
                </a>
            </div>
        </div>
    </div>

<?php get_footer(); ?>
