<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBusRequest;
use App\Http\Requests\UpdateBusRequest;
use App\Models\Bus;
use App\Services\BusOperatorService;
use App\Services\BusService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BusController extends Controller
{
    public function __construct(
        protected BusService $buses,
        protected BusOperatorService $operators
    ) {
    }

    public function index(): View
    {
        return view('bus-booking.buses.index', [
            'buses' => $this->buses->paginate(),
        ]);
    }

    public function create(): View
    {
        return view('bus-booking.buses.create', [
            'operators' => $this->operators->all(),
        ]);
    }

    public function store(StoreBusRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['seat_layout'] = $data['seat_layout'] ? json_decode($data['seat_layout'], true) : null;
        $this->buses->create($data);

        return redirect()->route('bus-booking.buses.index');
    }

    public function edit(Bus $bus): View
    {
        return view('bus-booking.buses.edit', [
            'bus' => $bus->load('operator'),
            'operators' => $this->operators->all(),
        ]);
    }

    public function show(Bus $bus): View
    {
        return view('bus-booking.buses.show', [
            'bus' => $bus->load(['operator', 'seats', 'trips']),
        ]);
    }

    public function update(UpdateBusRequest $request, Bus $bus): RedirectResponse
    {
        $data = $request->validated();
        $data['seat_layout'] = $data['seat_layout'] ? json_decode($data['seat_layout'], true) : null;
        $this->buses->update($bus, $data);

        return redirect()->route('bus-booking.buses.index');
    }

    public function destroy(Bus $bus): RedirectResponse
    {
        $this->buses->delete($bus);

        return redirect()->route('bus-booking.buses.index');
    }
}
