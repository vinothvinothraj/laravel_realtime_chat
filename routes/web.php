<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\BusBookingController;
use App\Http\Controllers\BusBookingDashboardController;
use App\Http\Controllers\BusController;
use App\Http\Controllers\BusOperatorController;
use App\Http\Controllers\BusRouteController;
use App\Http\Controllers\BusTripController;
use App\Services\PusherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/chat', function () {
        return view('chat');
    })->name('chat');

    Route::controller(TaskController::class)->group(function () {
        Route::get('/tasks', 'index')->name('tasks.index');
        Route::post('/tasks', 'store')->name('tasks.store');
        Route::patch('/tasks/{task}/move', 'move')->name('tasks.move');
    });

    Route::prefix('admin/bus-booking')
        ->name('bus-booking.')
        ->group(function (): void {
        Route::get('/', [BusBookingDashboardController::class, 'index'])->name('dashboard');

        Route::resource('operators', BusOperatorController::class)->parameters([
            'operators' => 'operator',
        ]);

        Route::resource('routes', BusRouteController::class)->parameters([
            'routes' => 'route',
        ]);

        Route::resource('buses', BusController::class)->parameters([
            'buses' => 'bus',
        ]);

        Route::resource('trips', BusTripController::class)->parameters([
            'trips' => 'trip',
        ]);

        Route::get('bookings', [BusBookingController::class, 'index'])->name('bookings.index');
        Route::get('bookings/{booking}', [BusBookingController::class, 'show'])->name('bookings.show');
    });

    Route::get('/test-pusher', function () {
        return view('test-pusher');
    })->name('test-pusher');

    Route::post('/test-pusher/trigger', function (Request $request) {
        app(PusherService::class)->trigger('test-pusher', 'my-event', [
            'message' => 'Hello world from the Laravel test route!',
            'sender' => auth()->user()?->only(['id', 'name']),
        ]);

        return back()->with('status', 'Test payload dispatched.');
    })->name('test-pusher.trigger');
});
