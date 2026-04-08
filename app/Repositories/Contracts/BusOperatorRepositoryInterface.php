<?php

namespace App\Repositories\Contracts;

use App\Models\BusOperator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface BusOperatorRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    public function all(): Collection;

    public function findOrFail(int $id): BusOperator;

    public function create(array $data): BusOperator;

    public function update(BusOperator $operator, array $data): BusOperator;

    public function delete(BusOperator $operator): void;
}
