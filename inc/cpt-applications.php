<?php
/**
 * Applications CPT — stores career form submissions in WP Admin.
 * Each application is a private post with applicant details as meta.
 * Status (pending / accepted / rejected) is managed from the edit screen.
 */
if (!defined('ABSPATH'))
    exit;

/* ─────────────────────────────────────────────
   Register CPT
───────────────────────────────────────────── */

function kg_register_application_cpt()
{
    register_post_type('kg_application', array(
        'labels' => array(
            'name' => 'Applications',
            'singular_name' => 'Application',
            'menu_name' => 'Applications',
            'all_items' => 'All Applications',
            'add_new' => 'Add New Applicant',
            'add_new_item' => 'Add New Applicant',
            'edit_item' => 'View Application',
            'search_items' => 'Search Applications',
            'not_found' => 'No applications found.',
            'not_found_in_trash' => 'No applications in trash.',
        ),
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_rest' => false,
        'supports' => false,
        'has_archive' => false,
        'rewrite' => false,
        'menu_icon' => 'dashicons-id-alt',
        'menu_position' => 5,
        'capability_type' => 'application',
        'map_meta_cap' => true,
    ));
}
add_action('init', 'kg_register_application_cpt');

function kg_application_title_placeholder($title) {
    $screen = get_current_screen();
    if ($screen && $screen->post_type == 'kg_application') {
        return 'Enter Applicant Full Name';
    }
    return $title;
}
add_filter('enter_title_here', 'kg_application_title_placeholder');

function kg_add_enctype_to_application_form($post) {
    if ($post->post_type === 'kg_application') {
        echo ' enctype="multipart/form-data"';
    }
}
add_action('post_edit_form_tag', 'kg_add_enctype_to_application_form');

/* ─────────────────────────────────────────────
   Save application post (called from form handler)
───────────────────────────────────────────── */

function kg_save_application_post($data)
{
    $post_id = wp_insert_post(array(
        'post_title' => sanitize_text_field($data['fullname']),
        'post_status' => 'publish',
        'post_type' => 'kg_application',
        'post_date' => current_time('mysql'),
    ));

    if (is_wp_error($post_id))
        return false;

    update_post_meta($post_id, 'kg_app_email', sanitize_email($data['email']));
    $normalized_email = preg_replace('/(\+.*)(?=@)/', '', strtolower(sanitize_email($data['email'])));
    update_post_meta($post_id, 'kg_app_email_normalized', $normalized_email);
    update_post_meta($post_id, 'kg_app_phone', sanitize_text_field($data['phone']));
    update_post_meta($post_id, 'kg_app_role', sanitize_text_field($data['role']));
    if (!empty($data['job_id'])) {
        update_post_meta($post_id, 'kg_app_job_id', intval($data['job_id']));
        if (!empty($data['applied_via_single'])) {
            update_post_meta($post_id, 'kg_app_locked_job', 'yes');
            update_post_meta($post_id, 'kg_app_applied_via_single', 'yes');
        }
    }
    update_post_meta($post_id, 'kg_app_preferred_roles', $data['preferred_roles'] ?? array());
    update_post_meta($post_id, 'kg_app_linkedin', esc_url_raw($data['linkedin']));
    update_post_meta($post_id, 'kg_app_cv_url', esc_url_raw($data['cv_url']));
    // Pooling applicants start in the talent pool; active job-seekers go to screening
    $initial_status = (isset($data['purpose']) && $data['purpose'] === 'pooling') ? 'pooling' : 'screening';
    update_post_meta($post_id, 'kg_app_status', $initial_status);
    if ($initial_status === 'screening') {
        update_post_meta($post_id, 'kg_app_screening_start_date', current_time('timestamp'));
    }
    update_post_meta($post_id, 'kg_app_client', '');

    // Save dynamic demographic and cascading address metadata
    update_post_meta($post_id, 'kg_app_mname', sanitize_text_field($data['mname'] ?? ''));
    update_post_meta($post_id, 'kg_app_purpose', sanitize_text_field($data['purpose'] ?? ''));
    update_post_meta($post_id, 'kg_app_gender', sanitize_text_field($data['gender'] ?? ''));
    update_post_meta($post_id, 'kg_app_birthday', sanitize_text_field($data['birthday'] ?? ''));
    update_post_meta($post_id, 'kg_app_street', sanitize_text_field($data['street'] ?? ''));
    update_post_meta($post_id, 'kg_app_region', sanitize_text_field($data['region'] ?? ''));
    update_post_meta($post_id, 'kg_app_city', sanitize_text_field($data['city'] ?? ''));
    update_post_meta($post_id, 'kg_app_barangay', sanitize_text_field($data['barangay'] ?? ''));
    update_post_meta($post_id, 'kg_app_region_code', sanitize_text_field($data['region_code'] ?? ''));
    update_post_meta($post_id, 'kg_app_city_code', sanitize_text_field($data['city_code'] ?? ''));
    update_post_meta($post_id, 'kg_app_barangay_code', sanitize_text_field($data['barangay_code'] ?? ''));

    // Recruiter ID is left blank on submission; Admin manually assigns this recruiter.
    $recruiter_id = '';
    update_post_meta($post_id, 'kg_app_recruiter_id', $recruiter_id);

    // Look up and save the branch location of the applied job
    $app_location = '';
    $applied_role = $data['role'] ?? '';

    if (!empty($data['job_id'])) {
        $app_location = get_post_meta(intval($data['job_id']), 'job_location', true);
    } elseif (!empty($applied_role)) {
        $job_posts = get_posts(array(
            'post_type' => 'jobs',
            'title' => $applied_role,
            'posts_per_page' => 1,
            'post_status' => 'any',
            'fields' => 'ids'
        ));
        if (!empty($job_posts)) {
            $job_id = $job_posts[0];
            $app_location = get_post_meta($job_id, 'job_location', true);
        }
    }
    update_post_meta($post_id, 'kg_app_location', $app_location);


    return $post_id;
}

/* ─────────────────────────────────────────────
   Admin columns
───────────────────────────────────────────── */

/* Helper: canonical ATS status list */
function kg_ats_statuses()
{
    return array(
        'pooling' => 'Pooling',
        'screening' => 'Screening',
        'processing' => 'Processing',
        'interviewing' => 'Interviewing',
        'hired' => 'Hired',
        'deployed' => 'Deployed',
        'rejected' => 'Rejected',
    );
}

function kg_application_columns($columns)
{
    return array(
        'cb' => '<input type="checkbox">',
        'title' => 'Applicant Name',
        'kg_email' => 'Email',
        'kg_role' => 'Suitable Job / Assigned Role',
        'kg_recruiter' => 'Recruiter',
        'kg_status' => 'Status',
        'kg_cv' => 'CV',
        'date' => 'Submitted',
    );
}
add_filter('manage_kg_application_posts_columns', 'kg_application_columns');

// Removed SLA Badge from Title because WP escapes HTML in title attributes.
// The SLA badge logic is now moved to the kg_status column.
function kg_application_column_content($column, $post_id)
{
    switch ($column) {

        case 'kg_email':
            $email = get_post_meta($post_id, 'kg_app_email', true);
            echo $email
                ? '<a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a>'
                : '—';
            break;

        case 'kg_role':
            echo esc_html(get_post_meta($post_id, 'kg_app_role', true) ?: 'Not assigned');
            break;

        case 'kg_recruiter':
            $rec_id = get_post_meta($post_id, 'kg_app_recruiter_id', true);
            if ($rec_id) {
                $rec_user = get_userdata($rec_id);
                echo esc_html($rec_user ? $rec_user->display_name : 'Unknown Recruiter');
            } else {
                echo '<span style="color:#94a3b8;font-style:italic;">None</span>';
            }
            break;

        case 'kg_status':
            $status = get_post_meta($post_id, 'kg_app_status', true) ?: 'pooling';
            $status_styles = array(
                'pooling' => 'background:#fef3c7;color:#92400e;',
                'screening' => 'background:#dbeafe;color:#1e40af;',
                'processing' => 'background:#e0f2fe;color:#0369a1;',
                'interviewing' => 'background:#ede9fe;color:#6d28d9;',
                'hired' => 'background:#d1fae5;color:#065f46;',
                'deployed' => 'background:#dcfce7;color:#15803d;',
                'rejected' => 'background:#fee2e2;color:#991b1b;',
            );
            $s = $status_styles[$status] ?? 'background:#f3f4f6;color:#374151;';

            // Check if interview and deploy date fields are filled
            $int_date = get_post_meta($post_id, 'kg_interview_date', true);
            $int_time = get_post_meta($post_id, 'kg_interview_time', true);
            $int_format = get_post_meta($post_id, 'kg_interview_format', true);
            $int_details = get_post_meta($post_id, 'kg_interview_details', true);
            $int_er_id = get_post_meta($post_id, 'kg_interviewer_id', true);
            $has_interview = (!empty($int_date) && !empty($int_time) && !empty($int_format) && !empty($int_details) && !empty($int_er_id)) ? '1' : '0';

            $deploy_date = get_post_meta($post_id, 'kg_app_deploy_date', true);
            $has_deploy_date = !empty($deploy_date) ? '1' : '0';

            echo '<select class="kg-inline-status" data-post-id="' . esc_attr($post_id) . '" data-nonce="' . esc_attr(wp_create_nonce('kg_inline_status_' . $post_id)) . '" data-original-status="' . esc_attr($status) . '" data-has-interview="' . esc_attr($has_interview) . '" data-has-deploy-date="' . esc_attr($has_deploy_date) . '" style="padding:4px 8px;border-radius:6px;font-size:12px;font-weight:600;border:2px solid transparent;cursor:pointer;' . $s . '">';
            foreach (kg_ats_statuses() as $val => $lbl) {
                echo '<option value="' . esc_attr($val) . '"' . selected($status, $val, false) . '>' . esc_html($lbl) . '</option>';
            }
            echo '</select>';

            // SLA Warning/Breach Check (Only if status is screening)
            if ($status === 'screening') {
                $start_date = get_post_meta($post_id, 'kg_app_screening_start_date', true);
                if (!$start_date) {
                    $start_date = strtotime(get_post($post_id)->post_modified);
                }
                $days_stuck = floor((current_time('timestamp') - $start_date) / (60 * 60 * 24));

                if ($days_stuck >= 10) {
                    echo '<div style="margin-top:8px;"><span style="background:#fecaca;color:#dc2626;border:1px solid #fca5a5;padding:2px 6px;border-radius:4px;font-size:10px;font-weight:bold;display:inline-block;" title="Stuck in Screening for ' . $days_stuck . ' days">🚨 SLA BREACH (' . $days_stuck . 'd)</span></div>';
                } elseif ($days_stuck >= 5) {
                    echo '<div style="margin-top:8px;"><span style="background:#ffedd5;color:#ea580c;border:1px solid #fed7aa;padding:2px 6px;border-radius:4px;font-size:10px;font-weight:bold;display:inline-block;" title="Stuck in Screening for ' . $days_stuck . ' days">⚠️ SLA WARNING (' . $days_stuck . 'd)</span></div>';
                }
            }
            break;

        case 'kg_cv':
            $cv_url = get_post_meta($post_id, 'kg_app_cv_url', true);
            if ($cv_url) {
                $download_url = add_query_arg('kg_download_cv', $post_id, home_url('/'));
                echo '<a href="' . esc_url($download_url) . '" target="_blank" class="button button-small">⬇ Download CV</a>';
            } else {
                echo '—';
            }
            break;
    }
}
add_action('manage_kg_application_posts_custom_column', 'kg_application_column_content', 10, 2);

function kg_application_sortable_columns($columns)
{
    $columns['kg_status'] = 'kg_status';
    $columns['kg_role'] = 'kg_role';
    return $columns;
}
add_filter('manage_edit-kg_application_sortable_columns', 'kg_application_sortable_columns');

// SLA Quick Filter for Applications List
add_action('restrict_manage_posts', 'kg_application_sla_filter');
function kg_application_sla_filter($post_type) {
    if ($post_type === 'kg_application') {
        $selected = isset($_GET['kg_sla_filter']) ? $_GET['kg_sla_filter'] : '';
        echo '<select name="kg_sla_filter">';
        echo '<option value="">All SLA Statuses</option>';
        echo '<option value="warning" ' . selected($selected, 'warning', false) . '>SLA Warning (5-9 days)</option>';
        echo '<option value="breach" ' . selected($selected, 'breach', false) . '>SLA Breach (10+ days)</option>';
        echo '</select>';
    }
}

add_action('pre_get_posts', 'kg_application_sla_filter_query');
function kg_application_sla_filter_query($query) {
    global $pagenow;
    if (is_admin() && $pagenow === 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] === 'kg_application' && $query->is_main_query()) {
        if (!empty($_GET['kg_sla_filter'])) {
            $filter = $_GET['kg_sla_filter'];
            
            // Find posts in screening that match the SLA criteria
            $args = array(
                'post_type' => 'kg_application',
                'post_status' => 'any',
                'posts_per_page' => -1,
                'fields' => 'ids',
                'meta_query' => array(
                    array(
                        'key' => 'kg_app_status',
                        'value' => 'screening',
                    )
                )
            );
            $screening_posts = get_posts($args);
            $matched_ids = array();
            $now = current_time('timestamp');

            if (!empty($screening_posts)) {
                foreach ($screening_posts as $pid) {
                    $start_date = get_post_meta($pid, 'kg_app_screening_start_date', true);
                    if (!$start_date) {
                        $start_date = strtotime(get_post($pid)->post_modified);
                    }
                    $days_stuck = floor(($now - $start_date) / (60 * 60 * 24));
                    
                    if ($filter === 'breach' && $days_stuck >= 10) {
                        $matched_ids[] = $pid;
                    } elseif ($filter === 'warning' && $days_stuck >= 5 && $days_stuck < 10) {
                        $matched_ids[] = $pid;
                    }
                }
            }

            if (empty($matched_ids)) {
                $matched_ids = array(0); // force no results
            }
            $query->set('post__in', $matched_ids);
        }
    }
}

/* ─────────────────────────────────────────────
   Meta boxes on edit screen
───────────────────────────────────────────── */

function kg_log_application_audit_trail($post_id, $action, $assignee_id = null)
{
    $current_user = wp_get_current_user();
    $actor_name = $current_user->exists() ? $current_user->display_name : 'System';

    $assignee_name = '';
    if ($assignee_id) {
        $assignee = get_userdata($assignee_id);
        $assignee_name = $assignee ? $assignee->display_name : 'Unknown';
    }

    $log_entry = array(
        'timestamp' => current_time('timestamp'),
        'action' => $action,
        'actor' => $actor_name,
        'assignee' => $assignee_name
    );

    $audit_trail = get_post_meta($post_id, 'kg_app_audit_trail', true);
    if (!is_array($audit_trail)) {
        $audit_trail = array();
    }

    array_unshift($audit_trail, $log_entry);
    update_post_meta($post_id, 'kg_app_audit_trail', $audit_trail);

    // Also write to global DB table
    global $wpdb;
    $wpdb->insert(
        $wpdb->prefix . 'kg_audit_logs',
        array(
            'post_id' => $post_id,
            'timestamp' => current_time('mysql'),
            'action' => $action,
            'actor' => $actor_name,
            'assignee' => $assignee_name
        ),
        array('%d', '%s', '%s', '%s', '%s')
    );
}

function kg_application_meta_boxes()
{
    add_meta_box(
        'kg_app_details',
        'Applicant Details',
        'kg_application_details_box',
        'kg_application',
        'normal',
        'high'
    );
    add_meta_box(
        'kg_app_status_box',
        'Application Status',
        'kg_application_status_box',
        'kg_application',
        'side',
        'high'
    );
    add_meta_box(
        'kg_app_audit_trail_box',
        'Applicant Audit Trail',
        'kg_application_audit_trail_box',
        'kg_application',
        'normal',
        'low'
    );
}
add_action('add_meta_boxes', 'kg_application_meta_boxes');

function kg_application_audit_trail_box($post)
{
    $audit_trail = get_post_meta($post->ID, 'kg_app_audit_trail', true);
    if (empty($audit_trail) || !is_array($audit_trail)) {
        echo '<p>No history available.</p>';
        return;
    }
    echo '<table class="wp-list-table widefat fixed striped">';
    echo '<thead><tr><th>Date & Time</th><th>Activity Details</th><th>Done By</th></tr></thead>';
    echo '<tbody>';
    foreach ($audit_trail as $log) {
        $date = wp_date(get_option('date_format') . ' ' . get_option('time_format'), $log['timestamp']);

        $activity = esc_html($log['action']);
        // For backwards compatibility with older logs that didn't embed the name in the action string
        if ($log['action'] === 'Assigned to Recruiter' && !empty($log['assignee'])) {
            $activity = 'Assigned to ' . esc_html($log['assignee']);
        }

        $actor = esc_html($log['actor']);
        echo "<tr><td>{$date}</td><td>{$activity}</td><td><strong>{$actor}</strong></td></tr>";
    }
    echo '</tbody></table>';
}


function kg_application_details_box($post)
{
    $email = get_post_meta($post->ID, 'kg_app_email', true);
    $phone = get_post_meta($post->ID, 'kg_app_phone', true);
    $role = get_post_meta($post->ID, 'kg_app_role', true);
    $linkedin = get_post_meta($post->ID, 'kg_app_linkedin', true);
    $cv_url = get_post_meta($post->ID, 'kg_app_cv_url', true);

    // Dynamic metadata fields
    $mname = get_post_meta($post->ID, 'kg_app_mname', true);
    $purpose = get_post_meta($post->ID, 'kg_app_purpose', true);
    $gender = get_post_meta($post->ID, 'kg_app_gender', true);
    $birthday = get_post_meta($post->ID, 'kg_app_birthday', true);
    $street = get_post_meta($post->ID, 'kg_app_street', true);
    $region = get_post_meta($post->ID, 'kg_app_region', true);
    $city = get_post_meta($post->ID, 'kg_app_city', true);
    $barangay = get_post_meta($post->ID, 'kg_app_barangay', true);
    $region_code = get_post_meta($post->ID, 'kg_app_region_code', true);
    $city_code = get_post_meta($post->ID, 'kg_app_city_code', true);
    $barangay_code = get_post_meta($post->ID, 'kg_app_barangay_code', true);
    ?>
    <?php $is_editable = ($post->post_status === 'auto-draft' || $post->post_status === 'draft'); ?>
    <table style="width:100%;border-collapse:collapse;">
        <tr>
            <td style="padding:10px 8px;font-weight:600;width:140px;border-bottom:1px solid #f0f0f0;">Full Name</td>
            <td style="padding:10px 8px;border-bottom:1px solid #f0f0f0;">
                <?php if ($is_editable): ?>
                    <label style="font-size: 11px; font-weight: 600;">Full Name (First & Last):</label><br>
                    <?php $current_title = ($post->post_status === 'auto-draft') ? '' : $post->post_title; ?>
                    <input type="text" name="kg_app_fullname" value="<?php echo esc_attr($current_title); ?>" style="width:100%; max-width:300px; padding:4px; margin-bottom:5px;" required /><br>
                    <label style="font-size: 11px; font-weight: 600;">Middle Name:</label><br>
                    <input type="text" name="kg_app_mname" value="<?php echo esc_attr($mname); ?>" style="width:100%; max-width:300px; padding:4px;" />
                <?php else: ?>
                    <span style="font-size:16px; font-weight:600;"><?php echo esc_html($post->post_title); ?></span><br>
                    <span style="color:#666; font-size:12px;">Middle Name: <?php echo esc_html($mname ?: '—'); ?></span>
                    <input type="hidden" name="kg_app_fullname" value="<?php echo esc_attr($post->post_title); ?>" />
                    <input type="hidden" name="kg_app_mname" value="<?php echo esc_attr($mname); ?>" />
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td style="padding:10px 8px;font-weight:600;border-bottom:1px solid #f0f0f0;">Purpose</td>
            <td style="padding:10px 8px;border-bottom:1px solid #f0f0f0;">
                <?php if ($is_editable): ?>
                    <select name="kg_app_purpose" style="width:100%; max-width:300px; padding:4px;">
                        <option value="">— Select —</option>
                        <option value="Job Application" <?php selected($purpose, 'Job Application'); ?>>Job Application</option>
                        <option value="pooling" <?php selected($purpose, 'pooling'); ?>>Pooling</option>
                    </select>
                    <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        var purposeSelect = document.querySelector('select[name="kg_app_purpose"]');
                        var statusSelect = document.querySelector('select[name="kg_app_status"]');
                        if (purposeSelect && statusSelect) {
                            purposeSelect.addEventListener('change', function() {
                                if (this.value === 'Job Application') {
                                    statusSelect.value = 'screening';
                                } else if (this.value === 'pooling') {
                                    statusSelect.value = 'pooling';
                                }
                            });
                        }
                    });
                    </script>
                <?php else: ?>
                    <?php echo esc_html($purpose ?: '—'); ?>
                    <input type="hidden" name="kg_app_purpose" value="<?php echo esc_attr($purpose); ?>" />
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td style="padding:10px 8px;font-weight:600;border-bottom:1px solid #f0f0f0;">Gender</td>
            <td style="padding:10px 8px;border-bottom:1px solid #f0f0f0;">
                <?php if ($is_editable): ?>
                    <select name="kg_app_gender" style="width:100%; max-width:300px; padding:4px;">
                        <option value="">— Select —</option>
                        <option value="Male" <?php selected(strtolower($gender), 'male'); ?>>Male</option>
                        <option value="Female" <?php selected(strtolower($gender), 'female'); ?>>Female</option>
                    </select>
                <?php else: ?>
                    <?php echo esc_html($gender ?: '—'); ?>
                    <input type="hidden" name="kg_app_gender" value="<?php echo esc_attr($gender); ?>" />
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td style="padding:10px 8px;font-weight:600;border-bottom:1px solid #f0f0f0;">Birthdate</td>
            <td style="padding:10px 8px;border-bottom:1px solid #f0f0f0;">
                <?php if ($is_editable): ?>
                    <input type="date" name="kg_app_birthday" value="<?php echo esc_attr($birthday); ?>" style="width:100%; max-width:300px; padding:4px;" />
                <?php else: ?>
                    <?php echo esc_html($birthday ?: '—'); ?>
                    <input type="hidden" name="kg_app_birthday" value="<?php echo esc_attr($birthday); ?>" />
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td style="padding:10px 8px;font-weight:600;border-bottom:1px solid #f0f0f0;">Address</td>
            <td style="padding:10px 8px;border-bottom:1px solid #f0f0f0;">
                <?php if ($is_editable): ?>
                    <input type="text" name="kg_app_street" value="<?php echo esc_attr($street); ?>" placeholder="Street" style="width:100%; max-width:300px; padding:4px; margin-bottom:4px;" /><br>
                    <input type="text" name="kg_app_barangay" value="<?php echo esc_attr($barangay); ?>" placeholder="Barangay" style="width:100%; max-width:300px; padding:4px; margin-bottom:4px;" /><br>
                    <input type="text" name="kg_app_city" value="<?php echo esc_attr($city); ?>" placeholder="City" style="width:100%; max-width:300px; padding:4px; margin-bottom:4px;" /><br>
                    <input type="text" name="kg_app_region" value="<?php echo esc_attr($region); ?>" placeholder="Region" style="width:100%; max-width:300px; padding:4px;" />
                <?php else: ?>
                    <?php 
                    $addr_parts = array_filter(array($street, $barangay, $city, $region));
                    echo !empty($addr_parts) ? esc_html(implode(', ', $addr_parts)) : '—'; 
                    ?>
                    <input type="hidden" name="kg_app_street" value="<?php echo esc_attr($street); ?>" />
                    <input type="hidden" name="kg_app_barangay" value="<?php echo esc_attr($barangay); ?>" />
                    <input type="hidden" name="kg_app_city" value="<?php echo esc_attr($city); ?>" />
                    <input type="hidden" name="kg_app_region" value="<?php echo esc_attr($region); ?>" />
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td style="padding:10px 8px;font-weight:600;border-bottom:1px solid #f0f0f0;">Email</td>
            <td style="padding:10px 8px;border-bottom:1px solid #f0f0f0;">
                <?php if ($is_editable): ?>
                    <input type="email" name="kg_app_email" value="<?php echo esc_attr($email); ?>" style="width:100%; max-width:300px; padding:4px;" />
                <?php else: ?>
                    <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email ?: '—'); ?></a>
                    <input type="hidden" name="kg_app_email" value="<?php echo esc_attr($email); ?>" />
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td style="padding:10px 8px;font-weight:600;border-bottom:1px solid #f0f0f0;">Phone</td>
            <td style="padding:10px 8px;border-bottom:1px solid #f0f0f0;">
                <?php if ($is_editable): ?>
                    <input type="text" name="kg_app_phone" value="<?php echo esc_attr($phone); ?>" style="width:100%; max-width:300px; padding:4px;" />
                <?php else: ?>
                    <?php echo esc_html($phone ?: '—'); ?>
                    <input type="hidden" name="kg_app_phone" value="<?php echo esc_attr($phone); ?>" />
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td style="padding:10px 8px;font-weight:600;border-bottom:1px solid #f0f0f0;">Preferred Roles</td>
            <td style="padding:10px 8px;border-bottom:1px solid #f0f0f0;">
                <?php
                $preferred_roles = get_post_meta($post->ID, 'kg_app_preferred_roles', true);
                if (is_array($preferred_roles)) {
                    $preferred_roles = array_filter(array_map('trim', $preferred_roles));
                } else {
                    $preferred_roles = array();
                }

                if ($is_editable):
                    $all_jobs_posts_for_roles = get_posts(array(
                        'post_type' => 'jobs',
                        'post_status' => 'publish',
                        'posts_per_page' => -1,
                        'orderby' => 'title',
                        'order' => 'ASC',
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'job_type_tax',
                                'field' => 'slug',
                                'terms' => 'offshoring',
                                'operator' => 'NOT IN',
                            ),
                        ),
                    ));
                    $available_roles = array();
                    if ($all_jobs_posts_for_roles) {
                        foreach ($all_jobs_posts_for_roles as $job_post) {
                            $available_roles[$job_post->post_title] = $job_post->post_title;
                        }
                    }
                ?>
                    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
                    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
                    <select name="kg_app_preferred_roles_arr[]" class="kg-select2-roles" multiple="multiple" style="width:100%; max-width:300px;">
                        <?php foreach ($available_roles as $role_title): ?>
                            <option value="<?php echo esc_attr($role_title); ?>" <?php echo in_array($role_title, $preferred_roles) ? 'selected' : ''; ?>><?php echo esc_html($role_title); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <script>
                    jQuery(document).ready(function($) {
                        $('.kg-select2-roles').select2({
                            placeholder: 'Select preferred roles...'
                        });
                    });
                    </script>
                <?php else: ?>
                    <?php echo !empty($preferred_roles) ? esc_html(implode(', ', $preferred_roles)) : '—'; ?>
                    <?php foreach ($preferred_roles as $role_val): ?>
                        <input type="hidden" name="kg_app_preferred_roles_arr[]" value="<?php echo esc_attr($role_val); ?>" />
                    <?php endforeach; ?>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td style="padding:10px 8px;font-weight:600;border-bottom:1px solid #f0f0f0;">Suitable Job / Assigned Role</td>
            <td style="padding:10px 8px;border-bottom:1px solid #f0f0f0;">
                <?php
                $preferred_roles = get_post_meta($post->ID, 'kg_app_preferred_roles', true) ?: array();
                $applied_roles = array_unique(array_filter(array_merge((array) $role, (array) $preferred_roles)));
                $current_status_for_dropdown = get_post_meta($post->ID, 'kg_app_status', true) ?: 'pooling';
                $current_job_id = get_post_meta($post->ID, 'kg_app_job_id', true);

                $all_jobs_posts = get_posts(array(
                    'post_type' => 'jobs',
                    'post_status' => 'publish',
                    'posts_per_page' => -1,
                    'orderby' => 'title',
                    'order' => 'ASC',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'job_type_tax',
                            'field' => 'slug',
                            'terms' => 'offshoring',
                            'operator' => 'NOT IN',
                        ),
                    ),
                ));

                // For pooling applicants: show all jobs. For others: restrict to applied roles only.
                if ($current_status_for_dropdown !== 'pooling' && !empty($applied_roles)) {
                    $filtered_jobs = array_filter($all_jobs_posts, function ($job) use ($applied_roles) {
                        return in_array($job->post_title, $applied_roles, true);
                    });
                } else {
                    $filtered_jobs = $all_jobs_posts;
                }
                
                $is_locked = get_post_meta($post->ID, 'kg_app_locked_job', true);

                if (!empty($current_job_id) && $is_locked === 'yes') {
                    $assigned_job = get_post($current_job_id);
                    if ($assigned_job) {
                        $location = get_post_meta($assigned_job->ID, 'job_location', true) ?: 'No Location';
                        $label = $assigned_job->post_title . ' - ' . $location;
                        echo '<div style="padding: 6px 12px; font-size: 14px; border: 1px solid #ddd; border-radius: 4px; background: #f5f5f5; color: #333; display: inline-block;">';
                        echo '<strong>' . esc_html($label) . '</strong>';
                        echo '</div>';
                        echo '<input type="hidden" name="kg_app_job_id" value="' . esc_attr($current_job_id) . '">';
                        echo '<input type="hidden" name="kg_app_role_legacy" value="' . esc_attr($role) . '">';
                        echo '<p class="description" style="margin: 4px 0 0 0; font-size: 11px; color: #666;">This application is locked to this specific vacancy because the applicant applied directly to it.</p>';
                    }
                } else {
                ?>
                <select name="kg_app_job_id"
                    style="width:100%; max-width:400px; padding:6px 10px; font-size:14px; border: 1px solid #ccc; border-radius: 4px;">
                    <option value="">— Choose a Specific Vacancy —</option>
                    <?php
                    if (!empty($filtered_jobs)) {
                        foreach ($filtered_jobs as $job) {
                            $location = get_post_meta($job->ID, 'job_location', true) ?: 'No Location';
                            $label = $job->post_title . ' - ' . $location;
                            $selected = selected($current_job_id, $job->ID, false);
                            echo '<option value="' . esc_attr($job->ID) . '" ' . $selected . '>' . esc_html($label) . '</option>';
                        }
                    }
                    // Fallback if role exists but no job is explicitly selected
                    if (!empty($role) && empty($current_job_id)) {
                        echo '<option value="" selected>Pending Job Assignment</option>';
                    }
                    ?>
                </select>
                <input type="hidden" name="kg_app_role_legacy" value="<?php echo esc_attr($role); ?>">
                <p class="description" style="margin: 4px 0 0 0; font-size: 11px; color: #666;">Select the exact job post
                    and location for this applicant.</p>
                <?php } ?>
            </td>
        </tr>
        <tr id="kg-assigned-recruiter-row">
            <td style="padding:10px 8px;font-weight:600;border-bottom:1px solid #f0f0f0;">Assigned Recruiter</td>
            <td style="padding:10px 8px;border-bottom:1px solid #f0f0f0;">
                <?php
                $rec_id = get_post_meta($post->ID, 'kg_app_recruiter_id', true);
                if (current_user_can('manage_options') || kg_is_current_user_recruitment_admin() || kg_is_current_user_recruiter()) {
                    // Admins and recruiters get an interactive select dropdown
                    $recruiters = get_users(array('role__in' => array('recruiter')));
                    echo '<select name="kg_app_recruiter_id" style="width:100%; max-width:400px; padding:6px 10px; font-size:14px; border: 1px solid #ccc; border-radius: 4px;">';
                    echo '<option value="">— Unassigned —</option>';
                    foreach ($recruiters as $rec) {
                        $status = get_user_meta($rec->ID, 'kg_recruiter_status', true) ?: 'active';
                        if ($status === 'inactive' && $rec->ID != $rec_id) {
                            continue;
                        }
                        $selected = selected($rec_id, $rec->ID, false);
                        $inactive_label = ($status === 'inactive') ? ' (Inactive)' : '';
                        echo '<option value="' . esc_attr($rec->ID) . '" ' . $selected . '>' . esc_html($rec->display_name . $inactive_label) . '</option>';
                    }
                    echo '</select>';
                } else {
                    // Non-admins (recruiters) just see the label
                    if ($rec_id) {
                        $rec_user = get_userdata($rec_id);
                        echo esc_html($rec_user ? $rec_user->display_name : 'Unknown Recruiter');
                    } else {
                        echo '<span style="color:#94a3b8;font-style:italic;">None</span>';
                    }
                }
                ?>
            </td>
        </tr>
        <tr>
            <td style="padding:10px 8px;font-weight:600;border-bottom:1px solid #f0f0f0;">CV File</td>
            <td style="padding:10px 8px;border-bottom:1px solid #f0f0f0;">
                <?php if ($cv_url):
                    $preview_url = add_query_arg(array('kg_download_cv' => $post->ID, 'inline' => 1), home_url('/'));
                    $download_url = add_query_arg('kg_download_cv', $post->ID, home_url('/'));
                    ?>
                    <a href="<?php echo esc_url($preview_url); ?>" target="_blank" class="button button-primary"
                        style="margin-right:8px;">👁 View CV</a>
                    <a href="<?php echo esc_url($download_url); ?>" target="_blank" class="button">⬇ Download CV</a>
                    <br><br>
                <?php endif; ?>
                <?php if ($is_editable): ?>
                    <label style="font-size: 11px; font-weight: 600;">Upload New CV (PDF/Word):</label><br>
                    <input type="file" name="kg_app_cv_file" accept=".pdf,.doc,.docx" style="width:100%; max-width:300px; padding:4px;" />
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td style="padding:10px 8px;font-weight:600;vertical-align:top;border-bottom:1px solid #f0f0f0;">Staff Notes &
                Comments</td>
            <td style="padding:10px 8px;border-bottom:1px solid #f0f0f0;">
                <?php
                $notes = get_post_meta($post->ID, 'kg_app_notes', true);
                if (!is_array($notes)) {
                    $notes = array();
                }
                ?>
                <div class="kg-notes-thread"
                    style="max-height: 250px; overflow-y: auto; margin-bottom: 12px; background: #f8fafc; padding: 10px; border-radius: 6px; border: 1px solid #e2e8f0;">
                    <?php if (empty($notes)): ?>
                        <p style="color: #64748b; font-style: italic; margin: 0; font-size: 13px;">No staff notes added yet.</p>
                    <?php else: ?>
                        <?php foreach (array_reverse($notes) as $note): ?>
                            <div class="kg-note-item"
                                style="margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #e2e8f0; font-size: 13px;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                                    <strong style="color: #1e293b;"><?php echo esc_html($note['author']); ?></strong>
                                    <span
                                        style="color: #64748b; font-size: 11px;"><?php echo esc_html(date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $note['timestamp'])); ?></span>
                                </div>
                                <div style="color: #334155; line-height: 1.4; white-space: pre-wrap;">
                                    <?php echo esc_html($note['message']); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <textarea name="kg_add_note"
                    style="width: 100%; height: 70px; padding: 6px 10px; border-radius: 4px; border: 1px solid #ccc; font-size: 13px;"
                    placeholder="Type a note here to add to the thread..."></textarea>
                <p class="description" style="margin: 4px 0 0 0; font-size: 11px; color: #666;">Type a note and click
                    "Update" above or below to save it.</p>
            </td>
        </tr>
        <tr>
            <td style="padding:10px 8px;font-weight:600;vertical-align:top;">Applicant History</td>
            <td style="padding:10px 8px;">
                <?php
                $meta_queries = array('relation' => 'OR');
                if (!empty($email)) {
                    $meta_queries[] = array('key' => 'kg_app_email', 'value' => $email);
                }
                if (!empty($phone)) {
                    $meta_queries[] = array('key' => 'kg_app_phone', 'value' => $phone);
                }

                $history_query = new WP_Query(array(
                    'post_type' => 'kg_application',
                    'post_status' => 'publish',
                    'posts_per_page' => 5,
                    'post__not_in' => array($post->ID),
                    'meta_query' => $meta_queries,
                ));

                if (!$history_query->have_posts()): ?>
                    <p style="color: #64748b; font-style: italic; margin: 0; font-size: 13px;">No prior applications found for
                        this applicant.</p>
                <?php else: ?>
                    <div
                        style="background: #f8fafc; padding: 10px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 13px;">
                        <ul style="margin: 0; padding-left: 15px; line-height: 1.5;">
                            <?php while ($history_query->have_posts()):
                                $history_query->the_post();
                                $h_id = get_the_ID();
                                $h_role = get_post_meta($h_id, 'kg_app_role', true) ?: 'Unassigned Role';
                                $h_status = get_post_meta($h_id, 'kg_app_status', true) ?: 'pooling';
                                $statuses = kg_ats_statuses();
                                $h_status_lbl = $statuses[$h_status] ?? $h_status;
                                $h_date = get_the_date(get_option('date_format'));
                                ?>
                                <li style="margin-bottom: 6px;">
                                    <strong><?php echo esc_html($h_date); ?></strong> —
                                    <span>Applied for <strong><?php echo esc_html($h_role); ?></strong></span>
                                    <span
                                        style="font-size: 11px; padding: 2px 6px; border-radius: 4px; font-weight: bold; margin-left: 5px; background: #e2e8f0; color: #475569;"><?php echo esc_html($h_status_lbl); ?></span>
                                    <a href="<?php echo esc_url(get_edit_post_link($h_id)); ?>"
                                        style="margin-left: 8px; text-decoration: none;">View</a>
                                </li>
                            <?php endwhile;
                            wp_reset_postdata(); ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </td>
        </tr>

    </table>
    <?php
}

function kg_application_status_box($post)
{
    wp_nonce_field('kg_app_status_save', 'kg_app_status_nonce');
    $status = get_post_meta($post->ID, 'kg_app_status', true) ?: 'pooling';
    ?>
    <div style="margin-bottom:12px;">
        <label style="font-size:12px;font-weight:600;display:block;margin-bottom:4px;">Pipeline Status</label>
        <select name="kg_app_status" data-original-status="<?php echo esc_attr($status); ?>"
            style="width:100%;padding:8px;font-size:14px;">
            <?php foreach (kg_ats_statuses() as $val => $lbl): ?>
                <option value="<?php echo esc_attr($val); ?>" <?php selected($status, $val); ?>><?php echo esc_html($lbl); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <?php
    $int_date = get_post_meta($post->ID, 'kg_interview_date', true);
    $int_time = get_post_meta($post->ID, 'kg_interview_time', true);
    $int_format = get_post_meta($post->ID, 'kg_interview_format', true);
    $int_details = get_post_meta($post->ID, 'kg_interview_details', true);
    $int_er_id = get_post_meta($post->ID, 'kg_interviewer_id', true);

    $recruiters = get_users(array('role__in' => array('recruiter')));
    ?>

    <div id="kg-status-validation-warning"
        style="display:none; background:#fee2e2; border:1px solid #fca5a5; color:#991b1b; padding:10px; border-radius:6px; font-size:12px; margin-bottom:12px; font-weight:600;">
    </div>

    <div style="margin-top:16px; border-top:1px solid #eee; padding-top:12px; display: <?php echo ($status === 'interviewing') ? 'block' : 'none'; ?>;"
        id="kg-scheduler-section">
        <h4 style="margin:0 0 8px 0; font-size:13px;">Schedule Interview</h4>

        <div style="margin-bottom:8px;">
            <label style="font-size:11px;font-weight:600;display:block;margin-bottom:2px;">Date</label>
            <input type="date" name="kg_interview_date" value="<?php echo esc_attr($int_date); ?>"
                style="width:100%; padding:4px;" />
        </div>

        <div style="margin-bottom:8px;">
            <label style="font-size:11px;font-weight:600;display:block;margin-bottom:2px;">Time</label>
            <input type="time" name="kg_interview_time" value="<?php echo esc_attr($int_time); ?>"
                style="width:100%; padding:4px;" />
        </div>

        <div style="margin-bottom:8px;">
            <label style="font-size:11px;font-weight:600;display:block;margin-bottom:2px;">Format</label>
            <select name="kg_interview_format" style="width:100%; padding:4px;">
                <option value="">— Select —</option>
                <option value="online" <?php selected($int_format, 'online'); ?>>Online (Teams / Zoom / GMeet)</option>
                <option value="face_to_face" <?php selected($int_format, 'face_to_face'); ?>>Face-to-Face (Office)</option>
            </select>
        </div>

        <div style="margin-bottom:8px;">
            <label style="font-size:11px;font-weight:600;display:block;margin-bottom:2px;">Meeting Link or Address</label>
            <textarea name="kg_interview_details"
                style="width:100%; height:45px; padding:4px; font-size:12px;"><?php echo esc_textarea($int_details); ?></textarea>
        </div>

        <div style="margin-bottom:8px;">
            <label style="font-size:11px;font-weight:600;display:block;margin-bottom:2px;">Interviewer</label>
            <select name="kg_interviewer_id" style="width:100%; padding:4px;">
                <option value="">— Select Recruiter —</option>
                <?php foreach ($recruiters as $rec):
                    $status = get_user_meta($rec->ID, 'kg_recruiter_status', true) ?: 'active';
                    if ($status === 'inactive' && $rec->ID != $int_er_id)
                        continue;
                    $inactive_label = ($status === 'inactive') ? ' (Inactive)' : '';
                    ?>
                    <option value="<?php echo esc_attr($rec->ID); ?>" <?php selected($int_er_id, $rec->ID); ?>>
                        <?php echo esc_html($rec->display_name . $inactive_label); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div style="margin-top:16px; border-top:1px solid #eee; padding-top:12px; display: <?php echo ($status === 'processing') ? 'block' : 'none'; ?>;"
        id="kg-processing-section">
        <h4 style="margin:0 0 8px 0; font-size:13px;">Processing Details</h4>
        <div style="margin-bottom:8px;">
            <label style="font-size:11px;font-weight:600;display:block;margin-bottom:2px;">Deadline of Submission of
                Requirements</label>
            <input type="date" name="kg_app_submission_date"
                value="<?php echo esc_attr(get_post_meta($post->ID, 'kg_app_submission_date', true)); ?>"
                style="width:100%; padding:4px;" />
        </div>
        <div style="margin-bottom:8px;">
            <label style="font-size:11px;font-weight:600;display:block;margin-bottom:2px;">Target Date of Deployment</label>
            <input type="date" name="kg_app_target_deploy_date"
                value="<?php echo esc_attr(get_post_meta($post->ID, 'kg_app_target_deploy_date', true)); ?>"
                style="width:100%; padding:4px;" />
        </div>
    </div>

    <div style="margin-top:16px; border-top:1px solid #eee; padding-top:12px; display: <?php echo ($status === 'deployed') ? 'block' : 'none'; ?>;"
        id="kg-deploy-date-section">
        <h4 style="margin:0 0 8px 0; font-size:13px;">Deployment Details</h4>
        <div style="margin-bottom:8px;">
            <label style="font-size:11px;font-weight:600;display:block;margin-bottom:2px;">Deployment Start Date</label>
            <input type="date" name="kg_app_deploy_date"
                value="<?php echo esc_attr(get_post_meta($post->ID, 'kg_app_deploy_date', true)); ?>"
                style="width:100%; padding:4px;" />
        </div>
    </div>

    <p style="font-size:12px;color:#666;margin:10px 0 0 0;">Click <strong>Update</strong> to save pipeline &amp; scheduling
        changes.</p>

    <script>
        jQuery(document).ready(function ($) {
            var $statusSelect = $('select[name="kg_app_status"]');
            var $warningBox = $('#kg-status-validation-warning');
            var $publishBtn = $('#publish');

            // Fields
            var $dateInp = $('input[name="kg_interview_date"]');
            var $timeInp = $('input[name="kg_interview_time"]');
            var $formatSelect = $('select[name="kg_interview_format"]');
            var $detailsInp = $('textarea[name="kg_interview_details"]');
            var $interviewerSelect = $('select[name="kg_interviewer_id"]');
            var $deployDateInp = $('input[name="kg_app_deploy_date"]');

            var $targetDeployDateInp = $('input[name="kg_app_target_deploy_date"]');
            var $submissionDateInp = $('input[name="kg_app_submission_date"]');

            var $recruiterSelect = $('select[name="kg_app_recruiter_id"]');
            var existingRecruiter = '<?php echo esc_js(get_post_meta($post->ID, "kg_app_recruiter_id", true)); ?>';

            var originalStatus = $statusSelect.data('original-status');
            var statusOrder = {
                'pooling': 0,
                'screening': 1,
                'processing': 2,
                'interviewing': 3,
                'hired': 4,
                'deployed': 5
            };

            function doValidate() {
                var status = $statusSelect.val();
                var isValid = true;
                var errorMsg = "";

                // Reset field styles
                $('.kg-validation-error-field').removeClass('kg-validation-error-field').css('border', '');

                // Show/Hide sections dynamically
                if (status === 'interviewing') {
                    $('#kg-scheduler-section').show();
                } else {
                    $('#kg-scheduler-section').hide();
                }

                if (status === 'processing') {
                    $('#kg-processing-section').show();
                } else {
                    $('#kg-processing-section').hide();
                }

                if (status === 'deployed') {
                    $('#kg-deploy-date-section').show();
                } else {
                    $('#kg-deploy-date-section').hide();
                }

                // Recruiter row is now always visible for harvesting

                // 1. Check progression lock
                if (['interviewing', 'processing', 'hired', 'deployed'].indexOf(originalStatus) !== -1 && status !== 'rejected') {
                    if (statusOrder[status] < statusOrder[originalStatus]) {
                        isValid = false;
                        errorMsg = "Status cannot be reverted backward once it reaches the Interviewing stage.";
                    }
                }

                // 1b. Check if recruiter is assigned when moving past pooling
                var currentRecVal = $recruiterSelect.length ? $recruiterSelect.val() : existingRecruiter;
                if (isValid && status !== 'pooling' && status !== 'rejected' && !currentRecVal) {
                    isValid = false;
                    errorMsg = "An Assigned Recruiter must be selected to move past Pooling.";
                    if ($recruiterSelect.length) {
                        $recruiterSelect.addClass('kg-validation-error-field').css('border', '1px solid #ef4444');
                    }
                }

                // 2. Validate interviewing fields
                if (isValid && status === 'interviewing') {
                    var missing = [];
                    if (!$dateInp.val()) { missing.push('Date'); $dateInp.addClass('kg-validation-error-field').css('border', '1px solid #ef4444'); }
                    if (!$timeInp.val()) { missing.push('Time'); $timeInp.addClass('kg-validation-error-field').css('border', '1px solid #ef4444'); }
                    if (!$formatSelect.val()) { missing.push('Format'); $formatSelect.addClass('kg-validation-error-field').css('border', '1px solid #ef4444'); }
                    if (!$detailsInp.val()) { missing.push('Meeting Link/Address'); $detailsInp.addClass('kg-validation-error-field').css('border', '1px solid #ef4444'); }
                    if (!$interviewerSelect.val()) { missing.push('Interviewer'); $interviewerSelect.addClass('kg-validation-error-field').css('border', '1px solid #ef4444'); }

                    if (missing.length > 0) {
                        isValid = false;
                        errorMsg = "To set status to Interviewing, you must fill out: " + missing.join(', ') + ".";
                    }
                }

                // 2b. Validate processing fields
                if (isValid && status === 'processing') {
                    var missingProc = [];
                    if (!$submissionDateInp.val()) { missingProc.push('Deadline of Submission of Requirements'); $submissionDateInp.addClass('kg-validation-error-field').css('border', '1px solid #ef4444'); }
                    if (!$targetDeployDateInp.val()) { missingProc.push('Target Date of Deployment'); $targetDeployDateInp.addClass('kg-validation-error-field').css('border', '1px solid #ef4444'); }
                    if (missingProc.length > 0) {
                        isValid = false;
                        errorMsg = "To set status to Processing, you must fill out: " + missingProc.join(', ') + ".";
                    }
                }

                // 3. Validate deployed fields
                if (isValid && status === 'deployed') {
                    if (!$deployDateInp.val()) {
                        isValid = false;
                        $deployDateInp.addClass('kg-validation-error-field').css('border', '1px solid #ef4444');
                        errorMsg = "To set status to Deployed, you must specify a Deployment Start Date first.";
                    }
                }

                // Apply validation state
                if (!isValid) {
                    $warningBox.html('<strong>Warning:</strong> ' + errorMsg).show();
                    $publishBtn.attr('disabled', 'disabled').addClass('button-disabled');
                } else {
                    $warningBox.hide().text('');
                    $publishBtn.removeAttr('disabled').removeClass('button-disabled');
                }
            }

            // Attach event listeners
            $statusSelect.on('change', doValidate);
            $dateInp.on('input change', doValidate);
            $timeInp.on('input change', doValidate);
            $formatSelect.on('change', doValidate);
            $detailsInp.on('input change', doValidate);
            $interviewerSelect.on('change', doValidate);
            $deployDateInp.on('input change', doValidate);
            $targetDeployDateInp.on('input change', doValidate);
            $submissionDateInp.on('input change', doValidate);
            if ($recruiterSelect.length) {
                $recruiterSelect.on('change', doValidate);
            }

            // Run validation initially
            doValidate();
        });
    </script>
    <?php
}

function kg_save_application_status($post_id)
{
    if (!isset($_POST['kg_app_status_nonce']))
        return;
    if (!wp_verify_nonce($_POST['kg_app_status_nonce'], 'kg_app_status_save'))
        return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;
    if (!current_user_can('edit_post', $post_id))
        return;

    if (!isset($_POST['kg_app_status']))
        return;

    $allowed = array_keys(kg_ats_statuses());
    $new_status = in_array($_POST['kg_app_status'], $allowed, true)
        ? $_POST['kg_app_status']
        : 'pooling';
    $old_status_raw = get_post_meta($post_id, 'kg_app_status', true);
    $is_new_application = empty($old_status_raw);
    $old_status = $old_status_raw ?: 'pooling';

    // Save manual entry fields
    if (isset($_POST['kg_app_mname'])) update_post_meta($post_id, 'kg_app_mname', sanitize_text_field($_POST['kg_app_mname']));
    if (isset($_POST['kg_app_purpose'])) update_post_meta($post_id, 'kg_app_purpose', sanitize_text_field($_POST['kg_app_purpose']));
    if (isset($_POST['kg_app_gender'])) update_post_meta($post_id, 'kg_app_gender', sanitize_text_field($_POST['kg_app_gender']));
    if (isset($_POST['kg_app_birthday'])) update_post_meta($post_id, 'kg_app_birthday', sanitize_text_field($_POST['kg_app_birthday']));
    if (isset($_POST['kg_app_street'])) update_post_meta($post_id, 'kg_app_street', sanitize_text_field($_POST['kg_app_street']));
    if (isset($_POST['kg_app_barangay'])) update_post_meta($post_id, 'kg_app_barangay', sanitize_text_field($_POST['kg_app_barangay']));
    if (isset($_POST['kg_app_city'])) update_post_meta($post_id, 'kg_app_city', sanitize_text_field($_POST['kg_app_city']));
    if (isset($_POST['kg_app_region'])) update_post_meta($post_id, 'kg_app_region', sanitize_text_field($_POST['kg_app_region']));
    if (isset($_POST['kg_app_email'])) {
        update_post_meta($post_id, 'kg_app_email', sanitize_email($_POST['kg_app_email']));
        update_post_meta($post_id, 'kg_app_email_normalized', preg_replace('/(\+.*)(?=@)/', '', strtolower(sanitize_email($_POST['kg_app_email']))));
    }
    if (isset($_POST['kg_app_phone'])) update_post_meta($post_id, 'kg_app_phone', sanitize_text_field($_POST['kg_app_phone']));
    
    if (isset($_POST['kg_app_preferred_roles_arr']) && is_array($_POST['kg_app_preferred_roles_arr'])) {
        $roles_arr = array_filter(array_map('sanitize_text_field', $_POST['kg_app_preferred_roles_arr']));
        update_post_meta($post_id, 'kg_app_preferred_roles', $roles_arr);
    } else {
        update_post_meta($post_id, 'kg_app_preferred_roles', array());
    }

    if (isset($_POST['kg_app_fullname'])) {
        $fullname = sanitize_text_field($_POST['kg_app_fullname']);
        if (!empty($fullname)) {
            remove_action('save_post_kg_application', 'kg_save_application_status', 10);
            wp_update_post(array('ID' => $post_id, 'post_title' => $fullname));
            add_action('save_post_kg_application', 'kg_save_application_status', 10, 3);
        }
    }

    if (!empty($_FILES['kg_app_cv_file']['name'])) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        $uploadedfile = $_FILES['kg_app_cv_file'];
        
        $raw_name = $uploadedfile['name'];
        $clean_name = preg_replace('/[\s_]+/', '-', $raw_name);
        $uploadedfile['name'] = sanitize_file_name($clean_name);

        $upload_dir = wp_upload_dir();
        $secure_dir = $upload_dir['basedir'] . '/secure-cvs';
        if (!file_exists($secure_dir)) {
            wp_mkdir_p($secure_dir);
        }
        $htaccess_file = $secure_dir . '/.htaccess';
        if (!file_exists($htaccess_file)) {
            @file_put_contents($htaccess_file, "Deny from all\n");
        }

        if (!function_exists('kg_secure_upload_directory_manual')) {
            function kg_secure_upload_directory_manual($param) {
                $param['path'] = $param['basedir'] . '/secure-cvs';
                $param['url'] = $param['baseurl'] . '/secure-cvs';
                return $param;
            }
        }
        add_filter('upload_dir', 'kg_secure_upload_directory_manual');

        $upload_overrides = array('test_form' => false);
        $movefile = wp_handle_upload($uploadedfile, $upload_overrides);
        
        remove_filter('upload_dir', 'kg_secure_upload_directory_manual');

        if ($movefile && !isset($movefile['error'])) {
            update_post_meta($post_id, 'kg_app_cv_url', $movefile['url']);
        }
    }

    // Retrieve input interview fields first to validate them
    $new_int_date = isset($_POST['kg_interview_date']) ? sanitize_text_field($_POST['kg_interview_date']) : '';
    $new_int_time = isset($_POST['kg_interview_time']) ? sanitize_text_field($_POST['kg_interview_time']) : '';
    $new_int_format = isset($_POST['kg_interview_format']) ? sanitize_text_field($_POST['kg_interview_format']) : '';
    $new_int_details = isset($_POST['kg_interview_details']) ? sanitize_textarea_field($_POST['kg_interview_details']) : '';
    $new_int_er_id = isset($_POST['kg_interviewer_id']) ? sanitize_text_field($_POST['kg_interviewer_id']) : '';

    $status_order = array(
        'pooling' => 0,
        'screening' => 1,
        'processing' => 2,
        'interviewing' => 3,
        'hired' => 4,
        'deployed' => 5
    );

    // 1. Status progression lock (only from interviewing onwards, unless rejecting)
    if (in_array($old_status, array('interviewing', 'processing', 'hired', 'deployed'), true) && $new_status !== 'rejected') {
        if (isset($status_order[$old_status]) && isset($status_order[$new_status])) {
            if ($status_order[$new_status] < $status_order[$old_status]) {
                $new_status = $old_status;
                set_transient('kg_app_error_' . $post_id, 'Status cannot be reverted backward once it reaches the Interviewing stage.', 30);
            }
        }
    }

    // 1b. Validate recruiter assignment when moving past pooling
    $new_recruiter_id = isset($_POST['kg_app_recruiter_id']) ? sanitize_text_field($_POST['kg_app_recruiter_id']) : get_post_meta($post_id, 'kg_app_recruiter_id', true);
    if ($new_status !== 'pooling' && $new_status !== 'screening' && $new_status !== 'rejected' && empty($new_recruiter_id)) {
        $new_status = 'pooling';
        set_transient('kg_app_error_' . $post_id, 'You must assign a recruiter to move the applicant past the Screening stage.', 30);
    }

    // 2. Interviewing validation (requires schedule details)
    if ($new_status === 'interviewing') {
        if (empty($new_int_date) || empty($new_int_time) || empty($new_int_format) || empty($new_int_details) || empty($new_int_er_id)) {
            $new_status = ($old_status !== 'interviewing') ? $old_status : 'screening';
            set_transient('kg_app_error_' . $post_id, 'To set status to Interviewing, you must fill out the Date, Time, Format, Meeting Link/Address, and Interviewer recruiter first.', 30);
        }
    }

    // 2b. Processing validation
    $new_target_deploy_date = isset($_POST['kg_app_target_deploy_date']) ? sanitize_text_field($_POST['kg_app_target_deploy_date']) : '';
    $new_submission_date = isset($_POST['kg_app_submission_date']) ? sanitize_text_field($_POST['kg_app_submission_date']) : '';
    if ($new_status === 'processing') {
        if (empty($new_target_deploy_date) || empty($new_submission_date)) {
            $new_status = ($old_status !== 'processing') ? $old_status : 'interviewing';
            set_transient('kg_app_error_' . $post_id, 'To set status to Processing, you must specify the Target Date of Deployment and Deadline of Submission of Requirements.', 30);
        }
    }

    // 3. Deployed validation (requires deployment start date)
    $new_deploy_date = isset($_POST['kg_app_deploy_date']) ? sanitize_text_field($_POST['kg_app_deploy_date']) : '';
    if ($new_status === 'deployed' && empty($new_deploy_date)) {
        $new_status = ($old_status !== 'deployed') ? $old_status : 'screening';
        set_transient('kg_app_error_' . $post_id, 'To set status to Deployed, you must specify a Deployment Start Date first.', 30);
    }

    update_post_meta($post_id, 'kg_app_status', $new_status);
    if ($new_status === 'screening' && $old_status !== 'screening') {
        update_post_meta($post_id, 'kg_app_screening_start_date', current_time('timestamp'));
    }

    // Automatically unlock when moved to Pooling, lock otherwise if job is assigned
    // ONLY if the application originated from a direct job apply
    $was_direct = get_post_meta($post_id, 'kg_app_applied_via_single', true);
    $has_locked_meta = metadata_exists('post', $post_id, 'kg_app_locked_job');
    
    if ($was_direct === 'yes' || $has_locked_meta) {
        if ($new_status === 'pooling') {
            update_post_meta($post_id, 'kg_app_locked_job', 'no');
        } else {
            $assigned_job_id = isset($_POST['kg_app_job_id']) ? intval($_POST['kg_app_job_id']) : get_post_meta($post_id, 'kg_app_job_id', true);
            if ($assigned_job_id > 0) {
                update_post_meta($post_id, 'kg_app_locked_job', 'yes');
            }
        }
    }

    if (isset($_POST['kg_app_client'])) {
        update_post_meta($post_id, 'kg_app_client', sanitize_text_field($_POST['kg_app_client']));
    }

    if (isset($_POST['kg_app_job_id'])) {
        $job_id = intval($_POST['kg_app_job_id']);
        update_post_meta($post_id, 'kg_app_job_id', $job_id);
        if ($job_id > 0) {
            $job_post = get_post($job_id);
            if ($job_post) {
                update_post_meta($post_id, 'kg_app_role', $job_post->post_title);
            }
        } elseif (isset($_POST['kg_app_role_legacy']) && !empty($_POST['kg_app_role_legacy'])) {
            // Keep legacy role if job_id is empty but they had a legacy role
            update_post_meta($post_id, 'kg_app_role', sanitize_text_field($_POST['kg_app_role_legacy']));
        } elseif (isset($_POST['kg_app_preferred_roles_arr']) && is_array($_POST['kg_app_preferred_roles_arr'])) {
            // Fallback: Use the first preferred role as the primary role for manual entries
            $roles_arr = array_filter(array_map('sanitize_text_field', $_POST['kg_app_preferred_roles_arr']));
            if (!empty($roles_arr)) {
                update_post_meta($post_id, 'kg_app_role', reset($roles_arr));
            }
        }
    }

    if (isset($_POST['kg_app_deploy_date'])) {
        update_post_meta($post_id, 'kg_app_deploy_date', sanitize_text_field($_POST['kg_app_deploy_date']));
    }

    if (isset($_POST['kg_app_target_deploy_date'])) {
        update_post_meta($post_id, 'kg_app_target_deploy_date', sanitize_text_field($_POST['kg_app_target_deploy_date']));
    }

    if (isset($_POST['kg_app_submission_date'])) {
        update_post_meta($post_id, 'kg_app_submission_date', sanitize_text_field($_POST['kg_app_submission_date']));
    }

    if ((current_user_can('manage_options') || kg_is_current_user_recruitment_admin() || kg_is_current_user_recruiter()) && isset($_POST['kg_app_recruiter_id'])) {
        $old_rec_user_id = get_post_meta($post_id, 'kg_app_recruiter_id', true);
        $rec_user_id = sanitize_text_field($_POST['kg_app_recruiter_id']);

        if ($old_rec_user_id != $rec_user_id) {
            update_post_meta($post_id, 'kg_app_recruiter_id', $rec_user_id);

            $old_name = 'Unassigned';
            if ($old_rec_user_id) {
                $old_user = get_userdata($old_rec_user_id);
                if ($old_user) {
                    $old_name = $old_user->display_name;
                }
            }

            $new_name = 'Unassigned';
            if ($rec_user_id) {
                $new_user = get_userdata($rec_user_id);
                if ($new_user) {
                    $new_name = $new_user->display_name;
                }
            }

            $action = 'Reassigned from ' . $old_name . ' to ' . $new_name;
            if (!$old_rec_user_id && $rec_user_id) {
                $action = 'Assigned to ' . $new_name;
            } elseif ($old_rec_user_id && !$rec_user_id) {
                $action = 'Unassigned (previously ' . $old_name . ')';
            }

            kg_log_application_audit_trail($post_id, $action, $rec_user_id);
        }
    }

    // Save interview fields
    $old_int_date = get_post_meta($post_id, 'kg_interview_date', true);
    $old_int_time = get_post_meta($post_id, 'kg_interview_time', true);

    $new_int_date = isset($_POST['kg_interview_date']) ? sanitize_text_field($_POST['kg_interview_date']) : '';
    $new_int_time = isset($_POST['kg_interview_time']) ? sanitize_text_field($_POST['kg_interview_time']) : '';
    $new_int_format = isset($_POST['kg_interview_format']) ? sanitize_text_field($_POST['kg_interview_format']) : '';
    $new_int_details = isset($_POST['kg_interview_details']) ? sanitize_textarea_field($_POST['kg_interview_details']) : '';
    $new_int_er_id = isset($_POST['kg_interviewer_id']) ? sanitize_text_field($_POST['kg_interviewer_id']) : '';

    update_post_meta($post_id, 'kg_interview_date', $new_int_date);
    update_post_meta($post_id, 'kg_interview_time', $new_int_time);
    update_post_meta($post_id, 'kg_interview_format', $new_int_format);
    update_post_meta($post_id, 'kg_interview_details', $new_int_details);
    update_post_meta($post_id, 'kg_interviewer_id', $new_int_er_id);

    // If interview date/time changes, send notification email
    if (!empty($new_int_date) && ($new_int_date !== $old_int_date || $new_int_time !== $old_int_time)) {
        // Trigger scheduler email notifier helper
        if (function_exists('kg_send_interview_invitation_email')) {
            kg_send_interview_invitation_email($post_id);
        }
    }

    // Trigger processing email if status changed to processing
    if ($new_status === 'processing' && $old_status !== 'processing') {
        if (function_exists('kg_send_processing_email')) {
            kg_send_processing_email($post_id);
        }
    }

    // Handle adding a new note
    if (!empty($_POST['kg_add_note'])) {
        $new_note_msg = sanitize_textarea_field($_POST['kg_add_note']);
        $current_user = wp_get_current_user();
        $author_name = $current_user->display_name ?: $current_user->user_login;

        $notes = get_post_meta($post_id, 'kg_app_notes', true);
        if (!is_array($notes)) {
            $notes = array();
        }

        $notes[] = array(
            'author' => $author_name,
            'timestamp' => current_time('timestamp'),
            'message' => $new_note_msg,
        );

        update_post_meta($post_id, 'kg_app_notes', $notes);
    }

    /* — Email applicant when status changes — */
    if ($new_status !== $old_status && !$is_new_application) {
        kg_notify_applicant_status($post_id, $new_status);
        kg_notify_recruiter_status_change($post_id, $old_status, $new_status);
    }

    $is_manual_new = get_post_meta($post_id, '_kg_manual_new_email_sent', true) ? false : true;
    if ($is_manual_new && get_post_status($post_id) === 'publish') {
        update_post_meta($post_id, '_kg_manual_new_email_sent', 'yes');
        if (function_exists('kg_send_manual_new_application_emails')) {
            kg_send_manual_new_application_emails($post_id);
        }
    }
}

function kg_send_manual_new_application_emails($post_id) {
    require_once get_template_directory() . '/inc/email-templates.php';
    if (!function_exists('kg_send_mail')) {
        require_once get_template_directory() . '/inc/form-handlers.php';
    }

    $fullname = get_the_title($post_id);
    $email = get_post_meta($post_id, 'kg_app_email', true);
    $phone = get_post_meta($post_id, 'kg_app_phone', true);
    $preferred_roles = get_post_meta($post_id, 'kg_app_preferred_roles', true);
    $role = get_post_meta($post_id, 'kg_app_role', true);
    
    $download_url = add_query_arg('kg_download_cv', $post_id, home_url('/'));
    
    $to_email = defined('KG_CAREER_EMAIL') ? KG_CAREER_EMAIL : (defined('KG_ADMIN_EMAIL') ? KG_ADMIN_EMAIL : 'hr@kingsgroup.com.ph');
    
    $recruiter_email = '';
    $rec_id = get_post_meta($post_id, 'kg_app_recruiter_id', true);
    if ($rec_id) {
        $rec_user = get_userdata($rec_id);
        if ($rec_user) {
            $recruiter_email = $rec_user->user_email;
        }
    }

    $mail_recipient = array($to_email);
    if (!empty($recruiter_email) && $recruiter_email !== $to_email) {
        $mail_recipient[] = $recruiter_email;
    }
    if (function_exists('kg_get_hr_emails')) {
        $mail_recipient = array_merge($mail_recipient, kg_get_hr_emails());
        $mail_recipient = array_unique($mail_recipient);
    }
    
    $edit_url = get_edit_post_link($post_id);
    
    $submission_details = '<div style="border:1px solid #e8ecf0;border-radius:8px;padding:20px;margin-bottom:24px;background:#ffffff;">'
        . kg_email_row('Full Name', $fullname)
        . kg_email_row('Email', '<a href="mailto:' . esc_attr($email) . '" style="color:#0A2540;">' . esc_html($email) . '</a>')
        . kg_email_row('Phone', $phone ?: '—')
        . kg_email_row('Preferred Roles', !empty($preferred_roles) ? implode(', ', $preferred_roles) : ($role ?: 'Not specified'))
        . kg_email_row('CV File', '<a href="' . esc_url($download_url) . '" style="color:#00D09C;font-weight:600;">Download CV (Secure)</a>')
        . '</div>';

    $parsed_admin = kg_get_parsed_email('admin_submission', array(
        '{applicant_name}' => $fullname,
        '{submission_details}' => $submission_details,
        '{edit_url}' => $edit_url,
    ));

    $admin_subject = $parsed_admin ? $parsed_admin['subject'] : 'New Application: ' . $fullname . ($role ? ' — ' . $role : '');
    $admin_body = kg_email_heading($parsed_admin ? $parsed_admin['heading'] : 'Applicant Application Notification') . ($parsed_admin ? $parsed_admin['body'] : '');
    if ($parsed_admin && !empty($parsed_admin['banner'])) {
        $admin_body .= kg_email_banner($parsed_admin['banner']);
    }
    if ($parsed_admin && !empty($parsed_admin['btn_text']) && !empty($parsed_admin['btn_link'])) {
        $admin_body .= kg_email_button($parsed_admin['btn_text'], $parsed_admin['btn_link']);
    }

    $headers = array('Content-Type: text/html; charset=UTF-8');
    if (!empty($email)) {
        $headers[] = 'Reply-To: ' . $fullname . ' <' . $email . '>';
    }

    $attachments = array();
    $cv_url = get_post_meta($post_id, 'kg_app_cv_url', true);
    if ($cv_url) {
        $upload_dir = wp_upload_dir();
        $cv_path = str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $cv_url);
        if (file_exists($cv_path)) {
            $attachments[] = $cv_path;
        }
    }

    kg_send_mail(
        $mail_recipient,
        $admin_subject,
        kg_email_wrap('New CV Application', $admin_body),
        $headers,
        $attachments
    );

    // Initial status email to applicant
    $initial_status = get_post_meta($post_id, 'kg_app_status', true) ?: 'pooling';
    kg_notify_applicant_status($post_id, $initial_status);
}
add_action('save_post_kg_application', 'kg_save_application_status');

/**
 * Auto-sync Dashboard headcount when an application is marked deployed.
 * Counts all deployed applications that match the same role,
 * then updates job_filled_headcount on the related Job post.
 */
function kg_sync_job_headcount_on_status_change($post_id)
{
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;
    if (get_post_type($post_id) !== 'kg_application')
        return;

    $job_id = get_post_meta($post_id, 'kg_app_job_id', true);
    if (!$job_id) {
        // Fallback for legacy applications
        $role = get_post_meta($post_id, 'kg_app_role', true);
        if (!$role)
            return;

        $jobs = get_posts(array(
            'post_type' => 'jobs',
            'post_status' => array('publish', 'draft'),
            'posts_per_page' => 1,
            'title' => $role,
            'fields' => 'ids',
        ));
        if (empty($jobs))
            return;
        $job_id = $jobs[0];
    }

    // Count all deployed applications for this specific job ID
    // (Legacy deployed applicants won't be counted here unless they are updated with a job_id, 
    // ensuring accurate per-location counts moving forward)
    $filled = new WP_Query(array(
        'post_type' => 'kg_application',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'fields' => 'ids',
        'meta_query' => array(
            'relation' => 'AND',
            array('key' => 'kg_app_job_id', 'value' => $job_id),
            array('key' => 'kg_app_status', 'value' => 'deployed'),
        ),
    ));

    update_post_meta($job_id, 'job_filled_headcount', $filled->found_posts);

    if (function_exists('kg_auto_draft_if_headcount_reached')) {
        kg_auto_draft_if_headcount_reached($job_id);
    }
}
add_action('save_post_kg_application', 'kg_sync_job_headcount_on_status_change', 20);

/**
 * Sends a status update email to the assigned recruiter.
 */
function kg_notify_recruiter_status_change($post_id, $old_status, $new_status)
{
    require_once get_template_directory() . '/inc/email-templates.php';

    $applicant_name = get_the_title($post_id);
    $role = get_post_meta($post_id, 'kg_app_role', true) ?: 'Unassigned Role';
    $statuses = kg_ats_statuses();
    $old_lbl = $statuses[$old_status] ?? $old_status;
    $new_lbl = $statuses[$new_status] ?? $new_status;
    $edit_url = get_edit_post_link($post_id);
    $subject = 'Applicant Status Change: ' . $applicant_name . ' — Kings Manpower';

    $recipients = array();

    // 1. Notify Admin for ALL status changes
    $admin_email = defined('KG_CAREER_EMAIL') ? KG_CAREER_EMAIL : (defined('KG_ADMIN_EMAIL') ? KG_ADMIN_EMAIL : get_option('admin_email'));
    if (!empty($admin_email)) {
        $recipients[] = array('email' => $admin_email, 'name' => 'Administrator');
    }

    // 2. Notify ALL HR and Recruitment Admins
    if (function_exists('get_users')) {
        $hr_and_admins = get_users(array('role__in' => array('hr', 'recruitment_admin')));
        foreach ($hr_and_admins as $hr_user) {
            if (is_email($hr_user->user_email)) {
                $hr_name = $hr_user->display_name ?: $hr_user->user_login;
                $recipients[] = array('email' => $hr_user->user_email, 'name' => $hr_name);
            }
        }
    }

    // 3. Notify assigned recruiter (for any status change)
    $rec_id = get_post_meta($post_id, 'kg_app_recruiter_id', true);
    if ($rec_id) {
        $rec_user = get_userdata($rec_id);
        if ($rec_user && !empty($rec_user->user_email)) {
            $rec_name = $rec_user->display_name ?: $rec_user->user_login;
            $recipients[] = array('email' => $rec_user->user_email, 'name' => $rec_name);
        }
    }

    if (empty($recipients)) {
        return;
    }

    foreach ($recipients as $recipient) {
        $email = $recipient['email'];
        $name = $recipient['name'];
        $status_details_html = '<div style="border:1px solid #e8ecf0;border-radius:8px;padding:20px;margin-bottom:24px;background:#ffffff;">'
            . kg_email_row('Applicant Name', $applicant_name)
            . kg_email_row('Job Role', $role)
            . kg_email_row('Previous Status', $old_lbl)
            . kg_row_styled_status('New Status', $new_lbl, $new_status)
            . '</div>';

        $replacements = array(
            '{name}' => $name,
            '{applicant_name}' => $applicant_name,
            '{role}' => $role,
            '{old_status}' => $old_lbl,
            '{new_status}' => $new_lbl,
            '{edit_url}' => $edit_url,
            '{status_change_details}' => $status_details_html
        );

        $parsed = kg_get_parsed_email('recruiter_change', $replacements);

        $body = kg_email_heading($parsed['heading'])
            . $parsed['body'];

        if (!empty($parsed['banner'])) {
            $body .= kg_email_banner($parsed['banner']);
        }
        if (!empty($parsed['btn_text']) && !empty($parsed['btn_link'])) {
            $body .= kg_email_button($parsed['btn_text'], $parsed['btn_link']);
        }

        wp_mail(
            $email,
            $parsed['subject'],
            kg_email_wrap($parsed['subject'], $body, $name, '', date_i18n(get_option('date_format'))),
            array('Content-Type: text/html; charset=UTF-8')
        );
    }
}

/**
 * Helper to render styled status cell in email
 */
function kg_row_styled_status($label, $value, $status)
{
    $status_styles = array(
        'pooling' => 'background:#fef3c7;color:#92400e;padding:4px 8px;border-radius:4px;font-weight:bold;font-size:12px;',
        'screening' => 'background:#dbeafe;color:#1e40af;padding:4px 8px;border-radius:4px;font-weight:bold;font-size:12px;',
        'interviewing' => 'background:#ede9fe;color:#6d28d9;padding:4px 8px;border-radius:4px;font-weight:bold;font-size:12px;',
        'hired' => 'background:#d1fae5;color:#065f46;padding:4px 8px;border-radius:4px;font-weight:bold;font-size:12px;',
        'deployed' => 'background:#dcfce7;color:#15803d;padding:4px 8px;border-radius:4px;font-weight:bold;font-size:12px;',
        'rejected' => 'background:#fee2e2;color:#991b1b;padding:4px 8px;border-radius:4px;font-weight:bold;font-size:12px;',
    );
    $style = $status_styles[$status] ?? 'background:#f3f4f6;color:#374151;padding:4px 8px;border-radius:4px;font-weight:bold;font-size:12px;';

    $styled_value = '<span style="' . $style . '">' . esc_html($value) . '</span>';
    return kg_email_row($label, $styled_value);
}

/**
 * Sends a branded status-update email to the applicant.
 */
function kg_notify_applicant_status($post_id, $status)
{
    require_once get_template_directory() . '/inc/email-templates.php';

    $fname = explode(' ', get_the_title($post_id))[0];
    $fullname = get_the_title($post_id);
    $email = get_post_meta($post_id, 'kg_app_email', true);
    $role = get_post_meta($post_id, 'kg_app_role', true) ?: 'the position';

    if (!$email)
        return;

    $street = get_post_meta($post_id, 'kg_app_street', true);
    $barangay = get_post_meta($post_id, 'kg_app_barangay', true);
    $city = get_post_meta($post_id, 'kg_app_city', true);
    $region = get_post_meta($post_id, 'kg_app_region', true);

    $address_parts = array();
    if ($street)
        $address_parts[] = $street;
    if ($barangay)
        $address_parts[] = 'Brgy. ' . $barangay;
    if ($city)
        $address_parts[] = $city;
    if ($region)
        $address_parts[] = $region;
    $address = implode(', ', $address_parts);

    $date = date_i18n(get_option('date_format'));

    $preferred_roles_list = get_post_meta($post_id, 'kg_app_preferred_roles', true) ?: array();
    $roles_display = !empty($preferred_roles_list) ? implode(', ', $preferred_roles_list) : $role;

    $deploy_date_raw = get_post_meta($post_id, 'kg_app_deploy_date', true);
    $deploy_date = $deploy_date_raw ? date_i18n(get_option('date_format'), strtotime($deploy_date_raw)) : 'To be finalized';
    $deploy_rec_id = get_post_meta($post_id, 'kg_app_recruiter_id', true);
    $deploy_recruiter = 'Kings Recruitment Team';
    if ($deploy_rec_id) {
        $deploy_rec_user = get_userdata($deploy_rec_id);
        if ($deploy_rec_user) {
            $deploy_recruiter = $deploy_rec_user->display_name;
        }
    }

    $deployment_details_html = '<div style="border:1px solid #e8ecf0;border-radius:8px;padding:20px;margin-bottom:24px;background:#ffffff;">'
        . kg_email_row('Job Role', $role)
        . kg_email_row('Recruiter', $deploy_recruiter)
        . kg_email_row('Start Date', $deploy_date)
        . '</div>';

    $replacements = array(
        '{fname}' => $fname,
        '{fullname}' => $fullname,
        '{role}' => $role,
        '{roles}' => $roles_display,
        '{deployment_details}' => $deployment_details_html
    );

    $parsed = array();

    switch ($status) {
        case 'screening':
            $parsed = kg_get_parsed_email('screening', $replacements);
            break;

        case 'interviewing':
            if (function_exists('kg_send_interview_invitation_email')) {
                kg_send_interview_invitation_email($post_id);
            }
            return;

        case 'processing':
            return; // Handled directly in save hook via kg_send_processing_email()

        case 'hired':
        case 'accepted':
            $parsed = kg_get_parsed_email('hired', $replacements);
            break;

        case 'deployed':
            $parsed = kg_get_parsed_email('deployed', $replacements);
            break;

        case 'pooling':
            $parsed = kg_get_parsed_email('pooling', $replacements);
            break;

        case 'rejected':
        default:
            $parsed = kg_get_parsed_email('rejected', $replacements);
            break;
    }

    if (!empty($parsed)) {
        $subject = $parsed['subject'];
        $body = kg_email_heading($parsed['heading'])
            . $parsed['body'];

        if (!empty($parsed['banner'])) {
            $body .= kg_email_banner($parsed['banner']);
        }
        if (!empty($parsed['btn_text']) && !empty($parsed['btn_link'])) {
            $body .= kg_email_button($parsed['btn_text'], $parsed['btn_link']);
        }

        wp_mail(
            $email,
            $subject,
            kg_email_wrap($subject, $body, $fullname, $address, $date),
            array('Content-Type: text/html; charset=UTF-8')
        );
    }
}

/* ─────────────────────────────────────────────
   Filter by status in admin list
───────────────────────────────────────────── */

function kg_application_status_filter($post_type)
{
    if ($post_type !== 'kg_application')
        return;

    $current = $_GET['kg_status_filter'] ?? '';
    ?>
    <select name="kg_status_filter">
        <option value="">All Statuses</option>
        <?php foreach (kg_ats_statuses() as $val => $lbl): ?>
            <option value="<?php echo esc_attr($val); ?>" <?php selected($current, $val); ?>><?php echo esc_html($lbl); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <?php
}
add_action('restrict_manage_posts', 'kg_application_status_filter');

function kg_application_status_filter_query($query)
{
    global $pagenow;
    if (
        is_admin() &&
        $pagenow === 'edit.php' &&
        ($_GET['post_type'] ?? '') === 'kg_application' &&
        !empty($_GET['kg_status_filter'])
    ) {
        $allowed = array_keys(kg_ats_statuses());
        $filter = $_GET['kg_status_filter'];
        if (in_array($filter, $allowed, true)) {
            $query->set('meta_query', array(
                array(
                    'key' => 'kg_app_status',
                    'value' => $filter,
                ),
            ));
        }
    }
}
add_action('pre_get_posts', 'kg_application_status_filter_query');

/* ─────────────────────────────────────────────
   AJAX: inline status change from list view
───────────────────────────────────────────── */

function kg_ajax_inline_status()
{
    $post_id = absint($_POST['post_id'] ?? 0);
    $new_status = sanitize_text_field($_POST['status'] ?? '');
    $nonce = $_POST['nonce'] ?? '';

    if (!wp_verify_nonce($nonce, 'kg_inline_status_' . $post_id)) {
        wp_send_json_error('Security check failed.');
    }
    if (!current_user_can('edit_post', $post_id)) {
        wp_send_json_error('Permission denied.');
    }

    $allowed = array_keys(kg_ats_statuses());
    if (!in_array($new_status, $allowed, true)) {
        wp_send_json_error('Invalid status.');
    }

    $old_status = get_post_meta($post_id, 'kg_app_status', true) ?: 'screening';

    $status_order = array(
        'pooling' => 0,
        'screening' => 1,
        'interviewing' => 2,
        'hired' => 3,
        'deployed' => 4
    );

    // 1. Status progression lock (only from interviewing onwards, unless rejecting)
    if (in_array($old_status, array('interviewing', 'hired', 'deployed'), true) && $new_status !== 'rejected') {
        if (isset($status_order[$old_status]) && isset($status_order[$new_status])) {
            if ($status_order[$new_status] < $status_order[$old_status]) {
                wp_send_json_error('Status cannot be reverted backward once it reaches the Interviewing stage.');
            }
        }
    }

    // 1b. Recruiter assignment check
    $rec_id = get_post_meta($post_id, 'kg_app_recruiter_id', true);
    if ($new_status !== 'pooling' && $new_status !== 'rejected' && empty($rec_id)) {
        wp_send_json_error('You must assign a recruiter in the applicant profile before moving past the Pooling stage.');
    }

    // 2. Interviewing validation (requires schedule details)
    if ($new_status === 'interviewing') {
        $int_date = get_post_meta($post_id, 'kg_interview_date', true);
        $int_time = get_post_meta($post_id, 'kg_interview_time', true);
        $int_format = get_post_meta($post_id, 'kg_interview_format', true);
        $int_details = get_post_meta($post_id, 'kg_interview_details', true);
        $int_er_id = get_post_meta($post_id, 'kg_interviewer_id', true);

        if (empty($int_date) || empty($int_time) || empty($int_format) || empty($int_details) || empty($int_er_id)) {
            wp_send_json_error('To set status to Interviewing, you must fill out the Date, Time, Format, Meeting Link/Address, and Interviewer recruiter first by editing the applicant profile.');
        }
    }

    // 3. Deployed validation (requires deployment start date)
    if ($new_status === 'deployed') {
        $deploy_date = get_post_meta($post_id, 'kg_app_deploy_date', true);
        if (empty($deploy_date)) {
            wp_send_json_error('To set status to Deployed, you must specify a Deployment Start Date by editing the applicant profile first.');
        }
    }

    update_post_meta($post_id, 'kg_app_status', $new_status);
    if ($new_status === 'screening' && $old_status !== 'screening') {
        update_post_meta($post_id, 'kg_app_screening_start_date', current_time('timestamp'));
    }

    if ($new_status !== $old_status) {
        kg_notify_applicant_status($post_id, $new_status);
    }

    wp_send_json_success(array('status' => $new_status));
}
add_action('wp_ajax_kg_inline_status', 'kg_ajax_inline_status');

/* ─────────────────────────────────────────────
   Bulk actions: Accept / Reject selected
───────────────────────────────────────────── */

function kg_application_bulk_actions($actions)
{
    $actions['kg_bulk_accept'] = 'Mark as Accepted';
    $actions['kg_bulk_reject'] = 'Mark as Rejected';
    return $actions;
}
add_filter('bulk_actions-edit-kg_application', 'kg_application_bulk_actions');

function kg_application_bulk_action_handler($redirect, $action, $post_ids)
{
    if (!in_array($action, array('kg_bulk_accept', 'kg_bulk_reject'), true)) {
        return $redirect;
    }
    $new_status = $action === 'kg_bulk_accept' ? 'hired' : 'rejected';

    foreach ($post_ids as $post_id) {
        $old_status = get_post_meta($post_id, 'kg_app_status', true) ?: 'screening';
        update_post_meta($post_id, 'kg_app_status', $new_status);
        if ($new_status !== $old_status) {
            kg_notify_applicant_status($post_id, $new_status);
        }
    }

    return add_query_arg('kg_bulk_done', count($post_ids), $redirect);
}
add_filter('handle_bulk_actions-edit-kg_application', 'kg_application_bulk_action_handler', 10, 3);

function kg_application_bulk_notice()
{
    if (!empty($_GET['kg_bulk_done'])) {
        $count = absint($_GET['kg_bulk_done']);
        echo '<div class="notice notice-success is-dismissible"><p>'
            . sprintf('%d application(s) updated and applicants notified by email.', $count)
            . '</p></div>';
    }
}
add_action('admin_notices', 'kg_application_bulk_notice');

/* ─────────────────────────────────────────────
   Admin JS: inline dropdown + hide Add New
───────────────────────────────────────────── */

function kg_application_admin_scripts()
{
    global $post_type, $pagenow;
    if ($pagenow !== 'edit.php' || $post_type !== 'kg_application')
        return;
    ?>
    <style>


        .kg-inline-status {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="none" viewBox="0 0 24 24" stroke="%234b5563" stroke-width="3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>');
            background-repeat: no-repeat;
            background-position: right 8px center;
            padding-right: 24px !important;
            border: 1px solid rgba(0, 0, 0, 0.06) !important;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
            transition: all 0.15s ease-in-out;
        }

        .kg-inline-status:hover {
            filter: brightness(0.95);
        }

        .kg-inline-status:focus {
            outline: 2px solid #2271b1;
            box-shadow: 0 0 0 2px rgba(34, 113, 177, 0.3);
        }

        .kg-status-saving {
            opacity: 0.5;
            pointer-events: none;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.kg-inline-status').forEach(function (select) {
                select.addEventListener('change', function () {
                    var postId = this.dataset.postId;
                    var nonce = this.dataset.nonce;
                    var status = this.value;
                    var el = this;

                    var originalStatus = this.dataset.originalStatus || 'screening';
                    var hasInterview = this.dataset.hasInterview === '1';
                    var hasDeployDate = this.dataset.hasDeployDate === '1';
                    var statusOrder = {
                        'pooling': 0,
                        'screening': 1,
                        'interviewing': 2,
                        'hired': 3,
                        'deployed': 4
                    };

                    // 1. Validate progression lock
                    if (['interviewing', 'hired', 'deployed'].indexOf(originalStatus) !== -1 && status !== 'rejected') {
                        if (statusOrder[status] < statusOrder[originalStatus]) {
                            alert('Warning: Status cannot be reverted backward once it reaches the Interviewing stage.');
                            el.value = originalStatus;
                            return;
                        }
                    }

                    // 2. Validate interviewing fields
                    if (status === 'interviewing' && !hasInterview) {
                        alert('Warning: To set status to Interviewing, you must fill out the Date, Time, Format, Meeting Link/Address, and Interviewer recruiter first by editing the applicant profile.');
                        el.value = originalStatus;
                        return;
                    }

                    // 3. Validate deployed fields
                    if (status === 'deployed' && !hasDeployDate) {
                        alert('Warning: To set status to Deployed, you must specify a Deployment Start Date by editing the applicant profile first.');
                        el.value = originalStatus;
                        return;
                    }

                    el.classList.add('kg-status-saving');

                    var body = new FormData();
                    body.append('action', 'kg_inline_status');
                    body.append('post_id', postId);
                    body.append('status', status);
                    body.append('nonce', nonce);

                    fetch(ajaxurl, { method: 'POST', body: body })
                        .then(function (r) { return r.json(); })
                        .then(function (data) {
                            if (data.success) {
                                var colors = {
                                    screening: 'background:#dbeafe;color:#1e40af;',
                                    interviewing: 'background:#ede9fe;color:#6d28d9;',
                                    hired: 'background:#d1fae5;color:#065f46;',
                                    deployed: 'background:#dcfce7;color:#15803d;',
                                    benched: 'background:#fef3c7;color:#92400e;',
                                    rejected: 'background:#fee2e2;color:#991b1b;',
                                };
                                el.style.cssText = 'padding:4px 8px;border-radius:6px;font-size:12px;font-weight:600;border:2px solid transparent;cursor:pointer;' + (colors[status] || '');
                            } else {
                                alert('Failed to update status. Please try again.');
                                location.reload();
                            }
                        })
                        .catch(function () { location.reload(); })
                        .finally(function () { el.classList.remove('kg-status-saving'); });
                });
            });
        });
    </script>
    <?php
}
add_action('admin_footer', 'kg_application_admin_scripts');

/**
 * Handles secure resume downloads for authenticated administrators and recruiters.
 */
function kg_handle_secure_cv_download()
{
    if (isset($_GET['kg_download_cv'])) {
        $post_id = absint($_GET['kg_download_cv']);
        if (!current_user_can('edit_posts')) {
            wp_die('Permission denied. You must be logged in as an administrator or recruiter to access candidate resumes.', 'Access Denied', array('response' => 403));
        }

        $cv_url = get_post_meta($post_id, 'kg_app_cv_url', true);
        if (!$cv_url) {
            wp_die('CV file not found.', 'Not Found', array('response' => 404));
        }

        $upload_dir = wp_upload_dir();
        $filename = basename($cv_url);
        $cv_path = $upload_dir['basedir'] . '/secure-cvs/' . $filename;

        if (!file_exists($cv_path)) {
            wp_die('CV file does not exist on disk.', 'Not Found', array('response' => 404));
        }

        $disposition = isset($_GET['inline']) ? 'inline' : 'attachment';
        header('Content-Description: File Transfer');
        header('Content-Type: ' . (function_exists('mime_content_type') ? mime_content_type($cv_path) : 'application/octet-stream'));
        header('Content-Disposition: ' . $disposition . '; filename="' . basename($cv_path) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($cv_path));
        readfile($cv_path);
        exit;
    }
}
add_action('init', 'kg_handle_secure_cv_download');

/**
 * Filter applications list in wp-admin for recruiters.
 */
function kg_filter_applications_for_recruiters($query)
{
    // Feature: Total Visibility. All recruiters can now see all applications.
    // The previous scoping restriction by 'kg_app_recruiter_id' has been removed
    // to allow recruiters to view and harvest any applicants.
}
add_action('pre_get_posts', 'kg_filter_applications_for_recruiters');

/**
 * Filter jobs list in wp-admin for recruiters.
 */
function kg_filter_jobs_for_recruiters($query)
{
    global $pagenow;

    if (is_admin() && $query->is_main_query() && $pagenow === 'edit.php' && $query->get('post_type') === 'jobs') {
        if (kg_is_current_user_recruiter() && !kg_is_current_user_recruitment_admin()) {
            $rec_id = get_current_user_id();
            $rec_locations = (array) get_user_meta($rec_id, 'kg_recruiter_location', true);
            $rec_locations = array_filter($rec_locations);

            // Recruiters see jobs in their assigned locations
            $tax_query = (array) $query->get('tax_query');
            if (!empty($rec_locations)) {
                $tax_query[] = array(
                    'taxonomy' => 'job_location_tax',
                    'field' => 'slug',
                    'terms' => $rec_locations,
                    'operator' => 'IN',
                );
            }
            $query->set('tax_query', $tax_query);

            // Also keep standard author assignment if they want to manage their own posts
            // But since we want them to see location-scoped jobs as well, we let them see all jobs matching the location scope.
        }
    }
}
add_action('pre_get_posts', 'kg_filter_jobs_for_recruiters');

/**
 * Phase 6: Bulk Recruiter Assignment
 */
function kg_register_bulk_actions_assign_recruiter($bulk_actions)
{
    if (current_user_can('manage_options') || kg_is_current_user_recruitment_admin()) {
        // Only allow admin to bulk-assign recruiters
        $bulk_actions['kg_bulk_assign_recruiter'] = 'Assign Recruiter';
    }
    return $bulk_actions;
}
// Removed Assign Recruiter per request
// add_filter( 'bulk_actions-edit-kg_application', 'kg_register_bulk_actions_assign_recruiter' );

function kg_handle_bulk_actions_assign_recruiter($redirect_to, $action, $post_ids)
{
    if ($action !== 'kg_bulk_assign_recruiter') {
        return $redirect_to;
    }

    // Since we need to let the admin select a recruiter, let's redirect to a intermediate confirm URL,
    // or just let them confirm via a query parameter.
    // For a simple UX, we will check if the user appended recruiter_id to the query.
    if (!current_user_can('manage_options') && !kg_is_current_user_recruitment_admin()) {
        return $redirect_to;
    }

    if (isset($_REQUEST['kg_bulk_recruiter_id'])) {
        $rec_id = (int) $_REQUEST['kg_bulk_recruiter_id'];
        foreach ($post_ids as $post_id) {
            update_post_meta($post_id, 'kg_app_recruiter_id', $rec_id);
        }
        $redirect_to = add_query_arg('kg_bulk_assigned', count($post_ids), $redirect_to);
    } else {
        // Redirect to selection screen/action
        $redirect_to = add_query_arg(array(
            'kg_trigger_bulk_assign' => '1',
            'ids' => implode(',', $post_ids)
        ), $redirect_to);
    }

    return $redirect_to;
}
add_filter('handle_bulk_actions-edit-kg_application', 'kg_handle_bulk_actions_assign_recruiter', 10, 3);

// Render selection notice / interface when bulk assign is triggered
function kg_bulk_assign_admin_notices()
{
    global $pagenow;
    if ($pagenow === 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] === 'kg_application') {
        if (isset($_GET['kg_trigger_bulk_assign']) && !empty($_GET['ids'])) {
            $ids = sanitize_text_field($_GET['ids']);
            $recruiters = get_users(array('role__in' => array('recruiter')));
            ?>
            <div class="notice notice-info is-dismissible" style="padding:15px;">
                <h4 style="margin:0 0 10px 0;">Bulk Assign Recruiter</h4>
                <p>Select a recruiter to assign to the selected applicants:</p>
                <form method="post" action="">
                    <?php wp_nonce_field('kg_bulk_assign_nonce'); ?>
                    <input type="hidden" name="kg_action" value="execute_bulk_assign" />
                    <input type="hidden" name="post_ids" value="<?php echo esc_attr($ids); ?>" />
                    <select name="kg_bulk_recruiter_id" style="vertical-align:middle; margin-right:10px;">
                        <option value="">— Unassign —</option>
                        <?php foreach ($recruiters as $rec): ?>
                            <option value="<?php echo esc_attr($rec->ID); ?>"><?php echo esc_html($rec->display_name); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="submit" class="button button-primary" value="Confirm Assignment" />
                    <a href="<?php echo esc_url(remove_query_arg(array('kg_trigger_bulk_assign', 'ids'))); ?>"
                        class="button button-link">Cancel</a>
                </form>
            </div>
            <?php
        }

        if (isset($_GET['kg_bulk_assigned'])) {
            $count = (int) $_GET['kg_bulk_assigned'];
            printf('<div class="notice notice-success is-dismissible"><p>' . _n('Successfully assigned %s recruiter to applicant.', 'Successfully assigned %s recruiter to applicants.', $count, 'kingsgroup') . '</p></div>', $count);
        }
    }
}
add_action('admin_notices', 'kg_bulk_assign_admin_notices');

// Handle the post submit of the bulk selection form
function kg_handle_bulk_assign_form_submit()
{
    if (is_admin() && isset($_POST['kg_action']) && $_POST['kg_action'] === 'execute_bulk_assign') {
        check_admin_referer('kg_bulk_assign_nonce');
        if (!current_user_can('manage_options') && !kg_is_current_user_recruitment_admin()) {
            wp_die('Unauthorized');
        }
        $post_ids = isset($_POST['post_ids']) ? explode(',', sanitize_text_field($_POST['post_ids'])) : array();
        $rec_id = isset($_POST['kg_bulk_recruiter_id']) ? (int) $_POST['kg_bulk_recruiter_id'] : 0;

        foreach ($post_ids as $post_id) {
            $old_rec_id = get_post_meta($post_id, 'kg_app_recruiter_id', true);
            update_post_meta($post_id, 'kg_app_recruiter_id', $rec_id ? $rec_id : '');

            if ($old_rec_id != $rec_id) {
                $action = 'Bulk Assigned to Recruiter';
                if (!$rec_id)
                    $action = 'Bulk Unassigned';
                kg_log_application_audit_trail($post_id, $action, $rec_id);
            }
        }

        $redirect = remove_query_arg(array('kg_trigger_bulk_assign', 'ids'));
        $redirect = add_query_arg('kg_bulk_assigned', count($post_ids), $redirect);
        wp_safe_redirect($redirect);
        exit;
    }
}
add_action('admin_init', 'kg_handle_bulk_assign_form_submit');

function kg_application_admin_notices()
{
    global $post;
    if ($post && get_post_type($post->ID) === 'kg_application') {
        $error = get_transient('kg_app_error_' . $post->ID);
        if ($error) {
            delete_transient('kg_app_error_' . $post->ID);
            echo '<div class="notice notice-error is-dismissible"><p><strong>Error:</strong> ' . esc_html($error) . '</p></div>';
        }
    }
}
add_action('admin_notices', 'kg_application_admin_notices');

/**
 * Add Email Templates settings page as a top-level menu item
 */
function kg_register_email_templates_settings_page()
{
    add_menu_page(
        'Email Templates',
        'Email Templates',
        'manage_options',
        'kg-email-templates',
        'kg_email_templates_settings_render',
        'dashicons-email-alt',
        25 // Position below CPTs
    );
}
add_action('admin_menu', 'kg_register_email_templates_settings_page');

/**
 * Enqueue WordPress Media Library scripts on the Email Templates settings page
 */
function kg_email_templates_admin_scripts($hook)
{
    if (isset($_GET['page']) && $_GET['page'] === 'kg-email-templates') {
        wp_enqueue_media();
    }
}
add_action('admin_enqueue_scripts', 'kg_email_templates_admin_scripts');

function kg_email_templates_settings_render()
{
    if (!current_user_can('manage_options')) {
        wp_die('Unauthorized');
    }

    $templates_list = array(
        'pooling' => array(
            'label' => 'Pooling Status Email (Applicant)',
            'desc' => 'Sent to the applicant when their application is placed in the Talent Pool.',
            'fallback_subj' => 'Application Received: Talent Pool — Kings Manpower',
            'fallback_body' => "Dear {fname},\n\nThank you for submitting your CV to Kings Manpower. Your profile has been added to our active talent pool.\n\nWe will review your qualifications and match you with suitable opportunities as they become available. There is no further action required from you at this time.",
            'fallback_banner' => 'We will reach out as soon as a position aligns with your skills and experience.',
            'fallback_btn_text' => 'View Open Positions',
            'fallback_btn_link' => '{site_url}/our-jobs/',
            'tokens' => array('{fname}', '{fullname}', '{role}', '{site_url}')
        ),
        'screening' => array(
            'label' => 'Screening Status Email (Applicant)',
            'desc' => 'Sent to the applicant when their application is put Under Review.',
            'fallback_subj' => 'Application Status: Screening — Kings Manpower',
            'fallback_body' => "Dear {fname},\n\nWe have received your application for the following position(s): <strong>{roles}</strong>. Your application is currently being screened by our recruiting team.\n\nOur team is carefully reviewing your credentials and experience. We will be in touch with you soon regarding the next steps in our hiring process.",
            'fallback_banner' => 'Your applicant profile is actively under review. No further action is required from you at this stage.',
            'fallback_btn_text' => 'Visit Kings Manpower',
            'fallback_btn_link' => '{site_url}',
            'tokens' => array('{fname}', '{fullname}', '{roles}', '{site_url}')
        ),
        'processing' => array(
            'label' => 'Processing Status Email (Applicant)',
            'desc' => 'Sent to the applicant when their application is put into Processing.',
            'fallback_subj' => 'Application Processing: Action Required — Kings Manpower',
            'fallback_body' => "Dear {fname},\n\nCongratulations! Your application for <strong>{role}</strong> is now being processed.\n\nPlease review your specific requirements and important dates below. Ensure all your requirements are submitted on or before the deadline.\n\n{processing_details}",
            'fallback_banner' => 'Submit all requirements on or before the deadline to avoid delays in your deployment.',
            'fallback_btn_text' => 'Visit Kings Manpower',
            'fallback_btn_link' => '{site_url}',
            'tokens' => array('{fname}', '{fullname}', '{role}', '{processing_details}', '{site_url}')
        ),
        'interviewing_online' => array(
            'label' => 'Online Interview Email (Applicant)',
            'desc' => 'Sent to the applicant when an Online interview is scheduled.',
            'fallback_subj' => 'Online Interview Scheduled: {role} — Kings Manpower',
            'fallback_body' => "Dear {fname},\n\nYour application for the <strong>{role}</strong> role has progressed! We have scheduled your online interview. Please review the connection details below:\n\n{interview_details}",
            'fallback_banner' => 'Please join the meeting link 10 minutes prior to your scheduled time. Ensure your camera, microphone, and internet connection are stable and working.',
            'fallback_btn_text' => 'Visit Kings Manpower',
            'fallback_btn_link' => '{site_url}',
            'tokens' => array('{fname}', '{fullname}', '{role}', '{interview_details}', '{site_url}')
        ),
        'interviewing_face_to_face' => array(
            'label' => 'Face-to-Face Interview Email (Applicant)',
            'desc' => 'Sent to the applicant when an in-office interview is scheduled.',
            'fallback_subj' => 'Face-to-Face Interview Scheduled: {role} — Kings Manpower',
            'fallback_body' => "Dear {fname},\n\nYour application for the <strong>{role}</strong> role has progressed! We have scheduled your face-to-face interview at our office. Please review the schedule and location details below:\n\n{interview_details}",
            'fallback_banner' => 'Please arrive at our office location 10 minutes prior to your scheduled time. Please bring a printed copy of your resume and a valid ID.',
            'fallback_btn_text' => 'Visit Kings Manpower',
            'fallback_btn_link' => '{site_url}',
            'tokens' => array('{fname}', '{fullname}', '{role}', '{interview_details}', '{site_url}')
        ),
        'hired' => array(
            'label' => 'Hired / Accepted Email (Applicant)',
            'desc' => 'Sent to the applicant when their application is accepted.',
            'fallback_subj' => 'Application Status: Accepted — Kings Manpower',
            'fallback_body' => "Dear {fname},\n\nWe are pleased to inform you that your application for the position of <strong>{role}</strong> has been formally <strong style=\"color:#d97706;\">accepted</strong> by Kings Manpower.\n\nOur human resources department will contact you imminently to outline the subsequent onboarding procedures, required documentation, and to finalize your commencement date.",
            'fallback_banner' => 'Welcome to the Kings Group of Companies. We look forward to a mutually rewarding professional relationship.',
            'fallback_btn_text' => 'Visit Kings Manpower',
            'fallback_btn_link' => '{site_url}',
            'tokens' => array('{fname}', '{fullname}', '{role}', '{site_url}')
        ),
        'deployed' => array(
            'label' => 'Deployed Email (Applicant)',
            'desc' => 'Sent to the applicant with deployment details.',
            'fallback_subj' => 'Deployment Assignment Notification — Kings Manpower',
            'fallback_body' => "Dear {fname},\n\nWe are excited to confirm your formal deployment details for your upcoming role. Please review your assignment details below:\n\n{deployment_details}",
            'fallback_banner' => 'Your onboarding supervisor will coordinate any site-specific guidelines and check-in procedures before your commencement date.',
            'fallback_btn_text' => 'Visit Kings Manpower',
            'fallback_btn_link' => '{site_url}',
            'tokens' => array('{fname}', '{fullname}', '{role}', '{deployment_details}', '{site_url}')
        ),
        'rejected' => array(
            'label' => 'Rejected Email (Applicant)',
            'desc' => 'Sent to the applicant when their application is rejected.',
            'fallback_subj' => 'Application Status Update — Kings Manpower',
            'fallback_body' => "Dear {fname},\n\nWe appreciate your interest in joining Kings Manpower and thank you for submitting your application for the position of <strong>{role}</strong>.\n\nFollowing a thorough evaluation of your qualifications by our talent acquisition team, we have opted to proceed with other applicants whose profiles more closely align with our immediate operational requirements.",
            'fallback_banner' => 'Please note that you cannot reapply for any other positions with us at this time. You will need to wait 14 days before submitting a new application.',
            'fallback_btn_text' => 'View Other Opportunities',
            'fallback_btn_link' => '{site_url}/our-jobs/',
            'tokens' => array('{fname}', '{fullname}', '{role}', '{site_url}')
        ),
        'recruiter_change' => array(
            'label' => 'Status Change Alert (Recruiter & Admin)',
            'desc' => 'Sent to recruiters and admins when an applicant status changes.',
            'fallback_subj' => 'Applicant Status Update: {applicant_name} — Kings Manpower',
            'fallback_body' => "Dear {name},\n\nPlease be advised that the status of an applicant in your recruitment pipeline has been updated.\n\n{status_change_details}",
            'fallback_banner' => 'Please review the updated application details and initiate the appropriate next steps in the recruitment workflow.',
            'fallback_btn_text' => 'View Application Profile',
            'fallback_btn_link' => '{edit_url}',
            'tokens' => array('{name}', '{applicant_name}', '{role}', '{old_status}', '{new_status}', '{edit_url}')
        ),
        'admin_submission' => array(
            'label' => 'New Application Submission Alert (Admin & Recruiter)',
            'desc' => 'Sent to admins and recruiters when a candidate submits their application.',
            'fallback_subj' => 'New Applicant: {applicant_name} — Kings Manpower',
            'fallback_body' => "Hi Team,\n\nA new applicant has submitted their CV via the website form. Please find the application details below:\n\n{submission_details}",
            'fallback_banner' => 'Please check the applicant profile inside WP Admin.',
            'fallback_btn_text' => 'View Application Profile',
            'fallback_btn_link' => '{edit_url}',
            'tokens' => array('{applicant_name}', '{submission_details}', '{edit_url}')
        ),
        'inquiry_update' => array(
            'label' => 'Inquiry Resolved Email (Client)',
            'desc' => 'Sent to clients when their inquiry is marked as resolved.',
            'fallback_subj' => 'Inquiry Update: Resolved — Kings Manpower',
            'fallback_body' => "Dear Client,\n\nWe are writing to confirm that your inquiry regarding <strong>\"{inquiry_subject}\"</strong> has been marked as <strong style=\"color:#00D09C;\">resolved</strong> within our system.\n\nWe trust our representatives have addressed your concerns satisfactorily. Should you require further assistance or clarification, please do not hesitate to initiate a new inquiry or reply to this correspondence.",
            'fallback_banner' => 'Thank you for choosing Kings Manpower as your trusted partner.',
            'fallback_btn_text' => 'Visit Kings Manpower',
            'fallback_btn_link' => '{site_url}',
            'tokens' => array('{inquiry_subject}', '{site_url}')
        ),
        'inquiry_in_progress' => array(
            'label' => 'Inquiry In-Progress Email (Client)',
            'desc' => 'Sent to clients when their inquiry status is updated to in progress.',
            'fallback_subj' => 'Inquiry Update: In Progress — Kings Manpower',
            'fallback_body' => "Dear {fname},\n\nThis correspondence serves to inform you that your inquiry regarding <strong>\"{inquiry_subject}\"</strong> is currently under active review by our assigned specialists.\n\nA representative will communicate our findings or request additional information shortly.",
            'fallback_banner' => 'We appreciate your patience as we ensure a comprehensive resolution to your request.',
            'fallback_btn_text' => 'Visit Kings Manpower',
            'fallback_btn_link' => '{site_url}',
            'tokens' => array('{fname}', '{inquiry_subject}', '{site_url}')
        ),
        'quote_update_contacted' => array(
            'label' => 'Quote Lead Contacted Email (Client)',
            'desc' => 'Sent to clients when their quote lead is marked as contacted.',
            'fallback_subj' => 'Update on your Service Configuration Request — Kings Manpower',
            'fallback_body' => "Dear {fname},\n\nThis is to inform you that your service configuration request has been assigned to a dedicated business development representative. We are currently finalizing the details of your proposal.",
            'fallback_banner' => 'You can expect a direct communication from our team shortly to present the formal proposal and discuss any customized requirements.',
            'fallback_btn_text' => 'Visit Kings Manpower',
            'fallback_btn_link' => '{site_url}',
            'tokens' => array('{fname}', '{fullname}', '{site_url}')
        ),
        'quote_update_converted' => array(
            'label' => 'Quote Lead Converted/Confirmed Email (Client)',
            'desc' => 'Sent to clients when their quote lead is confirmed.',
            'fallback_subj' => 'Welcome to Kings Manpower — Partnership Confirmed',
            'fallback_body' => "Dear {fname},\n\nWe are delighted to officially welcome you as a valued partner of Kings Manpower. Your service proposal has been marked as <strong style=\"color:#00D09C;\">confirmed</strong> within our system.\n\nOur account management team is currently preparing your onboarding materials and service level agreements (SLAs).",
            'fallback_banner' => 'We look forward to delivering exceptional workforce solutions that drive your operational success.',
            'fallback_btn_text' => 'Visit Kings Manpower',
            'fallback_btn_link' => '{site_url}',
            'tokens' => array('{fname}', '{fullname}', '{site_url}')
        ),
        'contact_admin' => array(
            'label' => 'Contact Inquiry Notification (Admin)',
            'desc' => 'Sent to admins when a user submits a contact form.',
            'fallback_subj' => 'Contact Inquiry: {contact_subject}',
            'fallback_body' => "Hi Team,\n\nA new inquiry has been submitted via the Kings Manpower corporate website. Please review the details below and ensure a timely response.\n\n{contact_details}",
            'fallback_banner' => "To respond, please reply directly to this email. The sender's address is configured as the reply-to destination.",
            'fallback_btn_text' => 'View Inquiries',
            'fallback_btn_link' => '{site_url}/wp-admin/edit.php?post_type=kg_inquiry',
            'tokens' => array('{contact_subject}', '{contact_details}', '{site_url}')
        ),
        'contact_client' => array(
            'label' => 'Contact Inquiry Acknowledgment (Client)',
            'desc' => 'Sent to the user acknowledging receipt of their contact form submission.',
            'fallback_subj' => 'Inquiry Acknowledgment — Kings Manpower',
            'fallback_body' => "Dear {name},\n\nThank you for contacting Kings Manpower. We acknowledge receipt of your inquiry and appreciate your interest in our services. Our team is currently reviewing your message and will provide a comprehensive response shortly.\n\n{contact_details}",
            'fallback_banner' => 'Please expect a response from our representatives within 24 to 48 hours.',
            'fallback_btn_text' => 'Visit Our Website',
            'fallback_btn_link' => '{site_url}',
            'tokens' => array('{name}', '{contact_subject}', '{contact_details}', '{site_url}')
        ),
        'quote_admin' => array(
            'label' => 'Service Proposal Request Notification (Admin)',
            'desc' => 'Sent to admins when a prospective client submits a quote/team configuration request.',
            'fallback_subj' => 'Quote Request from {client_name} — {quote_total}',
            'fallback_body' => "Hi Team,\n\nA prospective client has submitted a formal request for a service proposal via the Kings Manpower platform. Please review the enclosed workforce configuration.\n\nClient Name: <strong>{client_name}</strong>\nWork Email: <strong>{client_email}</strong>\n\n{quote_details}",
            'fallback_banner' => "To initiate correspondence, please reply directly to this email. The prospect's email address is designated as the reply-to.",
            'fallback_btn_text' => 'View Quote Leads',
            'fallback_btn_link' => '{site_url}/wp-admin/edit.php?post_type=kg_quote_lead',
            'tokens' => array('{client_name}', '{client_email}', '{quote_total}', '{quote_details}', '{site_url}')
        ),
        'quote_client' => array(
            'label' => 'Proposal Request Acknowledgment (Client)',
            'desc' => 'Sent to the client acknowledging their quote/team configuration submission.',
            'fallback_subj' => 'Your Kings Manpower Service Proposal — {quote_total}',
            'fallback_body' => "Dear {name},\n\nThank you for considering Kings Manpower as your workforce solutions partner. We have successfully received your service configuration request. Our business development team is currently analyzing your requirements to formulate a comprehensive proposal.\n\n{quote_details}",
            'fallback_banner' => 'A dedicated representative will contact you within one business day to present a detailed pricing breakdown and discuss your specific needs.',
            'fallback_btn_text' => 'Visit Kings Manpower',
            'fallback_btn_link' => '{site_url}',
            'tokens' => array('{name}', '{quote_total}', '{quote_details}', '{site_url}')
        ),
        'recruiter_job_review' => array(
            'label' => 'Job Pending Review Notification (Admin/HR/Recruitment Admin)',
            'desc' => 'Sent to administrators, HR, and Recruitment Admin when a recruiter submits a job for review.',
            'fallback_subj' => '[{site_name}] Job Submitted for Review: {job_title}',
            'fallback_body' => "Hello Team,\n\nA new job post has been submitted by <strong>{author_name}</strong> and is currently waiting for your review and approval.\n\n<div style=\"border:1px solid #e8ecf0;border-radius:8px;padding:20px;margin-bottom:24px;background:#ffffff;\">{job_details}</div>",
            'fallback_banner' => '',
            'fallback_btn_text' => 'Review & Publish Job',
            'fallback_btn_link' => '{edit_link}',
            'tokens' => array('{site_name}', '{job_title}', '{author_name}', '{job_details}', '{edit_link}', '{site_url}')
        ),
        'sla_digest_email' => array(
            'label' => 'Daily SLA Digest (Admin)',
            'desc' => 'Sent daily to administrators summarizing applications that have breached SLA or are near breach.',
            'fallback_subj' => 'SLA Daily Digest: Applicant Warnings & Breaches — Kings Manpower',
            'fallback_body' => "Hi {admin_name},\n\nThis is your automated daily digest regarding Applicant Tracking SLAs. The following applications are currently stuck in the Screening phase and require immediate attention.\n\n{sla_table}\n\nPlease review these applicants in the system and update their status to prevent further delays.",
            'fallback_banner' => 'Timely processing of applicants ensures a high-quality candidate experience and meets our Service Level Agreements.',
            'fallback_btn_text' => 'View Applications in WP Admin',
            'fallback_btn_link' => '{site_url}/wp-admin/edit.php?post_type=kg_application',
            'tokens' => array('{admin_name}', '{sla_table}', '{site_url}')
        )
    );

    $saved = get_option('kg_email_templates', array());
    $active_key = isset($_GET['template']) ? sanitize_key($_GET['template']) : 'pooling';

    // Save handling
    if (isset($_POST['kg_email_templates_save_nonce']) && wp_verify_nonce($_POST['kg_email_templates_save_nonce'], 'kg_email_templates_save')) {
        $key = sanitize_key($_POST['kg_active_template']);
        if ($key === 'global_branding') {
            $saved['branding'] = array(
                'logo_url' => esc_url_raw($_POST['logo_url'] ?? ''),
                'footer_address' => sanitize_textarea_field($_POST['footer_address'] ?? ''),
                'footer_phone' => sanitize_text_field($_POST['footer_phone'] ?? ''),
                'footer_web_text' => sanitize_text_field($_POST['footer_web_text'] ?? ''),
            );
            update_option('kg_email_templates', $saved);
            echo '<div class="notice notice-success is-dismissible"><p><strong>Global Email Branding Settings</strong> saved successfully.</p></div>';
        } elseif (isset($templates_list[$key])) {
            $saved[$key] = array(
                'subject' => sanitize_text_field($_POST['subject'] ?? ''),
                'heading' => sanitize_text_field($_POST['heading'] ?? ''),
                'body' => wp_kses_post($_POST['body'] ?? ''),
                'banner' => sanitize_text_field($_POST['banner'] ?? ''),
                'btn_text' => sanitize_text_field($_POST['btn_text'] ?? ''),
                'btn_link' => sanitize_text_field($_POST['btn_link'] ?? ''),
            );
            update_option('kg_email_templates', $saved);
            echo '<div class="notice notice-success is-dismissible"><p>Settings for <strong>' . esc_html($templates_list[$key]['label']) . '</strong> saved successfully.</p></div>';
        }
    }

    $headings_map = array(
        'pooling' => 'You Are in Our Talent Pool!',
        'screening' => 'Application Status: Under Review',
        'processing' => 'You\'re moving to Processing!',
        'interviewing_online' => 'Your Online Interview Schedule',
        'interviewing_face_to_face' => 'Your Face-to-Face Interview Schedule',
        'hired' => 'Application Status: Accepted',
        'deployed' => 'Workforce Deployment Details',
        'rejected' => 'Application Status Update',
        'recruiter_change' => 'Applicant Status Updated',
        'admin_submission' => 'Applicant Application Notification',
        'inquiry_update' => 'Inquiry Status: Resolved',
        'inquiry_in_progress' => 'Inquiry Status: In Progress',
        'quote_update_contacted' => 'Proposal Status: Under Review',
        'quote_update_converted' => 'Partnership Confirmed',
        'contact_admin' => 'Website Inquiry Notification',
        'contact_client' => 'Inquiry Acknowledgment',
        'quote_admin' => 'Service Proposal Request Notification',
        'quote_client' => 'Proposal Request Acknowledgment',
        'recruiter_job_review' => 'Job Pending Review',
        'sla_digest_email' => 'SLA Daily Digest: Action Required',
    );

    if ($active_key === 'global_branding') {
        $active = array(
            'label' => 'Global Layout & Branding Settings',
            'desc' => 'Configure the overall look, feel, and contact details of all automated transactional emails sent by the system.'
        );
        $branding_data = isset($saved['branding']) ? $saved['branding'] : array();
    } else {
        $active_key = isset($templates_list[$active_key]) ? $active_key : 'pooling';
        $active = $templates_list[$active_key];
        $data = isset($saved[$active_key]) ? $saved[$active_key] : array();

        $subj = isset($data['subject']) ? $data['subject'] : $active['fallback_subj'];
        $heading_fallback = isset($headings_map[$active_key]) ? $headings_map[$active_key] : '';
        $heading = isset($data['heading']) ? $data['heading'] : $heading_fallback;
        $body = isset($data['body']) ? $data['body'] : $active['fallback_body'];
        $banner = isset($data['banner']) ? $data['banner'] : $active['fallback_banner'];
        $btn_t = isset($data['btn_text']) ? $data['btn_text'] : $active['fallback_btn_text'];
        $btn_l = isset($data['btn_link']) ? $data['btn_link'] : $active['fallback_btn_link'];
    }

    ?>
    <div class="wrap">
        <h1>Email Templates Configuration</h1>
        <p>Customize the automated emails sent by the recruitment and sales systems. Use the left menu to navigate through
            templates.</p>
        <hr style="margin-bottom:20px;">

        <div style="display:flex; gap:20px; align-items:flex-start;">
            <!-- Left Sidebar Navigation -->
            <div style="width:250px; background:#fff; border:1px solid #ccd0d4; box-shadow:0 1px 1px rgba(0,0,0,.04);">
                <?php
                $groups = array(
                    'branding' => array(
                        'label' => 'Global Branding',
                        'keys' => array('global_branding')
                    ),
                    'apps' => array(
                        'label' => 'Applications Emails',
                        'keys' => array('pooling', 'screening', 'processing', 'interviewing_online', 'interviewing_face_to_face', 'hired', 'deployed', 'rejected', 'recruiter_change', 'admin_submission', 'recruiter_job_review', 'sla_digest_email')
                    ),
                    'quotes' => array(
                        'label' => 'Quote Emails',
                        'keys' => array('quote_update_contacted', 'quote_update_converted', 'quote_admin', 'quote_client')
                    ),
                    'inquiries' => array(
                        'label' => 'Inquiry Emails',
                        'keys' => array('inquiry_update', 'inquiry_in_progress', 'contact_admin', 'contact_client')
                    )
                );
                foreach ($groups as $group_key => $group): ?>
                    <div
                        style="background:#f0f0f1; padding:10px 12px; font-weight:700; font-size:11px; text-transform:uppercase; letter-spacing: 0.05em; color:#50575e; border-bottom:1px solid #ccd0d4; border-top:<?php echo $group_key === 'branding' ? '0' : '1px solid #ccd0d4'; ?>;">
                        <?php echo esc_html($group['label']); ?>
                    </div>
                    <ul style="margin:0; padding:0; list-style:none;">
                        <?php foreach ($group['keys'] as $k):
                            $label = ($k === 'global_branding') ? 'Layout & Branding Settings' : ($templates_list[$k]['label'] ?? '');
                            if (empty($label))
                                continue;
                            $is_active = ($k === $active_key);
                            $bg = $is_active ? '#2271b1' : '#fff';
                            $color = $is_active ? '#fff' : '#2271b1';
                            $hover_style = $is_active ? '' : 'onmouseover="this.style.background=\'#f0f0f1\'" onmouseout="this.style.background=\'#fff\'"';
                            ?>
                            <li style="margin:0; border-bottom:1px solid #ccd0d4;">
                                <a href="<?php echo esc_url(add_query_arg('template', $k)); ?>"
                                    style="display:block; padding:10px 16px; text-decoration:none; font-weight:600; font-size:12px; background:<?php echo $bg; ?>; color:<?php echo $color; ?>;"
                                    <?php echo $hover_style; ?>>
                                    <?php echo esc_html($label); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endforeach; ?>
            </div>

            <!-- Right Content Form -->
            <div
                style="flex:1; background:#fff; border:1px solid #ccd0d4; padding:20px; box-shadow:0 1px 1px rgba(0,0,0,.04);">
                <h2>Edit Template: <?php echo esc_html($active['label']); ?></h2>
                <p style="color:#64748b; font-style:italic; margin-bottom:20px;"><?php echo esc_html($active['desc']); ?>
                </p>

                <form method="post" action="">
                    <?php wp_nonce_field('kg_email_templates_save', 'kg_email_templates_save_nonce'); ?>
                    <input type="hidden" name="kg_active_template" value="<?php echo esc_attr($active_key); ?>" />

                    <?php if ($active_key === 'global_branding'): ?>
                        <table class="form-table" role="presentation">
                            <tr>
                                <th scope="row"><label for="logo_url">Branded Logo</label></th>
                                <td>
                                    <div id="logo_preview_container"
                                        style="margin-bottom: 10px; <?php echo empty($branding_data['logo_url']) ? 'display:none;' : ''; ?>">
                                        <img id="logo_preview_img"
                                            src="<?php echo esc_url($branding_data['logo_url'] ?? ''); ?>"
                                            style="max-height: 80px; background: #f0f0f1; padding: 10px; border-radius: 4px; display: block;" />
                                    </div>
                                    <input type="text" name="logo_url" id="logo_url"
                                        value="<?php echo esc_url($branding_data['logo_url'] ?? ''); ?>" class="large-text"
                                        readonly style="background: #f0f0f1; cursor: not-allowed; margin-bottom: 10px;" />
                                    <div>
                                        <button type="button" class="button button-secondary" id="kg_upload_logo_btn">Select or
                                            Upload Logo</button>
                                        <button type="button" class="button button-link-delete" id="kg_remove_logo_btn"
                                            style="margin-left: 10px; <?php echo empty($branding_data['logo_url']) ? 'display:none;' : ''; ?>">Remove
                                            Logo</button>
                                    </div>
                                    <p class="description">Select or upload the logo image (preferably PNG/WebP with transparent
                                        bg) from the Media Library. It will be centered at the top of your emails.</p>

                                    <script type="text/javascript">
                                        jQuery(document).ready(function ($) {
                                            var file_frame;
                                            $('#kg_upload_logo_btn').on('click', function (e) {
                                                e.preventDefault();
                                                if (file_frame) {
                                                    file_frame.open();
                                                    return;
                                                }
                                                file_frame = wp.media.frames.file_frame = wp.media({
                                                    title: 'Select or Upload Logo',
                                                    button: {
                                                        text: 'Use this Logo'
                                                    },
                                                    multiple: false
                                                });
                                                file_frame.on('select', function () {
                                                    var attachment = file_frame.state().get('selection').first().toJSON();
                                                    $('#logo_url').val(attachment.url);
                                                    $('#logo_preview_img').attr('src', attachment.url);
                                                    $('#logo_preview_container').show();
                                                    $('#kg_remove_logo_btn').show();
                                                });
                                                file_frame.open();
                                            });

                                            $('#kg_remove_logo_btn').on('click', function (e) {
                                                e.preventDefault();
                                                $('#logo_url').val('');
                                                $('#logo_preview_img').attr('src', '');
                                                $('#logo_preview_container').hide();
                                                $(this).hide();
                                            });
                                        });
                                    </script>
                                </td>
                            </tr>

                            <tr>
                                <th scope="row"><label for="footer_address">Footer Address Details</label></th>
                                <td>
                                    <textarea name="footer_address" id="footer_address" class="large-text" rows="3"
                                        placeholder="100 Doña Soledad Ave, Better Living, Parañaque, 1711 Philippines"><?php echo esc_textarea($branding_data['footer_address'] ?? '100 Doña Soledad Ave, Better Living, Parañaque, 1711 Philippines'); ?></textarea>
                                    <p class="description">Physical corporate details rendered in the email footer signature
                                        block.</p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="footer_phone">Footer Phone / Contacts</label></th>
                                <td>
                                    <input type="text" name="footer_phone" id="footer_phone"
                                        value="<?php echo esc_attr($branding_data['footer_phone'] ?? '+63 2 8-776-6712 | +63 2 7-738-8071'); ?>"
                                        class="large-text" placeholder="+63 2 8-776-6712 | +63 2 7-738-8071" />
                                    <p class="description">Display phone details printed at the bottom of standard transactional
                                        emails.</p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="footer_web_text">Footer Website Link Text</label></th>
                                <td>
                                    <input type="text" name="footer_web_text" id="footer_web_text"
                                        value="<?php echo esc_attr($branding_data['footer_web_text'] ?? 'kingsgroup.com.ph'); ?>"
                                        class="regular-text" placeholder="kingsgroup.com.ph" />
                                    <p class="description">Branded target link label displayed in the email footer. Defaults to
                                        kingsgroup.com.ph.</p>
                                </td>
                            </tr>
                        </table>
                    <?php else: ?>
                        <table class="form-table" role="presentation">
                            <tr>
                                <th scope="row"><label for="subject">Email Subject</label></th>
                                <td>
                                    <input type="text" name="subject" id="subject" value="<?php echo esc_attr($subj); ?>"
                                        class="large-text" required />
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="heading">Email Heading</label></th>
                                <td>
                                    <input type="text" name="heading" id="heading" value="<?php echo esc_attr($heading); ?>"
                                        class="large-text" required />
                                    <p class="description">Main heading text printed in the colored card header at the top of
                                        the email.</p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="body">Email Body (HTML/Copy)</label></th>
                                <td>
                                    <?php
                                    wp_editor($body, 'body', array(
                                        'textarea_name' => 'body',
                                        'textarea_rows' => 12,
                                        'media_buttons' => false,
                                        'teeny' => false,
                                        'quicktags' => true,
                                    ));
                                    ?>
                                    <p class="description" style="margin-top:8px;">
                                        <strong>Supported tokens:</strong>
                                        <code><?php echo esc_html(implode(', ', $active['tokens'])); ?></code>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="banner">Banner Highlight Text</label></th>
                                <td>
                                    <input type="text" name="banner" id="banner" value="<?php echo esc_attr($banner); ?>"
                                        class="large-text" />
                                    <p class="description">Optional highlight text shown in a prominent visual card inside the
                                        email.</p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="btn_text">Button Text</label></th>
                                <td>
                                    <input type="text" name="btn_text" id="btn_text" value="<?php echo esc_attr($btn_t); ?>"
                                        class="regular-text" />
                                    <p class="description">Optional call-to-action button label.</p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="btn_link">Button Link (URL)</label></th>
                                <td>
                                    <input type="text" name="btn_link" id="btn_link" value="<?php echo esc_attr($btn_l); ?>"
                                        class="large-text" />
                                    <p class="description">Supports link URLs or dynamic tokens like <code>{site_url}</code> or
                                        <code>{edit_url}</code>.
                                    </p>
                                </td>
                            </tr>
                        </table>
                    <?php endif; ?>

                    <div style="margin-top:20px;">
                        <input type="submit" class="button button-primary button-large" value="Save Template Settings" />
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Global helper to parse subject and body templates from settings
 */
function kg_get_parsed_email($template_key, $replacements = array())
{
    $replacements['{site_url}'] = home_url('/');

    $templates_list = array(
        'pooling' => array(
            'fallback_subj' => 'Application Received: Talent Pool — Kings Manpower',
            'fallback_body' => "Dear {fname},\n\nThank you for submitting your CV to Kings Manpower. Your profile has been added to our active talent pool.\n\nWe will review your qualifications and match you with suitable opportunities as they become available. There is no further action required from you at this time.",
            'fallback_banner' => 'We will reach out as soon as a position aligns with your skills and experience.',
            'fallback_btn_text' => 'View Open Positions',
            'fallback_btn_link' => '{site_url}/our-jobs/'
        ),
        'screening' => array(
            'fallback_subj' => 'Application Status: Screening — Kings Manpower',
            'fallback_body' => "Dear {fname},\n\nWe have received your application for the following position(s): <strong>{roles}</strong>. Your application is currently being screened by our recruiting team.\n\nOur team is carefully reviewing your credentials and experience. We will be in touch with you soon regarding the next steps in our hiring process.",
            'fallback_banner' => 'Your applicant profile is actively under review. No further action is required from you at this stage.',
            'fallback_btn_text' => 'Visit Kings Manpower',
            'fallback_btn_link' => '{site_url}'
        ),
        'processing' => array(
            'fallback_subj' => 'Application Processing: Action Required — Kings Manpower',
            'fallback_body' => "Dear {fname},\n\nCongratulations! Your application for <strong>{role}</strong> is now being processed.\n\nPlease review your specific requirements and important dates below. Ensure all your requirements are submitted on or before the deadline.\n\n{processing_details}",
            'fallback_banner' => 'Submit all requirements on or before the deadline to avoid delays in your deployment.',
            'fallback_btn_text' => 'Visit Kings Manpower',
            'fallback_btn_link' => '{site_url}'
        ),
        'interviewing_online' => array(
            'fallback_subj' => 'Online Interview Scheduled: {role} — Kings Manpower',
            'fallback_body' => "Dear {fname},\n\nYour application for the <strong>{role}</strong> role has progressed! We have scheduled your online interview. Please review the connection details below:\n\n{interview_details}",
            'fallback_banner' => 'Please join the meeting link 10 minutes prior to your scheduled time. Ensure your camera, microphone, and internet connection are stable and working.',
            'fallback_btn_text' => 'Visit Kings Manpower',
            'fallback_btn_link' => '{site_url}'
        ),
        'interviewing_face_to_face' => array(
            'fallback_subj' => 'Face-to-Face Interview Scheduled: {role} — Kings Manpower',
            'fallback_body' => "Dear {fname},\n\nYour application for the <strong>{role}</strong> role has progressed! We have scheduled your face-to-face interview at our office. Please review the schedule and location details below:\n\n{interview_details}",
            'fallback_banner' => 'Please arrive at our office location 10 minutes prior to your scheduled time. Please bring a printed copy of your resume and a valid ID.',
            'fallback_btn_text' => 'Visit Kings Manpower',
            'fallback_btn_link' => '{site_url}'
        ),
        'hired' => array(
            'fallback_subj' => 'Application Status: Accepted — Kings Manpower',
            'fallback_body' => "Dear {fname},\n\nWe are pleased to inform you that your application for the position of <strong>{role}</strong> has been formally <strong style=\"color:#d97706;\">accepted</strong> by Kings Manpower.\n\nOur human resources department will contact you imminently to outline the subsequent onboarding procedures, required documentation, and to finalize your commencement date.",
            'fallback_banner' => 'Welcome to the Kings Group of Companies. We look forward to a mutually rewarding professional relationship.',
            'fallback_btn_text' => 'Visit Kings Manpower',
            'fallback_btn_link' => '{site_url}'
        ),
        'deployed' => array(
            'fallback_subj' => 'Deployment Assignment Notification — Kings Manpower',
            'fallback_body' => "Dear {fname},\n\nWe are excited to confirm your formal deployment details for your upcoming role. Please review your assignment details below:\n\n{deployment_details}",
            'fallback_banner' => 'Your onboarding supervisor will coordinate any site-specific guidelines and check-in procedures before your commencement date.',
            'fallback_btn_text' => 'Visit Kings Manpower',
            'fallback_btn_link' => '{site_url}'
        ),
        'rejected' => array(
            'fallback_subj' => 'Application Status Update — Kings Manpower',
            'fallback_body' => "Dear {fname},\n\nWe appreciate your interest in joining Kings Manpower and thank you for submitting your application for the position of <strong>{role}</strong>.\n\nFollowing a thorough evaluation of your qualifications by our talent acquisition team, we have opted to proceed with other applicants whose profiles more closely align with our immediate operational requirements.",
            'fallback_banner' => 'Please note that you cannot reapply for any other positions with us at this time. You will need to wait 14 days before submitting a new application.',
            'fallback_btn_text' => 'View Other Opportunities',
            'fallback_btn_link' => '{site_url}/our-jobs/'
        ),
        'recruiter_change' => array(
            'fallback_subj' => 'Applicant Status Change: {applicant_name} — Kings Manpower',
            'fallback_body' => "Hi {name},\n\nAn applicant has changed their pipeline status.\n\n{status_change_details}",
            'fallback_banner' => 'Please check the application details and proceed with the necessary next actions.',
            'fallback_btn_text' => 'View Application in Admin',
            'fallback_btn_link' => '{edit_url}'
        ),
        'admin_submission' => array(
            'fallback_subj' => 'New Applicant: {applicant_name} — Kings Manpower',
            'fallback_body' => "Hi Team,\n\nA new applicant has submitted their CV via the website form. Please find the application details below:\n\n{submission_details}",
            'fallback_banner' => 'Please check the applicant profile inside WP Admin.',
            'fallback_btn_text' => 'View Application Profile',
            'fallback_btn_link' => '{edit_url}'
        ),
        'inquiry_update' => array(
            'fallback_subj' => 'Inquiry Update: Resolved — Kings Manpower',
            'fallback_body' => "Dear Client,\n\nWe are writing to confirm that your inquiry regarding <strong>\"{inquiry_subject}\"</strong> has been marked as <strong style=\"color:#00D09C;\">resolved</strong> within our system.\n\nWe trust our representatives have addressed your concerns satisfactorily. Should you require further assistance or clarification, please do not hesitate to initiate a new inquiry or reply to this correspondence.",
            'fallback_banner' => 'Thank you for choosing Kings Manpower as your trusted partner.',
            'fallback_btn_text' => 'Visit Kings Manpower',
            'fallback_btn_link' => '{site_url}'
        ),
        'quote_update_contacted' => array(
            'fallback_subj' => 'Update on your Service Configuration Request — Kings Manpower',
            'fallback_body' => "Dear {fname},\n\nThis is to inform you that your service configuration request has been assigned to a dedicated business development representative. We are currently finalizing the details of your proposal.",
            'fallback_banner' => 'You can expect a direct communication from our team shortly to present the formal proposal and discuss any customized requirements.',
            'fallback_btn_text' => 'Visit Kings Manpower',
            'fallback_btn_link' => '{site_url}'
        ),
        'quote_update_converted' => array(
            'fallback_subj' => 'Welcome to Kings Manpower — Partnership Confirmed',
            'fallback_body' => "Dear {fname},\n\nWe are delighted to officially welcome you as a valued partner of Kings Manpower. Your service proposal has been marked as <strong style=\"color:#00D09C;\">confirmed</strong> within our system.\n\nOur account management team is currently preparing your onboarding materials and service level agreements (SLAs).",
            'fallback_banner' => 'We look forward to delivering exceptional workforce solutions that drive your operational success.',
            'fallback_btn_text' => 'Visit Kings Manpower',
            'fallback_btn_link' => '{site_url}'
        ),
        'contact_admin' => array(
            'fallback_subj' => 'Contact Inquiry: {contact_subject}',
            'fallback_body' => "Hi Team,\n\nA new inquiry has been submitted via the Kings Manpower corporate website. Please review the details below and ensure a timely response.\n\n{contact_details}",
            'fallback_banner' => "To respond, please reply directly to this email. The sender's address is configured as the reply-to destination.",
            'fallback_btn_text' => 'View Inquiries',
            'fallback_btn_link' => '{site_url}/wp-admin/edit.php?post_type=kg_inquiry'
        ),
        'contact_client' => array(
            'fallback_subj' => 'Inquiry Acknowledgment — Kings Manpower',
            'fallback_body' => "Dear {name},\n\nThank you for contacting Kings Manpower. We acknowledge receipt of your inquiry and appreciate your interest in our services. Our team is currently reviewing your message and will provide a comprehensive response shortly.\n\n{contact_details}",
            'fallback_banner' => 'Please expect a response from our representatives within 24 to 48 hours.',
            'fallback_btn_text' => 'Visit Our Website',
            'fallback_btn_link' => '{site_url}'
        ),
        'quote_admin' => array(
            'fallback_subj' => 'Quote Request from {client_name} — {quote_total}',
            'fallback_body' => "Hi Team,\n\nA prospective client has submitted a formal request for a service proposal via the Kings Manpower platform. Please review the enclosed workforce configuration.\n\nClient Name: <strong>{client_name}</strong>\nWork Email: <strong>{client_email}</strong>\n\n{quote_details}",
            'fallback_banner' => "To initiate correspondence, please reply directly to this email. The prospect's email address is designated as the reply-to.",
            'fallback_btn_text' => 'View Quote Leads',
            'fallback_btn_link' => '{site_url}/wp-admin/edit.php?post_type=kg_quote_lead'
        ),
        'quote_client' => array(
            'fallback_subj' => 'Your Kings Manpower Service Proposal — {quote_total}',
            'fallback_body' => "Dear {name},\n\nThank you for considering Kings Manpower as your workforce solutions partner. We have successfully received your service configuration request. Our business development team is currently analyzing your requirements to formulate a comprehensive proposal.\n\n{quote_details}",
            'fallback_banner' => 'A dedicated representative will contact you within one business day to present a detailed pricing breakdown and discuss your specific needs.',
            'fallback_btn_text' => 'Visit Kings Manpower',
            'fallback_btn_link' => '{site_url}'
        ),
        'recruiter_job_review' => array(
            'fallback_subj' => '[{site_name}] Job Submitted for Review: {job_title}',
            'fallback_body' => "Hello Team,\n\nA new job post has been submitted by <strong>{author_name}</strong> and is currently waiting for your review and approval.\n\n<div style=\"border:1px solid #e8ecf0;border-radius:8px;padding:20px;margin-bottom:24px;background:#ffffff;\">{job_details}</div>",
            'fallback_banner' => '',
            'fallback_btn_text' => 'Review & Publish Job',
            'fallback_btn_link' => '{edit_link}'
        )
    );

    if (!isset($templates_list[$template_key])) {
        return false;
    }

    $headings_map = array(
        'pooling' => 'You Are in Our Talent Pool!',
        'screening' => 'Application Status: Under Review',
        'interviewing_online' => 'Your Online Interview Schedule',
        'interviewing_face_to_face' => 'Your Face-to-Face Interview Schedule',
        'hired' => 'Application Status: Accepted',
        'deployed' => 'Workforce Deployment Details',
        'rejected' => 'Application Status Update',
        'recruiter_change' => 'Applicant Status Updated',
        'admin_submission' => 'Applicant Application Notification',
        'inquiry_update' => 'Inquiry Status: Resolved',
        'quote_update_contacted' => 'Proposal Status: Under Review',
        'quote_update_converted' => 'Partnership Confirmed',
        'contact_admin' => 'Website Inquiry Notification',
        'contact_client' => 'Inquiry Acknowledgment',
        'quote_admin' => 'Service Proposal Request Notification',
        'quote_client' => 'Proposal Request Acknowledgment',
    );

    $saved = get_option('kg_email_templates', array());
    $active = $templates_list[$template_key];
    $data = isset($saved[$template_key]) ? $saved[$template_key] : array();

    $subject = isset($data['subject']) ? $data['subject'] : $active['fallback_subj'];
    $heading_fallback = isset($headings_map[$template_key]) ? $headings_map[$template_key] : '';
    $heading = isset($data['heading']) ? $data['heading'] : $heading_fallback;
    $body = isset($data['body']) ? $data['body'] : $active['fallback_body'];
    $banner = isset($data['banner']) ? $data['banner'] : $active['fallback_banner'];
    $btn_text = isset($data['btn_text']) ? $data['btn_text'] : $active['fallback_btn_text'];
    $btn_link = isset($data['btn_link']) ? $data['btn_link'] : $active['fallback_btn_link'];

    foreach ($replacements as $placeholder => $val) {
        $subject = str_replace($placeholder, $val, $subject);
        $heading = str_replace($placeholder, $val, $heading);
        $body = str_replace($placeholder, $val, $body);
        $banner = str_replace($placeholder, $val, $banner);
        $btn_text = str_replace($placeholder, $val, $btn_text);
        $btn_link = str_replace($placeholder, $val, $btn_link);
    }

    if (!preg_match('/<[a-z][\s\S]*>/i', $body)) {
        $body = wpautop($body);
    }

    return array(
        'subject' => $subject,
        'heading' => $heading,
        'body' => $body,
        'banner' => $banner,
        'btn_text' => $btn_text,
        'btn_link' => $btn_link,
    );
}

// --- SLA Email Digest Cron ---

if ( ! wp_next_scheduled( 'kg_daily_sla_check' ) ) {
    wp_schedule_event( time(), 'daily', 'kg_daily_sla_check' );
}

add_action( 'kg_daily_sla_check', 'kg_process_daily_sla_digest' );

function kg_process_daily_sla_digest() {
    $args = array(
        'post_type'      => 'kg_application',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'meta_query'     => array(
            array(
                'key'   => 'kg_app_status',
                'value' => 'screening',
            ),
        ),
    );

    $query = new WP_Query( $args );
    if ( ! $query->have_posts() ) return;

    $breaches = array();
    $warnings = array();

    $now = current_time( 'timestamp' );

    foreach ( $query->posts as $post ) {
        $start_date = get_post_meta( $post->ID, 'kg_app_screening_start_date', true );
        if ( ! $start_date ) {
            $start_date = strtotime( $post->post_modified );
        }
        $days_stuck = floor( ( $now - $start_date ) / ( 60 * 60 * 24 ) );

        if ( $days_stuck >= 10 ) {
            $breaches[] = array( 'post' => $post, 'days' => $days_stuck );
        } elseif ( $days_stuck >= 5 ) {
            $warnings[] = array( 'post' => $post, 'days' => $days_stuck );
        }
    }

    if ( empty( $breaches ) && empty( $warnings ) ) return;

    // Build Table
    $table_html = '<table style="width:100%;border-collapse:collapse;margin-top:10px;">';
    $table_html .= '<thead><tr><th style="border-bottom:1px solid #ddd;text-align:left;padding:8px;">Applicant</th><th style="border-bottom:1px solid #ddd;text-align:left;padding:8px;">Role</th><th style="border-bottom:1px solid #ddd;text-align:left;padding:8px;">SLA Status</th></tr></thead>';
    $table_html .= '<tbody>';

    foreach ( $breaches as $b ) {
        $role = get_post_meta( $b['post']->ID, 'kg_app_role', true ) ?: 'Not assigned';
        $table_html .= '<tr>';
        $table_html .= '<td style="border-bottom:1px solid #eee;padding:8px;"><strong>' . esc_html( $b['post']->post_title ) . '</strong></td>';
        $table_html .= '<td style="border-bottom:1px solid #eee;padding:8px;">' . esc_html( $role ) . '</td>';
        $table_html .= '<td style="border-bottom:1px solid #eee;padding:8px;"><span style="color:#dc2626;font-weight:bold;">🚨 BREACH (' . $b['days'] . 'd)</span></td>';
        $table_html .= '</tr>';
    }
    
    foreach ( $warnings as $w ) {
        $role = get_post_meta( $w['post']->ID, 'kg_app_role', true ) ?: 'Not assigned';
        $table_html .= '<tr>';
        $table_html .= '<td style="border-bottom:1px solid #eee;padding:8px;"><strong>' . esc_html( $w['post']->post_title ) . '</strong></td>';
        $table_html .= '<td style="border-bottom:1px solid #eee;padding:8px;">' . esc_html( $role ) . '</td>';
        $table_html .= '<td style="border-bottom:1px solid #eee;padding:8px;"><span style="color:#ea580c;font-weight:bold;">⚠️ WARNING (' . $w['days'] . 'd)</span></td>';
        $table_html .= '</tr>';
    }

    $table_html .= '</tbody></table>';

    $parsed = kg_get_parsed_email( 'sla_digest_email', array(
        '{admin_name}' => 'Admin Team',
        '{sla_table}'  => $table_html,
        '{site_url}'   => home_url(),
    ) );

    if ( ! empty( $parsed ) ) {
        $subject = $parsed['subject'];
        $body = kg_email_heading( $parsed['heading'] ) . $parsed['body'];

        if ( ! empty( $parsed['banner'] ) ) {
            $body .= kg_email_banner( $parsed['banner'] );
        }
        if ( ! empty( $parsed['btn_text'] ) && ! empty( $parsed['btn_link'] ) ) {
            $body .= kg_email_button( $parsed['btn_text'], $parsed['btn_link'] );
        }

        $recipient_emails = array();
        
        $admin_users = get_users(array(
            'role__in' => array('administrator', 'hr', 'recruitment_admin')
        ));
        
        foreach ($admin_users as $user) {
            if (is_email($user->user_email)) {
                $recipient_emails[] = $user->user_email;
            }
        }

        if (empty($recipient_emails)) {
            $recipient_emails = get_option('admin_email');
        }

        wp_mail(
            $recipient_emails,
            $subject,
            kg_email_wrap( $subject, $body, 'Admin Team', '', date_i18n( get_option( 'date_format' ) ) ),
            array( 'Content-Type: text/html; charset=UTF-8' )
        );
    }
}
