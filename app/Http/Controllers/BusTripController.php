<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBusTripRequest;
use App\Http\Requests\UpdateBusTripRequest;
use App\Models\BusTrip;
use App\Services\BusRouteService;
use App\Services\BusService;
use App\Services\BusTripService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BusTripController extends Controller
{
    public function __construct(
        protected BusTripService $trips,
        protected BusRouteService $routes,
        protected BusService $buses
    ) {
    }

    public function index(): View
    {
        return view('bus-booking.trips.index', [
            'trips' => $this->trips->paginate(),
        ]);
    }

    public function create(): View
    {
        return view('bus-booking.trips.create', [
            'routes' => $this->routes->all(),
            'buses' => $this->buses->all(),
        ]);
    }

    public function store(StoreBusTripRequest $request): RedirectResponse
    {
        $this->trips->create($request->validated());

        return redirect()->route('bus-booking.trips.index');
    }

    public function edit(BusTrip $trip): View
    {
        return view('bus-booking.trips.edit', [
            'trip' => $trip->load(['route.operator', 'bus.operator']),
            'routes' => $this->routes->all(),
            'buses' => $this->buses->all(),
        ]);
    }

    public function show(BusTrip $trip): View
    {
        return view('bus-booking.trips.show', [
            'trip' => $trip->load(['route.operator', 'bus.operator', 'seats.seat', 'bookings.passengers']),
        ]);
    }

    public function update(UpdateBusTripRequest $request, BusTrip $trip): RedirectResponse
    {
        $this->trips->update($trip, $request->validated());

        return redirect()->route('bus-booking.trips.index');
    }

    public function destroy(BusTrip $trip): RedirectResponse
    {
        $this->trips->delete($trip);

        return redirect()->route('bus-booking.trips.index');
    }
}
