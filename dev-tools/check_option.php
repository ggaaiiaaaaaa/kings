<?php
$wp_load = 'C:/xampp/htdocs/kings_cms/wp-load.php';
if (file_exists($wp_load)) {
    require_once($wp_load);
    $opt = get_option('kg_full_site_populated');
    echo "Option kg_full_site_populated is: " . var_export($opt, true);
} else {
    echo "WP not found.";
}
