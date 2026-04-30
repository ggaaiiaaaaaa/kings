# Phase 6: Forms & Email Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Wire up all three forms (Contact, Careers CV upload, Quote team builder) with real server-side processing, professional branded HTML emails, and local email testing via Mailtrap.

**Architecture:** Each form posts via WordPress AJAX (`admin-ajax.php`) to a dedicated PHP handler in `inc/form-handlers.php`. All emails use a shared HTML template builder in `inc/email-templates.php`. WP Mail SMTP is configured to route through Mailtrap SMTP for local testing. No CF7 plugin needed — pure WordPress AJAX + `wp_mail()`.

**Tech Stack:** PHP 8+, WordPress AJAX (`wp_ajax_nopriv_*`), `wp_mail()`, WP Mail SMTP plugin (free), Mailtrap.io (free SMTP sandbox), honeypot spam protection, WP nonces for CSRF, `wp_handle_upload()` for CV files.

---

## File Structure

| File | Role |
|---|---|
| `inc/form-handlers.php` | All three AJAX handlers — contact, CV upload, quote |
| `inc/email-templates.php` | HTML email builder functions shared by all handlers |
| `functions.php` | `require_once` both new files; register AJAX actions |
| `contact.php` | Replace CF7 shortcode with real HTML form + JS fetch |
| `careers.php` | Wire `submitApplication()` to real AJAX upload |
| `quote.php` | Replace `alert()` in `submitQuote()` with real AJAX call |

---

## Task 1: Set Up Mailtrap for Local Email Testing

**Files:**
- No code files — plugin install + Mailtrap account setup

- [ ] **Step 1: Create free Mailtrap account**

Go to https://mailtrap.io → Sign up free → Go to **Email Testing → Inboxes → My Inbox** → click **SMTP Settings** → select **WordPress** from the integrations dropdown. You will see credentials like:
```
Host: sandbox.smtp.mailtrap.io
Port: 2525
Username: <your-username>
Password: <your-password>
```
Keep this tab open — you'll need these in Step 3.

- [ ] **Step 2: Install WP Mail SMTP plugin**

In WP Admin → Plugins → Add New → search **"WP Mail SMTP"** → Install → Activate.

- [ ] **Step 3: Configure WP Mail SMTP**

Go to WP Admin → WP Mail SMTP → Settings:
- **From Email:** `no-reply@kingsgroup.com.ph`
- **From Name:** `Kings Group Cooperative`
- **Mailer:** Select **Other SMTP**
- **SMTP Host:** `sandbox.smtp.mailtrap.io`
- **Encryption:** TLS
- **SMTP Port:** `2525`
- **Auto TLS:** On
- **Authentication:** On
- **SMTP Username:** (paste from Mailtrap)
- **SMTP Password:** (paste from Mailtrap)
- Click **Save Settings**

- [ ] **Step 4: Send test email**

WP Mail SMTP → Tools → Email Test → Send To: your own email → Send. Check Mailtrap inbox — you should see it arrive. If it does, SMTP is working.

- [ ] **Step 5: Commit**

```bash
git add -A
git commit -m "chore: configure WP Mail SMTP with Mailtrap for local email testing"
```

---

## Task 2: Build the Shared HTML Email Template System

**Files:**
- Create: `inc/email-templates.php`

- [ ] **Step 1: Create `inc/email-templates.php`**

```php
<?php
/**
 * Shared HTML email template builder for all Kings Group forms.
 * Usage: kg_email_wrap( $subject, $body_html ) → returns full HTML string
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function kg_email_wrap( $subject, $body_html ) {
    $logo_url = function_exists('get_template_directory_uri')
        ? get_template_directory_uri() . '/img/[LOGO] Main Logo Black.webp'
        : 'https://kingsgroup.com.ph/wp-content/themes/kingsgroup/img/[LOGO] Main Logo Black.webp';

    $year = date('Y');

    return '<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>' . esc_html($subject) . '</title>
</head>
<body style="margin:0;padding:0;background:#f4f6f9;font-family:\'Segoe UI\',Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6f9;padding:40px 0;">
  <tr><td align="center">
    <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;">

      <!-- Header -->
      <tr>
        <td style="background:#0A2540;padding:32px 40px;text-align:center;border-radius:12px 12px 0 0;">
          <img src="' . esc_url($logo_url) . '" alt="Kings Group" width="160" style="display:block;margin:0 auto;">
        </td>
      </tr>

      <!-- Body -->
      <tr>
        <td style="background:#ffffff;padding:40px;border-left:1px solid #e8ecf0;border-right:1px solid #e8ecf0;">
          ' . $body_html . '
        </td>
      </tr>

      <!-- Footer -->
      <tr>
        <td style="background:#0A2540;padding:24px 40px;text-align:center;border-radius:0 0 12px 12px;">
          <p style="margin:0 0 8px;color:rgba(255,255,255,0.5);font-size:12px;">
            Kings Group Cooperative &bull; 100 Doña Soledad Ave, Better Living, Parañaque, Metro Manila 1711
          </p>
          <p style="margin:0;color:rgba(255,255,255,0.3);font-size:11px;">
            &copy; ' . $year . ' Kings Group Cooperative. All rights reserved.
          </p>
        </td>
      </tr>

    </table>
  </td></tr>
</table>
</body>
</html>';
}

/**
 * Renders a key-value detail row inside an email.
 * Usage: kg_email_row('Name', 'John Doe')
 */
function kg_email_row( $label, $value ) {
    return '
    <tr>
      <td style="padding:10px 16px;font-size:13px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;width:35%;vertical-align:top;border-bottom:1px solid #f0f0f0;">'
        . esc_html($label) .
      '</td>
      <td style="padding:10px 16px;font-size:15px;color:#1a1a2e;vertical-align:top;border-bottom:1px solid #f0f0f0;">'
        . wp_kses_post($value) .
      '</td>
    </tr>';
}

/**
 * Renders a section heading inside an email body.
 */
function kg_email_heading( $text ) {
    return '<h2 style="margin:0 0 20px;font-size:22px;font-weight:700;color:#0A2540;letter-spacing:-0.02em;">'
        . esc_html($text) . '</h2>';
}

/**
 * Renders a muted paragraph.
 */
function kg_email_para( $text ) {
    return '<p style="margin:0 0 24px;font-size:15px;color:#4b5563;line-height:1.7;">'
        . wp_kses_post($text) . '</p>';
}

/**
 * Renders a green CTA button.
 */
function kg_email_button( $label, $url ) {
    return '<div style="text-align:center;margin:28px 0;">
      <a href="' . esc_url($url) . '" style="display:inline-block;background:#00D09C;color:#0A2540;padding:14px 36px;font-size:15px;font-weight:700;text-decoration:none;border-radius:6px;letter-spacing:0.01em;">'
        . esc_html($label) . '</a>
    </div>';
}

/**
 * Renders an info banner (e.g. "We'll reply within 1 business day").
 */
function kg_email_banner( $text ) {
    return '<div style="background:#f0fdf9;border-left:4px solid #00D09C;padding:14px 20px;margin:24px 0;border-radius:0 6px 6px 0;">
      <p style="margin:0;font-size:14px;color:#065f46;font-weight:500;">' . esc_html($text) . '</p>
    </div>';
}
```

- [ ] **Step 2: Verify the file was created**

```bash
ls /c/xampp/htdocs/project3/inc/
```
Expected: `acf-fields.php  data-populator.php  email-templates.php  form-handlers.php` (form-handlers.php added next task)

- [ ] **Step 3: Commit**

```bash
git add inc/email-templates.php
git commit -m "feat: add shared HTML email template builder (kg_email_wrap, helpers)"
```

---

## Task 3: Build the Form Handlers (Contact + Careers + Quote)

**Files:**
- Create: `inc/form-handlers.php`

- [ ] **Step 1: Create `inc/form-handlers.php`**

```php
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
        wp_send_json_success( array( 'message' => 'Thank you!' ) ); // silent pass
    }
}

/* ─────────────────────────────────────────────
   HANDLER 1: Contact Form
   Action: kg_submit_contact
───────────────────────────────────────────── */

function kg_handle_contact() {
    kg_verify_nonce( $_POST['kg_nonce'] ?? '', 'kg_contact_nonce' );
    kg_check_honeypot();

    $name    = sanitize_text_field( $_POST['contact_name']    ?? '' );
    $email   = sanitize_email(      $_POST['contact_email']   ?? '' );
    $subject = sanitize_text_field( $_POST['contact_subject'] ?? 'General Inquiry' );
    $message = sanitize_textarea_field( $_POST['contact_message'] ?? '' );

    if ( ! $name || ! is_email( $email ) || ! $message ) {
        wp_send_json_error( array( 'message' => 'Please fill in all required fields.' ), 422 );
    }

    $admin_email = get_option('admin_email');
    $to_email    = 'info@kingsgroup.com.ph'; // Kings Group inbox

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

    wp_mail( $to_email, 'Contact Inquiry: ' . $subject, kg_email_wrap( 'Contact Inquiry: ' . $subject, $body ), $headers );

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

    wp_mail( $email, 'We received your message — Kings Group', kg_email_wrap( 'Message Received', $reply_body ), array( 'Content-Type: text/html; charset=UTF-8' ) );

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
    $allowed_types = array( 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' );
    $file_type     = mime_content_type( $_FILES['app_cv']['tmp_name'] );
    $file_size     = $_FILES['app_cv']['size'];

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

    /* — Email to Kings Group — */
    $to_email  = 'info@kingsgroup.com.ph';
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

    wp_mail( $to_email, 'New Application: ' . $fullname . ( $role ? ' — ' . $role : '' ), kg_email_wrap( 'New CV Application', $body ), $headers, array( $cv_path ) );

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

    wp_mail( $email, 'Your application to Kings Group has been received', kg_email_wrap( 'Application Received', $reply_body ), array( 'Content-Type: text/html; charset=UTF-8' ) );

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
        $role_name = sanitize_text_field( $role['title'] ?? '' );
        $qty       = absint( $role['qty']   ?? 1 );
        $price     = floatval( $role['price'] ?? 0 );
        $subtotal  = $qty * $price;
        $total_price += $subtotal;
        $roles_rows .= '
        <tr>
          <td style="padding:12px 16px;font-size:14px;color:#1a1a2e;border-bottom:1px solid #f0f0f0;">' . esc_html($role_name) . '</td>
          <td style="padding:12px 16px;font-size:14px;color:#1a1a2e;text-align:center;border-bottom:1px solid #f0f0f0;">' . $qty . '</td>
          <td style="padding:12px 16px;font-size:14px;color:#1a1a2e;text-align:right;border-bottom:1px solid #f0f0f0;">$' . number_format($price, 0) . '</td>
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
          <th style="padding:12px 16px;font-size:12px;font-weight:600;color:rgba(255,255,255,0.7);text-align:center;text-transform:uppercase;letter-spacing:0.05em;">Qty</th>
          <th style="padding:12px 16px;font-size:12px;font-weight:600;color:rgba(255,255,255,0.7);text-align:right;text-transform:uppercase;letter-spacing:0.05em;">Unit/mo</th>
          <th style="padding:12px 16px;font-size:12px;font-weight:600;color:rgba(255,255,255,0.7);text-align:right;text-transform:uppercase;letter-spacing:0.05em;">Subtotal</th>
        </tr>
      </thead>
      <tbody>' . $roles_rows . $total_row . '</tbody>
    </table>';

    /* — Email to Kings Group — */
    $to_email = 'info@kingsgroup.com.ph';
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

    wp_mail( $to_email, 'Quote Request from ' . $name . ' — $' . number_format($total_price, 0) . '/mo', kg_email_wrap( 'New Quote Request', $body ), $headers );

    /* — Confirmation email to client — */
    $client_body = kg_email_heading( 'Your Quote Has Been Received!' )
        . kg_email_para( 'Hi ' . esc_html($name) . ',' )
        . kg_email_para( 'Thank you for your interest in building a team with Kings Group Cooperative. We have received your team configuration and our sales team will prepare a detailed proposal for you.' )
        . '<h3 style="margin:0 0 12px;font-size:16px;font-weight:700;color:#0A2540;">Your Team Configuration Summary</h3>'
        . $roles_table
        . kg_email_banner( 'Our team will reach out within 1 business day with a full proposal and pricing breakdown.' )
        . kg_email_para( 'Questions? Reply to this email or call us at <strong>+63 (2) 87766712</strong>.' )
        . kg_email_button( 'Visit Kings Group', home_url('/') );

    wp_mail( $email, 'Your Kings Group Team Quote — $' . number_format($total_price, 0) . '/mo', kg_email_wrap( 'Quote Received', $client_body ), array( 'Content-Type: text/html; charset=UTF-8' ) );

    wp_send_json_success( array(
        'message' => 'Quote submitted! Check your email for your team configuration summary.',
        'total'   => '$' . number_format($total_price, 0),
    ) );
}
add_action( 'wp_ajax_nopriv_kg_submit_quote', 'kg_handle_quote' );
add_action( 'wp_ajax_kg_submit_quote',        'kg_handle_quote' );
```

- [ ] **Step 2: Commit**

```bash
git add inc/form-handlers.php
git commit -m "feat: add AJAX form handlers for contact, CV upload, and quote with branded emails"
```

---

## Task 4: Register Handlers & Inject Nonces in functions.php

**Files:**
- Modify: `functions.php`

- [ ] **Step 1: Add require and nonce injection after `kingsgroup_scripts()`**

Find this block in `functions.php`:
```php
function kingsgroup_scripts()
{
    wp_enqueue_style('kingsgroup-style', get_stylesheet_uri(), array(), filemtime(get_template_directory() . '/style.css'));
    wp_enqueue_script('kingsgroup-script', get_template_directory_uri() . '/script.js', array(), filemtime(get_template_directory() . '/script.js'), true);
}
add_action('wp_enqueue_scripts', 'kingsgroup_scripts');
```

Replace with:
```php
function kingsgroup_scripts()
{
    wp_enqueue_style('kingsgroup-style', get_stylesheet_uri(), array(), filemtime(get_template_directory() . '/style.css'));
    wp_enqueue_script('kingsgroup-script', get_template_directory_uri() . '/script.js', array(), filemtime(get_template_directory() . '/script.js'), true);

    // Pass AJAX URL and nonces to JS
    wp_localize_script( 'kingsgroup-script', 'KG_AJAX', array(
        'url'            => admin_url('admin-ajax.php'),
        'contact_nonce'  => wp_create_nonce('kg_contact_nonce'),
        'careers_nonce'  => wp_create_nonce('kg_careers_nonce'),
        'quote_nonce'    => wp_create_nonce('kg_quote_nonce'),
    ) );
}
add_action('wp_enqueue_scripts', 'kingsgroup_scripts');

// Load form handlers (registers AJAX actions)
if ( function_exists('add_action') ) {
    require_once get_template_directory() . '/inc/form-handlers.php';
}
```

- [ ] **Step 2: Verify `KG_AJAX` is available in browser**

Open browser DevTools Console on any page and type:
```js
console.log(KG_AJAX)
```
Expected output:
```js
{ url: "http://localhost/project3/wp-admin/admin-ajax.php", contact_nonce: "abc123...", careers_nonce: "def456...", quote_nonce: "ghi789..." }
```

- [ ] **Step 3: Commit**

```bash
git add functions.php
git commit -m "feat: register form handlers and inject KG_AJAX nonces via wp_localize_script"
```

---

## Task 5: Wire Up the Contact Form

**Files:**
- Modify: `contact.php`

- [ ] **Step 1: Replace the CF7 shortcode block with a real form**

Find this block in `contact.php` (lines ~105–116):
```php
<div class="contact-form-wrapper" style="max-width: 800px; margin: 0 auto;">
    <?php 
    if (!empty($form_shortcode)) {
        if (function_exists('do_shortcode')) {
            echo do_shortcode($form_shortcode);
        } else {
            echo '<div class="cf7-placeholder">' . esc_html($form_shortcode) . '</div>';
        }
    }
    ?>
</div>
```

Replace with:
```php
<div class="contact-form-wrapper" style="max-width: 800px; margin: 0 auto;">
    <div id="contact-success" style="display:none;background:#f0fdf9;border:1px solid #00D09C;padding:1.5rem 2rem;margin-bottom:1.5rem;border-radius:8px;">
        <p style="margin:0;color:#065f46;font-weight:600;font-size:1.05rem;" id="contact-success-msg"></p>
    </div>
    <div id="contact-error" style="display:none;background:#fef2f2;border:1px solid #fca5a5;padding:1rem 1.5rem;margin-bottom:1.5rem;border-radius:8px;">
        <p style="margin:0;color:#991b1b;font-size:0.95rem;" id="contact-error-msg"></p>
    </div>

    <form id="kg-contact-form" novalidate>
        <!-- Honeypot -->
        <div style="display:none;" aria-hidden="true">
            <input type="text" name="kg_hp_field" value="" tabindex="-1" autocomplete="off">
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;margin-bottom:1.25rem;">
            <div>
                <label style="display:block;font-size:0.8rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.5rem;">Your Name *</label>
                <input type="text" name="contact_name" required placeholder="e.g. Maria Santos"
                    style="width:100%;padding:0.9rem 1.1rem;border:2px solid var(--border-color);font-family:var(--font-body);font-size:0.95rem;outline:none;transition:var(--transition);"
                    onfocus="this.style.borderColor='var(--main-blue)'" onblur="this.style.borderColor='var(--border-color)'">
            </div>
            <div>
                <label style="display:block;font-size:0.8rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.5rem;">Email Address *</label>
                <input type="email" name="contact_email" required placeholder="you@company.com"
                    style="width:100%;padding:0.9rem 1.1rem;border:2px solid var(--border-color);font-family:var(--font-body);font-size:0.95rem;outline:none;transition:var(--transition);"
                    onfocus="this.style.borderColor='var(--main-blue)'" onblur="this.style.borderColor='var(--border-color)'">
            </div>
        </div>
        <div style="margin-bottom:1.25rem;">
            <label style="display:block;font-size:0.8rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.5rem;">Subject</label>
            <input type="text" name="contact_subject" placeholder="How can we help?"
                style="width:100%;padding:0.9rem 1.1rem;border:2px solid var(--border-color);font-family:var(--font-body);font-size:0.95rem;outline:none;transition:var(--transition);"
                onfocus="this.style.borderColor='var(--main-blue)'" onblur="this.style.borderColor='var(--border-color)'">
        </div>
        <div style="margin-bottom:1.75rem;">
            <label style="display:block;font-size:0.8rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.5rem;">Message *</label>
            <textarea name="contact_message" required rows="6" placeholder="Tell us about your inquiry..."
                style="width:100%;padding:0.9rem 1.1rem;border:2px solid var(--border-color);font-family:var(--font-body);font-size:0.95rem;outline:none;resize:vertical;transition:var(--transition);"
                onfocus="this.style.borderColor='var(--main-blue)'" onblur="this.style.borderColor='var(--border-color)'"></textarea>
        </div>
        <button type="submit" id="contact-submit" class="btn btn-primary" style="padding:1rem 2.5rem;font-size:1rem;width:100%;">
            Send Message
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 2L11 13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
        </button>
    </form>

    <script>
    document.getElementById('kg-contact-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = document.getElementById('contact-submit');
        const successBox = document.getElementById('contact-success');
        const errorBox   = document.getElementById('contact-error');
        successBox.style.display = 'none';
        errorBox.style.display   = 'none';

        btn.disabled = true;
        btn.textContent = 'Sending…';

        const form = new FormData(this);
        form.append('action',    'kg_submit_contact');
        form.append('kg_nonce',  KG_AJAX.contact_nonce);

        fetch(KG_AJAX.url, { method: 'POST', body: form })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    successBox.style.display = 'block';
                    document.getElementById('contact-success-msg').textContent = data.data.message;
                    this.reset();
                } else {
                    errorBox.style.display = 'block';
                    document.getElementById('contact-error-msg').textContent = data.data.message;
                }
            })
            .catch(() => {
                errorBox.style.display = 'block';
                document.getElementById('contact-error-msg').textContent = 'Network error. Please try again.';
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = 'Send Message <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 2L11 13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>';
            });
    });
    </script>
</div>
```

- [ ] **Step 2: Test the contact form**

1. Open `http://localhost/project3/contact/`
2. Fill in Name, Email, Message → Submit
3. Check Mailtrap inbox — you should see two emails:
   - **To: info@kingsgroup.com.ph** — "Contact Inquiry: ..."
   - **To: your-test-email** — "We received your message — Kings Group"
4. Verify both emails render correctly with Kings Group branding

- [ ] **Step 3: Commit**

```bash
git add contact.php
git commit -m "feat: wire contact form to AJAX handler with real email sending"
```

---

## Task 6: Wire Up the Careers CV Upload Form

**Files:**
- Modify: `careers.php`

- [ ] **Step 1: Replace `submitApplication()` in careers.php**

Find this function in `careers.php` (the `<script>` block near the bottom):
```js
function submitApplication() {
    // Capture form data for review
    const cvFile = cvInput.files.length > 0 ? cvInput.files[0].name : '—';
    ...
    document.getElementById('success-modal').classList.add('visible');
    document.body.style.overflow = 'hidden';
}
```

Replace the entire `submitApplication()` function with:
```js
function submitApplication() {
    const fname    = document.getElementById('app-fname').value.trim();
    const lname    = document.getElementById('app-lname').value.trim();
    const email    = document.getElementById('app-email').value.trim();
    const phone    = document.getElementById('app-phone').value.trim();
    const role     = document.getElementById('app-role').value;
    const linkedin = document.getElementById('app-linkedin').value.trim();
    const cvFile   = cvInput.files[0];

    if (!fname || !lname || !email) {
        alert('Please fill in your name and email.');
        return;
    }
    if (!cvFile) {
        alert('Please upload your CV.');
        return;
    }

    const submitBtn = document.querySelector('#step-2 .btn-primary');
    submitBtn.disabled = true;
    submitBtn.textContent = 'Submitting…';

    const formData = new FormData();
    formData.append('action',       'kg_submit_application');
    formData.append('kg_nonce',     KG_AJAX.careers_nonce);
    formData.append('app_fname',    fname);
    formData.append('app_lname',    lname);
    formData.append('app_email',    email);
    formData.append('app_phone',    phone);
    formData.append('app_role',     role);
    formData.append('app_linkedin', linkedin);
    formData.append('app_cv',       cvFile, cvFile.name);

    fetch(KG_AJAX.url, { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                // Populate review panel
                document.getElementById('rev-cv').textContent      = cvFile.name;
                document.getElementById('rev-name').textContent    = fname + ' ' + lname;
                document.getElementById('rev-email').textContent   = email;
                document.getElementById('rev-phone').textContent   = phone || '—';
                document.getElementById('rev-role').textContent    = role  || '—';
                document.getElementById('rev-linkedin').textContent = linkedin || '—';

                document.getElementById('modal-buttons').style.display = 'flex';
                document.getElementById('modal-review').style.display  = 'none';
                document.getElementById('success-modal').classList.add('visible');
                document.body.style.overflow = 'hidden';
            } else {
                alert(data.data.message || 'Submission failed. Please try again.');
            }
        })
        .catch(() => alert('Network error. Please try again.'))
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Submit Application';
        });
}
```

- [ ] **Step 2: Test the careers form**

1. Open `http://localhost/project3/careers/`
2. Upload a PDF, fill in name/email → Submit Application
3. Check Mailtrap:
   - **To: info@kingsgroup.com.ph** — "New Application: [Name] — [Role]" with CV attached
   - **To: applicant email** — "Your application to Kings Group has been received"
4. Check WP Admin → Media — the uploaded CV should appear there

- [ ] **Step 3: Commit**

```bash
git add careers.php
git commit -m "feat: wire careers CV upload to AJAX handler — saves file, sends branded emails"
```

---

## Task 7: Wire Up the Quote Team Builder

**Files:**
- Modify: `quote.php`

- [ ] **Step 1: Replace `submitQuote()` in quote.php**

Find this function in `quote.php`:
```js
function submitQuote() {
    alert("Thanks! Your team configuration has been successfully submitted to Kings.");
    cartData.length = 0;
    document.getElementById('quoteName').value = '';
    document.getElementById('quoteEmail').value = '';
    updateCartUI();
}
```

Replace with:
```js
function submitQuote() {
    const name  = document.getElementById('quoteName').value.trim();
    const email = document.getElementById('quoteEmail').value.trim();
    const btn   = document.getElementById('btnSubmitQuote');

    if (!name || !email) return;

    btn.disabled = true;
    btn.textContent = 'Sending…';

    const formData = new FormData();
    formData.append('action',      'kg_submit_quote');
    formData.append('kg_nonce',    KG_AJAX.quote_nonce);
    formData.append('quote_name',  name);
    formData.append('quote_email', email);
    formData.append('quote_roles', JSON.stringify(
        cartData.map(item => ({
            title: item.title,
            qty:   item.qty,
            price: item.price
        }))
    ));

    fetch(KG_AJAX.url, { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                alert('Quote submitted! Check your email for your team configuration summary.');
                cartData.length = 0;
                document.getElementById('quoteName').value  = '';
                document.getElementById('quoteEmail').value = '';
                updateCartUI();
            } else {
                alert(data.data.message || 'Submission failed. Please try again.');
            }
        })
        .catch(() => alert('Network error. Please try again.'))
        .finally(() => {
            btn.disabled = false;
            btn.textContent = 'Request Detailed Quote';
        });
}
```

- [ ] **Step 2: Test the quote form**

1. Open `http://localhost/project3/quote/`
2. Add 2-3 roles to the cart, enter name + email → Request Detailed Quote
3. Check Mailtrap:
   - **To: info@kingsgroup.com.ph** — "Quote Request from [Name] — $X/mo" with full roles table
   - **To: client email** — "Your Kings Group Team Quote — $X/mo" with branded summary
4. Verify the total amount matches what was shown in the cart

- [ ] **Step 3: Commit**

```bash
git add quote.php
git commit -m "feat: wire quote team builder to AJAX handler — sends branded quote emails to client and Kings Group"
```

---

## Task 8: Final Testing Checklist

- [ ] **Contact form** — submit → both emails arrive in Mailtrap with correct branding
- [ ] **Contact form** — submit empty → shows inline error, no page reload
- [ ] **Contact form** — honeypot test: open browser console → `document.querySelector('[name=kg_hp_field]').value = 'bot'` → submit → silent success, no emails sent
- [ ] **Careers form** — upload PDF + fill info → submit → CV arrives attached in Mailtrap, auto-reply goes to applicant email
- [ ] **Careers form** — upload file > 5MB → shows "File size must be under 5 MB" error
- [ ] **Careers form** — upload .jpg file → shows "Only PDF or DOCX files are accepted" error
- [ ] **Quote form** — add roles, submit → both emails arrive with matching totals
- [ ] **Quote form** — submit with empty cart → shows "Please add at least one role" error
- [ ] **All email templates** — logos visible, colors correct (#0A2540 header, #00D09C button), footer shows address
- [ ] **Commit final state**

```bash
git add -A
git commit -m "test: all three forms verified working end-to-end with Mailtrap"
```
