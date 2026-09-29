# HITAM Hostel — Database Schema Reference

**Project:** HITAM Hostel Residential Management System  
**Engine:** MySQL 8.0+ (InnoDB) via Hostinger / cPanel / XAMPP  
**ORM:** Laravel 11 Eloquent + Laravel Sanctum v4.3.3  
**Last Updated:** September 2026  
**Total Tables:** 22 (16 domain + 3 Laravel system + 3 infrastructure)  
**Total Views:** 3 Reporting & Analytics Views

---

## 1. What Was Missing & Why It Was Added

The schema incorporates these essential tables and architectural enhancements required by the project blueprints:

| Table / Feature | Why It's Needed | Source |
|---|---|---|
| `otps` | Brevo email OTP storage for secure student password resets & two-factor verifications. | Security & Reset Auth |
| `students` (Vault Columns) | Document URLs (`photo_url`, `id_proof_url`, `admission_letter_url`, `medical_cert_url`) and verification metadata for resident onboarding. | Admission & Document Vault |
| `academic_years` | Reference table normalizing the `'2025-2026'` academic year label across models. | Schema Normalization |
| `personal_access_tokens` | Laravel Sanctum Bearer Token authentication for the Flutter Student App. | Blueprint Phase 3 |
| `visitors` | Gate visitor pre-registration (`GET/POST /api/v1/student/visitors`). | Blueprint Phase 3 |
| `mess_menus` | `GET /api/v1/mess/today-menu` endpoint with full 7-day × 4-meal cycle backing. | Mobile Blueprint |
| `device_tokens` | FCM push notification token storage for outpass approvals and urgent notices. | Blueprint Phase 4 |
| `hostel_id` on `outpasses` | Enables instant filtering ("who is outside by hostel") without expensive 5-table joins. | Performance Optimization |
| `hostel_wardens` | Pivot table enabling multi-warden management (Chief Warden, Assistant, Night Warden). | Operational Scaling |
| `v_room_occupancy` | Authoritative SQL View dynamically calculating room capacity, occupied beds, and available beds. | Live Analytics |
| `v_student_room_snapshot` | Unified active resident room mapping view for Student Portal and Warden Directory. | Query Optimization |
| `v_hostel_outside_count` | Real-time aggregate view of outside students, overdue passes, and today's return counts. | Security Gate Operations |

---

## 2. Schema Optimizations Applied

| # | Component | Original Bottleneck | Production Fix |
|---|---|---|---|
| 1 | `room_allocations` | `UNIQUE(student_id, academic_year)` completely blocked students from transferring rooms mid-year. | Replaced with composite index `(student_id, academic_year, status)` + `(student_id, status)` and added `vacated_at DATE NULL`. Preserves complete transfer audit trail. |
| 2 | `outpasses` | Status enum lacked `'cancelled'`. If a student cancelled a pass or security invalidated it, queries failed. | Added `'cancelled'` to status enum: `['pending', 'approved', 'rejected', 'cancelled', 'returned', 'overdue']`. Added `(hostel_id, status)` and `(hostel_id, out_datetime)` indexes. |
| 3 | `rooms` | Status enum `'available'` vs `'full'` was a denormalized cache that desynced from bed records. | Aligned status enum to operational states: `['active', 'available', 'full', 'maintenance', 'inactive']` (default `'active'`). Live occupancy is calculated dynamically via `v_room_occupancy`. Added `(floor_id, status)` index. |
| 4 | `hostels` / Multi-Warden | `warden_in_charge_id` limited each hostel building to a single warden. | Retained `warden_in_charge_id` (Chief Warden) and added `hostel_wardens` pivot table (`hostel_id`, `user_id`, `role_title`, `is_primary`, `contact_phone`). |
| 5 | `beds` | Querying available beds per room during allocation was doing full-table scans. | Added composite index `(room_id, status)`. |
| 6 | `users` | Role filtering (`WHERE role = 'warden' AND is_active = 1`) had no index. | Added composite index `(role, is_active)` and included `'security'` in enum. |
| 7 | SQL Views | Complex reporting was calculated in PHP with N+1 queries. | Implemented 3 native database views (`v_room_occupancy`, `v_student_room_snapshot`, `v_hostel_outside_count`) compatible with MySQL and SQLite. |

---

## 3. Complete Relationship Map

```
academic_years                          (reference table — no FKs)

users
 ├── students              (user_id → users.id) [nullable]
 │    ├── room_allocations (student_id → students.id, bed_id → beds.id)
 │    ├── outpasses        (student_id → students.id)
 │    ├── complaints       (student_id → students.id)
 │    ├── visitors         (student_id → students.id)
 │    └── fee_transactions (student_id → students.id)
 ├── hostels               (warden_in_charge_id → users.id)   [Chief Warden]
 ├── hostel_wardens        (user_id → users.id)               [Multi-Warden Team]
 ├── notices               (published_by → users.id)          [nullable]
 ├── outpasses             (approved_by → users.id)           [nullable]
 ├── visitors              (approved_by → users.id)           [nullable]
 ├── complaints            (assigned_to → users.id)           [nullable]
 ├── room_allocations      (allocated_by → users.id)          [nullable]
 ├── students (verifier)   (document_verified_by → users.id)  [nullable]
 └── device_tokens         (user_id → users.id)

hostels
 ├── blocks                (hostel_id → hostels.id)
 │    └── floors           (block_id → blocks.id)
 │         └── rooms       (floor_id → floors.id)
 │              └── beds   (room_id → rooms.id)
 ├── hostel_wardens        (hostel_id → hostels.id)
 ├── outpasses             (hostel_id → hostels.id)           [direct FK for gate reporting]
 ├── visitors              (hostel_id → hostels.id)           [nullable]
 ├── mess_menus            (hostel_id → hostels.id)           [nullable — null = all hostels]
 └── fee_transactions      (hostel_id → hostels.id)           [nullable]

otps                        (standalone index on identifier: email/phone)
personal_access_tokens      (tokenable polymorphic → users)   [Sanctum — token authentication]
```

---

## 4. Table Summary

| # | Table | Purpose |
|---|---|---|
| 1 | `academic_years` | Reference table: 2024-25, 2025-26 (current), 2026-27 |
| 2 | `users` | Base identity: admin, warden, security, student credentials |
| 3 | `otps` | Brevo OTP storage, expiration, rate limiting & attempt lockout |
| 4 | `students` | Student academic profile, parent contacts, address & document vault |
| 5 | `hostels` | Boys Hostel, Girls Hostel, New Boys Hostel |
| 6 | `hostel_wardens` | Multi-warden team assignments (Chief, Assistant, Resident Warden) |
| 7 | `blocks` | Building blocks (Block A, Block B, Block C, Block G) |
| 8 | `floors` | Floor mapping per block (Ground to 4th Floor) |
| 9 | `rooms` | Rooms per floor, capacity, room type, operational status |
| 10 | `beds` | Individual beds (A, B, C, D) with occupancy status |
| 11 | `room_allocations` | Active & historical room allocations with transfer support |
| 12 | `outpasses` | Gate leave passes with dynamic QR verification & cancel support |
| 13 | `complaints` | Maintenance ticketing system with category, priority, status |
| 14 | `visitors` | Visitor pre-registration & security gate check-in log |
| 15 | `notices` | Hostel announcements with audience targeting and pinning |
| 16 | `mess_menus` | 7-day × 4-meal cycle nutrition and food menu |
| 17 | `fee_transactions` | Fee ledger for hostel, mess, and amenity payments |
| 18 | `device_tokens` | FCM push notification registration tokens |
| 19 | `personal_access_tokens` | Laravel Sanctum Bearer Tokens for mobile app API |
| 20 | `password_reset_tokens` | Laravel authentication subsystem |
| 21 | `sessions` | Web session state management |
| 22 | `cache` / `jobs` | High-throughput background worker queue & cache |

---

## 5. MySQL Workbench — Complete Optimized Schema Script

```sql
-- =============================================================
-- HITAM HOSTEL MANAGEMENT SYSTEM
-- Optimized Production Schema (MySQL 8.0+ / InnoDB)
-- Engine: InnoDB | Charset: utf8mb4 | Collation: utf8mb4_unicode_ci
-- =============================================================

CREATE DATABASE IF NOT EXISTS `hitam_hostel`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE `hitam_hostel`;

-- 1. academic_years
CREATE TABLE `academic_years` (
    `id`          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `label`       VARCHAR(9)      NOT NULL COMMENT '2025-2026',
    `start_date`  DATE            NOT NULL,
    `end_date`    DATE            NOT NULL,
    `is_current`  TINYINT(1)      NOT NULL DEFAULT 0,
    `created_at`  TIMESTAMP       NULL DEFAULT NULL,
    `updated_at`  TIMESTAMP       NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `academic_years_label_unique` (`label`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. users
CREATE TABLE `users` (
    `id`                BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `name`              VARCHAR(255)     NOT NULL,
    `email`             VARCHAR(255)     NOT NULL,
    `email_verified_at` TIMESTAMP        NULL DEFAULT NULL,
    `password`          VARCHAR(255)     NOT NULL,
    `role`              ENUM('admin','warden','security','student') NOT NULL DEFAULT 'student',
    `avatar_url`        VARCHAR(255)     NULL DEFAULT NULL,
    `is_active`         TINYINT(1)       NOT NULL DEFAULT 1,
    `remember_token`    VARCHAR(100)     NULL DEFAULT NULL,
    `created_at`        TIMESTAMP        NULL DEFAULT NULL,
    `updated_at`        TIMESTAMP        NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `users_email_unique` (`email`),
    KEY `users_role_active_index` (`role`, `is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. otps
CREATE TABLE `otps` (
    `id`          BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `identifier`  VARCHAR(255)     NOT NULL COMMENT 'email or phone',
    `otp_code`    VARCHAR(255)     NOT NULL COMMENT 'Hashed storage',
    `purpose`     VARCHAR(255)     NOT NULL DEFAULT 'student_signup',
    `attempts`    TINYINT UNSIGNED NOT NULL DEFAULT 0,
    `is_locked`   TINYINT(1)       NOT NULL DEFAULT 0,
    `expires_at`  TIMESTAMP        NOT NULL,
    `verified_at` TIMESTAMP        NULL DEFAULT NULL,
    `created_at`  TIMESTAMP        NULL DEFAULT NULL,
    `updated_at`  TIMESTAMP        NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `otps_identifier_index` (`identifier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. students
CREATE TABLE `students` (
    `id`                   BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `user_id`              BIGINT UNSIGNED  NULL DEFAULT NULL,
    `name`                 VARCHAR(255)     NOT NULL,
    `email`                VARCHAR(255)     NOT NULL,
    `roll_number`          VARCHAR(20)      NOT NULL COMMENT 'e.g. 24E51A05A2',
    `department`           ENUM('CSE','ECE','EEE','MECH','CIVIL','IT','MBA','MCA','PHD','OTHER') NOT NULL,
    `year_of_study`        TINYINT UNSIGNED NOT NULL COMMENT '1 to 4',
    `phone`                VARCHAR(15)      NOT NULL,
    `parent_name`          VARCHAR(255)     NOT NULL,
    `parent_phone`         VARCHAR(15)      NOT NULL,
    `emergency_contact`    VARCHAR(15)      NULL DEFAULT NULL,
    `blood_group`          VARCHAR(5)       NULL DEFAULT NULL,
    `gender`               ENUM('male','female','other') NOT NULL,
    `date_of_birth`        DATE             NULL DEFAULT NULL,
    `permanent_address`    TEXT             NULL DEFAULT NULL,
    `photo_url`            VARCHAR(255)     NULL DEFAULT NULL,
    `id_proof_url`         VARCHAR(255)     NULL DEFAULT NULL,
    `admission_letter_url` VARCHAR(255)     NULL DEFAULT NULL,
    `medical_cert_url`     VARCHAR(255)     NULL DEFAULT NULL,
    `document_verified_at` DATETIME         NULL DEFAULT NULL,
    `document_verified_by` BIGINT UNSIGNED  NULL DEFAULT NULL,
    `verification_notes`   TEXT             NULL DEFAULT NULL,
    `onboarding_status`    ENUM('whitelisted','active','archived') NOT NULL DEFAULT 'whitelisted',
    `created_at`           TIMESTAMP        NULL DEFAULT NULL,
    `updated_at`           TIMESTAMP        NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `students_email_unique` (`email`),
    UNIQUE KEY `students_roll_number_unique` (`roll_number`),
    KEY `students_user_id_index` (`user_id`),
    KEY `students_phone_index` (`phone`),
    CONSTRAINT `students_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
    CONSTRAINT `students_verified_by_foreign` FOREIGN KEY (`document_verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. hostels
CREATE TABLE `hostels` (
    `id`                  BIGINT UNSIGNED     NOT NULL AUTO_INCREMENT,
    `name`                VARCHAR(255)        NOT NULL,
    `code`                VARCHAR(10)         NOT NULL,
    `gender_type`         ENUM('male','female','mixed') NOT NULL,
    `total_capacity`      SMALLINT UNSIGNED   NOT NULL DEFAULT 0,
    `warden_in_charge_id` BIGINT UNSIGNED     NULL DEFAULT NULL,
    `address`             TEXT                NULL DEFAULT NULL,
    `contact_phone`       VARCHAR(15)         NULL DEFAULT NULL,
    `created_at`          TIMESTAMP           NULL DEFAULT NULL,
    `updated_at`          TIMESTAMP           NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `hostels_code_unique` (`code`),
    CONSTRAINT `hostels_warden_foreign` FOREIGN KEY (`warden_in_charge_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. hostel_wardens (Multi-Warden Team Pivot)
CREATE TABLE `hostel_wardens` (
    `id`            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `hostel_id`     BIGINT UNSIGNED NOT NULL,
    `user_id`       BIGINT UNSIGNED NOT NULL,
    `role_title`    ENUM('chief_warden','assistant_warden','resident_warden') NOT NULL DEFAULT 'assistant_warden',
    `is_primary`    TINYINT(1)      NOT NULL DEFAULT 0,
    `contact_phone` VARCHAR(15)     NULL DEFAULT NULL,
    `created_at`    TIMESTAMP       NULL DEFAULT NULL,
    `updated_at`    TIMESTAMP       NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `hostel_wardens_unique` (`hostel_id`, `user_id`),
    KEY `hostel_wardens_primary_index` (`hostel_id`, `is_primary`),
    CONSTRAINT `hw_hostel_foreign` FOREIGN KEY (`hostel_id`) REFERENCES `hostels` (`id`) ON DELETE CASCADE,
    CONSTRAINT `hw_user_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. blocks
CREATE TABLE `blocks` (
    `id`           BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `hostel_id`    BIGINT UNSIGNED  NOT NULL,
    `block_name`   VARCHAR(100)     NOT NULL,
    `block_code`   VARCHAR(10)      NOT NULL,
    `total_floors` TINYINT UNSIGNED NOT NULL DEFAULT 1,
    `created_at`   TIMESTAMP        NULL DEFAULT NULL,
    `updated_at`   TIMESTAMP        NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `blocks_hostel_code_unique` (`hostel_id`, `block_code`),
    CONSTRAINT `blocks_hostel_id_foreign` FOREIGN KEY (`hostel_id`) REFERENCES `hostels` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. floors
CREATE TABLE `floors` (
    `id`           BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `block_id`     BIGINT UNSIGNED  NOT NULL,
    `floor_number` TINYINT UNSIGNED NOT NULL,
    `floor_label`  VARCHAR(50)      NULL DEFAULT NULL,
    `created_at`   TIMESTAMP        NULL DEFAULT NULL,
    `updated_at`   TIMESTAMP        NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `floors_block_number_unique` (`block_id`, `floor_number`),
    CONSTRAINT `floors_block_id_foreign` FOREIGN KEY (`block_id`) REFERENCES `blocks` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. rooms
CREATE TABLE `rooms` (
    `id`          BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `floor_id`    BIGINT UNSIGNED  NOT NULL,
    `room_number` VARCHAR(10)      NOT NULL COMMENT 'A-101, B-204',
    `capacity`    TINYINT UNSIGNED NOT NULL DEFAULT 2,
    `room_type`   ENUM('AC','Non-AC') NOT NULL DEFAULT 'Non-AC',
    `base_fee`    DECIMAL(10,2)    NOT NULL DEFAULT 0.00,
    `status`      ENUM('active','available','full','maintenance','inactive') NOT NULL DEFAULT 'active',
    `created_at`  TIMESTAMP        NULL DEFAULT NULL,
    `updated_at`  TIMESTAMP        NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `rooms_floor_number_unique` (`floor_id`, `room_number`),
    KEY `rooms_floor_status_index` (`floor_id`, `status`),
    CONSTRAINT `rooms_floor_id_foreign` FOREIGN KEY (`floor_id`) REFERENCES `floors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. beds
CREATE TABLE `beds` (
    `id`             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `room_id`        BIGINT UNSIGNED NOT NULL,
    `bed_identifier` VARCHAR(5)      NOT NULL COMMENT 'A, B, C, D',
    `status`         ENUM('available','occupied','maintenance') NOT NULL DEFAULT 'available',
    `created_at`     TIMESTAMP       NULL DEFAULT NULL,
    `updated_at`     TIMESTAMP       NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `beds_room_identifier_unique` (`room_id`, `bed_identifier`),
    KEY `beds_room_status_index` (`room_id`, `status`),
    CONSTRAINT `beds_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 11. room_allocations (Transfer-Enabled Architecture)
CREATE TABLE `room_allocations` (
    `id`             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id`     BIGINT UNSIGNED NOT NULL,
    `bed_id`         BIGINT UNSIGNED NOT NULL,
    `academic_year`  VARCHAR(9)      NOT NULL COMMENT '2025-2026',
    `allocated_from` DATE            NOT NULL,
    `allocated_to`   DATE            NULL DEFAULT NULL,
    `vacated_at`     DATE            NULL DEFAULT NULL,
    `status`         ENUM('active','vacated','cancelled') NOT NULL DEFAULT 'active',
    `remarks`        TEXT            NULL DEFAULT NULL,
    `allocated_by`   BIGINT UNSIGNED NULL DEFAULT NULL,
    `created_at`     TIMESTAMP       NULL DEFAULT NULL,
    `updated_at`     TIMESTAMP       NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `allocations_student_status_index` (`student_id`, `status`),
    KEY `allocations_student_year_status_index` (`student_id`, `academic_year`, `status`),
    KEY `allocations_bed_status_index` (`bed_id`, `status`),
    KEY `allocations_year_status_index` (`academic_year`, `status`),
    CONSTRAINT `allocations_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
    CONSTRAINT `allocations_bed_id_foreign` FOREIGN KEY (`bed_id`) REFERENCES `beds` (`id`) ON DELETE CASCADE,
    CONSTRAINT `allocations_allocated_by_foreign` FOREIGN KEY (`allocated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 12. outpasses
CREATE TABLE `outpasses` (
    `id`                     BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id`             BIGINT UNSIGNED NOT NULL,
    `hostel_id`              BIGINT UNSIGNED NULL DEFAULT NULL,
    `leave_type`             ENUM('day_pass','weekend','vacation','emergency','medical','academic') NOT NULL,
    `destination`            VARCHAR(255)    NOT NULL,
    `reason`                 TEXT            NOT NULL,
    `out_datetime`           DATETIME        NOT NULL,
    `in_datetime`            DATETIME        NOT NULL,
    `actual_return_datetime` DATETIME        NULL DEFAULT NULL,
    `parent_consent`         TINYINT(1)      NOT NULL DEFAULT 0,
    `parent_consent_via`     VARCHAR(50)     NULL DEFAULT NULL,
    `status`                 ENUM('pending','approved','rejected','cancelled','returned','overdue') NOT NULL DEFAULT 'pending',
    `approved_by`            BIGINT UNSIGNED NULL DEFAULT NULL,
    `approved_at`            DATETIME        NULL DEFAULT NULL,
    `rejection_reason`       TEXT            NULL DEFAULT NULL,
    `qr_verification_code`   VARCHAR(64)     NULL DEFAULT NULL,
    `created_at`             TIMESTAMP       NULL DEFAULT NULL,
    `updated_at`             TIMESTAMP       NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `outpasses_qr_unique` (`qr_verification_code`),
    KEY `outpasses_student_status_index` (`student_id`, `status`),
    KEY `outpasses_hostel_status_index` (`hostel_id`, `status`),
    KEY `outpasses_hostel_out_datetime_index` (`hostel_id`, `out_datetime`),
    KEY `outpasses_status_in_datetime_index` (`status`, `in_datetime`),
    KEY `outpasses_out_datetime_index` (`out_datetime`),
    CONSTRAINT `outpasses_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
    CONSTRAINT `outpasses_hostel_id_foreign` FOREIGN KEY (`hostel_id`) REFERENCES `hostels` (`id`) ON DELETE SET NULL,
    CONSTRAINT `outpasses_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 13. complaints
CREATE TABLE `complaints` (
    `id`                BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `student_id`        BIGINT UNSIGNED  NOT NULL,
    `room_id`           BIGINT UNSIGNED  NULL DEFAULT NULL,
    `category`          ENUM('electrical','plumbing','wifi','cleaning','carpentry','ac_fan','pest_control','other') NOT NULL,
    `title`             VARCHAR(255)     NOT NULL,
    `description`       TEXT             NOT NULL,
    `photo_url`         VARCHAR(255)     NULL DEFAULT NULL,
    `priority`          ENUM('low','medium','high','urgent') NOT NULL DEFAULT 'medium',
    `status`            ENUM('submitted','in_progress','resolved','closed') NOT NULL DEFAULT 'submitted',
    `assigned_to`       BIGINT UNSIGNED  NULL DEFAULT NULL,
    `assigned_at`       DATETIME         NULL DEFAULT NULL,
    `warden_remarks`    TEXT             NULL DEFAULT NULL,
    `resolved_at`       DATETIME         NULL DEFAULT NULL,
    `resolution_rating` TINYINT UNSIGNED NULL DEFAULT NULL,
    `created_at`        TIMESTAMP        NULL DEFAULT NULL,
    `updated_at`        TIMESTAMP        NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `complaints_student_status_index` (`student_id`, `status`),
    KEY `complaints_status_priority_index` (`status`, `priority`),
    KEY `complaints_assigned_to_index` (`assigned_to`),
    CONSTRAINT `complaints_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
    CONSTRAINT `complaints_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE SET NULL,
    CONSTRAINT `complaints_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 14. visitors
CREATE TABLE `visitors` (
    `id`                 BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `student_id`         BIGINT UNSIGNED  NOT NULL,
    `hostel_id`          BIGINT UNSIGNED  NULL DEFAULT NULL,
    `visitor_name`       VARCHAR(255)     NOT NULL,
    `relation`           VARCHAR(50)      NOT NULL,
    `visitor_phone`      VARCHAR(15)      NOT NULL,
    `visitor_id_type`    VARCHAR(50)      NULL DEFAULT NULL,
    `visitor_id_number`  VARCHAR(100)     NULL DEFAULT NULL,
    `visitor_count`      SMALLINT UNSIGNED NOT NULL DEFAULT 1,
    `visit_date`         DATE             NOT NULL,
    `expected_arrival`   TIME             NULL DEFAULT NULL,
    `expected_departure` TIME             NULL DEFAULT NULL,
    `purpose`            TEXT             NULL DEFAULT NULL,
    `status`             ENUM('pre_registered','checked_in','checked_out','no_show','rejected') NOT NULL DEFAULT 'pre_registered',
    `checked_in_at`      TIMESTAMP        NULL DEFAULT NULL,
    `checked_out_at`     TIMESTAMP        NULL DEFAULT NULL,
    `approved_by`        BIGINT UNSIGNED  NULL DEFAULT NULL,
    `remarks`            TEXT             NULL DEFAULT NULL,
    `created_at`         TIMESTAMP        NULL DEFAULT NULL,
    `updated_at`         TIMESTAMP        NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `visitors_student_date_index` (`student_id`, `visit_date`),
    KEY `visitors_hostel_date_status_index` (`hostel_id`, `visit_date`, `status`),
    KEY `visitors_status_date_index` (`status`, `visit_date`),
    CONSTRAINT `visitors_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
    CONSTRAINT `visitors_hostel_id_foreign` FOREIGN KEY (`hostel_id`) REFERENCES `hostels` (`id`) ON DELETE SET NULL,
    CONSTRAINT `visitors_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 15. notices
CREATE TABLE `notices` (
    `id`                BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title`             VARCHAR(255)    NOT NULL,
    `content`           TEXT            NOT NULL,
    `category`          ENUM('general','fee','maintenance','event','academic','rule','emergency') NOT NULL DEFAULT 'general',
    `target_audience`   ENUM('all','boys_hostel','girls_hostel','new_boys_hostel') NOT NULL DEFAULT 'all',
    `is_pinned`         TINYINT(1)      NOT NULL DEFAULT 0,
    `is_published`      TINYINT(1)      NOT NULL DEFAULT 1,
    `attachment_url`    VARCHAR(255)    NULL DEFAULT NULL,
    `published_by`      BIGINT UNSIGNED NULL DEFAULT NULL,
    `expires_at`        DATETIME        NULL DEFAULT NULL,
    `created_at`        TIMESTAMP       NULL DEFAULT NULL,
    `updated_at`        TIMESTAMP       NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `notices_published_pinned_index` (`is_published`, `is_pinned`),
    KEY `notices_target_published_index` (`target_audience`, `is_published`),
    KEY `notices_expires_at_index` (`expires_at`),
    CONSTRAINT `notices_published_by_foreign` FOREIGN KEY (`published_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 16. mess_menus
CREATE TABLE `mess_menus` (
    `id`              BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `hostel_id`       BIGINT UNSIGNED NULL DEFAULT NULL,
    `day_of_week`     ENUM('monday','tuesday','wednesday','thursday','friday','saturday','sunday') NOT NULL,
    `meal_type`       ENUM('breakfast','lunch','snacks','dinner') NOT NULL,
    `menu_title`      VARCHAR(255)    NOT NULL,
    `items`           TEXT            NOT NULL,
    `special_note`    VARCHAR(255)    NULL DEFAULT NULL,
    `is_active`       TINYINT(1)      NOT NULL DEFAULT 1,
    `effective_from`  DATE            NULL DEFAULT NULL,
    `effective_until` DATE            NULL DEFAULT NULL,
    `created_at`      TIMESTAMP       NULL DEFAULT NULL,
    `updated_at`      TIMESTAMP       NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `mess_menus_unique` (`hostel_id`, `day_of_week`, `meal_type`, `effective_from`),
    KEY `mess_menus_lookup_index` (`hostel_id`, `day_of_week`, `is_active`),
    CONSTRAINT `mess_menus_hostel_id_foreign` FOREIGN KEY (`hostel_id`) REFERENCES `hostels` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 17. fee_transactions
CREATE TABLE `fee_transactions` (
    `id`               BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id`       BIGINT UNSIGNED NOT NULL,
    `hostel_id`        BIGINT UNSIGNED NULL DEFAULT NULL,
    `amount`           DECIMAL(10,2)   NOT NULL,
    `payment_type`     ENUM('hostel_fee','mess_fee','amenity_fee','fine','security_deposit') NOT NULL,
    `academic_year`    VARCHAR(9)      NOT NULL,
    `transaction_id`   VARCHAR(100)    NULL DEFAULT NULL,
    `payment_method`   VARCHAR(50)     NULL DEFAULT NULL,
    `status`           ENUM('paid','pending','failed','refunded') NOT NULL DEFAULT 'pending',
    `receipt_path`     VARCHAR(255)    NULL DEFAULT NULL,
    `remarks`          TEXT            NULL DEFAULT NULL,
    `paid_at`          DATETIME        NULL DEFAULT NULL,
    `created_at`       TIMESTAMP       NULL DEFAULT NULL,
    `updated_at`       TIMESTAMP       NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `fee_transactions_trans_id_unique` (`transaction_id`),
    KEY `fee_student_year_status_index` (`student_id`, `academic_year`, `status`),
    KEY `fee_status_type_index` (`status`, `payment_type`),
    CONSTRAINT `fee_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fee_hostel_id_foreign` FOREIGN KEY (`hostel_id`) REFERENCES `hostels` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 18. device_tokens
CREATE TABLE `device_tokens` (
    `id`           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id`      BIGINT UNSIGNED NOT NULL,
    `fcm_token`    VARCHAR(255)    NOT NULL,
    `device_type`  VARCHAR(10)     NOT NULL DEFAULT 'android',
    `device_name`  VARCHAR(255)    NULL DEFAULT NULL,
    `app_version`  VARCHAR(20)     NULL DEFAULT NULL,
    `is_active`    TINYINT(1)      NOT NULL DEFAULT 1,
    `last_used_at` TIMESTAMP       NULL DEFAULT NULL,
    `created_at`   TIMESTAMP       NULL DEFAULT NULL,
    `updated_at`   TIMESTAMP       NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `device_tokens_fcm_unique` (`fcm_token`),
    KEY `device_tokens_user_active_index` (`user_id`, `is_active`),
    CONSTRAINT `device_tokens_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 19. personal_access_tokens (Sanctum)
CREATE TABLE `personal_access_tokens` (
    `id`             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `tokenable_type` VARCHAR(255)    NOT NULL,
    `tokenable_id`   BIGINT UNSIGNED NOT NULL,
    `name`           VARCHAR(255)    NOT NULL,
    `token`          VARCHAR(64)     NOT NULL,
    `abilities`      TEXT            NULL DEFAULT NULL,
    `last_used_at`   TIMESTAMP       NULL DEFAULT NULL,
    `expires_at`     TIMESTAMP       NULL DEFAULT NULL,
    `created_at`     TIMESTAMP       NULL DEFAULT NULL,
    `updated_at`     TIMESTAMP       NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
    KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`, `tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 20. password_reset_tokens
CREATE TABLE `password_reset_tokens` (
    `email`      VARCHAR(255) NOT NULL,
    `token`      VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP    NULL DEFAULT NULL,
    PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 21. sessions
CREATE TABLE `sessions` (
    `id`            VARCHAR(255)     NOT NULL,
    `user_id`       BIGINT UNSIGNED  NULL DEFAULT NULL,
    `ip_address`    VARCHAR(45)      NULL DEFAULT NULL,
    `user_agent`    TEXT             NULL DEFAULT NULL,
    `payload`       LONGTEXT         NOT NULL,
    `last_activity` INT              NOT NULL,
    PRIMARY KEY (`id`),
    KEY `sessions_user_id_index` (`user_id`),
    KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 22. cache & jobs
CREATE TABLE `cache` (
    `key`        VARCHAR(255) NOT NULL,
    `value`      MEDIUMTEXT   NOT NULL,
    `expiration` INT          NOT NULL,
    PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `jobs` (
    `id`           BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `queue`        VARCHAR(255)     NOT NULL,
    `payload`      LONGTEXT         NOT NULL,
    `attempts`     TINYINT UNSIGNED NOT NULL,
    `reserved_at`  INT UNSIGNED     NULL DEFAULT NULL,
    `available_at` INT UNSIGNED     NOT NULL,
    `created_at`   INT UNSIGNED     NOT NULL,
    PRIMARY KEY (`id`),
    KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 6. Authoritative SQL Reporting Views

These views are deployed via migration `2025_01_01_000120_create_reporting_views.php` and provide instant analytics without complex runtime PHP calculations:

### View 1: `v_room_occupancy`
Dynamically calculates true room capacity, occupied beds, available beds, and fullness across all hostels:
```sql
CREATE OR REPLACE VIEW v_room_occupancy AS
SELECT 
    h.id AS hostel_id,
    h.name AS hostel_name,
    h.code AS hostel_code,
    b.id AS block_id,
    b.block_name,
    f.id AS floor_id,
    f.floor_number,
    r.id AS room_id,
    r.room_number,
    r.capacity,
    r.room_type,
    r.base_fee,
    r.status AS room_status,
    COUNT(bd.id) AS total_beds,
    COUNT(CASE WHEN bd.status = 'occupied' THEN 1 END) AS occupied_beds,
    COUNT(CASE WHEN bd.status = 'available' THEN 1 END) AS available_beds,
    COUNT(CASE WHEN bd.status = 'maintenance' THEN 1 END) AS maintenance_beds,
    CASE WHEN COUNT(CASE WHEN bd.status = 'available' THEN 1 END) = 0 THEN 1 ELSE 0 END AS is_full
FROM rooms r
JOIN floors f ON r.floor_id = f.id
JOIN blocks b ON f.block_id = b.id
JOIN hostels h ON b.hostel_id = h.id
LEFT JOIN beds bd ON bd.room_id = r.id
GROUP BY r.id, h.id, h.name, h.code, b.id, b.block_name, f.id, f.floor_number, r.room_number, r.capacity, r.room_type, r.base_fee, r.status;
```

### View 2: `v_student_room_snapshot`
Returns the active room mapping for resident students, used directly by the Student Portal and Warden Directory:
```sql
CREATE OR REPLACE VIEW v_student_room_snapshot AS
SELECT 
    s.id AS student_id,
    s.roll_number,
    u.name AS student_name,
    u.email AS student_email,
    s.phone AS student_phone,
    s.gender,
    s.department,
    s.year_of_study,
    s.parent_name,
    s.parent_phone,
    ra.id AS allocation_id,
    ra.academic_year,
    ra.allocated_from,
    ra.status AS allocation_status,
    bd.id AS bed_id,
    bd.bed_identifier,
    r.id AS room_id,
    r.room_number,
    r.room_type,
    f.floor_number,
    b.block_name,
    h.id AS hostel_id,
    h.name AS hostel_name,
    h.code AS hostel_code
FROM students s
JOIN users u ON s.user_id = u.id
JOIN room_allocations ra ON ra.student_id = s.id AND ra.status = 'active'
JOIN beds bd ON ra.bed_id = bd.id
JOIN rooms r ON bd.room_id = r.id
JOIN floors f ON r.floor_id = f.id
JOIN blocks b ON f.block_id = b.id
JOIN hostels h ON b.hostel_id = h.id;
```

### View 3: `v_hostel_outside_count`
Real-time security gate metrics aggregated by hostel building:
```sql
CREATE OR REPLACE VIEW v_hostel_outside_count AS
SELECT 
    h.id AS hostel_id,
    h.name AS hostel_name,
    h.code AS hostel_code,
    COUNT(CASE WHEN o.status = 'approved' AND o.actual_return_datetime IS NULL THEN 1 END) AS total_outside,
    COUNT(CASE WHEN o.status = 'overdue' OR (o.status = 'approved' AND o.in_datetime < CURRENT_TIMESTAMP AND o.actual_return_datetime IS NULL) THEN 1 END) AS total_overdue,
    COUNT(CASE WHEN o.status = 'returned' AND DATE(o.actual_return_datetime) = CURRENT_DATE THEN 1 END) AS total_returned_today,
    COUNT(CASE WHEN o.status = 'pending' THEN 1 END) AS total_pending_outpasses
FROM hostels h
LEFT JOIN outpasses o ON o.hostel_id = h.id
GROUP BY h.id, h.name, h.code;
```

---

## 7. High-Performance Production Query Catalog

These pre-optimized queries power the Web Portal and Mobile API endpoints:

### Query 1: Student Roommates Lookup (`GET /api/v1/student/roommates`)
```sql
SELECT 
    s.roll_number,
    u.name AS student_name,
    s.department,
    s.year_of_study,
    bd.bed_identifier
FROM beds bd
JOIN room_allocations ra ON ra.bed_id = bd.id AND ra.status = 'active'
JOIN students s ON ra.student_id = s.id
JOIN users u ON s.user_id = u.id
WHERE bd.room_id = (
    SELECT b2.room_id 
    FROM room_allocations ra2 
    JOIN beds b2 ON ra2.bed_id = b2.id 
    WHERE ra2.student_id = :student_id AND ra2.status = 'active'
)
AND s.id != :student_id
ORDER BY bd.bed_identifier;
```

### Query 2: Gate Security QR Code Verification (`POST /api/v1/gate/verify-outpass`)
```sql
SELECT 
    o.id AS outpass_id,
    o.status,
    o.leave_type,
    o.destination,
    o.out_datetime,
    o.in_datetime,
    s.roll_number,
    u.name AS student_name,
    s.phone AS student_phone,
    s.parent_phone,
    h.name AS hostel_name,
    r.room_number
FROM outpasses o
JOIN students s ON o.student_id = s.id
JOIN users u ON s.user_id = u.id
JOIN hostels h ON o.hostel_id = h.id
LEFT JOIN room_allocations ra ON ra.student_id = s.id AND ra.status = 'active'
LEFT JOIN beds bd ON ra.bed_id = bd.id
LEFT JOIN rooms r ON bd.room_id = r.id
WHERE o.qr_verification_code = :scanned_qr_code
LIMIT 1;
```

### Query 3: Hostel Occupancy Dashboard Summary (Warden / Admin)
```sql
SELECT 
    hostel_name,
    COUNT(room_id) AS total_rooms,
    SUM(total_beds) AS total_beds,
    SUM(occupied_beds) AS occupied_beds,
    SUM(available_beds) AS available_beds,
    ROUND((SUM(occupied_beds) / SUM(total_beds)) * 100, 1) AS occupancy_percentage
FROM v_room_occupancy
GROUP BY hostel_id, hostel_name;
```

---

## 8. Scheduled Maintenance Commands

Run daily via Laravel Task Scheduler (`app/Console/Kernel.php` or cron):

```sql
-- 1. Mark overdue outpasses (runs daily at midnight)
UPDATE `outpasses`
SET `status` = 'overdue', `updated_at` = NOW()
WHERE `status` = 'approved'
  AND `in_datetime` < NOW()
  AND `actual_return_datetime` IS NULL;

-- 2. Auto-archive expired notices
UPDATE `notices`
SET `is_published` = 0, `updated_at` = NOW()
WHERE `is_published` = 1
  AND `expires_at` IS NOT NULL
  AND `expires_at` < NOW();

-- 3. Deactivate stale FCM device tokens (> 90 days inactive)
UPDATE `device_tokens`
SET `is_active` = 0, `updated_at` = NOW()
WHERE `is_active` = 1
  AND `last_used_at` < DATE_SUB(NOW(), INTERVAL 90 DAY);
```

---

## 9. Migration File Order

All migrations execute in strict foreign-key safe dependency order:

```text
0001_01_01_000000_create_users_table.php             (Base users & roles, indexed role+is_active)
0001_01_01_000001_create_cache_table.php             (Cache table)
0001_01_01_000002_create_jobs_table.php              (Queue worker jobs)
2025_01_01_000003_create_otps_table.php              (Brevo OTP password reset storage)
2025_01_01_000005_create_academic_years_table.php     (Academic year reference)
2025_01_01_000010_create_students_table.php           (Student profiles, FK → users)
2025_01_01_000012_add_documents_to_students_table.php (Document vault URLs & verification metadata)
2025_01_01_000020_create_hostels_table.php            (Hostels, FK → users)
2025_01_01_000025_create_hostel_wardens_table.php     (Multi-warden pivot, FK → hostels, users)
2025_01_01_000030_create_blocks_table.php             (Hostel blocks, FK → hostels)
2025_01_01_000040_create_floors_table.php             (Floors, FK → blocks)
2025_01_01_000050_create_rooms_table.php              (Rooms, operational status, FK → floors)
2025_01_01_000060_create_beds_table.php               (Beds, indexed room_id+status, FK → rooms)
2025_01_01_000070_create_room_allocations_table.php   (Transfer-enabled allocations, vacated_at)
2025_01_01_000080_create_outpasses_table.php          (Outpasses, cancelled status, hostel_id index)
2025_01_01_000085_create_personal_access_tokens_table (Laravel Sanctum auth)
2025_01_01_000090_create_complaints_table.php         (Maintenance tickets)
2025_01_01_000095_create_visitors_table.php           (Gate visitor pre-registration)
2025_01_01_000100_create_notices_table.php            (Announcements board)
2025_01_01_000105_create_mess_menus_table.php         (7-day meal menu)
2025_01_01_000110_create_fee_transactions_table.php   (Payment ledger)
2025_01_01_000115_create_device_tokens_table.php      (FCM push tokens)
2025_01_01_000120_create_reporting_views.php          (v_room_occupancy, v_student_room_snapshot, v_hostel_outside_count)
```
