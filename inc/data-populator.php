<?php
function kingsgroup_populate_all_pages()
{
    $file_hash = md5_file(__FILE__);
    if (get_option('kg_seeder_file_hash') === $file_hash) {
        return;
    }

    // Migration: Re-include existing jobs in Team Builder
    $all_jobs = get_posts(array(
        'post_type' => 'jobs',
        'posts_per_page' => -1,
        'fields' => 'ids'
    ));
    foreach ($all_jobs as $jid) {
        update_post_meta($jid, 'include_in_team_builder', 1);
    }


    $pages_to_create = array(
        'Home' => 'front-page.php',
        'Our Story' => 'story.php',
        'Careers' => 'careers.php',
        'Team Builder' => 'quote.php',
        'Member Benefits' => 'benefits.php',
        'Labor Management' => 'service-labor.php',
        'HR Tech (KIT)' => 'service-kit.php',
        'Our Network' => 'network.php',
        'Our Jobs' => 'our-jobs.php',
        'Contact Us' => 'contact.php',
        'Community' => 'community.php',
        'News' => 'index.php',
        'Terms of Service' => 'terms.php',
        'Privacy Policy' => 'privacy.php',
        'Trust & Safety' => 'trust-safety.php'
    );

    foreach ($pages_to_create as $title => $template) {
        // Check by template first, then by title
        $page_id = kg_get_page_by_template($template);

        $is_new_page = false;
        if (!$page_id) {
            $existing_pages = get_posts(array(
                'post_type' => 'page',
                'title' => $title,
                'numberposts' => 1
            ));
            if (!empty($existing_pages)) {
                $page_id = $existing_pages[0]->ID;
            }
        }

        if (!$page_id) {
            $page_id = wp_insert_post(array(
                'post_title' => $title,
                'post_status' => 'publish',
                'post_type' => 'page',
                'page_template' => $template
            ));
            $is_new_page = true;
        }

        // Auto-seed legal pages content from templates into database if empty
        if ($template === 'terms.php' || $template === 'privacy.php' || $template === 'trust-safety.php') {
            $current_post = get_post($page_id);
            if ($current_post && empty(trim($current_post->post_content))) {
                $file_path = get_template_directory() . '/' . $template;
                if (file_exists($file_path)) {
                    $file_content = file_get_contents($file_path);
                    $parts = explode('else {', $file_content);
                    if (count($parts) > 1) {
                        $subparts = explode('<?php', $parts[1]);
                        $html_block = trim($subparts[0]);
                        if (strpos($html_block, '?>') === 0) {
                            $html_block = trim(substr($html_block, 2));
                        }
                        if (!empty($html_block)) {
                            wp_update_post(array(
                                'ID' => $page_id,
                                'post_content' => $html_block
                            ));
                        }
                    }
                }
            }
        }

        // ─────────────────────────────────────────
        // 1. HOME PAGE
        // ─────────────────────────────────────────
        if ($template === 'front-page.php') {
            update_option('show_on_front', 'page');
            update_option('page_on_front', $page_id);

            // Hero
            update_post_meta($page_id, 'hero_headline', 'Your Trusted Local <span style="color:#ffd166;">Manpower Provider</span> <span style="color:#ffffff;">in the Philippines.</span>');
            update_post_meta($page_id, '_hero_headline', 'field_home_hero_headline');
            update_post_meta($page_id, 'hero_description', 'Helping businesses find qualified, reliable, and job-ready manpower across a wide range of industries. Kings Group has been connecting employers with skilled Filipino talent since 1999.');
            update_post_meta($page_id, '_hero_description', 'field_home_hero_desc');

            update_post_meta($page_id, 'hero_headline_intl', 'Elite Talent.<br>Ethical Staffing. <span>Exceptional Results.</span>');
            update_post_meta($page_id, '_hero_headline_intl', 'field_home_hero_headline_intl');
            update_post_meta($page_id, 'hero_description_intl', 'Scale your operations with dedicated offshore professionals from the Philippines.');
            update_post_meta($page_id, '_hero_description_intl', 'field_home_hero_desc_intl');

            // Hero images — pre-fill with local image assets
            update_post_meta($page_id, 'hero_img_1', kg_asset('img/front-page/hero-slide1.png'));
            update_post_meta($page_id, '_hero_img_1', 'field_home_hero_img_1');
            update_post_meta($page_id, 'hero_img_2', kg_asset('img/front-page/hero-slide2.png'));
            update_post_meta($page_id, '_hero_img_2', 'field_home_hero_img_2');
            update_post_meta($page_id, 'hero_img_3', kg_asset('img/front-page/hero-slide3.png'));
            update_post_meta($page_id, '_hero_img_3', 'field_home_hero_img_3');


            // Staffing Intro
            update_post_meta($page_id, 'home_intro_title', 'A Different Kind of Staffing');
            update_post_meta($page_id, '_home_intro_title', 'field_home_intro_title');
            update_post_meta($page_id, 'home_intro_sub', 'Bridging the gap between a traditional agency and a modern global talent platform to serve businesses and career-seekers alike.');
            update_post_meta($page_id, '_home_intro_sub', 'field_home_intro_sub');

            // Client Advantage — INTL
            update_post_meta($page_id, 'adv_headline', 'Build a World-Class Team<br>at a Fraction of the Cost.');
            update_post_meta($page_id, '_adv_headline', 'field_home_adv_headline');
            update_post_meta($page_id, 'adv_subheadline', 'Build your dedicated offshore team in the Philippines — without the risk, red tape, or overhead.');
            update_post_meta($page_id, '_adv_subheadline', 'field_home_adv_sub');
            update_post_meta($page_id, 'adv_desc', 'Kings Group is the Philippines\' only worker-owned staffing cooperative. With 10,000+ members since 1999, you get deeply loyal, high-performing offshore professionals who treat your business like their own.');
            update_post_meta($page_id, '_adv_desc', 'field_home_adv_desc');
            update_post_meta($page_id, 'adv_stat', '10000');
            update_post_meta($page_id, '_adv_stat', 'field_home_adv_stat');
            update_post_meta($page_id, 'adv_img', kg_asset('img/front-page/for-client.png'));
            update_post_meta($page_id, '_adv_img', 'field_home_adv_img');

            $adv_features = array(
                array('Significant Cost Savings', 'Filipino professionals deliver world-class output at a significantly lower cost than equivalent local hires — with zero compromise on quality or reliability.'),
                array('Fully Managed. Zero Hassle.', 'We handle HR, payroll, compliance, hardware, and facilities. You focus on growing your business — we take care of everything on the ground.'),
                array('Hire in Days, Not Months', 'Tap into our pre-screened talent pool of 10,000+ professionals. Most clients have their first team member deployed within 7–14 business days.'),
            );
            foreach ($adv_features as $i => $f) {
                $n = $i + 1;
                update_post_meta($page_id, 'adv_f' . $n . '_title', $f[0]);
                update_post_meta($page_id, '_adv_f' . $n . '_title', 'field_home_adv_f' . $n . '_title');
                update_post_meta($page_id, 'adv_f' . $n . '_desc', $f[1]);
                update_post_meta($page_id, '_adv_f' . $n . '_desc', 'field_home_adv_f' . $n . '_desc');
            }

            // Client Advantage — Local PH
            update_post_meta($page_id, 'adv_headline_ph', 'Your Trusted Local<br>Manpower Provider.');
            update_post_meta($page_id, '_adv_headline_ph', 'field_home_adv_ph_headline');
            update_post_meta($page_id, 'adv_subheadline_ph', 'Stop the hiring hassle. Start deploying.');
            update_post_meta($page_id, '_adv_subheadline_ph', 'field_home_adv_ph_sub');
            update_post_meta($page_id, 'adv_desc_ph', 'Get job-ready Filipino workers sourced, screened, and deployed to your business — fully managed and DOLE-compliant. We\'ve been doing this since 1999.');
            update_post_meta($page_id, '_adv_desc_ph', 'field_home_adv_ph_desc');

            $adv_ph_features = array(
                array('DOLE-Licensed & Compliant', 'All deployments are covered under a valid DOLE license. We handle legal compliance, government remittances, and labor standards on your behalf.'),
                array('Fast Deployment', 'From job order to boots on the ground — our local network of screened candidates means faster turnaround than traditional agencies.'),
                array('Managed Payroll & Benefits', 'We administer payroll, SSS, PhilHealth, and Pag-IBIG remittances so you can focus on running your business, not HR paperwork.'),
            );
            foreach ($adv_ph_features as $i => $f) {
                $n = $i + 1;
                update_post_meta($page_id, 'adv_f' . $n . '_title_ph', $f[0]);
                update_post_meta($page_id, '_adv_f' . $n . '_title_ph', 'field_home_adv_ph_f' . $n . '_title');
                update_post_meta($page_id, 'adv_f' . $n . '_desc_ph', $f[1]);
                update_post_meta($page_id, '_adv_f' . $n . '_desc_ph', 'field_home_adv_ph_f' . $n . '_desc');
            }


            // Applicant Focus
            update_post_meta($page_id, 'app_headline', '"Your Career,<br>Owned by You."');
            update_post_meta($page_id, '_app_headline', 'field_home_app_headline');
            update_post_meta($page_id, 'app_subheadline', 'Join a community where you are a member, not just a number.');
            update_post_meta($page_id, '_app_subheadline', 'field_home_app_sub');
            update_post_meta($page_id, 'app_desc', 'Get access to premium benefits, career coaching, and the stability of a worker-owned cooperative. It\'s built for you, by people like you.');
            update_post_meta($page_id, '_app_desc', 'field_home_app_desc');
            update_post_meta($page_id, 'app_img', kg_asset('img/front-page/for-applicants.png'));
            update_post_meta($page_id, '_app_img', 'field_home_app_img');

            // Applicant CTA — PH
            update_post_meta($page_id, 'app_cta_ph', 'Drop your CV Today');
            update_post_meta($page_id, '_app_cta_ph', 'field_home_app_cta_ph_label');
            update_post_meta($page_id, 'app_cta_ph_url', home_url('/careers/'));
            update_post_meta($page_id, '_app_cta_ph_url', 'field_home_app_cta_ph_url');

            // Applicant CTA — INTL
            update_post_meta($page_id, 'app_cta_intl', 'Find Offshore Talent');
            update_post_meta($page_id, '_app_cta_intl', 'field_home_app_cta_intl_label');
            update_post_meta($page_id, 'app_cta_intl_url', home_url('/contact/'));
            update_post_meta($page_id, '_app_cta_intl_url', 'field_home_app_cta_intl_url');

            // Applicant Features — PH (3 items)
            $app_ph_features = array(
                array('Fast-Track Application', 'No long forms. Just drop your CV and let our recruiters find your perfect match in our global network.'),
                array('Skill Assessment & Training', 'Enhance your career readiness with personalized skill assessments and guidance to make your profile stand out.'),
                array('Direct Employer Matching', 'Skip the middleman and get introduced directly to top local employers looking for your exact skillset.'),
            );
            foreach ($app_ph_features as $i => $f) {
                $n = $i + 1;
                update_post_meta($page_id, 'app_f' . $n . '_title', $f[0]);
                update_post_meta($page_id, '_app_f' . $n . '_title', 'field_home_app_ph_f' . $n . '_title');
                update_post_meta($page_id, 'app_f' . $n . '_desc', $f[1]);
                update_post_meta($page_id, '_app_f' . $n . '_desc', 'field_home_app_ph_f' . $n . '_desc');
            }

            // Applicant Features — INTL (3 items)
            $app_intl_features = array(
                array('Global Hiring Alignment', 'We prepare candidates to work with top-tier international businesses, ensuring seamless cultural and workflow integration.'),
                array('Modern Office Workspaces', 'Candidates work in our highly secure, modern workspaces equipped with high-speed internet and premium facilities.'),
                array('Premium Benefits Support', 'Cooperative security and full statutory compliance support, offering unparalleled stability compared to traditional freelancing.'),
            );
            foreach ($app_intl_features as $i => $f) {
                $n = $i + 1;
                update_post_meta($page_id, 'app_intl_f' . $n . '_title', $f[0]);
                update_post_meta($page_id, '_app_intl_f' . $n . '_title', 'field_home_app_intl_f' . $n . '_title');
                update_post_meta($page_id, 'app_intl_f' . $n . '_desc', $f[1]);
                update_post_meta($page_id, '_app_intl_f' . $n . '_desc', 'field_home_app_intl_f' . $n . '_desc');
            }

            // Testimonials
            update_post_meta($page_id, 'testi_title', 'What Our Members Say');
            update_post_meta($page_id, '_testi_title', 'field_home_testi_title');
            update_post_meta($page_id, 'testi_subtitle', 'Hear from the empowered professionals and cooperative members who have built their careers with Kings.');
            update_post_meta($page_id, '_testi_subtitle', 'field_home_testi_sub');

            $testimonials = array(
                array(
                    'Kings Group fundamentally transformed how we structure our customer service in Asia. The worker-owned model means our team operates with an unparalleled sense of ownership and dedication.',
                    'David K.',
                    'COO, Global Logistics Tech'
                ),
                array(
                    'Before Kings, my career was just a series of jobs. Now, as a member-owner, I have access to lending programs, real benefits, and a voice in how we operate. It\'s life-changing.',
                    'Maria S.',
                    'Senior Technical Support'
                ),
            );
            foreach ($testimonials as $i => $t) {
                $n = $i + 1;
                update_post_meta($page_id, 'testi_' . $n . '_quote', $t[0]);
                update_post_meta($page_id, '_testi_' . $n . '_quote', 'field_home_t' . $n . '_quote');
                update_post_meta($page_id, 'testi_' . $n . '_name', $t[1]);
                update_post_meta($page_id, '_testi_' . $n . '_name', 'field_home_t' . $n . '_name');
                update_post_meta($page_id, 'testi_' . $n . '_role', $t[2]);
                update_post_meta($page_id, '_testi_' . $n . '_role', 'field_home_t' . $n . '_role');
            }

            // Our Network
            update_post_meta($page_id, 'net_title', 'Our Network');
            update_post_meta($page_id, '_net_title', 'field_home_net_title');
            update_post_meta($page_id, 'net_subtitle', 'Explore our affiliated brands and communities.');
            update_post_meta($page_id, '_net_subtitle', 'field_home_net_desc');

            $brands = array(
                1 => array('title' => 'The Kings City', 'desc' => 'A space where creativity, productivity, and community come together. Designed for individuals, creatives, entrepreneurs, and growing teams, the club offers thoughtfully curated spaces for coworking, collaboration, workshops, and meaningful connections.', 'link' => 'https://www.kings-city.com/', 'btn' => 'Discover Kings City', 'img' => kg_asset('img/front-page/kings-city.JPG')),
                2 => array('title' => 'The Social Manila', 'desc' => 'The premier events, lifestyle, and community engagement hub. We host corporate functions, team-building events, and exclusive gatherings designed to connect leaders and ignite culture.', 'link' => 'https://kingscity.com.ph/', 'btn' => 'Explore The Social', 'img' => kg_asset('img/front-page/the-social-manila.png')),
                3 => array('title' => 'The Home Culinary School', 'desc' => 'Professional culinary training and certification programs. Equipping the next generation of chefs and hospitality professionals with world-class skills, discipline, and ethical standards.', 'link' => 'https://temptest.homeculinaryschool.com/', 'btn' => 'Start Cooking', 'img' => kg_asset('img/front-page/home-culinary.png')),
            );
            foreach ($brands as $i => $b) {
                update_post_meta($page_id, 'net_brand' . $i . '_title', $b['title']);
                update_post_meta($page_id, '_net_brand' . $i . '_title', 'field_home_net_b' . $i . '_title');
                update_post_meta($page_id, 'net_brand' . $i . '_desc', $b['desc']);
                update_post_meta($page_id, '_net_brand' . $i . '_desc', 'field_home_net_b' . $i . '_desc');
                update_post_meta($page_id, 'net_brand' . $i . '_link', $b['link']);
                update_post_meta($page_id, '_net_brand' . $i . '_link', 'field_home_net_b' . $i . '_link');
                update_post_meta($page_id, 'net_brand' . $i . '_btn', $b['btn']);
                update_post_meta($page_id, '_net_brand' . $i . '_btn', 'field_home_net_b' . $i . '_btn');
                update_post_meta($page_id, 'net_brand' . $i . '_img', $b['img']);
                update_post_meta($page_id, '_net_brand' . $i . '_img', 'field_home_net_b' . $i . '_img');
            }

            // Who We Are Section
            update_post_meta($page_id, 'wwa_title', 'Who We Are');
            update_post_meta($page_id, '_wwa_title', 'field_home_wwa_title');
            update_post_meta($page_id, 'wwa_p1', 'THE KINGS is a fast-rising cooperative in the Philippines, duly registered with the Cooperative Development Authority (CDA) and organized pursuant to the provisions of the law and existing rules and regulations, with an ever-growing list of satisfied clients.');
            update_post_meta($page_id, '_wwa_p1', 'field_home_wwa_p1');
            update_post_meta($page_id, 'wwa_p2', 'We are bound by a common goal of improving our members\' lives by giving better benefits. All owner-members enjoy additional benefits such as Interest on Capital Contribution, Insurance/HMO and Surplus Sharing. KINGS also provides members with facilities such as the Savings Program, Livelihood Program and Loan Program.');
            update_post_meta($page_id, '_wwa_p2', 'field_home_wwa_p2');
            update_post_meta($page_id, 'wwa_p3', 'The Kings is offering different kinds of Manpower Services, Staff Leasing, HR & Payroll Management, Spaces, Culinary and Livelihood Programs and Microfinancing Services.');
            update_post_meta($page_id, '_wwa_p3', 'field_home_wwa_p3');
            update_post_meta($page_id, 'wwa_btn_text', 'Learn Our Story');
            update_post_meta($page_id, '_wwa_btn_text', 'field_home_wwa_btn_text');
            update_post_meta($page_id, 'wwa_img', kg_asset('img/front-page/homepage.png'));
            update_post_meta($page_id, '_wwa_img', 'field_home_wwa_img');

            // Join The Kings Section
            update_post_meta($page_id, 'jtk_title', 'Join The Kings');
            update_post_meta($page_id, '_jtk_title', 'field_home_jtk_title');

            $jtk_defaults = array(
                1 => array('title' => 'Why The Kings', 'link' => '/benefits/', 'img' => kg_asset('img/front-page/jointhekings1.png')),
                2 => array('title' => 'Engagements', 'link' => '/network/', 'img' => kg_asset('img/front-page/jointhekings2.png')),
                3 => array('title' => 'Community', 'link' => '/community/', 'img' => kg_asset('img/front-page/jointhekings3.png')),
            );
            for ($i = 1; $i <= 3; $i++) {
                update_post_meta($page_id, 'jtk_card' . $i . '_title', $jtk_defaults[$i]['title']);
                update_post_meta($page_id, '_jtk_card' . $i . '_title', 'field_home_jtk_card' . $i . '_title');
                update_post_meta($page_id, 'jtk_card' . $i . '_link', $jtk_defaults[$i]['link']);
                update_post_meta($page_id, '_jtk_card' . $i . '_link', 'field_home_jtk_card' . $i . '_link');
                update_post_meta($page_id, 'jtk_card' . $i . '_img', $jtk_defaults[$i]['img']);
                update_post_meta($page_id, '_jtk_card' . $i . '_img', 'field_home_jtk_card' . $i . '_img');
            }
        }


        // ─────────────────────────────────────────
        // 2. STORY PAGE
        // ─────────────────────────────────────────
        if ($template === 'story.php') {
            // Hero
            update_post_meta($page_id, 'story_headline', 'Our Story');
            update_post_meta($page_id, '_story_headline', 'field_story_headline');
            update_post_meta($page_id, 'story_desc', 'A legacy formed on ethical practices, worker empowerment, and shared success since 1999.');
            update_post_meta($page_id, '_story_desc', 'field_story_desc');
            update_post_meta($page_id, 'story_bg', kg_asset('img/story/hero-story.png'));
            update_post_meta($page_id, '_story_bg', 'field_story_bg');
            update_post_meta($page_id, 'story_hero_video', 'https://vimeo.com/1197690853');
            update_post_meta($page_id, '_story_hero_video', 'field_story_hero_video');

            // Vision & Mission
            update_post_meta($page_id, 'story_vision_title', 'Vision & Mission');
            update_post_meta($page_id, '_story_vision_title', 'field_story_vision_title');
            update_post_meta($page_id, 'story_vision_text', "We stand as a leader in professional workforce solutions, honoring the confidence our clients and\nmembers place in us every day. Our commitment is simple: to protect the integrity of our clients and\nensure that their policies are upheld with consistency, respect, and excellence.\nAt Kings Manpower, everyday is an opportunity to build upon our collective efforts—to make clients\nfeel more secure, leaders feel more confident, and people feel more at ease. In every decision we\nmake, we work toward brighter tomorrows—for companies, for communities, and for every Filipino we\nserve.");
            update_post_meta($page_id, '_story_vision_text', 'field_story_vision_text');
            update_post_meta($page_id, 'story_mission_text', "Success takes shape in the light of the right people—those who show up with skill, heart, and\npurpose. At Kings Manpower, we nurture this spirit in every Kings Scout. Through sustainable work,\nongoing training, and meaningful pathways for growth, we empower our members to rise. And in turn, we give businesses a workforce they can rely on from the first light of every new day to the next.");
            update_post_meta($page_id, '_story_mission_text', 'field_story_mission_text');
            update_post_meta($page_id, 'story_mission_text_2', '');
            update_post_meta($page_id, '_story_mission_text_2', 'field_story_mission_text_2');

            // Core Values
            update_post_meta($page_id, 'story_values_title', 'Core Values');
            update_post_meta($page_id, '_story_values_title', 'field_story_values_title');
            update_post_meta($page_id, 'story_values_intro', 'SCOUT');
            update_post_meta($page_id, '_story_values_intro', 'field_story_values_intro');

            $values = array(
                array('STRENGTH IN SERVICE', 'We are member-centric, putting our members and their families first while serving clients and communities with respect, courtesy, and efficiency.'),
                array('COMMITMENT', 'We lead with ownership and embrace accountability in all that we do, taking responsibility with integrity, dedication, and care to honor the trust placed in us.'),
                array('OUTSTANDING VALUE & INNOVATION', 'We deliver meaningful results by bringing together the best people, proven practices, and innovative ideas—always improving to create smart, future-ready solutions.'),
                array('UNITED IN EXCELLENCE', 'Success is a collective effort. By working hand-in-hand with our clients, we combine effort and insight, ensuring alignment that delivers extraordinary results and creates exceptional experiences.'),
                array('TRUTH & TRUST', 'Our actions are rooted in honesty, guided by transparency, and strengthened by reliability.'),
            );
            foreach ($values as $i => $v) {
                $n = $i + 1;
                update_post_meta($page_id, 'story_v' . $n . '_title', $v[0]);
                update_post_meta($page_id, '_story_v' . $n . '_title', 'field_story_v' . $n . '_title');
                update_post_meta($page_id, 'story_v' . $n . '_desc', $v[1]);
                update_post_meta($page_id, '_story_v' . $n . '_desc', 'field_story_v' . $n . '_desc');
            }
            // Clear out old values 6-9
            for ($k = 6; $k <= 9; $k++) {
                delete_post_meta($page_id, 'story_v' . $k . '_title');
                delete_post_meta($page_id, '_story_v' . $k . '_title');
                delete_post_meta($page_id, 'story_v' . $k . '_desc');
                delete_post_meta($page_id, '_story_v' . $k . '_desc');
            }

            // Our Roots
            update_post_meta($page_id, 'story_timeline_title', 'Our Roots');
            update_post_meta($page_id, '_story_timeline_title', 'field_story_timeline_title');
            update_post_meta($page_id, 'story_timeline_intro', 'Since 1999, Kings has been redefining the staffing industry.');
            update_post_meta($page_id, '_story_timeline_intro', 'field_story_timeline_intro');

            // Clean up / Delete Leadership Team & Group of Companies meta keys
            delete_post_meta($page_id, 'story_team_title');
            delete_post_meta($page_id, '_story_team_title');
            delete_post_meta($page_id, 'story_team_intro');
            delete_post_meta($page_id, '_story_team_intro');
            for ($k = 1; $k <= 3; $k++) {
                delete_post_meta($page_id, 'story_leader' . $k . '_name');
                delete_post_meta($page_id, '_story_leader' . $k . '_name');
                delete_post_meta($page_id, 'story_leader' . $k . '_role');
                delete_post_meta($page_id, '_story_leader' . $k . '_role');
                delete_post_meta($page_id, 'story_leader' . $k . '_creds');
                delete_post_meta($page_id, '_story_leader' . $k . '_creds');
                delete_post_meta($page_id, 'story_leader' . $k . '_img');
                delete_post_meta($page_id, '_story_leader' . $k . '_img');
            }
            delete_post_meta($page_id, 'story_companies_title');
            delete_post_meta($page_id, '_story_companies_title');
            delete_post_meta($page_id, 'story_companies_intro');
            delete_post_meta($page_id, '_story_companies_intro');


        }

        // ─────────────────────────────────────────
        // 3. CAREERS PAGE
        // ─────────────────────────────────────────
        if ($template === 'careers.php') {
            update_post_meta($page_id, 'careers_headline', 'Build Your Future<br><span style="color:var(--neutral-yellow);">Own Your Career</span>');
            update_post_meta($page_id, '_careers_headline', 'field_careers_headline');
            update_post_meta($page_id, 'careers_desc', 'Join the Philippines\' leading worker-owned cooperative. Get profit-sharing, career coaching, and a network of 10,000+ professionals.');
            update_post_meta($page_id, '_careers_desc', 'field_careers_desc');
            update_post_meta($page_id, 'careers_bg', 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=2000&q=80');
            update_post_meta($page_id, '_careers_bg', 'field_careers_bg');
            update_post_meta($page_id, 'careers_form_title', 'Fast-Track Application');
            update_post_meta($page_id, '_careers_form_title', 'field_careers_form_title');
            update_post_meta($page_id, 'careers_form_desc', 'No long forms. Drop your CV and our recruiters will find your perfect match.');
            update_post_meta($page_id, '_careers_form_desc', 'field_careers_form_desc');
        }

        // ─────────────────────────────────────────
        // 4. BENEFITS PAGE
        // ─────────────────────────────────────────
        if ($template === 'benefits.php') {
            update_post_meta($page_id, 'benefits_headline', 'Why Join Kings?');
            update_post_meta($page_id, '_benefits_headline', 'field_benefits_headline');
            update_post_meta($page_id, 'benefits_desc', 'Experience a new standard of employment. At Kings Group, our cooperative model empowers members with comprehensive benefits, financial security, and shared success.');
            update_post_meta($page_id, '_benefits_desc', 'field_benefits_desc');
            update_post_meta($page_id, 'benefits_bg', 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=2000&q=80');
            update_post_meta($page_id, '_benefits_bg', 'field_benefits_bg');
            update_post_meta($page_id, 'benefits_list_title', 'Our Member Benefits');
            update_post_meta($page_id, '_benefits_list_title', 'field_benefits_list_title');
            update_post_meta($page_id, 'benefits_list_desc', 'Comprehensive member benefits designed to support every stage of your career and life.');
            update_post_meta($page_id, '_benefits_list_desc', 'field_benefits_list_desc');

            $benefits = array(
                array('HMO with Insurance', 'Comprehensive health coverage and life insurance for you and your dependents.'),
                array('SSS', 'Full SSS contribution coverage and compliant government remittances.'),
                array('PhilHealth', 'Full PhilHealth contribution coverage and compliant government remittances.'),
                array('Pag-Ibig', 'Full Pag-IBIG contribution coverage and compliant government remittances.'),
                array('Tax Exemption', 'Enjoy the financial advantage of tax exemptions available to cooperative members.'),
                array('Associate Membership', 'Join a community of empowered professionals with a real stake in the company.'),
                array('Credit and Savings Programs', 'Access our internal lending program and structured savings schemes.'),
                array('Extended HR Department', 'Dedicated support from our HR professionals who are always ready to assist you.'),
                array('Access to Advanced Technology Tools', 'Work with premium hardware, enterprise software, and our proprietary KIT platform.'),
                array('Livelihood Support for their Families', 'Skill-building and sustainable livelihood programs for members and their dependents.'),
            );
            foreach ($benefits as $i => $b) {
                $n = $i + 1;
                update_post_meta($page_id, 'benefits_b' . $n . '_title', $b[0]);
                update_post_meta($page_id, '_benefits_b' . $n . '_title', 'field_benefits_b' . $n . '_title');
                update_post_meta($page_id, 'benefits_b' . $n . '_desc', $b[1]);
                update_post_meta($page_id, '_benefits_b' . $n . '_desc', 'field_benefits_b' . $n . '_desc');
            }
        }

        // ─────────────────────────────────────────
        // 5. SERVICE LABOR PAGE
        // ─────────────────────────────────────────
        if ($template === 'service-labor.php') {
            // Hero
            update_post_meta($page_id, 'slab_headline', 'Labor Management Services');
            update_post_meta($page_id, '_slab_headline', 'field_slab_headline');
            update_post_meta($page_id, 'slab_desc', 'The Kings diversified services include the following: Managed Services and Managed Staff Leasing Services');
            update_post_meta($page_id, '_slab_desc', 'field_slab_desc');
            update_post_meta($page_id, 'slab_bg', kg_asset('img/service-labor/hero-labor.JPG'));
            update_post_meta($page_id, '_slab_bg', 'field_slab_bg');

            // Stats
            update_post_meta($page_id, 'slab_stat1_num', '10,000+');
            update_post_meta($page_id, '_slab_stat1_num', 'field_slab_stat1_num');
            update_post_meta($page_id, 'slab_stat1_label', 'Active Members');
            update_post_meta($page_id, '_slab_stat1_label', 'field_slab_stat1_label');

            update_post_meta($page_id, 'slab_stat2_num', '25+');
            update_post_meta($page_id, '_slab_stat2_num', 'field_slab_stat2_num');
            update_post_meta($page_id, 'slab_stat2_label', 'Years of Excellence');
            update_post_meta($page_id, '_slab_stat2_label', 'field_slab_stat2_label');

            update_post_meta($page_id, 'slab_stat3_num', '70%');
            update_post_meta($page_id, '_slab_stat3_num', 'field_slab_stat3_num');
            update_post_meta($page_id, 'slab_stat3_label', 'Productivity Increase');
            update_post_meta($page_id, '_slab_stat3_label', 'field_slab_stat3_label');

            update_post_meta($page_id, 'slab_stat4_num', '100%');
            update_post_meta($page_id, '_slab_stat4_num', 'field_slab_stat4_num');
            update_post_meta($page_id, 'slab_stat4_label', 'DOLE Compliant');
            update_post_meta($page_id, '_slab_stat4_label', 'field_slab_stat4_label');

            // Service Intro
            update_post_meta($page_id, 'slab_intro_title', 'Labor Management Services');
            update_post_meta($page_id, '_slab_intro_title', 'field_slab_intro_title');
            update_post_meta($page_id, 'slab_intro_desc', 'The Kings diversified services include the following: Managed Services and Managed Staff Leasing Services');
            update_post_meta($page_id, '_slab_intro_desc', 'field_slab_intro_desc');
            update_post_meta($page_id, 'slab_intro_img', kg_asset('img/service-labor/kings-labor-content1.JPG'));
            update_post_meta($page_id, '_slab_intro_img', 'field_slab_intro_img');
            update_post_meta($page_id, 'slab_intro_pills', "Recruitment & Deployment\nOrientation & Training\nTimekeeping & Payroll\nCompensation Programs\nLegal Processes\nHR Administration");
            update_post_meta($page_id, '_slab_intro_pills', 'field_slab_intro_pills');
            update_post_meta($page_id, 'slab_badge_num', '10,000+');
            update_post_meta($page_id, '_slab_badge_num', 'field_slab_badge_num');
            update_post_meta($page_id, 'slab_badge_label', 'Member Network');
            update_post_meta($page_id, '_slab_badge_label', 'field_slab_badge_label');

            // Managed Services
            update_post_meta($page_id, 'slab_managed_label', 'Managed Services');
            update_post_meta($page_id, '_slab_managed_label', 'field_slab_managed_label');
            update_post_meta($page_id, 'slab_managed_title', 'Managed Services');
            update_post_meta($page_id, '_slab_managed_title', 'field_slab_managed_title');
            update_post_meta($page_id, 'slab_managed_desc', 'This kind of service that we are offering is focused on local companies in the Philippines.<br><br>The Kings has a very innovative concept that addresses the issue of contractual workers and their productivity. The business model we are referring to is the Manpower Service Cooperative concept of which The Kings can help you with.');
            update_post_meta($page_id, '_slab_managed_desc', 'field_slab_managed_desc');

            $features = array(
                array('Owner-Members', 'The Kings is owned by a group of professionals and workers. We are bound by a common aim of providing gainful and sustainable livelihood opportunities for each member of the organization.', kg_asset('img/service-labor/talent-sourcing.png')),
                array('Self-Employed Status', 'By being owner-members, we are SELF-EMPLOYED individuals. We are neither employees nor are we employers.', kg_asset('img/service-labor/payroll.png')),
                array('Flexible Engagement', 'You can keep our services as long as you consider it necessary without the need of absorbing our members as regular employees. This breaks the tedious cycle of recruiting, training, and terminating contractual workers.', kg_asset('img/service-labor/performance-management.png')),
                array('Maximized Productivity', 'The Kings has helped increase clients’ productivity by up to 70% by way of continuous engagement of the learned skills of owner-members, while bringing down their administrative costs vis-à-vis recurring recruitment and training expenses.', kg_asset('img/service-labor/hardware & equipment.png')),
            );
            foreach ($features as $i => $f) {
                $n = $i + 1;
                update_post_meta($page_id, 'slab_feat' . $n . '_title', $f[0]);
                update_post_meta($page_id, '_slab_feat' . $n . '_title', 'field_slab_feat' . $n . '_title');
                update_post_meta($page_id, 'slab_feat' . $n . '_desc', $f[1]);
                update_post_meta($page_id, '_slab_feat' . $n . '_desc', 'field_slab_feat' . $n . '_desc');
                update_post_meta($page_id, 'slab_feat' . $n . '_img', $f[2]);
                update_post_meta($page_id, '_slab_feat' . $n . '_img', 'field_slab_feat' . $n . '_img');
            }

            // Total Manpower
            update_post_meta($page_id, 'slab_manpower_title', 'Total Manpower Solutions');
            update_post_meta($page_id, '_slab_manpower_title', 'field_slab_manpower_title');
            update_post_meta($page_id, 'slab_manpower_text', '<p>We are committed to providing total solutions to our clients’ manpower outsourcing concerns and our candidates’ successful entry. We may absorb your existing people or we may look for your needed manpower force, freeing your company from the hassles of manpower attention and supervision. We move as you desire.</p><p>Our goal is to exceed the expectations of every client by offering outstanding manpower services, increased flexibility and greater value.</p><p>Our associates are distinguished by their functional and technical ability, ensuring that our clients receive the most effective and professional service. Finally, The Kings keeps up with the client’s objective of properly implementing its rules and regulations.</p><p>The Kings has consistently executed its role as a manpower cooperative in the country. Thriving on partnerships and long-term relationships with clients from diverse industries, The Kings has been serving with excellence and passion through the years.</p>');
            update_post_meta($page_id, '_slab_manpower_text', 'field_slab_manpower_text');
            update_post_meta($page_id, 'slab_manpower_img', kg_asset('img/service-labor/total-manpower.png'));
            update_post_meta($page_id, '_slab_manpower_img', 'field_slab_manpower_img');

            $mp_features = array(
                array('Absorb Existing Staff', 'We may absorb your existing people, freeing your company from the hassles of manpower attention and supervision.'),
                array('Source New Talent', 'We may look for your needed manpower force and ensure candidates\' successful entry.'),
                array('Move as You Desire', 'We move as you desire to exceed expectations by offering outstanding services and flexibility.'),
            );
            foreach ($mp_features as $i => $mf) {
                $n = $i + 1;
                update_post_meta($page_id, 'slab_mp_feat' . $n . '_title', $mf[0]);
                update_post_meta($page_id, '_slab_mp_feat' . $n . '_title', 'field_slab_mp_feat' . $n . '_title');
                update_post_meta($page_id, 'slab_mp_feat' . $n . '_desc', $mf[1]);
                update_post_meta($page_id, '_slab_mp_feat' . $n . '_desc', 'field_slab_mp_feat' . $n . '_desc');
            }

            // Comparison 1
            update_post_meta($page_id, 'slab_comp1_title', 'The Cooperative Advantage');
            update_post_meta($page_id, '_slab_comp1_title', 'field_slab_comp1_title');
            update_post_meta($page_id, 'slab_comp1_desc', 'See how The Kings model compares against Direct Hire and Agency hiring.');
            update_post_meta($page_id, '_slab_comp1_desc', 'field_slab_comp1_desc');

            // Staff Leasing
            update_post_meta($page_id, 'slab_lease_label', 'Offshore Staff Leasing');
            update_post_meta($page_id, '_slab_lease_label', 'field_slab_lease_label');
            update_post_meta($page_id, 'slab_lease_title', 'Managed Staff Leasing Services');
            update_post_meta($page_id, '_slab_lease_title', 'field_slab_lease_title');
            update_post_meta($page_id, 'slab_lease_desc', 'This service is primarily for our Offshore Clients in countries such as Australia and the United States of America.<br><br>Managed Staff Leasing is a business delivery between Kings and our clients, where we take care of recruiting staff for our client\'s particular needs, along with providing equipment and overseeing operations of our clients, ensuring that their leased staff from Kings are delivering the quality of work that is expected of them by the client.');
            update_post_meta($page_id, '_slab_lease_desc', 'field_slab_lease_desc');
            update_post_meta($page_id, 'slab_offshore_label', 'Cooperative Advantage');
            update_post_meta($page_id, '_slab_offshore_label', 'field_slab_offshore_label');
            update_post_meta($page_id, 'slab_offshore_step_label', 'Step 1');
            update_post_meta($page_id, '_slab_offshore_step_label', 'field_slab_offshore_step_label');
            update_post_meta($page_id, 'slab_offshore_title', 'How Does Offshore Managed Staff Leasing Work?');
            update_post_meta($page_id, '_slab_offshore_title', 'field_slab_offshore_title');
            update_post_meta($page_id, 'slab_offshore_text', '<p>It is your team. With offshore managed staff leasing, you have full control of your members without having to worry about the facilities, operations as well as the regulations here in the Philippines.</p><p>Offshoring is a type of outsourcing where you create an extension of your business by setting up a team in another country.</p><p>Managed services for offshore clients or what we refer to as Managed Staff Leasing involves setting up an overseas team of your company and getting The Kings to handle the equipments in the office, facilities, IT, labor laws, recruitment and HR or what we call People and Culture, while you retain full control of the quality and productivity for your extended team. In other words, we take care of everything on the ground while you can focus on the business processes and expand your core business to run from the Philippines. We make managing your offshore teams as smooth as possible.</p>');
            update_post_meta($page_id, '_slab_offshore_text', 'field_slab_offshore_text');
            update_post_meta($page_id, 'slab_offshore_img', kg_asset('img/service-labor/offshore-staff-leasing.png'));
            update_post_meta($page_id, '_slab_offshore_img', 'field_slab_offshore_img');
            update_post_meta($page_id, 'slab_offshore_callout', 'Your team, your direction — we handle the rest.');
            update_post_meta($page_id, '_slab_offshore_callout', 'field_slab_offshore_callout');

            // Improving Manpower
            update_post_meta($page_id, 'slab_improve_step_label', 'Step 2');
            update_post_meta($page_id, '_slab_improve_step_label', 'field_slab_improve_step_label');
            update_post_meta($page_id, 'slab_improve_title', 'Improving Your Manpower');
            update_post_meta($page_id, '_slab_improve_title', 'field_slab_improve_title');
            update_post_meta($page_id, 'slab_improve_desc', 'Our Offshore Managed Staff Leasing services can help improve your manpower. We will take care of all the administrative requirements, allowing you to focus more on income-generating activities and maintaining your business\' competitive edge. We source, recruit and onboard your offshore team or resource in line with your business needs.');
            update_post_meta($page_id, '_slab_improve_desc', 'field_slab_improve_desc');
            update_post_meta($page_id, 'slab_improve_img', kg_asset('img/service-labor/improve-manpower.png'));
            update_post_meta($page_id, '_slab_improve_img', 'field_slab_improve_img');

            $checklists = array(
                array('Cost Efficient', 'Outsourcing allows companies to save overhead costs such as adding more equipment and space to accommodate and train additional employees.'),
                array('Set Up', 'We provide your offshore team with the space, facilities, equipment\'s and everything they need to get the job done.'),
                array('Extensive HR and Payroll Services', 'The Kings offers a comprehensive set of services that are designed to optimize our clients\' talent management, cost saving measures and improve your processes while also giving you full control on overseeing your offshore team.'),
            );
            foreach ($checklists as $i => $c) {
                $n = $i + 1;
                update_post_meta($page_id, 'slab_check' . $n . '_title', $c[0]);
                update_post_meta($page_id, '_slab_check' . $n . '_title', 'field_slab_check' . $n . '_title');
                update_post_meta($page_id, 'slab_check' . $n . '_desc', $c[1]);
                update_post_meta($page_id, '_slab_check' . $n . '_desc', 'field_slab_check' . $n . '_desc');
            }

            // Onboarding Journey
            update_post_meta($page_id, 'slab_onboard_step_label', 'Step 3');
            update_post_meta($page_id, '_slab_onboard_step_label', 'field_slab_onboard_step_label');
            update_post_meta($page_id, 'slab_onboard_title', 'What is involved in offshore Managed Staff Leasing to the Philippines?');
            update_post_meta($page_id, '_slab_onboard_title', 'field_slab_onboard_title');
            update_post_meta($page_id, 'slab_onboard_desc', 'Your team in the Philippines is legally employed and managed by The Kings but they report directly to you.');
            update_post_meta($page_id, '_slab_onboard_desc', 'field_slab_onboard_desc');

            $steps = array(
                array('Inquiry', 'Business Development'),
                array('Negotiation', 'Business Development'),
                array('Contract Signing', 'Business Development'),
                array('Alignment of Policies', 'People & Culture, Operations'),
                array('Manpower Requisition', 'People & Culture, Operations'),
                array('Verification of Hours', 'Operations'),
                array('Payment for Services', 'Accounting & Finance'),
                array('Satisfaction Survey', 'Audit & Business Dev'),
                array('Offering Other Services', 'Business Development'),
                array('Contract Renewal', 'Business Dev & People'),
            );
            foreach ($steps as $i => $s) {
                $n = $i + 1;
                update_post_meta($page_id, 'slab_step' . $n . '_title', $s[0]);
                update_post_meta($page_id, '_slab_step' . $n . '_title', 'field_slab_step' . $n . '_title');
                update_post_meta($page_id, 'slab_step' . $n . '_dept_label', $s[1]);
                update_post_meta($page_id, '_slab_step' . $n . '_dept_label', 'field_slab_step' . $n . '_dept_label');
            }

            // Comparison 2
            update_post_meta($page_id, 'slab_comp2_title', 'Difference Against BPO & Incorporation');
            update_post_meta($page_id, '_slab_comp2_title', 'field_slab_comp2_title');
            update_post_meta($page_id, 'slab_comp2_desc', 'See why Kings Managed Staff Leasing stands out against traditional outsourcing models.');
            update_post_meta($page_id, '_slab_comp2_desc', 'field_slab_comp2_desc');

            // CTA Banner
            update_post_meta($page_id, 'slab_cta_title', 'Ready to Build Your Offshore Team?');
            update_post_meta($page_id, '_slab_cta_title', 'field_slab_cta_title');
            update_post_meta($page_id, 'slab_cta_desc', 'Connect with our workforce specialists and get a customized labor management plan tailored to your business — at no obligation.');
            update_post_meta($page_id, '_slab_cta_desc', 'field_slab_cta_desc');
            update_post_meta($page_id, 'slab_cta_btn1', 'Get a Free Quote');
            update_post_meta($page_id, '_slab_cta_btn1', 'field_slab_cta_btn1');
            update_post_meta($page_id, 'slab_cta_btn2', 'Contact Us');
            update_post_meta($page_id, '_slab_cta_btn2', 'field_slab_cta_btn2');
        }

        // ─────────────────────────────────────────
        // 6. SERVICE KIT PAGE
        // ─────────────────────────────────────────
        if ($template === 'service-kit.php') {
            // Hero
            update_post_meta($page_id, 'skit_headline', 'HR & Kings Information Technology (KIT)');
            update_post_meta($page_id, '_skit_headline', 'field_skit_headline');
            update_post_meta($page_id, 'skit_desc', 'Proprietary Kings Information Technology System');
            update_post_meta($page_id, '_skit_desc', 'field_skit_desc');
            update_post_meta($page_id, 'skit_bg', kg_asset('img/service-kit/hero-kit.png'));
            update_post_meta($page_id, '_skit_bg', 'field_skit_bg');

            // HR & Payroll
            update_post_meta($page_id, 'skit_hr_title', 'HR & Payroll Management');
            update_post_meta($page_id, '_skit_hr_title', 'field_skit_hr_title');
            update_post_meta($page_id, 'skit_hr_desc', 'Human resource management, consulting and benefits administration are crucial aspects of the business that The Kings can manage for you. Payroll and HR Experts from The Kings, who are familiar with the local laws and taxations will handle your employees so you can focus on the revenue-generating activities of your business. They can either be placed in your office or work from our own corporate offices in Parañaque City.');
            update_post_meta($page_id, '_skit_hr_desc', 'field_skit_hr_desc');
            update_post_meta($page_id, 'skit_hr_list', "Recruitment, Selection and Deployment\nOrientation and Training\nTimekeeping and Payroll\nCompensation Programs\nManagement and Legal Processes");
            update_post_meta($page_id, '_skit_hr_list', 'field_skit_hr_list');
            update_post_meta($page_id, 'skit_hr_img', kg_asset('img/service-labor/kings-labor-content1.JPG'));
            update_post_meta($page_id, '_skit_hr_img', 'field_skit_hr_img');

            // KIT System
            update_post_meta($page_id, 'skit_kit_title', 'Kings Information Technology (KIT)');
            update_post_meta($page_id, '_skit_kit_title', 'field_skit_kit_title');
            update_post_meta($page_id, 'skit_kit_desc', "Kings Information Technology is a software the company aimed to create offering the best solution for the Philippines HR demands— Philippines has a great need for a localized software that is why KIT was born.\n\nOur goal is to help companies in the Philippines grow through our suite of backend solutions that address payroll, HR and recruitment challenges.");
            update_post_meta($page_id, '_skit_kit_desc', 'field_skit_kit_desc');
            update_post_meta($page_id, 'skit_intro_img1', kg_asset('img/service-kit/skit-intro-img1.png'));
            update_post_meta($page_id, '_skit_intro_img1', 'field_skit_intro_img1');

            // How We Work
            update_post_meta($page_id, 'skit_hww_title', 'HOW WE WORK');
            update_post_meta($page_id, '_skit_hww_title', 'field_skit_hww_title');
            update_post_meta($page_id, 'skit_hww_desc', 'Our work structure is uniquely tailored to a process that involves accountability, transparency and drive from all our teams. The Kings practices the flexibility of continuously adapting to changes and trends in the industry focusing on the delivering of quality product for our client’s satisfaction. We make sure we deliver on-time, with the best quality, right at your fingertips.');
            update_post_meta($page_id, '_skit_hww_desc', 'field_skit_hww_desc');
            update_post_meta($page_id, 'skit_hww_list', "Time and Attendance Monitoring\nPayslip Generation (Online Viewing)\nGovernment mandated remittances and reports\nUpdated Report on Payroll and Tax\nHandling Labor Management related issues\nEmployer access to Employees' Time record");
            update_post_meta($page_id, '_skit_hww_list', 'field_skit_hww_list');
            update_post_meta($page_id, 'skit_hww_img', kg_asset('img/service-kit/how-we-work.JPG'));
            update_post_meta($page_id, '_skit_hww_img', 'field_skit_hww_img');

            // Moving Forward
            update_post_meta($page_id, 'skit_mf_title', 'Moving Forward');
            update_post_meta($page_id, '_skit_mf_title', 'field_skit_mf_title');
            update_post_meta($page_id, 'skit_mf_desc', 'The Kings has a smooth track record and an expert in the said industry for over 10 years. We will be glad to meet with you, personally or virtually, to clarify any concern and work on the engagement that fits your current and future requirements.');
            update_post_meta($page_id, '_skit_mf_desc', 'field_skit_mf_desc');
        }

        if ($template === 'network.php') {
            update_post_meta($page_id, 'net_headline', 'Our Global Network');
            update_post_meta($page_id, '_net_headline', 'field_net_headline');
            update_post_meta($page_id, 'net_desc', 'Serving over 10,000 members and integrating with world-class clients across industries.');
            update_post_meta($page_id, '_net_desc', 'field_net_desc');
            update_post_meta($page_id, 'net_bg', 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=2000&q=80');
            update_post_meta($page_id, '_net_bg', 'field_net_bg');


            // Engagements Section Defaults
            update_post_meta($page_id, 'net_engagements_title', 'Industry Engagements');
            update_post_meta($page_id, '_net_engagements_title', 'field_net_eng_title');
            update_post_meta($page_id, 'net_engagements_subtitle', 'We place highly trained professionals across a vast spectrum of industries — bridging talent with global opportunity.');
            update_post_meta($page_id, '_net_engagements_subtitle', 'field_net_eng_subtitle');

            $cards_defaults = array(
                1 => array(
                    'cat' => 'Professional',
                    'title' => 'Professional Services',
                    'desc' => 'Certified experts across finance, healthcare, and technical disciplines for high-responsibility roles.',
                    'img' => 'img/jobs_professional.webp',
                    'tags' => "Accountant\nArchitect\nAuditor\nEngineer\nDentist\nNurse\nPharmacist\nTherapist"
                ),
                2 => array(
                    'cat' => 'Wellness',
                    'title' => 'Wellness & Beauty',
                    'desc' => 'Skilled practitioners delivering premium personal care and therapeutic wellness experiences.',
                    'img' => 'img/jobs_wellness.webp',
                    'tags' => "Therapist\nBeautician\nNail Technician\nManicurist\nPedicurist"
                ),
                3 => array(
                    'cat' => 'Medical',
                    'title' => 'Medical & Caregiving',
                    'desc' => 'Compassionate and trained medical support staff providing essential day-to-day care.',
                    'img' => 'img/jobs_medical.webp',
                    'tags' => "Medical Assistant\nCare Giver"
                ),
                4 => array(
                    'cat' => 'Business',
                    'title' => 'Business & Industry',
                    'desc' => 'Operational backbone staff keeping businesses running smoothly with precision and professionalism.',
                    'img' => 'img/jobs_business.webp',
                    'tags' => "Clerk\nCustomer Service Rep.\nStaff\nSupervisors"
                ),
                5 => array(
                    'cat' => 'Hospitality',
                    'title' => 'Food & Beverage',
                    'desc' => 'Hospitality-trained talent for restaurants, hotels, and luxury dining establishments worldwide.',
                    'img' => 'img/jobs_food_beverage.webp',
                    'tags' => "Waiter\nBartender\nChef\nKitchen Steward\nButler\nBaker"
                ),
                6 => array(
                    'cat' => 'Sales',
                    'title' => 'Sales & Services',
                    'desc' => 'Front-line service professionals driving customer satisfaction and sales performance across retail and events.',
                    'img' => 'img/jobs_sales_services.webp',
                    'tags' => "Service Crew\nCashier\nPromodiser\nMerchandiser\nCall Center Agent\nHousekeeping\nEvent Organizer"
                ),
                7 => array(
                    'cat' => 'Skilled Trades',
                    'title' => 'Skilled Trades & Construction',
                    'desc' => 'Certified tradespeople and construction professionals delivering quality workmanship on projects of every scale.',
                    'img' => 'img/jobs_skilled_construction.webp',
                    'tags' => "Carpenter\nMason\nWelder\nPlumber\nIndustrial Electrician\nPainter\nSafety Officer\nDriver\nMechanic\nProduction Worker"
                )
            );

            foreach ($cards_defaults as $n => $c) {
                $img_url = kg_asset($c['img']);
                update_post_meta($page_id, 'net_card' . $n . '_category', $c['cat']);
                update_post_meta($page_id, '_net_card' . $n . '_category', 'field_net_card_' . $n . '_category');
                update_post_meta($page_id, 'net_card' . $n . '_title', $c['title']);
                update_post_meta($page_id, '_net_card' . $n . '_title', 'field_net_card_' . $n . '_title');
                update_post_meta($page_id, 'net_card' . $n . '_img', $img_url);
                update_post_meta($page_id, '_net_card' . $n . '_img', 'field_net_card_' . $n . '_img');
                update_post_meta($page_id, 'net_card' . $n . '_desc', $c['desc']);
                update_post_meta($page_id, '_net_card' . $n . '_desc', 'field_net_card_' . $n . '_desc');
                update_post_meta($page_id, 'net_card' . $n . '_tags', $c['tags']);
                update_post_meta($page_id, '_net_card' . $n . '_tags', 'field_net_card_' . $n . '_tags');
            }

            // CTA Defaults
            update_post_meta($page_id, 'net_cta_title', 'Ready to Build Your Team?');
            update_post_meta($page_id, '_net_cta_title', 'field_net_cta_title');
            update_post_meta($page_id, 'net_cta_desc', 'Connect with our workforce specialists and get a custom deployment plan tailored to your business.');
            update_post_meta($page_id, '_net_cta_desc', 'field_net_cta_desc');
        }

        // ─────────────────────────────────────────
        // 8. QUOTE / TEAM BUILDER PAGE
        // ─────────────────────────────────────────
        if ($template === 'quote.php') {
            update_post_meta($page_id, 'quote_headline', 'Build Your Offshore Team');
            update_post_meta($page_id, '_quote_headline', 'field_quote_headline');
            update_post_meta($page_id, 'quote_desc', 'Select the roles you need, adjust headcount and experience levels, and receive an instant transparent estimate of your monthly cooperative investment.');
            update_post_meta($page_id, '_quote_desc', 'field_quote_desc');
            update_post_meta($page_id, 'quote_bg', kg_asset('img/quote/hero-quote.JPG'));
            update_post_meta($page_id, '_quote_bg', 'field_quote_bg');
            update_post_meta($page_id, 'quote_b_title', 'Estimate Your Monthly Investment');
            update_post_meta($page_id, '_quote_b_title', 'field_quote_b_title');
            update_post_meta($page_id, 'quote_calc_instructions', 'Select roles, adjust experience levels, and see a transparent baseline for your offshore team investment.');
            update_post_meta($page_id, '_quote_calc_instructions', 'field_quote_calc_instructions');
        }

        // ─────────────────────────────────────────
        // 9. CONTACT PAGE
        // ─────────────────────────────────────────
        if ($template === 'contact.php') {
            update_post_meta($page_id, 'contact_headline', 'Contact Us');
            update_post_meta($page_id, '_contact_headline', 'field_contact_headline');
            update_post_meta($page_id, 'contact_desc', 'We are here to help. Reach out to our team for any inquiries about staffing, membership, or partnership.');
            update_post_meta($page_id, '_contact_desc', 'field_contact_desc');
            update_post_meta($page_id, 'contact_bg', 'https://images.unsplash.com/photo-1497366754035-f200968a6e72?auto=format&fit=crop&w=2000&q=80');
            update_post_meta($page_id, '_contact_bg', 'field_contact_bg');

            update_post_meta($page_id, 'contact_form_title_ph', 'Your Trusted Partner for Reliable Manpower Solutions');
            update_post_meta($page_id, '_contact_form_title_ph', 'field_contact_form_title_ph');
            update_post_meta($page_id, 'contact_form_desc_ph', 'Helping businesses find qualified, reliable, and job-ready manpower across a wide range of industries, Kings Group has been connecting employers with skilled Filipino talent since 1999.');
            update_post_meta($page_id, '_contact_form_desc_ph', 'field_contact_form_desc_ph');

            update_post_meta($page_id, 'contact_form_title_intl', 'Scale Your Business With Elite Talent');
            update_post_meta($page_id, '_contact_form_title_intl', 'field_contact_form_title_intl');
            update_post_meta($page_id, 'contact_form_desc_intl', 'Unlock high-performance offshore staffing solutions from the Philippines. Connect with our experts to build your dedicated global team.');
            update_post_meta($page_id, '_contact_form_desc_intl', 'field_contact_form_desc_intl');

            update_post_meta($page_id, 'contact_form_shortcode', '[contact-form-7 id="123" title="Contact form 1"]');
            update_post_meta($page_id, '_contact_form_shortcode', 'field_contact_form_shortcode');

            update_post_meta($page_id, 'contact_info_title', 'Contact Us');
            update_post_meta($page_id, '_contact_info_title', 'field_contact_info_title');
            update_post_meta($page_id, 'contact_telephone', '+63 (2) 87766712');
            update_post_meta($page_id, '_contact_telephone', 'field_contact_telephone');
            update_post_meta($page_id, 'contact_mobile', '+63 (917) 634 2088 / +63 (917) 710 3221');
            update_post_meta($page_id, '_contact_mobile', 'field_contact_mobile');
            update_post_meta($page_id, 'contact_email', 'info@kingsgroup.com.ph');
            update_post_meta($page_id, '_contact_email', 'field_contact_email');

            update_post_meta($page_id, 'contact_visit_title', 'Visit Us');
            update_post_meta($page_id, '_contact_visit_title', 'field_contact_visit_title');
            update_post_meta($page_id, 'contact_address', 'DVN Building, Melaño Calixto St, Zamboanga City, Zamboanga del Sur');
            update_post_meta($page_id, '_contact_address', 'field_contact_address');
            update_post_meta($page_id, 'contact_address_2', '100 Doña Soledad Avenue, Better Living, Paranaque City, Metro Manila, Philippines, 1711');
            update_post_meta($page_id, '_contact_address_2', 'field_contact_address_2');
        }

        // ─────────────────────────────────────────
        // 10. COMMUNITY PAGE
        // ─────────────────────────────────────────
        if ($template === 'page-community.php') {
            $fields = array(
                'comm_hero_title' => 'Our Commitment to Community',
                'comm_welcome_text' => 'Welcome to The KINGS — Find great opportunities now!',
                'comm_queens_title' => 'Queens of Kings Group',
                'comm_scholar_desc' => 'The Kings Group supports the aspirations of its members and their dependents by providing scholarships to ensure sustainable futures.',
                'comm_culinary_desc' => 'A TESDA-accredited and certified institution built to provide sustainable education and livelihood programs.',
            );
            foreach ($fields as $key => $val) {
                update_post_meta($page_id, $key, $val);
                update_post_meta($page_id, '_' . $key, 'field_' . $key);
            }
        }
        // ─────────────────────────────────────────
        // 11. NEWS PAGE
        // ─────────────────────────────────────────
        if ($title === 'News') {
            update_option('page_for_posts', $page_id);
        }

        // ─────────────────────────────────────────
        // 12. COMMUNITY PAGE
        // ─────────────────────────────────────────
        if ($template === 'community.php') {
            update_post_meta($page_id, 'comm_hero_title', 'Our Commitment to Community');
            update_post_meta($page_id, '_comm_hero_title', 'field_comm_hero_title');
            update_post_meta($page_id, 'comm_hero_desc', 'Building a sustainable future through education, empowerment, and shared success.');
            update_post_meta($page_id, '_comm_hero_desc', 'field_comm_hero_desc');
            update_post_meta($page_id, 'comm_impact_intro', 'Community is essential to our mission and it is our responsibility to support the aspirations of our members by providing scholarships to our members and their dependents.');
            update_post_meta($page_id, '_comm_impact_intro', 'field_comm_impact_intro');
            update_post_meta($page_id, 'comm_stat1_num', '500+');
            update_post_meta($page_id, '_comm_stat1_num', 'field_comm_stat1_num');
            update_post_meta($page_id, 'comm_stat1_label', 'Scholarships Awarded');
            update_post_meta($page_id, '_comm_stat1_label', 'field_comm_stat1_label');
            update_post_meta($page_id, 'comm_stat2_num', '100%');
            update_post_meta($page_id, '_comm_stat2_num', 'field_comm_stat2_num');
            update_post_meta($page_id, 'comm_stat2_label', 'Member Focused');
            update_post_meta($page_id, '_comm_stat2_label', 'field_comm_stat2_label');
            update_post_meta($page_id, 'comm_impact_img', kg_asset('img/community/community-impact.JPG'));
            update_post_meta($page_id, '_comm_impact_img', 'field_comm_impact_img');
            update_post_meta($page_id, 'comm_queens_title', 'Queens of Kings Group');
            update_post_meta($page_id, '_comm_queens_title', 'field_comm_queens_title');
            update_post_meta($page_id, 'comm_queens_desc', 'Dedicated to empowering women within the Kings Group network through specialized resources, mentorship, and support structures designed for professional and personal growth.');
            update_post_meta($page_id, '_comm_queens_desc', 'field_comm_queens_desc');
            update_post_meta($page_id, 'comm_queens_img', kg_asset('img/community/queens-of-kingsgroup.png'));
            update_post_meta($page_id, '_comm_queens_img', 'field_comm_queens_img');
            update_post_meta($page_id, 'comm_culinary_tag', 'Education');
            update_post_meta($page_id, '_comm_culinary_tag', 'field_comm_culinary_tag');
            update_post_meta($page_id, 'comm_culinary_intro', 'We built Home Culinary and Technical School to have a sustainable education and livelihood programs for our members and their families.');
            update_post_meta($page_id, '_comm_culinary_intro', 'field_comm_culinary_intro');
            update_post_meta($page_id, 'comm_culinary_desc', 'As The Kings expands, so does our scholarship program with Home Culinary and Technical School. We are TESDA accredited and certified.');
            update_post_meta($page_id, '_comm_culinary_desc', 'field_comm_culinary_desc');
            update_post_meta($page_id, 'comm_culinary_img', kg_asset('img/community/community-culinary.png'));
            update_post_meta($page_id, '_comm_culinary_img', 'field_comm_culinary_img');
            update_post_meta($page_id, 'comm_hero_bg', kg_asset('img/community/hero-community.png'));
            update_post_meta($page_id, '_comm_hero_bg', 'field_comm_hero_bg');
        }
        if ($template === 'news.php' || $template === 'index.php') {
            update_post_meta($page_id, 'news_headline', 'Kings Group Newsroom');
            update_post_meta($page_id, '_news_headline', 'field_news_headline');
            update_post_meta($page_id, 'news_desc', 'Corporate insights, upcoming events, and stories of cooperative success.');
            update_post_meta($page_id, '_news_desc', 'field_news_desc');
            update_post_meta($page_id, 'news_bg', kg_asset('img/community/hero-community.png'));
            update_post_meta($page_id, '_news_bg', 'field_news_bg');
        }
        if ($template === 'terms.php') {
            update_post_meta($page_id, 'terms_headline', 'Terms of Service');
            update_post_meta($page_id, '_terms_headline', 'field_terms_headline');
            update_post_meta($page_id, 'terms_desc', 'Last updated: ' . date('F j, Y'));
            update_post_meta($page_id, '_terms_desc', 'field_terms_desc');
            update_post_meta($page_id, 'terms_bg', 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=2000&q=80');
            update_post_meta($page_id, '_terms_bg', 'field_terms_bg');
        }
        if ($template === 'privacy.php') {
            update_post_meta($page_id, 'privacy_headline', 'Privacy Policy');
            update_post_meta($page_id, '_privacy_headline', 'field_privacy_headline');
            update_post_meta($page_id, 'privacy_desc', 'Last updated: ' . date('F j, Y'));
            update_post_meta($page_id, '_privacy_desc', 'field_privacy_desc');
            update_post_meta($page_id, 'privacy_bg', 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=2000&q=80');
            update_post_meta($page_id, '_privacy_bg', 'field_privacy_bg');
        }
        if ($template === 'trust-safety.php') {
            update_post_meta($page_id, 'trust_headline', 'Trust & Safety');
            update_post_meta($page_id, '_trust_headline', 'field_trust_headline');
            update_post_meta($page_id, 'trust_desc', 'Our commitments to absolute compliance and ethical talent management.');
            update_post_meta($page_id, '_trust_desc', 'field_trust_desc');
            update_post_meta($page_id, 'trust_bg', 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=2000&q=80');
            update_post_meta($page_id, '_trust_bg', 'field_trust_bg');
        }
    }

    // Global Options
    if (function_exists('update_field')) {
        update_field('footer_description', 'Empowering global teams with ethical Philippine talent through a worker-owned cooperative model.', 'option');
        update_field('social_fb', 'https://www.facebook.com/kingsgroup', 'option');
        update_field('social_tw', 'https://twitter.com/kingsgroup', 'option');
        update_field('social_li', 'https://www.linkedin.com/company/kingsgroup', 'option');
    }

    // Default Jobs for Team Builder
    $existing_jobs = get_posts(array('post_type' => 'jobs', 'numberposts' => 1));
    if (empty($existing_jobs)) {
        $default_jobs = array(
            array('Software Developer', 'Frontend, Backend, and Full Stack development', 2000),
            array('Operations Head', 'Strategic oversight and operational management', 2500),
            array('Customer Service Rep', 'Inbound/outbound support and client relationship management', 800),
            array('Data Analyst', 'Business intelligence, reporting, and data visualization', 1400),
            array('Graphic Designer', 'Brand identity, digital assets, and visual communication', 900),
            array('Virtual Assistant', 'Administrative support, scheduling, and correspondence', 700),
            array('Digital Marketing Exec', 'SEO, social media, paid ads, and content strategy', 1100),
            array('Accountant', 'Bookkeeping, financial reporting, and compliance', 1200),
        );
        $job_images = array(
            'Software Developer' => 'https://images.unsplash.com/photo-1607799279861-4dd421887fb3?auto=format&fit=crop&w=600&q=80',
            'Operations Head' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=600&q=80',
            'Customer Service Rep' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=600&q=80',
            'Data Analyst' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=600&q=80',
            'Graphic Designer' => 'https://images.unsplash.com/photo-1561070791-26c113006238?auto=format&fit=crop&w=600&q=80',
            'Virtual Assistant' => 'https://images.unsplash.com/photo-1521737711867-e3b97375f902?auto=format&fit=crop&w=600&q=80',
            'Digital Marketing Exec' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=600&q=80',
            'Accountant' => 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?auto=format&fit=crop&w=600&q=80',
        );
        $job_depts = array(
            'Software Developer' => 'Technology',
            'Operations Head' => 'Operations',
            'Customer Service Rep' => 'Customer Support',
            'Data Analyst' => 'Technology',
            'Graphic Designer' => 'Creative',
            'Virtual Assistant' => 'Administrative',
            'Digital Marketing Exec' => 'Marketing',
            'Accountant' => 'Finance',
        );
        foreach ($default_jobs as $job) {
            $title = $job[0];
            $job_id = wp_insert_post(array(
                'post_title' => $title,
                'post_status' => 'publish',
                'post_type' => 'jobs',
                'post_excerpt' => $job[1],
            ));

            $img = isset($job_images[$title]) ? $job_images[$title] : 'https://images.unsplash.com/photo-1600880292203-757bb62b4baf?auto=format&fit=crop&w=600&q=80';
            $dept = isset($job_depts[$title]) ? $job_depts[$title] : 'General';
            $is_remote = ($title === 'Software Developer' || $title === 'Virtual Assistant' || $title === 'Graphic Designer');

            update_post_meta($job_id, 'base_price', $job[2]);
            update_post_meta($job_id, '_base_price', 'field_job_base_price');
            update_post_meta($job_id, 'include_in_team_builder', 1);
            update_post_meta($job_id, '_include_in_team_builder', 'field_job_include_team_builder');
            update_post_meta($job_id, 'job_card_image', $img);
            update_post_meta($job_id, '_job_card_image', 'field_job_card_image');
            update_post_meta($job_id, 'job_location', $is_remote ? 'Remote, Philippines' : 'Parañaque, Metro Manila');
            update_post_meta($job_id, '_job_location', 'field_job_location');
            update_post_meta($job_id, 'job_type', $is_remote ? 'CONTRACTOR' : 'FULL_TIME');
            update_post_meta($job_id, '_job_type', 'field_job_type');
            update_post_meta($job_id, 'job_work_setup', $is_remote ? 'WFH' : 'WFO');
            update_post_meta($job_id, '_job_work_setup', 'field_job_work_setup');
            update_post_meta($job_id, 'job_department', $dept);
            update_post_meta($job_id, '_job_department', 'field_job_department');
            update_post_meta($job_id, 'job_target_headcount', 5);
            update_post_meta($job_id, '_job_target_headcount', 'field_job_target_headcount');
            update_post_meta($job_id, 'job_filled_headcount', 1);
            update_post_meta($job_id, '_job_filled_headcount', 'field_job_filled_headcount');
        }
        // ─────────────────────────────────────────
        // 13. OUR JOBS PAGE
        // ─────────────────────────────────────────
        if ($template === 'our-jobs.php') {
            update_post_meta($page_id, 'jobs_hero_headline', 'Our Jobs');
            update_post_meta($page_id, '_jobs_hero_headline', 'field_jobs_hero_headline');
            update_post_meta($page_id, 'jobs_hero_desc', 'Find your next opportunity at one of the Philippines\' most people-first cooperatives.');
            update_post_meta($page_id, '_jobs_hero_desc', 'field_jobs_hero_desc');
            update_post_meta($page_id, 'jobs_hero_bg', 'https://images.unsplash.com/photo-1521737711867-e3b97375f902?auto=format&fit=crop&w=2000&q=80');
            update_post_meta($page_id, '_jobs_hero_bg', 'field_jobs_hero_bg');
        }
    }

    // ─────────────────────────────────────────
    // CSV JOBS MIGRATION (June 2026 Execom Report)
    // Add these jobs if they don't already exist
    // ─────────────────────────────────────────
    $csv_jobs = array(
        // ── Food Industry ─────────────────────────────────────────────────────
        array('title' => 'Service Crew', 'desc' => 'Food service and customer-facing operations', 'price' => 700, 'dept' => 'Food Industry', 'location' => 'Metro Manila'),
        array('title' => 'Service Crew', 'desc' => 'Food service and customer-facing operations', 'price' => 700, 'dept' => 'Food Industry', 'location' => 'Imus'),
        array('title' => 'Service Crew', 'desc' => 'Food service and customer-facing operations', 'price' => 700, 'dept' => 'Food Industry', 'location' => 'Lima'),
        array('title' => 'Service Crew', 'desc' => 'Food service and customer-facing operations', 'price' => 700, 'dept' => 'Food Industry', 'location' => 'Malolos, Bulacan'),
        array('title' => 'Service Crew', 'desc' => 'Food service and customer-facing operations', 'price' => 700, 'dept' => 'Food Industry', 'location' => 'Galleria'),
        array('title' => 'Waiter', 'desc' => 'Table service and dining experience management', 'price' => 700, 'dept' => 'Food Industry', 'location' => 'Alabang'),
        array('title' => 'Dining Staff', 'desc' => 'Restaurant and dining service operations', 'price' => 700, 'dept' => 'Food Industry', 'location' => 'Eastwood'),
        // ── Sales Industry — Singer Promodiser ────────────────────────────────
        array('title' => 'Singer Promodiser', 'desc' => 'In-store product demonstration and promotional sales', 'price' => 800, 'dept' => 'Sales', 'location' => 'Metro Manila'),
        array('title' => 'Singer Promodiser', 'desc' => 'In-store product demonstration and promotional sales', 'price' => 800, 'dept' => 'Sales', 'location' => 'Batangas'),
        array('title' => 'Singer Promodiser', 'desc' => 'In-store product demonstration and promotional sales', 'price' => 800, 'dept' => 'Sales', 'location' => 'Laguna'),
        array('title' => 'Singer Promodiser', 'desc' => 'In-store product demonstration and promotional sales', 'price' => 800, 'dept' => 'Sales', 'location' => 'Pampanga'),
        array('title' => 'Singer Promodiser', 'desc' => 'In-store product demonstration and promotional sales', 'price' => 800, 'dept' => 'Sales', 'location' => 'Bamban, Tarlac'),
        array('title' => 'Singer Promodiser', 'desc' => 'In-store product demonstration and promotional sales', 'price' => 800, 'dept' => 'Sales', 'location' => 'Bulacan'),
        array('title' => 'Singer Promodiser', 'desc' => 'In-store product demonstration and promotional sales', 'price' => 800, 'dept' => 'Sales', 'location' => 'Tanauan, Batangas'),
        array('title' => 'Singer Promodiser', 'desc' => 'In-store product demonstration and promotional sales', 'price' => 800, 'dept' => 'Sales', 'location' => 'Pangasinan'),
        array('title' => 'Singer Promodiser', 'desc' => 'In-store product demonstration and promotional sales', 'price' => 800, 'dept' => 'Sales', 'location' => 'Tuguegarao'),
        array('title' => 'Singer Promodiser', 'desc' => 'In-store product demonstration and promotional sales', 'price' => 800, 'dept' => 'Sales', 'location' => 'Cebu'),
        array('title' => 'Singer Promodiser', 'desc' => 'In-store product demonstration and promotional sales', 'price' => 800, 'dept' => 'Sales', 'location' => 'Harrison, Baguio'),
        array('title' => 'Singer Promodiser', 'desc' => 'In-store product demonstration and promotional sales', 'price' => 800, 'dept' => 'Sales', 'location' => 'Iriga, Camarines'),
        array('title' => 'Singer Promodiser', 'desc' => 'In-store product demonstration and promotional sales', 'price' => 800, 'dept' => 'Sales', 'location' => 'Cabanatuan'),
        array('title' => 'Singer Promodiser', 'desc' => 'In-store product demonstration and promotional sales', 'price' => 800, 'dept' => 'Sales', 'location' => 'Tagum'),
        array('title' => 'Singer Promodiser', 'desc' => 'In-store product demonstration and promotional sales', 'price' => 800, 'dept' => 'Sales', 'location' => 'Citistore Solano'),
        array('title' => 'Singer Promodiser', 'desc' => 'In-store product demonstration and promotional sales', 'price' => 800, 'dept' => 'Sales', 'location' => 'SACI Olongapo Central'),
        array('title' => 'Singer Promodiser', 'desc' => 'In-store product demonstration and promotional sales', 'price' => 800, 'dept' => 'Sales', 'location' => 'SACI Cabanatuan'),
        array('title' => 'Singer Promodiser', 'desc' => 'In-store product demonstration and promotional sales', 'price' => 800, 'dept' => 'Sales', 'location' => 'Abenson Batangas'),
        array('title' => 'Singer Promodiser', 'desc' => 'In-store product demonstration and promotional sales', 'price' => 800, 'dept' => 'Sales', 'location' => 'SACI Bacoor'),
        array('title' => 'Singer Promodiser', 'desc' => 'In-store product demonstration and promotional sales', 'price' => 800, 'dept' => 'Sales', 'location' => 'LCC Tabaco'),
        array('title' => 'Singer Promodiser', 'desc' => 'In-store product demonstration and promotional sales', 'price' => 800, 'dept' => 'Sales', 'location' => 'Abenson San Pascual, Batangas'),
        array('title' => 'Singer Promodiser', 'desc' => 'In-store product demonstration and promotional sales', 'price' => 800, 'dept' => 'Sales', 'location' => 'LCC Daraga, Albay'),
        array('title' => 'Singer Promodiser', 'desc' => 'In-store product demonstration and promotional sales', 'price' => 800, 'dept' => 'Sales', 'location' => 'LCC Naga, Camarines Sur'),
        array('title' => 'Singer Promodiser', 'desc' => 'In-store product demonstration and promotional sales', 'price' => 800, 'dept' => 'Sales', 'location' => 'SACI Tanza'),
        array('title' => 'Singer Promodiser', 'desc' => 'In-store product demonstration and promotional sales', 'price' => 800, 'dept' => 'Sales', 'location' => 'SACI Legazpi'),
        array('title' => 'Singer Promodiser', 'desc' => 'In-store product demonstration and promotional sales', 'price' => 800, 'dept' => 'Sales', 'location' => 'SACI Bacolod'),
        array('title' => 'Singer Promodiser', 'desc' => 'In-store product demonstration and promotional sales', 'price' => 800, 'dept' => 'Sales', 'location' => 'RA Kabankalan'),
        array('title' => 'Singer Promodiser', 'desc' => 'In-store product demonstration and promotional sales', 'price' => 800, 'dept' => 'Sales', 'location' => 'SSM Marilao'),
        array('title' => 'Singer Promodiser', 'desc' => 'In-store product demonstration and promotional sales', 'price' => 800, 'dept' => 'Sales', 'location' => 'SACI Iloilo City'),
        array('title' => 'Singer Promodiser', 'desc' => 'In-store product demonstration and promotional sales', 'price' => 800, 'dept' => 'Sales', 'location' => 'Citistore Midsayap'),
        array('title' => 'Singer Promodiser', 'desc' => 'In-store product demonstration and promotional sales', 'price' => 800, 'dept' => 'Sales', 'location' => 'Abenson Cotabato'),
        // ── Sales Industry — Other Roles ──────────────────────────────────────
        array('title' => 'Sales Staff', 'desc' => 'Retail sales and customer assistance', 'price' => 750, 'dept' => 'Sales', 'location' => 'Vermosa'),
        array('title' => 'Sales Staff', 'desc' => 'Retail sales and customer assistance', 'price' => 750, 'dept' => 'Sales', 'location' => 'Subic'),
        array('title' => 'Sales Staff', 'desc' => 'Retail sales and customer assistance', 'price' => 750, 'dept' => 'Sales', 'location' => 'Legazpi'),
        array('title' => 'Sales Staff', 'desc' => 'Retail sales and customer assistance', 'price' => 750, 'dept' => 'Sales', 'location' => 'Taguig'),
        array('title' => 'Sales Staff', 'desc' => 'Retail sales and customer assistance', 'price' => 750, 'dept' => 'Sales', 'location' => 'Cebu'),
        array('title' => 'Assistant OIC', 'desc' => 'Assists the Officer-in-Charge in store operations', 'price' => 900, 'dept' => 'Sales', 'location' => 'Cauayan'),
        array('title' => 'Assistant OIC', 'desc' => 'Assists the Officer-in-Charge in store operations', 'price' => 900, 'dept' => 'Sales', 'location' => 'MOA'),
        array('title' => 'Assistant OIC', 'desc' => 'Assists the Officer-in-Charge in store operations', 'price' => 900, 'dept' => 'Sales', 'location' => 'Makati'),
        array('title' => 'Assistant OIC', 'desc' => 'Assists the Officer-in-Charge in store operations', 'price' => 900, 'dept' => 'Sales', 'location' => 'Davao'),
        array('title' => 'Sales Associate', 'desc' => 'Customer engagement and product sales support', 'price' => 780, 'dept' => 'Sales', 'location' => 'Lipa'),
        array('title' => 'Sales Associate', 'desc' => 'Customer engagement and product sales support', 'price' => 780, 'dept' => 'Sales', 'location' => 'Pampanga'),
        array('title' => 'Sales Associate', 'desc' => 'Customer engagement and product sales support', 'price' => 780, 'dept' => 'Sales', 'location' => 'Baliwag'),
        array('title' => 'Sales Supervisor', 'desc' => 'Supervises sales team and monitors performance targets', 'price' => 1100, 'dept' => 'Sales', 'location' => 'Pampanga'),
        array('title' => 'Sales Supervisor', 'desc' => 'Supervises sales team and monitors performance targets', 'price' => 1100, 'dept' => 'Sales', 'location' => 'Baliwag'),
        array('title' => 'Sales Promodiser', 'desc' => 'Promotes and sells products directly to consumers', 'price' => 800, 'dept' => 'Sales', 'location' => 'Makati'),
        // ── Warehouse / Technical ──────────────────────────────────────────────
        array('title' => 'Electronics Technician', 'desc' => 'Repair and maintenance of electronic equipment', 'price' => 1100, 'dept' => 'Technical', 'location' => 'Taguig'),
        array('title' => 'Receiving/Scanner', 'desc' => 'Warehouse receiving, sorting, and inventory scanning', 'price' => 800, 'dept' => 'Warehouse', 'location' => 'Taguig'),
        array('title' => 'Quality Control', 'desc' => 'Product inspection and quality assurance', 'price' => 950, 'dept' => 'Warehouse', 'location' => 'Taguig'),
        array('title' => 'Packer', 'desc' => 'Product packing and order fulfillment', 'price' => 700, 'dept' => 'Warehouse', 'location' => 'Taguig'),
        array('title' => 'Logistics Rider', 'desc' => 'Last-mile delivery and logistics support', 'price' => 800, 'dept' => 'Logistics', 'location' => 'Taguig'),
        array('title' => 'Logistics Driver', 'desc' => 'Vehicle operation for freight and cargo delivery', 'price' => 900, 'dept' => 'Logistics', 'location' => 'Taguig'),
        array('title' => 'Logistics Helper', 'desc' => 'Assists in loading, unloading, and delivery tasks', 'price' => 700, 'dept' => 'Logistics', 'location' => 'Taguig'),
        array('title' => 'Multi-Skilled Technician', 'desc' => 'General repairs and facility maintenance support', 'price' => 1000, 'dept' => 'Technical', 'location' => 'Taguig'),
        array('title' => 'Aircon & Refrigeration Technician', 'desc' => 'Installation and repair of cooling systems', 'price' => 1200, 'dept' => 'Technical', 'location' => 'Taguig'),
        array('title' => 'Digital Marketing Associate', 'desc' => 'Social media, content creation, and online campaigns', 'price' => 1000, 'dept' => 'Marketing', 'location' => 'Taguig'),
        array('title' => 'Visual Merchandising Associate', 'desc' => 'In-store display design and product presentation', 'price' => 950, 'dept' => 'Creative', 'location' => 'Taguig'),
        array('title' => 'VM Graphic Artist', 'desc' => 'Visual merchandising graphics and layout design', 'price' => 1000, 'dept' => 'Creative', 'location' => 'Taguig'),
        array('title' => 'Merchandising Associate', 'desc' => 'Product placement, shelf management, and store audits', 'price' => 800, 'dept' => 'Merchandising', 'location' => 'Taguig'),
        array('title' => 'Graphic Artist', 'desc' => 'Visual design and graphic production for campaigns', 'price' => 950, 'dept' => 'Creative', 'location' => 'Taguig'),
        array('title' => 'Live Host', 'desc' => 'Live streaming product hosting and audience engagement', 'price' => 900, 'dept' => 'Sales', 'location' => 'Taguig'),
        array('title' => 'IT Support', 'desc' => 'Technical helpdesk and IT infrastructure support', 'price' => 1100, 'dept' => 'Technology', 'location' => 'Taguig'),
        array('title' => 'Driver', 'desc' => 'Vehicle operation for transport and delivery duties', 'price' => 850, 'dept' => 'Logistics', 'location' => 'Laguna'),
        array('title' => 'Helper', 'desc' => 'General labor and support for warehouse operations', 'price' => 650, 'dept' => 'Warehouse', 'location' => 'Bulacan'),
        // ── Merchandiser — per location ────────────────────────────────────────
        array('title' => 'Merchandiser', 'desc' => 'Product display, restocking, and retail merchandising', 'price' => 800, 'dept' => 'Merchandising', 'location' => 'Rizal'),
        array('title' => 'Merchandiser', 'desc' => 'Product display, restocking, and retail merchandising', 'price' => 800, 'dept' => 'Merchandising', 'location' => 'Makati'),
        array('title' => 'Merchandiser', 'desc' => 'Product display, restocking, and retail merchandising', 'price' => 800, 'dept' => 'Merchandising', 'location' => 'Pasay'),
        array('title' => 'Merchandiser', 'desc' => 'Product display, restocking, and retail merchandising', 'price' => 800, 'dept' => 'Merchandising', 'location' => 'Quezon City'),
        array('title' => 'Merchandiser', 'desc' => 'Product display, restocking, and retail merchandising', 'price' => 800, 'dept' => 'Merchandising', 'location' => 'Cebu'),
        array('title' => 'Merchandiser', 'desc' => 'Product display, restocking, and retail merchandising', 'price' => 800, 'dept' => 'Merchandising', 'location' => 'Manila'),
        array('title' => 'Merchandiser', 'desc' => 'Product display, restocking, and retail merchandising', 'price' => 800, 'dept' => 'Merchandising', 'location' => 'Bicol'),
        // ── Production ─────────────────────────────────────────────────────────
        array('title' => 'Production Helper', 'desc' => 'Support for production line and manufacturing tasks', 'price' => 700, 'dept' => 'Production', 'location' => 'Taguig'),
        array('title' => 'Messenger', 'desc' => 'Document delivery and inter-office courier service', 'price' => 700, 'dept' => 'Logistics', 'location' => 'Makati'),
        array('title' => 'Driver', 'desc' => 'Vehicle operation for transport and delivery duties', 'price' => 850, 'dept' => 'Logistics', 'location' => 'Taguig'),
        array('title' => 'Driver', 'desc' => 'Vehicle operation for transport and delivery duties', 'price' => 850, 'dept' => 'Logistics', 'location' => 'Laoag'),
        array('title' => 'Driver', 'desc' => 'Vehicle operation for transport and delivery duties', 'price' => 850, 'dept' => 'Logistics', 'location' => 'Mabalacat'),
        array('title' => 'Maintenance', 'desc' => 'Facility upkeep, repairs, and preventive maintenance', 'price' => 800, 'dept' => 'Technical', 'location' => 'Mabalacat'),
        array('title' => 'Warehouseman', 'desc' => 'Inventory management and warehouse operations', 'price' => 800, 'dept' => 'Warehouse', 'location' => 'Mabalacat'),
        array('title' => 'Operator', 'desc' => 'Machine or equipment operation in production settings', 'price' => 850, 'dept' => 'Production', 'location' => 'Dagupan'),
        array('title' => 'Maintenance', 'desc' => 'Facility upkeep, repairs, and preventive maintenance', 'price' => 800, 'dept' => 'Technical', 'location' => 'Dagupan'),
    );

    $generic_img = 'https://images.unsplash.com/photo-1600880292203-757bb62b4baf?auto=format&fit=crop&w=600&q=80';
    $csv_job_images = array(
        'Service Crew' => 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?auto=format&fit=crop&w=600&q=80',
        'Waiter' => 'https://images.unsplash.com/photo-1571903677502-a2fb8b5f4e6c?auto=format&fit=crop&w=600&q=80',
        'Singer Promodiser' => 'https://images.unsplash.com/photo-1559136555-9303baea8ebd?auto=format&fit=crop&w=600&q=80',
        'Sales Staff' => 'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?auto=format&fit=crop&w=600&q=80',
        'Sales Associate' => 'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?auto=format&fit=crop&w=600&q=80',
        'Sales Supervisor' => 'https://images.unsplash.com/photo-1519389950473-47ba0277781c?auto=format&fit=crop&w=600&q=80',
        'Merchandiser' => 'https://images.unsplash.com/photo-1542744094-3a31f272c490?auto=format&fit=crop&w=600&q=80',
        'Merchandising Associate' => 'https://images.unsplash.com/photo-1542744094-3a31f272c490?auto=format&fit=crop&w=600&q=80',
        'Electronics Technician' => 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&w=600&q=80',
        'Multi-Skilled Technician' => 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&w=600&q=80',
        'Aircon & Refrigeration Technician' => 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&w=600&q=80',
        'Logistics Rider' => 'https://images.unsplash.com/photo-1568727349530-9f9b4f1d9cf5?auto=format&fit=crop&w=600&q=80',
        'Logistics Driver' => 'https://images.unsplash.com/photo-1568727349530-9f9b4f1d9cf5?auto=format&fit=crop&w=600&q=80',
        'Driver' => 'https://images.unsplash.com/photo-1568727349530-9f9b4f1d9cf5?auto=format&fit=crop&w=600&q=80',
        'Digital Marketing Associate' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=600&q=80',
        'Visual Merchandising Associate' => 'https://images.unsplash.com/photo-1561070791-26c113006238?auto=format&fit=crop&w=600&q=80',
        'VM Graphic Artist' => 'https://images.unsplash.com/photo-1561070791-26c113006238?auto=format&fit=crop&w=600&q=80',
        'IT Support' => 'https://images.unsplash.com/photo-1607799279861-4dd421887fb3?auto=format&fit=crop&w=600&q=80',
        'Production Helper' => 'https://images.unsplash.com/photo-1504917595217-d4dc5ebe6122?auto=format&fit=crop&w=600&q=80',
        'Operator' => 'https://images.unsplash.com/photo-1504917595217-d4dc5ebe6122?auto=format&fit=crop&w=600&q=80',
        'Quality Control' => 'https://images.unsplash.com/photo-1563986768609-322da13575f3?auto=format&fit=crop&w=600&q=80',
        'Live Host' => 'https://images.unsplash.com/photo-1559136555-9303baea8ebd?auto=format&fit=crop&w=600&q=80',
    );

    foreach ($csv_jobs as $csv_job) {
        $csv_title = $csv_job['title'];
        $csv_loc = $csv_job['location'];

        $existing = get_posts(array(
            'post_type' => 'jobs',
            'meta_query' => array(
                'relation' => 'AND',
                array('key' => 'job_location', 'value' => $csv_loc),
            ),
            'title' => $csv_title,
            'numberposts' => 1,
            'post_status' => 'publish',
        ));

        if (!empty($existing)) {
            continue; // Skip — already exists
        }
        $new_job_id = wp_insert_post(array(
            'post_title' => $csv_title,
            'post_status' => 'publish',
            'post_type' => 'jobs',
            'post_excerpt' => $csv_job['desc'],
        ));
        if (!$new_job_id || is_wp_error($new_job_id)) {
            continue;
        }
        $img = isset($csv_job_images[$csv_title]) ? $csv_job_images[$csv_title] : $generic_img;

        update_post_meta($new_job_id, 'base_price', $csv_job['price']);
        update_post_meta($new_job_id, '_base_price', 'field_job_base_price');
        update_post_meta($new_job_id, 'include_in_team_builder', 1);
        update_post_meta($new_job_id, '_include_in_team_builder', 'field_job_include_team_builder');
        update_post_meta($new_job_id, 'job_card_image', $img);
        update_post_meta($new_job_id, '_job_card_image', 'field_job_card_image');
        update_post_meta($new_job_id, 'job_location', $csv_job['location']);
        update_post_meta($new_job_id, '_job_location', 'field_job_location');
        update_post_meta($new_job_id, 'job_type', 'FULL_TIME');
        update_post_meta($new_job_id, '_job_type', 'field_job_type');
        update_post_meta($new_job_id, 'job_work_setup', 'WFO');
        update_post_meta($new_job_id, '_job_work_setup', 'field_job_work_setup');
        update_post_meta($new_job_id, 'job_department', $csv_job['dept']);
        update_post_meta($new_job_id, '_job_department', 'field_job_department');
        update_post_meta($new_job_id, 'job_target_headcount', 5);
        update_post_meta($new_job_id, '_job_target_headcount', 'field_job_target_headcount');
        update_post_meta($new_job_id, 'job_filled_headcount', 1);
        update_post_meta($new_job_id, '_job_filled_headcount', 'field_job_filled_headcount');
    }

    update_option('kg_seeder_file_hash', $file_hash);
    flush_rewrite_rules();
}
add_action('init', 'kingsgroup_populate_all_pages');

/**
 * Helper: Find a page ID by its template filename.
 */
function kg_get_page_by_template($template_name)
{
    $pages = get_posts(array(
        'post_type' => 'page',
        'meta_key' => '_wp_page_template',
        'meta_value' => $template_name,
        'posts_per_page' => 1,
        'fields' => 'ids',
    ));
    return !empty($pages) ? $pages[0] : false;
}

