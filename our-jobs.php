<?php
/* Template Name: Our Jobs */
if (!defined('ABSPATH')) {
    require_once 'functions.php';
}
$page_title = 'Our Jobs | Kings Group Careers';
$page_description = 'Browse open positions at Kings Group Cooperative. Filter by Full-Time, Part-Time, Contract, and Remote roles across the Philippines.';

$page_hero_bg = kg_get_field('jobs_hero_bg', 'https://images.unsplash.com/photo-1521737711867-e3b97375f902?auto=format&fit=crop&w=2000&q=80');
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
        <p class="animate-on-scroll" style="margin-bottom: 2.5rem; font-size: 1.15rem; color: rgba(255, 255, 255, 0.8);"><?php echo esc_html($hero_desc); ?></p>

        <?php
        // Query all published jobs for filter population
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
            'CONTRACTOR' => 'Remote',
            'OTHER' => 'Other',
        );

        $badge_colors = array(
            'FULL_TIME' => array('bg' => 'rgba(10,37,64,0.06)', 'color' => 'var(--main-blue)'),
            'PART_TIME' => array('bg' => 'rgba(255,197,0,0.1)', 'color' => '#a07800'),
            'CONTRACTOR' => array('bg' => 'rgba(0, 208, 156, 0.08)', 'color' => 'var(--sec-accent-green)'),
            'OTHER' => array('bg' => 'rgba(239,68,68,0.08)', 'color' => '#dc2626'),
        );
        ?>

        <!-- Dynamic Multi-faceted Search Panel (Cloudstaff inspired) -->
        <div class="jobs-filter-container animate-on-scroll">
            <div class="search-panel-glass">
                <div class="search-input-wrapper">
                    <svg class="search-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="text" id="job-search-input" placeholder="Search job title, skills, or keywords...">
                </div>
                
                <div class="filters-row">
                    <!-- Department Filter -->
                    <div class="filter-select-wrapper">
                        <select id="filter-dept">
                            <option value="all">All Departments</option>
                            <?php
                            $depts = [];
                            if ($jobs_query->have_posts()) {
                                while ($jobs_query->have_posts()) {
                                    $jobs_query->the_post();
                                    $d = kg_get_field('job_department', '', get_the_ID());
                                    if ($d) $depts[] = $d;
                                }
                                $depts = array_unique($depts);
                                asort($depts);
                                foreach ($depts as $dept) {
                                    echo '<option value="' . esc_attr($dept) . '">' . esc_html($dept) . '</option>';
                                }
                                $jobs_query->rewind_posts();
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Work Setup Filter -->
                    <div class="filter-select-wrapper">
                        <select id="filter-setup">
                            <option value="all">All Work Setups</option>
                            <option value="WFO">Office-Based (WFO)</option>
                            <option value="WFH">Home-Based (WFH)</option>
                            <option value="Hybrid">Hybrid</option>
                        </select>
                    </div>

                    <!-- Employment Type Filter -->
                    <div class="filter-select-wrapper">
                        <select id="filter-type">
                            <option value="all">All Job Types</option>
                            <option value="FULL_TIME">Full-Time</option>
                            <option value="PART_TIME">Part-Time</option>
                            <option value="CONTRACTOR">Remote</option>
                            <option value="OTHER">Other</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Jobs Grid Section -->
<section class="section section-bg-light" style="padding: 5rem 0;">
    <div class="container">

        <?php if ($jobs_query->have_posts()): ?>

            <div class="jobs-meta-header animate-on-scroll">
                <div id="results-count" class="results-count">
                    Showing <strong id="visible-count"><?php echo $jobs_query->post_count; ?></strong> matching opportunities
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
                    $excerpt = wp_trim_words(get_the_excerpt() ?: get_the_content(), 18, '…');

                    // Priority 1: ACF Custom Image, Priority 2: Featured Image, Priority 3: Premium Dynamic Placeholder
                    $custom_img = kg_get_field('job_card_image', '', get_the_ID());
                    $thumb_url = trim($custom_img ? $custom_img : get_the_post_thumbnail_url(get_the_ID(), 'kg-card'));
                    
                    // Intercept empty, boolean false, null, placeholder, or theme default paths
                    if (empty($thumb_url) || 
                        $thumb_url === 'false' || 
                        $thumb_url === 'null' || 
                        strpos($thumb_url, 'http') === false || 
                        strpos($thumb_url, 'placeholder') !== false || 
                        strpos($thumb_url, 'photo-1600880292203-757bb62b4baf') !== false) {
                        
                        $job_title = trim(get_the_title());
                        $title_images_raw = array(
                            'operations head'                        => 'https://images.unsplash.com/photo-1531538606174-0f90ff5dce83?auto=format&fit=crop&w=800&q=80',
                            'accounting and finance head'            => 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?auto=format&fit=crop&w=800&q=80',
                            'building administrator'                 => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=800&q=80',
                            'culinary administrator'                 => 'https://images.unsplash.com/photo-1556910103-1c02745aae4d?auto=format&fit=crop&w=800&q=80',
                            'software developer'                     => 'https://images.unsplash.com/photo-1607799279861-4dd421887fb3?auto=format&fit=crop&w=800&q=80',
                            'business analyst'                       => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=800&q=80',
                            'marketing officer'                      => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=800&q=80',
                            'hr coordinator'                         => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=800&q=80',
                            'recruitment officer'                    => 'https://images.unsplash.com/photo-1556761175-5973dc0f32e7?auto=format&fit=crop&w=800&q=80',
                            'billing and collection officer'         => 'https://images.unsplash.com/photo-1563986768609-322da13575f3?auto=format&fit=crop&w=800&q=80',
                            'payroll master / senior payroll analyst' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&w=800&q=80',
                            'payroll staff'                          => 'https://images.unsplash.com/photo-1506784983877-45594efa4cbe?auto=format&fit=crop&w=800&q=80',
                            'accounting supervisor'                  => 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=800&q=80',
                            'accounting manager'                     => 'https://images.unsplash.com/photo-1497215728101-856f4ea42174?auto=format&fit=crop&w=800&q=80',
                        );
                        
                        $lookup_title = strtolower($job_title);
                        if (isset($title_images_raw[$lookup_title])) {
                            $thumb_url = $title_images_raw[$lookup_title];
                        } else {
                            $title_lower = strtolower($job_title);
                            $dept_lower = strtolower($job_dept);
                            if (strpos($dept_lower, 'tech') !== false || strpos($title_lower, 'developer') !== false || strpos($title_lower, 'engineer') !== false) {
                                $thumb_url = 'https://images.unsplash.com/photo-1605810230434-7631ac76ec81?auto=format&fit=crop&w=600&q=80'; // tech workspace
                            } elseif (strpos($dept_lower, 'analyst') !== false || strpos($title_lower, 'analyst') !== false) {
                                $thumb_url = 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=600&q=80'; // analytics / dashboard
                            } elseif (strpos($dept_lower, 'market') !== false || strpos($title_lower, 'marketing') !== false || strpos($title_lower, 'sales') !== false) {
                                $thumb_url = 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=600&q=80'; // marketing strategy
                            } elseif (strpos($dept_lower, 'hr') !== false || strpos($dept_lower, 'resource') !== false || strpos($title_lower, 'coordinator') !== false || strpos($title_lower, 'recruiter') !== false || strpos($title_lower, 'recruitment') !== false) {
                                $thumb_url = 'https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?auto=format&fit=crop&w=600&q=80'; // professional team/HR
                            } elseif (strpos($dept_lower, 'finance') !== false || strpos($dept_lower, 'account') !== false || strpos($title_lower, 'billing') !== false || strpos($title_lower, 'payroll') !== false || strpos($title_lower, 'accountant') !== false) {
                                $thumb_url = 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?auto=format&fit=crop&w=600&q=80'; // finance/bookkeeping
                            } else {
                                $thumb_url = 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=600&q=80'; // generic modern premium office
                            }
                        }
                    }

                    $apply_url = esc_url(add_query_arg('role', get_the_title(), home_url('/careers/')) . '#apply');

                    $slots_left = $target_hc > 0 ? ($target_hc - $filled_hc) : null;

                    // Get work setup field or parse from title as fallback
                    $work_setup = kg_get_field('job_work_setup', '', get_the_ID());
                    if (empty($work_setup)) {
                        $title_lower = strtolower(get_the_title());
                        $work_setup = 'WFO'; // default
                        if (strpos($title_lower, 'wfh') !== false || strpos($title_lower, 'remote') !== false || $job_type === 'OTHER' || $job_type === 'CONTRACTOR') {
                            $work_setup = 'WFH';
                        } elseif (strpos($title_lower, 'hybrid') !== false) {
                            $work_setup = 'Hybrid';
                        }
                    }
                    
                    ?>
                    <article class="job-glass-card" 
                        data-type="<?php echo esc_attr($job_type); ?>"
                        data-department="<?php echo esc_attr($job_dept); ?>"
                        data-setup="<?php echo esc_attr($work_setup); ?>">

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
                            <h3 class="job-card-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>

                            <div class="job-card-meta">
                                <?php if ($job_dept): ?>
                                    <span class="meta-item department">
                                        <?php echo esc_html($job_dept); ?>
                                    </span>
                                <?php endif; ?>
                                <?php if ($job_location): ?>
                                    <?php if ($job_dept): ?>
                                        <span class="meta-separator">·</span>
                                    <?php endif; ?>
                                    <span class="meta-item location">
                                        <?php echo esc_html($job_location); ?>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <?php if ($excerpt): ?>
                                <p class="job-card-excerpt"><?php echo esc_html($excerpt); ?></p>
                            <?php endif; ?>

                            <div class="job-card-footer-info">
                                <span class="job-type-badge" style="color: <?php echo $badge['color']; ?>;">
                                    <?php echo esc_html($type_label); ?>
                                </span>
                                
                                <?php if ($slots_left !== null): ?>
                                    <div class="job-card-slots">
                                        <span class="slots-indicator <?php echo $slots_left > 0 ? 'pulse' : ''; ?>"></span>
                                        <?php echo $slots_left; ?> slot<?php echo $slots_left !== 1 ? 's' : ''; ?> remaining
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="job-card-actions">
                                <a href="<?php the_permalink(); ?>" class="btn btn-outline btn-sm">Details</a>
                                <a href="<?php echo $apply_url; ?>" class="btn btn-primary btn-sm">Apply Now</a>
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
                <p class="section-subtitle">Try adjusting your filters or search keywords.</p>
            </div>

        <?php else: ?>
            <!-- No jobs posted -->
            <div class="empty-state-glass animate-on-scroll">
                <div class="empty-icon"><?php echo kg_icon('briefcase'); ?></div>
                <h2 class="section-title">Hiring Soon</h2>
                <p class="section-subtitle">We are currently updating our job board. Check back soon for new opportunities!</p>
                <a href="<?php echo esc_url(home_url('/careers/#apply')); ?>" class="btn btn-primary">Join Talent Pool</a>
            </div>
        <?php endif; ?>

    </div>
</section>

<!-- Final CTA -->
<section class="section final-cta-section cta-bottom animate-on-scroll" style="padding: 6rem 2rem; width: 100%;">
    <div style="max-width: 800px; margin: 0 auto; text-align: center;">
        <h2 class="section-title" style="color: #ffffff; font-weight: 800; font-size: 2.25rem; margin-bottom: 1rem;">Don't see the right fit?</h2>
        <p class="section-subtitle" style="margin-bottom: 2.5rem; color: rgba(255, 255, 255, 0.85); font-size: 1.1rem; line-height: 1.6;">Upload your CV and we'll keep you in mind for upcoming roles that match your expertise.</p>
        <div style="display: flex; justify-content: center; align-items: center;">
            <a href="<?php echo esc_url(home_url('/careers/#apply')); ?>" class="btn btn-gold btn-lg" style="box-shadow: 0 10px 25px rgba(255, 209, 102, 0.4); padding: 1.25rem 2.5rem; font-size: 1.1rem; border-radius: 12px; font-weight: 700; display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none;">
                Submit Open Application
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</section>


<style>
    .page-hero {
        overflow: visible !important;
    }

    /* Cloudstaff-inspired Search Panel */
    .search-panel-glass {
        background: rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 20px;
        padding: 1.5rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        max-width: 900px;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    .search-input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .search-icon {
        position: absolute;
        left: 1.25rem;
        color: rgba(255, 255, 255, 0.6);
        pointer-events: none;
    }

    #job-search-input {
        width: 100%;
        padding: 1rem 1rem 1rem 3.25rem;
        background: rgba(10, 37, 64, 0.4);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 12px;
        color: #fff;
        font-size: 1rem;
        font-family: var(--font-body);
        transition: var(--transition);
    }

    #job-search-input:focus {
        outline: none;
        border-color: var(--neutral-yellow);
        background: rgba(10, 37, 64, 0.6);
        box-shadow: 0 0 15px rgba(255, 209, 102, 0.2);
    }

    #job-search-input::placeholder {
        color: rgba(255, 255, 255, 0.5);
    }

    .filters-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }

    /* Ultra-Premium Custom Dropdown */
    .kg-custom-select {
        position: relative;
        width: 100%;
        user-select: none;
        z-index: 10;
    }

    .kg-custom-select.open {
        z-index: 99999;
    }

    .kg-select-trigger {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        padding: 0.9rem 1.25rem;
        background: rgba(255, 255, 255, 0.05);
        border: 1.5px solid rgba(255, 255, 255, 0.08);
        border-radius: 12px;
        color: #fff;
        font-weight: 600;
        font-size: 0.9rem;
        font-family: var(--font-body);
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }

    .kg-select-trigger:hover {
        background-color: rgba(255, 255, 255, 0.1);
        border-color: rgba(255, 255, 255, 0.2);
    }

    .kg-custom-select.open .kg-select-trigger {
        border-color: var(--neutral-yellow);
        background-color: rgba(10, 37, 64, 0.6);
        box-shadow: 0 0 0 4px rgba(255, 209, 102, 0.15);
    }

    .kg-select-trigger svg {
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        color: rgba(255, 255, 255, 0.7);
    }

    .kg-custom-select.open .kg-select-trigger svg {
        transform: rotate(180deg);
        color: var(--neutral-yellow);
    }

    .kg-select-options {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        margin-top: 8px;
        background: rgba(13, 30, 54, 0.96);
        backdrop-filter: blur(25px);
        -webkit-backdrop-filter: blur(25px);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 12px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
        z-index: 9999;
        opacity: 0;
        transform: translateY(-10px);
        pointer-events: none;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        max-height: 260px;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: rgba(255,255,255,0.2) transparent;
    }

    .kg-select-options::-webkit-scrollbar {
        width: 6px;
    }
    .kg-select-options::-webkit-scrollbar-thumb {
        background: rgba(255,255,255,0.2);
        border-radius: 3px;
    }

    .kg-custom-select.open .kg-select-options {
        opacity: 1;
        transform: translateY(0);
        pointer-events: auto;
    }

    .kg-option {
        padding: 0.8rem 1.25rem;
        color: rgba(255, 255, 255, 0.85);
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        text-align: left;
    }

    .kg-option:hover {
        background: rgba(255, 255, 255, 0.08);
        color: #fff;
        padding-left: 1.5rem;
    }

    .kg-option.selected {
        background: rgba(255, 209, 102, 0.1);
        color: var(--neutral-yellow);
        font-weight: 700;
    }

    /* Grid & Enhanced Cards */
    .jobs-meta-header {
        margin-bottom: 2rem;
        text-align: left;
    }

    .results-count {
        font-size: 0.95rem;
        color: var(--text-muted);
        letter-spacing: 0.5px;
    }

    .jobs-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 2rem;
    }

    .job-glass-card {
        background: #ffffff;
        border: 1px solid rgba(0, 0, 0, 0.06);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.04);
        border-radius: 40px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
    }

    .job-glass-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 25px 50px rgba(10, 61, 54, 0.1);
        border-color: rgba(10, 61, 54, 0.15);
    }

    .job-card-media {
        height: 250px;
        position: relative;
        overflow: hidden;
    }

    .job-card-media img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center top;
        transition: transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .job-glass-card:hover .job-card-media img {
        transform: scale(1.06);
    }

    .job-card-placeholder {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #0a3d36, #003831);
        display: flex;
        align-items: center;
        justify-content: center;
        color: rgba(255, 255, 255, 0.2);
    }

    .job-card-content {
        background: #ffffff;
        border-radius: 36px 36px 0 0;
        margin-top: -36px;
        position: relative;
        z-index: 2;
        padding: 2.25rem 2rem 2rem 2rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .job-card-title {
        font-family: var(--font-header);
        font-size: 1.5rem;
        font-weight: 700;
        line-height: 1.35;
        margin-bottom: 0.5rem;
    }

    .job-card-title a {
        color: var(--main-blue);
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .job-card-title a:hover {
        color: var(--main-blue-light);
    }

    .job-card-meta {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        font-size: 0.8rem;
        color: var(--neutral-yellow);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    .job-card-meta .meta-item {
        display: inline-block;
    }

    .job-card-meta .meta-separator {
        color: rgba(255, 209, 102, 0.4);
        margin: 0 0.25rem;
    }

    .job-card-excerpt {
        font-size: 0.95rem;
        color: #4a4a4a;
        line-height: 1.65;
        margin-bottom: 1.75rem;
        flex: 1;
    }

    .job-card-footer-info {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.25rem;
        border-top: 1px solid rgba(0, 0, 0, 0.05);
        padding-top: 0.75rem;
    }

    .job-type-badge {
        padding: 0;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .job-card-slots {
        font-size: 0.75rem;
        font-weight: 600;
        color: #666;
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    .slots-indicator {
        width: 8px;
        height: 8px;
        background: var(--neutral-yellow);
        border-radius: 50%;
    }

    .slots-indicator.pulse {
        animation: slots-glow 2s infinite;
    }

    @keyframes slots-glow {
        0% {
            box-shadow: 0 0 0 0 rgba(255, 209, 102, 0.7);
        }
        70% {
            box-shadow: 0 0 0 6px rgba(255, 209, 102, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(255, 209, 102, 0);
        }
    }

    .job-glass-card .job-card-actions {
        display: flex;
        gap: 0.75rem;
        margin-top: auto;
    }

    .job-glass-card .job-card-actions .btn {
        flex: 1;
        border-radius: 100px;
        font-family: var(--font-header);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        padding: 0.85rem 1.25rem;
        font-size: 0.8rem;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        text-align: center;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
    }

    .job-glass-card .job-card-actions .btn-outline {
        border: 2px solid var(--main-blue);
        color: var(--main-blue);
        background: transparent;
    }

    .job-glass-card .job-card-actions .btn-outline:hover {
        background: var(--main-blue);
        color: #ffffff;
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(10, 37, 64, 0.15);
    }

    .job-glass-card .job-card-actions .btn-primary {
        border: 2px solid var(--main-blue);
        color: #ffffff;
        background: var(--main-blue);
        box-shadow: 0 6px 15px rgba(10, 37, 64, 0.15);
    }

    .job-glass-card .job-card-actions .btn-primary:hover {
        background: var(--main-blue-hover);
        border-color: var(--main-blue-hover);
        color: #ffffff;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(10, 37, 64, 0.25);
    }

    /* States */
    .empty-state-glass {
        padding: 5rem 2rem;
        text-align: center;
        background: var(--glass-mid-bg);
        backdrop-filter: var(--glass-mid-blur);
        border: 1px solid var(--glass-mid-border);
        border-radius: 20px;
    }

    .empty-icon {
        font-size: 3.5rem;
        margin-bottom: 1.5rem;
        opacity: 0.25;
    }

    .cta-glass-box {
        padding: 4rem;
        background: var(--gradient-tech-subtle);
        border: 1px solid var(--border-color);
        border-radius: 24px;
    }

    @media (max-width: 768px) {
        .filters-row {
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }
        
        .search-panel-glass {
            padding: 1.25rem;
        }

        .jobs-grid {
            grid-template-columns: 1fr;
        }

        .cta-glass-box {
            padding: 2.5rem 1.5rem;
        }
    }
</style>

<script>
    (function () {
        const searchInput = document.getElementById('job-search-input');
        const selectDept = document.getElementById('filter-dept');
        const selectSetup = document.getElementById('filter-setup');
        const selectType = document.getElementById('filter-type');
        const cards = document.querySelectorAll('.job-glass-card');
        const noRes = document.getElementById('no-results');
        const grid = document.getElementById('jobs-grid');
        const count = document.getElementById('visible-count');

        // Helper to convert standard select to premium custom dropdown
        function createCustomDropdown(selectEl) {
            if (!selectEl) return;
            selectEl.style.display = 'none';

            const wrapper = document.createElement('div');
            wrapper.className = 'kg-custom-select';

            const trigger = document.createElement('div');
            trigger.className = 'kg-select-trigger';
            trigger.setAttribute('tabindex', '0');
            trigger.setAttribute('role', 'combobox');
            trigger.setAttribute('aria-haspopup', 'listbox');
            trigger.setAttribute('aria-expanded', 'false');
            trigger.setAttribute('aria-controls', 'options-' + selectEl.id);
            trigger.setAttribute('aria-label', selectEl.options[0] ? selectEl.options[0].text : 'Filter selection');
            
            const triggerText = selectEl.options[selectEl.selectedIndex] ? selectEl.options[selectEl.selectedIndex].text : '';
            trigger.innerHTML = `<span>${triggerText}</span><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>`;

            const optionsPanel = document.createElement('div');
            optionsPanel.className = 'kg-select-options';
            optionsPanel.setAttribute('role', 'listbox');
            optionsPanel.setAttribute('id', 'options-' + selectEl.id);

            Array.from(selectEl.options).forEach((opt, idx) => {
                const optDiv = document.createElement('div');
                optDiv.className = 'kg-option';
                if (idx === selectEl.selectedIndex) optDiv.classList.add('selected');
                optDiv.textContent = opt.text;
                optDiv.dataset.value = opt.value;
                optDiv.setAttribute('role', 'option');
                optDiv.setAttribute('tabindex', '0');
                optDiv.setAttribute('aria-selected', idx === selectEl.selectedIndex ? 'true' : 'false');

                optDiv.addEventListener('click', (e) => {
                    e.stopPropagation();
                    selectEl.value = opt.value;
                    trigger.querySelector('span').textContent = opt.text;
                    
                    optionsPanel.querySelectorAll('.kg-option').forEach(o => {
                        o.classList.remove('selected');
                        o.setAttribute('aria-selected', 'false');
                    });
                    optDiv.classList.add('selected');
                    optDiv.setAttribute('aria-selected', 'true');

                    wrapper.classList.remove('open');
                    trigger.setAttribute('aria-expanded', 'false');
                    selectEl.dispatchEvent(new Event('change'));
                });

                optDiv.addEventListener('keydown', (e) => {
                    if (e.key === ' ' || e.key === 'Enter') {
                        e.preventDefault();
                        optDiv.click();
                        trigger.focus();
                    } else if (e.key === 'ArrowDown') {
                        e.preventDefault();
                        const next = optDiv.nextElementSibling;
                        if (next && next.classList.contains('kg-option')) next.focus();
                    } else if (e.key === 'ArrowUp') {
                        e.preventDefault();
                        const prev = optDiv.previousElementSibling;
                        if (prev && prev.classList.contains('kg-option')) prev.focus();
                    } else if (e.key === 'Escape') {
                        e.preventDefault();
                        wrapper.classList.remove('open');
                        trigger.setAttribute('aria-expanded', 'false');
                        trigger.focus();
                    }
                });

                optionsPanel.appendChild(optDiv);
            });

            wrapper.appendChild(trigger);
            wrapper.appendChild(optionsPanel);
            selectEl.parentNode.insertBefore(wrapper, selectEl.nextSibling);

            trigger.addEventListener('click', (e) => {
                e.stopPropagation();
                const isOpen = wrapper.classList.contains('open');
                document.querySelectorAll('.kg-custom-select').forEach(other => {
                    if (other !== wrapper) {
                        other.classList.remove('open');
                        other.querySelector('.kg-select-trigger').setAttribute('aria-expanded', 'false');
                    }
                });
                wrapper.classList.toggle('open');
                trigger.setAttribute('aria-expanded', !isOpen ? 'true' : 'false');
            });

            trigger.addEventListener('keydown', (e) => {
                if (e.key === ' ' || e.key === 'Enter' || e.key === 'ArrowDown') {
                    e.preventDefault();
                    if (!wrapper.classList.contains('open')) {
                        trigger.click();
                    }
                    setTimeout(() => {
                        const firstOpt = optionsPanel.querySelector('.kg-option');
                        if (firstOpt) firstOpt.focus();
                    }, 50);
                }
            });
        }

        // Apply custom dropdown to our job filters
        document.querySelectorAll('.filter-select-wrapper select').forEach(createCustomDropdown);

        // Global click listener to close dropdowns when clicking outside
        document.addEventListener('click', () => {
            document.querySelectorAll('.kg-custom-select').forEach(drop => drop.classList.remove('open'));
        });

        function filterJobs() {
            const query = searchInput ? searchInput.value.toLowerCase().trim() : '';
            const dept = selectDept ? selectDept.value : 'all';
            const setup = selectSetup ? selectSetup.value : 'all';
            const type = selectType ? selectType.value : 'all';

            let visible = 0;

            cards.forEach(function (card) {
                const cardTitle = card.querySelector('.job-card-title').textContent.toLowerCase();
                const cardDept = card.dataset.department || '';
                const cardSetup = card.dataset.setup || '';
                const cardType = card.dataset.type || '';

                const matchesQuery = !query || cardTitle.includes(query) || cardDept.toLowerCase().includes(query);
                const matchesDept = dept === 'all' || cardDept === dept;
                const matchesSetup = setup === 'all' || cardSetup === setup;
                const matchesType = type === 'all' || cardType === type;

                if (matchesQuery && matchesDept && matchesSetup && matchesType) {
                    card.style.display = 'flex';
                    visible++;
                } else {
                    card.style.display = 'none';
                }
            });

            if (count) count.textContent = visible;
            if (noRes && grid) {
                noRes.style.display = visible === 0 ? 'block' : 'none';
                grid.style.display = visible === 0 ? 'none' : 'grid';
            }
        }

        if (searchInput) searchInput.addEventListener('input', filterJobs);
        if (selectDept) selectDept.addEventListener('change', filterJobs);
        if (selectSetup) selectSetup.addEventListener('change', filterJobs);
        if (selectType) selectType.addEventListener('change', filterJobs);
    })();
</script>

<?php get_footer(); ?>