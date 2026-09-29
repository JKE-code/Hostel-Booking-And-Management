<?php

namespace Database\Seeders;

use App\Models\Bed;
use App\Models\RoomAllocation;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $profiles = [
            [
                'email'            => 'rahul.sharma@student.hitam.org',
                'roll_number'      => '23HT1A0501',
                'department'       => 'CSE',
                'year_of_study'    => 2,
                'phone'            => '+91 91234 56789',
                'parent_name'      => 'Vijay Sharma',
                'parent_phone'     => '+91 91234 56780',
                'blood_group'      => 'O+',
                'gender'           => 'male',
                'date_of_birth'    => '2004-06-15',
                'room_prefix'      => 'B',   // Boys Block B
            ],
            [
                'email'            => 'kavya.reddy@student.hitam.org',
                'roll_number'      => '22HT1A0412',
                'department'       => 'ECE',
                'year_of_study'    => 3,
                'phone'            => '+91 98480 12345',
                'parent_name'      => 'K. Ravi Reddy',
                'parent_phone'     => '+91 98480 12300',
                'blood_group'      => 'B+',
                'gender'           => 'female',
                'date_of_birth'    => '2003-03-22',
                'room_prefix'      => 'G',   // Girls Block G
            ],
            [
                'email'            => 'sai.teja@student.hitam.org',
                'roll_number'      => '24HT1A1208',
                'department'       => 'IT',
                'year_of_study'    => 1,
                'phone'            => '+91 94400 98765',
                'parent_name'      => 'N. Teja Rao',
                'parent_phone'     => '+91 94400 98700',
                'blood_group'      => 'A+',
                'gender'           => 'male',
                'date_of_birth'    => '2005-09-10',
                'room_prefix'      => 'A',   // Boys Block A
            ],
            [
                'email'            => 'priya.nair@student.hitam.org',
                'roll_number'      => '23HT1A0687',
                'department'       => 'MECH',
                'year_of_study'    => 2,
                'phone'            => '+91 99460 11000',
                'parent_name'      => 'Suresh Nair',
                'parent_phone'     => '+91 99460 11001',
                'blood_group'      => 'AB+',
                'gender'           => 'female',
                'date_of_birth'    => '2004-01-05',
                'room_prefix'      => 'G',
            ],
            [
                'email'            => 'v.chaitanya@student.hitam.org',
                'roll_number'      => '24HT1A0322',
                'department'       => 'EEE',
                'year_of_study'    => 1,
                'phone'            => '+91 87654 32100',
                'parent_name'      => 'V. Ramesh',
                'parent_phone'     => '+91 87654 32101',
                'blood_group'      => 'B-',
                'gender'           => 'male',
                'date_of_birth'    => '2005-11-28',
                'room_prefix'      => 'C',   // Boys Block C
            ],
        ];

        foreach ($profiles as $profile) {
            $user = User::where('email', $profile['email'])->first();
            if (! $user) continue;

            $student = Student::create([
                'user_id'          => $user->id,
                'name'             => $user->name,
                'email'            => $user->email,
                'roll_number'      => $profile['roll_number'],
                'department'       => $profile['department'],
                'year_of_study'    => $profile['year_of_study'],
                'phone'            => $profile['phone'],
                'parent_name'      => $profile['parent_name'],
                'parent_phone'     => $profile['parent_phone'],
                'blood_group'      => $profile['blood_group'],
                'gender'           => $profile['gender'],
                'date_of_birth'    => $profile['date_of_birth'],
                'permanent_address'=> 'Hyderabad, Telangana',
                'onboarding_status'=> 'active',
            ]);

            // Assign the first available bed in the correct block
            $bed = Bed::whereHas('room.floor.block', function ($q) use ($profile) {
                    $q->where('block_code', $profile['room_prefix']);
                })
                ->where('status', 'available')
                ->first();

            if ($bed) {
                RoomAllocation::create([
                    'student_id'     => $student->id,
                    'bed_id'         => $bed->id,
                    'academic_year'  => '2025-2026',
                    'allocated_from' => '2025-08-01',
                    'allocated_to'   => '2026-05-31',
                    'status'         => 'active',
                ]);

                // Mark bed as occupied
                $bed->update(['status' => 'occupied']);

                // Update room status if full
                $room = $bed->room;
                if ($room->beds()->where('status', 'available')->count() === 0) {
                    $room->update(['status' => 'full']);
                }
            }
        }

        // ── Pre-whitelisted Student (Awaiting 2-Way OTP Verification Onboarding) ──
        Student::create([
            'user_id'          => null,
            'name'             => 'Aditya Varma',
            'email'            => 'aditya.varma@student.hitam.org',
            'roll_number'      => '25HT1A0599',
            'department'       => 'CSE',
            'year_of_study'    => 1,
            'phone'            => '+91 98765 43210',
            'parent_name'      => 'S. Varma',
            'parent_phone'     => '+91 98765 43200',
            'emergency_contact'=> '+91 98765 43200',
            'blood_group'      => 'O+',
            'gender'           => 'male',
            'date_of_birth'    => '2006-04-12',
            'permanent_address'=> 'Flat 402, Green Meadows, Medchal, Hyderabad',
            'onboarding_status'=> 'whitelisted',
        ]);
    }
}
