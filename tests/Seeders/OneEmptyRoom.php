<?php

namespace Tests\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class OneEmptyRoom extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        Room::factory()->create();
    }
}
