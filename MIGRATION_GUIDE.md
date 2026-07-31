# Migration Guide: Alluri Resorts → College Hostel Management System

## Overview
This document explains how the Alluri Resorts booking system has been transformed into a comprehensive College Hostel Management System for managing student accommodations in boys and girls hostels.

---

## Key Changes

### 1. Database Schema Transformation

#### Old Structure (Alluri Resorts - Resort Booking):
- `room_types` → Room types with pricing
- `bookings` → Guest bookings with check-in/checkout
- `guests` → Temporary guest information
- `booking_rooms` → Room quantities booked
- `payments` → Payment transactions

#### New Structure (College Hostel):
- `hostels` → Boys/Girls hostel units
- `blocks` → Residential blocks (A, B, C, etc.)
- `floors` → Floor numbers within blocks
- `rooms` → Individual rooms with bed positions
- `bed_positions` → Specific bed assignments
- `students` → Permanent student records with enrollment numbers
- `room_allocations` → Semester/yearly room assignments
- `student_fees` → Semester fee tracking
- `fee_payments` → Fee collection records
- `student_leaves` → Leave/checkout requests
- `complaints` → Maintenance and facility complaints
- `hostel_settings` → Configuration and rules

### 2. Core Concept Changes

| Alluri Resorts | College Hostel |
|---|---|
| **Temporary Guests** | Permanent Students (enrollment based) |
| **Check-in/Checkout Dates** | Allocated From/Till (semester/yearly) |
| **Booking Reference** | Enrollment Number (unique, permanent) |
| **Payment** | Razorpay online + Cash/Check for fees |
| **Room Types** | Room Types (Single/Double/Triple/Quad) |
| **Room Quantity Selection** | Specific Bed Assignment |
| **Add-ons (Extras)** | Fee Components (room rent, mess, maintenance) |
| **Coupons** | Not applicable |
| **Multi-step Booking** | Admin-driven allocation + Student self-service |

### 3. User Roles

#### New Roles Added:
1. **Super Admin** - Manages all hostels, creates staff
2. **Hostel Admin** - Manages single hostel
3. **Block Warden** - Manages specific block
4. **Staff** - Support personnel
5. **Student** - Allocated student (reads-only for most features)

### 4. Key Features Added

#### Student Management:
- Student registration with enrollment number
- KYC document verification
- Department, semester, section tracking
- Emergency contact information
- Status tracking (active, graduated, left)

#### Room Allocation:
- Semester/yearly allocations
- Gender-based hostel assignment
- Specific room and bed assignment
- Room change requests
- Allocation history tracking

#### Fee Management:
- Semester fee structure configuration
- Per-student fee assignment
- Multiple payment methods
- Payment status tracking
- Late fee penalties
- Outstanding dues collection

#### Leave Management:
- Leave request workflow
- Approval by warden
- Return confirmation
- Automatic room availability updates

#### Complaint Tracking:
- Category-based complaints
- Priority assignment
- Status workflow
- Resolution tracking
- Photo documentation

#### Configuration:
- Hostel rules
- Check-in/checkout times
- Visiting hours
- Mess menu management
- Late fees percentage

---

## File Structure Mapping

```
EXISTING FILES (Keep these):
├── admin/
│   ├── dashboard.php ✓ (Modified for hostel stats)
│   ├── rooms.php ✓ (Redefined for hostel rooms)
│   ├── bookings.php ✓ (Changed to allocations)
│   ├── pricing.php ✓ (Changed to fee management)
│   ├── addons.php ✓ (Changed to fee components)
│   ├── coupons.php ✗ (REMOVE - not needed)
│   ├── login.php ✓ (Keep, update auth)
│   ├── menu.php ✓ (Keep - mess menu)
│   ├── analytics.php ✓ (Refactor for hostel)
│   └── includes/ ✓ (Update authentication)
│
├── config/
│   ├── db.php ✓ (Keep - database setup)
│   ├── razorpay.php ✓ (Keep - for fee payments)
│   └── hostel.php ✓ (NEW - hostel constants)
│
├── assets/
│   ├── css/
│   │   ├── style.css ✓ (Update colors/layout)
│   │   └── admin.css ✓ (Create for admin)
│   └── images/ ✓ (Keep, add hostel images)

NEW STRUCTURE:
├── admin/
│   ├── students.php (NEW - Student management)
│   ├── room_allocation.php (NEW - Allocate rooms)
│   ├── fee_management.php (NEW - Fee config)
│   ├── fee_collection.php (NEW - Payment tracking)
│   ├── leaves_management.php (NEW - Leave approvals)
│   ├── complaints_management.php (NEW - Complaint tracking)
│   ├── maintenance_schedule.php (NEW - Maintenance)
│   ├── hostel_settings.php (NEW - Configuration)
│   └── reports.php (NEW - Analytics)
│
├── student/
│   ├── dashboard.php (NEW - Student home)
│   ├── my_room.php (NEW - Room details)
│   ├── my_allocation.php (NEW - Allocation info)
│   ├── fee_payment.php (NEW - Fee payment)
│   ├── apply_leave.php (NEW - Leave request)
│   ├── my_complaints.php (NEW - Complaint view)
│   ├── submit_complaint.php (NEW - Submit complaint)
│   └── login.php (NEW - Student login)
│
├── public/
│   ├── hostels.php (NEW - Hostel info)
│   ├── facilities.php (NEW - Facilities showcase)
│   ├── rules.php (NEW - Hostel rules)
│   └── contact.php (NEW - Contact warden)
```

---

## Migration Steps

### Phase 1: Database Update
1. Backup existing Alluri Resorts database
2. Run migration scripts to create new tables
3. Create hostels (Boys, Girls)
4. Create blocks (A, B, C, D, etc.)
5. Create floors (1, 2, 3, etc.)
6. Create rooms with bed positions
7. Import student data from enrollment list

### Phase 2: Admin Backend
1. Update admin login with role-based access
2. Create student management module
3. Implement room allocation system
4. Build fee management system
5. Create leave request workflow
6. Build complaint tracking system
7. Set up maintenance scheduling

### Phase 3: Student Portal
1. Create student login
2. Build student dashboard
3. Show allocated room/bed information
4. Build fee payment interface (Razorpay)
5. Leave request form
6. Complaint submission form
7. Profile management

### Phase 4: Public Pages
1. Hostel information pages
2. Facilities showcase
3. Rules and regulations
4. Contact information

---

## Data Migration

### From Old to New Tables:

**Students Table:**
- Enrollment number (new column to generate)
- Full name, email, mobile
- Department (new)
- Semester (new)
- Status = active

**Room Allocations Table:**
- Map students to specific beds
- Set allocated_from = current semester start
- Set allocated_till = end of semester/year
- allocation_type = 'semester' or 'yearly'

**Fee Management:**
- Remove add-ons related to "extra persons"
- Create semester fee structure
- Set components: room rent, mess charges, maintenance

**Remove Tables:**
- booking_addons (no longer needed)
- coupons (no longer needed)
- coupon_usage (no longer needed)

---

## Configuration Changes

### config/hostel.php (New)
Contains:
- Hostel types (boys, girls)
- Room types (single, double, triple, quad)
- Allocation types (yearly, semester, temporary)
- Student status values
- Payment status values
- Complaint types and priorities
- Leave status values
- Admin roles
- Color scheme (keep Alluri theme)

### config/db.php (Update)
- Ensure PDO/MySQLi supports new tables
- Add helper functions for new models

---

## API Endpoints (New/Modified)

### Student Management:
- `POST /admin/api/register_student.php` - Register new student
- `GET /admin/api/get_students.php` - List students
- `PUT /admin/api/update_student.php` - Update student info
- `POST /admin/api/verify_student.php` - Verify KYC

### Room Allocation:
- `POST /admin/api/allocate_room.php` - Assign room/bed
- `GET /admin/api/get_allocations.php` - List allocations
- `POST /admin/api/deallocate_room.php` - Deallocate
- `POST /admin/api/request_room_change.php` - Change room request

### Fee Management:
- `POST /admin/api/set_fee_structure.php` - Configure semester fees
- `GET /admin/api/get_student_fees.php` - View fees for student
- `POST /admin/api/record_payment.php` - Record fee payment

### Leaves:
- `POST /student/api/apply_leave.php` - Student apply leave
- `POST /admin/api/approve_leave.php` - Warden approve leave
- `POST /admin/api/reject_leave.php` - Warden reject leave

### Complaints:
- `POST /student/api/submit_complaint.php` - Student submit complaint
- `GET /admin/api/get_complaints.php` - List complaints
- `PUT /admin/api/update_complaint.php` - Update status
- `POST /admin/api/resolve_complaint.php` - Mark resolved

---

## Testing Checklist

- [ ] Admin login works with new roles
- [ ] Student login works with enrollment number
- [ ] Room allocation creates proper bed assignments
- [ ] Fee payment via Razorpay works
- [ ] Leave approval workflow functions
- [ ] Complaint submission and tracking work
- [ ] Dashboard shows correct statistics
- [ ] Room status display is accurate
- [ ] Occupancy calculations are correct
- [ ] Reports generate properly

---

## Theme & Design

**Keep Existing:**
- Color scheme (Maroon #8b0000, Green #2d5f3f, Amber #d97706)
- Gradient backgrounds
- Card-based layouts
- Professional modals
- Responsive design

**Update:**
- Replace resort imagery with hostel images
- Update hero section messaging
- Adjust room showcase for hostel context
- Modify admin dashboard for hostel metrics

---

## Deployment Notes

1. **Database Connection:** Update db.php with college server credentials
2. **Upload Directory:** Create `/uploads/` for documents, photos
3. **Session Configuration:** Set appropriate timeout for admin (1 hour) and students (2 hours)
4. **Razorpay Keys:** Update for fee payments (if using online collection)
5. **Email Setup:** Configure for notifications (optional)
6. **SSL Certificate:** Ensure HTTPS for payment pages
7. **File Permissions:** Set proper permissions for upload directory

---

## Rollback Plan

If needed to revert to Alluri Resorts:
1. Restore database backup
2. Keep `/config/db.php` from college version
3. Switch admin pages back to original versions
4. Restore original CSS files
5. Test all resort functionality

---

## Support & Troubleshooting

### Common Issues:

**Issue:** Student can't login
- **Solution:** Check enrollment number matches database, verify status = 'active'

**Issue:** Room allocation fails
- **Solution:** Verify hostel, block, floor, room, and bed_positions exist in database

**Issue:** Fee payment not recording
- **Solution:** Check Razorpay configuration, verify fee_payments table structure

**Issue:** Leave approval not working
- **Solution:** Verify admin has hostel_id assigned, check leave_status values

For additional support, check activity logs and error logs in server.

---

**Status:** Migration Ready for Implementation
**Version:** 2.0 (College Hostel Edition)
**Date:** 2024
