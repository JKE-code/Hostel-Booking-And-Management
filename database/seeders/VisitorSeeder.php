<?php

namespace Database\Seeders;

use App\Models\Hostel;
use App\Models\Student;
use App\Models\User;
use App\Models\Visitor;
use Illuminate\Database\Seeder;

class VisitorSeeder extends Seeder
{
    public function run(): void
    {
        $warden   = User::where('role', 'warden')->first();
        $students = Student::all();
        $boysHostel  = Hostel::where('code', 'BH')->first();
        $girlsHostel = Hostel::where('code', 'GH')->first();

        if ($students->isEmpty()) return;

        $visits = [
            [
                'roll'              => '23HT1A0501',
                'hostel'            => $boysHostel,
                'visitor_name'      => 'Vijay Sharma',
                'relation'          => 'Father',
                'visitor_phone'     => '+91 91234 56780',
                'visitor_id_type'   => 'Aadhaar',
                'visitor_id_number' => '2345 6789 0123',
                'visitor_count'     => 2,
                'visit_date'        => '2025-09-21',
                'expected_arrival'  => '10:00:00',
                'expected_departure'=> '13:00:00',
                'purpose'           => 'Bringing semester fee DD and personal items.',
                'status'            => 'pre_registered',
            ],
            [
                'roll'              => '22HT1A0412',
                'hostel'            => $girlsHostel,
                'visitor_name'      => 'K. Ravi Reddy',
                'relation'          => 'Father',
                'visitor_phone'     => '+91 98480 12300',
                'visitor_id_type'   => 'Driving Licence',
                'visitor_id_number' => 'TS0520180012345',
                'visitor_count'     => 1,
                'visit_date'        => '2025-09-20',
                'expected_arrival'  => '14:00:00',
                'expected_departure'=> '16:00:00',
                'purpose'           => 'Parent-warden meeting and hostel room inspection.',
                'status'            => 'checked_out',
                'checked_in_at'     => '2025-09-20 14:12:00',
                'checked_out_at'    => '2025-09-20 15:48:00',
            ],
            [
                'roll'              => '24HT1A1208',
                'hostel'            => $boysHostel,
                'visitor_name'      => 'N. Teja Rao',
                'relation'          => 'Father',
                'visitor_phone'     => '+91 94400 98700',
                'visitor_id_type'   => 'Aadhaar',
                'visitor_id_number' => '9876 5432 1098',
                'visitor_count'     => 2,
                'visit_date'        => '2025-09-22',
                'expected_arrival'  => '11:00:00',
                'expected_departure'=> '14:00:00',
                'purpose'           => 'Dropping off winter clothes and medicines.',
                'status'            => 'pre_registered',
            ],
        ];

        foreach ($visits as $v) {
            $student = $students->first(fn($s) => $s->roll_number === $v['roll']);
            if (! $student) continue;

            Visitor::create([
                'student_id'         => $student->id,
                'hostel_id'          => $v['hostel']?->id,
                'visitor_name'       => $v['visitor_name'],
                'relation'           => $v['relation'],
                'visitor_phone'      => $v['visitor_phone'],
                'visitor_id_type'    => $v['visitor_id_type'] ?? null,
                'visitor_id_number'  => $v['visitor_id_number'] ?? null,
                'visitor_count'      => $v['visitor_count'],
                'visit_date'         => $v['visit_date'],
                'expected_arrival'   => $v['expected_arrival'] ?? null,
                'expected_departure' => $v['expected_departure'] ?? null,
                'purpose'            => $v['purpose'] ?? null,
                'status'             => $v['status'],
                'checked_in_at'      => $v['checked_in_at'] ?? null,
                'checked_out_at'     => $v['checked_out_at'] ?? null,
                'approved_by'        => $warden?->id,
            ]);
        }
    }
}
