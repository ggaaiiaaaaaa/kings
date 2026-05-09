# Community & News Integration Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Implement separate News and Community pages and update the navigation system to include them.

**Architecture:** Create `page-community.php` for the impact-focused page and utilize `index.php` as the article-focused News archive. Use WordPress Categories ("News", "Community") to filter content and ACF to manage static community program details.

**Tech Stack:** WordPress (PHP), ACF (Free), Liquid Glass UI (CSS/JS).

---

### Task 1: Navigation System Updates

**Files:**
- Modify: `header.php`
- Modify: `functions.php`

- [ ] **Step 1: Update About Mega Menu in header.php**
Replace the hardcoded About menu links with the new structure.
```php
<!-- header.php around line 70 -->
<div class="mega-links-col">
    <h4 style="margin-bottom: 0.75rem; color: var(--main-blue); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; padding-left: 1rem;">Company</h4>
    <a href="<?php echo esc_url(home_url('/story/')); ?>" class="mega-feature-link">
        <div class="feature-link-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg></div>
        <div class="feature-link-text"><span class="title">Our Story</span><span class="desc">A legacy of empowering workers since 1999.</span></div>
    </a>
    <a href="<?php echo esc_url(home_url('/story/#vision-mission')); ?>" class="mega-feature-link">
        <div class="feature-link-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg></div>
        <div class="feature-link-text"><span class="title">Mission & Values</span><span class="desc">Discover our cooperative advantage and vision.</span></div>
    </a>
    <!-- NEW LINKS -->
    <a href="<?php echo esc_url(home_url('/news/')); ?>" class="mega-feature-link">
        <div class="feature-link-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 20H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h10l4 4v10a2 2 0 0 1-2 2z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg></div>
        <div class="feature-link-text"><span class="title">News</span><span class="desc">Latest updates and corporate milestones.</span></div>
    </a>
    <a href="<?php echo esc_url(home_url('/community/')); ?>" class="mega-feature-link">
        <div class="feature-link-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg></div>
        <div class="feature-link-text"><span class="title">Community</span><span class="desc">Uplifting lives through education and livelihood.</span></div>
    </a>
</div>
```

- [ ] **Step 2: Update Footer Menu in functions.php**
Update `kg_create_default_menus` to include News and Community.
```php
// functions.php in kg_create_default_menus()
$footer_items = array(
    array( 'title' => 'Our Story',    'url' => home_url('/story/') ),
    array( 'title' => 'News',         'url' => home_url('/news/') ),
    array( 'title' => 'Community',    'url' => home_url('/community/') ),
    array( 'title' => 'Careers',      'url' => home_url('/careers/') ),
    // ... rest of items
);
```

- [ ] **Step 3: Commit**
```bash
git add header.php functions.php
git commit -m "feat: update navigation to include News and Community"
```

---

### Task 2: ACF Fields for Community Page

**Files:**
- Modify: `inc/acf-fields.php`
- Modify: `inc/data-populator.php`

- [ ] **Step 1: Register Community Page ACF group**
Add the field group for `page-community.php`.
```php
// inc/acf-fields.php
if ( function_exists('acf_add_local_field_group') ) {
    acf_add_local_field_group(array(
        'key' => 'group_community_page',
        'title' => 'Community Page Fields',
        'fields' => array(
            array('key' => 'field_comm_hero_title', 'label' => 'Hero Title', 'name' => 'comm_hero_title', 'type' => 'text', 'default_value' => 'Our Commitment to Community'),
            array('key' => 'field_comm_welcome_text', 'label' => 'Welcome Text', 'name' => 'comm_welcome_text', 'type' => 'textarea', 'default_value' => 'Welcome to The KINGS — Find great opportunities now!'),
            array('key' => 'field_comm_queens_title', 'label' => 'Queens Section Title', 'name' => 'comm_queens_title', 'type' => 'text', 'default_value' => 'Queens of Kings Group'),
            array('key' => 'field_comm_scholar_desc', 'label' => 'Scholarship Description', 'name' => 'comm_scholar_desc', 'type' => 'textarea', 'default_value' => 'The Kings Group supports the aspirations of its members and their dependents by providing scholarships to ensure sustainable futures.'),
            array('key' => 'field_comm_culinary_desc', 'label' => 'Culinary School Description', 'name' => 'comm_culinary_desc', 'type' => 'textarea', 'default_value' => 'A TESDA-accredited and certified institution built to provide sustainable education and livelihood programs.'),
        ),
        'location' => array(array(array('param' => 'page_template', 'operator' => '==', 'value' => 'page-community.php'))),
    ));
}
```

- [ ] **Step 2: Add auto-population logic**
Ensure the Community page is created and populated on activation.
```php
// inc/data-populator.php
// Add to kg_auto_populate_site()
$comm_id = kg_get_page_by_template('page-community.php');
if ( ! $comm_id ) {
    $comm_id = wp_insert_post(array('post_title' => 'Community', 'post_type' => 'page', 'post_status' => 'publish'));
    update_post_meta($comm_id, '_wp_page_template', 'page-community.php');
}
update_post_meta($comm_id, 'comm_hero_title', 'Our Commitment to Community');
// ... populate other fields
```

- [ ] **Step 3: Commit**
```bash
git add inc/acf-fields.php inc/data-populator.php
git commit -m "feat: add ACF fields for Community page"
```

---

### Task 3: Create page-community.php Template

**Files:**
- Create: `page-community.php`

- [ ] **Step 1: Implement template structure**
Create the file with Liquid Glass styling and content pulled from the live site.
```php
<?php /* Template Name: Community */ ?>
<?php get_header(); ?>
<section class="page-hero community-bg">
    <div class="container text-center">
        <h1><?php echo esc_html(kg_get_field('comm_hero_title', 'Our Commitment to Community')); ?></h1>
    </div>
</section>
<section class="section">
    <div class="container">
        <h2 class="section-title"><?php echo esc_html(kg_get_field('comm_welcome_text')); ?></h2>
        <!-- Queens Section -->
        <div class="queens-section glass-card">...</div>
        <!-- Programs Grid -->
        <div class="programs-grid">
            <div class="program-card">
                <h3>Scholarship Program</h3>
                <p><?php echo esc_html(kg_get_field('comm_scholar_desc')); ?></p>
            </div>
            <div class="program-card">
                <h3>Home Culinary School</h3>
                <p><?php echo esc_html(kg_get_field('comm_culinary_desc')); ?></p>
                <ul><li>Culinary Arts</li><li>Cookery NC II</li>...</ul>
                <a href="#" class="btn btn-primary">APPLY NOW</a>
            </div>
        </div>
    </div>
</section>
<?php get_footer(); ?>
```

- [ ] **Step 2: Commit**
```bash
git add page-community.php
git commit -m "feat: implement page-community.php template"
```

---

### Task 4: Refine News Archive (index.php)

**Files:**
- Modify: `index.php`

- [ ] **Step 1: Implement responsive article grid**
Ensure `index.php` handles the "News" category loop with the correct card design.
```php
<!-- index.php loop -->
<div class="news-grid container">
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <article class="news-card glass-card">
            <?php the_post_thumbnail('kg-card'); ?>
            <div class="news-meta">
                <span class="news-category"><?php the_category(', '); ?></span>
                <span class="news-date"><?php echo get_the_date(); ?></span>
            </div>
            <h3><?php the_title(); ?></h3>
            <p><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
            <a href="<?php the_permalink(); ?>" class="read-more">Read More &rarr;</a>
        </article>
    <?php endwhile; endif; ?>
</div>
```

- [ ] **Step 2: Commit**
```bash
git add index.php
git commit -m "feat: refine news archive grid in index.php"
```
