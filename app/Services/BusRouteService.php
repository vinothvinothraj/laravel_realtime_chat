<?php

namespace App\Services;

use App\Models\BusRoute;
use App\Repositories\Contracts\BusRouteRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class BusRouteService
{
    public function __construct(
        protected BusRouteRepositoryInterface $routes
    ) {
    }

    public function paginate(): LengthAwarePaginator
    {
        return $this->routes->paginate();
    }

    public function all(): Collection
    {
        return $this->routes->all();
    }

    public function findOrFail(int $id): BusRoute
    {
        return $this->routes->findOrFail($id);
    }

    public function create(array $data): BusRoute
    {
        return $this->routes->create($data);
    }

    public function update(BusRoute $route, array $data): BusRoute
    {
        return $this->routes->update($route, $data);
    }

    public function delete(BusRoute $route): void
    {
        $this->routes->delete($route);
    }
}
