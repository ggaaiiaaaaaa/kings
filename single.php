<?php
if (!defined('ABSPATH')) {
    require_once 'functions.php';
}
/**
 * The template for displaying all single posts
 */

get_header();
?>

<main id="main-content" style="background-color: #FAF9F6; padding-top: 160px; padding-bottom: 100px;">
    <?php
    while (have_posts()):
        the_post();

        // Calculate reading time
        $word_count = str_word_count(strip_tags(get_the_content()));
        $reading_time = max(1, ceil($word_count / 200));

        // Image
        $post_img = get_the_post_thumbnail_url(get_the_ID(), 'full') ?: get_post_meta(get_the_ID(), '_kg_post_image', true);
        ?>

        <article class="single-article-container">

            <!-- Back Button -->
            <a href="<?php echo esc_url(home_url('/news/')); ?>" class="single-article__back-btn">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5" />
                    <polyline points="12 19 5 12 12 5" />
                </svg>
                <span>Back to News</span>
            </a>

            <!-- Meta Row -->
            <div class="single-article__meta">
                <div class="single-article__meta-left">
                    <span><?php echo get_the_date('M j'); ?></span>
                    <span>&middot;</span>
                    <span><?php echo $reading_time; ?> min read</span>
                </div>
                <!-- Three dots sharing toggle -->
                <div class="single-article__share-toggle" id="share-toggle">
                    <button type="button" class="single-article__dots-btn" aria-label="Share options" id="share-dots-btn">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
                            <path
                                d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z" />
                        </svg>
                    </button>
                    <!-- Sharing Dropdown -->
                    <div class="sharing-dropdown" id="sharing-dropdown" aria-hidden="true">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>"
                            target="_blank" rel="noopener noreferrer" class="sharing-dropdown__item">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" />
                            </svg>
                            <span>Share on Facebook</span>
                        </a>
                        <button type="button" class="sharing-dropdown__item" id="copy-link-btn"
                            data-url="<?php echo esc_url(get_permalink()); ?>">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" />
                                <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71" />
                            </svg>
                            <span>Copy Link</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Title -->
            <h1 class="single-article__title">
                <?php the_title(); ?>
            </h1>

            <?php if (!empty($post_img)): ?>
                <div style="margin-bottom: 2rem;">
                    <img src="<?php echo esc_url($post_img); ?>" alt="<?php the_title_attribute(); ?>"
                        style="width: 100%; border-radius: 8px; max-height: 500px; object-fit: cover;">
                </div>
            <?php endif; ?>

            <!-- Content -->
            <div class="single-article-content">
                <div class="single-article__text-block">
                    <?php the_content(); ?>
                </div>
            </div>

            <!-- Article Footer: Social Icons -->
            <div class="single-article__social-footer">
                <div class="single-article__social-divider"></div>
                <div class="single-article__social-icons">
                    <a href="https://www.facebook.com/KingsCooperative" target="_blank" rel="noopener noreferrer"
                        aria-label="Facebook" class="single-article__social-link">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="currentColor" width="20"
                            height="20">
                            <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" />
                        </svg>
                    </a>
                </div>
            </div>

        </article>

        <!-- Comments Section -->
        <?php
        echo '<div class="container blog-page-container" style="max-width: 800px; margin: 3rem auto 0; padding: 0 1.5rem;">';
        
        // Forcefully load the comments file, bypassing ANY WordPress plugins that might block it
        if ( file_exists( get_template_directory() . '/comments.php' ) ) {
            include get_template_directory() . '/comments.php';
        } else {
            echo '<!-- comments.php is missing from the server -->';
        }
        
        echo '</div>';
        ?>

        <!-- Recent Posts Section at the very bottom -->
        <div class="blog-footer-widget-area" style="margin-top: 4rem;">
            <div class="container blog-page-container animate-on-scroll"
                style="max-width: 1100px; margin: 0 auto; padding: 0 1.5rem;">
                <div class="sidebar-widget recent-posts-widget">

                    <div class="recent-posts-header"
                        style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2.5rem; padding-bottom: 1rem; border-bottom: 2px solid #ffd166;">
                        <h3 class="widget-title"
                            style="font-family: var(--font-header, sans-serif); font-size: 1.75rem; font-weight: 850; color: #0a2540; margin: 0;">
                            Read More Stories</h3>
                        <a href="<?php echo esc_url(home_url('/news/')); ?>" class="see-all-btn"
                            style="display: inline-flex; align-items: center; gap: 0.5rem; font-size: 1rem; font-weight: 700; color: #0a2540; text-decoration: none;">
                            See All
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5">
                                <path d="M5 12h14M12 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>

                    <div class="recent-posts-list">
                        <?php
                        $recent_query = new WP_Query(array(
                            'post_type' => 'post',
                            'posts_per_page' => 4,
                            'post__not_in' => array(get_the_ID()),
                        ));
                        if ($recent_query->have_posts()):
                            while ($recent_query->have_posts()):
                                $recent_query->the_post();
                                $recent_img = get_the_post_thumbnail_url(get_the_ID(), 'medium') ?: get_post_meta(get_the_ID(), '_kg_post_image', true);
                                ?>
                                <a href="<?php the_permalink(); ?>" class="recent-post-item"
                                    style="display: flex; flex-direction: column; text-decoration: none;">
                                    <?php if (!empty($recent_img)): ?>
                                        <div class="recent-post-img-wrapper"
                                            style="aspect-ratio: 16/10; overflow: hidden; margin-bottom: 1.25rem; background: #f7f9fc; border-radius: 12px;">
                                            <img src="<?php echo esc_url($recent_img); ?>" alt="<?php the_title_attribute(); ?>"
                                                loading="lazy" style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>
                                    <?php else: ?>
                                        <div class="recent-post-img-wrapper fallback-img"
                                            style="aspect-ratio: 16/10; overflow: hidden; margin-bottom: 1.25rem; background: #f7f9fc; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; color: rgba(10, 37, 64, 0.15);">
                                            <span>📰</span>
                                        </div>
                                    <?php endif; ?>

                                    <div class="recent-post-info">
                                        <h4 class="recent-post-title"
                                            style="font-family: var(--font-header, sans-serif); font-size: 1.15rem; font-weight: 800; color: #0a2540; margin: 0; line-height: 1.4;">
                                            <?php the_title(); ?></h4>
                                    </div>
                                </a>
                                <?php
                            endwhile;
                            wp_reset_postdata();
                        else:
                            echo '<p class="no-posts">No other stories posted yet.</p>';
                        endif;
                        ?>
                    </div>
                </div>
            </div>
        </div>

    <?php endwhile; ?>
</main>

<style>
    .single-article-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 0 1.5rem;
        font-family: var(--font-body, "Inter", sans-serif);
        color: #2D3748;
    }

    .single-article__back-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        font-weight: 600;
        color: #4A5568;
        text-decoration: none;
        margin-bottom: 2.5rem;
        transition: color 0.2s, transform 0.2s ease;
    }

    .single-article__back-btn:hover {
        color: #0a2540;
        transform: translateX(-4px);
    }

    .single-article__meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        position: relative;
    }

    .single-article__meta-left {
        font-size: 0.85rem;
        color: #718096;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 500;
    }

    .single-article__share-toggle {
        position: relative;
    }

    .single-article__dots-btn {
        background: none;
        border: none;
        color: #4A5568;
        cursor: pointer;
        padding: 0.5rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s;
    }

    .single-article__dots-btn:hover {
        background: rgba(0, 0, 0, 0.05);
    }

    .sharing-dropdown {
        position: absolute;
        top: 100%;
        right: 0;
        background: #fff;
        border: 1px solid rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        width: 180px;
        z-index: 10;
        display: flex;
        flex-direction: column;
        padding: 0.5rem 0;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: opacity 0.2s, transform 0.2s, visibility 0.2s;
    }

    .sharing-dropdown[aria-hidden="false"] {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .sharing-dropdown__item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        background: none;
        border: none;
        text-align: left;
        font-size: 0.9rem;
        color: #4A5568;
        cursor: pointer;
        text-decoration: none;
        transition: background 0.2s;
    }

    .sharing-dropdown__item:hover {
        background: #f7fafc;
        color: #1a202c;
    }

    .single-article__title {
        font-family: var(--font-header, "Playfair Display", serif);
        font-size: clamp(2.2rem, 4.5vw, 3.5rem);
        font-weight: 850;
        color: #1a202c;
        line-height: 1.15;
        letter-spacing: -0.02em;
        margin-bottom: 2.5rem;
    }

    .single-article__text-block {
        font-size: 1.15rem;
        line-height: 1.8;
        color: #4A5568;
    }

    .single-article__text-block p {
        margin-bottom: 1.75rem;
    }

    .single-article__text-block h2,
    .single-article__text-block h3 {
        font-family: var(--font-header, "Playfair Display", serif);
        color: #1a202c;
        margin-top: 3rem;
        margin-bottom: 1rem;
    }

    .single-article__text-block h2 {
        font-size: 2.1rem;
    }

    .single-article__text-block h3 {
        font-size: 1.6rem;
    }

    .single-article__text-block blockquote {
        border-left: 4px solid #ffd166;
        padding-left: 1.75rem;
        margin: 3rem 0;
        font-style: italic;
        font-size: 1.35rem;
        color: #1a202c;
        background: #fff;
        padding: 2rem;
        border-radius: 0 12px 12px 0;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
    }

    .single-article__social-footer {
        margin-top: 4rem;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }

    .single-article__social-divider {
        width: 100%;
        height: 1px;
        background: rgba(0, 0, 0, 0.1);
        margin-bottom: 1.5rem;
    }

    .single-article__social-icons {
        display: flex;
        gap: 1rem;
    }

    .single-article__social-link {
        color: #4A5568;
        padding: 0.5rem;
        border-radius: 50%;
        transition: color 0.2s, background 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .single-article__social-link:hover {
        background: rgba(0, 0, 0, 0.05);
        color: #1a202c;
    }

    /* Grid for Recent Posts */
    .recent-posts-list {
        display: grid;
        grid-template-columns: repeat(1, 1fr);
        gap: 2.5rem;
    }

    @media (min-width: 576px) {
        .recent-posts-list {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (min-width: 992px) {
        .recent-posts-list {
            grid-template-columns: repeat(4, 1fr);
        }
    }
</style>

<script>
    (function () {
        'use strict';
        var dotsBtn = document.getElementById('share-dots-btn');
        var dropdown = document.getElementById('sharing-dropdown');
        var copyBtn = document.getElementById('copy-link-btn');

        if (!dotsBtn || !dropdown) return;

        // Toggle dropdown
        dotsBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            var isOpen = dropdown.getAttribute('aria-hidden') === 'false';
            dropdown.setAttribute('aria-hidden', isOpen ? 'true' : 'false');
        });

        // Close on outside click
        document.addEventListener('click', function (e) {
            if (!dropdown.contains(e.target) && e.target !== dotsBtn) {
                dropdown.setAttribute('aria-hidden', 'true');
            }
        });

        // Close on Escape
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                dropdown.setAttribute('aria-hidden', 'true');
            }
        });

        // Copy link securely
        if (copyBtn) {
            copyBtn.addEventListener('click', function () {
                var url = copyBtn.getAttribute('data-url');
                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(url).then(function () {
                        copyBtn.querySelector('span').textContent = 'Copied!';
                        setTimeout(function () {
                            copyBtn.querySelector('span').textContent = 'Copy Link';
                        }, 2000);
                    });
                } else {
                    var ta = document.createElement('textarea');
                    ta.value = url;
                    ta.style.position = 'fixed';
                    ta.style.left = '-9999px';
                    document.body.appendChild(ta);
                    ta.select();
                    try { document.execCommand('copy'); copyBtn.querySelector('span').textContent = 'Copied!'; } catch (err) { }
                    document.body.removeChild(ta);
                    setTimeout(function () { copyBtn.querySelector('span').textContent = 'Copy Link'; }, 2000);
                }
                dropdown.setAttribute('aria-hidden', 'true');
            });
        }
    })();
</script>

<?php get_footer(); ?>