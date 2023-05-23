<?php

namespace App\Http\Resources;

use App\Models\Booking;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class BookingResource
 * @package App\Http\Resources
 * @OA\Schema(
 *      @OA\Property(
 *          property="id",
 *          description="Identifier of the resource",
 *          type="string",
 *      ),
 *      @OA\Property(
 *          property="room_id",
 *          description="Room for which the booking is made",
 *          type="string",
 *      ),
 *      @OA\Property(
 *          property="starts_at",
 *          description="Date at which the booking starts",
 *          type="string",
 *      ),
 *      @OA\Property(
 *          property="ends_at",
 *          description="Booking at which the booking ends",
 *          type="string",
 *      ),
 * )
 *
 * @mixin Booking
 */
class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'room_id' => $this->room_id,
            'starts_at' => $this->starts_at->format('Y-m-d'),
            'ends_at' => $this->ends_at->format('Y-m-d'),
        ];
    }
}
