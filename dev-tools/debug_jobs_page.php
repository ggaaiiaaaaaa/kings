<?php
/**
 * Quick debug script to check the Jobs List page properties.
 */
$wp_load = 'C:/xampp/htdocs/kings_cms/wp-load.php';
if (!file_exists($wp_load)) {
    die("WordPress load file not found at: $wp_load");
}
require_once($wp_load);

header('Content-Type: text/plain');

echo "=== CHECKING 'JOBS' RELATED PAGES ===\n";
$pages = get_posts(array(
    'post_type' => 'page',
    'post_status' => 'any',
    'numberposts' => -1,
    's' => 'Jobs'
));

if (empty($pages)) {
    echo "No pages found with 'Jobs' in the title.\n";
} else {
    foreach($pages as $p) {
        echo "ID: " . $p->ID . "\n";
        echo "Title: " . $p->post_title . "\n";
        echo "Slug: " . $p->post_name . "\n";
        echo "Permalink: " . get_permalink($p->ID) . "\n";
        echo "Template: " . get_post_meta($p->ID, '_wp_page_template', true) . "\n";
        echo "-------------------\n";
    }
}

echo "\n=== HOME URL CHECK ===\n";
echo "home_url('/jobs-list/'): " . home_url('/jobs-list/') . "\n";
echo "site_url(): " . site_url() . "\n";
