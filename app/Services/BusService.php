<?php

namespace App\Services;

use App\Models\Bus;
use App\Repositories\Contracts\BusRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class BusService
{
    public function __construct(
        protected BusRepositoryInterface $buses
    ) {
    }

    public function paginate(): LengthAwarePaginator
    {
        return $this->buses->paginate();
    }

    public function all(): Collection
    {
        return $this->buses->all();
    }

    public function findOrFail(int $id): Bus
    {
        return $this->buses->findOrFail($id);
    }

    public function create(array $data): Bus
    {
        return $this->buses->create($data);
    }

    public function update(Bus $bus, array $data): Bus
    {
        return $this->buses->update($bus, $data);
    }

    public function delete(Bus $bus): void
    {
        $this->buses->delete($bus);
    }
}
