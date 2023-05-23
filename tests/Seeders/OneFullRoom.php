<?php

namespace Tests\Seeders;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class OneFullRoom extends Seeder
{
    public const OCCUPIED_ON_DATE = '2023-01-01';

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $capacity = 4;
        /** @var Room $room */
        $room = Room::factory()->create([
            'capacity' => $capacity
        ]);

        while ($capacity--) {
            Booking::create([
                'room_id' => $room->id,
                'starts_at' => self::OCCUPIED_ON_DATE,
                'ends_at' => Carbon::parse(self::OCCUPIED_ON_DATE)->addDay()->toDateTimeString()
            ]);
        }
    }
}

