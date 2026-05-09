<?php
if ( ! defined( 'ABSPATH' ) ) {
    require_once 'functions.php';
}
/**
 * The template for displaying all single posts
 */

get_header();
?>

    <main id="main-content">
        <?php
        while ( have_posts() ) :
            the_post();
            ?>
            <section class="section">
                <div class="container animate-on-scroll">
                    <div style="margin-bottom: 1rem; color: var(--main-blue); font-weight: 600; text-transform: uppercase; font-size: 0.85rem;">
                        <?php the_date(); ?>
                    </div>
                    <h1 class="section-title" style="text-align: left;"><?php the_title(); ?></h1>
                    <div class="post-content" style="margin-top: 2rem; line-height: 1.8; color: var(--text-body);">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="post-thumbnail" style="margin-bottom: 2rem; border-radius: 12px; overflow: hidden; box-shadow: var(--shadow-md);">
                                <?php the_post_thumbnail('large', array('style' => 'width:100%; height:auto; display:block;')); ?>
                            </div>
                        <?php endif; ?>
                        <?php the_content(); ?>
                    </div>
                </div>
            </section>
            <?php
        endwhile;
        ?>
    </main>

<?php
get_footer();


