<?php

use App\Http\Controllers\BusBookingApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('bus-booking')->name('bus-booking.api.')->controller(BusBookingApiController::class)->group(function (): void {
    Route::get('/search', 'search')->name('search');
    Route::get('/trips/{trip}', 'trip')->name('trip.show');
    Route::get('/trips/{trip}/seat-map', 'seatMap')->name('trip.seat-map');
    Route::post('/bookings', 'store')->name('bookings.store');
    Route::get('/bookings/{reference}', 'show')->name('bookings.show');
    Route::post('/bookings/{reference}/cancel', 'cancel')->name('bookings.cancel');
});
