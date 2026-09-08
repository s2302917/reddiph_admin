<?php
/**
 * emergency_alertView.php
 *
 * Presentation view for Emergency Alerts.
 * Data is supplied by EmergencyAlertController.
 */

// If view is opened directly without controller, bootstrap through controller
if (!isset($alerts) || !isset($summary)) {
    require_once __DIR__ . '/../controllers/emergency_alertController.php';
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emergency Alerts — PulseAlert</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../public/css/emergency_alert.css">
</head>
<body class="emergency_alert_body">
<div class="emergency_alert_shell" id="emergency_alert_shell">

    <aside class="emergency_alert_sidebar" id="emergency_alert_sidebar">
        <div class="emergency_alert_brand" id="emergency_alert_brand">
            <span class="emergency_alert_brand_badge">PULSE<br>ALERT</span>
            <span class="emergency_alert_brand_tag">ADMIN</span>
        </div>

        <nav class="emergency_alert_nav" id="emergency_alert_nav" aria-label="Primary">
            <a href="/reddiph_admin/app/controllers/dispatchController.php" class="emergency_alert_nav_item" id="emergency_alert_nav_dispatches">
                <span class="emergency_alert_nav_icon" aria-hidden="true">📋</span>
                <span>Incoming dispatches</span>
            </a>

            <a href="/reddiph_admin/app/controllers/dashboardController.php" class="emergency_alert_nav_item" id="emergency_alert_nav_dashboard">
                <span class="emergency_alert_nav_icon" aria-hidden="true">📊</span>
                <span>Dashboard</span>
            </a>

            <a href="/reddiph_admin/app/controllers/emergency_alertController.php" class="emergency_alert_nav_item emergency_alert_nav_item_active" id="emergency_alert_nav_alerts">
                <span class="emergency_alert_nav_icon" aria-hidden="true">⚠️</span>
                <span>Emergency Alerts</span>
            </a>

            <a href="#" class="emergency_alert_nav_item" id="emergency_alert_nav_mapping">
                <span class="emergency_alert_nav_icon" aria-hidden="true">🗺️</span>
                <span>Live Mapping</span>
            </a>

            <a href="#" class="emergency_alert_nav_item" id="emergency_alert_nav_schedule">
                <span class="emergency_alert_nav_icon" aria-hidden="true">👥</span>
                <span>Staff Schedule</span>
            </a>

            <a href="#" class="emergency_alert_nav_item" id="emergency_alert_nav_network">
                <span class="emergency_alert_nav_icon" aria-hidden="true">🏥</span>
                <span>Hospital Network</span>
            </a>

            <a href="#" class="emergency_alert_nav_item" id="emergency_alert_nav_analytics">
                <span class="emergency_alert_nav_icon" aria-hidden="true">📈</span>
                <span>Analytics</span>
            </a>

            <a href="#" class="emergency_alert_nav_item" id="emergency_alert_nav_logs">
                <span class="emergency_alert_nav_icon" aria-hidden="true">📋</span>
                <span>Logs &amp; Demographics</span>
            </a>

            <a href="#" class="emergency_alert_nav_item" id="emergency_alert_nav_settings">
                <span class="emergency_alert_nav_icon" aria-hidden="true">⚙️</span>
                <span>Settings</span>
            </a>
        </nav>
        <div class="emergency_alert_system_status" id="emergency_alert_system_status">
            <span class="emergency_alert_system_status_icon" aria-hidden="true">!</span>

            <div>
                <p class="emergency_alert_system_status_title">System Status</p>
                <p class="emergency_alert_system_status_sub">All systems operational.</p>
            </div>
        </div>
    </aside>

    <main class="emergency_alert_main" id="emergency_alert_main">
        <header class="emergency_alert_header" id="emergency_alert_header">
            <div class="emergency_alert_header_copy" id="emergency_alert_header_copy">
                <h1 class="emergency_alert_title" id="emergency_alert_title">Emergency Alerts</h1>
                <p class="emergency_alert_subtitle" id="emergency_alert_subtitle"><?= htmlspecialchars($pageTitle ?? 'La Carlota District Hospital : Admitting & Coordination') ?></p>
            </div>

            <div class="emergency_alert_header_controls" id="emergency_alert_header_controls">
                <label class="emergency_alert_search" id="emergency_alert_search_wrap">
                    <span class="emergency_alert_search_icon" aria-hidden="true">⌕</span>
                    <input type="search" id="emergency_alert_search_input" class="emergency_alert_search_input" placeholder="Search incidents, hospitals, or units..." autocomplete="off">
                </label>

                <button type="button" class="emergency_alert_bell" id="emergency_alert_bell_btn" aria-label="Notifications">
                    <span aria-hidden="true">🔔</span>
                    <span class="emergency_alert_bell_dot" id="emergency_alert_bell_dot" <?= (($alertCount ?? 0) > 0) ? '' : 'hidden' ?>></span>
                </button>

                <button type="button" class="emergency_alert_user" id="emergency_alert_user_menu" aria-label="Open user menu">
                    <span class="emergency_alert_user_text">
                        <strong class="emergency_alert_user_name" id="emergency_alert_user_name"><?= htmlspecialchars($coordinatorName ?? 'Charlotte M.') ?></strong>
                        <span class="emergency_alert_user_role" id="emergency_alert_user_role"><?= htmlspecialchars($coordinatorRole ?? 'Chief Coordinator') ?></span>
                    </span>
                    <span class="emergency_alert_avatar" id="emergency_alert_avatar" aria-hidden="true">CM</span>
                </button>
            </div>
        </header>

        <section class="emergency_alert_summary" id="emergency_alert_summary" aria-label="Emergency alert summary">
            <button type="button" class="emergency_alert_summary_card" id="emergency_alert_summary_critical" data-status="critical">
                <span class="emergency_alert_summary_label">Critical</span>
                <strong class="emergency_alert_summary_value" id="emergency_alert_summary_critical_value"><?= (int) ($summary['critical'] ?? 0) ?></strong>
            </button>

            <button type="button" class="emergency_alert_summary_card" id="emergency_alert_summary_dispatched" data-status="dispatched">
                <span class="emergency_alert_summary_label">Dispatched</span>
                <strong class="emergency_alert_summary_value" id="emergency_alert_summary_dispatched_value"><?= (int) ($summary['dispatched'] ?? 0) ?></strong>
            </button>

            <button type="button" class="emergency_alert_summary_card" id="emergency_alert_summary_stable" data-status="stable">
                <span class="emergency_alert_summary_label">Stable</span>
                <strong class="emergency_alert_summary_value" id="emergency_alert_summary_stable_value"><?= (int) ($summary['stable'] ?? 0) ?></strong>
            </button>

            <button type="button" class="emergency_alert_summary_card" id="emergency_alert_summary_resolved" data-status="resolved">
                <span class="emergency_alert_summary_label">Resolved today</span>
                <strong class="emergency_alert_summary_value" id="emergency_alert_summary_resolved_value"><?= (int) ($summary['resolvedToday'] ?? 0) ?></strong>
            </button>
        </section>

        <section class="emergency_alert_filter_bar" id="emergency_alert_filter_bar" aria-label="Alert filters">
            <button type="button" class="emergency_alert_filter emergency_alert_filter_active" id="emergency_alert_filter_all" data-status="all">ALL ALERTS</button>
            <button type="button" class="emergency_alert_filter" id="emergency_alert_filter_critical" data-status="critical">CRITICAL</button>
            <button type="button" class="emergency_alert_filter" id="emergency_alert_filter_dispatched" data-status="dispatched">DISPATCHED</button>
            <button type="button" class="emergency_alert_filter" id="emergency_alert_filter_stable" data-status="stable">STABLE</button>
        </section>

        <div class="emergency_alert_notice" id="emergency_alert_critical_notice" hidden>
            THESE CASES NEED IMMEDIATE STAFF NOTIFICATION. CONFIRM DOCTOR AND NURSE ASSIGNMENT AS SOON AS POSSIBLE.
        </div>

        <section class="emergency_alert_table_panel" id="emergency_alert_table_panel">
            <div class="emergency_alert_table_wrap" id="emergency_alert_table_wrap">
                <table class="emergency_alert_table emergency_alert_table_all" id="emergency_alert_table" data-view="all">
                    <thead id="emergency_alert_table_head">
                        <tr id="emergency_alert_table_head_row">
                            <th>STATUS</th>
                            <th>INCIDENT TYPE</th>
                            <th>LOCATION</th>
                            <th>TIME</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody id="emergency_alert_table_body">
                    <?php if (!empty($alerts)): ?>
                        <?php foreach ($alerts as $index => $alert): ?>
                            <tr class="emergency_alert_row" id="emergency_alert_row_<?= (int) $alert['id'] ?>" data-alert-id="<?= (int) $alert['id'] ?>" data-status="<?= htmlspecialchars(strtolower($alert['status'])) ?>" style="animation-delay: <?= $index * 55 ?>ms;">
                                <td>
                                    <span class="emergency_alert_status emergency_alert_status_<?= htmlspecialchars(strtolower($alert['status'])) ?>">
                                        <?= htmlspecialchars($alert['status']) ?>
                                    </span>
                                </td>
                                <td class="emergency_alert_incident_cell">
                                    <strong class="emergency_alert_incident"><?= htmlspecialchars($alert['incidentType']) ?></strong>
                                    <?php if (!empty($alert['patientInfo'])): ?>
                                        <small class="emergency_alert_patient_info"><?= htmlspecialchars($alert['patientInfo']) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td class="emergency_alert_location"><?= htmlspecialchars($alert['location']) ?></td>
                                <td class="emergency_alert_time"><?= htmlspecialchars($alert['timeAgo']) ?></td>
                                <td>
                                    <?php if (strtolower($alert['status']) === 'critical'): ?>
                                        <button type="button" class="emergency_alert_action emergency_alert_action_notify" data-action="notify" data-alert-id="<?= (int) $alert['id'] ?>">
                                            NOTIFY STAFF <span>&rarr;</span>
                                        </button>
                                    <?php else: ?>
                                        <button type="button" class="emergency_alert_action emergency_alert_action_details" data-action="details" data-alert-id="<?= (int) $alert['id'] ?>">
                                            VIEW DETAILS <span>&rarr;</span>
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
                <div class="emergency_alert_empty" id="emergency_alert_empty" <?= empty($alerts) ? '' : 'hidden' ?>>No emergency alerts found.</div>
            </div>
        </section>
    </main>
</div>

<div class="emergency_alert_modal" id="emergency_alert_details_modal" aria-hidden="true">
    <div class="emergency_alert_modal_backdrop" id="emergency_alert_modal_backdrop"></div>
    <section class="emergency_alert_modal_card" id="emergency_alert_modal_card" role="dialog" aria-modal="true" aria-labelledby="emergency_alert_modal_title">
        <button type="button" class="emergency_alert_modal_close" id="emergency_alert_modal_close" aria-label="Close">&times;</button>
        <p class="emergency_alert_modal_kicker">Emergency Alert</p>
        <h2 class="emergency_alert_modal_title" id="emergency_alert_modal_title">Alert Details</h2>
        <div class="emergency_alert_modal_body" id="emergency_alert_modal_body"></div>
    </section>
</div>

<div class="emergency_alert_toast" id="emergency_alert_toast" role="status" aria-live="polite"></div>

<script type="application/json" id="emergency_alert_initial_data"><?= htmlspecialchars(json_encode($initialData ?? ['alerts' => $alerts, 'summary' => $summary], JSON_UNESCAPED_SLASHES), ENT_NOQUOTES) ?></script>
<script src="../../public/js/script.js"></script>
</body>
</html>
