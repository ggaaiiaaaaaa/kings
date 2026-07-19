<?php
/* Template Name: Our Jobs */
if (!defined('ABSPATH')) {
    require_once 'functions.php';
}
$page_title = 'Our Jobs | Kings Group Careers';
$page_description = 'Browse open positions at Kings Group Cooperative. Filter by Full-Time, Part-Time, Contract, and Remote roles across the Philippines.';

if (!function_exists('kg_get_region_by_location')) {
    function kg_get_region_by_location($location) {
        $location = strtoupper(trim($location));
        if (empty($location)) {
            return 'Other';
        }

        // 1. National Capital Region (NCR)
        if (strpos($location, 'METRO MANILA') !== false ||
            strpos($location, 'MANILA') !== false ||
            strpos($location, 'TAGUIG') !== false ||
            strpos($location, 'MAKATI') !== false ||
            strpos($location, 'PASAY') !== false ||
            strpos($location, 'QC') !== false ||
            strpos($location, 'QUEZON CITY') !== false ||
            strpos($location, 'GALLERIA') !== false ||
            strpos($location, 'ALABANG') !== false ||
            strpos($location, 'MOA') !== false ||
            strpos($location, 'PARAÑAQUE') !== false ||
            strpos($location, 'PARANAQUE') !== false ||
            strpos($location, 'CALOOCAN') !== false ||
            strpos($location, 'LAS PIÑAS') !== false ||
            strpos($location, 'LAS PINAS') !== false ||
            strpos($location, 'MALABON') !== false ||
            strpos($location, 'MANDALUYONG') !== false ||
            strpos($location, 'MARIKINA') !== false ||
            strpos($location, 'MUNTINLUPA') !== false ||
            strpos($location, 'NAVOTAS') !== false ||
            strpos($location, 'PASIG') !== false ||
            strpos($location, 'SAN JUAN') !== false ||
            strpos($location, 'VALENZUELA') !== false ||
            strpos($location, 'PATEROS') !== false ||
            strpos($location, 'NCR') !== false) {
            return 'National Capital Region (NCR)';
        }

        // 2. Cordillera Administrative Region (CAR)
        if (strpos($location, 'BAGUIO') !== false ||
            strpos($location, 'BENGUET') !== false ||
            strpos($location, 'ABRA') !== false ||
            strpos($location, 'APAYAO') !== false ||
            strpos($location, 'IFUGAO') !== false ||
            strpos($location, 'KALINGA') !== false ||
            strpos($location, 'MOUNTAIN PROVINCE') !== false ||
            strpos($location, 'CAR') !== false) {
            return 'Cordillera Administrative Region (CAR)';
        }

        // 3. Ilocos Region (Region I)
        if (strpos($location, 'PANGASINAN') !== false ||
            strpos($location, 'DAGUPAN') !== false ||
            strpos($location, 'LAOAG') !== false ||
            strpos($location, 'ILOCOS') !== false ||
            strpos($location, 'LA UNION') !== false ||
            strpos($location, 'VIGAN') !== false ||
            strpos($location, 'SAN FERNANDO, LA UNION') !== false ||
            strpos($location, 'REGION I') !== false) {
            return 'Ilocos Region (Region I)';
        }

        // 4. Cagayan Valley (Region II)
        if (strpos($location, 'TUGUEGARAO') !== false ||
            strpos($location, 'SOLANO') !== false ||
            strpos($location, 'NUEVA VIZCAYA') !== false ||
            strpos($location, 'CAUAYAN') !== false ||
            strpos($location, 'ISABELA') !== false ||
            strpos($location, 'BATANES') !== false ||
            strpos($location, 'CAGAYAN') !== false ||
            strpos($location, 'QUIRINO') !== false ||
            strpos($location, 'SANTIAGO') !== false ||
            strpos($location, 'REGION II') !== false) {
            return 'Cagayan Valley (Region II)';
        }

        // 5. Central Luzon (Region III)
        if (strpos($location, 'BULACAN') !== false ||
            strpos($location, 'MALOLOS') !== false ||
            strpos($location, 'PAMPANGA') !== false ||
            strpos($location, 'TARLAC') !== false ||
            strpos($location, 'BAMBAN') !== false ||
            strpos($location, 'CABANATUAN') !== false ||
            strpos($location, 'OLONGAPO') !== false ||
            strpos($location, 'MARILAO') !== false ||
            strpos($location, 'MABALACAT') !== false ||
            strpos($location, 'SUBIC') !== false ||
            strpos($location, 'AURORA') !== false ||
            strpos($location, 'BATAAN') !== false ||
            strpos($location, 'NUEVA ECIJA') !== false ||
            strpos($location, 'ZAMBALES') !== false ||
            strpos($location, 'ANGELES') !== false ||
            strpos($location, 'SAN FERNANDO, PAMPANGA') !== false ||
            strpos($location, 'REGION III') !== false) {
            return 'Central Luzon (Region III)';
        }

        // 6. CALABARZON (Region IV-A)
        if (strpos($location, 'IMUS') !== false ||
            strpos($location, 'LIMA') !== false ||
            strpos($location, 'BATANGAS') !== false ||
            strpos($location, 'LAGUNA') !== false ||
            strpos($location, 'BACOOR') !== false ||
            strpos($location, 'TANZA') !== false ||
            strpos($location, 'VERMOSA') !== false ||
            strpos($location, 'RIZAL') !== false ||
            strpos($location, 'CAVITE') !== false ||
            strpos($location, 'ANTIPOLO') !== false ||
            strpos($location, 'QUEZON') !== false ||
            strpos($location, 'LUCENA') !== false ||
            strpos($location, 'CALABARZON') !== false ||
            strpos($location, 'REGION IV-A') !== false) {
            return 'CALABARZON (Region IV-A)';
        }

        // 7. MIMAROPA Region
        if (strpos($location, 'MOGPOG') !== false ||
            strpos($location, 'MARINDUQUE') !== false ||
            strpos($location, 'MIMAROPA') !== false ||
            strpos($location, 'MINDORO') !== false ||
            strpos($location, 'PALAWAN') !== false ||
            strpos($location, 'ROMBLON') !== false ||
            strpos($location, 'PUERTO PRINCESA') !== false ||
            strpos($location, 'REGION IV-B') !== false) {
            return 'MIMAROPA Region';
        }

        // 8. Bicol Region (Region V)
        if (strpos($location, 'BICOL') !== false ||
            strpos($location, 'CAMARINES') !== false ||
            strpos($location, 'IRIGA') !== false ||
            strpos($location, 'TABACO') !== false ||
            strpos($location, 'DARAGA') !== false ||
            strpos($location, 'ALBAY') !== false ||
            strpos($location, 'NAGA') !== false ||
            strpos($location, 'LEGAZPI') !== false ||
            strpos($location, 'CATANDUANES') !== false ||
            strpos($location, 'MASBATE') !== false ||
            strpos($location, 'SORSOGON') !== false ||
            strpos($location, 'REGION V') !== false) {
            return 'Bicol Region (Region V)';
        }

        // 9. Western Visayas (Region VI)
        if (strpos($location, 'BACOLOD') !== false ||
            strpos($location, 'KABANKALAN') !== false ||
            strpos($location, 'ILOILO') !== false ||
            strpos($location, 'AKLAN') !== false ||
            strpos($location, 'ANTIQUE') !== false ||
            strpos($location, 'CAPIZ') !== false ||
            strpos($location, 'GUIMARAS') !== false ||
            strpos($location, 'NEGROS OCCIDENTAL') !== false ||
            strpos($location, 'REGION VI') !== false) {
            return 'Western Visayas (Region VI)';
        }

        // 10. Central Visayas (Region VII)
        if (strpos($location, 'CEBU') !== false ||
            strpos($location, 'BOHOL') !== false ||
            strpos($location, 'NEGROS ORIENTAL') !== false ||
            strpos($location, 'SIQUIJOR') !== false ||
            strpos($location, 'MANDAUE') !== false ||
            strpos($location, 'LAPU-LAPU') !== false ||
            strpos($location, 'DUMAGUETE') !== false ||
            strpos($location, 'TAGBILARAN') !== false ||
            strpos($location, 'REGION VII') !== false) {
            return 'Central Visayas (Region VII)';
        }

        // 11. Eastern Visayas (Region VIII)
        if (strpos($location, 'BILIRAN') !== false ||
            strpos($location, 'LEYTE') !== false ||
            strpos($location, 'SAMAR') !== false ||
            strpos($location, 'TACLOBAN') !== false ||
            strpos($location, 'ORMOC') !== false ||
            strpos($location, 'REGION VIII') !== false) {
            return 'Eastern Visayas (Region VIII)';
        }

        // 12. Zamboanga Peninsula (Region IX)
        if (strpos($location, 'ZAMBOANGA') !== false ||
            strpos($location, 'PAGADIAN') !== false ||
            strpos($location, 'DIPOLOG') !== false ||
            strpos($location, 'DAPITAN') !== false ||
            strpos($location, 'SIBUGAY') !== false ||
            strpos($location, 'ISABELA CITY') !== false ||
            strpos($location, 'REGION IX') !== false) {
            return 'Zamboanga Peninsula (Region IX)';
        }

        // 13. Northern Mindanao (Region X)
        if (strpos($location, 'BUKIDNON') !== false ||
            strpos($location, 'CAMIGUIN') !== false ||
            strpos($location, 'LANAO DEL NORTE') !== false ||
            strpos($location, 'MISAMIS') !== false ||
            strpos($location, 'CAGAYAN DE ORO') !== false ||
            strpos($location, 'CDO') !== false ||
            strpos($location, 'ILIGAN') !== false ||
            strpos($location, 'REGION X') !== false) {
            return 'Northern Mindanao (Region X)';
        }

        // 14. Davao Region (Region XI)
        if (strpos($location, 'TAGUM') !== false ||
            strpos($location, 'DAVAO') !== false ||
            strpos($location, 'REGION XI') !== false) {
            return 'Davao Region (Region XI)';
        }

        // 15. SOCCSKSARGEN (Region XII)
        if (strpos($location, 'MIDSAYAP') !== false ||
            strpos($location, 'COTABATO') !== false ||
            strpos($location, 'SARANGANI') !== false ||
            strpos($location, 'GENERAL SANTOS') !== false ||
            strpos($location, 'GENSAN') !== false ||
            strpos($location, 'KORONADAL') !== false ||
            strpos($location, 'SULTAN KUDARAT') !== false ||
            strpos($location, 'REGION XII') !== false) {
            return 'SOCCSKSARGEN (Region XII)';
        }

        // 16. Caraga (Region XIII)
        if (strpos($location, 'AGUSAN') !== false ||
            strpos($location, 'DINAGAT') !== false ||
            strpos($location, 'SURIGAO') !== false ||
            strpos($location, 'BUTUAN') !== false ||
            strpos($location, 'REGION XIII') !== false ||
            strpos($location, 'CARAGA') !== false) {
            return 'Caraga (Region XIII)';
        }

        // 17. Bangsamoro Autonomous Region in Muslim Mindanao (BARMM)
        if (strpos($location, 'SULU') !== false ||
            strpos($location, 'TAWI-TAWI') !== false ||
            strpos($location, 'BASILAN') !== false ||
            strpos($location, 'LANAO DEL SUR') !== false ||
            strpos($location, 'MAGUINDANAO') !== false ||
            strpos($location, 'BARMM') !== false) {
            return 'Bangsamoro Autonomous Region in Muslim Mindanao (BARMM)';
        }

        return 'Other';
    }
}

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
    style="background-image: linear-gradient(rgba(10, 37, 64, 0.8), rgba(10, 37, 64, 0.8)), url('<?php echo esc_url($hero_bg); ?>'); padding: 6rem 0 7rem 0;">
    <div class="container text-center">
        <h1 class="animate-on-scroll" style="font-size: clamp(3rem, 5vw, 4.5rem); font-weight: 800; line-height: 1.1; margin-bottom: 1.5rem;"><?php echo esc_html($hero_headline); ?></h1>
        <p class="animate-on-scroll" style="margin-bottom: 0; font-size: 1.15rem; color: rgba(255, 255, 255, 0.8);"><?php echo esc_html($hero_desc); ?></p>
    </div>
</section>

<?php
// Query all published jobs for filter population
$jobs_query = new WP_Query(array(
    'post_type' => 'jobs',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'tax_query' => array(
        array(
            'taxonomy' => 'job_type_tax',
            'field'    => 'slug',
            'terms'    => 'offshoring',
            'operator' => 'NOT IN',
        ),
    ),
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

<!-- Redesigned Search Panel below Hero -->
<section class="search-panel-section">
    <div class="container">
        <div class="job-search-card animate-on-scroll">
            <form id="job-search-form" onsubmit="event.preventDefault();">
                <!-- Row 1: Filters -->
                <div class="search-filters-row">
                    <!-- Title or Keyword -->
                    <div class="filter-group keyword-group">
                        <label for="job-search-input"><?php echo esc_html(kg_get_field('jobs_search_keyword_label', 'Title or Keyword')); ?></label>
                        <div class="input-pill-wrapper">
                            <input type="text" id="job-search-input" placeholder="<?php echo esc_attr(kg_get_field('jobs_search_keyword_placeholder', 'Type Here')); ?>" autocomplete="off">
                        </div>
                    </div>

                    <!-- Select Region -->
                    <div class="filter-group">
                        <label for="filter-region"><?php echo esc_html(kg_get_field('jobs_search_region_label', 'Select Region')); ?></label>
                        <div class="select-pill-wrapper">
                            <div class="custom-select-container" id="custom-select-region">
                                <div class="custom-select-trigger">
                                    <span class="selected-text"><?php echo esc_html(kg_get_field('jobs_search_region_label', 'Select Region')); ?></span>
                                    <svg class="chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                                </div>
                                <div class="custom-options-menu"></div>
                            </div>
                            <select id="filter-region" style="display: none;">
                                <option value="all"><?php echo esc_html(kg_get_field('jobs_search_region_label', 'Select Region')); ?></option>
                            </select>
                        </div>
                    </div>

                    <!-- Select Location -->
                    <div class="filter-group">
                        <label for="filter-location"><?php echo esc_html(kg_get_field('jobs_search_location_label', 'Select Location')); ?></label>
                        <div class="select-pill-wrapper">
                            <div class="custom-select-container disabled" id="custom-select-location">
                                <div class="custom-select-trigger">
                                    <span class="selected-text"><?php echo esc_html(kg_get_field('jobs_search_location_label', 'Select Location')); ?></span>
                                    <svg class="chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                                </div>
                                <div class="custom-options-menu"></div>
                            </div>
                            <select id="filter-location" style="display: none;" disabled>
                                <option value="all"><?php echo esc_html(kg_get_field('jobs_search_location_label', 'Select Location')); ?></option>
                            </select>
                        </div>
                    </div>

                    <!-- Search button -->
                    <div class="filter-group btn-group">
                        <button type="button" id="search-submit-btn" class="search-btn-pill">
                            <?php echo esc_html(kg_get_field('jobs_search_btn_text', 'SEARCH JOB')); ?> <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 5px;"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        </button>
                    </div>
                </div>

                <hr class="search-panel-divider">

                <!-- Row 2: CV and Popular Search -->
                <div class="search-extra-row">
                    <!-- Left: Upload CV (opens application modal) -->
                    <div class="cv-upload-column" id="cv-upload-column-trigger" style="cursor:pointer;" title="Click to upload your CV">
                        <h4 class="extra-group-title" onclick="openPoolingModal()"><?php echo esc_html(kg_get_field('jobs_upload_cv_label', 'Upload Your CV')); ?></h4>
                        <div class="cv-dropzone-inline-container">
                            <div class="cv-dropzone-inline cv-dropzone-clickable" id="inline-cv-dropzone" onclick="openPoolingModal(); event.stopPropagation();">
                                <span><?php echo esc_html(kg_get_field('jobs_drag_drop_text', 'Drag and drop your document here or browse for a document to upload')); ?></span>
                            </div>
                            <input type="file" id="inline-cv-upload" style="display:none;" accept=".pdf,.doc,.docx">
                            <div class="cv-upload-note">
                                <?php echo esc_html(kg_get_field('jobs_file_format_note', 'File names cannot contain spaces or underscores and should be in either .doc, .docx, or .pdf format.')); ?>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Popular Search -->
                    <div class="popular-search-column">
                        <h4 class="extra-group-title"><?php echo esc_html(kg_get_field('jobs_popular_searches_label', 'Popular Search:')); ?></h4>
                        <div class="popular-tags-container">
                            <?php
                            $pop_tags_raw = kg_get_field('jobs_popular_searches_tags');
                            if (!empty($pop_tags_raw)) {
                                $pop_tags = explode("\n", $pop_tags_raw);
                            } else {
                                $pop_tags = array('Service Crew', 'Merchandiser', 'Sales Associate', 'Warehouseman', 'Driver', 'Production Helper');
                            }
                            foreach ($pop_tags as $tag) {
                                $tag = trim($tag);
                                if (empty($tag)) continue;
                                echo '<button type="button" class="popular-tag-badge" data-tag="' . esc_attr($tag) . '">' . esc_html($tag) . '</button>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </form>
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

                    // Skip jobs that are fully filled or closed
                    $is_closed = get_post_meta(get_the_ID(), 'job_closed', true);
                    if ($is_closed || ($target_hc > 0 && $filled_hc >= $target_hc)) {
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
                    
                    $job_reg = kg_get_region_by_location($job_location);
                    ?>
                    <article class="job-glass-card" 
                        data-type="<?php echo esc_attr($job_type); ?>"
                        data-department="<?php echo esc_attr($job_dept); ?>"
                        data-setup="<?php echo esc_attr($work_setup); ?>"
                        data-location="<?php echo esc_attr(strtoupper(trim($job_location))); ?>"
                        data-region="<?php echo esc_attr($job_reg); ?>">

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
                                <a href="javascript:void(0)" onclick="openApplyModal('<?php echo esc_js(get_the_title()); ?>')" class="btn btn-primary btn-sm">Apply Now</a>
                            </div>
                        </div>
                    </article>
                <?php endwhile;
                wp_reset_postdata(); ?>

            </div>

            <!-- Empty filter state -->
            <div id="oj-pagination" class="oj-pagination"></div>
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


<!-- CV Application Modal — Our Jobs (2-Step) -->
<div id="oj-pooling-overlay" class="oj-modal-overlay" onclick="if(event.target===this)closePoolingModal()">
    <div class="oj-modal-box" role="dialog" aria-modal="true" aria-labelledby="oj-modal-title">

        <!-- Header -->
        <div class="oj-modal-header">
            <div class="oj-modal-header-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            </div>
            <div style="flex:1;">
                <h2 id="oj-modal-title" class="oj-modal-title">Submit Your CV</h2>
                <p class="oj-modal-subtitle">Join the Kings Group talent network</p>
            </div>
            <div class="oj-step-pill">
                <span id="oj-step-current">1</span><span style="opacity:0.45;">&thinsp;/&thinsp;2</span>
            </div>
            <button class="oj-modal-close" onclick="closePoolingModal()" aria-label="Close">&times;</button>
        </div>

        <!-- Step stepper indicator -->
        <div class="oj-stepper-bar">
            <div class="oj-stepper-track">
                <div class="oj-stepper-step active" id="oj-stepper-1">
                    <div class="oj-stepper-circle"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg></div>
                    <span>Upload CV</span>
                </div>
                <div class="oj-stepper-line" id="oj-stepper-line"></div>
                <div class="oj-stepper-step" id="oj-stepper-2">
                    <div class="oj-stepper-circle"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>
                    <span>Your Details</span>
                </div>
            </div>
            <div class="oj-progress-bar"><div id="oj-progress-fill" class="oj-progress-fill" style="width:50%"></div></div>
        </div>

        <!-- ===== STEP 1: Purpose + Upload CV ===== -->
        <div id="oj-step-1" class="oj-step-panel">

            <!-- Purpose Section -->
            <div class="oj-s1-section">
                <div class="oj-s1-section-header">
                    <div class="oj-s1-section-num">01</div>
                    <div>
                        <div class="oj-s1-section-title">Why are you applying?</div>
                        <div class="oj-s1-section-sub">Choose the option that best describes you</div>
                    </div>
                </div>

                <div class="oj-purpose-row">
                    <label class="oj-purpose-card oj-purpose-active" id="oj-card-pooling">
                        <input type="radio" name="oj_purpose" value="pooling" checked>
                        <div class="oj-purpose-badge">Pooling</div>
                        <div class="oj-purpose-icon">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                            </svg>
                        </div>
                        <div class="oj-purpose-label">For Pooling</div>
                        <div class="oj-purpose-desc">Add me to your talent pool for future openings. I'm open to opportunities.</div>
                        <div class="oj-purpose-check">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                    </label>

                    <label class="oj-purpose-card" id="oj-card-job">
                        <input type="radio" name="oj_purpose" value="looking_for_job">
                        <div class="oj-purpose-badge oj-badge-blue">Active</div>
                        <div class="oj-purpose-icon">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"/>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                            </svg>
                        </div>
                        <div class="oj-purpose-label">Looking for a Job</div>
                        <div class="oj-purpose-desc">I'm actively job hunting and want to be considered for current openings.</div>
                        <div class="oj-purpose-check">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Divider -->
            <div class="oj-s1-divider"></div>

            <!-- CV Upload Section -->
            <div class="oj-s1-section">
                <div class="oj-s1-section-header">
                    <div class="oj-s1-section-num">02</div>
                    <div>
                        <div class="oj-s1-section-title">Upload your CV</div>
                        <div class="oj-s1-section-sub">PDF, DOC or DOCX — max 5 MB</div>
                    </div>
                </div>

                <div id="oj-cv-dropzone" class="oj-dropzone" onclick="document.getElementById('oj-cv-input').click()">
                    <div class="oj-dropzone-icon">
                        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#ffd166" stroke-width="1.7"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                    </div>
                    <p class="oj-dropzone-text"><strong>Drag &amp; drop your CV here</strong><br><span>or <u>click to browse files</u></span></p>
                    <p class="oj-dropzone-hint">PDF &nbsp;·&nbsp; DOC &nbsp;·&nbsp; DOCX &nbsp;—&nbsp; Max 5 MB</p>
                    <input type="file" id="oj-cv-input" style="display:none;" accept=".pdf,.doc,.docx">
                </div>

                <div id="oj-file-info" class="oj-file-info">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    <span id="oj-file-name" class="oj-file-name"></span>
                    <button type="button" onclick="ojRemoveFile()" class="oj-file-remove">✕ Remove</button>
                </div>
            </div>

            <!-- Step 1 Footer -->
            <div class="oj-step-footer">
                <p class="oj-file-note">File names should not contain spaces or underscores</p>
                <button type="button" id="oj-continue-btn" class="oj-submit-btn" onclick="ojGoStep2()" disabled>
                    Next: Your Details
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </button>
            </div>
        </div>

        <!-- ===== STEP 2: Your Info ===== -->
        <div id="oj-step-2" class="oj-step-panel" style="display:none;">
            <div class="oj-form-body">
                <!-- Name row -->
                <div class="oj-field-section-label" style="margin-top:0.25rem;">Personal Information</div>
                <div class="oj-field-row oj-three-col">
                    <div class="oj-field">
                        <label class="oj-label">First Name <span class="oj-req">*</span></label>
                        <input type="text" id="oj-fname" class="oj-input" placeholder="Juan">
                    </div>
                    <div class="oj-field">
                        <label class="oj-label">Middle Name</label>
                        <input type="text" id="oj-mname" class="oj-input" placeholder="Optional">
                    </div>
                    <div class="oj-field">
                        <label class="oj-label">Last Name <span class="oj-req">*</span></label>
                        <input type="text" id="oj-lname" class="oj-input" placeholder="dela Cruz">
                    </div>
                </div>

                <!-- Contact row -->
                <div class="oj-field-row oj-two-col">
                    <div class="oj-field">
                        <label class="oj-label">Email</label>
                        <input type="email" id="oj-email" class="oj-input" placeholder="you@email.com">
                    </div>
                    <div class="oj-field">
                        <label class="oj-label">Phone <span class="oj-req">*</span></label>
                        <input type="tel" id="oj-phone" class="oj-input" placeholder="+63 9XX XXX XXXX">
                    </div>
                </div>

                <!-- Gender + Birthday -->
                <div class="oj-field-row oj-two-col">
                    <div class="oj-field">
                        <label class="oj-label">Gender <span class="oj-req">*</span></label>
                        <select id="oj-gender" class="oj-input">
                            <option value="" disabled selected>Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Non-Binary">Non-Binary</option>
                            <option value="Prefer not to say">Prefer not to say</option>
                        </select>
                    </div>
                    <div class="oj-field">
                        <label class="oj-label">Birthdate <span class="oj-req">*</span></label>
                        <input type="date" id="oj-birthday" class="oj-input">
                    </div>
                </div>

                <!-- Address -->
                <div class="oj-field-section-label">Address</div>
                <div class="oj-field">
                    <input type="text" id="oj-street" class="oj-input" placeholder="Street / Barangay">
                </div>
                <div class="oj-field-row" style="margin-top:0.6rem; display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                    <div style="grid-column: span 2;">
                        <select id="oj-region" class="oj-input" onchange="this.title = this.options[this.selectedIndex]?.text || '';"><option value="">Region</option></select>
                    </div>
                    <div>
                        <select id="oj-city" class="oj-input" disabled onchange="this.title = this.options[this.selectedIndex]?.text || '';"><option value="">City / Municipality</option></select>
                    </div>
                    <div>
                        <select id="oj-barangay" class="oj-input" disabled onchange="this.title = this.options[this.selectedIndex]?.text || '';"><option value="">Barangay</option></select>
                    </div>
                </div>
                <input type="hidden" id="oj_region_code">
                <input type="hidden" id="oj_city_code">
                <input type="hidden" id="oj_barangay_code">

                <!-- Preferred Role Checkboxes -->
                <div class="oj-preferred-role-banner" style="margin-top: 0.5rem;">
                    <div style="display: flex; align-items: center; gap: 0.85rem;">
                        <div class="oj-pr-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                        </div>
                        <div class="oj-pr-content">
                            <label class="oj-pr-label">Preferred Role(s) <span class="oj-req">*</span></label>
                        </div>
                    </div>
                    <div class="oj-pr-checkbox-grid">
                        <?php
                        $oj_jobs_query = new WP_Query(array(
                            'post_type'      => 'jobs',
                            'post_status'    => 'publish',
                            'posts_per_page' => -1,
                            'tax_query'      => array(
                                array(
                                    'taxonomy' => 'job_type_tax',
                                    'field'    => 'slug',
                                    'terms'    => 'offshoring',
                                    'operator' => 'NOT IN',
                                ),
                            ),
                            'orderby'        => 'title',
                            'order'          => 'ASC'
                        ));
                        if ($oj_jobs_query->have_posts()) {
                            $unique_oj_titles = array();
                            while ($oj_jobs_query->have_posts()) {
                                $oj_jobs_query->the_post();
                                $job_title = trim(get_the_title());
                                $is_closed = get_post_meta(get_the_ID(), 'job_closed', true);
                                if ($is_closed) {
                                    continue;
                                }
                                if (in_array(strtolower($job_title), array_map('strtolower', $unique_oj_titles))) {
                                    continue;
                                }
                                $unique_oj_titles[] = $job_title;
                            }
                            wp_reset_postdata();
                            
                            if (!empty($unique_oj_titles)) {
                                foreach ($unique_oj_titles as $job_title) {
                                    echo '<label class="oj-checkbox-item">';
                                    echo '<input type="checkbox" name="oj_preferred_roles[]" value="' . esc_attr($job_title) . '" class="oj-checkbox-input">';
                                    echo '<span class="oj-checkbox-text">' . esc_html($job_title) . '</span>';
                                    echo '</label>';
                                }
                            } else {
                                echo '<p style="color:var(--text-muted);font-size:0.8rem;">No active positions available.</p>';
                            }
                        } else {
                            echo '<p style="color:var(--text-muted);font-size:0.8rem;">No active positions available.</p>';
                        }
                        ?>
                    </div>
                </div>
                <input type="hidden" id="oj-role" value="General Pooling">

                <!-- honeypot -->
                <div style="display:none;" aria-hidden="true"><input type="text" id="oj_hp" name="oj_hp" value="" tabindex="-1" autocomplete="off"></div>

                <!-- CAPTCHA -->
                <div class="cf-turnstile" data-sitekey="<?php echo esc_attr(defined('CF_TURNSTILE_SITE_KEY') ? CF_TURNSTILE_SITE_KEY : ''); ?>" data-appearance="interaction-only" style="margin-top:0.5rem;margin-bottom:0.5rem;"></div>

                <!-- Error -->
                <div id="oj-error-box" class="oj-error-box">
                    <span id="oj-error-msg"></span>
                </div>

                <!-- Step 2 Footer -->
                <div class="oj-form-footer">
                    <button type="button" class="oj-back-btn" onclick="ojGoStep1()">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                        Back
                    </button>
                    <label class="oj-privacy-label">
                        <input type="checkbox" id="oj-privacy" style="accent-color:var(--main-blue);">
                        <span>I agree to the <a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>" target="_blank">Privacy Policy</a> &amp; <a href="<?php echo esc_url(home_url('/terms/')); ?>" target="_blank">Terms</a></span>
                    </label>
                    <button type="button" id="oj-submit-btn" onclick="ojSubmitApplication()" class="oj-submit-btn">
                        Submit CV
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                    </button>
                </div>

            </div>
        </div>

    </div>
</div>

<!-- OJ Success Overlay -->
<div id="oj-success-overlay" class="oj-modal-overlay" onclick="if(event.target===this)closeOjSuccess()">
    <div class="oj-modal-box" style="max-width:420px;text-align:center;padding:3rem 2rem;">
        <button class="oj-modal-close" onclick="closeOjSuccess()" style="color:var(--text-muted);">&times;</button>
        <div class="oj-success-icon">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <h3 style="font-size:1.5rem;font-weight:700;margin-bottom:0.5rem;color:var(--text-dark);">CV Submitted!</h3>
        <p style="color:var(--text-muted);font-size:0.95rem;margin-bottom:1.75rem;">Our team will review your profile and reach out within 2–3 business days. Thank you!</p>
        <button onclick="closeOjSuccess()" class="oj-submit-btn" style="max-width:200px;margin:0 auto;">Done</button>
    </div>
</div>



<style>
    .page-hero {
        overflow: visible !important;
    }

    /* Redesigned Search Panel styling */
    .search-panel-section {
        padding: 3rem 0;
        margin-top: 2rem; /* Keep distance from the hero section */
        position: relative;
        z-index: 99;
    }

    .job-search-card {
        background: #ffffff;
        border-radius: 20px;
        padding: 2.25rem;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(0, 0, 0, 0.06);
    }

    .search-filters-row {
        display: flex;
        flex-wrap: wrap;
        align-items: flex-end;
        gap: 1.25rem;
    }

    .filter-group {
        flex: 1;
        min-width: 180px;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .filter-group.keyword-group {
        flex: 1.3;
    }

    .filter-group.btn-group {
        flex: 0.9;
        min-width: 150px;
        justify-content: flex-end;
    }

    .filter-group label {
        font-family: var(--font-header);
        font-weight: 700;
        font-size: 0.9rem;
        color: var(--text-dark);
        margin: 0;
        text-align: left;
    }

    .input-pill-wrapper, .select-pill-wrapper {
        position: relative;
        width: 100%;
    }

    .input-pill-wrapper input, .select-pill-wrapper select {
        width: 100%;
        padding: 0.75rem 1.25rem;
        font-size: 0.9rem;
        color: var(--text-dark);
        background: #ffffff;
        border: 2px solid #ffd166; /* Themed gold/orange border */
        border-radius: 100px;
        outline: none;
        transition: all 0.3s ease;
        font-family: var(--font-body);
        height: 48px;
        box-sizing: border-box;
    }

    .select-pill-wrapper select {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        padding-right: 2.25rem;
        cursor: pointer;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23ffd166' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: calc(100% - 1rem) center;
    }

    /* Custom Dropdown Styling */
    .custom-select-container {
        position: relative;
        width: 100%;
        user-select: none;
    }

    .custom-select-trigger {
        width: 100%;
        padding: 0.75rem 1.25rem;
        font-size: 0.9rem;
        color: var(--text-dark);
        background: #ffffff;
        border: 2px solid #ffd166;
        border-radius: 100px;
        outline: none;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        font-family: var(--font-body);
        height: 48px;
        box-sizing: border-box;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
    }

    .custom-select-trigger .selected-text {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        flex: 1;
        text-align: left;
        margin-right: 0.5rem;
    }

    .custom-select-trigger:hover, .custom-select-container.active .custom-select-trigger {
        border-color: var(--main-blue);
        box-shadow: 0 0 10px rgba(10, 37, 64, 0.1);
    }

    .custom-select-trigger .chevron {
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        color: #ffd166;
    }

    .custom-select-container.active .custom-select-trigger .chevron {
        transform: rotate(180deg);
    }

    .custom-select-container.disabled .custom-select-trigger {
        opacity: 0.65;
        cursor: not-allowed;
        background: #f7fafc;
        border-color: #e2e8f0;
    }

    .custom-options-menu {
        position: absolute;
        top: calc(100% + 8px);
        left: 0;
        width: 100%;
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(10, 37, 64, 0.08);
        border-radius: 16px;
        box-shadow: 0 15px 40px rgba(10, 37, 64, 0.12);
        z-index: 1000;
        max-height: 260px;
        overflow-y: auto;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        padding: 0.5rem 0;
    }

    .custom-select-container.active .custom-options-menu {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    /* Premium Scrollbar */
    .custom-options-menu::-webkit-scrollbar {
        width: 6px;
    }
    .custom-options-menu::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-options-menu::-webkit-scrollbar-thumb {
        background: rgba(10, 37, 64, 0.12);
        border-radius: 100px;
    }
    .custom-options-menu::-webkit-scrollbar-thumb:hover {
        background: rgba(10, 37, 64, 0.25);
    }

    .custom-option-item {
        padding: 0.7rem 1.25rem;
        font-size: 0.88rem;
        color: var(--text-dark);
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .custom-option-item:hover {
        background: rgba(255, 209, 102, 0.12);
        color: var(--main-blue);
        padding-left: 1.5rem;
    }

    .custom-option-item.selected {
        background: rgba(10, 37, 64, 0.05);
        color: var(--main-blue);
        font-weight: 700;
        border-left: 3px solid #ffd166;
    }

    .input-pill-wrapper input::placeholder {
        color: #cbd5e0;
    }

    .input-pill-wrapper input:focus, .select-pill-wrapper select:focus {
        border-color: var(--main-blue);
        box-shadow: 0 0 10px rgba(10, 37, 64, 0.15);
    }

    .search-btn-pill {
        width: 100%;
        height: 48px;
        font-size: 0.85rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        color: #ffffff;
        background: var(--main-blue); /* Premium primary blue button */
        border: none;
        border-radius: 100px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        text-transform: uppercase;
        box-sizing: border-box;
    }

    .search-btn-pill:hover {
        background: var(--main-blue-hover);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(10, 37, 64, 0.18);
    }

    .search-btn-pill:active {
        transform: translateY(0);
    }

    .search-panel-divider {
        border: 0;
        height: 1px;
        background: rgba(0, 0, 0, 0.06);
        margin: 1.75rem 0;
    }

    /* Row 2 CV & Popular Search styling */
    .search-extra-row {
        display: grid;
        grid-template-columns: 1.2fr 1fr;
        gap: 2.5rem;
    }

    .cv-upload-column {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .extra-group-title {
        font-family: var(--font-header);
        font-weight: 700;
        font-size: 1rem;
        color: var(--text-dark);
        margin: 0;
        text-align: left;
    }

    .cv-dropzone-inline-container {
        display: flex;
        align-items: center;
        gap: 1.25rem;
    }

    .cv-dropzone-inline {
        flex: 1.4;
        border: 2px dashed #ffd166;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        text-align: center;
        background: rgba(255, 209, 102, 0.02);
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 60px;
    }

    .cv-dropzone-inline span {
        font-size: 0.8rem;
        color: #4a5568;
        line-height: 1.4;
    }

    .cv-dropzone-inline .browse-link {
        color: var(--main-blue-light);
        font-weight: 700;
        text-decoration: underline;
    }

    .cv-dropzone-inline:hover, .cv-dropzone-inline.drag-over {
        background: rgba(10, 37, 64, 0.02);
        border-color: var(--main-blue);
    }

    .cv-upload-note {
        flex: 1;
        font-size: 0.75rem;
        color: #718096;
        line-height: 1.4;
        text-align: left;
    }

    .popular-search-column {
        border-left: 1px solid rgba(0, 0, 0, 0.06);
        padding-left: 2.5rem;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .popular-tags-container {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .popular-tag-badge {
        background: #edf2f7;
        color: #4a5568;
        border: none;
        border-radius: 6px;
        padding: 0.4rem 0.85rem;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .popular-tag-badge:hover {
        background: var(--main-blue);
        color: #ffffff;
    }

    @media (max-width: 992px) {
        .search-filters-row {
            grid-template-columns: repeat(2, 1fr);
            display: grid;
        }
        .filter-group.btn-group {
            grid-column: span 2;
        }
        .search-extra-row {
            grid-template-columns: 1fr;
            gap: 1.75rem;
        }
        .popular-search-column {
            border-left: none;
            padding-left: 0;
            border-top: 1px solid rgba(0, 0, 0, 0.06);
            padding-top: 1.5rem;
        }
    }

    @media (max-width: 576px) {
        .search-filters-row {
            grid-template-columns: 1fr;
        }
        .filter-group.btn-group {
            grid-column: span 1;
        }
        .cv-dropzone-inline-container {
            flex-direction: column;
            align-items: stretch;
            gap: 0.75rem;
        }
        .cv-upload-note {
            text-align: center;
        }
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
        border-radius: 0;
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

    /* Modal Wizard Styles */
    .modal-cprog-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.4rem;
        min-width: 80px;
    }
    .modal-cprog-circle {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: 2px solid var(--border-color);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.85rem;
        color: var(--text-muted);
        background: var(--bg-white);
        transition: var(--transition);
    }
    .modal-cprog-step span {
        font-size: 0.75rem;
        color: var(--text-muted);
        font-weight: 500;
    }
    .modal-cprog-step.active .modal-cprog-circle {
        background: var(--main-blue);
        color: #fff;
        border-color: var(--main-blue);
    }
    .modal-cprog-step.active span {
        color: var(--main-blue);
        font-weight: 600;
    }
    .modal-cprog-step.done .modal-cprog-circle {
        background: var(--sec-accent-green);
        color: #fff;
        border-color: var(--sec-accent-green);
    }
    .modal-cprog-line {
        flex: 1;
        height: 2px;
        background: var(--border-color);
        max-width: 100px;
        margin: 0 0.5rem;
        margin-top: -1rem;
        transition: var(--transition);
    }
    .modal-cprog-line.active {
        background: var(--main-blue);
    }
    .modal-cprog-line.done {
        background: var(--sec-accent-green);
    }
    .modal-career-step {
        display: none;
    }
    .modal-career-step.active {
        display: block;
    }
    #modal-cv-dropzone.drag-over {
        border-color: var(--sec-accent-green);
        background: rgba(0, 208, 156, 0.04);
    }

    /* =============================================
       OJ Modal — Redesigned (Professional 2-Step)
       ============================================= */

    /* Overlay */
    .oj-modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(5, 20, 45, 0.75);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        z-index: 99999;
        align-items: center;
        justify-content: center;
        padding: 2rem 1rem;
    }
    .oj-modal-overlay.visible { display: flex; }

    /* Box */
    .oj-modal-box {
        background: #fff;
        border-radius: 24px;
        max-width: 580px;
        width: 100%;
        max-height: 85vh;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        box-shadow: 0 40px 80px rgba(5,20,45,0.28), 0 0 0 1px rgba(255,255,255,0.05);
        margin: auto;
        animation: ojSlideIn 0.35s cubic-bezier(0.16,1,0.3,1);
    }
    @keyframes ojSlideIn {
        from { opacity: 0; transform: translateY(30px) scale(0.98); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }

    /* Close */
    .oj-modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        color: #fff;
        cursor: pointer;
        line-height: 1;
        opacity: 0.7;
        transition: opacity 0.2s;
        margin-left: auto;
        padding: 0;
    }
    .oj-modal-close:hover { opacity: 1; }

    /* Header */
    .oj-modal-header {
        background: linear-gradient(135deg, var(--main-blue) 0%, #0e3d70 100%);
        padding: 1.4rem 1.75rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        color: #fff;
    }
    .oj-modal-header-icon {
        width: 44px;
        height: 44px;
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.15);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: #ffd166;
    }
    .oj-modal-title {
        font-size: 1.15rem;
        font-weight: 700;
        margin: 0;
        color: #fff;
        letter-spacing: -0.01em;
    }
    .oj-modal-subtitle {
        font-size: 0.8rem;
        color: rgba(255,255,255,0.6);
        margin: 0.15rem 0 0;
    }

    /* ── Stepper Bar ── */
    .oj-stepper-bar {
        background: #f8fafc;
        border-bottom: 1px solid #eef2f7;
    }
    .oj-stepper-track {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0;
        padding: 1rem 2rem 0.75rem;
    }
    .oj-stepper-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.3rem;
    }
    .oj-stepper-step span {
        font-size: 0.7rem;
        font-weight: 600;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        transition: color 0.3s;
    }
    .oj-stepper-step.active span { color: var(--main-blue); }
    .oj-stepper-step.done span { color: #16a34a; }
    .oj-stepper-circle {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: 2px solid #e2e8f0;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        transition: all 0.3s cubic-bezier(0.16,1,0.3,1);
    }
    .oj-stepper-step.active .oj-stepper-circle {
        border-color: var(--main-blue);
        background: var(--main-blue);
        color: #fff;
        box-shadow: 0 4px 12px rgba(10,37,64,0.25);
    }
    .oj-stepper-step.done .oj-stepper-circle {
        border-color: #16a34a;
        background: #16a34a;
        color: #fff;
    }
    .oj-stepper-line {
        flex: 1;
        height: 2px;
        background: #e2e8f0;
        max-width: 80px;
        margin: 0 0.75rem;
        margin-bottom: 1.25rem;
        border-radius: 2px;
        transition: background 0.4s;
    }
    .oj-stepper-line.active { background: var(--main-blue); }
    .oj-stepper-line.done   { background: #16a34a; }
    .oj-progress-bar {
        height: 3px;
        background: transparent;
        overflow: hidden;
    }
    .oj-progress-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--main-blue), #ffd166);
        transition: width 0.45s cubic-bezier(0.4,0,0.2,1);
    }

    /* ── Step 1 Layout ── */
    .oj-s1-section {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    .oj-s1-section-header {
        display: flex;
        align-items: flex-start;
        gap: 0.85rem;
    }
    .oj-s1-section-num {
        font-size: 0.7rem;
        font-weight: 800;
        color: #ffd166;
        background: var(--main-blue);
        border-radius: 6px;
        padding: 0.2rem 0.45rem;
        letter-spacing: 0.04em;
        flex-shrink: 0;
        margin-top: 0.15rem;
    }
    .oj-s1-section-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--text-dark);
        line-height: 1.2;
    }
    .oj-s1-section-sub {
        font-size: 0.78rem;
        color: var(--text-muted);
        margin-top: 0.1rem;
    }
    .oj-s1-divider {
        height: 1px;
        background: linear-gradient(to right, transparent, #e2e8f0, transparent);
        margin: 0.25rem 0;
    }

    /* ── Purpose Cards ── */
    .oj-purpose-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.85rem;
    }
    .oj-purpose-card {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 0.35rem;
        padding: 1.25rem 1.25rem 1.1rem;
        cursor: pointer;
        border: 2px solid #e8edf4;
        border-radius: 16px;
        background: #fafbfc;
        transition: all 0.25s cubic-bezier(0.16,1,0.3,1);
        overflow: hidden;
    }
    .oj-purpose-card::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(255,209,102,0.06), transparent);
        opacity: 0;
        transition: opacity 0.25s;
    }
    .oj-purpose-card input { display: none; }
    .oj-purpose-card:hover {
        border-color: rgba(255,209,102,0.6);
        background: #fff;
        box-shadow: 0 4px 20px rgba(10,37,64,0.07);
    }
    .oj-purpose-card:hover::before { opacity: 1; }
    .oj-purpose-active,
    .oj-purpose-card:has(input:checked) {
        border-color: #ffd166;
        background: #fff;
        box-shadow: 0 6px 24px rgba(245,166,35,0.15);
    }
    .oj-purpose-active::before,
    .oj-purpose-card:has(input:checked)::before { opacity: 1; }
    .oj-purpose-badge {
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        padding: 0.2rem 0.55rem;
        border-radius: 100px;
        background: rgba(255,209,102,0.15);
        color: #a07800;
        margin-bottom: 0.5rem;
    }
    .oj-badge-blue {
        background: rgba(10,37,64,0.07);
        color: var(--main-blue);
    }
    .oj-purpose-icon {
        color: #cbd5e0;
        margin-bottom: 0.25rem;
        transition: color 0.25s;
    }
    .oj-purpose-active .oj-purpose-icon,
    .oj-purpose-card:has(input:checked) .oj-purpose-icon { color: #f5a623; }
    .oj-purpose-label {
        font-weight: 700;
        font-size: 0.95rem;
        color: var(--text-dark);
        line-height: 1.2;
    }
    .oj-purpose-desc {
        font-size: 0.78rem;
        color: var(--text-muted);
        line-height: 1.45;
        margin-top: 0.1rem;
    }
    .oj-purpose-check {
        position: absolute;
        top: 0.85rem;
        right: 0.85rem;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: transparent;
        transition: all 0.25s;
    }
    .oj-purpose-active .oj-purpose-check,
    .oj-purpose-card:has(input:checked) .oj-purpose-check {
        background: #f5a623;
        color: #fff;
    }

    /* ── Preferred Role Banner (Step 2) ── */
    .oj-preferred-role-banner {
        display: block;
        background: linear-gradient(135deg, rgba(10,37,64,0.03), rgba(255,209,102,0.05));
        border: 1.5px solid rgba(255,209,102,0.35);
        border-radius: 14px;
        padding: 1rem 1.1rem;
        margin-bottom: 0.25rem;
    }
    .oj-pr-icon {
        width: 38px;
        height: 38px;
        background: var(--main-blue);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffd166;
        flex-shrink: 0;
    }
    .oj-pr-content { flex: 1; display: flex; flex-direction: column; gap: 0.35rem; }
    .oj-pr-label {
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--main-blue);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .oj-pr-checkbox-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
        gap: 0.65rem;
        max-height: 150px;
        overflow-y: auto;
        padding: 0.8rem;
        background: #fff;
        border: 1.5px solid rgba(255,209,102,0.4);
        border-radius: 10px;
        box-sizing: border-box;
        margin-top: 0.5rem;
    }
    .oj-checkbox-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--text-dark);
        cursor: pointer;
        user-select: none;
        transition: color 0.2s;
    }
    .oj-checkbox-item:hover {
        color: var(--main-blue);
    }
    .oj-checkbox-input {
        width: 15px;
        height: 15px;
        accent-color: var(--main-blue);
        cursor: pointer;
        border-radius: 4px;
        border: 1.5px solid #cbd5e1;
        margin: 0;
        flex-shrink: 0;
    }
    .oj-checkbox-text {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Form body */
    .oj-form-body {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    /* Field rows */
    .oj-field-row {
        display: grid;
        gap: 0.75rem;
    }
    .oj-two-col   { grid-template-columns: 1fr 1fr; }
    .oj-three-col { grid-template-columns: 1fr 1fr 1fr; }

    .oj-field { display: flex; flex-direction: column; gap: 0.25rem; }

    .oj-label {
        font-size: 0.78rem;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.4px;
        padding-left: 0.25rem;
    }
    .oj-req { color: var(--neutral-yellow); }

    /* Inputs */
    .oj-input {
        width: 100%;
        padding: 0.7rem 1rem;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        font-family: var(--font-body);
        font-size: 0.875rem;
        color: var(--text-dark);
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
        background: #fafafa;
        box-sizing: border-box;
    }
    .oj-input:focus {
        border-color: var(--main-blue);
        background: #fff;
        box-shadow: 0 0 0 3px rgba(10,37,64,0.15);
    }
    select.oj-input {
        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: calc(100% - 0.75rem) center;
        padding-right: 2rem;
        cursor: pointer;
        height: 42px;
    }
    input[type="date"].oj-input {
        height: 42px;
        color: var(--text-dark);
    }
    /* Style date placeholder / empty indicator behavior */
    input[type="date"].oj-input:invalid {
        color: #b0b8c8;
    }
    input[type="date"].oj-input::-webkit-calendar-picker-indicator {
        cursor: pointer;
        opacity: 0.5;
        transition: opacity 0.2s;
    }
    input[type="date"].oj-input::-webkit-calendar-picker-indicator:hover {
        opacity: 0.8;
    }

    /* Section label */
    .oj-field-section-label {
        font-size: 0.78rem;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.4px;
        padding: 0.25rem 0 0;
        border-top: 1px solid #f1f5f9;
        padding-top: 0.75rem;
    }

    /* Dropzone */
    .oj-upload-row { display: flex; flex-direction: column; gap: 0.5rem; }

    .oj-dropzone {
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        padding: 1.25rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.25s ease;
        background: #f8fafc;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.4rem;
    }
    .oj-dropzone:hover, .oj-dropzone.drag-over {
        border-color: var(--main-blue);
        background: rgba(10, 37, 64, 0.02);
    }
    .oj-dropzone-text {
        margin: 0;
        font-size: 0.875rem;
        color: var(--text-dark);
        line-height: 1.4;
    }
    .oj-dropzone-text span { color: var(--text-muted); font-size: 0.8rem; }
    .oj-dropzone-hint {
        margin: 0;
        font-size: 0.72rem;
        color: #94a3b8;
        letter-spacing: 0.3px;
    }

    .oj-file-info {
        display: none;
        align-items: center;
        gap: 0.6rem;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 10px;
        padding: 0.65rem 1rem;
    }
    .oj-file-name {
        flex: 1;
        font-size: 0.85rem;
        font-weight: 600;
        color: #166534;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .oj-file-remove {
        background: none;
        border: none;
        cursor: pointer;
        font-size: 0.78rem;
        font-weight: 700;
        color: #dc2626;
        padding: 0;
        white-space: nowrap;
    }

    /* Error */
    .oj-error-box {
        display: none;
        background: #fef2f2;
        border: 1px solid #fca5a5;
        border-radius: 10px;
        padding: 0.65rem 1rem;
        font-size: 0.875rem;
        color: #991b1b;
    }

    /* Footer row */
    .oj-form-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
        padding-top: 0.5rem;
        border-top: 1px solid #f1f5f9;
        margin-top: 0.25rem;
    }
    .oj-privacy-label {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.78rem;
        color: var(--text-muted);
        cursor: pointer;
        flex: 1;
    }
    .oj-privacy-label a {
        color: var(--main-blue);
        font-weight: 600;
        text-decoration: none;
    }
    .oj-privacy-label a:hover { text-decoration: underline; }

    /* Submit button — inline in footer */
    .oj-submit-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.6rem;
        background: var(--main-blue);
        color: #fff;
        border: none;
        border-radius: 100px;
        font-family: var(--font-header);
        font-weight: 700;
        font-size: 0.88rem;
        letter-spacing: 0.04em;
        cursor: pointer;
        transition: all 0.25s ease;
        white-space: nowrap;
        flex-shrink: 0;
    }
    .oj-submit-btn:hover {
        background: #0e3060;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(10,37,64,0.2);
    }
    .oj-submit-btn:disabled { opacity: 0.6; pointer-events: none; }

    /* Success icon */
    .oj-success-icon {
        width: 72px;
        height: 72px;
        background: #f0fdf4;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.25rem;
    }

    /* CV column hover */
    .cv-upload-column { transition: all 0.3s ease; }
    .cv-upload-column:hover .cv-dropzone-inline {
        background: rgba(10, 37, 64, 0.04);
        border-color: var(--main-blue);
    }
    .cv-dropzone-clickable { cursor: pointer; }

    /* Responsive */
    /* Step wizard UI */
    .oj-progress-bar {
        height: 3px;
        background: #f1f5f9;
        overflow: hidden;
    }
    .oj-progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #f5a623, #ffd166);
        transition: width 0.4s cubic-bezier(0.4,0,0.2,1);
    }
    .oj-step-pill {
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.25);
        border-radius: 100px;
        padding: 0.25rem 0.75rem;
        font-size: 0.8rem;
        font-weight: 700;
        color: #fff;
        white-space: nowrap;
        flex-shrink: 0;
    }
    .oj-step-panel {
        padding: 1.5rem 2.25rem;
        overflow-y: auto;
        flex: 1;
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .oj-step-panel::-webkit-scrollbar {
        display: none;
    }
    .oj-step-intro {
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-muted);
        margin: 0 0 0.75rem;
    }
    .oj-dropzone-icon {
        width: 56px;
        height: 56px;
        background: rgba(245,166,35,0.08);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 0.5rem;
    }
    .oj-step-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-top: 1.25rem;
        padding-top: 1rem;
        border-top: 1px solid #f1f5f9;
        flex-wrap: wrap;
    }
    .oj-file-note {
        font-size: 0.72rem;
        color: #94a3b8;
        line-height: 1.4;
        flex: 1;
    }
    .oj-back-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        background: none;
        border: 1.5px solid #e2e8f0;
        border-radius: 100px;
        padding: 0.65rem 1.1rem;
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--text-muted);
        cursor: pointer;
        transition: all 0.2s;
        flex-shrink: 0;
        white-space: nowrap;
    }
    .oj-back-btn:hover {
        border-color: var(--main-blue);
        color: var(--main-blue);
    }
    /* Disabled continue btn */
    #oj-continue-btn:disabled {
        opacity: 0.45;
        cursor: not-allowed;
        transform: none !important;
        box-shadow: none !important;
    }
    /* Pagination CSS */
    .oj-pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.5rem;
        margin-top: 3rem;
    }
    .oj-pagination-btn {
        background: #fff;
        border: 1.5px solid var(--border-color);
        color: var(--text-dark);
        border-radius: 8px;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.2s ease;
        padding: 0;
        font-family: var(--font-header);
    }
    .oj-pagination-btn:hover {
        border-color: var(--main-blue);
        color: var(--main-blue);
        background: #f8fafc;
    }
    .oj-pagination-btn.active {
        background: var(--main-blue);
        border-color: var(--main-blue);
        color: #fff;
    }
    .oj-pagination-btn.disabled {
        opacity: 0.35;
        pointer-events: none;
    }
    @media (max-width: 560px) {
        .oj-step-panel { padding: 1.25rem 1.25rem; }
        .oj-step-footer { flex-direction: column; align-items: stretch; }
        .oj-submit-btn, .oj-back-btn { width: 100%; justify-content: center; }
    }
</style>


<script>
    (function () {
        const searchInput = document.getElementById('job-search-input');
        const selectRegion = document.getElementById('filter-region');
        const selectLocation = document.getElementById('filter-location');
        const searchBtn = document.getElementById('search-submit-btn');
        const cards = document.querySelectorAll('.job-glass-card');
        const noRes = document.getElementById('no-results');
        const grid = document.getElementById('jobs-grid');
        const count = document.getElementById('visible-count');

        const regionsList = [
            { name: "National Capital Region (NCR)", code: "130000000" },
            { name: "Ilocos Region (Region I)", code: "010000000" },
            { name: "Cagayan Valley (Region II)", code: "020000000" },
            { name: "Central Luzon (Region III)", code: "030000000" },
            { name: "CALABARZON (Region IV-A)", code: "040000000" },
            { name: "MIMAROPA Region", code: "170000000" },
            { name: "Bicol Region (Region V)", code: "050000000" },
            { name: "Western Visayas (Region VI)", code: "060000000" },
            { name: "Central Visayas (Region VII)", code: "070000000" },
            { name: "Eastern Visayas (Region VIII)", code: "080000000" },
            { name: "Zamboanga Peninsula (Region IX)", code: "090000000" },
            { name: "Northern Mindanao (Region X)", code: "100000000" },
            { name: "Davao Region (Region XI)", code: "110000000" },
            { name: "SOCCSKSARGEN (Region XII)", code: "120000000" },
            { name: "Caraga (Region XIII)", code: "160000000" },
            { name: "Bangsamoro Autonomous Region in Muslim Mindanao (BARMM)", code: "190000000" },
            { name: "Cordillera Administrative Region (CAR)", code: "140000000" },
            { name: "Other", code: "other" }
        ];

        // Populate Regions
        function initRegions() {
            if (!selectRegion) return;
            selectRegion.innerHTML = '<option value="all">Select Region</option>';
            regionsList.forEach(r => {
                const opt = document.createElement('option');
                opt.value = r.name;
                opt.dataset.code = r.code;
                opt.textContent = r.name;
                selectRegion.appendChild(opt);
            });
        }

        // Handle Region Cascade
        if (selectRegion) {
            selectRegion.addEventListener('change', function () {
                const selectedRegion = this.value;
                const selectedOpt = this.options[this.selectedIndex];
                const regionCode = selectedOpt ? selectedOpt.dataset.code : '';
                if (!selectLocation) return;
                
                selectLocation.innerHTML = '<option value="all">Select Location</option>';
                
                if (selectedRegion === 'all') {
                    selectLocation.disabled = true;
                    filterJobs();
                } else {
                    selectLocation.disabled = false;
                    
                    // Harvest any locations matching this region from DOM cards
                    const domLocations = [];
                    cards.forEach(card => {
                        const cardReg = card.dataset.region || 'Other';
                        const cardLoc = (card.dataset.location || '').trim();
                        if (cardReg === selectedRegion && cardLoc) {
                            if (!domLocations.includes(cardLoc)) {
                                domLocations.push(cardLoc);
                            }
                        }
                    });

                    // Start with DOM locations formatted nicely
                    const finalLocationsMap = {};
                    domLocations.forEach(loc => {
                        const displayText = loc.toLowerCase().replace(/\b\w/g, c => c.toUpperCase());
                        finalLocationsMap[loc.toUpperCase()] = displayText;
                    });

                    const renderOptions = () => {
                        selectLocation.innerHTML = '<option value="all">Select Location</option>';
                        Object.keys(finalLocationsMap).sort().forEach(val => {
                            const opt = document.createElement('option');
                            opt.value = val;
                            opt.textContent = finalLocationsMap[val];
                            selectLocation.appendChild(opt);
                        });
                    };

                    renderOptions();

                    // Fetch cities/municipalities from PSGC API if valid
                    if (regionCode && regionCode !== 'other') {
                        fetch(`https://psgc.gitlab.io/api/regions/${regionCode}/cities-municipalities/`)
                            .then(res => res.json())
                            .then(data => {
                                data.forEach(item => {
                                    const val = item.name.toUpperCase();
                                    if (!finalLocationsMap[val]) {
                                        finalLocationsMap[val] = item.name;
                                    }
                                });
                                renderOptions();
                            })
                            .catch(err => console.error('Failed to fetch cities:', err));
                    }
                    filterJobs();
                }
            });
        }

        if (selectLocation) {
            selectLocation.addEventListener('change', filterJobs);
        }

        let currentPage = 1;
        const jobsPerPage = 10;

        function filterJobs(resetPage = true) {
            if (resetPage) {
                currentPage = 1;
            }

            const query = searchInput ? searchInput.value.toLowerCase().trim() : '';
            const region = selectRegion ? selectRegion.value : 'all';
            const location = selectLocation ? selectLocation.value : 'all';

            const matchingCards = [];

            cards.forEach(function (card) {
                const cardTitle = card.querySelector('.job-card-title').textContent.toLowerCase();
                const cardDept = (card.dataset.department || '').toLowerCase();
                const cardLoc = (card.dataset.location || '').toUpperCase();
                const cardReg = card.dataset.region || '';

                const matchesQuery = !query || cardTitle.includes(query) || cardDept.includes(query) || cardLoc.toLowerCase().includes(query);
                const matchesRegion = region === 'all' || cardReg === region;
                
                let matchesLocation = false;
                if (location === 'all') {
                    matchesLocation = true;
                } else {
                    const cleanCardLoc = cardLoc.replace(/[^A-Z0-9\s]/g, '').replace(/\b(CITY OF|MUNICIPALITY OF|PROVINCE OF)\b/g, '').trim();
                    const cleanFilterLoc = location.toUpperCase().replace(/[^A-Z0-9\s]/g, '').replace(/\b(CITY OF|MUNICIPALITY OF|PROVINCE OF)\b/g, '').trim();
                    matchesLocation = cleanCardLoc.includes(cleanFilterLoc) || cleanFilterLoc.includes(cleanCardLoc);
                }

                if (matchesQuery && matchesRegion && matchesLocation) {
                    matchingCards.push(card);
                } else {
                    card.style.display = 'none';
                }
            });

            const totalMatching = matchingCards.length;
            const totalPages = Math.ceil(totalMatching / jobsPerPage) || 1;

            if (currentPage > totalPages) currentPage = totalPages;
            if (currentPage < 1) currentPage = 1;

            const startIndex = (currentPage - 1) * jobsPerPage;
            const endIndex = startIndex + jobsPerPage;

            matchingCards.forEach((card, index) => {
                if (index >= startIndex && index < endIndex) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });

            const resultsCountEl = document.getElementById('results-count');
            if (resultsCountEl) {
                if (totalMatching === 0) {
                    resultsCountEl.innerHTML = 'Showing <strong>0</strong> matching opportunities';
                } else {
                    const fromNum = startIndex + 1;
                    const toNum = Math.min(startIndex + jobsPerPage, totalMatching);
                    resultsCountEl.innerHTML = `Showing <strong>${fromNum}–${toNum}</strong> of <strong>${totalMatching}</strong> matching opportunities`;
                }
            }
            if (noRes && grid) {
                noRes.style.display = totalMatching === 0 ? 'block' : 'none';
                grid.style.display = totalMatching === 0 ? 'none' : 'grid';
            }

            renderPagination(totalPages);
        }

        function renderPagination(totalPages) {
            const paginationContainer = document.getElementById('oj-pagination');
            if (!paginationContainer) return;

            if (totalPages <= 1) {
                paginationContainer.innerHTML = '';
                return;
            }

            let html = '';
            
            // Previous Button
            html += `<button type="button" class="oj-pagination-btn ${currentPage === 1 ? 'disabled' : ''}" onclick="window.ojChangePage(${currentPage - 1})">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
            </button>`;

            // Page numbers
            for (let i = 1; i <= totalPages; i++) {
                html += `<button type="button" class="oj-pagination-btn ${i === currentPage ? 'active' : ''}" onclick="window.ojChangePage(${i})">${i}</button>`;
            }

            // Next Button
            html += `<button type="button" class="oj-pagination-btn ${currentPage === totalPages ? 'disabled' : ''}" onclick="window.ojChangePage(${currentPage + 1})">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
            </button>`;

            paginationContainer.innerHTML = html;
        }

        window.ojChangePage = function(page) {
            currentPage = page;
            filterJobs(false);
            const gridHeader = document.querySelector('.jobs-meta-header');
            if (gridHeader) {
                gridHeader.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        };

        if (searchInput) {
            searchInput.addEventListener('input', () => filterJobs(true));
        }

        if (searchBtn) {
            searchBtn.addEventListener('click', () => filterJobs(true));
        }

        // Popular Tags Click Listener
        const popularTags = document.querySelectorAll('.popular-tag-badge');
        popularTags.forEach(tag => {
            tag.addEventListener('click', function () {
                const val = this.dataset.tag;
                if (!searchInput) return;
                
                if (val === 'other...') {
                    searchInput.value = '';
                    searchInput.focus();
                } else {
                    searchInput.value = val;
                }
                filterJobs();
            });
        });

        // Inline CV Drag-and-drop Flow
        const inlineCvUpload = document.getElementById('inline-cv-upload');
        const inlineDropzone = document.getElementById('inline-cv-dropzone');

        function handleInlineFileSelect(files) {
            const ojCvInput = document.getElementById('oj-cv-input');
            if (files && files.length > 0 && ojCvInput) {
                // Pipe files to the modal cv upload input
                ojCvInput.files = files;
                ojCvInput.dispatchEvent(new Event('change'));
                
                // Open modal
                openPoolingModal();
                
                // Go straight to Step 2 since the CV is already preloaded
                setTimeout(() => {
                    ojGoStep2();
                }, 50);
            }
        }

        if (inlineCvUpload) {
            inlineCvUpload.addEventListener('change', function () {
                handleInlineFileSelect(this.files);
            });
        }

        if (inlineDropzone) {
            inlineDropzone.addEventListener('dragover', e => {
                e.preventDefault();
                inlineDropzone.classList.add('drag-over');
            });

            inlineDropzone.addEventListener('dragleave', () => {
                inlineDropzone.classList.remove('drag-over');
            });

            inlineDropzone.addEventListener('drop', e => {
                e.preventDefault();
                inlineDropzone.classList.remove('drag-over');
                handleInlineFileSelect(e.dataTransfer.files);
            });
        }

        // Setup Custom Selects
        function setupCustomSelect(selectId, containerId) {
            const selectEl = document.getElementById(selectId);
            const containerEl = document.getElementById(containerId);
            if (!selectEl || !containerEl) return;

            const trigger = containerEl.querySelector('.custom-select-trigger');
            const menu = containerEl.querySelector('.custom-options-menu');
            const textSpan = trigger.querySelector('.selected-text');

            trigger.addEventListener('click', function (e) {
                e.stopPropagation();
                if (containerEl.classList.contains('disabled')) return;
                
                document.querySelectorAll('.custom-select-container').forEach(el => {
                    if (el !== containerEl) el.classList.remove('active');
                });
                containerEl.classList.toggle('active');
            });

            const observer = new MutationObserver(() => {
                if (selectEl.disabled) {
                    containerEl.classList.add('disabled');
                    containerEl.classList.remove('active');
                } else {
                    containerEl.classList.remove('disabled');
                }
            });
            observer.observe(selectEl, { attributes: true, attributeFilter: ['disabled'] });

            const populateCustomMenu = () => {
                menu.innerHTML = '';
                const options = Array.from(selectEl.options);
                
                options.forEach(opt => {
                    const item = document.createElement('div');
                    item.className = 'custom-option-item';
                    if (opt.value === selectEl.value) {
                        item.classList.add('selected');
                        textSpan.textContent = opt.textContent;
                    }
                    item.textContent = opt.textContent;
                    item.dataset.value = opt.value;

                    item.addEventListener('click', function () {
                        selectEl.value = opt.value;
                        selectEl.dispatchEvent(new Event('change'));
                        
                        menu.querySelectorAll('.custom-option-item').forEach(child => child.classList.remove('selected'));
                        item.classList.add('selected');
                        textSpan.textContent = opt.textContent;
                        containerEl.classList.remove('active');
                    });

                    menu.appendChild(item);
                });
            };

            selectEl.addEventListener('change', () => {
                const matchedOpt = Array.from(selectEl.options).find(o => o.value === selectEl.value);
                if (matchedOpt) {
                    textSpan.textContent = matchedOpt.textContent;
                }
                Array.from(menu.children).forEach(child => {
                    child.classList.toggle('selected', child.dataset.value === selectEl.value);
                });
            });

            const optObserver = new MutationObserver(populateCustomMenu);
            optObserver.observe(selectEl, { childList: true });

            populateCustomMenu();
        }

        document.addEventListener('click', () => {
            document.querySelectorAll('.custom-select-container').forEach(el => el.classList.remove('active'));
        });

        // Initialize on DOM load
        document.addEventListener('DOMContentLoaded', () => {
            initRegions();
            setupCustomSelect('filter-region', 'custom-select-region');
            setupCustomSelect('filter-location', 'custom-select-location');
            filterJobs(true);
        });
    })();
</script>

<!-- Pooling Modal Wrapper -->
<div id="pooling-modal" class="career-modal-overlay" style="display: none; position: fixed; inset: 0; background: rgba(10, 37, 64, 0.6); backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px); z-index: 9999; align-items: center; justify-content: center;">
    <div class="career-modal" style="background: var(--bg-white); padding: 3rem 2.5rem; max-width: 600px; width: 90%; position: relative; box-shadow: var(--shadow-xl); max-height: 90vh; overflow-y: auto; border-radius: 12px;">
        <button class="career-modal-close" id="close-pooling-btn" style="position: absolute; top: 1rem; right: 1.25rem; background: none; border: none; font-size: 1.5rem; color: var(--text-muted); cursor: pointer; transition: color 0.2s; line-height: 1;">&times;</button>
        
        <div style="text-align:center;margin-bottom:2rem;">
            <h3 style="color:var(--text-dark);font-size:1.5rem;margin-bottom:0.5rem;margin-top:0;">Fast-Track Application</h3>
            <p style="color:var(--text-muted);font-size:0.95rem;margin:0;">Upload your CV and we'll match you to the right role.</p>
        </div>

        <!-- Progress Bar -->
        <div id="modal-career-progress" style="display:flex;align-items:center;justify-content:center;gap:0;margin-bottom:2rem;">
            <div class="modal-cprog-step active" data-step="1">
                <div class="modal-cprog-circle">1</div>
                <span>Upload CV</span>
            </div>
            <div class="modal-cprog-line active"></div>
            <div class="modal-cprog-step" data-step="2">
                <div class="modal-cprog-circle">2</div>
                <span>Your Info</span>
            </div>
        </div>

        <!-- Step 1: Upload -->
        <div id="modal-step-1" class="modal-career-step active">
            <div id="modal-cv-dropzone" style="border:2px dashed var(--border-color);padding:3rem 2rem;text-align:center;cursor:pointer;transition:var(--transition);background:var(--bg-light);" onclick="document.getElementById('modal-cv-upload').click()">
                <div style="width:72px;height:72px;margin:0 auto 1.25rem;background:rgba(0,208,156,0.1);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--sec-accent-green)" stroke-width="2">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                        <polyline points="17 8 12 3 7 8" />
                        <line x1="12" y1="3" x2="12" y2="15" />
                    </svg>
                </div>
                <h4 style="color:var(--text-dark);font-size:1.15rem;margin-bottom:0.5rem;margin-top:0;">Drag & drop your CV here</h4>
                <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">or <span style="color:var(--sec-accent-green);font-weight:600;text-decoration:underline;">browse files</span></p>
                <p style="color:var(--text-light);font-size:0.8rem;margin-top:0.75rem;margin-bottom:0;">PDF, DOCX — Max 5 MB</p>
                <input type="file" id="modal-cv-upload" style="display:none;" accept=".pdf,.doc,.docx">
            </div>
            <div id="modal-file-info" style="display:none;margin-top:1rem;padding:1rem 1.25rem;background:rgba(0,208,156,0.06);border:1px solid rgba(0,208,156,0.2);align-items:center;gap:0.75rem;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--sec-accent-green)" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                    <polyline points="14 2 14 8 20 8" />
                </svg>
                <span id="modal-file-name" style="flex:1;font-weight:500;color:var(--text-dark);font-size:0.95rem;"></span>
                <button type="button" onclick="removeModalFile()" style="background:none;border:none;cursor:pointer;color:var(--accent-red);font-size:0.85rem;font-weight:600;">Remove</button>
            </div>
            <button type="button" id="modal-btn-step1" class="btn btn-primary" style="width:100%;margin-top:1.5rem;padding:1rem;font-size:1.05rem;opacity:0.5;pointer-events:none;" onclick="goToModalStep(2)">Continue</button>
        </div>

        <!-- Step 2: Info -->
        <div id="modal-step-2" class="modal-career-step" style="display:none;">
            <div style="display:flex;flex-direction:column;gap:1.25rem;">
                <!-- Purpose -->
                <div>
                    <label style="font-weight:600;color:var(--text-dark);display:block;margin-bottom:0.5rem;font-size:0.95rem;">Application Purpose</label>
                    <div style="display:flex;gap:1.5rem;align-items:center;">
                        <label style="display:flex;align-items:center;gap:0.4rem;font-size:0.95rem;cursor:pointer;">
                            <input type="radio" name="modal_app_purpose" value="pooling" checked style="accent-color:var(--main-blue);"> For Pooling (Future Openings)
                        </label>
                        <label style="display:flex;align-items:center;gap:0.4rem;font-size:0.95rem;cursor:pointer;">
                            <input type="radio" name="modal_app_purpose" value="looking_for_job" style="accent-color:var(--main-blue);"> Looking for a Job (Active Candidate)
                        </label>
                    </div>
                </div>

                <!-- Name Row -->
                <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(150px, 1fr));gap:1rem;">
                    <input type="text" id="modal-app-fname" placeholder="First Name" required style="padding:0.95rem 1.1rem;border:2px solid var(--border-color);font-family:var(--font-body);font-size:0.95rem;width:100%;transition:var(--transition);outline:none;">
                    <input type="text" id="modal-app-mname" placeholder="Middle Name (Optional)" style="padding:0.95rem 1.1rem;border:2px solid var(--border-color);font-family:var(--font-body);font-size:0.95rem;width:100%;transition:var(--transition);outline:none;">
                    <input type="text" id="modal-app-lname" placeholder="Last Name" required style="padding:0.95rem 1.1rem;border:2px solid var(--border-color);font-family:var(--font-body);font-size:0.95rem;width:100%;transition:var(--transition);outline:none;">
                </div>

                <!-- Demographics Row -->
                <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(180px, 1fr));gap:1rem;">
                    <div style="display:flex;flex-direction:column;gap:0.3rem;">
                        <label for="modal-app-gender" style="font-weight:600;font-size:0.85rem;color:var(--text-muted);">Gender</label>
                        <select id="modal-app-gender" required style="padding:0.95rem 1.1rem;border:2px solid var(--border-color);font-family:var(--font-body);font-size:0.95rem;width:100%;transition:var(--transition);outline:none;background:white;">
                            <option value="" disabled selected>Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Non-Binary">Non-Binary</option>
                            <option value="Prefer not to say">Prefer not to say</option>
                        </select>
                    </div>
                    <div style="display:flex;flex-direction:column;gap:0.3rem;">
                        <label for="modal-app-birthday" style="font-weight:600;font-size:0.85rem;color:var(--text-muted);">Birthdate</label>
                        <input type="date" id="modal-app-birthday" required style="padding:0.95rem 1.1rem;border:2px solid var(--border-color);font-family:var(--font-body);font-size:0.95rem;width:100%;transition:var(--transition);outline:none;">
                    </div>
                </div>

                <!-- Address Cascades -->
                <div style="display:flex;flex-direction:column;gap:0.8rem;">
                    <label style="font-weight:600;color:var(--text-dark);font-size:0.95rem;margin-bottom:0.1rem;">Address Details</label>
                    <input type="text" id="modal-app-street" placeholder="Street Address" required style="padding:0.95rem 1.1rem;border:2px solid var(--border-color);font-family:var(--font-body);font-size:0.95rem;width:100%;transition:var(--transition);outline:none;">
                    
                    <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(150px, 1fr));gap:1rem;">
                        <select id="modal-app-region" required style="padding:0.95rem 1.1rem;border:2px solid var(--border-color);font-family:var(--font-body);font-size:0.95rem;width:100%;transition:var(--transition);outline:none;background:white;">
                            <option value="">Select Region</option>
                        </select>
                        <select id="modal-app-city" required style="padding:0.95rem 1.1rem;border:2px solid var(--border-color);font-family:var(--font-body);font-size:0.95rem;width:100%;transition:var(--transition);outline:none;background:white;">
                            <option value="">Select City / Municipality</option>
                        </select>
                        <select id="modal-app-barangay" required style="padding:0.95rem 1.1rem;border:2px solid var(--border-color);font-family:var(--font-body);font-size:0.95rem;width:100%;transition:var(--transition);outline:none;background:white;">
                            <option value="">Select Barangay</option>
                        </select>
                    </div>

                    <!-- Hidden codes inputs -->
                    <input type="hidden" id="modal_app_region_code" value="">
                    <input type="hidden" id="modal_app_city_code" value="">
                    <input type="hidden" id="modal_app_barangay_code" value="">
                </div>

                <!-- Contact Row -->
                <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(180px, 1fr));gap:1rem;">
                    <input type="email" id="modal-app-email" placeholder="Email Address (Optional)" style="padding:0.95rem 1.1rem;border:2px solid var(--border-color);font-family:var(--font-body);font-size:0.95rem;width:100%;transition:var(--transition);outline:none;">
                    <input type="tel" id="modal-app-phone" placeholder="Phone Number (+63)" required style="padding:0.95rem 1.1rem;border:2px solid var(--border-color);font-family:var(--font-body);font-size:0.95rem;width:100%;transition:var(--transition);outline:none;">
                </div>

                <!-- Preferred Roles Checkboxes -->
                <div style="margin-top:0.5rem;" id="modal-preferred-roles-container">
                    <label style="font-weight:600;color:var(--text-dark);display:block;margin-bottom:0.75rem;font-size:0.95rem;">Select Your Preferred Role(s) <span style="color:red;">*</span></label>
                    <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(160px, 1fr));gap:0.5rem;max-height: 150px;overflow-y: auto;padding: 0.5rem;border: 1px solid var(--border-color);border-radius: 8px;">
                        <?php
                        $modal_jobs_query = new WP_Query(array(
                            'post_type'      => 'jobs',
                            'post_status'    => 'publish',
                            'posts_per_page' => -1,
                            'tax_query'      => array(
                                array(
                                    'taxonomy' => 'job_type_tax',
                                    'field'    => 'slug',
                                    'terms'    => 'offshoring',
                                    'operator' => 'NOT IN',
                                ),
                            ),
                            'orderby'        => 'title',
                            'order'          => 'ASC'
                        ));
                        if ($modal_jobs_query->have_posts()) {
                            $unique_modal_titles = array();
                            while ($modal_jobs_query->have_posts()) {
                                $modal_jobs_query->the_post();
                                $job_title = trim(get_the_title());
                                $is_closed = get_post_meta(get_the_ID(), 'job_closed', true);
                                if ($is_closed) {
                                    continue;
                                }
                                if (in_array(strtolower($job_title), array_map('strtolower', $unique_modal_titles))) {
                                    continue;
                                }
                                $unique_modal_titles[] = $job_title;
                            }
                            wp_reset_postdata();

                            if (!empty($unique_modal_titles)) {
                                foreach ($unique_modal_titles as $job_title) {
                                    echo '<label style="display:flex;align-items:center;gap:0.4rem;font-size:0.85rem;cursor:pointer;">';
                                    echo '<input type="checkbox" name="modal_app_preferred_roles[]" value="' . esc_attr($job_title) . '" style="accent-color:var(--main-blue);cursor:pointer;">';
                                    echo '<span>' . esc_html($job_title) . '</span>';
                                    echo '</label>';
                                }
                            } else {
                                echo '<p style="color:var(--text-muted);font-size:0.8rem;">No active positions available.</p>';
                            }
                        } else {
                            echo '<p style="color:var(--text-muted);font-size:0.8rem;">No active positions available.</p>';
                        }
                        ?>
                    </div>
                </div>
            </div>

            <!-- honeypot -->
            <div style="display:none;" aria-hidden="true">
                <input type="text" id="kg_hp_modal" name="kg_hp_field" value="" tabindex="-1" autocomplete="off">
            </div>

            <!-- CAPTCHA -->
            <div class="cf-turnstile" data-sitekey="<?php echo esc_attr(defined('CF_TURNSTILE_SITE_KEY') ? CF_TURNSTILE_SITE_KEY : ''); ?>" data-appearance="interaction-only" style="margin-top:1rem;margin-bottom:0.75rem;"></div>

            <div id="modal-careers-error" style="display:none;background:#fef2f2;border:1px solid #fca5a5;padding:0.75rem 1rem;margin-top:1rem;margin-bottom:0.75rem;border-radius:6px;">
                <p style="margin:0;color:#991b1b;font-size:0.9rem;" id="modal-careers-error-msg"></p>
            </div>
            
            <div style="display:flex;gap:1rem;margin-top:1.5rem;">
                <button type="button" class="btn btn-outline" style="flex:1;padding:1rem;" onclick="goToModalStep(1)">Back</button>
                <button type="button" class="btn btn-primary" style="flex:2;padding:1rem;background:var(--sec-accent-green);color:var(--main-blue);font-size:1.05rem;" onclick="submitModalApplication()">Submit Application</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Success Overlay -->
<div id="modal-success-modal" class="career-modal-overlay" style="display: none; position: fixed; inset: 0; background: rgba(10, 37, 64, 0.6); backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px); z-index: 10000; align-items: center; justify-content: center;">
    <div class="career-modal" style="background: var(--bg-white); padding: 3rem 2.5rem; max-width: 480px; width: 90%; position: relative; box-shadow: var(--shadow-xl); border-radius: 12px; text-align:center;">
        <button class="career-modal-close" onclick="closeModalSuccess()">&times;</button>
        <div style="width:80px;height:80px;margin:0 auto 1.5rem;background:rgba(0,208,156,0.12);border-radius:50%;display:flex;align-items:center;justify-content:center;">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--sec-accent-green)" stroke-width="2.5">
                <polyline points="20 6 9 17 4 12" />
            </svg>
        </div>
        <h3 style="font-size:1.6rem;margin-bottom:0.75rem;color:var(--text-dark);">Application Received!</h3>
        <p style="color:var(--text-muted);font-size:1.05rem;max-width:440px;margin:0 auto 1.25rem;">
            Thank you for submitting your application. Our team will review your CV shortly.
        </p>
        <button class="btn btn-primary" style="padding:0.8rem 2.5rem;background:var(--sec-accent-green);color:var(--main-blue);width:100%;max-width:300px;margin:1.5rem auto 0;border:none;cursor:pointer;border-radius:100px;font-weight:700;" onclick="closeModalSuccess()">Close</button>
    </div>
</div>

<script>
    (function() {
        const poolingModal = document.getElementById('pooling-modal');
        const openPoolingBtn = document.getElementById('open-pooling-btn');
        const closePoolingBtn = document.getElementById('close-pooling-btn');
        const modalSuccessModal = document.getElementById('modal-success-modal');

        if (openPoolingBtn) {
            openPoolingBtn.addEventListener('click', () => {
                poolingModal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
                goToModalStep(1);
            });
        }

        if (closePoolingBtn) {
            closePoolingBtn.addEventListener('click', closePoolingModal);
        }

        poolingModal.addEventListener('click', (e) => {
            if (e.target === poolingModal) closePoolingModal();
        });

        function closePoolingModal() {
            poolingModal.style.display = 'none';
            document.body.style.overflow = '';
            removeModalFile();
            document.querySelectorAll('#modal-step-2 input').forEach(el => { el.value = ''; });
            document.querySelectorAll('#modal-step-2 select').forEach(el => { el.value = ''; });
        }

        window.closeModalSuccess = function() {
            modalSuccessModal.style.display = 'none';
            document.body.style.overflow = '';
        }

        // Modal Wizard Navigation
        let modalCurrentStep = 1;
        window.goToModalStep = function(step) {
            document.querySelectorAll('.modal-career-step').forEach(s => s.style.display = 'none');
            document.getElementById('modal-step-' + step).style.display = 'block';
            
            const steps = document.querySelectorAll('.modal-cprog-step');
            const lines = document.querySelectorAll('.modal-cprog-line');
            steps.forEach((s, i) => {
                s.classList.remove('active', 'done');
                if (i + 1 < step) s.classList.add('done');
                if (i + 1 === step) s.classList.add('active');
            });
            lines.forEach((l, i) => {
                l.classList.remove('active', 'done');
                if (i < step - 1) l.classList.add('done');
                if (i === step - 1) l.classList.add('active');
            });
            modalCurrentStep = step;
        }

        // File upload for Modal
        const modalCvInput = document.getElementById('modal-cv-upload');
        const modalFileInfo = document.getElementById('modal-file-info');
        const modalFileName = document.getElementById('modal-file-name');
        const modalBtnStep1 = document.getElementById('modal-btn-step1');
        const modalDropzone = document.getElementById('modal-cv-dropzone');

        modalCvInput.addEventListener('change', function () {
            if (this.files.length > 0) {
                modalFileInfo.style.display = 'flex';
                modalDropzone.style.display = 'none';
                modalBtnStep1.style.opacity = '1';
                modalBtnStep1.style.pointerEvents = 'auto';
            }
        });

        window.removeModalFile = function() {
            modalCvInput.value = '';
            modalFileInfo.style.display = 'none';
            modalDropzone.style.display = 'block';
            modalBtnStep1.style.opacity = '0.5';
            modalBtnStep1.style.pointerEvents = 'none';
        }

        // Drag & drop for Modal
        modalDropzone.addEventListener('dragover', e => { e.preventDefault(); modalDropzone.classList.add('drag-over'); });
        modalDropzone.addEventListener('dragleave', () => modalDropzone.classList.remove('drag-over'));
        modalDropzone.addEventListener('drop', e => {
            e.preventDefault();
            modalDropzone.classList.remove('drag-over');
            if (e.dataTransfer.files.length > 0) {
                modalCvInput.files = e.dataTransfer.files;
                modalCvInput.dispatchEvent(new Event('change'));
            }
        });

        // Initialize address cascades and CV sanitizer for modal on page load
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof initPSGCAddressCascades === 'function') {
                initPSGCAddressCascades('modal-app-region', 'modal-app-city', 'modal-app-barangay', 'modal_app_region_code', 'modal_app_city_code', 'modal_app_barangay_code');
            }
            if (typeof sanitizeCVFilename === 'function') {
                sanitizeCVFilename(modalCvInput, modalFileName);
            }
        });

        // Submit for Modal
        window.submitModalApplication = function() {
            const fname    = document.getElementById('modal-app-fname').value.trim();
            const mname    = document.getElementById('modal-app-mname').value.trim();
            const lname    = document.getElementById('modal-app-lname').value.trim();
            const email    = document.getElementById('modal-app-email').value.trim();
            const phone    = document.getElementById('modal-app-phone').value.trim();
            const gender   = document.getElementById('modal-app-gender').value;
            const birthday = document.getElementById('modal-app-birthday').value;
            const street   = document.getElementById('modal-app-street').value.trim();
            const cvFile   = modalCvInput.files[0];

            const purposeElement = document.querySelector('input[name="modal_app_purpose"]:checked');
            const purpose  = purposeElement ? purposeElement.value : 'pooling';

            const regionEl   = document.getElementById('modal-app-region');
            const cityEl     = document.getElementById('modal-app-city');
            const barangayEl = document.getElementById('modal-app-barangay');
            const region     = regionEl ? regionEl.value.trim() : '';
            const city       = cityEl ? cityEl.value.trim() : '';
            const barangay   = barangayEl ? barangayEl.value.trim() : '';

            const regionCode = document.getElementById('modal_app_region_code') ? document.getElementById('modal_app_region_code').value : '';
            const cityCode   = document.getElementById('modal_app_city_code') ? document.getElementById('modal_app_city_code').value : '';
            const brgyCode   = document.getElementById('modal_app_barangay_code') ? document.getElementById('modal_app_barangay_code').value : '';

            const errBox = document.getElementById('modal-careers-error');
            const errMsg = document.getElementById('modal-careers-error-msg');
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

            // Extract preferred roles from checkboxes
            const preferredRoles = [];
            document.querySelectorAll('input[name="modal_app_preferred_roles[]"]:checked').forEach(cb => {
                preferredRoles.push(cb.value);
            });

            if (purpose !== 'pooling' && preferredRoles.length === 0) {
                errMsg.textContent = 'Please select at least one preferred position.';
                errBox.style.display = 'block';
                return;
            }

            if (!cvFile) {
                errMsg.textContent = 'Please upload your CV before submitting.';
                errBox.style.display = 'block';
                return;
            }

            const submitBtn = document.querySelector('#modal-step-2 .btn-primary');
            submitBtn.disabled    = true;
            submitBtn.textContent = 'Submitting…';

            const turnstileResponse = document.querySelector('#modal-step-2 [name="cf-turnstile-response"]')?.value || '';

            const formData = new FormData();
            formData.append('action',       'kg_submit_application');
            formData.append('kg_nonce',     KG_AJAX.careers_nonce);
            formData.append('app_fname',    fname);
            formData.append('app_mname',    mname);
            formData.append('app_lname',    lname);
            formData.append('app_email',    email);
            formData.append('app_phone',    phone);
            formData.append('app_purpose',  purpose);
            formData.append('app_gender',   gender);
            formData.append('app_birthday', birthday);
            formData.append('app_street',   street);
            formData.append('app_region',   region);
            formData.append('app_city',     city);
            formData.append('app_barangay', barangay);
            formData.append('app_region_code', regionCode);
            formData.append('app_city_code',   cityCode);
            formData.append('app_barangay_code', brgyCode);
            formData.append('app_preferred_roles', JSON.stringify(preferredRoles));
            formData.append('app_role',     preferredRoles.length > 0 ? preferredRoles[0] : '');
            formData.append('app_cv',       cvFile, cvFile.name);
            formData.append('kg_hp_field',  document.getElementById('kg_hp_modal').value);
            formData.append('cf-turnstile-response', turnstileResponse);

            fetch(KG_AJAX.url, { method: 'POST', body: formData })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        closePoolingModal();
                        modalSuccessModal.style.display = 'flex';
                        document.body.style.overflow = 'hidden';
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
                    submitBtn.disabled    = false;
                    submitBtn.textContent = 'Submit Application';
                });
        }
    })();
</script>

<script>
    /* =============================================
       OJ Pooling Modal JS
       ============================================= */

    // Open modal
    function openPoolingModal() {
        // Reset preferred roles checkboxes
        document.querySelectorAll('input[name="oj_preferred_roles[]"]').forEach(cb => {
            cb.checked = false;
        });
        const hiddenRole = document.getElementById('oj-role');
        if (hiddenRole) {
            hiddenRole.value = 'General Pooling';
        }

        // Select 'pooling' radio card
        const radioPooling = document.querySelector('input[name="oj_purpose"][value="pooling"]');
        if (radioPooling) {
            radioPooling.checked = true;
            radioPooling.dispatchEvent(new Event('change'));
        }

        const overlay = document.getElementById('oj-pooling-overlay');
        if (overlay) {
            overlay.classList.add('visible');
        }
        document.body.style.overflow = 'hidden';
        ojGoStep1(true);
    }

    // Open modal for specific job application
    function openApplyModal(roleName) {
        // Set corresponding checkbox to checked, others to unchecked
        document.querySelectorAll('input[name="oj_preferred_roles[]"]').forEach(cb => {
            cb.checked = (cb.value === roleName);
        });
        const hiddenRole = document.getElementById('oj-role');
        if (hiddenRole) {
            hiddenRole.value = roleName;
        }

        // Select 'looking_for_job' radio card
        const radioJob = document.querySelector('input[name="oj_purpose"][value="looking_for_job"]');
        if (radioJob) {
            radioJob.checked = true;
            radioJob.dispatchEvent(new Event('change'));
        }

        const overlay = document.getElementById('oj-pooling-overlay');
        if (overlay) {
            overlay.classList.add('visible');
        }
        document.body.style.overflow = 'hidden';
        ojGoStep1(true);
    }

    // Navigate to Step 2 (Your Info)
    function ojGoStep2() {
        const cvFile = document.getElementById('oj-cv-input')?.files[0];
        if (!cvFile) return;
        document.getElementById('oj-step-1').style.display = 'none';
        document.getElementById('oj-step-2').style.display = 'block';
        document.getElementById('oj-step-current').textContent = '2';
        document.getElementById('oj-progress-fill').style.width = '100%';

        // Update stepper UI
        const stepper1 = document.getElementById('oj-stepper-1');
        const stepper2 = document.getElementById('oj-stepper-2');
        const stepperLine = document.getElementById('oj-stepper-line');
        if (stepper1) {
            stepper1.classList.remove('active');
            stepper1.classList.add('done');
        }
        if (stepper2) {
            stepper2.classList.add('active');
        }
        if (stepperLine) {
            stepperLine.classList.add('active');
        }

        // Initialize PSGC cascades on step 2
        if (typeof initPSGCAddressCascades === 'function') {
            initPSGCAddressCascades('oj-region', 'oj-city', 'oj-barangay', 'oj_region_code', 'oj_city_code', 'oj_barangay_code');
        }
        // Scroll modal box to top
        const box = document.querySelector('#oj-pooling-overlay .oj-modal-box');
        if (box) box.scrollTop = 0;
    }

    // Navigate back to Step 1
    function ojGoStep1(resetFile) {
        document.getElementById('oj-step-2').style.display = 'none';
        document.getElementById('oj-step-1').style.display = 'block';
        document.getElementById('oj-step-current').textContent = '1';
        document.getElementById('oj-progress-fill').style.width = '50%';
        document.getElementById('oj-error-box').style.display = 'none';

        // Update stepper UI
        const stepper1 = document.getElementById('oj-stepper-1');
        const stepper2 = document.getElementById('oj-stepper-2');
        const stepperLine = document.getElementById('oj-stepper-line');
        if (stepper1) {
            stepper1.classList.add('active');
            stepper1.classList.remove('done');
        }
        if (stepper2) {
            stepper2.classList.remove('active');
        }
        if (stepperLine) {
            stepperLine.classList.remove('active');
        }

        if (resetFile) ojRemoveFile();
        const box = document.querySelector('#oj-pooling-overlay .oj-modal-box');
        if (box) box.scrollTop = 0;
    }

    // Purpose card active toggle (cross-browser :has() fallback)
    document.querySelectorAll('.oj-purpose-card input[type="radio"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.oj-purpose-card').forEach(function(c) { c.classList.remove('oj-purpose-active'); });
            if (this.checked) this.closest('.oj-purpose-card').classList.add('oj-purpose-active');
        });
    });


    // Close main modal
    function closePoolingModal() {
        document.getElementById('oj-pooling-overlay').classList.remove('visible');
        document.body.style.overflow = '';
    }

    // Close success overlay
    function closeOjSuccess() {
        document.getElementById('oj-success-overlay').classList.remove('visible');
        document.body.style.overflow = '';
    }

    // CV file removal
    function ojRemoveFile() {
        document.getElementById('oj-cv-input').value = '';
        document.getElementById('oj-file-info').style.display = 'none';
        document.getElementById('oj-cv-dropzone').style.display = 'flex';
        const continueBtn = document.getElementById('oj-continue-btn');
        if (continueBtn) continueBtn.disabled = true;
    }

    // Wire up CV file input
    (function() {
        const ojCvInput = document.getElementById('oj-cv-input');
        const ojFileInfo = document.getElementById('oj-file-info');
        const ojFileName = document.getElementById('oj-file-name');
        const ojDropzone = document.getElementById('oj-cv-dropzone');

        if (ojCvInput) {
            ojCvInput.addEventListener('change', function () {
                if (this.files.length > 0) {
                    ojFileName.textContent = this.files[0].name;
                    ojFileInfo.style.display = 'flex';
                    ojDropzone.style.display = 'none';
                    const continueBtn = document.getElementById('oj-continue-btn');
                    if (continueBtn) continueBtn.disabled = false;
                }
            });
        }

        if (ojDropzone) {
            ojDropzone.addEventListener('dragover', function(e) {
                e.preventDefault();
                ojDropzone.classList.add('drag-over');
            });
            ojDropzone.addEventListener('dragleave', function() {
                ojDropzone.classList.remove('drag-over');
            });
            ojDropzone.addEventListener('drop', function(e) {
                e.preventDefault();
                ojDropzone.classList.remove('drag-over');
                if (e.dataTransfer.files.length > 0) {
                    ojCvInput.files = e.dataTransfer.files;
                    ojCvInput.dispatchEvent(new Event('change'));
                }
            });
        }
    })();

    // Submit application
    function ojSubmitApplication() {
        const fname    = (document.getElementById('oj-fname')?.value || '').trim();
        const mname    = (document.getElementById('oj-mname')?.value || '').trim();
        const lname    = (document.getElementById('oj-lname')?.value || '').trim();
        const email    = (document.getElementById('oj-email')?.value || '').trim();
        const phone    = (document.getElementById('oj-phone')?.value || '').trim();
        const gender   = document.getElementById('oj-gender')?.value || '';
        const birthday = document.getElementById('oj-birthday')?.value || '';
        const street   = (document.getElementById('oj-street')?.value || '').trim();
        const message  = (document.getElementById('oj-message')?.value || '').trim();
        const cvFile   = document.getElementById('oj-cv-input')?.files[0];
        const privacy  = document.getElementById('oj-privacy')?.checked;

        const purposeEl = document.querySelector('input[name="oj_purpose"]:checked');
        const purpose   = purposeEl ? purposeEl.value : 'pooling';

        const region   = document.getElementById('oj-region')?.value || '';
        const city     = document.getElementById('oj-city')?.value || '';
        const barangay = document.getElementById('oj-barangay')?.value || '';
        const regionCode = document.getElementById('oj_region_code')?.value || '';
        const cityCode   = document.getElementById('oj_city_code')?.value || '';
        const brgyCode   = document.getElementById('oj_barangay_code')?.value || '';

        const preferredRoles = [];
        document.querySelectorAll('input[name="oj_preferred_roles[]"]:checked').forEach(cb => {
            preferredRoles.push(cb.value);
        });

        const errBox = document.getElementById('oj-error-box');
        const errMsg = document.getElementById('oj-error-msg');
        errBox.style.display = 'none';

        function showErr(msg) {
            errMsg.textContent = msg;
            errBox.style.display = 'block';
            errBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }

        if (!fname || !lname)   return showErr('Please fill in your first and last name.');
        if (!email && !phone)   return showErr('Please provide either an email address or a phone number.');
        if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) return showErr('Please provide a valid email address.');
        if (!gender)            return showErr('Please select your gender.');
        if (!birthday)          return showErr('Please select your birthdate.');
        if (!street || !region || !city || !barangay) return showErr('Please fill in your complete address details.');
        if (purpose !== 'pooling' && preferredRoles.length === 0) return showErr('Please select at least one preferred position.');
        if (!cvFile)            return showErr('Please upload your CV before submitting.');
        if (!privacy)           return showErr('Please agree to the Privacy Policy and Terms of Service.');

        const submitBtn = document.getElementById('oj-submit-btn');
        submitBtn.disabled    = true;
        submitBtn.textContent = 'Submitting…';

        const turnstileResponse = document.querySelector('#oj-pooling-overlay [name="cf-turnstile-response"]')?.value || '';

        const formData = new FormData();
        formData.append('action',        'kg_submit_application');
        formData.append('kg_nonce',      typeof KG_AJAX !== 'undefined' ? KG_AJAX.careers_nonce : '');
        formData.append('app_fname',     fname);
        formData.append('app_mname',     mname);
        formData.append('app_lname',     lname);
        formData.append('app_email',     email);
        formData.append('app_phone',     phone);
        formData.append('app_purpose',   purpose);
        formData.append('app_gender',    gender);
        formData.append('app_birthday',  birthday);
        formData.append('app_street',    street);
        formData.append('app_region',    region);
        formData.append('app_city',      city);
        formData.append('app_barangay',  barangay);
        formData.append('app_region_code',   regionCode);
        formData.append('app_city_code',     cityCode);
        formData.append('app_barangay_code', brgyCode);
        formData.append('app_preferred_roles', JSON.stringify(preferredRoles));
        formData.append('app_role',      preferredRoles.length > 0 ? preferredRoles[0] : 'General Pooling');
        formData.append('app_message',   message);
        formData.append('app_cv',        cvFile, cvFile.name);
        formData.append('kg_hp_field',   document.getElementById('oj_hp')?.value || '');
        formData.append('cf-turnstile-response', turnstileResponse);

        const ajaxUrl = typeof KG_AJAX !== 'undefined' ? KG_AJAX.url : '/wp-admin/admin-ajax.php';

        fetch(ajaxUrl, { method: 'POST', body: formData })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    closePoolingModal();
                    const successOverlay = document.getElementById('oj-success-overlay');
                    successOverlay.classList.add('visible');
                    document.body.style.overflow = 'hidden';
                } else {
                    showErr((data.data && data.data.message) ? data.data.message : 'Submission failed. Please try again.');
                    if (typeof turnstile !== 'undefined') turnstile.reset();
                }
            })
            .catch(() => {
                showErr('Network error. Please try again.');
                if (typeof turnstile !== 'undefined') turnstile.reset();
            })
            .finally(() => {
                submitBtn.disabled    = false;
                submitBtn.textContent = 'UPLOAD RESUME/CV';
            });
    }

    // Keyboard: Escape to close modals
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closePoolingModal();
            closeOjSuccess();
        }
    });
</script>


<?php get_footer(); ?>