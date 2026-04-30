# Phase 4 & 5 Design Spec — Kings Group Theme
**Date:** 2026-04-30
**Project:** Kings Group WordPress Theme (`C:\xampp\htdocs\project3`)
**Stack:** PHP, WordPress, ACF, Vanilla CSS/JS
**Status:** Approved — ready for implementation

---

## Phase 4: WordPress Template Hierarchy

### Goal
Convert hardcoded nav links to WP-managed menus, add missing template files for the `jobs` CPT, and add a search results page.

---

### 4A. Dynamic Nav Menus

**Files:** `header.php`, `footer.php`

Three menus are already registered in `functions.php`:
| Location Key | Label | Where Used |
|---|---|---|
| `menu-1` | Primary Client Menu | Left side of header |
| `menu-2` | Primary Applicant Menu | Right side of header |
| `footer` | Footer Menu | Footer nav links |

**What changes:**
- Replace hardcoded `<a>` tags in the left nav section (`.nav-section.client`) with `wp_nav_menu(['theme_location' => 'menu-1', ...])`
- Replace hardcoded `<a>` tags in the right nav section (`.nav-section.applicant`) with `wp_nav_menu(['theme_location' => 'menu-2', ...])`
- Replace hardcoded footer links with `wp_nav_menu(['theme_location' => 'footer', ...])`

**What does NOT change:**
- The mega menu dropdown under "About" stays hardcoded — it is a custom visual design that `wp_nav_menu()` cannot replicate
- All CSS classes, layout, logo position, and visual design remain identical
- Fallback: if no menu is assigned in WP Admin, the hardcoded links show as the default via `wp_nav_menu()` fallback callback

**wp_nav_menu() config:**
```php
wp_nav_menu([
    'theme_location'  => 'menu-1',
    'menu_class'      => 'nav-links',
    'container'       => false,
    'depth'           => 1,
    'fallback_cb'     => false,
    'items_wrap'      => '<ul class="nav-menu-list">%3$s</ul>',
]);
```
> The current header uses bare `<a class="nav-link">` tags. `wp_nav_menu()` wraps each link in `<li>`. We add a minimal CSS rule to `.nav-section ul.nav-menu-list` and `.nav-section ul.nav-menu-list li` (reset list styles, display inline/flex) so the visual output is identical to the current hardcoded links. No existing CSS is removed.

---

### 4B. `single-jobs.php` — Individual Job Post Page

**URL pattern:** `/jobs/{post-slug}/`
**Triggered by:** WordPress template hierarchy when a `jobs` CPT single post is viewed

**ACF fields to register (in `inc/acf-fields.php`):**
| Field Name | Type | Label |
|---|---|---|
| `job_location` | Text | Location (e.g. "Parañaque, Metro Manila") |
| `job_type` | Select | Employment Type (Full-time, Part-time, Contract, Remote) |
| `job_salary_min` | Number | Salary Min (PHP/month) |
| `job_salary_max` | Number | Salary Max (PHP/month) |
| `job_department` | Text | Department |

**Page layout (top to bottom):**
1. **Hero** — branded dark blue hero with job title (`the_title()`), location badge, job type badge, posted date
2. **Job Body** — two-column on desktop: left = `the_content()` (full description), right = sticky sidebar with Apply CTA + key details (type, location, salary, department)
3. **Apply CTA** — "Apply for this Role" button → links to `home_url('/careers/#apply')`
4. **Breadcrumb** — Home → Jobs → {Job Title} (HTML only, no plugin)

**JSON-LD `JobPosting` schema (injected via `$page_schema`):**
```php
$page_schema = [
    '@context'          => 'https://schema.org',
    '@type'             => 'JobPosting',
    'title'             => get_the_title(),
    'description'       => get_the_content(),
    'datePosted'        => get_the_date('Y-m-d'),
    'validThrough'      => date('Y-m-d', strtotime('+90 days')),
    'employmentType'    => $job_type,     // from ACF
    'jobLocation'       => [...],         // PostalAddress
    'baseSalary'        => [...],         // MonetaryAmount
    'hiringOrganization'=> ['@id' => 'https://kingsgroup.com.ph/#organization'],
];
```

---

### 4C. `archive-jobs.php` — Jobs Archive Page

**URL:** `/jobs/`
**Triggered by:** WordPress template hierarchy for `jobs` CPT archive

**Layout:**
1. **Section header** — "Open Positions" title + subtitle
2. **Job cards grid** — `repeat(auto-fill, minmax(300px, 1fr))` CSS grid
   - Each card: job title, type badge, location, excerpt (first 120 chars), "View Position" button
   - Uses existing glass card style (`var(--glass-mid-bg)` etc.)
3. **Empty state** — "No open positions right now. Check back soon." message if no posts
4. **Pagination** — `the_posts_pagination()` if more than 10 jobs

---

### 4D. `search.php` — Search Results Page

**URL:** `/?s={query}` or `/search/{query}/`

**Layout:**
1. **Hero** — "Search Results for: {query}" heading
2. **Results loop** — post title (linked), excerpt, post type badge, date
3. **No results state** — "Nothing found for '{query}'" + link back to homepage
4. **Pagination** — `the_posts_pagination()`

---

## Phase 5: Performance & Image Optimization

### Goal
Serve WebP images wherever available, lazy-load all below-fold images, register WP image sizes for Media Library uploads, and update `kg_img()` to lazy-load by default.

---

### 5A. `kg_webp()` Helper in `functions.php`

```php
function kg_webp( $path ) {
    // Swap .png / .jpg / .jpeg extension to .webp if the file exists on disk
    $webp_path = preg_replace('/\.(png|jpg|jpeg)$/i', '.webp', $path);
    if ( $webp_path !== $path ) {
        // Build absolute filesystem path to check existence
        $base = function_exists('get_template_directory')
            ? get_template_directory()
            : __DIR__;
        $abs = rtrim($base, '/') . '/' . ltrim($webp_path, '/');
        if ( file_exists($abs) ) {
            return $webp_path;
        }
    }
    return $path;
}
```

**Usage pattern:** Wrap every `kg_asset('img/something.png')` call:
```php
kg_asset( kg_webp('img/adidas.png') )
// → serves adidas.webp if it exists, adidas.png as fallback
```

**Scope:** All templates that reference `/img/` or `/assets/` files with `.png`/`.jpg`.

---

### 5B. `loading="lazy"` Audit

**Rule:**
- All `<img>` tags → add `loading="lazy"`
- **Exception:** The first hero image on each page (above the fold) → `loading="eager"` or omit the attribute — this is the LCP element and must not be deferred

**Files to audit:** all 9 page templates + `header.php`, `footer.php`, `single-jobs.php`, `archive-jobs.php`

---

### 5C. WordPress Image Sizes in `functions.php`

```php
add_image_size( 'kg-hero',      1920, 800,  true ); // Full-width hero banners
add_image_size( 'kg-card',      600,  400,  true ); // Job cards, service cards
add_image_size( 'kg-thumbnail', 300,  200,  true ); // Grid thumbnails
```

Register inside `kingsgroup_setup()`. Used by `single-jobs.php` featured image and any future WP Media Library images.

---

### 5D. Update `kg_img()` Helper

Add `loading="lazy"` as a default parameter. Existing calls with no `$loading` argument automatically get lazy loading. Pass `'eager'` explicitly for hero images.

```php
function kg_img($url, $alt = 'Image', $class = '', $style = '', $loading = 'lazy') {
    // ... existing logic ...
    return '<img src="..." loading="' . esc_attr($loading) . '" ...>';
}
```

---

## Files Modified / Created

| File | Action |
|---|---|
| `header.php` | Replace hardcoded nav links with `wp_nav_menu()` |
| `footer.php` | Replace hardcoded footer links with `wp_nav_menu()` |
| `single-jobs.php` | **Create** — individual job post template |
| `archive-jobs.php` | **Create** — jobs archive listing template |
| `search.php` | **Create** — search results template |
| `functions.php` | Add `kg_webp()`, update `kg_img()`, add image sizes |
| `inc/acf-fields.php` | Add job ACF field group |
| All 9 templates | WebP swap + lazy loading audit |

---

## Constraints

- Visual design must not change — only functionality and performance
- Mega menu dropdown stays hardcoded
- `kg_webp()` is filesystem-based (no build step, no npm)
- No new plugins required
- `single-jobs.php` apply button always links to `/careers/#apply` (no inline form)
