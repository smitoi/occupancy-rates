<?php

namespace App\Services;

use App\Models\Block;
use App\Models\Booking;
use App\Models\Room;
use Carbon\Carbon;

class RoomService
{
    public function isRoomAvailableOn(Room $room, Carbon $date, int $desiredCapacity = 1): bool
    {
        $room->load('blocks', 'bookings');

        return $room->blocks
                ->filter(
                    fn(Block $block) => $block->starts_at->isBefore($date) && $block->ends_at->isAfter($date)
                )->count()
            + $room->bookings
                ->filter(
                    fn(Booking $block) => $block->starts_at->isBefore($date) && $block->ends_at->isAfter($date)
                )->count()
            <= $room->capacity - $desiredCapacity;
    }

    public function findAvailableDatesForRoom(Room $room, int $desiredPeriodLength, int $desiredCapacity, Carbon $betweenStart, Carbon $betweenEnd = null): array
    {
        if ($betweenEnd === null) {
            $betweenEnd = Carbon::now();
        }

        $betweenStart->startOfDay();
        $betweenEnd->addDay()->startOfDay();

        $room->load('blocks', 'bookings');

        $availableDays = 0;
        $availableStart = $betweenStart->copy();
        while ($availableStart->isBefore($betweenEnd)) {
            if ($this->isRoomAvailableOn($room, $availableStart, $desiredCapacity)) {
                $availableDays++;
            } else {
                $availableDays = 0;
            }

            if ($availableDays === $desiredPeriodLength) {
                $availableEnd = $availableStart->copy();
                $availableEnd->addDays($desiredPeriodLength);
                return [$availableStart, $availableEnd];
            }

            $availableStart->addDay();
        }

        return [null, null];
    }
}
