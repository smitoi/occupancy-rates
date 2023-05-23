<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Room;
use App\Services\RoomService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;

class BookingController extends Controller
{
    public function __construct(private readonly RoomService $roomService)
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreBookingRequest $request
     * @return JsonResponse
     */
    public function store(StoreBookingRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $room = Room::find($validated['room_id']);
        $startsAt = Carbon::parse($validated['start_at']);
        $endsAt = Carbon::parse($validated['ends_at']);

        if (count(
            array_filter(
                $this->roomService->findAvailableDatesForRoom(
                    $room,
                    $endsAt->diffInDays($startsAt),
                    1,
                    $startsAt,
                    $endsAt,
                )
            )
        )) {
            $booking = Booking::create([
                'room_id' => $room->id,
                'starts_at' => $startsAt,
                'ends_at' => $endsAt,
            ]);

            return response()->json(
                data: [
                    'message' => "Successfully created booking between $startsAt - $endsAt",
                    'data' => [
                        'id' => $booking->id,
                    ],
                ], status: 201
            );

        }

        return response()->json(
            data: [
                'message' => "Room is not available during the $startsAt - $endsAt interval",
            ], status: 400
        );
    }
}
