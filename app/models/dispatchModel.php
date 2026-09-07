<?php
/**
 * dispatchModel.php
 *
 * MODEL — in this UI-only build, this stands in for the database layer.
 * Every method returns the exact same shape a real DB query would
 * (arrays of rows), just hardcoded here instead of via PDO. Swap the
 * method bodies for real SQL later without touching the Controller or View.
 */

class dispatchModel
{
    /**
     * All barangays with their live active-dispatch counts.
     * Matches: barangays(id, name, slug, active_dispatch_count, sort_order)
     */
    public function getBarangays(): array
    {
        return [
            ['id' => 1,  'name' => 'Batuan',       'slug' => 'batuan',       'active_dispatch_count' => 2],
            ['id' => 2,  'name' => 'Brgy 3',        'slug' => 'brgy-3',       'active_dispatch_count' => 1],
            ['id' => 3,  'name' => 'Brgy 1',        'slug' => 'brgy-1',       'active_dispatch_count' => 0],
            ['id' => 4,  'name' => 'Brgy 2',        'slug' => 'brgy-2',       'active_dispatch_count' => 0],
            ['id' => 5,  'name' => 'Ara-al',        'slug' => 'ara-al',       'active_dispatch_count' => 0],
            ['id' => 6,  'name' => 'Ayungon',       'slug' => 'ayungon',      'active_dispatch_count' => 0],
            ['id' => 7,  'name' => 'Balabag',       'slug' => 'balabag',      'active_dispatch_count' => 0],
            ['id' => 8,  'name' => 'Cubay',         'slug' => 'cubay',        'active_dispatch_count' => 0],
            ['id' => 9,  'name' => 'Haguimit',      'slug' => 'haguimit',     'active_dispatch_count' => 0],
            ['id' => 10, 'name' => 'La Granja',     'slug' => 'la-granja',    'active_dispatch_count' => 0],
            ['id' => 11, 'name' => 'Nagsai',        'slug' => 'nagsai',       'active_dispatch_count' => 0],
            ['id' => 12, 'name' => 'Barangay RSB',  'slug' => 'barangay-rsb', 'active_dispatch_count' => 0],
            ['id' => 13, 'name' => 'San Miguel',    'slug' => 'san-miguel',   'active_dispatch_count' => 0],
            ['id' => 14, 'name' => 'Yubo',          'slug' => 'yubo',         'active_dispatch_count' => 0],
        ];
    }

    /**
     * Facility status widgets: bed capacity, ambulance bay, admitting desk, records queue.
     * Matches: facility_status(metric_key, current_value, max_value, status_label)
     */
    public function getFacilityStatus(): array
    {
        return [
            ['metric_key' => 'bed_capacity',   'current_value' => 72, 'max_value' => 100, 'status_label' => null],
            ['metric_key' => 'ambulance_bay',  'current_value' => 2,  'max_value' => 5,   'status_label' => null],
            ['metric_key' => 'admitting_desk', 'current_value' => 0,  'max_value' => 0,   'status_label' => 'Open'],
            ['metric_key' => 'records_queue',  'current_value' => 0,  'max_value' => 0,   'status_label' => '3 pending'],
        ];
    }

    /**
     * Staff currently on duty, with an availability flag for the status dot.
     * Matches: staff(full_name, department, is_available)
     */
    public function getStaffOnDuty(): array
    {
        return [
            ['id' => 1, 'full_name' => 'Dr. Maria Santos',   'department' => 'Cardiology',          'is_available' => 1],
            ['id' => 2, 'full_name' => 'Dr. James Dela Cruz','department' => 'Emergency Medicine',  'is_available' => 1],
            ['id' => 3, 'full_name' => 'Nurse R. Cruz',      'department' => 'ER Ward',              'is_available' => 1],
            ['id' => 4, 'full_name' => 'Nurse A. Tan',       'department' => 'General Ward',         'is_available' => 0],
        ];
    }

    /**
     * Most recent activity log entries for the "Recent Activity" panel.
     * Matches: activity_log(icon_type, message, created_at)
     */
    public function getRecentActivity(int $limit = 5): array
    {
        $rows = [
            ['icon_type' => 'notify',   'message' => 'Doctor & nurses notified — Batuan cardiac case', 'minutes_ago' => 2],
            ['icon_type' => 'check',    'message' => 'Bed B-204 marked ready — Taculing case',          'minutes_ago' => 18],
            ['icon_type' => 'dispatch', 'message' => 'New dispatch received — Barangay III',            'minutes_ago' => 24],
        ];

        return array_slice($rows, 0, $limit);
    }
}