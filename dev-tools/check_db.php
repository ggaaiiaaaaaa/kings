<?php
$wp_load = 'C:/xampp/htdocs/kings_cms/wp-load.php';
if (file_exists($wp_load)) {
    require_once($wp_load);
    $front_id = get_option('page_on_front');
    $headline = get_post_meta($front_id, 'hero_headline', true);
    echo "Current DB Headline: " . $headline;
}
