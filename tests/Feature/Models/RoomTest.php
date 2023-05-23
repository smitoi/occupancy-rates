<?php

namespace Tests\Feature\Models;

use App\Models\Room;
use App\Models\User;
use App\Services\RoomService;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Seeders\JanuaryRoomsOccupancy;

class RoomTest extends TestCase
{
    use RefreshDatabase;

    private RoomService $roomService;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);
        $this->seed(JanuaryRoomsOccupancy::class);
    }

    public function test_daily_occupancy_rates(): void
    {
        $cases = JanuaryRoomsOccupancy::getDailyTestCases();

        foreach ($cases as $case) {
            [$date, $rooms, $occupancyRate] = $case;

            $response = $this->actingAs(User::first())->get(
                "/api/room/daily-occupancy-rates/$date" . ($rooms !== null ? '?' . http_build_query([
                        'room_ids' => Room::whereIn('name', $rooms)->pluck('id')->toArray()
                    ]) : ''),
            );

            $response->assertSuccessful()
                ->assertJson([
                    'occupancy_rate' => $occupancyRate
                ]);
        }
    }

    public function test_monthly_occupancy_rates(): void
    {
        $cases = JanuaryRoomsOccupancy::getMonthlyTestCases();

        foreach ($cases as $case) {
            [$date, $rooms, $occupancyRate] = $case;

            $response = $this->actingAs(User::first())->get(
                "/api/room/monthly-occupancy-rates/$date" . ($rooms !== null ? '?' . http_build_query([
                        'room_ids' => Room::whereIn('name', $rooms)->pluck('id')->toArray()
                    ]) : ''),
            );

            $response->assertSuccessful()
                ->assertJson([
                    'occupancy_rate' => $occupancyRate
                ]);
        }
    }
}
