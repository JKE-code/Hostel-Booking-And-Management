<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Authoritative Room Occupancy View
        DB::statement("DROP VIEW IF EXISTS v_room_occupancy");
        DB::statement("
            CREATE VIEW v_room_occupancy AS
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
            GROUP BY r.id, h.id, h.name, h.code, b.id, b.block_name, f.id, f.floor_number, r.room_number, r.capacity, r.room_type, r.base_fee, r.status
        ");

        // 2. Active Student Room Snapshot View
        DB::statement("DROP VIEW IF EXISTS v_student_room_snapshot");
        DB::statement("
            CREATE VIEW v_student_room_snapshot AS
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
            JOIN hostels h ON b.hostel_id = h.id
        ");

        // 3. Real-time Hostel Outside & Gate Statistics View
        DB::statement("DROP VIEW IF EXISTS v_hostel_outside_count");
        DB::statement("
            CREATE VIEW v_hostel_outside_count AS
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
            GROUP BY h.id, h.name, h.code
        ");
    }

    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS v_hostel_outside_count");
        DB::statement("DROP VIEW IF EXISTS v_student_room_snapshot");
        DB::statement("DROP VIEW IF EXISTS v_room_occupancy");
    }
};
