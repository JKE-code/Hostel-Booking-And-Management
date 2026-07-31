# Database Optimization & Recommendations

## Current Status
Your database structure is generally good, but here are some optimizations and additions that will improve performance and functionality.

## Critical Indexes to Add

### 1. Bookings Table Indexes
```sql
-- Add indexes for frequently queried columns
ALTER TABLE `bookings` ADD INDEX `idx_booking_ref` (`booking_ref`);
ALTER TABLE `bookings` ADD INDEX `idx_status` (`status`);
ALTER TABLE `bookings` ADD INDEX `idx_checkin_checkout` (`checkin`, `checkout`);
ALTER TABLE `bookings` ADD INDEX `idx_created_at` (`created_at`);
ALTER TABLE `bookings` ADD INDEX `idx_booking_source` (`booking_source`);
```

### 2. Guests Table Indexes
```sql
-- Add index for booking_id (foreign key)
ALTER TABLE `guests` ADD INDEX `idx_booking_id` (`booking_id`);
ALTER TABLE `guests` ADD INDEX `idx_email` (`email`);
ALTER TABLE `guests` ADD INDEX `idx_mobile` (`mobile`);
```

### 3. Booking Rooms Table Indexes
```sql
-- Add indexes for joins
ALTER TABLE `booking_rooms` ADD INDEX `idx_booking_id` (`booking_id`);
ALTER TABLE `booking_rooms` ADD INDEX `idx_room_type_id` (`room_type_id`);
```

### 4. Booking Add-ons Table Indexes
```sql
-- Add indexes for joins
ALTER TABLE `booking_addons` ADD INDEX `idx_booking_id` (`booking_id`);
ALTER TABLE `booking_addons` ADD INDEX `idx_addon_id` (`addon_id`);
```

### 5. Room Types Table Indexes
```sql
-- Add index for status
ALTER TABLE `room_types` ADD INDEX `idx_status` (`status`);
```

### 6. Add-ons Table Indexes
```sql
-- Add index for status
ALTER TABLE `addons` ADD INDEX `idx_status` (`status`);
```

### 7. Contact Messages Table Indexes
```sql
-- Add indexes for queries page
ALTER TABLE `contact_messages` ADD INDEX `idx_created_at` (`created_at`);
ALTER TABLE `contact_messages` ADD INDEX `idx_keep_forever` (`keep_forever`);
```

### 8. Reviews Table Indexes
```sql
-- Add indexes for queries page
ALTER TABLE `reviews` ADD INDEX `idx_created_at` (`created_at`);
ALTER TABLE `reviews` ADD INDEX `idx_keep_forever` (`keep_forever`);
ALTER TABLE `reviews` ADD INDEX `idx_rating` (`rating`);
```

## Performance Optimizations

### 1. Add Composite Indexes for Common Queries
```sql
-- For dashboard room occupancy query
ALTER TABLE `bookings` ADD INDEX `idx_status_dates` (`status`, `checkin`, `checkout`);

-- For filtering bookings by payment and status
ALTER TABLE `bookings` ADD INDEX `idx_payment_status` (`payment_type`, `status`);
```

### 2. Optimize Table Storage Engine
```sql
-- Ensure all tables use InnoDB (already done, but verify)
-- InnoDB supports foreign keys, transactions, and better concurrency
```

## Missing Columns to Add

### 1. Bookings Table - Add Payment ID
```sql
-- For storing Razorpay payment IDs
ALTER TABLE `bookings` 
ADD COLUMN `payment_id` VARCHAR(100) DEFAULT NULL AFTER `payment_type`,
ADD COLUMN `payment_method` VARCHAR(50) DEFAULT NULL AFTER `payment_id`;

-- Add index
ALTER TABLE `bookings` ADD INDEX `idx_payment_id` (`payment_id`);
```

### 2. Bookings Table - Add Timestamps
```sql
-- Add updated_at for tracking changes
ALTER TABLE `bookings` 
ADD COLUMN `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `created_at`;
```

### 3. Add Booking Notes/Comments Table
```sql
-- For staff to add notes about bookings
CREATE TABLE `booking_notes` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `booking_id` INT(11) NOT NULL,
  `admin_id` INT(11) DEFAULT NULL,
  `note` TEXT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_booking_id` (`booking_id`),
  INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

### 4. Add Email Log Table
```sql
-- Track all emails sent
CREATE TABLE `email_log` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `booking_id` INT(11) DEFAULT NULL,
  `recipient_email` VARCHAR(100) NOT NULL,
  `subject` VARCHAR(255) NOT NULL,
  `email_type` ENUM('confirmation', 'receipt', 'invoice', 'reminder', 'cancellation') NOT NULL,
  `status` ENUM('sent', 'failed', 'pending') DEFAULT 'pending',
  `sent_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_booking_id` (`booking_id`),
  INDEX `idx_email_type` (`email_type`),
  INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

## Data Integrity - Add Foreign Keys

### 1. Guests Table
```sql
ALTER TABLE `guests`
ADD CONSTRAINT `fk_guests_booking` 
FOREIGN KEY (`booking_id`) REFERENCES `bookings`(`id`) 
ON DELETE CASCADE ON UPDATE CASCADE;
```

### 2. Booking Rooms Table
```sql
ALTER TABLE `booking_rooms`
ADD CONSTRAINT `fk_booking_rooms_booking` 
FOREIGN KEY (`booking_id`) REFERENCES `bookings`(`id`) 
ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `fk_booking_rooms_room_type` 
FOREIGN KEY (`room_type_id`) REFERENCES `room_types`(`id`) 
ON DELETE RESTRICT ON UPDATE CASCADE;
```

### 3. Booking Add-ons Table
```sql
ALTER TABLE `booking_addons`
ADD CONSTRAINT `fk_booking_addons_booking` 
FOREIGN KEY (`booking_id`) REFERENCES `bookings`(`id`) 
ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `fk_booking_addons_addon` 
FOREIGN KEY (`addon_id`) REFERENCES `addons`(`id`) 
ON DELETE RESTRICT ON UPDATE CASCADE;
```

## Query Optimization Tips

### 1. Use Prepared Statements (Already Doing ✅)
- You're already using prepared statements which is great for security and performance

### 2. Avoid SELECT * in Production
```sql
-- Instead of: SELECT * FROM bookings
-- Use: SELECT id, booking_ref, status, total_amount FROM bookings
```

### 3. Use LIMIT for Large Result Sets
```sql
-- Always use LIMIT when displaying lists
SELECT * FROM bookings ORDER BY created_at DESC LIMIT 50;
```

### 4. Use Pagination
```sql
-- For bookings page
SELECT * FROM bookings 
ORDER BY created_at DESC 
LIMIT 20 OFFSET 0; -- Page 1
```

## Backup Strategy

### 1. Automated Daily Backups
```bash
# Add to cron job (daily at 2 AM)
0 2 * * * mysqldump -u root -p alluri_resorts > /backup/alluri_$(date +\%Y\%m\%d).sql
```

### 2. Keep Last 30 Days of Backups
```bash
# Delete backups older than 30 days
find /backup -name "alluri_*.sql" -mtime +30 -delete
```

## Security Enhancements

### 1. Add Audit Trail Table
```sql
CREATE TABLE `audit_log` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `table_name` VARCHAR(50) NOT NULL,
  `record_id` INT(11) NOT NULL,
  `action` ENUM('INSERT', 'UPDATE', 'DELETE') NOT NULL,
  `old_values` JSON DEFAULT NULL,
  `new_values` JSON DEFAULT NULL,
  `admin_id` INT(11) DEFAULT NULL,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_table_record` (`table_name`, `record_id`),
  INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

### 2. Add Session Management Table
```sql
CREATE TABLE `admin_sessions` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `admin_id` INT(11) NOT NULL,
  `session_id` VARCHAR(128) NOT NULL,
  `ip_address` VARCHAR(45) NOT NULL,
  `user_agent` VARCHAR(255) DEFAULT NULL,
  `last_activity` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_session` (`session_id`),
  INDEX `idx_admin_id` (`admin_id`),
  INDEX `idx_last_activity` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

## Analytics & Reporting Tables

### 1. Daily Statistics Table
```sql
CREATE TABLE `daily_stats` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `date` DATE NOT NULL,
  `total_bookings` INT(11) DEFAULT 0,
  `total_revenue` DECIMAL(10,2) DEFAULT 0,
  `occupancy_rate` DECIMAL(5,2) DEFAULT 0,
  `avg_booking_value` DECIMAL(10,2) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_date` (`date`),
  INDEX `idx_date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

## Maintenance Tasks

### 1. Regular Table Optimization
```sql
-- Run monthly
OPTIMIZE TABLE bookings;
OPTIMIZE TABLE guests;
OPTIMIZE TABLE booking_rooms;
OPTIMIZE TABLE booking_addons;
```

### 2. Analyze Tables for Query Optimization
```sql
-- Run after adding indexes
ANALYZE TABLE bookings;
ANALYZE TABLE guests;
ANALYZE TABLE booking_rooms;
```

### 3. Check Table Health
```sql
CHECK TABLE bookings;
CHECK TABLE guests;
```

## Recommended Execution Order

1. **First Priority - Add Indexes** (Immediate performance boost)
   - Run all index creation queries
   - This will speed up all queries immediately

2. **Second Priority - Add Missing Columns**
   - Add payment_id and payment_method to bookings
   - Add updated_at timestamp

3. **Third Priority - Add Foreign Keys**
   - Ensures data integrity
   - Prevents orphaned records

4. **Fourth Priority - Create New Tables**
   - booking_notes
   - email_log
   - audit_log
   - admin_sessions
   - daily_stats

5. **Fifth Priority - Setup Backups**
   - Configure automated backups
   - Test restore process

## Performance Monitoring

### 1. Enable Slow Query Log
```sql
-- In my.cnf or my.ini
slow_query_log = 1
slow_query_log_file = /var/log/mysql/slow-query.log
long_query_time = 2
```

### 2. Monitor Query Performance
```sql
-- Check slow queries
SHOW PROCESSLIST;

-- Check table sizes
SELECT 
    table_name,
    ROUND(((data_length + index_length) / 1024 / 1024), 2) AS "Size (MB)"
FROM information_schema.TABLES
WHERE table_schema = 'alluri_resorts'
ORDER BY (data_length + index_length) DESC;
```

## Estimated Impact

- **Indexes**: 50-80% faster queries
- **Foreign Keys**: Prevents data corruption
- **Audit Log**: Complete tracking of changes
- **Backups**: Data safety and recovery
- **New Tables**: Better organization and features

## SQL Script File

I'll create a complete SQL file with all recommended changes that you can run in order.
