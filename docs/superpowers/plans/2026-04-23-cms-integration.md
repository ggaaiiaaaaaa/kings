# CMS Integration & Deployment Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Convert the static Kings Group website into a fully functional, dynamic WordPress theme with ACF integration, Custom Post Types for Jobs, and prepare it for production deployment.

**Architecture:** We will use Advanced Custom Fields (ACF) Pro to create options pages and field groups to replace hardcoded content in PHP templates. We will register a Custom Post Type (CPT) for Jobs and integrate it with the frontend. Finally, we will configure essential plugins (RankMath, CF7, WP Mail SMTP, Solid Security).

**Tech Stack:** WordPress, PHP, ACF Pro, RankMath, Contact Form 7.

---

### Task 1: ACF Global Settings (Options Page)

**Files:**
- Modify: `functions.php`
- Modify: `header.php`
- Modify: `footer.php`

- [ ] **Step 1: Register ACF Options Page in functions.php**

```php
// Add to functions.php
if( function_exists('acf_add_options_page') ) {
    acf_add_options_page(array(
        'page_title'    => 'Theme General Settings',
        'menu_title'    => 'Theme Settings',
        'menu_slug'     => 'theme-general-settings',
        'capability'    => 'edit_posts',
        'redirect'      => false
    ));
}
```

- [ ] **Step 2: Verify Options Page appears in WP Admin**
Action: Open WP Dashboard, verify "Theme Settings" appears in the sidebar.

- [ ] **Step 3: Update Header to use ACF fields**
(Assuming fields 'logo_white' and 'logo_black' are created in ACF pointing to the Options page)
```php
// In header.php, replace hardcoded logo paths:
// <img src="<?php echo kg_asset('img/[LOGO] Main Logo White.webp'); ?>" ...>
// with:
<?php 
$logo_white = function_exists('get_field') && get_field('logo_white', 'option') ? get_field('logo_white', 'option') : kg_asset('img/[LOGO] Main Logo White.webp');
?>
<img src="<?php echo esc_url($logo_white); ?>" alt="Kings Group Logo" class="brand-logo">
```

- [ ] **Step 4: Update Footer to use ACF fields**
(Assuming 'footer_description' is created)
```php
// In footer.php, replace hardcoded description:
// <p style="color: rgba(255,255,255,0.7); font-size: 0.95rem;">Empowering global teams...</p>
// with:
<p style="color: rgba(255,255,255,0.7); font-size: 0.95rem;">
    <?php echo esc_html(function_exists('get_field') && get_field('footer_description', 'option') ? get_field('footer_description', 'option') : 'Empowering global teams with ethical Philippine talent through a worker-owned cooperative model.'); ?>
</p>
```

- [ ] **Step 5: Commit**
```bash
git add functions.php header.php footer.php
git commit -m "feat: add ACF Options page and connect header/footer"
```

### Task 2: Jobs Custom Post Type

**Files:**
- Modify: `functions.php`

- [ ] **Step 1: Register 'jobs' CPT**

```php
// Add to functions.php
function kingsgroup_register_jobs_cpt() {
    $labels = array(
        'name'                  => _x( 'Jobs', 'Post type general name', 'kingsgroup' ),
        'singular_name'         => _x( 'Job', 'Post type singular name', 'kingsgroup' ),
        'menu_name'             => _x( 'Jobs', 'Admin Menu text', 'kingsgroup' ),
        'add_new'               => __( 'Add New', 'kingsgroup' ),
        'add_new_item'          => __( 'Add New Job', 'kingsgroup' ),
    );
    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'jobs' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
    );
    register_post_type( 'jobs', $args );
}
add_action( 'init', 'kingsgroup_register_jobs_cpt' );
```

- [ ] **Step 2: Verify CPT in WP Admin**
Action: Open WP Dashboard, verify "Jobs" appears in the sidebar.

- [ ] **Step 3: Commit**
```bash
git add functions.php
git commit -m "feat: register Jobs custom post type"
```

### Task 3: Navigation Menus

**Files:**
- Modify: `header.php`

- [ ] **Step 1: Replace static navigation with wp_nav_menu**

```php
// In header.php, replace the <div class="nav-section client"> ... </div> block with:
<div class="nav-section client">
    <?php
    if ( has_nav_menu( 'menu-1' ) ) {
        wp_nav_menu(array(
            'theme_location' => 'menu-1',
            'container'      => false,
            'menu_class'     => 'nav-list',
            'fallback_cb'    => false,
        ));
    } else {
        echo '<a href="' . esc_url(home_url('/')) . '" class="nav-link">Home</a>';
    }
    ?>
    <a href="<?php echo esc_url(home_url('/quote/')); ?>" class="nav-link nav-btn primary">Get a Quote</a>
</div>
```

- [ ] **Step 2: Verify Menu on Frontend**
Action: Assign a menu to "Primary Client Menu" in WP Admin -> Appearance -> Menus, and check frontend.

- [ ] **Step 3: Commit**
```bash
git add header.php
git commit -m "feat: integrate wp_nav_menu for primary navigation"
```

### Task 4: Dynamic Home Page (ACF Integration)

**Files:**
- Modify: `front-page.php`

- [ ] **Step 1: Replace Hero Content with ACF fields**

```php
// In front-page.php, find:
// <h1>Elite Talent.<br><span>Ethical Staffing.</span>Exceptional Results.</h1>
// Replace with:
<h1>
    <?php echo function_exists('get_field') && get_field('hero_headline') ? get_field('hero_headline') : 'Elite Talent.<br><span>Ethical Staffing.</span>Exceptional Results.'; ?>
</h1>
```

- [ ] **Step 2: Commit**
```bash
git add front-page.php
git commit -m "feat: make homepage hero headline dynamic via ACF"
```

### Task 5: Security & Optimization Plugins

**Files:**
- None (WP Admin only)

- [ ] **Step 1: Install and Activate WP Mail SMTP**
Action: Navigate to Plugins > Add New, search for "WP Mail SMTP", install, activate, and configure credentials.

- [ ] **Step 2: Install and Activate WPS Hide Login**
Action: Navigate to Plugins > Add New, search for "WPS Hide Login", install, activate, and configure new login URL (e.g., `/kg-secure-login`).

- [ ] **Step 3: Install and Activate RankMath SEO**
Action: Navigate to Plugins > Add New, search for "RankMath", install, activate, and run setup wizard.
