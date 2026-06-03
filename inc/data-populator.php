<?php
function kingsgroup_populate_all_pages() {
    if ( get_option( 'kg_full_site_populated_v13' ) ) { return; }

    $pages_to_create = array(
        'Home'             => 'front-page.php',
        'Our Story'        => 'story.php',
        'Careers'          => 'careers.php',
        'Team Builder'     => 'quote.php',
        'Member Benefits'  => 'benefits.php',
        'Labor Management' => 'service-labor.php',
        'HR Tech (KIT)'    => 'service-kit.php',
        'Our Network'      => 'network.php',
        'Our Jobs'         => 'our-jobs.php',
        'Contact Us'       => 'contact.php',
        'Community'        => 'community.php',
        'News'             => 'index.php',
        'Terms of Service' => 'terms.php',
        'Privacy Policy'   => 'privacy.php',
        'Trust & Safety'   => 'trust-safety.php'
    );

    foreach ($pages_to_create as $title => $template) {
        // Check by template first, then by title
        $page_id = kg_get_page_by_template($template);
        
        $is_new_page = false;
        if (!$page_id) {
            $existing_pages = get_posts(array(
                'post_type'   => 'page',
                'title'       => $title,
                'numberposts' => 1
            ));
            if (!empty($existing_pages)) {
                $page_id = $existing_pages[0]->ID;
            }
        }

        if (!$page_id) {
            $page_id = wp_insert_post(array(
                'post_title'    => $title,
                'post_status'   => 'publish',
                'post_type'     => 'page',
                'page_template' => $template
            ));
            $is_new_page = true;
        }

        // ─────────────────────────────────────────
        // 1. HOME PAGE
        // ─────────────────────────────────────────
        if ($template === 'front-page.php') {
            update_option('show_on_front', 'page');
            update_option('page_on_front', $page_id);

            // Hero
            update_post_meta($page_id, 'hero_headline',   'Elite Talent.<br><span>Ethical Staffing.</span>Exceptional Results.');
            update_post_meta($page_id, '_hero_headline',  'field_home_hero_headline');
            update_post_meta($page_id, 'hero_description', 'We connect global businesses with the Philippines\' top professionals. Established in 1999 as a worker-owned cooperative, our people aren\'t just staff—they are partners in your success.');
            update_post_meta($page_id, '_hero_description', 'field_home_hero_desc');
            // Hero images — pre-fill with placeholder Unsplash URLs (admin replaces with real photos)
            update_post_meta($page_id, 'hero_img_1',  'https://images.unsplash.com/photo-1600880292203-757bb62b4baf?auto=format&fit=crop&w=2000&q=80');
            update_post_meta($page_id, '_hero_img_1', 'field_home_hero_img_1');
            update_post_meta($page_id, 'hero_img_2',  'https://images.unsplash.com/photo-1556761175-4b46a572b786?auto=format&fit=crop&w=2000&q=80');
            update_post_meta($page_id, '_hero_img_2', 'field_home_hero_img_2');
            update_post_meta($page_id, 'hero_img_3',  'https://images.unsplash.com/photo-1521737711867-e3b97375f902?auto=format&fit=crop&w=2000&q=80');
            update_post_meta($page_id, '_hero_img_3', 'field_home_hero_img_3');

            // Trust Bar
            update_post_meta($page_id, 'trust_label',  'Trusted by leading organizations worldwide');
            update_post_meta($page_id, '_trust_label', 'field_home_trust_label');

            // Staffing Intro
            update_post_meta($page_id, 'home_intro_title',  'A Different Kind of Staffing');
            update_post_meta($page_id, '_home_intro_title', 'field_home_intro_title');
            update_post_meta($page_id, 'home_intro_sub',    'Bridging the gap between a traditional agency and a modern global talent platform to serve businesses and career-seekers alike.');
            update_post_meta($page_id, '_home_intro_sub',   'field_home_intro_sub');

            // Client Advantage
            update_post_meta($page_id, 'adv_headline',    'Your Dedicated Philippine HQ<br>Without the Overhead.');
            update_post_meta($page_id, '_adv_headline',   'field_home_adv_headline');
            update_post_meta($page_id, 'adv_subheadline', 'Stop "outsourcing" and start "building."');
            update_post_meta($page_id, '_adv_subheadline','field_home_adv_sub');
            update_post_meta($page_id, 'adv_desc',        'Access elite Filipino talent through a worker-owned cooperative with 10,000 members. Since 1999, every team member has been personally invested in your growth.');
            update_post_meta($page_id, '_adv_desc',       'field_home_adv_desc');
            update_post_meta($page_id, 'adv_stat',        '10000');
            update_post_meta($page_id, '_adv_stat',       'field_home_adv_stat');
            update_post_meta($page_id, 'adv_img',         'https://images.unsplash.com/photo-1556761175-4b46a572b786?auto=format&fit=crop&w=1200&q=80');
            update_post_meta($page_id, '_adv_img',        'field_home_adv_img');

            $adv_features = array(
                array('Owner-Level Commitment',       'They aren\'t just "working for a paycheck." They have a literal stake in the company\'s success.'),
                array('The "Zero-Hassle" Guarantee',  'We handle strict DOLE compliance, premium hardware, and operations so you can focus on leadership.'),
                array('Ethical Global Sourcing',      'Care about ESG? By hiring through Kings, you support an ethical, worker-centric business model that empowers the local community.'),
            );
            foreach ($adv_features as $i => $f) {
                $n = $i + 1;
                update_post_meta($page_id, 'adv_f'.$n.'_title',  $f[0]);
                update_post_meta($page_id, '_adv_f'.$n.'_title', 'field_home_adv_f'.$n.'_title');
                update_post_meta($page_id, 'adv_f'.$n.'_desc',   $f[1]);
                update_post_meta($page_id, '_adv_f'.$n.'_desc',  'field_home_adv_f'.$n.'_desc');
            }

            // Applicant Focus
            update_post_meta($page_id, 'app_headline',    '"Your Career,<br>Owned by You."');
            update_post_meta($page_id, '_app_headline',   'field_home_app_headline');
            update_post_meta($page_id, 'app_subheadline', 'Join a community where you are a member, not just a number.');
            update_post_meta($page_id, '_app_subheadline','field_home_app_sub');
            update_post_meta($page_id, 'app_desc',        'Get access to premium benefits, career coaching, and the stability of a worker-owned cooperative. It\'s built for you, by people like you.');
            update_post_meta($page_id, '_app_desc',       'field_home_app_desc');
            update_post_meta($page_id, 'app_img',         'https://images.unsplash.com/photo-1521737711867-e3b97375f902?auto=format&fit=crop&w=1200&q=80');
            update_post_meta($page_id, '_app_img',        'field_home_app_img');

            $app_features = array(
                array('Fast-Track Application', 'No long forms. Just drop your CV and let our recruiters find your perfect match in our global network.'),
                array('Kings Lending Access',   'Need a boost? Our internal lending program is exclusively designed to support our members\' major life milestones.'),
                array('Wealth Building',        'Build true financial security. Share in the true economic success we create together as a cooperative.'),
            );
            foreach ($app_features as $i => $f) {
                $n = $i + 1;
                update_post_meta($page_id, 'app_f'.$n.'_title',  $f[0]);
                update_post_meta($page_id, '_app_f'.$n.'_title', 'field_home_app_f'.$n.'_title');
                update_post_meta($page_id, 'app_f'.$n.'_desc',   $f[1]);
                update_post_meta($page_id, '_app_f'.$n.'_desc',  'field_home_app_f'.$n.'_desc');
            }

            // Testimonials
            update_post_meta($page_id, 'testi_title',    'What Our Members Say');
            update_post_meta($page_id, '_testi_title',   'field_home_testi_title');
            update_post_meta($page_id, 'testi_subtitle', 'Hear from the empowered professionals and cooperative members who have built their careers with Kings.');
            update_post_meta($page_id, '_testi_subtitle','field_home_testi_sub');

            $testimonials = array(
                array(
                    'Kings Group fundamentally transformed how we structure our customer service in Asia. The worker-owned model means our team operates with an unparalleled sense of ownership and dedication.',
                    'David K.', 'COO, Global Logistics Tech'
                ),
                array(
                    'Before Kings, my career was just a series of jobs. Now, as a member-owner, I have access to lending programs, real benefits, and a voice in how we operate. It\'s life-changing.',
                    'Maria S.', 'Senior Technical Support'
                ),
            );
            foreach ($testimonials as $i => $t) {
                $n = $i + 1;
                update_post_meta($page_id, 'testi_'.$n.'_quote', $t[0]);
                update_post_meta($page_id, '_testi_'.$n.'_quote','field_home_t'.$n.'_quote');
                update_post_meta($page_id, 'testi_'.$n.'_name',  $t[1]);
                update_post_meta($page_id, '_testi_'.$n.'_name', 'field_home_t'.$n.'_name');
                update_post_meta($page_id, 'testi_'.$n.'_role',  $t[2]);
                update_post_meta($page_id, '_testi_'.$n.'_role', 'field_home_t'.$n.'_role');
            }

            // Our Network
            update_post_meta($page_id, 'net_title',    'Our Network');
            update_post_meta($page_id, '_net_title',   'field_home_net_title');
            update_post_meta($page_id, 'net_subtitle', 'Explore our affiliated brands and communities.');
            update_post_meta($page_id, '_net_subtitle','field_home_net_desc');

            $brands = array(
                1 => array('title' => 'The Kings City', 'desc' => 'Our premier coworking and flex-office brand. We provide modern, inspiring workspaces designed to foster collaboration, innovation, and productivity for professionals in the heart of the business district.', 'link' => 'https://www.kings-city.com/', 'btn' => 'Discover Kings City', 'img' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=800&q=80'),
                2 => array('title' => 'The Social Manila', 'desc' => 'The premier events, lifestyle, and community engagement hub. We host corporate functions, team-building events, and exclusive gatherings designed to connect leaders and ignite culture.', 'link' => 'https://kingscity.com.ph/', 'btn' => 'Explore The Social', 'img' => 'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=800&q=80'),
                3 => array('title' => 'The Home Culinary School', 'desc' => 'Professional culinary training and certification programs. Equipping the next generation of chefs and hospitality professionals with world-class skills, discipline, and ethical standards.', 'link' => 'https://unique-souffle-78e15a.netlify.app/', 'btn' => 'Start Cooking', 'img' => 'https://images.unsplash.com/photo-1556910103-1c02745a872e?auto=format&fit=crop&w=800&q=80'),
            );
            foreach ($brands as $i => $b) {
                update_post_meta($page_id, 'net_brand'.$i.'_title', $b['title']);
                update_post_meta($page_id, '_net_brand'.$i.'_title', 'field_home_net_b'.$i.'_title');
                update_post_meta($page_id, 'net_brand'.$i.'_desc',  $b['desc']);
                update_post_meta($page_id, '_net_brand'.$i.'_desc',  'field_home_net_b'.$i.'_desc');
                update_post_meta($page_id, 'net_brand'.$i.'_link',  $b['link']);
                update_post_meta($page_id, '_net_brand'.$i.'_link',  'field_home_net_b'.$i.'_link');
                update_post_meta($page_id, 'net_brand'.$i.'_btn',   $b['btn']);
                update_post_meta($page_id, '_net_brand'.$i.'_btn',   'field_home_net_b'.$i.'_btn');
                update_post_meta($page_id, 'net_brand'.$i.'_img',   $b['img']);
                update_post_meta($page_id, '_net_brand'.$i.'_img',   'field_home_net_b'.$i.'_img');
            }
        }


        // ─────────────────────────────────────────
        // 2. STORY PAGE
        // ─────────────────────────────────────────
        if ($template === 'story.php') {
            // Hero
            update_post_meta($page_id, 'story_headline',  'Our Story');
            update_post_meta($page_id, '_story_headline', 'field_story_headline');
            update_post_meta($page_id, 'story_desc',      'A legacy formed on ethical practices, worker empowerment, and shared success since 1999.');
            update_post_meta($page_id, '_story_desc',     'field_story_desc');
            update_post_meta($page_id, 'story_bg',        'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=2000&q=80');
            update_post_meta($page_id, '_story_bg',       'field_story_bg');

            // Vision & Mission
            update_post_meta($page_id, 'story_vision_title',   'Vision & Mission');
            update_post_meta($page_id, '_story_vision_title',  'field_story_vision_title');
            update_post_meta($page_id, 'story_vision_text',    'To be the leading professional workforce provider with satisfied clients and secured members in the Philippines.');
            update_post_meta($page_id, '_story_vision_text',   'field_story_vision_text');
            update_post_meta($page_id, 'story_mission_text',   'Driven with passion, agility, and excellence in everything we do, we are committed to maintain a successful partnership with our clients as we also help with the needs and aspirations of our members.');
            update_post_meta($page_id, '_story_mission_text',  'field_story_mission_text');
            update_post_meta($page_id, 'story_mission_text_2', 'Staying true to our purpose, it is our mission to be a responsible member of the Cooperative Development Authority in contributing to the growth of the economy by continuously providing jobs.');
            update_post_meta($page_id, '_story_mission_text_2','field_story_mission_text_2');

            // Core Values
            update_post_meta($page_id, 'story_values_title',  'Core Values');
            update_post_meta($page_id, '_story_values_title', 'field_story_values_title');
            update_post_meta($page_id, 'story_values_intro',  'The principles that guide everything we do at Kings.');
            update_post_meta($page_id, '_story_values_intro', 'field_story_values_intro');

            $values = array(
                array('Member-Centric',  'Our members\' welfare is our top priority. We help improve the lives of our members by providing jobs that uplift their dignity.'),
                array('Truth',           'We strive to promote and implement truthful and transparent transactions with our Clients and Co-Members.'),
                array('Quality',         'What we do, we do well. Delivering only the best products and exceptional services for you.'),
                array('Value',           'Developing systems to ensure the best possible return on investment for our members, clients and other stakeholders.'),
                array('Integrity',       'Possessing and practicing fairness and objectivity and strong moral principles even in the most challenging situations.'),
                array('Excellence',      'Providing our clients with superb services and exceptional overall experience, exceeding expectations by working smart and hard.'),
                array('Innovation',      'Actively creative and open to all possibilities in meeting our clients\' unique needs.'),
                array('Accountability',  'Demonstrating dependability and personal ownership necessary in achieving desired results.'),
                array('Professionalism', 'Making sure that everyone who seeks our services is treated with respect, courtesy and efficiency.'),
            );
            foreach ($values as $i => $v) {
                $n = $i + 1;
                update_post_meta($page_id, 'story_v'.$n.'_title',  $v[0]);
                update_post_meta($page_id, '_story_v'.$n.'_title', 'field_story_v'.$n.'_title');
                update_post_meta($page_id, 'story_v'.$n.'_desc',   $v[1]);
                update_post_meta($page_id, '_story_v'.$n.'_desc',  'field_story_v'.$n.'_desc');
            }

            // Our Roots (Video)
            update_post_meta($page_id, 'story_timeline_title',  'Our Roots');
            update_post_meta($page_id, '_story_timeline_title', 'field_story_timeline_title');
            update_post_meta($page_id, 'story_timeline_intro',  'Since 1999, Kings has been redefining the staffing industry.');
            update_post_meta($page_id, '_story_timeline_intro', 'field_story_timeline_intro');
            update_post_meta($page_id, 'story_roots_video', 'https://www.youtube.com/watch?v=ScMzIvxBSi4');
            update_post_meta($page_id, '_story_roots_video', 'field_story_roots_video');

            // Leadership Team
            update_post_meta($page_id, 'story_team_title',  'Kings Team');
            update_post_meta($page_id, '_story_team_title', 'field_story_team_title');
            update_post_meta($page_id, 'story_team_intro',  'Meet the visionary leaders driving our cooperative.');
            update_post_meta($page_id, '_story_team_intro', 'field_story_team_intro');

            $leaders = array(
                array(
                    'name'  => 'Neil John S. Makasiar',
                    'role'  => 'Managing Director',
                    'creds' => "Director, Makenter Construction & Development Corp\nVice President, Human Services Cluster, CDA Region IX\nCharter Vice President, REBAP - Zamboanga City\nCorporate Secretary, ZC Tierra Verde Corporation\nFormer President, Happy Kings Corporation\nMember, Rotary Club of Makati\nBachelor's Degree, De La Salle University – Manila",
                    'img'   => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=800&q=80',
                ),
                array(
                    'name'  => 'Camille Navarro Makasiar',
                    'role'  => 'Founder and Executive Director',
                    'creds' => "Master in Entrep, Ateneo de Manila University GSB\nMember, Entrepreneurs Organization (EO) PH South Chapter\nTrustee, Bayan Innovation Group, Inc. & Bayan Academy\nCharter President, Inner Wheel Club of Metro Manila\nKnowledge Management, University of Oxford\nStrategic Management, Imperial College London\nBachelor's Degree, Southville International School & Colleges",
                    'img'   => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=800&q=80',
                ),
                array(
                    'name'  => 'Cory DV Navarro',
                    'role'  => 'Founder, Kings Group of Companies',
                    'creds' => "Hall of Famer, Manila's Best Dressed & Zamboanga's Best Dressed\nHuwarang Ina Awardee (2017) & Empowered Women of the PH\nCharter President, Ambassador Charter Club, Melbourne\nPast Chairman, PH National Red Cross – Zamboanga City\nPast President, Rotary Club of Makati EDSA\nMember, MAP & PH Chamber of Commerce and Industry\nBachelor's Degree, Pioneer Nursing Batch of Ateneo de Zamboanga",
                    'img'   => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=800&q=80',
                ),
            );
            foreach ($leaders as $i => $l) {
                $n = $i + 1;
                update_post_meta($page_id, 'story_leader'.$n.'_name',  $l['name']);
                update_post_meta($page_id, '_story_leader'.$n.'_name', 'field_story_leader'.$n.'_name');
                update_post_meta($page_id, 'story_leader'.$n.'_role',  $l['role']);
                update_post_meta($page_id, '_story_leader'.$n.'_role', 'field_story_leader'.$n.'_role');
                update_post_meta($page_id, 'story_leader'.$n.'_creds', $l['creds']);
                update_post_meta($page_id, '_story_leader'.$n.'_creds','field_story_leader'.$n.'_creds');
                update_post_meta($page_id, 'story_leader'.$n.'_img',   $l['img']);
                update_post_meta($page_id, '_story_leader'.$n.'_img',  'field_story_leader'.$n.'_img');
            }

            // Group of Companies
            update_post_meta($page_id, 'story_companies_title',  'Group of Companies');
            update_post_meta($page_id, '_story_companies_title', 'field_story_companies_title');
            update_post_meta($page_id, 'story_companies_intro',  'A unified ecosystem of businesses powering the Kings network.');
            update_post_meta($page_id, '_story_companies_intro', 'field_story_companies_intro');

            $companies = array(
                'Kings Cooperative', 'Kings Asia Pacific', 'Kings Lending', 'Makenter',
                'Home Culinary', 'Marian Palazz', 'Navishi Shell', 'Pacific Water',
                'Print Artist', 'RN Foundation', 'RPS Migration',
            );
            foreach ($companies as $i => $name) {
                $n = $i + 1;
                update_post_meta($page_id, 'story_co'.$n.'_name',  $name);
                update_post_meta($page_id, '_story_co'.$n.'_name', 'field_story_co'.$n.'_name');
                update_post_meta($page_id, 'story_co'.$n.'_img', 'https://images.unsplash.com/photo-1599305445671-ac291c95aba9?auto=format&fit=crop&w=300&q=80');
                update_post_meta($page_id, '_story_co'.$n.'_img', 'field_story_co'.$n.'_img');
            }
        }

        // ─────────────────────────────────────────
        // 3. CAREERS PAGE
        // ─────────────────────────────────────────
        if ($template === 'careers.php') {
            update_post_meta($page_id, 'careers_headline',  'Build Your Future<br><span style="color:var(--neutral-yellow);">Own Your Career</span>');
            update_post_meta($page_id, '_careers_headline', 'field_careers_headline');
            update_post_meta($page_id, 'careers_desc',      'Join the Philippines\' leading worker-owned cooperative. Get profit-sharing, career coaching, and a network of 10,000+ professionals.');
            update_post_meta($page_id, '_careers_desc',     'field_careers_desc');
            update_post_meta($page_id, 'careers_bg',        'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=2000&q=80');
            update_post_meta($page_id, '_careers_bg',       'field_careers_bg');
            update_post_meta($page_id, 'careers_form_title', 'Fast-Track Application');
            update_post_meta($page_id, '_careers_form_title','field_careers_form_title');
            update_post_meta($page_id, 'careers_form_desc',  'No long forms. Drop your CV and our recruiters will find your perfect match.');
            update_post_meta($page_id, '_careers_form_desc', 'field_careers_form_desc');
        }

        // ─────────────────────────────────────────
        // 4. BENEFITS PAGE
        // ─────────────────────────────────────────
        if ($template === 'benefits.php') {
            update_post_meta($page_id, 'benefits_headline',   'Why Join Kings?');
            update_post_meta($page_id, '_benefits_headline',  'field_benefits_headline');
            update_post_meta($page_id, 'benefits_desc',       'Experience a new standard of employment. At Kings Group, our cooperative model empowers members with comprehensive benefits, financial security, and shared success.');
            update_post_meta($page_id, '_benefits_desc',      'field_benefits_desc');
            update_post_meta($page_id, 'benefits_bg',         'https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=2000&q=80');
            update_post_meta($page_id, '_benefits_bg',        'field_benefits_bg');
            update_post_meta($page_id, 'benefits_list_title', 'The "Lucky 9" Benefits');
            update_post_meta($page_id, '_benefits_list_title','field_benefits_list_title');
            update_post_meta($page_id, 'benefits_list_desc',  'Comprehensive member benefits designed to support every stage of your career and life.');
            update_post_meta($page_id, '_benefits_list_desc', 'field_benefits_list_desc');

            $benefits = array(
                array('Tax Exempt',           'Enjoy the financial advantage of tax exemptions available to cooperative members.'),
                array('Associate Membership', 'Join a community of empowered professionals with a real stake in the company.'),
                array('Credit Loan Facility', 'Access our internal lending program for major life milestones.'),
                array('Savings Program',      'Secure your future with our structured cooperative savings scheme.'),
                array('Mandatory Benefits',   'Full SSS, PhilHealth, and Pag-IBIG contributions covered and compliant.'),
                array('HMO with Insurance',   'Comprehensive health coverage and life insurance for you and your dependents.'),
                array('Livelihood Programs',  'Skill-building opportunities through the Home Culinary School and partners.'),
                array('Extended HR Support',  'Dedicated HR professionals available to assist with any employment concern.'),
                array('Education Assistance', 'Continuous learning opportunities and scholarships for members and their families.'),
            );
            foreach ($benefits as $i => $b) {
                $n = $i + 1;
                update_post_meta($page_id, 'benefits_b'.$n.'_title',  $b[0]);
                update_post_meta($page_id, '_benefits_b'.$n.'_title', 'field_benefits_b'.$n.'_title');
                update_post_meta($page_id, 'benefits_b'.$n.'_desc',   $b[1]);
                update_post_meta($page_id, '_benefits_b'.$n.'_desc',  'field_benefits_b'.$n.'_desc');
            }
        }

        // ─────────────────────────────────────────
        // 5. SERVICE LABOR PAGE
        // ─────────────────────────────────────────
        if ($template === 'service-labor.php') {
            // Hero
            update_post_meta($page_id, 'slab_headline',  'Managed Services &<br>Offshore Staff Leasing');
            update_post_meta($page_id, '_slab_headline', 'field_slab_headline');
            update_post_meta($page_id, 'slab_desc',      'End-to-end workforce solutions powered by the Philippines\' leading worker-owned cooperative. From recruitment to payroll — we handle it all.');
            update_post_meta($page_id, '_slab_desc',     'field_slab_desc');
            update_post_meta($page_id, 'slab_bg',        'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=2000&q=80');
            update_post_meta($page_id, '_slab_bg',       'field_slab_bg');

            // Service Intro
            update_post_meta($page_id, 'slab_intro_title', 'Workforce Solutions Built for Scale');
            update_post_meta($page_id, '_slab_intro_title','field_slab_intro_title');
            update_post_meta($page_id, 'slab_intro_desc',  'Kings Group delivers comprehensive labor management services — from recruitment and onboarding to payroll processing and compliance. Our worker-owned model ensures every team member is personally invested in your success.');
            update_post_meta($page_id, '_slab_intro_desc', 'field_slab_intro_desc');
            update_post_meta($page_id, 'slab_intro_img',   'https://images.unsplash.com/photo-1600880292203-757bb62b4baf?auto=format&fit=crop&w=1200&q=80');
            update_post_meta($page_id, '_slab_intro_img',  'field_slab_intro_img');
            update_post_meta($page_id, 'slab_intro_pills', "Recruitment & Deployment\nPayroll Management\nDOLE Compliance\nOnboarding Support\nPerformance Monitoring\nHR Administration");
            update_post_meta($page_id, '_slab_intro_pills','field_slab_intro_pills');

            // Managed Services
            update_post_meta($page_id, 'slab_managed_title', 'A. Managed Services');
            update_post_meta($page_id, '_slab_managed_title','field_slab_managed_title');
            update_post_meta($page_id, 'slab_managed_desc',  'A fully integrated approach to workforce management — we act as your Philippine operations partner, handling everything from sourcing to performance reviews.');
            update_post_meta($page_id, '_slab_managed_desc', 'field_slab_managed_desc');

            $features = array(
                array('Talent Sourcing',       'Access our 10,000-member cooperative network to find pre-vetted professionals across any industry.',        'https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?auto=format&fit=crop&w=600&q=80'),
                array('Payroll & Compliance',  'Full DOLE-compliant payroll processing, statutory benefits, and government remittances handled end-to-end.', 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?auto=format&fit=crop&w=600&q=80'),
                array('Performance Management','Regular KPI reviews, coaching sessions, and performance reporting delivered to your team dashboard.',         'https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=600&q=80'),
                array('Hardware & Equipment', 'We supply premium workstations and peripherals so your offshore team is always equipped to perform.',          'https://images.unsplash.com/photo-1593642632559-0c6d3fc62b89?auto=format&fit=crop&w=600&q=80'),
            );
            foreach ($features as $i => $f) {
                $n = $i + 1;
                update_post_meta($page_id, 'slab_feat'.$n.'_title',  $f[0]);
                update_post_meta($page_id, '_slab_feat'.$n.'_title', 'field_slab_feat'.$n.'_title');
                update_post_meta($page_id, 'slab_feat'.$n.'_desc',   $f[1]);
                update_post_meta($page_id, '_slab_feat'.$n.'_desc',  'field_slab_feat'.$n.'_desc');
                update_post_meta($page_id, 'slab_feat'.$n.'_img',    $f[2]);
                update_post_meta($page_id, '_slab_feat'.$n.'_img',   'field_slab_feat'.$n.'_img');
            }

            // Total Manpower
            update_post_meta($page_id, 'slab_manpower_title', 'Total Manpower Solutions');
            update_post_meta($page_id, '_slab_manpower_title','field_slab_manpower_title');
            update_post_meta($page_id, 'slab_manpower_text',  '<p>From entry-level support staff to senior management roles, Kings Group provides a full spectrum of manpower solutions tailored to your operational requirements.</p><p>Whether you need one specialist or a team of hundreds, our cooperative model ensures you receive committed professionals who treat your business as their own.</p>');
            update_post_meta($page_id, '_slab_manpower_text', 'field_slab_manpower_text');
            update_post_meta($page_id, 'slab_manpower_img',   'https://images.unsplash.com/photo-1542744173-8e7e53415bb0?auto=format&fit=crop&w=1200&q=80');
            update_post_meta($page_id, '_slab_manpower_img',  'field_slab_manpower_img');

            // Staff Leasing
            update_post_meta($page_id, 'slab_lease_title',    'B. Managed Staff Leasing Services');
            update_post_meta($page_id, '_slab_lease_title',   'field_slab_lease_title');
            update_post_meta($page_id, 'slab_lease_desc',     'Flexible offshore staffing with complete HR, payroll, and compliance handled by Kings.');
            update_post_meta($page_id, '_slab_lease_desc',    'field_slab_lease_desc');
            update_post_meta($page_id, 'slab_offshore_title', '1. How Does Offshore Staff Leasing Work?');
            update_post_meta($page_id, '_slab_offshore_title','field_slab_offshore_title');
            update_post_meta($page_id, 'slab_offshore_text',  '<p>Offshore staff leasing allows your business to employ skilled Filipino professionals without setting up a local entity. Kings Group acts as the employer of record — managing all HR, legal, and payroll responsibilities while you maintain full operational control of your team.</p>');
            update_post_meta($page_id, '_slab_offshore_text', 'field_slab_offshore_text');
            update_post_meta($page_id, 'slab_offshore_img',   'https://images.unsplash.com/photo-1553877522-43269d4ea984?auto=format&fit=crop&w=1200&q=80');
            update_post_meta($page_id, '_slab_offshore_img',  'field_slab_offshore_img');

            // Improving Manpower
            update_post_meta($page_id, 'slab_improve_title', '2. Improving Your Manpower');
            update_post_meta($page_id, '_slab_improve_title','field_slab_improve_title');
            update_post_meta($page_id, 'slab_improve_desc',  'We continuously invest in our members through structured training, performance coaching, and career development programs.');
            update_post_meta($page_id, '_slab_improve_desc', 'field_slab_improve_desc');
            update_post_meta($page_id, 'slab_improve_img',   'https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=1200&q=80');
            update_post_meta($page_id, '_slab_improve_img',  'field_slab_improve_img');

            $checklists = array(
                array('Skills Assessment',    'Every candidate undergoes a thorough skills and behavioral assessment before deployment.'),
                array('Continuous Training',  'Ongoing learning programs keep our members sharp, updated, and aligned with your business needs.'),
                array('Performance Coaching', 'Regular 1-on-1 coaching sessions to ensure consistent quality and growth for your team.'),
            );
            foreach ($checklists as $i => $c) {
                $n = $i + 1;
                update_post_meta($page_id, 'slab_check'.$n.'_title',  $c[0]);
                update_post_meta($page_id, '_slab_check'.$n.'_title', 'field_slab_check'.$n.'_title');
                update_post_meta($page_id, 'slab_check'.$n.'_desc',   $c[1]);
                update_post_meta($page_id, '_slab_check'.$n.'_desc',  'field_slab_check'.$n.'_desc');
            }

            // Onboarding Journey
            update_post_meta($page_id, 'slab_onboard_title', '3. The Onboarding Journey');
            update_post_meta($page_id, '_slab_onboard_title','field_slab_onboard_title');
            update_post_meta($page_id, 'slab_onboard_desc',  'A structured, step-by-step process designed to integrate your offshore team seamlessly into your operations.');
            update_post_meta($page_id, '_slab_onboard_desc', 'field_slab_onboard_desc');
        }

        // ─────────────────────────────────────────
        // 6. SERVICE KIT PAGE
        // ─────────────────────────────────────────
        if ($template === 'service-kit.php') {
            // Hero
            update_post_meta($page_id, 'skit_headline',  'HR & Payroll System');
            update_post_meta($page_id, '_skit_headline', 'field_skit_headline');
            update_post_meta($page_id, 'skit_desc',      'Kings Information Technology (KIT) — the all-in-one HR, payroll, and workforce management platform built for the cooperative era.');
            update_post_meta($page_id, '_skit_desc',     'field_skit_desc');
            update_post_meta($page_id, 'skit_bg',        'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=2000&q=80');
            update_post_meta($page_id, '_skit_bg',       'field_skit_bg');

            // Service Intro
            update_post_meta($page_id, 'skit_intro_title', 'The Smart HR Platform for Modern Workforce Management');
            update_post_meta($page_id, '_skit_intro_title','field_skit_intro_title');
            update_post_meta($page_id, 'skit_intro_desc',  'KIT is our proprietary HR and payroll technology platform designed to give both employers and cooperative members real-time visibility into their workforce data, benefits, and performance metrics.');
            update_post_meta($page_id, '_skit_intro_desc', 'field_skit_intro_desc');
            update_post_meta($page_id, 'skit_intro_pills', "Automated Payroll\nLeave Management\n201 File System\nTime & Attendance\nPerformance Tracking\nGovernment Remittances");
            update_post_meta($page_id, '_skit_intro_pills','field_skit_intro_pills');
            update_post_meta($page_id, 'skit_intro_img1',  'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=1200&q=80');
            update_post_meta($page_id, '_skit_intro_img1', 'field_skit_intro_img1');
            update_post_meta($page_id, 'skit_intro_img2',  'https://images.unsplash.com/photo-1600880292203-757bb62b4baf?auto=format&fit=crop&w=1200&q=80');
            update_post_meta($page_id, '_skit_intro_img2', 'field_skit_intro_img2');

            // How We Work
            update_post_meta($page_id, 'skit_hww_title', 'How We Work');
            update_post_meta($page_id, '_skit_hww_title','field_skit_hww_title');
            update_post_meta($page_id, 'skit_hww_text',  'KIT integrates directly with your existing workflows. Our onboarding team sets up your account, imports your team data, and trains your HR staff — all within 5 business days. After go-live, our support team is always one message away.');
            update_post_meta($page_id, '_skit_hww_text', 'field_skit_hww_text');
            update_post_meta($page_id, 'skit_hww_img',   'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=1200&q=80');
            update_post_meta($page_id, '_skit_hww_img',  'field_skit_hww_img');

            // Platform Features
            $skit_features = array(
                array('Automated Payroll',        'Process payroll in minutes with automatic tax calculations, deductions, and government remittances.'),
                array('Leave & Attendance',       'Real-time leave requests, approvals, and attendance tracking accessible from any device.'),
                array('201 File Management',      'Complete digital employee records — contracts, documents, and compliance files in one secure place.'),
                array('Performance Management',   'Set KPIs, track progress, and run appraisals through a structured, data-driven review system.'),
                array('Government Compliance',    'Automated SSS, PhilHealth, and Pag-IBIG computations and reporting to keep you always compliant.'),
                array('Real-Time Analytics',      'Live dashboards and workforce reports give management instant visibility into team performance and costs.'),
            );
            foreach ($skit_features as $i => $f) {
                $n = $i + 1;
                update_post_meta($page_id, 'skit_feat'.$n.'_title',  $f[0]);
                update_post_meta($page_id, '_skit_feat'.$n.'_title', 'field_skit_feat'.$n.'_title');
                update_post_meta($page_id, 'skit_feat'.$n.'_desc',   $f[1]);
                update_post_meta($page_id, '_skit_feat'.$n.'_desc',  'field_skit_feat'.$n.'_desc');
            }

            // Moving Forward
            update_post_meta($page_id, 'skit_forward_title', 'Moving Forward');
            update_post_meta($page_id, '_skit_forward_title','field_skit_forward_title');
            update_post_meta($page_id, 'skit_forward_text',  'Ready to modernize your HR operations? KIT is included as part of your Kings Group partnership — no additional software fees. Contact us today to schedule your onboarding session.');
            update_post_meta($page_id, '_skit_forward_text', 'field_skit_forward_text');
        }

        // ─────────────────────────────────────────
        // 7. NETWORK PAGE
        // ─────────────────────────────────────────
        if ($template === 'network.php') {
            update_post_meta($page_id, 'net_headline',  'Our Global Network');
            update_post_meta($page_id, '_net_headline', 'field_net_headline');
            update_post_meta($page_id, 'net_desc',      'Serving over 10,000 members and integrating with world-class clients across industries.');
            update_post_meta($page_id, '_net_desc',     'field_net_desc');
            update_post_meta($page_id, 'net_bg',        'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=2000&q=80');
            update_post_meta($page_id, '_net_bg',       'field_net_bg');

            $stats = array(
                array('10000', 'Active Members'),
                array('7',     'Industry Sectors'),
                array('50',    'Roles Deployed'),
                array('25',    'Years of Excellence'),
            );
            foreach ($stats as $i => $s) {
                $n = $i + 1;
                update_post_meta($page_id, 'net_s'.$n.'_num',   $s[0]);
                update_post_meta($page_id, '_net_s'.$n.'_num',  'field_net_s'.$n.'_num');
                update_post_meta($page_id, 'net_s'.$n.'_label', $s[1]);
                update_post_meta($page_id, '_net_s'.$n.'_label','field_net_s'.$n.'_label');
            }
        }

        // ─────────────────────────────────────────
        // 8. QUOTE / TEAM BUILDER PAGE
        // ─────────────────────────────────────────
        if ($template === 'quote.php') {
            update_post_meta($page_id, 'quote_headline',          'Build Your Offshore Team');
            update_post_meta($page_id, '_quote_headline',         'field_quote_headline');
            update_post_meta($page_id, 'quote_desc',              'Select the roles you need, adjust headcount and experience levels, and receive an instant transparent estimate of your monthly cooperative investment.');
            update_post_meta($page_id, '_quote_desc',             'field_quote_desc');
            update_post_meta($page_id, 'quote_bg',                'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=2000&q=80');
            update_post_meta($page_id, '_quote_bg',               'field_quote_bg');
            update_post_meta($page_id, 'quote_b_title',           'Estimate Your Monthly Investment');
            update_post_meta($page_id, '_quote_b_title',          'field_quote_b_title');
            update_post_meta($page_id, 'quote_calc_instructions', 'Select roles, adjust experience levels, and see a transparent baseline for your offshore team investment.');
            update_post_meta($page_id, '_quote_calc_instructions','field_quote_calc_instructions');
        }

        // ─────────────────────────────────────────
        // 9. CONTACT PAGE
        // ─────────────────────────────────────────
        if ($template === 'contact.php') {
            update_post_meta($page_id, 'contact_headline',  'Contact Us');
            update_post_meta($page_id, '_contact_headline', 'field_contact_headline');
            update_post_meta($page_id, 'contact_desc',      'We are here to help. Reach out to our team for any inquiries about staffing, membership, or partnership.');
            update_post_meta($page_id, '_contact_desc',     'field_contact_desc');
            update_post_meta($page_id, 'contact_bg',        'https://images.unsplash.com/photo-1497366754035-f200968a6e72?auto=format&fit=crop&w=2000&q=80');
            update_post_meta($page_id, '_contact_bg',       'field_contact_bg');

            update_post_meta($page_id, 'contact_form_title', 'Get In Touch');
            update_post_meta($page_id, '_contact_form_title', 'field_contact_form_title');
            update_post_meta($page_id, 'contact_form_desc', 'THE KINGS is a community of jobs and workspaces. We are excited to welcome you to THE KINGS!');
            update_post_meta($page_id, '_contact_form_desc', 'field_contact_form_desc');
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
            update_post_meta($page_id, 'contact_address', 'Better Living Subdivision, 100 Doña Soledad Ave, Parañaque, 1711 Metro Manila');
            update_post_meta($page_id, '_contact_address', 'field_contact_address');
        }

        // ─────────────────────────────────────────
        // 10. COMMUNITY PAGE
        // ─────────────────────────────────────────
        if ($template === 'page-community.php') {
            $fields = array(
                'comm_hero_title'    => 'Our Commitment to Community',
                'comm_welcome_text'  => 'Welcome to The KINGS — Find great opportunities now!',
                'comm_queens_title'  => 'Queens of Kings Group',
                'comm_scholar_desc'  => 'The Kings Group supports the aspirations of its members and their dependents by providing scholarships to ensure sustainable futures.',
                'comm_culinary_desc' => 'A TESDA-accredited and certified institution built to provide sustainable education and livelihood programs.',
            );
            foreach ( $fields as $key => $val ) {
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
            update_post_meta($page_id, 'comm_hero_title',      'Our Commitment to Community');
            update_post_meta($page_id, '_comm_hero_title',     'field_comm_hero_title');
            update_post_meta($page_id, 'comm_hero_desc',       'Building a sustainable future through education, empowerment, and shared success.');
            update_post_meta($page_id, '_comm_hero_desc',      'field_comm_hero_desc');
            update_post_meta($page_id, 'comm_impact_intro',    'Community is essential to our mission and it is our responsibility to support the aspirations of our members by providing scholarships to our members and their dependents.');
            update_post_meta($page_id, '_comm_impact_intro',   'field_comm_impact_intro');
            update_post_meta($page_id, 'comm_stat1_num',        '500+');
            update_post_meta($page_id, '_comm_stat1_num',       'field_comm_stat1_num');
            update_post_meta($page_id, 'comm_stat1_label',      'Scholarships Awarded');
            update_post_meta($page_id, '_comm_stat1_label',     'field_comm_stat1_label');
            update_post_meta($page_id, 'comm_stat2_num',        '100%');
            update_post_meta($page_id, '_comm_stat2_num',       'field_comm_stat2_num');
            update_post_meta($page_id, 'comm_stat2_label',      'Member Focused');
            update_post_meta($page_id, '_comm_stat2_label',     'field_comm_stat2_label');
            update_post_meta($page_id, 'comm_impact_img',       'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1200&q=80');
            update_post_meta($page_id, '_comm_impact_img',      'field_comm_impact_img');
            update_post_meta($page_id, 'comm_queens_title',    'Queens of Kings Group');
            update_post_meta($page_id, '_comm_queens_title',   'field_comm_queens_title');
            update_post_meta($page_id, 'comm_queens_desc',     'Dedicated to empowering women within the Kings Group network through specialized resources, mentorship, and support structures designed for professional and personal growth.');
            update_post_meta($page_id, '_comm_queens_desc',    'field_comm_queens_desc');
            update_post_meta($page_id, 'comm_queens_img',       'https://images.unsplash.com/photo-1600880292203-757bb62b4baf?auto=format&fit=crop&w=800&q=80');
            update_post_meta($page_id, '_comm_queens_img',      'field_comm_queens_img');
            update_post_meta($page_id, 'comm_culinary_tag',     'Education');
            update_post_meta($page_id, '_comm_culinary_tag',    'field_comm_culinary_tag');
            update_post_meta($page_id, 'comm_culinary_title',   'Home Culinary & Technical School');
            update_post_meta($page_id, '_comm_culinary_title',  'field_comm_culinary_title');
            update_post_meta($page_id, 'comm_culinary_sub',     'Empowering our members with sustainable livelihood programs and TESDA-accredited training.');
            update_post_meta($page_id, '_comm_culinary_sub',    'field_comm_culinary_sub');
            update_post_meta($page_id, 'comm_culinary_intro',  'We built Home Culinary and Technical School to have a sustainable education and livelihood programs for our members and their families.');
            update_post_meta($page_id, '_comm_culinary_intro', 'field_comm_culinary_intro');
            update_post_meta($page_id, 'comm_culinary_desc',   'As The Kings expands, so does our scholarship program with Home Culinary and Technical School. We are TESDA accredited and certified.');
            update_post_meta($page_id, '_comm_culinary_desc',  'field_comm_culinary_desc');
            update_post_meta($page_id, 'comm_culinary_img',    'https://images.unsplash.com/photo-1556910103-1c02745a872e?auto=format&fit=crop&w=800&q=80');
            update_post_meta($page_id, '_comm_culinary_img',   'field_comm_culinary_img');
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
            array('Software Developer',      'Frontend, Backend, and Full Stack development',                    2000),
            array('Operations Head',         'Strategic oversight and operational management',                   2500),
            array('Customer Service Rep',    'Inbound/outbound support and client relationship management',      800),
            array('Data Analyst',            'Business intelligence, reporting, and data visualization',         1400),
            array('Graphic Designer',        'Brand identity, digital assets, and visual communication',         900),
            array('Virtual Assistant',       'Administrative support, scheduling, and correspondence',           700),
            array('Digital Marketing Exec',  'SEO, social media, paid ads, and content strategy',               1100),
            array('Accountant',              'Bookkeeping, financial reporting, and compliance',                 1200),
        );
        $job_images = array(
            'Software Developer'     => 'https://images.unsplash.com/photo-1607799279861-4dd421887fb3?auto=format&fit=crop&w=600&q=80',
            'Operations Head'        => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=600&q=80',
            'Customer Service Rep'   => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=600&q=80',
            'Data Analyst'           => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=600&q=80',
            'Graphic Designer'       => 'https://images.unsplash.com/photo-1561070791-26c113006238?auto=format&fit=crop&w=600&q=80',
            'Virtual Assistant'      => 'https://images.unsplash.com/photo-1521737711867-e3b97375f902?auto=format&fit=crop&w=600&q=80',
            'Digital Marketing Exec' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=600&q=80',
            'Accountant'             => 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?auto=format&fit=crop&w=600&q=80',
        );
        $job_depts = array(
            'Software Developer'     => 'Technology',
            'Operations Head'        => 'Operations',
            'Customer Service Rep'   => 'Customer Support',
            'Data Analyst'           => 'Technology',
            'Graphic Designer'       => 'Creative',
            'Virtual Assistant'      => 'Administrative',
            'Digital Marketing Exec' => 'Marketing',
            'Accountant'             => 'Finance',
        );
        foreach ($default_jobs as $job) {
            $title = $job[0];
            $job_id = wp_insert_post(array(
                'post_title'   => $title,
                'post_status'  => 'publish',
                'post_type'    => 'jobs',
                'post_excerpt' => $job[1],
            ));
            
            $img = isset($job_images[$title]) ? $job_images[$title] : 'https://images.unsplash.com/photo-1600880292203-757bb62b4baf?auto=format&fit=crop&w=600&q=80';
            $dept = isset($job_depts[$title]) ? $job_depts[$title] : 'General';
            $is_remote = ($title === 'Software Developer' || $title === 'Virtual Assistant' || $title === 'Graphic Designer');

            update_post_meta($job_id, 'base_price',             $job[2]);
            update_post_meta($job_id, '_base_price',            'field_job_base_price');
            update_post_meta($job_id, 'include_in_team_builder', 1);
            update_post_meta($job_id, '_include_in_team_builder','field_job_include_team_builder');
            
            update_post_meta($job_id, 'job_card_image',         $img);
            update_post_meta($job_id, '_job_card_image',        'field_job_card_image');
            update_post_meta($job_id, 'job_location',           $is_remote ? 'Remote, Philippines' : 'Parañaque, Metro Manila');
            update_post_meta($job_id, '_job_location',          'field_job_location');
            update_post_meta($job_id, 'job_type',               $is_remote ? 'CONTRACTOR' : 'FULL_TIME');
            update_post_meta($job_id, '_job_type',              'field_job_type');
            update_post_meta($job_id, 'job_work_setup',          $is_remote ? 'WFH' : 'WFO');
            update_post_meta($job_id, '_job_work_setup',         'field_job_work_setup');
            update_post_meta($job_id, 'job_salary_min',          $job[2] * 40);
            update_post_meta($job_id, '_job_salary_min',         'field_job_salary_min');
            update_post_meta($job_id, 'job_salary_max',          $job[2] * 60);
            update_post_meta($job_id, '_job_salary_max',         'field_job_salary_max');
            update_post_meta($job_id, 'job_department',         $dept);
            update_post_meta($job_id, '_job_department',         'field_job_department');
            update_post_meta($job_id, 'job_target_headcount',    5);
            update_post_meta($job_id, '_job_target_headcount',   'field_job_target_headcount');
            update_post_meta($job_id, 'job_filled_headcount',    1);
            update_post_meta($job_id, '_job_filled_headcount',   'field_job_filled_headcount');
        }
        // ─────────────────────────────────────────
        // 13. OUR JOBS PAGE
        // ─────────────────────────────────────────
        if ($template === 'our-jobs.php') {
            update_post_meta($page_id, 'jobs_hero_headline',  'Our Jobs');
            update_post_meta($page_id, '_jobs_hero_headline', 'field_jobs_hero_headline');
            update_post_meta($page_id, 'jobs_hero_desc',      'Find your next opportunity at one of the Philippines\' most people-first cooperatives.');
            update_post_meta($page_id, '_jobs_hero_desc',     'field_jobs_hero_desc');
            update_post_meta($page_id, 'jobs_hero_bg',       'https://images.unsplash.com/photo-1521737711867-e3b97375f902?auto=format&fit=crop&w=2000&q=80');
            update_post_meta($page_id, '_jobs_hero_bg',      'field_jobs_hero_bg');
        }
    }

    update_option('kg_full_site_populated_v13', true);
    flush_rewrite_rules();
}
add_action('init', 'kingsgroup_populate_all_pages');

/**
 * Helper: Find a page ID by its template filename.
 */
function kg_get_page_by_template($template_name) {
    $pages = get_posts(array(
        'post_type'  => 'page',
        'meta_key'   => '_wp_page_template',
        'meta_value' => $template_name,
        'posts_per_page' => 1,
        'fields'     => 'ids',
    ));
    return !empty($pages) ? $pages[0] : false;
}

