<?php
/**
 * dispatchView.php
 *
 * VIEW — HTML only. No SQL, no business logic.
 * All variables ($barangays, $facilityStatus, $staffOnDuty, $recentActivity,
 * $hospitalName, $coordinatorName, $coordinatorRole, $alertCount) are
 * injected by dispatchController::render(). This build's dispatchModel
 * uses hardcoded data instead of a database, so this view works with
 * no DB setup required.
 *
 * Every class/id starts with "incomingdispatch" so incomingdispatch.css
 * stays fully scoped and won't collide with other pages' styles.
 */
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle ?? 'Incoming Dispatches') ?> — PulseAlert</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../../public/css/incomingdispatch.css">
</head>

<body class="incomingdispatch-body">

  <div class="incomingdispatch-shell">

    <!-- ===================== SIDEBAR ===================== -->
    <aside class="incomingdispatch-sidebar" id="incomingdispatch-sidebar">
      <div class="incomingdispatch-brand">
        <span class="incomingdispatch-brand-badge">PULSE<br>ALERT</span>
        <span class="incomingdispatch-brand-tag">ADMIN</span>
      </div>

      <nav class="incomingdispatch-nav" aria-label="Primary">
        <a href="#" class="incomingdispatch-nav-item incomingdispatch-nav-item--active"
          id="incomingdispatch-nav-dispatches">
          <span class="incomingdispatch-nav-icon" aria-hidden="true">📋</span>
          Incoming dispatches
        </a>
        <a href="#" class="incomingdispatch-nav-item" id="incomingdispatch-nav-dashboard">
          <span class="incomingdispatch-nav-icon" aria-hidden="true">📊</span>
          Dashboard
        </a>
        <a href="#" class="incomingdispatch-nav-item" id="incomingdispatch-nav-alerts">
          <span class="incomingdispatch-nav-icon" aria-hidden="true">⚠️</span>
          Emergency Alerts
        </a>
        <a href="#" class="incomingdispatch-nav-item" id="incomingdispatch-nav-mapping">
          <span class="incomingdispatch-nav-icon" aria-hidden="true">🗺️</span>
          Live Mapping
        </a>
        <a href="#" class="incomingdispatch-nav-item" id="incomingdispatch-nav-schedule">
          <span class="incomingdispatch-nav-icon" aria-hidden="true">👥</span>
          Staff Schedule
        </a>
        <a href="#" class="incomingdispatch-nav-item" id="incomingdispatch-nav-network">
          <span class="incomingdispatch-nav-icon" aria-hidden="true">🏥</span>
          Hospital Network
        </a>
        <a href="#" class="incomingdispatch-nav-item" id="incomingdispatch-nav-analytics">
          <span class="incomingdispatch-nav-icon" aria-hidden="true">📈</span>
          Analytics
        </a>
        <a href="#" class="incomingdispatch-nav-item" id="incomingdispatch-nav-logs">
          <span class="incomingdispatch-nav-icon" aria-hidden="true">📋</span>
          Logs &amp; Demographics
        </a>
        <a href="#" class="incomingdispatch-nav-item" id="incomingdispatch-nav-settings">
          <span class="incomingdispatch-nav-icon" aria-hidden="true">⚙️</span>
          Settings
        </a>
      </nav>

      <div class="incomingdispatch-system-status" id="incomingdispatch-system-status">
        <span class="incomingdispatch-system-status-icon" aria-hidden="true">!</span>
        <div>
          <p class="incomingdispatch-system-status-title">System Status</p>
          <p class="incomingdispatch-system-status-sub">All systems operational.</p>
        </div>
      </div>
    </aside>

    <!-- ===================== MAIN CONTENT ===================== -->
    <main class="incomingdispatch-main">

      <!-- Header -->
      <header class="incomingdispatch-header">
        <div>
          <h1 class="incomingdispatch-hospital-name"><?= htmlspecialchars($hospitalName ?? '') ?></h1>
          <h2 class="incomingdispatch-page-title"><?= htmlspecialchars($pageTitle ?? '') ?></h2>
          <p class="incomingdispatch-page-subtitle">Live requests from barangay emergency teams</p>
        </div>

        <div class="incomingdispatch-header-controls">
          <div class="incomingdispatch-search">
            <span class="incomingdispatch-search-icon" aria-hidden="true">⌕</span>
            <input type="text" id="incomingdispatch-search-input" class="incomingdispatch-search-input"
              placeholder="Search incidents, hospitals, or units...">
          </div>

          <button type="button" class="incomingdispatch-bell" id="incomingdispatch-bell-btn" aria-label="Notifications">
            🔔
            <?php if (($alertCount ?? 0) > 0): ?>
              <span class="incomingdispatch-bell-dot" id="incomingdispatch-bell-dot"></span>
            <?php endif; ?>
          </button>

          <div class="incomingdispatch-user" id="incomingdispatch-user-menu">
            <div class="incomingdispatch-user-avatar" aria-hidden="true"></div>
            <div class="incomingdispatch-user-text">
              <p class="incomingdispatch-user-name"><?= htmlspecialchars($coordinatorName ?? '') ?></p>
              <p class="incomingdispatch-user-role"><?= htmlspecialchars($coordinatorRole ?? '') ?></p>
            </div>
          </div>
        </div>
      </header>

      <!-- Barangay dispatch grid -->
      <section class="incomingdispatch-grid" id="incomingdispatch-barangay-grid" aria-label="Barangay dispatch status">
        <?php foreach (($barangays ?? []) as $b): ?>
          <button type="button" class="incomingdispatch-card <?= $b['isActive'] ? 'incomingdispatch-card--active' : '' ?>"
            id="incomingdispatch-card-<?= htmlspecialchars($b['slug']) ?>" data-barangay-id="<?= (int) $b['id'] ?>"
            data-count="<?= (int) $b['count'] ?>">
            <span class="incomingdispatch-card-pin" aria-hidden="true">📍</span>
            <?php if ($b['count'] > 0): ?>
              <span class="incomingdispatch-card-badge"><?= (int) $b['count'] ?></span>
            <?php endif; ?>
            <span class="incomingdispatch-card-name"><?= htmlspecialchars($b['name']) ?></span>
            <span class="incomingdispatch-card-status"><?= htmlspecialchars($b['statusLabel']) ?></span>
          </button>
        <?php endforeach; ?>

        <button type="button" class="incomingdispatch-card incomingdispatch-card--network"
          id="incomingdispatch-card-allbrgys">
          <span class="incomingdispatch-card-grid-icon" aria-hidden="true">▦</span>
          <span class="incomingdispatch-card-name">All Brgys</span>
          <span class="incomingdispatch-card-status">View Network</span>
        </button>
      </section>

      <!-- Facility status + Staff on duty -->
      <section class="incomingdispatch-panels-row">

        <div class="incomingdispatch-panel" id="incomingdispatch-panel-facility">
          <h3 class="incomingdispatch-panel-title">Facility Status</h3>
          <div class="incomingdispatch-panel-body">

            <?php foreach (($facilityStatus ?? []) as $key => $metric): ?>
              <div class="incomingdispatch-metric-row" id="incomingdispatch-metric-<?= htmlspecialchars($key) ?>">

                <span class="incomingdispatch-metric-label">
                  <?= htmlspecialchars($metric['label']) ?>
                </span>

                <?php if (isset($metric['percent']) && $metric['statusText'] === null): ?>

                  <div class="incomingdispatch-metric-bar-track">
                    <div class="incomingdispatch-metric-bar-fill <?= htmlspecialchars($metric['capacityClass']) ?>"
                      data-percent="<?= (int) $metric['percent'] ?>">
                    </div>
                  </div>

                  <span class="incomingdispatch-metric-value">
                    <?= htmlspecialchars($metric['display']) ?>
                  </span>

                <?php elseif ($metric['statusText'] === 'Open'): ?>

                  <span class="incomingdispatch-metric-pill incomingdispatch-metric-pill--open">
                    <?= htmlspecialchars($metric['statusText']) ?>
                  </span>

                <?php else: ?>

                  <span class="incomingdispatch-metric-value">
                    <?= htmlspecialchars($metric['display']) ?>
                  </span>

                <?php endif; ?>

              </div>
            <?php endforeach; ?>

          </div>
        </div>

        <div class="incomingdispatch-panel" id="incomingdispatch-panel-staff">
          <h3 class="incomingdispatch-panel-title">Staff On Duty</h3>
          <div class="incomingdispatch-panel-body">

            <?php foreach (($staffOnDuty ?? []) as $i => $s): ?>
              <div class="incomingdispatch-staff-row" id="incomingdispatch-staff-<?= $i ?>">
                <span class="incomingdispatch-staff-avatar"><?= htmlspecialchars($s['initials']) ?></span>
                <div class="incomingdispatch-staff-text">
                  <p class="incomingdispatch-staff-name"><?= htmlspecialchars($s['name']) ?></p>
                  <p class="incomingdispatch-staff-dept"><?= htmlspecialchars($s['department']) ?></p>
                </div>
                <span
                  class="incomingdispatch-staff-dot <?= $s['isAvailable'] ? 'incomingdispatch-staff-dot--on' : 'incomingdispatch-staff-dot--off' ?>"></span>
              </div>
            <?php endforeach; ?>

          </div>
        </div>

      </section>

      <!-- Recent activity + Quick actions -->
      <section class="incomingdispatch-panels-row">

        <div class="incomingdispatch-panel" id="incomingdispatch-panel-activity">
          <h3 class="incomingdispatch-panel-title">Recent Activity</h3>
          <div class="incomingdispatch-panel-body">

            <?php foreach (($recentActivity ?? []) as $i => $a): ?>
              <div class="incomingdispatch-activity-row" id="incomingdispatch-activity-<?= $i ?>">
                <span class="incomingdispatch-activity-icon" data-icon-type="<?= htmlspecialchars($a['iconType']) ?>"
                  aria-hidden="true"></span>
                <div class="incomingdispatch-activity-text">
                  <p class="incomingdispatch-activity-message"><?= htmlspecialchars($a['message']) ?></p>
                  <p class="incomingdispatch-activity-time"><?= htmlspecialchars($a['timeAgo']) ?></p>
                </div>
              </div>
            <?php endforeach; ?>

          </div>
        </div>

        <div class="incomingdispatch-panel" id="incomingdispatch-panel-quickactions">
          <h3 class="incomingdispatch-panel-title">Quick Actions</h3>
          <div class="incomingdispatch-panel-body">
            <button type="button" class="incomingdispatch-action-btn incomingdispatch-action-btn--primary"
              id="incomingdispatch-btn-broadcast">
              <span aria-hidden="true">🔔</span> Broadcast alert to all barangays
            </button>
            <button type="button" class="incomingdispatch-action-btn incomingdispatch-action-btn--secondary"
              id="incomingdispatch-btn-export">
              <span aria-hidden="true">⭳</span> Export today's log
            </button>
          </div>
        </div>

      </section>

    </main>
  </div>

  <script src="../../public/js/script.js"></script>
</body>

</html>