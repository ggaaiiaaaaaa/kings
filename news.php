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

get_header();

// Category filter from query string
$active_filter = sanitize_text_field( $_GET['category'] ?? 'all' );
$filter_categories = array(
    'all'       => 'All News',
    'corporate' => 'Corporate',
    'events'    => 'Events',
    'community' => 'Community',
);

// Query posts
$query_args = array(
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'posts_per_page' => 12,
    'paged'          => get_query_var('paged') ? get_query_var('paged') : 1,
);
if ( $active_filter !== 'all' ) {
    $query_args['category_name'] = $active_filter;
}
$news_query = new WP_Query( $query_args );

// Featured post (latest)
$featured_post = null;
if ( $news_query->have_posts() && get_query_var('paged', 1) <= 1 ) {
    $news_query->the_post();
    $featured_post = get_post();
    wp_reset_postdata();
}
?>

<!-- Premium News Page Hero -->
<section class="news-hero-section">
    <div class="news-hero-glow"></div>
    <div class="container news-hero-container">
        <span class="news-tag animate-on-scroll">Stay Informed</span>
        <h1 class="news-title animate-on-scroll">Kings Group Newsroom</h1>
        <p class="news-subtitle animate-on-scroll">Corporate insights, upcoming events, and stories of cooperative success.</p>
    </div>
</section>

<!-- Premium Category Filter Bar (Glassmorphic & Floating) -->
<section class="news-filter-section">
    <div class="container">
        <div class="news-filter-wrapper animate-on-scroll">
            <div class="news-filter-scroll">
                <?php foreach ( $filter_categories as $slug => $label ) :
                    $is_active = ($active_filter === $slug);
                    $url = ($slug === 'all') ? home_url('/news/') : home_url('/news/?category=' . $slug);
                ?>
                <a href="<?php echo esc_url($url); ?>" class="news-filter-pill <?php echo $is_active ? 'active' : ''; ?>">
                    <?php echo esc_html($label); ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<?php if ( $featured_post && get_query_var('paged', 1) <= 1 ) : ?>
<!-- Featured Article Showcase (Borderless Glass Hero) -->
<section class="featured-news-section">
    <div class="container animate-on-scroll">
        <a href="<?php echo get_permalink($featured_post); ?>" class="news-featured-card">
            <div class="featured-img-wrapper">
                <?php if ( has_post_thumbnail($featured_post) ) : ?>
                    <img src="<?php echo get_the_post_thumbnail_url($featured_post, 'large'); ?>" alt="<?php echo esc_attr(get_the_title($featured_post)); ?>" loading="eager">
                <?php else : ?>
                    <div class="featured-img-fallback">📰</div>
                <?php endif; ?>
            </div>
            <div class="featured-content">
                <span class="featured-badge">Featured Story</span>
                <h2 class="featured-title"><?php echo esc_html(get_the_title($featured_post)); ?></h2>
                <p class="featured-desc"><?php echo wp_trim_words(get_the_excerpt($featured_post) ?: get_the_content(null, false, $featured_post), 32, '…'); ?></p>
                <div class="featured-meta">
                    <span class="featured-date"><?php echo get_the_date('M j, Y', $featured_post); ?></span>
                    <span class="featured-link">Read Full Story 
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </span>
                </div>
            </div>
        </a>
    </div>
</section>
<?php endif; ?>

<!-- News Grid & Archive -->
<section class="news-grid-section">
    <div class="container">
        <?php if ( $news_query->have_posts() ) : ?>
        <div class="news-cards-grid">
            <?php
            // Skip the featured post (already displayed)
            $skip_first = ($featured_post && get_query_var('paged', 1) <= 1);
            $skipped = false;
            while ( $news_query->have_posts() ) : $news_query->the_post();
                if ( $skip_first && !$skipped && get_the_ID() === $featured_post->ID ) {
                    $skipped = true;
                    continue;
                }
            ?>
            <a href="<?php the_permalink(); ?>" class="news-card animate-on-scroll">
                <div class="news-card-img-wrapper">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'medium_large'); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
                    <?php else : ?>
                        <div class="news-card-img-fallback">📰</div>
                    <?php endif; ?>
                </div>
                <div class="news-card-content">
                    <span class="news-card-date"><?php echo get_the_date('M j, Y'); ?></span>
                    <h3 class="news-card-title"><?php the_title(); ?></h3>
                    <p class="news-card-desc"><?php echo wp_trim_words(get_the_excerpt() ?: get_the_content(), 20, '…'); ?></p>
                    <span class="news-card-link">Read Article
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </span>
                </div>
            </a>
            <?php endwhile; ?>
        </div>

        <!-- Premium Pagination -->
        <div class="news-pagination animate-on-scroll">
            <?php
            echo paginate_links( array(
                'total'     => $news_query->max_num_pages,
                'current'   => max(1, get_query_var('paged')),
                'prev_text' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="transform: rotate(180deg);"><path d="M5 12h14M12 5l7 7-7 7"/></svg> Previous',
                'next_text' => 'Next <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>',
            ) );
            ?>
        </div>
        <?php wp_reset_postdata(); ?>

        <?php else : ?>
        <!-- Premium Empty State -->
        <div class="news-empty-state animate-on-scroll">
            <div class="empty-icon">📰</div>
            <h2 class="empty-title">News updates coming soon</h2>
            <p class="empty-desc">We are crafting articles about cooperative developments and corporate highlights. Check back in a few days!</p>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="btn-bespoke-gold">Back to Homepage</a>
        </div>
        <?php endif; ?>
    </div>
</section>

<style>
    /* News Hero Layout */
    .news-hero-section {
        position: relative;
        padding: 9rem 0 6rem 0;
        background: var(--main-blue);
        overflow: hidden;
    }

    .news-hero-glow {
        position: absolute;
        top: -50%;
        left: -30%;
        width: 160%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 209, 102, 0.08) 0%, transparent 60%);
        z-index: 0;
        pointer-events: none;
    }

    .news-hero-container {
        position: relative;
        z-index: 1;
        text-align: center;
        max-width: 800px;
    }

    .news-tag {
        display: inline-block;
        font-size: 0.72rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: var(--neutral-yellow);
        margin-bottom: 1rem;
    }

    .news-title {
        font-family: var(--font-header);
        font-size: clamp(2.5rem, 5vw, 4rem);
        font-weight: 900;
        color: #fff;
        line-height: 1.15;
        margin: 0 0 1.25rem 0;
        letter-spacing: -1px;
    }

    .news-subtitle {
        font-size: 1.15rem;
        color: rgba(255, 255, 255, 0.75);
        max-width: 600px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* Floating Filter Bar with Premium Blur */
    .news-filter-section {
        position: sticky;
        top: 80px;
        z-index: 90;
        margin-top: -2.25rem;
        padding-bottom: 2rem;
    }

    .news-filter-wrapper {
        background: rgba(255, 255, 255, 0.65);
        backdrop-filter: blur(35px) saturate(200%);
        -webkit-backdrop-filter: blur(35px) saturate(200%);
        padding: 0.85rem 1.75rem;
        border-radius: 50px;
        box-shadow: 0 15px 35px rgba(10, 37, 64, 0.08);
        border: 1px solid rgba(10, 37, 64, 0.05);
    }

    .news-filter-scroll {
        display: flex;
        justify-content: center;
        gap: 0.75rem;
        overflow-x: auto;
        scrollbar-width: none; /* Firefox */
    }

    .news-filter-scroll::-webkit-scrollbar {
        display: none; /* Chrome/Safari */
    }

    .news-filter-pill {
        padding: 0.7rem 1.6rem;
        border-radius: 50px;
        font-size: 0.88rem;
        font-weight: 700;
        color: var(--text-muted);
        text-decoration: none;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        white-space: nowrap;
        background: rgba(10, 37, 64, 0.03);
        border: 1px solid rgba(10, 37, 64, 0.05);
    }

    .news-filter-pill:hover,
    .news-filter-pill.active {
        background: var(--main-blue);
        color: #fff;
        border-color: var(--main-blue);
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(10, 37, 64, 0.2);
    }

    /* Featured Section (Liquid Glass Banner) */
    .featured-news-section {
        padding: 3rem 0;
    }

    .news-featured-card {
        display: grid;
        grid-template-columns: 1.15fr 1fr;
        gap: 3.5rem;
        align-items: center;
        text-decoration: none;
        background: rgba(255, 255, 255, 0.4);
        backdrop-filter: blur(25px) saturate(180%);
        -webkit-backdrop-filter: blur(25px) saturate(180%);
        border-radius: 28px;
        overflow: hidden;
        border: 1px solid rgba(10, 37, 64, 0.06);
        box-shadow: 0 20px 45px rgba(10, 37, 64, 0.05);
        transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .news-featured-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 30px 60px rgba(10, 37, 64, 0.1);
        border-color: rgba(10, 37, 64, 0.12);
    }

    .featured-img-wrapper {
        aspect-ratio: 16/10;
        overflow: hidden;
        position: relative;
    }

    .featured-img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .news-featured-card:hover .featured-img-wrapper img {
        transform: scale(1.06);
    }

    .featured-img-fallback {
        width: 100%;
        height: 100%;
        background: var(--gradient-hero);
        display: flex;
        align-items: center;
        justify-content: center;
        color: rgba(255, 255, 255, 0.25);
        font-size: 4rem;
    }

    .featured-content {
        padding: 3rem 3rem 3rem 0;
    }

    .featured-badge {
        display: inline-block;
        background: rgba(0, 208, 156, 0.08);
        color: #008765;
        padding: 0.45rem 1.15rem;
        border-radius: 50px;
        font-size: 0.72rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 1.25rem;
        border: 1px solid rgba(0, 208, 156, 0.18);
    }

    .featured-title {
        font-family: var(--font-header);
        font-size: 1.95rem;
        font-weight: 800;
        color: var(--main-blue);
        line-height: 1.3;
        margin: 0 0 1rem 0;
        letter-spacing: -0.5px;
    }

    .featured-desc {
        color: var(--text-muted);
        font-size: 1.02rem;
        line-height: 1.7;
        margin: 0 0 1.75rem 0;
    }

    .featured-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1px solid rgba(10, 37, 64, 0.08);
        padding-top: 1.25rem;
    }

    .featured-date {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-light);
    }

    .featured-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.92rem;
        font-weight: 700;
        color: var(--main-blue);
    }

    .featured-link svg {
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .news-featured-card:hover .featured-link svg {
        transform: translateX(4px);
    }

    /* Grid Section & Cards */
    .news-grid-section {
        padding: 4rem 0 6rem 0;
        background: var(--bg-light);
    }

    .news-cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
        gap: 2.5rem;
    }

    .news-card {
        display: flex;
        flex-direction: column;
        text-decoration: none;
        background: rgba(255, 255, 255, 0.55);
        backdrop-filter: blur(25px) saturate(180%);
        -webkit-backdrop-filter: blur(25px) saturate(180%);
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid rgba(10, 37, 64, 0.05);
        box-shadow: 0 12px 35px rgba(10, 37, 64, 0.03);
        transition: all 0.45s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .news-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 45px rgba(10, 37, 64, 0.08);
        border-color: rgba(10, 37, 64, 0.1);
    }

    .news-card-img-wrapper {
        aspect-ratio: 16/10;
        overflow: hidden;
        position: relative;
        background: rgba(10, 37, 64, 0.02);
    }

    .news-card-img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .news-card:hover .news-card-img-wrapper img {
        transform: scale(1.05);
    }

    .news-card-img-fallback {
        width: 100%;
        height: 100%;
        background: rgba(10, 37, 64, 0.04);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-light);
        font-size: 3rem;
    }

    .news-card-content {
        padding: 1.75rem;
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    .news-card-date {
        font-size: 0.78rem;
        font-weight: 700;
        color: var(--text-light);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.75rem;
    }

    .news-card-title {
        font-size: 1.2rem;
        font-weight: 800;
        color: var(--main-blue);
        line-height: 1.35;
        margin: 0 0 0.75rem 0;
        letter-spacing: -0.2px;
    }

    .news-card-desc {
        font-size: 0.95rem;
        color: var(--text-body);
        line-height: 1.6;
        margin: 0 0 1.5rem 0;
        flex: 1;
    }

    .news-card-link {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        color: var(--main-blue);
        font-size: 0.88rem;
        font-weight: 700;
        margin-top: auto;
    }

    .news-card-link svg {
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .news-card:hover .news-card-link svg {
        transform: translateX(3px);
    }

    /* Premium Pagination */
    .news-pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.35rem;
        margin-top: 4.5rem;
    }

    .news-pagination .page-numbers {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 44px;
        height: 44px;
        padding: 0 0.75rem;
        border-radius: 50px;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--text-muted);
        background: rgba(255, 255, 255, 0.75);
        backdrop-filter: blur(5px);
        border: 1px solid rgba(10, 37, 64, 0.05);
        transition: all 0.3s ease;
    }

    .news-pagination .page-numbers.current,
    .news-pagination .page-numbers:hover {
        background: var(--main-blue);
        color: #fff;
        border-color: var(--main-blue);
        box-shadow: 0 6px 15px rgba(10, 37, 64, 0.15);
    }

    /* Branded Empty State */
    .news-empty-state {
        text-align: center;
        padding: 7rem 2rem;
        max-width: 550px;
        margin: 0 auto;
    }

    .empty-icon {
        font-size: 4.5rem;
        margin-bottom: 1.5rem;
        line-height: 1;
        opacity: 0.35;
    }

    .empty-title {
        font-family: var(--font-header);
        font-size: 1.85rem;
        color: var(--main-blue);
        font-weight: 800;
        margin-bottom: 0.75rem;
    }

    .empty-desc {
        font-size: 1rem;
        color: var(--text-muted);
        line-height: 1.6;
        margin-bottom: 2.25rem;
    }

    .btn-bespoke-gold {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 1.1rem 2.75rem;
        border-radius: 50px;
        font-weight: 700;
        text-decoration: none;
        font-size: 1.02rem;
        background: #ffd166;
        color: #0a2540;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 6px 20px rgba(255, 209, 102, 0.35);
    }

    .btn-bespoke-gold:hover {
        background: #0a2540;
        color: #fff;
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(10, 37, 64, 0.2);
    }

    /* Responsiveness Adaptations */
    @media (max-width: 992px) {
        .news-filter-section {
            top: 70px;
        }
        .news-featured-card {
            grid-template-columns: 1fr;
            gap: 0;
        }
        .featured-content {
            padding: 2.5rem;
        }
    }

    @media (max-width: 576px) {
        .news-filter-section {
            top: 60px;
            margin-top: -1.5rem;
        }
        .news-filter-wrapper {
            padding: 0.7rem 1.25rem;
        }
        .news-filter-pill {
            padding: 0.55rem 1.25rem;
            font-size: 0.82rem;
        }
        .featured-content {
            padding: 1.75rem;
        }
        .featured-title {
            font-size: 1.5rem;
        }
        .news-cards-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<?php get_footer(); ?>
