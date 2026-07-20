<?php
/**
 * ATS Admin Analytics Dashboard
 * Adds a custom WP Dashboard widget with live metrics:
 *   - Active vacancies vs. filled slots
 *   - Applicant pipeline counts by stage
 *   - Deployed vs. Benched workers
 *   - Recent quote lead values
 */
if (!defined('ABSPATH'))
    exit;

add_action('wp_dashboard_setup', 'kg_register_ats_dashboard_widget');

function kg_register_ats_dashboard_widget()
{
    // Remove unnecessary default widgets
    remove_meta_box('dashboard_site_health', 'dashboard', 'normal');
    remove_meta_box('dashboard_right_now', 'dashboard', 'normal'); // At a Glance
    remove_meta_box('dashboard_quick_press', 'dashboard', 'side'); // Quick Draft
    remove_meta_box('dashboard_primary', 'dashboard', 'side'); // WordPress News
    
    wp_add_dashboard_widget(
        'kg_ats_overview',
        'Kings Cooperative Dashboard',
        'kg_ats_dashboard_widget_render'
    );
    wp_add_dashboard_widget(
        'kg_ats_applicant_tracker',
        'Applicant Tracker',
        'kg_ats_applicant_tracker_render'
    );
    wp_add_dashboard_widget(
        'kg_ats_job_listings_overview',
        'Job Listings Overview',
        'kg_ats_job_listings_overview_render'
    );
}

function kg_ats_dashboard_widget_render()
{

    /* ── 1. Headcount Metrics ─────────────────────────── */
    $is_recruiter = kg_is_current_user_recruiter();
    
    // Determine active locations to filter by
    $active_locations = array();
    if ($is_recruiter) {
        $active_locations = (array) get_user_meta(get_current_user_id(), 'kg_recruiter_location', true);
        $active_locations = array_filter($active_locations);
    } else {
        $loc = isset($_GET['kg_ats_loc']) ? sanitize_text_field($_GET['kg_ats_loc']) : '';
        if ($loc) $active_locations[] = $loc;
    }

    $jobs_args = array(
        'post_type' => 'jobs',
        'post_status' => array('publish', 'draft'),
        'posts_per_page' => -1,
        'fields' => 'ids',
    );
    
    if ( ! empty($active_locations) ) {
        $jobs_args['tax_query'] = array(
            array(
                'taxonomy' => 'job_location_tax',
                'field'    => 'slug',
                'terms'    => $active_locations,
            )
        );
    } elseif ($is_recruiter) {
        $jobs_args['author'] = get_current_user_id();
    }
    
    $jobs = get_posts($jobs_args);

    $location_job_titles = [];
    if (!empty($jobs)) {
        foreach ($jobs as $jid) {
            $location_job_titles[] = get_the_title($jid);
        }
    }
    if (empty($location_job_titles)) {
        $location_job_titles = ['__none__'];
    }

    $total_target = 0;
    $total_filled = 0;
    $active_jobs = 0;
    $filled_jobs = 0;

    foreach ($jobs as $jid) {
        $target = (int) get_post_meta($jid, 'job_target_headcount', true);
        $filled = (int) get_post_meta($jid, 'job_filled_headcount', true);
        $total_target += $target;
        $total_filled += $filled;
        if ($target > 0 && $filled >= $target) {
            $filled_jobs++;
        } else {
            $active_jobs++;
        }
    }
    $open_slots = max(0, $total_target - $total_filled);

    /* ── 2. Applicant Pipeline ────────────────────────── */
    $pipeline_counts = array();
    if (function_exists('kg_ats_statuses')) {
        foreach (array_keys(kg_ats_statuses()) as $stage) {
            $q_args = array(
                'post_type' => 'kg_application',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'fields' => 'ids',
                'meta_query' => array(
                    array('key' => 'kg_app_status', 'value' => $stage)
                ),
            );
            if ($is_recruiter || ! empty($active_locations)) {
                $q_args['meta_query'][] = array(
                    'key' => 'kg_app_role',
                    'value' => $location_job_titles,
                    'compare' => 'IN'
                );
            }
            $q = new WP_Query($q_args);
            $pipeline_counts[$stage] = $q->found_posts;
        }
    }

    $deployed = $pipeline_counts['deployed'] ?? 0;
    $pooling  = $pipeline_counts['pooling']  ?? 0;
    $total_apps = array_sum($pipeline_counts);

    /* ── 3. Recent Quote Leads ────────────────────────── */
    $recent_quotes = array();
    if (!$is_recruiter) {
        $recent_quotes = get_posts(array(
            'post_type' => 'kg_quote_lead',
            'post_status' => 'publish',
            'posts_per_page' => 5,
            'orderby' => 'date',
            'order' => 'DESC',
        ));
    }

    $stage_styles = array(
        'screening' => array('#dbeafe', '#1e40af'),
        'interviewing' => array('#ede9fe', '#6d28d9'),
        'hired' => array('#d1fae5', '#065f46'),
        'deployed' => array('#dcfce7', '#15803d'),
        'pooling'      => array('#fef3c7', '#92400e'),
        'rejected' => array('#fee2e2', '#991b1b'),
    );
    $stage_labels = function_exists('kg_ats_statuses') ? kg_ats_statuses() : array();

    /* ── 4. Regional/Location Breakdown of Active Job Openings ────── */
    $breakdown_counts = array();
    $locations_map = function_exists('kg_get_locations') ? kg_get_locations() : array();

    foreach ($jobs as $jid) {
        $target = (int) get_post_meta($jid, 'job_target_headcount', true);
        $filled = (int) get_post_meta($jid, 'job_filled_headcount', true);
        $closed = get_post_meta($jid, 'job_closed', true);
        if ($closed || ($target > 0 && $filled >= $target)) continue;

        $loc_meta = get_post_meta($jid, 'job_location', true);

        if ($is_recruiter) {
            $loc_name = isset($locations_map[$loc_meta]) ? $locations_map[$loc_meta] : ($loc_meta ?: 'Unassigned');
            $breakdown_counts[$loc_name] = ($breakdown_counts[$loc_name] ?? 0) + 1;
        } else {
            // mb_strtoupper properly uppercases ñ → Ñ and other special chars
            $loc = mb_strtoupper(trim($loc_meta), 'UTF-8');

            // ── Priority 1: use the explicit Region field if admin set it ──────────
            $saved_region = trim(get_post_meta($jid, 'job_region', true));
            if (!empty($saved_region)) {
                $region = $saved_region;
            // ── Priority 2: fall back to keyword detection for legacy jobs ─────────
            } elseif (preg_match('/REMOTE|WFH|WORK FROM HOME/u', $loc)) {
                $region = 'Remote / WFH';
            } elseif (preg_match('/NATIONWIDE|PHILIPPINES|ALL REGIONS/u', $loc)) {
                $region = 'Nationwide';
            } elseif (preg_match('/METRO MANILA|MANILA|TAGUIG|MAKATI|PASAY|QC|QUEZON CITY|GALLERIA|ALABANG|MOA|PARAÑAQUE|PARANAQUE|CALOOCAN|LAS PIÑAS|LAS PINAS|MANDALUYONG|MARIKINA|MUNTINLUPA|NAVOTAS|PASIG|SAN JUAN|VALENZUELA|PATEROS|EASTWOOD/u', $loc)) {
                $region = 'NCR';
            } elseif (preg_match('/PANGASINAN|DAGUPAN|LAOAG|ILOCOS|LA UNION|VIGAN|ILOCOS NORTE|ILOCOS SUR/u', $loc)) {
                $region = 'Ilocos Region (I)';
            } elseif (preg_match('/TUGUEGARAO|SOLANO|NUEVA VIZCAYA|CAUAYAN|BATANES|CAGAYAN|QUIRINO|SANTIAGO CITY|CITISTORE SOLANO|SACI CABANATUAN/u', $loc)) {
                $region = 'Cagayan Valley (II)';
            } elseif (preg_match('/BULACAN|MALOLOS|PAMPANGA|TARLAC|BAMBAN|CABANATUAN|OLONGAPO|MARILAO|MABALACAT|SUBIC|AURORA|BATAAN|NUEVA ECIJA|ZAMBALES|ANGELES|SACI OLONGAPO|SSM MARILAO/u', $loc)) {
                $region = 'Central Luzon (III)';
            } elseif (preg_match('/IMUS|LIMA|BATANGAS|LAGUNA|BACOOR|TANZA|VERMOSA|RIZAL|CAVITE|ANTIPOLO|LIPA|BALIWAG|TANAUAN|SACI BACOOR|SACI TANZA|ABENSON BATANGAS|ABENSON SAN PASCUAL|QUEZON PROVINCE|LUCENA/u', $loc)) {
                $region = 'CALABARZON (IV-A)';
            } elseif (preg_match('/MOGPOG|MARINDUQUE|MIMAROPA|MINDORO|PALAWAN|ROMBLON|PUERTO PRINCESA|CALAPAN/u', $loc)) {
                $region = 'MIMAROPA (IV-B)';
            } elseif (preg_match('/BICOL|CAMARINES|IRIGA|TABACO|DARAGA|ALBAY|NAGA|LEGAZPI|CATANDUANES|MASBATE|SORSOGON|LCC TABACO|LCC DARAGA|LCC NAGA|SACI LEGAZPI/u', $loc)) {
                $region = 'Bicol (V)';
            } elseif (preg_match('/BACOLOD|KABANKALAN|ILOILO|AKLAN|ANTIQUE|CAPIZ|GUIMARAS|NEGROS OCCIDENTAL|SACI BACOLOD|RA KABANKALAN|SACI ILOILO/u', $loc)) {
                $region = 'Western Visayas (VI)';
            } elseif (preg_match('/CEBU|BOHOL|NEGROS ORIENTAL|SIQUIJOR|MANDAUE|LAPU-LAPU|DUMAGUETE|TAGBILARAN/u', $loc)) {
                $region = 'Central Visayas (VII)';
            } elseif (preg_match('/BILIRAN|LEYTE|SAMAR|TACLOBAN|ORMOC/u', $loc)) {
                $region = 'Eastern Visayas (VIII)';
            } elseif (preg_match('/ZAMBOANGA|PAGADIAN|DIPOLOG|DAPITAN|SIBUGAY|ZAMBOANGA DEL SUR|ZAMBOANGA DEL NORTE|ZAMBOANGA SIBUGAY/u', $loc)) {
                $region = 'Zamboanga Peninsula (IX)';
            } elseif (preg_match('/BUKIDNON|CAMIGUIN|LANAO DEL NORTE|MISAMIS|CAGAYAN DE ORO|CDO|ILIGAN/u', $loc)) {
                $region = 'Northern Mindanao (X)';
            } elseif (preg_match('/TAGUM|DAVAO/u', $loc)) {
                $region = 'Davao Region (XI)';
            } elseif (preg_match('/MIDSAYAP|COTABATO|SARANGANI|GENERAL SANTOS|GENSAN|KORONADAL|SULTAN KUDARAT|CITISTORE MIDSAYAP|ABENSON COTABATO/u', $loc)) {
                $region = 'SOCCSKSARGEN (XII)';
            } elseif (preg_match('/AGUSAN|DINAGAT|SURIGAO|BUTUAN/u', $loc)) {
                $region = 'Caraga (XIII)';
            } elseif (preg_match('/SULU|TAWI-TAWI|BASILAN|LANAO DEL SUR|MAGUINDANAO|BANGSAMORO|COTABATO CITY/u', $loc)) {
                $region = 'BARMM';
            } elseif (preg_match('/BAGUIO|BENGUET|ABRA|APAYAO|IFUGAO|KALINGA|MOUNTAIN PROVINCE|HARRISON/u', $loc)) {
                $region = 'CAR';
            } else {
                // Fallback: show the raw location so you know exactly what needs to be added
                $region = 'Other: ' . (mb_strlen($loc, 'UTF-8') > 20 ? mb_substr($loc, 0, 20, 'UTF-8') . '…' : $loc);
            }
            $breakdown_counts[$region] = ($breakdown_counts[$region] ?? 0) + 1;
        }
    }
    arsort($breakdown_counts);

    ?>
    <style>
        #kg_ats_overview .hndle span {
            font-size: 14px;
        }

        .kg-dash-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 16px;
        }

        .kg-dash-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 14px 16px;
        }

        .kg-dash-card .kg-dc-num {
            font-size: 2rem;
            font-weight: 800;
            line-height: 1;
            color: #0a2540;
        }

        .kg-dash-card .kg-dc-label {
            font-size: 11px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 4px;
        }

        .kg-dash-card.accent {
            background: linear-gradient(135deg, #0a2540, #1a4a8f);
        }

        .kg-dash-card.accent .kg-dc-num,
        .kg-dash-card.accent .kg-dc-label {
            color: #fff;
        }

        .kg-dash-card.green {
            background: linear-gradient(135deg, #064e3b, #065f46);
        }

        .kg-dash-card.green .kg-dc-num,
        .kg-dash-card.green .kg-dc-label {
            color: #fff;
        }

        .kg-pipeline {
            margin-bottom: 16px;
        }

        .kg-pipe-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 6px;
        }

        .kg-pipe-badge {
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 700;
            min-width: 80px;
            text-align: center;
        }

        .kg-pipe-bar-wrap {
            flex: 1;
            background: #e2e8f0;
            border-radius: 4px;
            height: 8px;
            overflow: hidden;
        }

        .kg-pipe-bar {
            height: 100%;
            border-radius: 4px;
            background: #0a2540;
            transition: width 0.4s;
        }

        .kg-pipe-count {
            font-size: 12px;
            font-weight: 700;
            color: #0a2540;
            min-width: 24px;
            text-align: right;
        }

        .kg-quote-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: 12px;
        }

        .kg-quote-row:last-child {
            border-bottom: none;
        }

        .kg-section-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            margin: 0 0 10px;
        }
    </style>

    <!-- Location Switcher (Admin Only) -->
    <?php if ( ! $is_recruiter ) : 
        $all_branches = function_exists('kg_get_locations') ? kg_get_locations() : array();
        ?>
        <div style="margin-bottom: 16px; display: flex; align-items: center; justify-content: space-between; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px 14px;">
            <span style="font-size: 12px; font-weight: 700; color: #0a2540;">Filter Dashboard by Location:</span>
            <select id="kg_dashboard_loc_filter" onchange="location.href = addOrUpdateUrlParam('kg_ats_loc', this.value);" style="padding: 4px 8px; border-radius: 4px; border: 1px solid #cbd5e1; min-width: 150px;">
                <option value="">— All Locations —</option>
                <?php foreach ( $all_branches as $slug => $name ) : ?>
                    <option value="<?php echo esc_attr($slug); ?>" <?php selected(in_array($slug, $active_locations), true); ?>><?php echo esc_html($name); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <script>
        function addOrUpdateUrlParam(key, value) {
            var uri = window.location.href;
            var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
            var separator = uri.indexOf('?') !== -1 ? "&" : "?";
            if (uri.match(re)) {
                if (value) {
                    return uri.replace(re, '$1' + key + "=" + value + '$2');
                } else {
                    var cleaned = uri.replace(re, '$1' + key + '=' + '$2').replace(/&+/g, '&').replace(/\?&/, '?');
                    // Strip the param completely if empty
                    var url = new URL(uri);
                    url.searchParams.delete(key);
                    return url.toString();
                }
            } else {
                if (value) {
                    return uri + separator + key + "=" + value;
                }
                return uri;
            }
        }
        </script>
    <?php endif; ?>

    <!-- Headcount Overview -->
    <p class="kg-section-title">Headcount</p>
    <div class="kg-dash-grid">
        <div class="kg-dash-card accent">
            <div class="kg-dc-num"><?php echo $active_jobs; ?></div>
            <div class="kg-dc-label">Active Job Openings</div>
        </div>
        <div class="kg-dash-card">
            <div class="kg-dc-num"><?php echo $open_slots; ?></div>
            <div class="kg-dc-label">Open Slots Remaining</div>
        </div>
        <div class="kg-dash-card green">
            <div class="kg-dc-num"><?php echo $deployed; ?></div>
            <div class="kg-dc-label">Workers Deployed</div>
        </div>
        <div class="kg-dash-card">
            <div class="kg-dc-num"><?php echo $pooling; ?></div>
            <div class="kg-dc-label">In Talent Pool</div>
        </div>
    </div>

    <!-- Jobs by Region/Location Breakdown -->
    <?php
    $breakdown_title = $is_recruiter ? 'Active Openings by Location' : 'Active Openings by Region';
    ?>
    <p class="kg-section-title"><?php echo esc_html($breakdown_title); ?> (<?php echo $active_jobs; ?> total)</p>
    <div class="kg-pipeline">
        <?php foreach ($breakdown_counts as $label => $cnt):
            $pct = $active_jobs > 0 ? round(($cnt / $active_jobs) * 100) : 0;
            ?>
            <div class="kg-pipe-row">
                <span class="kg-pipe-badge" style="background:#dbeafe;color:#1e40af;min-width:170px;font-size:10px;"><?php echo esc_html($label); ?></span>
                <div class="kg-pipe-bar-wrap">
                    <div class="kg-pipe-bar" style="width:<?php echo $pct; ?>%;background:#0a2540;"></div>
                </div>
                <span class="kg-pipe-count"><?php echo $cnt; ?></span>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Pipeline -->
    <p class="kg-section-title">Applicant Pipeline (<?php echo $total_apps; ?> total)</p>
    <div class="kg-pipeline">
        <?php foreach ($stage_labels as $stage => $label):
            $cnt = $pipeline_counts[$stage] ?? 0;
            $pct = $total_apps > 0 ? round(($cnt / $total_apps) * 100) : 0;
            $clrs = $stage_styles[$stage] ?? array('#f3f4f6', '#374151');
            ?>
            <div class="kg-pipe-row">
                <span class="kg-pipe-badge"
                    style="background:<?php echo $clrs[0]; ?>;color:<?php echo $clrs[1]; ?>;"><?php echo esc_html($label); ?></span>
                <div class="kg-pipe-bar-wrap">
                    <div class="kg-pipe-bar" style="width:<?php echo $pct; ?>%;background:<?php echo $clrs[1]; ?>;"></div>
                </div>
                <span class="kg-pipe-count"><?php echo $cnt; ?></span>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Recent Quotes -->
    <?php if ($recent_quotes): ?>
        <p class="kg-section-title">Recent Quote Leads</p>
        <?php foreach ($recent_quotes as $q):
            $client_name = get_post_meta($q->ID, 'kg_quote_name', true) ?: '—';
            $total_val   = get_post_meta($q->ID, 'kg_quote_total', true);
            $roles_json  = get_post_meta($q->ID, 'kg_quote_roles', true);
            $roles_arr   = json_decode($roles_json, true) ?: array();
            $roles_count = count($roles_arr);
            ?>
            <div class="kg-quote-row">
                <span style="font-weight:600;color:#0a2540;"><?php echo esc_html($client_name); ?></span>
                <span style="color:#64748b;">
                    <?php echo $roles_count ? esc_html($roles_count) . ' roles' : ''; ?>
                    <?php echo $total_val ? ' · $' . esc_html(number_format($total_val)) . '/mo' : ''; ?>
                    <em style="margin-left:6px;"><?php echo esc_html(get_the_date('M j', $q->ID)); ?></em>
                </span>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <div style="margin-top:14px;display:flex;gap:8px;flex-wrap:wrap;">
        <a href="<?php echo admin_url('edit.php?post_type=kg_application'); ?>" class="button button-primary"
            style="font-size:12px;">View All Applications</a>
        <a href="<?php echo admin_url('edit.php?post_type=jobs'); ?>" class="button" style="font-size:12px;">Manage Job
            Openings</a>
        <?php if (!$is_recruiter): ?>
        <a href="<?php echo admin_url('edit.php?post_type=kg_quote_lead'); ?>" class="button" style="font-size:12px;">Quote
            Leads</a>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Render Applicant Tracker Widget
 */
function kg_ats_applicant_tracker_render()
{
    // Latest 5 CV submissions
    $recent_apps = get_posts(array(
        'post_type'      => 'kg_application',
        'post_status'    => 'publish',
        'posts_per_page' => 5,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ));

    // Count Pooling vs. Active Job Hunting (screening, interviewing, hired, deployed)
    $pooling_count = 0;
    $active_hunting_count = 0;

    $all_apps = get_posts(array(
        'post_type'      => 'kg_application',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'fields'         => 'ids',
    ));

    foreach ($all_apps as $app_id) {
        $status = get_post_meta($app_id, 'kg_app_status', true) ?: 'screening';
        if ($status === 'pooling') {
            $pooling_count++;
        } elseif (in_array($status, array('screening', 'interviewing', 'hired', 'deployed'), true)) {
            $active_hunting_count++;
        }
    }
    ?>
    <style>
        .kg-tracker-summary {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }
        .kg-tracker-stat {
            flex: 1;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px;
            text-align: center;
        }
        .kg-tracker-stat-num {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0a2540;
            line-height: 1.2;
        }
        .kg-tracker-stat-label {
            font-size: 10px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .kg-tracker-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        .kg-tracker-table th {
            text-align: left;
            padding: 8px 6px;
            border-bottom: 2px solid #e2e8f0;
            color: #475569;
            font-weight: 600;
        }
        .kg-tracker-table td {
            padding: 8px 6px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }
        .kg-tracker-table tr:last-child td {
            border-bottom: none;
        }
    </style>

    <div class="kg-tracker-summary">
        <div class="kg-tracker-stat">
            <div class="kg-tracker-stat-num"><?php echo esc_html($pooling_count); ?></div>
            <div class="kg-tracker-stat-label">Pooling</div>
        </div>
        <div class="kg-tracker-stat">
            <div class="kg-tracker-stat-num"><?php echo esc_html($active_hunting_count); ?></div>
            <div class="kg-tracker-stat-label">Active Job Hunting</div>
        </div>
    </div>

    <p class="kg-section-title" style="margin-top: 15px;">Latest CV Submissions</p>
    <?php if (!empty($recent_apps)) : ?>
        <table class="kg-tracker-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Applied Role</th>
                    <th>Date</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recent_apps as $app) :
                    $role = get_post_meta($app->ID, 'kg_app_role', true) ?: 'Not specified';
                    $cv_url = get_post_meta($app->ID, 'kg_app_cv_url', true);
                    $download_url = $cv_url ? add_query_arg('kg_download_cv', $app->ID, home_url('/')) : '';
                    ?>
                    <tr>
                        <td>
                            <a href="<?php echo esc_url(get_edit_post_link($app->ID)); ?>" style="font-weight: 600; color: #0a2540; text-decoration: none;">
                                <?php echo esc_html($app->post_title); ?>
                            </a>
                        </td>
                        <td><?php echo esc_html($role); ?></td>
                        <td><?php echo esc_html(get_the_date('M j, Y', $app->ID)); ?></td>
                        <td style="text-align: right;">
                            <?php if ($download_url) : ?>
                                <a href="<?php echo esc_url($download_url); ?>" target="_blank" class="button button-small" style="font-size: 10px; padding: 0 8px; min-height: 20px; line-height: 18px;">
                                    Download CV
                                </a>
                            <?php else : ?>
                                <span style="color: #94a3b8; font-style: italic;">No CV</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p style="font-size: 12px; color: #64748b; font-style: italic;">No applications submitted yet.</p>
    <?php endif; ?>
    <div style="margin-top: 12px;">
        <a href="<?php echo admin_url('edit.php?post_type=kg_application'); ?>" class="button" style="font-size: 11px;">View All CVs</a>
    </div>
    <?php
}

/**
 * Render Job Listings Overview Widget
 */
function kg_ats_job_listings_overview_render()
{
    $jobs = get_posts(array(
        'post_type'      => 'jobs',
        'post_status'    => array('publish', 'draft'),
        'posts_per_page' => -1,
    ));

    $active_openings = 0;
    $closed_filled_positions = 0;
    $total_slots_filled = 0;
    $total_slots_target = 0;

    foreach ($jobs as $job) {
        $target = (int) get_post_meta($job->ID, 'job_target_headcount', true);
        $filled = (int) get_post_meta($job->ID, 'job_filled_headcount', true);
        $is_closed = get_post_meta($job->ID, 'job_closed', true);

        $total_slots_filled += $filled;
        $total_slots_target += $target;

        if ($is_closed || ($target > 0 && $filled >= $target)) {
            $closed_filled_positions++;
        } else {
            $active_openings++;
        }
    }

    $remaining_slots = max(0, $total_slots_target - $total_slots_filled);
    $fill_pct = $total_slots_target > 0 ? min(100, round(($total_slots_filled / $total_slots_target) * 100)) : 0;
    ?>
    <style>
        .kg-jobs-summary {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }
        .kg-jobs-stat {
            flex: 1;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px;
            text-align: center;
        }
        .kg-jobs-stat-num {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0a2540;
            line-height: 1.2;
        }
        .kg-jobs-stat-label {
            font-size: 10px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .kg-slots-meter-title {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            margin-bottom: 6px;
        }
        .kg-slots-meter-bar-wrap {
            background: #e2e8f0;
            border-radius: 6px;
            height: 16px;
            overflow: hidden;
            position: relative;
            margin-bottom: 10px;
        }
        .kg-slots-meter-bar {
            height: 100%;
            background: linear-gradient(90deg, #0a2540, #00d09c);
            border-radius: 6px;
            transition: width 0.4s;
        }
        .kg-slots-meter-text {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: 700;
            color: #0a2540;
            mix-blend-mode: multiply;
        }
    </style>

    <div class="kg-jobs-summary">
        <div class="kg-jobs-stat">
            <div class="kg-jobs-stat-num"><?php echo esc_html($active_openings); ?></div>
            <div class="kg-jobs-stat-label">Active Openings</div>
        </div>
        <div class="kg-jobs-stat">
            <div class="kg-jobs-stat-num"><?php echo esc_html($closed_filled_positions); ?></div>
            <div class="kg-jobs-stat-label">Closed/Filled</div>
        </div>
    </div>

    <div class="kg-slots-meter-wrap" style="margin-top: 15px;">
        <div class="kg-slots-meter-title">
            <span>Slots Meter</span>
            <span><?php echo esc_html($total_slots_filled); ?> / <?php echo esc_html($total_slots_target); ?> Slots (<?php echo $fill_pct; ?>%)</span>
        </div>
        <div class="kg-slots-meter-bar-wrap">
            <div class="kg-slots-meter-bar" style="width: <?php echo $fill_pct; ?>%;"></div>
            <div class="kg-slots-meter-text">
                <?php echo esc_html($total_slots_filled); ?> Filled · <?php echo esc_html($remaining_slots); ?> Remaining
            </div>
        </div>
    </div>
    <div style="margin-top: 12px;">
        <a href="<?php echo admin_url('edit.php?post_type=jobs'); ?>" class="button" style="font-size: 11px;">Manage Job Openings</a>
    </div>
    <?php
}

