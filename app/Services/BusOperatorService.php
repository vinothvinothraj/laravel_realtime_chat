<?php

namespace App\Services;

use App\Models\BusOperator;
use App\Repositories\Contracts\BusOperatorRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class BusOperatorService
{
    public function __construct(
        protected BusOperatorRepositoryInterface $operators
    ) {
    }

    public function paginate(): LengthAwarePaginator
    {
        return $this->operators->paginate();
    }

    public function all(): Collection
    {
        return $this->operators->all();
    }

    public function findOrFail(int $id): BusOperator
    {
        return $this->operators->findOrFail($id);
    }

    public function create(array $data): BusOperator
    {
        return $this->operators->create($data);
    }

    public function update(BusOperator $operator, array $data): BusOperator
    {
        return $this->operators->update($operator, $data);
    }

    public function delete(BusOperator $operator): void
    {
        $this->operators->delete($operator);
    }
}
