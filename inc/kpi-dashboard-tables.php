<?php
if (!defined('ABSPATH')) exit;

$apps_paged = isset($_GET['apps_paged']) ? max(1, intval($_GET['apps_paged'])) : 1;
$inq_paged = isset($_GET['inq_paged']) ? max(1, intval($_GET['inq_paged'])) : 1;
$quote_paged = isset($_GET['quote_paged']) ? max(1, intval($_GET['quote_paged'])) : 1;
$items_per_page = 20;

$selected_year_month = isset($_GET['kpi_month']) ? sanitize_text_field($_GET['kpi_month']) : date('Y-m');
$export_apps_url = add_query_arg(array('kg_export_apps_csv' => '1', 'kpi_month' => $selected_year_month), admin_url('admin.php?page=kg-kpi-dashboard'));
$export_inq_url = add_query_arg(array('kg_export_inq_csv' => '1', 'kpi_month' => $selected_year_month), admin_url('admin.php?page=kg-kpi-dashboard'));
$export_quote_url = add_query_arg(array('kg_export_quote_csv' => '1', 'kpi_month' => $selected_year_month), admin_url('admin.php?page=kg-kpi-dashboard'));

// --- APPLICATIONS TAB FILTERS ---
if ( ! kg_is_current_user_recruiter() ) :
$app_search = isset($_GET['app_search']) ? sanitize_text_field($_GET['app_search']) : '';
$app_role = isset($_GET['app_role']) ? sanitize_text_field($_GET['app_role']) : '';
$app_status = isset($_GET['app_status']) ? sanitize_text_field($_GET['app_status']) : '';
$app_recruiter = isset($_GET['app_recruiter']) ? intval($_GET['app_recruiter']) : '';

$export_apps_params = array(
    'kg_export_apps_csv' => '1',
    'app_search' => $app_search,
    'app_role' => $app_role,
    'app_status' => $app_status,
    'app_recruiter' => $app_recruiter
);
$export_apps_url = add_query_arg(array_filter($export_apps_params), admin_url('admin.php?page=kg-kpi-dashboard'));

// --- APPLICATIONS TAB ---
$apps_args = array(
    'post_type' => 'kg_application',
    'post_status' => 'publish',
    'posts_per_page' => $items_per_page,
    'paged' => $apps_paged,
    'orderby' => 'date',
    'order' => 'DESC',
    'meta_query' => array('relation' => 'AND')
);

// Apply Filters
if ($app_search) {
    $apps_args['s'] = $app_search;
}
if ($app_role) {
    $apps_args['meta_query'][] = array(
        'key' => 'kg_app_role',
        'value' => $app_role,
        'compare' => 'LIKE'
    );
}
if ($app_status) {
    $apps_args['meta_query'][] = array(
        'key' => 'kg_app_status',
        'value' => $app_status,
        'compare' => '='
    );
}
if ($app_recruiter) {
    $apps_args['meta_query'][] = array(
        'key' => 'kg_app_recruiter_id',
        'value' => $app_recruiter,
        'compare' => '='
    );
}

// Fetch distinct roles for the dropdown
global $wpdb;
$all_roles = $wpdb->get_col("
    SELECT DISTINCT meta_value 
    FROM {$wpdb->postmeta} 
    WHERE meta_key = 'kg_app_role' 
    AND meta_value != ''
    ORDER BY meta_value ASC
");

$apps_query = new WP_Query($apps_args);
?>
<div id="tab-applications" class="kg-kpi-tab-content">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
        <h2 style="margin:0; font-size:20px; font-weight:700;">Applications</h2>
        <a href="<?php echo esc_url($export_apps_url); ?>" class="button button-secondary">Export Filtered to CSV</a>
    </div>

    <!-- Filters -->
    <form method="GET" action="" style="background:#f8fafc; padding:16px; border-radius:8px; border:1px solid #e2e8f0; margin-bottom:20px; display:flex; flex-wrap:wrap; gap:16px; align-items:flex-end;">
        <input type="hidden" name="page" value="kg-kpi-dashboard">
        
        <div>
            <label style="display:block; font-size:12px; font-weight:600; color:#64748b; margin-bottom:4px;">Search Name</label>
            <input type="text" name="app_search" value="<?php echo esc_attr($app_search); ?>" placeholder="E.g. John Doe..." style="padding:6px 12px; border:1px solid #cbd5e1; border-radius:4px; max-width:150px;">
        </div>
        
        <div>
            <label style="display:block; font-size:12px; font-weight:600; color:#64748b; margin-bottom:4px;">Applied Role</label>
            <select name="app_role" style="padding:6px 28px 6px 12px; border:1px solid #cbd5e1; border-radius:4px; min-width:140px; max-width:220px; text-overflow:ellipsis; overflow:hidden; white-space:nowrap;">
                <option value="">All Roles</option>
                <?php foreach($all_roles as $r): ?>
                    <option value="<?php echo esc_attr($r); ?>" <?php selected($app_role, $r); ?>><?php echo esc_html($r); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div>
            <label style="display:block; font-size:12px; font-weight:600; color:#64748b; margin-bottom:4px;">Status</label>
            <select name="app_status" style="padding:6px 28px 6px 12px; border:1px solid #cbd5e1; border-radius:4px; min-width:130px; max-width:180px; text-overflow:ellipsis; overflow:hidden; white-space:nowrap;">
                <option value="">All Statuses</option>
                <?php foreach(kg_ats_statuses() as $k => $v): ?>
                    <option value="<?php echo esc_attr($k); ?>" <?php selected($app_status, $k); ?>><?php echo esc_html($v); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div>
            <label style="display:block; font-size:12px; font-weight:600; color:#64748b; margin-bottom:4px;">Recruiter</label>
            <select name="app_recruiter" style="padding:6px 28px 6px 12px; border:1px solid #cbd5e1; border-radius:4px; min-width:140px; max-width:180px; text-overflow:ellipsis; overflow:hidden; white-space:nowrap;">
                <option value="">All Recruiters</option>
                <?php 
                $recruiters = get_users(array('role__in' => array('recruiter')));
                foreach($recruiters as $rec): 
                ?>
                    <option value="<?php echo esc_attr($rec->ID); ?>" <?php selected($app_recruiter, $rec->ID); ?>><?php echo esc_html($rec->display_name); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div>
            <button type="submit" class="button button-primary">Apply Filters</button>
            <a href="?page=kg-kpi-dashboard#tab-applications" class="button button-secondary">Clear</a>
        </div>
    </form>

    <table class="kg-kpi-table">
        <thead>
            <tr>
                <th>Applicant Name</th>
                <th>Applied Role</th>
                <th>Assigned Recruiter</th>
                <th>Status</th>
                <th>Submitted</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($apps_query->have_posts()): while ($apps_query->have_posts()): $apps_query->the_post(); 
                $app_id = get_the_ID();
                $email = get_post_meta($app_id, 'kg_app_email', true);
                $role = get_post_meta($app_id, 'kg_app_role', true);
                $recruiter_id = get_post_meta($app_id, 'kg_app_recruiter_id', true);
                $recruiter_name = 'Unassigned';
                if ($recruiter_id) {
                    $rec_user = get_userdata($recruiter_id);
                    if ($rec_user) $recruiter_name = $rec_user->display_name;
                }
                $status_code = get_post_meta($app_id, 'kg_app_status', true);
                $statuses = kg_ats_statuses();
                $status_name = isset($statuses[$status_code]) ? $statuses[$status_code] : ucfirst($status_code);
                
                $cv_id = get_post_meta($app_id, 'kg_app_cv', true);
                $cv_url = $cv_id ? wp_get_attachment_url($cv_id) : '';
                $modal_id = 'modal-app-' . $app_id;
            ?>
            <tr>
                <td>
                    <strong><?php the_title(); ?></strong><br>
                    <small style="color:#64748b;"><?php echo esc_html($email); ?></small>
                </td>
                <td><?php echo esc_html($role); ?></td>
                <td><?php echo esc_html($recruiter_name); ?></td>
                <td><span style="background:#f1f5f9; padding:4px 8px; border-radius:4px; font-size:12px; font-weight:bold;"><?php echo esc_html($status_name); ?></span></td>
                <td><?php echo get_the_date('M j, Y'); ?></td>
                <td>
                    <button type="button" class="button" onclick="kgOpenModal('<?php echo $modal_id; ?>')">View Details</button>
                    
                    <!-- Modal -->
                    <div id="<?php echo $modal_id; ?>" class="kg-modal-overlay" onclick="if(event.target===this) kgCloseModal('<?php echo $modal_id; ?>')">
                        <div class="kg-modal">
                            <div class="kg-modal-header">
                                <h3 class="kg-modal-title">Application: <?php the_title(); ?></h3>
                                <button type="button" class="kg-modal-close" onclick="kgCloseModal('<?php echo $modal_id; ?>')">&times;</button>
                            </div>
                            <div class="kg-modal-body">
                                <div class="kg-modal-field"><label>Email</label><div><?php echo esc_html($email); ?></div></div>
                                <div class="kg-modal-field"><label>Phone</label><div><?php echo esc_html(get_post_meta($app_id, 'kg_app_phone', true)); ?></div></div>
                                <div class="kg-modal-field"><label>Role</label><div><?php echo esc_html($role); ?></div></div>
                                <div class="kg-modal-field"><label>Cover Letter / Notes</label><div><?php echo nl2br(esc_html(get_post_meta($app_id, 'kg_app_cover_letter', true))); ?></div></div>
                                <?php if ($cv_url): ?>
                                <div class="kg-modal-field" style="margin-top:24px;">
                                    <a href="<?php echo esc_url($cv_url); ?>" target="_blank" class="button button-primary">Download/View CV</a>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <?php endwhile; else: ?>
            <tr><td colspan="6" style="text-align:center; padding:24px; color:#64748b;">No applications found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    
    <?php
    $total_apps_pages = $apps_query->max_num_pages;
    if ($total_apps_pages > 1):
    ?>
    <div class="kg-kpi-table-pagination">
        <div>Page <?php echo $apps_paged; ?> of <?php echo $total_apps_pages; ?></div>
        <div>
            <?php if ($apps_paged > 1): ?>
                <a href="<?php echo esc_url(add_query_arg('apps_paged', $apps_paged - 1)); ?>#tab-applications" class="button">&laquo; Prev</a>
            <?php endif; ?>
            <?php if ($apps_paged < $total_apps_pages): ?>
                <a href="<?php echo esc_url(add_query_arg('apps_paged', $apps_paged + 1)); ?>#tab-applications" class="button">Next &raquo;</a>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; wp_reset_postdata(); ?>
</div>
<?php endif; ?>

<?php
// --- INQUIRIES TAB ---
if ( ! kg_is_current_user_recruiter() ) :

$inq_args = array(
    'post_type' => 'kg_inquiry',
    'post_status' => 'publish',
    'posts_per_page' => $items_per_page,
    'paged' => $inq_paged,
    'orderby' => 'date',
    'order' => 'DESC'
);
$inq_query = new WP_Query($inq_args);
?>
<div id="tab-inquiries" class="kg-kpi-tab-content">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
        <h2 style="margin:0; font-size:20px; font-weight:700;">Inquiries</h2>
        <a href="<?php echo esc_url($export_inq_url); ?>" class="button button-secondary">Export Inquiries to CSV</a>
    </div>

    <table class="kg-kpi-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Status</th>
                <th>Submitted</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($inq_query->have_posts()): while ($inq_query->have_posts()): $inq_query->the_post(); 
                $inq_id = get_the_ID();
                $email = get_post_meta($inq_id, 'kg_inq_email', true);
                $status_code = get_post_meta($inq_id, 'kg_inq_status', true);
                $status_name = ucfirst(str_replace('_', ' ', $status_code));
                if (!$status_name) $status_name = 'New';
                $modal_id = 'modal-inq-' . $inq_id;
            ?>
            <tr>
                <td><strong><?php the_title(); ?></strong></td>
                <td><?php echo esc_html($email); ?></td>
                <td><?php echo esc_html($status_name); ?></td>
                <td><?php echo get_the_date('M j, Y'); ?></td>
                <td>
                    <button type="button" class="button" onclick="kgOpenModal('<?php echo $modal_id; ?>')">View Details</button>
                    
                    <!-- Modal -->
                    <div id="<?php echo $modal_id; ?>" class="kg-modal-overlay" onclick="if(event.target===this) kgCloseModal('<?php echo $modal_id; ?>')">
                        <div class="kg-modal">
                            <div class="kg-modal-header">
                                <h3 class="kg-modal-title">General Inquiry: <?php the_title(); ?></h3>
                                <button type="button" class="kg-modal-close" onclick="kgCloseModal('<?php echo $modal_id; ?>')">&times;</button>
                            </div>
                            <div class="kg-modal-body">
                                <div class="kg-modal-field"><label>Email</label><div><?php echo esc_html($email); ?></div></div>
                                <div class="kg-modal-field"><label>Phone</label><div><?php echo esc_html(get_post_meta($inq_id, 'kg_inq_phone', true) ?: '—'); ?></div></div>
                                <div class="kg-modal-field"><label>Subject</label><div><?php echo esc_html(get_post_meta($inq_id, 'kg_inq_subject', true)); ?></div></div>
                                <div class="kg-modal-field"><label>Message</label><div><?php echo nl2br(esc_html(get_post_meta($inq_id, 'kg_inq_message', true))); ?></div></div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <?php endwhile; else: ?>
            <tr><td colspan="5" style="text-align:center; padding:24px; color:#64748b;">No inquiries found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    
    <?php
    $total_inq_pages = $inq_query->max_num_pages;
    if ($total_inq_pages > 1):
    ?>
    <div class="kg-kpi-table-pagination">
        <div>Page <?php echo $inq_paged; ?> of <?php echo $total_inq_pages; ?></div>
        <div>
            <?php if ($inq_paged > 1): ?>
                <a href="<?php echo esc_url(add_query_arg('inq_paged', $inq_paged - 1)); ?>#tab-inquiries" class="button">&laquo; Prev</a>
            <?php endif; ?>
            <?php if ($inq_paged < $total_inq_pages): ?>
                <a href="<?php echo esc_url(add_query_arg('inq_paged', $inq_paged + 1)); ?>#tab-inquiries" class="button">Next &raquo;</a>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; wp_reset_postdata(); ?>
</div>
<?php endif; ?>

<?php
// --- QUOTES TAB ---
if ( ! kg_is_current_user_recruiter() ) :

$quote_args = array(
    'post_type' => 'kg_quote_lead',
    'post_status' => 'publish',
    'posts_per_page' => $items_per_page,
    'paged' => $quote_paged,
    'orderby' => 'date',
    'order' => 'DESC'
);
$quote_query = new WP_Query($quote_args);
?>
<div id="tab-quotes" class="kg-kpi-tab-content">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
        <h2 style="margin:0; font-size:20px; font-weight:700;">Quote Requests</h2>
        <a href="<?php echo esc_url($export_quote_url); ?>" class="button button-secondary">Export Quotes to CSV</a>
    </div>

    <table class="kg-kpi-table">
        <thead>
            <tr>
                <th>Client</th>
                <th>Email</th>
                <th>Status</th>
                <th>Submitted</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($quote_query->have_posts()): while ($quote_query->have_posts()): $quote_query->the_post(); 
                $quote_id = get_the_ID();
                $email = get_post_meta($quote_id, 'kg_quote_email', true);
                $status_code = get_post_meta($quote_id, 'kg_quote_status', true);
                $status_name = ucfirst(str_replace('_', ' ', $status_code));
                if (!$status_name) $status_name = 'New';
                $modal_id = 'modal-quote-' . $quote_id;
            ?>
            <tr>
                <td><strong><?php the_title(); ?></strong></td>
                <td><?php echo esc_html($email); ?></td>
                <td><?php echo esc_html($status_name); ?></td>
                <td><?php echo get_the_date('M j, Y'); ?></td>
                <td>
                    <button type="button" class="button" onclick="kgOpenModal('<?php echo $modal_id; ?>')">View Details</button>
                    
                    <!-- Modal -->
                    <div id="<?php echo $modal_id; ?>" class="kg-modal-overlay" onclick="if(event.target===this) kgCloseModal('<?php echo $modal_id; ?>')">
                        <div class="kg-modal">
                            <div class="kg-modal-header">
                                <h3 class="kg-modal-title">Quote Request: <?php the_title(); ?></h3>
                                <button type="button" class="kg-modal-close" onclick="kgCloseModal('<?php echo $modal_id; ?>')">&times;</button>
                            </div>
                            <div class="kg-modal-body">
                                <div class="kg-modal-field"><label>Email</label><div><?php echo esc_html($email); ?></div></div>
                                <div class="kg-modal-field"><label>Phone</label><div><?php echo esc_html(get_post_meta($quote_id, 'kg_quote_phone', true) ?: '—'); ?></div></div>
                                <div class="kg-modal-field"><label>Estimated Monthly Total</label><div><strong style="color:#0A2540;font-size:16px;">$<?php echo number_format((float)get_post_meta($quote_id, 'kg_quote_total', true), 0); ?>/mo</strong></div></div>
                                <div class="kg-modal-field"><label>Team Roles</label><div><?php 
                                    $roles = json_decode(get_post_meta($quote_id, 'kg_quote_roles', true), true);
                                    if (!empty($roles)) {
                                        echo '<ul style="margin:0;padding-left:16px;color:#555;">';
                                        foreach($roles as $r) {
                                            echo '<li>' . esc_html($r['role'] . ' - ' . $r['level']) . ' &times; ' . intval($r['qty']) . '</li>';
                                        }
                                        echo '</ul>';
                                    } else {
                                        echo '—';
                                    }
                                ?></div></div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <?php endwhile; else: ?>
            <tr><td colspan="5" style="text-align:center; padding:24px; color:#64748b;">No quote requests found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    
    <?php
    $total_quote_pages = $quote_query->max_num_pages;
    if ($total_quote_pages > 1):
    ?>
    <div class="kg-kpi-table-pagination">
        <div>Page <?php echo $quote_paged; ?> of <?php echo $total_quote_pages; ?></div>
        <div>
            <?php if ($quote_paged > 1): ?>
                <a href="<?php echo esc_url(add_query_arg('quote_paged', $quote_paged - 1)); ?>#tab-quotes" class="button">&laquo; Prev</a>
            <?php endif; ?>
            <?php if ($quote_paged < $total_quote_pages): ?>
                <a href="<?php echo esc_url(add_query_arg('quote_paged', $quote_paged + 1)); ?>#tab-quotes" class="button">Next &raquo;</a>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; wp_reset_postdata(); ?>
</div>
<?php endif; ?>

<?php
// --- AUDIT LOGS TAB ---
if ( ! kg_is_current_user_recruiter() ) :

    global $wpdb;
    $audit_table = $wpdb->prefix . 'kg_audit_logs';

    $audit_paged = isset($_GET['audit_paged']) ? max(1, intval($_GET['audit_paged'])) : 1;
    $audit_per_page = 20;
    $audit_offset = ($audit_paged - 1) * $audit_per_page;

    // We can also filter by month if needed, but for now we'll just show all logs ordered by time
    // Optionally filter by selected month
    $audit_where = "WHERE timestamp LIKE %s";
    $audit_month_like = $selected_year_month . '%';
    
    // Check if table exists before querying to prevent fatal error on first load before dbDelta fires correctly
    $table_exists = $wpdb->get_var("SHOW TABLES LIKE '$audit_table'") === $audit_table;
    
    if ($table_exists) {
        $total_audits = $wpdb->get_var($wpdb->prepare("SELECT COUNT(id) FROM {$audit_table} " . $audit_where, $audit_month_like));
        $audit_results = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$audit_table} " . $audit_where . " ORDER BY timestamp DESC LIMIT %d OFFSET %d", $audit_month_like, $audit_per_page, $audit_offset));
        $total_audit_pages = ceil($total_audits / $audit_per_page);
    } else {
        $audit_results = array();
        $total_audit_pages = 0;
    }
?>
<div id="tab-audit-logs" class="kg-kpi-tab-content">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
        <h2 style="margin:0; font-size:20px; font-weight:700;">Global Audit Logs (<?php echo esc_html(date('F Y', strtotime($selected_year_month . '-01'))); ?>)</h2>
    </div>

    <table class="kg-kpi-table">
        <thead>
            <tr>
                <th>Date & Time</th>
                <th>Action</th>
                <th>Applicant</th>
                <th>Actor (User)</th>
                <th>Assigned To</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($audit_results)): foreach ($audit_results as $log): 
                $app_title = get_the_title($log->post_id) ?: 'Unknown Applicant';
            ?>
            <tr>
                <td style="white-space:nowrap;"><?php echo date('M j, Y g:i A', strtotime($log->timestamp)); ?></td>
                <td><span style="background:#f1f5f9; padding:4px 8px; border-radius:4px; font-size:12px; font-weight:bold;"><?php echo esc_html($log->action); ?></span></td>
                <td>
                    <a href="<?php echo get_edit_post_link($log->post_id); ?>" target="_blank" style="text-decoration:none;font-weight:600;">
                        <?php echo esc_html($app_title); ?>
                    </a>
                </td>
                <td><?php echo esc_html($log->actor); ?></td>
                <td><?php echo esc_html($log->assignee); ?></td>
            </tr>
            <?php endforeach; else: ?>
            <tr><td colspan="5" style="text-align:center; padding:24px; color:#64748b;">No routing activity found for this period.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    
    <?php if ($total_audit_pages > 1): ?>
    <div class="kg-kpi-table-pagination">
        <div>Page <?php echo $audit_paged; ?> of <?php echo $total_audit_pages; ?></div>
        <div>
            <?php if ($audit_paged > 1): ?>
                <a href="<?php echo esc_url(add_query_arg('audit_paged', $audit_paged - 1)); ?>#tab-audit-logs" class="button">&laquo; Prev</a>
            <?php endif; ?>
            <?php if ($audit_paged < $total_audit_pages): ?>
                <a href="<?php echo esc_url(add_query_arg('audit_paged', $audit_paged + 1)); ?>#tab-audit-logs" class="button">Next &raquo;</a>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php endif; ?>

