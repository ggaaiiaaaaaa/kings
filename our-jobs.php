<?php
/* Template Name: Our Jobs */
if (!defined('ABSPATH')) {
    require_once 'functions.php';
}
$page_title = 'Our Jobs | Kings Group Careers';
$page_description = 'Browse open positions at Kings Group Cooperative. Filter by Full-Time, Part-Time, Contract, and Remote roles across the Philippines.';

get_header();
?>

<!-- Hero -->
<?php
$hero_headline = kg_get_field('jobs_hero_headline', 'Our Jobs');
$hero_desc = kg_get_field('jobs_hero_desc', 'Find your next opportunity at one of the Philippines\' most people-first cooperatives.');
$hero_bg = kg_get_field('jobs_hero_bg', 'https://images.unsplash.com/photo-1521737711867-e3b97375f902?auto=format&fit=crop&w=2000&q=80');
?>
<section class="page-hero"
    style="background-image: linear-gradient(rgba(10, 37, 64, 0.8), rgba(10, 37, 64, 0.8)), url('<?php echo esc_url($hero_bg); ?>');">
    <div class="container text-center">
        <h1 class="animate-on-scroll"><?php echo esc_html($hero_headline); ?></h1>
        <p class="animate-on-scroll"><?php echo esc_html($hero_desc); ?></p>

        <!-- Filter Bar -->
        <div class="jobs-filter-container animate-on-scroll">
            <div id="job-filters" class="glass-filter-bar">
                <button class="job-filter-btn active" data-filter="all">All Positions</button>
                <button class="job-filter-btn" data-filter="FULL_TIME">Full-Time</button>
                <button class="job-filter-btn" data-filter="PART_TIME">Part-Time</button>
                <button class="job-filter-btn" data-filter="CONTRACTOR">Contract</button>
                <button class="job-filter-btn" data-filter="OTHER">Remote</button>
            </div>
        </div>
    </div>
</section>

<!-- Jobs Grid Section -->
<section class="section section-bg-light" style="padding: 6rem 0;">
    <div class="container">

        <?php
        // Query all published jobs, excluding filled ones
        $jobs_query = new WP_Query(array(
            'post_type' => 'jobs',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'date',
            'order' => 'DESC'
        ));

        $job_type_labels = array(
            'FULL_TIME' => 'Full-Time',
            'PART_TIME' => 'Part-Time',
            'CONTRACTOR' => 'Contract',
            'TEMPORARY' => 'Temporary',
            'OTHER' => 'Remote',
        );

        $badge_colors = array(
            'FULL_TIME' => array('bg' => 'rgba(10,37,64,0.1)', 'color' => 'var(--main-blue)'),
            'PART_TIME' => array('bg' => 'rgba(255,197,0,0.15)', 'color' => '#a07800'),
            'CONTRACTOR' => array('bg' => 'rgba(0,208,156,0.12)', 'color' => 'var(--sec-accent-green)'),
            'TEMPORARY' => array('bg' => 'rgba(147,51,234,0.1)', 'color' => '#7c22bf'),
            'OTHER' => array('bg' => 'rgba(239,68,68,0.1)', 'color' => '#dc2626'),
        );
        ?>

        <?php if ($jobs_query->have_posts()): ?>

            <div class="jobs-meta-header animate-on-scroll">
                <div id="results-count" class="results-count">
                    Showing <strong id="visible-count"><?php echo $jobs_query->post_count; ?></strong> opportunities
                </div>
            </div>

            <div id="jobs-grid" class="jobs-grid animate-on-scroll">

                <?php while ($jobs_query->have_posts()):
                    $jobs_query->the_post();
                    $job_type = kg_get_field('job_type', 'FULL_TIME', get_the_ID());
                    $job_location = kg_get_field('job_location', 'Philippines', get_the_ID());
                    $job_dept = kg_get_field('job_department', '', get_the_ID());
                    $job_duration = kg_get_field('job_duration', '', get_the_ID());
                    $target_hc = (int) kg_get_field('job_target_headcount', 0, get_the_ID());
                    $filled_hc = (int) kg_get_field('job_filled_headcount', 0, get_the_ID());

                    // Skip jobs that are fully filled
                    if ($target_hc > 0 && $filled_hc >= $target_hc) {
                        continue;
                    }

                    $type_label = $job_type_labels[$job_type] ?? $job_type;
                    $badge = $badge_colors[$job_type] ?? $badge_colors['FULL_TIME'];
                    $excerpt = wp_trim_words(get_the_excerpt() ?: get_the_content(), 20, '…');

                    // Priority 1: ACF Custom Image, Priority 2: Featured Image
                    $custom_img = kg_get_field('job_card_image', '', get_the_ID());
                    $thumb_url = $custom_img ? $custom_img : get_the_post_thumbnail_url(get_the_ID(), 'kg-card');

                    $apply_url = esc_url(add_query_arg('role', urlencode(get_the_title()), home_url('/careers/#apply')));

                    $slots_left = $target_hc > 0 ? ($target_hc - $filled_hc) : null;
                    ?>
                    <article class="job-glass-card" data-type="<?php echo esc_attr($job_type); ?>">

                        <!-- Card Media -->
                        <div class="job-card-media">
                            <?php if ($thumb_url): ?>
                                <img src="<?php echo esc_url($thumb_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>"
                                    loading="lazy">
                            <?php else: ?>
                                <div class="job-card-placeholder">
                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.2">
                                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2" />
                                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16" />
                                    </svg>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Card Content -->
                        <div class="job-card-content">
                            <div class="job-card-meta">
                                <?php if ($job_location): ?>
                                    <span class="meta-item location">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2">
                                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                                            <circle cx="12" cy="10" r="3" />
                                        </svg>
                                        <?php echo esc_html($job_location); ?>
                                    </span>
                                <?php endif; ?>
                                <?php if ($job_dept): ?>
                                    <span class="meta-separator">·</span>
                                    <span class="meta-item department"><?php echo esc_html($job_dept); ?></span>
                                <?php endif; ?>
                                <?php if ($job_duration && in_array($job_type, ['CONTRACTOR', 'TEMPORARY'])): ?>
                                    <span class="meta-separator">·</span>
                                    <span class="meta-item duration">⏱️ <?php echo esc_html($job_duration); ?></span>
                                <?php endif; ?>
                            </div>

                            <h3 class="job-card-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>

                            <?php if ($excerpt): ?>
                                <p class="job-card-excerpt"><?php echo esc_html($excerpt); ?></p>
                            <?php endif; ?>

                            <?php if ($slots_left !== null): ?>
                                <div class="job-card-slots">
                                    <span class="slots-icon"></span>
                                    <?php echo $slots_left; ?> slot<?php echo $slots_left !== 1 ? 's' : ''; ?> remaining
                                </div>
                            <?php endif; ?>

                            <div class="job-card-actions">
                                <a href="<?php echo $apply_url; ?>" class="btn btn-primary btn-sm">Apply Now</a>
                                <a href="<?php the_permalink(); ?>" class="btn btn-outline btn-sm">Details</a>
                            </div>
                        </div>
                    </article>
                <?php endwhile;
                wp_reset_postdata(); ?>

            </div>

            <!-- Empty filter state -->
            <div id="no-results" style="display:none;" class="empty-state-glass animate-on-scroll">
                <div class="empty-icon"><?php echo kg_icon('search'); ?></div>
                <h2 class="section-title">No positions found</h2>
                <p class="section-subtitle">Try a different category or reach out to our team directly.</p>
            </div>

        <?php else: ?>
            <!-- No jobs posted -->
            <div class="empty-state-glass animate-on-scroll">
                <div class="empty-icon"><?php echo kg_icon('briefcase'); ?></div>
                <h2 class="section-title">Hiring Soon</h2>
                <p class="section-subtitle">We are currently updating our job board. Check back soon for new opportunities!
                </p>
                <a href="<?php echo esc_url(home_url('/careers/#apply')); ?>" class="btn btn-primary">Join Talent Pool</a>
            </div>
        <?php endif; ?>

    </div>
</section>

<!-- Final CTA -->
<section class="section section-bg-white" style="border-top: 1px solid var(--border-color);">
    <div class="container text-center">
        <div class="cta-glass-box animate-on-scroll">
            <h2 class="section-title">Don't see the right fit?</h2>
            <p class="section-subtitle" style="margin-bottom: 2rem;">Upload your CV and we'll keep you in mind for
                upcoming roles that match your expertise.</p>
            <a href="<?php echo esc_url(home_url('/careers/#apply')); ?>" class="btn btn-gold btn-lg">Submit Open
                Application</a>
        </div>
    </div>
</section>

<style>
    /* Filter Bar Styling */
    .jobs-filter-container {
        margin-top: 3rem;
        display: flex;
        justify-content: center;
    }

    .glass-filter-bar {
        display: inline-flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        padding: 0.5rem;
        background: var(--glass-strong-bg);
        backdrop-filter: var(--glass-strong-blur);
        border: 1px solid var(--glass-strong-border);
        border-radius: 12px;
    }

    .job-filter-btn {
        padding: 0.75rem 1.5rem;
        border: none;
        background: transparent;
        color: rgba(255, 255, 255, 0.7);
        font-weight: 600;
        font-size: 0.9rem;
        border-radius: 8px;
        cursor: pointer;
        transition: var(--transition);
        font-family: var(--font-body);
    }

    .job-filter-btn:hover {
        color: #fff;
        background: rgba(255, 255, 255, 0.05);
    }

    .job-filter-btn.active {
        background: var(--neutral-yellow);
        color: var(--main-blue);
        box-shadow: var(--shadow-sm);
    }

    /* Grid & Cards */
    .jobs-meta-header {
        margin-bottom: 2.5rem;
        text-align: left;
    }

    .results-count {
        font-size: 0.95rem;
        color: var(--text-muted);
        letter-spacing: 0.5px;
    }

    .jobs-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
        gap: 2rem;
    }

    .job-glass-card {
        background: var(--glass-mid-bg);
        backdrop-filter: var(--glass-mid-blur);
        border: 1px solid var(--glass-mid-border);
        box-shadow: var(--glass-mid-shadow);
        border-radius: var(--card-radius-lg);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: var(--transition);
    }

    .job-glass-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-hover);
        border-color: var(--main-blue-light);
    }

    .job-card-media {
        height: 200px;
        position: relative;
        overflow: hidden;
    }

    .job-card-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }

    .job-glass-card:hover .job-card-media img {
        transform: scale(1.1);
    }

    .job-card-placeholder {
        width: 100%;
        height: 100%;
        background: var(--gradient-hero);
        display: flex;
        align-items: center;
        justify-content: center;
        color: rgba(255, 255, 255, 0.2);
    }

    .job-card-content {
        padding: 2rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .job-card-meta {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.75rem;
        font-size: 0.8rem;
        color: var(--text-muted);
        font-weight: 500;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .job-card-title {
        font-size: 1.25rem;
        margin-bottom: 1rem;
        line-height: 1.3;
    }

    .job-card-title a {
        color: var(--text-dark);
        text-decoration: none;
    }

    .job-card-excerpt {
        font-size: 0.95rem;
        color: var(--text-body);
        line-height: 1.6;
        margin-bottom: 1.5rem;
        flex: 1;
    }

    .job-card-slots {
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--neutral-yellow);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .slots-icon {
        width: 8px;
        height: 8px;
        background: var(--neutral-yellow);
        border-radius: 50%;
        box-shadow: 0 0 10px var(--neutral-yellow);
    }

    .job-card-actions {
        display: flex;
        gap: 0.75rem;
    }

    .btn-sm {
        padding: 0.6rem 1.25rem;
        font-size: 0.85rem;
        flex: 1;
    }

    /* States */
    .empty-state-glass {
        padding: 6rem 2rem;
        text-align: center;
        background: var(--glass-mid-bg);
        backdrop-filter: var(--glass-mid-blur);
        border: 1px solid var(--glass-mid-border);
        border-radius: var(--card-radius-lg);
    }

    .empty-icon {
        font-size: 4rem;
        margin-bottom: 2rem;
        opacity: 0.2;
    }

    .cta-glass-box {
        padding: 4rem;
        background: var(--gradient-tech-subtle);
        border: 1px solid var(--border-color);
        border-radius: 24px;
    }

    @media (max-width: 768px) {
        .jobs-grid {
            grid-template-columns: 1fr;
        }

        .glass-filter-bar {
            width: 100%;
            justify-content: center;
        }

        .cta-glass-box {
            padding: 2rem;
        }
    }
</style>

<script>
    (function () {
        const btns = document.querySelectorAll('.job-filter-btn');
        const cards = document.querySelectorAll('.job-glass-card');
        const noRes = document.getElementById('no-results');
        const grid = document.getElementById('jobs-grid');
        const count = document.getElementById('visible-count');

        btns.forEach(function (btn) {
            btn.addEventListener('click', function () {
                const filter = this.dataset.filter;

                // UI Reset
                btns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                // Filtering Logic
                let visible = 0;
                cards.forEach(function (card) {
                    if (filter === 'all' || card.dataset.type === filter) {
                        card.style.display = 'flex';
                        visible++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                // Results Feedback
                if (count) count.textContent = visible;
                if (noRes && grid) {
                    noRes.style.display = visible === 0 ? 'block' : 'none';
                    grid.style.display = visible === 0 ? 'none' : 'grid';
                }
            });
        });
    })();
</script>

<?php get_footer(); ?>Content = visible;
if (noRes && grid) {
noRes.style.display = visible === 0 ? 'block' : 'none';
grid.style.display = visible === 0 ? 'none' : 'grid';
}
});
});
})();
</script>

<?php get_footer(); ?>