<?php
/**
 * dashboardView.php
 *
 * VIEW — Contains all HTML markup for the dashboard.
 * All class and ID attributes are prefixed with "dashboard-" for namespace isolation.
 * This file receives clean data arrays from the Controller and renders the page.
 */
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Dashboard') ?> — PulseAlert Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/dashboard.css">
</head>

<body class="dashboard-body">

    <!-- ============ SIDEBAR ============ -->
    <aside class="dashboard-sidebar">
        <div class="dashboard-sidebar-header">
            <div class="dashboard-logo">
                <svg viewBox="0 0 24 24" fill="currentColor" class="dashboard-logo-icon">
                    <circle cx="12" cy="12" r="10" opacity="0.2" />
                    <path d="M12 2a10 10 0 0110 10" />
                </svg>
                <div class="dashboard-logo-text">
                    <div class="dashboard-logo-brand">PULSE</div>
                    <div class="dashboard-logo-subtext">ALERT</div>
                </div>
                <div class="dashboard-logo-badge">ADMIN</div>
            </div>
        </div>

        <nav class="dashboard-sidebar-nav">
            <a href="/app/controllers/dispatchController.php" class="dashboard-nav-item">
                <span class="dashboard-nav-icon">📋</span>
                <span class="dashboard-nav-label">Incoming dispatches</span>
            </a>
            <a href="/app/controllers/dashboardController.php" class="dashboard-nav-item dashboard-nav-item--active">
                <span class="dashboard-nav-icon">📊</span>
                <span class="dashboard-nav-label">Dashboard</span>
            </a>
            <a href="#" class="dashboard-nav-item">
                <span class="dashboard-nav-icon">⚠️</span>
                <span class="dashboard-nav-label">Emergency Alerts</span>
            </a>
            <a href="#" class="dashboard-nav-item">
                <span class="dashboard-nav-icon">🗺️</span>
                <span class="dashboard-nav-label">Live Mapping</span>
            </a>
            <a href="#" class="dashboard-nav-item">
                <span class="dashboard-nav-icon">👥</span>
                <span class="dashboard-nav-label">Staff Schedule</span>
            </a>
            <a href="#" class="dashboard-nav-item">
                <span class="dashboard-nav-icon">🏥</span>
                <span class="dashboard-nav-label">Hospital Network</span>
            </a>
            <a href="#" class="dashboard-nav-item">
                <span class="dashboard-nav-icon">📈</span>
                <span class="dashboard-nav-label">Analytics</span>
            </a>
            <a href="#" class="dashboard-nav-item">
                <span class="dashboard-nav-icon">📋</span>
                <span class="dashboard-nav-label">Logs & Demographics</span>
            </a>
            <a href="#" class="dashboard-nav-item">
                <span class="dashboard-nav-icon">⚙️</span>
                <span class="dashboard-nav-label">Settings</span>
            </a>
        </nav>

        <div class="dashboard-sidebar-footer">
            <div class="dashboard-system-status">
                <div class="dashboard-status-indicator"></div>
                <span class="dashboard-status-text">System Status</span>
            </div>
            <p class="dashboard-status-message"><?= htmlspecialchars($systemStatus ?? 'All systems operational') ?></p>
        </div>
    </aside>

    <!-- ============ MAIN LAYOUT ============ -->
    <div class="dashboard-container">

        <!-- ============ HEADER ============ -->
        <header class="dashboard-header">
            <div class="dashboard-header-left">
                <h1 class="dashboard-header-hospital-name"><?= htmlspecialchars($hospitalName ?? '') ?></h1>
                <h2 class="dashboard-page-title"><?= htmlspecialchars($pageTitle ?? 'Dashboard') ?></h2>
                <p class="dashboard-page-subtitle">
                    <?= htmlspecialchars($hospitalSubtitle ?? 'Admitting & Coordination') ?>
                </p>
            </div>

            <div class="dashboard-header-controls">
                <div class="dashboard-search">
                    <span class="dashboard-search-icon" aria-hidden="true">⌕</span>
                    <input type="text" class="dashboard-search-input" id="dashboard-search-input"
                        placeholder="Search incidents, hospitals, or units..." aria-label="Search">
                </div>

                <button type="button" class="dashboard-bell" id="dashboard-bell-btn" aria-label="Notifications">
                    🔔
                    <span class="dashboard-bell-dot" id="dashboard-bell-dot"></span>
                </button>

                <div class="dashboard-user" id="dashboard-user-menu">
                    <div class="dashboard-user-avatar" aria-hidden="true"></div>
                    <div class="dashboard-user-text">
                        <p class="dashboard-user-name"><?= htmlspecialchars($coordinatorName ?? 'User') ?></p>
                        <p class="dashboard-user-role"><?= htmlspecialchars($coordinatorRole ?? 'Admin') ?></p>
                    </div>
                </div>
            </div>
        </header>

        <!-- ============ MAIN CONTENT ============ -->
        <main class="dashboard-main">

            <!-- Metrics Row -->
            <section class="dashboard-metrics">
                <?php foreach (($metrics ?? []) as $key => $metric): ?>
                    <div class="dashboard-metric-card" id="dashboard-metric-<?= htmlspecialchars($key) ?>">
                        <h3 class="dashboard-metric-label"><?= htmlspecialchars($metric['label']) ?></h3>
                        <div class="dashboard-metric-content">
                            <?php if (isset($metric['value'])): ?>
                                <div class="dashboard-metric-value">
                                    <?= htmlspecialchars($metric['value']) ?>         <?php if (isset($metric['unit'])): ?><span
                                            class="dashboard-metric-unit"><?= htmlspecialchars($metric['unit']) ?></span><?php endif; ?>
                                </div>
                            <?php endif; ?>
                            <?php if (isset($metric['display'])): ?>
                                <div class="dashboard-metric-display"><?= htmlspecialchars($metric['display']) ?></div>
                            <?php endif; ?>
                            <?php if (isset($metric['badge'])): ?>
                                <span class="dashboard-metric-badge <?= htmlspecialchars($metric['badgeClass'] ?? '') ?>">
                                    <?= htmlspecialchars($metric['badge']) ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        <?php if (isset($metric['percent'])): ?>
                            <div class="dashboard-metric-bar-track">
                                <div class="dashboard-metric-bar-fill" data-percent="<?= (int) $metric['percent'] ?>"></div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </section>

            <!-- Content Grid -->
            <div class="dashboard-grid">

                <!-- Left Column: Live Alert Feed -->
                <section class="dashboard-panel dashboard-panel--alerts">
                    <div class="dashboard-panel-header">
                        <h2 class="dashboard-panel-title">Live Alert Feed</h2>
                        <a href="#" class="dashboard-panel-action">View History →</a>
                    </div>
                    <div class="dashboard-panel-body">
                        <table class="dashboard-alerts-table">
                            <thead class="dashboard-alerts-thead">
                                <tr>
                                    <th class="dashboard-alerts-th">STATUS</th>
                                    <th class="dashboard-alerts-th">INCIDENT TYPE</th>
                                    <th class="dashboard-alerts-th">LOCATION</th>
                                    <th class="dashboard-alerts-th">TIME</th>
                                </tr>
                            </thead>
                            <tbody class="dashboard-alerts-tbody">
                                <?php foreach (($alerts ?? []) as $alert): ?>
                                    <tr class="dashboard-alert-row">
                                        <td class="dashboard-alert-cell">
                                            <span
                                                class="dashboard-alert-badge <?= htmlspecialchars($alert['statusClass']) ?>">
                                                <?= htmlspecialchars($alert['status']) ?>
                                            </span>
                                        </td>
                                        <td class="dashboard-alert-cell"><?= htmlspecialchars($alert['incidentType']) ?>
                                        </td>
                                        <td class="dashboard-alert-cell"><?= htmlspecialchars($alert['location']) ?></td>
                                        <td class="dashboard-alert-cell"><?= htmlspecialchars($alert['timeAgo']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </section>

                <!-- Right Column: Response Performance Chart -->
                <section class="dashboard-panel dashboard-panel--chart">
                    <div class="dashboard-panel-header">
                        <h2 class="dashboard-panel-title">Response Performance</h2>
                    </div>
                    <div class="dashboard-panel-body">
                        <canvas id="dashboard-response-chart" class="dashboard-chart" width="640" height="300"
                            data-chart-data="<?= htmlspecialchars($responseChart ?? '{}') ?>"></canvas>
                    </div>
                </section>

            </div>

            <!-- Hospital Network Status -->
            <section class="dashboard-hospital-network">
                <div class="dashboard-hospital-header">
                    <h2 class="dashboard-hospital-title">Hospital Network Status</h2>
                    <button class="dashboard-filter-btn">▼ Specialty: All</button>
                </div>
                <div class="dashboard-hospital-grid">
                    <?php foreach (($hospitals ?? []) as $hospital): ?>
                        <div class="dashboard-hospital-card"
                            id="dashboard-hospital-<?= htmlspecialchars($hospital['id']) ?>">
                            <div class="dashboard-hospital-card-header">
                                <h3 class="dashboard-hospital-name"><?= htmlspecialchars($hospital['name']) ?></h3>
                                <span
                                    class="dashboard-hospital-distance"><?= htmlspecialchars($hospital['distanceKm']) ?>km</span>
                            </div>
                            <div class="dashboard-hospital-metric">
                                <span class="dashboard-hospital-metric-label">Bed Availability</span>
                                <span
                                    class="dashboard-hospital-metric-value"><?= htmlspecialchars($hospital['bedDisplay']) ?></span>
                            </div>
                            <div class="dashboard-hospital-bar-track">
                                <div class="dashboard-hospital-bar-fill"
                                    data-percent="<?= (int) $hospital['capacityPercent'] ?>"></div>
                            </div>
                            <div class="dashboard-hospital-specs">
                                <?php foreach ($hospital['specialList'] as $spec): ?>
                                    <span class="dashboard-hospital-spec-tag"><?= htmlspecialchars(trim($spec)) ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

        </main>

    </div>

    <!-- ============ SCRIPTS ============ -->
    <script src="/public/js/script.js"></script>
    <script>
        // Initialize dashboard animations and interactions when DOM is ready
        document.addEventListener('DOMContentLoaded', function () {
            // Header interactions
            if (typeof dashboardInitHeaderInteractions === 'function') {
                dashboardInitHeaderInteractions();
            }
            // Metric animations
            if (typeof dashboardAnimateMetricBars === 'function') {
                dashboardAnimateMetricBars();
            }
            if (typeof dashboardAnimateHospitalBars === 'function') {
                dashboardAnimateHospitalBars();
            }
            // Chart initialization
            if (typeof dashboardInitChart === 'function') {
                dashboardInitChart();
            }
        });
    </script>

</body>

</html>