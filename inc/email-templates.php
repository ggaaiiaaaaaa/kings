<?php
/**
 * Shared HTML email template builder for all Kings Group forms.
 * Usage: kg_email_wrap( $subject, $body_html ) → returns full HTML string
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function kg_email_wrap( $subject, $body_html ) {
    $logo_url = function_exists('get_template_directory_uri')
        ? get_template_directory_uri() . '/img/[LOGO] Main Logo Black.png'
        : 'https://kingsgroup.com.ph/wp-content/themes/kingsgroup/img/[LOGO] Main Logo Black.png';

    $year = date('Y');

    return '<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>' . esc_html($subject) . '</title>
<!--[if mso]>
<style type="text/css">
body, table, td, p, h1, h2, h3, a {font-family: Arial, Helvetica, sans-serif !important;}
</style>
<![endif]-->
</head>
<body style="margin:0;padding:0;background:#F7F9FC;font-family:\'Garet\',\'Segoe UI\',Arial,sans-serif;-webkit-font-smoothing:antialiased;">
<style type="text/css">
@import url(\'https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@400;700&display=swap\');
@import url(\'https://fonts.cdnfonts.com/css/garet\');
</style>
<table width="100%" cellpadding="0" cellspacing="0" style="background:#F7F9FC;padding:50px 0;">
  <tr><td align="center">
    <table width="650" cellpadding="0" cellspacing="0" style="max-width:650px;width:100%;background:#ffffff;border:1px solid #e5e9f0;border-top:6px solid #0A2540;box-shadow:0 8px 24px rgba(10,37,64,0.04);border-radius:4px;">

      <!-- Letterhead Header -->
      <tr>
        <td style="padding:40px 45px 30px;text-align:left;border-bottom:2px solid #FFD166;">
          <img src="' . esc_url($logo_url) . '" alt="Kings Group" width="180" style="display:block;">
        </td>
      </tr>

      <!-- Body -->
      <tr>
        <td style="padding:45px;color:#3b4252;">
          ' . $body_html . '
        </td>
      </tr>

      <!-- Footer -->
      <tr>
        <td style="background:#f8fafc;padding:30px 45px;text-align:center;border-top:1px solid #e5e9f0;border-radius:0 0 4px 4px;">
          <p style="margin:0 0 6px;color:#64748b;font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;font-family:\'Lexend Deca\',\'Segoe UI\',sans-serif;">
            Kings Group Cooperative
          </p>
          <p style="margin:0 0 12px;color:#94a3b8;font-size:12px;font-family:\'Garet\',\'Segoe UI\',Arial,sans-serif;">
            100 Do&ntilde;a Soledad Ave, Better Living, Para&ntilde;aque, Metro Manila 1711
          </p>
          <p style="margin:0;color:#cbd5e1;font-size:11px;font-family:\'Garet\',\'Segoe UI\',Arial,sans-serif;">
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
 * Renders a key-value detail row inside an email table.
 */
function kg_email_row( $label, $value ) {
    return '
    <tr>
      <td style="padding:14px 16px;font-size:13px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;width:35%;vertical-align:top;border-bottom:1px solid #f1f5f9;font-family:\'Lexend Deca\',\'Segoe UI\',sans-serif;">'
        . esc_html($label) .
      '</td>
      <td style="padding:14px 16px;font-size:15px;color:#0A2540;font-weight:500;vertical-align:top;border-bottom:1px solid #f1f5f9;font-family:\'Garet\',\'Segoe UI\',Arial,sans-serif;">'
        . wp_kses_post($value) .
      '</td>
    </tr>';
}

/**
 * Renders a section heading inside an email body.
 */
function kg_email_heading( $text ) {
    return '<h2 style="margin:0 0 24px;font-size:22px;font-weight:700;color:#0A2540;letter-spacing:-0.02em;font-family:\'Lexend Deca\',\'Segoe UI\',sans-serif;">'
        . esc_html($text) . '</h2>';
}

/**
 * Renders a muted paragraph.
 */
function kg_email_para( $text ) {
    return '<p style="margin:0 0 24px;font-size:15px;color:#4c566a;line-height:1.7;font-family:\'Garet\',\'Segoe UI\',Arial,sans-serif;">'
        . wp_kses_post($text) . '</p>';
}

/**
 * Renders a neutral yellow CTA button.
 */
function kg_email_button( $label, $url ) {
    return '<div style="margin:36px 0;">
      <a href="' . esc_url($url) . '" style="display:inline-block;background:#FFD166;color:#0A2540;padding:14px 36px;font-size:15px;font-weight:700;text-decoration:none;border-radius:4px;letter-spacing:0.02em;font-family:\'Lexend Deca\',\'Segoe UI\',sans-serif;">'
        . esc_html($label) . '</a>
    </div>';
}

/**
 * Renders an info banner (e.g. "We'll reply within 1 business day").
 */
function kg_email_banner( $text ) {
    return '<div style="background:#f8fafc;border-left:4px solid #FFD166;padding:16px 20px;margin:28px 0;border-radius:0 4px 4px 0;">
      <p style="margin:0;font-size:14px;color:#0A2540;font-weight:600;font-family:\'Garet\',\'Segoe UI\',Arial,sans-serif;">' . esc_html($text) . '</p>
    </div>';
}
