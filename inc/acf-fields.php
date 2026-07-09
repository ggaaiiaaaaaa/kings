<?php
if (function_exists('acf_add_local_field_group')):

    // Common setting to hide default editor clutter
    $hide_elements = array('the_content', 'excerpt', 'discussion', 'comments', 'revisions', 'slug', 'author', 'format', 'featured_image', 'categories', 'tags', 'send-trackbacks');

    if (!function_exists('kg_get_cta_acf_fields')) {
        function kg_get_cta_acf_fields($prefix, $tab_label = '5. CTA Banner')
        {
            return array(
                array('key' => 'tab_' . $prefix . '_cta', 'label' => $tab_label, 'type' => 'tab'),

                // PH / Local
                array('key' => 'field_' . $prefix . '_cta_title_ph', 'label' => 'Headline (PH / Local)', 'name' => $prefix . '_cta_title_ph', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'default_value' => 'Your Trusted Provider for Manpower Solutions & Career Growth', 'wrapper' => array('width' => '50')),
                array('key' => 'field_' . $prefix . '_cta_subtext_ph', 'label' => 'Subtext (PH / Local)', 'name' => $prefix . '_cta_subtext_ph', 'type' => 'textarea', 'rows' => 2, 'default_value' => 'Providing dependable local staffing services across industries while empowering 10,000+ member-owners nationwide with complete benefits and ethical opportunities.', 'wrapper' => array('width' => '50')),
                array('key' => 'field_' . $prefix . '_cta_btn1_ph', 'label' => 'Button 1 Label (PH)', 'name' => $prefix . '_cta_btn1_ph', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'default_value' => 'Inquire for Manpower Services', 'wrapper' => array('width' => '50')),
                array('key' => 'field_' . $prefix . '_cta_btn1_url_ph', 'label' => 'Button 1 URL (PH)', 'name' => $prefix . '_cta_btn1_url_ph', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'default_value' => '/contact/', 'wrapper' => array('width' => '50')),
                array('key' => 'field_' . $prefix . '_cta_btn2_ph', 'label' => 'Button 2 Label (PH)', 'name' => $prefix . '_cta_btn2_ph', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'default_value' => 'Explore Career Opportunities', 'wrapper' => array('width' => '50')),
                array('key' => 'field_' . $prefix . '_cta_btn2_url_ph', 'label' => 'Button 2 URL (PH)', 'name' => $prefix . '_cta_btn2_url_ph', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'default_value' => '/careers/', 'wrapper' => array('width' => '50')),

                // International
                array('key' => 'field_' . $prefix . '_cta_title_intl', 'label' => 'Headline (International)', 'name' => $prefix . '_cta_title_intl', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'default_value' => 'Scale Your Global Operations with Elite Philippine Talent', 'wrapper' => array('width' => '50')),
                array('key' => 'field_' . $prefix . '_cta_subtext_intl', 'label' => 'Subtext (International)', 'name' => $prefix . '_cta_subtext_intl', 'type' => 'textarea', 'rows' => 2, 'default_value' => 'Partner with top-tier offshore teams under an ethical, worker-owned cooperative model. Rapid integration and full operational support in under 14 days.', 'wrapper' => array('width' => '50')),
                array('key' => 'field_' . $prefix . '_cta_btn1_intl', 'label' => 'Button 1 Label (International)', 'name' => $prefix . '_cta_btn1_intl', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'default_value' => 'Contact Staffing Experts', 'wrapper' => array('width' => '50')),
                array('key' => 'field_' . $prefix . '_cta_btn1_url_intl', 'label' => 'Button 1 URL (International)', 'name' => $prefix . '_cta_btn1_url_intl', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'default_value' => '/contact/', 'wrapper' => array('width' => '50')),
                array('key' => 'field_' . $prefix . '_cta_btn2_intl', 'label' => 'Button 2 Label (International)', 'name' => $prefix . '_cta_btn2_intl', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'default_value' => 'Request a Custom Quote', 'wrapper' => array('width' => '50')),
                array('key' => 'field_' . $prefix . '_cta_btn2_url_intl', 'label' => 'Button 2 URL (International)', 'name' => $prefix . '_cta_btn2_url_intl', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'default_value' => '/quote/', 'wrapper' => array('width' => '50')),
            );
        }
    }

    // ==========================================
// 1. FRONT PAGE (HOME) FIELDS
// ==========================================
    $home_fields = array(
        // TAB 1: Hero
        array('key' => 'tab_home_hero', 'label' => '1. Hero Section', 'type' => 'tab'),
        array('key' => 'field_home_hero_headline', 'label' => 'Headline (Local PH)', 'name' => 'hero_headline', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'instructions' => 'Main hero headline for local PH users. Use &lt;span&gt; for styled text, &lt;br&gt; for line breaks.', 'placeholder' => 'Your Trusted Local<br>Manpower Partner <span>in the Philippines.</span>', 'default_value' => 'Your Trusted Local<br>Manpower Partner <span>in the Philippines.</span>'),
        array('key' => 'field_home_hero_desc', 'label' => 'Description (Local PH)', 'name' => 'hero_description', 'type' => 'textarea', 'instructions' => 'Supporting paragraph below the headline for local PH users. Speaks to both job seekers and local businesses.', 'rows' => 3, 'default_value' => 'Whether you\'re looking for a job or hiring for your business — Kings Group has been connecting Filipino talent with local employers since 1999.'),
        array('key' => 'field_home_hero_headline_intl', 'label' => 'Headline (International)', 'name' => 'hero_headline_intl', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'instructions' => 'Main hero headline for international users. Use &lt;br&gt; for line breaks, &lt;span&gt; for styled text.', 'placeholder' => 'Elite Talent.<br>Ethical Staffing. <span>Exceptional Results.</span>', 'default_value' => 'Elite Talent.<br>Ethical Staffing. <span>Exceptional Results.</span>'),
        array('key' => 'field_home_hero_desc_intl', 'label' => 'Description (International)', 'name' => 'hero_description_intl', 'type' => 'textarea', 'instructions' => 'Supporting paragraph below the headline for international users.', 'rows' => 3, 'default_value' => 'Scale your operations with dedicated offshore professionals from the Philippines.'),
        array('key' => 'field_home_hero_btn_ph', 'label' => 'Button Text (Local PH)', 'name' => 'hero_btn_ph', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'default_value' => 'Request Manpower'),
        array('key' => 'field_home_hero_btn_hover_ph', 'label' => 'Button Hover Text (Local PH)', 'name' => 'hero_btn_hover_ph', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'default_value' => 'Inquire Now'),
        array('key' => 'field_home_hero_cv_label', 'label' => 'CV Link Text (Local PH)', 'name' => 'hero_cv_label', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'default_value' => 'Looking for a job? Submit your CV'),
        array('key' => 'field_home_hero_btn_intl', 'label' => 'Button Text (International)', 'name' => 'hero_btn_intl', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'default_value' => 'Build Your Team'),
        array('key' => 'field_home_hero_img_1', 'label' => 'Slide 1 — Background Image', 'name' => 'hero_img_1', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium', 'instructions' => 'Hero slider image 1 (recommended 2000×1000px).'),
        array('key' => 'field_home_hero_img_2', 'label' => 'Slide 2 — Background Image', 'name' => 'hero_img_2', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium', 'instructions' => 'Hero slider image 2.'),
        array('key' => 'field_home_hero_img_3', 'label' => 'Slide 3 — Background Image', 'name' => 'hero_img_3', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium', 'instructions' => 'Hero slider image 3.'),
    );

    // Add remaining TABs
    $home_fields = array_merge($home_fields, array(
        // TAB 2: Who We Are
        array('key' => 'tab_home_wwa', 'label' => '2. Who We Are', 'type' => 'tab'),
        array('key' => 'field_home_wwa_title', 'label' => 'Title', 'name' => 'wwa_title', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'placeholder' => 'Who We Are'),
        array('key' => 'field_home_wwa_p1', 'label' => 'Paragraph 1', 'name' => 'wwa_p1', 'type' => 'textarea', 'rows' => 3),
        array('key' => 'field_home_wwa_p2', 'label' => 'Paragraph 2', 'name' => 'wwa_p2', 'type' => 'textarea', 'rows' => 3),
        array('key' => 'field_home_wwa_p3', 'label' => 'Paragraph 3', 'name' => 'wwa_p3', 'type' => 'textarea', 'rows' => 3),
        array('key' => 'field_home_wwa_btn_text', 'label' => 'Button Text', 'name' => 'wwa_btn_text', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'placeholder' => 'Learn Our Story'),
        array('key' => 'field_home_wwa_img', 'label' => 'Image', 'name' => 'wwa_img', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium'),

        // TAB 3: Staffing Intro
        array('key' => 'tab_home_intro', 'label' => '3. Staffing Intro', 'type' => 'tab'),
        array('key' => 'field_home_intro_title', 'label' => 'Section Title', 'name' => 'home_intro_title', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'instructions' => 'Heading for the staffing intro section.', 'placeholder' => 'A Different Kind of Staffing'),
        array('key' => 'field_home_intro_sub', 'label' => 'Section Subtitle', 'name' => 'home_intro_sub', 'type' => 'textarea', 'instructions' => 'Short description below the section title.', 'rows' => 2),

        // TAB 4: The Advantage (Clients)
        array('key' => 'tab_home_advantage', 'label' => '4. Client Advantage', 'type' => 'tab'),

        // ── Local PH ──
        array('key' => 'field_home_adv_ph_headline', 'label' => 'Headline (Local PH)', 'name' => 'adv_headline_ph', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'instructions' => 'Main headline for local PH visitors. Supports &lt;br&gt; for line breaks.', 'default_value' => 'Your Trusted Local<br>Manpower Partner.'),
        array('key' => 'field_home_adv_ph_sub', 'label' => 'Sub-Headline (Local PH)', 'name' => 'adv_subheadline_ph', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'instructions' => 'Bold tagline below the headline for PH visitors.', 'default_value' => 'Stop the hiring hassle. Start deploying.'),
        array('key' => 'field_home_adv_ph_desc', 'label' => 'Description (Local PH)', 'name' => 'adv_desc_ph', 'type' => 'textarea', 'rows' => 3, 'default_value' => 'Get job-ready Filipino workers sourced, screened, and deployed to your business — fully managed and DOLE-compliant. We\'ve been doing this since 1999.'),

        // ── INTL ──
        array('key' => 'field_home_adv_headline', 'label' => 'Headline (International)', 'name' => 'adv_headline', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'instructions' => 'Main headline for international visitors. Supports &lt;br&gt; for line breaks.'),
        array('key' => 'field_home_adv_sub', 'label' => 'Sub-Headline (International)', 'name' => 'adv_subheadline', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'instructions' => 'Bold tagline below the headline for INTL visitors.'),
        array('key' => 'field_home_adv_desc', 'label' => 'Description (International)', 'name' => 'adv_desc', 'type' => 'textarea', 'rows' => 3),
        array('key' => 'field_home_adv_img', 'label' => 'Section Image', 'name' => 'adv_img', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium'),
    ));

    // Manual Repeaters for Advantage Features — PH (Max 3)
    for ($i = 1; $i <= 3; $i++) {
        $home_fields[] = array('key' => 'field_home_adv_ph_f' . $i . '_title', 'label' => 'PH Feature ' . $i . ' Title', 'name' => 'adv_f' . $i . '_title_ph', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'wrapper' => array('width' => '50'));
        $home_fields[] = array('key' => 'field_home_adv_ph_f' . $i . '_desc', 'label' => 'PH Feature ' . $i . ' Desc', 'name' => 'adv_f' . $i . '_desc_ph', 'type' => 'textarea', 'wrapper' => array('width' => '50'));
    }

    // Manual Repeaters for Advantage Features — INTL (Max 3)
    for ($i = 1; $i <= 3; $i++) {
        $home_fields[] = array('key' => 'field_home_adv_f' . $i . '_title', 'label' => 'INTL Feature ' . $i . ' Title', 'name' => 'adv_f' . $i . '_title', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'wrapper' => array('width' => '50'));
        $home_fields[] = array('key' => 'field_home_adv_f' . $i . '_desc', 'label' => 'INTL Feature ' . $i . ' Desc', 'name' => 'adv_f' . $i . '_desc', 'type' => 'textarea', 'wrapper' => array('width' => '50'));
    }

    // TAB 5: Applicant Focus
    $home_fields[] = array('key' => 'tab_home_applicant', 'label' => '5. Applicant Focus', 'type' => 'tab');
    $home_fields[] = array('key' => 'field_home_app_headline', 'label' => 'Headline', 'name' => 'app_headline', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'instructions' => 'Main headline for the "For Applicants" panel.');
    $home_fields[] = array('key' => 'field_home_app_sub', 'label' => 'Sub-Headline', 'name' => 'app_subheadline', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'instructions' => 'Bold tagline below the headline.');
    $home_fields[] = array('key' => 'field_home_app_desc', 'label' => 'Description', 'name' => 'app_desc', 'type' => 'textarea', 'rows' => 3);
    $home_fields[] = array('key' => 'field_home_app_img', 'label' => 'Section Image', 'name' => 'app_img', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium');

    // PH CTA Button
    $home_fields[] = array('key' => 'field_home_app_cta_ph_label', 'label' => 'CTA Button Text (PH)', 'name' => 'app_cta_ph', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'default_value' => 'Drop your CV Today', 'wrapper' => array('width' => '50'));
    $home_fields[] = array('key' => 'field_home_app_cta_ph_url', 'label' => 'CTA Button URL (PH)', 'name' => 'app_cta_ph_url', 'type' => 'url', 'default_value' => '/careers/', 'wrapper' => array('width' => '50'));

    // INTL CTA Button
    $home_fields[] = array('key' => 'field_home_app_cta_intl_label', 'label' => 'CTA Button Text (International)', 'name' => 'app_cta_intl', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'default_value' => 'Find Offshore Talent', 'instructions' => 'Button label shown to international visitors instead of "Drop your CV".', 'wrapper' => array('width' => '50'));
    $home_fields[] = array('key' => 'field_home_app_cta_intl_url', 'label' => 'CTA Button URL (International)', 'name' => 'app_cta_intl_url', 'type' => 'url', 'default_value' => '/contact/', 'instructions' => 'Destination URL for the international CTA button.', 'wrapper' => array('width' => '50'));

    // Manual Repeaters for Applicant Features — PH (Max 3)
    for ($i = 1; $i <= 3; $i++) {
        $home_fields[] = array('key' => 'field_home_app_ph_f' . $i . '_title', 'label' => 'PH Feature ' . $i . ' Title', 'name' => 'app_f' . $i . '_title', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'wrapper' => array('width' => '50'));
        $home_fields[] = array('key' => 'field_home_app_ph_f' . $i . '_desc', 'label' => 'PH Feature ' . $i . ' Desc', 'name' => 'app_f' . $i . '_desc', 'type' => 'textarea', 'wrapper' => array('width' => '50'));
    }

    // Manual Repeaters for Applicant Features — INTL (Max 3)
    for ($i = 1; $i <= 3; $i++) {
        $home_fields[] = array('key' => 'field_home_app_intl_f' . $i . '_title', 'label' => 'INTL Feature ' . $i . ' Title', 'name' => 'app_intl_f' . $i . '_title', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'wrapper' => array('width' => '50'));
        $home_fields[] = array('key' => 'field_home_app_intl_f' . $i . '_desc', 'label' => 'INTL Feature ' . $i . ' Desc', 'name' => 'app_intl_f' . $i . '_desc', 'type' => 'textarea', 'wrapper' => array('width' => '50'));
    }

    // TAB 6: Our Network
    $home_fields[] = array('key' => 'tab_home_net', 'label' => '6. Our Network', 'type' => 'tab');
    $home_fields[] = array('key' => 'field_home_net_title', 'label' => 'Section Title', 'name' => 'net_title', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'placeholder' => 'Our Network');
    $home_fields[] = array('key' => 'field_home_net_desc', 'label' => 'Section Subtitle', 'name' => 'net_subtitle', 'type' => 'textarea', 'rows' => 2, 'placeholder' => 'Explore our affiliated brands and communities.');

    for ($i = 1; $i <= 3; $i++) {
        $brand_names = array(1 => 'The Kings City', 2 => 'The Social Manila', 3 => 'The Home Culinary School');
        $brand_defaults = array(
            1 => array('desc' => 'A space where creativity, productivity, and community come together. Designed for individuals, creatives, entrepreneurs, and growing teams, the club offers thoughtfully curated spaces for coworking, collaboration, workshops, and meaningful connections.', 'link' => 'https://www.kings-city.com/', 'btn' => 'Discover Kings City'),
            2 => array('desc' => 'The premier events, lifestyle, and community engagement hub. We host corporate functions, team-building events, and exclusive gatherings designed to connect leaders and ignite culture.', 'link' => 'https://kingscity.com.ph/', 'btn' => 'Explore The Social'),
            3 => array('desc' => 'Professional culinary training and certification programs. Equipping the next generation of chefs and hospitality professionals with world-class skills, discipline, and ethical standards.', 'link' => 'https://temptest.homeculinaryschool.com/', 'btn' => 'Start Cooking'),
        );

        $home_fields[] = array('key' => 'field_home_net_b' . $i . '_title', 'label' => 'Brand ' . $i . ' Title', 'name' => 'net_brand' . $i . '_title', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'placeholder' => $brand_names[$i], 'wrapper' => array('width' => '33'));
        $home_fields[] = array('key' => 'field_home_net_b' . $i . '_link', 'label' => 'Brand ' . $i . ' Link URL', 'name' => 'net_brand' . $i . '_link', 'type' => 'url', 'placeholder' => $brand_defaults[$i]['link'], 'wrapper' => array('width' => '33'));
        $home_fields[] = array('key' => 'field_home_net_b' . $i . '_btn', 'label' => 'Brand ' . $i . ' Button Text', 'name' => 'net_brand' . $i . '_btn', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'placeholder' => $brand_defaults[$i]['btn'], 'wrapper' => array('width' => '33'));
        $home_fields[] = array('key' => 'field_home_net_b' . $i . '_img', 'label' => 'Brand ' . $i . ' Image', 'name' => 'net_brand' . $i . '_img', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium', 'wrapper' => array('width' => '50'));
        $home_fields[] = array('key' => 'field_home_net_b' . $i . '_desc', 'label' => 'Brand ' . $i . ' Description', 'name' => 'net_brand' . $i . '_desc', 'type' => 'textarea', 'rows' => 3, 'placeholder' => $brand_defaults[$i]['desc'], 'wrapper' => array('width' => '50'));
    }

    // TAB 7: Join The Kings
    $home_fields[] = array('key' => 'tab_home_jtk', 'label' => '7. Join The Kings', 'type' => 'tab');
    $home_fields[] = array('key' => 'field_home_jtk_title', 'label' => 'Section Title', 'name' => 'jtk_title', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'placeholder' => 'Join The Kings');
    for ($i = 1; $i <= 3; $i++) {
        $jtk_defaults = array(
            1 => array('title' => 'Why The Kings', 'link' => '/benefits/'),
            2 => array('title' => 'Engagements', 'link' => '/network/'),
            3 => array('title' => 'Community', 'link' => '/community/'),
        );
        $home_fields[] = array('key' => 'field_home_jtk_card' . $i . '_title', 'label' => 'Card ' . $i . ' Title', 'name' => 'jtk_card' . $i . '_title', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'placeholder' => $jtk_defaults[$i]['title'], 'wrapper' => array('width' => '33'));
        $home_fields[] = array('key' => 'field_home_jtk_card' . $i . '_link', 'label' => 'Card ' . $i . ' Link URL', 'name' => 'jtk_card' . $i . '_link', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'placeholder' => $jtk_defaults[$i]['link'], 'wrapper' => array('width' => '33'));
        $home_fields[] = array('key' => 'field_home_jtk_card' . $i . '_img', 'label' => 'Card ' . $i . ' Image', 'name' => 'jtk_card' . $i . '_img', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium', 'wrapper' => array('width' => '34'));
    }

    acf_add_local_field_group(array(
        'key' => 'group_homepage',
        'title' => 'Homepage Content & Sections',
        'fields' => $home_fields,
        'location' => array(array(array('param' => 'page_template', 'operator' => '==', 'value' => 'front-page.php')), array(array('param' => 'page_type', 'operator' => '==', 'value' => 'front_page'))),
        'hide_on_screen' => $hide_elements,
    ));


    // ==========================================
// 2. OUR STORY PAGE FIELDS
// ==========================================
    $story_fields = array(
        array('key' => 'tab_story_hero', 'label' => '1. Page Hero', 'type' => 'tab'),
        array('key' => 'field_story_headline', 'label' => 'Main Headline', 'name' => 'story_headline', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'instructions' => 'Hero section headline.', 'placeholder' => 'Our Story'),
        array('key' => 'field_story_desc', 'label' => 'Hero Description', 'name' => 'story_desc', 'type' => 'textarea', 'instructions' => 'Short description below the hero headline.', 'rows' => 2),
        array('key' => 'field_story_bg', 'label' => 'Background Image', 'name' => 'story_bg', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium', 'instructions' => 'Hero background image (recommended 2000×1000px).'),
        array('key' => 'field_story_hero_video', 'label' => 'Hero Video URL', 'name' => 'story_hero_video', 'type' => 'url', 'instructions' => 'Paste a YouTube or Vimeo URL — e.g. https://vimeo.com/123456789 or https://www.youtube.com/watch?v=XXXXX. The video is embedded automatically beside the hero text.', 'placeholder' => 'https://vimeo.com/1197690853'),

        array('key' => 'tab_story_vision', 'label' => '2. Vision & Mission', 'type' => 'tab'),
        array('key' => 'field_story_vision_title', 'label' => 'Section Title', 'name' => 'story_vision_title', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'placeholder' => 'Vision & Mission'),
        array('key' => 'field_story_vision_text', 'label' => 'Vision Text', 'name' => 'story_vision_text', 'type' => 'textarea', 'instructions' => 'The company\'s vision statement.', 'rows' => 3),
        array('key' => 'field_story_mission_text', 'label' => 'Mission Text 1', 'name' => 'story_mission_text', 'type' => 'textarea', 'instructions' => 'First paragraph of the mission statement.', 'rows' => 3),
        array('key' => 'field_story_mission_text_2', 'label' => 'Mission Text 2', 'name' => 'story_mission_text_2', 'type' => 'textarea', 'instructions' => 'Second paragraph of the mission statement.', 'rows' => 3),

        array('key' => 'tab_story_values', 'label' => '3. Core Values', 'type' => 'tab'),
        array('key' => 'field_story_values_title', 'label' => 'Section Title', 'name' => 'story_values_title', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'placeholder' => 'Core Values'),
        array('key' => 'field_story_values_intro', 'label' => 'Values Intro', 'name' => 'story_values_intro', 'type' => 'textarea', 'instructions' => 'Subtitle text below the section title.', 'rows' => 2),
    );

    // Manual Repeaters for Core Values (Max 5 - SCOUT)
    for ($i = 1; $i <= 5; $i++) {
        $story_fields[] = array('key' => 'field_story_v' . $i . '_title', 'label' => 'Value ' . $i . ' Title', 'name' => 'story_v' . $i . '_title', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'wrapper' => array('width' => '30'));
        $story_fields[] = array('key' => 'field_story_v' . $i . '_desc', 'label' => 'Value ' . $i . ' Desc', 'name' => 'story_v' . $i . '_desc', 'type' => 'textarea', 'wrapper' => array('width' => '70'));
    }

    // TAB 4: Our Roots
    $story_fields[] = array('key' => 'tab_story_timeline', 'label' => '4. Our Roots', 'type' => 'tab');
    $story_fields[] = array('key' => 'field_story_timeline_title', 'label' => 'Section Title', 'name' => 'story_timeline_title', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'placeholder' => 'Our Roots', 'instructions' => 'Heading for the Our Roots section.');
    $story_fields[] = array('key' => 'field_story_timeline_intro', 'label' => 'Section Subtitle', 'name' => 'story_timeline_intro', 'type' => 'textarea', 'instructions' => 'Short subtitle below the heading.', 'rows' => 2);

    // Manual Repeaters for Timeline Nodes (Max 7)
    for ($i = 1; $i <= 7; $i++) {
        $story_fields[] = array('key' => 'field_story_t' . $i . '_year', 'label' => 'Milestone ' . $i . ' Year', 'name' => 'story_t' . $i . '_year', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'wrapper' => array('width' => '20'));
        $story_fields[] = array('key' => 'field_story_t' . $i . '_title', 'label' => 'Milestone ' . $i . ' Title', 'name' => 'story_t' . $i . '_title', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'wrapper' => array('width' => '30'));
        $story_fields[] = array('key' => 'field_story_t' . $i . '_desc', 'label' => 'Milestone ' . $i . ' Desc', 'name' => 'story_t' . $i . '_desc', 'type' => 'textarea', 'wrapper' => array('width' => '50'), 'rows' => 2);
    }

    $story_fields = array_merge($story_fields, kg_get_cta_acf_fields('story', '5. CTA Banner'));

    acf_add_local_field_group(array(
        'key' => 'group_storypage',
        'title' => 'Story Page Sections',
        'fields' => $story_fields,
        'location' => array(array(array('param' => 'page_template', 'operator' => '==', 'value' => 'story.php'))),
        'hide_on_screen' => $hide_elements,
    ));

    // ==========================================
// 3. CAREERS PAGE FIELDS
// ==========================================
    acf_add_local_field_group(array(
        'key' => 'group_careerspage',
        'title' => 'Careers Page Sections',
        'fields' => array(
            array('key' => 'tab_careers_hero', 'label' => '1. Page Hero', 'type' => 'tab'),
            array('key' => 'field_careers_headline', 'label' => 'Main Headline', 'name' => 'careers_headline', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'instructions' => 'Hero headline. Supports HTML tags like &lt;br&gt; and &lt;span&gt;.'),
            array('key' => 'field_careers_desc', 'label' => 'Hero Description', 'name' => 'careers_desc', 'type' => 'textarea', 'rows' => 2),
            array('key' => 'field_careers_bg', 'label' => 'Background Image', 'name' => 'careers_bg', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium'),

            array('key' => 'tab_careers_application', 'label' => '2. Application Form Info', 'type' => 'tab'),
            array('key' => 'field_careers_form_title', 'label' => 'Form Section Title', 'name' => 'careers_form_title', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'placeholder' => 'Fast-Track Application'),
            array('key' => 'field_careers_form_desc', 'label' => 'Form Instructions', 'name' => 'careers_form_desc', 'type' => 'textarea', 'instructions' => 'Instructions text shown above the application form.', 'rows' => 2),
        ),
        'location' => array(array(array('param' => 'page_template', 'operator' => '==', 'value' => 'careers.php'))),
        'hide_on_screen' => $hide_elements,
    ));

    // ==========================================
// 4. MEMBER BENEFITS PAGE FIELDS
// ==========================================
    $benefits_fields = array(
        array('key' => 'tab_benefits_hero', 'label' => '1. Page Hero', 'type' => 'tab'),
        array('key' => 'field_benefits_headline', 'label' => 'Main Headline', 'name' => 'benefits_headline', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'placeholder' => 'Why Join Kings?'),
        array('key' => 'field_benefits_desc', 'label' => 'Hero Description', 'name' => 'benefits_desc', 'type' => 'textarea', 'rows' => 2),
        array('key' => 'field_benefits_bg', 'label' => 'Background Image', 'name' => 'benefits_bg', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium'),

        array('key' => 'tab_benefits_list', 'label' => '2. Member Benefits', 'type' => 'tab'),
        array('key' => 'field_benefits_list_title', 'label' => 'Section Title', 'name' => 'benefits_list_title', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'placeholder' => 'Our Member Benefits'),
        array('key' => 'field_benefits_list_desc', 'label' => 'Section Intro', 'name' => 'benefits_list_desc', 'type' => 'textarea', 'instructions' => 'Subtitle text shown below the section title.', 'rows' => 2),
    );

    // Manual Repeaters for Benefits (Max 10)
    for ($i = 1; $i <= 10; $i++) {
        $benefits_fields[] = array('key' => 'field_benefits_b' . $i . '_title', 'label' => 'Benefit ' . $i . ' Title', 'name' => 'benefits_b' . $i . '_title', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'wrapper' => array('width' => '30'));
        $benefits_fields[] = array('key' => 'field_benefits_b' . $i . '_desc', 'label' => 'Benefit ' . $i . ' Desc', 'name' => 'benefits_b' . $i . '_desc', 'type' => 'textarea', 'wrapper' => array('width' => '70'));
    }

    $benefits_fields = array_merge($benefits_fields, kg_get_cta_acf_fields('slab', '3. CTA Banner'));

    acf_add_local_field_group(array(
        'key' => 'group_benefitspage',
        'title' => 'Benefits Page Sections',
        'fields' => $benefits_fields,
        'location' => array(array(array('param' => 'page_template', 'operator' => '==', 'value' => 'benefits.php'))),
        'hide_on_screen' => $hide_elements,
    ));

    // ==========================================
// 5. SERVICES (LABOR) PAGE FIELDS
// ==========================================
    $slab_fields = array(
        // TAB 1: Hero
        array('key' => 'tab_slab_hero', 'label' => '1. Page Hero', 'type' => 'tab'),
        array('key' => 'field_slab_headline', 'label' => 'Main Headline', 'name' => 'slab_headline', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'instructions' => 'Hero headline.'),
        array('key' => 'field_slab_desc', 'label' => 'Hero Description', 'name' => 'slab_desc', 'type' => 'textarea', 'rows' => 2),
        array('key' => 'field_slab_bg', 'label' => 'Background Image', 'name' => 'slab_bg', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium'),
        array('key' => 'tab_slab_managed', 'label' => '2. Managed Services', 'type' => 'tab'),
        array('key' => 'field_slab_managed_label', 'label' => 'Section A Label', 'name' => 'slab_managed_label', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'placeholder' => 'Managed Services'),
        array('key' => 'field_slab_managed_title', 'label' => 'Section A Title', 'name' => 'slab_managed_title', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'placeholder' => 'Managed Services'),
        array('key' => 'field_slab_managed_desc', 'label' => 'Section A Description', 'name' => 'slab_managed_desc', 'type' => 'textarea', 'rows' => 3),
        array('key' => 'field_slab_intro_img', 'label' => 'Intro Image', 'name' => 'slab_intro_img', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium'),

        array('key' => 'field_slab_feat1_title', 'label' => 'Feature 1 Title', 'name' => 'slab_feat1_title', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'wrapper' => array('width' => '50')),
        array('key' => 'field_slab_feat1_desc', 'label' => 'Feature 1 Description', 'name' => 'slab_feat1_desc', 'type' => 'textarea', 'rows' => 2, 'wrapper' => array('width' => '50')),
        array('key' => 'field_slab_feat2_title', 'label' => 'Feature 2 Title', 'name' => 'slab_feat2_title', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'wrapper' => array('width' => '50')),
        array('key' => 'field_slab_feat2_desc', 'label' => 'Feature 2 Description', 'name' => 'slab_feat2_desc', 'type' => 'textarea', 'rows' => 2, 'wrapper' => array('width' => '50')),
        array('key' => 'field_slab_feat3_title', 'label' => 'Feature 3 Title', 'name' => 'slab_feat3_title', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'wrapper' => array('width' => '50')),
        array('key' => 'field_slab_feat3_desc', 'label' => 'Feature 3 Description', 'name' => 'slab_feat3_desc', 'type' => 'textarea', 'rows' => 2, 'wrapper' => array('width' => '50')),
        array('key' => 'field_slab_feat4_title', 'label' => 'Feature 4 Title', 'name' => 'slab_feat4_title', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'wrapper' => array('width' => '50')),
        array('key' => 'field_slab_feat4_desc', 'label' => 'Feature 4 Description', 'name' => 'slab_feat4_desc', 'type' => 'textarea', 'rows' => 2, 'wrapper' => array('width' => '50')),

        // Manpower fields (placed inside Managed Services tab)
        array('key' => 'field_slab_manpower_text', 'label' => 'Manpower Solutions Text', 'name' => 'slab_manpower_text', 'type' => 'textarea', 'rows' => 6),
        array('key' => 'field_slab_manpower_img', 'label' => 'Manpower Solutions Image', 'name' => 'slab_manpower_img', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium'),

        // TAB 3: Staff Leasing (Section B)
        array('key' => 'tab_slab_leasing', 'label' => '3. Staff Leasing', 'type' => 'tab'),
        array('key' => 'field_slab_lease_label', 'label' => 'Leasing Label', 'name' => 'slab_lease_label', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'placeholder' => 'Offshore Staff Leasing'),
        array('key' => 'field_slab_lease_title', 'label' => 'Leasing Title', 'name' => 'slab_lease_title', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'placeholder' => 'Managed Staff Leasing Services'),
        array('key' => 'field_slab_lease_desc', 'label' => 'Leasing Description', 'name' => 'slab_lease_desc', 'type' => 'textarea', 'rows' => 3),
        array('key' => 'field_slab_offshore_label', 'label' => 'How it Works Label', 'name' => 'slab_offshore_label', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'placeholder' => 'Cooperative Advantage'),
        array('key' => 'field_slab_offshore_title', 'label' => 'How it Works Title', 'name' => 'slab_offshore_title', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'placeholder' => 'How Does Offshore Managed Staff Leasing Work?'),
        array('key' => 'field_slab_offshore_text', 'label' => 'How Does it Work Text', 'name' => 'slab_offshore_text', 'type' => 'textarea', 'rows' => 6),
        array('key' => 'field_slab_improve_desc', 'label' => 'Improving Manpower Description', 'name' => 'slab_improve_desc', 'type' => 'textarea', 'rows' => 3),
        array('key' => 'field_slab_offshore_img', 'label' => 'How it Works Image', 'name' => 'slab_offshore_img', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium'),

        // Improving Manpower fields (placed inside Staff Leasing tab)
        array('key' => 'field_slab_improve_img', 'label' => 'Improving Manpower Image', 'name' => 'slab_improve_img', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium'),

        array('key' => 'field_slab_check1_title', 'label' => 'Checklist 1 Title', 'name' => 'slab_check1_title', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'wrapper' => array('width' => '30')),
        array('key' => 'field_slab_check1_desc', 'label' => 'Checklist 1 Description', 'name' => 'slab_check1_desc', 'type' => 'textarea', 'rows' => 2, 'wrapper' => array('width' => '70')),
        array('key' => 'field_slab_check2_title', 'label' => 'Checklist 2 Title', 'name' => 'slab_check2_title', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'wrapper' => array('width' => '30')),
        array('key' => 'field_slab_check2_desc', 'label' => 'Checklist 2 Description', 'name' => 'slab_check2_desc', 'type' => 'textarea', 'rows' => 2, 'wrapper' => array('width' => '70')),
        array('key' => 'field_slab_check3_title', 'label' => 'Checklist 3 Title', 'name' => 'slab_check3_title', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'wrapper' => array('width' => '30')),
        array('key' => 'field_slab_check3_desc', 'label' => 'Checklist 3 Description', 'name' => 'slab_check3_desc', 'type' => 'textarea', 'rows' => 2, 'wrapper' => array('width' => '70')),

        // TAB 4: Onboarding
        array('key' => 'tab_slab_onboarding', 'label' => '4. Onboarding Journey', 'type' => 'tab'),
        array('key' => 'field_slab_onboard_title', 'label' => 'Section Title', 'name' => 'slab_onboard_title', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'placeholder' => 'What is involved in offshore Managed Staff Leasing to the Philippines?'),
        array('key' => 'field_slab_onboard_desc', 'label' => 'Section Description', 'name' => 'slab_onboard_desc', 'type' => 'textarea', 'rows' => 2),

    );

    $slab_fields = array_merge($slab_fields, kg_get_cta_acf_fields('slab', '5. CTA Banner'));

    acf_add_local_field_group(array(
        'key' => 'group_servicelabor',
        'title' => 'Labor Service Sections',
        'fields' => $slab_fields,
        'location' => array(array(array('param' => 'page_template', 'operator' => '==', 'value' => 'service-labor.php'))),
        'hide_on_screen' => $hide_elements,
    ));

    // ==========================================
// 6. SERVICES (KIT) PAGE FIELDS
// ==========================================
    $skit_fields = array(
        // TAB 1: Hero
        array('key' => 'tab_skit_hero', 'label' => '1. Page Hero', 'type' => 'tab'),
        array('key' => 'field_skit_headline', 'label' => 'Main Headline', 'name' => 'skit_headline', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'placeholder' => 'HR & Kings Information Technology (KIT)'),
        array('key' => 'field_skit_desc', 'label' => 'Hero Description', 'name' => 'skit_desc', 'type' => 'textarea', 'rows' => 2, 'placeholder' => 'Proprietary Kings Information Technology System'),
        array('key' => 'field_skit_bg', 'label' => 'Background Image', 'name' => 'skit_bg', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium'),

        // TAB 2: HR & Payroll
        array('key' => 'tab_skit_hr', 'label' => '2. HR & Payroll', 'type' => 'tab'),
        array('key' => 'field_skit_hr_title', 'label' => 'HR Section Title', 'name' => 'skit_hr_title', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'placeholder' => 'HR & Payroll Management'),
        array('key' => 'field_skit_hr_desc', 'label' => 'HR Section Description', 'name' => 'skit_hr_desc', 'type' => 'textarea', 'rows' => 4),
        array('key' => 'field_skit_hr_list', 'label' => 'HR Features List', 'name' => 'skit_hr_list', 'type' => 'textarea', 'instructions' => 'Enter one feature per line.', 'rows' => 5),
        array('key' => 'field_skit_hr_img', 'label' => 'HR Section Image', 'name' => 'skit_hr_img', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium', 'instructions' => 'Image for the HR & Payroll Management split layout.'),

        // TAB 3: KIT System
        array('key' => 'tab_skit_platform', 'label' => '3. KIT System', 'type' => 'tab'),
        array('key' => 'field_skit_kit_title', 'label' => 'KIT Title', 'name' => 'skit_kit_title', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'placeholder' => 'Kings Information Technology (KIT)'),
        array('key' => 'field_skit_kit_desc', 'label' => 'KIT Description', 'name' => 'skit_kit_desc', 'type' => 'textarea', 'rows' => 4),
        array('key' => 'field_skit_intro_img1', 'label' => 'Platform Image', 'name' => 'skit_intro_img1', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium', 'instructions' => 'Main dashboard screenshot or platform image.'),

        // TAB 4: How We Work
        array('key' => 'tab_skit_hww', 'label' => '4. How We Work', 'type' => 'tab'),
        array('key' => 'field_skit_hww_title', 'label' => 'Workflow Title', 'name' => 'skit_hww_title', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'placeholder' => 'HOW WE WORK'),
        array('key' => 'field_skit_hww_desc', 'label' => 'Workflow Description', 'name' => 'skit_hww_desc', 'type' => 'textarea', 'rows' => 4),
        array('key' => 'field_skit_hww_list', 'label' => 'Workflow List', 'name' => 'skit_hww_list', 'type' => 'textarea', 'instructions' => 'Enter one workflow item per line.', 'rows' => 6),
        array('key' => 'field_skit_hww_img', 'label' => 'Workflow Image', 'name' => 'skit_hww_img', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium', 'instructions' => 'Image for the How We Work section.'),

        // TAB 5: Moving Forward
        array('key' => 'tab_skit_mf', 'label' => '5. Moving Forward', 'type' => 'tab'),
        array('key' => 'field_skit_mf_title', 'label' => 'Moving Forward Title', 'name' => 'skit_mf_title', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'placeholder' => 'Moving Forward'),
        array('key' => 'field_skit_mf_desc', 'label' => 'Moving Forward Description', 'name' => 'skit_mf_desc', 'type' => 'textarea', 'rows' => 4),
    );

    acf_add_local_field_group(array(
        'key' => 'group_servicekit',
        'title' => 'KIT Service Sections',
        'fields' => $skit_fields,
        'location' => array(array(array('param' => 'page_template', 'operator' => '==', 'value' => 'service-kit.php'))),
        'hide_on_screen' => $hide_elements,
    ));

    // ==========================================
// 7. NETWORK PAGE FIELDS
// ==========================================
    $network_fields = array(
        array('key' => 'tab_net_hero', 'label' => '1. Page Hero', 'type' => 'tab'),
        array('key' => 'field_net_headline', 'label' => 'Main Headline', 'name' => 'net_headline', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'placeholder' => 'Our Global Network'),
        array('key' => 'field_net_desc', 'label' => 'Hero Description', 'name' => 'net_desc', 'type' => 'textarea', 'rows' => 2),
        array('key' => 'field_net_bg', 'label' => 'Background Image', 'name' => 'net_bg', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium'),
    );

    // TAB 3: Engagements Section
    $network_fields[] = array('key' => 'tab_net_engagements', 'label' => '3. Engagements', 'type' => 'tab');
    $network_fields[] = array('key' => 'field_net_eng_title', 'label' => 'Engagements Section Title', 'name' => 'net_engagements_title', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'placeholder' => 'Industry Engagements');
    $network_fields[] = array('key' => 'field_net_eng_subtitle', 'label' => 'Engagements Section Subtitle', 'name' => 'net_engagements_subtitle', 'type' => 'textarea', 'rows' => 2);

    for ($i = 1; $i <= 7; $i++) {
        $network_fields[] = array('key' => 'tab_net_card_' . $i, 'label' => 'Card ' . $i, 'type' => 'tab');
        $network_fields[] = array('key' => 'field_net_card_' . $i . '_category', 'label' => 'Card ' . $i . ' Category', 'name' => 'net_card' . $i . '_category', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'wrapper' => array('width' => '33'));
        $network_fields[] = array('key' => 'field_net_card_' . $i . '_title', 'label' => 'Card ' . $i . ' Title', 'name' => 'net_card' . $i . '_title', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'wrapper' => array('width' => '33'));
        $network_fields[] = array('key' => 'field_net_card_' . $i . '_img', 'label' => 'Card ' . $i . ' Image', 'name' => 'net_card' . $i . '_img', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium', 'wrapper' => array('width' => '34'));
        $network_fields[] = array('key' => 'field_net_card_' . $i . '_desc', 'label' => 'Card ' . $i . ' Description', 'name' => 'net_card' . $i . '_desc', 'type' => 'textarea', 'rows' => 2);
        $network_fields[] = array('key' => 'field_net_card_' . $i . '_tags', 'label' => 'Card ' . $i . ' Role Tags', 'name' => 'net_card' . $i . '_tags', 'type' => 'textarea', 'rows' => 3, 'instructions' => 'Enter one role per line.');
    }

    $network_fields = array_merge($network_fields, kg_get_cta_acf_fields('slab', '4. CTA Banner'));

    acf_add_local_field_group(array(
        'key' => 'group_network',
        'title' => 'Network Page Sections',
        'fields' => $network_fields,
        'location' => array(array(array('param' => 'page_template', 'operator' => '==', 'value' => 'network.php'))),
        'hide_on_screen' => $hide_elements,
    ));

    // ==========================================
// 8. QUOTE PAGE FIELDS
// ==========================================
    acf_add_local_field_group(array(
        'key' => 'group_quote',
        'title' => 'Team Builder Page Sections',
        'fields' => array(
            array('key' => 'tab_quote_hero', 'label' => '1. Page Hero', 'type' => 'tab'),
            array('key' => 'field_quote_headline', 'label' => 'Main Headline', 'name' => 'quote_headline', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'instructions' => 'Hero headline for the Team Builder page.', 'placeholder' => 'Build Your Offshore Team'),
            array('key' => 'field_quote_desc', 'label' => 'Hero Description', 'name' => 'quote_desc', 'type' => 'textarea', 'instructions' => 'Short description below the hero headline.', 'rows' => 2),
            array('key' => 'field_quote_bg', 'label' => 'Background Image', 'name' => 'quote_bg', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium'),

            array('key' => 'tab_quote_builder', 'label' => '2. Builder Text', 'type' => 'tab'),
            array('key' => 'field_quote_b_title', 'label' => 'Builder Title', 'name' => 'quote_b_title', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'instructions' => 'Title above the team builder calculator.', 'placeholder' => 'Estimate Your Monthly Investment'),
            array('key' => 'field_quote_calc_instructions', 'label' => 'Calculator Instructions', 'name' => 'quote_calc_instructions', 'type' => 'textarea', 'instructions' => 'Instructions text below the builder title.', 'rows' => 2, 'placeholder' => 'Select roles, adjust experience levels, and see a transparent baseline.'),
        ),
        'location' => array(array(array('param' => 'page_template', 'operator' => '==', 'value' => 'quote.php'))),
        'hide_on_screen' => $hide_elements,
    ));

    // ==========================================
// 9. CONTACT PAGE FIELDS
// ==========================================
    acf_add_local_field_group(array(
        'key' => 'group_contact',
        'title' => 'Contact Page Sections',
        'fields' => array(
            array('key' => 'tab_contact_hero', 'label' => '1. Page Hero', 'type' => 'tab'),
            array('key' => 'field_contact_headline', 'label' => 'Main Headline', 'name' => 'contact_headline', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'placeholder' => 'Contact Us'),
            array('key' => 'field_contact_desc', 'label' => 'Hero Description', 'name' => 'contact_desc', 'type' => 'textarea', 'rows' => 2),
            array('key' => 'field_contact_bg', 'label' => 'Background Image', 'name' => 'contact_bg', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium'),

            array('key' => 'tab_contact_form', 'label' => '2. Form Section', 'type' => 'tab'),
            array('key' => 'field_contact_form_title_ph', 'label' => 'Form Title (PH/Local)', 'name' => 'contact_form_title_ph', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'placeholder' => 'Let’s Build Something Great Together'),
            array('key' => 'field_contact_form_desc_ph', 'label' => 'Form Description (PH/Local)', 'name' => 'contact_form_desc_ph', 'type' => 'textarea', 'rows' => 2, 'placeholder' => 'THE KINGS is your home for career growth, premium workspaces, and collaborative success in the Philippines. Reach out to start your journey today!'),
            array('key' => 'field_contact_form_title_intl', 'label' => 'Form Title (Intl)', 'name' => 'contact_form_title_intl', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'placeholder' => 'Scale Your Business With Elite Talent'),
            array('key' => 'field_contact_form_desc_intl', 'label' => 'Form Description (Intl)', 'name' => 'contact_form_desc_intl', 'type' => 'textarea', 'rows' => 2, 'placeholder' => 'Unlock high-performance offshore staffing solutions from the Philippines. Connect with our experts to build your dedicated global team.'),
            array('key' => 'field_contact_form_shortcode', 'label' => 'Contact Form Shortcode', 'name' => 'contact_form_shortcode', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'placeholder' => '[contact-form-7 id="123" title="Contact form 1"]'),

            array('key' => 'tab_contact_info', 'label' => '3. Corporate Info', 'type' => 'tab'),
            array('key' => 'field_contact_info_title', 'label' => 'Info Section Title', 'name' => 'contact_info_title', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'placeholder' => 'Contact Us'),
            array('key' => 'field_contact_telephone', 'label' => 'Telephone', 'name' => 'contact_telephone', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'placeholder' => '+63 (2) 87766712'),
            array('key' => 'field_contact_mobile', 'label' => 'Mobile', 'name' => 'contact_mobile', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'placeholder' => '+63 (917) 634 2088'),
            array('key' => 'field_contact_email', 'label' => 'Email', 'name' => 'contact_email', 'type' => 'email', 'placeholder' => 'info@kingsgroup.com.ph'),
            array('key' => 'field_contact_visit_title', 'label' => 'Visit Section Title', 'name' => 'contact_visit_title', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'placeholder' => 'Visit Us'),
            array('key' => 'field_contact_address', 'label' => 'Headquarters Address (Zamboanga)', 'name' => 'contact_address', 'type' => 'textarea', 'instructions' => 'Corporate HQ address in Zamboanga. Use &lt;br&gt; for line breaks.', 'rows' => 2, 'placeholder' => 'DVN Building, Melaño Calixto St, Zamboanga City, Zamboanga del Sur'),
            array('key' => 'field_contact_address_2', 'label' => 'Manila Office Address', 'name' => 'contact_address_2', 'type' => 'textarea', 'instructions' => 'Branch office address in Manila. Use &lt;br&gt; for line breaks.', 'rows' => 2, 'placeholder' => '100 Doña Soledad Avenue, Better Living, Paranaque City, Metro Manila, Philippines, 1711'),
        ),
        'location' => array(array(array('param' => 'page_template', 'operator' => '==', 'value' => 'contact.php'))),
        'hide_on_screen' => $hide_elements,
    ));
    // ==========================================
// 10. GLOBAL OPTIONS (FOOTER/SOCIAL)
// ==========================================
    if (function_exists('acf_add_options_page')) {
        acf_add_options_page(array(
            'page_title' => 'Theme General Settings',
            'menu_title' => 'Theme Settings',
            'menu_slug' => 'theme-general-settings',
            'capability' => 'edit_posts',
            'redirect' => false
        ));
    }

    acf_add_local_field_group(array(
        'key' => 'group_theme_options',
        'title' => 'Global Settings',
        'fields' => array(
            array('key' => 'tab_opt_footer', 'label' => 'Footer Text', 'type' => 'tab'),
            array('key' => 'field_opt_footer_desc', 'label' => 'Footer Description', 'name' => 'footer_description', 'type' => 'textarea'),

            array('key' => 'tab_opt_social', 'label' => 'Social Links', 'type' => 'tab'),
            array('key' => 'field_opt_social_fb', 'label' => 'Facebook URL', 'name' => 'social_fb', 'type' => 'url'),
            array('key' => 'field_opt_social_tw', 'label' => 'Twitter URL', 'name' => 'social_tw', 'type' => 'url'),
            array('key' => 'field_opt_social_li', 'label' => 'LinkedIn URL', 'name' => 'social_li', 'type' => 'url'),

            array('key' => 'tab_opt_jobs', 'label' => 'Careers & Jobs Labels', 'type' => 'tab'),
            array('key' => 'field_jobs_apply_instantly_title', 'label' => 'Apply Instantly Title', 'name' => 'jobs_apply_instantly_title', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'default_value' => 'Apply Instantly'),
            array('key' => 'field_jobs_apply_instantly_desc', 'label' => 'Apply Instantly Description', 'name' => 'jobs_apply_instantly_desc', 'type' => 'textarea', 'default_value' => 'Fast-track your application to our hiring coordinators. Form takes under 2 minutes.', 'rows' => 2),
            array('key' => 'field_jobs_perks_title', 'label' => 'Cooperative Perks Title', 'name' => 'jobs_perks_title', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'default_value' => 'Cooperative Perks & Advantages'),
            array('key' => 'field_jobs_trust_safety_text', 'label' => 'Trust & Safety Notice', 'name' => 'jobs_trust_safety_text', 'type' => 'textarea', 'default_value' => 'Trust & Safety: Kings Group Cooperative will never request payment or bank credentials during any stage of recruitment. Apply securely above.', 'rows' => 2),
        ),
        'location' => array(array(array('param' => 'options_page', 'operator' => '==', 'value' => 'theme-general-settings'))),
    ));

    // ==========================================
// 10. JOBS CPT FIELDS
// ==========================================
    acf_add_local_field_group(array(
        'key' => 'group_jobs',
        'title' => 'Job Details',
        'fields' => array(
            array(
                'key' => 'tab_job_info',
                'label' => 'Job Details',
                'type' => 'tab',
            ),
            array(
                'key' => 'field_job_card_image',
                'label' => 'Card Image',
                'name' => 'job_card_image',
                'type' => 'image',
                'instructions' => 'Optional: Upload an image to display on the job board card. If left blank, it falls back to the post Featured Image or a placeholder.',
                'return_format' => 'url',
                'preview_size' => 'medium',
            ),
            array(
                'key' => 'field_job_location_tax',
                'label' => 'Location',
                'name' => 'job_location_tax',
                'type' => 'taxonomy',
                'instructions' => 'Select the specific branch / location for this job.',
                'required' => 1,
                'taxonomy' => 'job_location_tax',
                'field_type' => 'select',
                'allow_null' => 0,
                'add_term' => 0,
                'save_terms' => 1,
                'load_terms' => 1,
                'return_format' => 'id',
            ),
            array(
                'key' => 'field_job_region',
                'label' => 'Region',
                'name' => 'job_region',
                'type' => 'select',
                'instructions' => 'Select the Philippine region where this job is based. Used for dashboard grouping.',
                'choices' => array(
                    'NCR' => 'NCR (National Capital Region)',
                    'Ilocos Region (I)' => 'Ilocos Region (I)',
                    'Cagayan Valley (II)' => 'Cagayan Valley (II)',
                    'Central Luzon (III)' => 'Central Luzon (III)',
                    'CALABARZON (IV-A)' => 'CALABARZON (IV-A)',
                    'MIMAROPA (IV-B)' => 'MIMAROPA (IV-B)',
                    'Bicol (V)' => 'Bicol (V)',
                    'Western Visayas (VI)' => 'Western Visayas (VI)',
                    'Central Visayas (VII)' => 'Central Visayas (VII)',
                    'Eastern Visayas (VIII)' => 'Eastern Visayas (VIII)',
                    'Zamboanga Peninsula (IX)' => 'Zamboanga Peninsula (IX)',
                    'Northern Mindanao (X)' => 'Northern Mindanao (X)',
                    'Davao Region (XI)' => 'Davao Region (XI)',
                    'SOCCSKSARGEN (XII)' => 'SOCCSKSARGEN (XII)',
                    'Caraga (XIII)' => 'Caraga (XIII)',
                    'BARMM' => 'BARMM',
                    'CAR' => 'CAR (Cordillera Administrative Region)',
                    'Nationwide' => 'Nationwide / Multiple Regions',
                    'Remote / WFH' => 'Remote / WFH',
                ),
                'default_value' => '',
                'allow_null' => 1,
                'return_format' => 'value',
            ),
            array(
                'key' => 'field_job_type',
                'label' => 'Employment Type',
                'name' => 'job_type',
                'type' => 'select',
                'choices' => array(
                    'FULL_TIME' => 'Full-time',
                    'PART_TIME' => 'Part-time',
                    'CONTRACTOR' => 'Remote',
                    'OTHER' => 'Other',
                ),
                'default_value' => 'FULL_TIME',
                'allow_null' => 0,
                'return_format' => 'value',
            ),
            array(
                'key' => 'field_job_work_setup',
                'label' => 'Work Setup',
                'name' => 'job_work_setup',
                'type' => 'select',
                'choices' => array(
                    'WFO' => 'Office-Based (WFO)',
                    'WFH' => 'Home-Based (WFH)',
                    'Hybrid' => 'Hybrid Setup',
                ),
                'default_value' => 'WFO',
                'allow_null' => 0,
                'return_format' => 'value',
            ),

            array(
                'key' => 'field_job_department',
                'label' => 'Department',
                'name' => 'job_department',
                'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2,
                'placeholder' => 'e.g. Operations, Technology, HR',
            ),
            array(
                'key' => 'field_job_target_headcount',
                'label' => 'Target Headcount',
                'name' => 'job_target_headcount',
                'type' => 'number',
                'instructions' => 'Total number of hires needed for this role. Set to 0 or leave blank for unlimited.',
                'min' => 0,
                'placeholder' => '0',
                'wrapper' => array('width' => '50'),
            ),
            array(
                'key' => 'field_job_filled_headcount',
                'label' => 'Filled Headcount',
                'name' => 'job_filled_headcount',
                'type' => 'number',
                'instructions' => 'Number of positions already filled. When this equals Target, the job auto-hides from the front-end.',
                'min' => 0,
                'placeholder' => '0',
                'wrapper' => array('width' => '50'),
            ),
            array(
                'key' => 'tab_job_pricing',
                'label' => 'Quote Builder Settings',
                'type' => 'tab',
            ),
            array(
                'key' => 'field_job_base_price',
                'label' => 'Base Monthly Price (AUD)',
                'name' => 'base_price',
                'type' => 'number',
                'instructions' => 'The base monthly cost for this role at Junior level (in AUD). Mid-Level = ×1.4, Senior = ×1.8.',
                'placeholder' => '2000',
                'prepend' => 'A$',
                'wrapper' => array('width' => '50'),
            ),
            array(
                'key' => 'field_job_include_team_builder',
                'label' => 'Show in Team Builder?',
                'name' => 'include_in_team_builder',
                'type' => 'true_false',
                'instructions' => 'Enable to show this job in the Quote page calculator.',
                'default_value' => 1,
                'ui' => 1,
                'wrapper' => array('width' => '50'),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'jobs',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => array(),
    ));


    // ==========================================
// 11. JOBS LIST PAGE FIELDS
// ==========================================
    acf_add_local_field_group(array(
        'key' => 'group_jobspage',
        'title' => 'Jobs Page Sections',
        'fields' => array(
            array('key' => 'tab_jobs_hero', 'label' => '1. Page Hero', 'type' => 'tab'),
            array('key' => 'field_jobs_hero_headline', 'label' => 'Main Headline', 'name' => 'jobs_hero_headline', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'placeholder' => 'Our Jobs'),
            array('key' => 'field_jobs_hero_desc', 'label' => 'Hero Description', 'name' => 'jobs_hero_desc', 'type' => 'textarea', 'rows' => 2),
            array('key' => 'field_jobs_hero_bg', 'label' => 'Background Image', 'name' => 'jobs_hero_bg', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium'),

            array('key' => 'tab_jobs_search', 'label' => '2. Search & Filters', 'type' => 'tab'),
            array('key' => 'field_jobs_search_keyword_label', 'label' => 'Keyword Label', 'name' => 'jobs_search_keyword_label', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'default_value' => 'Title or Keyword'),
            array('key' => 'field_jobs_search_keyword_placeholder', 'label' => 'Keyword Input Placeholder', 'name' => 'jobs_search_keyword_placeholder', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'default_value' => 'Type Here'),
            array('key' => 'field_jobs_search_region_label', 'label' => 'Select Region Label', 'name' => 'jobs_search_region_label', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'default_value' => 'Select Region'),
            array('key' => 'field_jobs_search_location_label', 'label' => 'Select Location Label', 'name' => 'jobs_search_location_label', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'default_value' => 'Select Location'),
            array('key' => 'field_jobs_search_btn_text', 'label' => 'Search Button Text', 'name' => 'jobs_search_btn_text', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'default_value' => 'SEARCH JOB'),
            array('key' => 'field_jobs_popular_searches_label', 'label' => 'Popular Searches Label', 'name' => 'jobs_popular_searches_label', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'default_value' => 'Popular Search:'),
            array('key' => 'field_jobs_popular_searches_tags', 'label' => 'Popular Search Tags (New Line Separated)', 'name' => 'jobs_popular_searches_tags', 'type' => 'textarea', 'default_value' => "Service Crew\nMerchandiser\nSales Associate\nWarehouseman\nDriver\nProduction Helper", 'rows' => 6),
            array('key' => 'field_jobs_upload_cv_label', 'label' => 'Upload CV Heading', 'name' => 'jobs_upload_cv_label', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'default_value' => 'Upload Your CV'),
            array('key' => 'field_jobs_drag_drop_text', 'label' => 'Drag & Drop Text', 'name' => 'jobs_drag_drop_text', 'type' => 'textarea', 'default_value' => 'Drag and drop your document here or browse for a document to upload', 'rows' => 2),
            array('key' => 'field_jobs_file_format_note', 'label' => 'File Format Note', 'name' => 'jobs_file_format_note', 'type' => 'textarea', 'default_value' => 'File names cannot contain spaces or underscores and should be in either .doc, .docx, or .pdf format.', 'rows' => 2),
        ),
        'location' => array(array(array('param' => 'page_template', 'operator' => '==', 'value' => 'our-jobs.php'))),
        'hide_on_screen' => $hide_elements,
    ));

    // ==========================================
// 12. COMMUNITY PAGE FIELDS
// ==========================================
    if (function_exists('acf_add_local_field_group')) {
        acf_add_local_field_group(array(
            'key' => 'group_community_page',
            'title' => 'Community Page Fields',
            'fields' => array(
                array('key' => 'field_comm_hero_title', 'label' => 'Hero Title', 'name' => 'comm_hero_title', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'default_value' => 'Our Commitment to Community'),
                array('key' => 'field_comm_hero_desc', 'label' => 'Hero Description', 'name' => 'comm_hero_desc', 'type' => 'textarea', 'default_value' => 'Building a sustainable future through education, empowerment, and shared success.', 'rows' => 2),
                array('key' => 'field_comm_hero_bg', 'label' => 'Hero Background Image', 'name' => 'comm_hero_bg', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium'),
                array('key' => 'field_comm_impact_intro', 'label' => 'Impact Intro', 'name' => 'comm_impact_intro', 'type' => 'textarea', 'default_value' => 'Community is essential to our mission and it is our responsibility to support the aspirations of our members by providing scholarships to our members and their dependents.'),
                array('key' => 'field_comm_stat1_num', 'label' => 'Stat 1 Number', 'name' => 'comm_stat1_num', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'default_value' => '500+', 'wrapper' => array('width' => '50')),
                array('key' => 'field_comm_stat1_label', 'label' => 'Stat 1 Label', 'name' => 'comm_stat1_label', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'default_value' => 'Scholarships Awarded', 'wrapper' => array('width' => '50')),
                array('key' => 'field_comm_stat2_num', 'label' => 'Stat 2 Number', 'name' => 'comm_stat2_num', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'default_value' => '100%', 'wrapper' => array('width' => '50')),
                array('key' => 'field_comm_stat2_label', 'label' => 'Stat 2 Label', 'name' => 'comm_stat2_label', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'default_value' => 'Member Focused', 'wrapper' => array('width' => '50')),
                array('key' => 'field_comm_impact_img', 'label' => 'Impact Image', 'name' => 'comm_impact_img', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium'),
                array('key' => 'field_comm_queens_title', 'label' => 'Queens Section Title', 'name' => 'comm_queens_title', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'default_value' => 'Queens of Kings Group'),
                array('key' => 'field_comm_queens_desc', 'label' => 'Queens Section Description', 'name' => 'comm_queens_desc', 'type' => 'textarea', 'default_value' => 'Dedicated to empowering women within the Kings Group network through specialized resources, mentorship, and support structures designed for professional and personal growth.'),
                array('key' => 'field_comm_queens_img', 'label' => 'Queens Visual Image', 'name' => 'comm_queens_img', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium'),
                array('key' => 'field_comm_culinary_tag', 'label' => 'Culinary Section Tag', 'name' => 'comm_culinary_tag', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'default_value' => 'Education'),
                array('key' => 'field_comm_culinary_intro', 'label' => 'Culinary School Intro', 'name' => 'comm_culinary_intro', 'type' => 'textarea', 'default_value' => 'We built Home Culinary and Technical School to have a sustainable education and livelihood programs for our members and their families.'),
                array('key' => 'field_comm_culinary_desc', 'label' => 'Culinary School Description', 'name' => 'comm_culinary_desc', 'type' => 'textarea', 'default_value' => 'As The Kings expands, so does our scholarship program with Home Culinary and Technical School. We are TESDA accredited and certified.'),
                array('key' => 'field_comm_culinary_img', 'label' => 'Culinary School Image', 'name' => 'comm_culinary_img', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium', 'instructions' => 'Upload a 16:9 image (e.g. 800x450px) for the culinary school card.'),
                array('key' => 'field_comm_courses_title', 'label' => 'Courses Title', 'name' => 'comm_courses_title', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'default_value' => 'Available Programs'),
                array('key' => 'field_comm_courses_subtitle', 'label' => 'Courses Subtitle', 'name' => 'comm_courses_subtitle', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'default_value' => 'TESDA accredited and certified certifications for professional growth.'),
                array('key' => 'field_comm_courses_list', 'label' => 'Courses List (New Line Separated)', 'name' => 'comm_courses_list', 'type' => 'textarea', 'default_value' => "Culinary Arts\nCookery NC II\nBread and Pastry NC II\nFood and Beverage Services NC II\nHousekeeping NC II", 'rows' => 5),
                array('key' => 'field_comm_scholarship_btn_text', 'label' => 'Scholarship Button Text', 'name' => 'comm_scholarship_btn_text', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'default_value' => 'Apply for Scholarship'),
                array('key' => 'field_comm_scholarship_btn_url', 'label' => 'Scholarship Button URL', 'name' => 'comm_scholarship_btn_url', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'default_value' => 'https://thehomeculinaryschool.com/'),
            ),
            'location' => array(array(array('param' => 'page_template', 'operator' => '==', 'value' => 'community.php'))),
        ));
    }

    // ==========================================
    // 13. NEWS PAGE FIELDS
    // ==========================================
    acf_add_local_field_group(array(
        'key' => 'group_newspage',
        'title' => 'News Page Sections',
        'fields' => array(
            array('key' => 'tab_news_hero', 'label' => '1. Page Hero', 'type' => 'tab'),
            array('key' => 'field_news_headline', 'label' => 'Main Headline', 'name' => 'news_headline', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'placeholder' => 'Kings Group Newsroom'),
            array('key' => 'field_news_desc', 'label' => 'Hero Description', 'name' => 'news_desc', 'type' => 'textarea', 'rows' => 2),
            array('key' => 'field_news_bg', 'label' => 'Background Image', 'name' => 'news_bg', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium'),

            array('key' => 'tab_news_labels', 'label' => '2. Section Labels & Empty State', 'type' => 'tab'),
            array('key' => 'field_news_featured_tag', 'label' => 'Featured Post Tag', 'name' => 'news_featured_tag', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'default_value' => 'Latest News'),
            array('key' => 'field_news_more_title', 'label' => 'More News Title', 'name' => 'news_more_title', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'default_value' => 'More News'),
            array('key' => 'field_news_read_time_label', 'label' => 'Read Time Label', 'name' => 'news_read_time_label', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'default_value' => 'min read'),
            array('key' => 'field_news_empty_title', 'label' => 'Empty State Title', 'name' => 'news_empty_title', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'default_value' => 'News updates coming soon'),
            array('key' => 'field_news_empty_desc', 'label' => 'Empty State Description', 'name' => 'news_empty_desc', 'type' => 'textarea', 'default_value' => 'We are crafting articles about cooperative developments and corporate highlights. Check back in a few days!', 'rows' => 3),
        ),
        'location' => array(
            array(
                array('param' => 'page_template', 'operator' => '==', 'value' => 'news.php')
            ),
            array(
                array('param' => 'page_type', 'operator' => '==', 'value' => 'posts_page')
            )
        ),
        'hide_on_screen' => $hide_elements,
    ));

    // ==========================================
    // 14. TERMS OF SERVICE PAGE FIELDS
    // ==========================================
    acf_add_local_field_group(array(
        'key' => 'group_termspage',
        'title' => 'Terms of Service Page Sections',
        'fields' => array(
            array('key' => 'tab_terms_hero', 'label' => '1. Page Hero', 'type' => 'tab'),
            array('key' => 'field_terms_headline', 'label' => 'Main Headline', 'name' => 'terms_headline', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'placeholder' => 'Terms of Service'),
            array('key' => 'field_terms_desc', 'label' => 'Hero Description', 'name' => 'terms_desc', 'type' => 'textarea', 'rows' => 2),
            array('key' => 'field_terms_bg', 'label' => 'Background Image', 'name' => 'terms_bg', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium'),
        ),
        'location' => array(
            array(
                array('param' => 'page_template', 'operator' => '==', 'value' => 'terms.php')
            )
        ),
        'hide_on_screen' => $hide_elements,
    ));

    // ==========================================
    // 15. PRIVACY POLICY PAGE FIELDS
    // ==========================================
    acf_add_local_field_group(array(
        'key' => 'group_privacypage',
        'title' => 'Privacy Policy Page Sections',
        'fields' => array(
            array('key' => 'tab_privacy_hero', 'label' => '1. Page Hero', 'type' => 'tab'),
            array('key' => 'field_privacy_headline', 'label' => 'Main Headline', 'name' => 'privacy_headline', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'placeholder' => 'Privacy Policy'),
            array('key' => 'field_privacy_desc', 'label' => 'Hero Description', 'name' => 'privacy_desc', 'type' => 'textarea', 'rows' => 2),
            array('key' => 'field_privacy_bg', 'label' => 'Background Image', 'name' => 'privacy_bg', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium'),
        ),
        'location' => array(
            array(
                array('param' => 'page_template', 'operator' => '==', 'value' => 'privacy.php')
            )
        ),
        'hide_on_screen' => $hide_elements,
    ));

    // ==========================================
    // 16. TRUST & SAFETY PAGE FIELDS
    // ==========================================
    acf_add_local_field_group(array(
        'key' => 'group_trustpage',
        'title' => 'Trust & Safety Page Sections',
        'fields' => array(
            array('key' => 'tab_trust_hero', 'label' => '1. Page Hero', 'type' => 'tab'),
            array('key' => 'field_trust_headline', 'label' => 'Main Headline', 'name' => 'trust_headline', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'placeholder' => 'Trust & Safety'),
            array('key' => 'field_trust_desc', 'label' => 'Hero Description', 'name' => 'trust_desc', 'type' => 'textarea', 'rows' => 2),
            array('key' => 'field_trust_bg', 'label' => 'Background Image', 'name' => 'trust_bg', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium'),
        ),
        'location' => array(
            array(
                array('param' => 'page_template', 'operator' => '==', 'value' => 'trust-safety.php')
            )
        ),
        'hide_on_screen' => $hide_elements,
    ));

    // ==========================================
    // 17. HIDE CATEGORIES ON STANDARD POSTS
    // ==========================================
    acf_add_local_field_group(array(
        'key' => 'group_post_cleanup',
        'title' => 'Post Cleanup',
        'fields' => array(),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'post',
                ),
            ),
        ),
        'menu_order' => 99,
        'hide_on_screen' => array('categories'),
    ));


    // ==========================================
    // 18. CAREERS WIZARD FIELDS
    // ==========================================
    acf_add_local_field_group(array(
        'key' => 'group_careers_wizard',
        'title' => 'Careers Wizard Form Settings',
        'fields' => array(
            array('key' => 'tab_careers_form', 'label' => 'Form Text Overrides', 'type' => 'tab'),
            array('key' => 'field_careers_form_title', 'label' => 'Form Title', 'name' => 'careers_form_title', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'default_value' => 'Fast-Track Application'),
            array('key' => 'field_careers_form_desc', 'label' => 'Form Description', 'name' => 'careers_form_desc', 'type' => 'textarea', 'default_value' => 'No long forms — upload your CV and we\'ll match you to the right role.'),
            array('key' => 'field_careers_step1_label', 'label' => 'Step 1 Label', 'name' => 'careers_step1_label', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'default_value' => 'Upload CV'),
            array('key' => 'field_careers_step2_label', 'label' => 'Step 2 Label', 'name' => 'careers_step2_label', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'default_value' => 'Your Info'),

            array('key' => 'tab_careers_success', 'label' => 'Success & Summary Labels', 'type' => 'tab'),
            array('key' => 'field_careers_success_title', 'label' => 'Success Title', 'name' => 'careers_success_title', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'default_value' => 'Application Received!'),
            array('key' => 'field_careers_success_desc', 'label' => 'Success Description', 'name' => 'careers_success_desc', 'type' => 'textarea', 'default_value' => 'Thank you for applying. Our talent team will review your profile and reach out within 2–3 business days.', 'rows' => 2),
            array('key' => 'field_careers_check_btn', 'label' => 'Check Application Button Text', 'name' => 'careers_check_btn', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'default_value' => 'Check Application'),
            array('key' => 'field_careers_back_btn', 'label' => 'Back to Home Button Text', 'name' => 'careers_back_btn', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'default_value' => 'Back to Home'),
            array('key' => 'field_careers_summary_title', 'label' => 'Summary Title', 'name' => 'careers_summary_title', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'default_value' => 'Application Summary'),
            array('key' => 'field_careers_rev_cv_label', 'label' => 'Summary: CV File Label', 'name' => 'careers_rev_cv_label', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'default_value' => 'CV File'),
            array('key' => 'field_careers_rev_name_label', 'label' => 'Summary: Name Label', 'name' => 'careers_rev_name_label', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'default_value' => 'Name'),
            array('key' => 'field_careers_rev_email_label', 'label' => 'Summary: Email Label', 'name' => 'careers_rev_email_label', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'default_value' => 'Email'),
            array('key' => 'field_careers_rev_phone_label', 'label' => 'Summary: Phone Label', 'name' => 'careers_rev_phone_label', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'default_value' => 'Phone'),
            array('key' => 'field_careers_rev_role_label', 'label' => 'Summary: Preferred Role Label', 'name' => 'careers_rev_role_label', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'default_value' => 'Preferred Role'),
        ),
        'location' => array(
            array(
                array('param' => 'page_template', 'operator' => '==', 'value' => 'careers.php')
            )
        ),
    ));

    // ==========================================
    // 19. CONTACT FORM FIELDS
    // ==========================================

    // ==========================================
    // 20. SERVICE KIT PAGE FIELDS
    // ==========================================
    acf_add_local_field_group(array(
        'key' => 'group_service_kit',
        'title' => 'Service Kit (KIT) Page Fields',
        'fields' => array(
            array('key' => 'tab_skit_hero', 'label' => '1. Page Hero', 'type' => 'tab'),
            array('key' => 'field_skit_headline', 'label' => 'Main Headline', 'name' => 'skit_headline', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'default_value' => 'HR & Kings Information Technology (KIT)'),
            array('key' => 'field_skit_desc', 'label' => 'Hero Description', 'name' => 'skit_desc', 'type' => 'textarea', 'default_value' => 'Proprietary Kings Information Technology System', 'rows' => 2),
            array('key' => 'field_skit_bg', 'label' => 'Background Image', 'name' => 'skit_bg', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium'),

            array('key' => 'tab_skit_hr', 'label' => '2. HR & Payroll Section', 'type' => 'tab'),
            array('key' => 'field_skit_hr_title', 'label' => 'HR Section Title', 'name' => 'skit_hr_title', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'default_value' => 'HR & Payroll Management'),
            array('key' => 'field_skit_hr_desc', 'label' => 'HR Section Description', 'name' => 'skit_hr_desc', 'type' => 'textarea', 'default_value' => 'Human resource management, consulting and benefits administration are crucial aspects of the business that The Kings can manage for you. Payroll and HR Experts from The Kings, who are familiar with the local laws and taxations will handle your employees so you can focus on the revenue-generating activities of your business. They can either be placed in your office or work from our own corporate offices in Parañaque City.', 'rows' => 6),
            array('key' => 'field_skit_hr_list', 'label' => 'HR Features List (New Line Separated)', 'name' => 'skit_hr_list', 'type' => 'textarea', 'default_value' => "Recruitment, Selection and Deployment\nOrientation and Training\nTimekeeping and Payroll\nCompensation Programs\nManagement and Legal Processes", 'rows' => 5),
            array('key' => 'field_skit_hr_img', 'label' => 'HR Section Image', 'name' => 'skit_hr_img', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium'),

            array('key' => 'tab_skit_kit', 'label' => '3. KIT Platform Section', 'type' => 'tab'),
            array('key' => 'field_skit_kit_title', 'label' => 'KIT Title', 'name' => 'skit_kit_title', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'default_value' => 'Kings Information Technology (KIT)'),
            array('key' => 'field_skit_kit_label', 'label' => 'KIT Subtitle/Label', 'name' => 'skit_kit_label', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'default_value' => 'HR & Payroll System'),
            array('key' => 'field_skit_kit_desc', 'label' => 'KIT Description', 'name' => 'skit_kit_desc', 'type' => 'textarea', 'default_value' => "Kings Information Technology is a software the company aimed to create offering the best solution for the Philippines HR demands— Philippines has a great need for a localized software that is why KIT was born.\n\nOur goal is to help companies in the Philippines grow through our suite of backend solutions that address payroll, HR and recruitment challenges.", 'rows' => 5),
            array('key' => 'field_skit_intro_img1', 'label' => 'KIT Section Image', 'name' => 'skit_intro_img1', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium'),

            array('key' => 'tab_skit_hww', 'label' => '4. How We Work', 'type' => 'tab'),
            array('key' => 'field_skit_hww_title', 'label' => 'How We Work Title', 'name' => 'skit_hww_title', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'default_value' => 'HOW WE WORK'),
            array('key' => 'field_skit_hww_desc', 'label' => 'How We Work Description', 'name' => 'skit_hww_desc', 'type' => 'textarea', 'default_value' => 'Our work structure is uniquely tailored to a process that involves accountability, transparency and drive from all our teams. The Kings practices the flexibility of continuously adapting to changes and trends in the industry focusing on the delivering of quality product for our client’s satisfaction. We make sure we deliver on-time, with the best quality, right at your fingertips.', 'rows' => 5),
            array('key' => 'field_skit_hww_list', 'label' => 'How We Work Features List (New Line Separated)', 'name' => 'skit_hww_list', 'type' => 'textarea', 'default_value' => "Time and Attendance Monitoring\nPayslip Generation (Online Viewing)\nGovernment mandated remittances and reports\nUpdated Report on Payroll and Tax\nHandling Labor Management related issues\nEmployer access to Employees' Time record", 'rows' => 6),
            array('key' => 'field_skit_hww_img', 'label' => 'How We Work Image', 'name' => 'skit_hww_img', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium'),

            array('key' => 'tab_skit_mf', 'label' => '5. Moving Forward', 'type' => 'tab'),
            array('key' => 'field_skit_mf_title', 'label' => 'Moving Forward Title', 'name' => 'skit_mf_title', 'type' => 'textarea', 'new_lines' => 'br', 'rows' => 2, 'default_value' => 'Moving Forward'),
            array('key' => 'field_skit_mf_desc', 'label' => 'Moving Forward Description', 'name' => 'skit_mf_desc', 'type' => 'textarea', 'default_value' => 'The Kings has a smooth track record and an expert in the said industry for over 10 years. We will be glad to meet with you, personally or virtually, to clarify any concern and work on the engagement that fits your current and future requirements.', 'rows' => 4),
        ),
        'location' => array(
            array(
                array('param' => 'page_template', 'operator' => '==', 'value' => 'service-kit.php')
            )
        ),
        'hide_on_screen' => $hide_elements,
    ));


endif;