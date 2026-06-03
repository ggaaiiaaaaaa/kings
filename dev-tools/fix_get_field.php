<?php
$wp_load = 'C:/xampp/htdocs/kings_cms/wp-load.php';
if (file_exists($wp_load)) {
    require_once($wp_load);
    
    $templates = glob('*.php');
    $count = 0;
    
    foreach ($templates as $file) {
        if ($file === 'functions.php' || $file === 'clean_pages.php' || $file === 'check_home.php' || $file === 'debug_db.php' || $file === 'fix_slugs.php' || $file === 'force_populator.php') {
            continue;
        }
        
        $content = file_get_contents($file);
        
        // Find get_field('field_name', get_queried_object_id()) and replace with get_field('field_name', get_queried_object_id())
        // but ONLY if it doesn't already have a second parameter.
        // Simple regex: get_field('some_name', get_queried_object_id()) -> get_field('some_name', get_queried_object_id())
        $new_content = preg_replace("/get_field\('([^']+)'\)/", "get_field('$1', get_queried_object_id())", $content);
        
        // Also fix the ACF Options page calls which use 'option'
        // get_field('logo_white', 'option') is fine, we leave it alone.
        // Wait, the regex above only matches get_field('...', get_queried_object_id()) with exactly one parameter string.
        // Let's make sure it doesn't match get_field('...', 'option').
        // The regex `get_field\('([^']+)'\)` explicitly looks for a closing parenthesis immediately after the first string.
        // Let's test if it matched.
        
        if ($new_content !== $content) {
            file_put_contents($file, $new_content);
            echo "Updated " . $file . " to use bulletproof get_field calls.\n";
            $count++;
        }
    }
    
    echo "Fixed " . $count . " templates to guarantee live updates.";
} else {
    echo "Error: WP not found.";
}
