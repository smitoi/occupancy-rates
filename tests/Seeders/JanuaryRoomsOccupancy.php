<?php

namespace Tests\Seeders;

use App\Models\Block;
use App\Models\Booking;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class JanuaryRoomsOccupancy extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        /**
         * Room A - capacity 6
         * Room B - capacity 4
         * Room C - capacity 2
         */
        $roomA = Room::create(['name' => 'A', 'capacity' => 6]);
        $roomB = Room::create(['name' => 'B', 'capacity' => 4]);
        Room::create(['name' => 'C', 'capacity' => 2]);

        /** B1 - Room A 1 Jan to 5 Jan */
        Booking::create([
            'room_id' => $roomA->id,
            'starts_at' => Carbon::parse('2023-01-01'),
            'ends_at' => Carbon::parse('2023-01-05')
        ]);

        /** B2 - Room A 1 Jan to 5 Jan */
        Booking::create([
            'room_id' => $roomA->id,
            'starts_at' => Carbon::parse('2023-01-01'),
            'ends_at' => Carbon::parse('2023-01-05')
        ]);

        /** B3 - Room A 1 Jan to 5 Jan */
        Booking::create([
            'room_id' => $roomA->id,
            'starts_at' => Carbon::parse('2023-01-01'),
            'ends_at' => Carbon::parse('2023-01-05')
        ]);

        /** B4 - Room B 1 Jan to 5 Jan */
        Booking::create([
            'room_id' => $roomB->id,
            'starts_at' => Carbon::parse('2023-01-01'),
            'ends_at' => Carbon::parse('2023-01-05')
        ]);

        /** B5 - Room B 3 Jan to 8 Jan */
        Booking::create([
            'room_id' => $roomB->id,
            'starts_at' => Carbon::parse('2023-01-03'),
            'ends_at' => Carbon::parse('2023-01-08')
        ]);

        /** Room B 1 Jan to 10 Jan */
        Block::create([
            'room_id' => $roomB->id,
            'starts_at' => Carbon::parse('2023-01-01'),
            'ends_at' => Carbon::parse('2023-01-10')
        ]);
    }
}
