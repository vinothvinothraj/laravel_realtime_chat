<?php

namespace App\Repositories\Contracts;

use App\Models\Bus;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface BusRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    public function all(): Collection;

    public function findOrFail(int $id): Bus;

    public function create(array $data): Bus;

    public function update(Bus $bus, array $data): Bus;

    public function delete(Bus $bus): void;
}
