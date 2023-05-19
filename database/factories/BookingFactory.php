<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Room;
use App\Services\RoomService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $room = Room::inRandomOrder()->firstOrFail();

        $service = app(RoomService::class);
        [$startAt, $endsAt] = [null, null];
        while ($startAt === null && $endsAt === null) {
            [$startAt, $endsAt] = $service->findAvailableDatesForRoom(
                $room,
                $this->faker->numberBetween(1, 7),
                1,
                Carbon::parse($this->faker->dateTimeBetween('-12 months')),
            );
        }

        return [
            'room_id' => $room->id,
            'starts_at' => $startAt,
            'ends_at' => $endsAt,
        ];
    }
}
