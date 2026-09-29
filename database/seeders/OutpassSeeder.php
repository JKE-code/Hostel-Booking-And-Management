<?php

namespace Database\Seeders;

use App\Models\Hostel;
use App\Models\Outpass;
use App\Models\RoomAllocation;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OutpassSeeder extends Seeder
{
    public function run(): void
    {
        $warden  = User::where('role', 'warden')->first();
        $students = Student::with('user')->get();

        if ($students->isEmpty()) return;

        $data = [
            [
                'roll'        => '23HT1A0501',
                'leave_type'  => 'weekend',
                'destination' => 'Warangal, Telangana',
                'reason'      => 'Family visit during Dussehra holidays.',
                'out'         => '2025-09-20 14:00:00',
                'in'          => '2025-09-22 21:00:00',
                'status'      => 'pending',
            ],
            [
                'roll'        => '22HT1A0412',
                'leave_type'  => 'medical',
                'destination' => 'Apollo Clinic, Kompally',
                'reason'      => 'Scheduled follow-up consultation with physician.',
                'out'         => '2025-09-18 15:00:00',
                'in'          => '2025-09-18 19:00:00',
                'status'      => 'approved',
                'approved_at' => '2025-09-18 13:30:00',
            ],
            [
                'roll'        => '24HT1A1208',
                'leave_type'  => 'academic',
                'destination' => 'IIT Hyderabad',
                'reason'      => 'Inter-college hackathon participation.',
                'out'         => '2025-09-21 08:00:00',
                'in'          => '2025-09-23 20:00:00',
                'status'      => 'approved',
                'approved_at' => '2025-09-20 10:00:00',
            ],
            [
                'roll'        => '23HT1A0687',
                'leave_type'  => 'emergency',
                'destination' => 'Kochi, Kerala',
                'reason'      => 'Family emergency — emergency travel approved.',
                'out'         => '2025-09-19 06:00:00',
                'in'          => '2025-09-25 21:00:00',
                'status'      => 'approved',
                'approved_at' => '2025-09-18 23:00:00',
            ],
            [
                'roll'        => '24HT1A0322',
                'leave_type'  => 'weekend',
                'destination' => 'Nalgonda, Telangana',
                'reason'      => 'Weekend home visit.',
                'out'         => '2025-09-20 15:00:00',
                'in'          => '2025-09-22 21:00:00',
                'status'      => 'pending',
            ],
        ];

        foreach ($data as $row) {
            $student = $students->first(fn($s) => $s->roll_number === $row['roll']);
            if (! $student) continue;

            // Resolve hostel_id from the student's active bed allocation
            $hostelId = RoomAllocation::where('student_id', $student->id)
                ->where('status', 'active')
                ->with('bed.room.floor.block.hostel')
                ->first()?->bed?->room?->floor?->block?->hostel?->id;

            Outpass::create([
                'student_id'           => $student->id,
                'hostel_id'            => $hostelId,
                'leave_type'           => $row['leave_type'],
                'destination'          => $row['destination'],
                'reason'               => $row['reason'],
                'out_datetime'         => $row['out'],
                'in_datetime'          => $row['in'],
                'parent_consent'       => true,
                'parent_consent_via'   => 'sms',
                'status'               => $row['status'],
                'approved_by'          => in_array($row['status'], ['approved', 'rejected'])
                                          ? $warden?->id : null,
                'approved_at'          => $row['approved_at'] ?? null,
                'qr_verification_code' => strtoupper(Str::random(12)),
            ]);
        }
    }
}
