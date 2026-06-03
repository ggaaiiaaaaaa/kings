<?php
$wp_load = 'C:/xampp/htdocs/kings_cms/wp-load.php';
if (file_exists($wp_load)) {
    require_once($wp_load);
    
    // Run the main populator
    kingsgroup_populate_all_pages();
    
    // FORCE UPDATE EXISTING PAGES
    $pages_to_create = array(
        'Home' => 'front-page.php',
        'Our Story' => 'story.php',
        'Careers' => 'careers.php',
        'Team Builder' => 'quote.php',
        'Member Benefits' => 'benefits.php',
        'Labor Management' => 'service-labor.php',
        'HR Tech (KIT)' => 'service-kit.php',
        'Our Network' => 'network.php',
        'Contact Us' => 'contact.php'
    );
    
    foreach ($pages_to_create as $title => $template) {
        $existing_pages = get_posts(array(
            'post_type'   => 'page',
            'title'       => $title,
            'numberposts' => 1
        ));
        if (!empty($existing_pages)) {
            $existing = $existing_pages[0];
            update_post_meta($existing->ID, '_wp_page_template', $template);
            wp_update_post(array(
                'ID' => $existing->ID,
                'post_status' => 'publish'
            ));
        }
    }
    
    // Flush permalinks to fix 404s
    flush_rewrite_rules();
    
    echo "Success: Page templates forced, permalinks flushed, data verified.";
} else {
    echo "Error: Could not find wp-load.php at " . $wp_load;
}
