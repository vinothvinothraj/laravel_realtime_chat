<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\User;
use Illuminate\Support\Facades\View;
use Livewire\Livewire;
use App\Http\Livewire\ChatRoom;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->register(RepositoryServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Livewire::component('chat-room', ChatRoom::class);

        View::composer('layouts.app', function ($view) {
            $view->with('sidebarOtherUsers', collect());
        });
        View::composer('livewire.chat-room', function ($view) {
            $user = auth()->user();
            $view->with('participants', $user ? User::where('id', '!=', $user->id)->get() : collect());
        });
    }
}
