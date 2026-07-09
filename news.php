<?php
/* Template Name: News */
if ( ! defined( 'ABSPATH' ) ) {
    require_once 'functions.php';
}
$page_title       = 'News & Updates | Kings Group Cooperative';
$page_description = 'Stay updated with the latest corporate news, events, and community milestones from Kings Group Cooperative.';

// JSON-LD: CollectionPage schema
$page_schema = [
    '@context'    => 'https://schema.org',
    '@type'       => 'CollectionPage',
    '@id'         => 'https://kingsgroup.com.ph/news/#webpage',
    'url'         => 'https://kingsgroup.com.ph/news/',
    'name'        => 'News & Updates | Kings Group Cooperative',
    'description' => 'Stay updated with the latest corporate news, events, and community milestones from Kings Group Cooperative.',
    'isPartOf'    => [ '@id' => 'https://kingsgroup.com.ph/#website' ],
];

$page_id = get_the_ID();
$page_hero_bg = kg_get_field('news_bg', kg_asset('img/community/hero-community.png'), $page_id);
$hero_title = kg_get_field('news_headline', 'News & Updates', $page_id);
$hero_subtitle = kg_get_field('news_desc', 'Corporate insights, upcoming events, and stories of cooperative success.', $page_id);

get_header();
?>

<!-- Hero Section -->
<section class="page-hero" style="background-image: linear-gradient(rgba(10, 37, 64, 0.72), rgba(10, 37, 64, 0.72)), url('<?php echo esc_url($page_hero_bg); ?>'); min-height: unset; padding: 5rem 2rem 4rem;">
    <div class="container text-center">
        <h1 class="animate-on-scroll" style="font-size: clamp(2rem, 6vw, 3.5rem); font-weight: 900; letter-spacing: -0.02em; line-height: 1.05; color: #fff; margin-bottom: 1.25rem;"><?php echo esc_html($hero_title); ?></h1>
        <p class="animate-on-scroll" style="max-width: 600px; margin: 0 auto; font-weight: 400; font-size: 1.5rem; color: rgba(255,255,255,0.82); line-height: 1.7;"><?php echo esc_html($hero_subtitle); ?></p>
    </div>
</section>

<?php
// Query posts (Newest first)
$query_args = array(
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'posts_per_page' => 10,
    'paged'          => get_query_var('paged') ? get_query_var('paged') : 1,
);
$news_query = new WP_Query( $query_args );

// Featured post (latest, only on page 1)
$featured_post = null;
if ( $news_query->have_posts() && get_query_var('paged', 1) <= 1 ) {
    $news_query->the_post();
    $featured_post = get_post();
    wp_reset_postdata();
}
?>

<main id="main-content" class="news-page-root">
    <div class="container news-page-container">
        
        <?php if ( $featured_post && get_query_var('paged', 1) <= 1 ) : 
            // Calculate reading time for the featured post
            $word_count = str_word_count( strip_tags( $featured_post->post_content ) );
            $reading_time = max( 1, ceil( $word_count / 200 ) );
            $featured_img = get_the_post_thumbnail_url($featured_post, 'large') ?: get_post_meta($featured_post->ID, '_kg_post_image', true);
        ?>
        <!-- Massive Featured Showcase (Inspiration from Current Affairs) -->
        <section class="news-featured-showcase animate-on-scroll">
            <a href="<?php echo get_permalink($featured_post); ?>" class="featured-link-wrapper">
                <div class="featured-image-col">
                    <?php if ( !empty($featured_img) ) : ?>
                        <img src="<?php echo esc_url($featured_img); ?>" alt="<?php echo esc_attr(get_the_title($featured_post)); ?>" loading="eager">
                    <?php else : ?>
                        <div class="featured-img-fallback">📰</div>
                    <?php endif; ?>
                </div>
                <div class="featured-details-col">
                    <span class="featured-tag"><?php echo esc_html(kg_get_field('news_featured_tag', 'Latest News', $page_id)); ?></span>
                    <h1 class="featured-title"><?php echo esc_html(get_the_title($featured_post)); ?></h1>
                    <p class="featured-excerpt"><?php echo wp_trim_words($featured_post->post_excerpt ?: $featured_post->post_content, 35, '…'); ?></p>
                    <div class="featured-meta">
                        <span class="meta-date"><?php echo get_the_date('F j, Y', $featured_post); ?></span>
                        <span class="meta-separator">•</span>
                        <span class="meta-read-time"><?php echo $reading_time; ?> <?php echo esc_html(kg_get_field('news_read_time_label', 'min read', $page_id)); ?></span>
                    </div>
                </div>
            </a>
        </section>
        
        <hr class="section-divider">
        <?php endif; ?>

        <!-- Older Stories Section -->
        <section class="older-news-section">
            <?php if ( $news_query->have_posts() ) : ?>
                <h2 class="section-subtitle"><?php echo esc_html(kg_get_field('news_more_title', 'More News', $page_id)); ?></h2>
                
                <div class="news-cards-grid">
                    <?php
                    // Skip the featured post in loop
                    $skip_first = ($featured_post && get_query_var('paged', 1) <= 1);
                    $skipped = false;
                    while ( $news_query->have_posts() ) : $news_query->the_post();
                        if ( $skip_first && !$skipped && get_the_ID() === $featured_post->ID ) {
                            $skipped = true;
                            continue;
                        }
                        
                        // Calculate reading time
                        $word_count = str_word_count( strip_tags( get_the_content() ) );
                        $reading_time = max( 1, ceil( $word_count / 200 ) );
                        $post_img = get_the_post_thumbnail_url(get_the_ID(), 'medium_large') ?: get_post_meta(get_the_ID(), '_kg_post_image', true);
                    ?>
                    <article class="news-list-item animate-on-scroll">
                        <a href="<?php the_permalink(); ?>" class="news-item-link">
                            <?php if ( !empty($post_img) ) : ?>
                                <div class="news-item-img-wrapper">
                                    <img src="<?php echo esc_url($post_img); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
                                </div>
                            <?php endif; ?>
                            
                            <div class="news-item-content">
                                <h3 class="news-item-title"><?php the_title(); ?></h3>
                                <p class="news-item-excerpt"><?php echo wp_trim_words(get_the_excerpt() ?: get_the_content(), 22, '…'); ?></p>
                                <div class="news-item-meta">
                                    <span class="meta-date"><?php echo get_the_date('F j, Y'); ?></span>
                                    <span class="meta-separator">•</span>
                                    <span class="meta-read-time"><?php echo $reading_time; ?> <?php echo esc_html(kg_get_field('news_read_time_label', 'min read', $page_id)); ?></span>
                                </div>
                            </div>
                        </a>
                    </article>
                    <?php endwhile; ?>
                </div>

                <!-- Pagination -->
                <div class="news-pagination animate-on-scroll">
                    <?php
                    echo paginate_links( array(
                        'total'     => $news_query->max_num_pages,
                        'current'   => max(1, get_query_var('paged')),
                        'prev_text' => '&larr; Previous',
                        'next_text' => 'Next &rarr;',
                    ) );
                    ?>
                </div>
                <?php wp_reset_postdata(); ?>

            <?php else : ?>
                <div class="news-empty-state animate-on-scroll">
                    <div class="empty-icon">📰</div>
                    <h2 class="empty-title"><?php echo esc_html(kg_get_field('news_empty_title', 'News updates coming soon', $page_id)); ?></h2>
                    <p class="empty-desc"><?php echo esc_html(kg_get_field('news_empty_desc', 'We are crafting articles about cooperative developments and corporate highlights. Check back in a few days!', $page_id)); ?></p>
                </div>
            <?php endif; ?>
        </section>
        
    </div>
</main>

<style>
    .news-page-root {
        background: #fff;
        padding-top: 5rem;
        padding-bottom: 6rem;
    }
    
    .news-page-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1.5rem;
    }
    
    /* Massive Featured Showcase */
    .news-featured-showcase {
        margin-bottom: 2.5rem;
    }
    
    .featured-link-wrapper {
        display: grid;
        grid-template-columns: 1fr;
        gap: 2rem;
        text-decoration: none;
        color: inherit;
        align-items: center;
        transition: opacity 0.25s ease;
    }
    
    @media (min-width: 992px) {
        .featured-link-wrapper {
            grid-template-columns: 1.15fr 1fr;
            gap: 3.5rem;
        }
    }
    
    .featured-link-wrapper:hover {
        opacity: 0.95;
    }
    
    .featured-image-col {
        border-radius: 16px;
        overflow: hidden;
        aspect-ratio: 16/10;
        border: 1px solid rgba(10, 37, 64, 0.05);
        background: #f7f9fc;
    }
    
    .featured-image-col img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    
    .featured-img-fallback {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(10, 37, 64, 0.03);
        color: rgba(10, 37, 64, 0.25);
        font-size: 4rem;
    }
    
    .featured-details-col {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }
    
    .featured-tag {
        font-size: 0.72rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: var(--neutral-yellow, #ffd166);
        margin-bottom: 1rem;
    }
    
    .featured-title {
        font-family: var(--font-header, sans-serif);
        font-size: clamp(2rem, 4vw, 2.75rem);
        font-weight: 800;
        color: var(--main-blue, #0a2540);
        line-height: 1.2;
        margin: 0 0 1.25rem 0;
        letter-spacing: -0.5px;
    }
    
    .featured-title:hover {
        color: var(--neutral-yellow, #ffd166);
    }
    
    .featured-excerpt {
        font-size: 1.05rem;
        line-height: 1.75;
        color: var(--text-body, #2e3e4f);
        margin: 0 0 1.75rem 0;
    }
    
    .featured-meta,
    .news-item-meta {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        font-size: 0.82rem;
        font-weight: 700;
        color: var(--text-muted, #7e8c9b);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .meta-separator {
        color: var(--neutral-yellow, #ffd166);
    }
    
    .section-divider {
        border: none;
        border-top: 1px solid rgba(10, 37, 64, 0.08);
        margin: 4.5rem 0;
    }
    
    /* Older Stories Section */
    .older-news-section {
        margin-top: 2rem;
    }
    
    .section-subtitle {
        font-family: var(--font-header, sans-serif);
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--main-blue, #0a2540);
        margin: 0 0 2.5rem 0;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--neutral-yellow, #ffd166);
    }
    
    .news-cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 3.5rem;
    }
    
    .news-list-item {
        min-width: 0;
    }
    
    .news-item-link {
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        height: 100%;
        transition: opacity 0.25s ease;
    }
    
    .news-item-link:hover {
        opacity: 0.95;
    }
    
    .news-item-img-wrapper {
        border-radius: 12px;
        overflow: hidden;
        aspect-ratio: 16/10;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(10, 37, 64, 0.05);
        background: #f7f9fc;
    }
    
    .news-item-img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.5s ease;
    }
    
    .news-item-link:hover .news-item-img-wrapper img {
        transform: scale(1.03);
    }
    
    .news-item-content {
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }
    
    .news-item-title {
        font-family: var(--font-header, sans-serif);
        font-size: 1.35rem;
        font-weight: 750;
        color: var(--main-blue, #0a2540);
        line-height: 1.35;
        margin: 0 0 0.85rem 0;
        letter-spacing: -0.25px;
    }
    
    .news-item-link:hover .news-item-title {
        color: var(--neutral-yellow, #ffd166);
    }
    
    .news-item-excerpt {
        font-size: 0.95rem;
        line-height: 1.65;
        color: var(--text-body, #2e3e4f);
        margin: 0 0 1.5rem 0;
        flex-grow: 1;
    }
    
    /* Pagination */
    .news-pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.5rem;
        margin-top: 5rem;
    }
    
    .news-pagination .page-numbers {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
        height: 40px;
        padding: 0 0.75rem;
        border-radius: 8px;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--text-muted, #7e8c9b);
        border: 1px solid rgba(10, 37, 64, 0.08);
        background: #fff;
        transition: all 0.3s ease;
    }
    
    .news-pagination .page-numbers.current,
    .news-pagination .page-numbers:hover {
        background: var(--main-blue, #0a2540);
        color: #fff;
        border-color: var(--main-blue, #0a2540);
    }
    
    /* Empty State */
    .news-empty-state {
        text-align: center;
        padding: 6rem 2rem;
        max-width: 500px;
        margin: 0 auto;
    }
    
    .empty-icon {
        font-size: 4rem;
        opacity: 0.25;
        margin-bottom: 1.5rem;
    }
    
    .empty-title {
        font-family: var(--font-header, sans-serif);
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--main-blue, #0a2540);
        margin-bottom: 0.75rem;
    }
    
    .empty-desc {
        font-size: 0.95rem;
        line-height: 1.6;
        color: var(--text-muted, #7e8c9b);
        margin: 0;
    }
</style>

<?php get_footer(); ?>
