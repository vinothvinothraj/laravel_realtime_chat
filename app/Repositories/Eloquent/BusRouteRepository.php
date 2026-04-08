<?php

namespace App\Repositories\Eloquent;

use App\Models\BusRoute;
use App\Repositories\Contracts\BusRouteRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class BusRouteRepository implements BusRouteRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return BusRoute::query()
            ->with('operator')
            ->withCount('trips')
            ->orderBy('origin')
            ->paginate($perPage);
    }

    public function all(): Collection
    {
        return BusRoute::query()
            ->with('operator')
            ->orderBy('origin')
            ->orderBy('destination')
            ->get();
    }

    public function findOrFail(int $id): BusRoute
    {
        return BusRoute::query()->with('operator')->findOrFail($id);
    }

    public function create(array $data): BusRoute
    {
        return BusRoute::create($data);
    }

    public function update(BusRoute $route, array $data): BusRoute
    {
        $route->fill($data)->save();

        return $route;
    }

    public function delete(BusRoute $route): void
    {
        $route->delete();
    }
}
