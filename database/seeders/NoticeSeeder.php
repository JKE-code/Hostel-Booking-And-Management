<?php

namespace Database\Seeders;

use App\Models\Notice;
use App\Models\User;
use Illuminate\Database\Seeder;

class NoticeSeeder extends Seeder
{
    public function run(): void
    {
        $warden = User::where('role', 'warden')->first();

        $notices = [
            [
                'title'           => 'Hostel Fee Payment Deadline — Semester II (2025-26)',
                'content'         => 'All resident scholars are reminded that the Semester II hostel fee of ₹27,500 must be paid on or before 15th October 2025. Late payments will attract a penalty of ₹500 per week. Payment portal: https://erp.hitam.org. Contact the estate office for DD submission.',
                'category'        => 'fee',
                'target_audience' => 'all',
                'is_pinned'       => true,
                'expires_at'      => '2025-10-20 23:59:00',
            ],
            [
                'title'           => 'Anti-Ragging Awareness Workshop — Mandatory Attendance',
                'content'         => 'A mandatory anti-ragging orientation session will be conducted on 25th September 2025 at 4:00 PM in the Boys Hostel Common Hall. All first-year resident students must attend. Attendance will be marked for compliance records.',
                'category'        => 'event',
                'target_audience' => 'boys_hostel',
                'is_pinned'       => true,
            ],
            [
                'title'           => 'Biometric Gate System Maintenance — 22 Sep 2025',
                'content'         => 'The biometric entry/exit system will be under scheduled maintenance from 10:00 AM to 02:00 PM on 22nd September 2025. Security personnel will conduct manual register-based attendance during this window. Students are requested to carry their ID cards.',
                'category'        => 'maintenance',
                'target_audience' => 'all',
                'is_pinned'       => false,
            ],
            [
                'title'           => 'Revised Mess Menu — October 2025',
                'content'         => 'The Student Mess Committee has approved a revised mess menu effective 1st October 2025. The revised menu incorporates student feedback from the September survey. Printed copies are available at the dining hall notice board. Key additions: Sambar Rice (Wed dinner), Egg Curry option (Mon & Thu).',
                'category'        => 'general',
                'target_audience' => 'all',
                'is_pinned'       => false,
            ],
            [
                'title'           => 'Curfew Timings Reminder — Girls Hostel',
                'content'         => 'A gentle reminder to all residents of the Girls Hostel: Gate-in curfew time is strictly 8:00 PM on weekdays and 9:00 PM on weekends. Any exceptions require a prior written permission from the resident warden. Repeated violations will be escalated to the parent and the disciplinary committee.',
                'category'        => 'rule',
                'target_audience' => 'girls_hostel',
                'is_pinned'       => false,
            ],
        ];

        foreach ($notices as $data) {
            Notice::create(array_merge($data, [
                'is_published' => true,
                'published_by' => $warden?->id,
            ]));
        }
    }
}
