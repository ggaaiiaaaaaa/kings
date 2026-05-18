<?php
/* Single template for the 'jobs' custom post type — auto-applied by WP hierarchy */
if ( ! defined( 'ABSPATH' ) ) {
    require_once 'functions.php';
}

// --- Pull ACF job fields ---
$job_location   = kg_get_field( 'job_location',   'Philippines' );
$job_type       = kg_get_field( 'job_type',        'FULL_TIME' );
$job_salary_min = kg_get_field( 'job_salary_min',  '' );
$job_salary_max = kg_get_field( 'job_salary_max',  '' );
$job_department = kg_get_field( 'job_department',  '' );

// Human-readable employment type label
$job_type_labels = [
    'FULL_TIME'  => 'Full-time',
    'PART_TIME'  => 'Part-time',
    'CONTRACTOR' => 'Contract',
    'TEMPORARY'  => 'Temporary',
    'OTHER'      => 'Remote',
];
$job_type_label = $job_type_labels[ $job_type ] ?? $job_type;

// Salary display string
$salary_display = '';
if ( $job_salary_min && $job_salary_max ) {
    $salary_display = '₱' . number_format( $job_salary_min ) . ' – ₱' . number_format( $job_salary_max ) . '/mo';
} elseif ( $job_salary_min ) {
    $salary_display = 'From ₱' . number_format( $job_salary_min ) . '/mo';
}

// --- Page meta ---
$page_title       = get_the_title() . ' | Kings Group Careers';
$page_description = wp_strip_all_tags( get_the_excerpt() ) ?: 'Apply for ' . get_the_title() . ' at Kings Group Cooperative.';

// --- JSON-LD: JobPosting schema for Google for Jobs ---
$page_schema = [
    '@context'       => 'https://schema.org',
    '@type'          => 'JobPosting',
    'title'          => get_the_title(),
    'description'    => wp_strip_all_tags( get_the_content() ),
    'datePosted'     => get_the_date( 'Y-m-d' ),
    'validThrough'   => date( 'Y-m-d', strtotime( '+90 days' ) ),
    'employmentType' => $job_type,
    'jobLocation'    => [
        '@type'   => 'Place',
        'address' => [
            '@type'           => 'PostalAddress',
            'addressLocality' => $job_location,
            'addressCountry'  => 'PH',
        ],
    ],
    'hiringOrganization' => [
        '@id'    => 'https://kingsgroup.com.ph/#organization',
        '@type'  => 'Organization',
        'name'   => 'Kings Group Cooperative',
        'sameAs' => 'https://kingsgroup.com.ph/',
    ],
    'applicantLocationRequirements' => [
        '@type' => 'Country',
        'name'  => 'Philippines',
    ],
];

get_header();
?>

<!-- Breadcrumb -->
<nav aria-label="Breadcrumb" class="breadcrumb-nav">
    <div class="container">
        <a href="<?php echo esc_url( home_url('/') ); ?>">Home</a>
        <span class="sep">›</span>
        <a href="<?php echo esc_url( home_url('/our-jobs/') ); ?>">Our Jobs</a>
        <span class="sep">›</span>
        <span class="current"><?php echo esc_html( get_the_title() ); ?></span>
    </div>
</nav>

<!-- Job Hero -->
<section class="page-hero job-single-hero">
    <div class="container">
        <div class="job-meta-badges animate-on-scroll">
            <span class="type-badge"><?php echo esc_html( $job_type_label ); ?></span>
            <?php if ( $job_location ) : ?>
                <span class="meta-pill"><?php echo kg_icon('location', 'inline-icon'); ?> <?php echo esc_html( $job_location ); ?></span>
            <?php endif; ?>
            <?php if ( $job_department ) : ?>
                <span class="meta-pill"><?php echo kg_icon('building', 'inline-icon'); ?> <?php echo esc_html( $job_department ); ?></span>
            <?php endif; ?>
        </div>
        <h1 class="animate-on-scroll"><?php the_title(); ?></h1>
        <p class="hero-date animate-on-scroll">Posted on <?php echo get_the_date(); ?></p>
    </div>
</section>

<!-- Job Body -->
<section class="section section-bg-white">
    <div class="container">
        <div class="job-single-layout">

            <!-- Left: Full Job Description -->
            <div class="job-main-content animate-on-scroll">
                <div class="post-content">
                    <?php the_content(); ?>
                </div>

                <!-- Shared CTA for Mobile -->
                <div class="mobile-apply-cta">
                    <a href="<?php echo esc_url( add_query_arg('role', urlencode(get_the_title()), home_url('/careers/#apply')) ); ?>" class="btn btn-primary btn-block">Apply for this Role</a>
                </div>
            </div>

            <!-- Right: Sticky Sidebar -->
            <aside class="job-sidebar animate-on-scroll">
                <!-- Apply CTA Card -->
                <div class="sidebar-card glass-cta">
                    <h3>Interested?</h3>
                    <p>Submit your application in under 2 minutes — just your CV and basic details.</p>
                    <a href="<?php echo esc_url( add_query_arg('role', urlencode(get_the_title()), home_url('/careers/#apply')) ); ?>" class="btn btn-primary btn-block">Apply Now</a>
                </div>

                <!-- Job Details Card -->
                <div class="sidebar-card job-info">
                    <h4>Job Details</h4>
                    <div class="info-list">
                        <div class="info-item">
                            <label>Employment Type</label>
                            <div class="value"><?php echo esc_html( $job_type_label ); ?></div>
                        </div>
                        <?php if ( $job_duration && in_array($job_type, ['CONTRACTOR', 'TEMPORARY']) ) : ?>
                        <div class="info-item">
                            <label>Contract Duration</label>
                            <div class="value highlight"><?php echo esc_html( $job_duration ); ?></div>
                        </div>
                        <?php endif; ?>
                        <?php if ( $job_location ) : ?>
                        <div class="info-item">
                            <label>Location</label>
                            <div class="value"><?php echo esc_html( $job_location ); ?></div>
                        </div>
                        <?php endif; ?>
                        <?php if ( $salary_display ) : ?>
                        <div class="info-item">
                            <label>Salary Range</label>
                            <div class="value highlight"><?php echo esc_html( $salary_display ); ?></div>
                        </div>
                        <?php endif; ?>
                        <?php if ( $job_department ) : ?>
                        <div class="info-item">
                            <label>Department</label>
                            <div class="value"><?php echo esc_html( $job_department ); ?></div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Security Note -->
                <p class="sidebar-note">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                        <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                    </svg>
                    Kings Group will never ask for payment during the recruitment process.
                </p>
            </aside>

        </div>
    </div>
</section>

<style>
    /* Breadcrumb */
    .breadcrumb-nav {
        padding: 1.5rem 0;
        background: var(--bg-light);
        border-bottom: 1px solid var(--border-color);
    }
    .breadcrumb-nav .container {
        font-size: 0.85rem;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .breadcrumb-nav a {
        color: var(--main-blue);
        text-decoration: none;
        transition: color 0.3s;
    }
    .breadcrumb-nav a:hover { color: var(--main-blue-light); }
    .breadcrumb-nav .sep { opacity: 0.5; }
    .breadcrumb-nav .current { color: var(--text-dark); font-weight: 500; }

    /* Hero */
    .job-single-hero {
        min-height: 40vh;
        padding: 6rem 0;
        background-color: var(--main-blue);
        background-image: var(--gradient-hero);
    }
    .job-meta-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }
    .type-badge {
        background: rgba(0, 208, 156, 0.2);
        color: var(--sec-accent-green);
        padding: 0.4rem 1.2rem;
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        border-radius: 4px;
    }
    .meta-pill {
        background: rgba(255, 255, 255, 0.1);
        color: rgba(255, 255, 255, 0.9);
        padding: 0.4rem 1rem;
        font-size: 0.8rem;
        border-radius: 4px;
        backdrop-filter: blur(4px);
    }
    .hero-date {
        color: rgba(255, 255, 255, 0.6);
        font-size: 1rem;
        margin-top: 1rem;
    }

    /* Layout */
    .job-single-layout {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 4rem;
        align-items: start;
    }
    .post-content {
        color: var(--text-body);
        line-height: 1.8;
        font-size: 1.1rem;
    }
    .post-content h2, .post-content h3 {
        color: var(--text-dark);
        margin: 2rem 0 1rem;
    }
    .post-content ul {
        margin-bottom: 2rem;
        padding-left: 1.25rem;
    }
    .post-content li { margin-bottom: 0.5rem; }

    /* Sidebar */
    .job-sidebar {
        position: sticky;
        top: 8rem;
    }
    .sidebar-card {
        border-radius: 16px;
        margin-bottom: 1.5rem;
        overflow: hidden;
    }
    .glass-cta {
        background: var(--glass-mid-bg);
        backdrop-filter: var(--glass-mid-blur);
        border: 1px solid var(--glass-mid-border);
        padding: 2.5rem;
        box-shadow: var(--glass-mid-shadow);
    }
    .glass-cta h3 { font-size: 1.25rem; margin-bottom: 0.75rem; }
    .glass-cta p { font-size: 0.95rem; color: var(--text-muted); margin-bottom: 2rem; line-height: 1.5; }
    
    .job-info {
        background: var(--bg-light);
        border: 1px solid var(--border-color);
        padding: 2rem;
    }
    .job-info h4 {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: var(--text-muted);
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border-color);
    }
    .info-list { display: flex; flex-direction: column; gap: 1.25rem; }
    .info-item label {
        display: block;
        font-size: 0.7rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
    }
    .info-item .value { font-weight: 700; color: var(--text-dark); font-size: 1rem; }
    .info-item .value.highlight { color: var(--sec-accent-green); }

    .sidebar-note {
        font-size: 0.8rem;
        color: var(--text-muted);
        display: flex;
        gap: 0.5rem;
        line-height: 1.4;
        padding: 0 1rem;
    }
    .sidebar-note svg { flex-shrink: 0; margin-top: 2px; }

    .btn-block { display: block; text-align: center; width: 100%; padding: 1rem; }
    .mobile-apply-cta { display: none; margin-top: 3rem; padding-top: 2rem; border-top: 1px solid var(--border-color); }

    @media (max-width: 992px) {
        .job-single-layout { grid-template-columns: 1fr; gap: 3rem; }
        .job-sidebar { position: static; }
        .mobile-apply-cta { display: block; }
    }
</style>

<?php get_footer(); ?>