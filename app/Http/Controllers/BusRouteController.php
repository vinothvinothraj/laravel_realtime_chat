<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBusRouteRequest;
use App\Http\Requests\UpdateBusRouteRequest;
use App\Models\BusRoute;
use App\Services\BusOperatorService;
use App\Services\BusRouteService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BusRouteController extends Controller
{
    public function __construct(
        protected BusRouteService $routes,
        protected BusOperatorService $operators
    ) {
    }

    public function index(): View
    {
        return view('bus-booking.routes.index', [
            'routes' => $this->routes->paginate(),
        ]);
    }

    public function create(): View
    {
        return view('bus-booking.routes.create', [
            'operators' => $this->operators->all(),
        ]);
    }

    public function store(StoreBusRouteRequest $request): RedirectResponse
    {
        $this->routes->create($request->validated());

        return redirect()->route('bus-booking.routes.index');
    }

    public function edit(BusRoute $route): View
    {
        return view('bus-booking.routes.edit', [
            'route' => $route->load('operator'),
            'operators' => $this->operators->all(),
        ]);
    }

    public function show(BusRoute $route): View
    {
        return view('bus-booking.routes.show', [
            'route' => $route->load(['operator', 'trips']),
        ]);
    }

    public function update(UpdateBusRouteRequest $request, BusRoute $route): RedirectResponse
    {
        $this->routes->update($route, $request->validated());

        return redirect()->route('bus-booking.routes.index');
    }

    public function destroy(BusRoute $route): RedirectResponse
    {
        $this->routes->delete($route);

        return redirect()->route('bus-booking.routes.index');
    }
}
