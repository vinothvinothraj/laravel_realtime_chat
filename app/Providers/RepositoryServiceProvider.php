<?php

namespace App\Providers;

use App\Repositories\Contracts\MessageRepositoryInterface;
use App\Repositories\Eloquent\MessageRepository;
use App\Repositories\Contracts\BusBookingRepositoryInterface;
use App\Repositories\Contracts\BusOperatorRepositoryInterface;
use App\Repositories\Contracts\BusRepositoryInterface;
use App\Repositories\Contracts\BusRouteRepositoryInterface;
use App\Repositories\Contracts\BusTripRepositoryInterface;
use App\Repositories\Eloquent\BusBookingRepository;
use App\Repositories\Eloquent\BusOperatorRepository;
use App\Repositories\Eloquent\BusRepository;
use App\Repositories\Eloquent\BusRouteRepository;
use App\Repositories\Eloquent\BusTripRepository;
use App\Repositories\Contracts\RoomRepositoryInterface;
use App\Repositories\Eloquent\RoomRepository;
use App\Repositories\Contracts\TaskRepositoryInterface;
use App\Repositories\Eloquent\TaskRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(MessageRepositoryInterface::class, MessageRepository::class);
        $this->app->bind(RoomRepositoryInterface::class, RoomRepository::class);
        $this->app->bind(TaskRepositoryInterface::class, TaskRepository::class);
        $this->app->bind(BusOperatorRepositoryInterface::class, BusOperatorRepository::class);
        $this->app->bind(BusRouteRepositoryInterface::class, BusRouteRepository::class);
        $this->app->bind(BusRepositoryInterface::class, BusRepository::class);
        $this->app->bind(BusTripRepositoryInterface::class, BusTripRepository::class);
        $this->app->bind(BusBookingRepositoryInterface::class, BusBookingRepository::class);
    }
}
