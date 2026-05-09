<?php
if ( ! defined( 'ABSPATH' ) ) {
    require_once 'functions.php';
}
/**
 * The template for displaying all pages
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
                    <h1 class="section-title"><?php the_title(); ?></h1>
                    <div class="page-content" style="margin-top: 2rem; line-height: 1.8; color: var(--text-body);">
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


