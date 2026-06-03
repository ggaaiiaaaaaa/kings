<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Kings_Group
 */

if ( ! defined( 'ABSPATH' ) ) {
    require_once 'functions.php';
}

$page_hero_bg = 'https://images.unsplash.com/photo-1495020689067-958852a7765e?auto=format&fit=crop&w=2000&q=80';
get_header();

// Determine if we are on the blog archive or a general archive
$is_news_archive = is_home() || is_archive();
$title = $is_news_archive ? 'News & Updates' : 'Kings Group';
$subtitle = $is_news_archive ? 'Stay informed with the latest insights, company news, and industry updates from the Kings Group community.' : 'Elite talent acquisition and ethical staffing solutions.';
?>

<!-- Hero Section -->
<section class="page-hero" style="background-image: linear-gradient(rgba(10, 37, 64, 0.8), rgba(10, 37, 64, 0.8)), url('https://images.unsplash.com/photo-1495020689067-958852a7765e?auto=format&fit=crop&w=2000&q=80');">
    <div class="container text-center">
        <h1 class="animate-on-scroll"><?php echo esc_html($title); ?></h1>
        <p class="animate-on-scroll" style="max-width: 700px; margin: 0 auto;"><?php echo esc_html($subtitle); ?></p>
    </div>
</section>

<?php if ( $is_news_archive ) : ?>
    <!-- News Archive Grid -->
    <section class="section section-bg-light" style="padding: 6rem 0;">
        <div class="container">
            <?php if ( have_posts() ) : ?>
                <div class="news-grid">
                    <?php while ( have_posts() ) : the_post(); ?>
                        <article class="news-glass-card animate-on-scroll">
                            <div class="news-card-media">
                                <?php 
                                $custom_img_url = get_post_meta(get_the_ID(), '_kg_post_image', true);
                                if (has_post_thumbnail()) : ?>
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('kg-card'); ?>
                                    </a>
                                <?php elseif (!empty($custom_img_url)) : ?>
                                    <a href="<?php the_permalink(); ?>">
                                        <?php echo kg_img($custom_img_url, get_the_title(), 'img-fluid'); ?>
                                    </a>
                                <?php else : ?>
                                    <div class="news-card-placeholder">
                                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" opacity="0.3">
                                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                            <polyline points="21 15 16 10 5 21"></polyline>
                                        </svg>
                                    </div>
                                <?php endif; ?>
                                <div class="news-card-date"><?php echo get_the_date('M d'); ?></div>
                            </div>
                            <div class="news-card-body">
                                <div class="news-card-meta">
                                    <span class="news-category"><?php the_category(', '); ?></span>
                                </div>
                                <h3 class="news-card-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h3>
                                <div class="news-card-excerpt">
                                    <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
                                </div>
                                <a href="<?php the_permalink(); ?>" class="news-card-link">
                                    Read Article 
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                                </a>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>

                <!-- Pagination -->
                <div class="pagination-container animate-on-scroll">
                    <?php
                    echo paginate_links( array(
                        'prev_text' => '← Newer',
                        'next_text' => 'Older →',
                        'type'      => 'list',
                    ) );
                    ?>
                </div>

            <?php else : ?>
                <div class="empty-state-glass text-center">
                    <h2 class="section-title">No articles found</h2>
                    <p class="section-subtitle">We haven't posted any news yet. Please check back later.</p>
                    <a href="<?php echo esc_url(home_url()); ?>" class="btn btn-primary">Return Home</a>
                </div>
            <?php endif; ?>
        </div>
    </section>
<?php else : ?>
    <!-- Fallback for non-archive index -->
    <section class="section">
        <div class="container">
            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                <?php the_content(); ?>
            <?php endwhile; endif; ?>
        </div>
    </section>
<?php endif; ?>

<style>
    .news-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
        gap: 2.5rem;
    }
    .news-glass-card {
        background: var(--glass-mid-bg);
        backdrop-filter: var(--glass-mid-blur);
        border: 1px solid var(--glass-mid-border);
        box-shadow: var(--glass-mid-shadow);
        border-radius: 16px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: var(--transition);
        height: 100%;
    }
    .news-glass-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-hover);
        border-color: var(--main-blue-light);
    }
    .news-card-media {
        position: relative;
        aspect-ratio: 16/10;
        overflow: hidden;
        background: #eee;
    }
    .news-card-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }
    .news-glass-card:hover .news-card-media img {
        transform: scale(1.05);
    }
    .news-card-date {
        position: absolute;
        bottom: 1rem;
        right: 1rem;
        background: var(--neutral-yellow);
        color: var(--main-blue);
        padding: 0.4rem 0.8rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .news-card-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--gradient-hero);
        color: rgba(255,255,255,0.2);
    }
    .news-card-body {
        padding: 2rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .news-card-meta {
        margin-bottom: 0.75rem;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .news-category a {
        color: var(--sec-accent-green);
        text-decoration: none;
    }
    .news-card-title {
        font-size: 1.3rem;
        margin-bottom: 1rem;
        line-height: 1.4;
    }
    .news-card-title a {
        color: var(--text-dark);
        text-decoration: none;
    }
    .news-card-excerpt {
        font-size: 0.95rem;
        color: var(--text-muted);
        line-height: 1.6;
        margin-bottom: 1.5rem;
        flex: 1;
    }
    .news-card-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--main-blue);
        text-decoration: none;
        transition: var(--transition);
    }
    .news-card-link:hover {
        color: var(--neutral-yellow);
        gap: 0.75rem;
    }

    .pagination-container {
        margin-top: 5rem;
        display: flex;
        justify-content: center;
    }
    .pagination ul {
        display: flex;
        list-style: none;
        padding: 0;
        gap: 0.5rem;
    }
    .pagination .page-numbers {
        display: inline-block;
        padding: 0.6rem 1.2rem;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        text-decoration: none;
        color: var(--text-dark);
        font-weight: 600;
        transition: var(--transition);
    }
    .pagination .page-numbers.current {
        background: var(--main-blue);
        color: #fff;
        border-color: var(--main-blue);
    }
    .pagination .page-numbers:hover:not(.current) {
        background: var(--bg-light);
    }

    @media (max-width: 768px) {
        .news-grid { grid-template-columns: 1fr; }
    }
</style>

<?php get_footer(); ?>