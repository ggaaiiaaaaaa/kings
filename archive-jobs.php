<?php
/* Archive template for the 'jobs' custom post type — URL: /jobs/ */
if ( ! defined( 'ABSPATH' ) ) {
    require_once 'functions.php';
}
$page_title       = 'Open Positions | Kings Group Careers';
$page_description = 'Browse current job openings at Kings Group Cooperative. Full-time, part-time, and contract roles across the Philippines.';

get_header();
?>

    <!-- Hero -->
    <section class="page-hero">
        <div class="container text-center">
            <h1>Open Positions</h1>
            <p>Join a worker-owned cooperative where your growth is everyone's mission.</p>
            <a href="<?php echo esc_url( home_url('/careers/#apply') ); ?>" class="btn btn-primary" style="margin-top:1.5rem;display:inline-block;padding:0.85rem 2rem;">Apply Now — Upload Your CV</a>
        </div>
    </section>

    <!-- Jobs Grid -->
    <section style="padding:5rem 0;background:var(--bg-light);">
        <div class="container">

            <?php if ( have_posts() ) : ?>

                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:1.5rem;">
                    <?php while ( have_posts() ) : the_post();
                        $job_location  = kg_get_field('job_location', 'Philippines');
                        $job_type      = kg_get_field('job_type', 'FULL_TIME');
                        $job_type_labels = [
                            'FULL_TIME'  => 'Full-time',
                            'PART_TIME'  => 'Part-time',
                            'CONTRACTOR' => 'Contract',
                            'TEMPORARY'  => 'Temporary',
                            'OTHER'      => 'Remote',
                        ];
                        $job_type_label = $job_type_labels[$job_type] ?? $job_type;
                        $excerpt = wp_trim_words( get_the_excerpt() ?: get_the_content(), 20, '…' );
                    ?>
                    <div style="background:var(--glass-mid-bg);border:1px solid var(--glass-mid-border);backdrop-filter:var(--glass-mid-blur);padding:2rem;box-shadow:var(--glass-mid-shadow);display:flex;flex-direction:column;transition:var(--transition);">
                        <!-- Type badge -->
                        <span style="display:inline-block;background:rgba(0,208,156,0.12);color:var(--sec-accent-green);padding:0.25rem 0.75rem;font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:1rem;align-self:flex-start;"><?php echo esc_html( $job_type_label ); ?></span>

                        <h3 style="font-size:1.1rem;font-weight:700;color:var(--text-dark);margin-bottom:0.4rem;">
                            <a href="<?php the_permalink(); ?>" style="color:inherit;text-decoration:none;"><?php the_title(); ?></a>
                        </h3>

                        <?php if ( $job_location ) : ?>
                        <p style="font-size:0.85rem;color:var(--text-muted);margin-bottom:0.75rem;">📍 <?php echo esc_html( $job_location ); ?></p>
                        <?php endif; ?>

                        <?php if ( $excerpt ) : ?>
                        <p style="color:var(--text-body);font-size:0.9rem;line-height:1.6;margin-bottom:1.5rem;flex:1;"><?php echo esc_html( $excerpt ); ?></p>
                        <?php endif; ?>

                        <a href="<?php the_permalink(); ?>" class="btn btn-outline" style="align-self:flex-start;padding:0.65rem 1.5rem;font-size:0.9rem;">View Position →</a>
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

                <!-- Empty state -->
                <div style="text-align:center;padding:5rem 2rem;">
                    <div style="font-size:4rem;opacity:0.15;margin-bottom:1rem;">💼</div>
                    <h2 class="section-title" style="margin-bottom:1rem;">No Open Positions Right Now</h2>
                    <p style="color:var(--text-muted);font-size:1.05rem;max-width:480px;margin:0 auto 2rem;">We're not actively hiring at the moment, but we'd love to hear from talented people. Send us your CV anyway.</p>
                    <a href="<?php echo esc_url( home_url('/careers/#apply') ); ?>" class="btn btn-primary" style="padding:0.85rem 2rem;">Send Your CV</a>
                </div>

            <?php endif; ?>

        </div>
    </section>

<?php get_footer(); ?>
