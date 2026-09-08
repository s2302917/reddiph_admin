<?php
/**
 * emergency_alertController.php
 *
 * Controller for the Pulse Alert Emergency Alerts page.
 * Responsibilities:
 * - Receive/filter request parameters.
 * - Ask the model for database data.
 * - Prepare presentation data.
 * - Handle Notify Staff and View Details actions.
 *
 * Database access and SQL remain inside emergency_alertModel.php.
 */

require_once __DIR__ . '/../models/emergency_alertModel.php';

class EmergencyAlertController
{
    private EmergencyAlertModel $model;

    public function __construct()
    {
        $this->model = new EmergencyAlertModel();
    }

    public function index(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $summary = $this->model->getSummary();
        $rawAlerts = $this->model->getAlerts('all', '');
        $hospitalName = $this->model->getHospitalName();
        $pageTitle = $hospitalName . ' : Admitting & Coordination';

        $coordinatorName = $_SESSION['user_name'] ?? 'Charlotte M.';
        $coordinatorRole = $_SESSION['user_role'] ?? 'Chief Coordinator';
        $alertCount = (int) ($summary['critical'] ?? 0);

        $alerts = array_map([$this, 'prepareAlert'], $rawAlerts);

        $initialData = [
            'success' => true,
            'pageTitle' => $pageTitle,
            'coordinatorName' => $coordinatorName,
            'coordinatorRole' => $coordinatorRole,
            'summary' => $summary,
            'alerts' => $alerts,
        ];

        require __DIR__ . '/../views/emergency_alertView.php';
    }

    public function data(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $status = $this->getRequestedStatus();
        $search = trim((string) ($_GET['search'] ?? ''));

        $summary = $this->model->getSummary();
        $rawAlerts = $this->model->getAlerts($status, $search);
        $hospitalName = $this->model->getHospitalName();

        $coordinatorName = $_SESSION['user_name'] ?? 'Charlotte M.';
        $coordinatorRole = $_SESSION['user_role'] ?? 'Chief Coordinator';

        $this->jsonResponse([
            'success' => true,
            'pageTitle' => $hospitalName . ' : Admitting & Coordination',
            'coordinatorName' => $coordinatorName,
            'coordinatorRole' => $coordinatorRole,
            'summary' => [
                'critical' => (int) ($summary['critical'] ?? 0),
                'dispatched' => (int) ($summary['dispatched'] ?? 0),
                'stable' => (int) ($summary['stable'] ?? 0),
                'resolvedToday' => (int) ($summary['resolvedToday'] ?? 0),
            ],
            'alerts' => array_map(
                [$this, 'prepareAlert'],
                $rawAlerts
            ),
        ]);
    }

    public function notifyStaff(): void
    {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid alert ID.'], 422);
        }

        try {
            $updated = $this->model->notifyStaff((int) $id);

            $this->jsonResponse([
                'success' => $updated,
                'message' => $updated
                    ? 'Staff notification sent successfully.'
                    : 'The alert could not be updated.',
            ], $updated ? 200 : 404);
        } catch (Throwable $e) {
            error_log('[EmergencyAlertController] notifyStaff: ' . $e->getMessage());
            $this->jsonResponse([
                'success' => false,
                'message' => 'Unable to send the staff notification.',
            ], 500);
        }
    }

    public function details(): void
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid alert ID.'], 422);
        }

        $alert = $this->model->getAlertById((int) $id);

        if (!$alert) {
            $this->jsonResponse(['success' => false, 'message' => 'Alert not found.'], 404);
        }

        $this->jsonResponse([
            'success' => true,
            'alert' => $this->prepareAlert($alert),
        ]);
    }

    private function getRequestedStatus(): string
    {
        $status = strtolower(trim((string) ($_GET['status'] ?? 'all')));

        return in_array($status, ['all', 'critical', 'dispatched', 'stable'], true)
            ? $status
            : 'all';
    }

   private function prepareAlert(array $alert): array
{
    $status = strtolower((string) ($alert['status'] ?? 'stable'));

    $gender = strtolower((string) ($alert['patient_gender'] ?? ''));

    $genderLabel = match ($gender) {
        'male' => 'MALE',
        'female' => 'FEMALE',
        'other' => 'OTHER',
        default => '',
    };

    $age = $alert['patient_age'] !== null
        ? (int) $alert['patient_age']
        : null;

    $eta = $alert['eta_minutes'] !== null
        ? (int) $alert['eta_minutes']
        : null;

    $patientInfo = '';

    if ($genderLabel !== '' && $age !== null) {
        $patientInfo = $genderLabel . ', ' . $age;

        if ($eta !== null) {
            $patientInfo .= ' · ETA ' . $eta . ' MINS';
        } elseif ($status === 'stable') {
            $patientInfo .= ' · VITALS STABLE';
        }
    }

    return [
        'id' => (int) ($alert['incident_id'] ?? 0),

        'status' => ucfirst($status),

        'incidentType' => (string) (
            $alert['incident_type'] ?? 'Unknown Incident'
        ),

        'patientInfo' => $patientInfo,

        'location' => (string) (
            $alert['location'] ?? 'Unknown Location'
        ),

        'hospital' => (string) (
            $alert['hospital_name'] ?? ''
        ),

        'timeAgo' => $this->formatTimeAgo(
            $alert['reported_at'] ?? null
        ),

        'responder' => (string) (
            $alert['responder_name'] ?? ''
        ),

        'eta' => $eta !== null
            ? $eta . ' mins'
            : '',

        'notes' => (string) (
            $alert['notes'] ?? ''
        ),

        'reportedAt' => (string) (
            $alert['reported_at'] ?? ''
        ),

        'resolvedAt' => $alert['resolved_at'] ?? null,

        'action' => $status === 'critical'
            ? 'Notify Staff'
            : 'View Details',
    ];
}

    private function formatTimeAgo(mixed $value): string
    {
        if (is_numeric($value)) {
            $minutes = max(0, (int) $value);
        } elseif (is_string($value) && $value !== '') {
            $timestamp = strtotime($value);
            $minutes = $timestamp ? max(0, (int) floor((time() - $timestamp) / 60)) : 0;
        } else {
            $minutes = 0;
        }

        if ($minutes < 1) {
            return 'Just now';
        }

        if ($minutes < 60) {
            return $minutes . 'm ago';
        }

        $hours = (int) floor($minutes / 60);

        if ($hours < 24) {
            return $hours . 'h ago';
        }

        return (int) floor($hours / 24) . 'd ago';
    }

    private function jsonResponse(array $payload, int $statusCode = 200): never
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload, JSON_UNESCAPED_SLASHES);
        exit;
    }
}

$controller = new EmergencyAlertController();

$action = strtolower(trim((string) ($_GET['action'] ?? 'index')));

switch ($action) {
    case 'data':
        $controller->data();
        break;

    case 'notify':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            header('Allow: POST');
            exit('Method Not Allowed');
        }
        $controller->notifyStaff();
        break;

    case 'details':
        $controller->details();
        break;

    default:
        $controller->index();
        break;
}
