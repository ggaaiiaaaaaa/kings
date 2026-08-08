<?php
/* Single template for the 'jobs' custom post type — auto-applied by WP hierarchy */
if (!defined('ABSPATH')) {
    require_once 'functions.php';
}

// --- Pull ACF job fields ---
$job_location = kg_get_field('job_location', 'Philippines');
$job_type = kg_get_field('job_type', 'FULL_TIME');
$job_salary_min = kg_get_field('job_salary_min', '');
$job_salary_max = kg_get_field('job_salary_max', '');
$job_department = kg_get_field('job_department', '');

// --- Position closure status ---
$job_closed = get_post_meta(get_the_ID(), 'job_closed', true);

// --- Increment view count ---
if (!is_user_logged_in() || !current_user_can('edit_posts')) {
    $views = (int) get_post_meta(get_the_ID(), 'job_view_count', true);
    update_post_meta(get_the_ID(), 'job_view_count', $views + 1);
}

// Human-readable employment type label
$job_type_labels = [
    'FULL_TIME' => 'Full-time',
    'PART_TIME' => 'Part-time',
    'CONTRACTOR' => 'Remote',
    'OTHER' => 'Other',
];
$job_type_label = $job_type_labels[$job_type] ?? $job_type;

// Salary display string
$salary_display = '';
if ($job_salary_min && $job_salary_max) {
    $salary_display = '₱' . number_format($job_salary_min) . ' – ₱' . number_format($job_salary_max) . '/mo';
} elseif ($job_salary_min) {
    $salary_display = 'From ₱' . number_format($job_salary_min) . '/mo';
}

// --- Page meta ---
$page_title = get_the_title() . ' | Kings Group Careers';
$page_description = wp_strip_all_tags(get_the_excerpt()) ?: 'Apply for ' . get_the_title() . ' at Kings Group Cooperative.';

// Get work setup field or parse from title as fallback
$work_setup = kg_get_field('job_work_setup', '');
if (empty($work_setup)) {
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
    'WFO' => 'Office-Based (WFO)',
    'WFH' => 'Home-Based (WFH)',
    'Hybrid' => 'Hybrid Setup',
];
$work_setup_label = $work_setup_labels[$work_setup] ?? $work_setup;

// --- JSON-LD: JobPosting schema for Google for Jobs ---
$page_schema = [
    '@context' => 'https://schema.org',
    '@type' => 'JobPosting',
    'title' => get_the_title(),
    'description' => wp_strip_all_tags(get_queried_object()->post_content) ?: (wp_strip_all_tags(get_queried_object()->post_excerpt) ?: 'Apply for ' . get_the_title() . ' at Kings Group Cooperative.'),
    'datePosted' => get_the_date('Y-m-d'),
    'validThrough' => date('Y-m-d', strtotime('+90 days')),
    'employmentType' => $job_type,
    'jobLocation' => [
        '@type' => 'Place',
        'address' => [
            '@type' => 'PostalAddress',
            'addressLocality' => $job_location,
            'addressCountry' => 'PH',
        ],
    ],
    'hiringOrganization' => [
        '@id' => 'https://kingsgroup.com.ph/#organization',
        '@type' => 'Organization',
        'name' => 'Kings Group Cooperative',
        'sameAs' => 'https://kingsgroup.com.ph/',
    ],
    'applicantLocationRequirements' => [
        '@type' => 'Country',
        'name' => 'Philippines',
    ],
];

get_header();
while (have_posts()):
    the_post();
    ?>

    <!-- Job Body — Dual Column Layout -->
    <section class="section section-bg-white" style="padding: 6rem 0 5rem 0;">
        <div class="container">

            <div class="job-single-grid">

                <!-- Left Column: Content -->
                <div class="job-main-column">

                    <!-- Elegant Back to All Jobs navigation -->
                    <div class="back-to-jobs-container" style="margin-bottom: 1.5rem;">
                        <a href="<?php echo esc_url(home_url('/our-jobs/')); ?>" class="back-to-jobs-link"
                            style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--main-blue); font-weight: 600; text-decoration: none; font-size: 0.9rem; transition: all 0.3s ease;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                                style="transition: transform 0.3s ease;">
                                <line x1="19" y1="12" x2="5" y2="12"></line>
                                <polyline points="12 19 5 12 12 5"></polyline>
                            </svg>
                            Back to All Jobs
                        </a>
                    </div>
                    <style>
                        .back-to-jobs-link:hover {
                            color: var(--hover-blue, #0d3b66) !important;
                        }

                        .back-to-jobs-link:hover svg {
                            transform: translateX(-4px);
                        }
                    </style>

                    <!-- Elegant Job Detail Header (Light Theme / Minimalist) -->
                    <div class="job-detail-header">
                        <h1 class="job-detail-title"><?php the_title(); ?></h1>

                        <div class="job-detail-badges animate-on-scroll">
                            <span class="badge badge-date">
                                <svg class="inline-icon" width="14" height="14" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                                Posted <?php echo get_the_date('M j, Y'); ?>
                            </span>
                        </div>
                    </div>

                    <!-- Job Details Specs Grid (Borderless & Premium) -->
                    <div class="job-specs-grid animate-on-scroll">
                        <div class="spec-item">
                            <div class="spec-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                                </svg>
                            </div>
                            <div class="spec-meta">
                                <span class="spec-label">Employment Type</span>
                                <span class="spec-value"><?php echo esc_html($job_type_label); ?></span>
                            </div>
                        </div>
                        <div class="spec-item">
                            <div class="spec-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                                    <line x1="8" y1="21" x2="16" y2="21"></line>
                                    <line x1="12" y1="17" x2="12" y2="21"></line>
                                </svg>
                            </div>
                            <div class="spec-meta">
                                <span class="spec-label">Work Setup</span>
                                <span class="spec-value"><?php echo esc_html($work_setup_label); ?></span>
                            </div>
                        </div>
                        <?php if ($job_location): ?>
                            <div class="spec-item">
                                <div class="spec-icon">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                </div>
                                <div class="spec-meta">
                                    <span class="spec-label">Location</span>
                                    <span class="spec-value"><?php echo esc_html($job_location); ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if ($job_department): ?>
                            <div class="spec-item">
                                <div class="spec-icon">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                                        <polyline points="2 17 12 22 22 17"></polyline>
                                        <polyline points="2 12 12 17 22 12"></polyline>
                                    </svg>
                                </div>
                                <div class="spec-meta">
                                    <span class="spec-label">Department</span>
                                    <span class="spec-value"><?php echo esc_html($job_department); ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if ($salary_display): ?>
                            <div class="spec-item">
                                <div class="spec-icon">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="2" y="6" width="20" height="12" rx="2"></rect>
                                        <circle cx="12" cy="12" r="2"></circle>
                                        <path d="M6 12h.01M18 12h.01"></path>
                                    </svg>
                                </div>
                                <div class="spec-meta">
                                    <span class="spec-label">Salary Range</span>
                                    <span class="spec-value"><?php echo esc_html($salary_display); ?></span>
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
                        <h3><?php echo esc_html(kg_get_field('jobs_perks_title', 'Cooperative Perks & Advantages', 'option')); ?>
                        </h3>
                        <div class="perks-grid">
                            <div class="perk-item-inline">
                                <div class="perk-icon-wrapper">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="8" x2="12" y2="16"></line>
                                        <line x1="8" y1="12" x2="16" y2="12"></line>
                                    </svg>
                                </div>
                                <div class="perk-content-inline">
                                    <h4>Annual Dividend Sharing</h4>
                                    <p>As a cooperative member, enjoy direct profit-sharing and competitive annual dividend
                                        returns.</p>
                                </div>
                            </div>
                            <div class="perk-item-inline">
                                <div class="perk-icon-wrapper">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M22 12h-4l-3 9L9 3l-3 9H2"></path>
                                    </svg>
                                </div>
                                <div class="perk-content-inline">
                                    <h4>Comprehensive Health Coverage</h4>
                                    <p>Premium medical, dental, and life insurance policies ensuring complete peace of mind
                                        for you and your family.</p>
                                </div>
                            </div>
                            <div class="perk-item-inline">
                                <div class="perk-icon-wrapper">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                    </svg>
                                </div>
                                <div class="perk-content-inline">
                                    <h4>Training & Career Academy</h4>
                                    <p>Access our extensive academy courses, professional certifications, and regular
                                        upskilling bootcamps.</p>
                                </div>
                            </div>
                            <div class="perk-item-inline">
                                <div class="perk-icon-wrapper">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path
                                            d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="perk-content-inline">
                                    <h4>Family & Wellness Support</h4>
                                    <p>Tailored allowances, regular wellness days, mental health initiatives, and active
                                        lifestyle programs.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Trust & Safety Section -->
                    <div class="job-footer-meta animate-on-scroll">
                        <p class="sidebar-note" style="margin: 0 auto;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                            </svg>
                            <span><strong>Trust & Safety:</strong>
                                <?php echo esc_html(kg_get_field('jobs_trust_safety_text', 'Kings Group Cooperative will never request payment or bank credentials during any stage of recruitment. Apply securely above.', 'option')); ?></span>
                        </p>
                    </div>
                </div>

                <!-- Right Column: Sticky Sidebar -->
                <div class="job-sidebar-column">
                    <div class="job-sticky-sidebar">
                        <div class="glass-sidebar-card">
                            <?php if ($job_closed): ?>
                                <div class="sidebar-badge-top" style="color:#dc2626;">
                                    <span
                                        style="width:8px;height:8px;background:#dc2626;border-radius:50%;display:inline-block;margin-right:4px;"></span>
                                    Position Closed
                                </div>
                                <h3 class="sidebar-title" style="color:#dc2626;">Position Closed</h3>
                                <p class="sidebar-desc">This position is no longer accepting applications. Browse our other open
                                    roles below.</p>
                                <a href="<?php echo esc_url(home_url('/our-jobs/')); ?>" class="btn-sidebar-apply"
                                    style="background:#e5e7eb;color:#6b7280;box-shadow:none;pointer-events:auto;text-decoration:none;display:flex;align-items:center;justify-content:center;gap:0.5rem;">
                                    View Open Positions
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2.5">
                                        <line x1="5" y1="12" x2="19" y2="12" />
                                        <polyline points="12 5 19 12 12 19" />
                                    </svg>
                                </a>
                            <?php else: ?>
                                <div class="sidebar-badge-top">
                                    <span class="badge-pulse"></span> Open Position
                                </div>

                                <h3 class="sidebar-title">
                                    <?php echo esc_html(kg_get_field('jobs_apply_instantly_title', 'Apply Instantly', 'option')); ?>
                                </h3>
                                <p class="sidebar-desc">
                                    <?php echo esc_html(kg_get_field('jobs_apply_instantly_desc', 'Fast-track your application to our hiring coordinators. Form takes under 2 minutes.', 'option')); ?>
                                </p>

                                <div class="sidebar-specs">
                                    <div class="sidebar-spec-item">
                                        <span class="sidebar-spec-label">Target Role</span>
                                        <span class="sidebar-spec-val"><?php the_title(); ?></span>
                                    </div>
                                    <?php if ($job_department): ?>
                                        <div class="sidebar-spec-item">
                                            <span class="sidebar-spec-label">Department</span>
                                            <span class="sidebar-spec-val"><?php echo esc_html($job_department); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    <div class="sidebar-spec-item">
                                        <span class="sidebar-spec-label">Employment Type</span>
                                        <span class="sidebar-spec-val"><?php echo esc_html($job_type_label); ?></span>
                                    </div>
                                    <?php if ($salary_display): ?>
                                        <div class="sidebar-spec-item">
                                            <span class="sidebar-spec-label">Salary Estimate</span>
                                            <span
                                                class="sidebar-spec-val gold-accent"><?php echo esc_html($salary_display); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <form id="sidebar-apply-form"
                                    style="display: flex; flex-direction: column; gap: 1rem; position: relative; z-index: 1;">
                                    <!-- CV Upload -->
                                    <div id="sidebar-cv-dropzone"
                                        style="border: 2px dashed var(--border-color); padding: 1.5rem 1rem; text-align: center; cursor: pointer; transition: var(--transition); background: var(--bg-light);"
                                        onclick="document.getElementById('sidebar-cv-upload').click()">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            stroke="var(--sec-accent-green)" stroke-width="2" style="margin-bottom:0.5rem;">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                            <polyline points="17 8 12 3 7 8" />
                                            <line x1="12" y1="3" x2="12" y2="15" />
                                        </svg>
                                        <div style="font-size: 0.85rem; font-weight: 700; color: var(--text-dark);">Upload CV
                                        </div>
                                        <div style="font-size: 0.75rem; color: var(--text-muted);">PDF, DOCX (Max 5MB)</div>
                                        <input type="file" id="sidebar-cv-upload" style="display:none;" accept=".pdf,.doc,.docx"
                                            required>
                                    </div>

                                    <div id="sidebar-file-info"
                                        style="display:none; padding: 0.75rem; background: rgba(0, 208, 156, 0.06); border: 1px solid rgba(0, 208, 156, 0.2); align-items: center; gap: 0.5rem; border-radius: 8px;">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                            stroke="var(--sec-accent-green)" stroke-width="2" style="flex-shrink:0;">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                            <polyline points="14 2 14 8 20 8" />
                                        </svg>
                                        <span id="sidebar-file-name"
                                            style="flex:1; font-weight: 500; color: var(--text-dark); font-size: 0.8rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"></span>
                                        <button type="button" onclick="removeSidebarFile()"
                                            style="background:none; border:none; cursor:pointer; color:var(--accent-red); font-size:0.75rem; font-weight:600; flex-shrink:0;">Remove</button>
                                    </div>

                                    <!-- Purpose -->
                                    <input type="hidden" id="sidebar-app-purpose" value="looking_for_job">

                                    <!-- Name inputs -->
                                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                        <input type="text" id="sidebar-app-fname" placeholder="First Name" required
                                            style="padding: 0.75rem 1rem; border: 1.5px solid var(--border-color); font-size: 0.9rem; border-radius: 8px; width: 100%; outline: none; background: white; color: var(--text-dark);">
                                        <input type="text" id="sidebar-app-mname" placeholder="Middle Name (Optional)"
                                            style="padding: 0.75rem 1rem; border: 1.5px solid var(--border-color); font-size: 0.9rem; border-radius: 8px; width: 100%; outline: none; background: white; color: var(--text-dark);">
                                        <input type="text" id="sidebar-app-lname" placeholder="Last Name" required
                                            style="padding: 0.75rem 1rem; border: 1.5px solid var(--border-color); font-size: 0.9rem; border-radius: 8px; width: 100%; outline: none; background: white; color: var(--text-dark);">
                                    </div>

                                    <!-- Demographics -->
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem;">
                                        <select id="sidebar-app-gender" required
                                            style="box-sizing: border-box; height: 48px; padding: 0.75rem 1rem; border: 1.5px solid var(--border-color); font-size: 0.9rem; border-radius: 8px; width: 100%; background: white; color: var(--text-dark); outline: none;">
                                            <option value="" disabled selected>Gender</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                            <option value="Non-Binary">Non-Binary</option>
                                            <option value="Prefer not to say">Prefer not to say</option>
                                        </select>
                                        <input type="text" id="sidebar-app-birthday" required placeholder="Birthdate"
                                            onfocus="(this.type='date')" onblur="if(!this.value) this.type='text'"
                                            style="box-sizing: border-box; height: 48px; padding: 0.75rem 1rem; border: 1.5px solid var(--border-color); font-size: 0.9rem; border-radius: 8px; width: 100%; background: white; color: var(--text-dark); outline: none;"
                                            title="Birthdate">
                                    </div>

                                    <!-- Address Cascades -->
                                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                        <input type="text" id="sidebar-app-street" placeholder="Street Address" required
                                            style="padding: 0.75rem 1rem; border: 1.5px solid var(--border-color); font-size: 0.9rem; border-radius: 8px; outline: none; background: white; color: var(--text-dark);">
                                        <select id="sidebar-app-region" required
                                            style="padding: 0.75rem 1rem; border: 1.5px solid var(--border-color); font-size: 0.9rem; border-radius: 8px; background: white; color: var(--text-dark); outline: none;">
                                            <option value="">Select Region</option>
                                        </select>
                                        <select id="sidebar-app-city" required
                                            style="padding: 0.75rem 1rem; border: 1.5px solid var(--border-color); font-size: 0.9rem; border-radius: 8px; background: white; color: var(--text-dark); outline: none;">
                                            <option value="">Select City / Mun</option>
                                        </select>
                                        <select id="sidebar-app-barangay" required
                                            style="padding: 0.75rem 1rem; border: 1.5px solid var(--border-color); font-size: 0.9rem; border-radius: 8px; background: white; color: var(--text-dark); outline: none;">
                                            <option value="">Select Barangay</option>
                                        </select>

                                        <!-- Hidden codes -->
                                        <input type="hidden" id="sidebar_app_region_code" value="">
                                        <input type="hidden" id="sidebar_app_city_code" value="">
                                        <input type="hidden" id="sidebar_app_barangay_code" value="">
                                    </div>

                                    <!-- Contact info -->
                                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                        <input type="email" id="sidebar-app-email" placeholder="Email Address (Optional)"
                                            style="padding: 0.75rem 1rem; border: 1.5px solid var(--border-color); font-size: 0.9rem; border-radius: 8px; outline: none; background: white; color: var(--text-dark);">
                                        <input type="tel" id="sidebar-app-phone" placeholder="Phone Number (+63)" required
                                            style="padding: 0.75rem 1rem; border: 1.5px solid var(--border-color); font-size: 0.9rem; border-radius: 8px; outline: none; background: white; color: var(--text-dark);">
                                    </div>

                                    <input type="hidden" id="sidebar-app-role" value="<?php echo esc_attr(get_the_title()); ?>">

                                    <!-- honeypot -->
                                    <div style="display:none;" aria-hidden="true">
                                        <input type="text" id="kg_hp_sidebar" name="kg_hp_field" value="" tabindex="-1"
                                            autocomplete="off">
                                    </div>

                                    <!-- CAPTCHA -->
                                    <div class="cf-turnstile"
                                        data-sitekey="<?php echo esc_attr(defined('CF_TURNSTILE_SITE_KEY') ? CF_TURNSTILE_SITE_KEY : ''); ?>"
                                        data-appearance="interaction-only" style="margin-top:0.25rem;"></div>

                                    <div id="sidebar-careers-error"
                                        style="display:none;background:#fef2f2;border:1px solid #fca5a5;padding:0.5rem 0.75rem;border-radius:6px;">
                                        <p style="margin:0;color:#991b1b;font-size:0.8rem;" id="sidebar-careers-error-msg"></p>
                                    </div>

                                    <button type="button" class="btn-sidebar-apply" style="border:none; cursor:pointer;"
                                        onclick="submitSidebarApplication()">
                                        Submit Application
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2.5">
                                            <path d="M5 12h14M12 5l7 7-7 7" />
                                        </svg>
                                    </button>
                                </form>

                                <div class="sidebar-share">
                                    <span class="sidebar-share-lbl">Share this role</span>
                                    <div class="sidebar-share-icons">
                                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>"
                                            target="_blank" rel="noopener" class="mini-share-btn" title="Share on Facebook">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                                <path
                                                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.469h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.469h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>

                            <?php endif; // end job_closed check ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Floating Mobile Sticky Apply Bar -->
    <div id="mobile-sticky-apply-bar" class="mobile-sticky-apply-bar">
        <div class="mobile-sticky-info">
            <div class="mobile-sticky-title"><?php the_title(); ?></div>
            <div class="mobile-sticky-meta"><?php echo esc_html($job_type_label); ?> ·
                <?php echo esc_html($work_setup_label); ?></div>
        </div>
        <?php if ($job_closed): ?>
            <span
                style="background:#fee2e2;color:#dc2626;padding:0.5rem 1rem;border-radius:8px;font-size:0.8rem;font-weight:700;letter-spacing:0.3px;">Position
                Closed</span>
        <?php else: ?>
            <a href="<?php echo esc_url(add_query_arg('role', get_the_title(), home_url('/careers/')) . '#apply'); ?>"
                class="btn btn-gold btn-sm" style="padding: 0.6rem 1.2rem; font-size: 0.8rem; border-radius: 8px;">Apply Now</a>
        <?php endif; ?>
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
            0% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(255, 209, 102, 0.7);
            }

            70% {
                transform: scale(1);
                box-shadow: 0 0 0 6px rgba(255, 209, 102, 0);
            }

            100% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(255, 209, 102, 0);
            }
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

        .post-content h2,
        .post-content h3 {
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

        .sidebar-note svg {
            flex-shrink: 0;
            color: var(--sec-accent-green);
        }

        .btn-block {
            display: block;
            text-align: center;
            width: 100%;
            padding: 1rem;
            border-radius: 10px;
            text-decoration: none;
        }

        .mobile-apply-cta {
            display: none;
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid var(--border-color);
        }

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

        #sidebar-cv-dropzone.drag-over {
            border-color: var(--sec-accent-green) !important;
            background: rgba(0, 208, 156, 0.04) !important;
        }
    </style>

    <script>
        (function () {
            const stickyBar = document.getElementById('mobile-sticky-apply-bar');
            if (stickyBar) {
                window.addEventListener('scroll', function () {
                    if (window.innerWidth <= 992) {
                        if (window.scrollY > 300) {
                            stickyBar.classList.add('visible');
                        } else {
                            stickyBar.classList.remove('visible');
                        }
                    } else {
                        stickyBar.classList.remove('visible');
                    }
                });
            }

            // Sidebar File upload
            const sidebarCvInput = document.getElementById('sidebar-cv-upload');
            const sidebarFileInfo = document.getElementById('sidebar-file-info');
            const sidebarFileName = document.getElementById('sidebar-file-name');
            const sidebarDropzone = document.getElementById('sidebar-cv-dropzone');

            if (sidebarCvInput) {
                sidebarCvInput.addEventListener('change', function () {
                    if (this.files.length > 0) {
                        sidebarFileInfo.style.display = 'flex';
                        sidebarDropzone.style.display = 'none';
                    }
                });
            }

            window.removeSidebarFile = function () {
                sidebarCvInput.value = '';
                sidebarFileInfo.style.display = 'none';
                sidebarDropzone.style.display = 'block';
            }

            // Drag & drop for Sidebar
            if (sidebarDropzone) {
                sidebarDropzone.addEventListener('dragover', e => { e.preventDefault(); sidebarDropzone.classList.add('drag-over'); });
                sidebarDropzone.addEventListener('dragleave', () => sidebarDropzone.classList.remove('drag-over'));
                sidebarDropzone.addEventListener('drop', e => {
                    e.preventDefault();
                    sidebarDropzone.classList.remove('drag-over');
                    if (e.dataTransfer.files.length > 0) {
                        sidebarCvInput.files = e.dataTransfer.files;
                        sidebarCvInput.dispatchEvent(new Event('change'));
                    }
                });
            }

            // Initialize address cascades and CV sanitizer for sidebar on page load
            document.addEventListener('DOMContentLoaded', () => {
                if (typeof initPSGCAddressCascades === 'function') {
                    initPSGCAddressCascades('sidebar-app-region', 'sidebar-app-city', 'sidebar-app-barangay', 'sidebar_app_region_code', 'sidebar_app_city_code', 'sidebar_app_barangay_code');
                }
                if (typeof sanitizeCVFilename === 'function') {
                    sanitizeCVFilename(sidebarCvInput, sidebarFileName);
                }
            });

            // Submit for Sidebar Form
            window.submitSidebarApplication = function () {
                const fname = document.getElementById('sidebar-app-fname').value.trim();
                const mname = document.getElementById('sidebar-app-mname').value.trim();
                const lname = document.getElementById('sidebar-app-lname').value.trim();
                const email = document.getElementById('sidebar-app-email').value.trim();
                const phone = document.getElementById('sidebar-app-phone').value.trim();
                const gender = document.getElementById('sidebar-app-gender').value;
                const birthday = document.getElementById('sidebar-app-birthday').value;
                const street = document.getElementById('sidebar-app-street').value.trim();
                const role = document.getElementById('sidebar-app-role').value;
                const cvFile = sidebarCvInput.files[0];

                const purpose = document.getElementById('sidebar-app-purpose').value;

                const regionEl = document.getElementById('sidebar-app-region');
                const cityEl = document.getElementById('sidebar-app-city');
                const barangayEl = document.getElementById('sidebar-app-barangay');
                const region = regionEl ? regionEl.value.trim() : '';
                const city = cityEl ? cityEl.value.trim() : '';
                const barangay = barangayEl ? barangayEl.value.trim() : '';

                const regionCode = document.getElementById('sidebar_app_region_code') ? document.getElementById('sidebar_app_region_code').value : '';
                const cityCode = document.getElementById('sidebar_app_city_code') ? document.getElementById('sidebar_app_city_code').value : '';
                const brgyCode = document.getElementById('sidebar_app_barangay_code') ? document.getElementById('sidebar_app_barangay_code').value : '';

                const errBox = document.getElementById('sidebar-careers-error');
                const errMsg = document.getElementById('sidebar-careers-error-msg');
                errBox.style.display = 'none';

                if (!fname || !lname) {
                    errMsg.textContent = 'Please fill in your first and last name.';
                    errBox.style.display = 'block';
                    return;
                }
                if (!email && !phone) {
                    errMsg.textContent = 'Please provide either an email address or a phone number.';
                    errBox.style.display = 'block';
                    return;
                }
                if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                    errMsg.textContent = 'Please provide a valid email address.';
                    errBox.style.display = 'block';
                    return;
                }
                if (!gender) {
                    errMsg.textContent = 'Please select your gender.';
                    errBox.style.display = 'block';
                    return;
                }
                if (!birthday) {
                    errMsg.textContent = 'Please select your birthdate.';
                    errBox.style.display = 'block';
                    return;
                }
                if (!street || !region || !city || !barangay) {
                    errMsg.textContent = 'Please fill in your complete address details.';
                    errBox.style.display = 'block';
                    return;
                }
                if (!cvFile) {
                    errMsg.textContent = 'Please upload your CV before submitting.';
                    errBox.style.display = 'block';
                    return;
                }

                const submitBtn = document.querySelector('#sidebar-apply-form .btn-sidebar-apply');
                const submitBtnText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.textContent = 'Submitting…';

                const turnstileResponse = document.querySelector('#sidebar-apply-form [name="cf-turnstile-response"]')?.value || '';

                const formData = new FormData();
                formData.append('action', 'kg_submit_application');
                formData.append('kg_nonce', KG_AJAX.careers_nonce);
                formData.append('app_fname', fname);
                formData.append('app_mname', mname);
                formData.append('app_lname', lname);
                formData.append('app_email', email);
                formData.append('app_phone', phone);
                formData.append('app_purpose', purpose);
                formData.append('app_gender', gender);
                formData.append('app_birthday', birthday);
                formData.append('app_street', street);
                formData.append('app_region', region);
                formData.append('app_city', city);
                formData.append('app_barangay', barangay);
                formData.append('app_region_code', regionCode);
                formData.append('app_city_code', cityCode);
                formData.append('app_barangay_code', brgyCode);
                formData.append('app_role', role);
                formData.append('app_job_id', '<?php echo esc_js(get_the_ID()); ?>');
                formData.append('app_cv', cvFile, cvFile.name);
                formData.append('kg_hp_field', document.getElementById('kg_hp_sidebar').value);
                formData.append('cf-turnstile-response', turnstileResponse);

                fetch(KG_AJAX.url, { method: 'POST', body: formData })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            const card = document.querySelector('.glass-sidebar-card');
                            card.innerHTML = `
                            <div style="text-align:center;padding:2rem 1rem; position:relative; z-index:1;">
                                <div style="width:72px;height:72px;margin:0 auto 1.5rem;background:rgba(0,208,156,0.12);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="var(--sec-accent-green)" stroke-width="2.5">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                </div>
                                <h3 style="font-size:1.5rem;margin-bottom:0.75rem;color:var(--main-blue);font-weight:800;">Application Sent!</h3>
                                <p style="color:var(--text-muted);font-size:0.95rem;line-height:1.6;margin-bottom:1.5rem;">
                                    Thank you for applying. Our talent team has received your application for <strong>${role}</strong>.
                                </p>
                                <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-outline" style="display:inline-block;padding:0.75rem 1.5rem;border-radius:50px;font-weight:700;font-size:0.85rem;text-decoration:none;border:2px solid var(--main-blue);color:var(--main-blue);">Back to Home</a>
                            </div>
                        `;
                        } else {
                            errMsg.textContent = (data.data && data.data.message) ? data.data.message : 'Submission failed. Please try again.';
                            errBox.style.display = 'block';
                            if (typeof turnstile !== 'undefined') turnstile.reset();
                        }
                    })
                    .catch(() => {
                        errMsg.textContent = 'Network error. Please try again.';
                        errBox.style.display = 'block';
                        if (typeof turnstile !== 'undefined') turnstile.reset();
                    })
                    .finally(() => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = submitBtnText;
                    });
            }
        })();
    </script>
    <?php
endwhile;
get_footer();
?>