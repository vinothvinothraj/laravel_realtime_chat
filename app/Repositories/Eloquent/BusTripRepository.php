<?php

namespace App\Repositories\Eloquent;

use App\Enums\SeatStatus;
use App\Models\BusTrip;
use App\Models\BusTripSeat;
use App\Repositories\Contracts\BusTripRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class BusTripRepository implements BusTripRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return BusTrip::query()
            ->with(['route.operator', 'bus.operator'])
            ->withCount('bookings')
            ->orderByDesc('departure_at')
            ->paginate($perPage);
    }

    public function all(): Collection
    {
        return BusTrip::query()
            ->with(['route.operator', 'bus.operator'])
            ->orderByDesc('departure_at')
            ->get();
    }

    public function findOrFail(int $id): BusTrip
    {
        return BusTrip::query()->with(['route.operator', 'bus.operator', 'seats.seat', 'bookings'])->findOrFail($id);
    }

    public function create(array $data): BusTrip
    {
        return BusTrip::create($data);
    }

    public function update(BusTrip $trip, array $data): BusTrip
    {
        $trip->fill($data)->save();

        return $trip;
    }

    public function delete(BusTrip $trip): void
    {
        $trip->delete();
    }

    public function searchTrips(array $filters): Collection
    {
        return BusTrip::query()
            ->with(['route.operator', 'bus.operator', 'seats.seat'])
            ->when($filters['origin'] ?? null, function ($query, $origin): void {
                $query->whereHas('route', fn ($routeQuery) => $routeQuery->where('origin', 'like', "%{$origin}%"));
            })
            ->when($filters['destination'] ?? null, function ($query, $destination): void {
                $query->whereHas('route', fn ($routeQuery) => $routeQuery->where('destination', 'like', "%{$destination}%"));
            })
            ->when($filters['travel_date'] ?? null, fn ($query, $travelDate) => $query->whereDate('departure_at', $travelDate))
            ->orderBy('departure_at')
            ->get();
    }

    public function seatMap(BusTrip $trip): Collection
    {
        $this->syncSeatInventory($trip);

        return BusTripSeat::query()
            ->with('seat')
            ->where('bus_trip_id', $trip->id)
            ->orderBy('id')
            ->get();
    }

    public function syncSeatInventory(BusTrip $trip): void
    {
        $trip->loadMissing('bus.seats');

        foreach ($trip->bus->seats as $seat) {
            BusTripSeat::query()->firstOrCreate(
                [
                    'bus_trip_id' => $trip->id,
                    'bus_seat_id' => $seat->id,
                ],
                [
                    'status' => SeatStatus::Available->value,
                ]
            );
        }
    }
}
