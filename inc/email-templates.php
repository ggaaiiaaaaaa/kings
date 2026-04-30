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
            Kings Group Cooperative &bull; 100 Do&ntilde;a Soledad Ave, Better Living, Para&ntilde;aque, Metro Manila 1711
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
 * Renders a key-value detail row inside an email table.
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
