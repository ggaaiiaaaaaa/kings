# CMS Integration with ACF Blocks Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Convert the static Kings Group website into a dynamic WordPress theme using ACF Custom Blocks to provide a visual, drag-and-drop editing experience for the client, while maintaining the "Liquid Glass" custom design.

**Architecture:** We will register custom Gutenberg blocks using ACF Pro (`acf_register_block_type`). We will extract the static HTML sections (like the Hero section) from page templates and move them into block template files (`template-parts/blocks/`). We will then update the page templates to use `the_content()` so WordPress can render the blocks natively. We will also set up a Custom Post Type for Jobs.

**Tech Stack:** WordPress, PHP, ACF Pro Blocks, Gutenberg.

---

### Task 1: Register ACF Blocks Support

**Files:**
- Modify: `functions.php`

- [ ] **Step 1: Add ACF Block Registration function**

```php
// Add to functions.php
function kingsgroup_register_acf_blocks() {
    // Check function exists.
    if( function_exists('acf_register_block_type') ) {
        // Register a Hero block.
        acf_register_block_type(array(
            'name'              => 'kg-hero',
            'title'             => __('Kings Group Hero'),
            'description'       => __('A custom hero block with Liquid Glass styling.'),
            'render_template'   => 'template-parts/blocks/hero/hero.php',
            'category'          => 'formatting',
            'icon'              => 'admin-users',
            'keywords'          => array( 'hero', 'kings', 'glass' ),
            'supports'          => array('align' => false)
        ));
    }
}
add_action('acf/init', 'kingsgroup_register_acf_blocks');
```

- [ ] **Step 2: Commit**

```bash
git add functions.php
git commit -m "feat: register ACF Custom Block for Hero section"
```

### Task 2: Create Hero Block Template

**Files:**
- Create: `template-parts/blocks/hero/hero.php`

- [ ] **Step 1: Create the directory and file**

Run: `mkdir -p template-parts/blocks/hero`

- [ ] **Step 2: Write the block template**

```php
<?php
/**
 * Hero Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id and class attribute
$id = 'kg-hero-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}
$className = 'hero';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}

// Load values and assign defaults.
$headline = get_field('hero_headline') ?: 'Elite Talent.<br><span>Ethical Staffing.</span>Exceptional Results.';
$description = get_field('hero_description') ?: 'We connect global businesses with the Philippines\' top professionals...';
$bg_image_1 = get_field('hero_bg_1') ?: kg_asset('img/HeroCulinary.webp'); // Ensure valid fallback
?>
<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
    <div class="hero-bg-media" id="hero-slider">
        <img src="<?php echo esc_url($bg_image_1); ?>" class="hero-slide active" alt="Hero Background">
    </div>
    <div class="hero-content">
        <h1><?php echo wp_kses_post($headline); ?></h1>
        <p><?php echo esc_html($description); ?></p>
        <div class="hero-buttons">
            <a href="<?php echo esc_url(home_url('/quote/')); ?>" class="btn btn-primary">Build Your Team</a>
            <a href="<?php echo esc_url(home_url('/careers/')); ?>" class="btn btn-outline" style="background: rgba(255,255,255,0.1); color: white; border-color: rgba(255,255,255,0.3); backdrop-filter: blur(5px);">View Open Roles</a>
        </div>
    </div>
</section>
```

- [ ] **Step 3: Commit**

```bash
git add template-parts/blocks/hero/hero.php
git commit -m "feat: create ACF Hero block render template"
```

### Task 3: Update Front Page for Gutenberg

**Files:**
- Modify: `front-page.php`

- [ ] **Step 1: Replace hardcoded Hero section with the_content() loop**

```php
// In front-page.php, replace the entire <section class="hero">...</section> with:

<?php
// If there is content in the Gutenberg editor, show it.
if ( have_posts() ) {
    while ( have_posts() ) {
        the_post();
        the_content();
    }
} else {
    // Fallback if no blocks are added yet
    echo '<p>Please add blocks in the WordPress editor.</p>';
}
?>
```

- [ ] **Step 2: Commit**

```bash
git add front-page.php
git commit -m "feat: update front-page to render Gutenberg blocks instead of hardcoded hero"
```

### Task 4: Setup Jobs CPT

**Files:**
- Modify: `functions.php`

- [ ] **Step 1: Register 'jobs' Custom Post Type**

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

- [ ] **Step 2: Commit**

```bash
git add functions.php
git commit -m "feat: register Jobs CPT"
```
