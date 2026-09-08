<?php
/**
 * dashboardView.php
 *
 * Pure presentation template. Dashboard content uses only dashboard- classes.
 * Sidebar/header reuse incomingdispatch.css as requested.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — PulseAlert</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../public/css/incomingdispatch.css">
    <link rel="stylesheet" href="../../public/css/dashboard.css">
</head>
<body class="incomingdispatch-body dashboard-body">
<div class="incomingdispatch-shell dashboard-shell">

    <aside class="incomingdispatch-sidebar dashboard-sidebar" id="incomingdispatch-sidebar">
        <div class="incomingdispatch-brand">
            <span class="incomingdispatch-brand-badge">PULSE<br>ALERT</span>
            <span class="incomingdispatch-brand-tag">ADMIN</span>
        </div>

       <nav class="incomingdispatch-nav" aria-label="Primary">
    <a href="/reddiph_admin/app/controllers/dispatchController.php"
       class="incomingdispatch-nav-item"
       id="incomingdispatch-nav-dispatches">
        <span class="incomingdispatch-nav-icon" aria-hidden="true">📋</span>
        Incoming dispatches
    </a>

    <a href="/reddiph_admin/app/controllers/dashboardController.php"
       class="incomingdispatch-nav-item incomingdispatch-nav-item--active"
       id="incomingdispatch-nav-dashboard">
        <span class="incomingdispatch-nav-icon" aria-hidden="true">📊</span>
        Dashboard
    </a>

    <a href="/reddiph_admin/app/controllers/emergency_alertController.php"
       class="incomingdispatch-nav-item"
       id="incomingdispatch-nav-alerts">
        <span class="incomingdispatch-nav-icon" aria-hidden="true">⚠️</span>
        Emergency Alerts
    </a>

    <a href="#"
       class="incomingdispatch-nav-item"
       id="incomingdispatch-nav-mapping">
        <span class="incomingdispatch-nav-icon" aria-hidden="true">🗺️</span>
        Live Mapping
    </a>

    <a href="#"
       class="incomingdispatch-nav-item"
       id="incomingdispatch-nav-schedule">
        <span class="incomingdispatch-nav-icon" aria-hidden="true">👥</span>
        Staff Schedule
    </a>

    <a href="#"
       class="incomingdispatch-nav-item"
       id="incomingdispatch-nav-network">
        <span class="incomingdispatch-nav-icon" aria-hidden="true">🏥</span>
        Hospital Network
    </a>

    <a href="#"
       class="incomingdispatch-nav-item"
       id="incomingdispatch-nav-analytics">
        <span class="incomingdispatch-nav-icon" aria-hidden="true">📈</span>
        Analytics
    </a>

    <a href="#"
       class="incomingdispatch-nav-item"
       id="incomingdispatch-nav-logs">
        <span class="incomingdispatch-nav-icon" aria-hidden="true">📋</span>
        Logs &amp; Demographics
    </a>

    <a href="#"
       class="incomingdispatch-nav-item"
       id="incomingdispatch-nav-settings">
        <span class="incomingdispatch-nav-icon" aria-hidden="true">⚙️</span>
        Settings
    </a>
</nav>

        <div class="incomingdispatch-system-status" id="incomingdispatch-system-status">
            <span class="incomingdispatch-system-status-icon">!</span>
            <div>
                <p class="incomingdispatch-system-status-title">System Status</p>
                <p class="incomingdispatch-system-status-sub">All systems operational.</p>
            </div>
        </div>
    </aside>

    <main class="incomingdispatch-main dashboard-main">
        <header class="incomingdispatch-header dashboard-header">
            <div class="dashboard-header-copy">
                <h1 class="dashboard-title">Dashboard</h1>
                <p class="dashboard-subtitle"><?= htmlspecialchars($pageTitle ?? 'La Carlota District Hospital : Admitting & Coordination') ?></p>
            </div>

            <div class="incomingdispatch-header-controls dashboard-header-controls">
                <div class="incomingdispatch-search dashboard-search">
                    <span class="incomingdispatch-search-icon">⌕</span>
                    <input type="text" id="dashboard-search-input" class="incomingdispatch-search-input" placeholder="Search incidents, hospitals, or units..." autocomplete="off">
                </div>

                <button type="button" class="incomingdispatch-bell dashboard-bell" id="dashboard-bell-btn" aria-label="Notifications">
                    🔔
                    <?php if (($alertCount ?? 0) > 0): ?><span class="incomingdispatch-bell-dot" id="dashboard-bell-dot"></span><?php endif; ?>
                </button>

                <div class="incomingdispatch-user dashboard-user" id="incomingdispatch-user-menu">
                    <div class="incomingdispatch-user-text">
                        <p class="incomingdispatch-user-name"><?= htmlspecialchars($coordinatorName ?? 'Charlotte M.') ?></p>
                        <p class="incomingdispatch-user-role"><?= htmlspecialchars($coordinatorRole ?? 'Chief Coordinator') ?></p>
                    </div>
                    <div class="incomingdispatch-user-avatar dashboard-avatar" aria-hidden="true"></div>
                </div>
            </div>
        </header>

        <section class="dashboard-metrics" aria-label="Dashboard metrics">
            <?php foreach ($metrics as $key => $metric): ?>
                <article class="dashboard-metric-card" data-dashboard-metric="<?= htmlspecialchars($key) ?>">
                    <p class="dashboard-metric-label"><?= htmlspecialchars($metric['label']) ?></p>
                    <div class="dashboard-metric-value-row">
                        <strong class="dashboard-metric-value"><?= htmlspecialchars((string) $metric['value']) ?></strong>
                        <?php if ($metric['suffix'] !== ''): ?><span class="dashboard-metric-suffix"><?= htmlspecialchars($metric['suffix']) ?></span><?php endif; ?>
                        <?php if ($metric['new'] !== null): ?><span class="dashboard-metric-new">+<?= (int) $metric['new'] ?> New</span><?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>

        <section class="dashboard-middle-grid">
            <article class="dashboard-panel dashboard-alert-panel">
                <div class="dashboard-panel-heading">
                    <h2>Live Alert Feed</h2>
                    <a href="#" class="dashboard-history-link">View History <span>→</span></a>
                </div>
                <div class="dashboard-alert-table-wrap">
                    <table class="dashboard-alert-table" id="dashboard-alert-table">
                        <thead>
                            <tr><th>STATUS</th><th>INCIDENT TYPE</th><th>LOCATION</th><th>TIME</th></tr>
                        </thead>
                        <tbody>
                        <?php foreach ($alerts as $alert): ?>
                            <tr class="dashboard-alert-row">
                                <td><span class="dashboard-status <?= htmlspecialchars($alert['statusClass']) ?>"><?= htmlspecialchars($alert['status']) ?></span></td>
                                <td class="dashboard-alert-incident"><?= htmlspecialchars($alert['incidentType']) ?></td>
                                <td><?= htmlspecialchars($alert['location']) ?></td>
                                <td><?= htmlspecialchars($alert['timeAgo']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </article>

            <article class="dashboard-panel dashboard-chart-panel">
                <div class="dashboard-panel-heading">
                    <h2>Response Performance</h2>
                </div>
                <div class="dashboard-chart-wrap">
                    <canvas id="dashboard-response-chart" aria-label="Response performance line chart"></canvas>
                </div>
                <script type="application/json" id="dashboard-chart-data"><?= htmlspecialchars(json_encode($responseChart, JSON_UNESCAPED_SLASHES), ENT_NOQUOTES) ?></script>
            </article>
        </section>

        <section class="dashboard-panel dashboard-network-panel">
            <div class="dashboard-panel-heading dashboard-network-heading">
                <h2>Hospital Network Status</h2>
                <button type="button" class="dashboard-filter">⚑&nbsp; Specialty: All</button>
            </div>

            <div class="dashboard-hospital-grid" id="dashboard-hospital-grid">
                <?php foreach ($hospitalNetwork as $hospital): ?>
                    <article class="dashboard-hospital-card">
                        <div class="dashboard-hospital-title-row">
                            <h3><?= htmlspecialchars($hospital['name']) ?></h3>
                            <span class="dashboard-distance"><?= htmlspecialchars($hospital['distance']) ?></span>
                        </div>
                        <div class="dashboard-bed-label-row">
                            <span>Bed Availability</span>
                            <strong><?= (int) $hospital['availableBeds'] ?> / <?= (int) $hospital['totalBeds'] ?></strong>
                        </div>
                        <div class="dashboard-bed-track" aria-label="<?= htmlspecialchars((string) $hospital['availabilityPercent']) ?> percent available">
                            <div class="dashboard-bed-fill" data-percent="<?= htmlspecialchars((string) $hospital['availabilityPercent']) ?>"></div>
                        </div>
                        <div class="dashboard-specialties">
                            <?php foreach ($hospital['specialties'] as $specialty): ?>
                                <span class="dashboard-specialty-tag"><?= htmlspecialchars($specialty) ?></span>
                            <?php endforeach; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
    </main>
</div>
<script src="../../public/js/script.js"></script>
</body>
</html>
