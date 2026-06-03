<?php
$wp_load = 'C:/xampp/htdocs/kings_cms/wp-load.php';
require_once($wp_load);

echo "=== CHECKING 'HOME' PAGES ===\n";
$home_pages = get_posts(array(
    'post_type' => 'page',
    'title' => 'Home',
    'post_status' => 'any',
    'numberposts' => -1
));

foreach($home_pages as $home) {
    echo "ID: " . $home->ID . " | Status: " . $home->post_status . " | Modified: " . $home->post_modified . "\n";
}

$front_id = get_option('page_on_front');
echo "\nThe Official Front Page ID is: " . $front_id . "\n";
