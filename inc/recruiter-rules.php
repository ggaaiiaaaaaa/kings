<?php
/**
 * Recruiter role and CPT Jobs Rules / Enhancements
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * 1. Sync job_location meta with selected job_location_tax term for backwards compatibility.
 */
function kg_sync_job_location_meta_on_save( $post_id ) {
    if ( get_post_type( $post_id ) !== 'jobs' ) {
        return;
    }

    $terms = wp_get_post_terms( $post_id, 'job_location_tax' );
    if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
        update_post_meta( $post_id, 'job_location', $terms[0]->name );
    } else {
        update_post_meta( $post_id, 'job_location', '' );
    }
}
add_action( 'save_post_jobs', 'kg_sync_job_location_meta_on_save', 25 );
add_action( 'acf/save_post', 'kg_sync_job_location_meta_on_save', 25 );

/**
 * 2. Auto-set job location to the recruiter's assigned branch on creation.
 */
function kg_auto_assign_recruiter_location_to_job( $post_id, $post, $update ) {
    // Only run on save of jobs post type
    if ( $post->post_type !== 'jobs' ) {
        return;
    }

    // Only run if the current user is a recruiter
    if ( ! function_exists( 'kg_is_current_user_recruiter' ) || ! kg_is_current_user_recruiter() ) {
        return;
    }

    // Get the recruiter's locations
    $rec_id = get_current_user_id();
    $rec_location_slugs = (array) get_user_meta( $rec_id, 'kg_recruiter_location', true );

    $term_ids = array();
    foreach ( $rec_location_slugs as $slug ) {
        $term = get_term_by( 'slug', $slug, 'job_location_tax' );
        if ( $term && ! is_wp_error( $term ) ) {
            $term_ids[] = (int) $term->term_id;
        }
    }

    if ( ! empty( $term_ids ) ) {
        // Temporarily unhook to avoid infinite loop
        remove_action( 'save_post_jobs', 'kg_auto_assign_recruiter_location_to_job', 10 );
        
        wp_set_post_terms( $post_id, $term_ids, 'job_location_tax' );
        $first_term = get_term( $term_ids[0] );
        update_post_meta( $post_id, 'job_location', $first_term->name );
        
        add_action( 'save_post_jobs', 'kg_auto_assign_recruiter_location_to_job', 10, 3 );
    }
}
add_action( 'save_post_jobs', 'kg_auto_assign_recruiter_location_to_job', 10, 3 );

/**
 * 3. Restrict recruiters from editing/deleting other users' jobs.
 */
function kg_restrict_recruiter_job_permissions( $caps, $cap, $user_id, $args ) {
    // Only apply restriction to recruiters
    if ( ! function_exists( 'kg_is_current_user_recruiter' ) ) {
        return $caps;
    }
    
    // Check if the user is a recruiter
    $user = get_userdata( $user_id );
    if ( ! $user || ! in_array( 'recruiter', (array) $user->roles, true ) ) {
        return $caps;
    }

    // Target edit/delete capabilities for specific posts
    if ( in_array( $cap, array( 'edit_post', 'delete_post', 'edit_published_posts', 'delete_published_posts' ), true ) ) {
        $post_id = isset( $args[0] ) ? (int) $args[0] : 0;
        if ( $post_id && get_post_type( $post_id ) === 'jobs' ) {
            $post = get_post( $post_id );
            if ( $post && (int) $post->post_author !== (int) $user_id ) {
                // Deny edit/delete permission if recruiter is not the author
                $caps[] = 'do_not_allow';
            }
        }
    }
    
    return $caps;
}
add_filter( 'map_meta_cap', 'kg_restrict_recruiter_job_permissions', 10, 4 );

/**
 * 4. Hide "Inquiries" and "Quote Requests" from recruiters in the WordPress Admin Menu.
 */
function kg_hide_inquiries_quotes_for_recruiters() {
    if ( ! function_exists( 'kg_is_current_user_recruiter' ) || ! kg_is_current_user_recruiter() ) {
        return;
    }

    // Hide Inquiries and Quote Requests custom post types
    remove_menu_page( 'edit.php?post_type=kg_inquiry' );
    remove_menu_page( 'edit.php?post_type=kg_quote_lead' );
}
add_action( 'admin_menu', 'kg_hide_inquiries_quotes_for_recruiters', 999 );

/**
 * 5. Auto-bump job post date when transitioning from draft to publish.
 */
function kg_auto_bump_job_publish_date( $new_status, $old_status, $post ) {
    if ( $post->post_type === 'jobs' && $old_status === 'draft' && $new_status === 'publish' ) {
        // Temporarily unhook to prevent infinite loop
        remove_action( 'transition_post_status', 'kg_auto_bump_job_publish_date', 10 );
        
        $current_time = current_time('mysql');
        $current_time_gmt = current_time('mysql', 1);
        
        $update_args = array(
            'ID' => $post->ID,
            'post_date' => $current_time,
            'post_date_gmt' => $current_time_gmt
        );
        
        wp_update_post( $update_args );
        
        add_action( 'transition_post_status', 'kg_auto_bump_job_publish_date', 10, 3 );
    }
}
add_action( 'transition_post_status', 'kg_auto_bump_job_publish_date', 10, 3 );
