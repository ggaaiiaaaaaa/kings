<?php
/**
 * Dedicated KPI Dashboard Page
 * Registers a new menu item under WordPress Admin and displays KPIs grouped by month.
 */

if (!defined('ABSPATH')) {
    exit;
}

// Handle KPI CSV Export before admin headers are sent
function kg_handle_kpi_csv_export() {
    if ( ! isset( $_GET['kg_export_kpi_csv'] ) ) {
        return;
    }
    if ( ! current_user_can('manage_options') && !kg_is_current_user_recruiter() ) {
        wp_die(__('You do not have sufficient permissions to perform this action.', 'kingsgroup'));
    }

    $current_time = current_time('timestamp');
    $selected_year_month = isset($_GET['kpi_month']) ? sanitize_text_field($_GET['kpi_month']) : date('Y-m', $current_time);
    list($year, $month) = explode('-', $selected_year_month);
    $year = (int)$year;
    $month = (int)$month;

    // Set headers for download
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="kings-recruiter-kpi-' . $selected_year_month . '.csv"');

    $output = fopen('php://output', 'w');
    
    // Column headings
    fputcsv($output, array('Recruiter Name', 'Location', 'Applications Received', 'Hired / Deployed', 'Conversion Rate (%)', 'Avg. Time-to-Deploy (Days)'));

    // Query all recruiters
    $recruiters = get_users(array('role' => 'recruiter'));

    foreach ($recruiters as $rec) {
        $rec_id = $rec->ID;
        $loc_code = get_user_meta($rec_id, 'kg_recruiter_location', true);
        $loc_label = $loc_code && isset(kg_get_locations()[$loc_code]) ? kg_get_locations()[$loc_code] : 'Unassigned';

        // 1. Applications Received
        $apps_args = [
            'post_type' => 'kg_application',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'date_query' => [[
                'year' => $year,
                'month' => $month,
            ]],
        ];
        $meta_sub_query = [
            'relation' => 'OR',
            ['key' => 'kg_app_recruiter_id', 'value' => $rec_id]
        ];
        if (!empty($loc_code)) {
            $meta_sub_query[] = ['key' => 'kg_app_location', 'value' => $loc_code];
        } else {
            $meta_sub_query[] = ['key' => 'kg_app_location', 'value' => '', 'compare' => 'NOT EXISTS'];
            $meta_sub_query[] = ['key' => 'kg_app_location', 'value' => '', 'compare' => '='];
        }
        $apps_args['meta_query'] = [$meta_sub_query];
        $apps_query = new WP_Query($apps_args);
        $total_apps = $apps_query->found_posts;

        // 2. Hired / Deployed
        $hired_args = [
            'post_type' => 'kg_application',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'date_query' => [[
                'year' => $year,
                'month' => $month,
            ]],
            'meta_query' => [
                'relation' => 'AND',
                ['key' => 'kg_app_status', 'value' => ['hired', 'deployed'], 'compare' => 'IN']
            ]
        ];
        $hired_meta_sub = [
            'relation' => 'OR',
            ['key' => 'kg_app_recruiter_id', 'value' => $rec_id]
        ];
        if (!empty($loc_code)) {
            $hired_meta_sub[] = ['key' => 'kg_app_location', 'value' => $loc_code];
        } else {
            $hired_meta_sub[] = ['key' => 'kg_app_location', 'value' => '', 'compare' => 'NOT EXISTS'];
            $hired_meta_sub[] = ['key' => 'kg_app_location', 'value' => '', 'compare' => '='];
        }
        $hired_args['meta_query'][] = $hired_meta_sub;
        $hired_query = new WP_Query($hired_args);
        $total_hired = $hired_query->found_posts;

        // 3. Conversion Rate
        $conv_rate = $total_apps > 0 ? round(($total_hired / $total_apps) * 100, 1) : 0;

        // 4. Avg. Time-to-Deploy
        $deployed_args = [
            'post_type' => 'kg_application',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'date_query' => [[
                'year' => $year,
                'month' => $month,
            ]],
            'meta_query' => [
                'relation' => 'AND',
                ['key' => 'kg_app_status', 'value' => 'deployed'],
                ['key' => 'kg_app_deploy_date', 'compare' => 'EXISTS']
            ]
        ];
        $dep_meta_sub = [
            'relation' => 'OR',
            ['key' => 'kg_app_recruiter_id', 'value' => $rec_id]
        ];
        if (!empty($loc_code)) {
            $dep_meta_sub[] = ['key' => 'kg_app_location', 'value' => $loc_code];
        } else {
            $dep_meta_sub[] = ['key' => 'kg_app_location', 'value' => '', 'compare' => 'NOT EXISTS'];
            $dep_meta_sub[] = ['key' => 'kg_app_location', 'value' => '', 'compare' => '='];
        }
        $deployed_args['meta_query'][] = $dep_meta_sub;
        $dep_posts = get_posts($deployed_args);

        $total_days = 0;
        $velocity_count = 0;
        foreach ($dep_posts as $app) {
            $deploy_date_raw = get_post_meta($app->ID, 'kg_app_deploy_date', true);
            if ($deploy_date_raw) {
                $created_time = strtotime($app->post_date);
                $deploy_time = strtotime($deploy_date_raw);
                if ($deploy_time >= $created_time) {
                    $diff_days = round(($deploy_time - $created_time) / (60 * 60 * 24));
                    $total_days += $diff_days;
                    $velocity_count++;
                }
            }
        }
        $avg_vel = $velocity_count > 0 ? round($total_days / $velocity_count, 1) : 'N/A';

        // Add line to CSV
        fputcsv($output, array($rec->display_name, $loc_label, $total_apps, $total_hired, $conv_rate, $avg_vel));
    }

    fclose($output);
    exit;
}
add_action('admin_init', 'kg_handle_kpi_csv_export');

add_action('admin_menu', 'kg_register_kpi_dashboard_page');

function kg_register_kpi_dashboard_page()
{
    add_menu_page(
        'KPI Dashboard',
        'KPI Dashboard',
        'edit_posts',
        'kg-kpi-dashboard',
        'kg_render_kpi_dashboard_page',
        'dashicons-chart-bar',
        30
    );
}

function kg_render_kpi_dashboard_page()
{
    if (!current_user_can('manage_options') && !kg_is_current_user_recruiter()) {
        wp_die(__('You do not have sufficient permissions to access this page.', 'kingsgroup'));
    }

    // Get selected month/year or default to current
    $current_time = current_time('timestamp');
    $selected_year_month = isset($_GET['kpi_month']) ? sanitize_text_field($_GET['kpi_month']) : date('Y-m', $current_time);

    list($selected_year, $selected_month) = explode('-', $selected_year_month);
    $selected_year = (int) $selected_year;
    $selected_month = (int) $selected_month;

    // Generate month options for the dropdown (last 12 months)
    $month_options = [];
    for ($i = 0; $i < 12; $i++) {
        $timestamp = strtotime("-$i months", $current_time);
        $value = date('Y-m', $timestamp);
        $label = date('F Y', $timestamp);
        $month_options[$value] = $label;
    }

    // Determine if current user is a recruiter and retrieve their location
    $is_recruiter = kg_is_current_user_recruiter();
    $is_admin = current_user_can('manage_options');
    $selected_recruiter_id = $is_admin && isset($_GET['kpi_recruiter']) ? (int) $_GET['kpi_recruiter'] : 0;

    $filter_recruiter_id = $is_recruiter ? get_current_user_id() : $selected_recruiter_id;
    $is_filtered = ($is_recruiter || $filter_recruiter_id > 0);

    // Retrieve the target recruiter's location
    $recruiter_location = '';
    if ($is_filtered) {
        $recruiter_location = get_user_meta($filter_recruiter_id, 'kg_recruiter_location', true);
    }

    /* ── 1. RECRUITMENT METRICS ── */
    // Total Applications in Selected Month
    $apps_args = [
        'post_type' => 'kg_application',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'date_query' => [
            [
                'year' => $selected_year,
                'month' => $selected_month,
            ],
        ],
    ];
    if ($is_filtered) {
        $meta_sub_query = [
            'relation' => 'OR',
            [
                'key' => 'kg_app_recruiter_id',
                'value' => $filter_recruiter_id,
            ]
        ];
        if (!empty($recruiter_location)) {
            $meta_sub_query[] = [
                'key' => 'kg_app_location',
                'value' => $recruiter_location,
            ];
        } else {
            // Recruiter has no assigned location: fallback to unassigned
            $meta_sub_query[] = [
                'key' => 'kg_app_location',
                'value' => '',
                'compare' => 'NOT EXISTS'
            ];
            $meta_sub_query[] = [
                'key' => 'kg_app_location',
                'value' => '',
                'compare' => '='
            ];
        }
        $apps_args['meta_query'] = [$meta_sub_query];
    }
    $apps_query = new WP_Query($apps_args);
    $total_apps_in_month = $apps_query->found_posts;

    // Hired/Deployed Applications in Selected Month
    $hired_args = [
        'post_type' => 'kg_application',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'date_query' => [
            [
                'year' => $selected_year,
                'month' => $selected_month,
            ],
        ],
        'meta_query' => [
            'relation' => 'AND',
            [
                'key' => 'kg_app_status',
                'value' => ['hired', 'deployed'],
                'compare' => 'IN'
            ]
        ]
    ];
    if ($is_filtered) {
        $meta_sub_query = [
            'relation' => 'OR',
            [
                'key' => 'kg_app_recruiter_id',
                'value' => $filter_recruiter_id,
            ]
        ];
        if (!empty($recruiter_location)) {
            $meta_sub_query[] = [
                'key' => 'kg_app_location',
                'value' => $recruiter_location,
            ];
        } else {
            $meta_sub_query[] = [
                'key' => 'kg_app_location',
                'value' => '',
                'compare' => 'NOT EXISTS'
            ];
            $meta_sub_query[] = [
                'key' => 'kg_app_location',
                'value' => '',
                'compare' => '='
            ];
        }
        $hired_args['meta_query'][] = $meta_sub_query;
    }
    $hired_apps_query = new WP_Query($hired_args);
    $hired_apps_in_month = $hired_apps_query->found_posts;

    // Conversion Rate
    $conversion_rate = $total_apps_in_month > 0 ? round(($hired_apps_in_month / $total_apps_in_month) * 100, 1) : 0;

    // Average Hiring Velocity (in days)
    $deployed_args = [
        'post_type' => 'kg_application',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'date_query' => [
            [
                'year' => $selected_year,
                'month' => $selected_month,
            ],
        ],
        'meta_query' => [
            'relation' => 'AND',
            [
                'key' => 'kg_app_status',
                'value' => 'deployed'
            ],
            [
                'key' => 'kg_app_deploy_date',
                'compare' => 'EXISTS'
            ]
        ]
    ];
    if ($is_filtered) {
        $meta_sub_query = [
            'relation' => 'OR',
            [
                'key' => 'kg_app_recruiter_id',
                'value' => $filter_recruiter_id,
            ]
        ];
        if (!empty($recruiter_location)) {
            $meta_sub_query[] = [
                'key' => 'kg_app_location',
                'value' => $recruiter_location,
            ];
        } else {
            $meta_sub_query[] = [
                'key' => 'kg_app_location',
                'value' => '',
                'compare' => 'NOT EXISTS'
            ];
            $meta_sub_query[] = [
                'key' => 'kg_app_location',
                'value' => '',
                'compare' => '='
            ];
        }
        $deployed_args['meta_query'][] = $meta_sub_query;
    }
    $deployed_apps = get_posts($deployed_args);

    $total_days = 0;
    $velocity_count = 0;
    foreach ($deployed_apps as $app) {
        $deploy_date_raw = get_post_meta($app->ID, 'kg_app_deploy_date', true);
        if ($deploy_date_raw) {
            $created_time = strtotime($app->post_date);
            $deploy_time = strtotime($deploy_date_raw);
            if ($deploy_time >= $created_time) {
                $diff_days = round(($deploy_time - $created_time) / (60 * 60 * 24));
                $total_days += $diff_days;
                $velocity_count++;
            }
        }
    }
    $avg_velocity = $velocity_count > 0 ? round($total_days / $velocity_count, 1) : '—';

    /* ── 2. STAFFING & UTILIZATION ── */
    $jobs_args = [
        'post_type' => 'jobs',
        'post_status' => 'publish',
        'posts_per_page' => -1,
    ];
    if ($is_filtered) {
        $jobs_args['meta_query'] = [
            'relation' => 'OR',
            [
                'key' => 'job_location',
                'value' => $recruiter_location,
            ]
        ];
        if (empty($recruiter_location)) {
            $jobs_args['meta_query'][] = [
                'key' => 'job_location',
                'value' => '',
                'compare' => 'NOT EXISTS',
            ];
            $jobs_args['meta_query'][] = [
                'key' => 'job_location',
                'value' => '',
                'compare' => '=',
            ];
        }
    }
    $jobs = get_posts($jobs_args);

    $total_target_headcount = 0;
    $total_filled_headcount = 0;
    foreach ($jobs as $job) {
        $total_target_headcount += (int) get_post_meta($job->ID, 'job_target_headcount', true);
        $total_filled_headcount += (int) get_post_meta($job->ID, 'job_filled_headcount', true);
    }
    $headcount_fill_rate = $total_target_headcount > 0 ? round(($total_filled_headcount / $total_target_headcount) * 100, 1) : 0;

    // Current general workforce status
    $pooling_count = 0;
    $deployed_count = 0;
    $status_args = [
        'post_type' => 'kg_application',
        'post_status' => 'publish',
        'posts_per_page' => -1,
    ];
    if ($is_filtered) {
        $meta_sub_query = [
            'relation' => 'OR',
            [
                'key' => 'kg_app_recruiter_id',
                'value' => $filter_recruiter_id,
            ]
        ];
        if (!empty($recruiter_location)) {
            $meta_sub_query[] = [
                'key' => 'kg_app_location',
                'value' => $recruiter_location,
            ];
        } else {
            $meta_sub_query[] = [
                'key' => 'kg_app_location',
                'value' => '',
                'compare' => 'NOT EXISTS'
            ];
            $meta_sub_query[] = [
                'key' => 'kg_app_location',
                'value' => '',
                'compare' => '='
            ];
        }
        $status_args['meta_query'] = [$meta_sub_query];
    }
    $status_query = new WP_Query($status_args);
    if ($status_query->have_posts()) {
        while ($status_query->have_posts()) {
            $status_query->the_post();
            $status = get_post_meta(get_the_ID(), 'kg_app_status', true);
            if ($status === 'pooling') {
                $pooling_count++;
            } elseif ($status === 'deployed') {
                $deployed_count++;
            }
        }
        wp_reset_postdata();
    }
    $utilization_rate = ($deployed_count + $pooling_count) > 0 ? round(($deployed_count / ($deployed_count + $pooling_count)) * 100, 1) : 0;

    /* ── 3. QUOTE LEADS & REVENUE ── */
    if ($is_filtered) {
        $total_quote_count = 0;
        $total_quote_value = 0;
    } else {
        $quotes_query = new WP_Query([
            'post_type' => 'kg_quote_lead',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'date_query' => [
                [
                    'year' => $selected_year,
                    'month' => $selected_month,
                ],
            ],
        ]);

        $total_quote_count = $quotes_query->found_posts;
        $total_quote_value = 0;

        if ($quotes_query->have_posts()) {
            while ($quotes_query->have_posts()) {
                $quotes_query->the_post();
                $val = (float) get_post_meta(get_the_ID(), 'kg_quote_total', true);
                $total_quote_value += $val;
            }
            wp_reset_postdata();
        }
    }


    ?>
    <div class="wrap kg-kpi-wrap">
        <header class="kg-kpi-header">
            <div class="kg-kpi-header-content">
                <h1>Kings Group KPI Dashboard</h1>
                <p class="description">Live operational performance and recruitment metrics.</p>
            </div>

            <form method="get" class="kg-kpi-filter-form">
                <input type="hidden" name="page" value="kg-kpi-dashboard" />
                
                <?php if ($is_admin): ?>
                    <?php $recruiters = get_users(array('role' => 'recruiter')); ?>
                    <label for="kpi_recruiter">Recruiter:</label>
                    <select name="kpi_recruiter" id="kpi_recruiter" onchange="this.form.submit()">
                        <option value="0">All Recruiters</option>
                        <?php foreach ($recruiters as $rec): 
                            $loc = get_user_meta($rec->ID, 'kg_recruiter_location', true);
                            $loc_label = $loc && isset(kg_get_locations()[$loc]) ? ' (' . kg_get_locations()[$loc] . ')' : '';
                        ?>
                            <option value="<?php echo esc_attr($rec->ID); ?>" <?php selected($selected_recruiter_id, $rec->ID); ?>>
                                <?php echo esc_html($rec->display_name . $loc_label); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                <?php endif; ?>

                <label for="kpi_month">Reporting Period:</label>
                <select name="kpi_month" id="kpi_month" onchange="this.form.submit()">
                    <?php foreach ($month_options as $val => $label): ?>
                        <option value="<?php echo esc_attr($val); ?>" <?php selected($selected_year_month, $val); ?>>
                            <?php echo esc_html($label); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <a href="<?php echo esc_url( add_query_arg( array( 'kg_export_kpi_csv' => '1', 'kpi_month' => $selected_year_month ), admin_url('admin.php?page=kg-kpi-dashboard') ) ); ?>" class="button button-secondary" style="background:#ffd166 !important;color:#0a2540 !important;border:none !important;font-weight:bold !important;padding:4px 12px !important;height:auto !important;line-height:20px !important;border-radius:6px !important;text-decoration:none !important;">Export CSV</a>
            </form>
        </header>

        <!-- Load Chart.js CDN -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <style>
            .kg-kpi-wrap {
                margin: 20px 20px 0 0;
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
            }

            .kg-kpi-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                flex-wrap: wrap;
                background: #0a2540;
                color: #fff;
                padding: 24px 30px;
                border-radius: 12px;
                margin-bottom: 24px;
                box-shadow: 0 4px 20px rgba(10, 37, 64, 0.15);
            }

            .kg-kpi-header h1 {
                color: #fff;
                font-size: 26px;
                font-weight: 800;
                margin: 0 0 4px 0;
                padding: 0;
            }

            .kg-kpi-header .description {
                color: #94a3b8;
                margin: 0;
                font-size: 14px;
            }

            .kg-kpi-filter-form {
                display: flex;
                align-items: center;
                gap: 12px;
            }

            .kg-kpi-filter-form label {
                font-weight: 600;
                font-size: 14px;
                color: #e2e8f0;
            }

            .kg-kpi-filter-form select {
                background: rgba(255, 255, 255, 0.15) !important;
                border: 1px solid rgba(255, 255, 255, 0.3) !important;
                border-radius: 6px !important;
                color: #fff !important;
                padding: 6px 12px !important;
                font-size: 14px !important;
                cursor: pointer !important;
                outline: none !important;
                transition: all 0.3s !important;
                height: auto !important;
                line-height: 1.5 !important;
            }

            .kg-kpi-filter-form select option {
                background: #0a2540 !important;
                color: #fff !important;
            }

            .kg-kpi-filter-form select:hover,
            .kg-kpi-filter-form select:focus,
            .kg-kpi-filter-form select:active {
                background: #10355a !important;
                color: #fff !important;
                border-color: #ffd166 !important;
                box-shadow: 0 0 0 1px #ffd166 !important;
            }

            /* Visual Chart Cards Row */
            .kg-kpi-charts-row {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
                gap: 20px;
                margin-bottom: 24px;
            }

            .kg-kpi-chart-card {
                background: #fff;
                border: 1px solid #e2e8f0;
                border-radius: 12px;
                padding: 24px;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            }

            .kg-kpi-chart-container {
                position: relative;
                height: 260px;
                width: 100%;
            }

            /* Metrics Grid */
            .kg-kpi-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 20px;
                margin-bottom: 24px;
            }

            .kg-kpi-section-card {
                background: #fff;
                border: 1px solid #e2e8f0;
                border-radius: 12px;
                padding: 24px;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
                transition: transform 0.3s, box-shadow 0.3s;
            }

            .kg-kpi-section-card:hover {
                transform: translateY(-4px);
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            }

            .kg-kpi-section-title {
                font-size: 16px;
                font-weight: 700;
                color: #0a2540;
                border-bottom: 2px solid #f1f5f9;
                padding-bottom: 12px;
                margin-top: 0;
                margin-bottom: 20px;
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .kg-kpi-section-title span.badge {
                background: #f1f5f9;
                color: #64748b;
                font-size: 11px;
                padding: 2px 8px;
                border-radius: 999px;
                font-weight: 600;
            }

            .kg-kpi-metric-row {
                display: flex;
                flex-direction: column;
                gap: 16px;
            }

            .kg-kpi-metric-item {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .kg-kpi-label {
                font-size: 13px;
                color: #64748b;
                font-weight: 500;
            }

            .kg-kpi-value {
                font-size: 22px;
                font-weight: 800;
                color: #0a2540;
            }

            .kg-kpi-value.highlight {
                color: #00d09c;
            }

            /* Progress bars */
            .kg-kpi-progress-container {
                margin-top: 6px;
            }

            .kg-kpi-progress-bar-wrap {
                background: #f1f5f9;
                height: 8px;
                border-radius: 4px;
                overflow: hidden;
                margin-top: 4px;
            }

            .kg-kpi-progress-bar {
                height: 100%;
                background: linear-gradient(90deg, #0a2540, #00d09c);
                border-radius: 4px;
                transition: width 0.6s ease-out;
            }
        </style>

        <div class="kg-kpi-grid">
            <!-- Recruitment & Hiring -->
            <div class="kg-kpi-section-card">
                <h3 class="kg-kpi-section-title">
                    Recruitment & Hiring
                    <span class="badge">Monthly</span>
                </h3>
                <div class="kg-kpi-metric-row">
                    <div>
                        <div class="kg-kpi-metric-item">
                            <span class="kg-kpi-label">Applications Received</span>
                            <span class="kg-kpi-value"><?php echo $total_apps_in_month; ?></span>
                        </div>
                    </div>

                    <div>
                        <div class="kg-kpi-metric-item">
                            <span class="kg-kpi-label">Hired / Deployed</span>
                            <span class="kg-kpi-value"><?php echo $hired_apps_in_month; ?></span>
                        </div>
                    </div>

                    <div>
                        <div class="kg-kpi-metric-item">
                            <span class="kg-kpi-label">Conversion Rate</span>
                            <span class="kg-kpi-value highlight"><?php echo $conversion_rate; ?>%</span>
                        </div>
                        <div class="kg-kpi-progress-container">
                            <div class="kg-kpi-progress-bar-wrap">
                                <div class="kg-kpi-progress-bar" style="width: <?php echo $conversion_rate; ?>%;"></div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="kg-kpi-metric-item">
                            <span class="kg-kpi-label">Average Time-to-Deploy</span>
                            <span class="kg-kpi-value"><?php echo $avg_velocity; ?>
                                <?php echo is_numeric($avg_velocity) ? 'days' : ''; ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Staffing & Capacity -->
            <div class="kg-kpi-section-card">
                <h3 class="kg-kpi-section-title">
                    Workforce & Capacity
                    <span class="badge">Cumulative</span>
                </h3>
                <div class="kg-kpi-metric-row">
                    <div>
                        <div class="kg-kpi-metric-item">
                            <span class="kg-kpi-label">Total Target Headcount</span>
                            <span class="kg-kpi-value"><?php echo $total_target_headcount; ?></span>
                        </div>
                    </div>

                    <div>
                        <div class="kg-kpi-metric-item">
                            <span class="kg-kpi-label">Total Filled Headcount</span>
                            <span class="kg-kpi-value"><?php echo $total_filled_headcount; ?></span>
                        </div>
                    </div>

                    <div>
                        <div class="kg-kpi-metric-item">
                            <span class="kg-kpi-label">Headcount Fill Rate</span>
                            <span class="kg-kpi-value highlight"><?php echo $headcount_fill_rate; ?>%</span>
                        </div>
                        <div class="kg-kpi-progress-container">
                            <div class="kg-kpi-progress-bar-wrap">
                                <div class="kg-kpi-progress-bar"
                                    style="width: <?php echo $headcount_fill_rate; ?>%; background: linear-gradient(90deg, #0a2540, #ffd166);">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="kg-kpi-metric-item">
                            <span class="kg-kpi-label">Active Staff Utilization</span>
                            <span class="kg-kpi-value"><?php echo $utilization_rate; ?>%</span>
                        </div>
                        <div class="kg-kpi-progress-container">
                            <div class="kg-kpi-progress-bar-wrap">
                                <div class="kg-kpi-progress-bar"
                                    style="width: <?php echo $utilization_rate; ?>%; background: linear-gradient(90deg, #ffd166, #00d09c);">
                                </div>
                            </div>
                        </div>
                        <div
                            style="font-size: 11px; color: #94a3b8; margin-top: 4px; display: flex; justify-content: space-between;">
                            <span>Deployed: <?php echo $deployed_count; ?></span>
                            <span>Pooling: <?php echo $pooling_count; ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (!$is_filtered): ?>
            <!-- Quote Leads & Pipeline Value -->
            <div class="kg-kpi-section-card">
                <h3 class="kg-kpi-section-title">
                    Sales & Pipeline
                    <span class="badge">Monthly</span>
                </h3>
                <div class="kg-kpi-metric-row">
                    <div>
                        <div class="kg-kpi-metric-item">
                            <span class="kg-kpi-label">New Quote Leads</span>
                            <span class="kg-kpi-value"><?php echo $total_quote_count; ?></span>
                        </div>
                    </div>

                    <div>
                        <div class="kg-kpi-metric-item">
                            <span class="kg-kpi-label">Est. Recurring Revenue</span>
                            <span class="kg-kpi-value highlight">$<?php echo number_format($total_quote_value); ?>/mo</span>
                        </div>
                    </div>
                </div>
            </div>
             <?php endif; ?>
        </div>

        <!-- KPI Visual Analytics Charts -->
        <div class="kg-kpi-charts-row">
            <!-- Recruitment & Conversion Funnel Chart -->
            <div class="kg-kpi-chart-card">
                <h3 class="kg-kpi-section-title" style="margin-bottom:15px; border-bottom:1px solid #f1f5f9; padding-bottom:8px;">
                    Recruitment Funnel & Conversion
                </h3>
                <div class="kg-kpi-chart-container">
                    <canvas id="kgFunnelChart"></canvas>
                </div>
            </div>

            <!-- Workforce Status Distribution Chart -->
            <div class="kg-kpi-chart-card">
                <h3 class="kg-kpi-section-title" style="margin-bottom:15px; border-bottom:1px solid #f1f5f9; padding-bottom:8px;">
                    Workforce Capacity & Allocation
                </h3>
                <div class="kg-kpi-chart-container">
                    <canvas id="kgWorkforceChart"></canvas>
                </div>
            </div>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Funnel Chart
            const funnelCtx = document.getElementById('kgFunnelChart').getContext('2d');
            new Chart(funnelCtx, {
                type: 'bar',
                data: {
                    labels: ['Applications', 'Hired / Deployed'],
                    datasets: [{
                        label: 'Candidates',
                        data: [<?php echo $total_apps_in_month; ?>, <?php echo $hired_apps_in_month; ?>],
                        backgroundColor: [
                            'rgba(10, 37, 64, 0.85)',
                            'rgba(0, 208, 156, 0.85)'
                        ],
                        borderColor: [
                            '#0a2540',
                            '#00d09c'
                        ],
                        borderWidth: 1.5,
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                footer: function(tooltipItems) {
                                    let rate = <?php echo $conversion_rate; ?>;
                                    return 'Conversion Rate: ' + rate + '%';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#f1f5f9'
                            },
                            ticks: {
                                precision: 0
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // 2. Workforce Doughnut Chart
            const workforceCtx = document.getElementById('kgWorkforceChart').getContext('2d');
            new Chart(workforceCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Deployed', 'Pooling'],
                    datasets: [{
                        data: [<?php echo $deployed_count; ?>, <?php echo $pooling_count; ?>],
                        backgroundColor: [
                            '#00d09c',
                            '#ffd166'
                        ],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12,
                                font: {
                                    size: 11,
                                    weight: 'bold'
                                },
                                padding: 15
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let value = context.raw;
                                    let total = <?php echo ($deployed_count + $pooling_count); ?>;
                                    let percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                    return ' ' + context.label + ': ' + value + ' (' + percentage + '%)';
                                }
                            }
                        }
                    },
                    cutout: '65%'
                }
            });
        });
        </script>
    </div>
    <?php
}
