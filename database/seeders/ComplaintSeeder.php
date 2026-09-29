<?php

namespace Database\Seeders;

use App\Models\Complaint;
use App\Models\RoomAllocation;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class ComplaintSeeder extends Seeder
{
    public function run(): void
    {
        $warden   = User::where('role', 'warden')->first();
        $students = Student::all();

        if ($students->isEmpty()) return;

        $tickets = [
            [
                'roll'        => '23HT1A0501',
                'category'    => 'electrical',
                'title'       => 'Tube light flickering in room',
                'description' => 'Tube light has been flickering since 3 days. Affects sleep and study concentration.',
                'priority'    => 'medium',
                'status'      => 'submitted',
            ],
            [
                'roll'        => '24HT1A1208',
                'category'    => 'plumbing',
                'title'       => 'Bathroom tap dripping continuously',
                'description' => 'Bathroom tap is dripping continuously leading to water wastage.',
                'priority'    => 'low',
                'status'      => 'in_progress',
            ],
            [
                'roll'        => '23HT1A0687',
                'category'    => 'ac_fan',
                'title'       => 'Ceiling fan rattling noise',
                'description' => 'Ceiling fan makes loud rattling noise during operation. Very disruptive at night.',
                'priority'    => 'high',
                'status'      => 'submitted',
            ],
            [
                'roll'        => '24HT1A0322',
                'category'    => 'wifi',
                'title'       => 'Wi-Fi disconnects every 30 minutes',
                'description' => 'Wi-Fi connection drops every 30 minutes. Cannot attend online classes without interruptions.',
                'priority'    => 'high',
                'status'      => 'in_progress',
                'assigned_to' => $warden?->id,
            ],
            [
                'roll'        => '22HT1A0412',
                'category'    => 'cleaning',
                'title'       => 'Common bathroom not cleaned since 2 days',
                'description' => 'The shared bathroom on Floor 1 has not been cleaned for 2 days. Unhygienic conditions.',
                'priority'    => 'urgent',
                'status'      => 'resolved',
                'resolved_at' => now()->subDay(),
                'rating'      => 4,
            ],
        ];

        foreach ($tickets as $t) {
            $student = $students->first(fn($s) => $s->roll_number === $t['roll']);
            if (! $student) continue;

            // Get the room from active allocation
            $allocation = RoomAllocation::where('student_id', $student->id)
                ->where('status', 'active')->with('bed.room')->first();
            $roomId = $allocation?->bed?->room?->id;

            Complaint::create([
                'student_id'        => $student->id,
                'room_id'           => $roomId,
                'category'          => $t['category'],
                'title'             => $t['title'],
                'description'       => $t['description'],
                'priority'          => $t['priority'],
                'status'            => $t['status'],
                'assigned_to'       => $t['assigned_to'] ?? null,
                'assigned_at'       => isset($t['assigned_to']) ? now()->subHours(3) : null,
                'resolved_at'       => $t['resolved_at'] ?? null,
                'resolution_rating' => $t['rating'] ?? null,
            ]);
        }
    }
}
