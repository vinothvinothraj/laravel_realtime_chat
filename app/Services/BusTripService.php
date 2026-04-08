<?php

namespace App\Services;

use App\Models\BusTrip;
use App\Repositories\Contracts\BusTripRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class BusTripService
{
    public function __construct(
        protected BusTripRepositoryInterface $trips
    ) {
    }

    public function paginate(): LengthAwarePaginator
    {
        return $this->trips->paginate();
    }

    public function all(): Collection
    {
        return $this->trips->all();
    }

    public function findOrFail(int $id): BusTrip
    {
        return $this->trips->findOrFail($id);
    }

    public function create(array $data): BusTrip
    {
        return $this->trips->create($data);
    }

    public function update(BusTrip $trip, array $data): BusTrip
    {
        return $this->trips->update($trip, $data);
    }

    public function delete(BusTrip $trip): void
    {
        $this->trips->delete($trip);
    }
}
