<?php

namespace Tests\Unit\Services;

use App\Models\Room;
use App\Services\RoomService;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Seeders\JanuaryRoomsOccupancy;

class RoomServiceTest extends TestCase
{
    use RefreshDatabase;

    private RoomService $roomService;

    public function setUp(): void
    {
        // TODO: create more cases using each month
        parent::setUp();
        $this->seed(JanuaryRoomsOccupancy::class);
        $this->roomService = app(RoomService::class);
    }

    public function test_compute_room_daily_occupancy_rate(): void
    {
        $cases = [
            ['2023-01-02', null, 0.36],
            ['2023-01-06', ['B', 'C'], 0.2],
        ];

        foreach ($cases as $case) {
            [$date, $rooms, $occupancyRate] = $case;
            $startDate = Carbon::parse($date);
            $endDate = $startDate->copy();
            $endDate->endOfDay();
            $this->assertEquals(
                $this->roomService->computeOccupancyRate(
                    $rooms === null ?
                        Room::all() :
                        Room::whereIn('name', $rooms)->get(),
                    $startDate,
                    $endDate,
                ),
                $occupancyRate);
        }
    }

    public function test_compute_room_monthly_occupancy_rate(): void
    {
        $cases = [
            ['2023-01', null, 0.07],
            ['2023-01', ['B', 'C'], 0.06],
        ];

        foreach ($cases as $case) {
            [$date, $rooms, $occupancyRate] = $case;
            $startDate = Carbon::parse($date);
            $endDate = $startDate->copy();
            $endDate->endOfMonth();
            $this->assertEquals(
                $this->roomService->computeOccupancyRate(
                    $rooms === null ?
                        Room::all() :
                        Room::whereIn('name', $rooms)->get(),
                    $startDate,
                    $endDate,
                ),
                $occupancyRate);
        }

    }
}
