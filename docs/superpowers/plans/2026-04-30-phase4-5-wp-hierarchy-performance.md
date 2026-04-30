# Phase 4 & 5 — WP Template Hierarchy + Performance Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Wire WordPress nav menus dynamically, add `single-jobs.php`, `archive-jobs.php`, and `search.php` templates, then optimize all images with WebP serving and lazy loading.

**Architecture:** Phase 4 converts hardcoded HTML nav links to `wp_nav_menu()` calls (3 locations already registered), creates CPT templates for the existing `jobs` post type, and adds a search results page. Phase 5 adds a `kg_webp()` filesystem helper to auto-serve `.webp` over `.png`/`.jpg`, audits all templates for `loading="lazy"`, registers WP image sizes, and updates `kg_img()` to lazy-load by default.

**Tech Stack:** PHP 8+, WordPress template hierarchy, ACF (programmatic fields), vanilla CSS custom properties (existing glass token system)

---

## File Map

| File | Action |
|---|---|
| `functions.php` | Add `kg_webp()`, update `kg_img()`, add `add_image_size()` calls inside `kingsgroup_setup()` |
| `inc/acf-fields.php` | Add `group_jobs` ACF field group for the jobs CPT |
| `header.php` | Replace hardcoded client + applicant nav `<a>` tags with `wp_nav_menu()` |
| `footer.php` | Replace hardcoded footer column links with `wp_nav_menu()` |
| `style.css` | Add nav-menu-list reset rules so `<ul><li><a>` renders identically to current bare `<a>` links |
| `single-jobs.php` | **Create** — individual job post page with `JobPosting` JSON-LD |
| `archive-jobs.php` | **Create** — jobs archive grid with glass cards |
| `search.php` | **Create** — branded search results page |
| All 9 page templates + header/footer | Lazy loading audit (add `loading="lazy"` where missing) |

---

## Task 1: Add `kg_webp()` helper and update `kg_img()` in `functions.php`

**Files:**
- Modify: `functions.php` (after `kg_img()` function, around line 218)

- [ ] **Step 1: Add `kg_webp()` after the `kg_img()` function**

Open `functions.php`. After the closing `}` of `kg_img()` (around line 218), add:

```php
/**
 * Returns the .webp version of an image path if that file exists on disk.
 * Falls back to the original path if no .webp counterpart is found.
 * Usage: kg_asset( kg_webp('img/logo.png') )
 */
function kg_webp( $path ) {
    $webp_path = preg_replace( '/\.(png|jpg|jpeg)$/i', '.webp', $path );
    if ( $webp_path === $path ) {
        return $path; // no extension to swap
    }
    $base = function_exists( 'get_template_directory' )
        ? get_template_directory()
        : __DIR__;
    $abs = rtrim( $base, '/' ) . '/' . ltrim( $webp_path, '/' );
    return file_exists( $abs ) ? $webp_path : $path;
}
```

- [ ] **Step 2: Update `kg_img()` to accept a `$loading` parameter and lazy-load by default**

Find the existing `kg_img()` function (around line 210). Replace the entire function with:

```php
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
```

- [ ] **Step 3: Add image sizes inside `kingsgroup_setup()` in `functions.php`**

Find `kingsgroup_setup()` (around line 143). Add these three lines right before the closing `}` of the function:

```php
        // Register named image sizes for WP Media Library uploads
        add_image_size( 'kg-hero',      1920, 800,  true );
        add_image_size( 'kg-card',      600,  400,  true );
        add_image_size( 'kg-thumbnail', 300,  200,  true );
```

- [ ] **Step 4: Verify the file is valid PHP**

```bash
php -l /c/xampp/htdocs/project3/functions.php
```
Expected: `No syntax errors detected`

- [ ] **Step 5: Commit**

```bash
cd /c/xampp/htdocs/project3
git add functions.php
git commit -m "feat: add kg_webp() helper, lazy-load kg_img(), register WP image sizes"
```

---

## Task 2: Add Jobs ACF field group to `inc/acf-fields.php`

**Files:**
- Modify: `inc/acf-fields.php` (append before the final `endif;`)

- [ ] **Step 1: Append the jobs field group at the bottom of `inc/acf-fields.php`**

Find the very last `endif;` in `inc/acf-fields.php` (it closes the `if( function_exists('acf_add_local_field_group') ):` block). Insert this block BEFORE that final `endif;`:

```php
// ==========================================
// 10. JOBS CPT FIELDS
// ==========================================
acf_add_local_field_group(array(
    'key'      => 'group_jobs',
    'title'    => 'Job Details',
    'fields'   => array(
        array(
            'key'           => 'field_job_location',
            'label'         => 'Location',
            'name'          => 'job_location',
            'type'          => 'text',
            'instructions'  => 'e.g. Parañaque, Metro Manila or Remote',
            'placeholder'   => 'Parañaque, Metro Manila',
        ),
        array(
            'key'           => 'field_job_type',
            'label'         => 'Employment Type',
            'name'          => 'job_type',
            'type'          => 'select',
            'choices'       => array(
                'FULL_TIME'  => 'Full-time',
                'PART_TIME'  => 'Part-time',
                'CONTRACTOR' => 'Contract',
                'TEMPORARY'  => 'Temporary',
                'OTHER'      => 'Remote',
            ),
            'default_value' => 'FULL_TIME',
            'allow_null'    => 0,
            'return_format' => 'value',
        ),
        array(
            'key'          => 'field_job_salary_min',
            'label'        => 'Salary Min (PHP/month)',
            'name'         => 'job_salary_min',
            'type'         => 'number',
            'instructions' => 'Minimum monthly salary in PHP. Leave blank to hide salary.',
            'min'          => 0,
        ),
        array(
            'key'          => 'field_job_salary_max',
            'label'        => 'Salary Max (PHP/month)',
            'name'         => 'job_salary_max',
            'type'         => 'number',
            'instructions' => 'Maximum monthly salary in PHP.',
            'min'          => 0,
        ),
        array(
            'key'         => 'field_job_department',
            'label'       => 'Department',
            'name'        => 'job_department',
            'type'        => 'text',
            'placeholder' => 'e.g. Operations, Technology, HR',
        ),
    ),
    'location' => array(
        array(
            array(
                'param'    => 'post_type',
                'operator' => '==',
                'value'    => 'jobs',
            ),
        ),
    ),
    'menu_order'            => 0,
    'position'              => 'normal',
    'style'                 => 'default',
    'label_placement'       => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen'        => array(),
));
```

- [ ] **Step 2: Verify PHP syntax**

```bash
php -l /c/xampp/htdocs/project3/inc/acf-fields.php
```
Expected: `No syntax errors detected`

- [ ] **Step 3: Commit**

```bash
git add inc/acf-fields.php
git commit -m "feat: add ACF field group for jobs CPT (location, type, salary, department)"
```

---

## Task 3: Create `single-jobs.php`

**Files:**
- Create: `single-jobs.php`

- [ ] **Step 1: Create `single-jobs.php` with the full template**

```php
<?php
/* Template Name: Single Job Post (auto-applied by WP hierarchy) */
if ( ! defined( 'ABSPATH' ) ) {
    require_once 'functions.php';
}

// --- Pull ACF job fields ---
$job_location   = kg_get_field( 'job_location',   'Philippines' );
$job_type       = kg_get_field( 'job_type',        'FULL_TIME' );
$job_salary_min = kg_get_field( 'job_salary_min',  '' );
$job_salary_max = kg_get_field( 'job_salary_max',  '' );
$job_department = kg_get_field( 'job_department',  '' );

// Human-readable employment type label
$job_type_labels = [
    'FULL_TIME'  => 'Full-time',
    'PART_TIME'  => 'Part-time',
    'CONTRACTOR' => 'Contract',
    'TEMPORARY'  => 'Temporary',
    'OTHER'      => 'Remote',
];
$job_type_label = $job_type_labels[ $job_type ] ?? $job_type;

// Salary display string
$salary_display = '';
if ( $job_salary_min && $job_salary_max ) {
    $salary_display = '₱' . number_format( $job_salary_min ) . ' – ₱' . number_format( $job_salary_max ) . '/mo';
} elseif ( $job_salary_min ) {
    $salary_display = 'From ₱' . number_format( $job_salary_min ) . '/mo';
}

// --- Page meta ---
$page_title       = get_the_title() . ' | Kings Group Careers';
$page_description = wp_strip_all_tags( get_the_excerpt() ) ?: 'Apply for ' . get_the_title() . ' at Kings Group Cooperative.';

// --- JSON-LD: JobPosting schema for Google for Jobs ---
$page_schema = [
    '@context'       => 'https://schema.org',
    '@type'          => 'JobPosting',
    'title'          => get_the_title(),
    'description'    => wp_strip_all_tags( get_the_content() ),
    'datePosted'     => get_the_date( 'Y-m-d' ),
    'validThrough'   => date( 'Y-m-d', strtotime( '+90 days' ) ),
    'employmentType' => $job_type,
    'jobLocation'    => [
        '@type'   => 'Place',
        'address' => [
            '@type'           => 'PostalAddress',
            'addressLocality' => $job_location,
            'addressCountry'  => 'PH',
        ],
    ],
    'hiringOrganization' => [
        '@id'  => 'https://kingsgroup.com.ph/#organization',
        '@type'=> 'Organization',
        'name' => 'Kings Group Cooperative',
        'sameAs' => 'https://kingsgroup.com.ph/',
    ],
    'applicantLocationRequirements' => [
        '@type' => 'Country',
        'name'  => 'Philippines',
    ],
];

// Add salary to schema only if provided
if ( $job_salary_min ) {
    $page_schema['baseSalary'] = [
        '@type'    => 'MonetaryAmount',
        'currency' => 'PHP',
        'value'    => [
            '@type'    => 'QuantitativeValue',
            'minValue' => (float) $job_salary_min,
            'maxValue' => $job_salary_max ? (float) $job_salary_max : (float) $job_salary_min,
            'unitText' => 'MONTH',
        ],
    ];
}

get_header();
?>

    <!-- Breadcrumb -->
    <nav aria-label="Breadcrumb" style="padding:1rem 0;background:var(--bg-light);border-bottom:1px solid var(--border-color);">
        <div class="container" style="font-size:0.85rem;color:var(--text-muted);">
            <a href="<?php echo esc_url( home_url('/') ); ?>" style="color:var(--main-blue);text-decoration:none;">Home</a>
            <span style="margin:0 0.5rem;">›</span>
            <a href="<?php echo esc_url( home_url('/jobs/') ); ?>" style="color:var(--main-blue);text-decoration:none;">Jobs</a>
            <span style="margin:0 0.5rem;">›</span>
            <span><?php echo esc_html( get_the_title() ); ?></span>
        </div>
    </nav>

    <!-- Job Hero -->
    <section class="page-hero" style="padding:4rem 0 3rem;">
        <div class="container">
            <div style="display:flex;flex-wrap:wrap;gap:0.75rem;margin-bottom:1.25rem;">
                <span style="background:rgba(0,208,156,0.15);color:var(--sec-accent-green);padding:0.3rem 0.9rem;font-size:0.8rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;"><?php echo esc_html( $job_type_label ); ?></span>
                <?php if ( $job_location ) : ?>
                <span style="background:rgba(255,255,255,0.1);color:rgba(255,255,255,0.85);padding:0.3rem 0.9rem;font-size:0.8rem;">📍 <?php echo esc_html( $job_location ); ?></span>
                <?php endif; ?>
                <?php if ( $job_department ) : ?>
                <span style="background:rgba(255,255,255,0.1);color:rgba(255,255,255,0.85);padding:0.3rem 0.9rem;font-size:0.8rem;"><?php echo esc_html( $job_department ); ?></span>
                <?php endif; ?>
            </div>
            <h1 style="font-size:clamp(1.75rem,4vw,2.75rem);font-weight:800;color:#fff;margin-bottom:0.75rem;"><?php the_title(); ?></h1>
            <p style="color:rgba(255,255,255,0.7);font-size:0.9rem;">Posted <?php echo get_the_date(); ?></p>
        </div>
    </section>

    <!-- Job Body -->
    <section style="padding:4rem 0;background:var(--bg-white);">
        <div class="container">
            <div style="display:grid;grid-template-columns:1fr 320px;gap:3rem;align-items:start;">

                <!-- Left: Full Job Description -->
                <div class="post-content" style="line-height:1.85;color:var(--text-body);font-size:1.05rem;">
                    <?php the_content(); ?>
                </div>

                <!-- Right: Sticky Sidebar -->
                <aside style="position:sticky;top:6rem;">
                    <!-- Apply CTA Card -->
                    <div style="background:var(--glass-mid-bg);border:1px solid var(--glass-mid-border);backdrop-filter:var(--glass-mid-blur);padding:2rem;margin-bottom:1.5rem;box-shadow:var(--glass-mid-shadow);">
                        <h3 style="font-size:1.15rem;color:var(--text-dark);margin-bottom:0.5rem;">Interested in this role?</h3>
                        <p style="color:var(--text-muted);font-size:0.9rem;margin-bottom:1.5rem;">Submit your application in under 2 minutes — just your CV and basic details.</p>
                        <a href="<?php echo esc_url( home_url('/careers/#apply') ); ?>" class="btn btn-primary" style="display:block;text-align:center;padding:1rem;">Apply for this Role</a>
                    </div>

                    <!-- Job Details Card -->
                    <div style="background:var(--bg-light);border:1px solid var(--border-color);padding:1.75rem;">
                        <h4 style="font-size:0.8rem;text-transform:uppercase;letter-spacing:0.5px;color:var(--text-muted);margin-bottom:1.25rem;">Job Details</h4>
                        <div style="display:flex;flex-direction:column;gap:1rem;">
                            <div>
                                <div style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.3px;margin-bottom:0.2rem;">Employment Type</div>
                                <div style="font-weight:600;color:var(--text-dark);"><?php echo esc_html( $job_type_label ); ?></div>
                            </div>
                            <?php if ( $job_location ) : ?>
                            <div>
                                <div style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.3px;margin-bottom:0.2rem;">Location</div>
                                <div style="font-weight:600;color:var(--text-dark);"><?php echo esc_html( $job_location ); ?></div>
                            </div>
                            <?php endif; ?>
                            <?php if ( $salary_display ) : ?>
                            <div>
                                <div style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.3px;margin-bottom:0.2rem;">Salary Range</div>
                                <div style="font-weight:600;color:var(--sec-accent-green);"><?php echo esc_html( $salary_display ); ?></div>
                            </div>
                            <?php endif; ?>
                            <?php if ( $job_department ) : ?>
                            <div>
                                <div style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.3px;margin-bottom:0.2rem;">Department</div>
                                <div style="font-weight:600;color:var(--text-dark);"><?php echo esc_html( $job_department ); ?></div>
                            </div>
                            <?php endif; ?>
                            <div>
                                <div style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.3px;margin-bottom:0.2rem;">Date Posted</div>
                                <div style="font-weight:600;color:var(--text-dark);"><?php echo get_the_date(); ?></div>
                            </div>
                        </div>
                    </div>
                </aside>

            </div>
        </div>
    </section>

    <!-- Responsive: collapse sidebar on mobile -->
    <style>
        @media (max-width: 768px) {
            .container > div[style*="grid-template-columns:1fr 320px"] {
                grid-template-columns: 1fr !important;
            }
            .container > div[style*="grid-template-columns:1fr 320px"] aside {
                position: static !important;
            }
        }
    </style>

<?php get_footer(); ?>
```

- [ ] **Step 2: Verify PHP syntax**

```bash
php -l /c/xampp/htdocs/project3/single-jobs.php
```
Expected: `No syntax errors detected`

- [ ] **Step 3: Commit**

```bash
git add single-jobs.php
git commit -m "feat: add single-jobs.php with JobPosting JSON-LD schema for Google for Jobs"
```

---

## Task 4: Create `archive-jobs.php`

**Files:**
- Create: `archive-jobs.php`

- [ ] **Step 1: Create `archive-jobs.php`**

```php
<?php
/* Archive template for the 'jobs' custom post type — URL: /jobs/ */
if ( ! defined( 'ABSPATH' ) ) {
    require_once 'functions.php';
}
$page_title       = 'Open Positions | Kings Group Careers';
$page_description = 'Browse current job openings at Kings Group Cooperative. Full-time, part-time, and contract roles across the Philippines.';

get_header();
?>

    <!-- Hero -->
    <section class="page-hero">
        <div class="container text-center">
            <h1>Open Positions</h1>
            <p>Join a worker-owned cooperative where your growth is everyone's mission.</p>
            <a href="<?php echo esc_url( home_url('/careers/#apply') ); ?>" class="btn btn-primary" style="margin-top:1.5rem;display:inline-block;padding:0.85rem 2rem;">Apply Now — Upload Your CV</a>
        </div>
    </section>

    <!-- Jobs Grid -->
    <section style="padding:5rem 0;background:var(--bg-light);">
        <div class="container">

            <?php if ( have_posts() ) : ?>

                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:1.5rem;">
                    <?php while ( have_posts() ) : the_post();
                        $job_location  = kg_get_field('job_location', 'Philippines');
                        $job_type      = kg_get_field('job_type', 'FULL_TIME');
                        $job_type_labels = [
                            'FULL_TIME'  => 'Full-time',
                            'PART_TIME'  => 'Part-time',
                            'CONTRACTOR' => 'Contract',
                            'TEMPORARY'  => 'Temporary',
                            'OTHER'      => 'Remote',
                        ];
                        $job_type_label = $job_type_labels[$job_type] ?? $job_type;
                        $excerpt = wp_trim_words( get_the_excerpt() ?: get_the_content(), 20, '…' );
                    ?>
                    <div style="background:var(--glass-mid-bg);border:1px solid var(--glass-mid-border);backdrop-filter:var(--glass-mid-blur);padding:2rem;box-shadow:var(--glass-mid-shadow);display:flex;flex-direction:column;transition:var(--transition);">
                        <!-- Type badge -->
                        <span style="display:inline-block;background:rgba(0,208,156,0.12);color:var(--sec-accent-green);padding:0.25rem 0.75rem;font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:1rem;align-self:flex-start;"><?php echo esc_html( $job_type_label ); ?></span>

                        <h3 style="font-size:1.1rem;font-weight:700;color:var(--text-dark);margin-bottom:0.4rem;">
                            <a href="<?php the_permalink(); ?>" style="color:inherit;text-decoration:none;"><?php the_title(); ?></a>
                        </h3>

                        <?php if ( $job_location ) : ?>
                        <p style="font-size:0.85rem;color:var(--text-muted);margin-bottom:0.75rem;">📍 <?php echo esc_html( $job_location ); ?></p>
                        <?php endif; ?>

                        <?php if ( $excerpt ) : ?>
                        <p style="color:var(--text-body);font-size:0.9rem;line-height:1.6;margin-bottom:1.5rem;flex:1;"><?php echo esc_html( $excerpt ); ?></p>
                        <?php endif; ?>

                        <a href="<?php the_permalink(); ?>" class="btn btn-outline" style="align-self:flex-start;padding:0.65rem 1.5rem;font-size:0.9rem;">View Position →</a>
                    </div>
                    <?php endwhile; ?>
                </div>

                <!-- Pagination -->
                <div style="margin-top:3rem;text-align:center;">
                    <?php
                    the_posts_pagination( array(
                        'mid_size'  => 2,
                        'prev_text' => '← Previous',
                        'next_text' => 'Next →',
                    ) );
                    ?>
                </div>

            <?php else : ?>

                <!-- Empty state -->
                <div style="text-align:center;padding:5rem 2rem;">
                    <div style="font-size:4rem;opacity:0.15;margin-bottom:1rem;">💼</div>
                    <h2 class="section-title" style="margin-bottom:1rem;">No Open Positions Right Now</h2>
                    <p style="color:var(--text-muted);font-size:1.05rem;max-width:480px;margin:0 auto 2rem;">We're not actively hiring at the moment, but we'd love to hear from talented people. Send us your CV anyway.</p>
                    <a href="<?php echo esc_url( home_url('/careers/#apply') ); ?>" class="btn btn-primary" style="padding:0.85rem 2rem;">Send Your CV</a>
                </div>

            <?php endif; ?>

        </div>
    </section>

<?php get_footer(); ?>
```

- [ ] **Step 2: Verify PHP syntax**

```bash
php -l /c/xampp/htdocs/project3/archive-jobs.php
```
Expected: `No syntax errors detected`

- [ ] **Step 3: Commit**

```bash
git add archive-jobs.php
git commit -m "feat: add archive-jobs.php with glass card grid and empty state"
```

---

## Task 5: Create `search.php`

**Files:**
- Create: `search.php`

- [ ] **Step 1: Create `search.php`**

```php
<?php
/* Search results template */
if ( ! defined( 'ABSPATH' ) ) {
    require_once 'functions.php';
}
$search_query     = get_search_query();
$page_title       = $search_query ? 'Search: ' . $search_query . ' | Kings Group' : 'Search | Kings Group';
$page_description = 'Search results for "' . esc_attr( $search_query ) . '" on the Kings Group Cooperative website.';

get_header();
?>

    <!-- Search Hero -->
    <section class="page-hero" style="padding:3.5rem 0;">
        <div class="container text-center">
            <p style="font-size:0.85rem;text-transform:uppercase;letter-spacing:1px;color:rgba(255,255,255,0.6);margin-bottom:0.5rem;">Search Results</p>
            <h1 style="font-size:clamp(1.5rem,3.5vw,2.5rem);">
                <?php if ( $search_query ) : ?>
                    Results for: <em style="color:var(--sec-accent-green);"><?php echo esc_html( $search_query ); ?></em>
                <?php else : ?>
                    Search
                <?php endif; ?>
            </h1>
        </div>
    </section>

    <!-- Results -->
    <section style="padding:4rem 0;background:var(--bg-white);">
        <div class="container" style="max-width:800px;">

            <?php if ( have_posts() ) : ?>

                <p style="color:var(--text-muted);font-size:0.9rem;margin-bottom:2rem;">
                    Found <?php echo $wp_query->found_posts; ?> result<?php echo $wp_query->found_posts !== 1 ? 's' : ''; ?>
                </p>

                <div style="display:flex;flex-direction:column;gap:1.5rem;">
                    <?php while ( have_posts() ) : the_post(); ?>
                    <div style="border-bottom:1px solid var(--border-color);padding-bottom:1.5rem;">
                        <!-- Post type badge -->
                        <span style="display:inline-block;background:var(--bg-light);border:1px solid var(--border-color);color:var(--text-muted);padding:0.2rem 0.65rem;font-size:0.72rem;font-weight:600;text-transform:uppercase;letter-spacing:0.4px;margin-bottom:0.5rem;"><?php echo esc_html( get_post_type() ); ?></span>

                        <h2 style="font-size:1.15rem;font-weight:700;margin-bottom:0.4rem;">
                            <a href="<?php the_permalink(); ?>" style="color:var(--main-blue);text-decoration:none;"><?php the_title(); ?></a>
                        </h2>

                        <p style="font-size:0.8rem;color:var(--text-muted);margin-bottom:0.5rem;"><?php echo get_the_date(); ?></p>

                        <?php if ( has_excerpt() || get_the_content() ) : ?>
                        <p style="color:var(--text-body);font-size:0.95rem;line-height:1.65;"><?php echo wp_trim_words( get_the_excerpt() ?: get_the_content(), 25, '…' ); ?></p>
                        <?php endif; ?>
                    </div>
                    <?php endwhile; ?>
                </div>

                <!-- Pagination -->
                <div style="margin-top:3rem;text-align:center;">
                    <?php
                    the_posts_pagination( array(
                        'mid_size'  => 2,
                        'prev_text' => '← Previous',
                        'next_text' => 'Next →',
                    ) );
                    ?>
                </div>

            <?php else : ?>

                <!-- No results state -->
                <div style="text-align:center;padding:4rem 0;">
                    <div style="font-size:4rem;opacity:0.12;margin-bottom:1rem;">🔍</div>
                    <h2 class="section-title" style="margin-bottom:1rem;">Nothing Found</h2>
                    <p style="color:var(--text-muted);font-size:1.05rem;max-width:480px;margin:0 auto 2rem;">
                        No results for <strong>"<?php echo esc_html( $search_query ); ?>"</strong>. Try a different keyword or browse our pages below.
                    </p>
                    <a href="<?php echo esc_url( home_url('/') ); ?>" class="btn btn-primary" style="padding:0.85rem 2rem;">Back to Homepage</a>
                </div>

            <?php endif; ?>

        </div>
    </section>

<?php get_footer(); ?>
```

- [ ] **Step 2: Verify PHP syntax**

```bash
php -l /c/xampp/htdocs/project3/search.php
```
Expected: `No syntax errors detected`

- [ ] **Step 3: Commit**

```bash
git add search.php
git commit -m "feat: add search.php with results loop and no-results state"
```

---

## Task 6: Dynamic nav menus — `header.php` and CSS

**Files:**
- Modify: `header.php`
- Modify: `style.css`

The header has two nav sections: `.nav-section.client` (left, business-focused) and `.nav-section.applicant` (right, talent-focused). The mega-menu dropdown under "About" stays hardcoded. Only the surrounding plain `<a>` links become dynamic.

- [ ] **Step 1: Replace client-side plain nav links in `header.php`**

Find this block in `header.php` (the `.nav-section.client` div). It currently contains:
```php
<a href="<?php echo esc_url(home_url('/')); ?>" class="nav-link">Home</a>
<!-- Dropdown Menu (mega menu — keep as-is) -->
<div class="dropdown">...</div>
<a href="<?php echo esc_url(home_url('/quote/')); ?>" class="nav-link nav-btn primary">Get a Quote</a>
```

Replace the `Home` anchor and the `Get a Quote` anchor with `wp_nav_menu()` calls. The dropdown stays untouched. The updated `.nav-section.client` content should be:

```php
<!-- Left Side (Client Focus) -->
<div class="nav-section client">
    <?php
    // Dynamic client nav — falls back to nothing if no menu assigned yet
    wp_nav_menu( array(
        'theme_location' => 'menu-1',
        'container'      => false,
        'depth'          => 1,
        'fallback_cb'    => false,
        'items_wrap'     => '<ul class="nav-menu-list">%3$s</ul>',
    ) );
    ?>

    <!-- Dropdown Menu: About (stays hardcoded — custom mega menu design) -->
    <div class="dropdown">
        <button class="nav-link dropdown-toggle" aria-haspopup="true" aria-expanded="false">
            About
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M7 10L12 15L17 10"></path>
            </svg>
        </button>
        <div class="dropdown-menu mega-menu">
            <?php /* ... existing mega menu inner content — DO NOT CHANGE ... */ ?>
        </div>
    </div>
</div>
```

> **Note:** Keep the entire contents of `<div class="dropdown-menu mega-menu">...</div>` exactly as they currently are — only replace the surrounding `<a>` Home and `<a>` Get a Quote tags with `wp_nav_menu()`.

- [ ] **Step 2: Replace applicant-side nav links in `header.php`**

Find the `.nav-section.applicant` div:
```php
<div class="nav-section applicant">
    <a href="<?php echo esc_url(home_url('/careers/')); ?>" class="nav-link">Find a Job</a>
    <a href="https://zckings.azurewebsites.net/" class="nav-link" target="_blank" rel="noopener">Member Portal</a>
    <a href="https://zckings.azurewebsites.net/" class="nav-link nav-btn" target="_blank" rel="noopener">Log In</a>
</div>
```

Replace with:
```php
<!-- Right Side (Applicant Focus) -->
<div class="nav-section applicant">
    <?php
    wp_nav_menu( array(
        'theme_location' => 'menu-2',
        'container'      => false,
        'depth'          => 1,
        'fallback_cb'    => false,
        'items_wrap'     => '<ul class="nav-menu-list">%3$s</ul>',
    ) );
    ?>
</div>
```

- [ ] **Step 3: Add nav-menu-list CSS reset to `style.css`**

Find the nav/header CSS section in `style.css`. Add these rules (they reset the `<ul><li>` structure output by `wp_nav_menu()` so it renders exactly like the previous bare `<a>` tags):

```css
/* wp_nav_menu() list reset — makes <ul><li><a> render like bare <a class="nav-link"> */
.nav-section ul.nav-menu-list {
    list-style: none;
    margin: 0;
    padding: 0;
    display: contents; /* items flow inline with surrounding flex siblings */
}
.nav-section ul.nav-menu-list li {
    display: contents;
}
.nav-section ul.nav-menu-list li a {
    /* inherit nav-link appearance — matches existing .nav-link rules */
    font-family: var(--font-body);
    font-size: 0.9rem;
    font-weight: 500;
    color: rgba(255, 255, 255, 0.9);
    text-decoration: none;
    padding: 0.4rem 0.1rem;
    transition: var(--transition);
    white-space: nowrap;
}
.nav-section ul.nav-menu-list li a:hover,
.nav-section ul.nav-menu-list li.current-menu-item > a {
    color: #ffffff;
}
/* Primary button style for "Get a Quote" — WP adds class via menu item CSS Classes field */
.nav-section ul.nav-menu-list li.menu-btn-primary > a {
    background: var(--sec-accent-green);
    color: var(--main-blue);
    padding: 0.5rem 1.25rem;
    font-weight: 700;
}
```

- [ ] **Step 4: Verify PHP syntax**

```bash
php -l /c/xampp/htdocs/project3/header.php
```
Expected: `No syntax errors detected`

- [ ] **Step 5: Commit**

```bash
git add header.php style.css
git commit -m "feat: replace hardcoded header nav links with wp_nav_menu() (menu-1, menu-2)"
```

---

## Task 7: Dynamic footer menu — `footer.php`

**Files:**
- Modify: `footer.php`

The footer has three link columns: Company, Members, Legal. The `footer` menu location is already registered. We replace the hardcoded `<a>` tags in the footer columns with a single `wp_nav_menu()` call, and add a CSS reset.

- [ ] **Step 1: Replace footer link columns with `wp_nav_menu()`**

Find the `footer-links-col` divs in `footer.php`. Replace the three `<div class="footer-links-col">` blocks (Company, Members, Legal) with:

```php
<?php
// Dynamic footer links — client assigns links from WP Admin → Appearance → Menus
if ( function_exists('wp_nav_menu') && has_nav_menu('footer') ) :
    wp_nav_menu( array(
        'theme_location' => 'footer',
        'container'      => false,
        'depth'          => 2,
        'fallback_cb'    => false,
        'items_wrap'     => '<ul class="footer-nav-list">%3$s</ul>',
    ) );
else : ?>
    <!-- Fallback footer links if no menu is assigned in WP Admin -->
    <div class="footer-links-col">
        <h4>Company</h4>
        <a href="<?php echo esc_url(home_url('/story/')); ?>">Our Story</a>
        <a href="<?php echo esc_url(home_url('/careers/')); ?>">Careers</a>
        <a href="<?php echo esc_url(home_url('/contact/')); ?>">Contact Us</a>
    </div>
    <div class="footer-links-col">
        <h4>Members</h4>
        <a href="https://zckings.azurewebsites.net/" target="_blank" rel="noopener">Member Portal</a>
        <a href="https://kingslending.timefree.ph/" target="_blank" rel="noopener">Kings Lending</a>
        <a href="<?php echo esc_url(home_url('/benefits/')); ?>">Benefits</a>
    </div>
    <div class="footer-links-col">
        <h4>Legal</h4>
        <a href="#">Terms of Service</a>
        <a href="#">Privacy Policy</a>
        <a href="#">Trust &amp; Safety</a>
    </div>
<?php endif; ?>
```

- [ ] **Step 2: Add footer-nav-list CSS to `style.css`**

Add these rules after the nav-menu-list rules added in Task 6:

```css
/* Footer wp_nav_menu() list reset */
ul.footer-nav-list {
    list-style: none;
    margin: 0;
    padding: 0;
    display: contents;
}
ul.footer-nav-list > li {
    /* top-level items become column headers */
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}
ul.footer-nav-list > li > a {
    font-size: 0.95rem;
    font-weight: 700;
    color: rgba(255, 255, 255, 0.9);
    text-decoration: none;
    text-transform: capitalize;
    margin-bottom: 0.25rem;
}
ul.footer-nav-list > li > ul {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}
ul.footer-nav-list > li > ul > li > a {
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.65);
    text-decoration: none;
    transition: color 0.2s;
}
ul.footer-nav-list > li > ul > li > a:hover {
    color: rgba(255, 255, 255, 0.95);
}
```

- [ ] **Step 3: Verify PHP syntax**

```bash
php -l /c/xampp/htdocs/project3/footer.php
```
Expected: `No syntax errors detected`

- [ ] **Step 4: Commit**

```bash
git add footer.php style.css
git commit -m "feat: replace hardcoded footer links with wp_nav_menu() (footer location)"
```

---

## Task 8: Lazy loading audit — all templates

**Files:**
- Modify: `front-page.php`, `story.php`, `service-labor.php`, `service-kit.php`, `network.php`, `careers.php`, `benefits.php`, `quote.php`, `contact.php`

The rule: every `<img>` below the fold gets `loading="lazy"`. The very first background/hero image on each page (LCP element) must NOT be lazy-loaded — it already loads via CSS `background-image` (not an `<img>` tag) on most pages, so this primarily affects content images further down the page.

- [ ] **Step 1: Add `loading="lazy"` to all content images across templates**

Run this grep to find `<img` tags without a `loading=` attribute:

```bash
grep -rn "<img " /c/xampp/htdocs/project3/ --include="*.php" | grep -v 'loading=' | grep -v "node_modules\|data-populator\|acf-fields\|check_\|debug_\|fix_\|force_\|update_"
```

For each result, add `loading="lazy"` to the `<img>` tag. Example — in `story.php` line 228:
```html
<!-- Before -->
<img src="..." alt="...">
<!-- After -->
<img src="..." alt="..." loading="lazy">
```

The two already-existing lazy images (`contact.php:223`, `story.php:228`) are already correct — skip them.

- [ ] **Step 2: Verify no img tags are missing loading attribute**

```bash
grep -rn "<img " /c/xampp/htdocs/project3/ --include="*.php" | grep -v 'loading=' | grep -v "node_modules\|data-populator\|acf-fields\|check_\|debug_\|fix_\|force_\|update_\|single-jobs\|archive-jobs\|search\.php"
```
Expected: no output (all `<img>` tags now have `loading=`)

- [ ] **Step 3: Commit**

```bash
git add front-page.php story.php service-labor.php service-kit.php network.php careers.php benefits.php quote.php contact.php header.php footer.php
git commit -m "perf: add loading=lazy to all below-fold img tags across all templates"
```

---

## Task 9: Update `PROJECT_CONTEXT.md` and Obsidian vault

**Files:**
- Modify: `PROJECT_CONTEXT.md`
- Modify: `C:\Users\LEGION\OneDrive\Documents\godmode-brain\godmode-brain\_ACTIVE_SESSION.md`
- Modify: `C:\Users\LEGION\OneDrive\Documents\godmode-brain\godmode-brain\projects\project3\CHANGELOG.md`

- [ ] **Step 1: Update `PROJECT_CONTEXT.md`**

Update `CURRENT STATE` phase to `Phase 4 & 5 complete`. Move Phase 4 and Phase 5 tasks to `Done`. Set `Up Next` to Phase 6 (Forms & Production Readiness).

- [ ] **Step 2: Update Obsidian `_ACTIVE_SESSION.md`**

Update status to reflect Phase 4 & 5 complete. Next action = Phase 6 decision.

- [ ] **Step 3: Update Obsidian `CHANGELOG.md`**

Add `0.7.0` entry: Phase 4 — dynamic nav menus, single-jobs.php, archive-jobs.php, search.php. Add `0.8.0` entry: Phase 5 — kg_webp(), lazy loading audit, WP image sizes.

- [ ] **Step 4: Final commit**

```bash
git add PROJECT_CONTEXT.md
git commit -m "docs: update PROJECT_CONTEXT.md — Phase 4 & 5 complete"
```
