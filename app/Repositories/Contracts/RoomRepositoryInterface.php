<?php

namespace App\Repositories\Contracts;

use App\Models\Room;
use Illuminate\Support\Collection;

interface RoomRepositoryInterface
{
    public function forUser(int $userId): Collection;

    public function findOrCreateDirectRoom(int $currentUserId, int $otherUserId): Room;

    public function createGroup(string $name, int $ownerId, array $participants): Room;
}
