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
 * Flushes a JSON success response to the browser immediately,
 * then keeps PHP running so emails send after the connection closes.
 * This makes the form feel instant — no waiting for SMTP.
 */
function kg_flush_response( $data ) {
    $json = wp_json_encode( array( 'success' => true, 'data' => $data ) );

    // Disable output buffering so headers can be sent
    while ( ob_get_level() ) {
        ob_end_clean();
    }

    header( 'Content-Type: application/json; charset=UTF-8' );
    header( 'Content-Length: ' . strlen( $json ) );
    header( 'Connection: close' );

    echo $json;

    // Flush to the browser
    flush();

    // Keep PHP alive after response (FastCGI / Apache)
    if ( function_exists( 'fastcgi_finish_request' ) ) {
        fastcgi_finish_request();
    }

    // Prevent WP from sending anything else
    ignore_user_abort( true );
    set_time_limit( 120 );
}

/**
 * Wraps wp_mail() and captures any PHPMailer errors.
 * Returns true on success, or the error message string on failure.
 */
function kg_send_mail( $to, $subject, $body, $headers = array(), $attachments = array() ) {
    $mail_error = null;

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

    $to_email = defined('KG_ADMIN_EMAIL') ? KG_ADMIN_EMAIL : get_option('admin_email');

    /* — Email to Kings Group — */
    $body = kg_email_heading( 'Website Inquiry Notification' )
        . kg_email_para( 'A new inquiry has been submitted via the Kings Manpower corporate website. Please review the details below and ensure a timely response.' )
        . '<table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e8ecf0;border-radius:8px;overflow:hidden;margin-bottom:24px;">'
        . kg_email_row( 'Name',    $name )
        . kg_email_row( 'Email',   '<a href="mailto:' . esc_attr($email) . '" style="color:#0A2540;">' . esc_html($email) . '</a>' )
        . kg_email_row( 'Subject', $subject )
        . kg_email_row( 'Message', nl2br( esc_html($message) ) )
        . '</table>'
        . kg_email_banner( 'To respond, please reply directly to this email. The sender\'s address is configured as the reply-to destination.' );

    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'Reply-To: ' . $name . ' <' . $email . '>',
    );

    /* — Save to WP Admin (Inquiries CPT) — */
    if ( function_exists('kg_save_inquiry_post') ) {
        kg_save_inquiry_post( array(
            'name'    => $name,
            'email'   => $email,
            'subject' => $subject,
            'message' => $message,
        ) );
    }

    /* — Respond to browser immediately, send emails after — */
    kg_flush_response( array( 'message' => 'Your message has been sent. We\'ll be in touch soon!' ) );

    kg_send_mail( $to_email, 'Contact Inquiry: ' . $subject, kg_email_wrap( 'Contact Inquiry: ' . $subject, $body ), $headers );

    /* — Auto-reply to visitor — */
    $reply_body = kg_email_heading( 'Inquiry Acknowledgment' )
        . kg_email_para( 'Dear ' . esc_html($name) . ',' )
        . kg_email_para( 'Thank you for contacting Kings Manpower. We acknowledge receipt of your inquiry and appreciate your interest in our services. Our team is currently reviewing your message and will provide a comprehensive response shortly.' )
        . '<table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e8ecf0;border-radius:8px;overflow:hidden;margin-bottom:24px;">'
        . kg_email_row( 'Your Name',    $name )
        . kg_email_row( 'Subject',      $subject )
        . kg_email_row( 'Your Message', nl2br( esc_html($message) ) )
        . '</table>'
        . kg_email_banner( 'Please expect a response from our representatives within 24 to 48 hours.' )
        . kg_email_button( 'Visit Our Website', home_url('/') );

    kg_send_mail(
        $email,
        'Inquiry Acknowledgment — Kings Manpower',
        kg_email_wrap( 'Inquiry Acknowledgment', $reply_body ),
        array( 'Content-Type: text/html; charset=UTF-8' )
    );

    exit;
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

    $to_email = defined('KG_ADMIN_EMAIL') ? KG_ADMIN_EMAIL : get_option('admin_email');

    /* — Email to Kings Group — */
    $body = kg_email_heading( 'Candidate Application Notification' )
        . kg_email_para( 'A new employment application has been successfully submitted via the Kings Manpower careers portal. The candidate\'s details are enclosed for your evaluation.' )
        . '<table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e8ecf0;border-radius:8px;overflow:hidden;margin-bottom:24px;">'
        . kg_email_row( 'Full Name',      $fullname )
        . kg_email_row( 'Email',          '<a href="mailto:' . esc_attr($email) . '" style="color:#0A2540;">' . esc_html($email) . '</a>' )
        . kg_email_row( 'Phone',          $phone ?: '—' )
        . kg_email_row( 'Preferred Role', $role  ?: 'Not specified' )
        . kg_email_row( 'LinkedIn',       $linkedin ? '<a href="' . esc_url($linkedin) . '" style="color:#0A2540;">View Profile</a>' : '—' )
        . kg_email_row( 'CV File',        '<a href="' . esc_url($cv_url) . '" style="color:#00D09C;font-weight:600;">Download CV</a>' )
        . '</table>'
        . kg_email_banner( 'The candidate\'s Curriculum Vitae is attached to this email and archived within the corporate media library.' );

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

    /* — Respond to browser immediately, send emails after — */
    kg_flush_response( array( 'message' => 'Application submitted! Check your email for confirmation.' ) );

    kg_send_mail(
        $to_email,
        'New Application: ' . $fullname . ( $role ? ' — ' . $role : '' ),
        kg_email_wrap( 'New CV Application', $body ),
        $headers,
        array( $cv_path )
    );

    /* — Auto-reply to applicant — */
    $reply_body = kg_email_heading( 'Application Acknowledgment' )
        . kg_email_para( 'Dear ' . esc_html($fname) . ',' )
        . kg_email_para( 'Thank you for your interest in a career with Kings Manpower. This email confirms the successful receipt of your application and Curriculum Vitae. Our talent acquisition team will review your qualifications against our current requirements.' )
        . '<table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e8ecf0;border-radius:8px;overflow:hidden;margin-bottom:24px;">'
        . kg_email_row( 'Full Name',      $fullname )
        . kg_email_row( 'Email',          $email )
        . kg_email_row( 'Preferred Role', $role ?: 'Open to opportunities' )
        . '</table>'
        . kg_email_banner( 'Should your profile match our needs, a representative will contact you within 2 to 3 business days to discuss the next steps.' )
        . kg_email_para( 'We appreciate your time and interest in joining our organization.' )
        . kg_email_button( 'View Career Opportunities', home_url('/jobs/') );

    kg_send_mail(
        $email,
        'Application Acknowledgment — Kings Manpower',
        kg_email_wrap( 'Application Acknowledgment', $reply_body ),
        array( 'Content-Type: text/html; charset=UTF-8' )
    );

    exit;
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

    $to_email = defined('KG_ADMIN_EMAIL') ? KG_ADMIN_EMAIL : get_option('admin_email');

    /* — Email to Kings Group — */
    $body = kg_email_heading( 'Service Proposal Request Notification' )
        . kg_email_para( 'A prospective client has submitted a formal request for a service proposal via the Kings Manpower platform. Please review the enclosed workforce configuration.' )
        . '<table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e8ecf0;border-radius:8px;overflow:hidden;margin-bottom:24px;">'
        . kg_email_row( 'Client Name',  $name )
        . kg_email_row( 'Work Email',   '<a href="mailto:' . esc_attr($email) . '" style="color:#0A2540;">' . esc_html($email) . '</a>' )
        . kg_email_row( 'Submitted At', current_time('D, d M Y g:i A') )
        . '</table>'
        . '<h3 style="margin:0 0 12px;font-size:16px;font-weight:700;color:#0A2540;">Requested Team Configuration</h3>'
        . $roles_table
        . kg_email_banner( 'To initiate correspondence, please reply directly to this email. The prospect\'s email address is designated as the reply-to.' );

    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'Reply-To: ' . $name . ' <' . $email . '>',
    );

    /* — Save to WP Admin (Quote Leads CPT) — */
    if ( function_exists('kg_save_quote_lead_post') ) {
        kg_save_quote_lead_post( array(
            'name'  => $name,
            'email' => $email,
            'total' => $total_price,
            'roles' => $roles,
        ) );
    }

    /* — Respond to browser immediately, send emails after — */
    kg_flush_response( array(
        'message' => 'Quote submitted! Check your email for your team configuration summary.',
        'total'   => '$' . number_format($total_price, 0),
    ) );

    kg_send_mail(
        $to_email,
        'Quote Request from ' . $name . ' — $' . number_format($total_price, 0) . '/mo',
        kg_email_wrap( 'Service Proposal Request', $body ),
        $headers
    );

    /* — Confirmation email to client — */
    $client_body = kg_email_heading( 'Proposal Request Acknowledgment' )
        . kg_email_para( 'Dear ' . esc_html($name) . ',' )
        . kg_email_para( 'Thank you for considering Kings Manpower as your workforce solutions partner. We have successfully received your service configuration request. Our business development team is currently analyzing your requirements to formulate a comprehensive proposal.' )
        . '<h3 style="margin:0 0 12px;font-size:16px;font-weight:700;color:#0A2540;">Your Team Configuration Summary</h3>'
        . $roles_table
        . kg_email_banner( 'A dedicated representative will contact you within one business day to present a detailed pricing breakdown and discuss your specific needs.' )
        . kg_email_para( 'Should you require immediate assistance, please reply directly to this correspondence or contact our corporate office at <strong>+63 (2) 87766712</strong>.' )
        . kg_email_button( 'Visit Kings Manpower', home_url('/') );

    kg_send_mail(
        $email,
        'Your Kings Manpower Service Proposal — $' . number_format($total_price, 0) . '/mo',
        kg_email_wrap( 'Proposal Request Acknowledgment', $client_body ),
        array( 'Content-Type: text/html; charset=UTF-8' )
    );

    exit;
}
add_action( 'wp_ajax_nopriv_kg_submit_quote', 'kg_handle_quote' );
add_action( 'wp_ajax_kg_submit_quote',        'kg_handle_quote' );
