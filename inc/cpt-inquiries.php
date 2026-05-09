<?php
/**
 * CPT: kg_inquiry — stores Contact form submissions in WP Admin.
 * CPT: kg_quote_lead — stores Quote/Team Builder submissions in WP Admin.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

/* ═══════════════════════════════════════════════
   CONTACT INQUIRIES CPT
═══════════════════════════════════════════════ */

function kg_register_inquiry_cpt() {
    register_post_type( 'kg_inquiry', array(
        'labels' => array(
            'name'               => 'Contact Inquiries',
            'singular_name'      => 'Inquiry',
            'menu_name'          => 'Inquiries',
            'all_items'          => 'All Inquiries',
            'edit_item'          => 'View Inquiry',
            'not_found'          => 'No inquiries found.',
            'not_found_in_trash' => 'No inquiries in trash.',
        ),
        'public'        => false,
        'show_ui'       => true,
        'show_in_menu'  => true,
        'show_in_rest'  => false,
        'supports'      => array( 'title' ),
        'has_archive'   => false,
        'rewrite'       => false,
        'menu_icon'     => 'dashicons-email-alt',
        'menu_position' => 6,
        'capabilities'  => array( 'create_posts' => 'do_not_allow' ),
        'map_meta_cap'  => true,
    ) );
}
add_action( 'init', 'kg_register_inquiry_cpt' );

function kg_save_inquiry_post( $data ) {
    $post_id = wp_insert_post( array(
        'post_title'  => sanitize_text_field( $data['name'] ) . ' — ' . sanitize_text_field( $data['subject'] ),
        'post_status' => 'publish',
        'post_type'   => 'kg_inquiry',
        'post_date'   => current_time( 'mysql' ),
    ) );
    if ( is_wp_error( $post_id ) ) return false;

    update_post_meta( $post_id, 'kg_inq_name',    sanitize_text_field( $data['name'] ) );
    update_post_meta( $post_id, 'kg_inq_email',   sanitize_email( $data['email'] ) );
    update_post_meta( $post_id, 'kg_inq_subject', sanitize_text_field( $data['subject'] ) );
    update_post_meta( $post_id, 'kg_inq_message', sanitize_textarea_field( $data['message'] ) );
    update_post_meta( $post_id, 'kg_inq_status',  'new' );
    return $post_id;
}

/* — Admin columns — */
function kg_inquiry_columns( $columns ) {
    return array(
        'cb'           => '<input type="checkbox">',
        'title'        => 'Name — Subject',
        'kg_inq_email' => 'Email',
        'kg_inq_msg'   => 'Message',
        'kg_inq_status'=> 'Status',
        'date'         => 'Received',
    );
}
add_filter( 'manage_kg_inquiry_posts_columns', 'kg_inquiry_columns' );

function kg_inquiry_column_content( $column, $post_id ) {
    switch ( $column ) {
        case 'kg_inq_email':
            $e = get_post_meta( $post_id, 'kg_inq_email', true );
            echo $e ? '<a href="mailto:' . esc_attr($e) . '">' . esc_html($e) . '</a>' : '—';
            break;
        case 'kg_inq_msg':
            $msg = get_post_meta( $post_id, 'kg_inq_message', true );
            echo '<span style="color:#555;font-size:13px;">' . esc_html( wp_trim_words( $msg, 12 ) ) . '</span>';
            break;
        case 'kg_inq_status':
            $status = get_post_meta( $post_id, 'kg_inq_status', true ) ?: 'new';
            $styles = array(
                'new'         => 'background:#dbeafe;color:#1e40af;',
                'in_progress' => 'background:#fef3c7;color:#92400e;',
                'resolved'    => 'background:#d1fae5;color:#065f46;',
            );
            $labels = array( 'new' => '🔵 New', 'in_progress' => '🕐 In Progress', 'resolved' => '✅ Resolved' );
            $style  = $styles[$status] ?? $styles['new'];
            echo '<select class="kg-inq-status" data-post-id="' . esc_attr($post_id) . '" data-nonce="' . esc_attr( wp_create_nonce('kg_inq_status_'.$post_id) ) . '"
                style="padding:4px 8px;border-radius:6px;font-size:12px;font-weight:600;border:2px solid transparent;cursor:pointer;' . $style . '">';
            foreach ( array( 'new' => '🔵 New', 'in_progress' => '🕐 In Progress', 'resolved' => '✅ Resolved' ) as $val => $label ) {
                echo '<option value="' . esc_attr($val) . '"' . selected($status,$val,false) . '>' . esc_html($label) . '</option>';
            }
            echo '</select>';
            break;
    }
}
add_action( 'manage_kg_inquiry_posts_custom_column', 'kg_inquiry_column_content', 10, 2 );

/* — Meta box — */
add_action( 'add_meta_boxes', function() {
    add_meta_box( 'kg_inq_details', 'Inquiry Details', 'kg_inquiry_details_box', 'kg_inquiry', 'normal', 'high' );
} );

function kg_inquiry_details_box( $post ) {
    $name    = get_post_meta( $post->ID, 'kg_inq_name',    true );
    $email   = get_post_meta( $post->ID, 'kg_inq_email',   true );
    $subject = get_post_meta( $post->ID, 'kg_inq_subject', true );
    $message = get_post_meta( $post->ID, 'kg_inq_message', true );
    $status  = get_post_meta( $post->ID, 'kg_inq_status',  true ) ?: 'new';
    wp_nonce_field( 'kg_inq_status_save', 'kg_inq_status_nonce' );
    ?>
    <table style="width:100%;border-collapse:collapse;">
        <tr><td style="padding:10px 8px;font-weight:600;width:140px;border-bottom:1px solid #f0f0f0;">Name</td>
            <td style="padding:10px 8px;border-bottom:1px solid #f0f0f0;"><?php echo esc_html($name); ?></td></tr>
        <tr><td style="padding:10px 8px;font-weight:600;border-bottom:1px solid #f0f0f0;">Email</td>
            <td style="padding:10px 8px;border-bottom:1px solid #f0f0f0;">
                <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a></td></tr>
        <tr><td style="padding:10px 8px;font-weight:600;border-bottom:1px solid #f0f0f0;">Subject</td>
            <td style="padding:10px 8px;border-bottom:1px solid #f0f0f0;"><?php echo esc_html($subject); ?></td></tr>
        <tr><td style="padding:10px 8px;font-weight:600;border-bottom:1px solid #f0f0f0;">Message</td>
            <td style="padding:10px 8px;border-bottom:1px solid #f0f0f0;white-space:pre-wrap;"><?php echo esc_html($message); ?></td></tr>
        <tr><td style="padding:10px 8px;font-weight:600;">Status</td>
            <td style="padding:10px 8px;">
                <select name="kg_inq_status" style="padding:6px 10px;font-size:13px;width:200px;">
                    <option value="new"         <?php selected($status,'new');         ?>>🔵 New</option>
                    <option value="in_progress" <?php selected($status,'in_progress'); ?>>🕐 In Progress</option>
                    <option value="resolved"    <?php selected($status,'resolved');    ?>>✅ Resolved</option>
                </select>
            </td></tr>
    </table>
    <?php
}

add_action( 'save_post_kg_inquiry', function( $post_id ) {
    if ( ! isset($_POST['kg_inq_status_nonce']) ) return;
    if ( ! wp_verify_nonce($_POST['kg_inq_status_nonce'], 'kg_inq_status_save') ) return;
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
    if ( ! current_user_can('edit_post', $post_id) ) return;
    $allowed = array('new','in_progress','resolved');
    if ( isset($_POST['kg_inq_status']) && in_array($_POST['kg_inq_status'], $allowed, true) ) {
        $old_status = get_post_meta( $post_id, 'kg_inq_status', true ) ?: 'new';
        $new_status = $_POST['kg_inq_status'];
        update_post_meta( $post_id, 'kg_inq_status', $new_status );
        if ( $new_status !== $old_status ) {
            kg_notify_inquiry_status( $post_id, $new_status );
        }
    }
} );

/* — AJAX inline status — */
function kg_ajax_inq_status() {
    $post_id = absint( $_POST['post_id'] ?? 0 );
    $status  = sanitize_text_field( $_POST['status'] ?? '' );
    $nonce   = $_POST['nonce'] ?? '';
    if ( ! wp_verify_nonce($nonce, 'kg_inq_status_'.$post_id) ) wp_send_json_error('Security check failed.');
    if ( ! current_user_can('edit_post', $post_id) ) wp_send_json_error('Permission denied.');
    $allowed = array('new','in_progress','resolved');
    if ( ! in_array($status, $allowed, true) ) wp_send_json_error('Invalid status.');
    
    $old_status = get_post_meta( $post_id, 'kg_inq_status', true ) ?: 'new';
    update_post_meta( $post_id, 'kg_inq_status', $status );
    
    if ( $status !== $old_status ) {
        kg_notify_inquiry_status( $post_id, $status );
    }
    
    wp_send_json_success( array('status' => $status) );
}
add_action( 'wp_ajax_kg_inq_status', 'kg_ajax_inq_status' );

/**
 * Sends a branded status-update email for inquiries.
 */
function kg_notify_inquiry_status( $post_id, $status ) {
    require_once get_template_directory() . '/inc/email-templates.php';

    $name    = get_post_meta( $post_id, 'kg_inq_name',    true );
    $email   = get_post_meta( $post_id, 'kg_inq_email',   true );
    $subject = get_post_meta( $post_id, 'kg_inq_subject', true );
    $fname   = explode( ' ', $name )[0];

    if ( ! $email ) return;

    if ( $status === 'in_progress' ) {
        $mail_subject = 'Inquiry Update: In Progress — Kings Manpower';
        $body = kg_email_heading( 'Inquiry Status: In Progress' )
            . kg_email_para( 'Dear ' . esc_html($fname) . ',' )
            . kg_email_para( 'This correspondence serves to inform you that your inquiry regarding <strong>"' . esc_html($subject) . '"</strong> is currently under active review by our assigned specialists.' )
            . kg_email_para( 'A representative will communicate our findings or request additional information shortly.' )
            . kg_email_banner( 'We appreciate your patience as we ensure a comprehensive resolution to your request.' )
            . kg_email_button( 'Visit Kings Manpower', home_url('/') );
    } elseif ( $status === 'resolved' ) {
        $mail_subject = 'Inquiry Update: Resolved — Kings Manpower';
        $body = kg_email_heading( 'Inquiry Status: Resolved' )
            . kg_email_para( 'Dear ' . esc_html($fname) . ',' )
            . kg_email_para( 'We are writing to confirm that your inquiry regarding <strong>"' . esc_html($subject) . '"</strong> has been marked as <strong style="color:#00D09C;">resolved</strong> within our system.' )
            . kg_email_para( 'We trust our representatives have addressed your concerns satisfactorily. Should you require further assistance or clarification, please do not hesitate to initiate a new inquiry or reply to this correspondence.' )
            . kg_email_banner( 'Thank you for choosing Kings Manpower as your trusted partner.' )
            . kg_email_button( 'Visit Kings Manpower', home_url('/') );
    } else {
        return; // Don't send emails for 'new'
    }

    wp_mail(
        $email,
        $mail_subject,
        kg_email_wrap( $mail_subject, $body ),
        array( 'Content-Type: text/html; charset=UTF-8' )
    );
}

/* — Filter + hide Add New — */
add_action( 'restrict_manage_posts', function($pt) {
    if ($pt !== 'kg_inquiry') return;
    $cur = $_GET['kg_inq_filter'] ?? '';
    echo '<select name="kg_inq_filter">
        <option value="">All Statuses</option>
        <option value="new"'         . selected($cur,'new',false)         . '>New</option>
        <option value="in_progress"' . selected($cur,'in_progress',false) . '>In Progress</option>
        <option value="resolved"'    . selected($cur,'resolved',false)    . '>Resolved</option>
    </select>';
} );
add_action( 'pre_get_posts', function($query) {
    global $pagenow;
    if ( ! is_admin() || $pagenow !== 'edit.php' || ($query->get('post_type') ?? '') !== 'kg_inquiry' || empty($_GET['kg_inq_filter']) ) return;
    $allowed = array('new','in_progress','resolved');
    if ( in_array($_GET['kg_inq_filter'], $allowed, true) ) {
        $query->set('meta_query', array(array('key'=>'kg_inq_status','value'=>$_GET['kg_inq_filter'])));
    }
} );
add_action( 'admin_footer', function() {
    global $post_type, $pagenow;
    if ( $pagenow !== 'edit.php' || ! in_array($post_type, array('kg_inquiry','kg_quote_lead'), true) ) return;
    ?>
    <style>.page-title-action{display:none!important;}</style>
    <script>
    document.addEventListener('DOMContentLoaded',function(){
        var colors = {
            new:         'background:#dbeafe;color:#1e40af;',
            in_progress: 'background:#fef3c7;color:#92400e;',
            resolved:    'background:#d1fae5;color:#065f46;',
            pending:     'background:#fef3c7;color:#92400e;',
            contacted:   'background:#dbeafe;color:#1e40af;',
            converted:   'background:#d1fae5;color:#065f46;',
            closed:      'background:#fee2e2;color:#991b1b;',
        };
        document.querySelectorAll('.kg-inq-status,.kg-quote-status').forEach(function(sel){
            sel.addEventListener('change',function(){
                var postId=this.dataset.postId, nonce=this.dataset.nonce, status=this.value, el=this;
                el.style.opacity='0.5'; el.disabled=true;
                var action = el.classList.contains('kg-inq-status') ? 'kg_inq_status' : 'kg_quote_status';
                var b=new FormData(); b.append('action',action); b.append('post_id',postId); b.append('status',status); b.append('nonce',nonce);
                fetch(ajaxurl,{method:'POST',body:b}).then(r=>r.json()).then(function(d){
                    if(d.success){
                        el.style.cssText='padding:4px 8px;border-radius:6px;font-size:12px;font-weight:600;border:2px solid transparent;cursor:pointer;'+(colors[status]||'');
                    } else { alert('Failed to update.'); location.reload(); }
                }).catch(function(){ location.reload(); })
                .finally(function(){ el.style.opacity='1'; el.disabled=false; });
            });
        });
    });
    </script>
    <?php
} );


/* ═══════════════════════════════════════════════
   QUOTE LEADS CPT
═══════════════════════════════════════════════ */

function kg_register_quote_lead_cpt() {
    register_post_type( 'kg_quote_lead', array(
        'labels' => array(
            'name'               => 'Quote Requests',
            'singular_name'      => 'Quote Request',
            'menu_name'          => 'Quote Requests',
            'all_items'          => 'All Quote Requests',
            'edit_item'          => 'View Quote Request',
            'not_found'          => 'No quote requests found.',
            'not_found_in_trash' => 'No quote requests in trash.',
        ),
        'public'        => false,
        'show_ui'       => true,
        'show_in_menu'  => true,
        'show_in_rest'  => false,
        'supports'      => array( 'title' ),
        'has_archive'   => false,
        'rewrite'       => false,
        'menu_icon'     => 'dashicons-chart-bar',
        'menu_position' => 7,
        'capabilities'  => array( 'create_posts' => 'do_not_allow' ),
        'map_meta_cap'  => true,
    ) );
}
add_action( 'init', 'kg_register_quote_lead_cpt' );

function kg_save_quote_lead_post( $data ) {
    $post_id = wp_insert_post( array(
        'post_title'  => sanitize_text_field( $data['name'] ) . ' — $' . number_format( floatval($data['total']), 0 ) . '/mo',
        'post_status' => 'publish',
        'post_type'   => 'kg_quote_lead',
        'post_date'   => current_time( 'mysql' ),
    ) );
    if ( is_wp_error($post_id) ) return false;

    update_post_meta( $post_id, 'kg_quote_name',   sanitize_text_field( $data['name'] ) );
    update_post_meta( $post_id, 'kg_quote_email',  sanitize_email( $data['email'] ) );
    update_post_meta( $post_id, 'kg_quote_total',  floatval( $data['total'] ) );
    update_post_meta( $post_id, 'kg_quote_roles',  wp_json_encode( $data['roles'] ) );
    update_post_meta( $post_id, 'kg_quote_status', 'pending' );
    return $post_id;
}

/* — Admin columns — */
function kg_quote_lead_columns( $columns ) {
    return array(
        'cb'              => '<input type="checkbox">',
        'title'           => 'Client — Est. Total',
        'kg_quote_email'  => 'Email',
        'kg_quote_roles'  => 'Roles',
        'kg_quote_total'  => 'Est. Monthly',
        'kg_quote_status' => 'Status',
        'date'            => 'Submitted',
    );
}
add_filter( 'manage_kg_quote_lead_posts_columns', 'kg_quote_lead_columns' );

function kg_quote_lead_column_content( $column, $post_id ) {
    switch ( $column ) {
        case 'kg_quote_email':
            $e = get_post_meta( $post_id, 'kg_quote_email', true );
            echo $e ? '<a href="mailto:' . esc_attr($e) . '">' . esc_html($e) . '</a>' : '—';
            break;
        case 'kg_quote_roles':
            $roles = json_decode( get_post_meta($post_id,'kg_quote_roles',true), true );
            if ( ! empty($roles) ) {
                $summary = array_map( function($r){ return esc_html($r['role']) . ' ×' . intval($r['qty']); }, $roles );
                echo '<span style="font-size:12px;color:#555;">' . implode(', ', $summary) . '</span>';
            } else { echo '—'; }
            break;
        case 'kg_quote_total':
            $total = get_post_meta( $post_id, 'kg_quote_total', true );
            echo $total ? '<strong style="color:#0A2540;">$' . number_format($total,0) . '/mo</strong>' : '—';
            break;
        case 'kg_quote_status':
            $status = get_post_meta( $post_id, 'kg_quote_status', true ) ?: 'pending';
            $styles = array(
                'pending'   => 'background:#fef3c7;color:#92400e;',
                'contacted' => 'background:#dbeafe;color:#1e40af;',
                'converted' => 'background:#d1fae5;color:#065f46;',
                'closed'    => 'background:#fee2e2;color:#991b1b;',
            );
            $style = $styles[$status] ?? $styles['pending'];
            echo '<select class="kg-quote-status" data-post-id="' . esc_attr($post_id) . '" data-nonce="' . esc_attr(wp_create_nonce('kg_quote_status_'.$post_id)) . '"
                style="padding:4px 8px;border-radius:6px;font-size:12px;font-weight:600;border:2px solid transparent;cursor:pointer;' . $style . '">';
            foreach ( array('pending'=>'🕐 Pending','contacted'=>'📞 Contacted','converted'=>'✅ Converted','closed'=>'❌ Closed') as $val=>$label ) {
                echo '<option value="' . esc_attr($val) . '"' . selected($status,$val,false) . '>' . esc_html($label) . '</option>';
            }
            echo '</select>';
            break;
    }
}
add_action( 'manage_kg_quote_lead_posts_custom_column', 'kg_quote_lead_column_content', 10, 2 );

/* — Meta box — */
add_action( 'add_meta_boxes', function() {
    add_meta_box( 'kg_quote_details', 'Quote Details', 'kg_quote_lead_details_box', 'kg_quote_lead', 'normal', 'high' );
    add_meta_box( 'kg_quote_status_box', 'Lead Status', 'kg_quote_lead_status_box', 'kg_quote_lead', 'side', 'high' );
} );

function kg_quote_lead_details_box( $post ) {
    $name   = get_post_meta( $post->ID, 'kg_quote_name',  true );
    $email  = get_post_meta( $post->ID, 'kg_quote_email', true );
    $total  = get_post_meta( $post->ID, 'kg_quote_total', true );
    $roles  = json_decode( get_post_meta($post->ID,'kg_quote_roles',true), true ) ?: array();
    ?>
    <table style="width:100%;border-collapse:collapse;margin-bottom:20px;">
        <tr><td style="padding:10px 8px;font-weight:600;width:140px;border-bottom:1px solid #f0f0f0;">Client Name</td>
            <td style="padding:10px 8px;border-bottom:1px solid #f0f0f0;"><?php echo esc_html($name); ?></td></tr>
        <tr><td style="padding:10px 8px;font-weight:600;border-bottom:1px solid #f0f0f0;">Email</td>
            <td style="padding:10px 8px;border-bottom:1px solid #f0f0f0;">
                <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a></td></tr>
        <tr><td style="padding:10px 8px;font-weight:600;">Est. Monthly Total</td>
            <td style="padding:10px 8px;font-size:18px;font-weight:700;color:#0A2540;">
                $<?php echo number_format($total,0); ?>/mo</td></tr>
    </table>
    <?php if ( ! empty($roles) ) : ?>
    <h4 style="margin:0 0 10px;font-size:13px;text-transform:uppercase;letter-spacing:0.05em;color:#6b7280;">Team Configuration</h4>
    <table style="width:100%;border-collapse:collapse;border:1px solid #e8ecf0;border-radius:6px;overflow:hidden;">
        <thead><tr style="background:#0A2540;">
            <th style="padding:10px 14px;font-size:12px;color:rgba(255,255,255,0.7);text-align:left;">Role</th>
            <th style="padding:10px 14px;font-size:12px;color:rgba(255,255,255,0.7);text-align:center;">Level / Qty</th>
            <th style="padding:10px 14px;font-size:12px;color:rgba(255,255,255,0.7);text-align:right;">Unit/mo</th>
            <th style="padding:10px 14px;font-size:12px;color:rgba(255,255,255,0.7);text-align:right;">Subtotal</th>
        </tr></thead>
        <tbody>
        <?php foreach ($roles as $role) : ?>
            <tr>
                <td style="padding:10px 14px;font-size:13px;border-bottom:1px solid #f0f0f0;"><?php echo esc_html($role['role'] ?? ''); ?></td>
                <td style="padding:10px 14px;font-size:13px;text-align:center;border-bottom:1px solid #f0f0f0;"><?php echo esc_html($role['level'] ?? ''); ?> &times; <?php echo intval($role['qty'] ?? 1); ?></td>
                <td style="padding:10px 14px;font-size:13px;text-align:right;border-bottom:1px solid #f0f0f0;">$<?php echo number_format($role['unit_price'] ?? 0,0); ?></td>
                <td style="padding:10px 14px;font-size:13px;font-weight:600;text-align:right;border-bottom:1px solid #f0f0f0;">$<?php echo number_format($role['subtotal'] ?? 0,0); ?></td>
            </tr>
        <?php endforeach; ?>
        <tr style="background:#f0fdf9;">
            <td colspan="3" style="padding:12px 14px;font-weight:700;text-align:right;color:#0A2540;">Estimated Monthly Total</td>
            <td style="padding:12px 14px;font-size:16px;font-weight:700;text-align:right;color:#00D09C;">$<?php echo number_format($total,0); ?></td>
        </tr>
        </tbody>
    </table>
    <?php endif;
}

function kg_quote_lead_status_box( $post ) {
    wp_nonce_field( 'kg_quote_status_save', 'kg_quote_status_nonce' );
    $status = get_post_meta( $post->ID, 'kg_quote_status', true ) ?: 'pending';
    ?>
    <select name="kg_quote_status" style="width:100%;padding:8px;font-size:14px;margin-bottom:8px;">
        <option value="pending"   <?php selected($status,'pending');   ?>>🕐 Pending</option>
        <option value="contacted" <?php selected($status,'contacted'); ?>>📞 Contacted</option>
        <option value="converted" <?php selected($status,'converted'); ?>>✅ Converted</option>
        <option value="closed"    <?php selected($status,'closed');    ?>>❌ Closed</option>
    </select>
    <p style="font-size:12px;color:#666;margin:0;">Click <strong>Update</strong> to save.</p>
    <?php
}

add_action( 'save_post_kg_quote_lead', function($post_id) {
    if ( ! isset($_POST['kg_quote_status_nonce']) ) return;
    if ( ! wp_verify_nonce($_POST['kg_quote_status_nonce'],'kg_quote_status_save') ) return;
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
    if ( ! current_user_can('edit_post',$post_id) ) return;
    $allowed = array('pending','contacted','converted','closed');
    if ( isset($_POST['kg_quote_status']) && in_array($_POST['kg_quote_status'],$allowed,true) ) {
        $old_status = get_post_meta( $post_id, 'kg_quote_status', true ) ?: 'pending';
        $new_status = $_POST['kg_quote_status'];
        update_post_meta( $post_id, 'kg_quote_status', $new_status );
        if ( $new_status !== $old_status ) {
            kg_notify_quote_status( $post_id, $new_status );
        }
    }
} );

/* — AJAX inline status — */
function kg_ajax_quote_status() {
    $post_id = absint( $_POST['post_id'] ?? 0 );
    $status  = sanitize_text_field( $_POST['status'] ?? '' );
    $nonce   = $_POST['nonce'] ?? '';
    if ( ! wp_verify_nonce($nonce,'kg_quote_status_'.$post_id) ) wp_send_json_error('Security check failed.');
    if ( ! current_user_can('edit_post',$post_id) ) wp_send_json_error('Permission denied.');
    $allowed = array('pending','contacted','converted','closed');
    if ( ! in_array($status,$allowed,true) ) wp_send_json_error('Invalid status.');
    
    $old_status = get_post_meta( $post_id, 'kg_quote_status', true ) ?: 'pending';
    update_post_meta( $post_id, 'kg_quote_status', $status );
    
    if ( $status !== $old_status ) {
        kg_notify_quote_status( $post_id, $status );
    }
    
    wp_send_json_success( array('status'=>$status) );
}
add_action( 'wp_ajax_kg_quote_status', 'kg_ajax_quote_status' );

/**
 * Sends a branded status-update email for quote requests.
 */
function kg_notify_quote_status( $post_id, $status ) {
    require_once get_template_directory() . '/inc/email-templates.php';

    $name  = get_post_meta( $post_id, 'kg_quote_name',  true );
    $email = get_post_meta( $post_id, 'kg_quote_email', true );
    $fname = explode( ' ', $name )[0];

    if ( ! $email ) return;

    if ( $status === 'contacted' ) {
        $mail_subject = 'Proposal Update: Under Review — Kings Manpower';
        $body = kg_email_heading( 'Proposal Status: Under Review' )
            . kg_email_para( 'Dear ' . esc_html($fname) . ',' )
            . kg_email_para( 'This is to inform you that your service configuration request has been assigned to a dedicated business development representative. We are currently finalizing the details of your proposal.' )
            . kg_email_banner( 'You can expect a direct communication from our team shortly to present the formal proposal and discuss any customized requirements.' )
            . kg_email_button( 'Visit Kings Manpower', home_url('/') );
    } elseif ( $status === 'converted' ) {
        $mail_subject = 'Welcome to Kings Manpower — Partnership Confirmed';
        $body = kg_email_heading( 'Partnership Confirmed' )
            . kg_email_para( 'Dear ' . esc_html($fname) . ',' )
            . kg_email_para( 'We are delighted to officially welcome you as a valued partner of Kings Manpower. Your service proposal has been marked as <strong style="color:#00D09C;">confirmed</strong> within our system.' )
            . kg_email_para( 'Our account management team is currently preparing your onboarding materials and service level agreements (SLAs).' )
            . kg_email_banner( 'We look forward to delivering exceptional workforce solutions that drive your operational success.' )
            . kg_email_button( 'Visit Kings Manpower', home_url('/') );
    } else {
        return; // Don't send emails for 'pending' or 'closed'
    }

    wp_mail(
        $email,
        $mail_subject,
        kg_email_wrap( $mail_subject, $body ),
        array( 'Content-Type: text/html; charset=UTF-8' )
    );
}

/* — Filter — */
add_action( 'restrict_manage_posts', function($pt) {
    if ($pt !== 'kg_quote_lead') return;
    $cur = $_GET['kg_quote_filter'] ?? '';
    echo '<select name="kg_quote_filter">
        <option value="">All Statuses</option>
        <option value="pending"'   . selected($cur,'pending',false)   . '>Pending</option>
        <option value="contacted"' . selected($cur,'contacted',false) . '>Contacted</option>
        <option value="converted"' . selected($cur,'converted',false) . '>Converted</option>
        <option value="closed"'    . selected($cur,'closed',false)    . '>Closed</option>
    </select>';
} );
add_action( 'pre_get_posts', function($query) {
    global $pagenow;
    if ( ! is_admin() || $pagenow !== 'edit.php' || ($query->get('post_type') ?? '') !== 'kg_quote_lead' || empty($_GET['kg_quote_filter']) ) return;
    $allowed = array('pending','contacted','converted','closed');
    if ( in_array($_GET['kg_quote_filter'],$allowed,true) ) {
        $query->set('meta_query', array(array('key'=>'kg_quote_status','value'=>$_GET['kg_quote_filter'])));
    }
} );
