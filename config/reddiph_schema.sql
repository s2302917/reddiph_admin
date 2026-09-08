-- ============================================================
-- ReddiPH / PulseAlert — Emergency Alerts Module
-- Added to existing reddiph_db database
--
-- SAFE:
-- - Does NOT CREATE DATABASE
-- - Does NOT DROP DATABASE
-- - Does NOT DROP TABLES
-- - Does NOT DELETE existing data
-- - Creates incidents only if it does not already exist
-- - Sample incidents are inserted only if they do not already exist
-- ============================================================

CREATE TABLE IF NOT EXISTS incidents (
    incident_id       INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    incident_type     VARCHAR(120) NOT NULL,

    status            ENUM(
        'critical',
        'dispatched',
        'stable',
        'resolved'
    ) NOT NULL DEFAULT 'critical',

    location          VARCHAR(160) NOT NULL,

    patient_gender    ENUM(
        'male',
        'female',
        'other',
        'unknown'
    ) DEFAULT 'unknown',

    patient_age       INT NULL,

    eta_minutes       INT NULL,

    responder_name    VARCHAR(120) NULL,

    hospital_name     VARCHAR(160)
                      NOT NULL
                      DEFAULT 'La Carlota District Hospital',

    reported_at       DATETIME NOT NULL
                      DEFAULT CURRENT_TIMESTAMP,

    resolved_at       DATETIME NULL,

    notes             VARCHAR(255) NULL,

    created_at        TIMESTAMP NOT NULL
                      DEFAULT CURRENT_TIMESTAMP,

    updated_at        TIMESTAMP NOT NULL
                      DEFAULT CURRENT_TIMESTAMP
                      ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_incidents_status (status),
    INDEX idx_incidents_location (location),
    INDEX idx_incidents_reported_at (reported_at),
    INDEX idx_incidents_hospital (hospital_name)
    
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- SAMPLE / DEMO INCIDENT DATA
-- Only inserts a record if the same incident does not already
-- exist. This prevents duplicate seed data on re-import.
-- ============================================================

INSERT INTO incidents
(
    incident_type,
    status,
    location,
    patient_gender,
    patient_age,
    eta_minutes,
    responder_name,
    reported_at
)
SELECT
    'Cardiac Arrest (Unit 402)',
    'critical',
    'Central Mall, 4th Floor',
    'male',
    58,
    4,
    NULL,
    NOW() - INTERVAL 2 MINUTE
WHERE NOT EXISTS (
    SELECT 1
    FROM incidents
    WHERE incident_type = 'Cardiac Arrest (Unit 402)'
      AND location = 'Central Mall, 4th Floor'
);


INSERT INTO incidents
(
    incident_type,
    status,
    location,
    patient_gender,
    patient_age,
    eta_minutes,
    responder_name,
    reported_at
)
SELECT
    'Severe Respiratory Distress',
    'critical',
    'North District Terminal',
    'female',
    41,
    9,
    NULL,
    NOW() - INTERVAL 28 MINUTE
WHERE NOT EXISTS (
    SELECT 1
    FROM incidents
    WHERE incident_type = 'Severe Respiratory Distress'
      AND location = 'North District Terminal'
);


INSERT INTO incidents
(
    incident_type,
    status,
    location,
    patient_gender,
    patient_age,
    eta_minutes,
    responder_name,
    reported_at
)
SELECT
    'Severe Allergic Reaction',
    'critical',
    'Brgy. Taculing',
    'male',
    22,
    6,
    NULL,
    NOW() - INTERVAL 6 MINUTE
WHERE NOT EXISTS (
    SELECT 1
    FROM incidents
    WHERE incident_type = 'Severe Allergic Reaction'
      AND location = 'Brgy. Taculing'
);


INSERT INTO incidents
(
    incident_type,
    status,
    location,
    patient_gender,
    patient_age,
    eta_minutes,
    responder_name,
    reported_at
)
SELECT
    'Stab Wound — Assault',
    'critical',
    'Brgy. Mandalagan',
    'male',
    29,
    12,
    NULL,
    NOW() - INTERVAL 10 MINUTE
WHERE NOT EXISTS (
    SELECT 1
    FROM incidents
    WHERE incident_type = 'Stab Wound — Assault'
      AND location = 'Brgy. Mandalagan'
);


INSERT INTO incidents
(
    incident_type,
    status,
    location,
    patient_gender,
    patient_age,
    eta_minutes,
    responder_name,
    reported_at
)
SELECT
    'Multi-vehicle Collision',
    'dispatched',
    'Highway 12 - Exit 4B',
    NULL,
    NULL,
    7,
    'A. Reyes',
    NOW() - INTERVAL 2 MINUTE
WHERE NOT EXISTS (
    SELECT 1
    FROM incidents
    WHERE incident_type = 'Multi-vehicle Collision'
      AND location = 'Highway 12 - Exit 4B'
);


INSERT INTO incidents
(
    incident_type,
    status,
    location,
    patient_gender,
    patient_age,
    eta_minutes,
    responder_name,
    reported_at
)
SELECT
    'Motorcycle Accident',
    'dispatched',
    'Lacson St. corner 6th',
    NULL,
    NULL,
    3,
    'M. Torres',
    NOW() - INTERVAL 2 MINUTE
WHERE NOT EXISTS (
    SELECT 1
    FROM incidents
    WHERE incident_type = 'Motorcycle Accident'
      AND location = 'Lacson St. corner 6th'
);


INSERT INTO incidents
(
    incident_type,
    status,
    location,
    patient_gender,
    patient_age,
    eta_minutes,
    responder_name,
    reported_at
)
SELECT
    'Workplace Injury',
    'dispatched',
    'Robinsons Bacolod',
    NULL,
    NULL,
    14,
    'C. Bautista',
    NOW() - INTERVAL 2 MINUTE
WHERE NOT EXISTS (
    SELECT 1
    FROM incidents
    WHERE incident_type = 'Workplace Injury'
      AND location = 'Robinsons Bacolod'
);


INSERT INTO incidents
(
    incident_type,
    status,
    location,
    patient_gender,
    patient_age,
    eta_minutes,
    responder_name,
    reported_at
)
SELECT
    'Pregnancy Complication',
    'dispatched',
    'Brgy. Alijis',
    NULL,
    NULL,
    5,
    'J. Cruz',
    NOW() - INTERVAL 2 MINUTE
WHERE NOT EXISTS (
    SELECT 1
    FROM incidents
    WHERE incident_type = 'Pregnancy Complication'
      AND location = 'Brgy. Alijis'
);


INSERT INTO incidents
(
    incident_type,
    status,
    location,
    patient_gender,
    patient_age,
    eta_minutes,
    responder_name,
    reported_at
)
SELECT
    'Seizure Episode',
    'dispatched',
    'Brgy. Villamonte',
    NULL,
    NULL,
    10,
    'R. Villanueva',
    NOW() - INTERVAL 2 MINUTE
WHERE NOT EXISTS (
    SELECT 1
    FROM incidents
    WHERE incident_type = 'Seizure Episode'
      AND location = 'Brgy. Villamonte'
);


INSERT INTO incidents
(
    incident_type,
    status,
    location,
    patient_gender,
    patient_age,
    eta_minutes,
    responder_name,
    reported_at
)
SELECT
    'Home Fall — Elderly',
    'stable',
    '22 Oakwood Drive',
    'female',
    76,
    NULL,
    'M. Santos',
    NOW() - INTERVAL 24 MINUTE
WHERE NOT EXISTS (
    SELECT 1
    FROM incidents
    WHERE incident_type = 'Home Fall — Elderly'
      AND location = '22 Oakwood Drive'
);


INSERT INTO incidents
(
    incident_type,
    status,
    location,
    patient_gender,
    patient_age,
    eta_minutes,
    responder_name,
    reported_at
)
SELECT
    'Minor Laceration',
    'stable',
    'Brgy. Estefania',
    'male',
    19,
    NULL,
    'P. Aguirre',
    NOW() - INTERVAL 31 MINUTE
WHERE NOT EXISTS (
    SELECT 1
    FROM incidents
    WHERE incident_type = 'Minor Laceration'
      AND location = 'Brgy. Estefania'
);


INSERT INTO incidents
(
    incident_type,
    status,
    location,
    patient_gender,
    patient_age,
    eta_minutes,
    responder_name,
    reported_at
)
SELECT
    'Mild Dehydration',
    'stable',
    'Brgy. Handumanan',
    'female',
    8,
    NULL,
    'C. Bautista',
    NOW() - INTERVAL 40 MINUTE
WHERE NOT EXISTS (
    SELECT 1
    FROM incidents
    WHERE incident_type = 'Mild Dehydration'
      AND location = 'Brgy. Handumanan'
);


-- ============================================================
-- END OF EMERGENCY ALERTS MODULE
-- ============================================================