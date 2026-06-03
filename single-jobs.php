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
    'CONTRACTOR' => 'Remote',
    'OTHER'      => 'Other',
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

// Get work setup field or parse from title as fallback
$work_setup = kg_get_field( 'job_work_setup', '' );
if ( empty( $work_setup ) ) {
    $title_lower = strtolower(get_the_title());
    $work_setup = 'WFO'; // default
    if (strpos($title_lower, 'wfh') !== false || strpos($title_lower, 'remote') !== false || $job_type === 'OTHER' || $job_type === 'CONTRACTOR') {
        $work_setup = 'WFH';
    } elseif (strpos($title_lower, 'hybrid') !== false) {
        $work_setup = 'Hybrid';
    }
}

// Convert work setup abbreviation to full human-readable label
$work_setup_labels = [
    'WFO'    => 'Office-Based (WFO)',
    'WFH'    => 'Home-Based (WFH)',
    'Hybrid' => 'Hybrid Setup',
];
$work_setup_label = $work_setup_labels[ $work_setup ] ?? $work_setup;

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

<!-- Job Body — Dual Column Layout -->
<section class="section section-bg-white" style="padding: 6rem 0 5rem 0;">
    <div class="container">
        
        <div class="job-single-grid">
            
            <!-- Left Column: Content -->
            <div class="job-main-column">
                
                <!-- Elegant Job Detail Header (Light Theme / Minimalist) -->
                <div class="job-detail-header">
                    <h1 class="job-detail-title"><?php the_title(); ?></h1>
                    
                    <div class="job-detail-badges animate-on-scroll">
                        <span class="badge badge-date">
                            <svg class="inline-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                            Posted <?php echo get_the_date('M j, Y'); ?>
                        </span>
                    </div>
                </div>

                <!-- Job Details Specs Grid (Borderless & Premium) -->
                <div class="job-specs-grid animate-on-scroll">
                    <div class="spec-item">
                        <div class="spec-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                        </div>
                        <div class="spec-meta">
                            <span class="spec-label">Employment Type</span>
                            <span class="spec-value"><?php echo esc_html( $job_type_label ); ?></span>
                        </div>
                    </div>
                    <div class="spec-item">
                        <div class="spec-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                        </div>
                        <div class="spec-meta">
                            <span class="spec-label">Work Setup</span>
                            <span class="spec-value"><?php echo esc_html( $work_setup_label ); ?></span>
                        </div>
                    </div>
                    <?php if ( $job_location ) : ?>
                        <div class="spec-item">
                            <div class="spec-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                            </div>
                            <div class="spec-meta">
                                <span class="spec-label">Location</span>
                                <span class="spec-value"><?php echo esc_html( $job_location ); ?></span>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ( $job_department ) : ?>
                        <div class="spec-item">
                            <div class="spec-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
                            </div>
                            <div class="spec-meta">
                                <span class="spec-label">Department</span>
                                <span class="spec-value"><?php echo esc_html( $job_department ); ?></span>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ( $salary_display ) : ?>
                        <div class="spec-item">
                            <div class="spec-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="6" width="20" height="12" rx="2"></rect><circle cx="12" cy="12" r="2"></circle><path d="M6 12h.01M18 12h.01"></path></svg>
                            </div>
                            <div class="spec-meta">
                                <span class="spec-label">Salary Range</span>
                                <span class="spec-value"><?php echo esc_html( $salary_display ); ?></span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Primary Section: Full Job Description -->
                <div class="job-main-content animate-on-scroll">
                    <div class="post-content">
                        <?php the_content(); ?>
                    </div>
                </div>

                <!-- Cooperative Perks Section (Beautiful borderless layout, no generic cards) -->
                <div class="cooperative-perks-section animate-on-scroll">
                    <h3>Cooperative Perks & Advantages</h3>
                    <div class="perks-grid">
                        <div class="perk-item-inline">
                            <div class="perk-icon-wrapper">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
                            </div>
                            <div class="perk-content-inline">
                                <h4>Annual Dividend Sharing</h4>
                                <p>As a cooperative member, enjoy direct profit-sharing and competitive annual dividend returns.</p>
                            </div>
                        </div>
                        <div class="perk-item-inline">
                            <div class="perk-icon-wrapper">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"></path></svg>
                            </div>
                            <div class="perk-content-inline">
                                <h4>Comprehensive Health Coverage</h4>
                                <p>Premium medical, dental, and life insurance policies ensuring complete peace of mind for you and your family.</p>
                            </div>
                        </div>
                        <div class="perk-item-inline">
                            <div class="perk-icon-wrapper">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                            </div>
                            <div class="perk-content-inline">
                                <h4>Training & Career Academy</h4>
                                <p>Access our extensive academy courses, professional certifications, and regular upskilling bootcamps.</p>
                            </div>
                        </div>
                        <div class="perk-item-inline">
                            <div class="perk-icon-wrapper">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                            </div>
                            <div class="perk-content-inline">
                                <h4>Family & Wellness Support</h4>
                                <p>Tailored allowances, regular wellness days, mental health initiatives, and active lifestyle programs.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Trust & Safety Section -->
                <div class="job-footer-meta animate-on-scroll">
                    <p class="sidebar-note" style="margin: 0 auto;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                        </svg>
                        <span><strong>Trust & Safety:</strong> Kings Group Cooperative will never request payment or bank credentials during any stage of recruitment. Apply securely above.</span>
                    </p>
                </div>
            </div>

            <!-- Right Column: Sticky Sidebar -->
            <div class="job-sidebar-column">
                <div class="job-sticky-sidebar">
                    <div class="glass-sidebar-card">
                        <div class="sidebar-badge-top">
                            <span class="badge-pulse"></span> Open Position
                        </div>
                        
                        <h3 class="sidebar-title">Apply Instantly</h3>
                        <p class="sidebar-desc">Fast-track your application to our hiring coordinators. Form takes under 2 minutes.</p>
                        
                        <div class="sidebar-specs">
                            <div class="sidebar-spec-item">
                                <span class="sidebar-spec-label">Target Role</span>
                                <span class="sidebar-spec-val"><?php the_title(); ?></span>
                            </div>
                            <?php if ( $job_department ) : ?>
                            <div class="sidebar-spec-item">
                                <span class="sidebar-spec-label">Department</span>
                                <span class="sidebar-spec-val"><?php echo esc_html( $job_department ); ?></span>
                            </div>
                            <?php endif; ?>
                            <div class="sidebar-spec-item">
                                <span class="sidebar-spec-label">Employment Type</span>
                                <span class="sidebar-spec-val"><?php echo esc_html( $job_type_label ); ?></span>
                            </div>
                            <?php if ( $salary_display ) : ?>
                            <div class="sidebar-spec-item">
                                <span class="sidebar-spec-label">Salary Estimate</span>
                                <span class="sidebar-spec-val gold-accent"><?php echo esc_html( $salary_display ); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <a href="<?php echo esc_url( add_query_arg('role', get_the_title(), home_url('/careers/')) . '#apply' ); ?>" class="btn-sidebar-apply">
                            Apply Now
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7" /></svg>
                        </a>
                        
                        <div class="sidebar-share">
                            <span class="sidebar-share-lbl">Share this role</span>
                            <div class="sidebar-share-icons">
                                <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode(get_permalink()); ?>" target="_blank" rel="noopener" class="mini-share-btn" title="Share on LinkedIn">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                                </a>
                                <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode(get_the_title() . ' — Apply now!'); ?>&url=<?php echo urlencode(get_permalink()); ?>" target="_blank" rel="noopener" class="mini-share-btn" title="Share on X">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                </a>
                                <a href="mailto:?subject=<?php echo rawurlencode(get_the_title() . ' — Kings Group Careers'); ?>&body=<?php echo rawurlencode('Check out this role: ' . get_permalink()); ?>" class="mini-share-btn" title="Share via Email">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Floating Mobile Sticky Apply Bar (Cloudstaff inspired) -->
<div id="mobile-sticky-apply-bar" class="mobile-sticky-apply-bar">
    <div class="mobile-sticky-info">
        <div class="mobile-sticky-title"><?php the_title(); ?></div>
        <div class="mobile-sticky-meta"><?php echo esc_html( $job_type_label ); ?> · <?php echo esc_html( $work_setup_label ); ?></div>
    </div>
    <a href="<?php echo esc_url( add_query_arg('role', get_the_title(), home_url('/careers/')) . '#apply' ); ?>" class="btn btn-gold btn-sm" style="padding: 0.6rem 1.2rem; font-size: 0.8rem; border-radius: 8px;">Apply Now</a>
</div>

<style>
    /* Job Detail Header (Light / Premium) */
    .job-detail-header {
        padding-bottom: 0.5rem;
        margin-bottom: 0;
        border-bottom: none;
    }


    .job-detail-title {
        font-family: var(--font-header);
        font-size: 2.75rem;
        font-weight: 800;
        color: var(--main-blue);
        line-height: 1.25;
        margin-bottom: 1rem;
        letter-spacing: -0.5px;
    }

    .job-detail-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        align-items: center;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.5rem 1rem;
        font-size: 0.8rem;
        font-weight: 700;
        border-radius: 8px;
        line-height: 1;
        border: 1px solid transparent;
        transition: var(--transition);
    }

    .badge-type {
        background: rgba(10, 37, 64, 0.06);
        color: var(--main-blue);
        border: 1px solid rgba(10, 37, 64, 0.12);
    }

    .badge-setup {
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .setup-wfo {
        background: rgba(10, 37, 64, 0.06);
        color: var(--main-blue);
        border: 1px solid rgba(10, 37, 64, 0.12);
    }

    .setup-wfh {
        background: rgba(255, 197, 0, 0.1);
        color: #8a6500;
        border: 1px solid rgba(255, 197, 0, 0.2);
    }

    .setup-hybrid {
        background: rgba(0, 208, 156, 0.08);
        color: #008765;
        border: 1px solid rgba(0, 208, 156, 0.18);
    }

    .badge-meta {
        background: var(--bg-light);
        color: var(--text-muted);
        border: 1px solid var(--border-color);
        font-weight: 600;
    }

    .badge-salary {
        background: rgba(0, 208, 156, 0.08);
        color: #008765;
        border: 1px solid rgba(0, 208, 156, 0.18);
    }

    .badge-date {
        background: var(--bg-light);
        color: var(--text-light);
        border: 1px solid var(--border-color);
        font-weight: 500;
    }

    .inline-icon {
        flex-shrink: 0;
        stroke-width: 2.25px;
    }

    /* Job Details Specs Grid */
    .job-specs-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1.75rem 1.5rem;
        padding: 1.75rem 0;
        border-top: 1px solid rgba(10, 37, 64, 0.08);
        border-bottom: 1px solid rgba(10, 37, 64, 0.08);
        margin: 0;
    }
    .spec-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        transition: transform 0.2s ease;
    }
    .spec-icon {
        flex-shrink: 0;
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: rgba(10, 37, 64, 0.03);
        color: var(--main-blue);
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid rgba(10, 37, 64, 0.05);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .spec-icon svg {
        width: 17px;
        height: 17px;
    }
    .spec-item:hover .spec-icon {
        background: rgba(255, 209, 102, 0.15);
        color: #b38600;
        border-color: rgba(255, 209, 102, 0.4);
        transform: translateY(-2px);
    }
    .spec-meta {
        display: flex;
        flex-direction: column;
        gap: 0.15rem;
    }
    .spec-label {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: var(--text-muted);
        line-height: 1;
    }
    .spec-value {
        font-size: 1rem;
        font-weight: 700;
        color: var(--main-blue);
        line-height: 1.3;
    }

    /* Premium Grid Layout */
    .job-single-grid {
        display: grid;
        grid-template-columns: 1fr 360px;
        gap: 4rem;
        align-items: start;
        max-width: 1200px;
        margin: 0 auto;
    }

    .job-main-column {
        display: flex;
        flex-direction: column;
        gap: 2.75rem;
    }

    /* Sticky Sidebar */
    .job-sidebar-column {
        position: sticky;
        top: 120px;
    }

    .glass-sidebar-card {
        padding: 2.5rem;
        background: rgba(255, 255, 255, 0.35);
        backdrop-filter: blur(30px) saturate(200%);
        -webkit-backdrop-filter: blur(30px) saturate(200%);
        border-radius: 24px;
        display: flex;
        flex-direction: column;
        gap: 1.75rem;
        position: relative;
        overflow: hidden;
    }

    /* Subtle backdrop glow */
    .glass-sidebar-card::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 209, 102, 0.12) 0%, transparent 60%);
        z-index: 0;
        pointer-events: none;
    }

    .sidebar-badge-top {
        position: relative;
        z-index: 1;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: var(--main-blue);
    }

    .badge-pulse {
        width: 8px;
        height: 8px;
        background-color: var(--neutral-yellow);
        border-radius: 50%;
        display: inline-block;
        animation: pulse-ring 1.5s cubic-bezier(0.215, 0.610, 0.355, 1) infinite;
    }

    @keyframes pulse-ring {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(255, 209, 102, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(255, 209, 102, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(255, 209, 102, 0); }
    }

    .sidebar-title {
        position: relative;
        z-index: 1;
        font-family: var(--font-header);
        font-size: 1.85rem;
        font-weight: 800;
        color: var(--main-blue);
        line-height: 1.2;
        margin: 0;
    }

    .sidebar-desc {
        position: relative;
        z-index: 1;
        font-size: 0.95rem;
        color: var(--text-muted);
        line-height: 1.6;
        margin: 0;
    }

    .sidebar-specs {
        position: relative;
        z-index: 1;
        display: flex;
        flex-direction: column;
        gap: 1.15rem;
        padding: 1.25rem 0;
        border-top: 1px solid rgba(10, 37, 64, 0.06);
        border-bottom: 1px solid rgba(10, 37, 64, 0.06);
    }

    .sidebar-spec-item {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .sidebar-spec-label {
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: var(--text-light);
    }

    .sidebar-spec-val {
        font-size: 0.98rem;
        font-weight: 700;
        color: var(--main-blue);
    }

    .sidebar-spec-val.gold-accent {
        color: #d4a300;
    }

    .btn-sidebar-apply {
        position: relative;
        z-index: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        padding: 1.1rem 2.25rem;
        background: #ffd166;
        color: #0a2540;
        border: none;
        border-radius: 50px;
        font-weight: 700;
        font-size: 1.05rem;
        text-decoration: none;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 8px 25px rgba(255, 209, 102, 0.35);
        width: 100%;
    }

    .btn-sidebar-apply:hover {
        background: #FACC15;
        color: #0a2540;
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(255, 209, 102, 0.5);
    }

    .sidebar-share {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 0.5rem;
    }

    .sidebar-share-lbl {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--text-light);
    }

    .sidebar-share-icons {
        display: flex;
        gap: 0.6rem;
    }

    .mini-share-btn {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: rgba(10, 37, 64, 0.03);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-muted);
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .mini-share-btn:hover {
        background: var(--main-blue);
        color: #fff;
        transform: translateY(-2px);
    }

    .sidebar-security {
        position: relative;
        z-index: 1;
        display: flex;
        gap: 0.6rem;
        font-size: 0.78rem;
        color: var(--text-light);
        line-height: 1.5;
        background: rgba(10, 37, 64, 0.02);
        padding: 0.85rem 1.15rem;
        border-radius: 14px;
    }

    .sidebar-security svg {
        flex-shrink: 0;
        color: var(--sec-accent-green);
        margin-top: 2px;
    }

    /* Mobile adaptations */
    @media (max-width: 992px) {
        .job-single-grid {
            grid-template-columns: 1fr;
            gap: 3rem;
        }
        .job-sidebar-column {
            display: none;
        }
    }
    
    /* Elegant Content Checklist System */
    .post-content {
        color: var(--text-body);
        line-height: 1.8;
        font-size: 1.05rem;
    }
    .post-content h2, .post-content h3 {
        color: var(--main-blue);
        margin: 2.5rem 0 1.25rem;
        font-weight: 800;
        font-size: 1.6rem;
        font-family: var(--font-header);
    }
    .post-content h2:first-of-type {
        margin-top: 0;
    }
    .post-content p {
        margin-bottom: 1.5rem;
    }
    .post-content ul {
        list-style: none;
        padding-left: 0;
        margin-bottom: 2rem;
    }
    .post-content li {
        position: relative;
        padding-left: 2rem;
        margin-bottom: 0.85rem;
        font-size: 1.05rem;
        line-height: 1.7;
    }
    .post-content li::before {
        content: '✓';
        position: absolute;
        left: 0.25rem;
        top: 0.1rem;
        color: var(--sec-accent-green);
        font-weight: 900;
        font-size: 1.15rem;
    }

    /* Cooperative Perks inline (Unified, borderless) */
    .cooperative-perks-section {
        padding: 2.5rem 0 1rem 0;
        border: none;
    }
    .cooperative-perks-section h3 {
        font-size: 1.6rem;
        font-weight: 800;
        color: var(--main-blue);
        margin-bottom: 2rem;
        font-family: var(--font-header);
        text-align: center;
    }
    .perks-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 2rem 2.5rem;
    }
    .perk-item-inline {
        display: flex;
        align-items: flex-start;
        gap: 1.25rem;
    }
    .perk-icon-wrapper {
        flex-shrink: 0;
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: rgba(10, 37, 64, 0.04);
        color: var(--main-blue);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        border: 1px solid rgba(10, 37, 64, 0.06);
    }
    .perk-item-inline:hover .perk-icon-wrapper {
        background: #ffd166;
        color: #0a2540;
        border-color: #ffd166;
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 6px 15px rgba(255, 209, 102, 0.4);
    }
    .perk-content-inline h4 {
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 0.3rem;
    }
    .perk-content-inline p {
        font-size: 0.92rem;
        color: var(--text-muted);
        margin: 0;
        line-height: 1.6;
    }

    /* Elegant, Integrated CTA Section (Clean typographic flow, completely cardless) */
    .job-cta-section {
        text-align: center;
        padding: 3.5rem 0 2rem 0;
        border-top: 1px solid rgba(10, 37, 64, 0.06);
        position: relative;
    }
    .job-cta-section .cta-title {
        font-family: var(--font-header);
        font-size: 2rem;
        font-weight: 800;
        color: var(--main-blue);
        margin-bottom: 0.75rem;
        letter-spacing: -0.5px;
    }
    .job-cta-section .cta-desc {
        font-size: 1.05rem;
        color: var(--text-muted);
        margin: 0 auto 1.75rem auto;
        max-width: 600px;
        line-height: 1.7;
    }
    .cta-action-row {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 1.25rem;
        width: 100%;
        margin-top: 1.25rem;
        flex-wrap: wrap;
    }
    .btn-bespoke-gold {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        padding: 1.15rem 3rem;
        border-radius: 50px;
        font-weight: 700;
        text-decoration: none;
        font-size: 1.05rem;
        background: #ffd166;
        color: #0a2540;
        border: 2px solid #ffd166;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 6px 20px rgba(255, 209, 102, 0.35);
    }
    .btn-bespoke-gold:hover {
        background: #0a2540;
        border-color: #0a2540;
        color: #fff;
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(10, 37, 64, 0.25);
    }
    .btn-bespoke-outline {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        padding: 1.15rem 3rem;
        border-radius: 50px;
        font-weight: 700;
        text-decoration: none;
        font-size: 1.05rem;
        background: transparent;
        color: var(--main-blue);
        border: 2px solid rgba(10, 37, 64, 0.15);
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .btn-bespoke-outline svg {
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .btn-bespoke-outline:hover {
        background: rgba(10, 37, 64, 0.04);
        border-color: var(--main-blue);
        transform: translateY(-3px);
    }
    .btn-bespoke-outline:hover svg {
        transform: rotate(180deg) translateX(4px);
    }

    /* Share and Safety Row (Minimalist and seamless) */
    .job-footer-meta {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1.75rem;
        padding-top: 2.5rem;
        border-top: 1px solid rgba(10, 37, 64, 0.06);
    }
    .job-share-container {
        display: flex;
        align-items: center;
        gap: 1.25rem;
        flex-wrap: wrap;
        justify-content: center;
    }
    .job-share-label {
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--text-muted);
    }
    .share-buttons-row {
        display: flex;
        gap: 0.75rem;
    }

    .share-btn {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: rgba(10, 37, 64, 0.03);
        border: 1px solid rgba(10, 37, 64, 0.06);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-muted);
        transition: all 0.3s ease;
    }
    .share-btn:hover {
        background: var(--main-blue);
        color: white;
        border-color: var(--main-blue);
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(10, 37, 64, 0.15);
    }

    /* Security Note */
    .sidebar-note {
        font-size: 0.85rem;
        color: var(--text-muted);
        display: flex;
        gap: 0.75rem;
        line-height: 1.6;
        max-width: 600px;
        justify-content: center;
        text-align: center;
        align-items: center;
        background: rgba(10, 37, 64, 0.02);
        padding: 1rem 1.75rem;
        border-radius: 50px;
        border: 1px solid rgba(10, 37, 64, 0.04);
    }
    .sidebar-note svg { flex-shrink: 0; color: var(--sec-accent-green); }

    .btn-block { display: block; text-align: center; width: 100%; padding: 1rem; border-radius: 10px; text-decoration: none; }
    .mobile-apply-cta { display: none; margin-top: 3rem; padding-top: 2rem; border-top: 1px solid var(--border-color); }

    /* Mobile Sticky Apply Bar */
    .mobile-sticky-apply-bar {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(10, 37, 64, 0.85);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border-top: 1px solid rgba(255, 255, 255, 0.12);
        padding: 1rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        z-index: 999;
        transform: translateY(100%);
        transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 -10px 30px rgba(0, 0, 0, 0.3);
    }

    .mobile-sticky-apply-bar.visible {
        transform: translateY(0);
    }

    .mobile-sticky-info {
        display: flex;
        flex-direction: column;
        gap: 0.2rem;
        max-width: 65%;
    }

    .mobile-sticky-title {
        font-weight: 700;
        font-size: 0.95rem;
        color: #fff;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .mobile-sticky-meta {
        font-size: 0.75rem;
        color: rgba(255, 255, 255, 0.6);
    }

    @media (max-width: 768px) {
        .job-single-grid {
            gap: 2.5rem;
        }
        .perks-grid {
            gap: 1.5rem;
        }
    }
    @media (max-width: 576px) {
        .perks-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        .job-share-container {
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;
        }
    }
</style>

<script>
    (function () {
        const stickyBar = document.getElementById('mobile-sticky-apply-bar');
        if (!stickyBar) return;
        
        window.addEventListener('scroll', function () {
            if (window.innerWidth <= 992) {
                // Show if scrolled past 300px
                if (window.scrollY > 300) {
                    stickyBar.classList.add('visible');
                } else {
                    stickyBar.classList.remove('visible');
                }
            } else {
                stickyBar.classList.remove('visible');
            }
        });
    })();
</script>

<?php get_footer(); ?>