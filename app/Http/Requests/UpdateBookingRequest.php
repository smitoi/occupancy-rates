<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'id' => 'required|exists:bookings,id',
            'room_id' => 'exists:rooms,id',
            'start_at' => 'date|date_format:Y-m-d',
            'ends_at' => 'date|date_format:Y-m-d',
        ];
    }
}
