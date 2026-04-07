<?php

namespace App\Repositories\Eloquent;

use App\Models\Room;
use App\Models\User;
use App\Repositories\Contracts\RoomRepositoryInterface;
use Illuminate\Support\Collection;

class RoomRepository implements RoomRepositoryInterface
{
    public function forUser(int $userId): Collection
    {
        return Room::whereHas('participants', fn ($query) => $query->where('user_id', $userId))
            ->with(['participants'])
            ->get();
    }

    public function findOrCreateDirectRoom(int $currentUserId, int $otherUserId): Room
    {
        $existing = Room::where('is_group', false)
            ->whereHas('participants', fn ($query) => $query->where('user_id', $currentUserId))
            ->whereHas('participants', fn ($query) => $query->where('user_id', $otherUserId))
            ->withCount('participants')
            ->having('participants_count', 2)
            ->first();

        if ($existing) {
            return $existing;
        }

        $room = Room::create([
            'is_group' => false,
            'created_by' => $currentUserId,
        ]);

        $room->participants()->attach([$currentUserId, $otherUserId]);

        return $room;
    }

    public function createGroup(string $name, int $ownerId, array $participants): Room
    {
        $room = Room::create([
            'name' => $name,
            'is_group' => true,
            'created_by' => $ownerId,
        ]);

        $room->participants()->attach(array_unique(array_merge([$ownerId], $participants)));

        return $room;
    }
}
