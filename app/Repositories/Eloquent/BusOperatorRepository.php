<?php

namespace App\Repositories\Eloquent;

use App\Models\BusOperator;
use App\Repositories\Contracts\BusOperatorRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class BusOperatorRepository implements BusOperatorRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return BusOperator::query()
            ->withCount(['routes', 'buses'])
            ->orderBy('name')
            ->paginate($perPage);
    }

    public function all(): Collection
    {
        return BusOperator::query()
            ->orderBy('name')
            ->get();
    }

    public function findOrFail(int $id): BusOperator
    {
        return BusOperator::query()->findOrFail($id);
    }

    public function create(array $data): BusOperator
    {
        return BusOperator::create($data);
    }

    public function update(BusOperator $operator, array $data): BusOperator
    {
        $operator->fill($data)->save();

        return $operator;
    }

    public function delete(BusOperator $operator): void
    {
        $operator->delete();
    }
}
