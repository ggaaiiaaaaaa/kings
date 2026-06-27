<?php
if ( ! defined( 'ABSPATH' ) ) {
    require_once 'functions.php';
}
/**
 * The template for displaying all single posts
 */

get_header();
?>

    <main id="main-content" class="blog-post-page">
        <?php
        while ( have_posts() ) :
            the_post();
            
            // Calculate reading time
            $word_count = str_word_count( strip_tags( get_the_content() ) );
            $reading_time = max( 1, ceil( $word_count / 200 ) );
            ?>
            
            <section class="blog-post-section">
                <!-- Main Content Container (Narrow for maximum readability) -->
                <div class="container blog-post-container animate-on-scroll">
                    

                    
                    <div class="blog-main-content">
                        <!-- Post Header -->
                        <div class="blog-post-header">
                            <div class="post-meta-strip">
                                <span class="meta-date"><?php echo get_the_date('F j, Y'); ?></span>
                                <span class="meta-separator">•</span>
                                <span class="meta-read-time"><?php echo $reading_time; ?> min read</span>
                            </div>
                            <h1 class="blog-post-title"><?php the_title(); ?></h1>
                        </div>
                        
                        <!-- Post Content -->
                        <article class="blog-post-article">
                            <div class="blog-post-content">
                                <?php the_content(); ?>
                            </div>
                        </article>
                    </div>
                </div>

                <!-- Recent Stories Section at the very bottom (Wider for the grid layout) -->
                <div class="blog-footer-widget-area">
                    <div class="container blog-page-container animate-on-scroll">
                        <div class="sidebar-widget recent-posts-widget">
                            
                            <!-- Header with "See All" inline -->
                            <div class="recent-posts-header">
                                <h3 class="widget-title">Recent Stories</h3>
                                <a href="<?php echo esc_url(home_url('/news/')); ?>" class="see-all-btn">
                                    See All
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                                </a>
                            </div>
                            
                            <div class="recent-posts-list">
                                <?php
                                $recent_query = new WP_Query( array(
                                    'post_type'      => 'post',
                                    'posts_per_page' => 4,
                                    'post__not_in'   => array( get_the_ID() ),
                                ) );
                                if ( $recent_query->have_posts() ) :
                                    while ( $recent_query->have_posts() ) : $recent_query->the_post();
                                        $post_img = get_the_post_thumbnail_url(get_the_ID(), 'medium') ?: get_post_meta(get_the_ID(), '_kg_post_image', true);
                                    ?>
                                    <a href="<?php the_permalink(); ?>" class="recent-post-item">
                                        <?php if ( !empty($post_img) ) : ?>
                                            <div class="recent-post-img-wrapper">
                                                <img src="<?php echo esc_url($post_img); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
                                            </div>
                                        <?php else : ?>
                                            <div class="recent-post-img-wrapper fallback-img">
                                                <span>📰</span>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div class="recent-post-info">
                                            <h4 class="recent-post-title"><?php the_title(); ?></h4>
                                        </div>
                                    </a>
                                    <?php
                                    endwhile;
                                    wp_reset_postdata();
                                else :
                                    echo '<p class="no-posts">No other stories posted yet.</p>';
                                endif;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <?php
        endwhile;
        ?>
    </main>

<style>
    .blog-post-page {
        background: #fff;
        padding-top: 6rem;
        padding-bottom: 6rem;
    }
    
    .blog-post-section {
        padding: 0;
    }
    
    .blog-post-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 0 1.5rem;
    }
    
    .blog-page-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 0 1.5rem;
    }
    
    /* Main Content */
    .blog-main-content {
        min-width: 0;
    }
    
    /* Back Link styling */
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--main-blue, #0a2540);
        margin-bottom: 2rem;
        text-decoration: none;
        transition: color 0.3s ease, transform 0.3s ease;
    }
    
    .back-link:hover {
        color: var(--neutral-yellow, #ffd166);
        transform: translateX(-4px);
    }
    
    .blog-post-header {
        margin-bottom: 2.5rem;
    }
    
    .post-meta-strip {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--text-muted, #7e8c9b);
        margin-bottom: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .meta-separator {
        color: var(--neutral-yellow, #ffd166);
    }
    
    .blog-post-title {
        font-family: var(--font-header, sans-serif);
        font-size: clamp(2.25rem, 4.5vw, 3.25rem);
        font-weight: 800;
        color: var(--main-blue, #0a2540);
        line-height: 1.25;
        margin: 0;
        letter-spacing: -0.5px;
    }
    
    .blog-post-content {
        font-size: 1.125rem;
        line-height: 1.85;
        color: var(--text-body, #2e3e4f);
    }
    
    .blog-post-content p {
        margin-bottom: 1.75rem;
    }
    
    .blog-post-content h2,
    .blog-post-content h3 {
        font-family: var(--font-header, sans-serif);
        color: var(--main-blue, #0a2540);
        font-weight: 800;
        margin-top: 2.75rem;
        margin-bottom: 1.25rem;
    }
    
    .blog-post-content h2 {
        font-size: 1.75rem;
        border-bottom: 1px solid rgba(10, 37, 64, 0.08);
        padding-bottom: 0.5rem;
    }
    
    .blog-post-content h3 {
        font-size: 1.4rem;
    }
    
    .blog-post-content blockquote {
        margin: 2.5rem 0;
        padding: 1.25rem 1.75rem;
        background: rgba(255, 209, 102, 0.04);
        border-left: 4px solid var(--neutral-yellow, #ffd166);
        font-style: italic;
        font-size: 1.2rem;
        line-height: 1.7;
        color: var(--main-blue, #0a2540);
    }
    
    .blog-post-content ul,
    .blog-post-content ol {
        margin-bottom: 2rem;
        padding-left: 1.5rem;
    }
    
    .blog-post-content li {
        margin-bottom: 0.75rem;
    }
    
    /* Recent Stories Footer Area */
    .blog-footer-widget-area {
        margin-top: 5rem;
        padding-top: 3.5rem;
    }
    
    .blog-footer-widget-area .sidebar-widget {
        background: transparent;
        border: none;
        padding: 0;
    }
    
    /* Header with Title and "See All" inline */
    .recent-posts-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid var(--neutral-yellow, #ffd166);
    }
    
    .recent-posts-header .widget-title {
        font-family: var(--font-header, sans-serif);
        font-size: 1.4rem;
        font-weight: 800;
        color: var(--main-blue, #0a2540);
        margin: 0;
        border: none;
        padding: 0;
    }
    
    .see-all-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--main-blue, #0a2540);
        text-decoration: none;
        transition: color 0.3s ease, transform 0.3s ease;
    }
    
    .see-all-btn:hover {
        color: var(--neutral-yellow, #ffd166);
        transform: translateX(3px);
    }
    
    .blog-footer-widget-area .recent-posts-list {
        display: grid;
        grid-template-columns: repeat(1, 1fr);
        gap: 2.5rem;
    }
    
    @media (min-width: 576px) {
        .blog-footer-widget-area .recent-posts-list {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (min-width: 992px) {
        .blog-footer-widget-area .recent-posts-list {
            grid-template-columns: repeat(4, 1fr);
        }
    }
    
    .recent-post-item {
        display: flex;
        flex-direction: column;
        text-decoration: none;
        transition: transform 0.2s ease, opacity 0.2s ease;
    }
    
    .recent-post-item:hover {
        opacity: 0.9;
        transform: translateY(-2px);
    }
    
    .recent-post-img-wrapper {
        aspect-ratio: 16/10;
        overflow: hidden;
        margin-bottom: 1.25rem;
        border: 1px solid rgba(10, 37, 64, 0.05);
        background: #f7f9fc;
        border-radius: 8px;
    }
    
    .recent-post-img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.4s ease;
    }
    
    .recent-post-item:hover .recent-post-img-wrapper img {
        transform: scale(1.03);
    }
    
    .recent-post-img-wrapper.fallback-img {
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        color: rgba(10, 37, 64, 0.15);
    }
    
    .recent-post-info {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    
    .recent-post-title {
        font-family: var(--font-header, sans-serif);
        font-size: 1.15rem;
        font-weight: 750;
        color: var(--main-blue, #0a2540);
        text-align: center;
        line-height: 1.4;
        margin: 0;
        padding: 0 0.5rem;
    }
    
    .recent-post-divider {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        max-width: 150px;
        margin: 0.75rem 0;
        position: relative;
    }
    
    .recent-post-divider::before {
        content: "";
        position: absolute;
        left: 0;
        right: 0;
        top: 50%;
        height: 1px;
        background: #fbc4db;
        z-index: 1;
    }
    
    .recent-post-divider .ornament {
        background: #fff;
        padding: 0 0.5rem;
        font-size: 0.65rem;
        color: #fbc4db;
        z-index: 2;
        font-family: sans-serif;
    }
    
    .recent-post-author {
        font-family: var(--font-body, sans-serif);
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: #d63384;
        text-align: center;
        display: block;
    }
    
    .no-posts {
        font-size: 0.9rem;
        color: var(--text-muted, #7e8c9b);
        margin: 0;
    }
</style>
<?php
get_footer();
