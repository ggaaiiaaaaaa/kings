<?php
/**
 * Applications CPT — stores career form submissions in WP Admin.
 * Each application is a private post with applicant details as meta.
 * Status (pending / accepted / rejected) is managed from the edit screen.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

/* ─────────────────────────────────────────────
   Register CPT
───────────────────────────────────────────── */

function kg_register_application_cpt() {
    register_post_type( 'kg_application', array(
        'labels' => array(
            'name'               => 'Applications',
            'singular_name'      => 'Application',
            'menu_name'          => 'Applications',
            'all_items'          => 'All Applications',
            'edit_item'          => 'View Application',
            'search_items'       => 'Search Applications',
            'not_found'          => 'No applications found.',
            'not_found_in_trash' => 'No applications in trash.',
        ),
        'public'        => false,
        'show_ui'       => true,
        'show_in_menu'  => true,
        'show_in_rest'  => false,
        'supports'      => array( 'title' ),
        'has_archive'   => false,
        'rewrite'       => false,
        'menu_icon'     => 'dashicons-id-alt',
        'menu_position' => 5,
        'capabilities'  => array(
            'create_posts' => 'do_not_allow', // no manual creation from admin
        ),
        'map_meta_cap'  => true,
    ) );
}
add_action( 'init', 'kg_register_application_cpt' );

/* ─────────────────────────────────────────────
   Save application post (called from form handler)
───────────────────────────────────────────── */

function kg_save_application_post( $data ) {
    $post_id = wp_insert_post( array(
        'post_title'  => sanitize_text_field( $data['fullname'] ),
        'post_status' => 'publish',
        'post_type'   => 'kg_application',
        'post_date'   => current_time( 'mysql' ),
    ) );

    if ( is_wp_error( $post_id ) ) return false;

    update_post_meta( $post_id, 'kg_app_email',    sanitize_email( $data['email'] ) );
    update_post_meta( $post_id, 'kg_app_phone',    sanitize_text_field( $data['phone'] ) );
    update_post_meta( $post_id, 'kg_app_role',     sanitize_text_field( $data['role'] ) );
    update_post_meta( $post_id, 'kg_app_linkedin', esc_url_raw( $data['linkedin'] ) );
    update_post_meta( $post_id, 'kg_app_cv_url',   esc_url_raw( $data['cv_url'] ) );
    update_post_meta( $post_id, 'kg_app_status',   'screening' );
    update_post_meta( $post_id, 'kg_app_client',   '' );

    return $post_id;
}

/* ─────────────────────────────────────────────
   Admin columns
───────────────────────────────────────────── */

/* Helper: canonical ATS status list */
function kg_ats_statuses() {
    return array(
        'screening'    => 'Screening',
        'interviewing' => 'Interviewing',
        'hired'        => 'Hired',
        'deployed'     => 'Deployed',
        'benched'      => 'Benched',
        'blacklisted'  => 'Blacklisted',
    );
}

function kg_application_columns( $columns ) {
    return array(
        'cb'         => '<input type="checkbox">',
        'title'      => 'Applicant Name',
        'kg_email'   => 'Email',
        'kg_role'    => 'Role Applied For',
        'kg_status'  => 'Status',
        'kg_client'  => 'Client Assignment',
        'kg_cv'      => 'CV',
        'date'       => 'Submitted',
    );
}
add_filter( 'manage_kg_application_posts_columns', 'kg_application_columns' );

function kg_application_column_content( $column, $post_id ) {
    switch ( $column ) {

        case 'kg_email':
            $email = get_post_meta( $post_id, 'kg_app_email', true );
            echo $email
                ? '<a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a>'
                : '—';
            break;

        case 'kg_role':
            echo esc_html( get_post_meta( $post_id, 'kg_app_role', true ) ?: 'Not specified' );
            break;

        case 'kg_status':
            $status = get_post_meta( $post_id, 'kg_app_status', true ) ?: 'screening';
            $status_styles = array(
                'screening'    => 'background:#dbeafe;color:#1e40af;',
                'interviewing' => 'background:#ede9fe;color:#6d28d9;',
                'hired'        => 'background:#d1fae5;color:#065f46;',
                'deployed'     => 'background:#dcfce7;color:#15803d;',
                'benched'      => 'background:#fef3c7;color:#92400e;',
                'blacklisted'  => 'background:#fee2e2;color:#991b1b;',
            );
            $s = $status_styles[ $status ] ?? 'background:#f3f4f6;color:#374151;';
            echo '<select class="kg-inline-status" data-post-id="' . esc_attr( $post_id ) . '" data-nonce="' . esc_attr( wp_create_nonce( 'kg_inline_status_' . $post_id ) ) . '" style="padding:4px 8px;border-radius:6px;font-size:12px;font-weight:600;border:2px solid transparent;cursor:pointer;' . $s . '">';
            foreach ( kg_ats_statuses() as $val => $lbl ) {
                echo '<option value="' . esc_attr($val) . '"' . selected( $status, $val, false ) . '>' . esc_html($lbl) . '</option>';
            }
            echo '</select>';
            break;

        case 'kg_client':
            echo esc_html( get_post_meta( $post_id, 'kg_app_client', true ) ?: '—' );
            break;

        case 'kg_cv':
            $cv_url = get_post_meta( $post_id, 'kg_app_cv_url', true );
            echo $cv_url
                ? '<a href="' . esc_url( $cv_url ) . '" target="_blank" class="button button-small">⬇ Download CV</a>'
                : '—';
            break;
    }
}
add_action( 'manage_kg_application_posts_custom_column', 'kg_application_column_content', 10, 2 );

function kg_application_sortable_columns( $columns ) {
    $columns['kg_status'] = 'kg_status';
    $columns['kg_role']   = 'kg_role';
    return $columns;
}
add_filter( 'manage_edit-kg_application_sortable_columns', 'kg_application_sortable_columns' );

/* ─────────────────────────────────────────────
   Meta boxes on edit screen
───────────────────────────────────────────── */

function kg_application_meta_boxes() {
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
}
add_action( 'add_meta_boxes', 'kg_application_meta_boxes' );

function kg_application_details_box( $post ) {
    $email    = get_post_meta( $post->ID, 'kg_app_email',    true );
    $phone    = get_post_meta( $post->ID, 'kg_app_phone',    true );
    $role     = get_post_meta( $post->ID, 'kg_app_role',     true );
    $linkedin = get_post_meta( $post->ID, 'kg_app_linkedin', true );
    $cv_url   = get_post_meta( $post->ID, 'kg_app_cv_url',   true );
    ?>
    <table style="width:100%;border-collapse:collapse;">
        <tr>
            <td style="padding:10px 8px;font-weight:600;width:140px;border-bottom:1px solid #f0f0f0;">Full Name</td>
            <td style="padding:10px 8px;border-bottom:1px solid #f0f0f0;"><?php echo esc_html( get_the_title( $post->ID ) ); ?></td>
        </tr>
        <tr>
            <td style="padding:10px 8px;font-weight:600;border-bottom:1px solid #f0f0f0;">Email</td>
            <td style="padding:10px 8px;border-bottom:1px solid #f0f0f0;">
                <?php if ( $email ) : ?>
                    <a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
                <?php else : ?>—<?php endif; ?>
            </td>
        </tr>
        <tr>
            <td style="padding:10px 8px;font-weight:600;border-bottom:1px solid #f0f0f0;">Phone</td>
            <td style="padding:10px 8px;border-bottom:1px solid #f0f0f0;"><?php echo esc_html( $phone ?: '—' ); ?></td>
        </tr>
        <tr>
            <td style="padding:10px 8px;font-weight:600;border-bottom:1px solid #f0f0f0;">Role Applied For</td>
            <td style="padding:10px 8px;border-bottom:1px solid #f0f0f0;"><?php echo esc_html( $role ?: 'Not specified' ); ?></td>
        </tr>
        <tr>
            <td style="padding:10px 8px;font-weight:600;border-bottom:1px solid #f0f0f0;">LinkedIn</td>
            <td style="padding:10px 8px;border-bottom:1px solid #f0f0f0;">
                <?php if ( $linkedin ) : ?>
                    <a href="<?php echo esc_url( $linkedin ); ?>" target="_blank"><?php echo esc_html( $linkedin ); ?></a>
                <?php else : ?>—<?php endif; ?>
            </td>
        </tr>
        <tr>
            <td style="padding:10px 8px;font-weight:600;">CV File</td>
            <td style="padding:10px 8px;">
                <?php if ( $cv_url ) : ?>
                    <a href="<?php echo esc_url( $cv_url ); ?>" target="_blank" class="button button-primary">⬇ Download CV</a>
                <?php else : ?>—<?php endif; ?>
            </td>
        </tr>
    </table>
    <?php
}

function kg_application_status_box( $post ) {
    wp_nonce_field( 'kg_app_status_save', 'kg_app_status_nonce' );
    $status = get_post_meta( $post->ID, 'kg_app_status', true ) ?: 'screening';
    $client = get_post_meta( $post->ID, 'kg_app_client', true );
    ?>
    <div style="margin-bottom:12px;">
        <label style="font-size:12px;font-weight:600;display:block;margin-bottom:4px;">Pipeline Status</label>
        <select name="kg_app_status" style="width:100%;padding:8px;font-size:14px;">
            <?php foreach ( kg_ats_statuses() as $val => $lbl ) : ?>
            <option value="<?php echo esc_attr($val); ?>" <?php selected( $status, $val ); ?>><?php echo esc_html($lbl); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div style="margin-bottom:12px;">
        <label style="font-size:12px;font-weight:600;display:block;margin-bottom:4px;">Client Assignment</label>
        <input type="text" name="kg_app_client" value="<?php echo esc_attr($client); ?>" placeholder="e.g. Acme Corp" style="width:100%;padding:8px;font-size:14px;">
    </div>
    <p style="font-size:12px;color:#666;margin:0;">Click <strong>Update</strong> to save changes.</p>
    <?php
}

function kg_save_application_status( $post_id ) {
    if ( ! isset( $_POST['kg_app_status_nonce'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['kg_app_status_nonce'], 'kg_app_status_save' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    if ( ! isset( $_POST['kg_app_status'] ) ) return;

    $allowed    = array_keys( kg_ats_statuses() );
    $new_status = in_array( $_POST['kg_app_status'], $allowed, true )
        ? $_POST['kg_app_status']
        : 'screening';
    $old_status = get_post_meta( $post_id, 'kg_app_status', true ) ?: 'screening';

    update_post_meta( $post_id, 'kg_app_status', $new_status );

    if ( isset( $_POST['kg_app_client'] ) ) {
        update_post_meta( $post_id, 'kg_app_client', sanitize_text_field( $_POST['kg_app_client'] ) );
    }

    /* — Email applicant when status changes to hired — */
    if ( $new_status !== $old_status && $new_status === 'hired' ) {
        kg_notify_applicant_status( $post_id, 'accepted' );
    }
    if ( $new_status !== $old_status && $new_status === 'blacklisted' ) {
        kg_notify_applicant_status( $post_id, 'rejected' );
    }
}
add_action( 'save_post_kg_application', 'kg_save_application_status' );

/**
 * Sends a branded status-update email to the applicant.
 */
function kg_notify_applicant_status( $post_id, $status ) {
    require_once get_template_directory() . '/inc/email-templates.php';

    $fname    = explode( ' ', get_the_title( $post_id ) )[0];
    $fullname = get_the_title( $post_id );
    $email    = get_post_meta( $post_id, 'kg_app_email', true );
    $role     = get_post_meta( $post_id, 'kg_app_role',  true ) ?: 'the position';

    if ( ! $email ) return;

    if ( $status === 'accepted' ) {
        $subject = 'Application Status: Accepted — Kings Manpower';
        $body = kg_email_heading( 'Application Status: Accepted' )
            . kg_email_para( 'Dear ' . esc_html($fname) . ',' )
            . kg_email_para( 'We are pleased to inform you that your application for the position of <strong>' . esc_html($role) . '</strong> has been formally <strong style="color:#00D09C;">accepted</strong> by Kings Manpower.' )
            . kg_email_para( 'Our human resources department will contact you imminently to outline the subsequent onboarding procedures, required documentation, and to finalize your commencement date.' )
            . kg_email_banner( 'Welcome to the Kings Manpower organization. We look forward to a mutually rewarding professional relationship.' )
            . kg_email_para( 'Should you have any immediate inquiries, please direct them by replying to this correspondence.' )
            . kg_email_button( 'Visit Kings Manpower', home_url('/') );
    } else {
        $subject = 'Application Status Update — Kings Manpower';
        $body = kg_email_heading( 'Application Status Update' )
            . kg_email_para( 'Dear ' . esc_html($fname) . ',' )
            . kg_email_para( 'We appreciate your interest in joining Kings Manpower and thank you for submitting your application for the position of <strong>' . esc_html($role) . '</strong>.' )
            . kg_email_para( 'Following a thorough evaluation of your qualifications by our talent acquisition team, we have opted to proceed with other candidates whose profiles more closely align with our immediate operational requirements.' )
            . kg_email_banner( 'We will retain your Curriculum Vitae in our secure database and may contact you should a suitable opportunity arise in the future.' )
            . kg_email_para( 'We thank you for the time invested in this process and wish you continued success in your professional endeavors.' )
            . kg_email_button( 'View Other Opportunities', home_url('/jobs/') );
    }

    wp_mail(
        $email,
        $subject,
        kg_email_wrap( $subject, $body ),
        array( 'Content-Type: text/html; charset=UTF-8' )
    );
}

/* ─────────────────────────────────────────────
   Filter by status in admin list
───────────────────────────────────────────── */

function kg_application_status_filter( $post_type ) {
    if ( $post_type !== 'kg_application' ) return;

    $current = $_GET['kg_status_filter'] ?? '';
    ?>
    <select name="kg_status_filter">
        <option value="">All Statuses</option>
        <?php foreach ( kg_ats_statuses() as $val => $lbl ) : ?>
        <option value="<?php echo esc_attr($val); ?>" <?php selected( $current, $val ); ?>><?php echo esc_html($lbl); ?></option>
        <?php endforeach; ?>
    </select>
    <?php
}
add_action( 'restrict_manage_posts', 'kg_application_status_filter' );

function kg_application_status_filter_query( $query ) {
    global $pagenow;
    if (
        is_admin() &&
        $pagenow === 'edit.php' &&
        ( $_GET['post_type'] ?? '' ) === 'kg_application' &&
        ! empty( $_GET['kg_status_filter'] )
    ) {
        $allowed = array( 'pending', 'accepted', 'rejected' );
        $filter  = $_GET['kg_status_filter'];
        if ( in_array( $filter, $allowed, true ) ) {
            $query->set( 'meta_query', array(
                array(
                    'key'   => 'kg_app_status',
                    'value' => $filter,
                ),
            ) );
        }
    }
}
add_action( 'pre_get_posts', 'kg_application_status_filter_query' );

/* ─────────────────────────────────────────────
   AJAX: inline status change from list view
───────────────────────────────────────────── */

function kg_ajax_inline_status() {
    $post_id    = absint( $_POST['post_id'] ?? 0 );
    $new_status = sanitize_text_field( $_POST['status'] ?? '' );
    $nonce      = $_POST['nonce'] ?? '';

    if ( ! wp_verify_nonce( $nonce, 'kg_inline_status_' . $post_id ) ) {
        wp_send_json_error( 'Security check failed.' );
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        wp_send_json_error( 'Permission denied.' );
    }

    $allowed = array_keys( kg_ats_statuses() );
    if ( ! in_array( $new_status, $allowed, true ) ) {
        wp_send_json_error( 'Invalid status.' );
    }

    $old_status = get_post_meta( $post_id, 'kg_app_status', true ) ?: 'screening';
    update_post_meta( $post_id, 'kg_app_status', $new_status );

    if ( $new_status !== $old_status && $new_status === 'hired' ) {
        kg_notify_applicant_status( $post_id, 'accepted' );
    }
    if ( $new_status !== $old_status && $new_status === 'blacklisted' ) {
        kg_notify_applicant_status( $post_id, 'rejected' );
    }

    wp_send_json_success( array( 'status' => $new_status ) );
}
add_action( 'wp_ajax_kg_inline_status', 'kg_ajax_inline_status' );

/* ─────────────────────────────────────────────
   Bulk actions: Accept / Reject selected
───────────────────────────────────────────── */

function kg_application_bulk_actions( $actions ) {
    $actions['kg_bulk_accept'] = 'Mark as Accepted';
    $actions['kg_bulk_reject'] = 'Mark as Rejected';
    return $actions;
}
add_filter( 'bulk_actions-edit-kg_application', 'kg_application_bulk_actions' );

function kg_application_bulk_action_handler( $redirect, $action, $post_ids ) {
    if ( ! in_array( $action, array( 'kg_bulk_accept', 'kg_bulk_reject' ), true ) ) {
        return $redirect;
    }
    $new_status = $action === 'kg_bulk_accept' ? 'hired' : 'blacklisted';

    foreach ( $post_ids as $post_id ) {
        $old_status = get_post_meta( $post_id, 'kg_app_status', true ) ?: 'screening';
        update_post_meta( $post_id, 'kg_app_status', $new_status );
        if ( $new_status !== $old_status ) {
            $email_status = $new_status === 'hired' ? 'accepted' : 'rejected';
            kg_notify_applicant_status( $post_id, $email_status );
        }
    }

    return add_query_arg( 'kg_bulk_done', count( $post_ids ), $redirect );
}
add_filter( 'handle_bulk_actions-edit-kg_application', 'kg_application_bulk_action_handler', 10, 3 );

function kg_application_bulk_notice() {
    if ( ! empty( $_GET['kg_bulk_done'] ) ) {
        $count = absint( $_GET['kg_bulk_done'] );
        echo '<div class="notice notice-success is-dismissible"><p>'
            . sprintf( '%d application(s) updated and applicants notified by email.', $count )
            . '</p></div>';
    }
}
add_action( 'admin_notices', 'kg_application_bulk_notice' );

/* ─────────────────────────────────────────────
   Admin JS: inline dropdown + hide Add New
───────────────────────────────────────────── */

function kg_application_admin_scripts( $hook ) {
    global $post_type;
    if ( $hook !== 'edit.php' || $post_type !== 'kg_application' ) return;
    ?>
    <style>
        .page-title-action { display: none !important; }
        .kg-inline-status:focus { outline: 2px solid #2271b1; }
        .kg-status-saving { opacity: 0.5; pointer-events: none; }
    </style>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.kg-inline-status').forEach(function (select) {
            select.addEventListener('change', function () {
                var postId  = this.dataset.postId;
                var nonce   = this.dataset.nonce;
                var status  = this.value;
                var el      = this;

                el.classList.add('kg-status-saving');

                var body = new FormData();
                body.append('action',  'kg_inline_status');
                body.append('post_id', postId);
                body.append('status',  status);
                body.append('nonce',   nonce);

                fetch(ajaxurl, { method: 'POST', body: body })
                    .then(function (r) { return r.json(); })
                    .then(function (data) {
                        if (data.success) {
                            var colors = {
                                pending:  'background:#fef3c7;color:#92400e;',
                                accepted: 'background:#d1fae5;color:#065f46;',
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
add_action( 'admin_footer', 'kg_application_admin_scripts' );
