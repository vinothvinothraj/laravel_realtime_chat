<?php

namespace App\Repositories\Contracts;

use App\Models\Message;
use Illuminate\Support\Collection;

interface MessageRepositoryInterface
{
    public function latestForRoom(int $roomId, int $limit = 50): Collection;

    public function create(array $data): Message;
}
