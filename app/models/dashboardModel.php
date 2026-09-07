<?php
/**
 * dashboardModel.php
 *
 * MODEL — Handles all database connections and data queries for the dashboard.
 * Returns structured arrays that the Controller can process.
 * Includes fallback demo data for UI-only prototyping when DB is unavailable.
 */

class DashboardModel
{
    private $connection = null;

    public function __construct()
    {
        $this->connection = $this->connect();
    }

    /**
     * Attempt to establish a PDO connection to the database.
     * Returns null if connection fails (UI can still render with fallback data).
     */
    private function connect()
    {
        try {
            $pdo = new PDO(
                'mysql:host=127.0.0.1;dbname=reddiph_system',
                'root',
                ''
            );
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_THROW);
            return $pdo;
        } catch (PDOException $e) {
            error_log('Dashboard DB Connection failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Fetch dashboard metrics: active alerts, ambulances, response time, hospital capacity.
     */
    public function getMetrics(): array
    {
        if ($this->connection) {
            try {
                $stmt = $this->connection->query('SELECT * FROM dashboard_metrics LIMIT 1');
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($result) {
                    return $this->formatMetrics($result);
                }
            } catch (PDOException $e) {
                error_log('Error fetching metrics: ' . $e->getMessage());
            }
        }
        return $this->fallbackMetrics();
    }

    /**
     * Fetch recent alerts/incidents for the live feed.
     */
    public function getRecentAlerts(int $limit = 4): array
    {
        if ($this->connection) {
            try {
                $stmt = $this->connection->prepare('
                    SELECT id, status, incident_type, location, created_at 
                    FROM incidents 
                    ORDER BY created_at DESC 
                    LIMIT :limit
                ');
                $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                error_log('Error fetching alerts: ' . $e->getMessage());
            }
        }
        return $this->fallbackAlerts($limit);
    }

    /**
     * Fetch hospital network status (nearby hospitals with bed availability).
     */
    public function getHospitalNetwork(): array
    {
        if ($this->connection) {
            try {
                $stmt = $this->connection->query('
                    SELECT id, name, distance_km, available_beds, total_beds, specializations 
                    FROM hospitals 
                    ORDER BY distance_km ASC 
                    LIMIT 3
                ');
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                error_log('Error fetching hospital network: ' . $e->getMessage());
            }
        }
        return $this->fallbackHospitalNetwork();
    }

    /**
     * Fetch response time data for the performance chart.
     */
    public function getResponseTimeData(): array
    {
        if ($this->connection) {
            try {
                $stmt = $this->connection->query('
                    SELECT hour, response_time_minutes 
                    FROM response_metrics 
                    WHERE DATE(recorded_at) = CURDATE() 
                    ORDER BY hour ASC
                ');
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                error_log('Error fetching response data: ' . $e->getMessage());
            }
        }
        return $this->fallbackResponseTimeData();
    }

    /**
     * Format metrics into a consistent structure.
     */
    private function formatMetrics(array $data): array
    {
        return [
            'active_alerts' => (int) ($data['active_alerts'] ?? 12),
            'new_alerts' => (int) ($data['new_alerts'] ?? 2),
            'available_ambulances' => (int) ($data['available_ambulances'] ?? 48),
            'total_ambulances' => (int) ($data['total_ambulances'] ?? 85),
            'avg_response_time' => (float) ($data['avg_response_time'] ?? 8.4),
            'hospital_capacity_percent' => (int) ($data['capacity_percent'] ?? 72),
        ];
    }

    /* ==========================================
       FALLBACK DATA — For UI-only prototyping
       ========================================== */

    private function fallbackMetrics(): array
    {
        return [
            'active_alerts' => 12,
            'new_alerts' => 2,
            'available_ambulances' => 48,
            'total_ambulances' => 85,
            'avg_response_time' => 8.4,
            'hospital_capacity_percent' => 72,
        ];
    }

    private function fallbackAlerts(int $limit): array
    {
        $alerts = [
            [
                'id' => 1,
                'status' => 'Critical',
                'incident_type' => 'Cardiac Arrest (Unit 402)',
                'location' => 'Central Mall, 4th Floor',
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 minutes')),
            ],
            [
                'id' => 2,
                'status' => 'Dispatched',
                'incident_type' => 'Multi-vehicle Collision',
                'location' => 'Highway 12 - Exit 4B',
                'created_at' => date('Y-m-d H:i:s', strtotime('-15 minutes')),
            ],
            [
                'id' => 3,
                'status' => 'Stable',
                'incident_type' => 'Home Fall - Elderly',
                'location' => '22 Oakwood Drive',
                'created_at' => date('Y-m-d H:i:s', strtotime('-24 minutes')),
            ],
            [
                'id' => 4,
                'status' => 'Critical',
                'incident_type' => 'Severe Respiratory Distress',
                'location' => 'North District Terminal',
                'created_at' => date('Y-m-d H:i:s', strtotime('-28 minutes')),
            ],
        ];
        return array_slice($alerts, 0, $limit);
    }

    private function fallbackHospitalNetwork(): array
    {
        return [
            [
                'id' => 1,
                'name' => 'Bago City Hospital',
                'distance_km' => 1.2,
                'available_beds' => 14,
                'total_beds' => 120,
                'specializations' => 'Level 1 Trauma, ICU, Burn Unit',
            ],
            [
                'id' => 2,
                'name' => 'Valladolid District Hospital',
                'distance_km' => 4.8,
                'available_beds' => 42,
                'total_beds' => 200,
                'specializations' => 'Cardiac Care, Stroke Center',
            ],
            [
                'id' => 3,
                'name' => 'Corazon Lossin Hospital',
                'distance_km' => 6.1,
                'available_beds' => 12,
                'total_beds' => 80,
                'specializations' => 'Pediatrics, NICU',
            ],
        ];
    }

    private function fallbackResponseTimeData(): array
    {
        return [
            ['hour' => '06:00', 'response_time_minutes' => 8],
            ['hour' => '08:00', 'response_time_minutes' => 12],
            ['hour' => '10:00', 'response_time_minutes' => 20],
            ['hour' => '12:00', 'response_time_minutes' => 18],
            ['hour' => '14:00', 'response_time_minutes' => 16],
            ['hour' => '16:00', 'response_time_minutes' => 14],
            ['hour' => '18:00', 'response_time_minutes' => 10],
            ['hour' => '20:00', 'response_time_minutes' => 11],
        ];
    }
}
