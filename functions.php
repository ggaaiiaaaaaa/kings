<?php
/**
 * Compatibility Shim: Allows the site to run on localhost without WordPress.
 */
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
        echo '<link rel="stylesheet" href="style.css">';
    }
}

if (!function_exists('wp_footer')) {
    function wp_footer()
    {
        echo '<script src="script.js"></script>';
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

    // Pass AJAX URL and nonces to JS — available as KG_AJAX.url, KG_AJAX.contact_nonce, etc.
    wp_localize_script('kingsgroup-script', 'KG_AJAX', array(
        'url' => admin_url('admin-ajax.php'),
        'contact_nonce' => wp_create_nonce('kg_contact_nonce'),
        'careers_nonce' => wp_create_nonce('kg_careers_nonce'),
        'quote_nonce' => wp_create_nonce('kg_quote_nonce'),
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
        if ($value !== null && $value !== false && $value !== '') {
            return $value;
        }
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
            'role' => 'Cooperative Member',
            'order' => 0
        ),
        array(
            'name' => 'Melanie Santos',
            'quote' => 'Maraming salamat ssa KINGS, kase nabigyan ako ng opurtunidad na makapag trabaho at makapag aral ang aking mga anak.',
            'role' => 'Cooperative Member',
            'order' => 1
        ),
        array(
            'name' => 'Daisy Salaño',
            'quote' => 'Working with KINGS feels like my second home. They treated me as a family.',
            'role' => 'Cooperative Member',
            'order' => 2
        ),
        array(
            'name' => 'Mariel Insur',
            'quote' => 'Nang dahil sa KINGS, nasusuportahan ko ang pangangailangan ng aking anak sa pag-aaral at ganun din ang pangangailangan pang medical ng akin inang may cancer.',
            'role' => 'Cooperative Member',
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
 * Automatically seeds the 14 requested jobs if they are not already present in the jobs CPT database.
 */
function kg_seed_jobs()
{
    if (!post_type_exists('jobs'))
        return;

    // Check if we already seeded these exact jobs to avoid duplicate inserts
    $check = get_posts(array(
        'post_type' => 'jobs',
        'title' => 'Accounting Supervisor',
        'post_status' => 'any',
        'numberposts' => 1
    ));
    if (!empty($check)) {
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

    $default_jobs = array(
        array(
            'title' => 'Operations Head',
            'desc' => 'Strategic oversight, operational planning, and leadership of cooperative business units.',
            'price' => 2500,
            'dept' => 'Operations',
            'setup' => 'WFO',
            'min' => 80000,
            'max' => 120000,
            'type' => 'FULL_TIME'
        ),
        array(
            'title' => 'Accounting and Finance Head',
            'desc' => 'Financial reporting, strategic planning, budgeting, tax management, and leadership of accounting department.',
            'price' => 2400,
            'dept' => 'Finance',
            'setup' => 'WFO',
            'min' => 75000,
            'max' => 110000,
            'type' => 'FULL_TIME'
        ),
        array(
            'title' => 'Building Administrator',
            'desc' => 'Facilities management, vendor relations, property maintenance, safety compliance, and tenant support.',
            'price' => 1500,
            'dept' => 'Operations',
            'setup' => 'WFO',
            'min' => 45000,
            'max' => 70000,
            'type' => 'FULL_TIME'
        ),
        array(
            'title' => 'Culinary Administrator',
            'desc' => 'Kitchen operations management, curriculum oversight, culinary training compliance, and food safety standards.',
            'price' => 1600,
            'dept' => 'Operations',
            'setup' => 'WFO',
            'min' => 48000,
            'max' => 75000,
            'type' => 'FULL_TIME'
        ),
        array(
            'title' => 'Software Developer',
            'desc' => 'Frontend, Backend, or Full Stack development across modern technology stacks and frameworks.',
            'price' => 2000,
            'dept' => 'Technology',
            'setup' => 'WFH',
            'min' => 60000,
            'max' => 100000,
            'type' => 'CONTRACTOR'
        ),
        array(
            'title' => 'Business Analyst',
            'desc' => 'Requirements gathering, process optimization, stakeholder coordination, and business intelligence reporting.',
            'price' => 1600,
            'dept' => 'Technology',
            'setup' => 'WFH',
            'min' => 50000,
            'max' => 80000,
            'type' => 'CONTRACTOR'
        ),
        array(
            'title' => 'Marketing Officer',
            'desc' => 'Brand promotion, campaign execution, social media management, content creation, and lead generation.',
            'price' => 1200,
            'dept' => 'Marketing',
            'setup' => 'Hybrid',
            'min' => 35000,
            'max' => 55000,
            'type' => 'FULL_TIME'
        ),
        array(
            'title' => 'HR Coordinator',
            'desc' => 'Employee relations, benefits administration, onboarding coordination, and HR policy enforcement.',
            'price' => 1300,
            'dept' => 'Human Resources',
            'setup' => 'WFO',
            'min' => 38000,
            'max' => 60000,
            'type' => 'FULL_TIME'
        ),
        array(
            'title' => 'Recruitment Officer',
            'desc' => 'Talent acquisition, candidate sourcing, initial screening, interview scheduling, and hiring pipeline management.',
            'price' => 1200,
            'dept' => 'Human Resources',
            'setup' => 'Hybrid',
            'min' => 35000,
            'max' => 55000,
            'type' => 'FULL_TIME'
        ),
        array(
            'title' => 'Billing and Collection Officer',
            'desc' => 'Invoicing management, accounts receivable tracking, payment collection coordination, and client reconciliation.',
            'price' => 1100,
            'dept' => 'Finance',
            'setup' => 'WFO',
            'min' => 30000,
            'max' => 48000,
            'type' => 'FULL_TIME'
        ),
        array(
            'title' => 'Payroll Master / Senior Payroll Analyst',
            'desc' => 'End-to-end payroll processing, statutory deductions, tax filing compliance, and payroll auditing.',
            'price' => 1600,
            'dept' => 'Finance',
            'setup' => 'WFO',
            'min' => 50000,
            'max' => 80000,
            'type' => 'FULL_TIME'
        ),
        array(
            'title' => 'Payroll Staff',
            'desc' => 'Timekeeping verification, payroll data entry, payslip distribution, and query resolution support.',
            'price' => 1000,
            'dept' => 'Finance',
            'setup' => 'WFO',
            'min' => 25000,
            'max' => 40000,
            'type' => 'FULL_TIME'
        ),
        array(
            'title' => 'Accounting Supervisor',
            'desc' => 'General ledger management, bank reconciliations, tax compliance supervision, and financial statement preparation support.',
            'price' => 1700,
            'dept' => 'Finance',
            'setup' => 'WFO',
            'min' => 55000,
            'max' => 85000,
            'type' => 'FULL_TIME'
        ),
        array(
            'title' => 'Accounting Manager',
            'desc' => 'Full-cycle accounting management, internal controls implementation, financial audit coordination, and team leadership.',
            'price' => 2200,
            'dept' => 'Finance',
            'setup' => 'WFO',
            'min' => 70000,
            'max' => 105000,
            'type' => 'FULL_TIME'
        ),
    );

    foreach ($default_jobs as $job) {
        $job_id = wp_insert_post(array(
            'post_title' => $job['title'],
            'post_status' => 'publish',
            'post_type' => 'jobs',
            'post_excerpt' => $job['desc'],
        ));

        $img = 'https://images.unsplash.com/photo-1600880292203-757bb62b4baf?auto=format&fit=crop&w=600&q=80';
        if ($job['title'] === 'Software Developer') {
            $img = 'https://images.unsplash.com/photo-1607799279861-4dd421887fb3?auto=format&fit=crop&w=600&q=80';
        }

        update_post_meta($job_id, 'base_price', $job['price']);
        update_post_meta($job_id, '_base_price', 'field_job_base_price');
        update_post_meta($job_id, 'include_in_team_builder', 1);
        update_post_meta($job_id, '_include_in_team_builder', 'field_job_include_team_builder');

        update_post_meta($job_id, 'job_card_image', $img);
        update_post_meta($job_id, '_job_card_image', 'field_job_card_image');
        update_post_meta($job_id, 'job_location', $job['setup'] === 'WFH' ? 'Remote, Philippines' : 'Parañaque, Metro Manila');
        update_post_meta($job_id, '_job_location', 'field_job_location');
        update_post_meta($job_id, 'job_type', $job['type']);
        update_post_meta($job_id, '_job_type', 'field_job_type');
        update_post_meta($job_id, 'job_work_setup', $job['setup']);
        update_post_meta($job_id, '_job_work_setup', 'field_job_work_setup');
        update_post_meta($job_id, 'job_salary_min', $job['min']);
        update_post_meta($job_id, '_job_salary_min', 'field_job_salary_min');
        update_post_meta($job_id, 'job_salary_max', $job['max']);
        update_post_meta($job_id, '_job_salary_max', 'field_job_salary_max');
        update_post_meta($job_id, 'job_department', $job['dept']);
        update_post_meta($job_id, '_job_department', 'field_job_department');
        update_post_meta($job_id, 'job_target_headcount', 5);
        update_post_meta($job_id, '_job_target_headcount', 'field_job_target_headcount');
        update_post_meta($job_id, 'job_filled_headcount', 0);
        update_post_meta($job_id, '_job_filled_headcount', 'field_job_filled_headcount');
    }
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

    // Check if the post already exists to avoid duplicate inserts
    $check = get_posts(array(
        'post_type'   => 'post',
        'title'       => 'Commitment, Culture, and Community in Action (October 2025)',
        'post_status' => 'any',
        'numberposts' => 1
    ));
    if (!empty($check)) {
        return; // Already seeded, do nothing
    }

    $post_content = '
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

<h2>Afterwork Yoga: Clock out & roll out (your mat) with us!</h2>
<p>Our 1st Afterwork Yoga took place last September 26 with Teacher Ven at Kings City’s Roofdeck. It was the perfect after-work unwind and recharge session we all needed! We loved the deep stretches, calm energy, and how totally beginner-friendly it was.</p>
<p>We also had a FREE Yoga Session for Desk Workers last October 11 at BetterLife Studio. It was the perfect reminder to pause, breathe, and recharge in the middle of a busy week!</p>

<h2>Coming Soon: Scout of the Month</h2>
<ul>
  <li><strong>PROACTIVE & DRIVEN</strong> - Takes initiative, looks for ways to contribute, and goes beyond what’s expected.</li>
  <li><strong>LEADS BY EXAMPLE</strong> - Inspires others through actions, reliability, and professionalism.</li>
  <li><strong>EMBODIES THE KINGS SPIRIT</strong> - Demonstrates dedication, teamwork, and commitment to excellence in all tasks.</li>
</ul>
';

    $post_id = wp_insert_post(array(
        'post_title'   => 'Commitment, Culture, and Community in Action (October 2025)',
        'post_content' => $post_content,
        'post_status'  => 'publish',
        'post_date'    => '2025-10-30 09:00:00',
        'post_type'    => 'post',
        'post_excerpt' => 'Kings Lending celebrates 26 years, Cebu earthquake relief, PP Cory Navarro AKS recognition, SCPA upskilling training, and Halloween Scare for a Cause.',
    ));

    if ($post_id && !is_wp_error($post_id)) {
        // Set the custom meta image URL for the post featured image fallback
        update_post_meta($post_id, '_kg_post_image', 'https://images.unsplash.com/photo-1531482615713-2afd69097998?auto=format&fit=crop&w=1200&q=80');
        
        // Also assign to the "Community" category if it exists, or create it
        $cat_id = get_cat_ID('Community');
        if (!$cat_id) {
            if (!function_exists('wp_create_category')) {
                require_once(ABSPATH . 'wp-admin/includes/taxonomy.php');
            }
            $cat_id = wp_create_category('Community');
        }
        if ($cat_id) {
            wp_set_post_categories($post_id, array($cat_id));
        }
    }
}
add_action('init', 'kg_seed_news_posts', 40);



