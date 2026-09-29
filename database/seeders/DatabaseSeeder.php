<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Dependency order (each seeder depends on all above it):
     *   1. AcademicYearSeeder  — no FKs (reference data)
     *   2. UserSeeder          — no FKs
     *   3. HostelSeeder        — FK: users (warden); creates blocks→floors→rooms→beds
     *   4. StudentSeeder       — FK: users; creates room_allocations (FK: beds)
     *   5. NoticeSeeder        — FK: users (published_by)
     *   6. OutpassSeeder       — FK: students, users, hostels
     *   7. ComplaintSeeder     — FK: students, rooms, users
     *   8. VisitorSeeder       — FK: students, hostels, users
     *   9. MessMenuSeeder      — FK: hostels (nullable — applies to all)
     */
    public function run(): void
    {
        $this->call([
            AcademicYearSeeder::class,
            UserSeeder::class,
            HostelSeeder::class,
            StudentSeeder::class,
            NoticeSeeder::class,
            OutpassSeeder::class,
            ComplaintSeeder::class,
            VisitorSeeder::class,
            MessMenuSeeder::class,
        ]);
    }
}
