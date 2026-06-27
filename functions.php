<?php
/**
 * Compatibility Shim: Allows the site to run on localhost without WordPress.
 */
if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(__FILE__) . '/');
}

if (!function_exists('get_header')) {
    function get_header($name = null)
    {
        if ($name === 'minimal') {
            get_template_part('header-minimal');
        } else {
            get_template_part('header');
        }
    }
}

if (!function_exists('get_footer')) {
    function get_footer()
    {
        get_template_part('footer');
    }
}

if (!function_exists('get_template_part')) {
    function get_template_part($slug)
    {
        require_once $slug . '.php';
    }
}

if (!function_exists('home_url')) {
    function home_url($path = '')
    {
        // If path is empty or just '/', return index.php
        if (empty($path) || $path === '/') {
            return 'index.php';
        }

        $clean_path = trim($path, '/');

        // Handle anchors (e.g. /story/#vision)
        $parts = explode('#', $clean_path);
        $base = $parts[0];
        $anchor = isset($parts[1]) ? '#' . $parts[1] : '';

        // If the base doesn't have an extension, add .php for standalone mode
        if (!empty($base) && strpos($base, '.') === false) {
            $base .= '.php';
        }

        return $base . $anchor;
    }
}

if (!function_exists('esc_url')) {
    function esc_url($url)
    {
        return $url;
    }
}

if (!function_exists('wp_kses_post')) {
    function wp_kses_post($text)
    {
        return $text;
    }
}

if (!function_exists('esc_html__')) {
    function esc_html__($text, $domain)
    {
        return $text;
    }
}

if (!function_exists('esc_html')) {
    function esc_html($text)
    {
        return $text;
    }
}

if (!function_exists('esc_attr')) {
    function esc_attr($text)
    {
        return $text;
    }
}

if (!function_exists('__')) {
    function __($text, $domain = '')
    {
        return $text;
    }
}

if (!function_exists('_e')) {
    function _e($text, $domain = '')
    {
        echo $text;
    }
}

if (!function_exists('_x')) {
    function _x($text, $context, $domain = '')
    {
        return $text;
    }
}

if (!function_exists('is_home')) {
    function is_home()
    {
        return false;
    }
}

if (!function_exists('is_archive')) {
    function is_archive()
    {
        return false;
    }
}

if (!function_exists('have_posts')) {
    function have_posts()
    {
        return false;
    }
}

if (!function_exists('the_post')) {
    function the_post()
    {
    }
}

if (!function_exists('is_post_type_archive')) {
    function is_post_type_archive($post_type)
    {
        return false;
    }
}

if (!function_exists('wp_create_nonce')) {
    function wp_create_nonce($action)
    {
        return 'dummy-nonce-shim';
    }
}

if (!function_exists('wp_verify_nonce')) {
    function wp_verify_nonce($nonce, $action)
    {
        return true;
    }
}

if (!function_exists('admin_url')) {
    function admin_url($path)
    {
        return $path;
    }
}

if (!function_exists('wp_send_json_success')) {
    function wp_send_json_success($data)
    {
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'data' => $data]);
        exit;
    }
}

if (!function_exists('wp_send_json_error')) {
    function wp_send_json_error($data, $status = 400)
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'data' => $data]);
        exit;
    }
}

if (!function_exists('add_action')) {
    function add_action($tag, $function)
    {
    }
}

if (!function_exists('add_filter')) {
    function add_filter($tag, $function_to_add, $priority = 10, $accepted_args = 1)
    {
    }
}

if (!function_exists('add_theme_support')) {
    function add_theme_support($feature)
    {
    }
}

if (!function_exists('register_nav_menus')) {
    function register_nav_menus($locations)
    {
    }
}

if (!function_exists('has_nav_menu')) {
    function has_nav_menu($location)
    {
        return false;
    }
}

if (!function_exists('wp_nav_menu')) {
    function wp_nav_menu($args = array())
    {
        return '';
    }
}

if (!function_exists('wp_enqueue_style')) {
    function wp_enqueue_style($handle, $src = '', $deps = array(), $ver = false)
    {
    }
}

if (!function_exists('wp_enqueue_script')) {
    function wp_enqueue_script($handle, $src = '', $deps = array(), $ver = false, $in_footer = false)
    {
    }
}

if (!function_exists('get_stylesheet_uri')) {
    function get_stylesheet_uri()
    {
        return 'style.css';
    }
}

if (!function_exists('get_template_directory_uri')) {
    function get_template_directory_uri()
    {
        return '.';
    }
}

if (!function_exists('get_template_directory')) {
    function get_template_directory()
    {
        return '.';
    }
}

if (!function_exists('wp_head')) {
    function wp_head()
    {
        $ver = file_exists('style.css') ? filemtime('style.css') : time();
        echo '<link rel="stylesheet" href="style.css?v=' . $ver . '">';
    }
}

if (!function_exists('wp_footer')) {
    function wp_footer()
    {
        $ver = file_exists('script.js') ? filemtime('script.js') : time();
        echo '<script src="script.js?v=' . $ver . '"></script>';
    }
}

if (!function_exists('body_class')) {
    function body_class()
    {
    }
}

/**
 * Kings Group functions and definitions
 */

if (!function_exists('kingsgroup_setup')):
    function kingsgroup_setup()
    {
        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        // Let WordPress manage the document title.
        add_theme_support('title-tag');

        // Enable support for Post Thumbnails on posts and pages.
        add_theme_support('post-thumbnails');

        // Register Navigation Menus
        register_nav_menus(
            array(
                'menu-1' => esc_html__('Primary Client Menu', 'kingsgroup'),
                'menu-2' => esc_html__('Primary Applicant Menu', 'kingsgroup'),
                'footer' => esc_html__('Footer Menu', 'kingsgroup'),
            )
        );

        // Switch default core markup for search form, comment form, and comments to output valid HTML5.
        add_theme_support(
            'html5',
            array(
                'search-form',
                'comment-form',
                'comment-list',
                'gallery',
                'caption',
                'style',
                'script',
            )
        );

        // Register named image sizes for WP Media Library uploads
        add_image_size('kg-hero', 1920, 800, true);
        add_image_size('kg-card', 600, 400, true);
        add_image_size('kg-thumbnail', 300, 200, true);
    }
endif;
add_action('after_setup_theme', 'kingsgroup_setup');

/**
 * Auto-create and assign nav menus if they don't exist yet.
 * Runs once on init; skips silently if menus are already set up.
 */
function kg_create_default_menus()
{
    // Client nav (left side) — About dropdown and Get a Quote are hardcoded in header.php
    $client_items = array(
        array('title' => 'Home', 'url' => home_url('/')),
    );

    // Applicant nav (right side)
    $applicant_items = array(
        array('title' => 'Our Jobs', 'url' => home_url('/our-jobs/')),
        array('title' => 'Apply Now', 'url' => home_url('/careers/')),
        array('title' => 'Member Portal', 'url' => 'https://zckings.azurewebsites.net/'),
        array('title' => 'Log In', 'url' => 'https://zckings.azurewebsites.net/'),
    );

    // Footer nav
    $footer_items = array(
        array('title' => 'Our Story', 'url' => home_url('/story/')),
        array('title' => 'News', 'url' => home_url('/news/')),
        array('title' => 'Community', 'url' => home_url('/community/')),
        array('title' => 'Careers', 'url' => home_url('/careers/')),
        array('title' => 'Contact Us', 'url' => home_url('/contact/')),
        array('title' => 'Member Portal', 'url' => 'https://zckings.azurewebsites.net/'),
        array('title' => 'Kings Lending', 'url' => 'https://kingslending.timefree.ph/'),
        array('title' => 'Benefits', 'url' => home_url('/benefits/')),
        array('title' => 'Terms of Service', 'url' => home_url('/terms/')),
        array('title' => 'Privacy Policy', 'url' => home_url('/privacy/')),
    );

    $menus = array(
        'menu-1' => array('name' => 'Primary Client Menu', 'items' => $client_items),
        'menu-2' => array('name' => 'Primary Applicant Menu', 'items' => $applicant_items),
        'footer' => array('name' => 'Footer Menu', 'items' => $footer_items),
    );

    foreach ($menus as $location => $config) {
        $existing = wp_get_nav_menu_object($config['name']);

        if ($existing) {
            // Menu already exists — just ensure it is assigned to the theme location
            $locations = get_theme_mod('nav_menu_locations', array());
            if (empty($locations[$location]) || $locations[$location] !== $existing->term_id) {
                $locations[$location] = $existing->term_id;
                set_theme_mod('nav_menu_locations', $locations);
            }
            continue;
        }

        // Menu does not exist — create it with default items
        $menu_id = wp_create_nav_menu($config['name']);
        if (is_wp_error($menu_id))
            continue;

        foreach ($config['items'] as $item) {
            wp_update_nav_menu_item($menu_id, 0, array(
                'menu-item-title' => $item['title'],
                'menu-item-url' => $item['url'],
                'menu-item-status' => 'publish',
                'menu-item-type' => 'custom',
            ));
        }

        // Assign to theme location
        $locations = get_theme_mod('nav_menu_locations', array());
        $locations[$location] = $menu_id;
        set_theme_mod('nav_menu_locations', $locations);
    }
}
add_action('init', 'kg_create_default_menus');

/**
 * Auto-add menu-btn-primary class to "Get a Quote" nav item.
 */
function kg_nav_item_classes($classes, $item)
{
    if ($item->title === 'Get a Quote') {
        $classes[] = 'menu-btn-primary';
    }
    return $classes;
}
add_filter('nav_menu_css_class', 'kg_nav_item_classes', 10, 2);

/**
 * Enqueue scripts and styles.
 */
function kingsgroup_scripts()
{
    wp_enqueue_style('kingsgroup-style', get_stylesheet_uri(), array(), filemtime(get_template_directory() . '/style.css'));
    wp_enqueue_script('kingsgroup-script', get_template_directory_uri() . '/script.js', array(), filemtime(get_template_directory() . '/script.js'), true);

    // Cloudflare Turnstile API
    wp_enqueue_script('cf-turnstile', 'https://challenges.cloudflare.com/turnstile/v0/api.js', array(), null, true);

    // Pass AJAX URL and nonces to JS — available as KG_AJAX.url, KG_AJAX.contact_nonce, etc.
    wp_localize_script('kingsgroup-script', 'KG_AJAX', array(
        'url' => admin_url('admin-ajax.php'),
        'contact_nonce' => wp_create_nonce('kg_contact_nonce'),
        'careers_nonce' => wp_create_nonce('kg_careers_nonce'),
        'quote_nonce' => wp_create_nonce('kg_quote_nonce'),
        'turnstile_site_key' => defined('CF_TURNSTILE_SITE_KEY') ? CF_TURNSTILE_SITE_KEY : '',
    ));
}
add_action('wp_enqueue_scripts', 'kingsgroup_scripts');

// Load form handlers — registers all three wp_ajax_* actions
if (function_exists('add_action')) {
    require_once get_template_directory() . '/inc/form-handlers.php';
}

/**
 * Helper function for asset paths (Presentation Safe)
 */
function kg_asset($path)
{
    if (function_exists('get_template_directory_uri')) {
        return get_template_directory_uri() . '/' . ltrim($path, '/');
    }
    return $path;
}

/**
 * Helper: Outputs an <img> tag from ACF field value, or a styled "No Image" placeholder.
 * Usage: <?php echo kg_img($url, 'Alt text', 'extra-class'); ?>
 * - $url: The image URL (from get_field). If empty/false, shows placeholder.
 * - $alt: Alt text for accessibility.
 * - $class: Optional CSS class for the <img> or placeholder div.
 * - $style: Optional inline style string.
 */
/**
 * Outputs an <img> tag from a URL, or a styled "No Image" placeholder.
 * $loading: 'lazy' (default for below-fold images) or 'eager' (for LCP hero images).
 */
function kg_img($url, $alt = 'Image', $class = '', $style = '', $loading = 'lazy', $fetchpriority = '')
{
    $style_attr = $style ? ' style="' . esc_attr($style) . '"' : '';
    $loading_attr = $loading ? ' loading="' . esc_attr($loading) . '"' : '';
    $priority_attr = $fetchpriority ? ' fetchpriority="' . esc_attr($fetchpriority) . '"' : '';
    if (!empty($url)) {
        return '<img src="' . esc_url($url) . '" alt="' . esc_attr($alt) . '" class="' . esc_attr($class) . '"' . $loading_attr . $style_attr . $priority_attr . '>';
    }
    return '<div class="kg-no-image ' . esc_attr($class) . '"' . $style_attr . '><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg><span>No Image</span></div>';
}

/**
 * Returns the .webp version of an image path if that file exists on disk.
 * Falls back to the original path if no .webp counterpart is found.
 * Usage: kg_asset( kg_webp('img/logo.png') )
 */
function kg_webp($path)
{
    $webp_path = preg_replace('/\.(png|jpg|jpeg)$/i', '.webp', $path);
    if ($webp_path === $path) {
        return $path;
    }
    $base = function_exists('get_template_directory')
        ? get_template_directory()
        : __DIR__;
    $abs = rtrim($base, '/') . '/' . ltrim($webp_path, '/');
    return file_exists($abs) ? $webp_path : $path;
}

// Include ACF programmatic field definitions
if (file_exists(get_template_directory() . '/inc/acf-fields.php')) {
    require_once get_template_directory() . '/inc/acf-fields.php';
}

// Applications CPT (career form submissions)
if (file_exists(get_template_directory() . '/inc/cpt-applications.php')) {
    require_once get_template_directory() . '/inc/cpt-applications.php';
}

// Interview Scheduler Notification Engine
if (file_exists(get_template_directory() . '/inc/interview-notifier.php')) {
    require_once get_template_directory() . '/inc/interview-notifier.php';
}

// Inquiries + Quote Leads CPTs (contact and quote form submissions)
if (file_exists(get_template_directory() . '/inc/cpt-inquiries.php')) {
    require_once get_template_directory() . '/inc/cpt-inquiries.php';
}

// Testimonials CPT — replaces ACF testi_1_* through testi_4_* fields
if (file_exists(get_template_directory() . '/inc/cpt-testimonials.php')) {
    require_once get_template_directory() . '/inc/cpt-testimonials.php';
}

/**
 * Safely get ACF field value, allowing intentional empty strings.
 */
function kg_get_field($field_name, $fallback = '', $post_id = null)
{
    if (function_exists('get_field')) {
        if ($post_id === null) {
            $post_id = get_queried_object_id();
        }
        $value = get_field($field_name, $post_id);
        if ($value !== null && $value !== false) {
            if (is_array($value) && isset($value['url'])) {
                return $value['url'];
            }
            if (is_numeric($value) && (
                strpos($field_name, 'bg') !== false ||
                strpos($field_name, 'img') !== false ||
                strpos($field_name, 'image') !== false
            )) {
                $url = wp_get_attachment_image_url($value, 'full');
                if ($url) {
                    return $url;
                }
            }
            if (is_string($value)) {
                $value = str_replace('Partner for Manpower Solutions', 'Provider for Manpower Solutions', $value);
            }
            return $value;
        }
    }
    if (is_string($fallback)) {
        $fallback = str_replace('Partner for Manpower Solutions', 'Provider for Manpower Solutions', $fallback);
    }
    return $fallback;
}

// Include Auto-Population Script
if (file_exists(get_template_directory() . '/inc/data-populator.php')) {
    require_once get_template_directory() . '/inc/data-populator.php';
}

// ATS Admin Dashboard Widget
if (file_exists(get_template_directory() . '/inc/ats-dashboard.php')) {
    require_once get_template_directory() . '/inc/ats-dashboard.php';
}

// KPI Dashboard Page
if (file_exists(get_template_directory() . '/inc/kpi-dashboard.php')) {
    require_once get_template_directory() . '/inc/kpi-dashboard.php';
}

// Recruiter Account Enhancements & Restrictions
if (file_exists(get_template_directory() . '/inc/recruiter-rules.php')) {
    require_once get_template_directory() . '/inc/recruiter-rules.php';
}


// Jobs Custom Post Type
function kingsgroup_register_jobs_cpt()
{
    $labels = array(
        'name' => _x('Jobs', 'Post type general name', 'kingsgroup'),
        'singular_name' => _x('Job', 'Post type singular name', 'kingsgroup'),
        'menu_name' => _x('Jobs', 'Admin Menu text', 'kingsgroup'),
        'add_new' => __('Add New', 'kingsgroup'),
        'add_new_item' => __('Add New Job', 'kingsgroup'),
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'jobs'),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
    );
    register_post_type('jobs', $args);

    // Register job_location_tax taxonomy
    register_taxonomy('job_location_tax', 'jobs', array(
        'labels' => array(
            'name'              => _x('Locations', 'taxonomy general name', 'kingsgroup'),
            'singular_name'     => _x('Location', 'taxonomy singular name', 'kingsgroup'),
            'search_items'      => __('Search Locations', 'kingsgroup'),
            'all_items'         => __('All Locations', 'kingsgroup'),
            'parent_item'       => __('Parent Location', 'kingsgroup'),
            'parent_item_colon' => __('Parent Location:', 'kingsgroup'),
            'edit_item'         => __('Edit Location', 'kingsgroup'),
            'update_item'       => __('Update Location', 'kingsgroup'),
            'add_new_item'      => __('Add New Location', 'kingsgroup'),
            'new_item_name'     => __('New Location Name', 'kingsgroup'),
            'menu_name'         => __('Locations', 'kingsgroup'),
        ),
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'job-location'),
        'show_in_rest'      => true,
        'meta_box_cb'       => false,
    ));
}
add_action('init', 'kingsgroup_register_jobs_cpt');

/**
 * Redirect default jobs archive to the custom "Our Jobs" portal page.
 */
function kg_redirect_jobs_archive()
{
    if (is_post_type_archive('jobs')) {
        // Try to find the page with the our-jobs.php template
        $portal_page = get_pages(array(
            'meta_key' => '_wp_page_template',
            'meta_value' => 'our-jobs.php'
        ));

        if (!empty($portal_page)) {
            wp_redirect(get_permalink($portal_page[0]->ID), 301);
            exit;
        } else {
            // Fallback to home if page not found
            wp_redirect(home_url('/our-jobs/'), 301);
            exit;
        }
    }
}
add_action('template_redirect', 'kg_redirect_jobs_archive');

/**
 * Flush rewrite rules once after theme activation or CPT registration changes.
 * Runs only when the flush flag isn't set yet, then sets it so it never runs twice.
 */
function kg_flush_rewrite_once()
{
    if (!get_option('kg_rewrite_flushed')) {
        flush_rewrite_rules();
        update_option('kg_rewrite_flushed', true);
    }
}
add_action('init', 'kg_flush_rewrite_once', 20);

/**
 * Configure PHPMailer directly with Gmail SMTP credentials from wp-config.php.
 * This bypasses WP Mail SMTP OAuth and works with a Gmail App Password.
 * Credentials are defined in wp-config.php (not committed to git).
 */
if (defined('KG_SMTP_HOST')) {
    add_action('phpmailer_init', function ($phpmailer) {
        $phpmailer->isSMTP();
        $phpmailer->Host = KG_SMTP_HOST;
        $phpmailer->Port = KG_SMTP_PORT;
        $phpmailer->SMTPAuth = true;
        $phpmailer->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $phpmailer->Username = KG_SMTP_USER;
        $phpmailer->Password = KG_SMTP_PASS;
        $phpmailer->setFrom(KG_SMTP_FROM, KG_SMTP_FROMNAME);
    }, 999);
}

/**
 * Returns an inline SVG icon string.
 * Uses Heroicons 24x24 outline.
 */
function kg_icon($name, $class = '')
{
    $class_attr = $class ? ' class="' . esc_attr($class) . '"' : '';
    $default_attrs = 'xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="24" height="24"' . $class_attr;

    switch ($name) {
        case 'search':
            return '<svg ' . $default_attrs . '><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>';
        case 'briefcase':
            return '<svg ' . $default_attrs . '><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.084-.816 1.964-1.828 2.192a41.465 41.465 0 0 1-9.222 0c-1.012-.228-1.828-1.108-1.828-2.192v-4.25m16.5 0a21.819 21.819 0 0 0-3.846-1.106c-.106-.02-.213-.04-.321-.06A21.83 21.83 0 0 0 12 11.5a21.82 21.82 0 0 0-3.333.284c-.108.02-.215.04-.321.06A21.819 21.819 0 0 0 3.75 14.15m16.5 0v-4.25c0-1.084-.816-1.964-1.828-2.192a41.465 41.465 0 0 0-9.222 0c-1.012.228-1.828 1.108-1.828 2.192v4.25m16.5 0a21.819 21.819 0 0 1-3.846 1.106c-.106.02-.213.04-.321.06A21.83 21.83 0 0 1 12 12.5a21.82 21.82 0 0 1-3.333-.284c-.108-.02-.215-.04-.321-.06A21.819 21.819 0 0 1 3.75 14.15m16.5 0v-4.25c0-1.084-.816-1.964-1.828-2.192a41.465 41.465 0 0 0-9.222 0c-1.012.228-1.828 1.108-1.828 2.192v4.25" /><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 7.5h-9v-2.25c0-1.084.816-1.964 1.828-2.192a41.465 41.465 0 0 1 5.344 0c1.012.228 1.828 1.108 1.828 2.192V7.5Z" /></svg>';
        case 'location':
            return '<svg ' . $default_attrs . '><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" /></svg>';
        case 'building':
            return '<svg ' . $default_attrs . '><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" /></svg>';
        case 'crown':
            return '<svg ' . $default_attrs . '><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" /></svg>';
        case 'clipboard':
            return '<svg ' . $default_attrs . '><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V8.25ZM6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" /></svg>';
        case 'refresh':
            return '<svg ' . $default_attrs . '><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" /></svg>';
        default:
            return '';
    }
}

/**
 * Dynamically filter navigation menu items to force the Log In link to the new Azure portal.
 */
function kg_custom_menu_link_filter($items, $menu, $args)
{
    foreach ($items as &$item) {
        if (strtolower($item->title) === 'log in' || strtolower($item->title) === 'login') {
            $item->url = 'https://zckings.azurewebsites.net/';
        }
    }
    return $items;
}
add_filter('wp_get_nav_menu_items', 'kg_custom_menu_link_filter', 99, 3);

function kg_custom_menu_link_attributes($atts, $item, $args)
{
    if (strtolower($item->title) === 'log in' || strtolower($item->title) === 'login') {
        $atts['target'] = '_blank';
        $atts['rel'] = 'noopener';
    }
    return $atts;
}
add_filter('nav_menu_link_attributes', 'kg_custom_menu_link_attributes', 99, 3);

/**
 * Automatically seeds the three requested testimonials if no testimonials exist in the CPT database.
 */
function kg_seed_testimonials()
{
    if (!post_type_exists('kg_testimonial'))
        return;

    // Check if we already seeded these exact four to avoid duplicate inserts
    $check = get_posts(array(
        'post_type' => 'kg_testimonial',
        'title' => 'Mariel Insur',
        'post_status' => 'any',
        'numberposts' => 1
    ));
    if (!empty($check)) {
        return; // Already seeded, do nothing
    }

    // Delete any existing default/dummy testimonials to make room for these four
    $existing = get_posts(array(
        'post_type' => 'kg_testimonial',
        'post_status' => 'any',
        'numberposts' => -1
    ));
    foreach ($existing as $ex_post) {
        wp_delete_post($ex_post->ID, true);
    }

    $default_testis = array(
        array(
            'name' => 'Julius Cobarubrias',
            'quote' => 'Dahil sa Savings Program ni KINGS, naka pundar po ako ng isang Negosyo na nakakatulong sa gastos para kay baby.',
            'role' => 'Member',
            'order' => 0
        ),
        array(
            'name' => 'Melanie Santos',
            'quote' => 'Maraming salamat ssa KINGS, kase nabigyan ako ng opurtunidad na makapag trabaho at makapag aral ang aking mga anak.',
            'role' => 'Member',
            'order' => 1
        ),
        array(
            'name' => 'Daisy Salaño',
            'quote' => 'Working with KINGS feels like my second home. They treated me as a family.',
            'role' => 'Member',
            'order' => 2
        ),
        array(
            'name' => 'Mariel Insur',
            'quote' => 'Nang dahil sa KINGS, nasusuportahan ko ang pangangailangan ng aking anak sa pag-aaral at ganun din ang pangangailangan pang medical ng akin inang may cancer.',
            'role' => 'Member',
            'order' => 3
        ),
    );
    foreach ($default_testis as $t) {
        $t_id = wp_insert_post(array(
            'post_title' => $t['name'],
            'post_status' => 'publish',
            'post_type' => 'kg_testimonial',
        ));
        update_post_meta($t_id, '_kg_testi_quote', $t['quote']);
        update_post_meta($t_id, '_kg_testi_role', $t['role']);
        update_post_meta($t_id, '_kg_testi_order', $t['order']);
    }
}
add_action('init', 'kg_seed_testimonials', 30);

/**
 * Generate a beautiful default description for seeded jobs based on title and department.
 */
function kg_get_default_job_content($title, $dept, $location)
{
    $dept_lower = strtolower($dept);
    $title_lower = strtolower($title);
    
    $overview = "We are seeking a dedicated and energetic <strong>" . esc_html($title) . "</strong> to join our team in <strong>" . esc_html($location) . "</strong>. In this role, you will represent Kings Group Cooperative with professionalism and commitment, delivering outstanding results for our partners while contributing to our cooperative community.";
    
    $responsibilities = array(
        "Perform daily operational duties associated with the role of " . esc_html($title) . " with high efficiency and attention to quality.",
        "Collaborate effectively with team members and supervisors to meet daily targets and maintain operational excellence.",
        "Ensure compliance with all safety regulations, organizational guidelines, and customer service standards.",
        "Maintain a clean, organized, and professional work environment at all times.",
        "Participate in regular team meetings, performance evaluations, and cooperative upskilling opportunities."
    );
    
    $requirements = array(
        "Proven experience or strong interest in a " . esc_html($dept) . " or related role.",
        "Excellent communication, interpersonal, and team collaboration skills.",
        "Strong work ethic, reliability, and capability to work effectively under pressure.",
        "High school diploma, vocational certification, or equivalent (relevant experience is highly valued).",
        "Willingness to undergo continuous training and professional development."
    );

    if (strpos($dept_lower, 'food') !== false || strpos($title_lower, 'crew') !== false || strpos($title_lower, 'waiter') !== false) {
        $responsibilities = array(
            "Provide warm, friendly, and efficient customer service to all guests and clients.",
            "Assist with food preparation, assembly, and presentation in compliance with food safety protocols.",
            "Maintain dining area, counters, and kitchen workstations in pristine condition.",
            "Process payments accurately using the POS system and handle cash transactions securely.",
            "Manage inventory levels and restock food service items and supplies as needed."
        );
        $requirements = array(
            "Experience in the food service, hospitality, or restaurant industry is preferred.",
            "Knowledge of food safety, sanitation, and hygiene standards.",
            "Strong customer-oriented mindset and friendly demeanor.",
            "Ability to stand for long periods and work in a fast-paced environment.",
            "Excellent teamwork and verbal communication skills."
        );
    } elseif (strpos($dept_lower, 'sales') !== false || strpos($title_lower, 'promodiser') !== false || strpos($title_lower, 'sales') !== false) {
        $responsibilities = array(
            "Actively promote and demonstrate product features and benefits to customers.",
            "Engage customers to understand their needs and guide them to appropriate product solutions.",
            "Achieve or exceed sales targets, tracking and reporting daily sales volumes.",
            "Set up and maintain eye-catching product displays and merchandising materials.",
            "Monitor stock levels, report inventory needs, and keep products organized on the sales floor."
        );
        $requirements = array(
            "Experience in sales, retail, promotion, or customer relations.",
            "Confident presentation skills and outgoing, persuasive personality.",
            "Strong communication and negotiation skills.",
            "Ability to track sales metrics and operate basic point-of-sale systems.",
            "Results-driven mindset with a commitment to customer satisfaction."
        );
    } elseif (strpos($dept_lower, 'warehouse') !== false || strpos($dept_lower, 'logistics') !== false || strpos($title_lower, 'warehouse') !== false || strpos($title_lower, 'driver') !== false || strpos($title_lower, 'rider') !== false || strpos($title_lower, 'packer') !== false) {
        $responsibilities = array(
            "Execute receiving, stocking, packing, and dispatch operations in the warehouse.",
            "Ensure safe, accurate, and timely handling and transport of inventory and shipments.",
            "Maintain accurate records of goods moved, shipped, or received.",
            "Perform regular equipment checks and basic maintenance to ensure operational safety.",
            "Optimize storage space and organize materials for maximum efficiency."
        );
        $requirements = array(
            "Experience in warehousing, logistics, delivery, or inventory control.",
            "Familiarity with standard warehouse safety guidelines and procedures.",
            "For drivers/riders: Valid professional driver's license and clean driving record.",
            "Strong physical stamina and ability to lift and move heavy goods.",
            "Excellent time management and spatial organization skills."
        );
    } elseif (strpos($dept_lower, 'merchandis') !== false || strpos($title_lower, 'merchandiser') !== false) {
        $responsibilities = array(
            "Design and implement visual merchandising displays to enhance product visibility.",
            "Ensure stock is regularly replenished and displays conform to brand guidelines.",
            "Monitor product shelf life, inventory levels, and price tags accuracy.",
            "Coordinate with store management to align promotional strategies and launches.",
            "Gather and report customer feedback and market trends to supervisors."
        );
        $requirements = array(
            "Prior experience in retail merchandising or visual display coordination.",
            "Keen eye for aesthetics, product styling, and organization.",
            "Strong coordination skills to work with store managers and sales staff.",
            "Reliable, independent, and proactive working style.",
            "Basic mathematical skills for inventory tracking and reporting."
        );
    } elseif (strpos($dept_lower, 'production') !== false || strpos($title_lower, 'production') !== false || strpos($title_lower, 'helper') !== false || strpos($title_lower, 'technician') !== false) {
        $responsibilities = array(
            "Assist in assembly line operations, machine operations, and raw materials preparation.",
            "Execute quality checks on raw materials and finished goods to identify defects.",
            "Maintain assembly machinery and tools in optimal working condition.",
            "Adhere strictly to standard operating procedures (SOPs) and safety protocols.",
            "Pack, label, and prepare finished items for shipment or storage."
        );
        $requirements = array(
            "Prior experience in manufacturing, assembly, production, or technical maintenance.",
            "Understanding of occupational safety standards and quality control principles.",
            "Ability to perform repetitive tasks with precision and high attention to detail.",
            "For technicians: Vocational course or certification in electronics, electrical, mechanical, or aircon repair.",
            "Strong physical coordination and technical troubleshooting aptitude."
        );
    }

    $html = "<h3>Job Overview</h3>";
    $html .= "<p>" . $overview . "</p>";
    
    $html .= "<h3>Key Responsibilities</h3>";
    $html .= "<ul>";
    foreach ($responsibilities as $resp) {
        $html .= "<li>" . esc_html($resp) . "</li>";
    }
    $html .= "</ul>";
    
    $html .= "<h3>Requirements & Qualifications</h3>";
    $html .= "<ul>";
    foreach ($requirements as $req) {
        $html .= "<li>" . esc_html($req) . "</li>";
    }
    $html .= "</ul>";
    
    $html .= "<h3>Working Conditions</h3>";
    $html .= "<ul>";
    $html .= "<li><strong>Location:</strong> " . esc_html($location) . "</li>";
    $html .= "<li><strong>Setup:</strong> On-site operational setting</li>";
    $html .= "<li><strong>Department:</strong> " . esc_html($dept) . "</li>";
    $html .= "<li><strong>Hours:</strong> Standard operational shifts</li>";
    $html .= "</ul>";

    return $html;
}

/**
 * Automatically seeds the 14 requested jobs if they are not already present in the jobs CPT database.
 */
function kg_seed_jobs()
{
    if (!post_type_exists('jobs'))
        return;

    // Use a versioned option key so we can force re-seeding when this logic changes
    if (get_option('kg_jobs_seeded_version_v3')) {
        return; // Already seeded, do nothing
    }

    // Delete any existing default/dummy jobs to make room for the new list
    $existing = get_posts(array(
        'post_type' => 'jobs',
        'post_status' => 'any',
        'numberposts' => -1
    ));
    foreach ($existing as $ex_post) {
        wp_delete_post($ex_post->ID, true);
    }

    $csv_file = get_template_directory() . '/June-2026-Execom-Report-Copy.csv';
    if (!file_exists($csv_file)) {
        return;
    }

    $handle = fopen($csv_file, 'r');
    if (!$handle) {
        return;
    }

    $row_idx = 0;
    $columns = array(
        1  => array('dept' => 'Food Service',           'pos_idx' => 1,  'loc_idx' => 2),
        4  => array('dept' => 'Sales',                  'pos_idx' => 4,  'loc_idx' => 5),
        7  => array('dept' => 'Warehouse & Logistics',  'pos_idx' => 7,  'loc_idx' => 8),
        10 => array('dept' => 'Merchandising',          'pos_idx' => 10, 'loc_idx' => 11),
        13 => array('dept' => 'Production',             'pos_idx' => 13, 'loc_idx' => 14),
    );

    // Track inserted combinations to avoid duplicates
    $inserted = array();

    while (($data = fgetcsv($handle, 1000, ',')) !== false) {
        $row_idx++;
        if ($row_idx <= 14) {
            continue; // Skip header rows
        }

        foreach ($columns as $col_id => $config) {
            $pos = isset($data[$config['pos_idx']]) ? trim($data[$config['pos_idx']]) : '';
            $loc = isset($data[$config['loc_idx']]) ? trim($data[$config['loc_idx']]) : '';

            if (!empty($pos) && $pos !== '#NAME?') {
                $uniq_key = md5(strtolower($pos . '|' . $loc));
                if (isset($inserted[$uniq_key])) {
                    continue; // Skip duplicates
                }
                $inserted[$uniq_key] = true;

                $job_content = kg_get_default_job_content($pos, $config['dept'], $loc);

                $job_id = wp_insert_post(array(
                    'post_title' => $pos,
                    'post_content' => $job_content,
                    'post_status' => 'publish',
                    'post_type' => 'jobs',
                    'post_excerpt' => $pos . ' position in ' . $loc,
                ));

                if (!is_wp_error($job_id)) {
                    update_post_meta($job_id, 'base_price', 1200);
                    update_post_meta($job_id, '_base_price', 'field_job_base_price');
                    update_post_meta($job_id, 'include_in_team_builder', 1);
                    update_post_meta($job_id, '_include_in_team_builder', 'field_job_include_team_builder');
                    update_post_meta($job_id, 'job_card_image', ''); // Omitted for now
                    update_post_meta($job_id, '_job_card_image', 'field_job_card_image');
                    update_post_meta($job_id, 'job_location', $loc);
                    update_post_meta($job_id, '_job_location', 'field_job_location');
                    update_post_meta($job_id, 'job_type', 'FULL_TIME');
                    update_post_meta($job_id, '_job_type', 'field_job_type');
                    update_post_meta($job_id, 'job_work_setup', 'WFO');
                    update_post_meta($job_id, '_job_work_setup', 'field_job_work_setup');
                    update_post_meta($job_id, 'job_department', $config['dept']);
                    update_post_meta($job_id, '_job_department', 'field_job_department');
                    update_post_meta($job_id, 'job_target_headcount', 10);
                    update_post_meta($job_id, '_job_target_headcount', 'field_job_target_headcount');
                    update_post_meta($job_id, 'job_filled_headcount', 0);
                    update_post_meta($job_id, '_job_filled_headcount', 'field_job_filled_headcount');
                }
            }
        }
    }
    fclose($handle);

    update_option('kg_jobs_seeded_version_v3', true);
}
add_action('init', 'kg_seed_jobs', 35);

/**
 * Automatically seeds the new post in the standard WordPress posts database.
 */
function kg_seed_news_posts()
{
    if (!function_exists('wp_insert_post')) {
        return;
    }

    // Post 1: Commitment, Culture, and Community in Action (October 2025)
    $check_1 = get_posts(array(
        'post_type'   => 'post',
        'title'       => 'Commitment, Culture, and Community in Action (October 2025)',
        'post_status' => 'any',
        'numberposts' => 1
    ));
    if (empty($check_1)) {
        $post_content_1 = '
<h2>Fueling Dreams for 26 Years: Celebrating Kings Lending</h2>
<p>Kings Lending proudly celebrates 26 remarkable years of service, empowering countless individuals and communities through accessible and dependable financial support. Now being led by Mr. Reymarc Navarro, the company continues to grow and inspire. Over the years, Kings Lending has helped thousands of Kings Members achieve their goals, build brighter futures, and celebrate financial milestones through trusted lending services.</p>

<h2>Kings Scout: Helping Hands for Cebu Quake Victims</h2>
<p>Kings, through its Member Supervisors assigned in Cebu and nearby areas, extended immediate support to communities affected by the 6.9 magnitude earthquake that struck on September 30. With compassion and unity, they distributed essential relief goods to families in need.</p>
<p>More than just material aid, their presence brought hope, comfort, and a reminder that in times of crisis, the spirit of bayanihan lives on through the helping hands of the Kings community. The smiles and gratitude of those they served were a heartfelt reminder of the power of coming together. Kings remains committed to standing by its members and the communities they call home.</p>

<h2>From Kings to Rotary International: Honoring Cory Navarro’s Inspiring Leadership</h2>
<p>We are proud to share that PP Cory Navarro was among the honorees of the Arch Klumph Society Certificate at the Arch Klumph Society Ceremony, held during the AKS Weekend at the Rotary International World Headquarters. This prestigious recognition is one of Rotary’s highest honors, celebrating exceptional generosity and commitment to The Rotary Foundation. A truly proud and inspiring moment for all of us!</p>

<h2>Kings & SCPA: Training the Trainer Program – Empowering Members, Elevating Standards</h2>
<p>Aimed to enhance Member Supervisors’ skills through a deeper understanding of Client’s processes and products, the Training the Trainer Program held last October 16–17, 2025, empowered participants to better guide and support their assigned members across different branches. In collaboration with SCPA’s South Luzon Sales Supervisor, Ms. Carmela Enriquez, as the resource speaker, the training focused on equipping Kings Member Supervisors with practical knowledge and upskilling them to lead, mentor, and manage their teams more effectively. Dedicated to learning contributes to a stronger, more capable workforce. Together, we grow and elevate our standards!</p>

<h2>Scare For A Cause</h2>
<p>Held last 24 October 2025, Kings Community turned Halloween into a celebration of purpose through its “Scare for a Cause” event. Each department showcased their creativity with hauntingly impressive presentations, and we extend our heartfelt thanks to all participants who joined the Scare-Off Contest. The People & Culture Department bagged 1st Place with their chilling portrayal of Sadako’s story, followed by the Operations Team, Servo Dynamics, and the Accounting Team, who brought monster-like and zombie characters to life, giving everyone a frightful thrill.</p>
<p>But beyond the screams and laughter, the true highlight was the spirit of generosity. The winning team’s prize was doubled and donated to families affected by the recent earthquakes across the Philippines, while all departments contributed food, clothing, and essentials through the ABS-CBN Foundation. We are truly grateful to be part of something bigger than ourselves — a community for the community, proving that together, we can turn fear into hope and compassion into action.</p>

<h2>Kings Music Awards: Retro Beats, Bright Lights, and Holiday Delights</h2>

<h2>Afterwork Yoga: Clock out & roll out (your mat) with us!</h2>
<p>Our 1st Afterwork Yoga took place last September 26 with Teacher Ven at Kings City’s Roofdeck. It was the perfect after-work unwind and recharge session we all needed! We loved the deep stretches, calm energy, and how totally beginner-friendly it was.</p>
<p>We also had a FREE Yoga Session for Desk Workers last October 11 at BetterLife Studio. It was the perfect reminder to pause, breathe, and recharge in the middle of a busy week!</p>

<h2>Coming Soon: Scout of the Month</h2>
<ul>
  <li><strong>PROACTIVE & DRIVEN</strong> - Takes initiative, looks for ways to contribute, and goes beyond what’s expected.</li>
  <li><strong>LEADS BY EXAMPLE</strong> - Inspires others through actions, reliability, and professionalism.</li>
  <li><strong>EMBODIES THE KINGS SPIRIT</strong> - Demonstrates dedication, teamwork, and commitment to excellence in all tasks.</li>
</ul>

<h2>Painting Workshop</h2>
';
        $post_id_1 = wp_insert_post(array(
            'post_title'   => 'Commitment, Culture, and Community in Action (October 2025)',
            'post_content' => $post_content_1,
            'post_status'  => 'publish',
            'post_date'    => '2025-10-30 09:00:00',
            'post_type'    => 'post',
            'post_excerpt' => 'Kings Lending celebrates 26 years, Cebu earthquake relief, PP Cory Navarro AKS recognition, SCPA upskilling training, and Halloween Scare for a Cause.',
        ));

        if ($post_id_1 && !is_wp_error($post_id_1)) {
            update_post_meta($post_id_1, '_kg_post_image', 'https://images.unsplash.com/photo-1531482615713-2afd69097998?auto=format&fit=crop&w=1200&q=80');
            $cat_id = get_cat_ID('Community');
            if (!$cat_id) {
                if (!function_exists('wp_create_category')) {
                    require_once(ABSPATH . 'wp-admin/includes/taxonomy.php');
                }
                $cat_id = wp_create_category('Community');
            }
            if ($cat_id) {
                wp_set_post_categories($post_id_1, array($cat_id));
            }
        }
    }

    // Post 2: Dental Mission, Mental Health, KOICA, and Cultural Celebrations (August–September 2025)
    $check_2 = get_posts(array(
        'post_type'   => 'post',
        'title'       => 'Dental Mission, Mental Health, KOICA, and Cultural Celebrations (August–September 2025)',
        'post_status' => 'any',
        'numberposts' => 1
    ));
    if (empty($check_2)) {
        $post_content_2 = '
<h2>Ngiting Kings x Mencius Smile Dental Wellness Mission</h2>
<p>Last September 7, in coordination with the Manila Health Department, we held our “Ngiting Kings x Mencius Smile Dental Wellness Mission.” The wellness mission served over 300 beneficiaries, providing essential dental check-ups, consultations, and treatments. As part of the program, dentures were provided to 25 recipients, restoring not only their smiles but also their confidence and quality of life.</p>
<p>In addition, 100 pneumonia vaccines were administered, further strengthening the health and protection of our community. This initiative reflects our shared commitment to promoting wellness, ensuring that our members continue to thrive with healthier smiles and stronger lives.</p>

<h2>Kalma sa Gitna ng Pangangamba</h2>
<p>As part of our Mental Health Awareness initiatives, we held a special talk by Father Bitoy. Through his insightful sharing, participants were reminded of the importance of faith, hope, and resilience in overcoming life’s challenges. Father Bitoy emphasized practical ways to find calm in moments of fear and uncertainty, encouraging everyone to embrace both prayer and mindfulness as anchors of strength. It was an enlightening and heartwarming session that left participants with renewed peace and courage.</p>

<h2>안녕하세요, KOICA VOLUNTEERS!</h2>
<p>The KOICA Volunteers had a flavorful experience as they immersed themselves in Filipino culture through food! Guided by Home Culinary, they learned how to cook two local favorites — the warm and comforting sinigang and the sweet, crunchy turon. More than just a cooking lesson, it was a cultural exchange filled with laughter, stories, and hands-on learning. The session highlighted how food brings people together, creating connections that go beyond language and borders.</p>

<h2>Bulan Ti Wikang Pambansa</h2>
<p>Sa paggunita ng Buwan ng Wika, sama-samang ipinagdiwang ng ating komunidad ang yaman ng ating kultura at pagkakakilanlan bilang Pilipino. Tampok sa selebrasyon ang koronasyon ng Lakan at Lakambini, pagpapakita ng galing at talento sa pamamagitan ng Sining sa Bilao. Isa itong makulay na patunay na ang ating wika ay hindi lamang daluyan ng komunikasyon, kundi isang buhay na simbolo ng ating kasaysayan, pagkakaisa, at pagmamalaki sa pagiging Pilipino.</p>
<p>Sa patimpalak na Lakan at Lakambini ng Wika, ipinakita ng mga kalahok ang kanilang ganda at tikas. Sa pamamagitan ng kanilang kasuotan at pagganap, itinatampok nila ang kahalagahan ng kulturang Pilipino at ang ganda ng ating pagkakakilanlan bilang isang bayan.</p>
<p>Sa Sining sa Bilao, ipinamalas naman ng mga kalahok ang kanilang malikhaing galing at husay sa sining. Mula sa makukulay na disenyo hanggang sa masining na presentasyon, bawat obra ay sumasalamin sa yaman ng imahinasyon at malikhaing diwa ng Pilipino.</p>

<h2>Creative/Junk Journaling Workshop</h2>
<p>Our recent Creative Junk Journaling Workshop was such a fun and inspiring session! ✨</p>
<p>Participants enjoyed exploring their creativity through junk journaling while also taking home lots of exciting freebies.</p>
<p>The event featured a charming flower bar, where everyone got to pick and style their own blooms, plus a hands-on activity of customizing tote bags to make the experience even more personal.</p>
<p>It was a day filled with art, self-expression, and community — truly a reminder that creativity blossoms when shared together.</p>

<h2>Hand-Building Pottery Workshop!</h2>
<p>Our Hand-Building Pottery Workshop was a hands-on experience where creativity met craftsmanship. Participants learned the basics of shaping and molding clay, discovering the joy of turning simple materials into unique, handmade pieces.</p>
<p>The session was filled with laughter, focus, and a sense of calm as everyone explored the art of pottery at their own pace. Each creation carried a personal touch — proof that art is not just about skill, but about expression and the beauty of imperfection.</p>
<p>Truly, it was a workshop that left hearts (and hands!) full.</p>
';
        $post_id_2 = wp_insert_post(array(
            'post_title'   => 'Dental Mission, Mental Health, KOICA, and Cultural Celebrations (August–September 2025)',
            'post_content' => $post_content_2,
            'post_status'  => 'publish',
            'post_date'    => '2025-09-30 09:00:00',
            'post_type'    => 'post',
            'post_excerpt' => 'Ngiting Kings x Mencius Smile Dental Wellness Mission, Kalma sa Gitna ng Pangangamba, KOICA Volunteers cooking, Buwan ng Wikang Pambansa, Creative Junk Journaling, and Pottery workshops.',
        ));

        if ($post_id_2 && !is_wp_error($post_id_2)) {
            update_post_meta($post_id_2, '_kg_post_image', 'https://images.unsplash.com/photo-1588776814546-1ffcf47267a5?auto=format&fit=crop&w=1200&q=80');
            $cat_id = get_cat_ID('Community');
            if ($cat_id) {
                wp_set_post_categories($post_id_2, array($cat_id));
            }
        }
    }

    // Post 3: Leadership, Community, and Creative Growth (June–July 2025)
    $check_3 = get_posts(array(
        'post_type'   => 'post',
        'title'       => 'Leadership, Community, and Creative Growth (June–July 2025)',
        'post_status' => 'any',
        'numberposts' => 1
    ));
    if (empty($check_3)) {
        $post_content_3 = '
<h2>Happy Birthday, NSM!</h2>
<p>Sir Neil, our Kings Managing Director, celebrated his birthday surrounded by the very people he leads — sharing stories, laughter, and a hearty meal at Samgyupsalamat. More than just a celebration, it was a reflection of the kind of leader he is: grounded, generous, and deeply connected to the Kings Family.</p>
<p>Through his quiet strength, clear vision, and unwavering commitment to our people, Sir Neil continues to lead by example. Thank you for being the steady force behind our growth — a leader we trust, and a presence we’re proud to stand beside.</p>

<h2>Welcome, Kings & Élan Media Group</h2>
<p>You might remember them from the company pictorial, but their creative impact goes far beyond the frame. From shaping the spirit of Kings Camp 2025 to crafting works for Louis Vuitton, ALO, and La Prairie, Kings & Élan is redefining what creative excellence looks like within the Kings Group and beyond.</p>
<p>Guiding this creative house are co-founders Nian Ellao, Creative Chief Director, whose visionary lens defines the agency’s artistic direction, and Camille Makasiar, Managing Director, whose strategic clarity and executional precision bring each idea to life.</p>
<p>We’re proud to officially welcome Kings & Élan into the growing creative family of Kings. This is where brands belong. The future is bold, beautiful, and just beginning.</p>

<h2>The Kings Camp</h2>
<p>Last July 4–5, we came together not just as individuals from different companies, but as one Kings family, gathering in Zambales for a weekend of connection, courage, and collective growth!</p>
<p>Throughout the weekend, members of Kings Group of Companies bonded through the activities and conversations that broke barriers and built bridges. Whether it was in a heated team competition or a quiet moment of reflection, the spirit of collaboration was alive in every interaction. Each activity revealed not just our strengths as individuals, but our collective resilience and synergy as one Kings community.</p>
<p>The energy was high, the laughter was real, and the memories were meaningful. We saw leadership in motion, not just from those with titles, but from every person who showed up with courage, humility, and drive. Sir Neil’s message reminded us all of the deeper “why” behind our work: to lead with purpose, to lift others as we rise, and to stay rooted in service.</p>
<p>Special thanks to Be Club for hosting with heart and to Kings & Élan for capturing the weekend’s best moments. As we return to our roles and routines, may we carry the momentum of Kings Camp with us — the kind that reminds us that we are not alone in this journey. Together, we move stronger, bolder, and more united than ever. This is what it means to be Kings Scout.</p>
';
        $post_id_3 = wp_insert_post(array(
            'post_title'   => 'Leadership, Community, and Creative Growth (June–July 2025)',
            'post_content' => $post_content_3,
            'post_status'  => 'publish',
            'post_date'    => '2025-07-11 09:00:00',
            'post_type'    => 'post',
            'post_excerpt' => 'Sir Neil’s birthday celebration, welcoming Kings & Élan Media Group, and the team building highlights from Kings Camp 2025 in Zambales.',
        ));

        if ($post_id_3 && !is_wp_error($post_id_3)) {
            update_post_meta($post_id_3, '_kg_post_image', 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=1200&q=80');
            $cat_id = get_cat_ID('Community');
            if ($cat_id) {
                wp_set_post_categories($post_id_3, array($cat_id));
            }
        }
    }

    // Post 4: Wellness Wins, Workshops, and Mother’s Day (May 2025)
    $check_4 = get_posts(array(
        'post_type'   => 'post',
        'title'       => 'Wellness Wins, Workshops, and Mother’s Day (May 2025)',
        'post_status' => 'any',
        'numberposts' => 1
    ));
    if (empty($check_4)) {
        $post_content_4 = '
<h2>The Biggest Loser: Kings Edition – Momentum Check: Who’s Crushing It?</h2>
<p>It’s been a month since our last update, but don’t worry, we’re back and ready to bring the energy!</p>
<p>Our Biggest Loser Campaign has been going strong, and we’re excited to share that we officially have someone leading the pack! 🏆</p>
<p>While we took a little pause on the updates, the commitment, effort, and sweat didn’t stop — and it’s paying off. We\'re seeing incredible progress from many of you, and it’s inspiring to witness the drive and discipline across the board.</p>

<h2>Officially in their ganstilyo era!</h2>
<p>A huge thank you to everyone who made the crochet workshop memorable! We learned so much from Mima! — her guidance and patience made all the difference.</p>
<p>This process-based workshop taught us not just stitches, but creativity, focus, and the joy of making something by hand.</p>
<p>To all the participants: thank you for your enthusiasm and energy. We hope you keep those hooks moving and continue your crochet journey!</p>

<h2>Happy Mother’s Day!</h2>
<p>This Mother’s Day, we wanted to pause and truly honor the incredible moms of Kings City. We see you — the sacrifices you make, the quiet moments of strength, the endless love you pour into your families.</p>
<p>To say thank you, we’ve created a Mother’s Day Care Kit just for you. Inside, you’ll find a pamper voucher to remind you to take a moment for yourself, a special gift chosen with so much love, and a fresh rose to brighten your day. Because you deserve to be celebrated, cherished, and gently reminded how deeply you are appreciated — today and every day.</p>
';
        $post_id_4 = wp_insert_post(array(
            'post_title'   => 'Wellness Wins, Workshops, and Mother’s Day (May 2025)',
            'post_content' => $post_content_4,
            'post_status'  => 'publish',
            'post_date'    => '2025-05-11 09:00:00',
            'post_type'    => 'post',
            'post_excerpt' => 'The Biggest Loser Kings Edition check, crochet workshop highlights, and Mother\'s Day Care Kit celebration at Kings City.',
        ));

        if ($post_id_4 && !is_wp_error($post_id_4)) {
            update_post_meta($post_id_4, '_kg_post_image', 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?auto=format&fit=crop&w=1200&q=80');
            $cat_id = get_cat_ID('Community');
            if ($cat_id) {
                wp_set_post_categories($post_id_4, array($cat_id));
            }
        }
    }

    // Post 5: Celebrating Effort, Energy, and Empowerment (April 2025)
    $check_5 = get_posts(array(
        'post_type'   => 'post',
        'title'       => 'Celebrating Effort, Energy, and Empowerment (April 2025)',
        'post_status' => 'any',
        'numberposts' => 1
    ));
    if (empty($check_5)) {
        $post_content_5 = '
<h2>Who\'s Holding Strong?</h2>
<p>We’re loving all the energy our challengers have been bringing in! From healthy meals to workout wins, it’s been amazing to see their entries, their food choices, and all the activities they’ve been trying out!</p>
<p>To our challengers, don’t let the mid-challenge slump get to you! Whether you\'re seeing fast progress or still finding your rhythm, what matters most is that you keep showing up. Progress isn’t always linear, but the commitment you’ve shown is what truly counts. Now, let’s celebrate the ones who\'ve made it to the top this week! Originally meant to be a Top 5, we\'ve got a tie—so we\'re shining the spotlight on our TOP 6!</p>

<h2>BASIC MAKEUP WORKSHOP</h2>
<p>We hosted an insightful Basic Makeup Workshop, and it was a success! Participants had an amazing time learning the fundamentals of makeup application, from perfecting their base to creating stunning eye looks.</p>
<p>Our expert instructors walked them through step-by-step techniques, offering tips and tricks to enhance their natural beauty. By the end of the session, everyone left with newfound skills and confidence to take their makeup game to the next level!</p>
';
        $post_id_5 = wp_insert_post(array(
            'post_title'   => 'Celebrating Effort, Energy, and Empowerment (April 2025)',
            'post_content' => $post_content_5,
            'post_status'  => 'publish',
            'post_date'    => '2025-04-03 09:00:00',
            'post_type'    => 'post',
            'post_excerpt' => 'Weekly check-in on the Biggest Loser challengers and highlights from our Basic Makeup Workshop.',
        ));

        if ($post_id_5 && !is_wp_error($post_id_5)) {
            update_post_meta($post_id_5, '_kg_post_image', 'https://images.unsplash.com/photo-1489659639091-8b687bc4386e?auto=format&fit=crop&w=1200&q=80');
            $cat_id = get_cat_ID('Community');
            if ($cat_id) {
                wp_set_post_categories($post_id_5, array($cat_id));
            }
        }
    }

    // Post 6: Honoring Women and Welcoming New Leaders (March 2025)
    $check_6 = get_posts(array(
        'post_type'   => 'post',
        'title'       => 'Honoring Women and Welcoming New Leaders (March 2025)',
        'post_status' => 'any',
        'numberposts' => 1
    ));
    if (empty($check_6)) {
        $post_content_6 = '
<h2>Let’s celebrate Women’s Month!</h2>
<p>This month, we honor the strength, resilience, and impact of women in our community. At Kings City, we believe in creating spaces where women can connect, lead, and thrive. Whether in business, creativity, or everyday life, we celebrate the contributions of women who shape and inspire the world around us.</p>
<p>Here’s to the women who push boundaries, uplift others, and continue to make a difference. Your presence and achievements make Kings City stronger.</p>

<h2>BENTO CAKE WORKSHOP</h2>
<p>Our Bento Cake Workshop was a fun and creative session, bringing together a great mix of participants. Sarah, Beverly, Jaypee, and Patrick joined us in designing their own personalized mini cakes, each one reflecting their unique style. From learning piping techniques to experimenting with colors and designs, it was a hands-on experience filled with creativity and good company. Stay tuned for upcoming workshops at Kings City.</p>

<h2>The Biggest Loser: Kings Edition – The Challenge is On! Who’s Ready to Lose Big?</h2>
<p>The Biggest Loser Challenge has officially begun! It’s time to push limits, embrace healthier habits, and stay committed to your goals.</p>
<p>The scoreboard for Week 1 (March 17-22) is now out! See who’s leading the way! Whether you\'re at the top or just getting started, every step counts. Keep pushing, stay consistent, and let’s make this journey one to remember!</p>

<h2>Welcome to the Team, Cherie!</h2>
<p>We’re excited to introduce Cherie as our new Shared Services General Manager!</p>
<p>With her leadership and expertise, we look forward to enhancing our operations, streamlining processes, and driving greater efficiency across our teams.</p>
<p>Join us in giving her a warm welcome as we embark on this exciting new chapter together!</p>

<h2>HERSTORY TRIVIA QUIZ</h2>
<p>On March 10, we celebrated Women’s Month alongside our monthly mass, honoring the strength, resilience, and achievements of women in our community. The event was filled with camaraderie, reflection, and fun, capped off with an exciting game.</p>
<p>Congratulations to Team Green for their well-earned victory! Let’s continue to support and uplift one another beyond this celebration.</p>

<h2>New Community Manager!</h2>
<p>We’re thrilled to have you on board as our new CM, Roisa Panteras!</p>
<p>Your passion for connecting with people and your dedication to the team have earned you this well-deserved recognition. We’re excited to see all the great things you’ll accomplish!</p>
';
        $post_id_6 = wp_insert_post(array(
            'post_title'   => 'Honoring Women and Welcoming New Leaders (March 2025)',
            'post_content' => $post_content_6,
            'post_status'  => 'publish',
            'post_date'    => '2025-03-24 09:00:00',
            'post_type'    => 'post',
            'post_excerpt' => 'Women’s Month celebration, Bento Cake Workshop, Biggest Loser kickoff, Herstory Trivia, and welcoming our new Shared Services GM.',
        ));

        if ($post_id_6 && !is_wp_error($post_id_6)) {
            update_post_meta($post_id_6, '_kg_post_image', 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=1200&q=80');
            $cat_id = get_cat_ID('Community');
            if ($cat_id) {
                wp_set_post_categories($post_id_6, array($cat_id));
            }
        }
    }

    // Post 7: Unity Through Games and Celebration (February 2025)
    $check_7 = get_posts(array(
        'post_type'   => 'post',
        'title'       => 'Unity Through Games and Celebration (February 2025)',
        'post_status' => 'any',
        'numberposts' => 1
    ));
    if (empty($check_7)) {
        $post_content_7 = '
<h2>Kings Game: A 3-in-1 Anniversary Celebration!</h2>
<p>KINGS GAME was an unforgettable anniversary event celebrating the milestones of Kings Manpower, Kings City, and Taza Coffee Manila. The day was filled with energy, friendly competition, and meaningful connections, bringing our community closer together!</p>
<p>The competition was fierce, with each match keeping us on the edge of our seats. Players brought their A-game, showcasing impressive strategies and skills that made every round a thrilling experience. But what stood out the most was the sense of teamwork that flowed throughout the event. It was amazing to see participants support one another, and that’s what truly made Kings Game special. Here, it’s not just about winning; it’s about growing together, learning from each other, and celebrating the success of everyone involved.</p>
<p>Congratulations to our winners! Your hard work and dedication were evident, and we couldn’t be more proud. But the real victory was in the spirit of the game itself—everyone who joined played an essential role in making this event one to remember. You all brought something unique to the table, and that’s what made the experience so memorable.</p>
<p>A huge thank you to everyone who participated and made this celebration special. Your enthusiasm and spirit continue to define what makes Kings a true community. Stay tuned for more events, and let’s keep the momentum going!</p>

<h2>KINGS Valentine’s Special!</h2>
<p>Our Sugar Cookie Decorating Challenge was a total hit this Valentine’s Day! The creativity was flowing, and the competition was sweeter than ever. A big congratulations to Team 1 for taking the win with their stunning designs—your cookies truly stole the show!</p>
<p>But the real success was in the effort from all three teams. Team 2 brought vibrant, playful designs that had us all smiling, while Team 3 wowed us with their elegant, intricate details. Every team showcased their unique flair, and we couldn’t be more proud of all the hard work.</p>
<p>Thank you to everyone who participated, you made this event a memorable one. Stay tuned for more fun challenges, and until then, keep the Valentine’s spirit alive with your creative creations!</p>
';
        $post_id_7 = wp_insert_post(array(
            'post_title'   => 'Unity Through Games and Celebration (February 2025)',
            'post_content' => $post_content_7,
            'post_status'  => 'publish',
            'post_date'    => '2025-02-28 09:00:00',
            'post_type'    => 'post',
            'post_excerpt' => 'Kings Game 3-in-1 Anniversary event and Sugar Cookie Decorating Challenge for Valentine\'s Day.',
        ));

        if ($post_id_7 && !is_wp_error($post_id_7)) {
            update_post_meta($post_id_7, '_kg_post_image', 'https://images.unsplash.com/photo-1511632765486-a01980e01a18?auto=format&fit=crop&w=1200&q=80');
            $cat_id = get_cat_ID('Community');
            if ($cat_id) {
                wp_set_post_categories($post_id_7, array($cat_id));
            }
        }
    }
}
add_action('init', 'kg_seed_news_posts', 40);

/**
 * Geo-Routing and Consent Gate Helper Functions
 */

function kg_has_accepted_consent() {
    return isset($_COOKIE['kg_consent_accepted']) && $_COOKIE['kg_consent_accepted'] === 'true';
}

function kg_get_user_geo() {
    // 1. Query parameter override (testing & reset)
    if (isset($_GET['geo'])) {
        $geo = strtoupper(preg_replace('/[^a-zA-Z]/', '', $_GET['geo']));
        if (in_array($geo, ['PH', 'INTL'])) {
            $secure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
            setcookie('kg_user_geo', $geo, [
                'expires' => time() + (30 * 24 * 60 * 60),
                'path' => '/',
                'secure' => $secure,
                'httponly' => false,
                'samesite' => 'Lax'
            ]);
            $_COOKIE['kg_user_geo'] = $geo;
            return $geo;
        }
    }

    // 2. Cookie cached value
    if (isset($_COOKIE['kg_user_geo'])) {
        return $_COOKIE['kg_user_geo'];
    }

    // 3. Cloudflare Header Detection
    if (isset($_SERVER['HTTP_CF_IPCOUNTRY'])) {
        $country = strtoupper($_SERVER['HTTP_CF_IPCOUNTRY']);
        $geo = ($country === 'PH') ? 'PH' : 'INTL';
        $secure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
        setcookie('kg_user_geo', $geo, [
            'expires' => time() + (30 * 24 * 60 * 60),
            'path' => '/',
            'secure' => $secure,
            'httponly' => false,
            'samesite' => 'Lax'
        ]);
        $_COOKIE['kg_user_geo'] = $geo;
        return $geo;
    }

    // 4. IP-based fallback detection
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    // Check if localhost/local network
    if (empty($ip) || $ip === '127.0.0.1' || $ip === '::1' || strpos($ip, '192.168.') === 0 || strpos($ip, '10.') === 0) {
        $geo = 'PH'; // Default to PH on localhost
        $secure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
        setcookie('kg_user_geo', $geo, [
            'expires' => time() + (30 * 24 * 60 * 60),
            'path' => '/',
            'secure' => $secure,
            'httponly' => false,
            'samesite' => 'Lax'
        ]);
        $_COOKIE['kg_user_geo'] = $geo;
        return $geo;
    }

    // Call external API with brief timeout
    $geo = 'PH'; // Fallback default
    $context = stream_context_create([
        'http' => [
            'timeout' => 2, // 2 seconds timeout max
            'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64)\r\n"
        ]
    ]);
    
    // We try to query ipapi.co to get the country code
    $api_url = "https://ipapi.co/{$ip}/country_code/";
    $response = @file_get_contents($api_url, false, $context);
    if ($response !== false) {
        $country = trim(strtoupper($response));
        if (strlen($country) === 2) {
            $geo = ($country === 'PH') ? 'PH' : 'INTL';
        }
    } else {
        // Try fallback ip-api.com
        $fallback_url = "http://ip-api.com/json/{$ip}?fields=status,countryCode";
        $fallback_resp = @file_get_contents($fallback_url, false, $context);
        if ($fallback_resp !== false) {
            $data = json_decode($fallback_resp, true);
            if (isset($data['status']) && $data['status'] === 'success' && isset($data['countryCode'])) {
                $geo = ($data['countryCode'] === 'PH') ? 'PH' : 'INTL';
            }
        }
    }

    $secure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    setcookie('kg_user_geo', $geo, [
        'expires' => time() + (30 * 24 * 60 * 60),
        'path' => '/',
        'secure' => $secure,
        'httponly' => false,
        'samesite' => 'Lax'
    ]);
    $_COOKIE['kg_user_geo'] = $geo;
    return $geo;
}

function kg_handle_geo_routing_redirect() {
    // Handle ?reset=1 query parameter to reset consent/cookie
    if (isset($_GET['reset'])) {
        $secure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
        setcookie('kg_consent_accepted', '', [
            'expires' => time() - 3600,
            'path' => '/',
            'secure' => $secure,
            'httponly' => false,
            'samesite' => 'Lax'
        ]);
        setcookie('kg_user_geo', '', [
            'expires' => time() - 3600,
            'path' => '/',
            'secure' => $secure,
            'httponly' => false,
            'samesite' => 'Lax'
        ]);
        unset($_COOKIE['kg_consent_accepted']);
        unset($_COOKIE['kg_user_geo']);
        
        // Redirect to clean URL without ?reset=1 parameter
        $clean_url = strtok($_SERVER['REQUEST_URI'], '?');
        if (function_exists('wp_redirect')) {
            wp_redirect($clean_url);
        } else {
            header('Location: ' . $clean_url);
            exit;
        }
    }

    // Only redirect if consent is accepted. Otherwise, the legal gate modal blocks access.
    if (!kg_has_accepted_consent()) {
        return;
    }    // Client-side JavaScript redirects (in head.php) now handle page routing 
    // to prevent server-side caching issues on GoDaddy.
}

// Hook redirects into WordPress template redirect, or run directly if standalone
if (function_exists('add_action')) {
    add_action('template_redirect', 'kg_handle_geo_routing_redirect');
} else {
    kg_handle_geo_routing_redirect();
}

// One-time database update for Zamboanga HQ & Manila Office address fields
if (function_exists('add_action')) {
    add_action('init', function() {
        if (!get_option('kg_updated_contact_addresses_v2')) {
            if (function_exists('kg_get_page_by_template')) {
                $contact_page_id = kg_get_page_by_template('contact.php');
                if ($contact_page_id) {
                    update_post_meta($contact_page_id, 'contact_address', 'DVN Building, Melaño Calixto St, Zamboanga City, Zamboanga del Sur');
                    update_post_meta($contact_page_id, '_contact_address', 'field_contact_address');
                    
                    update_post_meta($contact_page_id, 'contact_address_2', '100 Doña Soledad Avenue, Better Living, Paranaque City, Metro Manila, Philippines, 1711');
                    update_post_meta($contact_page_id, '_contact_address_2', 'field_contact_address_2');
                    
                    update_option('kg_updated_contact_addresses_v2', true);
                }
            }
        }
    });
}

/**
 * Register recruiter role and capabilities
 */
if (function_exists('add_action')) {
    add_action('init', 'kg_register_recruiter_role');
}

function kg_register_recruiter_role() {
    // add_role() is a no-op if the role already exists, so we must also
    // explicitly add each cap to handle sites where the role was previously
    // registered with an incomplete set of capabilities.
    $caps = array(
        'read'                   => true,
        'edit_posts'             => true,
        'edit_others_posts'      => true,  // needed to edit kg_application posts (any author)
        'publish_posts'          => true,
        'edit_published_posts'   => true,
        'delete_posts'           => true,
        'delete_published_posts' => true,
        'upload_files'           => true,
    );

    // Register for fresh installs
    if (function_exists('add_role')) {
        add_role('recruiter', 'Recruiter', $caps);
    }

    // Sync caps for existing installs (get_role returns null if role absent, safe to skip)
    if (function_exists('get_role')) {
        $role = get_role('recruiter');
        if ($role) {
            foreach ($caps as $cap => $grant) {
                $role->add_cap($cap, $grant);
            }
        }
    }
}

function kg_is_current_user_recruiter() {
    if (!function_exists('wp_get_current_user')) {
        return false;
    }
    $user = wp_get_current_user();
    if (!$user || !isset($user->roles)) {
        return false;
    }
    return in_array('recruiter', (array) $user->roles);
}

/**
 * Programmatically create a sample recruiter account on init if it doesn't exist.
 */
if (function_exists('add_action')) {
    add_action('init', 'kg_create_sample_recruiter_account');
}

function kg_create_sample_recruiter_account() {
    if (function_exists('username_exists') && function_exists('wp_create_user')) {
        if (!username_exists('samplerecruiter') && !email_exists('recruiter@kingsgroup.com')) {
            $user_id = wp_create_user('samplerecruiter', 'recruiter123', 'recruiter@kingsgroup.com');
            if (!is_wp_error($user_id)) {
                $user = new WP_User($user_id);
                $user->set_role('recruiter');
            }
        }
    }
}

/**
 * Define the dynamic locations/branches based on the job_location_tax taxonomy
 */
function kg_get_locations() {
    $locations = array();
    $terms = get_terms( array(
        'taxonomy'   => 'job_location_tax',
        'hide_empty' => false,
    ) );
    if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
        foreach ( $terms as $term ) {
            $locations[ $term->slug ] = $term->name;
        }
    }
    return $locations;
}

/**
 * Migrate existing job_location meta values to the new job_location_tax taxonomy
 */
function kg_migrate_existing_job_locations() {
    if ( get_option( 'kg_job_locations_migrated_v3' ) ) {
        return;
    }
    
    $jobs = get_posts( array(
        'post_type'   => 'jobs',
        'post_status' => 'any',
        'numberposts' => -1,
    ) );
    
    if ( ! empty( $jobs ) ) {
        foreach ( $jobs as $job ) {
            $loc = get_post_meta( $job->ID, 'job_location', true );
            if ( $loc ) {
                $clean = trim( $loc );
                if ( $clean !== '' ) {
                    $term = term_exists( $clean, 'job_location_tax' );
                    if ( ! $term ) {
                        $term = wp_insert_term( $clean, 'job_location_tax' );
                    }
                    if ( ! is_wp_error( $term ) && isset( $term['term_id'] ) ) {
                        wp_set_post_terms( $job->ID, array( (int) $term['term_id'] ), 'job_location_tax' );
                    }
                }
            }
        }
    }
    
    update_option( 'kg_job_locations_migrated_v3', true );
}
if ( function_exists( 'add_action' ) ) {
    add_action( 'init', 'kg_migrate_existing_job_locations', 20 );
}

// ============================================================
// AUTO-DERIVE REGION FROM LOCATION TAXONOMY TERM
// ============================================================

/**
 * Map a location term name to the correct Philippine region
 * using the same keyword logic as the ATS dashboard breakdown.
 */
function kg_derive_region_from_location_name( $name ) {
    $loc = mb_strtoupper( trim( $name ), 'UTF-8' );

    if ( preg_match( '/REMOTE|WFH|WORK FROM HOME/u', $loc ) )                                                   return 'Remote / WFH';
    if ( preg_match( '/NATIONWIDE|PHILIPPINES|ALL REGIONS/u', $loc ) )                                          return 'Nationwide';
    if ( preg_match( '/METRO MANILA|MANILA|TAGUIG|MAKATI|PASAY|QC|QUEZON CITY|GALLERIA|ALABANG|MOA|PARAÑAQUE|PARANAQUE|CALOOCAN|LAS PIÑAS|LAS PINAS|MANDALUYONG|MARIKINA|MUNTINLUPA|NAVOTAS|PASIG|SAN JUAN|VALENZUELA|PATEROS|EASTWOOD/u', $loc ) ) return 'NCR';
    if ( preg_match( '/PANGASINAN|DAGUPAN|LAOAG|ILOCOS|LA UNION|VIGAN|ILOCOS NORTE|ILOCOS SUR/u', $loc ) )     return 'Ilocos Region (I)';
    if ( preg_match( '/TUGUEGARAO|SOLANO|NUEVA VIZCAYA|CAUAYAN|BATANES|CAGAYAN|QUIRINO|SANTIAGO CITY|CITISTORE SOLANO|SACI CABANATUAN/u', $loc ) ) return 'Cagayan Valley (II)';
    if ( preg_match( '/BULACAN|MALOLOS|PAMPANGA|TARLAC|BAMBAN|CABANATUAN|OLONGAPO|MARILAO|MABALACAT|SUBIC|AURORA|BATAAN|NUEVA ECIJA|ZAMBALES|ANGELES|SACI OLONGAPO|SSM MARILAO/u', $loc ) ) return 'Central Luzon (III)';
    if ( preg_match( '/IMUS|LIMA|BATANGAS|LAGUNA|BACOOR|TANZA|VERMOSA|RIZAL|CAVITE|ANTIPOLO|LIPA|BALIWAG|TANAUAN|SACI BACOOR|SACI TANZA|ABENSON BATANGAS|ABENSON SAN PASCUAL|QUEZON PROVINCE|LUCENA/u', $loc ) ) return 'CALABARZON (IV-A)';
    if ( preg_match( '/MOGPOG|MARINDUQUE|MIMAROPA|MINDORO|PALAWAN|ROMBLON|PUERTO PRINCESA|CALAPAN/u', $loc ) )  return 'MIMAROPA (IV-B)';
    if ( preg_match( '/BICOL|CAMARINES|IRIGA|TABACO|DARAGA|ALBAY|NAGA|LEGAZPI|CATANDUANES|MASBATE|SORSOGON|LCC TABACO|LCC DARAGA|LCC NAGA|SACI LEGAZPI/u', $loc ) ) return 'Bicol (V)';
    if ( preg_match( '/BACOLOD|KABANKALAN|ILOILO|AKLAN|ANTIQUE|CAPIZ|GUIMARAS|NEGROS OCCIDENTAL|SACI BACOLOD|RA KABANKALAN|SACI ILOILO/u', $loc ) ) return 'Western Visayas (VI)';
    if ( preg_match( '/CEBU|BOHOL|NEGROS ORIENTAL|SIQUIJOR|MANDAUE|LAPU-LAPU|DUMAGUETE|TAGBILARAN/u', $loc ) )  return 'Central Visayas (VII)';
    if ( preg_match( '/BILIRAN|LEYTE|SAMAR|TACLOBAN|ORMOC/u', $loc ) )                                          return 'Eastern Visayas (VIII)';
    if ( preg_match( '/ZAMBOANGA|PAGADIAN|DIPOLOG|DAPITAN|SIBUGAY|ZAMBOANGA DEL SUR|ZAMBOANGA DEL NORTE|ZAMBOANGA SIBUGAY/u', $loc ) ) return 'Zamboanga Peninsula (IX)';
    if ( preg_match( '/BUKIDNON|CAMIGUIN|LANAO DEL NORTE|MISAMIS|CAGAYAN DE ORO|CDO|ILIGAN/u', $loc ) )        return 'Northern Mindanao (X)';
    if ( preg_match( '/TAGUM|DAVAO/u', $loc ) )                                                                 return 'Davao Region (XI)';
    if ( preg_match( '/MIDSAYAP|COTABATO|SARANGANI|GENERAL SANTOS|GENSAN|KORONADAL|SULTAN KUDARAT|CITISTORE MIDSAYAP|ABENSON COTABATO/u', $loc ) ) return 'SOCCSKSARGEN (XII)';
    if ( preg_match( '/AGUSAN|DINAGAT|SURIGAO|BUTUAN/u', $loc ) )                                              return 'Caraga (XIII)';
    if ( preg_match( '/SULU|TAWI-TAWI|BASILAN|LANAO DEL SUR|MAGUINDANAO|BANGSAMORO|COTABATO CITY/u', $loc ) ) return 'BARMM';
    if ( preg_match( '/BAGUIO|BENGUET|ABRA|APAYAO|IFUGAO|KALINGA|MOUNTAIN PROVINCE|HARRISON/u', $loc ) )      return 'CAR';

    return ''; // Unrecognised — leave blank so it doesn't mis-assign
}

/**
 * Before ACF saves a job post, auto-derive the correct region
 * from the selected job_location_tax term and override the
 * manually-chosen (and often incorrect) job_region field value.
 */
add_action( 'acf/save_post', 'kg_auto_set_job_region_on_save', 1 );
function kg_auto_set_job_region_on_save( $post_id ) {
    if ( get_post_type( $post_id ) !== 'jobs' ) {
        return;
    }

    // ACF taxonomy field stores the term ID in $_POST['acf']
    $term_id = isset( $_POST['acf']['field_job_location_tax'] )
        ? (int) $_POST['acf']['field_job_location_tax']
        : 0;

    if ( ! $term_id ) {
        return;
    }

    $term = get_term( $term_id, 'job_location_tax' );
    if ( is_wp_error( $term ) || empty( $term ) ) {
        return;
    }

    $region = kg_derive_region_from_location_name( $term->name );
    if ( ! empty( $region ) ) {
        // Override whatever the user selected — ACF reads from $_POST at priority 10
        $_POST['acf']['field_job_region'] = $region;
    }
}

/**
 * One-time migration: fix all existing jobs whose job_region was
 * incorrectly saved (e.g. defaulted to NCR).
 * Runs once on init, then marks itself done.
 */
function kg_fix_job_regions_migration() {
    if ( get_option( 'kg_job_regions_fixed_v1' ) ) {
        return;
    }

    $jobs = get_posts( array(
        'post_type'      => 'jobs',
        'post_status'    => 'any',
        'posts_per_page' => -1,
        'fields'         => 'ids',
    ) );

    foreach ( $jobs as $job_id ) {
        $terms = get_the_terms( $job_id, 'job_location_tax' );
        if ( empty( $terms ) || is_wp_error( $terms ) ) {
            continue;
        }
        $term   = reset( $terms );
        $region = kg_derive_region_from_location_name( $term->name );
        if ( ! empty( $region ) ) {
            update_post_meta( $job_id, 'job_region', $region );
        }
    }

    update_option( 'kg_job_regions_fixed_v1', true );
}
add_action( 'init', 'kg_fix_job_regions_migration', 30 );


/**
 * Add Location field to Recruiter user profile in wp-admin
 */
function kg_add_recruiter_profile_location_field( $user ) {
    // Only allow admins to assign/view this field, and only for recruiter roles
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    
    $user_roles = isset( $user->roles ) ? (array) $user->roles : array();
    if ( ! in_array( 'recruiter', $user_roles, true ) && ! in_array( 'administrator', $user_roles, true ) ) {
        return;
    }

    $current_location = get_user_meta( $user->ID, 'kg_recruiter_location', true );
    ?>
    <h3>Recruiter Settings</h3>
    <table class="form-table">
        <tr>
            <th><label for="kg_recruiter_location">Assigned Branch / Location</label></th>
            <td>
                <select name="kg_recruiter_location" id="kg_recruiter_location">
                    <option value="">— Unassigned (All Locations) —</option>
                    <?php foreach ( kg_get_locations() as $key => $label ) : ?>
                        <option value="<?php echo esc_attr( $key ); ?>" <?php selected( $current_location, $key ); ?>><?php echo esc_html( $label ); ?></option>
                    <?php endforeach; ?>
                </select>
                <p class="description">Recruiters will only be allowed to view and manage job posts and applicants matching this branch location.</p>
            </td>
        </tr>
    </table>
    <?php
}
add_action( 'show_user_profile', 'kg_add_recruiter_profile_location_field' );
add_action( 'edit_user_profile', 'kg_add_recruiter_profile_location_field' );

function kg_save_recruiter_profile_location_field( $user_id ) {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    if ( isset( $_POST['kg_recruiter_location'] ) ) {
        $allowed = array_keys( kg_get_locations() );
        $location = sanitize_text_field( $_POST['kg_recruiter_location'] );
        if ( in_array( $location, $allowed, true ) || $location === '' ) {
            update_user_meta( $user_id, 'kg_recruiter_location', $location );
        }
    }
}
add_action( 'personal_options_update', 'kg_save_recruiter_profile_location_field' );
add_action( 'edit_user_profile_update', 'kg_save_recruiter_profile_location_field' );


/**
 * Automated QR Code generation on Job publishes
 */
function kg_generate_job_qr_code( $post_id, $post, $update ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( $post->post_type !== 'jobs' ) return;
    if ( $post->post_status !== 'publish' ) return;

    $permalink = get_permalink( $post_id );
    if ( ! $permalink ) return;

    // Use api.qrserver.com public QR code API
    $qr_api_url = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode( $permalink );
    
    // Cache the QR code URL on the Job post
    update_post_meta( $post_id, 'kg_job_qr_code_url', esc_url_raw( $qr_api_url ) );
}
add_action( 'save_post_jobs', 'kg_generate_job_qr_code', 20, 3 );

/**
 * Register Job QR Code Metabox in wp-admin
 */
function kg_add_job_qr_code_metabox() {
    add_meta_box(
        'kg_job_qr_code_box',
        'Job Posting QR Code',
        'kg_render_job_qr_code_metabox',
        'jobs',
        'side',
        'default'
    );
}
add_action( 'add_meta_boxes_jobs', 'kg_add_job_qr_code_metabox' );

function kg_render_job_qr_code_metabox( $post ) {
    $qr_url = get_post_meta( $post->ID, 'kg_job_qr_code_url', true );
    if ( ! $qr_url && $post->post_status === 'publish' ) {
        // Fallback: build QR code URL inline if not cached yet
        $permalink = get_permalink( $post->ID );
        $qr_url = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode( $permalink );
    }

    if ( $qr_url ) : ?>
        <div style="text-align:center; padding:10px 0;">
             <img src="<?php echo esc_url( $qr_url ); ?>" style="max-width:100%; height:auto; border:1px solid #ddd; border-radius:4px; padding:4px;" alt="Job QR Code" />
            <p style="font-size:12px; color:#666; margin:8px 0 0 0;">Scan to view or apply to this job on a mobile device.</p>
            <p style="margin:8px 0 0 0;"><a href="<?php echo esc_url( $qr_url ); ?>&download=1" download="job-qr-code-<?php echo esc_attr( $post->post_name ); ?>.png" class="button" target="_blank">⬇ Download QR Code</a></p>
        </div>
    <?php else : ?>
        <p style="font-size:12px; color:#777; margin:0; padding:10px 0;">Publish this job listing to generate its unique scan-to-apply QR code automatically.</p>
    <?php endif;
}

/**
 * Phase 5: Schedule Daily Event for Job Post Expiry and Headcount Auto-Close
 */
if ( ! wp_next_scheduled( 'kg_daily_job_expiry_check' ) ) {
    wp_schedule_event( time(), 'daily', 'kg_daily_job_expiry_check' );
}

function kg_run_daily_job_expiry_check() {
    $jobs = get_posts( array(
        'post_type'      => 'jobs',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
    ) );

    $today = current_time( 'Ymd' ); // ACF date field defaults to Ymd format

    foreach ( $jobs as $job ) {
        $job_id = $job->ID;
        $should_close = false;

        // Check 1: Target Headcount Filled
        $target = (int) get_post_meta( $job_id, 'job_target_headcount', true );
        $filled = (int) get_post_meta( $job_id, 'job_filled_headcount', true );

        if ( $target > 0 && $filled >= $target ) {
            $should_close = true;
        }

        // Check 2: Expiry Date Reached
        $expiry_date = get_post_meta( $job_id, 'job_expiry_date', true ); // Ymd string e.g. 20260625
        if ( ! empty( $expiry_date ) && $today >= $expiry_date ) {
            $should_close = true;
        }

        if ( $should_close ) {
            // Set job_closed meta flag to 1
            update_post_meta( $job_id, 'job_closed', '1' );
        }
    }
}
add_action( 'kg_daily_job_expiry_check', 'kg_run_daily_job_expiry_check' );

/**
 * Phase 8: Register Job Posting Analytics Metabox
 */
function kg_add_job_analytics_metabox() {
    add_meta_box(
        'kg_job_analytics_box',
        'Job Posting Analytics',
        'kg_render_job_analytics_metabox',
        'jobs',
        'side',
        'default'
    );
}
add_action( 'add_meta_boxes_jobs', 'kg_add_job_analytics_metabox' );

function kg_render_job_analytics_metabox( $post ) {
    $views = (int) get_post_meta( $post->ID, 'job_view_count', true );

    // Count all applications received for this job
    $apps = new WP_Query( array(
        'post_type'      => 'kg_application',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'meta_query'     => array(
            array(
                'key'   => 'kg_app_role',
                'value' => $post->post_title,
            )
        )
    ) );
    $total_apps = $apps->found_posts;

    // Count applications this month
    $current_month_apps = new WP_Query( array(
        'post_type'      => 'kg_application',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'date_query'     => array(
            array(
                'year'  => date( 'Y' ),
                'month' => date( 'm' ),
            )
        ),
        'meta_query'     => array(
            array(
                'key'   => 'kg_app_role',
                'value' => $post->post_title,
            )
        )
    ) );
    $monthly_apps = $current_month_apps->found_posts;
    ?>
    <div style="padding: 5px 0;">
        <table style="width:100%; border-collapse:collapse; font-size:13px;">
            <tr style="border-bottom:1px solid #eee;">
                <td style="padding:8px 0; font-weight:600; color:#475569;">Total Page Views</td>
                <td style="padding:8px 0; text-align:right; font-weight:bold; color:#0f172a;"><?php echo number_format($views); ?></td>
            </tr>
            <tr style="border-bottom:1px solid #eee;">
                <td style="padding:8px 0; font-weight:600; color:#475569;">Total Applications</td>
                <td style="padding:8px 0; text-align:right; font-weight:bold; color:#0f172a;"><?php echo number_format($total_apps); ?></td>
            </tr>
            <tr>
                <td style="padding:8px 0; font-weight:600; color:#475569;">Applications (This Month)</td>
                <td style="padding:8px 0; text-align:right; font-weight:bold; color:#0f172a;"><?php echo number_format($monthly_apps); ?></td>
            </tr>
        </table>
    </div>
    <?php
}

/**
 * Phase 3: "Share Job to Social Media" Facebook Toolkit
 */
function kg_add_job_social_toolkit_metabox() {
    add_meta_box(
        'kg_job_social_toolkit_box',
        'Facebook Sharing Toolkit',
        'kg_render_job_social_toolkit_metabox',
        'jobs',
        'side',
        'default'
    );
}
add_action( 'add_meta_boxes_jobs', 'kg_add_job_social_toolkit_metabox' );

function kg_render_job_social_toolkit_metabox( $post ) {
    $title     = get_the_title( $post->ID );
    $location  = get_post_meta( $post->ID, 'job_location', true ) ?: 'Philippines';
    $permalink = get_permalink( $post->ID );
    
    // Format Facebook Posting Text
    $fb_copy = "📢 WE ARE HIRING! 📢\n\n"
             . "💼 Role: " . esc_html($title) . "\n"
             . "📍 Location: " . esc_html($location) . "\n\n"
             . "Apply instantly here:\n🔗 " . esc_url($permalink) . "\n\n"
             . "Or scan the QR code to apply on your mobile phone!";
    ?>
    <div style="padding:5px 0;">
        <textarea id="kg-fb-copy-text" style="width:100%; height:120px; font-size:12px; font-family:monospace; padding:6px; margin-bottom:8px; border-radius:4px; border:1px solid #ccc;" readonly><?php echo esc_textarea($fb_copy); ?></textarea>
        <button type="button" class="button button-primary" onclick="kgCopyFacebookText()" style="width:100%; text-align:center;">📋 Copy Copywriting for Facebook</button>
        
        <script type="text/javascript">
            function kgCopyFacebookText() {
                var copyText = document.getElementById("kg-fb-copy-text");
                copyText.select();
                copyText.setSelectionRange(0, 99999); // For mobile devices
                navigator.clipboard.writeText(copyText.value).then(function() {
                    alert("Facebook recruitment text copied to clipboard!");
                }, function() {
                    // Fallback
                    document.execCommand("copy");
                    alert("Facebook recruitment text copied to clipboard!");
                });
            }
        </script>
    </div>
    <?php
}

if (function_exists('add_action')) {
    add_action('init', function () {
        if (get_option('kg_cta_partner_to_provider_updated_v2') !== '1') {
            global $wpdb;
            if (isset($wpdb->postmeta)) {
                $wpdb->query("UPDATE {$wpdb->postmeta} SET meta_value = REPLACE(meta_value, 'Partner for Manpower Solutions', 'Provider for Manpower Solutions') WHERE meta_value LIKE '%Partner for Manpower Solutions%'");
                $wpdb->query("UPDATE {$wpdb->postmeta} SET meta_value = REPLACE(meta_value, 'Your Trusted Partner for Manpower', 'Your Trusted Provider for Manpower') WHERE meta_value LIKE '%Your Trusted Partner for Manpower%'");
                update_option('kg_cta_partner_to_provider_updated_v2', '1');
            }
        }
    });
}
