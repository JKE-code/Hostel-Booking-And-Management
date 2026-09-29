# HITAM Hostel Management System — Environment, Software & Dependencies Guide

> **Document Status**: Active  
> **Target Audience**: Developers, Evaluators, and Maintainers  
> **Scope**: Flutter Student Mobile Application (`mobile_app/`) & Laravel Backend

---

## 1. Executive Summary & Root Cause Diagnosis

### Why did `assembleDebug` fail or hang?
When running `flutter run` on your Samsung device (`SM-M515F`), the build failed due to a **JDK and Android Toolchain version mismatch**:

1. **Java Version Conflict**:
   - Android Studio installed **Java 25** (`D:\Android Studio\jbr\bin\java.exe`).
   - Standard Android development (and the Android Gradle Plugin) requires an **LTS Java version: JDK 17 or JDK 21**.
   - Standard Gradle (Gradle 8.x) does **not** support Java 25.
2. **Gradle Version Requirement**:
   - Flutter 3.47.5 enforces a minimum Gradle version of **Gradle 8.14.0+**.
3. **Missing NDK Tooling on AGP 9**:
   - When attempting to run newer Gradle 9 / AGP 9 to accommodate Java 25, AGP required Android NDK's native `llvm-strip` utility to strip native debug symbols from the Flutter engine (`.so` files).
   - In your local Android SDK directory, the NDK folder was empty (only containing a 53-byte `source.properties` file), triggering:
     ```text
     java.io.IOException: Cannot run program "...llvm-strip": CreateProcess error=2, The system cannot find the file specified
     ```

---

## 2. Software & Dependencies Matrix

| Software / Tool | Current Machine Status | Required / Recommended Version | Purpose & Action Needed |
| :--- | :--- | :--- | :--- |
| **Flutter SDK** | `3.47.5 (stable)` | `3.47.5` | Installed at `D:\Flutter\flutter_windows_3.47.5-stable\flutter`. **OK**. |
| **Dart SDK** | `3.13.4` | `3.13.4` | Bundled inside Flutter SDK. **OK**. |
| **Android Studio** | Installed | Ladybug / Koala / Meerkat | Installed at `D:\Android Studio`. **OK**. |
| **Android SDK Platform** | API 35 / 36 | API 34 or 35 | Installed in `AppData\Local\Android\Sdk`. **OK**. |
| **Android Command-line Tools** | `latest` (junction configured) | `latest` | Configured and licenses accepted. **OK**. |
| **JDK (Java Development Kit)** | Java 25 (JBR) & Java 8 | **JDK 17 LTS** or **JDK 21 LTS** | **ACTION REQUIRED**: Install JDK 17 or 21 and tell Flutter to use it. |
| **Gradle** | 8.9 / 9.4 | **8.14.0+** (or 8.14.1) | Managed automatically via `gradle-wrapper.properties`. |
| **Android Gradle Plugin (AGP)** | 8.7.0 / 9.1.0 | **8.7.0** or **8.8.0** | Configured in `settings.gradle.kts`. |
| **Android NDK** | Corrupted / Missing | **27.x or 28.x (Side by side)** | Optional if using standard JDK 17/21; Required if staying on AGP 9. |
| **Connected Device** | `SM M515F` (`RZ8N92HKB7W`) | Android 10+ (Your phone is Android 12) | Connected via USB with USB Debugging enabled. **OK**. |

---

## 3. What to Download & How to Fix (The 2-Minute Solution)

To resolve the issue permanently and maintain a standard, clean Flutter toolchain, follow **Method 1** (Recommended).

---

### Method 1: Install Official JDK 17 LTS (Recommended — Cleanest & Most Stable)

Standard Android & Flutter tooling is officially certified for **Java 17 LTS**. Installing this eliminates all Gradle 9 / AGP 9 / NDK strip errors.

#### Step 1: Download & Install OpenJDK 17 LTS
1. Download the official **Eclipse Temurin JDK 17 (LTS)** Windows MSI installer:
   - **Direct Download Link**: [Eclipse Temurin OpenJDK 17.0.14 x64 Installer](https://github.com/adoptium/temurin17-binaries/releases/download/jdk-17.0.14%2B7/OpenJDK17U-jdk_x64_windows_hotspot_17.0.14_7.msi)
   *(Or download Amazon Corretto 17 from [Amazon Corretto 17 Windows Installer](https://corretto.aws/downloads/latest/amazon-corretto-17-x64-windows-jdk.msi))*.
2. Run the installer `.msi`. Follow the prompt and click **Next → Install** (keep default path: `C:\Program Files\Eclipse Adoptium\jdk-17.0.14.7-hotspot`).

#### Step 2: Configure Flutter to use JDK 17
Open Command Prompt or PowerShell and run:
```shell
flutter config --jdk-dir "C:\Program Files\Eclipse Adoptium\jdk-17.0.14.7-hotspot"
```

#### Step 3: Run the App
From the `d:\Hostel Booking\mobile_app` folder, run:
```shell
flutter run -d RZ8N92HKB7W
```
The app will compile and install on your Samsung phone in under 20 seconds.

---

### Method 2: If You Prefer Staying with Android Studio's Current Setup (Install NDK)

If you do not want to download a separate JDK installer, you must provide the missing `llvm-strip` tool that AGP 9 demands:

1. Open **Android Studio**.
2. Go to **Settings** (or ⚙️ gear icon) → **Languages & Frameworks** → **Android SDK** (or **Tools → SDK Manager**).
3. Click the **SDK Tools** tab at the top.
4. Check the checkbox for **NDK (Side by side)**.
5. Click **Apply** and then **OK**.
6. Wait for Android Studio to download and extract the NDK files (~400 MB).
7. Once finished, run the project again.

---

## 4. Mobile App Production Packaging (Lightweight APK)

To satisfy the requirement that the download finishes in **under 15–30 seconds**:

### Debug APK vs Release APK
- **Debug APK** (`assembleDebug`): **~60 to 70 MB**
  - Includes full Dart JIT compiler, Observatory VM profiler, and hot reload server. Only meant for development.
- **Production Release APK** (`flutter build apk --split-per-abi`): **~15 to 18 MB**
  - Strips all debug overhead and compiles pure ARM64 native machine code.
  - At typical 4G/5G mobile speeds (25–50 Mbps), an 18 MB file downloads in **under 5 to 10 seconds**.

### Command to generate the lightweight APK:
```shell
cd "d:\Hostel Booking\mobile_app"
flutter build apk --release --split-per-abi
```
The output file will be generated at:
```text
mobile_app/build/app/outputs/flutter-apk/app-arm64-v8a-release.apk
```
This file is copied to `d:\Hostel Booking\apk\hitam-hostel-student-v1.0.0.apk` for distribution.

---

## 5. Upcoming: Part 3 (Backend & Database Setup) Requirements

When you are ready to proceed with **Part 3 (Laravel Backend & MySQL Database Integration)**:

| Software | Requirement | Current Status on PC | Notes |
| :--- | :--- | :--- | :--- |
| **XAMPP / MySQL** | MySQL 8.0+ / MariaDB 10.4+ | Installed at `D:\xampp` | Start Apache and MySQL via XAMPP Control Panel. |
| **PHP** | PHP 8.2 or 8.3 | Installed (via XAMPP or system) | Required for Laravel 11. |
| **Composer** | Composer 2.x | Installed (`d:\Hostel Booking\composer.phar`) | PHP dependency manager. |
| **Project Location** | Anywhere on drive | `d:\Hostel Booking` | **Do NOT move** the folder into `D:\xampp\htdocs`. Laravel runs via `php artisan serve` and connects to MySQL via TCP port `3306`. |

---

## 6. Quick Verification Checklist

- [ ] JDK 17 LTS installed or NDK (Side by side) installed via Android Studio.
- [ ] `flutter doctor -v` shows all green checkmarks.
- [ ] Samsung `SM M515F` is visible under `flutter devices`.
- [ ] `flutter run` launches the HITAM Resident Portal on the physical device.
- [ ] Release APK generated under `apk/` (< 20 MB).
