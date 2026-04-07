<?php

use App\Events\TestPusherEvent;
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

    Route::get('/test-pusher', function () {
        return view('test-pusher');
    })->name('test-pusher');

    Route::post('/test-pusher/trigger', function (Request $request) {
        broadcast(new TestPusherEvent([
            'message' => 'Hello world from the Laravel test route!',
            'sender' => auth()->user()?->only(['id', 'name']),
        ]));

        return back()->with('status', 'Test payload dispatched.');
    })->name('test-pusher.trigger');
});
