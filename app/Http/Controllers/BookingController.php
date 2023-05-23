<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Http\Resources\BookingResource;
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
     * @OA\Post(
     *     path="/api/booking/",
     *     security={{"bearerAuth":{}}},
     *     tags={"booking"},
     *     description="Store",
     *     summary="Stores a new booking",
     *     operationId="bookingStore",
     *     requestBody={"$ref": "#/components/requestBodies/StoreBookingRequest"},
     *     @OA\Response(
     *         response="200",
     *         description="Success with the new booking",
     *         @OA\JsonContent(ref="#/components/schemas/BookingResource"),
     *     )
     * )
     * @param StoreBookingRequest $request
     * @return JsonResponse|BookingResource
     */
    public function store(StoreBookingRequest $request): JsonResponse|BookingResource
    {
        $validated = $request->validated();

        $room = Room::find($validated['room_id']);
        $startsAt = Carbon::parse($validated['starts_at']);
        $endsAt = Carbon::parse($validated['ends_at']);

        if (count(
            array_filter(
                $this->roomService->findAvailableDatesForRoom(
                    $room,
                    $endsAt->diffInDays($startsAt),
                    1,
                    $startsAt->copy(),
                    $endsAt->copy(),
                )
            )
        )) {
            $booking = Booking::create([
                'room_id' => $room->id,
                'starts_at' => $startsAt,
                'ends_at' => $endsAt,
            ]);

            return BookingResource::make($booking);

        }

        return response()->json(
            data: [
                'message' => "Room is not available during the $startsAt - $endsAt interval",
            ], status: 400
        );
    }

    /**
     * @OA\Put(
     *     path="/api/booking/{id}",
     *     @OA\Parameter(
     *        in="path",
     *        name="id",
     *        parameter="id"
     *     ),
     *     security={{"bearerAuth":{}}},
     *     tags={"booking"},
     *     description="Update",
     *     summary="Updated an existing booking",
     *     operationId="bookingUpdate",
     *     requestBody={"$ref": "#/components/requestBodies/UpdateBookingRequest"},
     *     @OA\Response(
     *         response="200",
     *         description="Success with the company's updated data",
     *         @OA\JsonContent(ref="#/components/schemas/BookingResource"),
     *     )
     * )
     *
     * @param UpdateBookingRequest $request
     * @param Booking $booking
     * @return JsonResponse|BookingResource
     */
    public function update(UpdateBookingRequest $request, Booking $booking): JsonResponse|BookingResource
    {
        $validated = $request->validated();

        $room = Room::find($validated['room_id']);
        $startsAt = Carbon::parse($validated['starts_at']);
        $endsAt = Carbon::parse($validated['ends_at']);

        if ($room->id === $booking->id || count(
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
            $booking->update([
                'room_id' => $room->id,
                'starts_at' => $startsAt,
                'ends_at' => $endsAt,
            ]);

            return response()->json(
                data: [
                    'message' => "Successfully updated booking",
                    'data' => [
                        'id' => $booking->id,
                    ],
                ], status: 201
            );

        }

        return response()->json(
            data: [
                'message' => "Selected room is not available during the $startsAt - $endsAt interval",
            ], status: 400
        );
    }
}
