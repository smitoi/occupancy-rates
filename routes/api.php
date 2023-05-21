<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', LoginController::class);
Route::middleware('auth:sanctum')->group(function () {
    Route::controller(RoomController::class)
        ->prefix('/room')
        ->as('room.')->group(static function () {
            Route::get('/daily-occupancy-rates', 'dailyOccupancyRates')->name('daily-occupancy-rates');
            Route::get('/monthly-occupancy-rates', 'monthlyOccupancyRates')->name('monthly-occupancy-rates');
    });
});
