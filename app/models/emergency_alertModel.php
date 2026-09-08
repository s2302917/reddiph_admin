<?php

class EmergencyAlertModel
{
    private ?PDO $db = null;

    public function __construct()
    {
        $this->connect();
    }

    private function connect(): void
    {
        $configFiles = [
            dirname(__DIR__, 2) . '/config/config.php',
            dirname(__DIR__, 2) . '/config.php',
        ];

        foreach ($configFiles as $configFile) {
            if (is_file($configFile)) {
                require_once $configFile;
                break;
            }
        }

        if (class_exists('Database')) {
            try {
                $this->db = Database::connection();
                return;
            } catch (Throwable $e) {
                error_log('[EmergencyAlertModel] Database connection failed: ' . $e->getMessage());
            }
        }

        if (
            defined('DB_HOST') &&
            defined('DB_NAME') &&
            defined('DB_USER') &&
            defined('DB_PASS')
        ) {
            try {
                $dsn = 'mysql:host=' . DB_HOST .
                       ';dbname=' . DB_NAME .
                       ';charset=utf8mb4';

                $this->db = new PDO(
                    $dsn,
                    DB_USER,
                    DB_PASS,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                    ]
                );
            } catch (Throwable $e) {
                error_log('[EmergencyAlertModel] PDO connection failed: ' . $e->getMessage());
            }
        }
    }

    /**
     * Get emergency alert summary directly from incidents table.
     */
    public function getSummary(): array
    {
        if (!$this->db) {
            return [
                'critical' => 0,
                'dispatched' => 0,
                'stable' => 0,
                'resolvedToday' => 0,
            ];
        }

        try {
            $sql = "
                SELECT
                    COALESCE(SUM(status = 'critical'), 0) AS critical,
                    COALESCE(SUM(status = 'dispatched'), 0) AS dispatched,
                    COALESCE(SUM(status = 'stable'), 0) AS stable,
                    (
                        SELECT COUNT(*)
                        FROM incidents
                        WHERE status = 'resolved'
                          AND resolved_at IS NOT NULL
                          AND DATE(resolved_at) = CURDATE()
                    ) AS resolvedToday
                FROM incidents
            ";

            $row = $this->db->query($sql)->fetch();

            return [
                'critical' => (int) ($row['critical'] ?? 0),
                'dispatched' => (int) ($row['dispatched'] ?? 0),
                'stable' => (int) ($row['stable'] ?? 0),
                'resolvedToday' => (int) ($row['resolvedToday'] ?? 0),
            ];
        } catch (Throwable $e) {
            error_log('[EmergencyAlertModel] getSummary: ' . $e->getMessage());

            return [
                'critical' => 0,
                'dispatched' => 0,
                'stable' => 0,
                'resolvedToday' => 0,
            ];
        }
    }

    /**
     * Get hospital name from incidents or fallback.
     */
    public function getHospitalName(): string
    {
        if ($this->db) {
            try {
                $stmt = $this->db->query("SELECT hospital_name FROM incidents WHERE hospital_name IS NOT NULL AND hospital_name != '' LIMIT 1");
                $val = $stmt->fetchColumn();
                if ($val) {
                    return (string) $val;
                }
            } catch (Throwable $e) {
                error_log('[EmergencyAlertModel] getHospitalName: ' . $e->getMessage());
            }
        }

        return 'La Carlota District Hospital';
    }

    /**
     * Get active incidents.
     */
    public function getAlerts(
        string $status = 'all',
        string $search = ''
    ): array {
        if (!$this->db) {
            return [];
        }

        try {
            $where = [
                "status IN ('critical', 'dispatched', 'stable')"
            ];

            $params = [];

            if ($status !== 'all') {
                $where[] = 'status = :status';
                $params['status'] = strtolower($status);
            }

            if ($search !== '') {
                $where[] = "
                    (
                        incident_type LIKE :search
                        OR location LIKE :search
                        OR responder_name LIKE :search
                        OR hospital_name LIKE :search
                        OR notes LIKE :search
                    )
                ";

                $params['search'] = '%' . $search . '%';
            }

            $sql = "
                SELECT
                    incident_id,
                    incident_type,
                    status,
                    location,
                    patient_gender,
                    patient_age,
                    eta_minutes,
                    responder_name,
                    hospital_name,
                    reported_at,
                    resolved_at,
                    notes,
                    created_at,
                    updated_at
                FROM incidents
                WHERE " . implode(' AND ', $where) . "
                ORDER BY
                    CASE status
                        WHEN 'critical' THEN 1
                        WHEN 'dispatched' THEN 2
                        WHEN 'stable' THEN 3
                        ELSE 4
                    END,
                    reported_at DESC
            ";

            $statement = $this->db->prepare($sql);
            $statement->execute($params);

            return $statement->fetchAll();

        } catch (Throwable $e) {
            error_log('[EmergencyAlertModel] getAlerts: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get one incident.
     */
    public function getAlertById(int $id): ?array
    {
        if (!$this->db) {
            return null;
        }

        try {
            $statement = $this->db->prepare("
                SELECT
                    incident_id,
                    incident_type,
                    status,
                    location,
                    patient_gender,
                    patient_age,
                    eta_minutes,
                    responder_name,
                    hospital_name,
                    reported_at,
                    resolved_at,
                    notes,
                    created_at,
                    updated_at
                FROM incidents
                WHERE incident_id = :id
                LIMIT 1
            ");

            $statement->execute([
                'id' => $id
            ]);

            $alert = $statement->fetch();

            return $alert ?: null;

        } catch (Throwable $e) {
            error_log('[EmergencyAlertModel] getAlertById: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * The supplied incidents schema does not contain
     * staff_notified/notified_at columns.
     *
     * Keep this safe until those fields are added.
     */
    public function notifyStaff(int $id): bool
    {
        if (!$this->db) {
            return false;
        }

        try {
            $statement = $this->db->prepare("
                UPDATE incidents
                SET notes = CASE
                    WHEN notes IS NULL OR notes = ''
                        THEN 'Staff notification requested.'
                    ELSE CONCAT(notes, ' Staff notification requested.')
                END
                WHERE incident_id = :id
                LIMIT 1
            ");

            $statement->execute([
                'id' => $id
            ]);

            return $statement->rowCount() > 0;

        } catch (Throwable $e) {
            error_log('[EmergencyAlertModel] notifyStaff: ' . $e->getMessage());
            return false;
        }
    }
}