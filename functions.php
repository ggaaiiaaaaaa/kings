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
 * Enqueue scripts and styles.
 */
function kingsgroup_scripts()
{
    wp_enqueue_style('kingsgroup-style', get_stylesheet_uri(), array(), filemtime(get_template_directory() . '/style.css'));
    wp_enqueue_script('kingsgroup-script', get_template_directory_uri() . '/script.js', array(), filemtime(get_template_directory() . '/script.js'), true);
}
add_action('wp_enqueue_scripts', 'kingsgroup_scripts');

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


