<?php
/**
 * dashboardController.php
 *
 * CONTROLLER — Handles all business logic, data processing, and routing for the dashboard.
 * Pulls data from the Model, applies business rules and transformations,
 * then passes clean data to the View for rendering.
 */

require_once __DIR__ . '/../models/dashboardModel.php';

class DashboardController
{
    private DashboardModel $model;

    public function __construct()
    {
        $this->model = new DashboardModel();
    }

    /**
     * Main entry point — orchestrates the dashboard page render.
     */
    public function index(): void
    {
        $metrics = $this->model->getMetrics();
        $alerts = $this->model->getRecentAlerts(4);
        $hospitals = $this->model->getHospitalNetwork();
        $responseData = $this->model->getResponseTimeData();

        $data = [
            'pageTitle' => 'Dashboard',
            'hospitalName' => 'DSB District Hospital',
            'hospitalSubtitle' => 'Admitting & Coordination',
            'coordinatorName' => 'Charlotte M.',
            'coordinatorRole' => 'Chief Coordinator',
            'metrics' => $this->prepareMetrics($metrics),
            'alerts' => $this->prepareAlerts($alerts),
            'hospitals' => $this->prepareHospitalNetwork($hospitals),
            'responseChart' => $this->prepareResponseChart($responseData),
            'systemStatus' => 'All systems operational',
        ];

        $this->render($data);
    }

    /**
     * Prepares metrics for display (includes calculated percentages).
     */
    private function prepareMetrics(array $metrics): array
    {
        $ambulancePercent = $metrics['total_ambulances'] > 0
            ? round(($metrics['available_ambulances'] / $metrics['total_ambulances']) * 100)
            : 0;

        return [
            'active_alerts' => [
                'label' => 'Active Alerts',
                'value' => $metrics['active_alerts'],
                'badge' => '+' . $metrics['new_alerts'] . ' New',
                'badgeClass' => 'dashboard-metric-badge--alert',
            ],
            'available_ambulances' => [
                'label' => 'Available Ambulances',
                'value' => $metrics['available_ambulances'],
                'total' => $metrics['total_ambulances'],
                'display' => sprintf('%d/%d', $metrics['available_ambulances'], $metrics['total_ambulances']),
                'percent' => $ambulancePercent,
            ],
            'response_time' => [
                'label' => 'Avg Response Time',
                'value' => $metrics['avg_response_time'],
                'unit' => 'min',
            ],
            'hospital_capacity' => [
                'label' => 'Hospital Capacity',
                'value' => $metrics['hospital_capacity_percent'],
                'unit' => '%',
                'percent' => $metrics['hospital_capacity_percent'],
            ],
        ];
    }

    /**
     * Prepares alert rows with human-readable time and status styling.
     */
    private function prepareAlerts(array $alerts): array
    {
        return array_map(function ($alert) {
            $timestamp = strtotime($alert['created_at']);
            $minutesAgo = floor((time() - $timestamp) / 60);
            $timeText = $minutesAgo . ' minute' . ($minutesAgo !== 1 ? 's' : '') . ' ago';

            return [
                'id' => $alert['id'],
                'status' => $alert['status'],
                'statusClass' => $this->getStatusClass($alert['status']),
                'incidentType' => $alert['incident_type'],
                'location' => $alert['location'],
                'timeAgo' => $timeText,
            ];
        }, $alerts);
    }

    /**
     * Maps incident status to CSS class for styling.
     */
    private function getStatusClass(string $status): string
    {
        $map = [
            'Critical' => 'dashboard-alert-status--critical',
            'Dispatched' => 'dashboard-alert-status--dispatched',
            'Stable' => 'dashboard-alert-status--stable',
            'Resolved' => 'dashboard-alert-status--resolved',
        ];
        return $map[$status] ?? 'dashboard-alert-status--default';
    }

    /**
     * Prepares hospital network cards with capacity calculations.
     */
    private function prepareHospitalNetwork(array $hospitals): array
    {
        return array_map(function ($hospital) {
            $capacityPercent = $hospital['total_beds'] > 0
                ? round(($hospital['available_beds'] / $hospital['total_beds']) * 100)
                : 0;

            return [
                'id' => $hospital['id'],
                'name' => $hospital['name'],
                'distanceKm' => $hospital['distance_km'],
                'availableBeds' => $hospital['available_beds'],
                'totalBeds' => $hospital['total_beds'],
                'bedDisplay' => sprintf('%d/%d', $hospital['available_beds'], $hospital['total_beds']),
                'capacityPercent' => $capacityPercent,
                'specializations' => $hospital['specializations'],
                'specialList' => array_map('trim', explode(',', $hospital['specializations'])),
            ];
        }, $hospitals);
    }

    /**
     * Prepares response time chart data (converts to JSON for JS charting).
     */
    private function prepareResponseChart(array $data): string
    {
        $labels = array_map(fn($row) => $row['hour'], $data);
        $values = array_map(fn($row) => (float) $row['response_time_minutes'], $data);

        return json_encode([
            'labels' => $labels,
            'values' => $values,
        ]);
    }

    /**
     * Extracts data into scope and includes the View.
     */
    private function render(array $data = []): void
    {
        extract($data);
        require __DIR__ . '/../views/dashboardView.php';
    }
}

/**
 * Bootstrap — runs the dashboard directly when this file is accessed.
 * Visit dashboardController.php in the browser to see the dashboard.
 */
(new DashboardController())->index();
