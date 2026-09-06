Absolutely. I’d treat the following as the **technical blueprint for HITAM Hostel V1**. This is detailed enough to hand to an AI coding IDE and use as the architectural contract throughout development.

# HITAM Hostel Portal — Technical Blueprint

**Version:** V1
**Institution:** Hyderabad Institute of Technology and Management (HITAM)
**Initial hostels:** Boys Hostel, Girls Hostel
**Future:** New Boys Hostel, added through Admin
**Architecture:** Laravel monolith + MySQL/InnoDB + Blade/HTML/CSS/JS
**Hosting:** Hostinger
**Future app:** Student mobile app consuming Laravel API
**External automation:** None in V1

---

# 1. Core Architecture

```text
                         ┌───────────────────────────┐
                         │       HITAM HOSTEL        │
                         │          PORTAL           │
                         └─────────────┬─────────────┘
                                       │
                 ┌─────────────────────┼─────────────────────┐
                 │                     │                     │
                 ▼                     ▼                     ▼
        ┌────────────────┐    ┌────────────────┐    ┌────────────────┐
        │ PUBLIC WEBSITE │    │ STUDENT SYSTEM │    │ ADMIN SYSTEM   │
        │                │    │                │    │                │
        │ Blade/HTML     │    │ Blade/HTML     │    │ Blade/HTML     │
        │ CSS + JS       │    │ CSS + JS       │    │ CSS + JS       │
        └───────┬────────┘    └───────┬────────┘    └───────┬────────┘
                │                     │                     │
                └─────────────────────┼─────────────────────┘
                                      │
                              ┌───────▼───────┐
                              │    LARAVEL    │
                              │               │
                              │ Routes        │
                              │ Controllers   │
                              │ Services      │
                              │ Policies      │
                              │ Models        │
                              └───────┬───────┘
                                      │
                              ┌───────▼───────┐
                              │ MySQL InnoDB  │
                              └───────────────┘
```

The crucial architectural decision:

> **One Laravel application, one database, three interfaces.**

Don't create three separate applications.

---

# 2. Technology Stack

## Backend

```text
PHP 8.x
Laravel 12.x or current Hostinger-compatible Laravel release
Eloquent ORM
Laravel Blade
Laravel Validation
Laravel Policies
Laravel Sanctum
```

Use the latest Laravel version that is **fully supported by the PHP version available on the Hostinger plan** rather than forcing a version that creates deployment problems.

## Database

```text
MySQL
Storage Engine: InnoDB
```

## Frontend

```text
HTML5
CSS3
JavaScript ES6+
Bootstrap 5
Blade
```

Optional lightweight libraries:

```text
Bootstrap Icons
Chart.js
DataTables
```

Don't load large frontend frameworks unnecessarily.

---

# 3. Laravel Application Structure

The application should follow a clean Laravel structure.

```text
app/
├── Http/
│   ├── Controllers/
│   │   ├── Public/
│   │   ├── Student/
│   │   ├── Admin/
│   │   └── Api/
│   │
│   ├── Middleware/
│   ├── Requests/
│   │   ├── Student/
│   │   └── Admin/
│   │
│   └── Resources/
│
├── Models/
│
├── Policies/
│
├── Services/
│
└── Providers/

database/
├── migrations/
├── seeders/
└── factories/

resources/
├── views/
│   ├── layouts/
│   ├── components/
│   ├── public/
│   ├── student/
│   └── admin/
│
├── css/
└── js/

routes/
├── web.php
├── api.php
└── auth.php

storage/
└── app/
```

---

# 4. Controllers

Organize controllers by responsibility.

```text
app/Http/Controllers/

Public/
├── HomeController
├── HostelController
├── FacilityController
├── MessController
├── RuleController
├── NoticeController
├── EventController
├── GalleryController
├── DownloadController
├── FaqController
└── ContactController

Student/
├── DashboardController
├── ProfileController
├── RoomController
├── LeaveController
├── ComplaintController
├── VisitorController
├── NoticeController
└── NotificationController

Admin/
├── DashboardController
├── HostelController
├── BlockController
├── FloorController
├── RoomController
├── BedController
├── StudentController
├── AllocationController
├── WardenController
├── LeaveController
├── ComplaintController
├── VisitorController
├── NoticeController
├── EventController
├── GalleryController
├── DownloadController
├── ReportController
├── UserController
└── SettingsController
```

---

# 5. Services Layer

Don't put complex business logic directly into controllers.

For example:

```text
app/Services/

AllocationService.php
LeaveService.php
ComplaintService.php
VisitorService.php
HostelService.php
RoomService.php
NotificationService.php
ReportService.php
```

Example:

```text
AllocationController
        ↓
AllocationService
        ↓
Validate hostel
Validate room
Validate bed
Validate student
Create allocation
Update bed
Record audit log
```

This becomes extremely useful when the mobile app arrives.

The mobile API can call the **same service**.

---

# 6. Database ER Architecture

The central structure is:

```text
HOSTEL
  │
  ├── BLOCK
  │     │
  │     └── FLOOR
  │           │
  │           └── ROOM
  │                 │
  │                 └── BED
  │
  ├── WARDEN
  │
  ├── NOTICE
  ├── EVENT
  └── GALLERY

STUDENT
  │
  ├── USER
  ├── ALLOCATION
  ├── LEAVE
  ├── COMPLAINT
  └── VISITOR REQUEST
```

---

# 7. `users`

Authentication table.

```text
users
────────────────────
id
name
email
password
role_id
phone
status
remember_token
created_at
updated_at
```

`status`:

```text
active
inactive
suspended
```

---

# 8. `roles`

```text
roles
────────────────
id
name
description
created_at
updated_at
```

V1:

```text
1  Super Admin
2  Hostel Admin
3  Warden
4  Student
```

---

# 9. `hostels`

This is one of the most important tables.

```text
hostels
────────────────────────
id
name
code
type
description
capacity
status
address
contact_phone
image
created_at
updated_at
deleted_at
```

Example:

```text
1 | Boys Hostel | BOYS
2 | Girls Hostel | GIRLS
3 | New Boys Hostel | BOYS
```

`status`:

```text
coming_soon
active
inactive
```

Use Laravel SoftDeletes where appropriate.

---

# 10. `blocks`

```text
blocks
────────────────
id
hostel_id
name
code
description
status
created_at
updated_at
deleted_at
```

Relationship:

```text
Hostel hasMany Blocks
Block belongsTo Hostel
```

---

# 11. `floors`

```text
floors
────────────────
id
block_id
name
floor_number
status
created_at
updated_at
```

---

# 12. `rooms`

```text
rooms
────────────────────
id
floor_id
room_number
room_type
capacity
status
maintenance_reason
maintenance_start_date
maintenance_expected_end_date
notes
created_at
updated_at
deleted_at
```

Status:

```text
active
maintenance
inactive
```

### Important constraint

A room in `maintenance` or `inactive` status cannot receive a new allocation.

---

# 13. `beds`

```text
beds
────────────────
id
room_id
bed_number
status
created_at
updated_at
```

Status:

```text
available
occupied
maintenance
inactive
```

The system should calculate room occupancy from beds rather than trusting manually entered numbers.

---

# 14. `students`

Student-specific information.

```text
students
──────────────────────────
id
user_id
student_id
roll_number
department
year
section
phone
email
date_of_birth
gender
guardian_name
guardian_relationship
guardian_phone
address
profile_photo
status
created_at
updated_at
deleted_at
```

Don't duplicate password information here.

Authentication belongs to `users`.

---

# 15. `staff`

Generic staff information.

```text
staff
────────────────
id
user_id
employee_id
designation
phone
status
created_at
updated_at
```

---

# 16. `wardens`

A warden is a staff member.

```text
wardens
────────────────
id
staff_id
hostel_id
is_primary
assigned_from
assigned_until
status
created_at
updated_at
```

This allows:

```text
Boys Hostel
 ├── Primary Warden
 └── Assistant Warden
```

and preserves historical assignments.

---

# 17. `room_allocations`

Do **not** put `room_id` directly into `students`.

Use an allocation table.

```text
room_allocations
────────────────────────
id
student_id
hostel_id
block_id
floor_id
room_id
bed_id
allocated_from
allocated_until
status
allocated_by
notes
created_at
updated_at
```

Status:

```text
active
completed
cancelled
```

Only one active allocation should exist for a student.

Only one active student should occupy a bed.

---

# 18. Leave System

### `leave_requests`

```text
leave_requests
────────────────────────
id
student_id
leave_type
from_date
to_date
reason
destination
contact_number
document_path
status
reviewed_by
reviewed_at
review_remarks
created_at
updated_at
```

Status:

```text
pending
approved
rejected
cancelled
completed
```

---

# 19. Complaints

### `complaint_categories`

```text
id
name
description
status
```

### `complaints`

```text
complaints
──────────────────────
id
student_id
category_id
hostel_id
room_id
title
description
priority
image_path
status
assigned_to
resolution_notes
resolved_at
closed_at
created_at
updated_at
```

Priority:

```text
low
medium
high
urgent
```

Status:

```text
submitted
assigned
in_progress
resolved
closed
```

---

# 20. Visitor System

```text
visitor_requests
────────────────────────
id
student_id
visitor_name
relationship
phone
visit_date
expected_time
purpose
status
approved_by
approved_at
check_in_at
check_out_at
remarks
created_at
updated_at
```

---

# 21. Notices

```text
notices
────────────────────
id
title
slug
content
category
target_type
hostel_id
published_at
expires_at
status
created_by
created_at
updated_at
```

`target_type`:

```text
all
hostel
```

If `hostel`, use `hostel_id`.

This allows one notice to target all students or a specific hostel.

---

# 22. Events

```text
events
────────────────
id
title
slug
description
hostel_id
venue
event_date
start_time
end_time
image
status
created_by
created_at
updated_at
```

---

# 23. Gallery

```text
gallery_albums
────────────────
id
title
slug
description
hostel_id
cover_image
status
created_at
updated_at
```

```text
gallery_images
────────────────
id
album_id
image_path
caption
sort_order
created_at
updated_at
```

---

# 24. Downloads

```text
downloads
────────────────
id
title
description
category
file_path
file_name
file_size
status
uploaded_by
created_at
updated_at
```

---

# 25. Facilities

```text
facilities
────────────────
id
name
slug
description
icon
image
hostel_id
status
sort_order
created_at
updated_at
```

`hostel_id = NULL` means common/public facility.

---

# 26. Rules

```text
rules
────────────────
id
title
content
category
hostel_id
status
sort_order
created_at
updated_at
```

Again:

```text
hostel_id = NULL
```

means common rule.

---

# 27. Mess

V1 doesn't need an elaborate mess management system.

Use:

```text
mess_menus
────────────────
id
hostel_id
menu_date
breakfast
lunch
snacks
dinner
status
created_by
created_at
updated_at
```

---

# 28. Notifications

These are **internal notifications only**.

```text
notifications
────────────────────
id
user_id
title
message
type
related_type
related_id
read_at
created_at
updated_at
```

Examples:

```text
Leave Approved
Complaint Updated
New Notice
Visitor Approved
```

No email/WhatsApp/SMS.

---

# 29. Audit Logs

```text
audit_logs
────────────────────────
id
user_id
action
entity_type
entity_id
old_values
new_values
ip_address
created_at
```

Examples:

```text
Admin created hostel
Admin changed room status
Warden approved leave
Admin transferred student
```

For `old_values` and `new_values`, JSON is appropriate.

---

# 30. Database Relationships

The major Laravel relationships:

```text
Hostel
 ├── hasMany(Block)
 ├── hasMany(Warden)
 ├── hasMany(RoomAllocation)
 ├── hasMany(Notice)
 ├── hasMany(Event)
 ├── hasMany(Facility)
 └── hasMany(Rule)

Block
 ├── belongsTo(Hostel)
 └── hasMany(Floor)

Floor
 ├── belongsTo(Block)
 └── hasMany(Room)

Room
 ├── belongsTo(Floor)
 ├── hasMany(Bed)
 └── hasMany(RoomAllocation)

Bed
 ├── belongsTo(Room)
 └── hasMany(RoomAllocation)

Student
 ├── belongsTo(User)
 ├── hasMany(RoomAllocation)
 ├── hasMany(LeaveRequest)
 ├── hasMany(Complaint)
 └── hasMany(VisitorRequest)
```

---

# 31. Route Architecture

## Public

```text
/
 /about
 /hostels
 /hostels/{slug}
 /facilities
 /mess
 /rules
 /notices
 /notices/{slug}
 /events
 /events/{slug}
 /gallery
 /downloads
 /faq
 /contact
```

---

# 32. Student Routes

Prefix:

```text
/student
```

```text
/student
/student/profile
/student/room

/student/leaves
/student/leaves/create
/student/leaves/{id}

/student/complaints
/student/complaints/create
/student/complaints/{id}

/student/visitors
/student/visitors/create
/student/visitors/{id}

/student/notices
/student/notifications
```

All protected by:

```text
auth
role:student
```

---

# 33. Admin Routes

Prefix:

```text
/admin
```

```text
/admin/dashboard

/admin/hostels
/admin/hostels/create
/admin/hostels/{id}/edit

/admin/blocks
/admin/floors
/admin/rooms
/admin/beds

/admin/students
/admin/students/{id}

/admin/allocation
/admin/allocation/create
/admin/allocation/{id}

/admin/wardens
/admin/wardens/{id}

/admin/leaves
/admin/complaints
/admin/visitors

/admin/notices
/admin/events
/admin/gallery
/admin/downloads

/admin/reports
/admin/users
/admin/settings
/admin/audit-logs
```

---

# 34. Future Student API

Even though the app isn't being built in V1, reserve:

```text
/api/v1/
```

For example:

```text
/api/v1/student/dashboard
/api/v1/student/profile
/api/v1/student/room
/api/v1/student/leaves
/api/v1/student/complaints
/api/v1/student/visitors
/api/v1/student/notices
/api/v1/student/notifications
```

Authentication should eventually use **Laravel Sanctum**.

Don't expose admin APIs to students.

---

# 35. Authorization

Authentication answers:

> Who are you?

Authorization answers:

> What are you allowed to do?

Use both.

Example:

```text
Student
  ↓
Can view own profile
Can create own leave
Can view own complaints

Cannot:
Edit hostel
Edit room
Approve leave
View other students' private data
```

Warden:

```text
Can manage assigned hostel
```

Admin:

```text
Can manage all hostels
```

Super Admin:

```text
Everything
```

Use Laravel **Policies/Gates**, not only frontend hiding.

---

# 36. Critical Business Rules

These should be treated as non-negotiable.

### Room allocation

```text
IF room.status != active
    reject allocation
```

### Bed allocation

```text
IF bed.status != available
    reject allocation
```

### Student allocation

```text
IF student already has active allocation
    reject new allocation
```

### Hostel

```text
IF hostel.status != active
    reject allocation
```

### Maintenance

```text
Room → Maintenance
       ↓
No new allocations
```

### Warden

```text
Only active wardens can be assigned.
```

### Leave

```text
Student cannot edit approved/rejected request.
```

### Complaint

```text
Closed complaint cannot be modified by student.
```

These validations must happen **server-side**, not just JavaScript.

---

# 37. UI Architecture

Use a shared design system.

```text
resources/views/

layouts/
├── public.blade.php
├── student.blade.php
└── admin.blade.php

components/
├── navbar.blade.php
├── footer.blade.php
├── alert.blade.php
├── modal.blade.php
├── card.blade.php
├── badge.blade.php
├── table.blade.php
├── pagination.blade.php
└── empty-state.blade.php
```

This prevents duplicate HTML everywhere.

---

# 38. Student Layout

Desktop:

```text
┌─────────────────────────────────────┐
│ HITAM HOSTELS                  👤   │
├────────────┬────────────────────────┤
│ Dashboard  │                        │
│ My Room    │       CONTENT          │
│ Leave      │                        │
│ Complaints │                        │
│ Visitors   │                        │
│ Notices    │                        │
│ Profile    │                        │
└────────────┴────────────────────────┘
```

Mobile:

```text
┌──────────────────────┐
│ HITAM HOSTELS     ☰  │
├──────────────────────┤
│                      │
│      CONTENT         │
│                      │
├──────────────────────┤
│ Home Room Leave Alerts│
└──────────────────────┘
```

That bottom navigation is intentional because it will translate well into the eventual app.

---

# 39. Admin UI

Admin gets a conventional dashboard:

```text
┌────────────┬─────────────────────────┐
│ Dashboard  │                         │
│ Hostels    │      Dashboard          │
│ Rooms      │                         │
│ Students   │      Statistics         │
│ Allocation │                         │
│ Wardens    │      Tables             │
│ Leave      │      Charts             │
│ Complaints │                         │
│ Visitors   │                         │
│ Content    │                         │
│ Reports    │                         │
└────────────┴─────────────────────────┘
```

---

# 40. Design System

## Primary

Deep HITAM blue/navy.

## Neutrals

```text
Background → #F7F8FA-ish
Surface → White
Text → Dark charcoal
Secondary text → Slate
Borders → Light grey
```

## Accent

A restrained HITAM-compatible warm accent.

Use it for:

* active navigation
* small highlights
* selected states
* important CTA

Not entire cards or backgrounds.

---

# 41. Typography

Use one primary modern sans-serif family.

For example:

**Inter**

or another clean system-compatible sans-serif.

Hierarchy:

```text
H1 → strong
H2 → medium/semibold
H3 → semibold
Body → regular
Labels → medium
```

Avoid using 4–5 fonts.

---

# 42. Component Rules

Cards:

```text
border-radius: moderate
subtle border
very light shadow
```

Buttons:

```text
Primary
Secondary
Outline
Danger
```

Don't create a different button style for every page.

Status badges:

```text
ACTIVE
PENDING
APPROVED
REJECTED
MAINTENANCE
RESOLVED
```

Use consistent visual language.

---

# 43. Responsive Breakpoints

Build mobile-first.

At minimum:

```text
Mobile
Tablet
Desktop
Large Desktop
```

Test specifically around:

```text
360px
390px
768px
1024px
1440px
```

Don't rely on desktop layouts simply shrinking down.

---

# 44. Authentication Architecture

```text
/login
    ↓
Authentication
    ↓
Determine role
    │
    ├── Student → /student
    ├── Warden → /admin
    ├── Hostel Admin → /admin
    └── Super Admin → /admin
```

Use Laravel's authentication system rather than implementing custom password authentication.

Passwords:

* Never store plaintext
* Laravel password hashing
* Session-based authentication for web

---

# 45. Admin vs Warden Scope

This is important.

A warden should not automatically see every hostel.

For example:

```text
Warden A
 ↓
Boys Hostel
```

They should see:

```text
Boys students
Boys rooms
Boys leave requests
Boys complaints
Boys visitors
```

but not necessarily:

```text
Girls Hostel
```

unless explicitly authorized.

This should be enforced server-side.

---

# 46. Admin Dashboard Data

Dashboard should dynamically calculate:

```text
Total Hostels
Active Hostels

Total Students
Boys Students
Girls Students

Total Rooms
Available Rooms
Maintenance Rooms

Total Beds
Occupied Beds
Available Beds

Pending Leaves
Open Complaints
Pending Visitors
```

Don't store these as manually maintained numbers.

Calculate/query them from the database.

---

# 47. Hostel Expansion

This architecture handles:

```text
2026
Boys
Girls
```

Then:

```text
2027
Boys
Girls
New Boys
```

Then potentially:

```text
2028
Boys
Girls
New Boys
International Hostel
PG Block
Staff Quarters
```

without restructuring the database.

---

# 48. File Handling

Use Laravel storage:

```text
storage/app/public/
```

Organize:

```text
hostels/
gallery/
downloads/
student-documents/
complaints/
profiles/
```

Create the public storage link:

```text
php artisan storage:link
```

Validate uploads:

```text
Allowed image types
jpg
jpeg
png
webp
```

Documents:

```text
pdf
```

Set sensible file-size limits.

---

# 49. Security Requirements

This should be taken seriously because student data is involved.

### Must have

* CSRF protection
* Password hashing
* Authentication middleware
* Authorization policies
* Server-side validation
* SQL injection protection through Eloquent/query builder
* XSS protection
* Secure file upload validation
* Session security
* Rate limiting on login
* HTTPS
* Secure `.env`
* No database credentials in Git
* No sensitive information in frontend JavaScript

---

# 50. Privacy

Students should only see information necessary for their own account.

For example, don't expose:

```text
Other student's phone
Guardian phone
Address
Documents
```

unless the college specifically requires it.

Roommates can show limited information such as:

> Name · Department · Year

rather than their entire profile.

---

# 51. Auditability

Actions that should generate audit logs:

```text
Create hostel
Edit hostel
Deactivate hostel

Add room
Remove room
Put room into maintenance
Restore room

Add student
Edit student
Deactivate student

Allocate student
Transfer student
Vacate student

Assign warden
Remove warden

Approve leave
Reject leave

Update complaint
Resolve complaint

Approve visitor
Reject visitor
```

---

# 52. V1 Development Order

This is the order I strongly recommend.

## Phase 1 — Foundation

```text
Laravel installation
Database connection
Environment setup
Authentication
Roles
Permissions
Base layouts
Design system
```

## Phase 2 — Hostel Structure

```text
Hostels
Blocks
Floors
Rooms
Beds
Wardens
```

## Phase 3 — Students

```text
Student management
Student profiles
Allocation
Allocation history
```

## Phase 4 — Student Portal

```text
Dashboard
Profile
Room
Leave
Complaints
Visitors
Notices
Notifications
```

## Phase 5 — Admin Operations

```text
Dashboard
Leave management
Complaint management
Visitor management
Warden management
Room maintenance
```

## Phase 6 — Public Website

```text
Home
Hostels
Facilities
Rules
Mess
Notices
Events
Gallery
Downloads
FAQ
Contact
```

## Phase 7 — Content Management

```text
Notice CMS
Event CMS
Gallery CMS
Downloads CMS
```

## Phase 8 — Reports & Audit

```text
Reports
Statistics
Audit logs
```

## Phase 9 — Testing

```text
Authentication testing
Role testing
Allocation testing
Mobile testing
Security testing
Database integrity testing
```

## Phase 10 — Deployment

```text
Production environment
Database migration
Storage
HTTPS
Backups
Cron if required
Final testing
```

---

# 53. Testing Checklist

Before calling V1 complete:

### Authentication

* Student login works
* Admin login works
* Invalid credentials rejected
* Unauthorized pages blocked
* Session logout works

### Allocation

Test:

```text
Student → available bed → SUCCESS

Student → occupied bed → FAIL

Student → maintenance room → FAIL

Student → inactive room → FAIL

Already allocated student → new allocation → FAIL
```

### Leave

```text
Student creates leave
↓
Admin sees it
↓
Admin approves
↓
Student sees APPROVED
```

### Complaints

```text
Student creates
↓
Admin assigns
↓
Status changes
↓
Student sees update
```

### Hostel

```text
Admin creates New Boys Hostel
↓
Adds blocks
↓
Adds rooms
↓
Adds beds
↓
Assigns warden
↓
Activates hostel
↓
Public website displays hostel
```

This last test proves the architecture works.

---

# 54. Future Mobile App Contract

When the app is eventually built, the API should expose:

```text
Authentication
    ↓
GET student profile
GET room
GET roommates
GET notices
GET notifications
GET leaves
GET complaints
GET visitors

POST leave
POST complaint
POST visitor request
```

The backend business logic remains:

```text
Mobile App
     ↓
Laravel API
     ↓
Services
     ↓
Eloquent
     ↓
MySQL
```

So the mobile app is **not a second backend**.

---

# 55. What Should NOT Be Done

This is just as important as the architecture.

### ❌ Don't

Create separate databases for Boys/Girls.

### ❌ Don't

Create separate Laravel applications.

### ❌ Don't

Hard-code hostel names throughout Blade files.

### ❌ Don't

Store room information directly in student records.

### ❌ Don't

Hard-delete historical allocations.

### ❌ Don't

Put business rules only in JavaScript.

### ❌ Don't

Put complicated business logic directly inside controllers.

### ❌ Don't

Expose admin functionality through hidden frontend buttons alone.

### ❌ Don't

Build the student app now.

### ❌ Don't

Introduce React/Node/Firebase simply because a mobile app may come later.

---

# 56. Final Architecture in One Diagram

```text
                              ┌───────────────────┐
                              │   HITAM HOSTEL    │
                              │      PORTAL       │
                              └─────────┬─────────┘
                                        │
              ┌─────────────────────────┼─────────────────────────┐
              │                         │                         │
              ▼                         ▼                         ▼
       ┌─────────────┐          ┌──────────────┐          ┌──────────────┐
       │   PUBLIC    │          │   STUDENT    │          │    ADMIN     │
       │   WEBSITE   │          │    PORTAL    │          │    PORTAL    │
       └──────┬──────┘          └──────┬───────┘          └──────┬───────┘
              │                        │                         │
              └────────────────────────┼─────────────────────────┘
                                       │
                               ┌───────▼───────┐
                               │    LARAVEL    │
                               ├───────────────┤
                               │ Controllers   │
                               │ Services      │
                               │ Models        │
                               │ Policies      │
                               │ Validation    │
                               └───────┬───────┘
                                       │
                               ┌───────▼───────┐
                               │ MYSQL INNODB  │
                               ├───────────────┤
                               │ Users         │
                               │ Hostels       │
                               │ Blocks        │
                               │ Floors        │
                               │ Rooms         │
                               │ Beds          │
                               │ Students      │
                               │ Allocations   │
                               │ Wardens       │
                               │ Leaves        │
                               │ Complaints    │
                               │ Visitors      │
                               │ Notices       │
                               │ Events        │
                               │ Gallery       │
                               │ etc.          │
                               └───────────────┘
                                       ▲
                                       │
                              Future Student App
                                       │
                               ┌───────┴───────┐
                               │ Laravel API   │
                               │ /api/v1/*     │
                               └───────────────┘
```

# 57. The final product definition

So, **this is what I would now consider locked as the HITAM Hostel V1 architecture**:

> **A Laravel-based, MySQL/InnoDB hostel information and management platform consisting of a public information website, a mobile-first student portal, and a web-based administration/warden portal. The system is multi-hostel by design, with hostels, blocks, floors, rooms, beds, wardens and students managed dynamically through the database. Room maintenance, allocation history, leave, complaints and visitor workflows are built into V1. No external automation is included. The student portal is API-ready so it can later be converted into a dedicated mobile application without replacing the Laravel backend or database.**

The **next artifact I would create before coding is the actual database schema/migration specification** — every table, column, datatype, nullable/required status, foreign key, index, unique constraint, enum/status and relationship. That should become the definitive source of truth before Antigravity or another coding agent starts generating Laravel code.
