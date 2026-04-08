<?php

namespace App\Repositories\Contracts;

use App\Models\BusTrip;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface BusTripRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    public function all(): Collection;

    public function findOrFail(int $id): BusTrip;

    public function create(array $data): BusTrip;

    public function update(BusTrip $trip, array $data): BusTrip;

    public function delete(BusTrip $trip): void;

    public function searchTrips(array $filters): Collection;

    public function seatMap(BusTrip $trip): Collection;

    public function syncSeatInventory(BusTrip $trip): void;
}
