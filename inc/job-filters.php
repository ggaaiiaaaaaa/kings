<?php
/**
 * Admin Filters for Jobs
 */

if (!defined('ABSPATH')) {
    exit;
}

// Add dropdowns to the Jobs list table
add_action('restrict_manage_posts', 'kg_add_jobs_admin_filters');
function kg_add_jobs_admin_filters($post_type) {
    if ($post_type !== 'jobs') {
        return;
    }

    // 1. Author Filter
    $selected_author = isset($_GET['author']) ? $_GET['author'] : '';
    wp_dropdown_users(array(
        'show_option_all' => 'All Authors',
        'name'            => 'author',
        'selected'        => $selected_author,
        'who'             => 'authors',
    ));

    // 2. Location (Taxonomy) Filter
    $selected_location = isset($_GET['job_location_tax']) ? $_GET['job_location_tax'] : '';
    wp_dropdown_categories(array(
        'show_option_all' => 'All Locations',
        'taxonomy'        => 'job_location_tax',
        'name'            => 'job_location_tax',
        'orderby'         => 'name',
        'selected'        => $selected_location,
        'show_count'      => false,
        'hide_empty'      => false,
        'value_field'     => 'slug',
    ));

    // Helper to output ACF Select filters dynamically based on existing data
    $acf_fields = array(
        'job_region' => 'All Regions',
        'job_type' => 'All Employment Types',
        'job_work_setup' => 'All Work Setups',
        'job_department' => 'All Departments'
    );

    global $wpdb;
    
    foreach ($acf_fields as $meta_key => $default_label) {
        $selected = isset($_GET[$meta_key]) ? $_GET[$meta_key] : '';
        echo '<select name="' . esc_attr($meta_key) . '">';
        echo '<option value="">' . esc_html($default_label) . '</option>';
        
        $values = $wpdb->get_col( $wpdb->prepare( "
            SELECT DISTINCT meta_value 
            FROM {$wpdb->postmeta} pm
            JOIN {$wpdb->posts} p ON p.ID = pm.post_id
            WHERE pm.meta_key = %s 
            AND p.post_type = 'jobs' 
            AND p.post_status IN ('publish', 'draft', 'pending', 'private')
            AND pm.meta_value != ''
            ORDER BY pm.meta_value ASC
        ", $meta_key ) );
        
        foreach ($values as $val) {
            $sel = selected($selected, $val, false);
            echo '<option value="' . esc_attr($val) . '" ' . $sel . '>' . esc_html($val) . '</option>';
        }
        echo '</select>';
    }
}

// Process the filters in WP_Query
add_action('pre_get_posts', 'kg_process_jobs_admin_filters');
function kg_process_jobs_admin_filters($query) {
    global $pagenow;
    if (is_admin() && $query->is_main_query() && $pagenow === 'edit.php' && $query->get('post_type') === 'jobs') {
        
        $meta_query = (array) $query->get('meta_query');
        
        $filters = array('job_region', 'job_type', 'job_work_setup', 'job_department');
        foreach ($filters as $meta_key) {
            if (!empty($_GET[$meta_key])) {
                $meta_query[] = array(
                    'key'     => $meta_key,
                    'value'   => sanitize_text_field($_GET[$meta_key]),
                    'compare' => '='
                );
            }
        }
        
        if (!empty($meta_query)) {
            $query->set('meta_query', $meta_query);
        }
    }
}
