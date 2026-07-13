<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <?php
  global $page_title, $page_description, $page_og_image, $page_hero_bg, $page_schema;

  // --- SEO: Build page URL ---
  // Use WordPress home_url() when available, else reconstruct from server vars.
  $kg_site_url = function_exists('get_bloginfo') ? get_bloginfo('url') : 'https://kingsgroup.com.ph';
  $kg_current_url = function_exists('get_permalink') && get_the_ID()
    ? get_permalink()
    : rtrim($kg_site_url, '/') . (isset($_SERVER['REQUEST_URI']) ? strtok($_SERVER['REQUEST_URI'], '?') : '/');

  // --- SEO: Resolve per-page values ---
  if (!empty($page_title)) {
      $kg_title = (strpos($page_title, 'Kings Group') === false) ? $page_title . ' | The Kings Group' : $page_title;
  } else {
      $kg_title = 'The Kings Group';
  }
  $kg_description = $page_description ?? 'Elite talent acquisition and ethical staffing solutions. Discover Kings Group\'s managed services and labor management for businesses.';
  $kg_og_image = $page_og_image ?? kg_asset('img/[LOGO] Main Logo White.webp');
  ?>

  <?php if (!function_exists('_wp_render_title_tag')): ?>
    <title><?php echo esc_html($kg_title); ?></title>
  <?php endif; ?>
  <meta name="description" content="<?php echo esc_attr($kg_description); ?>">
  <link rel="canonical" href="<?php echo esc_url($kg_current_url); ?>">
  <?php if (!empty($page_hero_bg)): ?>
    <link rel="preload" as="image" href="<?php echo esc_url($page_hero_bg); ?>" fetchpriority="high">
  <?php endif; ?>

  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website">
  <meta property="og:url" content="<?php echo esc_url($kg_current_url); ?>">
  <meta property="og:title" content="<?php echo esc_attr($kg_title); ?>">
  <meta property="og:description" content="<?php echo esc_attr($kg_description); ?>">
  <meta property="og:image" content="<?php echo esc_url($kg_og_image); ?>">
  <meta property="og:site_name" content="Kings Group Cooperative">
  <meta property="og:locale" content="en_PH">

  <!-- Twitter / X -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:site" content="@kingsgroup">
  <meta name="twitter:title" content="<?php echo esc_attr($kg_title); ?>">
  <meta name="twitter:description" content="<?php echo esc_attr($kg_description); ?>">
  <meta name="twitter:image" content="<?php echo esc_url($kg_og_image); ?>">

  <!-- Robots & Index -->
  <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">

  <link rel="icon" type="image/webp" href="<?php echo kg_asset('img/[LOGO] Main Logo White.webp'); ?>">
  <link rel="apple-touch-icon" href="<?php echo kg_asset('img/[LOGO] Main Logo White.webp'); ?>">

  <!-- Modern Typefaces are loaded in style.css -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/css/intlTelInput.css">
  <style>
    .iti { width: 100%; display: block; } /* Ensure it takes full width of container */
    .iti input[type="tel"] { padding-left: 52px !important; }
  </style>
  <?php wp_head(); ?>

  <!-- SEO: Sitewide Organization Schema (on every page) -->
  <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "@id": "<?php echo esc_url($kg_site_url); ?>/#organization",
      "name": "Kings Group Cooperative",
      "url": "<?php echo esc_url($kg_site_url); ?>",
      "logo": {
        "@type": "ImageObject",
        "url": "<?php echo esc_url(kg_asset('img/[LOGO] Main Logo Black.webp')); ?>"
      },
      "sameAs": [
        "https://www.linkedin.com/company/kings-group-cooperative",
        "https://www.facebook.com/KingsCooperative"
      ],
      "contactPoint": {
        "@type": "ContactPoint",
        "telephone": "+63-2-8776-6712",
        "contactType": "customer service",
        "areaServed": "PH",
        "availableLanguage": ["English", "Filipino"]
      },
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "100 Doña Soledad Ave, Better Living Subdivision",
        "addressLocality": "Parañaque",
        "addressRegion": "Metro Manila",
        "postalCode": "1711",
        "addressCountry": "PH"
      },
      "description": "Elite talent acquisition and ethical staffing solutions since 1999. A worker-owned cooperative empowering global teams."
    }
    </script>

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