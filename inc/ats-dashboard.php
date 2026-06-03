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
    wp_add_dashboard_widget(
        'kg_ats_overview',
        'Kings Cooperative Dashboard',
        'kg_ats_dashboard_widget_render'
    );
}

function kg_ats_dashboard_widget_render()
{

    /* ── 1. Headcount Metrics ─────────────────────────── */
    $jobs = get_posts(array(
        'post_type' => 'jobs',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'fields' => 'ids',
    ));

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
            $q = new WP_Query(array(
                'post_type' => 'kg_application',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'fields' => 'ids',
                'meta_query' => array(array('key' => 'kg_app_status', 'value' => $stage)),
            ));
            $pipeline_counts[$stage] = $q->found_posts;
        }
    }

    $deployed = $pipeline_counts['deployed'] ?? 0;
    $benched = $pipeline_counts['benched'] ?? 0;
    $total_apps = array_sum($pipeline_counts);

    /* ── 3. Recent Quote Leads ────────────────────────── */
    $recent_quotes = get_posts(array(
        'post_type' => 'kg_quote_lead',
        'post_status' => 'publish',
        'posts_per_page' => 5,
        'orderby' => 'date',
        'order' => 'DESC',
    ));

    $stage_styles = array(
        'screening' => array('#dbeafe', '#1e40af'),
        'interviewing' => array('#ede9fe', '#6d28d9'),
        'hired' => array('#d1fae5', '#065f46'),
        'deployed' => array('#dcfce7', '#15803d'),
        'benched' => array('#fef3c7', '#92400e'),
        'rejected' => array('#fee2e2', '#991b1b'),
    );
    $stage_labels = function_exists('kg_ats_statuses') ? kg_ats_statuses() : array();

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
            <div class="kg-dc-num"><?php echo $benched; ?></div>
            <div class="kg-dc-label">Workers Benched / Available</div>
        </div>
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
        <a href="<?php echo admin_url('edit.php?post_type=kg_inquiry'); ?>" class="button" style="font-size:12px;">Quote
            Leads</a>
    </div>
    <?php
}
