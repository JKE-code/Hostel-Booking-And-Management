# College Hostel Management System - Specification

## 🎓 Project Overview
Convert Alluri Resorts booking system into a comprehensive College Hostel Management System with:
- Boys and Girls hostels
- Multiple blocks per hostel
- Room allocation system
- Student registration & verification
- Hostel fee management
- Leave/checkout management
- Complaint & maintenance tracking

---

## 📊 New Database Schema

### 1. Hostel Configuration Tables

**hostels**
- id, name (Boys Hostel / Girls Hostel), type (boys/girls), warden_name, warden_contact, status
- capacity, built_year, description

**blocks**
- id, hostel_id, block_name (A, B, C, etc.), block_number, floor_count, status
- capacity (total rooms in block)

**floors**
- id, block_id, floor_number (1, 2, 3, etc.), capacity, status

**rooms**
- id, block_id, floor_id, room_number (e.g., "A-101", "B-204"), room_type (single/double/triple/quad)
- capacity (max students), current_occupancy, status (available/occupied/maintenance)
- maintenance_notes

**bed_positions**
- id, room_id, bed_number (1, 2, 3, 4), status (available/occupied/blocked)

---

### 2. Student & Admission Tables

**students**
- id, enrollment_no (unique), full_name, email, mobile, parent_contact
- department, semester, section, admission_date
- date_of_birth, gender, address, city, state, pincode
- emergency_contact_name, emergency_contact_phone
- kyc_verified (bool), document_path
- status (active/inactive/graduated/left)
- created_at

**student_verification**
- id, student_id, admin_id, verification_date, verification_status (pending/approved/rejected)
- remarks, verification_documents

---

### 3. Allocation & Booking Tables

**room_allocations**
- id, student_id, room_id, bed_position_id
- allocated_from (admission date), allocated_till (checkout date)
- allocation_type (yearly/semester/temporary)
- status (active/inactive/completed/cancelled)
- allocated_by_admin_id, allocation_date
- created_at, updated_at

**allocation_history**
- Audit trail: which student was in which room/bed, dates, status changes

**room_change_requests**
- id, student_id, current_room_id, requested_room_id, reason
- status (pending/approved/rejected), admin_remarks
- created_at, admin_response_date

---

### 4. Fee & Payment Tables

**hostel_fees**
- id, semester_no, academic_year (2024-25), amount_per_semester
- fee_due_date, late_fee_percentage
- fee_components: room_rent, mess_charges, maintenance, others
- status (active/inactive)

**student_fees**
- id, student_id, semester_no, total_amount, amount_paid, balance_due
- payment_status (pending/partial/complete)
- last_payment_date, payment_method (razorpay/bank_transfer/check/cash)

**fee_payments**
- id, student_fee_id, amount, payment_date, razorpay_payment_id
- payment_method, remarks
- created_at

---

### 5. Leave & Checkout Tables

**student_leaves**
- id, student_id, leave_from, leave_till, reason
- status (pending/approved/rejected)
- approval_by_admin_id, approval_date
- actual_checkout_date
- return_expected_date, actual_return_date

**student_checkout**
- id, student_id, checkout_date, checkout_reason (semester_end/permanent/dropout)
- clearance_status (pending/cleared/pending_fees)
- dues_cleared_date
- room_inspection_notes
- damage_assessment (yes/no), damage_amount
- created_at

---

### 6. Complaint & Maintenance Tables

**complaints**
- id, student_id, room_id, complaint_type (plumbing/electrical/furniture/cleanliness/noise/other)
- complaint_description, priority (low/medium/high/urgent)
- status (open/in_progress/resolved/closed)
- reported_date, reported_by_student_id
- assigned_to_admin_id, assigned_date, resolution_date
- resolution_notes, before_photo, after_photo
- created_at

**maintenance_schedule**
- id, block_id, maintenance_type (daily_cleaning/weekly_inspection/monthly_deep_clean)
- scheduled_date, scheduled_time
- assigned_staff_id, status (pending/in_progress/completed)
- notes, created_at

---

### 7. Admin & Staff Tables

**admins** (wardens/hostel staff)
- id, name, email, mobile, designation (warden/asst_warden/staff)
- hostel_id, block_id (optional - can be assigned to specific block)
- role (super_admin/hostel_warden/block_warden/staff)
- username, password_hash, status (active/inactive)
- last_login, created_at

**activity_logs**
- id, admin_id, action (allocation_created/fee_paid/complaint_resolved), target_student_id, details
- ip_address, created_at

---

### 8. Configuration Tables

**mess_menu**
- id, day_of_week, meal_type (breakfast/lunch/dinner/snacks)
- menu_items, dietary_notes, created_at

**hostel_settings**
- id, setting_key, setting_value (JSON)
- Examples: check_in_time, check_out_time, visiting_hours, etc.

---

## 🎨 Frontend Changes

### User Dashboard (Student Portal)
- **My Room**: Current room details, roommates, floor info, block info
- **My Allocation**: View current allocation, history, transfer requests
- **Fee Status**: Semester fees, payment status, payment history, online payment
- **My Complaints**: Submit new complaint, view complaint status
- **Leaves**: Apply for leave, view leave status
- **Checkout**: Request checkout, view clearance status
- **Mess Menu**: View daily mess menu
- **My Documents**: Upload/manage documents

### Public Pages
- **Hostels Overview**: Boys/Girls hostels, block info, capacity
- **Facilities & Rules**: Hostel rules, facilities, timing
- **Contact**: Hostel warden contact, office hours
- **FAQ**: Common queries

### Admin Dashboard (Warden/Staff)
- **Students Management**: Add, verify, manage students
- **Room Allocation**: Allocate rooms, view occupancy, room transfers
- **Fee Management**: Configure fees, track payments, send reminders
- **Leave Approvals**: Approve/reject leave requests
- **Complaints & Maintenance**: Track complaints, schedule maintenance
- **Reports**: Occupancy, revenue, complaints analytics
- **Staff Management**: Manage hostel staff (if super admin)
- **Settings**: Configure hostel rules, timings, fee components

---

## 🔧 Key Features

### 1. Room Allocation System
- Multi-level allocation: Hostel → Block → Floor → Room → Bed
- Auto-allocation based on gender, block availability
- Manual allocation by warden
- Room change requests with approval workflow
- Allocation history tracking

### 2. Student Management
- Enrollment-based student records
- KYC document verification
- Duplicate enrollment prevention
- Student status tracking (active/inactive/graduated)

### 3. Fee Management
- Semester-based fee structure
- Multiple fee components
- Online payment via Razorpay
- Late payment penalties
- Payment reminders
- Outstanding dues tracking

### 4. Leave Management
- Apply for leave with date range
- Warden approval workflow
- Automatic room availability updates
- Return confirmation

### 5. Complaint Tracking
- Multi-category complaints
- Priority-based assignment
- Status tracking with updates
- Photo documentation
- Resolution notes

### 6. Reports & Analytics
- Occupancy rate per block/hostel
- Fee collection status
- Complaint resolution time
- Student demographics

---

## 🔒 Security & Validation

- Role-based access control (Student, Warden, Admin)
- Student enrollment verification
- Document upload security (file type, size validation)
- Payment signature verification (Razorpay)
- Activity logging for all admin actions
- Session timeout for admins
- XSS and SQL injection prevention

---

## 📱 Design Theme
Keep existing Alluri Resorts theme:
- Maroon Red (#8b0000) primary
- Forest Green (#2d5f3f) secondary
- Amber (#d97706) accent
- Gradient backgrounds
- Card-based layouts
- Professional modals

---

## 📁 File Structure
```
/config
  - db.php (database connection)
  - hostel.php (hostel constants/settings)
  - razorpay.php (payment config)

/admin
  - dashboard.php
  - students.php (manage students)
  - room_allocation.php
  - room_management.php
  - fee_management.php
  - fee_collection.php
  - leaves_management.php
  - complaints_management.php
  - maintenance_schedule.php
  - reports.php
  - staff_management.php
  - hostel_settings.php
  - includes/ (header, footer, auth, etc.)

/student
  - dashboard.php (home)
  - my_room.php
  - my_allocation.php
  - fee_payment.php
  - apply_leave.php
  - my_complaints.php
  - submit_complaint.php
  - request_room_change.php
  - documents.php
  - checkout.php
  - includes/

/public
  - hostels.php
  - facilities.php
  - rules.php
  - contact.php
  - faq.php
  - includes/

/assets
  - css/
  - js/
  - images/
```

---

## ✅ Implementation Checklist

- [x] Database schema design
- [ ] Database migration script
- [ ] Admin setup & authentication
- [ ] Student registration & verification
- [ ] Room allocation system
- [ ] Fee management & payments
- [ ] Leave management
- [ ] Complaint tracking
- [ ] Frontend student dashboard
- [ ] Frontend admin dashboard
- [ ] Reports & analytics
- [ ] Testing & deployment

---

**Status**: Specification Complete - Ready for Implementation
