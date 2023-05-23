<?php

namespace Models;

use App\Models\Room;
use App\Models\User;
use App\Services\RoomService;
use Carbon\Carbon;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\Seeders\OneEmptyRoom;
use Tests\Seeders\OneFullRoom;
use Tests\TestCase;
use Tests\Seeders\JanuaryRoomsOccupancy;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);
        $this->seed(OneFullRoom::class);
        $this->seed(OneEmptyRoom::class);
    }

    public function test_can_create_booking_for_empty_room(): void
    {
        /** @var Room $room */
        $room = Room::query()->whereDoesntHave('bookings')->firstOrFail();
        $startDate = Carbon::parse('2023-01-01')->startOfDay();
        $endDate = $startDate->copy()->addDay()->format('Y-m-d');
        $startDate = $startDate->format('Y-m-d');

        $this->actingAs(User::first())
            ->post('/api/booking/', [
                'room_id' => $room->id,
                'starts_at' => $startDate,
                'ends_at' => $endDate,
            ])
            ->assertSuccessful()
            ->assertJson(fn(AssertableJson $json) => $json
                ->where('data.ends_at', $endDate)
                ->where('data.starts_at', $startDate)
                ->where('data.room_id', $room->id)
                ->has('data.id')
            );
    }

    public function test_cannot_create_booking_for_full_room(): void
    {
        /** @var Room $room */
        $room = Room::query()->whereHas('bookings')->firstOrFail();
        $startDate = Carbon::parse('2023-01-01')->startOfDay();
        $endDate = $startDate->copy()->addDay()->format('Y-m-d');
        $startDate = $startDate->format('Y-m-d');

        $this->actingAs(User::first())
            ->post('/api/booking/', [
                'room_id' => $room->id,
                'starts_at' => $startDate,
                'ends_at' => $endDate,
            ])
            ->assertStatus(400)
            ->assertJsonStructure([
                'message',
            ]);
    }
}
