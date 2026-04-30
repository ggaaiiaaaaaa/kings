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
