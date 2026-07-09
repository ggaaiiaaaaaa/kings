<?php
/**
 * Shared HTML email template builder for all Kings Group forms.
 * Usage: kg_email_wrap( $subject, $body_html ) → returns full HTML string
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function kg_email_wrap( $subject, $body_html, $recipient_name = '', $recipient_address = '', $date = '' ) {
    $saved = get_option( 'kg_email_templates', array() );
    $branding = isset($saved['branding']) ? $saved['branding'] : array();

    // Fallbacks
    $logo_url = !empty($branding['logo_url']) ? $branding['logo_url'] : (
        function_exists('get_template_directory_uri')
            ? get_template_directory_uri() . '/img/[LOGO]%20Kings%20Manpower%20with%20COOP%20-%20Black.png'
            : 'https://kingsgroup.com.ph/wp-content/themes/kingsgroup/img/[LOGO]%20Kings%20Manpower%20with%20COOP%20-%20Black.png'
    );
    $body_bg = '#F7F9FC';
    $card_bg = '#ffffff';
    $primary_color = '#0A2540';
    $button_bg = '#FFD166';
    $button_text_color = '#0A2540';
    $footer_address = !empty($branding['footer_address']) ? $branding['footer_address'] : '100 Doña Soledad Ave, Better Living, Parañaque, 1711 Philippines';
    $footer_phone = !empty($branding['footer_phone']) ? $branding['footer_phone'] : '+63 2 8-776-6712 | +63 2 7-738-8071';
    $footer_web_text = !empty($branding['footer_web_text']) ? $branding['footer_web_text'] : 'kingsgroup.com.ph';

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
<body style="margin:0;padding:0;background:' . esc_attr($body_bg) . ';font-family:\'Garet\',\'Segoe UI\',Arial,sans-serif;-webkit-font-smoothing:antialiased;">
<style type="text/css">
@import url(\'https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@400;700&display=swap\');
@import url(\'https://fonts.cdnfonts.com/css/garet\');
</style>
<table width="100%" cellpadding="0" cellspacing="0" style="background:' . esc_attr($body_bg) . ';padding:50px 0;">
  <tr><td align="center">
    <table width="650" cellpadding="0" cellspacing="0" style="max-width:650px;width:100%;background:' . esc_attr($card_bg) . ';border:1px solid #e5e9f0;box-shadow:0 8px 24px rgba(10,37,64,0.04);border-radius:4px;overflow:hidden;">

      <!-- Letterhead Header -->
      <tr>
        <td align="center" style="padding:40px 0 25px;">
          <img src="' . esc_url($logo_url) . '" alt="Kings Group" width="160" style="display:block; margin:0 auto; width:160px; max-width:100%; height:auto; border:none;">
        </td>
      </tr>

      <!-- Top Diagonal Striped Ribbon -->
      <tr>
        <td style="padding:0;">
          <div style="height:12px;background:' . esc_attr($primary_color) . ';background-image:repeating-linear-gradient(-45deg,' . esc_attr($button_bg) . ',' . esc_attr($button_bg) . 8 . 'px,' . esc_attr($primary_color) . 8 . 'px,' . esc_attr($primary_color) . 16 . 'px,#d62246 16px,#d62246 24px,#ffffff 24px,#ffffff 32px);line-height:12px;font-size:1px;">&nbsp;</div>
        </td>
      </tr>

      <!-- To / Date Block -->
      ' . ( !empty($recipient_name) ? '
      <tr>
        <td style="padding:30px 45px 0;">
          <table width="100%" cellpadding="0" cellspacing="0" style="width:100%;">
            <tr>
              <td style="vertical-align:top;text-align:left;font-family:\'Garet\',\'Segoe UI\',Arial,sans-serif;">
                <p style="margin:0 0 6px;font-size:14px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.5px;">To:</p>
                <p style="margin:0 0 4px;font-size:16px;font-weight:700;color:' . esc_attr($primary_color) . ';">' . esc_html($recipient_name) . '</p>
                ' . ( !empty($recipient_address) ? '<p style="margin:0;font-size:14px;color:#4c566a;line-height:1.4;">' . nl2br(esc_html($recipient_address)) . '</p>' : '' ) . '
              </td>
              <td style="vertical-align:top;text-align:right;width:180px;font-family:\'Garet\',\'Segoe UI\',Arial,sans-serif;">
                <p style="margin:0;font-size:15px;font-weight:700;color:' . esc_attr($primary_color) . ';">' . esc_html($date ? $date : date_i18n(get_option('date_format'))) . '</p>
              </td>
            </tr>
          </table>
        </td>
      </tr>
      ' : '' ) . '

      <!-- Body -->
      <tr>
        <td style="padding:45px;color:#3b4252;font-family:\'Garet\',\'Segoe UI\',Arial,sans-serif;font-size:15px;line-height:1.6;">
          ' . $body_html . '

          <!-- Letterhead Signature / Regards block -->
          <table width="100%" cellpadding="0" cellspacing="0" style="margin-top:40px;width:100%;">
            <tr>
              <td align="right" style="font-family:\'Garet\',\'Segoe UI\',Arial,sans-serif;text-align:right;color:' . esc_attr($primary_color) . ';">
                <p style="margin:0 0 20px;font-size:15px;font-weight:500;">Regards,</p>
                <p style="margin:0;font-size:16px;font-weight:700;">Kings Recruitment Team</p>
                <p style="margin:0;font-size:13px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">KINGS COOP</p>
              </td>
            </tr>
          </table>
        </td>
      </tr>

      <!-- Bottom Diagonal Striped Ribbon -->
      <tr>
        <td style="padding:0;">
          <div style="height:12px;background:' . esc_attr($primary_color) . ';background-image:repeating-linear-gradient(-45deg,' . esc_attr($button_bg) . ',' . esc_attr($button_bg) . 8 . 'px,' . esc_attr($primary_color) . 8 . 'px,' . esc_attr($primary_color) . 16 . 'px,#d62246 16px,#d62246 24px,#ffffff 24px,#ffffff 32px);line-height:12px;font-size:1px;">&nbsp;</div>
        </td>
      </tr>

      <!-- Contact Details Footer -->
      <tr>
        <td align="center" style="padding:20px 45px 30px;background:' . esc_attr($card_bg) . ';border-radius:0 0 4px 4px;">
          <table cellpadding="0" cellspacing="0" style="width:100%;border-collapse:collapse;font-family:\'Garet\',\'Segoe UI\',Arial,sans-serif;font-size:12px;color:#4c566a;">
            <tr>
              <td align="center" style="padding:4px 0;line-height:1.6;">
                <span style="font-size:14px;margin-right:4px;vertical-align:middle;">📍</span> 
                <strong>Kings Headquarters:</strong> ' . esc_html($footer_address) . '
              </td>
            </tr>
            <tr>
              <td align="center" style="padding:4px 0;line-height:1.6;">
                <span style="font-size:14px;margin-right:4px;vertical-align:middle;">📞</span> 
                <strong>Phone:</strong> ' . esc_html($footer_phone) . '
                &nbsp;&nbsp;&nbsp;&nbsp;
                <span style="font-size:14px;margin-left:10px;margin-right:4px;vertical-align:middle;">🌐</span> 
                <strong>Web:</strong> <a href="https://kingsgroup.com.ph" target="_blank" style="color:' . esc_attr($primary_color) . ';text-decoration:none;font-weight:700;">' . esc_html($footer_web_text) . '</a>
              </td>
            </tr>
          </table>
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
    $primary_color = '#0A2540';

    return '
    <div style="margin-bottom:16px;font-family:\'Garet\',\'Segoe UI\',Arial,sans-serif;font-size:15px;line-height:1.5;">
      <span style="font-size:12px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;display:block;margin-bottom:4px;font-family:\'Lexend Deca\',\'Segoe UI\',sans-serif;">'
        . esc_html($label) .
      ':</span>
      <span style="color:' . esc_attr($primary_color) . ';font-weight:500;">'
        . wp_kses_post($value) .
      '</span>
    </div>';
}

/**
 * Renders a section heading inside an email body.
 */
function kg_email_heading( $text ) {
    $primary_color = '#0A2540';

    return '<h2 style="margin:0 0 24px;font-size:22px;font-weight:700;color:' . esc_attr($primary_color) . ';letter-spacing:-0.02em;font-family:\'Lexend Deca\',\'Segoe UI\',sans-serif;">'
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
    $button_bg = '#FFD166';
    $button_text_color = '#0A2540';

    return '<div style="margin:36px 0;">
      <a href="' . esc_url($url) . '" style="display:inline-block;background:' . esc_attr($button_bg) . ';color:' . esc_attr($button_text_color) . ';padding:14px 36px;font-size:15px;font-weight:700;text-decoration:none;border-radius:4px;letter-spacing:0.02em;font-family:\'Lexend Deca\',\'Segoe UI\',sans-serif;">'
        . esc_html($label) . '</a>
    </div>';
}

/**
 * Renders an info banner (e.g. "We'll reply within 1 business day").
 */
function kg_email_banner( $text ) {
    $button_bg = '#FFD166';
    $primary_color = '#0A2540';

    return '<div style="background:#f8fafc;border-left:4px solid ' . esc_attr($button_bg) . ';padding:16px 20px;margin:28px 0;border-radius:0 4px 4px 0;">
      <p style="margin:0;font-size:14px;color:' . esc_attr($primary_color) . ';font-weight:600;font-family:\'Garet\',\'Segoe UI\',Arial,sans-serif;">' . esc_html($text) . '</p>
    </div>';
}

