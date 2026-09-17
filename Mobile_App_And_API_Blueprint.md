# Strategic Plan: Student Mobile App (Android APK), Database Architecture & Unified REST API

## Executive Overview
This document outlines the architecture, database design, API communication layer, and mobile application roadmap for the **HITAM Residential Student Portal**.

As requested:
1. **No authentication gateway for now**: The app will boot directly into the authenticated Student Experience with a pre-configured resident profile (or test switcher), enabling instant testing of all student functions without login friction.
2. **App Store & Play Store Ready**: Native Kotlin structure following Clean Architecture, MVVM, Jetpack Compose / Material Design 3, repository pattern, and standard Android Gradle structure for production signing (`.apk` / `.aab`).
3. **Database Architecture**: Comprehensive relational schema (MySQL / InnoDB / SQLite for local testing) covering hostels, blocks, floors, rooms, beds, students, allocations, leave requests, complaints, notices, and visitors.
4. **Unified REST API**: Built into Laravel (`routes/api.php` and `app/Http/Controllers/Api/`) serving clean JSON responses consumed simultaneously by the Web UI and Mobile App.

---

## 1. System Architecture Diagram

```text
┌────────────────────────────────────────────────────────┐
│                   STUDENT CLIENTS                      │
│                                                        │
│   ┌────────────────────────┐  ┌────────────────────┐   │
│   │   Android Native App   │  │   Web Student UI   │   │
│   │   (Kotlin / Compose)   │  │   (Laravel Blade)  │   │
│   └───────────┬────────────┘  └─────────┬──────────┘   │
└───────────────┼─────────────────────────┼──────────────┘
                │                         │
                ▼                         ▼
         REST API (JSON)            Blade HTTP Routes
     (routes/api.php: /api/v1)      (routes/web.php)
                │                         │
                └───────────┬─────────────┘
                            ▼
           ┌─────────────────────────────────┐
           │      LARAVEL BACKEND LAYER      │
           │  Controllers & Service Layer    │
           │ (LeaveService, ComplaintService)│
           └────────────────┬────────────────┘
                            ▼
           ┌─────────────────────────────────┐
           │         DATABASE LAYER          │
           │     MySQL 8.x / InnoDB          │
           │  (Students, Rooms, Leaves, etc) │
           └─────────────────────────────────┘
```

---

## 2. Structured Database Schema (Migrations & SQL)

The database matches the specifications in `Techincal_Blueprint.md` and `Architecture.md`.

### Core Tables
1. **`hostels`**: Boys Hostel, Girls Hostel, New Boys Block
2. **`blocks`**: Block A, Block B, East Wing, West Wing
3. **`floors`**: Floor 1, Floor 2, Floor 3, Floor 4
4. **`rooms`**: Room numbers, room types (2-sharing, 3-sharing, 4-sharing, AC/Non-AC)
5. **`beds`**: Individual bed identifiers, occupancy status (`available`, `occupied`, `maintenance`)
6. **`students`**: Roll number, branch, semester, phone, parent phone, blood group, emergency contact
7. **`room_allocations`**: Student-to-Bed assignment with history, allocation date, status (`active`, `vacated`)
8. **`leaves`**: Leave outpass requests (Leave type, reason, start date, return date, warden status: `pending`, `approved`, `rejected`)
9. **`complaints`**: Maintenance tickets (Plumbing, Electrical, Wi-Fi, Carpentry, Mess, status: `open`, `in_progress`, `resolved`, warden remarks)
10. **`notices`**: Circular announcements with categories, importance, and attachments
11. **`visitors`**: Gate pass visitors (Visitor name, relation, visit date, check-in, check-out)

---

## 3. Unified REST API Endpoints (`/api/v1`)

| Method | Endpoint | Description |
|---|---|---|
| `GET` | `/api/v1/student/profile` | Resident student details, room, bed & hostel info |
| `GET` | `/api/v1/student/dashboard` | Aggregated dashboard data (room info, latest leaves, complaints, notices) |
| `GET` | `/api/v1/student/room` | Allocated room details, roommates, bed number, block info |
| `GET` | `/api/v1/student/leaves` | List of student's leave requests & status |
| `POST`| `/api/v1/student/leaves` | Submit a new leave / outpass application |
| `GET` | `/api/v1/student/complaints` | List of maintenance complaints |
| `POST`| `/api/v1/student/complaints` | File a new plumbing / electrical / internet complaint |
| `GET` | `/api/v1/student/notices` | Notices and circulars feed |
| `GET` | `/api/v1/student/visitors` | Registered visitors and approval status |
| `POST`| `/api/v1/student/visitors` | Pre-register a parent or visitor |

---

## 4. Android App (Kotlin) Structure

The app will be located in `android/` with full Gradle wrapper and standard packaging:

```text
android/
├── app/
│   ├── build.gradle.kts
│   └── src/main/
│       ├── AndroidManifest.xml
│       ├── java/org/hitam/hostel/
│       │   ├── data/
│       │   │   ├── api/ (Retrofit service & endpoints)
│       │   │   ├── model/ (Student, Room, Leave, Complaint, Notice DTOs)
│       │   │   └── repository/ (StudentRepository)
│       │   ├── ui/
│       │   │   ├── theme/ (Color.kt, Theme.kt - Forest & Emerald HITAM palette)
│       │   │   ├── screens/
│       │   │   │   ├── DashboardScreen.kt
│       │   │   │   ├── MyRoomScreen.kt
│       │   │   │   ├── LeaveScreen.kt (Form & history)
│       │   │   │   ├── ComplaintsScreen.kt (Form & history)
│       │   │   │   ├── NoticesScreen.kt
│       │   │   │   └── ProfileScreen.kt
│       │   │   └── navigation/ (BottomNav, AppNavHost)
│       │   └── MainActivity.kt
│       └── res/ (drawable, values, mipmap icons)
├── build.gradle.kts
├── settings.gradle.kts
└── gradlew / gradlew.bat
```

### Store Readiness Checklist:
- Package Name: `org.hitam.hostel` (or `com.hitam.hostel`)
- Minimum SDK: 26 (Android 8.0 Oreo)
- Target SDK: 34 (Android 14)
- Material 3 Design conforming to Google Play guidelines
- Clear permissions in `AndroidManifest.xml` (`INTERNET`, `ACCESS_NETWORK_STATE`)
