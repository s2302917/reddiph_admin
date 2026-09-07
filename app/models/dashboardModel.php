<?php
/**
 * DashboardModel
 *
 * Dashboard-only data access. The model attempts to use the application's
 * Database::connection() when available, but keeps safe demo fallbacks so
 * the dashboard can render while the dashboard tables/API are being wired.
 */

class DashboardModel
{
    private ?PDO $db = null;

    public function __construct()
    {
        $config = dirname(__DIR__, 2) . '/config/config.php';
        $rootConfig = dirname(__DIR__, 2) . '/config.php';

        if (class_exists('Database')) {
            try {
                $this->db = Database::connection();
            } catch (Throwable $e) {
                $this->db = null;
            }
        } else {
            if (is_file($config)) {
                require_once $config;
            } elseif (is_file($rootConfig)) {
                require_once $rootConfig;
            }

            if (class_exists('Database')) {
                try {
                    $this->db = Database::connection();
                } catch (Throwable $e) {
                    $this->db = null;
                }
            }
        }
    }

    public function getMetrics(): array
    {
        // Keep this dashboard self-contained until the final dashboard
        // database schema is connected. Replace this block with queries
        // against the project's actual alert/ambulance/facility tables.
        return [
            'activeAlerts' => 12,
            'newAlerts' => 2,
            'availableAmbulances' => 48,
            'totalAmbulances' => 85,
            'avgResponseTime' => 8.4,
            'hospitalCapacity' => 72,
        ];
    }

    public function getRecentAlerts(): array
    {
        return [
            [
                'status' => 'Critical',
                'incidentType' => 'Cardiac Arrest (Unit 402)',
                'location' => 'Central Mall, 4th Floor',
                'minutesAgo' => 2,
            ],
            [
                'status' => 'Dispatched',
                'incidentType' => 'Multi-vehicle Collision',
                'location' => 'Highway 12 - Exit 4B',
                'minutesAgo' => 15,
            ],
            [
                'status' => 'Stable',
                'incidentType' => 'Home Fall - Elderly',
                'location' => '22 Oakwood Drive',
                'minutesAgo' => 24,
            ],
            [
                'status' => 'Critical',
                'incidentType' => 'Severe Respiratory Distress',
                'location' => 'North District Terminal',
                'minutesAgo' => 28,
            ],
        ];
    }

    public function getHospitalNetwork(): array
    {
        return [
            [
                'name' => 'Bago City Hospital',
                'distance' => '1.2km',
                'availableBeds' => 14,
                'totalBeds' => 120,
                'specialties' => ['LEVEL 1 TRAUMA', 'ICU', 'BURN UNIT'],
            ],
            [
                'name' => 'Valladolid District hospital',
                'distance' => '4.8km',
                'availableBeds' => 42,
                'totalBeds' => 200,
                'specialties' => ['CARDIAC CARE', 'STROKE CENTER'],
            ],
            [
                'name' => 'Corazon Locsin Hospital',
                'distance' => '6.1km',
                'availableBeds' => 12,
                'totalBeds' => 80,
                'specialties' => ['PEDIATRICS', 'NICU'],
            ],
        ];
    }

    public function getResponseTimeData(): array
    {
        return [
            'labels' => ['00:00', '04:00', '08:00', '12:00', '16:00', '20:00', '24:00'],
            'values' => [12, 10, 15, 22, 18, 14, 11],
        ];
    }
}
