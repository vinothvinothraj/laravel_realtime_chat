<?php

namespace App\Repositories\Eloquent;

use App\Models\Bus;
use App\Repositories\Contracts\BusRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class BusRepository implements BusRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Bus::query()
            ->with('operator')
            ->withCount(['seats', 'trips'])
            ->orderBy('name')
            ->paginate($perPage);
    }

    public function all(): Collection
    {
        return Bus::query()
            ->with('operator')
            ->orderBy('name')
            ->get();
    }

    public function findOrFail(int $id): Bus
    {
        return Bus::query()->with(['operator', 'seats'])->findOrFail($id);
    }

    public function create(array $data): Bus
    {
        return Bus::create($data);
    }

    public function update(Bus $bus, array $data): Bus
    {
        $bus->fill($data)->save();

        return $bus;
    }

    public function delete(Bus $bus): void
    {
        $bus->delete();
    }
}
