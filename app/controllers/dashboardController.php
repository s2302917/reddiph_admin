<?php
/**
 * dashboardController.php
 *
 * Controller for the Pulse Alert admin dashboard.
 */

require_once __DIR__ . '/../models/dashboardModel.php';

class DashboardController
{
    private DashboardModel $model;

    public function __construct()
    {
        $this->model = new DashboardModel();
    }

    public function index(): void
    {
        $metrics = $this->prepareMetrics($this->model->getMetrics());
        $alerts = $this->prepareAlerts($this->model->getRecentAlerts());
        $hospitalNetwork = $this->prepareHospitalNetwork($this->model->getHospitalNetwork());
        $responseChart = $this->prepareResponseChart($this->model->getResponseTimeData());

        $hospitalName = 'Dashboard';
        $pageTitle = 'La Carlota District Hospital : Admitting & Coordination';
        $coordinatorName = 'Charlotte M.';
        $coordinatorRole = 'Chief Coordinator';
        $alertCount = (int) ($metrics['activeAlerts']['value'] ?? 0);

        require __DIR__ . '/../views/dashboardView.php';
    }

    private function prepareMetrics(array $metrics): array
    {
        $available = max(0, (int) ($metrics['availableAmbulances'] ?? 0));
        $total = max(0, (int) ($metrics['totalAmbulances'] ?? 0));
        $capacity = max(0, min(100, (float) ($metrics['hospitalCapacity'] ?? 0)));

        return [
            'activeAlerts' => [
                'label' => 'Active Alerts',
                'value' => (int) ($metrics['activeAlerts'] ?? 0),
                'suffix' => '',
                'new' => (int) ($metrics['newAlerts'] ?? 0),
            ],
            'availableAmbulances' => [
                'label' => 'Available Ambulances',
                'value' => $available,
                'suffix' => '/' . $total,
                'new' => null,
            ],
            'avgResponseTime' => [
                'label' => 'Avg Response Time',
                'value' => number_format((float) ($metrics['avgResponseTime'] ?? 0), 1),
                'suffix' => 'min',
                'new' => null,
            ],
            'hospitalCapacity' => [
                'label' => 'Hospital Capacity',
                'value' => number_format($capacity, 0) . '%',
                'suffix' => '',
                'new' => null,
            ],
        ];
    }

    private function prepareAlerts(array $alerts): array
    {
        return array_map(function (array $alert): array {
            $minutes = max(0, (int) ($alert['minutesAgo'] ?? 0));

            return [
                'status' => (string) ($alert['status'] ?? 'Stable'),
                'statusClass' => $this->getStatusClass((string) ($alert['status'] ?? 'Stable')),
                'incidentType' => (string) ($alert['incidentType'] ?? 'Unknown Incident'),
                'location' => (string) ($alert['location'] ?? 'Unknown Location'),
                'timeAgo' => $this->getTimeAgo($minutes),
            ];
        }, $alerts);
    }

    private function prepareHospitalNetwork(array $hospitals): array
    {
        return array_map(function (array $hospital): array {
            $available = max(0, (int) ($hospital['availableBeds'] ?? 0));
            $total = max(1, (int) ($hospital['totalBeds'] ?? 1));
            $usedPercent = max(0, min(100, (($total - $available) / $total) * 100));

            return [
                'name' => (string) ($hospital['name'] ?? 'Hospital'),
                'distance' => (string) ($hospital['distance'] ?? ''),
                'availableBeds' => $available,
                'totalBeds' => $total,
                'availabilityPercent' => round(($available / $total) * 100, 1),
                'usedPercent' => round($usedPercent, 1),
                'specialties' => array_values($hospital['specialties'] ?? []),
            ];
        }, $hospitals);
    }

    private function prepareResponseChart(array $data): array
    {
        $labels = array_values($data['labels'] ?? []);
        $values = array_map('floatval', array_values($data['values'] ?? []));

        return [
            'labels' => $labels,
            'values' => $values,
            'max' => max(25, (int) ceil((max($values ?: [0]) + 3) / 5) * 5),
        ];
    }

    private function getStatusClass(string $status): string
    {
        return match (strtolower(trim($status))) {
            'critical' => 'dashboard-status--critical',
            'dispatched' => 'dashboard-status--dispatched',
            'stable' => 'dashboard-status--stable',
            default => 'dashboard-status--default',
        };
    }

    private function getTimeAgo(int $minutes): string
    {
        if ($minutes < 1) {
            return 'Just now';
        }

        return $minutes . 'm ago';
    }
}

// Direct access: /app/controllers/dashboardController.php
if (basename($_SERVER['SCRIPT_FILENAME'] ?? '') === basename(__FILE__)) {
    (new DashboardController())->index();
}
