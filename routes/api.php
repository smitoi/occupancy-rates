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
Route::middleware(['auth:sanctum'])->group(function () {
    Route::controller(RoomController::class)
        ->prefix('/room')
        ->as('room.')->group(static function () {
            Route::get('/daily-occupancy-rates/{date}', 'dailyOccupancyRates')
                ->name('daily-occupancy-rates')
                ->where('date', '2[0-9]{3}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[01])');
            Route::get('/monthly-occupancy-rates/{date}', 'monthlyOccupancyRates')
                ->name('monthly-occupancy-rates')
                ->where('date', '2[0-9]{3}-(0[1-9]|1[0-2])');
        });

    Route::controller(\App\Http\Controllers\BookingController::class)
        ->prefix('/booking')
        ->as('booking.')->group(static function () {
            Route::post('/', 'store')->name('store');
            Route::put('/{booking}', 'update')->name('update');
        });
});
