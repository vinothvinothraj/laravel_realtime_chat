<?php

namespace App\Repositories\Contracts;

use App\Models\BusRoute;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface BusRouteRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    public function all(): Collection;

    public function findOrFail(int $id): BusRoute;

    public function create(array $data): BusRoute;

    public function update(BusRoute $route, array $data): BusRoute;

    public function delete(BusRoute $route): void;
}
