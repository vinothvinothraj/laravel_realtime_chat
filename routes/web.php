<?php

use App\Http\Controllers\TaskController;
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
