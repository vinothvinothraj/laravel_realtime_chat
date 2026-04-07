<?php

namespace App\Repositories\Eloquent;

use App\Models\Message;
use App\Repositories\Contracts\MessageRepositoryInterface;
use Illuminate\Support\Collection;

class MessageRepository implements MessageRepositoryInterface
{
    public function latestForRoom(int $roomId, int $limit = 50): Collection
    {
        return Message::where('room_id', $roomId)
            ->latest()
            ->limit($limit)
            ->get()
            ->reverse()
            ->values();
    }

    public function create(array $data): Message
    {
        return Message::create($data);
    }
}
