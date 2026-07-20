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
        
        // Auto-assign Region based on the Location
        if (function_exists('kg_get_acf_region_key_by_location')) {
            $region = kg_get_acf_region_key_by_location($terms[0]->name);
            if (function_exists('update_field')) {
                update_field( 'job_region', $region, $post_id );
            } else {
                update_post_meta( $post_id, 'job_region', $region );
                update_post_meta( $post_id, '_job_region', 'field_job_region' );
            }
        }
    } else {
        update_post_meta( $post_id, 'job_location', '' );
        if (function_exists('update_field')) {
            update_field( 'job_region', '', $post_id );
        }
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
 * 2b. Restrict ACF location dropdown to ONLY the recruiter's assigned locations.
 */
function kg_filter_acf_location_dropdown_for_recruiters( $args, $field, $post_id ) {
    if ( function_exists( 'kg_is_current_user_recruiter' ) && kg_is_current_user_recruiter() && ! kg_is_current_user_recruitment_admin() ) {
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
            $args['include'] = $term_ids;
        } else {
            $args['include'] = array( 0 ); // Show empty if no locations assigned
        }
    }
    return $args;
}
add_filter( 'acf/fields/taxonomy/query/name=job_location_tax', 'kg_filter_acf_location_dropdown_for_recruiters', 10, 3 );

/**
 * 2c. Restrict ACF Region dropdown based on recruiter locations.
 */
function kg_get_acf_region_key_by_location($location) {
    $location = strtoupper(trim($location));
    if (empty($location)) return 'Nationwide';

    if (strpos($location, 'MANILA') !== false || strpos($location, 'TAGUIG') !== false || strpos($location, 'MAKATI') !== false || strpos($location, 'QC') !== false || strpos($location, 'ALABANG') !== false || strpos($location, 'NCR') !== false) return 'NCR';
    if (strpos($location, 'BAGUIO') !== false || strpos($location, 'BENGUET') !== false || strpos($location, 'CAR') !== false) return 'CAR';
    if (strpos($location, 'PANGASINAN') !== false || strpos($location, 'DAGUPAN') !== false || strpos($location, 'REGION I') !== false) return 'Ilocos Region (I)';
    if (strpos($location, 'TUGUEGARAO') !== false || strpos($location, 'ISABELA') !== false || strpos($location, 'REGION II') !== false) return 'Cagayan Valley (II)';
    if (strpos($location, 'BULACAN') !== false || strpos($location, 'PAMPANGA') !== false || strpos($location, 'TARLAC') !== false || strpos($location, 'SUBIC') !== false || strpos($location, 'REGION III') !== false) return 'Central Luzon (III)';
    if (strpos($location, 'BATANGAS') !== false || strpos($location, 'LAGUNA') !== false || strpos($location, 'CAVITE') !== false || strpos($location, 'RIZAL') !== false || strpos($location, 'CALABARZON') !== false) return 'CALABARZON (IV-A)';
    if (strpos($location, 'MINDORO') !== false || strpos($location, 'PALAWAN') !== false || strpos($location, 'MIMAROPA') !== false) return 'MIMAROPA (IV-B)';
    if (strpos($location, 'BICOL') !== false || strpos($location, 'ALBAY') !== false || strpos($location, 'CAMARINES') !== false) return 'Bicol (V)';
    if (strpos($location, 'ILOILO') !== false || strpos($location, 'BACOLOD') !== false || strpos($location, 'REGION VI') !== false) return 'Western Visayas (VI)';
    if (strpos($location, 'CEBU') !== false || strpos($location, 'BOHOL') !== false || strpos($location, 'REGION VII') !== false) return 'Central Visayas (VII)';
    if (strpos($location, 'LEYTE') !== false || strpos($location, 'SAMAR') !== false || strpos($location, 'REGION VIII') !== false) return 'Eastern Visayas (VIII)';
    if (strpos($location, 'ZAMBOANGA') !== false || strpos($location, 'REGION IX') !== false) return 'Zamboanga Peninsula (IX)';
    if (strpos($location, 'CAGAYAN DE ORO') !== false || strpos($location, 'REGION X') !== false) return 'Northern Mindanao (X)';
    if (strpos($location, 'DAVAO') !== false || strpos($location, 'REGION XI') !== false) return 'Davao Region (XI)';
    if (strpos($location, 'GENERAL SANTOS') !== false || strpos($location, 'SOCCSKSARGEN') !== false) return 'SOCCSKSARGEN (XII)';
    if (strpos($location, 'BUTUAN') !== false || strpos($location, 'CARAGA') !== false) return 'Caraga (XIII)';
    if (strpos($location, 'COTABATO') !== false || strpos($location, 'BARMM') !== false) return 'BARMM';
    if (strpos($location, 'REMOTE') !== false || strpos($location, 'WFH') !== false) return 'Remote / WFH';

    return 'Nationwide';
}

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
    
    // Deny publish capability for recruiters so their posts go to "Submit for Review" (pending)
    if ( $cap === 'publish_jobs' || $cap === 'publish_posts' ) {
        // This ensures the button says "Submit for Review"
        $caps[] = 'do_not_allow';
    }
    
    return $caps;
}
add_filter( 'map_meta_cap', 'kg_restrict_recruiter_job_permissions', 10, 4 );

/**
 * 4. Hide "Inquiries" and "Quote Requests" from recruiters in the WordPress Admin Menu.
 */
function kg_hide_inquiries_quotes_for_recruiters() {
    $is_recruiter = function_exists( 'kg_is_current_user_recruiter' ) && kg_is_current_user_recruiter();
    $is_recruitment_admin = function_exists( 'kg_is_current_user_recruitment_admin' ) && kg_is_current_user_recruitment_admin();
    
    if ( ! $is_recruiter && ! $is_recruitment_admin ) {
        return;
    }

    // Hide Inquiries, Quote Requests, Testimonials, and Comments custom post types
    remove_menu_page( 'edit.php?post_type=kg_inquiry' );
    remove_menu_page( 'edit.php?post_type=kg_quote_lead' );
    remove_menu_page( 'edit.php?post_type=kg_testimonial' );
    remove_menu_page( 'edit-comments.php' );
}
add_action( 'admin_menu', 'kg_hide_inquiries_quotes_for_recruiters', 999 );

/**
 * Hide dashboard widgets (e.g., Activity) for recruiters and recruitment admins.
 */
function kg_hide_dashboard_widgets_for_recruiters() {
    $is_recruiter = function_exists( 'kg_is_current_user_recruiter' ) && kg_is_current_user_recruiter();
    $is_recruitment_admin = function_exists( 'kg_is_current_user_recruitment_admin' ) && kg_is_current_user_recruitment_admin();
    
    if ( ! $is_recruiter && ! $is_recruitment_admin ) {
        return;
    }
    remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );
}
add_action( 'wp_dashboard_setup', 'kg_hide_dashboard_widgets_for_recruiters', 999 );

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

/**
 * 6. Send email notification when a recruiter submits a job for review.
 */
function kg_notify_on_recruiter_job_publish( $new_status, $old_status, $post ) {
    if ( $post->post_type === 'jobs' && $new_status === 'pending' && $old_status !== 'pending' ) {
        
        $author_id = $post->post_author;
        $author = get_userdata( $author_id );
        
        // Ensure author exists and is a recruiter
        if ( ! $author || ! in_array( 'recruiter', (array) $author->roles, true ) ) {
            return;
        }

        // Get recipients (administrators, hr, recruitment_admin)
        $roles_to_notify = array( 'administrator', 'hr', 'recruitment_admin' );
        $emails = array();

        foreach ( $roles_to_notify as $role ) {
            $users = get_users( array( 'role' => $role ) );
            foreach ( $users as $user ) {
                if ( is_email( $user->user_email ) ) {
                    $emails[] = $user->user_email;
                }
            }
        }
        
        // Remove duplicates just in case someone has multiple roles
        $emails = array_unique( $emails );

        if ( empty( $emails ) ) {
            return;
        }

        $job_title    = get_the_title( $post->ID );
        $author_name  = $author->display_name;
        $edit_link    = admin_url( 'post.php?post=' . $post->ID . '&action=edit' );
        
        if ( function_exists( 'kg_get_parsed_email' ) && function_exists( 'kg_email_wrap' ) ) {
            $job_details_html = kg_email_row( 'Job Title', $job_title ) . kg_email_row( 'Submitted By', $author_name );
            
            $parsed = kg_get_parsed_email( 'recruiter_job_review', array(
                '{site_name}'   => get_bloginfo('name'),
                '{job_title}'   => esc_html( $job_title ),
                '{author_name}' => esc_html( $author_name ),
                '{job_details}' => $job_details_html,
                '{edit_link}'   => esc_url( $edit_link )
            ) );
            
            $subject = $parsed['subject'];
            $body_html = kg_email_heading( $parsed['heading'] ) . $parsed['body'];
            
            if ( ! empty( $parsed['banner'] ) ) {
                $body_html .= kg_email_banner( $parsed['banner'] );
            }
            if ( ! empty( $parsed['btn_text'] ) && ! empty( $parsed['btn_link'] ) ) {
                $body_html .= kg_email_button( $parsed['btn_text'], $parsed['btn_link'] );
            }
            
            $message = kg_email_wrap( $subject, $body_html, 'Kings Team', '', date_i18n( get_option( 'date_format' ) ) );
            
        } else {
            // Fallback just in case template functions are not loaded
            $subject  = sprintf( '[%s] Job Submitted for Review: %s', get_bloginfo('name'), $job_title );
            $message  = "Hello,\n\n";
            $message .= sprintf( "A new job post has been submitted for review by %s.\n\n", $author_name );
            $message .= sprintf( "Job Title: %s\n", $job_title );
            $message .= sprintf( "Please review and publish it here: %s\n\n", $edit_link );
            $message .= "This is an automated notification from your WordPress system.";
        }
        
        $headers = array('Content-Type: text/html; charset=UTF-8');
        
        // Send email
        wp_mail( $emails, $subject, $message, $headers );
    }
}
add_action( 'transition_post_status', 'kg_notify_on_recruiter_job_publish', 20, 3 );

/**
 * 7. Disable Date fields in Quick Edit for recruiters.
 */
function kg_disable_quick_edit_date_for_recruiters() {
    if ( ! function_exists( 'kg_is_current_user_recruiter' ) || ! kg_is_current_user_recruiter() ) {
        return;
    }
    
    $screen = function_exists('get_current_screen') ? get_current_screen() : null;
    if ( $screen && $screen->id === 'edit-jobs' ) {
        echo '<style>
            .inline-edit-col .inline-edit-date {
                pointer-events: none !important;
                opacity: 0.5 !important;
            }
        </style>';
    }
}
add_action( 'admin_head', 'kg_disable_quick_edit_date_for_recruiters' );

/**
 * 8. Auto-generate Slug in Quick Edit based on Title.
 */
function kg_auto_slug_in_quick_edit() {
    $screen = function_exists('get_current_screen') ? get_current_screen() : null;
    if ( $screen && $screen->id === 'edit-jobs' ) {
        echo '<script>
            jQuery(document).ready(function($){
                $(document).on("keyup", ".inline-edit-row input[name=\'post_title\']", function(){
                    var title = $(this).val();
                    var slug = title.toLowerCase().replace(/[^a-z0-9]+/g, "-").replace(/(^-|-$)+/g, "");
                    $(this).closest(".inline-edit-row").find("input[name=\'post_name\']").val(slug);
                });
            });
        </script>';
    }
}
add_action( 'admin_head', 'kg_auto_slug_in_quick_edit' );

/**
 * 9. Capture Timestamps for Job Status Changes (Pending / Published)
 */
function kg_capture_job_status_timestamps( $new_status, $old_status, $post ) {
    if ( $post->post_type !== 'jobs' ) return;

    if ( $new_status === 'pending' && $old_status !== 'pending' ) {
        update_post_meta( $post->ID, 'kg_job_submitted_timestamp', current_time('timestamp') );
    }

    if ( $new_status === 'publish' && $old_status !== 'publish' ) {
        update_post_meta( $post->ID, 'kg_job_published_timestamp', current_time('timestamp') );
    }
}
add_action( 'transition_post_status', 'kg_capture_job_status_timestamps', 10, 3 );

/**
 * 10. Display Timestamps in Jobs List Table
 */
function kg_add_jobs_timestamp_columns( $columns ) {
    $columns['job_submitted'] = 'Submitted (Pending)';
    $columns['job_published'] = 'Published At';
    return $columns;
}
add_filter( 'manage_jobs_posts_columns', 'kg_add_jobs_timestamp_columns' );

function kg_render_jobs_timestamp_columns( $column, $post_id ) {
    if ( $column === 'job_submitted' ) {
        $timestamp = get_post_meta( $post_id, 'kg_job_submitted_timestamp', true );
        if ( $timestamp ) {
            echo date_i18n( 'M j, Y h:i a', $timestamp );
        } else {
            echo '<span style="color:#94a3b8;">-</span>';
        }
    }
    if ( $column === 'job_published' ) {
        $timestamp = get_post_meta( $post_id, 'kg_job_published_timestamp', true );
        if ( $timestamp ) {
            echo date_i18n( 'M j, Y h:i a', $timestamp );
        } else {
            $post_status = get_post_status($post_id);
            if ($post_status === 'publish') {
                echo get_the_date('M j, Y h:i a', $post_id);
            } else {
                echo '<span style="color:#94a3b8;">-</span>';
            }
        }
    }
}
add_action( 'manage_jobs_posts_custom_column', 'kg_render_jobs_timestamp_columns', 10, 2 );

/**
 * 11. Hide Job Category (Local / Offshoring) ACF Field for recruiters and recruitment admins
 */
function kg_hide_acf_offshoring_for_recruiters() {
    $is_recruiter = function_exists( 'kg_is_current_user_recruiter' ) && kg_is_current_user_recruiter();
    $is_recruitment_admin = function_exists( 'kg_is_current_user_recruitment_admin' ) && kg_is_current_user_recruitment_admin();
    
    if ( ! $is_recruiter && ! $is_recruitment_admin ) {
        return;
    }

    $screen = function_exists('get_current_screen') ? get_current_screen() : null;
    if ( $screen && $screen->id === 'jobs' ) {
        echo '<style>
            .acf-field[data-key="field_job_type_tax_acf"] {
                display: none !important;
            }
        </style>';
    }
}
add_action( 'admin_head', 'kg_hide_acf_offshoring_for_recruiters' );

/**
 * 12. Hide Offshoring Jobs from Recruiters and Recruitment Admins in the Backend List
 */
function kg_hide_offshoring_jobs_from_recruiters( $query ) {
    if ( ! is_admin() || ! $query->is_main_query() ) {
        return;
    }

    // Only apply to the jobs post type list
    if ( $query->get('post_type') !== 'jobs' ) {
        return;
    }

    $is_recruiter = function_exists( 'kg_is_current_user_recruiter' ) && kg_is_current_user_recruiter();
    $is_recruitment_admin = function_exists( 'kg_is_current_user_recruitment_admin' ) && kg_is_current_user_recruitment_admin();
    
    if ( $is_recruiter || $is_recruitment_admin ) {
        $tax_query = (array) $query->get( 'tax_query' );
        
        $tax_query[] = array(
            'taxonomy' => 'job_type_tax',
            'field'    => 'slug',
            'terms'    => array( 'offshoring' ),
            'operator' => 'NOT IN',
        );
        
        $query->set( 'tax_query', $tax_query );
    }
}
add_action( 'pre_get_posts', 'kg_hide_offshoring_jobs_from_recruiters' );
