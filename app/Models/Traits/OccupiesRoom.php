<?php

namespace App\Models\Traits;

use App\Models\Room;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @mixin Model
 */
trait OccupiesRoom
{
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id', 'id');
    }

    /**
     * Scope a query to only include models on the specified date.
     */
    public function scopeOnDate(Builder $query, Carbon $date): void
    {
        $query->whereDate('starts_at', '>=', $date)
            ->whereDate('ends_at', '<=', $date);
    }

    /**
     * Scope a query to only include models between specified dates.
     */
    public function scopeInPeriod(Builder $query, Carbon $start, Carbon $end): void
    {
        $query->whereDate('starts_at', '>=', $start)
            ->orWhereDate('ends_at', '<=', $end);
    }
}
