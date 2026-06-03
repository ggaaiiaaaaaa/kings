<?php
if (function_exists('acf_add_local_field_group')):

    // Common setting to hide default editor clutter
    $hide_elements = array('the_content', 'excerpt', 'discussion', 'comments', 'revisions', 'slug', 'author', 'format', 'featured_image', 'categories', 'tags', 'send-trackbacks');

    // ==========================================
// 1. FRONT PAGE (HOME) FIELDS
// ==========================================
    $home_fields = array(
        // TAB 1: Hero
        array('key' => 'tab_home_hero', 'label' => '1. Hero Section', 'type' => 'tab'),
        array('key' => 'field_home_hero_headline', 'label' => 'Headline', 'name' => 'hero_headline', 'type' => 'text', 'instructions' => 'Main hero headline. Use &lt;br&gt; for line breaks, &lt;span&gt; for styled text.', 'placeholder' => 'Elite Talent. Ethical Staffing.'),
        array('key' => 'field_home_hero_desc', 'label' => 'Description', 'name' => 'hero_description', 'type' => 'textarea', 'instructions' => 'Supporting paragraph below the headline.', 'rows' => 3),
        
        array('key' => 'field_home_hero_img_1', 'label' => 'Slide 1 — Background Image', 'name' => 'hero_img_1', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium', 'instructions' => 'Hero slider image 1 (recommended 2000×1000px).'),
        array('key' => 'field_home_hero_img_2', 'label' => 'Slide 2 — Background Image', 'name' => 'hero_img_2', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium', 'instructions' => 'Hero slider image 2.'),
        array('key' => 'field_home_hero_img_3', 'label' => 'Slide 3 — Background Image', 'name' => 'hero_img_3', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium', 'instructions' => 'Hero slider image 3.'),
    );

    // Add remaining TABs
    $home_fields = array_merge($home_fields, array(
        // TAB 2: Trust Bar
        array('key' => 'tab_home_trust', 'label' => '2. Trust Bar', 'type' => 'tab'),
        array('key' => 'field_home_trust_label', 'label' => 'Trust Bar Label', 'name' => 'trust_label', 'type' => 'text', 'instructions' => 'Text displayed above the scrolling client logos.', 'placeholder' => 'Trusted by leading organizations worldwide'),

        // TAB 3: Staffing Intro
        array('key' => 'tab_home_intro', 'label' => '3. Staffing Intro', 'type' => 'tab'),
        array('key' => 'field_home_intro_title', 'label' => 'Section Title', 'name' => 'home_intro_title', 'type' => 'text', 'instructions' => 'Heading for the staffing intro section.', 'placeholder' => 'A Different Kind of Staffing'),
        array('key' => 'field_home_intro_sub', 'label' => 'Section Subtitle', 'name' => 'home_intro_sub', 'type' => 'textarea', 'instructions' => 'Short description below the section title.', 'rows' => 2),

        // TAB 4: The Advantage (Clients)
        array('key' => 'tab_home_advantage', 'label' => '4. Client Advantage', 'type' => 'tab'),
        array('key' => 'field_home_adv_headline', 'label' => 'Headline', 'name' => 'adv_headline', 'type' => 'text', 'instructions' => 'Main headline for the "For Clients" panel. Supports &lt;br&gt; for line breaks.'),
        array('key' => 'field_home_adv_sub', 'label' => 'Sub-Headline', 'name' => 'adv_subheadline', 'type' => 'text', 'instructions' => 'Bold tagline below the headline.'),
        array('key' => 'field_home_adv_desc', 'label' => 'Description', 'name' => 'adv_desc', 'type' => 'textarea', 'rows' => 3),
        array('key' => 'field_home_adv_stat', 'label' => 'Member Count Stat', 'name' => 'adv_stat', 'type' => 'number', 'instructions' => 'Animated counter number (e.g. 10000).'),
        array('key' => 'field_home_adv_img', 'label' => 'Section Image', 'name' => 'adv_img', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium'),
    ));

    // Manual Repeaters for Advantage Features (Max 3)
    for ($i = 1; $i <= 3; $i++) {
        $home_fields[] = array('key' => 'field_home_adv_f' . $i . '_title', 'label' => 'Feature ' . $i . ' Title', 'name' => 'adv_f' . $i . '_title', 'type' => 'text', 'wrapper' => array('width' => '50'));
        $home_fields[] = array('key' => 'field_home_adv_f' . $i . '_desc', 'label' => 'Feature ' . $i . ' Desc', 'name' => 'adv_f' . $i . '_desc', 'type' => 'textarea', 'wrapper' => array('width' => '50'));
    }

    // TAB 5: Applicant Focus
    $home_fields[] = array('key' => 'tab_home_applicant', 'label' => '5. Applicant Focus', 'type' => 'tab');
    $home_fields[] = array('key' => 'field_home_app_headline', 'label' => 'Headline', 'name' => 'app_headline', 'type' => 'text', 'instructions' => 'Main headline for the "For Applicants" panel.');
    $home_fields[] = array('key' => 'field_home_app_sub', 'label' => 'Sub-Headline', 'name' => 'app_subheadline', 'type' => 'text', 'instructions' => 'Bold tagline below the headline.');
    $home_fields[] = array('key' => 'field_home_app_desc', 'label' => 'Description', 'name' => 'app_desc', 'type' => 'textarea', 'rows' => 3);
    $home_fields[] = array('key' => 'field_home_app_img', 'label' => 'Section Image', 'name' => 'app_img', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium');

    // Manual Repeaters for Applicant Features (Max 3)
    for ($i = 1; $i <= 3; $i++) {
        $home_fields[] = array('key' => 'field_home_app_f' . $i . '_title', 'label' => 'Feature ' . $i . ' Title', 'name' => 'app_f' . $i . '_title', 'type' => 'text', 'wrapper' => array('width' => '50'));
        $home_fields[] = array('key' => 'field_home_app_f' . $i . '_desc', 'label' => 'Feature ' . $i . ' Desc', 'name' => 'app_f' . $i . '_desc', 'type' => 'textarea', 'wrapper' => array('width' => '50'));
    }

    // TAB 6: Testimonials
    $home_fields[] = array('key' => 'tab_home_testi', 'label' => '6. Testimonials', 'type' => 'tab');
    $home_fields[] = array('key' => 'field_home_testi_title', 'label' => 'Section Title', 'name' => 'testi_title', 'type' => 'text', 'placeholder' => 'What Our Members Say');
    $home_fields[] = array('key' => 'field_home_testi_sub', 'label' => 'Section Subtitle', 'name' => 'testi_subtitle', 'type' => 'textarea', 'rows' => 2);

    // TAB 7: Our Network
    $home_fields[] = array('key' => 'tab_home_net', 'label' => '7. Our Network', 'type' => 'tab');
    $home_fields[] = array('key' => 'field_home_net_title', 'label' => 'Section Title', 'name' => 'net_title', 'type' => 'text', 'placeholder' => 'Our Network');
    $home_fields[] = array('key' => 'field_home_net_desc', 'label' => 'Section Subtitle', 'name' => 'net_subtitle', 'type' => 'textarea', 'rows' => 2, 'placeholder' => 'Explore our affiliated brands and communities.');

    for ($i = 1; $i <= 3; $i++) {
        $brand_names = array(1 => 'The Kings City', 2 => 'The Social Manila', 3 => 'The Home Culinary School');
        $brand_defaults = array(
            1 => array('desc' => 'Our premier coworking and flex-office brand. We provide modern, inspiring workspaces designed to foster collaboration, innovation, and productivity for professionals in the heart of the business district.', 'link' => 'https://www.kings-city.com/', 'btn' => 'Discover Kings City'),
            2 => array('desc' => 'The premier events, lifestyle, and community engagement hub. We host corporate functions, team-building events, and exclusive gatherings designed to connect leaders and ignite culture.', 'link' => 'https://kingscity.com.ph/', 'btn' => 'Explore The Social'),
            3 => array('desc' => 'Professional culinary training and certification programs. Equipping the next generation of chefs and hospitality professionals with world-class skills, discipline, and ethical standards.', 'link' => 'https://unique-souffle-78e15a.netlify.app/', 'btn' => 'Start Cooking'),
        );

        $home_fields[] = array('key' => 'field_home_net_b' . $i . '_title', 'label' => 'Brand ' . $i . ' Title', 'name' => 'net_brand' . $i . '_title', 'type' => 'text', 'placeholder' => $brand_names[$i], 'wrapper' => array('width' => '33'));
        $home_fields[] = array('key' => 'field_home_net_b' . $i . '_link', 'label' => 'Brand ' . $i . ' Link URL', 'name' => 'net_brand' . $i . '_link', 'type' => 'url', 'placeholder' => $brand_defaults[$i]['link'], 'wrapper' => array('width' => '33'));
        $home_fields[] = array('key' => 'field_home_net_b' . $i . '_btn', 'label' => 'Brand ' . $i . ' Button Text', 'name' => 'net_brand' . $i . '_btn', 'type' => 'text', 'placeholder' => $brand_defaults[$i]['btn'], 'wrapper' => array('width' => '33'));
        $home_fields[] = array('key' => 'field_home_net_b' . $i . '_img', 'label' => 'Brand ' . $i . ' Image', 'name' => 'net_brand' . $i . '_img', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium', 'wrapper' => array('width' => '50'));
        $home_fields[] = array('key' => 'field_home_net_b' . $i . '_desc', 'label' => 'Brand ' . $i . ' Description', 'name' => 'net_brand' . $i . '_desc', 'type' => 'textarea', 'rows' => 3, 'placeholder' => $brand_defaults[$i]['desc'], 'wrapper' => array('width' => '50'));
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
        array('key' => 'field_story_headline', 'label' => 'Main Headline', 'name' => 'story_headline', 'type' => 'text', 'instructions' => 'Hero section headline.', 'placeholder' => 'Our Story'),
        array('key' => 'field_story_desc', 'label' => 'Hero Description', 'name' => 'story_desc', 'type' => 'textarea', 'instructions' => 'Short description below the hero headline.', 'rows' => 2),
        array('key' => 'field_story_bg', 'label' => 'Background Image', 'name' => 'story_bg', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium', 'instructions' => 'Hero background image (recommended 2000×1000px).'),

        array('key' => 'tab_story_vision', 'label' => '2. Vision & Mission', 'type' => 'tab'),
        array('key' => 'field_story_vision_title', 'label' => 'Section Title', 'name' => 'story_vision_title', 'type' => 'text', 'placeholder' => 'Vision & Mission'),
        array('key' => 'field_story_vision_text', 'label' => 'Vision Text', 'name' => 'story_vision_text', 'type' => 'textarea', 'instructions' => 'The company\'s vision statement.', 'rows' => 3),
        array('key' => 'field_story_mission_text', 'label' => 'Mission Text 1', 'name' => 'story_mission_text', 'type' => 'textarea', 'instructions' => 'First paragraph of the mission statement.', 'rows' => 3),
        array('key' => 'field_story_mission_text_2', 'label' => 'Mission Text 2', 'name' => 'story_mission_text_2', 'type' => 'textarea', 'instructions' => 'Second paragraph of the mission statement.', 'rows' => 3),

        array('key' => 'tab_story_values', 'label' => '3. Core Values', 'type' => 'tab'),
        array('key' => 'field_story_values_title', 'label' => 'Section Title', 'name' => 'story_values_title', 'type' => 'text', 'placeholder' => 'Core Values'),
        array('key' => 'field_story_values_intro', 'label' => 'Values Intro', 'name' => 'story_values_intro', 'type' => 'textarea', 'instructions' => 'Subtitle text below the section title.', 'rows' => 2),
    );

    // Manual Repeaters for Core Values (Max 9)
    for ($i = 1; $i <= 9; $i++) {
        $story_fields[] = array('key' => 'field_story_v' . $i . '_title', 'label' => 'Value ' . $i . ' Title', 'name' => 'story_v' . $i . '_title', 'type' => 'text', 'wrapper' => array('width' => '30'));
        $story_fields[] = array('key' => 'field_story_v' . $i . '_desc', 'label' => 'Value ' . $i . ' Desc', 'name' => 'story_v' . $i . '_desc', 'type' => 'textarea', 'wrapper' => array('width' => '70'));
    }

    // TAB 4: Our Roots (Video)
    $story_fields[] = array('key' => 'tab_story_timeline', 'label' => '4. Our Roots (Video)', 'type' => 'tab');
    $story_fields[] = array('key' => 'field_story_timeline_title', 'label' => 'Section Title', 'name' => 'story_timeline_title', 'type' => 'text', 'placeholder' => 'Our Roots', 'instructions' => 'Heading for the Our Roots section.');
    $story_fields[] = array('key' => 'field_story_timeline_intro', 'label' => 'Section Subtitle', 'name' => 'story_timeline_intro', 'type' => 'textarea', 'instructions' => 'Short subtitle below the heading.', 'rows' => 2);
    $story_fields[] = array('key' => 'field_story_roots_video', 'label' => 'Company Video URL', 'name' => 'story_roots_video', 'type' => 'url', 'instructions' => 'Paste a YouTube or Vimeo URL (e.g. https://www.youtube.com/watch?v=XXXXX). The video will be embedded automatically.');

    // TAB 5: Leadership Team
    $story_fields[] = array('key' => 'tab_story_leadership', 'label' => '5. Leadership Team', 'type' => 'tab');
    $story_fields[] = array('key' => 'field_story_team_title', 'label' => 'Section Title', 'name' => 'story_team_title', 'type' => 'text', 'placeholder' => 'Kings Team', 'instructions' => 'Heading for the leadership section.');
    $story_fields[] = array('key' => 'field_story_team_intro', 'label' => 'Section Subtitle', 'name' => 'story_team_intro', 'type' => 'textarea', 'instructions' => 'Subtitle text below the team heading.', 'rows' => 2);

    // Manual Repeaters for Leadership Team Members (Max 3)
    for ($i = 1; $i <= 3; $i++) {
        $story_fields[] = array('key' => 'field_story_leader' . $i . '_name', 'label' => 'Leader ' . $i . ' Name', 'name' => 'story_leader' . $i . '_name', 'type' => 'text', 'instructions' => 'Full name of the team member.', 'wrapper' => array('width' => '33'));
        $story_fields[] = array('key' => 'field_story_leader' . $i . '_role', 'label' => 'Leader ' . $i . ' Title/Role', 'name' => 'story_leader' . $i . '_role', 'type' => 'text', 'instructions' => 'Official title or role.', 'wrapper' => array('width' => '33'));
        $story_fields[] = array('key' => 'field_story_leader' . $i . '_img', 'label' => 'Leader ' . $i . ' Photo', 'name' => 'story_leader' . $i . '_img', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium', 'wrapper' => array('width' => '33'));
        $story_fields[] = array('key' => 'field_story_leader' . $i . '_creds', 'label' => 'Leader ' . $i . ' Credentials', 'name' => 'story_leader' . $i . '_creds', 'type' => 'textarea', 'instructions' => 'One credential per line. Each line becomes a list item.', 'rows' => 5);
    }

    // TAB 6: Group of Companies
    $story_fields[] = array('key' => 'tab_story_companies', 'label' => '6. Group of Companies', 'type' => 'tab');
    $story_fields[] = array('key' => 'field_story_companies_title', 'label' => 'Section Title', 'name' => 'story_companies_title', 'type' => 'text', 'placeholder' => 'Group of Companies', 'instructions' => 'Heading for the companies section.');
    $story_fields[] = array('key' => 'field_story_companies_intro', 'label' => 'Section Subtitle', 'name' => 'story_companies_intro', 'type' => 'textarea', 'instructions' => 'Subtitle text below the companies heading.', 'rows' => 2);

    // Manual Repeaters for Group of Companies (Max 11)
    for ($i = 1; $i <= 11; $i++) {
        $story_fields[] = array('key' => 'field_story_co' . $i . '_img', 'label' => 'Company ' . $i . ' Logo', 'name' => 'story_co' . $i . '_img', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium', 'instructions' => 'Company logo image (transparent PNG or WebP recommended).', 'wrapper' => array('width' => '50'));
        $story_fields[] = array('key' => 'field_story_co' . $i . '_name', 'label' => 'Company ' . $i . ' Name', 'name' => 'story_co' . $i . '_name', 'type' => 'text', 'instructions' => 'Name used as alt text for accessibility.', 'wrapper' => array('width' => '50'));
    }

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
            array('key' => 'field_careers_headline', 'label' => 'Main Headline', 'name' => 'careers_headline', 'type' => 'text', 'instructions' => 'Hero headline. Supports HTML tags like &lt;br&gt; and &lt;span&gt;.'),
            array('key' => 'field_careers_desc', 'label' => 'Hero Description', 'name' => 'careers_desc', 'type' => 'textarea', 'rows' => 2),
            array('key' => 'field_careers_bg', 'label' => 'Background Image', 'name' => 'careers_bg', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium'),

            array('key' => 'tab_careers_application', 'label' => '2. Application Form Info', 'type' => 'tab'),
            array('key' => 'field_careers_form_title', 'label' => 'Form Section Title', 'name' => 'careers_form_title', 'type' => 'text', 'placeholder' => 'Fast-Track Application'),
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
        array('key' => 'field_benefits_headline', 'label' => 'Main Headline', 'name' => 'benefits_headline', 'type' => 'text', 'placeholder' => 'Why Join Kings?'),
        array('key' => 'field_benefits_desc', 'label' => 'Hero Description', 'name' => 'benefits_desc', 'type' => 'textarea', 'rows' => 2),
        array('key' => 'field_benefits_bg', 'label' => 'Background Image', 'name' => 'benefits_bg', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium'),

        array('key' => 'tab_benefits_list', 'label' => '2. The Lucky 9 Benefits', 'type' => 'tab'),
        array('key' => 'field_benefits_list_title', 'label' => 'Section Title', 'name' => 'benefits_list_title', 'type' => 'text', 'placeholder' => 'The "Lucky 9" Benefits'),
        array('key' => 'field_benefits_list_desc', 'label' => 'Section Intro', 'name' => 'benefits_list_desc', 'type' => 'textarea', 'instructions' => 'Subtitle text shown below the section title.', 'rows' => 2),
    );

    // Manual Repeaters for Benefits (Max 9)
    for ($i = 1; $i <= 9; $i++) {
        $benefits_fields[] = array('key' => 'field_benefits_b' . $i . '_title', 'label' => 'Benefit ' . $i . ' Title', 'name' => 'benefits_b' . $i . '_title', 'type' => 'text', 'wrapper' => array('width' => '30'));
        $benefits_fields[] = array('key' => 'field_benefits_b' . $i . '_desc', 'label' => 'Benefit ' . $i . ' Desc', 'name' => 'benefits_b' . $i . '_desc', 'type' => 'textarea', 'wrapper' => array('width' => '70'));
    }

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
        array('key' => 'field_slab_headline', 'label' => 'Main Headline', 'name' => 'slab_headline', 'type' => 'text', 'instructions' => 'Hero headline. Supports &lt;br&gt; for line breaks.'),
        array('key' => 'field_slab_desc', 'label' => 'Hero Description', 'name' => 'slab_desc', 'type' => 'textarea', 'rows' => 2),
        array('key' => 'field_slab_bg', 'label' => 'Background Image', 'name' => 'slab_bg', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium'),

        // TAB 2: Service Intro
        array('key' => 'tab_slab_intro', 'label' => '2. Service Intro', 'type' => 'tab'),
        array('key' => 'field_slab_intro_title', 'label' => 'Intro Title', 'name' => 'slab_intro_title', 'type' => 'text', 'instructions' => 'Title for the service introduction section.'),
        array('key' => 'field_slab_intro_desc', 'label' => 'Intro Text', 'name' => 'slab_intro_desc', 'type' => 'textarea', 'instructions' => 'Detailed description of managed services.', 'rows' => 4),
        array('key' => 'field_slab_intro_img', 'label' => 'Intro Image', 'name' => 'slab_intro_img', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium', 'instructions' => 'Image beside the intro text.'),
        array('key' => 'field_slab_intro_pills', 'label' => 'Capability Pills', 'name' => 'slab_intro_pills', 'type' => 'textarea', 'instructions' => 'One pill label per line (e.g. Recruitment & Deployment). Each line becomes a pill badge.', 'rows' => 5),

        // TAB 3: Managed Services (Section A)
        array('key' => 'tab_slab_managed', 'label' => '3. Managed Services', 'type' => 'tab'),
        array('key' => 'field_slab_managed_title', 'label' => 'Section A Title', 'name' => 'slab_managed_title', 'type' => 'text', 'placeholder' => 'A. Managed Services'),
        array('key' => 'field_slab_managed_desc', 'label' => 'Section A Description', 'name' => 'slab_managed_desc', 'type' => 'textarea', 'rows' => 3, 'instructions' => 'Description below the Section A heading.'),
    );

    // 4 Feature Cards for Managed Services
    for ($i = 1; $i <= 4; $i++) {
        $slab_fields[] = array('key' => 'field_slab_feat' . $i . '_title', 'label' => 'Feature ' . $i . ' Title', 'name' => 'slab_feat' . $i . '_title', 'type' => 'text', 'wrapper' => array('width' => '30'));
        $slab_fields[] = array('key' => 'field_slab_feat' . $i . '_desc', 'label' => 'Feature ' . $i . ' Description', 'name' => 'slab_feat' . $i . '_desc', 'type' => 'textarea', 'rows' => 2, 'wrapper' => array('width' => '40'));
        $slab_fields[] = array('key' => 'field_slab_feat' . $i . '_img', 'label' => 'Feature ' . $i . ' Image', 'name' => 'slab_feat' . $i . '_img', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'thumbnail', 'wrapper' => array('width' => '30'));
    }

    // TAB 4: Total Manpower Solutions
    $slab_fields[] = array('key' => 'tab_slab_manpower', 'label' => '4. Total Manpower', 'type' => 'tab');
    $slab_fields[] = array('key' => 'field_slab_manpower_title', 'label' => 'Title', 'name' => 'slab_manpower_title', 'type' => 'text', 'placeholder' => 'Total Manpower Solutions');
    $slab_fields[] = array('key' => 'field_slab_manpower_text', 'label' => 'Body Text', 'name' => 'slab_manpower_text', 'type' => 'wysiwyg', 'instructions' => 'Rich text content for the manpower solutions section. Multiple paragraphs supported.', 'media_upload' => 0, 'tabs' => 'visual');
    $slab_fields[] = array('key' => 'field_slab_manpower_img', 'label' => 'Section Image', 'name' => 'slab_manpower_img', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium');

    // TAB 5: Staff Leasing (Section B)
    $slab_fields[] = array('key' => 'tab_slab_leasing', 'label' => '5. Staff Leasing', 'type' => 'tab');
    $slab_fields[] = array('key' => 'field_slab_lease_title', 'label' => 'Leasing Title', 'name' => 'slab_lease_title', 'type' => 'text', 'placeholder' => 'B. Managed Staff Leasing Services');
    $slab_fields[] = array('key' => 'field_slab_lease_desc', 'label' => 'Leasing Subtitle', 'name' => 'slab_lease_desc', 'type' => 'textarea', 'rows' => 2);
    $slab_fields[] = array('key' => 'field_slab_offshore_title', 'label' => 'How it Works — Title', 'name' => 'slab_offshore_title', 'type' => 'text', 'placeholder' => '1. How Does Offshore Staff Leasing Work?');
    $slab_fields[] = array('key' => 'field_slab_offshore_text', 'label' => 'How it Works — Body', 'name' => 'slab_offshore_text', 'type' => 'wysiwyg', 'instructions' => 'Rich text for the offshore leasing explanation.', 'media_upload' => 0, 'tabs' => 'visual');
    $slab_fields[] = array('key' => 'field_slab_offshore_img', 'label' => 'How it Works — Image', 'name' => 'slab_offshore_img', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium');

    // TAB 6: Improving Manpower
    $slab_fields[] = array('key' => 'tab_slab_improve', 'label' => '6. Improving Manpower', 'type' => 'tab');
    $slab_fields[] = array('key' => 'field_slab_improve_title', 'label' => 'Section Title', 'name' => 'slab_improve_title', 'type' => 'text', 'placeholder' => '2. Improving Your Manpower');
    $slab_fields[] = array('key' => 'field_slab_improve_desc', 'label' => 'Section Description', 'name' => 'slab_improve_desc', 'type' => 'textarea', 'rows' => 3);
    $slab_fields[] = array('key' => 'field_slab_improve_img', 'label' => 'Section Image', 'name' => 'slab_improve_img', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium');

    // 3 Checklist items for Improving Manpower
    for ($i = 1; $i <= 3; $i++) {
        $slab_fields[] = array('key' => 'field_slab_check' . $i . '_title', 'label' => 'Checklist ' . $i . ' Title', 'name' => 'slab_check' . $i . '_title', 'type' => 'text', 'wrapper' => array('width' => '30'));
        $slab_fields[] = array('key' => 'field_slab_check' . $i . '_desc', 'label' => 'Checklist ' . $i . ' Description', 'name' => 'slab_check' . $i . '_desc', 'type' => 'textarea', 'rows' => 2, 'wrapper' => array('width' => '70'));
    }

    // TAB 7: Onboarding Journey header
    $slab_fields[] = array('key' => 'tab_slab_onboarding', 'label' => '7. Onboarding Journey', 'type' => 'tab');
    $slab_fields[] = array('key' => 'field_slab_onboard_title', 'label' => 'Section Title', 'name' => 'slab_onboard_title', 'type' => 'text', 'placeholder' => '3. The Onboarding Journey');
    $slab_fields[] = array('key' => 'field_slab_onboard_desc', 'label' => 'Section Description', 'name' => 'slab_onboard_desc', 'type' => 'textarea', 'rows' => 2);

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
        array('key' => 'field_skit_headline', 'label' => 'Main Headline', 'name' => 'skit_headline', 'type' => 'text', 'placeholder' => 'HR & Payroll System'),
        array('key' => 'field_skit_desc', 'label' => 'Hero Description', 'name' => 'skit_desc', 'type' => 'textarea', 'rows' => 2),
        array('key' => 'field_skit_bg', 'label' => 'Background Image', 'name' => 'skit_bg', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium'),

        // TAB 2: Service Intro
        array('key' => 'tab_skit_intro', 'label' => '2. Service Intro', 'type' => 'tab'),
        array('key' => 'field_skit_intro_title', 'label' => 'Intro Title', 'name' => 'skit_intro_title', 'type' => 'text', 'instructions' => 'Title for the KIT service introduction.'),
        array('key' => 'field_skit_intro_desc', 'label' => 'Intro Text', 'name' => 'skit_intro_desc', 'type' => 'textarea', 'instructions' => 'Detailed description of the KIT platform.', 'rows' => 4),
        array('key' => 'field_skit_intro_pills', 'label' => 'Capability Pills', 'name' => 'skit_intro_pills', 'type' => 'textarea', 'instructions' => 'One pill label per line. Each line becomes a pill badge.', 'rows' => 3),
        array('key' => 'field_skit_intro_img1', 'label' => 'Intro Image (Back)', 'name' => 'skit_intro_img1', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium', 'instructions' => 'Background image in the stacked intro image pair.'),
        array('key' => 'field_skit_intro_img2', 'label' => 'Intro Image (Front)', 'name' => 'skit_intro_img2', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium', 'instructions' => 'Foreground image in the stacked intro image pair.'),

        // TAB 3: How We Work
        array('key' => 'tab_skit_howwework', 'label' => '3. How We Work', 'type' => 'tab'),
        array('key' => 'field_skit_hww_title', 'label' => 'Section Title', 'name' => 'skit_hww_title', 'type' => 'text', 'placeholder' => 'How We Work'),
        array('key' => 'field_skit_hww_text', 'label' => 'Section Body', 'name' => 'skit_hww_text', 'type' => 'textarea', 'instructions' => 'Description of the work methodology.', 'rows' => 4),
        array('key' => 'field_skit_hww_img', 'label' => 'Section Image', 'name' => 'skit_hww_img', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium'),

        // TAB 4: Platform Features
        array('key' => 'tab_skit_features', 'label' => '4. Platform Features', 'type' => 'tab'),
    );

    // 6 Feature Cards for KIT platform
    for ($i = 1; $i <= 6; $i++) {
        $skit_fields[] = array('key' => 'field_skit_feat' . $i . '_title', 'label' => 'Feature ' . $i . ' Title', 'name' => 'skit_feat' . $i . '_title', 'type' => 'text', 'wrapper' => array('width' => '50'));
        $skit_fields[] = array('key' => 'field_skit_feat' . $i . '_desc', 'label' => 'Feature ' . $i . ' Description', 'name' => 'skit_feat' . $i . '_desc', 'type' => 'textarea', 'rows' => 2, 'wrapper' => array('width' => '50'));
    }

    // TAB 5: Moving Forward (CTA)
    $skit_fields[] = array('key' => 'tab_skit_forward', 'label' => '5. Moving Forward', 'type' => 'tab');
    $skit_fields[] = array('key' => 'field_skit_forward_title', 'label' => 'Section Title', 'name' => 'skit_forward_title', 'type' => 'text', 'placeholder' => 'Moving Forward');
    $skit_fields[] = array('key' => 'field_skit_forward_text', 'label' => 'Section Text', 'name' => 'skit_forward_text', 'type' => 'textarea', 'rows' => 3, 'instructions' => 'Closing paragraph / call-to-action text.');

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
        array('key' => 'field_net_headline', 'label' => 'Main Headline', 'name' => 'net_headline', 'type' => 'text', 'placeholder' => 'Our Global Network'),
        array('key' => 'field_net_desc', 'label' => 'Hero Description', 'name' => 'net_desc', 'type' => 'textarea', 'rows' => 2),
        array('key' => 'field_net_bg', 'label' => 'Background Image', 'name' => 'net_bg', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium'),

        array('key' => 'tab_net_stats', 'label' => '2. Stats Strip', 'type' => 'tab'),
    );

    for ($i = 1; $i <= 4; $i++) {
        $network_fields[] = array('key' => 'field_net_s' . $i . '_num', 'label' => 'Stat ' . $i . ' Number', 'name' => 'net_s' . $i . '_num', 'type' => 'text', 'wrapper' => array('width' => '50'));
        $network_fields[] = array('key' => 'field_net_s' . $i . '_label', 'label' => 'Stat ' . $i . ' Label', 'name' => 'net_s' . $i . '_label', 'type' => 'text', 'wrapper' => array('width' => '50'));
    }

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
            array('key' => 'field_quote_headline', 'label' => 'Main Headline', 'name' => 'quote_headline', 'type' => 'text', 'instructions' => 'Hero headline for the Team Builder page.', 'placeholder' => 'Build Your Offshore Team'),
            array('key' => 'field_quote_desc', 'label' => 'Hero Description', 'name' => 'quote_desc', 'type' => 'textarea', 'instructions' => 'Short description below the hero headline.', 'rows' => 2),
            array('key' => 'field_quote_bg', 'label' => 'Background Image', 'name' => 'quote_bg', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium'),

            array('key' => 'tab_quote_builder', 'label' => '2. Builder Text', 'type' => 'tab'),
            array('key' => 'field_quote_b_title', 'label' => 'Builder Title', 'name' => 'quote_b_title', 'type' => 'text', 'instructions' => 'Title above the team builder calculator.', 'placeholder' => 'Estimate Your Monthly Investment'),
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
            array('key' => 'field_contact_headline', 'label' => 'Main Headline', 'name' => 'contact_headline', 'type' => 'text', 'placeholder' => 'Contact Us'),
            array('key' => 'field_contact_desc', 'label' => 'Hero Description', 'name' => 'contact_desc', 'type' => 'textarea', 'rows' => 2),
            array('key' => 'field_contact_bg', 'label' => 'Background Image', 'name' => 'contact_bg', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium'),

            array('key' => 'tab_contact_form', 'label' => '2. Form Section', 'type' => 'tab'),
            array('key' => 'field_contact_form_title', 'label' => 'Form Title', 'name' => 'contact_form_title', 'type' => 'text', 'placeholder' => 'Get In Touch'),
            array('key' => 'field_contact_form_desc', 'label' => 'Form Description', 'name' => 'contact_form_desc', 'type' => 'textarea', 'rows' => 2),
            array('key' => 'field_contact_form_shortcode', 'label' => 'Contact Form Shortcode', 'name' => 'contact_form_shortcode', 'type' => 'text', 'placeholder' => '[contact-form-7 id="123" title="Contact form 1"]'),

            array('key' => 'tab_contact_info', 'label' => '3. Corporate Info', 'type' => 'tab'),
            array('key' => 'field_contact_info_title', 'label' => 'Info Section Title', 'name' => 'contact_info_title', 'type' => 'text', 'placeholder' => 'Contact Us'),
            array('key' => 'field_contact_telephone', 'label' => 'Telephone', 'name' => 'contact_telephone', 'type' => 'text', 'placeholder' => '+63 (2) 87766712'),
            array('key' => 'field_contact_mobile', 'label' => 'Mobile', 'name' => 'contact_mobile', 'type' => 'text', 'placeholder' => '+63 (917) 634 2088'),
            array('key' => 'field_contact_email', 'label' => 'Email', 'name' => 'contact_email', 'type' => 'email', 'placeholder' => 'info@kingsgroup.com.ph'),
            array('key' => 'field_contact_visit_title', 'label' => 'Visit Section Title', 'name' => 'contact_visit_title', 'type' => 'text', 'placeholder' => 'Visit Us'),
            array('key' => 'field_contact_address', 'label' => 'Address', 'name' => 'contact_address', 'type' => 'textarea', 'instructions' => 'Corporate HQ address. Use &lt;br&gt; for line breaks.', 'rows' => 2),
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
                'key' => 'field_job_location',
                'label' => 'Location',
                'name' => 'job_location',
                'type' => 'text',
                'instructions' => 'e.g. Parañaque, Metro Manila or Remote',
                'placeholder' => 'Parañaque, Metro Manila',
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
                'key' => 'field_job_duration',
                'label' => 'Contract Duration',
                'name' => 'job_duration',
                'type' => 'text',
                'instructions' => 'Specify the duration (e.g., "6 Months", "1 Year").',
                'placeholder' => 'e.g. 6 Months',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_job_type',
                            'operator' => '==',
                            'value' => 'CONTRACTOR',
                        ),
                    ),
                    array(
                        array(
                            'field' => 'field_job_type',
                            'operator' => '==',
                            'value' => 'TEMPORARY',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_job_salary_min',
                'label' => 'Salary Min (PHP/month)',
                'name' => 'job_salary_min',
                'type' => 'number',
                'instructions' => 'Minimum monthly salary in PHP. Leave blank to hide salary.',
                'min' => 0,
            ),
            array(
                'key' => 'field_job_salary_max',
                'label' => 'Salary Max (PHP/month)',
                'name' => 'job_salary_max',
                'type' => 'number',
                'instructions' => 'Maximum monthly salary in PHP.',
                'min' => 0,
            ),
            array(
                'key' => 'field_job_department',
                'label' => 'Department',
                'name' => 'job_department',
                'type' => 'text',
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
                'label' => 'Base Monthly Price (USD)',
                'name' => 'base_price',
                'type' => 'number',
                'instructions' => 'The base monthly cost for this role at Junior level. Mid-Level = ×1.4, Senior = ×1.8.',
                'placeholder' => '2000',
                'prepend' => '$',
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
            array('key' => 'field_jobs_hero_headline', 'label' => 'Main Headline', 'name' => 'jobs_hero_headline', 'type' => 'text', 'placeholder' => 'Our Jobs'),
            array('key' => 'field_jobs_hero_desc', 'label' => 'Hero Description', 'name' => 'jobs_hero_desc', 'type' => 'textarea', 'rows' => 2),
            array('key' => 'field_jobs_hero_bg', 'label' => 'Background Image', 'name' => 'jobs_hero_bg', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium'),
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
                array('key' => 'field_comm_hero_title', 'label' => 'Hero Title', 'name' => 'comm_hero_title', 'type' => 'text', 'default_value' => 'Our Commitment to Community'),
                array('key' => 'field_comm_hero_desc', 'label' => 'Hero Description', 'name' => 'comm_hero_desc', 'type' => 'textarea', 'default_value' => 'Building a sustainable future through education, empowerment, and shared success.', 'rows' => 2),
                array('key' => 'field_comm_impact_intro', 'label' => 'Impact Intro', 'name' => 'comm_impact_intro', 'type' => 'textarea', 'default_value' => 'Community is essential to our mission and it is our responsibility to support the aspirations of our members by providing scholarships to our members and their dependents.'),
                array('key' => 'field_comm_stat1_num', 'label' => 'Stat 1 Number', 'name' => 'comm_stat1_num', 'type' => 'text', 'default_value' => '500+', 'wrapper' => array('width' => '50')),
                array('key' => 'field_comm_stat1_label', 'label' => 'Stat 1 Label', 'name' => 'comm_stat1_label', 'type' => 'text', 'default_value' => 'Scholarships Awarded', 'wrapper' => array('width' => '50')),
                array('key' => 'field_comm_stat2_num', 'label' => 'Stat 2 Number', 'name' => 'comm_stat2_num', 'type' => 'text', 'default_value' => '100%', 'wrapper' => array('width' => '50')),
                array('key' => 'field_comm_stat2_label', 'label' => 'Stat 2 Label', 'name' => 'comm_stat2_label', 'type' => 'text', 'default_value' => 'Member Focused', 'wrapper' => array('width' => '50')),
                array('key' => 'field_comm_impact_img', 'label' => 'Impact Image', 'name' => 'comm_impact_img', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium'),
                array('key' => 'field_comm_queens_title', 'label' => 'Queens Section Title', 'name' => 'comm_queens_title', 'type' => 'text', 'default_value' => 'Queens of Kings Group'),
                array('key' => 'field_comm_queens_desc', 'label' => 'Queens Section Description', 'name' => 'comm_queens_desc', 'type' => 'textarea', 'default_value' => 'Dedicated to empowering women within the Kings Group network through specialized resources, mentorship, and support structures designed for professional and personal growth.'),
                array('key' => 'field_comm_queens_img', 'label' => 'Queens Visual Image', 'name' => 'comm_queens_img', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium'),
                array('key' => 'field_comm_culinary_tag', 'label' => 'Culinary Section Tag', 'name' => 'comm_culinary_tag', 'type' => 'text', 'default_value' => 'Education', 'wrapper' => array('width' => '33')),
                array('key' => 'field_comm_culinary_title', 'label' => 'Culinary Card Title', 'name' => 'comm_culinary_title', 'type' => 'text', 'default_value' => 'Home Culinary & Technical School', 'wrapper' => array('width' => '33')),
                array('key' => 'field_comm_culinary_sub', 'label' => 'Culinary Card Subtitle', 'name' => 'comm_culinary_sub', 'type' => 'text', 'default_value' => 'Empowering our members with sustainable livelihood programs and TESDA-accredited training.', 'wrapper' => array('width' => '34')),
                array('key' => 'field_comm_culinary_intro', 'label' => 'Culinary School Intro', 'name' => 'comm_culinary_intro', 'type' => 'textarea', 'default_value' => 'We built Home Culinary and Technical School to have a sustainable education and livelihood programs for our members and their families.'),
                array('key' => 'field_comm_culinary_desc', 'label' => 'Culinary School Description', 'name' => 'comm_culinary_desc', 'type' => 'textarea', 'default_value' => 'As The Kings expands, so does our scholarship program with Home Culinary and Technical School. We are TESDA accredited and certified.'),
                array('key' => 'field_comm_culinary_img', 'label' => 'Culinary School Image', 'name' => 'comm_culinary_img', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium', 'instructions' => 'Upload a 16:9 image (e.g. 800x450px) for the culinary school card.'),
            ),
            'location' => array(array(array('param' => 'page_template', 'operator' => '==', 'value' => 'community.php'))),
        ));
    }

endif;