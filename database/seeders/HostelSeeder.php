<?php

namespace Database\Seeders;

use App\Models\Bed;
use App\Models\Block;
use App\Models\Floor;
use App\Models\Hostel;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Seeder;

class HostelSeeder extends Seeder
{
    public function run(): void
    {
        $warden = User::where('role', 'warden')->first();

        // ── Boys Hostel ─────────────────────────────────────────
        $boysHostel = Hostel::create([
            'name'                => 'Boys Hostel',
            'code'                => 'BH',
            'gender_type'         => 'male',
            'total_capacity'      => 480,
            'warden_in_charge_id' => $warden?->id,
            'address'             => 'HITAM Campus, Medchal, Hyderabad – 501401',
            'contact_phone'       => '+91 92480 09871',
        ]);

        // Block A – Boys Hostel
        $blockA = Block::create([
            'hostel_id'   => $boysHostel->id,
            'block_name'  => 'Block A',
            'block_code'  => 'A',
            'total_floors' => 4,
        ]);
        $this->seedFloorRoomsAndBeds($blockA, 4, 'A', 2);

        // Block B – Boys Hostel
        $blockB = Block::create([
            'hostel_id'   => $boysHostel->id,
            'block_name'  => 'Block B',
            'block_code'  => 'B',
            'total_floors' => 4,
        ]);
        $this->seedFloorRoomsAndBeds($blockB, 4, 'B', 2);

        // Block C – Boys Hostel
        $blockC = Block::create([
            'hostel_id'   => $boysHostel->id,
            'block_name'  => 'Block C',
            'block_code'  => 'C',
            'total_floors' => 3,
        ]);
        $this->seedFloorRoomsAndBeds($blockC, 3, 'C', 3);

        // ── Girls Hostel ────────────────────────────────────────
        $girlsHostel = Hostel::create([
            'name'            => 'Girls Hostel',
            'code'            => 'GH',
            'gender_type'     => 'female',
            'total_capacity'  => 290,
            'address'         => 'HITAM Campus, Medchal, Hyderabad – 501401',
            'contact_phone'   => '+91 92480 09872',
        ]);

        $blockG = Block::create([
            'hostel_id'   => $girlsHostel->id,
            'block_name'  => 'Block G',
            'block_code'  => 'G',
            'total_floors' => 4,
        ]);
        $this->seedFloorRoomsAndBeds($blockG, 4, 'G', 2);

        // ── New Boys Hostel (under development) ─────────────────
        Hostel::create([
            'name'           => 'New Boys Hostel',
            'code'           => 'NBH',
            'gender_type'    => 'male',
            'total_capacity' => 0,
            'address'        => 'HITAM Campus, Medchal, Hyderabad – 501401',
        ]);
    }

    /**
     * Create floors 1...$totalFloors, 10 rooms per floor, $bedsPerRoom beds each.
     */
    private function seedFloorRoomsAndBeds(Block $block, int $totalFloors, string $prefix, int $bedsPerRoom): void
    {
        $floorLabels = ['Ground Floor', 'First Floor', 'Second Floor', 'Third Floor', 'Fourth Floor'];

        for ($f = 1; $f <= $totalFloors; $f++) {
            $floor = Floor::create([
                'block_id'     => $block->id,
                'floor_number' => $f,
                'floor_label'  => $floorLabels[$f] ?? "Floor {$f}",
            ]);

            for ($r = 1; $r <= 10; $r++) {
                $roomNumber = sprintf('%s-%d%02d', $prefix, $f, $r);
                $room = Room::create([
                    'floor_id'    => $floor->id,
                    'room_number' => $roomNumber,
                    'capacity'    => $bedsPerRoom,
                    'room_type'   => 'Non-AC',
                    'base_fee'    => 55000.00,
                    'status'      => 'active',
                ]);

                $bedLetters = array_slice(['A', 'B', 'C', 'D'], 0, $bedsPerRoom);
                foreach ($bedLetters as $letter) {
                    Bed::create([
                        'room_id'        => $room->id,
                        'bed_identifier' => $letter,
                        'status'         => 'available',
                    ]);
                }
            }
        }
    }
}
