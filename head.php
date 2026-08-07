<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <?php
  global $page_hero_bg, $page_schema;
  
  // Site URL fallback (might still be used by schema)
  $kg_site_url = function_exists('get_bloginfo') ? get_bloginfo('url') : 'https://kingsgroup.com.ph';
  ?>

  <?php if (!empty($page_hero_bg)): ?>
    <link rel="preload" as="image" href="<?php echo esc_url($page_hero_bg); ?>" fetchpriority="high">
  <?php endif; ?>

  <link rel="icon" type="image/webp" href="<?php echo kg_asset('img/[LOGO] Main Logo White.webp'); ?>">
  <link rel="apple-touch-icon" href="<?php echo kg_asset('img/[LOGO] Main Logo White.webp'); ?>">

  <!-- Modern Typefaces are loaded in style.css -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/css/intlTelInput.css">
  <style>
    .iti { width: 100%; display: block; } /* Ensure it takes full width of container */
    .iti input[type="tel"] { padding-left: 52px !important; }
  </style>
  <?php wp_head(); ?>

  <!-- Note: Organization schema is now handled by RankMath -->

  <?php
  // --- SEO: Per-page schema injection ---
  // Each template sets $page_schema as a ready-to-encode array before get_header().
  if (!empty($page_schema)):
    echo '<script type="application/ld+json">' . "\n";
    echo json_encode($page_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    echo "\n</script>\n";
  endif;
  ?>
  <script>
    // Client-side geo-routing redirects to prevent issues with server-side caching on GoDaddy
    (function() {
      // Helper to read cookies
      function getCookie(name) {
        var value = "; " + document.cookie;
        var parts = value.split("; " + name + "=");
        if (parts.length == 2) return parts.pop().split(";").shift();
      }

      var geo = getCookie('kg_user_geo');
      // If we don't have a cookie yet, fall back to the server-side detected geo value
      if (!geo) {
        geo = '<?php echo esc_js(kg_get_user_geo()); ?>';
      }
      
      // Inject CSS rules immediately to avoid showing both layouts side-by-side
      if (geo) {
        var style = document.createElement('style');
        if (geo === 'PH') {
          style.innerHTML = '.intl-only { display: none !important; }';
        } else {
          style.innerHTML = '.ph-only { display: none !important; }';
        }
        document.head.appendChild(style);
      }

      // Check if consent has been accepted. If not accepted yet, still allow geo redirection.
      if (!geo) return; 

      var path = window.location.pathname;

      if (geo === 'PH') {
        // PH users trying to access get-a-quote or quote pages
        if (path.indexOf('/quote') !== -1 || path.indexOf('quote.php') !== -1) {
          window.location.replace("<?php echo esc_url(home_url('/our-jobs/')); ?>");
        }
      } else {
        // International users trying to access careers or jobs pages
        if (path.indexOf('/careers') !== -1 || path.indexOf('/our-jobs') !== -1 || path.indexOf('careers.php') !== -1 || path.indexOf('our-jobs.php') !== -1) {
          window.location.replace("<?php echo esc_url(home_url('/quote/')); ?>");
        }
      }
    })();
  </script>
</head>