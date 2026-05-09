<?php
if ( ! defined( 'ABSPATH' ) ) {
    require_once 'functions.php';
}
/**
 * The template for displaying 404 pages (not found)
 */

get_header();
?>

    <main id="main-content">
        <section class="section" style="min-height: 70vh; display: flex; align-items: center; justify-content: center; text-align: center;">
            <div class="container animate-on-scroll">
                <div style="font-size: 6rem; font-weight: 800; color: var(--main-blue); opacity: 0.15; line-height: 1;">404</div>
                <h1 class="section-title" style="margin-top: -2rem;">Page Not Found</h1>
                <p style="font-size: 1.15rem; color: var(--text-muted); max-width: 500px; margin: 1.5rem auto 2.5rem;">
                    The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.
                </p>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary" style="padding: 1rem 2.5rem;">
                    Return to Homepage
                </a>
            </div>
        </section>
    </main>

<?php
get_footer();


