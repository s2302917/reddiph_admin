<?php
/**
 * dispatchController.php
 *
 * CONTROLLER — all logic and functions.
 * Pulls data from dispatchModel (hardcoded for this UI-only build),
 * applies display rules (which barangay cards are active, bar
 * percentages, relative time text, staff initials), and hands a
 * clean $data array to dispatchView.php. No HTML lives here.
 */

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once __DIR__ . '/../models/dispatchModel.php';

class dispatchController
{
    private dispatchModel $model;

    public function __construct()
    {
        $this->model = new dispatchModel();
    }

    /**
     * Main entry point for the Incoming Dispatches page.
     */
    public function index(): void
    {
        $barangays = $this->model->getBarangays();
        $facilityStatus = $this->model->getFacilityStatus();
        $staffOnDuty = $this->model->getStaffOnDuty();
        $recentActivity = $this->model->getRecentActivity(5);

        $data = [
            'pageTitle' => 'Incoming Dispatches',
            'hospitalName' => 'DSB District Hospital',
            'coordinatorName' => 'Charlotte M.',
            'coordinatorRole' => 'Chief Coordinator',
            'barangays' => $this->prepareBarangayCards($barangays),
            'facilityStatus' => $this->prepareFacilityStatus($facilityStatus),
            'staffOnDuty' => $this->prepareStaffList($staffOnDuty),
            'recentActivity' => $this->prepareActivityFeed($recentActivity),
            'alertCount' => $this->countUrgentAlerts($barangays),
        ];

        $this->render($data);
    }

    /* -----------------------------------------------------------
     * Private helpers — business rules / formatting live here
     * ----------------------------------------------------------- */

    /**
     * Decides card state (active/idle) and label text for each barangay tile.
     */
    private function prepareBarangayCards(array $barangays): array
    {
        return array_map(function ($b) {
            $count = (int) $b['active_dispatch_count'];

            return [
                'id' => $b['id'],
                'slug' => $b['slug'],
                'name' => $b['name'],
                'count' => $count,
                'isActive' => $count > 0,
                'statusLabel' => $count > 0
                    ? sprintf('%d active %s', $count, $count === 1 ? 'dispatch' : 'dispatches')
                    : 'No active dispatch',
            ];
        }, $barangays);
    }

    /**
     * Formats facility metrics into percentage bars + display values.
     */
    private function prepareFacilityStatus(array $rows): array
    {
        $formatted = [];

        foreach ($rows as $row) {
            $current = (float) $row['current_value'];
            $max = (float) $row['max_value'];

            // Calculate capacity percentage
            $percent = $max > 0
                ? round(($current / $max) * 100)
                : 0;

            // Keep percentage within valid progress-bar range
            $percent = max(0, min(100, $percent));

            // Determine capacity color class
            if ($percent >= 80) {
                $capacityLevel = 'high';
            } elseif ($percent >= 50) {
                $capacityLevel = 'medium';
            } else {
                $capacityLevel = 'low';
            }

            $formatted[$row['metric_key']] = [
                'label' => $this->metricLabel($row['metric_key']),

                'display' => $max > 0
                    ? sprintf('%d/%d', $current, $max)
                    : ($row['status_label'] ?? ''),

                'percent' => $percent,

                // Color is decided by Controller
                'capacityClass' => 'incomingdispatch-metric-bar-fill--' . $capacityLevel,

                'statusText' => $row['status_label'] ?? null,
            ];
        }

        return $formatted;
    }

    private function metricLabel(string $key): string
    {
        $labels = [
            'bed_capacity' => 'Bed Capacity',
            'ambulance_bay' => 'Ambulance Bay',
            'admitting_desk' => 'Admitting Desk',
            'records_queue' => 'Records Queue',
        ];

        return $labels[$key] ?? ucwords(str_replace('_', ' ', $key));
    }

    /**
     * Builds initials + availability dot state for each staff member.
     */
    private function prepareStaffList(array $staff): array
    {
        return array_map(function ($s) {
            return [
                'initials' => $this->initialsFromName($s['full_name']),
                'name' => $s['full_name'],
                'department' => $s['department'],
                'isAvailable' => (bool) $s['is_available'],
            ];
        }, $staff);
    }

    private function initialsFromName(string $name): string
    {
        $parts = preg_split('/\s+/', trim($name));
        $letters = array_map(fn($p) => mb_strtoupper(mb_substr($p, 0, 1)), $parts);
        return implode('', array_slice($letters, 0, 2));
    }

    /**
     * Formats activity log rows into a "x minutes ago" display string.
     */
    private function prepareActivityFeed(array $rows): array
    {
        return array_map(function ($row) {
            return [
                'iconType' => $row['icon_type'],
                'message' => $row['message'],
                'timeAgo' => $row['minutes_ago'] . ' minutes ago',
            ];
        }, $rows);
    }

    /**
     * Counts barangays that currently have at least one active dispatch —
     * drives the notification-bell badge in the header.
     */
    private function countUrgentAlerts(array $barangays): int
    {
        return count(array_filter($barangays, fn($b) => (int) $b['active_dispatch_count'] > 0));
    }

    /**
     * Extracts $data into scope and includes dispatchView.php.
     */
    private function render(array $data = []): void
    {
        extract($data);
        require_once __DIR__ . '/../views/dispatchView.php';
    }
}

/**
 * Bootstrap — runs the page directly from this file so no separate
 * index.php/router is needed. Visit dispatchController.php in the
 * browser (or `php -S localhost:8000` and open it) to see the page.
 */
(new dispatchController())->index();