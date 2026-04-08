<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\BusBookingService;
use App\Services\BusOperatorService;
use App\Services\BusRouteService;
use App\Services\BusService;
use App\Services\BusTripService;
use Illuminate\View\View;

class BusBookingDashboardController extends Controller
{
    public function __construct(
        protected BusOperatorService $operators,
        protected BusRouteService $routes,
        protected BusService $buses,
        protected BusTripService $trips,
        protected BusBookingService $bookings
    ) {
    }

    public function index(): View
    {
        return view('bus-booking.dashboard', [
            'summary' => [
                'operators' => $this->operators->all()->count(),
                'routes' => $this->routes->all()->count(),
                'buses' => $this->buses->all()->count(),
                'trips' => $this->trips->all()->count(),
                'bookings' => Booking::query()->count(),
            ],
        ]);
    }
}
