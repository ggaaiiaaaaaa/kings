<?php
/**
 * AJAX form handlers for Contact, CV Application, and Quote forms.
 * Registered via add_action in functions.php.
 */
if (!defined('ABSPATH'))
    exit;

require_once get_template_directory() . '/inc/email-templates.php';

/* ─────────────────────────────────────────────
   SHARED: sanitize + validate helpers
───────────────────────────────────────────── */

function kg_verify_nonce($nonce_value, $action)
{
    if (!wp_verify_nonce($nonce_value, $action)) {
        wp_send_json_error(array('message' => 'Security check failed. Please refresh and try again.'), 403);
    }
}

function kg_check_honeypot()
{
    if (!empty($_POST['kg_hp_field'])) {
        // Silent pass — looks like success to the bot
        wp_send_json_success(array('message' => 'Thank you!'));
    }
}

function kg_verify_turnstile()
{
    if (!defined('CF_TURNSTILE_SECRET_KEY') || empty(CF_TURNSTILE_SECRET_KEY)) {
        return;
    }

    $token = $_POST['cf-turnstile-response'] ?? '';
    if (empty($token)) {
        wp_send_json_error(array('message' => 'Security check failed. Please complete the CAPTCHA.'), 400);
    }

    $remote_ip = $_SERVER['REMOTE_ADDR'] ?? '';

    $response = wp_remote_post('https://challenges.cloudflare.com/turnstile/v0/siteverify', array(
        'body' => array(
            'secret' => CF_TURNSTILE_SECRET_KEY,
            'response' => $token,
            'remoteip' => $remote_ip,
        ),
    ));

    if (is_wp_error($response)) {
        wp_send_json_error(array('message' => 'Unable to verify CAPTCHA. Please try again.'), 500);
    }

    $body = wp_remote_retrieve_body($response);
    $result = json_decode($body, true);

    if (empty($result['success'])) {
        wp_send_json_error(array('message' => 'CAPTCHA verification failed. Please try again.'), 400);
    }
}

/**
 * Flushes a JSON success response to the browser immediately,
 * then keeps PHP running so emails send after the connection closes.
 * This makes the form feel instant — no waiting for SMTP.
 */
function kg_flush_response($data)
{
    $json = wp_json_encode(array('success' => true, 'data' => $data));

    // Disable output buffering so headers can be sent
    while (ob_get_level()) {
        ob_end_clean();
    }

    header('Content-Type: application/json; charset=UTF-8');
    header('Content-Length: ' . strlen($json));
    header('Connection: close');

    echo $json;

    // Flush to the browser
    flush();

    // Keep PHP alive after response (FastCGI / Apache)
    if (function_exists('fastcgi_finish_request')) {
        fastcgi_finish_request();
    }

    // Prevent WP from sending anything else
    ignore_user_abort(true);
    set_time_limit(120);
}

/**
 * Wraps wp_mail() and captures any PHPMailer errors.
 * Returns true on success, or the error message string on failure.
 */
function kg_send_mail($to, $subject, $body, $headers = array(), $attachments = array())
{
    $mail_error = null;

    $capture = static function ($wp_error) use (&$mail_error) {
        $mail_error = $wp_error->get_error_message();
    };
    add_action('wp_mail_failed', $capture, 10, 1);

    $sent = wp_mail($to, $subject, $body, $headers, $attachments);

    remove_action('wp_mail_failed', $capture, 10);

    if ($mail_error) {
        return $mail_error;
    }
    return true;
}

/**
 * Returns an array of emails for all users with the 'hr' role.
 */
function kg_get_hr_emails()
{
    $hr_emails = array();
    if (function_exists('get_users')) {
        $hr_users = get_users(array('role' => 'hr'));
        foreach ($hr_users as $user) {
            if (is_email($user->user_email)) {
                $hr_emails[] = $user->user_email;
            }
        }
    }
    return $hr_emails;
}

/* ─────────────────────────────────────────────
   HANDLER 1: Contact Form
   Action: kg_submit_contact
───────────────────────────────────────────── */

function kg_handle_contact()
{
    kg_verify_nonce($_POST['kg_nonce'] ?? '', 'kg_contact_nonce');
    kg_check_honeypot();
    kg_verify_turnstile();

    $name = sanitize_text_field($_POST['contact_name'] ?? '');
    $email = sanitize_email($_POST['contact_email'] ?? '');
    $phone = sanitize_text_field($_POST['contact_phone'] ?? '');
    $subject = sanitize_text_field($_POST['contact_subject'] ?? 'General Inquiry');
    $message = sanitize_textarea_field($_POST['contact_message'] ?? '');

    if (!$name || !is_email($email) || !$message) {
        wp_send_json_error(array('message' => 'Please fill in all required fields.'), 422);
    }

    $to_email = defined('KG_INQUIRY_EMAIL') ? KG_INQUIRY_EMAIL : (defined('KG_ADMIN_EMAIL') ? KG_ADMIN_EMAIL : 'info@kingsgroup.com.ph');

    $contact_details = '<table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e8ecf0;border-radius:8px;overflow:hidden;margin-bottom:24px;">'
        . kg_email_row('Name', $name)
        . kg_email_row('Email', '<a href="mailto:' . esc_attr($email) . '" style="color:#0A2540;">' . esc_html($email) . '</a>')
        . kg_email_row('Phone', $phone ?: '—')
        . kg_email_row('Subject', $subject)
        . kg_email_row('Message', nl2br(esc_html($message)))
        . '</table>';

    /* — Email to Kings Group (Admin Alert) — */
    $parsed_admin = kg_get_parsed_email('contact_admin', array(
        '{contact_subject}' => $subject,
        '{contact_details}' => $contact_details,
    ));

    $admin_subject = $parsed_admin ? $parsed_admin['subject'] : 'Contact Inquiry: ' . $subject;
    $admin_body = kg_email_heading($parsed_admin ? $parsed_admin['heading'] : 'Website Inquiry Notification') . ($parsed_admin ? $parsed_admin['body'] : '');
    if ($parsed_admin && !empty($parsed_admin['banner'])) {
        $admin_body .= kg_email_banner($parsed_admin['banner']);
    }
    if ($parsed_admin && !empty($parsed_admin['btn_text']) && !empty($parsed_admin['btn_link'])) {
        $admin_body .= kg_email_button($parsed_admin['btn_text'], $parsed_admin['btn_link']);
    }

    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'Reply-To: ' . $name . ' <' . $email . '>',
    );

    /* — Save to WP Admin (Inquiries CPT) — */
    if (function_exists('kg_save_inquiry_post')) {
        kg_save_inquiry_post(array(
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'subject' => $subject,
            'message' => $message,
        ));
    }

    /* — Respond to browser immediately, send emails after — */
    kg_flush_response(array('message' => 'Your message has been sent. We\'ll be in touch soon!'));

    $recipients = array($to_email);
    if (function_exists('kg_get_hr_emails')) {
        $recipients = array_merge($recipients, kg_get_hr_emails());
        $recipients = array_unique($recipients);
    }

    kg_send_mail($recipients, $admin_subject, kg_email_wrap($admin_subject, $admin_body), $headers);

    /* — Auto-reply to visitor (Client Acknowledgment) — */
    $parsed_client = kg_get_parsed_email('contact_client', array(
        '{name}' => esc_html($name),
        '{contact_subject}' => $subject,
        '{contact_details}' => $contact_details,
    ));

    $client_subject = $parsed_client ? $parsed_client['subject'] : 'Inquiry Acknowledgment — Kings Manpower';
    $client_body = kg_email_heading($parsed_client ? $parsed_client['heading'] : 'Inquiry Acknowledgment') . ($parsed_client ? $parsed_client['body'] : '');
    if ($parsed_client && !empty($parsed_client['banner'])) {
        $client_body .= kg_email_banner($parsed_client['banner']);
    }
    if ($parsed_client && !empty($parsed_client['btn_text']) && !empty($parsed_client['btn_link'])) {
        $client_body .= kg_email_button($parsed_client['btn_text'], $parsed_client['btn_link']);
    }

    kg_send_mail(
        $email,
        $client_subject,
        kg_email_wrap($client_subject, $client_body),
        array('Content-Type: text/html; charset=UTF-8')
    );

    exit;
}
add_action('wp_ajax_nopriv_kg_submit_contact', 'kg_handle_contact');
add_action('wp_ajax_kg_submit_contact', 'kg_handle_contact');

function kg_secure_upload_directory($dirs)
{
    $dirs['subdir'] = '/secure-cvs';
    $dirs['path'] = $dirs['basedir'] . '/secure-cvs';
    $dirs['url'] = $dirs['baseurl'] . '/secure-cvs';
    return $dirs;
}

/* ─────────────────────────────────────────────
   HANDLER 2: CV Application
   Action: kg_submit_application
   ───────────────────────────────────────────── */

function kg_handle_application()
{
    kg_verify_nonce($_POST['kg_nonce'] ?? '', 'kg_careers_nonce');
    kg_check_honeypot();
    kg_verify_turnstile();

    $fname = sanitize_text_field($_POST['app_fname'] ?? '');
    $lname = sanitize_text_field($_POST['app_lname'] ?? '');
    $email = sanitize_email($_POST['app_email'] ?? '');
    $phone = sanitize_text_field($_POST['app_phone'] ?? '');
    $fullname = trim($fname . ' ' . $lname);

    if (!$fname || !$lname || (empty($email) && empty($phone)) || (!empty($email) && !is_email($email))) {
        wp_send_json_error(array('message' => 'Please fill in your name and email or phone number.'), 422);
    }

    // Capture preferred roles
    $preferred_roles = array();
    if (!empty($_POST['app_preferred_roles'])) {
        if (is_array($_POST['app_preferred_roles'])) {
            $preferred_roles = array_map('sanitize_text_field', $_POST['app_preferred_roles']);
        } else {
            // Check for JSON string
            $decoded = json_decode(stripslashes($_POST['app_preferred_roles']), true);
            if (is_array($decoded)) {
                $preferred_roles = array_map('sanitize_text_field', $decoded);
            } else {
                $preferred_roles = array_filter(array_map('trim', explode(',', sanitize_text_field($_POST['app_preferred_roles']))));
            }
        }
    } elseif (!empty($_POST['app_role'])) {
        $preferred_roles[] = sanitize_text_field($_POST['app_role']);
    }

    $preferred_roles = array_filter($preferred_roles);

    $purpose = sanitize_text_field($_POST['app_purpose'] ?? '');
    if ($purpose !== 'pooling' && empty($preferred_roles)) {
        wp_send_json_error(array('message' => 'Please select at least one preferred position.'), 422);
    }

    // 1. Single Active Application Check & 14-Day Cooldown Check
    if (!empty($email)) {
        $last_apps = get_posts(array(
            'post_type' => 'kg_application',
            'posts_per_page' => 1,
            'orderby' => 'date',
            'order' => 'DESC',
            'meta_query' => array(
                array('key' => 'kg_app_email', 'value' => $email)
            )
        ));

        if (!empty($last_apps)) {
            $last_app = $last_apps[0];
            $status = get_post_meta($last_app->ID, 'kg_app_status', true) ?: 'screening';

            if ($status !== 'rejected') {
                wp_send_json_error(array('message' => 'You already have an active application in progress.'), 422);
            } else {
                $date_diff = (time() - strtotime($last_app->post_date)) / DAY_IN_SECONDS;
                if ($date_diff < 14) {
                    wp_send_json_error(array('message' => 'Please wait ' . ceil(14 - $date_diff) . ' more days before reapplying.'), 422);
                }
            }
        }
    }

    // 2. Position Closure Validation
    if (!empty($preferred_roles)) {
        $any_open = false;
        foreach ($preferred_roles as $role_title) {
            $job_posts = get_posts(array(
                'post_type' => 'jobs',
                'title' => $role_title,
                'posts_per_page' => 1,
                'post_status' => 'publish',
            ));
            if (!empty($job_posts)) {
                $is_closed = get_post_meta($job_posts[0]->ID, 'job_closed', true);
                if (!$is_closed) {
                    $any_open = true;
                }
            } else {
                $any_open = true; // Fallback for custom entries
            }
        }
        if (!$any_open) {
            wp_send_json_error(array('message' => 'All selected positions are currently closed.'), 422);
        }
    }

    $role = !empty($preferred_roles) ? $preferred_roles[0] : sanitize_text_field($_POST['app_role'] ?? '');

    $cv_url = '';
    $cv_path = '';

    if (empty($_FILES['app_cv']) || $_FILES['app_cv']['error'] !== UPLOAD_ERR_OK) {
        wp_send_json_error(array('message' => 'Please upload your CV (PDF or DOCX).'), 422);
    }

    /* — Sanitize the file name to replace spaces and underscores with hyphens — */
    if (!empty($_FILES['app_cv']['name'])) {
        $raw_name = $_FILES['app_cv']['name'];
        $clean_name = preg_replace('/[\s_]+/', '-', $raw_name);
        $_FILES['app_cv']['name'] = sanitize_file_name($clean_name);
    }

    /* — Validate file type & size — */
    $allowed_types = array(
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    );
    if (function_exists('mime_content_type')) {
        $file_type = mime_content_type($_FILES['app_cv']['tmp_name']);
    } else {
        $file_info = wp_check_filetype($_FILES['app_cv']['name']);
        $file_type = $file_info['type'];
    }
    $file_size = $_FILES['app_cv']['size'];

    if (!in_array($file_type, $allowed_types, true)) {
        wp_send_json_error(array('message' => 'Only PDF or DOCX files are accepted.'), 422);
    }
    if ($file_size > 5 * 1024 * 1024) {
        wp_send_json_error(array('message' => 'File size must be under 5 MB.'), 422);
    }

    /* — Save CV to WP uploads — */
    $upload_dir = wp_upload_dir();
    $secure_dir = $upload_dir['basedir'] . '/secure-cvs';
    if (!file_exists($secure_dir)) {
        wp_mkdir_p($secure_dir);
    }
    $htaccess_file = $secure_dir . '/.htaccess';
    if (!file_exists($htaccess_file)) {
        @file_put_contents($htaccess_file, "Deny from all\n");
    }

    add_filter('upload_dir', 'kg_secure_upload_directory');
    require_once ABSPATH . 'wp-admin/includes/file.php';
    $upload = wp_handle_upload($_FILES['app_cv'], array('test_form' => false));
    remove_filter('upload_dir', 'kg_secure_upload_directory');
    if (isset($upload['error'])) {
        wp_send_json_error(array('message' => 'Upload failed: ' . $upload['error']), 500);
    }
    $cv_url = $upload['url'];
    $cv_path = $upload['file'];

    $to_email = defined('KG_CAREER_EMAIL') ? KG_CAREER_EMAIL : (defined('KG_ADMIN_EMAIL') ? KG_ADMIN_EMAIL : 'hr@kingsgroup.com.ph');

    /* — Save to WP Admin (Applications CPT) — */
    $app_post_id = 0;
    if (function_exists('kg_save_application_post')) {
        $app_post_id = kg_save_application_post(array(
            'fullname' => $fullname,
            'email' => $email,
            'phone' => $phone,
            'role' => $role,
            'job_id' => sanitize_text_field($_POST['app_job_id'] ?? ''),
            'preferred_roles' => $preferred_roles,
            'linkedin' => '',
            'cv_url' => $cv_url,
            'applied_via_single' => !empty($_POST['app_job_id']),
            // Demographic metadata
            'mname' => sanitize_text_field($_POST['app_mname'] ?? ''),
            'purpose' => sanitize_text_field($_POST['app_purpose'] ?? ''),
            'gender' => sanitize_text_field($_POST['app_gender'] ?? ''),
            'birthday' => sanitize_text_field($_POST['app_birthday'] ?? ''),
            // Text address fields
            'street' => sanitize_text_field($_POST['app_street'] ?? ''),
            'region' => sanitize_text_field($_POST['app_region'] ?? ''),
            'city' => sanitize_text_field($_POST['app_city'] ?? ''),
            'barangay' => sanitize_text_field($_POST['app_barangay'] ?? ''),
            // Numeric PSGC codes
            'region_code' => sanitize_text_field($_POST['app_region_code'] ?? ''),
            'city_code' => sanitize_text_field($_POST['app_city_code'] ?? ''),
            'barangay_code' => sanitize_text_field($_POST['app_barangay_code'] ?? ''),
        ));
    }


    $download_url = $app_post_id ? add_query_arg('kg_download_cv', $app_post_id, home_url('/')) : $cv_url;

    /* — Email Routing & Recruiter Lookup — */
    $recruiter_email = '';
    $recruiter_name = '';
    $submitted_job_id = sanitize_text_field($_POST['app_job_id'] ?? '');

    if (!empty($submitted_job_id)) {
        $author_id = get_post_field('post_author', $submitted_job_id);
        if ($author_id) {
            $author_user = get_userdata($author_id);
            if ($author_user) {
                $recruiter_email = $author_user->user_email;
                $recruiter_name = $author_user->display_name;
            }
        }
    } elseif (!empty($role)) {
        $job_posts = get_posts(array(
            'post_type' => 'jobs',
            'title' => $role,
            'posts_per_page' => 1,
            'post_status' => 'any',
        ));
        if (!empty($job_posts)) {
            $author_id = $job_posts[0]->post_author;
            $author_user = get_userdata($author_id);
            if ($author_user) {
                $recruiter_email = $author_user->user_email;
                $recruiter_name = $author_user->display_name;
            }
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
    $edit_url = $app_post_id ? get_edit_post_link($app_post_id) : '';

    $submission_details = '<div style="border:1px solid #e8ecf0;border-radius:8px;padding:20px;margin-bottom:24px;background:#ffffff;">'
        . kg_email_row('Full Name', $fullname)
        . kg_email_row('Email', '<a href="mailto:' . esc_attr($email) . '" style="color:#0A2540;">' . esc_html($email) . '</a>')
        . kg_email_row('Phone', $phone ?: '—')
        . kg_email_row('Preferred Roles', !empty($preferred_roles) ? implode(', ', $preferred_roles) : ($role ?: 'Not specified'))
        . kg_email_row('CV File', '<a href="' . esc_url($download_url) . '" style="color:#00D09C;font-weight:600;">Download CV (Secure)</a>')
        . '</div>';

    /* — Email to Kings Group (Admin & Recruiter Alert) — */
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

    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'Reply-To: ' . $fullname . ' <' . $email . '>',
    );

    /* — Respond to browser immediately, send emails after — */
    kg_flush_response(array('message' => 'Application submitted! Check your email for confirmation.'));

    $attachments = $cv_path ? array($cv_path) : array();
    kg_send_mail(
        $mail_recipient,
        $admin_subject,
        kg_email_wrap('New CV Application', $admin_body),
        $headers,
        $attachments
    );

    /* — Auto-reply to applicant based on status (pooling vs screening) — */
    if ($app_post_id) {
        $initial_status = (isset($_POST['app_purpose']) && $_POST['app_purpose'] === 'pooling') ? 'pooling' : 'screening';
        kg_notify_applicant_status($app_post_id, $initial_status);
    }

    exit;
}
add_action('wp_ajax_nopriv_kg_submit_application', 'kg_handle_application');
add_action('wp_ajax_kg_submit_application', 'kg_handle_application');

/* ─────────────────────────────────────────────
   HANDLER 3: Quote / Team Builder
   Action: kg_submit_quote
───────────────────────────────────────────── */

function kg_handle_quote()
{
    kg_verify_nonce($_POST['kg_nonce'] ?? '', 'kg_quote_nonce');
    kg_check_honeypot();
    kg_verify_turnstile();

    $name = sanitize_text_field($_POST['quote_name'] ?? '');
    $email = sanitize_email($_POST['quote_email'] ?? '');
    $phone = sanitize_text_field($_POST['quote_phone'] ?? '');
    $roles = json_decode(stripslashes($_POST['quote_roles'] ?? '[]'), true);

    $currency = sanitize_text_field($_POST['quote_currency'] ?? 'USD');
    $discount_p = absint($_POST['quote_discount_percent'] ?? 0);
    $discount_a = sanitize_text_field($_POST['quote_discount_amount'] ?? '0');
    $quote_total = sanitize_text_field($_POST['quote_total'] ?? '');

    $currency_symbols = array(
        'USD' => '$',
        'AUD' => 'A$',
        'PHP' => '₱'
    );
    $sym = $currency_symbols[$currency] ?? '$';

    if (!$name || !is_email($email)) {
        wp_send_json_error(array('message' => 'Please enter your name and work email.'), 422);
    }
    if (empty($roles) || !is_array($roles)) {
        wp_send_json_error(array('message' => 'Please add at least one role to your team.'), 422);
    }

    // Determine exchange rates relative to USD in case we need math
    $rates = array('USD' => 1.0, 'AUD' => 1.5, 'PHP' => 58.0);
    $rate = $rates[$currency] ?? 1.0;

    /* — Build roles table HTML — */
    $roles_rows = '';
    $total_base = 0;
    foreach ($roles as $role) {
        $role_name = sanitize_text_field($role['role'] ?? '');
        $level = sanitize_text_field($role['level'] ?? 'Junior');
        $qty = absint($role['qty'] ?? 1);
        $unit_price = floatval($role['unit_price'] ?? 0);
        $subtotal = floatval($role['subtotal'] ?? ($qty * $unit_price));

        $converted_unit = round($unit_price * $rate);
        $converted_sub = round($subtotal * $rate);
        $total_base += $converted_sub;

        $roles_rows .= '
        <tr>
          <td style="padding:12px 16px;font-size:14px;color:#1a1a2e;border-bottom:1px solid #f0f0f0;">' . esc_html($role_name) . '</td>
          <td style="padding:12px 16px;font-size:14px;color:#1a1a2e;text-align:center;border-bottom:1px solid #f0f0f0;">' . esc_html($level) . ' &times; ' . $qty . '</td>
          <td style="padding:12px 16px;font-size:14px;color:#1a1a2e;text-align:right;border-bottom:1px solid #f0f0f0;">' . $sym . number_format($converted_unit, 0) . '</td>
          <td style="padding:12px 16px;font-size:14px;font-weight:600;color:#0A2540;text-align:right;border-bottom:1px solid #f0f0f0;">' . $sym . number_format($converted_sub, 0) . '</td>
        </tr>';
    }

    $discount_rows_html = '';
    $final_total_val = $total_base;
    if ($discount_p > 0) {
        $discount_amount_val = round($total_base * ($discount_p / 100));
        $final_total_val = $total_base - $discount_amount_val;
        $discount_rows_html = '
        <tr style="background:#fffbeb;">
          <td colspan="3" style="padding:10px 16px;font-size:14px;color:#92400e;text-align:right;font-weight:600;">Volume Discount (' . $discount_p . '%)</td>
          <td style="padding:10px 16px;font-size:14px;font-weight:600;color:#b45309;text-align:right;">-' . $sym . number_format($discount_amount_val, 0) . '</td>
        </tr>';
    }

    $total_row = $discount_rows_html . '
        <tr style="background:#f0fdf9;">
          <td colspan="3" style="padding:14px 16px;font-size:15px;font-weight:700;color:#0A2540;text-align:right;">Estimated Monthly Total</td>
          <td style="padding:14px 16px;font-size:18px;font-weight:700;color:#00D09C;text-align:right;">' . ($quote_total ?: ($sym . number_format($final_total_val, 0) . '/mo')) . '</td>
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

    $to_email = defined('KG_QUOTE_EMAIL') ? KG_QUOTE_EMAIL : (defined('KG_ADMIN_EMAIL') ? KG_ADMIN_EMAIL : 'hr@kingsgroup.com.ph');

    $quote_total_display = ($quote_total ?: ($sym . number_format($final_total_val, 0) . '/mo'));

    /* — Email to Kings Group (Admin Notification) — */
    $parsed_admin = kg_get_parsed_email('quote_admin', array(
        '{client_name}' => $name,
        '{client_email}' => $email,
        '{client_phone}' => $phone ?: '—',
        '{quote_total}' => $quote_total_display,
        '{quote_details}' => $roles_table,
    ));

    $admin_subject = $parsed_admin ? $parsed_admin['subject'] : 'Quote Request from ' . $name . ' — ' . $quote_total_display;
    $admin_body = kg_email_heading($parsed_admin ? $parsed_admin['heading'] : 'Service Proposal Request Notification') . ($parsed_admin ? $parsed_admin['body'] : '');
    if ($parsed_admin && !empty($parsed_admin['banner'])) {
        $admin_body .= kg_email_banner($parsed_admin['banner']);
    }
    if ($parsed_admin && !empty($parsed_admin['btn_text']) && !empty($parsed_admin['btn_link'])) {
        $admin_body .= kg_email_button($parsed_admin['btn_text'], $parsed_admin['btn_link']);
    }

    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'Reply-To: ' . $name . ' <' . $email . '>',
    );

    /* — Save to WP Admin (Quote Leads CPT) — */
    if (function_exists('kg_save_quote_lead_post')) {
        kg_save_quote_lead_post(array(
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'total' => $final_total_val, // Save final calculated USD base total or converted total
            'roles' => $roles,
        ));
    }

    /* — Respond to browser immediately, send emails after — */
    kg_flush_response(array(
        'message' => 'Quote submitted! Check your email for your team configuration summary.',
        'total' => $quote_total_display,
    ));

    $recipients = array($to_email);
    if (function_exists('kg_get_hr_emails')) {
        $recipients = array_merge($recipients, kg_get_hr_emails());
        $recipients = array_unique($recipients);
    }

    kg_send_mail(
        $recipients,
        $admin_subject,
        kg_email_wrap('Service Proposal Request', $admin_body),
        $headers
    );

    /* — Confirmation email to client — */
    $parsed_client = kg_get_parsed_email('quote_client', array(
        '{name}' => esc_html($name),
        '{quote_total}' => $quote_total_display,
        '{quote_details}' => $roles_table,
    ));

    $client_subject = $parsed_client ? $parsed_client['subject'] : 'Your Kings Manpower Service Proposal — ' . $quote_total_display;
    $client_body = kg_email_heading($parsed_client ? $parsed_client['heading'] : 'Proposal Request Acknowledgment') . ($parsed_client ? $parsed_client['body'] : '');
    if ($parsed_client && !empty($parsed_client['banner'])) {
        $client_body .= kg_email_banner($parsed_client['banner']);
    }
    if ($parsed_client && !empty($parsed_client['btn_text']) && !empty($parsed_client['btn_link'])) {
        $client_body .= kg_email_button($parsed_client['btn_text'], $parsed_client['btn_link']);
    }

    kg_send_mail(
        $email,
        $client_subject,
        kg_email_wrap($client_subject, $client_body),
        array('Content-Type: text/html; charset=UTF-8')
    );

    exit;
}
add_action('wp_ajax_nopriv_kg_submit_quote', 'kg_handle_quote');
add_action('wp_ajax_kg_submit_quote', 'kg_handle_quote');
