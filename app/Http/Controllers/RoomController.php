<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Services\RoomService;
use Illuminate\Support\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class RoomController extends Controller
{
    public function __construct(private readonly RoomService $roomService)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/room/daily-occupancy-rates/{date}",
     *     @OA\Parameter(
     *        in="path",
     *        name="date",
     *        parameter="date",
     *        description="Date on which the occupancy rate will be computed",
     *     ),
     *     @OA\Parameter(
     *        in="query",
     *        name="room_ids",
     *        parameter="room_ids",
     *        @OA\Schema(
     *            type="array",
     *            @OA\Items(
     *                 type="integer",
     *            ),
     *        )
     *     ),
     *     security={{"bearerAuth":{}}},
     *     tags={"room"},
     *     description="Daily occupancy",
     *     summary="Gets the daily occupancy rates for a list of rooms",
     *     operationId="dailyOccupancyRates",
     *     @OA\Response(
     *         response="200",
     *         description="Success with the daily occupancy rates computed",
     *         @OA\JsonContent(
     *             example={
     *                  "occupancy_rate": 0.05
     *             },
     *         )
     *     )
     * )
     *
     * @param Request $request
     * @param Carbon $date
     * @return JsonResponse
     */
    public function dailyOccupancyRates(Request $request, Carbon $date): JsonResponse
    {
        $validated = $request->validate([
            'room_ids' => 'sometimes|array',
        ]);

        $nextDay = $date->copy();
        $nextDay->addDay();
        return response(200)->json([
            'occupancy_rate' => $this->roomService->computeOccupancyRate(
                Arr::exists($validated, 'room_ids') ?
                    Room::query()->whereIn('id', $validated['room_ids'])->get() :
                    Room::get(),
                $date,
                $nextDay
            )
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/room/monthly-occupancy-rates/{date}",
     *     @OA\Parameter(
     *        in="path",
     *        name="date",
     *        parameter="date",
     *        description="Month on which the occupancy rate will be computed",
     *     ),
     *     @OA\Parameter(
     *        in="query",
     *        name="room_ids",
     *        parameter="room_ids",
     *        @OA\Schema(
     *            type="array",
     *            @OA\Items(
     *                 type="integer",
     *            ),
     *        )
     *     ),
     *     security={{"bearerAuth":{}}},
     *     tags={"room"},
     *     description="Monthly occupancy",
     *     summary="Gets the monthly occupancy rates for a list of rooms",
     *     operationId="monthlyOccupancyRates",
     *     @OA\Response(
     *         response="200",
     *         description="Success with the monthly occupancy rates computed",
     *         @OA\JsonContent(
     *             example={
     *                  "occupancy_rate": 0.05
     *             },
     *         )
     *     )
     * )
     *
     * @param Request $request
     * @param Carbon $date
     * @return JsonResponse
     */
    public function monthlyOccupancyRates(Request $request, Carbon $date): JsonResponse
    {
        $validated = $request->validate([
            'room_ids' => 'sometimes|array',
        ]);

        $date->startOfMonth();
        $nextMonth = $date->copy();
        $nextMonth->addMonth()->startOfMonth();

        return response(200)->json([
            'occupancy_rate' => $this->roomService->computeOccupancyRate(
                Arr::exists($validated, 'room_ids') ?
                    Room::query()->whereIn('id', $validated['room_ids'])->get() :
                    Room::get(),
                $date,
                $nextMonth
            )
        ]);
    }
}
