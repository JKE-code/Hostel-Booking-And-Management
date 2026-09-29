<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use Illuminate\Database\Seeder;

class AcademicYearSeeder extends Seeder
{
    public function run(): void
    {
        // Past year — for historical allocation records
        AcademicYear::create([
            'label'      => '2024-2025',
            'start_date' => '2024-08-01',
            'end_date'   => '2025-05-31',
            'is_current' => false,
        ]);

        // Current year — all active allocations and fees belong here
        AcademicYear::create([
            'label'      => '2025-2026',
            'start_date' => '2025-08-01',
            'end_date'   => '2026-05-31',
            'is_current' => true,
        ]);

        // Next year — pre-loaded for advance allotment planning
        AcademicYear::create([
            'label'      => '2026-2027',
            'start_date' => '2026-08-01',
            'end_date'   => '2027-05-31',
            'is_current' => false,
        ]);
    }
}
