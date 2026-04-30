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
    update_post_meta( $post_id, 'kg_app_status',   'pending' );

    return $post_id;
}

/* ─────────────────────────────────────────────
   Admin columns
───────────────────────────────────────────── */

function kg_application_columns( $columns ) {
    return array(
        'cb'         => '<input type="checkbox">',
        'title'      => 'Applicant Name',
        'kg_email'   => 'Email',
        'kg_role'    => 'Role Applied For',
        'kg_status'  => 'Status',
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
            $status = get_post_meta( $post_id, 'kg_app_status', true ) ?: 'pending';
            // Inline dropdown — change status directly from the list view
            echo '<select class="kg-inline-status" data-post-id="' . esc_attr( $post_id ) . '" data-nonce="' . esc_attr( wp_create_nonce( 'kg_inline_status_' . $post_id ) ) . '"
                style="padding:4px 8px;border-radius:6px;font-size:12px;font-weight:600;border:2px solid transparent;cursor:pointer;'
                . ( $status === 'accepted' ? 'background:#d1fae5;color:#065f46;' : ( $status === 'rejected' ? 'background:#fee2e2;color:#991b1b;' : 'background:#fef3c7;color:#92400e;' ) ) . '">';
            foreach ( array( 'pending' => '🕐 Pending', 'accepted' => '✅ Accepted', 'rejected' => '❌ Rejected' ) as $val => $label ) {
                echo '<option value="' . esc_attr($val) . '"' . selected( $status, $val, false ) . '>' . esc_html($label) . '</option>';
            }
            echo '</select>';
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
    $status = get_post_meta( $post->ID, 'kg_app_status', true ) ?: 'pending';
    ?>
    <div style="margin-bottom:12px;">
        <select name="kg_app_status" style="width:100%;padding:8px;font-size:14px;">
            <option value="pending"  <?php selected( $status, 'pending' );  ?>>🕐 Pending</option>
            <option value="accepted" <?php selected( $status, 'accepted' ); ?>>✅ Accepted</option>
            <option value="rejected" <?php selected( $status, 'rejected' ); ?>>❌ Rejected</option>
        </select>
    </div>
    <p style="font-size:12px;color:#666;margin:0;">Click <strong>Update</strong> to save the new status.</p>
    <?php
}

function kg_save_application_status( $post_id ) {
    if ( ! isset( $_POST['kg_app_status_nonce'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['kg_app_status_nonce'], 'kg_app_status_save' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    if ( ! isset( $_POST['kg_app_status'] ) ) return;

    $allowed    = array( 'pending', 'accepted', 'rejected' );
    $new_status = in_array( $_POST['kg_app_status'], $allowed, true )
        ? $_POST['kg_app_status']
        : 'pending';
    $old_status = get_post_meta( $post_id, 'kg_app_status', true ) ?: 'pending';

    update_post_meta( $post_id, 'kg_app_status', $new_status );

    /* — Email applicant when status changes to accepted or rejected — */
    if ( $new_status !== $old_status && in_array( $new_status, array( 'accepted', 'rejected' ), true ) ) {
        kg_notify_applicant_status( $post_id, $new_status );
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
        $subject = 'Congratulations! Your application has been accepted — Kings Group';
        $body = kg_email_heading( 'You\'ve Been Accepted! 🎉' )
            . kg_email_para( 'Hi ' . esc_html($fname) . ',' )
            . kg_email_para( 'We are thrilled to inform you that your application for <strong>' . esc_html($role) . '</strong> has been <strong style="color:#065f46;">accepted</strong>.' )
            . kg_email_para( 'Our team will reach out to you shortly with the next steps, including onboarding details and your start date.' )
            . kg_email_banner( 'Welcome to the Kings Group family! We are excited to have you on board.' )
            . kg_email_para( 'If you have any questions in the meantime, simply reply to this email.' )
            . kg_email_button( 'Visit Kings Group', home_url('/') );
    } else {
        $subject = 'Update on your Kings Group application';
        $body = kg_email_heading( 'Application Update' )
            . kg_email_para( 'Hi ' . esc_html($fname) . ',' )
            . kg_email_para( 'Thank you for your interest in joining Kings Group Cooperative and for taking the time to apply for <strong>' . esc_html($role) . '</strong>.' )
            . kg_email_para( 'After careful consideration, we regret to inform you that we will not be moving forward with your application at this time.' )
            . kg_email_banner( 'We encourage you to apply again in the future — we regularly open new positions.' )
            . kg_email_para( 'We appreciate the time you invested and wish you the best in your career.' )
            . kg_email_button( 'Browse Open Positions', home_url('/jobs/') );
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
        <option value="pending"  <?php selected( $current, 'pending' );  ?>>Pending</option>
        <option value="accepted" <?php selected( $current, 'accepted' ); ?>>Accepted</option>
        <option value="rejected" <?php selected( $current, 'rejected' ); ?>>Rejected</option>
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

    $allowed = array( 'pending', 'accepted', 'rejected' );
    if ( ! in_array( $new_status, $allowed, true ) ) {
        wp_send_json_error( 'Invalid status.' );
    }

    $old_status = get_post_meta( $post_id, 'kg_app_status', true ) ?: 'pending';
    update_post_meta( $post_id, 'kg_app_status', $new_status );

    if ( $new_status !== $old_status && in_array( $new_status, array( 'accepted', 'rejected' ), true ) ) {
        kg_notify_applicant_status( $post_id, $new_status );
    }

    wp_send_json_success( array( 'status' => $new_status ) );
}
add_action( 'wp_ajax_kg_inline_status', 'kg_ajax_inline_status' );

/* ─────────────────────────────────────────────
   Bulk actions: Accept / Reject selected
───────────────────────────────────────────── */

function kg_application_bulk_actions( $actions ) {
    $actions['kg_bulk_accept'] = '✅ Mark as Accepted';
    $actions['kg_bulk_reject'] = '❌ Mark as Rejected';
    return $actions;
}
add_filter( 'bulk_actions-edit-kg_application', 'kg_application_bulk_actions' );

function kg_application_bulk_action_handler( $redirect, $action, $post_ids ) {
    if ( ! in_array( $action, array( 'kg_bulk_accept', 'kg_bulk_reject' ), true ) ) {
        return $redirect;
    }
    $new_status = $action === 'kg_bulk_accept' ? 'accepted' : 'rejected';

    foreach ( $post_ids as $post_id ) {
        $old_status = get_post_meta( $post_id, 'kg_app_status', true ) ?: 'pending';
        update_post_meta( $post_id, 'kg_app_status', $new_status );
        if ( $new_status !== $old_status ) {
            kg_notify_applicant_status( $post_id, $new_status );
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
