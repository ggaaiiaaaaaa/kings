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

// Add salary to schema only if provided
if ( $job_salary_min ) {
    $page_schema['baseSalary'] = [
        '@type'    => 'MonetaryAmount',
        'currency' => 'PHP',
        'value'    => [
            '@type'    => 'QuantitativeValue',
            'minValue' => (float) $job_salary_min,
            'maxValue' => $job_salary_max ? (float) $job_salary_max : (float) $job_salary_min,
            'unitText' => 'MONTH',
        ],
    ];
}

get_header();
?>

    <!-- Breadcrumb -->
    <nav aria-label="Breadcrumb" style="padding:1rem 0;background:var(--bg-light);border-bottom:1px solid var(--border-color);">
        <div class="container" style="font-size:0.85rem;color:var(--text-muted);">
            <a href="<?php echo esc_url( home_url('/') ); ?>" style="color:var(--main-blue);text-decoration:none;">Home</a>
            <span style="margin:0 0.5rem;">›</span>
            <a href="<?php echo esc_url( home_url('/jobs/') ); ?>" style="color:var(--main-blue);text-decoration:none;">Jobs</a>
            <span style="margin:0 0.5rem;">›</span>
            <span><?php echo esc_html( get_the_title() ); ?></span>
        </div>
    </nav>

    <!-- Job Hero -->
    <section class="page-hero" style="padding:4rem 0 3rem;">
        <div class="container">
            <div style="display:flex;flex-wrap:wrap;gap:0.75rem;margin-bottom:1.25rem;">
                <span style="background:rgba(0,208,156,0.15);color:var(--sec-accent-green);padding:0.3rem 0.9rem;font-size:0.8rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;"><?php echo esc_html( $job_type_label ); ?></span>
                <?php if ( $job_location ) : ?>
                <span style="background:rgba(255,255,255,0.1);color:rgba(255,255,255,0.85);padding:0.3rem 0.9rem;font-size:0.8rem;">📍 <?php echo esc_html( $job_location ); ?></span>
                <?php endif; ?>
                <?php if ( $job_department ) : ?>
                <span style="background:rgba(255,255,255,0.1);color:rgba(255,255,255,0.85);padding:0.3rem 0.9rem;font-size:0.8rem;"><?php echo esc_html( $job_department ); ?></span>
                <?php endif; ?>
            </div>
            <h1 style="font-size:clamp(1.75rem,4vw,2.75rem);font-weight:800;color:#fff;margin-bottom:0.75rem;"><?php the_title(); ?></h1>
            <p style="color:rgba(255,255,255,0.7);font-size:0.9rem;">Posted <?php echo get_the_date(); ?></p>
        </div>
    </section>

    <!-- Job Body -->
    <section style="padding:4rem 0;background:var(--bg-white);">
        <div class="container">
            <div style="display:grid;grid-template-columns:1fr 320px;gap:3rem;align-items:start;">

                <!-- Left: Full Job Description -->
                <div class="post-content" style="line-height:1.85;color:var(--text-body);font-size:1.05rem;">
                    <?php the_content(); ?>
                </div>

                <!-- Right: Sticky Sidebar -->
                <aside style="position:sticky;top:6rem;">
                    <!-- Apply CTA Card -->
                    <div style="background:var(--glass-mid-bg);border:1px solid var(--glass-mid-border);backdrop-filter:var(--glass-mid-blur);padding:2rem;margin-bottom:1.5rem;box-shadow:var(--glass-mid-shadow);">
                        <h3 style="font-size:1.15rem;color:var(--text-dark);margin-bottom:0.5rem;">Interested in this role?</h3>
                        <p style="color:var(--text-muted);font-size:0.9rem;margin-bottom:1.5rem;">Submit your application in under 2 minutes — just your CV and basic details.</p>
                        <a href="<?php echo esc_url( home_url('/careers/#apply') ); ?>" class="btn btn-primary" style="display:block;text-align:center;padding:1rem;">Apply for this Role</a>
                    </div>

                    <!-- Job Details Card -->
                    <div style="background:var(--bg-light);border:1px solid var(--border-color);padding:1.75rem;">
                        <h4 style="font-size:0.8rem;text-transform:uppercase;letter-spacing:0.5px;color:var(--text-muted);margin-bottom:1.25rem;">Job Details</h4>
                        <div style="display:flex;flex-direction:column;gap:1rem;">
                            <div>
                                <div style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.3px;margin-bottom:0.2rem;">Employment Type</div>
                                <div style="font-weight:600;color:var(--text-dark);"><?php echo esc_html( $job_type_label ); ?></div>
                            </div>
                            <?php if ( $job_location ) : ?>
                            <div>
                                <div style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.3px;margin-bottom:0.2rem;">Location</div>
                                <div style="font-weight:600;color:var(--text-dark);"><?php echo esc_html( $job_location ); ?></div>
                            </div>
                            <?php endif; ?>
                            <?php if ( $salary_display ) : ?>
                            <div>
                                <div style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.3px;margin-bottom:0.2rem;">Salary Range</div>
                                <div style="font-weight:600;color:var(--sec-accent-green);"><?php echo esc_html( $salary_display ); ?></div>
                            </div>
                            <?php endif; ?>
                            <?php if ( $job_department ) : ?>
                            <div>
                                <div style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.3px;margin-bottom:0.2rem;">Department</div>
                                <div style="font-weight:600;color:var(--text-dark);"><?php echo esc_html( $job_department ); ?></div>
                            </div>
                            <?php endif; ?>
                            <div>
                                <div style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.3px;margin-bottom:0.2rem;">Date Posted</div>
                                <div style="font-weight:600;color:var(--text-dark);"><?php echo get_the_date(); ?></div>
                            </div>
                        </div>
                    </div>
                </aside>

            </div>
        </div>
    </section>

    <!-- Responsive: collapse sidebar on mobile -->
    <style>
        @media (max-width: 768px) {
            .container > div[style*="grid-template-columns:1fr 320px"] {
                grid-template-columns: 1fr !important;
            }
            .container > div[style*="grid-template-columns:1fr 320px"] aside {
                position: static !important;
            }
        }
    </style>

<?php get_footer(); ?>
