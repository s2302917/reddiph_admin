-- ============================================================
-- reddiph_admin — Database Schema
-- Target: MySQL 8.0+
-- Engine: InnoDB, utf8mb4
--
-- IMPORTANT:
-- This script is intended to be run inside the existing
-- Hostinger database: reddiph
--
-- It does NOT:
--   - CREATE DATABASE
--   - DROP DATABASE
--   - DROP TABLE
--   - DELETE existing data
--   - ALTER existing tables
--
-- Existing tables will be skipped because IF NOT EXISTS
-- is used.
-- ============================================================


-- ============================================================
-- PART 1 — Auth (user_type)
-- ============================================================

-- Normalized hospital record
CREATE TABLE IF NOT EXISTS hospitals (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(150) NOT NULL,
    address         VARCHAR(255),
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;


-- Merged replacement for hospital_admins / doctors / nurses
CREATE TABLE IF NOT EXISTS user_type (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    role            ENUM('hospital_admin','doctor','nurse') NOT NULL,
    full_name       VARCHAR(150) NOT NULL,
    hospital_name   VARCHAR(150) NOT NULL,
    hospital_id     INT UNSIGNED NULL,
    work_email      VARCHAR(150) NOT NULL UNIQUE,
    license_number  VARCHAR(50) NULL UNIQUE,
    password_hash   VARCHAR(255) NOT NULL,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_user_type_hospital
        FOREIGN KEY (hospital_id)
        REFERENCES hospitals(id),

    INDEX idx_user_type_role (role)
) ENGINE=InnoDB;


-- Forgot-password tokens
CREATE TABLE IF NOT EXISTS password_reset_tokens (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    account_role    ENUM('hospital_admin','doctor','nurse') NOT NULL,
    account_id      INT UNSIGNED NOT NULL,
    token_hash      VARCHAR(255) NOT NULL,
    expires_at      TIMESTAMP NOT NULL,
    used_at         TIMESTAMP NULL,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_reset_user_type
        FOREIGN KEY (account_id)
        REFERENCES user_type(id),

    INDEX idx_reset_role_account (account_role, account_id)
) ENGINE=InnoDB;


-- ============================================================
-- PART 2 — Dispatch / Case Tracking
-- ============================================================


-- Barangays
CREATE TABLE IF NOT EXISTS barangays (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(100) NOT NULL,
    hospital_id     INT UNSIGNED,

    CONSTRAINT fk_barangays_hospital
        FOREIGN KEY (hospital_id)
        REFERENCES hospitals(id)
) ENGINE=InnoDB;


-- Emergency units / ambulances
CREATE TABLE IF NOT EXISTS units (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    unit_code       VARCHAR(20) NOT NULL,
    hospital_id     INT UNSIGNED,

    status          ENUM(
        'available',
        'dispatched',
        'in_transit',
        'maintenance'
    ) DEFAULT 'available',

    CONSTRAINT fk_units_hospital
        FOREIGN KEY (hospital_id)
        REFERENCES hospitals(id)
) ENGINE=InnoDB;


-- Patients
CREATE TABLE IF NOT EXISTS patients (
    id                    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    full_name             VARCHAR(150) NOT NULL,
    age                   INT,
    condition_summary     VARCHAR(255),
    consciousness_status  VARCHAR(50),
    created_at            TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;


-- Hospital beds
CREATE TABLE IF NOT EXISTS beds (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    hospital_id     INT UNSIGNED,
    bed_number      VARCHAR(20),

    status          ENUM(
        'available',
        'occupied',
        'reserved'
    ) DEFAULT 'available',

    CONSTRAINT fk_beds_hospital
        FOREIGN KEY (hospital_id)
        REFERENCES hospitals(id)
) ENGINE=InnoDB;


-- Emergency cases
CREATE TABLE IF NOT EXISTS cases (
    id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    case_number         VARCHAR(20) NOT NULL UNIQUE,
    patient_id          INT UNSIGNED,
    incident_type       VARCHAR(100),
    barangay_id         INT UNSIGNED,
    hospital_id         INT UNSIGNED,

    reported_by_role    ENUM(
        'hospital_admin',
        'doctor',
        'nurse'
    ),

    reported_by_id      INT UNSIGNED,
    reported_at         TIMESTAMP NOT NULL,

    assigned_unit_id    INT UNSIGNED,

    status              ENUM(
        'new',
        'not_dispatched',
        'en_route',
        'arrived',
        'completed'
    ) DEFAULT 'new',

    eta_minutes         INT,

    dispatched_at       TIMESTAMP NULL,
    picked_up_at        TIMESTAMP NULL,
    arrived_at          TIMESTAMP NULL,

    accepted_at         TIMESTAMP NULL,

    accepted_by_role    ENUM(
        'hospital_admin',
        'doctor',
        'nurse'
    ),

    accepted_by_id      INT UNSIGNED,
    assigned_bed_id     INT UNSIGNED,


    -- Foreign keys
    CONSTRAINT fk_cases_patient
        FOREIGN KEY (patient_id)
        REFERENCES patients(id),

    CONSTRAINT fk_cases_barangay
        FOREIGN KEY (barangay_id)
        REFERENCES barangays(id),

    CONSTRAINT fk_cases_hospital
        FOREIGN KEY (hospital_id)
        REFERENCES hospitals(id),

    CONSTRAINT fk_cases_unit
        FOREIGN KEY (assigned_unit_id)
        REFERENCES units(id),

    CONSTRAINT fk_cases_bed
        FOREIGN KEY (assigned_bed_id)
        REFERENCES beds(id),

    CONSTRAINT fk_cases_reported_by
        FOREIGN KEY (reported_by_id)
        REFERENCES user_type(id),

    CONSTRAINT fk_cases_accepted_by
        FOREIGN KEY (accepted_by_id)
        REFERENCES user_type(id),


    -- Indexes
    INDEX idx_cases_status (status),
    INDEX idx_cases_hospital (hospital_id)

) ENGINE=InnoDB;


-- ============================================================
-- Case Timeline
-- ============================================================

CREATE TABLE IF NOT EXISTS case_timeline_events (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    case_id         INT UNSIGNED NOT NULL,

    event_type      ENUM(
        'dispatch_request_received',
        'unit_dispatched',
        'patient_picked_up',
        'arrived_at_hospital'
    ) NOT NULL,

    event_time      TIMESTAMP NULL,

    is_completed    BOOLEAN DEFAULT FALSE,

    notes           VARCHAR(255),

    CONSTRAINT fk_timeline_case
        FOREIGN KEY (case_id)
        REFERENCES cases(id),

    INDEX idx_timeline_case (case_id)

) ENGINE=InnoDB;


-- ============================================================
-- Intake Checklist
-- ============================================================

CREATE TABLE IF NOT EXISTS intake_checklist_items (
    id                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    case_id           INT UNSIGNED NOT NULL,

    task_name         VARCHAR(150) NOT NULL,

    is_completed      BOOLEAN DEFAULT FALSE,

    completed_by_role ENUM(
        'hospital_admin',
        'doctor',
        'nurse'
    ),

    completed_by_id   INT UNSIGNED,

    completed_at      TIMESTAMP NULL,


    CONSTRAINT fk_checklist_case
        FOREIGN KEY (case_id)
        REFERENCES cases(id),

    CONSTRAINT fk_checklist_completed_by
        FOREIGN KEY (completed_by_id)
        REFERENCES user_type(id),

    INDEX idx_checklist_case (case_id)

) ENGINE=InnoDB;


-- ============================================================
-- Facility Resources
-- ============================================================

CREATE TABLE IF NOT EXISTS facility_resources (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    hospital_id     INT UNSIGNED,

    resource_type   ENUM(
        'bed_capacity',
        'ambulance_bay',
        'admitting_desk',
        'records_queue'
    ) NOT NULL,

    status_label    VARCHAR(100),

    capacity_total  INT,

    capacity_used   INT,

    updated_at      TIMESTAMP
                    DEFAULT CURRENT_TIMESTAMP
                    ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_resources_hospital
        FOREIGN KEY (hospital_id)
        REFERENCES hospitals(id)

) ENGINE=InnoDB;


-- ============================================================
-- Response Metrics
-- ============================================================

CREATE TABLE IF NOT EXISTS response_metrics (
    id                          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    hospital_id                 INT UNSIGNED,

    metric_date                 DATE NOT NULL,

    avg_response_time_minutes   DECIMAL(5,2),

    total_incoming_requests     INT,

    total_in_transit            INT,

    CONSTRAINT fk_metrics_hospital
        FOREIGN KEY (hospital_id)
        REFERENCES hospitals(id)

) ENGINE=InnoDB;


-- ============================================================
-- Notifications
-- ============================================================

CREATE TABLE IF NOT EXISTS notifications (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    case_id         INT UNSIGNED,

    type            VARCHAR(50),

    message         VARCHAR(255),

    is_read         BOOLEAN DEFAULT FALSE,

    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_notifications_case
        FOREIGN KEY (case_id)
        REFERENCES cases(id),

    INDEX idx_notifications_read (is_read)

) ENGINE=InnoDB;