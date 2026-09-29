# HITAM Hostel — Production Hosting & Deployment Guide

This guide covers deploying the HITAM Hostel residential management system to **Hostinger (cPanel / hPanel)**, **cPanel Shared Hosting**, or an **Ubuntu VPS / Cloud Server**.

---

## 1. What's Included in `to_upload.zip`

The archive `to_upload.zip` contains:
- Complete Laravel 11 backend code (`app/`, `bootstrap/`, `config/`, `database/`, `public/`, `resources/`, `routes/`).
- Updated Blade views (Admin scholar profiles, Room selection removed from admission, 15-minute inactivity auto-logout, fade transitions).
- Document vault columns and OTP security migrations.
- Complete `.env.production.example` file ready for configuration.
- Excluded to keep the upload fast and clean: `vendor/`, `node_modules/`, `.git/`, `mobile_app/`, `apk/`, `scratch/`, and local log files.

---

## 2. Option A: Hosting on Hostinger / cPanel Shared Hosting (Recommended & Fastest)

### Step 1: Upload and Extract `to_upload.zip`
1. Log in to **Hostinger hPanel** or **cPanel**.
2. Open **File Manager**.
3. Locate your domain root folder:
   - For main domain: usually `public_html/` or a folder parallel to `public_html/` (recommended architecture for Laravel: put the Laravel files one level *above* `public_html/`, or inside a subfolder `hostel/`).
   - If using standard cPanel:
     - Upload `to_upload.zip` to your account home directory (e.g. `/home/u123456789/`).
     - Extract `to_upload.zip` into a directory called `laravel_app/`.
     - Move the contents of `laravel_app/public/*` into `public_html/`.
     - Edit `public_html/index.php` and update the paths:
       ```php
       // Change from:
       require __DIR__.'/../vendor/autoload.php';
       $app = require_once __DIR__.'/../bootstrap/app.php';

       // To:
       require __DIR__.'/../laravel_app/vendor/autoload.php';
       $app = require_once __DIR__.'/../laravel_app/bootstrap/app.php';
       ```
   - **Alternative (Hostinger hPanel Document Root setting):**
     - Extract `to_upload.zip` directly into `domains/yourdomain.com/public_html/`.
     - In Hostinger hPanel -> **Websites** -> **Manage** -> **Website Configuration** -> Set **Public Directory** to `public_html/public`. This keeps your project intact without needing to move files!

---

### Step 2: Setup MySQL Database in cPanel / hPanel
1. In hPanel / cPanel, navigate to **Databases** -> **MySQL Databases**.
2. Create a new Database: e.g. `u123456789_hitam_hostel`.
3. Create a Database User and strong password: e.g. `u123456789_dbuser`.
4. Grant **All Privileges** to this user for the database.
5. Open **phpMyAdmin**:
   - Select your new database.
   - Click the **Import** tab.
   - You can run the optimized schema from [DB.md](file:///d:/Hostel%20Booking/DB.md) (Section 5) OR run the Laravel migrations in Step 4.

---

### Step 3: Configure `.env`
1. In File Manager, find `.env.production.example` and rename it to `.env` (or copy it).
2. Open and fill in the details:
   ```env
   APP_NAME="HITAM Hostel"
   APP_ENV=production
   APP_KEY=                      <-- Will be generated in Step 4
   APP_DEBUG=false
   APP_URL=https://yourhosteldomain.com

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=u123456789_hitam_hostel
   DB_USERNAME=u123456789_dbuser
   DB_PASSWORD=your_actual_db_password

   # Brevo SMTP Configuration (Already verified working)
   MAIL_MAILER=smtp
   MAIL_HOST=smtp-relay.brevo.com
   MAIL_PORT=587
   MAIL_USERNAME=ba87c6001@smtp-brevo.com
   MAIL_PASSWORD=your_brevo_smtp_master_key
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS=jayanthkarthik.enaganti@gmail.com
   MAIL_FROM_NAME="HITAM Hostel Administration"
   ```

---

### Step 4: Run Composer & Artisan Commands via SSH
Connect to your hosting account via SSH (or use Hostinger's built-in **Terminal** in hPanel):
```bash
# 1. Navigate to your project directory
cd ~/domains/yourdomain.com/public_html   # or cd ~/laravel_app

# 2. Install production dependencies (installs the vendor/ folder)
composer install --no-dev --optimize-autoloader

# 3. Generate the application encryption key
php artisan key:generate

# 4. Run database migrations & seeders (if you didn't import SQL manually)
php artisan migrate --force
php artisan db:seed --force

# 5. Link public storage for student documents, photos, and receipts
php artisan storage:link

# 6. Cache config, routes, and views for lightning-fast performance
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

### Step 5: Setup Cron Job (for Outpass Overdue & Notice Cleanup)
In cPanel / hPanel -> **Cron Jobs**:
- Set frequency to **Every Minute** (`* * * * *`).
- Command:
  ```bash
  /usr/bin/php /home/u123456789/domains/yourdomain.com/public_html/artisan schedule:run >> /dev/null 2>&1
  ```

---

## 3. Option B: Hosting on Ubuntu VPS (DigitalOcean / AWS / Linode)

If deploying on an Ubuntu VPS with NGINX:

1. **Install NGINX, PHP 8.2/8.3, and MySQL**:
   ```bash
   sudo apt update && sudo apt install -y nginx php8.2-fpm php8.2-mysql php8.2-mbstring php8.2-xml php8.2-curl php8.2-zip unzip git composer
   ```

2. **Upload & Extract**:
   ```bash
   sudo mkdir -p /var/www/hitam-hostel
   sudo unzip to_upload.zip -d /var/www/hitam-hostel/
   cd /var/www/hitam-hostel
   composer install --no-dev --optimize-autoloader
   cp .env.production.example .env
   # Edit .env with your DB credentials & nano .env
   php artisan key:generate
   php artisan migrate --force --seed
   php artisan storage:link
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

3. **Set Permissions**:
   ```bash
   sudo chown -R www-data:www-data /var/www/hitam-hostel/storage /var/www/hitam-hostel/bootstrap/cache
   sudo chmod -R 775 /var/www/hitam-hostel/storage /var/www/hitam-hostel/bootstrap/cache
   ```

4. **NGINX Server Block (`/etc/nginx/sites-available/hitam-hostel`)**:
   ```nginx
   server {
       listen 80;
       server_name hostel.hitam.org; # or your IP
       root /var/www/hitam-hostel/public;

       add_header X-Frame-Options "SAMEORIGIN";
       add_header X-Content-Type-Options "nosniff";

       index index.php;
       charset utf-8;

       location / {
           try_files $uri $uri/ /index.php?$query_string;
       }

       location = /favicon.ico { access_log off; log_not_found off; }
       location = /robots.txt  { access_log off; log_not_found off; }

       error_page 404 /index.php;

       location ~ \.php$ {
           fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
           fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
           include fastcgi_params;
       }

       location ~ /\.(?!well-known).* {
           deny all;
       }
   }
   ```
5. **Enable site & SSL**:
   ```bash
   sudo ln -s /etc/nginx/sites-available/hitam-hostel /etc/nginx/sites-enabled/
   sudo nginx -t
   sudo systemctl reload nginx
   sudo certbot --nginx -d hostel.hitam.org
   ```

---

## 4. Database Query & Schema Check Summary

| Component | Status | Details |
|---|---|---|
| `otps` Table | Verified & Added | Documents OTP hashing, expiry, purpose, and attempt locking for Brevo mail verification. |
| `students` Table | Verified & Synchronized | Full document vault columns (`photo_url`, `id_proof_url`, `admission_letter_url`, `medical_cert_url`), `permanent_address`, and verification audit columns are fully synced in `DB.md`. |
| `users` Table | Verified | Added `security` role to the enum definition matching code. |
| SQL Views | Verified | `v_room_occupancy`, `v_student_room_snapshot`, `v_hostel_outside_count` are 100% matched with migrations. |
| Production Queries | Verified | Student roommate lookup, gate QR scan verification, and occupancy metrics cataloged. |
