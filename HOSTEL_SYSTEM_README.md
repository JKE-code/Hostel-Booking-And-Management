# College Hostel Management System

**Converted from:** Alluri Resorts Booking System  
**Current Version:** 2.0 - College Hostel Edition  
**Status:** Implementation Ready

---

## 🎓 What Changed?

The system has been **transformed from a resort booking platform into a comprehensive college hostel management system** while keeping the same codebase structure and design theme.

### Before (Alluri Resorts):
- Resort room booking for temporary guests
- Check-in/checkout based dates
- Guest information temporary
- Booking reference system
- Payment for room bookings
- Add-ons for extras (breakfast, activities)
- Multi-step online booking flow

### After (College Hostel):
- Student accommodation for permanent residents
- Semester/yearly allocation dates
- Student enrollment-based records
- Permanent enrollment numbers
- Fee collection (room, mess, maintenance)
- Fee components (not add-ons)
- Admin-driven allocation + self-service student portal

---

## 🏗️ New Architecture

### Database Schema

**New Concept Hierarchy:**
```
College
  ├─ Hostel (Boys/Girls)
  │  ├─ Block (A, B, C, ...)
  │  │  ├─ Floor (1, 2, 3, ...)
  │  │  │  ├─ Room (101, 102, 103, ...)
  │  │  │  │  ├─ Bed Position 1
  │  │  │  │  ├─ Bed Position 2
  │  │  │  │  └─ Bed Position 3
```

**Key Tables:**
- `hostels` - Boys/Girls hostel units
- `blocks` - Residential blocks
- `floors` - Floor numbers
- `rooms` - Individual rooms with type
- `bed_positions` - Specific bed assignments
- `students` - Permanent student records (enrollment-based)
- `room_allocations` - Student → Room/Bed assignments
- `student_fees` - Semester fee tracking
- `fee_payments` - Payment records
- `student_leaves` - Leave requests
- `complaints` - Maintenance/facility complaints
- `hostels_settings` - Configuration

---

## 📋 System Features

### Admin Side

#### 1. Student Management
- Register students (enrollment-based)
- Verify KYC documents
- Track student status (active, graduated, left)
- View student details

#### 2. Room Allocation
- Allocate students to specific rooms and beds
- View occupancy status
- Handle room change requests
- Track allocation history
- Deallocate (checkout) students

#### 3. Fee Management
- Configure semester fee structure
- Define fee components (room, mess, maintenance)
- Assign fees to students
- Track payment status
- Set late fee penalties
- Generate fee reports

#### 4. Leave Management
- Approve/reject leave requests
- Track leave dates
- Confirm return dates
- Generate leave reports

#### 5. Complaint Tracking
- Receive maintenance complaints
- Assign to staff
- Track resolution progress
- Document with photos
- Generate complaint reports

#### 6. Maintenance Scheduling
- Schedule preventive maintenance
- Assign to maintenance team
- Track completion
- Block rooms when needed

#### 7. Analytics & Reports
- Occupancy rates per hostel/block
- Fee collection status
- Complaint statistics
- Student demographics
- Revenue reports

#### 8. Configuration
- Hostel rules
- Check-in/checkout times
- Visiting hours
- Late fees percentage
- Mess menu management

### Student Side

#### 1. Dashboard
- Personal greeting
- Current allocation display
- Fee status
- Quick access to features

#### 2. Room Information
- Hostel and block details
- Room number and bed assigned
- Roommates list
- Room facilities

#### 3. Allocation Details
- Current allocation dates
- Past allocations history
- Request room change

#### 4. Fee Payment
- View semester fees
- Payment breakdown
- Pay online via Razorpay
- Download receipts
- Payment history

#### 5. Leave Request
- Apply for leave
- View approval status
- Get estimated return date
- View leave history

#### 6. Complaints
- Submit new complaint
- View complaint status
- Track resolution progress
- View complaint history

#### 7. Profile
- View personal information
- Update contact details
- Emergency contact management
- Document upload

### Public Pages

- **Hostels Info** - Boys/Girls hostel details
- **Facilities** - Amenities and services
- **Rules** - Hostel regulations
- **Contact** - Warden contact info
- **FAQ** - Common questions

---

## 🚀 Quick Start

### 1. Database Setup

```bash
# 1. Create database
mysql -u root -p < database/college_hostel_schema.sql

# 2. Create hostels
INSERT INTO hostels (name, type, warden_name, warden_contact, warden_email) 
VALUES 
('Boys Hostel', 'boys', 'Mr. Warden', '+91-9876543210', 'boys@college.edu'),
('Girls Hostel', 'girls', 'Ms. Warden', '+91-9876543211', 'girls@college.edu');

# 3. Create admin user
# Use the admin login page to create first super admin
```

### 2. Configuration

Update `config/db.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'your_user');
define('DB_PASS', 'your_password');
define('DB_NAME', 'college_hostel');
```

### 3. File Structure

```
/
├── config/
│   ├── db.php (update with credentials)
│   ├── hostel.php (hostel constants)
│   └── razorpay.php (payment gateway)
├── admin/
│   ├── dashboard.php (admin home)
│   ├── students.php (student management)
│   ├── room_allocation.php (allocate rooms)
│   ├── fee_management.php (fee configuration)
│   ├── complaints_management.php (track complaints)
│   └── ... (other admin pages)
├── student/
│   ├── dashboard.php (student home)
│   ├── my_room.php (room details)
│   ├── fee_payment.php (pay fees)
│   ├── apply_leave.php (request leave)
│   └── ... (other student pages)
├── public/
│   ├── index.php (home page)
│   ├── hostels.php (hostel info)
│   ├── facilities.php (facilities)
│   └── ... (other public pages)
├── database/
│   └── college_hostel_schema.sql
└── assets/
    ├── css/ (styling)
    └── images/ (hostel images)
```

### 4. Default Logins

**Admin:**
- URL: `/admin/login.php`
- Username: `admin`
- Password: `admin123` (change after first login)
- Roles: Super Admin, Hostel Admin, Block Warden, Staff

**Student:**
- URL: `/student/login.php`
- Enrollment Number: `BCA001` (example)
- Password: `hostel123` (or enrollment-based default)

---

## 🔄 Data Flow

### Student Accommodation Flow

```
1. Register Student (Admin)
   ↓
2. Verify KYC (Admin)
   ↓
3. Allocate Room/Bed (Admin)
   ↓
4. Student Receives Allocation (Auto Notification - optional)
   ↓
5. Student Logs In (Student Portal)
   ↓
6. Views Room Details (Student)
   ↓
7. Pays Hostel Fees (Student - Razorpay)
   ↓
8. Check-in (Manual or Auto)
   ↓
9. During Stay:
   - Request Leave (Student)
   - Submit Complaint (Student)
   - Pay Additional Fees (Student)
   ↓
10. Checkout (Admin deallocates room)
```

### Fee Collection Flow

```
1. Configure Fee Structure (Admin)
   - Set room rent, mess charges, maintenance
   - Set semester dates
   
2. Assign Fees to Students (Admin or Auto)
   - Per student fee record created
   - Due date set
   
3. Student Pays Fees (Student)
   - View fees online
   - Pay via Razorpay
   - Auto-update balance
   
4. Track Payments (Admin)
   - View payment status
   - Send reminders for outstanding dues
   - Generate collection reports
```

---

## 🔐 Security Features

1. **Role-Based Access Control**
   - Super Admin → All hostels
   - Hostel Admin → Their hostel only
   - Block Warden → Their block only
   - Student → Their own data only

2. **Database Security**
   - Prepared statements (SQL injection prevention)
   - Password hashing (bcrypt)
   - Session management with timeout

3. **Payment Security**
   - Razorpay signature verification
   - Server-side payment validation
   - No card data storage

4. **XSS Protection**
   - HTML escaping for user input
   - Sanitized output

---

## 💾 Database

### Important Tables

**Students**
```sql
- enrollment_no (unique, permanent)
- full_name
- email
- mobile
- department, semester, section
- gender (for hostel assignment)
- status (active, graduated, left)
- kyc_verified
```

**Room Allocations**
```sql
- student_id
- room_id
- bed_position_id
- allocated_from, allocated_till
- allocation_type (yearly/semester)
- status (active/inactive/completed)
```

**Student Fees**
```sql
- student_id
- semester_no
- academic_year
- total_amount
- amount_paid
- balance_due
- payment_status (pending/partial/complete)
```

**Complaints**
```sql
- student_id
- complaint_type
- complaint_description
- priority (low/medium/high/urgent)
- status (open/in_progress/resolved)
- assigned_to_admin_id
- resolution_notes
```

---

## 🎨 Design & Theme

**Color Scheme (Maintained from Alluri Resorts):**
- Primary: Maroon Red `#8b0000`
- Secondary: Forest Green `#2d5f3f`
- Accent: Amber `#d97706`
- Success: Emerald `#10b981`
- Danger: Red `#ef4444`

**Design Elements:**
- Gradient backgrounds
- Card-based layouts
- Professional modals with blur backdrop
- Responsive design (mobile, tablet, desktop)
- Smooth transitions and animations

---

## 📱 Responsive Design

- **Mobile:** Stacked layouts, touch-friendly
- **Tablet:** 2-column grids
- **Desktop:** Multi-column layouts with sidebars

---

## 🔧 Configuration

### Hostel Settings
Located in database table `hostel_settings`:

```
- check_in_time: "15:00"
- check_out_time: "10:00"
- visiting_hours_start: "10:00"
- visiting_hours_end: "18:00"
- lights_off_time: "22:00"
- max_guests_allowed: 3
- late_fee_percentage: 5
```

### Mess Menu
Managed via `admin/menu.php`:
- Daily meal planning
- Dietary information
- Meal timings
- Hostel-specific menus

---

## 📊 Reports Available

1. **Occupancy Report**
   - Per hostel, block, floor
   - Occupancy percentage
   - Available rooms

2. **Fee Collection Report**
   - Total fees collected
   - Outstanding dues
   - Payment method breakdown
   - Defaulter list

3. **Complaint Report**
   - Complaints by type
   - Average resolution time
   - By priority level

4. **Student Report**
   - Demographics
   - Department distribution
   - Semester-wise distribution

5. **Revenue Report**
   - Total fees collected
   - Hostel-wise revenue
   - Year-on-year comparison

---

## 🚨 Troubleshooting

### Common Issues

**Problem:** Student can't login
- Check enrollment number in database
- Verify student status = 'active'
- Check if KYC is verified

**Problem:** Room already allocated
- Verify no overlapping allocations
- Check bed_position availability
- Check room status

**Problem:** Fee payment fails
- Verify Razorpay keys
- Check payment amount
- Verify HTTPS enabled

**Problem:** Leave approval not working
- Check admin has hostel_id assigned
- Verify leave status values
- Check activity logs

---

## 📞 Support

### For Administrators:
1. Check Admin Guide (ADMIN_GUIDE.md)
2. Review activity logs for errors
3. Check database for data consistency
4. Contact system administrator

### For Students:
1. Check Student Guide (STUDENT_GUIDE.md)
2. Contact hostel warden
3. Check FAQ page
4. Email: hostel@college.edu

---

## 🔄 Maintenance

### Regular Tasks:
- [ ] Weekly backup of database
- [ ] Monitor error logs
- [ ] Check complaint resolution
- [ ] Verify fee collection
- [ ] Update hostel rules as needed
- [ ] Archive old complaints

### Periodic Tasks:
- [ ] Monthly reports generation
- [ ] Semester-end fee reconciliation
- [ ] Academic year data archival
- [ ] Performance optimization

---

## 🎯 Next Steps

1. **Setup Database** - Run schema scripts
2. **Configure Admin** - Create admin accounts
3. **Import Students** - Bulk upload student data
4. **Setup Hostels** - Create hostels, blocks, rooms
5. **Configure Fees** - Set fee structure
6. **Test System** - Run through all workflows
7. **Train Admins** - Conduct training session
8. **Launch** - Go live with students

---

## 📝 Documentation

- **MIGRATION_GUIDE.md** - Detailed migration steps
- **IMPLEMENTATION_PLAN.md** - Week-by-week plan
- **ADMIN_GUIDE.md** - (To be created) Admin documentation
- **STUDENT_GUIDE.md** - (To be created) Student guide
- **API_DOCUMENTATION.md** - (To be created) API endpoints

---

## 🔗 Related Files

- Database Schema: `database/college_hostel_schema.sql`
- Configuration: `config/hostel.php`
- Migration Notes: `MIGRATION_GUIDE.md`
- Implementation: `IMPLEMENTATION_PLAN.md`

---

## 📄 License & Rights

This system is customized for college hostel management. Original design inspired by Alluri Resorts booking system.

---

## 📞 Contact

- **System Administrator:** [Your Email]
- **Hostel Warden:** [Warden Email]
- **Technical Support:** [Tech Support Email]

---

**Version:** 2.0 (College Hostel Edition)  
**Last Updated:** 2024  
**Status:** Implementation Ready  
**Next Review:** Post-Launch
