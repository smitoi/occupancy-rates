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

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        User::create([
            'name' => 'User',
            'email' => 'user@bookinglayer.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make(
                UserFactory::DEFAULT_PASSWORD
            ),
        ]);
    }
}
