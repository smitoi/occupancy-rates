<?php

namespace App\Models;

use App\Models\Traits\OccupiesRoom;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Class Booking
 * @property int $id
 * @property int $room_id
 * @property Carbon $starts_at
 * @property Carbon $ends_at
 *
 * @property-read Room $room
 *
 * @package App\Models
 *
 * @mixin Builder
 */
class Booking extends Model
{
    use HasFactory, OccupiesRoom;

    protected $fillable = [
        'room_id',
        'starts_at',
        'ends_at',
    ];

    protected $casts = [
        'starts_at' => 'date:Y-m-d',
        'ends_at' => 'date:Y-m-d',
    ];
}
