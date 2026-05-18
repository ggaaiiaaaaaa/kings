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
        add_image_size( 'kg-hero',      1920, 800,  true );
        add_image_size( 'kg-card',      600,  400,  true );
        add_image_size( 'kg-thumbnail', 300,  200,  true );
    }
endif;
add_action('after_setup_theme', 'kingsgroup_setup');

/**
 * Auto-create and assign nav menus if they don't exist yet.
 * Runs once on init; skips silently if menus are already set up.
 */
function kg_create_default_menus() {
    // Client nav (left side) — About dropdown and Get a Quote are hardcoded in header.php
    $client_items = array(
        array( 'title' => 'Home', 'url' => home_url('/') ),
    );

    // Applicant nav (right side)
    $applicant_items = array(
        array( 'title' => 'Our Jobs',      'url' => home_url('/our-jobs/') ),
        array( 'title' => 'Apply Now',     'url' => home_url('/careers/') ),
        array( 'title' => 'Member Portal', 'url' => 'https://zckings.azurewebsites.net/' ),
        array( 'title' => 'Log In',        'url' => wp_login_url() ),
    );

    // Footer nav
    $footer_items = array(
        array( 'title' => 'Our Story',    'url' => home_url('/story/') ),
        array( 'title' => 'News',         'url' => home_url('/news/') ),
        array( 'title' => 'Community',    'url' => home_url('/community/') ),
        array( 'title' => 'Careers',      'url' => home_url('/careers/') ),
        array( 'title' => 'Contact Us',   'url' => home_url('/contact/') ),
        array( 'title' => 'Member Portal','url' => 'https://zckings.azurewebsites.net/' ),
        array( 'title' => 'Kings Lending','url' => 'https://kingslending.timefree.ph/' ),
        array( 'title' => 'Benefits',     'url' => home_url('/benefits/') ),
        array( 'title' => 'Terms of Service', 'url' => home_url('/terms/') ),
        array( 'title' => 'Privacy Policy',   'url' => home_url('/privacy/') ),
    );

    $menus = array(
        'menu-1' => array( 'name' => 'Primary Client Menu',    'items' => $client_items ),
        'menu-2' => array( 'name' => 'Primary Applicant Menu', 'items' => $applicant_items ),
        'footer' => array( 'name' => 'Footer Menu',            'items' => $footer_items ),
    );

    foreach ( $menus as $location => $config ) {
        $existing = wp_get_nav_menu_object( $config['name'] );

        if ( $existing ) {
            // Delete and recreate to ensure items stay in sync with code
            wp_delete_nav_menu( $existing->term_id );
        }

        $menu_id = wp_create_nav_menu( $config['name'] );
        if ( is_wp_error( $menu_id ) ) continue;

        foreach ( $config['items'] as $item ) {
            wp_update_nav_menu_item( $menu_id, 0, array(
                'menu-item-title'  => $item['title'],
                'menu-item-url'    => $item['url'],
                'menu-item-status' => 'publish',
                'menu-item-type'   => 'custom',
            ) );
        }

        // Assign to theme location
        $locations            = get_theme_mod( 'nav_menu_locations', array() );
        $locations[ $location ] = $menu_id;
        set_theme_mod( 'nav_menu_locations', $locations );
    }
}
add_action( 'init', 'kg_create_default_menus' );

/**
 * Auto-add menu-btn-primary class to "Get a Quote" nav item.
 */
function kg_nav_item_classes( $classes, $item ) {
    if ( $item->title === 'Get a Quote' ) {
        $classes[] = 'menu-btn-primary';
    }
    return $classes;
}
add_filter( 'nav_menu_css_class', 'kg_nav_item_classes', 10, 2 );

/**
 * Enqueue scripts and styles.
 */
function kingsgroup_scripts()
{
    wp_enqueue_style('kingsgroup-style', get_stylesheet_uri(), array(), filemtime(get_template_directory() . '/style.css'));
    wp_enqueue_script('kingsgroup-script', get_template_directory_uri() . '/script.js', array(), filemtime(get_template_directory() . '/script.js'), true);

    // Pass AJAX URL and nonces to JS — available as KG_AJAX.url, KG_AJAX.contact_nonce, etc.
    wp_localize_script( 'kingsgroup-script', 'KG_AJAX', array(
        'url'           => admin_url('admin-ajax.php'),
        'contact_nonce' => wp_create_nonce('kg_contact_nonce'),
        'careers_nonce' => wp_create_nonce('kg_careers_nonce'),
        'quote_nonce'   => wp_create_nonce('kg_quote_nonce'),
    ) );
}
add_action('wp_enqueue_scripts', 'kingsgroup_scripts');

// Load form handlers — registers all three wp_ajax_* actions
if ( function_exists('add_action') ) {
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
function kg_img($url, $alt = 'Image', $class = '', $style = '', $loading = 'lazy') {
    $style_attr   = $style   ? ' style="'   . esc_attr($style)   . '"' : '';
    $loading_attr = $loading ? ' loading="' . esc_attr($loading) . '"' : '';
    if (!empty($url)) {
        return '<img src="' . esc_url($url) . '" alt="' . esc_attr($alt) . '" class="' . esc_attr($class) . '"' . $loading_attr . $style_attr . '>';
    }
    return '<div class="kg-no-image ' . esc_attr($class) . '"' . $style_attr . '><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg><span>No Image</span></div>';
}

/**
 * Returns the .webp version of an image path if that file exists on disk.
 * Falls back to the original path if no .webp counterpart is found.
 * Usage: kg_asset( kg_webp('img/logo.png') )
 */
function kg_webp( $path ) {
    $webp_path = preg_replace( '/\.(png|jpg|jpeg)$/i', '.webp', $path );
    if ( $webp_path === $path ) {
        return $path;
    }
    $base = function_exists( 'get_template_directory' )
        ? get_template_directory()
        : __DIR__;
    $abs = rtrim( $base, '/' ) . '/' . ltrim( $webp_path, '/' );
    return file_exists( $abs ) ? $webp_path : $path;
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
function kg_get_field($field_name, $fallback = '', $post_id = null) {
    if (function_exists('get_field')) {
        if ($post_id === null) {
            $post_id = get_queried_object_id();
        }
        $value = get_field($field_name, $post_id);
        if ($value !== null && $value !== false) {
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
function kg_redirect_jobs_archive() {
    if ( is_post_type_archive( 'jobs' ) ) {
        // Try to find the page with the our-jobs.php template
        $portal_page = get_pages( array(
            'meta_key' => '_wp_page_template',
            'meta_value' => 'our-jobs.php'
        ));

        if ( ! empty( $portal_page ) ) {
            wp_redirect( get_permalink( $portal_page[0]->ID ), 301 );
            exit;
        } else {
            // Fallback to home if page not found
            wp_redirect( home_url( '/our-jobs/' ), 301 );
            exit;
        }
    }
}
add_action( 'template_redirect', 'kg_redirect_jobs_archive' );

/**
 * Flush rewrite rules once after theme activation or CPT registration changes.
 * Runs only when the flush flag isn't set yet, then sets it so it never runs twice.
 */
function kg_flush_rewrite_once() {
    if ( ! get_option('kg_rewrite_flushed') ) {
        flush_rewrite_rules();
        update_option('kg_rewrite_flushed', true);
    }
}
add_action('init', 'kg_flush_rewrite_once', 20 );

/**
 * Configure PHPMailer directly with Gmail SMTP credentials from wp-config.php.
 * This bypasses WP Mail SMTP OAuth and works with a Gmail App Password.
 * Credentials are defined in wp-config.php (not committed to git).
 */
if ( defined('KG_SMTP_HOST') ) {
    add_action( 'phpmailer_init', function( $phpmailer ) {
        $phpmailer->isSMTP();
        $phpmailer->Host       = KG_SMTP_HOST;
        $phpmailer->Port       = KG_SMTP_PORT;
        $phpmailer->SMTPAuth   = true;
        $phpmailer->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $phpmailer->Username   = KG_SMTP_USER;
        $phpmailer->Password   = KG_SMTP_PASS;
        $phpmailer->setFrom( KG_SMTP_FROM, KG_SMTP_FROMNAME );
    }, 999 );
}

/**
 * Returns an inline SVG icon string.
 * Uses Heroicons 24x24 outline.
 */
function kg_icon($name, $class = '') {
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


