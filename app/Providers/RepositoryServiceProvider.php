<?php

namespace App\Providers;

use App\Repositories\Contracts\MessageRepositoryInterface;
use App\Repositories\Eloquent\MessageRepository;
use App\Repositories\Contracts\RoomRepositoryInterface;
use App\Repositories\Eloquent\RoomRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(MessageRepositoryInterface::class, MessageRepository::class);
        $this->app->bind(RoomRepositoryInterface::class, RoomRepository::class);
    }
}
