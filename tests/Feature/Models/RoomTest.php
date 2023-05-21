<?php

namespace Tests\Feature\Models;

use App\Services\RoomService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Seeders\JanuaryRoomsOccupancy;

class RoomTest extends TestCase
{
    use RefreshDatabase;

    private RoomService $roomService;

    public function setUp(): void
    {
        // TODO: create more cases using each month
        parent::setUp();
        $this->seed(JanuaryRoomsOccupancy::class);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
