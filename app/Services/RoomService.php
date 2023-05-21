<?php

namespace App\Services;

use App\Models\Block;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Support\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class RoomService
{
    /**
     * @param Room $room
     * @param Carbon $date
     * @return int
     */
    public function getRoomOccupancy(Room $room, Carbon $date): int
    {
        return $room->bookings
            ->filter(
                fn(Booking $block) => $date->isBetween($block->starts_at, $block->ends_at)
            )->count();
    }

    /**
     * @param Room $room
     * @param Carbon $date
     * @return int
     */
    public function getRoomBlockingIntervals(Room $room, Carbon $date): int
    {
        return $room->blocks
            ->filter(
                fn(Block $block) => $date->isBetween($block->starts_at, $block->ends_at)
            )->count();
    }

    /**
     * @param Room $room
     * @param Carbon $date
     * @return int
     */
    public function getRoomOccupiedCapacity(Room $room, Carbon $date): int
    {
        return $this->getRoomOccupancy($room, $date)
            + $this->getRoomBlockingIntervals($room, $date);
    }

    /**
     * @param Room|Collection $rooms
     * @param Carbon $start
     * @param Carbon $end
     * @param int $precision
     * @return float
     */
    public function computeOccupancyRate(Room|Collection $rooms, Carbon $start, Carbon $end, int $precision = 2): float
    {
        if ($rooms instanceof Room) {
            $rooms = collect([$rooms]);
        }

        $start->startOfDay();
        $end->addDay()->startOfDay();

        $roomsModels = Room::whereIn('id', $rooms->pluck('id'))->with([
            'blocks' => fn(Builder $query) => $query->inPeriod($start, $end),
            'bookings' => fn(Builder $query) => $query->inPeriod($start, $end)
        ])->get();

        $occupancy = 0;
        $blockingIntervals = 0;
        /** @var int $capacity */
        $capacity = $roomsModels->sum('capacity') * $start->diffInDays($end);
        while ($start->isBefore($end)) {
            /** @var Room $room */
            foreach ($rooms as $room) {
                $occupancy += $this->getRoomOccupancy($room, $start);
                $blockingIntervals += $this->getRoomBlockingIntervals($room, $start);
            }
            $start->addDay();
        }

        return round(($occupancy / (float)($capacity - $blockingIntervals)), $precision);
    }

    /**
     * @param Room $room
     * @param int $desiredPeriodLength
     * @param int $desiredCapacity
     * @param Carbon $betweenStart
     * @param Carbon|null $betweenEnd
     * @return array|null[]
     */
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
            if ($this->getRoomOccupiedCapacity($room, $availableStart) <= ($room->capacity - $desiredCapacity)) {
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
