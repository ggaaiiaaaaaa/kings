<?php
$wp_load = 'C:/xampp/htdocs/kings_cms/wp-load.php';
require_once($wp_load);
wp_cache_flush();


echo "DEBUG INFO:\n";

// Check front page setting
$front_page_id = get_option('page_on_front');
echo "Front Page ID Option: " . $front_page_id . "\n";

// Check if page exists
if ($front_page_id) {
    $page = get_post($front_page_id);
    if ($page) {
        echo "Front Page Title: " . $page->post_title . "\n";
        echo "Front Page Template: " . get_page_template_slug($front_page_id) . "\n";
        
        // Check meta
        $headline = get_post_meta($front_page_id, 'hero_headline', true);
        echo "Hero Headline Meta: " . ($headline ? $headline : 'MISSING') . "\n";
    } else {
        echo "Front Page post not found in DB!\n";
    }
} else {
    echo "Front Page ID is 0 or empty.\n";
}

// Check jobs
$jobs = get_posts(array('post_type' => 'jobs', 'numberposts' => -1));
echo "Total Jobs: " . count($jobs) . "\n";
foreach($jobs as $job) {
    echo " - Job: " . $job->post_title . " (Price: " . get_post_meta($job->ID, 'base_price', true) . ")\n";
}
