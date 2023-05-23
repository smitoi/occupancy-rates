<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use ReflectionException;

/**
 * @OA\RequestBody(
 *     request="UpdateBookingRequest",
 *     description="Request to update an existing booking",
 *     @OA\MediaType(
 *         mediaType="application/json",
 *         @OA\Schema(
 *             @OA\Property(
 *                 property="room_id",
 *                 description="ID of the room for which the booking will be moved",
 *                 type="string"
 *             ),
 *             @OA\Property(
 *                 property="starts_at",
 *                 description="Date when the booking will start",
 *                 type="date"
 *             ),
 *             @OA\Property(
 *                 property="ends_at",
 *                 description="Date when the booking will end",
 *                 type="date"
 *             )
 *         )
 *     )
 * )
 */
class UpdateBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // TODO: should add authorisation based on a user_id column for a booking
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'room_id' => 'exists:rooms,id',
            'starts_at' => 'date|date_format:Y-m-d',
            'ends_at' => 'date|date_format:Y-m-d',
        ];
    }
}
