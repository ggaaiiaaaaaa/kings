<?php
/**
 * AJAX form handlers for Contact, CV Application, and Quote forms.
 * Registered via add_action in functions.php.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

require_once get_template_directory() . '/inc/email-templates.php';

/* ─────────────────────────────────────────────
   SHARED: sanitize + validate helpers
───────────────────────────────────────────── */

function kg_verify_nonce( $nonce_value, $action ) {
    if ( ! wp_verify_nonce( $nonce_value, $action ) ) {
        wp_send_json_error( array( 'message' => 'Security check failed. Please refresh and try again.' ), 403 );
    }
}

function kg_check_honeypot() {
    if ( ! empty( $_POST['kg_hp_field'] ) ) {
        // Silent pass — looks like success to the bot
        wp_send_json_success( array( 'message' => 'Thank you!' ) );
    }
}

/**
 * Wraps wp_mail() and captures any PHPMailer errors.
 * Returns true on success, or the error message string on failure.
 */
function kg_send_mail( $to, $subject, $body, $headers = array(), $attachments = array() ) {
    $mail_error = null;

    // Use a named static function stored in a variable so remove_action works
    $capture = static function( $wp_error ) use ( &$mail_error ) {
        $mail_error = $wp_error->get_error_message();
    };
    add_action( 'wp_mail_failed', $capture, 10, 1 );

    $sent = wp_mail( $to, $subject, $body, $headers, $attachments );

    remove_action( 'wp_mail_failed', $capture, 10 );

    if ( $mail_error ) {
        return $mail_error;
    }
    return true;
}

/* ─────────────────────────────────────────────
   HANDLER 1: Contact Form
   Action: kg_submit_contact
───────────────────────────────────────────── */

function kg_handle_contact() {
    kg_verify_nonce( $_POST['kg_nonce'] ?? '', 'kg_contact_nonce' );
    kg_check_honeypot();

    $name    = sanitize_text_field(     $_POST['contact_name']    ?? '' );
    $email   = sanitize_email(          $_POST['contact_email']   ?? '' );
    $subject = sanitize_text_field(     $_POST['contact_subject'] ?? 'General Inquiry' );
    $message = sanitize_textarea_field( $_POST['contact_message'] ?? '' );

    if ( ! $name || ! is_email( $email ) || ! $message ) {
        wp_send_json_error( array( 'message' => 'Please fill in all required fields.' ), 422 );
    }

    $to_email = 'rhonjames95@gmail.com';

    /* — Email to Kings Group — */
    $body = kg_email_heading( 'New Contact Inquiry' )
        . kg_email_para( 'You have received a new message from the Kings Group website contact form.' )
        . '<table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e8ecf0;border-radius:8px;overflow:hidden;margin-bottom:24px;">'
        . kg_email_row( 'Name',    $name )
        . kg_email_row( 'Email',   '<a href="mailto:' . esc_attr($email) . '" style="color:#0A2540;">' . esc_html($email) . '</a>' )
        . kg_email_row( 'Subject', $subject )
        . kg_email_row( 'Message', nl2br( esc_html($message) ) )
        . '</table>'
        . kg_email_banner( 'Reply directly to this email — the sender\'s address is set as reply-to.' );

    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'Reply-To: ' . $name . ' <' . $email . '>',
    );

    $result = kg_send_mail( $to_email, 'Contact Inquiry: ' . $subject, kg_email_wrap( 'Contact Inquiry: ' . $subject, $body ), $headers );
    if ( $result !== true ) {
        wp_send_json_error( array( 'message' => 'Email delivery failed: ' . $result . ' — Check WP Mail SMTP settings in WP Admin.' ), 500 );
    }

    /* — Auto-reply to visitor — */
    $reply_body = kg_email_heading( 'We received your message!' )
        . kg_email_para( 'Hi ' . esc_html($name) . ',' )
        . kg_email_para( 'Thank you for reaching out to Kings Group Cooperative. We have received your message and our team will review it shortly.' )
        . '<table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e8ecf0;border-radius:8px;overflow:hidden;margin-bottom:24px;">'
        . kg_email_row( 'Your Name',    $name )
        . kg_email_row( 'Subject',      $subject )
        . kg_email_row( 'Your Message', nl2br( esc_html($message) ) )
        . '</table>'
        . kg_email_banner( 'We typically respond within 1–2 business days.' )
        . kg_email_button( 'Visit Our Website', home_url('/') );

    kg_send_mail(
        $email,
        'We received your message — Kings Group',
        kg_email_wrap( 'Message Received', $reply_body ),
        array( 'Content-Type: text/html; charset=UTF-8' )
    );

    wp_send_json_success( array( 'message' => 'Your message has been sent. We\'ll be in touch soon!' ) );
}
add_action( 'wp_ajax_nopriv_kg_submit_contact', 'kg_handle_contact' );
add_action( 'wp_ajax_kg_submit_contact',        'kg_handle_contact' );

/* ─────────────────────────────────────────────
   HANDLER 2: CV Application
   Action: kg_submit_application
───────────────────────────────────────────── */

function kg_handle_application() {
    kg_verify_nonce( $_POST['kg_nonce'] ?? '', 'kg_careers_nonce' );
    kg_check_honeypot();

    $fname    = sanitize_text_field( $_POST['app_fname']    ?? '' );
    $lname    = sanitize_text_field( $_POST['app_lname']    ?? '' );
    $email    = sanitize_email(      $_POST['app_email']    ?? '' );
    $phone    = sanitize_text_field( $_POST['app_phone']    ?? '' );
    $role     = sanitize_text_field( $_POST['app_role']     ?? '' );
    $linkedin = esc_url_raw(         $_POST['app_linkedin'] ?? '' );
    $fullname = trim( $fname . ' ' . $lname );

    if ( ! $fname || ! $lname || ! is_email( $email ) ) {
        wp_send_json_error( array( 'message' => 'Please fill in your name and email.' ), 422 );
    }

    if ( empty( $_FILES['app_cv'] ) || $_FILES['app_cv']['error'] !== UPLOAD_ERR_OK ) {
        wp_send_json_error( array( 'message' => 'Please upload your CV (PDF or DOCX).' ), 422 );
    }

    /* — Validate file type & size — */
    $allowed_types = array(
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    );
    $file_type = mime_content_type( $_FILES['app_cv']['tmp_name'] );
    $file_size = $_FILES['app_cv']['size'];

    if ( ! in_array( $file_type, $allowed_types, true ) ) {
        wp_send_json_error( array( 'message' => 'Only PDF or DOCX files are accepted.' ), 422 );
    }
    if ( $file_size > 5 * 1024 * 1024 ) {
        wp_send_json_error( array( 'message' => 'File size must be under 5 MB.' ), 422 );
    }

    /* — Save CV to WP uploads — */
    require_once ABSPATH . 'wp-admin/includes/file.php';
    $upload = wp_handle_upload( $_FILES['app_cv'], array( 'test_form' => false ) );
    if ( isset( $upload['error'] ) ) {
        wp_send_json_error( array( 'message' => 'Upload failed: ' . $upload['error'] ), 500 );
    }
    $cv_url  = $upload['url'];
    $cv_path = $upload['file'];

    $to_email = 'rhonjames95@gmail.com';

    /* — Email to Kings Group — */
    $body = kg_email_heading( 'New CV Application Received' )
        . kg_email_para( 'A new job application has been submitted via the Kings Group careers page.' )
        . '<table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e8ecf0;border-radius:8px;overflow:hidden;margin-bottom:24px;">'
        . kg_email_row( 'Full Name',      $fullname )
        . kg_email_row( 'Email',          '<a href="mailto:' . esc_attr($email) . '" style="color:#0A2540;">' . esc_html($email) . '</a>' )
        . kg_email_row( 'Phone',          $phone ?: '—' )
        . kg_email_row( 'Preferred Role', $role  ?: 'Not specified' )
        . kg_email_row( 'LinkedIn',       $linkedin ? '<a href="' . esc_url($linkedin) . '" style="color:#0A2540;">View Profile</a>' : '—' )
        . kg_email_row( 'CV File',        '<a href="' . esc_url($cv_url) . '" style="color:#00D09C;font-weight:600;">Download CV</a>' )
        . '</table>'
        . kg_email_banner( 'CV file is attached to this email and also saved to the WP Media Library.' );

    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'Reply-To: ' . $fullname . ' <' . $email . '>',
    );

    /* — Save to WP Admin (Applications CPT) — */
    if ( function_exists( 'kg_save_application_post' ) ) {
        kg_save_application_post( array(
            'fullname' => $fullname,
            'email'    => $email,
            'phone'    => $phone,
            'role'     => $role,
            'linkedin' => $linkedin,
            'cv_url'   => $cv_url,
        ) );
    }

    $result = kg_send_mail(
        $to_email,
        'New Application: ' . $fullname . ( $role ? ' — ' . $role : '' ),
        kg_email_wrap( 'New CV Application', $body ),
        $headers,
        array( $cv_path )
    );
    if ( $result !== true ) {
        wp_send_json_error( array( 'message' => 'Email delivery failed: ' . $result . ' — Check WP Mail SMTP settings in WP Admin.' ), 500 );
    }

    /* — Auto-reply to applicant — */
    $reply_body = kg_email_heading( 'Application Received!' )
        . kg_email_para( 'Hi ' . esc_html($fname) . ',' )
        . kg_email_para( 'Thank you for applying to Kings Group Cooperative. We have received your CV and our talent team will review your profile carefully.' )
        . '<table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e8ecf0;border-radius:8px;overflow:hidden;margin-bottom:24px;">'
        . kg_email_row( 'Full Name',      $fullname )
        . kg_email_row( 'Email',          $email )
        . kg_email_row( 'Preferred Role', $role ?: 'Open to opportunities' )
        . '</table>'
        . kg_email_banner( 'Our talent team typically reaches out within 2–3 business days.' )
        . kg_email_para( 'While you wait, feel free to explore our open positions and learn more about life at Kings Group.' )
        . kg_email_button( 'Browse Open Positions', home_url('/jobs/') );

    kg_send_mail(
        $email,
        'Your application to Kings Group has been received',
        kg_email_wrap( 'Application Received', $reply_body ),
        array( 'Content-Type: text/html; charset=UTF-8' )
    );

    wp_send_json_success( array( 'message' => 'Application submitted! Check your email for confirmation.' ) );
}
add_action( 'wp_ajax_nopriv_kg_submit_application', 'kg_handle_application' );
add_action( 'wp_ajax_kg_submit_application',        'kg_handle_application' );

/* ─────────────────────────────────────────────
   HANDLER 3: Quote / Team Builder
   Action: kg_submit_quote
───────────────────────────────────────────── */

function kg_handle_quote() {
    kg_verify_nonce( $_POST['kg_nonce'] ?? '', 'kg_quote_nonce' );
    kg_check_honeypot();

    $name  = sanitize_text_field( $_POST['quote_name']  ?? '' );
    $email = sanitize_email(      $_POST['quote_email'] ?? '' );
    $roles = json_decode( stripslashes( $_POST['quote_roles'] ?? '[]' ), true );

    if ( ! $name || ! is_email( $email ) ) {
        wp_send_json_error( array( 'message' => 'Please enter your name and work email.' ), 422 );
    }
    if ( empty( $roles ) || ! is_array( $roles ) ) {
        wp_send_json_error( array( 'message' => 'Please add at least one role to your team.' ), 422 );
    }

    /* — Build roles table HTML — */
    $roles_rows  = '';
    $total_price = 0;
    foreach ( $roles as $role ) {
        $role_name  = sanitize_text_field( $role['role']       ?? '' );
        $level      = sanitize_text_field( $role['level']      ?? 'Junior' );
        $qty        = absint(              $role['qty']        ?? 1 );
        $unit_price = floatval(            $role['unit_price'] ?? 0 );
        $subtotal   = floatval(            $role['subtotal']   ?? ( $qty * $unit_price ) );
        $total_price += $subtotal;
        $roles_rows .= '
        <tr>
          <td style="padding:12px 16px;font-size:14px;color:#1a1a2e;border-bottom:1px solid #f0f0f0;">' . esc_html($role_name) . '</td>
          <td style="padding:12px 16px;font-size:14px;color:#1a1a2e;text-align:center;border-bottom:1px solid #f0f0f0;">' . esc_html($level) . ' &times; ' . $qty . '</td>
          <td style="padding:12px 16px;font-size:14px;color:#1a1a2e;text-align:right;border-bottom:1px solid #f0f0f0;">$' . number_format($unit_price, 0) . '</td>
          <td style="padding:12px 16px;font-size:14px;font-weight:600;color:#0A2540;text-align:right;border-bottom:1px solid #f0f0f0;">$' . number_format($subtotal, 0) . '</td>
        </tr>';
    }
    $total_row = '
        <tr style="background:#f0fdf9;">
          <td colspan="3" style="padding:14px 16px;font-size:15px;font-weight:700;color:#0A2540;text-align:right;">Estimated Monthly Total</td>
          <td style="padding:14px 16px;font-size:18px;font-weight:700;color:#00D09C;text-align:right;">$' . number_format($total_price, 0) . '</td>
        </tr>';

    $roles_table = '
    <table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e8ecf0;border-radius:8px;overflow:hidden;margin-bottom:24px;">
      <thead>
        <tr style="background:#0A2540;">
          <th style="padding:12px 16px;font-size:12px;font-weight:600;color:rgba(255,255,255,0.7);text-align:left;text-transform:uppercase;letter-spacing:0.05em;">Role</th>
          <th style="padding:12px 16px;font-size:12px;font-weight:600;color:rgba(255,255,255,0.7);text-align:center;text-transform:uppercase;letter-spacing:0.05em;">Level / Qty</th>
          <th style="padding:12px 16px;font-size:12px;font-weight:600;color:rgba(255,255,255,0.7);text-align:right;text-transform:uppercase;letter-spacing:0.05em;">Unit/mo</th>
          <th style="padding:12px 16px;font-size:12px;font-weight:600;color:rgba(255,255,255,0.7);text-align:right;text-transform:uppercase;letter-spacing:0.05em;">Subtotal</th>
        </tr>
      </thead>
      <tbody>' . $roles_rows . $total_row . '</tbody>
    </table>';

    $to_email = 'rhonjames95@gmail.com';

    /* — Email to Kings Group — */
    $body = kg_email_heading( 'New Quote Request' )
        . kg_email_para( 'A client has submitted a team builder quote request from the Kings Group website.' )
        . '<table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e8ecf0;border-radius:8px;overflow:hidden;margin-bottom:24px;">'
        . kg_email_row( 'Client Name',  $name )
        . kg_email_row( 'Work Email',   '<a href="mailto:' . esc_attr($email) . '" style="color:#0A2540;">' . esc_html($email) . '</a>' )
        . kg_email_row( 'Submitted At', current_time('D, d M Y g:i A') )
        . '</table>'
        . '<h3 style="margin:0 0 12px;font-size:16px;font-weight:700;color:#0A2540;">Requested Team Configuration</h3>'
        . $roles_table
        . kg_email_banner( 'Reply directly to this email — the client\'s address is set as reply-to.' );

    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'Reply-To: ' . $name . ' <' . $email . '>',
    );

    $result = kg_send_mail(
        $to_email,
        'Quote Request from ' . $name . ' — $' . number_format($total_price, 0) . '/mo',
        kg_email_wrap( 'New Quote Request', $body ),
        $headers
    );
    if ( $result !== true ) {
        wp_send_json_error( array( 'message' => 'Email delivery failed: ' . $result . ' — Check WP Mail SMTP settings in WP Admin.' ), 500 );
    }

    /* — Confirmation email to client — */
    $client_body = kg_email_heading( 'Your Quote Has Been Received!' )
        . kg_email_para( 'Hi ' . esc_html($name) . ',' )
        . kg_email_para( 'Thank you for your interest in building a team with Kings Group Cooperative. We have received your team configuration and our sales team will prepare a detailed proposal for you.' )
        . '<h3 style="margin:0 0 12px;font-size:16px;font-weight:700;color:#0A2540;">Your Team Configuration Summary</h3>'
        . $roles_table
        . kg_email_banner( 'Our team will reach out within 1 business day with a full proposal and pricing breakdown.' )
        . kg_email_para( 'Questions? Reply to this email or call us at <strong>+63 (2) 87766712</strong>.' )
        . kg_email_button( 'Visit Kings Group', home_url('/') );

    kg_send_mail(
        $email,
        'Your Kings Group Team Quote — $' . number_format($total_price, 0) . '/mo',
        kg_email_wrap( 'Quote Received', $client_body ),
        array( 'Content-Type: text/html; charset=UTF-8' )
    );

    wp_send_json_success( array(
        'message' => 'Quote submitted! Check your email for your team configuration summary.',
        'total'   => '$' . number_format($total_price, 0),
    ) );
}
add_action( 'wp_ajax_nopriv_kg_submit_quote', 'kg_handle_quote' );
add_action( 'wp_ajax_kg_submit_quote',        'kg_handle_quote' );
