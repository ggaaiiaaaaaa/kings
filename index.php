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

get_header();

// Determine if we are on the blog archive or a general archive
$is_news_archive = is_home() || is_archive();
$title = $is_news_archive ? 'News & Updates' : 'Kings Group';
$subtitle = $is_news_archive ? 'Stay informed with the latest insights, company news, and industry updates from the Kings Group community.' : 'Elite talent acquisition and ethical staffing solutions.';
?>

<!-- Hero Section -->
<section class="hero hero-minimal" style="min-height: 40vh; display: flex; align-items: center; background: var(--main-blue); position: relative; overflow: hidden;">
    <div class="hero-bg-media">
        <img src="https://images.unsplash.com/photo-1495020689067-958852a7765e?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80" 
             alt="News and Updates" style="opacity: 0.3; filter: grayscale(100%);">
    </div>
    <div class="container" style="position: relative; z-index: 2; text-align: center; color: white;">
        <h1 style="font-size: clamp(2.5rem, 5vw, 4rem); margin-bottom: 1rem;"><?php echo esc_html($title); ?></h1>
        <p style="max-width: 700px; margin: 0 auto; font-size: 1.1rem; opacity: 0.9; line-height: 1.6;">
            <?php echo esc_html($subtitle); ?>
        </p>
    </div>
</section>

<?php if ( $is_news_archive ) : ?>
    <!-- News Archive Grid -->
    <section class="section section-bg-light">
        <div class="container">
            <?php if ( have_posts() ) : ?>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2.5rem;">
                    <?php while ( have_posts() ) : the_post(); ?>
                        <article class="news-card glass-card animate-on-scroll" style="display: flex; flex-direction: column; height: 100%; overflow: hidden; padding: 0; background: white; border-radius: var(--card-radius); border: 1px solid var(--border-color); transition: transform 0.3s ease, box-shadow 0.3s ease;">
                            <div style="aspect-ratio: 16/9; overflow: hidden; border-bottom: 1px solid var(--border-color); background: #eee;">
                                <?php if (has_post_thumbnail()) : ?>
                                    <a href="<?php the_permalink(); ?>" style="display: block; height: 100%;">
                                        <?php the_post_thumbnail('kg-card', ['style' => 'width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;']); ?>
                                    </a>
                                <?php else : ?>
                                    <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: var(--text-muted); font-size: 0.9rem;">
                                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" opacity="0.3">
                                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                            <polyline points="21 15 16 10 5 21"></polyline>
                                        </svg>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div style="padding: 1.75rem; display: flex; flex-direction: column; flex-grow: 1;">
                                <div style="display: flex; justify-content: space-between; font-size: 0.8rem; margin-bottom: 1rem; color: var(--text-muted); font-weight: 500; text-transform: uppercase; letter-spacing: 0.05em;">
                                    <span><?php the_category(', '); ?></span>
                                    <span><?php echo get_the_date(); ?></span>
                                </div>
                                <h3 style="font-size: 1.3rem; margin-bottom: 1rem; line-height: 1.4;">
                                    <a href="<?php the_permalink(); ?>" style="color: var(--main-blue); text-decoration: none; transition: color 0.2s ease;">
                                        <?php the_title(); ?>
                                    </a>
                                </h3>
                                <div style="font-size: 0.95rem; color: var(--text-muted); line-height: 1.6; margin-bottom: 2rem; flex-grow: 1;">
                                    <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
                                </div>
                                <a href="<?php the_permalink(); ?>" class="btn btn-outline" style="align-self: flex-start; padding: 0.6rem 1.2rem; font-size: 0.85rem; border-color: var(--main-blue); color: var(--main-blue);">
                                    Read Full Article
                                </a>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>

                <!-- Pagination -->
                <div class="pagination" style="margin-top: 4rem; text-align: center;">
                    <?php
                    echo paginate_links( array(
                        'prev_text' => '<span class="prev">← Newer</span>',
                        'next_text' => '<span class="next">Older →</span>',
                        'type'      => 'list',
                    ) );
                    ?>
                </div>

            <?php else : ?>
                <div style="text-align: center; padding: 5rem 0;">
                    <h2 style="color: var(--main-blue);">No articles found</h2>
                    <p style="color: var(--text-muted);">We haven't posted any news yet. Please check back later.</p>
                    <a href="<?php echo esc_url(home_url()); ?>" class="btn btn-primary" style="margin-top: 2rem;">Return Home</a>
                </div>
            <?php endif; ?>
        </div>
    </section>
<?php else : ?>
    <!-- Fallback for non-archive index (if any) -->
    <section class="section">
        <div class="container">
            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                <?php the_content(); ?>
            <?php endwhile; endif; ?>
        </div>
    </section>
<?php endif; ?>

<?php get_footer(); ?>
