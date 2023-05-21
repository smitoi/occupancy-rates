<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * Class Room
 * @property int $id
 * @property string $name
 * @property int $capacity
 *
 * @property-read Collection<Booking> $bookings
 * @property-read Collection<Block> $blocks
 *
 * @package App\Models
 *
 * @mixin Builder
 */
class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'capacity'
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function blocks(): HasMany
    {
        return $this->hasMany(Block::class);
    }
}
