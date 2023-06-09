<?php

namespace Database\Seeders;

use App\Models\Block;
use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Illuminate\Support\Carbon;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        if (app()->runningUnitTests() === false) {
            Room::factory(3)->create();

            Booking::factory(100)->create();
            Block::factory(10)->create();
        }
    }
}
