-- ============================================================
-- reddiph_admin — Database Schema
-- Target: MySQL 8.0+
-- Engine: InnoDB
-- Charset: utf8mb4
--
-- SAFE IMPORT NOTES:
-- - Does NOT CREATE DATABASE
-- - Does NOT DROP DATABASE
-- - Does NOT DROP TABLES
-- - Does NOT DELETE existing data
-- - Existing tables are skipped using IF NOT EXISTS
--
-- XAMPP:
--   1. Create/select database: reddiph_db
--   2. Import this file in phpMyAdmin
--
-- Hostinger:
--   1. Select your existing Hostinger database
--   2. Import this file in phpMyAdmin
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE IF NOT EXISTS hospitals (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    address VARCHAR(255) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_hospitals_name (name)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS user_type (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    role ENUM('hospital_admin','doctor','nurse') NOT NULL,
    full_name VARCHAR(150) NOT NULL,
    hospital_name VARCHAR(150) NOT NULL,
    hospital_id INT UNSIGNED NULL,
    work_email VARCHAR(150) NOT NULL,
    license_number VARCHAR(50) NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT uq_user_type_work_email UNIQUE (work_email),
    CONSTRAINT uq_user_type_license_number UNIQUE (license_number),

    CONSTRAINT fk_user_type_hospital
        FOREIGN KEY (hospital_id)
        REFERENCES hospitals(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL,

    INDEX idx_user_type_role (role),
    INDEX idx_user_type_hospital (hospital_id)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS password_reset_tokens (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    account_role ENUM('hospital_admin','doctor','nurse') NOT NULL,
    account_id INT UNSIGNED NOT NULL,
    token_hash VARCHAR(255) NOT NULL,
    expires_at TIMESTAMP NOT NULL,
    used_at TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_reset_user_type
        FOREIGN KEY (account_id)
        REFERENCES user_type(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    INDEX idx_reset_role_account (account_role, account_id),
    INDEX idx_reset_token_hash (token_hash),
    INDEX idx_reset_expires_at (expires_at)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS barangays (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    hospital_id INT UNSIGNED NULL,

    CONSTRAINT fk_barangays_hospital
        FOREIGN KEY (hospital_id)
        REFERENCES hospitals(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL,

    INDEX idx_barangays_name (name),
    INDEX idx_barangays_hospital (hospital_id)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS units (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    unit_code VARCHAR(20) NOT NULL,
    hospital_id INT UNSIGNED NULL,
    status ENUM('available','dispatched','in_transit','maintenance') NOT NULL DEFAULT 'available',

    CONSTRAINT fk_units_hospital
        FOREIGN KEY (hospital_id)
        REFERENCES hospitals(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL,

    INDEX idx_units_code (unit_code),
    INDEX idx_units_hospital (hospital_id),
    INDEX idx_units_status (status)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS patients (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(150) NOT NULL,
    age INT NULL,
    condition_summary VARCHAR(255) NULL,
    consciousness_status VARCHAR(50) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_patients_full_name (full_name)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS beds (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    hospital_id INT UNSIGNED NULL,
    bed_number VARCHAR(20) NULL,
    status ENUM('available','occupied','reserved') NOT NULL DEFAULT 'available',

    CONSTRAINT fk_beds_hospital
        FOREIGN KEY (hospital_id)
        REFERENCES hospitals(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL,

    INDEX idx_beds_hospital (hospital_id),
    INDEX idx_beds_status (status)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS cases (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    case_number VARCHAR(20) NOT NULL,
    patient_id INT UNSIGNED NULL,
    incident_type VARCHAR(100) NULL,
    barangay_id INT UNSIGNED NULL,
    hospital_id INT UNSIGNED NULL,
    reported_by_role ENUM('hospital_admin','doctor','nurse') NULL,
    reported_by_id INT UNSIGNED NULL,
    reported_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    assigned_unit_id INT UNSIGNED NULL,
    status ENUM('new','not_dispatched','en_route','arrived','completed') NOT NULL DEFAULT 'new',
    eta_minutes INT NULL,
    dispatched_at TIMESTAMP NULL DEFAULT NULL,
    picked_up_at TIMESTAMP NULL DEFAULT NULL,
    arrived_at TIMESTAMP NULL DEFAULT NULL,
    accepted_at TIMESTAMP NULL DEFAULT NULL,
    accepted_by_role ENUM('hospital_admin','doctor','nurse') NULL,
    accepted_by_id INT UNSIGNED NULL,
    assigned_bed_id INT UNSIGNED NULL,

    CONSTRAINT uq_cases_case_number UNIQUE (case_number),

    CONSTRAINT fk_cases_patient
        FOREIGN KEY (patient_id)
        REFERENCES patients(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL,

    CONSTRAINT fk_cases_barangay
        FOREIGN KEY (barangay_id)
        REFERENCES barangays(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL,

    CONSTRAINT fk_cases_hospital
        FOREIGN KEY (hospital_id)
        REFERENCES hospitals(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL,

    CONSTRAINT fk_cases_unit
        FOREIGN KEY (assigned_unit_id)
        REFERENCES units(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL,

    CONSTRAINT fk_cases_bed
        FOREIGN KEY (assigned_bed_id)
        REFERENCES beds(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL,

    CONSTRAINT fk_cases_reported_by
        FOREIGN KEY (reported_by_id)
        REFERENCES user_type(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL,

    CONSTRAINT fk_cases_accepted_by
        FOREIGN KEY (accepted_by_id)
        REFERENCES user_type(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL,

    INDEX idx_cases_status (status),
    INDEX idx_cases_hospital (hospital_id),
    INDEX idx_cases_patient (patient_id),
    INDEX idx_cases_barangay (barangay_id),
    INDEX idx_cases_unit (assigned_unit_id),
    INDEX idx_cases_bed (assigned_bed_id),
    INDEX idx_cases_reported_by (reported_by_id),
    INDEX idx_cases_accepted_by (accepted_by_id),
    INDEX idx_cases_reported_at (reported_at)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS case_timeline_events (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    case_id INT UNSIGNED NOT NULL,
    event_type ENUM(
        'dispatch_request_received',
        'unit_dispatched',
        'patient_picked_up',
        'arrived_at_hospital'
    ) NOT NULL,
    event_time TIMESTAMP NULL DEFAULT NULL,
    is_completed BOOLEAN NOT NULL DEFAULT FALSE,
    notes VARCHAR(255) NULL,

    CONSTRAINT fk_timeline_case
        FOREIGN KEY (case_id)
        REFERENCES cases(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    INDEX idx_timeline_case (case_id),
    INDEX idx_timeline_event_type (event_type),
    INDEX idx_timeline_event_time (event_time)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS intake_checklist_items (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    case_id INT UNSIGNED NOT NULL,
    task_name VARCHAR(150) NOT NULL,
    is_completed BOOLEAN NOT NULL DEFAULT FALSE,
    completed_by_role ENUM('hospital_admin','doctor','nurse') NULL,
    completed_by_id INT UNSIGNED NULL,
    completed_at TIMESTAMP NULL DEFAULT NULL,

    CONSTRAINT fk_checklist_case
        FOREIGN KEY (case_id)
        REFERENCES cases(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    CONSTRAINT fk_checklist_completed_by
        FOREIGN KEY (completed_by_id)
        REFERENCES user_type(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL,

    INDEX idx_checklist_case (case_id),
    INDEX idx_checklist_completed_by (completed_by_id),
    INDEX idx_checklist_is_completed (is_completed)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS facility_resources (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    hospital_id INT UNSIGNED NULL,
    resource_type ENUM('bed_capacity','ambulance_bay','admitting_desk','records_queue') NOT NULL,
    status_label VARCHAR(100) NULL,
    capacity_total INT NULL,
    capacity_used INT NULL,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_resources_hospital
        FOREIGN KEY (hospital_id)
        REFERENCES hospitals(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL,

    INDEX idx_resources_hospital (hospital_id),
    INDEX idx_resources_type (resource_type)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS response_metrics (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    hospital_id INT UNSIGNED NULL,
    metric_date DATE NOT NULL,
    avg_response_time_minutes DECIMAL(5,2) NULL,
    total_incoming_requests INT NULL,
    total_in_transit INT NULL,

    CONSTRAINT fk_metrics_hospital
        FOREIGN KEY (hospital_id)
        REFERENCES hospitals(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL,

    INDEX idx_metrics_hospital (hospital_id),
    INDEX idx_metrics_date (metric_date)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS notifications (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    case_id INT UNSIGNED NULL,
    type VARCHAR(50) NULL,
    message VARCHAR(255) NULL,
    is_read BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_notifications_case
        FOREIGN KEY (case_id)
        REFERENCES cases(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    INDEX idx_notifications_case (case_id),
    INDEX idx_notifications_read (is_read),
    INDEX idx_notifications_created_at (created_at)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- END OF SCHEMA
-- ============================================================
